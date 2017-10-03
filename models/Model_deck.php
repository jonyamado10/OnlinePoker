<?php  
class Model_deck extends CI_model{

 
  public function create_new_game(){
 
    $data = array(
      'name' => $this -> input->post('name'),
      'owner' => $this->session->userdata('id'),
      'max_players' => $this -> input->post('max_players'),
      'first_bet' => $this -> input->post('first_bet'),
      'description' => $this -> input->post('description'),
      'entry' => $this->input->post('entry'),
      'timeout' => $this->input->post('timeout')
      );
    $query = $this ->db->insert('game_request',$data);
    
    if($query){
        $sql = "SELECT MAX(id) FROM game_request";
        $query2 = $this->db->query($sql);
        $result2 = $query2->result_array();
        if ($this->Model_deck->join_game($result2[0]['MAX(id)'])){
        return true;
        }
        else{return false;}
    } 
    else{
      return false;
    }
 
 
  }
  function join_game($gameid){
    $sql = "SELECT entry FROM game_request WHERE id= $gameid";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $entry = $result[0]['entry'];

    $playerid = $this->session->userdata('id');
    $sql = "SELECT wallet FROM users WHERE id= $playerid";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $wallet = $result[0]['wallet'];

    if($wallet >= $entry){
      $data = array(
          'gameid' => $gameid,
          'playerid' => $this->session->userdata('id'),
          'player_folded' => 0,
          'stack' => $entry
          );

            $query = $this ->db->insert('plays_game',$data);
            if($query){
              return true;
            } else{
              return false;
            }
    }
    else{
      return false;
    }     
   }
    function get_players_stacks($gameid){
	   $sql = "SELECT playerid,stack FROM plays_game WHERE gameid = $gameid";
    	$query = $this->db->query($sql);
      	$result = $query->result_array();
        $arr = array();
        foreach($result as $player){
            $arr[$this->get_player_name($player['playerid'])[0]['username']] = $player['stack'];
        }
        return $arr;
	}
   function get_game_state($gameid){
      $sql = "SELECT game_state FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result[0]['game_state'];
   }
    function get_game_timeout($gameid){
      $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result[0]['timeout'];
   }
function get_num_players_in_game($gameid){
      $sql = "SELECT * FROM plays_game WHERE gameid = $gameid";
      $query = $this->db->query($sql);
      $result2 = $query->result_array();
      $conta = 0;
      foreach($result2 as $a){
            $conta = $conta+1;
        }
        return strval($conta);
    }
   
   function get_room_name($gameid){
      $sql = "SELECT name FROM game_request WHERE id = $gameid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result[0]['name'];
   }
   public function get_player_on_button($gameid){
      $sql = "SELECT player_on_button FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result[0]['player_on_button'];

   }
   
