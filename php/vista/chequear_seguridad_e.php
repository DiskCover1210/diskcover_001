<?php
if(!isset($_SESSION)) 
	{ 		
			session_start();
			if (!isset($_SESSION['INGRESO']['empresa']) ) {
				if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
								$uri = 'https://';
							}else{
								$uri = 'http://';
							}
							$uri .= $_SERVER['HTTP_HOST'];
							//echo $uri;
					echo "<script type='text/javascript'>window.location='".$uri."/php/vista/panel.php'</script>";
			
			exit(); 
		}
			
	}
	else
	{
			if (!isset($_SESSION['INGRESO']['empresa'])) { 
				if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
								$uri = 'https://';
							}else{
								$uri = 'http://';
							}
							$uri .= $_SERVER['HTTP_HOST'];
							//echo $uri;
					echo "<script type='text/javascript'>window.location='".$uri."/php/vista/panel.php'</script>";
			exit(); 
		} 
	} 

?>
