<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="illnese_add" title="Add a illnese" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: {$nCountAllIllneses}
	<br />
		Active: {$nCountActiveIllneses}
	<br />
		Inactive: {$nCountInactiveIllneses}
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
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["illneses.com_illnese_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$language_abbr|cat:"_illneses.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="{$ILLNESE_ALL}" {if $aFilters["illneses.com_active"]==$ILLNESE_ALL}selected="selected"{/if}>Act./Inact.</option>
			<option value="{$ILLNESE_INACTIVE}" {if $aFilters["illneses.com_active"]==$ILLNESE_INACTIVE}selected="selected"{/if}>Inactive</option>
			<option value="{$ILLNESE_ACTIVE}" {if $aFilters["illneses.com_active"]==$ILLNESE_ACTIVE}selected="selected"{/if}>Active</option>
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
{foreach $aIllneses as $illnese}
	<tr>
		<td>{$illnese.com_illnese_id}</td>
		<td>{$illnese[$sMainLang|cat:"_title"]}</td>
		<td>{if $illnese.com_active == $ILLNESE_ACTIVE}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$illnese.com_illnese_id}" title="Deactivate this illnese" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$illnese.com_illnese_id}" title="Activate this illnese" onclick="return confirm('Are you sure?');"></a>
			{/if}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$illnese.com_illnese_id}_order_input" value="{$illnese.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$illnese.com_illnese_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$illnese.com_illnese_id}" title="Edit this illnese"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$illnese.com_illnese_id}" id="illnese_delete" title="Delete this illnese"></a>
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