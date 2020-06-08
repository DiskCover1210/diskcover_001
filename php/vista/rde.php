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

	//echo ' jjj '.$_SESSION['autentificado'];
	//die();
	//cuerpo
?>
	  <?php

    if(!isset($_SESSION)) 
	 		session_start();
	//para validar que solo este en esta pagina
	//$_SESSION['INGRESO']['solouna']=0;
	//echo $_SESSION['INGRESO']['solouna'].' fffff ';
?>
<!--<h2>Balance de Comprobacion/Situación/General</h2>-->

<div class="panel box box-primary">
	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		<div class="box table-responsive">
             <!--  /.box-header -->
			<?php
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
			$OpcDG=null;
			//border
			$b=null;
			//si escogio una opcion de radio buton
			if(isset($_GET['OpcDG'])) 
			{
				$OpcDG=$_GET['OpcDG'];
			}
			//border
			if(isset($_GET['b'])) 
			{
				$b=$_GET['b'];
			}
			//llamamos a la funcion para mostrar la grilla o exportar a excel , pdf
			
			if(isset($_GET['ex'])) 
			{
				if($_GET['ex']==1) 
				{
					if(isset($_GET['Opcb'])) 
					{
						ListarDocEletronico($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,'2');
					}
					else
					{
						ListarDocEletronico('REPORTE DOCUMENTO ELECTRÓNICO',null,null,$OpcDG,$b,'2');
					}
				}					
			}
			else
			{
				if(isset($_GET['Opcb'])) 
				{
					$balance=ListarDocEletronico($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,null,'1,clave',
					$start_from, $record_per_page, $filtros);
				}
				else
				{
					$balance=ListarDocEletronico('REPORTE DOCUMENTO ELECTRÓNICO',null,null,$OpcDG,$b,null,'1,clave',
					$start_from, $record_per_page,$filtros);
				}
				//echo $start_from.' fgfgfg '.$record_per_page;
			}
			?>
			<?php
				if(1==0)
				{
			?>
				  <div class="row ">
					  <div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
						 <a href="gde.php?q=q" target='_blank'><button type="submit" class="btn btn-default btn-xs active"  id='CD' style="width: 15%;">
						 Generar detalle</button></a>
					  </div>
				  </div>
			<?php
				}
			?>
			<script>
			//se implementa funcion check en vista
             function validarc(id,ta) 
			 {
				//verificamos que este seleccionado
				if (document.getElementById(id).checked)
				{
					$('#l4').attr("disabled", false);
					$('#l5').attr("disabled", false);
					$('#l6').attr("disabled", false);
				}
				else
				{
					$('#l4').attr("disabled", true);
					$('#l5').attr("disabled", true);
					$('#l6').attr("disabled", true);
				}
				
				var select = document.getElementById(id), //El <select>
				value = select.value; 
				
				var l4=$('#l4').attr("href");
				//agregar fechas				
				//var l1=l1+'&OpcDG='+texto;
				var l4=l4+'&cl='+value+'';
				//asignamos
				$("#l4").attr("href",l4);
				
				var l5=$('#l5').attr("href");
				//agregar fechas				
				//var l1=l1+'&OpcDG='+texto;
				var l5=l5+'&cl='+value+'';
				//asignamos
				$("#l5").attr("href",l5);
				
				var l6=$('#l6').attr("href");
				//agregar fechas				
				//var l1=l1+'&OpcDG='+texto;
				var l6=l6+'&cl='+value+'';
				//asignamos
				$("#l6").attr("href",l6);
				//alert(value);
				
				//otro link
				var l7=$('#l7').attr("href");
				//agregar fechas				
				//var l1=l1+'&OpcDG='+texto;
				var l7=l7+'&cl='+value+'';
				//asignamos
				$("#l7").attr("href",l7);
			 }
			 </script>
            <!-- /.box-body -->
        </div>
	</div>
  </div>
</div>
<?php

	require_once("footer.php");
	
?>			
	
