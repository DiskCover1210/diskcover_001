<?php 
include('../../modelo/inventario/kardex_ingM.php');
/**
 * 
 */
$controlador =  new kardex_ingC();
if(isset($_GET['iniciar_aseinto']))
{
	echo  json_encode($controlador->IniciarAsientosAdo($_POST['Trans_No']));
}
if(isset($_GET['familias']))
{
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}

	echo  json_encode($controlador->familias($_GET['q']));
}

if(isset($_GET['producto']))
{
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo  json_encode($controlador->producto($_GET['fami'],$_GET['q']));
}
if(isset($_GET['contracuenta']))
{
  if(!isset($_GET['q']))
  {
    $_GET['q'] =''; 
  }
  echo  json_encode($controlador->contracuenta($_GET['q']));
}
if(isset($_GET['ListarProveedorUsuario']))
{
  if(!isset($_GET['q']))
  {
    $_GET['q'] =''; 
  }
  echo  json_encode($controlador->ListarProveedorUsuario($_GET['cta'],$_GET['contra'],$_GET['q']));
}

if(isset($_GET['leercuenta']))
{
  echo  json_encode($controlador->LeerCta($_POST['parametros']));
}
if(isset($_GET['Trans_Kardex']))
{
  echo  json_encode($controlador->Trans_Kardex());
}
if(isset($_GET['bodega']))
{
  echo  json_encode($controlador->bodega());
}
if(isset($_GET['marca']))
{
  echo  json_encode($controlador->marca());
}
if(isset($_GET['detalle_articulos']))
{
  $parametros = $_POST['parametros'];
  echo  json_encode($controlador->producto_detalle($parametros));
}
if(isset($_GET['DCRetIBienes']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetIBienes());
}
if(isset($_GET['DCRetISer']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetISer());
}
if(isset($_GET['DCSustento']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCSustento($parametros));
}
if(isset($_GET['DCDctoModif']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCDctoModif());
}
if(isset($_GET['DCPorcenIva']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCPorcenIva($parametros));
}
if(isset($_GET['DCPorcenIce']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCPorcenIce($parametros));
}
if(isset($_GET['DCTipoPago']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCTipoPago());
}

if(isset($_GET['DCRetFuente']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCRetFuente());
}
if(isset($_GET['DCConceptoRet']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCConceptoRet($parametros));
}
if(isset($_GET['DCPais']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->DCPais());
}
if(isset($_GET['Carga_RetencionIvaBienes_Servicios']))
{
  // $parametros = $_POST['DCRetIBienes'];
  echo  json_encode($controlador->Carga_RetencionIvaBienes_Servicios());
}

if(isset($_GET['DCTipoComprobante']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCTipoComprobante($parametros));
}

if(isset($_GET['DCBenef_Data']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->DCBenef_Data($parametros));
}
if(isset($_GET['grabacion']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->grabacion($parametros));
}
if(isset($_GET['Insertar_DataGrid']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Insertar_DataGrid($parametros));
}
if(isset($_GET['Cargar_DataGrid']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Cargar_DataGrid($parametros['Trans_No']));
}

if(isset($_GET['Ult_fact_Prove']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Ult_fact_Prove($parametros));
}

if(isset($_GET['cancelar']))
{
   $parametros = $_POST['Trans_No'];
  echo  json_encode($controlador->cancelar($parametros));
}

if(isset($_GET['Documento_Modificado']))
{
   $parametros = $_POST['parametros'];
  echo  json_encode($controlador->Documento_Modificado($parametros));
}


