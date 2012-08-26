<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_illneses extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign('ILLNESE_ALL', ILLNESE_ALL);
		$this->smarty->assign('ILLNESE_ACTIVE', ILLNESE_ACTIVE);
		$this->smarty->assign('ILLNESE_INACTIVE', ILLNESE_INACTIVE);
	}
	
	public function index()
	{
		redirect($this->router->class . '/illneses');
	}
	
	
	/**
	 * Готель, форма добавления и обработчик формы(также AJAX) 
	 */
	public function illnese_add()
	{
		$this->load->model('illneses_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aIllneseData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_illnese_id' => NULL,
			);
			$nIllneseId = $this->illneses_model->save($aIllneseData);
			$aIllneseData = array(
				'illnese_id' => $nIllneseId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$this->illneses_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aIllneseData);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aIllneseData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/illneses', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aIllneseData = array(
					'illnese_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aIllneseData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/illneses');
			
			$this->title(vlang('Adding a illnese'));
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
	public function illneses($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('illneses_model');
		
		$this->javascript('adm/illneses/filter.js');
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
			'illneses.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_illnese_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Illnese', 'Status', 'Order');
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
				$aIllneses = $this->illneses_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aIllneses = $this->illneses_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->illneses_model->get_count();
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
				$aIllneses = $this->illneses_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aIllneses = $this->illneses_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->illneses_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aIllneses', $aIllneses);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/illnese_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/illnese_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/illnese_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/illnese_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/illnese_delete/');
		
		$nCountAllIllneses = $this->illneses_model->get_count();
		$nCountInactiveIllneses = $this->illneses_model->get_count(array('com_active' => ILLNESE_INACTIVE));
		$this->smarty->assign('nCountAllIllneses', $nCountAllIllneses);
		$this->smarty->assign('nCountInactiveIllneses', $nCountInactiveIllneses);
		$this->smarty->assign('nCountActiveIllneses', $nCountAllIllneses - $nCountInactiveIllneses);
		$this->title(vlang('The illneses'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nIllneseId 
	 */
	public function illnese_edit($nIllneseId = FALSE)
	{
		if ( ! $nIllneseId )
		{
			redirect($this->router->class . '/illneses', 'refresh');
		}
		$this->load->model('illneses_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aIllneseData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => intval($this->input->post('com_order')),
			);
			$this->illneses_model->save($aIllneseData, $nIllneseId);
			
			$aIllneseData = array(
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$this->illneses_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aIllneseData, $nIllneseId);
			if ( $this->isAjaxRequest() )
			{
				$this->AjaxResponse(intval($nIllneseId), FALSE);
			}
			else
			{
				redirect($this->router->class . '/illneses', 'refresh');
			}
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$aIllneseData = $this->illneses_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('illnese_id' => $nIllneseId), TRUE);
			$this->smarty->assign('aIllneseData', $aIllneseData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/illneses');
			$this->title(vlang('Editing a illnese'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nIllneseId 
	 */
	public function illnese_activate($nIllneseId = FALSE)
	{
		if ( ! $nIllneseId )
		{
			redirect($this->router->class . '/illneses');
		}
		$this->load->model('illneses_model');
		$aData = array(
			'com_active' => ILLNESE_ACTIVE
		);
		if ( $nIllneseId )
		{
			$this->illneses_model->save($aData, $nIllneseId);
			redirect($this->router->class . '/illneses');
		}
		else
		{
			$aIllneseId = $this->input->post('aIllneseId');
			if ( $aIllneseId )
			{
				echo $this->illneses_model->save($aData, $aIllneseId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nIllneseId 
	 */
	public function illnese_deactivate($nIllneseId = FALSE)
	{
		if ( ! $nIllneseId )
		{
			redirect($this->router->class . '/illneses');
		}
		$this->load->model('illneses_model');
		$aData = array(
			'com_active' => ILLNESE_INACTIVE
		);
		if ( $nIllneseId )
		{
			$this->illneses_model->save($aData, $nIllneseId);
			redirect($this->router->class . '/illneses');
		}
		else
		{
			$aIllneseId = $this->input->post('aIllneseId');
			if ( $aIllneseId )
			{
				echo $this->illneses_model->save($aData, $aIllneseId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nIllneseId 
	 */
	public function illnese_delete($nIllneseId = FALSE)
	{
		if ( ! $nIllneseId )
		{
			redirect($this->router->class . '/illneses', 'refresh');
		}
		$this->load->model('illneses_model');
		$this->illneses_model->delete($nIllneseId);
		redirect($this->router->class . '/illneses', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function illnese_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nIllneseId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nIllneseId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('illneses_model');
				if ( $this->illneses_model->get_count(array('com_illnese_id' => $nIllneseId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->illneses_model->save(array('com_order' => $nOrder), $nIllneseId);
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
	
	
}

/* End of file adm_illneses.php */
/* Location: ./application/controllers/adm_illneses.php */