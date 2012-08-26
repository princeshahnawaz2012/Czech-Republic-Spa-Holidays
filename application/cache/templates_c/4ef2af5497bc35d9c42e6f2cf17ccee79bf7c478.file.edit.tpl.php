<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:35:40
         compiled from "application//views/adm/adm_articles/edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11205475825039dfdcb4f578-65867245%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4ef2af5497bc35d9c42e6f2cf17ccee79bf7c478' => 
    array (
      0 => 'application//views/adm/adm_articles/edit.tpl',
      1 => 1341115082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11205475825039dfdcb4f578-65867245',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sSiteTitle' => 0,
    'site_url' => 0,
    'sCancelUrl' => 0,
    'aArticleData' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039dfdcd1a21',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039dfdcd1a21')) {function content_5039dfdcd1a21($_smarty_tpl) {?><h1><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</h1>
<?php echo form_open();?>

	<div class="center">
		<input type="submit" value="<?php echo vlang('Save article');?>
" />
		<a class="button" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sCancelUrl']->value;?>
"><?php echo vlang('Cancel');?>
</a>
	</div>
	<fieldset>
		<div class="grid_8">
			<label><?php echo vlang('Title');?>
 <small><?php echo vlang('Required');?>
</small></label>
			<input type="text" name="title" value="<?php echo set_value('title',$_smarty_tpl->tpl_vars['aArticleData']->value['title']);?>
" autocomplete="off" />
		</div>
		<div class="grid_8">
			<label><?php echo vlang('SEO link suffix');?>
</label>
			<input type="text" name="seo_link" value="<?php echo set_value('seo_link',$_smarty_tpl->tpl_vars['aArticleData']->value['seo_link']);?>
" autocomplete="off" />
		</div>
		<div class="grid_16">
			<label><?php echo vlang('Meta keywords');?>
</label>
			<textarea class="small_textarea" name="keywords"><?php echo set_value('keywords',$_smarty_tpl->tpl_vars['aArticleData']->value['keywords']);?>
</textarea>
		</div>
		<div class="grid_16">
			<label><?php echo vlang('Meta description');?>
</label>
			<textarea class="small_textarea" name="description"><?php echo set_value('description',$_smarty_tpl->tpl_vars['aArticleData']->value['description']);?>
</textarea>
		</div>
		<div class="grid_8">
			<label><?php echo vlang('Order');?>
</label>
			<input type="text" name="com_order" value="<?php echo set_value('com_order',$_smarty_tpl->tpl_vars['aArticleData']->value['com_order']);?>
" autocomplete="off" />
		</div>
		<div class="grid_8">
			<label><?php echo vlang('Hits');?>
</label>
			<input type="text" name="com_hits" value="<?php echo set_value('com_hits',$_smarty_tpl->tpl_vars['aArticleData']->value['com_hits']);?>
" autocomplete="off" />
		</div>
		<div class="grid_16">
			<label><?php echo vlang('Article body');?>
 <small><?php echo vlang('Required');?>
</small></label>
			<textarea class="large_textarea tinymce" name="full"><?php echo set_value('full',$_smarty_tpl->tpl_vars['aArticleData']->value['full']);?>
</textarea>
		</div>
	</fieldset>
	<div class="center">
		<input type="submit" value="<?php echo vlang('Save article');?>
" />
		<a class="button" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sCancelUrl']->value;?>
"><?php echo vlang('Cancel');?>
</a>
	</div>
</form><?php }} ?>