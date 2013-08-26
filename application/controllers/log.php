<?php
/**
 * The Log Class. Contains functions that allows one to add and view entries in the asset manager log.
 */
class log extends CI_Controller {

    /**
     * Loads up all the external libraries used for this class.
     * Will also redirect to login if the user isn't logged in.
     * It will also redirect to a unauthorized error
     * if the current user is not an admin.
     */
    function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('pagination');
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
     * Equivalent to viewLog().
     */
    function index(){
		$this->viewLog();
	}

    /**
     * Shows the details of a log entry from a given log id.
     *
     * @param $log_id The log entry to view
     */
    function viewEntry($log_id){
		$data['logData']=$this->log_model->getLogEntry($log_id);
		$this->load->view('templates/header',$data);
		$this->load->view('log/details',$data);
		$this->load->view('templates/footer');
	}

    /**
     * Loads up the log with a fancy pagination system.
     *
     * @param int $startingIndex What index to start at in the log.
     * @param int $perPage How many log entries to show on the page.
     */
    function viewLog($startingIndex=0,$perPage=25){
		if(FALSE !== $this->input->post('perPage')){
			$perPage=$this->input->post('perPage');
			$this->session->set_userdata('perPage', $perPage);
		}else{	
			$perPageSession = $this->session->userdata('perPage');
			if(FALSE !== $perPageSession){
				$perPage=$perPageSession;
			}
		}
		
		$log = $this->log_model->getLog($startingIndex,$perPage);
		//var_dump($this->uri);
		$this->pagination->initialize(
			array(
				'base_url' => '/AssetManager/index.php/log/viewLog'
				,'total_rows' => $log['totalRows'] //total pages
				,'per_page' => $perPage
				,'num_links' => 10
				,'full_tag_open' => '<div class="pagination"><ul>'
				,'full_tag_close' => '</ul></div>'
				,'first_tag_open' => '<li>'
				,'first_tag_close' => '</li>'
				,'last_tag_open' => '<li>'
				,'last_tag_close' => '</li>'
				,'next_tag_open' => '<li>'
				,'next_tag_close' => '</li>'
				,'prev_tag_open' => '<li>'
				,'prev_tag_close' => '</li>'
				,'num_tag_open' => '<li>'
				,'num_tag_close' => '</li>'
				,'cur_tag_open' => '<li class="disabled"><a href="#" >'
				,'cur_tag_close' => '</a></li>'
				//,'use_page_numbers' => true
			)
		);
	
		$this->load->model('log_model');
		$this->load->view('templates/header',array('fluid' 	=>	true));
		$this->load->view('log/log_view.php',array(
			'logData'	=>	$log['data']
			,'links' 	=>	$this->pagination->create_links()
			,'perPage'	=> 	$perPage
		));
		$this->load->view('templates/footer');
	}
}
?>