<?php

/**
 * Description of users_model
 * @author levada_sv
 */
class users_model extends CI_Model
{

	private $table = 'users';
	/* first_name last_name email password created age status */
	private $log_table = 'login_attempts';
	/* login_attempt_id user_email ip_address time_active */

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	 * Добавление или редактироавние пользователя
	 * @param <type> $aUserData
	 * @param <type> $nUserId
	 * @return <type>
	 */
	function save($aUserData, $nUserId=NULL)
	{
		if ($nUserId)
		{
			$this->db->where('user_id', $nUserId);
			$this->db->update($this->table, $aUserData);
		} else
		{
			$this->db->insert($this->table, $aUserData);
		}
		return $this->db->affected_rows();
	}

	/**
	 * Удаление пользвователя и записей о его вхождении в группы
	 * @param <type> $nUserId
	 * @return <type>
	 */
	function delete($nUserId)
	{
		$this->db->where('user_id', $nUserId);
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}

	/**
	 * Блокирование пользователя
	 * @param <type> $nUserId
	 * @return <type>
	 */
	function block($nUserId)
	{
		$this->db->where('user_id', $nUserId);
		$this->db->update($this->table, array('status' => USER_STATUS_BLOCKED));
		return $this->db->affected_rows();
	}

	/**
	 * Разблокировать пользователя
	 * @param <type> $nUserId
	 * @return <type>
	 */
	function unblock($nUserId)
	{
		$this->db->where('user_id', $nUserId);
		$this->db->update($this->table, array('status' => USER_STATUS_ACTIVE));
		return $this->db->affected_rows();
	}

	/**
	 * Проверка реквизитов доступа
	 * @param <type> $sLogin
	 * @param <type> $sPassword
	 * @return <type>
	 */
	function login($sLogin, $sPassword)
	{
		$this->db->from($this->table);
		$this->db->where('login', $sLogin);
		$this->db->where('password', $sPassword);
		$oQuery = $this->db->get();
		return $oQuery->row_array();
	}

	/**
	 * Проверка, есть ли такой email в системе
	 * @param <type> $sEmail
	 * @return <type>
	 */
	function email_exists($sEmail)
	{
		$this->db->from($this->table);
		$this->db->where('email', $sEmail);
		$oQuery = $this->db->get();
		if ($oQuery->num_rows())
		{
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Получение пользователя или списка пользователей
	 * @param <type> $aFilters - фильры отбора ( array(поле => значение,...) )
	 * @param <type> $bSingle - один пользователь или список (TRUE/FALSE)
	 * @param <type> $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	 * @param <type> $aOrder - сортировка ( array(поле => направление,...) )
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	 * @return <type> одномерный/двумерный масив (один пользователь/список пользователей)
	 */
	function get($aFilters = FALSE, $bSingle = FALSE, $aLimit = NULL, $aOrder = NULL, $bFilterLike = FALSE)
	{
		$this->db->from($this->table);

		if ( $aFilters )
		{
			if ( $bFilterLike )
			{
				foreach ( $aFilters as $sField => $sValue)
				{
					$this->db->like($sField, $sValue);
				}
			}
			else
			{
				$this->db->where($aFilters);
			}
		}
		if ($aLimit)
		{
			if (is_array($aLimit))
			{
				$this->db->limit($aLimit['0'], $aLimit['1']);
			} else if (is_numeric($aLimit))
			{
				$this->db->limit($aLimit);
			}
		}
		if ($aOrder)
		{
			foreach ($aOrder as $key => $value)
			{
				$this->db->order_by($key, $value); // $value = "desc"/"asc"
			}
		}
		$oQuery = $this->db->get();
		if ($bSingle)
		{
			return $oQuery->row_array();
		}
		return $oQuery->result_array();
	}
	
	/**
	 * Количество строк в таблице удовлетворяющие фильтру
	 *
	 * @param array $aFilters
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	 * @return int
	 */
	public function get_count($aFilters = false, $bFilterLike = FALSE)
	{
		$this->db->from($this->table);
		if ( $aFilters )
		{
			if ( $bFilterLike )
			{
				foreach ( $aFilters as $sField => $sValue)
				{
					$this->db->like($sField, $sValue);
				}
			}
			else
			{
				$this->db->where($aFilters);
			}
		}
		return $this->db->count_all_results();
	}	

	/**
	 * Добаление записи в login_attempts
	 * @param <type> $sUserEmail
	 * @param <type> $sIpAddress
	 * @return <type>
	 */
	function add_log($sUser, $sIpAddress ,$nType = 0)
	{
		$aData = array(
			'user' => $sUser,
			'ip_address' => $sIpAddress,
			'status' => $nType
		);
		$this->db->insert($this->log_table, $aData);
		return $this->db->affected_rows();
	}

	/**
	 * Удаление записей с login_attempts удовлетворяющих условию
	 * @param <type> $aFilters
	 * @return <type>
	 */
	function delete_log($aFilters)
	{
		if ($aFilters)
		{
			$this->db->where($aFilters);
			$this->db->delete($this->log_table);
			return $this->db->affected_rows();
		}
		return FALSE;
	}

}

/* End of file users_model.php */
/* Location: ./groupjackpot/models/users_model.php */