<?php

/**
 * Description of articles_model
 * @author levada_sv, seha16
 */
class Articles_model extends CI_Model
{

	private $table = 'articles';
	private $table_join = 'com_article_id';
	private $table_fields = array('com_article_id', 'com_hits', 'com_active', 'com_time', 'com_order');

	private $subtable = '_articles';
	private $subtable_join = 'article_id';
	private $subtable_fields = array('article_id', 'hits', 'time', 'author_id', 'title', 'full', 'keywords', 'description', 'editor_id', 'editing_end', 'seo_link');

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации о статье
	*
	* @param array $aArticleData
	* @param int|array $aArticleId
	* @return int
	*/
	function save($aArticleData, $aArticleId = NULL)
	{
		if ( $aArticleId )
		{
			if ( is_array($aArticleId) )
			{
				$this->db->where_in('com_article_id', $aArticleId);
			}
			else
			{
				$this->db->where('com_article_id', $aArticleId);
			}
			$this->db->update($this->table, $aArticleData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aArticleData);
			return $this->db->insert_id();
		}
	}

	/**
	* Добавление или редактироавние текстовой части статьи, перевода
	*
	* @param string $sLang двохбуквенное обозначение языка en, ru, es, fr...
	* @param array $aArticleData
	* @param int|array $aArticleId
	* @return int
	*/
	function save_translate($sLang, $aArticleData, $aArticleId = NULL)
	{
		if ( $aArticleId )
		{
			if ( is_array($aArticleId) )
			{
				$this->db->where_in('article_id', $aArticleId);
			}
			else
			{
				$this->db->where('article_id', $aArticleId);
			}
			$this->db->update($sLang . $this->subtable, $aArticleData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($sLang . $this->subtable, $aArticleData);
			return $this->db->insert_id();
		}
	}

	/**
	* Удаление статьи. И всех ее переводов
	*
	* @param int|array $aArticleId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aArticleId)
	{
		$aAffectedRows = array();
		if ( is_array($aArticleId) )
		{
			$this->db->where_in('com_article_id', $aArticleId);
		}
		else
		{
			$this->db->where('com_article_id', $aArticleId);
		}
		$this->db->delete($this->table);
		$aAffectedRows['com'] = $this->db->affected_rows();
		$aAffectedRows['all'] = $aAffectedRows['com'];

		foreach ( $this->config->item('lang_uri_abbr') as $key => $value )
		{
			if ( is_array($aArticleId) )
			{
				$this->db->where_in('article_id', $aArticleId);
			}
			else
			{
				$this->db->where('article_id', $aArticleId);
			}
			$this->db->delete($key . $this->subtable);
			$aAffectedRows[$key] = $this->db->affected_rows();
			$aAffectedRows['all'] = $aAffectedRows[$key];
		}

		return $aAffectedRows;
	}

	/**
	* Удаление перевода статьи.
	*
	* @param string $sLang
	* @param int|array $aArticleId
	* @return int
	*/
	function delete_translate($sLang, $aArticleId)
	{
		if ( is_array($aArticleId) )
		{
			$this->db->where_in('article_id', $aArticleId);
		}
		else
		{
			$this->db->where('article_id', $aArticleId);
		}
		$this->db->delete($sLang . $this->subtable);
		return $this->db->affected_rows();
	}

	/**
	* Получение статьи или списка статей, основной информации
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
	* Получение статьи или списка статей, перевода
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
	* Получение статьи или списка статей, основной информации и перевода
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
	* Получение статьи или списка статей. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
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
	* Инкрементирование количества просмотров конкретного переводаю.
	* И инкремент количества просмортров в общей таблице.
	*
	* @param string $sLang
	* @param int $nArticleId
	* @return array array('translate' => количество затроннутых строк в таблице перевода, 'common' => количество затроннутых строк в общей таблице)
	*/
	public function inc_hit($sLang, $nArticleId)
	{
		$aAffectedRows = array();

		$this->db->where('article_id', $nArticleId);
		$this->db->set('hits', 'hits+1', FALSE);
		$this->db->update($sLang . $this->subtable);
		$aAffectedRows['translate'] = $this->db->affected_rows();

		$this->db->where('com_article_id', $nArticleId);
		$this->db->set('com_hits', 'com_hits+1', FALSE);
		$this->db->update($this->table);
		$aAffectedRows['common'] = $this->db->affected_rows();

		return $aAffectedRows;
	}

	/**
	 * Взять список статей для просмотра в админ. панели
	 *
	 * @param array $aAdditionalLang - дополнительные языки кроме основного. Основной берется из конфига (en)
	 * @param array $aFilters - фильры отбора ( array(поле => значение,...) )
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
		$this->db->select($sMainLang . $this->subtable . '.editor_id as `' . $sMainLang . '_editor_id`');
		$this->db->select($sMainLang . $this->subtable . '.editing_end as `' . $sMainLang . '_editing_end`');
		$this->db->join($sMainLang . $this->subtable, $this->table . '.' . $this->table_join . ' = ' . $sMainLang . $this->subtable . '.' . $this->subtable_join, 'left');

		if ( is_array($aAdditionalLang) )
		{
			foreach ( $aAdditionalLang as $sLang )
			{
				if ( $sLang != $sMainLang )
				{
					$this->db->select($sLang . $this->subtable . '.title as `' . $sLang . '_title`');
					$this->db->select($sLang . $this->subtable . '.editor_id as `' . $sLang . '_editor_id`');
					$this->db->select($sLang . $this->subtable . '.editing_end as `' . $sLang . '_editing_end`');
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

}

/* End of file articles_model.php */
/* Location: ./application/models/articles_model.php */