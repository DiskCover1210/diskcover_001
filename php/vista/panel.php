<?php  
// header("'Access-Control-Allow-Origin: 'https://srienlinea.sri.gob.ec'");
// header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
// header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
// header("Allow: GET, POST, OPTIONS, PUT, DELETE");
// $method = $_SERVER['REQUEST_METHOD'];
// if($method == "OPTIONS") {
//     die();
// }


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
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="../../lib/bower_components/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../lib/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../lib/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../lib/dist/css/AdminLTE.min.css">

  <!-- AdminLTE Skins. Choose a skin from the css/skins -->
  <link rel="stylesheet" href="../../lib/dist/css/skins/_all-skins.min.css"> 
 
  <!-- jQuery 3 -->
<script src="../../lib/bower_components/jquery/dist/jquery.min.js"></script>
<script src="../../lib/bower_components/bootstrap/js/bootstrap.min.js"></script>

<!-- AdminLTE App -->
<script src="../../lib/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../lib/dist/js/demo.js"></script>

<!-- JS principal para todo el sistema -->
<script src="../../lib/dist/js/principal.js"></script>
<!-- CSS principal para todo el sistema -->
<link rel="stylesheet" href="../../lib/dist/css/principal.css">

<script src="../../lib/dist/js/jquery-ui.js"></script>
<!-- <link rel="stylesheet" href="../../lib/dist/css/jquery-ui.css"> -->
<link rel="stylesheet" href="../../lib/dist/css/jquery-ui.min.css">
<script src="../../lib/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="../../lib/dist/css/select2.min.css">
<link rel="stylesheet" href="../../lib/dist/css/sweetalert.css">
<script src="../../lib/dist/js/sweetalert2.min.js"></script>
<link rel="stylesheet" href="../../lib/dist/css/sweetalert2.min.css">
  <link rel="shortcut icon" href="../../img/jpg/logo.jpg" />
  <style>
       .ui-autocomplete {
            max-height: 200px;
            overflow-y: auto;
            /* prevent horizontal scrollbar */
            overflow-x: hidden;
             z-index:2147483647;
            /* add padding to account for vertical scrollbar */
            padding-right: 20px;
        } 
</style>
<script type="text/javascript">

function validador_correo(imput)
{
    var campo = $('#'+imput).val();   
    var emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
    //Se muestra un texto a modo de ejemplo, luego va a ser un icono
    if (emailRegex.test(campo)) {
      // alert("válido");
      return true;

    } else {
      Swal.fire('Email incorrecto','','info');
      console.log(campo);
      return false;
    }
}
function is_numeric(value) {
	return !isNaN(parseFloat(value)) && isFinite(value);
}

		function cambiar_1(){
			$('#myModal_espera').modal('show');
			var value =  $('#sempresa').val(); 
			separador = "-"; // un espacio en blanco
			limite    = 2;
			arregloDeSubCadenas = value.split(separador, limite);
			text =$('#sempresa option:selected').html(); //El texto de la opción seleccionada
			window.location="panel.php?mos="+value+"&mos1="+text+"&mos3="+arregloDeSubCadenas[1]+"";
		}
		function descargar_archivos(url,archivo)
		{
            var link = document.createElement("a");
            link.download = archivo;
            link.href = url;
            link.click();
    }
    	function validar_cuenta(campo)
	{
		var id = campo.id;
		let cap = $('#'+id).val();
		let cuentaini = cap.replace(/[.]/gi,'');
	//	var cuentafin = $('#txt_CtaF').val();
		var formato = "<?php if(isset($_SESSION['INGRESO']['Formato_Cuentas']))echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>";
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

		function validar_year_mayor(nombre)
		{

			var fecha = $('#'+nombre+'').val();
			var partes = fecha.split('-');
			console.log(partes);
			if(partes[0].length > 4 || partes[0] > 2050)
			{
				$('#'+nombre+'').val('2050-'+partes[1]+'-'+partes[2]);
			}
			

		}
		function validar_year_menor(nombre)
		{

			var fecha = $('#'+nombre+'').val();
			var partes = fecha.split('-');
			console.log(partes);
			if(partes[0] < 2000)
			{
				alert('Año seleccionado menor a 1999');
				$('#'+nombre+'').val('1999-'+partes[1]+'-'+partes[2]);
			}
		}

	function addCommas(nStr) 
		{
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
  }

  String.prototype.ucwords = function() {
			str = this.toLowerCase();
			// return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
			// 	function($1){
			// 		return $1.toUpperCase();
			// 		});
			return str.toUpperCase(); 
		}
function num_caracteres(campo,num)
{
	var val = $('#'+campo).val();
	var cant = val.length;
	console.log(cant+'-'+num);

	if(cant>num)
	{
		$('#'+campo).val(val.substr(0,num));
		return false;
	}

}


  //revisar esta funcion
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
				var sms = $('#Mensaje').val();
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1,sms:sms }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			
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