   function game_start($gameid, $max_players){
      if ($this->game_is_full($gameid, $max_players)) {
            $sql = "UPDATE game_request SET closed = 1 WHERE id = $gameid";
            $query = $this->db->query($sql);
            $poker = new PTHE($max_players);
            $shufledDeck =$poker->make_deck_shuffle();
            $table_cards = $poker->show_game_state();
            $sql = "SELECT owner, first_bet FROM game_request where id = $gameid";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            $sql = "SELECT id,playerid FROM plays_game WHERE gameid = $gameid";
            $query = $this->db->query($sql);
            $players = $query->result_array();
            $players_hands = $poker->show_players_hands();
            
           $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
            $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
            $data = array(
                'id' => $gameid,
                'started_at' => date('Y-m-d H:i:s'),
                'deck ' => implode(" ",$shufledDeck),
                'table_cards' => implode(" ",$table_cards),
                'current_player' => $players[1]['playerid'],
                'player_on_button' => $players[1]['playerid'],
                'current_bet' => $result[0]['first_bet'],
                'last_to_raise' => $players[1]['playerid'],
                'game_state' => "pre_flop",
                'timer' => $timeout,
                'current_pot' => $result[0]['first_bet']);
            $query = $this ->db->insert('game_status',$data);
            if($query){
                  $blind = $result[0]['first_bet'];
                  $player_on_blind = $players[0]['playerid'];
                  $player_on_button = $players[1]['playerid'];
               
                  $sql = "UPDATE plays_game SET player_bet = $blind WHERE gameid = $gameid AND playerid = $player_on_blind";
                  $query = $this->db->query($sql);
                  $sql = "UPDATE plays_game SET stack = stack - $blind WHERE gameid = $gameid AND playerid = $player_on_blind";
                  $query = $this->db->query($sql);
                  $whatplayer = "player_";
                  $c = 1;
                  $sql = "SELECT entry FROM game_request WHERE id = $gameid";
                  $query = $this->db->query($sql);
                  $entry = $query->result_array()[0]['entry'];

                foreach($players as $player){       
                    $playerid = $player['playerid'];
                    $set_hand =  $whatplayer.$c;
                    $hand = implode(" ", $players_hands[$set_hand]);
                    $sql = "UPDATE plays_game SET player_hand = '$hand' WHERE gameid = $gameid AND playerid = $playerid";
                    $query = $this->db->query($sql);
                    $sql = "UPDATE users SET wallet = wallet - $entry WHERE id = $playerid";
                    $query = $this->db->query($sql);
                    // if($set_hand == $winner){
                    //   $sql = "UPDATE game_status SET winner = $playerid WHERE id = $gameid";
                    //   $query = $this->db->query($sql);
                    // }
                    $c += 1;
                  }
              return true;
            } else{
              return false;
            }   
   

      }
      else{

        return false;

      }



   }
   function rematch($gameid, $max_players){


            $poker = new PTHE($max_players);
            $shufledDeck =$poker->make_deck_shuffle();
            $table_cards = $poker->show_game_state();
            $sql = "SELECT owner, first_bet FROM game_request where id = $gameid";
            $query = $this->db->query($sql);
            $result = $query->result_array();
            $blind = $result[0]['first_bet'];
            $sql = "SELECT playerid FROM plays_game WHERE gameid = $gameid";
            $query = $this->db->query($sql);
            $players = $query->result_array();
            $players_hands = $poker->show_players_hands();
         
            $sql = "SELECT player_on_button FROM game_status WHERE id = $gameid";
            $query = $this->db->query($sql);
            $player_on_button = $query->result_array();
            $player_on_button = $player_on_button[0]['player_on_button'];
            $eliminados = 0;
            $index= 0;
            foreach ($players as $player) {
                if(intval($player['playerid']) == intval($player_on_button)){
                      
                        if(($index + 1) > (sizeof($players)-1)){
                          $next_player_on_button = $players[0]['playerid'];
      

                        }
                        else{
                          $next_player_on_button = $players[$index + 1]['playerid'];
                        }
                }
                else{
                $index +=1;
                $eliminados +=1;

                  }
              # code...
            }
       echo $eliminados;
       if(sizeof($players)==$eliminados){
        $next_player_on_button = $players[1]['playerid'];
    $player_on_button = $players[0]['playerid'];
       $sql = "UPDATE plays_game SET player_bet = $blind WHERE gameid = $gameid AND playerid = $player_on_button";
        $query = $this->db->query($sql);
            $sql = "UPDATE plays_game SET stack = stack - $blind WHERE gameid = $gameid AND playerid = $player_on_button";
            $query = $this->db->query($sql);
       }
       else{
            $sql = "UPDATE plays_game SET player_bet = $blind WHERE gameid = $gameid AND playerid = $player_on_button";
            $query = $this->db->query($sql);
            $sql = "UPDATE plays_game SET stack = stack - $blind WHERE gameid = $gameid AND playerid = $player_on_button";
            $query = $this->db->query($sql);
       }

            $data = array(

                'deck ' => implode(" ",$shufledDeck),
                'table_cards' => implode(" ",$table_cards),
                'current_bet' => $result[0]['first_bet'],
                'game_state' => "pre_flop",
                'player_on_button' => $next_player_on_button,
                'last_to_raise' => $next_player_on_button,
                'current_player' => $next_player_on_button,
                'current_pot' => $result[0]['first_bet']);

            $this->db->where('id', $gameid);
            $query = $this->db->update('game_status', $data);
        if($query){
                 $whatplayer = "player_";
                 $c = 1;
                foreach($players as $player){       
                    $playerid = $player['playerid'];
                    $set_hand =  $whatplayer.$c;
                    $hand = implode(" ", $players_hands[$set_hand]);
      
                    $sql = "UPDATE plays_game SET player_folded = 0 WHERE gameid = $gameid AND playerid = $playerid";
                    $query = $this->db->query($sql);
                    $sql = "UPDATE plays_game SET all_in = 0 WHERE gameid = $gameid AND playerid = $playerid";
                    $query = $this->db->query($sql);
                    $sql = "UPDATE plays_game SET ready = 0 WHERE gameid = $gameid AND playerid = $playerid";
                    $query = $this->db->query($sql);
                    $sql = "UPDATE plays_game SET player_hand = '$hand' WHERE gameid = $gameid AND playerid = $playerid";
                    $query = $this->db->query($sql);
              
                    // if($set_hand == $winner){
                    //   $sql = "UPDATE game_status SET winner = $playerid WHERE id = $gameid";
                    //   $query = $this->db->query($sql);
                    // }
                    $c += 1;
                  }
              return true;
            } else{
              return false;
            }   
   }
   function player_exit_game($gameid){
      $sql = "SELECT playerid, stack FROM plays_game WHERE gameid = $gameid";
      $query = $this->db->query($sql);
      $players = $query->result_array();
      $eliminados = array();
      foreach ($players as $player) {
        # code...
      if($player['stack']<= 0){
          array_push($eliminados, $player['playerid']);
          $playerid = $player['playerid'];
          $sql = "DELETE FROM plays_game WHERE $gameid = $gameid AND playerid = $playerid";
          $query = $this->db->query($sql);
      }
     }
      return $eliminados;

   }
   function checks_if_game_ended($gameid)
   {
      $sql = "SELECT ended_at FROM game_status WHERE gameid = $gameid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      if($result[0]['ended_at'] == null){
        return true;
      }
      else
        {return false;}

   }
    function check_if_table_winner($gameid){
      $sql = "SELECT playerid,stack FROM plays_game WHERE gameid = $gameid";
      $query = $this->db->query($sql);
      $player = $query->result_array();
      if(sizeof($player) == 1){
        $stack = $player[0]['stack'];
        $playerid = $player[0]['playerid'];
        $enddate = date('Y-m-d H:i:s');
        $sql = "UPDATE users SET wallet = wallet + $stack WHERE id = $playerid";
        $query = $this->db->query($sql);
        $sql = "UPDATE game_status SET winner = $playerid";
        $query = $this->db->query($sql);
        $sql = "UPDATE game_status SET ended_at = '$enddate'";
        $query = $this->db->query($sql);
        $sql = "UPDATE game_request SET closed = 1 WHERE id = $gameid";
        $query = $this->db->query($sql);

        return true;
      }
      else{
        return false;
      }
   }

   function get_hand_description($gameid,$playerid){
      $sql = "SELECT hand_description FROM plays_game WHERE gameid = $gameid AND playerid = $playerid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result[0]['hand_description'];

   }
   function get_player_bet($gameid,$playerid){
      $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $playerid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result[0]['player_bet'];
   }
   function get_round_winner($gameid){
      $sql = "SELECT MAX(hand_value) FROM plays_game WHERE gameid = $gameid AND player_folded = 0 ";
      $query = $this->db->query($sql);
      $hand_value = $query->result_array()[0]['MAX(hand_value)'];

      $sql = "SELECT playerid FROM plays_game WHERE gameid = $gameid AND player_folded = 0 AND hand_value = $hand_value";
      $query = $this->db->query($sql);
      $result = $query->result_array();


    
      return $result;
   }
   function get_last_to_raise($gameid){
      $sql = "SELECT last_to_raise FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result[0]['last_to_raise'];

   }
   function set_last_to_raise($gameid){
        $id= $this->session->userdata('id');
        $sql = "UPDATE game_status SET last_to_raise = $id WHERE id = $gameid";
        $query = $this->db->query($sql);

   }
  function set_last_to_raise_by_id($gameid,$playerid){
        
        $sql = "UPDATE game_status SET last_to_raise = $playerid WHERE id = $gameid";
        $query = $this->db->query($sql);

   }
   function reset_current_bet($gameid){

        $sql = "UPDATE game_status SET current_bet = 0 WHERE id = $gameid";
        $query = $this->db->query($sql);

   }
   function reset_player_bets($gameid){


        $sql = "UPDATE plays_game SET player_bet = 0 WHERE gameid = $gameid";
        $query = $this->db->query($sql);

   }
   function reset_pot($gameid){


        $sql = "UPDATE game_status SET current_pot = 0 WHERE id = $gameid";
        $query = $this->db->query($sql);

   }
    function all_player_ready($gameid){
      $sql = "SELECT playerid FROM plays_game WHERE gameid = $gameid and ready = 0";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      if(sizeof($result)==0){
          return true;
      }
        else{
            return false;
        }
        
    }
    function set_ready_player($gameid){
        $playerid = $this->session->userdata('id');
        $sql = "UPDATE plays_game SET ready = 1 WHERE gameid = $gameid AND playerid = $playerid";
        $query = $this->db->query($sql);
    }
    function unset_ready_player($gameid){
        $playerid = $this->session->userdata('id');
        $sql = "UPDATE plays_game SET ready = 0 WHERE gameid = $gameid AND playerid = $playerid";
        $query = $this->db->query($sql);
    }
    function set_status_all_ready($gameid){
        $sql = "UPDATE game_status SET all_ready = 1 WHERE id = $gameid ";
        $query = $this->db->query($sql);
    }
    function set_status_all_ready_false($gameid){
        $sql = "UPDATE game_status SET all_ready = 0 WHERE id = $gameid ";
        $query = $this->db->query($sql);
    }
    function is_status_all_ready($gameid){
      $sql = "SELECT id FROM game_status WHERE id = $gameid AND all_ready = 1";
      $query = $this->db->query($sql);
      $result = $query->result_array();
        if(sizeof($result)==0){
          return false;
      }
        else{
            return true;
        }
    }
    function update_timer($gameid){
    
      $sql = "UPDATE game_status SET timer = timer-1 WHERE id = $gameid";
      $query = $this->db->query($sql);
      $sql = "SELECT timer FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $result = $query->result_array()[0]['timer'];
      if($result < 0){
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];

        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
      }
      return $result;

    }
   function change_game_state($gameid){

        $game_state = $this->Model_deck->get_game_state($gameid);
        $sql = "UPDATE plays_game SET action = NULL WHERE gameid = $gameid";
        $query = $this->db->query($sql);
        if($game_state == "pre_flop"){
              $sql = "UPDATE game_status SET game_state = 'flop' WHERE id = $gameid";
              $query = $this->db->query($sql);

              $this->Model_deck->reset_current_bet($gameid);
              $this->Model_deck->reset_player_bets($gameid);

        }
        else if ($game_state == "flop"){
              $sql = "UPDATE game_status SET game_state = 'turn' WHERE id = $gameid";
              $query = $this->db->query($sql);
              $this->Model_deck->reset_current_bet($gameid);
              $this->Model_deck->reset_player_bets($gameid);

        }
        else if ($game_state == "turn"){
              $sql = "UPDATE game_status SET game_state = 'river' WHERE id = $gameid";
              $query = $this->db->query($sql);
              $this->Model_deck->reset_current_bet($gameid);
              $this->Model_deck->reset_player_bets($gameid);
          
        }
        else if ($game_state == "river"){
              $sql = "UPDATE game_status SET game_state = 'endgame' WHERE id = $gameid";
                $query = $this->db->query($sql);
              $this->Model_deck->reset_current_bet($gameid);
              $this->Model_deck->reset_player_bets($gameid);
          
        }
        else{

              $sql = "UPDATE game_status SET game_state = 'pre_flop' WHERE id = $gameid";
              $query = $this->db->query($sql);
              $this->Model_deck->reset_current_bet($gameid);
              $this->Model_deck->reset_player_bets($gameid);
        }

   }
  function pot_goes_to_winner($gameid, $winnerid){
      $sql = "SELECT current_pot FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $current_pot = $query->result_array();
      $current_pot = $current_pot[0]['current_pot'];
       $sql = "UPDATE plays_game SET stack = stack + $current_pot WHERE gameid = $gameid AND playerid = $winnerid";
       $query = $this->db->query($sql);

  }
  function check_if_all_bets_are_equal($bets_array){
    $first_bet = $bets_array[0]['player_bet'];
    $iguais = 0;
    foreach ($bets_array as $bet) {
        if($bet['player_bet'] == $first_bet){
            $iguais += 1;
        }
    }
    if($iguais == sizeof($bets_array)){
      return true;
    }
    else{
    return false;
  }
}

   function get_player_hand($gameid){
    $id = $this->session->userdata('id');
    $sql = "SELECT player_hand From plays_game WHERE gameid = $gameid AND playerid = $id;";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    return $result;
   }
   function get_player_stack ($gameid, $playerid){
    $sql = "SELECT stack From plays_game WHERE gameid = $gameid AND playerid = $playerid;";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    return $result[0]['stack'];

   }

   function set_bet($gameid,$betsize){

        $id = $this->session->userdata('id');
        $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $current_bet = $query->result_array();
        $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $mybet = $query->result_array();
        $mybet = $mybet[0]['player_bet'];
    

        if($current_bet[0]['current_bet'] < $betsize + $mybet){

            $sql = "UPDATE plays_game SET player_bet = $betsize WHERE gameid = $gameid AND playerid = $id";
            $query = $this->db->query($sql);
            $sql = "UPDATE game_status SET current_bet = $mybet + $betsize WHERE id = $gameid ";
            $query = $this->db->query($sql);
            $sql = "UPDATE plays_game SET stack = stack - $betsize WHERE gameid = $gameid AND playerid = $id";
            $query = $this->db->query($sql);
            $action = "Bets ".$betsize. "$";
            $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
            $query = $this->db->query($sql);
            $sql = "UPDATE game_status SET current_pot = current_pot + $betsize WHERE id = $gameid ";
            $query = $this->db->query($sql);
            
            return true;
        }

        else{


           
            return false;
        }

    }
       function set_bet_service($gameid,$id, $betsize){
        $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $current_bet = $query->result_array();
        $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $mybet = $query->result_array();
        $mybet = $mybet[0]['player_bet'];
    

        if($current_bet[0]['current_bet'] < $betsize + $mybet){

            $sql = "UPDATE plays_game SET player_bet = $betsize WHERE gameid = $gameid AND playerid = $id";
            $query = $this->db->query($sql);
            $sql = "UPDATE game_status SET current_bet = $mybet + $betsize WHERE id = $gameid ";
            $query = $this->db->query($sql);
            $sql = "UPDATE plays_game SET stack = stack - $betsize WHERE gameid = $gameid AND playerid = $id";
            $query = $this->db->query($sql);
            $action = "Bets ".$betsize. "$";
            $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
            $query = $this->db->query($sql);
            $sql = "UPDATE game_status SET current_pot = current_pot + $betsize WHERE id = $gameid ";
            $query = $this->db->query($sql);
            
            return true;
        }

        else{


           
            return false;
        }

    }
    function set_call($gameid){
      $id = $this->session->userdata('id');
      $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
      $query = $this->db->query($sql);
      $mybet = $query->result_array();
      $mybet = $mybet[0]['player_bet'];
      $sql = "SELECT stack FROM plays_game WHERE gameid = $gameid AND playerid = $id";
      $query = $this->db->query($sql);
      $stack = $query->result_array();
      $stack = $stack[0]['stack'];
      $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $current_bet = $query->result_array();
      $current_bet = $current_bet[0]['current_bet'];
      $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
      $query = $this->db->query($sql);
      $timeout = $query->result_array()[0]['timeout'];
      $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
      $query = $this->db->query($sql);
      if ($stack + $mybet >= $current_bet ){
        $sql = "UPDATE plays_game SET player_bet = player_bet + $current_bet WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "UPDATE plays_game SET stack = stack - $current_bet + $mybet WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "UPDATE game_status SET current_pot = current_pot + $current_bet - $mybet WHERE id = $gameid ";
        $query = $this->db->query($sql);
        $action = "Call";
        $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);

        return true;
      }
      else{
        $sql = "UPDATE plays_game SET player_bet = $stack + $mybet WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "UPDATE plays_game SET stack = 0 WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "UPDATE game_status SET current_pot = current_pot + $stack WHERE id = $gameid ";
        $query = $this->db->query($sql);
        $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $action = "Call-ALL-IN:".$stack + $mybet. "$";
        $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        return false;
      }
     
    }
     function set_call_service($gameid, $id){

      $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
      $query = $this->db->query($sql);
      $mybet = $query->result_array();
      $mybet = $mybet[0]['player_bet'];
      $sql = "SELECT stack FROM plays_game WHERE gameid = $gameid AND playerid = $id";
      $query = $this->db->query($sql);
      $stack = $query->result_array();
      $stack = $stack[0]['stack'];
      $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $current_bet = $query->result_array();
      $current_bet = $current_bet[0]['current_bet'];
      $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
      $query = $this->db->query($sql);
      $timeout = $query->result_array()[0]['timeout'];
      $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
      $query = $this->db->query($sql);
      if ($stack + $mybet >= $current_bet ){
        $sql = "UPDATE plays_game SET player_bet = player_bet + $current_bet WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "UPDATE plays_game SET stack = stack - $current_bet + $mybet WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "UPDATE game_status SET current_pot = current_pot + $current_bet - $mybet WHERE id = $gameid ";
        $query = $this->db->query($sql);
        $action = "Call";
        $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);

        return true;
      }
      else{
        $sql = "UPDATE plays_game SET player_bet = $stack + $mybet WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "UPDATE plays_game SET stack = 0 WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "UPDATE game_status SET current_pot = current_pot + $stack WHERE id = $gameid ";
        $query = $this->db->query($sql);
        $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $action = "Call-ALL-IN:".$stack + $mybet. "$";
        $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        return false;
      }
     
    }
    function set_check($gameid){
      $id = $this->session->userdata('id');
      $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $current_bet = $query->result_array();
      $current_bet = $current_bet[0]['current_bet'];
      $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
      $query = $this->db->query($sql);
      $my_bet = $query->result_array();
      $my_bet = $my_bet[0]['player_bet'];
      if($my_bet == $current_bet){
        $action = "Check";
        $sql = "UPDATE plays_game SET action ='$action' WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
        return true;
      }
      else{
        return false;
      }


    }
  function set_check_service($gameid, $id){

      $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
      $query = $this->db->query($sql);
      $current_bet = $query->result_array();
      $current_bet = $current_bet[0]['current_bet'];
      $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
      $query = $this->db->query($sql);
      $my_bet = $query->result_array();
      $my_bet = $my_bet[0]['player_bet'];
      if($my_bet == $current_bet){
        $action = "Check";
        $sql = "UPDATE plays_game SET action ='$action' WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
        return true;
      }
      else{
        return false;
      }


    }
    function last_to_fold($gameid){
     
      $sql = "SELECT playerid FROM plays_game WHERE gameid = $gameid AND player_folded = 0";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      if (sizeof($result)== 1){
          $sql = "UPDATE plays_game SET action = NULL WHERE gameid = $gameid";
          $query = $this->db->query($sql);
          $sql = "UPDATE game_status SET game_state = 'endgame' WHERE id = $gameid";
          $query = $this->db->query($sql);
          return true;

      }
      else{
        return false;
      }

    }
    function last_to_call($gameid){
      $sql = "SELECT playerid FROM plays_game WHERE gameid = $gameid AND player_folded = 0 AND all_in = 0";
      $query = $this->db->query($sql);
      $result = $query->result_array();
        
      if (sizeof($result)<= 1){
          $sql = "UPDATE plays_game SET action = NULL WHERE gameid = $gameid";
          $query = $this->db->query($sql);
          $sql = "UPDATE game_status SET game_state = 'endgame' WHERE id = $gameid";
          $query = $this->db->query($sql);

          return true;

      }
      else{
     
        return false;
      }


    }
    function set_fold($gameid){

      $id = $this->session->userdata('id');
      $sql = "UPDATE plays_game SET player_folded = True WHERE gameid = $gameid AND playerid = $id";
      $query = $this->db->query($sql);
      if($query){

        $action = "Fold";
        $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
        return true;}
        else{return false;}
      
    }
    function set_fold_service($gameid,$id){

      $sql = "UPDATE plays_game SET player_folded = True WHERE gameid = $gameid AND playerid = $id";
      $query = $this->db->query($sql);
      if($query){

        $action = "Fold";
        $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
        return true;}
        else{return false;}
      
    }
    function set_call_all_in($gameid){
        $id = $this->session->userdata('id');
        $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $mybet = $query->result_array();
         $mybet = $mybet[0]['player_bet'];
        $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $current_bet = $query->result_array()[0]['current_bet'];
        $betsize = $this->Model_deck->get_player_stack($gameid,$id);
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
        if($current_bet <= ($betsize + $mybet)){
                $sql = "UPDATE plays_game SET player_bet = $current_bet WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_bet = $current_bet WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET stack = stack - $current_bet WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_pot = current_pot + $current_bet WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $action = "ALL IN";
                $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
            
            }
            else{
                $sql = "UPDATE plays_game SET player_bet = player_bet + $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET stack = stack - $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_pot = current_pot + $betsize WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $action = "ALL IN";
                $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);

             
            }
            return true;

    }

    function set_all_in($gameid){
        $id = $this->session->userdata('id');
        $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $mybet = $query->result_array();
         $mybet = $mybet[0]['player_bet'];
        $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $current_bet = $query->result_array()[0]['current_bet'];
        $betsize = $this->Model_deck->get_player_stack($gameid,$id);
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
            if($current_bet <= $betsize){
                $sql = "UPDATE plays_game SET player_bet = $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_bet = $betsize WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET stack = stack - $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_pot = current_pot + $betsize WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $action = "ALL IN";
                $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                
             
            }
            else{
                $sql = "UPDATE plays_game SET player_bet = $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET stack = stack - $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_pot = current_pot + $betsize WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $action = "ALL IN";
                $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);

               
                
            }
            return true;
    }
