<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 *      Base_Controller
 *
 * 	Filename: base.php. Proto controller - all controllers must extend it.
 *
 *      @package controllers
 *      @version 1.0
 *
 */
class Base_Controller extends CI_Controller
{

    /**
     *
     * @var array 
     */
    var $aBreadCrumbs;


    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('smarty');
        $this->load->library('session');
        $this->load->library('errors');
//		$this->javascript('jquery-1.7.min.js');
//		$this->javascript('jquery-ui-1.8.11.custom.min.js');
	//	$this->javascript('jquery.history.js');
	//	$this->javascript('lib.js');
//		$this->stylesheet('style.css');
        $this->smarty->assign('site_url', site_url().'/');
        $this->smarty->assign('sUri', $this->uri->uri_string());
		$this->smarty->assign('language', $this->config->item('language'));
		$this->smarty->assign('language_abbr', $this->config->item('language_abbr'));
		$this->smarty->assign('info_phone', $this->config->item('info_phone'));
		$this->smarty->assign('info_email', $this->config->item('info_email'));
		$this->lang->load('main');
		
		if ($this->isWindows())
		{
			if ($this->isWebkit())
			{
				$this->stylesheet('webkit-windows.css');
			}
			elseif ($this->isOpera())
			{
				$this->stylesheet('opera-windows.css');
			}
		}

/*
        $sUrl = $this->router->class.'/'.$this->router->method;
        if ( !$this->session->userdata('user_id') )
        {
		$bLogin= FALSE;
		if ($this->isAjaxRequest())
		{
			$aData['status'] = 'error_login';
			$this->AjaxResponse($aData,TRUE);
		}
                if ( !($sUrl == 'user/login' || $sUrl == 'user/register' || $sUrl == 'user/forgot' || $sUrl == 'user/invite' || $sUrl == 'paypal/ipn' ) )
                {
                        $this->session->set_userdata(array(
                                'redirect_after_login' => $this->uri->uri_string()
                        ));
                        redirect(base_url('user/login'), 'refresh');
                }
        }
        else{
                $bLogin = TRUE;
                $nUserId = $this->session->userdata('user_id');
                $this->smarty->assign('nUserId',$nUserId);
        }
        $this->smarty->assign('bLogin',$bLogin);
*/
     }

    /**
     * _redirect_after_login
     * 
     * gets a requested page from session and redirects to it after successfull login
     *
     */
    protected function _redirect_after_login()
    {
        $location = ($this->session->userdata('redirect_after_login')) ? $this->session->userdata('redirect_after_login') : "";
        $this->session->unset_userdata('redirect_after_login');
        return $location;
    }

    /**
     * 
     * javascript
     * 
     * Sets or assigns javascript includes
     * 
     * @staticvar string $sIncludeScripts string that contain all added scripts in html format
     * @staticvar array $aAddedScripts used for storing all scripts to reduce duplicates
     * @param type $sPath path to script
     * 
     * @return void | false false if file doesn't exists
     */
    protected function javascript($sPath = NULL)
    {
        static $sIncludeScripts, $aAddedScripts;
        if (!$sPath)
        {
            $this->smarty->sysassign('sScripts', $sIncludeScripts);
            return true;
        }
        if (!$aAddedScripts)
            $aAddedScripts = array();

        if (!in_array($sPath, $aAddedScripts))
        {
            $aAddedScripts[] = $sPath;
            if (strpos($sPath, 'http://') === false)
            {
                $sPath = base_url() . 'js/' . $sPath;
            }


            if (!$sIncludeScripts)
                $sIncludeScripts = '<script type="text/javascript" src="' . $sPath . '"></script>
';
            else
                $sIncludeScripts .= '<script type="text/javascript" src="' . $sPath . '"></script>
';
        }
    }

    /**
     *
     * stylesheet
     * 
     * Sets or assigns css files includes
     *
     * @staticvar string $sIncludeScripts string that contain all added scripts in html format
     * @staticvar array $aAddedScripts used for storing all scripts to reduce duplicates
     * @param type $sPath path to file
     *
     * @return void | false false if file doesn't exists
     */
    protected function stylesheet($sPath = NULL)
    {
        static $sIncludeScripts, $aAddedScripts;
        if (!$sPath)
        {
            $this->smarty->sysassign('sStyles', $sIncludeScripts);
            return true;
        }
        if (!$aAddedScripts)
            $aAddedScripts = array();

        if (!in_array($sPath, $aAddedScripts))
        {
            $aAddedScripts[] = $sPath;
            if (strpos($sPath, 'http://') === false)
            {
                $sPath = base_url() . 'css/' . $sPath;
            }



            if (!$sIncludeScripts)
                $sIncludeScripts = '<link rel="stylesheet" href="' . $sPath . '" type="text/css" />
';
            else
                $sIncludeScripts .= '<link rel="stylesheet" href="' . $sPath . '" type="text/css" />
';
        }
    }

    /**
     *
     * title
     * 
     * Sets or assigns a site title
     * 
     * @staticvar string $title current title
     * @param string $sTitle title to set
     *
     * @return current site title
     */
    protected function title($sTitle = NULL)
    {
        static $title;
        if (!$sTitle)
            $this->smarty->sysassign('sSiteTitle', $title);
        else
            $title = $sTitle;

        return $title;
    }

    /**
     *
     * metakeywords
     * 
     * Sets or assigns a site meta keywords
     *
     * @staticvar string $title current keywords
     * @param string $sTitle keywords to set
     *
     * @return current keywords
     */
    protected function metakeywords($sKeys = NULL)
    {
        static $keys;
        if (!$sKeys)
            $this->smarty->sysassign('sMetaKeywords', $keys);
        else
            $keys = $sKeys;

        return $keys;
    }

    /**
     *
     * metadescription
     * 
     * Sets or assigns a site meta description
     *
     * @staticvar string $title current description
     * @param string $sTitle description to set
     *
     * @return current description
     */
    protected function metadescription($sText = NULL)
    {
        static $text;
        if (!$sText)
            $this->smarty->sysassign('sMetaDescription', $text);
        else
            $text = $sText;

        return $text;
    }

    /**
     *
     * check_permissions
     * 
     * @param char $sSymbol
     * 
     * @return bool access
     */
    protected function check_permissions($sSymbol)
    {
        return true;
    }

    /**
     * access_denied
     * 
     * shows 403 page
     */
    protected function access_denied()
    {
        //http_send_status(403);
        if ($this->isAjaxRequest())
        {
            $aResult = array();
            $aResult['error'] = 1;
            $aResult['mesage'] = t('You have no permissions for it');
            $this->AjaxResponse($aResult, 1);
        } else
        {
            $this->smarty->view('pages', 'forbidden');
        }
    }

    /**
     * 
     * 
     * shows 404 page
     */
    protected function not_found()
    {
//        http_send_status(404);
//		$this->smarty->view('pages', 'notfound');
		$this->title(vlang('404. That\'s an error.'));
		$sCurUrl = '/' . $this->config->item('language_abbr') . $this->uri->uri_string();
		$this->smarty->assign('sMessage', vlang('The requested URL   was not found on this server. That\'s all we know.', array($sCurUrl,)));
		$this->view('controllers', 'pages', 'notfound');
		die();
    }

    /**
     *
     * isAjaxRequest
     * 
     * Checks is page requested via ajax
     *
     * @param string $mMethod expected method
     *
     * @return bool
     */
    static function isAjaxRequest($mMethod = false)
    {
        return
                (isset($_SERVER['HTTP_X_REQUESTED_WITH']) ?
                        $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' && ( ($mMethod) ? $_SERVER['REQUEST_METHOD'] == $mMethod : true ) : false );
    }

    /**
     *
     * AjaxResponse
     * 
     * response data to ajax request
     * 
     * @param mixed $oData data to response
     * @param bool $bIsJSON flag to conevert to json
     */
    static function AjaxResponse($oData, $bIsJSON)
    {
        echo ($bIsJSON ? json_encode($oData) : $oData);
        die;
    }

    /**
     *
     * getBreadCrumbs
     * 
     * sets or displays a breadcrumbs
     *
     * @param mixed $aTempBreadCrumbs breadcrumbs item or NULL
     * 
     */
    protected function getBreadCrumbs($aTempBreadCrumbs = null)
    {
        if ($aTempBreadCrumbs)
        {
            $this->aBreadCrumbs[] = $aTempBreadCrumbs;
        } else
        {
            return $this->aBreadCrumbs;
        }

        $this->smarty->sysassign('aBreadCrumbs', $this->aBreadCrumbs);
    }

    /**
     *
     * isPostMethod
     *
     * checks is there some data in POST
     *
     * @return boolean
     */
    protected function isPostMethod()
    {
        return!empty($_POST);
    }

    /**
     *
     * POST
     * 
     * returns value of post field
     *
     * @param string $sName field name
     * @param bool $bIntval flag to convert value to integer
     * @param bool $bTrim flag to trim value
     * 
     * @return string
     */
    protected function POST($sName, $bIntval = false, $bTrim = false)
    {
        return $bIntval ? intval($_POST[$sName]) : ($bTrim ? trim($_POST[$sName]) : $_POST[$sName]);
    }

    /**
     *
     * GET
     * 
     * returns value of GET field
     *
     * @param string $sName field name
     * @param bool $bIntval flag to convert value to integer
     * @param bool $bTrim flag to trim value
     *
     * @return string
     */
    protected function GET($sName, $bIntval = false, $bTrim = false)
    {
        return $bIntval ? intval($_GET[$sName]) : ($bTrim ? trim($_GET[$sName]) : $_GET[$sName]);
    }

    /**
     *
     * POSTGET
     * 
     * returns value of POST or GET field
     *
     * @param string $sName field name
     * @param bool $bIntval flag to convert value to integer
     * @param bool $bTrim flag to trim value
     *
     * @return string
     */
    protected function POSTGET($sName, $bIntval = false, $bTrim = false)
    {
        $sValue = isset($_POST[$sName]) ? $_POST[$sName] : $_GET[$sName];
        return $bIntval ? intval($sValue) : ($bTrim ? trim($sValue) : $sValue);
    }

    /**
     *
     * GETPOST
     * 
     * returns value of GET or POST field
     *
     * @param string $sName field name
     * @param bool $bIntval flag to convert value to integer
     * @param bool $bTrim flag to trim value
     *
     * @return string
     */
    protected function GETPOST($sName, $bIntval = false, $bTrim = false)
    {
        $sValue = isset($_GET[$sName]) ? $_GET[$sName] : $_POST[$sName];
        return $bIntval ? intval($sValue) : ($bTrim ? trim($sValue) : $sValue);
    }

    /**
     *
     * resize
     *
     * Resize of image
	 * Before use this function you must check library path of ImageMagick
     *
     * @param string $file_input input file
     * @param string $file_output output file
     * @param int $w_o new width
     * @param int $h_o hew height
	 * @param bool $bOnlyZoomOut TRUE - resize image only for zoom out
     * @return bool success
     */
    protected function resize($file_input, $file_output, $w_o, $h_o, $bOnlyZoomOut = FALSE, $bMaintainRatio = FALSE)
    {
        list($w_i, $h_i, $type) = getimagesize($file_input);
        if (!$w_i || !$h_i)
        {
            return;
        }
		
		if (!$h_o)
            $h_o = $w_o / ($w_i / $h_i);
        if (!$w_o)
            $w_o = $h_o / ($h_i / $w_i);
		
		if ( $bOnlyZoomOut && ( $w_i < $w_o || $h_i < $h_o ) )
		{
			if ( $file_input == $file_output )
			{
				return TRUE;
			}
			return copy($file_input, $file_output);
		}
		
        $types = array('', 'gif', 'jpeg', 'png');
        $ext = $types[$type];
		
		if ( !$ext )
		{
			return '';
		}
		
//        if ($ext)
//        {
//            $func = 'imagecreatefrom' . $ext;
//            $img = $func($file_input);
//        } else
//        {
//            //echo $type;
//            return;
//        }
//
//        $img_o = imagecreatetruecolor($w_o, $h_o);
//        imagecopyresampled($img_o, $img, 0, 0, 0, 0, $w_o, $h_o, $w_i, $h_i);
//        if ($type == 2)
//        {
//            return imagejpeg($img_o, $file_output, 100);
//        } else
//        {
//            $func = 'image' . $ext;
//            return $func($img_o, $file_output);
//        }
		
		$aConfig = array();
//		$aConfig['image_library'] = 'imagemagick';
//		$aConfig['library_path'] = '/usr/bin/';
		$aConfig['maintain_ratio'] = $bMaintainRatio;
		$aConfig['quality'] = '100%';
		$aConfig['source_image']	= $file_input;
		if ( $file_input != $file_output )
		{
			$aConfig['new_image'] = $file_output;
		}
		$aConfig['width'] = $w_o;
		$aConfig['height'] = $h_o;
		$this->load->library('image_lib', $aConfig);
		if ( $this->image_lib->resize() )
		{
			return $ext;
		}
		return '';
    }
	
	/**
	 * Image cropping using ImageMagick library
	 * Before use this function you must check library path of ImageMagick
	 * 
	 * @param string $sFileInput input file path
	 * @param int $nW required width
	 * @param int $nH required height
	 * @param int $nX required offset by axis X
	 * @param int $nY required offset by axis Y
	 * @param string $sFileOutput output file path (optional)
	 * @return bool TRUE - success cropping, FALSE - fail cropping
	 */
	function crop($sFileInput, $nW, $nH, $nX, $nY, $sFileOutput = '')
	{
		$aConfig = array();
//		$aConfig['image_library'] = 'imagemagick';
//		$aConfig['library_path'] = '/usr/bin/';
		$aConfig['maintain_ratio'] = FALSE;
		$aConfig['quality'] = '100%';
		$aConfig['source_image']	= $sFileInput;
		if ( $sFileOutput )
		{
			$aConfig['new_image'] = $sFileOutput;
		}
		$aConfig['x_axis'] = $nX;
		$aConfig['y_axis'] = $nY;
		$aConfig['width'] = $nW;
		$aConfig['height'] = $nH;
		$this->load->library('image_lib', $aConfig);
		return $this->image_lib->crop();
	}
	
	/**
	 * Image rotation clockwise using ImageMagick library
	 * Before use this function you must check library path of ImageMagick
	 * 
	 * @param string $sFileInput input file path
	 * @param int|string $sAngle required angle or state ver|hor
	 * @param string $sFileOutput output file path (optional)
	 * @return bool TRUE - success rotation, FALSE - fail rotation
	 */
	function rotate($sFileInput, $sAngle, $sFileOutput = '')
	{
		$aConfig = array();
//		$aConfig['image_library'] = 'imagemagick';
//		$aConfig['library_path'] = '/usr/bin/';
		$aConfig['maintain_ratio'] = FALSE;
		$aConfig['quality'] = '100%';
		$aConfig['source_image']	= $sFileInput;
		if ( $sFileOutput )
		{
			$aConfig['new_image'] = $sFileOutput;
		}
		$aConfig['rotation_angle'] = $sAngle;
		$this->load->library('image_lib', $aConfig);
		return $this->image_lib->rotate();
	}
    
    
    /**
     *	image_sizes()
     *  Generates images of pre-defined sizes,
     *  resizing image with name $filename.
     * 
     *  Output names are generated adding prefixes to $basename.
     * 
     *  @param string $filename - full path to image.
     *  @param string $basename - string that is used as name
     * 			All prefixes are added to the beginning of this name
     *  @param string $user_prefix - this string is added to the
     * 			beginning of final imagename. 
     * 
     *  @param string $purpose - string constant {'review'|'avatar'}
     * 			It defines which set of sizes to use 
     * 			and which directory to put.
	 *
	 * 	Final image name is generated this way:
	 * 		$user_prefix.<size_prefix>.$basename
     */
    protected function image_sizes($filename, $basename, $user_prefix, $purpose = 'review')
    {
		$image_sizes = 
			array(
				'review' =>
					array('mi' => 23, 'sm' => 63, 'ft' => 80, 'md'=>92),
				'avatar' =>
					array('mi' =>23, 'sm' => 63, 'md' => 92),
			);
		$locations = array(
				'review' => 'uploads/review/',
				'avatar' => 'uploads/avatars/',
				);
		
		$sizes = $image_sizes[ $purpose ];
		$dir = $locations[ $purpose ];
		
		if ( !($sizes && $dir))
		{
			return;
		}

		list($w, $h, $type) = getimagesize($filename);
        if (!$w || !$h)
        {
            return;
        }
        
        $ext = image_type_to_extension($type, false);
        if ($ext)
        {
            $func = 'imagecreatefrom' . $ext;
            $img_fl = $func($filename);
        } 
        else
        {
            //echo $type;
            return;
        }

		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
        $sq_size = min($w, $h);
        $img_square = imagecreatetruecolor($sq_size, $sq_size);
        
		$start_x = floor(($w - $sq_size)/2); 
		$start_y = floor(($h - $sq_size)/2);
		
		imagecopy($img_square, $img_fl, 0, 0, $start_x, $start_y, 
					$sq_size, $sq_size);
		
		foreach ($sizes as $size_prefix => $one_size)
		{
			$img_o = imagecreatetruecolor($one_size, $one_size);
			imagecopyresampled($img_o, $img_square, 0, 0, 0, 0, $one_size, $one_size, $sq_size, $sq_size);
			
			$output_name = $dir.$user_prefix.$size_prefix.$basename; 			
			if ($type == IMAGETYPE_JPEG)
			{
				$result = imagejpeg($img_o, $output_name, 100);
			} 
			else
			{
				$func = 'image' . $ext;
				$result =  $func($img_o, $output_name);
			}
		
						
			imagedestroy($img_o);	
			if (!$result) return;
			
			unset ($output_name);
		}
		unset($one_size);

		//writing original image		
		$output_name = $dir.$user_prefix.'fl'.$basename; 			
		if ($type == IMAGETYPE_JPEG)
		{
			$result = imagejpeg($img_fl, $output_name, 100);
		} 
		else
		{
			$func = 'image' . $ext;
			$result =  $func($img_fl, $output_name);
		}
		imagedestroy($img_fl);	
		if (!$result) return;

		return true;
		 
	}			

    /**
     *
     * help_content
     * 
     * Sets or gets the help content for page
     * 
     * @param string $sContent help content
     * @param string $sPage page identifyer
     * @return bool/string content if get, success if set
     */
    protected function help_content($sContent = NULL, $sPage = NULL)
    {

        if ($sPage)
        {
            $aPath = explode('-', $sPage);
            $sPage = $aPath[0] . '-' . $aPath[1];
        } else
        {
            $sPage = $this->router->class . '-' . $this->router->method;
        }

        if (!$sContent)
        {
            return $this->help_system_model->get_help_content($sPage);
        } else
        {
            $this->help_system_model->set_help_content($sContent, $sPage);
        }
    }

    /**
     *
     * template_var
     * 
     * Assign a variable to template
     *
     * @param array $aVariable name=>value variable to assign
     */
    protected function template_var($aVariable = NULL)
    {
        static $aVariables;
        if (!$aVariables)
            $aVariables = array();
        if (is_array($aVariable))
        {
            foreach ($aVariable as $k => $v)
            {
                if (is_array($v) || is_object($v))
                    $aVariables[$k] = json_encode($v);
                else if (is_string($v))
                    $aVariables[$k] = '"' . $v . '"';
                else if (is_bool($v))
                    $aVariables[$k] = intval($v);
                else if (!$v)
                    $aVariables[$k] = '""';
                else
                    $aVariables[$k] = $v;
            }
        }
        else
        {
            $this->smarty->sysassign('aTemplateVar', $aVariables);
        }
    }

    protected function set_future_message($msg, $type='success')
    {
        $this->session->set_userdata('message-' . $type, $msg);
    }

    protected function get_future_message($type='success')
    {
        $msg = $this->session->userdata('message-' . $type);
        $this->session->unset_userdata('message-' . $type);
        return $msg;
    }

    protected function clear_future_messages()
    {
        $this->session->unset_userdata('message-error');
        $this->session->unset_userdata('message-success');
    }

    protected function generate_key($width)
    {
        $arr = array('a', 'b', 'c', 'd', 'e', 'f',
            'g', 'h', 'i', 'j', 'k', 'l',
            'm', 'n', 'o', 'p', 'r', 's',
            't', 'u', 'v', 'x', 'y', 'z',
            'A', 'B', 'C', 'D', 'E', 'F',
            'G', 'H', 'I', 'J', 'K', 'L',
            'M', 'N', 'O', 'P', 'R', 'S',
            'T', 'U', 'V', 'X', 'Y', 'Z');
        $key = "";
        for ($i = 0; $i < $width; $i++)
        {
            $index = rand(0, count($arr) - 1);
            $key .= $arr[$index];
        }
        return $key;
    }

    /**
     *
     * Displays a template
     *
     * @param <type> $sClassName folder name
     * @param <type> $sViewName file name
     * @param <type> $params additional params
     * @param <type> $templateName wrap template name
     */
    function view($sTemplateDir = 'controllers', $sClassName = NULL, $sViewName = NULL, $aParams = array(), $sTemplateName='main')
    {
            $this->javascript();
            $this->stylesheet();
            $this->title();
            $this->metakeywords();
            $this->metadescription();
            $this->template_var();
            $this->smarty->view($sTemplateDir, $sClassName, $sViewName, $aParams, $sTemplateName);
    }
	
	/**
	 * Upload image to destination direcroty and resize it
	 * 
	 * @param string $sFieldName
	 * @param string $sDestDir
	 * @param string $sNewName
	 * @param bool $bResize
	 * @param int $nWidth
	 * @param int $nHeight
	 * @param bool $bOnlyZoomOut
	 * @return string|null 
	 */
	protected function upload_image($sFieldName, $sDestDir, $sNewName, $bResize = FALSE, $nWidth = 0, $nHeight = 0, $bOnlyZoomOut = FALSE, $bMaintainRatio = FALSE)
	{
		$config = array();
		$config['upload_path'] = './uploads/';
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$this->load->library('upload', $config);
		$aExt = array(
			'image/jpeg' => 'jpg',
			'image/pjpeg' => 'jpg',
			'image/gif' => 'gif',
			'image/png' => 'png',
			'image/x-png' => 'png'
		);
		if ( $this->upload->do_upload($sFieldName) )
		{
			$aUploadData = $this->upload->data();
			$sImageExt = $aExt[$aUploadData['file_type']];
			if ( $bResize )
			{
				$bResult = $this->resize('./uploads/' . $aUploadData['file_name'], $sDestDir . $sNewName . '.' . $sImageExt, $nWidth, $nHeight, $bOnlyZoomOut, $bMaintainRatio);
			}
			else
			{
				$bResult = $this->resize('./uploads/' . $aUploadData['file_name'], $sDestDir . $sNewName . '.' . $sImageExt, $aUploadData['image_width'], $aUploadData['image_height']);
			}
			@unlink('./uploads/' . $aUploadData['file_name']);
			if ( $bResult )
			{
				return $sImageExt;
			}
		}
		return NULL;
	}
	
	static function isWindows()
	{
		return strpos($_SERVER['HTTP_USER_AGENT'], 'Windows');
	}
	
	static function isLinux()
	{
		return strpos($_SERVER['HTTP_USER_AGENT'], 'Linux');
	}
	
	static function isMac()
	{
		return strpos($_SERVER['HTTP_USER_AGENT'], 'Mac OS');
	}
	
	
	
	static function isOpera()
	{
		return strpos(' ' .$_SERVER['HTTP_USER_AGENT'], 'Opera');
	}
	
	static function isWebkit()
	{
		return strpos($_SERVER['HTTP_USER_AGENT'], 'AppleWebKit');
	}
	
	static function isFirefox()
	{
		return strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox');
	}
	
	/**
	 * 
	 * @param string $sVersion such as 5.5 or 9.0
	 * @return bool
	 */
	static function isMSIE($sVersion = '')
	{
		return strpos('MSIE' . ' ' . $sVersion, $_SERVER['HTTP_USER_AGENT']);
	}

}
