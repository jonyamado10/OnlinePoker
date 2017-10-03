<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_model extends CI_Model {

    function get_members() {
        $query = $this->db->get('users');
        return $query->result_array();
    }
        function get_games_started() {
        $sql = "SELECT * FROM game_status WHERE  ended_at is NULL";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
        function get_games_ended() {
        $sql = "SELECT * FROM game_status WHERE ended_at is NOT NULL";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
        function get_games_waiting() {
        $query = $this->db->get('game_request');
        return $query->result_array();
    }
        function get_players_on_tables_data() {
        $query = $this->db->get('plays_game');
        return $query->result_array();
    }
    
    function getAdmin($username) {
        $this->db->where('username',$username);
		$query = $this->db->get('admin');

		if($query->num_rows()==1){
				return true;

		}
		else{

			return false;
        }
    }
}
?>