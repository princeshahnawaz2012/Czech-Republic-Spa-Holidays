<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save country')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_8">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input id="country_title_input" type="text" name="title" value="{set_value('title')}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('ISO')} <small>{vlang('Required')}</small></label>
			<input id="country_iso_input" type="text" name="com_iso" value="{set_value('com_iso')}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('Order')}  <small>{vlang('Integer')}</small></label>
			<input id="country_order_input" type="text" name="com_order" value="{set_value('com_order')}" autocomplete="off" />
		</div>
	</fieldset>
	<div class="center">
		<input id="country_submit_button" type="submit" value="{vlang('Save country')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}