<?php
class user_model extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	function addUser($data){
		$this->db->insert('users', $data);
		return $this->db->insert_id();
	}

	function deleteUser($user_id){
		$this->db->where('user_id', $user_id)->delete('users');
	}

	function getUsers(){
		return $this->db->get('users')->result_array();
	}
	function getUser($user_id){
		return $this->db->where('user_id',$user_id)->get('users')->row_array();
	}
	function getFname($user_id){
		return $this->db->where('user_id',$user_id)->get('users')->row()->user_first_name;
	}
	function updateUser($data){
		$this->db->where('user_id',$data['user_id']);
		unset($data['user_id']);
		$this->db->update('users', $data); 
	}
	function checkEmailExists($user_email){
		return (count($this->db->where('user_email',$user_email)->get('users')->result_array())!=0);
	}
	function cleanSessions($user_id){
		$currentSessionId = $this->session->userdata('session_id');
		$query = $this->db->where('session_id !=',$currentSessionId)->get('ci_sessions');
		$extraSessions=false;
		foreach ($query->result_array() as $row){
    		$data = unserialize($row['user_data']);
    		if($data['logged_in']['user_id']==$user_id){
    			$this->db->or_where('session_id',$row['session_id']);
    			$extraSessions=true;
    		}
		}
		if($extraSessions){
			$this->db->delete('ci_sessions'); 
		}
	}
	function getThemeOfUser($user_id){
		return $this->db->select('user_theme')->from('users')->where('user_id',$user_id)->get()->row()->user_theme;
	}
	function cleanSessionsIncludingOwn($user_id){
		$query = $this->db->get('ci_sessions');
		$extraSessions=false;
		foreach ($query->result_array() as $row){
    		$data = unserialize($row['user_data']);
    		if($data['logged_in']['user_id']==$user_id){
    			$this->db->or_where('session_id',$row['session_id']);
    			$extraSessions=true;
    		}
		}
		if($extraSessions){
			$this->db->delete('ci_sessions'); 
		}
	}
	function getUserByEmail($user_email){
		$this->db->where('user_email',$user_email);
		return $this->db->get('users') -> row_array();
	}
	function setTheme($user_id,$theme_id){
		$this->db->where('user_id',$user_id);
		$this->db->update('users', array(
			'theme_id' => $theme_id
		));
	}
	function countAdmins(){
		$this->db->where('is_admin',1);
		$this->db->from('users');
		return $this->db->count_all_results();
	}
	function countDisabled(){
		$this->db->where('is_disabled',1);
		$this->db->from('users');
		return $this->db->count_all_results();
	}
	function countDisabledAdmins(){
		$this->db->where('is_disabled',1);
		$this->db->where('is_admin',1);
		$this->db->from('users');
		return $this->db->count_all_results();
	}
	function countNonDisabledAdmins(){
		$this->db->where('is_disabled',0);
		$this->db->where('is_admin',1);
		$this->db->from('users');
		return $this->db->count_all_results();
	}
	function countUsers(){
		$this->db->from('users');
		return $this->db->count_all_results();
	}
	function curIsAdmin(){
		$sess=$this->session->userdata('logged_in');
		$this->db->where('user_id',$sess['user_id']);
		$this->db->where('is_admin',1);
		$this->db->from('users');
		return ($this->db->count_all_results()==1);
	}
	function isAdmin($user_id){
		$this->db->where('user_id',$user_id);
		$this->db->where('is_admin',1);
		$this->db->from('users');
		return ($this->db->count_all_results()==1);
	}
}
?>