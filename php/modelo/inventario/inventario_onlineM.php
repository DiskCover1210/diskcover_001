<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
$_SESSION['INGRESO']['modulo_']='60';
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
     //$sql2="SELECT  Codigo_Inv,Producto,Unidad from Catalogo_Productos WHERE Item = '".$_SESSION['INGRESO']['item']."' AND TC = 'I' AND Periodo='".$_SESSION['INGRESO']['periodo']."'";
			$sql2 = "SELECT Codigo_Inv,Producto,TC,Valor_Total,Unidad,Stock_Actual,Cta_Inventario FROM Catalogo_Productos WHERE INV = 1 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 AND Producto LIKE '%".$query."%'";
     // print_r($sql2);die();
        $stmt = sqlsrv_query($cid, $sql2);
        $datos =  array();
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
	   	  
	   	  // print_r($costo);die();
          
         // $datos[]=['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'].','.$row['TC'].','.$row['Valor_Total'].','.$row['Cta_Inventario'],'text'=>utf8_encode($row['Producto'])];
            $datos[]=['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'].','.$row['TC'].','.$row['Valor_Total'].','.$row['Cta_Inventario'],'text'=>$row['Producto']];


		//$datos[]=['id'=>$row['Codigo_Inv'].'/'.$row['Unidad'],'text'=>utf8_encode($row['Producto']),'children'=>$this->lista_hijos($row['Codigo_Inv'],$query)];	
		   // $datos[]=['id'=>$row['Codigo_Inv'].'/'.$row['Unidad'],'text'=>$row['Producto'],'children'=>$this->lista_hijos($row['Codigo_Inv'],$query)];	
	   }
	   // print_r($datos);die();
       return $datos;
	}

	function lista_hijos($codigo,$query='')
	{
		$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS

// 	 $sql = "SELECT CP.Codigo_Inv,CP.Producto,CP.TC,TK.Costo as 'Valor_Total',CP.Unidad, SUM(Entrada-Salida) As Stock_Actual ,CP.Cta_Inventario
// FROM Catalogo_Productos As CP, Trans_Kardex AS TK WHERE CP.INV = 1 AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND CP.Item = '".$_SESSION['INGRESO']['item']."'AND LEN(CP.Cta_Inventario)>3 AND SUBSTRING(CP.Codigo_Inv,1, 5) = '".$codigo."'  AND CP.Producto LIKE '%".$query."%' AND TK.T<> 'A' AND CP.Periodo = TK.Periodo AND CP.Item = TK.Item AND CP.Codigo_Inv = TK.Codigo_Inv group by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,TK.Costo,CP.Cta_Inventario having SUM(TK.Entrada-TK.Salida) <> 0 
// order by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,CP.Cta_Inventario";
      $sql = "SELECT Codigo_Inv,Producto,TC,Valor_Total,Unidad,Stock_Actual,Cta_Inventario FROM Catalogo_Productos WHERE INV = 1 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 AND Codigo_Inv LIKE '".$codigo."%'";

     // if($query !='')
     // {
     // 	$sql .=" AND Producto LIKE '%".$query."%'"; 
     // }
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
	   	array_push($datos, ['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'].','.$row['TC'].','.$row['Valor_Total'].','.$row['Cta_Inventario'],'text'=>utf8_encode($row['Producto'])]);
	   	// array_push($datos,  ['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'].','.$row['TC'].','.$row['Valor_Total'].','.$row['Cta_Inventario'],'text'=>$row['Producto']]);
	   }
	   // print_r($datos);die();
       return $datos;

	}

		function lista_hijos_id($query)
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
      $sql = "SELECT CP.Codigo_Inv,CP.Producto,CP.TC,TK.Costo as 'Valor_Total',CP.Unidad, SUM(Entrada-Salida) As Stock_Actual ,CP.Cta_Inventario
