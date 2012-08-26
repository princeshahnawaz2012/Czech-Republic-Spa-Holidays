<?php

/**
 * Description of spas_model
 * @author Sergii Lapin
 */
class Spas_model extends CI_Model
{

	private $table = 'spas';
	private $table_join = 'com_spa_id';
	private $table_fields = array('com_spa_id', 'com_city_id', 'com_active', 'com_order');

	private $subtable = '_spas';
	private $subtable_join = 'spa_id';
	private $subtable_fields = array('spa_id', 'title');
	
	
	
	private $essential_info_table = 'spas_essential_infos';
	private $essential_info_join = 'com_spa_id';
	private $essential_info_sub_join = 'com_essential_info_id';
	private $essential_info_fields = array('com_spa_id', 'com_essential_info_id');
	
	private $essential_info_maintable = 'essential_infos';
	private $essential_info_maintable_join = 'com_essential_info_id';
	private $essential_info_maintable_fields = array('com_essential_info_id', 'com_order', 'com_active');
	
	private $essential_info_subtable = '_essential_infos';
	private $essential_info_subtable_join = 'essential_info_id';
	private $essential_info_subtable_fields = array('essential_info_id', 'title', 'short_desc');
	
	
	
	private $medical_treatment_table = 'spas_medical_treatments';
	private $medical_treatment_join = 'com_spa_id';
	private $medical_treatment_sub_join = 'com_medical_treatment_id';
	private $medical_treatment_fields = array('com_spa_id', 'com_medical_treatment_id');
	
	private $medical_treatment_maintable = 'medical_treatments';
	private $medical_treatment_maintable_join = 'com_medical_treatment_id';
	private $medical_treatment_maintable_fields = array('com_medical_treatment_id', 'com_order', 'com_active');
	
	private $medical_treatment_subtable = '_medical_treatments';
	private $medical_treatment_subtable_join = 'medical_treatment_id';
	private $medical_treatment_subtable_fields = array('medical_treatment_id', 'title', 'short_desc');
	
	
	
	private $facility_table = 'spas_facilities';
	private $facility_join = 'com_spa_id';
	private $facility_sub_join = 'com_facility_id';
	private $facility_fields = array('com_spa_id', 'com_facility_id');
	
	private $facility_maintable = 'facilities';
	private $facility_maintable_join = 'com_facility_id';
	private $facility_maintable_fields = array('com_facility_id', 'com_order', 'com_active');
	
	private $facility_subtable = '_facilities';
	private $facility_subtable_join = 'facility_id';
	private $facility_subtable_fields = array('facility_id', 'title', 'short_desc');

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации
	*
	* @param array $aSpaData
	* @param int|array $aSpaId
	* @return int
	*/
	function save($aSpaData, $aSpaId = NULL)
	{
		if ( $aSpaId )
		{
			if ( is_array($aSpaId) )
			{
				$this->db->where_in('com_spa_id', $aSpaId);
			}
			else
			{
				$this->db->where('com_spa_id', $aSpaId);
			}
			$this->db->update($this->table, $aSpaData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aSpaData);
			return $this->db->insert_id();
		}
	}

