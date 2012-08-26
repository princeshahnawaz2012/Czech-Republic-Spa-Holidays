<?php

if (!defined('BASEPATH'))
        exit('No direct script access allowed');

require_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . "base.php");

class Draw extends Base_Controller
{

        function __construct()
        {
                parent::__construct();
                $this->load->model('draws_model');
        }

	/**
	 * Главная страница. на нее почти все редиректы
	 * @return <type>
	 */
        function index()
        {
		$this->title('All my draws | Groupjackpot');
		$this->template_var( array( 'orig_content'=>base_url('draw/listing/active') ) );
		return $this->view();
        }

	/**
	 * Вывод списка груп пользователя и указание его статуса в этих группах
	 * @param int $nSort
	 * @param <type> $sSortType
	 * @param <type> $nPage
	 * @return <type>
	 */
        function listing( $sListType='active',$nSort=1, $sSortType='up', $nPage=0)//down
        {
                $nUserId = $this->session->userdata('user_id');

		$aSortField = array( 1=>'gj_draws.lotto_corp', 2=>'gj_draws.draw_date',3=>'gj_draws.jackpot',
		    4=>'gj_draws.status', 5=>'gj_draw_users.role', );
		if ($nSort!=1 && $nSort!=2 && $nSort!=3 && $nSort!=4 && $nSort!=5)
		{
			$nSort=1;
		}
		if($sSortType=='down')
		{
			$aSort=array( $aSortField[$nSort]=>'desc');
			$sSortType='up';
		} else
		{
			$aSort=array( $aSortField[$nSort]=>'asc');
			$sSortType='down';
		}

		if($sListType=='active')
		{
			$aFilters=array('gj_draws.status < '=>DRAW_STATUS_CLOSED);
		} else 
		{
			$aFilters=array('gj_draws.status > '=>DRAW_STATUS_CLOSED);
			$sListType = 'completed';
		}

		$aFilters['gj_draw_users.user_id']=$nUserId;
                $this->load->library('pagination');
                $aConfig['uri_segment'] = 6;
                $aConfig['base_url'] = '#draw/listing/'.$sListType.'/'.$nSort.'/'.$sSortType;
                $aConfig['total_rows'] = $this->draws_model->get_count_draw($aFilters);;
                $aConfig['per_page']  = PER_PAGE;
                $aConfig['full_tag_open'] = '<span class="pagin">';
                $aConfig['full_tag_close'] = '</span>';
                $this->pagination->initialize($aConfig);
                $sPagin = $this->pagination->create_links();
		unset($aFilters['gj_draws.user_id']);

                $aDraws = $this->draws_model->get_user_draws($nUserId, array(PER_PAGE,$nPage), $aSort, $aFilters);
                $aRole = array(DRAW_USER_ADMIN => 'Administrator', DRAW_USER_USER => 'Member');
		$aStatus = array(
			DRAW_STATUS_OPENED => '<strong class="open">Open',
			DRAW_STATUS_PENDING => '<strong>Pending</strong>',
			DRAW_STATUS_COMPLETE => 'Complete',
			DRAW_STATUS_CLOSED => '<strong>Closed</strong>',
			DRAW_STATUS_CANCELLED => '<em>Cancelled</em>',
		);
		foreach ($aDraws as $key => $val)
		{
			if (strtotime($aDraws[$key]['open_until']) < time() && $aDraws[$key]['status'] == DRAW_STATUS_OPENED)
			{
				$aDraws[$key]['status'] = DRAW_STATUS_PENDING;
			}
		}

                $this->smarty->assign('aData', array('draws'=>$aDraws, 'role'=>$aRole, 'listtype'=>$sListType,
		    'pagin' => $sPagin,'sort'=>$sSortType, 'status'=>$aStatus) );

                return $this->view();
        }

