<?php
$this->benchmark->mark('code_start'); ?>
<!DOCTYPE html>
<html> 
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=10; IE=9; IE=8; IE=7; IE=EDGE" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>
	<?php 
		if(isset($installerMode)){
			echo 'PHP Simple Asset Manager Install';
		}else{
			if($this->config->item('websiteTitle')!==null){
			 	echo $this->config->item('websiteTitle');
			}else{
			echo '[No Title]';
			}
			$i=1;
			while($this->uri->segment($i)!==false && $this->uri->segment($i)!="index"){
				echo ' | '.ucwords($this->uri->segment($i));
				$i++;
			}
		}
		
	?>
	</title>

	<?php
	$session_data = $this->session->userdata('logged_in');
	if($session_data['user_id']!==null){
		$theme=$this->user_model->getThemeOfUser($session_data['user_id']);
		if($theme!=null && file_exists('css/themes/'.$theme.'.css')){
			echo '<link class="changeMe" rel="stylesheet" href="'.$this->config->item('base_url').'css/themes/'.$theme.'.css">';
		}else{
			echo '<link class="changeMe" rel="stylesheet" href="'.$this->config->item('base_url').'css/system/bootstrap.min.css">';
		}
	}else{
		echo '<link class="changeMe" rel="stylesheet" href="'.$this->config->item('base_url').'css/system/bootstrap.min.css">';
	}
	?>
	<link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>css/system/bootstrap-responsive.css">
	<link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>css/system/font-awesome.min.css">
	<!--<link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>css/select2.css">-->
	<!--<link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>css/select2-bootstrap.css">-->

	<!--[if IE 7]>
		<link rel="stylesheet" href="<?php echo $this->config->item('base_url'); ?>css/font-awesome-ie7.min.css">
	<![endif]-->
	<link rel="apple-touch-icon" href="<?php echo $this->config->item('base_url'); ?>img/icon.png">

	<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>js/jquery-ui.js"></script>
	<!--<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>js/expand.js"></script>-->
	<!--<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>js/jquery.caret.js"></script>-->
	<!--<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>js/jquery.ipaddress.js"></script>-->
	<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>js/bootstrap-commented690.js"></script>
	<!--<script type="text/javascript" src="<?php echo $this->config->item('base_url'); ?>js/select2.min.js"></script>-->
	
	<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]> 
      <script src="<?php echo $this->config->item('base_url'); ?>js/html5shiv.js"></script>
    <![endif]-->
	

	<style>
	
		body {
			/*background-color: #f5f5f5;*/
			overflow-y: scroll;
		}
		p{
			margin:0px;
		}
		.redbg{
			background-color:red;
		}
		.text-align-left{
			text-align:left !important;
		}
		.text-align-center{
			text-align:center !important;
		}
		.text-align-right{
			text-align:right !important;
		}
		.css-centered{
			float:none !important;
			margin-left:auto !important;  
			margin-right:auto !important;
		}
		.jac-icon{
			float:left;
			position:absolute;
			right:60px;
			top:-20px;
		}
		.jackLeft{
			background: url(<?php echo $this->config->item('base_url'); ?>img/jackleft.png);
			width:100px;
			height:100px;
		}
		.jackRight{
			background: url(<?php echo $this->config->item('base_url'); ?>img/jackright.png);
		}
		.pulse{
			border-color: red;
			box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px red;
			outline: 0 none;
		}
		.login-box{
			opacity:0.8;
			filter:alpha(opacity=80);
		}
		.login-box input[name="user_email"],
		.login-box input[name="user_password"] {
			font-size: 16px;
			height: auto;
			margin-bottom: 15px;
			padding: 7px 9px;
		}
		.extra-padding-right{
			margin-right:5px;
		}
		.bordered {
			background-color: #fff;
			border: 1px solid #e5e5e5;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
			-webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			-moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
			box-shadow: 0 1px 2px rgba(0,0,0,.05);
			margin-bottom: 10px;
		}
		.borderedContent{
			margin:19px;
		}
	
		.invisable{
			visibility:hidden;
		}
		.notify-box p{
			text-align:center;
			width:90%;
		}
		
		.ip_container {
			border: 1px inset #999;
			background:#ffffff;
		}
		.ip_octet {
			border: 0;
			text-align: center;
			width: 2em;
		}
		.ip_cidr {
			width: 1.5em;
		}

		.ui-helper-hidden-accessible {
    		border: 0 none;
    		clip: rect(0px, 0px, 0px, 0px);
    		height: 1px;
    		margin: -1px;
    		overflow: hidden;
    		padding: 0;
    		position: absolute;
    		width: 1px;
		}

		.searchResultDropdown{
			max-height:300px;
			overflow-y: scroll;
		}
       
        input{
        	margin-bottom:0px !important;
        }
		
		.modal{
			top:50px !important;
		}

	</style>
	<script type="text/javascript">
		var screenHeight = $(window).height();
		document.write('<style>.modal-body{max-height:'+(screenHeight-250)+'px !important;}</style>');

		function showErrorMessage(messageContent,messageBoxId){	
			var boxId = messageBoxId || false;
			if(boxId === false){
				var box = $('#message');
				var closeParam = '#message';
				$('html, body').animate({scrollTop:0}, 'slow');
			}else{
				var box = $(messageBoxId);
				var closeParam = messageBoxId;
				
			}
			box.hide().html('<div class="alert alert-error chaserLights" ><button type="button" class="close" onclick="closeMessage(\''+closeParam+'\')">&times;</button>'+messageContent+'</div>').slideDown('fast');
			$(".alert-error").pulse("","pulse", 300);
		}
		function showHappyMessage(messageContent,messageBoxId){	
			var boxId = messageBoxId || false;
			if(boxId === false){
				var box = $('#message');
				var closeParam = '#message';
				$('html, body').animate({scrollTop:0}, 'slow');
			}else{
				var box = $(messageBoxId);
				var closeParam = messageBoxId;
			}
			box.hide().html('<div class="alert alert-success chaserLights" ><button type="button" class="close" onclick="closeMessage(\''+closeParam+'\')">&times;</button>'+messageContent+'</div>').slideDown('fast');
			
		}
		function closeMessage(messageBoxId){
			var boxId = messageBoxId || false;
			if(boxId === false){
				var box = $('#message');
			}else{
				var box = $(messageBoxId);
			}
			box.hide();
		}
		function setFormErrorColors(failed_fields){
			$('div[wrapping]').removeClass('error'); //clear any errors
			$('span[errorMessageFor]').html('');
			var otherErrors=false;
			var theErrors="";
			if(failed_fields && failed_fields.length>0){
				closeMessage(); //close any happy/error message
			}
			for (var name in failed_fields){
				if($('div[wrapping="'+name+'"]').length == 0){ //This element is not on the page!
					otherErrors=true;
					theErrors+="<br />"+name+" ("+failed_fields[name]+")";
				}
				$('div[wrapping="'+name+'"]').addClass('error');
				$('span[errorMessageFor="'+name+'"]').html(' - '+failed_fields[name]);
			}
			if(otherErrors){
				showErrorMessage("There are hidden errors on this form"+theErrors);
			}
			
		}
		

		//PULSE THING
				
		jQuery.fn.pulse = function( beforeproperties, afterproperties, duration, repeat) {  
		   
		   if (duration === undefined || duration < 0) duration = 500;

		   return this.each(function() {
		      var $this = jQuery(this);
			  var beforeAnimComplete = true;
			  var afterAnimComplete = false;
			  var count = 0;
			  var intervalID = 0;
			  
			  intervalID = setInterval( 
				function startTimer(){
					if(!beforeAnimComplete){
						$this.toggleClass(afterproperties);
						beforeAnimComplete = true;
						afterAnimComplete = false;
					}else{
						$this.toggleClass(afterproperties);
						beforeAnimComplete = false;
						afterAnimComplete = true;
					}
					count++;
					if (repeat != undefined && count >= repeat){
						clearInterval(intervalID);
						count = 0;
					}	
				}, duration );
				$this.data("intervalID", intervalID);
		   });
		};

		jQuery.fn.stopPulse = function(){
			var $this = jQuery(this);
			var intervalID = $this.data("intervalID");
			if(intervalID != undefined)
				clearInterval(intervalID);
		}	
	</script>
