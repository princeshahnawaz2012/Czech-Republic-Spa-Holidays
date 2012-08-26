<h1>{$sSiteTitle}</h1>
<div class="grid_8">
	<h2>{vlang('Original')} ({ucfirst($lang_uri_abbr[$language_abbr])})</h2>
	
	<label>{vlang('City')} <small>{vlang('required')}</small></label>
	{$aCityDefaultTranslateData.title}
	
	<label>{vlang('Flag Label')}</label>
	{$aCityDefaultTranslateData.flag_label}

	<label>{vlang('Emblem Label')}</label>
	{$aCityDefaultTranslateData.emblem_label}
	
	<label>{vlang('Description')}</label>
	{$aCityDefaultTranslateData.desc}
</div>

<div class="grid_8">
{form_open()}
	
	<h2>{vlang('Translate')} ({ucfirst($lang_uri_abbr[$sLang])})</h2>

	<div class="center">
		<input type="submit" value="Save" />&nbsp;
		<a class="button" href="{$site_url}{$sCancelUrl}">Cancel</a>
	</div>
	<fieldset>	
		<label>{vlang('City')} <small>{vlang('Required')}</small></label>
		<input name="title" id="new_title" type="text" value="{set_value('title', $aCityTranslateData.title)}" autocomplete="off" />
	
		<label>{vlang('Flag Label')}</label>
		<input name="flag_label" id="new_flag_label" type="text" value="{set_value('flag_label', $aCityTranslateData.flag_label)}" autocomplete="off" />
		{$aCityDefaultTranslateData.flag_label}
	
		<label>{vlang('Emblem Label')}</label>
		<input name="emblem_label" id="new_emblem_label" type="text" value="{set_value('emblem_label', $aCityTranslateData.emblem_label)}" autocomplete="off" />
	
	<label>{vlang('Description')}</label>
	<textarea name="desc">{set_value('desc', $aCityTranslateData.desc)}</textarea>
	
	</fieldset>	
	<div class="center">
		<input type="submit" value="Save" />&nbsp;
		<a class="button" href="{$site_url}{$sCancelUrl}">Cancel</a>
	</div>
	
</form>
</div>