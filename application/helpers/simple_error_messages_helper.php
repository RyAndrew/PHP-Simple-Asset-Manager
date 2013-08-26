<?php
	function loadSimpleErrorMessages($obj){
		$obj->form_validation->set_message('required','Required');
		$obj->form_validation->set_message('valid_email','Not valid email');
		$obj->form_validation->set_message('is_natural','Must be positive number');
		$obj->form_validation->set_message('min_length','Min %2$s chars');
		$obj->form_validation->set_message('max_length','Max %2$s chars');
		$obj->form_validation->set_message('greater_than','Must be > %2$s ');
		$obj->form_validation->set_message('less_than','Must be < %2$s ');
		$obj->form_validation->set_message('matches','Doesn\'t match!');
		$obj->form_validation->set_message('is_unique','Already in the system');
		$obj->form_validation->set_message('valid_ip','Not a valid IP');
	}
?>