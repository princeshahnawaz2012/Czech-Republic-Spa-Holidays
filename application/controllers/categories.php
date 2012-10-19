<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "base.php");

class Categories extends Base_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('categories_model');
		$this->smarty->assign('COMPLEX_TREATMENT_COSMETIC', COMPLEX_TREATMENT_COSMETIC);
		$this->smarty->assign('COMPLEX_TREATMENT_MEDICAL', COMPLEX_TREATMENT_MEDICAL);
	}
	
	public function index()
	{
		$aCategoriesData = $this->categories_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_active' => CATEGORY_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$this->smarty->assign('aCategoriesData', $aCategoriesData);
		$this->smarty->assign('sCategoryPicturesDir', $this->config->item('category_pictures_dir'));
//		$this->smarty->assign('CATEGORY_SHOW_SHORT_DESCRIPTION', CATEGORY_SHOW_SHORT_DESCRIPTION);
//		$this->smarty->assign('CATEGORY_SHOW_ILLNESES', CATEGORY_SHOW_ILLNESES);
		
		$this->metakeywords(vlang('Site title'));
		$this->metadescription(vlang('Site title'));
		$this->title(vlang('Site title'));
		$this->view();
	}

	/**
	 * Вывод категории
	 */
	public function id($nCategoryId = FALSE)
	{
		if ($nCategoryId === FALSE)
		{
			$this->not_found();
		}
		
		$aFilters = array(
			'com_category_id' => $nCategoryId,
			'com_active' => CATEGORY_ACTIVE,
		);
		$bSingle = TRUE;
		$nLimit = 1;
		$aCategoryData = $this->categories_model->get_joined_with_default_lang($this->config->item('language_abbr'), $aFilters, $bSingle, $nLimit);
		if ( ! flang($aCategoryData, 'title') )
		{
			$this->not_found();
		}
		
//		$this->smarty->assign('sType', $sType);
//		$this->smarty->assign('CATEGORY_SHOW_SHORT_DESCRIPTION', CATEGORY_SHOW_SHORT_DESCRIPTION);
//		$this->smarty->assign('CATEGORY_SHOW_ILLNESES', CATEGORY_SHOW_ILLNESES);

		$this->load->model('illneses_model');
		$aIllnesesData = $this->illneses_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_active' => ILLNESE_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$nIllnesesDataCount = count($aIllnesesData);
		$this->smarty->assign('aIllnesesData', $aIllnesesData);
		$this->smarty->assign('nIllnesesDataCount', $nIllnesesDataCount);
		$aIllnesesTitle = array();
		foreach ($aIllnesesData as $aIllneseData)
		{
			$aIllnesesTitle[$aIllneseData['com_illnese_id']] = flang($aIllneseData, 'title');
		}
		$this->smarty->assign('aIllnesesTitle', $aIllnesesTitle);
		
		
		$this->load->model('programmes_model');
		$aProgrammesData = $this->programmes_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_category_id' => $nCategoryId, 'com_active' => PROGRAMME_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$this->smarty->assign('aProgrammesData', $aProgrammesData);
		
		$aProgrammesIllnesesData = array();
		foreach ($aProgrammesData as $aProgrammeData)
		{
			$aProgrammesIllnesesData[$aProgrammeData['com_programme_id']] = $this->programmes_model->get_illnese_joined_with_default_lang($this->config->item('language_abbr'), array('com_programme_id' => $aProgrammeData['com_programme_id'], 'com_active' => ILLNESE_ACTIVE), FALSE, NULL, array('com_order' => 'asc',));
		}
		$this->smarty->assign('aProgrammesIllnesesData', $aProgrammesIllnesesData);
		
		$this->load->model('cities_model');
		$aCitiesData = $this->cities_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_active' => CITY_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$aCitiesTitle = array();
		foreach ($aCitiesData as $aCityData)
		{
			$aCitiesTitle[$aCityData['com_city_id']] = flang($aCityData, 'title');
		}
		$this->smarty->assign('aCitiesTitle', $aCitiesTitle);
		
		
		$this->load->model('spas_model');
		$aSpasData = $this->spas_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_active' => SPA_ACTIVE), FALSE, FALSE, array('com_order' => 'asc'));
		$aSpasTitle = array();
		foreach ($aSpasData as $aSpaData)
		{
			$aSpasTitle[$aSpaData['com_spa_id']] = flang($aSpaData, 'title');
		}
		$this->smarty->assign('aSpasTitle', $aSpasTitle);

		$aTemplateVar = array(
			'nCategoryId' => $nCategoryId,
		);
		$this->template_var($aTemplateVar);
		$this->title(flang($aCategoryData, 'title'));
		$this->metakeywords(flang($aCategoryData, 'metakeywords'));
		$this->metadescription(flang($aCategoryData, 'metadescription'));
		$this->javascript('jquery.scrollTo-min.js');
		$this->javascript('controllers/categories/illneses_filter.js');
		$this->smarty->assign('aCategoryData', $aCategoryData);
		$this->smarty->assign('sCategoryPicturesDir', $this->config->item('category_pictures_dir'));
		$this->view();
	}
}

/* End of file categories.php */
/* Location: ./application/controllers/categories.php */