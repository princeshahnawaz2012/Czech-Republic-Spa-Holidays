<?php

/**
 * Description of draws_model
 * @author levada_sv
 */
class draws_model extends CI_Model
{

        private $table = 'draws';
        /* draw_id admin_id created draw_date open_until jackpot ticket_cost lotto_corp paypal_email paypal_password status comments */
        private $draw_users_table = 'draw_users';
        /* draw_users_id draw_id user_id created */	
		
        function __construct()
        {
                parent::__construct();
                $this->load->database();
        }

        /**
         * Добавление или редактироавние тиража
         * @param <type> $aDrawData
         * @param <type> $nDrawId
         * @return <type>
         */
        function save($aDrawData, $nDrawId = NULL)
        {
                if ($nDrawId)
                {
                        $this->db->where('draw_id', $nDrawId);
                        $this->db->update($this->table, $aDrawData);
                        return $this->db->affected_rows();
                } else
                {
                        $this->db->insert($this->table, $aDrawData);
                        return $this->db->insert_id();
                }
        }

        /**
         * Удаление тиража и всех билетов со сканами принадлежащих этому тиражу
         * @param <type> $nDrawId
         * @return <type>
         */
        function delete($nDrawId)
        {
                $this->load->model('tickets_model');
                $aTickets = $this->tickets_model->get(array('draw_id' => $nDrawId));
                $sFilePatch = $this->config->item('tickets_scan_dir');
                foreach ($aTickets as $aTicket)
                {
                        if (file_exists($sFilePatch . $aTicket['scan']))
                        {
                                unlink($sFilePatch . $aTicket['scan']);
                        }
                }
                $this->db->where('draw_id', $nDrawId);
                $this->db->delete('tickets');

                $this->db->where('draw_id', $nDrawId);
                $this->db->delete($this->table);
                return $this->db->affected_rows();
        }

        /**
         * Изменение статуса тиража
         * @param <type> $nDrawId
         * @return <type>
         */
        function opened($nDrawId)
        {
                $this->db->where('draw_id', $nDrawId);
                $this->db->update($this->table, array('status' => DRAW_STATUS_OPENED));
                return $this->db->affected_rows();
        }

        /**
         * Изменение статуса тиража
         * @param <type> $nDrawId
         * @return <type>
         */
        function pending($nDrawId)
        {
                $this->db->where('draw_id', $nDrawId);
                $this->db->update($this->table, array('status' => DRAW_STATUS_PENDING));
                return $this->db->affected_rows();
        }

        /**
         * Изменение статуса тиража
         * @param <type> $nDrawId
         * @return <type>
         */
        function complete($nDrawId)
        {
                $this->db->where('draw_id', $nDrawId);
                $this->db->update($this->table, array('status' => DRAW_STATUS_COMPLETE));
                return $this->db->affected_rows();
        }		
		
        /**
         * Изменение статуса тиража
         * @param <type> $nDrawId
         * @return <type>
         */
        function closed($nDrawId)
        {
                $this->db->where('draw_id', $nDrawId);
                $this->db->update($this->table, array('status' => DRAW_STATUS_CLOSED));
                return $this->db->affected_rows();
        }

	/**
         * Изменение статуса тиража
         * @param <type> $nDrawId
         * @return <type>
         */
        function cancel($nDrawId)
        {
                $this->db->where('draw_id', $nDrawId);
                $this->db->update($this->table, array('status' => DRAW_STATUS_CANCELLED));
                return $this->db->affected_rows();
        }

