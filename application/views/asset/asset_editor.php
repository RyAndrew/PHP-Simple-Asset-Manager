<?php
	if($current_attributes == -1){
		show_error('This Asset does not exist! (ID#: '.$asset_id.')');
	}

	echo 
		'<div class="row-fluid">'
		,'<div class="text-align-left span5">'
		,heading('<i class="icon-edit"></i> Editing Asset '.$asset_id, 2)
		,'<div class="well">'
		,heading('Asset Details', 4);
	
	$form_attributes = array(
		'id' 		=> 'assetAttributeForm'
		,'onsubmit' => 'updateAsset(); return false;'
	);
	
	echo form_open('asset/edit/'.$asset_id,$form_attributes);
	echo '<button class="printButton btn" id="print" type="button" onclick="printTag(); return false;"><i class="icon-print"></i> Print Asset Tag'.' - ';
	if($tags_printed < 1){
		echo "<span id='noPrint'>TAG NOT PRINTED!</span>";
	}elseif($tags_printed > 1){
		echo "<span id='printNumber'>{$tags_printed}</span> tags printed!";
	}else{
		echo "<span id='printNumber'>1</span> <span id='tag'>tag</span> printed!";
	}
	echo '</button>';
 
	echo 
		anchor('asset/history/'.$asset_id,'<i class="icon-time"></i> View Asset history',array('class' => 'btn'))
		,form_hidden(array('asset_id' => $asset_id))
		,'<div wrapping="asset_name" class="control-group">'
			,form_label('Name <span errorMessageFor="asset_name"></span>','',array('class' => 'control-label'))
			,'<div class="controls">'.form_input(array('name' => 'asset_name' ,'value' => set_value('asset_name',$asset_name), 'originalValue' => set_value('asset_name',$asset_name), 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
		,'</div>'
		,'<div wrapping="asset_type" class="control-group">'
			,form_label('Type <span errorMessageFor="asset_type"></span>','',array('class' => 'control-label'))
			,'<div class="controls">'.form_dropdown('asset_type', $all_types,$asset_type,'originalValue="'.$asset_type.'" class="input-block-level"').'</div>'
		,'</div>'
		,'<div wrapping="asset_location" class="control-group">'
			,form_label('Asset Location <span errorMessageFor="asset_location"></span>','',array('class' => 'control-label'))
			,'<div class="controls">'.form_input(array('name' => 'asset_location' ,'value' => set_value('asset_location',$asset_location), 'originalValue' => set_value('asset_location',$asset_location), 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
		,'</div>'
		,'<div wrapping="asset_status" class="control-group">'
			,form_label('Status <span errorMessageFor="asset_status"></span>','',array('class' => 'control-label'))
			,'<div class="controls">'.form_dropdown('asset_status',array('Available' => 'Available','Active' => 'Active','Inactive' => 'Inactive'),$asset_status,'originalValue="'.$asset_status.'" class="input-block-level"').'</div>'
		,'</div>';
	echo '<div id="theAttributeTable">'.$attributeTable.'</div>';
	echo 
		//,form_hidden('updating','updating')
		form_button(array('type' => 'submit', 'name' => 'Save Changes', 'value' => 'Save Changes', 'content' => 'Save Changes' , 'class' => 'btn btn-primary btn-large input-block-level'))
		,form_close()
		,'</div>';

	echo
		heading('<i class="icon-plus-sign"></i> Add More Attributes', 2)
		,'<div class="well">';
	
	/*	
	$this->load->view('utility/javascriptConfirmDelete',array(
		'formName'=>'assetAttributeForm'
		,'checkBoxClassName'=>'assetAttribute-delete'
		,'message' => 'Are you sure you want to PERMANENTLY delete the selected attributes from this asset?'
	));
	*/

	$form_attributes = array(
		'id' => 'addAttributeForm'
	);
	
	echo 
		form_open('',$form_attributes)
		,form_hidden('asset_id',$asset_id)
		,'<div wrapping="new_attribute_id" class="control-group">'
				,form_label('Attribute <span errorMessageFor="new_attribute_id"></span>','new_attribute_id',array('class' => 'control-label'))
				,'<div class="controls">'.form_dropdown('new_attribute_id',$all_attributes,set_value('new_attribute_id'),'id="new_attribute_id" class="input-block-level" originalValue=""').'</div>'
		,'</div>'
		,'<div wrapping="new_attribute_value" class="control-group">'
				,form_label('Attribute Value <span errorMessageFor="new_attribute_value"></span>','new_attribute_value',array('class' => 'control-label'))
				,'<div class="controls">'.form_input(array('name' => 'new_attribute_value' ,'id' => 'new_attribute_value' , 'originalValue' => '', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
		,'</div>'
		 ,form_button(array(
		 	'id'		=> 'addAttributeSubmitBtn'
			,'type' 	=> 'submit'
			,'name' 	=> 'Add Attribute'
			,'value' 	=> 'Add Attribute'
			,'content' 	=> 'Add Attribute'
			,'onclick' 	=> 'asmAddAttribute(); return false;'
			,'class' 	=> 'btn disabled btn-large input-block-level'
		))
	 	,form_close()
		,'</div>';

	$this->load->view('utility/initFormOnChangeActivate.php',array(
		'formID' 		=> 'addAttributeForm'
		,'submitButtonID' => 'addAttributeSubmitBtn'
	));

	echo 
		heading('<i class="icon-trash"></i> Delete Asset', 2)
		,'<div class="well">';

	$deleteFormAttributes=array(
		'id' => 'deleteForm',
	);
	echo 
		form_open('',$deleteFormAttributes)
	 	,form_button(array('onclick' => 'confirmDelete(); return false;', 'name' => 'Delete Asset', 'value' => 'Delete Asset', 'content' => 'Delete Asset' , 'class' => 'btn btn-danger btn-large input-block-level'))
	 	,form_close()
	 	,'</div></div>';
	
	
	$deletelinkForm_attributes = array(
		'id' => 'deletelinkForm'
	);
	$addlinkForm_attributes = array(
		'id' => 'addlinkForm'
	);
	echo 
		'<div class="text-align-left span7">'
		,heading('<i class="icon-link"></i> Asset Links', 2)
		,'<ul id="linkTabs" class="nav nav-tabs" style="margin-bottom:-1px; margin-left:10px;"><li class="active"><a data-toggle="tab" href="#links">Linked to</a></li><li><a data-toggle="tab" id="addLinkTab" href="#addLink">Add Link</a></li></ul>'
		,'<div class="well">'
		,'<div class="tab-content">'
	 	,'<div id="links" class="tab-pane active">'
	 	,form_open('',$deletelinkForm_attributes)
	 	,form_hidden('asset_id',$asset_id)
	 	,'<div id="linksForm">';
	if($linksTable==""){
		echo '<center>'.heading('This asset is not linked to anything',3).'</center>';
	}else{
		echo $linksTable;
	}
	echo
		'</div>'
	 	,form_close()
	 	,'</div>'
	 	,'<div id="addLink" class="tab-pane">'
	 	,form_open('',$addlinkForm_attributes)
	 	,form_hidden('asset_id',$asset_id)

	 	,'<div wrapping="new_linked_asset" class="control-group">'
	 		,'<input type="hidden" name="new_linked_asset" id="new-link-asset-id"></input>'
	 		,form_label('Select Asset (Begin typing) <i id="searchIcon"></i><span errorMessageFor="new_linked_asset"></span>','new_linked_asset',array('class' => 'control-label'))
	 		,form_input(array('id' => 'new-link-asset-name' ,'class' => 'input-block-level' ))
	 	,'</div>'
	 	,'<div wrapping="link_note" class="control-group">'
	 		,form_label('Link Note (Why are you linking this asset?) <span errorMessageFor="link_note"></span>','link_note',array('class' => 'control-label'))
	 		,form_input(array('name' => 'link_note' ,'value' => set_value('link_note'), 'class' => 'input-block-level' ,'maxlength' =>30))
	 	,'</div>'
	 	,'<div  class="control-group">'
	 		,form_button(array('id' => 'addLinkSubmit', 'name' => 'Add Link', 'value' => 'Add Link', 'content' => 'Add Link' , 'class' => 'btn btn-large btn-primary btn-block'))
	 	,'</div>'
	 	,form_close()
	 	,'</div>'
	 	,'</div>'
	 	,'</div>';

	$form_attributes = array(
		'id' => 'addNoteForm'
	);

	echo 
		heading('<i class="icon-file-alt"></i> Asset Notes', 2)
		,'<ul id="noteTabs" class="nav nav-tabs" style="margin-bottom:-1px; margin-left:10px;"><li class="active"><a data-toggle="tab" href="#noteHistory">Note History</a></li><li><a data-toggle="tab" id="addNoteTab" href="#notes">Add Note</a></li></ul>'
		,'<div class="well">'
		,form_open('',$form_attributes)
		,'<div class="tab-content">'
	 	,'<div id="notes" class="tab-pane">'
	 	,form_hidden('asset_id',$asset_id)
	 	,form_label('Note Type','')
	 	,form_dropdown('note_type',$noteTypes,set_value('note_type'),'class="input-block-level"')
	 	,form_label('Note','')
	 	,form_textarea(array('name' => 'note','value' => set_value('note'), 'class' => 'input-block-level', 'rows' => '5'));
	 	
	$submitAttributes=array(
		'name' 		=> 'Add Note'
		,'value' 	=> 'Add Note'
		,'onclick' 	=> 'addNote(); return false;'
		,'class' 	=> 'btn btn-primary btn-large btn-block'
	);

	
	echo 
		form_submit($submitAttributes).'</div>'
		,'<div id="noteHistory" class="tab-pane active" style="min-height:273px;">';
	if($notesTable==""){
		echo '<center>'.heading('There are no notes on this asset',3).'</center>';
	}else{
	echo $notesTable;
	}
	echo 	
		'</div>'
	 	,'</div>'
	 	,form_close()
	 	,'</div></div></div>';
	
	$formAttributes=array(
		'id' => 'changingStatusForm'
		,'class' => 'form-inline'
		,'onsubmit' => 'clickedSubmitStatusReason(); return false;'
	);
	echo 
		'<div id="statusReasonPrompt" class="modal hide fade text-align-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
			,'<div class="modal-header">'
				,'<button type="button" class="close changingStatusCancel" data-dismiss="modal" aria-hidden="true">×</button>'
				,'<h3>Why the status change?</h3>'
			,'</div>'
			,'<div class="modal-body">'
				,'<div id="modalmessage" class="notify-box cssCentered"></div>'
				,form_open('',$formAttributes)
				,'<div wrapping="statusReasonField" class="control-group">'
					,form_label('Reason (Min 15 characters) <span errorMessageFor="statusReasonField"></span>','statusReasonField',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'statusReasonField','id' => 'statusReasonField', 'class' => 'input-block-level' )).'</div>'
				,'</div>'
				,form_close()
			,'</div>'
			,'<div class="modal-footer">'
				,'<button class="btn changingStatusCancel" data-dismiss="modal" aria-hidden="true">Cancel</button>'
				,'<button id="submitStatusReason" onclick="clickedSubmitStatusReason()" class="btn btn-primary" >Change Status</button>'
			,'</div>'
		,'</div>';

	$formAttributes=array(
		'id' => 'deleteWarningForm'
		,'class' => 'form-inline'
		,'onsubmit' => 'clickedSubmitDeleteReason(); return false;'
	);
	echo 
		'<div id="deletePrompt" class="modal hide fade text-align-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
			,'<div class="modal-header">'
				,'<button type="button" class="close deleteCancel" data-dismiss="modal" aria-hidden="true">×</button>'
				,'<h3>Why delete this asset?</h3>'
			,'</div>'
			,'<div class="modal-body">'
				,'<div id="modalmessage" class="notify-box cssCentered"></div>'
				,form_open('',$formAttributes)
				,form_hidden('asset_id',$asset_id)
				,'<div wrapping="deleteReasonField" class="control-group">'
					,form_label('Reason (Min 15 characters) <span errorMessageFor="deleteReasonField"></span>','deleteReasonField',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'deleteReasonField','id' => 'deleteReasonField', 'class' => 'input-block-level' )).'</div>'
				,'</div>'
				,form_close()
			,'</div>'
			,'<div class="modal-footer">'
				,'<button class="btn deleteCancel" data-dismiss="modal" aria-hidden="true">Cancel</button>'
				,'<button id="submitDeleteReason" onclick="clickedSubmitDeleteReason()" class="btn btn-primary btn-danger" >Delete Asset</button>'
			,'</div>'
		,'</div>';
?>
<script>
/*
var changed=false;
var before = new Array();
var after = new Array();

$(function(){
	var fields=$('#assetAttributeForm').serializeArray();
	$.each(fields, function(i, field){
		//before[i] = $('input[name="'+field.name+'"]').attr('originalValue');
		before[i] = field.value;
	});
});
*/

$('#statusReasonPrompt').on('hide', function () {
	$("select[name=asset_status]").val($('select[name=asset_status]').attr('originalValue'));
	setFormErrorColors(-1);
});
//function checkStatusBeforeUpdatingAsset(){
$("select[name=asset_status]").change(function () {
	closeMessage();
	if($('select[name=asset_status]').val() != $('select[name=asset_status]').attr('originalValue')){
		$('#statusReasonPrompt').modal('show');	
	}else{
		updateAsset();
	}
});

function clickedSubmitStatusReason(){
	$.ajax({
		url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiConfirmAssetStatusChange"
		,type:'post'
		,data:$('#changingStatusForm').serializeArray()
		,dataType:"json"
		,timeout:3000
		,success:function(reply){
			if(reply.success){
				updateAsset(reply.data);
			}
			setFormErrorColors(reply.failed_fields);
		}
		,error:function(obj,error){
			showErrorMessage("Error updating user:\n"+error,'#modalmessage');
		}
	});
}

function updateAsset(statusChangeNote){
	setFormErrorColors(-1);

	formData = $('#assetAttributeForm').serializeArray();
	
	if (typeof statusChangeNote != 'undefined') {
		formData[formData.length] = {name:'asset_status_note',value:'From: '+$('select[name=asset_status]').attr('originalValue')+' To: '+$('select[name=asset_status]').val()+'. '+statusChangeNote}
	}

	$.ajax({
		url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiUpdateAsset"
		,type:'post'
		,data:formData
		,dataType:"json"
		,timeout:3000
		,success:function(reply){ //html request succeed?
			if(reply.success){
				showHappyMessage(reply.message);
				$('#theAttributeTable').html(reply.data.attributeTable);
				//we don't need the asset status change field anymore. Clean it!
				$('[name=statusReasonField]').val('');

				if (typeof reply.data.notesTable != 'undefined') {
					$('#noteHistory').html(reply.data.notesTable);
					$('a[href="#noteHistory"]').tab("show");
				}
				//resseting originalValue
				for(var i=0;i<formData.length;i++){
					$('[name="'+formData[i].name+'"]').attr('originalValue',formData[i].value);
				}
				
			}else{
				if (typeof reply.data != 'undefined' && typeof reply.data.hadStatusMessage != 'undefined') {
					showErrorMessage('Status not saved! Change the error on this asset and try again.');
				}
			}
			$('#statusReasonPrompt').modal('hide');	
			setFormErrorColors(reply.failed_fields);
		}
		,error:function(obj,error,error2){
			showErrorMessage("Error updating asset:\n"+error);
		}
	});
}

//check for changes in the attributes because we don't want to lose any when the form submits
function asmAddAttribute(){
	if(!asmCheckChangesWith_addAttributeForm()){
		return false;
	}

	changed=false;
	var fields=$('#assetAttributeForm').serializeArray();
	$.each(fields, function(i, field){
		if(field.name!="asset_id" && field.value!=$('[name="'+field.name+'"]').attr('originalValue')){
			changed=true;
		}
	});

	if(changed){
		if(!confirm("Are you sure you want to add a new attribute?\nYou have made changes on this asset page, and will lose them if they aren't saved first.")){
			return false;
		}
	}

	$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiAddAttribute"
			,type:'post'
			,data:$('#addAttributeForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){ //html request succeed?
				if(reply.success){
					showHappyMessage(reply.message);
					$('input[name=new_attribute_value]').val('');
					$('select[name=new_attribute_id]').val('');
					asmCheckChangesWith_addAttributeForm();
					$('#theAttributeTable').html(reply.data.attributeTable);
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error,error2){
				showErrorMessage("Error Adding Attribute:\n"+error);
			}
		});

}
/*
function checkChangesBeforeAddingNote(){
	var fields=$('#assetAttributeForm').serializeArray();
	$.each(fields, function(i, field){
		after[i] = field.value;
	});
	
	$.each(fields, function(i, field){
		//console.log(before[i]+' - '+after[i]);
		if(before[i]!=after[i]){
			changed=true;
		}
	});
	if(changed){
		if(confirm("Are you sure you want to add a new note?\nYou have made changes on this asset page, and will lose them if they aren't saved first.")){
			//$('#addNoteForm').submit();
			addNote()
		}
	}
	else{ 
		//$('#addNoteForm').submit();
		addNote()
	}
	return false;
}*/
function addNote(){
	$.ajax({
		url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiAddNote"
		,type:'post'
		,data:$('#addNoteForm').serializeArray()
		,dataType:"json"
		,timeout:5000
		,success:function(reply){ //html request succeed?
			if(reply.success){
				showHappyMessage(reply.message);
				//window.location.href = "<?php echo $this->config->item('base_url'); ?>index.php/asset/edit/"+reply.data.asset_id;
				$('#noteHistory').html(reply.data.notesTable);
				//$('#noteTabs a:first').tab('show');
				$('a[href="#noteHistory"]').tab("show");
				$('textarea[name=note]').val('');
			}
			else{
				showErrorMessage(reply.message);
			}
		}
		,error:function(obj,error){
			showErrorMessage("Error adding note:\n"+error);
		}
	});
}
function printTag(){
	$.ajax({
		url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/printAssetTag/<?php echo $asset_id; ?>"
		,dataType:"json"
		,timeout:3000
		,success:function(reply){ //html request succeed?
			if(reply.success){
				showHappyMessage(reply.message);
				if($('#printNumber').html()==null){
					$('#noPrint').html("<span id='printNumber'>1</span> <span id='tag'>tag</span> printed!");
				}
				else{
					var num = parseInt($('#printNumber').html());
					if(num==1){
						$('#tag').html('tags');
					}
					num++;
					$('#printNumber').html(num);
				}
			}
			else{
				showErrorMessage(reply.message);
			}
		}
		,error:function(obj,error){
			showErrorMessage("Error printing:\n"+error);
		}
	});
}

function confirmDelete(){
	$('#deletePrompt').modal('show');
}

function clickedSubmitDeleteReason(){
	$.ajax({
		url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiDeleteAsset"
		,type:'post'
		,data:$('#deleteWarningForm').serializeArray()
		,dataType:"json"
		,timeout:3000
		,success:function(reply){
			if(reply.success){
				showHappyMessage(reply.message);
				$('#deletePrompt').modal('hide');
				window.location.href = "<?php echo $this->config->item('base_url'); ?>index.php/asset/allAssets";
			}
			setFormErrorColors(reply.failed_fields);
		}
		,error:function(obj,error){
			showErrorMessage("Error updating user:\n"+error,'#modalmessage');
		}
	});
}

$('#deletePrompt').on('hide', function () {
	setFormErrorColors(-1);
});

$('#addLinkSubmit').click(function(){
	$.ajax({
		url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiAddLink"
		,type:'post'
		,data:$('#addlinkForm').serializeArray()
		,dataType:"json"
		,timeout:3000
		,success:function(reply){
			if(reply.success){
				showHappyMessage(reply.message);
				$('#linksForm').html(reply.data.linksTable);
				$('a[href="#links"]').tab("show");
				$('input[name=new_linked_asset]').val('');
				$('#new-link-asset-name').val('');
				$('input[name=link_note]').val('');
				initLinkDeleteButton();
			}
			else{
				//showErrorMessage(reply.message);
			}
			setFormErrorColors(reply.failed_fields);
		}
		,error:function(obj,error){
			showErrorMessage("Error Deleting Link:\n"+error);
		}
	});
	checkLinkDeleteButton();
});


function checkLinkDeleteButton(){
	if($('.linkDelete').filter(":checked").length>0){
		$('#deleteLinkSubmit').addClass('btn-danger');
		$('#deleteLinkSubmit').removeClass('disabled');
	}else{
		$('#deleteLinkSubmit').removeClass('btn-danger');
		$('#deleteLinkSubmit').addClass('disabled');
	}
}

function initLinkDeleteButton(){
	$('.linkDelete').change(function() {
		checkLinkDeleteButton();
	});
	checkLinkDeleteButton();

	$('#deleteLinkSubmit').click(function(){
		if($('.linkDelete').filter(":checked").length<=0){
			return false;
		}
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiDeleteLink"
			,type:'post'
			,data:$('#deletelinkForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message);
					for(i=0;i<reply.data.linksToRemove.length;i++){
						$('tr[linkId='+reply.data.linksToRemove[i]+']').remove();
					}
					$('#deleteLinkSubmit').removeClass('btn-danger');
					$('#deleteLinkSubmit').addClass('disabled');
				}
				else{
					showErrorMessage(reply.message);
				}
			}
			,error:function(obj,error){
				showErrorMessage("Error Deleting Link:\n"+error);
			}
		});
		checkLinkDeleteButton();
	});
}