</head>
<body>
	<center>
			<?php
				
				$this->load->view('templates/links');
				

				if(isset($fluid)){
					echo '<div class="container-fluid" style="margin-top:10px;">';
				}else{
					echo '<div class="container" style="margin-top:10px;">';
				}

				if(isset($error_message) && $error_message!=''){
					//echo '<table class="error_message"><tr><td id="icon"><img src="'.$this->config->item('base_url').'css/exclamation.png" /></td><td id="messageText">'.$error_message.'</td></tr></table>';
					//echo'<div class="error_message">'.$error_message.'</div>';
					echo '<div id="message" class="notify-box css-centered"><div class="alert alert-error chaserLights" ><button type="button" class="close" data-dismiss="alert">&times;</button>'.$error_message.'</div></div>';
				}
				if(isset($happy_message) && $happy_message!=''){
					//echo '<table class="happy_message"><tr><td id="icon"><img src="'.$this->config->item('base_url').'css/lightbulb.png" /></td><td id="messageText">'.$happy_message.'</td></tr></table>';
					//echo'<div class="happy_message">'.$happy_message.'</div>';
					echo '<div id="message" class="notify-box css-centered"><div class="alert alert-success chaserLights" ><button type="button" class="close" data-dismiss="alert">&times;</button>'.$happy_message.'</div></div>';
				}
				echo '<div id="message" class="notify-box cssCentered"></div>';	
				
			?>
				
				 
