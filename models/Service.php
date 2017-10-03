<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Service extends CI_Model {

	   public function info_partida($id, $username) {
	    $lobbyname = $this->get_lobby_name($id);
	    $str = $lobbyname." ";
	    $this->load->model('Model_deck');
	    $result = $this->Model_deck->get_game_status($id);
	    if(sizeof($result) == 1){
		foreach ($result[0] as $key) {
	 		     $str= $str.$key. " ";
		}
		$playerHand = $this->get_player_hand($id, $username);
		$str = $str.$playerHand;
		$playersBets = $this->get_players_bets($id);
		$str = $str." ";
		$gameState = $this->get_game_state($id);
		$str = $str.$gameState;
		$str = $str." ";
		foreach($playersBets as $player){
			
			$username = $this->get_username($player['playerid']);
			$str = $str."*".$username."-----".$player['stack'];
		
		}
	    }
	    else{
	       return "ID Doesnt exist";
	    }
	 	return $str;
	}

	public function login($username, $password){
		$this->db->where('username',$username);
		$this->db->where('password',md5($password));
		$query = $this->db->get('users');

		if($query->num_rows()==1){
				return 1;

		}
		else{

			return 0;
		} 
   
	}
	public function aposta_partida($id,$username, $password, $jogada, $valor){
        $playerid = $this->get_user_id($username);
        if ($this->is_my_turn($id, $playerid)){
            if($jogada == "check"){
                return $this->check($id,$playerid);
            }
            elseif($jogada == "call"){
                return $this->call($id,$playerid);
            }
            elseif($jogada == "bet"){
                return $this->bet($id,$playerid,$valor);
            }
            elseif($jogada == "allin"){
                return $this->all_in($id,$playerid);
            }
            else{
                return $this->fold($id,$playerid);
                
            }
        }
        else{
            return "It's not your turn, please try again later";
        }

	   
    }
    function is_my_turn($gameid, $playerid){
        
        $sql = "SELECT current_player FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $current_player= $query->result_array();
        
        $current_player = $current_player[0]['current_player'];
        if($playerid == $current_player){
            return true;
        }
        else{
          return false;
        }
    }
    function get_user_id($username){
        $sql = "SELECT id FROM users WHERE username = '$username'";
        $query = $this->db->query($sql);
        $playerid= $query->result_array();
        return $playerid[0]['id'];
    }
   function get_players_bets($gameid){
	   $sql = "SELECT playerid,stack FROM plays_game WHERE gameid = $gameid";
    	$query = $this->db->query($sql);
      	$result = $query->result_array();
        return $result;
	}
    function get_username($playerid){
      	$sql = "SELECT username FROM users WHERE id = $playerid";
      	$query = $this->db->query($sql);
      	$result = $query->result_array();
        return $result[0]['username'];
    }
   function get_player_hand($gameid, $username){
	$playerid = $this->get_user_id($username);
    	$sql = "SELECT player_hand FROM plays_game WHERE gameid = $gameid AND playerid = $playerid";
    	$query = $this->db->query($sql);
    	$result = $query->result_array();
    	return $result[0]['player_hand'];  
  }
  function get_lobby_name($gameid){
	$sql = "SELECT name FROM game_request WHERE id = $gameid";
    	$query = $this->db->query($sql);
      	$result = $query->result_array();
        return $result[0]['name'];
  }
  function get_game_state($gameid){
	$sql = "SELECT game_state FROM game_status WHERE id = $gameid";
    	$query = $this->db->query($sql);
      	$result = $query->result_array();
        return $result[0]['game_state'];
  }

     public function check($gameid, $playerid){
        $this->load->model('Model_deck');
        if($this->Model_deck->set_check_service($gameid,$playerid)){
                $next_player = $this->Model_deck->set_next_player($gameid);
                $last_to_raise = $this->Model_deck->get_last_to_raise($gameid);

                if($next_player == $last_to_raise){
                    $this->Model_deck->change_game_state($gameid);
                }

                return "you checked";
                
            }
            else{
                return "you cant check, call or fold";
                
            }  
    }
      public function call($gameid,$playerid){
           $this->load->model('Model_deck');
                if($this->Model_deck->set_call_service($gameid, $playerid)){
                   if(!$this->Model_deck->last_to_call($gameid)){
                        $next_player = $this->Model_deck->set_next_player($gameid);
                        $last_to_raise = $this->Model_deck->get_last_to_raise($gameid);

                        if($next_player == $last_to_raise){
                            $this->Model_deck->change_game_state($gameid);
                           return "You Call";

                        }

                        else {
                           return "You Call";
                

                        }   
                
                    }
                    else{ 
                        return "You Call";
                          }
         
                }
                else{
                    if($this->Model_deck->last_to_call($gameid)){
                        return "You Call-ALL-IN";
                       
                    }
                    else{
                        
                        return "You Call-ALL-IN";  
                    }     
                
                }
    }
    public function fold($gameid,$playerid){
         $this->load->model('Model_deck');
                if($this->Model_deck->set_fold_service($gameid,$playerid)){
                    $next_player = $this->Model_deck->set_next_player($gameid);
                    $last_to_raise = $this->Model_deck->get_last_to_raise($gameid);
                    if ($this->Model_deck->last_to_fold($gameid)){

                    }
                    else if($next_player == $last_to_raise and !$this->Model_deck->last_to_fold($gameid)){
                        $this->Model_deck->change_game_state($gameid);
                    }

                     return "you fold";
                
                }
                else{
                    return "something went wrong, please try again";                       
                    
                }
    }
    public function bet($gameid,$playerid,$betsize){
             $this->load->model('Model_deck');
            $mystack = $this->Model_deck->get_player_stack($gameid,$playerid);
            
            if ($betsize < $mystack){
               
                if($this->Model_deck->set_bet_service($gameid,$playerid,$betsize)){
                    $this->Model_deck->set_next_player($gameid);
                    $last_to_raise = $this->Model_deck->set_last_to_raise_by_id($gameid,$playerid);
                    return "your bet has been placed";
                    
                }
            
                else{
                    return "Invalid bet";
                    
                }

            }
            else if($betsize == $mystack) {
                
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $playerid";
                $query = $this->db->query($sql);
                $this->all_in($gameid,$playerid);

                }
            else{
               return "You dont have that much money, if you want to all-in press all-in";
            }

    }
    public function all_in($gameid,$playerid){
         $this->load->model('Model_deck');
            if(!$this->Model_deck->last_to_call($gameid)){
                if($this->Model_deck->set_all_in_service($gameid,$playerid)){
                        $next_player = $this->Model_deck->set_next_player($gameid);
                        $last_to_raise = $this->Model_deck->set_last_to_raise_by_id($gameid,$playerid);
                        if($next_player == $last_to_raise){
                            $this->Model_deck->change_game_state($gameid);
                           return "you ALL IN, good luck";
                        }
              
                        else{
                            $last_to_raise = $this->Model_deck->set_last_to_raise_by_id($gameid,$next_player);
                          return "you ALL IN, good luck";
                        }
                }
                else{
                    return "you ALL IN, good luck";

                }
            }
        else{
             if($this->Model_deck->set_call_all_in_service($gameid,$playerid)){
   
                        return "you ALL IN, good luck";
                }
                else{
                     return "Something went wrong, please try again";

                }
            }

    } 

}