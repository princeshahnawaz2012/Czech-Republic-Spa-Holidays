<h1>{$sSiteTitle}</h1>
{form_open_multipart()}
	<div class="center">
		<input type="submit" value="{vlang('Save image')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_4">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title')}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('Status')} <small>{vlang('Required')}</small></label>
			<select name="com_active">
				<option value="{$PROGRAMME_IMAGE_ACTIVE}" {set_select('com_active', $PROGRAMME_IMAGE_ACTIVE)}>{vlang('Active')}</option>
				<option value="{$PROGRAMME_IMAGE_INACTIVE}" {set_select('com_active', $PROGRAMME_IMAGE_INACTIVE)}>{vlang('Inactive')}</option>
			</select>
		</div>
		<div class="grid_2">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order')}" autocomplete="off" />
		</div>
		<div class="clearfix"></div>
		<div class="grid_2">
			<label>{vlang('Picture')}</label>
			<input type="hidden" name="com_image_ext" id="new_com_picture_ext" value="" />
			<a class="button" id="button_picture_upload" href="#">{vlang('Image')}</a>
		</div>
		<div class="grid_8" id="picture_image_block">
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
			<div class="image_block"></div>
		</div>
		<div class="clearfix"></div>
		
		<input type="hidden" name="temp_name_picture" id="temp_name_picture" value="{set_value('temp_name_picture', $sTempNamePicture)}" />
	
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save image')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}