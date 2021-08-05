<?php
require_once(dirname(__DIR__,2)."/db/db.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class kardexM
{
 	private $db;
  private $dbs;
  public function __construct(){
    //conexion mysql
    $this->db=Conectar::conexion();
    //conexion sql server
    $this->dbs=Conectar::conexionSQL();
  }
 

  public function productos($tipo,$codigoProducto){
    $sql="SELECT Codigo_Inv, Producto As NomProd , Minimo, Maximo, Unidad
            FROM Catalogo_Productos 
            WHERE Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
            AND TC = '".$tipo."'";
    if ($codigoProducto != '') {
      $sql .= " AND Codigo_Inv LIKE '".$codigoProducto."%'";
    }
    $sql .= " ORDER BY Codigo_Inv";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    return $stmt;
  }

  public function bodegas(){
    $sql="SELECT Bodega, CodBod
          FROM Catalogo_Bodegas 
          WHERE Item = '".$_SESSION['INGRESO']['item']."'
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
          ORDER BY CodBod";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    return $stmt;
  }

  public function consulta_kardex_producto($desde,$hasta,$codigo){
    $sql= "SELECT CodBodega As Bodega,Fecha,TP,Numero As Comp_No,TC,Serie,Factura,Detalle,
          Entrada,Salida,Existencia As Stock,Costo,Total As Saldo, Valor_Unitario, Valor_Total, Serie_No, Codigo_Barra, ID 
          FROM Trans_Kardex 
          WHERE Fecha BETWEEN '".$desde."' AND '".$hasta."'
          AND Codigo_Inv = '".$codigo."'
          AND T = '".G_NORMAL."' 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    $tabla = grilla_generica_new($sql,'Trans_Kardex','myTable','',false,false,false,1,1,1,100);
    return $tabla;
  }

  public function consulta_kardex($desde,$hasta,$codigo,$cbBodega,$bodega){
    $sql =  "SELECT K.Codigo_Inv, K.Codigo_Barra, SUM(Entrada-Salida) As Stock_Kardex 
            FROM Trans_Kardex As K, Comprobantes As C 
            WHERE K.Fecha BETWEEN '".$desde."' AND '".$hasta."' 
            AND K.Codigo_Inv = '".$codigo."'
            AND K.T = '".G_NORMAL."' 
            AND K.Item = '".$_SESSION['INGRESO']['item']."' 
            AND K.Periodo = '".$_SESSION['INGRESO']['periodo']."'";
    if ($cbBodega) {
      $sql .= "AND K.CodBodega = '".$bodega."' ";
    }
    $sql .= "AND K.TP = C.TP 
            AND K.Fecha = C.Fecha 
            AND K.Numero = C.Numero 
            AND K.Item = C.Item 
            AND K.Periodo = C.Periodo 
            GROUP BY K.Codigo_Inv, K.Codigo_Barra
            HAVING SUM(Entrada-Salida) >=1 
            ORDER BY K.Codigo_Inv, K.Codigo_Barra ";
    $tabla = grilla_generica_new($sql,'Trans_Kardex As K, Comprobantes As C','myTable','',false,false,false,1,1,1,100);
    return $tabla;
  }

  public function kardex_total($desde,$hasta,$codigo,$cbBodega,$bodega){
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(C.Cliente,1,50) 
            FROM Trans_Kardex As TK, Clientes As C 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' _
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.Codigo_P <> '.'
            AND TK.Fecha BETWEEN '".$desde."' AND '".$hasta."'
            AND TK.Codigo_P = C.Codigo ";
    sqlsrv_query( $this->dbs, $sql);
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(CS.Detalle,1,50) 
            FROM Trans_Kardex As TK, Catalogo_SubCtas As CS 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.Codigo_P <> '.' 
            AND TK.Fecha BETWEEN '".$desde."' AND '".$hasta."' 
            AND TK.Item = CS.Item 
            AND TK.Periodo = CS.Periodo 
            AND TK.Codigo_P = CS.Codigo ";
    sqlsrv_query( $this->dbs, $sql);

    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = SUBSTRING(CS.Detalle,1,50) 
            FROM Trans_Kardex As TK, Catalogo_SubCtas As CS 
            WHERE TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
            AND TK.CodigoL <> '.' 
            AND TK.Fecha BETWEEN '".$desde."' AND #'".$hasta."' 
            AND TK.Item = CS.Item 
            AND TK.Periodo = CS.Periodo 
            AND TK.CodigoL = CS.Codigo ";
    sqlsrv_query( $this->dbs, $sql);
  
    $sql =  "UPDATE Trans_Kardex 
            SET Centro_Costo = '.' 
            WHERE Centro_Costo IS NULL ";
    sqlsrv_query( $this->dbs, $sql);

    $sql =  "SELECT TK.Codigo_Inv, CP.Producto, TK.CodBodega As Bodega, TK.Fecha,TK.Entrada, TK.Salida, TK.Existencia, 
            TK.Valor_Unitario, TK.Valor_Total, TK.Costo, TK.Total, TK.TP, TK.Numero As Comp_No, TK.TC, TK.Serie, 
            TK.Factura, C.CI_RUC, C.Cliente As Beneficiario, TK.Detalle, TK.Lote_No, CP.Unidad, TK.Cta_Inv, TK.Contra_Cta, TK.Centro_Costo, TK.Codigo_Barra 
            FROM Trans_Kardex As TK, Catalogo_Productos As CP, Clientes As C 
            WHERE TK.Fecha BETWEEN '".$desde."' AND '".$hasta."'
            AND TK.T = '".G_NORMAL."' 
            AND TK.Item = '".$_SESSION['INGRESO']['item']."' 
            AND TK.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
    if ($codigo != "*") {
      $sql .= "AND TK.Codigo_Inv LIKE '".$codigo."%' ";
    }
    if ($cbBodega) {
      $sql .= "AND TK.CodBodega = '".$bodega."'";
    }
    $sql .=  "AND TK.Item = CP.Item 
            AND TK.Periodo = CP.Periodo 
            AND TK.Codigo_Inv = CP.Codigo_Inv 
            AND TK.Codigo_P = C.Codigo 
            ORDER BY TK.Codigo_Inv, TK.Fecha, TK.Entrada DESC, TK.Salida, TK.TP, TK.Numero, TK.ID ";
    $stmt = sqlsrv_query( $this->dbs, $sql);
    $tabla = grilla_generica_new($sql,'Trans_Kardex','myTable','',false,false,false,1,1,1,100);
    return $tabla;
  }
} 
?>