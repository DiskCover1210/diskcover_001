<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/facturarM.php");
//require_once(dirname(__DIR__,2)."/vista/appr/modelo/modelomesa.php");

$controlador = new facturarC();
if(isset($_GET['lineas_factura']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_facturas());
}
if(isset($_GET['DCMod']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCMod());
}
if(isset($_GET['DCLineas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCLinea($parametros));
}
if(isset($_GET['DCTipoPago']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCTipoPago());
}
if(isset($_GET['DCBodega']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCBodega());
}
if(isset($_GET['DCMarca']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCMarca());
}
if(isset($_GET['DCMedico']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCMedico());
}
if(isset($_GET['PorCodigo']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->PorCodigo());
}
if(isset($_GET['Lineas_De_CxC']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Lineas_De_CxC($parametros));
}
if(isset($_GET['CDesc1']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->CDesc1());
}
if(isset($_GET['DCEjecutivo']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->DCEjecutivo());
}

if(isset($_GET['DCGrupo_No']))
{
	//$parametros = $_POST['parametros'];
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->DCGrupo_No($query));
}
if(isset($_GET['numero_factura']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->numero_factura());
}
if(isset($_GET['LstOrden']))
{
	//$parametros = $_POST['parametros'];
	echo json_encode($controlador->LstOrden());
}

if(isset($_GET['DCCliente']))
{
	//$parametros = $_POST['parametros'];
	$grupo = G_NINGUNO;
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	if(isset($_GET['Grupo'])!='')
	{
		$grupo = $_GET['Grupo'];
	}
	echo json_encode($controlador->Listar_Tipo_Beneficiarios($query,$grupo));
}

if(isset($_GET['DCArticulos']))
{
	//$parametros = $_POST['parametros'];
	$marca = G_NINGUNO;
	$codmarca = G_NINGUNO;
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	if(isset($_GET['marca']) and $_GET['marca']!='')
	{
		$marca = $_GET['marca'];
	}
	if(isset($_GET['codMarca']) and $_GET['codMarca']!='')
	{
		$codmarca = $_GET['codMarca'];
	}
	echo json_encode($controlador->Listar_Productos($query,$codmarca,$marca));
}

if(isset($_GET['DCArticulo_LostFocus']))
{
	$parametros = $_POST['parametros'];	
	echo json_encode($controlador->DCArticulo_LostFocus($parametros));
}

if(isset($_GET['TextVUnit_LostFocus']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->TextVUnit_LostFocus($parametros));
}

if(isset($_GET['Tipo_De_Facturacion']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->Tipo_De_Facturacion($parametros));
}
if(isset($_GET['Eliminar_linea']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->delete_asientoF($parametros));
}



class facturarC
{
	private $modelo;

	public function __construct(){
        $this->modelo = new facturarM();	
    }

    function lineas_facturas()
    {
    	// $codigoCliente = $parametro['codigoCliente'];
    	$datos = $this->modelo->lineas_factura();
    	 $TextFacturaNo= Leer_Campo_Empresa("Mod_Fact");
       $Mod_PVP = Leer_Campo_Empresa("Mod_PVP");
       $DCEjecutivo = Leer_Campo_Empresa("Comision_Ejecutivo");
    	return array('tbl'=>$datos['tbl'],'TextFacturaNo'=>$TextFacturaNo,'Mod_PVP'=>$Mod_PVP,'DCEjecutivo'=>$DCEjecutivo);
    }

    function  DCMod(){
    	$datos = $this->modelo->DCMod();
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('codigo'=>$value['Codigo'],'nombre'=>$value['Detalle']);
    	}
    	return $lis;
    }  

    function DCLinea($parametros)
    {
    	$datos = $this->modelo->DCLinea($parametros['TC'],$parametros['Fecha']);
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('codigo'=>$value['Codigo'],'nombre'=>$value['Concepto']);
    	}
    	return $lis;
    }
    function DCTipoPago()
    {
    	$datos = $this->modelo->DCTipoPago();
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('codigo'=>$value['Codigo'],'nombre'=>$value['CTipoPago']);
    	}
    	return $lis;
    }      
    function DCGrupo_No($query)
    {
    	$datos = $this->modelo->DCGrupo_No($query);
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('id'=>$value['Grupo'],'text'=>$value['Grupo']);
    	}
    	return $lis;
    }
    function Listar_Tipo_Beneficiarios($query,$grupo)
    {
    	$datos = $this->modelo->Listar_Tipo_Beneficiarios($query,$grupo);
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('id'=>$value['Codigo'],'text'=>$value['Cliente']);
    	}
    	return $lis;
    }

    function DCBodega()
    {
    	$datos = $this->modelo->bodega();
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('codigo'=>$value['CodBod'],'nombre'=>$value['Bodega']);
    	}
    	return $lis;
    }
    function DCMarca()
    {
    	$datos = $this->modelo->DCMarca();
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('codigo'=>$value['CodMar'],'nombre'=>$value['Marca']);
    	}
    	return $lis;
    }

    function DCMedico()
    {
    	$datos = $this->modelo->DCMedico();
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('codigo'=>$value['CI_RUC'].'-'.$value['TD'].'-'.$value['Codigo'],'nombre'=>$value['Cliente']);
    	}
    	return $lis;
    }
    function DCEjecutivo()
    {
    	$datos = $this->modelo->DCEjecutivo();
    	$lis = array();
    	foreach ($datos as $key => $value) {
    		$lis[] =array('codigo'=>$value['Codigo'],'nombre'=>$value['Cliente']);
    	}
    	return $lis;
    }
    function PorCodigo()
    {
    	$res= ReadSetDataNum("PorCodigo", True, False);
    	return $res;
    }
    function CDesc1()
    {
    	$datos = $this->modelo->CDesc1();
    	$lis[] = array('codigo'=>0,'nombre'=>'00.00');
    	foreach ($datos as $key => $value) {
    		$lis[] =array('codigo'=>$value['ID'],'nombre'=>number_format($value['Interes'],2));
    	}
    	return $lis;

    }

    function Lineas_De_CxC($parametros)
    {
    	 $resp = Lineas_De_CxC($parametros);
    	 // $resp = 'ssss';
    	 return $resp;
    }
    function Tipo_De_Facturacion($parametros)
    {
    	print_r($_SESSION);die();
    	return array('Porc_Serv'=>$_SESSION['INGRESO']['porc'],'Autorizacion'=>123654789,'Serie'=>$_SESSION['INGRESO']['Serie_FA'] );

    }

    function Listar_Productos($query,$codmarca,$marca)
    {

    	$datos = $this->modelo->Listar_Productos($codmarca,$OpcServicio=false,$query,$marca);
    	foreach ($datos as $key => $value) {
    		$lis[] =array('id'=>$value['Codigo_Inv'],'text'=>$value['Producto']);
    	}
    	return $lis;
    }
    function DCArticulo_LostFocus($parametros)
    {
    	// print_r($_SESSION['INGRESO']);die();
    	$respuesta = Leer_Codigo_Inv($parametros['codigo'],$parametros['fecha'],$parametros['bodega'],$parametros['marca']);
    	if($respuesta['respueta']==1)
    	{
    		if(count($respuesta['datos']) > 0 ){
		        $Producto = $respuesta['datos']["Producto"];
		        $Cta_Ventas = $respuesta['datos']["Cta_Ventas"];		       
		        $TextVUnit= number_format($respuesta['datos']["PVP"],4,'.','');
		        $NumStrg = $TextVUnit;
		       if($respuesta['datos']["IVA"]){ $NumStrg = number_format($respuesta['datos']["PVP"] + ($respuesta['datos']["PVP"] * $_SESSION['INGRESO']["porc"]),$_SESSION['INGRESO']['Dec_Costo'],'.','');}
		       $LabelStockArt = " P R O D U C T O 	--- ".$_SESSION['INGRESO']['S_M']."   ".$NumStrg;
		       $VUnitAnterior = $respuesta['datos']["PVP"];
		       $LabelStock = $respuesta['datos']["Stock"];
		       $Codigos = $respuesta['datos']["Codigo_Inv"];
		       $CodigoInv1 = $respuesta['datos']["Codigo_Barra"];
		       $BanIVA = $respuesta['datos']["IVA"];
		       if($parametros["tipoFactura"] == "NV"){$BanIVA = False;}
		       $DCArticulo = $Producto;
		       $TextComEjec = "0";
		       // 'TxtDetalle.SetFocus
		       $TxtDetalle = $Producto;
		       if(strlen($respuesta['datos']["Detalle"]) > 3){ $TxtDetalle = $TxtDetalle.' '.$respuesta['datos']["Detalle"];}
		          $EsNumero = False;
		          if(is_numeric($respuesta['datos']["Codigo_Barra"])) {
		             if(intval($respuesta['datos']["Codigo_Barra"]) > 0){$EsNumero = True;}
		          }
		          if(strlen($respuesta['datos']["Codigo_Barra"]) > 1 && $EsNumero ){ $TxtDetalle = $TxtDetalle."S/N: ".$respuesta['datos']["Codigo_Barra"];}
		          $TxtDetalle_Visible = True;
		          // 'TxtDetalle.SetFocus
      		}
      		return $respuesta = array('codigos'=>$Codigos,'producto'=>$Producto,'cta_venta'=>$Cta_Ventas,'labelstock'=>$LabelStock,'baniva'=>$BanIVA,'TextVUnit'=>$TextVUnit,'VUnitAnterior'=>$VUnitAnterior,'CodigoInv1'=>$CodigoInv1,'LabelStockArt'=>$LabelStockArt,'TextComEjec'=>$TextComEjec,'TxtDetalle'=>$TxtDetalle);

    	}else
    	{
    		return $respuesta;
    	}
    }

 function LstOrden()
 {
 	$datos = $this->modelo->LstOrden();
 	$op = '';
 	if(count($datos)>0)
 	{
 		foreach ($datos as $key => $value) {
 			$op.="<option>Lote No. ".$value['Lote_No']."</option>";
 		}
 	}
 	return $op;
 }

 function numero_factura()
 {
 	// print_r($_SESSION['INGRESO']);die();
 	$TextFacturaNo = Leer_Campo_Empresa("Mod_Fact");
   $Mod_PVP = Leer_Campo_Empresa("Mod_PVP");
   $CheqSP =  Leer_Campo_Empresa("SP");
    if(Leer_Campo_Empresa("Comision_Ejecutivo")){$CheqEjec = True; }else{ $CheqEjec= False;}
    if($_SESSION['INGRESO']['Nombre'] == "Administrador de Red"){
      $command4 = True;
      $TextFacturaNo = True;
   }
   $Total_Desc = 0;
   $Ln_No = 0;
   return array('TextFacturaNo'=>$TextFacturaNo,'Mod_PVP'=>$Mod_PVP,'CheqEjec'=>$CheqEjec,'Command4'=>$command4,'Total_Desc'=>$Total_Desc,'Ln_No'=>$Ln_No,'CheqSP'=>$CheqSP);
 }

