<?php
/* Smarty version 4.3.1, created on 2023-04-08 15:40:13
  from 'C:\laragon\www\vortex-test\vendor\vortex-framework\vortex-framework\Core\Core\error.galaxy.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.1',
  'unifunc' => 'content_6431b50deae8b9_47346511',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '63a5d8489d480ef4068eedaf99f76f391c69ff38' => 
    array (
      0 => 'C:\\laragon\\www\\vortex-test\\vendor\\vortex-framework\\vortex-framework\\Core\\Core\\error.galaxy.tpl',
      1 => 1680847986,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_6431b50deae8b9_47346511 (Smarty_Internal_Template $_smarty_tpl) {
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo content('favicon.ico');?>
">
    <title>Vortex <?php echo is_null($_smarty_tpl->tpl_vars['trace']->value) ? 'Error' : 'Exception';?>
</title>
</head>
<body>
<header>
    <img id="logo" src="<?php echo content('img/vortex.png');?>
" alt="@Vortex logo">
</header>
<div id="sidebar">
    <div class="information-list">
        <?php
$_from = $_smarty_tpl->smarty->ext->_foreach->init($_smarty_tpl, $_smarty_tpl->tpl_vars['parameters']->value, 'value', false, 'key');
$_smarty_tpl->tpl_vars['value']->do_else = true;
if ($_from !== null) foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$_smarty_tpl->tpl_vars['value']->do_else = false;
?>
            <div class="information-row">
                <div>
                    <span><?php echo $_smarty_tpl->tpl_vars['key']->value;?>
</span>
                </div>
                <div>
                    <span><?php echo $_smarty_tpl->tpl_vars['value']->value;?>
</span>
                </div>
            </div>
        <?php
}
$_smarty_tpl->smarty->ext->_foreach->restore($_smarty_tpl, 1);?>
    </div>
</div>
<main>
    <?php if ((isset($_smarty_tpl->tpl_vars['message']->value)) && !is_null($_smarty_tpl->tpl_vars['message']->value)) {?>
        <div class="message">
            <p><?php echo $_smarty_tpl->tpl_vars['message']->value;?>
</p>
        </div>
    <?php }?>

    <div id="file-path"><span><?php echo $_smarty_tpl->tpl_vars['file']->value;?>
</span></div>

    <?php if ((isset($_smarty_tpl->tpl_vars['trace']->value)) && !is_null($_smarty_tpl->tpl_vars['trace']->value)) {?>
        <div class="trace">
            <p><?php echo $_smarty_tpl->tpl_vars['trace']->value !== null ? $_smarty_tpl->tpl_vars['trace']->value : $_smarty_tpl->tpl_vars['message']->value;?>
</p>
        </div>
    <?php }?>
    <?php if ((isset($_smarty_tpl->tpl_vars['lines']->value))) {?>
        <div id="code-container">
            <div class="line-marker">
                <?php
$_smarty_tpl->tpl_vars['i'] = new Smarty_Variable(null, $_smarty_tpl->isRenderingCache);
$_smarty_tpl->tpl_vars['i']->value = 1;
if ($_smarty_tpl->tpl_vars['i']->value <= $_smarty_tpl->tpl_vars['lines']->value) {
for ($_foo=true;$_smarty_tpl->tpl_vars['i']->value <= $_smarty_tpl->tpl_vars['lines']->value; $_smarty_tpl->tpl_vars['i']->value++) {
?>
                    <span
                            class="<?php echo $_smarty_tpl->tpl_vars['i']->value == $_smarty_tpl->tpl_vars['line']->value || $_smarty_tpl->tpl_vars['i']->value+1 == $_smarty_tpl->tpl_vars['line']->value || $_smarty_tpl->tpl_vars['i']->value-1 == $_smarty_tpl->tpl_vars['line']->value ? 'error-line-marker' : '';?>
"><?php echo $_smarty_tpl->tpl_vars['i']->value;?>
</span>
                    <br>
                <?php }
}
?>
            </div>
            <div id="code">
                <pre>
                    <?php echo $_smarty_tpl->tpl_vars['code']->value;?>

                </pre>
            </div>
        </div>
    <?php }?>
</main>
</body>
</html>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    * {
        font-family: 'Poppins', sans-serif;
        padding: 0;
        margin: 0;
    }

    body {
        min-height: 100vh;
        max-width: 100vw;
        background-color: #1a1d21;
        display: grid;
        grid-template-areas: "header header"
                             "sidebar main";
        grid-template-columns: 400px 1fr;
        grid-template-rows: 82px 1fr;
        overflow-x: hidden;
    }

    body header {
        width: 100vw;
        max-width: 100vw;
        grid-area: header;
        background-color: #292e34;
        border-bottom: 2px solid #ffffff0f;
        height: 80px;
        display: flex;
        align-items: center;
    }

    body header #logo {
        margin-left: 20px;
        height: 60px;
    }

    body div#sidebar {
        grid-area: sidebar;
        background-color: #292e34;
    }

    body div#sidebar .information-list {
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    body div#sidebar .information-list .information-row {
        display: flex;
        height: 50px;
    }

    body div#sidebar .information-row div {
        height: 100%;
        display: flex;
        align-items: center;
        border: 1px solid #1a1d21;
        font-size: 0.9rem;
    }

    body div#sidebar .information-row div:nth-child(1) {
        width: 35%;
        background-color: rgba(0, 255, 223, 0.75);
        font-weight: bold;
        color: #444444;
    }

    body div#sidebar .information-row div:nth-child(2) {
        width: 65%;
        background-color: #26292d;
        color: rgba(255, 255, 255, 0.31);
    }

    body div#sidebar .information-row div span {
        margin-left: 10px;
    }

    body main {
        display: flex;
        flex-direction: column;
        width: calc(100vw - 400px);
    }

    body main .trace, .message {
        background-color: #00705f;
        color: rgb(0, 255, 190);
    }

    body main #file-path {
        background-color: rgb(58, 58, 58);
        padding: 10px 0;
        border-top: 2px solid rgb(0, 255, 190);
        border-bottom: 2px solid rgb(0, 255, 190);
    }

    body main #file-path span {
        margin-left: 10px;
        color: #a4a4a4;
    }


    body main .trace p {
        padding: 20px;
        font-size: 0.9rem;
    }

    body main .message p {
        padding: 20px;
        font-size: 0.9rem;
    }

    body main #code-container {
        padding: 20px 0;
        grid-area: main;
        color: rgba(255, 255, 255, 0.43);
        display: flex;
        overflow-x: scroll;
        max-width: 100%;
    }

    body main #code-container .line-marker {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 0 10px;
    }

    body main #code-container .line-marker span.error-line-marker {
        color: rgba(255, 59, 59, 0.59);
        font-weight: bold;
    }

    body main #code-container #code pre {
        width: fit-content;
    }

    body main #code-container #code pre span.error-line {
        background-color: rgba(255, 59, 59, 0.59);
        color: rgba(16, 16, 16, 0.56);
        width: 100%;
        font-weight: bold;
        display: inline-block;
    }

</style>
<?php }
}
