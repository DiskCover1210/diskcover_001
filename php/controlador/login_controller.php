<?php
//Llamada al modelo
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("../modelo/usuario_model.php");
//require_once("afe.php");

if(isset($_POST['submitlog'])) 
{
	login('', '', '');
}
 function login($sTabla, $vValores, $sCampos=NULL)
{
	//validar digito verificador
	//digito_verificador($_POST['entidad']);
	//echo "entroooo";
	
	//vemos los campos a enviar
	if (isset($_POST['entidad']) )
	{
		$ent=$_POST['entidad'];
	}
	if (isset($_POST['correo']) )
	{
		$cor=$_POST['correo'];
	}
	if (isset($_POST['contra']) )
	{
		$pas=$_POST['contra'];
	}
	
	
	if(!isset($_SESSION['INGRESO']['CodigoU']))
	{
		//llamamos al modelo
		$per=new usuario_model();
		 $per->Ingresar($ent,$cor,$pas);
		//die();
		//$datos=$per->get_contacto();
		//Llamada a la vista
		require_once("../vista/contacto_view.php");
	}
	else
	{
		//por lo es correcto el logeo realizamos la redireccion
		if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
			$uri = 'https://';
		}else{
			$uri = 'http://';
		}
		$uri .= $_SERVER['HTTP_HOST'];
		//die();
		//Aqui modificar si el pag de aministracion esta 
		//en un subdirectorio
		// "<script type=\"text/javascript\">
		// window.location=\"".$uri."/wp-admin/admin.php\";
		// </script>";
		echo "<script type='text/javascript'>window.location='".$uri.$_SERVER["REQUEST_URI"]."/../../../php/vista/panel.php'</script>";
	}
}	
//$per=new contacto_model();
//$datos=$per->get_contacto();
//Llamada a la vista
//require_once("../vista/contacto_view.php");
?>
