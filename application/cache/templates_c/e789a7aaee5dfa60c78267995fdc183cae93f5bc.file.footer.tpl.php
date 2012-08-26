<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:15:16
         compiled from "application//views/regions/footer.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11366435965039db14028816-24047596%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e789a7aaee5dfa60c78267995fdc183cae93f5bc' => 
    array (
      0 => 'application//views/regions/footer.tpl',
      1 => 1345247536,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11366435965039db14028816-24047596',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'site_url' => 0,
    'language_abbr' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039db140d61b',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039db140d61b')) {function content_5039db140d61b($_smarty_tpl) {?><div id="footer">
	<div id="footer_menu">
		<ul>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
site_map"><?php echo vlang('Site Map');?>
</a></li>
			<li class="separator"></li>
			<li><a href="/<?php echo $_smarty_tpl->tpl_vars['language_abbr']->value;?>
"><?php echo vlang('Home');?>
</a></li>
			<li class="separator"></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/6"><?php echo vlang('About Us');?>
</a></li>
			<li class="separator"></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/5"><?php echo vlang('Contact Us');?>
</a></li>
			<li class="separator"></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/3"><?php echo vlang('Terms and Conditions');?>
</a></li>
			<li class="separator"></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/2"><?php echo vlang('Useful info');?>
</a></li>
			<li class="separator"></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/7"><?php echo vlang('Useful Links');?>
</a></li>
			<li class="separator"></li>
			<li><a href="http://www.fco.gov.uk/en/travel-and-living-abroad/travel-advice-by-country/europe/czech-republic"><?php echo vlang('FCO');?>
</a></li>
			<li class="separator"></li>
			<li><a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
articles/id/4"><?php echo vlang('Work with Us');?>
</a></li>
		</ul>
	</div>
	<div id="footer_info">
		<?php echo vlang('Czech Spa Holidays.com ® is a trading name of Interservis, independent travel agency specializing in organizing health and beauty holidays in mineral springs spas around Czech Republic. We work hard to ensure that your experience with us is one of supreme excellence in quality, service and value. All Rights reserved © 2012');?>

	</div>
</div><?php }} ?>