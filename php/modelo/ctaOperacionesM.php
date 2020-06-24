<?php 
include(dirname(__DIR__).'/funciones/funciones.php');//
@session_start(); 
/**
 * 
 */
class ctaOperacionesM 
{
	
	 private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}
	function cargar_cuentas($leng)
	{

		// print_r($_SESSION);die();
		$cid = $this->conn;
  	$sql= "SELECT ID,Codigo,Cuenta FROM Catalogo_Cuentas WHERE 
  	       Item='".$_SESSION['INGRESO']['item']."' AND 
  	       Periodo='".$_SESSION['INGRESO']['periodo']."' AND Len(Codigo)=".$leng."";
  	     $sql.="  ORDER BY Codigo ASC";
  	    // print_r($sql);
        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		 $result[] = $row;
	   }
	   // print_r($result);

  //cerrarSQLSERVERFUN($cid);
	   return $result;

	}
	function cargar_niveles($padre)
	{

		// print_r($_SESSION);die();
		$cid = $this->conn;
  	$sql= "SELECT ID,Codigo,Cuenta,TC FROM Catalogo_Cuentas WHERE 
  	       Item='".$_SESSION['INGRESO']['item']."' AND 
  	       Periodo='".$_SESSION['INGRESO']['periodo']."' AND SUBSTRING(Codigo,1,1) != 'x' AND SUBSTRING(Codigo,1,1) ='".$padre."' ORDER BY Codigo ASC";
  	     // print_r($sql);
        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		 $result[] = $row;
	   }

  //cerrarSQLSERVERFUN($cid);
	 //  print_r($result);
	   return $result;

	}
	function tipo_pago_()
	{
		// print_r($_SESSION);die();
		$cid = $this->conn;
	   $sql = "SELECT (Codigo +' '+Descripcion) As CTipoPago, Codigo FROM Tabla_Referenciales_SRI 
       WHERE Tipo_Referencia = 'FORMA DE PAGO'
       AND Codigo IN ('01','16','17','18','19','20','21')
       ORDER BY Codigo ";
  	     // print_r($sql);
        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		 $result[] = $row;
	   }

  //cerrarSQLSERVERFUN($cid);
	 //  print_r($result);
	   return $result;

	}
	function DGGastos()
	{
		// print_r($_SESSION);die();
		$cid = $this->conn;
	   $sql = "SELECT * FROM Ctas_Proceso
          WHERE Item = '".$_SESSION['INGRESO']['item']. "'
          AND Periodo = '".$_SESSION['INGRESO']['periodo']. "' 
          ORDER BY T_No ";
  	     // print_r($sql);
        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		 $result[] = $row;
	   }

  //cerrarSQLSERVERFUN($cid);
	 //  print_r($result);
	   return $result;
	}

	function cta_existente($codigo = false)
	{
		// print_r($_SESSION);die();
		$cid = $this->conn;
	   $sql = "SELECT *
       FROM Catalogo_Cuentas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND SUBSTRING(Codigo,1,1) <> 'x' ";
       if($codigo)
       {
         $sql.=" and Codigo LIKE '".$codigo."'";
       }
       $sql.= " ORDER BY Codigo ";
  	     // print_r($sql);
        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		 $result[] = $row;
	   }
	   return $result;
	}
}
?>