<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 * IMPORTANTE la ruta de imagenes deben estar en la raiz del servidor preferiblemente todo el proyecto debe estar en la raiz 
 * no en sub-directorios
 */
//require_once 'determ.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
//include("chequear_seguridad.php"); 
//variables globales
require_once("../db/variables_globales.php");
require_once("../controlador/panel.php");
//enviar correo
require_once("../../lib/phpmailer/PHPMailerAutoload.php");
	//echo ' jjj '.$_SESSION['autentificado'];
	//die();
	//asignamos la empresa
	if(isset($_GET['mos']) and !isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
	{
		?>
			<script>
				//document.body.style.cursor = 'wait';
			</script>
		<?php
		
		$_SESSION['INGRESO']['empresa']=$_GET['mos'];
		//nombre empresa
		$_SESSION['INGRESO']['noempr']=$_GET['mos1'];
		$_SESSION['INGRESO']['item']=$_GET['mos3'];
		//varible para consultar
		$_SESSION['INGRESO']['ninguno']='.';
		$cod = explode("-", $_GET['mos']);
		//echo $cod[0];
		if($cod[0]!='')
		{
			//echo $cod[0].' -- ';
			//die();
			//$('#selector').css('cursor', 'wait');
			
			$empresa=getEmpresasId($cod[0]);
			//print_r($empresa);
			foreach ($empresa as &$valor) 
			{
				$_SESSION['INGRESO']['IP_VPN_RUTA']=$valor['IP_VPN_RUTA'];
				$_SESSION['INGRESO']['Base_Datos']=$valor['Base_Datos'];
				$_SESSION['INGRESO']['Usuario_DB']=$valor['Usuario_DB'];
				$_SESSION['INGRESO']['Contraseña_DB']=$valor['Contraseña_DB'];
				$_SESSION['INGRESO']['Tipo_Base']=$valor['Tipo_Base'];
				$_SESSION['INGRESO']['Puerto']=$valor['Puerto'];
				$_SESSION['INGRESO']['Fecha']=$valor['Fecha'];
				$_SESSION['INGRESO']['Logo_Tipo']=$valor['Logo_Tipo'];
				$_SESSION['INGRESO']['periodo']='.';
				$_SESSION['INGRESO']['Razon_Social']=$valor['Razon_Social'];
				$_SESSION['INGRESO']['Fecha_ce']=$valor['Fecha_CE'];
				//echo $_SESSION['INGRESO']['IP_VPN_RUTA'];
				//obtenemos el resto de inf. de la empresa tales como correo direccion
				$empresa_d=getEmpresasDE($_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['noempr']);
				$_SESSION['INGRESO']['Direccion']=$empresa_d[0]['Direccion'];
				$_SESSION['INGRESO']['Telefono1']=$empresa_d[0]['Telefono1'];
				$_SESSION['INGRESO']['FAX']=$empresa_d[0]['FAX'];
				$_SESSION['INGRESO']['Nombre_Comercial']=$empresa_d[0]['Nombre_Comercial'];
				$_SESSION['INGRESO']['Razon_Social']=$empresa_d[0]['Razon_Social'];
				$_SESSION['INGRESO']['Sucursal']=$empresa_d[0]['Sucursal'];
				$_SESSION['INGRESO']['Opc']=$empresa_d[0]['Opc'];
				$_SESSION['INGRESO']['noempr']=$empresa_d[0]['Empresa'];
				$_SESSION['INGRESO']['S_M']=$empresa_d[0]['S_M'];
				$_SESSION['INGRESO']['Num_CD']=$empresa_d[0]['Num_CD'];
				$_SESSION['INGRESO']['Num_CE']=$empresa_d[0]['Num_CE'];
				$_SESSION['INGRESO']['Num_CI']=$empresa_d[0]['Num_CI'];
				$_SESSION['INGRESO']['Num_ND']=$empresa_d[0]['Num_ND'];		
				$_SESSION['INGRESO']['Num_NC']=$empresa_d[0]['Num_NC'];
				$_SESSION['INGRESO']['Email_Conexion_CE']=$empresa_d[0]['Email_Conexion_CE'];				
				$_SESSION['INGRESO']['Formato_Cuentas']=$empresa_d[0]['Formato_Cuentas'];
				$_SESSION['INGRESO']['porc']=$empresa_d[0]['porc'];
				$_SESSION['INGRESO']['Ambiente']=$empresa_d[0]['Ambiente'];
				$_SESSION['INGRESO']['Obligado_Conta']=$empresa_d[0]['Obligado_Conta'];
				$_SESSION['INGRESO']['LeyendaFA']=$empresa_d[0]['LeyendaFA'];
				$_SESSION['INGRESO']['Email']=$empresa_d[0]['Email'];
				$_SESSION['INGRESO']['RUC']=$empresa_d[0]['RUC'];
				$_SESSION['INGRESO']['Gerente']=$empresa_d[0]['Gerente'];
				$_SESSION['INGRESO']['Det_Comp']=$empresa_d[0]['Det_Comp'];
				$_SESSION['INGRESO']['Signo_Dec']=$empresa_d[0]['Signo_Dec'];
				$_SESSION['INGRESO']['Signo_Mil']=$empresa_d[0]['Signo_Mil'];
				$_SESSION['INGRESO']['Sucursal']=$empresa_d[0]['Sucursal'];
				
				//verificamos si es sql server o mysql para consultar periodos
				if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) ) 
				{
					if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
					{
						$periodo=getPeriodoActualSQL();
						//echo $periodo[0]['Fecha_Inicial'];
						//$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
						//$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
						//buscamos codigo usuario
						$usuario=getUsuario();
						$_SESSION['INGRESO']['CodigoU']=$usuario[0]['CodigoU'];
						$_SESSION['INGRESO']['Nombre_Completo']=$usuario[0]['Nombre_Completo'];
						//verificamos en acceso si puede ingresar a esa empresa
						$_SESSION['INGRESO']['accesoe']='0';
						$_SESSION['INGRESO']['modulo'][0]='0';
						$permiso=getAccesoEmpresas();
					}
					else
					{
						//mysql que se valide en controlador
						//echo ' ada '.$_SESSION['INGRESO']['Tipo_Base'];
						$periodo=getPeriodoActualSQL();
						//echo $periodo[0]['Fecha_Inicial'];
						$usuario=getUsuario();
						$_SESSION['INGRESO']['CodigoU']=$usuario[0]['CodigoU'];
						$_SESSION['INGRESO']['Nombre_Completo']=$usuario[0]['Nombre_Completo'];
						//verificamos en acceso si puede ingresar a esa empresa
						$_SESSION['INGRESO']['accesoe']='0';
						$_SESSION['INGRESO']['modulo'][0]='0';
						$permiso=getAccesoEmpresas();
					}
				}
				
				
			}
			//die();
		}
		?>
			<script>
				//document.body.style.cursor = 'auto';
			</script>
		<?php
	}
	if(!isset($_SESSION['INGRESO']['ninguno'])) 
	{
		$_SESSION['INGRESO']['ninguno']='.';
	}
	if(isset($_GET['mos2'])=='e') 
	{
		//destruimos la sesion
		unset( $_SESSION['INGRESO']['empresa'] ); 
		unset( $_SESSION['INGRESO']['noempr'] );  	
		unset( $_SESSION['INGRESO']['modulo_']);
		unset( $_SESSION['INGRESO']['accion']);
		unset($_SESSION['INGRESO']['IP_VPN_RUTA']);
		unset($_SESSION['INGRESO']['Base_Datos']);
		unset($_SESSION['INGRESO']['Usuario_DB']);
		unset($_SESSION['INGRESO']['Contraseña_DB']);
		unset($_SESSION['INGRESO']['Tipo_Base']);
		unset($_SESSION['INGRESO']['Puerto']);
		unset($_SESSION['INGRESO']['Fecha']);
		unset($_SESSION['INGRESO']['Fechai']);
		unset($_SESSION['INGRESO']['Fechaf']);
		unset($_SESSION['INGRESO']['Logo_Tipo']);
		unset($_SESSION['INGRESO']['Razon_Social']);
		unset($_SESSION['INGRESO']['Direccion']);
		unset($_SESSION['INGRESO']['Telefono1']);
		unset($_SESSION['INGRESO']['FAX']);
		unset($_SESSION['INGRESO']['Nombre_Comercial']);
		unset($_SESSION['INGRESO']['Razon_Social']);
		unset($_SESSION['INGRESO']['S_M']);
		unset($_SESSION['INGRESO']['porc']);
		
		unset($_SESSION['INGRESO']['CodigoU']);
		unset($_SESSION['INGRESO']['Nombre_Completo']);
		//eliminamos filtros
		unset($_SESSION['FILTRO']['cam1']);
		unset($_SESSION['FILTRO']['cam2']);
		unset($_SESSION['FILTRO']['cam3']);
		//eliminar permisos
		unset($_SESSION['INGRESO']['accesoe']);
		unset($_SESSION['INGRESO']['modulo']);
		if($_SESSION['INGRESO']['RUCEnt']=='0590031984001' ) 
		{
			?>
			<script>
			location.href="logout.php";
			</script>
			<?php
		}
	}
	//presionan salir
	if(isset($_GET['sa'])=='s') 
	{
		unset( $_SESSION['INGRESO']['accion']);
		//eliminamos filtros
		unset($_SESSION['FILTRO']['cam1']);
		unset($_SESSION['FILTRO']['cam2']);
		unset($_SESSION['FILTRO']['cam3']);
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
		if($_SESSION['INGRESO']['RUCEnt']=='0590031984001' AND $_SESSION['INGRESO']['Tipo_Usuario']!='user') 
		{
			?>
			<script>
			location.href="logout.php";
			</script>
			<?php
		}
	}
	//para saber si hay accion y colocar menus
	if (isset($_SESSION['INGRESO']['accion'])) 
	{
		$tam_m=11;
	}
	else
	{
		if(isset($_GET['mod'])) 
		{
			$tam_m=11;
		}
		else
		{
			$tam_m=5;
		}
	}		
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <META HTTP-EQUIV="Pragma" CONTENT="no-cache"> <META HTTP-EQUIV="Expires" CONTENT="-1">
   
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
  
  
  <!-- daterange picker 
  <link rel="stylesheet" href="../../lib/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker 
  <link rel="stylesheet" href="../../lib/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs 
  <link rel="stylesheet" href="../../lib/plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker 
  <link rel="stylesheet" href="../../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker 
  <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 
  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
