<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_essential_infos extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		
		$this->smarty->assign('ESSENTIAL_INFO_ALL', ESSENTIAL_INFO_ALL);
		$this->smarty->assign('ESSENTIAL_INFO_ACTIVE', ESSENTIAL_INFO_ACTIVE);
		$this->smarty->assign('ESSENTIAL_INFO_INACTIVE', ESSENTIAL_INFO_INACTIVE);
	}
	
	public function index()
	{
		redirect($this->router->class . '/essential_infos');
	}
	
	
	/**
	 * Готель, форма добавления и обработчик формы(также AJAX) 
	 */
	public function essential_info_add()
	{
		$this->load->model('temp_files_model');
		$this->load->model('essential_infos_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer|xss_clean');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		$this->form_validation->set_rules('com_picture_ext', vlang('Picture'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aEssential_infoData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => $this->input->post('com_order'),
				'com_essential_info_id' => NULL,
			);
			$nEssential_infoId = $this->essential_infos_model->save($aEssential_infoData);
			
			//перемещаем изображения в папки хранения с временной папки редактирования
			$aEssential_infoData = array(
				'com_picture_ext' => $this->temp_files_model->move($this->input->post('temp_name_picture'), $this->input->post('com_picture_ext'), './' . $this->config->item('essential_info_pictures_dir'), $nEssential_infoId),
				
			);
			//сохраняем раширения изображений
			$this->essential_infos_model->save($aEssential_infoData, $nEssential_infoId);
			
			$aEssential_infoData = array(
				'essential_info_id' => $nEssential_infoId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$this->essential_infos_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aEssential_infoData);
			if ( $this->isAjaxRequest('POST') )
			{
				$this->AjaxResponse($aEssential_infoData, TRUE);
			}
			else
			{
				redirect($this->router->class . '/essential_infos', 'refresh');
			}
		}
		else
		{
			if ( $this->isAjaxRequest('POST') )
			{
				$aEssential_infoData = array(
					'essential_info_id' => 0,
					'validation_errors' => validation_errors(" ", "\n"),
				);
				$this->AjaxResponse($aEssential_infoData, TRUE);
			}
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->smarty->assign('sCancelUrl', $this->router->class . '/essential_infos');
			
			$sTempNamePicture = $this->isPostMethod() ? $this->POST('temp_name_picture', TRUE) : $this->temp_files_model->get();
			
			$this->smarty->assign('sTempNamePicture', $sTempNamePicture);
			
			$aTempalteVar = array(
				'max_picture_width' => $this->config->item('max_essential_info_picture_width'),
				'max_picture_height' => $this->config->item('max_essential_info_picture_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/essential_info_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/essential_info_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/essential_info_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/essential_info_rotate_image/',
			);
			
			$nPictureMaxWidth = $this->config->item('max_essential_info_picture_width');
			$nPictureMaxHeight = $this->config->item('max_essential_info_picture_height');;
			$nPictureMinWidth = 0;
			$nPictureMinHeight = 0;
			$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
			$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
			$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
			$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);
			
			$this->template_var($aTempalteVar);
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			$this->javascript('adm/essential_infos/essential_info_images_add.js');
			
			$this->title(vlang('Adding a essential info'));
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
	public function essential_infos($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('essential_infos_model');
		
		$this->javascript('adm/essential_infos/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'essential_infos.com_essential_info_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_essential_infos.title' => '',
			'essential_infos.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_essential_info_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Essential info', 'Status', 'Order');
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
				$aEssential_infos = $this->essential_infos_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aEssential_infos = $this->essential_infos_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->essential_infos_model->get_count();
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
				$aEssential_infos = $this->essential_infos_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aEssential_infos = $this->essential_infos_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->essential_infos_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aEssential_infos', $aEssential_infos);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/essential_info_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/essential_info_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/essential_info_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/essential_info_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/essential_info_delete/');
		
		$nCountAllEssential_infos = $this->essential_infos_model->get_count();
		$nCountInactiveEssential_infos = $this->essential_infos_model->get_count(array('com_active' => ESSENTIAL_INFO_INACTIVE));
		$this->smarty->assign('nCountAllEssential_infos', $nCountAllEssential_infos);
		$this->smarty->assign('nCountInactiveEssential_infos', $nCountInactiveEssential_infos);
		$this->smarty->assign('nCountActiveEssential_infos', $nCountAllEssential_infos - $nCountInactiveEssential_infos);
		$this->title(vlang('The essential info'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nEssential_infoId 
	 */
	public function essential_info_edit($nEssential_infoId = FALSE)
	{
		if ( ! $nEssential_infoId )
		{
			redirect($this->router->class . '/essential_infos', 'refresh');
		}
		$this->load->model('temp_files_model');
		$this->load->model('essential_infos_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', vlang('Title'), 'required|xss_clean');
		$this->form_validation->set_rules('com_active', vlang('Status'), 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer|xss_clean');
		$this->form_validation->set_rules('short_desc', vlang('Short description'), 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aEssential_infoData = array(
				'com_active' => $this->input->post('com_active'),
				'com_order' => intval($this->input->post('com_order')),
			);
			$this->essential_infos_model->save($aEssential_infoData, $nEssential_infoId);
			
			//перемещаем изображения в папки хранения с временной папки редактирования
			$aEssential_infoData = array(
				'com_picture_ext' => $this->temp_files_model->move($this->input->post('temp_name_picture'), $this->input->post('com_picture_ext'), './' . $this->config->item('essential_info_pictures_dir'), $nEssential_infoId),
			);
			//сохраняем раширения изображений
			$this->essential_infos_model->save($aEssential_infoData, $nEssential_infoId);
			
			
			$aEssential_infoData = array(
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
			);
			$this->essential_infos_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aEssential_infoData, $nEssential_infoId);
			if ( $this->isAjaxRequest() )
			{
				$this->AjaxResponse(intval($nEssential_infoId), FALSE);
			}
			else
			{
				redirect($this->router->class . '/essential_infos', 'refresh');
			}
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_active'), 'com_active');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$aEssential_infoData = $this->essential_infos_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('essential_info_id' => $nEssential_infoId), TRUE);
			$this->smarty->assign('aEssential_infoData', $aEssential_infoData);
			$this->smarty->assign('sCancelUrl', $this->router->class . '/essential_infos');
			
			$sTempNamePicture = $this->isPostMethod() ? $this->POST('temp_name_picture', TRUE) : $this->temp_files_model->get();
			$this->smarty->assign('sTempNamePicture', $sTempNamePicture);
			
			$aTempalteVar = array(
				'max_picture_width' => $this->config->item('max_essential_info_picture_width'),
				'max_picture_height' => $this->config->item('max_essential_info_picture_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/essential_info_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/essential_info_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/essential_info_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/essential_info_rotate_image/',
			);
			
			$nPictureMaxWidth = $this->config->item('max_essential_info_picture_width');
			$nPictureMaxHeight = $this->config->item('max_essential_info_picture_height');
			$nPictureMinWidth = 0;
			$nPictureMinHeight = 0;
			$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
			$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
			$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
			$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);
			
			$this->smarty->assign('sEssential_infoPicturesDir', '/' . $this->config->item('essential_info_pictures_dir'));
			
			if ($aEssential_infoData['com_picture_ext'])
			{
				copy('./' . $this->config->item('essential_info_pictures_dir') . $nEssential_infoId . '.' . $aEssential_infoData['com_picture_ext'], './' . $this->config->item('temp_files_dir') . $sTempNamePicture . '.' . $aEssential_infoData['com_picture_ext']);
				$this->javascript('adm/essential_infos/picture_image_manipulation_add.js');
			}
			$this->template_var($aTempalteVar);
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			$this->javascript('adm/essential_infos/essential_info_images_add.js');
			
			$this->title(vlang('Editing a essential info'));
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nEssential_infoId 
	 */
	public function essential_info_activate($nEssential_infoId = FALSE)
	{
		if ( ! $nEssential_infoId )
		{
			redirect($this->router->class . '/essential_infos');
		}
		$this->load->model('essential_infos_model');
		$aData = array(
			'com_active' => ESSENTIAL_INFO_ACTIVE
		);
		if ( $nEssential_infoId )
		{
			$this->essential_infos_model->save($aData, $nEssential_infoId);
			redirect($this->router->class . '/essential_infos');
		}
		else
		{
			$aEssential_infoId = $this->input->post('aEssential_infoId');
			if ( $aEssential_infoId )
			{
				echo $this->essential_infos_model->save($aData, $aEssential_infoId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nEssential_infoId 
	 */
	public function essential_info_deactivate($nEssential_infoId = FALSE)
	{
		if ( ! $nEssential_infoId )
		{
			redirect($this->router->class . '/essential_infos');
		}
		$this->load->model('essential_infos_model');
		$aData = array(
			'com_active' => ESSENTIAL_INFO_INACTIVE
		);
		if ( $nEssential_infoId )
		{
			$this->essential_infos_model->save($aData, $nEssential_infoId);
			redirect($this->router->class . '/essential_infos');
		}
		else
		{
			$aEssential_infoId = $this->input->post('aEssential_infoId');
			if ( $aEssential_infoId )
			{
				echo $this->essential_infos_model->save($aData, $aEssential_infoId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nEssential_infoId 
	 */
	public function essential_info_delete($nEssential_infoId = FALSE)
	{
		if ( ! $nEssential_infoId )
		{
			redirect($this->router->class . '/essential_infos', 'refresh');
		}
		$this->load->model('essential_infos_model');
		$this->essential_infos_model->unlink_files($nEssential_infoId);
		$this->essential_infos_model->delete($nEssential_infoId);
		redirect($this->router->class . '/essential_infos', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function essential_info_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nEssential_infoId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nEssential_infoId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('essential_infos_model');
				if ( $this->essential_infos_model->get_count(array('com_essential_info_id' => $nEssential_infoId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->essential_infos_model->save(array('com_order' => $nOrder), $nEssential_infoId);
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
	public function essential_info_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('essential_infos_model');
				$aResult = $this->essential_infos_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	
	public function essential_info_upload_image($sImageType = 'picture')
	{
		if ($this->isPostMethod())
		{
			$sTempName = $this->input->post('temp_name');
			$bOnlyZoomOut = TRUE;
			$sExt = $this->upload_image('image_file', './' . $this->config->item('temp_files_dir'), $sTempName, TRUE, 0, $this->config->item('max_essential_info_' . $sImageType . '_height'), $bOnlyZoomOut);
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
	
	public function essential_info_crop_image($sImageType = 'picture')
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
	
	public function essential_info_resize_image($sImageType = 'picture')
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
	
	public function essential_info_rotate_image($sImageType = 'picture')
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
	
}

/* End of file adm_essential_infos.php */
/* Location: ./application/controllers/adm_essential_infos.php */