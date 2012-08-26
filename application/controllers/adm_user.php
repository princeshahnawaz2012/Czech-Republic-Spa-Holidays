<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "adm_base.php");

class adm_user extends Adm_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('users_model');
		$this->smarty->assign('USER_STATUS_ALL', USER_STATUS_ALL);
		$this->smarty->assign('USER_STATUS_ACTIVE', USER_STATUS_ACTIVE);
		$this->smarty->assign('USER_STATUS_BLOCKED', USER_STATUS_BLOCKED);
		$this->smarty->assign('USER_ROLE_SUPER_ADMIN', USER_ROLE_SUPER_ADMIN);
		$this->smarty->assign('USER_ROLE_ADMIN', USER_ROLE_ADMIN);
		$this->smarty->assign('USER_ROLE_MANAGER', USER_ROLE_MANAGER);
		$this->smarty->assign('USER_ROLE_TRANSLATOR', USER_ROLE_TRANSLATOR);
	}	
	
	public function index()
	{
		redirect(site_url('adm_user/listing'), 'refresh');
		//$this->view();
	}

	function username_check($login)
	{
		if ($this->users_model->get(array('login'=>$login)))
		{
			$this->form_validation->set_message('username_check', 'This username is already in use');
			return FALSE;
		}
		else 
			return TRUE;
	}

	function add()
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('login', 'Login', 'required|min_length[5]|max_length[40]|callback_username_check');
		$this->form_validation->set_rules('fio', 'Full name', 'required');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('role', 'Role', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('perm', 'Permissions', '');
		$this->form_validation->set_rules('lang', 'Permissions', '');
		$this->form_validation->set_rules('pass', 'Password', 'required|min_length[6]|matches[passconf]');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', 'required');		
		if ($this->form_validation->run() === FALSE)
		{
			
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('login'), 'login');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('fio'), 'fio');	
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('email'), 'email');	
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('role'), 'role');	
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('lang'), 'lang');	
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('pass'), 'pass');
		
			$aRoleSelect = array();
			$aLangSelect = array();
			if($this->input->post())
			{
				$aLangSelect = $this->input->post('lang');
				$aRoleSelect = $this->input->post('perm');
			}
			$nRole = $this->session->userdata('role');
			if ($nRole==USER_ROLE_MANAGER)
			{
				$aRole = array(
					0 => '--' . vlang('select role') . '--',
					USER_ROLE_MANAGER => vlang('Manager'),
					USER_ROLE_TRANSLATOR => vlang('Translator'),
				);
			}
			else
			{
				$aRole = array(
					0 => '--' . vlang('select role') . '--',		
					USER_ROLE_ADMIN => vlang('Administrator'),
					USER_ROLE_MANAGER => vlang('Manager'),
					USER_ROLE_TRANSLATOR => vlang('Translator'),
				);				
			}
			$aMenuPerm = $this->config->item('admin_menu');
			foreach($aMenuPerm as $key => $value)
			{
				$aMenuP[$key] = $value['name'];
			}
			$aLang = $this->config->item('lang_uri_abbr');
			$this->smarty->assign('aData',array('role'=>$aRole, 'menu_perm'=>$aMenuP, 'lang'=>$aLang,
				'perm_select'=>$aRoleSelect, 'lang_select'=>$aLangSelect));
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/listing');
			$this->title(vlang('Adding a user'));
			$this->javascript('adm/users/user_role.js');
			$this->view();
		}
		else
		{
			$aLangSelect = $this->input->post('lang');
			$aRoleSelect = $this->input->post('perm');
			$nRole = $this->input->post('role');
			switch ( $nRole )
			{
				case USER_ROLE_ADMIN :
					$sPerm = array();
					break;
				case USER_ROLE_MANAGER :
					$sPerm = $aRoleSelect;
					break;
				case USER_ROLE_TRANSLATOR :
					$sPerm = $aLangSelect;
					break;
				default :
					$sPerm = array();
					$nRole = $this->session->userdata('role');
			}
			$sPermissions = json_encode($sPerm);
			$aUserData = array(
				'login' => $this->input->post('login'),
				'fio' => $this->input->post('fio'),
				'email' => $this->input->post('email'),
				'password' => md5($this->input->post('pass') . $this->config->item('password_salt')),
				'role' => $nRole,
				'permissions' => $sPermissions,
				'status' => USER_STATUS_ACTIVE,
				);	
			$this->users_model->save($aUserData);
			redirect(site_url('adm_user/listing'), 'refresh');
		}
	}
	
	function listing($nPerPage=25, $nOrder=0, $sDirect='down', $sFilter='~~~', $nOffset=0)
	{
		$this->javascript('adm/users/filter.js');
		$this->smarty->assign('nPerPage', $nPerPage);
		$this->smarty->assign('nOffset', $nOffset);
		$this->smarty->assign('nOrder', $nOrder);
		$this->smarty->assign('sDirect', $sDirect);
		$this->smarty->assign('sFilter', $sFilter);
		
		$this->smarty->assign('aPerPageVariables', $this->config->item('adm_per_page_variables'));
		
		$aFilters = array(
			'user_id' => '',
			'login' => '',
			'fio' => '',
			'status' => '',
		);
		$this->smarty->assign('aFilters', $aFilters);		

		$aOrders = array('user_id', 'login', 'fio', 'role', 'created', 'last_login', 'status');
		$aOrdersName = array('ID', 'User login', 'User name', 'User role', 'Create Time', 'Last login', 'Status');
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
		
		$this->smarty->assign('nOrders', count($aOrders));
		
		if( $sFilter == '~~~' )
		{
			if ( empty($nPerPage) )
			{
				$aUsers = $this->users_model->get(FALSE, FALSE,  FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]));
			}
			else
			{
				$aUsers = $this->users_model->get(FALSE, FALSE, array($nPerPage,$nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->users_model->get_count();
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
				$aUsers = $this->users_model->get($aFilters, FALSE, FALSE, array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}
			else
			{
				$aUsers = $this->users_model->get($aFilters, FALSE, array($nPerPage, $nOffset), array($aOrders[$nOrder] => $aDirects[$sDirect]) );
			}

			$aConfig['total_rows'] = $this->users_model->get_count($aFilters);
		}
		
		//$aUsers = $this->users_model->get();
		foreach ($aUsers as $key => $aUser)
		{
			$aPerm = json_decode($aUser['permissions']);
			$aUsers[$key]['perm'] = $aPerm;
		}
		$aRole = array(
			USER_ROLE_SUPER_ADMIN => vlang('Super Administrator'),
			USER_ROLE_ADMIN => vlang('Administrator'),
			USER_ROLE_MANAGER => vlang('Manager'),
			USER_ROLE_TRANSLATOR => vlang('Translator'),
		);
		$user_role = $this->session->userdata('role');
		$this->smarty->assign('aData',array('users'=>$aUsers, 'role'=>$aRole, 'user_role' => $user_role));

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
		$this->smarty->assign('sActivateUrl', $this->router->class . '/unblock/');
		$this->smarty->assign('sDeactivateUrl', $this->router->class . '/block/');
		$this->smarty->assign('sEditUrl', $this->router->class . '/edit/');
		$this->smarty->assign('sDeleteUrl', $this->router->class . '/delete/');
			
		
		$nCountAllUsers = $this->users_model->get_count();
		$nCountInactiveUsers = $this->users_model->get_count(array('status' => USER_STATUS_BLOCKED));
		$this->smarty->assign('nCountAllUsers', $nCountAllUsers);
		$this->smarty->assign('nCountInactiveUsers', $nCountInactiveUsers);
		$this->smarty->assign('nCountActiveUsers', $nCountAllUsers - $nCountInactiveUsers);
		$this->title(vlang('The users'));
		$this->view();
	}
	
	function edit($nUserId)
	{
		$this->load->library('form_validation');
		$this->form_validation->set_rules('fio', 'Full name', 'required');
		$this->form_validation->set_rules('email', 'E-mail', 'required|valid_email');
		$this->form_validation->set_rules('role', 'Role', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('perm', 'Permissions', '');
		$this->form_validation->set_rules('lang', 'Permissions', '');
		$this->form_validation->set_rules('pass', 'Password', 'min_length[6]|matches[passconf]');
		$this->form_validation->set_rules('passconf', 'Password Confirmation', '');		
		if ($this->form_validation->run() == FALSE)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('fio'), 'fio');	
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('email'), 'email');	
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('role'), 'role');	
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('lang'), 'lang');	
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('pass'), 'pass');
		
			$nRole = $this->session->userdata('role');
			$aUser = $this->users_model->get(array('user_id'=>$nUserId),TRUE);
			if ($nRole==USER_ROLE_MANAGER)
			{
				$aRole = array(
					0 => '--' . vlang('select role') . '--',
					USER_ROLE_MANAGER => vlang('Manager'),
					USER_ROLE_TRANSLATOR => vlang('Translator'),
				);
			}
			elseif ($nRole == USER_ROLE_SUPER_ADMIN && $aUser['user_id'] == $this->session->userdata('user_id'))
			{
				$aRole = array(
					USER_ROLE_SUPER_ADMIN => vlang('Super administrator'),
				);
			}
			else
			{
				$aRole = array(
					0 => '--' . vlang('select role') . '--',		
					USER_ROLE_ADMIN => vlang('Administrator'),
					USER_ROLE_MANAGER => vlang('Manager'),
					USER_ROLE_TRANSLATOR => vlang('Translator'),
				);				
			}
			$aRoleSelect = array();
			$aLangSelect = array();
			
			if($this->input->post())
			{
				$aLangSelect = $this->input->post('lang');
				$aRoleSelect = $this->input->post('perm');
			}
			else if ($aUser['role'] == USER_ROLE_MANAGER)
			{
				$aRoleSelect = json_decode($aUser['permissions']);
			}
			else if ($aUser['role'] == USER_ROLE_TRANSLATOR)
			{
				$aLangSelect = json_decode($aUser['permissions']);				
			}
			
			$aMenuPerm = $this->config->item('admin_menu');
			foreach($aMenuPerm as $key => $value)
			{
				$aMenuP[$key] = $value['name'];
			}		
			$aLang = $this->config->item('lang_uri_abbr');
			$this->smarty->assign('aData',array('role'=>$aRole, 'menu_perm'=>$aMenuP, 'lang'=>$aLang,
				'perm_select'=>$aRoleSelect, 'lang_select'=>$aLangSelect, 'user' => $aUser ));
			
			$this->smarty->assign('sCancelUrl', $this->router->class . '/listing');
			$this->title(vlang('Editing a user'));
			$this->javascript('adm/users/user_role.js');
			$this->view();
		} else
		{
			$aLangSelect = $this->input->post('lang');
			$aRoleSelect = $this->input->post('perm');
			$nRole = $this->input->post('role');
			switch ( $nRole )
			{
				case USER_ROLE_SUPER_ADMIN :
				case USER_ROLE_ADMIN :
					$sPerm = array();
					break;
				case USER_ROLE_MANAGER :
					$sPerm = $aRoleSelect;
					break;
				case USER_ROLE_TRANSLATOR :
					$sPerm = $aLangSelect;
					break;
				default :
					$sPerm = array();
					$nRole = $this->session->userdata('role');
			}
			$sPermissions = json_encode($sPerm);
			$aUserData = array(
				'fio' => $this->input->post('fio'),
				'email' => $this->input->post('email'),
				'role' => $nRole,
				'permissions' => $sPermissions,
				'status' => USER_STATUS_ACTIVE,
				);	
			if ($this->input->post('pass'))
			{
				$aUserData['password'] = md5($this->input->post('pass') . $this->config->item('password_salt'));
			}
			$this->users_model->save($aUserData,$nUserId);
			redirect(site_url('adm_user/listing'), 'refresh');
		}
	}
	
	function delete($nUserId)
	{
		$this->users_model->delete($nUserId);
		redirect(site_url('adm_user/listing'), 'refresh');			
	}
	
	function block($nUserId)
	{
		$this->users_model->block($nUserId);
		redirect(site_url('adm_user/listing'), 'refresh');		
	}
	
	function unblock($nUserId)
	{
		$this->users_model->unblock($nUserId);
		redirect(site_url('adm_user/listing'), 'refresh');		
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по логину 
	 */
	public function autocomplete_login()
	{
		if ( $this->isAjaxRequest() )
		{
			$sLogin = $this->GETPOST('term');			
			if ($sLogin !== FALSE)
			{
				$aResult = $this->users_model->get(array('login' => $sLogin,), FALSE, NULL, NULL, TRUE);
				$aResponse = array();
				foreach ( $aResult as $aRow )
				{
					$aResponse[] = $aRow['login'];
				}
				$this->AjaxResponse($aResponse, TRUE);
			}
		}
		$this->AjaxResponse('["' . vlang('Autocomplete error') . '"]', FALSE);
	}
	
	
	/**
	 * Генерация списка вариантов автозаполнения для фильтра по имени пользователя 
	 */
	public function autocomplete_username()
	{
		if ( $this->isAjaxRequest() )
		{
			$sUsername = $this->GETPOST('term');			
			if ($sUsername !== FALSE)
			{
				$aResult = $this->users_model->get(array('fio' => $sUsername,), FALSE, NULL, NULL, TRUE);
				$aResponse = array();
				foreach ( $aResult as $aRow )
				{
					$aResponse[] = $aRow['fio'];
				}
				$this->AjaxResponse($aResponse, TRUE);
			}
		}
		$this->AjaxResponse('["' . vlang('Autocomplete error') . '"]', FALSE);
	}
	
}

/* End of file adm_user.php */
/* Location: ./application/controllers/adm_user.php */