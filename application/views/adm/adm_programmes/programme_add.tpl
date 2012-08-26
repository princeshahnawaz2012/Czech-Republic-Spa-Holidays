<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save programme')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_7">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title')}" autocomplete="off" />
		</div>
		<div class="grid_7">
			<label>{vlang('Category')} <small>{vlang('Required')}</small></label>
			<select name="com_category_id" id="new_category_title">
				<option value="">{vlang('-- select an option --')}</option>
				{foreach $aCategories as $aCategory}
					<option value={$aCategory.com_category_id} {set_select('com_category_id', $aCategory.com_category_id)}>{$aCategory.title}</option>
				{/foreach}
			</select>
		</div>
		<div class="grid_2">
			<label>&nbsp;</label>
			<a class="button" href="{$site_url}adm_categories/category_add" onclick="return true; ajax_category_add(); return false;" id="button_category_add">{vlang('Add category')}</a>
		</div>
		<div class="clear"></div>		
		<div class="grid_6">
			<label>{vlang('Hotel spa')} <small>{vlang('Required')}</small></label>
			<select name="com_spa_id" id="new_spa_id">
				<option value="">{vlang('-- select an option --')}</option>
				{foreach $aSpas as $aSpa}
					<option value={$aSpa.com_spa_id} {set_select('com_spa_id', $aSpa.com_spa_id)}>{$aSpa.title}</option>
				{/foreach}
			</select>
		</div>
		<div class="grid_2">
			<label>&nbsp;</label>
			<a class="button" href="{$site_url}adm_spas/spa_add" onclick="ajax_spa_add(); return false;" id="button_spa_add">{vlang('Add spa')}</a>
		</div>
		<div class="grid_8">
			<label>{vlang('City')} <small>{vlang('Required')}</small></label>
			<input type="hidden" name="com_city_id" id="new_city_id" value="{set_value('com_city_id')}" />
			<input type="text" name="city_title" id="new_city_title" value="{set_value('city_title')}" disabled="disabled" />
		</div>
		<div class="clear"></div>
		<div class="grid_3">
			<label>{vlang('Status')} <small>{vlang('Required')}</small></label>
			<select name="com_active">
				<option value="{$PROGRAMME_INACTIVE}" {set_select('com_active', $PROGRAMME_INACTIVE)}>{vlang('Inactive')}</option>
				<option value="{$PROGRAMME_ACTIVE}" {set_select('com_active', $PROGRAMME_ACTIVE)}>{vlang('Active')}</option>
			</select>
		</div>
		<div class="grid_3">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order')}" autocomplete="off" />
		</div>
		<div class="grid_3">
			<label>{vlang('Price from')} <small>{vlang('Decimal')}</small></label>
			<input type="text" name="com_price_from" value="{set_value('com_price_from')}" autocomplete="off" />
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
		<div class="grid_4">
			<label>{vlang('SEO link')}</label>
			<input type="text" name="seo_link" value="{set_value('seo_link')}" autocomplete="off" />
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			<label>{vlang("Short description")}</label>
			<textarea name="short_desc">{set_value('short_desc')}</textarea>
		</div>
		<div class="clear"></div>
		<div class="grid_8">
			<label>{vlang("Meta keywords")}</label>
			<textarea name="metakeywords">{set_value('metakeywords')}</textarea>
		</div>
		<div class="grid_8">
			<label>{vlang("Meta description")}</label>
			<textarea name="metadescription">{set_value('metadescription')}</textarea>
		</div>
		<div class="clear"></div>
		<div class="grid_8">
			<label>{vlang("Description")}</label>
			<textarea class="tinymce" name="description">{set_value('description')}</textarea>
		</div>
		<div class="grid_8">
			<label>{vlang("Terms")}</label>
			<textarea class="tinymce" name="terms">{set_value('terms')}</textarea>
		</div>
		<div class="clear"></div>
		<div class="grid_8">
			<label>{vlang("Included")}</label>
			<textarea class="tinymce" name="included">{set_value('included')}</textarea>
		</div>
		<div class="grid_8">
			<label>{vlang("Not included")}</label>
			<textarea class="tinymce" name="notincluded">{set_value('notincluded')}</textarea>
		</div>
		<div class="clear"></div>
		<div id="illneses" class="grid_16">
			<br />
			<a class="button" href="{$site_url}adm_illneses/illnese_add" onclick="ajax_illnese_add(); return false;">{vlang('Add illnese')}</a>
			<br />
			<br />
			<label>{vlang('Illneses')}</label>
			<div class="master_list">
				{foreach from=$aIllnesesIds key=nIllneseId item=sIllneseTitle}
					<label><input type="checkbox" name="com_illnese_id[]" value="{$nIllneseId}" {set_checkbox('com_illnese_id', $nIllneseId)} />{$sIllneseTitle}</label><br />
				{/foreach}
			</div>
		</div>
		<div class="clear"></div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save programme')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}