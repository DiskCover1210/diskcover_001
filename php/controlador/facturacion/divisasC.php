<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/divisasM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new divisasC();

if(isset($_GET['guardarLineas']))
{
  $controlador->guardarLineas();
}

if(isset($_GET['guardarFactura']))
{
  $controlador->guardarFactura();
}

class divisasC
{
	private $modelo;
  private $pdf;

	public function __construct(){
    $this->modelo = new divisasM();
    $this->autorizar_sri = new autorizacion_sri();
    $this->pdf = new cabecera_pdf();
    $this->email = new enviar_emails(); 
  }

  public function getCatalogoLineas(){
    $datos = $this->modelo->getCatalogoLineas();
    $catalogo = [];
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      //$catalogo[] = array('id'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC'] ,'text'=>utf8_encode($value['Concepto']));
      $catalogo[] = array('id'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC'] ,'text'=>$value['Concepto']);
    }
    return $catalogo;
  }

  public function getProductos(){
    $datos = $this->modelo->getProductos();
    $productos = [];
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      //$productos[] = array('id'=>utf8_decode($value['Codigo_Inv'])." ".utf8_decode($value['Producto']) ,'text'=>utf8_encode($value['Producto']));
      $productos[] = array('id'=>$value['Codigo_Inv']."/".$value['Producto']."/".$value['PVP']."/".$value['Div'] ,'text'=>$value['Producto']);
    }
    return $productos;
  }

  public function guardarLineas(){
    $this->modelo->deleteAsiento($_POST['codigoCliente']);
    $datos = array();
    $Contador = 0;
    foreach ($_POST['datos'] as $key => $producto) {
      $dato[0]['campo']='CODIGO';
      $dato[0]['dato']= $producto['Codigo'];
      $dato[1]['campo']='CODIGO_L';
      $dato[1]['dato']= $producto['CodigoL'];
      $dato[2]['campo']='PRODUCTO';
      $dato[2]['dato']= $producto['Producto'] ;
      $dato[3]['campo']='CANT';
      $dato[3]['dato']= 1;
      $dato[4]['campo']='PRECIO';
      $dato[4]['dato']= $producto['Precio'] ;
      $dato[5]['campo']='Total_Desc';
      $dato[5]['dato']= $producto['Total_Desc'] ;
      $dato[6]['campo']='Total_Desc2';
      $dato[6]['dato']= $producto['Total_Desc2'] ;
      $dato[7]['campo']='TOTAL';
      $dato[7]['dato']= $producto['Total'];
      $dato[8]['campo']='Total_IVA';
      $dato[8]['dato']= $producto['Total'] * ($producto['Iva'] / 100);
      $dato[9]['campo']='Cta';
      $dato[9]['dato']= 'Cuenta' ;
      $dato[10]['campo']='Item';
      $dato[10]['dato']= $_SESSION['INGRESO']['item'];
      $dato[11]['campo']='Codigo_Cliente';
      $dato[11]['dato']= $_POST['codigoCliente'];
      $dato[12]['campo']='HABIT';
      $dato[12]['dato']= G_PENDIENTE;
      $dato[13]['campo']='Mes';
      $dato[13]['dato']= $producto['MiMes'] ;
      $dato[14]['campo']='TICKET';
      $dato[14]['dato']= $producto['Periodo'] ;
      $dato[15]['campo']='CodigoU';
      $dato[15]['dato']= $_SESSION['INGRESO']['CodigoU'];
      $dato[16]['campo']='A_No';
      $dato[16]['dato']= $Contador;
      $Contador++;
      insert_generico("Asiento_F",$dato);
    }
  }

  public function guardarFactura(){
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
    $Cta_CajaG = 1;
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
      $updateCliF = $this->modelo->updateClientesFacturacion($TxtGrupo,$codigoCliente);
      $updateCliM = $this->modelo->updateClientesMatriculas($TextRepresentante,$TextCI,$TD_Rep,$TxtTelefono,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente);
      $updateCli = $this->modelo->updateClientes($TxtTelefono,$TxtDirS,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente);
    }
    $TC = SinEspaciosIzq($_POST['DCLinea']);
    $serie = SinEspaciosDer($_POST['DCLinea']);
    //traer secuencial de catalogo lineas
    $TextFacturaNo = ReadSetDataNum($TC."_SERIE_".$serie, True, False);
    $this->Grabar_FA($_POST,$TextFacturaNo);
  }


  public function Grabar_FA($FA,$TextFacturaNo){
    $codigoCliente = $FA['codigoCliente'];
    //Seteamos los encabezados para las facturas
    $Estudiante['cedula'] = $FA['TextCI'];
    $Estudiante['fonopaga'] = $FA['TxtTelefono'];
    $Estudiante['pagador'] = $FA['TextRepresentante'];
    $Estudiante['direcpaga'] = $FA['TxtDireccion'];
    $resultado = explode(" ", $FA['DCLinea']);
    $FA['Autorizacion'] = $resultado[2];
    $FA['Cta_CxP'] = $resultado[3];
    //Procedemos a grabar la factura
    $datos = $this->modelo->getAsiento();
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      $TFA = Calculos_Totales_Factura($codigoCliente);
      $FA['Tipo_PRN'] = "FM";
      $FA['FacturaNo'] = $TextFacturaNo;
      $FA['Nuevo_Doc'] = true;
      $FA['Factura'] = intval($TextFacturaNo);
      $FA['TC'] = SinEspaciosIzq($FA['DCLinea']);
      $FA['Serie'] = SinEspaciosDer($FA['DCLinea']);
      if (Existe_Factura($FA)) {
        
      }
      $SaldoPendiente = 0;
      $DiarioCaja = ReadSetDataNum("Recibo_No", True, True);
      if ($FA['Nuevo_Doc']) {
        $FA['Factura'] = ReadSetDataNum($FA['TC']."_SERIE_".$FA['Serie'], True, True);
      }
      $SubTotal_NC = $FA['DCNC'];
      $Total_Bancos = $FA['TextCheque'];
      $TotalCajaMN = $FA['Total'] ;
      $TextoFormaPago = "CONTADO";
      $Total_Abonos = $TotalCajaMN;
      $FA['Total_Abonos'] = $Total_Abonos;
      $FA['T'] = G_PENDIENTE;
      $FA['Saldo_MN'] = $FA['Total'] - $Total_Abonos;
      $FA['Porc_IVA'] = $value['Total_IVA'];
      $FA['Cliente'] = $FA['TextRepresentante'];
      $FA['me'] = $value['HABIT'];
      $TA['me'] = $value['HABIT'];
      $TA['Recibi_de'] = $FA['Cliente'];
      $Cta = SinEspaciosIzq($FA['DCBanco']);
      $Cta1 = SinEspaciosIzq($FA['DCNC']);
      $Valor = $value["TOTAL"];
      $Codigo = $value["Codigo_Cliente"];
      $Codigo1 = $value["CODIGO"];
      $Codigo2 = $value["Mes"];
      $Codigo3 = ".";
      $Anio1 = $value["TICKET"];
      $this->modelo->updateClientesFacturacion1($Valor,$Anio1,$Codigo1,$Codigo,$Codigo3,$Codigo2);
      //Grabamos el numero de factura
      Grabar_Factura($FA);
      //Seteos de Abonos Generales para todos los tipos de abonos
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente']; //codigo cliente
      $TA['Factura'] = $FA['Factura'];
      $TA['Fecha'] = $FA['Fecha'];
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['email'] = $FA['TxtEmail'];
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";
     
      //Abono de Factura Banco o Tarjetas
      $TA['Cta'] = $Cta;
      $TA['Banco'] = strtoupper($FA['TxtGrupo'])." - ".$FA['DCBanco'];
      $TA['Cheque'] = '';
      $TA['Abono'] = $Total_Bancos;
      Grabar_Abonos($TA);

      $Cta_CajaG = 1;
      //Abono de Factura
      $TA['Cta'] = $Cta_CajaG;
      $TA['Banco'] = "EFECTIVO MN";
      $TA['Cheque'] = strtoupper($FA['TextCheque']);
      $TA['Abono'] = $TotalCajaMN;
      $TA['Comprobante'] = "";
      $TA['Codigo_Inv'] = "";
      Grabar_Abonos($TA);

      //Forma del Abono SubTotal NC
      if ($SubTotal_NC > 0) {
        $SubTotal_NC = $SubTotal_NC - $TFA['Total_IVA'];
        $TA['Cta'] = $Cta1;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "VENTAS";
        $TA['Abono'] = $SubTotal_NC;
        Grabar_Abonos($TA);
      }
     
      //Forma del Abono IVA NC
      if ($TFA['Total_IVA'] > 0) {
        $TA['Cta'] = $Cta_IVA;
        $TA['Banco'] = "NOTA DE CREDITO";
        $TA['Cheque'] = "I.V.A.";
        $TA['Abono'] = $TFA['Total_IVA'];
        Grabar_Abonos($TA);
      }
     
      $TextInteres = 0;
      //Abono de Factura
      $TA['T'] = G_NORMAL;
      $TA['TP'] = "TJ";
      $TACta = $Cta;
      $TA['Cta_CxP'] = $FA['Cta_CxP'];
      $TA['Banco'] = "INTERES POR TARJETA";
      $TA['Cheque'] =  $FA['TextCheque'];
      $TA['Abono'] = intval($TextInteres);
      $TA['Recibi_de'] = $FA['Cliente'];
      Grabar_Abonos($TA);
       
      $TA['T'] = $FA['T'];
      $TA['TP'] = $FA['TC'];
      $TA['Serie'] = $FA['Serie'];
      $TA['Factura'] = $FA['Factura'];
      $TA['Autorizacion'] = $FA['Autorizacion'];
      $TA['CodigoC'] = $FA['codigoCliente'];

      $TxtEfectivo = "0.00";
      if (strlen($FA['Autorizacion']) >= 13) {
        /*if (!$No_Autorizar) {
        }*/
        //print_r("expression");
        //generar_xml();
        $FA['Desde'] = $FA['Factura'];
        $FA['Hasta'] = $FA['Factura'];
        //Imprimir_Facturas_CxC(FacturasPension, FA, True, False, True, True);
        //SRI_Generar_PDF_FA(FA, True);
      }
      $FA['serie'] = $FA['Serie'];
      $FA['num_fac'] = $FA['Factura'];
      $FA['tc'] = $FA['TC'];
      $FA['cod_doc'] = '01';
      if (strlen($FA['Autorizacion']) == 13) {
        $resultado = $this->autorizar_sri->Autorizar($FA);
      }else{
        $resultado = array('respuesta'=>4);
      }
      echo json_encode($resultado);
      exit();
    }
  }
        
}
?>