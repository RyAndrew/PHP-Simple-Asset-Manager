<?php
	$formAttributes=array(
		'id' 		=> 'addUserForm'
		,'class' 	=> 'form-inline'
		,'onsubmit' => 'asmAddUser(); return false;'
	);

	echo
		'<div class="row-fluid">'
			,'<div class="text-align-left span6">'
			,heading('<i class="icon-plus-sign"></i> Add User', 2)
			,'<div class="well">'
				,form_open('',$formAttributes)
				,'<div wrapping="addUserEmailField" class="control-group">'
					,form_label('Email <span errorMessageFor="addUserEmailField"></span>','addUserEmailField',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'addUserEmailField','id' => 'addUserEmailField', 'class' => 'input-block-level' ,'maxlength' =>30, 'originalValue' => '')).'</div>'
				,'</div>'
				,'<div wrapping="addUserFnameField" class="control-group">'
					,form_label('First Name <span errorMessageFor="addUserFnameField"></span>','addUserFnameField',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'addUserFnameField','id' => 'addUserFnameField', 'class' => 'input-block-level' ,'maxlength' =>30, 'originalValue' => '')).'</div>'
				,'</div>'
				,'<div wrapping="addUserLnameField" class="control-group">'
					,form_label('Last Name <span errorMessageFor="addUserLnameField"></span>','addUserLnameField',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'addUserLnameField','id' => 'addUserLnameField', 'class' => 'input-block-level' ,'maxlength' =>30, 'originalValue' => '')).'</div>'
				,'</div>'
				,'<div wrapping="addUserAdminField" class="control-group">'
					,form_label(form_checkbox(array('name' => 'addUserAdminField', 'value' => 1,'originalValue' => false)).'Is Admin','',array('class' => 'checkbox control-label'))
				,'</div>'
				,'<div wrapping="addUserDisableField" class="control-group">'
					,form_label(form_checkbox(array('name' => 'addUserDisableField', 'value' => 1 ,'originalValue' => false)).'Is Disabled','',array('class' => 'checkbox control-label'))
				,'</div>'
				,'<div wrapping="addUserPasswdField" class="control-group">'
					,form_label('Password ( <b>Minimum 8 characters</b> ) <span errorMessageFor="addUserPasswdField"></span>','addUserPasswdField',array('class' => 'control-label'))
					,'<div class="controls">'.form_password(array('name' => 'addUserPasswdField','id' => 'addUserPasswdField', 'class' => 'input-block-level' ,'maxlength' =>30, 'originalValue' => '')).'</div>'
				,'</div>'
				,'<div wrapping="addUserPasswdField2" class="control-group">'
					,form_label('Password Again <span errorMessageFor="addUserPasswdField2"></span>','addUserPasswdField2',array('class' => 'control-label'))
					,'<div class="controls">'.form_password(array('name' => 'addUserPasswdField2','id' => 'addUserPasswdField2', 'class' => 'input-block-level' ,'maxlength' =>30, 'originalValue' => '')).'</div>'
				,'</div>'
				,'<div class="control-group">'
					,'<div class="controls">'.form_button(array('type' => 'submit', 'id' => 'addUserSubmit', 'name' => 'Add', 'value' => 'Add', 'content' => 'Add' , 'class' => 'btn disabled btn-large btn-block')).'</div>'
				,'</div>'
				,form_close()
		,'</div></div>';

	$formAttributes=array(
		'id' => 'deleteUserForm'
	);

	echo
		'<div class="text-align-left span6">'
		,heading('<i class="icon-user-md"></i> User Management', 2)
		,'<div class="well">'
		,form_open('',$formAttributes)
		,'<div class="row-fluid">'
		,'<div class="span6">'
		,form_label('Click any user to edit','')
		,'</div>'
		,'<div class="span6">'
		,'<table class="table table-condensed table-bordered table-hover"><tr class="error"><td>User Is Disabled</td></tr><tr class="success"><td>User Is Admin</td></tr></table>'
		,'</div>'
		,'</div>'
		,'<table id="userTable" class="table table-condensed table-bordered table-hover">'
		,'<thead>'
		,'<tr><th>Email</th><th>First Name</th><th>Last Name</th><!--<th>Select</th>--></tr>'
		,'</thead>'
		,'<tbody>';
	foreach($users as $user){
		echo '<tr class="';
		if($user['is_disabled']){
			echo ' error ';
		}else if($user['is_admin']){
			echo ' success ';
		}
		echo 'userRow" userid="'.$user['user_id'].'"><td emailOfUser="'.$user['user_id'].'">'.$user['user_email'].'</td><td fnameOfUser="'.$user['user_id'].'">'.$user['user_first_name'].'</td><td lnameOfUser="'.$user['user_id'].'">'.$user['user_last_name'].'</td><!--<td>'.form_checkbox().'</td>--></tr>';
	}
	echo
		'</tbody>'
		,'</table>'
		//Bulk Delete/Disable - Coming soon.
		//,form_dropdown('selectAction',array('delete' => 'Delete Selected','disable' => 'Disable Selected'),'','class="input-block-level"')
		//,form_button(array('name' => 'Update', 'value' => 'Update', 'content' => 'Update' , 'class' => 'btn btn-danger btn-large input-block-level'))
		,form_close()
		,'</div></div>'
		,'</div>';
	$formAttributes=array(
		'id' => 'editUserForm'
		,'class' => 'form-inline'
	);
	echo 
		'<div id="editUser" class="modal hide fade text-align-left" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">'
			,'<div class="modal-header">'
				,'<button type="button" class="close editUserCancel" data-dismiss="modal" aria-hidden="true">×</button>'
				,'<h3 id="editUserName">[Name]</h3>'
			,'</div>'
			,'<div class="modal-body">'
				,'<div id="modalmessage" class="notify-box cssCentered"></div>'
				,form_open('',$formAttributes)
				,'<div wrapping="addUserIdField" class="control-group">'
					,form_hidden(array('editUserIdField' => ''))
				,'</div>'
				,'<div wrapping="addOrgEmailField" class="control-group">'
					,form_hidden(array('editOrgEmailField' => ''))
				,'</div>'
				,'<div wrapping="addOrgAdminField" class="control-group">'
					,form_hidden(array('editOrgAdminField' => ''))
				,'</div>'
				,'<div wrapping="addOrgDisableField" class="control-group">'
					,form_hidden(array('editOrgDisableField' => ''))
				,'</div>'
				,'<div wrapping="editUserEmailField" class="control-group">'
					,form_label('Email ( <b>This will change this user\'s login</b> )  <span errorMessageFor="editUserEmailField"></span>','editUserEmailField',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'editUserEmailField','id' => 'editUserEmailField', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
				,'</div>'
				,'<div wrapping="editUserFnameField" class="control-group">'
					,form_label('First Name  <span errorMessageFor="editUserFnameField"></span>','editUserFnameField',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'editUserFnameField','id' => 'editUserFnameField', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
				,'</div>'
				,'<div wrapping="editUserLnameField" class="control-group">'
					,form_label('Last Name  <span errorMessageFor="editUserLnameField"></span>','editUserLnameField',array('class' => 'control-label'))
					,'<div class="controls">'.form_input(array('name' => 'editUserLnameField','id' => 'editUserLnameField', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
				,'</div>'
				,'<div wrapping="editUserAdminField" class="control-group">'
					,form_label(form_checkbox(array('name' => 'editUserAdminField', 'value' => 1)).'Is Admin  <span errorMessageFor="editUserAdminField"></span>','',array('class' => 'checkbox control-label'))
				,'</div>'
				,'<div wrapping="editUserDisableField" class="control-group">'
					,form_label(form_checkbox(array('name' => 'editUserDisableField', 'value' => 1)).'Is Disabled  <span errorMessageFor="editUserDisableField"></span>','',array('class' => 'checkbox control-label'))
				,'</div>'
				,'<div wrapping="editUserPasswdField" class="control-group">'
					,form_label('New Password ( <b>Minimum 8 characters</b> )  <span errorMessageFor="editUserPasswdField"></span>','editUserPasswdField',array('class' => 'control-label'))
					,'<div class="controls">'.form_password(array('name' => 'editUserPasswdField','id' => 'editUserPasswdField', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
				,'</div>'
				,'<div wrapping="editUserPasswdField2" class="control-group">'
					,form_label('New Password Again  <span errorMessageFor="editUserPasswdField2"></span>','editUserPasswdField2',array('class' => 'control-label'))
					,'<div class="controls">'.form_password(array('name' => 'editUserPasswdField2','id' => 'editUserPasswdField2', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
				,'</div>'
				,form_close()
			,'</div>'
			,'<div class="modal-footer">'
				,'<button id="deleteUserSubmit" class="btn btn-danger" style="float:left;">Delete User</button>'
				,'<button class="btn editUserCancel" data-dismiss="modal" aria-hidden="true">Cancel</button>'
				,'<button id="editUserSubmit" class="btn btn-primary" >Save changes</button>'
			,'</div>'
		,'</div>';

		$this->load->view('utility/initFormOnChangeActivate.php',array(
			'formID' 		=> 'addUserForm'
			,'submitButtonID' => 'addUserSubmit'
		));
?>
<script type="text/JavaScript">
	function initUserTable(){
		$('.userRow').mouseover(function(){
	    	$(this).css('cursor','pointer');
	    	$(this).css('text-decoration','underline');
	  	});
	  	$('.userRow').mouseout(function(){
	    	$(this).css('cursor','auto');
	    	$(this).css('text-decoration','none');
	  	});
	  	$('.userRow').click(function(){
	  		$.ajax({
				url:"<?php echo $this->config->item('base_url'); ?>index.php/system/apiLoadUserInfo"
				,type:'post'
				,data:{user_id:$(this).attr('userid')}
				,dataType:"json"
				,timeout:3000
				,success:function(reply){
					if(reply.success){
						$('#editUserName').html('<i class="icon-user"></i> '+reply.data.user_first_name+" "+reply.data.user_last_name);
						$('input[name=editUserIdField]').val(reply.data.user_id);
						$('input[name=editOrgEmailField]').val(reply.data.user_email);
						$('input[name=editOrgAdminField]').val(reply.data.is_admin);
						$('input[name=editOrgDisableField]').val(reply.data.is_disabled);
						$('input[name=editUserEmailField]').val(reply.data.user_email);
						$('input[name=editUserFnameField]').val(reply.data.user_first_name);
						$('input[name=editUserLnameField]').val(reply.data.user_last_name);
						$('input[name=editUserAdminField]').prop('checked', reply.data.is_admin==1);
						$('input[name=editUserDisableField]').prop('checked', reply.data.is_disabled==1);
						$('input[name=editUserPasswdField]').val("");
						$('input[name=editUserPasswdField2]').val("");
						$('#editUser').modal('show');
					}
					else{
						showErrorMessage(reply.message);
					}
				}
				,error:function(obj,error){
					showErrorMessage("Error loading user:\n"+error);
				}
			});
	  	});
	}
	initUserTable();



	$('#deleteUserSubmit').click(function(){
		user = $('input[name=editUserFnameField]').val()+" "+$('input[name=editUserLnameField]').val();
		if(!confirm("Do you really want to delete "+user+"?")){
			return false;
		}
		if ($('input[name=editUserIdField]').val() == <?php $session_data = $this->session->userdata('logged_in'); echo $session_data['user_id']; ?> && !confirm("YOU ARE ABOUT TO DELETE YOURSELF!\nAre you sure you wish to do this?")){	
			return false;
		}
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/system/apiDeleteUser"
			,type:'post'
			,data:{user_id:$('input[name=editUserIdField]').val()}
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					$('#editUser').modal('hide')
					showHappyMessage(reply.message+" "+user);
					$('tr[userid='+reply.data.user_id+']').remove();
				}
				else{
					showErrorMessage(reply.message,'#modalmessage');
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error adding user:\n"+error,'#modalmessage');
			}
		});
	});


	function asmAddUser(){
		if(!asmCheckChangesWith_addUserForm()){
			return false;
		}

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/system/apiAddUser"
			,type:'post'
			,data:$('#addUserForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message);
					newRow = '<tr class="';
					if(reply.data.is_disabled == 1){
						newRow += ' error ';
					}else if(reply.data.is_admin == 1){
						newRow += ' success ';
					}
					newRow += 'userRow" userid="'+reply.data.user_id+'"><td emailOfUser="'+reply.data.user_id+'">'+reply.data.user_email+'</td><td fnameOfUser="'+reply.data.user_id+'">'+reply.data.user_first_name+'</td><td lnameOfUser="'+reply.data.user_id+'">'+reply.data.user_last_name+'</td><!--<td><?php echo form_checkbox(); ?></td>--></tr>';

					$('#userTable tr:last').after(newRow);
					initUserTable();

					$('input[name=addUserEmailField]').val('');
					$('input[name=addUserFnameField]').val('');
					$('input[name=addUserLnameField]').val('');
					$('input[name=addUserAdminField]').prop('checked', false);
					$('input[name=addUserDisableField]').prop('checked', false);
					$('input[name=addUserPasswdField]').val('');
					$('input[name=addUserPasswdField2]').val('');

					asmCheckChangesWith_addUserForm();
				
				}
				else{
					//showErrorMessage(reply.message);
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error adding user:\n"+error,'#modalmessage');
			}
		});
	}
  	$('#editUser').on('hide', function () {
    	closeMessage('#modalmessage');
    	setFormErrorColors(-1); //resets form fields if there was an error on them.
    });
	$('#editUserSubmit').click(function(){
		if (!$('input[name=editUserAdminField]').prop('checked') && $('input[name=editUserIdField]').val() == <?php $session_data = $this->session->userdata('logged_in'); echo $session_data['user_id']; ?> && !confirm("You're about to demote yourself.\nIf you continue, you will be unable to promote yourself again.\nContinue?")){	
				return false;
		}
		if ($('input[name=editUserDisableField]').prop('checked') && $('input[name=editUserIdField]').val() == <?php $session_data = $this->session->userdata('logged_in'); echo $session_data['user_id']; ?> && !confirm("You're about to disable yourself.\nIf you continue, you will be logged out and unable to login again.\nContinue?")){	
				return false;
		}
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/system/apiUpdateUserInfo"
			,type:'post'
			,data:$('#editUserForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					showHappyMessage(reply.message);
					$('#editUser').modal('hide')
					$('*[emailOfUser="'+reply.data.user_id+'"]').html(reply.data.user_email);
					$('*[fnameOfUser="'+reply.data.user_id+'"]').html(reply.data.user_first_name);
					$('*[lnameOfUser="'+reply.data.user_id+'"]').html(reply.data.user_last_name);

					if(reply.data.is_admin==1){
						$('tr[userid="'+reply.data.user_id+'"]').addClass('success');
						$('tr[userid="'+reply.data.user_id+'"]').removeClass('error');
					}else{
						$('tr[userid="'+reply.data.user_id+'"]').removeClass('success');
					}

					if(reply.data.is_disabled==1){
						$('tr[userid="'+reply.data.user_id+'"]').addClass('error');
						$('tr[userid="'+reply.data.user_id+'"]').removeClass('success');
					}else{
						$('tr[userid="'+reply.data.user_id+'"]').removeClass('error');
					}

					if(reply.data.thisIsCurrentUser){
						$('#yourUsername').html(reply.data.user_first_name);
					}
				}
				else{
					//showErrorMessage(reply.message,'#modalmessage');
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error updating user:\n"+error,'#modalmessage');
			}
		});
		
  	});
</script>