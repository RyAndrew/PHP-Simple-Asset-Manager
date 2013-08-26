<?php
	$snmpWalkFormAttributes=array(
		'id' 		=> 'snmpWalkForm'
		,'class' 	=> 'form-inline'
		,'onsubmit' => 'snmpWalk(); return false;'
	);
	$snmpAddTaskAttributes=array(
		'id' 		=> 'snmpAddTaskForm'
		,'class' 	=> 'form-inline'
		,'onsubmit' => 'addTask(); return false;'
	);
	echo
		'<div class="row-fluid">'
			,'<div class="text-align-left span6">'
				,heading('<i class="icon-road"></i> SNMP Walker', 2)
				,'<div class="well">'
					,form_open('',$snmpWalkFormAttributes)
					,'<div wrapping="ip_to_walk" class="control-group">'
						,form_label('IP Address <span errorMessageFor="ip_to_walk"></span>','ip_to_walk',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'ip_to_walk','id' => 'ip_to_walk', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
					,'</div>'
					,'<div wrapping="object_id" class="control-group">'
						,form_label('Object ID <span errorMessageFor="object_id"></span>','object_id',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'object_id','id' => 'object_id', 'class' => 'input-block-level' ,'maxlength' =>30, 'value' => '.1.3.6.1.2.1.2')).'</div>'
					,'</div>'
					,'<div class="control-group">'
						,'<div class="controls">'.form_button(array('type'=>'submit', 'id' => 'snmpWalkSubmit', 'name' => 'Walk', 'value' => 'Walk', 'content' => '<i class="icon-refresh icon-spin hidden" id="walkButton"></i> Walk' , 'class' => 'btn btn-primary btn-large btn-block')).'</div>'
					,'</div>'
					,'<div wrapping="walkResultsField" class="control-group">'
						,form_label('Results','walkResultsField',array('class' => 'control-label'))
						,'<div class="controls">'.form_textarea(array('name' => 'walkResultsField','id' => 'walkResultsField', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
					,'</div>'
					,form_close()
				,'</div>'
			,'</div>'

			,'<div class="text-align-left span6">'
				
				,heading('<i class="icon-off"></i> Run Tasks', 2)
				,'<div class="well">'
					,form_open('')
					,'<div class="control-group">'
						,'<div class="controls">'.form_button(array('type'=>'submit', 'id' => 'runTasksSubmit', 'name' => 'Run Tasks Now', 'value' => 'Run Tasks Now', 'content' => 'Run Tasks Now' , 'class' => 'btn btn-primary btn-large btn-block')).'</div>'
						,'<p class="text-align-center">(This can also be run through a scheduled task on your server.)</p>'
					,'</div>'
					,form_close()

				,'</div>'

				,heading('<i class="icon-tasks"></i> SNMP Tasks', 2)
				,'<div class="well">'
					
				,'</div>'

				,heading('<i class="icon-plus"></i> Add Task', 2)
				,'<div class="well">'
					,form_open('',$snmpAddTaskAttributes)
					,'<div wrapping="new_task_asset_id" class="control-group">'
	 					,'<input type="hidden" name="new_task_asset_id" id="new_task_asset_id"></input>'
	 					,form_label('Select Asset (Begin typing) <i id="taskAssetSearchIcon"></i><span errorMessageFor="new_task_asset_id"></span>','new_task_asset_id',array('class' => 'control-label'))
	 					,'<div class="controls">'.form_input(array('id' => 'new_task_asset_name' , 'originalValue'=>'' ,'class' => 'input-block-level' )).'</div>'
	 				,'</div>'

	 				,'<div wrapping="new_task_ip" class="control-group">'
	 					,form_label('Select IP Address Attribute </i><span errorMessageFor="new_task_ip"></span>','new_task_ip',array('class' => 'control-label'))
						,'<div class="controls">'.form_dropdown('new_task_ip',array(''=>''),'','class="input-block-level" originalValue="" id="new_task_ip"').'</div>'
					,'</div>'

					,'<div wrapping="new_task_object_id" class="control-group">'
						,form_label('Object ID <span errorMessageFor="new_task_object_id"></span>','new_task_object_id',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'new_task_object_id','id' => 'new_task_object_id', 'class' => 'input-block-level' ,'maxlength' =>30, 'originalValue' => '')).'</div>'
					,'</div>'

					,'<div wrapping="new_task_attribute_add" class="control-group">'
	 					,form_label('Select attribute to add </i><span errorMessageFor="new_task_attribute_add"></span>','new_task_attribute_add',array('class' => 'control-label'))
						,'<div class="controls">'.form_dropdown('new_task_attribute_add',$all_attributes,'','class="input-block-level" originalValue="" id="new_task_attribute_add"').'</div>'
					,'</div>'

					,'<div wrapping="new_task_note_type" class="control-group">'
	 					,form_label('Select note type to add </i><span errorMessageFor="new_task_note_type"></span>','new_task_note_type',array('class' => 'control-label'))
						,'<div class="controls">'.form_dropdown('new_task_note_type',$all_note_types,'','class="input-block-level" originalValue="" id="new_task_note_type"').'</div>'
					,'</div>'

					,'<div wrapping="new_task_prefix" class="control-group">'
						,form_label('Data Prefix <span errorMessageFor="new_task_prefix"></span>','new_task_prefix',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'new_task_prefix','id' => 'new_task_prefix', 'class' => 'input-block-level' ,'maxlength' =>30, 'originalValue' => '')).'</div>'
					,'</div>'

					,'<div wrapping="new_task_postfix" class="control-group">'
						,form_label('Data Postfix <span errorMessageFor="new_task_postfix"></span>','new_task_postfix',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'new_task_postfix','id' => 'new_task_postfix', 'class' => 'input-block-level' ,'maxlength' =>30, 'originalValue' => '')).'</div>'
					,'</div>'
					
					,'<div class="control-group">'
						,'<div class="controls">'.form_button(array('type'=>'submit', 'id' => 'taskAddSubmit', 'name' => 'Add', 'value' => 'Add', 'content' => 'Add' , 'class' => 'btn disabled btn-large btn-block')).'</div>'
					,'</div>'
					,form_close()
				,'</div>'
			,'</div>'
		,'</div>';
