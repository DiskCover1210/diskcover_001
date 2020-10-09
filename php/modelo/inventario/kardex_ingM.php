<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');


/**
 * 
 */
class kardex_ingM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}
	function familias($query='')
	{
		 $cid = $this->conn;
	  $sql = "SELECT Codigo_Inv,Codigo_Inv+'  '+ Producto As NomProd 
      FROM Catalogo_Productos 
      WHERE Item = '".$_SESSION['INGRESO']['item']."' 
      AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      AND TC = 'I' ";
      if($query != '')
      {
      	$sql.= " AND Producto LIKE '%".$query."%' ";
      }
      $sql.=" ORDER BY Codigo_Inv ";
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
		$datos[]=['id'=>$row['Codigo_Inv'],'text'=>utf8_encode($row['NomProd'])];	
	   }
       return $datos;

	}
	function Producto($fami,$query='',$opcion)
	{
		 $cid = $this->conn;
		 //CodigoInv = SinEspaciosIzq(DCTInv.Text)
		 $sql = "SELECT *,(Codigo_Inv + '  ' + Producto) As NomProd
		 FROM Catalogo_Productos
		 WHERE Item = '".$_SESSION['INGRESO']['item']."'
		 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		 AND SUBSTRING(Codigo_Inv,1,".strval(strlen($fami)).") = '".$fami."'
		 AND LEN(Cta_Inventario) > 1 
		 AND TC = 'P' ";
		  if($query != '')
      {
      	$sql.= " AND Producto LIKE '%".$query."%' ";
      }
      if($opcion==1)
      {
      	$sql.= ' ORDER BY Producto';
      }else
      {
      	$sql.=" ORDER BY Codigo_Inv ";
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
		$datos[]=['id'=>$row['Codigo_Inv'],'text'=>utf8_encode($row['NomProd'])];	
	   }
       return $datos;

	}

	function dato_empresa()
	{
		$cid = $this->conn;
	    $sql= "SELECT * FROM Empresas WHERE Item = '".$_SESSION['INGRESO']['item']."'";
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

	function Trans_Kardex()
	{
		$cid = $this->conn;
	    $sql= "SELECT Numero
               FROM Trans_Kardex
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               AND TP = 'CD'
               AND Entrada > 0
               GROUP BY Numero
               ORDER BY Numero DESC ";
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


	function bodega()
	{
		$cid = $this->conn;
	    $sql= "SELECT *
               FROM Catalogo_Bodegas
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               ORDER BY Bodega ";
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

	function marca()
	{
		$cid = $this->conn;
	    $sql= "SELECT *
               FROM Catalogo_Marcas
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               ORDER BY Marca ";
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

	function contracuenta($query)
	{
		$cid = $this->conn;
	    $sql="SELECT Codigo,Cuenta + SPACE(67-LEN(Cuenta)) + Codigo As Nomb_Cta
             FROM Catalogo_Cuentas
             WHERE Item = '".$_SESSION['INGRESO']['item']."'
             AND Periodo ='".$_SESSION['INGRESO']['periodo']."'
             AND TC IN ('RP','C','P','HC','I','G')
             AND DG = 'D' ";
             if($query!='')
             {
             	$sql.=" AND Cuenta LIKE '%".$query."%' ";
             }
             $sql.= "ORDER BY Codigo,Cuenta";
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
		//$datos[]=$row;			
		$datos[]=['id'=>$row['Codigo'],'text'=>utf8_encode($row['Nomb_Cta'])];	
		//$datos[]=['id'=>$row['Codigo'],'text'=>$row['Nomb_Cta']];	
	   }
       return $datos;
	}


	function LeerCta($CodigoCta)
	{

		$cid = $this->conn;
		$Cuenta = G_NINGUNO;
		$Codigo = G_NINGUNO;
		$TipoCta = "G";
		$SubCta = "N";
		$TipoPago = "01";
		$Moneda_US = False;
		$datos= array();
		if (strlen(substr($CodigoCta, 1, 1)) >= 1){
			$sql = "SELECT Codigo, Cuenta, TC, ME, DG, Tipo_Pago
              FROM Catalogo_Cuentas 
              WHERE Codigo = '" .$CodigoCta. "'
              AND Item = '".$_SESSION['INGRESO']['item']."' 
              AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
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
               		//$datos=['id'=>$row['Codigo'],'text'=>utf8_encode($row['Nomb_Cta'])];	
               		//$datos[]=['id'=>$row['Codigo'],'text'=>$row['Nomb_Cta']];	
               	}
       }

       return $datos;
    }

  function ListarProveedorUsuario($TipoSubCta,$Contra_Cta,$query)
	{
		$cid = $this->conn;
		switch ($TipoSubCta) {
			case 'RP':
				   $sql = "SELECT C.Cliente,CR.Codigo,C.CI_RUC,C.Direccion,C.Telefono,C.TD,'.' As Cta,0 As Importaciones,C.CI_RUC,C.Grupo,'P' As TipoBenef
                   FROM Clientes As C,Catalogo_Rol_Pagos As CR
                   WHERE CR.Item = '".$_SESSION['INGRESO']['item']."' 
                   AND CR.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                   AND C.Codigo = CR.Codigo
                   ORDER BY C.Cliente ";
				break;
			case ($TipoSubCta=='P' or $TipoSubCta=='C'):				
				    $sql= "SELECT C.Cliente,C.Codigo,C.CI_RUC,C.Direccion,C.Telefono,C.TD,CP.Cta,CP.Importaciones,C.CI_RUC,C.Grupo,'P' As TipoBenef
                    FROM Clientes As C,Catalogo_CxCxP As CP
                    WHERE CP.TC = '" .$TipoSubCta."'
                    AND CP.Item = '".$_SESSION['INGRESO']['item']."' 
                    AND CP.Periodo ='".$_SESSION['INGRESO']['periodo']."'
                    AND CP.Cta = '".$Contra_Cta."'
                    AND C.Codigo = CP.Codigo
                    GROUP BY C.Cliente,C.Codigo,C.CI_RUC,C.Direccion,C.Telefono,C.TD,CP.Cta,CP.Importaciones,C.CI_RUC,C.Grupo
                    ORDER BY C.Cliente ";
				break;
			case ($TipoSubCta== 'G' or $TipoSubCta== 'I'):
				 //$OpcX.value = 1
                   $sql = "SELECT CS.Detalle As Cliente,CS.*,'O' As TD,'.' As Cta,0 As Importaciones,'9999999999999' As CI_RUC,'999999' As Grupo,'X' As TipoBenef
                   FROM Catalogo_SubCtas As CS
                   WHERE CS.Item ='".$_SESSION['INGRESO']['item']."' 
                   AND CS.Periodo = '".$_SESSION['INGRESO']['periodo']."'
                   AND CS.TC = '".$TipoSubCta."'
                   ORDER BY Detalle ";
				break;
			default:
			      $sql = "SELECT Cliente,Codigo,CI_RUC,Grupo,Direccion,Telefono,TD,'.' As Cta,0 As Importaciones,'X' As TipoBenef
                  FROM Clientes
                  WHERE Grupo = '.'
                  ORDER BY Cliente ";
				break;
		}
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
               		//$datos[]=$row;
               		$datos[]=['id'=>$row['Codigo'],'text'=>utf8_encode($row['Cliente'])];	
               		//$datos[]=['id'=>$row['Codigo'],'text'=>$row['Nomb_Cta']];	
               	}

       return $datos;
    }





	function borrar_asientos($Trans_No,$B_Asiento=false)
	{
		$sql = '';
		if($Trans_No <=0)
		{
			$Trans_No = 1;
		}
		 if($B_Asiento)
		 {
		 	$sql.= "DELETE   FROM Asiento WHERE Item = '".$_SESSION['INGRESO']['item']."'  AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'
          AND T_No = ".$Trans_No;
		 }

	   $sql.= "DELETE FROM Asiento_SC WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_B WHERE Item = '".$_SESSION['INGRESO']['item']."'  AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "' AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_R WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_RP WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_K WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

       $sql.= "DELETE FROM Asiento_P WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Air WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Compras WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Exportaciones  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Importaciones  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

         $sql.= "DELETE FROM Asiento_Ventas  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']. "'  AND T_No = ".$Trans_No.';';

          $cid=$this->conn;
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

function dtaAsiento_sc($Trans_No){

     $cid = $this->conn;
	$sql= "SELECT * 
       FROM Asiento_SC 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_b($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_B 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_air($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Air 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_compras($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Compras 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_ventas($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Ventas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_impo($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Importaciones 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_expo($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_Exportaciones 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento_k($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento_K 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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
 function dtaAsiento($Trans_No){

     $cid = $this->conn;
  $sql= "SELECT * 
       FROM Asiento 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND CodigoU = '" .$_SESSION['INGRESO']['CodigoU']."' 
       AND T_No = ".$Trans_No;
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

   function codigos($Concepto)
   {
   	 $cid = $this->conn;
  $sql= "SELECT Numero, ID 
             FROM Codigos 
             WHERE Concepto = '".$Concepto."' 
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
             AND Item = '".$_SESSION['INGRESO']['item']."'";
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

   function ingresar_codigo($NumEmpA,$sql,$NumCodigo)
   {
   	  
		 $cid=$this->conn;
		$sql = "INSERT INTO Codigos (Periodo,Item,Concepto,Numero) VALUES ('".$_SESSION['INGRESO']['periodo']."' ,'".$NumEmpA."','".$sql."',".$NumCodigo.") ";
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

   function actualizar_codigo($NumEmpA,$sql)
   {

		 $cid=$this->conn;
   	$sql  = "UPDATE Codigos SET Numero = Numero + 1 WHERE Concepto = '".$sql."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$NumEmpA."' ";
	
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