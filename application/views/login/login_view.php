<style>
	body{
		background: url(<?php echo $this->config->item('base_url'); ?>img/bg.jpg) no-repeat center center fixed;
        -webkit-background-size: cover;
           -moz-background-size: cover;
             -o-background-size: cover;
        		background-size: cover;
	}
</style>
<?php
	//remembers your username!
	$cookieEmail=get_cookie("PHPAssetManager-cookieEmail");
	if($cookieEmail!==false){
		$cookieEmail=json_decode($cookieEmail,true);
		$cookieEmail=$cookieEmail['email'];
	}else{
		$cookieEmail="";
	}
	   
	$loginAttributes=array(
		'id' 		=> 'loginForm'
		,'class' 	=> 'form-signin'
		,'onsubmit' => 'asm_login(); return false;'
		,'style' 	=> 'margin-bottom:0px;'
	);

	$registerAttributes=array(
		'id' 		=> 'registerForm'
		,'class' 	=> 'form-signin'
		,'onsubmit' => 'asm_register(); return false;'
	);
	$resetPasswordAttributes=array(
		'id' 		=> 'resetPasswordForm'
		,'class' 	=> 'form-signin'
		,'onsubmit' => 'asm_resetPassword(); return false;'
	);

	echo
		'<div class="row-fluid">'
			,'<div class="text-align-left span4 css-centered login-box">'
				,heading('<i class="icon-lock"></i> Please Login', 2,'style="color:white;"')
				,'<div class="well">'
					,'<div id="modalmessage" class="notify-box cssCentered"></div>'
					,form_open('',$loginAttributes)
					,'<div wrapping="user_email" class="control-group">'
						,form_label('Email <span errorMessageFor="user_email"></span>','user_email',array('class' => 'control-label'))
						,'<div class="controls">'.form_input(array('name' => 'user_email', 'value' => set_value('user_email',$cookieEmail),'originalValue' => set_value('user_email',$cookieEmail), 'class' => 'input-block-level', 'maxlength' =>30)).'</div>'
					,'</div>'
					,'<div wrapping="user_password" class="control-group">'
						,form_label('Password <span errorMessageFor="user_password"></span>','user_password',array('class' => 'control-label'))
						,'<div class="controls">'.form_password(array('name' => 'user_password', 'value' => set_value('user_password'),'originalValue' => set_value('user_password'), 'class' => 'input-block-level', 'maxlength' => 30)).'</div>'
					,'</div>'
					,form_submit(array('id' => 'loginFormSubmit','name' => 'Login','value' => 'Login','class' => 'btn btn-large disabled btn-block')).'<br />'
					,form_close()
					,'<div class="accordion" id="subLoginButtons" style="">';						
						if($this->config->item('allow_registering') == 1){
						echo
						'<div class="accordion-group">'
							,'<a class="accordion-toggle btn btn-block" data-toggle="collapse" data-parent="#subLoginButtons" href="#registerUser">Register for a new account</a>'
							,'<div id="registerUser" class="accordion-body collapse">'
								,'<div class="accordion-inner">'
									,form_open('',$registerAttributes)
									,'<div wrapping="register_user_first_name" class="control-group">'
										,form_label('First Name <span errorMessageFor="register_user_first_name"></span>','register_user_first_name',array('class' => 'control-label'))
										,'<div class="controls">'.form_input(array('name' => 'register_user_first_name','id' => 'register_user_first_name', 'value' => set_value('register_user_first_name'), 'originalValue' => set_value('register_user_first_name'), 'class' => 'input-block-level', 'maxlength' =>30)).'</div>'
									,'</div>'
									,'<div wrapping="register_user_last_name" class="control-group">'
										,form_label('Last Name <span errorMessageFor="register_user_last_name"></span>','register_user_last_name',array('class' => 'control-label'))
										,'<div class="controls">'.form_input(array('name' => 'register_user_last_name','id' => 'register_user_last_name', 'value' => set_value('register_user_last_name'), 'originalValue' => set_value('register_user_last_name'), 'class' => 'input-block-level', 'maxlength' =>30)).'</div>'
									,'</div>'
									,'<div wrapping="register_user_email" class="control-group">'
										,form_label('Email <span errorMessageFor="register_user_email"></span>','register_user_email',array('class' => 'control-label'))
										,'<div class="controls">'.form_input(array('name' => 'register_user_email','id' => 'register_user_email', 'value' => set_value('register_user_email'), 'originalValue' => set_value('register_user_email'), 'class' => 'input-block-level', 'maxlength' =>30)).'</div>'
									,'</div>'
									,'<div wrapping="register_user_email_repeat" class="control-group">'
										,form_label('Email (Again) <span errorMessageFor="register_user_email_repeat"></span>','register_user_email_repeat',array('class' => 'control-label'))
										,'<div class="controls">'.form_input(array('name' => 'register_user_email_repeat','id' => 'user_email_repeat', 'value' => set_value('register_user_email_repeat'),'originalValue' => set_value('register_user_email_repeat'), 'class' => 'input-block-level', 'maxlength' =>30)).'</div>'
									,'</div>'
									,'<div wrapping="register_user_password" class="control-group">'
										,form_label('Password <span errorMessageFor="register_user_password"></span>','register_user_password',array('class' => 'control-label'))
										,'<div class="controls">'.form_password(array('name' => 'register_user_password','id' => 'register_user_password', 'value' => set_value('register_user_password'),'originalValue' => set_value('register_user_password'), 'class' => 'input-block-level', 'maxlength' =>30)).'</div>'
									,'</div>'
									,'<div wrapping="register_user_password_repeat" class="control-group">'
										,form_label('Password (Again) <span errorMessageFor="register_user_password_repeat"></span>','register_user_password_repeat',array('class' => 'control-label'))
										,'<div class="controls">'.form_password(array('name' => 'register_user_password_repeat','id' => 'register_user_password_repeat', 'value' => set_value('register_user_password_repeat'), 'originalValue' => set_value('register_user_password_repeat'), 'class' => 'input-block-level', 'maxlength' =>30)).'</div>'
									,'</div>'
									,'<div class="control-group">'
										,'<div class="controls">'.form_submit(array('id' => 'registerFormSubmit','name' => 'Register','value' => 'Register','class' => 'btn btn-large disabled btn-block')).'</div>'
									,'</div>'
									,form_close()
								,'</div>'
							,'</div>'
						,'</div>';
						}
						echo
						'<div class="accordion-group">'
							,'<a class="accordion-toggle btn btn-block" data-toggle="collapse" data-parent="#subLoginButtons" href="#forgotPassword">Forgot your password?</a>'
							,'<div id="forgotPassword" class="accordion-body collapse">'
								,'<div class="accordion-inner">'
									,form_open('',$resetPasswordAttributes)
									,'<div wrapping="reset_email" class="control-group">'
										,form_label('Email <span errorMessageFor="reset_email"></span>','reset_email',array('class' => 'control-label'))
										,'<div class="controls">'.form_input(array('name' => 'reset_email','id' => 'reset_email', 'value' => set_value('reset_email'),'originalValue' => set_value('reset_email'), 'class' => 'input-block-level', 'maxlength' =>30)).'</div>'
									,'</div>'
									,'<div class="control-group">'
										,'<div class="controls">'.form_submit(array('id' => 'resetPasswordSubmit','name' => 'Reset Password','value' => 'Reset Password','class' => 'btn btn-large disabled btn-block')).'</div>'
									,'</div>'
									,form_close()
								,'</div>'
							,'</div>'
						,'</div>'
					,'</div>'
				,'</div>'
			,'</div>'
		,'</div>';
