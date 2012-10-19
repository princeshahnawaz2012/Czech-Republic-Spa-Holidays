<?php

/**
 * Description of programmes_model
 * @author Sergii Lapin
 */
class Programmes_model extends CI_Model
{

	private $table = 'programmes';
	private $table_join = 'com_programme_id';
	private $table_fields = array('com_programme_id', 'com_category_id', 'com_active', 'com_order', 'com_spa_id', 'com_city_id', 'com_price_from', 'com_currency_id');

	private $subtable = '_programmes';
	private $subtable_join = 'programme_id';
	private $subtable_fields = array('programme_id', 'title', 'description', 'included', 'notincluded', 'terms', 'seo_link', 'metakeywords', 'metadescription', 'short_desc');
	
	
	
	private $illnese_table = 'programmes_illneses';
	private $illnese_join = 'com_programme_id';
	private $illnese_sub_join = 'com_illnese_id';
	private $illnese_fields = array('com_programme_id', 'com_illnese_id');
	
	private $illnese_maintable = 'illneses';
	private $illnese_maintable_join = 'com_illnese_id';
	private $illnese_maintable_fields = array('com_illnese_id', 'com_order', 'com_active');
	
	private $illnese_subtable = '_illneses';
	private $illnese_subtable_join = 'illnese_id';
	private $illnese_subtable_fields = array('illnese_id', 'title', 'short_desc');
	
	
	
	private $image_table = 'programmes_images';
	private $image_join = 'com_programme_id';
	private $image_sub_join = 'com_programme_image_id';
	private $image_fields = array('com_programme_image_id', 'com_programme_id', 'com_order', 'com_active', 'com_picture_ext');
	
	private $image_subtable = '_programmes_images';
	private $image_subtable_join = 'programme_image_id';
	private $image_subtable_fields = array('programme_image_id', 'title');

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации
	*
	* @param array $aProgrammeData
	* @param int|array $aProgrammeId
	* @return int
	*/
	function save($aProgrammeData, $aProgrammeId = NULL)
	{
		if ( $aProgrammeId )
		{
			if ( is_array($aProgrammeId) )
			{
				$this->db->where_in('com_programme_id', $aProgrammeId);
			}
			else
			{
				$this->db->where('com_programme_id', $aProgrammeId);
			}
			$this->db->update($this->table, $aProgrammeData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aProgrammeData);
			return $this->db->insert_id();
		}
	}

