<?php
	echo
		'<div class="row-fluid">'
		,'<div class="text-align-left">'
		,heading('<i class="icon-lemon"></i> ERROR  404', 2)
		,'<div class="well">'
		,'<center><div id="jackNO" class="jackLeft"></div>'
		,heading('Jackson cannot find the page you are looking for.',3)
		,heading('Double check your link and try again.',5)
		,'</center>'
		,'</div></div></div>';
?>
<script>
$("#jackNO").pulse("","jackRight", 300);
$(".jac-icon").addClass("invisable");
</script>