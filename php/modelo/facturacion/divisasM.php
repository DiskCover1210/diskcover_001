<?php

require_once(dirname(__DIR__,2)."/db/db.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class divisasM
{
	private $db;
	private $dbs;

	public function __construct(){
    //conexion mysql
    $this->db=Conectar::conexion();
    //conexion sql server
    $this->dbs=Conectar::conexionSQL();
  }

  public function getCatalogoLineas(){
    $sql="SELECT * 
          FROM Catalogo_Lineas 
          WHERE TL <> 0 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          ORDER BY Fact,Codigo";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    return $stmt;
  }

  public function getProductos(){
    $sql="SELECT Producto, Codigo_Inv, Codigo_Barra, PVP, Div
          FROM Catalogo_Productos 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = 'P' 
          AND SUBSTRING(Codigo_Inv,1,2) = '01'
          ORDER BY Producto,Codigo_Inv ";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    return $stmt;
  }
}

?>