<?php

/**
 * Description of categories_model
 * @author Sergii Lapin
 */
class Categories_model extends CI_Model
{

	private $table = 'categories';
	private $table_join = 'com_category_id';
	private $table_fields = array('com_category_id', 'com_active', 'com_order', 'com_picture_ext', 'com_complex_treatments');

	private $subtable = '_categories';
	private $subtable_join = 'category_id';
	private $subtable_fields = array('category_id', 'title', 'short_desc', 'desc', 'seo_link', 'metakeywords', 'metadescription');

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации
	*
	* @param array $aCategoryData
	* @param int|array $aCategoryId
	* @return int
	*/
	function save($aCategoryData, $aCategoryId = NULL)
	{
		if ( $aCategoryId )
		{
			if ( is_array($aCategoryId) )
			{
				$this->db->where_in('com_category_id', $aCategoryId);
			}
			else
			{
				$this->db->where('com_category_id', $aCategoryId);
			}
			$this->db->update($this->table, $aCategoryData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aCategoryData);
			return $this->db->insert_id();
		}
	}

	/**
	* Добавление или редактироавние текстовой части категории, перевода
	*
	* @param string $sLang двохбуквенное обозначение языка en, ru, es, fr...
	* @param array $aCategoryData
	* @param int|array $aCategoryId
	* @return int
	*/
	function save_translate($sLang, $aCategoryData, $aCategoryId = NULL)
	{
		if ( $aCategoryId )
		{
			if ( is_array($aCategoryId) )
			{
				$this->db->where_in('category_id', $aCategoryId);
			}
			else
			{
				$this->db->where('category_id', $aCategoryId);
			}
			$this->db->update($sLang . $this->subtable, $aCategoryData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($sLang . $this->subtable, $aCategoryData);
			return $this->db->insert_id();
		}
	}

	/**
	* Удаление статьи. И всех ее переводов
	*
	* @param int|array $aCategoryId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aCategoryId)
	{
		$aAffectedRows = array();
		if ( is_array($aCategoryId) )
		{
			$this->db->where_in('com_category_id', $aCategoryId);
		}
		else
		{
			$this->db->where('com_category_id', $aCategoryId);
		}
		$this->db->delete($this->table);
		$aAffectedRows['com'] = $this->db->affected_rows();
		$aAffectedRows['all'] = $aAffectedRows['com'];

		foreach ( $this->config->item('lang_uri_abbr') as $key => $value )
		{
			if ( is_array($aCategoryId) )
			{
				$this->db->where_in('category_id', $aCategoryId);
			}
			else
			{
				$this->db->where('category_id', $aCategoryId);
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
	 * @param int|array $aCategoryId
	 * @return int
	 */
	function delete_translate($sLang, $aCategoryId)
	{
		if ( is_array($aCategoryId) )
		{
			$this->db->where_in('category_id', $aCategoryId);
		}
		else
		{
			$this->db->where('category_id', $aCategoryId);
		}
		$this->db->delete($sLang . $this->subtable);
		return $this->db->affected_rows();
	}

	/**
	 * Получение категории или списка категорий, основной информации
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
	 * Получение категории или списка категорий, перевода
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
	 * Получение категории или списка категорий, основной информации и перевода
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
	* Получение категории или списка категорий. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
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
	 * Взять список категорий для просмотра в админ. панели
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
	 * Удаление файлов связаных с категорией
	 * 
	 * @param int $nCategoryId
	 * @return void
	 */
	public function unlink_files($nCategoryId = '')
	{
		if ( ! $nCategoryId )
		{
			return;
		}
		$aCategoryData = $this->get(array('com_category_id' => $nCategoryId), TRUE);
		if ( !$aCategoryData )
		{
			return;
		}
		$sPicturePath = './' . $this->config->item('category_pictures_dir') . $nCategoryId . '.' . $aCategoryData['com_picture_ext'];
		if (file_exists($sPicturePath))
		{
			unlink($sPicturePath);
		}
		return;
	}

}

/* End of file categories_model.php */
/* Location: ./application/models/categories_model.php */