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
}
?>