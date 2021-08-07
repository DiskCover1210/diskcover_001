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
    </head>
    <body>
    	<div style="width: 100%;height: 100%;display:flex;align-items: center;justify-content: center;background: #6f6b6b;">
        <div style="width: 350px;padding: 10px;background: #ffffff;;margin-top: 15px;margin-bottom: 15px">
        <br>
        	<?php
            if (isset($_GET['ticket'])) {
        	   @session_start();
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