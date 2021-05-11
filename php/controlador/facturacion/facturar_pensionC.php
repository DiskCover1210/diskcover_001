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
     	//Grabar_FA_Pensiones();
      //traer secuencial de catalogo lineas
     	$TextFacturaNo = ReadSetDataNum("FA_SERIE_001001", True, False);
	}


	public function Grabar_FA_Pensiones($datosFac){
		$codigoCliente = $datosFac['codigoCliente'];
		//Seteamos los encabezados para las facturas
		$Estudiante['cedula'] = $TextCI;
		$Estudiante['fonopaga'] = $TxtTelefono;
  	$Estudiante['pagador'] = $TextRepresentante;
		$Estudiante['direcpaga'] = $TxtDireccion;
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
    }
	} 

	/*
	Public Sub Grabar_FA_Pensiones()
  
  
 
  SelectAdodc AdoAsientoF, sSQL
  With AdoAsientoF.Recordset
   If .RecordCount > 0 Then
       Calculos_Totales_Factura FA
       
       If Existe_Factura(FA) Then
          Titulo = "FORMULARIO DE CONFIRMACION"
          Mensajes = "ADVERTENCIA:" & vbCrLf & vbCrLf _
                   & "Ya existe " & FA.TC & " No. " & FA.Serie & "-" & Format$(FA.Factura, "000000000") & vbCrLf & vbCrLf _
                   & "Desea Reprocesarla"
          If BoxMensaje = vbYes Then FA.Nuevo_Doc = False Else GoTo NoGrabarFA
       Else
          Factura_No = ReadSetDataNum(FA.TC & "_SERIE_" & FA.Serie, True, False)
          If FA.Factura <> Factura_No Then
             Titulo = "FORMULARIO DE CONFIRMACION"
             Mensajes = "La " & FA.TC & " No. " & FA.Serie & "-" & Format(FA.Factura, "000000000") _
                      & ", no esta Procesada, Desea Procesarla?"
             If BoxMensaje = vbYes Then FA.Nuevo_Doc = False Else GoTo NoGrabarFA
          End If
       End If
   
       SaldoPendiente = 0
       DiarioCaja = ReadSetDataNum("Recibo_No", True, True)
       If FA.Nuevo_Doc Then FA.Factura = ReadSetDataNum(FA.TC & "_SERIE_" & FA.Serie, True, True)
''       Bandera = False
''       Evaluar = True
''       Si_No = False
       TextoFormaPago = "CONTADO"
       Total_Abonos = TotalCajaMN + Total_Bancos + SubTotal_NC
       FA.T = Pendiente
       FA.Saldo_MN = FA.Total_MN - Total_Abonos
       FA.Porc_IVA = Porc_IVA
       FA.Cliente = NombreCliente
       TA.Recibi_de = FA.Cliente
       Cta = SinEspaciosIzq(DCBanco)
       Cta1 = SinEspaciosIzq(DCNC)
''       SerieFactura = FA.Serie
''       Factura_No = FA.Factura
''       Autorizacion = FA.Autorizacion
''       Fecha_Vence = FA.Vencimiento
''       Cta_Cobrar = FA.Cta_CxP
''       SubTotal_IVA = FA.Total_IVA
      .MoveFirst
       Do While Not .EOF
          Valor = .Fields("TOTAL")
          Codigo = .Fields("Codigo_Cliente")
          Codigo1 = .Fields("CODIGO")
          Codigo2 = .Fields("Mes")
          Codigo3 = .Fields("HABIT")
          Anio1 = .Fields("TICKET")
          sSQL = "UPDATE Clientes_Facturacion " _
               & "SET Valor = Valor - " & Valor & " " _
               & "WHERE Item = '" & NumEmpresa & "' " _
               & "AND Periodo = '" & Anio1 & "' " _
               & "AND Codigo_Inv = '" & Codigo1 & "' " _
               & "AND Codigo = '" & Codigo & "' " _
               & "AND Credito_No = '" & Codigo3 & "' " _
               & "AND Mes = '" & Codigo2 & "' "
          Conectar_Ado_Execute sSQL
         .MoveNext
       Loop
      'Grabamos el numero de factura
       Grabar_Factura FA, True
     
      'Seteos de Abonos Generales para todos los tipos de abonos
       TA.T = FA.T
       TA.TP = FA.TC
       TA.Serie = FA.Serie
       TA.Autorizacion = FA.Autorizacion
       TA.CodigoC = FA.CodigoC
       TA.Factura = FA.Factura
       TA.Fecha = FA.Fecha
       TA.Cta_CxP = FA.Cta_CxP
     
      'Abono de Factura Banco o Tarjetas
       TA.Cta = Cta
       TA.Banco = UCaseStrg(Grupo_No) & " - " & TextBanco
       TA.Cheque = TextCheqNo
       TA.Abono = Total_Bancos
       Grabar_Abonos TA
        
      'Abono de Factura
       TA.Cta = Cta_CajaG
       TA.Banco = "EFECTIVO MN"
       TA.Cheque = UCaseStrg(Grupo_No)
       TA.Abono = TotalCajaMN
       Grabar_Abonos TA
     
      'Forma del Abono SubTotal NC
       If SubTotal_NC > 0 Then
          SubTotal_NC = SubTotal_NC - SubTotal_IVA
          TA.Cta = Cta1
          TA.Banco = "NOTA DE CREDITO"
          TA.Cheque = "VENTAS"
          TA.Abono = SubTotal_NC
          Grabar_Abonos TA
       End If
     
      'Forma del Abono IVA NC
       If SubTotal_IVA > 0 Then
          TA.Cta = Cta_IVA
          TA.Banco = "NOTA DE CREDITO"
          TA.Cheque = "I.V.A."
          TA.Abono = SubTotal_IVA
          Grabar_Abonos TA
       End If
     
      'Abono de Factura
       TA.T = Normal
       TA.TP = "TJ"
       TA.Cta = Cta
       TA.Cta_CxP = Cta_Tarjetas
       TA.Banco = "INTERES POR TARJETA"
       TA.Cheque = TextCheqNo
       TA.Abono = Val(TextInteres)
       TA.Recibi_de = FA.Cliente
       Grabar_Abonos TA
       
       TA.T = FA.T
       TA.TP = FA.TC
       TA.Serie = FA.Serie
       TA.Factura = FA.Factura
       TA.Autorizacion = FA.Autorizacion
       TA.CodigoC = FA.CodigoC

       RatonNormal
       TxtEfectivo.Text = "0.00"
       If Len(FA.Autorizacion) >= 13 Then
          If Not No_Autorizar Then SRI_Crear_Clave_Acceso_Facturas FA, False, True
          FA.Desde = FA.Factura
          FA.Hasta = FA.Factura
          Imprimir_Facturas_CxC FacturasPension, FA, True, False, True, True
          SRI_Generar_PDF_FA FA, True
''          RutaDestino = RutaSysBases & "\TEMP\" & FA.Autorizacion & ".pdf"
''          MsgBox RutaDestino
''          SRI_Presenta_PDF FacturasPension, RutaDestino
       Else
          Mensajes = "Facturacion Multiple"
          Titulo = "IMPRESION"
          If BoxMensaje = vbYes Then
             FA.Desde = FA.Factura
             FA.Hasta = FA.Factura
             Imprimir_Facturas_CxC FacturasPension, FA
          Else
             Imprimir_Facturas FA
          End If
         'Imprimir_Comprobante_Caja TA
       End If
       RatonReloj
       TA.Autorizacion = FA.Autorizacion
       Actualiza_Estado_Factura TA
       'MsgBox TA.Factura & vbCrLf & TA.TP & vbCrLf & TA.Serie
       Facturas_Impresas FA
       
       sSQL = "SELECT * " _
            & "FROM Asiento_F " _
            & "WHERE Item = '" & NumEmpresa & "' " _
            & "AND CodigoU = '" & CodigoUsuario & "' "
       SelectAdodc AdoAsientoF, sSQL
       TextInteres = "0.00"
       TextCheque = "0.00"
       TxtEfectivo = "0.00"
       TxtNC = "0.00"
       TxtSaldoFavor = "0.00"
       LblSaldo.Caption = "0.00"
       ListaDeClientes
       Nuevo = False
       RatonNormal
      'MsgBox Estudiante_DBF.codest
   Else
NoGrabarFA:
       RatonNormal
       MsgBox "No se procedio a grabar el documento " & FA.TC & " No. " & FA.Serie & "-" _
            & Format(FA.Factura, "000000000") & ", revise los datos ingresados y vuelva a intentar"
   End If
  End With
End Sub*/
}
?>