        /**
         * Создание розыгрыша.
         * @return <type>
         */
        function create()
        {
                $this->title('Draw create | Groupjackpot');
                $nUserId = $this->session->userdata('user_id');

		$this->load->library('form_validation');
		$this->load->helper('draw_validation');
		$this->form_validation->set_rules('lotto_corp', 'Lotto corp', 'required|xss_clean');
		$this->form_validation->set_rules('draw_date', 'Draw Date', 'required|callback_DateCheck');
		$this->form_validation->set_rules('open_until_date', 'Open Until', 'required|callback_DateCheck');
		$this->form_validation->set_rules('Time_Hour', 'Open Until', 'required|is_natural');
		$this->form_validation->set_rules('Time_Minute', 'Open Until', 'required|is_natural');
		$this->form_validation->set_rules('Time_Meridian', 'Open Until', 'required');
		$this->form_validation->set_rules('open_until_time', 'Open Until', 'required');
		$this->form_validation->set_rules('jackpot', 'Jackpot Amount', 'required|is_natural');
                $this->form_validation->set_rules('ticket_cost', 'Cost Per Ticket', 'required|numeric');
		$this->form_validation->set_rules('email', 'PayPal E-mail address', 'required|valid_email');
		$bError = FALSE;
		if ( strtotime($this->input->post('draw_date')) < strtotime($this->input->post('open_until_date')) )
		{
		        $this->errors->set(UM_ERRORTYPE_ERROR, 'The Open Until Date must be before the draw date', 'open_until_date');
		        $bError = TRUE;
		}
		if ( $this->input->post('draw_date')!='' && strtotime($this->input->post('draw_date')) < time() )
		{
		        $this->errors->set(UM_ERRORTYPE_ERROR, 'Draw Date date is incorrect', 'draw_date');
		        $bError = TRUE;
		}
		$nHoerPlus = ( $this->input->post('Time_Meridian')=='pm' ) ? 12 : 0 ;
		$nHoer = ( ($this->input->post('Time_Hour')+$nHoerPlus) > 23 ) ? 23 : ($this->input->post('Time_Hour')+$nHoerPlus);
		$nMinute = ( $this->input->post('Time_Minute') > 59 ) ? 59 : $this->input->post('Time_Minute');
		$_POST['open_until_time'] =$nHoer .":".$nMinute.':00';
		if ( $this->input->post('email')!='' && ($this->form_validation->run() == FALSE || !$bError) )
		{
			$nvpStr='&EMAIL='.urlencode( $this->input->post('email') ).'&STREET=str&ZIP=43123';
			$aPay = PayPalAPI('AddressVerify', $nvpStr);
			if ($aPay['ACK']!='Success')
			{
				$this->errors->set(UM_ERRORTYPE_ERROR, urldecode( $aPay['L_LONGMESSAGE0'] ), 'email');
				$bError = TRUE;
			}
		}
		if ($this->form_validation->run() == FALSE || $bError)
		{
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('lotto_corp'), 'lotto_corp');
		        $this->errors->set(UM_ERRORTYPE_ERROR, form_error('draw_date'), 'draw_date');
		        $this->errors->set(UM_ERRORTYPE_ERROR, form_error('open_until_date'), 'open_until_date');
		        $this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Hour'), 'Time_Meridian');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Minute'), 'Time_Meridian');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Meridian'), 'Time_Meridian');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('jackpot'), 'jackpot');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('ticket_cost'), 'ticket_cost');
			$this->errors->set(UM_ERRORTYPE_ERROR, form_error('email'), 'email');
		        return $this->view();
		}
		else
		{
		        $nTime = $this->input->post('open_until_date').' '.$this->input->post('open_until_time');
		        $aData = array(
			    'admin_id' => $nUserId,
			    'lotto_corp' => $this->input->post('lotto_corp'),
		            'open_until' => $nTime,
			    'jackpot' => $this->input->post('jackpot'),
			    'ticket_cost' => $this->input->post('ticket_cost'),
			    'paypal_email' => $this->input->post('email'),
		            'draw_date' => $this->input->post('draw_date'),
			    'status' => DRAW_STATUS_OPENED,
		        );
		        $nDrawId = $this->draws_model->save($aData);
			$this->draws_model->connect_user($nDrawId, $nUserId, DRAW_USER_ADMIN);
		        redirect(base_url('/draw/details/'.$nDrawId.'/invite'), 'refresh');
		}
        }

        /**
         * Вывод подробностей розыгрыша
         * @param <type> $nDrawId
         */
        function details($nDrawId = FALSE, $bShowInvite = FALSE)
        {
		$this->load->model('tickets_model');
                $aDraw = $this->draws_model->get_draw_and_admin( $nDrawId );
                $nUserId = $this->session->userdata('user_id');
                $nRole = $this->draws_model->user_status( $nDrawId, $nUserId);
		$aCount = array();
                if ( $nRole )
                {
                        if (strtotime($aDraw['open_until']) < time() && $aDraw['status'] == DRAW_STATUS_OPENED)
                        {
                                $aDraw['status'] = DRAW_STATUS_PENDING;
                        }
			if ($aDraw['status']!=DRAW_STATUS_OPENED)
			{
				$aCount['ticket'] = $this->tickets_model->get_count_ticket($nDrawId);;
			}
                        $aStatus = array(
                            DRAW_STATUS_OPENED => 'Open',
                            DRAW_STATUS_PENDING => 'This draw is Pending',
                            DRAW_STATUS_COMPLETE => 'This draw has Completed',
                            DRAW_STATUS_CLOSED => 'This draw has Closed',
                            DRAW_STATUS_CANCELLED => 'This draw has Cancelled',
                        );
			$this->template_var( array( 'orig_content'=>base_url('draw/members/'.$nDrawId) ) );
                        $this->smarty->assign('aData', array('status'=>$aStatus,'draw'=>$aDraw,
                            'role' => $nRole, 'count' => $aCount, 'show_invite' => $bShowInvite ));
                        $this->title($aDraw['draw_date'].' draw details | Groupjackpot');
                        return $this->view();
                } else if ( $aDraw )
		{
			$this->load->model('users_model');
			$this->users_model->add_log($nUserId, $this->input->ip_address(), ATTEMPT_ACCESS);
		}
                $this->errors->set(UM_ERRORTYPE_ERROR, 'This page does not exist' );
                redirect(base_url(), 'refresh');
        }

        /**
         * Список всех пользователей
         * @param <type> $nDrawId
         * @return <type>
         */
        function members($nDrawId = NULL, $nSort=1, $sSortType='down', $nPage=0)
        {
                $nUserId = $this->session->userdata('user_id');
		$nRole = $this->draws_model->user_status( $nDrawId, $nUserId);
                if ( $nRole )
                {
                        $this->load->library('pagination');
                        $aConfig['uri_segment'] = 6;
                        $aConfig['base_url'] = '#draw/members/'.$nDrawId.'/'.$nSort.'/'.$sSortType;
                        $aConfig['total_rows'] = $this->draws_model->get_count_user_draws(array('draw_id'=>$nDrawId));
                        $aConfig['per_page']  = PER_PAGE;
                        $aConfig['full_tag_open'] = '<span class="pagin">';
                        $aConfig['full_tag_close'] = '</span>';

                        $aSortField = array(1=>'users.first_name');
                        if ( $nSort!=1 )
                        {
                                $nSort=1;
                        }
                        if($sSortType=='down')
                        {
                                $aSort=array( $aSortField[$nSort]=>'desc');
                                $sSortType='up';
                        } else
                        {
                                $aSort=array( $aSortField[$nSort]=>'asc');
                                $sSortType='down';
                        }
	
			$aInvites = array();
                        if ( $nRole == DRAW_USER_ADMIN)
                        {
                                $this->load->model('invites_model');
                                $aMembers = $this->draws_model->member_list($nDrawId,array(PER_PAGE,$nPage),$aSort);
                                $nInviteCount = $this->invites_model->get_count(array('draw_id'=>$nDrawId));
				if ( count($aMembers)<PER_PAGE)
                                {
                                        $aInvites = $this->invites_model->invite_list($nDrawId,array(PER_PAGE-count($aMembers),$nPage+count($aMembers)-$aConfig['total_rows']));
                                }
                                $aConfig['total_rows'] = $aConfig['total_rows'] + $nInviteCount;
                        } else
                        {
                                $aMembers = $this->draws_model->member_list($nDrawId,array(PER_PAGE,$nPage),$aSort);
                        }
	
			$this->pagination->initialize($aConfig);
                        $sPagin = $this->pagination->create_links();

			$this->load->model('payments_model');
			$aTickets = $this->payments_model->get_users_tisket_count( $nDrawId );
			$this->smarty->assign('aData', array( 'members' => $aMembers, 'drawid' => $nDrawId,
                            'pagin' => $sPagin,'sort'=>$sSortType, 'tickets' => $aTickets, 'invites'=>$aInvites,
			    'role' => $nRole) );
                        return $this->view();
                } else
                {
                $this->errors->set(UM_ERRORTYPE_ERROR, 'This page does not exist' );
                redirect(base_url(), 'refresh');
                }
        }

        /**
         * Отправка сообщения всем учасникам группы
         * @param <type> $nDrawId
         * @return <type>
         */
        function message($nDrawId = FALSE)
        {
                if ($nDrawId)
                {
                        $nUserId = $this->session->userdata('user_id');
                        $nRole = $this->draws_model->user_status( $nDrawId, $nUserId);
                        if ( $nRole == DRAW_USER_ADMIN )
                        {
                                $this->smarty->assign('aData', array('drawid'=>$nDrawId) );
                                $this->load->library('form_validation');
                                $this->form_validation->set_rules('msg', 'Message', 'required');
                                if ($this->form_validation->run() == FALSE)
                                {
                                        $this->errors->set(UM_ERRORTYPE_ERROR, form_error('msg'), 'msg');
                                        if ($_POST && $this->isAjaxRequest())
					{
						$aData['status'] = 'error';
						$aData['errors'] = array("msg" => form_error('msg') );
						$this->AjaxResponse($aData,TRUE);
					}
                                        return $this->view();
                                } else {
                                        $sMsg = $this->input->post('msg');
                                        $this->load->library('email');
                                        $sMailFrom = $this->config->item('email_site_address');
                                        $sNameFrom = $this->session->userdata('first_name').' '.$this->session->userdata('last_name');

                                        $aMembers = $this->draws_model->member_list( $nDrawId );
                                        $this->email->message( $sMsg );
                                        $this->email->from($sMailFrom, $sNameFrom);

                                        foreach ($aMembers as $aMember)
                                        {
						$this->email->subject('Msg from group');
                                                $this->email->to($aMember['email']);
                                                $this->email->send();
                                        }
                                        if ($this->isAjaxRequest())
					{
						$aData['status'] = 'ok';
						$aData['msg'] = "Messages send";
						$this->AjaxResponse($aData,TRUE);
					}
                                        $this->errors->set(UM_ERRORTYPE_MESSAGE, 'Messages send');
					redirect(base_url('draw/details/'.$nDrawId),'refresh');
                                }
                        } else 
			{
				$this->load->model('users_model');
				$this->users_model->add_log($nUserId, $this->input->ip_address(), ATTEMPT_ACCESS);
			}
                }
                redirect(site_url(), 'refresh');
        }

	/**
         * Отправка приглашений на почту
         * @param <type> $nDrawId
         * @return <type>
         */
        function invite($nDrawId = FALSE)
        {
                if ($nDrawId)
                {
			$aDraw = $this->draws_model->get( array('draw_id' => $nDrawId), TRUE );
			$nUserId = $this->session->userdata('user_id');
			if ( $aDraw['admin_id'] == $nUserId )
                        {
                                $this->smarty->assign('aData', array('drawid'=>$nDrawId) );
                                $this->load->library('form_validation');
                                $this->form_validation->set_rules('emails', 'Email Address', 'required');
                                if ($this->form_validation->run() == FALSE)
                                {
                                        $this->errors->set(UM_ERRORTYPE_ERROR, form_error('emails'), 'emails');
                                        if ($_POST && $this->isAjaxRequest())
					{
						$aData['status'] = 'error';
						$aData['errors'] = array("emails" => form_error('emails') );
						$this->AjaxResponse($aData,TRUE);
					}
                                        return $this->view();
                                } else {
                                        $sEmails = $this->input->post('emails');
                                        $this->load->model('invites_model');
                                        $this->load->library('email');

					//echo $sEmails;
					$sMask = "/.{1,}/i";
					preg_match_all($sMask, $sEmails, $aEmails);
					//print_r($aEmails);
					//die();
					
					foreach ($aEmails[0] as $sEmail)
					{
						$sMask = "/([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}/ix";
						preg_match_all($sMask, $sEmail, $aEmail);
						if (!$aEmail[0])
						{
							if ($this->isAjaxRequest())
							{
								$aData['status'] = 'error';
								$aData['errors'] = array("emails" => 'The E-mail field must contain a valid email address.' );
								$this->AjaxResponse($aData,TRUE);
							}
							$this->errors->set(UM_ERRORTYPE_ERROR, 'The E-mail field must contain a valid email address.', 'emails');
							return $this->view();
						}						
					}

                                       // $sMask = "/([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}/ix";
                                       // preg_match_all($sMask, $sEmails, $aEmails);

                                        $sMailFrom = $this->config->item('email_site_address');
                                        $sNameFrom = $this->session->userdata('first_name').' '.$this->session->userdata('last_name');

                                        $this->email->from($sMailFrom, $sNameFrom );
					$this->email->reply_to($this->session->userdata('email'), $sNameFrom);
                                        $nCount=0;
                                        foreach ($aEmails[0] as $sEmail)
                                        {
						$sMD5 = $this->invites_model->add($sEmail, $nDrawId);
						$this->email->subject("$sNameFrom has invited to participate in the Group Lotto draw on ".$aDraw['draw_date']);
                                                $this->email->to($sEmail);

						$this->smarty->assign('aData', array('name_from' => $sNameFrom, 'invite_link' => site_url('user/invite/'.$sMD5),
						    'draw_date' => $aDraw['draw_date']) );
						$sClassName = 'email/invite.tpl';
						$sMsg = $this->smarty->fetch($sClassName);

						$this->email->message($sMsg);
                                                if ( $this->email->send() )
                                                {
                                                        $nCount++;
                                                }
                                        }
                                }
                                if ($this->isAjaxRequest())
				{
					$aData['status'] = 'ok';
					$aData['msg'] = 'Your invites have been sent';
					$this->AjaxResponse($aData,TRUE);
				}
                                redirect(base_url('draw/details/'.$nDrawId),'refresh');
                        } else
			{
				$this->load->model('users_model');
				$this->users_model->add_log($nUserId, $this->input->ip_address(), ATTEMPT_ACCESS);
			}
                }
                redirect(site_url(), 'refresh');
        }

        /**
         * Удаление приглашения в группу
         * @param <type> $nDrawId
         * @param <type> $nInviteId
         */
        function uninvite($nDrawId = FALSE, $nInviteId = FALSE)
        {
                if ($nDrawId && $nInviteId)
                {
                        $nUserId = $this->session->userdata('user_id');
                        $nRole = $this->draws_model->user_status( $nDrawId, $nUserId);
                        if ( $nRole == DRAW_USER_ADMIN )
                        {
                                $this->load->model('invites_model');
                                $this->invites_model->delete(array('invite_id' => $nInviteId,'draw_id' => $nDrawId));
                                if ($this->isAjaxRequest())
				{
					$aData['status'] = 'ok';
					$this->AjaxResponse($aData,TRUE);
				}
                                redirect(site_url('draw/members/'.$nDrawId), 'refresh');
                        } else
			{
				$this->load->model('users_model');
				$this->users_model->add_log($nUserId, $this->input->ip_address(), ATTEMPT_ACCESS);
			}
                }
                if ($this->isAjaxRequest())
				{
					$aData['status'] = 'error';
					$this->AjaxResponse($aData,TRUE);
				}
                redirect(site_url(), 'refresh');
        }

        /**
         * Повторная отправка приглашения в группу
         * @param <type> $nDrawId
         * @param <type> $nInviteId
         */
        function reinvite($nDrawId = FALSE, $nInviteId = FALSE)
        {
                if ($nDrawId && $nInviteId)
                {
			$aDraw = $this->draws_model->get( array('draw_id' => $nDrawId), TRUE );
			$nUserId = $this->session->userdata('user_id');
			if ( $aDraw['admin_id'] == $nUserId )
                        {
                                $this->load->model('invites_model');
                                $aInvite = $this->invites_model->get(array('invite_id' => $nInviteId,'draw_id' => $nDrawId),TRUE);
                                $this->load->library('email');
				$config['wordwrap'] = FALSE;
				$this->email->initialize($config);
                                $sMailFrom = $this->config->item('email_site_address');
                                $sNameFrom = $this->session->userdata('first_name').' '.$this->session->userdata('last_name');

                                $this->email->subject("$sNameFrom has invited to participate in the Group Lotto draw on ".$aDraw['draw_date']);
                                $this->email->from($sMailFrom, $sNameFrom);
				$this->email->reply_to($this->session->userdata('email'), $sNameFrom);
                                $this->email->to($aInvite['email']);

				$this->smarty->assign('aData', array('name_from' => $sNameFrom, 'invite_link' => site_url('user/invite/'.$aInvite['activate_code']),
				    'draw_date' => $aDraw['draw_date']) );
				$sClassName = 'email/invite.tpl';
				$sMsg = $this->smarty->fetch($sClassName);

                                $this->email->message($sMsg);
                                if ($this->email->send())
                                {
                                        if ($this->isAjaxRequest())
					{
						$aData['status'] = 'ok';
						$this->AjaxResponse($aData,TRUE);
					}
                                        $this->errors->set(UM_ERRORTYPE_MESSAGE, 'Re-invite has been sent');
                                        redirect(site_url('draw/details/'.$nDrawId), 'refresh');
                                }
                        } else
			{
				$this->load->model('users_model');
				$this->users_model->add_log($nUserId, $this->input->ip_address(), ATTEMPT_ACCESS);
			}
                }
                if ($this->isAjaxRequest())
		{
			$aData['status'] = 'error';
			$this->AjaxResponse($aData,TRUE);
		}
                redirect(site_url(), 'refresh');
        }

	/**
	 * Формирование файла отчета о заказах
	 * @param <type> $nDrawId
	 */
        function pdf($nDrawId = NULL)
        {
                $this->load->model('payments_model');
                $this->load->model('draws_model');
                $nUserId = $this->session->userdata('user_id');
                $aDraw = $this->draws_model->get( array('draw_id' => $nDrawId),TRUE );
                if ( $nUserId == $aDraw['admin_id'] )
                {
                        $this->load->library('html_to_pdf');
                        $aMembers = $this->payments_model->order_list( $nDrawId );
                        $this->smarty->assign('aData', array('draw' => $aDraw, 'members' => $aMembers,) );
                        $sClassName = 'controllers/draw/pdf.tpl';
                        $sHtml = $this->smarty->fetch($sClassName);
                        //$sAuthor = $this->session->userdata('first_name').' '.$this->session->userdata('last_name');
                        //$sTitle = 'GroupJackpot draw '.$nDrawId;
			$sTitle = "";
			$sAuthor = $aDraw['lotto_corp'].':'
				.date("M d, Y",  strtotime($aDraw['draw_date']) )."\n"."\nJackpot: "
				.$aDraw['jackpot']."\n"."\n";
                        $sSubject = 'Draw orders list';
                        $sKeywords = 'GroupJackpot';
                        $sLogo = '../../../../images/logo.jpg';
                        $this->html_to_pdf->get_pdf( $sHtml, $sAuthor, $sTitle, $sSubject, $sKeywords, site_url(), $sLogo );
                        //get_pdf($html,$author,$title,$subject,$keywords,$siteurl,$logo)
                        //return $this->view();
                } else
                {
                        redirect(base_url(), 'refresh');
                }
        }

	/**
         * Редактирование розыгрыша
         * @param <type> $nDrawId
         * @return <type>
         */
        function edit( $nDrawId = NULL )
        {
                $this->title('Draw edit | Groupjackpot');
                $aDraw = $this->draws_model->get( array('draw_id' => $nDrawId), TRUE );
                $nUserId = $this->session->userdata('user_id');
                if ( $aDraw['admin_id'] == $nUserId )
                {
			$this->load->model('payments_model');
			$this->load->library('form_validation');
			$this->load->helper('draw_validation');
			$nOrder = $this->payments_model->get_count_order(array('draw_id'=>$nDrawId,'paid'=>ORDER_STATUS_PAID));
			$this->smarty->assign('aData', array('draw'=>$aDraw ) );
			if( $nOrder )
			{
				$this->form_validation->set_rules('open_until_date', 'Open Until', 'required|callback_DateCheck');
				$this->form_validation->set_rules('Time_Hour', 'Open Until', 'required|is_natural');
				$this->form_validation->set_rules('Time_Minute', 'Open Until', 'required|is_natural');
				$this->form_validation->set_rules('Time_Meridian', 'Open Until', 'required');
				$this->form_validation->set_rules('open_until_time', 'Open Until', 'required');
				$bError = FALSE;
				if ( strtotime($aDraw['draw_date']) < strtotime($this->input->post('open_until_date')) )
				{
					$this->errors->set(UM_ERRORTYPE_ERROR, 'The Open Until Date must be before the draw date', 'open_until_date');
					$bError = TRUE;
				}
				$nHoerPlus = ( $this->input->post('Time_Meridian')=='pm' ) ? 12 : 0 ;
				$nHoer = ( ($this->input->post('Time_Hour')+$nHoerPlus) > 23 ) ? 23 : ($this->input->post('Time_Hour')+$nHoerPlus);
				$nMinute = ( $this->input->post('Time_Minute') > 59 ) ? 59 : $this->input->post('Time_Minute');
				$_POST['open_until_time'] =$nHoer .":".$nMinute.':00';
				if ($this->form_validation->run() == FALSE || $bError)
				{
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('open_until_date'), 'open_until_date');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Hour'), 'Time_Meridian');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Minute'), 'Time_Meridian');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Meridian'), 'Time_Meridian');
					return $this->view('draw','edit_paid');
				} else
				{
					$nTime = $this->input->post('open_until_date').' '.$this->input->post('open_until_time');
					if ($aDraw['open_until'] != $nTime)
					{
						$aData = array(
						    'open_until' => $nTime,
						    'status' => DRAW_STATUS_OPENED,
						    );
						$this->draws_model->save($aData, $nDrawId);
					}
					redirect(base_url('draw/details/'.$nDrawId), 'refresh');					
				}
			} else
			{
				$this->form_validation->set_rules('lotto_corp', 'Lotto corp', 'required|xss_clean');
				$this->form_validation->set_rules('draw_date', 'Draw Date', 'required|callback_DateCheck');
				$this->form_validation->set_rules('open_until_date', 'Open Until', 'required|callback_DateCheck');
				$this->form_validation->set_rules('Time_Hour', 'Open Until', 'required|is_natural');
				$this->form_validation->set_rules('Time_Minute', 'Open Until', 'required|is_natural');
				$this->form_validation->set_rules('Time_Meridian', 'Open Until', 'required');
				$this->form_validation->set_rules('open_until_time', 'Open Until', 'required');
				$this->form_validation->set_rules('jackpot', 'Jackpot Amount', 'required|is_natural');
				$this->form_validation->set_rules('ticket_cost', 'Cost Per Ticket', 'required|numeric');

				if ( strtotime($this->input->post('draw_date')) < strtotime($this->input->post('open_until_date')) )
				{
					$this->errors->set(UM_ERRORTYPE_ERROR, 'The Open Until Date must be before the draw date', 'open_until_date');
					$bError = TRUE;
				}
				if ( $this->input->post('draw_date')!='' && strtotime($this->input->post('draw_date')) < time() )
				{
					$this->errors->set(UM_ERRORTYPE_ERROR, 'Draw Date date is incorrect', 'draw_date');
					$bError = TRUE;
				}
				$nHoerPlus = ( $this->input->post('Time_Meridian')=='pm' ) ? 12 : 0 ;
				$nHoer = ( ($this->input->post('Time_Hour')+$nHoerPlus) > 23 ) ? 23 : ($this->input->post('Time_Hour')+$nHoerPlus);
				$nMinute = ( $this->input->post('Time_Minute') > 59 ) ? 59 : $this->input->post('Time_Minute');
				$_POST['open_until_time'] =$nHoer .":".$nMinute.':00';
				if ($this->form_validation->run() == FALSE || $bError)
				{
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('lotto_corp'), 'lotto_corp');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('draw_date'), 'draw_date');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('open_until_date'), 'open_until_date');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Hour'), 'Time_Meridian');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Minute'), 'Time_Meridian');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('Time_Meridian'), 'Time_Meridian');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('jackpot'), 'jackpot');
					$this->errors->set(UM_ERRORTYPE_ERROR, form_error('ticket_cost'), 'ticket_cost');
					return $this->view();
				} else
				{
					$nTime = $this->input->post('open_until_date').' '.$this->input->post('open_until_time');
					$aData = array(
					    'lotto_corp' => $this->input->post('lotto_corp'),
					    'open_until' => $nTime,
					    'jackpot' => $this->input->post('jackpot'),
					    'ticket_cost' => $this->input->post('ticket_cost'),
					    'draw_date' => $this->input->post('draw_date'),
					    'status' => DRAW_STATUS_OPENED,
					);
					$this->draws_model->save($aData, $nDrawId);
					redirect(base_url('draw/details/'.$nDrawId), 'refresh');
				}

			}

                } elseif ($nDrawId)
		{
			$this->load->model('users_model');
			$this->users_model->add_log($nUserId, $this->input->ip_address(), ATTEMPT_ACCESS);
		}
                redirect(base_url(), 'refresh');
        }

	/**
	 * Отмена розыгрыша
	 * @param <type> $nDrawId
	 */
	function cancel($nDrawId = NULL)
	{
                $this->title('Draw cancel | Groupjackpot');
                $aDraw = $this->draws_model->get( array('draw_id' => $nDrawId), TRUE );
                $nUserId = $this->session->userdata('user_id');
                if ( $aDraw['admin_id'] == $nUserId )
                {
			$this->load->model('payments_model');
			$nOrder = $this->payments_model->get_count_order(array('draw_id'=>$nDrawId,'paid'=>ORDER_STATUS_PAID));
			$this->smarty->assign('aData', array('draw'=>$aDraw, 'num_order'=>$nOrder ) );
			return $this->view();
		} else
		{
			$this->load->model('users_model');
			$this->users_model->add_log($nUserId, $this->input->ip_address(), ATTEMPT_ACCESS);
		}
		redirect(base_url(), 'refresh');
	}

	/**
	 * Отмена розыгрыша
	 * @param <type> $nDrawId
	 */
	function cancelled($nDrawId = NULL)
	{
		$nUserId = $this->session->userdata('user_id');
		$aDraw = $this->draws_model->get( array('draw_id' => $nDrawId), TRUE );
                if ( $aDraw['admin_id'] == $nUserId && $aDraw['status'] != DRAW_STATUS_COMPLETE)
		{
			$this->load->model('payments_model');
			$aMembers = $this->payments_model->order_list($nDrawId);

			$this->load->library('email');
			$sMailFrom = $this->config->item('email_site_address');
			$sNameFrom = $this->session->userdata('first_name').' '.$this->session->userdata('last_name');

			$this->email->from($sMailFrom, $sNameFrom);

			foreach ($aMembers as $aMember)
			{
				$sMsg = 'The draw scheduled for '.$aDraw['draw_date'].' has been cancelled by '.$sNameFrom.'.'.
					'By cancelling the draw the administrator has acknowledged a refund for the '.$aMember['count_tickets'].' tickets.'.
					'Thanks for using groupjackpot.com, good luck on your next draw.';
				$this->email->message( $sMsg );
				$this->email->subject('Groupjackpot. Draw cancelled.');
				$this->email->to($aMember['email']);
				$this->email->send();
			}

			$this->draws_model->cancel($nDrawId);
			redirect(base_url('draw/details/'.$nDrawId), 'refresh');
		}
		$this->load->model('users_model');
		$this->users_model->add_log($nUserId, $this->input->ip_address(), ATTEMPT_ACCESS);
		redirect(base_url(), 'refresh');
	}

	/**
         * Удаление розыгрыша. Закоментированно.
         * @param <type> $nDrawId
         */
        function delete($nDrawId = NULL)
        {
                $aDraw = $this->draws_model->get( array('draw_id' => $nDrawId), TRUE );
                $nUserId = $this->session->userdata('user_id');
                if ( $aDraw['admin_id'] == $nUserId )
                {
                        //$this->draws_model->delete($nDrawId);
                        redirect(base_url('draw'), 'refresh');
                }
        }

}

/* End of file draw.php */
/* Location: ./groupjackpot/controllers/draw.php */