<?php
class installer extends CI_Controller {
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->model('asset_model');
		$this->load->model('attribute_model');
		$this->load->model('type_model');
		$this->load->model('system_model');
		$this->load->helper('simple_error_messages');
	}
	function index($step=1){
		$this->load->view('templates/header');
		$this->load->view('installer/start_install_view');
		$this->load->view('templates/footer');
		/*
		$this->load->view('templates/header');
		if(!is_numeric($step)){
			$this->load->view('installer/step1');
		}else{
			$this->load->view('installer/step'.$step);
		}
		$this->load->view('templates/footer');
		*/
	}
	function showInstaller(){
		$this->load->view('templates/header');
		$this->load->view('installer/install_view');
		$this->load->view('templates/footer');
	}
	function apiCheckDatabaseSettings(){
		$postValidation = array(
			array(
				'field'	=> 'database_server'
				,'label'=> 'Database Server'
				,'rules'=> 'trim|xss_clean|prep_for_form|required'
			)
			,array(
				'field'	=> 'database_name'
				,'label'=> 'Database Name'
				,'rules'=> 'trim|xss_clean|prep_for_form|required'
			)
			,array(
				'field'	=> 'database_user'
				,'label'=> 'Database Username'
				,'rules'=> 'trim|xss_clean|prep_for_form|required'
			)
			,array(
				'field'	=> 'database_password'
				,'label'=> 'Database Password'
				,'rules'=> 'trim|xss_clean|prep_for_form|required'
			)
		);
		loadSimpleErrorMessages($this);
		$this->form_validation->set_rules($postValidation);
		$data['data'] = $this->input->post();
		if($this->form_validation->run() == FALSE){
			$data['error_message'] = validation_errors();
			$data['failed_fields'] = $this->form_validation->error_array();
			$this->load->view('utility/notifier',$data);
            return false;
		}
	}
}
?>