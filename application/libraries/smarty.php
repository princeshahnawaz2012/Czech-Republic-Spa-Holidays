<?php  
    require_once( APPPATH.'/libraries/Smarty/Smarty.class.php');

    class CI_Smarty extends Smarty

    {
        private $class;
        private $method;
        private $oInstance;
        function __construct() 
        {
			parent::__construct();
                        $config = & get_config();
			$this->template_dir = $config['smarty_template_dir'];
			$this->compile_dir  = $config['smarty_compile_dir'];
			$this->config_dir = $config['smarty_config_dir'];
                        $this->cache_dir = $config['smarty_cache_dir'];
                        $this->force_compile = true;
                        $this->oInstance = &get_instance();
                        $this->class = $this->oInstance->router->class;
                        $this->method = $this->oInstance->router->method;
                        //$this->oInstance->load->helper('url');
                        if (function_exists('site_url')) {
                        // URL helper required                        
                                $this->sysassign("site_url", site_url()); // so we can get the full path to CI easily
                        }

		}
		function view($sTemplateDir = NULL, $sClassName = NULL, $sViewName = NULL, $params = array(), $templateName='main')
		{
			if(!$sClassName)
                                $sClassName = $this->class;
                            if(!$sViewName)
                                $sViewName = $this->method;

							/*
                            if($this->class == 'admin')
                            {
                                    $templateName = 'admin';
                            }
							*/
                            $sClassName = strtolower($sClassName);
                            $sViewName = strtolower($sViewName);

                            $sClassName = $sTemplateDir.'/'.$sClassName.'/'.$sViewName;

                            //$sRegionsDir = $this->class == 'admin'?'admin_regions':'regions';
							$sRegionsDir = 'regions';
                            $aFiles = scandir($this->template_dir.$sRegionsDir);

                            foreach($aFiles as $k=>$v)
                            {
                                if(stripos($v, '.')!==0)
                                {
                                    if(file_exists($this->template_dir.$sClassName.'-'.$v))
                                    {
                                        $sTypeName = str_replace('.tpl', '', $v);
                                        $this->sysassign('s'.$sTypeName.'Content', $this->fetch($sClassName.'-'.$v));
                                    }
                                }
                            }

                            if (strpos($sClassName, '.') === false) {
                                    $sClassName .= '.tpl';
                            }
                            if (strpos($templateName, '.') === false) {
                                    $templateName .= '.tpl';
                            }

                            if (is_array($params) && count($params)) {
                                    foreach ($params as $key => $value) {
                                            $this->assign($key, $value);
                                    }
                            }

                            $bFilePresent = true;
                            // check if the template file exists.
                            if (!is_file($this->template_dir . $sClassName)) {
                                $this->oInstance->errors->set(UM_ERRORTYPE_ERROR, "template: [$sClassName] cannot be found.");
                                $bFilePresent = false;
                            }
                            if (!is_file($this->template_dir . $templateName)) {
                                $this->oInstance->errors->set(UM_ERRORTYPE_ERROR, "template: [$templateName] cannot be found.");
                            }
                            
			    $sContent = NULL;
                            if($bFilePresent)
                                $sContent = $this->fetch($sClassName);

                            if($bFilePresent &&  Base_Controller::isAjaxRequest())
                            {
                                echo $sContent;
                                die;
                            }
                            else
                            {
                                $this->sysassign('content', $sContent);
                            }

                            $this->sysassign('aUserInfo', (array)$this->oInstance->session->userdata('user_data'));
                            $this->oInstance->errors->show($this->oInstance, true);
                            return parent::display($templateName);
		}
	}

?>