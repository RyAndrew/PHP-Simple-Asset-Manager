<?php
	echo 
		'<div class="row-fluid">'
		,'<div class="text-align-left span6">'
		,heading('<i class="icon-info"></i> Recently Created', 2)
		,'<div class="well">'
		,'<table class="table table-striped table-bordered table-condensed"><tr><th class="span2">Date</th><th>Asset</th></tr>';

	foreach($recentassets as $recentasset){
		echo '<tr><td class="span2">'.anchor('asset/history/'.$recentasset['asset_id'],date('m/d/y H:i',strtotime($recentasset['date']))).'</td><td>'.anchor('asset/edit/'.$recentasset['asset_id'],$recentasset['asset_name']).'</td></tr>';
	}
	echo '</table></div></div>';
?>