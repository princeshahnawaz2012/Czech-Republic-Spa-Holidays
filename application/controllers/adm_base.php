<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "base.php");
/**
 *      Adm_Controller
 *
 *      Filename: adm_base.php. Proto controller - all adm controllers must extend it.
 *
 *      @package controllers
 *      @version 1.0
 *
 */
class Adm_Controller extends Base_Controller
{
	public $aLangPermissions = array(); // languages permissions of user

    function __construct()
    {
		parent::__construct();
		$sUrl = $this->router->class.'/'.$this->router->method;
		$aUserMenu = array();
		$aUserLink = array('admin');
		if ( !$this->session->userdata('user_id') )
		{
			if ( !($sUrl == 'admin/login') )
			{
				$this->session->set_userdata(array(
					'redirect_after_login' => $this->uri->uri_string()
				));
				redirect(site_url('admin/login'), 'refresh');
			}
		}
		else
		{
			$nUserId = $this->session->userdata('user_id');
			$nRole = $this->session->userdata('role');
			$aAllMenu = $this->config->item('admin_menu');
			$aMenuLink = $this->config->item('admin_menu_link');
			if ($nRole == USER_ROLE_MANAGER)
			{
				$aPerm = json_decode($this->session->userdata('permissions'));
				foreach ($aPerm as $value)
				{
					$aUserMenu[$value] = $aAllMenu[$value];
					$aUserLink = array_merge($aUserLink,$aMenuLink[$value]);
				}

				$aTemp = $this->config->item('lang_uri_abbr');
				foreach ( $aTemp as $sLng => $sFullLng )
				{
					$this->aLangPermissions[] = $sLng;
				}

				if ( in_array($this->router->class, $aUserLink) )
				{
					;//все нормально, работаем.
				} else
				{	// или нет доступа, или на главную, в общем кудато.
					redirect(site_url('admin'), 'refresh');
				}
			}
			else if($nRole == USER_ROLE_TRANSLATOR)
			{
				$aUserMenu['trans'] = $aAllMenu['trans'];
				$this->aLangPermissions = json_decode($this->session->userdata('permissions'));
				if( in_array($this->router->class, array('admin','adm_trans')) )
				{
					;//все нормально, работаем.
				}
				else
				{
					redirect(site_url('admin'), 'refresh');
				}

			}
			else
			{
				$aUserMenu = $aAllMenu;

				$aTemp = $this->config->item('lang_uri_abbr');
				foreach ( $aTemp as $sLng => $sFullLng )
				{
					$this->aLangPermissions[] = $sLng;
				}
			}
			$this->smarty->assign('nUserId',$nUserId);
		}
		$this->smarty->assign('menu',$aUserMenu);
	}

    /**
     * _redirect_after_login
     *
     * gets a requested page from session and redirects to it after successfull login
     *
     */
    protected function _redirect_after_login()
    {
        $location = ($this->session->userdata('redirect_after_login')) ? $this->session->userdata('redirect_after_login') : site_url('admin');
        $this->session->unset_userdata('redirect_after_login');
        return $location;
    }

    /**
     * Displays a template
     *
     * @param <type> $sClassName folder name
     * @param <type> $sViewName file name
     * @param <type> $params additional params
     * @param <type> $templateName wrap template name
     */
	function view($sTemplateDir = 'adm', $sClassName = NULL, $sViewName = NULL, $aParams = array(), $sTemplateName='main_admin')
	{
		Base_Controller::view($sTemplateDir, $sClassName, $sViewName, $aParams, $sTemplateName);
	}

}

/* End of file adm_base.php */
/* Location: ./application/controllers/adm_base.php */