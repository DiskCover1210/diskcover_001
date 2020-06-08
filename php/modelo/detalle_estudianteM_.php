<?php
//include(dirname(__DIR__).'/funciones/funciones.php');

//require(dirname(__DIR__,2).'/lib/fpdf/cabecera_pdf.php');
include(dirname(__DIR__,2).'/lib/fpdf/reporte_de.php');
@session_start(); 
/**
 * 
 */
class detalle_estudianteM
{
	private $conn;	
	function __construct()
	{
		$this->conn = cone_ajax();
		$this->pdf = new FPDF();
	}


	function cargar_cursos()
	{
	   $cid=$this->conn;
       $sql = "SELECT * FROM Catalogo_Cursos WHERE 
       Item='".$_SESSION['INGRESO']['item']."' AND 
       Periodo = '".$_SESSION['INGRESO']['periodo']."' AND 
       LEN(Curso)>4 ORDER bY Curso;";

      //echo $sql;
     //  die();
       
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
		//$result[] = array('Curso'=>$row['Curso'],'Descripcion'=>utf8_encode($row['Descripcion']));
		$result[] = $row;
		//echo $row[0];
	}

       //print_r($result);
	return $result;
 //print_r($result);
	}

	function login($usu,$pass,$nuevo)
	{

//	print_r($usu.'--'.$pass.'--'.$nuevo);
	   $cid=$this->conn;
	   if($nuevo=='false')
	   {
		$sql = "SELECT C.ID,C.Grupo,Archivo_Foto,CI_RUC,CI_P,Ocupacion_M,Ocupacion_P,C.Codigo,Cliente,Direccion,Sexo,Email,Procedencia,Matricula
,Representante_Alumno,Nacionalidad,Prov,Seccion,Curso_Superior,C.Fecha_N,Fecha_M,Fecha,Cedula,Celular,Especialidad,Telefono,Observaciones,Nombre_Padre,CI_P,
Nacionalidad_P,Lugar_Trabajo_P,Telefono_Trabajo_P,Celular_P,Profesion_P,Email_P,Nombre_Madre,CI_M,Nacionalidad_M,
Lugar_Trabajo_M,Telefono_Trabajo_M,Celular_M,Profesion_M,Email_M,Representante_Alumno,CI_R,Profesion_R,Ocupacion_R,
C.Telefono_R,Telefono_RS,Lugar_Trabajo_R,Email_R,Email_R,Matricula_No,Folio_No,Ciudad,DireccionT,Email2 FROM Clientes as C
			INNER JOIN Clientes_Matriculas ON c.CI_RUC = Clientes_Matriculas.Codigo		
		INNER JOIN Catalogo_Cursos ON c.Grupo = Catalogo_Cursos.Curso
	WHERE FA = 'TRUE' AND CI_RUC = '".$usu."' AND Clave='".$pass."'";
	   }else
	   {
	   	$sql = "SELECT * FROM Clientes
		INNER JOIN Clientes_Matriculas ON Clientes.CI_RUC = Clientes_Matriculas.Codigo	
		WHERE FA = 'TRUE' AND CI_RUC = '".$usu."' AND Clave='".$pass."'";
	   }
		// AND PASS='".$pass."'";

		//echo $sql;
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
	     // print_r($result);
	      if(empty($result))
	      {
	      		$sql = "SELECT * FROM Clientes
		        INNER JOIN Clientes_Matriculas ON Clientes.CI_RUC = Clientes_Matriculas.Codigo	
		         WHERE FA = 'TRUE' AND CI_RUC = '".$usu."' AND Clave='".$pass."'";
		          //print_r($sql);
		          $stmt1 = sqlsrv_query($cid, $sql);
	              if( $stmt1 === false)  
	             {  
		          echo "Error en consulta PA.\n";  
		         return '';
		         die( print_r( sqlsrv_errors(), true));  
	              }   

	            $result = array();	
	            while( $row = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC)) 
	              {
	    	        $result[] = $row;
	              }
	             // print_r($result);
	      return $result;
	      }else
	      {

         // print_r($result);
	      return $result;	
	      }
	   
	}


	function img_guardar($name,$codigo)
	{
		 $cid=$this->conn;
		$sql = "UPDATE Clientes SET Archivo_foto = '".$name."' WHERE Codigo='".$codigo."'";
		//echo $sql;
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

   function actualizar_datos($datos,$tabla,$campoWhere,$valorwhere)
   { 

   	 $campos_db = dimenciones_tabla($tabla);
	$cid=$this->conn;
   	$sql = 'UPDATE '.$tabla.' SET '; 
   	 $set='';
   	foreach ($datos as $key => $value) {
   		foreach ($campos_db as $key => $value1) 
   		{
   			if($value1->COLUMN_NAME==$value['campo'])
   				{
   					if($value1->CHARACTER_MAXIMUM_LENGTH != '' && $value1->CHARACTER_MAXIMUM_LENGTH != null)
   						{
   							$set .=$value['campo']."='".substr($value['dato'],0,$value1->CHARACTER_MAXIMUM_LENGTH)."',";
   				        }else
   				        {
   				        	$set .=$value['campo']."='".$value['dato']."',";
   				        }
   	            }

   		}
   		//print_r($value['campo']);
   	}
   	$set = substr($set,0,-1);
   	$where = "WHERE ".$campoWhere."='".$valorwhere."'";
   	$sql = $sql.$set.$where;
   //	print_r($sql);	
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

   function codigo_matricula($curso)
   {

	   $cid=$this->conn;
       $sql = "SELECT  COUNT(T) as num  FROM Clientes_Matriculas WHERE Grupo_No = '".$curso."';";

      //echo $sql;
     //  die();
       
    $stmt = sqlsrv_query($cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	}
	$result = array();

	 $row = sqlsrv_num_rows($stmt);
	 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
	      {
	    	$result[] = $row;
	      }
	$row = $result[0]['num']+1;
	if($row<10)
	{
		$row = '00'.$row;
	}else if($row>9 && $row < 100)
	{
	  $row = '0'.$row;
	}
	//print_r($row);

	return $row;
 //print_r($result);

   }

   function institucion_data()
   {
   	$cid=$this->conn;
	   $sql = "SELECT * from Catalogo_Periodo_Lectivo where Item='".$_SESSION['INGRESO']['item']."'";
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
	     
	      return $result;
   }

  function Empresa_data()
   {
   	$cid=$this->conn;
	   $sql = "SELECT * FROM Empresas where Item='".$_SESSION['INGRESO']['item']."'";
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
		
		$sql ="SELECT T,TC,Serie,Autorizacion,Factura,Fecha,SubTotal,Con_IVA,IVA,Descuento+Descuento2 as Descuentos,Total_MN as Total,Saldo_MN as Saldo,RUC_CI,TB,Razon_Social FROM Facturas 
		WHERE CodigoC ='".$codigo."'
		AND Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ORDER BY Fecha DESC"; 
      
       //echo $sql;
       $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }
        
       $tabla = grilla_generica($stmt,null,NULL,'1','2,4,clave');
       return $tabla;
   }

   function pdf_factura($cod,$ser,$ci)
   {
   	$id='factura_'.$ci;
   	$cid = $this->conn;
   	$sql="SELECT * FROM Facturas WHERE Serie='".$ser."' and Factura='".$cod."' and CodigoC='".$ci."' 
   	AND Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";
   	$sql1="SELECT * from Detalle_Factura WHERE Factura = '".$cod."' AND CodigoC='".$ci."' AND Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' ";

       $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }

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
	   if($datos_cli_edu != '')
	   {
	   		 imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,'matr',$id,null,'factura',null,null);
	   }else
	   {

        $datos_cli_edu=$this->Cliente($ci);
        imprimirDocEle_fac($datos_fac,$detalle_fac,$datos_cli_edu,$id,null,'factura',null,null);
	   }

   }

  function Cliente($cod)
   {
   	$cid=$this->conn;
	   $sql = "SELECT * from Clientes WHERE  Codigo= '".$cod."'";
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

  function cliente_matri($codigo)
   {
   	$cid=$this->conn;
	   $sql = "SELECT * FROM Clientes_Matriculas WHERE Item = '".$_SESSION['INGRESO']['item']."'
		AND Periodo =  '".$_SESSION['INGRESO']['periodo']."' and Codigo = '".$codigo."'";
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