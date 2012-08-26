<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save facility')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_8">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title')}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('Status')} <small>{vlang('Required')}</small></label>
			<select name="com_active">
				<option value="{$FACILITY_ACTIVE}" {set_select('com_active', $FACILITY_ACTIVE)}>{vlang('Active')}</option>
				<option value="{$FACILITY_INACTIVE}" {set_select('com_active', $FACILITY_INACTIVE)}>{vlang('Inactive')}</option>
			</select>
		</div>
		<div class="grid_4">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order')}" autocomplete="off" />
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			<label>{vlang("Short description")}</label>
			<textarea name="short_desc">{set_value('short_desc')}</textarea>
		</div>
		<div class="clear"></div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save facility')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}