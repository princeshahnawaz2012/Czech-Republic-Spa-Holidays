<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:40:23
         compiled from "application//views/adm/adm_articles/index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7602344365039e0f7d8f104-32156209%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '59cb67b905c6af28368b004f286d3068030adbfa' => 
    array (
      0 => 'application//views/adm/adm_articles/index.tpl',
      1 => 1341284389,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7602344365039e0f7d8f104-32156209',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sSiteTitle' => 0,
    'site_url' => 0,
    'sAddUrl' => 0,
    'nCountAllArticles' => 0,
    'nCountActiveArticles' => 0,
    'nCountInactiveArticles' => 0,
    'aPerPageVariables' => 0,
    'nPerPageVar' => 0,
    'nPerPage' => 0,
    'aFilters' => 0,
    'sMainLang' => 0,
    'nOrder' => 0,
    'sDirect' => 0,
    'aOrderLinks' => 0,
    'sLink' => 0,
    'aArticles' => 0,
    'aArticle' => 0,
    'sDeactivateUrl' => 0,
    'sActivateUrl' => 0,
    'sEditUrl' => 0,
    'sDeleteUrl' => 0,
    'sPagination' => 0,
    'nOrders' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039e0f81c6de',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039e0f81c6de')) {function content_5039e0f81c6de($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_date_format')) include '/home/sergii/Projects/Web/Alena/CzechSpaHolidays.Com/Git-Subversion/git/application/libraries/Smarty/plugins/modifier.date_format.php';
?><h1><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sAddUrl']->value;?>
" id="article_add" title="Add an article" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: <?php echo $_smarty_tpl->tpl_vars['nCountAllArticles']->value;?>

	<br />
		Active: <?php echo $_smarty_tpl->tpl_vars['nCountActiveArticles']->value;?>

	<br />
		Inactive: <?php echo $_smarty_tpl->tpl_vars['nCountInactiveArticles']->value;?>

	</p>
</div>	
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
		<input type="text" id="art_id" placeholder="ID" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value["articles.com_article_id"];?>
" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_articles.title")];?>
" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="" <?php if ($_smarty_tpl->tpl_vars['aFilters']->value["articles.com_active"]=='0'){?>selected="selected"<?php }?>>Act./Inact.</option>
			<option value="2" <?php if ($_smarty_tpl->tpl_vars['aFilters']->value["articles.com_active"]=='2'){?>selected="selected"<?php }?>>Inactive</option>
			<option value="1" <?php if ($_smarty_tpl->tpl_vars['aFilters']->value["articles.com_active"]=='1'){?>selected="selected"<?php }?>>Active</option>
		</select>
		<input type="hidden" id="art_per_page" value="<?php echo $_smarty_tpl->tpl_vars['nPerPage']->value;?>
" />
		<input type="hidden" id="art_order" value="<?php echo $_smarty_tpl->tpl_vars['nOrder']->value;?>
" />
		<input type="hidden" id="art_direct" value="<?php echo $_smarty_tpl->tpl_vars['sDirect']->value;?>
" />
	</p>
</div>
<div class="grid_3">
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
		<th colspan="2">Actions</th>
	</tr>
	</thead>
	<tbody>
<?php  $_smarty_tpl->tpl_vars['aArticle'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aArticle']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aArticles']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aArticle']->key => $_smarty_tpl->tpl_vars['aArticle']->value){
$_smarty_tpl->tpl_vars['aArticle']->_loop = true;
?>
	<tr>
		<td><?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_article_id'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['aArticle']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_title")];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_hits'];?>
</td>
		<td>
			<?php if ($_smarty_tpl->tpl_vars['aArticle']->value['com_active']==1){?>
				<a class="active_status" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sDeactivateUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_article_id'];?>
" title="Deactivate this article" onclick="return confirm('Are you sure?');"></a>
			<?php }else{ ?>
				<a class="inactive_status" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sActivateUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_article_id'];?>
" title="Activate this article" onclick="return confirm('Are you sure?');"></a>
			<?php }?>
		</td>
		<td style="min-width: 120px;"><?php echo smarty_modifier_date_format($_smarty_tpl->tpl_vars['aArticle']->value['com_time'],"%Y-%m-%d %H:%M:%S");?>
</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="<?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_article_id'];?>
_order_input" value="<?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_order'];?>
" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="<?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_article_id'];?>
_save_order" title="<?php echo vlang('Save order value');?>
"></a>
		</td>
		<td>
			<a class="edit" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sEditUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_article_id'];?>
" title="Edit this article"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sDeleteUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aArticle']->value['com_article_id'];?>
" id="article_delete" title="Delete this article"></a>
		</td>
	</tr>
<?php } ?>
	</tbody>
	<?php if ($_smarty_tpl->tpl_vars['sPagination']->value){?>
	<tfoot>
	<tr>
		<td colspan="<?php echo $_smarty_tpl->tpl_vars['nOrders']->value+2;?>
" class="pagination">
		<?php echo $_smarty_tpl->tpl_vars['sPagination']->value;?>

		</td>
	</tr>
	</tfoot>
	<?php }?>
</table>
</div><?php }} ?>