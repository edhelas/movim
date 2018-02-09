<div id="login_widget">
    <script type="text/javascript">
        /*if(typeof navigator.registerProtocolHandler == 'function') {
            navigator.registerProtocolHandler('xmpp',
                                          '{$c->route("share")}/%s',
                                          'Movim');
        }*/

        Login.domain = '{$domain}';
        {if="isset($httpAuthUser)"}
            localStorage.username = '{$httpAuthUser}';
            MovimWebsocket.attach(function() {
                MovimWebsocket.connection.register('{$httpAuthHost}');
            });
            MovimWebsocket.register(function() {
                Login_ajaxHTTPLogin('{$httpAuthUser}', '{$httpAuthPassword}');
            });
        {/if}
    </script>

    <div id="form" class="dialog">
        <section>
            <span class="info">{$c->__('form.connected')} {$connected} / {$pop}</span>
            <h3>{$c->__('page.login')}</h3>
            {if="$invitation != null"}
                <br />
                <ul class="list middle">
                    <li>
                        {$url = $contact->getPhoto('m')}
                        {if="$url"}
                            <span class="primary icon bubble" style="background-image: url({$url});">
                            </span>
                        {else}
                            <span class="primary icon bubble color {$contact->jid|stringToColor}">
                                {$contact->getTrueName()|firstLetterCapitalize}
                            </span>
                        {/if}
                        <p class="line">{$c->__('form.invite_chatroom', $contact->getTrueName())}</p>
                        <p class="line">{$invitation->resource}</p>
                    </li>
                </ul>
            {/if}

            <form
                method="post" action="login"
                name="login">
                <div>
                    <input type="text" id="complete" tabindex="-1"/>
                    <input type="text" pattern="^[^\u0000-\u001f\u0020\u0022\u0026\u0027\u002f\u003a\u003c\u003e\u0040\u007f\u0080-\u009f\u00a0]+@[a-z0-9.-]+\.[a-z]{2,10}$" name="username" id="username" autofocus required
                        placeholder="username@server.com"/>
                    <label for="username">{$c->__('form.username')}</label>
                </div>
                <div>
                    <input type="password" name="password" id="password" required
                        placeholder="{$c->__('form.password')}"/>
                    <label for="password">{$c->__('form.password')}</label>
                </div>
                <div>
                    <ul class="list thin">
                        <li>
                            <p class="center">
                                <input
                                    type="submit"
                                    disabled
                                    data-loading="{$c->__('button.connecting')}…"
                                    value="{$c->__('page.login')}"
                                    class="button flat"/>
                            </p>
                        </li>
                    </ul>
                </div>
            </form>

            {if="isset($info) && $info != ''"}
            <ul class="list thin card">
                <li class="info">
                    <p></p>
                    <p class="center normal">{$info|addUrls}</p>
                </li>
            </ul>
            {/if}

            {if="isset($whitelist) && $whitelist != ''"}
            <ul class="list thin">
                <li class="info">
                    <p></p>
                    <p class="center normal">{$c->__('form.whitelist_info')} : {$whitelist}</p>
                </li>
            </ul>
            {/if}

            <ul class="list thin">
                <li>
                    <p class="normal center">
                        {$c->__('form.no_account')}
                        <a class="button flat" href="{$c->route('account')}">
                            {$c->__('form.create_one')}
                        </a>
                    </p>
                </li>
            </ul>
        </section>
    </div>

    <div id="error" class="dialog actions">
        {$error}
    </div>
</div>

