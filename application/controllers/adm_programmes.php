<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_programmes extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign('PROGRAMME_ALL', PROGRAMME_ALL);
		$this->smarty->assign('PROGRAMME_ACTIVE', PROGRAMME_ACTIVE);
		$this->smarty->assign('PROGRAMME_INACTIVE', PROGRAMME_INACTIVE);
		
		$this->smarty->assign('PROGRAMME_IMAGE_ALL', PROGRAMME_IMAGE_ALL);
		$this->smarty->assign('PROGRAMME_IMAGE_ACTIVE', PROGRAMME_IMAGE_ACTIVE);
		$this->smarty->assign('PROGRAMME_IMAGE_INACTIVE', PROGRAMME_IMAGE_INACTIVE);
	}
	
	public function index()
	{
		redirect($this->router->class . '/programmes');
	}
	
	
	/**
	 * Готель, форма добавления и обработчик формы(также AJAX) 
	 */
	public function programme_add()
	{
		$this->load->model('programmes_model');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('com_order', vlang('Order'), 'xss_clean|integer');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_category_id', vlang('Category'), 'required|xss_clean');
		$this->form_validation->set_rules('com_spa_id', vlang('Hotel spa'), 'required|xss_clean');
		$this->form_validation->set_rules('com_city_id', vlang('City'), 'required|xss_clean');
		$this->form_validation->set_rules('com_price_from', vlang('Price from'), 'required|xss_clean|is_numeric');
		$this->form_validation->set_rules('com_currency_id', vlang('Currency'), 'required|xss_clean');
		
		$this->form_validation->set_rules('com_illnese_id', vlang('Illneses'), 'xss_clean');
		
		$this->form_validation->set_rules('title', vlang('Programme'), 'required|xss_clean');
		$this->form_validation->set_rules('description', vlang('Description'), 'xss_clean');
		$this->form_validation->set_rules('included', vlang('Included'), 'xss_clean');
		$this->form_validation->set_rules('notincluded', vlang('Not included'), 'xss_clean');
		$this->form_validation->set_rules('terms', vlang('Terms'), 'xss_clean');
		$this->form_validation->set_rules('seo_link', vlang('Seo Link'), 'xss_clean|alpha_dash');
		$this->form_validation->set_rules('metakeywords', vlang('Meta keywords'), 'xss_clean');
		$this->form_validation->set_rules('metadescription', vlang('Meta description'), 'xss_clean');
		$this->form_validation->set_rules('short_desc', vlang('Short Description'), 'xss_clean');
		
		
		if ( $this->form_validation->run() === TRUE )
		{
			$aProgrammeData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_category_id' => $this->input->post('com_category_id'),
				'com_programme_id' => NULL,
				'com_spa_id' => $this->input->post('com_spa_id'),
				'com_city_id' => $this->input->post('com_city_id'),
				'com_price_from' => $this->input->post('com_price_from'),
				'com_currency_id' => $this->input->post('com_currency_id'),
			);
			$nProgrammeId = $this->programmes_model->save($aProgrammeData);
			$aProgrammeTranslateData = array(
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
			$this->programmes_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aProgrammeTranslateData);
			$this->programmes_model->save_illneses($this->input->post('com_illnese_id'), $nProgrammeId);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aProgrammeData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/programmes', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aProgrammeData = array(
					'programme_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aProgrammeData, TRUE);
			}
			$this->load->model('illneses_model');
			$this->load->model('categories_model');
			$this->load->model('spas_model');
			$this->load->model('cities_model');
			$this->load->model('currencies_model');
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_category_id'), 'com_category_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_spa_id'), 'com_spa_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_city_id'), 'com_city_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_price_from'), 'com_price_from');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_currency_id'), 'com_currency_id');
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_illnese_id'), 'com_illnese_id');
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('description'), 'description');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('included'), 'included');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('notincluded'), 'notincluded');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('terms'), 'terms');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('seo_link'), 'seo_link');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metakeywords'), 'metakeywords');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metadescription'), 'metadescription');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			
			$aCategories = $this->categories_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aCategories', $aCategories);
			
			$aSpas = $this->spas_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aSpas', $aSpas);
			
			$aCurrencies = $this->currencies_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aCurrencies', $aCurrencies);
						
			$aIllnesesData = $this->illneses_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order' => 'asc'));
			$aIllnesesIds = array();
			foreach ($aIllnesesData as $aIllnesesItem)
			{
				$aIllnesesIds[$aIllnesesItem['com_illnese_id']] = $aIllnesesItem['title'];
			}
			
			$this->smarty->assign('aIllnesesIds', $aIllnesesIds);
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/programmes');
			
			$sProgrammePictureDir = $this->config->item('programme_pictures_dir');
			$this->smarty->assign('sProgrammePictureDir', $sProgrammePictureDir);
			
			$aTempalteVar = array(
				'max_map_width' => $this->config->item('max_city_map_width'),
				'max_flag_width' => $this->config->item('max_city_flag_width'),
				'max_emblem_width' => $this->config->item('max_city_emblem_width'),
				'max_map_height' => $this->config->item('max_city_map_height'),
				'max_flag_height' => $this->config->item('max_city_flag_height'),
				'max_emblem_height' => $this->config->item('max_city_emblem_height'),
				'programme_image_width' => $this->config->item('programme_image_width'),
				'programme_image_height' => $this->config->item('programme_image_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/programme_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/programme_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/programme_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/programme_rotate_image/',
			);
			
			$nPictureMaxWidth = $this->config->item('programme_image_width');
			$nPictureMaxHeight = $this->config->item('programme_image_height');
			$nPictureMinWidth = $this->config->item('programme_image_width');
			$nPictureMinHeight = $this->config->item('programme_image_height');
			$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
			$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
			$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
			$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);
			
			$this->template_var($aTempalteVar);
			
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			
			$this->javascript('adm/cities/city_images_add.js');
			$this->javascript('adm/regions/country_add.js');
			$this->javascript('adm/cities/region_add.js');
			$this->javascript('adm/spas/city_add.js');
			
			$this->javascript('adm/essential_infos/essential_info_add.js');
			$this->javascript('adm/medical_treatments/medical_treatment_add.js');
			$this->javascript('adm/facilities/facility_add.js');
			
			$this->javascript('adm/programmes/get_spa_city.js');
			$this->javascript('adm/programmes/programme_images_add.js');
			$this->javascript('adm/programmes/illnese_add.js');
			$this->javascript('adm/programmes/spa_add.js');
			
			$this->javascript('tiny_mce/jquery.tinymce.js');
			$this->javascript('adm/articles/tinymce.init.js');
			
			$this->title(vlang('Adding a programme'));
			$this->view();
		}
	}
	
	
	/**
	 * Список всех готелейв системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function programmes($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~~~', $nOffset=0)
	{
		$this->load->model('programmes_model');
		
		$this->javascript('adm/programmes/filter.js');
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
			'programmes.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_programme_id', LANGUAGE_ABBR_DEFAULT . '_title', LANGUAGE_ABBR_DEFAULT . '_category_title', LANGUAGE_ABBR_DEFAULT . '_spa_title', LANGUAGE_ABBR_DEFAULT . '_city_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Title', 'Category', vlang('Hotel spa'), vlang('City'), 'Status', 'Order');
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
		
		if( $sFilter == '~~~~~' )
		{
			if ( empty($nPerPage) )
			{
				$aProgrammes = $this->programmes_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aProgrammes = $this->programmes_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->programmes_model->get_count();
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
				$aProgrammes = $this->programmes_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aProgrammes = $this->programmes_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->programmes_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aProgrammes', $aProgrammes);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/programme_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/programme_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/programme_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/programme_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/programme_delete/');
		
		$this->smarty->assign('sImagesUrl', $this->router->class . '/images/');
		
		$nCountAllProgrammes = $this->programmes_model->get_count();
		$nCountInactiveProgrammes = $this->programmes_model->get_count(array('com_active' => PROGRAMME_INACTIVE));
		$this->smarty->assign('nCountAllProgrammes', $nCountAllProgrammes);
		$this->smarty->assign('nCountInactiveProgrammes', $nCountInactiveProgrammes);
		$this->smarty->assign('nCountActiveProgrammes', $nCountAllProgrammes - $nCountInactiveProgrammes);
		$this->title(vlang('The Programmes'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nProgrammeId 
	 */
	public function programme_edit($nProgrammeId = FALSE)
	{
		if ( ! $nProgrammeId )
		{
			redirect($this->router->class . '/programmes', 'refresh');
		}
		$this->load->model('programmes_model');
		$this->load->library('form_validation');
		
		
		$this->form_validation->set_rules('com_order', vlang('Order'), 'xss_clean|integer');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_category_id', vlang('Category'), 'required|xss_clean');
		$this->form_validation->set_rules('com_spa_id', vlang('Hotel spa'), 'required|xss_clean');
		$this->form_validation->set_rules('com_city_id', vlang('City'), 'required|xss_clean');
		$this->form_validation->set_rules('com_price_from', vlang('Price from'), 'required|xss_clean|is_numeric');
		$this->form_validation->set_rules('com_currency_id', vlang('Currency'), 'required|xss_clean');
		
		$this->form_validation->set_rules('com_illnese_id', vlang('Illneses'), 'xss_clean');
		
		$this->form_validation->set_rules('title', vlang('Programme'), 'required|xss_clean');
		$this->form_validation->set_rules('description', vlang('Description'), 'xss_clean');
		$this->form_validation->set_rules('included', vlang('Included'), 'xss_clean');
		$this->form_validation->set_rules('notincluded', vlang('Not included'), 'xss_clean');
		$this->form_validation->set_rules('terms', vlang('Terms'), 'xss_clean');
		$this->form_validation->set_rules('seo_link', vlang('Seo Link'), 'xss_clean|alpha_dash');
		$this->form_validation->set_rules('metakeywords', vlang('Meta keywords'), 'xss_clean');
		$this->form_validation->set_rules('metadescription', vlang('Meta description'), 'xss_clean');
		$this->form_validation->set_rules('short_desc', vlang('Short Description'), 'xss_clean');
		
		
		if ( $this->form_validation->run() === TRUE )
		{
			$aProgrammeData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_category_id' => $this->input->post('com_category_id'),
				'com_spa_id' => $this->input->post('com_spa_id'),
				'com_city_id' => $this->input->post('com_city_id'),
				'com_price_from' => $this->input->post('com_price_from'),
				'com_currency_id' => $this->input->post('com_currency_id'),
			);
			$this->programmes_model->save($aProgrammeData, $nProgrammeId);
			$aProgrammeTranslateData = array(
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
			$this->programmes_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aProgrammeTranslateData, $nProgrammeId);
			$this->programmes_model->save_illneses($this->input->post('com_illnese_id'), $nProgrammeId);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aProgrammeData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/programmes', 'refresh');
			}
		}
		else
		{
			$this->load->model('illneses_model');
			$this->load->model('categories_model');
			$this->load->model('spas_model');
			$this->load->model('cities_model');
			$this->load->model('currencies_model');
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_category_id'), 'com_category_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_spa_id'), 'com_spa_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_city_id'), 'com_city_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_price_from'), 'com_price_from');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_currency_id'), 'com_currency_id');
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_illnese_id'), 'com_illnese_id');
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('description'), 'description');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('included'), 'included');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('notincluded'), 'notincluded');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('terms'), 'terms');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('seo_link'), 'seo_link');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metakeywords'), 'metakeywords');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metadescription'), 'metadescription');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			
			$aProgrammeData = $this->programmes_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('programme_id' => $nProgrammeId), TRUE);
			
			$aCategories = $this->categories_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aCategories', $aCategories);
			
			$aSpas = $this->spas_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aSpas', $aSpas);
			
			$aSpaCity = $this->cities_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('city_id' => $aProgrammeData['com_city_id']), TRUE, 1);
			$this->smarty->assign('aSpaCity', $aSpaCity);
			
			$aCurrencies = $this->currencies_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aCurrencies', $aCurrencies);
			
			$aIllnesesData = $this->illneses_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order' => 'asc'));
			$aIllnesesIds = array();
			foreach ($aIllnesesData as $aIllnesesItem)
			{
				$aIllnesesIds[$aIllnesesItem['com_illnese_id']] = $aIllnesesItem['title'];
			}
			
			
			$aProgrammeIllnesesData = $this->programmes_model->get_illneses(array('com_programme_id' => $aProgrammeData['com_programme_id']));
			$aProgrammeIllnesesIds = array();
			foreach ($aProgrammeIllnesesData as $aProgrammeIllnesesItem)
			{
				$aProgrammeIllnesesIds[] = $aProgrammeIllnesesItem['com_illnese_id'];
			}
			
			$this->smarty->assign('aIllnesesIds', $aIllnesesIds);
			
			$this->smarty->assign('aProgrammeIllnesesIds', $aProgrammeIllnesesIds);
			$this->smarty->assign('aProgrammeData', $aProgrammeData);			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/programmes');
			
			$sProgrammePictureDir = $this->config->item('programme_pictures_dir');
			$this->smarty->assign('sProgrammePictureDir', $sProgrammePictureDir);
			
			$aTempalteVar = array(
				'max_map_width' => $this->config->item('max_city_map_width'),
				'max_flag_width' => $this->config->item('max_city_flag_width'),
				'max_emblem_width' => $this->config->item('max_city_emblem_width'),
				'max_map_height' => $this->config->item('max_city_map_height'),
				'max_flag_height' => $this->config->item('max_city_flag_height'),
				'max_emblem_height' => $this->config->item('max_city_emblem_height'),
				'programme_image_width' => $this->config->item('programme_image_width'),
				'programme_image_height' => $this->config->item('programme_image_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/programme_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/programme_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/programme_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/programme_rotate_image/',
			);
			
			$nPictureMaxWidth = $this->config->item('programme_image_width');
			$nPictureMaxHeight = $this->config->item('programme_image_height');
			$nPictureMinWidth = $this->config->item('programme_image_width');
			$nPictureMinHeight = $this->config->item('programme_image_height');
			$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
			$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
			$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
			$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);
			
			$this->template_var($aTempalteVar);
			
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			
			$this->javascript('adm/cities/city_images_add.js');
			$this->javascript('adm/regions/country_add.js');
			$this->javascript('adm/cities/region_add.js');
			$this->javascript('adm/spas/city_add.js');
			
			$this->javascript('adm/essential_infos/essential_info_add.js');
			$this->javascript('adm/medical_treatments/medical_treatment_add.js');
			$this->javascript('adm/facilities/facility_add.js');
			
			$this->javascript('adm/programmes/get_spa_city.js');
			$this->javascript('adm/programmes/programme_images_add.js');
			$this->javascript('adm/programmes/illnese_add.js');
			$this->javascript('adm/programmes/spa_add.js');
			
			$this->javascript('tiny_mce/jquery.tinymce.js');
			$this->javascript('adm/articles/tinymce.init.js');
			
