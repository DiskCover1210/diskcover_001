<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
include("../chequear_seguridad.php"); 
require_once("../../controlador/panelr.php");
//enviar correo
require_once("../../../lib/phpmailer/antiguo/PHPMailerAutoload.php");

include('controlador/controladormesa.php');
//require('modelo/modelomesa.php');
//facturacion


// print_r($_SESSION['INGRESO']);die();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Doc</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="lib/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="lib/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="lib/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="lib/dist/css/skins/_all-skins.min.css">
  <link rel="shortcut icon" href="../../../img/jpg/logo.jpg" />
  


  <link rel="stylesheet" href="lib/bower_components/jquery-ui/themes/base/jquery-ui.css">


  <!-- jQuery 3 -->
<script src="lib/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="lib/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="lib/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="lib/plugins/input-mask/jquery.inputmask.js"></script>
<script src="lib/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="lib/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="lib/bower_components/moment/min/moment.min.js"></script>
<script src="lib/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="lib/bower_components/jquery-ui/jquery-ui.js"></script>

<!-- bootstrap datepicker -->

<!-- bootstrap color picker -->
<script src="lib/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="lib/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="lib/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="lib/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="lib/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="lib/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="lib/dist/js/demo.js"></script>
  
 <link rel="stylesheet" href="lib/dist/css/sweetalert.css">
  <script src="lib/dist/js/sweetalert-dev.js"></script>
  
  <script src="lib/dist/js/sweetalert2.min.js"></script>
  <script type="text/javascript" src="lib/dist/js/typeahead.js"></script>
  <link rel="stylesheet" href="lib/dist/css/sweetalert2.min.css">

<script src="../../../lib/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="../../../lib/dist/css/select2.min.css">
 

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
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
	</script>
	<script>
	$(document).ready(function () {
	$('.sidebar-menu').tree()
	})
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
	</script>
	<!-- <script type="text/javascript">
		$(document).ready(function () {
			$('#nombrec').select2({
             placeholder: 'Digite Clinete',
             ajax: {
               url: 'controlador/controladormesa.php?buscarcli=true',
               dataType: 'json',
               delay: 250,
               processResults: function (data) {
                 return {
                   results: data
                 };
               },
               cache: true
             }
           });
		});
		
	</script> -->
</head>
<!-- class="hold-transition skin-blue sidebar-mini" -->
<body class="skin-blue sidebar-mini sidebar-collapse" id='cargar'>
	<?php 

	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) && $_SESSION['INGRESO']['Tipo_Base'] =='SQL SERVER') 
{

//variables para autorizar
$IP_VPN_RUTA = $_SESSION['INGRESO']['IP_VPN_RUTA'];
$Base_Datos = $_SESSION['INGRESO']['Base_Datos'];
$Usuario_DB = $_SESSION['INGRESO']['Usuario_DB'];
$Contraseña_DB = $_SESSION['INGRESO']['Contraseña_DB'];
$Tipo_Base = $_SESSION['INGRESO']['Tipo_Base'];
$Puerto = $_SESSION['INGRESO']['Puerto'];
$ruta_cer=$_SESSION['INGRESO']['Ruta_Certificado'];
$clave_cer=$_SESSION['INGRESO']['Clave_Certificado'];
$ambiente=$_SESSION['INGRESO']['Ambiente'];
$nom_com=$_SESSION['INGRESO']['Nombre_Comercial'];
$raz_soc=$_SESSION['INGRESO']['Razon_Social'];
$ruc = $_SESSION['INGRESO']['RUC'];
$dir = $_SESSION['INGRESO']['Direccion'];

//Encripta información:
$IP_VPN_RUTA = $encriptar($IP_VPN_RUTA);
$Base_Datos = $encriptar($Base_Datos);
$Usuario_DB = $encriptar($Usuario_DB);
$Contraseña_DB = $encriptar($Contraseña_DB);
$Tipo_Base = $encriptar($Tipo_Base);
$Puerto = $encriptar($Puerto);
$ruta_cer = $encriptar($ruta_cer);
$clave_cer = $encriptar($clave_cer);
$ambiente = $encriptar($ambiente);
$nom_com = $encriptar($nom_com);
$raz_soc = $encriptar($raz_soc);
$ruc = $encriptar($ruc);
$dir = $encriptar($dir);
$item = $encriptar($_SESSION['INGRESO']['item']);
$periodo = $encriptar($_SESSION['INGRESO']['periodo']);
//tipo documkento
$cod_doc = $encriptar('01');
$tc=$encriptar('FA');
$_SESSION['INGRESO']['modulo_']='02';
}else
{
	echo "<script>
	$(document).ready(function () {
	
				Swal.fire({
				  type: 'error',
				  title: 'Asegurese de tener credeciales de SQLSERVER',
				  text: '',
				  allowOutsideClick:false,
				}).then((result) => {
				  if (result.value) {
					location.href='../panel.php#';
				  } 
				});
				$('#mesas').css('display','none')
	})
			</script>";
}


	?>

