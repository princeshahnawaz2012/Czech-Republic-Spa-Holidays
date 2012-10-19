<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="station_add" title="Add a station" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: {$nCountAllStations}
	<br />
		Active: {$nCountActiveStations}
	<br />
		Inactive: {$nCountInactiveStations}
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
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["stations.com_station_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$language_abbr|cat:"_stations.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="{$STATION_ALL}" {if $aFilters["stations.com_active"]==$STATION_ALL}selected="selected"{/if}>Act./Inact.</option>
			<option value="{$STATION_INACTIVE}" {if $aFilters["stations.com_active"]==$STATION_INACTIVE}selected="selected"{/if}>Inactive</option>
			<option value="{$STATION_ACTIVE}" {if $aFilters["stations.com_active"]==$STATION_ACTIVE}selected="selected"{/if}>Active</option>
		</select>
	</p>
</div>
<div class="grid_3">
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
		<th colspan="2">Actions</th>
	</tr>
	</thead>
	<tbody>
{foreach $aStations as $station}
	<tr>
		<td>{$station.com_station_id}</td>
		<td>{$station[$sMainLang|cat:"_title"]}</td>
		<td>{if $station.com_active == $STATION_ACTIVE}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$station.com_station_id}" title="Deactivate this station" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$station.com_station_id}" title="Activate this station" onclick="return confirm('Are you sure?');"></a>
			{/if}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$station.com_station_id}_order_input" value="{$station.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$station.com_station_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$station.com_station_id}" title="Edit this station"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$station.com_station_id}" id="station_delete" title="Delete this station"></a>
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