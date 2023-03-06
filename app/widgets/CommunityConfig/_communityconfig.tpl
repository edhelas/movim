<section class="scroll">
    <form id="config" name="config">
        <h3>{$c->__('communityaffiliation.configuration')}</h3>
        {if="$config"}
        <div>
            <input id="pubsub#title" name="pubsub#title" label="{$c->__('general.name')}"
                   placeholder="{$c->__('general.name')}" type="text-single"
                   value="{$config['pubsub#title']}">
            <label for="pubsub#title">{$c->__('general.name')}</label>
        </div>
        <div>
            <textarea id="pubsub#description" name="pubsub#description"
                      data-autoheight="true"
                      placeholder="{$c->__('communityconfig.description')}">{if="isset($config['pubsub#description'])"}{$config['pubsub#description']}{/if}</textarea>
            <label for="pubsub#description">{$c->__('communityconfig.description')}</label>
        </div>
        <div>
            <ul class="list middle labeled fill">
                <li>
                    <span class="control">
                        <div class="radio">
                            <input name="pubsub#publish_model" value="open"
                                id="publish_model_open" type="radio"
                                {if="$config['pubsub#publish_model'] == 'open'"}checked{/if}>
                            <label for="publish_model_open"></label>
                        </div>
                    </span>
                    <div>
                        <p>{$c->__('communityconfig.publish_model_open_title')}</p>
                        <p>{$c->__('communityconfig.publish_model_open_text')}</p>
                    </div>
                </li>
                <li>
                    <span class="control">
                        <div class="radio">
                            <input name="pubsub#publish_model" value="publishers"
                                id="publish_model_publishers" type="radio"
                                {if="$config['pubsub#publish_model'] == 'publishers'"}checked{/if}>
                            <label for="publish_model_publishers"></label>
                        </div>
                    </span>
                    <div>
                        <p>{$c->__('communityconfig.publish_model_publishers_title')}</p>
                        <p>{$c->__('communityconfig.publish_model_publishers_text')}</p>
                    </div>
                </li>
                <li>
                    <span class="control">
                        <div class="radio">
                            <input name="pubsub#publish_model" value="subscribers"
                                id="publish_model_subscribers" type="radio"
                                {if="$config['pubsub#publish_model'] == 'subscribers'"}checked{/if}>
                            <label for="publish_model_subscribers"></label>
                        </div>
                    </span>
                    <div>
                        <p>{$c->__('communityconfig.publish_model_subscribers_title')}</p>
                        <p>{$c->__('communityconfig.publish_model_subscribers_text')}</p>
                    </div>
                </li>
            </ul>
            <label>{$c->__('communityconfig.publication')}</label>
        </div>
        <div>
            <ul class="list middle labeled fill">
                <li>
                    <span class="primary icon gray">
                        <i class="material-icons">view_agenda</i>
                    </span>
                    <span class="control">
                        <div class="radio">
                            <input name="pubsub#type" value="urn:xmpp:pubsub-social-feed:0"
                                id="pubsub_type_feed" type="radio"
                                {if="$config['pubsub#type'] == 'urn:xmpp:pubsub-social-feed:0'"}checked{/if}>
                            <label for="pubsub_type_feed"></label>
                        </div>
                    </span>
                    <div>
                        <p>{$c->__('communityconfig.type_articles_title')}</p>
                        <p>{$c->__('communityconfig.type_articles_text')}</p>
                    </div>
                </li>
                <li>
                    <span class="primary icon gray">
                        <i class="material-icons">grid_view</i>
                    </span>
                    <span class="control">
                        <div class="radio">
                            <input name="pubsub#type" value="urn:xmpp:pubsub-social-gallery:0"
                                id="pubsub_type_gallery" type="radio"
                                {if="$config['pubsub#type'] == 'urn:xmpp:pubsub-social-gallery:0'"}checked{/if}>
                            <label for="pubsub_type_gallery"></label>
                        </div>
                    </span>
                    <div>
                        <p>{$c->__('communityconfig.type_gallery_title')}</p>
                        <p>{$c->__('communityconfig.type_gallery_text')}</p>
                    </div>
                </li>
            </ul>
            <label>{$c->__('communityconfig.type')}</label>
        </div>
        {else}
            {autoescape="off"}
                {$form}
            {/autoescape}
        {/if}
    </form>
</section>
<div>
    {if="$config"}
        <button class="button flat" onclick="CommunityConfig_ajaxGetConfig('{$server|echapJS}', '{$node|echapJS}', true)">
            <i class="material-icons">more_vert</i>
        </button>
    {/if}
    <button onclick="Dialog_ajaxClear()" class="button flat">
        {$c->__('button.close')}
    </button>
    <button onclick="CommunityConfig_ajaxSetConfig(MovimUtils.formToJson('config'), '{$server|echapJS}', '{$node|echapJS}'); Dialog_ajaxClear();"
       class="button flat">
        {$c->__('button.save')}
    </button>
</div>