function set_all_in_service($gameid,$id){
        $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $mybet = $query->result_array();
         $mybet = $mybet[0]['player_bet'];
        $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $current_bet = $query->result_array()[0]['current_bet'];
        $betsize = $this->Model_deck->get_player_stack($gameid,$id);
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
            if($current_bet <= $betsize){
                $sql = "UPDATE plays_game SET player_bet = $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_bet = $betsize WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET stack = stack - $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_pot = current_pot + $betsize WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $action = "ALL IN";
                $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                
             
            }
            else{
                $sql = "UPDATE plays_game SET player_bet = $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET stack = stack - $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_pot = current_pot + $betsize WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $action = "ALL IN";
                $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);

               
                
            }
            return true;
    }
    function set_call_all_in_service($gameid,$id){
        $sql = "SELECT player_bet FROM plays_game WHERE gameid = $gameid AND playerid = $id";
        $query = $this->db->query($sql);
        $mybet = $query->result_array();
         $mybet = $mybet[0]['player_bet'];
        $sql = "SELECT current_bet FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $current_bet = $query->result_array()[0]['current_bet'];
        $betsize = $this->Model_deck->get_player_stack($gameid,$id);
        $sql = "SELECT timeout FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $timeout = $query->result_array()[0]['timeout'];
        $sql = "UPDATE game_status SET timer = $timeout WHERE id = $gameid";
        $query = $this->db->query($sql);
        if($current_bet <= ($betsize + $mybet)){
                $sql = "UPDATE plays_game SET player_bet = $current_bet WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_bet = $current_bet WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET stack = stack - $current_bet WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_pot = current_pot + $current_bet WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $action = "ALL IN";
                $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
            
            }
            else{
                $sql = "UPDATE plays_game SET player_bet = player_bet + $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET stack = stack - $betsize WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $sql = "UPDATE game_status SET current_pot = current_pot + $betsize WHERE id = $gameid ";
                $query = $this->db->query($sql);
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                $action = "ALL IN";
                $sql = "UPDATE plays_game SET action = '$action' WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);

             
            }
            return true;

    }

    function is_my_turn($gameid){
        $my_id = $this->session->userdata('id');
        $sql = "SELECT current_player FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $current_player= $query->result_array();
        $current_player = $current_player[0]['current_player'];
        if($my_id == $current_player){
            return true;
        }
        else{
          return false;
        }


    }
  
  function game_is_full($game, $max_players){

    $sql = "SELECT gameid From plays_game WHERE gameid = $game";
    $query = $this->db->query($sql);
    $numberofgames = $query->result_array();
    $num_games = 0;
    foreach ($numberofgames as $row ) {
        $num_games += 1;
    }
    
    if($num_games < $max_players){
        return false;
    }
    else{

      return true;
    }
  }

  function game_is_closed($gameid){

    $sql = "SELECT closed From game_request WHERE id = $gameid";
    $query = $this->db->query($sql);
    $closed = $query->result_array()[0]['closed'];
    if($closed == 1){
      return true;
    }
    else{
      return false;
    }
  }
  function get_games() {
        $sql = "SELECT id,name, owner, max_players, first_bet FROM game_request WHERE closed = 0";
        $query = $this->db->query($sql);
        return $query->result_array();
    }


    function get_active_games($userid) {
        $sql = "SELECT gameid FROM plays_game WHERE playerid = $userid";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        return $result;

    }
    function get_players_actions($gameid) {
        $sql = "SELECT playerid, action FROM plays_game WHERE gameid = $gameid";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $actions = array();
        
        foreach ($result as $player) {
        $playername = $this->get_player_name($player['playerid'])[0]['username'];
     
        $actions[$playername] = $player['action'];
          
          # code...
        }
        return $actions;

    }
    function get_game_name($gameid){

        $sql = "SELECT name FROM game_request WHERE id = $gameid";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        # code...
      return $result[0]['name'];

    }
    function get_current_player($gameid){

        $sql = "SELECT current_player FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if (sizeof($result) == 0){
          $sql = "SELECT owner FROM game_request WHERE id = $gameid";
          $query = $this->db->query($sql);
          $result = $query->result_array();
          $owner = $result[0]['owner'];
          $sql2 = "SELECT username FROM users WHERE id = $owner";
          $query2 = $this->db->query($sql2);
          $result2 = $query2->result_array();
          return $result2[0]['username'];
        }
        else{
        # code...
        $current_player_id = $result[0]['current_player'];
        $sql2 = "SELECT username FROM users WHERE id = $current_player_id";
        $query2 = $this->db->query($sql2);
        $result2 = $query2->result_array();
        return $result2[0]['username'];
      }
    }
      function get_players_folded($gameid){
    $folds = array();
    $sql = "SELECT playerid FROM plays_game WHERE player_folded = 1 and gameid = $gameid";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    if(sizeof($result)>0){
      foreach ($result as $player) {
        $id = $player['playerid'];
        $sql = "SELECT username FROM users WHERE id = $id";
        $query = $this->db->query($sql);
        $player = $query->result_array()[0]['username'];
        array_push($folds, $player);
        # code...
      }
    }
    return $folds;
  }

   function get_game_status($gameid) {
        $sql = "SELECT id,started_at, table_cards, current_player, current_bet,current_pot FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $playerid = $result[0]['current_player'];
        $sql2 = "SELECT username FROM users WHERE id = $playerid";
        $query2 = $this->db->query($sql2);
        $result2 = $query2->result_array();
        $result[0]['current_player'] = $result2[0]['username'];
        return $result;

    }
  function get_start_date($gameid){
    $sql = "SELECT started_at FROM game_status WHERE id = $gameid";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    if($result){
    return $result[0]['started_at'];}
      else{
          return "Hasn't started";
      }
  }
  public function get_players_in_game($gameid){
      $sql = "SELECT playerid FROM plays_game WHERE gameid = $gameid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
      return $result;

    }
  function get_player_name($playerid){
       $sql = "SELECT username FROM users WHERE id = $playerid";
      $query = $this->db->query($sql);
      $result = $query->result_array();
        return $result;


  }
  public function set_next_player($gameid){
    $sql = "SELECT playerid FROM plays_game WHERE gameid = $gameid";
    $query = $this->db->query($sql);
    $players = $query->result_array();
    $sql = "SELECT current_player FROM game_status WHERE id = $gameid";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $current_player = $result[0]['current_player'];
    $key = 0;
    foreach($players as $player){

      if ($current_player == $player['playerid']){
        break;

      }
      $key +=1;
    }
    if($key != (sizeof($players)-1)){
        $key +=1;
        $next_player = $players[$key]['playerid'];
        $sql = "UPDATE game_status SET current_player = $next_player WHERE id = $gameid";
        $query = $this->db->query($sql);
    }
    else{
      $key = 0;
      $next_player = $players[$key]['playerid'];
      $sql = "UPDATE game_status SET current_player = $next_player WHERE id = $gameid";
      $query = $this->db->query($sql);
    }
    $sql = "SELECT player_folded FROM plays_game WHERE gameid = $gameid AND playerid = $next_player";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $fold = $result[0]['player_folded'];
    $sql = "SELECT all_in FROM plays_game WHERE gameid = $gameid AND playerid = $next_player";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $all_in = $result[0]['all_in'];
    if($current_player == $next_player){
        $sql = "UPDATE game_status SET game_state = 'endgame' WHERE id = $gameid";
        $query = $this->db->query($sql);
    }
      if ($fold == 1 or $all_in == 1){
        return $this->Model_deck->set_next_player($gameid);
         
      }
      else{

        return $next_player;

      }
    

  }
  function get_players_hands($gameid){
    $sql = "SELECT playerid, player_hand FROM plays_game WHERE gameid = $gameid And player_folded = 0";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $result = $result;
    return $result;
      
  }
  function did_player_fold($gameid){
     $playerid = $this->session->userdata('id');
    $sql = "SELECT player_folded FROM plays_game WHERE playerid = $playerid";
    $query = $this->db->query($sql);
    $result = $query->result_array();
    $result = $result[0]['player_folded'];
    return $result;

  }
  function check_if_game_started($gameid) {
        $sql = "SELECT id FROM game_status WHERE id = $gameid";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        if(sizeof($result) == 1){
          return true;
        }
        else{
          return false;
        }
 
 
  }
  function check_if_player_already_in_game($gameid,$userid) {
        $sql = "SELECT id FROM plays_game WHERE gameid = $gameid AND playerid = $userid";
        $query = $this->db->query($sql);
        $result = $query->result_array();

        if(sizeof($result) == 1){
          return true;
        }
        else{
          
          return false;

        }
 
 
  }
}
class PokerDeck
{
  /** * 52 card poker deck 
  */
  private $deck = array(  'AH', '2H', '3H', '4H', '5H', '6H', '7H', '8H', '9H', 'TH', 'JH', 'QH', 'KH',
              'AD', '2D', '3D', '4D', '5D', '6D', '7D', '8D', '9D', 'TD', 'JD', 'QD', 'KD',
              'AS', '2S', '3S', '4S', '5S', '6S', '7S', '8S', '9S', 'TS', 'JS', 'QS', 'KS',
              'AC', '2C', '3C', '4C', '5C', '6C', '7C', '8C', '9C', 'TC', 'JC', 'QC', 'KC',
            );
  
