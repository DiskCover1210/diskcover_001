<?php 
include('controladormesa.php');
$controlador = new MesaCon();
?>
<!DOCTYPE html>
<html>
    <head>
    	<script type="text/javascript">
    		window.print();
    	</script>
        <!-- <link rel="stylesheet" href="style.css"> <script src="script.js"></script> -->
    </head>
    <body>

    	<div style="width: 100%;height: 100%;display:flex;align-items: center;justify-content: center;background: #6f6b6b;">
        <div style="width: 350px;padding: 10px;background: #ffffff;;margin-top: 15px;margin-bottom: 15px">
        	<?php
        	@session_start(); 

        	// print_r($_SESSION);
        	$src = '';
              if(isset($_SESSION['INGRESO']['Logo_Tipo']))
		         {
		   	      $logo=$_SESSION['INGRESO']['Logo_Tipo'];
		   	      //si es jpg
		   	      $src ='../../../../img/logotipos/'.$logo.'.jpg'; 
		   	      if(!file_exists($src))
		   	      {
		   		      $src ='../../../../img/logotipos/'.$logo.'.gif'; 
		   		      if(!file_exists($src))
		   		      {
		   			      $src ='../../../../img/logotipos/'.$logo.'.png'; 
		   			      if(!file_exists($src))
		   			      {
		   				      $logo="diskcover_web";
		                      $src='../../../../img/logotipos/'.$logo.'.gif';

		   			      }

		   		      }

		   	      }
		        }

		        $src = "../../../../img/modulos/doc.png";
		 
        	?><pre>
                    <b>RUC</b>
                <?php echo $_SESSION['INGRESO']['RUC'];?>
                            
                   <b>Telefono</b>
             <?php echo $_SESSION['INGRESO']['Telefono1'];?>
      			
 	<b><?php echo $_SESSION['INGRESO']['Razon_Social'];?></b>
 
<b>Direccion Mat.:<br></b><?php echo $_SESSION['INGRESO']['Direccion'];?>
        			
<b>Obligado a llevar Contabilidad:</b><?php echo $_SESSION['INGRESO']['Obligado_Conta'];?>
<hr>       			
 </pre>
        	<?php
            if (isset($_GET['ticket'])) {
                $ci = $_GET['CI'];
                $serie = $_GET['serie'];
                $fac = $_GET['fac'];
                $parametros = array('tipo'=>'FA','ci'=>$ci,'serie'=>$serie,'factura'=>$fac);
                echo $controlador->datos_fac_ticket($parametros);
            }
        	   if(isset($_GET['mesa']))
        	   {
        	   	 $mesa = $_GET['mesa'];
        	   	 if(isset($_GET['tipo']) && $_GET['tipo']=='PF')
        	   	 {
        	   	 	$parametros = array('tipo'=>'PF','mesa'=>$mesa);
        	   	 	echo $controlador->datos_fac_pre($parametros);
        	   	 }else
        	   	 {
        	   	 	$ci = $_GET['CI'];
        	   	 	$serie = $_GET['serie'];
        	   	 	$fac = $_GET['fac'];
        	   	 	$parametros = array('tipo'=>'FA','mesa'=>$mesa,'ci'=>$ci,'serie'=>$serie,'factura'=>$fac);
        	   	 	echo $controlador->datos_fac_pre($parametros);
        	   	 }
        	     
        	   }
        	?>
        </div>
    </div>
    </body>
</html>