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
require_once("chequear_seguridad_e.php");
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
		
	