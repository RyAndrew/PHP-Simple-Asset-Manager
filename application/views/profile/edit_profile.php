<?php
$formAttributes=array(
	'id' => 'updateUserForm'
	,'onsubmit' => 'edit(); return false;'
);

echo
	'<div class="row-fluid">'
	,'<div class="text-align-left span6">'
	,heading('<i class="icon-user"></i> Update Info', 2)
	,'<div class="well">'
	,form_open('',$formAttributes)
	,'<div wrapping="user_email" class="control-group">'
		,form_label('Email <span errorMessageFor="user_email"></span>','',array('class' => 'control-label'))
		,'<div class="controls">'.'<span class="input-block-level uneditable-input">'.$user['user_email'].'</span>'.'</div>'
	,'</div>'
	,'<div wrapping="user_first_name" class="control-group">'
		,form_label('First Name <span errorMessageFor="user_first_name"></span>','user_first_name',array('class' => 'control-label'))
 		,'<div class="controls">'.form_input( array('name' => 'user_first_name','id' => 'user_first_name','value'=> set_value('user_first_name',$user['user_first_name']),'originalValue'=> set_value('user_first_name',$user['user_first_name']),'class'=> 'input-block-level')).'</div>'
	,'</div>'
	,'<div wrapping="user_last_name" class="control-group">'
		,form_label('Last Name <span errorMessageFor="user_last_name"></span>','user_last_name',array('class' => 'control-label'))
		,'<div class="controls">'.form_input(array('name' => 'user_last_name','id' => 'user_last_name','value'=> set_value('user_last_name',$user['user_last_name']),'originalValue'=> set_value('user_last_name',$user['user_last_name']),'class'=> 'input-block-level')).'</div>'
	,'</div>'
	,'<div wrapping="user_theme" class="control-group">'
		,form_label('Select Theme <span errorMessageFor="user_theme"></span>','user_theme',array('class' => 'control-label'))
		,'<div class="controls">'.form_dropdown('user_theme',$allThemes,set_value('user_theme',$user['user_theme']),'id="user_theme" class="input-block-level" originalValue="'.set_value('user_theme',$user['user_theme']).'"').'</div>'
	,'</div>'
	,'<div class="control-group">'
		,'<div class="controls">'.form_button(array('id' => 'profileEditSubmit','type' => 'submit', 'name' => 'Update', 'value' => 'Update', 'content' => 'Update' , 'class' => 'btn disabled btn-large input-block-level')).'</div>'
	,'</div>'
	,form_close()
	,'</div></div>';

$formAttributes=array(
	'id' => 'updatePasswordForm'
	,'onsubmit' => 'updatePassword(); return false;'
);

echo
	'<div class="text-align-left span6">'
	,heading('<i class="icon-key"></i> Change Password', 2)
	,'<div class="well">'
	,form_open('profile/changePassword',$formAttributes)
	,'<div wrapping="new_password" class="control-group">'
		,form_label('New Password <span errorMessageFor="new_password"></span>','new_password',array('class' => 'control-label'))
		,'<div class="controls">'.form_password(array('name' => 'new_password','id'	=> 'new_password','value' => set_value('new_password'),'originalValue' => set_value('new_password'),'class' => 'input-block-level')).'</div>'
	,'</div>'
	,'<div wrapping="new_password_again" class="control-group">'
		,form_label('New Password (Again) <span errorMessageFor="new_password_again"></span>','new_password_again',array('class' => 'control-label'))
		,'<div class="controls">'.form_password(array('name' => 'new_password_again','id' => 'new_password_again','value' => set_value('new_password_again'),'originalValue' => set_value('new_password_again'),'class'=> 'input-block-level')).'</div>'
	,'</div>'
	,'<div class="control-group">'
		,'<div class="controls">'.form_button(array('id' => 'passwordFormSubmit','type' => 'submit', 'name' => 'Change Password', 'value' => 'Change Password', 'content' => 'Change Password' , 'class' => 'btn disabled btn-large input-block-level')).'</div>'
	,'</div>'
	,form_close()
	,'</div></div></div>';
?>

<script type="text/JavaScript">

	$("#updateUserForm :input").on({
		'keydown input':function(){
	   		checkChangesWithProfileEditForm();	
   		}
   		,change:function(){
   			checkChangesWithProfileEditForm();
   		}
	});

	function checkChangesWithProfileEditForm(){
		var changed = false;
   		$("#updateUserForm :input").each(function(){
        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
	        	if($(this).val() != $(this).attr('originalValue')){
	        		changed=true;
	        	}
        	}
   		});
   		if(changed){
   			$("#profileEditSubmit").addClass('btn-primary');
			$("#profileEditSubmit").removeClass('disabled');
			return true;
   		}else{
   			$("#profileEditSubmit").removeClass('btn-primary');
			$("#profileEditSubmit").addClass('disabled');
			return false;
   		}
	}

	function edit(){
		if(!checkChangesWithProfileEditForm()){
			return false;
		}

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/profile/apiUpdateProfile"
			,type:'post'
			,data:$('#updateUserForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){ //html request succeed?
				if(reply.success){
					showHappyMessage(reply.message);
					$('#yourUsername').html(reply.data.user_first_name);
					$("link:first").attr("href","<?php echo $this->config->item('base_url'); ?>css/themes/"+reply.data.theme_filename+".css");

					setTimeout(adjustNavBarSearchCarrot,2000);
				
					$("#updateUserForm :input").each(function(){
			        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
				        	$(this).attr('originalValue',$(this).val());			        	
			        	}
			   		});
					
					checkChangesWithProfileEditForm();
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error,error2){
				showErrorMessage("Error updating data:\n"+error);
			}
		});
	}

	$("#updatePasswordForm :input").on({
		'keydown input':function(){
	   		checkChangesWithPasswordForm();	
   		}
   		,change:function(){
   			checkChangesWithPasswordForm();
   		}
	});

	function checkChangesWithPasswordForm(){
		var changed = false;
   		$("#updatePasswordForm :input").each(function(){
        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
	        	if($(this).val() != $(this).attr('originalValue')){
	        		changed=true;
	        	}
        	}
   		});
   		if(changed){
   			$("#passwordFormSubmit").addClass('btn-primary');
			$("#passwordFormSubmit").removeClass('disabled');
			return true;
   		}else{
   			$("#passwordFormSubmit").removeClass('btn-primary');
			$("#passwordFormSubmit").addClass('disabled');
			return false;
   		}
	}

	function updatePassword(){
		if(!checkChangesWithPasswordForm()){
			return false;
		}

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/profile/apiChangePassword"
			,type:'post'
			,data:$('#updatePasswordForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){ //html request succeed?
				if(reply.success){
					showHappyMessage(reply.message);
					$("#updatePasswordForm :input").each(function(){
			        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
				        	$(this).val('');			        	
			        	}
			   		});
					checkChangesWithPasswordForm()
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error,error2){
				showErrorMessage("Error changing password:\n"+error);
			}
		});
	}
</script>