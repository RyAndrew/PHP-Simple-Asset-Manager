<?php
class attribute_model extends CI_Model{
	
	function __construct(){
		// Call the Model constructor
		parent::__construct();
		$this->load->library('form_validation'); 
		$this->load->database();
	}
	function getAttributes($showNone=false)
	{
		$query = $this->db->query("SELECT attribute_id,attribute_name FROM attributes ORDER BY attribute_name");
		$attributes=array();
		if($showNone)
			$attributes['']='[ None ]';
		foreach($query->result_array() as $row)
		{
			$attributes[$row['attribute_id']]=$row['attribute_name'];
		}
		return $attributes;
	}
	function getAssetAttribute($asset_attribute_id)
	{
		$query = $this->db->query("SELECT attribute_name, attribute_value FROM attributes, asset_attributes WHERE asset_attributes.asset_attribute_id={$asset_attribute_id} and asset_attributes.attribute_id=attributes.attribute_id");
		return $query->row();
	}
	function getNameFromAssetAttributeId($asset_attribute_id)
	{
		$query = $this->db->query("SELECT attribute_name FROM attributes, asset_attributes WHERE asset_attributes.asset_attribute_id={$asset_attribute_id} and asset_attributes.attribute_id=attributes.attribute_id");
		return $query->row()->attribute_name;
	}
	function getName($attribute_id)
	{
		$query = $this->db->query("SELECT attribute_name FROM attributes WHERE attribute_id={$attribute_id}");
		//var_dump($query);
		
		return $query->row()->attribute_name;
	}
	function create($data)
	{
		$this->db->insert('attributes',$data);
		return $this->db->insert_id();
	}
	function deleteAttribute($attribute_id)
	{
		$this->db->query("DELETE FROM attributes WHERE attribute_id = {$attribute_id} ");
		$this->db->query("DELETE FROM asset_attributes WHERE attribute_id = {$attribute_id} ");
		$this->db->query("DELETE FROM asset_type_attributes WHERE attribute_id = {$attribute_id} ");
	}
	function editAttribute($attribute_id,$attribute_name)
	{
		$this->db->where('attribute_id',$attribute_id);
		$this->db->update('attributes',array('attribute_name' => $attribute_name));
	}

	function countSerials($str,$asset_id){
		$this->db->select('*');
		$this->db->from('assets');
		$this->db->join('asset_attributes','asset_attributes.asset_id = assets.asset_id');
		$this->db->join('attributes','attributes.attribute_id = asset_attributes.attribute_id');
		$this->db->like('attribute_name','Serial');
		$this->db->where('attribute_value',$str);
		$this->db->where('assets.asset_id !=',$asset_id);
		return $this->db->get()->result_array();
	}

	function countMACs($str,$asset_id){
		$this->db->select('*');
		$this->db->from('assets');
		$this->db->join('asset_attributes','asset_attributes.asset_id = assets.asset_id');
		$this->db->join('attributes','attributes.attribute_id = asset_attributes.attribute_id');
		$this->db->like('attribute_name','MAC Addr');
		$this->db->where('attribute_value',$str);
		$this->db->where('assets.asset_id !=',$asset_id);
		return $this->db->get()->result_array();
	}
}

?>
