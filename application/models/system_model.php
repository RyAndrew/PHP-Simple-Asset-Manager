<?php
class system_model extends CI_Model{
	function __construct(){
		$this->load->library('php_var_persister',array(
			'fileName' => $this->config->item('assetManagerSettingsFileName')
			,'varName' => $this->config->item('assetManagerSettingsVarName')
		));
		parent::__construct();
	}

	function setWebsiteTitle($title){
		$this->php_var_persister->pushData($this->php_var_persister->read());
		$this->php_var_persister->pushData(array('websiteTitle'=>$title));
		$this->php_var_persister->writeData();
	}

	function setWebsiteSettings($data){
		$this->php_var_persister->pushData($this->php_var_persister->read());
		foreach($data as $name => $value){
			$this->php_var_persister->pushData(array($name=>$value));
		}
		$this->php_var_persister->writeData();
	}
}
?>