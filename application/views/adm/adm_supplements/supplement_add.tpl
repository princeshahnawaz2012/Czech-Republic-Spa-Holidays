<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save supplement')}" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_4">
			<label for="new_com_title">{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" id="new_com_title" name="com_title" value="{set_value('com_title')}" autocomplete="off" />
		</div>
		<div class="grid_3">
			<label for="new_com_date_from">{vlang('From')} <small>{vlang('yyyy-mm-dd')}</small></label>
			<input type="text" class="datepicker" id="new_com_date_from" name="com_date_from" value="{set_value('com_date_from')}" autocomplete="off" />
		</div>
		<div class="grid_3">
			<label for="new_com_date_till">{vlang('Till')} <small>{vlang('yyyy-mm-dd')}</small></label>
			<input type="text" class="datepicker" id="new_com_date_till" name="com_date_till" value="{set_value('com_date_till')}" autocomplete="off" />
		</div>
		<div class="grid_3">
			<label for="new_com_price">{vlang('Price')} <small>{vlang('Required')} {vlang('Decimal')}</small></label>
			<input type="text" id="new_com_price" name="com_price" value="{set_value('com_price')}" autocomplete="off" />
		</div>
		<div class="grid_3">
			<label for="new_com_currency_id">{vlang('Currency')} <small>{vlang('Required')}</small></label>
			<select id="new_com_currency_id" name="com_currency_id">
				<option value="">{vlang('-- select an option --')}</option>
				{foreach $aCurrencies as $aCurrency}
					<option value="{$aCurrency.com_currency_id}" {set_select('com_currency_id', $aCurrency.com_currency_id)}>{$aCurrency.title}</option>
				{/foreach}
			</select>
		</div>
		<div class="clear"></div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save supplement')}" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
</form>