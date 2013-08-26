<?php

class note_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}			

	function getNoteTypes($showNone=false){
		$query = $this->db->select('note_type_id,note_type')->from('note_types')->get();
		$types=array();
		if($showNone)
			$types['']='[ None ]';
		foreach ($query->result_array() as $row){
			$types[$row['note_type_id']] = $row['note_type'];
		}
		return $types;
	}

	function addNote($data){
		$this->db->insert('asset_notes', $data);
	}

	function getNotesOfAsset($asset_id){
		return $this->db->select('note_date,note_types.note_type,note')->from('asset_notes')->join('note_types','note_type_id=asset_notes.note_type')->where('asset_id',$asset_id)->order_by('note_id','desc')->get()->result_array();
	}
}
?>