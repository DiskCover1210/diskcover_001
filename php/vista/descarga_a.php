<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

if(!isset($_SESSION)) 
{	
	session_start();
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>DiskCover System descarga</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="appr/lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="appr/lib/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="appr/lib/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="appr/lib/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="appr/lib/dist/css/skins/_all-skins.min.css">
  <link rel="shortcut icon" href="../../img/jpg/logo.jpg" />
  


  <link rel="stylesheet" href="appr/lib/bower_components/jquery-ui/themes/base/jquery-ui.css">


  <!-- jQuery 3 -->
<script src="appr/lib/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="appr/lib/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="appr/lib/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="appr/lib/plugins/input-mask/jquery.inputmask.js"></script>
<script src="appr/lib/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="appr/lib/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="appr/lib/bower_components/moment/min/moment.min.js"></script>
<script src="appr/lib/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="appr/lib/bower_components/jquery-ui/jquery-ui.js"></script>

<!-- bootstrap datepicker -->

<!-- bootstrap color picker -->
<script src="appr/lib/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="appr/lib/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="appr/lib/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="appr/lib/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="appr/lib/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="appr/lib/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="appr/lib/dist/js/demo.js"></script>
  
 <link rel="stylesheet" href="appr/lib/dist/css/sweetalert.css">
  <script src="appr/lib/dist/js/sweetalert-dev.js"></script>
  
  <script src="appr/lib/dist/js/sweetalert2.min.js"></script>
  <script type="text/javascript" src="appr/lib/dist/js/typeahead.js"></script>
  <link rel="stylesheet" href="appr/lib/dist/css/sweetalert2.min.css">
 
<!--<link href="../../css/style.css" rel="stylesheet">-->
<link href="../../css/slider1.css" rel="stylesheet">
<!--<link href="../../css/slider2.css" rel="stylesheet">-->
<link href="../../css/slider3.css" rel="stylesheet">
<link href="../../css/style-programador.css" media="screen" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Droid+Sans:400,700">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Droid+Serif">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Boogaloo">
<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Economica:700,400italic">
<link rel="shortcut icon" href="../../img/jpg/logo.jpg" />

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
    <a href="panel.php" class="logo">
		<!-- mini logo for sidebar mini 50x50 pixels -->
		<span class="logo-mini">
			<div class="Icon">
				<img src="../../img/jpg/logo.jpg" class="img-circle" alt="User Image" style="width: 50%; height:50%;">
				<!--<span class="glyphicon glyphicon-user"></span>-->
			</div>
		</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Disk</b>Cover</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
			 
											
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../img/jpg/sinimagen.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"> 
              	<?php	echo 'Descargas';?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <p>
                  <?php	echo 'Descargas'; ?>
                  <small></small>
                </p>
              </li>
             
             
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                    <a href="logout.php"  data-toggle="tooltip"  data-placement="bottom" title="Cerrar sesión" style=' float: right'>
						<img src="../../img/png/salirs.png"  width='70%' height='50%'>
					</a>
                </div>
                <div class="pull-right">
                    <a href="panel.php?mos2=e"  data-toggle="tooltip"  data-placement="bottom" title="Salir de empresa" style=' float: right'>
						<img src="../../img/png/salire.png"  width='70%' height='50%'>
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
    
     <section class="content">
     	<div class="row">
     		 
			<div id="insta" class="color white">
				<!--start: Wrapper -->
				<div class="container">
					<!-- start: Page Title -->
					<div id="page-title">

						<div id="page-title-inner">

							<h2><span>Descargas</span></h2>

						</div>

					</div>
					<!-- end: Page Title -->

					<!-- start: Row -->
					<div class="row-fluid">
						<div class="span12">
								
								<div class="alert alert-success parpadea" role="alert">
									<h4 class="alert-heading">Importante!</h4>
									<p class="text-justify">En esta versión se incluyen el Anexo Transaccional Simplificado (ATS) 
									para presentar desde abril del 2020, y nuevos formatos de excel para subir las ventas del ATS; Se incluye un archivo master
									en formato de excel para los descuentos en el rol de pago de el tiempo parcial, medio sueldo o cambio de jornada 
									establecido por el COVID-19, así como también algunas mejoras en los datos de Clientes y Proveedores para la 
									presentación del ATS.
									</p>
									<hr>
									<p class="mb-0">Fecha de Actualización: 28 de mayo de 2020</p>
								</div>
								<!--<p class="texto_centrar">Fecha de Actualizacion: 01 de Marzo del 2020</p>
								<p class="parpadea" style='font-size: 125%'>
									En esta versión se incluyen el Anexo Tranaccional Simplificado (ATS) para presentar desde junio,
									y nuevos formatos de excel para subir las ventas del ATS; así como también algunas mejoras en los
									datos de Clientes y Proveedores para la presentacion del ATS.
								</p>-->
								<p>
								<h4 class="pt-5 pb-3">PASOS A SEGUIR PARA LA ACTUALIZACION DEL SISTEMA</h4>
								<dl class="row">
									<dt class="col-sm-3">1. DESCARGAR EL ARCHIVO DE ACTUALIZACIÓN</dt>
									<dd class="col-sm-9">
										<p class="text-justify">Lo primero que tiene que hacer es descargar el archivo de actualización presionando <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/UPDATE_DB.zip" title="Archivo: UPDATE_DB">click aquí</a> para que empiece la descarga, debe fijarse bien donde se bajan estos archivos.</p>
										<p class="text-justify"><b>NOTA:</b> Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>
									</dd>

									<dt class="col-sm-3">2. DESCARGAR LOS PROGRAMAS DE ACTUALIZACIÓN</dt>
									<dd class="col-sm-9">
										<p class="text-justify">De la misma manera como se bajó el archivo anterior, se debe descargar presionando <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/UPDATE_EXE.zip" title="Archivo: UPDATE_EXE">click aquí</a> el archivo comprimido, donde se encuentran los programas ejecutables; después se debe descomprimir el archivo, este creará una carpeta llamada UPDATE_EXE, en el interior de esta carpeta se encuentran los nuevos ejecutables (programas) actualizados.</p>
										<p class="text-justify">Copie estos archivos y péguelos en el interior de la carpeta SISTEMA, en donde se encuentra instalado el sistema, sea en la unidad local o de red.</p>
										<p class="text-justify"><b>NOTA:</b> Si le pide que reemplace los archivos seleccionar que SI.</p>
									</dd>
								  
									<dt class="col-sm-3">3. DESCARGAR DE IMAGENES Y FONDOS</dt>
									<dd class="col-sm-9">
										<p class="text-justify">Una vez actualizado el sistema, si al ejecutar los módulos tuviera inconvenientes debe <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/UPDATE_FONDO.zip" title="Archivo UPDATE_FON">presionar aquí</a> para actualizar</p>
										<p class="text-justify"><b>NOTA:</b> Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>
									</dd>  
								</dl> 
								<!--<br/>
								AL BAJARCE ESTA ACTUALIZACION, USTED ESTA CONCIENTE DE QUE HA CANCELADO SU RENOVACION, CASO CONTRARIO NO NOS
								RESPONSABILIZAMOS POR LOS INCONVENIENTES QUE SE SUCITEN POR ESTA ACTUALIZACION.
								<br>
								1.- DESCARGAR EL ARCHIVO DE ACTUALIZACIÓN:
								<br>
								Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/UPDATE_DB.zip" title="Archivo: UPDATE_DB">click aquí</a>
								para que empiece la descarga,
								debe fijarce bien donde se bajan estos archivos.
								NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.
								<br>
								2.- DESCARGAR LOS PROGRAMAS DE ACTUALIZACIÓN:
								<br>
								 De la misna manera como se bajó el archivo anterior, se debe descargarce presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/UPDATE_EXE.zip" title="Archivo: UPDATE_EXE">click aquí</a>
								 el archivo comprimido, donde se encuentran los programas ejecutables; después se debe descomprimir el archivo, este creará una
								 carpeta llamada UPDATE_EXE, en el interior de esta carpeta se encuentran los nuevos ejecutables (programas) actualizados.
								 <br>
								 <br>
								 Copie estos archivos y peguelos en el interior de la carpeta SISTEMA, en donde se encuentra instalado el sistema, sea en la
								 unidad local o de red.
								 <br>
								 <br>
								 NOTA: Si le pide que reemplace los archivos seleccionar que SI.
								 <br>
								 3.- DESCARGAR DE IMAGENES Y FONDOS
								Una vez actualizado el sistema, si al ejecutar los módulos tuviera inconvenientes debe
								<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/UPDATE_FONDO.zip" title="Archivo UPDATE_FONDO">presionar Aqui</a>
								para actualizar
								 <br>-->
								4.- ACTUALIZACION DE LA NUEVA VERSION:
								<br>
								<div class="row-fluid">

									<div class="span6">

										<p>
											 Una vez que se realizarán los pasos previos, usted encontrará dentro de la carpeta SISTEMA, una aplicacion
											 (programa ejecutable) que se llama ACTUALIZAR, presione doble click a esa aplicación y espere que se termine
											 de ejecutar el programa.
											 <img src="../../img/png/actualizar/actualizar_01.png" class="img_actualizar">
										</p>

									</div>

									<div class="span6">

										<p>
											 <br>
											<img src="../../img/png/actualizar/actualizar_02.png" class="img_actualizar">
											<br>
											 Enseguida que se ejecutó el sistema de actualización, aparecerá en pantalla el siguiente cuadro, presionamos un click
											 sobre el boton [ACTUALIZAR SISTEMA]; y esperamos que el programa presente un cuadro.
											 <br>
										</p>

									</div>

								</div>

								 <br>

								 En este paso, aparecerá el siguiente cuadro de selección de archivos, es aquí donde buscamos la carpeta donde bajamos el
								 archivo UPDATE_DB, que se encuentra empaquetado (Zippiado), presionamos un click sobre el y luego presionamos un click
								 sobre el botón [ABRIR] u open según el idioma del sistema operativo.
								 <br>
								  <img src="../../img/png/actualizar/actualizar_03.png" class="img_actualizar">
								  <br>
								  <br>
								  <img src="../../img/png/actualizar/actualizar_04.png" class="img_actualizar">
								  <br>
								   Al terminar el proceso de actualización en el paso anterior, enseguida aparecera el siguiente cuadro, donde le informa el
								 sistema que se realizo el proceso de actualización con éxito, presionamos click sobre el botón [ACEPTAR] y listo ya puede
								 ejecutar el sistema sin problemas.
								 <br>
								 Si al presionar sobre el botón [ACEPTAR] apareciera otro cuadro con letras rojas, el sistema se ejecutará con total
								 normalidad pero solicitamos que se comunique con nuestro personal para concluir otros procesos secundario.
							</p>

						</div>

					</div>
					

					<div class="clearfix"></div>
					<hr class="clean">
				</div> 
				<!--start: Container -->
				<div class="container">

					<!--start: Wrapper
					<div class="wrapper span12"> -->
					<div class="">

						<!-- start: Page Title -->
						<div id="page-title">

							<div id="page-title-inner">

									<h2><span>Instaladores</span></h2>

							</div>

						</div>
						<!-- end: Page Title -->

						<!-- start: Row -->
						<div class="row-fluid">

							<div class ="span12">

								<!-- start: Team -->
								<div id="insta">

									<!-- start: Row -->
									<div class="row-fluid">

										<!-- start: About Member -->
										<h3>PROGRAMAS QUE DEBEN ESTAR INSTALADOS</h3>

										<p>Los siguiente programas, en muchos casos para envitar virus, se encuentran empaquetados (Zippiados),
										los cuales deben ser desempaquetados antes de ejecutarce. En ciertos link de descarga se debe dar click sobre la
										imagen para que empiece la descarga.</p>
										<p>NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>
										
										<div class="row-fluid">
											<div class="span6">
												<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/UPDATE_SETUP.zip"   >
													<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> Controlador del sistema 
													y comprobante electronico
													</p></a>
													<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
													 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/UPDATE_SETUP.zip" title="Archivo: Controlador del sistema y comprobante electronico">
													 click aquí</a>
													 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.
													 </p>
											</div>
											<div class="span6">
												<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sqlserver/mysql-connector-odbc-3.51.30-win64.msi"   >
													<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> mysql-connector-odbc-3.51.30-win64</p></a>
													<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
													 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sqlserver/mysql-connector-odbc-3.51.30-win64.msi" title="Archivo: mysql-connector-odbc-3.51.30-win64">click aquí</a>
													 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
											</div>
										</div>
										<div class="row-fluid">
											<div class="span6">
												<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sqlserver/mysql-connector-odbc-3.51.30-win32.msi"   >
													<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> mysql-connector-odbc-3.51.30-win32</p></a>
													<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
													 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sqlserver/mysql-connector-odbc-3.51.30-win32.msi" title="Archivo: mysql-connector-odbc-3.51.30-win32">click aquí</a>
													 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
											</div>
											<div class="span6">
												<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/SISTEMA.zip"   >
													<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> sistema_setup</p></a>
													<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
													 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/SISTEMA.zip" title="Archivo: sistema_setup">click aquí</a>
													 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
											</div>
										</div>
									</div>
									<!-- end: Row -->
								</div>
								<!-- end: Team -->
							</div>
						</div>
		<!-- end: Row -->
					</div>
					<!-- end: Wrapper -->

				</div>
				<!--end: Container -->

			</div>
			<div id="descae" class="color blue transparent">

				<!--start: Container -->
				<div class="container">

					<!--start: Wrapper -->
					<div class="">



					<!-- start: Page Title -->
					<div id="page-title1">

						<div id="page-title-inner">

								<h2><span>Descargas Externas</span></h2>

						</div>

					</div>
					<!-- end: Page Title -->

					<!-- start: Row -->
					<div class="row-fluid">

						<div class="span12">

							<!-- start: Contact Info -->
							<h3>LISTADO DE ENLACES</h3>
							<!-- end: Contact Info -->

						</div>

					</div>
					<!-- end: Row -->

					<hr class="clean">

					<!-- start: Row -->
					<div class="row-fluid">

						<div class="span6">
							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/adobe/AdobeReader_DC.exe"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> AdobeReader DC</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/adobe/AdobeReader_DC.exe" title="Archivo: AdobeReader_DC">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
						</div>
						<div class="span6">
							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/pdf/PDF_Creator.exe"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> PDF Creator</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/pdf/PDF_Creator.exe" title="Archivo: PDF Creator">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
						</div>
					</div>
					<div class="row-fluid">
					
						
						<!-- start: Map 
						<div class="span6">

							<a href="web_download/Note_pp_Installer.exe"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> Note pp Installer 32 bit</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="web_download/Note_pp_Installer.exe" title="Archivo: Note pp Installer 32 bit">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.
								 NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>

						</div>-->
						<div class="span6">

							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/varios/Note_pp_Installer_x64.exe"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> Note pp Installer 64 bit</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/varios/Note_pp_Installer_x64.exe" title="Archivo: Note pp Installer 64 bit">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.
								 NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>

						</div>
					</div>
					<div class="row-fluid">

						<!-- start: Map -->
						<div class="span6">

							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sqlserver/SQLServer2017-x64-ESN-Dev.iso"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'>SQLServer2017-x64-ESN-Dev</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sqlserver/SQLServer2017-x64-ESN-Dev.iso" title="Archivo: SQLServer2017-x64-ESN-Dev">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.
								 NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>

						</div>
						<!-- end: Map -->

						<div class="span6">
							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sqlserver/SSMS-Setup-ENU.exe"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> SSMS-Setup-ENU</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sqlserver/SSMS-Setup-ENU.exe" title="Archivo: SSMS-Setup-ENU">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
						</div>

					</div>
					<div class="row-fluid">

						<!-- start: Map -->
						<div class="span6">

							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/ats.plugin.1.1.5.zip"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'>ats.plugin.1.1.5</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/ats.plugin.1.1.5.zip" title="Archivo: ats.plugin.1.1.5">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.
								 NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>

						</div>
						<!-- end: Map -->

						<div class="span6">
							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/dimm-aps.1.0.57.zip"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> dimm-aps.1.0.57</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/dimm-aps.1.0.57.zip" title="Archivo: dimm-aps.1.0.57">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
						</div>

					</div>
					<div class="row-fluid">

						<!-- start: Map -->
						<div class="span6">

							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/DimmFormularios-1.13-Win.exe"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'>DimmFormularios-1.13-Win</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/DimmFormularios-1.13-Win.exe" title="Archivo: DimmFormularios-1.13-Win">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.
								 NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>

						</div>
						<!-- end: Map -->

						<div class="span6">
							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/jaxb-api.exe"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> jaxb-api</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/jaxb-api.exe" title="Archivo: jaxb-api">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
						</div>

					</div>
					<div class="row-fluid">

						<!-- start: Map -->
						<div class="span6">

							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/rdep.zip"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'>rdep</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/rdep.zip" title="Archivo: rdep">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.
								 NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>

						</div>
						<!-- end: Map -->

						<div class="span6">
							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/reoc_2_0_4.zip"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> reoc_2_0_4</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/sri/reoc_2_0_4.zip" title="Archivo: reoc_2_0_4">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.</p >
						</div>

					</div>
					<div class="row-fluid">

						<!-- start: Map -->
						<div class="span6">

							<a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/zip/winzip_es.exe"   >
								<p align='left'><img src="../../img/png/pc.png" class="img_descarga" width='15%'> winzip_es</p></a>
								<p >Lo primero que tiene que hacer es descargar el archivo de actualización presionando
								 <a href="<?php echo $_SESSION['INGRESO']['RUTA']; ?>download/zip/winzip_es.exe" title="Archivo: winzip_es">click aquí</a>
								 para que empiece la descarga, debe fijarce bien donde se bajan estos archivos.
								 NOTA: Se aconseja borrar los archivos bajados anteriormente para que no se cometan errores.</p>

						</div>
					</div>
					<!-- end: Row -->

					</div>
					<!-- end: Wrapper -->

				</div>
				<!--end: Container -->

			</div>
    	</div>
    	
    </section>
  </div>
  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <!--<b>Version</b> 2.4.0-->
    </div>
    <strong>Copyright &copy;  <a href="https://erp.diskcoversystem.com">DiskCover System</a>.</strong> todos los derechos reservados.
  </footer>

 
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<script>
	
</script>

</body>
</html>
