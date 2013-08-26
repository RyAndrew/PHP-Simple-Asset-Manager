<?php
class log_model extends CI_Model
{
	function __construct()
 	{
		parent::__construct();
 	}
	function log($data)
	{
		$session_data = $this->session->userdata('logged_in');
		if(FALSE !== $session_data){
			$user = $this->user_model->getUser($session_data['user_id']);
			$data['user_first_name']=$user['user_first_name'];
			$data['user_last_name']=$user['user_last_name'];
			$data['user_email']=$user['user_email'];
			$data['user_id']=$session_data['user_id'];
		}
		$data['user_ip']=$_SERVER['REMOTE_ADDR']; 
		$this->db->insert('logs', $data); 
	}
	function getLogEntry($log_id)
	{
		$this->db->where('log_id',$log_id);
		return $this->db->get('logs') -> row_array();
	}
	function getLog($startingIndex, $perPage, $asset_id=null)
	{
		//$query = $this->db->get('logs');
		 
		$totalRows = $this->db->count_all('logs');
	
		if(!is_null($asset_id)){
			$this->db->where("asset_id", $asset_id);
		}
		$this->db->order_by("log_id", "desc");
		$this->db->limit($perPage,$startingIndex);
		$query = $this->db->get('logs');
		//echo ($pageNumber-1)*$perPage,' ',$perPage;
		
		//var_dump($query);
		return array(
			'totalRows' => $totalRows
			,'data'=> $query -> result_array()
		);
	}
	function getLogForAsset($asset_id)
	{
		$this->db->where('asset_id',$asset_id);
		$this->db->order_by("log_id", "desc");
		return $query = $this->db->get('logs') -> result_array();
	}
	function getLastCreated(){
		if($this->config->item('num_recent_created')!=null && is_numeric($this->config->item('num_recent_created'))){
			$count=$this->config->item('num_recent_created');
		}else{
			$count=10;
		}

		$this->db->where('class','asset');
		$this->db->where('method','apiCreateAsset');
		$this->db->order_by('date','desc');
		return $this->db->get('logs',$count) -> result_array();
	}
	function getLastChanged(){
		if($this->config->item('num_recent_changed')!=null && is_numeric($this->config->item('num_recent_changed'))){
			$count=$this->config->item('num_recent_changed');
		}else{
			$count=10;
		}


		return $this->db->query("
			SELECT maxlogid_and_assetid.asset_id, `logs`.asset_name, date, method FROM (
            	SELECT MAX(log_id) max_log_id, `logs`.asset_id FROM (
            		SELECT DISTINCT asset_id FROM `logs` WHERE class = 'asset' AND method <> 'apiCreateAsset' ORDER BY log_id DESC LIMIT {$count}
                ) AS last_asset_entry, `logs`
                WHERE last_asset_entry.asset_id=`logs`.asset_id 
                AND method <> 'apiCreateAsset' 
                AND class = 'asset' 
                GROUP BY `logs`.asset_id
			) AS maxlogid_and_assetid, `logs`
			WHERE maxlogid_and_assetid.max_log_id=`logs`.log_id
			ORDER BY max_log_id DESC
		") -> result_array();
	}
}

?>