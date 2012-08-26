<h1>{$sSiteTitle}</h1>
{form_open()}
	<div class="center">
		<input type="submit" value="Add article" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
	<fieldset>
		<div class="grid_8">
			<label>Title <small>{vlang('Required')}</small></label>
			<input type="text" name="title" value="{set_value('title')}" autocomplete="off" />
		</div>		
		<div class="grid_8">
			<label>SEO link suffix</span></label>
			<input type="text" name="seo_link" value="{set_value('seo_link')}" autocomplete="off" />
		</div>
		<div class="clearfix"></div>
		<div class="grid_16">
			<label>Meta keywords</label>
			<textarea class="small_textarea" name="keywords">{set_value('keywords')}</textarea>
		</div>
		<div class="clearfix"></div>
		<div class="grid_16">
			<label>Meta description</label>
			<textarea class="small_textarea" name="description">{set_value('description')}</textarea>
		</div>
		<div class="clearfix"></div>
		<div class="grid_16">
			<label>Article body <small>{vlang('Required')}</small></label>
			<textarea class="large_textarea tinymce" name="full">{set_value('full')}</textarea>
		</div>		
		<div class="clearfix"></div>
	</fieldset>
	<div class="center">
		<input type="submit" value="Add article" />
		<a class="button" href="{$site_url}{$sCancelUrl}">{vlang('Cancel')}</a>
	</div>
</form>