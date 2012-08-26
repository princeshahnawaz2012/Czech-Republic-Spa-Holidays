<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_facilities extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign('FACILITY_ALL', FACILITY_ALL);
		$this->smarty->assign('FACILITY_ACTIVE', FACILITY_ACTIVE);
		$this->smarty->assign('FACILITY_INACTIVE', FACILITY_INACTIVE);
	}
	
	public function index()
	{
		redirect($this->router->class . '/facilities');
	}
	
	
	/**
	 * Готель, форма добавления и обработчик формы(также AJAX) 
	 */
	public function facility_add()
	{
		$this->load->model('facilities_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aFacilityData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_facility_id' => NULL,
			);
			$nFacilityId = $this->facilities_model->save($aFacilityData);
			$aFacilityData = array(
				'facility_id' => $nFacilityId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$this->facilities_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aFacilityData);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aFacilityData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/facilities', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aFacilityData = array(
					'facility_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aFacilityData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/facilities');
			
			$this->title(vlang('Adding a facility'));
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
	public function facilities($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('facilities_model');
		
		$this->javascript('adm/facilities/filter.js');
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
			'facilities.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_facility_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Facility', 'Status', 'Order');
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
				$aFacilities = $this->facilities_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aFacilities = $this->facilities_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->facilities_model->get_count();
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
				$aFacilities = $this->facilities_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aFacilities = $this->facilities_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->facilities_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aFacilities', $aFacilities);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/facility_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/facility_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/facility_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/facility_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/facility_delete/');
		
		$nCountAllFacilities = $this->facilities_model->get_count();
		$nCountInactiveFacilities = $this->facilities_model->get_count(array('com_active' => FACILITY_INACTIVE));
		$this->smarty->assign('nCountAllFacilities', $nCountAllFacilities);
		$this->smarty->assign('nCountInactiveFacilities', $nCountInactiveFacilities);
		$this->smarty->assign('nCountActiveFacilities', $nCountAllFacilities - $nCountInactiveFacilities);
		$this->title(vlang('The facilities'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nFacilityId 
	 */
	public function facility_edit($nFacilityId = FALSE)
	{
		if ( ! $nFacilityId )
		{
			redirect($this->router->class . '/facilities', 'refresh');
		}
		$this->load->model('facilities_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aFacilityData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => intval($this->input->post('com_order')),
			);
			$this->facilities_model->save($aFacilityData, $nFacilityId);
			
			$aFacilityData = array(
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$this->facilities_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aFacilityData, $nFacilityId);
			if ( $this->isAjaxRequest() )
			{
				$this->AjaxResponse(intval($nFacilityId), FALSE);
			}
			else
			{
				redirect($this->router->class . '/facilities', 'refresh');
			}
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$aFacilityData = $this->facilities_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('facility_id' => $nFacilityId), TRUE);
			$this->smarty->assign('aFacilityData', $aFacilityData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/facilities');
			$this->title(vlang('Editing a facility'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nFacilityId 
	 */
	public function facility_activate($nFacilityId = FALSE)
	{
		if ( ! $nFacilityId )
		{
			redirect($this->router->class . '/facilities');
		}
		$this->load->model('facilities_model');
		$aData = array(
			'com_active' => FACILITY_ACTIVE
		);
		if ( $nFacilityId )
		{
			$this->facilities_model->save($aData, $nFacilityId);
			redirect($this->router->class . '/facilities');
		}
		else
		{
			$aFacilityId = $this->input->post('aFacilityId');
			if ( $aFacilityId )
			{
				echo $this->facilities_model->save($aData, $aFacilityId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nFacilityId 
	 */
	public function facility_deactivate($nFacilityId = FALSE)
	{
		if ( ! $nFacilityId )
		{
			redirect($this->router->class . '/facilities');
		}
		$this->load->model('facilities_model');
		$aData = array(
			'com_active' => FACILITY_INACTIVE
		);
		if ( $nFacilityId )
		{
			$this->facilities_model->save($aData, $nFacilityId);
			redirect($this->router->class . '/facilities');
		}
		else
		{
			$aFacilityId = $this->input->post('aFacilityId');
			if ( $aFacilityId )
			{
				echo $this->facilities_model->save($aData, $aFacilityId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nFacilityId 
	 */
	public function facility_delete($nFacilityId = FALSE)
	{
		if ( ! $nFacilityId )
		{
			redirect($this->router->class . '/facilities', 'refresh');
		}
		$this->load->model('facilities_model');
		$this->facilities_model->delete($nFacilityId);
		redirect($this->router->class . '/facilities', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function facility_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nFacilityId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nFacilityId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('facilities_model');
				if ( $this->facilities_model->get_count(array('com_facility_id' => $nFacilityId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->facilities_model->save(array('com_order' => $nOrder), $nFacilityId);
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
	
	
}

/* End of file adm_facilities.php */
/* Location: ./application/controllers/adm_facilities.php */