<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save country')}" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_8">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title', $aCountryData.title)}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('ISO')} <small>{vlang('Required')}</small></label>
			<input type="text" name="com_iso" value="{set_value('com_iso', $aCountryData.com_iso)}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('Order')}  <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order', $aCountryData.com_order)}" autocomplete="off" />
		</div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save country')}" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
</form>