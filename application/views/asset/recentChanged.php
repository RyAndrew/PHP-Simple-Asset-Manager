<?php
	echo 
		'<div class="text-align-left span6">'
		,heading('<i class="icon-info"></i> Recently Changed', 2)
		,'<div class="well">'
		,'<table class="table table-striped table-bordered table-condensed"><tr><th class="span2">Date</th><th>Asset</th><th>Method</th></tr>';

	foreach($recentedits as $recentedit){
		echo '<tr><td class="span2">'.anchor('asset/history/'.$recentedit['asset_id'],date('m/d/y H:i',strtotime($recentedit['date']))).'</td><td>'.anchor('asset/edit/'.$recentedit['asset_id'],$recentedit['asset_name']).'</td><td>'.$recentedit['method'].'</td></tr>';
	}
	echo '</table></div></div></div>';
?>