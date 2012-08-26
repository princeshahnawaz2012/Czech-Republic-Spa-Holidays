<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save supplier')}" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_4">
			<label for="new_com_title">{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" id="new_com_title" name="com_title" value="{set_value('com_title')}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label for="new_com_contact_currency_id">{vlang('Currency')} <small>{vlang('Required')}</small></label>
			<select id="new_com_contact_currency_id" name="com_contact_currency_id">
				<option value="">{vlang('-- select an option --')}</option>
				{foreach $aCurrencies as $aCurrency}
					<option value="{$aCurrency.com_currency_id}" {set_select('com_contact_currency_id', $aCurrency.com_currency_id)}>{$aCurrency.title}</option>
				{/foreach}
			</select>
		</div>
		<div class="grid_4">
			<label for="new_com_transfers_calc_type">{vlang('Trans Calc Type')} <small>{vlang('Required')}</small></label>
			<select id="new_com_transfers_calc_type" name="com_transfers_calc_type">
				<option value="{$TRANSFER_CALCULATION_TYPE_COMISSION}" {set_select('com_transfers_calc_type', $TRANSFER_CALCULATION_TYPE_COMISSION)}>{vlang('Comission')}</option>
				<option value="{$TRANSFER_CALCULATION_TYPE_MARK_UP}" {set_select('com_transfers_calc_type', $TRANSFER_CALCULATION_TYPE_MARK_UP)}>{vlang('Mark Up')}</option>
			</select>
		</div>
		<div class="grid_4">
			<label for="new_com_accounts_percent">{vlang('Trans Percent')}  <small>{vlang('Decimal')}</small></label>
			<input type="text" id="new_com_accounts_percent" name="com_transfers_percent" value="{set_value('com_transfers_percent')}" autocomplete="off" />
		</div>
		<div class="grid_8">
			<label for="new_com_accounts_contact">{vlang('Contact Name')}  <small>{vlang('Required')}</small></label>
			<input type="text" id="new_com_accounts_contact" name="com_accounts_contact" value="{set_value('com_accounts_contact')}" autocomplete="off" />
		</div>
		<div class="grid_8">
			<label for="new_com_accounts_email">{vlang('Contact E-mail')}  <small>{vlang('Required')}</small></label>
			<input type="text" id="new_com_accounts_email" name="com_accounts_email" value="{set_value('com_accounts_email')}" autocomplete="off" />
		</div>
		<div class="grid_16">
			<label for="new_com_office_contacts">{vlang('Office contacts')}  <small>{vlang('Required')}</small></label>
			<textarea id="new_com_office_contacts" name="com_office_contacts">{set_value('com_office_contacts')}</textarea>
		</div>
		<div class="grid_16">
			<label for="new_com_bank_details">{vlang('Bank details')}  <small>{vlang('Required')}</small></label>
			<textarea id="new_com_bank_details" name="com_bank_details">{set_value('com_bank_details')}</textarea>
		</div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save supplier')}" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
</form>