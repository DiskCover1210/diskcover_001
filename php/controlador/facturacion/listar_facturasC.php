<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/listar_facturasM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new listar_facturasC();
if(isset($_GET['cliente']))
{
	$query = '';
	if(isset($_GET['q']) && $_GET['q'] != ''  )
	{
		$query = $_GET['q']; 
	}
  if (isset($_GET['total'])) {
    echo json_encode($controlador->totalClientes());
  }else{
    echo json_encode($controlador->getClientes($query));
  }
}

if(isset($_GET['numFactura']))
{
  $query = '';
  $DCLinea = $_POST['DCLinea'];
  $fact = SinEspaciosIzq($DCLinea);
  $serie = SinEspaciosDer($DCLinea);
  $DCLinea = explode(" ", $DCLinea);
  $autorizacion = $DCLinea[2];
  $codigo = ReadSetDataNum($fact."_SERIE_".$serie, True, False);
  echo json_encode(array('codigo' => $codigo,'serie' => $serie,'autorizacion' => $autorizacion));
  exit();
}

if(isset($_GET['catalogo']))
{
	$controlador->getCatalogoLineas();
}

if(isset($_GET['serie']))
{
   $datos = $controlador->numeroSerie();
   echo json_encode($datos);
}

if(isset($_GET['secuencial']))
{
  $datos = $controlador->numeroSecuencial();
  echo json_encode($datos);
}

if(isset($_GET['minmaxsecuencial']))
{
  $datos = $controlador->minmaxsecuencial();
  echo json_encode($datos);
}

if(isset($_GET['catalogoProducto']))
{
	$controlador->getCatalogoProductos();
}

if(isset($_GET['historiaCliente']))
{
  $controlador->historiaCliente();
}

if(isset($_GET['historiaClienteExcel']))
{
  $controlador->historiaClienteExcel($_REQUEST['codigoCliente']);
}

if(isset($_GET['historiaClientePDF']))
{
  $controlador->historiaClientePDF($_REQUEST['codigoCliente']);
}

