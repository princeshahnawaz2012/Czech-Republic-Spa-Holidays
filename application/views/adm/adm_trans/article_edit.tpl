<h1>{$sSiteTitle}</h1>
<div class="grid_8">
	<h2>{vlang('Original')} ({ucfirst($lang_uri_abbr[$language_abbr])})</h2>
	
	<label>{vlang('Title')} <small>{vlang('required')}</small></label>
	{$aOriginalData.title}
	<br />
	
	<label>{vlang('Meta keywords')}</label>
	{$aOriginalData.keywords|nl2br}
	<br />

	<label>{vlang('Meta description')}</label>
	{$aOriginalData.description|nl2br}
	<br />

	<label>{vlang('SEO link suffix')}</label>
	{$aOriginalData.seo_link}
	<br />

	<label>{vlang('Article body')} <small>{vlang('required')}</small></label>
	{$aOriginalData.full|nl2br}
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
		<input type="text" name="title" value="{set_value('title', $aArticleData.title)}" autocomplete="off" />
	<br />

		<label>{vlang('Meta keywords')}</label>
		<textarea class="small_textarea" name="keywords">{set_value('keywords', $aArticleData.keywords)}</textarea>
	<br />

		<label>{vlang('Meta description')}</label>
		<textarea class="small_textarea" name="description">{set_value('description', $aArticleData.description)}</textarea>
	<br />

		<label>{vlang('SEO link suffix')}</label>
		<input type="text" name="seo_link" value="{set_value('seo_link', $aArticleData.seo_link)}" autocomplete="off" />
	<br />

		<label>{vlang('Article body')} <small>{vlang('Required')}</small></label>
		<textarea class="large_textarea tinymce" name="full">{set_value('full', $aArticleData.full)}</textarea>
	<br />
	</fieldset>	
		<div class="center">
			<input type="submit" value="Save" />&nbsp;
			<a class="button" href="{$site_url}{$sCancelUrl}">Cancel</a>
			<input type="hidden" value="{$aArticleData.article_id}" id="art_id" />
			<input type="hidden" value="{$sLang}" id="art_lang" />
		</div>
	
</form>
</div>