	/**
	* Добавление или редактироавние текстовой части страны, перевода
	*
	* @param string $sLang двохбуквенное обозначение языка en, ru, es, fr...
	* @param array $aProgrammeData
	* @param int|array $aProgrammeId
	* @return int
	*/
	function save_translate($sLang, $aProgrammeData, $aProgrammeId = NULL)
	{
		if ( $aProgrammeId )
		{
			if ( is_array($aProgrammeId) )
			{
				$this->db->where_in('programme_id', $aProgrammeId);
			}
			else
			{
				$this->db->where('programme_id', $aProgrammeId);
			}
			$this->db->update($sLang . $this->subtable, $aProgrammeData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($sLang . $this->subtable, $aProgrammeData);
			return $this->db->insert_id();
		}
	}

	/**
	* Удаление статьи. И всех ее переводов
	*
	* @param int|array $aProgrammeId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aProgrammeId)
	{
		$aAffectedRows = array();
		if ( is_array($aProgrammeId) )
		{
			$this->db->where_in('com_programme_id', $aProgrammeId);
		}
		else
		{
			$this->db->where('com_programme_id', $aProgrammeId);
		}
		$this->db->delete($this->table);
		$aAffectedRows['com'] = $this->db->affected_rows();
		$aAffectedRows['all'] = $aAffectedRows['com'];

		foreach ( $this->config->item('lang_uri_abbr') as $key => $value )
		{
			if ( is_array($aProgrammeId) )
			{
				$this->db->where_in('programme_id', $aProgrammeId);
			}
			else
			{
				$this->db->where('programme_id', $aProgrammeId);
			}
			$this->db->delete($key . $this->subtable);
			$aAffectedRows[$key] = $this->db->affected_rows();
			$aAffectedRows['all'] = $aAffectedRows[$key];
		}
		
		$this->delete_illneses($aProgrammeId);

		return $aAffectedRows;
	}

	/**
	* Удаление перевода
	*
	* @param string $sLang
	* @param int|array $aProgrammeId
	* @return int
	*/
	function delete_translate($sLang, $aProgrammeId)
	{
		if ( is_array($aProgrammeId) )
		{
			$this->db->where_in('programme_id', $aProgrammeId);
		}
		else
		{
			$this->db->where('programme_id', $aProgrammeId);
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
	* Получение программы или списка программ. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
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
		
		$this->db->select($sMainLang . '_categories.title as `' . $sMainLang . '_category_title`');
		$this->db->join($sMainLang . '_categories', $this->table . '.com_category_id = ' . $sMainLang . '_categories.category_id', 'left');
		
		$this->db->select($sMainLang . '_spas.title as `' . $sMainLang . '_spa_title`');
		$this->db->join($sMainLang . '_spas', $this->table . '.com_spa_id = ' . $sMainLang . '_spas.spa_id', 'left');
		
		$this->db->select($sMainLang . '_cities.title as `' . $sMainLang . '_city_title`');
		$this->db->join($sMainLang . '_cities', $this->table . '.com_city_id = ' . $sMainLang . '_cities.city_id', 'left');
		
		$this->db->select('programmes_images_count.num_images');
		$this->db->join('programmes_images_count', $this->table . '.' . $this->table_join . ' = programmes_images_count.' . $this->image_join, 'left');

		if ( is_array($aAdditionalLang) )
		{
			foreach ( $aAdditionalLang as $sLang )
			{
				if ( $sLang != $sMainLang )
				{
					$this->db->select($sLang . $this->subtable . '.title as `' . $sLang . '_title`');
					$this->db->join($sLang . $this->subtable, $this->table . '.' . $this->table_join . ' = ' . $sLang . $this->subtable . '.' . $this->subtable_join, 'left');
					
					$this->db->select($sLang . '_categories.title as `' . $sLang . '_category_title`');
					$this->db->join($sLang . '_categories', $this->table . '.com_category_id = ' . $sLang . '_categories.category_id', 'left');

					$this->db->select($sLang . '_spas.title as `' . $sLang . '_spa_title`');
					$this->db->join($sLang . '_spas', $this->table . '.com_spa_id = ' . $sLang . '_spas.spa_id', 'left');
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
		
		$this->db->join($sMainLang . '_categories', $this->table . '.com_category_id = ' . $sMainLang . '_categories.category_id', 'left');
		
		$this->db->join($sMainLang . '_spas', $this->table . '.com_spa_id = ' . $sMainLang . '_spas.spa_id', 'left');
		
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
	 * Сохранение процедур для лечения заболеваний
	 * 
	 * @param array $aIllneseIds массив идентификаторов услуг на територии
	 * @param int $nProgrammeId идентификатор отеля
	 * @return void
	 */
	public function save_illneses($aIllneseIds, $nProgrammeId)
	{
		$this->delete_illneses($nProgrammeId);
		foreach ((array)$aIllneseIds as $nIllneseId)
		{
			$this->db->set(array('com_illnese_id' => $nIllneseId, $this->illnese_join => $nProgrammeId));
			$this->db->insert($this->illnese_table);
		}
	}
	
	/**
	 * Удаление заболевания
	 * 
	 * @param int|array $aSpaId идентификатор(ы) отеля(ей)
	 * @return void
	 */
	public function delete_illneses($aProgrammeId)
	{
		if ( is_array($aProgrammeId) )
		{
			$this->db->where_in($this->illnese_join, $aProgrammeId);
		}
		else
		{
			$this->db->where($this->illnese_join, $aProgrammeId);
		}
		$this->db->delete($this->illnese_table);
		return $this->db->affected_rows();
	}
	
	/**
	 * Список всех заболеваний
	 * 
	 * @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	 * @param boolean $bSingle - один или список (TRUE/FALSE)
	 * @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	 * @param array $aOrder - сортировка ( array(поле => направление,...) )
	 * @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	 * @return array одномерный/двумерный масив (один/список)
	 */
	public function get_illneses($aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->from($this->illnese_table);

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
	* Получение заболевания или списка заболеваний, основной информации и перевода
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_illnese_joined_with_default_lang($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->illnese_table . '.*');
		$this->db->select($this->illnese_maintable . '.*');
		$this->db->select($sLang . $this->illnese_subtable . '.*');

		$this->db->from($this->illnese_table);
		$this->db->join($this->illnese_maintable, $this->illnese_table . '.' . $this->illnese_sub_join . ' = ' . $this->illnese_maintable . '.' . $this->illnese_maintable_join, 'left');
		$this->db->join($sLang . $this->illnese_subtable, $this->illnese_table . '.' . $this->illnese_sub_join . ' = ' . $sLang . $this->illnese_subtable . '.' . $this->illnese_subtable_join, 'left');

		foreach ($this->illnese_subtable_fields as $subtable_field)
		{
			$this->db->select('default_lang' . $this->illnese_subtable . '.' . $subtable_field . ' as `default_lang_' . $subtable_field . '`');
		}
		$this->db->join(LANGUAGE_ABBR_DEFAULT . $this->illnese_subtable . ' as default_lang' . $this->illnese_subtable, $this->illnese_table . '.' . $this->illnese_sub_join . ' = default_lang' . $this->illnese_subtable . '.' . $this->illnese_subtable_join, 'left');

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
	* Добавление или редактироавние информации о изображении
	*
	* @param array $aImageData
	* @param int|array $aImageId
	* @return int
	*/
	function save_image($aImageData, $aImageId = NULL)
	{
		if ( $aImageId )
		{
			if ( is_array($aImageId) )
			{
				$this->db->where_in($this->image_sub_join, $aImageId);
			}
			else
			{
				$this->db->where($this->image_sub_join, $aImageId);
			}
			$this->db->update($this->image_table, $aImageData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->image_table, $aImageData);
			return $this->db->insert_id();
		}
	}

	/**
	* Добавление или редактироавние текстовой части изображения программы, перевода
	*
	* @param string $sLang двохбуквенное обозначение языка en, ru, es, fr...
	* @param array $aImageData
	* @param int|array $aImageId
	* @return int
	*/
	function save_image_translate($sLang, $aImageData, $aImageId = NULL)
	{
		if ( $aImageId )
		{
			if ( is_array($aImageId) )
			{
				$this->db->where_in($this->image_subtable_join, $aImageId);
			}
			else
			{
				$this->db->where($this->image_subtable_join, $aImageId);
			}
			$this->db->update($sLang . $this->image_subtable, $aImageData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($sLang . $this->image_subtable, $aImageData);
			return $this->db->insert_id();
		}
	}

	/**
	* Удаление изображения программы. И всех ее переводов
	*
	* @param int|array $aImageId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete_image($aImageId)
	{
		$aAffectedRows = array();
		if ( is_array($aImageId) )
		{
			$this->db->where_in($this->image_sub_join, $aImageId);
		}
		else
		{
			$this->db->where($this->image_sub_join, $aImageId);
		}
		$this->db->delete($this->image_table);
		$aAffectedRows['com'] = $this->db->affected_rows();
		$aAffectedRows['all'] = $aAffectedRows['com'];

		foreach ( $this->config->item('lang_uri_abbr') as $key => $value )
		{
			if ( is_array($aImageId) )
			{
				$this->db->where_in($this->image_subtable_join, $aImageId);
			}
			else
			{
				$this->db->where($this->image_subtable_join, $aImageId);
			}
			$this->db->delete($key . $this->image_subtable);
			$aAffectedRows[$key] = $this->db->affected_rows();
			$aAffectedRows['all'] = $aAffectedRows[$key];
		}

		return $aAffectedRows;
	}

	/**
	* Удаление перевода изображения программы
	*
	* @param string $sLang
	* @param int|array $aImageId
	* @return int
	*/
	function delete_image_translate($sLang, $aImageId)
	{
		if ( is_array($aImageId) )
		{
			$this->db->where_in($this->image_subtable_join, $aImageId);
		}
		else
		{
			$this->db->where($this->image_subtable_join, $aImageId);
		}
		$this->db->delete($sLang . $this->image_subtable);
		return $this->db->affected_rows();
	}

	/**
	* Получение изображения программы или списка изображений программ, основной информации
	*
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_image($aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->from($this->image_table);

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
	* Получение изображения программы или списка изображений программ, перевода
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_image_translate($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->from($sLang . $this->image_subtable);

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
	* Получение изображения программы или списка изображений программ, основной информации и перевода
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_image_joined($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->image_table . '.*');
		$this->db->select($sLang . $this->image_subtable . '.*');

		$this->db->from($this->image_table);
		$this->db->join($sLang . $this->image_subtable, $this->image_table . '.' . $this->image_sub_join . ' = ' . $sLang . $this->image_subtable . '.' . $this->image_subtable_join, 'left');

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
	* Получение изображения программы или списка изображений программы. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_image_joined_with_default_lang($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->image_table . '.*');
		$this->db->select($sLang . $this->image_subtable . '.*');

		$this->db->from($this->image_table);
		$this->db->join($sLang . $this->image_subtable, $this->image_table . '.' . $this->image_sub_join . ' = ' . $sLang . $this->image_subtable . '.' . $this->image_subtable_join, 'left');

		foreach ($this->image_subtable_fields as $subtable_field)
		{
			$this->db->select('default_lang' . $this->image_subtable . '.' . $subtable_field . ' as `default_lang_' . $subtable_field . '`');
		}
		$this->db->join(LANGUAGE_ABBR_DEFAULT . $this->image_subtable . ' as default_lang' . $this->image_subtable, $this->image_table . '.' . $this->image_sub_join . ' = default_lang' . $this->image_subtable . '.' . $this->image_subtable_join, 'left');
		
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
	public function get_image_count($aFilters = false, $bFilterLike = FALSE)
	{
		$this->db->from($this->image_table);
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
	 * Удаление файлов связаных с программой
	 * 
	 * @param int $nProgrammeId идентификатор программы
	 * @param bool $bDeleteFileData флаг удаления данных файлов из базы данных
	 * @return void вне зависимости от результатов возвращает пустой ответ
	 */
	public function unlink_files($nProgrammeId = '', $bDeleteFileData = FALSE)
	{
		if ( ! $nProgrammeId )
		{
			return;
		}
		$aProgrammeImagesData = $this->get_image(array('com_programme_id' => $nProgrammeId));
		if ( !$aProgrammeImagesData )
		{
			return;
		}
		$aImagesId = array();
		foreach ($aProgrammeImagesData as $aImageData)
		{
			$sPicturePath = './' . $this->config->item('programme_pictures_dir') . $aImageData['com_programme_image_id'] . '.' . $aImageData['com_image_ext'];
			$aImagesId[] =  $aImageData['com_programme_image_id'];
			if (file_exists($sPicturePath))
			{
				unlink($sPicturePath);
			}
		}
		if ($bDeleteFileData)
		{
			$this->delete_image($aImagesId);
		}
		return;
	}
	
	
	/**
	 * Удаление файла изображения программы
	 * 
	 * @param int $nProgrammeImageId
	 * @return void
	 */
	public function unlink_image($nProgrammeImageId = '')
	{
		if ( ! $nProgrammeImageId )
		{
			return;
		}
		$aProgrammeImageData = $this->get_image(array('com_programme_image_id' => $nProgrammeImageId), TRUE);
		if ( !$aProgrammeImageData )
		{
			return;
		}
		$sPicturePath = './' . $this->config->item('programme_pictures_dir') . $nProgrammeImageId . '.' . $aProgrammeImageData['com_image_ext'];
		if (file_exists($sPicturePath))
		{
			unlink($sPicturePath);
		}
		return;
	}

	/**
	* редактироавние основной информации
	*
	* @param array $aProgrammeData
	* @param array $aFilters
	* @return int
	*/
	function edit($aProgrammeData, $aFilters = NULL)
	{
		if ( $aFilters )
		{
			$this->db->where($aFilters);
		}
		$this->db->update($this->table, $aProgrammeData);
		return $this->db->affected_rows();
	}
	
	
	public function get_joined_with_default_lang_by_illneses($aIllnesesId, $sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder = NULL, $bFilterLike = FALSE)
	{
		if ( ! empty($aIllnesesId) )
		{
			$this->db->where($this->illnese_sub_join, $aIllnesesId[0]);
			$this->db->select($this->illnese_join);
			$aProgrammesData = $this->db->get($this->illnese_table)->result_array();
			$aProgrammesId = array();
			foreach ($aProgrammesData as $aData)
			{
				$aProgrammesId[] = $aData[$this->illnese_join];
			}
			for ($i = 1; $i < count($aIllnesesId); $i++)
			{
				$this->db->where($this->illnese_sub_join, $aIllnesesId[$i]);
				$this->db->select($this->illnese_join);
				$aProgrammesData = $this->db->get($this->illnese_table)->result_array();
				$aProgrammesId2 = array();
				foreach ($aProgrammesData as $aData)
				{
					$aProgrammesId2[] = $aData[$this->illnese_join];
				}
				$aProgrammesId = array_intersect($aProgrammesId, $aProgrammesId2);
			}
			$aProgrammesId[] = 0;
			$this->db->where_in($this->table_join, $aProgrammesId);
		}
		return $this->get_joined_with_default_lang($sLang, $aFilters, $bSingle, $aLimit, $aOrder, $bFilterLike);
	}

}

/* End of file programmes_model.php */
/* Location: ./application/models/programmes_model.php */