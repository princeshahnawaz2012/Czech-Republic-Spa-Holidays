<?php

/**
 * Description of supplements_model
 * @author Sergii Lapin
 */
class Supplements_model extends CI_Model
{

	private $table = 'supplements';
	private $table_fields = array('com_supplement_id', 'com_title', 'com_date_from', 'com_date_till', 'com_currency_id', 'com_price');

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации
	*
	* @param array $aSupplementData
	* @param int|array $aSupplementId
	* @return int
	*/
	function save($aSupplementData, $aSupplementId = NULL)
	{
		if ( $aSupplementId )
		{
			if ( is_array($aSupplementId) )
			{
				$this->db->where_in('com_supplement_id', $aSupplementId);
			}
			else
			{
				$this->db->where('com_supplement_id', $aSupplementId);
			}
			$this->db->update($this->table, $aSupplementData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aSupplementData);
			return $this->db->insert_id();
		}
	}


	/**
	* Удаление статьи. И всех ее переводов
	*
	* @param int|array $aSupplementId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aSupplementId)
	{
		if ( is_array($aSupplementId) )
		{
			$this->db->where_in('com_supplement_id', $aSupplementId);
		}
		else
		{
			$this->db->where('com_supplement_id', $aSupplementId);
		}
		$this->db->delete($this->table);
		return $this->db->affected_rows();
	}

	/**
	 * Получение страны или списка стран, основной информации
	 *
	 * @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	 * @param boolean $bSingle - один или список (TRUE/FALSE)
	 * @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	 * @param array $aOrder - сортировка ( array(поле => направление,...) )
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%' 
	 * @return array одномерный/двумерный масив (один/список)
	 */
	function get($aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
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
		if ( $aLimit )
		{
			if ( is_array($aLimit) )
			{
				$this->db->limit($aLimit['0'], $aLimit['1']);
			}
			elseif ( is_numeric($aLimit) )
			{
				$this->db->limit($aLimit);
			}
		}
		if ( $aOrder )
		{
			foreach ( $aOrder as $key => $value )
			{
				$this->db->order_by($key, $value); // $value = "desc"/"asc"
			}
		}
		$oQuery = $this->db->get();
		if ( $bSingle )
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

}

/* End of file supplements_model.php */
/* Location: ./application/models/supplements_model.php */