<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:15:15
         compiled from "application//views/regions/header.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12957794745039db13e21599-02032374%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3425b17ca016284d64ca0757afbe30459ff30ee7' => 
    array (
      0 => 'application//views/regions/header.tpl',
      1 => 1345248539,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12957794745039db13e21599-02032374',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'language_abbr' => 0,
    'site_url' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039db13eb002',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039db13eb002')) {function content_5039db13eb002($_smarty_tpl) {?><div id="header">
	<div id="logo">
		<a href="/<?php echo $_smarty_tpl->tpl_vars['language_abbr']->value;?>
"><?php echo vlang('Site title');?>
</a>
	</div>
	<div id="menu">
		<ul>
			<li><a href="/<?php echo $_smarty_tpl->tpl_vars['language_abbr']->value;?>
"><?php echo vlang('Home');?>
</a></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/6/"><?php echo vlang('About Us');?>
</a></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/5/"><?php echo vlang('Contact Us');?>
</a></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/3/"><?php echo vlang('Terms and Conditions');?>
</a></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/2/"><?php echo vlang('Useful info');?>
</a></li>
		</ul>
	</div>
	<div class="cl"></div>
</div><?php }} ?>