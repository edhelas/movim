<?php

use Moxl\Xec\Action\Pubsub\PostPublish;
use Moxl\Xec\Action\Microblog\CommentCreateNode;
use Moxl\Xec\Action\Pubsub\Subscribe;

use Movim\Session;
use Movim\Cache;

use Michelf\MarkdownExtra;
use Respect\Validation\Validator;


class Publish extends \Movim\Widget\Base
{
    function load()
    {
        $this->addjs('publish.js');
        $this->addcss('publish.css');
        $this->registerEvent('pubsub_postpublish_handle', 'onPublish', 'publish');
        $this->registerEvent('microblog_commentcreatenode_handle', 'onCommentNodeCreated');
    }

    function onPublish($packet)
    {
        Notification::append(false, $this->__('post.published'));

        list($to, $node, $id, $repost, $comments) = array_values($packet->content);

        if(!$repost && $comments) {
            $this->ajaxCreateComments(($comments === true) ? $to : $comments, $id);
        }

        if($node == 'urn:xmpp:microblog:0') {
            $this->rpc('MovimUtils.redirect', $this->route('news'));
        } else {
            $this->rpc('MovimUtils.redirect', $this->route('community', [$to, $node]));
        }
    }

    function onCommentNodeCreated($packet)
    {
        list($server, $parentid) = array_values($packet->content);

        $s = new Subscribe;
        $s->setTo($server)
          ->setFrom($this->user->getLogin())
          ->setNode('urn:xmpp:microblog:0:comments/'.$parentid)
          ->request();
    }

    function ajaxReply($server, $node, $id)
    {
        $this->ajaxCreate($server, $node, $id, true);
    }

    function ajaxCreate($server = false, $node = false, $id = false, $reply = false)
    {
        if($server == false
        && $node == false) {
            $server = $this->user->getLogin();
            $node = 'urn:xmpp:microblog:0';
        }

        if(!$this->validateServerNode($server, $node)) return;

        $post = false;

        $view = $this->tpl();

        if($id) {
            $pd = new \Modl\PostnDAO;
            $p = $pd->get($server, $node, $id);

            if($p) {
                if($p->isEditable() && !$reply) {
                    $post = $p;
                }

                if($p->isReply()) {
                    $reply = $p->getReply();
                } elseif($reply) {
                    $reply = $p;
                }
            }
        }

        if($reply) {
            $view->assign('to', $this->user->getLogin());
            $view->assign('node', 'urn:xmpp:microblog:0');
            $view->assign('item', $post);
            $view->assign('reply', $reply);
        } else {
            $view->assign('to', $server);
            $view->assign('node', $node);
            $view->assign('item', $post);
            $view->assign('reply', false);
        }

        $session = Session::start();
        $view->assign('url', $session->get('share_url'));
        $view->assign('draft', Cache::c('draft'));

        $this->rpc('MovimTpl.fill', '#publish', $view->draw('_publish_create', true));

        if($id) {
            $this->rpc('Publish.initEdit');
        }

        if($session->get('share_url') || is_object(Cache::c('draft'))) {
            $this->rpc('Publish.setEmbed');
        }

        $this->rpc('Publish.initDraftBehavior');
    }

    function ajaxCreateComments($server, $id)
    {
        if(!$this->validateServerNode($server, $id)) return;

        $cn = new CommentCreateNode;
        $cn->setTo($server)
           ->setParentId($id)
           ->request();
    }

    function ajaxFormFilled($server, $node)
    {
        $view = $this->tpl();

        $view->assign('server', $server);
        $view->assign('node', $node);

        Dialog::fill($view->draw('_publish_back_confirm', true));
    }

    function ajaxPreview($form)
    {
        if($form->content->value != '') {
            $view = $this->tpl();

            $doc = new DOMDocument;

            $parser = new MarkdownExtra;
            $parser->hashtag_protection = true;

            $doc->loadXML('<div>'.addHFR(addHFR($parser->transform($form->content->value))).'</div>');
            $view->assign('content', substr($doc->saveXML($doc->getElementsByTagName('div')->item(0)), 5, -6));

            Dialog::fill($view->draw('_publish_preview', true), true);
        } else {
            Notification::append(false, $this->__('publish.no_content_preview'));
        }
    }

    function ajaxClearShareUrl()
    {
        $session = Session::start();
        $session->remove('share_url');

        $this->rpc('Publish.clearEmbed');
    }

    function ajaxHelp()
    {
        $view = $this->tpl();
        Dialog::fill($view->draw('_publish_help', true), true);
    }