-->
  
  
  
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
  
 <link rel="stylesheet" href="../../lib/dist/css/sweetalert.css">
  <script src="../../lib/dist/js/sweetalert-dev.js"></script>
  
  <script src="../../lib/dist/js/sweetalert2.min.js"></script>
  <script type="text/javascript" src="../../lib/dist/js/typeahead.js"></script>
  <link rel="stylesheet" href="../../lib/dist/css/sweetalert2.min.css">
  <link rel="shortcut icon" href="../../img/jpg/logo.jpg" />
  <style>
.courier {
	font-family: "courier new";
	font-size: 0.8em;
	position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top: 100%;
    left: 0;
    right: 0;
}
/*the container must be positioned relative:*/
.autocomplete {
  position: relative;
  display: inline-block;
}


.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}

.autocomplete-items div {
  padding: 10px;
  h pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}

/*when hovering an item:*/
.autocomplete-items div:hover {
  background-color: #e9e9e9; 
}

/*when navigating through the items using the arrow keys:*/
.autocomplete-active {
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
</style>
<!--    <script src="../../lib/dist/js/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../lib/dist/css/sweetalert2.css"> -->
	<script>
		function sleep(ms) {
		  return new Promise(resolve => setTimeout(resolve, ms));
		}
		async function empezar() {
			$('#cargar').css('cursor', 'wait');
			await sleep(2000);
			//$('#cargar').css('cursor', 'default');
		}
		async function parar() {
			//$('#cargar').css('cursor', 'wait');
			//await sleep(2000);
			$('#cargar').css('cursor', 'default');
		}
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
			//alert(value);
			//redireccionamos
			window.location="panel.php?mos="+value+"&mos1="+text+"&mos3="+arregloDeSubCadenas[1]+"";
		}
		
		function buscar(idMensaje)
		{
			//caso comprobantes procesados
			if(idMensaje=='comproba')
			{
				var select = document.getElementById('mes'); //El <select>
				value = select.value;
				var select = document.getElementById('tipoc'); //El <select>
				value1 = select.value;
				//alert(value);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, MesNo: value, TP: value1 }, function(data){
						$('div.'+idMensaje).html(data); 
						//alert('entrooo '+idMensaje);
					});
			}
			//caso buscar
			if(idMensaje=='comp')
			{
				var select = document.getElementById(idMensaje); //El <select>
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1 }, function(data){
						//$('div.pdfcom').load(data);
						$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						//alert('entrooo '+idMensaje+" ajax/TEMP/'+value1+'.pdf");
					});
			}
			//caso entidad-ciudad
			if(idMensaje=='ciudad')
			{
				//alert(idMensaje+' entro ');
				//solo para el caso de gestion empresa
				var select = document.getElementById('entidad'); //El <select>
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			//caso entidad-empresa-ciudad
			if(idMensaje=='entidad')
			{
				var select = document.getElementById('ciudad'); //El <select>
				value2 = select.value;
				var select = document.getElementById(idMensaje); //El <select>
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1, ciu: value2 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			//caso entidad-empresa
			if(idMensaje=='entidad_u')
			{
				var select = document.getElementById(idMensaje); //El <select>
				var ch = '0';
				var isChecked = document.getElementById('entidadch').checked;
				if(isChecked){
					ch = '1';
				}
				value1 = select.value;
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1, ch: ch }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			//caso entidad-empresa
			if(idMensaje=='usuario')
			{
				var select = document.getElementById(idMensaje); //El <select>
				value1 = select.value;
				//var value2 = document.getElementById('item1').value; //
				//alert(value2);
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			if(idMensaje=='buscarusu')
			{
				var value1 = document.getElementById('entidad_u').value;
				var ch1 = '0';
				var isChecked = document.getElementById('entidadch').checked;
				if(isChecked)
				{
					ch1 = '1';
				}
				var value3 = document.getElementById('usuario').value;
				var ch2 = '0';
				var isChecked = document.getElementById('usuarioch').checked;
				if(isChecked)
				{
					ch2 = '1';
				}
				var ch3 = '0';
				var isChecked = document.getElementById('empresach').checked;
				if(isChecked)
				{
					ch3 = '1';
				}
				var value7 = document.getElementById('empresa').value;
				var value5 = document.getElementById('FechaI').value;
				var value6 = document.getElementById('FechaF').value;
				//alert(value1+' '+value3+' '+value5+' '+value6+' '+value7+' '+ch1+' '+ch2+' '+ch3);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, value1: value1, value3: value3, value5: value5, value6: value6,
					value7: value7, ch1: ch1, ch2: ch2, ch3: ch3 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			//caso empresa
			if(idMensaje=='empresa')
			{
				var select = document.getElementById(idMensaje); //El <select>
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			
		}
	</script>
 
		<script>

		$(document).ready(function () {
			var es_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
		if(es_chrome)
		{
			if(document.URL.indexOf("#")==-1)
            {
               url = document.URL+"#"; 
               location = "#"; 
               location.reload(true); 
           }			    
        }
			$('.navbar .dropdown-item.dropdown').on('click', function (e) {
				var $el = $(this).children('.dropdown-toggle');
				if ($el.length > 0 && $(e.target).hasClass('dropdown-toggle')) {
					var $parent = $el.offsetParent(".dropdown-menu");
					$(this).parent("li").toggleClass('open');
			
					if (!$parent.parent().hasClass('navbar-nav')) {
						if ($parent.hasClass('show')) {
							$parent.removeClass('show');
							$el.next().removeClass('show');
							$el.next().css({"top": -999, "left": -999});
						} else {
							$parent.parent().find('.show').removeClass('show');
							$parent.addClass('show');
							$el.next().addClass('show');
							$el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
						}
						e.preventDefault();
						e.stopPropagation();
					}
					return;
				}
			});

			$('.navbar .dropdown').on('hidden.bs.dropdown', function () {
				$(this).find('li.dropdown').removeClass('show open');
				$(this).find('ul.dropdown-menu').removeClass('show open');
			});

		});
		function cambiar(id){
			var select = document.getElementById(id), //El <select>
			value = select.value; //El valor seleccionado
			//partimos cadenas
			separador = "-"; // un espacio en blanco
			limite    = 2;
			arregloDeSubCadenas = value.split(separador, limite);
			text = select.options[select.selectedIndex].innerText; //El texto de la opción seleccionada
			//alert(value);
			//redireccionamos
			window.location="panel.php?mos="+value+"&mos1="+text+"&mos3="+arregloDeSubCadenas[1]+"";
		}
	
		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
		
		function soloNumeros(e)
		{
			var key = window.Event ? e.which : e.keyCode
			return (key >= 48 && key <= 57)
		}
		function soloNumeros12(e)
		{
			$("#codigo1").hide();
			var key = window.Event ? e.which : e.keyCode
			//alert(key);
			if(key >= 49 && key <= 50)
			{
				 $(this).next().focus();
				 return (key >= 49 && key <= 50);
			}
			
		}
		function soloNumerosDecimales(e)
		{
			var key = window.Event ? e.which : e.keyCode
			return (key <= 13 || (key >= 48 && key <= 57) || key==46)
		}
		function  cerrar(id)
		{
			if(id=='codigo1')
			{
				$( "#moneda" ).focus(function() {
					var bene = document.getElementById('cuenta').value;
					var cod = document.getElementById('codigo').value;
					if('Seleccionar'==bene || bene=='' || bene=='no existe registro' || bene=='undefined' || cod=='0' || cod=='')
					{
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: 'debe agregar cuenta!'
						});
						$("#cuenta").focus();
					}
					//
				});
				$("#"+id).hide();
			}
			else
			{
				$("#"+id).hide();
			}
		}
</script>	
	<script>
	function readCookie(name) {

	  var nameEQ = name + "="; 
	  var ca = document.cookie.split(';');

	  for(var i=0;i < ca.length;i++) {

		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) {
		  return decodeURIComponent( c.substring(nameEQ.length,c.length) );
		}

	  }

	  return null;

	}
	function autocomplete(inp, arr, ina='') {

	    /*the autocomplete function takes two arguments,
	    the text field element and an array of possible autocompleted values:*/
	    var currentFocus;
	    /*execute a function when someone writes in the text field:*/
	    if (ina == '') 
		{
			inp.addEventListener("input", function(e) {
				  var a, b, i, val = this.value;
				  /*close any already open lists of autocompleted values*/
				  closeAllLists();
				  if (!val) { return false;}
				  currentFocus = -1;
				  /*create a DIV element that will contain the items (values):*/
				  a = document.createElement("DIV");
				  a.setAttribute("id", this.id + "autocomplete-list");
				  a.setAttribute("class", "autocomplete-items");
				  /*append the DIV element as a child of the autocomplete container:*/
				  this.parentNode.appendChild(a);
				  /*for each item in the array...*/
				  //alert(arr.length);
				  separador = "-"; // un espacio en blanco
				  limite    = 2;
				for (i = 0; i < arr.length; i++) {
					/*check if the item starts with the same letters as the text field value:*/
					//if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
					  /*create a DIV element for each matching element:*/
					  b = document.createElement("DIV");
					  b.className = "form-control input-sm";
					  //class='form-control input-sm'
					  /*make the matching letters bold:*/
					  b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
					  b.innerHTML += arr[i].substr(val.length);
					  /*insert a input field that will hold the current array item's value:*/
					 //alert(arr[i]);
						arregloDeSubCadenas = arr[i].split(separador, limite);
						if(arregloDeSubCadenas.length>2)
						{
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[2] + "' id='V_1'>";
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[1] + "' id='V_0'>";
						}
						if(arregloDeSubCadenas.length==2)
						{
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[0] + "' id='V_1'>";
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[1] + "' id='V_0'>";
						}
						//  b.innerHTML += "<input type='hidden' value='" + arr[i] + "'  >";
						/*execute a function when someone clicks on the item value (DIV element):*/
						b.addEventListener("click", function(e) {
							
							//selec(this.getElementsByTagName("input")[1].value,document.getElementById('V_1').value);
							selec(this.getElementsByTagName("input")[1].value,this.getElementsByTagName("input")[0].value);
							/*insert the value for the autocomplete text field:*/
							inp.value = this.getElementsByTagName("input")[0].value;
							/*close the list of autocompleted values,
							(or any other open lists of autocompleted values:*/
							closeAllLists();
						});
					  a.appendChild(b);
					//}
				}
			});
		}
		else
		{
			//alert(" pppp ");
			ina1=document.getElementById(ina);
			inp.addEventListener("input", function(e) {
				  var a, b, i, val = this.value;
				  /*close any already open lists of autocompleted values*/
				  closeAllLists();
				  if (!val) { return false;}
				  currentFocus = -1;
				  /*create a DIV element that will contain the items (values):*/
				  a = document.createElement("DIV");
				  a.setAttribute("id", this.id + "autocomplete-list");
				  a.setAttribute("class", "autocomplete-items");
				  /*append the DIV element as a child of the autocomplete container:*/
				 ina1.parentNode.appendChild(a);
				  /*for each item in the array...*/
				  //alert(arr.length);
				  separador = "-"; // un espacio en blanco
				  limite    = 2;
				for (i = 0; i < arr.length; i++) {
					/*check if the item starts with the same letters as the text field value:*/
					//if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
					  /*create a DIV element for each matching element:*/
					  b = document.createElement("DIV");
					  b.className = "form-control input-sm";
					  //class='form-control input-sm'
					  /*make the matching letters bold:*/
					  b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
					  b.innerHTML += arr[i].substr(val.length);
					  /*insert a input field that will hold the current array item's value:*/
					 
						arregloDeSubCadenas = arr[i].split(separador, limite);
						if(arregloDeSubCadenas.length>2)
						{
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[2] + "' id='V_1'>";
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[1] + "' >";
						}
						if(arregloDeSubCadenas.length==2)
						{
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[0] + "' id='V_1'>";
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[1] + "' >";
						}
						//  b.innerHTML += "<input type='hidden' value='" + arr[i] + "'  >";
						/*execute a function when someone clicks on the item value (DIV element):*/
						b.addEventListener("click", function(e) {
							
							selec(this.getElementsByTagName("input")[1].value,document.getElementById('V_1').value);
							
							//selec(this.getElementsByTagName("input")[1].value);
							/*insert the value for the autocomplete text field:*/
							inp.value = this.getElementsByTagName("input")[0].value;
							/*close the list of autocompleted values,
							(or any other open lists of autocompleted values:*/
							closeAllLists();
						});
					  a.appendChild(b);
					//}
				}
			});
		}
		    
	  /*execute a function presses a key on the keyboard:*/
	  inp.addEventListener("keydown", function(e) {
		  var x = document.getElementById(this.id + "autocomplete-list");
		  if (x) x = x.getElementsByTagName("div");
		  if (e.keyCode == 40) {
			/*If the arrow DOWN key is pressed,
			increase the currentFocus variable:*/
			currentFocus++;
			/*and and make the current item more visible:*/
			addActive(x);
		  } else if (e.keyCode == 38) { //up
			/*If the arrow UP key is pressed,
			decrease the currentFocus variable:*/
			currentFocus--;
			/*and and make the current item more visible:*/
			addActive(x);
		  } else if (e.keyCode == 13) {
			/*If the ENTER key is pressed, prevent the form from being submitted,*/
			e.preventDefault();
			if (currentFocus > -1) {
			  /*and simulate a click on the "active" item:*/
			  if (x) x[currentFocus].click();
			}
		  }
	  });
	  function addActive(x) {
		/*a function to classify an item as "active":*/
		if (!x) return false;
		/*start by removing the "active" class on all items:*/
		removeActive(x);
		if (currentFocus >= x.length) currentFocus = 0;
		if (currentFocus < 0) currentFocus = (x.length - 1);
		/*add class "autocomplete-active":*/
		x[currentFocus].classList.add("autocomplete-active");
	  }
	  function removeActive(x) {
		/*a function to remove the "active" class from all autocomplete items:*/
		for (var i = 0; i < x.length; i++) {
		  x[i].classList.remove("autocomplete-active");
		}
	  }
	  function closeAllLists(elmnt) {
		/*close all autocomplete lists in the document,
		except the one passed as an argument:*/
		var x = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < x.length; i++) {
		  if (elmnt != x[i] && elmnt != inp) {
			x[i].parentNode.removeChild(x[i]);
		  }
		}
	  }
	  /*execute a function when someone clicks in the document:*/
	  document.addEventListener("click", function (e) {
		  closeAllLists(e.target);
	  });
	}

	function validar_cuenta(campo)
	{
		var id = campo.id;
		let cap = $('#'+id).val();
		let cuentaini = cap.replace(/[.]/gi,'');
	//	var cuentafin = $('#txt_CtaF').val();
		var formato = "<?php echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>";
		let parte =formato.split('.');
		var nuevo =  new Array(); 
		let cadnew ='';
		for (var i = 0 ; i < parte.length; i++) {

			if(cuentaini.length != '')
			{
				var b = parte[i].length;
				var c = cuentaini.substr(0,b);
				if(c.length==b)
				{
					nuevo[i] = c;
					cuentaini = cuentaini.substr(b);
				}else
				{   
				  if(c != 0){  
					//for (var ii =0; ii<b; ii++) {
						var n = c;
						//if(n.length==b)
						//{
						   //if(n !='00')
						  // {
							nuevo[i] =n;
				            cuentaini = cuentaini.substr(b);
				         //  }
				         //break;
						  
						//}else
						//{
						//	c = n;
						//}
						
					//}
				  }else
				  {
				  	nuevo[i] =c;
				    cuentaini = cuentaini.substr(b);
				  }
				}
			}
		}
		var m ='';
		nuevo.forEach(function(item,index){
			m+=item+'.';
		})
		//console.log(m);
		$('#'+id).val(m);


	}
	
	</script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
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
			  			  <?php
			$host= $_SERVER["HTTP_HOST"];
			$url= $_SERVER["REQUEST_URI"];
			$url = $host . $url;
			//echo $url;
			$cadena_de_texto = 'Esta es la frase donde haremos la búsqueda';
			$bus   = 'panel.php';
			$posicion_coincidencia = strpos($url, $bus);
			 $ban=0;
			//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
			if ($posicion_coincidencia === false) 
			{
				$ban=1;
				//echo "entro";
			} 
			else 
			{
				
			}
			if(isset($_GET['sa'])=='s' OR $ban==0) 
			{
		?>
				
		  <?php
			}
		?>
