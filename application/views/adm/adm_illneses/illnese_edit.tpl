<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save illnese')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_8">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title', $aIllneseData.title)}" autocomplete="off" />
		</div>
		<div class="grid_4">
			<label>{vlang('Status')} <small>{vlang('Required')}</small></label>
			<select name="com_active">
				<option value="{$ILLNESE_INACTIVE}" {set_select('com_active', $ILLNESE_INACTIVE, $aIllneseData.com_active == $ILLNESE_INACTIVE)}>{vlang('Inactive')}</option>
				<option value="{$ILLNESE_ACTIVE}" {set_select('com_active', $ILLNESE_ACTIVE, $aIllneseData.com_active == $ILLNESE_ACTIVE)}>{vlang('Active')}</option>
			</select>
		</div>
		<div class="grid_4">
			<label>{vlang('Order')} <small>{vlang('Integer')}</small></label>
			<input type="text" name="com_order" value="{set_value('com_order', $aIllneseData.com_order)}" autocomplete="off" />
		</div>
		<div class="clear"></div>
		<div class="grid_16">
			<label>{vlang("Short description")}</label>
			<textarea name="short_desc">{set_value('short_desc', $aIllneseData.short_desc)}</textarea>
		</div>
		<div class="clear"></div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save illnese')}" />
		<a class="button cancel" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
{form_close()}