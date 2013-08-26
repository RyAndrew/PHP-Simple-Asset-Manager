<?php
/**
 * The error class. It handles some errors that may arise when using the asset manager.
 */
class error extends CI_Controller {
    /**
     * Equivalent to the parent (CI_Controller) constructor.
     */
    function __construct(){
		parent::__construct();
	}

    /**
     * Shows a 404 error message. Also logs that someone saw a 404.
     */
    function four0four(){
		$this->load->view('templates/header');
		$this->load->view('errors/404_view');
		$this->load->view('templates/footer');
		$this->log_model->log(array(
			'class' 		=> 'error'
			,'method' 		=> '404'
		));
	}

    /**
     * Shows a unauthorized error message. Also logs that someone saw this error.
     */
	function notAuthorized(){
 		$this->load->view('templates/header');
	    $this->load->view('errors/notAuthorized_view');
	    $this->load->view('templates/footer');
	    $this->log_model->log(array(
			'class' 		=> 'error'
			,'method' 		=> 'notAuthorized'
		));
 	}

}
?>