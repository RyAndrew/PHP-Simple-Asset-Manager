<?php 
$formAttributes=array(
	'id' => 'searchByIdForm',
	'onsubmit' => 'searchById(); return false;'
);

echo 
	'<div class="row-fluid"><div class="text-align-left span4">'
	,heading('<i class="icon-barcode"></i> Search by ID', 2)
	,'<div class="well">'
	,form_open('',$formAttributes)
	,'<div wrapping="asset_id" class="control-group">'
		,form_label('Asset ID <span errorMessageFor="asset_id"></span>','asset_id',array('class' => 'control-label'))
		,'<div class="controls">'.form_input(array('name' => 'asset_id', 'class' => 'input-block-level', 'id' => 'asset_id', 'originalValue' =>'')).'</div>'
	,'</div>'
	,'<div class="control-group">'
		,'<div class="controls">'.form_button(array('id' => 'searchByIDSubmit','type' => 'submit', 'name' => 'Lookup', 'value' => 'Lookup', 'content' => 'Lookup' , 'class' => 'btn disabled btn-large btn-block')).'</div>'
	,'</div>'
	,form_close()
	,'</div></div>';
?>
<script type="text/JavaScript">
	$('#asset_id').focus();

	function searchById(){
		if(!checkChangesWithSearchIdForm()){
			return false;	
		}
		
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiSearchById"
			,type:'post'
			,data:$('#searchByIdForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){ //html request succeed?
				if(reply.success){
					window.location.href = "<?php echo $this->config->item('base_url'); ?>index.php/asset/edit/"+reply.data.asset_id;
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error searching:\n"+error);
			}
		});
	}

	function checkChangesWithSearchIdForm(){
		if($("input[name=asset_id]").attr('originalValue') != $("input[name=asset_id]").val()){
			$("#searchByIDSubmit").addClass('btn-primary');
			$("#searchByIDSubmit").removeClass('disabled');
			return true;
		}else{
			$("#searchByIDSubmit").removeClass('btn-primary');
			$("#searchByIDSubmit").addClass('disabled');
			return false;
		}
	};

	$("input[name=asset_id]").on({
		'keydown input':function(){
	   		checkChangesWithSearchIdForm();	
   		}
   		,change:function(){
   			checkChangesWithSearchIdForm();
   		}
	});
</script>
