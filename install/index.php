<?php
	class installer{
		function __construct(){
			error_reporting(0);
            //if(isset($_POST['data'])){
            //    var_dump(json_decode(base64_decode($_POST['data']),true));
            //}
			$this->header();
			if(isset($_POST['step']) && is_numeric($_POST['step']) && method_exists($this,"step".$_POST['step'])){
				$nextStep="step".$_POST['step'];
				$this->$nextStep();
			}else{
				$this->index();
			}
			$this->footer();
		}
		function index(){
			echo <<<EOD
				<form method="post">
					<input type="hidden" name="step" value="1">
					<input type="submit" value="Start Installer ->">
				</form>
EOD;
		}
		function header(){
			echo <<<EOD
				<!DOCTYPE html>
				<html>
					<head>
						<title>PHP Asset Manager Installer</title>
						<style>
							label, input{
								display:block;
								margin:2px;
							}
						</style>
					</head>
					<body>
						<h1>PHP Asset Manager Installer</h1>
EOD;
		}
		function footer(){
				echo <<<EOD

					</body>
				</html>
EOD;
		}
		
		//Web Location
		function step1(){
			$rootLoc = str_replace(array('install/index.php','http://','https://'),'', $_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);
			$rootLoc = 'http://'.$rootLoc;

			echo <<<EOD
				<form method="post">
					<input type="hidden" name="step" value="2">
					<label for="website_root" >PHP Asset Manager install location - <b>Include trailing slash!</b></label>
					<input type="text" size="60" name="website_root" id="website_root" value="{$rootLoc}">
					<input type="submit" value="Next ->">
				</form>
EOD;
			
		}

		//check web url & and write it to codeigniter.
		function step2(){
			$loc =  $_POST['website_root'].'install/urltest.txt';
			$passbutton=file_get_contents($loc);
			//var_dump($passbutton);
			if($passbutton === false || $passbutton != 'thetestpassed!'){
				echo <<<EOD
					I failed to connect to that url. Please go back and try again.
					<form method="post">
						<input type="hidden" name="step" value="1">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
				return false;
			}
			$templateLoc="../application/config/config_template.txt";
			$configLoc="../application/config/config.php";
			$configFile = file_get_contents($templateLoc);

			if($configFile  === false){
				echo <<<EOD
					I failed to load config template. 
					<form method="post">
						<input type="hidden" name="step" value="1">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
				return false;	
			}

			$configFile=str_replace('{ASSET_MANAGER_BASE_URL}',$_POST['website_root'],$configFile);
			if(file_put_contents($configLoc,$configFile)===false){
				echo <<<EOD
					I failed to write to the config file. 
					<form method="post">
						<input type="hidden" name="step" value="1">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
				return false;
			}
            echo <<<EOD
					Successfully saved root site url.
					<form method="post">
						<input type="hidden" name="step" value="3">
						<input type="submit" value="Next ->">
					</form>
EOD;
        }
		function step3(){
			echo <<<EOD
				<form method="post">
					<input type="hidden" name="step" value="4">
					
					<label for="database_host" >Database Host</label>
					<input type="text" size="60" name="database_host" id="database_host" value="localhost">

					<label for="database_username" >Database Username</label>
					<input type="text" size="60" name="database_username" id="database_username">

					<label for="database_password" >Database Password</label>
					<input type="password" size="60" name="database_password" id="database_password">

					<label for="database_name" >Database Name</label>
					<input type="text" size="60" name="database_name" id="database_name">

					<input type="submit" value="Next ->">
				</form>
EOD;
		}

		//Check/save Database Info
		function step4(){
			if(!isset($_POST['database_name']) || $_POST['database_name']==""){
				echo <<<EOD
				    You need to specify a database name.
					<form method="post">
						<input type="hidden" name="step" value="3">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
				return false;
			}


			if(FALSE===$dbc=mysql_connect($_POST['database_host'],$_POST['database_username'],$_POST['database_password'])){
				$error = mysql_error();
				echo <<<EOD
					Failed to connect to the database. {$error}
					<form method="post">
						<input type="hidden" name="step" value="3">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
				return false;
			}
            if(FALSE === mysql_select_db($_POST['database_name'],$dbc)){
                $error = mysql_error();
                echo <<<EOD
					Database doesn't exist. {$error}
					<form method="post">
						<input type="hidden" name="step" value="3">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
                return false;
            }


			$templateLoc="../application/config/database_template.txt";
			$configLoc="../application/config/database.php";
			$configFile = file_get_contents($templateLoc);

			if($configFile  === false){
				echo <<<EOD
					I failed to load database config template. 
					<form method="post">
						<input type="hidden" name="step" value="1">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
				return false;	
			}

			$configFile=str_replace('{ASSET_MANAGER_HOSTNAME}',$_POST['database_host'],$configFile);
			$configFile=str_replace('{ASSET_MANAGER_USERNAME}',$_POST['database_username'],$configFile);
			$configFile=str_replace('{ASSET_MANAGER_PASSWORD}',$_POST['database_password'],$configFile);
			$configFile=str_replace('{ASSET_MANAGER_DATABASE}',$_POST['database_name'],$configFile);
			if(file_put_contents($configLoc,$configFile)===false){
				echo <<<EOD
					I failed to write to the database config file. 
					<form method="post">
						<input type="hidden" name="step" value="1">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
				return false;
			}

            $data=base64_encode(json_encode($_POST));
            echo <<<EOD
				    Successfully connected to the database. Click next to run the mysql install.
					<form method="post">
						<input type="hidden" name="step" value="5">
                        <input type="hidden" name="data" value="{$data}">
						<input type="submit" value="Next ->">
					</form>
EOD;
        }

        function step5(){
            $data=json_decode(base64_decode($_POST['data']),true);
            if(FALSE===$sql=file_get_contents("assetManagerTemplate.sql")){
                echo <<<EOD
                    Failed to load the sql query.
					<form method="post">
						<input type="hidden" name="step" value="3">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
                return false;
            }


            $dbc=mysql_connect($data['database_host'],$data['database_username'],$data['database_password']);
            mysql_select_db($data['database_name'],$dbc);
            foreach(explode(';',$sql) as $query){
            	$query=trim($query);
            	if($query!='' && FALSE === mysql_query($query,$dbc)){
	                $error = mysql_error();
	                echo <<<EOD
	                    Failed to create a table in database (Do you have permission?). {$error} <br /> Query That Failed: <br /> {$query}
						<form method="post">
							<input type="hidden" name="step" value="3">
							<input type="submit" value="<- Go Back">
						</form>
EOD;
	                return false;
            	}
            }
            /*
            if(FALSE=== mysql_query("INSERT INTO `users` (`user_id`, `user_email`, `user_first_name`, `user_last_name`, `user_password`, `user_salt`, `is_admin`, `is_disabled`, `user_theme`) VALUES (NULL, 'admin@assetManager.com', 'admin', 'istrator', 'password', 'password', '1', '0', NULL)")){
            	$error = mysql_error();
	                echo <<<EOD
	                    Failed to create admin user. {$error}
						<form method="post">
							<input type="hidden" name="step" value="3">
							<input type="submit" value="<- Go Back">
						</form>
EOD;
	                return false;
			}
			*/

			
            echo <<<EOD
				    Successfully executed mysql install. Only one last step - create your first user!
					<form method="post">
						<input type="hidden" name="step" value="6">
                        <input type="hidden" name="data" value="{$_POST['data']}">
						<input type="submit" value="Next ->">
					</form>
EOD;

            

        }
        function step6(){
        	echo <<<EOD
					<form method="post">
						<input type="hidden" name="step" value="7">
                        <input type="hidden" name="data" value="{$_POST['data']}">

                        <label for="email" >Email</label>
						<input type="text" size="60" name="email" id="email" max_length="30">

						<label for="fname" >First Name</label>
						<input type="text" size="60" name="fname" id="fname" max_length="30">

						<label for="lname" >Last Name</label>
						<input type="text" size="60" name="lname" id="lname" max_length="30">

						<label for="passwd" >Password</label>
						<input type="password" size="60" name="passwd" id="passwd" max_length="30">

						<input type="submit" value="Next ->">
					</form>
EOD;
        }

        function step7(){
        	$data=json_decode(base64_decode($_POST['data']),true);

        	if(!isset($_POST['email']) || $_POST['email']=='' || !isset($_POST['fname']) || $_POST['fname']=='' || !isset($_POST['lname']) || $_POST['lname']=='' || !isset($_POST['passwd']) || $_POST['passwd']=='' ){
        		echo <<<EOD
                    You didn't fill out the entire form.
					<form method="post">
						<input type="hidden" name="step" value="6">
						<input type="hidden" name="data" value="{$_POST['data']}">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
                return false;
        	}

        	$dbc=mysql_connect($data['database_host'],$data['database_username'],$data['database_password']);
            mysql_select_db($data['database_name'],$dbc);

            $user_salt =substr(md5(uniqid(rand(), true)), 0, 8);
            $password = hash("sha512",hash("md5",$user_salt.$_POST['passwd']));

            if(FALSE === mysql_query("INSERT INTO users (user_email,user_first_name,user_last_name,user_password,user_salt,is_admin,is_disabled) VALUES ('{$_POST['email']}','{$_POST['fname']}','{$_POST['lname']}','{$password}','{$user_salt}',1,0)",$dbc)){
                $error = mysql_error();
                echo <<<EOD
                    Failed to create admin user. {$error}
					<form method="post">
						<input type="hidden" name="step" value="6">
						<input type="hidden" name="data" value="{$_POST['data']}">
						<input type="submit" value="<- Go Back">
					</form>
EOD;
                return false;
        	}

        	echo <<<EOD
                    Successfully Installed The Asset Manager!<br />
                    <b>Username: </b>'{$_POST['email']}'<br />
                    <b>Password: </b>{Your chosen password}<br />
                    <strong>(MAKE SURE YOU CHANGE THE PASSWORD!)</strong><br />
                    <strong style="color:red;">(ALSO MAKE SURE TO DELETE/RENAME THE INSTALL DIRECTORY!)</strong><br />
                    <a href="../">Go log into your new asset manager!</a>

EOD;
        }
	}
	new installer();
?>