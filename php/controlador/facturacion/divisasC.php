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

if(isset($_GET['ticketPDF']))
{
  $controlador->ticketPDF();
}

if(isset($_GET['productos']))
{
  $codigoLinea = $_POST['DCLinea'];
  $TC = explode(" ", $codigoLinea);
  $datos = $controlador->getProductos($TC[0]);
  echo json_encode($datos);
}

if(isset($_GET['cliente']))
{
  $query = 'consumidor final';
  if(isset($_GET['q']) && $_GET['q'] != ''  )
  {
    $query = $_GET['q']; 
  }
  echo json_encode($controlador->getClientes($query));
}


if(isset($_GET['catalogoLineas']))
{
  $fecha = $_POST['fecha'];
  $datos = $controlador->getCatalogoLineas($fecha);
  echo json_encode($datos);
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

  public function getCatalogoLineas($fecha){
    $datos = $this->modelo->getCatalogoLineas($fecha);
    $catalogo = [];
    foreach ($datos as $value) {
      $catalogo[] = array('codigo'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC']." " ,'nombre'=>$value['Concepto']);
    }
    return $catalogo;
  }

  public function getProductos($TC){
    $datos = $this->modelo->getProductos($TC);
    $productos = [];
    foreach ($datos as $value) {
      // $productos[] = array('codigo'=>$value['Codigo_Inv']."/".utf8_encode($value['Producto'])."/".$value['PVP']."/".$value['Div'] ,'nombre'=> utf8_encode($value['Producto'])); 
      $productos[] = array('codigo'=>$value['Codigo_Inv']."/".$value['Producto']."/".$value['PVP']."/".$value['Div'] ,'nombre'=> $value['Producto']);
    }
    return $productos;
  }

  public function getClientes($query){
    $datos = $this->modelo->getClientes($query);
    $clientes = [];
    foreach ($datos as $key => $value) {
      $clientes[] = array('id'=>$value['Cliente'],'text'=>utf8_encode($value['Cliente']),'data'=>array('email'=> $value['Email'],'direccion' => utf8_encode($value['Direccion']), 'telefono' => utf8_encode($value['Telefono']), 'ci_ruc' => utf8_encode($value['CI_RUC']), 'codigo' => utf8_encode($value['Codigo']), 'cliente' => utf8_encode($value['Cliente']), 'grupo' => utf8_encode($value['Grupo']), 'tdCliente' => utf8_encode($value['TD'])));
    }
    return $clientes;
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
    foreach ($datos as $value) {
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
      $this->modelo->updateClientesFacturacion($Valor,$Anio1,$Codigo1,$Codigo,$Codigo3,$Codigo2);
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
    }
    if (strlen($FA['Autorizacion']) == 13) {
      $resultado = $this->autorizar_sri->Autorizar($FA);
    }else{
      $resultado = array('respuesta'=>4);
    }
    echo json_encode($resultado);
    
  }

  public function ticketPDF(){
    date_default_timezone_set('America/Guayaquil');
    $ci = $_GET['CI'];
    $serie = $_GET['serie'];
    $fac = $_GET['fac'];
    $TC = $_GET['TC'];
    $efectivo = $_GET['efectivo'];
    $saldo = $_GET['saldo'];
    $parametros = array('tipo'=>'FA','ci'=>$ci,'serie'=>$serie,'factura'=>$fac,'TC' => $TC,'efectivo' => $efectivo,
      'saldo' => $saldo);
    $datos_pre  ="";
    $datos_pre =  $this->modelo->datos_factura($parametros);
    $cabe ='<font face="Courier New" size=2>Transaccion ('.$TC.'): No. '.$datos_pre['lineas'][0]['Factura'].' <br>
        Fecha: '.date('Y-m-d').' - Hora: </b>'.date('H:m:s').' <br>
        Cliente: <br>'.$datos_pre['cliente']['Cliente'].'<br>
        R.U.C/C.I.: '.$datos_pre['cliente']['CI_RUC'].'<br> 
        Cajero: '.$_SESSION['INGRESO']['Nombre'].' <br>
        Telefono: '.$datos_pre['cliente']['Telefono'].'<br>
        Dirección: '.$datos_pre['cliente']['Direccion'].'<br>';
    $cabe .= "<hr>PRODUCTO/Cant x PVP/TOTAL";
    $lineas = "<hr>";
    foreach ($datos_pre['lineas'] as $key => $value) {
      if($value['Total_IVA']==0)
      {
        $lineas.= '<div class="row"><div class="col-sm-12">'.$value['Producto'].' </div></div>';
      }else
      {
        $lineas.= '<div class="row"><div class="col-sm-12">'.$value['Producto'].'</div></div>';
      }
      $lineas.='<div class="row"><div class="col-sm-6">'.$value['Cantidad'].' X '.number_format($value['Precio'],2).'</div><div class="col-sm-6" style="text-align: right;">'.number_format($value['Total'],2).'</div></div>';
    }
    $totales = "<hr>
     <table>
       <tr>
         <td style='width: 250px;' colspan='3'></td>
         <td style='text-align: right;'>SUBTOTAL:</td>
         <td style='text-align: right;'>".number_format($datos_pre['tota'],2) ."</td>
       </tr>
       <tr>
         <td colspan='3'></td>
         <td style='text-align: right;'>I.V.A 12%:</td>
         <td style='text-align: right;'>".number_format($datos_pre['iva'],2) ."</td>
       </tr>
       <tr>
         <td colspan='3'></td>
         <td style='text-align: right;'>TOTAL FACTURA:</td>
         <td style='text-align: right;'>".number_format($datos_pre['tota'],2)."</td>
       </tr>
       <tr>
         <td colspan='3'></td>
         <td style='text-align: right;'>EFECTIVO:</td>
         <td style='text-align: right;'>".number_format($efectivo,2)."</td>
       </tr>
       <tr>
         <td colspan='3'></td>
         <td style='text-align: right;'>CAMBIO:</td>
         <td style='text-align: right;'>".number_format($saldo,2)."</td>
       </tr>
     </table>";

    $datos_extra = "<hr>
    <table style='width:100%'>
      <tr>
        <td style='text-align:center'>Fue un placer atenderle <br>Gracias por su compra<br>www.cofradiadelvino.com <br></td>
      </tr>
    </table></font>";
    $html =  $cabe.$lineas.$totales.$datos_extra;
    $this->pdf->formatoPDFMatricial($html,$parametros,$datos_pre);
  }
        
}
?>