<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="category_add" title="Add a category" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		<span title="Number of all records">All</span>: {$nCountAllCategories}
		<br />
		<span title="Number of active records">Active</span>: {$nCountActiveCategories}
		<br />
		<span title="Number of inactive records">Inactive</span>: {$nCountInactiveCategories}
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
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["categories.com_category_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$language_abbr|cat:"_categories.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="{$CATEGORY_ALL}" {if $aFilters["categories.com_active"]==$CATEGORY_ALL}selected="selected"{/if}>Act./Inact.</option>
			<option value="{$CATEGORY_INACTIVE}" {if $aFilters["categories.com_active"]==$CATEGORY_INACTIVE}selected="selected"{/if}>Inactive</option>
			<option value="{$CATEGORY_ACTIVE}" {if $aFilters["categories.com_active"]==$CATEGORY_ACTIVE}selected="selected"{/if}>Active</option>
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
{foreach $aCategories as $aCategory}
	<tr>
		<td>{$aCategory.com_category_id}</td>
		<td>{$aCategory[$sMainLang|cat:"_title"]}</td>
		<td>{if $aCategory.com_active == $CATEGORY_ACTIVE}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$aCategory.com_category_id}" title="Deactivate this category" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$aCategory.com_category_id}" title="Activate this category" onclick="return confirm('Are you sure?');"></a>
			{/if}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$aCategory.com_category_id}_order_input" value="{$aCategory.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$aCategory.com_category_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$aCategory.com_category_id}" title="Edit this category"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$aCategory.com_category_id}" id="category_delete" title="Delete this category"></a>
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