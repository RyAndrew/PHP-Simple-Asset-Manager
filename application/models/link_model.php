<?php
class link_model extends CI_Model{
	function __construct(){
		parent::__construct();
	}			

	function getLink($asset_link_id){
		return $this->db->where('asset_link_id',$asset_link_id)->get('asset_links')->row_array();
	}

	function addLink($data){
		$this->db->insert('asset_links', $data);
		return $this->db->insert_id();
	}

	function deleteLink($asset_link_id){
		$this->db->where('asset_link_id',$asset_link_id)->delete('asset_links');
	}

	private function linkQuery($asset_id){
		return "SELECT asset_id, asset_name, asset_link_id, link_note
				FROM assets
				Join (
					SELECT CASE WHEN asset_id = {$asset_id} THEN asset_linked_to ELSE asset_id END AS linkedAssetId, asset_link_id, link_note
					FROM asset_links 
					WHERE asset_id = {$asset_id} OR asset_linked_to = {$asset_id}
				) 
				AS assetLinks ON assetLinks.linkedAssetId= assets.asset_id
				";
	}

	function getLinksOfAsset($asset_id){
		return $this->db->query($this->linkQuery($asset_id))->result_array();
	}

	//used to see if there is already a link connecting two assets (even though the user can already see it on the asset links tab (idiots...))
	function countLinks($asset_id,$linked_to){
		return $this->db->query($this->linkQuery($asset_id)." WHERE asset_id = {$linked_to}")->num_rows();

	}
}
?>