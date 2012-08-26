<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:54:42
         compiled from "application//views/adm/adm_trans/articles.tpl" */ ?>
<?php /*%%SmartyHeaderCode:14338872555039e452eada37-53733495%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'ed2c2e7eb0875a738dfa020ab6e10cbaaa8a22d7' => 
    array (
      0 => 'application//views/adm/adm_trans/articles.tpl',
      1 => 1341115082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '14338872555039e452eada37-53733495',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sSiteTitle' => 0,
    'aPerPageVariables' => 0,
    'nPerPageVar' => 0,
    'nPerPage' => 0,
    'aFilters' => 0,
    'sMainLang' => 0,
    'nOrder' => 0,
    'sDirect' => 0,
    'aOrderLinks' => 0,
    'sLink' => 0,
    'aLangPermissions' => 0,
    'aLang' => 0,
    'lang_uri_abbr' => 0,
    'aArticles' => 0,
    'article' => 0,
    'nTime' => 0,
    'site_url' => 0,
    'sPagination' => 0,
    'nOrdersNum' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039e45325ef2',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039e45325ef2')) {function content_5039e45325ef2($_smarty_tpl) {?><?php if (!is_callable('smarty_function_cycle')) include '/home/sergii/Projects/Web/Alena/CzechSpaHolidays.Com/Git-Subversion/git/application/libraries/Smarty/plugins/function.cycle.php';
?><h1><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</h1>
<div class="grid_2">
	<p>
		<label for="art_per_page">Per page</label>
		<select id="art_per_page">
			<?php  $_smarty_tpl->tpl_vars['nPerPageVar'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['nPerPageVar']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aPerPageVariables']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['nPerPageVar']->key => $_smarty_tpl->tpl_vars['nPerPageVar']->value){
$_smarty_tpl->tpl_vars['nPerPageVar']->_loop = true;
?>
				<option value="<?php echo $_smarty_tpl->tpl_vars['nPerPageVar']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['nPerPage']->value==$_smarty_tpl->tpl_vars['nPerPageVar']->value){?>selected="selected"<?php }?>><?php echo $_smarty_tpl->tpl_vars['nPerPageVar']->value;?>
</option>
			<?php } ?>
			<option value="0" <?php if ($_smarty_tpl->tpl_vars['nPerPage']->value==0){?>selected="selected"<?php }?>>All</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_id">ID</label>
		<input type="text" id="art_id" placeholder="ID" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value["com_article_id"];?>
" />
	</p>
</div>
<div class="grid_6">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_articles.title")];?>
" />
		<input type="hidden" id="art_per_page" value="<?php echo $_smarty_tpl->tpl_vars['nPerPage']->value;?>
" />
		<input type="hidden" id="art_order" value="<?php echo $_smarty_tpl->tpl_vars['nOrder']->value;?>
" />
		<input type="hidden" id="art_direct" value="<?php echo $_smarty_tpl->tpl_vars['sDirect']->value;?>
" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label>&nbsp;</label>
		<input type="submit" id="art_filter" value="Filter" />
		<input type="submit" id="art_filter_reset" value="Reset" />
	</p>
</div>
<div class="clearfix"></div>
<div class="grid_16">
<table>
	<thead>
	<tr>
		<?php  $_smarty_tpl->tpl_vars['sLink'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['sLink']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aOrderLinks']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['sLink']->key => $_smarty_tpl->tpl_vars['sLink']->value){
$_smarty_tpl->tpl_vars['sLink']->_loop = true;
?>
		<th><?php echo $_smarty_tpl->tpl_vars['sLink']->value;?>
</th>
		<?php } ?>
		<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangPermissions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value){
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
			<th><?php echo ucfirst($_smarty_tpl->tpl_vars['lang_uri_abbr']->value[$_smarty_tpl->tpl_vars['aLang']->value]);?>
</th>
		<?php } ?>
	</tr>
	</thead>
	<tbody>
<?php  $_smarty_tpl->tpl_vars['article'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['article']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aArticles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['article']->key => $_smarty_tpl->tpl_vars['article']->value){
$_smarty_tpl->tpl_vars['article']->_loop = true;
?>
	<tr class="<?php echo smarty_function_cycle(array('values'=>"first,alt"),$_smarty_tpl);?>
">
		<td><?php echo $_smarty_tpl->tpl_vars['article']->value['com_article_id'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['article']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_title")];?>
</td>
		<?php  $_smarty_tpl->tpl_vars['aLang'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aLang']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aLangPermissions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aLang']->key => $_smarty_tpl->tpl_vars['aLang']->value){
$_smarty_tpl->tpl_vars['aLang']->_loop = true;
?>
			<td>
				<?php if ($_smarty_tpl->tpl_vars['article']->value[($_smarty_tpl->tpl_vars['aLang']->value).("_editing_end")]<$_smarty_tpl->tpl_vars['nTime']->value){?>
					<?php if ($_smarty_tpl->tpl_vars['article']->value[($_smarty_tpl->tpl_vars['aLang']->value).("_title")]){?>
						<a class="edit" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
adm_trans/article_edit/<?php echo $_smarty_tpl->tpl_vars['article']->value['com_article_id'];?>
/<?php echo $_smarty_tpl->tpl_vars['aLang']->value;?>
" title="Edit this translate"></a>
					<?php }else{ ?>
						<a class="new" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
adm_trans/article_edit/<?php echo $_smarty_tpl->tpl_vars['article']->value['com_article_id'];?>
/<?php echo $_smarty_tpl->tpl_vars['aLang']->value;?>
" title="Input this translate"></a>
					<?php }?>
				<?php }else{ ?>
					<?php if ($_smarty_tpl->tpl_vars['article']->value[($_smarty_tpl->tpl_vars['aLang']->value).("_title")]){?>
						<span class="edit"></span>
					<?php }else{ ?>
						<span class="edit"></span>
					<?php }?>
				<?php }?>
			</td>
		<?php } ?>
	</tr>	
<?php } ?>
	</tbody>
	<?php if ($_smarty_tpl->tpl_vars['sPagination']->value){?>
	<tfoot>
	<tr>
		<td colspan="<?php echo $_smarty_tpl->tpl_vars['nOrdersNum']->value;?>
" class="pagination">
		<?php echo $_smarty_tpl->tpl_vars['sPagination']->value;?>

		</td>
	</tr>
	</tfoot>
	<?php }?>
</table>
</div><?php }} ?>