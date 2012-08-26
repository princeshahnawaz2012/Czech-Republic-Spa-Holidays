<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "base.php");

class Programmes extends Base_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('programmes_model');
	}

	/**
	 * Вывод статьи
	 */
	public function id($nProgrammeId = FALSE)
	{
		if ($nProgrammeId === FALSE)
		{
			$this->not_found();
		}
		
		$aFilters = array(
			'com_programme_id' => $nProgrammeId,
			'com_active' => PROGRAMME_ACTIVE,
		);
		$bSingle = TRUE;
		$nLimit = 1;
		$aProgrammeData = $this->programmes_model->get_joined_with_default_lang($this->config->item('language_abbr'), $aFilters, $bSingle, $nLimit);
		if ( ! flang($aProgrammeData, 'title') )
		{
			$this->not_found();
		}
		
		
		$aProgrammeImagesData = $this->programmes_model->get_image_joined_with_default_lang($this->config->item('language_abbr'), array('com_programme_id' => $nProgrammeId, 'com_active' => PROGRAMME_IMAGE_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$this->smarty->assign('aProgrammeImagesData', $aProgrammeImagesData);
		$this->smarty->assign('nProgrammeImagesCount', count($aProgrammeImagesData));
		
		
		$this->load->model('cities_model');
		$aCityData = $this->cities_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_city_id' => $aProgrammeData['com_city_id'], 'com_active' => CITY_ACTIVE), TRUE, 1);
		if ( ! $aCityData )
		{
			$this->not_found();
		}
		$this->smarty->assign('aCityData', $aCityData);
		$this->smarty->assign('sCityMapsDir', '/' . $this->config->item('city_maps_dir'));
		$this->smarty->assign('sCityFlagsDir', '/' . $this->config->item('city_flags_dir'));
		$this->smarty->assign('sCityEmblemsDir', '/' . $this->config->item('city_emblems_dir'));
		
		
		$this->load->model('regions_model');
		$aRegionData = $this->regions_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_region_id' => $aCityData['com_region_id'], 'com_active' => REGION_ACTIVE), TRUE, 1);
		if ( ! $aRegionData )
		{
			$this->not_found();
		}
		$this->smarty->assign('aRegionData', $aRegionData);
		
		
		$this->load->model('countries_model');
		$aCountryData = $this->countries_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_country_id' => $aRegionData['com_country_id'], 'com_active' => COUNTRY_ACTIVE), TRUE, 1);
		if ( ! $aCountryData )
		{
			$this->not_found();
		}
		$this->smarty->assign('aCountryData', $aCountryData);
		
		
		$this->load->model('spas_model');
		$aSpaData = $this->spas_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_spa_id' => $aProgrammeData['com_spa_id']), TRUE, 1);
		$this->smarty->assign('aSpaData', $aSpaData);

		
		$aFacilitiesData = $this->spas_model->get_facility_joined_with_default_lang($this->config->item('language_abbr'), array('com_spa_id' => $aSpaData['com_spa_id'], 'com_active' => FACILITY_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$this->smarty->assign('aFacilitiesData', $aFacilitiesData);
		$this->smarty->assign('nFacilitiesCount', count($aFacilitiesData));
		
		$this->smarty->assign('sEssential_infoPicturesDir', $this->config->item('essential_info_pictures_dir'));
		
		$aEssential_infosData = $this->spas_model->get_essential_info_joined_with_default_lang($this->config->item('language_abbr'), array('com_spa_id' => $aSpaData['com_spa_id'], 'com_active' => ESSENTIAL_INFO_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$this->smarty->assign('aEssential_infosData', $aEssential_infosData);
		$this->smarty->assign('nEssential_infosCount', count($aEssential_infosData));

		
		$aMedical_treatmentsData = $this->spas_model->get_medical_treatment_joined_with_default_lang($this->config->item('language_abbr'), array('com_spa_id' => $aSpaData['com_spa_id'], 'com_active' => MEDICAL_TREATMENT_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$this->smarty->assign('aMedical_treatmentsData', $aMedical_treatmentsData);
		$this->smarty->assign('nMedical_treatmentsCount', count($aMedical_treatmentsData));
		
		$aTemplateVar = array(
			'str_show_more_photos' => vlang('See More Photos'),
			'str_hide_photos' => vlang('Hide Photos'),
		);
		
		$this->template_var($aTemplateVar);
		$this->title(flang($aProgrammeData, 'title'));
		$this->metakeywords(flang($aProgrammeData, 'metakeywords'));
		$this->metadescription(flang($aProgrammeData, 'metadescription'));
		$this->smarty->assign('aProgrammeData', $aProgrammeData);
		$this->smarty->assign('sProgrammePicturesDir', $this->config->item('programme_pictures_dir'));
		$this->javascript('controllers/programmes/show_images.js');
		$this->view();
	}
}

/* End of file programmes.php */
/* Location: ./application/controllers/programmes.php */