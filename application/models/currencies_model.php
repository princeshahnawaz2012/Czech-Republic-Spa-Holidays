<?php

/**
 * Description of currencies_model
 * @author Sergii Lapin
 */
class Currencies_model extends CI_Model
{

	private $table = 'currencies';
	private $table_join = 'com_currency_id';
	private $table_fields = array('com_currency_id', 'com_active', 'com_order');

	private $subtable = '_currencies';
	private $subtable_join = 'currency_id';
	private $subtable_fields = array('currency_id', 'title');
	
	private $exchange_table = 'currencies_exchange';
	private $exchange_table_fields = array(
		'com_currency_from_id', // идентификатор валюты, с которой нужно перевести
		'com_currency_to_id', // идентификатор валюты, на которую нужно перевести
		'com_exchange' // float - коефициент обмена валют
	);

	function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	/**
	* Добавление или редактироавние основной информации
	*
	* @param array $aCurrencyData
	* @param string|array $aCurrencyId
	* @return int
	*/
	function save($aCurrencyData, $aCurrencyId = NULL)
	{
		if ( $aCurrencyId )
		{
			if ( is_array($aCurrencyId) )
			{
				$this->db->where_in('com_currency_id', $aCurrencyId);
			}
			else
			{
				$this->db->where('com_currency_id', $aCurrencyId);
			}
			$this->db->update($this->table, $aCurrencyData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($this->table, $aCurrencyData);
			return 1;
		}
	}

	/**
	* Добавление или редактироавние текстовой части валюты, перевода
	*
	* @param string $sLang двохбуквенное обозначение языка en, ru, es, fr...
	* @param array $aCurrencyData
	* @param string|array $aCurrencyId
	* @return int
	*/
	function save_translate($sLang, $aCurrencyData, $aCurrencyId = NULL)
	{
		if ( $aCurrencyId )
		{
			if ( is_array($aCurrencyId) )
			{
				$this->db->where_in('currency_id', $aCurrencyId);
			}
			else
			{
				$this->db->where('currency_id', $aCurrencyId);
			}
			$this->db->update($sLang . $this->subtable, $aCurrencyData);
			return $this->db->affected_rows();
		}
		else
		{
			$this->db->insert($sLang . $this->subtable, $aCurrencyData);
			return 1;
		}
	}

	/**
	* Удаление статьи. И всех ее переводов
	*
	* @param string|array $aCurrencyId
	* @return array  array('all' => общее количество удаленных строк, 'com' => количество удаленных строк с основной таблицы, 'en' => количество удаленных строк с таблицы английского перевода,...)
	*/
	function delete($aCurrencyId)
	{
		$aAffectedRows = array();
		if ( is_array($aCurrencyId) )
		{
			$this->db->where_in('com_currency_id', $aCurrencyId);
		}
		else
		{
			$this->db->where('com_currency_id', $aCurrencyId);
		}
		$this->db->delete($this->table);
		$aAffectedRows['com'] = $this->db->affected_rows();
		$aAffectedRows['all'] = $aAffectedRows['com'];

		foreach ( $this->config->item('lang_uri_abbr') as $key => $value )
		{
			if ( is_array($aCurrencyId) )
			{
				$this->db->where_in('currency_id', $aCurrencyId);
			}
			else
			{
				$this->db->where('currency_id', $aCurrencyId);
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
	* @param string|array $aCurrencyId
	* @return int
	*/
	function delete_translate($sLang, $aCurrencyId)
	{
		if ( is_array($aCurrencyId) )
		{
			$this->db->where_in('currency_id', $aCurrencyId);
		}
		else
		{
			$this->db->where('currency_id', $aCurrencyId);
		}
		$this->db->delete($sLang . $this->subtable);
		return $this->db->affected_rows();
	}

	/**
	* Получение валюты или списка валют, основной информации
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
	* Получение валюты или списка валют, перевода
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
	* Получение валюты или списка валют, основной информации и перевода
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
	* Получение валюты или списка валют. Основной информации, перевода на нужный язык и перевод на язык по умолчанию
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
	 * Курс обмена с валюты в валюту
	 * 
	 * @param string $sCurrencyFromId
	 * @param string $sCurrencyToId
	 * @return float 
	 */
	public function get_exchange($sCurrencyFromId, $sCurrencyToId)
	{
		$this->db->where('com_currency_from_id', $sCurrencyFromId)->where('com_currency_to_id', $sCurrencyToId);
		$aRow = $this->db->get($this->exchange_table)->row_array();
		if ( isset($aRow['exchange']) )
		{
			return $aRow['exchange'];
		}
		return 1.0;
	}
	
	/**
	 * Сохранить курс валют
	 * 
	 * @param string $sCurrencyFromId
	 * @param string $sCurrencyToId
	 * @param float $fExchangeValue
	 * @return int 
	 */
	public function save_exchange($sCurrencyFromId, $sCurrencyToId, $fExchangeValue)
	{
		$aWhereData = array(
			'com_currency_from_id' => $sCurrencyFromId,
			'com_currency_to_id' => $sCurrencyToId,
		);
		$aCurrencyExchange = array(
			'com_exchange' => $fExchangeValue,
		);
		if ( $this->db->where($aWhereData)->count_all_results($this->exchange_table) )
		{
			$this->db->where($aWhereData);
			$this->db->set($aCurrencyExchange);
			$this->db->update($this->exchange_table);
			return $this->db->affected_rows();
		}
		$this->db->set($aWhereData);
		$this->db->set($aCurrencyExchange);
		$this->db->insert($this->exchange_table);
		return 1;
	}
	
	/**
	 * Удаление курса одной или всех валют
	 * 
	 * @param string|NULL $sCurrencyFromId - код валюты, из которой переводят
	 * @param string|NULL $sCurrencyToId - код валюты, на которую переводят
	 * @return int количество удаленных строк из базы
	 */
	public function delete_exchanges($sCurrencyFromId = NULL, $sCurrencyToId = NULL)
	{
		if ( $sCurrencyFromId )
		{
			$this->db->where('com_currency_from_id', $sCurrencyFromId);
		}
		if ( $sCurrencyToId )
		{
			$this->db->where('com_currency_to_id', $sCurrencyToId);
		}
		$this->db->delete($this->exchange_table);
		return $this->db->affected_rows();
	}

	

	/**
	* Получение списка курсов валют
	*
	* @param string $sLang
	* @param array $aFilters - фильры отбора ( array(поле => значение,...) )
	* @param boolean $bSingle - один или список (TRUE/FALSE)
	* @param array $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
	* @param array $aOrder - сортировка ( array(поле => направление,...) )
	* @param boolean $bFilterLike - использовать при фильтре команду like '%value%'
	* @return array одномерный/двумерный масив (один/список)
	*/
	function get_currencies_exchange_list($sLang, $aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL, $bFilterLike = FALSE)
	{
		$this->db->select($this->exchange_table . '.*');
		$this->db->select($sLang . $this->subtable . '_from.title as title_from');
		$this->db->select($sLang . $this->subtable . '_to.title as title_to');

		$this->db->from($this->exchange_table);
		$this->db->join($sLang . $this->subtable . ' as ' . $this->db->protect_identifiers($sLang . $this->subtable . '_to', TRUE), $this->exchange_table . '.com_currency_to_id = ' . $sLang . $this->subtable . '_to.' . $this->subtable_join, 'left');
		
		$this->db->join($sLang . $this->subtable . ' as ' . $this->db->protect_identifiers($sLang . $this->subtable . '_from', TRUE), $this->exchange_table . '.com_currency_from_id = ' . $sLang . $this->subtable . '_from.' . $this->subtable_join, 'left');

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
	public function get_currencies_exchange_count($sLang = '', $aFilters = false, $bFilterLike = FALSE)
	{
		$this->db->select($this->exchange_table . '.*');
		$this->db->select($sLang . $this->subtable . '_from.title as title_from');
		$this->db->select($sLang . $this->subtable . '_to.title as title_to');

		$this->db->from($this->exchange_table);
		$this->db->join($sLang . $this->subtable . ' as ' . $this->db->protect_identifiers($sLang . $this->subtable . '_to', TRUE), $this->exchange_table . '.com_currency_to_id = ' . $sLang . $this->subtable . '_to.' . $this->subtable_join, 'left');
		
		$this->db->join($sLang . $this->subtable . ' as ' . $this->db->protect_identifiers($sLang . $this->subtable . '_from', TRUE), $this->exchange_table . '.com_currency_from_id = ' . $sLang . $this->subtable . '_from.' . $this->subtable_join, 'left');
		
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
	
	public function get_currency_exchange_rate_from_remote_server($sCurrencyFromId = NULL, $sCurrencyToId = NULL)
	{
		if ( ! $sCurrencyFromId || ! $sCurrencyToId )
		{
			return FALSE;
		}
//		$curl = curl_init();
//		$sOutput = '';
//		if( $curl ) {
//			curl_setopt($curl, CURLOPT_URL, 'http://www.webservicex.net/CurrencyConvertor.asmx/ConversionRate?FromCurrency=' . $sCurrencyFromId . '&ToCurrency=' . $sCurrencyToId);
//			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//			$sOutput = curl_exec($curl);
//			curl_close($curl);
//		}
//		return $sOutput;
		$sURI = 'http://www.webservicex.net/CurrencyConvertor.asmx/ConversionRate?FromCurrency=' . $sCurrencyFromId . '&ToCurrency=' . $sCurrencyToId;
		$sResponseXml = file_get_contents($sURI);
		if ( $sResponseXml !== FALSE )
		{
			$oXml = simplexml_load_string($sResponseXml);
			if ( $oXml !== FALSE && isset($oXml[0]) )
			{
				return floatval($oXml[0]);
			}
		}
//		$oXml = new XMLReader();
//		if ( $oXml->open($sURI, 'UTF-8') )
//		{
////			$oXml->read();
//			$fRate = $oXml->value;
//			$oXml->close();
//			return var_export($oXml, TRUE);
//			return $fRate;
//		}
//		$oXml->close();
		return FALSE;
	}
}

/* End of file currencies_model.php */
/* Location: ./application/models/currencies_model.php */