<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
include(dirname(__DIR__,3).'/lib/fpdf/reporte_de.php');
@session_start(); 
/**
 * 
 */
class lista_facturasM
{
	private $conn;	
	function __construct()
	{
		$this->conn = cone_ajax();
	}
 
   function facturas_emitidas_excel($codigo,$reporte_Excel=false)
   {
   	$cid = $this->conn;
		
		$sql ="SELECT T,TC,Serie,Autorizacion,Factura,Fecha,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social  FROM Facturas 
       WHERE CodigoC ='".$codigo."'
      AND Item = '".$_SESSION['INGRESO']['item']."'
       AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ORDER BY Fecha DESC"; 
      

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
	   	 $stmt1 = sqlsrv_query($cid, $sql);
	     exportar_excel_generico($stmt1,'Facturasemitidas',null,null);

	   }

   }

    function facturas_emitidas_tabla($codigo)
   {
   	$cid = $this->conn;
		
		$sql ="SELECT T,TC,Serie,Autorizacion,Factura,Fecha,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social,CodigoC,ID FROM Facturas 
		WHERE CodigoC ='".$codigo."'
		AND Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ORDER BY Fecha DESC"; 
		  $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
// print_r($sql);die();
	   $datos = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[] = $row;
		//echo $row[0];
	   }

      
       $botones[0] = array('boton'=>'Ver factura','icono'=>'<i class="fa fa-eye"></i>', 'tipo'=>'default', 'id'=>'Factura,Serie,CodigoC');
       // $botones[1] = array('boton'=>'Generar PDF','icono'=>'<i class="fa fa-file-pdf-o"></i>', 'tipo'=>'primary', 'id'=>'ID');
       // $botones[2] = array('boton'=>'Generar EXCEL','icono'=>'<i class="fa fa-file-excel-o"></i>', 'tipo'=>'info', 'id'=>'ID');

        $tbl = grilla_generica_new($sql,'Facturas',false,$titulo=false,$botones,$check=false,$imagen=false,1,1,1,400);
        
       // $tabla = grilla_generica($stmt,null,NULL,'1','2,4,clave');
       return array('datos'=>$datos,'tbl'=>$tbl);
   }

   function pdf_factura($cod,$ser,$ci)
   {
   	$id='factura_'.$ci;
   	$cid = $this->conn;
   	$sql="SELECT * 
   	FROM Facturas 
   	WHERE Serie='".$ser."' 
   	AND Factura='".$cod."' 
   	AND CodigoC='".$ci."' 
   	AND Item = '".$_SESSION['INGRESO']['item']."'
	AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";

   	$sql1="SELECT * 
   	FROM Detalle_Factura 
   	WHERE Factura = '".$cod."' 
   	AND CodigoC='".$ci."' 
   	AND Item = '".$_SESSION['INGRESO']['item']."'
	AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";

       $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
// print_r($sql1);die();
	   $datos_fac = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos_fac[] = $row;
		//echo $row[0];
	   }
	     $stmt1 = sqlsrv_query($cid, $sql1);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }

	   $detalle_fac = array();	
	   while( $row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC) ) 
	   {
		$detalle_fac[] = $row1;
		//echo $row[0];
	   }
        $datos_cli_edu=$this->cliente_matri($ci);
        if(empty($datos))
        {
        	$datos_cli_edu = null;
        }
	   if($datos_cli_edu != '')
	   {
	   		 imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,'matr',$id,null,'factura',null,null);
	   }else
	   {

        $datos_cli_edu=$this->Cliente($ci);
        imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,$id,null,'factura',null,null);
	   }

   }

   function cliente_matri($codigo)
   {
   	$cid=$this->conn;
	   $sql = "SELECT * FROM Clientes_Matriculas WHERE Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' and Codigo = '".$codigo."'";

		// print_r($sql);die();
	   $stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }

	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	      {
	    	$result[] = $row;
		    //echo $row[0];
	      }

	     // $result =  encode($result);
	      // print_r($result);
	      return $result;
   }

  
}
?>