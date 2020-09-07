<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class catalogoCtaM
{	
		
    private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}


	function cargar_datos_cuenta_datos($OpcT,$OpcG,$OpcD,$txt_CtaI,$txt_CtaF,$reporte_Excel=false)
	{
		$cid = $this->conn;
		if($txt_CtaI=='')
		{
			$txt_CtaI=1;
		}
		if($txt_CtaF=='')
		{
			$txt_CtaF=9;
		}

		$sql ="SELECT Clave,TC,ME,DG,Codigo,Cuenta,Presupuesto,Codigo_Ext 
       FROM Catalogo_Cuentas 
       WHERE Cuenta <> 'Ninguno' 
       AND Codigo BETWEEN '".$txt_CtaI."' and '".$txt_CtaF."' 
       AND Item = '".$_SESSION['INGRESO']['item']."'
       AND Periodo =  '".$_SESSION['INGRESO']['periodo']."'"; 
       if($OpcG=='true')
       {
       	 $sql.=" AND DG='G'";
       }
       if($OpcD=='true')
       {
       	 $sql.=" AND DG='D'";
       }
       $sql.='ORDER BY Codigo';

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
	     exportar_excel_generico($stmt1,null,null,$b);

	   }


	}

	function cargar_datos_cuenta_tabla($OpcT,$OpcG,$OpcD,$txt_CtaI,$txt_CtaF)
	{
		$cid = $this->conn;
		if($txt_CtaI=='')
		{
			$txt_CtaI=1;
		}
		if($txt_CtaF=='')
		{
			$txt_CtaF=9;
		}

		$sql ="SELECT Clave,TC,ME,DG,Codigo,Cuenta,Presupuesto,Codigo_Ext 
       FROM Catalogo_Cuentas 
       WHERE Cuenta <> 'Ninguno' 
       AND Codigo BETWEEN '".$txt_CtaI."' and '".$txt_CtaF."' 
       AND Item = '".$_SESSION['INGRESO']['item']."'
       AND Periodo =  '".$_SESSION['INGRESO']['periodo']."'"; 
       if($OpcG=='true')
       {
       	 $sql.=" AND DG='G'";
       }
       if($OpcD=='true')
       {
       	 $sql.=" AND DG='D'";
       }
       $sql.='ORDER BY Codigo';

       //echo $sql;
       $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }

	  
        $tabla = grilla_generica($stmt,null,NULL,'1',null,null,null,true);

       return $tabla;

	}

	function buscar_cuenta($cuenta,$item=false)
	{
		$cid = $this->conn;
		$datos =array();
		$sql = "SELECT Codigo,Cuenta from Catalogo_Cuentas WHERE Periodo ='".$_SESSION['INGRESO']['periodo']."' AND Codigo+''+Cuenta LIKE '%".$cuenta."%'";
		if($item)
		{
			$sql.=" and Item ='".$item."'";
		}else
		{
			$sql.=" and Item ='".$_SESSION['INGRESO']['item']."'";
		}

		
		   $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
	  // print_r($sql);
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos[] = $row;
		//echo $row[0];
	   }
	   
	  return $datos;

	}

}
?>