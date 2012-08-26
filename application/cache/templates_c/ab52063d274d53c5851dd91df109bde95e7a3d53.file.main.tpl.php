<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:15:15
         compiled from "application//views/main.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1416074665039db13cf3369-41538103%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ab52063d274d53c5851dd91df109bde95e7a3d53' => 
    array (
      0 => 'application//views/main.tpl',
      1 => 1345247830,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1416074665039db13cf3369-41538103',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sErrorBlock' => 0,
    'content' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039db13d4f50',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039db13d4f50')) {function content_5039db13d4f50($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("regions/head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
<div class="content" align="center" >
    <div id="all">
<?php echo $_smarty_tpl->tpl_vars['sErrorBlock']->value;?>

    <?php echo $_smarty_tpl->getSubTemplate ("regions/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<div id="wrapper">
	<?php echo $_smarty_tpl->getSubTemplate ("regions/top.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

		<div class="sign_in">
	    <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

		</div>
	</div>
	<?php echo $_smarty_tpl->getSubTemplate ("regions/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

    </div>
</div>
</body>
</html><?php }} ?>