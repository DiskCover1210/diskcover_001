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
//require_once("../funciones/numeros_en_letras.php");
$_SESSION['INGRESO']['modulo_']='11';
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
				  title: 'Oops...',
				  text: 'Asegurese de tener credeciales de SQLSERVER',
				  allowOutsideClick:false,
				}).then((result) => {
				  if (result.value) {
					location.href='panel.php#';
				  } 
				});
			</script>";
}
?>
	
		<div class="row">
			<div class="col-xs-12">
			 <div class="box">
				<div class="box-header">					
				 </div>
				</div>
				<!-- /.box-header -->
				
				<!-- /.box-body -->
				<?php
				//llamamos a los parciales
					if (isset($_SESSION['INGRESO']['accion'])) 
					{
						//cambio de clave
						if ($_SESSION['INGRESO']['accion']=='detalle_estudiante') 
						{
							require_once("educativo/detalle_estudiante.php");
						}
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