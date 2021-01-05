<?php 
require_once(dirname(__DIR__,3)."/db/db.php");
require_once(dirname(__DIR__,3)."/funciones/funciones.php");
require_once(dirname(__DIR__,4).'/lib/fpdf/reporte_de.php');
date_default_timezone_set('America/Guayaquil');
//echo dirname(__DIR__,3);
@session_start(); 
/**
 * 
 */
class MesaModel
{
	private $conn;
	function __construct() 
	{
		$this->conn = new Conectar();
	}
	function cargar_mesas()
{
/*
MESA A1
MESA A2
MESA AB1
MESA R1
MESA RB2
MESA Barra R1
MESA Barra R2
MESA Barra R3
MESA Barra R4
MESA P1
MESA P2
MESA P3
MESA P4
MESA P5
MESA PB1
MESA PB2
MESA PB3
MESA PB4
*/
	$cid=$this->conn->conexion();
	$sql="SELECT * ". 
		   "FROM Catalogo_Productos
			WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv like 'MS.%'
			ORDER BY Codigo_Inv";
		
	//echo $sql;
	//die();
	$stmt = sqlsrv_query($cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$row_count=0;
	$i=0;
	$Result = array();
	while ($row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) {
		
		$Result[] = $row;
	 } 
	cerrarSQLSERVERFUN($cid);
	return $Result;
}

function productos_entregar($Codigo_Inv)
{
	//buscamos productos para entregar
	$cid=$this->conn->conexion();
	$sql="select * ". 
		 "FROM Asiento_F
		  WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
		  AND  HABIT='".$Codigo_Inv."' AND Estado='A'
		  ORDER BY CODIGO";
				
	  //echo $sql;
	//die();
	$stmt1 = sqlsrv_query( $cid, $sql);
	if( $stmt1 === false)  
    {  
		echo "Error en consulta PA.\n";  
		die( print_r( sqlsrv_errors(), true));  
    }
	$ii=0;	
	while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
	{
		$ii++;
	}
	if($ii==0)
	{
		return $pedi="";
	}
	else
	{
		return "<small class='label label-danger'><i class='fa fa-clock-o'></i> Servir</small>";
	}
	cerrarSQLSERVERFUN($cid);
}
function list_categorias()
{
	$cid=$this->conn->conexion();
	$sql="SELECT * ". 
		"FROM Catalogo_Productos 
		WHERE (TC = 'I')
		AND Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND LEN(Codigo_Inv)>=5
        AND SUBSTRING(Codigo_Inv,1,2)='01' 
		 order by Producto";	
	/*$sql="SELECT * ". 
		"FROM Catalogo_Productos 
		WHERE (TC = 'I')
		AND Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND LEN(Codigo_Inv)>=4
		 order by Producto";
		 */
	//echo $sql;
    //die();
    $stmt = sqlsrv_query($cid, $sql);
    if( $stmt === false)  
	{  
		echo "Error en consulta PA.\n";  
		die( print_r( sqlsrv_errors(), true));  
	}
	$Result = array();
	while( $row = sqlsrv_fetch_array( $stmt,SQLSRV_FETCH_ASSOC) ) 
	{
		$Result[]=$row;				
	}
	return $Result;
	cerrarSQLSERVERFUN($cid);
}
function list_product($buscar,$fil)
{
	$cid=$this->conn->conexion();
	if($buscar=='')
    {
		if($fil=='0')
		{
			$sql="SELECT * ". 
			 "FROM Catalogo_Productos
			  WHERE (LEN(Cta_Inventario) > 2) AND (LEN(Cta_Costo_Venta) > 2) AND (TC = 'P') AND 
			  (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			  AND (Item = '".$_SESSION['INGRESO']['item']."')
			  ORDER BY Codigo_Inv";
		}
		else
		{
			$sql="SELECT * ". 
			 "FROM Catalogo_Productos
			  WHERE (LEN(Cta_Inventario) > 2) AND (LEN(Cta_Costo_Venta) > 2) AND (TC = 'P') AND 
			  (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			  AND (Item = '".$_SESSION['INGRESO']['item']."')
			  AND (Codigo_Inv LIKE '".$fil."%')
			  ORDER BY Codigo_Inv";
		}
	}
	else
	{
		if($fil=='0')
		{
			$sql="SELECT * ". 
			  "FROM Catalogo_Productos
			   WHERE (LEN(Cta_Inventario) > 2) AND (LEN(Cta_Costo_Venta) > 2) AND (TC = 'P') AND 
			   (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Item = '".$_SESSION['INGRESO']['item']."')
				AND  Producto LIKE '%".$buscar."%'
				ORDER BY Codigo_Inv";
		}
		else
		{
			$sql="SELECT * ". 
			  "FROM Catalogo_Productos
			   WHERE (LEN(Cta_Inventario) > 2) AND (LEN(Cta_Costo_Venta) > 2) AND (TC = 'P') AND 
			   (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Item = '".$_SESSION['INGRESO']['item']."')
				AND  (Producto LIKE '%".$buscar."%' or (Codigo_Inv LIKE '".$fil."%'))
				ORDER BY Codigo_Inv";
		}
	}			
	//echo $sql;
    //die();
    $stmt = sqlsrv_query($cid, $sql);
    if( $stmt === false)  
	{  
		echo "Error en consulta PA.\n";  
		die( print_r( sqlsrv_errors(), true));  
	}
	$Result = array();
	while( $row = sqlsrv_fetch_array( $stmt,SQLSRV_FETCH_ASSOC) ) 
	{
		$Result[]=$row;				
	}
	return $Result;
	cerrarSQLSERVERFUN($cid);
}
					

function pedido_realizado($me)
{

	$cid=$this->conn->conexion();

	$sql="select * ". 
		  "FROM Asiento_F
		   WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
		   AND  HABIT='".$me."' 
		   ORDER BY CODIGO";
					
	//echo $sql;
	//die();
	$stmt =sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$Result = array();
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	{
		$Result[]= $row;					
	
	}
	cerrarSQLSERVERFUN($cid);
	return $Result;

}
function agregar_factura($datos1)
{
	$cid=$this->conn->conexion();
	$nombrec= $datos1['nombrec'];
	$ruc= $datos1['ruc'];
	$email= $datos1['email'];
	$ser= $datos1['ser'];
	$ser1=explode("_", $ser);
	$n_fac= $datos1['n_fac'];
	$me= $datos1['me'];
	$total_total_= $datos1['total_total_'];
	$total_abono= $datos1['total_abono'];	
	$propina_a= $datos1['propina_a'];
	$fecha_actual = date("Y-m-d"); 
	$hora = date("H:i:s");
	$fechaEntera = strtotime($fecha_actual);
	$anio = date("Y", $fechaEntera);
	$mes = date("m", $fechaEntera);
	$total_iva=0;
	$imp=0;
	if(isset($datos1['imprimir']))
	{
		$imp=$datos1['imprimir'];
	}
	if($imp==0)
	{
		//$mes=$mes+1;
		//consultamos clientes
		$sql="SELECT * FROM Clientes WHERE  (CI_RUC= '".$ruc."') AND Cliente='".$nombrec."' ";
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$codigo=$row[2];
		}
		//consultamos catalogo linea
		$sql="SELECT   Codigo, CxC
		FROM   Catalogo_Lineas
		WHERE   (Periodo = '".$_SESSION['INGRESO']['periodo']."') AND 
		(Item = '".$_SESSION['INGRESO']['item']."') AND (Serie = '".$ser1[2]."') AND (Fact = 'FA')";
		//echo $sql;
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$cxc=$row[1];
			$cod_linea=$row[0];
		}
		//verificamos que no exista la factura
		$sql="SELECT        TOP (200) Periodo, T, TC, CodigoC, Factura, Fecha, Codigo, CodigoL, Producto, Cantidad, Precio, Total, Total_IVA, Ruta, Ticket, Item, Corte, Reposicion, Total_Desc, No_Hab, Cod_Ejec, Porc_C, Com_Pag, Cta_Venta, CodigoU, 
							 CodBodega, Tonelaje, Costo, Comision, Mes, X, Producto_Aux, Puntos, Autorizacion, Serie, CodMarca, Gramaje, Orden_No, Mes_No, C, CodigoB, Precio2, Total_Desc2, SubTotal_NC, Total_IVA_NC, Fecha_IN, Fecha_OUT, 
							 Cant_Hab, Tipo_Hab, Codigo_Barra, Serie_NC, Autorizacion_NC, Fecha_NC, Secuencial_NC, Fecha_V, Cant_Bonif, Lote_No, Fecha_Fab, Fecha_Exp, Modelo, Procedencia, Serie_No, Porc_IVA, Cantidad_NC, Total_Desc_NC, 
							 ID
				FROM            Detalle_Factura
				WHERE        (Factura = '".$n_fac."') AND (Serie = '".$ser1[2]."') AND (Item = '".$_SESSION['INGRESO']['item']."') AND (Periodo = '".$_SESSION['INGRESO']['periodo']."')
			 ";
						
		//echo $sql;
		//die();
		$stmt =sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$ii=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$ii++;
		}
		if($ii==0)
		{
			//agregamos detalle factura
			$sql="select * ". 
				  "FROM Asiento_F
				   WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
				   AND  HABIT='".$me."' 
				   ORDER BY CODIGO";
							
			//echo $sql;
			//die();
			$total_coniva=0;
			$total_siniva=0;
			$stmt =sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			/*
			SELECT        TOP (200) Periodo, Item, C, T, ME, Cod_CxC, TC, Serie, Autorizacion, Factura, CodigoC, Fecha, Fecha_C, Fecha_V, SubTotal, Con_IVA, Sin_IVA, IVA, Descuento, Porc_C, Comision, Servicio, Total_MN, Total_ME, Saldo_MN, 
								 Saldo_ME, Saldo_Actual, Forma_Pago, Cotizacion, Cta_CxP, Cta_Venta, Cod_Ejec, Com_Pag, Nota, Observacion, CodigoU, SubCta, Hora, Vencimiento, P, Fecha_Aut, Dias_Vencidos, Desc_0, Desc_X, RUC_CI, TB, Razon_Social, 
								 Direccion_RS, Telefono_RS, CodigoB, Descuento2, Total_Efectivo, Total_Banco, Total_Ret_Fuente, Total_Ret_IVA_B, Total_Ret_IVA_S, Otros_Abonos, Total_Abonos, Abonos_MN, Clave_Acceso, Hora_Aut, Estado_SRI, Efectivo, 
								 CodigoDr, Serie_R, Secuencial_R, Autorizacion_R, Fecha_R, Autorizacion_NC, Clave_Acceso_NC, Hora_Aut_NC, Estado_SRI_NC, Tipo_Pago, Error_FA_SRI, Porc_IVA, Imp_Mes, SP, Orden_Compra, X, Chq_Posf, Venc_0_60, 
								 Venc_61_90, Venc_91_120, Venc_121_360, Venc_mas_360, ID
			FROM            Facturas
			WHERE        (Factura = '1569') AND (Item = '001') AND (Periodo = '.')
			SELECT        TOP (200) Periodo, T, TC, CodigoC, Factura, Fecha, Codigo, CodigoL, Producto, Cantidad, Precio, Total, Total_IVA, Ruta, Ticket, Item, Corte, Reposicion, Total_Desc, No_Hab, Cod_Ejec, Porc_C, Com_Pag, Cta_Venta, CodigoU, 
								 CodBodega, Tonelaje, Costo, Comision, Mes, X, Producto_Aux, Puntos, Autorizacion, Serie, CodMarca, Gramaje, Orden_No, Mes_No, C, CodigoB, Precio2, Total_Desc2, SubTotal_NC, Total_IVA_NC, Fecha_IN, Fecha_OUT, 
								 Cant_Hab, Tipo_Hab, Codigo_Barra, Serie_NC, Autorizacion_NC, Fecha_NC, Secuencial_NC, Fecha_V, Cant_Bonif, Lote_No, Fecha_Fab, Fecha_Exp, Modelo, Procedencia, Serie_No, Porc_IVA, Cantidad_NC, Total_Desc_NC, 
								 ID
			FROM            Detalle_Factura
			WHERE        (Factura = '1569') AND (Item = '001') AND (Periodo = '.')
			*/
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$dato[0]['campo']='T';
				$dato[0]['dato']='C';
				$dato[1]['campo']='TC';
				$dato[1]['dato']='FA';
				$dato[2]['campo']='CodigoC';
				$dato[2]['dato']=$codigo;
				$dato[3]['campo']='Factura';
				$dato[3]['dato']=$n_fac;
				$dato[4]['campo']='Fecha';
				$dato[4]['dato']=$fecha_actual;	
				$dato[5]['campo']='Codigo';
				$dato[5]['dato']=$row[0];
				$dato[6]['campo']='CodigoL';
				$dato[6]['dato']=$cod_linea;	
				$dato[7]['campo']='Producto';
				$dato[7]['dato']=$row[3];	
				$dato[8]['campo']='Cantidad';
				$dato[8]['dato']=$row[1];
				$dato[9]['campo']='Precio';
				$dato[9]['dato']=$row[4];	
				$dato[10]['campo']='Total';
				$dato[10]['dato']=$row[9];//descontar descuentos	
				$dato[11]['campo']='Total_IVA';
				$dato[11]['dato']=$row[7];
				//$dato[12]['campo']='Cta_Venta';
				//$dato[12]['dato']='.';	
				$dato[12]['campo']='Item';
				$dato[12]['dato']=$_SESSION['INGRESO']['item'];	
				$dato[13]['campo']='CodigoU';
				$dato[13]['dato']=$_SESSION['INGRESO']['CodigoU'];	
				$dato[14]['campo']='Periodo';
				$dato[14]['dato']=$_SESSION['INGRESO']['periodo'];	
				$dato[15]['campo']='Serie';
				$dato[15]['dato']=$ser1[2];	
				$dato[16]['campo']='Mes_No';
				$dato[16]['dato']=$mes;	
				//$dato[17]['campo']='C';
				//$dato[17]['dato']=0;	
				/*$dato[18]['campo']='Fecha_IN';
				$dato[18]['dato']=$fecha_actual;	
				$dato[19]['campo']='Fecha_OUT';
				$dato[19]['dato']=$fecha_actual;	
				$dato[20]['campo']='Fecha_NC';
				$dato[20]['dato']=$fecha_actual;
				$dato[21]['campo']='Fecha_V';
				$dato[21]['dato']=$fecha_actual;	
				$dato[22]['campo']='Fecha_Fab';
				$dato[22]['dato']=$fecha_actual;	
				$dato[23]['campo']='Fecha_Exp';
				$dato[23]['dato']=$fecha_actual;*/	
				$dato[17]['campo']='Porc_IVA';
				$dato[17]['dato']=$_SESSION['INGRESO']['porc'];	
				$dato[18]['campo']='Autorizacion';
				$dato[18]['dato']=$_SESSION['INGRESO']['RUC'];
				$total_iva=$total_iva+$row[7];
				$this->insert_generico("Detalle_Factura",$dato);
				if($row[7]==0)
				{
					$total_siniva=$row[9]+$total_siniva;
				}
				else
				{
					$total_coniva=$row[9]+$total_coniva;
				}
			}
			//agregamos abono
			$sql="SELECT * FROM Asiento_Abonos WHERE  (HABIT= '".$me."') AND 
			(Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Item = '".$_SESSION['INGRESO']['item']."')";
			$stmt = sqlsrv_query($cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}				
			
			/*
			INSERT INTO Trans_Abonos
			   (Periodo,C,ME,T,TP,Cta(preguntar),Cta_CxP(preguntar),Fecha,Recibo_No,Comprobante(preguntar),Factura,Total,Abono(preguntar),
			   CodigoC,Banco,Cheque,CodigoU
			   ,Item,Serie,Tipo_Cta ,Fecha_Aut_NC,Fecha_Aut,Ejecutivo)
			*/
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				//datos de la cuenta
				$sql="SELECT TC,Codigo,Cuenta,Tipo_Pago FROM Catalogo_Cuentas 
					WHERE TC IN ('BA','CJ','CP','C','P','TJ','CF','CI','CB') 
					AND DG = 'D' AND Item = '".$_SESSION['INGRESO']['item']."' 
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo='".$row[11]."' ";
					//echo $sql.'<br>';
				$stmt1 =sqlsrv_query( $cid, $sql);
				if( $stmt1 === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$cod_cue='.';
				$TC='.';
				$cuenta='.';
				$tipo_pago='.';
				while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
				{
					$cod_cue=$row1[1];
					$TC=$row1[0];
					$cuenta=$row1[2];
					if($row1[3]!='.')
					{
						if($tipo_pago=='.')
						{
							$tipo_pago=$row1[3];
						}
						else
						{
							if($tipo_pago<$row1[3])
							{
								$tipo_pago=$row1[3];
							}
						}
					}
				}
				$dato[0]['campo']='T';
				$dato[0]['dato']='C';
				$dato[1]['campo']='TP';
				$dato[1]['dato']='FA';
				$dato[2]['campo']='CodigoC';
				$dato[2]['dato']=$codigo;
				$dato[3]['campo']='Factura';
				$dato[3]['dato']=$n_fac;
				$dato[4]['campo']='Fecha';
				$dato[4]['dato']=$fecha_actual;	
				$dato[5]['campo']='Cta';
				$dato[5]['dato']=$cod_cue;
				$dato[6]['campo']='Cta_CxP';
				$dato[6]['dato']=$cod_linea;	
				$dato[7]['campo']='Recibo_No';
				$dato[7]['dato']='0000000000';	
				$dato[8]['campo']='Comprobante';
				$dato[8]['dato']='.';
				$dato[9]['campo']='Abono';
				//$dato[9]['dato']=$row[9];	
				$dato[9]['dato']=$total_total_;
				$dato[10]['campo']='Total';
				//$dato[10]['dato']=$row[9];	
				$dato[10]['dato']=$total_total_;
				$dato[11]['campo']='Cheque';
				$dato[11]['dato']=$row[12];
				$dato[12]['campo']='Fecha_Aut_NC';
				$dato[12]['dato']=$fecha_actual;	
				$dato[13]['campo']='Item';
				$dato[13]['dato']=$_SESSION['INGRESO']['item'];	
				$dato[14]['campo']='CodigoU';
				$dato[14]['dato']=$_SESSION['INGRESO']['CodigoU'];	
				$dato[14]['campo']='Periodo';
				$dato[14]['dato']=$_SESSION['INGRESO']['periodo'];	
				$dato[15]['campo']='Serie';
				$dato[15]['dato']=$ser1[2];	
				$dato[16]['campo']='Fecha_Aut';
				$dato[16]['dato']=$fecha_actual;
				$dato[17]['campo']='C';
				$dato[17]['dato']=0;
				$dato[18]['campo']='Tipo_Cta';
				$dato[18]['dato']=$TC;
				$dato[19]['campo']='Banco';
				$dato[19]['dato']=$cuenta;
				$dato[20]['campo']='Autorizacion';
				$dato[20]['dato']=$_SESSION['INGRESO']['RUC'];
				$this->insert_generico("Trans_Abonos",$dato);
			}
			/*
			INSERT INTO Detalle_Factura
				   (T,TC,CodigoC ,Factura,Fecha,Codigo,CodigoL ,Producto,Cantidad,Reposicion ,Precio,Total ,Total_Desc,Total_IVA,Ruta,Ticket,No_Hab
				   ,Cod_Ejec,Porc_C,Cta_Venta,Item,CodigoU,Periodo,Com_Pag,CodBodega,Tonelaje,Corte,X,Costo,Comision,Mes,Producto_Aux,Puntos
				   ,Autorizacion,Serie,CodMarca,Gramaje,Orden_No,Mes_No,C,CodigoB,Precio2,Total_Desc2,SubTotal_NC,Total_IVA_NC,Fecha_IN
				   ,Fecha_OUT,Cant_Hab,Tipo_Hab,Codigo_Barra,Serie_NC,Autorizacion_NC,Fecha_NC,Secuencial_NC,Fecha_V ,Cant_Bonif,Lote_No
				   ,Fecha_Fab,Fecha_Exp,Modelo,Procedencia,Serie_No,Porc_IVA ,Cantidad_NC,Total_Desc_NC)
			
			*/
			/*
			INSERT INTO Facturas
				   (C,T ,TC,ME,Factura,CodigoC ,Fecha,Fecha_C ,Fecha_V,SubTotal,Con_IVA ,Sin_IVA,IVA,Total_MN
				   ,Cta_CxP,Cta_Venta,Item ,CodigoU,Periodo,Cod_CxC,Com_Pag
				   ,Hora ,X,Serie,Vencimiento,P,Fecha_Aut,RUC_CI,TB,Razon_Social,Total_Efectivo,Total_Banco,Otros_Abonos,Total_Abonos,
				   Abonos_MN,Tipo_Pago,Porc_IVA)
			 VALUES
			*/
			$query="INSERT INTO Facturas
				   (C,T ,TC,ME,Factura,CodigoC ,Fecha,Fecha_C ,Fecha_V,SubTotal,Con_IVA ,Sin_IVA,IVA,Total_MN
				   ,Cta_CxP,Cta_Venta,Item ,CodigoU,Periodo,Cod_CxC,Com_Pag
				   ,Hora ,X,Serie,Vencimiento,P,Fecha_Aut,RUC_CI,TB,Razon_Social,Total_Efectivo,Total_Banco,Otros_Abonos,Total_Abonos,
				   Abonos_MN,Tipo_Pago,Porc_IVA)
			 VALUES
			   (1
			   ,'C'
			   ,'FA'
			   ,0
			   ,".$n_fac."
			   ,'".$codigo."'
			   ,'".$fecha_actual."'
			   ,'".$fecha_actual."'
			   ,'".$fecha_actual."'
			   ,".$total_total_."
			   ,0
			   ,".$total_total_."
			   ,".$total_iva."
			   ,".$total_total_."
			   ,'".$cxc."'
			   ,'0'
			   ,'".$_SESSION['INGRESO']['item']."'
			   ,'".$_SESSION['INGRESO']['CodigoU']."'
			   ,'".$_SESSION['INGRESO']['periodo']."'
			   ,'".$cod_linea."'
			   ,0
			   ,'".$hora."'
			   ,'X'
			   ,'".$ser1[2]."'
			   ,'".$fecha_actual."'
			   ,0
			   ,'".$fecha_actual."'
			   ,'".$ruc."'
			   ,'R'
			   ,'".$nombrec."'
			   ,".$total_abono."
			   ,0
			   ,0
			   ,".$total_abono."
			   ,".$total_abono."
			   ,'20'
			   ,".$_SESSION['INGRESO']['porc']."
			  )";
			//echo $query;
			
			$dato[0]['campo']='C';
			$dato[0]['dato']=1;
			$dato[1]['campo']='T';
			$dato[1]['dato']='C';
			$dato[2]['campo']='TC';
			$dato[2]['dato']='FA';
			$dato[3]['campo']='ME';
			$dato[3]['dato']=0;
			$dato[4]['campo']='Factura';
			$dato[4]['dato']=$n_fac;
			$dato[5]['campo']='CodigoC';
			$dato[5]['dato']=$codigo;
			$dato[6]['campo']='Fecha';
			$dato[6]['dato']=$fecha_actual;
			$dato[7]['campo']='Fecha_C';
			$dato[7]['dato']=$fecha_actual;
			$dato[8]['campo']='Fecha_V';
			$dato[8]['dato']=$fecha_actual;
			$dato[9]['campo']='SubTotal';
			$dato[9]['dato']=($total_total_-$total_iva);
			$dato[10]['campo']='Con_IVA';
			$dato[10]['dato']=($total_coniva-$total_iva);
			$dato[11]['campo']='Sin_IVA';
			$dato[11]['dato']=$total_siniva;
			$dato[12]['campo']='IVA';
			$dato[12]['dato']=$total_iva;
			$dato[13]['campo']='Total_MN';
			$dato[13]['dato']=$total_total_;
			$dato[14]['campo']='Cta_CxP';
			$dato[14]['dato']=$cxc;
			$dato[15]['campo']='Cta_Venta';
			$dato[15]['dato']='0';
			$dato[16]['campo']='Item';
			$dato[16]['dato']=$_SESSION['INGRESO']['item'];
			$dato[17]['campo']='CodigoU';
			$dato[17]['dato']=$_SESSION['INGRESO']['CodigoU'];
			$dato[18]['campo']='Periodo';
			$dato[18]['dato']=$_SESSION['INGRESO']['periodo'];
			$dato[19]['campo']='Cod_CxC';
			$dato[19]['dato']=$cod_linea;
			$dato[20]['campo']='Com_Pag';
			$dato[20]['dato']=0;
			$dato[21]['campo']='Hora';
			$dato[21]['dato']=$hora;
			$dato[22]['campo']='X';
			$dato[22]['dato']='X';
			$dato[23]['campo']='Serie';
			$dato[23]['dato']=$ser1[2];
			$dato[24]['campo']='Vencimiento';
			$dato[24]['dato']=$fecha_actual;
			$dato[25]['campo']='P';
			$dato[25]['dato']=0;
			$dato[26]['campo']='Fecha_Aut';
			$dato[26]['dato']=$fecha_actual;
			$dato[27]['campo']='RUC_CI';
			$dato[27]['dato']=$ruc;
			$dato[28]['campo']='TB';
			$dato[28]['dato']='R';
			$dato[29]['campo']='Razon_Social';
			$dato[29]['dato']=$nombrec;
			$dato[30]['campo']='Total_Efectivo';
			$dato[30]['dato']=$total_total_;
			$dato[31]['campo']='Total_Banco';
			$dato[31]['dato']=0;
			$dato[32]['campo']='Otros_Abonos';
			$dato[32]['dato']=0;
			$dato[33]['campo']='Total_Abonos';
			$dato[33]['dato']=$total_total_;
			$dato[34]['campo']='Abonos_MN';
			$dato[34]['dato']=$total_total_;
			$dato[35]['campo']='Tipo_Pago';
			$dato[35]['dato']=$tipo_pago;
			$dato[36]['campo']='Porc_IVA';
			$dato[36]['dato']=$_SESSION['INGRESO']['porc'];
			$dato[37]['campo']='Propina';
			$dato[37]['dato']=$propina_a;	
			$dato[38]['campo']='Autorizacion';
			$dato[38]['dato']=$_SESSION['INGRESO']['RUC'];
			$this->insert_generico("Facturas",$dato);
			$n_fac++;
			//incrementar contador de facturas
			$sql="UPDATE Codigos set Numero='".$n_fac."'
			WHERE  (Concepto = 'FA_SERIE_".$ser1[2]."') AND (Item = '".$_SESSION['INGRESO']['item']."') 
			AND (Periodo = '".$_SESSION['INGRESO']['periodo']."')";
			//echo $sql;
			$stmt1 =sqlsrv_query( $cid, $sql);
			if( $stmt1 === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//eliminar campos temporales asiento_f
			$sql="DELETE ". 
				"FROM Asiento_F
				WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
				AND  HABIT='".$me."' ";
			//echo $sql;
			$stmt = sqlsrv_query($cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//eliminar abono
			$sql="DELETE FROM Asiento_Abonos WHERE  (HABIT= '".$me."') AND 
			(Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Item = '".$_SESSION['INGRESO']['item']."')";
			//echo $sql;
			$stmt = sqlsrv_query($cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			cerrarSQLSERVERFUN($cid);
			  //campo que informar imprimir pdf automatico
			  return 2;
		}
		else
		{
			return 0;
		}
	}
	else
	{
		//liberar mesa 
		$this->liberar($me);
		//datos para el pdf
		$param=array();
		$param[0]['nombrec']=$nombrec;
		//echo $param[0]['nombrec'].' -- ';
		$param[0]['ruc']=$ruc;
		$param[0]['mesa']=$me;
		$param[0]['PFA']='F';
		$param[0]['serie']=$ser1[2];
		$param[0]['factura']=($n_fac-1);
		imprimirDocElPF(null,$me,null,null,null,0,$param,'F',$cid);
		//imprimir factura despues de autorizar 
		return 2;
	}
}
function agregar_abono($datos1)
{
	$cid=$this->conn->conexion();
	$nombrec= $datos1['nombrec'];
	$ruc= $datos1['ruc'];
	$email= $datos1['email'];
	$ser= $datos1['ser'];
	$n_fac= $datos1['n_fac'];
	$abo= $datos1['abo'];
	$abo1=explode("-", $abo);
	$compro_a= $datos1['compro_a'];
	$monto_a= $datos1['monto_a'];
	$me= $datos1['me'];
	$fecha_actual = date("Y-m-d"); 
	
	$sql="SELECT * FROM Clientes WHERE  (CI_RUC= '".$ruc."') ";
	$stmt = sqlsrv_query($cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$row_count=0;
	$ii=0;
	$Result = array();
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$codigo=$row[2];
	}
	
	$dato[0]['campo']='Periodo';
	$dato[0]['dato']=$_SESSION['INGRESO']['periodo'];
	$dato[1]['campo']='Item';
	$dato[1]['dato']=$_SESSION['INGRESO']['item'];
	$dato[2]['campo']='CodigoC';
	$dato[2]['dato']=$codigo;
	$dato[3]['campo']='Fecha';
	$dato[3]['dato']=$fecha_actual;
	$dato[4]['campo']='Abono';
	$dato[4]['dato']=$monto_a;
	$dato[5]['campo']='CodigoU';
	$dato[5]['dato']=$_SESSION['INGRESO']['CodigoU'];
	$dato[6]['campo']='HABIT';
	$dato[6]['dato']=$me;
	$dato[7]['campo']='Cta';
	$dato[7]['dato']=$abo1[0];
	$dato[8]['campo']='Comprobante';
	$dato[8]['dato']=$compro_a;
	$this->insert_generico("Asiento_Abonos",$dato);
	cerrarSQLSERVERFUN($cid);
	return 1;				
}
function mostrar_abono($me)
{
	$cid=$this->conn->conexion();
	$sql="SELECT * FROM Asiento_Abonos A
    INNER JOIN Catalogo_Cuentas C ON A.Cta= C.Codigo  WHERE  (HABIT= '".$me."') AND 
	(C.Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (C.Item = '".$_SESSION['INGRESO']['item']."')";
	// print_r($sql);die();
	$stmt = sqlsrv_query($cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$row_count=0;
	$ii=0;
	$Result = array();
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$Result[$ii]['Abono']=$row[9];
		$Result[$ii]['HABIT']=$row[10];
		$Result[$ii]['Cta']=$row[11];
		$Result[$ii]['Comprobante']=$row[12];
		$Result[$ii]['Forma']=$row[22];
		
		$ii++;
	}
	cerrarSQLSERVERFUN($cid);
	return $Result;				
}

function agregar_a_pedido_EXPER($parametros)
{
	$cid=$this->conn->conexion();
	$sql="SELECT * FROM Catalogo_Productos WHERE (TC = 'P') AND 
			(Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND  Codigo_Inv = '".$parametros['prod']."' ORDER BY Codigo_Inv";
		
		// print_r($sql);
		// die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		
		$nombre = '';
		$cta_i = '';
		$cta_CV = '';
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		{
			$nombre = $row['Producto'];
			$cta_i = 	$row['Cta_Inventario'];
			$cta_CV = 	$row['Cta_Costo_Venta'];		
		}
		
		//verificamos valor
		$A_No=0;
		$sql=" SELECT MAX(A_No) AS Expr1 FROM  Asiento_F
		where CodigoU ='".$_SESSION['INGRESO']['CodigoU']."' 
		AND item='".$_SESSION['INGRESO']['item']."'";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//echo $sql;
		$row_count=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$A_No=$row[0];
		}
		if($A_No==null)
		{
			$A_No=1;
		}
		else
		{
			$A_No++;
		}
		//echo $Result[0]['Producto']."<br>";
		$dato[0]['campo']='CODIGO';
		$dato[0]['dato']=$parametros['prod'];
		$dato[1]['campo']='CANT';
		$dato[1]['dato']=1;
		$dato[2]['campo']='PRODUCTO';
		$dato[2]['dato']=$nombre;
		$dato[3]['campo']='PRECIO';
		$dato[3]['dato']=$parametros['Precio'];
		$dato[4]['campo']='Total_IVA';
		$dato[4]['dato']=$parametros['iva'];
		$dato[5]['campo']='TOTAL';
		$dato[5]['dato']=$parametros['total'];
		$dato[6]['campo']='VALOR_TOTAL';
		$dato[6]['dato']=$parametros['valor_total'];
		$dato[7]['campo']='HABIT';
		$dato[7]['dato']=$parametros['me'];
		$dato[8]['campo']='Item';
		$dato[8]['dato']=$_SESSION['INGRESO']['item'];
		$dato[9]['campo']='CodigoU';
		$dato[9]['dato']=$_SESSION['INGRESO']['CodigoU'];
		$dato[10]['campo']='Cta_Inv';
		$dato[10]['dato']=$cta_i;
		$dato[11]['campo']='Cta_Costo';
		$dato[11]['dato']=$cta_CV;
		$dato[12]['campo']='Fecha_IN';
		$dato[12]['dato']='20200209';
		$dato[13]['campo']='Meses';
		$dato[13]['dato']='0';
		$dato[14]['campo']='A_No';
		$dato[14]['dato']=$A_No;
		$dato[15]['campo']='RUTA';
		$dato[15]['dato']='';
		$dato[16]['campo']='Estado';
		$dato[16]['dato']='R';
		// print_r($dato);die();
		if($this->insert_generico("Asiento_F",$dato)=='')
		{	
		
		//hacemos udate
		$sql="UPDATE ". 
		   " Catalogo_Productos SET Estado=1
			WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv = '".$parametros['me']."'
			";
		
		//echo $sql;
		//die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
	
	
	    return 1;		
       }else
       {
       	return -1;
       }
	cerrarSQLSERVERFUN($cid);
}



function agregar_a_pedido($datos1)
{
	$cid=$this->conn->conexion();

//ingresamos
$cuen=count($_SESSION['APPR']['PED']);
//echo $cuen.' ------------------------- ';
	$fecha_actual = date("Y-m-d"); 
	$prod = explode(",", $datos1['prod']);
	$cant = explode(",", $datos1['cant']);
	$obs = explode(",", $datos1['obs']);
	$cop = explode(",", $datos1['cop']);
	$me=$datos1['me'];
	//echo $_POST['prod'].'<br>';
	//echo count($prod).' ------------------------- ';
	for($i=0;$i<(count($prod)-1);$i++)
	{
		//echo $prod[$i].'<br>';
		$sql="SELECT * FROM Catalogo_Productos WHERE (LEN(Cta_Inventario) > 2) AND (LEN(Cta_Costo_Venta) > 2) AND (TC = 'P') AND 
			(Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND  Codigo_Inv = '".trim($prod[$i])."' ORDER BY Codigo_Inv";
		
		// print_r($sql);
		// die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$row_count=0;
		$ii=0;
		$Result = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		{
			$Result[$ii]['Codigo_Inv']=$row['Codigo_Inv'];
			$Result[$ii]['Producto']=$row['Producto'];
			//echo $Result[$ii]['Producto']."<br>";
			$Result[$ii]['PVP']=$row['PVP'];
			$Result[$ii]['PVP_2']=$row['PVP_2'];
			$Result[$ii]['IVA']=$row['IVA'];
			if($Result[$ii]['IVA']==true)
			{
				//buscar variable del iva
				$Result[$ii]['IVA']=$Result[$ii]['PVP']*$_SESSION['INGRESO']['porc'];
				$Result[$ii]['IVA_2']=$Result[$ii]['PVP_2']*$_SESSION['INGRESO']['porc'];
			}
			else
			{
				$Result[$ii]['IVA']=0;
				$Result[$ii]['IVA_2']=0;
			}
			$Result[$ii]['Cta_Inventario']=$row['Cta_Inventario'];
			$Result[$ii]['Cta_Costo_Venta']=$row['Cta_Costo_Venta'];
		}
		/*while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$Result[$ii]['Codigo_Inv']=$row[2];
			$Result[$ii]['Producto']=$row[3];
			//echo $Result[$ii]['Producto']."<br>";
			$Result[$ii]['PVP']=$row[9];
			$Result[$ii]['PVP_2']=$row[40];
			$Result[$ii]['IVA']=$row[10];
			if($Result[$ii]['IVA']==true)
			{
				//buscar variable del iva
				$Result[$ii]['IVA']=$Result[$ii]['PVP']*$_SESSION['INGRESO']['porc'];
				$Result[$ii]['IVA_2']=$Result[$ii]['PVP_2']*$_SESSION['INGRESO']['porc'];
			}
			else
			{
				$Result[$ii]['IVA']=0;
				$Result[$ii]['IVA_2']=0;
			}
			$Result[$ii]['Cta_Inventario']=$row[11];
			$Result[$ii]['Cta_Costo_Venta']=$row[12];
			//$ii++;
		}*/
		//verificamos valor
		$A_No=0;
		$sql=" SELECT MAX(A_No) AS Expr1 FROM  Asiento_F
		where CodigoU ='".$_SESSION['INGRESO']['CodigoU']."' 
		AND item='".$_SESSION['INGRESO']['item']."'";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//echo $sql;
		$row_count=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$A_No=$row[0];
		}
		if($A_No==null)
		{
			$A_No=1;
		}
		else
		{
			$A_No++;
		}
		//echo $Result[0]['Producto']."<br>";
		$dato[0]['campo']='CODIGO';
		$dato[0]['dato']=trim($prod[$i]);
		$dato[1]['campo']='CANT';
		$dato[1]['dato']=$cant[$i];
		$dato[2]['campo']='PRODUCTO';
		$dato[2]['dato']=$Result[0]['Producto'];
		$dato[3]['campo']='PRECIO';
		//si es copa o botella
		if($cop[$i]==1)
		{
			$dato[3]['dato']=$Result[0]['PVP_2'];
		}
		else
		{
			$dato[3]['dato']=$Result[0]['PVP'];
		}
		$dato[4]['campo']='Total_IVA';
		//si es copa o botella
		if($cop[$i]==1)
		{
			$dato[4]['dato']=($Result[0]['IVA_2']*$cant[$i]);
		}
		else
		{
			$dato[4]['dato']=($Result[0]['IVA']*$cant[$i]);
		}
		$dato[5]['campo']='TOTAL';
		//si es copa o botella
		if($cop[$i]==1)
		{
			$dato[5]['dato']=($Result[0]['PVP_2']*$cant[$i]);
		}
		else
		{
			$dato[5]['dato']=($Result[0]['PVP']*$cant[$i]);
		}
		$dato[6]['campo']='VALOR_TOTAL';
		//si es copa o botella
		if($cop[$i]==1)
		{
			$dato[6]['dato']=(($Result[0]['PVP_2']*$cant[$i])+($Result[0]['IVA_2']*$cant[$i]));
		}
		else
		{
			$dato[6]['dato']=(($Result[0]['PVP']*$cant[$i])+($Result[0]['IVA']*$cant[$i]));
		}
		
		$dato[7]['campo']='HABIT';
		$dato[7]['dato']=$me;
		$dato[8]['campo']='Item';
		$dato[8]['dato']=$_SESSION['INGRESO']['item'];
		$dato[9]['campo']='CodigoU';
		$dato[9]['dato']=$_SESSION['INGRESO']['CodigoU'];
		$dato[10]['campo']='Cta_Inv';
		$dato[10]['dato']=$Result[0]['Cta_Inventario'];
		$dato[11]['campo']='Cta_Costo';
		$dato[11]['dato']=$Result[0]['Cta_Costo_Venta'];
		$dato[12]['campo']='Fecha_IN';
		$dato[12]['dato']='20200209';
		$dato[13]['campo']='Meses';
		$dato[13]['dato']='0';
		$dato[14]['campo']='A_No';
		$dato[14]['dato']=$A_No;
		$dato[15]['campo']='RUTA';
		$dato[15]['dato']=$obs[$i];
		$dato[16]['campo']='Estado';
		$dato[16]['dato']='R';
		$lista = $this->pedido_realizado($me);
		$exist = false;
		foreach ($lista as $key => $value) {
			if($value['CodigoU']== $dato[9]['dato'] AND $value['CODIGO']==$dato[0]['dato'])
			{
				$exist = true;
				$cant = $value['CANT']+$dato[1]['dato'];
				$total = $value['TOTAL']+$dato[5]['dato'];
				$val_total = $value['VALOR_TOTAL']+$dato[6]['dato']; 
				$parametros = array('CANT'=>$cant,'TOTAL'=>$total,'VALOR_TOTAL'=>$val_total,'CODIGO'=>$dato[0]['dato'],'codigoU'=>$dato[9]['dato']);
				if($this->update_pedido_linea($parametros)==1)
				{
					break;
				}
			}
		}		
		if($exist ==false)
		{
			$this->insert_generico("Asiento_F",$dato);	
		}
		//hacemos udate
		$sql="UPDATE ". 
		   " Catalogo_Productos SET Estado=1
			WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv = '".$me."'
			";
		
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
	
	}
	return 1;		
	cerrarSQLSERVERFUN($cid);
}


function eliminar_de_pedido($datos)
{
		   $cid=$this->conn->conexion();
			$prod =  $datos['cod_p'];
			$cant = $datos['cant'];
			$cod = $datos['cod'];
			$me=$datos['me'];
			
			$sql="DELETE ". 
			   "FROM Asiento_F
				WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
				AND A_No = '".$cod."' AND HABIT='".$me."' 
				";
		
			//echo $sql;
			//die();
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else{
				return 1;
			}
	cerrarSQLSERVERFUN($cid);
}
function eliminar_abono($datos)
{
	$cid=$this->conn->conexion();
	$monto =  $datos['monto'];
	$comprob = $datos['comprob'];
	$cta = $datos['cta'];
	$me=$datos['me'];
	
	$sql="DELETE ". 
	   "FROM Asiento_Abonos
		WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
		AND (Periodo = '".$_SESSION['INGRESO']['periodo']."')
		AND HABIT='".$me."' AND Cta='".$cta."' AND Abono='".$monto."' 
		AND Comprobante='".$comprob."' 
		";

	//echo ' fff '.$sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 return 0;
	}
	else{
		return 1;
	}
	cerrarSQLSERVERFUN($cid);
}
function pdf_imprimir($me,$param)
{
	$cid=$this->conn->conexion();
	imprimirDocElPF(null,$me,null,null,null,0,$param,'PF',$cid);
	cerrarSQLSERVERFUN($cid);
}
//serie factura
function factura_serie()
{
	$cid=$this->conn->conexion();
	$serie='';
	$sql="SELECT Serie_FA
		FROM Empresas
		WHERE (Item = '".$_SESSION['INGRESO']['item']."')
		";
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$row_count=0;
	$i=0;
	$Result = array();
	$total=0;
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$serie=$row[0];	
	}
	return $serie;
	cerrarSQLSERVERFUN($cid);
}
//numero factura
function factura_numero($ser)
{
	$cid=$this->conn->conexion();
	$numero='';
	$sql="SELECT Item, Concepto, Numero, Periodo, ID
		FROM Codigos
		WHERE (Item = '".$_SESSION['INGRESO']['item']."') AND 
		(Periodo = '".$_SESSION['INGRESO']['periodo']."') AND 
		(Concepto = 'FA_SERIE_".$ser."')";
	
	
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$row_count=0;
	$i=0;
	$Result = array();
	$total=0;
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$numero=$row[2];	
	}
	return $numero;
	cerrarSQLSERVERFUN($cid);
}
function cone_ajaxSQL()
{
	//realizamos conexion
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
	{
		$database=$_SESSION['INGRESO']['Base_Datos'];
		$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
		$user=$_SESSION['INGRESO']['Usuario_DB'];
		$password=$_SESSION['INGRESO']['Contraseña_DB'];
	}
	else
	{
		$database="DiskCover_Prismanet";
		$server="tcp:mysql.diskcoversystem.com, 11433";
		$user="sa";
		$password="disk2017Cover";
	}
	/*$database="DiskCover_Prismanet";
	$server="mysql.diskcoversystem.com";
	$user="sa";
	$password="disk2017Cover";*/
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			echo "fallo conecion sql server";
		}
	}
	return $cid;
}

