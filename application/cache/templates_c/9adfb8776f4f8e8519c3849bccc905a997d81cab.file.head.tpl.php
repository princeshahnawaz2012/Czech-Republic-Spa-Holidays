<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:15:15
         compiled from "application//views/regions/head.tpl" */ ?>
<?php /*%%SmartyHeaderCode:8968248055039db13d6e8a5-31952972%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9adfb8776f4f8e8519c3849bccc905a997d81cab' => 
    array (
      0 => 'application//views/regions/head.tpl',
      1 => 1341115082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '8968248055039db13d6e8a5-31952972',
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
  'unifunc' => 'content_5039db13e0170',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039db13e0170')) {function content_5039db13e0170($_smarty_tpl) {?><?php if (isset($_smarty_tpl->tpl_vars['sheadContent']->value)&&$_smarty_tpl->tpl_vars['sheadContent']->value){?>
<?php echo $_smarty_tpl->tpl_vars['sheadContent']->value;?>

<?php }else{ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="<?php echo $_smarty_tpl->tpl_vars['sMetaKeywords']->value;?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->tpl_vars['sMetaDescription']->value;?>
"/>
<title><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</title>
<link rel="icon" type="image/vnd.microsoft.icon" href="/images/favicon.ico" />
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
<link rel="stylesheet" href="/css/style.css" type="text/css" media="screen" charset="utf-8" />
<link rel="stylesheet" href="/css/ui-lightness/jquery-ui-1.8.20.custom.css" type="text/css" media="screen" charset="utf-8" />
<script src="/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script src="/js/jquery-ui-1.8.11.custom.min.js" type="text/javascript"></script>
<?php echo $_smarty_tpl->tpl_vars['sStyles']->value;?>

<?php echo $_smarty_tpl->tpl_vars['sScripts']->value;?>

</head>
<?php }?><?php }} ?>