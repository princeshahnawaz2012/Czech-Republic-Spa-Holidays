<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_spas extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign('SPA_ALL', SPA_ALL);
		$this->smarty->assign('SPA_ACTIVE', SPA_ACTIVE);
		$this->smarty->assign('SPA_INACTIVE', SPA_INACTIVE);
		
		$this->smarty->assign('OFFSEASON_CALCULATION_ALL', OFFSEASON_CALCULATION_ALL);
		$this->smarty->assign('OFFSEASON_CALCULATION_BY_FIRST_SEASON', OFFSEASON_CALCULATION_BY_FIRST_SEASON);
		$this->smarty->assign('OFFSEASON_CALCULATION_BY_SECOND_SEASON', OFFSEASON_CALCULATION_BY_SECOND_SEASON);
		$this->smarty->assign('OFFSEASON_CALCULATION_BY_BOTH_SEASON', OFFSEASON_CALCULATION_BY_BOTH_SEASON);
	}
	
	public function index()
	{
		redirect($this->router->class . '/spas');
	}
	
	
	/**
	 * Готель, форма добавления и обработчик формы(также AJAX) 
	 */
	public function spa_add()
	{
		$this->load->model('spas_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Spa', 'required|xss_clean');
		$this->form_validation->set_rules('com_city_id', 'City', 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('com_contacts', vlang('Contacts'), 'required|xss_clean');
		$this->form_validation->set_rules('com_midseason_pay_type', vlang('Calculation of prices in offseason'), 'required|xss_clean');
		$this->form_validation->set_rules('com_reservation_email', vlang("Reponsible's e-mail for reservation"), 'required|xss_clean|valid_email|max_length[512]');
		$this->form_validation->set_rules('com_reservation_name', vlang("Reponsible's name for reservation"), 'required|xss_clean|max_length[256]');
		$this->form_validation->set_rules('com_reservation_email2', vlang("2nd reponsible's e-mail for reservation"), 'xss_clean|valid_email|max_length[512]');
		$this->form_validation->set_rules('com_reservation_name2', vlang("2nd reponsible's name for reservation"), 'xss_clean|max_length[256]');
		$this->form_validation->set_rules('com_essential_info_id', vlang('Essential info'), 'xss_clean');
		$this->form_validation->set_rules('com_medical_treatment_id', vlang('Medical treatments'), 'xss_clean');
		$this->form_validation->set_rules('com_facility_id', vlang('Facilities'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aSpaData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_city_id' => $this->input->post('com_city_id'),
				'com_spa_id' => NULL,
				'com_contacts' => $this->input->post('com_contacts'),
				'com_midseason_pay_type' => $this->input->post('com_midseason_pay_type'),
				'com_reservation_email' => $this->input->post('com_reservation_email'),
				'com_reservation_name' => $this->input->post('com_reservation_name'),
				'com_reservation_email2' => $this->input->post('com_reservation_email2'),
				'com_reservation_name2' => $this->input->post('com_reservation_name2'),
			);
			$nSpaId = $this->spas_model->save($aSpaData);
			$aSpaTranslateData = array(
				'spa_id' => $nSpaId,
				'title' => $this->input->post('title'),
			);
			$this->spas_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aSpaTranslateData);
			$this->spas_model->save_essential_infos($this->input->post('com_essential_info_id'), $nSpaId);
			$this->spas_model->save_medical_treatments($this->input->post('com_medical_treatment_id'), $nSpaId);
			$this->spas_model->save_facilities($this->input->post('com_facility_id'), $nSpaId);
			if ( $this->isAjaxRequest('POST') )
			{
				$aSpaData = array_merge($aSpaData, $aSpaTranslateData);
				$this->AjaxResponse($aSpaData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/spas', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aSpaData = array(
					'spa_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aSpaData, TRUE);
			}
			$this->load->model('cities_model');
			$this->load->model('essential_infos_model');
			$this->load->model('medical_treatments_model');
			$this->load->model('facilities_model');
			$aCities = $this->cities_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aCities', $aCities);
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_city_id'), 'com_city_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_contacts'), 'com_contacts');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_midseason_pay_type'), 'com_midseason_pay_type');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_reservation_email'), 'com_reservation_email');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_reservation_name'), 'com_reservation_name');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_reservation_email2'), 'com_reservation_email2');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_reservation_name2'), 'com_reservation_name2');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_essential_info_id'), 'com_essential_info_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_medical_treatment_id'), 'com_medical_treatment_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_facility_id'), 'com_facility_id');
			
			$aEssential_infosData = $this->essential_infos_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order' => 'asc'));
			$aEssential_infosIds = array();
			foreach ($aEssential_infosData as $aEssential_infosItem)
			{
				$aEssential_infosIds[$aEssential_infosItem['com_essential_info_id']] = $aEssential_infosItem['title'];
			}
			
			
			$aMedical_treatmentsData = $this->medical_treatments_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order' => 'asc'));
			$aMedical_treatmentsIds = array();
			foreach ($aMedical_treatmentsData as $aMedical_treatmentsItem)
			{
				$aMedical_treatmentsIds[$aMedical_treatmentsItem['com_medical_treatment_id']] = $aMedical_treatmentsItem['title'];
			}
			
			
			$aFacilitiesData = $this->facilities_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order' => 'asc'));
			$aFacilitiesIds = array();
			foreach ($aFacilitiesData as $aFacilitiesItem)
			{
				$aFacilitiesIds[$aFacilitiesItem['com_facility_id']] = $aFacilitiesItem['title'];
			}
			
			$this->smarty->assign('aEssential_infosIds', $aEssential_infosIds);
			$this->smarty->assign('aMedical_treatmentsIds', $aMedical_treatmentsIds);
			$this->smarty->assign('aFacilitiesIds', $aFacilitiesIds);
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/spas');
			
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
			$this->title(vlang('Adding a spa'));
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
	public function spas($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~', $nOffset=0)
	{
		$this->load->model('spas_model');
		
		$this->javascript('adm/spas/filter.js');
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
			'spas.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_spa_id', LANGUAGE_ABBR_DEFAULT . '_title', LANGUAGE_ABBR_DEFAULT . '_city_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Spa', 'City', 'Status', 'Order');
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
				$aSpas = $this->spas_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aSpas = $this->spas_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->spas_model->get_count();
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
				$aSpas = $this->spas_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aSpas = $this->spas_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->spas_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aSpas', $aSpas);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/spa_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/spa_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/spa_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/spa_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/spa_delete/');
		
		$nCountAllSpas = $this->spas_model->get_count();
		$nCountInactiveSpas = $this->spas_model->get_count(array('com_active' => SPA_INACTIVE));
		$this->smarty->assign('nCountAllSpas', $nCountAllSpas);
		$this->smarty->assign('nCountInactiveSpas', $nCountInactiveSpas);
		$this->smarty->assign('nCountActiveSpas', $nCountAllSpas - $nCountInactiveSpas);
		$this->title(vlang('The Hotels Spa'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nSpaId 
	 */
	public function spa_edit($nSpaId = FALSE)
	{
		if ( ! $nSpaId )
		{
			redirect($this->router->class . '/spas', 'refresh');
		}
		$this->load->model('spas_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_city_id', vlang('City'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('com_contacts', vlang('Contacts'), 'required|xss_clean');
		$this->form_validation->set_rules('com_midseason_pay_type', vlang('Calculation of prices in offseason'), 'required|xss_clean');
		$this->form_validation->set_rules('com_reservation_email', vlang("Reponsible's e-mail for reservation"), 'required|xss_clean|valid_email|max_length[512]');
		$this->form_validation->set_rules('com_reservation_name', vlang("Reponsible's name for reservation"), 'required|xss_clean|max_length[256]');
		$this->form_validation->set_rules('com_reservation_email2', vlang("2nd reponsible's e-mail for reservation"), 'xss_clean|valid_email|max_length[512]');
		$this->form_validation->set_rules('com_reservation_name2', vlang("2nd reponsible's name for reservation"), 'xss_clean|max_length[256]');
		$this->form_validation->set_rules('com_essential_info_id', vlang('Essential info'), 'xss_clean');
		$this->form_validation->set_rules('com_medical_treatment_id', vlang('Medical treatments'), 'xss_clean');
		$this->form_validation->set_rules('com_facility_id', vlang('Facilities'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aSpaData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => intval($this->input->post('com_order')),
				'com_city_id' => $this->input->post('com_city_id'),
				'com_contacts' => $this->input->post('com_contacts'),
				'com_midseason_pay_type' => $this->input->post('com_midseason_pay_type'),
				'com_reservation_email' => $this->input->post('com_reservation_email'),
				'com_reservation_name' => $this->input->post('com_reservation_name'),
				'com_reservation_email2' => $this->input->post('com_reservation_email2'),
				'com_reservation_name2' => $this->input->post('com_reservation_name2'),
			);
			$this->spas_model->save($aSpaData, $nSpaId);
			
			$this->load->model('programmes_model');
			$aProgrammeData = array(
				'com_city_id' => $aSpaData['com_city_id'],
			);
			$aProgrammeFilters = array(
				'com_spa_id' => $nSpaId,
			);
			$this->programmes_model->edit($aProgrammeData, $aProgrammeFilters);
			
			$aSpaTranslateData = array(
				'title' => $this->input->post('title'),
			);
			$this->spas_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aSpaTranslateData, $nSpaId);
			$this->spas_model->save_essential_infos($this->input->post('com_essential_info_id'), $nSpaId);
			$this->spas_model->save_medical_treatments($this->input->post('com_medical_treatment_id'), $nSpaId);
			$this->spas_model->save_facilities($this->input->post('com_facility_id'), $nSpaId);
			if ( $this->isAjaxRequest() )
			{
				$this->AjaxResponse(intval($nSpaId), FALSE);
			}
			else
			{
				redirect($this->router->class . '/spas', 'refresh');
			}
		}
		else
		{
			$this->load->model('cities_model');
			$this->load->model('essential_infos_model');
			$this->load->model('medical_treatments_model');
			$this->load->model('facilities_model');
			$aCities = $this->cities_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order'=>'asc'));
			$this->smarty->assign('aCities', $aCities);
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_city_id'), 'com_city_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_contacts'), 'com_contacts');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_midseason_pay_type'), 'com_midseason_pay_type');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_reservation_email'), 'com_reservation_email');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_reservation_name'), 'com_reservation_name');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_reservation_email2'), 'com_reservation_email2');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_reservation_name2'), 'com_reservation_name2');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_essential_info_id'), 'com_essential_info_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_medical_treatment_id'), 'com_medical_treatment_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_facility_id'), 'com_facility_id');
			$aSpaData = $this->spas_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('spa_id' => $nSpaId), TRUE);
			
			
			$aEssential_infosData = $this->essential_infos_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order' => 'asc'));
			$aEssential_infosIds = array();
			foreach ($aEssential_infosData as $aEssential_infosItem)
			{
				$aEssential_infosIds[$aEssential_infosItem['com_essential_info_id']] = $aEssential_infosItem['title'];
			}
			
			
			$aMedical_treatmentsData = $this->medical_treatments_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order' => 'asc'));
			$aMedical_treatmentsIds = array();
			foreach ($aMedical_treatmentsData as $aMedical_treatmentsItem)
			{
				$aMedical_treatmentsIds[$aMedical_treatmentsItem['com_medical_treatment_id']] = $aMedical_treatmentsItem['title'];
			}
			
			
			$aFacilitiesData = $this->facilities_model->get_joined(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, NULL, array('com_order' => 'asc'));
			$aFacilitiesIds = array();
			foreach ($aFacilitiesData as $aFacilitiesItem)
			{
				$aFacilitiesIds[$aFacilitiesItem['com_facility_id']] = $aFacilitiesItem['title'];
			}
			
			
			$aSpaEssential_infosData = $this->spas_model->get_essential_infos(array('com_spa_id' => $aSpaData['com_spa_id']));
			$aSpaEssential_infosIds = array();
			foreach ($aSpaEssential_infosData as $aSpaEssential_infosItem)
			{
				$aSpaEssential_infosIds[] = $aSpaEssential_infosItem['com_essential_info_id'];
			}
			
			
			$aSpaMedical_treatmentsData = $this->spas_model->get_medical_treatments(array('com_spa_id' => $aSpaData['com_spa_id']));
			$aSpaMedical_treatmentsIds = array();
			foreach ($aSpaMedical_treatmentsData as $aSpaMedical_treatmentsItem)
			{
				$aSpaMedical_treatmentsIds[] = $aSpaMedical_treatmentsItem['com_medical_treatment_id'];
			}
			
			
			$aSpaFacilitiesData = $this->spas_model->get_facilities(array('com_spa_id' => $aSpaData['com_spa_id']));
			$aSpaFacilitiesIds = array();
			foreach ($aSpaFacilitiesData as $aSpaFacilitiesItem)
			{
				$aSpaFacilitiesIds[] = $aSpaFacilitiesItem['com_facility_id'];
			}
			
			$this->smarty->assign('aEssential_infosIds', $aEssential_infosIds);
			$this->smarty->assign('aMedical_treatmentsIds', $aMedical_treatmentsIds);
			$this->smarty->assign('aFacilitiesIds', $aFacilitiesIds);
			$this->smarty->assign('aSpaEssential_infosIds', $aSpaEssential_infosIds);
			$this->smarty->assign('aSpaMedical_treatmentsIds', $aSpaMedical_treatmentsIds);
			$this->smarty->assign('aSpaFacilitiesIds', $aSpaFacilitiesIds);
			$this->smarty->assign('aSpaData', $aSpaData);			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/spas');
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
			$this->title(vlang('Editing a spa'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nSpaId 
	 */
	public function spa_activate($nSpaId = FALSE)
	{
		if ( ! $nSpaId )
		{
			redirect($this->router->class . '/spas');
		}
		$this->load->model('spas_model');
		$aData = array(
			'com_active' => SPA_ACTIVE
		);
		if ( $nSpaId )
		{
			$this->spas_model->save($aData, $nSpaId);
			redirect($this->router->class . '/spas');
		}
		else
		{
			$aSpaId = $this->input->post('aSpaId');
			if ( $aSpaId )
			{
				echo $this->spas_model->save($aData, $aSpaId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nSpaId 
	 */
	public function spa_deactivate($nSpaId = FALSE)
	{
		if ( ! $nSpaId )
		{
			redirect($this->router->class . '/spas');
		}
		$this->load->model('spas_model');
		$aData = array(
			'com_active' => SPA_INACTIVE
		);
		if ( $nSpaId )
		{
			$this->spas_model->save($aData, $nSpaId);
			redirect($this->router->class . '/spas');
		}
		else
		{
			$aSpaId = $this->input->post('aSpaId');
			if ( $aSpaId )
			{
				echo $this->spas_model->save($aData, $aSpaId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nSpaId 
	 */
	public function spa_delete($nSpaId = FALSE)
	{
		if ( ! $nSpaId )
		{
			redirect($this->router->class . '/spas', 'refresh');
		}
		$this->load->model('spas_model');
		$this->spas_model->delete($nSpaId);
		redirect($this->router->class . '/spas', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function spa_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nSpaId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nSpaId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('spas_model');
				if ( $this->spas_model->get_count(array('com_spa_id' => $nSpaId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->spas_model->save(array('com_order' => $nOrder), $nSpaId);
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
	
	
	/**
	 * Город отеля
	 */
	public function get_spa_city()
	{
		$aResponse = array('id' => 0, 'title' => vlang('City was not found!'));
		if ( $this->isAjaxRequest() )
		{
			$nSpaId = $this->input->post('id');			
			if ($nSpaId !== FALSE)
			{
				$this->load->model('spas_model');
				$aSpaData = $this->spas_model->get(array('com_spa_id' => $nSpaId,), TRUE, 1);
				if ( empty($aSpaData['com_city_id']) )
				{
					$this->AjaxResponse($aResponse, TRUE);
				}
				$this->load->model('cities_model');
				$aCityData = $this->cities_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('city_id' => $aSpaData['com_city_id']), TRUE, 1);
				if ( empty($aCityData['title']) )
				{
					$this->AjaxResponse($aResponse, TRUE);
				}
				$aResponse = array(
					'id' => $aSpaData['com_city_id'],
					'title' => $aCityData['title'],
				);
				$this->AjaxResponse($aResponse, TRUE);
			}
		}
		$this->AjaxResponse($aResponse, TRUE);
	}
	
	
}

/* End of file adm_spas.php */
/* Location: ./application/controllers/adm_spas.php */