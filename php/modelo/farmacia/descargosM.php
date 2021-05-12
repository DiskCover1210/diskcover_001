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

	function pedido_paciente($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false,$busfe=false)
	{

		$cid = $this->conn;
		$sql = "SELECT SUM(VALOR_TOTAL) as 'importe',ORDEN,Codigo_B,Fecha_Fab,C.Cliente as 'nombre',A.SUBCTA as 'area',CS.Detalle as 'subcta',C.Matricula as 'his',A.Detalle as 'Detalle'
			FROM Asiento_K  A
			LEFT JOIN Clientes C ON A.Codigo_B = C.CI_RUC 
			LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.SUBCTA
			WHERE 1=1  AND DH='2' ";
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
		if($busfe)
		{		
			  $sql.=" AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."'";
		}

		$sql.=" GROUP BY ORDEN ,Codigo_B,Fecha_Fab,C.Cliente,A.SUBCTA,CS.Detalle,C.Matricula,A.Detalle ORDER BY Fecha_Fab DESC";
		$sql.=" OFFSET 0 ROWS FETCH NEXT 50 ROWS ONLY;";
		
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

	function productos_procesados($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false,$busfe=false)
	{

		$cid = $this->conn;
		$sql = "SELECT DISTINCT A.CODIGO_INV,CANTIDAD
			FROM Asiento_K  A
			LEFT JOIN Clientes C ON A.Codigo_B = C.CI_RUC 
			LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.SUBCTA
			WHERE 1=1  AND DH='2' ";
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
		if($busfe)
		{		
			  $sql.=" AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."'";
		}

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

	function ordenes_producto_nega($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false,$busfe=false,$negativos=false)
	{

		$cid = $this->conn;
		$sql = "SELECT DISTINCT ORDEN
			FROM Asiento_K  A
			LEFT JOIN Clientes C ON A.Codigo_B = C.CI_RUC 
			LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.SUBCTA
			WHERE 1=1  AND DH='2' ";
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
		if($busfe)
		{		
			  $sql.=" AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."'";
		}
		if($negativos)
		{
			$sql.= " AND CODIGO_INV IN (".$negativos.")";
		}
		
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




	function pedido_paciente_distintos($codigo_b=false,$tipo=false,$query=false,$desde=false,$hasta =false,$busfe=false)
	{

		$cid = $this->conn;
		$sql = "SELECT DISTINCT ORDEN,Codigo_B,C.Cliente as 'nombre',A.SUBCTA as 'area',CS.Detalle as 'subcta',C.Matricula as 'his',A.Detalle as 'Detalle'
			FROM Asiento_K  A
			LEFT JOIN Clientes C ON A.Codigo_B = C.CI_RUC 
			LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.SUBCTA
			WHERE 1=1  AND DH='2' ";
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
		if($busfe)
		{		
			  $sql.=" AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."'";
		}

		$sql.=" GROUP BY ORDEN ,Codigo_B,C.Cliente,A.SUBCTA,CS.Detalle,C.Matricula,A.Detalle ORDER BY ORDEN DESC";
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


	function area_descargo($query = false,$codigo = false)
	{
		$cid = $this->conn;
		$sql = "SELECT   TC, Codigo, Detalle
		FROM   Catalogo_SubCtas
		WHERE  TC='CC' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item= '".$_SESSION['INGRESO']['item']."'";
		if($query)
		{
			$sql.=" AND Detalle like '%".$query."%'";
		}
		if($codigo)
		{
			$sql.=" AND Codigo ='".$codigo."'";
		}
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

	function actualizar_his($dato,$where)
	{
		return update_generico($dato,'Clientes',$where);
	}


	function elimina_pedido($parametros)
	{

		$cid = $this->conn;
		$sql = "DELETE FROM Asiento_K WHERE ORDEN='".$parametros['ped']."' and SUBCTA ='".$parametros['area']."'";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  

			return -1;

		}else
		{
		   return 1;

		}		
	}

	function cargar_lineas_pedidos($orden,$fecha)
	{
		// print_r($hasta.'-'.$hasta);die();
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Asiento_K WHERE ORDEN = '".$orden."'";
     if($fecha)
     {
     	$sql.=" AND Fecha_Fab = '".$fecha."'";
     }
     $sql.=" ORDER BY A_No DESC";
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


	function cargar_lineas_pedidos_por_fecha($orden,$desde=false,$hasta=false)
	{
		// print_r($hasta.'-'.$hasta);die();
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT DISTINCT Fecha_Fab  FROM Asiento_K WHERE ORDEN = '".$orden."' ";

     if($desde)
       { 
         $sql.= " AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."' ";
       }

      $sql.=" ORDER BY Fecha_Fab desc ";

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

	function imprimir_excel($stmt1)
	{		
	     exportar_excel_descargos($stmt1,null,null,1);
	}


}