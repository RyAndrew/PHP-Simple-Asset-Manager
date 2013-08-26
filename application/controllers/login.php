<?php

/**
 * The login class. Contains functions that allow a user to login, logout, and reset their password.
 */
class Login extends CI_Controller {

    /**
     * Loads up libaries and models used for this class.
     */
    function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('login_model','',TRUE);
		$this->load->helper('simple_error_messages');
 	}

    /**
     * Shows the login page. If the user is already logged in, it will redirect them to the asset dashboard.
     */
    function index(){
		if($this->session->userdata('logged_in')){
			redirect('asset');
			die();
		}
			
	  	$this->load->helper(array('form', 'url'));
	   	$this->load->view('templates/header',array('hideLinks'=>true));
	   	$this->load->view('login/login_view');
	   	$this->load->view('templates/footer');
 	}

    /**
     * Logs out the user and destroys their session. Also logs the event.
     */
    function logout(){

	 	$this->log_model->log(array(
			'class' => 'login',
			'method' => 'user logout'
		));

	   	$this->session->unset_userdata('logged_in');
	   	$this->session->sess_destroy();
	   	redirect('asset', 'refresh');
 	}

    /**
     * The user (from a given email in POST) password is reset to a random string, and a email is sent to that user. This will log the user out everywhere. Also logs the event and echos back the email in JSON.
     *
     * @return bool Returns false on POST validation error.
     */
    function apiResetPassword(){
 		$postValidation = array(
			array(
		    'field'=> 	'reset_email',
		    'label'=> 	'Password Reset Email',
		    'rules'=>	'trim|required|xss_clean|prep_for_form|valid_email|max_length[30]|callback_check_email'
		  )
		);
		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}

		$userInfo = $this->user_model->getUserByEmail($this->input->post('reset_email'));
		
		//$newPass=base_convert(mt_rand(base_convert(pow(10, 8-1), 36, 10), pow(36, 8)), 10, 36); //will generate random characters. Change to something else if you want...
		$newPass = substr(md5(uniqid(rand(), true)), 0, 8);


		$this->login_model->changePassword($this->input->post('reset_email'),$newPass);
		
		$this->load->library('email');
		$this->email->initialize(array('mailtype'=>'html'));
		$this->email->from($this->config->item('system_email'),$this->config->item('system_email_name'));
		$this->email->to($this->input->post('reset_email'));
		$this->email->subject($this->config->item('websiteTitle').' - Password Reset');
		$this->email->message('You recently requested your Asset Manager password to be reset. <br /><br /> Here is your new password: <br /><b>'.$newPass.'</b><br /><br />'.anchor('login','Login Now'));
		$this->email->send();
		
		$this->log_model->log(array(
			'user_email' => $this->input->post('reset_email'),
			'class' => 'login',
			'method' => 'apiPasswordReset',
			'user_first_name' => $userInfo['user_first_name'],
			'user_last_name' => $userInfo['user_last_name']
		));
		
		$this->user_model->cleanSessionsIncludingOwn($userInfo['user_id']);

		$data['happy_message']="Check your email for your new password!";
		$data['data']['reset_email']=$this->input->post('reset_email');
    	$this->load->view('utility/notifier',$data);
 	}

    /**
     * Checks if an email exists in the database.
     *
     * @param $email An email address
     * @return bool Returns true if the email exists in the database.
     */
    function check_email($email){ //checks if it's email in the database.
 		$this->form_validation->set_message('check_email', 'This account does not exist');
 		return $this->login_model->checkEmail($email);
 	}

}

?>