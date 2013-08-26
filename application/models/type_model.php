<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class type_model extends CI_Model{
	
	function __construct(){
		// Call the Model constructor
		parent::__construct();
		$this->load->library('form_validation'); 
	}
	
	function create($data){
		$this->db->insert('asset_types',$data);
		return $this->db->insert_id();
	}
	
	function getTypes($showNone=true){
		$types=array();
		$query = $this->db->query("SELECT type_id,type_name FROM asset_types ORDER BY type_name");
		if($showNone){
			$types[null]='[ None ]';
		}
		foreach ($query->result_array() as $row){
			$types[$row['type_id']] = $row['type_name'];
		}
		return $types;
	}
	
	function updateType($type_id,$type_name){
		//$this->db->query("UPDATE asset_types SET type_name='".$type_name."' WHERE type_id={$type_id}");
		$this->db->where('type_id',$type_id);
		$this->db->update('asset_types',array('type_name' => $type_name));
	}
	
	/*
	function deleteType($type_id){
		$this->db->query("DELETE asset_attributes FROM asset_attributes, asset_type_attributes WHERE asset_type_attributes.type_id={$type_id} AND asset_attributes.attribute_id=asset_type_attributes.attribute_id AND (attribute_value='' OR attribute_value IS NULL))");
		$this->db->query("DELETE FROM asset_types WHERE type_id = {$type_id} ");
		$this->db->query("DELETE FROM asset_type_attributes WHERE type_id = {$type_id} ");
		$this->db->query("DELETE FROM asset_type_modules WHERE asset_type_id = {$type_id} ");
		$this->db->query("UPDATE assets SET type_id=NULL WHERE type_id={$type_id}");
	}
	*/
	
	
	function getName($type_id){
		$query = $this->db->query("SELECT type_name FROM asset_types WHERE type_id={$type_id}");
		return $query->row()->type_name;
	}
	
	function getAttributes($type_id){
		$query = $this->db->query("SELECT asset_type_attribute_id,attribute_name FROM asset_type_attributes JOIN attributes ON attributes.attribute_id=asset_type_attributes.attribute_id  WHERE type_id={$type_id} ORDER BY attribute_name");
		$attributes=array();
		foreach ($query->result_array() as $row){
			$attributes[$row['asset_type_attribute_id']] = $row['attribute_name'];
		}
		return $attributes;
	}

	function getTypeAttributeData($type_id){
		$query = $this->db->query("SELECT asset_type_attribute_id,attributes.attribute_id,attribute_name FROM asset_type_attributes JOIN attributes ON attributes.attribute_id=asset_type_attributes.attribute_id  WHERE type_id={$type_id}");
		$attributes=array();
		foreach ($query->result_array() as $row){
			$attributes[$row['asset_type_attribute_id']] = $row;
		}
		return $attributes;
	}

	function getModulesOfType($type_id){
		return $this->db->select('asset_type_module_id,asset_modules.asset_module_id,asset_module_name,asset_module_location')->from('asset_modules')->join('asset_type_modules','asset_modules.asset_module_id=asset_type_modules.asset_module_id')->where('asset_type_id',$type_id)->order_by('asset_module_name')->get()->result_array();
	}
	
	function addAttribute($data){
		$this->db->insert('asset_type_attributes',$data);
	}

	function addModule($data){
		$this->db->insert('asset_type_modules',$data);
	}
	
	function addAttributeToAllAssetsWithType($attribute_id,$type_id){
		$this->db->select('count(attribute_id) as theCount');
		$this->db->from('asset_type_attributes');
		$this->db->where('attribute_id', $attribute_id);
		$this->db->where('type_id', $type_id);
		$totalTypeAttributeCount = $this->db->get()->row()->theCount;
		
		$this->db->query("
			insert into asset_attributes (asset_id, attribute_id )
			SELECT 
			assets.asset_id, {$attribute_id} as attribute_id 
			FROM 
			assets 
			LEFT JOIN asset_attributes ON asset_attributes.asset_id=assets.asset_id AND attribute_id={$attribute_id}
			WHERE
			type_id={$type_id}
			GROUP BY assets.asset_id
			HAVING 
			COUNT(asset_attribute_id) < {$totalTypeAttributeCount}
			
		");
	}

	function deleteAttributes($attributes){
		if(count($attributes)<1){
			return false;
		}
		foreach($attributes as $asset_type_attribute_id => $dummyVar){
			$this->db->or_where('asset_type_attribute_id',$asset_type_attribute_id);
		}
		$this->db->delete('asset_type_attributes');
		//$this->db->query("DELETE FROM asset_type_attributes WHERE asset_type_attribute_id = {$asset_type_attribute_id} ");
	}

	function removeModules($modules){
		if(count($modules)==0){
			return;
		}
		foreach($modules as $module){
			$this->db->or_where('asset_type_module_id',$module);
		}
		$this->db->delete('asset_type_modules'); 
	}
	
	function getAttributeID($asset_type_attribute_id){
		$query=$this->db->select('attribute_id')->from('asset_type_attributes')->where('asset_type_attribute_id',$asset_type_attribute_id)->get();
		if($query->num_rows()!=1){
			return false;
		}
		return $query->row()->attribute_id;
	}

	function deleteTypes($types){
		if(count($types)==0){
			return;
		}
		
		$this->db->query("DELETE asset_attributes FROM assets, asset_attributes, asset_type_attributes WHERE assets.type_id IN (".implode(",",$types).") AND asset_attributes.asset_id = assets.asset_id AND (attribute_value='' OR attribute_value IS NULL) AND asset_type_attributes.type_id IN (".implode(",",$types).") AND asset_type_attributes.attribute_id=asset_attributes.attribute_id");
		
		foreach($types as $type){
			$this->db->or_where('asset_type_id',$type);
		}
		$this->db->delete('asset_type_modules'); 

		foreach($types as $type){
			$this->db->or_where('type_id',$type);
		}
		$this->db->delete('asset_types'); 

		foreach($types as $type){
			$this->db->or_where('type_id',$type);
		}
		$this->db->delete('asset_type_attributes');
		
	}

}

?>