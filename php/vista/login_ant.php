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
	  <link rel="stylesheet" href="../../lib/dist/css/sweetalert2.min.css">
	  
	
	<link href="https://cdn.jsdelivr.net/npm/simple-line-icons@2.4.1/css/simple-line-icons.css" rel="stylesheet" type="text/css" />
	
</head>
<body >
	
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
						//   echo $men;
						  echo "<script> 
					
					Swal.fire({
					  type: 'error',
					  title: '".$men."',
					  text: ''
					});
					
					</script>";
						}
					  
					?>
				</div>
				<!--<div id="alerta" class="alert invisible"></div>-->
                <div class="input-group input-group-lg" >
                  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-home"></i></span>
                  <!-- 1792164710001 -->
				 
				  <input type="text" class="form-control" name="entidad" placeholder="Entidad a la que perteneces" id="Entidad" onblur="entidad_b()" required="">
				  <input type="hidden" name="" id="res">
                </div>
                <br>
                <div class="input-group input-group-lg" >
                  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-envelope"></i></span>
                <!--  <input type="email" class="form-control" name="correo" placeholder="Correo Electrónico/Usuario" id="Correo" aria-describedby="sizing-addon1" required> -->
                  <input type="text" class="form-control" name="correo" placeholder="Correo Electrónico/Usuario" id="Correo" onblur="verifiuser_('','Correo')" required="">                    
                </div>                
                <br>
                <div class="input-group input-group-lg" >
                  <span class="input-group-addon" id="sizing-addon1"><i class="glyphicon glyphicon-lock"></i></span>
                  <input type="password" name="contra" class="form-control" placeholder="******" aria-describedby="sizing-addon1" required>
                </div>
                <br>
				<input type="submit" name="submitlog" id='enviar' value="Entrar" class="btn btn-lg btn-primary btn-block btn-signin" id="IngresoLog" />
                <div class="opcioncontra"><a href="">Olvidaste tu contraseña?</a></div>
				<!-- <a class="btn btn-lg btn-primary btn-block btn-signin" href="descarga_a.php" style='colo:ffffff;'>
					Descargas Importantes
				</a> -->
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

<script type="text/javascript">
	function entidad_b()
	{
		 var vae = $("#Entidad").val();
		  if(vae!='')
		  {
		 document.cookie = "nombre=; max-age=0";
		
			// var select = document.getElementById("Entidad"); //El <select>
			// value1 = select.value;
			//alert(value1);
			 $.ajax({
             data:  {ajax_page:'ajax_page',ajax_page:'Entidad',com: vae},
             url:   'ajax/vista_ajax.php?ajax_page=Entidad',
             type:  'post',
             dataType: 'json',
               success:  function (response) { 
               console.log(response);
               if(response.resp == 1)
               {
               	 $('#resul').html('<div id="alerta" class="alert alert-success visible" align="center">'+response.enti+'</div>');
               	 $('#res').val(response);
               }else if(response.resp == -1)
               {
               	 $('#resul').html('');
               	 $('#res').val(response);
               	 Swal.fire({
				  type: 'error',
				  title: 'No se pudo realizar sesion',
				  text: 'La entidad que ingresaste no tiene el formato correcto.(3)'
				});
               	}else if(response.resp== 2)
               	{
               		 $('#res').val(response);
               		Swal.fire({
					  type: 'error',
					  title: 'No se pudo realizar sesion(1)',
					  text: 'Entidad no encontrada'
					});

               	}else
               	{
               		 $('#res').val(response);
               		 Swal.fire({
				  type: 'error',
				  title: 'No se pudo realizar sesion, verifique conexion (2)',
				  text: 'Error de conexion.'
				});
               	}
             }
           });	
		}

	}


	function verifiuser_(idMensaje,user_msj)
	{
		if($('#Entidad').val()!='')
		{
		// var select = document.getElementById(idMensaje); //El <select>
		var s_user_msj = document.getElementById(user_msj).value; 
		// value1 = select.value;
		//alert(value1+' '+s_user_msj+' '+codigo);
		
			$.ajax({
				data:  {ajax_page:'ajax_page',ajax_page: 'USER', user:  s_user_msj},
				url:   'ajax/vista_ajax.php?ajax_page=User',
				type:  'post',
				success:  function (response) 
				{
					console.log(response);
					//$('div.pdfcom').load(data);
					$('#resul').html(''); 
					$('#resul').html(response); 
					$("#enviar").show();
					//alert('entrooo '+idMensaje);
				}
			});
		}
		
	}
	 

</script>
<script type="text/javascript">
	$(document).ready(function()
  {
  	var acc = '<?php if(isset($_GET['accs'])){echo $_GET['accs'];}else{echo '1';}?>';
  	console.log(acc);
  	if(acc == 0)
  	{
  		Swal.fire({
  			title: 'Usuario Bloqueado o sin permisos?',
  			text:'Consulte con su administrador',
  			type: 'warning',
  			confirmButtonColor: '#3085d6',
  			confirmButtonText: 'Ok!'
  		}).then((result) => {
  			if (result.value) {
  				  window.location.href='login.php';
  			 }
  		})
  		// window.location.href='login.php';
  	}
  
  });
</script>
</html>