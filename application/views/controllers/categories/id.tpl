<div class="material">
	<h2>{flang($aCategoryData, 'title')}</h2>
<table class="tree_part_table" cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td width="30%">
		<div class="some_text_table">
			{flang($aCategoryData, 'desc')}
		</div>
		<img src="/{$sCategoryPicturesDir}{$aCategoryData.com_category_id}.{$aCategoryData.com_picture_ext}" alt="{flang($aCategoryData, 'title')}" title="{flang($aCategoryData, 'title')}" />
		</td>
		<td width="70%">
			<div class="right_half_table" id="category_filter">
				<strong>{vlang('Define and see the programmes that you wish to have dispalyed:')} </strong>
				<div class="half_table">
					<ul>
						{for $i=0 to ceil($nIllnesesDataCount / 2.0)-1}
							<li><input type="checkbox" name="illneses[]" value="{$aIllnesesData[$i].com_illnese_id}" id="illnese_{$aIllnesesData[$i].com_illnese_id}" /><label for="illnese_{$aIllnesesData[$i].com_illnese_id}" title="{flang($aIllnesesData[$i], 'short_desc')}">{flang($aIllnesesData[$i], 'title')}</label></li>
						{/for}
					</ul>
				</div>
				<div class="half_table">
					<ul>
						{for $i=ceil($nIllnesesDataCount / 2.0) to $nIllnesesDataCount-1}
							<li><input type="checkbox" name="illneses[]" value="{$aIllnesesData[$i].com_illnese_id}" id="illnese_{$aIllnesesData[$i].com_illnese_id}" /><label for="illnese_{$aIllnesesData[$i].com_illnese_id}" title="{flang($aIllnesesData[$i], 'short_desc')}">{flang($aIllnesesData[$i], 'title')}</label></li>
						{/for}
					</ul>
				</div>
				<div class="cl"></div>
				<div class="reset_all">
					<a  class="bt_push" href="#" id="reset_category_filter"><img src="/images/reset_all_button.gif" alt="{vlang('Reset all')}"</a>
				</div>
			</div>
		</td>

	</tr>
</table>
</div>

<div class="big_table">
	<table cellpadding="0" cellspacing="0">
		<thead>
			<tr>
				<td class="tb_h_1">{vlang('Programme')}</td>
				<td class="tb_h_2">
					{if $sType==$CATEGORY_SHOW_SHORT_DESCRIPTION}
						{vlang('Description')}
					{else}
						{vlang('Recommended for')}
					{/if}</td>
				<td class="tb_h_3">{vlang('Location')}</td>
				<td class="tb_h_4">{vlang('Spa')}</td>
				<td class="tb_h_5">{vlang('Price per course')}</td>
			</tr>
		</thead>
		<tbody>
			{foreach $aProgrammesData as $aProgrammeData}
			<tr>
				<td class="tb_h_1">
					<a href="{$site_url}programmes/id/{$aProgrammeData.com_programme_id}/{flang($aProgrammeData, 'seo_link')}" title="{vlang('View details of programme')} {flang($aProgrammeData, 'title')}">{flang($aProgrammeData, 'title')}</a>
				</td>
				<td class="tb_h_2">
					<a href="{$site_url}programmes/id/{$aProgrammeData.com_programme_id}/{flang($aProgrammeData, 'seo_link')}" title="{vlang('View details of programme')} {flang($aProgrammeData, 'title')}">
						{if $sType==$CATEGORY_SHOW_SHORT_DESCRIPTION}
							{flang($aProgrammeData, 'short_desc')}
						{else}
							{foreach $aProgrammesIllnesesData[$aProgrammeData.com_programme_id] as $aProgrammeIllnesesData}
								{flang($aProgrammeIllnesesData, 'title')},&nbsp;
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
			{/foreach}
		</tbody>
	</table>
</div>