<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:14:21
         compiled from "application//views/adm/adm_programmes/programmes.tpl" */ ?>
<?php /*%%SmartyHeaderCode:11984317615039dadd525e99-33464663%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '874ab16d1998e3ffcd9df16da35276d1cc6e7dda' => 
    array (
      0 => 'application//views/adm/adm_programmes/programmes.tpl',
      1 => 1345674521,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '11984317615039dadd525e99-33464663',
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
    'language_abbr' => 0,
    'sMainLang' => 0,
    'nCountAllProgrammes' => 0,
    'nCountActiveProgrammes' => 0,
    'nCountInactiveProgrammes' => 0,
    'PROGRAMME_ALL' => 0,
    'PROGRAMME_INACTIVE' => 0,
    'PROGRAMME_ACTIVE' => 0,
    'nOrder' => 0,
    'sDirect' => 0,
    'aOrderLinks' => 0,
    'sLink' => 0,
    'aProgrammes' => 0,
    'programme' => 0,
    'sDeactivateUrl' => 0,
    'sActivateUrl' => 0,
    'sEditUrl' => 0,
    'sImagesUrl' => 0,
    'sDeleteUrl' => 0,
    'sPagination' => 0,
    'nOrders' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039dadd94244',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039dadd94244')) {function content_5039dadd94244($_smarty_tpl) {?><h1><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sAddUrl']->value;?>
" id="programme_add" title="Add a programme" class="button">Add</a>
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
<div class="grid_3">
	<p>
		<label for="art_id">ID</label>
		<input type="text" id="art_id" placeholder="ID" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value["programmes.com_programme_id"];?>
" autocomplete="off" />
	</p>
</div>
<div class="grid_5">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value[($_smarty_tpl->tpl_vars['language_abbr']->value).("_programmes.title")];?>
" autocomplete="off" />
	</p>
</div>
<div class="grid_5">
	<p>
		<label for="art_category_title">Category</label>
		<input type="text" id="art_category_title" placeholder="Category" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_categories.title")];?>
" autocomplete="off" />
	</p>
</div>
<div class="clearfix"></div>
<div class="grid_1">
	<p>
		<span title="<?php echo vlang('All');?>
">All</span>: <?php echo $_smarty_tpl->tpl_vars['nCountAllProgrammes']->value;?>

	<br />
		<span title="<?php echo vlang('Active');?>
">Act.</span>: <?php echo $_smarty_tpl->tpl_vars['nCountActiveProgrammes']->value;?>

	<br />
		<span title="<?php echo vlang('Inactive');?>
">Ina.</span>: <?php echo $_smarty_tpl->tpl_vars['nCountInactiveProgrammes']->value;?>

	</p>
</div>
<div class="grid_5">
	<p>
		<label for="art_spa_title"><?php echo vlang('Hotel spa');?>
</label>
		<input type="text" id="art_spa_title" placeholder="<?php echo vlang('Hotel spa');?>
" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_spas.title")];?>
" autocomplete="off" />
	</p>
</div>
<div class="grid_5">
	<p>
		<label for="art_city_title"><?php echo vlang('City');?>
</label>
		<input type="text" id="art_city_title" placeholder="<?php echo vlang('City');?>
" value="<?php echo $_smarty_tpl->tpl_vars['aFilters']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_cities.title")];?>
" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="<?php echo $_smarty_tpl->tpl_vars['PROGRAMME_ALL']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['aFilters']->value["programmes.com_active"]==$_smarty_tpl->tpl_vars['PROGRAMME_ALL']->value){?>selected="selected"<?php }?>>Act./Inact.</option>
			<option value="<?php echo $_smarty_tpl->tpl_vars['PROGRAMME_INACTIVE']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['aFilters']->value["programmes.com_active"]==$_smarty_tpl->tpl_vars['PROGRAMME_INACTIVE']->value){?>selected="selected"<?php }?>>Inactive</option>
			<option value="<?php echo $_smarty_tpl->tpl_vars['PROGRAMME_ACTIVE']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['aFilters']->value["programmes.com_active"]==$_smarty_tpl->tpl_vars['PROGRAMME_ACTIVE']->value){?>selected="selected"<?php }?>>Active</option>
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
		<th colspan="3">Actions</th>
	</tr>
	</thead>
	<tbody>
<?php  $_smarty_tpl->tpl_vars['programme'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['programme']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aProgrammes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['programme']->key => $_smarty_tpl->tpl_vars['programme']->value){
$_smarty_tpl->tpl_vars['programme']->_loop = true;
?>
	<tr>
		<td><?php echo $_smarty_tpl->tpl_vars['programme']->value['com_programme_id'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['programme']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_title")];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['programme']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_category_title")];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['programme']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_spa_title")];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['programme']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_city_title")];?>
</td>
		<td><?php if ($_smarty_tpl->tpl_vars['programme']->value['com_active']==$_smarty_tpl->tpl_vars['PROGRAMME_ACTIVE']->value){?>
				<a class="active_status" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sDeactivateUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['programme']->value['com_programme_id'];?>
" title="Deactivate this programme" onclick="return confirm('Are you sure?');"></a>
			<?php }else{ ?>
				<a class="inactive_status" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sActivateUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['programme']->value['com_programme_id'];?>
" title="Activate this programme" onclick="return confirm('Are you sure?');"></a>
			<?php }?></td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="<?php echo $_smarty_tpl->tpl_vars['programme']->value['com_programme_id'];?>
_order_input" value="<?php echo $_smarty_tpl->tpl_vars['programme']->value['com_order'];?>
" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="<?php echo $_smarty_tpl->tpl_vars['programme']->value['com_programme_id'];?>
_save_order" title="<?php echo vlang('Save order value');?>
"></a>
		</td>
		<td>
			<a class="edit" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sEditUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['programme']->value['com_programme_id'];?>
" title="Edit this programme"></a>
		</td>
		<td>
			<a class="pictures" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sImagesUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['programme']->value['com_programme_id'];?>
" title="The images of this programme">(<?php echo intval($_smarty_tpl->tpl_vars['programme']->value['num_images']);?>
)</a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sDeleteUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['programme']->value['com_programme_id'];?>
" id="programme_delete" title="Delete this programme"></a>
		</td>
	</tr>	
<?php } ?>
	</tbody>
	<?php if ($_smarty_tpl->tpl_vars['sPagination']->value){?>
	<tfoot>
	<tr>
		<td colspan="<?php echo $_smarty_tpl->tpl_vars['nOrders']->value+3;?>
" class="pagination">
		<?php echo $_smarty_tpl->tpl_vars['sPagination']->value;?>

		</td>
	</tr>
	</tfoot>
	<?php }?>
</table>
</div><?php }} ?>