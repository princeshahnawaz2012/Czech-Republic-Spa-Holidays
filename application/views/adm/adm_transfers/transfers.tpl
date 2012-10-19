<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="transfer_add" title="Add a transfer" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: {$nCountAllTransfers}
	<br />
		Active: {$nCountActiveTransfers}
	<br />
		Inactive: {$nCountInactiveTransfers}
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
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["transfers.com_transfer_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$language_abbr|cat:"_transfers.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="{$TRANSFER_ALL}" {if $aFilters["transfers.com_active"]==$TRANSFER_ALL}selected="selected"{/if}>Act./Inact.</option>
			<option value="{$TRANSFER_INACTIVE}" {if $aFilters["transfers.com_active"]==$TRANSFER_INACTIVE}selected="selected"{/if}>Inactive</option>
			<option value="{$TRANSFER_ACTIVE}" {if $aFilters["transfers.com_active"]==$TRANSFER_ACTIVE}selected="selected"{/if}>Active</option>
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
{foreach $aTransfers as $transfer}
	<tr>
		<td>{$transfer.com_transfer_id}</td>
		<td>{$transfer[$sMainLang|cat:"_title"]}</td>
		<td>{if $transfer.com_active == $TRANSFER_ACTIVE}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$transfer.com_transfer_id}" title="Deactivate this transfer" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$transfer.com_transfer_id}" title="Activate this transfer" onclick="return confirm('Are you sure?');"></a>
			{/if}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$transfer.com_transfer_id}_order_input" value="{$transfer.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$transfer.com_transfer_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$transfer.com_transfer_id}" title="Edit this transfer"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$transfer.com_transfer_id}" id="transfer_delete" title="Delete this transfer"></a>
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