<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="../panel.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b>C</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Disk</b>Cover</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
			 
											
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../../img/jpg/sinimagen.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"> 
              	<?php	echo $_SESSION['INGRESO']['Nombre'];?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <p>
                  <?php	echo $_SESSION['INGRESO']['Nombre']; ?>
                  <small></small>
                </p>
              </li>
             
             
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                    <a href="../logout.php"  data-toggle="tooltip"  data-placement="bottom" title="Cerrar sesión" style=' float: right'>
						<img src="../../../img/png/salirs.png"  width='70%' height='50%'>
					</a>
                </div>
                <div class="pull-right">
                    <a href="../panel.php?mos2=e"  data-toggle="tooltip"  data-placement="bottom" title="Salir de empresa" style=' float: right'>
						<img src="../../../img/png/salire.png"  width='70%' height='50%'>
					</a>
                </div>
              </li>
            </ul>
          </li>
          
        </ul>
      </div>
    </nav>
  </header>
  <div class="content-wrapper">
    	
    	    
	<?php
		$_SESSION['APPR']['MESAS']=array();
		//llenamos las mesas
		$_SESSION['APPR']['MESAS'][0]['nombre']='Mesa 1';
		$_SESSION['APPR']['MESAS'][0]['codigo']='1';
		$_SESSION['APPR']['MESAS'][0]['disp']='1';
		
		$_SESSION['APPR']['MESAS'][1]['nombre']='Mesa 2';
		$_SESSION['APPR']['MESAS'][1]['codigo']='2';
		$_SESSION['APPR']['MESAS'][1]['disp']='1';
		
		$_SESSION['APPR']['MESAS'][2]['nombre']='Mesa 3';
		$_SESSION['APPR']['MESAS'][2]['codigo']='3';
		$_SESSION['APPR']['MESAS'][2]['disp']='1';
		
		$_SESSION['APPR']['MESAS'][3]['nombre']='Mesa 4';
		$_SESSION['APPR']['MESAS'][3]['codigo']='4';
		$_SESSION['APPR']['MESAS'][3]['disp']=0;
		
		$_SESSION['APPR']['MESAS'][4]['nombre']='Mesa 5';
		$_SESSION['APPR']['MESAS'][4]['codigo']='5';
		$_SESSION['APPR']['MESAS'][4]['disp']='2';
		
		//llenamos las productos
		$_SESSION['APPR']['PROD']=array();
		$_SESSION['APPR']['PROD'][0]['nombre']='producto 1';
		$_SESSION['APPR']['PROD'][0]['codigo']='1';
		$_SESSION['APPR']['PROD'][0]['disp']='1';
		$_SESSION['APPR']['PROD'][0]['precio']='2';
		
		$_SESSION['APPR']['PROD'][1]['nombre']='producto 2';
		$_SESSION['APPR']['PROD'][1]['codigo']='2';
		$_SESSION['APPR']['PROD'][1]['disp']='1';
		$_SESSION['APPR']['PROD'][1]['precio']='2.5';
		
		$_SESSION['APPR']['PROD'][2]['nombre']='producto 3';
		$_SESSION['APPR']['PROD'][2]['codigo']='3';
		$_SESSION['APPR']['PROD'][2]['disp']='1';
		$_SESSION['APPR']['PROD'][2]['precio']='1.5';
		
		$_SESSION['APPR']['PROD'][3]['nombre']='producto 4';
		$_SESSION['APPR']['PROD'][3]['codigo']='4';
		$_SESSION['APPR']['PROD'][3]['disp']=0;
		$_SESSION['APPR']['PROD'][3]['precio']='1';
		
		$_SESSION['APPR']['PROD'][4]['nombre']='producto 5';
		$_SESSION['APPR']['PROD'][4]['codigo']='5';
		$_SESSION['APPR']['PROD'][4]['disp']='0';
		$_SESSION['APPR']['PROD'][4]['precio']='2.3';
		
		//pedidos
		$_SESSION['APPR']['PED']=array();
		
		$ove=400;
	?>
     <section class="content">
     	<div class="row">
     		<div id="mesas">
     		<?php $con = new MesaCon();
     		// echo "<script>alert('sss')</script>";
     		// print_r($_SESSION['INGRESO']);
     		echo $con->cargar_todas_mesas();  ?> 
     		</div>    	
    	</div>
    	
    </section>
  </div>
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <!--<b>Version</b> 2.4.0-->
    </div>
    <strong>Copyright &copy;  <a href="https://erp.diskcoversystem.com">prismanet profesional</a>.</strong> todos los derechos reservados.
  </footer>

 
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>
	$(document).ready(function () {
		var parametros = 
		{
			"ajax_page": 'pop2',
			cl: 'mes2'
			
		};
		$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			beforeSend: function () {
					$("#pdfcom1").html("");
			},
			success:  function (response) {
					$("#pdfcom1").html(response);
					//$("#myModal").modal();					
			}
		});
	});

	 setInterval(function(){ 		
		$("#mesas").load(" #mesas");
	 }, 5000);

			

	function entregar(cod,me,nom,pro,cant)
	{
		//alert(cod+' '+me+' '+pro+' '+cant);
		var parametros = 
		{
			"me" : me,
			"pro" : pro,
			"cant" : cant,
			"cod_i": cod,
			"ajax_page": 'env',
			cl: 'env2'
		};
		$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			beforeSend: function () {
					
			},
			success:  function (response) {
				verpedido(me,nom);
				agregar_n(me,nom,'f_pro');	
			}
		});
	}
	function eliminarpro(id,nom,cod_p,cant,cod)
	{
		var parametros = 
		{
			"me" : id,
			"nom" : nom,
			"cod_p" : cod_p,
			"cant" : cant,
			"cod" : cod,
			
		};
		$.ajax({
			data:  {parametros:parametros},
			url:   'controlador/controladormesa.php?eliminar=true',
			type:  'post',
			beforeSend: function () {
					
			},
			success:  function (response) {
				if(response==1)
				{
					//alert('Articulo eliminado');
					agregar_n(id,nom,'f_pro');
				}else
				{
					alert(response);
				}			
			}
		});
	}
	function liberar(me)
	{
		$.ajax({
			url:   'controlador/controladormesa.php?liberar=true&me='+me,
			type:  'post',
			beforeSend: function () {
					
			},
			success:  function (response) {
						
			}
		});
	}
	function prefact2(me,nom)
	{
		var nombrec=document.getElementById('nombrec2').value;
		var ruc=document.getElementById('ruc').value;
		var email=document.getElementById('email').value;
		if(nombrec !='')
		{
		
		//var ca = document.getElementById('num_com1').value;
		var ca = me;
		$.post('ajax/vista_ajax.php'
		, {ajax_page: 'rfac', com: ca,
		nombrec: nombrec,
		ruc: ruc,
		email: email,
		me: me,
		nom: nom,
		cl: 'pre1' }, function(data){
			//$('div.pdfcom').load(data);
			var botones = "<button class='btn btn-primary' onclick='agregar_n(\""+me+"\",\""+nom+"\",\"\");'> Ir a mesa</button>";
			var html= "<div class='row'><div class='col-sm-12 text-right'></div><div class='col-sm-12'><iframe style='width:100%; height:50vw;'' src='../../controlador/imprimir_factura.php?tipo=PF' frameborder='0' allowfullscreen></iframe></div></div>"


			$('#contenido').html(html); 
			$('#botones').html(botones); 
			$("#myModal").modal();
			//alert('entrooo '+idMensaje+" ajax/TEMP/'+value1+'.pdf");
		});
	 }else
	 {
	 	alert('Escoja un cliente');
	 }
	}

	function prefact22(me,nom)
	{ 
		var nombrec='consumidor final';
		var ruc='9999999999999';
		var email='.';
		var parametros = 
		{
			"nombrec" : nombrec,
			"ruc" : ruc,
			"email" : email,
			"nom" : nom,
			"me" : me
		};
		$.ajax({
			data:  parametros,
			url:   'controlador/controladormesa.php?pdf=true&me='+me,
			
			type:  'post',
			beforeSend: function () {
					$("#contenido").html("");
			},
			success:  function (response) {
			ventana = window.open("ajax/TEMP/"+me+".pdf", "nuevo", "width=400,height=400");
			ventana.close();
			var html='<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+me+'.pdf" frameborder="0" allowfullscreen></iframe>';

			$('#contenido').html(html); 
			$("#myModal").modal();				
			}
		});
		//$('#pdfcom').html(''+id+''); 	
	}

	function imprimir_ticket(mesa)
	{
		var html='<iframe style="width:100%; height:50vw;" src="../appr/controlador/imprimir_ticket.php?mesa='+mesa+'&tipo=PF" frameborder="0" allowfullscreen></iframe>';
		$('#contenido').html(html); 
			$("#myModal").modal();
	}
	function imprimir_ticket_fac(mesa,ci,fac,serie)
	{
		var html='<iframe style="width:100%; height:50vw;" src="../appr/controlador/imprimir_ticket.php?mesa='+mesa+'&tipo=FA&CI='+ci+'&fac='+fac+'&serie='+serie+'" frameborder="0" allowfullscreen></iframe>';
		$('#contenido').html(html); 
			$("#myModal").modal();
	}

	function prefact221(me,nom)
	{   var botones = "<button class='btn btn-primary' onclick='agregar_n(\""+me+"\",\""+nom+"\",\"\");'> Regresar</button>";
		var html= "<div class='row'><div class='col-sm-12'><iframe style='width:100%; height:50vw;'' src='../../controlador/imprimir_factura.php?tipo=PF' frameborder='0' allowfullscreen></iframe></div></div>"


			$('#contenido').html(html);
			$('#botones').html(botones); 
			$("#myModal").modal();
	}

	function prefact(me,nom)
	{
		//alert(me);
		var parametros = 
		{
			"me" : me,
			"nom" : nom,
		};
		$.ajax({
			data:  parametros,
			url:   'controlador/controladormesa.php?factura=true',
			type:  'post',
			beforeSend: function () {
					$("#contenido").html("");
			},
			success:  function (response) {
					$("#contenido").html(response);
					$("#myModal").modal();					
			}
		});
		//$('#pdfcom').html(''+id+''); 
		
	}
	function eliminarabo(monto,comprob,cta)
	{
		var cli=document.getElementById('ruc').value;
		var me=document.getElementById('me').value;
		var nombrec=document.getElementById('nombrec2').value;
		var email=document.getElementById('email').value;
		var nom=document.getElementById('nom').value;
		var monto=monto;
		var dir = $('#dir').val();
		var tel = $('#tel').val();
		var comprob=comprob;
		var cta=cta;
		
		var parametros = 
		{
			"me" : me,
			"nom" : nom,
			"nombrec": nombrec,
			"ruc": cli,
			"email": email,
			"monto": monto,
			"comprob": comprob,
			"cta": cta,
			'dir':dir,
			'tel':tel,
		};
		$.ajax({
			data:  {parametros:parametros},
			url:   'controlador/controladormesa.php?eliminarabo=true',
			type:  'post',
			beforeSend: function () {
					//$("#contenido").html("");
			},
			success:  function (response) {
					$("#contenido").html(response);
					//$("#myModal").modal();	
				var parametros = 
				{
					"me" : me,
					"nom" : nom,
					"nombrec": nombrec,
					"ruc": cli,
					"email": email,
					'dir':dir,
					'tel':tel,
				};
				$.ajax({
					data:  parametros,
					url:   'controlador/controladormesa.php?factura=true',
					type:  'post',
					beforeSend: function () {
							$("#contenido").html("");
					},
					success:  function (response) {
							$("#contenido").html(response);
							$("#myModal").modal();					
					}
				});	
			}
		});	
	}
	function guardac1(event,me,cli,nom)
	{
		var codigo = event.which || event.keyCode;
		//var cl=document.getElementById(cli).value;
		//alert(codigo);
		if(codigo === 13 || codigo === 1)
		{
			var cli=document.getElementById('ruc').value;
			var telefono=document.getElementById('telefono').value;
			var codigoc=document.getElementById('codigoc').value;
			var nombrec=document.getElementById('nombrec2').value.toUpperCase();
			var email=document.getElementById('email').value.toLowerCase();
			var direccion=document.getElementById('direccion').value;
			var nv=document.getElementById('nv').value;
			var naciona=document.getElementById('naciona').value;
			var prov=document.getElementById('prov').value;
			var ciu=document.getElementById('ciu').value;
			var TC=document.getElementById('TC').value;
			var grupo=document.getElementById('grupo').value;
			var bus=document.getElementById('buscar').value;
			document.getElementById("e_ruc").style.display = "none";
			document.getElementById("e_telefono").style.display = "none";
			document.getElementById("e_codigoc").style.display = "none";
			document.getElementById("e_nombrec").style.display = "none";
			document.getElementById("e_direccion").style.display = "none";
			if(cli=='' || telefono==''  || codigoc==''  || nombrec=='' || direccion=='')
			{
				Swal.fire({
					type: 'error',
					title: 'Oops...',
					text: 'debe agregar Los datos obligatorios (*)'
				});
				if(cli=='')
				{
					document.getElementById("e_ruc").style.display = "block";
				}
				if(telefono=='')
				{
					document.getElementById("e_telefono").style.display = "block";
				}
				if(codigoc=='')
				{
					document.getElementById("e_codigoc").style.display = "block";
				}
				if(nombrec=='')
				{
					document.getElementById("e_nombrec").style.display = "block";
				}
				if(direccion=='')
				{
					document.getElementById("e_direccion").style.display = "block";
				}
			}
			else
			{
				//alert(me+' '+cli+' '+telefono+' '+codigoc+' '+nombrec+' '+email+' '+direccion+' '+nv+' '+naciona+' '+prov+' '+ciu+' '+TC);
				var parametros = 
					{
						"me" : me,
						"ajax_page": 'cli1',
						cl: 'cli2',
						cli: cli,
						telefono: telefono,
						codigoc: codigoc,
						nombrec: nombrec,
						email: email,
						direccion: direccion,
						nv: nv,
						naciona: naciona,
						prov: prov,
						ciu: ciu,
						TC: TC,
						grupo: grupo
					};
					$.ajax({
						data:  parametros,
						url:   'ajax/vista_ajax.php',
						type:  'post',
						beforeSend: function () {
								$("#contenido").html("");
						},
						success:  function (response) 
						{
							var parametros = 
							{
								"me" : me,
								"nom" : nom,
								"nombrec": nombrec,
								"ruc": cli,
								"email": email
							};
							$.ajax({
								data:  parametros,
								url:   'controlador/controladormesa.php?factura=true',
								type:  'post',
								beforeSend: function () {
										$("#contenido").html("");
								},
								success:  function (response) {
										$("#contenido").html(response);
										$("#myModal").modal();					
								}
							});							
						}
					});
			}
		}
		
	}
	function filtro_prod(id,nom,id_) 
	{
		agregar_n(id,nom,id_)
		/*var vari=document.getElementById(id_).value;
		alert(vari);*/
	}
	function mos_ocu(id,id_) 
	{
		
		var vari=document.getElementById(id_).value;
		var arreglo = vari.split("-");
		//alert(id+' '+arreglo[1]+' gg '+arreglo[0]);
		if(arreglo[1]=='TJ' || arreglo[1]=='BA')
		{
			document.getElementById(""+id).style.display = "block";
		}
		else
		{
			document.getElementById(""+id).style.display = "none";
		}
	}

