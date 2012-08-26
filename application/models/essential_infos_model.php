<?php

/**
 * Description of essential_infos_model
 * @author Sergii Lapin
 */
class Essential_infos_model extends CI_Model
{

	private $table = 'essential_infos';
	private $table_join = 'com_essential_info_id';
	private $table_fields = array('com_essential_info_id', 'com_active', 'com_order');

	private $subtable = '_essential_infos';
	private $subtable_join = 'essential_info_id';
	private $subtable_fields = array('essential_info_id', 'title', 'short_desc');
	
	private $spas_essential_infos_table = 'spas_essential_infos';
	private $spas_essential_infos_join = 'com_essential_info_id';

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации
	*
	* @param array $aEssential_infoData
	* @param int|array $aEssential_infoId
	* @return int
	*/
	function save($aEssential_infoData, $aEssential_infoId = NULL)
	{
		if ( $aEssential_infoId )
		{
			if ( is_array($aEssential_infoId) )
			{
				$this->db->where_in('com_essential_info_id', $aEssential_infoId);
			}
			else
			{
				$this->db->where('com_essential_info_id', $aEssential_infoId);
			}
			$this->db->update($this->table, $aEssential_infoData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aEssential_infoData);
			return $this->db->insert_id();
		}
	}

	/**
	* Добавление или редактироавние текстовой части, перевода
	*
	* @param string $sLang двохбуквенное обозначение языка en, ru, es, fr...
	* @param array $aEssential_infoData
	* @param int|array $aEssential_infoId
	* @return int
	*/
	function save_translate($sLang, $aEssential_infoData, $aEssential_infoId = NULL)
	{
		if ( $aEssential_infoId )
		{
			if ( is_array($aEssential_infoId) )
			{
				$this->db->where_in('essential_info_id', $aEssential_infoId);
			}
			else
			{
				$this->db->where('essential_info_id', $aEssential_infoId);
			}
			$this->db->update($sLang . $this->subtable, $aEssential_infoData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($sLang . $this->subtable, $aEssential_infoData);
			return $this->db->insert_id();
		}
	}

	/**
	* Удаление. И всех переводов
	*
	* @param int|array $aEssential_infoId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aEssential_infoId)
	{
		$aAffectedRows = array();
		if ( is_array($aEssential_infoId) )
		{
			$this->db->where_in('com_essential_info_id', $aEssential_infoId);
		}
		else
		{
			$this->db->where('com_essential_info_id', $aEssential_infoId);
		}
		$this->db->delete($this->table);
		$aAffectedRows['com'] = $this->db->affected_rows();
		$aAffectedRows['all'] = $aAffectedRows['com'];

		foreach ( $this->config->item('lang_uri_abbr') as $key => $value )
		{
			if ( is_array($aEssential_infoId) )
			{
				$this->db->where_in('essential_info_id', $aEssential_infoId);
			}
			else
			{
				$this->db->where('essential_info_id', $aEssential_infoId);
			}
			$this->db->delete($key . $this->subtable);
			$aAffectedRows[$key] = $this->db->affected_rows();
			$aAffectedRows['all'] = $aAffectedRows[$key];
		}
		
		if ( is_array($aEssential_infoId) )
		{
			$this->db->where_in($this->spas_essential_infos_join, $aEssential_infoId);
		}
		else
		{
			$this->db->where($this->spas_essential_infos_join, $aEssential_infoId);
		}
		$this->db->delete($this->spas_essential_infos_table);

		return $aAffectedRows;
	}

	/**
	* Удаление перевода
	*
	* @param string $sLang
	* @param int|array $aEssential_infoId
	* @return int
	*/
	function delete_translate($sLang, $aEssential_infoId)
	{
		if ( is_array($aEssential_infoId) )
		{
			$this->db->where_in('essential_info_id', $aEssential_infoId);
		}
		else
		{
			$this->db->where('essential_info_id', $aEssential_infoId);
		}
		$this->db->delete($sLang . $this->subtable);
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
	* Получение страны или списка стран, перевода
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_translate($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->from($sLang . $this->subtable);

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
	* Получение страны или списка стран, основной информации и перевода
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_joined($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->table . '.*');
		$this->db->select($sLang . $this->subtable . '.*');

		$this->db->from($this->table);
		$this->db->join($sLang . $this->subtable, $this->table . '.' . $this->table_join . ' = ' . $sLang . $this->subtable . '.' . $this->subtable_join, 'left');

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
	* Получение пункта важной инфо или списка пунктов важной инфо. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_joined_with_default_lang($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->table . '.*');
		$this->db->select($sLang . $this->subtable . '.*');

		$this->db->from($this->table);
		$this->db->join($sLang . $this->subtable, $this->table . '.' . $this->table_join . ' = ' . $sLang . $this->subtable . '.' . $this->subtable_join, 'left');
		
		foreach ($this->subtable_fields as $subtable_field)
		{
			$this->db->select('default_lang' . $this->subtable . '.' . $subtable_field . ' as `default_lang_' . $subtable_field . '`');
		}
		$this->db->join(LANGUAGE_ABBR_DEFAULT . $this->subtable . ' as default_lang' . $this->subtable, $this->table . '.' . $this->table_join . ' = default_lang' . $this->subtable . '.' . $this->subtable_join, 'left');

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
	 * Взять список статей для просмотра в админ. панели
	 *
	 * @param array $aAdditionalLang - дополнительные языки кроме основного. Основной берется из конфига (en)
	 * @param array $aFilters - фильтры отбора ( array(поле => значение,...) )
	 * @param boolean $bSingle - один или список (TRUE/FALSE)
	 * @param array|int $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	 * @param array $aOrder - сортировка ( array(поле => направление,...) )
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	 * @return array - одномерный/двумерный масив (один/список)
	 */
	public function get_adm_list($aAdditionalLang = array(), $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->table . '.*');

		$sMainLang = LANGUAGE_ABBR_DEFAULT;
		$this->db->select($sMainLang . $this->subtable . '.title as `' . $sMainLang . '_title`');
		$this->db->join($sMainLang . $this->subtable, $this->table . '.' . $this->table_join . ' = ' . $sMainLang . $this->subtable . '.' . $this->subtable_join, 'left');

		if ( is_array($aAdditionalLang) )
		{
			foreach ( $aAdditionalLang as $sLang )
			{
				if ( $sLang != $sMainLang )
				{
					$this->db->select($sLang . $this->subtable . '.title as `' . $sLang . '_title`');
					$this->db->join($sLang . $this->subtable, $this->table . '.' . $this->table_join . ' = ' . $sLang . $this->subtable . '.' . $this->subtable_join, 'left');
				}
			}
		}

		return $this->get($aFilters, $bSingle, $aLimit, $aOrder, $bFilterLike);
	}

	/**
	 * Количество строк в таблице удовлетворяющие фильтру
	 *
	 * @param array $aFilters
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	 * @return int
	 */
	public function get_count_adm_list($aFilters = false, $bFilterLike = FALSE)
	{
		$sMainLang = LANGUAGE_ABBR_DEFAULT;
		$this->db->from($this->table);
		$this->db->join($sMainLang . $this->subtable, $this->table . '.' . $this->table_join . ' = ' . $sMainLang . $this->subtable . '.' . $this->subtable_join, 'left');
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
	 * Удаление файлов связаных с важной инфо
	 * 
	 * @param int $nEssential_infoId
	 * @return void
	 */
	public function unlink_files($nEssential_infoId = '')
	{
		if ( ! $nEssential_infoId )
		{
			return;
		}
		$aEssential_infoData = $this->get(array('com_essential_info_id' => $nEssential_infoId), TRUE);
		if ( !$aEssential_infoData )
		{
			return;
		}
		$sPicturePath = './' . $this->config->item('essential_info_pictures_dir') . $nEssential_infoId . '.' . $aEssential_infoData['com_picture_ext'];
		if (file_exists($sPicturePath))
		{
			unlink($sPicturePath);
		}
		return;
	}

}

/* End of file essential_infos_model.php */
/* Location: ./application/models/essential_infos_model.php */