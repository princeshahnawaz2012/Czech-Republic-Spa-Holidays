<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_locations extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->smarty->assign('COUNTRY_ALL', COUNTRY_ALL);
		$this->smarty->assign('COUNTRY_ACTIVE', COUNTRY_ACTIVE);
		$this->smarty->assign('COUNTRY_INACTIVE', COUNTRY_INACTIVE);
		$this->smarty->assign('REGION_ALL', REGION_ALL);
		$this->smarty->assign('REGION_ACTIVE', REGION_ACTIVE);
		$this->smarty->assign('REGION_INACTIVE', REGION_INACTIVE);
		$this->smarty->assign('CITY_ALL', CITY_ALL);
		$this->smarty->assign('CITY_ACTIVE', CITY_ACTIVE);
		$this->smarty->assign('CITY_INACTIVE', CITY_INACTIVE);	
	}
	
	public function index()
	{
		$this->cities();
	}
	
	
	//=====================COUNTRY==========================//
	
	
	/**
	 * Страна, форма добавления и обработчик формы(также AJAX) 
	 */
	public function country_add()
	{
		$this->load->model('countries_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Country', 'required|xss_clean');
		$this->form_validation->set_rules('com_iso', 'ISO', 'required|exact_length[2]|xss_clean|alpha');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCountryData = array(
				'com_active' => COUNTRY_ACTIVE,
				'com_iso' => strtoupper($this->input->post('com_iso')),
				'com_country_id' => NULL,
				'com_order' => $this->input->post('com_order'),
			);
			$nCountryId = $this->countries_model->save($aCountryData);
			$aCountryTranslateData = array(
				'country_id' => $nCountryId,
				'title' => $this->input->post('title', TRUE),
			);
			$this->countries_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aCountryTranslateData);
			if ( $this->isAjaxRequest('POST') )
			{
				$aCountryData = array_merge($aCountryData, $aCountryTranslateData);
				$this->AjaxResponse($aCountryData, TRUE);
			}
			redirect($this->router->class . '/countries', 'refresh');
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aCountryData = array(
					'country_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aCountryData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_iso'), 'com_iso');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/countries');
			$this->title(vlang('Adding a country'));
			$this->view();
		}
	}
	
	
	/**
	 * Список всех стран в системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function countries($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('countries_model');
		
		$this->javascript('adm/countries/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'countries.com_country_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_countries.title' => '',
			'countries.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_country_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_iso', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Title', 'ISO', 'Status', 'Order');
		$aDirects = array('up'=>'asc', 'down'=>'desc');
		$aDirectsSuffixTitle = array('up' => '&triangle;', 'down' => '&triangledown;');
		$aDirectsLinkExchanger = array('up' => 'down', 'down' => 'up');		
		$aOrderLinks = array();
		
		$this->smarty->assign('nOrders', count($aOrders));
		
		foreach ( $aOrders as $nKey => $sValue )
		{
			$aOrderLinks[$sValue] = anchor($this->router->class . '/' .$this->router->method . '/' . $nPerPage . '/' . $nKey . '/' . $sDirect . '/' . $sFilter . '/' . $nOffset, $aOrdersName[$nKey]);
		}
		$aOrderLinks[$aOrders[$nOrder]] = anchor($this->router->class . '/' .$this->router->method . '/' . $nPerPage . '/' . $nOrder . '/' . $aDirectsLinkExchanger[$sDirect] . '/' . $sFilter . '/' . $nOffset, $aOrdersName[$nOrder] . $aDirectsSuffixTitle[$sDirect]);
		$this->smarty->assign('aOrderLinks', $aOrderLinks);
		
		if( $sFilter == '~~' )
		{
			if ( empty($nPerPage) )
			{
				$aCountries = $this->countries_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCountries = $this->countries_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->countries_model->get_count();
		}
		else
		{
			$i = 0;
			$aFilterData = explode('~', $sFilter);
			foreach ( $aFilters as $nKey => $sValue )
			{
				$aFilters[$nKey] = urldecode($aFilterData[$i++]);
			}

			$this->smarty->assign('aFilters', $aFilters);
			foreach ($aFilters as $nKey => $sValue )
			{
				if ( empty($sValue) )
				{
					unset($aFilters[$nKey]);
				}
			}

			if ( empty($nPerPage) )
			{
				$aCountries = $this->countries_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCountries = $this->countries_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->countries_model->get_count_adm_list($aFilters);
		}
				
		$this->smarty->assign('aCountries', $aCountries);
		
		
		$sPagination='';
		if ( !empty($nPerPage) )
		{
			$this->load->library('pagination');
			$aConfig['base_url'] = strtr(site_url($this->router->class . '/' . $this->router->method . '/' . $nPerPage . '/' . $nOrder . '/' . $sDirect . '/' . $sFilter), $this->config->item('url_suffix'), '') . '/';
			$aConfig['per_page'] = $nPerPage;
			$aConfig['uri_segment'] = 7;
			$aConfig['num_links'] = 4;
			$aConfig['full_tag_open'] = '';
			$aConfig['full_tag_close'] = '';
			$aConfig['anchor_class'] = ' class="curved" ';
			$aConfig['cur_tag_open'] = '<span class="active curved">';
			$aConfig['cur_tag_close'] = '</span>';
//			$aConfig['first_link'] = FALSE;
//			$aConfig['last_link'] = FALSE;
//			$aConfig['prev_link'] = FALSE;
//			$aConfig['next_link'] = FALSE;
			$this->pagination->initialize($aConfig);
			$sPagination = $this->pagination->create_links();
		}		
		$this->smarty->assign('sPagination', $sPagination);	
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/country_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/country_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/country_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/country_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/country_delete/');
		
		
		$nCountAllCountries = $this->countries_model->get_count();
		$nCountInactiveCountries = $this->countries_model->get_count(array('com_active' => COUNTRY_INACTIVE));
		$this->smarty->assign('nCountAllCountries', $nCountAllCountries);
		$this->smarty->assign('nCountInactiveCountries', $nCountInactiveCountries);
		$this->smarty->assign('nCountActiveCountries', $nCountAllCountries - $nCountInactiveCountries);
		$this->title(vlang('The countries'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nCountryId 
	 */
	public function country_edit($nCountryId = FALSE)
	{
		if ( ! $nCountryId )
		{
			redirect($this->router->class . '/countries', 'refresh');
		}
		$this->load->model('countries_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required');
		$this->form_validation->set_rules('com_iso', vlang('ISO'), 'required|exact_length[2]|alpha');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_iso'), 'com_iso');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$aCountryData = $this->countries_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('country_id' => $nCountryId), TRUE);
			$this->smarty->assign('aCountryData', $aCountryData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/countries');
			$this->title(vlang('Editing a country'));
			$this->view();
		}
		else
		{
			$aCountryData = array(
				'com_iso' => strtoupper($this->input->post('com_iso')),
				'com_order' => intval($this->input->post('com_order')),
			);
			$this->countries_model->save($aCountryData, $nCountryId);
			
			$aCountryData = array(
				'title' => $this->input->post('title'),
			);
			$this->countries_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aCountryData, $nCountryId);
			
			redirect($this->router->class . '/countries', 'refresh');
		}
	}

	/**
	 * Активация записи
	 * @param int $nCountryId 
	 */
	public function country_activate($nCountryId = FALSE)
	{
		if ( ! $nCountryId )
		{
			redirect($this->router->class . '/countries');
		}
		$this->load->model('countries_model');
		$aData = array(
			'com_active' => COUNTRY_ACTIVE
		);
		if ( $nCountryId )
		{
			$this->countries_model->save($aData, $nCountryId);
			redirect($this->router->class . '/countries');
		}
		else
		{
			$aCountryId = $this->input->post('aCountryId');
			if ( $aCountryId )
			{
				echo $this->countries_model->save($aData, $aCountryId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nCountryId 
	 */
	public function country_deactivate($nCountryId = FALSE)
	{
		if ( ! $nCountryId )
		{
			redirect($this->router->class . '/countries');
		}
		$this->load->model('countries_model');
		$aData = array(
			'com_active' => COUNTRY_INACTIVE
		);
		if ( $nCountryId )
		{
			$this->countries_model->save($aData, $nCountryId);
			redirect($this->router->class . '/countries');
		}
		else
		{
			$aCountryId = $this->input->post('aCountryId');
			if ( $aCountryId )
			{
				echo $this->countries_model->save($aData, $aCountryId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nCountryId 
	 */
	public function country_delete($nCountryId = FALSE)
	{
		if ( ! $nCountryId )
		{
			redirect($this->router->class . '/countries', 'refresh');
		}
		$this->load->model('countries_model');
		$this->countries_model->delete($nCountryId);
		redirect($this->router->class . '/countries', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function country_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nCountryId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nCountryId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('countries_model');
				if ( $this->countries_model->get_count(array('com_country_id' => $nCountryId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->countries_model->save(array('com_order' => $nOrder), $nCountryId);
				if ( $nAffectedRows )
				{
					$this->AjaxResponse('ok', FALSE);
				}
			}
		}
		$this->AjaxResponse('error', FALSE);
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию статьи 
	 */
	public function country_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('countries_model');
				$aResult = $this->countries_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
				$aResponse = array();
				foreach ( $aResult as $aRow )
				{
					$aResponse[] = $aRow['title'];
				}
				$this->AjaxResponse($aResponse, TRUE);
			}
		}
		$this->AjaxResponse('["' . vlang('Autocomplete error') . '"]', FALSE);
	}
	
	
	
	//========================END OF COUNTRY=========================//
	
	
	//========================REGION===============================//
	
	
	/**
	 * Регион, форма добавления и обработчик формы(также AJAX) 
	 */
	public function region_add()
	{
		$this->load->model('regions_model');
		$this->load->library('form_validation');
		$this->javascript('adm/regions/country_add.js');
		$this->form_validation->set_rules('title', 'Region', 'required|xss_clean');
		$this->form_validation->set_rules('com_country_id', 'Country', 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		if ( $this->form_validation->run() === TRUE )
		{
			$aRegionData = array(
				'com_active' => REGION_ACTIVE,
				'com_order' => $this->input->post('com_order'),
				'com_country_id' => $this->input->post('com_country_id'),
				'com_region_id' => NULL,
			);
			$nRegionId = $this->regions_model->save($aRegionData);
			$aRegionData = array(
				'region_id' => $nRegionId,
				'title' => $this->input->post('title'),
			);
			$this->regions_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aRegionData);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aRegionData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/regions', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aRegionData = array(
					'region_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aRegionData, TRUE);
			}
			$this->load->model('countries_model');
			$aCountries = $this->countries_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aCountries', $aCountries);
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_country_id'), 'com_country_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/regions');
			$this->title(vlang('Adding a region'));
			$this->view();
		}
	}
	
	
	/**
	 * Список всех регионов в системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function regions($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~', $nOffset=0)
	{
		$this->load->model('regions_model');
		
		$this->javascript('adm/regions/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'regions.com_region_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_regions.title' => '',
			LANGUAGE_ABBR_DEFAULT . '_countries.title' => '',
			'regions.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_region_id', LANGUAGE_ABBR_DEFAULT . '_title', LANGUAGE_ABBR_DEFAULT . '_country_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Region', 'Country', 'Status', 'Order');
		$aDirects = array('up'=>'asc', 'down'=>'desc');
		$aDirectsSuffixTitle = array('up' => '&triangle;', 'down' => '&triangledown;');
		$aDirectsLinkExchanger = array('up' => 'down', 'down' => 'up');		
		$aOrderLinks = array();
		
		$this->smarty->assign('nOrders', count($aOrders));
		
		foreach ( $aOrders as $nKey => $sValue )
		{
			$aOrderLinks[$sValue] = anchor($this->router->class . '/' .$this->router->method . '/' . $nPerPage . '/' . $nKey . '/' . $sDirect . '/' . $sFilter . '/' . $nOffset, $aOrdersName[$nKey]);
		}
		$aOrderLinks[$aOrders[$nOrder]] = anchor($this->router->class . '/' .$this->router->method . '/' . $nPerPage . '/' . $nOrder . '/' . $aDirectsLinkExchanger[$sDirect] . '/' . $sFilter . '/' . $nOffset, $aOrdersName[$nOrder] . $aDirectsSuffixTitle[$sDirect]);
		$this->smarty->assign('aOrderLinks', $aOrderLinks);
		
		if( $sFilter == '~~~' )
		{
			if ( empty($nPerPage) )
			{
				$aRegions = $this->regions_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aRegions = $this->regions_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->regions_model->get_count();
		}
		else
		{
			$i = 0;
			$aFilterData = explode('~', $sFilter);
			foreach ( $aFilters as $nKey => $sValue )
			{
				$aFilters[$nKey] = urldecode($aFilterData[$i++]);
			}

			$this->smarty->assign('aFilters', $aFilters);
			foreach ($aFilters as $nKey => $sValue )
			{
				if ( empty($sValue) )
				{
					unset($aFilters[$nKey]);
				}
			}

			if ( empty($nPerPage) )
			{
				$aRegions = $this->regions_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aRegions = $this->regions_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->regions_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aRegions', $aRegions);
		
		$sPagination='';
		if ( !empty($nPerPage) )
		{
			$this->load->library('pagination');
			$aConfig['base_url'] = strtr(site_url($this->router->class . '/' . $this->router->method . '/' . $nPerPage . '/' . $nOrder . '/' . $sDirect . '/' . $sFilter), $this->config->item('url_suffix'), '') . '/';
			$aConfig['per_page'] = $nPerPage;
			$aConfig['uri_segment'] = 7;
			$aConfig['num_links'] = 4;
			$aConfig['full_tag_open'] = '';
			$aConfig['full_tag_close'] = '';
			$aConfig['anchor_class'] = ' class="curved" ';
			$aConfig['cur_tag_open'] = '<span class="active curved">';
			$aConfig['cur_tag_close'] = '</span>';
//			$aConfig['first_link'] = FALSE;
//			$aConfig['last_link'] = FALSE;
//			$aConfig['prev_link'] = FALSE;
//			$aConfig['next_link'] = FALSE;
			$this->pagination->initialize($aConfig);
			$sPagination = $this->pagination->create_links();
		}		
		$this->smarty->assign('sPagination', $sPagination);
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/region_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/region_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/region_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/region_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/region_delete/');
		
		$nCountAllRegions = $this->regions_model->get_count();
		$nCountInactiveRegions = $this->regions_model->get_count(array('com_active' => REGION_INACTIVE));
		$this->smarty->assign('nCountAllRegions', $nCountAllRegions);
		$this->smarty->assign('nCountInactiveRegions', $nCountInactiveRegions);
		$this->smarty->assign('nCountActiveRegions', $nCountAllRegions - $nCountInactiveRegions);
		$this->title(vlang('The regions'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nRegionId 
	 */
	public function region_edit($nRegionId = FALSE)
	{
		if ( ! $nRegionId )
		{
			redirect($this->router->class . '/regions', 'refresh');
		}
		$this->load->model('regions_model');
		$this->load->library('form_validation');
		$this->javascript('adm/regions/country_add.js');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_country_id', vlang('Country'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		if ( $this->form_validation->run() === TRUE )
		{
			$aRegionData = array(
				'com_order' => intval($this->input->post('com_order')),
				'com_country_id' => $this->input->post('com_country_id'),
			);
			$this->regions_model->save($aRegionData, $nRegionId);
			
			$aRegionData = array(
				'title' => $this->input->post('title'),
			);
			$this->regions_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aRegionData, $nRegionId);
			if ( $this->isAjaxRequest() )
			{
				$this->AjaxResponse(intval($nRegionId), FALSE);
			}
			else
			{
				redirect($this->router->class . '/regions', 'refresh');
			}
		}
		else
		{
			$this->load->model('countries_model');
			$aCountries = $this->countries_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aCountries', $aCountries);
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_country_id'), 'com_country_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$aRegionData = $this->regions_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('region_id' => $nRegionId), TRUE);
			$this->smarty->assign('aRegionData', $aRegionData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/regions');
			$this->title(vlang('Editing a region'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nRegionId 
	 */
	public function region_activate($nRegionId = FALSE)
	{
		if ( ! $nRegionId )
		{
			redirect($this->router->class . '/regions');
		}
		$this->load->model('regions_model');
		$aData = array(
			'com_active' => REGION_ACTIVE
		);
		if ( $nRegionId )
		{
			$this->regions_model->save($aData, $nRegionId);
			redirect($this->router->class . '/regions');
		}
		else
		{
			$aRegionId = $this->input->post('aRegionId');
			if ( $aRegionId )
			{
				echo $this->regions_model->save($aData, $aRegionId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nRegionId 
	 */
	public function region_deactivate($nRegionId = FALSE)
	{
		if ( ! $nRegionId )
		{
			redirect($this->router->class . '/regions');
		}
		$this->load->model('regions_model');
		$aData = array(
			'com_active' => REGION_INACTIVE
		);
		if ( $nRegionId )
		{
			$this->regions_model->save($aData, $nRegionId);
			redirect($this->router->class . '/regions');
		}
		else
		{
			$aRegionId = $this->input->post('aRegionId');
			if ( $aRegionId )
			{
				echo $this->regions_model->save($aData, $aRegionId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nRegionId 
	 */
	public function region_delete($nRegionId = FALSE)
	{
		if ( ! $nRegionId )
		{
			redirect($this->router->class . '/regions', 'refresh');
		}
		$this->load->model('regions_model');
		$this->regions_model->delete($nRegionId);
		redirect($this->router->class . '/regions', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function region_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nRegionId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nRegionId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('regions_model');
				if ( $this->regions_model->get_count(array('com_region_id' => $nRegionId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->regions_model->save(array('com_order' => $nOrder), $nRegionId);
				if ( $nAffectedRows )
				{
					$this->AjaxResponse('ok', FALSE);
				}
			}
		}
		$this->AjaxResponse('error', FALSE);
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию статьи 
	 */
	public function region_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('regions_model');
				$aResult = $this->regions_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
				$aResponse = array();
				foreach ( $aResult as $aRow )
				{
					$aResponse[] = $aRow['title'];
				}
				$this->AjaxResponse($aResponse, TRUE);
			}
		}
		$this->AjaxResponse('["' . vlang('Autocomplete error') . '"]', FALSE);
	}
	
	
	
	
	
	//============================END OF REGION==========================//
	
	
	
	
	//==========================CITY===================================//
	
	
	/**
	 * Город, форма добавления и обработчик формы(также AJAX) 
	 */
	public function city_add()
	{
		$this->load->model('temp_files_model');
		$this->load->model('cities_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'City', 'required|xss_clean');
		$this->form_validation->set_rules('com_region_id', 'Region', 'integer|required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer|xss_clean');
		$this->form_validation->set_rules('desc', 'Description', 'xss_clean');
		$this->form_validation->set_rules('flag_label', 'Flag Label', 'xss_clean');
		$this->form_validation->set_rules('emblem_label', 'Emblem Label', 'xss_clean');
		$this->form_validation->set_rules('com_flag_ext', 'Flag', 'xss_clean');
		$this->form_validation->set_rules('com_map_ext', 'Map', 'xss_clean');
		$this->form_validation->set_rules('com_emblem_ext', 'Emblem', 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCityData = array(
				'com_active' => CITY_ACTIVE,
				'com_region_id' => $this->input->post('com_region_id'),
				'com_city_id' => NULL,
				'com_order' => $this->input->post('com_order'),
			);
			$nCityId = $this->cities_model->save($aCityData);
			
			//перемещаем изображения в папки хранения с временной папки редактирования
			$aCityData = array(
				'com_flag_ext' => $this->temp_files_model->move($this->input->post('temp_name_flag'), $this->input->post('com_flag_ext'), './' . $this->config->item('city_flags_dir'), $nCityId),
				'com_map_ext' => $this->temp_files_model->move($this->input->post('temp_name_map'), $this->input->post('com_map_ext'), './' . $this->config->item('city_maps_dir'), $nCityId),
				'com_emblem_ext' => $this->temp_files_model->move($this->input->post('temp_name_emblem'), $this->input->post('com_emblem_ext'), './' . $this->config->item('city_emblems_dir'), $nCityId),
			);
			//сохраняем раширения изображений
			$this->cities_model->save($aCityData, $nCityId);
			
			
			$aCityData = array(
				'city_id' => $nCityId,
				'title' => $this->input->post('title'),
				'desc' => $this->input->post('desc'),
				'flag_label' => $this->input->post('flag_label'),
				'emblem_label' => $this->input->post('emblem_label'),
			);
			$this->cities_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aCityData);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aCityData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/cities', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aCityData = array(
					'city_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aCityData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_region_id'), 'com_region_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('desc'), 'desc');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('flag_label'), 'flag_label');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('emblem_label'), 'emblem_label');

			$this->load->model('regions_model');
			
			$aRegions = $this->regions_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$sTempNameMap = $this->isPostMethod() ? $this->POST('temp_name_map', TRUE) : $this->temp_files_model->get();
			$sTempNameFlag = $this->isPostMethod() ? $this->POST('temp_name_flag', TRUE) : $this->temp_files_model->get();
			$sTempNameEmblem = $this->isPostMethod() ? $this->POST('temp_name_emblem', TRUE) : $this->temp_files_model->get();
			
//			$sTempDir = './' . $this->config->item('temp_files_dir');
//			
//			$bExistsMap = !$this->isPostMethod() ? 0 : (file_exists($sTempDir . $sTempNameMap . '.' . $this->POST('com_map_ext', TRUE)) ? 1 : 0 );
//			$bExistsFlag = !$this->isPostMethod() ? 0 : (file_exists($sTempDir . $sTempNameFlag . '.' . $this->POST('com_flag_ext', TRUE)) ? 1 : 0 );
//			$bExistsEmblem = !$this->isPostMethod() ? 0 : (file_exists($sTempDir . $sTempNameEmblem . '.' . $this->POST('com_emblem_ext', TRUE)) ? 1 : 0 );
//			
//			$this->smarty->assign('bExistsMap', $bExistsMap);
//			$this->smarty->assign('bExistsFlag', $bExistsFlag);
//			$this->smarty->assign('bExistsEmblem', $bExistsEmblem);
			
			$this->smarty->assign('sTempNameMap', $sTempNameMap);
			$this->smarty->assign('sTempNameFlag', $sTempNameFlag);
			$this->smarty->assign('sTempNameEmblem', $sTempNameEmblem);
			
			$this->smarty->assign('aRegions', $aRegions);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/cities');
			$aTempalteVar = array(
				'max_map_width' => $this->config->item('max_city_map_width'),
				'max_flag_width' => $this->config->item('max_city_flag_width'),
				'max_emblem_width' => $this->config->item('max_city_emblem_width'),
				'max_map_height' => $this->config->item('max_city_map_height'),
				'max_flag_height' => $this->config->item('max_city_flag_height'),
				'max_emblem_height' => $this->config->item('max_city_emblem_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/city_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/city_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/city_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/city_rotate_image/',
			);
			
			$nMapMaxWidth = $this->config->item('max_city_map_width');
			$nMapMaxHeight = 0;
			$nMapMinWidth = 0;
			$nMapMinHeight = 0;
			$this->smarty->assign('nMapMaxWidth', $nMapMaxWidth);
			$this->smarty->assign('nMapMaxHeight', $nMapMaxHeight);
			$this->smarty->assign('nMapMinWidth', $nMapMinWidth);
			$this->smarty->assign('nMapMinHeight', $nMapMinHeight);
			
			$nFlagMaxWidth = $this->config->item('max_city_flag_width');
			$nFlagMaxHeight = 0;
			$nFlagMinWidth = 0;
			$nFlagMinHeight = 0;
			$this->smarty->assign('nFlagMaxWidth', $nFlagMaxWidth);
			$this->smarty->assign('nFlagMaxHeight', $nFlagMaxHeight);
			$this->smarty->assign('nFlagMinWidth', $nFlagMinWidth);
			$this->smarty->assign('nFlagMinHeight', $nFlagMinHeight);
			
			$nEmblemMaxWidth = $this->config->item('max_city_emblem_width');
			$nEmblemMaxHeight = 0;
			$nEmblemMinWidth = 0;
			$nEmblemMinHeight = 0;
			$this->smarty->assign('nEmblemMaxWidth', $nEmblemMaxWidth);
			$this->smarty->assign('nEmblemMaxHeight', $nEmblemMaxHeight);
			$this->smarty->assign('nEmblemMinWidth', $nEmblemMinWidth);
			$this->smarty->assign('nEmblemMinHeight', $nEmblemMinHeight);
			
			$this->template_var($aTempalteVar);
			$this->title(vlang('Adding a city'));
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			$this->javascript('adm/cities/region_add.js');
			$this->javascript('adm/regions/country_add.js');
			$this->javascript('adm/cities/city_images_add.js');
			$this->view();
		}
	}
	
	
	/**
	 * Список всех стран в системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function cities($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~~', $nOffset=0)
	{
		$this->load->model('cities_model');
		
		$this->javascript('adm/cities/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'cities.com_city_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_cities.title' => '',
			LANGUAGE_ABBR_DEFAULT . '_regions.title' => '',
			LANGUAGE_ABBR_DEFAULT . '_countries.title' => '',
			'cities.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_city_id', LANGUAGE_ABBR_DEFAULT . '_title', LANGUAGE_ABBR_DEFAULT . '_region_title', LANGUAGE_ABBR_DEFAULT . '_country_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Title', 'Region', 'Country', 'Status', 'Order');
		$aDirects = array('up'=>'asc', 'down'=>'desc');
		$aDirectsSuffixTitle = array('up' => '&triangle;', 'down' => '&triangledown;');
		$aDirectsLinkExchanger = array('up' => 'down', 'down' => 'up');		
		$aOrderLinks = array();
		
		$this->smarty->assign('nOrders', count($aOrders));
		
		foreach ( $aOrders as $nKey => $sValue )
		{
			$aOrderLinks[$sValue] = anchor($this->router->class . '/' .$this->router->method . '/' . $nPerPage . '/' . $nKey . '/' . $sDirect . '/' . $sFilter . '/' . $nOffset, $aOrdersName[$nKey]);
		}
		$aOrderLinks[$aOrders[$nOrder]] = anchor($this->router->class . '/' .$this->router->method . '/' . $nPerPage . '/' . $nOrder . '/' . $aDirectsLinkExchanger[$sDirect] . '/' . $sFilter . '/' . $nOffset, $aOrdersName[$nOrder] . $aDirectsSuffixTitle[$sDirect]);
		$this->smarty->assign('aOrderLinks', $aOrderLinks);
		
		if( $sFilter == '~~~~' )
		{
			if ( empty($nPerPage) )
			{
				$aCities = $this->cities_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCities = $this->cities_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->cities_model->get_count();
		}
		else
		{
			$i = 0;
			$aFilterData = explode('~', $sFilter);
			foreach ( $aFilters as $nKey => $sValue )
			{
				$aFilters[$nKey] = urldecode($aFilterData[$i++]);
			}

			$this->smarty->assign('aFilters', $aFilters);
			foreach ($aFilters as $nKey => $sValue )
			{
				if ( empty($sValue) )
				{
					unset($aFilters[$nKey]);
				}
			}

			if ( empty($nPerPage) )
			{
				$aCities = $this->cities_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCities = $this->cities_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->cities_model->get_count_adm_list($aFilters);
		}
		
		$this->smarty->assign('aCities', $aCities);
		
		$sPagination='';
		if ( !empty($nPerPage) )
		{
			$this->load->library('pagination');
			$aConfig['base_url'] = strtr(site_url($this->router->class . '/' . $this->router->method . '/' . $nPerPage . '/' . $nOrder . '/' . $sDirect . '/' . $sFilter), $this->config->item('url_suffix'), '') . '/';
			$aConfig['per_page'] = $nPerPage;
			$aConfig['uri_segment'] = 7;
			$aConfig['num_links'] = 4;
			$aConfig['full_tag_open'] = '';
			$aConfig['full_tag_close'] = '';
			$aConfig['anchor_class'] = ' class="curved" ';
			$aConfig['cur_tag_open'] = '<span class="active curved">';
			$aConfig['cur_tag_close'] = '</span>';
//			$aConfig['first_link'] = FALSE;
//			$aConfig['last_link'] = FALSE;
//			$aConfig['prev_link'] = FALSE;
//			$aConfig['next_link'] = FALSE;
			$this->pagination->initialize($aConfig);
			$sPagination = $this->pagination->create_links();
		}		
		$this->smarty->assign('sPagination', $sPagination);
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/city_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/city_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/city_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/city_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/city_delete/');
		
		$nCountAllCities = $this->cities_model->get_count();
		$nCountInactiveCities = $this->cities_model->get_count(array('com_active' => CITY_INACTIVE));
		$this->smarty->assign('nCountAllCities', $nCountAllCities);
		$this->smarty->assign('nCountInactiveCities', $nCountInactiveCities);
		$this->smarty->assign('nCountActiveCities', $nCountAllCities - $nCountInactiveCities);
		$this->title(vlang('The cities'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nCityId 
	 */
	public function city_edit($nCityId = FALSE)
	{
		if ( ! $nCityId )
		{
			redirect($this->router->class . '/cities', 'refresh');
		}
		$this->load->model('temp_files_model');
		$this->load->model('cities_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'City', 'required|xss_clean');
		$this->form_validation->set_rules('com_region_id', 'Region', 'integer|required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer|xss_clean');
		$this->form_validation->set_rules('desc', 'Description', 'xss_clean');
		$this->form_validation->set_rules('flag_label', 'Flag Label', 'xss_clean');
		$this->form_validation->set_rules('emblem_label', 'Emblem Label', 'xss_clean');
		$this->form_validation->set_rules('com_flag_ext', 'Flag', 'xss_clean');
		$this->form_validation->set_rules('com_map_ext', 'Map', 'xss_clean');
		$this->form_validation->set_rules('com_emblem_ext', 'Emblem', 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCityData = array(
				'com_region_id' => $this->input->post('com_region_id'),
				'com_order' => $this->input->post('com_order'),
			);
			$this->cities_model->save($aCityData, $nCityId);
			
			//перемещаем изображения в папки хранения с временной папки редактирования
			$aCityData = array(
				'com_flag_ext' => $this->temp_files_model->move($this->input->post('temp_name_flag'), $this->input->post('com_flag_ext'), './' . $this->config->item('city_flags_dir'), $nCityId),
				'com_map_ext' => $this->temp_files_model->move($this->input->post('temp_name_map'), $this->input->post('com_map_ext'), './' . $this->config->item('city_maps_dir'), $nCityId),
				'com_emblem_ext' => $this->temp_files_model->move($this->input->post('temp_name_emblem'), $this->input->post('com_emblem_ext'), './' . $this->config->item('city_emblems_dir'), $nCityId),
			);
			//сохраняем раширения изображений
			$this->cities_model->save($aCityData, $nCityId);
			
			
			$aCityData = array(
				'title' => $this->input->post('title'),
				'desc' => $this->input->post('desc'),
				'flag_label' => $this->input->post('flag_label'),
				'emblem_label' => $this->input->post('emblem_label'),
			);
			$this->cities_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aCityData, $nCityId);
			redirect($this->router->class . '/cities', 'refresh');
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_region_id'), 'com_region_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('desc'), 'desc');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('flag_label'), 'flag_label');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('emblem_label'), 'emblem_label');

			$this->load->model('regions_model');
			$aCityData = $this->cities_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('com_city_id' => $nCityId,), TRUE);
			$aRegions = $this->regions_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$sTempNameMap = $this->isPostMethod() ? $this->POST('temp_name_map', TRUE) : $this->temp_files_model->get();
			$sTempNameFlag = $this->isPostMethod() ? $this->POST('temp_name_flag', TRUE) : $this->temp_files_model->get();
			$sTempNameEmblem = $this->isPostMethod() ? $this->POST('temp_name_emblem', TRUE) : $this->temp_files_model->get();
			
//			$sTempDir = './' . $this->config->item('temp_files_dir');
//			
//			$bExistsMap = !$this->isPostMethod() ? 0 : (file_exists($sTempDir . $sTempNameMap . '.' . $this->POST('com_map_ext', TRUE)) ? 1 : 0 );
//			$bExistsFlag = !$this->isPostMethod() ? 0 : (file_exists($sTempDir . $sTempNameFlag . '.' . $this->POST('com_flag_ext', TRUE)) ? 1 : 0 );
//			$bExistsEmblem = !$this->isPostMethod() ? 0 : (file_exists($sTempDir . $sTempNameEmblem . '.' . $this->POST('com_emblem_ext', TRUE)) ? 1 : 0 );
//			
//			$this->smarty->assign('bExistsMap', $bExistsMap);
//			$this->smarty->assign('bExistsFlag', $bExistsFlag);
//			$this->smarty->assign('bExistsEmblem', $bExistsEmblem);
			
			$this->smarty->assign('sTempNameMap', $sTempNameMap);
			$this->smarty->assign('sTempNameFlag', $sTempNameFlag);
			$this->smarty->assign('sTempNameEmblem', $sTempNameEmblem);
			
			$this->smarty->assign('aCityData', $aCityData);
			$this->smarty->assign('aRegions', $aRegions);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/cities');
			$aTempalteVar = array(
				'max_map_width' => $this->config->item('max_city_map_width'),
				'max_flag_width' => $this->config->item('max_city_flag_width'),
				'max_emblem_width' => $this->config->item('max_city_emblem_width'),
				'max_map_height' => $this->config->item('max_city_map_height'),
				'max_flag_height' => $this->config->item('max_city_flag_height'),
				'max_emblem_height' => $this->config->item('max_city_emblem_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/city_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/city_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/city_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/city_rotate_image/',
			);
			
			$nMapMaxWidth = $this->config->item('max_city_map_width');
			$nMapMaxHeight = 0;
			$nMapMinWidth = 0;
			$nMapMinHeight = 0;
			$this->smarty->assign('nMapMaxWidth', $nMapMaxWidth);
			$this->smarty->assign('nMapMaxHeight', $nMapMaxHeight);
			$this->smarty->assign('nMapMinWidth', $nMapMinWidth);
			$this->smarty->assign('nMapMinHeight', $nMapMinHeight);
			
			$nFlagMaxWidth = $this->config->item('max_city_flag_width');
			$nFlagMaxHeight = 0;
			$nFlagMinWidth = 0;
			$nFlagMinHeight = 0;
			$this->smarty->assign('nFlagMaxWidth', $nFlagMaxWidth);
			$this->smarty->assign('nFlagMaxHeight', $nFlagMaxHeight);
			$this->smarty->assign('nFlagMinWidth', $nFlagMinWidth);
			$this->smarty->assign('nFlagMinHeight', $nFlagMinHeight);
			
			$nEmblemMaxWidth = $this->config->item('max_city_emblem_width');
			$nEmblemMaxHeight = 0;
			$nEmblemMinWidth = 0;
			$nEmblemMinHeight = 0;
			$this->smarty->assign('nEmblemMaxWidth', $nEmblemMaxWidth);
			$this->smarty->assign('nEmblemMaxHeight', $nEmblemMaxHeight);
			$this->smarty->assign('nEmblemMinWidth', $nEmblemMinWidth);
			$this->smarty->assign('nEmblemMinHeight', $nEmblemMinHeight);
			
			$this->smarty->assign('sCityMapsDir', '/' . $this->config->item('city_maps_dir'));
			$this->smarty->assign('sCityFlagsDir', '/' . $this->config->item('city_flags_dir'));
			$this->smarty->assign('sCityEmblemsDir', '/' . $this->config->item('city_emblems_dir'));
			
			if ($aCityData['com_map_ext'])
			{
				copy('./' . $this->config->item('city_maps_dir') . $nCityId . '.' . $aCityData['com_map_ext'], './' . $this->config->item('temp_files_dir') . $sTempNameMap . '.' . $aCityData['com_map_ext']);
				$this->javascript('adm/cities/map_image_manipulation_add.js');
			}
			if ($aCityData['com_emblem_ext'])
			{
				copy('./' . $this->config->item('city_emblems_dir') . $nCityId . '.' . $aCityData['com_emblem_ext'], './' . $this->config->item('temp_files_dir') . $sTempNameEmblem . '.' . $aCityData['com_emblem_ext']);
				$this->javascript('adm/cities/emblem_image_manipulation_add.js');
			}
			if ($aCityData['com_flag_ext'])
			{
				copy('./' . $this->config->item('city_flags_dir') . $nCityId . '.' . $aCityData['com_flag_ext'], './' . $this->config->item('temp_files_dir') . $sTempNameFlag . '.' . $aCityData['com_flag_ext']);
				$this->javascript('adm/cities/flag_image_manipulation_add.js');
			}
			$this->template_var($aTempalteVar);
			$this->title(vlang('Editing a city'));
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			$this->javascript('adm/regions/country_add.js');
			$this->javascript('adm/cities/region_add.js');
			$this->javascript('adm/cities/city_images_add.js');
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nCityId 
	 */
	public function city_activate($nCityId = FALSE)
	{
		if ( ! $nCityId )
		{
			redirect($this->router->class . '/cities');
		}
		$this->load->model('cities_model');
		$aData = array(
			'com_active' => CITY_ACTIVE
		);
		if ( $nCityId )
		{
			$this->cities_model->save($aData, $nCityId);
			redirect($this->router->class . '/cities');
		}
		else
		{
			$aCityId = $this->input->post('aCityId');
			if ( $aCityId )
			{
				echo $this->cities_model->save($aData, $aCityId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nCityId 
	 */
	public function city_deactivate($nCityId = FALSE)
	{
		if ( ! $nCityId )
		{
			redirect($this->router->class . '/cities');
		}
		$this->load->model('cities_model');
		$aData = array(
			'com_active' => CITY_INACTIVE
		);
		if ( $nCityId )
		{
			$this->cities_model->save($aData, $nCityId);
			redirect($this->router->class . '/cities');
		}
		else
		{
			$aCityId = $this->input->post('aCityId');
			if ( $aCityId )
			{
				echo $this->cities_model->save($aData, $aCityId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nCityId 
	 */
	public function city_delete($nCityId = FALSE)
	{
		if ( ! $nCityId )
		{
			redirect($this->router->class . '/cities', 'refresh');
		}
		$this->load->model('cities_model');
		$this->cities_model->unlink_files($nCityId);
		$this->cities_model->delete($nCityId);
		redirect($this->router->class . '/cities', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function city_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nCityId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nCityId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('cities_model');
				if ( $this->cities_model->get_count(array('com_city_id' => $nCityId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->cities_model->save(array('com_order' => $nOrder), $nCityId);
				if ( $nAffectedRows )
				{
					$this->AjaxResponse('ok', FALSE);
				}
			}
		}
		$this->AjaxResponse('error', FALSE);
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию статьи 
	 */
	public function city_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('cities_model');
				$aResult = $this->cities_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
				$aResponse = array();
				foreach ( $aResult as $aRow )
				{
					$aResponse[] = $aRow['title'];
				}
				$this->AjaxResponse($aResponse, TRUE);
			}
		}
		$this->AjaxResponse('["' . vlang('Autocomplete error') . '"]', FALSE);
	}
	
	
	public function city_upload_image($sImageType = 'map')
	{
		if ($this->isPostMethod())
		{
			$sTempName = $this->input->post('temp_name');
			$bOnlyZoomOut = TRUE;
			$sExt = $this->upload_image('image_file', './' . $this->config->item('temp_files_dir'), $sTempName, TRUE, $this->config->item('max_city_' . $sImageType . '_width'), 0, $bOnlyZoomOut);
			if ($sExt)
			{
				$this->AjaxResponse(array('result' => 'ok', 'ext' => $sExt), TRUE);
			}
			else
			{
				$this->AjaxResponse(
					array(
						'result' => 'error',
						'temp_name' => $sTempName,
						'ext' => $sExt,
						'errors' => $this->upload->display_errors(" ", " "),
					),
					TRUE
				);
			}
		}
		die(vlang('Access denied'));
	}
	
	public function city_crop_image($sImageType = 'map')
	{
		if ($this->isAjaxRequest('POST'))
		{
			$sFileInput = './' . $this->config->item('temp_files_dir') . $this->input->post('temp_name') . '.' . $this->input->post('ext');
			$nW = $this->input->post('width');
			$nH = $this->input->post('height');
			$nX = $this->input->post('x_axis');
			$nY = $this->input->post('y_axis');
			$bResult = $this->crop($sFileInput, $nW, $nH, $nX, $nY);
			if ($bResult)
			{
				$this->AjaxResponse(array('result' => 'ok'), TRUE);
			}
			else
			{
				$this->AjaxResponse(
					array(
						'result' => 'error',
						'temp_name' => $this->input->post('temp_name'),
						'ext' => $this->input->post('ext'),
						'width' => $nW,
						'height' => $nH,
						'x_axis' => $nX,
						'y_axis' => $nY,
						'errors' => $this->image_lib->display_errors(" ", " "),
					),
					TRUE
				);
			}
		}
		die(vlang('Access denied'));
	}
	
	public function city_resize_image($sImageType = 'map')
	{
		if ($this->isAjaxRequest('POST'))
		{
			$sFileInput = './' . $this->config->item('temp_files_dir') . $this->input->post('temp_name') . '.' . $this->input->post('ext');
			$nW = $this->input->post('width');
			$nH = $this->input->post('height');
			$bResult = $this->resize($sFileInput, $sFileInput, $nW, $nH);
			if ($bResult)
			{
				$this->AjaxResponse(array('result' => 'ok'), TRUE);
			}
			else
			{
				$this->AjaxResponse(
					array(
						'result' => 'error',
						'temp_name' => $this->input->post('temp_name'),
						'ext' => $this->input->post('ext'),
						'width' => $nW,
						'height' => $nH,
						'errors' => $this->image_lib->display_errors(" ", " "),
					),
					TRUE
				);
			}
		}
		die(vlang('Access denied'));
	}
	
	public function city_rotate_image($sImageType = 'map')
	{
		if ($this->isAjaxRequest('POST'))
		{
			$sFileInput = './' . $this->config->item('temp_files_dir') . $this->input->post('temp_name') . '.' . $this->input->post('ext');
			$nAngle = $this->input->post('angle');
			$bResult = $this->rotate($sFileInput, $nAngle);
			if ($bResult)
			{
				$this->AjaxResponse(array('result' => 'ok'), TRUE);
			}
			else
			{
				$this->AjaxResponse(
					array(
						'result' => 'error',
						'temp_name' => $this->input->post('temp_name'),
						'ext' => $this->input->post('ext'),
						'angle' => $nAngle,
						'errors' => $this->image_lib->display_errors(" ", " "),
					),
					TRUE
				);
			}
		}
		die(vlang('Access denied'));
	}
	
	//========================END OF CITY=====================//
	
	
}

/* End of file adm_locaitons.php */
/* Location: ./application/controllers/adm_locations.php */