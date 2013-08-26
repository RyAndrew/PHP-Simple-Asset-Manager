<?php
	//echo 'welcome to step2.'.anchor('installer/1',' Back to step 1?');
	echo
		'<div class="text-align-left span8 css-centered">'
			,heading('<i class="icon-star"></i> Well, Hello!', 2)
			,'<div class="well">'
				,'<div class="text-align-center" style="float:right; width:100px;"><img src="'.$this->config->item('base_url').'img/jackleft.png" /><p class="text-info">(Hello!)</p></div>'
				,heading('PHP Asset Manager Installer',3)
				,'<p> Jackson here would like to personally welcome you to the PHP Asset Manager (PHPSAM, for short.) Installer. Click on <span class="text-success">the big green button</span> below to start.</p>'
				,anchor('installer/showInstaller','The Big Green Button (Start Installer)','class="btn btn-large btn-success btn-block" style="margin-top:30px;"')
				,'<p class="text-align-center muted">(You are seeing this because you have not run this installer before, or have not deleted the installer.php class yet)</p>'
			,'</div>'
		,'</div>'
?>