?>
<script>
	function snmpWalk(){
		closeMessage();
		$('#walkResultsField').val('');
		$('#walkButton').removeClass('hidden');
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/system/apiSnmpWalk"
			,type:'post'
			,data:$('#snmpWalkForm').serializeArray()
			,dataType:"json"
			//,timeout:3000
			,success:function(reply){
				if(reply.success){
					$('#walkResultsField').val(reply.data.walkResults);
				}
				setFormErrorColors(reply.failed_fields);
				$('#walkButton').addClass('hidden');
			}
			,error:function(obj,error){
				showErrorMessage("Failed to walk using the given IP Address and Object ID");
				$('#walkButton').addClass('hidden');
			}
		});
	}

	$(function(){
		$( "#new_task_asset_name" ).autocomplete({
			minLength: 2,
			source: '<?php echo $this->config->item('base_url'); ?>index.php/asset/apiGetAssetsUsingSearchTerm',
			focus: function(event, ui){
				$( "#new_task_asset_name" ).val( ui.item.cleanLabel );
				$( "#new_task_asset_id" ).val( ui.item.value );
				$("#new_task_ip option").remove();
				return false;
			},
			select: function(event, ui){
				$( "#new_task_asset_name" ).val( ui.item.cleanLabel );
				$( "#new_task_asset_id" ).val( ui.item.value );
				$("#new_task_ip option").remove();
				getAssetAttributes();
				return false;
			},
			search: function(event, ui){
				$("#taskAssetSearchIcon").addClass('icon-refresh').addClass('icon-spin');
				$("#new_task_ip option").remove();
				setFormErrorColors(-1);
			},
			response: function(event, ui){
				$("#taskAssetSearchIcon").removeClass('icon-refresh').removeClass('icon-spin');
				if(ui.content.length==0){ 
					setFormErrorColors({new_task_asset_id:"No Results Found"});
				}

			}
		})
		.data("ui-autocomplete")._renderItem = function( ul, item ){
			ul.addClass('dropdown-menu');
			ul.addClass('searchResultDropdown');
			return $("<li>").append("<a>"+item.label+"</a>").appendTo(ul);
		};
	});

	function getAssetAttributes(){
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/asset/apiGetAssetAttributes"
			,type:'post'
			,data:{asset_id:$('#new_task_asset_id').val()}
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					var foundIP = false;
					for(i in reply.data.attributes){
						newOption = $('<option></option>').val(reply.data.attributes[i]['asset_attribute_id']).html(reply.data.attributes[i]['attribute_name']+" = "+reply.data.attributes[i]['attribute_value'])
						if(!foundIP && reply.data.attributes[i]['attribute_name'].toLowerCase().indexOf("ip address") !== -1){
							foundIP=true;
							$('input[name=ip_to_walk]').val(reply.data.attributes[i]['attribute_value']);
							newOption.attr("selected","selected"); 
						}
						$('#new_task_ip').append(newOption);
					}
				}
			}
			,error:function(obj,error){
				showErrorMessage("Error getting asset attributes: "+error);
			}
		});
	}


	$("#snmpAddTaskForm :input").on({
		'keydown input':function(){
	   		checkChangesWithAddTaskForm();
   		}
   		,change:function(){
   			checkChangesWithAddTaskForm();
   		}
	});

	function checkChangesWithAddTaskForm(){
		var changed = false;
   		$("#snmpAddTaskForm :input").each(function(){
        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
	        	if($(this).val() != $(this).attr('originalValue')){
	        		changed=true;
	        	}
        	}
   		});
   		if(changed){
   			$("#taskAddSubmit").addClass('btn-primary');
			$("#taskAddSubmit").removeClass('disabled');
			return true;
   		}else{
   			$("#taskAddSubmit").removeClass('btn-primary');
			$("#taskAddSubmit").addClass('disabled');
			return false;
   		}
	}

	function addTask(){
		if(!checkChangesWithAddTaskForm()){
			return false;
		}
	}


</script>		