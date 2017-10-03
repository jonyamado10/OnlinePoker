<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	function __contruct(){
		parent::__contruct();
		
	}
	public function index()
	{
		$this->load->view('home');
	}

	public function login()
	{
		$this->load->helper('form');
		$this->load->view('login');
	}

	public function admin()
	{
		$this->load->helper('form');
		$this->load->view('admin');
	}

	public function signup()
	{
		$this->load->view('signup');
	}
    public function game()
	{
		$this->load->view('game');
	}
   	public function scores()
	{
		$this->load->view('scores');
	}

	public function members(){
		if($this->session->userdata('is_logged_in')){
	            $this->load->model('Model_deck');
                $this->load->model('Model_users');
                $data['wallet'] = $this->Model_users->get_my_wallet();
	            $this->load->library('table');
	            $data['username'] = $this->session->userdata('username');
	            $data['idjogador'] = $this->session->userdata('id');
		        $data['game_request'] = $this->Model_deck->get_games();
		        $availablegames = array();
		        $data['games_active'] = $this->Model_deck->get_active_games($this->session->userdata('id'));
		        $games_active_names = array();
	     
	        
	        
		    foreach ($data['game_request'] as &$row) {
                 $ownerid =$row['owner'];
                 $row['owner']= $this->Model_deck->get_player_name($ownerid)[0]['username'];
                $players_ingame = $this->Model_deck->get_num_players_in_game($row['id']);
                array_push($row, $players_ingame."/".$row['max_players']);
				 array_push($row, "<a href='join_game/$row[id]/$row[max_players]'>Join</a>");
     
                
				 
				 if(!$this->Model_deck->game_is_full($row['id'],$row['max_players']) and !$this->Model_deck->game_is_closed($row['id'])){
					if(!$this->Model_deck->check_if_player_already_in_game($row['id'],$this->session->userdata('id'))) {	
					 	unset($row['id']);
                        unset($row['max_players']);
					 	array_push($availablegames,$row);
					 	
				 	}
				}
			}
		 	foreach ($data['games_active'] as &$row) {
		 		$gameid = $row['gameid'];
		 		if($this->Model_deck->check_if_game_started($gameid)){
		 			$row['gameid'] = $this->Model_deck->get_game_name($gameid);
                    $row['started_at']= $this->Model_deck->get_start_date($gameid);
		 			$row['current_player'] = $this->Model_deck->get_current_player($gameid);
                    
				 	array_push($row, "<a href='play/$gameid'>Resume</a>");
				}
				else{
					$row['gameid'] = $this->Model_deck->get_game_name($gameid);
                    $row['started_at']= $this->Model_deck->get_start_date($gameid);
					$row['current_player'] = $this->Model_deck->get_current_player($gameid);
        
					array_push($row, "<p>Waiting to start</p>");
				}
		 	}
			 
		    

		    $data['game_request'] = $availablegames;
	        
	            $template = array('table_open'  => '<table border="1" class="display table" id = "tabela">');
             $template2 = array('table_open'  => '<table border="1" class="display table" id = "tabela2">');
	            $this->table->set_template($template);
                $data['template2'] = $template2;
	            $this->load->view('members', $data, $template);
		}
		else{
			redirect('Main/restricted');
		}
	}
	   public function join_game($gameid, $max_players){

			$this->load->model('model_deck');
		    if($this->model_deck->join_game($gameid)){
		    	if($this->model_deck->game_start($gameid, $max_players)){
/*		    			$first_to_bet = $this->model_deck->set_next_player($gameid);
		    			$this->model_deck->set_last_to_raise_by_id($gameid,$first_to_bet);
		    			$this->model_deck->set_blind($gameid);*/

		        		redirect("Main/play/$gameid");
		    	}
		    	else{
				redirect("Main/members");
		    		echo '<script>alert("Your game will be active, as soon the table is complete")</script>';
		    		 
	       
		    	}


		    }
		    else{
		      echo '<script>alert("You dont have enough cash, please recharge")</script>';
                echo '<script>javascript:window.history.go(-1)</script>';
		    }

		

    }

	public function profile()
	{
		if($this->session->userdata('is_logged_in')){
            $this->load->model('Model_users');
                $data['wallet'] = $this->Model_users->get_my_wallet();
			$data['username'] = $this->session->userdata('username');
			$this->load->view('profile',$data);
		}
		else{
			redirect('Main/restricted');
		}

	}
	public function wallet()
	{
		if($this->session->userdata('is_logged_in')){
            $this->load->model('Model_users');
            $data['wallet'] = $this->Model_users->get_my_wallet();
			$data['username'] = $this->session->userdata('username');
			$this->load->view('wallet',$data);
		}
		else{
			redirect('Main/restricted');
		}

	}
	public function gamelogged()
	{
		if($this->session->userdata('is_logged_in')){
            $this->load->model('Model_users');
            $data['wallet'] = $this->Model_users->get_my_wallet();
			$data['username'] = $this->session->userdata('username');
			$this->load->view('gamelogged',$data);
		}
		else{
			redirect('Main/restricted');
		}

	}
	public function scoreslogged()
	{
		if($this->session->userdata('is_logged_in')){
            $this->load->model('Model_users');
            $data['wallet'] = $this->Model_users->get_my_wallet();
			$data['username'] = $this->session->userdata('username');
			$this->load->view('scoreslogged',$data);
		}
		else{
			redirect('Main/restricted');
		}

	}
	public function news()
	{
		if($this->session->userdata('is_logged_in')){
            $this->load->model('Model_users');
            $data['wallet'] = $this->Model_users->get_my_wallet();
			$data['username'] = $this->session->userdata('username');
			$this->load->view('news',$data);
		}
		else{
			redirect('Main/restricted');
		}

	}
	public function ended_game($gameid)
	{
		if($this->session->userdata('is_logged_in')){
			$data['username'] = $this->session->userdata('username');
            $this->load->model('Model_users');
            $data['wallet'] = $this->Model_users->get_my_wallet();
			$sql = "SELECT started_at, ended_at, winner FROM game_status WHERE id = $gameid";
	        $query = $this->db->query($sql);
			$data['game_status'] = $query->result_array();
			$winnerid = $data['game_status'][0]['winner'];
			$sql = "SELECT username FROM users WHERE id = $winnerid";
      		$query = $this->db->query($sql);
      		$winner = $query->result_array()[0]['username'];
			$data['winner'] = $winner;
			$this->load->view('ended_game',$data);
		}
		else{
			redirect('Main/restricted');
		}

	}
