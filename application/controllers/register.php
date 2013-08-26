<?php

/**
 * The register class. Contains all the functions that allow a new user to register for the asset manager.
 */
class register extends CI_Controller {

    /**
     * Loads the libraries used for this class.
     */
    function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('simple_error_messages');
 	}

    /**
     * Equivalent to create();
     */
    function index(){
		$this->apiCreate();
 	}

    /**
     * Creates a new user using the given POST data. Logs the creation of the new user. Echos back the new user's email address via JSON.
     *
     * @return bool Returns false on POST validation error.
     */
    function apiCreate(){

    	$data['data']['adminLockedRegistering']=0;
    	if($this->config->item('allow_registering') != 1){
    		$data['data']['adminLockedRegistering']=1;
    		$data['error_message'] = "An admin has blocked registering";
			$this->load->view('utility/notifier',$data);
			return false;
    	}

		$postValidation = array(
			array(
                'field' =>'register_user_first_name'
                ,'label'=>'First Name'
                ,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
	        )
            ,array(
                'field' =>'register_user_last_name'
                ,'label'=>'Last Name'
                ,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
            )
	    	,array(
	    	    'field' =>'register_user_email'
	            ,'label'=>'Email'
	            ,'rules'=>'trim|required|xss_clean|prep_for_form|valid_email|is_unique[users.user_email]|max_length[30]'
	        )
	        ,array(
	    	    'field' =>'register_user_email_repeat'
	            ,'label'=>'Email (Repeat)'
	            ,'rules'=>'trim|required|xss_clean|prep_for_form|valid_email|matches[register_user_email]'
	        )
	        ,array(
	    	    'field' =>'register_user_password'
	            ,'label'=>'Password'
	            ,'rules'=>'trim|required|xss_clean|prep_for_form|min_length[8]|max_length[30]'
	        )
	        ,array(
	    	    'field' =>'register_user_password_repeat'
	            ,'label'=>'Password (Repeat)'
	            ,'rules'=>'trim|required|xss_clean|prep_for_form|matches[register_user_password]'
	        )
	    );

		$this->form_validation->set_rules($postValidation);
		loadSimpleErrorMessages($this);
		$this->form_validation->set_message('is_unique','Already in use!');
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}
		
		$newUser = array(
	    	'user_first_name'    => $this->input->post('register_user_first_name')
	    	,'user_last_name'    => $this->input->post('register_user_last_name')
	    	,'user_email'        => $this->input->post('register_user_email')
	    	,'user_password'     => md5($this->input->post('register_user_password'))
	    	,'user_salt' 		 => substr(md5(uniqid(rand(), true)), 0, 8)
	    );
		
		$this->load->model('register_model');
		$userId = $this->register_model->register($newUser);
		
		$this->log_model->log(array(
			'class'              => 'register'
			,'method'            => 'apiCreate'
			,'user_first_name'   => $newUser['user_first_name']
			,'user_last_name'    => $newUser['user_last_name']
			,'user_email'        => $newUser['user_email']
			,'user_id'           => $userId
		));
		
		$data['data']['user_email'] = $newUser['user_email'];
		$data['happy_message']='You can now log in!';
		$this->load->view('utility/notifier',$data);

 	}
}
?>