function TextVUnit_LostFocus($parametros)
{
   if($parametros['Mod_PVP']==0){$TextVUnit = $parametro['TextVUnit'];}
   if($parametros['DatInv_Serie_No']== ""){$DatInv_Serie_No = G_NINGUNO;}
	//   'MsgBox TipoFactura & vbCrLf & BanIVA
   $Factura_No =$parametros['TextFacturaNo'];
   $TextVUnit = TextoValido($parametros['TextVUnit'],true,false,$_SESSION['INGRESO']['Dec_PVP']);
   $TextCant = TextoValido($parametros['TextCant'],true);
  // 'TextoValido TextDesc1, True
   $SubTotal = 0; $SubTotalDescuento = 0; $SubTotalIVA = 0; $SubTotalPorcComision = 0;
   $NumMeses = 0; $VUnitTemp = 0; $Interes = 0;
   $datosL = $this->modelo->lineas_factura();
     	// print_r($parametros);die();
   if(count($datosL['datos'])<=$parametros['Cant_Item_FA'])
   {
   	  if($parametros['TxtDetalle'] <> G_NINGUNO){$Producto = $parametros['TxtDetalle'];}
        // TxtDetalle.Visible = False //revision
       // 'Porcentaje por ejecutivo
        if(intval($parametros['TextComision']) > 0){$SubTotalPorcComision = number_format(intval($TextComision) / 100, 2,'.','');}
       // 'SubTotal por producto
        $SubTotal = number_format(floatval($parametros['TextCant']) * floatval($parametros['TextVUnit']), 2,'.','');
        if($VUnitTemp > 0){ $SubTotal = number_format($VUnitTemp, 2,'.','');}
       // 'Descuento
        $SubTotalDescuento = number_format($SubTotal * (number_format(intval($parametros['CDesc1']), 2,'.','') / 100), 2,'.','');
       // 'IVA = SubTotal - Descuento
        if($parametros['BanIVA'] && $parametros['tipoFactura'] <> "NV"){$SubTotalIVA = number_format(($SubTotal - $SubTotalDescuento) * $_SESSION['INGRESO']['porc'], 4,'.','');}

       // 'If TipoFactura = "OP" Then SubTotalIVA = 0
        if(floatval($parametros['TextVUnit']) == 0){$SubTotalIVA = 0;}
        // LabelVTotal.Caption = Format$(SubTotal, "#,##0.00")
			// '''        If CheqCom.value = 1 Then FComision.Show 1
       // 'MsgBox Redondear(CDbl(TextVUnit), Dec_PVP) & " ..." & Redondear(Val(TextVUnit), Dec_PVP)

        	// print_r($parametros);die();
        $Ln_No=count($datosL['datos'])+1; 

   	if(strlen($parametros['codigo']) > 1 )
   	{
   		$DatInv = $this->modelo->Listar_Productos_all($PatronDeBusqueda=false,$parametros['codigo']);
           // SetAdoAddNew "Asiento_F"
           $datos[0]['campo'] = "CODIGO"; 
           $datos[0]['dato'] = $parametros['codigo'];
           $datos[1]['campo'] = "CODIGO_L"; 
           $datos[1]['dato'] = $parametros['CodigoL'];
           $datos[2]['campo'] = "PRODUCTO"; 
           $datos[2]['dato'] = $Producto;
           $datos[3]['campo'] = "REP"; 
           $datos[3]['dato'] = 0;
           $datos[4]['campo'] = "CANT"; 
           $datos[4]['dato'] = $parametros['TextCant'];
           $datos[5]['campo'] = "PRECIO"; 
           $datos[5]['dato'] = number_format($parametros['TextVUnit'],$_SESSION['INGRESO']['Dec_PVP'],'.','');
           $datos[6]['campo'] = "TOTAL"; 
           $datos[6]['dato'] = $SubTotal;
           $datos[7]['campo'] = "VALOR_TOTAL"; 
           $datos[7]['dato'] = $SubTotal - $SubTotalDescuento + $SubTotalIVA;
           $datos[8]['campo'] = "Total_Desc"; 
           $datos[8]['dato'] = $SubTotalDescuento;
           $datos[9]['campo'] = "Total_IVA"; 
           $datos[9]['dato'] = $SubTotalIVA;
           $datos[10]['campo'] = "Cta"; 
           $datos[10]['dato'] = $DatInv[0]['Cta_Ventas'];
           $datos[11]['campo'] = "Cta_SubMod"; 
           $datos[11]['dato'] = $parametros['SubCta'];
           $datos[12]['campo'] = "CodBod"; 
           $datos[12]['dato'] = $parametros['bodega'];
           $datos[13]['campo'] = "CodMar"; 
           $datos[13]['dato'] = $parametros['marca'];
           $datos[14]['campo'] = "COD_BAR"; 
           $datos[14]['dato'] = $DatInv[0]['Codigo_Barra'];
           $datos[15]['campo'] = "Item"; 
           $datos[15]['dato'] = $_SESSION['INGRESO']['item'];
           $datos[16]['campo'] = "CodigoU"; 
           $datos[16]['dato'] = $_SESSION['INGRESO']['CodigoU'];
           $datos[17]['campo'] = "CORTE"; 
           $datos[17]['dato'] = $VUnitTemp;
           $datos[18]['campo'] = "A_No"; 
           $datos[18]['dato'] = $Ln_No;
           $datos[19]['campo'] = "Fecha_V"; 
           $datos[19]['dato'] = $parametros['fechaVGR'];
           $datos[20]['campo'] = "Cod_Ejec"; 
           $datos[20]['dato'] = $parametros['Cod_Ejec'];
           $datos[21]['campo'] = "Porc_C"; 
           $datos[21]['dato'] = $SubTotalPorcComision;
           $datos[22]['campo'] = "Serie_No"; 
           $datos[22]['dato'] = $DatInv_Serie_No;
           $datos[23]['campo'] = "COSTO"; 
           $datos[23]['dato'] = $DatInv[0]['Costo'];
           $pos = count($datos);           
           if(strlen($parametros['TextComEjec']) > 1){ $pos = $pos+1; $datos[$pos]['campo'] = "RUTA";$datos[$pos]['dato'] =$parametros['TextComEjec'];}
           if($DatInv[0]['Por_Reservas']){
           	  $pos = $pos+1;
              $datos[$pos]['campo'] = "Fecha_IN"; 
              $datos[$pos]['dato'] = $parametros['MBFechaIn'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Fecha_OUT";
              $datos[$pos]['dato'] = $parametros['MBFechaOut'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Cant_Hab"; 
              $datos[$pos]['dato'] = $parametros['TxtCantRooms'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Tipo_Hab"; 
              $datos[$pos]['dato'] = $parametros['TxtTipoRooms'];
           }
           if(strlen($parametros['LstOrden']) > 1){
           	  $pos = $pos+1;
              $datos[$pos]['campo'] = "Lote_No"; 
              $datos[$pos]['dato'] = $parametros['LstOrden'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Fecha_Fab"; 
              $datos[$pos]['dato'] = $DatInv[0]['Fecha_Fab'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Fecha_Exp"; 
              $datos[$pos]['dato'] = $DatInv[0]['Fecha_Exp'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Reg_Sanitario"; 
              $datos[$pos]['dato'] = $DatInv[0]['Reg_Sanitario'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Procedencia"; 
              $datos[$pos]['dato'] = $DatInv[0]['Procedencia'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Modelo"; 
              $datos[$pos]['dato'] = $DatInv[0]['Modelo'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "SP"; 
              $datos[$pos]['dato'] = 0;
              if($parametros['Sec_Public']==true)
              {
                $datos[$pos]['dato'] = 1;
              }
           }
           if($DatInv[0]['Costo'] > 0){
           	  $pos = $pos+1;
              $datos[$pos]['campo'] = "Cta_Inv"; 
              $datos[$pos]['dato'] = $DatInv[0]['Cta_Inventario'];
              $pos = $pos+1;
              $datos[$pos]['campo'] = "Cta_Costo"; 
              $datos[$pos]['dato'] = $DatInv[0]['Cta_Costo_Venta'];
           }
           // print_r($datos);die();
          if(insert_generico('Asiento_F',$datos)==null)
          {
          	return 1;
          }
      }
      else{

          return $MsgBox = "No ha seleccionado el codigo correcto, vuelva a ingresar";
      }
    }else{
       return $MsgBox = "Ya no se puede ingresar más datos.";
    }





//    sSQL = "SELECT * " _
//         & "FROM Asiento_F " _
//         & "WHERE Item = '" & NumEmpresa & "' " _
//         & "AND CodigoU = '" & CodigoUsuario & "' " _
//         & "ORDER BY A_No "
//    SQLDec = "PRECIO " & CStr(Dec_PVP) & "|CORTE " & CStr(Dec_PVP) & "|Total_IVA 4|."
//    SelectDataGrid DGAsientoF, AdoAsientoF, sSQL, SQLDec
//    Calculos_Totales_Factura FA
//    LabelSubTotal.Caption = Format$(FA.Sin_IVA, "#,##0.00")
//    LabelConIVA.Caption = Format$(FA.Con_IVA, "#,##0.00")
//    TextDesc.Text = Format$(FA.Descuento, "#,##0.00")
//    LabelServ.Caption = Format$(FA.Servicio, "#,##0.00")
//    LabelIVA.Caption = Format$(FA.Total_IVA, "#,##0.00")
//    LabelTotal.Caption = Format$(FA.Total_MN, "#,##0.00")
//    DGAsientoF.Visible = True
//    TextCant.Text = ""
//    LabelVTotal.Caption = ""
//    MarcarTexto TextDesc
//    If (Redondear(CDbl(TextVUnit), Dec_PVP) < DatInv.Costo) And (DatInv.Costo > 0 And Len(DatInv.Cta_Inventario) > 3) Then
//       MsgBox "Usted esta vendiendo por debajo del Costo de Produccion"
//    End If
//    DCArticulo.SetFocus
}

function delete_asientoF($parametros)
{
	$ln_No = $parametros['ln_No'];
	return $this->modelo->delete_asientoF($ln_No);
}
    
}
?>