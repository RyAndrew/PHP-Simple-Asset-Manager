		</div>
		<div class="container">
		<?php
		$this->load->helper('file');
		if($this->config->item('show_build_date')!=1){
			$cssClass='hidden';
		}else{
			$cssClass='';
		}
		echo '<div id="buildDate" class="'.$cssClass.'" style="font-size:10px;">'.read_file('PHPSAM_version.txt').'</div>';
		?>
		</div>
		</center>
		
		<script>
			
			$(function(){
				/*
				var ips =$('#ipField input');
				//alert(ips.length);
				for(var i=0;i<ips.length;i++){
					ips.eq(i).ipaddress();
				}
				*/

				$('.tooltip-me').tooltip({
					selector: "a[data-toggle=tooltip]"
				}) 
				
				$(".alert-error").pulse("","pulse", 300);
				
			});



		</script>
	</body>
</html>
<?php $this->benchmark->mark('code_end'); ?>


