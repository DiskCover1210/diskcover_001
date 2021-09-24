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
require_once("chequear_seguridad.php");
require_once("../controlador/contabilidad_controller.php");
//require_once("../funciones/numeros_en_letras.php");
$_SESSION['INGRESO']['modulo_']='01';
//verificamos sesion sql

// print_r($_SESSION);die();
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
						if ($_SESSION['INGRESO']['accion']=='cambioc') 
						{
							require_once("contabilidad/cambioc.php");
						}
						//ingreso catalogo de cuenta
						if ($_SESSION['INGRESO']['accion']=='incc') 
						{
							require_once("contabilidad/inccu.php");
						}
						//Mayorización
						if ($_SESSION['INGRESO']['accion']=='macom') 
						{
							require_once("contabilidad/macom.php");
						}
						//Balance de Comprobacion/Situación/General
						if ($_SESSION['INGRESO']['accion']=='bacsg') 
						{
							require_once("contabilidad/bacsg1.php");
						}
						//herramientas conexion oracle
						if ($_SESSION['INGRESO']['accion']=='hco') 
						{
							require_once("contabilidad/hco.php");
						}
						//comprobantes procesados
						if ($_SESSION['INGRESO']['accion']=='compro') 
						{
							require_once("contabilidad/compro.php");
						}
						//cambio de periodo
						if ($_SESSION['INGRESO']['accion']=='campe') 
						{
							require_once("contabilidad/campe.php");
						}
						//Ingresar Comprobantes (Crtl+f5)
						if ($_SESSION['INGRESO']['accion']=='incom') 
						{
							require_once("contabilidad/incom.php");
						}
						//saldo de factura submodulo
						if ($_SESSION['INGRESO']['accion']=='saldo_fac_submodulo') 
						{
							require_once("contabilidad/saldo_fac_submodulo.php");
						}
						if ($_SESSION['INGRESO']['accion']=='catalogo_cuentas') 
						{
							include("contabilidad/catalogoCta.php");
						}
						if ($_SESSION['INGRESO']['accion']=='diario_general') 
						{
							require_once("contabilidad/diario_general.php");
						}
						if ($_SESSION['INGRESO']['accion']=='mayor_auxiliar') 
						{
							require_once("contabilidad/mayor_auxiliar.php");
						}
						if ($_SESSION['INGRESO']['accion']=='libro_banco') 
						{
							require_once("contabilidad/libro_banco.php");
						}
						if ($_SESSION['INGRESO']['accion']=='ctaOperaciones') 
						{
							require_once("contabilidad/ctaOperaciones.php");
						}
						if ($_SESSION['INGRESO']['accion']=='anexos_trans') 
						{
							require_once("contabilidad/anexos_trans.php");
						}
						if ($_SESSION['INGRESO']['accion']=='bamup') 
						{
							require_once("contabilidad/bamup.php");
						}
						if ($_SESSION['INGRESO']['accion']=='reportes') 
						{
							require_once("contabilidad/resumen_retenciones.php");
						}
						if ($_SESSION['INGRESO']['accion']=='Clientes') 
						{
							require_once("contabilidad/FCliente.php");
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
		
	