if(isset($_GET['enviarCorreo']))
{
  $controlador->enviarCorreo();
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

if(isset($_GET['guardarLineas']))
{
  $controlador->guardarLineas();
}

class listar_facturasC
{
	private $facturacion;
  private $pdf;


	public function __construct(){
    $this->autorizar_sri = new autorizacion_sri();
    $this->pdf = new cabecera_pdf();
    $this->email = new enviar_emails(); 
    $this->modelo = new listar_facturasM();
  }

  public function factura_formatos(){
    $datos = $this->modelo->factura_formatos();
    $formatos = [];
    $formatos[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
    foreach ($datos as $value) {
      $formatos[$i] = array('TC'=>utf8_encode($value['TC']));
      $i++;
    }
    return $formatos;
  }

	public function getClientes($query){
		$datos = $this->modelo->getClientes($query);
		$clientes = [];
		foreach ($datos as $key => $value) {
			$clientes[] = array('id'=>$value['Cliente'],'text'=>utf8_encode($value['Cliente']),'data'=>array('email'=> $value['Email'],'direccion' => utf8_encode($value['Direccion']), 'telefono' => utf8_encode($value['Telefono']), 'ci_ruc' => utf8_encode($value['CI_RUC']), 'codigo' => utf8_encode($value['Codigo']), 'cliente' => utf8_encode($value['Cliente']), 'grupo' => utf8_encode($value['Grupo']), 'tdCliente' => utf8_encode($value['TD'])));
		}
		echo json_encode($clientes);
		exit();
	}

  public function totalClientes(){
    $datos = $this->modelo->getClientes('total');
    $total = count($datos);
    echo json_encode(array('registros'=>$total));
    exit();
  }

	public function getCatalogoLineas(){
		$emision = $_POST['fechaEmision'];
		$vencimiento = $_POST['fechaVencimiento'];
		$datos = $this->modelo->getCatalogoLineas($emision,$vencimiento);
		$catalogo = [];
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$catalogo[] = array('id'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC'] ,'text'=>utf8_encode($value['Concepto']));
		}
		echo json_encode($catalogo);
		exit();
	}

	public function getCatalogoProductos(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->modelo->getCatalogoProductos($codigoCliente);
		$catalogo = [];
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$catalogo[] = array('mes'=> utf8_encode($value['Mes']),'codigo'=> utf8_encode($value['Codigo_Inv']),'periodo'=> utf8_encode($value['Periodos']),'producto'=> utf8_encode($value['Producto']),'valor'=> utf8_encode($value['Valor']), 'descuento'=> utf8_encode($value['Descuento']),'descuento2'=> utf8_encode($value['Descuento2']),'iva'=> utf8_encode($value['IVA']),'CodigoL'=> utf8_encode($value['Codigo']),'CodigoL'=> utf8_encode($value['Codigo']));
		}
		echo json_encode($catalogo);
		exit();
	}

  public function historiaCliente(){
    $codigoCliente = $_POST['codigoCliente'];
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->modelo->historiaCliente($codigoCliente);
    $historia = [];
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      $historia[] = array('TD'=> utf8_encode($value['TD']),'Fecha'=> utf8_encode($value['Fecha']->format('Y-m-d')),'Serie'=> utf8_encode($value['Serie']),'Factura'=> utf8_encode($value['Factura']),'Detalle'=> utf8_encode($value['Detalle']), 'Anio'=> utf8_encode($value['Anio']),'Mes'=> utf8_encode($value['Mes']),'Total'=> utf8_encode($value['Total']),'Abonos'=> utf8_encode($value['Abonos']),'Mes_No'=> utf8_encode($value['Mes_No']),'No'=> utf8_encode($value['No']) );
    }
    echo json_encode($historia);
    exit();
  }

  public function historiaClienteExcel($codigo,$download = true){
    $codigoCliente = $codigo;
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->modelo->historiaCliente($codigoCliente);
    historiaClienteExcel($datos,$ti='HistoriaCliente',$camne=null,$b=null,$base=null,$download);
  }

  public function historiaClientePDf($codigo,$download = true){
    $codigoCliente = $codigo;
    if ($codigoCliente == "") {
      $codigoCliente = G_NINGUNO;
    }
    $datos = $this->modelo->historiaCliente($codigoCliente);

    $titulo = 'HistoriaCliente';
    $parametros['desde'] = false;
    $parametros['hasta'] = false;
    $sizetable = 8;
    $mostrar = false;
    $tablaHTML = array();


    $tablaHTML[0]['medidas'] = array(8,18,10,15,60,10,20,15,15,10,10);
    $tablaHTML[0]['alineado'] = array('L','L','L','L','L','L','L','L','L','L','L');
    $tablaHTML[0]['datos'] = array('TD','Fecha','Serie','Factura','Detalle','Año','Mes','Total','Abonos','Mes No','No');
    $tablaHTML[0]['borde'] = 1;
    $tablaHTML[0]['estilo'] = 'B';

    $count = 1;
     while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      $tablaHTML[$count]['medidas'] = $tablaHTML[0]['medidas'];
      $tablaHTML[$count]['alineado'] = array('L','L','L','L','L','L','R','R','R','R','R');
      $tablaHTML[$count]['datos'] = array($value['TD'],$value['Fecha']->format('Y-m-d'),$value['Serie'],$value['Factura'],$value['Detalle'], $value['Anio'],$value['Mes'],$value['Total'],$value['Abonos'],$value['Mes_No'],$value['No']);
      $tablaHTML[$count]['borde'] = $tablaHTML[0]['borde'];
      $count+=1;
    }
    $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$parametros['desde'],$parametros['hasta'],$sizetable,$mostrar,25,$orientacion='P',$download);
  }

  public function enviarCorreo(){
    //Eliminar archivos temporales
    if (file_exists(dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.xlsx')) {
      unlink(dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.xlsx');
    }
    if (file_exists(dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.pdf')) {
      unlink(dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.pdf');
    }
    $this->historiaClientePDf($_REQUEST['codigoCliente'],false);
    $this->historiaClienteExcel($_REQUEST['codigoCliente'],false);
    $archivos[0] = dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.xlsx';
    $archivos[1] = dirname(__DIR__,2).'/vista/TEMP/HistoriaCliente.pdf';
    $to_correo = $_REQUEST['email'];
    $titulo_correo = 'Historial de cliente';
    $nombre = 'DiskCover System';
    $cuerpo_correo = 'Estimado (a) ha recibido su historial en formato PDF y EXCEL';
    $cuerpo_correo .= '<br>'.utf8_decode('
    <pre>
      -----------------------------------
      SERVIRLES ES NUESTRO COMPROMISO, DISFRUTARLO ES EL SUYO.


      Este correo electrónico fue generado automáticamente del Sistema Financiero Contable DiskCover System a usted porque figura como correo electrónico alternativo de Oblatas de San Francisco de Sales.
      Nosotros respetamos su privacidad y solamente se utiliza este correo electrónico para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su información personal fuera de nuestra empresa. Para obtener más información, comunicate a nuestro Centro de Atención al Cliente Teléfono: 052310304. Este mensaje fue recibido por: DiskCover Sytem.

      Por la atención que se de al presente quedo de usted.


      Esta dirección de correo electrónico no admite respuestas. En caso de requerir atención personalizada por parte de un asesor de servicio al cliente de DiskCover System, Usted podrá solicitar ayuda mediante los canales de atención al cliente oficiales que detallamos a continuación: Telefonos: (+593) 02-321-0051/098-652-4396/099-965-4196/098-910-5300.
      Emails: prisma_net@hotmail.es/diskcover@msn.com.

      www.diskcoversystem.com
      QUITO - ECUADOR</pre>');
    $this->email->enviar_historial($archivos,$to_correo,$cuerpo_correo,$titulo_correo,$nombre);
    exit();
    
  }

	public function getCatalogoCuentas(){
		$datos = $this->modelo->getCatalogoCuentas();
		$cuentas = [];
    $cuentas[0] = array('codigo'=>$value['Codigo'],'nombre'=>'No existen datos.');
    $i = 0;
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$cuentas[$i] = array('codigo'=>$value['Codigo'],'nombre'=>utf8_encode($value['Codigo'])." - ".utf8_encode($value['NomCuenta']));
      $i++;
		}
		return $cuentas;
	}

	public function getNotasCredito(){
		$datos = $this->modelo->getNotasCredito();
		$cuentas = [];
    $cuentas[0] = array('codigo'=>$value['Codigo'],'nombre'=>'No existen datos.');
    $i = 0;
		while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
			$cuentas[$i] = array('codigo'=>$value['Codigo'],'nombre'=>utf8_encode($value['Codigo'])." - ".utf8_encode($value['NomCuenta']));
      $i++;
		}
		return $cuentas;
	}

  public function getAnticipos(){
    $codigo = Leer_Seteos_Ctas('Cta_Anticipos_Clientes');
    $datos = $this->modelo->getAnticipos($codigo);
    $cuentas = [];
    $cuentas[0] = array('codigo'=>$value['Codigo'],'nombre'=>'No existen datos.');
    $i = 0;
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      $cuentas[$i] = array('codigo'=>$value['Codigo'],'nombre'=>utf8_encode($value['Codigo'])." - ".utf8_encode($value['NomCuenta']));
      $i++;
    }
    return $cuentas;
  }

	public function getSaldoFavor(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->modelo->getSaldoFavor($codigoCliente);
		$catalogo = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC);
		echo json_encode($catalogo);
		exit();
	}

	public function getSaldoPendiente(){
		$codigoCliente = $_POST['codigoCliente'];
		$datos = $this->modelo->getSaldoPendiente($codigoCliente);
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
   	$this->Grabar_FA_Pensiones($_POST,$TextFacturaNo);
	}


	public function Grabar_FA_Pensiones($FA,$TextFacturaNo){
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
      $TotalCajaMN = $FA['Total'] - $Total_Bancos - $SubTotal_NC;
      $TextoFormaPago = "CONTADO";
      $Total_Abonos = $TotalCajaMN + $Total_Bancos + $SubTotal_NC;
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
      $TA['Cheque'] = $FA['chequeNo'];
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
      $resultado = $this->autorizar_sri->Autorizar($FA);
      echo json_encode($resultado);
      exit();
    }
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

  public function numeroSerie(){
    $tc = $_POST['TC'];
    $datos = $this->modelo->numeroSerie($tc);
    $serie = [];
    $serie[0] = array('codigo'=>'','nombre'=>'No existen datos.');
    $i = 0;
    foreach ($datos as $value) {
      $serie[$i] = array('codigo'=>$value['Serie'],'nombre'=>$value['Serie']);
      $i++;
    }
    return $serie;
  }

  public function numeroSecuencial(){
    $tc = $_POST['TC'];
    $serie = $_POST['serie'];
    $datos = $this->modelo->numeroSecuencial($tc,$serie);
    $secuencial = [];
    foreach ($datos as $value) {
      $secuencial[] = array('nombre' => $value['Factura'], 'codigo' => utf8_encode($value['Autorizacion']."/".$value['Clave_Acceso']."/".$value['CodigoC']."/".$value['Razon_Social']));
    }
    return $secuencial;
  }

  public function minmaxsecuencial(){
    $tc = $_POST['TC'];
    $serie = $_POST['serie'];
    $datos = $this->modelo->minmaxSecuencial($tc,$serie);
    $secuencial = [];
    $secuencial[0] = array('desde'=>0,'hasta'=>0);
    if ($datos) {
      $secuencial = $datos;
    }
    return $secuencial;
  }

  public function BuscarFactura(){
    //acabar esta funcion
    $CSQL1 = ""; 
    $CSQL2 = "";
    $CSQL3 = "";
    $CSQL4 = "";
    $CSQL5 = ""; 
    $CSQL6 = "";
    $TxtXML = "";
    //DGAbonos.Visible = False
    //DGDetalle.Visible = False
    //DGAsiento.Visible = False
 
  /*ReDim CtasProc(30) As CtasAsiento
  For IE = 0 To UBound(CtasProc) - 1
      CtasProc(IE).Cta = "0"
      CtasProc(IE).Valor = 0
  Next IE
  
 'Volvemos a recalcular los totales de la factura
  With AdoFactList.Recordset
   If .RecordCount > 0 Then
      .MoveFirst
      .Find ("Factura = " & FA.Factura & " ")
       If Not .EOF Then FA.Autorizacion = .Fields("Autorizacion")
   End If
  End With
 ' MsgBox ".."
  Leer_Datos_FA_NV FA
 ' MsgBox "..."
 'Procesamos Factura
  If FA.Si_Existe_Doc Then*/
  
     $SQL3 = "SELECT F.Cta_CxP As Cta_Cobrar, TA.Cta_CxP, TA.Cta 
          FROM Facturas As F, Trans_Abonos As TA 
          WHERE F.Item = '" & NumEmpresa & "' 
          AND F.Periodo = '" & Periodo_Contable & "' 
          AND F.TC = '" & FA.TC & "' 
          AND F.Serie = '" & FA.Serie & "' 
          AND F.Autorizacion = '" & FA.Autorizacion & "' 
          AND F.Factura = " & FA.Factura & " 
          AND F.Item = TA.Item 
          AND F.Periodo = TA.Periodo 
          AND F.TC = TA.TP 
          AND F.Serie = TA.Serie 
          AND F.Autorizacion = TA.Autorizacion 
          AND F.Factura = TA.Factura 
          GROUP BY TA.Cta, TA.Cta_CxP, F.Cta_CxP ";
     SelectDataGrid DGAbonos, AdoAbonos, SQL3
     If AdoAbonos.Recordset.RecordCount > 0 Then
        Do While Not AdoAbonos.Recordset.EOF
           SetearCtasCierre AdoAbonos.Recordset.Fields("Cta")
           SetearCtasCierre AdoAbonos.Recordset.Fields("Cta_CxP")
           SetearCtasCierre AdoAbonos.Recordset.Fields("Cta_Cobrar")
           AdoAbonos.Recordset.MoveNext
        Loop
     End If
    'Consultamos el detalle de la factura
     SetearCtasCierre FA.Cta_CxP
     SetearCtasCierre Cta_IVA
     SetearCtasCierre Cta_Desc
     SetearCtasCierre Cta_Desc2
      
     InsValorCta FA.Cta_CxP, FA.Total_MN
     InsValorCta Cta_Desc, FA.Descuento
     InsValorCta Cta_Desc2, FA.Descuento2
     InsValorCta Cta_IVA, -FA.Total_IVA
     
    'Listamos el error en la autorizacion del documento si tuvier error
     If Len(FA.Autorizacion) >= 13 Then
         Label20.Caption = "Clave de Accceso: " & FA.TC & " "
         Cadena = SRI_Mensaje_Error(FA.ClaveAcceso)
         If Len(Cadena) > 1 Then
            TxtXML = "Clave de Accceso: " & FA.ClaveAcceso & vbCrLf _
                   & String(100, "-") & vbCrLf _
                   & Cadena _
                   & String(100, "-")
         Else
            TxtXML = "Clave de Accceso: " & FA.ClaveAcceso & vbCrLf _
                   & String(100, "-") & vbCrLf _
                   & "OK: No existe ningun error en su aprobacion" & vbCrLf _
                   & String(100, "-")
         End If
     End If
     If SQL_Server Then
        sSQL = "UPDATE Trans_Abonos " _
             & "SET Tipo_Cta = CC.TC " _
             & "FROM Trans_Abonos As TA, Catalogo_Cuentas As CC "
     Else
        sSQL = "UPDATE Trans_Abonos As TA, Catalogo_Cuentas As CC " _
             & "SET TA.Tipo_Cta = CC.TC "
     End If
     sSQL = sSQL _
          & "WHERE TA.Item = '" & NumEmpresa & "' " _
          & "AND TA.Periodo = '" & Periodo_Contable & "' " _
          & "AND TA.TP = '" & FA.TC & "' " _
          & "AND TA.Serie = '" & FA.Serie & "' " _
          & "AND TA.Factura = " & FA.Factura & " " _
          & "AND TA.Autorizacion = '" & FA.Autorizacion & "' " _
          & "AND TA.Item = CC.Item " _
          & "AND TA.Periodo = CC.Periodo " _
          & "AND TA.Cta = CC.Codigo "
     Conectar_Ado_Execute sSQL
     
     'FA.Autorizacion_GR = Ninguno
     SQL2 = "SELECT Serie_GR, Remision, Clave_Acceso_GR, Autorizacion_GR, Fecha, CodigoC, Comercial, CIRUC_Comercial, " _
          & "Entrega, CIRUC_Entrega, CiudadGRI, CiudadGRF, Placa_Vehiculo, FechaGRE, FechaGRI, FechaGRF, Pedido, Zona, " _
          & "Hora_Aut_GR, Estado_SRI_GR, Error_FA_SRI, Fecha_Aut_GR, TC, Serie, Factura, Autorizacion, Lugar_Entrega, " _
          & "Periodo, Item " _
          & "FROM Facturas_Auxiliares " _
          & "WHERE Item = '" & NumEmpresa & "' " _
          & "AND Periodo = '" & Periodo_Contable & "' " _
          & "AND Remision > 0 " _
          & "AND TC = '" & FA.TC & "' " _
          & "AND Serie = '" & FA.Serie & "' " _
          & "AND Factura = " & FA.Factura & " " _
          & "AND Autorizacion = '" & FA.Autorizacion & "' "
     SelectDataGrid DGGuiaRemision, AdoGuiaRemision, SQL2
      
     SQL2 = "SELECT DF.Codigo,DF.Producto,DF.Cantidad,DF.Precio,DF.Total,DF.Total_Desc,DF.Total_Desc2,DF.Total_IVA," _
          & "ROUND(((DF.Total-(DF.Total_Desc+DF.Total_Desc2))+DF.Total_IVA),2,0) As Valor_Total,DF.Mes,DF.Ticket," _
          & "DF.Serie,DF.Factura,DF.Autorizacion,CP.Detalle,CP.Cta_Ventas,CP.Reg_Sanitario,CP.Marca,Lote_No, DF.Modelo, " _
          & "DF.Procedencia, DF.Serie_No, DF.CodigoC, Cantidad_NC, SubTotal_NC,DF.CodMarca,DF.CodBodega," _
          & "DF.Tonelaje,Total_Desc_NC,Total_IVA_NC,DF.Periodo,DF.Codigo_Barra,DF.ID " _
          & "FROM Detalle_Factura As DF,Catalogo_Productos As CP " _
          & "WHERE DF.Item = '" & NumEmpresa & "' " _
          & "AND DF.Periodo = '" & Periodo_Contable & "' " _
          & "AND DF.TC = '" & FA.TC & "' " _
          & "AND DF.Serie = '" & FA.Serie & "' " _
          & "AND DF.Autorizacion = '" & FA.Autorizacion & "' " _
          & "AND DF.Factura = " & FA.Factura & " " _
          & "AND DF.Periodo = CP.Periodo " _
          & "AND DF.Item = CP.Item " _
          & "AND DF.Codigo = CP.Codigo_Inv " _
          & "ORDER BY CP.Cta_Ventas,DF.Codigo,DF.ID "
     SQLDec = "Precio " & CStr(Dec_PVP) & "|Total 2|Total_IVA 4|."
     SelectDataGrid DGDetalle, AdoDetalle, SQL2, SQLDec
     If AdoDetalle.Recordset.RecordCount > 0 Then
        Do While Not AdoDetalle.Recordset.EOF
           Contra_Cta = AdoDetalle.Recordset.Fields("Cta_Ventas")
           SetearCtasCierre Contra_Cta
           InsValorCta Contra_Cta, -AdoDetalle.Recordset.Fields("Total")
           AdoDetalle.Recordset.MoveNext
        Loop
        AdoDetalle.Recordset.MoveFirst
     End If
      
      FinBucle = True
     'Recolectamos los item de la factura a buscar
      LabelEstado.Caption = FA.T
      Label7.Caption = FA.Grupo
      LabelFechaPe.Caption = FA.Fecha
      FechaComp = FA.Fecha
      LabelCodigo.Caption = FA.CodigoC
      LabelCliente.Caption = FA.Cliente
      Label8.Caption = FA.Razon_Social & ", CI/RUC: " & FA.CI_RUC & vbCrLf _
                     & "Dirección: " & FA.DireccionC & ", Teléfono: " & FA.TelefonoC & vbCrLf _
                     & "Emails: " & FA.EmailC & "; " & FA.EmailR & vbCrLf _
                     & "Elaborado por: " & FA.Digitador & " (" & FA.Hora & ")"
                     
      LabelVendedor.Caption = " Ejecutivo: " & FA.Ejecutivo_Venta
      DireccionGuia = FA.Comercial
      TxtAutorizacion = FA.Autorizacion
      TxtClaveAcceso = FA.ClaveAcceso
      TxtObs = FA.Observacion
      LabelTransp.Caption = FA.Nota
      Label15.Caption = DireccionGuia
     'LabelFormaPa.Caption = .Fields("Forma_Pago")
      LabelServicio.Caption = Format$(FA.Servicio, "#,##0.00")
      LabelConIVA.Caption = Format$(FA.Con_IVA, "#,##0.00")
      LabelSubTotal.Caption = Format$(FA.Sin_IVA, "#,##0.00")
      LabelDesc.Caption = Format$(FA.Descuento + FA.Descuento2, "#,##0.00")
      LabelIVA.Caption = Format$(FA.Total_IVA, "#,##0.00")
      LabelTotal.Caption = Format$(FA.Total_MN, "#,##0.00")
      LabelSaldoAct.Caption = Format$(FA.Saldo_MN, "#,##0.00")
      Select Case LabelEstado.Caption
        Case Anulado
             LabelEstado.Caption = "Anulada"
        Case Pendiente, Normal
             LabelEstado.Caption = "Pendiente"
        Case Cancelado
             LabelEstado.Caption = "Cancelada"
        Case Else
             LabelEstado.Caption = "No existe"
      End Select

     'Consultamos los pagos Interes de Tarjetas y Abonos de Bancos con efectivo
      
      FA.Total_Abonos = 0
      FA.SubTotal_NC = 0
      FA.Total_IVA_NC = 0
      SQL3 = "SELECT C,T,Fecha,Banco,Cheque,Abono,Serie,Factura,Autorizacion,Protestado,CodigoC,Cta_CxP,Cta,Tipo_Cta," _
           & "Fecha_Aut_NC,Serie_NC,Secuencial_NC,Autorizacion_NC,Clave_Acceso_NC,TP,Recibo_No,Comprobante,Estado_SRI_NC,Hora_Aut_NC,Periodo,Item,CodigoU,Cod_Ejec " _
           & "FROM Trans_Abonos " _
           & "WHERE Item = '" & NumEmpresa & "' " _
           & "AND Periodo = '" & Periodo_Contable & "' " _
           & "AND Autorizacion = '" & FA.Autorizacion & "' " _
           & "AND TP = '" & FA.TC & "' " _
           & "AND Serie = '" & FA.Serie & "' " _
           & "AND Factura = " & FA.Factura & " " _
           & "ORDER BY TP,Fecha,Cta,Cta_CxP,Abono,Banco,Cheque "
      SelectDataGrid DGAbonos, AdoAbonos, SQL3
      If AdoAbonos.Recordset.RecordCount > 0 Then
        'Len(AdoAbonos.Recordset.Fields("Clave_Acceso_NC")) >= 13 And
         Do While Not AdoAbonos.Recordset.EOF
            If AdoAbonos.Recordset.Fields("TP") <> "TJ" Then
               FA.Total_Abonos = FA.Total_Abonos + AdoAbonos.Recordset.Fields("Abono")
               If AdoAbonos.Recordset.Fields("Banco") = "NOTA DE CREDITO" Then
                  FA.Porc_NC = FA.Porc_IVA
                  FA.Fecha_NC = AdoAbonos.Recordset.Fields("Fecha")
                  FA.Fecha_Aut_NC = AdoAbonos.Recordset.Fields("Fecha_Aut_NC")
                  FA.Serie_NC = AdoAbonos.Recordset.Fields("Serie_NC")
                  FA.Nota_Credito = AdoAbonos.Recordset.Fields("Secuencial_NC")
                  FA.Autorizacion_NC = AdoAbonos.Recordset.Fields("Autorizacion_NC")
                  FA.ClaveAcceso_NC = AdoAbonos.Recordset.Fields("Clave_Acceso_NC")
                  If AdoAbonos.Recordset.Fields("Cheque") = "VENTAS" Then
                     FA.SubTotal_NC = FA.SubTotal_NC + AdoAbonos.Recordset.Fields("Abono")
                  Else
                     FA.Total_IVA_NC = FA.Total_IVA_NC + AdoAbonos.Recordset.Fields("Abono")
                  End If
               End If
            End If
           'MsgBox AdoAbonos.Recordset.Fields("Cta") & vbCrLf & AdoAbonos.Recordset.Fields("Cta_CxP") & vbCrLf & AdoAbonos.Recordset.Fields("Abono") & " - " & Anulado
            If AdoAbonos.Recordset.Fields("T") <> Anulado Then
                InsValorCta AdoAbonos.Recordset.Fields("Cta"), AdoAbonos.Recordset.Fields("Abono")
                InsValorCta AdoAbonos.Recordset.Fields("Cta_CxP"), -AdoAbonos.Recordset.Fields("Abono")
            End If
            AdoAbonos.Recordset.MoveNext
         Loop
      End If
      
   'Procesamos el Saldo de la Factura
    FA.Saldo_MN = FA.Total_MN - FA.Total_Abonos
    If FA.Saldo_MN <= 0 Then TipoCta = Cancelado Else TipoCta = Pendiente
    LabelSaldoAct.Caption = Format$(FA.Saldo_MN, "#,##0.00")
    If FA.T <> Anulado Then
       sSQL = "UPDATE Detalle_Factura " _
            & "SET T = '" & TipoCta & "' " _
            & "WHERE Factura = " & FA.Factura & " " _
            & "AND Autorizacion = '" & FA.Autorizacion & "' " _
            & "AND Serie = '" & FA.Serie & "' " _
            & "AND TC = '" & FA.TC & "' " _
            & "AND Item = '" & NumEmpresa & "' " _
            & "AND Periodo = '" & Periodo_Contable & "' "
       Conectar_Ado_Execute sSQL
         
       sSQL = "UPDATE Facturas " _
            & "SET T = '" & TipoCta & "', Saldo_MN = " & FA.Saldo_MN & " " _
            & "WHERE Factura = " & FA.Factura & " " _
            & "AND Autorizacion = '" & FA.Autorizacion & "' " _
            & "AND Serie = '" & FA.Serie & "' " _
            & "AND TC = '" & FA.TC & "' " _
            & "AND Item = '" & NumEmpresa & "' " _
            & "AND Periodo = '" & Periodo_Contable & "' "
       Conectar_Ado_Execute sSQL
    End If
      
     'Listamos el asiento individual de la factura
      Trans_No = 255
      Cadena = ""
      IniciarAsientosDe DGAsiento, AdoAsiento
      For IE = 0 To UBound(CtasProc) - 1
          If Len(CtasProc(IE).Cta) > 1 Then
             Cadena = Cadena & CtasProc(IE).Cta & " = " & CtasProc(IE).Valor & vbCrLf
             If CtasProc(IE).Valor >= 0 Then
                InsertarAsientos AdoAsiento, CtasProc(IE).Cta, 0, CtasProc(IE).Valor, 0
             Else
                InsertarAsientos AdoAsiento, CtasProc(IE).Cta, 0, 0, -CtasProc(IE).Valor
             End If
          End If
      Next IE
     'MsgBox Cadena
      Debe = 0
      Haber = 0
      sSQL = "SELECT * " _
           & "FROM Asiento " _
           & "WHERE Item = '" & NumEmpresa & "' " _
           & "AND T_No = " & Trans_No & " " _
           & "AND CodigoU = '" & CodigoUsuario & "' " _
           & "ORDER BY DEBE DESC,HABER "
      SelectDataGrid DGAsiento, AdoAsiento, sSQL
      With AdoAsiento.Recordset
       If .RecordCount > 0 Then
           Do While Not .EOF
              Debe = Debe + .Fields("Debe")
              Haber = Haber + .Fields("Haber")
             .MoveNext
           Loop
       End If
      End With
      Frame1.Caption = FA.CxC_Clientes & ": EN BLOQUE"
      LabelDebe.Caption = Format$(Debe, "#,##0.00")
      LabelHaber.Caption = Format$(Haber, "#,##0.00")
      LblDiferencia.Caption = Format$(Debe - Haber, "#,##0.00")
      
      DGAbonos.Visible = True
      DGDetalle.Visible = True
      DGAsiento.Visible = True
      RatonNormal
  Else
      DGAbonos.Visible = True
      DGDetalle.Visible = True
      DGAsiento.Visible = True
      RatonNormal
      MsgBox "Esta Factura no existe."
      DCTipo.SetFocus
  End If
End Sub
  }
        
}
?>