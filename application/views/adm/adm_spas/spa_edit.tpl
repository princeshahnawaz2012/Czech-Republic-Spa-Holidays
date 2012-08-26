<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save spa')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_4">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title', $aSpaData.title)}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('City')} <small>{vlang('Required')}</small></label>
			<select name="com_city_id" id="new_city_title">
				<option value="">{vlang('-- select an option --')}</option>
				{foreach $aCities as $aCity}
					<option value={$aCity.com_city_id} {set_select('com_city_id', $aCity.com_city_id, $aCity.com_city_id == $aSpaData.com_city_id)}>{$aCity.title}</option>
				{/foreach}
			</select>
		</div>
		<div class="grid_3">
			<label>{vlang('Status')} <small>{vlang('Required')}</small></label>
			<select name="com_active">
				<option value="{$SPA_INACTIVE}" {set_select('com_active', $SPA_INACTIVE, $aSpaData.com_active == $SPA_INACTIVE)}>{vlang('Inactive')}</option>
				<option value="{$SPA_ACTIVE}" {set_select('com_active', $SPA_ACTIVE, $aSpaData.com_active == $SPA_ACTIVE)}>{vlang('Active')}</option>
			</select>
		</div>
		<div class="grid_3">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order', $aSpaData.com_order)}" autocomplete="off" />
		</div>
		<div class="grid_2">
			<label>&nbsp;</label>
			<a class="button" href="{$site_url}adm_locations/city_add" onclick="ajax_city_add(); return false;" id="button_city_add">{vlang('Add city')}</a>
		</div>
		<div class="clear"></div>
		<div class="grid_8">
			<label>{vlang("Reponsible's e-mail for reservation")} <small>{vlang('Required')}</small></label>
			<input type="text" name="com_reservation_email" value="{set_value('com_reservation_email', $aSpaData.com_reservation_email)}" autocomplete="off" />
		</div>
		<div class="grid_8">
			<label>{vlang("Reponsible's name for reservation")} <small>{vlang('Required')}</small></label>
			<input type="text" name="com_reservation_name" value="{set_value('com_reservation_name', $aSpaData.com_reservation_name)}" autocomplete="off" />
		</div>
		<div class="clear"></div>
		<div class="grid_8">
			<label>{vlang("2nd reponsible's e-mail for reservation")}</label>
			<input type="text" name="com_reservation_email2" value="{set_value('com_reservation_email2', $aSpaData.com_reservation_email2)}" autocomplete="off" />
		</div>
		<div class="grid_8">
			<label>{vlang("2nd reponsible's name for reservation")}</label>
			<input type="text" name="com_reservation_name2" value="{set_value('com_reservation_name2', $aSpaData.com_reservation_name2)}" autocomplete="off" />
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			<label>{vlang("Contacts")} <small>{vlang('Required')}</small></label>
			<textarea name="com_contacts">{set_value('com_contacts', $aSpaData.com_contacts)}</textarea>
		</div>
		<div class="clear"></div>
		<div class="grid_6">
			<label>{vlang('Calculation of prices in offseason')} <small>{vlang('Required')}</small></label>
			<select name="com_midseason_pay_type">
				<option value="{$OFFSEASON_CALCULATION_BY_FIRST_SEASON}" {set_select('com_midseason_pay_type', $OFFSEASON_CALCULATION_BY_FIRST_SEASON, $aSpaData.com_midseason_pay_type == $OFFSEASON_CALCULATION_BY_FIRST_SEASON)}>{vlang('By first season')}</option>
				<option value="{$OFFSEASON_CALCULATION_BY_SECOND_SEASON}" {set_select('com_midseason_pay_type', $OFFSEASON_CALCULATION_BY_SECOND_SEASON, $aSpaData.com_midseason_pay_type == $OFFSEASON_CALCULATION_BY_SECOND_SEASON)}>{vlang('By second season')}</option>
				<option value="{$OFFSEASON_CALCULATION_BY_BOTH_SEASON}" {set_select('com_midseason_pay_type', $OFFSEASON_CALCULATION_BY_BOTH_SEASON, $aSpaData.com_midseason_pay_type == $OFFSEASON_CALCULATION_BY_BOTH_SEASON)}>{vlang('By both seasons')}</option>
			</select>
		</div>
		<div class="clear"></div>
		<div id="essential_infos" class="grid_6">
			<br />
			<a class="button" href="{$site_url}adm_essential_infos/essential_info_add" onclick="ajax_essential_info_add(); return false;">{vlang('Add essential info')}</a>
			<br />
			<br />
			<label>{vlang('Essential info')}</label>
			<div class="master_list">
				{foreach from=$aEssential_infosIds key=nEssential_infoId item=sEssential_infoTitle}
					<label><input type="checkbox" name="com_essential_info_id[]" value="{$nEssential_infoId}" {set_checkbox('com_essential_info_id', $nEssential_infoId, in_array($nEssential_infoId, $aSpaEssential_infosIds))} />{$sEssential_infoTitle}</label><br />
				{/foreach}
			</div>
		</div>
		<div id="medical_treatments" class="grid_5">
			<br />
			<a class="button" href="{$site_url}adm_medical_treatments/medical_treatment_add" onclick="ajax_medical_treatment_add(); return false;">{vlang('Add medical treament')}</a>
			<br />
			<br />
			<label>{vlang('Medical treatments')}</label>
			<div class="master_list">
				{foreach from=$aMedical_treatmentsIds key=nMedical_treatmentId item=sMedical_treatmentTitle}
					<label><input type="checkbox" name="com_medical_treatment_id[]" value="{$nMedical_treatmentId}" {set_checkbox('com_medical_treatment_id', $nMedical_treatmentId, in_array($nMedical_treatmentId, $aSpaMedical_treatmentsIds))} />{$sMedical_treatmentTitle}</label><br />
				{/foreach}
			</div>
		</div>
		<div id="facilities" class="grid_5">
			<br />
			<a class="button" href="{$site_url}adm_facilities/facility_add" onclick="ajax_facility_add(); return false;">{vlang('Add facility')}</a>
			<br />
			<br />
			<label>{vlang('Facilities')}</label>
			<div class="master_list">
				{foreach from=$aFacilitiesIds key=nFacilityId item=sFacilityTitle}
					<label><input type="checkbox" name="com_facility_id[]" value="{$nFacilityId}" {set_checkbox('com_facility_id', $nFacilityId, in_array($nFacilityId, $aSpaFacilitiesIds))} />{$sFacilityTitle}</label><br />
				{/foreach}
			</div>
		</div>
		<div class="clear"></div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save spa')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}