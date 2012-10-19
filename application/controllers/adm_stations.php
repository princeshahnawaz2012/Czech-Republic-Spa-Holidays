<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_stations extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign('STATION_ALL', STATION_ALL);
		$this->smarty->assign('STATION_ACTIVE', STATION_ACTIVE);
		$this->smarty->assign('STATION_INACTIVE', STATION_INACTIVE);
	}
	
	public function index()
	{
		redirect($this->router->class . '/stations');
	}
	
	
	/**
	 * Готель, форма добавления и обработчик формы(также AJAX) 
	 */
	public function station_add()
	{
		$this->load->model('stations_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aStationData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_station_id' => NULL,
			);
			$nStationId = $this->stations_model->save($aStationData);
			$aStationData = array(
				'station_id' => $nStationId,
				'title' => $this->input->post('title'),
			);
			$this->stations_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aStationData);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aStationData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/stations', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aStationData = array(
					'station_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aStationData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/stations');
			
			$this->title(vlang('Adding a station'));
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
	public function stations($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('stations_model');
		
		$this->javascript('adm/stations/filter.js');
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
			'stations.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_station_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Station', 'Status', 'Order');
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
				$aStations = $this->stations_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aStations = $this->stations_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->stations_model->get_count();
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
				$aStations = $this->stations_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aStations = $this->stations_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->stations_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aStations', $aStations);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/station_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/station_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/station_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/station_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/station_delete/');
		
		$nCountAllStations = $this->stations_model->get_count();
		$nCountInactiveStations = $this->stations_model->get_count(array('com_active' => STATION_INACTIVE));
		$this->smarty->assign('nCountAllStations', $nCountAllStations);
		$this->smarty->assign('nCountInactiveStations', $nCountInactiveStations);
		$this->smarty->assign('nCountActiveStations', $nCountAllStations - $nCountInactiveStations);
		$this->title(vlang('The stations'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nStationId 
	 */
	public function station_edit($nStationId = FALSE)
	{
		if ( ! $nStationId )
		{
			redirect($this->router->class . '/stations', 'refresh');
		}
		$this->load->model('stations_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		if ( $this->form_validation->run() === TRUE )
		{
			$aStationData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => intval($this->input->post('com_order')),
			);
			$this->stations_model->save($aStationData, $nStationId);
			
			$aStationData = array(
				'title' => $this->input->post('title'),
			);
			$this->stations_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aStationData, $nStationId);
			if ( $this->isAjaxRequest() )
			{
				$this->AjaxResponse(intval($nStationId), FALSE);
			}
			else
			{
				redirect($this->router->class . '/stations', 'refresh');
			}
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$aStationData = $this->stations_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('station_id' => $nStationId), TRUE);
			$this->smarty->assign('aStationData', $aStationData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/stations');
			$this->title(vlang('Editing a station'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nStationId 
	 */
	public function station_activate($nStationId = FALSE)
	{
		if ( ! $nStationId )
		{
			redirect($this->router->class . '/stations');
		}
		$this->load->model('stations_model');
		$aData = array(
			'com_active' => STATION_ACTIVE
		);
		if ( $nStationId )
		{
			$this->stations_model->save($aData, $nStationId);
			redirect($this->router->class . '/stations');
		}
		else
		{
			$aStationId = $this->input->post('aStationId');
			if ( $aStationId )
			{
				echo $this->stations_model->save($aData, $aStationId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nStationId 
	 */
	public function station_deactivate($nStationId = FALSE)
	{
		if ( ! $nStationId )
		{
			redirect($this->router->class . '/stations');
		}
		$this->load->model('stations_model');
		$aData = array(
			'com_active' => STATION_INACTIVE
		);
		if ( $nStationId )
		{
			$this->stations_model->save($aData, $nStationId);
			redirect($this->router->class . '/stations');
		}
		else
		{
			$aStationId = $this->input->post('aStationId');
			if ( $aStationId )
			{
				echo $this->stations_model->save($aData, $aStationId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nStationId 
	 */
	public function station_delete($nStationId = FALSE)
	{
		if ( ! $nStationId )
		{
			redirect($this->router->class . '/stations', 'refresh');
		}
		$this->load->model('stations_model');
		$this->stations_model->delete($nStationId);
		redirect($this->router->class . '/stations', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function station_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nStationId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nStationId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('stations_model');
				if ( $this->stations_model->get_count(array('com_station_id' => $nStationId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->stations_model->save(array('com_order' => $nOrder), $nStationId);
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
	
	
}

/* End of file adm_stations.php */
/* Location: ./application/controllers/adm_stations.php */