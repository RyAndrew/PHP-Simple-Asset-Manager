<?php 
$session_data = $this->session->userdata('logged_in');
if($session_data){
	
	$menu['<i class="icon-list"></i> Asset List'] 		= 'asset/allAssets';
	$menu['<i class="icon-search"></i> Asset Search'] 	= 'asset/search';
	$menu['<i class="icon-bar-chart"></i> Reports'] 	= 'reports';
	if($this->user_model->curIsAdmin()){
		$menu['<i class="icon-cogs"></i> System'] 		= array(
			'Log' 				=> 'log'
			,'Settings'			=> 'system'
			,'SNMP'				=> 'system/snmpSettings'
			,'User Management'	=> 'system/userManagement'
			,'Attributes' 		=> 'attribute'
			,'Types' 			=> 'type'
		);
	}
	$menu['<i class="icon-user"></i> Account'] 			= array(
			'Hi, <span id="yourUsername">'.$this->user_model->getFname($session_data['user_id']).'</span>!'	=> NULL
			,'Profile' 			=> 'profile'
			,'Logout' 			=> 'login/logout'
		);
}
?>
<div class="navbar navbar-inverse navbar-static-top text-align-left">
	<div class="navbar-inner">
		<div class="container"> 
			<?php if($session_data){ ?>
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<?php } ?>
	 
			<a id="homeLink" class="brand" href="<?php echo site_url(); ?>"><i class="icon-home invisable" id="homeIcon" style="line-height:0px;"></i>
			<span id="homeText">
			<?php 
				if($this->config->item('websiteTitle')!==null){
		 			echo $this->config->item('websiteTitle');
				}else{
					echo '[No Title]';
				}
			?>
			</span>
			</a>
			<script>
				$("#homeLink").hover(function() {
					$("#homeIcon").removeClass("invisable");
				}, function() {
					$("#homeIcon").addClass("invisable"); 
				});
			</script>
			<div class="nav-collapse collapse">
				<?php
				if($this->session->userdata('logged_in')){
					echo 
					'<ul class="nav">';
						foreach($menu as $name => $link){
							if(is_array($link)){
								echo '<li class="dropdown">';
								echo '<a data-toggle="dropdown" class="dropdown-toggle" href="#">'.$name.'<b class="caret"></b></a>';
									echo '<ul class="dropdown-menu">';
									foreach($link as $subname => $sublink){
										if($sublink === NULL){
											echo '<li class="disabled"><a href="#">'.$subname.'</a></li>';
										}
										elseif(uri_string()==$link){
											echo '<li class="active">'.anchor($sublink,$subname).'</li>';
										}else{
											echo '<li>'.anchor($sublink,$subname).'</li>';
										}
									}
									echo '</ul>';
								echo '</li>';
							}else{
								if(uri_string()==$link){
									if($link=="asset/search"){
										echo '<li class="active hidden-desktop">'.anchor($link,$name).'</li>';
									}else{
										echo '<li class="active">'.anchor($link,$name).'</li>';
									}
								}else{
									if($link=="asset/search"){
										echo '<li class="hidden-desktop">'.anchor($link,$name).'</li>';
									}else{
										echo '<li>'.anchor($link,$name).'</li>';
									}
									
								}
							}
						}
					


					echo 
					'</ul>'
					,'<form onsubmit="return false;" class="navbar-search pull-right visible-desktop">'
	    				,'<i id="quickSearchIcon" class="icon-refresh icon-spin icon-light icon-large hidden" style="vertical-align:0%;"></i> <input id="quickSearch" type="text" class="search-query span2" placeholder="Quick Search">'
	    				,'<ul class="nav pull-right">'
	    					,'<li class="dropdown" id="AdvSearchDropdown">'
								,'<a class="dropdown-toggle" href="#" data-toggle="dropdown"><b class="caret"></b></a>'
								,'<ul class="dropdown-menu">'
									,'<li>'.anchor('asset/search','Advanced Search').'</li>'
	    						,'</ul>'
	    					,'</li>'
	    				,'</ul>'
	    			,'</form>';
				}
				?>
			</div>
			<!--<img class="jac-icon hidden-phone" src="<?php echo $this->config->item('base_url'); ?>img/andy-jackson3.png" />-->
		</div>
	</div>
</div>
<?php  if($this->session->userdata('logged_in')){ ?>
<script>

	function adjustNavBarSearchCarrot(){
		$('#AdvSearchDropdown').css('margin-top','-'+$('.navbar-search').css('margin-top'));
		$('#quickSearchIcon').css('margin-top','-'+$('.navbar-search').css('margin-top'));

	}

	$(function(){	

		adjustNavBarSearchCarrot();

		$( "#quickSearch" ).autocomplete({
			minLength: 2,
			source: '<?php echo $this->config->item('base_url'); ?>index.php/asset/apiGetAssetsUsingSearchTerm',
			focus: function(event, ui){
				$( "#quickSearch" ).val( ui.item.cleanLabel );
				//$( "#new-link-asset-id" ).val( ui.item.value );
				return false;
			},
			select: function(event, ui){
				$( "#quickSearch" ).val( ui.item.cleanLabel );
				window.location.href = "<?php echo $this->config->item('base_url'); ?>index.php/asset/edit/"+ui.item.value;
				return false;
			},
			search: function(event, ui){
				$("#quickSearchIcon").removeClass('hidden');
				//setFormErrorColors(-1);
			},
			response: function(event, ui){
				$("#quickSearchIcon").addClass('hidden')
				if(ui.content.length==0){ 
					//setFormErrorColors({new_linked_asset:"No Results Found"});
				}
			}
		})
		.data("ui-autocomplete")._renderItem = function( ul, item ){
			ul.addClass('dropdown-menu');
			ul.addClass('searchResultDropdown');
			return $("<li>").append("<a>"+item.label+"</a>").appendTo(ul);
		};
	});
</script>
<?php } ?>