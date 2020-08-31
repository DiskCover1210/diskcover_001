<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
/**
 * 
 */
class inventario_onlineM
{
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function listar_articulos($query='')
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo_Inv,Producto,Unidad FROM Catalogo_Productos WHERE INV = 1 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 ";
     if($query !='')
     {
     	$sql .=" AND Producto LIKE '%".$query."%'"; 
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
		$datos[]=['id'=>$row['Codigo_Inv'].'/'.$row['Unidad'],'text'=>utf8_encode($row['Producto'])];	
	   }
       return $datos;
	}


	function listar_cc($query='')
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo,Cuenta FROM Catalogo_Cuentas WHERE TC='G' AND DG='D' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' ";
     if($query !='')
     {
     	$sql .=" AND Cuenta+' '+Codigo LIKE '%".$query."%'"; 
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
		$datos[]=['id'=>$row['Codigo'],'text'=>utf8_encode($row['Cuenta'])];
		// $datos[]=['id'=>$row['Codigo'],'text'=>$row['Cuenta']];	
	   }
       return $datos;

	}

	function listar_rubro($query='')
	{
	
	$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo,Detalle FROM Catalogo_SubCtas WHERE TC='G' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' ";
     if($query !='')
     {
     	$sql .=" AND Detalle+' '+Codigo LIKE '%".$query."%'"; 
     }

     // print_r($sql);die();
     $sql.= "  ORDER BY Codigo ASC";
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
		$datos[]=['id'=>$row['Codigo'],'text'=>utf8_encode($row['Detalle'])];
		// $datos[]=['id'=>$row['Codigo'],'text'=>$row['Detalle']];		
	   }
       return $datos;
	}

	function lista_entrega()
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Asiento_K";
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

	function ingresar_asiento_K($datos,$campoWhere=false)
	{
		// print_r($datos);die();
		if ($campoWhere) {
			$resp = update_generico($datos,'Asiento_K',$campoWhere);			
		  return $resp;
			
		}else{
	      $resp = insert_generico("Asiento_K",$datos);
	      return $resp;
	  }
	}	
   function eliminar($codigo,$item)
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento_K WHERE CODIGO_INV = '".$codigo."' AND Item='".$item."'";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return -1;
		     die( print_r( sqlsrv_errors(), true));  
	      }
	      else{
	      	return 1;
	      }      

	}	
}
?>