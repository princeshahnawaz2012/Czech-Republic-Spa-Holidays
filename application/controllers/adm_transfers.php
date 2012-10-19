<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_transfers extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign('TRANSFER_ALL', TRANSFER_ALL);
		$this->smarty->assign('TRANSFER_ACTIVE', TRANSFER_ACTIVE);
		$this->smarty->assign('TRANSFER_INACTIVE', TRANSFER_INACTIVE);
	}
	
	public function index()
	{
		redirect($this->router->class . '/transfers');
	}
	
	
	/**
	 * Готель, форма добавления и обработчик формы(также AJAX) 
	 */
	public function transfer_add()
	{
		$this->load->model('transfers_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aTransferData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_transfer_id' => NULL,
			);
			$nTransferId = $this->transfers_model->save($aTransferData);
			$aTransferData = array(
				'transfer_id' => $nTransferId,
				'title' => $this->input->post('title'),
			);
			$this->transfers_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aTransferData);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aTransferData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/transfers', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aTransferData = array(
					'transfer_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aTransferData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/transfers');
			
			$this->title(vlang('Adding a transfer'));
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
	public function transfers($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('transfers_model');
		
		$this->javascript('adm/transfers/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'transfers.com_transfer_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_spas.title' => '',
			LANGUAGE_ABBR_DEFAULT . '_suppliers.title' => '',
			LANGUAGE_ABBR_DEFAULT . '_stations.title' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_transfer_id', LANGUAGE_ABBR_DEFAULT . '_spas.title', LANGUAGE_ABBR_DEFAULT . '_suppliers.title', LANGUAGE_ABBR_DEFAULT . '_stations.title');
		$aOrdersName = array(vlang('ID'), vlang('Hotel'), vlang('Supplier'), vlang('Station'));
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
				$aTransfers = $this->transfers_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aTransfers = $this->transfers_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->transfers_model->get_count();
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
				$aTransfers = $this->transfers_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aTransfers = $this->transfers_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->transfers_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aTransfers', $aTransfers);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/transfer_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/transfer_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/transfer_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/transfer_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/transfer_delete/');
		
		$nCountAllTransfers = $this->transfers_model->get_count();
		$nCountInactiveTransfers = $this->transfers_model->get_count(array('com_active' => TRANSFER_INACTIVE));
		$this->smarty->assign('nCountAllTransfers', $nCountAllTransfers);
		$this->smarty->assign('nCountInactiveTransfers', $nCountInactiveTransfers);
		$this->smarty->assign('nCountActiveTransfers', $nCountAllTransfers - $nCountInactiveTransfers);
		$this->title(vlang('The transfers'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nTransferId 
	 */
	public function transfer_edit($nTransferId = FALSE)
	{
		if ( ! $nTransferId )
		{
			redirect($this->router->class . '/transfers', 'refresh');
		}
		$this->load->model('transfers_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		if ( $this->form_validation->run() === TRUE )
		{
			$aTransferData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => intval($this->input->post('com_order')),
			);
			$this->transfers_model->save($aTransferData, $nTransferId);
			
			$aTransferData = array(
				'title' => $this->input->post('title'),
			);
			$this->transfers_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aTransferData, $nTransferId);
			if ( $this->isAjaxRequest() )
			{
				$this->AjaxResponse(intval($nTransferId), FALSE);
			}
			else
			{
				redirect($this->router->class . '/transfers', 'refresh');
			}
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$aTransferData = $this->transfers_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('transfer_id' => $nTransferId), TRUE);
			$this->smarty->assign('aTransferData', $aTransferData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/transfers');
			$this->title(vlang('Editing a transfer'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nTransferId 
	 */
	public function transfer_activate($nTransferId = FALSE)
	{
		if ( ! $nTransferId )
		{
			redirect($this->router->class . '/transfers');
		}
		$this->load->model('transfers_model');
		$aData = array(
			'com_active' => TRANSFER_ACTIVE
		);
		if ( $nTransferId )
		{
			$this->transfers_model->save($aData, $nTransferId);
			redirect($this->router->class . '/transfers');
		}
		else
		{
			$aTransferId = $this->input->post('aTransferId');
			if ( $aTransferId )
			{
				echo $this->transfers_model->save($aData, $aTransferId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nTransferId 
	 */
	public function transfer_deactivate($nTransferId = FALSE)
	{
		if ( ! $nTransferId )
		{
			redirect($this->router->class . '/transfers');
		}
		$this->load->model('transfers_model');
		$aData = array(
			'com_active' => TRANSFER_INACTIVE
		);
		if ( $nTransferId )
		{
			$this->transfers_model->save($aData, $nTransferId);
			redirect($this->router->class . '/transfers');
		}
		else
		{
			$aTransferId = $this->input->post('aTransferId');
			if ( $aTransferId )
			{
				echo $this->transfers_model->save($aData, $aTransferId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nTransferId 
	 */
	public function transfer_delete($nTransferId = FALSE)
	{
		if ( ! $nTransferId )
		{
			redirect($this->router->class . '/transfers', 'refresh');
		}
		$this->load->model('transfers_model');
		$this->transfers_model->delete($nTransferId);
		redirect($this->router->class . '/transfers', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function transfer_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nTransferId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nTransferId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('transfers_model');
				if ( $this->transfers_model->get_count(array('com_transfer_id' => $nTransferId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->transfers_model->save(array('com_order' => $nOrder), $nTransferId);
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
	public function transfer_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('transfers_model');
				$aResult = $this->transfers_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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

/* End of file adm_transfers.php */
/* Location: ./application/controllers/adm_transfers.php */