  private $deck_index   = 0;
  private $deck_shuffle = array();
  private $players_hands  = array();  
    
  
  
  /** * Shuffle deck order
  */
  public function make_deck_shuffle()
  {
    $this->deck_index = 0;
    $this->deck_shuffle = $this->deck;
    shuffle($this->deck_shuffle);
    
    return $this->deck_shuffle;
  }

  
  /** * Get players hands
  */
  public function get_players_hands($players, $cards)
  {
    if ((int)$players && (int)$cards)
    { 
      $player = 0;
      $this->players_hands = array();
    
      for ($i = 1; $i <= $players; ++$i)
      {
        ++$player;
        $this->players_hands['player_' . $player][] = $this->deck_shuffle[$i];
        
        for ($j = 1; $j < $cards; ++$j)
            $this->players_hands['player_' . $player][] = $this->deck_shuffle[($players*$j)+$player];
      }
      
      $this->deck_index = $players*$cards;
      
      return $this->players_hands;
    }
  }
  
  
  /** * Draw $number card(s)
  */
  public function draw_card($number)
  {
    ++$this->deck_index;
    
    $draw_cards = array();
    
    for ($i = 0; $i < $number; ++$i)
        $draw_cards[] = $this->deck_shuffle[++$this->deck_index];
        
  
    return $draw_cards;
  }
  
  
  /** * return the value of card, without consider seed
  */
  public function card_value($card)
  {
    return substr($card, 0, -1);
  }
  
