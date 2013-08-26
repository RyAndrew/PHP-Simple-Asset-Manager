<?php

/*
=============================
THIS FILE IS OLD - DO NOT USE
=============================
*/
  
/*
echo form_open('asset/searchByAttribute');

echo '<table id="attributeTable">';
echo '<tr class="tableTitle"><th colspan="3">Search by Attribute</th></tr>';
echo '<tr class="tableTitle"><th>Attribute</td><th colspan="2">Value</th></tr>';
echo '<tr><td style="width:180px;">'.form_dropdown('searchParams[attribute_id][]',$all_attributes).'</td><td style="width:200px;">'.form_input('searchParams[attribute_value][]','').'</td><td style="width:120px;"></td></tr>';
echo '</table>';

echo '<table style="margin-top:-1px"><tr><td style="width:180px;border-right:0px;"></td><td style="width:200px; padding-top:0px; text-align:center; border-left:0px; border-right:0px;">(Use * for wildcard)</td><td style="width:120px; border-left:0px;"></td></tr></table>';
echo '<table style="margin-top:-1px"><tr><td style="text-align:left;border-right:0px;">'.form_button('Add another Attribute','Add another Attribute','onClick="asm_addAttribute();"').'</td>';
echo '<td style="text-align:right;border-left:0px;">'.form_submit('Search','Search').'</td></tr></table>';
echo form_close();

$asm_attributeDropdown = '<select name="searchParams[attribute_id][]">';
foreach($all_attributes as $id => $value)
{
	$asm_attributeDropdown .= '<option value="'.$id.'">'.$value.'</option>';
}
$asm_attributeDropdown .='</select>';
*/
?>
<!--
<script type="text/javascript">

var asm_attributeRowCount = 0;
var asm_attributeSerialEntries = {};
var asm_domAttributeObj = document.getElementById('attributeTable');


function asm_addAttribute(){
	var newRow1 = asm_domAttributeObj.insertRow(asm_domAttributeObj.rows.length)
		,newCell;
	
	newCell = newRow1.insertCell(0);
	newCell.className = 'width180';
	newCell.innerHTML = '<?php echo $asm_attributeDropdown; ?>';
	
	newCell=newRow1.insertCell(1);
	newCell.className = 'width200';
	newCell.innerHTML = '<INPUT TYPE="TEXT" name="searchParams[attribute_value][]">'
	
	newCell=newRow1.insertCell(2);
	newCell.className = 'width120';
	newCell.innerHTML = '<a href="#" onClick="asm_removeAttribute('+ asm_attributeRowCount +');return false;" />[Remove]</a>';
	
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
-->
