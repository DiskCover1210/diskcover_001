<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturar_pensionM.php");
require_once(dirname(__DIR__,2)."/vista/appr/modelo/modelomesa.php");

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

if(isset($_GET['guardarPension']))
{
	$controlador->guardarFacturaPension();
}

class facturar_pensionC
{
	private $facturacion;


	public function __construct(){
        $this->facturacion = new facturar_pensionM();
        $this->modelo = new MesaModel();
    }

	public function getClientes($query){
		$datos = $this->facturacion->getClientes($query);
		$clientes = [];
		foreach ($datos as $key => $value) {
			$clientes[] = array('id'=>$value['Cliente'],'text'=>utf8_encode($value['Cliente']),'data'=>array('email'=> $value['Email'],'direccion' => utf8_encode($value['Direccion']), 'telefono' => utf8_encode($value['Telefono']), 'ci_ruc' => utf8_encode($value['CI_RUC']), 'codigo' => utf8_encode($value['Codigo']), 'cliente' => utf8_encode($value['Cliente']), 'grupo' => utf8_encode($value['Grupo']), 'tdCliente' => utf8_encode($value['TD'])));
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
			$cuentas[] = array('codigo'=>$value['Codigo'],'nombre'=>utf8_encode($value['Codigo'])." - ".utf8_encode($value['NomCuenta']));
		}
		return $cuentas;
	}

	public function getNotasCredito(){
		$datos = $this->facturacion->getNotasCredito();
		$cuentas = [];
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$cuentas[] = array('codigo'=>$value['Codigo'],'nombre'=>utf8_encode($value['Codigo'])." - ".utf8_encode($value['NomCuenta']));
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

	public function guardarFacturaPension(){
		$TextRepresentante = $_POST['TextRepresentante'];
		$TxtDireccion = $_POST['TxtDireccion'];
		$TxtTelefono = $_POST['TxtTelefono'];
		$TextFacturaNo = $_POST['TextFacturaNo'];
		$TxtGrupo = $_POST['TxtGrupo'];
  		$TextCI = $_POST['TextCI'];
  		$TD_Rep = $_POST['TD_Rep'];
  		$TxtEmail = $_POST['TxtEmail'];
  		$TxtDirS = $_POST['TxtDirS'];
		$codigoCliente = $_POST['codigoCliente'];
		$update = $_POST['update'];
		$CtaPagoMax = "";
		$ValPagoMax = "";
  		TextoValido($TextRepresentante,"" , true);
  		TextoValido($TxtDireccion, "" , True);
  		TextoValido($TxtTelefono, "" , True);
  		TextoValido($TxtEmail);
  		//cuentas
  		$TextCheque = $_POST['TextCheque'];
  		$DCBanco = $_POST['DCBanco'];
  		$TxtEfectivo = $_POST['TxtEfectivo'];
  		$TxtNC = $_POST['TxtNC'];
  		$DCNC = $_POST['DCNC'];
  		
        $Titulo = "Formulario de Grabacion";
        $Mensajes = "Esta Seguro que desea grabar: La Factura No. ".$TextFacturaNo;
     	$ValPagoMax = 0;
     	$CtaPagoMax = "1";
     	if ($ValPagoMax <= intval($TextCheque)) {
     		$ValPagoMax = intval($TextCheque);
        	$CtaPagoMax = SinEspaciosIzq($DCBanco);
     	}
     	if ($ValPagoMax <= intval($TxtEfectivo)) {
	        $ValPagoMax = intval($TxtEfectivo);
	        $CtaPagoMax = $Cta_CajaG;
     	}
     	if ($ValPagoMax <= intval($TxtNC)) {
     		$ValPagoMax = intval($TxtNC);
        	$CtaPagoMax = SinEspaciosIzq($DCNC);
     	}
     	$Cta_Aux = Leer_Cta_Catalogo($CtaPagoMax);
     	print_r($Cta_Aux);
     	//$Tipo_Pago = $Cta_Aux['TipoPago'];

     	if ($update) {
     		$updateCliF = $this->facturacion->updateClientesFacturacion($TxtGrupo,$codigoCliente);
     		$updateCliM = $this->facturacion->updateClientesMatriculas($TextRepresentante,$TextCI,$TD_Rep,$TxtTelefono,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente);
     		$updateCli = $this->facturacion->updateClientes($TxtTelefono,$TxtDirS,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente);
     		echo "Cliente fac".$updateCliF."<br>";
     		echo "Cliente m".$updateCliM."<br>";
     		echo "Cliente ".$updateCli."<br>";
     	}
     	//Grabar_FA_Pensiones
     	$TextFacturaNo = ReadSetDataNum("FA_SERIE_001001", True, False);
	}
}
?>