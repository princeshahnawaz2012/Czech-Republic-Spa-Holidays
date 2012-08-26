<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:14:29
         compiled from "application//views/adm/adm_supplements/supplements.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1638725695039dae5e437b9-24967715%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'af5e581b8fc7079db30e16ecd6bfc07602043eb2' => 
    array (
      0 => 'application//views/adm/adm_supplements/supplements.tpl',
      1 => 1345068450,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1638725695039dae5e437b9-24967715',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sSiteTitle' => 0,
    'site_url' => 0,
    'sAddUrl' => 0,
    'aPerPageVariables' => 0,
    'nPerPageVar' => 0,
    'nPerPage' => 0,
    'aFilters' => 0,
    'nOrder' => 0,
    'sDirect' => 0,
    'aOrderLinks' => 0,
    'sLink' => 0,
    'aSupplements' => 0,
    'supplement' => 0,
    'sEditUrl' => 0,
    'sDeleteUrl' => 0,
    'sPagination' => 0,
    'nOrders' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039dae61191f',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039dae61191f')) {function content_5039dae61191f($_smarty_tpl) {?><h1><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sAddUrl']->value;?>
" id="supplement_add" title="Add a supplement" class="button">Add</a>
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
		<input type="text" id="art_id" placeholder="ID" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value["supplements.com_supplement_id"];?>
" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value["supplements.com_title"];?>
" autocomplete="off" />
	</p>
</div>
<div class="grid_3">
	<p>
		<label>&nbsp;</label>
		<input type="submit" id="art_filter" value="Filter" />
		<input type="submit" id="art_filter_reset" value="Reset" />
		<input type="hidden" id="art_per_page" value="<?php echo $_smarty_tpl->tpl_vars['nPerPage']->value;?>
" />
		<input type="hidden" id="art_order" value="<?php echo $_smarty_tpl->tpl_vars['nOrder']->value;?>
" />
		<input type="hidden" id="art_direct" value="<?php echo $_smarty_tpl->tpl_vars['sDirect']->value;?>
" />
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
<?php  $_smarty_tpl->tpl_vars['supplement'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['supplement']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aSupplements']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['supplement']->key => $_smarty_tpl->tpl_vars['supplement']->value){
$_smarty_tpl->tpl_vars['supplement']->_loop = true;
?>
	<tr>
		<td><?php echo $_smarty_tpl->tpl_vars['supplement']->value['com_supplement_id'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['supplement']->value['com_title'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['supplement']->value['com_date_from'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['supplement']->value['com_date_till'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['supplement']->value['com_price'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['supplement']->value['com_currency_id'];?>
</td>
		<td>
			<a class="edit" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sEditUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['supplement']->value['com_supplement_id'];?>
" id="supplement_edit" title="Edit this supplement"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sDeleteUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['supplement']->value['com_supplement_id'];?>
" id="supplement_delete" title="Delete this supplement"></a>
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