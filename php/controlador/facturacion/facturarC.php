<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturarM.php");
//require_once(dirname(__DIR__,2)."/vista/appr/modelo/modelomesa.php");

$controlador = new facturarC();
if(isset($_GET['lineas_factura']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_facturas($parametros));
}


class facturarC
{
	private $modelo;

	public function __construct(){
        $this->modelo = new facturarM();	
    }

    function lineas_facturas($parametro)
    {
    	$codigoCliente = $parametro['codigoCliente'];
    	$datos = $this->modelo->lineas_factura($codigoCliente);
    	return array('tbl'=>$datos['tbl']);
    }        
}
?>