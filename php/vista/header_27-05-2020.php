<?php
      $imag = '<img src="../../img/logotipos/diskcover_web.gif" style="height:60px ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-left">';
      
if(isset($_GET['mod'])) 
		{
			//sesion para saber en que modulo esta 
			$_SESSION['INGRESO']['modulo']=$_GET['mod'];
			//verificamos accion 
			if(isset($_GET['acc'])) 
			{
				$_SESSION['INGRESO']['accion']=$_GET['acc'];
			}
			else
			{
				unset( $_SESSION['INGRESO']['accion']);
			}
			//verificacion titulo accion
			if(isset($_GET['acc1'])) 
			{
				$_SESSION['INGRESO']['accion1']=$_GET['acc1'];
			}
			else
			{
				unset( $_SESSION['INGRESO']['accion1']);
			}      
			$tipo_img = array('png','jpg','gif');
			foreach ($tipo_img as $key => $value) {
				if(file_exists( dirname(__DIR__,2). '/img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.'.$value))
				{
					$url='../../img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.'.$value;
          $imag = '<img src="'.$url.'" style="height:60px ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-left">';
					break;
				}else
				{
          $imag = '<img src="../../img/logotipos/diskcover_web.gif" style="height:36% ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-left">';
				}
			}			
						
}
if(isset($_GET['mos3']))
{
  $tipo_img = array('png','jpg','gif');
      foreach ($tipo_img as $key => $value) {
        if(file_exists( dirname(__DIR__,2). '/img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.'.$value))
        {
         
          $url='../../img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.'.$value;
          $imag = '<img src="'.$url.'" style="height:60px ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-left">';
          break;
        }else
        {
               $imag = '<img src="../../img/logotipos/diskcover_web.gif" style="height:60px ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-left">';
      
        }
      }

}
 if(isset($_SESSION['INGRESO']['Logo_Tipo']))
 {
  $tipo_img = array('png','jpg','gif');
      foreach ($tipo_img as $key => $value) {
        if(file_exists( dirname(__DIR__,2). '/img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.'.$value))
        {
         
          $url='../../img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.'.$value;
          $imag = '<img src="'.$url.'" style="height:60px ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-left">';
          break;
        }else
        {
               $imag = '<img src="../../img/logotipos/diskcover_web.gif" style="height:60px ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-left">';
      
        }
      }
 }
?>
<style type="text/css">
  .color
  {
    background: #3c8dbc;
  }
</style>
<script>
$(function(){
$(window).scroll(function(){
if ($(window).scrollTop() > 5)
{
$("#datos").fadeOut();
}
else
{
$("#datos").fadeIn();
}
});
});
</script> 

