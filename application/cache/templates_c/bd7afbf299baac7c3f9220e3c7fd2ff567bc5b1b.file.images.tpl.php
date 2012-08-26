<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:14:13
         compiled from "application//views/adm/adm_programmes/images.tpl" */ ?>
<?php /*%%SmartyHeaderCode:6731741855039dad573f019-16352418%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'bd7afbf299baac7c3f9220e3c7fd2ff567bc5b1b' => 
    array (
      0 => 'application//views/adm/adm_programmes/images.tpl',
      1 => 1345201455,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '6731741855039dad573f019-16352418',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'sSiteTitle' => 0,
    'site_url' => 0,
    'sAddUrl' => 0,
    'aProgramme' => 0,
    'sAllProgrammesUrl' => 0,
    'sMainLang' => 0,
    'PROGRAMME_ACTIVE' => 0,
    'aImages' => 0,
    'aImage' => 0,
    'PROGRAMME_IMAGE_ACTIVE' => 0,
    'sDeactivateUrl' => 0,
    'sActivateUrl' => 0,
    'sEditUrl' => 0,
    'sDeleteUrl' => 0,
    'sProgrammePictureDir' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039dad5ab5ac',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039dad5ab5ac')) {function content_5039dad5ab5ac($_smarty_tpl) {?><h1><?php echo $_smarty_tpl->tpl_vars['sSiteTitle']->value;?>
</h1>
<div class="grid_3" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sAddUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aProgramme']->value['com_programme_id'];?>
" id="image_add" title="Add a image of programme" class="button">Add image</a>
	</p>
</div>
<div class="grid_3">
	<p>
		<label>&nbsp;</label>
		<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sAllProgrammesUrl']->value;?>
" title="Back to all programmes" class="button">All programmes</a>
	</p>
</div>
<div class="clearfix"></div>
<div class="grid_16">
<table>
	<thead>
	<tr>
		<th><?php echo vlang('Programme ID');?>
</th>
		<th><?php echo vlang('Programme title');?>
</th>
		<th><?php echo vlang('Category');?>
</th>
		<th><?php echo vlang('Hotel spa');?>
</th>
		<th><?php echo vlang('Status');?>
</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td><?php echo $_smarty_tpl->tpl_vars['aProgramme']->value['com_programme_id'];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['aProgramme']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_title")];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['aProgramme']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_category_title")];?>
</td>
		<td><?php echo $_smarty_tpl->tpl_vars['aProgramme']->value[($_smarty_tpl->tpl_vars['sMainLang']->value).("_spa_title")];?>
</td>
		<td><?php if ($_smarty_tpl->tpl_vars['aProgramme']->value['com_active']==$_smarty_tpl->tpl_vars['PROGRAMME_ACTIVE']->value){?>
				<a href="#" onclick="return false;" class="active_status"></a>
			<?php }else{ ?>
				<a href="#" onclick="return false;" class="inactive_status"></a>
			<?php }?></td>
	</tr>
	</tbody>
</table>
</div>
<div class="clearfix"></div>
<?php  $_smarty_tpl->tpl_vars['aImage'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aImage']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aImages']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aImage']->key => $_smarty_tpl->tpl_vars['aImage']->value){
$_smarty_tpl->tpl_vars['aImage']->_loop = true;
?>
	<div class="grid_8">
		<br />
		<label><?php echo $_smarty_tpl->tpl_vars['aImage']->value['title'];?>
</label>
		<?php if ($_smarty_tpl->tpl_vars['aImage']->value['com_active']==$_smarty_tpl->tpl_vars['PROGRAMME_IMAGE_ACTIVE']->value){?>
			<a class="active_status" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sDeactivateUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_programme_image_id'];?>
" title="Deactivate this image of the programme" onclick="return confirm('Are you sure?');"></a>
		<?php }else{ ?>
			<a class="inactive_status" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sActivateUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_programme_image_id'];?>
" title="Activate this image of the programme" onclick="return confirm('Are you sure?');"></a>
		<?php }?>
		&nbsp;
		&nbsp;
		<input type="text" class="order_input" id="<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_programme_image_id'];?>
_order_input" value="<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_order'];?>
" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_programme_image_id'];?>
_save_order" title="<?php echo vlang('Save order value');?>
"></a>
		&nbsp;
		&nbsp;
		<a class="button" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sEditUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_programme_image_id'];?>
" title="<?php echo vlang('Edit this image of the programme');?>
"><?php echo vlang('Edit');?>
</a>
		&nbsp;
		&nbsp;
		<a class="button" onclick="return confirm('<?php echo vlang('Are you sure?');?>
');" href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
<?php echo $_smarty_tpl->tpl_vars['sDeleteUrl']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_programme_image_id'];?>
" title="<?php echo vlang('Delete this image of the programme');?>
"><?php echo vlang('Delete');?>
</a>
		<br />
		<br />
		<img src="/<?php echo $_smarty_tpl->tpl_vars['sProgrammePictureDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_programme_image_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aImage']->value['com_image_ext'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['aImage']->value['title'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['aImage']->value['title'];?>
" />
		<br />
	</div>
<?php } ?><?php }} ?>