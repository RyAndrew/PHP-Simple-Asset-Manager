<?php  

echo 
	'<div class="text-align-left span8">'
	,heading('<i class="icon-search"></i> Advanced Search', 2)
	,'<div class="well">'
	,form_open('asset/searchByNameTypeAttribute')
	,'<div wrapping="searchParams[asset_name]" class="control-group">'
		,form_label('By Name <span errorMessageFor="searchParams[asset_name]"></span>','asset_name',array('class' => 'control-label'))
		,'<div class="controls">'.form_input(array('name' => 'searchParams[asset_name]', 'id' => 'asset_name', 'class' => 'input-block-level' ,'maxlength' =>30)).'</div>'
	,'</div>'
	,'<div class="control-group">'
		,form_label('By Type','',array('class' => 'control-label'))
		,'<table id="typetable" class="table table-striped table-bordered table-condensed">'
		,'<tr><th>Type</th> <th>Remove</th></tr>'
		,'<tr><td>'.form_dropdown('searchParams[type_id][]',$all_types,'','class="input-block-level"').'</td><td class="text-align-center span1"><i class="icon-remove icon-2x invisable" ></i></td></tr>'
		,'</table>'
		,form_button('Add another Attribute','Add another Type','onClick="asm_addType();" class="btn btn-block"')
	,'</div>'
	,form_label('By Attribute')
	,'<table id="attributeTable" class="table table-striped table-bordered table-condensed">'
	,'<tr><th>Attribute</th><th>Value (Use * for wildcard)</th><th>Remove</th></tr>'
	,'<tr><td>'.form_dropdown('searchParams[attribute_id][]',$all_attributes,'','class="input-block-level"').'</td><td>'.form_input(array('name' => 'searchParams[attribute_value][]','class' => 'input-block-level' ,'maxlength' =>30)).'</td><td class="text-align-center span1"><i class="icon-remove icon-2x invisable" ></i></td></tr>'
	,'</table>'
	,form_button('Add another Attribute','Add another Attribute','onClick="asm_addAttribute();" class="btn btn-block"')
	,form_label('','')
	,form_button(array('type' => 'submit', 'name' => 'Search', 'value' => 'Search', 'content' => 'Search' , 'class' => 'btn btn-primary btn-large btn-block'))
	,form_close()
	,'</div></div></div>';

$asm_typeDropdown = '<select name="searchParams[type_id][]" class="input-block-level">';
foreach($all_types as $id => $value)
{
	$asm_typeDropdown .= '<option value="'.$id.'">'.$value.'</option>';
}
$asm_typeDropdown .='</select>';

$asm_attributeDropdown = '<select name="searchParams[attribute_id][]" class="input-block-level">';
foreach($all_attributes as $id => $value)
{
	$asm_attributeDropdown .= '<option value="'.$id.'">'.$value.'</option>';
}
$asm_attributeDropdown .='</select>';

?>
<script type="text/javascript">

var asm_typeRowCount = 0;
var asm_typeSerialEntries = {};
var asm_domTypeTable = document.getElementById('typetable');

function asm_addType(){
	var newRow1 = asm_domTypeTable.insertRow(asm_domTypeTable.rows.length)
		,newCell;
	
	newCell = newRow1.insertCell(0);
	//newCell.className = 'width180';
	newCell.innerHTML = '<?php echo $asm_typeDropdown; ?>';
	
	//newCell=newRow1.insertCell(1);
	//newCell.className = 'width200';
	//newCell.innerHTML = ''
	
	newCell=newRow1.insertCell(1);
	newCell.className = 'text-align-center span1';
	newCell.innerHTML = '<a href="#" onClick="asm_removeType('+ asm_typeRowCount +');return false;" style="text-decoration:none;"/><i class="icon-remove icon-2x" ></i></a>';
	
	asm_typeSerialEntries[asm_typeRowCount] = [newRow1];
	asm_typeRowCount++;
}

function asm_removeType(serialEntry){

asm_typeSerialEntries[serialEntry][0].parentNode.removeChild(asm_typeSerialEntries[serialEntry][0]);
//asm_typeSerialEntries[serialEntry][1].parentNode.removeChild(asm_typeSerialEntries[serialEntry][1]);

delete asm_typeSerialEntries[serialEntry][0];
//delete asm_typeSerialEntries[serialEntry][1];
}



var asm_attributeRowCount = 0;
var asm_attributeSerialEntries = {};
var asm_domAttributeObj = document.getElementById('attributeTable');


function asm_addAttribute(){
	var newRow1 = asm_domAttributeObj.insertRow(asm_domAttributeObj.rows.length)
		,newCell;
	
	newCell = newRow1.insertCell(0);
	//newCell.className = 'width180';
	newCell.innerHTML = '<?php echo $asm_attributeDropdown; ?>';
	
	newCell=newRow1.insertCell(1);
	//newCell.className = 'width200';
	newCell.innerHTML = '<input type="text" class="input-block-level" name="searchParams[attribute_value][]">'
	
	newCell=newRow1.insertCell(2);
	newCell.className = 'text-align-center span1';
	newCell.innerHTML = '<a href="#" onClick="asm_removeAttribute('+ asm_attributeRowCount +');return false;" style="text-decoration:none;"/><i class="icon-remove icon-2x" ></i></a>';
	
	asm_attributeSerialEntries[asm_attributeRowCount] = [newRow1];
	asm_attributeRowCount++;
}

function asm_removeAttribute(serialEntry){
//console.log('Calling asm_removeAttribute ('+serialEntry+')');
asm_attributeSerialEntries[serialEntry][0].parentNode.removeChild(asm_attributeSerialEntries[serialEntry][0]);
//asm_attributeSerialEntries[serialEntry][1].parentNode.removeChild(asm_attributeSerialEntries[serialEntry][1]);

delete asm_attributeSerialEntries[serialEntry][0];
//delete asm_attributeSerialEntries[serialEntry][1];
}



</script>
