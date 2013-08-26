<?php

/**
 * This is the verifyLogin class. It contains all the functions that get run before one logs in (to confirm they should actually be able to log in).
 */
class VerifyLogin extends CI_Controller {

    /**
     * Loads the libraries used or this class.
     */
    function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('login_model','',TRUE);
		$this->load->helper('simple_error_messages');
	}

    /**
     * @return bool Returns true if the function was called vis AJAX
     */
    function usingAjax(){
		return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	}

    /**
     * Checks the email and password of the user to make sure the user exists and the password is correct.
     * Logs on failed login and on successful login.
     * Redirects to the dashboard on successful login.
     *
     * @return bool Returns false if POST validation fails.
     */
    function index(){
		$postValidation = array(
			array(
				'field'=>'user_email',
				'label'=>'Email',
				'rules'=>'trim|required|valid_email|xss_clean|prep_for_form|max_length[30]'
			),
			array(
				'field'=>'user_password',
				'label'=>'Password',
				'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]|callback_check_database'
			)
		);
		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		$messages['data']=$this->input->post();
		if($this->form_validation->run() == FALSE){
			$this->log_model->log(array(
				'class'			=> 	'login',
				'method'		=> 	'failed login',
				'description'	=> 	json_encode(array(
					'user_email'	=>	$this->input->post('user_email')//,
					//'user_password'	=>	$this->input->post('user_password')
				))
			));
			$messages['failed_fields'] = $this->form_validation->error_array();
			$messages['error_message']=validation_errors();
			$this->load->view('utility/notifier',$messages);
			return false;
		}

		$this->log_model->log(array(
			'class' => 'login',
			'method' => 'user login'
		));

		$messages['happy_message']='Logged In! - Redirecting...';
		$this->load->view('utility/notifier',$messages);
	
	}


    /**
     * Helper function for index(). Checks the given password against the email to ensure it's the correct email/password combo.
     * Creates the cookie "PHPAssetManager-cookieEmail" to store the last logged in email. (for quicker logins later).
     *
     * @param string $user_password The given password.
     * @return bool Returns false if user is disabled or email/password is incorrect.
     */
    function check_database($user_password){
		//Field validation succeeded.  Validate against database
		$email = $this->input->post('user_email');

		//query the database
		$result = $this->login_model->login($email, $user_password);

		if($result){
			$sess_array = array();
			foreach($result as $row){
				if($row->is_disabled){
					$this->form_validation->set_message('check_database', 'Your account is disabled.');
					return FALSE;
				}
				$sess_array = array(
					'user_id' => $row->user_id
					//,'user_email' => $row->user_email
					//,'user_first_name' => $row->user_first_name
					//,'user_last_name' => $row->user_last_name
					//,'user_theme_id' => $row->user_theme_id
				);
				$this->session->set_userdata('logged_in', $sess_array);
				
				$cookie = array(
					'name'   => 'PHPAssetManager-cookieEmail',
					'value'  => json_encode(array('email' => $email)),
					'expire' => '31557600' //1 year
				);
				set_cookie($cookie); 
			}
			return TRUE;
		}else{
			$this->form_validation->set_message('check_database', 'Invalid Username/Password');
			return FALSE;
		}
	}
}
?>