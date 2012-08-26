<h1>{$sSiteTitle}</h1>
<div class="grid_2" id="common_actions">
	<p>
		<a href="{$site_url}{$sAddUrl}" id="currency_add" title="Add a currency" class="button">Add</a>
	</p>
	<p>
		<a href="#" id="sync_add" title="Synchronize all currency exchange rates from remote server" class="button">Sync all</a>
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
		<label for="cur_id_from">ISO From</label>
		<input type="text" id="cur_id_from" placeholder="ISO From" value="{$aFilters["currencies_exchange.com_currency_from_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="cur_title_from">Title From</label>
		<input class="currency_title_filter" type="text" id="cur_title_from" placeholder="Title From" value="{$aFilters[$language_abbr|cat:"_currencies_from.title"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="cur_id_to">ISO To</label>
		<input type="text" id="cur_id_to" placeholder="ISO To" value="{$aFilters["currencies_exchange.com_currency_to_id"]}" autocomplete="off" />
	</p>
</div>
<div class="grid_2">
	<p>
		<label for="cur_title_to">Title To</label>
		<input class="currency_title_filter" type="text" id="cur_title_to" placeholder="Title To" value="{$aFilters[$language_abbr|cat:"_currencies_to.title"]}" autocomplete="off" />
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
		<th>Actions</th>
	</tr>
	</thead>
	<tbody>
{foreach $aCurrencies as $aCurrency}
	<tr>
		<td>{$aCurrency.title_from}</td>
		<td>{$aCurrency.com_currency_from_id}</td><td style="min-width: 100px;">
			<input type="text" class="exchange_rate_input" id="{$aCurrency.com_currency_from_id}-{$aCurrency.com_currency_to_id}_exchange_rate" value="{$aCurrency.com_exchange}" style="width: 60px;" autocomplete="off" /><a href="#" class="save" id="{$aCurrency.com_currency_from_id}-{$aCurrency.com_currency_to_id}_save_exchange_rate" title="{vlang('Save currency exchange rate')}"></a>
		</td>
		<td>{$aCurrency.com_currency_to_id}</td>
		<td>{$aCurrency.title_to}</td>
		<td>
			<a class="button sync" href="#" id="{$aCurrency.com_currency_from_id}-{$aCurrency.com_currency_to_id}_sync_exchange_rate" title="Synchronize this currency exchange rate from remote server">Sync</a>
		</td>
	</tr>	
{/foreach}
	</tbody>
	{if $sPagination}
	<tfoot>
	<tr>
		<td colspan="{$nOrders + 1}" class="pagination">
		{$sPagination}
		</td>
	</tr>
	</tfoot>
	{/if}
</table>
</div>