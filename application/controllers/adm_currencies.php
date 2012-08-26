<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_currencies extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->smarty->assign('CURRENCY_ALL', CURRENCY_ALL);
		$this->smarty->assign('CURRENCY_ACTIVE', CURRENCY_ACTIVE);
		$this->smarty->assign('CURRENCY_INACTIVE', CURRENCY_INACTIVE);
	}
	
	public function index()
	{
		redirect($this->router->class . '/currencies');
	}
	
		
	/**
	 * Валюта, форма добавления и обработчик формы(также AJAX) 
	 */
	public function currency_add()
	{
		$this->load->model('currencies_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_currency_id', vlang('ISO'), 'required|exact_length[3]|xss_clean|alpha');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'integer');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCurrencyData = array(
				'com_active' => $this->input->post('com_active'),
				'com_currency_id' => strtoupper($this->input->post('com_currency_id')),
				'com_order' => $this->input->post('com_order'),
			);
			$this->currencies_model->save($aCurrencyData);
			$sCurrencyId = $aCurrencyData['com_currency_id'];
			$aCurrencyData = array(
				'currency_id' => $sCurrencyId,
				'title' => $this->input->post('title'),
			);
			$this->currencies_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aCurrencyData);
			$aOtherCurrencies = $this->currencies_model->get(array('com_currency_id <> ' => $sCurrencyId));
			$bRemoteCurrencyRatesEnabled = $this->config->item('remote_currency_rates_enabled');
			foreach ($aOtherCurrencies as $aOtherCurrency)
			{
				$fCurrencyRate = 1.0;
				if ($bRemoteCurrencyRatesEnabled)
				{
					$fRemoteRate = $this->currencies_model->get_currency_exchange_rate_from_remote_server($sCurrencyId, $aOtherCurrency['com_currency_id']);
					if ( $fRemoteRate !== FALSE )
					{
						$fCurrencyRate = $fRemoteRate;
					}
				}
				$this->currencies_model->save_exchange($sCurrencyId, $aOtherCurrency['com_currency_id'], $fCurrencyRate);
				$fCurrencyRate = 1.0;
				if ($bRemoteCurrencyRatesEnabled)
				{
					$fRemoteRate = $this->currencies_model->get_currency_exchange_rate_from_remote_server($aOtherCurrency['com_currency_id'], $sCurrencyId);
					if ( $fRemoteRate !== FALSE )
					{
						$fCurrencyRate = $fRemoteRate;
					}
				}
				$this->currencies_model->save_exchange($aOtherCurrency['com_currency_id'], $sCurrencyId, $fCurrencyRate);
			}
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aCurrencyData, TRUE);
			}
			redirect($this->router->class . '/currencies', 'refresh');
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aCurrencyData = array(
					'currency_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aCurrencyData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_currency_id'), 'com_currency_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/currencies');
			$this->title(vlang('Adding a currency'));
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
	public function currencies($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('currencies_model');
		
		$this->javascript('adm/currencies/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'currencies.com_currency_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_currencies.title' => '',
			'currencies.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_currency_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_active', 'com_order');
		$aOrdersName = array('ISO', 'Title', 'Status', 'Order');
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
				$aCurrencies = $this->currencies_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCurrencies = $this->currencies_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->currencies_model->get_count();
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
				$aCurrencies = $this->currencies_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCurrencies = $this->currencies_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->currencies_model->get_count_adm_list($aFilters);
		}
				
		$this->smarty->assign('aCurrencies', $aCurrencies);
		
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/currency_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/currency_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/currency_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/currency_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/currency_delete/');
		
		
		$nCountAllCurrencies = $this->currencies_model->get_count();
		$nCountInactiveCurrencies = $this->currencies_model->get_count(array('com_active' => CURRENCY_INACTIVE));
		$this->smarty->assign('nCountAllCurrencies', $nCountAllCurrencies);
		$this->smarty->assign('nCountInactiveCurrencies', $nCountInactiveCurrencies);
		$this->smarty->assign('nCountActiveCurrencies', $nCountAllCurrencies - $nCountInactiveCurrencies);
		$this->title(vlang('The currencies'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param string $sCurrencyId 
	 */
	public function currency_edit($sCurrencyId = FALSE)
	{
		if ( ! $sCurrencyId )
		{
			redirect($this->router->class . '/currencies', 'refresh');
		}
		$this->load->model('currencies_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_currency_id', vlang('ISO'), 'required|exact_length[3]|xss_clean|alpha');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'integer');
		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_currency_id'), 'com_currency_id');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$aCurrencyData = $this->currencies_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('currency_id' => $sCurrencyId), TRUE);
			$this->smarty->assign('aCurrencyData', $aCurrencyData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/currencies');
			$this->title(vlang('Editing a currency'));
			$this->view();
		}
		else
		{
			$aCurrencyData = array(
				'com_currency_id' => strtoupper($this->input->post('com_currency_id')),
				'com_order' => intval($this->input->post('com_order')),
				'com_active' => intval($this->input->post('com_active')),
			);
			$this->currencies_model->save($aCurrencyData, $sCurrencyId);
			
			$aCurrencyData = array(
				'currency_id' => $aCurrencyData['com_currency_id'],
				'title' => $this->input->post('title'),
			);
			$this->currencies_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aCurrencyData, $sCurrencyId);
			
			redirect($this->router->class . '/currencies', 'refresh');
		}
	}

	/**
	 * Активация записи
	 * @param string $sCurrencyId 
	 */
	public function currency_activate($sCurrencyId = FALSE)
	{
		if ( ! $sCurrencyId )
		{
			redirect($this->router->class . '/currencies');
		}
		$this->load->model('currencies_model');
		$aData = array(
			'com_active' => CURRENCY_ACTIVE
		);
		if ( $sCurrencyId )
		{
			$this->currencies_model->save($aData, $sCurrencyId);
			redirect($this->router->class . '/currencies');
		}
		else
		{
			$aCurrencyId = $this->input->post('aCurrencyId');
			if ( $aCurrencyId )
			{
				echo $this->currencies_model->save($aData, $aCurrencyId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param string $sCurrencyId 
	 */
	public function currency_deactivate($sCurrencyId = FALSE)
	{
		if ( ! $sCurrencyId )
		{
			redirect($this->router->class . '/currencies');
		}
		$this->load->model('currencies_model');
		$aData = array(
			'com_active' => CURRENCY_INACTIVE
		);
		if ( $sCurrencyId )
		{
			$this->currencies_model->save($aData, $sCurrencyId);
			redirect($this->router->class . '/currencies');
		}
		else
		{
			$aCurrencyId = $this->input->post('aCurrencyId');
			if ( $aCurrencyId )
			{
				echo $this->currencies_model->save($aData, $aCurrencyId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param string $sCurrencyId 
	 */
	public function currency_delete($sCurrencyId = FALSE)
	{
		if ( ! $sCurrencyId )
		{
			redirect($this->router->class . '/currencies', 'refresh');
		}
		$this->load->model('currencies_model');
		$this->currencies_model->delete_exchanges($sCurrencyId);
		$this->currencies_model->delete_exchanges(NULL, $sCurrencyId);
		$this->currencies_model->delete($sCurrencyId);
		redirect($this->router->class . '/currencies', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function currency_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$sCurrencyId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($sCurrencyId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('currencies_model');
				if ( $this->currencies_model->get_count(array('com_currency_id' => $sCurrencyId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->currencies_model->save(array('com_order' => $nOrder), $sCurrencyId);
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
	
	public function currencies_exchange($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~', $nOffset=0)
	{
//		$this->output->enable_profiler(TRUE);
		$this->load->model('currencies_model');
		
		$this->javascript('adm/currencies/filter_exchange.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'currencies_exchange.com_currency_from_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_currencies_from.title' => '',
			'currencies_exchange.com_currency_to_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_currencies_to.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('title_from', 'com_currency_from_id', 'exchange', 'com_currency_to_id', 'title_to',);
		$aOrdersName = array('Title From', 'ISO From', 'Exchange', 'ISO To', 'Currency To');
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
				$aCurrencies = $this->currencies_model->get_currencies_exchange_list(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCurrencies = $this->currencies_model->get_currencies_exchange_list(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->currencies_model->get_currencies_exchange_count(LANGUAGE_ABBR_DEFAULT);
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
				$aCurrencies = $this->currencies_model->get_currencies_exchange_list(LANGUAGE_ABBR_DEFAULT, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCurrencies = $this->currencies_model->get_currencies_exchange_list(LANGUAGE_ABBR_DEFAULT, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] =  $this->currencies_model->get_currencies_exchange_count(LANGUAGE_ABBR_DEFAULT, $aFilters);
		}
				
		$this->smarty->assign('aCurrencies', $aCurrencies);
		
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/currency_add/');
		
		$this->javascript('adm/currencies/sync.js');
		$this->title(vlang('Currencies exchange'));
		$this->view();
	}
	
	/**
	 * Синхронизация курса валют с удаленным сервером 
	 */
	public function sync_remote()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$aCurrencies = $this->input->post('currencies');
			if ( $aCurrencies )
			{
				$this->load->model('currencies_model');
				$aRates = array();
				foreach ( $aCurrencies as $sCurrencies )
				{
					$aCurrency = explode('-', $sCurrencies);
					$aRates[$sCurrencies] = $this->currencies_model->get_currency_exchange_rate_from_remote_server($aCurrency[0], $aCurrency[1]);
					$this->currencies_model->save_exchange($aCurrency[0], $aCurrency[1], $aRates[$sCurrencies]);
				}
				$this->AjaxResponse(array('result' => 1, 'rates' => $aRates,), TRUE);
			}
			$this->AjaxResponse(array('result' => 0, 'currencies' => $aCurrencies,), TRUE);
		}
		die(vlang('Access denied'));
	}
	
	
	
	/**
	 * Сохранения курса валюты(com_exchange) через AJAX
	 */
	public function currency_exchange_rate_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$sCurrencyFromId = $this->input->post('from_id');
			$sCurrencyToId = $this->input->post('to_id');
			$fRate = $this->input->post('rate');
			if ($sCurrencyFromId !== FALSE && $sCurrencyToId !== FALSE && $fRate !== FALSE)
			{
				$this->load->model('currencies_model');
				if ( $this->currencies_model->get_currencies_exchange_count(LANGUAGE_ABBR_DEFAULT, array('com_currency_from_id' => $sCurrencyFromId, 'com_currency_to_id' => $sCurrencyToId, 'com_exchange' => $fRate)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->currencies_model->save_exchange($sCurrencyFromId, $sCurrencyToId, $fRate);
				if ( $nAffectedRows )
				{
					$this->AjaxResponse('ok', FALSE);
				}
			}
		}
		$this->AjaxResponse('error', FALSE);
	}
	
}

/* End of file adm_currencies.php */
/* Location: ./application/controllers/adm_currencies.php */