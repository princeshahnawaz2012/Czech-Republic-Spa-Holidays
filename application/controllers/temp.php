<?php

class temp
{
	
	function __construct()
		{
			parent::__construct();
	}

	//=======================PROGRAMME======================================//
	
	/**
	 * Редактирование 
	 */
	public function programme_edit($nProgrammeId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nProgrammeId) )
		{
			redirect($this->router->class . '/programmes', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/programmes', 'refresh');
		}
		$this->load->model('programmes_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Programme', 'required|xss_clean');
		$this->form_validation->set_rules('description', 'Description', 'xss_clean');
		$this->form_validation->set_rules('included', 'Included', 'xss_clean');
		$this->form_validation->set_rules('notincluded', 'Not included', 'xss_clean');
		$this->form_validation->set_rules('terms', 'Terms', 'xss_clean');
		$this->form_validation->set_rules('seo_link', 'Seo Link', 'xss_clean');
		$this->form_validation->set_rules('metakeywords', 'Meta keywords', 'xss_clean');
		$this->form_validation->set_rules('metadescription', 'Meta description', 'xss_clean');
		$this->form_validation->set_rules('short_desc', 'Short Description', 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aProgrammeData = array(
				'programme_id' => $nProgrammeId,
				'title' => $this->input->post('title'),
				'description' => $this->input->post('description'),
				'included' => $this->input->post('included'),
				'notincluded' => $this->input->post('notincluded'),
				'terms' => $this->input->post('terms'),
				'seo_link' => $this->input->post('seo_link'),
				'metakeywords' => $this->input->post('metakeywords'),
				'metadescription' => $this->input->post('metadescription'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$aProgrammeTranslateData = $this->programmes_model->get_translate($sLang, array('programme_id' => $nProgrammeId), TRUE , 1);
			if ( ! empty($aProgrammeTranslateData) )
			{
				$this->programmes_model->save_translate($sLang, $aProgrammeData, $nProgrammeId);
			}
			else
			{
				$this->programmes_model->save_translate($sLang, $aProgrammeData);
			}
			redirect($this->router->class . '/programmes', 'refresh');
		}
		else
		{
			$aProgrammeDefaultTranslateData = $this->programmes_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('programme_id' => $nProgrammeId), TRUE , 1);
			$aProgrammeTranslateData = $this->programmes_model->get_translate($sLang, array('programme_id' => $nProgrammeId), TRUE , 1);
			if ( empty($aProgrammeTranslateData) )
			{
				$aProgrammeTranslateData = $aProgrammeDefaultTranslateData;
			}
			$this->smarty->assign('aProgrammeTranslateData', $aProgrammeTranslateData);
			$this->smarty->assign('aProgrammeDefaultTranslateData', $aProgrammeDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('description'), 'description');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('included'), 'included');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('notincluded'), 'notincluded');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('terms'), 'terms');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('seo_link'), 'seo_link');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metakeywords'), 'metakeywords');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metadescription'), 'metadescription');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/programmes');
			$this->title(vlang('Translating a programme'));
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
	public function programmes($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~', $nOffset=0)
	{
		$this->load->model('programmes_model');
		
		$this->javascript('adm/programmes/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'programmes.com_programme_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_programmes.title' => '',
			LANGUAGE_ABBR_DEFAULT . '_categories.title' => '',
			LANGUAGE_ABBR_DEFAULT . '_spas.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('programmes.com_programme_id', LANGUAGE_ABBR_DEFAULT . '_programmes.title', LANGUAGE_ABBR_DEFAULT . '_category_title', LANGUAGE_ABBR_DEFAULT . '_spa_title');
		$aOrdersName = array(vlang('ID'), vlang('Title'), vlang('Category'), vlang('Hotel spa'));
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
		
		if( $sFilter == '~~~' )
		{
			if ( empty($nPerPage) )
			{
				$aProgrammes = $this->programmes_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aProgrammes = $this->programmes_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->programmes_model->get_count_adm_list();
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
				$aProgrammes = $this->programmes_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aProgrammes = $this->programmes_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->programmes_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aProgrammes', $aProgrammes);
		$this->title(vlang('The programmes'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
	 */
	public function programme_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('programmes_model');
				$aResult = $this->programmes_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	
	//========================END OF PROGRAMME===========================//
	
	
	
}