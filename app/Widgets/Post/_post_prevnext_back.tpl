<li class="block"
    {if="$post->isMicroblog()"}
        onclick="MovimUtils.reload('{$c->route('contact', $post->server)}')"
    {else}
        onclick="MovimUtils.reload('{$c->route('community', [$post->server, $post->node])}')"
    {/if}
    >
    <span class="control icon gray">
        <i class="material-symbols">expand_less</i>
    </span>
    {if="$post->isMicroblog()"}
        {if="$post->contact"}
            <span class="primary icon bubble">
                <img src="{$post->contact->getPicture(\Movim\ImageSize::M)}">
            </span>
            <div>
                <p class="normal line">
                    {$post->contact->truename}
                </p>
        {else}
            <span class="primary icon bubble color {$post->server|stringToColor}">
                <i class="material-symbols">person</i>
            </span>
            <div>
                <p class="normal line">
                    {$post->server}
                </p>
        {/if}
            <p class="line">
                {$post->server}
            </p>
        </div>
    {else}
        {if="$info"}
            <span class="primary icon thumb">
                <img src="{$info->getPicture(\Movim\ImageSize::M)}"/>
            </span>
            <div>
                <p class="line two">
                    {if="$info->name"}
                        {$info->name}
                    {else}
                        {$info->node}
                    {/if}
                </p>
                {if="$info->description"}
                    <p dir="auto">{$info->description|strip_tags}</p>
                {/if}
            </div>
        {else}
            <span class="primary icon bubble color {$post->node|stringToColor}">
                {$post->node|firstLetterCapitalize}
            </span>
            <div>
                <p class="line two">{$post->node}</p>
                <p dir="auto">{$post->server}</p>
            </div>
        {/if}
    {/if}
</li>
