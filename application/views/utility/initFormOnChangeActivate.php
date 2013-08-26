<?php
$thereIsAnError="";
if(!isset($submitButtonID)){
	$thereIsAnError.="submitButtonID was not loaded for this form.<br />";
}
if(!isset($formID)){
	$thereIsAnError.="formID was not loaded for this form.<br />";
}
if($thereIsAnError!=""){
	echo '<script>showErrorMessage("'.$thereIsAnError.'");</script>';
}
else{
	if(!isset($activeButtonClass)){
		$activeButtonClass="btn-primary";
	}

	if(!isset($disabledButtonClass)){
		$disabledButtonClass="disabled";
	}
	?>
	<script>
		function asmCheckChangesWith_<?php echo $formID; ?>(){
			var changed = false;
			//console.log('Checking for changes on <?php echo $formID; ?>!');
	   		$("#<?php echo $formID; ?> :input").each(function(){
	        	if (typeof $(this).attr('originalValue') !== 'undefined' && $(this).attr('originalValue') !== false){
		        	//console.log('"'+$(this).val()+'" - "'+$(this).attr('originalValue')+'"');
		        	if($(this).is(":checkbox")){
		        		if($(this).prop('checked') !== ($(this).attr('originalValue')==1)){
		        			changed=true;
		        		}
		        	}else{
		        		if($(this).val() != $(this).attr('originalValue')){
		        			changed=true;
		        		}
		        	} 
	        	}
	   		});
	   		if(changed){
	   			$("#<?php echo $submitButtonID; ?>").addClass("<?php echo $activeButtonClass; ?>");
				$("#<?php echo $submitButtonID; ?>").removeClass("<?php echo $disabledButtonClass; ?>");
				return true;
	   		}else{
	   			$("#<?php echo $submitButtonID; ?>").removeClass("<?php echo $activeButtonClass; ?>");
				$("#<?php echo $submitButtonID; ?>").addClass("<?php echo $disabledButtonClass; ?>");
				return false;
	   		}
		}

		function asmInitChangeListenerFor_<?php echo $formID; ?>(){
			//console.log('Init Change Listner on <?php echo $formID; ?>!');
			$("#<?php echo $formID; ?> :input").on({
				'keydown input':function(){
			   		asmCheckChangesWith_<?php echo $formID; ?>();	
		   		}
		   		,change:function(){
		   			asmCheckChangesWith_<?php echo $formID; ?>();
		   		}
			});
		}

		asmInitChangeListenerFor_<?php echo $formID; ?>();
	</script>
<?php } ?>