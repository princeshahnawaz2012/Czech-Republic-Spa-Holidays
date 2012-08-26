<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 12:40:05
         compiled from "application//views/regions/adm/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6857075415039eef5a31e64-11848185%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '33adcc0babd3de66b763cae3eb2bbdccab09737e' => 
    array (
      0 => 'application//views/regions/adm/header.tpl',
      1 => 1341284389,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6857075415039eef5a31e64-11848185',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'menu' => 0,
    'value' => 0,
    'sub1' => 0,
    'sub2' => 0,
    'nUserId' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039eef5b1e04',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039eef5b1e04')) {function content_5039eef5b1e04($_smarty_tpl) {?><div id="head"><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
admin">Engine of Site | Czech Spa Holidays</a></div>

<ul id="navigation">
	<!--<li><span class="active">Overview</span></li>
	<li><a href="#">News</a></li>
	<li><a href="#">Users</a></li>-->
	<?php  $_smarty_tpl->tpl_vars['value'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['value']->_loop = false;
 $_smarty_tpl->tpl_vars['key'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['menu']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['value']->key => $_smarty_tpl->tpl_vars['value']->value){
$_smarty_tpl->tpl_vars['value']->_loop = true;
 $_smarty_tpl->tpl_vars['key']->value = $_smarty_tpl->tpl_vars['value']->key;
?>
		<li>
			<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['value']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['value']->value['name'];?>
</a>
			<?php if (isset($_smarty_tpl->tpl_vars['value']->value['sub_menu'])){?>
				<ul>
				<?php  $_smarty_tpl->tpl_vars['sub1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sub1']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['value']->value['sub_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sub1']->key => $_smarty_tpl->tpl_vars['sub1']->value){
$_smarty_tpl->tpl_vars['sub1']->_loop = true;
?>
					<li>
						<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sub1']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['sub1']->value['name'];?>
</a>
						<?php if (isset($_smarty_tpl->tpl_vars['sub1']->value['sub_menu'])){?>
							<ul>
							<?php  $_smarty_tpl->tpl_vars['sub2'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sub2']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['sub1']->value['sub_menu']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sub2']->key => $_smarty_tpl->tpl_vars['sub2']->value){
$_smarty_tpl->tpl_vars['sub2']->_loop = true;
?>
								<li>
									<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sub2']->value['link'];?>
"><?php echo $_smarty_tpl->tpl_vars['sub2']->value['name'];?>
</a>
								</li>
							<?php } ?>
							</ul>
						<?php }?>
					</li>
				<?php } ?>
				</ul>
			<?php }?>
		</li>
	<?php } ?>					
	<?php if (isset($_smarty_tpl->tpl_vars['nUserId']->value)){?><li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
admin/logout">Logout</a></li><?php }?>
</ul><?php }} ?>