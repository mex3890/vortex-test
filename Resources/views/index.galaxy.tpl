{extends file="layouts/main.galaxy.tpl"}
{block name=title}Users list{/block}
{block name=main_content}
    <div id="cards-list">
        {foreach from=$users item=user}
            <div class="card">
                <div class="card-row">
                    <span>ID</span>
                    <span>{$user->id}</span>
                </div>
                <div class="card-row">
                    <span>Name</span>
                    <span>{$user->name}</span>
                </div>
                <div class="card-row">
                    <span>Email</span>
                    <span>{$user->email}</span>
                </div>
            </div>
        {/foreach}

    </div>
    {getPaginationLinks()}
{/block}
