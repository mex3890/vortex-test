{extends file="layouts/main.galaxy.tpl"}
{block name=title}Login{/block}
{block name=main_content}
    <main id="login">
        <div class="default-form">
            <a href="#">
                <img id="vortex_logo" src="{content('img/vortex.png')}" alt="">
            </a>
            <form method="post" action="/user/{$user->id}">
                {csrf()}
                <label>
                    <input name="name" type="text" value="{$user->name}" placeholder="Name">
                </label>
                <label>
                    <input name="email" type="email" value="{$user->email}" placeholder="Email">
                </label>
                <button type="submit">UPDATE</button>
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
        let message = document.querySelector('.error-message')

        setTimeout(function () {
            message.classList.add('disable')
        }, 6000)
    </script>
{/block}
