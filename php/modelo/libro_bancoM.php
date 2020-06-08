<?php
error_reporting(-1);
//include(dirname(__DIR__).'/funciones/funciones.php');//
include(dirname(__DIR__).'/db/variables_globales.php');//
 class libro_bancoM
 {
 	private $conn;
 	function __construct()
 	{

		$this->conn = cone_ajax();

 	}
  function cuentas_()
  {
  		$cid = $this->conn;
 	$sql= "SELECT Codigo, Codigo+'    '+Cuenta As Nombre_Cta 
          FROM Catalogo_Cuentas 
          WHERE TC = '".G_CTABANCOS."'
          AND DG = 'D'
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
          ORDER BY Codigo ";       

        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$result[] = $row;
	   }

  cerrarSQLSERVERFUN($cid);
	   return $result;

  }

   function cuentas_filtrado($ini,$fin)
  {
  		$cid = $this->conn;
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
        print($sql);

        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$result[] = $row;
	   }

  cerrarSQLSERVERFUN($cid);
	   return $result;

  }


 function consultar_banco_($desde,$hasta,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario,$DCCta)
 {

 	$cid = $this->conn;
  $sql = "SELECT Cta,T.Fecha,T.TP,T.Numero,Cheq_Dep,Cliente,C.Concepto,Debe,Haber,Saldo,Parcial_ME,Saldo_ME,T.T,T.Item
       FROM Transacciones As T,Comprobantes As C,Clientes As Cl
       WHERE T.Fecha BETWEEN '".$desde."' and '".$hasta."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'";

        if($CheckAgencia == 'true')
   {
   	 $sql.= " AND T.Item = '".$DCAgencia."' ";
   }else
   {
   	$sql.= "AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
   }

  
  	if($Checkusu == 'true')
  	{
  		$sql.=  "AND C.CodigoU = '".$DCUsuario."' 
  		AND T.Cta = '".$DCCta."' 
        AND C.TP = T.TP 
        AND C.Numero = T.Numero 
        AND C.Fecha = T.Fecha 
        AND C.Item = T.Item 
        AND C.Codigo_B = Cl.Codigo 
        AND C.Periodo = T.Periodo 
        ORDER BY Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";
  	}else
  	{
  		$sql.=  " AND T.Cta = '".$DCCta."' 
        AND C.TP = T.TP 
        AND C.Numero = T.Numero 
        AND C.Fecha = T.Fecha 
        AND C.Item = T.Item 
        AND C.Codigo_B = Cl.Codigo 
        AND C.Periodo = T.Periodo 
        ORDER BY Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";

  	}
    $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }

	  
        $tabla = grilla_generica($stmt,null,NULL,'1');

  cerrarSQLSERVERFUN($cid);
        return $tabla;
  
 }

 function consultar_banco_datos($desde,$hasta,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario,$DCCta)
 {

 	$cid = $this->conn;
  $sql = "SELECT Cta,T.Fecha,T.TP,T.Numero,Cheq_Dep,Cliente,C.Concepto,Debe,Haber,Saldo,Parcial_ME,Saldo_ME,T.T,T.Item
       FROM Transacciones As T,Comprobantes As C,Clientes As Cl
       WHERE T.Fecha BETWEEN '".$desde."' and '".$hasta."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."'";

        if($CheckAgencia == 'true')
   {
   	 $sql.= " AND T.Item = '".$DCAgencia."' ";
   }else
   {
   	$sql.= "AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
   }

  
  	if($Checkusu == 'true')
  	{
  		$sql.=  "AND C.CodigoU = '".$DCUsuario."' 
  		AND T.Cta = '".$DCCta."' 
        AND C.TP = T.TP 
        AND C.Numero = T.Numero 
        AND C.Fecha = T.Fecha 
        AND C.Item = T.Item 
        AND C.Codigo_B = Cl.Codigo 
        AND C.Periodo = T.Periodo 
        ORDER BY Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";
  	}else
  	{
  		$sql.=  " AND T.Cta = '".$DCCta."' 
        AND C.TP = T.TP 
        AND C.Numero = T.Numero 
        AND C.Fecha = T.Fecha 
        AND C.Item = T.Item 
        AND C.Codigo_B = Cl.Codigo 
        AND C.Periodo = T.Periodo 
        ORDER BY Cta,T.Fecha,T.TP,T.Numero,Debe DESC,Haber,T.ID ";

  	}

 
    $stmt = sqlsrv_query($cid, $sql);
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }
    $result = array();
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
       $result[] = $row;
     }

  //cerrarSQLSERVERFUN($cid);
     return $result;
  
 }

 function consultatr_submodulos($FechaIni,$FechaFin,$CheckAgencia,$DCAgencia,$CheckUsuario,$DCUsuario)
 {
 	 $cid = $this->conn;
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

  $stmt = sqlsrv_query($cid, $sql);
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }
    $result = array();
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
       $result[] = $row;
     }

  //cerrarSQLSERVERFUN($cid);
     return $result;
 }
 function consulta_totales($OpcUno,$PorConceptos,$cuentaini,$cuentafin,$desde,$hasta,$DCCtas,$CheckAgencia,$DCAgencia,$Checkusu,$DCUsuario)
 {
 	$cid = $this->conn;
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
	$stmt = sqlsrv_query($cid, $sql);
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }

    $result = array();
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
          $result[] = $row; 
     }

    return $result;
 }

 function exportar_excel($parametros,$sub)
 {

  	$desde = str_replace('-','',$parametros['desde']);
	$hasta = str_replace('-','',$parametros['hasta']);
	$result = $this->consultar_banco_datos($desde,$hasta,$parametros['CheckAgencia'],$parametros['DCAgencia'],$parametros['CheckUsu'],$parametros['DCUsuario'],$parametros['DCCtas']);
	
 	exportar_excel_libro_banco($result,'Libro Banco',null,null,null);    
  }
 

 } 
?>