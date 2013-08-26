<?php
class module_model extends CI_Model{
	
	function getModules($showNone=false){
		$query = $this->db->select('asset_module_id,asset_module_name')->from('asset_modules')->order_by('asset_module_name')->get();
		$modules=array();
		if($showNone)
			$modules['']='[ None ]';
		foreach ($query->result_array() as $row){
			$modules[$row['asset_module_id']] = $row['asset_module_name'];
		}
		return $modules;
	}

	function getModuleName($asset_module_id){
		return $this->db->select('asset_module_name')->from('asset_modules')->where('asset_module_id',$asset_module_id)->get()->row()->asset_module_name;
	}
	function getModuleNameUsingUniqueID($asset_type_module_id){
		return $this->db->select('asset_module_name')->from('asset_modules')->join('asset_type_modules','asset_type_modules.asset_module_id=asset_modules.asset_module_id')->where('asset_type_module_id',$asset_type_module_id)->get()->row()->asset_module_name;
	}
}
?>