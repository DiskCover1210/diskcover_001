<?php
require_once(dirname(__DIR__,2)."/modelo/cybernet/facturaM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new facturaC();
if(isset($_GET['cliente']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->getClientes($query));
}

if(isset($_GET['catalagoProducto']))
{
  $query = '';
  if(isset($_GET['q']))
  {
    $query = $_GET['q'];
  }
	$controlador->getCatalogoProductos($query);
}

if(isset($_GET['guardarFactura']))
{
  $controlador->guardarFactura();
}

class facturaC
{
	private $facturacion;
  private $pdf;


	public function __construct(){
    $this->facturacion = new facturaM();
    $this->autorizar_sri = new autorizacion_sri();
    $this->pdf = new cabecera_pdf();
  }

	public function getClientes($query){
		$datos = $this->facturacion->getClientes($query);
		$clientes = [];
		foreach ($datos as $key => $value) {
			$clientes[] = array('id'=>$value['Cliente'],'text'=>$value['Cliente'],'data'=>array('email'=> $value['Email'],'direccion' => $value['Direccion']), 'telefono' =>$value['Telefono'], 'ci_ruc' => $value['CI_RUC'], 'codigo' => $value['Codigo'], 'cliente' => $value['Cliente'], 'celular' =>$value['Celular']);
		}
		echo json_encode($clientes);
		exit();
	}

	public function getCatalogoProductos($query){
		$datos = $this->facturacion->getCatalogoProductos($query);
		$productos = [];
		foreach ($datos as $key => $value) {
      //print_r($value);
      $productos[] = array('id' =>$value['Producto']."/".$value['PVP']."/".($value['IVA'])."/".$value['Codigo_Inv'],'text' =>$value['Producto'], 'data' => array('pvp' =>$value['PVP']));
    }
		echo json_encode($productos);
		exit();
	}

  public function guardarFactura(){
    //datos
    $update = $_POST['update'];
    $cliente = $_POST['cliente'];
    $nombreCliente = $_POST['nombreCliente'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $factura = $_POST['factura'];
    $ci_ruc = $_POST['ci_ruc'];
    $email = $_POST['email'];
    $celular = $_POST['celular'];
    $codigoCliente = $_POST['codigoCliente'];
    if ($codigoCliente != '') {
      //actualizar datos del cliente
      $this->facturacion->updateCliente($telefono,$direccion,$email,$celular,$codigoCliente);
    }else{
      //crear nuevo cliente
      $dato[0]['campo'] = 'Codigo';
      $dato[0]['dato'] = $ci_ruc;
      $dato[1]['campo'] = 'Cliente';
      $dato[1]['dato'] = $nombreCliente;
      $dato[2]['campo'] = 'CI_RUC';
      $dato[2]['dato'] = $ci_ruc;
      $dato[3]['campo'] = 'Email';
      $dato[3]['dato'] = $email;
      $dato[4]['campo'] = 'Direccion';
      $dato[4]['dato'] = $direccion; 
      $dato[5]['campo'] = 'Telefono';
      $dato[5]['dato'] = $telefono;
      $dato[6]['campo'] = 'Celular';
      $dato[6]['dato'] = $celular;
      $dato[7]['campo'] = 'T';
      $dato[7]['dato'] = 'N';
      $dato[8]['campo'] = 'FA';
      $dato[8]['dato'] = 1;
      insert_generico('Clientes',$dato);
    }
    $serie = $_SESSION['INGRESO']['Serie_FA'];
    $TextFacturaNo = ReadSetDataNum("FA_SERIE_".$serie , True, False);
    //Seteamos los encabezados para las facturas
    $catalogoLinea = $this->facturacion->getCatalogoLineas();
    $FA['Autorizacion'] = $catalogoLinea['Autorizacion'];
    $FA['Cta_CxP'] = $catalogoLinea['CxC'];
    $this->guardarLineas($_POST);
    //Procedemos a grabar la factura
    $datos = $this->facturacion->getAsiento();
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      $TFA = Calculos_Totales_Factura($codigoCliente);
      $FA['Tipo_PRN'] = "FM";
      $FA['FacturaNo'] = $factura;
      $FA['Nuevo_Doc'] = true;
      $FA['Factura'] = $factura;
      $FA['TC'] = 'FA';
      $FA['Serie'] = $_SESSION['INGRESO']['Serie_FA'];
      $FA['Total'] = $_POST['total'];
      $SaldoPendiente = 0;
      $DiarioCaja = ReadSetDataNum("Recibo_No", True, True);
      $SubTotal_NC = 0;
      $Total_Bancos = 0;
      $TotalCajaMN = $FA['Total'] - $Total_Bancos - $SubTotal_NC;
      $TextoFormaPago = "CONTADO";
      $Total_Abonos = $TotalCajaMN + $Total_Bancos + $SubTotal_NC;
      $FA['Total_Abonos'] = $Total_Abonos;
      $FA['T'] = G_PENDIENTE;
      $FA['Saldo_MN'] = $FA['Total'] - $Total_Abonos;
      $FA['Porc_IVA'] = $value['Total_IVA'];
      $FA['Cliente'] = $cliente;
      $FA['me'] = $value['HABIT'];
      $TA['me'] = $value['HABIT'];
      $FA['TextCI'] = $ci_ruc;
      $FA['TxtEmail'] = $email;
      $TA['Recibi_de'] = $cliente;
      $FA['codigoCliente'] = $codigoCliente;
      $FA['Fecha'] = date('Y-m-d');
      $Valor = $value["TOTAL"];
      $Codigo = $value["Codigo_Cliente"];
      $Codigo1 = $value["CODIGO"];
      $Codigo2 = $value["Mes"];
      $Codigo3 = ".";
      $Anio1 = $value["TICKET"];
      //Grabamos el numero de factura
      Grabar_Factura($FA);

      //Seteos de Abonos Generales para todos los tipos de abonos
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente'];
      $TA['Factura'] = $FA['Factura'];
      $TA['Fecha'] = $FA['Fecha'];
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['email'] = $FA['TxtEmail'];
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";

      $Cta_CajaG = 1;
     
      $TextInteres = 0;
      //Abono de Factura
      $TA['Cta'] = $Cta_CajaG;
      $TA['Banco'] = "EFECTIVO MN";
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['Cheque'] = strtoupper($FA['Total']);
      $TA['Abono'] = $TotalCajaMN;
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";
      Grabar_Abonos($TA);
       
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Factura'] = $FA['Factura'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente'];

      $TxtEfectivo = "0.00";
      if (strlen($FA['Autorizacion']) >= 13) {
        $FA['Desde'] = $FA['Factura'];
        $FA['Hasta'] = $FA['Factura'];
      }
      $FA['serie'] = $FA['Serie'];
      $FA['num_fac'] = $FA['Factura'];
      $FA['tc'] = $FA['TC'];
      $FA['cod_doc'] = '01';
      $resultado = $this->autorizar_sri->Autorizar($FA);
      echo json_encode($resultado);
      exit();
    }
    exit();
  }

  public function guardarLineas($datos){
    $codigoCliente = '';
    if (isset($datos['codigoCliente']) && $datos['codigoCliente'] != '') {
      $codigoCliente = $datos['codigoCliente'];
    }else{
      $codigoCliente = $datos['ci_ruc'];  
    }
    $this->facturacion->deleteAsiento($datos['codigoCliente']);
    $dato = array();
    $Contador = 0;
    $mes = mes_X_nombre(date('m'));
    $periodo = date('Y');
    foreach ($datos['datos'] as $key => $producto) {
      $dato[0]['campo']='CODIGO';
      $dato[0]['dato']= $producto['codigo'];
      $dato[1]['campo']='CODIGO_L';
      $dato[1]['dato']= $producto['codigo'];
      $dato[2]['campo']='PRODUCTO';
      $dato[2]['dato']= $producto['producto'] ;
      $dato[3]['campo']='CANT';
      $dato[3]['dato']= $producto['cantidad'];
      $dato[4]['campo']='PRECIO';
      $dato[4]['dato']= $producto['pvp'] ;
      $dato[5]['campo']='Total_Desc';
      $dato[5]['dato']= 0 ;
      $dato[6]['campo']='Total_Desc2';
      $dato[6]['dato']= 0 ;
      $dato[7]['campo']='TOTAL';
      $dato[7]['dato']= $producto['total'];
      $dato[8]['campo']='Total_IVA';
      $dato[8]['dato']= $producto['total'] * ($producto['iva'] / 100);
      $dato[9]['campo']='Cta';
      $dato[9]['dato']= 'Cuenta' ;
      $dato[10]['campo']='Item';
      $dato[10]['dato']= $_SESSION['INGRESO']['item'];
      $dato[11]['campo']='Codigo_Cliente';
      $dato[11]['dato']= $_POST['codigoCliente'];
      $dato[12]['campo']='HABIT';
      $dato[12]['dato']= G_PENDIENTE;
      $dato[13]['campo']='Mes';
      $dato[13]['dato']= $mes ;
      $dato[14]['campo']='TICKET';
      $dato[14]['dato']= $periodo ;
      $dato[15]['campo']='CodigoU';
      $dato[15]['dato']= $_SESSION['INGRESO']['CodigoU'];
      $dato[16]['campo']='A_No';
      $dato[16]['dato']= $Contador;
      $Contador++;
      insert_generico("Asiento_F",$dato);
    }
  }
        
}
?>