<header class="main-header">	
    <nav class="navbar navbar-fixed-top">
      <div class="container-fluid" id="datos">         
        <div class="row">
        <div class="logo"  style="background-color:#ffff">  
			<?php
				if(!isset($_SESSION['INGRESO']['noempr']))
				{
			?>
				 <div class="logo-mini">              
					<div class="col-sm-6">
					   <div class="item">
						   <a href="panel.php?sa=s">
							  <?php echo $imag; ?>
						   </a>             

						  <p class="message"  style="color: #394082">
							<b>DiskCover System <br>
							R.U.C. 0702164179001 <br></b>
							<b>Representante:</b> Walter Jail Vaca Prieto
						  </p>
					   </div>                           
					</div>
					<div class="col-sm-6">
					   <div class="item">            
						 <img src="../../img/logotipos/diskcover_web.gif" style="height:36% ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-right" data-toggle="modal" data-target="#myModal_info" title="Direccion: PABLO PALACIO N23-154 Y AV. LA GASCA>  
						   <p class="message"  style="color: #394082">
							 <b>Direccion:</b> PABLO PALACIO N23-154 Y AV. LA GASCA<br>
							 <b>Telefono:</b> 025008082 / FAX: 025008082<br>
							 <b>Email:</b> diskcover@msn.com
						   </p>
					  </div>        
					</div>
				 </div>
			 <?php
				}
				else
				{
			?>
				<div class="logo-mini">              
					<div class="col-sm-6">
					   <div class="item">
						   <a href="panel.php?sa=s">
							  <?php echo $imag; ?>
						   </a>             

						  <p class="message"  style="color: #394082">
							<b><?php echo $_SESSION['INGRESO']['Razon_Social']; ?> <br>
							R.U.C. <?php echo $_SESSION['INGRESO']['RUC']; ?> <br></b>
							<b>Representante:</b> <?php echo $_SESSION['INGRESO']['Gerente']; ?>
						  </p>
					   </div>                           
					</div>
					<div class="col-sm-6">
					   <div class="item">            
						 <img src="../../img/logotipos/diskcover_web.gif" style="height:36% ;width: 18%;margin: 5px" alt="user image" class="text-muted pull-right" data-toggle="modal" data-target="#myModal_info" title="Direccion: PABLO PALACIO N23-154 Y AV. LA GASCA
               Telefono: 025008082 / FAX: 025008082
               Email: diskcover@msn.com">  
						   <p class="message"  style="color: #394082">
							 <b>Direccion:</b> <?php echo $_SESSION['INGRESO']['Direccion']; ?><br>
							 <b>Telefono:</b> <?php echo $_SESSION['INGRESO']['Telefono1'].' / '.$_SESSION['INGRESO']['FAX']; ?><br>
							 <b>Email:</b> <?php echo $_SESSION['INGRESO']['Email']; 
               if(!empty($_SESSION['INGRESO']['Email_Conexion_CE']))
               {
                echo  ' / '.$_SESSION['INGRESO']['Email_Conexion_CE'];
               }
               ?> 
						   </p>
					  </div>        
					</div>
				 </div>
			<?php
				}
			?>
             <div class="logo-lg">
              <div class="text-center" style="color: #3c8dbc">
                 <?php echo $imag; ?>
                 <span class="glyphicon glyphicon-envelope" aria-hidden="true" style="margin-top:10px" title="Email: diskcover@msn.com"></span>
                 <span class="glyphicon glyphicon-map-marker" aria-hidden="true" title="Direccion: PABLO PALACIO N23-154 Y AV. LA GASCA"></span>
                 <span class="glyphicon glyphicon-earphone" aria-hidden="true" title="Telefono: 025008082 / FAX: 025008082"></span>     
                 <img src="../../img/logotipos/diskcover_web.gif"  style="height:36% ;width: 18%;margin: 5px" class="text-muted pull-right">    
              </div>
             </div>
        </div>  
      </div>
    </div>
      <div class="container-fluid">
        
        <div class="navbar-header">          
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse" style="margin-bottom: -10px">

          <?php 
              if(!isset($_GET['mod']))
                {
                  echo $html = '<div style="color: #ffff;"></div>';

               }else{
          ?>
         

          <div style="color: #ffff;"><?php echo $_SESSION['INGRESO']['item']; ?>-<?php echo $_SESSION['INGRESO']['noempr'];?> <i class="fa fa-fw fa-chevron-circle-right"></i>
            <?php echo $_SESSION['INGRESO']['modulo']; ?> <i class="fa fa-fw fa-chevron-circle-right"></i>
            <?php if(isset($_SESSION['INGRESO']['accion1']))
                   {
                     echo $_SESSION['INGRESO']['accion1'];
                   } 
            ?> 
            <i class="fa fa-fw fa-chevron-circle-right"></i>
          </div>

        <?php } ?>
          <ul class="nav navbar-nav">


        <!---------inicio de pestañas de empresa------------>
        <?php
     if(isset($_GET['mod'])){
      if( $_GET['mod'] =='empresa') 
      {
      ?>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="archivo" data-toggle="dropdown" aria-haspopup="true" 
                aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Archivo</a>
                <ul class="dropdown-menu" aria-labelledby="archivo">
                  <li class="dropdown-item dropdown" style='width:100%;height=100%'>
                    <a class="dropdown-toggle" id="del_sistema" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style='width:100%;'>
                    Del sistema 
                    <div style=' float: right;'><i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></div></a>
                    <ul class="dropdown-menu" aria-labelledby="del_sistema">
                      <li class="dropdown-item">
                        <a href="#">
                          <div style=' float: left;'>Account Settings</div> 
                          <div style=' float: right;'><span class="glyphicon glyphicon-cog pull-right"></span></div>
                        </a></li>
                      <li class="dropdown-item"><a href="empresa.php?mod=empresa&acc=cambioe&acc1=Modificar empresa&b=1">Modificar empresa</a></li>
                      <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=campe&acc1=Cambio de periodo&b=1">Cambio de periodo</a></li>
                    </ul>
                  </li>
                  <li class="dropdown-item"><a href="panel.php?sa=s">
                    Volver
                  </a></li>
                </ul>
            </li>
              
        <?php
      }}
      ?>


        <!--------fin de  pestañas de empresa---->
        <!--------inicio de  pestañas de contabilidad---->

      <?php
       if(isset($_GET['mod']))
       {
          if($_GET['mod']=='contabilidad') 
             {
       ?>

              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="archivo" data-toggle="dropdown" aria-haspopup="true" 
                aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Archivo</a>
                <ul class="dropdown-menu" aria-labelledby="archivo">
                  <!--<li class="dropdown-item dropdown">
                    <a class="dropdown-toggle" id="del_sistema" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Del sistema
                    <i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
                    <ul class="dropdown-menu" aria-labelledby="del_sistema">
                      <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=cambioc&acc1=Cambio de clave">Cambio de clave</a></li>
                      <li class="dropdown-item"><a href="#">Ingresar nuevo usuario</a></li>
                      <li class="dropdown-item"><a href="#">Modulo de auditoria</a></li>
                      <li class="dropdown-item"><a href="#">Autorizacion del SRI</a></li>
                      <li class="dropdown-item"><a href="#">Cierre del mes</a></li>
                      <li class="dropdown-item"><a href="#">Cierre del ejercicio</a></li>
                      <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=campe&acc1=Cambio de periodo&b=1">Cambio de periodo</a></li>
                    </ul>
                  </li>-->
                  <li class="dropdown-item dropdown">
                    <a class="dropdown-toggle" id="de_operacion" data-toggle="dropdown" aria-haspopup="true" 
                    aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>De operación
                    <i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
                    <ul class="dropdown-menu" aria-labelledby="de_operacion">
                      <!--<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=incc&acc1=Ingreso catalogo de Cuentas ">
                      Ingreso catalogo de Cuentas <i  style=' float: right;' align="right">(CRTL+F6)</i></a></li>
                      <li class="dropdown-item dropdown">
                        <a class="dropdown-toggle" id="catalogosub" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ingreso catalogo de Subcuentas
                        <i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
                        <ul class="dropdown-menu" aria-labelledby="catalogosub">
                          <li class="dropdown-item"><a href="#">Ctas. Por Cobrar/Ctas por Pagar</a></li>
                          <li class="dropdown-item"><a  href="#">Ctas. Ingreso/Egresos/Primas</a></li>
                        </ul>
                      </li>-->
                      <!--<li class="dropdown-item"><a href="#">Ingresar Clientes/Proveedores 
                      <i  style=' float: right;' align="right">(CRTL+F8)</i></a></li>-->
                      <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=incom&acc1=Ingresar Comprobantes&b=1&po=subcu">
                      Ingresar Comprobantes <i  style=' float: right;' align="right">(CRTL+F5)</i></a></li>
                      <!--<li class="dropdown-item"><a href="#">Ingresos/Egreso de caja chica 
                      <i  style=' float: right;' align="right">(CRTL+F7)</i></a></li>
                      <li class="dropdown-item"><a href="#">Modificación de primas</a></li>
                      <li class="dropdown-item"><a href="#">Conciliación bancaria de debitos/creditos</a></li>-->
                    </ul>
                  </li>
                  <!--<li class="dropdown-item dropdown">
                    <a class="dropdown-toggle" id="archivo_e" data-toggle="dropdown" aria-haspopup="true" 
                    aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Archivos de excel
                    <i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
                    <ul class="dropdown-menu" aria-labelledby="archivo_e">
                      <li class="dropdown-item"><a href="#">Importar Compras de excel (CRTL+I)</a></li>
                      <li class="dropdown-item"><a href="#">Importar Ventas de excel (CRTL+J)</a></li>
                      <li class="dropdown-item"><a href="#">Importar Clientes/Proveedores</a></li>
                      <li class="dropdown-item"><a href="#">Importar Submodulos</a></li>
                      <li class="dropdown-item"><a href="#">Importar Contabilidad externa</a></li>
                      
                    </ul>
                  </li>-->
                  <!--<li class="dropdown-item"><a href="#">Pagos programados bancos
                    <i style=' float: right;' align="right">&nbsp;</i> </a></li>-->
                  <li class="dropdown-item"><a href="panel.php">Volver</a></li>
                  <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" >Enlace 2</a>
                     <ul class="dropdown-menu">
                       <a href="#" class="dropdown-item">Subenlace 1</a>
                       <a href="#" class="dropdown-item">Subenlace 2</a>
                       <a href="#" class="dropdown-item">Subenlace 3</a>
                    </ul>
     
                 </li>
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="reporte" data-toggle="dropdown" aria-haspopup="true" 
                aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Reportes</a>
                <ul class="dropdown-menu" aria-labelledby="reporte">
                  <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=catalogo_cuentas&acc1=catalogo cuentas&b=1">Catalogo de cuentas</a></li>
                  <!--<li class="dropdown-item"><a href="#" >Catalogo de SubCtas bloque</a></li>
                  <li class="dropdown-item"><a href="#" >Cuotas Pendientes de prestamos</a></li>
                  <li class="dropdown-item"><a href="#" >Catalogo de rol de pagos</a></li>
                  <li class="dropdown-item"><a href="#" >Catalogo de retención</a></li>-->
                  <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=diario_general&acc1=diario_general&b=1" >Diario general 
                  <i  style=' float: right;' align="right">(CRTL+D)</i></a></li>
                  <!--<li class="dropdown-item"><a href="#" >Libro banco 
                  <i  style=' float: right;' align="right">(CRTL+F)</i></a></li>
                  <li class="dropdown-item"><a href="#" >Mayores auxiliares 
                  <i  style=' float: right;' align="right">(CRTL+M)</i></a></li>
                  <li class="dropdown-item"><a href="#" >Mayores auxiliares por concepto</a></li>
                  <li class="dropdown-item"><a href="#" >Mayores de SubCtas 
                  <i  style=' float: right;' align="right">(CRTL+S)</i></a></li>-->
                  <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=compro&acc1=Comprobantes Procesados&b=1" >
                  Comprobantes procesados <i  style=' float: right;' align="right">(CRTL+L)</i></a></li>
                  <!--<li class="dropdown-item"><a href="#" >Comprobantes de retención (CRTL+R)</a></li>
                  <li class="dropdown-item"><a href="#" >Cheques procesados</a></li>
                  <li class="dropdown-item"><a href="#" >Conciliación bancaria</a></li>
                  <li class="dropdown-item"><a href="#" >Imprimir lista de comprobantes</a></li>
                     
                  <li class="dropdown-item"><a href="#" >Saldo de Caja/Bancos/Especiales</a></li>
                  -->
                  <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=saldo_fac_submodulo&acc1=Saldo de factura submodulo&b=1" >Saldo de facturas en SubModulos</a></li>
                  
                  <!--
                  <li class="dropdown-item"><a href="#" >Flujo de caja chica</a></li>
                  <li class="dropdown-item"><a href="#" >Reportes de SubCtas de costos</a></li>
                  <li class="dropdown-item dropdown">
                    <a class="dropdown-toggle" id="rsubcostos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reportes de SubCtas de costos
                    <i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
                    <ul class="dropdown-menu" aria-labelledby="rsubcostos">
                      <li class="dropdown-item"><a  href="#">Ingresar facturas de costos</a></li>
                      <li class="dropdown-item"><a  href="#">reporte de costos</a></li>
                    </ul>
                  </li>
                  <li class="dropdown-item"><a href="#" >Informa a UAF (Cooperativas)</a></li>
                  <li class="dropdown-item"><a href="#" >Buscar datos</a></li>-->
                  
                </ul>
              </li>
              <!--<li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="anexos" data-toggle="dropdown" aria-haspopup="true" 
                aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Anexos transaccionales</a>
                <ul class="dropdown-menu" aria-labelledby="anexos">
                  <li class="dropdown-item"><a href="#" >Generar anexos transaccionales (F12)</a></li>
                  <li class="dropdown-item"><a href="#" >Codigo de retención (AIR) 
                  <i  style=' float: right;' align="right">(F11)</i></a></li>
                  <li class="dropdown-item"><a href="#" >Resumen de retensiones 
                  <i  style=' float: right;' align="right">(F9)</i></a></li>
                  <li class="dropdown-item"><a href="#" >Correción de formularios</a></li>
                  <li class="dropdown-item dropdown">
                    <a class="dropdown-toggle" id="coform" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Correción de formularios
                    <i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
                    <ul class="dropdown-menu" aria-labelledby="coform">
                      <li class="dropdown-item"><a  href="#">Compras 
                      <i  style=' float: right;' align="right">(MAYUS+F1)</i></a></li>
                      <li class="dropdown-item"><a  href="#">Ventas 
                      <i  style=' float: right;' align="right">(MAYUS+F2)</i></a></li>
                      <li class="dropdown-item"><a  href="#">Exportaciones (MAYUS+F3)</a></li>
                      <li class="dropdown-item"><a  href="#">Importaciones (MAYUS+F4)</a></li>
                    </ul>
                  </li>
                  <li class="dropdown-item"><a href="#" >Relación por dependiencia 
                  <i  style=' float: right;' align="right">(F7)</i></a></li>
                  <li class="dropdown-item"><a href="#" >Anulados 
                  <i  style=' float: right;' align="right">(F6)</i></a></li>
                </ul>
              </li>-->
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="efinan" data-toggle="dropdown" aria-haspopup="true" 
                aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Estados financieros</a>
                <ul class="dropdown-menu" aria-labelledby="efinan">
                  <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=macom&acc1=Mayorización&b=1" >Mayorizar Comprobantes procesados 
                  <i  style=' float: right;' align="right">(CRTL+T)</i></a></li>
                  <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&b=1" >
                  Balance de Comprobacion/Situación/General (CRTL+G)</a></li>
                  <!--<li class="dropdown-item"><a href="#" >Resumen analitico de Utilidad/Perdida 
                  <i  style=' float: right;' align="right">(CRTL+U)</i></a></li>
                  <li class="dropdown-item"><a href="#" >Balance de SubModulos</a></li>-->
                  
                  
                </ul>
              </li>
              <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="herra" data-toggle="dropdown" aria-haspopup="true" 
                aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Herramientas</a>
                <ul class="dropdown-menu" aria-labelledby="herra">
                  <!--<li class="dropdown-item"><a href="#" >Calculadora 
                  <i  style=' float: right;' align="right">(CRTL+F1)</i></a></li>
                  <li class="dropdown-item"><a href="#" >Programador 
                  <i  style=' float: right;' align="right">(F11)</i></a></li>
                  <li class="dropdown-item"><a href="#" >Memorando 
                  <i  style=' float: right;' align="right">(CRTL+E)</i></a></li>
                  <li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=hco&acc1=Conexión Oracle&b=1" >Conexión Oracle</a></li>
                  -->
                  <li class="dropdown-item"><a target="_blank" href="https://www.diskcoversystem.com/" >diskcoversystem</a></li>
                  <li class="dropdown-item"><a target="_blank" href="imprimir_factura.php?pre=F">factura prueba</a></li>
                  <li class="dropdown-item"><a target="_blank" href="imprimir_factura.php?pre=PF">Prefactura prueba</a></li>
                  <!--<li class="dropdown-item"><a href="#" >Enviar correo electrónico</a></li>-->
                </ul>
              </li>
              
              <!--<li class="nav-item" > 
                <a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Motor de base de datos">
                  <p >
                      <span class="hidden-xs"><?php 
                    //var_dump($_SESSION['INGRESO']);
                    //echo $_SESSION['INGRESO']['Nombre'];
                  ?></span>
                  </p>
                </a>
                  
              </li>-->
              
           
      
 
<?php
      }}
