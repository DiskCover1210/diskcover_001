<?php
/**
 * Autor: Jonathan Avalos
 * Mail:  jd-avalos@hotmail.com
 * web:   www.diskcoversystem.com
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("panel.php");
require_once("chequear_seguridad.php");
require_once("../controlador/contabilidad_controller.php");
$_SESSION['INGRESO']['modulo_']='01';
//verificamos sesion sql
if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) && $_SESSION['INGRESO']['Tipo_Base'] =='SQL SERVER') 
{
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

?>
		<div class="row">
			<div class="col-xs-12">
			 				<!-- /.box-header -->
				
				<!-- /.box-body -->
				<?php
				//llamamos a los parciales
					if (isset($_SESSION['INGRESO']['accion'])) 
					{
						//facturar
						if ($_SESSION['INGRESO']['accion']=='factura') 
						{
							require_once("cybernet/factura.php");
						}
					}else
					{
						echo "<div class='box-body'><img src='../../img/modulo_contable.gif' width='100%'></div>";
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


	require_once("panel2.php");
?>	
		