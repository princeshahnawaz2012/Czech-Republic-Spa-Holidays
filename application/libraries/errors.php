<?php

define('UM_ERRORTYPE_ERROR', 1);
define('UM_ERRORTYPE_MESSAGE', 2);


/**
 * Errors
 *
 * Error handling system
 *
 * @version 1.0
 */
class Errors 
{
    private $aErrors;
    private $bErrorsFlag;
    private $class;
    private $method;
    private $oInstance;
    function __construct()
    {
        $this->oInstance = &get_instance();
	$this->class = $this->oInstance->router->class;
	$this->method = $this->oInstance->router->method;
        unset($oInstance);
        $this->aErrors = array();
	$this->bErrorsFlag = false;
    }    

    /**
     * set
     *
     * sets an error or message
     *
     * @param int $nType type
     * @param string $sMessage message content
     * @param string $sFieldName field to highlight
     * 
     *
     * @return mixed void or bool
     */
    public function set($nType, $sMessage, $sFieldName = NULL)
    {	
        foreach(func_get_args() as $k=>$val)
        {            
            if(!is_string($val) && !is_integer($val))
            {
                $this->set(UM_ERRORTYPE_ERROR, t("Argument is not a string, can't write to database."));
                return false;
            }
        }
        
        $sType = '';
        switch($nType)
        {
            case UM_ERRORTYPE_ERROR:
            {
		$this->bErrorsFlag = true;
                $sType = 'error';
            }break;
            case UM_ERRORTYPE_MESSAGE:
            {
                $sType = 'message';
            }break;
        }
	
        $aErrorInfo = array('message'=>$sMessage, 'controler'=>$this->class, 'method'=>$this->method, 'type'=>$sType, 'field'=>$sFieldName);

	if(!in_array($aErrorInfo, $this->aErrors))
	{
            $this->aErrors[] = $aErrorInfo;
            $this->oInstance->session->set_userdata('errors', $this->aErrors);
	}
	
    }
    /**
     * check
     *
     * Checks are there some errors
     * 
     * @return <bool>
     */
    public function check()
    {
        return $this->bErrorsFlag;
    }
    /**
     * get
     *
     * gets list of assigned errors
     * 
     * @param bool $bClear flag to clean errors data
     * 
     * @return array list of errors
     */
    public function get($bClear = false)
    {
        $this->aErrors;
        if($bClear)
        {
            $this->aErrors = array();

	    $this->oInstance->session->set_userdata('errors',array());
        }
        return $this->aErrors?$this->aErrors:false;
    }
    /**
     * show
     *
     * Assign error messages to view
     * 
     * @param object $oSmartyObject smarty instance of controler
     * @param bool $bClear flag to clear current errors
     *
     * @return bool  success
     */
    public function show(&$oSmartyObject, $bClear = true)
    {
        if(!$oSmartyObject->smarty)
        {
            $this->set(UM_ERRORTYPE_ERROR, t('Reference is not Smarty object in '.__FILE__.' on line '.__LINE__));
            return false;
        }
        if($this->aErrors)
        {
            $oSmartyObject->smarty->sysassign('aErrors', $this->aErrors);
        }
        else if($this->oInstance->session->userdata('errors'))
        {            
            $oSmartyObject->smarty->sysassign('aErrors', $this->oInstance->session->userdata('errors'));
        }        
        if($bClear)
        {
            $this->oInstance->session->set_userdata('errors',array());
            $this->aErrors = array();
        }
        $oSmartyObject->smarty->sysassign('sErrorBlock',$oSmartyObject->smarty->fetch('regions/block-messages.tpl'));

        return true;
    }
}