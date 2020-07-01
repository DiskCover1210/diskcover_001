<?php
include(dirname(__DIR__).'/funciones/funciones.php');//
@session_start(); 

/**
 * 
 */
class Saldo_fac_sub_C 
{
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}
	function mensaje()
	{
		$lista =array();
		for ($i=0; $i <10 ; $i++) { 
			$lista[] = $i;
		}
	  return $lista;

		//var_dump($lista);

	}
	function select_cta($tipocuenta)
	{
	    $cid=$this->conn;
		$sql = "SELECT (TS.Cta+' '+CC.Cuenta) As Nombre_Cta
       FROM Catalogo_Cuentas As CC, Trans_SubCtas As TS 
       WHERE CC.Item = '".$_SESSION['INGRESO']['item']."'
       AND CC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND CC.TC = '".$tipocuenta."'
       AND CC.Codigo = TS.Cta 
       AND CC.Item = TS.Item 
       AND CC.Periodo = TS.Periodo
       AND CC.TC = TS.TC 
       GROUP BY TS.Cta,CC.Cuenta
       ORDER BY TS.Cta ";

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
		$result[] = $row;
		//echo $row[0];
	}

       return $result;

	}
	function select_det($tipocuenta)
	{
		$sql = "SELECT Detalle_SubCta FROM Trans_SubCtas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TC = '".$tipocuenta."'
       GROUP BY Detalle_SubCta
       ORDER BY Detalle_SubCta ";
    $stmt = sqlsrv_query($this->conn, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
		 return ''; 
	}

	$result = array();	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	{
		$result[] = $row;
	}
      return $result;
	//return null;
	}

	function select_beneficiario($tipocuenta)
	{
		if($tipocuenta=='C' || $tipocuenta=='P')
		{
         $sql = "SELECT C.Cliente As Cliente,TS.Codigo as Codigo  FROM Trans_SubCtas As TS,Clientes As C
              WHERE TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              AND TS.TC = '".$tipocuenta."'
              AND TS.Codigo = C.Codigo 
              GROUP BY C.Cliente,TS.Codigo 
              ORDER BY C.Cliente,TS.Codigo ";
           }
           else if($tipocuenta=='G' || $tipocuenta =='I' || $tipocuenta=='CC')
           {
          // 	print_r('expression');
            $sql = "SELECT C.Detalle As Cliente,TS.Codigo as Codigo FROM Trans_SubCtas As TS,Catalogo_SubCtas As C 
              WHERE TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
              AND TS.TC = '".$tipocuenta."'
              AND TS.Codigo = C.Codigo 
              AND TS.TC = C.TC 
              AND TS.Item = C.Item 
              AND TS.Periodo = C.Periodo 
              GROUP BY C.Detalle,TS.Codigo 
              ORDER BY C.Detalle,TS.Codigo ";
          }
        // echo $sql;
        $stmt = sqlsrv_query($this->conn, $sql);
        if( $stmt === false)
        {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
	    }
	    $result = array();
	    $i=0;
	    while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC))
	    {
	    	$result[] = array('Cliente'=>utf8_encode($row['Cliente']),'Codigo'=>$row['Codigo']);
	    		$i+1;
	     }
       //print_r($result);
       return $result;



	}

	function consulta_c_p_datos($tipocuenta,$ChecksubCta,$OpcP,$CheqCta,$CheqDet,$CheqIndiv,$fechaini,$fechafin,$Cta,$CodigoCli,$DCDet,$reporte=false)
	{

         $sql = "SELECT CC.Cuenta,C.Cliente,C.Telefono,TS.Factura,MIN(TS.Fecha) As Fecha_Emi,MIN(TS.Fecha_V) As Fecha_Ven,";
         if($ChecksubCta == 'true')
         {
         	$sql.= "TS.Detalle_SubCta As Beneficiario,";
         }
         if($tipocuenta == 'C')
         {
         	 $sql.= "SUM(TS.Debitos) As Total,SUM(TS.Creditos) As Abonos,SUM(TS.Debitos-TS.Creditos) As Saldo,";
                $SQL1 = "HAVING SUM(TS.Debitos-TS.Creditos) ";
         	
         }
         if($tipocuenta == 'P')
         {
         	$sql.="SUM(TS.Creditos) As Total,SUM(TS.Debitos) As Abonos,SUM(TS.Creditos-TS.Debitos) As Saldo,";
            $SQL1 = "HAVING SUM(TS.Creditos-TS.Debitos) ";

         }
        //  If OpcP.value Then SQL1 = SQL1 & " <> 0 " Else SQL1 = SQL1 & " = 0 "
         if($OpcP=='true')
         {
         	$SQL1.=" <> 0 ";
         }else
         {
         	$SQL1.=" = 0 ";
         }
        
        $sql.="TS.TC,TS.Codigo,TS.Cta 
              FROM Clientes As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS
              WHERE TS.Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
              AND TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
              AND TS.TC = '".$tipocuenta."' ";

         // If CheqCta.value = 1 Then sSQL = sSQL & "AND CC.Codigo = '" & Cta & "' "    

        if($CheqCta =='true')
        {
        	$sql.="AND CC.Codigo = '".$Cta."' ";
        }

        // If CheqIndiv.value = 1 Then sSQL = sSQL & "AND TS.Codigo = '" & CodigoCli & "' "
        if($CheqIndiv=='true')
        {
        	$sql.="AND TS.Codigo = '".$CodigoCli."' ";
        }

        // If CheqDet.value = 1 Then sSQL = sSQL & "AND TS.Detalle_SubCta = '" & DCDet & "' "
        if($CheqDet =='true')
        {
        	"AND TS.Detalle_SubCta = '".$DCDet."' ";
        }

         $sql .="AND TS.Codigo = C.Codigo AND TS.Cta = CC.Codigo AND TS.Item = CC.Item AND TS.Periodo = CC.Periodo ";

         if($ChecksubCta =='true')
         {
         	$sql.="GROUP BY C.Cliente,TS.Codigo,CC.Cuenta,TS.Factura,C.Telefono,TS.Detalle_SubCta,TS.TC,TS.Cta "
         	      .$SQL1.
         	      "ORDER BY CC.Cuenta,C.Cliente,TS.Detalle_SubCta,TS.Factura ";
         }else
         {
         	$sql.="GROUP BY C.Cliente,TS.Codigo,CC.Cuenta,TS.Factura,C.Telefono,TS.TC,TS.Cta "
         	      .$SQL1.
         	       "ORDER BY CC.Cuenta,C.Cliente,TS.Factura ";

         }
        
       // echo $sql;
        $stmt = sqlsrv_query($this->conn, $sql);
        if( $stmt === false)
        {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
		 return ''; 
	    }
	    $datos=array();
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
	    {
	    	$datos[] = $row;
	    }
	    if($reporte==false)
	    {
          return $datos;
	    }else
	    {
            $stmt1 = sqlsrv_query($this->conn, $sql);
	    	exportar_excel_generico($stmt1,null,null,$b);
	    }
       //return $result;*/
       //print_r($stmt);
     
	}
	
	function consulta_c_p_tabla($tipocuenta,$ChecksubCta,$OpcP,$CheqCta,$CheqDet,$CheqIndiv,$fechaini,$fechafin,$Cta,$CodigoCli,$DCDet)
	{

         $sql = "SELECT CC.Cuenta,C.Cliente,C.Telefono,TS.Factura,MIN(TS.Fecha) As Fecha_Emi,MIN(TS.Fecha_V) As Fecha_Ven,";
         if($ChecksubCta == 'true')
         {
         	$sql.= "TS.Detalle_SubCta As Beneficiario,";
         }
         if($tipocuenta == 'C')
         {
         	 $sql.= "SUM(TS.Debitos) As Total,SUM(TS.Creditos) As Abonos,SUM(TS.Debitos-TS.Creditos) As Saldo,";
                $SQL1 = "HAVING SUM(TS.Debitos-TS.Creditos) ";
         	
         }
         if($tipocuenta == 'P')
         {
         	$sql.="SUM(TS.Creditos) As Total,SUM(TS.Debitos) As Abonos,SUM(TS.Creditos-TS.Debitos) As Saldo,";
            $SQL1 = "HAVING SUM(TS.Creditos-TS.Debitos) ";

         }
        //  If OpcP.value Then SQL1 = SQL1 & " <> 0 " Else SQL1 = SQL1 & " = 0 "//
         if($OpcP=='true')
         {
         	$SQL1.=" <> 0 ";
         }else
         {
         	$SQL1.=" = 0 ";
         }
        
        $sql.="TS.TC,TS.Codigo,TS.Cta 
              FROM Clientes As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS
              WHERE TS.Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
              AND TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
              AND TS.TC = '".$tipocuenta."' ";

         // If CheqCta.value = 1 Then sSQL = sSQL & "AND CC.Codigo = '" & Cta & "' "    

        if($CheqCta =='true')
        {
        	$sql.="AND CC.Codigo = '".$Cta."' ";
        }

        // If CheqIndiv.value = 1 Then sSQL = sSQL & "AND TS.Codigo = '" & CodigoCli & "' "
        if($CheqIndiv=='true')
        {
        	$sql.="AND TS.Codigo = '".$CodigoCli."' ";
        }

        // If CheqDet.value = 1 Then sSQL = sSQL & "AND TS.Detalle_SubCta = '" & DCDet & "' "
        if($CheqDet =='true')
        {
        	"AND TS.Detalle_SubCta = '".$DCDet."' ";
        }

         $sql .="AND TS.Codigo = C.Codigo AND TS.Cta = CC.Codigo AND TS.Item = CC.Item AND TS.Periodo = CC.Periodo ";

         if($ChecksubCta =='true')
         {
         	$sql.="GROUP BY C.Cliente,TS.Codigo,CC.Cuenta,TS.Factura,C.Telefono,TS.Detalle_SubCta,TS.TC,TS.Cta "
         	      .$SQL1.
         	      "ORDER BY CC.Cuenta,C.Cliente,TS.Detalle_SubCta,TS.Factura ";
         }else
         {
         	$sql.="GROUP BY C.Cliente,TS.Codigo,CC.Cuenta,TS.Factura,C.Telefono,TS.TC,TS.Cta "
         	      .$SQL1.
         	       "ORDER BY CC.Cuenta,C.Cliente,TS.Factura ";

         }
        
        //echo $sql;
        $stmt = sqlsrv_query($this->conn, $sql);
        $tabla = grilla_generica($stmt,null,NULL,'1',null,null,null,true);
        if( $stmt === false)
        {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
		 return ''; 
	    }
	    
       return $tabla;
       //print_r($result);
     
	}


	 function consulta_ing_egre_datos($tipocuenta,$ChecksubCta,$OpcP,$CheqCta,$CheqDet,$CheqIndiv,$fechaini,$fechafin,$Cta,$CodigoCli,$DCDet,$reporte=false)
   {
   	   $sql= "SELECT CC.Cuenta,C.Detalle As Sub_Modulos,MIN(TS.Fecha) As Fecha_Emi,";

       //If CheqDSubCta.value = 1 Then sSQL = sSQL & "TS.Detalle_SubCta As Beneficiario,"
   	   if($ChecksubCta=='true')
   	   {
   	   	$sql.="TS.Detalle_SubCta As Beneficiario,";

   	   }
   	   /*Select Case TipoCta
           Case "I"
                sSQL = sSQL & "SUM(TS.Creditos) As Total,"
           Case "G"
                sSQL = sSQL & "SUM(TS.Debitos) As Total,"
         End Select*/

   	   if($tipocuenta =='I')
   	   {
   	   	$sql.="SUM(TS.Creditos) As Total,";
   	   }
   	   if($tipocuenta=='G')
   	   {
   	   	 $sql.="SUM(TS.Debitos) As Total,";
   	   }

       /*  sSQL = sSQL & "TS.TC,TS.Codigo,TS.Cta " _
              & "FROM Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS " _
              & "WHERE TS.Fecha BETWEEN #" & FechaInicial & "# AND #" & FechaFinal & "# " _
              & "AND TS.Item = '" & NumEmpresa & "' " _
              & "AND TS.Periodo = '" & Periodo_Contable & "' " _
              & "AND TS.TC = '" & TipoCta & "' "*/
        
        $sql.= "TS.TC,TS.Codigo,TS.Cta  FROM Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS
              WHERE TS.Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
              AND TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
               AND TS.TC = '".$tipocuenta."'";

         //If CheqCta.value = 1 Then sSQL = sSQL & "AND CC.Codigo = '" & Cta & "' "
         if($CheqCta=='true')
         {
         	$sql.="AND CC.Codigo = '".$Cta. "' ";
         }

        // If CheqIndiv.value = 1 Then sSQL = sSQL & "AND TS.Codigo = '" & CodigoCli & "' "
          if($CheqIndiv=='true')
          {
          	$sql.= "AND TS.Codigo = '".$CodigoCli."' ";
          }

         //If CheqDet.value = 1 Then sSQL = sSQL & "AND TS.Detalle_SubCta = '" & DCDet & "' "
          if($CheqDet=='true')
          {
          	$sql.="AND TS.Detalle_SubCta = '".$DCDet."'";
          }

          $sql.="AND TS.Codigo = C.Codigo 
              AND TS.Cta = CC.Codigo
              AND TS.Item = CC.Item 
              AND TS.Periodo = CC.Periodo
              GROUP BY CC.Cuenta,C.Detalle,TS.Detalle_SubCta,TS.TC,TS.Codigo,TS.Cta
              ORDER BY CC.Cuenta,C.Detalle,TS.Detalle_SubCta ";
       // echo $sql;       

          $stmt = sqlsrv_query($this->conn, $sql);
        if( $stmt === false)
        {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
		 return ''; 
	    }
	    $datos = array();
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
	    {
	    	$datos[] = $row;
	     }
       if($reporte==false)
	    {
          return $datos;
	    }else
	    {
            $stmt1 = sqlsrv_query($this->conn, $sql);
	    	exportar_excel_generico($stmt1,null,null,$b);
	    }

   }



   function consulta_ing_egre_tabla($tipocuenta,$ChecksubCta,$OpcP,$CheqCta,$CheqDet,$CheqIndiv,$fechaini,$fechafin,$Cta,$CodigoCli,$DCDet)
   {
   	   $sql= "SELECT CC.Cuenta,C.Detalle As Sub_Modulos,MIN(TS.Fecha) As Fecha_Emi,";

       //If CheqDSubCta.value = 1 Then sSQL = sSQL & "TS.Detalle_SubCta As Beneficiario,"
   	   if($ChecksubCta=='true')
   	   {
   	   	$sql.="TS.Detalle_SubCta As Beneficiario,";

   	   }
   	   /*Select Case TipoCta
           Case "I"
                sSQL = sSQL & "SUM(TS.Creditos) As Total,"
           Case "G"
                sSQL = sSQL & "SUM(TS.Debitos) As Total,"
         End Select*/

   	   if($tipocuenta =='I')
   	   {
   	   	$sql.="SUM(TS.Creditos) As Total,";
   	   }
   	   if($tipocuenta=='G')
   	   {
   	   	 $sql.="SUM(TS.Debitos) As Total,";
   	   }

       /*  sSQL = sSQL & "TS.TC,TS.Codigo,TS.Cta " _
              & "FROM Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS " _
              & "WHERE TS.Fecha BETWEEN #" & FechaInicial & "# AND #" & FechaFinal & "# " _
              & "AND TS.Item = '" & NumEmpresa & "' " _
              & "AND TS.Periodo = '" & Periodo_Contable & "' " _
              & "AND TS.TC = '" & TipoCta & "' "*/
        
        $sql.= "TS.TC,TS.Codigo,TS.Cta  FROM Catalogo_SubCtas As C, Catalogo_Cuentas As CC, Trans_SubCtas As TS
              WHERE TS.Fecha BETWEEN '".$fechaini."' AND '".$fechafin."'
              AND TS.Item = '".$_SESSION['INGRESO']['item']."' 
              AND TS.Periodo =  '".$_SESSION['INGRESO']['periodo']."' 
               AND TS.TC = '".$tipocuenta."'";

         //If CheqCta.value = 1 Then sSQL = sSQL & "AND CC.Codigo = '" & Cta & "' "
         if($CheqCta=='true')
         {
         	$sql.="AND CC.Codigo = '".$Cta. "' ";
         }

        // If CheqIndiv.value = 1 Then sSQL = sSQL & "AND TS.Codigo = '" & CodigoCli & "' "
          if($CheqIndiv=='true')
          {
          	$sql.= "AND TS.Codigo = '".$CodigoCli."' ";
          }

         //If CheqDet.value = 1 Then sSQL = sSQL & "AND TS.Detalle_SubCta = '" & DCDet & "' "
          if($CheqDet=='true')
          {
          	$sql.="AND TS.Detalle_SubCta = '".$DCDet."'";
          }

          $sql.="AND TS.Codigo = C.Codigo 
              AND TS.Cta = CC.Codigo
              AND TS.Item = CC.Item 
              AND TS.Periodo = CC.Periodo
              GROUP BY CC.Cuenta,C.Detalle,TS.Detalle_SubCta,TS.TC,TS.Codigo,TS.Cta
              ORDER BY CC.Cuenta,C.Detalle,TS.Detalle_SubCta ";
       // echo $sql;       

          $stmt = sqlsrv_query($this->conn, $sql);
        if( $stmt === false)
        {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
		 return ''; 
	    }
       //return $result;
       $tabla = grilla_generica($stmt,null,NULL,'1');
       return $tabla;

   }


   function eliminar_saldo_diario()
   {
   	 $sql= "DELETE FROM Saldo_Diarios
       WHERE CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND TP = 'CCXP'"; 

       //echo $sql; 
        $stmt = sqlsrv_query($this->conn, $sql);
        if( $stmt === false)
        {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
		 //return -1; 
	    }else
	    {
	    	return 1;
	    }
   }

   function tabla_temporizada($fechafin)
   {
   	$sql ="SELECT Dato_Aux1 As Cuenta,Comprobante As Cliente,Fecha_Venc,Numero As Factura,
       Ven_1_a_7 as 'Ven 1 a 7',
       Ven_8_a_30 as 'Ven 8 a 30',
       Ven_31_a_60 as 'Ven 31 a 60',
       Ven_61_a_90 as 'Ven 61 a 90',
       Ven_91_a_180 as 'Ven 91 a 180',
       Ven_181_a_360 as 'Ven 181 a 360',
       Ven_mas_de_360 as 'Ven mas de 360'
       FROM Saldo_Diarios
       WHERE Fecha_Venc <= '".$fechafin."'
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND TP = 'CCXP'
       ORDER BY TC,Dato_Aux1,Comprobante,Cta,Numero";
       //echo $sql;

          $stmt = sqlsrv_query($this->conn, $sql);
        if( $stmt === false)
        {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
		 return ''; 
	    }
       $result=grilla_generica($stmt,null,NULL,'1');
       return $result;

   }

    function tabla_temporizada_datos($fechafin,$reporte=false)
   {
   	$sql ="SELECT Dato_Aux1 As Cuenta,Comprobante As Cliente,Fecha_Venc,Numero As Factura,
       Ven_1_a_7 as 'Ven 1 a 7',
       Ven_8_a_30 as 'Ven 8 a 30',
       Ven_31_a_60 as 'Ven 31 a 60',
       Ven_61_a_90 as 'Ven 61 a 90',
       Ven_91_a_180 as 'Ven 91 a 180',
       Ven_181_a_360 as 'Ven 181 a 360',
       Ven_mas_de_360 as 'Ven mas de 360'
       FROM Saldo_Diarios
       WHERE Fecha_Venc <= '".$fechafin."'
       AND Item = '".$_SESSION['INGRESO']['item']."' 
       AND TP = 'CCXP'
       ORDER BY TC,Dato_Aux1,Comprobante,Cta,Numero";
       //echo $sql;

          $stmt = sqlsrv_query($this->conn, $sql);
        if( $stmt === false)
        {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true)); 
		 return ''; 
	    }
	     $datos = array();
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
	    {
	    	$datos[] = $row;
	     }
       if($reporte==false)
	    {
          return $datos;
	    }else
	    {
            $stmt1 = sqlsrv_query($this->conn, $sql);
	    	exportar_excel_generico($stmt1,null,null,$b);
	    }


   }
}

?>