<!-- Site wrapper -->
<section class="content-wrapper">
  <div class="box box-default">
   <div class="box-body"> 
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
				//reporte facturacion
				if ($_SESSION['INGRESO']['accion']=='fact') 
				{
					require_once("fact_m.php");
				}
					
			}	
				?>	
   	  <div class="loader1">Procesando</div>	
   	  <?php
			
			if (!isset($_SESSION['INGRESO']['empresa'])) 
			{
				include('select_empresa.php');
				include('panel2.php');
			}
			else
			{
					//si esta en panel
					if(isset($_SESSION['INGRESO']['CodigoU']))
					{
						if($ban==0 and $_SESSION['INGRESO']['CodigoU']!='')
						{
							?>
							<script>
								var esVisible = $(".loader1").is(":visible");
								$(".cargando").removeClass('cargando');
							</script>
							<?php
							if($_SESSION['INGRESO']['modulo'][0]=='0')
							{
								$_SESSION['INGRESO']['accesoe']='0';
								
								$permiso=getAccesoEmpresas();
							}
							//echo $_SESSION['INGRESO']['modulo'][0];
							//die();
							//verificamos los accesos
							if(trim($_SESSION['INGRESO']['modulo'][0])=='TODOS' or trim($_SESSION['INGRESO']['modulo'][0])=='T')
							{
						?>
								<!--<h3 class="box-title">Modulos </h3>-->
								
								<br/>
								<div class="box-body">
									<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Ahorros">
										<i ><img src="../../img/gif/Ahorros.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Ahorros
									</a>
									<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Anexos">
										<i ><img src="../../img/gif/Anexos.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Anexos
									</a>
									
									<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Constructor">
										<i ><img src="../../img/gif/Constructor.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Constructor
									</a>-->
								
									
									<a class="btn btn-app" href="contabilidad.php?mod=contabilidad" style='width:60px;' data-toggle="tooltip" title="Contabilidad">
										<i ><img src="../../img/gif/Contabilidad.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Contabilidad
									</a>
									<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Credito">
										<i ><img src="../../img/gif/Credito.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Credito
									</a>-->
																
									<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Factura">
										<i ><img src="../../img/gif/Factura.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Factura
									</a>
									<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Gerencia">
										<i ><img src="../../img/gif/Gerencia.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Gerencia
									</a>
									<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Inventario">
										<i ><img src="../../img/gif/Inventario.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Inventario
									</a>
									<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Mantenimento">
										<i ><img src="../../img/gif/logosMod.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i>Mantenimento
									</a>
									
									<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Respaldo">
										<i ><img src="../../img/gif/Respaldo.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Respaldo
									</a>
									<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Rol de Pago">
										<i ><img src="../../img/gif/RolPago.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> RolPago
									</a>-->
									<a class="btn btn-app" href="educativo.php?mod=educativo&acc=detalle_estudiante&acc1=Detalle%20Estudiante&b=1&po=subcu" style='width:60px;' data-toggle="tooltip" title="Educativo">
										<i ><img src="../../img/gif/Contabilidad.gif" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i> Educativo
									</a>
									<?php
									if($_SESSION['INGRESO']['RUCEnt']=='1792164710001'  AND $_SESSION['INGRESO']['noempr']=='Prismanet Profesional S.A.') 
									{
									?>
										<a class="btn btn-app" href="migra4.php" style='width:60px;' data-toggle="tooltip" title="Migracion">
											<i class="fa fa-fw fa-server"></i> Migracion
										</a>
										<a class="btn btn-app" href="reindexar.php" style='width:60px;' data-toggle="tooltip" title="Re-indexar">
											<i class="fa fa-fw fa-server"></i> Re-indexar
										</a>

									<?php
									}
									?>
									<a class="btn btn-app" href="afe.php?mod=contabilidad" style='width:60px;' data-toggle="tooltip" title="Autorizacion Factura Electronica">
										<i class="fa fa-fw fa-file-excel-o"></i> Aut. Fact. Elec.
									</a>
									<a class="btn btn-app" href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electrónico" style='width:60px;' data-toggle="tooltip" title="Reporte Documento Electronico">
										<i class="fa fa-fw fa-file-excel-o"></i> Rep. Doc. Elect.
									</a>
									<a class="btn btn-app" href="fact.php?mod=contabilidad&acc=fact&acc1=Reporte Facturación" style='width:60px;' data-toggle="tooltip" title="Reporte Facturas">
										<i class="fa fa-fw fa-file-excel-o"></i> Rep. Fact
									</a>
									<?php
									if($_SESSION['INGRESO']['RUCEnt']=='1792164710001'  AND $_SESSION['INGRESO']['noempr']=='Prismanet Profesional S.A.' ) 
									{
									?>
										<a class="btn btn-app" href="empresa.php?mod=empresa&acc=cambioe&acc1=Modificar empresa" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
											<i class="fa fa-fw fa-industry"></i> Admi. Empre
										</a>
										<a class="btn btn-app" href="empresa.php?mod=empresa&acc=cambiou&acc1=Administrar Usuario" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
											<i class="fa fa-fw fa-users"></i> Admi. User
										</a>
									
										
									<?php
									}
									?>
									<?php
										$ove=200;
									?>
									<button type="button"  class="btn btn-app" data-toggle="modal" style='width:60px;' data-target="#myModal_doc" title="APPR">
										<!--<i class="fa fa-fw fa-spoon"></i>-->
										<i><img src="../../img/jpg/logo_doc.jpg" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:80%;'></i>DOC
									</button>
									<div class="modal fade" id="myModal_doc" role="dialog" >
										<div class="modal-dialog" style="width:50%;">
										  <div class="modal-content" style="width:50%;">
											<div class="modal-header">
											  <button type="button" class="close" data-dismiss="modal">&times;</button>
											  <h4 class="modal-title"><img  width='5%'  height='5%' src="../../../img/jpg/logo.jpg">DOC MENU </h4>
											</div>
											<div class="modal-body" style="height:<?php echo $ove; ?>px;overflow-y: scroll;">
												<div class="form-group">
													<div id='botones'>
														<a class="btn btn-app" href="appr/mesa.php?mod=appr&acc=cambiou&acc1=Administrar Usuario" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
															<!--<i class="fa fa-fw fa-spoon"></i>-->
															<i><img src="../../img/jpg/logo_doc.jpg" class="user-image" alt="User Image"
															style='font-size:20px; display:block; height:100%; width:80%;'></i>DOC MESAS
														</a>
														<a class="btn btn-app" href="appr/panel1.php?mod=appr&acc=cambiou&acc1=Administrar Usuario" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
															<!--<i class="fa fa-fw fa-spoon"></i>-->
															<i><img src="../../img/jpg/logo_doc.jpg" class="user-image" alt="User Image"
															style='font-size:20px; display:block; height:100%; width:80%;'></i>DOC ESTATUS
														</a>
													</div>
												</div>
											</div>
											<div class="modal-footer">
												<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
											</div>
										  </div>
										  
										</div>
									</div>
									<script>
										function abrir_doc(id,nom)
										{
											$("#myModal_doc").modal();		
										}
									</script>
								</div>
						<?php
							}
							else
							{
								//verificamos sesion sql
								if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
								{
									//echo "entroooooo   ";
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
									//echo $_SESSION['INGRESO']['CodigoU'].' '.$_SESSION['INGRESO']['Nombre_Completo'].' ';
									$permiso=getAccesoEmpresas();
									//echo $_SESSION['INGRESO']['modulo'][0];
								}
								//verificamos que modulos habilitar
								$_SESSION['INGRESO']['permiso']['cont']=0;
								$_SESSION['INGRESO']['permiso']['fact']=0;
								$_SESSION['INGRESO']['permiso']['anexo']=0;
								$_SESSION['INGRESO']['permiso']['ahorro']=0;
								$_SESSION['INGRESO']['permiso']['constru']=0;
								$_SESSION['INGRESO']['permiso']['gerenc']=0;
								$_SESSION['INGRESO']['permiso']['inven']=0;
								$_SESSION['INGRESO']['permiso']['seteos']=0;
								$_SESSION['INGRESO']['permiso']['respa']=0;
								$_SESSION['INGRESO']['permiso']['rpagos']=0;
								$_SESSION['INGRESO']['permiso']['appr']=0;
								$_SESSION['INGRESO']['permiso']['educa']=0;
								//llamamos al modulo
								$modulo=getModulo();
								$jj=0;
								for($i=0;$i<count($_SESSION['INGRESO']['modulo']);$i++)
								{
									for($j=0;$j<count($modulo);$j++)
									{
										if($_SESSION['INGRESO']['modulo'][$i]==$modulo[$j]['Modulo'])
										{
											
										}
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='01')
									{
										$_SESSION['INGRESO']['permiso']['cont']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='02')
									{
										$_SESSION['INGRESO']['permiso']['fact']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='21')
									{
										$_SESSION['INGRESO']['permiso']['anexo']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='04')
									{
										$_SESSION['INGRESO']['permiso']['ahorro']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='13')
									{
										$_SESSION['INGRESO']['permiso']['constru']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='15')
									{
										$_SESSION['INGRESO']['permiso']['gerenc']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='03')
									{
										$_SESSION['INGRESO']['permiso']['inven']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='26')
									{
										$_SESSION['INGRESO']['permiso']['seteos']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='07')
									{
										$_SESSION['INGRESO']['permiso']['respa']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='10')
									{
										$_SESSION['INGRESO']['permiso']['rpagos']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='33')
									{
										$_SESSION['INGRESO']['permiso']['appr']=1;
									}
									if($_SESSION['INGRESO']['modulo'][$i]=='11')
									{
										$_SESSION['INGRESO']['permiso']['educa']=1;
									}

								}
								?>
								<br/>
								<div class="box-body">
									<?php
									if($_SESSION['INGRESO']['permiso']['ahorro']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Ahorros">
											<i ><img src="../../img/gif/Ahorros.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Ahorros
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Ahorros" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/Ahorros.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Ahorros
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['anexo']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Anexos">
											<i ><img src="../../img/gif/Anexos.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Anexos
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Anexos" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%; '></div>
											<i ><img src="../../img/gif/Anexos.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Anexos
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['constru']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Constructor">
											<i ><img src="../../img/gif/Constructor.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Constructor
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Constructor" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/Constructor.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Constructor
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['cont']=='1') 
									{
									?>
										<a class="btn btn-app" href="contabilidad.php?mod=contabilidad" style='width:60px;' data-toggle="tooltip" 
										title="Contabilidad">
											<i ><img src="../../img/gif/Contabilidad.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Contabilidad
										</a>
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" href="#" style='width:60px;' data-toggle="tooltip" title="Contabilidad" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/Contabilidad.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Contabilidad
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['fact']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Factura">
											<i ><img src="../../img/gif/Factura.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Factura
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Factura" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/Factura.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Factura
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['gerenc']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Gerencia">
											<i ><img src="../../img/gif/Gerencia.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Gerencia
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Gerencia" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/Gerencia.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Gerencia
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['educa']=='1') 
									{
									?>
										<a class="btn btn-app" href="educativo.php?mod=educativo&acc=detalle_estudiante&acc1=Detalle%20Estudiante&b=1&po=subcu" style='width:60px;' data-toggle="tooltip" 
										title="Educativo">
											<i ><img src="../../img/gif/Contabilidad.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Educativo
										</a>
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" href="#" style='width:60px;' data-toggle="tooltip" title="Educativo" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/Contabilidad.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Educativo
										</a>
									<?php
									}
									
									if($_SESSION['INGRESO']['permiso']['inven']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Inventario">
											<i ><img src="../../img/gif/Inventario.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Inventario
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Inventario" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/Inventario.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Inventario
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['seteos']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Mantenimento">
											<i ><img src="../../img/gif/logosMod.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i>Mantenimento
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Mantenimento" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/logosMod.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i>Mantenimento
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['respa']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Respaldo">
											<i ><img src="../../img/gif/Respaldo.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Respaldo
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Respaldo" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i ><img src="../../img/gif/Respaldo.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> Respaldo
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['permiso']['rpagos']=='1') 
									{
									?>
										<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Rol de Pago">
											<i ><img src="../../img/gif/RolPago.gif" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i> RolPago
										</a>-->
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Rol de Pago" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<i>
												<img src="../../img/gif/RolPago.gif" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:100%; width:80%;'>
											</i> RolPago
										</a>
									<?php
									}
									if($_SESSION['INGRESO']['permiso']['appr']=='1') 
									{
										$ove=200;
									?>										
									<button type="button"  class="btn btn-app" data-toggle="modal" style='width:60px;' data-target="#myModal_doc" title="APPR">
											<!--<i class="fa fa-fw fa-spoon"></i>-->
											<i><img src="../../img/jpg/logo_doc.jpg" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i>DOC
									</button>
										<div class="modal fade" id="myModal_doc" role="dialog" >
											<div class="modal-dialog" style="width:80%;">
											  <div class="modal-content" style="width:80%;">
												<div class="modal-header">
												  <button type="button" class="close" data-dismiss="modal">&times;</button>
												  <h4 class="modal-title"><img  width='5%'  height='5%' src="../../../img/jpg/logo.jpg">DOC MENU </h4>
												</div>
												<div class="modal-body" style="height:<?php echo $ove; ?>px;overflow-y: scroll;">
													<div class="form-group">
														<div id='botones'>
															<a class="btn btn-app" href="appr/mesa.php?mod=appr&acc=cambiou&acc1=Administrar Usuario" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
																<i class="fa fa-fw fa-spoon"></i>
																DOC MESAS
															</a>
															<a class="btn btn-app" href="appr/panel1.php?mod=appr&acc=cambiou&acc1=Administrar Usuario" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
																<!--<i class="fa fa-fw fa-spoon"></i>-->
																<i><img src="../../img/jpg/logo_doc.jpg" class="user-image" alt="User Image"
																style='font-size:20px; display:block; height:100%; width:80%;'></i>DOC ESTATUS
															</a>
														</div>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
												</div>
											  </div>
											  
											</div>
										</div>										
									<?php
									}
									else
									{
									?>
										<a class="btn btn-app" href="#" style='width:60px;' data-toggle="tooltip" title="APP" disabled>
											<div style='float: right;position:absolute;top: 1%;left: 70%;'><img src="../../img/png/bloqueo.png" class="user-image" alt="User Image"
													style='font-size:20px; display:block; height:80%; width:80%;'></div>
											<!--<i class="fa fa-fw fa-spoon"></i>-->
											<i ><img src="../../img/jpg/logo_doc.jpeg" class="user-image" alt="User Image"
											style='font-size:20px; display:block; height:100%; width:80%;'></i>DOC
										</a>
									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['RUCEnt']=='1792164710001'  AND $_SESSION['INGRESO']['noempr']=='Prismanet Profesional S.A.') 
									{
									?>
										<a class="btn btn-app" href="migra4.php" style='width:60px;' data-toggle="tooltip" title="Migracion">
											<i class="fa fa-fw fa-server"></i> Migracion
										</a>
										<a class="btn btn-app" href="reindexar.php" style='width:60px;' data-toggle="tooltip" title="Re-indexar">
											<i class="fa fa-fw fa-server"></i> Re-indexar
										</a>

									<?php
									}
									?>
									<?php
									if($_SESSION['INGRESO']['RUCEnt']=='1792164710001'  AND $_SESSION['INGRESO']['noempr']=='Prismanet Profesional S.A.' ) 
									{
									?>
										<a class="btn btn-app" href="empresa.php?mod=empresa&acc=cambioe&acc1=Modificar empresa" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
											<i class="fa fa-fw fa-industry"></i> Admi. Empre
										</a>
										<a class="btn btn-app" href="empresa.php?mod=empresa&acc=cambiou&acc1=Administrar Usuario&ti=Administración de usuario" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
											<i class="fa fa-fw fa-users"></i> Admi. User
										</a>
									<?php
									}
									?>
								</div>
								 <script>
								  $(".loader1").hide();
								 </script>
								<?php
							}	

									include('panel2.php');						
						}
					}
			}
	  ?>
	  <script>
		$(".loader1").hide();
	</script>
   
  