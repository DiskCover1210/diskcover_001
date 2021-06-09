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
        $this->dbs=Conectar::conexionSQL();
    }
	
	public function getClientes($query){
        $sql="SELECT C.Email,C.T,C.Codigo,C.Cliente,C.Direccion,C.Grupo,C.Telefono,C.CI_RUC,C.TD,SUM(CF.Valor) As Deuda_Total 
			FROM Clientes As C, Clientes_Facturacion As CF 
			WHERE C.T = 'N'
			AND CF.Item = '".$_SESSION['INGRESO']['item']."' 
			AND CF.Num_Mes >= 0
			AND C.Codigo <> '9999999999' 
			AND C.FA <> 0
			AND CF.Codigo = C.Codigo";
		if($query != 'total')
		{
		   	$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		$sql.=" GROUP BY C.Email, C.T,C.Codigo,C.Cliente,C.Direccion,C.Grupo,C.Telefono,C.CI_RUC,C.TD 
			ORDER BY C.Cliente";
    if ($query != 'total') {
      $sql .= " OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";
    }
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
      $stmt = sqlsrv_query( $this->dbs, $sql);
      $result = array();
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
      {
      $result[] = $row;
      }
      return $result;
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