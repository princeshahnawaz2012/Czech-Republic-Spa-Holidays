<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 12:40:05
         compiled from "application//views/main_admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20299001995039eef58dc8b7-24569973%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '054316285faa4aaeb1c05b27d1207ceafa767213' => 
    array (
      0 => 'application//views/main_admin.tpl',
      1 => 1341115082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20299001995039eef58dc8b7-24569973',
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
  'unifunc' => 'content_5039eef59249f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039eef59249f')) {function content_5039eef59249f($_smarty_tpl) {?><?php echo $_smarty_tpl->getSubTemplate ("regions/adm/head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

<body>
	<?php echo $_smarty_tpl->tpl_vars['sErrorBlock']->value;?>

    <?php echo $_smarty_tpl->getSubTemplate ("regions/adm/header.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

	<div id="content" class="container_16 clearfix">
	    <?php echo $_smarty_tpl->tpl_vars['content']->value;?>

	</div>
	<?php echo $_smarty_tpl->getSubTemplate ("regions/adm/footer.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null, array(), 0);?>

</body>
</html><?php }} ?>