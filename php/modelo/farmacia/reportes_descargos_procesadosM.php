<?php 
if(!class_exists('variables_g'))
{
	include(dirname(__DIR__,2).'/db/variables_globales.php');//
    include(dirname(__DIR__,2).'/funciones/funciones.php');
}
@session_start(); 

/**
 * 
 */
class reportes_descargos_procesadosM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function cargar_comprobantes($query=false,$desde,$hasta,$tipo='')
	{
		$cid = $this->conn;
		$sql="SELECT Numero,CP.Fecha,Concepto,Monto_Total,Cliente FROM Comprobantes CP 
		LEFT JOIN Clientes C ON CP.Codigo_B = C.Codigo
		WHERE 1=1 AND TP='CD' AND CP.T='N' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo_B <> '.' AND Numero IN ( SELECT  DISTINCT Numero FROM Trans_Kardex WHERE 1=1 AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  AND Entrada = 0 )";
		if($tipo =='f')
		{
			$sql.= " AND CP.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}
		if($query)
		{
			$sql.=" AND C.Cliente like '%".$query."%'";
		}
		// " AND CP.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."';";

		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
            $datos[]=$row;
	   }
       return $datos;

	}
	function trans_kardex($numero)
	{
		$cid = $this->conn;
		$sql="SELECT  Codigo_Inv,Salida,Valor_Unitario,Valor_Total  FROM Trans_Kardex WHERE 1=1 AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Entrada = 0 AND Numero = '".$numero."' ";
		$stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
            $datos[]=$row;
	   }
       return $datos;

	}


	function producto($Codigo_Inv)
	{

		$cid = $this->conn;
        $sql="SELECT Producto FROM Catalogo_Productos WHERE Codigo_Inv = '".$Codigo_Inv."' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
            $datos[]=$row;
	   }
       return $datos;


	}
	function imprimir_excel($stmt1)
	{		
	     exportar_excel_comp($stmt1,null,null,1);
	}

}

?>