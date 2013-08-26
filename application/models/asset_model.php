<?php
class asset_model extends CI_Model{
	
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->database();
		$this->assetSetFields = array(
			'asset_name',
			'type_id',
  			'asset_location',
  			'asset_status'
		);
	}			
	function create($data){						
		$this->db->insert('assets', $data);
		$asset_id=$this->db->insert_id();

		$query = $this->db->query("SELECT assets.asset_id,asset_type_attributes.attribute_id  FROM assets JOIN asset_type_attributes WHERE assets.type_id=asset_type_attributes.type_id AND assets.asset_id={$asset_id}");
		foreach ($query->result_array() as $row){
			$data = array(
				'asset_id' 		=> $row['asset_id']
				,'attribute_id' => $row['attribute_id']
			);
			$this->db->insert('asset_attributes', $data);
		}
		return $asset_id;
	}
	function delete($asset_id){
		$this->db->where('asset_id', $asset_id)->delete('assets'); 
		$this->db->where('asset_id', $asset_id)->delete('asset_attributes');
		$this->db->where('asset_id', $asset_id)->delete('asset_notes');
		$this->db->where('asset_id', $asset_id)->or_where('asset_linked_to', $asset_id)->delete('asset_links'); 
	}
	function checkAssetExist($asset_id){
		$query = $this->db->query("SELECT * FROM assets WHERE asset_id={$asset_id}");
		if($query->num_rows()!=1){
			if($query->num_rows()>1){
				show_error('There is more than 1 asset in the database with that ID. Errorz. I haz BloWn up. Bo0m.');
			}
			else{
				//show_error('That asset (ID#: '.$asset_id.') doesn\'t exist!');
				return false;
			}
		}
		return true;
	}

	function getName($asset_id){
		$query = $this->db->select('asset_name')->from('assets')->where('asset_id',$asset_id)->get();
		
		if($query->num_rows()!=1){
			return false;
		}
		return $query->row()->asset_name;
	
	}

	function getLocation($asset_id){
		$query = $this->db->select('asset_location')->from('assets')->where('asset_id',$asset_id)->get();
		
		if($query->num_rows()!=1){
			return false;
		}
		return $query->row()->asset_location;
	}
	
	function getTagsPrinted($asset_id){
		$query = $this->db->select('tags_printed')->from('assets')->where('asset_id',$asset_id)->get();
		if($query->num_rows()!=1){
			return false;
		}
		return $query->row()->tags_printed;
	}
	
	function getStatus($asset_id){
		$query = $this->db->select('asset_status')->from('assets')->where('asset_id',$asset_id)->get();
		if($query->num_rows()!=1){
			return false;
		}
		return $query->row()->asset_status;
	}
	
	function getType($asset_id){
		$query = $this->db->select('type_id')->from('assets')->where('asset_id',$asset_id)->get();
		if($query->num_rows()!=1){
			return false;
		}
		return $query->row()->type_id;
	}
	
	function getAsset($asset_id){
		$query=$this->db->select('asset_id, type_id, asset_name, asset_location, asset_status, tags_printed')->from('assets')->where('asset_id',$asset_id)->get();
		return $query->row();
	}
	
	function getCurrentAttributes($asset_id){
		$currentAttributes=array();
		$query = $this->db->query("SELECT asset_attributes.asset_attribute_id, asset_attributes.attribute_id,attribute_name,attribute_value FROM asset_attributes JOIN attributes ON asset_attributes.attribute_id=attributes.attribute_id WHERE asset_id={$asset_id} ORDER BY attribute_name");
		if($this->checkAssetExist($asset_id)==FALSE){
			return -1;
		}
		foreach ($query->result_array() as $row){
			$currentAttributes[$row['asset_attribute_id']]=array(
				'asset_attribute_id' 	=> $row['asset_attribute_id']
				,'attribute_id' 		=> $row['attribute_id']
				,'attribute_name' 		=> $row['attribute_name']
				,'attribute_value' 		=> $row['attribute_value']
			);
		}
		return $currentAttributes;
	}

	function getAssetsByType($searchParams){
		$this->db->select('asset_id, type_name, asset_name, asset_location, asset_status');
		$this->db->from('assets');
		$this->db->join('asset_types','assets.type_id=asset_types.type_id','left');
		$lastIndex = count($searchParams['type_id'])-1;
		$this->db->where('assets.type_id',$searchParams['type_id'][$lastIndex]);
		for($i=$lastIndex-1;$i>=0;$i--){
			$this->db->or_where('assets.type_id',$searchParams['type_id'][$i]);
		}
		$query = $this->db->get();
		if($query->num_rows()<1){
			return -1;
		}
		foreach($query->result_array() as $rowColName=> $rowColValue){
			$assets[$rowColName]=$rowColValue;
		}
		return $assets;
	}
	
	
	//This function neeeeeeedddssss to be updated. It works, but it's ugly.
	function getAssetsByAttribute($searchParams){
		$where=array();
		$joins=array();
		$left=count($searchParams['attribute_id']);
		for($left;$left>0;$left--){
			$joins[]='JOIN asset_attributes a'.$left.' ON a'.$left.'.asset_id=assets.asset_id';
			$id=$searchParams['attribute_id'][$left-1];
			$value=addslashes($searchParams['attribute_value'][$left-1]);
			$value=str_replace('*','%',$value);
			$like='LIKE';
			if(strstr($value,'%')==false){
				$like='=';
			}
			$where[]='(a'.$left.'.attribute_id='.$id.' AND a'.$left.'.attribute_value '.$like.' \''.addslashes($value).'\')';
			if($left>1){
				$where[]='AND';
			}
		}

		$searchQuery="SELECT assets.asset_id, type_name, assets.asset_name, assets.asset_location, asset_status FROM assets LEFT JOIN asset_types ON assets.type_id=asset_types.type_id ".implode(" ",$joins)." WHERE ".implode(" ",$where)." ORDER BY assets.asset_name";

		$query = $this->db->query($searchQuery);
		
		if($query->num_rows()<1){
			return -1;
		}

		foreach($query->result_array() as $rowColName=> $rowColValue){
			$assets[$rowColName]=$rowColValue;
		}
		return $assets;
	}
	
	
	function getAssetsByNameTypeAttribute($searchParams){

		$this->db->select('assets.asset_id, type_name, asset_name, asset_location, asset_status');
		$this->db->from('assets');
		$this->db->join('asset_types','assets.type_id=asset_types.type_id','left');
		
		$usingType=false;
		for($i=0;$i<count($searchParams['type_id']);$i++){
			if($searchParams['type_id'][$i]!=null){
				$usingType=true;
			}
		}
		
		$usingAttribute=false;
		for($i=0;$i<count($searchParams['attribute_id']);$i++){
			if($searchParams['attribute_id'][$i]!=null){
				$usingAttribute=true;
			}
		}
		
		if($searchParams['asset_name']!=""){
			$this->db->like('asset_name',$searchParams['asset_name']);
		}

		if($usingType){
			foreach($searchParams['type_id'] as $param){
				if($param!=null){
					$filteredSearchParams['type_id'][]=$param;
				}
			}
		
			$lastIndex = count($filteredSearchParams['type_id'])-1;
			$this->db->where('assets.type_id',$filteredSearchParams['type_id'][$lastIndex]);
			for($i=$lastIndex-1;$i>=0;$i--){
				$this->db->or_where('assets.type_id',$filteredSearchParams['type_id'][$i]);
			}
		}

		if($usingAttribute){
			for($i=0;$i<count($searchParams['attribute_id']);$i++){
				if($searchParams['attribute_id'][$i]!=null){
					$filteredSearchParams['attribute_id'][$i]=$searchParams['attribute_id'][$i];
					$filteredSearchParams['attribute_value'][$i]=$searchParams['attribute_value'][$i];
				}
			}
		
			$left=count($filteredSearchParams['attribute_id']);
			for($left;$left>0;$left--){
				$this->db->join('asset_attributes a'.$left,'a'.$left.'.asset_id=assets.asset_id');
				$id=$filteredSearchParams['attribute_id'][$left-1];
				$value=addslashes($filteredSearchParams['attribute_value'][$left-1]);
				$value=str_replace('*','%',$value);
				$like='LIKE';
				if(strstr($value,'%')==false){
					$like='=';
				}
				$whereString='(a'.$left.'.attribute_id='.$id.' AND a'.$left.'.attribute_value '.$like.' \''.addslashes($value).'\')';	
				if($left<count($filteredSearchParams['attribute_id'])-1){
					$this->db->and_where($whereString);
				}else{
					$this->db->where($whereString);
				}
			}		
		}
		
		$this->db->order_by('asset_name');
		$query = $this->db->get();
	
		if($query->num_rows()<1){
			return -1;
		}

		foreach($query->result_array() as $rowColName=> $rowColValue){
			$assets[$rowColName]=$rowColValue;
		}
		return $assets;
	}
	
	function getAssets(){
		$assets=array();
		$query = $this->db->select('asset_id, type_name, asset_name, asset_location, asset_status')->from('assets')->join('asset_types', 'assets.type_id=asset_types.type_id', 'left')->order_by('asset_name')->get();
		 
		if($query->num_rows()<1){
			return -1;
		}

		foreach($query->result_array() as $rowColName=> $rowColValue){
			$assets[$rowColName]=$rowColValue;
		}
		return $assets;
	}

	function getAssetsUsingSearchTerm($term){
		if(is_numeric($term)){
			$term=(int)$term; 
		}
		return $this->db->select('asset_id, asset_name, asset_location ,type_name ')->from('assets')->join('asset_types','assets.type_id=asset_types.type_id')->like('asset_name',$term)->or_like('asset_id',$term)->get()->result_array();
	}

	function getAssetsByName($searchName=null){		
		$query = $this->db->select('asset_id, type_name, asset_name, asset_location, asset_status')->from('assets')->join('asset_types','assets.type_id=asset_types.type_id','left')->like('asset_name',$searchName)->order_by('asset_name')->get();
		
		if($query->num_rows()<1){
			return -1;
		}

		foreach($query->result_array() as $rowColName=> $rowColValue){
			$assets[$rowColName]=$rowColValue;
		}
		
		return $assets;
	}
	
	function incrementTagsPrintedCount($asset_id){
			$this->db->where('asset_id', $asset_id);
			$this->db->set('tags_printed', 'tags_printed+1', FALSE);
			$this->db->update('assets'); 
	}
	function editAssetAttribute($asset_attribute_id, $newValue){
			$this->db->where('asset_attribute_id', $asset_attribute_id);
			$this->db->update('asset_attributes', array('attribute_value' => $newValue)); 
	}
	function deleteAssetAttribute($asset_attribute_id){
			$this->db->query("DELETE FROM asset_attributes WHERE asset_attribute_id = {$asset_attribute_id} ");
	}
	function edit($asset_id,$assetData){	
		$this->db->where('asset_id', $asset_id);
		$this->db->update('assets', $assetData);	
	}
	function addAttribute($data){
		$this->db->insert('asset_attributes', $data);
	}
}
?>