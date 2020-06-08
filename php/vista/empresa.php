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
if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
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
			 <div class="box">
				<div class="box-header">
					
				 <!--  <h3 class="box-title">Responsive Hover Table</h3>

				  <div class="box-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
					  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

					  <div class="input-group-btn">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					  </div>
					</div>-->
				  </div>
				</div>
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
	
