<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 12:40:05
         compiled from "application//views/regions/adm/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6761738935039eef5b40d16-40229779%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0ff78bd60c42d368b5aed741ec81f1df43a0b7f6' => 
    array (
      0 => 'application//views/regions/adm/footer.tpl',
      1 => 1344568655,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6761738935039eef5b40d16-40229779',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039eef5b8ec3',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039eef5b8ec3')) {function content_5039eef5b8ec3($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sergii/Projects/Web/Alena/CzechSpaHolidays.Com/Git-Subversion/git/application/libraries/Smarty/plugins/modifier.date_format.php';
?><div id="foot">
	<i>Czech Spa Holidays.com ® is a trading name of Interservis, independent travel agency specializing in organizing health and beauty holidays in mineral springs spas around Czech Republic. We work hard to ensure that your experience with us is one of supreme excellence in quality, service and value.<br />All Rights reserved © 2012<?php if (intval(date("Y",time()))>2012){?>-<?php echo smarty_modifier_date_format(time(),"%Y");?>
<?php }?></i>
</div><?php }} ?>