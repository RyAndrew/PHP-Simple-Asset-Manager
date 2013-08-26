<?php

/**
 * The system class. Contains "admin-like" functions that allow for global setting changes.
 */
class system extends CI_Controller {

    /**
     * Loads up all the external libraries used for this class.
     * Will also redirect to login if the user isn't logged in.
     * It will also redirect to a unauthorized error
     * if the current user is not an admin.
     */
    function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('asset_model');
		$this->load->model('attribute_model');
		$this->load->model('type_model');
		$this->load->model('system_model');
		$this->load->model('note_model');
		$this->load->helper('simple_error_messages');
		if(!$this->session->userdata('logged_in')){
			redirect('login', 'refresh');
			die();
		}

		if(!$this->user_model->curIsAdmin()){
			redirect('error/notAuthorized', 'refresh');
			die();
		}

	}

    /**
     * Equivalent to settings();
     */
    function index(){
		$this->settings();
	}

    /**
     * Shows the system settings page.
     */
    function settings(){
		$this->load->view('templates/header');
		$this->load->view('system/system_view',array('websiteTitle' => $this->config->item('websiteTitle')));
		$this->load->view('templates/footer');
	}

    /**
     * Shows the user management page.
     */
    function userManagement(){
		$data['users'] = $this->user_model->getUsers();
		$this->load->view('templates/header');
		$this->load->view('system/userManagement',$data);
		$this->load->view('templates/footer');
	}

    /**
     * Changes the website title using given POST data.
     * Logs the change and echos the new website name back via JSON.
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiChangeWebsiteName(){
		$postValidation = array(
			array(
				'field' =>'website_title'
				,'label'=>'Website Title'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
		);
		$this->form_validation->set_rules($postValidation);
		loadSimpleErrorMessages($this);
		$data['data']=$this->input->post();
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}else{
			$data['happy_message']='Website Title Updated!';
			if($this->config->item('websiteTitle')!=$data['data']['website_title']){
				$this->log_model->log(array(
					'class' 	=> 'system'
					,'method' 	=> 'apiChangeWebsiteName'
					,'data_from'=> $this->config->item('websiteTitle')
					,'data_to' 	=> $data['data']['website_title']
				));
				$this->system_model->setWebsiteTitle($data['data']['website_title']);
			}
			$this->load->view('utility/notifier',$data);
		}
	}

	function apiUpdateSiteSettings(){
		$postValidation = array(
			array(
				'field' =>'num_recent_created'
				,'label'=>'Number of Recently Created'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural|greater_than[4]|less_than[21]'
			)
			,array(
				'field' =>'num_recent_changed'
				,'label'=>'Number of Recently Changed'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural|greater_than[4]|less_than[21]'
			)
			,array(
				'field' =>'allow_registering'
				,'label'=>'Allow Registering'
				,'rules'=>'trim|xss_clean|prep_for_form|is_natural|max_length[1]'
			)
			,array(
				'field' =>'show_build_date'
				,'label'=>'Show Build Date'
				,'rules'=>'trim|xss_clean|prep_for_form|is_natural|max_length[1]'
			)
			,array(
				'field' =>'admin_email'
				,'label'=>'Admin Email'
				,'rules'=>'trim|required|xss_clean|prep_for_form|valid_email|max_length[30]'
			)
			,array(
				'field' =>'system_email'
				,'label'=>'System Email'
				,'rules'=>'trim|required|xss_clean|prep_for_form|valid_email|max_length[30]'
			)
			,array(
				'field' =>'system_email_name'
				,'label'=>'System Email'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
		);
		$this->form_validation->set_rules($postValidation);
		loadSimpleErrorMessages($this);
		$data['data']=$this->input->post();
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}else{

			//Time to account for checkboxes... Ugh. I'd use dropdown menus, but they require unnecessary clicks.
			if($this->input->post('allow_registering')!=1){
				$data['data']['allow_registering'] = 0;
			}
			if($this->input->post('show_build_date')!=1){
				$data['data']['show_build_date'] = 0;
			}

			foreach($data['data'] as $name => $value){
				if($this->config->item($name)!=$value){
					$this->log_model->log(array(
						'class' 	=> 'system'
						,'method' 	=> 'apiUpdateSiteSettings'
						,'data_from'=> $this->config->item($name)
						,'data_to' 	=> $value
						,'description' => json_encode(array('Field' => $name))
					));
				}
			}
			$this->system_model->setWebsiteSettings($data['data']);
			$data['happy_message']='Settings updated';
			$this->load->view('utility/notifier',$data);
		}					
	}


    /**
     * Deletes a user using given POST data. Logs the deletion of the user.
     * Echos back the deleted user info via JSON.
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiDeleteUser(){
		$postValidation = array(
			array(
				'field' =>'user_id'
				,'label'=>'User ID'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural|callback_check_delete'
			)
		);
		$this->form_validation->set_rules($postValidation);
		$data['data']=$this->input->post();
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$this->load->view('utility/notifier',$data);
			return false;
		}else{
			
			$user = $this->user_model->getUser($data['data']['user_id']);
			unset($user['user_password']);
			unset($user['user_salt']);

			$this->log_model->log(array(
				'class'			=> 'system'
				,'method' 		=> 'deleteuser'
				,'description'  => json_encode($user)
			));

			$this->user_model->deleteUser($data['data']['user_id']);

			$this->user_model->cleanSessionsIncludingOwn($data['data']['user_id']);

			$data['happy_message'] = "Enjoy your trip into deletion,";
			$this->load->view('utility/notifier',$data);
		}
	}

    /**
     * Creates a new user using given POST data. Logs the creation of the user.
     * Echos back the new user data via JSON.
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiAddUser(){
		$postValidation = array(
			array(
				'field' =>'addUserEmailField'
				,'label'=>'User Email'
				,'rules'=>'trim|required|xss_clean|prep_for_form|valid_email|max_length[30]|is_unique[users.user_email]'
			)
			,array(
				'field' =>'addUserFnameField'
				,'label'=>'User First Name'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' =>'addUserLnameField'
				,'label'=>'User Last Name'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' =>'addUserAdminField'
				,'label'=>'Is Admin'
				,'rules'=>'trim|xss_clean|prep_for_form|is_natural|max_length[1]'
			)
			,array(
				'field' =>'addUserDisableField'
				,'label'=>'Is Disabled'
				,'rules'=>'trim|xss_clean|prep_for_form|is_natural|max_length[1]'
			)
			,array(
				'field' =>'addUserPasswdField'
				,'label'=>'Pasword'
				,'rules'=>'trim|required|xss_clean|prep_for_form|min_length[8]|max_length[30]|callback_check_password_match'
			)
			,array(
				'field' =>'addUserPasswdField2'
				,'label'=>'Pasword (Again)'
				,'rules'=>'trim|required|xss_clean|prep_for_form|'
			)
		);
		
		loadSimpleErrorMessages($this);
		$this->form_validation->set_message('is_unique', 'There is already a user with that email address!');

		$this->form_validation->set_rules($postValidation);
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}else{
			
			$user_salt =substr(md5(uniqid(rand(), true)), 0, 8);

			$newUser=array(
				'user_email' 		=> $this->input->post('addUserEmailField')
				,'user_first_name' 	=> $this->input->post('addUserFnameField')
				,'user_last_name' 	=> $this->input->post('addUserLnameField')
				,'is_admin' 		=> $this->input->post('addUserAdminField')
				,'is_disabled' 		=> $this->input->post('addUserDisableField')
				,'user_salt' 		=> $user_salt
				,'user_password' 	=> hash("sha512",hash("md5",$user_salt.$this->input->post('addUserPasswdField')))
			);

			$newUser['user_id'] = $this->user_model->addUser($newUser);
			unset($newUser['user_password']);
			unset($newUser['user_salt']);

			$this->log_model->log(array(
				'class'			=> 'system'
				,'method' 		=> 'adduser'
				,'description'  => json_encode($newUser)
			));

			$data['data'] = $newUser;
			$data['happy_message']='Added User: '.$this->input->post('addUserEmailField');
			$this->load->view('utility/notifier',$data);
		}
	}

    /**
     * Loads up info on a user from given POST data and echos it back via JSON.
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiLoadUserInfo(){
		$postValidation = array(
			array(
				'field' =>'user_id'
				,'label'=>'User ID'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural'
			)
		);
		$this->form_validation->set_rules($postValidation);
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$this->load->view('utility/notifier',$data);
			return false;
		}else{
			$data['data'] = $this->user_model->getUser($this->input->post('user_id'));
			unset($data['data']['user_password']);
			$data['happy_message']='User Info Loaded for '.$data['data']['user_id'];
			$this->load->view('utility/notifier',$data);
		}
	}

    /**
     * Updates user from given POST data.
     * Logs any changes made on the user.
     * If a user's password is changed, all sessions of the user are destroyed, excluding the session of the user who changed the password (if it was him/herself).
     * If a user is disabled, all sessions of the user are destroyed, including the session of the user who disabled the user (if it was him/herself).
     * Updated user info is echoed back via JSON.
     *
     * @return bool Returns false if POST validation fails.
     */
    function apiUpdateUserInfo(){
		$postValidation = array(
			array(
				'field' =>'editUserIdField'
				,'label'=>'User ID'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural'
			)
			,array(
				'field' =>'editOrgEmailField'
				,'label'=>'Hidden original email'
				,'rules'=>'trim|required|xss_clean|prep_for_form|valid_email|max_length[30]'
			)
			,array(
				'field' =>'editOrgAdminField'
				,'label'=>'Hidden original Is Admin'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural|max_length[1]'
			)
			,array(
				'field' =>'editOrgDisableField'
				,'label'=>'Hidden original Is Disabled'
				,'rules'=>'trim|required|xss_clean|prep_for_form|is_natural|max_length[1]'
			)
			,array(
				'field' =>'editUserEmailField'
				,'label'=>'User Email'
				,'rules'=>'trim|required|xss_clean|prep_for_form|valid_email|max_length[30]|callback_check_email'
			)
			,array(
				'field' =>'editUserFnameField'
				,'label'=>'User First Name'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' =>'editUserLnameField'
				,'label'=>'User Last Name'
				,'rules'=>'trim|required|xss_clean|prep_for_form|max_length[30]'
			)
			,array(
				'field' =>'editUserAdminField'
				,'label'=>'Is Admin'
				,'rules'=>'trim|xss_clean|prep_for_form|is_natural|max_length[1]|callback_check_admin'
			)
			,array(
				'field' =>'editUserDisableField'
				,'label'=>'Is Disabled'
				,'rules'=>'trim|xss_clean|prep_for_form|is_natural|max_length[1]|callback_check_disabled'
			)
			,array(
				'field' =>'editUserPasswdField'
				,'label'=>'New Pasword'
				,'rules'=>'trim|xss_clean|prep_for_form|min_length[8]|max_length[30]|callback_check_password_match'
			)
			,array(
				'field' =>'editUserPasswdField2'
				,'label'=>'New Pasword (Again)'
				,'rules'=>'trim|xss_clean|prep_for_form|'
			)
		);
		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
			return false;
		}else{
			$data['happy_message']='User Info saved for '.$this->input->post('editUserEmailField');
			$olddata = $this->user_model->getUser($this->input->post('editUserIdField'));
			$dataToUpdate = array(
				'user_id' 			=> $this->input->post('editUserIdField')
				,'user_email' 		=> $this->input->post('editUserEmailField')
				,'user_first_name' 	=> $this->input->post('editUserFnameField')
				,'user_last_name' 	=> $this->input->post('editUserLnameField')
				,'is_admin' 		=> $this->input->post('editUserAdminField')
				,'is_disabled' 		=> $this->input->post('editUserDisableField')
			);
			//changing password - clean sessions
			if($this->input->post('editUserPasswdField')){
				$dataToUpdate['user_password'] = hash("sha512",hash('md5',$olddata['user_salt'].$this->input->post('editUserPasswdField')));
				$this->user_model->cleanSessions($this->input->post('editUserIdField'));
			}

			if($this->input->post('editUserDisableField')){
				//Logs this person out everywhere. They are disabled now.
				$this->user_model->cleanSessionsIncludingOwn($this->input->post('editUserIdField'));
			}

			$this->user_model->updateUser($dataToUpdate);
			

			$data['data'] = $this->user_model->getUser($this->input->post('editUserIdField'));

			//log this! (check for changes)
			foreach($dataToUpdate as $name=>$value){
				if($value!=$olddata[$name]){
					
					if($name!="user_theme"){
						if($name=="user_password"){
							$olddata[$name]="**OLD PASSWORD***";
							$value="**NEW PASSWORD***";
						}
						$this->log_model->log(array(
							'class' => 'system',
							'method' => 'edituser',
							'data_name' => $name,
							'data_from' => $olddata[$name],
							'data_to' => $value
						));
					}
				}
			}
			unset($data['data']['user_password']);
			unset($data['data']['user_salt']);
			$this->load->view('utility/notifier',$data);
		}
	}

    /**
     * Checks if it is ok to delete a given user.
     * Checks to make sure there will still be at least 1 non-disabled admin
     *
     * @param $user_id the user to delete
     * @return bool Returns true/false if is ok to delete the given user.
     */
    function check_delete($user_id){
		$this->form_validation->set_message('check_delete', 'You must have at least 1 non-disabled admin!');
		$user = $this->user_model->getUser($user_id);
		if($user['is_admin']==1 && $user['is_disabled']==0 && ($this->user_model->countNonDisabledAdmins()-1)<1){
			return false;
		}
		return true;
	}

    /**
     * Checks to make sure there are no duplicate emails in the database.
     *
     * @param $email The email to check.
     * @return bool Returns false if the email is already in the database.
     */
    function check_email($email){
		$this->form_validation->set_message('check_email', 'There is already a user with that email address!');
		return !($this->user_model->checkEmailExists($email) && ($this->input->post('editOrgEmailField') != $email));
	}

    /**
     * Checks the given first password with the other password via POST. It ensures the passwords are the same.
     *
     * @param $password The first password.
     * @return bool Returns false if the passwords do not match.
     */
    function check_password_match($password){
		$this->form_validation->set_message('check_password_match', 'The new passwords do not match!');
		return($password==$this->input->post('editUserPasswdField2') || $password==$this->input->post('addUserPasswdField2'));
	}

    /**
     * Checks to make sure if the user is demoted, there will be at least one non-disabled admin.
     *
     * @param $value The changed isAdmin value
     * @return bool Returns false if it is not safe to change the admin status of the user.
     */
    function check_admin($value){
		$this->form_validation->set_message('check_admin', 'You must have at least 1 non-disabled admin!');
		if($this->input->post('editOrgDisableField')!=1){
			if(!($this->input->post('editOrgAdminField') == 1 && $value != 1 && ($this->user_model->countNonDisabledAdmins()-1)<=0)){
				return true;
			}
		}else{
			return true;
		}
		return false;
	}

    /**
     * Checks to make sure if the user is disabled, there will be at least one non-disabled admin.
     *
     * @param $value The changed isDisabled value
     * @return bool Returns false if it is not safe to change the disabled status of the user.
     */
    function check_disabled($value){
		$this->form_validation->set_message('check_disabled', 'You cannot disable all your admins!');
		if($this->input->post('editOrgAdminField')==1){
			if(!($this->input->post('editUserAdminField')==1 && $this->input->post('editOrgDisableField') != 1 && $value == 1 && ($this->user_model->countAdmins() - $this->user_model->countDisabledAdmins() == 1))){
				return true;
			}
		}else{
			return  true;
		}
		return false;
	}

	function snmpSettings(){
		$data['all_attributes']=$this->attribute_model->getAttributes(true);
		$data['all_note_types']=$this->note_model->getNoteTypes(true);
		$this->load->view('templates/header');
		$this->load->view('system/snmp_view',$data);
		$this->load->view('templates/footer');
	}

	function apiSnmpWalk(){
		$postValidation = array(
			array(
				'field' =>'ip_to_walk'
				,'label'=>'Ip Address'
				,'rules'=>'trim|required|xss_clean|prep_for_form|valid_ip'
			)
			,array(
				'field' =>'object_id'
				,'label'=>'Object Id'
				,'rules'=>'trim|required|xss_clean|prep_for_form'
			)
		);
		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		$data['data']=$this->input->post();
		if($this->form_validation->run() == FALSE){
			$data['failed_fields'] = $this->form_validation->error_array();
			$data['error_message'] = validation_errors();
			$this->load->view('utility/notifier',$data);
			return false;
		}
		$data['happy_message']='Recieved Results!';
		try {
			$walkdata = snmprealwalk($this->input->post('ip_to_walk'),'public',$this->input->post('object_id'));
		}catch (Exception $e) {
			$data['error_message'] = 'snmprealwalk Failed with Error: '.$e->getMessage();
			$this->load->view('utility/notifier',$data);
			return false;
		}
		$data['data']['walkResults'] = print_r($walkdata,true);
		$this->load->view('utility/notifier',$data);
		
	}
}
?>