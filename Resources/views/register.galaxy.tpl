{extends file="layouts/main.galaxy.tpl"}
{block name=title}Register{/block}
{block name=main_content}
    <main id="register">
        <div class="default-form">
            <a href="{$home_url}">
                <img id="vortex_logo" src="{content('img/vortex.png')}" alt="">
            </a>
            <form method="post" action="{route('register.post')}" enctype="multipart/form-data">
                {csrf()}
                <label>
                    <input name="name" type="text" value="{old('name')}" placeholder="Name">
                </label>

                <label>
                    <input name="email" type="email" value="{old('email')}" placeholder="Email">
                </label>

                <label>
                    <input name="password" type="password" value="{old('password')}" placeholder="Password">
                </label>
                <input name="vortex_redirect" type="hidden" value="{$smarty.get.LAST_ROUTE}">
                <button type="submit">REGISTER</button>
            </form>
        </div>
    </main>
    {if hasError()}
        <div class="error-message">
            {foreach from=error() item=errors}
                {foreach from=$errors item=error}
                    <span>
                        {$error}<br>
                    </span>
                {/foreach}
            {/foreach}
        </div>
    {/if}

    <script>
        const message = document.querySelector('.error-message')

        setTimeout(function () {
            message.classList.add('disable')
        }, 6000)
    </script>
{/block}
