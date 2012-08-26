<?php

/**
 * Description of suppliers_model
 * @author Sergii Lapin
 */
class Suppliers_model extends CI_Model
{

	private $table = 'suppliers';
	private $table_fields = array('com_supplier_id', 'com_order', 'com_title', 'com_office_contacts', 'com_bank_details', 'com_accounts_contact', 'com_accounts_email', 'com_contact_currency_id', 'com_transfers_calc_type', 'com_transfers_percent');

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации
	*
	* @param array $aSupplierData
	* @param int|array $aSupplierId
	* @return int
	*/
	function save($aSupplierData, $aSupplierId = NULL)
	{
		if ( $aSupplierId )
		{
			if ( is_array($aSupplierId) )
			{
				$this->db->where_in('com_supplier_id', $aSupplierId);
			}
			else
			{
				$this->db->where('com_supplier_id', $aSupplierId);
			}
			$this->db->update($this->table, $aSupplierData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aSupplierData);
			return $this->db->insert_id();
		}
	}


	/**
	* Удаление статьи. И всех ее переводов
	*
	* @param int|array $aSupplierId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aSupplierId)
	{
		if ( is_array($aSupplierId) )
		{
			$this->db->where_in('com_supplier_id', $aSupplierId);
		}
		else
		{
			$this->db->where('com_supplier_id', $aSupplierId);
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

/* End of file suppliers_model.php */
/* Location: ./application/models/suppliers_model.php */