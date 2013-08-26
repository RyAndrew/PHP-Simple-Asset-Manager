<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
 if(!isset($formName)){
 	$formName = 'form';
 }
 if(!isset($checkBoxClassName)){
 	$checkBoxClassName = 'form';
 }
 if(!isset($message)){
 	$message = 'Are you sure you want to delete the selected items?';
 }
 //$message=$message.'\n\n Enter "y" to continue:';
 
?>

<script>
function confirmSubmit()
{
	var allDeleteCheckboxes = $('.<?php echo $checkBoxClassName; ?>'),
		l = allDeleteCheckboxes.length,
		showConfirm = false;

	while(l--){
		if(allDeleteCheckboxes[l].checked == true){
			showConfirm = true;
		}
	}
	if(showConfirm){
		if(confirm('<?php echo $message; ?>')){
			document.getElementById('<?php echo $formName; ?>').submit();
		}else{
			return false;
		}
	}
	else
	{
		document.getElementById('<?php echo $formName; ?>').submit();
	}
}
</script>
