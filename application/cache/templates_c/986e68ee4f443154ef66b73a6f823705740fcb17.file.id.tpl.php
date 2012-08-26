<?php /* Smarty version Smarty 3.1.4, created on 2012-08-26 11:15:15
         compiled from "application//views/controllers/programmes/id.tpl" */ ?>
<?php /*%%SmartyHeaderCode:20546564735039db13579844-11767554%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '986e68ee4f443154ef66b73a6f823705740fcb17' => 
    array (
      0 => 'application//views/controllers/programmes/id.tpl',
      1 => 1345620978,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '20546564735039db13579844-11767554',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'aProgrammeData' => 0,
    'aSpaData' => 0,
    'aCityData' => 0,
    'aRegionData' => 0,
    'aCountryData' => 0,
    'sProgrammePicturesDir' => 0,
    'i' => 0,
    'aProgrammeImagesData' => 0,
    'nProgrammeImagesCount' => 0,
    'sCityMapsDir' => 0,
    'sCityFlagsDir' => 0,
    'sCityEmblemsDir' => 0,
    'aEssential_infosData' => 0,
    'aEssential_infoData' => 0,
    'sEssential_infoPicturesDir' => 0,
    'nEssential_infosCount' => 0,
    'nMedical_treatmentsCount' => 0,
    'aMedical_treatmentsData' => 0,
    'nFacilitiesCount' => 0,
    'aFacilitiesData' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty 3.1.4',
  'unifunc' => 'content_5039db13befd5',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5039db13befd5')) {function content_5039db13befd5($_smarty_tpl) {?><div class="material">
	<h2><?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'title');?>
 – <?php echo vlang('Spa Hotel');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aSpaData']->value,'title');?>
, <?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'title');?>
, <?php echo flang($_smarty_tpl->tpl_vars['aRegionData']->value,'title');?>
, <?php echo flang($_smarty_tpl->tpl_vars['aCountryData']->value,'title');?>
.</h2>
</div>
<div id="mini_baners">
	<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? 2+1 - (0) : 0-(2)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
	<table class="material_1" cellpadding="0" cellspacing="0" border="0">
		<tr><td class="m_image_1"><img src="/<?php echo $_smarty_tpl->tpl_vars['sProgrammePicturesDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aProgrammeImagesData']->value[$_smarty_tpl->tpl_vars['i']->value]['com_programme_image_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aProgrammeImagesData']->value[$_smarty_tpl->tpl_vars['i']->value]['com_image_ext'];?>
" alt="<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeImagesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
" /></td></tr>
		<tr><td class="m_link"><?php echo flang($_smarty_tpl->tpl_vars['aProgrammeImagesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</td></tr>
	</table>
	<?php }} ?>
	<div class="cl"></div>
	<div class="mini_baners_link">
		<strong><a href="#" onclick="toggle_more_photos(this); return false;"><?php echo vlang('See More Photos');?>
</a></strong>
	</div>
	<?php if ($_smarty_tpl->tpl_vars['nProgrammeImagesCount']->value>3){?>
	<div id="more_photos" class="display_none">
		<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['nProgrammeImagesCount']->value-1+1 - (3) : 3-($_smarty_tpl->tpl_vars['nProgrammeImagesCount']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 3, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
		<table class="material_1" cellpadding="0" cellspacing="0" border="0">
			<tr><td class="m_image_1"><img src="/<?php echo $_smarty_tpl->tpl_vars['sProgrammePicturesDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aProgrammeImagesData']->value[$_smarty_tpl->tpl_vars['i']->value]['com_programme_image_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aProgrammeImagesData']->value[$_smarty_tpl->tpl_vars['i']->value]['com_image_ext'];?>
" alt="<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeImagesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
" /></td></tr>
			<tr><td class="m_link"><?php echo flang($_smarty_tpl->tpl_vars['aProgrammeImagesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</td></tr>
		</table>
		<?php }} ?>
		<div class="cl"></div>
	</div>
	<?php }?>
</div>
 <div class="material_block">
	<div class="material">
		<div class="material_left">

			<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'description');?>


			<p>
				<strong><?php echo vlang('Programme Includes');?>
: </strong>
				<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'included');?>

			</p>

			<p> 
				<strong><?php echo vlang('Not Included');?>
: </strong>
				<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'notincluded');?>

			</p> 


			<p>
				<strong><?php echo vlang('Terms');?>
:</strong> 
				<?php echo flang($_smarty_tpl->tpl_vars['aProgrammeData']->value,'terms');?>

			</p>

			<!--
			<div class="buy_button">
				<a class="bt_push" href="/"><img src="images/buy_button.gif" alt="buy" /></a>
			</div>
			-->
			<div class="cl"></div>                

		</div>
		<div class="material_right">
			<img src="<?php echo $_smarty_tpl->tpl_vars['sCityMapsDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aCityData']->value['com_city_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aCityData']->value['com_map_ext'];?>
" alt="<?php echo vlang('Map of');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'title');?>
" title="<?php echo vlang('Map of');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'title');?>
" />
			<p>
			<?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'desc');?>

			</p>
			
			<table class="flag_emblem">
				<tr>
					<td class="picture_block">
						<img src="<?php echo $_smarty_tpl->tpl_vars['sCityFlagsDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aCityData']->value['com_city_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aCityData']->value['com_flag_ext'];?>
" alt="<?php echo vlang('Flag of');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'title');?>
" title="<?php echo vlang('Flag of');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'title');?>
" />
					</td>
					<td class="picture_block">
						<img src="<?php echo $_smarty_tpl->tpl_vars['sCityEmblemsDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aCityData']->value['com_city_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aCityData']->value['com_emblem_ext'];?>
" alt="<?php echo vlang('Emblem of');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'title');?>
" title="<?php echo vlang('Emblem of');?>
 <?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'title');?>
" />
					</td>
				</tr>
				<tr>
					<td class="picture_title">
						<?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'flag_label');?>

					</td>
					<td class="picture_title">
						<?php echo flang($_smarty_tpl->tpl_vars['aCityData']->value,'emblem_label');?>

					</td>
				</tr>
			</table>
					
		</div>
		<div class="cl"></div>
	</div>



	<div class="material_block">
		<div class="option_list">
			<p><strong><?php echo vlang('Spa Essential Info');?>
:</strong><p>
			<?php  $_smarty_tpl->tpl_vars['aEssential_infoData'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['aEssential_infoData']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['aEssential_infosData']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['aEssential_infoData']->key => $_smarty_tpl->tpl_vars['aEssential_infoData']->value){
$_smarty_tpl->tpl_vars['aEssential_infoData']->_loop = true;
?>
				<?php if ($_smarty_tpl->tpl_vars['aEssential_infoData']->value['com_picture_ext']){?>
					<table class="essential_pictures">
						<tr>
							<td>
								<img src="/<?php echo $_smarty_tpl->tpl_vars['sEssential_infoPicturesDir']->value;?>
<?php echo $_smarty_tpl->tpl_vars['aEssential_infoData']->value['com_essential_info_id'];?>
.<?php echo $_smarty_tpl->tpl_vars['aEssential_infoData']->value['com_picture_ext'];?>
" alt="<?php echo flang($_smarty_tpl->tpl_vars['aEssential_infoData']->value,'title');?>
" title="<?php echo flang($_smarty_tpl->tpl_vars['aEssential_infoData']->value,'title');?>
. <?php echo flang($_smarty_tpl->tpl_vars['aEssential_infoData']->value,'short_desc');?>
" />
							</td>
						</tr>
					</table>
				<?php }?>
			<?php } ?>
			<div class="cl"></div>
			<div class="material_2 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value/3.0)-1+1 - (0) : 0-(ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value/3.0)-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aEssential_infosData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aEssential_infosData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value*2.0/3.0)-1+1 - (ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value/3.0)) : ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value/3.0)-(ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value*2.0/3.0)-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value/3.0), $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aEssential_infosData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aEssential_infosData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['nEssential_infosCount']->value-1+1 - (ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value*2.0/3.0)) : ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value*2.0/3.0)-($_smarty_tpl->tpl_vars['nEssential_infosCount']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = ceil($_smarty_tpl->tpl_vars['nEssential_infosCount']->value*2.0/3.0), $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aEssential_infosData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aEssential_infosData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
				
				
			<div class="cl"></div>

		</div>
	</div>




	<div class="material_block">
		<div class="option_list">
			<p><strong><?php echo vlang('Spa Medical Treatments');?>
:</strong><p>
			<div class="material_2 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value/3.0)-1+1 - (0) : 0-(ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value/3.0)-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aMedical_treatmentsData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aMedical_treatmentsData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value*2.0/3.0)-1+1 - (ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value/3.0)) : ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value/3.0)-(ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value*2.0/3.0)-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value/3.0), $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aMedical_treatmentsData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aMedical_treatmentsData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value-1+1 - (ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value*2.0/3.0)) : ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value*2.0/3.0)-($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = ceil($_smarty_tpl->tpl_vars['nMedical_treatmentsCount']->value*2.0/3.0), $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aMedical_treatmentsData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aMedical_treatmentsData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
				
				
			<div class="cl"></div>
			
			
		</div>
	</div>
				
	
				

	<div class="material_block">
		<div class="option_list">
			<p><strong><?php echo vlang('Spa Facilities');?>
:</strong><p>
			<div class="material_2 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value/3.0)-1+1 - (0) : 0-(ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value/3.0)-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = 0, $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aFacilitiesData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aFacilitiesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value*2.0/3.0)-1+1 - (ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value/3.0)) : ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value/3.0)-(ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value*2.0/3.0)-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value/3.0), $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aFacilitiesData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aFacilitiesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					<?php $_smarty_tpl->tpl_vars['i'] = new Smarty_Variable;$_smarty_tpl->tpl_vars['i']->step = 1;$_smarty_tpl->tpl_vars['i']->total = (int)ceil(($_smarty_tpl->tpl_vars['i']->step > 0 ? $_smarty_tpl->tpl_vars['nFacilitiesCount']->value-1+1 - (ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value*2.0/3.0)) : ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value*2.0/3.0)-($_smarty_tpl->tpl_vars['nFacilitiesCount']->value-1)+1)/abs($_smarty_tpl->tpl_vars['i']->step));
if ($_smarty_tpl->tpl_vars['i']->total > 0){
for ($_smarty_tpl->tpl_vars['i']->value = ceil($_smarty_tpl->tpl_vars['nFacilitiesCount']->value*2.0/3.0), $_smarty_tpl->tpl_vars['i']->iteration = 1;$_smarty_tpl->tpl_vars['i']->iteration <= $_smarty_tpl->tpl_vars['i']->total;$_smarty_tpl->tpl_vars['i']->value += $_smarty_tpl->tpl_vars['i']->step, $_smarty_tpl->tpl_vars['i']->iteration++){
$_smarty_tpl->tpl_vars['i']->first = $_smarty_tpl->tpl_vars['i']->iteration == 1;$_smarty_tpl->tpl_vars['i']->last = $_smarty_tpl->tpl_vars['i']->iteration == $_smarty_tpl->tpl_vars['i']->total;?>
						<li title="<?php echo flang($_smarty_tpl->tpl_vars['aFacilitiesData']->value[$_smarty_tpl->tpl_vars['i']->value],'short_desc');?>
"><?php echo flang($_smarty_tpl->tpl_vars['aFacilitiesData']->value[$_smarty_tpl->tpl_vars['i']->value],'title');?>
</li>
					<?php }} ?>
				</ul>
			</div>
			
				
			<div class="cl"></div>
			
		</div>
	</div>
<!--
	<div class="buy_button_1"> <a class="bt_push" href="/"><img src="images/buy_button.gif" alt="buy" /></a></div>
-->

 </div><?php }} ?>