<?php 
// $_SESSION['INGRESO']['modulo_']='99';
if(!class_exists('variables_g'))
{
	include(dirname(__DIR__,2).'/db/variables_globales.php');//
    include(dirname(__DIR__,2).'/funciones/funciones.php');
}
@session_start(); 

/**
 * 
 */
class ingreso_descargosM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}


	function buscar_producto($query,$tipo)
		{
			$cid = $this->conn;
     //$sql2="SELECT  Codigo_Inv,Producto,Unidad from Catalogo_Productos WHERE Item = '".$_SESSION['INGRESO']['item']."' AND TC = 'I' AND Periodo='".$_SESSION['INGRESO']['periodo']."'";
			$sql = "SELECT Codigo_Inv,Producto,TC,Valor_Total,Unidad,Stock_Actual,Cta_Inventario,Cta_Costo_Venta,IVA FROM Catalogo_Productos WHERE INV = 1 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 AND ";
			if($tipo =='desc')
			{
			 $sql.="Producto LIKE '%".$query."%'";
			}else
			{
				$sql.=" Codigo_Inv LIKE '%".$query."%'";
			}
     // print_r($sql);die();s
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

	function cargar_pedidos($orden)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT * FROM Asiento_K WHERE ORDEN = '".$orden."' ORDER BY A_No DESC";
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

	function buscar_cc($query=false)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT Codigo,Cuenta,TC 
		     FROM Catalogo_Cuentas 
		     WHERE Item='".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND SUBSTRING(Codigo,1,1) ='5' AND Codigo < '6' AND TC = 'RP' ";
		if($query)
		{
		    $sql.=" AND Cuenta LIKE '%".$query."%'";
		}
    $sql.=" ORDER BY Codigo";
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

	function asignar_num_pedido_clinica()
	{

		$cid = $this->conn;
		$sql="SELECT * FROM Codigos WHERE 
		Item='".$_SESSION['INGRESO']['item']."' and 
		Periodo='".$_SESSION['INGRESO']['periodo']."' and Concepto='PEDIDOS_CLINICA'";
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		if(count($datos)==0)
		{
			$res = $this->CREAR_COD_PEDIDO_CLINICA();
			return $res;
		}else
		{

		   return $datos[0]['Numero'];
		}

	}

	function ACTUALIZAR_COD_PEDIDO_CLINICA($datos)
	{
		$campoWhere[0]['campo']='Concepto';
		$campoWhere[0]['valor']='PEDIDOS_CLINICA';

		$campoWhere[1]['campo']='Item';
		$campoWhere[1]['valor']=$_SESSION['INGRESO']['item'];

		$campoWhere[2]['campo']='Periodo';
		$campoWhere[2]['valor']=$_SESSION['INGRESO']['periodo'];

		  return update_generico($datos,'Codigos',$campoWhere);
	}

    function COD_PEDIDO_CLINICA_EXISTENTE($num_his)
	{

		$cid = $this->conn;
		$sql="SELECT * FROM Asiento_K WHERE ORDEN='".$num_his."'";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		if(count($datos)==0)
		{
			return $num_his;
		}else
		{
		   return -1;
		}

	}

	function CREAR_COD_PEDIDO_CLINICA()
	{
		$datos[0]['campo']='Periodo';
		$datos[0]['dato']='.';

		$datos[1]['campo']='Item';
		$datos[1]['dato']=$_SESSION['INGRESO']['item'];

		$datos[2]['campo']='Concepto';
		$datos[2]['dato']='PEDIDO_CLINICA';

		$datos[3]['campo']='Numero';
		$datos[3]['dato']=1;

		$resp = insert_generico('Codigo',$datos);
		if($resp=='')
		{
			return 1;
		}
	}

	function lineas_eli($parametros)
	{

		$cid = $this->conn;
		$sql = "DELETE FROM Asiento_K WHERE ORDEN='".$parametros['ped']."' and A_No ='".$parametros['lin']."'";
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			return -1;
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  

		}		
		   return 1;
		

	}
	function lineas_edi($datos,$where)
	{
		$resp = update_generico($datos,'Asiento_K',$where);
		return $resp;
	}

	function datos_asiento_haber($orden)
	{
     $cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CTA_INVENTARIO as 'cuenta',Fecha_Fab as 'fecha',TC FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' AND ORDEN = '".$orden."' GROUP BY Codigo_B,ORDEN,CONTRA_CTA,CTA_INVENTARIO,Fecha_Fab,TC,SUBCTA";
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
	function datos_asiento_debe($orden)
	{
      $cid = $this->conn;
      // 'LISTA DE CODIGO DE ANEXOS
     $sql = "SELECT SUM(VALOR_TOTAL) as 'total',CONTRA_CTA as 'cuenta',SUBCTA,Fecha_Fab as 'fecha',TC FROM Asiento_K  WHERE Item = '".$_SESSION['INGRESO']['item']."' and ORDEN = '".$orden."' GROUP BY Codigo_B,ORDEN,CONTRA_CTA,CTA_INVENTARIO,Fecha_Fab,TC,SUBCTA";
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

	function ingresar_asientos($parametros)
	{//ingresar asiento 

		// print_r($parametros);
		$cid = $this->conn;	
		$va = $parametros['va'];
		$dconcepto1 = $parametros['dconcepto1'];
		$codigo = $parametros['codigo'];
		$cuenta = $parametros['cuenta'];
		if(isset($parametros['t_no']))
		{
			$t_no = $parametros['t_no'];
		}
		else
		{
			$t_no = 1;
		}
		if(isset($parametros['efectivo_as']))
		{
			$efectivo_as = $parametros['efectivo_as'];
		}
		else
		{
			$efectivo_as = '';
		}
		if(isset($parametros['chq_as']))
		{
			$chq_as = $parametros['chq_as'];
		}
		else
		{
			$chq_as = '';
		}
		
		$moneda = $parametros['moneda'];
		$tipo_cue = $parametros['tipo_cue'];
		
		if($efectivo_as=='' or $efectivo_as==null)
		{
			$efectivo_as=$fecha;
		}
		if($chq_as=='' or $chq_as==null)
		{
			$chq_as='.';
		}
		$parcial = 0;
		if($moneda==2)
		{
			$cotizacion = $parametros['cotizacion'];
			$con = $parametros['con'];
			if($tipo_cue==1)
			{
				if($con=='/')
				{
					$debe=$va/$cotizacion;
				}
				else
				{
					$debe=$va*$cotizacion;
				}
				$parcial = $va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				if($con=='/')
				{
					$haber=$va/$cotizacion;
				}
				else
				{
					$haber=$va*$cotizacion;
				}
				$parcial = $va;
				$debe=0;
			}
		}
		else
		{
			if($tipo_cue==1)
			{
				$debe=$va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				$debe=0;
				$haber=$va;
			}
		}
		//verificar si ya existe en ese modulo ese registro
		$sql="SELECT CODIGO, CUENTA
		FROM Asiento
		WHERE (CODIGO = '".$codigo."') AND (Item = '".$_SESSION['INGRESO']['item']."') 
		AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND (DEBE = '".$va."') 
		AND T_No=".$_SESSION['INGRESO']['modulo_']." 
		ORDER BY A_No ASC ";
		//print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//para contar registro
		$i=0;
		$i=contar_registros($stmt);
		if($t_no == '60')
		{
			$i=0;
		}
		//echo $i.' -- '.$sql;
		//seleccionamos el valor siguiente
		$sql="SELECT TOP 1 A_No FROM Asiento
		WHERE (Item = '".$_SESSION['INGRESO']['item']."')
		ORDER BY A_No DESC";
		$A_No=0;
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		else
		{
			$ii=0;
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$A_No = $row[0];
				$ii++;
			}
			
			if($ii==0)
			{
				$A_No++;
			}
			else
			{
				$A_No++;
			}
		}
		//si no existe guardamos
		if($i==0)
		{
			
				$sql="INSERT INTO Asiento
				(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
				,ME,T_No,Item,CodigoU,A_No)
				VALUES
				('".$codigo."','".$cuenta."',".$parcial.",".$debe.",".$haber.",'".$chq_as."','".$dconcepto1."',
				'".$efectivo_as."','.','.',0,".$t_no.",'".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['CodigoU']."',".$A_No.")";
			
			// print_r($sql);die();
		   $stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
					ORDER BY A_No ASC ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					return 1;
				}
			}
		}
		else
		{
			return 'ya existe';
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


	function numero_comprobante($parametros)
	{
		$cid = $this->conn;
		if(isset($parametros['fecha']))
		{
			if($parametros['fecha']=='')
			{
				$fecha_actual = date("Y-m-d"); 
			}
			else
			{
				$fecha_actual = $parametros['fecha']; 
			}
		}
		else
		{
			$fecha_actual = date("Y-m-d"); 
		}
		$ot = explode("-",$fecha_actual);
		if($parametros['tip']=='CD')
		{
			if($_SESSION['INGRESO']['Num_CD']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."Diario')";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;

				if($i==0)
				{
					return -1;
				}else
				{
					return "Comprobante de Ingreso No. ".$ot[0].'-'.$codigo;
				}
			}
		}
		if($parametros['tip']=='CI')
		{
			if($_SESSION['INGRESO']['Num_CI']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."Ingresos')";
				
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Ingreso No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}
		if($parametros['tip']=='CE')
		{
			if($_SESSION['INGRESO']['Num_CE']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."Egresos')";
				
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Egreso No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}
		if($parametros['tip']=='NC')
		{
			if($_SESSION['INGRESO']['Num_NC']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."NotaCredito')";
				
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Nota de Credito No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}
		if($parametros['tip']=='ND')
		{
			if($_SESSION['INGRESO']['Num_ND']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."NotaDebito')";
				
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Nota de Debito No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}	
	}

	function generar_comprobantes($parametros)
	{
		$cid = $this->conn;
		if(isset($parametros['cotizacion']))
		{
			if($parametros['cotizacion']=='' or $parametros['cotizacion']==null)
			{
				$parametros['cotizacion']=0;
			}
		}
		else
		{
			$parametros['cotizacion']=0;
		}
		$codigo_b='';
		//echo $_POST['ru'].'<br>';
		if($parametros['ru']=='000000000')
		{
			$codigo_b='.';
		}
		else
		{
			//buscamos codigo
			$sql=" 	SELECT Codigo
					FROM Clientes
					WHERE((CI_RUC = '".$parametros['ru']."')) ";
					// print_r($sql);die();
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$codigo_b=$row[0];
				}
			}
			//caso en donde se necesite guardar el codigo de usuario como codigo beneficiario de comprobante
			if($codigo_b =='' or $codigo_b==null)
			{
				$codigo_b =$parametros['ru'];
			}
			//$codigo_b=$_POST['ru'];
		}
		//buscamos total
		if($parametros['tip']=='CE' or $parametros['tip']=='CI')
		{
			$sql="SELECT        SUM( DEBE) AS db, SUM(HABER) AS ha
			FROM            Asiento
			where T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."'  AND CUENTA 
			in (select Cuenta FROM  Catalogo_Cuentas 
			where Catalogo_Cuentas.Cuenta=Asiento.CUENTA AND (Catalogo_Cuentas.TC='CJ' OR Catalogo_Cuentas.TC='BA'))";
			
			$stmt = sqlsrv_query( $cid, $sql);
			$totald=0;
			$totalh=0;
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$totald=$row[0];
					$totalh=$row[1];
				}
			}
			if($parametros['tip']=='CE')
			{
				$parametros['totalh']=$totalh;
			}
			if($parametros['tip']=='CI')
			{
				$parametros['totalh']=$totald;
			}
		}
		
		if($parametros['concepto']=='')
		{
			$parametros['concepto']='.';
		}
		$num_com = explode("-", $parametros['num_com']);
		//verificamos que no se coloque fecha erronea
		$ot = explode("-",$parametros['fecha1']);
		$num_com1 = explode(".", $num_com[0]);
		$parametros['fecha1']=trim($num_com1[1]).'-'.$ot[1].'-'.$ot[2];
		
		//echo $_POST['fecha1'];
		//die();
		
		$sql="INSERT INTO Comprobantes
           (Periodo ,Item,T ,TP,Numero ,Fecha ,Codigo_B,Presupuesto,Concepto,Cotizacion,Efectivo,Monto_Total
           ,CodigoU ,Autorizado,Si_Existe ,Hora,CEj,X)
		   VALUES
           ('".$_SESSION['INGRESO']['periodo']."'
           ,'".$_SESSION['INGRESO']['item']."'
           ,'N'
           ,'".$parametros['tip']."'
           ,".$num_com[1]."
           ,'".$parametros['fecha1']."'
           ,'".$codigo_b."'
           ,0
           ,'".$parametros['concepto']."'
           ,'".$parametros['cotizacion']."'
           ,0
           ,'".$parametros['totalh']."'
           ,'".$_SESSION['INGRESO']['CodigoU']."'
           ,'.'
           ,0
           ,'".date('h:i:s')."'
           ,'.'
           ,'.')";
		    // echo $sql.'<br>';
		    $stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
		   //consultamos transacciones
		   $sql="SELECT CODIGO,CUENTA,PARCIAL_ME  ,DEBE ,HABER ,CHEQ_DEP ,DETALLE ,EFECTIVIZAR,CODIGO_C,CODIGO_CC
				,ME,T_No,Item,CodigoU ,A_No,TC
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			
			$sql=$sql." ORDER BY A_No ";
			// print_r($sql);
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$i=0;
				$ii=0;
				$Result = array();
				$fecha_actual = date("Y-m-d"); 
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$Result[$i]['CODIGO']=$row[0];
					$Result[$i]['CHEQ_DEP']=$row[5];
					$Result[$i]['DEBE']=$row[3];
					$Result[$i]['HABER']=$row[4];
					$Result[$i]['PARCIAL_ME']=$row[2];
					$Result[$i]['EFECTIVIZAR']=$row[7]->format('Y-m-d');
					$Result[$i]['CODIGO_C']=$row[8];
					
					$sql=" INSERT INTO Transacciones
				    (Periodo ,T,C ,Cta,Fecha,TP ,Numero,Cheq_Dep,Debe ,Haber,Saldo ,Parcial_ME ,Saldo_ME ,Fecha_Efec ,Item ,X ,Detalle
				    ,Codigo_C,Procesado,Pagar,C_Costo)
					 VALUES
				    ('".$_SESSION['INGRESO']['periodo']."'
				    ,'N'
				    ,0
				    ,'".$Result[$i]['CODIGO']."'
				    ,'".$parametros['fecha1']."'
				    ,'".$parametros['tip']."'
				    ,".$num_com[1]."
				    ,'".$Result[$i]['CHEQ_DEP']."'
				    ,".$Result[$i]['DEBE']."
				    ,".$Result[$i]['HABER']."
				    ,0
				    ,".$Result[$i]['PARCIAL_ME']."
				    ,0
				    ,'".$Result[$i]['EFECTIVIZAR']."'
				    ,'".$_SESSION['INGRESO']['item']."'
				    ,'.'
				    ,'.'
				    ,'".$Result[$i]['CODIGO_C']."'
				    ,0
				    ,0
				    ,'.');";
				   // echo $sql.'<br>';
					$stmt1 = sqlsrv_query( $cid, $sql);
					if( $stmt1 === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					$i++;
				}
				$sql="SELECT  Codigo,Beneficiario,Factura,Prima,DH,Valor ,Valor_ME,Detalle_SubCta,FECHA_V ,TC,Cta,TM
				,T_No,SC_No,Fecha_D,Fecha_H,Bloquear,Item,CodigoU
				FROM Asiento_SC
				WHERE 
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";

				//echo $sql;
				$stmt = sqlsrv_query(   $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$i=0;
					$Result = array();
					$fecha_actual = date("Y-m-d"); 
					while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
					{
						$Result[$i]['TC']=$row[9];
						$Result[$i]['Cta']=$row[10];
						$Result[$i]['FECHA_V']=$row[8]->format('Y-m-d');
						$Result[$i]['Codigo']=$row[0];
						$Result[$i]['Factura']=$row[2];
						$Result[$i]['Prima']=$row[3];
						$Result[$i]['DH']=$row[4];
						if($Result[$i]['DH']==1)
						{
							$Result[$i]['DEBITO']=$row[5];
							$Result[$i]['HABER']=0;
						}
						if($Result[$i]['DH']==2)
						{
							$Result[$i]['DEBITO']=0;
							$Result[$i]['HABER']=$row[5];
						}
						$sql="INSERT INTO Trans_SubCtas
							   (Periodo ,T,TC,Cta,Fecha,Fecha_V,Codigo ,TP,Numero ,Factura ,Prima ,Debitos ,Creditos ,Saldo_MN,Parcial_ME
							   ,Saldo_ME,Item,Saldo ,CodigoU,X,Comp_No,Autorizacion,Serie,Detalle_SubCta,Procesado)
						 VALUES
							   ('".$_SESSION['INGRESO']['periodo']."'
							   ,'N'
							   ,'".$Result[$i]['TC']."'
							   ,'".$Result[$i]['Cta']."'
							   ,'".$parametros['fecha1']."'
							   ,'".$Result[$i]['FECHA_V']."'
							   ,'".$Result[$i]['Codigo']."'
							   ,'".$parametros['tip']."'
							   ,".$num_com[1]."
							   ,".$Result[$i]['Factura']."
							   ,".$Result[$i]['Prima']."
							   ,".$Result[$i]['DEBITO']."
							   ,".$Result[$i]['HABER']."
							   ,0
							   ,0
							   ,0
							   ,'".$_SESSION['INGRESO']['item']."'
							   ,0
							   ,'".$_SESSION['INGRESO']['CodigoU']."'
							   ,'.'
							   ,0
							   ,'.'
							   ,'.'
							   ,'.'
							   ,0)";
						//echo $sql.'<br>';
						$stmt1 = sqlsrv_query( $cid, $sql);
						if( $stmt1 === false)  
						{  
							 echo "Error en consulta PA.\n";  
							 die( print_r( sqlsrv_errors(), true));  
						}
					}
				}
				//incrementamos el secuencial
				if($_SESSION['INGRESO']['Num_CD']==1)
				{
					//para variable en html
					$num1=$num_com[1];
					$num_com[1]=$num_com[1]+1;
					//echo $num_com[1].'<br>'.$_POST['tip'].'<br>';
					if(isset($parametros['fecha1']))
					{
						//echo $_POST['fecha'];
						$fecha_actual = $parametros['fecha1']; 
					}
					else
					{
						$fecha_actual = date("Y-m-d"); 
					}
					$ot = explode("-",$fecha_actual);
					if($parametros['tip']=='CD')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Diario')";
					}
					if($parametros['tip']=='CI')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Ingresos')";
					}
					if($parametros['tip']=='CE')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Egresos')";
					}
					if($parametros['tip']=='ND')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."NotaDebito')";
					}
					if($parametros['tip']=='NC')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."NotaCredito')";
					}
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//borramos temporales asientos
					$sql="DELETE FROM Asiento
					WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//borramos temporales asientos bancos
					
					$sql="DELETE FROM Asiento_B
					WHERE 
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//echo $sql;
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//borramos asiento subcuenta
					$sql="DELETE FROM Asiento_SC
					WHERE 
						Item = '".$_SESSION['INGRESO']['item']."' 
						AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
					$stmt = sqlsrv_query(   $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//generamos comprobante
					//reporte_com($num1);
					return $num1;
				}
			}
	}


	function datos_comprobante()
	{
		$cid = $this->conn;
		$sql="SELECT * FROM Asiento WHERE CodigoU='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."' AND T_No = '99'";
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

	function lista_hijos_id($query)
	{
			$cid = $this->conn;
    // 'LISTA DE CODIGO DE ANEXOS
      $sql = "SELECT CP.Codigo_Inv,CP.Producto,CP.TC,TK.Costo as 'Valor_Total',CP.Unidad, SUM(Entrada-Salida) As Stock_Actual ,CP.Cta_Inventario
           FROM Catalogo_Productos As CP, Trans_Kardex AS TK 
           WHERE CP.INV = 1 AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."' AND CP.Item = '".$_SESSION['INGRESO']['item']."'AND LEN(CP.Cta_Inventario)>3 AND CP.Codigo_Inv LIKE '".$query."' AND TK.T<> 'A' AND CP.Periodo = TK.Periodo AND CP.Item = TK.Item AND CP.Codigo_Inv = TK.Codigo_Inv group by CP.Codigo_Inv,CP.Producto,CP.TC,CP.Valor_Total,CP.Unidad,TK.Costo,CP.Cta_Inventario having SUM(TK.Entrada-TK.Salida) <> 0 
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




}

?>