<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->load->library('table');
        $this->load->model('Admin_model');
        
        if( !$this->Admin_model->getAdmin($this->session->userdata('username')) ) {
            echo "nao existe admin ";
            $this->session->sess_destroy();
	    redirect('Main/restricted');
        } 
        
    }

    function adminpage() {
        
        echo $this->session->userdata('is_logged_in');
		if($this->session->userdata('is_logged_in')){
            
	        $data['users'] = $this->Admin_model->get_members();
            $template = array('table_open'  => '<table border="6" class="display table" id = "tabela">');
            $this->table->set_template($template);
            $this->load->view('adminpage', $data, $template);
		}
		else{
			redirect('Main/restricted');
		}

    }
        function games_started() {
        
        echo $this->session->userdata('is_logged_in');
		if($this->session->userdata('is_logged_in')){
            
	        $data['games'] = $this->Admin_model->get_games_started();
            $template = array('table_open'  => '<table border="6" class="display table" id = "tabela">');
            $this->table->set_template($template);
            $this->load->view('games_started', $data, $template);
		}
		else{
			redirect('Main/restricted');
		}

    }
        function games_ended() {
        
        echo $this->session->userdata('is_logged_in');
		if($this->session->userdata('is_logged_in')){
            
	        $data['games'] = $this->Admin_model->get_games_ended();
            $template = array('table_open'  => '<table border="6" class="display table" id = "tabela">');
            $this->table->set_template($template);
            $this->load->view('games_ended', $data, $template);
		}
		else{
			redirect('Main/restricted');
		}

    }
        function games_waiting() {
        
		if($this->session->userdata('is_logged_in')){
            
	        $data['games_waiting'] = $this->Admin_model->get_games_waiting();
            $template = array('table_open'  => '<table border="6" class="display table" id = "tabela">');
            $this->table->set_template($template);
            $this->load->view('games_waiting', $data, $template);
		}
		else{
			redirect('Main/restricted');
		}

    }
     function games_player_data() {
        
		if($this->session->userdata('is_logged_in')){
            
	        $data['games'] = $this->Admin_model->get_players_on_tables_data();
            $template = array('table_open'  => '<table border="6" class="display table" id = "tabela">');
            $this->table->set_template($template);
            $this->load->view('games_player_data', $data, $template);
		}
		else{
			redirect('Main/restricted');
		}

    }
}
?>