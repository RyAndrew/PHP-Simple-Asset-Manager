<?php
	echo
		'<div class="row-fluid">'
		,'<div class="text-align-left">'
		,heading('<i class="icon-warning-sign"></i> Unauthorized', 2)
		,'<div class="well">'
		,'<center><div id="jackNO" class="jackLeft"></div>'
		,heading('Jackson caught you snooping around.',3)
		,heading('You do not have access to view this page.',5)
		,'</center>'
		,'</div></div></div>';
?>
<script>
$("#jackNO").pulse("","jackRight", 300);
$(".jac-icon").addClass("invisable");
</script>