/*
    	function showgames() {
            $this->load->model('Model_deck');
            $this->load->library('table');
            $data['username'] = $this->session->userdata('username');
	    $data['game_request'] = $this->Model_deck->get_games();
          
        
        
	    foreach ($data['game_request'] as &$row) {
		 array_push($row, "<a href='$row[id]'>Join</a>");
		 unset($row['id']);
	    }
        
            $template = array('table_open'  => '<table border="1" class="display table" id = "tabela">');
            $this->table->set_template($template);
            $this->load->view('members', $data, $template);

    }*/
	public function play($gameid)
	{
		$sql = "SELECT ended_at FROM game_status WHERE id = $gameid";
      	$query = $this->db->query($sql);
      $result = $query->result_array()[0]['ended_at'];
		if($result == null){
				 $this->load->model('Model_deck');
             
		         $this->load->library('table');
				if($this->session->userdata('is_logged_in')){
                   $this->load->model('Model_users');
                    
                    
                    
                    $data['wallet'] = $this->Model_users->get_my_wallet();
					$data['gameid'] = $gameid;
					$data['timeout'] = $this->Model_deck->get_game_timeout($gameid);
                    $data['is_my_turn'] = $this->Model_deck->is_my_turn($gameid) ;
					if($data['is_my_turn']and ($data['timeout'] != -1)){
					   $data['timer'] = $this->Model_deck->update_timer($gameid);
					}
                    
					$data['username'] = $this->session->userdata('username');
					$data['roomname'] =  $this->Model_deck->get_room_name($gameid);
					$data['game_status'] = $this->Model_deck->get_game_status($gameid);
					$data['players_in_game'] = array();
					$data['players_folded'] = $this->Model_deck->get_players_folded($gameid);
					$data['actions'] = $this->Model_deck->get_players_actions($gameid);
					$players = $this->Model_deck->get_players_in_game($gameid);
					$game_state = $this->Model_deck->get_game_state($gameid);
					$data['game_state'] = $game_state;
					$data['winner'] = "";
					$data['timeout'] = $this->Model_deck->get_game_timeout($gameid);
					$data['mystack'] = $this->Model_deck->get_player_stack($gameid,$this->session->userdata('id'));
                    $data['all_players_stacks'] = $this->Model_deck->get_players_stacks($gameid);
					$data['mybet'] = $this->Model_deck->get_player_bet($gameid,$this->session->userdata('id'));
					$data['player_on_button'] = $this->Model_deck->get_player_name($this->Model_deck->get_player_on_button($gameid));
					$data['hand'] = $this->Model_deck->get_player_hand($gameid);
					foreach ($players as $playerid) {
						# code...
						array_push($data['players_in_game'],$this->Model_deck->get_player_name($playerid['playerid']));
					}
				
					if($game_state == "pre_flop"){
						$data['game_status'][0]['table_cards'] = "";
					}
					elseif($game_state == "flop"){
						$data['game_status'][0]['table_cards'] = substr($data['game_status'][0]['table_cards'], 0,8);
					}
					elseif($game_state == "turn"){
						$data['game_status'][0]['table_cards'] = substr($data['game_status'][0]['table_cards'], 0,11);
					}
					elseif($game_state == "river"){
						$data['game_status'][0]['table_cards'] = $data['game_status'][0]['table_cards'];
					}
					elseif($game_state == "endgame"){
                            $players_hands = $this->Model_deck->get_players_hands($gameid);
                            $hands_str = "";
                            foreach ($players_hands as $players_final_hand){
                               
                                $cards_on_table = explode(' ',$data['game_status'][0]['table_cards']);
                                $player_hand = explode(' ',$players_final_hand['player_hand']);
                              
                                $hands_str = $hands_str.$player_hand[0]."+".$player_hand[1]."+".$cards_on_table[0]."+".$cards_on_table[1]."+".$cards_on_table[2]."+".$cards_on_table[3]."+".$cards_on_table[4].",";
                            }
                            $hands_str =substr($hands_str ,0,-1);
                            
                            
                            $xmlstring = file_get_contents('http://appserver-01.alunos.di.fc.ul.pt/~asw000/cgi-bin/findwinners.py?hands='.$hands_str.'&group=asw000');
                            $xml = new SimpleXMLElement($xmlstring);
                            $xml = (array)$xml;//optional
                    
                            $index = intval($xml['indices']->index);
						    $winnerid = $players_hands[$index]['playerid'];
    
							$data['winner'] = $this->Model_deck->get_player_name($winnerid)[0]['username'];
                           
							$data['hand_description']=$xml['winning-rank'];
                            
	
							
							$this->Model_deck->set_ready_player($gameid);
							
							
							$eliminated = $this->Model_deck->player_exit_game($gameid);
							if(sizeof($eliminated) > 0 and !$this->Model_deck->check_if_table_winner($gameid)){
								##ELIMINADO
								echo "<p style ='color:white; margin-top:20px;'>";
								foreach($eliminated as $eli){
									if($eli == $this->session->userdata('id')){ 

									echo "you were eliminated, you lost all your stack <br>";
									}
								else{
									echo "<b>".$this->Model_deck->get_player_name($eli)."</b>";
									echo "was eliminated<br>";}
								}
								echo "</p>";


							}
							elseif( sizeof($eliminated) > 0 and $this->Model_deck->check_if_table_winner($gameid)){
								echo "<h1 style ='color:white; margin-top:100px;'>";
								echo "this game ended, The Winner was <b>".strtoupper($data['winner'])."</b>";  
								echo "<h1>";
							}

									else{
							$this->Model_deck->reset_player_bets($gameid);
				            if($this->Model_deck->all_player_ready($gameid)){
                                $this->Model_deck->set_status_all_ready($gameid);
                                if($this->Model_deck->is_status_all_ready($gameid)){
							       
                                     $this->Model_deck->set_status_all_ready_false($gameid);
                                    $this->Model_deck->unset_ready_player($gameid);
                                    $this->Model_deck->pot_goes_to_winner($gameid,$winnerid);
                                     $this->Model_deck->rematch($gameid, sizeof($players));
                                }
                            }
							}
						} 



					$this->load->view('play',$data);
				}
				else{
					redirect('Main/restricted');
				}
			}
			else{
				redirect("Main/ended_game/".$gameid);
			}
	}
    
    	public function play_update($gameid)
	{
		$sql = "SELECT ended_at FROM game_status WHERE id = $gameid";
      	$query = $this->db->query($sql);
      $result = $query->result_array()[0]['ended_at'];
		if($result == null){
				 $this->load->model('Model_deck');
             
		         $this->load->library('table');
				if($this->session->userdata('is_logged_in')){
                   $this->load->model('Model_users');
                    
                    
                    
                    $data['wallet'] = $this->Model_users->get_my_wallet();
					$data['gameid'] = $gameid;
					$data['timeout'] = $this->Model_deck->get_game_timeout($gameid);
                    $data['is_my_turn'] = $this->Model_deck->is_my_turn($gameid) ;
					if($data['is_my_turn']and ($data['timeout'] != -1)){
					   $data['timer'] = $this->Model_deck->update_timer($gameid);
					}
					$data['username'] = $this->session->userdata('username');
					$data['roomname'] =  $this->Model_deck->get_room_name($gameid);
					$data['game_status'] = $this->Model_deck->get_game_status($gameid);
					$data['players_in_game'] = array();
					$data['players_folded'] = $this->Model_deck->get_players_folded($gameid);
					$data['actions'] = $this->Model_deck->get_players_actions($gameid);
					$players = $this->Model_deck->get_players_in_game($gameid);
					$game_state = $this->Model_deck->get_game_state($gameid);
					$data['game_state'] = $game_state;
					$data['winner'] = "";
                    $data['all_players_stacks'] = $this->Model_deck->get_players_stacks($gameid);
					$data['timeout'] = $this->Model_deck->get_game_timeout($gameid);
					$data['mystack'] = $this->Model_deck->get_player_stack($gameid,$this->session->userdata('id'));
					$data['mybet'] = $this->Model_deck->get_player_bet($gameid,$this->session->userdata('id'));
					$data['player_on_button'] = $this->Model_deck->get_player_name($this->Model_deck->get_player_on_button($gameid));
					$data['hand'] = $this->Model_deck->get_player_hand($gameid);
					foreach ($players as $playerid) {
						# code...
						array_push($data['players_in_game'],$this->Model_deck->get_player_name($playerid['playerid']));
					}
				
					if($game_state == "pre_flop"){
						$data['game_status'][0]['table_cards'] = "";
					}
					elseif($game_state == "flop"){
						$data['game_status'][0]['table_cards'] = substr($data['game_status'][0]['table_cards'], 0,8);
					}
					elseif($game_state == "turn"){
						$data['game_status'][0]['table_cards'] = substr($data['game_status'][0]['table_cards'], 0,11);
					}
					elseif($game_state == "river"){
						$data['game_status'][0]['table_cards'] = $data['game_status'][0]['table_cards'];
					}
					elseif($game_state == "endgame"){
                            $players_hands = $this->Model_deck->get_players_hands($gameid);
                            $hands_str = "";
                            foreach ($players_hands as $players_final_hand){
                               
                                $cards_on_table = explode(' ',$data['game_status'][0]['table_cards']);
                                $player_hand = explode(' ',$players_final_hand['player_hand']);
                              
                                $hands_str = $hands_str.$player_hand[0]."+".$player_hand[1]."+".$cards_on_table[0]."+".$cards_on_table[1]."+".$cards_on_table[2]."+".$cards_on_table[3]."+".$cards_on_table[4].",";
                            }
                            $hands_str =substr($hands_str ,0,-1);
             
                            $xmlstring = file_get_contents('http://appserver-01.alunos.di.fc.ul.pt/~asw000/cgi-bin/findwinners.py?hands='.$hands_str.'&group=asw000');
                            $xml = new SimpleXMLElement($xmlstring);
                            $xml = (array)$xml;//optional
                            if(sizeof($xml['indices']) == 1){
                                $index = intval($xml['indices']->index);
                                $winnerid = $players_hands[$index]['playerid'];

                                $data['winner'] = $this->Model_deck->get_player_name($winnerid)[0]['username'];

                                $data['hand_description']=$xml['winning-rank'];

                                echo "<h1 style ='color:white; margin-top:100px;'>";
                                echo "THE WINNER IS ".strtoupper($data['winner'])  . "<br>WITH ";
                                echo "</h1>";
                                echo "<h2 style ='color:white; margin-top:20px;'>";
                                echo $data['hand_description'];
                                echo "</h2>";
                                echo "<h3 style ='color:white; margin-top:20px;'> Takes all pot: ";
                                echo $data['game_status'][0]['current_pot'];
                                echo "$</h3>";
                                echo "<button onclick=". "window.location.href='javascript:window.history.go(0)'".">Dismiss</button>";
                                
                            }
                            else{
                                echo "<h1 style ='color:white; margin-top:100px;'>";
                                echo "SPLIT POT";
                                echo "</h1>";
                            }
							
							
							$this->Model_deck->set_ready_player($gameid);
							$eliminated = $this->Model_deck->player_exit_game($gameid);
							if(sizeof($eliminated) > 0 and !$this->Model_deck->check_if_table_winner($gameid)){
								##ELIMINADO
								echo "<p style ='color:white; margin-top:20px;'>";
								foreach($eliminated as $eli){
									if($eli == $this->session->userdata('id')){ 

									echo "you were eliminated, you lost all your stack <br>";
									}
								else{
									echo "<b>".$this->Model_deck->get_player_name($eli)."</b>";
									echo "was eliminated<br>";}
								}
								echo "</p>";


							}
							elseif( sizeof($eliminated) > 0 and $this->Model_deck->check_if_table_winner($gameid)){
								echo "<h1 style ='color:white; margin-top:100px;'>";
								echo "this game ended, The Winner was <b>".strtoupper($data['winner'])."</b>";  
								echo "<h1>";
                                $this->Model_deck->pot_goes_to_winner($gameid,$winnerid);
							}

									else{
							$this->Model_deck->reset_player_bets($gameid);
				            if($this->Model_deck->all_player_ready($gameid)){
                                $this->Model_deck->set_status_all_ready($gameid);
                                if($this->Model_deck->is_status_all_ready($gameid)){
							       $this->Model_deck->pot_goes_to_winner($gameid,$winnerid);
                                     $this->Model_deck->set_status_all_ready_false($gameid);
                                    $this->Model_deck->unset_ready_player($gameid);
                                     $this->Model_deck->rematch($gameid, sizeof($players));
                                }
                            }
							}
						} 



					$this->load->view('play_update',$data);
				}
				else{
					redirect('Main/restricted');
				}
			}
			else{
				redirect("Main/ended_game/".$gameid);
			}
	}
	public function restricted()
	{
		$this->load->view('restricted');
	}
}
