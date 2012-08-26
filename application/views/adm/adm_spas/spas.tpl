<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="spa_add" title="Add a spa" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: {$nCountAllSpas}
	<br />
		Active: {$nCountActiveSpas}
	<br />
		Inactive: {$nCountInactiveSpas}
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
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["spas.com_spa_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$language_abbr|cat:"_spas.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_city_title">City</label>
		<input type="text" id="art_city_title" placeholder="City" value="{$aFilters[$sMainLang|cat:"_cities.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="{$SPA_ALL}" {if $aFilters["spas.com_active"]==$SPA_ALL}selected="selected"{/if}>Act./Inact.</option>
			<option value="{$SPA_INACTIVE}" {if $aFilters["spas.com_active"]==$SPA_INACTIVE}selected="selected"{/if}>Inactive</option>
			<option value="{$SPA_ACTIVE}" {if $aFilters["spas.com_active"]==$SPA_ACTIVE}selected="selected"{/if}>Active</option>
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
{foreach $aSpas as $spa}
	<tr>
		<td>{$spa.com_spa_id}</td>
		<td>{$spa[$sMainLang|cat:"_title"]}</td>
		<td>{$spa[$sMainLang|cat:"_city_title"]}</td>
		<td>{if $spa.com_active == $SPA_ACTIVE}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$spa.com_spa_id}" title="Deactivate this spa" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$spa.com_spa_id}" title="Activate this spa" onclick="return confirm('Are you sure?');"></a>
			{/if}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$spa.com_spa_id}_order_input" value="{$spa.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$spa.com_spa_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$spa.com_spa_id}" title="Edit this spa"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$spa.com_spa_id}" id="spa_delete" title="Delete this spa"></a>
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