//programacion realizada por javier Farinango 
	function facturar1(me,nom)
	{
		var cli= $('#ruc').val(); 
		var nombrec=$('#nombrec2').val();
		var email=$('#email').val();
		var ser=$('#ser').val();
		var n_fac=$('#n_fac').val();
		var total_total_=$('#total_total_').val();
		var total_abono=$('#total_abono').val();
		var propina_a=$('#propina_a').val();
		//alert(propina_a);
		if(total_total_>total_abono)
		{
			Swal.fire({
				type: 'error',
				title: 'debe abonar mas del monto total: $'+total_total_+'  para generar Factura',
				text: ''
			});
		}			
		else
		{
			
		$('#myModal_espera').modal('show');
			var parametros =
			{
				"me" : me,
				"nom" : nom,
				"nombrec": nombrec,
				"ruc": cli,
				"email": email,
				"ser" : ser,
				"n_fac" : n_fac,
				"total_total_" : total_total_,
				"total_abono" : total_abono,
				"propina_a" : propina_a
			};
			$.ajax({
				data:  parametros,
				url:   'controlador/controladormesa.php?facturar=true',
				type:  'post',
				// beforeSend: function () {
				// 		$("#contenido").html("");
				// },
				success:  function (response) {
						//001003--507--,true;
						//FA_SERIE_001003
						ser1 = ser.split("_"); 
						autorizar(ser1[2],n_fac,me,cli);
					}

			});	
		}		
	}

	function autorizar(serie,factura,mesa,ci)
	{

		var parametros = 
		{
			"serie" : serie,
			"num_fac" :factura,
			"tc" :'FA' ,
			"cod_doc" : '01',			
		};
		$.ajax({
			data:  {parametros:parametros},
			url:   '../../comprobantes/SRI/autorizar_sri.php?autorizar=true',
			type:  'post',
			dataType: 'json',
			success:  function (response) {
		    $('#myModal_espera').modal('hide');
				console.log(response);
				if(response.respuesta == '3')
				{
					Swal.fire({
				       type: 'error',
				       title: 'Este documento electronico ya esta autorizado',
				       text: ''
			       });

			    }else if(response.respuesta == '1')
			    {
			    	Swal.fire({
				       type: 'success',
				       title: 'Este documento electronico autorizado',
				       text: ''
			       });
			    	imprimir_ticket_fac(mesa,ci,factura,serie);
			    	liberar(mesa);
			    }else if(response.respuesta == '2')
			    {
			    	Swal.fire({
				       type: 'info',
				       title: 'XML devuelto',
				       text: ''
			       });
			    	descargar_archivos(response.url,response.ar);

			    }
			    else
			    {
			    	Swal.fire({
				       type: 'info',
				       title: 'Error por: '+response,
				       text: ''
			       });

			    }

			}
		});

	}
	function descargar_archivos(url,archivo)
		{
			var url1 = url+archivo;
			console.log(url1);
            var link = document.createElement("a");
            link.download = archivo;
            link.href = url1;
            link.click();
        }


