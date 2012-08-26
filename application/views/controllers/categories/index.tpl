<h1>{$sSiteTitle}</h1>
<div class="material_shot">
	{foreach $aCategoriesData as $aCategoryData}
	<table class="material_1" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="m_title">
				<a href="{$site_url}categories/id/{$CATEGORY_SHOW_SHORT_DESCRIPTION}/{$aCategoryData.com_category_id}/{flang($aCategoryData, 'seo_link')}">{flang($aCategoryData, 'title')}</a>
			</td>
		</tr>
		<tr>
			<td class="m_imege">
				<a href="{$site_url}categories/id/{$CATEGORY_SHOW_SHORT_DESCRIPTION}/{$aCategoryData.com_category_id}/{flang($aCategoryData, 'seo_link')}">
					<img src="/{$sCategoryPicturesDir}{$aCategoryData.com_category_id}.{$aCategoryData.com_picture_ext}" alt="{flang($aCategoryData, 'title')}" title="{flang($aCategoryData, 'title')}" />
				</a>
			</td>
		</tr>
		<tr>
			<td class="m_text">
				<a href="{$site_url}categories/id/{$CATEGORY_SHOW_SHORT_DESCRIPTION}/{$aCategoryData.com_category_id}/{flang($aCategoryData, 'seo_link')}">
					{flang($aCategoryData, 'short_desc')}
				</a>
			</td>
		</tr>
		<tr>
			<td class="m_link">
				<a href="{$site_url}categories/id/{$CATEGORY_SHOW_ILLNESES}/{$aCategoryData.com_category_id}/{flang($aCategoryData, 'seo_link')}">{vlang('more')}</a>
			</td>
		</tr>
	</table>
	{/foreach}
	<div class="cl"></div>
</div>