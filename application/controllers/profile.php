<?php
/**
 * The profile class. Contains the functions to allow a user to modify personal profile settings.
 */
class profile extends CI_Controller {

    /**
     * Loads the libraries and helpers used for this class.
     * If the user is not logged in, then they are pushed to the login page.
     */
    function __construct(){
 		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('simple_error_messages');

		if(!$this->session->userdata('logged_in')){
			redirect('login', 'refresh');
			die();
		}
 	}

    /**
     * Equivalent to dispEdit().
     */
    function index(){
 		$this->dispEdit();
 	}

    /**
     * Changes the logged in user's password using given POST data. Also logs the event.
     *
     * @return bool Returns false on POST validation error.
     */
    function apiChangePassword(){
 		$session_data = $this->session->userdata('logged_in');
 		$user_id=$session_data['user_id'];
 		
 		$postValidation = array(
			array(
				'field'		=>'new_password'
				,'label'	=>'New Password'
				,'rules'	=>'trim|required|xss_clean|prep_for_form|min_length[6]|max_length[30]'
			)
			,array(
				'field'		=>'new_password_again'
				,'label'	=>'New Password Again'
				,'rules'	=>'required|matches[new_password]'
			)
		);

 		loadSimpleErrorMessages($this);
 		$this->form_validation->set_message('matches','The Passwords do not match!');
		$this->form_validation->set_rules($postValidation);
		$data['user']=$this->user_model->getUser($user_id);
		
		if($this->form_validation->run() == FALSE){
 			$messages['failed_fields'] = $this->form_validation->error_array();
			$messages['error_message']=validation_errors();
			$this->load->view('utility/notifier',$messages);
			return false;
		}else{
			$this->user_model->updateUser(array(
				'user_id' => $user_id
				,'user_password' => hash('sha512',hash('md5',$data['user']['user_salt'].$this->input->post('new_password')))
			));
			$this->user_model->cleanSessions($user_id);
			$this->log_model->log(array(
				'class' 	=> 'profile'
				,'method' 	=> 'apiChangePassword'
			));
			$messages['happy_message']='Password Changed!';
			$this->load->view('utility/notifier',$messages);
		}
		
 	}

    /**
     * Updates the user profile using given POST data. Logs any changes made to the profile.
     * Echos back an array with the changed data via JSON.
     *
     * @return bool Returns false on POST validation error.
     */
    function apiUpdateProfile(){
		$session_data = $this->session->userdata('logged_in');
 		$user_id=$session_data['user_id'];
		
		$data['user']=$this->user_model->getUser($user_id);
	
 		$postValidation = array(
			array(
				'field'=>'user_first_name',
				'label'=>'First Name',
				'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			),
			array(
				'field'=>'user_last_name',
				'label'=>'Last Name',
				'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			),
			array(
				'field'=>'user_theme',
				'label'=>'Theme Selection',
				'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
			
		);

 		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		$messages['data']=$this->input->post();
		if($this->form_validation->run() == FALSE){
			$messages['failed_fields'] = $this->form_validation->error_array();
			$messages['error_message']=validation_errors();
			$this->load->view('utility/notifier',$messages);
			return false;
		}else{
			$profileData=array(
				'user_id' 			=> $user_id
				,'user_first_name' 	=> $this->input->post('user_first_name')
				,'user_last_name' 	=> $this->input->post('user_last_name')
				,'user_theme' 		=> $this->input->post('user_theme')
			);

			$this->user_model->updateUser($profileData);
			foreach($profileData as $name=>$value){
				if($value!=$data['user'][$name]){ //checking for a change. Don't care about theme change
					if($name!="user_theme"){
						$dataToLog=array(
							'class' 		=> 'profile'
							,'method' 		=> 'edit'
							,'data_name' 	=> $name
							,'data_from' 	=> $data['user'][$name]
							,'data_to' 		=> $value
						);
						if($name=="user_password"){
							$dataToLog['data_from'] = '**Old Password**';
							$dataToLog['data_to'] = '**New Password**';
						}
						$this->log_model->log($dataToLog);
					}
				}
			}
			
			$messages['happy_message']='Profile Updated!';
			$messages['data']['theme_filename']=$this->user_model->getThemeOfUser($user_id);
			$this->load->view('utility/notifier',$messages);
		}
	}

    /**
     * Shows the edit profile page.
     */
    function dispEdit(){
 		$session_data = $this->session->userdata('logged_in');
 		$user_id=$session_data['user_id'];
		$data['user']=$this->user_model->getUser($user_id);
		$data['allThemes']=array();
		$dir = opendir('css/themes');
		$data['allThemes']['']="[None]";
		while ($file = readdir($dir)) {
			if ($file != "." && $file != "..") {
				$data['allThemes'][str_replace(".css","",$file)] = str_replace(".css","",$file);
			}
		}
		closedir($dir);
		$this->load->view('templates/header');
		$this->load->view('profile/edit_profile',$data);
		$this->load->view('templates/footer');
	}
}

?>