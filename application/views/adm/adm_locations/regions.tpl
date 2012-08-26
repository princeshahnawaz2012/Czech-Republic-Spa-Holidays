<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="region_add" title="Add a region" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: {$nCountAllRegions}
	<br />
		Active: {$nCountActiveRegions}
	<br />
		Inactive: {$nCountInactiveRegions}
	</p>
</div>	
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
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["regions.com_region_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$language_abbr|cat:"_regions.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_country_title">Country</label>
		<input type="text" id="art_country_title" placeholder="Country" value="{$aFilters[$sMainLang|cat:"_countries.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="{$REGION_ALL}" {if $aFilters["regions.com_active"]==$REGION_ALL}selected="selected"{/if}>Act./Inact.</option>
			<option value="{$REGION_INACTIVE}" {if $aFilters["regions.com_active"]==$REGION_INACTIVE}selected="selected"{/if}>Inactive</option>
			<option value="{$REGION_ACTIVE}" {if $aFilters["regions.com_active"]==$REGION_ACTIVE}selected="selected"{/if}>Active</option>
		</select>
		<input type="hidden" id="art_per_page" value="{$nPerPage}" />
		<input type="hidden" id="art_order" value="{$nOrder}" />
		<input type="hidden" id="art_direct" value="{$sDirect}" />
	</p>
</div>
<div class="grid_3">
	<p>
		<label>&nbsp;</label>
		<input type="submit" id="art_filter" value="Filter" />
		<input type="submit" id="art_filter_reset" value="Reset" />
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
		<th colspan="2">Actions</th>
	</tr>
	</thead>
	<tbody>
{foreach $aRegions as $region}
	<tr>
		<td>{$region.com_region_id}</td>
		<td>{$region[$sMainLang|cat:"_title"]}</td>
		<td>{$region[$sMainLang|cat:"_country_title"]}</td>
		<td>{if $region.com_active == $REGION_ACTIVE}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$region.com_region_id}" title="Deactivate this region" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$region.com_region_id}" title="Activate this region" onclick="return confirm('Are you sure?');"></a>
			{/if}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$region.com_region_id}_order_input" value="{$region.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$region.com_region_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$region.com_region_id}" title="Edit this region"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$region.com_region_id}" id="region_delete" title="Delete this region"></a>
		</td>
	</tr>	
{/foreach}
	</tbody>
	{if $sPagination}
	<tfoot>
	<tr>
		<td colspan="{$nOrders + 2}" class="pagination">
		{$sPagination}
		</td>
	</tr>
	</tfoot>
	{/if}
</table>
</div>