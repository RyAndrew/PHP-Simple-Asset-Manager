<?php

$arrayToSend = array();

if(isset($data)){
	$arrayToSend['data'] = $data;
}


if(isset($failed_fields)){
	$arrayToSend['failed_fields'] = $failed_fields;
}

$message ="=No Message=";
$arrayToSend['success'] = false;

if(isset($error_message)){
	$arrayToSend['message'] = $error_message;
	$arrayToSend['success'] = false;
}else if(isset($happy_message)){
	$arrayToSend['message'] = $happy_message;
	$arrayToSend['success'] = true;
}

echo(json_encode($arrayToSend));
?>