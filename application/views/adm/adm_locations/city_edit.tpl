<h1>{$sSiteTitle}</h1>
{form_open_multipart()}
	<div class="center">
		<input type="submit" value="{vlang('Save city')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_6">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title', $aCityData.title)}" autocomplete="off" />
		</div>
		<div class="grid_5">
			<label>{vlang('Region')} <small>{vlang('Required')}</small></label>
			<select name="com_region_id" id="new_region_title">
				<option value="">{vlang('-- select an option --')}</option>
				{foreach $aRegions as $aRegion}
					<option value={$aRegion.region_id} {set_select('com_region_id', $aRegion.region_id, $aRegion.region_id == $aCityData.com_region_id)}>{$aRegion.title}</option>
				{/foreach}
			</select>
		</div>
		<div class="grid_2">
			<label>&nbsp;</label>
			<a class="button" href="#" onclick="ajax_region_add(); return false;" id="button_regoin_add">{vlang('Add region')}</a>
		</div>
		<div class="grid_3">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order', $aCityData.com_order)}" autocomplete="off" />
		</div>
		<div class="clearfix"></div>
		<div class="grid_16">
			<label>Description</label>
			<textarea name="desc" id="new_desc" class="tinymce">{set_value('desc', $aCityData.desc)}</textarea>
		</div>
		<div class="clearfix"></div>
		<div class="grid_6">
			<label>{vlang('Map')}</label>
			<input type="hidden" name="com_map_ext" id="new_com_map_ext" value="{$aCityData.com_map_ext}" />
			<a class="button" id="button_map_upload" href="#">{vlang('Image')}</a>
			
		</div>
		<div class="grid_3">
			<label>{vlang('Emblem')}</label>
			<input name="emblem_label" id="new_emblem_label" type="text" value="{set_value('emblem_label', $aCityData.emblem_label)}" autocomplete="off" />
			<input type="hidden" name="com_emblem_ext" id="new_com_emblem_ext" value="{$aCityData.com_emblem_ext}" />
		</div>
		<div class="grid_2">
			<label>&nbsp;</label>
			<a class="button" id="button_emblem_upload" href="#">{vlang('Image')}</a>
		</div>
		<div class="grid_3">
			<label>{vlang('Flag')}</label>
			<input name="flag_label" id="new_flag_label" type="text" value="{set_value('flag_label', $aCityData.flag_label)}" autocomplete="off" />
			<input type="hidden" name="com_flag_ext" id="new_com_flag_ext" value="{$aCityData.com_flag_ext}" />
		</div>
		<div class="grid_2">
			<label>&nbsp;</label>
			<a class="button" id="button_flag_upload" href="#">{vlang('Image')}</a>
		</div>
		<div class="clearfix"></div>
		<div class="grid_6" id="map_image_block">
			<div class="panel">
				<div class="manage hide">
					<a href="#" class="button delete_image" title="Delete this image">{vlang('Delete')}</a>
					<a href="#" class="button crop_image" title="Crop this image on selected area">{vlang('Crop')}</a>
					<a href="#" class="button resize_image" title="Resize this image to size of selected area">{vlang('Resize')}</a>
					<a href="#" class="button rotate_image" title="Rotation this image clockwise 90 degree">{vlang('Rotate')}</a>
				</div>
				<div class="states hide">
					
					<span class="crop_w_label" title="Width">&nbsp;W:</span>
					<span class="crop_w"></span>
					<span class="crop_h_label" title="Height">&nbsp;H:</span>
					<span class="crop_h"></span>
					<span class="crop_x_label" title="Axis X">&nbsp;X:</span>
					<span class="crop_x"></span>
					<span class="crop_y_label" title="Axis Y">&nbsp;Y:</span>
					<span class="crop_y"></span>
				</div>
			</div>
			<div class="image_block">
				 {if $aCityData.com_map_ext!=''}
					 <img alt="map" src="{$sCityMapsDir}{$aCityData.com_city_id}.{$aCityData.com_map_ext}" />
				 {/if}
			</div>
		</div>
		<div class="grid_5" id="emblem_image_block">
			<div class="panel">
				<div class="manage hide">
					<a href="#" class="button delete_image" title="Delete this image">{vlang('Delete')}</a>
					<a href="#" class="button crop_image" title="Crop this image on selected area">{vlang('Crop')}</a>
					<a href="#" class="button resize_image" title="Resize this image to size of selected area">{vlang('Resize')}</a>
					<a href="#" class="button rotate_image" title="Rotation this image clockwise 90 degree">{vlang('Rotate')}</a>
				</div>
				<div class="states hide">
					
					<span class="crop_w_label" title="Width">&nbsp;W:</span>
					<span class="crop_w"></span>
					<span class="crop_h_label" title="Height">&nbsp;H:</span>
					<span class="crop_h"></span>
					<span class="crop_x_label" title="Axis X">&nbsp;X:</span>
					<span class="crop_x"></span>
					<span class="crop_y_label" title="Axis Y">&nbsp;Y:</span>
					<span class="crop_y"></span>
				</div>
			</div>
			<div class="image_block">
				 {if $aCityData.com_emblem_ext!=''}
					 <img alt="emblem" src="{$sCityEmblemsDir}{$aCityData.com_city_id}.{$aCityData.com_emblem_ext}" />
				 {/if}
			</div>
		</div>
		<div class="grid_5" id="flag_image_block">
			<div class="panel">
				<div class="manage hide">
					<a href="#" class="button delete_image" title="Delete this image">{vlang('Delete')}</a>
					<a href="#" class="button crop_image" title="Crop this image on selected area">{vlang('Crop')}</a>
					<a href="#" class="button resize_image" title="Resize this image to size of selected area">{vlang('Resize')}</a>
					<a href="#" class="button rotate_image" title="Rotation this image clockwise 90 degree">{vlang('Rotate')}</a>
				</div>
				<div class="states hide">
					
					<span class="crop_w_label" title="Width">&nbsp;W:</span>
					<span class="crop_w"></span>
					<span class="crop_h_label" title="Height">&nbsp;H:</span>
					<span class="crop_h"></span>
					<span class="crop_x_label" title="Axis X">&nbsp;X:</span>
					<span class="crop_x"></span>
					<span class="crop_y_label" title="Axis Y">&nbsp;Y:</span>
					<span class="crop_y"></span>
				</div>
			</div>
			<div class="image_block">
				 {if $aCityData.com_flag_ext!=''}
					 <img alt="flag" src="{$sCityFlagsDir}{$aCityData.com_city_id}.{$aCityData.com_flag_ext}" />
				 {/if}
			</div>
		</div>
		
		<input type="hidden" name="temp_name_map" id="temp_name_map" value="{set_value('temp_name_map', $sTempNameMap)}" />
		<input type="hidden" name="temp_name_flag" id="temp_name_flag" value="{set_value('temp_name_flag', $sTempNameFlag)}" />
		<input type="hidden" name="temp_name_emblem" id="temp_name_emblem" value="{set_value('temp_name_emblem', $sTempNameEmblem)}" /> 
	
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save city')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}