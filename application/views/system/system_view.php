<?php
	$websiteNameFormAttributes=array(
		'id' 			=> 'updateWebsitenameForm'
		,'onsubmit' 	=> 'asmChangeName(); return false;'
	);
	$otherSettingFormAttributes=array(
		'id' 			=> 'updateSettingsForm'
		,'onsubmit' 	=> 'asmSaveOtherSettings(); return false;'
	);
	for($i=1;$i<=20;$i++){

	}
	echo
		'<div class="row-fluid">'

			,'<div class="text-align-left span6">'
				,heading('<i class="icon-home"></i> Website Title', 2)
				,'<div class="well">'
					,form_open('',$websiteNameFormAttributes)
					,'<div wrapping="website_title" class="control-group">'
						,form_label('Website Name <span errorMessageFor="website_title"></span>','website_title',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'website_title', 'id' => 'website_title', 'originalValue' => set_value('website_title',$websiteTitle), 'value' => set_value('website_title',$websiteTitle) , 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
					,'</div>'
					,'<div class="control-group">'
						,form_button(array('id' => 'websiteNameSubmit', 'type' => 'submit', 'name' => 'Save', 'value' => 'Save', 'content' => 'Save' , 'class' => 'btn disabled btn-large btn-block'))
					,'</div>'
					,form_close()
				,'</div>'
			,'</div>'

			,'<div class="text-align-left span6">'
				,heading('<i class="icon-cog icon-spin"></i> Other Settings', 2)
				,'<div class="well tooltip-me">'
					,form_open('',$otherSettingFormAttributes)
					,'<div wrapping="num_recent_created" class="control-group">'
						,form_label('Number of "Recently Created" assets to show: <span errorMessageFor="num_recent_created"></span>','num_recent_created',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'num_recent_created', 'id' => 'num_recent_created','originalValue' => set_value('num_recent_created',$this->config->item('num_recent_created')), 'value' => set_value('num_recent_created',$this->config->item('num_recent_created')) , 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
					,'</div>'

					,'<div wrapping="num_recent_changed" class="control-group">'
						,form_label('Number of "Recently Changed" assets to show: <span errorMessageFor="num_recent_changed"></span>','num_recent_changed',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'num_recent_changed', 'id' => 'num_recent_changed','originalValue' => set_value('num_recent_changed',$this->config->item('num_recent_changed')), 'value' => set_value('num_recent_changed',$this->config->item('num_recent_changed')) , 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
					,'</div>'

					,'<div wrapping="admin_email" class="control-group">'
						,form_label('Admin Email (<a href="#" data-toggle="tooltip" title="In case anything goes wrong, or needs attention - This is the guy who will know it first."><i class="icon-question"></i></a>) <span errorMessageFor="admin_email"></span>','admin_email',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'admin_email', 'id' => 'admin_email','originalValue' => set_value('admin_email',$this->config->item('admin_email')), 'value' => set_value('admin_email',$this->config->item('admin_email')) , 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
					,'</div>'

					,'<div wrapping="system_email" class="control-group">'
						,form_label('Email Bot Address (<a href="#" data-toggle="tooltip" title="This is the email address that shows up in automated emails from the asset manager. It doesn\'t need to be a real email address."><i class="icon-question"></i></a>) <span errorMessageFor="system_email"></span>','system_email',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'system_email', 'id' => 'system_email','originalValue' => set_value('system_email',$this->config->item('system_email')), 'value' => set_value('system_email',$this->config->item('system_email')) , 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
					,'</div>'

					,'<div wrapping="system_email_name" class="control-group">'
						,form_label('Email Bot Name (<a href="#" data-toggle="tooltip" title="This is what shows up as the name in the asset manager automated emails."><i class="icon-question"></i></a>) <span errorMessageFor="system_email_name"></span>','system_email_name',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'system_email_name', 'id' => 'system_email_name','originalValue' => set_value('system_email_name',$this->config->item('system_email_name')), 'value' => set_value('system_email_name',$this->config->item('system_email_name')) , 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
					,'</div>'

					,'<div wrapping="allow_registering" class="control-group">'
						,form_label(form_checkbox(array('name' => 'allow_registering', 'value' => 1, 'checked' => ($this->config->item('allow_registering') == 1), 'originalValue' => ($this->config->item('allow_registering') == 1))).'Allow Registering <span errorMessageFor="allow_registering"></span>','',array('class' => 'checkbox control-label'))
					,'</div>'

					,'<div wrapping="show_build_date" class="control-group">'
						,form_label(form_checkbox(array('name' => 'show_build_date', 'value' => 1, 'checked' => ($this->config->item('show_build_date') == 1), 'originalValue' => ($this->config->item('show_build_date') == 1))).'Show Build Date <span errorMessageFor="show_build_date"></span>','',array('class' => 'checkbox control-label'))
					,'</div>'

					,'<div class="control-group">'
						//,'<div class="controls">'.form_button(array('id' => 'settingsChangeSubmit', 'type' => 'submit', 'onClick' =>'updateSettings();', 'name' => 'Save', 'value' => 'Save', 'content' => 'Save' , 'class' => 'btn disabled btn-large btn-block')).'</div>'
						,form_button(array('id' => 'settingsChangeSubmit', 'type' => 'submit', 'name' => 'Save', 'value' => 'Save', 'content' => 'Save' , 'class' => 'btn disabled btn-large btn-block'))
					,'</div>'
					,form_close()
				,'</div>'
			,'</div>'

		,'</div>';
		$this->load->view('utility/initFormOnChangeActivate.php',array(
			'formID' 		=> 'updateWebsitenameForm'
			,'submitButtonID' => 'websiteNameSubmit'
		));
		$this->load->view('utility/initFormOnChangeActivate.php',array(
			'formID' 		=> 'updateSettingsForm'
			,'submitButtonID' => 'settingsChangeSubmit'
		));
?>
<script type="text/JavaScript">

	function asmChangeName(){
		if(!asmCheckChangesWith_updateWebsitenameForm()){
			return false;
		}
		$.ajax({
			url:"system/apiChangeWebsiteName"
			,type:'post'
			,data:$('#updateWebsitenameForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){ //html request succeed?
				if(reply.success){
					showHappyMessage(reply.message);
					$('#homeText').html(reply.data.website_title);
					$("input[name=website_title]").attr('originalValue',reply.data.website_title);
					asmCheckChangesWith_updateWebsitenameForm();
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error updating data:\n"+error);
			}
		});
	}
	

	function asmSaveOtherSettings(){
		if(!asmCheckChangesWith_updateSettingsForm()){
			return false;
		}

		$.ajax({
			url:"system/apiUpdateSiteSettings"
			,type:'post'
			,data:$('#updateSettingsForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){ //html request succeed?
				if(reply.success){
					showHappyMessage(reply.message);

					if(reply.data.show_build_date==1){
						$('#buildDate').removeClass('hidden');	
					}else{
						$('#buildDate').addClass('hidden');	
					}
					

					$("#updateSettingsForm :input").each(function(){
			        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
				        	if($(this).is(":checkbox")){
				        		if($(this).prop('checked')){
				        			$(this).attr('originalValue',1);
				        		}else{
				        			$(this).attr('originalValue',0);
				        		}
	        				}else{
				        		$(this).attr('originalValue',$(this).val());
				        	}			        	
			        	}
			   		});
					asmCheckChangesWith_updateSettingsForm();
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error updating data:\n"+error);
			}
		});
	}
</script>