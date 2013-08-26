<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
$form_attributes = array(
	'id' => 'searchAssetNameForm',
	'onsubmit' => 'searchByName(); return false;'
);
echo 
	form_open('asset/searchByName',$form_attributes),
	'<table><tr class="tableTitle"><th colspan="2">Search by Name</th></tr>',
	'<tr><td class="label">Asset Name</td><td>', form_input('asset_name','','id="asset_name"') ,'</td></tr>',
	'<tr><td class="submitButton" colspan="2">', form_submit('Search','Search') ,'</td></tr></table>', form_close(),
	'<div id="searchResults"></div>',
/*
$this->load->view('utility/checkFieldEmpty',array(
	'formName' => 'searchAssetNameForm'
	,'fieldName'=>'asset_name'
	,'message' => 'The asset name field cannot be empty!'
));
*/
?>
<script>
function searchByName(){
	var results="";
	$('#searchResults').hide();
	$.ajax({
		url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/searchByName"
		,type:'post'
		,data:$('#searchAssetNameForm').serializeArray()
		,dataType:"json"
		,timeout:5000
		,success:function(reply){ //html request succeed?
			if(reply.success){
				//alert(reply.data);
				//console.log(reply.data);
				//showHappyMessage(reply.message);
				results+='<table style="border:0px"><tr class="tableTitle"><th colspan="4">'+reply.message+'<th></tr>';
				results+='<tr class="tableTitle"><th>Name</th><th>Type</th><th>Location</th><th>Status</th></tr>';
				for(var i=0;i<reply.data.length;i++){
					//console.log(reply.data[i]);
					results +='<tr><td><a href="<?php echo $this->config->item('base_url'); ?>index.php/asset/edit/'+reply.data[i].asset_id+'">'+reply.data[i].asset_name +'</a></td><td>'+ reply.data[i].type_name+ '</td><td>'+ reply.data[i].asset_location+ '</td><td>'+ reply.data[i].asset_status+ '</td></tr>';
				}
				results +='</table>'
				closeMessage();
				$('#searchResults').hide().html(results).slideDown('fast');
			}
			else{
				showErrorMessage(reply.message);
			}
		}
		,error:function(obj,error){
			alert("Error Searching:\n"+error);
		}
	});
}
</script>