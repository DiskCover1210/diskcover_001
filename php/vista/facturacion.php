<?php
/**
 * Autor: Orlando Quintero.
 * Mail:  filvovmax@gmail.com
 * web:   www.diskcoversystem.com
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("panel.php");
require_once("chequear_seguridad.php");
require_once("../controlador/contabilidad_controller.php");
$_SESSION['INGRESO']['modulo_']='02';
//verificamos sesion sql
if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) && $_SESSION['INGRESO']['Tipo_Base'] =='SQL SERVER') 
{
	$permiso=getAccesoEmpresas();
}else
{
	echo "<script>
				Swal.fire({
				  type: 'error',
				  title: 'Comuniquese con el Administrador del Sistema, Para Activar el acceso a su base de dato',
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
			 				<!-- /.box-header -->
				
				<!-- /.box-body -->
				<?php
				//llamamos a los parciales
					if (isset($_SESSION['INGRESO']['accion'])) 
					{
						//facturar pension
						if ($_SESSION['INGRESO']['accion']=='facturarPension') 
						{
							require_once("facturacion/facturar_pension.php");
						}
						//compra / venta divisas
						if ($_SESSION['INGRESO']['accion']=='divisas') 
						{
							require_once("facturacion/divisas.php");
						}
						//listar y anular facturas 
						if ($_SESSION['INGRESO']['accion']=='listarFactura') 
						{
							require_once("facturacion/listar_facturas.php");
						}
						if ($_SESSION['INGRESO']['accion']=='facturarLista') 
						{
							require_once("facturacion/lista_facturas.php");
						}
					}else
					{
						echo "<div class='box-body'><img src='../../img/modulo_facturacion.png' width='100%'></div>";
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