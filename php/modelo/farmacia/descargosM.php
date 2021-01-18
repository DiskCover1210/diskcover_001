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
class descargosM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function pedido_paciente($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false)
	{
		// print_r($codigo_b);print_r('\n');
		// print_r($tipo);print_r('\n');
		// print_r($query);print_r('\n');
		// print_r($desde);print_r('\n');
		// print_r($hasta);die();

		$cid = $this->conn;
		$sql = "SELECT SUM(VALOR_TOTAL) as 'importe',ORDEN,Codigo_B,Fecha_Fab,C.Cliente as 'nombre' FROM Asiento_K  A LEFT JOIN Clientes C ON A.Codigo_B+'001' = C.CI_RUC WHERE 1=1 ";
		if($codigo_b)
		{
			$sql.=" AND Codigo_B = '".$codigo_b."' ";
		}
		if($tipo=='P' AND $query!='')
		{
			$sql.=" AND ORDEN = '".$query."' ";
		}
		if($tipo=='C' AND $query!='')
		{
			$sql.=" AND Codigo_B LIKE '".$query."%' ";
		}
		if ($tipo=='N' AND $query!='') 
		{
			$sql.=" AND Cliente LIKE '%".$query."%'";
		}
		if($desde !=$hasta  AND $query!='')
		{
			$sql.=" AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."'";
		}

		$sql.=" GROUP BY ORDEN ,Codigo_B,Fecha_Fab,C.Cliente ORDER BY orden DESC";
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

}