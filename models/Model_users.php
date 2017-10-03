<?php  
class Model_users extends CI_model{

	public function can_log_in(){

		$this->db->where('username',$this->input->post('username'));
		$this->db->where('password',md5($this->input->post('password')));
		$query = $this->db->get('users');

		if($query->num_rows()==1){
				return true;

		}
		else{

			return false;
		}
	}
	public function can_log_in_admin(){

		$this->db->where('username',$this->input->post('username'));
		$this->db->where('password',md5($this->input->post('password')));
		$query = $this->db->get('admin');

		if($query->num_rows()==1){
				return true;

		}
		else{

			return false;
		}
	}
	public function add_temp_user(){

		$data = array(
			'email' => $this -> input->post('email'),
			'username' => $this -> input->post('username'),
			'firstname' => $this -> input->post('firstname'),
			'lastname' => $this -> input->post('lastname'),
			'gender' => $this -> input->post('gender'),
			'birthdate' => $this -> input->post('date'),
			'country' => $this -> input->post('country'),
			'district' => $this -> input->post('district'),
			'county' => $this -> input->post('county'),
			'password' => md5($this -> input->post('password'))
//			'chave' => $key
			);
		$query = $this->db->insert('users',$data);
		if($query){
			return true;
		} else{
			return false;
		}

    }
    
    function get_userID($username){
        $this->db->where('username',$username);
        $query = $this->db->get('users');       
        foreach ($query->result() as $row)
            {
                $idd = $row->id;
            }
            return $idd;

}
	function get_my_wallet(){
		$userid = $this->session->userdata('id');
		$sql = "SELECT wallet FROM users WHERE id = $userid";
      	$query = $this->db->query($sql);
		$result = $query->result_array()[0]['wallet'];
		return $result;
	}
		

}







?>