<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturar_pensionM.php");
//require_once(dirname(__DIR__,2)."/vista/appr/modelo/modelomesa.php");

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
        //$this->modelo = new MesaModel();
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
     	if ($Cta_Aux) {
     		$Tipo_Pago = $Cta_Aux['TipoPago'];
     	}

     	if ($update) {
     		$updateCliF = $this->facturacion->updateClientesFacturacion($TxtGrupo,$codigoCliente);
     		$updateCliM = $this->facturacion->updateClientesMatriculas($TextRepresentante,$TextCI,$TD_Rep,$TxtTelefono,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente);
     		$updateCli = $this->facturacion->updateClientes($TxtTelefono,$TxtDirS,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente);
     	}
     	$this->Grabar_FA_Pensiones($_POST);
      $TC = SinEspaciosIzq($_POST['DCLinea']);
      $serie = SinEspaciosDer($_POST['DCLinea']);
      //traer secuencial de catalogo lineas
     	$TextFacturaNo = ReadSetDataNum($TC."_SERIE_".$serie, True, False);
      print_r($TextFacturaNo);
	}


	public function Grabar_FA_Pensiones($FA){
		$codigoCliente = $FA['codigoCliente'];
		//Seteamos los encabezados para las facturas
		$Estudiante['cedula'] = $FA['TextCI'];
		$Estudiante['fonopaga'] = $FA['TxtTelefono'];
  	$Estudiante['pagador'] = $FA['TextRepresentante'];
		$Estudiante['direcpaga'] = $FA['TxtDireccion'];
		//Procedemos a grabar la factura
  	$datos = $this->facturacion->getAsiento($codigoCliente);
		$asiento = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC);
    if ($asiento) {
		  Calculos_Totales_Factura($FA);
      $FA['Tipo_PRN'] = "FM";
      $FA['Nuevo_Doc'] = true;
      $FA['Factura'] = intval($TextFacturaNo);
      if (Existe_Factura($FA)) {
        
      }

      $SaldoPendiente = 0;
      $DiarioCaja = ReadSetDataNum("Recibo_No", True, True);
      if ($FA['Nuevo_Doc']) {
        $FA['Factura'] = ReadSetDataNum($FA['TC']."_SERIE_".$FA['Serie'], True, True);
      }
      $TextoFormaPago = "CONTADO";
      $Total_Abonos = $TotalCajaMN + $Total_Bancos + $SubTotal_NC;
      $FA['T'] = G_PENDIENTE;
      $FA['Saldo_MN'] = $FA['Total_MN'] - $Total_Abonos;
      $FA['Porc_IVA'] = $Porc_IVA;
      $FA['Cliente'] = $NombreCliente;
      $TA['Recibi_de'] = $FA['Cliente'];
      $Cta = SinEspaciosIzq($DCBanco);
      $Cta1 = SinEspaciosIzq($DCNC);
      $Valor = $datos["TOTAL"];
      $Codigo = $datos["Codigo_Cliente"];
      $Codigo1 = $datos["CODIGO"];
      $Codigo2 = $datos["Mes"];
      $Codigo3 = $datos["HABIT"];
      $Anio1 = $datos["TICKET"];
      $sSQL = "UPDATE Clientes_Facturacion 
              SET Valor = Valor - ".$Valor." 
              WHERE Item = '".$NumEmpresa."' 
              AND Periodo = '".$Anio1."' 
              AND Codigo_Inv = '".$Codigo1."' 
              AND Codigo = '".$Codigo."' 
              AND Credito_No = '".$Codigo3."' 
              AND Mes = '".$Codigo2."' ";
      //Grabamos el numero de factura
      Grabar_Factura($FA);

      //Seteos de Abonos Generales para todos los tipos de abonos
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['CodigoC'];
      $TA['Factura'] = $FA['Factura'];
      $TA['Fecha'] = $FA['Fecha'];
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
     
      //Abono de Factura Banco o Tarjetas
      $TA['Cta'] = $Cta;
      $TA['Banco'] = strtoupper($Grupo_No)." - ".$TextBanco;
      $TA['Cheque'] = $TextCheqNo;
      $TA['Abono'] = $Total_Bancos;
      Grabar_Abonos($TA);

      //Abono de Factura
      $TA['Cta'] = $Cta_CajaG;
      $TA['Banco'] = "EFECTIVO MN";
      $TA['Cheque'] = strtoupper($Grupo_No);
      $TA['Abono'] = $TotalCajaMN;
      Grabar_Abonos($TA);

      //Forma del Abono SubTotal NC
      if ($SubTotal_NC > 0) {
        $SubTotal_NC = $SubTotal_NC - $SubTotal_IVA;
        $TA['Cta'] = $Cta1;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "VENTAS";
        $TA['Abono'] = $SubTotal_NC;
        Grabar_Abonos($TA);
      }
     
      //Forma del Abono IVA NC
      if ($SubTotal_IVA > 0) {
        $TA['Cta'] = $Cta_IVA;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "I.V.A.";
        $TA['Abono'] = $SubTotal_IVA;
        Grabar_Abonos($TA);
      }
     
      //Abono de Factura
      $TA['T'] = G_NORMAL;
      $TA['TP'] = "TJ";
      $TACta = $Cta;
      $TA['Cta_CxP'] = $Cta_Tarjetas;
      $TA['Banco'] = "INTERES POR TARJETA";
      $TA['Cheque'] = $TextCheqNo;
      $TA['Abono'] = intval($TextInteres);
      $TA['Recibi_de'] = $FA['Cliente'];
      Grabar_Abonos($TA);
       
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Factura'] = $FA['Factura'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['CodigoC'];

      $TxtEfectivo['Text'] = "0.00";
      if (strlen($FA['Autorizacion']) >= 13) {
        if (!$No_Autorizar) {
          SRI_Crear_Clave_Acceso_Facturas($FA, False, True);
        }
        $FA['Desde'] = $FA['Factura'];
        $FA['Hasta'] = $FA['Factura'];
        Imprimir_Facturas_CxC(FacturasPension, FA, True, False, True, True);
        SRI_Generar_PDF_FA(FA, True);
      }

      $TA['Autorizacion'] = $FA['Autorizacion'];
      Actualiza_Estado_Factura($TA);
      Facturas_Impresas($FA);
       
      $sql = "SELECT * 
              FROM Asiento_F 
              WHERE Item = '".NumEmpresa."' 
              AND CodigoU = '".$codigoCliente."' ";
      $TextInteres = "0.00";
      $TextCheque = "0.00";
      $TxtEfectivo = "0.00";
      $TxtNC = "0.00";
      $TxtSaldoFavor = "0.00";
      ListaDeClientes();
      $Nuevo = false;
    }
	} 
        
}
?>