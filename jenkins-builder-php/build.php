<?php 
class build{
	//not used for now... kinda useless
	function sync(){
		
	}
	
	
	function deploy(){
		$cmdArgs = array(
			"-avz",
			"-e",
			"'ssh -i /var/lib/jenkins-php-libs/sshKeys/itx148'", 
			"--delete",
			"--exclude=.svn/",
			"--exclude=jenkins-builder-php/",
			"--exclude=application/config/database.php",
			"--exclude=application/config/config.php",
			"--exclude=application/config/LIVE_SITE_CONFIGS/",
			".",
			"jenkins@192.168.100.148:/cygdrive/c/netfiles/AssetManager/"
		);
		
		print_r($cmdArgs);
		
		
		$file = fopen("PHPSAM_version.txt","w");
		echo fwrite($file,"Build date: ".date('F jS Y h:i:s A')." SVN Revision: ".$_SERVER['SVN_REVISION']);
		fclose($file);
		
		$rsyncCommand="rsync ".implode(" ",$cmdArgs);
		print_r($rsyncCommand);
	
		$output;
		$returnVar;
		exec($rsyncCommand,$output,$returnVar);
		echo implode("\n",$output);
		return $this->exitCode($returnVar);
	}
	
	function setPermissions(){
		$cmdArgs = array(
			'chmod'
			,'777'
			,'application/config/assetManagerSettings.php'
		);
		
		print_r($cmdArgs);
		
		$chmodCommand=implode(" ",$cmdArgs);
		print_r($chmodCommand);
	
		$output;
		$returnVar;
		exec($chmodCommand,$output,$returnVar);
		echo implode("\n",$output);
		return $this->exitCode($returnVar);
	}

	//updates the database config files with the contents of /LIVE_SITE_CONFIGS/
	function deployConfig(){
		
		$cmdArgs = array(
			"-avz",
			"-e",
			"'ssh -i /var/lib/jenkins-php-libs/sshKeys/itx148'",
			"--max-delete=0",
			"--exclude=.svn/",
			"'application/config/LIVE_SITE_CONFIGS/'",
			"jenkins@192.168.100.148:/cygdrive/c/netfiles/AssetManager/application/config"
		);
		
		print_r($cmdArgs);
		
		$rsyncCommand="rsync ".implode(" ",$cmdArgs);
		print_r($rsyncCommand);
		$output;
		$returnVar;
		exec($rsyncCommand,$output,$returnVar);
		echo implode("\n",$output);
		
		return $this->exitCode($returnVar);
	}
	function exitCode($returnVar)
	{
		switch($returnVar){
			case 0:		//Success
				echo 'Success';
				break;
			case 1:     //Syntax or usage error
				echo 'Syntax or usage error';
				break;
			case 2: 	//Protocol incompatibility
				echo 'Protocol incompatibility';
				break;
			case 3:     //Errors selecting input/output files, dirs
				echo 'Errors selecting input/output files, dirs';
				break;
			case 4:     //Requested  action not supported: an attempt was made to manipulate 64-bit files on a platform that cannot support them; or an option was specified that is supported by the client and not by the server.
				echo 'Requested  action not supported: an attempt was made to manipulate 64-bit files on a platform that cannot support them; or an option was specified that is supported by the client and not by the server.';
				break;
			case 5:     //Error starting client-server protocol
				echo 'Error starting client-server protocol';
				break;
			case 6:     //Daemon unable to append to log-file
				echo 'Daemon unable to append to log-file';
				break;
			case 10:    //Error in socket I/O
				echo 'Error in socket I/O';
				break;
			case 11:    //Error in file I/O
				echo 'Error in file I/O';
				break;
			case 12:    //Error in rsync protocol data stream
				echo 'Error in rsync protocol data stream';
				break;
			case 13:    //Errors with program diagnostics
				echo 'Errors with program diagnostics';
				break;
			case 14:    //Error in IPC code
				echo 'Error in IPC code';
				break;
			case 20:    //Received SIGUSR1 or SIGINT
				echo 'Received SIGUSR1 or SIGINT';
				break;
			case 21:    //Some error returned by waitpid()
				echo 'Some error returned by waitpid()';
				break;
			case 22:    //Error allocating core memory buffers
				echo 'Error allocating core memory buffers';
				break;
			case 23:    //Partial transfer due to error
				echo 'Partial transfer due to error';
				break;
			case 24:    //Partial transfer due to vanished source files
				echo 'Partial transfer due to vanished source files';
				break;
			case 25:    //The --max-delete limit stopped deletions
				echo 'The --max-delete limit stopped deletions';
				break;
			case 30:    //Timeout in data send/receive
				echo 'Timeout in data send/receive';
				break;
			case 35:    //Timeout waiting for daemon connection
				echo 'Timeout waiting for daemon connection';
				break;
		}
		echo "\n";
		return $returnVar;
	}
}
?>