function insert_generico($tabla=null,$datos=null)
{
	$cid=$this->conn->conexion();
	$sql = "SELECT * from Information_Schema.Tables where TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME='".$tabla."' ORDER BY TABLE_NAME";
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	$tabla_="";
	while( $obj = sqlsrv_fetch_object( $stmt)) 
	{
		//echo $obj->TABLE_NAME."<br />";
		$tabla_=$obj->TABLE_NAME;
	}
	if($tabla_!='')
	{
		//buscamos los campos
		$sql="SELECT        TOP (1) sys.sysindexes.rows
		FROM   sys.sysindexes INNER JOIN
		sys.sysobjects ON sys.sysindexes.id = sys.sysobjects.id
		WHERE   (sys.sysobjects.xtype = 'U') AND (sys.sysobjects.name = '".$tabla_."')
		ORDER BY sys.sysindexes.indid";
		
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta.\n";  
			die( print_r( sqlsrv_errors(), true));  
		} 
		$tabla_cc=0;
		while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			//cantidad de campos
			$tabla_cc=$obj->rows;
		}
		$sql="SELECT COLUMN_NAME,DATA_TYPE,IS_NULLABLE,CHARACTER_MAXIMUM_LENGTH
		FROM Information_Schema.Columns
		WHERE TABLE_NAME = '".$tabla_."'";
		
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		} 
		//consulta sql
		$sql_="INSERT INTO ".$tabla_."
			  (";
		$sql_v=" VALUES 
		(";
		$fecha_actual = date("Y-m-d"); 
		while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			if($obj->COLUMN_NAME!='ID')
			{
				$sql_=$sql_.$obj->COLUMN_NAME." ,";
			}
			
			//recorremos los datos
			$ban=0;
			for($i=0;$i<count($datos);$i++)
			{
				if($obj->COLUMN_NAME==$datos[$i]['campo'])
				{
					if($obj->CHARACTER_MAXIMUM_LENGTH != '' && $obj->CHARACTER_MAXIMUM_LENGTH != null)
					{
						$datos[$i]['dato'] =substr($datos[$i]['dato'],0,$obj->CHARACTER_MAXIMUM_LENGTH);
			        }
					if($obj->DATA_TYPE=='int identity')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='nvarchar')
					{
						$sql_v=$sql_v."'".$datos[$i]['dato']."',";
					}
					if($obj->DATA_TYPE=='ntext')
					{
						$sql_v=$sql_v."'".$datos[$i]['dato']."',";
					}
					if($obj->DATA_TYPE=='tinyint')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='real')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='bit')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='smalldatetime' OR $obj->DATA_TYPE=='datetime')
					{
						$sql_v=$sql_v."'".$datos[$i]['dato']."',";
					}
					if($obj->DATA_TYPE=='money')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='int')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='float')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='smallint')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='uniqueidentifier')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					$ban=1;
				}
			}
			//por defaul
			if($ban==0)
			{
				if($obj->DATA_TYPE=='int identity')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='nvarchar')
				{
					$sql_v=$sql_v."'.',";
				}
				if($obj->DATA_TYPE=='ntext')
				{
					$sql_v=$sql_v."'.',";
				}
				if($obj->DATA_TYPE=='tinyint')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='real')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='bit')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='smalldatetime' OR $obj->DATA_TYPE=='datetime')
				{
					$sql_v=$sql_v."'".$fecha_actual."',";
				}
				if($obj->DATA_TYPE=='money')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='int')
				{
					if($obj->COLUMN_NAME=='ID')
					{
						$sql_v=$sql_v."";
					}
					else
					{
						$sql_v=$sql_v."0,";
					}
				}
				if($obj->DATA_TYPE=='float')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='smallint')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='uniqueidentifier')
				{
					$sql_v=$sql_v."0,";
				}
			}
		}
		//echo $sql_.$sql_v;
		$longitud_cad = strlen($sql_); 
		$cam2 = substr_replace($sql_,")",$longitud_cad-1,1); 
		$longitud_cad = strlen($sql_v); 
		$v2 = substr_replace($sql_v,")",$longitud_cad-1,1); 
		//echo $cam2.$v2;
		$stmt = sqlsrv_query( $cid, $cam2.$v2);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		/*
		
		*/
	}
}



