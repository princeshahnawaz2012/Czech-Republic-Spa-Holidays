<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save region')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_6">
			<label for="region_title_input">{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input id="region_title_input" type="text" name="title" value="{set_value('title')}" autocomplete="off" />
		</div>
		<div class="grid_5">
			<label>{vlang('Country')} <small>{vlang('Required')}</small></label>
			<select name="com_country_id" id="new_country_title">
				<option value="">{vlang('-- select an option --')}</option>
				{foreach $aCountries as $aCountry}
					<option value={$aCountry.country_id} {set_select('com_country_id', $aCountry.country_id)}>{$aCountry.title}</option>
				{/foreach}
			</select>
		</div>
		<div class="grid_2">
			<label>&nbsp;</label>
			<a class="button" href="#" onclick="ajax_country_add(); return false;" id="button_country_add">{vlang('Add country')}</a>
		</div>
		<div class="grid_3">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input id="region_order_input" type="text" name="com_order" value="{set_value('com_order')}" autocomplete="off" />
		</div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save region')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}