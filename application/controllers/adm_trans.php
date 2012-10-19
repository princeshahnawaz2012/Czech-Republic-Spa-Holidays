<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_trans extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->view();
	}
	
	
	//==================================ARTICLE=================================//
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию статьи 
	 */
	public function articles_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('articles_model');
				$aResult = $this->articles_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	/**
	 * Закончить редактирование статьи без сохранения изменений
	 *
	 * @param int $nArticleId
	 * @param string $sLang
	 */
	public function article_leave($nArticleId = FALSE, $sLang = FALSE)
	{
		$this->load->model('articles_model');
		if ( $nArticleId && $sLang )
		{
			$aTranslateData = array(
				'editor_id' => NULL,
				'editing_end' => NULL,
			);

			$this->articles_model->save_translate($sLang, $aTranslateData, $nArticleId);
		}
		redirect($this->router->class . '/articles', 'refresh');
	}

	public function article_prolong_editing($nArticleId = FALSE, $sLang = FALSE)
	{
		$this->load->model('articles_model');
		if ( $nArticleId && $sLang )
		{
			$aTranslateData = array(
				'editing_end' => time() + $this->config->item('article_editing_expire'),
			);
			$this->articles_model->save_translate($sLang, $aTranslateData, $nArticleId);
		}
	}
	
	
	/**
	 * Редактирование перевода статьи 
	 */
	public function article_edit($nArticleId = false, $sLang = false)
	{
		if ( empty($sLang) || empty($nArticleId) )
		{
			redirect($this->router->class . '/articles', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/articles');
		}
		$this->load->model('articles_model');

		$this->javascript('tiny_mce/jquery.tinymce.js');
		$this->javascript('adm/articles/tinymce.init.js');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required');
		$this->form_validation->set_rules('full', vlang('Article body'), 'required');
		$this->form_validation->set_rules('keywords', vlang('Meta keywords'), '');
		$this->form_validation->set_rules('description', vlang('Meta description'), '');
		$this->form_validation->set_rules('seo_link', vlang('SEO link suffix'), 'alpha_dash');

		$nUserId = $this->session->userdata('user_id');

		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('full'), 'full');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('keywords'), 'keywords');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('description'), 'description');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('seo_link'), 'seo_link');

			$aArticleData = $this->articles_model->get_translate($sLang, array('article_id' => $nArticleId), TRUE);
			$aOriginalData = $this->articles_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('article_id' => $nArticleId), TRUE);
			
			$this->smarty->assign('aOriginalData', $aOriginalData);
			
			$nTime = time();
			$nEditingTime = $nTime + $this->config->item('article_editing_expire');
			if ( !empty($aArticleData) )
			{
				$this->articles_model->save_translate($sLang, array('editor_id' => $nUserId, 'editing_end' => $nEditingTime), $nArticleId);
				$this->smarty->assign('aArticleData', $aArticleData);
			}
			else
			{
				$aArticleData = array(
					'article_id' => $nArticleId,
					'title' => '',
					'full' => '',
					'keywords' => NULL,
					'description' => NULL,
					'seo_link' => NULL,
					'hits' => 0,
					'time' => $nTime,
					'author_id' => $nUserId,
					'editor_id' => $nUserId,
					'editing_end' => $nEditingTime,
				);
				$this->articles_model->save_translate($sLang, $aArticleData);
				$aArticleData['title'] = $aOriginalData['title'];
				$aArticleData['full'] = $aOriginalData['full'];
				$aArticleData['keywords'] = $aOriginalData['keywords'];
				$aArticleData['description'] = $aOriginalData['description'];
				$aArticleData['seo_link'] = $aOriginalData['seo_link'];
				$this->smarty->assign('aArticleData', $aArticleData);
			}

			$this->smarty->assign('nEditingInterval', $this->config->item('article_editing_expire'));
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->smarty->assign('sLang', $sLang);

			$this->javascript('adm/articles/prolong_editing.js');
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/article_leave/' . $nArticleId . '/' . $sLang);
			$this->title(vlang('Translating an article'));
			$this->view();
		}
		else
		{
			$aTranslateData = array(
				'title' => $this->input->post('title'),
				'full' => $this->input->post('full'),
				'keywords' => $this->input->post('keywords'),
				'description' => $this->input->post('description'),
				'seo_link' => $this->input->post('seo_link'),
				'editor_id' => NULL,
				'editing_end' => NULL,
			);

			$this->articles_model->save_translate($sLang, $aTranslateData, $nArticleId);

			redirect($this->router->class . '/articles');
		}
	}
	
	
	/**
	 * Список всех статей
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
		public function articles($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~', $nOffset=0)
	{
		$this->load->model('articles_model');
		
		$this->javascript('adm/articles/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$nTime = time();
		$this->smarty->assign('nTime', $nTime);
		
		$aFilters = array(
			'com_article_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_articles.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_article_id', LANGUAGE_ABBR_DEFAULT . '_articles.title');
		$aOrdersName = array(vlang('ID'), vlang('Title'),);
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
		
		if( $sFilter == '~' )
		{
			if ( empty($nPerPage) )
			{
				$aArticles = $this->articles_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aArticles = $this->articles_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->articles_model->get_count_adm_list();
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
				$aArticles = $this->articles_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aArticles = $this->articles_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->articles_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aArticles', $aArticles);
		$this->title(vlang('The articles'));
		$this->view();
	}
	
	//======================END OF ARTICLE====================//
	
	
	//========================COUNTRY=========================//
	
	
	/**
	 * Редактирование страны 
	 */
	public function country_edit($nCountryId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nCountryId) )
		{
			redirect($this->router->class . '/countries', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/countries', 'refresh');
		}
		$this->load->model('countries_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Country', 'required|xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCountryData = array(
				'country_id' => $nCountryId,
				'title' => $this->input->post('title'),
			);
			$aCountryTranslateData = $this->countries_model->get_translate($sLang, array('country_id' => $nCountryId), TRUE , 1);
			if ( ! empty($aCountryTranslateData) )
			{
				$this->countries_model->save_translate($sLang, $aCountryData, $nCountryId);
			}
			else
			{
				$this->countries_model->save_translate($sLang, $aCountryData);
			}
			redirect($this->router->class . '/countries', 'refresh');
		}
		else
		{
			$aCountryDefaultTranslateData = $this->countries_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('country_id' => $nCountryId), TRUE , 1);
			$aCountryTranslateData = $this->countries_model->get_translate($sLang, array('country_id' => $nCountryId), TRUE , 1);
			if ( empty($aCountryTranslateData) )
			{
				$aCountryTranslateData = $aCountryDefaultTranslateData;
			}
			$this->smarty->assign('aCountryTranslateData', $aCountryTranslateData);
			$this->smarty->assign('aCountryDefaultTranslateData', $aCountryDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/countries');
			$this->title(vlang('Translating a country'));
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
		
		$this->javascript('adm/countries/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'com_country_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_countries.title' => '',
			'com_iso' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_country_id', LANGUAGE_ABBR_DEFAULT . '_countries.title', 'com_iso');
		$aOrdersName = array('ID', 'Title', 'ISO');
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
				$aCountries = $this->countries_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCountries = $this->countries_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->countries_model->get_count_adm_list();
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
				$aCountries = $this->countries_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCountries = $this->countries_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->countries_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aCountries', $aCountries);
		$this->title(vlang('The countries'));
		$this->view();
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
	
	
	//======================END OF COUNTRY================================//
	
	
	
	//=======================REGION======================================//
	
	
	
	/**
	 * Редактирование 
	 */
	public function region_edit($nRegionId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nRegionId) )
		{
			redirect($this->router->class . '/regions', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/regions', 'refresh');
		}
		$this->load->model('regions_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Region', 'required|xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aRegionData = array(
				'region_id' => $nRegionId,
				'title' => $this->input->post('title'),
			);
			$aRegionTranslateData = $this->regions_model->get_translate($sLang, array('region_id' => $nRegionId), TRUE , 1);
			if ( ! empty($aRegionTranslateData) )
			{
				$this->regions_model->save_translate($sLang, $aRegionData, $nRegionId);
			}
			else
			{
				$this->regions_model->save_translate($sLang, $aRegionData);
			}
			redirect($this->router->class . '/regions', 'refresh');
		}
		else
		{
			$aRegionDefaultTranslateData = $this->regions_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('region_id' => $nRegionId), TRUE , 1);
			$aRegionTranslateData = $this->regions_model->get_translate($sLang, array('region_id' => $nRegionId), TRUE , 1);
			if ( empty($aRegionTranslateData) )
			{
				$aRegionTranslateData = $aRegionDefaultTranslateData;
			}
			$this->smarty->assign('aRegionTranslateData', $aRegionTranslateData);
			$this->smarty->assign('aRegionDefaultTranslateData', $aRegionDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/regions');
			$this->title(vlang('Translating a region'));
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
	public function regions($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('regions_model');
		
		$this->javascript('adm/regions/filter_translate.js');
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
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('regions.com_region_id', LANGUAGE_ABBR_DEFAULT . '_regions.title', LANGUAGE_ABBR_DEFAULT . '_country_title',);
		$aOrdersName = array('ID', 'Title', 'Country');
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
				$aRegions = $this->regions_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aRegions = $this->regions_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->regions_model->get_count_adm_list();
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
				$aRegions = $this->regions_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aRegions = $this->regions_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->regions_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aRegions', $aRegions);
		$this->title(vlang('The regions'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
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
	
	
	
	//========================END OF REGION===========================//
	
	
	
	//=======================CITY======================================//
	
	
	
	/**
	 * Редактирование 
	 */
	public function city_edit($nCityId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nCityId) )
		{
			redirect($this->router->class . '/cities', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/cities', 'refresh');
		}
		$this->load->model('cities_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'City', 'required|xss_clean');
		$this->form_validation->set_rules('flag_label', 'Flag Label', 'xss_clean');
		$this->form_validation->set_rules('emblem_label', 'Emblem Label', 'xss_clean');
		$this->form_validation->set_rules('desc', 'Description', 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCityData = array(
				'city_id' => $nCityId,
				'title' => $this->input->post('title'),
				'flag_label' => $this->input->post('flag_label'),
				'emblem_label' => $this->input->post('emblem_label'),
				'desc' => $this->input->post('desc'),
			);
			$aCityTranslateData = $this->cities_model->get_translate($sLang, array('city_id' => $nCityId), TRUE , 1);
			if ( ! empty($aCityTranslateData) )
			{
				$this->cities_model->save_translate($sLang, $aCityData, $nCityId);
			}
			else
			{
				$this->cities_model->save_translate($sLang, $aCityData);
			}
			redirect($this->router->class . '/cities', 'refresh');
		}
		else
		{
			$aCityDefaultTranslateData = $this->cities_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('city_id' => $nCityId), TRUE , 1);
			$aCityTranslateData = $this->cities_model->get_translate($sLang, array('city_id' => $nCityId), TRUE , 1);
			if ( empty($aCityTranslateData) )
			{
				$aCityTranslateData = $aCityDefaultTranslateData;
			}
			$this->smarty->assign('aCityTranslateData', $aCityTranslateData);
			$this->smarty->assign('aCityDefaultTranslateData', $aCityDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/cities');
			$this->title(vlang('Translating a city'));
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
	public function cities($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~', $nOffset=0)
	{
		$this->load->model('cities_model');
		
		$this->javascript('adm/cities/filter_translate.js');
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
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('cities.com_city_id', LANGUAGE_ABBR_DEFAULT . '_cities.title', LANGUAGE_ABBR_DEFAULT . '_region_title', LANGUAGE_ABBR_DEFAULT . '_country_title',);
		$aOrdersName = array('ID', 'Title', 'Region', 'Country');
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
				$aCities = $this->cities_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCities = $this->cities_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->cities_model->get_count_adm_list();
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
				$aCities = $this->cities_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCities = $this->cities_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->cities_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aCities', $aCities);
		$this->title(vlang('The cities'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
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
	
	
	
	//========================END OF CITY===========================//
	
	
	
	//========================CATEGORY=========================//
	
	
	/**
	 * Редактирование категории 
	 */
	public function category_edit($nCategoryId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nCategoryId) )
		{
			redirect($this->router->class . '/categories', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/categories', 'refresh');
		}
		$this->load->model('categories_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Category', 'required|xss_clean');
		$this->form_validation->set_rules('short_desc', 'Short description', 'required|xss_clean');
		$this->form_validation->set_rules('desc', 'Description', 'required|xss_clean');
		$this->form_validation->set_rules('seo_link', 'SEO Link', 'xss_clean');
		$this->form_validation->set_rules('metakeywords', 'Meta keywords', 'xss_clean');
		$this->form_validation->set_rules('metadescription', 'Meta description', 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCategoryData = array(
				'category_id' => $nCategoryId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
				'desc' => $this->input->post('desc'),
				'seo_link' => $this->input->post('seo_link'),
				'metakeywords' => $this->input->post('metakeywords'),
				'metadescription' => $this->input->post('metadescription'),
			);
			$aCategoryTranslateData = $this->categories_model->get_translate($sLang, array('category_id' => $nCategoryId), TRUE , 1);
			if ( ! empty($aCategoryTranslateData) )
			{
				$this->categories_model->save_translate($sLang, $aCategoryData, $nCategoryId);
			}
			else
			{
				$this->categories_model->save_translate($sLang, $aCategoryData);
			}
			redirect($this->router->class . '/categories', 'refresh');
		}
		else
		{
			$aCategoryDefaultTranslateData = $this->categories_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('category_id' => $nCategoryId), TRUE , 1);
			$aCategoryTranslateData = $this->categories_model->get_translate($sLang, array('category_id' => $nCategoryId), TRUE , 1);
			if ( empty($aCategoryTranslateData) )
			{
				$aCategoryTranslateData = $aCategoryDefaultTranslateData;
			}
			$this->smarty->assign('aCategoryTranslateData', $aCategoryTranslateData);
			$this->smarty->assign('aCategoryDefaultTranslateData', $aCategoryDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/categories');
			$this->title(vlang('Translating a category'));
			$this->view();
		}
		
	}
	
	
	/**
	 * Список всех категорий в системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function categories($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~', $nOffset=0)
	{
		$this->load->model('categories_model');
		
		$this->javascript('adm/categories/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'com_category_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_categories.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_category_id', LANGUAGE_ABBR_DEFAULT . '_categories.title');
		$aOrdersName = array('ID', 'Title');
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
		
		if( $sFilter == '~' )
		{
			if ( empty($nPerPage) )
			{
				$aCategories = $this->categories_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCategories = $this->categories_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->categories_model->get_count_adm_list();
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
				$aCategories = $this->categories_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCategories = $this->categories_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->categories_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('sEditUrl', $this->router->class . '/category_edit/');
		$this->smarty->assign('aCategories', $aCategories);
		$this->title(vlang('The categories'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию статьи 
	 */
	public function category_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('categories_model');
				$aResult = $this->categories_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	//======================END OF CATEGORY================================//
	
	
	
	//========================CURRENCY=========================//
	
	
	/**
	 * Редактирование валюты 
	 */
	public function currency_edit($nCurrencyId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nCurrencyId) )
		{
			redirect($this->router->class . '/currencies', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/currencies', 'refresh');
		}
		$this->load->model('currencies_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Currency', 'required|xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCurrencyData = array(
				'currency_id' => $nCurrencyId,
				'title' => $this->input->post('title'),
			);
			$aCurrencyTranslateData = $this->currencies_model->get_translate($sLang, array('currency_id' => $nCurrencyId), TRUE , 1);
			if ( ! empty($aCurrencyTranslateData) )
			{
				$this->currencies_model->save_translate($sLang, $aCurrencyData, $nCurrencyId);
			}
			else
			{
				$this->currencies_model->save_translate($sLang, $aCurrencyData);
			}
			redirect($this->router->class . '/currencies', 'refresh');
		}
		else
		{
			$aCurrencyDefaultTranslateData = $this->currencies_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('currency_id' => $nCurrencyId), TRUE , 1);
			$aCurrencyTranslateData = $this->currencies_model->get_translate($sLang, array('currency_id' => $nCurrencyId), TRUE , 1);
			if ( empty($aCurrencyTranslateData) )
			{
				$aCurrencyTranslateData = $aCurrencyDefaultTranslateData;
			}
			$this->smarty->assign('aCurrencyTranslateData', $aCurrencyTranslateData);
			$this->smarty->assign('aCurrencyDefaultTranslateData', $aCurrencyDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/currencies');
			$this->title(vlang('Translating a currency'));
			$this->view();
		}
		
	}
	
	
	/**
	 * Список всех категорий в системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function currencies($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~', $nOffset=0)
	{
		$this->load->model('currencies_model');
		
		$this->javascript('adm/currencies/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'com_currency_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_currencies.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_currency_id', LANGUAGE_ABBR_DEFAULT . '_currencies.title');
		$aOrdersName = array('ID', 'ISO');
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
		
		if( $sFilter == '~' )
		{
			if ( empty($nPerPage) )
			{
				$aCurrencies = $this->currencies_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCurrencies = $this->currencies_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->currencies_model->get_count_adm_list();
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
				$aCurrencies = $this->currencies_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCurrencies = $this->currencies_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->currencies_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('sEditUrl', $this->router->class . '/currency_edit/');
		$this->smarty->assign('aCurrencies', $aCurrencies);
		$this->title(vlang('The currencies'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию статьи 
	 */
	public function currency_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('currencies_model');
				$aResult = $this->currencies_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	//======================END OF CURRENCY================================//
	
	
	
	//=======================SPA======================================//
	
	
	
	/**
	 * Редактирование 
	 */
	public function spa_edit($nSpaId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nSpaId) )
		{
			redirect($this->router->class . '/spas', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/spas', 'refresh');
		}
		$this->load->model('spas_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Spa', 'required|xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aSpaData = array(
				'spa_id' => $nSpaId,
				'title' => $this->input->post('title'),
			);
			$aSpaTranslateData = $this->spas_model->get_translate($sLang, array('spa_id' => $nSpaId), TRUE , 1);
			if ( ! empty($aSpaTranslateData) )
			{
				$this->spas_model->save_translate($sLang, $aSpaData, $nSpaId);
			}
			else
			{
				$this->spas_model->save_translate($sLang, $aSpaData);
			}
			redirect($this->router->class . '/spas', 'refresh');
		}
		else
		{
			$aSpaDefaultTranslateData = $this->spas_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('spa_id' => $nSpaId), TRUE , 1);
			$aSpaTranslateData = $this->spas_model->get_translate($sLang, array('spa_id' => $nSpaId), TRUE , 1);
			if ( empty($aSpaTranslateData) )
			{
				$aSpaTranslateData = $aSpaDefaultTranslateData;
			}
			$this->smarty->assign('aSpaTranslateData', $aSpaTranslateData);
			$this->smarty->assign('aSpaDefaultTranslateData', $aSpaDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/spas');
			$this->title(vlang('Translating a spa'));
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
	public function spas($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('spas_model');
		
		$this->javascript('adm/spas/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'spas.com_spa_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_spas.title' => '',
			LANGUAGE_ABBR_DEFAULT . '_cities.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('spas.com_spa_id', LANGUAGE_ABBR_DEFAULT . '_spas.title', LANGUAGE_ABBR_DEFAULT . '_city_title',);
		$aOrdersName = array('ID', 'Title', 'City');
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
				$aSpas = $this->spas_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aSpas = $this->spas_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->spas_model->get_count_adm_list();
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
				$aSpas = $this->spas_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aSpas = $this->spas_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->spas_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aSpas', $aSpas);
		$this->title(vlang('The spas'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
	 */
	public function spa_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('spas_model');
				$aResult = $this->spas_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	
	//========================END OF SPA===========================//
	
	//=======================ILLNESE======================================//
	
	
	
	/**
	 * Редактирование 
	 */
	public function illnese_edit($nIllneseId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nIllneseId) )
		{
			redirect($this->router->class . '/illneses', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/illneses', 'refresh');
		}
		$this->load->model('illneses_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Illnese'), 'required|xss_clean');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aIllneseData = array(
				'illnese_id' => $nIllneseId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$aIllneseTranslateData = $this->illneses_model->get_translate($sLang, array('illnese_id' => $nIllneseId), TRUE , 1);
			if ( ! empty($aIllneseTranslateData) )
			{
				$this->illneses_model->save_translate($sLang, $aIllneseData, $nIllneseId);
			}
			else
			{
				$this->illneses_model->save_translate($sLang, $aIllneseData);
			}
			redirect($this->router->class . '/illneses', 'refresh');
		}
		else
		{
			$aIllneseDefaultTranslateData = $this->illneses_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('illnese_id' => $nIllneseId), TRUE , 1);
			$aIllneseTranslateData = $this->illneses_model->get_translate($sLang, array('illnese_id' => $nIllneseId), TRUE , 1);
			if ( empty($aIllneseTranslateData) )
			{
				$aIllneseTranslateData = $aIllneseDefaultTranslateData;
			}
			$this->smarty->assign('aIllneseTranslateData', $aIllneseTranslateData);
			$this->smarty->assign('aIllneseDefaultTranslateData', $aIllneseDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/illneses');
			$this->title(vlang('Translating a illnese'));
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
	public function illneses($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('illneses_model');
		
		$this->javascript('adm/illneses/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'illneses.com_illnese_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_illneses.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('illneses.com_illnese_id', LANGUAGE_ABBR_DEFAULT . '_illneses.title',);
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
				$aIllneses = $this->illneses_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aIllneses = $this->illneses_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->illneses_model->get_count_adm_list();
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
				$aIllneses = $this->illneses_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aIllneses = $this->illneses_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->illneses_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aIllneses', $aIllneses);
		$this->title(vlang('The illneses'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
	 */
	public function illnese_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('illneses_model');
				$aResult = $this->illneses_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	
	//========================END OF ILLNESE===========================//
	
	

	//=======================ESSENTIAL_INFO======================================//
	
	
	
	/**
	 * Редактирование 
	 */
	public function essential_info_edit($nEssential_infoId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nEssential_infoId) )
		{
			redirect($this->router->class . '/essential_infos', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/essential_infos', 'refresh');
		}
		$this->load->model('essential_infos_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Essential_info'), 'required|xss_clean');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aEssential_infoData = array(
				'essential_info_id' => $nEssential_infoId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$aEssential_infoTranslateData = $this->essential_infos_model->get_translate($sLang, array('essential_info_id' => $nEssential_infoId), TRUE , 1);
			if ( ! empty($aEssential_infoTranslateData) )
			{
				$this->essential_infos_model->save_translate($sLang, $aEssential_infoData, $nEssential_infoId);
			}
			else
			{
				$this->essential_infos_model->save_translate($sLang, $aEssential_infoData);
			}
			redirect($this->router->class . '/essential_infos', 'refresh');
		}
		else
		{
			$aEssential_infoDefaultTranslateData = $this->essential_infos_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('essential_info_id' => $nEssential_infoId), TRUE , 1);
			$aEssential_infoTranslateData = $this->essential_infos_model->get_translate($sLang, array('essential_info_id' => $nEssential_infoId), TRUE , 1);
			if ( empty($aEssential_infoTranslateData) )
			{
				$aEssential_infoTranslateData = $aEssential_infoDefaultTranslateData;
			}
			$this->smarty->assign('aEssential_infoTranslateData', $aEssential_infoTranslateData);
			$this->smarty->assign('aEssential_infoDefaultTranslateData', $aEssential_infoDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/essential_infos');
			$this->title(vlang('Translating a essential info'));
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
	public function essential_infos($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('essential_infos_model');
		
		$this->javascript('adm/essential_infos/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'essential_infos.com_essential_info_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_essential_infos.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('essential_infos.com_essential_info_id', LANGUAGE_ABBR_DEFAULT . '_essential_infos.title',);
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
				$aEssential_infos = $this->essential_infos_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aEssential_infos = $this->essential_infos_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->essential_infos_model->get_count_adm_list();
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
				$aEssential_infos = $this->essential_infos_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aEssential_infos = $this->essential_infos_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->essential_infos_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aEssential_infos', $aEssential_infos);
		$this->title(vlang('The essential info'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
	 */
	public function essential_info_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('essential_infos_model');
				$aResult = $this->essential_infos_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	
	//========================END OF ESSENTIAL_INFO===========================//
	
	
		
	//=======================MEDICAL_TREATMENT======================================//
	
	
	
	/**
	 * Редактирование 
	 */
	public function medical_treatment_edit($nMedical_treatmentId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nMedical_treatmentId) )
		{
			redirect($this->router->class . '/medical_treatments', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/medical_treatments', 'refresh');
		}
		$this->load->model('medical_treatments_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Medical_treatment'), 'required|xss_clean');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aMedical_treatmentData = array(
				'medical_treatment_id' => $nMedical_treatmentId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$aMedical_treatmentTranslateData = $this->medical_treatments_model->get_translate($sLang, array('medical_treatment_id' => $nMedical_treatmentId), TRUE , 1);
			if ( ! empty($aMedical_treatmentTranslateData) )
			{
				$this->medical_treatments_model->save_translate($sLang, $aMedical_treatmentData, $nMedical_treatmentId);
			}
			else
			{
				$this->medical_treatments_model->save_translate($sLang, $aMedical_treatmentData);
			}
			redirect($this->router->class . '/medical_treatments', 'refresh');
		}
		else
		{
			$aMedical_treatmentDefaultTranslateData = $this->medical_treatments_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('medical_treatment_id' => $nMedical_treatmentId), TRUE , 1);
			$aMedical_treatmentTranslateData = $this->medical_treatments_model->get_translate($sLang, array('medical_treatment_id' => $nMedical_treatmentId), TRUE , 1);
			if ( empty($aMedical_treatmentTranslateData) )
			{
				$aMedical_treatmentTranslateData = $aMedical_treatmentDefaultTranslateData;
			}
			$this->smarty->assign('aMedical_treatmentTranslateData', $aMedical_treatmentTranslateData);
			$this->smarty->assign('aMedical_treatmentDefaultTranslateData', $aMedical_treatmentDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/medical_treatments');
			$this->title(vlang('Translating a medical treatment'));
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
	public function medical_treatments($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('medical_treatments_model');
		
		$this->javascript('adm/medical_treatments/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'medical_treatments.com_medical_treatment_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_medical_treatments.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('medical_treatments.com_medical_treatment_id', LANGUAGE_ABBR_DEFAULT . '_medical_treatments.title',);
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
				$aMedical_treatments = $this->medical_treatments_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aMedical_treatments = $this->medical_treatments_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->medical_treatments_model->get_count_adm_list();
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
				$aMedical_treatments = $this->medical_treatments_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aMedical_treatments = $this->medical_treatments_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->medical_treatments_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aMedical_treatments', $aMedical_treatments);
		$this->title(vlang('The medical treatments'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
	 */
	public function medical_treatment_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('medical_treatments_model');
				$aResult = $this->medical_treatments_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	
	//========================END OF MEDICAL_TREATMENT===========================//
	
	
	//=======================FACILITY======================================//
	
	
	
	/**
	 * Редактирование 
	 */
	public function facility_edit($nFacilityId = 0, $sLang = '')
	{
		if ( empty($sLang) || empty($nFacilityId) )
		{
			redirect($this->router->class . '/facilities', 'refresh');
		}
		if ( !in_array($sLang, $this->aLangPermissions) )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Access denied'));
			redirect($this->router->class . '/facilities', 'refresh');
		}
		$this->load->model('facilities_model');		
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Facility'), 'required|xss_clean');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aFacilityData = array(
				'facility_id' => $nFacilityId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$aFacilityTranslateData = $this->facilities_model->get_translate($sLang, array('facility_id' => $nFacilityId), TRUE , 1);
			if ( ! empty($aFacilityTranslateData) )
			{
				$this->facilities_model->save_translate($sLang, $aFacilityData, $nFacilityId);
			}
			else
			{
				$this->facilities_model->save_translate($sLang, $aFacilityData);
			}
			redirect($this->router->class . '/facilities', 'refresh');
		}
		else
		{
			$aFacilityDefaultTranslateData = $this->facilities_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('facility_id' => $nFacilityId), TRUE , 1);
			$aFacilityTranslateData = $this->facilities_model->get_translate($sLang, array('facility_id' => $nFacilityId), TRUE , 1);
			if ( empty($aFacilityTranslateData) )
			{
				$aFacilityTranslateData = $aFacilityDefaultTranslateData;
			}
			$this->smarty->assign('aFacilityTranslateData', $aFacilityTranslateData);
			$this->smarty->assign('aFacilityDefaultTranslateData', $aFacilityDefaultTranslateData);
			$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('sLang', $sLang);
			$this->smarty->assign('lang_uri_abbr', $this->config->item('lang_uri_abbr'));
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/facilities');
			$this->title(vlang('Translating a facility'));
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
	public function facilities($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('facilities_model');
		
		$this->javascript('adm/facilities/filter_translate.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'facilities.com_facility_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_facilities.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('facilities.com_facility_id', LANGUAGE_ABBR_DEFAULT . '_facilities.title',);
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
				$aFacilities = $this->facilities_model->get_adm_list($this->aLangPermissions, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aFacilities = $this->facilities_model->get_adm_list($this->aLangPermissions, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->facilities_model->get_count_adm_list();
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
				$aFacilities = $this->facilities_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aFacilities = $this->facilities_model->get_adm_list($this->aLangPermissions, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->facilities_model->get_count_adm_list($aFilters);
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
		$this->smarty->assign('aFacilities', $aFacilities);
		$this->title(vlang('The facilities'));
		$this->view();
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию
	 */
	public function facility_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('facilities_model');
				$aResult = $this->facilities_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	
	//========================END OF FACILITY===========================//
	
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
			$this->javascript('tiny_mce/jquery.tinymce.js');
			$this->javascript('adm/articles/tinymce.init.js');
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
	public function programmes($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~~', $nOffset=0)
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
			LANGUAGE_ABBR_DEFAULT . '_cities.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('programmes.com_programme_id', LANGUAGE_ABBR_DEFAULT . '_programmes.title', LANGUAGE_ABBR_DEFAULT . '_category_title', LANGUAGE_ABBR_DEFAULT . '_spa_title', LANGUAGE_ABBR_DEFAULT . '_city_title');
		$aOrdersName = array(vlang('ID'), vlang('Title'), vlang('Category'), vlang('Hotel spa'), vlang('City'));
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
		
		if( $sFilter == '~~~~' )
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

/* End of file adm_trans.php */
/* Location: ./application/controllers/adm_trans.php */