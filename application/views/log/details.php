<?php
echo 
		'<div class="row-fluid">'
		,'<div class="text-align-left span12">'
		,heading('Showing Details for log entry #'.$logData['log_id'], 2)
		,'<div class="well">';

echo '<table class="table table-striped table-bordered table-condensed"><tr><th>Name</th><th>Value</th></tr>';
$highlight=false;
foreach($logData as $name=>$value)
{
	if($highlight){
		echo'<tr>';
	}else{
		echo'<tr class="highlight">';
	}
	$highlight=!$highlight;
	if($name == 'description'){
		if(NULL !== $decodedDesc = json_decode($value,true)){
			$descTable = "<table>\r\n";
			foreach($decodedDesc as $descAttr=>$descValue){
				$descTable .= "<tr><td>{$descAttr}</td><td>".print_r($descValue,true)."</td></tr>\r\n";
			}
			$descTable .= "</table>";
			echo'<td>details</td><td>'.$descTable.'</td></tr>';
		}else{
			echo'<td>'.$name.'</td><td>'.$value.'</td></tr>';
		}
	}else{
		echo'<td>'.$name.'</td><td>'.$value.'</td></tr>';
	}
	
}
echo '</table></div></div></div>';


?>