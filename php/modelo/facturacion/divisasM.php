<?php

require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class divisasM
{
	private $db;

	public function __construct(){
    //base de datos
    $this->db = new db();
  }

  public function getCatalogoLineas($FechaProceso){
    $sql="SELECT * 
          FROM Catalogo_Lineas 
          WHERE TL <> 0 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND Fecha <= '".$FechaProceso."' 
          AND Vencimiento >= '".$FechaProceso."' 
          ORDER BY Fact,Codigo";
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

  public function getProductos($tipoConsulta){
    if ($tipoConsulta=='FA') {
      $Tipo ='02.01';
    } 
    else {
      $Tipo='02.02';
    }
    $sql="SELECT Producto, Codigo_Inv, Codigo_Barra, PVP, Div
          FROM Catalogo_Productos 
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND TC = 'P' 
          AND SUBSTRING(Codigo_Inv,1,5) = '".$Tipo."'
          ORDER BY Producto,Codigo_Inv ";
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

  public function deleteAsiento($codigoCliente){
    $sql = "DELETE
          FROM Asiento_F
          WHERE Item = '".$_SESSION['INGRESO']['item']."' 
          AND Codigo_Cliente = '".$codigoCliente."' 
          AND CodigoU = '". $_SESSION['INGRESO']['CodigoU'] ."' ";
    $stmt = $this->db->String_Sql($sql);
    return $stmt;
  }

  public function updateClientesFacturacion($Valor,$Anio1,$Codigo1,$Codigo,$Codigo3,$Codigo2){
    $sql = "UPDATE Clientes_Facturacion 
            SET Valor = Valor - ".$Valor." 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$Anio1."' 
            AND Codigo_Inv = '".$Codigo1."' 
            AND Codigo = '".$Codigo."' 
            AND Credito_No = '".$Codigo3."' 
            AND Mes = '".$Codigo2."' ";
    $stmt = $this->db->String_Sql($sql);
    return $stmt;
  }

  public function getAsiento(){
    $sql = "SELECT * 
            FROM Asiento_F
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
            ORDER BY A_No ";
    $stmt = $this->db->datos($sql);
    return $stmt;
  }

  public function getClientes($query){
    $sql="SELECT C.Email,C.T,C.Codigo,C.Cliente,C.Direccion,C.Grupo,C.Telefono,C.CI_RUC,C.TD FROM Clientes As C
          WHERE Cliente LIKE '%".$query."%' 
          GROUP BY C.Email, C.T,C.Codigo,C.Cliente,C.Direccion,C.Grupo,C.Telefono,C.CI_RUC,C.TD 
          ORDER BY C.Cliente 
          OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";
    $stmt = $this->db->datos($sql);
    return $stmt;
  }
}

?>