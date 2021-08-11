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

  public function getCatalogoLineas($FechaProceso){
    $sql="SELECT * 
          FROM Catalogo_Lineas 
          WHERE TL <> 0 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND Fecha <= '".$FechaProceso."' 
          AND Vencimiento >= '".$FechaProceso."' 
          ORDER BY Fact,Codigo";
    $stmt = sqlsrv_query( $this->dbs, $sql);
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
    $stmt = sqlsrv_query( $this->dbs, $sql);

    return $stmt;
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

  public function updateClientesMatriculas($TextRepresentante,$TextCI,$TD_Rep,$TxtTelefono,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente){
        $sql = "UPDATE Clientes_Matriculas
                  SET Representante = '".$TextRepresentante."', 
                  Cedula_R = '".$TextCI."', 
                  TD = '".$TD_Rep."', 
                  Telefono_R = '".$TxtTelefono."', 
                  Lugar_Trabajo_R = '".$TxtDireccion."', 
                  Email_R = '".$TxtEmail."', 
                  Grupo_No = '".$TxtGrupo."' 
                  WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
                  AND Item = '".$_SESSION['INGRESO']['item']."'
                  AND Codigo = '".$codigoCliente."' ";
        $stmt = sqlsrv_query( $this->dbs, $sql);
        $rows_affected = sqlsrv_rows_affected( $stmt);
      return $rows_affected;
    }

    public function updateClientesFacturacion($TxtGrupo,$codigoCliente){
      $sql = "UPDATE Clientes_Facturacion
                  SET GrupoNo = '".$TxtGrupo."'
                  WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."'
                  AND Item = '".$_SESSION['INGRESO']['item']."'
                  AND Codigo = '".$codigoCliente."' ";
        $stmt = sqlsrv_query( $this->dbs, $sql);
      $rows_affected = sqlsrv_rows_affected( $stmt);
      return $rows_affected;
    }

    public function updateClientesFacturacion1($Valor,$Anio1,$Codigo1,$Codigo,$Codigo3,$Codigo2){
      $sql = "UPDATE Clientes_Facturacion 
              SET Valor = Valor - ".$Valor." 
              WHERE Item = '".$_SESSION['INGRESO']['item']."' 
              AND Periodo = '".$Anio1."' 
              AND Codigo_Inv = '".$Codigo1."' 
              AND Codigo = '".$Codigo."' 
              AND Credito_No = '".$Codigo3."' 
              AND Mes = '".$Codigo2."' ";
      $stmt = sqlsrv_query( $this->dbs, $sql);
      $rows_affected = sqlsrv_rows_affected( $stmt);
      return $rows_affected;
    }

    public function updateClientes($TxtTelefono,$TxtDirS,$TxtDireccion,$TxtEmail,$TxtGrupo,$codigoCliente){
        $sql = "UPDATE Clientes
                  SET Telefono = '".$TxtTelefono."', 
                  Telefono_R = '".$TxtTelefono."', 
                  Direccion = '".$TxtDirS."', 
                  DireccionT = '".$TxtDireccion."', 
                  Email = '".$TxtEmail."', 
                  Grupo = '".$TxtGrupo."' 
                  WHERE Codigo = '".$codigoCliente."'";
        $stmt = sqlsrv_query( $this->dbs, $sql);
    $rows_affected = sqlsrv_rows_affected( $stmt);
    return $rows_affected;
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