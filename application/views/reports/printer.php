<?php
$excel_form_attributes = array(
		'id' 		=> 'excelForm'
		,'class'	=> 'form-inline'
		,'style' 	=> 'padding:0px;margin:0px;'
	);
$exportJSON=array(array('Name','Location','Pg Count','Pg per Day','Pg per Month'));
foreach($tables as $table){
	$exportJSON[] = array(
		$table['asset_name']
		,$table['asset_location']
		,number_format($table['usage'])
		,$table['pg_per_day']
		,$table['pg_per_month']
	);
	
	$i=0;
	foreach($table['page_counts'] as $date=>$count){
		$i++;
		$exportJSON[] = array('','count '.$i.':', $date,number_format($count['count']));
	}
}
echo
	'<div class="row-fluid">'
	,'<div class="text-align-left span12">'
	,heading(form_open('reports/printerPageCountExcel',$excel_form_attributes).'<i class="icon-print"></i> Printer / Page Count &nbsp;'.form_hidden('excelDataJSON',json_encode($exportJSON)).form_button(array('type' => 'submit', 'name' => 'Excel', 'value' => 'Excel', 'content' => '<i class="icon-download"></i> Download Excel' , 'class' => 'btn btn-success', 'style' => 'margin-top:-7px;')).form_close(), 2)
	,'<div class="well">'
	,'<table class="table table-bordered table-condensed"><tr class="tableTitle"><th>Printer Name</th><th>Printer Location</th><th>Usage</th><th style="width:50px;">Days</th><th style="width:50px;">PPM</th><th style="width:200px;">Data</th></tr>';

$rowcount = 0;
foreach($tables as $id => $table){
	$rowcount++;
	if(isset($error[$id])){
		echo
			'<tr style="background:#EE6363;">'
			,'<td>',anchor('asset/edit/'.$id, $table['asset_name']),'</td>'
			,'<td colspan="5">'
			,$error[$id]
			,'</td></tr>';
	}else{
		echo 
			'<tr>'
			,'<td>',anchor('asset/edit/'.$id,$table['asset_name']),'</td>'
			,'<td>',$table['asset_location'],'</td>'
			,'<td>',number_format($table['usage']),'</td>'
			,'<td>',$table['pg_per_day'],'</td>'
			,'<td>',$table['pg_per_month'],'</td>'
		;
			


		$descTable = "<button type='button' class='btn' data-toggle='collapse' data-target='#reading".$rowcount."'>Readings</button>";
		$descTable .= "<div id='reading".$rowcount."' class='collapse'><table class='table table-striped table-bordered table-condensed'>\r\n";
		foreach($table['page_counts'] as $date=>$count){
			$descTable .= "<tr><td>{$date}</td><td>".number_format($count['count'])."</td></tr>\r\n";
		}
		$descTable .= "</table></div>";
		
		echo '<td>'.$descTable.'</td>';
		echo '</tr>';
	}
}
echo '</table>';
echo '</div></div></div>';
?>