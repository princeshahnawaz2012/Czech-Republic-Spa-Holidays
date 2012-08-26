<h1>{$sSiteTitle}</h1>
<div class="grid_8">
	<h2>{vlang('Original')} ({ucfirst($lang_uri_abbr[$language_abbr])})</h2>
	
	<label>{vlang('Category')}</label>
	{$aCategoryDefaultTranslateData.title}
	<br />
	<label>{vlang('SEO link')}</label>
	{$aCategoryDefaultTranslateData.seo_link}
	<br />
	<label>{vlang('Short Description')}</label>
	{$aCategoryDefaultTranslateData.short_desc}
	<br />
	<label>{vlang('Description')}</label>
	{$aCategoryDefaultTranslateData.desc}
	<br />
	<label>{vlang('Meta keywords')}</label>
	{$aCategoryDefaultTranslateData.metakeywords}
	<br />
	<label>{vlang('Meta Description')}</label>
	{$aCategoryDefaultTranslateData.metadescription}
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
		<label>{vlang('Category')} <small>{vlang('Required')}</small></label>
		<input name="title" id="new_title" type="text" value="{set_value('title', $aCategoryTranslateData.title)}" autocomplete="off" />
		<br />
		<label>{vlang('SEO link')}</label>
		<input name="seo_link" id="new_seo_link" type="text" value="{set_value('seo_link', $aCategoryTranslateData.seo_link)}" autocomplete="off" />
		<br />
		<label>{vlang('Short Description')} <small>{vlang('Required')}</small></label>
		<textarea name="short_desc" id="new_short_desc">{set_value('short_desc', $aCategoryTranslateData.short_desc)}</textarea>
		<br />
		<label>{vlang('Description')} <small>{vlang('Required')}</small></label>
		<textarea name="desc" id="new_desc">{set_value('desc', $aCategoryTranslateData.desc)}</textarea>
		<br />
		<label>{vlang('Meta keywords')}</label>
		<textarea name="metakeywords" id="new_metakeywords">{set_value('metakeywords', $aCategoryTranslateData.metakeywords)}</textarea>
		<br />
		<label>{vlang('Meta description')}</label>
		<textarea name="metadescription" id="new_metadescription">{set_value('metadescription', $aCategoryTranslateData.metadescription)}</textarea>
		<br />

	</fieldset>	
		<div class="center">
			<input type="submit" value="Save" />&nbsp;
			<a class="button" href="{$site_url}{$sCancelUrl}">Cancel</a>
		</div>
	
</form>
</div>