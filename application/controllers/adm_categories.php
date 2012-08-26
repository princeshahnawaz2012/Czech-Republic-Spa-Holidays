<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_categories extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->smarty->assign('CATEGORY_ALL', CATEGORY_ALL);
		$this->smarty->assign('CATEGORY_ACTIVE', CATEGORY_ACTIVE);
		$this->smarty->assign('CATEGORY_INACTIVE', CATEGORY_INACTIVE);
		$this->smarty->assign('COMPLEX_TREATMENT_MEDICAL', COMPLEX_TREATMENT_MEDICAL);
		$this->smarty->assign('COMPLEX_TREATMENT_COSMETIC', COMPLEX_TREATMENT_COSMETIC);
	}
	
	public function index()
	{
		redirect($this->router->class . '/categories');
	}
	
	
	/**
	 * Категория, форма добавления и обработчик формы(также AJAX) 
	 */
	public function category_add()
	{
		$this->load->model('temp_files_model');
		$this->load->model('categories_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Category', 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer|xss_clean');
		$this->form_validation->set_rules('short_desc', 'Short description', 'required|xss_clean');
		$this->form_validation->set_rules('desc', 'Description', 'required|xss_clean');
		$this->form_validation->set_rules('com_complex_treatments', 'Complex Treatments', 'required|xss_clean');
		$this->form_validation->set_rules('com_picture_ext', 'Picture', 'required|xss_clean');
		$this->form_validation->set_rules('seo_link', 'SEO Link', 'xss_clean');
		$this->form_validation->set_rules('metakeywords', 'Meta keywords', 'xss_clean');
		$this->form_validation->set_rules('metadescription', 'Meta description', 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCategoryData = array(
				'com_active' => CATEGORY_ACTIVE,
				'com_category_id' => NULL,
				'com_order' => $this->input->post('com_order'),
				'com_complex_treatments' => $this->input->post('com_complex_treatments'),
			);
			$nCategoryId = $this->categories_model->save($aCategoryData);
			
			//перемещаем изображения в папки хранения с временной папки редактирования
			$aCategoryData = array(
				'com_picture_ext' => $this->temp_files_model->move($this->input->post('temp_name_picture'), $this->input->post('com_picture_ext'), './' . $this->config->item('category_pictures_dir'), $nCategoryId),
				
			);
			//сохраняем раширения изображений
			$this->categories_model->save($aCategoryData, $nCategoryId);
			
			
			$aCategoryData = array(
				'category_id' => $nCategoryId,
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
				'desc' => $this->input->post('desc'),
				'seo_link' => $this->input->post('seo_link'),
				'metakeywords' => $this->input->post('metakeywords'),
				'metadescription' => $this->input->post('metadescription'),
			);
			$this->categories_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aCategoryData);
			redirect($this->router->class . '/categories', 'refresh');
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('desc'), 'desc');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_complex_treatments'), 'com_complex_treatments');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_picture_ext'), 'com_picture_ext');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('seo_link'), 'seo_link');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metakeywords'), 'metakeywords');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metadescription'), 'metadescription');

			$sTempNamePicture = $this->isPostMethod() ? $this->POST('temp_name_picture', TRUE) : $this->temp_files_model->get();
			
			$this->smarty->assign('sTempNamePicture', $sTempNamePicture);
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/categories');
			$aTempalteVar = array(
				'category_image_width' => $this->config->item('category_image_width'),
				'category_image_height' => $this->config->item('category_image_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/category_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/category_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/category_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/category_rotate_image/',
			);
			
			$nPictureMaxWidth = $this->config->item('category_image_width');
			$nPictureMaxHeight = $this->config->item('category_image_height');
			$nPictureMinWidth = $this->config->item('category_image_width');
			$nPictureMinHeight = $this->config->item('category_image_height');
			$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
			$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
			$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
			$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);
			
			$this->template_var($aTempalteVar);
			$this->title(vlang('Adding a category'));
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			$this->javascript('adm/categories/category_images_add.js');
			$this->view();
		}
	}
	
	
	/**
	 * Список всех категорий в системе
	 * 
	 * @param int $nPerPage
	 * @param array $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset 
	 */
	public function categories($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->model('categories_model');
		
		$this->javascript('adm/categories/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'categories.com_category_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_categories.title' => '',
			'categories.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aOrders = array('com_category_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_active', 'com_order');
		$aOrdersName = array('ID', 'Title', 'Status', 'Order');
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
				$aCategories = $this->categories_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aCategories = $this->categories_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->categories_model->get_count();
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
				$aCategories = $this->categories_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aCategories = $this->categories_model->get_adm_list(array(LANGUAGE_ABBR_DEFAULT), $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->categories_model->get_count_adm_list($aFilters);
		}
		
		$this->smarty->assign('aCategories', $aCategories);
		
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/category_add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/category_activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/category_deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/category_edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/category_delete/');
		
		$nCountAllCategories = $this->categories_model->get_count();
		$nCountInactiveCategories = $this->categories_model->get_count(array('com_active' => CATEGORY_INACTIVE));
		$this->smarty->assign('nCountAllCategories', $nCountAllCategories);
		$this->smarty->assign('nCountInactiveCategories', $nCountInactiveCategories);
		$this->smarty->assign('nCountActiveCategories', $nCountAllCategories - $nCountInactiveCategories);
		$this->title(vlang('The categories'));
		$this->view();
	}

	/**
	 * Редактирование записи с указанным ID
	 * @param int $nCategoryId 
	 */
	public function category_edit($nCategoryId = FALSE)
	{
		if ( ! $nCategoryId )
		{
			redirect($this->router->class . '/categories', 'refresh');
		}
		$this->load->model('temp_files_model');
		$this->load->model('categories_model');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('title', 'Category', 'required|xss_clean');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer|xss_clean');
		$this->form_validation->set_rules('short_desc', 'Short description', 'required|xss_clean');
		$this->form_validation->set_rules('desc', 'Description', 'required|xss_clean');
		$this->form_validation->set_rules('com_complex_treatments', 'Complex Treatments', 'required|xss_clean');
		$this->form_validation->set_rules('com_picture_ext', 'Picture', 'required|xss_clean');
		$this->form_validation->set_rules('seo_link', 'SEO Link', 'xss_clean');
		$this->form_validation->set_rules('metakeywords', 'Meta keywords', 'xss_clean');
		$this->form_validation->set_rules('metadescription', 'Meta description', 'xss_clean');
		if ( $this->form_validation->run() === TRUE )
		{
			$aCategoryData = array(
				'com_order' => $this->input->post('com_order'),
				'com_complex_treatments' => $this->input->post('com_complex_treatments'),
				'com_picture_ext' => $this->temp_files_model->move($this->input->post('temp_name_picture'), $this->input->post('com_picture_ext'), './' . $this->config->item('category_pictures_dir'), $nCategoryId),
			);
			$this->categories_model->save($aCategoryData, $nCategoryId);
			
			
			$aCategoryTranslateData = array(
				'title' => $this->input->post('title'),
				'short_desc' => $this->input->post('short_desc'),
				'desc' => $this->input->post('desc'),
				'seo_link' => $this->input->post('seo_link'),
				'metakeywords' => $this->input->post('metakeywords'),
				'metadescription' => $this->input->post('metadescription'),
			);
			$this->categories_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aCategoryTranslateData, $nCategoryId);
			redirect($this->router->class . '/categories', 'refresh');
		}
		else
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('short_desc'), 'short_desc');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('desc'), 'desc');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_complex_treatments'), 'com_complex_treatments');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_picture_ext'), 'com_picture_ext');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('seo_link'), 'seo_link');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metakeywords'), 'metakeywords');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('metadescription'), 'metadescription');

			$aCategoryData = $this->categories_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('com_category_id' => $nCategoryId,), TRUE);
			$this->smarty->assign('aCategoryData', $aCategoryData);
			
			$sTempNamePicture = $this->isPostMethod() ? $this->POST('temp_name_picture', TRUE) : $this->temp_files_model->get();
			
			$this->smarty->assign('sTempNamePicture', $sTempNamePicture);
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/categories');
			$aTempalteVar = array(
				'category_image_width' => $this->config->item('category_image_width'),
				'category_image_height' => $this->config->item('category_image_height'),
				'temp_dir' => $this->config->item('temp_files_dir'),
				'image_upload_url_begin' => $this->router->class . '/category_upload_image/',
				'image_crop_url_begin' => $this->router->class . '/category_crop_image/',
				'image_resize_url_begin' => $this->router->class . '/category_resize_image/',
				'image_rotate_url_begin' => $this->router->class . '/category_rotate_image/',
			);
			
			$nPictureMaxWidth = $this->config->item('category_image_width');
			$nPictureMaxHeight = $this->config->item('category_image_height');
			$nPictureMinWidth = $this->config->item('category_image_width');
			$nPictureMinHeight = $this->config->item('category_image_height');
			$this->smarty->assign('nPictureMaxWidth', $nPictureMaxWidth);
			$this->smarty->assign('nPictureMaxHeight', $nPictureMaxHeight);
			$this->smarty->assign('nPictureMinWidth', $nPictureMinWidth);
			$this->smarty->assign('nPictureMinHeight', $nPictureMinHeight);
			
			$this->smarty->assign('sCategoryPicturesDir', '/' . $this->config->item('category_pictures_dir'));
			
			if ($aCategoryData['com_picture_ext'])
			{
				copy('./' . $this->config->item('category_pictures_dir') . $nCategoryId . '.' . $aCategoryData['com_picture_ext'], './' . $this->config->item('temp_files_dir') . $sTempNamePicture . '.' . $aCategoryData['com_picture_ext']);
				$this->javascript('adm/categories/picture_image_manipulation_add.js');
			}
			
			$this->template_var($aTempalteVar);
			$this->title(vlang('Editing a category'));
			$this->stylesheet('jquery.Jcrop.min.css');
			$this->javascript('jquery.ocupload-1.1.2.packed.js');
			$this->javascript('jquery.Jcrop.min.js');
			$this->javascript('adm/categories/category_images_add.js');
			$this->view();
		}
	}

	/**
	 * Активация записи
	 * @param int $nCategoryId 
	 */
	public function category_activate($nCategoryId = FALSE)
	{
		if ( ! $nCategoryId )
		{
			redirect($this->router->class . '/categories');
		}
		$this->load->model('categories_model');
		$aData = array(
			'com_active' => CATEGORY_ACTIVE
		);
		if ( $nCategoryId )
		{
			$this->categories_model->save($aData, $nCategoryId);
			redirect($this->router->class . '/categories');
		}
		else
		{
			$aCategoryId = $this->input->post('aCategoryId');
			if ( $aCategoryId )
			{
				echo $this->categories_model->save($aData, $aCategoryId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Деактивация записи
	 * @param int $nCategoryId 
	 */
	public function category_deactivate($nCategoryId = FALSE)
	{
		if ( ! $nCategoryId )
		{
			redirect($this->router->class . '/categories');
		}
		$this->load->model('categories_model');
		$aData = array(
			'com_active' => CATEGORY_INACTIVE
		);
		if ( $nCategoryId )
		{
			$this->categories_model->save($aData, $nCategoryId);
			redirect($this->router->class . '/categories');
		}
		else
		{
			$aCategoryId = $this->input->post('aCategoryId');
			if ( $aCategoryId )
			{
				echo $this->categories_model->save($aData, $aCategoryId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	/**
	 * Удаление по указанному ID
	 * @param type $nCategoryId 
	 */
	public function category_delete($nCategoryId = FALSE)
	{
		if ( ! $nCategoryId )
		{
			redirect($this->router->class . '/categories', 'refresh');
		}
		$this->load->model('categories_model');
		$this->categories_model->unlink_files($nCategoryId);
		$this->categories_model->delete($nCategoryId);
		redirect($this->router->class . '/categories', 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) через AJAX 
	 */
	public function category_order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nCategoryId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nCategoryId !== FALSE && $nOrder !== FALSE)
			{
				$this->load->model('categories_model');
				if ( $this->categories_model->get_count(array('com_category_id' => $nCategoryId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->categories_model->save(array('com_order' => $nOrder), $nCategoryId);
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
	public function category_autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$this->load->model('categories_model');
				$aResult = $this->categories_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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
	
	
	public function category_upload_image($sImageType = 'picture')
	{
		if ($this->isPostMethod())
		{
			$sTempName = $this->input->post('temp_name');
			$sExt = $this->upload_image('image_file', './' . $this->config->item('temp_files_dir'), $sTempName, TRUE);
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
	
	public function category_crop_image($sImageType = 'picture')
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
	
	public function category_resize_image($sImageType = 'picture')
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
	
	public function category_rotate_image($sImageType = 'picture')
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

/* End of file adm_categories.php */
/* Location: ./application/controllers/adm_categories.php */