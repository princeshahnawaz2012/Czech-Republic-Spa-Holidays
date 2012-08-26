<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class admin extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
	}	
	
	public function index()
	{
		$this->title(vlang('Welcome to Engine of Site!'));
		$this->view();
	}
	
	/**
	 * Форма авторизации. После успешного логина _redirect_after_login()
	 */
	function login()
	{
		$this->title(vlang('Login'));
		if( $this->session->userdata('user_id') )
		{
			redirect(base_url(), 'refresh');
		}
		$this->load->library('form_validation');
		$this->form_validation->set_rules('login', vlang('Login'), 'required');
		$this->form_validation->set_rules('pass', vlang('Password'), 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('login'), 'login');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('pass'), 'pass');
			$this->view();
		} else
		{
			$sLogin = $this->input->post('login');
			$sPass = md5($this->input->post('pass') . $this->config->item('password_salt'));
			$aUser = $this->users_model->login($sLogin, $sPass);
			if ( ! $aUser )
			{
				$this->users_model->add_log($sLogin, $this->input->ip_address(), ATTEMPT_LOGIN);
				$this->errors->set(UM_ERRORTYPE_ERROR, vlang('Login or password is incorrect'));
				$this->view();
			}
			elseif ( $aUser['status'] == USER_STATUS_BLOCKED )
			{
				$this->errors->set(UM_ERRORTYPE_ERROR, vlang('This user is blocked'));
				$this->view();
			}
			else
			{
				$aUserData = array('last_login' => time());
				$this->users_model->save($aUserData,$aUser['user_id']);
				unset($aUser['created']);
				$this->session->set_userdata($aUser);
				redirect($this->_redirect_after_login(), 'refresh');
			}
		}
	}
	
	/**
	 * Выход
	 */
	function logout()
	{
		$this->session->sess_destroy();
		redirect(site_url('admin/login'), 'refresh');
	}	

	/**
	 * Востановление пароля
	 */
	function forgot()
	{
		
	}
	
}

/* End of file adm_user.php */
/* Location: ./application/controllers/adm_user.php */