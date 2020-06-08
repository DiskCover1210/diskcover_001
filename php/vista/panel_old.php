<?php
/**
 * Autor: Rodrigo Chambi Q.
 * Mail:  filvovmax@gmail.com
 * web:   www.gitmedio.com
 */
//require_once 'determ.php';
include("chequear_seguridad.php"); 
require_once("../controlador/panel.php");
	//echo ' jjj '.$_SESSION['autentificado'];
	//die();
	//asignamos la empresa
	if(isset($_GET['mos'])) 
	{
		$_SESSION['INGRESO']['empresa']=$_GET['mos'];
		$_SESSION['INGRESO']['noempr']=$_GET['mos1'];
	}
	if(isset($_GET['mos2'])=='e') 
	{
		//destruimos la sesion
		unset( $_SESSION['INGRESO']['empresa'] ); 
		unset( $_SESSION['INGRESO']['noempr'] );  	
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
  <link rel="stylesheet" href="../../lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../lib/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../lib/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../lib/dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../lib/dist/css/skins/_all-skins.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<!-- Site wrapper -->
<div class="wrapper">

  <header class="main-header">
    <!-- Logo -->
    <a href="panel.php" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>D</b>C</span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Disk</b>Cover</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>

      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          
          <!-- Notifications: style can be found in dropdown.less -->
          
          <!-- Tasks: style can be found in dropdown.less -->
          
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="../../img/jpg/sinimagen.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?php	
					//var_dump($_SESSION['INGRESO']);
					echo $_SESSION['INGRESO']['Nombre'];
				?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="../../img/jpg/sinimagen.jpg" class="img-circle" alt="User Image">

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
                <!--<div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>-->
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
    </nav>
  </header>

  <!-- =============================================== -->

  <!-- Left side column. contains the sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="../../img/jpg/sinimagen.jpg" class="img-circle" alt="User Image">
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
      <ul class="sidebar-menu" data-widget="tree">
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
                </li>
              </ul>
            </li>
            
          </ul>
        </li>
       
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- =============================================== -->

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
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
     <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> prueba</a></li>
        <li><a href="#">prueba</a></li>
        <li class="active">prueba</li>
      </ol>-->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box">
        <div class="box-header with-border">
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
        </div>
        <div class="box-body">
			
			 <?php
			  if (!isset($_SESSION['INGRESO']['empresa'])) 
			  {
				  $empresa=getEmpresas($_SESSION['INGRESO']['IDEntidad']);
				  $i=0;
				  ?>
				  <div class="row">
				    <div class="col-lg-3 col-xs-9">
						
							<label>Disabled Result</label>
							<select class="form-control select2" style="width: 100%;">
							  <option selected="selected">Alabama</option>
							  <option>Alaska</option>
							  <option disabled="disabled">California (disabled)</option>
							  <option>Delaware</option>
							  <option>Tennessee</option>
							  <option>Texas</option>
							  <option>Washington</option>
							</select>
					</div>
				   <?php
				  foreach ($empresa as &$valor) 
				  {
					
					?>
					
						
							<div class="col-lg-3 col-xs-6">
							  <!-- small box -->
							  <?php
							  if ($i%2==0)
							  {
							  ?>
								  <div class="small-box bg-green">
							   <?php
							  }
							  else
							  {
							  ?>
								  <div class="small-box bg-aqua">
							   <?php
							  }
							  ?>
							  
								<div class="inner">
								  <h3>&nbsp;</h3>

								  <p><?php echo $valor['Empresa']; ?></p>
								</div>
								<div class="icon">
								  <i class="fa fa-industry"></i>
								</div>
								<a href="panel.php?mos=<?php echo $valor['ID']; ?>&mos1=<?php echo $valor['Empresa']; ?>" class="small-box-footer">
								  Ingresar <i class="fa fa-arrow-circle-right"></i>
								</a>
							  </div>
							</div>
						
					 <?php
					 $i++;
				  }
			  ?>
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
						<h3 class="box-title">Modulos </h3>
					<?php
						require_once("footer.php");
					}
			  }
			  
			  ?>
			
      