function bucarcliente($keyword)
{
	$cid= $this->conn->conexion();
	$Result = array();
	$sql="SELECT Cliente AS nombre, CI_RUC as id, email FROM Clientes ".
		   "WHERE T <> '.' AND (Cliente LIKE '%".$keyword."%' ) OR  (CI_RUC LIKE '%".$keyword."%') ".
		   "ORDER BY Cliente ";	
	
	// echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt == false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));
		 return  $Result; 
	}
	//echo "<script> ";
	//echo " var countries = [];";
	//echo " var countries = [ ";
	while($row =  sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC))
	{		
		// $Result[]= $row;
		$Result[]= ['nombre'=>$row['nombre'],'id'=>$row['id'],'email'=>utf8_encode($row['email'])];
	}
	cerrarSQLSERVERFUN($cid);
	//sqlsrv_close( $cid );
	return $Result;
	//var_dump($Result);

}

function liberar($me)
{
   $cid= $this->conn->conexion();
   $sql="UPDATE ". 
		   " Catalogo_Productos SET Estado=0
			WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv = '".$me."'
			";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));
			 return -1;  
		}else
		{
			return 1;
		}
	cerrarSQLSERVERFUN($cid);
}
function datos_pre_factura($mesa)
{
	  $cid= $this->conn->conexion();
	$sql=" SELECT TOP(1) * FROM  Clientes
		WHERE  (CI_RUC LIKE '%9999999%')";

	$stmt = sqlsrv_query( $cid, $sql);
	$datos_cli = array();
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos_cli= $row;
	   }

	$sql="SELECT  *
		FROM            Asiento_F
		WHERE        (HABIT = '".$mesa."')";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$lineas=array();
		$preciot=0;
		$iva=0;
		$tota=0;
		$i=0;
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	        {
		      $lineas[]=$row;
		      $preciot+=$row['TOTAL'];
		      $iva+=$row['Total_IVA'];
		      $tota+=$row['VALOR_TOTAL']; 
	        }


	$prefa = array('cliente'=>$datos_cli,'lineas'=>$lineas,'preciot'=>$preciot,'iva'=>$iva,'tota'=>$tota);
	return $prefa;

}
function datos_factura($parametros)
{
	$cid= $this->conn->conexion();
	$sql=" SELECT * FROM  Clientes	WHERE  (CI_RUC = '".$parametros['ci']."')";
	$stmt = sqlsrv_query( $cid, $sql);
	$datos_cli = array();
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$datos_cli= $row;
	   }
	 $sql="SELECT  *
		FROM     Detalle_Factura
		WHERE    (Item = '".$_SESSION['INGRESO']['item']."') AND (Serie = '".$parametros['serie']."') 
		AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Factura = '".$parametros['factura']."')";

	   $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$lineas=array();
		$preciot=0;
		$iva=0;
		$tota=0;
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	        {
		      $lineas[]=$row;
		      $preciot+=($row['Precio']*$row['Cantidad']);
		      $iva+=$row['Total_IVA'];
		      $tota+=$row['Total']+$row['Total_IVA']; 
	        }


	$factura = array('cliente'=>$datos_cli,'lineas'=>$lineas,'preciot'=>$preciot,'iva'=>$iva,'tota'=>$tota);
	// print_r($factura);die();
	return $factura;
}

 	function listar_clientes($query='')
	{
		$cid = $this->conn->conexion();
		if(is_numeric($query))
		{
			 $sql="SELECT Cliente AS nombre, CI_RUC as id, email,Direccion,Telefono FROM Clientes ".
		   "WHERE T <> '.' AND CI_RUC LIKE '".$query."%' ".
		   "ORDER BY Cliente ";	

		}else
		{
			if($query !='')
			{
			 $sql="SELECT Cliente AS nombre, CI_RUC as id, email,Direccion,Telefono FROM Clientes ".
			  "WHERE T <> '.' AND Cliente LIKE '%".$query."%' ".
		       "ORDER BY Cliente ";	
		   }else
		   {
		   	$sql="SELECT Cliente AS nombre, CI_RUC as id, email,Direccion,Telefono FROM Clientes ".
			  "WHERE T <> '.' AND Cliente = '".$query."' ".
		       "ORDER BY Cliente ";	
		   }
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
	   	  
	   	  // print_r($costo);die();
          
         // $datos[]=['id'=>$row['Codigo_Inv'].','.$row['Unidad'].','.$row['Stock_Actual'].','.$row['TC'].','.$row['Valor_Total'].','.$row['Cta_Inventario'],'text'=>utf8_encode($row['Producto'])];
            $datos[]=['id'=>$row['id'].','.utf8_encode($row['email']).','.utf8_encode($row['Direccion']).','.$row['Telefono'],'text'=>utf8_encode($row['nombre'])];
	
	   }
	   // print_r($datos);die();
       return $datos;
	}

	function update_cliente($email,$direccion,$telefono,$ci)
	{
		$cid = $this->conn->conexion();
		$sql = "update Clientes set email='".$email."', Direccion='".$direccion."', Telefono = '".$telefono."' WHERE CI_RUC='".$ci."'";
		// print_r($sql);die();
		$stmt1 =sqlsrv_query( $cid, $sql);
			if( $stmt1 === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}else
			{
				return 1;
			}
	}
	function update_pedido_linea($parametros)
	{
		$cid = $this->conn->conexion();
		$sql = "update Asiento_F set CANT='".$parametros['CANT']."', TOTAL='".$parametros['TOTAL']."', VALOR_TOTAL = '".$parametros['VALOR_TOTAL']."' WHERE CODIGO='".$parametros['CODIGO']."' AND CodigoU='".$parametros['codigoU']."'";
		// print_r($sql);die();
		$stmt1 =sqlsrv_query( $cid, $sql);
			if( $stmt1 === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}else
			{
				return 1;
			}

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


}
?>

