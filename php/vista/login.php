<?php
/**
 * Autor: Orlando quintero.
 * Mail:  discoversystem@msn.com
 * web:   www.discoversystem.com
 */
//require_once 'determ.php';

if(!isset($_SESSION)) 
{	
	session_start();
}
if (isset($_SESSION['autentificado']) != "VERDADERO") 
{ 
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) 
	{
		$uri = 'https://';
	}
	else
	{
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	
	if($uri=='https://erp.diskcoversystem.com')
	{
		$_SESSION['INGRESO']['RUTA']='/';
		$_SESSION['INGRESO']['LOCAL_MYSQL'] = '';
		$_SESSION['INGRESO']['LOCAL_SQLSERVER'] = '';
	}
	else
	{
		$_SESSION['INGRESO']['RUTA']='https://erp.diskcoversystem.com/';
		$_SESSION['INGRESO']['LOCAL_MYSQL'] = '';
		$_SESSION['INGRESO']['LOCAL_SQLSERVER'] = '';
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>DiskCover System login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- vinculo a bootstrap 
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <!-- Temas
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">-->
    <!-- Estilo local de login
    <link rel="stylesheet" type="text/css" href="estilologin.css">--> 
	<!-- Bootstrap 3.3.7 -->
	  <link rel="stylesheet" href="../../lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
	  <!-- Font Awesome -->
	  <link rel="stylesheet" href="../../lib/bower_components/font-awesome/css/font-awesome.min.css">
	  <!-- Ionicons -->
	  <link rel="stylesheet" href="../../lib/bower_components/Ionicons/css/ionicons.min.css">
	  <!-- Theme style -->
	  <link rel="stylesheet" href="../../lib/dist/css/AdminLTE.min.css">
	  <!-- iCheck -->
	  <link rel="stylesheet" href="../../lib/plugins/iCheck/square/blue.css">
	  <link rel="stylesheet" type="text/css" href="../../css/estilologin.css">
	  <link rel="shortcut icon" href="../../img/jpg/logo.jpg" />
	  
	  <link rel="stylesheet" href="../../lib/dist/css/sweetalert.css">
	  <script src="../../lib/dist/js/sweetalert-dev.js"></script>
	  
	  <script src="../../lib/dist/js/sweetalert2.min.js"></script>
	  <script type="text/javascript" src="../../lib/dist/js/typeahead.js"></script>
	  <link rel="stylesheet" href="../../lib/dist/css/sweetalert2.min.css">
	  
	
	<link href="https://cdn.jsdelivr.net/npm/simple-line-icons@2.4.1/css/simple-line-icons.css" rel="stylesheet" type="text/css" />
	<!--<link href="/components/com_k2/css/k2.css?v=2.10.2&b=20191212" rel="stylesheet" type="text/css" />-->  

    <!-- CSS -->  
    <link rel="stylesheet" href="/templates/css/bootstrap.min.css">
    <link rel="stylesheet" href="/templates/css/template_home.css">
    <link rel="stylesheet" href="/templates/css/animate.min.css"> 
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" 
	integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
</head>
<body >
	<!--<div class="row justify-content-between align-items-center">
		<div class="d-none d-md-block col-12 col-sm-8 col-md-9 align-middle">
			<a href="https://www.google.com/maps?cid=9021962645809441259&amp;authuser=1" target="_blank">
				<i class="fas fa-map-marker-alt pr-1"></i><span class="pr-3">Atacames N23-226 y Av. la Gasca, Quito</span>
			</a>
			<script>
				var login  = 'info';
				var server = 'diskcoversystem.com';
				var email  = login+'@'+server;
				var url = 'mailto:'+email;
				document.write('<a title="e-mail" rel="nofollow" href="'+url+'"><i class="fas fa-envelope pr-1"></i><span class="pr-3">'+email+'</span></a>');
			</script>
			<a title="e-mail" rel="nofollow" href="mailto:info@diskcoversystem.com">
				<i class="fas fa-envelope pr-1"></i><span class="pr-3">info@diskcoversystem.com</span>
			</a> 
			<a href="tel:+59323210051">
				<i class="fas fa-phone pr-1"></i>
				<span class="pr-3">(02) 321-0051</span>
			</a>
		</div>
		<div class="d-block d-md-none col-6 col-sm-4 col-md-3">
			<a href="https://www.google.com/maps?cid=9021962645809441259&amp;authuser=1" target="_blank"><i class="fas fa-map-marker-alt pr-3"></i></a>
			<script>
				var login  = 'info';
				var server = 'diskcoversystem.com';
				var email  = login+'@'+server;
				var url = 'mailto:'+email;
				document.write('<a title="e-mail" rel="nofollow" href="'+url+'"><i class="fas fa-envelope pr-3"></i></a>');
			</script>
			<a title="e-mail" rel="nofollow" href="mailto:info@diskcoversystem.com"><i class="fas fa-envelope pr-3"></i></a> 
			<a href="tel:+59323210051"><i class="fas fa-phone pr-1"></i></a>
		</div>      
		<div class="col-6 col-sm-4 col-md-3 text-right">
			<a href="https://facebook.com/DiskCoverSystem/" rel="nofollow" target="_blank"><i class="fab fa-facebook pr-3"></i></a>
			<a href="https://instagram.com/DiskCoverSystem/" rel="nofollow" target="_blank"><i class="fab fa-instagram pr-3"></i></a>
			<a href="https://api.whatsapp.com/send?phone=593986524396" rel="nofollow" target="_blank"><i class="fab fa-whatsapp"></i></a>
		</div>
    </div>-->
    <div id="Contenedor" style="background:rgba(201, 223, 241,0.4);">
        <div class="Icon">
			<img src="../../img/jpg/logo.jpg" class="img-circle" alt="User Image" style="width: 20%; height:20%;">
			<!--<span class="glyphicon glyphicon-user"></span>-->
		</div>
       
        <div class="ContentForm">
            <form action="../controlador/login_controller.php" method="post" name="FormEntrar">
				<div id='resul'>
					 <?php
						//Datos para ingresar
						//usuario: marcos@gmail.com
						//Contraseña: test12345
						if(!empty($_GET['men']) ){
						 $men=$_GET['men'];
						  echo $men;
						}
					  
					?>
				</div>
				<!--<div id="alerta" class="alert invisible"></div>-->
                <div class="input-group input-group-lg" >
                  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-home"></i></span>
                  <!-- 1792164710001 -->
				 
				  <input type="text" class="form-control" name="entidad" placeholder="Entidad a la que perteneces" 
				  id="Entidad" aria-describedby="sizing-addon1" required>
                </div>
                <br>
                <div class="input-group input-group-lg" >
                  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
                <!--  <input type="email" class="form-control" name="correo" placeholder="Correo Electrónico/Usuario" id="Correo" aria-describedby="sizing-addon1" required> -->
                  <input type="text" class="form-control" name="correo" placeholder="Correo Electrónico/Usuario" 
				  id="Correo" aria-describedby="sizing-addon1" required onclick='verifient(event,"Entidad");' 
				  onkeyup='verifient(event,"Entidad");'>                    
                </div>                
                <br>
                <div class="input-group input-group-lg" >
                  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
                  <input type="password" name="contra" class="form-control" onclick='verifiuser(event,"Entidad","Correo");' 
				  onkeyup='verifiuser(event,"Entidad","Correo");' placeholder="******" aria-describedby="sizing-addon1" required>
                </div>
                <br>
				<input type="submit" name="submitlog" value="Entrar" class="btn btn-lg btn-primary btn-block btn-signin" id="IngresoLog" />
                <div class="opcioncontra"><a href="">Olvidaste tu contraseña?</a></div>
				<a class="btn btn-lg btn-primary btn-block btn-signin" href="descarga_a.php" style='colo:ffffff;'>
					Descargas Importantes
				</a>
				<!-- <a class="btn btn-lg btn-primary btn-block btn-signin" href="https://erp.diskcoversystem.com/download" style='colo:ffffff;'>
					Descargas Importantes
				</a>
				ejfc19omoshiroi@gmail.com
				-->
            </form>
        </div>
    </div>
</body>
<script src="../../lib/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../lib/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../../lib/plugins/iCheck/icheck.min.js"></script>
<script>
	var divAlerta = document.getElementById('alerta');
	function verifient(event,idMensaje)
	{
		var codigo = event.which || event.keyCode;
		var select = document.getElementById(idMensaje); //El <select>
		value1 = select.value;
		//alert(value1);
		if(codigo === 13 || codigo === 1)
		{
			$.post('ajax/vista_ajax.php'
				, {ajax_page: idMensaje, com: value1 }, function(data){
					//$('div.pdfcom').load(data);
					$('#resul').html(''); 
					$('#resul').html(data); 
					// //alert('entrooo '+idMensaje);
					 // alert($('#Contenedor').height()+$('#alerta').height());
					 var med = $('#Contenedor').height();
					 // console.log(med);
					 if( med <= 437.77778)
					 {
					 	console.log(med);
					    $('#Contenedor').height($('#Contenedor').height()+$('#alerta').height()+50);
					 }
				});
		}
	}
	function verifiuser(event,idMensaje,user_msj)
	{
		var codigo = event.which || event.keyCode;
		var select = document.getElementById(idMensaje); //El <select>
		var s_user_msj = document.getElementById(user_msj).value; 
		value1 = select.value;
		//alert(value1+' '+s_user_msj+' '+codigo);
		if(codigo === 13 || codigo === 1 || codigo === 9)
		{
			$.post('ajax/vista_ajax.php'
				, {ajax_page: 'USER', com: value1, user:  s_user_msj}, function(data){
					//$('div.pdfcom').load(data);
					$('#resul').html(''); 
					$('#resul').html(data); 
					//alert('entrooo '+idMensaje);
				});
		}
	}
	 
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
	$("#Entidad").keydown(function(e) {
		 var vae = $("#Entidad").val();
		 //tecla tab
		 var code = e.keyCode || e.which;
		 //eliminamos cookies
		 document.cookie = "nombre=; max-age=0";
		 //alert(code);
		if (code == '9') {
			var select = document.getElementById("Entidad"); //El <select>
			value1 = select.value;
			//alert(value1);
			$.post('ajax/vista_ajax.php'
				, {ajax_page: 'Entidad', com: value1 }, function(data){
					//$('div.pdfcom').load(data);
					$('#resul').html(data); 
					//alert('entrooo '+idMensaje);
				});
			
		}
	});
</script>
<!-- vinculando a libreria Jquery
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>-->
<!-- Libreria java scritp de bootstrap 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" 
integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>-->
</html>