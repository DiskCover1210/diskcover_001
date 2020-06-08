<?php
session_start(); 
session_destroy();  

?>

<?php
if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
								$uri = 'https://';
							}else{
								$uri = 'http://';
							}
							$uri .= $_SERVER['HTTP_HOST'];
							//echo $uri;
					echo "<script type='text/javascript'>window.location='".$uri."/php/vista/login.php'</script>";
					//echo "<script type='text/javascript'>window.location='".$uri."/diskcover/nuevo/index.php'</script>";
//header("Location:../vista/login.php"); 
exit(); 
?> 