    /*function ajaxRepost($server, $node, $id)
    {
        if(!$this->validateServerNode($server, $node)) return;

        $pd = new \modl\PostnDAO();
        $post = $pd->get($server, $node, $id);

        if($post) {
            $attachments = $post->getAttachments();

            $p = new PostPublish;

            if($post->aid) $p->setFrom($post->aid);
            else           $p->setFrom($post->origin);

            $p->setTo($this->user->getLogin())
              ->setTitle($post->title)
              ->setNode('urn:xmpp:microblog:0')
              ->setContent($post->contentraw)
              ->setContentXhtml($post->content)
              ->enableComments()
              ->setTags($post->getTags())
              ->setRepost([$post->origin, $post->node, $post->nodeid]);

            if(isset($attachments['links'])) {
                $p->setLink($attachments['links'][0]['href']);
            }

            if(isset($attachments['pictures'])) {
                $p->setImage(
                    $attachments['pictures'][0]['href'],
                    $attachments['pictures'][0]['title'],
                    $attachments['pictures'][0]['type']);
            }

            $p->request();
        }
    }*/

    function ajaxSaveDraft($form)
    {
        $p = new \Modl\Postn;
        $p->title = $form->title->value;
        $p->content = $form->content->value;
        array_push($p->links, $form->embed->value);

        Cache::c('draft', $p);
    }

    function ajaxPublish($form)
    {
        $this->rpc('Publish.disableSend');

        Cache::c('draft', null);

        if(Validator::stringType()->notEmpty()->validate(trim($form->title->value))) {
            $p = new PostPublish;
            $p->setFrom($this->user->getLogin())
              ->setTo($form->to->value)
              ->setTitle(htmlspecialchars($form->title->value))
              ->setNode($form->node->value);
              //->setLocation($geo)

            $content = $content_xhtml = '';

            $tags = [];

            $tagsTitle = getHashtags(htmlspecialchars($form->title->value));
            if(is_array($tagsTitle)) $tags += $tagsTitle;

            if(Validator::stringType()->notEmpty()->validate(trim($form->content->value))) {
                $content = $form->content->value;

                $parser = new MarkdownExtra;
                $parser->hashtag_protection = true;
                $content_xhtml = addHFR($parser->transform($content));

                $tagsContent = getHashtags(htmlspecialchars($form->content->value));
                if(is_array($tagsContent)) $tags = array_merge($tags, $tagsContent);
            }

            if(Validator::stringType()->notEmpty()->validate(trim($form->id->value))) {
                $p->setId($form->id->value);

                $pd = new \Modl\PostnDAO;
                $post = $pd->get($form->to->value, $form->node->value, $form->id->value);

                if(isset($post)) {
                    $p->setPublished(strtotime($post->published));
                }
            }

            if(Validator::notEmpty()->url()->validate($form->embed->value)) {
                try {
                    $murl = new \Modl\Url;
                    $embed = $murl->resolve($form->embed->value);
                    $p->setLink($form->embed->value);

                    if($embed->type == 'photo' || isset($embed->images)) {
                        $p->setImage($embed->images[0]['url'],
                                     $embed->title,
                                     $embed->images[0]['mime']);
                    }

                    $p->setLink($form->embed->value,
                                $embed->title,
                                'text/html',
                                $embed->description,
                                $embed->providerIcon);
                } catch(Exception $e) {
                    error_log($e->getMessage());
                }
            }

            if($form->open->value === true) {
                $p->isOpen();
            }

            $cd = new \Modl\CapsDAO;
            $comments = $cd->getComments($this->user->getServer());

            if($comments) {
                $p->enableComments($comments->node);
            } else {
                $p->enableComments();
            }

            if($content != '') {
                $p->setContent(htmlspecialchars($content));
            }

            if($content_xhtml != '') {
                $p->setContentXhtml($content_xhtml);
            }

            if($form->reply->value) {
                $pd = new \Modl\PostnDAO;
                $post = $pd->get($form->replyorigin->value,
                                 $form->replynode->value,
                                 $form->replynodeid->value);
                $p->setReply($post->getRef());
            }

            $p->setTags($tags);

            $session = Session::start();
            $session->remove('share_url');

            $p->request();
        } else {
            $this->rpc('Publish.enableSend');
            Notification::append(false, $this->__('publish.no_title'));
        }
    }

    function ajaxEmbedTest($url)
    {
        if($url == '') {
            return;
        }

        if(!filter_var($url, FILTER_VALIDATE_URL)) {
            Notification::append(false, $this->__('publish.valid_url'));
            return;
        }

        try {
            $murl = new \Modl\Url;
            $embed = $murl->resolve($url);
            $html = $this->prepareEmbed($embed);

            $this->rpc('MovimTpl.fill', '#preview', '');
            $this->rpc('MovimTpl.fill', '#gallery', '');
            $this->rpc('MovimTpl.fill', '#preview', $html);
        } catch(Exception $e) {
            error_log($e->getMessage());
        }
    }

    function prepareEmbed($embed)
    {
        $view = $this->tpl();
        $view->assign('embed', $embed);
        return $view->draw('_publish_embed', true);
    }

    private function validateServerNode($server, $node)
    {
        $validate_server = Validator::stringType()->noWhitespace()->length(6, 40);
        $validate_node = Validator::stringType()->length(3, 100);

        return ($validate_server->validate($server)
             && $validate_node->validate($node));
    }
}
