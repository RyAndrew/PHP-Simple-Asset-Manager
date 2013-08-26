<?php 
Class register_model extends CI_Model
{
	function __construct()
 	{
		parent::__construct();
 	}
	function register($data)
	{
		$this->db->insert('users', $data); 
		return $this->db->insert_id();
	}
}
?>