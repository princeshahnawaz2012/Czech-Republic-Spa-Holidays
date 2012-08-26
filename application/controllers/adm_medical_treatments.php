<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_medical_treatments extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign('MEDICAL_TREATMENT_ALL', MEDICAL_TREATMENT_ALL);
		$this->smarty->assign('MEDICAL_TREATMENT_ACTIVE', MEDICAL_TREATMENT_ACTIVE);
		$this->smarty->assign('MEDICAL_TREATMENT_INACTIVE', MEDICAL_TREATMENT_INACTIVE);
	}
	
	public function index()
	{
		redirect($this->router->class . '/medical_treatments');
	}
	
	
	/**
	 * Готель, форма добавления и обработчик формы(также AJAX) 
	 */
	public function medical_treatment_add()
	{
		$this->load->model('medical_treatments_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aMedical_treatmentData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_medical_treatment_id' => NULL,
			);
			$nMedical_treatmentId = $this->medical_treatments_model->save($aMedical_treatmentData);
			$aMedical_treatmentData = array(
				'medical_treatment_id' => $nMedical_treatmentId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$this->medical_treatments_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aMedical_treatmentData);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aMedical_treatmentData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/medical_treatments', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aMedical_treatmentData = array(
					'medical_treatment_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aMedical_treatmentData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/medical_treatments');
			
			$this->title(vlang('Adding a medical treatment'));
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
	public function medical_treatments($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('medical_treatments_model');
		
		$this->javascript('adm/medical_treatments/filter.js');
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
			'medical_treatments.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_medical_treatment_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Medical treatment', 'Status', 'Order');
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
				$aMedical_treatments = $this->medical_treatments_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aMedical_treatments = $this->medical_treatments_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->medical_treatments_model->get_count();
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
				$aMedical_treatments = $this->medical_treatments_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aMedical_treatments = $this->medical_treatments_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->medical_treatments_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aMedical_treatments', $aMedical_treatments);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/medical_treatment_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/medical_treatment_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/medical_treatment_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/medical_treatment_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/medical_treatment_delete/');
		
		$nCountAllMedical_treatments = $this->medical_treatments_model->get_count();
		$nCountInactiveMedical_treatments = $this->medical_treatments_model->get_count(array('com_active' => MEDICAL_TREATMENT_INACTIVE));
		$this->smarty->assign('nCountAllMedical_treatments', $nCountAllMedical_treatments);
		$this->smarty->assign('nCountInactiveMedical_treatments', $nCountInactiveMedical_treatments);
		$this->smarty->assign('nCountActiveMedical_treatments', $nCountAllMedical_treatments - $nCountInactiveMedical_treatments);
		$this->title(vlang('The medical treatments'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nMedical_treatmentId 
	 */
	public function medical_treatment_edit($nMedical_treatmentId = FALSE)
	{
		if ( ! $nMedical_treatmentId )
		{
			redirect($this->router->class . '/medical_treatments', 'refresh');
		}
		$this->load->model('medical_treatments_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aMedical_treatmentData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => intval($this->input->post('com_order')),
			);
			$this->medical_treatments_model->save($aMedical_treatmentData, $nMedical_treatmentId);
			
			$aMedical_treatmentData = array(
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$this->medical_treatments_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aMedical_treatmentData, $nMedical_treatmentId);
			if ( $this->isAjaxRequest() )
			{
				$this->AjaxResponse(intval($nMedical_treatmentId), FALSE);
			}
			else
			{
				redirect($this->router->class . '/medical_treatments', 'refresh');
			}
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$aMedical_treatmentData = $this->medical_treatments_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('medical_treatment_id' => $nMedical_treatmentId), TRUE);
			$this->smarty->assign('aMedical_treatmentData', $aMedical_treatmentData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/medical_treatments');
			$this->title(vlang('Editing a medical treatment'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nMedical_treatmentId 
	 */
	public function medical_treatment_activate($nMedical_treatmentId = FALSE)
	{
		if ( ! $nMedical_treatmentId )
		{
			redirect($this->router->class . '/medical_treatments');
		}
		$this->load->model('medical_treatments_model');
		$aData = array(
			'com_active' => MEDICAL_TREATMENT_ACTIVE
		);
		if ( $nMedical_treatmentId )
		{
			$this->medical_treatments_model->save($aData, $nMedical_treatmentId);
			redirect($this->router->class . '/medical_treatments');
		}
		else
		{
			$aMedical_treatmentId = $this->input->post('aMedical_treatmentId');
			if ( $aMedical_treatmentId )
			{
				echo $this->medical_treatments_model->save($aData, $aMedical_treatmentId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nMedical_treatmentId 
	 */
	public function medical_treatment_deactivate($nMedical_treatmentId = FALSE)
	{
		if ( ! $nMedical_treatmentId )
		{
			redirect($this->router->class . '/medical_treatments');
		}
		$this->load->model('medical_treatments_model');
		$aData = array(
			'com_active' => MEDICAL_TREATMENT_INACTIVE
		);
		if ( $nMedical_treatmentId )
		{
			$this->medical_treatments_model->save($aData, $nMedical_treatmentId);
			redirect($this->router->class . '/medical_treatments');
		}
		else
		{
			$aMedical_treatmentId = $this->input->post('aMedical_treatmentId');
			if ( $aMedical_treatmentId )
			{
				echo $this->medical_treatments_model->save($aData, $aMedical_treatmentId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nMedical_treatmentId 
	 */
	public function medical_treatment_delete($nMedical_treatmentId = FALSE)
	{
		if ( ! $nMedical_treatmentId )
		{
			redirect($this->router->class . '/medical_treatments', 'refresh');
		}
		$this->load->model('medical_treatments_model');
		$this->medical_treatments_model->delete($nMedical_treatmentId);
		redirect($this->router->class . '/medical_treatments', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function medical_treatment_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nMedical_treatmentId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nMedical_treatmentId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('medical_treatments_model');
				if ( $this->medical_treatments_model->get_count(array('com_medical_treatment_id' => $nMedical_treatmentId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->medical_treatments_model->save(array('com_order' => $nOrder), $nMedical_treatmentId);
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
	
	
}

/* End of file adm_medical_treatments.php */
/* Location: ./application/controllers/adm_medical_treatments.php */