	/**
	* Добавление или редактироавние текстовой части страны, перевода
	*
	* @param string $sLang двохбуквенное обозначение языка en, ru, es, fr...
	* @param array $aSpaData
	* @param int|array $aSpaId
	* @return int
	*/
	function save_translate($sLang, $aSpaData, $aSpaId = NULL)
	{
		if ( $aSpaId )
		{
			if ( is_array($aSpaId) )
			{
				$this->db->where_in('spa_id', $aSpaId);
			}
			else
			{
				$this->db->where('spa_id', $aSpaId);
			}
			$this->db->update($sLang . $this->subtable, $aSpaData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($sLang . $this->subtable, $aSpaData);
			return $this->db->insert_id();
		}
	}

	/**
	* Удаление статьи. И всех ее переводов
	*
	* @param int|array $aSpaId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aSpaId)
	{
		$aAffectedRows = array();
		if ( is_array($aSpaId) )
		{
			$this->db->where_in('com_spa_id', $aSpaId);
		}
		else
		{
			$this->db->where('com_spa_id', $aSpaId);
		}
		$this->db->delete($this->table);
		$aAffectedRows['com'] = $this->db->affected_rows();
		$aAffectedRows['all'] = $aAffectedRows['com'];

		foreach ( $this->config->item('lang_uri_abbr') as $key => $value )
		{
			if ( is_array($aSpaId) )
			{
				$this->db->where_in('spa_id', $aSpaId);
			}
			else
			{
				$this->db->where('spa_id', $aSpaId);
			}
			$this->db->delete($key . $this->subtable);
			$aAffectedRows[$key] = $this->db->affected_rows();
			$aAffectedRows['all'] = $aAffectedRows[$key];
		}
		
		$this->delete_facilities($aSpaId);
		
		$this->delete_essential_infos($aSpaId);
		
		$this->delete_medical_treatments($aSpaId);

		return $aAffectedRows;
	}

	/**
	* Удаление перевода
	*
	* @param string $sLang
	* @param int|array $aSpaId
	* @return int
	*/
	function delete_translate($sLang, $aSpaId)
	{
		if ( is_array($aSpaId) )
		{
			$this->db->where_in('spa_id', $aSpaId);
		}
		else
		{
			$this->db->where('spa_id', $aSpaId);
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
	* Получение отеля или списка отелей. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
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
		$this->db->select($sMainLang . '_cities.title as `' . $sMainLang . '_city_title`');
		$this->db->join($sMainLang . '_cities', $this->table . '.com_city_id = ' . $sMainLang . '_cities.city_id', 'left');

		if ( is_array($aAdditionalLang) )
		{
			foreach ( $aAdditionalLang as $sLang )
			{
				if ( $sLang != $sMainLang )
				{
					$this->db->select($sLang . $this->subtable . '.title as `' . $sLang . '_title`');
					$this->db->join($sLang . $this->subtable, $this->table . '.' . $this->table_join . ' = ' . $sLang . $this->subtable . '.' . $this->subtable_join, 'left');
					$this->db->select($sLang . '_cities.title as `' . $sLang . '_city_title`');
					$this->db->join($sLang . '_cities', $this->table . '.com_city_id = ' . $sLang . '_cities.city_id', 'left');
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
		$this->db->select('cities.title as `' . $sMainLang . '_city_title`');
		$this->db->join($sMainLang . '_cities', $this->table . '.com_city_id = ' . $sMainLang . '_cities.city_id', 'left');
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
	 * Сохранение важной инфо отеля
	 * 
	 * @param array $aEssentialInfoIds массив идентификаторов пунктов важной инфо
	 * @param int $nSpaId идентификатор отеля
	 * @return void
	 */
	public function save_essential_infos($aEssentialInfoIds, $nSpaId)
	{
		$this->delete_essential_infos($nSpaId);
		foreach ((array)$aEssentialInfoIds as $aEssentialInfoId)
		{
			$this->db->set(array('com_essential_info_id' => $aEssentialInfoId, $this->essential_info_join => $nSpaId));
			$this->db->insert($this->essential_info_table);
		}
	}
	
	/**
	 * Удаление важной инфо отеля
	 * 
	 * @param int|array $aSpaId идентификатор(ы) отеля(ей)
	 * @return void
	 */
	public function delete_essential_infos($aSpaId)
	{
		if ( is_array($aSpaId) )
		{
			$this->db->where_in($this->essential_info_join, $aSpaId);
		}
		else
		{
			$this->db->where($this->essential_info_join, $aSpaId);
		}
		$this->db->delete($this->essential_info_table);
		return $this->db->affected_rows();
	}
	
	/**
	 * Список всех пунктов информации на територии
	 * 
	 * @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	 * @param boolean $bSingle - один или список (TRUE/FALSE)
	 * @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	 * @param array $aOrder - сортировка ( array(поле => направление,...) )
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	 * @return array одномерный/двумерный масив (один/список)
	 */
	public function get_essential_infos($aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->from($this->essential_info_table);

		if ( $aFilters )
		{
			if ( $bFilterLike )
			{
				foreach ( $aFilters as $sField => $sValue )
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
	* Получение пункта важной информации или списка пунктов важной информаци. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_essential_info_joined_with_default_lang($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->essential_info_table . '.*');
		$this->db->select($this->essential_info_maintable . '.*');
		$this->db->select($sLang . $this->essential_info_subtable . '.*');

		$this->db->from($this->essential_info_table);
		$this->db->join($this->essential_info_maintable, $this->essential_info_table . '.' . $this->essential_info_sub_join . ' = ' . $this->essential_info_maintable . '.' . $this->essential_info_maintable_join, 'left');
		$this->db->join($sLang . $this->essential_info_subtable, $this->essential_info_table . '.' . $this->essential_info_sub_join . ' = ' . $sLang . $this->essential_info_subtable . '.' . $this->essential_info_subtable_join, 'left');

		foreach ($this->essential_info_subtable_fields as $subtable_field)
		{
			$this->db->select('default_lang' . $this->essential_info_subtable . '.' . $subtable_field . ' as `default_lang_' . $subtable_field . '`');
		}
		$this->db->join(LANGUAGE_ABBR_DEFAULT . $this->essential_info_subtable . ' as default_lang' . $this->essential_info_subtable, $this->essential_info_table . '.' . $this->essential_info_sub_join . ' = default_lang' . $this->essential_info_subtable . '.' . $this->essential_info_subtable_join, 'left');
		
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
	 * Сохранение процедур отеля
	 * 
	 * @param array $aEssentialInfoIds массив идентификаторов процедур
	 * @param int $nSpaId идентификатор отеля
	 * @return void
	 */
	public function save_medical_treatments($aMedicalTreatmentIds, $nSpaId)
	{
		$this->delete_medical_treatments($nSpaId);
		foreach ((array)$aMedicalTreatmentIds as $aMedicalTreatmentId)
		{
			$this->db->set(array('com_medical_treatment_id' => $aMedicalTreatmentId, $this->medical_treatment_join => $nSpaId));
			$this->db->insert($this->medical_treatment_table);
		}
	}
	
	/**
	 * Удаление важной инфо отеля
	 * 
	 * @param int|array $aSpaId идентификатор(ы) отеля(ей)
	 * @return void
	 */
	public function delete_medical_treatments($aSpaId)
	{
		if ( is_array($aSpaId) )
		{
			$this->db->where_in($this->medical_treatment_join, $aSpaId);
		}
		else
		{
			$this->db->where($this->medical_treatment_join, $aSpaId);
		}
		$this->db->delete($this->medical_treatment_table);
		return $this->db->affected_rows();
	}
	
	/**
	 * Список всех услуг на територии отеля
	 * 
	 * @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	 * @param boolean $bSingle - один или список (TRUE/FALSE)
	 * @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	 * @param array $aOrder - сортировка ( array(поле => направление,...) )
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	 * @return array одномерный/двумерный масив (один/список)
	 */
	public function get_medical_treatments($aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->from($this->medical_treatment_table);

		if ( $aFilters )
		{
			if ( $bFilterLike )
			{
				foreach ( $aFilters as $sField => $sValue )
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
	* Получение процедуры отеля или списка процедур. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_medical_treatment_joined_with_default_lang($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->medical_treatment_table . '.*');
		$this->db->select($this->medical_treatment_maintable . '.*');
		$this->db->select($sLang . $this->medical_treatment_subtable . '.*');

		$this->db->from($this->medical_treatment_table);
		$this->db->join($this->medical_treatment_maintable, $this->medical_treatment_table . '.' . $this->medical_treatment_sub_join . ' = ' . $this->medical_treatment_maintable . '.' . $this->medical_treatment_maintable_join, 'left');
		$this->db->join($sLang . $this->medical_treatment_subtable, $this->medical_treatment_table . '.' . $this->medical_treatment_sub_join . ' = ' . $sLang . $this->medical_treatment_subtable . '.' . $this->medical_treatment_subtable_join, 'left');

		foreach ($this->medical_treatment_subtable_fields as $subtable_field)
		{
			$this->db->select('default_lang' . $this->medical_treatment_subtable . '.' . $subtable_field . ' as `default_lang_' . $subtable_field . '`');
		}
		$this->db->join(LANGUAGE_ABBR_DEFAULT . $this->medical_treatment_subtable . ' as default_lang' . $this->medical_treatment_subtable, $this->medical_treatment_table . '.' . $this->medical_treatment_sub_join . ' = default_lang' . $this->medical_treatment_subtable . '.' . $this->medical_treatment_subtable_join, 'left');
		
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
	 * Сохранение услуг на територии отеля
	 * 
	 * @param array $aEssentialInfoIds массив идентификаторов услуг на територии
	 * @param int $nSpaId идентификатор отеля
	 * @return void
	 */
	public function save_facilities($aFacilityIds, $nSpaId)
	{
		$this->delete_facilities($nSpaId);
		foreach ((array)$aFacilityIds as $aFacilityId)
		{
			$this->db->set(array('com_facility_id' => $aFacilityId, $this->facility_join => $nSpaId));
			$this->db->insert($this->facility_table);
		}
	}
	
	/**
	 * Удаление важной инфо отеля
	 * 
	 * @param int|array $aSpaId идентификатор(ы) отеля(ей)
	 * @return void
	 */
	public function delete_facilities($aSpaId)
	{
		if ( is_array($aSpaId) )
		{
			$this->db->where_in($this->facility_join, $aSpaId);
		}
		else
		{
			$this->db->where($this->facility_join, $aSpaId);
		}
		$this->db->delete($this->facility_table);
		return $this->db->affected_rows();
	}
	
	/**
	 * Список всех услуг на територии отеля
	 * 
	 * @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	 * @param boolean $bSingle - один или список (TRUE/FALSE)
	 * @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	 * @param array $aOrder - сортировка ( array(поле => направление,...) )
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	 * @return array одномерный/двумерный масив (один/список)
	 */
	public function get_facilities($aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->from($this->facility_table);

		if ( $aFilters )
		{
			if ( $bFilterLike )
			{
				foreach ( $aFilters as $sField => $sValue )
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
	* Получение услуги на територии или списка услуг. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_facility_joined_with_default_lang($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->facility_table . '.*');
		$this->db->select($this->facility_maintable . '.*');
		$this->db->select($sLang . $this->facility_subtable . '.*');

		$this->db->from($this->facility_table);
		$this->db->join($this->facility_maintable, $this->facility_table . '.' . $this->facility_sub_join . ' = ' . $this->facility_maintable . '.' . $this->facility_maintable_join, 'left');
		$this->db->join($sLang . $this->facility_subtable, $this->facility_table . '.' . $this->facility_sub_join . ' = ' . $sLang . $this->facility_subtable . '.' . $this->facility_subtable_join, 'left');

		foreach ($this->facility_subtable_fields as $subtable_field)
		{
			$this->db->select('default_lang' . $this->facility_subtable . '.' . $subtable_field . ' as `default_lang_' . $subtable_field . '`');
		}
		$this->db->join(LANGUAGE_ABBR_DEFAULT . $this->facility_subtable . ' as default_lang' . $this->facility_subtable, $this->facility_table . '.' . $this->facility_sub_join . ' = default_lang' . $this->facility_subtable . '.' . $this->facility_subtable_join, 'left');
		
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

}

/* End of file spas_model.php */
/* Location: ./application/models/spas_model.php */