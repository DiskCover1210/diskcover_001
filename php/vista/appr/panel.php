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
include("../chequear_seguridad.php"); 
require_once("../../controlador/panelr.php");
//enviar correo
require_once("../../../lib/phpmailer/PHPMailerAutoload.php");
//facturacion
$_SESSION['INGRESO']['modulo_']='02';
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Blank Page</title>
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
</head>
<!-- class="hold-transition skin-blue sidebar-mini" -->
<body class="skin-blue sidebar-mini sidebar-collapse" id='cargar'>
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
      <!-- Sidebar toggle button
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>-->

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
			 
											
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../../img/jpg/sinimagen.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"> <?php	
											//var_dump($_SESSION['INGRESO']);
											echo $_SESSION['INGRESO']['Nombre'];
										?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
               <!-- <img src="../../../dist/img/user2-160x160.jpg" class="img-circle" alt="User Image"> -->

                <p>
                  <?php	
						//var_dump($_SESSION['INGRESO']);
						echo $_SESSION['INGRESO']['Nombre'];
					?>
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
          <!-- Control Sidebar Toggle Button 
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>-->
        </ul>
      </div>
    </nav>
  </header>

  <!-- =============================================== -->

 

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) 
    <section class="content-header">
      <h1>
        Blank page
        <small>it all starts here</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Examples</a></li>
        <li class="active">Blank page</li>
      </ol>
    </section>-->

    <!-- Main content -->
    <section class="content">

     <!-- <div class="box">
        <div class="box-header with-border">
          <h3 class="box-title">Title</h3>

          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>
        <div class="box-body">
          Start creating your amazing application!
        </div>
        <div class="box-footer">
          Footer
        </div>
      </div>
	-->
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
		?>
		<div id='pdfcom1'>
		</div>
		<?php
		//hacemos ciclo
		/*for($i=0;$i<count($_SESSION['APPR']['MESAS']);$i++)
		{
			//echo $_SESSION['APPR']['MESAS'][$i]['disp'].'<br>';
			if($_SESSION['APPR']['MESAS'][$i]['disp']==1)
			{
			?>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner">
							<h3 style="font-size: 20px">Disponible<sup style="font-size: 17px"></sup></h3>

							<p><?php echo $_SESSION['APPR']['MESAS'][$i]['nombre']; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a onclick="agregar('<?php echo $_SESSION['APPR']['MESAS'][$i]['codigo']; ?>','<?php echo $_SESSION['APPR']['MESAS'][$i]['nombre'];?>');" class="small-box-footer">ver <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			<?php
			}
			if($_SESSION['APPR']['MESAS'][$i]['disp']==0)
			{
				?>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner">
							<h3 style="font-size: 20px">Ocupada</h3>

							<p><?php echo $_SESSION['APPR']['MESAS'][$i]['nombre']; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-person-add"></i>
						</div>
						<a onclick="agregar('<?php echo $_SESSION['APPR']['MESAS'][$i]['codigo']; ?>','<?php echo $_SESSION['APPR']['MESAS'][$i]['nombre'];?>');" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				<?php
			}
			if($_SESSION['APPR']['MESAS'][$i]['disp']==2)
			{
				?>
				<div class="col-lg-3 col-xs-6">
				
					<div class="small-box bg-red">
						<div class="inner">
							<h3 style="font-size: 20px">problema</h3>

							<p><?php echo $_SESSION['APPR']['MESAS'][$i]['nombre']; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-pie-graph"></i>
						</div>
						<a onclick="agregar('<?php echo $_SESSION['APPR']['MESAS'][$i]['codigo']; ?>','<?php echo $_SESSION['APPR']['MESAS'][$i]['nombre'];?>');" class="small-box-footer">Ver <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
				
				<?php
			}
		}*/
		$ove=400;
	?>
	<div class="modal fade" id="myModal" role="dialog" >
		<div class="modal-dialog" style="width:100%;">
		  <div class="modal-content" style="width:100%;">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title"><img  width='5%'  height='5%' src="../../../img/jpg/logo.jpg">Pedido </h4>
			</div>
			<div class="modal-body" style="height:<?php echo $ove; ?>px;overflow-y: scroll;">
				<div class="form-group">
					<div id='pdfcom'>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<!--<div id="alerta" class="alert invisible"></div>
				<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">
				En caso de dudas, comuniquese al centro de atención al cliente, a los telefonos:<br> 
				+593-2-321-0051 / +593-9-8035-5483</p>
				<a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Exportar PDF"
				href="descarga.php?mod=contabilidad&acc=macom&acc1=Errores de mayorización&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
				&Opcb=6&Opcen=0&b=0&ex=pdf&cl=0" target="_blank" >
					<i ><img src="../../img/png/pdf.png" class="user-image" alt="User Image"
					style='font-size:20px; display:block; height:100%; width:100%;'></i> 
				</a>
				<button id="btnCopiar" class="btn btn-primary" onclick='copiar();'>Copiar</button>-->
				<div id='pie_p'>
					<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Cerrar</button>
				</div>
			</div>
		  </div>
		  
		</div>
	</div>
		<!--<div class="row">
			<div class="col-lg-3 col-xs-6">
				
				<div class="small-box bg-aqua">
					<div class="inner">
						<h3>150</h3>

						<p>New Orders</p>
					</div>
					<div class="icon">
						<i class="ion ion-bag"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			
			<div class="col-lg-3 col-xs-6">
				
				<div class="small-box bg-green">
					<div class="inner">
						<h3>53<sup style="font-size: 20px">%</sup></h3>

						<p>Bounce Rate</p>
					</div>
					<div class="icon">
						<i class="ion ion-stats-bars"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			
			<div class="col-lg-3 col-xs-6">
				
				<div class="small-box bg-yellow">
					<div class="inner">
						<h3>44</h3>

						<p>User Registrations</p>
					</div>
					<div class="icon">
						<i class="ion ion-person-add"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			
			<div class="col-lg-3 col-xs-6">
				
				<div class="small-box bg-red">
					<div class="inner">
						<h3>65</h3>

						<p>Unique Visitors</p>
					</div>
					<div class="icon">
						<i class="ion ion-pie-graph"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			
		</div>-->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

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
			"ajax_page": 'pop3',
			cl: 'eli1'
		};
		$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			beforeSend: function () {
					
			},
			success:  function (response) {
				verpedido(id,nom);			
			}
		});
	}
	function liberar(me)
	{
		var parametros = 
		{
			"me" : me,
			"ajax_page": 'pop3',
			cl: 'lib1'
		};
		$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			beforeSend: function () {
					
			},
			success:  function (response) {
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
			}
		});
	}
	function prefact2(me,nom)
	{
		var nombrec='consumidor final';
		var ruc='9999999999999';
		var email='.';
		//alert(me+' '+nom+' '+nombrec+' '+ruc+' '+email);
		
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
			ventana = window.open("ajax/TEMP/"+ca+".pdf", "nuevo", "width=400,height=400");
			ventana.close();
			$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+ca+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
			
			$("#myModal").modal();
			//alert('entrooo '+idMensaje+" ajax/TEMP/'+value1+'.pdf");
		});
	}
	function prefact(me,nom)
	{
		var parametros = 
		{
			"me" : me,
			"nom" : nom,
			"ajax_page": 'fac1',
			cl: 'pre1'
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
	function agregarabo(me,nom,bus)
	{
		var nombrec=document.getElementById('nombrec').value;
		var ruc=document.getElementById('ruc').value;
		var email=document.getElementById('email').value;
		var n_fac=document.getElementById('n_fac').value;
		var ser=document.getElementById('ser').value;
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
			var nombrec=document.getElementById('nombrec').value;
			var email=document.getElementById('email').value;
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
								$("#pdfcom").html("");
						},
						success:  function (response) {
							
							var parametros = 
							{
								"me" : me,
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
					});
			}
		}
		
	}
	function prefact1(event,me,cli,nom)
	{
		var codigo = event.which || event.keyCode;
		var cl=document.getElementById(cli).value;
		var nombrec=document.getElementById('nombrec').value;
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
	function buscarCli(id,me,nom)
	{
		//var bus=document.getElementById(id).value;
		//alert(id.value);
		$("#beneficiario1").show();
		$("#lbeneficiario1").show();
		$("#cbeneficiario1").show();
		var search = id.value;
		if(search != "")
		{
			var parametros = 
			{
				"query" : search,
				"ajax_page": 'aut1',
				cl: 'cl_a'
			};
			$.ajax({
				url: 'ajax/vista_ajax.php',
				type: 'post',
				data: parametros,
				dataType: 'json',
				success:function(response){
					var len = response.length;
					if(len>10)
					{
						$("#beneficiario1").attr("size",10);
					}
					else
					{
						$("#beneficiario1").attr("size",len);
					}
					//courier new
					$("#beneficiario1").empty();
					$("#beneficiario1").append("<option value='0'>Seleccionar</option>");
					var ban=0;
					for( var i = 0; i<len; i++){
						$("#beneficiario1").append("<option value='"+response[i]['id']+"-"+response[i]['email']+"'>"+response[i]['nombre']+"</option>");
						if(response[i]['nombre']=='no existe registro')
						{
							ban=1;
							break;
						}
					}
					if(ban==1)
					{
						ban=0;
						alert('No existe beneficiario');
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
								$("#pdfcom").html("");
							},
							success:  function (response) {
								$("#pdfcom").html(response);
								$("#myModal").modal();					
							}
						});
					}
				}
			});
		}
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
	function seleOn(id,me)
	{
		var valor = document.getElementById(id).value;
		separador = "-"; 
		limite    = 2;
		arregloDeSubCadenas = valor.split(separador, limite);
		document.getElementById("ruc").value=arregloDeSubCadenas[0];
		if(arregloDeSubCadenas[1].length>2)
		{
			//alert('1 '+arregloDeSubCadenas[1].length);
			document.getElementById("email").value=arregloDeSubCadenas[1];
			//document.getElementById("email").value='<?php echo $_SESSION['INGRESO']['Email_Conexion_CE']; ?>';
		}
		else
		{
			//alert('2');
			document.getElementById("email").value='<?php echo $_SESSION['INGRESO']['Email_Conexion_CE']; ?>';
		}
		
	}
	function seleFo(id,me)
	{
		var valor = document.getElementById("beneficiario1");
		if (document.getElementById("beneficiario1").value =='') {
			document.getElementById('beneficiario1').focus();	
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
		
			document.getElementById("nombrec").value=arregloDeSubCadenas[0];
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
		}
	}
	
	function fact(me,nom)
	{
		//alert(me);
		var nombrec=document.getElementById('nombrec').value;
		var ruc=document.getElementById('ruc').value;
		var email=document.getElementById('email').value;
		var n_fac=document.getElementById('n_fac').value;
		var ser=document.getElementById('ser').value;
		//alert(me+' '+nom+' '+nombrec+' '+ruc+' '+email);
		
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
			ventana = window.open("ajax/TEMP/"+ca+".pdf", "nuevo", "width=400,height=400");
			ventana.close();
			$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+ca+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
			$("#myModal").modal();
			//alert('entrooo '+idMensaje+" ajax/TEMP/'+value1+'.pdf");
		});		
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
				//document.getElementById("total_"+id_).style.display = "none";
				//alert("total_"+id_);
            }
			else
			{
				selected += $(this).val()+', ';
				document.getElementById("canti_"+$(this).val()).style.display = "block";
				document.getElementById("obs_"+$(this).val()).style.display = "block";
				document.getElementById("lcanti_"+$(this).val()).style.display = "block";
				document.getElementById("lobs_"+$(this).val()).style.display = "block";
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
	function c_total(id,pre,id_) 
	{
		var cant=document.getElementById('canti_'+id).value;
		var tot=cant*pre;
		//alert(tot+"#total_"+id);
		$("#total_"+id_).html('TOTAL $ '+tot);
	}
	function agregar_(id,nom)
	{
		//alert(' codido mesa '+id);
		var selected = '';
		var selected_c='';
		var selected_o='';
		$('#formu1 input[type=checkbox]').each(function(){
            if (this.checked) 
			{
				selected += $(this).val()+', ';
				selected_c += document.getElementById('canti_'+$(this).val()).value+', '; 
				selected_o += document.getElementById('obs_'+$(this).val()).value+', '; 
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
				bus : bus,
				"ajax_page": 'pop1',
				cl: 'ing_p1'
				
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
</script>

</body>
</html>
