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
include("chequear_seguridad.php"); 
require_once("../controlador/panel.php");
//enviar correo
require_once("../../diskcover_lib/phpmailer/PHPMailerAutoload.php");
	//echo ' jjj '.$_SESSION['autentificado'];
	//die();
	//asignamos la empresa
	if(isset($_GET['mos'])) 
	{
		$_SESSION['INGRESO']['empresa']=$_GET['mos'];
		//nombre empresa
		$_SESSION['INGRESO']['noempr']=$_GET['mos1'];
		$_SESSION['INGRESO']['item']=$_GET['mos3'];
		$cod = explode("-", $_GET['mos']);
		if($cod[0]!='')
		{
			//echo $cod[0].' -- ';
			//die();
			$empresa=getEmpresasId($cod[0]);
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
				//verificamos si es sql server o mysql para consultar periodos
				if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) ) 
				{
					if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
					{
						$periodo=getPeriodoActualSQL();
						//echo $periodo[0]['Fecha_Inicial'];
						$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
						$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
						
					}
					else
					{
						//mysql que se valide en controlador
						//echo ' ada '.$_SESSION['INGRESO']['Tipo_Base'];
						$periodo=getPeriodoActualSQL();
						//echo $periodo[0]['Fecha_Inicial'];
						$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
						$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
					}
				}
				
			}
			//die();
		}
	}
	if(isset($_GET['mos2'])=='e') 
	{
		//destruimos la sesion
		unset( $_SESSION['INGRESO']['empresa'] ); 
		unset( $_SESSION['INGRESO']['noempr'] );  	
		unset( $_SESSION['INGRESO']['modulo']);
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
		//eliminamos filtros
		unset($_SESSION['FILTRO']['cam1']);
		unset($_SESSION['FILTRO']['cam2']);
		unset($_SESSION['FILTRO']['cam3']);
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
  <title>DiskCover System login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../diskcover_lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../diskcover_lib/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../diskcover_lib/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../diskcover_lib/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../diskcover_lib/dist/css/skins/_all-skins.min.css">
  
  
  <!-- daterange picker 
  <link rel="stylesheet" href="../../diskcover_lib/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker 
  <link rel="stylesheet" href="../../diskcover_lib/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs 
  <link rel="stylesheet" href="../../diskcover_lib/plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker 
  <link rel="stylesheet" href="../../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker 
  <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 
  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
-->
  
  
  
  <script src="../../diskcover_lib/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- jQuery 3 -->
<script src="../../diskcover_lib/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../diskcover_lib/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../diskcover_lib/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../diskcover_lib/plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../diskcover_lib/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../diskcover_lib/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../../diskcover_lib/bower_components/moment/min/moment.min.js"></script>
<script src="../../diskcover_lib/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datepicker -->
<script src="../../diskcover_lib/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../../diskcover_lib/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../../diskcover_lib/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../../diskcover_lib/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../diskcover_lib/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="../../diskcover_lib/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../diskcover_lib/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../diskcover_lib/dist/js/demo.js"></script>
  
 <link rel="stylesheet" href="../../diskcover_lib/dist/css/sweetalert.css">
  <script src="../../diskcover_lib/dist/js/sweetalert-dev.js"></script>
  
  <script src="../../diskcover_lib/dist/js/sweetalert2.min.js"></script>
  <link rel="stylesheet" href="../../diskcover_lib/dist/css/sweetalert2.min.css">
  <link rel="shortcut icon" href="../../diskcover_img/jpg/logo.jpg" />
  
<!--    <script src="../../diskcover_lib/dist/js/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../diskcover_lib/dist/css/sweetalert2.css"> -->
  
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
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo 
   <a href="panel.php" class="logo">
	  <span class="logo-mini"> <img src="../../diskcover_img/gif/logosMod.gif"><b>D</b>C</span>
	  <span class="logo-lg"><b>Disk</b>Cover</span>
	</a>-->
    <!-- Header Navbar: style can be found in header.less navbar-static-top style='height:<?php //echo $tam_m;?>%;'-->
    <nav class="navbar navbar-fixed-top" >
		 <!--<div class="container">-->
		  <?php
				require_once("header.php");
		    ?>
		  <!-- Sidebar toggle button
		  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </a>-->

		  <div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
			  <!-- Messages: style can be found in dropdown.less-->
			  
			  <!-- Notifications: style can be found in dropdown.less -->
			  
			  <!-- Tasks: style can be found in dropdown.less -->
			  
			  <!-- User Account: style can be found in dropdown.less -->
			  <li class="dropdown user user-menu">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				  <img src="../../diskcover_img/jpg/sinimagen.jpg" class="user-image" alt="User Image">
				  <span class="hidden-xs"><?php	
						//var_dump($_SESSION['INGRESO']);
						echo $_SESSION['INGRESO']['Nombre'];
					?></span>
				</a>
				<ul class="dropdown-menu">
				  <!-- User image -->
				  <li class="user-header">
					<img src="../../diskcover_img/jpg/sinimagen.jpg" class="img-circle" alt="User Image">

					<p>
					<?php	
						//var_dump($_SESSION['INGRESO']);
						echo $_SESSION['INGRESO']['Nombre'];
					?>
					  <small>
						<?php	
							//var_dump($_SESSION['INGRESO']);
							echo $_SESSION['INGRESO']['Entidad'];
						?>
					  </small>
					</p>
				  </li>
				  <!-- Menu Body 
				  <li class="user-body">
					<div class="row">
					  <div class="col-xs-4 text-center">
						<a href="#">Followers</a>
					  </div>
					  <div class="col-xs-4 text-center">
						<a href="#">Sales</a>
					  </div>
					  <div class="col-xs-4 text-center">
						<a href="#">Friends</a>
					  </div>
					</div>-->
					<!-- /.row 
				  </li>-->
				  <!-- Menu Footer-->
				  <li class="user-footer">
					<div class="pull-left">
					  <?php
					  if (!isset($_SESSION['INGRESO']['empresa'])) 
					  {
					  ?>
						  <ol class="breadcrumb">
							<li><a href="#"><i class="fa fa-dashboard"></i> Seleccione empresa</a></li>
						  </ol>
					  <?php
					  }
					  else
						  {
							  ?>
							  <ol class="breadcrumb">
								<li><a href="panel.php?mos2=e">Seleccione empresa</a></li>
							  </ol>
							  
						<?php
						  }
						  ?>
					 <!-- <a href="#" class="btn btn-default btn-flat">Profile</a>-->
					</div>
					<div class="pull-right">
					  <a href="logout.php" class="btn btn-default btn-flat">Salir</a>
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
		<?php
			//llamamos a los parciales para menus
			if (isset($_SESSION['INGRESO']['accion'])) 
			{ 
				//Mayorización
				if ($_SESSION['INGRESO']['accion']=='macom') 
				{
					require_once("contabilidad/macom_m.php");
				}
				//Balance de Comprobacion/Situación/General
				if ($_SESSION['INGRESO']['accion']=='bacsg') 
				{
					require_once("contabilidad/bacsg_m.php");
				}
				//reporte documentos electronicos
				if ($_SESSION['INGRESO']['accion']=='rde') 
				{
					require_once("rde_m.php");
				}
				if ($_SESSION['INGRESO']['accion']=='compro') 
				{
					require_once("contabilidad/compro_m.php");
				}	
				if ($_SESSION['INGRESO']['accion']=='cambioe') 
				{
					require_once("empresa/cambioe_m.php");
				}	
			}	
				?>	
		<!--</div>-->
		<?php
		//echo $_SESSION['INGRESO']['empresa'].' sdsddssd ';
			if($_SESSION['INGRESO']['RUCEnt']=='0590031984001'  ) 
			{
				
				//echo "entro";
				//die();
				if(!isset($_SESSION['INGRESO']['IP_VPN_RUTA']) ) 
				{
					//para redireccionar
					//$_SESSION['INGRESO']['solouna']=0;
					//hacemos sesion automatica
					$_SESSION['INGRESO']['noempr']='CALZACUERO CA';
					$_SESSION['INGRESO']['item']='002';
					
					$empresa=getEmpresasId('804');
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
						//echo $_SESSION['INGRESO']['IP_VPN_RUTA'];
						//verificamos si es sql server o mysql para consultar periodos
						if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) ) 
						{
							if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
							{
								$periodo=getPeriodoActualSQL();
								//echo $periodo[0]['Fecha_Inicial'];
								$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
								$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
							}
							else
							{
								//mysql que se valide en controlador
								//echo ' ada '.$_SESSION['INGRESO']['Tipo_Base'];
								$periodo=getPeriodoActualSQL();
								//echo $periodo[0]['Fecha_Inicial'];
								$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
								$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
							}
						}
					}
					//verificacion titulo accion
					$_SESSION['INGRESO']['ti']='';
					if(isset($_GET['ti'])) 
					{
						$_SESSION['INGRESO']['ti']=$_GET['ti'];
					}
					else
					{
						unset( $_SESSION['INGRESO']['ti']);
						$_SESSION['INGRESO']['ti']='DOCUEMNTO ELECTRÓNICO';
					}
				}
				//echo $_SESSION['INGRESO']['Cambio'].' ddddddddddddddddd ';
				if ($_SESSION['INGRESO']['Cambio']==0)
				{
					//$_SESSION['INGRESO']['Cambio']=1;
					//echo $_SESSION['INGRESO']['Cambio'].' ddddddddddddddddd ';
				?>
					<script>
						/*swal({
							title: "Se requiere Clave Administrador",
							text: 'Introduzca su código:',
							type: 'input',
							inputType: "password",
							showCancelButton: true,
							closeOnConfirm: false,
							animation: "slide-from-top",
							inputPlaceholder: "Código",
						},
						function(inputValue){
							if (inputValue === false) return false;

							if (inputValue === "") {
								swal.showInputError("Debe escribir su código!");
								return false;
							}
						});*/
						async function getFormValues () {
							const {value: formValues} = await Swal.fire({
							  title: 'Nueva Contraseña',
							  html:
								'<input id="swal-input1" class="swal2-input" placeholder="Contraseña" type="password">' +
								'<input id="swal-input2" class="swal2-input" placeholder="Re-escribir contraseña" type="password">',
							  focusConfirm: false,
							  preConfirm: () => {
								if (document.getElementById('swal-input1').value === false) return false;

								if (document.getElementById('swal-input1').value === "") {
									//swal.showInputError("Debe escribir su nueva clave!");
									return 1;
								}
								else
								{
									if (document.getElementById('swal-input2').value === "") {
										//swal.showInputError("Debe re-escribir su nueva clave!");
										return 2;
									}
									else
									{
										if (document.getElementById('swal-input1').value != document.getElementById('swal-input2').value) {
											//swal.showInputError("No coincide su nueva clave!");
											return 3;
										}
										else
										{
											return 4;
										}
									}
								}
							  }
							})

							if (formValues) {
								//swal.showInputError("Debe escribir su nueva clave!");
								if(formValues==1)
								{
									Swal.fire({
									  type: 'error',
									  title: 'Oops...',
									  text: 'Debe escribir su nueva contraseña!',
									  showCancelButton: false,
									  confirmButtonColor: '#3085d6',
									  cancelButtonColor: '#d33',
									  confirmButtonText: 'OK!'
									}).then((result) => {
									  if (result.value) {
										location.href="logout.php";
									  } 
									})
									//location.href="logout.php";
								}
								if(formValues==2)
								{
									Swal.fire({
									  type: 'error',
									  title: 'Oops...',
									  text: 'Debe re-escribir su nueva contraseña!'
									}).then((result) => {
									  if (result.value) {
										location.href="logout.php";
									  } 
									})
								}
								if(formValues==3)
								{
									Swal.fire({
									  type: 'error',
									  title: 'Oops...',
									  text: 'No coincide las contraeñas!'
									}).then((result) => {
									  if (result.value) {
										location.href="logout.php";
									  } 
									})
								}
								//llamamos ajax
								if(formValues==4)
								{
									$.post('ajax/cambiarcon.php', { clave: document.getElementById('swal-input1').value,
									id: <?php echo $_SESSION['INGRESO']['ID']; ?> }, 
										function(returnedData){
									 console.log(returnedData);
									 if(returnedData.success){
										 Swal.fire({
										  //position: 'top-end',
										  type: 'success',
										  title: 'Su contraseña fue cambiada con exito!',
										  showConfirmButton: true
										  //timer: 2500
										}).then((result) => {
										  if (result.value) {
											location.href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti="+
										"<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0";
										  } 
										});
										//swal("Correcto!", 'Autorizado por: ' + returnedData.name, "success");
										
										//location.href="ver_nominas.php?borrar=" + name;
									 }else{
										 Swal.fire({
										  type: 'error',
										  title: 'Oops...',
										  text: 'No se pudo cambiar contraeñas!'
										});
									 }
										}, 'json');
									
									//location.href="logout.php";
									/*return [
											  document.getElementById('swal-input1').value,
											  document.getElementById('swal-input2').value
											]*/
								}
								
							  //Swal.fire(JSON.stringify(formValues))
							}
						}
						getFormValues();
						//swal("Error!", 'Debe seleccionar una opcion', "warning");
					</script>
					<?php
					$_SESSION['INGRESO']['empresa']='804-002';
				}
				else
				{
					$_SESSION['INGRESO']['empresa']='804-002';
					//$_SESSION['INGRESO']['solouna']=0;
				    //echo $_SESSION['INGRESO']['solouna'].' fffff ';
					//verificamos que la url sea siempre rde.php$host= $_SERVER["HTTP_HOST"];
					$host= $_SERVER["HTTP_HOST"];
					$url= $_SERVER["REQUEST_URI"];
					$url = $host . $url;
					//echo $url;
					$cadena_de_texto = 'Esta es la frase donde haremos la búsqueda';
					$bus   = 'rde.php';
					$posicion_coincidencia = strpos($url, $bus);
					 $ban=0;
					if($_SESSION['INGRESO']['Tipo_Usuario']!='user')
					{
						//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
						if ($posicion_coincidencia === false) 
						{
							?>	
							<script>
								location.href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti="+
												"<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0";
							</script>
											<?php
						} 
						else 
						{
							
						}
					}
				}
			}
		?>	
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <!-- <aside class="main-sidebar">
    
    <section class="sidebar">
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../../diskcover_img/jpg/sinimagen.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php	
					//var_dump($_SESSION['INGRESO']);
					echo $_SESSION['INGRESO']['Nombre'];
				?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form 
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>-->
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <!-- <ul class="sidebar-menu" data-widget="tree">
        <li class="header">Menu Principal</li>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-share"></i> <span>Gestion accesos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="entidad.php"><i class="fa fa-circle-o"></i>Entidad</a></li>
			<li><a href="#"><i class="fa fa-circle-o"></i>Empresa</a></li>
			<li><a href="#"><i class="fa fa-circle-o"></i>Usuario</a></li>
            <li class="treeview">
              <a href="#"><i class="fa fa-circle-o"></i> Accesos
                <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
              </a>
              <ul class="treeview-menu">
                <li><a href="#"><i class="fa fa-circle-o"></i> PC</a></li>
                <li class="treeview">
                  <a href="#"><i class="fa fa-circle-o"></i> Empresa
                  </a>
                  <!-- <ul class="treeview-menu">
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                  </ul>-->
               <!--  </li>
              </ul>
            </li>
            
          </ul>
        </li>
		<li>
          <a href="migra4.php" >
            <i class="fa fa-th"></i> <span>Migrador</span>
            <span class="pull-right-container">
              <small class="label pull-right bg-green">M</small>
            </span>
          </a>
        </li>
       
      </ul>
    </section>
  </aside>-->

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <!--<section class="content-header">
      <h1>
        Bienvenido
        <small>a DiskCover</small>
      </h1>
	  <?php
	  if (!isset($_SESSION['INGRESO']['empresa'])) 
	  {
	  ?>
		  <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Seleccione empresa</a></li>
		  </ol>
	  <?php
	  }
	  else
		  {
			  ?>
			  <ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $_SESSION['INGRESO']['noempr']; ?></a></li>
				<li><a href="panel.php?mos2=e">Seleccione empresa</a></li>
			  </ol>
			  
		<?php
		  }
		  ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> prueba</a></li>
        <li><a href="#">prueba</a></li>
        <li class="active">prueba</li>
      </ol>
    </section>-->

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <!--<div class="box-header with-border">
		 <?php
		  if (!isset($_SESSION['INGRESO']['empresa'])) 
		  {
		  ?>
			  <h3 class="box-title">Selecione su empresa</h3>
		<?php
		  }
		  else
		  {
			  ?>
			  <h3 class="box-title"><?php echo $_SESSION['INGRESO']['noempr']; ?></h3>
		<?php
		  }
		  ?>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>-->
        <div class="box-body">
			
			 <?php
			  if (!isset($_SESSION['INGRESO']['empresa'])) 
			  {
				  $empresa=getEmpresas($_SESSION['INGRESO']['IDEntidad']);
				  $i=0;
				  ?>
					<br/>
					<br/>
				  <div class="row">
						<div class="col-lg-9 col-xs-9">
						
							<label>Empresas asociadas a este usuario</label>
							<select class="form-control select2" style="width: 100%;" name="sempresa" id="sempresa" onchange="cambiar('sempresa')">
							  
							<option value='0-0'>
								Seleccione empresa
							</option>
					
				   <?php
				  foreach ($empresa as &$valor) 
				  {
					
					?>
						<option value='<?php echo $valor['ID'].'-'.$valor['Item']; ?>'>
							<?php echo $valor['Empresa']; ?>
						</option>
							
					 <?php
					 $i++;
				  }
			  ?>
							</select>
						</div>
					</div>
			<?php
			  }
			  else
			  {
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
					//si esta en panel
					if($ban==0)
					{
					?>
						<!--<h3 class="box-title">Modulos </h3>-->
						
						<br/>
						<div class="box-body">
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Ahorros">
								<i ><img src="../../diskcover_img/gif/Ahorros.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Ahorros
							</a>
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Anexos">
								<i ><img src="../../diskcover_img/gif/Anexos.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Anexos
							</a>
							
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Constructor">
								<i ><img src="../../diskcover_img/gif/Constructor.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Constructor
							</a>
						
							
							<a class="btn btn-app" href="contabilidad.php?mod=contabilidad" style='width:60px;' data-toggle="tooltip" title="Contabilidad">
								<i ><img src="../../diskcover_img/gif/Contabilidad.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Contabilidad
							</a>
							<!--<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Credito">
								<i ><img src="../../diskcover_img/gif/Credito.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Credito
							</a>-->
														
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Factura">
								<i ><img src="../../diskcover_img/gif/Factura.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Factura
							</a>
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Gerencia">
								<i ><img src="../../diskcover_img/gif/Gerencia.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Gerencia
							</a>
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Inventario">
								<i ><img src="../../diskcover_img/gif/Inventario.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Inventario
							</a>
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Mantenimento">
								<i ><img src="../../diskcover_img/gif/logosMod.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i>Mantenimento
							</a>
							
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Respaldo">
								<i ><img src="../../diskcover_img/gif/Respaldo.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> Respaldo
							</a>
							<a class="btn btn-app" style='width:60px;' data-toggle="tooltip" title="Rol de Pago">
								<i ><img src="../../diskcover_img/gif/RolPago.gif" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:80%;'></i> RolPago
							</a>
							
							<a class="btn btn-app" href="migra4.php" style='width:60px;' data-toggle="tooltip" title="Migracion">
								<i class="fa fa-fw fa-server"></i> Migracion
							</a>
							<a class="btn btn-app" href="afe.php?mod=contabilidad" style='width:60px;' data-toggle="tooltip" title="Autorizacion Factura Electronica">
								<i class="fa fa-fw fa-file-excel-o"></i> Aut. Fact. Elec.
							</a>
							<a class="btn btn-app" href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electrónico" style='width:60px;' data-toggle="tooltip" title="Reporte Documento Electronico">
								<i class="fa fa-fw fa-file-excel-o"></i> Rep. Doc. Elect.
							</a>
							<?php
							if($_SESSION['INGRESO']['RUCEnt']=='1792164710001'  ) 
							{
							?>
								<a class="btn btn-app" href="empresa.php?mod=empresa&acc=cambioe&acc1=Modificar empresa" style='width:60px;' data-toggle="tooltip" title="Administrar empresas">
									<i class="fa fa-fw fa-industry"></i> Admi. Empre
								</a>
							<?php
							}
							?>
						</div>
					<?php
						require_once("footer.php");
					}
			  }
			  
			  ?>
	<script >
		//$(document).on('change', '#sempresa', function(event) {
			 //$('#servicioSelecionado').val($("#sempresa option:selected").text());
			// alert($("#sempresa option:selected").text());
		//});
		$(document).ready(function () {

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
						//alert('entrooo '+idMensaje);
					});
			}
			//caso entidad-empresa
			if(idMensaje=='entidad')
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
      
