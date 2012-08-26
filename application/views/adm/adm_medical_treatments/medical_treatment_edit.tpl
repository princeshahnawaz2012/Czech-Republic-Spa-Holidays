<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save medical treatment')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_8">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title', $aMedical_treatmentData.title)}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('Status')} <small>{vlang('Required')}</small></label>
			<select name="com_active">
				<option value="{$MEDICAL_TREATMENT_INACTIVE}" {set_select('com_active', $MEDICAL_TREATMENT_INACTIVE, $aMedical_treatmentData.com_active == $MEDICAL_TREATMENT_INACTIVE)}>{vlang('Inactive')}</option>
				<option value="{$MEDICAL_TREATMENT_ACTIVE}" {set_select('com_active', $MEDICAL_TREATMENT_ACTIVE, $aMedical_treatmentData.com_active == $MEDICAL_TREATMENT_ACTIVE)}>{vlang('Active')}</option>
			</select>
		</div>
		<div class="grid_4">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order', $aMedical_treatmentData.com_order)}" autocomplete="off" />
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			<label>{vlang("Short description")}</label>
			<textarea name="short_desc">{set_value('short_desc', $aMedical_treatmentData.short_desc)}</textarea>
		</div>
		<div class="clear"></div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save medical treatment')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}