//			if ($aProgrammeData['com_picture_ext'])
//			{
//				copy('./' . $this->config->item('category_pictures_dir') . $nCategoryId . '.' . $aCategoryData['com_picture_ext'], './' . $this->config->item('temp_files_dir') . $sTempNamePicture . '.' . $aCategoryData['com_picture_ext']);
//				$this->javascript('adm/categories/picture_image_manipulation_add.js');
//			}
			
			$this->title(vlang('Editing a programme'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nProgrammeId 
	 */
	public function programme_activate($nProgrammeId = FALSE)
	{
		if ( ! $nProgrammeId )
		{
			redirect($this->router->class . '/programmes');
		}
		$this->load->model('programmes_model');
		$aData = array(
			'com_active' => PROGRAMME_ACTIVE
		);
		if ( $nProgrammeId )
		{
			$this->programmes_model->save($aData, $nProgrammeId);
			redirect($this->router->class . '/programmes');
		}
		else
		{
			$aProgrammeId = $this->input->post('aProgrammeId');
			if ( $aProgrammeId )
			{
				echo $this->programmes_model->save($aData, $aProgrammeId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nProgrammeId 
	 */
	public function programme_deactivate($nProgrammeId = FALSE)
	{
		if ( ! $nProgrammeId )
		{
			redirect($this->router->class . '/programmes');
		}
		$this->load->model('programmes_model');
		$aData = array(
			'com_active' => PROGRAMME_INACTIVE
		);
		if ( $nProgrammeId )
		{
			$this->programmes_model->save($aData, $nProgrammeId);
			redirect($this->router->class . '/programmes');
		}
		else
		{
			$aProgrammeId = $this->input->post('aProgrammeId');
			if ( $aProgrammeId )
			{
				echo $this->programmes_model->save($aData, $aProgrammeId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nProgrammeId 
	 */
	public function programme_delete($nProgrammeId = FALSE)
	{
		if ( ! $nProgrammeId )
		{
			redirect($this->router->class . '/programmes', 'refresh');
		}
		$this->load->model('programmes_model');
		$this->programmes_model->unlink_files($nProgrammeId, TRUE);
		$this->programmes_model->delete($nProgrammeId);
		redirect($this->router->class . '/programmes', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function programme_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nProgrammeId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nProgrammeId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('programmes_model');
				if ( $this->programmes_model->get_count(array('com_programme_id' => $nProgrammeId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->programmes_model->save(array('com_order' => $nOrder), $nProgrammeId);
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
	
	
	
	
	public function programme_upload_image($sImageType = 'picture')
	{
		if ($this->isPostMethod())
		{
			$sTempName = $this->input->post('temp_name');
			$sExt = $this->upload_image('image_file', './' . $this->config->item('temp_files_dir'), $sTempName, TRUE);
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
	
	public function programme_crop_image($sImageType = 'picture')
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
	
	public function programme_resize_image($sImageType = 'picture')
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
	
	public function programme_rotate_image($sImageType = 'picture')
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
	
	
	public function images($nProgrammeId)
	{
		if ( ! $nProgrammeId )
		{
			redirect($this->router->class . '/programmes', 'refresh');
		}
		$this->load->model('programmes_model');
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);

		$aImages = $this->programmes_model->get_image_joined(LANGUAGE_ABBR_DEFAULT, array('com_programme_id' => $nProgrammeId), FALSE, NULL, array('com_order' => 'asc',));
		
		$aProgramme = $this->programmes_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), array('programmes.com_programme_id' => $nProgrammeId), TRUE);
		
		$this->smarty->assign('aImages', $aImages);
		$this->smarty->assign('aProgramme', $aProgramme);
		
		
		$this->smarty->assign('sAllProgrammesUrl', $this->router->class . '/programmes/');
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/image_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/image_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/image_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/image_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/image_delete/');
		
		$sProgrammePictureDir = $this->config->item('programme_pictures_dir');
		$this->smarty->assign('sProgrammePictureDir', $sProgrammePictureDir);
		
		$aTempalteVar = array(
			'programme_image_width' => $this->config->item('programme_image_width'),
			'programme_image_height' => $this->config->item('programme_image_height'),
			'temp_dir' => $this->config->item('temp_files_dir'),
			'image_upload_url_begin' => $this->router->class . '/programme_upload_image/',
			'image_crop_url_begin' => $this->router->class . '/programme_crop_image/',
			'image_resize_url_begin' => $this->router->class . '/programme_resize_image/',
			'image_rotate_url_begin' => $this->router->class . '/programme_rotate_image/',
		);

		$nPictureMaxWidth = $this->config->item('programme_image_width');
		$nPictureMaxHeight = $this->config->item('programme_image_height');
		$nPictureMinWidth = $this->config->item('programme_image_width');
		$nPictureMinHeight = $this->config->item('programme_image_height');
		$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
		$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
		$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
		$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);

		$this->template_var($aTempalteVar);
		$this->stylesheet('jquery.Jcrop.min.css');
		$this->javascript('jquery.ocupload-1.1.2.packed.js');
		$this->javascript('jquery.Jcrop.min.js');
		$this->javascript('adm/programmes/programme_images_add.js');
		$this->javascript('adm/programmes/images_filter.js');
		
		$this->title(vlang('The images of programme') . ' "' . $aProgramme[LANGUAGE_ABBR_DEFAULT . '_title'] . '"');
		$this->view();
	}
	
	public function image_add($nProgrammeId)
	{
		if ( ! $nProgrammeId )
		{
			redirect($this->router->class . '/programmes', 'refresh');
		}
		$this->load->model('programmes_model');
		$this->load->model('temp_files_model');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('com_order', vlang('Order'), 'xss_clean|integer');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		
		$this->form_validation->set_rules('title', vlang('Title of image'), 'required|xss_clean');
		$this->form_validation->set_rules('com_image_ext', vlang('Picture'), 'required|xss_clean');
		
		if ( $this->form_validation->run() === TRUE )
		{
			$aProgrammeImageData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_programme_id' => $nProgrammeId,
				'com_programme_image_id' => NULL,
			);
			$nProgrammeImageId = $this->programmes_model->save_image($aProgrammeImageData);
			
			//перемещаем изображения в папки хранения с временной папки редактирования
			$aProgrammeImageData = array(
				'com_image_ext' => $this->temp_files_model->move($this->input->post('temp_name_picture'), $this->input->post('com_image_ext'), './' . $this->config->item('programme_pictures_dir'), $nProgrammeImageId),
				
			);
			//сохраняем раширения изображений
			$this->programmes_model->save_image($aProgrammeImageData, $nProgrammeImageId);
			
			$aProgrammeImageTranslateData = array(
				'programme_image_id' => $nProgrammeImageId,
				'title' => $this->input->post('title'),
			);
			
			$this->programmes_model->save_image_translate(LANGUAGE_ABBR_DEFAULT, $aProgrammeImageTranslateData);
			if ( $this->isAjaxRequest('POST') )
			{
				$aProgrammeImageData = $this->programmes_model->get_image(array('com_programme_image_id' => $nProgrammeImageId), TRUE);
				$this->AjaxResponse($aProgrammeImageData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/images/' . $nProgrammeId, 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aProgrammeImageData = array(
					'programme_image_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aProgrammeImageData, TRUE);
			}
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_image_ext'), 'com_image_ext');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/images/' . $nProgrammeId);

			$sTempNamePicture = $this->isPostMethod() ? $this->POST('temp_name_picture', TRUE) : $this->temp_files_model->get();
			
			$this->smarty->assign('sTempNamePicture', $sTempNamePicture);
			
			$aTempalteVar = array(
				'programme_image_width' => $this->config->item('programme_image_width'),
				'programme_image_height' => $this->config->item('programme_image_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/programme_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/programme_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/programme_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/programme_rotate_image/',
			);
			
			$nPictureMaxWidth = $this->config->item('programme_image_width');
			$nPictureMaxHeight = $this->config->item('programme_image_height');
			$nPictureMinWidth = $this->config->item('programme_image_width');
			$nPictureMinHeight = $this->config->item('programme_image_height');
			$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
			$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
			$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
			$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);
			
			$this->template_var($aTempalteVar);
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			$this->javascript('adm/programmes/programme_images_add.js');
			$this->title(vlang('Adding a image of the programme'));
			$this->view();
		}
	}
	
	public function image_edit($nProgrammeImageId)
	{
		if ( ! $nProgrammeImageId )
		{
			redirect($this->router->class . '/programmes', 'refresh');
		}
		$this->load->model('programmes_model');
		$this->load->model('temp_files_model');
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('com_order', vlang('Order'), 'xss_clean|integer');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		
		$this->form_validation->set_rules('title', vlang('Title of image'), 'required|xss_clean');
		$this->form_validation->set_rules('com_image_ext', vlang('Picture'), 'required|xss_clean');
		
		
		$aImageData = $this->programmes_model->get_image_joined(LANGUAGE_ABBR_DEFAULT, array('com_programme_image_id' => $nProgrammeImageId), TRUE);
		
		if ( $this->form_validation->run() === TRUE )
		{
			$aProgrammeImageData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_image_ext' => $this->temp_files_model->move($this->input->post('temp_name_picture'), $this->input->post('com_image_ext'), './' . $this->config->item('programme_pictures_dir'), $nProgrammeImageId),
				'com_programme_id' => $aImageData['com_programme_id'],
				'com_programme_image_id' => $nProgrammeImageId,
			);
			$this->programmes_model->save_image($aProgrammeImageData, $nProgrammeImageId);
			
			$aProgrammeImageTranslateData = array(
				'programme_image_id' => $nProgrammeImageId,
				'title' => $this->input->post('title'),
			);
			
			$this->programmes_model->save_image_translate(LANGUAGE_ABBR_DEFAULT, $aProgrammeImageTranslateData, $nProgrammeImageId);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aProgrammeImageData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/images/' . $aImageData['com_programme_id'], 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aProgrammeImageData = array(
					'programme_image_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aProgrammeImageData, TRUE);
			}
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_image_ext'), 'com_image_ext');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/images/' . $aImageData['com_programme_id']);
			
			$this->smarty->assign('aImageData', $aImageData);

			$sTempNamePicture = $this->isPostMethod() ? $this->POST('temp_name_picture', TRUE) : $this->temp_files_model->get();
			
			$this->smarty->assign('sTempNamePicture', $sTempNamePicture);
			
			$this->smarty->assign('sCategoryPicturesDir', '/' . $this->config->item('category_pictures_dir'));
			
			if ($aImageData['com_image_ext'])
			{
				copy('./' . $this->config->item('programme_pictures_dir') . $nProgrammeImageId . '.' . $aImageData['com_image_ext'], './' . $this->config->item('temp_files_dir') . $sTempNamePicture . '.' . $aImageData['com_image_ext']);
				$this->javascript('adm/programmes/picture_image_manipulation_add.js');
			}
			
			$aTempalteVar = array(
				'programme_image_width' => $this->config->item('programme_image_width'),
				'programme_image_height' => $this->config->item('programme_image_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/programme_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/programme_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/programme_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/programme_rotate_image/',
			);
			
			$nPictureMaxWidth = $this->config->item('programme_image_width');
			$nPictureMaxHeight = $this->config->item('programme_image_height');
			$nPictureMinWidth = $this->config->item('programme_image_width');
			$nPictureMinHeight = $this->config->item('programme_image_height');
			$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
			$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
			$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
			$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);
			
			$this->template_var($aTempalteVar);
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			$this->javascript('adm/programmes/programme_images_add.js');
			$this->title(vlang('Adding a image of the programme'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nProgrammeImageId 
	 */
	public function image_activate($nProgrammeImageId = FALSE)
	{
		if ( ! $nProgrammeImageId )
		{
			redirect($this->router->class . '/programmes');
		}
		$this->load->model('programmes_model');
		$aData = array(
			'com_active' => PROGRAMME_IMAGE_ACTIVE
		);
		if ( $nProgrammeImageId )
		{
			$this->programmes_model->save_image($aData, $nProgrammeImageId);
			$aImage = $this->programmes_model->get_image(array('com_programme_image_id' => $nProgrammeImageId), TRUE);
			redirect($this->router->class . '/images/' . $aImage['com_programme_id']);
		}
		else
		{
			$aProgrammeImageId = $this->input->post('aProgrammeImageId');
			if ( $aProgrammeImageId )
			{
				echo $this->programmes_model->save_image($aData, $aProgrammeImageId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nProgrammeImageId 
	 */
	public function image_deactivate($nProgrammeImageId = FALSE)
	{
		if ( ! $nProgrammeImageId )
		{
			redirect($this->router->class . '/images');
		}
		$this->load->model('programmes_model');
		$aData = array(
			'com_active' => PROGRAMME_IMAGE_INACTIVE
		);
		if ( $nProgrammeImageId )
		{
			$this->programmes_model->save_image($aData, $nProgrammeImageId);
			$aImage = $this->programmes_model->get_image(array('com_programme_image_id' => $nProgrammeImageId), TRUE);
			redirect($this->router->class . '/images/' . $aImage['com_programme_id']);
		}
		else
		{
			$aProgrammeImageId = $this->input->post('aProgrammeImageId');
			if ( $aProgrammeImageId )
			{
				echo $this->programmes_model->save_image($aData, $aProgrammeImageId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nProgrammeImageId 
	 */
	public function image_delete($nProgrammeImageId = FALSE)
	{
		if ( ! $nProgrammeImageId )
		{
			redirect($this->router->class . '/programmes', 'refresh');
		}
		$this->load->model('programmes_model');
		$aImage = $this->programmes_model->get_image(array('com_programme_image_id' => $nProgrammeImageId), TRUE);
		$this->programmes_model->unlink_image($nProgrammeImageId);
		$this->programmes_model->delete_image($nProgrammeImageId);
		redirect($this->router->class . '/images/' . $aImage['com_programme_id']);
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function image_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nProgrammeImageId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nProgrammeImageId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('programmes_model');
				if ( $this->programmes_model->get_image_count(array('com_programme_image_id' => $nProgrammeImageId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->programmes_model->save_image(array('com_order' => $nOrder), $nProgrammeImageId);
				if ( $nAffectedRows )
				{
					$this->AjaxResponse('ok', FALSE);
				}
			}
		}
		$this->AjaxResponse('error', FALSE);
	}
	
	
}

/* End of file adm_programmes.php */
/* Location: ./application/controllers/adm_programmes.php */