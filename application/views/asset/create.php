<?php
	$createFormAttributes=array(
		'id' => 'createAssetForm'
		,'onsubmit' => 'createAsset(); return false;'
	);
	echo
		'<div class="text-align-left span8">'
		,heading('<i class="icon-plus-sign"></i> Create Asset', 2)
		,'<div class="well">'
		,form_open('',$createFormAttributes)
		,'<div wrapping="new_asset_type" class="control-group">'
			,form_label('Type <span errorMessageFor="new_asset_type"></span>','new_asset_type',array('class' => 'control-label'))
			,'<div class="controls">'.form_dropdown('new_asset_type', $all_types,set_value('new_asset_type'),'class="input-block-level" id="new_asset_type" originalValue="'.set_value('new_asset_type').'"').'</div>'
		,'</div>'
		,'<div wrapping="new_asset_name" class="control-group">'
			,form_label('Name <span errorMessageFor="new_asset_name"></span>','new_asset_name',array('class' => 'control-label'))
			,'<div class="controls">'.form_input(array('name' => 'new_asset_name', 'id' => 'new_asset_name', 'value' => set_value('new_asset_name'), 'originalValue' => set_value('new_asset_name'), 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
		,'</div>'
		,'<div wrapping="new_asset_location" class="control-group">'
			,form_label('Location <span errorMessageFor="new_asset_location"></span>','new_asset_location',array('class' => 'control-label'))
			,'<div class="controls">'.form_input(array('name' => 'new_asset_location', 'id' => 'new_asset_location', 'value' => set_value('new_asset_location'), 'originalValue' => set_value('new_asset_location'), 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
		,'</div>'
		,'<div wrapping="new_asset_status" class="control-group">'
			,form_label('Status <span errorMessageFor="new_asset_status"></span>','new_asset_status',array('class' => 'control-label'))
			,'<div class="controls">'.form_dropdown('new_asset_status',array('Available' => 'Available','Active' => 'Active','Inactive' => 'Inactive'),set_value('new_asset_status','Available'),'class="input-block-level" id="new_asset_status" originalValue="'.set_value('new_asset_status','Available').'"').'</div>'
		,'</div>'
		,'<div class="control-group">'
			,'<div class="controls">'.form_button(array('id' => 'createAssetSubmit','type' => 'submit', 'name' => 'Create Asset', 'value' => 'Create Asset', 'content' => 'Create Asset' , 'class' => 'btn disabled btn-large btn-block')).'</div>'
		,'</div>'
		,form_close()
		,'</div></div></div>';
?>
<script>
	function checkChangesWithCreateAssetForm(){
		var changed = false;
   		$("#createAssetForm :input").each(function(){
        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
	        	if($(this).val() != $(this).attr('originalValue')){
	        		changed=true;
	        	}
        	}
   		});
   		if(changed){
   			$("#createAssetSubmit").addClass('btn-primary');
			$("#createAssetSubmit").removeClass('disabled');
			return true;
   		}else{
   			$("#createAssetSubmit").removeClass('btn-primary');
			$("#createAssetSubmit").addClass('disabled');
			return false;
   		}
	}

	$("#createAssetForm :input").on({
		'keydown input':function(){
	   		checkChangesWithCreateAssetForm();	
   		}
   		,change:function(){
   			checkChangesWithCreateAssetForm();
   		}
	});

	function createAsset(){
		if(!checkChangesWithCreateAssetForm()){
			return false;
		}
		

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiCreateAsset"
			,type:'post'
			,data:$('#createAssetForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){ //html request succeed?
				if(reply.success){
					window.location.href = "<?php echo $this->config->item('base_url'); ?>index.php/asset/edit/"+reply.data.asset_id;
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error Creating Asset:\n"+error);
			}
		});
	}
</script>