<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="programme_add" title="Add a programme" class="button">Add</a>
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
<div class="grid_3">
	<p>
		<label for="art_id">ID</label>
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["programmes.com_programme_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_5">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$language_abbr|cat:"_programmes.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_5">
	<p>
		<label for="art_category_title">Category</label>
		<input type="text" id="art_category_title" placeholder="Category" value="{$aFilters[$sMainLang|cat:"_categories.title"]}" autocomplete="off" />
	</p>
</div>
<div class="clearfix"></div>
<div class="grid_1">
	<p>
		<span title="{vlang('All')}">All</span>: {$nCountAllProgrammes}
	<br />
		<span title="{vlang('Active')}">Act.</span>: {$nCountActiveProgrammes}
	<br />
		<span title="{vlang('Inactive')}">Ina.</span>: {$nCountInactiveProgrammes}
	</p>
</div>
<div class="grid_5">
	<p>
		<label for="art_spa_title">{vlang('Hotel spa')}</label>
		<input type="text" id="art_spa_title" placeholder="{vlang('Hotel spa')}" value="{$aFilters[$sMainLang|cat:"_spas.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_5">
	<p>
		<label for="art_city_title">{vlang('City')}</label>
		<input type="text" id="art_city_title" placeholder="{vlang('City')}" value="{$aFilters[$sMainLang|cat:"_cities.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="{$PROGRAMME_ALL}" {if $aFilters["programmes.com_active"]==$PROGRAMME_ALL}selected="selected"{/if}>Act./Inact.</option>
			<option value="{$PROGRAMME_INACTIVE}" {if $aFilters["programmes.com_active"]==$PROGRAMME_INACTIVE}selected="selected"{/if}>Inactive</option>
			<option value="{$PROGRAMME_ACTIVE}" {if $aFilters["programmes.com_active"]==$PROGRAMME_ACTIVE}selected="selected"{/if}>Active</option>
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
		<th colspan="3">Actions</th>
	</tr>
	</thead>
	<tbody>
{foreach $aProgrammes as $programme}
	<tr>
		<td>{$programme.com_programme_id}</td>
		<td>{$programme[$sMainLang|cat:"_title"]}</td>
		<td>{$programme[$sMainLang|cat:"_category_title"]}</td>
		<td>{$programme[$sMainLang|cat:"_spa_title"]}</td>
		<td>{$programme[$sMainLang|cat:"_city_title"]}</td>
		<td>{if $programme.com_active == $PROGRAMME_ACTIVE}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$programme.com_programme_id}" title="Deactivate this programme" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$programme.com_programme_id}" title="Activate this programme" onclick="return confirm('Are you sure?');"></a>
			{/if}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$programme.com_programme_id}_order_input" value="{$programme.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$programme.com_programme_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$programme.com_programme_id}" title="Edit this programme"></a>
		</td>
		<td>
			<a class="pictures" href="{$site_url}{$sImagesUrl}{$programme.com_programme_id}" title="The images of this programme">({intval($programme.num_images)})</a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$programme.com_programme_id}" id="programme_delete" title="Delete this programme"></a>
		</td>
	</tr>	
{/foreach}
	</tbody>
	{if $sPagination}
	<tfoot>
	<tr>
		<td colspan="{$nOrders + 3}" class="pagination">
		{$sPagination}
		</td>
	</tr>
	</tfoot>
	{/if}
</table>
</div>