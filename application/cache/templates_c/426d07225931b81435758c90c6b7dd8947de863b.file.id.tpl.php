<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:15:02
         compiled from "application//views/controllers/categories/id.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1434600265039db06e5d187-16204036%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '426d07225931b81435758c90c6b7dd8947de863b' => 
    array (
      0 => 'application//views/controllers/categories/id.tpl',
      1 => 1345591646,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1434600265039db06e5d187-16204036',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aCategoryData' => 0,
    'sCategoryPicturesDir' => 0,
    'nIllnesesDataCount' => 0,
    'i' => 0,
    'aIllnesesData' => 0,
    'sType' => 0,
    'CATEGORY_SHOW_SHORT_DESCRIPTION' => 0,
    'aProgrammesData' => 0,
    'site_url' => 0,
    'aProgrammeData' => 0,
    'aProgrammesIllnesesData' => 0,
    'aProgrammeIllnesesData' => 0,
    'aCitiesTitle' => 0,
    'aSpasTitle' => 0,
    'price_array' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039db07338c5',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039db07338c5')) {function content_5039db07338c5($_smarty_tpl) {?><div class="material">
	<h2><?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'title');?>
</h2>
<table class="tree_part_table" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td width="30%">
		<div class="some_text_table">
			<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'desc');?>

		</div>
		<img src="/<?php echo $_smarty_tpl->tpl_vars['sCategoryPicturesDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aCategoryData']->value['com_category_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aCategoryData']->value['com_picture_ext'];?>
" alt="<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'title');?>
" title="<?php echo flang($_smarty_tpl->tpl_vars['aCategoryData']->value,'title');?>
" />
		</td>
		<td width="70%">
			<div class="right_half_table" id="category_filter">
				<strong><?php echo vlang('Define and see the programmes that you wish to have dispalyed:');?>
 </strong>
				<div class="half_table">
					<ul>
						<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? ceil($_smarty_tpl->tpl_vars['nIllnesesDataCount']->value/2.0)-1+1 - (0) : 0-(ceil($_smarty_tpl->tpl_vars['nIllnesesDataCount']->value/2.0)-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
							<li><label title="<?php echo flang($_smarty_tpl->tpl_vars['aIllnesesData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><input type="checkbox" name="illneses[]" value="<?php echo $_smarty_tpl->tpl_vars['aIllnesesData']->value[$_smarty_tpl->tpl_vars['i']->value]['com_illnese_id'];?>
" /><?php echo flang($_smarty_tpl->tpl_vars['aIllnesesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</label></li>
						<?php }} ?>
					</ul>
				</div>
				<div class="half_table">
					<ul>
						<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['nIllnesesDataCount']->value-1+1 - (ceil($_smarty_tpl->tpl_vars['nIllnesesDataCount']->value/2.0)) : ceil($_smarty_tpl->tpl_vars['nIllnesesDataCount']->value/2.0)-($_smarty_tpl->tpl_vars['nIllnesesDataCount']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = ceil($_smarty_tpl->tpl_vars['nIllnesesDataCount']->value/2.0), $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
							<li><label title="<?php echo flang($_smarty_tpl->tpl_vars['aIllnesesData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><input type="checkbox" name="illneses[]" value="<?php echo $_smarty_tpl->tpl_vars['aIllnesesData']->value[$_smarty_tpl->tpl_vars['i']->value]['com_illnese_id'];?>
" /><?php echo flang($_smarty_tpl->tpl_vars['aIllnesesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</label></li>
						<?php }} ?>
					</ul>
				</div>
				<div class="cl"></div>
				<div class="reset_all">
					<a  class="bt_push" href="#" id="reset_category_filter"><img src="/images/reset_all_button.gif" alt="<?php echo vlang('Reset all');?>
"</a>
				</div>
			</div>
		</td>

	</tr>
</table>
</div>

