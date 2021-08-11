<?php

require_once(dirname(__DIR__,2)."/db/db.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class facturaM
{
	private $db;
	private $dbs;

	public function __construct(){
    //conexion mysql
    $this->db=Conectar::conexion();
    //conexion sql server
    $this->dbs= cone_ajax();
  }
	
	public function getClientes($query){
    $sql="SELECT C.Email,C.Codigo,C.Cliente,C.Direccion,C.Telefono,C.CI_RUC, C.Celular 
			FROM Clientes As C 
			WHERE C.T = 'N'
			AND C.Codigo <> '9999999999' 
			AND C.FA <> 0";
		if($query != 'total')
		{
		   	$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		$sql.=" GROUP BY C.Email,C.Codigo,C.Cliente,C.Direccion,C.Telefono,C.CI_RUC,C.Celular 
			ORDER BY C.Cliente";
    if ($query != 'total') {
      $sql .= " OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";
    }
    //print_r($sql);
		$stmt = sqlsrv_query( $this->dbs, $sql);
		$result = array();
	  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	  {
			$result[] = $row;
	  }
	  return $result;
  }

  public function getCatalogoProductos($query){
  	$sql = "SELECT *
		FROM Catalogo_Productos As CP
		WHERE CP.Item = '".$_SESSION['INGRESO']['item']."'
		AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
    $sql.=" AND Producto LIKE '%".$query."%' ORDER BY Producto OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";

    // print_r($sql);die();
    $stmt = sqlsrv_query( $this->dbs, $sql);
    $result = array();
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
    {
      $result[] = $row;
    }
    return $result;
  }

  public function getCatalogoLineas(){
    $sql="SELECT TOP 1 * 
    FROM Catalogo_Lineas 
    WHERE TL <> 0 
    AND Item = '".$_SESSION['INGRESO']['item']."' 
    AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
    AND Serie = '".$_SESSION['INGRESO']['Serie_FA']."' 
    AND Fact = 'FA'
    ORDER BY Codigo";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    $result = array();
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
    {
      $result[] = $row;
    }
    return $result[0];
  }

  public function updateCliente($Telefono,$Direccion,$Email,$Celular,$codigoCliente){
    $sql = "UPDATE Clientes
              SET Telefono = '".$Telefono."', 
              Direccion = '".$Direccion."',  
              Email = '".$Email."', 
              Celular = '".$Celular."' 
              WHERE Codigo = '".$codigoCliente."'";
    $stmt = sqlsrv_query( $this->dbs, $sql);
		$rows_affected = sqlsrv_rows_affected( $stmt);
		return $rows_affected;
  }

  public function deleteAsiento($codigoCliente){
    $sql = "DELETE
          FROM Asiento_F
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Codigo_Cliente = '".$codigoCliente."' 
          AND CodigoU = '". $_SESSION['INGRESO']['CodigoU'] ."' ";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    //$stmt = sqlsrv_prepare($this->dbs, $sql);
    return $stmt;
  }

  public function getAsiento(){
    $sql = "SELECT * 
          FROM Asiento_F
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
          ORDER BY A_No ";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    return $stmt;
  }

}

?>