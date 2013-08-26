<?php
	//echo 'welcome to step1.'.anchor('installer/2',' Continue to step 2?');
	$databaseFormAttributes=array(
		 'id' 		=> 'databaseForm'
		,'onsubmit' => 'asmSubmitDatabaseInfo(); return false;'
	);

	echo
		'<div class="text-align-left span8 css-centered">'
		,heading('<i class="icon-table"></i> Database Info', 2)
		,'<div class="well">'
			,form_open('',$databaseFormAttributes)
				,'<p></p>'
				,'<div wrapping="database_server" class="control-group">'
		 			,form_label('Database Server <span errorMessageFor="database_server"></span>','database_server',array('class' => 'control-label'))
		 			,form_input(array('id' => 'database_server','name' => 'database_server' ,'originalValue' => '', 'class' => 'input-block-level'))
		 		,'</div>'
		 		,'<div wrapping="database_name" class="control-group">'
		 			,form_label('Database Name <span errorMessageFor="database_name"></span>','database_name',array('class' => 'control-label'))
		 			,form_input(array('id' => 'database_name' ,'name' => 'database_name' ,'originalValue' => '', 'class' => 'input-block-level'))
		 		,'</div>'
		 		,'<div wrapping="database_user" class="control-group">'
		 			,form_label('Database User <span errorMessageFor="database_user"></span>','database_user',array('class' => 'control-label'))
		 			,form_input(array('id' => 'database_user' ,'name' => 'database_user' ,'originalValue' => '', 'class' => 'input-block-level'))
		 		,'</div>'
		 		,'<div wrapping="database_password" class="control-group">'
		 			,form_label('Database Passsword <span errorMessageFor="database_password"></span>','database_password',array('class' => 'control-label'))
		 			,form_input(array('id' => 'database_password' ,'name' => 'database_password' ,'originalValue' => '', 'class' => 'input-block-level'))
		 		,'</div>'
		 		,'<div class="control-group">'
					,form_button(array('id' => 'databaseFormSubmit', 'type' => 'submit', 'name' => 'Test Connection', 'value' => 'Test Connection', 'content' => 'Test Connection' , 'class' => 'btn disabled btn-large btn-block'))
				,'</div>'
	 		,form_close()
		,'</div>';
		$this->load->view('utility/initFormOnChangeActivate.php',array(
			'formID' 		=> 'databaseForm'
			,'submitButtonID' => 'databaseFormSubmit'
		));
?>
<script>
	function asmSubmitDatabaseInfo(){
		if(!asmCheckChangesWith_databaseForm()){
			return false;
		}

		$.ajax({
			url:"<?php echo $this->config->item('base_url'); ?>index.php/installer/apiCheckDatabaseSettings"
			,type:'post'
			,data:$('#databaseForm').serializeArray()
			,dataType:"json"
			,timeout:3000
			,success:function(reply){ //html request succeed?
				if(reply.success){
					showHappyMessage(reply.message);
				}
				setFormErrorColors(reply.failed_fields);
			}
			,error:function(obj,error){
				showErrorMessage("Error submitting database info:\n"+error);
			}
		});
	} 
</script>