$('#addLinkTab').on('shown', function (e) {
	$('#new-link-asset-name').focus();
});

$('#addNoteTab').on('shown', function (e) {
	$('[name=note]').focus();
});

$(function(){
	initLinkDeleteButton();

	$( "#new-link-asset-name" ).autocomplete({
		minLength: 2,
		source: '<?php echo $this->config->item('base_url'); ?>index.php/asset/apiGetAssetsUsingSearchTerm',
		focus: function(event, ui){
			$( "#new-link-asset-name" ).val( ui.item.cleanLabel );
			$( "#new-link-asset-id" ).val( ui.item.value );
			return false;
		},
		select: function(event, ui){
			$( "#new-link-asset-name" ).val( ui.item.cleanLabel );
			$( "#new-link-asset-id" ).val( ui.item.value );
			return false;
		},
		search: function(event, ui){
			$("#searchIcon").addClass('icon-refresh').addClass('icon-spin');
			setFormErrorColors(-1);
		},
		response: function(event, ui){
			$("#searchIcon").removeClass('icon-refresh').removeClass('icon-spin');
			if(ui.content.length==0){ 
				setFormErrorColors({new_linked_asset:"No Results Found"});
			}

		}
	})
	.data("ui-autocomplete")._renderItem = function( ul, item ){
		ul.addClass('dropdown-menu');
		ul.addClass('searchResultDropdown');
		return $("<li>").append("<a>"+item.label+"</a>").appendTo(ul);
	};
});

</script>