class kardex_ingC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new kardex_ingM();
	}

	function familias($query)
	{
		$datos = $this->modelo->familias($query);
		return $datos;
	}
	function producto($fami,$query)
	{
	 	$opciones = $this->ReadSetDataNum("PorCodigo", True, False); 
		$datos = $this->modelo->Producto($fami,$query,$opciones);
		return $datos;
	}
  function producto_detalle($parametros)
  {
    $opciones = $this->ReadSetDataNum("PorCodigo", True, False); 
    $CodigoInv ='';
    $porc_iva=0;
    $fami = $parametros['fami'];
    $evaluar = False;
    if($opciones==1) {
      $CodigoInv=$parametros['nom'];
    }else
    {
      $CodigoInv=$parametros['arti'];
    }

    $datos = $this->modelo->producto_detalle($fami,$CodigoInv,'','','',$opciones);
    if(count($datos)>0)
    {
       $CodigoInv = $datos[0]["Codigo_Inv"];
       $evaluar = True;
    }else
    {
      $datos = $this->modelo->producto_detalle($fami,'',$CodigoInv,'','',$opciones);
      if (count($datos)>0) 
      {
         $CodigoInv = $datos[0]["Codigo_Inv"];
         $evaluar = True;        
      }else
      {
         $datos = $this->modelo->producto_detalle($fami,'','',$CodigoInv,'',$opciones);
         //print_r($datos);die();
         if (count($datos)>0) 
         {

          $CodigoInv = $datos["Codigo_Inv"];
          $evaluar = True;
          
         }else
         {
           $evaluar = False;
         }

      }
    }
     $datos1 = $this->modelo->producto_detalle($fami,'','','',$CodigoInv,$opciones);
     $iva = $this->modelo->Tabla_Por_ICE_IVA();
     if(count($iva)>0)
     {
      $porc_iva = ($iva[0]['Porc']/100);
     }
     $datos_art = array();
     if (count($datos1)>0) {
      // print_r($datos1);die();
       $datos_art = array('si_no' =>$datos1['IVA'] ,'unidad'=>$datos1['Unidad'],'producto'=>$datos1['Producto'],'cta_inventario'=>$datos1['Cta_Inventario'],'contra_cta1'=>$datos1['Cta_Costo_Venta'],'registrosani'=>$datos1['Reg_Sanitario'],'codigo'=>$datos['Codigo_Inv']);
     }
    return $datos_art;
  }
  function contracuenta($query)
  {
    $datos = $this->modelo->contracuenta($query);
    return $datos;
  }
   function ListarProveedorUsuario($cta,$contra,$query)
  {
    $datos = $this->modelo->ListarProveedorUsuario($cta,$contra,$query);
    return $datos;
  }

  function Trans_Kardex()
  {
    $datos = $this->modelo->Trans_Kardex();
    return $datos;
  }
   function bodega()
  {
    $datos = $this->modelo->bodega();
    return $datos;
  }
   function marca()
  {
    $datos = $this->modelo->marca();
    return $datos;
  }

  function leerCta($parametros)
  {
    $CodigoCta = $parametros['cuenta'];
   $cta = $this->modelo->LeerCta($CodigoCta);
    $datos = array();
    if(count($cta)>0)
    {
      $tipo='';
      if($cta[0]['Tipo_Pago']<=0)
      {
        $tipo = '01';
      }else
      {
        $tipo = $cta[0]['Tipo_Pago'];
      }
      $datos = array('Codigo' =>$cta[0]['Codigo'] ,'Cuenta' =>$cta[0]['Cuenta'] ,'SubCta' =>$cta[0]['TC'] ,'Moneda_US' =>$cta[0]['ME'] ,'TipoCta' =>$cta[0]['DG'] ,'TipoPago' => $tipo);
    }    
  
    return $datos;
  }

	function IniciarAsientosAdo($Trans_No)
	{
		if($Trans_No <=0)
		{
			$Trans_No=1;
		}
		$this->modelo->borrar_asientos($Trans_No);

		$this->modelo->dtaAsiento_sc($Trans_No);
		$this->modelo->dtaAsiento_b($Trans_No);
		$this->modelo->dtaAsiento_air($Trans_No);
		$this->modelo->dtaAsiento_compras($Trans_No);
		$this->modelo->dtaAsiento_ventas($Trans_No);
		$this->modelo->dtaAsiento_impo($Trans_No);
		$this->modelo->dtaAsiento_expo($Trans_No);
		$this->modelo->dtaAsiento_k($Trans_No);
		$this->modelo->dtaAsiento($Trans_No);

	}
	function ReadSetDataNum($sql,$ParaEmpresa,$Incrementar,$FechaComp="00/00/0000")
	{
		$empresa = $this->modelo->dato_empresa();
		$Num_Meses_CD = boolval($empresa[0]['Num_CD']) ? True : False;
		$Num_Meses_CI = boolval($empresa[0]['Num_CI']) ? True : False;
		$Num_Meses_CE = boolval($empresa[0]['Num_CE']) ? True : False;
		$Num_Meses_ND = boolval($empresa[0]['Num_ND']) ? True : False;
		$Num_Meses_NC = boolval($empresa[0]['Num_CD']) ? True : False;

    $NumCodigo = 0;
    $NuevoNumero = False;
    if (strlen($FechaComp) < 10 ){ $FechaComp = date('Y-m-d');}
    if($FechaComp = "00/00/0000"){$FechaComp = date('Y-m-d');}
    $Si_MesComp = False;
    if($ParaEmpresa==True){$NumEmpA = $_SESSION['INGRESO']['item'];}else { $NumEmpA = "000";}
    
    // HoraDelSistema = Second(Time)
    // HoraDelSistema = Int((HoraDelSistema * Rnd) + 1)
    // If HoraDelSistema < 6 Then HoraDelSistema = 6
    // Sleep HoraDelSistema
    
    if ($sql <> ""){
       $MesComp = "";
       if(strlen($FechaComp) >= 10){ $MesComp = date('m',strtotime($FechaComp));}
       if($MesComp = "" ){$MesComp = "01";}
       if($Num_Meses_CD && $sql == "Diario"){
          $sql= $MesComp.$sql;
          $Si_MesComp = True;
       }
       if($Num_Meses_CI &&  $sql == "Ingresos"){
          $sql = $MesComp.$sql;
          $Si_MesComp = True;
       }
       if($Num_Meses_CE && $sql == "Egresos"){
          $sql= $MesComp.$sql;
          $Si_MesComp = True;
       }
       if($Num_Meses_ND && $sql == "NotaDebito" ){
          $sql = $MesComp.$sql;
          $Si_MesComp = True;
       }
       if($Num_Meses_NC && $sql == "NotaCredito"){
          $sql = $MesComp.$sql;
          $Si_MesComp = True;
       }
          
        $datos =  $this->modelo->codigos($sql); 
        // print_r($datos);die();
       if(count($datos)>0)
       {
       	$NumCodigo = $datos[0]['Numero'];
       }else
       {
       	$NuevoNumero = True;
       	$NumCodigo = 1;
       	 if($Num_Meses_CD && $Si_MesComp){$NumCodigo = strval($MesComp."000001");}
         if($Num_Meses_CI && $Si_MesComp){$NumCodigo = strval($MesComp."000001");}
         if($Num_Meses_CE && $Si_MesComp){$NumCodigo = strval($MesComp."000001");}
         if($Num_Meses_ND && $Si_MesComp){$NumCodigo = strval($MesComp."000001");}
         if($Num_Meses_NC && $Si_MesComp){$NumCodigo = strval($MesComp."000001");}

       }
    }
    if($NumCodigo>0)
    {
    	if ($NuevoNumero) {
    		$this->modelo->ingresar_codigo($NumEmpA,$sql,$NumCodigo);
    	}
    	if ($Incrementar) {
    		$this->modelo->ingresar_codigo($NumEmpA,$sql);
    	}
    }
    // print_r($NumCodigo);die();
    return $NumCodigo;
	}
  function DCRetIBienes()
  {
    $datos = $this->modelo->DCRetIBienes();
    // print_r($datos);die();
    return $datos;
  }
  function DCRetISer()
  {
    $datos = $this->modelo->DCRetISer();
     // print_r($datos);die();
    return $datos;
  }
   function DCSustento($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCSustento($fecha);
     // print_r($datos);die();
    return $datos;
  }
   function DCDctoModif()
  {
    $datos = $this->modelo->DCDctoModif();
     // print_r($datos);die();
    return $datos;
  }
   function DCPorcenIva($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCPorcenIva($fecha);
     // print_r($datos);die();
    return $datos;
  }
  function DCPorcenIce($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCPorcenIce($fecha);
     // print_r($datos);die();
    return $datos;
  }
   function DCTipoPago()
  {
    $datos = $this->modelo->DCTipoPago();
     // print_r($datos);die();
    return $datos;
  }
     function DCRetFuente()
  {
    $datos = $this->modelo->DCRetFuente();
     // print_r($datos);die();
    return $datos;
  }
  function DCConceptoRet($parametros)
  {
    $fecha = $parametros['fecha'];
    $datos = $this->modelo->DCConceptoRet($fecha);
     // print_r($datos);die();
    return $datos;
  }
  function DCPais()
  {
    $datos = $this->modelo->DCPais();
     // print_r($datos);die();
    return $datos;
  }
   function DCTipoComprobante($parametros)
  {
    // print_r($parametros);die();
    $cadena = '';
    $datos = $this->modelo->DCSustento($parametros['fecha']);
    if(count($datos)>0)
    {
      $datos = $this->modelo->DCSustento($parametros['fecha'],$parametros['DCSustento']);
      if(count($datos)>0)
      {
         $cadena = $datos[0]['Codigo_Tipo_Comprobante'];
         $cadena = str_replace(' ',',',$cadena);
      }
    }
    // print_r($cadena);die();
    $datos = $this->modelo->DCTipoComprobante($cadena,$parametros['TipoBenef']);
     // print_r($datos);die();
    return $datos;
  }
     
  function DCBenef_Data($parametros)
  {
    // print_r($parametros);
    $datos = $this->modelo->ListarProveedorUsuario($parametros['cta'],$parametros['contra'],$parametros['DCBenef']);
    if(count($datos)>0)
    {
    if($datos[0]['tipodoc']=='R')
    {
       $datos = array_merge($datos[0], array('si_no'=>FALSE));
     // array_push($datos, array('si_no'=>FALSE ));
    }
  }else
  {
     $datos = array_merge($datos[0], array('si_no'=>FALSE));
     // array_push($datos,array('si_no' =>FALSE));
  }
  
   // print_r($datos);die();
    return $datos;
  }

  function Carga_RetencionIvaBienes_Servicios()
  {
    $datos = $this->modelo->Carga_RetencionIvaBienes_Servicios();
    // print_r($datos);die();
    return $datos;
  }
  function grabacion($parametros)
  {
    $datos[0]['campo']="IdProv";
    $datos[1]['campo']="DevIva";
    $datos[2]['campo']="CodSustento"; 
    $datos[3]['campo']="TipoComprobante" ;
    $datos[4]['campo']="Establecimiento"; 
    $datos[5]['campo']="PuntoEmision"; 
    $datos[6]['campo']="Secuencial"; //CTNumero
    $datos[7]['campo']="Autorizacion"; 
    $datos[8]['campo']="FechaEmision"; 
    $datos[9]['campo']="FechaRegistro"; 
    $datos[10]['campo']="FechaCaducidad"; 
    $datos[11]['campo']="BaseNoObjIVA"; //CTNumero 2 decimales
    $datos[12]['campo']="BaseImponible"; //CTNumero 2 decimales
    $datos[13]['campo']="BaseImpGrav"; //CTNumero 2 decimales
    $datos[14]['campo']="PorcentajeIva"; 
    $datos[15]['campo']="MontoIva"; //CTNumero 2 decimales
    $datos[16]['campo']="BaseImpIce"; //CTNumero 2 decimales
    $datos[17]['campo']="PorcentajeIce";
    $datos[18]['campo']="MontoIce"; //CTNumero 2 decimales
    $datos[19]['campo']="Porc_Bienes";
    $datos[20]['campo']="MontoIvaBienes"; //CTNumero 2 decimales
    $datos[21]['campo']="PorRetBienes";                  //ojo la varable puedee cambiar
    $datos[22]['campo']="ValorRetBienes"; //CTNumero 2 decimales
    $datos[23]['campo']="Porc_Servicios";
    $datos[24]['campo']="MontoIvaServicios"; //CTNumero 2 decimales
    $datos[25]['campo']="PorRetServicios";                //ojo la varable puedee cambiar
    $datos[26]['campo']="ValorRetServicios";

    $datos[0]['dato']=$parametros["IdProv"];
    $datos[1]['dato']=$parametros["DevIva"];
    $datos[2]['dato']=$parametros["CodSustento"]; 
    $datos[3]['dato']=$parametros["TipoComprobante"]; 
    $datos[4]['dato']=$parametros["Establecimiento"]; 
    $datos[5]['dato']=$parametros["PuntoEmision"]; 
    $datos[6]['dato']=$parametros["Secuencial"]; //CTNumero
    $datos[7]['dato']=$parametros["Autorizacion"]; 
    $datos[8]['dato']=$parametros["FechaEmision"]; 
    $datos[9]['dato']=$parametros["FechaRegistro" ];
    $datos[10]['dato']=$parametros["FechaCaducidad"]; 
    $datos[11]['dato']=  round($parametros["BaseNoObjIVA"],2, PHP_ROUND_HALF_ODD);
    $datos[12]['dato']=  round($parametros["BaseImponible"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[13]['dato']=  round($parametros["BaseImpGrav"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[14]['dato']=$parametros["PorcentajeIva"]; 
    $datos[15]['dato']=  round($parametros["MontoIva"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[16]['dato']=  round($parametros["BaseImpIce"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[17]['dato']=$parametros["PorcentajeIce"];
    $datos[18]['dato']=  round($parametros["MontoIce"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[19]['dato']=$parametros["Porc_Bienes"];
    $datos[20]['dato']=  round($parametros["MontoIvaBienes"] ,2, PHP_ROUND_HALF_ODD);//CTNumero 2 decimales
    $datos[21]['dato']=$parametros["PorRetBienes"];                  //ojo la varable puedee cambiar
    $datos[22]['dato']=  round($parametros["ValorRetBienes"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[23]['dato']=$parametros["Porc_Servicios"];
    $datos[24]['dato']=  round($parametros["MontoIvaServicios"],2, PHP_ROUND_HALF_ODD); //CTNumero 2 decimales
    $datos[25]['dato']=$parametros["PorRetServicios"];                //ojo la varable puedee cambiar
    $datos[26]['dato']=$parametros["ValorRetServicios"];

     $datos[27]['campo']= "DocModificado";
     $datos[28]['campo']= "FechaEmiModificado";
     $datos[29]['campo']= "EstabModificado"; 
     $datos[30]['campo']= "PtoEmiModificado"; 
     $datos[31]['campo']= "SecModificado";
     $datos[32]['campo']= "AutModificado";


     $datos[33]['campo']= "ContratoPartidoPolitico";
     $datos[34]['campo']= "MontoTituloOneroso";
     $datos[35]['campo']= "MontoTituloGratuito";

    $datos[36]['campo']= "PagoLocExt";
    $datos[37]['campo']="PaisEfecPago";
    $datos[38]['campo']= "AplicConvDobTrib";
    $datos[39]['campo']= "PagExtSujRetNorLeg";
    $datos[40]['campo']= "FormaPago";
    $datos[41]['campo']= "A_No";
    $datos[42]['campo']= "T_No";
    $datos[43]['campo']= "CodigoU";


     if($parametros['TipoComprobante']=='5' || $parametros['TipoComprobante']==4)
     {
       $datos[27]['dato']=$parametros['DocModificado'];
       $datos[28]['dato']=$parametros['FechaEmiModificado'];
       $datos[29]['dato']=$parametros['EstabModificado'];
       $datos[30]['dato']=$parametros['PtoEmiModificado'];
       $datos[31]['dato']=$parametros['SecModificado'];
       $datos[32]['dato']=$parametros['AutModificado'];

     }else
     {
       $datos[27]['dato']= "0";
       $datos[28]['dato']= date('Y-m-d');
       $datos[29]['dato']= "000";
       $datos[30]['dato']= "000";
       $datos[31]['dato']= "0000000";
       $datos[32]['dato']= "0000000000";

     }
     if($parametros['ContratoPartidoPolitico']=="")
     {
        $datos[33]['dato']="0000000000";
     }else
     {
       $datos[33]['dato']=$parametros['ContratoPartidoPolitico'];
     }
      $datos[34]['dato']=round($parametros["MontoTituloOneroso"],2, PHP_ROUND_HALF_ODD);
      $datos[35]['dato']=round($parametros["MontoTituloGratuito"],2, PHP_ROUND_HALF_ODD);

      if($parametros['CFormaPago']==2)
      {
        $datos[36]['dato']= "02";
        $datos[37]['dato']=$parametros['DCPais'];
        $datos[38]['dato']= $parametros['OpcSiAplicaDoble'];
        $datos[39]['dato']= $parametros['OpcSiFormaLegal'];

      }else
      {
        $datos[36]['dato']= "01";
        $datos[37]['dato']="NA";
        $datos[38]['dato']= "NA";
        $datos[39]['dato']= "NA";

      }
    
   
    $datos[40]['dato']= $parametros["FormaPago"];
    $datos[41]['dato']= "1";
    $datos[42]['dato']= 1; // verificar
    $datos[43]['dato']= $_SESSION['INGRESO']['CodigoU'];


    if(insert_generico("Asiento_Compras",$datos)==null)
    {
      print_r('expression');die();

    }
  }

  function Insertar_DataGrid($parametros)
  {
    // print_r($parametros);die();
     if($parametros['BaseImp']=='')
     {
       $parametros['BaseImp'] = 0;
     }
     if($parametros['BaseImp']>0)
     {
       $datos[0]['campo']= "CodRet";
       $datos[1]['campo']= "Detalle";
       $datos[2]['campo']= "BaseImp";
       $datos[3]['campo']= "Porcentaje";
       $datos[4]['campo']= "ValRet";
       $datos[5]['campo']= "EstabRetencion";
       $datos[6]['campo']= "PtoEmiRetencion";
       $datos[7]['campo']= "SecRetencion";
       $datos[8]['campo']= "AutRetencion";
       $datos[9]['campo']= "FechaEmiRet";
       $datos[10]['campo']= "Cta_Retencion";
       $datos[11]['campo']= "EstabFactura";
       $datos[12]['campo']= "PuntoEmiFactura";
       $datos[13]['campo']= "Factura_No";
       $datos[14]['campo']= "IdProv";
       $datos[15]['campo']= "A_No";
       $datos[16]['campo']= "T_No";
       $datos[17]['campo']="Tipo_Trans";
       $datos[18]['campo']="CodigoU";
       $datos[19]['campo']="Item";

       $datos[0]['dato']= $parametros["CodRet"];
       $datos[1]['dato']= $parametros["Detalle"];
       $datos[2]['dato']=round($parametros["BaseImp"],2, PHP_ROUND_HALF_ODD);
       $datos[3]['dato']=round( $parametros["Porcentaje"],2, PHP_ROUND_HALF_ODD) / 100;
       $datos[4]['dato']= round($parametros["ValRet"],2, PHP_ROUND_HALF_ODD);
       $datos[5]['dato']= $parametros["EstabRetencion"];
       $datos[6]['dato']= $parametros["PtoEmiRetencion"];
       $datos[7]['dato']= $parametros["SecRetencion"];
       $datos[8]['dato']= $parametros["AutRetencion"];
       $datos[9]['dato']= $parametros["FechaEmiRet"];
       $datos[10]['dato']= $parametros["Cta_Retencion"];
       $datos[11]['dato']= $parametros["EstabFactura"];
       $datos[12]['dato']= $parametros["PuntoEmiFactura"];
       $datos[13]['dato']= $parametros["Factura_No"];
       $datos[14]['dato']= $parametros["IdProv"];
       $datos[15]['dato']= $this->modelo->Maximo_De("Asiento_Air", "A_No");    
       $datos[16]['dato']= "1"; //ojo cambia
       $datos[17]['dato']= "C";
       $datos[18]['dato']=$_SESSION['INGRESO']['CodigoU'];
       $datos[19]['dato']=$_SESSION['INGRESO']['item'];
        if(insert_generico("Asiento_Air",$datos)==null)
          {
           return 1;
          }

     }else
     {
      return -1;
     } 
     
   
  }

  function Cargar_DataGrid($Trans_No)
  {
    $datos = $this->modelo->Cargar_DataGrid($Trans_No);
    $html = '';
    foreach ($datos as $key => $value) {
      $html.='<tr>
                <td>'.$value["CodRet"].'</td>
                <td>'.$value["Detalle"].'</td>
                <td>'.round($value["BaseImp"],2, PHP_ROUND_HALF_ODD).'</td>
                <td>'.round($value["Porcentaje"],2, PHP_ROUND_HALF_ODD).'</td>
                <td>'.round($value["ValRet"],2, PHP_ROUND_HALF_ODD).'</td>
                <td>'.$value["EstabRetencion"].'</td>
                <td>'.$value["PtoEmiRetencion"].'</td>
                <td>'.$value["SecRetencion"].'</td>
                <td>'.$value["AutRetencion"].'</td>
                <td>'.$value["FechaEmiRet"]->format('Y-m-d').'</td>
                <td>'.$value["Cta_Retencion"].'</td>
                <td>'.$value["EstabFactura"].'</td>
                <td>'.$value["PuntoEmiFactura"].'</td>
                <td>'.$value["Factura_No"].'</td>
                <td>'.$value["IdProv"].'</td>
                <td>'.$value["Item"].'</td>
                <td>'.$value["CodigoU"].'</td>
                <td>'.$value["A_No"].'</td>
                <td>'.$value["T_No"].'</td>
                <td>'.$value["Tipo_Trans"].'</td>              
              </tr>';
    }
    return $html;
  }

  function Ult_fact_Prove($parametros)
  {
    $datos = $this->modelo->Ult_fact_Prove($parametros['proveedor']);
    // print_r($datos);die();
    if(count($datos)>0)
    {
      $fact = array('secu'=>$datos[0]['Secuencial']+1,'fech_cad'=>$datos[0]['FechaCaducidad']->format('Y-m-d'),'esta'=>$datos[0]['Establecimiento'],'punto'=>$datos[0]['PuntoEmision'],'auto'=>$datos[0]['Autorizacion']);
      return $fact;
       // TxtNumSerietres = AdoAux.Recordset.Fields("Secuencial") + 1
      // MBFechaCad = AdoAux.Recordset.Fields("FechaCaducidad")
      // TxtNumSerieUno = AdoAux.Recordset.Fields("Establecimiento")
      // TxtNumSerieDos = AdoAux.Recordset.Fields("PuntoEmision")
      // TxtNumAutor = AdoAux.Recordset.Fields("Autorizacion")
    }else
    {
       $fact = array('secu'=>'0000001','fech_cad'=>date('Y-m-d'),'esta'=>'001','punto'=>'001','auto'=>'00000001');
      return $fact;

    }
  }

  function cancelar($Trans_No)
  {
    $res = $this->modelo->cancelar($Trans_No);
    return $res;
  }

  function Documento_Modificado($parametros)
  {
    $datos = $this->modelo->Documento_Modificado($parametros['proveedor']);
    if(count($datos)>0)
    {
      return $datos[0]['Secuencial'];
    }else
    {
      return '';
    }
  }
}
?>