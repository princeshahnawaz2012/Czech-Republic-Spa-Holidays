<h1>{$sSiteTitle}</h1>
<div class="grid_1" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}" id="article_add" title="Add an article" class="button">Add</a>
	</p>
</div>
<div class="grid_2">
	<p>
		All: {$nCountAllArticles}
	<br />
		Active: {$nCountActiveArticles}
	<br />
		Inactive: {$nCountInactiveArticles}
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
		<input type="text" id="art_id" placeholder="ID" value="{$aFilters["articles.com_article_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_4">
	<p>
		<label for="art_title">Title</label>
		<input type="text" id="art_title" placeholder="Title" value="{$aFilters[$sMainLang|cat:"_articles.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="art_status">Status</label>
		<select id="art_status">
			<option value="" {if $aFilters["articles.com_active"]=='0'}selected="selected"{/if}>Act./Inact.</option>
			<option value="2" {if $aFilters["articles.com_active"]=='2'}selected="selected"{/if}>Inactive</option>
			<option value="1" {if $aFilters["articles.com_active"]=='1'}selected="selected"{/if}>Active</option>
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
{foreach $aArticles as $aArticle}
	<tr>
		<td>{$aArticle.com_article_id}</td>
		<td>{$aArticle[$sMainLang|cat:"_title"]}</td>
		<td>{$aArticle.com_hits}</td>
		<td>
			{if $aArticle.com_active == 1}
				<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$aArticle.com_article_id}" title="Deactivate this article" onclick="return confirm('Are you sure?');"></a>
			{else}
				<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$aArticle.com_article_id}" title="Activate this article" onclick="return confirm('Are you sure?');"></a>
			{/if}
		</td>
		<td style="min-width: 120px;">{$aArticle.com_time|date_format:"%Y-%m-%d %H:%M:%S"}</td>
		<td style="min-width: 70px;">
			<input type="text" class="order_input" id="{$aArticle.com_article_id}_order_input" value="{$aArticle.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$aArticle.com_article_id}_save_order" title="{vlang('Save order value')}"></a>
		</td>
		<td>
			<a class="edit" href="{$site_url}{$sEditUrl}{$aArticle.com_article_id}" title="Edit this article"></a>
		</td>
		<td>
			<a class="delete" onclick="return confirm('Are you sure?');" href="{$site_url}{$sDeleteUrl}{$aArticle.com_article_id}" id="article_delete" title="Delete this article"></a>
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