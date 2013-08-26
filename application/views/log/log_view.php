<?php
	echo
		'<div class="row-fluid">'
		,'<div class="text-align-left">'
		,heading('<i class="icon-time"></i> The Log', 2)
		,'<div class="well">'
		,form_open('log/viewLog','class="form-inline"')
		,'<center>'.form_label('Per Page', '').'&nbsp;'
		,form_dropdown('perPage',array(
			25 	=> 25,
			50 	=> 50,
			100 => 100,
			500 => 500
		),$perPage)
		,'&nbsp;'.form_button(array('type' => 'submit', 'name' => 'Go', 'value' => 'Go', 'content' => 'Go' , 'class' => 'btn btn-primary'))
		,form_close()
		,$links.'</center>'
		,'<table class="table table-striped table-bordered table-condensed">'
		,'	<tr class="tableTitle">
			<th>Date</th>
			<th>User</th>
			<th>Class</th>
			<th>Method</th>
			<th>Asset</th>
			<th>Attribute</th>
			<th>Data Name</th>
			<th>Data From</th>
			<th>Data To</th>
			<th class="log-desc hidden-phone hidden-tablet">Details</th>
			</tr>';
	$count = 0;
	foreach ($logData as $row){
		$count++;
		$descTable = "";
		if(NULL !== $decodedDesc = json_decode($row['description'],true)){
			$descTable = "<button type='button' class='btn' data-toggle='collapse' data-target='#desc".$count."' id='desc".$count."Btn'>Show Details</button>";
			$descTable .= "<div id='desc".$count."' class='collapse descCollapse'><table style=\"table table-striped table-bordered table-condensed\">";
			foreach($decodedDesc as $attr=>$value){
				$descTable .= "<tr><td>".wordwrap($attr,20,"<br />",true)."</td><td>".wordwrap(print_r($value,true),20,"<br />",true)."</td></tr>";
			}
			$descTable .= "</table></div>";
		
		}
		//$descTable .= $row['description'];
		
		$historyLink="";
		if(is_numeric($row['asset_id'])){
			$historyLink = '('.anchor('asset/history/'.$row['asset_id'],'History').')';
		}

		echo'
			<tr>
			<td>'.anchor('log/viewEntry/'.$row['log_id'],date('m/d/y H:i',strtotime($row['date']))).'</td>
			<td>'.$row['user_first_name'].' '.$row['user_last_name'].'</td>
			<td>'.$row['class'].'</td>
			<td>'.$row['method'].'</td>
			<td>'.anchor('asset/edit/'.$row['asset_id'],$row['asset_name']." ").$historyLink.'</td>
			<td>'.$row['attribute_name'].'</td>
			<td>'.$row['data_name'].'</td>
			<td>'.$row['data_from'].'</td>
			<td>'.$row['data_to'].'</td>
			<td class="hidden-phone hidden-tablet">'.$descTable."</td>
			</tr>\r\n";
	}
	echo 
		'</table>'
		,'<center>'.$links.'</center>'
		,'</div></div></div>';
?>
<script>
	$('.descCollapse').on('hidden', function(){
   		$('#'+$(this).attr('id')+'Btn').html('Show Details');
    })
    $('.descCollapse').on('shown', function(){
   		$('#'+$(this).attr('id')+'Btn').html('Hide Details');
    })
</script>