  /** * return the seed of the card
  */
  public function card_seed($card)
  {
    return substr($card, -1); 
  }
  
  
  public function reset_pokerdeck()
  {
    unset($this->deck);
    unset($this->deck_index);
    unset($this->deck_shuffle);
    unset($this->players_hands);
  }
}


/** * Defining Texas Holdem Rules and specific method
  * @author Salvatore Rotondo <s.rotondo90@gmail.com>
*/
class PTHE extends PokerDeck
{
  private $TH_cards       = 2;
  private $TH_players_hands   = array();
  private $TH_players_points  = array();
  private $TH_game_state    = array();
  
  private $TH_desc_cards    = array(
                      14  => 'Ace',
                      13  => 'King',
                      12  => 'Queen',
                      11  => 'Jack',
                      10  => 'Ten',
                      9 => 'Nine',
                      8 => 'Eight',
                      7 => 'Seven',
                      6 => 'Six',
                      5 => 'Five',
                      4 => 'Four',
                      3 => 'Three',
                      2 => 'Two',
                      1 => 'Ace',
                      
                      200 => 'High',
                      201 => 'Four of',
                      202 => 'Full Over',
                      203 => 'Kicker',
                      204 => 'Pair of',
                      205 => 'Three of',
                      );
  
  private $TH_desc_points   = array(
                      1000  => 'Royal Flush',
                      999   => 'Flush Straight',
                      900   => 'Four of a Kind',
                      800   => 'Full House',
                      700   => 'Flush',
                      600   => 'Straight',
                      500   => 'Three of a Kind',
                      400   => 'Two Pair',
                      300   => 'One Pair',
                      200   => 'High Card'
                      );
    
    
  public function __construct($players = 1)
  {
    $this->make_deck_shuffle();
    $this->TH_players_hands = $this->get_players_hands($players, $this->TH_cards);
    $this->TH_draw_state();
    

  }
  private function TH_draw_state()
  {
    $this->TH_game_state['flop'] = $this->draw_card(3);
    $this->TH_game_state['turn'] = $this->draw_card(1);
    $this->TH_game_state['river'] = $this->draw_card(1);
    
    
    foreach ($this->TH_game_state as $game_cards)
        foreach ($game_cards as $card)
            $this->TH_game_state['all'][] = $card;
  }
  
  
  public function show_flop()
  {
    return $this->TH_game_state['flop'];
  }
  
  
  public function show_turn()
  {
    return $this->TH_game_state['turn'];  
  }
  
  
  public function show_river()
  {
    return $this->TH_game_state['river']; 
  }
  
  
  public function show_game_state()
  {
    return $this->TH_game_state['all']; 
  }
  
  
  public function show_players_hands()
  {
    return $this->TH_players_hands;
  } 
  
  
  public function show_players_points()
  {
    return $this->TH_players_points;
  }
  
  


  public function free_resources()
  {
    $this->reset_pokerdeck();
    
    unset($this->TH_cards);
    unset($this->TH_players_hands);
    unset($this->TH_game_state);
    unset($this->TH_desc_cards);

  }
  
}
 
 

?>