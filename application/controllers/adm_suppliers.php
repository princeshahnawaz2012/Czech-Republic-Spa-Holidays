<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_suppliers extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->smarty->assign('TRANSFER_CALCULATION_TYPE_COMISSION', TRANSFER_CALCULATION_TYPE_COMISSION);
		$this->smarty->assign('TRANSFER_CALCULATION_TYPE_MARK_UP', TRANSFER_CALCULATION_TYPE_MARK_UP);
	}
	
	public function index()
	{
		$this->suppliers();
	}
	
	
	//=====================SUPPLIERS==========================//
	
	
	/**
	 * Поставщик, форма добавления и обработчик формы(также AJAX) 
	 */
	public function supplier_add()
	{
		$this->load->model('suppliers_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('com_title', 'Supplier', 'required|xss_clean');
		$this->form_validation->set_rules('com_office_contacts', 'Office contacts', 'required|xss_clean');
		$this->form_validation->set_rules('com_bank_details', 'Bank', 'required|xss_clean');
		$this->form_validation->set_rules('com_accounts_contact', 'Account\'s Contacts', 'required|xss_clean');
		$this->form_validation->set_rules('com_accounts_email', 'Account\'s E-mail', 'required|xss_clean');
		$this->form_validation->set_rules('com_contact_currency_id', 'Currency', 'required|xss_clean');
		$this->form_validation->set_rules('com_transfers_calc_type', 'Trans Calc Type', 'required|xss_clean');
		$this->form_validation->set_rules('com_transfers_percent', 'Trans Persent', 'required|xss_clean|is_numeric');
		if ( $this->form_validation->run() === TRUE )
		{
			$aSupplierData = array(
				'com_supplier_id' => NULL,
				'com_office_contacts' => $this->input->post('com_office_contacts'),
				'com_title' => $this->input->post('com_title'),
				'com_bank_details' => $this->input->post('com_bank_details'),
				'com_accounts_contact' => $this->input->post('com_accounts_contact'),
				'com_accounts_email' => $this->input->post('com_accounts_email'),
				'com_contact_currency_id' => $this->input->post('com_contact_currency_id'),
				'com_transfers_calc_type' => $this->input->post('com_transfers_calc_type'),
				'com_transfers_percent' => $this->input->post('com_transfers_percent'),
			);
			$nSupplierId = $this->suppliers_model->save($aSupplierData);
			$aSupplierData['com_supplier_id'] = $nSupplierId;
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aSupplierData, TRUE);
			}
			redirect($this->router->class . '/suppliers', 'refresh');
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aSupplierData = array(
					'com_supplier_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aSupplierData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_office_contacts'), 'com_office_contacts');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_title'), 'com_title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_bank_details'), 'com_bank_details');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_accounts_contact'), 'com_accounts_contact');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_accounts_email'), 'com_accounts_email');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_contact_currency_id'), 'com_contact_currency_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_transfers_calc_type'), 'com_transfers_calc_type');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_transfers_percent'), 'com_transfers_percent');
			$this->load->model('currencies_model');
			$aCurrencies = $this->currencies_model->get_joined(LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('aCurrencies', $aCurrencies);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/suppliers');
			$this->title(vlang('Adding a supplier'));
			$this->view();
		}
	}
	
	
	/**
	 * Список всех поставщиков в системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function suppliers($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~', $nOffset=0)
	{
		$this->load->model('suppliers_model');
		
		$this->javascript('adm/suppliers/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'suppliers.com_supplier_id' => '',
			'suppliers.com_title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_supplier_id', 'com_title', 'com_contact_currency_id', 'com_transfers_calc_type', 'com_transfers_percent');
		$aOrdersName = array('ID', 'Title', 'Currency', 'Trans Calc Type', 'Trans Percent');
		$aDirects = array('up'=>'asc', 'down'=>'desc');
		$aDirectsSuffixTitle = array('up' => '&triangle;', 'down' => '&triangledown;');
		$aDirectsLinkExchanger = array('up' => 'down', 'down' => 'up');		
		$aOrderLinks = array();
		
		$aCalcTypes = array(TRANSFER_CALCULATION_TYPE_COMISSION => 'Comission', TRANSFER_CALCULATION_TYPE_MARK_UP => 'Mark Up');
		$this->smarty->assign('aCalcTypes', $aCalcTypes);
		
		$this->smarty->assign('nOrders', count($aOrders));
		
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
				$aSuppliers = $this->suppliers_model->get(FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aSuppliers = $this->suppliers_model->get(FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->suppliers_model->get_count();
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
				$aSuppliers = $this->suppliers_model->get($aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aSuppliers = $this->suppliers_model->get($aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->suppliers_model->get_count($aFilters);
		}
				
		$this->smarty->assign('aSuppliers', $aSuppliers);
				
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/supplier_add/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/supplier_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/supplier_delete/');
		
		$this->title(vlang('The suppliers'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nSupplierId 
	 */
	public function supplier_edit($nSupplierId = FALSE)
	{
		if ( ! $nSupplierId )
		{
			redirect($this->router->class . '/suppliers', 'refresh');
		}
		$this->load->model('suppliers_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('com_title', 'Supplier', 'required|xss_clean');
		$this->form_validation->set_rules('com_office_contacts', 'Office contacts', 'required|xss_clean');
		$this->form_validation->set_rules('com_bank_details', 'Bank', 'required|xss_clean');
		$this->form_validation->set_rules('com_accounts_contact', 'Account\'s Contacts', 'required|xss_clean');
		$this->form_validation->set_rules('com_accounts_email', 'Account\'s E-mail', 'required|xss_clean|valid_email');
		$this->form_validation->set_rules('com_contact_currency_id', 'Currency', 'required|xss_clean');
		$this->form_validation->set_rules('com_transfers_calc_type', 'Trans Calc Type', 'required|xss_clean');
		$this->form_validation->set_rules('com_transfers_percent', 'Trans Persent', 'required|xss_clean|is_numeric');
		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_office_contacts'), 'com_office_contacts');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_title'), 'com_title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_bank_details'), 'com_bank_details');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_accounts_contact'), 'com_accounts_contact');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_accounts_email'), 'com_accounts_email');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_contact_currency_id'), 'com_contact_currency_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_transfers_calc_type'), 'com_transfers_calc_type');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_transfers_percent'), 'com_transfers_percent');
			$this->load->model('currencies_model');
			$aCurrencies = $this->currencies_model->get_joined(LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('aCurrencies', $aCurrencies);
			$aSupplierData = $this->suppliers_model->get(array('com_supplier_id' => $nSupplierId), TRUE);
			$this->smarty->assign('aSupplierData', $aSupplierData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/suppliers');
			$this->title(vlang('Editing a supplier'));
			$this->view();
		}
		else
		{
			$aSupplierData = array(
				'com_office_contacts' => $this->input->post('com_office_contacts'),
				'com_title' => $this->input->post('com_title'),
				'com_bank_details' => $this->input->post('com_bank_details'),
				'com_accounts_contact' => $this->input->post('com_accounts_contact'),
				'com_accounts_email' => $this->input->post('com_accounts_email'),
				'com_contact_currency_id' => $this->input->post('com_contact_currency_id'),
				'com_transfers_calc_type' => $this->input->post('com_transfers_calc_type'),
				'com_transfers_percent' => $this->input->post('com_transfers_percent'),
			);
			$this->suppliers_model->save($aSupplierData, $nSupplierId);
			redirect($this->router->class . '/suppliers', 'refresh');
		}
	}

	

	/**
	 * Удаление по указанному ID
	 * @param type $nSupplierId 
	 */
	public function supplier_delete($nSupplierId = FALSE)
	{
		if ( ! $nSupplierId )
		{
			redirect($this->router->class . '/suppliers', 'refresh');
		}
		$this->load->model('suppliers_model');
		$this->suppliers_model->delete($nSupplierId);
		redirect($this->router->class . '/suppliers', 'refresh');
	}
	
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию статьи 
	 */
	public function supplier_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('suppliers_model');
				$aResult = $this->suppliers_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('com_title' => $sTitle,), FALSE, NULL, NULL, TRUE);
				$aResponse = array();
				foreach ( $aResult as $aRow )
				{
					$aResponse[] = $aRow['com_title'];
				}
				$this->AjaxResponse($aResponse, TRUE);
			}
		}
		$this->AjaxResponse('["' . vlang('Autocomplete error') . '"]', FALSE);
	}
	
	
	
	//========================END OF SUPPLIER=========================//
	

	
}

/* End of file adm_suppliers.php */
/* Location: ./application/controllers/adm_suppliers.php */