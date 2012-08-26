<h1>{$sSiteTitle}</h1>
<div class="grid_3" id="common_actions">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAddUrl}{$aProgramme.com_programme_id}" id="image_add" title="Add a image of programme" class="button">Add image</a>
	</p>
</div>
<div class="grid_3">
	<p>
		<label>&nbsp;</label>
		<a href="{$site_url}{$sAllProgrammesUrl}" title="Back to all programmes" class="button">All programmes</a>
	</p>
</div>
<div class="clearfix"></div>
<div class="grid_16">
<table>
	<thead>
	<tr>
		<th>{vlang('Programme ID')}</th>
		<th>{vlang('Programme title')}</th>
		<th>{vlang('Category')}</th>
		<th>{vlang('Hotel spa')}</th>
		<th>{vlang('Status')}</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td>{$aProgramme.com_programme_id}</td>
		<td>{$aProgramme[$sMainLang|cat:"_title"]}</td>
		<td>{$aProgramme[$sMainLang|cat:"_category_title"]}</td>
		<td>{$aProgramme[$sMainLang|cat:"_spa_title"]}</td>
		<td>{if $aProgramme.com_active == $PROGRAMME_ACTIVE}
				<a href="#" onclick="return false;" class="active_status"></a>
			{else}
				<a href="#" onclick="return false;" class="inactive_status"></a>
			{/if}</td>
	</tr>
	</tbody>
</table>
</div>
<div class="clearfix"></div>
{foreach $aImages as $aImage}
	<div class="grid_8">
		<br />
		<label>{$aImage.title}</label>
		{if $aImage.com_active == $PROGRAMME_IMAGE_ACTIVE}
			<a class="active_status" href="{$site_url}{$sDeactivateUrl}{$aImage.com_programme_image_id}" title="Deactivate this image of the programme" onclick="return confirm('Are you sure?');"></a>
		{else}
			<a class="inactive_status" href="{$site_url}{$sActivateUrl}{$aImage.com_programme_image_id}" title="Activate this image of the programme" onclick="return confirm('Are you sure?');"></a>
		{/if}
		&nbsp;
		&nbsp;
		<input type="text" class="order_input" id="{$aImage.com_programme_image_id}_order_input" value="{$aImage.com_order}" style="width: 30px;" autocomplete="off" /><a href="#" class="save" id="{$aImage.com_programme_image_id}_save_order" title="{vlang('Save order value')}"></a>
		&nbsp;
		&nbsp;
		<a class="button" href="{$site_url}{$sEditUrl}{$aImage.com_programme_image_id}" title="{vlang('Edit this image of the programme')}">{vlang('Edit')}</a>
		&nbsp;
		&nbsp;
		<a class="button" onclick="return confirm('{vlang('Are you sure?')}');" href="{$site_url}{$sDeleteUrl}{$aImage.com_programme_image_id}" title="{vlang('Delete this image of the programme')}">{vlang('Delete')}</a>
		<br />
		<br />
		<img src="/{$sProgrammePictureDir}{$aImage.com_programme_image_id}.{$aImage.com_image_ext}" alt="{$aImage.title}" title="{$aImage.title}" />
		<br />
	</div>
{/foreach}