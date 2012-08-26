<div class="material">
	<h2>{flang($aProgrammeData, 'title')} – {vlang('Spa Hotel')} {flang($aSpaData, 'title')}, {flang($aCityData, 'title')}, {flang($aRegionData, 'title')}, {flang($aCountryData, 'title')}.</h2>
</div>
<div id="mini_baners">
	{for $i=0 to 2}
	<table class="material_1" cellpadding="0" cellspacing="0" border="0">
		<tr><td class="m_image_1"><img src="/{$sProgrammePicturesDir}{$aProgrammeImagesData[$i].com_programme_image_id}.{$aProgrammeImagesData[$i].com_image_ext}" alt="{flang($aProgrammeImagesData[$i], 'title')}" /></td></tr>
		<tr><td class="m_link">{flang($aProgrammeImagesData[$i], 'title')}</td></tr>
	</table>
	{/for}
	<div class="cl"></div>
	<div class="mini_baners_link">
		<strong><a href="#" onclick="toggle_more_photos(this); return false;">{vlang('See More Photos')}</a></strong>
	</div>
	{if $nProgrammeImagesCount > 3}
	<div id="more_photos" class="display_none">
		{for $i=3 to $nProgrammeImagesCount-1}
		<table class="material_1" cellpadding="0" cellspacing="0" border="0">
			<tr><td class="m_image_1"><img src="/{$sProgrammePicturesDir}{$aProgrammeImagesData[$i].com_programme_image_id}.{$aProgrammeImagesData[$i].com_image_ext}" alt="{flang($aProgrammeImagesData[$i], 'title')}" /></td></tr>
			<tr><td class="m_link">{flang($aProgrammeImagesData[$i], 'title')}</td></tr>
		</table>
		{/for}
		<div class="cl"></div>
	</div>
	{/if}
</div>
 <div class="material_block">
	<div class="material">
		<div class="material_left">

			{flang($aProgrammeData, 'description')}

			<p>
				<strong>{vlang('Programme Includes')}: </strong>
				{flang($aProgrammeData, 'included')}
			</p>

			<p> 
				<strong>{vlang('Not Included')}: </strong>
				{flang($aProgrammeData, 'notincluded')}
			</p> 


			<p>
				<strong>{vlang('Terms')}:</strong> 
				{flang($aProgrammeData, 'terms')}
			</p>

			<!--
			<div class="buy_button">
				<a class="bt_push" href="/"><img src="images/buy_button.gif" alt="buy" /></a>
			</div>
			-->
			<div class="cl"></div>                

		</div>
		<div class="material_right">
			<img src="{$sCityMapsDir}{$aCityData.com_city_id}.{$aCityData.com_map_ext}" alt="{vlang('Map of')} {flang($aCityData, 'title')}" title="{vlang('Map of')} {flang($aCityData, 'title')}" />
			<p>
			{flang($aCityData, 'desc')}
			</p>
			
			<table class="flag_emblem">
				<tr>
					<td class="picture_block">
						<img src="{$sCityFlagsDir}{$aCityData.com_city_id}.{$aCityData.com_flag_ext}" alt="{vlang('Flag of')} {flang($aCityData, 'title')}" title="{vlang('Flag of')} {flang($aCityData, 'title')}" />
					</td>
					<td class="picture_block">
						<img src="{$sCityEmblemsDir}{$aCityData.com_city_id}.{$aCityData.com_emblem_ext}" alt="{vlang('Emblem of')} {flang($aCityData, 'title')}" title="{vlang('Emblem of')} {flang($aCityData, 'title')}" />
					</td>
				</tr>
				<tr>
					<td class="picture_title">
						{flang($aCityData, 'flag_label')}
					</td>
					<td class="picture_title">
						{flang($aCityData, 'emblem_label')}
					</td>
				</tr>
			</table>
					
		</div>
		<div class="cl"></div>
	</div>



	<div class="material_block">
		<div class="option_list">
			<p><strong>{vlang('Spa Essential Info')}:</strong><p>
			{foreach $aEssential_infosData as $aEssential_infoData}
				{if $aEssential_infoData.com_picture_ext}
					<table class="essential_pictures">
						<tr>
							<td>
								<img src="/{$sEssential_infoPicturesDir}{$aEssential_infoData.com_essential_info_id}.{$aEssential_infoData.com_picture_ext}" alt="{flang($aEssential_infoData, 'title')}" title="{flang($aEssential_infoData, 'title')}. {flang($aEssential_infoData, 'short_desc')}" />
							</td>
						</tr>
					</table>
				{/if}
			{/foreach}
			<div class="cl"></div>
			<div class="material_2 m_list">
				<ul>
					{for $i=0 to ceil($nEssential_infosCount / 3.0)-1}
						<li title="{flang($aEssential_infosData[$i], 'short_desc')}">{flang($aEssential_infosData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					{for $i=ceil($nEssential_infosCount / 3.0) to ceil($nEssential_infosCount * 2.0 / 3.0)-1}
						<li title="{flang($aEssential_infosData[$i], 'short_desc')}">{flang($aEssential_infosData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					{for $i=ceil($nEssential_infosCount * 2.0 / 3.0) to $nEssential_infosCount-1}
						<li title="{flang($aEssential_infosData[$i], 'short_desc')}">{flang($aEssential_infosData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
				
				
			<div class="cl"></div>

		</div>
	</div>




	<div class="material_block">
		<div class="option_list">
			<p><strong>{vlang('Spa Medical Treatments')}:</strong><p>
			<div class="material_2 m_list">
				<ul>
					{for $i=0 to ceil($nMedical_treatmentsCount / 3.0)-1}
						<li title="{flang($aMedical_treatmentsData[$i], 'short_desc')}">{flang($aMedical_treatmentsData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					{for $i=ceil($nMedical_treatmentsCount / 3.0) to ceil($nMedical_treatmentsCount * 2.0 / 3.0)-1}
						<li title="{flang($aMedical_treatmentsData[$i], 'short_desc')}">{flang($aMedical_treatmentsData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					{for $i=ceil($nMedical_treatmentsCount * 2.0 / 3.0) to $nMedical_treatmentsCount-1}
						<li title="{flang($aMedical_treatmentsData[$i], 'short_desc')}">{flang($aMedical_treatmentsData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
				
				
			<div class="cl"></div>
			
			
		</div>
	</div>
				
	
				

	<div class="material_block">
		<div class="option_list">
			<p><strong>{vlang('Spa Facilities')}:</strong><p>
			<div class="material_2 m_list">
				<ul>
					{for $i=0 to ceil($nFacilitiesCount / 3.0)-1}
						<li title="{flang($aFacilitiesData[$i], 'short_desc')}">{flang($aFacilitiesData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					{for $i=ceil($nFacilitiesCount / 3.0) to ceil($nFacilitiesCount * 2.0 / 3.0)-1}
						<li title="{flang($aFacilitiesData[$i], 'short_desc')}">{flang($aFacilitiesData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
			<div class="material_1 m_list">
				<ul>
					{for $i=ceil($nFacilitiesCount * 2.0 / 3.0) to $nFacilitiesCount-1}
						<li title="{flang($aFacilitiesData[$i], 'short_desc')}">{flang($aFacilitiesData[$i], 'title')}</li>
					{/for}
				</ul>
			</div>
			
				
			<div class="cl"></div>
			
		</div>
	</div>
<!--
	<div class="buy_button_1"> <a class="bt_push" href="/"><img src="images/buy_button.gif" alt="buy" /></a></div>
-->

 </div>