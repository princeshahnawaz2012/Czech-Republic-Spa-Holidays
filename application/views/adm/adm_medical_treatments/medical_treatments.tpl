<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="medical_treatment_add" title="Add a medical_treatment" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: {$nCountAllMedical_treatments}
	<br />
		Active: {$nCountActiveMedical_treatments}
	<br />
		Inactive: {$nCountInactiveMedical_treatments}
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
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["medical_treatments.com_medical_treatment_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$language_abbr|cat:"_medical_treatments.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="{$MEDICAL_TREATMENT_ALL}" {if $aFilters["medical_treatments.com_active"]==$MEDICAL_TREATMENT_ALL}selected="selected"{/if}>Act./Inact.</option>
			<option value="{$MEDICAL_TREATMENT_INACTIVE}" {if $aFilters["medical_treatments.com_active"]==$MEDICAL_TREATMENT_INACTIVE}selected="selected"{/if}>Inactive</option>
			<option value="{$MEDICAL_TREATMENT_ACTIVE}" {if $aFilters["medical_treatments.com_active"]==$MEDICAL_TREATMENT_ACTIVE}selected="selected"{/if}>Active</option>
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
{foreach $aMedical_treatments as $medical_treatment}
	<tr>
		<td>{$medical_treatment.com_medical_treatment_id}</td>
		<td>{$medical_treatment[$sMainLang|cat:"_title"]}</td>
		<td>{if $medical_treatment.com_active == $MEDICAL_TREATMENT_ACTIVE}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$medical_treatment.com_medical_treatment_id}" title="Deactivate this medical_treatment" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$medical_treatment.com_medical_treatment_id}" title="Activate this medical_treatment" onclick="return confirm('Are you sure?');"></a>
			{/if}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$medical_treatment.com_medical_treatment_id}_order_input" value="{$medical_treatment.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$medical_treatment.com_medical_treatment_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$medical_treatment.com_medical_treatment_id}" title="Edit this medical_treatment"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$medical_treatment.com_medical_treatment_id}" id="medical_treatment_delete" title="Delete this medical_treatment"></a>
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