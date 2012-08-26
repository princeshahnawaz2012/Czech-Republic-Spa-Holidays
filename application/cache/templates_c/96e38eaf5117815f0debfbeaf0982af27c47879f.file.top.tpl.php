<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:15:15
         compiled from "application//views/regions/top.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3220621385039db13ed0d92-43886375%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '96e38eaf5117815f0debfbeaf0982af27c47879f' => 
    array (
      0 => 'application//views/regions/top.tpl',
      1 => 1345579832,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3220621385039db13ed0d92-43886375',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sUri' => 0,
    'info_phone' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039db13f35be',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039db13f35be')) {function content_5039db13f35be($_smarty_tpl) {?><div class="top_banner" id="banner_<?php echo rand(0,3);?>
">
	<!--
	<div id="submenu">
		<div id="submenu_c"><span><a href="/">Manage My Booking</a></span>|<span><a href="/">View Shopping Basket</a></span></div>
	</div>
	-->
	<div class="cl"></div>
	<div id="language_menu">
		<ul>
			<li><a href="/cs<?php echo $_smarty_tpl->tpl_vars['sUri']->value;?>
"><img src="/images/flag_new/cs.png" alt="CS" title="CS" /></a></li>
			<li><a href="/en<?php echo $_smarty_tpl->tpl_vars['sUri']->value;?>
"><img src="/images/flag_new/en.png" alt="EN" title="EN" /></a></li>
			<li><a href="/de<?php echo $_smarty_tpl->tpl_vars['sUri']->value;?>
"><img src="/images/flag_new/de.png" alt="DE" title="DE" /></a></li>
			<li><a href="/ru<?php echo $_smarty_tpl->tpl_vars['sUri']->value;?>
"><img src="/images/flag_new/ru.png" alt="RU" title="RU" /></a></li>
		</ul>
	</div>
	<div id="phone_number">
		<?php echo $_smarty_tpl->tpl_vars['info_phone']->value;?>

	</div>
</div>
<div id="breadcrumbs">
	<!--
	<span><a href="/">Availablity and Rates</a> &nbsp;:&nbsp;:&nbsp;<a href="/">Shopping Basket</a> &nbsp;:&nbsp;:&nbsp;<a href="/">
	Complete Your Details  and Pay</a> &nbsp;:&nbsp;:&nbsp;<a href="/">Confirmation</a></span>
	-->
</div><?php }} ?>