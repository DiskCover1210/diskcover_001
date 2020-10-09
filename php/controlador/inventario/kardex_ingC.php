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
}
?>