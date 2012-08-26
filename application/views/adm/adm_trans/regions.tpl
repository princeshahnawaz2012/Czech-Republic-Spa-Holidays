<h1>{$sSiteTitle}</h1>
<div class="grid_2">
	<p>
		<label for="art_per_page">Per page</label>
		<select id="art_per_page">
			{foreach $aPerPageVariables as $nPerPageVar}
				<option value="{$nPerPageVar}" {if $nPerPage==$nPerPageVar}selected="selected"{/if}>{$nPerPageVar}</option>
			{/foreach}
			<option value="0" {if $nPerPage==0}selected="selected"{/if}>All</option>
		</select>
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_id">ID</label>
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["regions.com_region_id"]}" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$sMainLang|cat:"_regions.title"]}" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_country_title">Country</label>
		<input type="text" id="art_country_title" placeholder="Country" value="{$aFilters[$sMainLang|cat:"_countries.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label>&nbsp;</label>
		<input type="submit" id="art_filter" value="Filter" />
		<input type="submit" id="art_filter_reset" value="Reset" />
		<input type="hidden" id="art_per_page" value="{$nPerPage}" />
		<input type="hidden" id="art_order" value="{$nOrder}" />
		<input type="hidden" id="art_direct" value="{$sDirect}" />
	</p>
</div>
<div class="clearfix"></div>
<div class="grid_16">
<table>
	<thead>
	<tr>
		{foreach $aOrderLinks as $sLink}
		<th>{$sLink}</th>
		{/foreach}
		{foreach $aLangPermissions as $aLang}
			<th>{ucfirst($lang_uri_abbr[$aLang])}</th>
		{/foreach}
	</tr>
	</thead>
	<tbody>
{foreach $aRegions as $region}
	<tr class="{cycle values="first,alt"}">
		<td>{$region.com_region_id}</td>
		<td>{$region[$sMainLang|cat:"_title"]}</td>
		<td>{$region[$sMainLang|cat:"_country_title"]}</td>
		{foreach $aLangPermissions as $aLang}
			<td>
				{if $region[$aLang|cat:"_title"]}
					<a class="edit" href="{$site_url}adm_trans/region_edit/{$region.com_region_id}/{$aLang}" title="Edit this translate"></a>
				{else}
					<a class="new" href="{$site_url}adm_trans/region_edit/{$region.com_region_id}/{$aLang}" title="Input this translate"></a>
				{/if}
			</td>
		{/foreach}
	</tr>	
{/foreach}
	</tbody>
	{if $sPagination}
	<tfoot>
	<tr>
		<td colspan="{$nOrdersNum}" class="pagination">
		{$sPagination}
		</td>
	</tr>
	</tfoot>
	{/if}
</table>
</div>