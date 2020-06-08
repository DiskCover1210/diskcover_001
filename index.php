<?php 

if(!isset($_SESSION)) 
{	
	session_start();
}
if(isset($_SESSION['autentificado']))
{
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) 
	{
		$uri = 'https://';
	}
	else
	{
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];

if ($_SESSION['autentificado'] != "VERDADERO") 
{ 
		
	if($uri=='https://erp.diskcoversystem.com')
	{
		echo "<script>
				/*Swal.fire({
					type: 'error',
					title: 'Fallo',
					text: 'fallo conexion sql server',
					footer: 'Fallo conexion sql server'
				})*/
				//alert(' Prodccuon ".$uri." ');
		</script>";
		$_SESSION['INGRESO']['RUTA']='/';
	}
	else
	{
		echo "<script>
				/*Swal.fire({
					type: 'error',
					title: 'Fallo',
					text: 'fallo conexion sql server',
					footer: 'Fallo conexion sql server'
				})*/
				//alert(' Otro ".$uri." ');
		</script>";
		$_SESSION['INGRESO']['RUTA']='https://erp.diskcoversystem.com/';
	}
}else
{
	echo "<script type='text/javascript'>window.location='php/vista/login.php'</script>"; 
	exit(); 
}
}

?>
<script>
sum(10,20);
 diff(10,20);
 function sum(x,y) {
  return x + y;
 }
 let diff = function(x,y) {
  return x - y;
 }
</script>