        /**
         * Получение тиража или списка тиражей
         * @param <type> $aFilters - фильры отбора ( array(поле => значение,...) )
         * @param <type> $bSingle - один или список (TRUE/FALSE)
         * @param <type> $aLimit - количество со смешением (array(кол, смешение),без смешения (int)количество )
         * @param <type> $aOrder - сортировка ( array(поле => направление,...) )
         * @return <type> одномерный/двумерный масив (один/список)
         */
        function get($aFilters = false, $bSingle = false, $aLimit = NULL, $aOrder=NULL)
        {
                $this->db->from($this->table);

                if ($aFilters)
                {
                        $this->db->where($aFilters);
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
         * Количество записей удовлетворяющих условию
         * @param <type> $aFilters
         * @return <type>
         */
        function get_count($aFilters)
        {
                $this->db->from($this->table);
                $this->db->where($aFilters);
                return $this->db->count_all_results();
        }

	/**
	 * розыгрыши пользователя и количество приобретенных ими билетов
	 * @param <type> $nUserID
	 * @param <type> $sStatus
	 * @return <type>
	 */
	function get_user_draws2( $nUserID, $sStatus )
	{
		$this->db->from($this->table);
		$this->db->from('gj_orders');

		if ( $sStatus == 'active' )
		{
			$this->db->where($this->table.'.status !=', DRAW_STATUS_CLOSED);
		} else
		{
			$this->db->where($this->table.'.status =', DRAW_STATUS_CLOSED);
		}

		$this->db->where($this->table.'.draw_id = gj_orders.draw_id');
		$this->db->where('gj_orders.user_id',$nUserID);
		$this->db->where('gj_orders.paid', ORDER_STATUS_PAID);
		
		$this->db->select("$this->table.*, SUM(`gj_orders`.`number_ticket`) as count_tickets");

		$this->db->group_by('gj_orders.draw_id');

                $oQuery = $this->db->get();
                return $oQuery->result_array();
	}

        /**
         * Количество записей в таблице draw_users удовлетворяющее фильтру
         * @param <type> $aFilters
         * @return <type>
         */
        function get_count_user_draws($aFilters)
        {
                $this->db->from($this->draw_users_table);
                $this->db->where($aFilters);
                return $this->db->count_all_results();
        }

        /**
         * Количество розыгрышей удовлетворяющих фильтру удовлетворяющее фильтру
         * @param <type> $aFilters
         * @return <type>
         */
        function get_count_draw($aFilters=NULL)
        {
                $this->db->from($this->table);
                $this->db->from($this->draw_users_table);
                $this->db->where($this->draw_users_table.'.draw_id = '.$this->db->dbprefix.$this->table.'.draw_id');
		if ($aFilters)
		{
			$this->db->where($aFilters);
		}
                return $this->db->count_all_results();
        }
	
        /**
         * Добавление пользователя к розыгрышу
         * @param <type> $nDrawId
         * @param <type> $nUserId
         * @param <type> $nRole
         * @return <type>
         */
        function connect_user($nDrawId, $nUserId, $nRole )
        {
                $aData = array(
                    'draw_id' => $nDrawId,
                    'user_id' => $nUserId,
                    'role' => $nRole,
                );
                $this->db->insert($this->draw_users_table, $aData);
                return $this->db->affected_rows();
        }	

        /**
         * Удаление связей по переданным фильтрам
         * @param <type> $aFilters
         * @return <type>
         */
        function disconnect_user($aFilters = false)
        {
                if ($aFilters)
                {
                        $this->db->where($aFilters);
                        $this->db->delete($this->draw_users_table);
                        return $this->db->affected_rows();
                }
                return FALSE;
        }

        /**
         * Получение списка участников розыгрыша
         * @param <type> $nDrawId
         * @param <type> $aLimit 
	 * @param <type> $aOrder
         * @return <type>
         */
        function member_list($nDrawId, $aLimit=NULL, $aOrder=NULL)
        {
                $this->db->from($this->draw_users_table);
                $this->db->from('gj_users');
                $this->db->where($this->draw_users_table . '.draw_id', $nDrawId);
                $this->db->where($this->draw_users_table . '.user_id = `gj_users`.`user_id`');
                $this->db->select('gj_users.*,' . $this->draw_users_table . '.created');
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
                return $oQuery->result_array();
        }	

        /**
         * Участвует ли пользователь в розыгрыше
         * @param <type> $nDrawId
         * @param <type> $nUserId
         * @return <type>
         */
        function user_status($nDrawId, $nUserId)
        {
                $this->db->from($this->draw_users_table);
                $this->db->where('draw_id', $nDrawId);
                $this->db->where('user_id', $nUserId);
                $oQuery = $this->db->get();
                $aQuery = $oQuery->row_array();
                if ( $aQuery )
                {
                        return $aQuery['role'];
                }
                return FALSE;
        }

        /**
         * Возвращает розыгрышы в которых состоит пользователь и его
         * роль(status) в них
         * @param <type> $nUserID
         * @param <type> $aLimit
         * @param <type> $aOrder
         * @return <type>
         */
        function get_user_draws( $nUserID, $aLimit=NULL, $aOrder=NULL, $aFilters=NULL)
        {
                $this->db->from($this->table);
                $this->db->from($this->draw_users_table);
                $this->db->where($this->draw_users_table.'.user_id', $nUserID);
                $this->db->where($this->draw_users_table.'.draw_id = '.$this->db->dbprefix.$this->table.'.draw_id');
                $this->db->select($this->table.'.*, '.$this->draw_users_table.'.role');
                if ($aFilters)
                {
                        $this->db->where($aFilters);
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
                return $oQuery->result_array();
        }

        /**
         * Возвращает розыграш и его администратора
         * @param <type> $nGroupId
         * @return <type>
         */
        function get_draw_and_admin($nDrawId)
        {
                $this->db->from($this->table);
                $this->db->from('gj_users');
                $this->db->where($this->table . '.draw_id', $nDrawId);
                $this->db->where($this->table . '.admin_id = `gj_users`.`user_id`');
		$this->db->select('gj_users.first_name, gj_users.last_name, gj_users.email,'.$this->table.'.*');
                $oQuery = $this->db->get();
                $aQuery = $oQuery->row_array();
                if ($aQuery)
                {
                        return $aQuery;
                }
                return FALSE;
        }

        /**
         * Возвращает розыграшы и их администраторов
         * @param <type> $nGroupId
         * @return <type>
         */
        function get_draws_and_admins($aWhere)
        {
                $this->db->from($this->table);
                $this->db->from('gj_users');
                $this->db->where($this->table . '.admin_id = `gj_users`.`user_id`');
		$this->db->where($aWhere);
		$this->db->select('gj_users.first_name, gj_users.last_name, gj_users.email,'.$this->table.'.*');
                $oQuery = $this->db->get();
                $aQuery = $oQuery->result_array();
                if ($aQuery)
                {
                        return $aQuery;
                }
                return FALSE;
        }

}

/* End of file draws_model.php */
/* Location: ./groupjackpot/models/draws_model.php */