<?php 

class Login extends CI_Controller{
	

	public function login_validation(){
        $this->load->model('model_users');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|callback_validate_credentials');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|md5');
		if($this->form_validation->run()){
			$data = array('username' =>$this->input->post('username'), 'is_logged_in'=> 1, 'id' => $this->model_users->get_userID($this->input->post('username')),'stack' => 0);
			$this->session->set_userdata($data);
            
			redirect ('Main/members');
            
		}
		else{
			$this->load->view('login');

		}
	}
	public function login_admin_validation(){

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|callback_validate_credentials_admin');
		$this->form_validation->set_rules('password', 'Password', 'required|trim|md5');
		if($this->form_validation->run()){
            
			$data = array('username' =>$this->input->post('username'), 'is_logged_in'=> 1);
			$this->session->set_userdata($data);
			redirect ('Admin/adminpage');
            
		}
		else{
			$this->load->view('admin');

		}
	}

	public function validate_credentials_admin(){
		$this->load->model('model_users');

		if($this->model_users->can_log_in_admin()){
			return true;
		}
		else{
			$this->form_validation->set_message('validate_credentials_admin','Incorrect username/password');
			return false;
		}


	}
	public function validate_credentials(){
		$this->load->model('model_users');

		if($this->model_users->can_log_in()){
			return true;
		}
		else{
			$this->form_validation->set_message('validate_credentials','Incorrect username/password');
			return false;
		}


	}


	public function logout(){

		$this->session->sess_destroy();
		redirect('Main/login');
	}

	public function signup_validation(){

	$this->load->model('model_users');

		$this->load->library('form_validation');
		$this->form_validation->set_rules('username', 'Username', 'required|trim|is_unique[users.Username]');
		$this->form_validation->set_rules('firstname', 'Firstname', 'required|trim');
		$this->form_validation->set_rules('lastname', 'Lastname', 'required|trim');
		$this->form_validation->set_rules('gender', 'Gender', 'required|trim');
		$this->form_validation->set_rules('distrito', 'Distrito', 'trim');
		$this->form_validation->set_rules('concelho', 'Concelho', 'trim');
		$this->form_validation->set_rules('date', 'Date', 'required|trim|callback_dob_check');
		$this->form_validation->set_rules('country', 'Country', 'required|trim');
		$this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users.Email]');
		$this->form_validation->set_rules('password', 'Password', 'required|trim');
		$this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|trim|matches[password]');
		$this->form_validation->set_message('is_unique','Email or Username already exists');
		
		if($this->form_validation->run()){
		    $this->model_users->add_temp_user();
            $sucess = "Registo com sucesso";
            $this->load->view('signupsucess');
	//			$reg_key = md5(uniqid());
		    
	/*			$config = Array(
				"protocol"      => "smtp",
			"smtp_host"     => "smtp.live.com",

			"smtp_port"     => 587,
		  
			"smtp_user"     => "jony_amado10@hotmail.com",
			"smtp_pass"     => "fixe2017",
			"mailtype"      => "html",
			"charset"       => "ISO-8859-1",
			"starttls"      => true,
			"smtp_crypto"   =>"tls",
			"smpt_ssl"      => "auto",
			"send_multipart"=> false
		   		 );

	    			$this->load->library('email', $config);
	    			$this->email->set_newline("\r\n");
				$this->load->model('model_users');
				$this->email->from('poker.fcul.underground@gmail.com','Joao');
				$this->email->to($this->input->post('email'));
				$this->email->subject('Confirm your Account');
				$message = "<p>Thank You for joining Poker igniter<p>";
				$message .= "<p><a href ='".base_url()."Main/register_user/$reg_key'>Click here</a> to confirm your account</p>";
				$this->email->message($message);
		   */
	/*            if($this->Model_users->add_temp_user()){
		        if($this->email->send()){
		            echo "An email has been Sent!";
		            echo $this->input->post('email');
				 	}
		        else echo "could not send email!";
				 } 
		    else echo "problem adding to database";
	*/
			}
			else{
				$this->load->view('signup');

			}

	}
	public function dob_check($str){
		if (!DateTime::createFromFormat('Y-m-d', $str)) //yes it's YYYY-MM-DD
		{
		    $this->form_validation->set_message('dob_check', 'The {field} has not a valid date format');
		    return FALSE;
		}
		else
		{
		    return TRUE;
		}
}

}



?>
