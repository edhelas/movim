{loop="$reactions"}
    <li {if="$message->isMuc()"}title="{$value|implodeCsv}"{/if}
        {if="in_array($me, $value)"}class="reacted"{/if}
        onclick="Chat_ajaxHttpDaemonSendReaction('{$message->mid}', '{$key}')">
        {autoescape="off"}
            {$key|addEmojis:true}
        {/autoescape}
        {$value|count}
    </li>
{/loop}

{if="!empty($reactions)"}
    <li onclick="Stickers_ajaxReaction('{$message->mid}')" title="{$c->__('message.react')}">
        <i class="material-icons">add_reaction</i>
    </li>
{/if}