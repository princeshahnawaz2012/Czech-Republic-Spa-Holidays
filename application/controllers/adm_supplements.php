<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_supplements extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->supplements();
	}
	
	
	//=====================SUPPLIERS==========================//
	
	
	/**
	 * Поставщик, форма добавления и обработчик формы(также AJAX) 
	 */
	public function supplement_add()
	{
		$this->load->model('supplements_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('com_title', vlang('Supplement'), 'required|xss_clean');
		$this->form_validation->set_rules('com_date_from', vlang('Date from'), 'required|xss_clean');
		$this->form_validation->set_rules('com_date_till', vlang('Date till'), 'required|xss_clean');
		$this->form_validation->set_rules('com_price', vlang('Price'), 'required|xss_clean|is_numeric');
		$this->form_validation->set_rules('com_currency_id', vlang('Currency'), 'required|xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aSupplementData = array(
				'com_supplement_id' => NULL,
				'com_title' => $this->input->post('com_title'),
				'com_date_from' => $this->input->post('com_date_from'),
				'com_date_till' => $this->input->post('com_date_till'),
				'com_price' => $this->input->post('com_price'),
				'com_currency_id' => $this->input->post('com_currency_id'),
			);
			$nSupplementId = $this->supplements_model->save($aSupplementData);
			$aSupplementData['com_supplement_id'] = $nSupplementId;
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aSupplementData, TRUE);
			}
			redirect($this->router->class . '/supplements', 'refresh');
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aSupplementData = array(
					'com_supplement_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aSupplementData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_title'), 'com_title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_date_from'), 'com_date_from');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_date_till'), 'com_date_till');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_price'), 'com_price');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_currency_id'), 'com_currency_id');
			$this->load->model('currencies_model');
			$aCurrencies = $this->currencies_model->get_joined(LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('aCurrencies', $aCurrencies);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/supplements');
			$this->javascript('adm/supplements/datepicker.js');
			$this->title(vlang('Adding a supplement'));
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
	public function supplements($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~', $nOffset=0)
	{
		$this->load->model('supplements_model');
		
		$this->javascript('adm/supplements/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'supplements.com_supplement_id' => '',
			'supplements.com_title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_supplement_id', 'com_title', 'com_date_from', 'com_date_till', 'com_price', 'com_currency_id');
		$aOrdersName = array(vlang('ID'), vlang('Title'), vlang('From'), vlang('Till'), vlang('Price'), vlang('Currency'));
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
		
		if( $sFilter == '~' )
		{
			if ( empty($nPerPage) )
			{
				$aSupplements = $this->supplements_model->get(FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aSupplements = $this->supplements_model->get(FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->supplements_model->get_count();
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
				$aSupplements = $this->supplements_model->get($aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aSupplements = $this->supplements_model->get($aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->supplements_model->get_count($aFilters);
		}
				
		$this->smarty->assign('aSupplements', $aSupplements);
				
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/supplement_add/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/supplement_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/supplement_delete/');
		
		$this->title(vlang('The supplements'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nSupplementId 
	 */
	public function supplement_edit($nSupplementId = FALSE)
	{
		if ( ! $nSupplementId )
		{
			redirect($this->router->class . '/supplements', 'refresh');
		}
		$this->load->model('supplements_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('com_title', vlang('Supplement'), 'required|xss_clean');
		$this->form_validation->set_rules('com_date_from', vlang('Date from'), 'required|xss_clean');
		$this->form_validation->set_rules('com_date_till', vlang('Date till'), 'required|xss_clean');
		$this->form_validation->set_rules('com_price', vlang('Price'), 'required|xss_clean|is_numeric');
		$this->form_validation->set_rules('com_currency_id', vlang('Currency'), 'required|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_title'), 'com_title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_date_from'), 'com_date_from');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_date_till'), 'com_date_till');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_price'), 'com_price');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_currency_id'), 'com_currency_id');
			$this->load->model('currencies_model');
			$aCurrencies = $this->currencies_model->get_joined(LANGUAGE_ABBR_DEFAULT);
			$this->smarty->assign('aCurrencies', $aCurrencies);
			$aSupplementData = $this->supplements_model->get(array('com_supplement_id' => $nSupplementId), TRUE);
			$this->smarty->assign('aSupplementData', $aSupplementData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/supplements');
			$this->javascript('adm/supplements/datepicker.js');
			$this->title(vlang('Editing a supplement'));
			$this->view();
		}
		else
		{
			$aSupplementData = array(
				'com_title' => $this->input->post('com_title'),
				'com_date_from' => $this->input->post('com_date_from'),
				'com_date_till' => $this->input->post('com_date_till'),
				'com_price' => $this->input->post('com_price'),
				'com_currency_id' => $this->input->post('com_currency_id'),
			);
			$this->supplements_model->save($aSupplementData, $nSupplementId);
			redirect($this->router->class . '/supplements', 'refresh');
		}
	}

	

	/**
	 * Удаление по указанному ID
	 * @param type $nSupplementId 
	 */
	public function supplement_delete($nSupplementId = FALSE)
	{
		if ( ! $nSupplementId )
		{
			redirect($this->router->class . '/supplements', 'refresh');
		}
		$this->load->model('supplements_model');
		$this->supplements_model->delete($nSupplementId);
		redirect($this->router->class . '/supplements', 'refresh');
	}
	
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по названию статьи 
	 */
	public function supplement_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('supplements_model');
				$aResult = $this->supplements_model->get(array('com_title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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

/* End of file adm_supplements.php */
/* Location: ./application/controllers/adm_supplements.php */