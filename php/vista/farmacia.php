<?php
/**
 * Autor: Orlando Quintero.
 * Mail:  filvovmax@gmail.com
 * web:   www.diskcoversystem.com
 */
//require_once 'determ.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
require("panel.php");
require_once("chequear_seguridad.php");
//require_once("../funciones/numeros_en_letras.php");
$_SESSION['INGRESO']['modulo_']='28';
//verificamos sesion sql
if(isset($_GET['cuenta']))
{
	if($_GET['cuenta']=='-1')
	{
		echo '<script type="text/javascript">$(document).ready(function(){ Swal.fire("","Cuenta Cta_Desperdicio no encontrada","info"); });</script>';

	}else if($_GET['cuenta']== '-2')
	{
		echo '<script type="text/javascript">$(document).ready(function(){ Swal.fire("","Asegurese que la cuenta sea de detalle","info"); });</script>';
	}
}
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
						//cambio de clave
						if ($_SESSION['INGRESO']['accion']=='ingresar_proveedor') 
						{
							require_once("farmacia/ingresar_proveedor.php");
						}
						if ($_SESSION['INGRESO']['accion']=='ingresar_paciente') 
						{
							require_once("farmacia/ingresar_paciente.php");
						}
						if ($_SESSION['INGRESO']['accion']=='ingresar_descargos') 
						{
							require_once("farmacia/ingreso_descargos.php");
						}
						if ($_SESSION['INGRESO']['accion']=='ingresar_factura') 
						{
							require_once("farmacia/ingresar_factura.php");
						}
						if ($_SESSION['INGRESO']['accion']=='articulos') 
						{
							require_once("farmacia/articulos.php");
						}
						if ($_SESSION['INGRESO']['accion']=='pacientes') 
						{
							require_once("farmacia/paciente.php");
						}
						if ($_SESSION['INGRESO']['accion']=='vis_descargos') 
						{
							require_once("farmacia/descargos.php");
						}
						if ($_SESSION['INGRESO']['accion']=='descargos_procesados') 
						{
							require_once("farmacia/reporte_descargos_procesados.php");
						}
						if ($_SESSION['INGRESO']['accion']=='facturacion_insumos') 
						{
							require_once("farmacia/facturacion_insumos.php");
						}
						if ($_SESSION['INGRESO']['accion']=='farmacia_interna') 
						{
							require_once("farmacia/farmacia_interna.php");
						}
						
					}else
					{
						echo "<div class='box-body'><img src='../../img/modulo_farmacia.png' width='100%' heigth='500px'></div>";
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
		
	
