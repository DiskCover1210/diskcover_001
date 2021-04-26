<?php
/**
 * Autor: Orlando Quintero.
 * Mail:  filvovmax@gmail.com
 * web:   www.diskcoversystem.com
 */
//require_once 'determ.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("panel.php");
require_once("chequear_seguridad_e.php");
require_once("../controlador/contabilidad_controller.php");
$_SESSION['INGRESO']['modulo_']='00';
//verificamos sesion sql
if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) && $_SESSION['INGRESO']['Tipo_Base'] =='SQL SERVER') 
{
	$database=$_SESSION['INGRESO']['Base_Datos'];
	$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
	$user=$_SESSION['INGRESO']['Usuario_DB'];
	$password=$_SESSION['INGRESO']['Contraseña_DB'];
	$usuario=getUsuario();
	$_SESSION['INGRESO']['CodigoU']=$usuario[0]['CodigoU'];
	$_SESSION['INGRESO']['Nombre_Completo']=$usuario[0]['Nombre_Completo'];
	//verificamos en acceso si puede ingresar a esa empresa
	$_SESSION['INGRESO']['accesoe']='0';
	$_SESSION['INGRESO']['modulo'][0]='0';
	$permiso=getAccesoEmpresas();
}else
{
	echo "<script>
				Swal.fire({
				  type: 'error',
				  title: 'Asegurese de tener credeciales de SQLSERVER',
				  text: '',
				  allowOutsideClick:false,
				}).then((result) => {
				  if (result.value) {
					location.href='panel.php#';
				  } 
				});
			</script>";
}
	//echo ' jjj '.$_SESSION['autentificado'];
	//die();
	//cuerpo
?>
	<!-- <div class="panel panel-info">
		<div class="panel-heading">
			<p class="box-title">Contabilidad</p>
		</div>
	</div>-->
	<!-- <h3 class="box-title">Entidad </h3>-->
		<div class="row">
			<div class="col-xs-12">
			
				<!-- /.box-header -->
				
				<!-- /.box-body -->
				<?php
				//llamamos a los parciales
					if (isset($_SESSION['INGRESO']['accion'])) 
					{
						//cambio de empresa
						if ($_SESSION['INGRESO']['accion']=='cambioe') 
						{
							require_once("empresa/cambioe.php");
						}
						//adminis. usuario
						if ($_SESSION['INGRESO']['accion']=='cambiou') 
						{
							require_once("empresa/cambiou.php");
						}
						if ($_SESSION['INGRESO']['accion']=='niveles_seguri') 
						{
							require_once("empresa/niveles_seguri.php");
						}
						if ($_SESSION['INGRESO']['accion']=='mostrar_venci') 
						{
							require_once("empresa/mostrar_venci.php");
						}
						
					}else
					{
						echo "<div class='box-body'><img src='../../img/modulo_empresa.png' width='100%' heigth='500px'></div>";
					}
					
				?>	
				<?php
				//para errores
					if (isset($_GET['er'])) 
					{
						if($_GET['er']==1)
						{
							
						}
					}
				?>	
				
<?php

	require_once("footer.php");
	
?>			
	
