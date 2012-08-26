<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "base.php");

class Feedback extends Base_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('articles_model');
	}


	/**
	 * Форма обратной связи
	 */
	public function index()
	{
		$this->load->library('form_validation');

		$sTitle = vlang('Title');
		$sFullName = vlang('Full Name');
		$sEmail = vlang('E-mail');
		$sTelephone = vlang('Telephone');
		$sEnquiry = vlang('Enquiry');

		$this->smarty->assign('sTitle', $sTitle);
		$this->smarty->assign('sFullName', $sFullName);
		$this->smarty->assign('sEmail', $sEmail);
		$this->smarty->assign('sTelephone', $sTelephone);
		$this->smarty->assign('sEnquiry', $sEnquiry);

		$this->form_validation->set_rules('title', $sTitle, 'required');
		$this->form_validation->set_rules('name', $sFullName, 'required');
		$this->form_validation->set_rules('email', $sEmail, 'required|valid_email');
		$this->form_validation->set_rules('phone', $sTelephone, 'required|numeric');
		$this->form_validation->set_rules('type', vlang('Feedback Type'), 'required');
		$this->form_validation->set_rules('enquiry', $sEnquiry, 'required');

		if ( $this->form_validation->run() === FALSE )
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('title'), 'title');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('name'), 'name');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('email'), 'email');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('phone'), 'phone');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('type'), 'type');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('enquiry'), 'enquiry');

			$nArticleId = 4;//какая-то статья для обратной связи!!!!!!!!!!

			$aFilters = array(
				'com_article_id' => $nArticleId,
				'com_active' => ARTICLE_STATUS_ACTIVE,
			);
			$bSingle = TRUE;
			$nLimit = 1;
			$aArticleData = $this->articles_model->get_joined_with_default_lang($this->config->item('language_abbr'), $aFilters, $bSingle, $nLimit);
			$this->articles_model->inc_hit($this->config->item('language_abbr'), $nArticleId);
			$this->title(flang($aArticleData, 'title'));
			$this->metakeywords(flang($aArticleData, 'keywords'));
			$this->metadescription(flang($aArticleData, 'description'));

			$this->smarty->assign('sArticleTitle', flang($aArticleData, 'title'));
			$this->smarty->assign('sArticleFull', flang($aArticleData, 'full'));

			$nArticleId = 5;//какая-то статья для обратной связи!!!!!!!!!!

			$aFilters = array(
				'com_article_id' => $nArticleId,
				'com_active' => ARTICLE_STATUS_ACTIVE,
			);
			$bSingle = TRUE;
			$nLimit = 1;
			$aArticleData = $this->articles_model->get_joined_with_default_lang($this->config->item('language_abbr'), $aFilters, $bSingle, $nLimit);
			$this->articles_model->inc_hit($this->config->item('language_abbr'), $nArticleId);


			$this->smarty->assign('sArticleFull2', flang($aArticleData, 'full'));////!!!!!!!!!!!!!!!

			$this->view();
		}
		else
		{
			$aType = array(
				'1' => vlang('General Enquiry'),
				'2' => vlang('Cancel or Amend Booking'),
				'3' => vlang('Programmes for Kids'),
				'4' => vlang('Feedback'),
			);
			$sTypeValue = $aType[$this->input->post('type')];
			$sTitleValue = $this->input->post('title');
			$sEmailValue = $this->input->post('email');
			$sNameValue = $this->input->post('name');
			$sTelephoneValue = $this->input->post('phone');
			$sEnquiryValue = nl2br($this->input->post('enquiry'));

			$this->smarty->assign('aNameValue', array('0' => $sNameValue,));
			$this->smarty->assign('sTypeValue', $sTypeValue);
			$this->smarty->assign('sTitleValue', $sTitleValue);
			$this->smarty->assign('sEmailValue', $sEmailValue);
			$this->smarty->assign('sTypeValue', $sTypeValue);
			$this->smarty->assign('sTelephoneValue', $sTelephoneValue);
			$this->smarty->assign('sEnquiryValue', $sEnquiryValue);
			$this->smarty->assign('sDateValue', date("Y-m-d", time()));

			$sClassName = 'email/contact_us.tpl';
			$sMessage = $this->smarty->fetch($sClassName);

			$sInfoEmail = $this->config->item('info_email');
			$sOrgTitle = vlang('Organization title');
			$sSubject = $sOrgTitle . ' - ' . $sEnquiry . ' - ' . $sTitleValue;

			$this->load->library('email');

			$config = array(
				'newline' => "\r\n",
				'mailtype' => "html",
			);
			$this->email->initialize($config);

			$this->email->from($sInfoEmail, $sOrgTitle);
			if ( $this->input->post('send_copy') !== FALSE )
			{
				$this->email->to($sEmailValue);
				$this->email->bcc($sInfoEmail);
			}
			else
			{
				$this->email->to($sInfoEmail);
			}
			$this->email->subject($sSubject);
			$this->email->message($sMessage);

			$this->email->send();

			//echo $sMessage;
			//!!!!!!!!нужно проверить на правильность при отправки
			redirect('feedback/success');
			//echo $sMessage;

		}
	}

	/**
	 * Вывод сообщения об удачном отправлении зарпоса
	 */
	public function success()
	{
		$nArticleId = 6;
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

/* End of file feedback.php */
/* Location: ./application/controllers/feedback.php */