// fin programacion 
	function facturar(me,nom)
	{
		var cli=document.getElementById('ruc').value;
		var nombrec=document.getElementById('nombrec2').value;
		var email=document.getElementById('email').value;
		var ser=document.getElementById('ser').value;
		var n_fac=document.getElementById('n_fac').value;
		var total_total_=document.getElementById('total_total_').value;
		var total_abono=document.getElementById('total_abono').value;
		var propina_a=document.getElementById('propina_a').value;
		//alert(propina_a);
		if(total_total_>total_abono)
		{
			Swal.fire({
				type: 'error',
				title: 'debe abonar mas del monto total: $'+total_total_+'  para generar Factura',
				text: ''
			});
		}			
		else
		{
			var parametros =
			{
				"me" : me,
				"nom" : nom,
				"nombrec": nombrec,
				"ruc": cli,
				"email": email,
				"ser" : ser,
				"n_fac" : n_fac,
				"total_total_" : total_total_,
				"total_abono" : total_abono,
				"propina_a" : propina_a
			};
			$.ajax({
				data:  parametros,
				url:   'controlador/controladormesa.php?facturar=true',
				type:  'post',
				beforeSend: function () {
						$("#contenido").html("");
				},
				success:  function (response) {
						//001003--507--,true;
						//FA_SERIE_001003
						ser1 = ser.split("_"); 
						var mapForm = document.createElement("form");
						mapForm.target = "_blank";    
						mapForm.method = "POST";
						mapForm.action = "../../entidades/autorizar.php";
						//mapForm.onclick=window.open('../../entidades/autorizar.php','','scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
						
						// Create an input
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "ajax_page";
						mapInput.value = "autorizar";

						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "datos";
						mapInput.value = ser1[2]+'--'+n_fac;

						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "IP";
						mapInput.value = "<?php echo $IP_VPN_RUTA; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "Ba";
						mapInput.value = "<?php echo $Base_Datos; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "Us";
						mapInput.value = "<?php echo $Usuario_DB; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "Co";
						mapInput.value = "<?php echo $Contraseña_DB; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "Ti";
						mapInput.value = "<?php echo $Tipo_Base; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "Pu";
						mapInput.value = "<?php echo $Puerto; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "ru_ce";
						mapInput.value = "<?php echo $ruta_cer; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "cl_ce";
						mapInput.value = "<?php echo $clave_cer; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "amb";
						mapInput.value = "<?php echo $ambiente; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "nom_com";
						mapInput.value = "<?php echo $nom_com; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "raz_soc";
						mapInput.value = "<?php echo $raz_soc; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "ruc";
						mapInput.value = "<?php echo $ruc; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "dir";
						mapInput.value = "<?php echo $dir; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "cod_doc";
						mapInput.value = "<?php echo $cod_doc; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "tc";
						mapInput.value = "<?php echo $tc; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "item";
						mapInput.value = "<?php echo $item; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						var mapInput = document.createElement("input");
						mapInput.type = "text";
						mapInput.name = "peri";
						mapInput.value = "<?php echo $periodo; ?>";
														
						// Add the input to the form
						mapForm.appendChild(mapInput);
						
						// Add the form to dom
						document.body.appendChild(mapForm);
						
						// Just submit
						mapForm.submit();
						document.body.removeChild(mapForm);
						//var waitUntil = new Date().getTime() + 10*1000;
						//while(new Date().getTime() < waitUntil) true;
						var parametros =
						{
							"me" : me,
							"nom" : nom,
							"nombrec": nombrec,
							"ruc": cli,
							"email": email,
							"ser" : ser,
							"n_fac" : n_fac,
							"total_total_" : total_total_,
							"total_abono" : total_abono,
							"propina_a" : propina_a,
							"imprimir" : '1'
						};
						$.ajax({
							data:  parametros,
							url:   'controlador/controladormesa.php?facturar=true',
							type:  'post',
							beforeSend: function () {
									$("#contenido").html("");
							},
							success:  function (response) {
								 

									// ventana = window.open("ajax/TEMP/"+me+".pdf", "nuevo", "width=400,height=400");
									// ventana.close();
									
									// var html='<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+me+'.pdf" frameborder="0" allowfullscreen></iframe>';
									// //$("#contenido").html(response);
									// $('#contenido').html(html); 
									$("#myModal").modal();					
							}
						});
						/*ventana = window.open("ajax/TEMP/"+me+".pdf", "nuevo", "width=400,height=400");
						ventana.close();
						var waitUntil = new Date().getTime() + 2*1000;
						while(new Date().getTime() < waitUntil) true;
						var html='<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+me+'.pdf" frameborder="0" allowfullscreen></iframe>';
						//$("#contenido").html(response);
						$('#contenido').html(html); 
						$("#myModal").modal();	*/		

				 var serie = ser.split('_'); 
		         setTimeout(imprimir_ticket_fac(me,ruc,n_fac,serie[2]),5000);		
				}
			});	
		}

		
	}
	function agregar_abono(me,nom)
	{
		var cli=document.getElementById('ruc').value;
		var nombrec=document.getElementById('nombrec2').value;
		var email=document.getElementById('email').value;
		var ser=document.getElementById('ser').value;
		var n_fac=document.getElementById('n_fac').value;
		var abo=document.getElementById('abo').value;
		var compro_a=document.getElementById('compro_a').value;
		var monto_a=document.getElementById('monto_a').value;
		var propina_a=document.getElementById('propina_a').value;
		document.getElementById("e_abo").style.display = "none";
		document.getElementById("e_monto_a").style.display = "none";
		var dir = $('#dir').val();
		var tel = $('#tel').val();
		
		var total_total_=document.getElementById('total_total_').value;
		var total_abono=document.getElementById('total_abono').value;
		
		var devo = (parseFloat(total_abono)+parseFloat(monto_a))-parseFloat(total_total_);
		
		//alert(total_abono+' nn '+(parseFloat(total_abono)+parseFloat(monto_a))+' cc '+total_total_+' -- '+devo);
		if(devo>0)
		{
			Swal.fire({
				type: 'error',
				title: 'no puede ingresar mas abono del total de la factura: $'+total_total_,
				text: ''
			});
		}
		else
		{
			if(abo=='0')
			{
				document.getElementById("e_abo").style.display = "block";	
			}
			else
			{
				if(isNaN(monto_a) || isNaN(propina_a))
				{
					Swal.fire({
						type: 'error',
						title: 'debe Ingresar solo numero',
						text: ''
					});
				}
				else
				{	
					if(monto_a=='')
					{
						document.getElementById("e_monto_a").style.display = "block";	
					}
					else
					{
						var parametros =
						{
							"me" : me,
							"nom" : nom,
							"nombrec": nombrec,
							"ruc": cli,
							"email": email,
							"ser" : ser,
							"n_fac" : n_fac,
							"abo": abo,
							"compro_a": compro_a,
							"monto_a": monto_a,
							'dir':dir,
							'tel':tel,
						};
						$.ajax({
							data:  parametros,
							url:   'controlador/controladormesa.php?abono=true',
							type:  'post',
							beforeSend: function () {
									$("#contenido").html("");
							},
							success:  function (response) {
									$("#contenido").html(response);
									$("#myModal").modal();					
							}
						});	
					}
				}
			}
		}
	}

	function validarEmail() {
		valor = $('#email').val();
		emailRegex = /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i;
       if (emailRegex.test(valor)){
         return 1;
       } else {
        Swal.fire({
				type: 'error',
				title: 'Email no debe tener caracteres especiales',
				text: ''
			});

        $('#update_cli').show();
        $('#add_datos').hide();
        return -1;
       }
    }

    function validarDir() {
		valor = $('#Direccion').val();
		emailRegex =/^[A-Za-z\d\.\ \-]+$/; 
       if (emailRegex.test(valor)){
         return 1;
       } else {
        Swal.fire({
				type: 'error',
				title: 'Direccion no bebe tener caracteres especiales',
				text: ''
			});
        $('#update_cli').show();
        $('#add_datos').hide();
        return -1;
       }
    }

    function update_cliente()
    {
    	var dir =$('#Direccion').val();
    	var ema =$('#email').val();
    	var tel =$('#Telefono').val();
    	var sel =$('#nombrec').val();
    	var id = sel.split(',');
    	var parametros = 
			{
				"id" :id[0],
				"tel" : tel,
				"dir": dir,
				"ema": ema,
			};
			// console.log(parametros);
			$.ajax({
				data:  {parametros:parametros},
				url:   'controlador/controladormesa.php?update_cli=true',
				type:  'post',			
				dataType: 'json',	
				success:  function (response) {
					if(response=='1')
					{
						Swal.fire({
				           type: 'success',
				           title: 'Datos de cliente guardados!',
				           text: ''
			           });
						$('#update_cli').hide();
						$('#add_datos').show();
					}
				}
			});	
    }


	function agregar_cli(me,nom)
	{ 
		var ema = validarEmail();
		var di = validarDir();

		if(ema==1 && di==1)
		{
		var data = $('#nombrec').select2('data');       
		var cli=document.getElementById('ruc').value;
		var nombrec= data[0].text;
		var email=document.getElementById('email').value;
		var tel = $('#Telefono').val(); 
		var dir = $('#Direccion').val(); 
		
		if( (cli!='.' && nombrec=='') || (cli!='.' && nombrec!='') || (cli=='9999999999999' && nombrec==''))
		{
			var parametros = 
			{
				"me" : me,
				"nom" : nom,
				"nombrec": nombrec,
				"ruc": cli,
				"email": email,
				'tel':tel,
				'dir':dir,
			};
			$.ajax({
				data:  parametros,
				url:   'controlador/controladormesa.php?factura=true',
				type:  'post',
				beforeSend: function () {
						$("#contenido").html("");
				},
				success:  function (response) {
						$("#contenido").html(response);
						$("#myModal").modal();					
				}
			});	
		}
		else
		{
			Swal.fire({
				type: 'error',
				title: 'debe seleccionar cliente!',
				text: ''
			});
		}
	  }else
	  {
	  	 $('#update_cli').show();	  	 
	  	 $('#add_datos').hide();
	  }
	}
	function prefact1(event,me,cli,nom)
	{
		var codigo = event.which || event.keyCode;
		var cl=document.getElementById(cli).value;
		var nombrec=document.getElementById('nombrec2').value;
		var ruc=document.getElementById('ruc').value;
		var email=document.getElementById('email').value;
		if(codigo === 13 || codigo === 1)
		{
			//alert(me+' '+cl);
			var bus='';
			var parametros = 
			{
				"me" : me,
				"nom" : nom,
				bus : bus,
				nombrec: nombrec,
				ruc: ruc,
				email: email,
				"ajax_page": 'pop1',
				cl: 'pep1'
				
			};
			$.ajax({
				data:  parametros,
				url:   'ajax/vista_ajax.php',
				type:  'post',
				beforeSend: function () {
						$("#pdfcom").html("");
				},
				success:  function (response) {
						$("#pdfcom").html(response);
						$("#myModal").modal();
				}
			});
		}
		
	}
	
	 function buscarCli(event,cla)
	 {
		var codigo = event.which || event.keyCode;
		//alert(codigo);
		document.getElementById('nombrec').focus();
		if(codigo === 13 || cla== 13 || codigo=='undefined'){
		    var query = $('#nombrec').val();		
			if(query=='')
			{
				$("#beneficiario1").hide();
				$("#lbeneficiario1").hide();
				$("#cbeneficiario1").hide();		
			}

			$.ajax({
				url: 'controlador/controladormesa.php?buscarcli='+query,
				dataType: 'json',
				type: 'POST',
				data: {query:query},
				success:function(e){
					$("#beneficiario1").show();
					$("#lbeneficiario1").show();
					$("#cbeneficiario1").show();
					var ob = e.length;
					 
					 if(ob != 0)
					 {
						 $("#beneficiario1").empty();
						 $("#beneficiario1").append("<option value=''>Seleccionar</option>");
						for( var i = 0; i<ob; i++)
						{
							$("#beneficiario1").append("<option value='"+e[i].id+"-"+e[i].email+"-"+e[i].nombre+"'>"+e[i].nombre+"</option>");
						}

					 }
					 else
					 {
						$("#beneficiario1").hide();
						$("#lbeneficiario1").hide();
						$("#cbeneficiario1").hide();
						alert('Cliente no encontrado');
						var me=document.getElementById('me').value;
						var nom=document.getElementById('nom').value;
						var parametros = 
						{
							"me" : me,
							"nom" : nom,
							"ajax_page": 'cli1',
							cl: 'cli1'
						};
						$.ajax({
							data:  parametros,
							url:   'ajax/vista_ajax.php',
							type:  'post',
							beforeSend: function () {
								//$("#contenido").html("");
							},
							success:  function (response) {
								$("#contenido").html(response);
								$("#myModal").modal();								
							}
						});
					 }
					
				},
				error:function(e){
				   console.log('error'.e);
				}
			});
		}
		
    }

    function nuevo_cliente()
    {
	     var me=document.getElementById('me').value;
	     var nom=document.getElementById('nom').value;
	     var parametros = 
	     {
		     "me" : me,
		     "nom" : nom,
		     "ajax_page": 'cli1',
		     cl: 'cli1'
	     };
	     $.ajax({
		     data:  parametros,
		     url:   'ajax/vista_ajax.php',
		     type:  'post',
		     beforeSend: function () {
		     	//$("#contenido").html("");
		     },
		     success:  function (response) {
			     $("#contenido").html(response);
			     $("#myModal").modal();								
		     }
	     });

    }


	function vcliente(event,me,ru)
	{
		var codigo = event.which || event.keyCode;
		var ruc=document.getElementById(ru).value;
		var item=<?php echo $_SESSION['INGRESO']['item']; ?>
		//1303783813 verificar
		//RUVEN MEJIA & KLEBER SORIA TRANSPORTES CIA LTDA RMKESS CIA LTDA
		if (codigo == '9' || codigo == '1') 
		{
			//alert(me+' '+codigo+' '+ruc);
			var parametros = 
			{
				"ruc" : ruc,
				"vista" : 'afe',
				"idMen" : 'codigoc',
				"TC" : 'TC',
				"ajax_page": 'dig',
				"item" : item
			};
			$.ajax({
				data:  parametros,
				url:   'ajax/vista_ajax.php',
				type:  'post',
				beforeSend: function () {
						$("#resultado").html("");
				},
				success:  function (response) {
						$("#resultado").html("");
						$("#resultado").html(response);
						 var valor = $("#resultado").html();
						 document.cookie = "nombre=1; ";
						 if(valor=='RUC/CI (P)')
						 {
							if(confirm("¿EL CODIGO INGRESADO, NO ES NI CEDULA NI RUC(NIC); ESTE CODIGO ES DE UN PASAPORTE?"))
							{
								
							}
							else
							{
								$("#RUC").val('');
								$("#resultado").html('RUC/CI  <p style="color:#FF0000;">ingrese un RUC/CI correcto</p>');
								$("#RUC").focus();
								$("#RUC").select();
							}
						}
				}
			});
		}
	}
	function seleccionado(me)
	{		
		$('#update_cli').hide();
		$('#add_datos').show();
		var valor = $("#nombrec").val();
		var partes = valor.split(',');
		$('#ruc').val(partes[0]);
		$('#email').val(partes[1]);
		$('#Direccion').val(partes[2]);
		$('#Telefono').val(partes[3]);
		// if(valor!='')
		// {
		// 	arregloDeSubCadenas = valor.split("-"); 
		// 	 $("#nombrec").val(arregloDeSubCadenas[2]);
		// 	 $("#ruc").val(arregloDeSubCadenas[0]);
		//    if(arregloDeSubCadenas[1].length>2)
		// 	{
		// 		//alert('1 '+arregloDeSubCadenas[1].length);
		// 		$('#email').val(arregloDeSubCadenas[1]);
		// 		//document.getElementById("email").value='<?php echo $_SESSION['INGRESO']['Email_Conexion_CE']; ?>';
		// 	}
		// 	else
		// 	{
		// 		//alert('2');
		// 		document.getElementById("email").value='<?php echo $_SESSION['INGRESO']['Email_Conexion_CE']; ?>';
		// 	}
		// 	 $("#beneficiario1").css('display','none');
		// }
		// else
		// {
		// 	 Swal.fire({
		// 		type: 'error',
		// 		title: 'debe seleccionar un registro!',
		// 		text: ''
		// 	});
		// }
	}
	
	function seleFo(id,me)
	{
		alert('vvv');
		/*var valor = document.getElementById("beneficiario1");
		
		if (document.getElementById("beneficiario1").value =='') {
			document.getElementById('beneficiario1').focus();
			var query = $('#nombrec').val();		
			if(query=='')
			{
				$("#beneficiario1").hide();
				$("#lbeneficiario1").hide();
				$("#cbeneficiario1").hide();		
			}
			$.ajax({
				url: 'controlador/controladormesa.php?buscarcli='+query,
				dataType: 'json',
				type: 'POST',
				data: {query:query},
				success:function(e){
					$("#beneficiario1").show();
					$("#lbeneficiario1").show();
					$("#cbeneficiario1").show();
					var ob = e.length;
					 
					 if(ob != 0)
					 {
						 $("#beneficiario1").empty();
						for( var i = 0; i<ob; i++)
						{
							$("#beneficiario1").append("<option value='"+e[i].id+"-"+e[i].email+"-"+e[i].nombre+"'>"+e[i].nombre+"</option>");
						}

					 }
					 else
					 {
						$("#beneficiario1").hide();
						$("#lbeneficiario1").hide();
						$("#cbeneficiario1").hide();
						alert('Cliente no encontrado');
						var me=document.getElementById('me').value;
						var nom=document.getElementById('nom').value;
						var parametros = 
						{
							"me" : me,
							"nom" : nom,
							"ajax_page": 'cli1',
							cl: 'cli1'
						};
						$.ajax({
							data:  parametros,
							url:   'ajax/vista_ajax.php',
							type:  'post',
							beforeSend: function () {
								//$("#contenido").html("");
							},
							success:  function (response) {
								$("#contenido").html(response);
								$("#myModal").modal();								
							}
						});
					 }
					
				},
				error:function(e){
				   console.log('error'.e);
				}
			});
		}
		else
		{
			var text = valor.options[valor.selectedIndex].innerText; 
			separador = "-";
			limite    = 2;
			arregloDeSubCadenas = text.split(separador, limite);
			/*var select = document.getElementById('provincia');
			select.addEventListener('change',
			function(){
				var selectedOption = this.options[select.selectedIndex];
				console.log(selectedOption.value + ': ' + selectedOption.text);
			});*/
		
			/*document.getElementById("nombrec").value=arregloDeSubCadenas[0];
			$("#beneficiario1").hide();
			if(document.getElementById("nombrec").value=='no existe registro')
			{
				alert('agregar beneficiario');
				var parametros = 
				{
					"me" : me,
					"ajax_page": 'cli1',
					cl: 'cli1'
				};
				$.ajax({
					data:  parametros,
					url:   'ajax/vista_ajax.php',
					type:  'post',
					beforeSend: function () {
							$("#pdfcom").html("");
					},
					success:  function (response) {
							$("#pdfcom").html(response);
							$("#myModal").modal();					
					}
				});
			}
			else
			{
				document.getElementById('email').select();	
			}
		}*/
	}
	
	function fact(me,nom)
	{
		alert(me);
		var parametros = 
		{
			"me" : me,
			"ajax_page": 'pop1',
			cl: 'mes1'
		};
		/*$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			beforeSend: function () {
					$("#pdfcom").html("");
			},
			success:  function (response) {
					$("#pdfcom").html(response);
					$("#myModal").modal();					
			}
		});*/
		//$('#pdfcom').html(''+id+''); 
		
	}

	function agregar_n(id,nom,fil)
	{	
		var buscar= '';
		if(fil!='')
		{
			if(fil=='f_pro')
			{
				var vari=document.getElementById(fil).value;
			}
			else
			{
				var vari=fil;
			}
		}
		$('#botones').html(''); 
		if ($("#buscar").length) 
		{
			 buscar= $("#buscar").val();
		}
		$.ajax({
			data:  {
				pedido:true,
				item:'<?php echo $_SESSION['INGRESO']['item']; ?>',
				pediodo:'<?php echo $_SESSION['INGRESO']['periodo']?>',
				fil: vari,
				buscar:buscar},
			url:   'controlador/controladormesa.php?id='+id+'&nom='+nom,
			type:  'post',
			beforeSend: function () {
					$("#contenido").html("");
			},
			success:  function (response) {
					$("#contenido").html(response);
					$("#modal_contenido").modal();					
			}
		});
		//$('#pdfcom').html(''+id+''); 
	}

	function agregar(id,nom)
	{
		var parametros = 
		{
			"me" : id,
			"nom" : nom,
			"ajax_page": 'pop1',
			cl: 'mes1'
		};
		$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			beforeSend: function () {
					$("#pdfcom").html("");
			},
			success:  function (response) {
					$("#pdfcom").html(response);
					$("#myModal").modal();					
			}
		});
		//$('#pdfcom').html(''+id+''); 
		
	}
	function buscarpro(id,nom)
	{
		var bus=document.getElementById('buscar').value;
		var parametros = 
		{
			"me" : id,
			"nom" : nom,
			bus : bus,
			"ajax_page": 'pop1',
			cl: 'mes2'
			
		};
		$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			beforeSend: function () {
					$("#pdfcom").html("");
			},
			success:  function (response) {
					$("#pdfcom").html(response);
					$("#myModal").modal();					
			}
		});
	}
	function verpedido(id,nom)
	{
		var bus=document.getElementById('buscar').value;
		var parametros = 
		{
			"me" : id,
			"nom" : nom,
			bus : bus,
			"ajax_page": 'pop1',
			cl: 'pep1'
			
		};
		$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			beforeSend: function () {
					$("#pdfcom").html("");
			},
			success:  function (response) {
					$("#pdfcom").html(response);
					$("#myModal").modal();
			}
		});
	}
	function selec_p(id,id_) 
	{
		var selected = '';
		$('#formu1 input[type=checkbox]').each(function(){
            if (!this.checked) 
			{
                document.getElementById("canti_"+$(this).val()).style.display = "none";
				document.getElementById("obs_"+$(this).val()).style.display = "none";
				document.getElementById("lobs_"+$(this).val()).style.display = "none";
				document.getElementById("lcanti_"+$(this).val()).style.display = "none";
				document.getElementById("lcop_"+$(this).val()).style.display = "none";
				document.getElementById("cop_"+$(this).val()).style.display = "none";
				//document.getElementById("total_"+id_).style.display = "none";
				//alert("total_"+id_);
            }
			else
			{
				console.log($(this).val());
				selected += $(this).val()+', ';
				document.getElementById("canti_"+$(this).val()).style.display = "block";
				document.getElementById("obs_"+$(this).val()).style.display = "block";
				document.getElementById("lcanti_"+$(this).val()).style.display = "block";
				document.getElementById("lobs_"+$(this).val()).style.display = "block";
				document.getElementById("lcop_"+$(this).val()).style.display = "block";
				document.getElementById("cop_"+$(this).val()).style.display = "block";
				//alert("total_"+id_);
				//document.getElementById("total_"+id_).style.display = "block";
			}
        }); 
        /*if (selected != '') 
		{
            alert('Has seleccionado: '+selected);  
		}
        else
		{
            alert('Debes seleccionar al menos una opción.');
		}*/
		//return todos.join(".");
	}

	function c_total(id,pre,pre_c,id_) 
	{
		if(isNaN(document.getElementById('canti_'+id).value))
		{
			Swal.fire({
				type: 'error',
				title: 'debe Ingresar solo numero',
				text: ''
			});
		}
		else
		{
			if(document.getElementById('cop_'+id).value=='1')
			{
				var cant=document.getElementById('canti_'+id).value;
				var tot=cant*pre_c;
			}
			else
			{
				var cant=document.getElementById('canti_'+id).value;
				var tot=cant*pre;
			}
			
			//alert(tot+"#total_"+id);
			$("#total_"+id_).html('TOTAL $ '+tot);
		}
	}

	function agregar_(id,nom)
	{
		//alert(' codido mesa '+id);
		var selected = '';
		var selected_c='';
		var selected_o='';
		var selected_co='';
		$('#formu1 input[type=checkbox]').each(function(){
            if (this.checked) 
			{
				selected += $(this).val()+', ';
				selected_c += document.getElementById('canti_'+$(this).val()).value+', '; 
				selected_o += document.getElementById('obs_'+$(this).val()).value+', '; 
				selected_co += document.getElementById('cop_'+$(this).val()).value+', '; 
			}
		}); 
		//alert('productos: '+selected+' Cantidad: '+selected_c);  
		if (selected != '') 
		{
			var bus=document.getElementById('buscar').value;
            var parametros = 
			{
				"me" : id,
				"nom" : nom,
				prod : selected,
				cant : selected_c,
				obs : selected_o,
				cop : selected_co,
				bus : bus,
				
			};
			$.ajax({
				data:  {parametros:parametros},
				url:   'controlador/controladormesa.php?agregar=true',
				type:  'post',
				beforeSend: function () {
						$("#pdfcom").html("");
				},
				success:  function (response) {
						if(response==1)
						{
							//alert('Agregado');
							agregar_n(id,nom,'f_pro');
						}
				}
			});
		}
        else
		{
            Swal.fire({
				type: 'error',
				title: 'Oops...',
				text: 'debe seleccionar un registro!'
			});
		}
		
	}
	function formato(e)
	{
		e = e || window.event;
		e = e.target || e.srcElement;
		var input = this.shadowRoot.getElementById(e.id) ;

		//aquí elimino todo lo que no sea números o comas (,)
		var num = input.value.replace(/\,/g,'');
		if(!isNaN(num)){
		//convierto a string
		num = num.toString();
		//separo los caracteres del string
		num = num.split('');
		//invierto el orden del string
		num = num.reverse();
		//junto todos los caracteres de la cadena
		num = num.join('');
		//busco los dos primeros caracteres y le coloco una coma en la siguiente posición
		num = num.replace(/(?=\d*\.?)(\d{2})/,'$1,');
		//invierto del contenido de la cadena y reemplazo todo lo que no sea números o comas
		num = num.split('').reverse().join('').replace(/^[\,]/,'');
		//asigno la cadena formateada al input
		input.value = num;
		}
	}
</script>


<div id="modal_contenido" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><img  width='8%'  height='8%' src="../../../img/jpg/logo.jpg">    Pedido</h4>
      </div>
      <div class="modal-body" style="height:400px; width:100%; overflow-y: scroll;" id="contenido">
      	
      </div>
      <div class="modal-footer">
      	<div class="row">
      		<div class="col-sm-10 text-right" id='botones'>
      			
      		</div>
      		<div class="col-sm-12 text-right" id='pie_p'>
      			 <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>         			
      		</div>      		
      	</div>
      	
       
      </div>
    </div>

  </div>
</div>



</body>
</html>
 <div class="modal fade" id="myModal_espera" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body text-center">
        	<img src="../../../img/gif/loader4.1.gif" width="80%"> 	
        </div>
      </div>
    </div>
  </div>
