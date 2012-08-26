<h1>{$sSiteTitle}</h1>
<div class="grid_8">
	<h2>{vlang('Original')} ({ucfirst($lang_uri_abbr[$language_abbr])})</h2>
	
	<label>{vlang('Illnese')} <small>{vlang('required')}</small></label>
	{$aIllneseDefaultTranslateData.title}
	<br />
	
	<label>{vlang('Short description')}</label>
	{$aIllneseDefaultTranslateData.short_desc}
	<br />

</div>

<div class="grid_8">
{form_open()}
	
	<h2>{vlang('Translate')} ({ucfirst($lang_uri_abbr[$sLang])})</h2>

	<div class="center">
		<input type="submit" value="Save" />&nbsp;
		<a class="button" href="{$site_url}{$sCancelUrl}">Cancel</a>
	</div>
	<fieldset>	
		<label for="new_title">{vlang('Illnese')} <small>{vlang('Required')}</small></label>
		<input name="title" id="new_title" type="text" value="{set_value('title', $aIllneseTranslateData.title)}" autocomplete="off" />
	<br />
	<label for="new_short_desc">{vlang('Short description')}</label>
		<textarea name="short_desc" id="new_short_desc">{set_value('short_desc', $aIllneseTranslateData.short_desc)}</textarea>
	<br />

	</fieldset>	
		<div class="center">
			<input type="submit" value="Save" />&nbsp;
			<a class="button" href="{$site_url}{$sCancelUrl}">Cancel</a>
		</div>
	
</form>
</div>