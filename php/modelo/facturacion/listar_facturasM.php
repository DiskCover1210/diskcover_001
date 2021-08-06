<?php

require_once(dirname(__DIR__,2)."/db/db.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class listar_facturasM
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

    public function getAnticipos($codigo){
      $sql = "SELECT Codigo, Cuenta As NomCuenta, TC 
      FROM Catalogo_Cuentas 
      WHERE Codigo = ".$codigo."
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

    public function historiaCliente($codigoCliente){
      $SQL1 = "SELECT TC As TD, Fecha, Serie, Factura,'Emision ' + Producto As Detalle, YEAR(Fecha) As Anio, Mes, Total, 0 As Abonos, Mes_No, 
        (ROW_NUMBER() OVER(PARTITION BY Serie, Factura ORDER BY Fecha, Serie, Factura)) As No 
        FROM Detalle_Factura 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "'
        AND CodigoC = '" . $codigoCliente . "' 
        AND T <> 'A' 
        AND TC IN ('NV','FA') 
        GROUP BY TC, Fecha, Serie, Factura, Producto, Mes_No, Mes, Total ";
        
      $SQL2 = "SELECT TP As TD, Fecha, Serie, Factura, 'Tipo de Abono: ' + Banco As Detalle, YEAR(Fecha) AS Anio, Mes, 0 As Total, Abono As Abonos, Mes_No, 
        (ROW_NUMBER() OVER(PARTITION BY Serie, Factura ORDER BY Serie, Factura, Fecha)) As No 
        FROM Trans_Abonos 
        WHERE Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND T <> 'A' 
        AND CodigoC = '" . $codigoCliente . "' 
        GROUP BY TP, Serie, Factura, Fecha,  Mes_No, Mes, Abono, Banco, Cheque ";
        
      $SQL3 = "SELECT 'PF' As TD, CF.Fecha,'999999' As Serie,'999999999' As Factura, 'Por Facturar ' + CP.Producto As Detalle, CF.Periodo As Anio, CF.Mes, 
        CF.Valor As Total, (CF.Descuento + CF.Descuento2) As Abonos, CF.Num_Mes, (ROW_NUMBER() OVER(PARTITION BY CF.Fecha, CF.Mes ORDER BY CF.Fecha, CF.Mes)) As No 
        FROM Clientes_Facturacion As CF, Catalogo_Productos As CP 
        WHERE CP.Item = '" . $_SESSION['INGRESO']['item'] . "' 
        AND CF.Codigo = '" . $codigoCliente . "' 
        AND CP.Periodo = '.' 
        AND CP.Item = CF.Item 
        AND CP.Codigo_Inv = CF.Codigo_Inv 
        ORDER BY TD,Serie, Factura, Total desc, No ";
        $sql = $SQL1 . " UNION " . $SQL2 . " UNION " . $SQL3;
        $stmt = sqlsrv_query( $this->dbs, $sql);
        return $stmt;
    }

    public function factura_formatos(){
      $sql = "SELECT TC 
              FROM Facturas_Formatos 
              WHERE Item = '".$_SESSION['INGRESO']['item']."' 
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
              GROUP BY TC 
              ORDER BY TC";
      $stmt = sqlsrv_query( $this->dbs, $sql);
      return $stmt;
    }

    public function numeroSerie($tc){
      $TC = $tc;
      $sql = "SELECT Serie 
              FROM Facturas_Formatos 
              WHERE Item = '".$_SESSION['INGRESO']['item']."'
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
              AND TC = '".$TC."' 
              GROUP BY Serie 
              ORDER BY Serie";
      $stmt = sqlsrv_query( $this->dbs, $sql);
      return $stmt;
    }

    public function numeroSecuencial($tc,$serie){
      $TC = $tc;
      $serie = $serie;
      $sql = "SELECT Factura, Autorizacion, Clave_Acceso, CodigoC, Razon_Social
              FROM Facturas 
              WHERE Item = '".$_SESSION['INGRESO']['item']."'
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
              AND TC = '".$TC."' 
              AND Serie = '".$serie."' 
              ORDER BY Factura";
      $stmt = sqlsrv_query( $this->dbs, $sql);
      return $stmt;
    }
}

?>