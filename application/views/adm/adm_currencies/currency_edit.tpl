<h1>{$sSiteTitle}</h1>
{form_open_multipart()}
	<div class="center">
		<input type="submit" value="{vlang('Save currency')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_2">
			<label>{vlang('ISO')} <small>{vlang('Required')}</small></label>
			<input type="text" name="com_currency_id" value="{set_value('com_currency_id', $aCurrencyData['com_currency_id'])}" autocomplete="off" />
		</div>
		<div class="grid_6">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title', $aCurrencyData['title'])}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order', $aCurrencyData['com_order'])}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('Status')} <small>{vlang('Required')}</small></label>
			<select name="com_active">
				<option value="{$CURRENCY_INACTIVE}" {set_select('com_active', $CURRENCY_INACTIVE, $aCurrencyData['com_active'] == $CURRENCY_INACTIVE)}>{vlang('Inactive')}</option>
				<option value="{$CURRENCY_ACTIVE}" {set_select('com_active', $CURRENCY_ACTIVE, $aCurrencyData['com_active'] == $CURRENCY_ACTIVE)}>{vlang('Active')}</option>
			</select>
		</div>
		<div class="clearfix"></div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save currency')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}