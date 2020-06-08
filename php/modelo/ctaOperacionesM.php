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
	function cargar_cuentas($leng,$ini=false)
	{

		// print_r($_SESSION);die();
		$cid = $this->conn;
  	$sql= "SELECT ID,Codigo,Cuenta FROM Catalogo_Cuentas WHERE 
  	       Item='".$_SESSION['INGRESO']['item']."' AND 
  	       Periodo='".$_SESSION['INGRESO']['periodo']."' AND 
  	       DG='G' AND Len(Codigo)=".$leng."";
  	     if($ini)
  	     {
  	     	$sql.=" AND Codigo LIKE '".$ini.".%' ";
  	     }
  	     $sql.="  ORDER BY Codigo ASC";
  	    // print_r($sql);
        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		 $result[] = $row;
	   }

  //cerrarSQLSERVERFUN($cid);
	   return $result;

	}
}
?>