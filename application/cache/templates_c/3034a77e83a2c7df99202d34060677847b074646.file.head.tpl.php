<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 12:40:05
         compiled from "application//views/regions/adm/head.tpl" */ ?>
<?php /*%%SmartyHeaderCode:16583320615039eef59458a8-55216007%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3034a77e83a2c7df99202d34060677847b074646' => 
    array (
      0 => 'application//views/regions/adm/head.tpl',
      1 => 1344594071,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '16583320615039eef59458a8-55216007',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sheadContent' => 0,
    'sMetaKeywords' => 0,
    'sMetaDescription' => 0,
    'sSiteTitle' => 0,
    'aTemplateVar' => 0,
    'k' => 0,
    'v' => 0,
    'site_url' => 0,
    'sStyles' => 0,
    'sScripts' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039eef5a1112',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039eef5a1112')) {function content_5039eef5a1112($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['sheadContent']->value)&&$_smarty_tpl->tpl_vars['sheadContent']->value){?>
<?php echo $_smarty_tpl->tpl_vars['sheadContent']->value;?>

<?php }else{ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['sMetaKeywords']->value;?>
" />
		<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['sMetaDescription']->value;?>
" />
		<title><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</title>
		<link rel="icon" type="image/vnd.microsoft.icon" href="/adm_files/img/favicon_engine.ico" />
		
		<link rel="stylesheet" href="/adm_files/css/960.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="/adm_files/css/template.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="/adm_files/css/colour.css" type="text/css" media="screen" charset="utf-8" />
		<link rel="stylesheet" href="/adm_files/css/jquery-ui-1.8.21.custom.css" type="text/css" media="screen" charset="utf-8" />
		<!--[if IE]><![if gte IE 6]><![endif]-->
		<script src="/adm_files/js/jquery-1.7.2.min.js" type="text/javascript"></script>
		<script src="/adm_files/js/jquery-ui-1.8.21.custom.min.js" type="text/javascript"></script>
		<!--<script type="text/javascript">
			$(function() {
				$("#content .grid_5, #content .grid_6").sortable({
					placeholder: 'ui-state-highlight',
					forcePlaceholderSize: true,
					connectWith: '#content .grid_6, #content .grid_5',
					handle: 'h2',
					revert: true
				});
				$("#content .grid_5, #content .grid_6").disableSelection();
			});
		</script>-->
		<!--[if IE]><![endif]><![endif]-->		
		<script type="text/javascript">
			<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['aTemplateVar']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
				var <?php echo $_smarty_tpl->tpl_vars['k']->value;?>
=<?php echo $_smarty_tpl->tpl_vars['v']->value;?>
;
			<?php } ?>
		var site_url="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
";
		</script>
		<?php echo $_smarty_tpl->tpl_vars['sStyles']->value;?>

		<?php echo $_smarty_tpl->tpl_vars['sScripts']->value;?>

	</head>
<?php }?><?php }} ?>