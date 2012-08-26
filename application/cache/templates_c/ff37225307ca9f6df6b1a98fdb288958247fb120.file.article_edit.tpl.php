<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 12:40:05
         compiled from "application//views/adm/adm_trans/article_edit.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2453453455039eef5568471-96985731%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ff37225307ca9f6df6b1a98fdb288958247fb120' => 
    array (
      0 => 'application//views/adm/adm_trans/article_edit.tpl',
      1 => 1341115082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2453453455039eef5568471-96985731',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sSiteTitle' => 0,
    'language_abbr' => 0,
    'lang_uri_abbr' => 0,
    'aOriginalData' => 0,
    'sLang' => 0,
    'site_url' => 0,
    'sCancelUrl' => 0,
    'aArticleData' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039eef57ea05',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039eef57ea05')) {function content_5039eef57ea05($_smarty_tpl) {?><h1><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</h1>
<div class="grid_8">
	<h2><?php echo vlang('Original');?>
 (<?php echo ucfirst($_smarty_tpl->tpl_vars['lang_uri_abbr']->value[$_smarty_tpl->tpl_vars['language_abbr']->value]);?>
)</h2>
	
	<label><?php echo vlang('Title');?>
 <small><?php echo vlang('required');?>
</small></label>
	<?php echo $_smarty_tpl->tpl_vars['aOriginalData']->value['title'];?>

	<br />
	
	<label><?php echo vlang('Meta keywords');?>
</label>
	<?php echo nl2br($_smarty_tpl->tpl_vars['aOriginalData']->value['keywords']);?>

	<br />

	<label><?php echo vlang('Meta description');?>
</label>
	<?php echo nl2br($_smarty_tpl->tpl_vars['aOriginalData']->value['description']);?>

	<br />

	<label><?php echo vlang('SEO link suffix');?>
</label>
	<?php echo $_smarty_tpl->tpl_vars['aOriginalData']->value['seo_link'];?>

	<br />

	<label><?php echo vlang('Article body');?>
 <small><?php echo vlang('required');?>
</small></label>
	<?php echo nl2br($_smarty_tpl->tpl_vars['aOriginalData']->value['full']);?>

	<br />

</div>

<div class="grid_8">
<?php echo form_open();?>

	
	<h2><?php echo vlang('Translate');?>
 (<?php echo ucfirst($_smarty_tpl->tpl_vars['lang_uri_abbr']->value[$_smarty_tpl->tpl_vars['sLang']->value]);?>
)</h2>

	<div class="center">
		<input type="submit" value="Save" />&nbsp;
		<a class="button" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sCancelUrl']->value;?>
">Cancel</a>
	</div>
	<fieldset>	
		<label><?php echo vlang('Title');?>
 <small><?php echo vlang('Required');?>
</small></label>
		<input type="text" name="title" value="<?php echo set_value('title',$_smarty_tpl->tpl_vars['aArticleData']->value['title']);?>
" autocomplete="off" />
	<br />

		<label><?php echo vlang('Meta keywords');?>
</label>
		<textarea class="small_textarea" name="keywords"><?php echo set_value('keywords',$_smarty_tpl->tpl_vars['aArticleData']->value['keywords']);?>
</textarea>
	<br />

		<label><?php echo vlang('Meta description');?>
</label>
		<textarea class="small_textarea" name="description"><?php echo set_value('description',$_smarty_tpl->tpl_vars['aArticleData']->value['description']);?>
</textarea>
	<br />

		<label><?php echo vlang('SEO link suffix');?>
</label>
		<input type="text" name="seo_link" value="<?php echo set_value('seo_link',$_smarty_tpl->tpl_vars['aArticleData']->value['seo_link']);?>
" autocomplete="off" />
	<br />

		<label><?php echo vlang('Article body');?>
 <small><?php echo vlang('Required');?>
</small></label>
		<textarea class="large_textarea tinymce" name="full"><?php echo set_value('full',$_smarty_tpl->tpl_vars['aArticleData']->value['full']);?>
</textarea>
	<br />
	</fieldset>	
		<div class="center">
			<input type="submit" value="Save" />&nbsp;
			<a class="button" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sCancelUrl']->value;?>
">Cancel</a>
			<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['aArticleData']->value['article_id'];?>
" id="art_id" />
			<input type="hidden" value="<?php echo $_smarty_tpl->tpl_vars['sLang']->value;?>
" id="art_lang" />
		</div>
	
</form>
</div><?php }} ?>