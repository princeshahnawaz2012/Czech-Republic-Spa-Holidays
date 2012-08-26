<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "base.php");

class Articles extends Base_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('articles_model');
	}
	
	public function index()
	{
		$this->id(1);
	}

		/**
	 * Вывод статьи
	 */
	public function id($nArticleId = FALSE)
	{
		if ( $nArticleId === FALSE )
		{
			$this->not_found();
		}
		
		$aFilters = array(
			'com_article_id' => $nArticleId,
			'com_active' => ARTICLE_STATUS_ACTIVE,
		);
		$bSingle = TRUE;
		$nLimit = 1;
		$aArticleData = $this->articles_model->get_joined_with_default_lang($this->config->item('language_abbr'), $aFilters, $bSingle, $nLimit);

		if ( ! flang($aArticleData, 'title') )
		{
			$this->not_found();
		}
			
		$this->articles_model->inc_hit($this->config->item('language_abbr'), $nArticleId);
		$this->title(flang($aArticleData, 'title'));
		$this->metakeywords(flang($aArticleData, 'keywords'));
		$this->metadescription(flang($aArticleData, 'description'));
		$this->smarty->assign('aArticleData', $aArticleData);
		$this->view();
		
	}
}

/* End of file articles.php */
/* Location: ./application/controllers/articles.php */