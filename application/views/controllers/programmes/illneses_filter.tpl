{foreach $aProgrammesData as $aProgrammeData}
<tr>
	<td class="tb_h_1">
		<a href="{$site_url}programmes/id/{$aProgrammeData.com_programme_id}/{flang($aProgrammeData, 'seo_link')}" title="{vlang('View details of programme')} {flang($aProgrammeData, 'title')}">{flang($aProgrammeData, 'title')}</a>
	</td>
	<td class="tb_h_2">
		<a href="{$site_url}programmes/id/{$aProgrammeData.com_programme_id}/{flang($aProgrammeData, 'seo_link')}" title="{vlang('View details of programme')} {flang($aProgrammeData, 'title')}">
			{if $aCategoryData.com_complex_treatments==$COMPLEX_TREATMENT_COSMETIC}
				{flang($aProgrammeData, 'short_desc')}
			{else}
				{foreach from=$aProgrammesIllnesesData[$aProgrammeData.com_programme_id] item=aProgrammeIllnesesData name=illneses}
					{flang($aProgrammeIllnesesData, 'title')}{if $smarty.foreach.illneses.last}.{else},&nbsp;{/if}
				{/foreach}
			{/if}
		</a>
	</td>
	<td class="tb_h_3">
		<a href="{$site_url}programmes/id/{$aProgrammeData.com_programme_id}/{flang($aProgrammeData, 'seo_link')}" title="{vlang('View details of programme')} {flang($aProgrammeData, 'title')}">{$aCitiesTitle[$aProgrammeData.com_city_id]}</a>
	</td>
	<td class="tb_h_4">
		<a href="{$site_url}programmes/id/{$aProgrammeData.com_programme_id}/{flang($aProgrammeData, 'seo_link')}" title="{vlang('View details of programme')} {flang($aProgrammeData, 'title')}">{$aSpasTitle[$aProgrammeData.com_spa_id]}</a>
	</td>
	<td class="tb_h_5">
		<a href="{$site_url}programmes/id/{$aProgrammeData.com_programme_id}/{flang($aProgrammeData, 'seo_link')}" title="{vlang('View details of programme')} {flang($aProgrammeData, 'title')}">{$price_array = [$aProgrammeData.com_price_from, $aProgrammeData.com_currency_id]}{vlang('Price from per person', $price_array)}</a>
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="5" class="valign_center align_center">{vlang('No programmes found')}</td>
</tr>
{/foreach}