<?php

class temp
{
	
	function __construct()
	{
			parent::__construct();
	}

	//=======================STATION======================================//
	
	
	
	/**
	 * Редактирование 
	 */
	public function station_edit($nStationId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nStationId) )
		{
			redirect($this->router->class . '/stations', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/stations', 'refresh');
		}
		$this->load->model('stations_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Station'), 'required|xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aStationData = array(
				'station_id' => $nStationId,
				'title' => $this->input->post('title'),
			);
			$aStationTranslateData = $this->stations_model->get_translate($sLang, array('station_id' => $nStationId), TRUE , 1);
			if ( ! empty($aStationTranslateData) )
			{
				$this->stations_model->save_translate($sLang, $aStationData, $nStationId);
			}
			else
			{
				$this->stations_model->save_translate($sLang, $aStationData);
			}
			redirect($this->router->class . '/stations', 'refresh');
		}
		else
		{
			$aStationDefaultTranslateData = $this->stations_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('station_id' => $nStationId), TRUE , 1);
			$aStationTranslateData = $this->stations_model->get_translate($sLang, array('station_id' => $nStationId), TRUE , 1);
			if ( empty($aStationTranslateData) )
			{
				$aStationTranslateData = $aStationDefaultTranslateData;
			}
			$this->smarty->assign('aStationTranslateData', $aStationTranslateData);
			$this->smarty->assign('aStationDefaultTranslateData', $aStationDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/stations');
			$this->title(vlang('Translating a station'));
			$this->view();
		}
		
	}
	
	
	/**
	 * Список всех в системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function stations($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('stations_model');
		
		$this->javascript('adm/stations/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'stations.com_station_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_stations.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('stations.com_station_id', LANGUAGE_ABBR_DEFAULT . '_stations.title',);
		$aOrdersName = array('ID', 'Title',);
		$aDirects = array('up'=>'asc', 'down'=>'desc');
		$aDirectsSuffixTitle = array('up' => '&triangle;', 'down' => '&triangledown;');
		$aDirectsLinkExchanger = array('up' => 'down', 'down' => 'up');		
		$aOrderLinks = array();
		
		$this->smarty->assign('nOrdersNum', count($aOrders) + count($this->aLangPermissions));
		$this->smarty->assign('aLangPermissions', $this->aLangPermissions);
		$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
		
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
				$aStations = $this->stations_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aStations = $this->stations_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->stations_model->get_count_adm_list();
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
				$aStations = $this->stations_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aStations = $this->stations_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->stations_model->get_count_adm_list($aFilters);
		}
		
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
		$this->smarty->assign('aStations', $aStations);
		$this->title(vlang('The stations'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
	 */
	public function station_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('stations_model');
				$aResult = $this->stations_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	
	//========================END OF STATION===========================//
	
	
	
}