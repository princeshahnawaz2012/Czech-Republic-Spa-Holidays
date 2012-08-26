<h1>{$sSiteTitle}</h1>
<div class="grid_8">
	<h2>{vlang('Original')} ({ucfirst($lang_uri_abbr[$language_abbr])})</h2>
	
	<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
	{$aProgrammeDefaultTranslateData.title}
	<br />
	<label>{vlang('SEO link')}</label>
	{$aProgrammeDefaultTranslateData.seo_link}
	<br />
	<label>{vlang("Short description")}</label>
	{$aProgrammeDefaultTranslateData.short_desc}
	<br />
	<label>{vlang("Description")}</label>
	{$aProgrammeDefaultTranslateData.description}
	<br />
	<label>{vlang("Included")}</label>
	{$aProgrammeDefaultTranslateData.included}
	<br />
	<label>{vlang("Not included")}</label>
	{$aProgrammeDefaultTranslateData.notincluded}
	<br />
	<label>{vlang("Terms")}</label>
	{$aProgrammeDefaultTranslateData.terms}
	<br />
	<label>{vlang("Meta keywords")}</label>
	{$aProgrammeDefaultTranslateData.metakeywords}
	<br />
	<label>{vlang("Meta description")}</label>
	{$aProgrammeDefaultTranslateData.metadescription}
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
		<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
		<input type="text" name="title" value="{set_value('title', $aProgrammeTranslateData.title)}" autocomplete="off" />
		<br />
		<label>{vlang('SEO link')}</label>
		<input type="text" name="seo_link" value="{set_value('seo_link', $aProgrammeTranslateData.seo_link)}" autocomplete="off" />
		<br />
		<label>{vlang("Short description")}</label>
		<textarea name="short_desc">{set_value('short_desc', $aProgrammeTranslateData.short_desc)}</textarea>
		<br />
		<label>{vlang("Description")}</label>
		<textarea class="tinymce" name="description">{set_value('description', $aProgrammeTranslateData.description)}</textarea>
		<br />
		<label>{vlang("Included")}</label>
		<textarea class="tinymce" name="included">{set_value('included', $aProgrammeTranslateData.included)}</textarea>
		<br />
		<label>{vlang("Not included")}</label>
		<textarea class="tinymce" name="notincluded">{set_value('notincluded', $aProgrammeTranslateData.notincluded)}</textarea>
		<br />
		<label>{vlang("Terms")}</label>
		<textarea class="tinymce" name="terms">{set_value('terms', $aProgrammeTranslateData.terms)}</textarea>
		<br />
		<label>{vlang("Meta keywords")}</label>
		<textarea name="metakeywords">{set_value('metakeywords', $aProgrammeTranslateData.metakeywords)}</textarea>
		<br />
		<label>{vlang("Meta description")}</label>
		<textarea name="metadescription">{set_value('metadescription', $aProgrammeTranslateData.metadescription)}</textarea>
		<br />
	</fieldset>	
		<div class="center">
			<input type="submit" value="Save" />&nbsp;
			<a class="button" href="{$site_url}{$sCancelUrl}">Cancel</a>
		</div>
{form_close()}
</div>