<?php 

/*
=============================
THIS FILE IS OLD - DO NOT USE
=============================
*/

/*
echo form_open('asset/searchByType');

echo 
	'<table id="typetable">',
	'<tr class="tableTitle"><th colspan="3">Search by Type</th></tr>',
	'<tr class="tableTitle"><th colspan="3">Type</td></tr>',
	'<tr><td style="width:180px;" colspan="3">'.form_dropdown('searchParams[type_id][]',$all_types).'</td></tr>',
	'</table>';

//echo '<table style="margin-top:-1px"><tr><td style="width:180px;border-right:0px;"></td><td style="width:200px; padding-top:0px; text-align:center; border-left:0px; border-right:0px;">(Use * for wildcard)</td><td style="width:120px; border-left:0px;"></td></tr></table>';
echo '<table style="margin-top:-1px"><tr><td style="text-align:left;border-right:0px;">'.form_button('Add another Attribute','Add another Type','onClick="asm_addType();"').'</td>';
echo '<td style="text-align:right;border-left:0px;">'.form_submit('Search','Search').'</td></tr></table>';
echo form_close();

$asm_typeDropdown = '<select name="searchParams[type_id][]">';
foreach($all_types as $id => $value)
{
	$asm_typeDropdown .= '<option value="'.$id.'">'.$value.'</option>';
}
$asm_typeDropdown .='</select>';
*/
?>
<!--
<script type="text/javascript">

var asm_typeRowCount = 0;
var asm_typeSerialEntries = {};
var asm_domTypeTable = document.getElementById('typetable');

function asm_addType(){
	var newRow1 = asm_domTypeTable.insertRow(asm_domTypeTable.rows.length)
		,newCell;
	
	newCell = newRow1.insertCell(0);
	newCell.className = 'width180';
	newCell.innerHTML = '<?php echo $asm_typeDropdown; ?>';
	
	newCell=newRow1.insertCell(1);
	newCell.className = 'width200';
	newCell.innerHTML = ''
	
	newCell=newRow1.insertCell(2);
	newCell.className = 'width120';
	newCell.innerHTML = '<a href="#" onClick="asm_removeType('+ asm_typeRowCount +');return false;" />[Remove]</a>';
	
	asm_typeSerialEntries[asm_typeRowCount] = [newRow1];
	asm_typeRowCount++;
}

function asm_removeType(serialEntry){

asm_typeSerialEntries[serialEntry][0].parentNode.removeChild(asm_typeSerialEntries[serialEntry][0]);
//asm_typeSerialEntries[serialEntry][1].parentNode.removeChild(asm_typeSerialEntries[serialEntry][1]);

delete asm_typeSerialEntries[serialEntry][0];
//delete asm_typeSerialEntries[serialEntry][1];
}
</script>
-->
