<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="{vlang('Save article')}" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_8">
			<label>{vlang('Title')} <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title', $aArticleData.title)}" autocomplete="off" />
		</div>
		<div class="grid_8">
			<label>{vlang('SEO link suffix')}</label>
			<input type="text" name="seo_link" value="{set_value('seo_link', $aArticleData.seo_link)}" autocomplete="off" />
		</div>
		<div class="grid_16">
			<label>{vlang('Meta keywords')}</label>
			<textarea class="small_textarea" name="keywords">{set_value('keywords', $aArticleData.keywords)}</textarea>
		</div>
		<div class="grid_16">
			<label>{vlang('Meta description')}</label>
			<textarea class="small_textarea" name="description">{set_value('description', $aArticleData.description)}</textarea>
		</div>
		<div class="grid_8">
			<label>{vlang('Order')}</label>
			<input type="text" name="com_order" value="{set_value('com_order', $aArticleData.com_order)}" autocomplete="off" />
		</div>
		<div class="grid_8">
			<label>{vlang('Hits')}</label>
			<input type="text" name="com_hits" value="{set_value('com_hits', $aArticleData.com_hits)}" autocomplete="off" />
		</div>
		<div class="grid_16">
			<label>{vlang('Article body')} <small>{vlang('Required')}</small></label>
			<textarea class="large_textarea tinymce" name="full">{set_value('full', $aArticleData.full)}</textarea>
		</div>
	</fieldset>
	<div class="center">
		<input type="submit" value="{vlang('Save article')}" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
</form>