?>

<script type="text/JavaScript">
	
$("#loginForm :input").on({
		'keydown input':function(){
	   		checkChangesWithLoginForm();	
   		}
   		,change:function(){
   			checkChangesWithLoginForm();
   		}
	});

	function checkChangesWithLoginForm(){
		var changed = false;
   		$("#loginForm :input").each(function(){
        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
	        	if($(this).val() != $(this).attr('originalValue')){
	        		changed=true;
	        	}
        	}
   		});
   		if(changed){
   			$("#loginFormSubmit").addClass('btn-primary');
			$("#loginFormSubmit").removeClass('disabled');
			return true;
   		}else{
   			$("#loginFormSubmit").removeClass('btn-primary');
			$("#loginFormSubmit").addClass('disabled');
			return false;
   		}
	}

	function asm_login(){
		if(!checkChangesWithLoginForm()){
			return false;
		}

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/verifyLogin"
			,type:'post'
			,data:$('#loginForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					window.location.href = "<?php echo $this->config->item('base_url'); ?>";
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error logging in:\n"+error,'#modalmessage');
			}
		});
	}

	$("#registerForm :input").on({
		'keydown input':function(){
	   		checkChangesWithRegisterForm();	
   		}
   		,change:function(){
   			checkChangesWithRegisterForm();
   		}
	});

	function checkChangesWithRegisterForm(){
		var changed = false;
   		$("#registerForm :input").each(function(){
        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
	        	if($(this).val() != $(this).attr('originalValue')){
	        		changed=true;
	        	}
        	}
   		});
   		if(changed){
   			$("#registerFormSubmit").addClass('btn-primary');
			$("#registerFormSubmit").removeClass('disabled');
			return true;
   		}else{
   			$("#registerFormSubmit").removeClass('btn-primary');
			$("#registerFormSubmit").addClass('disabled');
			return false;
   		}
	}


	function asm_register(){
		if(!checkChangesWithRegisterForm()){
			return false;
		}

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/register"
			,type:'post'
			,data:$('#registerForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					
					$('input[name=register_user_first_name]').val('');
					$('input[name=register_user_last_name]').val('');
					$('input[name=register_user_email]').val('');
					$('input[name=register_user_email_repeat]').val('');
					$('input[name=register_user_password]').val('');
					$('input[name=register_user_password_repeat]').val('');
					
					checkChangesWithRegisterForm();

					showHappyMessage(reply.message,'#modalmessage');
					$('#registerUser').collapse('hide');
					$('input[name=user_email]').val(reply.data.user_email);
				}
				if(reply.data.adminLockedRegistering==1){
					showErrorMessage(reply.message,'#modalmessage');
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error Registering:\n"+error,'#modalmessage');
			}
		});
	}

	$("#resetPasswordForm :input").on({
		'keydown input':function(){
	   		checkChangesWithResetPasswordForm();	
   		}
   		,change:function(){
   			checkChangesWithResetPasswordForm();
   		}
	});

	function checkChangesWithResetPasswordForm(){
		var changed = false;
   		$("#resetPasswordForm :input").each(function(){
        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
	        	if($(this).val() != $(this).attr('originalValue')){
	        		changed=true;
	        	}
        	}
   		});
   		if(changed){
   			$("#resetPasswordSubmit").addClass('btn-primary');
			$("#resetPasswordSubmit").removeClass('disabled');
			return true;
   		}else{
   			$("#resetPasswordSubmit").removeClass('btn-primary');
			$("#resetPasswordSubmit").addClass('disabled');
			return false;
   		}
	}


	function asm_resetPassword(){
		if(!checkChangesWithResetPasswordForm()){
			return false;
		}
		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/login/apiResetPassword"
			,type:'post'
			,data:$('#resetPasswordForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){
				if(reply.success){
					
					$('input[name=reset_email]').val('');
					
					checkChangesWithResetPasswordForm();

					showHappyMessage(reply.message,'#modalmessage');
					$('#forgotPassword').collapse('hide');
					$('input[name=user_email]').val(reply.data.reset_email);
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error Resetting Password:\n"+error,'#modalmessage');
			}
		});
	}
</script>