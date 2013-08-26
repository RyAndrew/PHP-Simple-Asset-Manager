<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
if($assets!=-1){
	$form_attributes = array(
		'id' 		=> 'asssetForm'
		,'onsubmit' => 'confirmSubmit(); return false;'
	);
	$excel_form_attributes = array(
		'id' 		=> 'excelForm'
		,'class'	=> 'form-inline'
		,'style' 	=> 'padding:0px;margin:0px;'
	);
	$assetsToJSON=array();
	foreach($assets as $value){
		$assetsToJSON[]=array_values($value);
	}
	echo 
		'<div class="row-fluid">'
		,'<div class="text-align-left span12">'
		,heading(form_open('reports/assetListExcel',$excel_form_attributes).'<i class="icon-list"></i> List O\' Assets &nbsp;'.form_hidden('excelDataJSON',json_encode($assetsToJSON)).form_button(array('type' => 'submit', 'name' => 'Excel', 'value' => 'Excel', 'content' => '<i class="icon-download"></i> Download Excel' , 'class' => 'btn btn-success', 'style' => 'margin-top:-7px;')).form_close(), 2)
		,'<div class="well">'
		,form_open('asset/delete',$form_attributes)
		,'<table class="table table-striped table-bordered table-condensed">'
		,'<tr class="tableTitle">'
			,'<th>ID</th>'
			,'<th>Asset Name</th>'
			,'<th>Type</th>'
			,'<th>Location</th>'
			,'<th>Status</th>'
			//,'<th>Delete?</th>' //we need to determine how to handle this. 
		,'</tr>';
	foreach($assets as $asset){
		echo
			'<tr>'
				,'<td>'.$asset['asset_id'].'</td>'
				,'<td>'.anchor('asset/edit/'.$asset['asset_id'],$asset['asset_name']).'</td>'
				,'<td>'.$asset['type_name'].'</td>'
				,'<td>'.$asset['asset_location'].'</td>'
				,'<td>'.$asset['asset_status'].'</td>'
				//,'<td class="text-align-center span1">'.form_checkbox('asset_delete['.$asset['asset_id'].']','',false,"class='asset-delete'").'</td>'
			,'</tr>';
	}
	echo
		'</table>'
		//,form_button(array('type' => 'submit', 'name' => 'Delete Selected', 'value' => 'Delete Selected', 'content' => 'Delete Selected' , 'class' => 'btn btn-danger btn-large'))
		,form_close()
		,'Page rendered in: '.$this->benchmark->elapsed_time('code_start', 'code_end').' sec'
		,'</div></div></div>';
}
$this->load->view('utility/javascriptConfirmDelete',array(
	'formName'=>'asssetForm'
	,'checkBoxClassName'=>'asset-delete'
	,'message' => 'Are you sure you want to PERMANENTLY delete all selected assets?'
));

?>

