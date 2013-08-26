<?php  
echo
	'<div class="row-fluid">'
	,'<div class="text-align-left span5">'
	,heading('<i class="icon-plus-sign"></i> Create Attribute', 2)
	,'<div class="well">'
	,form_open('attribute/create')
	,heading('Attribute Name', 4)
	,form_input(array('name' => 'new_attribute_name', 'value' => set_value('new_attribute_name') , 'class' => 'input-block-level'))
	,form_button(array('type' => 'submit', 'name' => 'Create', 'value' => 'Create', 'content' => 'Create' , 'class' => 'btn btn-primary btn-large btn-block'))
	,form_close()
	,'</div></div>';


$form_attributes = array(
	'id' => 'attributeForm',
	'onsubmit' => 'confirmSubmit(); return false;'
);

echo 
	'<div class="text-align-left span7">'
	,heading('<i class="icon-edit-sign"></i> Edit Attributes', 2)
	,'<div class="well">'
	,form_open('attribute/edit',$form_attributes)
	,'<table class="table table-striped table-bordered table-condensed">'
	,'<tr class="tableTitle"><th>Attribute#</th><th>Attribute Name</th><th>Delete?</th></tr>';
	
$lineNum=0;
foreach($all_attributes as $attribute_id => $attribute_name){
	$lineNum++;
	echo'<tr><td class="lineNum">'.$lineNum.'</td><td>'.form_input(array('name' => 'attribute_name['.$attribute_id.']', 'value' => set_value('attribute_name['.$attribute_id.']',$attribute_name) , 'class' => 'input-block-level')).'</td><td>'.form_checkbox('attribute_delete['.$attribute_id.']','',false,"class='attribute-delete'").'</td></tr>';
}	
echo
	'</table>',
	form_button(array('type' => 'submit', 'name' => 'Save Changes', 'value' => 'Save Changes', 'content' => 'Save Changes' , 'class' => 'btn btn-primary btn-large btn-block')),
	form_close(),
	'</div></div></div>';


$this->load->view('utility/javascriptConfirmDelete',array(
	'formName'=>'attributeForm'
	,'checkBoxClassName'=>'attribute-delete'
	,'message' => 'Are you sure you want to PERMANENTLY delete the selected attributes? \nRemoving an attribute will also remove the attribute on all assets and any attribute data assoicated with it!'
));

?>