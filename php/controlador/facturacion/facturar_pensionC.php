<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturar_pensionM.php");

$controlador = new facturar_pensionC();
if(isset($_GET['cliente']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->getClientes($query));
}

if(isset($_GET['catalogo']))
{
	$controlador->getCatalogoLineas();
}

if(isset($_GET['catalogoProducto']))
{
	$controlador->getCatalogoProductos();
}

if(isset($_GET['saldoFavor']))
{
	$controlador->getSaldoFavor();
}

if(isset($_GET['saldoPendiente']))
{
	$controlador->getSaldoPendiente();
}

class facturar_pensionC
{
	private $facturacion;


	public function __construct(){
        $this->facturacion = new facturar_pensionM();
    }

	public function getClientes($query){
		$datos = $this->facturacion->getClientes($query);
		$clientes = [];
		foreach ($datos as $key => $value) {
			$clientes[] = array('id'=>$value['Cliente'],'text'=>utf8_encode($value['Cliente']),'data'=>array('email'=> $value['Email'],'direccion' => utf8_encode($value['Direccion']), 'telefono' => utf8_encode($value['Telefono']), 'ci_ruc' => utf8_encode($value['CI_RUC']), 'codigo' => utf8_encode($value['Codigo']), 'cliente' => utf8_encode($value['Cliente']), 'grupo' => utf8_encode($value['Grupo'])));
			//$clientes[] = array('id'=>$value['Cliente'],'text' => $value['Cliente'],'data' => array('email'=> $value['Email'],'direccion' => $value['Direccion'], 'telefono' => $value['Telefono'], 'ci_ruc' => $value['CI_RUC'], 'codigo' => $value['Codigo'], 'cliente' => $value['Cliente']));
		}
		echo json_encode($clientes);
		exit();
	}

	public function getCatalogoLineas(){
		$emision = $_POST['fechaEmision'];
		$vencimiento = $_POST['fechaVencimiento'];
		$datos = $this->facturacion->getCatalogoLineas($emision,$vencimiento);
		$catalogo = [];
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$catalogo[] = array('id'=>$value['Codigo'],'text'=>utf8_encode($value['Concepto']));
		}
		echo json_encode($catalogo);
		exit();
	}

	public function getCatalogoProductos(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getCatalogoProductos($codigoCliente);
		$catalogo = [];
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$catalogo[] = array('mes'=> utf8_encode($value['Mes']),'codigo'=> utf8_encode($value['Codigo_Inv']),'periodo'=> utf8_encode($value['Periodos']),'producto'=> utf8_encode($value['Producto']),'valor'=> utf8_encode($value['Valor']), 'descuento'=> utf8_encode($value['Descuento']),'descuento2'=> utf8_encode($value['Descuento2']),'iva'=> utf8_encode($value['IVA']));
		}
		echo json_encode($catalogo);
		exit();
	}

	public function getCatalogoCuentas(){
		$datos = $this->facturacion->getCatalogoCuentas();
		$cuentas = [];
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$cuentas[] = array('codigo'=>$value['Codigo'],'nombre'=>utf8_encode($value['NomCuenta']));
		}
		return $cuentas;
	}

	public function getNotasCredito(){
		$datos = $this->facturacion->getNotasCredito();
		$cuentas = [];
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$cuentas[] = array('codigo'=>$value['Codigo'],'nombre'=>utf8_encode($value['NomCuenta']));
		}
		return $cuentas;
	}

	public function getSaldoFavor(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getSaldoFavor($codigoCliente);
		$catalogo = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC);
		echo json_encode($catalogo);
		exit();
	}

	public function getSaldoPendiente(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->facturacion->getSaldoPendiente($codigoCliente);
		$catalogo = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC);
		echo json_encode($catalogo);
		exit();
	}
}
?>