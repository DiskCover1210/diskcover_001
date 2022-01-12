<?php
error_reporting(-1);
//include(dirname(__DIR__).'/funciones/funciones.php');//
include(dirname(__DIR__,2).'/db/variables_globales.php');//
 class mayor_auxiliarM
 {
 	private $conn;
 	function __construct()
 	{

		// $this->conn = cone_ajax();
    $this->conn = new db();

 	}
  function cuentas_($ini,$fin)
  {
  		
  	$sql= "SELECT Codigo, Codigo+'    '+Cuenta As Nombre_Cta 
          FROM Catalogo_Cuentas
          WHERE DG = 'D'";
          if(!empty($ini) && !empty($fin))
          {
          $sql.=" and Codigo BETWEEN '".$ini."' and '".$fin."' ";
          } 
          $sql.= "AND Cuenta <> '".G_NINGUNO."' 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          ORDER BY Codigo ";

          $result = $this->conn->datos($sql);
           return $result;

  }

   function cuentas_filtrado($ini,$fin)
  {
  		
  		if($ini =='')
  		{
  			$ini = 1;
  		}
  		if($fin == '')
  		{
  			$fin = $ini;
  		}
  		$sql ="SELECT Codigo, Codigo+'    '+Cuenta As Nombre_Cta 
       FROM Catalogo_Cuentas 
       WHERE DG = 'D'
        AND Cuenta <> '".G_NINGUNO."' 
        AND Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        ORDER BY Codigo ";
        // print($sql);
        $result = $this->conn->datos($sql);
           return $result;

  }


 function consultar_cuentas_($OpcUno,$PorConceptos,$cuentaini,$cuentafin,$desde,$hasta,$DCCtas,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario)
 {

 	
 	$totales = $this->consulta_totales($OpcUno,$PorConceptos,$cuentaini,$cuentafin,$desde,$hasta,$DCCtas,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario);
 	//print_r($PorConceptos);
 	if($cuentaini=='')
 	{
 		$cuentaini = 1;
 	}
 	if($cuentafin == '')
 	{
 		$cuentafin = 9;
 	}

 	if($PorConceptos=='true')
 	{
 		$sql =  "SELECT T.Fecha,T.TP,T.Numero,Cl.Cliente,T.Detalle As Concepto,T.Cheq_Dep,T.Debe,T.Haber,T.Saldo,
          T.Parcial_ME,T.Saldo_ME,T.ID,T.Cta,T.Item FROM Transacciones As T,Clientes As Cl ";
 	}else
 	{
 		 $sql = "SELECT T.Fecha,T.TP,T.Numero,Cl.Cliente,C.Concepto,T.Cheq_Dep,Debe,Haber,Saldo,
          Parcial_ME,Saldo_ME,T.ID,T.Cta,T.Item FROM Transacciones As T,Comprobantes As C,Clientes As Cl ";

 	}
 	
 	$sql.="WHERE T.Fecha BETWEEN '".$desde."' and '".$hasta."' AND T.T = '".G_NORMAL."' AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

    if($OpcUno == 'true' && $cuentaini =='' && $cuentafin=='')
    {
       $sql.=" AND T.Cta = '".$DCCtas."'";
    }else
    {
    	$sql.= "AND T.Cta BETWEEN '".$cuentaini."' AND '".$cuentafin."' ";
    }
    if($CheckAgencia == 'true')
   {
   	 $sql.= " AND T.Item = '".$DCAgencia."' ";
   }else
   {
   	$sql.= "AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
   }
 if($PorConceptos == 'true')
  {
  	$sql .= "AND T.Codigo_C = Cl.Codigo ";
  }else
  {
  	if($Checkusu == 'true')
  	{
  		$sql.=  "AND C.CodigoU = '".$DCUsuario."' AND C.Codigo_B = Cl.Codigo AND T.TP = C.TP AND T.Numero = C.Numero AND T.Periodo = C.Periodo AND T.Fecha = C.Fecha AND T.Item = C.Item ";
  	}else
  	{
  		 $sql.=  "AND C.Codigo_B = Cl.Codigo AND T.TP = C.TP AND T.Numero = C.Numero AND T.Periodo = C.Periodo AND T.Fecha = C.Fecha AND T.Item = C.Item ";

  	}
  }
  $sql.= "ORDER BY T.Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";
 // print_r($DCAgencia);print_r($CheckAgencia);
  // print_r($sql);
// die();
    $tbl = grilla_generica_new($sql,'Transacciones As T,Comprobantes As C,Clientes As Cl ','tbl_may',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,500);

       return $tbl;
  
 }

function consultar_cuentas_datos($OpcUno,$PorConceptos,$cuentaini,$cuentafin,$desde,$hasta,$DCCtas,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario)
 {

 	
 	$totales = $this->consulta_totales($OpcUno,$PorConceptos,$cuentaini,$cuentafin,$desde,$hasta,$DCCtas,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario);
 	//print_r($PorConceptos);
 	if($cuentaini=='')
 	{
 		$cuentaini = 1;
 	}
 	if($cuentafin == '')
 	{
 		$cuentafin = 9;
 	}

 	if($PorConceptos=='true')
 	{
 		$sql =  "SELECT T.Fecha,T.TP,T.Numero,Cl.Cliente,T.Detalle As Concepto,T.Cheq_Dep,T.Debe,T.Haber,T.Saldo,
          T.Parcial_ME,T.Saldo_ME,T.ID,T.Cta,T.Item FROM Transacciones As T,Clientes As Cl ";
 	}else
 	{
 		 $sql = "SELECT T.Fecha,T.TP,T.Numero,Cl.Cliente,C.Concepto,T.Cheq_Dep,Debe,Haber,Saldo,
          Parcial_ME,Saldo_ME,T.ID,T.Cta,T.Item FROM Transacciones As T,Comprobantes As C,Clientes As Cl ";

 	}
 	
 	$sql.="WHERE T.Fecha BETWEEN '".$desde."' and '".$hasta."' AND T.T = '".G_NORMAL."' AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

    if($OpcUno == 'true' && $cuentaini == '' && $cuentafin=='')
    {
       $sql.=" AND T.Cta = '".$DCCtas."'";
    }else
    {
    	$sql.= "AND T.Cta BETWEEN '".$cuentaini."' AND '".$cuentafin."' ";
    }
    if($CheckAgencia == 'true')
   {
   	 $sql.= " AND T.Item = '".$DCAgencia."' ";
   }else
   {
   	$sql.= "AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
   }
 if($PorConceptos == 'true')
  {
  	$sql .= "AND T.Codigo_C = Cl.Codigo ";
  }else
  {
  	if($Checkusu == 'true')
  	{
  		$sql.=  "AND C.CodigoU = '".$DCUsuario."' AND C.Codigo_B = Cl.Codigo AND T.TP = C.TP AND T.Numero = C.Numero AND T.Periodo = C.Periodo AND T.Fecha = C.Fecha AND T.Item = C.Item ";
  	}else
  	{
  		 $sql.=  "AND C.Codigo_B = Cl.Codigo AND T.TP = C.TP AND T.Numero = C.Numero AND T.Periodo = C.Periodo AND T.Fecha = C.Fecha AND T.Item = C.Item ";

  	}
  }
  $sql.= "ORDER BY T.Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";
 // print_r($DCAgencia);print_r($CheckAgencia);
//   print_r($sql);
// die();
   $result = $this->conn->datos($sql);
           return $result;
  
 }

 function consultatr_submodulos($FechaIni,$FechaFin,$CheckAgencia,$DCAgencia,$CheckUsuario,$DCUsuario)
 {
 	 
    $sql = "SELECT T.Fecha,T.TP,T.Numero,C.Cliente,T.Cta,T.TC,T.Factura,T.Debitos,T.Creditos,T.Prima
        FROM Trans_SubCtas As T,Clientes As C 
        WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND T.TC='P' ";

   if($CheckAgencia=='true')
  {
    if($DCAgencia=='')
    {     
      $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
    }else
    {
      $sql.= "AND T.Item = '".$DCAgencia."' ";
    }

  }else
  {
    $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
  }

  
   if($CheckUsuario=='true')
  {
    $sql.= "AND T.CodigoU = '".$DCUsuario."' ";
  }
  
  $sql .="AND T.Codigo = C.Codigo
       UNION
       SELECT T.Fecha,T.TP,T.Numero,Detalle As Cliente,T.Cta,T.TC,T.Factura,T.Debitos,T.Creditos,T.Prima
       FROM Trans_SubCtas As T,Catalogo_SubCtas As C 
        WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' AND T.TC='P' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

    if($CheckAgencia=='true')
  {
    if($DCAgencia=='')
    {     
      $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
    }else
    {
      $sql.= "AND T.Item = '".$DCAgencia."' ";
    }

  }else
  {
    $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
  }

    if($CheckUsuario=='true')
  {
    $sql.= "AND T.CodigoU = '".$DCUsuario."' ";
  }
 
  $sql.= "AND T.Item = C.Item 
       AND T.Periodo = C.Periodo 
       AND T.Codigo = C.Codigo 
       ORDER BY T.Fecha,T.TP,T.Numero,T.Cta,T.Factura ";
       // print_r($sql);die();

      $result = $this->conn->datos($sql);
           return $result;
 }
 function consulta_totales($OpcUno,$PorConceptos,$cuentaini,$cuentafin,$desde,$hasta,$DCCtas,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario)
 {
 	
  $SumaDebe = 0; $SumaHaber = 0; $Suma_ME = 0; $SaldoTotal = 0;
 	$sql = "SELECT T.Cta,SUM(T.Debe) As TDebe, SUM(T.Haber) As THaber, SUM(T.Parcial_ME) As TParcial_ME ";
 	if($PorConceptos=='true')
 	{
 		 $sql.="FROM Transacciones As T,Clientes As Cl ";
 	}else
 	{
 		$sql.="FROM Transacciones As T,Comprobantes As C,Clientes As Cl ";
 	}

 	$sql.="WHERE T.Fecha BETWEEN '".$desde."' AND '".$hasta."' AND T.T = '".G_NORMAL."' AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
 	if($OpcUno == 'true')
    {
       $sql.=" AND T.Cta = '".$DCCtas."'";
    }else
    {
    	$sql.= "AND T.Cta BETWEEN '".$cuentaini."' AND '".$cuentafin."' ";
    }
     if($CheckAgencia == 'true')
   {
   	 $sql.= " AND T.Item = '".$DCAgencia."' ";
   }else
   {
   	$sql.= "AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
   }
   if($PorConceptos == 'true')
  {
  	$sql .= "AND T.Codigo_C = Cl.Codigo ";
  }else
  {
  	if($Checkusu == 'true')
  	{
  		$sql.=  "AND C.CodigoU = '".$DCUsuario."' AND C.Codigo_B = Cl.Codigo AND T.TP = C.TP AND T.Numero = C.Numero AND T.Periodo = C.Periodo AND T.Fecha = C.Fecha AND T.Item = C.Item ";
  	}else
  	{
  		 $sql.=  "AND C.Codigo_B = Cl.Codigo AND T.TP = C.TP AND T.Numero = C.Numero AND T.Periodo = C.Periodo AND T.Fecha = C.Fecha AND T.Item = C.Item ";

  	}
  }
  $sql.="GROUP BY T.Cta ORDER BY T.Cta ";
// print_r($sql);
 //die();
	$result = $this->conn->datos($sql);
           return $result;
 }

 function exportar_excel($parametros,$sub)
 {

  	$desde = str_replace('-','',$parametros['desde']);
	$hasta = str_replace('-','',$parametros['hasta']);
	$result = $this->consultar_cuentas_datos($parametros['OpcUno'],$parametros['PorConceptos'],$parametros['txt_CtaI'],$parametros['txt_CtaF'],$desde,$hasta,$parametros['DCCtas'],$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario']);
	 if($sub != 'false')
       {       	
  		$submodulo = $this->consultatr_submodulos($desde,$hasta,$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario']);
  	   }else
  	   {
  	   	$submodulo=array();
  	   }
  	   
	//print_r($result);
 	// exportar_excel_mayor_auxi($result,$submodulo,'Mayor Auxiliar',null,null,null);  
  excel_file_mayor_auxi($result,$submodulo,'Mayor Auxiliar',null,null,null);  
  }
 

 } 
?>