<div class="big_table">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td class="tb_h_1"><?php echo vlang('Programme');?>
</td>
				<td class="tb_h_2">
					<?php if ($_smarty_tpl->tpl_vars['sType']->value==$_smarty_tpl->tpl_vars['CATEGORY_SHOW_SHORT_DESCRIPTION']->value){?>
						<?php echo vlang('Description');?>

					<?php }else{ ?>
						<?php echo vlang('Recommended for');?>

					<?php }?></td>
				<td class="tb_h_3"><?php echo vlang('Location');?>
</td>
				<td class="tb_h_4"><?php echo vlang('Spa');?>
</td>
				<td class="tb_h_5"><?php echo vlang('Price per course');?>
</td>
			</tr>
		</thead>
		<tbody>
			<?php  $_smarty_tpl->tpl_vars['aProgrammeData'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aProgrammeData']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aProgrammesData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aProgrammeData']->key => $_smarty_tpl->tpl_vars['aProgrammeData']->value){
$_smarty_tpl->tpl_vars['aProgrammeData']->_loop = true;
?>
			<tr>
				<td class="tb_h_1">
					<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
programmes/id/<?php echo $_smarty_tpl->tpl_vars['aProgrammeData']->value['com_programme_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'seo_link');?>
" title="<?php echo vlang('View details of programme');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'title');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'title');?>
</a>
				</td>
				<td class="tb_h_2">
					<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
programmes/id/<?php echo $_smarty_tpl->tpl_vars['aProgrammeData']->value['com_programme_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'seo_link');?>
" title="<?php echo vlang('View details of programme');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'title');?>
">
						<?php if ($_smarty_tpl->tpl_vars['sType']->value==$_smarty_tpl->tpl_vars['CATEGORY_SHOW_SHORT_DESCRIPTION']->value){?>
							<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'short_desc');?>

						<?php }else{ ?>
							<?php  $_smarty_tpl->tpl_vars['aProgrammeIllnesesData'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aProgrammeIllnesesData']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aProgrammesIllnesesData']->value[$_smarty_tpl->tpl_vars['aProgrammeData']->value['com_programme_id']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aProgrammeIllnesesData']->key => $_smarty_tpl->tpl_vars['aProgrammeIllnesesData']->value){
$_smarty_tpl->tpl_vars['aProgrammeIllnesesData']->_loop = true;
?>
								<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeIllnesesData']->value,'title');?>
,&nbsp;
							<?php } ?>
						<?php }?>
					</a>
				</td>
				<td class="tb_h_3">
					<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
programmes/id/<?php echo $_smarty_tpl->tpl_vars['aProgrammeData']->value['com_programme_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'seo_link');?>
" title="<?php echo vlang('View details of programme');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'title');?>
"><?php echo $_smarty_tpl->tpl_vars['aCitiesTitle']->value[$_smarty_tpl->tpl_vars['aProgrammeData']->value['com_city_id']];?>
</a>
				</td>
				<td class="tb_h_4">
					<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
programmes/id/<?php echo $_smarty_tpl->tpl_vars['aProgrammeData']->value['com_programme_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'seo_link');?>
" title="<?php echo vlang('View details of programme');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'title');?>
"><?php echo $_smarty_tpl->tpl_vars['aSpasTitle']->value[$_smarty_tpl->tpl_vars['aProgrammeData']->value['com_spa_id']];?>
</a>
				</td>
				<td class="tb_h_5">
					<a href="<?php echo $_smarty_tpl->tpl_vars['site_url']->value;?>
programmes/id/<?php echo $_smarty_tpl->tpl_vars['aProgrammeData']->value['com_programme_id'];?>
/<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'seo_link');?>
" title="<?php echo vlang('View details of programme');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'title');?>
"><?php $_smarty_tpl->tpl_vars['price_array'] = new Smarty_variable(array($_smarty_tpl->tpl_vars['aProgrammeData']->value['com_price_from'],$_smarty_tpl->tpl_vars['aProgrammeData']->value['com_currency_id']), null, 0);?><?php echo vlang('Price from per person',$_smarty_tpl->tpl_vars['price_array']->value);?>
</a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
</div><?php }} ?>