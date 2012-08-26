<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class Adm_maintain extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();		
	}

	public function index()
	{
		$this->view();
	}
}

/* End of file adm_maintain.php */
/* Location: ./application/controllers/adm_maintain.php */