?>

        <!--------fin de pestañas de contabilidad---->
        <!--------inicio de pestañas de educativo---->
         
      <?php
          if(isset($_GET['mod']))
          {
             if($_GET['mod']=='educativo' ) 
               {         

      ?>       
            <!--<li class="nav-item <!-- active "> -->
              <!--<a class="nav-link" href="#">Archivo <span class="sr-only">(current)</span></a>
            </li>-->
            <!-- archivo -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" id="archivo" data-toggle="dropdown" aria-haspopup="true" 
              aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Archivo</a>
              <ul class="dropdown-menu" aria-labelledby="archivo">
                <li class="dropdown-item dropdown">
                  <a class="dropdown-toggle" id="de_operacion" data-toggle="dropdown" aria-haspopup="true" 
                  aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>De operación
                  <i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
                  <ul class="dropdown-menu" aria-labelledby="de_operacion">
                    <li class="dropdown-item"><a href="educativo.php?mod=educativo&acc=detalle_estudiante&acc1=Detalle Estudiante&b=1&po=subcu">
                    Detalle estudiante <i  style=' float: right;' align="right"></i></a></li>
                  </ul>
                </li>
                
                <li class="dropdown-item"><a href="panel.php">Volver</a></li>
              </ul>
            </li>    
      <?php
       }}
      ?>
<!--------inicio de pestañas de educativo---->
     


          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown"   style='padding-top: 5px;padding-bottom: 5px;'>
              <?php echo $_SESSION['INGRESO']['Nombre']; ?>
            </a>
            <ul class="dropdown-menu">
              <li class="user-header">
                <img src="../../img/logotipos/diskcover_web.gif" class="img-circle" alt="User Image">
                <p>
                  Alexander Pierce - Web Developer
                  <small>Member since Nov. 2012</small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="#" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="#" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <div class="btn-group">
         	<a href="logout.php" class="btn btn-sm btn-primary" title="Salir"><img src="../../img/png/salirs.png"  style="height: 55%; width:55%"></a>
   			  <a href="panel.php?mos2=e" class="btn btn-sm btn-primary" title="Salir de Empresa"><img src="../../img/png/salire.png"  style="height: 55%; width:55%"></a>          	
          </div>
          </ul>

        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
