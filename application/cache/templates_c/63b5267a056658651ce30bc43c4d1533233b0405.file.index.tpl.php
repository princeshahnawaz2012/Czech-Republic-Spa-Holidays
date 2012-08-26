<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:14:59
         compiled from "application//views/controllers/categories/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:10992220715039db039cd0e9-16247573%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '63b5267a056658651ce30bc43c4d1533233b0405' => 
    array (
      0 => 'application//views/controllers/categories/index.tpl',
      1 => 1345592160,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '10992220715039db039cd0e9-16247573',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sSiteTitle' => 0,
    'aCategoriesData' => 0,
    'site_url' => 0,
    'CATEGORY_SHOW_SHORT_DESCRIPTION' => 0,
    'aCategoryData' => 0,
    'sCategoryPicturesDir' => 0,
    'CATEGORY_SHOW_ILLNESES' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039db03bbadd',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039db03bbadd')) {function content_5039db03bbadd($_smarty_tpl) {?><h1><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</h1>
<div class="material_shot">
	<?php  $_smarty_tpl->tpl_vars['aCategoryData'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aCategoryData']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aCategoriesData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aCategoryData']->key => $_smarty_tpl->tpl_vars['aCategoryData']->value){
$_smarty_tpl->tpl_vars['aCategoryData']->_loop = true;
?>
	<table class="material_1" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="m_title">
				<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
categories/id/<?php echo $_smarty_tpl->tpl_vars['CATEGORY_SHOW_SHORT_DESCRIPTION']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['aCategoryData']->value['com_category_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'seo_link');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'title');?>
</a>
			</td>
		</tr>
		<tr>
			<td class="m_imege">
				<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
categories/id/<?php echo $_smarty_tpl->tpl_vars['CATEGORY_SHOW_SHORT_DESCRIPTION']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['aCategoryData']->value['com_category_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'seo_link');?>
">
					<img src="/<?php echo $_smarty_tpl->tpl_vars['sCategoryPicturesDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aCategoryData']->value['com_category_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aCategoryData']->value['com_picture_ext'];?>
" alt="<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'title');?>
" title="<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'title');?>
" />
				</a>
			</td>
		</tr>
		<tr>
			<td class="m_text">
				<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
categories/id/<?php echo $_smarty_tpl->tpl_vars['CATEGORY_SHOW_SHORT_DESCRIPTION']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['aCategoryData']->value['com_category_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'seo_link');?>
">
					<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'short_desc');?>

				</a>
			</td>
		</tr>
		<tr>
			<td class="m_link">
				<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
categories/id/<?php echo $_smarty_tpl->tpl_vars['CATEGORY_SHOW_ILLNESES']->value;?>
/<?php echo $_smarty_tpl->tpl_vars['aCategoryData']->value['com_category_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'seo_link');?>
"><?php echo vlang('more');?>
</a>
			</td>
		</tr>
	</table>
	<?php } ?>
	<div class="cl"></div>
</div><?php }} ?>