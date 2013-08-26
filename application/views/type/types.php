<?php

$newTypeAttributes=array(
	'id' => 'addTypeForm'
	,'onSubmit' => 'asmAddType(); return false;'
);


echo
	'<div class="row-fluid">'
		,'<div class="text-align-left span5">'
			,heading('<i class="icon-plus-sign"></i> Create Type', 2)
			,'<div class="well">'
				,form_open('',$newTypeAttributes)
				,'<div wrapping="new_type_name" class="control-group">'
					,form_label('Type name <span errorMessageFor="new_type_name"></span>','new_type_name',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'new_type_name','id' => 'new_type_name','originalValue' => '', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
				,'</div>'
				,'<div class="control-group">'	
					,'<div class="controls">'.form_button(array('id' =>'addTypeSubmit','type' => 'submit', 'name' => 'Create', 'value' => 'Create', 'content' => 'Create' , 'class' => 'btn disabled btn-large btn-block')).'</div>'
				,'</div>'
				,form_close()		
			,'</div>'
		,'</div>';

echo 
	'<div class="text-align-left span7">'
	,heading('<i class="icon-edit-sign"></i> Edit Types', 2)
	,'<div class="well">'
	,heading('Click on any attribute to edit.', 4)
	,'<div id="typeTableWrapper">'.$typeTable.'</div>'
	,'</div></div>';

$typeNameChangeForm=array(
	'id' => 'editTypeNameForm'
	,'onsubmit' => 'asmUpdateTypeName(); return false;'
);
$typeAddAttributeForm=array(
	'id' => 'addTypeAttributeForm'
	,'onsubmit' => 'asmAddTypeAttribute(); return false;'
);
$typeAddModuleForm=array(
	'id' => 'addTypeModuleForm'
	,'onsubmit' => 'asmAddTypeModule(); return false;'
);
echo 
	'<div id="editType" class="modal hide fade text-align-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
		,'<div class="modal-header">'
			,'<button type="button" class="close editTypeCancel" data-dismiss="modal" aria-hidden="true">×</button>'
			,'<h3 id="edit_type_name_title">[Name]</h3>'
		,'</div>'
		,'<div class="modal-body">'
			,'<div id="modalmessage" class="notify-box cssCentered"></div>'
			,form_open('',$typeNameChangeForm)
			,'<div class="control-group">'
				,form_hidden(array('edit_type_id' => ''))
			,'</div>'
			,'<div wrapping="edit_type_name" class="control-group">'
				,form_label('Type name <span errorMessageFor="edit_type_name"></span>','edit_type_name',array('class' => 'control-label'))
				,'<div class="controls">'.form_input(array('name' => 'edit_type_name','id' => 'edit_type_name', 'originalValue' => '', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
			,'</div>'
			,'<div class="control-group">'	
				,'<div class="controls">'.form_button(array('id' =>'editTypeNameSubmit','type' => 'submit', 'name' => 'Update Name', 'value' => 'Update Name', 'content' => 'Update Name' , 'class' => 'btn disabled btn-block')).'</div>'
			,'</div>'
			,form_close()
			,'<div class="row-fluid">'

				,'<div class="span6">'
					,form_open('',$typeAddAttributeForm)
					,'<div class="control-group">'
						,form_hidden(array('edit_type_id' => ''))
					,'</div>'
					,'<div wrapping="new_type_attribute" class="control-group">'
						,form_label('Attributes <span errorMessageFor="new_type_attribute"></span>','new_type_attribute',array('class' => 'control-label'))
						,'<div class="controls">'.form_dropdown('new_type_attribute',$all_attributes,'','class="input-block-level" originalValue=""').'</div>'
					,'</div>'
					,'<div class="control-group">'	
						,'<div class="controls">'.form_button(array('id' =>'addTypeAttributeSubmit','type' => 'submit', 'name' => 'Add Attribute', 'value' => 'Add Attribute', 'content' => 'Add Attribute' , 'class' => 'btn disabled btn-block')).'</div>'
					,'</div>'
					,form_close()
					,'<div id="typeAttributeTableWrapper"></div>'
				,'</div>'

				,'<div class="span6">'
					,form_open('',$typeAddModuleForm)
					,'<div class="control-group">'
						,form_hidden(array('edit_type_id' => ''))
					,'</div>'
					,'<div wrapping="new_type_module" class="control-group">'
						,form_label('Modules <span errorMessageFor="new_type_module"></span>','new_type_module',array('class' => 'control-label'))
						,'<div class="controls">'.form_dropdown('new_type_module',$all_modules,'','class="input-block-level" originalValue=""').'</div>'
					,'</div>'
					,'<div class="control-group">'	
						,'<div class="controls">'.form_button(array('id' =>'addTypeModuleSubmit','type' => 'submit', 'name' => 'Add Module', 'value' => 'Add Module', 'content' => 'Add Module' , 'class' => 'btn disabled btn-block')).'</div>'
					,'</div>'
					,form_close()
					,'<div id="typeModuleTableWrapper"></div>'
				,'</div>'

			,'</div>'

		,'</div>'
		,'<div class="modal-footer">'
			//,'<button id="deleteTypeSubmit" class="btn btn-danger" style="float:left;">Delete Type</button>'
			,'<button class="btn editTypeCancel" data-dismiss="modal" aria-hidden="true">Close</button>'
			//,'<button id="editTypeSubmit" class="btn btn-primary" >Save changes</button>'
		,'</div>'
	,'</div>';



$this->load->view('utility/javascriptConfirmDelete',array(
	'formName'			 => 'typeForm'
	,'checkBoxClassName' => 'type-delete'
	,'message' 			 => 'Are you sure you wish to PERMANENTLY delete the selected types? \nRemoving a type will not delete any assets, but will change the type of any asset of the deleted types to [none]'
));
$this->load->view('utility/initFormOnChangeActivate.php',array(
	'formID' 		=> 'addTypeForm'
	,'submitButtonID' => 'addTypeSubmit'
));
$this->load->view('utility/initFormOnChangeActivate.php',array(
	'formID' 		=> 'editTypeNameForm'
	,'submitButtonID' => 'editTypeNameSubmit'
));
$this->load->view('utility/initFormOnChangeActivate.php',array(
	'formID' 		=> 'editTypeForm'
	,'submitButtonID' => 'deleteSelectedTypesSubmit'
	,'activeButtonClass' => 'btn-danger'
));
$this->load->view('utility/initFormOnChangeActivate.php',array(
	'formID' 		=> 'addTypeAttributeForm'
	,'submitButtonID' => 'addTypeAttributeSubmit'
	,'activeButtonClass' => 'btn-success'
));
$this->load->view('utility/initFormOnChangeActivate.php',array(
	'formID' 		=> 'addTypeModuleForm'
	,'submitButtonID' => 'addTypeModuleSubmit'
	,'activeButtonClass' => 'btn-success'
));
$this->load->view('utility/initFormOnChangeActivate.php',array(
	'formID' 		=> 'deleteTypeAttributeForm'
	,'submitButtonID' => 'deleteTypeAttributeSubmit'
	,'activeButtonClass' => 'btn-danger'
));
$this->load->view('utility/initFormOnChangeActivate.php',array(
	'formID' 		=> 'deleteTypeModuleForm'
	,'submitButtonID' => 'deleteTypeModuleSubmit'
	,'activeButtonClass' => 'btn-danger'
));

?>
<script>

	function asmInitTypeTable(){
		$('td[typenamefor]').mouseover(function(){
			$(this).css('cursor','pointer');
			$(this).css('text-decoration','underline');
	  	});
	  	$('td[typenamefor]').mouseout(function(){
			$(this).css('cursor','auto');
			$(this).css('text-decoration','none');
	  	});
	  	$('td[typenamefor]').click(function(){
	  		asmLoadTypeEditor($(this).parent().attr('typeid'));
	  	});
  	}
  	asmInitTypeTable();

	function asmLoadTypeEditor(load_type_id){
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/type/apiLoadTypeInfo"
			,type:'post'
			,data:{type_id:load_type_id}
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){

					$('#edit_type_name_title').html('<i class="icon-edit"></i> '+reply.data.type_info.type_name);
					$('#edit_type_name').attr('originalValue',reply.data.type_info.type_name);
					$('#edit_type_name').val(reply.data.type_info.type_name);

					$('#typeAttributeTableWrapper').html(reply.data.attributesTable);
					$('#typeModuleTableWrapper').html(reply.data.modulesTable);
					
					$('input[name=edit_type_id]').val(reply.data.type_info.type_id);
					asmInitChangeListenerFor_deleteTypeAttributeForm();
					asmInitChangeListenerFor_deleteTypeModuleForm();
					closeMessage('#modalmessage');

					$('#editType').modal('show');
				}
				else{
					showErrorMessage(reply.message);
				}
			}
			,error:function(obj,error){
				showErrorMessage("Error showing type:\n"+error);
			}
		});
	}

	function asmAddType(){
		if(!asmCheckChangesWith_addTypeForm()){
			return false;
		}
		
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/type/apiTypeCreate"
			,type:'post'
			,data:$('#addTypeForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					//showHappyMessage(reply.message);
					$('input[name=new_type_name]').val('');
					$('#typeTableWrapper').html(reply.data.type_table);
					asmLoadTypeEditor(reply.data.type_id);
					asmCheckChangesWith_addTypeForm();
					asmInitTypeTable();
					asmInitChangeListenerFor_editTypeForm();
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error adding type:\n"+error);
			}
		});
	}

	function asmUpdateTypeName(){
		if(!asmCheckChangesWith_editTypeNameForm()){
			return false;
		}
		
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/type/apiUpdateName"
			,type:'post'
			,data:$('#editTypeNameForm').serializeArray()
			,dataType:"json"
			//,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message,'#modalmessage');

					$('#edit_type_name_title').html('<i class="icon-edit"></i> '+reply.data.type_name);
					$('#edit_type_name').attr('originalValue',reply.data.type_name);
					$('td[typenamefor='+reply.data.type_id+']').html(reply.data.type_name);

					asmCheckChangesWith_editTypeNameForm()
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error updating name:\n"+error,'#modalmessage');
			}
		});
	}

	function asmAddTypeAttribute(){
		if(!asmCheckChangesWith_addTypeAttributeForm()){
			return false;
		}

		$('#addTypeAttributeSubmit').html('<i class="icon-refresh icon-spin"></i> (Don\'t Move!)');

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/type/apiAddTypeAttribute"
			,type:'post'
			,data:$('#addTypeAttributeForm').serializeArray()
			,dataType:"json"
			//,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message,'#modalmessage');
					$("[name=new_type_attribute]").val('');
					asmCheckChangesWith_addTypeAttributeForm();
					$('#typeAttributeTableWrapper').html(reply.data.attributesTable);
					$('input[name=edit_type_id]').val(reply.data.type_id);
					asmInitChangeListenerFor_deleteTypeAttributeForm();
					
				}
				setFormErrorColors(reply.failed_fields);
				$('.modal-body').animate({scrollTop:0}, 'slow');
				$('#addTypeAttributeSubmit').html('Add Attribute');
			}
			,error:function(obj,error){
				showErrorMessage("Error adding attribute associations:\n"+error,'#modalmessage');
				$('.modal-body').animate({scrollTop:0}, 'slow');
			}
		});
	}

	function asmDeleteTypeAttributes(){
		if(!asmCheckChangesWith_deleteTypeAttributeForm()){
			return false;
		}

		$('#deleteTypeAttributeSubmit').html('<i class="icon-refresh icon-spin"></i> (Don\'t Move!)');

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/type/apiDeleteTypeAttribute"
			,type:'post'
			,data:$('#deleteTypeAttributeForm').serializeArray()
			,dataType:"json"
			//,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message,'#modalmessage');
					$('#typeAttributeTableWrapper').html(reply.data.attributesTable);
					$('input[name=edit_type_id]').val(reply.data.type_id);
					asmInitChangeListenerFor_deleteTypeAttributeForm();
				}
				setFormErrorColors(reply.failed_fields);
				$('.modal-body').animate({scrollTop:0}, 'slow');
				$('#deleteTypeAttributeSubmit').html('Remove Selected');
			}
			,error:function(obj,error){
				showErrorMessage("Error deleting attribute associations:\n"+error,'#modalmessage');
				$('.modal-body').animate({scrollTop:0}, 'slow');
			}
		});
	}

	function asmAddTypeModule(){
		if(!asmCheckChangesWith_addTypeModuleForm()){
			return false;
		}
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/type/apiAddTypeModule"
			,type:'post'
			,data:$('#addTypeModuleForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message,'#modalmessage');
					$("[name=new_type_module]").val('');
					asmCheckChangesWith_addTypeModuleForm();
					$('#typeModuleTableWrapper').html(reply.data.modulesTable);
					$('input[name=edit_type_id]').val(reply.data.type_id);
					asmInitChangeListenerFor_deleteTypeModuleForm();
				}
				setFormErrorColors(reply.failed_fields);
				$('.modal-body').animate({scrollTop:0}, 'slow');
			}
			,error:function(obj,error){
				showErrorMessage("Error adding module associations:\n"+error,'#modalmessage');
				$('.modal-body').animate({scrollTop:0}, 'slow');
			}
		});
	}

	function asmDeleteTypeModules(){
		if(!asmCheckChangesWith_deleteTypeModuleForm()){
			return false;
		}
		
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/type/apiDeleteTypeModule"
			,type:'post'
			,data:$('#deleteTypeModuleForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message,'#modalmessage');
					$('#typeModuleTableWrapper').html(reply.data.modulesTable);
					$('input[name=edit_type_id]').val(reply.data.type_id);
					asmInitChangeListenerFor_deleteTypeModuleForm();
				}
				setFormErrorColors(reply.failed_fields);
				$('.modal-body').animate({scrollTop:0}, 'slow');
				
			}
			,error:function(obj,error){
				showErrorMessage("Error deleting module associations:\n"+error,'#modalmessage');
				$('.modal-body').animate({scrollTop:0}, 'slow');
			}
		});

	}

	function asmDeleteTypes(){
		if(!asmCheckChangesWith_editTypeForm()){
			return false;
		}

		if(!confirm('Are you sure you wish to delete the selected type(s)?')){
			return false;
		}

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/type/apiDeleteTypes"
			,type:'post'
			,data:$('#editTypeForm').serializeArray()
			,dataType:"json"
			//,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message);
					$('#typeTableWrapper').html(reply.data.type_table);
					asmInitTypeTable();
					asmInitChangeListenerFor_editTypeForm();
				}
			}
			,error:function(obj,error){
				showErrorMessage("Error deleting type(s):\n"+error);
			}
		});
	}

</script>