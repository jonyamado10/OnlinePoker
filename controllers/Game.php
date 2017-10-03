<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Game extends CI_Controller {
     function __construct() {
        parent::__construct();
        $this->load->library('table');
        $this->load->model('Model_deck');

        } 
        

    public function new_game(){
     $this->load->library('form_validation');
        $this->load->model('model_deck');
        $this->form_validation->set_rules('name', 'name', 'required|trim');
        if($this->form_validation->run()){
            if($this->model_deck->create_new_game()){
                redirect("Main/members");
                }
            else{
                echo '<script>alert("You dont have enough money to join this party, choose another one")</script>';
                echo '<script>javascript:window.history.go(-1)</script>';

            }
        }
        else{
              echo '<script>alert("Lobby not created, a lobby name is required")</script>';
                echo '<script>javascript:window.history.go(-1)</script>';

        }

    }
    public function call($gameid){
        if($this->Model_deck->is_my_turn($gameid)){
          
                if($this->Model_deck->set_call($gameid)){
                   if(!$this->Model_deck->last_to_call($gameid)){
                        $next_player = $this->Model_deck->set_next_player($gameid);
                        $last_to_raise = $this->Model_deck->get_last_to_raise($gameid);

                        if($next_player == $last_to_raise){
                            $this->Model_deck->change_game_state($gameid);
                            echo '<script>alert("your matched the current bet")</script>';
                            echo '<script>javascript:window.history.go(-1)</script>';

                        }

                        else {
                            echo '<script>alert("your matched the current bet")</script>';
                            echo '<script>javascript:window.history.go(-1)</script>';
                

                        }   
                
                    }
                    else{ echo '<script>alert("your matched the current bet")</script>';
                          echo '<script>javascript:window.history.go(-1)</script>';
                          }
         
                }
                else{
                    if($this->Model_deck->last_to_call($gameid)){
                    echo '<script>alert("You CALL-ALL-IN, good luck")</script>';
                    echo '<script>javascript:window.history.go(-1)</script>';
                       
                    }
                    else{
                        
                        echo '<script>alert("You CALL-ALL-IN , good luck")</script>';
                        echo '<script>javascript:window.history.go(-1)</script>';
                       
                    }
                    
                
                }
                
        }
        else{
            echo '<script>javascript:window.history.go(-1)</script>';
            echo '<script>alert("its not your turn, please wait")</script>';


        }
    }
    public function bet($gameid){
        if($this->Model_deck->is_my_turn($gameid)){
            $betsize = $this-> input->post('player_bet');
            $mystack = $this->Model_deck->get_player_stack($gameid,$this->session->userdata('id'));
            if ($betsize < $mystack){
                if($this->Model_deck->set_bet($gameid,$betsize)){
                    $this->Model_deck->set_next_player($gameid);
                    $this->Model_deck->set_last_to_raise($gameid);
                    echo '<script>javascript:window.history.go(-1)</script>';
                    echo '<script>alert("your bet has been placed")</script>';
                    
                }
            
                else{
                    echo '<script>javascript:window.history.go(-1)</script>';
                    echo '<script>alert("Invalid Bet")</script>';
                    
                }

            }
            else if($betsize == $mystack) {
                $id = $this->session->userdata('id');
                $sql = "UPDATE plays_game SET all_in = 1 WHERE gameid = $gameid AND playerid = $id";
                $query = $this->db->query($sql);
                
                $this->all_in($gameid);

                }
            else{
                echo '<script>javascript:window.history.go(-1)</script>';
                echo '<script>alert("You dont have that much money, if you want to all-in press all-in button")</script>';
            }
        }
        else{
            echo '<script>javascript:window.history.go(-1)</script>';
            echo '<script>alert("its not your turn, please wait")</script>';

        }
    }

    public function fold($gameid){
        if($this->Model_deck->is_my_turn($gameid)){
         
                if($this->Model_deck->set_fold($gameid)){
                    $next_player = $this->Model_deck->set_next_player($gameid);
                    $last_to_raise = $this->Model_deck->get_last_to_raise($gameid);
                    if ($this->Model_deck->last_to_fold($gameid)){

                    }
                    else if($next_player == $last_to_raise and !$this->Model_deck->last_to_fold($gameid)){
                        $this->Model_deck->change_game_state($gameid);
                    }

                    echo '<script>alert("you folded")</script>';
                    echo '<script>javascript:window.history.go(-1)</script>';
                
                }
                else{
                    echo '<script>alert("something went wrong, please try again")</script>';
                    echo '<script>javascript:window.history.go(-1)</script>';
                    
                }


        }
        else{
            echo '<script>alert("its not your turn, please wait")</script>';
            echo '<script>javascript:window.history.go(-1)</script>';

        }

        
    }
    public function all_in($gameid){
        if($this->Model_deck->is_my_turn($gameid)){
            if(!$this->Model_deck->last_to_call($gameid)){
                if($this->Model_deck->set_all_in($gameid)){
                        $next_player = $this->Model_deck->set_next_player($gameid);
                        $last_to_raise = $this->Model_deck->set_last_to_raise($gameid);
                        if($next_player == $last_to_raise){
                            $this->Model_deck->change_game_state($gameid);
                            echo '<script>alert("you ALL-IN1, good luck")</script>';
                            echo '<script>javascript:window.history.go(-1)</script>';
                        }
              
                        else{
                            $last_to_raise = $this->Model_deck->set_last_to_raise_by_id($gameid,$next_player);
                            echo '<script>alert("you ALL-IN11, good luck")</script>';
                            echo '<script>javascript:window.history.go(-1)</script>';
                        }
                }
                else{
                            echo '<script>alert("you ALL-IN")</script>';
                            echo '<script>javascript:window.history.go(-1)</script>';

                }
            }
        else{
             if($this->Model_deck->set_call_all_in($gameid)){
   
                            echo '<script>alert("you ALL-IN, good luck")</script>';
                            echo '<script>javascript:window.history.go(-1)</script>';  
                }
                else{
                        echo '<script>alert("something went wrong ")</script>';
                        echo '<script>javascript:window.history.go(-1)</script>';

                }
            }


        
        }
        else{
            echo '<script>alert("its not your turn, please wait")</script>';
            echo '<script>javascript:window.history.go(-1)</script>';

        }

    } 
       public function check($gameid){
        if($this->Model_deck->is_my_turn($gameid)){
            if($this->Model_deck->set_check($gameid)){
                $next_player = $this->Model_deck->set_next_player($gameid);
                $last_to_raise = $this->Model_deck->get_last_to_raise($gameid);

                if($next_player == $last_to_raise){
                    $this->Model_deck->change_game_state($gameid);
                }

                echo '<script>alert("you checked")</script>';
                echo '<script>javascript:window.history.go(-1)</script>';
                
            }
            else{
                echo '<script>alert("you cant check, call or fold")</script>';
                echo '<script>javascript:window.history.go(-1)</script>';
                
            }


        }
        else{
            echo '<script>alert("its not your turn, please wait")</script>';
            echo '<script>javascript:window.history.go(-1)</script>';
        }
        
    }
    public function ready($gameid){
        $id = $this->session->userdata('id');
        $sql = "UPDATE plays_game SET ready = 1 WHERE gameid = $gameid and playerid = $id";
        $query = $this->db->query($sql);
        echo '<script>alert("next round will start when all players are ready")</script>';
        echo '<script>javascript:window.history.go(-1)</script>';
    }

}

?> 