FROM Catalogo_Productos As CP, Trans_Kardex AS TK WHERE CP.INV = 1 AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND CP.Item = '".$_SESSION['INGRESO']['item']."'AND LEN(CP.Cta_Inventario)>3 AND CP.Codigo_Inv LIKE '".$query."' AND TK.T<> 'A' AND CP.Periodo = TK.Periodo AND CP.Item = TK.Item AND CP.Codigo_Inv = TK.Codigo_Inv group by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,TK.Costo,CP.Cta_Inventario having SUM(TK.Entrada-TK.Salida) <> 0 
order by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,CP.Cta_Inventario";
   

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
	   	array_push($datos, ['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'],'text'=>utf8_encode($row['Producto'])]);
	   	// array_push($datos,  ['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'],'text'=>$row['Producto']]);
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

	function listar_cc_info($query='')
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo,Cuenta FROM Catalogo_Cuentas WHERE DG='D' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' ";
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
     $sql = "SELECT Codigo+','+TC as 'Codigo',Detalle FROM Catalogo_SubCtas WHERE TC='G' AND Nivel='00' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' ";
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
		// $datos[]=['id'=>$row['Codigo'],'text'=>utf8_encode($row['Detalle'])];
		$datos[]=['id'=>$row['Codigo'],'text'=>$row['Detalle']];		
	   }
       return $datos;
	}

	function listar_rubro_bajas($query='')
	{
	
	$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo+','+TC as 'Codigo',Detalle FROM Catalogo_SubCtas WHERE TC='G' AND Nivel='01' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' ";
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
		// $datos[]=['id'=>$row['Codigo'],'text'=>utf8_encode($row['Detalle'])];
		$datos[]=['id'=>$row['Codigo'],'text'=>$row['Detalle']];		
	   }
       return $datos;
	}
	
	function lista_entrega($fecha=false)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Asiento_K WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND Item = '".$_SESSION['INGRESO']['item']."' ";
         if($fecha)
         {
         	$sql .= " AND Fecha_Fab='".$fecha."'";
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
   function eliminar($codigo,$po)
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento_K WHERE CODIGO_INV = '".$codigo."' AND Item='".$_SESSION['INGRESO']['item']."' AND A_No='".$po."' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."'";
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

	 function eliminar_aiseto_K()
	{
		 $cid=$this->conn;
		$sql = "DELETE Asiento_K WHERE Item='".$_SESSION['INGRESO']['item']."' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."'";
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

	function cargar_datos_cuenta_datos($reporte_Excel=false)
	{
		$cid = $this->conn;
		  $sql = "SELECT Fecha_Fab as 'FECHA',CODIGO_INV AS 'CODIGO',PRODUCTO,CANT_ES AS 'CANT',C.Cuenta as 'Centro de costos',CS.Detalle as 'rubro',Consumos as 'bajas o desper',CS2.Detalle AS 'Baja por',Procedencia as 'Observaciones' FROM Asiento_K A 
LEFT JOIN Catalogo_Cuentas C ON A.CTA_INVENTARIO = C.Codigo 
LEFT JOIN Catalogo_SubCtas CS on A.SUBCTA = CS.Codigo 
LEFT JOIN Catalogo_SubCtas CS2 on A.Codigo_Dr = CS2.Codigo  WHERE  CodigoU = '".$_SESSION['INGRESO']['Id']."' AND A.Item = '".$_SESSION['INGRESO']['item']."' ";

       // print_r($sql);die();
   
       $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }

	   $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$result[] = $row;
		//echo $row[0];
	   }
	   if($reporte_Excel==false)
	   {
	   	  return $result;
	   }else
	   {
	   	 $stmt1 = sqlsrv_query($this->conn, $sql);
	     exportar_excel_generico($stmt1,"entrega de materia",null,$b);

	   }


	}
  function datos_asiento_debe($fecha)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CONTRA_CTA as 'cuenta',Fecha_Fab as 'fecha',TC,SUM(Consumos) AS 'Consumos',VALOR_UNIT FROM Asiento_K  WHERE Item =  '".$_SESSION['INGRESO']['item']."' AND CodigoU ='".$_SESSION['INGRESO']['Id']."' AND Fecha_Fab = '".$fecha."' GROUP BY CONTRA_CTA,Fecha_Fab,TC,Consumos,VALOR_UNIT";
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

	function datos_asiento_haber($fecha)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CTA_INVENTARIO as 'cuenta',Fecha_Fab as 'fecha',TC FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' AND Fecha_Fab = '".$fecha."' GROUP BY CTA_INVENTARIO,Fecha_Fab,TC";
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

	function desperdicios_debe($fecha)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo_Dr,Sum(Consumos*VALOR_UNIT) AS 'TOTAL',Fecha_Fab as 'fecha' FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU =  '".$_SESSION['INGRESO']['CodigoU']."'  AND LEN(Codigo_Dr)>1  AND Fecha_Fab = '".$fecha."' GROUP BY Codigo_Dr,Fecha_Fab";
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
	function desperdicios_haber($fecha)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT CTA_INVENTARIO,Sum(Consumos*VALOR_UNIT) AS 'TOTAL',Fecha_Fab as 'fecha' FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU =  '".$_SESSION['INGRESO']['CodigoU']."'  AND LEN(Codigo_Dr)>1  AND Fecha_Fab = '".$fecha."'  GROUP BY CTA_INVENTARIO,Fecha_Fab";
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


	function datos_asiento_SC($fecha)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CONTRA_CTA,SUBCTA,Fecha_Fab,TC FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '".$_SESSION['INGRESO']['Id']."' AND Fecha_Fab = '".$fecha."' GROUP BY CONTRA_CTA,Fecha_Fab,TC,SUBCTA";
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

	function cuenta_existente()
	{
		 $cid = $this->conn;
		 $sql="SELECT CP.Codigo as 'Codigo'  FROM Ctas_Proceso CP WHERE CP.Item = '".$_SESSION['INGRESO']['item']."' AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND CP.Detalle = 'Cta_Desperdicio'";
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
	   if (count($datos)>0) {
	   	   $sql1="SELECT Codigo FROM Catalogo_Cuentas WHERE Codigo = '".$datos[0]['Codigo']."' AND  Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND DG = 'D' ";

        // print_r($sql1);die();
        $stmt1 = sqlsrv_query($cid, $sql1);
        $datos1 =  array();
	   if( $stmt1 === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	    while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos1[]=$row1;	
	   }
	   if(count($datos1)>0)	   {

	     	$_SESSION['INGRESO']['CTA_DESPERDICIO'] = $datos1[0]['Codigo'];
	   }else
	   {
	   	 return -2;
	   }

	   }else
	   {
	   	 return -1;
	   }

	}

	function catalogo_cuentas($cuenta)
	{

		 $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Catalogo_Cuentas  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo = '".$cuenta."'";
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
	function catalogo_subcuentas($cuenta)
	{

		 $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Catalogo_SubCtas   WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo = '".$cuenta."'";
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

	function datos_comprobante()
	{
		$cid = $this->conn;
		$sql="SELECT * FROM Asiento WHERE CodigoU='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."' AND T_No = '60'";
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
	function insertar_trans_kardex($datos)
	{
		$resp = insert_generico("Trans_Kardex",$datos);
	    return $resp;
	}

	function stock_kardex($id)
	{
		$cid = $this->conn;
		$sql="SELECT SUM(Entrada-Salida) as 'stock' 
		FROM Trans_Kardex 
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND  Fecha <= '".date('Y-m-d')."' 
		AND Codigo_Inv ='".$id."' 
		AND T <> 'A' ";
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
	function costo_venta($codigo_inv)
	{
		$cid = $this->conn;
		$sql = "SELECT TOP 1 Codigo_Inv,Costo 
		FROM Trans_Kardex
		WHERE Fecha <= '".date('Y-m-d')."'
		AND Codigo_Inv = '".$codigo_inv."'
		AND Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
		AND T <> 'A'
		ORDER BY Fecha DESC,TP DESC, Numero DESC,ID DESC";
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
	function delete_SC_ASientos(){
		 $cid=$this->conn;
		$sql = "DELETE Asiento_SC WHERE  Item='".$_SESSION['INGRESO']['item']."' AND T_No='60' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."';";
		$sql.= "DELETE Asiento WHERE  Item='".$_SESSION['INGRESO']['item']."' AND T_No='60' AND CodigoU='".$_SESSION['INGRESO']['CodigoU']."';";
		//print_r($sql);die();
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