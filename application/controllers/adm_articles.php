<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_articles extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('articles_model');
	}

	/**
	 * Список всех статей
	 *
	 * @param int $nPerPage
	 * @param int $nOrder
	 * @param string $sDirect
	 * @param string $sFilter
	 * @param int $nOffset
	 */
	public function index($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~', $nOffset=0)
	{
		$this->load->helper('form');
		$this->javascript('adm/articles/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('sMainLang', LANGUAGE_ABBR_DEFAULT);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));


		$aOrders = array('com_article_id', LANGUAGE_ABBR_DEFAULT . '_title', 'com_hits', 'com_active', 'com_time', 'com_order');
		//количество колонок в таблице статей
		$nOrders = count($aOrders);

		$this->smarty->assign('nOrders', $nOrders);

		$nTime = time();
		$this->smarty->assign('nTime', $nTime);

		$aOrdersName = array(vlang('ID'), vlang('Title'), vlang('Hits'), vlang('Status'), vlang('Created'), vlang('Order'),);
		$aDirects = array('up'=>'asc', 'down'=>'desc');
		$aDirectsSuffixTitle = array('up' => '&triangle;', 'down' => '&triangledown;');
		$aDirectsLinkExchanger = array('up' => 'down', 'down' => 'up');

		$aOrderLinks = array();
		foreach ( $aOrders as $nKey => $sValue )
		{
			$aOrderLinks[$sValue] = anchor($this->router->class . '/' .$this->router->method . '/' . $nPerPage . '/' . $nKey . '/' . $sDirect . '/' . $sFilter . '/' . $nOffset, $aOrdersName[$nKey]);
		}
		$aOrderLinks[$aOrders[$nOrder]] = anchor($this->router->class . '/' .$this->router->method . '/' . $nPerPage . '/' . $nOrder . '/' . $aDirectsLinkExchanger[$sDirect] . '/' . $sFilter . '/' . $nOffset, $aOrdersName[$nOrder] . $aDirectsSuffixTitle[$sDirect]);
		$this->smarty->assign('aOrderLinks', $aOrderLinks);

		$aFilters = array(
			'articles.com_article_id' => '',
			LANGUAGE_ABBR_DEFAULT . '_articles.title' => '',
			'articles.com_active' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);

		$aConfig = array('total_rows' => '');

		$aArticles = array();

		if( $sFilter == '~~' )
		{
			if ( empty($nPerPage) )
			{
				$aArticles = $this->articles_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aArticles = $this->articles_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, FALSE, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->articles_model->get_count();
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
				$aArticles = $this->articles_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, $aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aArticles = $this->articles_model->get_adm_list(LANGUAGE_ABBR_DEFAULT, $aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->articles_model->get_count_adm_list($aFilters);
		}
		$this->smarty->assign('aArticles', $aArticles);
		//echo $this->db->last_query();
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
		
		$this->smarty->assign('sAddUrl', $this->router->class . '/add/');
		$this->smarty->assign('sActivateUrl', $this->router->class . '/activate/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/deactivate/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/delete/');
		
		//echo $nPerPage.' '.$nOffset.' '.$aOrders[$nOrder].' '.$aDirects[$sDirect].' '.$sFilter;
		$nCountAllArticles = $this->articles_model->get_count();
		$nCountInactiveArticles = $this->articles_model->get_count(array('com_active' => ARTICLE_STATUS_INACTIVE));
		$this->smarty->assign('nCountAllArticles', $nCountAllArticles);
		$this->smarty->assign('nCountInactiveArticles', $nCountInactiveArticles);
		$this->smarty->assign('nCountActiveArticles', $nCountAllArticles - $nCountInactiveArticles);

		$this->title(vlang('The articles'));
		$this->view();
	}

	public function activate($nArticleId = FALSE)
	{
		if ( ! $nArticleId )
		{
			redirect($this->router->class, 'refresh');
		}
		$aData = array(
			'com_active' => ARTICLE_STATUS_ACTIVE
		);
		if ( $nArticleId )
		{
			$this->articles_model->save($aData, $nArticleId);
			//redirect($this->router->class . '/index');
			redirect($_SERVER['HTTP_REFERER']);
		}
		else
		{
			$aArticleId = $this->input->post('aArticleId');
			if ( $aArticleId )
			{
				echo $this->articles_model->save($aData, $aArticleId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	public function deactivate($nArticleId = FALSE)
	{
		if ( ! $nArticleId )
		{
			redirect($this->router->class, 'refresh');
		}
		$aData = array(
			'com_active' => ARTICLE_STATUS_INACTIVE
		);
		if ( $nArticleId )
		{
			$this->articles_model->save($aData, $nArticleId);
			//redirect($this->router->class . '/index');
			redirect($_SERVER['HTTP_REFERER']);
		}
		else
		{
			$aArticleId = $this->input->post('aArticleId');
			if ( $aArticleId )
			{
				echo $this->articles_model->save($aData, $aArticleId);
			}
			else
			{
				///NOT FOUND 404
			}
		}
	}

	public function add()
	{
		$this->javascript('tiny_mce/jquery.tinymce.js');
		$this->load->library('form_validation');
		$this->javascript('adm/articles/tinymce.init.js');
		$this->form_validation->set_rules('title', vlang('Title'), 'required');
		$this->form_validation->set_rules('full', vlang('Article body'), 'required');
		$this->form_validation->set_rules('keywords', vlang('Meta keywords'), '');
		$this->form_validation->set_rules('description', vlang('Meta description'), '');
		$this->form_validation->set_rules('seo_link', vlang('SEO link suffix'), 'alpha_dash');
		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('full'), 'full');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('keywords'), 'keywords');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('description'), 'description');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('seo_link'), 'seo_link');
			
			$this->smarty->assign('sCancelUrl', $this->router->class);

			$this->title(vlang('Adding an article'));
			$this->view();
		}
		else
		{
			$nUserId = $this->session->userdata('user_id');
			$nTime = time();
			$aArticleData = array(
				'com_hits' => 0,
				'com_active' => ARTICLE_STATUS_ACTIVE,
				'com_time' => $nTime,
				'com_order' => 0,
			);
			$nArticleId = $this->articles_model->save($aArticleData);
			if ( $nArticleId )
			{
				$aArticleData = array(
					'article_id' => $nArticleId,
					'title' => $this->input->post('title'),
					'full' => $this->input->post('full'),
					'keywords' => $this->input->post('keywords'),
					'description' => $this->input->post('description'),
					'seo_link' => $this->input->post('seo_link'),
					'hits' => 0,
					'time' => $nTime,
					'author_id' => $nUserId,
					'editor_id' => NULL,
					'editing_end' => NULL,
				);
				$this->articles_model->save_translate($this->config->item('language_abbr'), $aArticleData);

				$aTemp = $this->config->item('lang_uri_abbr');
				foreach ( $aTemp as $sLangAbbr => $sLangFull )
				{
					if ( $sLangAbbr != $this->config->item('language_abbr') )
					{
						$aArticleData = array(
							'article_id' => $nArticleId,
							'title' => '',
							'full' => '',
							'keywords' => NULL,
							'description' => NULL,
							'seo_link' => NULL,
							'hits' => 0,
							'time' => $nTime,
							'author_id' => $nUserId,
							'editor_id' => NULL,
							'editing_end' => NULL,
						);
						$this->articles_model->save_translate($sLangAbbr, $aArticleData);
					}
				}
			}
			redirect('adm_articles/index');
		}
	}

	public function edit($nArticleId = FALSE)
	{
		if ( ! $nArticleId )
		{
			redirect($this->router->class, 'refresh');
		}
		$this->javascript('tiny_mce/jquery.tinymce.js');
		$this->load->library('form_validation');
		$this->javascript('adm/articles/tinymce.init.js');
		$this->form_validation->set_rules('title', vlang('Title'), 'required');
		$this->form_validation->set_rules('full', vlang('Article body'), 'required');
		$this->form_validation->set_rules('keywords', vlang('Meta keywords'), '');
		$this->form_validation->set_rules('description', vlang('Meta description'), '');
		$this->form_validation->set_rules('seo_link', vlang('SEO link suffix'), 'alpha_dash');
		$this->form_validation->set_rules('com_order', vlang('Order'), 'integer');
		$this->form_validation->set_rules('com_hits', vlang('Hits'), 'integer');
		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('full'), 'full');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('keywords'), 'keywords');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('description'), 'description');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('seo_link'), 'seo_link');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_order'), 'com_order');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('com_hits'), 'com_hits');
			$aArticleData = $this->articles_model->get_joined(LANGUAGE_ABBR_DEFAULT, array('article_id' => $nArticleId), TRUE);
			$this->smarty->assign('aArticleData', $aArticleData);
			$this->smarty->assign('sCancelUrl', $this->router->class);
			$this->title(vlang('Editing an article'));
			$this->view();
		}
		else
		{
			$aArticleData = array(
				'com_hits' => intval($this->input->post('com_hits')),
				'com_order' => intval($this->input->post('com_order')),
			);
			$this->articles_model->save($aArticleData, $nArticleId);
			
			$aArticleData = array(
				'title' => $this->input->post('title'),
				'full' => $this->input->post('full'),
				'keywords' => $this->input->post('keywords'),
				'description' => $this->input->post('description'),
				'seo_link' => $this->input->post('seo_link'),
			);
			$this->articles_model->save_translate(LANGUAGE_ABBR_DEFAULT, $aArticleData, $nArticleId);
			
			redirect($this->router->class, 'refresh');
		}
	}

	public function delete($nArticleId = FALSE)
	{
		if ( ! $nArticleId )
		{
			redirect($this->router->class, 'refresh');
		}
		$this->articles_model->delete($nArticleId);
		redirect($this->router->class, 'refresh');
	}
	
	/**
	 * Сохранения порядка(com_order) для статей через AJAX 
	 */
	public function order_save()
	{
		if ( $this->isAjaxRequest('POST') )
		{
			$nArticleId = $this->input->post('id');
			$nOrder = $this->input->post('order');
			if ($nArticleId !== FALSE && $nOrder !== FALSE)
			{
				if ( $this->articles_model->get_count(array('com_article_id' => $nArticleId, 'com_order' => $nOrder)) )
				{
					$this->AjaxResponse('ok', FALSE);
				}
				$nAffectedRows = $this->articles_model->save(array('com_order' => $nOrder), $nArticleId);
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
	public function autocomplete_title()
	{
		if ( $this->isAjaxRequest() )
		{
			$sTitle = $this->GETPOST('term');			
			if ($sTitle !== FALSE)
			{
				$aResult = $this->articles_model->get_translate(LANGUAGE_ABBR_DEFAULT, array('title' => $sTitle,), FALSE, NULL, NULL, TRUE);
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

/* End of file adm_articles.php */
/* Location: ./application/controllers/adm_articles.php */