<?php

require_once(dirname(__DIR__,2)."/db/db.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class facturar_pensionM
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
		if($query != '')
		{
		   	$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		$sql.=" GROUP BY C.Email, C.T,C.Codigo,C.Cliente,C.Direccion,C.Grupo,C.Telefono,C.CI_RUC,C.TD 
			ORDER BY C.Cliente OFFSET 0 ROWS FETCH NEXT 10 ROWS ONLY";

		$stmt = sqlsrv_query( $this->dbs, $sql);
		$result = array();
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	    {
			$result[] = $row;
	   	}
	   	return $result;
    }

    public function getCatalogoLineas($fecha,$vencimiento){
        $sql="SELECT * FROM Catalogo_Lineas WHERE TL <> 0 
			AND Item = '".$_SESSION['INGRESO']['item']."' 
			AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND Fact = 'FA'
			AND CONVERT(DATE,Fecha) <= '".$fecha."'
			AND CONVERT(DATE,Vencimiento) >= '".$vencimiento."' 
			ORDER BY Codigo";
		$stmt = sqlsrv_query( $this->dbs, $sql);
		return $stmt;
    }

    public function getCatalogoCuentas(){
        $sql="SELECT Codigo, Cuenta As NomCuenta, TC 
       		FROM Catalogo_Cuentas 
       		WHERE TC IN ('C','P','BA','CJ','TJ') 
       		AND DG = 'D' 
       		AND Item = '".$_SESSION['INGRESO']['item']."'
       		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
       		ORDER BY TC,Codigo";
		$stmt = sqlsrv_query( $this->dbs, $sql);
		return $stmt;
    }

    public function getNotasCredito(){
    	$sql = "SELECT Codigo, Cuenta As NomCuenta, TC 
			FROM Catalogo_Cuentas 
			WHERE SUBSTRING (Codigo,1,1) = '4' 
			AND DG = 'D'
			AND Item = '".$_SESSION['INGRESO']['item']."'
       		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
			ORDER BY TC,Codigo";
		$stmt = sqlsrv_query( $this->dbs, $sql);
		return $stmt;
    }

    public function getCatalogoProductos($codigoCliente){
    	$sql = "SELECT CF.Mes,CF.Num_Mes,CF.Valor,CF.Descuento,CF.Descuento2,CF.Codigo,CF.Periodo As Periodos,CF.Mensaje,CF.Credito_No,CP.*
			FROM Clientes_Facturacion As CF,Catalogo_Productos As CP
			WHERE CF.Codigo = '".$codigoCliente."'
			AND CP.Item = '".$_SESSION['INGRESO']['item']."'
			AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND CF.Mes <> '.'
			AND CF.Item = CP.Item 
			AND CF.Codigo_Inv = CP.Codigo_Inv 
			ORDER BY CF.Periodo,CF.Num_Mes,CP.Codigo_Inv,CF.Credito_No";
		$stmt = sqlsrv_query( $this->dbs, $sql);
		return $stmt;
    }

    public function getSaldoFavor($codigoCliente){
    	$SubCtaGen = Leer_Seteos_Ctas("Cta_Anticipos_Clientes");
  		$sql = "SELECT Codigo, SUM(Creditos-Debitos) As Saldo_Pendiente
       		FROM Trans_SubCtas
       		WHERE Item = '".$_SESSION['INGRESO']['item']."'
       		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
       		AND Codigo = '".$codigoCliente."'
       		AND Cta = '".$SubCtaGen."'
       		AND T = 'N'
       		GROUP BY Codigo ";
       	$stmt = sqlsrv_query( $this->dbs, $sql);
		return $stmt;
    }

    public function getSaldoPendiente($codigoCliente){
  		$sql = "SELECT CodigoC,SUM(Saldo_MN) As Saldo_Pend 
                FROM Facturas 
                WHERE Item = '".$_SESSION['INGRESO']['item']."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                AND CodigoC = '".$codigoCliente."'
                AND Saldo_MN > 0
                AND T <> 'A'
                GROUP BY CodigoC";
       	$stmt = sqlsrv_query( $this->dbs, $sql);
		return $stmt;
    }

}

?>