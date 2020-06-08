<?php  
require_once("panel.php");
$prefactura = $_GET['pre'];

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="content">
		<div class="container-fluid">
			<br>
			<input type="button" name="" value="Imprimir factura" class="btn-btn-primary">
			<br>
			<?php
			if($prefactura == 'F')
			{
				echo "<iframe style='width:100%; height:50vw;'' src='../controlador/imprimir_factura.php?tipo=F' frameborder='0' allowfullscreen></iframe>";
			}else
			{
				echo "<iframe style='width:100%; height:50vw;'' src='../controlador/imprimir_factura.php?tipo=PF' frameborder='0' allowfullscreen></iframe>";
			}

			?>			
		</div>

</body>
</html>