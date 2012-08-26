<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="user_add" title="Add a user" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: {$nCountAllUsers}
	<br />
		Active: {$nCountActiveUsers}
	<br />
		Inactive: {$nCountInactiveUsers}
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
<div class="grid_1">
	<p>
		<label for="art_id">ID</label>
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["user_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_login">Login</label>
		<input type="text" id="art_login" placeholder="Login" value="{$aFilters["login"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_3">
	<p>
		<label for="art_username">Username</label>
		<input type="text" id="art_username" placeholder="Username" value="{$aFilters["fio"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="" {if $aFilters["status"]=='0'}selected="selected"{/if}>Act./Inact.</option>
			<option value="2" {if $aFilters["status"]=='2'}selected="selected"{/if}>Inactive</option>
			<option value="1" {if $aFilters["status"]=='1'}selected="selected"{/if}>Active</option>
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
{foreach $aData.users as $user}
	<tr>
		<td>{$user.user_id}</td>
		<td>{$user.login}</td>
		<td>{$user.fio}</td>
		<td>{$aData.role[$user.role]}</td>
		<td>{$user.created}</td>
		<td>{if $user.last_login}{$user.last_login|date_format:"%Y-%m-%d %H:%M:%S"}{else}Undefined{/if}</td>
		{if $user.role==1}
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		{else}
			<td>
				{if $user.status == 1}
					<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$user.user_id}" title="Block this user" onclick="return confirm('Are you sure?');"></a>
				{else}
					<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$user.user_id}" title="Unblock this user" onclick="return confirm('Are you sure?');"></a>
				{/if}
			</td>
			<td>
				<a class="edit" href="{$site_url}{$sEditUrl}{$user.user_id}" title="Edit this user"></a>
			</td>
			<td>
				<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$user.user_id}" id="user_delete" title="Delete this user"></a>
			</td>
		{/if}
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