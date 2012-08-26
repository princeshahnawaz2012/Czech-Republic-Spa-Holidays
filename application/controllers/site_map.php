<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "base.php");

class Site_map extends Base_Controller
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$aLinks = array(
			array(
				'title' => vlang('Home'),
				'link' => site_url() . '/',
			),
		);
		
		$this->load->model('articles_model');
		
		$aArticles = $this->articles_model->get_joined_with_default_lang($this->config->item('language_abbr'), array('com_active' => ARTICLE_STATUS_ACTIVE));
		
		foreach ( $aArticles as $aArticle )
		{
			$aLinks[] = array(
				'title' => flang($aArticle, 'title'),
				'link' => site_url() . '/articles/id/' . $aArticle['com_article_id'] . '/' . flang($aArticle, 'seo_link'),
			);
		}
		
		$this->smarty->assign('aLinks', $aLinks);
		$this->smarty->assign('sTitle', vlang('Site Map'));
		$this->view();
	}
}

/* End of file site_map.php */
/* Location: ./application/controllers/site_map.php */