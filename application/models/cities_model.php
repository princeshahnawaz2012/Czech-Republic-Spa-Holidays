<?php

/**
 * Description of cities_model
 * @author Sergii Lapin
 */
class Cities_model extends CI_Model
{

	private $table = 'cities';
	private $table_join = 'com_city_id';
	private $table_fields = array('com_city_id', 'com_order', 'com_flag_ext', 'com_emblem_ext', 'com_map_ext', 'com_region_id');

	private $subtable = '_cities';
	private $subtable_join = 'city_id';
	private $subtable_fields = array('city_id', 'title', 'desc', 'flag_label', 'emblem_label');

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации
	*
	* @param array $aCityData
	* @param int|array $aCityId
	* @return int
	*/
	function save($aCityData, $aCityId = NULL)
	{
		if ( $aCityId )
		{
			if ( is_array($aCityId) )
			{
				$this->db->where_in('com_city_id', $aCityId);
			}
			else
			{
				$this->db->where('com_city_id', $aCityId);
			}
			$this->db->update($this->table, $aCityData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aCityData);
			return $this->db->insert_id();
		}
	}

	/**
	* Добавление или редактироавние текстовой части города, перевода
	*
	* @param string $sLang двохбуквенное обозначение языка en, ru, es, fr...
	* @param array $aCityData
	* @param int|array $aCityId
	* @return int
	*/
	function save_translate($sLang, $aCityData, $aCityId = NULL)
	{
		if ( $aCityId )
		{
			if ( is_array($aCityId) )
			{
				$this->db->where_in('city_id', $aCityId);
			}
			else
			{
				$this->db->where('city_id', $aCityId);
			}
			$this->db->update($sLang . $this->subtable, $aCityData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($sLang . $this->subtable, $aCityData);
			return $this->db->insert_id();
		}
	}

	/**
	* Удаление статьи. И всех ее переводов
	*
	* @param int|array $aCityId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aCityId)
	{
		$aAffectedRows = array();
		if ( is_array($aCityId) )
		{
			$this->db->where_in('com_city_id', $aCityId);
		}
		else
		{
			$this->db->where('com_city_id', $aCityId);
		}
		$this->db->delete($this->table);
		$aAffectedRows['com'] = $this->db->affected_rows();
		$aAffectedRows['all'] = $aAffectedRows['com'];

		foreach ( $this->config->item('lang_uri_abbr') as $key => $value )
		{
			if ( is_array($aCityId) )
			{
				$this->db->where_in('city_id', $aCityId);
			}
			else
			{
				$this->db->where('city_id', $aCityId);
			}
			$this->db->delete($key . $this->subtable);
			$aAffectedRows[$key] = $this->db->affected_rows();
			$aAffectedRows['all'] = $aAffectedRows[$key];
		}

		return $aAffectedRows;
	}

	/**
	* Удаление перевода
	*
	* @param string $sLang
	* @param int|array $aCityId
	* @return int
	*/
	function delete_translate($sLang, $aCityId)
	{
		if ( is_array($aCityId) )
		{
			$this->db->where_in('city_id', $aCityId);
		}
		else
		{
			$this->db->where('city_id', $aCityId);
		}
		$this->db->delete($sLang . $this->subtable);
		return $this->db->affected_rows();
	}

	/**
	* Получение города или списка городов, основной информации
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
	* Получение города или списка городов, перевода
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
	* Получение города или списка городов, основной информации и перевода
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
	* Получение города или списка городов. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
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
		
		//Отборка названия региона, в котором находится город
		$this->db->select($sMainLang . '_regions.title as `' . $sMainLang . '_region_title`');
		$this->db->join($sMainLang . '_regions', $this->table . '.com_region_id' . ' = ' . $sMainLang . '_regions.region_id', 'left');
		//Отборка названия страны, в которой находится город исходя из региона
		$this->db->select($sMainLang . '_countries.title as `' . $sMainLang . '_country_title`');
		$this->db->join('regions', $this->table . '.com_region_id' . ' = regions.com_region_id', 'left');
		$this->db->join($sMainLang . '_countries', 'regions.com_country_id' . ' = ' . $sMainLang . '_countries.country_id', 'left');

		
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
		
		//Отборка названия региона, в котором находится город
		$this->db->select($sMainLang . '_regions.title as `' . $sMainLang . '_region_title`');
		$this->db->join($sMainLang . '_regions', $this->table . '.com_region_id' . ' = ' . $sMainLang . '_regions.region_id', 'left');
		//Отборка названия страны, в которой находится город исходя из региона
		$this->db->select($sMainLang . '_countries.title as `' . $sMainLang . '_country_title`');
		$this->db->join('regions', $this->table . '.com_region_id' . ' = regions.com_region_id', 'left');
		$this->db->join($sMainLang . '_countries', 'regions.com_country_id' . ' = ' . $sMainLang . '_countries.country_id', 'left');
		
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
	 * Удаление файлов связаных с городом
	 * 
	 * @param int $nCityId
	 * @return void
	 */
	public function unlink_files($nCityId = '')
	{
		if ( ! $nCityId )
		{
			return;
		}
		$aCityData = $this->get(array('com_city_id' => $nCityId), TRUE);
		if ( !$aCityData )
		{
			return;
		}
		$sMapPath = './' . $this->config->item('city_maps_dir') . $nCityId . '.' . $aCityData['com_map_ext'];
		if (file_exists($sMapPath))
		{
			unlink($sMapPath);
		}
		$sEmblemPath = './' . $this->config->item('city_emblems_dir') . $nCityId . '.' . $aCityData['com_emblem_ext'];
		if (file_exists($sEmblemPath))
		{
			unlink($sEmblemPath);
		}
		$sFlagPath = './' . $this->config->item('city_flags_dir') . $nCityId . '.' . $aCityData['com_flag_ext'];
		if (file_exists($sFlagPath))
		{
			unlink($sFlagPath);
		}
		return;
	}

}

/* End of file cities_model.php */
/* Location: ./application/models/cities_model.php */