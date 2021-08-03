<?php  
include("chequear_seguridad.php"); 
require("../controlador/panel.php");
require_once("../funciones/funciones.php");

if(isset($_GET['mos']))
{
	variables_sistema($_GET['mos'],$_GET['mos1'],$_GET['mos3']);
}
if(isset($_GET['mos2']))
{
	 eliminar_variables();
}
?>
<!DOCTYPE html>
<html>
<head>   
<!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
 
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../lib/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../lib/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../lib/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../../lib/bower_components/jquery-ui/themes/base/jquery-ui.css">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../lib/dist/css/skins/_all-skins.min.css"> 
 
  
  
  <script src="../../lib/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- jQuery 3 -->
<script src="../../lib/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../lib/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../lib/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../lib/plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../lib/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../lib/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../../lib/bower_components/moment/min/moment.min.js"></script>
<script src="../../lib/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../lib/bower_components/jquery-ui/jquery-ui.js"></script>

<!-- bootstrap datepicker -->
<script src="../../lib/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../../lib/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../../lib/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../../lib/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../lib/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="../../lib/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../lib/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../lib/dist/js/demo.js"></script>

<script src="../../lib/dist/js/jquery-ui.js"></script>
  <link rel="stylesheet" href="../../lib/dist/css/jquery-ui.css">


<script src="../../lib/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="../../lib/dist/css/select2.min.css">
  
 <link rel="stylesheet" href="../../lib/dist/css/sweetalert.css">
  <script src="../../lib/dist/js/sweetalert-dev.js"></script>
  
  <script src="../../lib/dist/js/sweetalert2.min.js"></script>
  <script type="text/javascript" src="../../lib/dist/js/typeahead.js"></script>
  <link rel="stylesheet" href="../../lib/dist/css/sweetalert2.min.css">
  <link rel="shortcut icon" href="../../img/jpg/logo.jpg" />
  <style>
       .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
            /* add padding to account for vertical scrollbar */
            padding-right: 20px;
        } 
</style>
<script type="text/javascript">
		function cambiar_1(){
			$('#myModal_espera').modal('show');
			//var select = document.getElementById(id), //El <select>
			var value =  $('#sempresa').val(); //El valor seleccionado
			//partimos cadenas
			separador = "-"; // un espacio en blanco
			limite    = 2;
			arregloDeSubCadenas = value.split(separador, limite);
			text =$('#sempresa option:selected').html(); //El texto de la opción seleccionada
			//console.log(text);
			// alert(value);
			// alert(text);
			//redireccionamos
			window.location="panel.php?mos="+value+"&mos1="+text+"&mos3="+arregloDeSubCadenas[1]+"";
		}
</script>


 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse cargando" id='cargar'>
	
	 <?php
			$host= $_SERVER["HTTP_HOST"];
			$url= $_SERVER["REQUEST_URI"];
			$url = $host . $url;
			//echo $url;
			$cadena_de_texto = 'Esta es la frase donde haremos la búsqueda';
			$bus   = 'popup.php';
			$posicion_coincidencia = strpos($url, $bus);
			 $ban=0;
			//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
			if ($posicion_coincidencia === false) 
			{			
				  require_once("header.php");			
			}
			
		    ?>
			 <?php
				if(isset($_SESSION['INGRESO']['accion1']))
				{
			  ?>
				<title>DiskCover System <?php echo $_SESSION['INGRESO']['accion1']; ?></title>
			  <?php
				}
				else
				{
			  ?>
					 <title>DiskCover System login</title>
			   <?php
				}
				
			  ?>
			  			  			
<!-- Site wrapper -->
<script type="text/javascript"></script>
<section class="content-wrapper" id="seccion" style="min-height: auto;">
  <div class="box box-default">
   <div class="box-body"> 
   	<br>
   	<?php
			//llamamos a los parciales para menus
			if (isset($_SESSION['INGRESO']['accion'])) 
			{ 
				//Mayorización
				//reporte documentos electronicos
				if ($_SESSION['INGRESO']['accion']=='rde') 
				{
					require_once("rde_m.php");
				}
					
			}	
				?>	
   	  <?php
			if (!isset($_SESSION['INGRESO']['empresa'])) 
			{
				include('select_empresa.php');
				include('panel2.php');
			}
			else
			{
				 // nuevo contruccion javier               
               if(!isset($_GET['mod']))
               { 
               	$_SESSION['INGRESO']['modulo_']='';
               	$_SESSION['INGRESO']['modulo']=modulos_habiliatados();
				$todo = false;
				foreach ($_SESSION['INGRESO']['modulo'] as  $key => $value) {
					if($value['modulo']=='TO')
					{
						$todo = true;
						break;
					}					
				}
				

				if($todo == true)
				  {
				  	if(!isset($_SESSION['INGRESO']['modulo_']) || $_SESSION['INGRESO']['modulo_']==""){
				  	echo '<div class="row">'.contruir_todos_modulos().'</div>';
				    }
					
				   }else
					{
						// print_r($_SESSION);die();
						if(!isset($_SESSION['INGRESO']['modulo_']) || $_SESSION['INGRESO']['modulo_']==""){
				         echo $l ='<div class="row">'.contruir_modulos($_SESSION['INGRESO']['modulo']).'</div>';
				       }

					}
			}


			}
	  ?>

