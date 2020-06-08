<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 */
 error_reporting(E_ALL);
ini_set('display_errors', '1');
 require_once("../controlador/contabilidad_controller.php");
 //verificacion titulo accion
 //"139"	"7"	"008"	"PHARMASERVICIOS-SERVICIOS MEDICOS"	"."	"1792509327001"	"QUITO"	"OK"	"."	"192.168.20.2"	"DiskCover_Pharma_Servicios"	"sa"	"Darwin92"	"SQL SERVER"	"1433"	"2019-03-20 00:00:00"	"PHARMA"	"DISKCOVER"	"2019-12-05"	"2019-12-31"
//0702164179001
	$_SESSION['INGRESO']['ti']='';
	if(isset($_GET['ti'])) 
	{
		$_SESSION['INGRESO']['ti']=$_GET['ti'];
	}
	else
	{
		unset( $_SESSION['INGRESO']['ti']);
		$_SESSION['INGRESO']['ti']='DOCUEMNTO ELECTRÓNICO';
	}	
?>
<link rel="stylesheet" href="../../lib/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="../../lib/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="../../lib/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css"> 
 <div class="row">
	<div class="col-xs-12">
		<div class="box">
			  <div class="box-header">
				<?php
					
					$sql = str_replace("AND numero BETWEEN 0 AND 10", "", $_SESSION['INGRESO']['sql']);
					$sql = str_replace('m.numero, Clave_Acceso,', 'm.numero, Clave_Acceso,Documento_Autorizado,', $sql);
					 echo $sql;
					//realizamos conexion
					if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
					{
						$database=$_SESSION['INGRESO']['Base_Datos'];
						//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
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
						$stmt = sqlsrv_query( $cid, $sql);
						if( $stmt === false)  
						{  
							 echo "Error en consulta.\n";  
							 die( print_r( sqlsrv_errors(), true));  
						}  
						$i=0;
						$ii=0;
						while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
						{
							//echo $row[0]; 
							//buscamos la factura
							$sql="SELECT CodigoC,Fecha,CodigoU,CodigoB,Fecha_C,Fecha_V,Fecha_C,Fecha_C,Fecha_V,Fecha_C
								FROM  Facturas
								WHERE  (Factura = '".$row[6]."') 
								AND (Periodo = '.') AND (Serie = '".$row[5]."')  AND (Item = '".$_SESSION['INGRESO']['item']."')";
							$stmt1 = sqlsrv_query( $cid, $sql);
							if( $stmt === false)  
							{  
								 echo "Error en consulta.\n";  
								 die( print_r( sqlsrv_errors(), true));  
							}  
							$i=0;
							while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
							{
								$CodigoC = $row1[0];
								$Fecha = $row1[1]->format('Y-m-d');
								$CodigoU = $row1[2];
								$CodigoB = $row1[3];
								$Fecha_C = $row1[4]->format('Y-m-d');
								$Fecha_V = $row1[5]->format('Y-m-d');
							}
							 echo '<br>'; 
							 $stmt1 = str_replace("ï»¿", "", $row[2]);
							$autorizacion =simplexml_load_string($stmt1);
							$resultado = str_replace("<![CDATA[", "", $autorizacion->comprobante);
							$resultado = str_replace("]]>", "", $resultado);
							 $arr1=etiqueta_xml($resultado,"<codigoPrincipal");
							if(is_array($arr1))
							{
								$arr2=etiqueta_xml($resultado,"<codigoAuxiliar");
								if($arr2=='')
								{
									$arr2=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr2[$i]='';
									}
								}
								$arr3=etiqueta_xml($resultado,"<cantidad");
								if($arr3=='')
								{
									$arr3=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr3[$i]='';
									}
								}
								$arr4='';
								$arr5=etiqueta_xml($resultado,"<descripcion");
								if($arr5=='')
								{
									$arr5=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr5[$i]='';
									}
								}
								$arr6='';
								$arr7=etiqueta_xml($resultado,"<precioUnitario");
								if($arr7=='')
								{
									$arr7=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr7[$i]='';
									}
								}
								$arr8=etiqueta_xml($resultado,"<descuento");
								if($arr8=='')
								{
									$arr8=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr8[$i]='';
									}
								}
								$arr9='';
								$arr10=etiqueta_xml($resultado,"<precioTotalSinImpuesto");
								if($arr10=='')
								{
									$arr10=array();
									//llenamos array vacio
									for ($i=0;$i<count($arr1);$i++)
									{
										$arr10[$i]='';
									}
								}
								$sql = "select * from Detalle_Factura where Factura='".$row[6]."' 
										and Serie='".$row[5]."' and Periodo='.' and Item='".$_SESSION['INGRESO']['item']."' ";
								//echo $sql.'<br>';
								$stmt1 = sqlsrv_query( $cid, $sql);
								if( $stmt1 === false)  
								{  
									 echo "Error en consulta.\n";  
									 die( print_r( sqlsrv_errors(), true));  
								}  
								$ia=0;
								while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
								{
									$ia++;
								}
								for ($i=0;$i<count($arr1);$i++)
								{
									
									//echo $ia.' -- '.$ii.' -- 1 <br>';
									$adi='';
									/*echo $arr1[$i].' - '.$arr2[$i].' - '.$arr3[$i].' - '.$arr4.' - '.$arr5[$i].' - '.$arr6.' - '.
									$arr7[$i].' - '.$arr8[$i].' - '.$arr9.' - '.$arr10[$i].'<br>';*/
									if($ia==0)
									{
										$ii++;
										echo "INSERT INTO Detalle_Factura
										   (Periodo,T,TC,CodigoC,Factura,Fecha,Codigo,CodigoL,Producto,Cantidad,Precio,Total,Total_IVA,Ruta,Ticket,Item,Corte,Reposicion,Total_Desc,No_Hab
										   ,Cod_Ejec,Porc_C,Com_Pag,Cta_Venta,CodigoU,CodBodega,Tonelaje,Costo,Comision,Mes,X,Producto_Aux,Puntos,Autorizacion,Serie,CodMarca,Gramaje,Orden_No
										   ,Mes_No,C,CodigoB,Precio2,Total_Desc2,SubTotal_NC,Total_IVA_NC,Fecha_IN,Fecha_OUT,Cant_Hab,Tipo_Hab,Codigo_Barra,Serie_NC,Autorizacion_NC,Fecha_NC
										   ,Secuencial_NC,Fecha_V,Cant_Bonif,Lote_No,Fecha_Fab,Fecha_Exp,Modelo ,Procedencia,Serie_No,Porc_IVA ,Cantidad_NC ,Total_Desc_NC)
										VALUES
										   ('.','C','FA','".$CodigoC."','".$row[6]."','".$Fecha."','".$arr1[$i]."','.','".$arr5[$i]."','".$arr3[$i]."' ,'".$arr7[$i]."',
										   '".$arr10[$i]."' ,'0' ,'.','.','".$_SESSION['INGRESO']['item']."',0 ,0 ,'".$arr8[$i]."' ,'.','.',0 ,0 ,'.','".$CodigoU."',
										   '.',0 ,0 ,0 ,'Julio','.','.',0 ,'".$row[1]."','".$row[5]."','.','.' ,0 ,'7' ,0 ,'".$CodigoB."',0,0 ,0 ,0 ,
										   '".$Fecha_C."' ,'".$Fecha_V."' ,0 ,'.','.','.','.','".$Fecha_C."' ,0 ,'".$Fecha_V."' ,0 ,'.','".$Fecha_C."' ,'".$Fecha_V."' ,'.','.',
										   '.',0 ,0 ,0);".'<br><br>';
									}
								}
							}
							else
							{
								/*echo etiqueta_xml($resultado,"<codigoPrincipal>").' - '.etiqueta_xml($resultado,"<codigoAuxiliar>").' - '.
								etiqueta_xml($resultado,"<cantidad>").' '.'-'.' '.
								etiqueta_xml($resultado,"<descripcion>").' '.'-'.' '.
								etiqueta_xml($resultado,"<precioUnitario>").' - '.etiqueta_xml($resultado,"<descuento>").' '.'-'.' '.
								etiqueta_xml($resultado,"<precioTotalSinImpuesto>").'<br>';*/
								//buscamos la factura
								$sql="SELECT CodigoC,Fecha,CodigoU,CodigoB,Fecha_C,Fecha_V,Fecha_C,Fecha_C,Fecha_V,Fecha_C
									FROM  Facturas
									WHERE  (Factura = '".$row[6]."') 
									AND (Periodo = '.') AND (Serie = '".$row[5]."')  AND (Item = '".$_SESSION['INGRESO']['item']."')";
								$stmt1 = sqlsrv_query( $cid, $sql);
								if( $stmt1 === false)  
								{  
									 echo "Error en consulta.\n";  
									 die( print_r( sqlsrv_errors(), true));  
								}  
								$i=0;
								while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
								{
									$CodigoC = $row1[0];
									$Fecha = $row1[1]->format('Y-m-d');
									$CodigoU = $row1[2];
									$CodigoB = $row1[3];
									$Fecha_C = $row1[4]->format('Y-m-d');
									$Fecha_V = $row1[5]->format('Y-m-d');
								}
								$sql = "select * from Detalle_Factura where Factura='".$row[6]."' 
										and Serie='".$row[5]."' and Periodo='.' and Item='".$_SESSION['INGRESO']['item']."' ";
								//echo $sql.'<br>';
								$stmt1 = sqlsrv_query( $cid, $sql);
								if( $stmt1 === false)  
								{  
									 echo "Error en consulta.\n";  
									 die( print_r( sqlsrv_errors(), true));  
								}  
								$ia=0;
								while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
								{
									$ia++; 
									//echo " entro <br>";
								}
								//echo $ia.' -- '.$ii.' -- 2 <br>';
								if($ia==0)
								{
									$ii++;
									echo "INSERT INTO Detalle_Factura
										   (Periodo,T,TC,CodigoC,Factura,Fecha,Codigo,CodigoL,Producto,Cantidad,Precio,Total,Total_IVA,Ruta,Ticket,Item,Corte,Reposicion,Total_Desc,No_Hab
										   ,Cod_Ejec,Porc_C,Com_Pag,Cta_Venta,CodigoU,CodBodega,Tonelaje,Costo,Comision,Mes,X,Producto_Aux,Puntos,Autorizacion,Serie,CodMarca,Gramaje,Orden_No
										   ,Mes_No,C,CodigoB,Precio2,Total_Desc2,SubTotal_NC,Total_IVA_NC,Fecha_IN,Fecha_OUT,Cant_Hab,Tipo_Hab,Codigo_Barra,Serie_NC,Autorizacion_NC,Fecha_NC
										   ,Secuencial_NC,Fecha_V,Cant_Bonif,Lote_No,Fecha_Fab,Fecha_Exp,Modelo ,Procedencia,Serie_No,Porc_IVA ,Cantidad_NC ,Total_Desc_NC)
									 VALUES
										   ('.','C','FA','".$CodigoC."','".$row[6]."','".$Fecha."','".etiqueta_xml($resultado,"<codigoPrincipal>")."','.',
										   '".etiqueta_xml($resultado,"<descripcion>")."','".etiqueta_xml($resultado,"<cantidad>")."' ,
										   '".etiqueta_xml($resultado,"<precioUnitario>")."','".etiqueta_xml($resultado,"<precioTotalSinImpuesto>")."' ,'0' ,'.',
										   '.','".$_SESSION['INGRESO']['item']."',0 ,0 ,'".etiqueta_xml($resultado,"<descuento>")."' ,'.','.',0 ,0 ,'.',
										   '".$CodigoU."','.',0 ,0 ,0 ,'Julio','.','.',0 ,'".$row[1]."','".$row[5]."','.','.' ,0 ,'7' ,0 ,'".$CodigoB."',0,0 
										   ,0 ,0 ,'".$Fecha_C."' ,'".$Fecha_V."' ,0 ,'.','.','.','.','".$Fecha_C."' ,0 ,
										   '".$Fecha_V."' ,0 ,'.','".$Fecha_C."' ,'".$Fecha_V."' ,'.','.','.',0 ,0 ,0);".'<br><br>';
								}
								//die();
							}
							/*
							Periodo	T	TC	CodigoC	    Factura	Fecha	                Codigo	CodigoL	                        Producto	            Cantidad	
								.	C	FA	1311956153	6768	2019-07-10 00:00:00.000	SU.034	S001003	Albendazol liquido oral 100mg/5ml frasco x 20	1.00
								
							Precio	Total	Total_IVA	Ruta	Ticket	Item	Corte	Reposicion	Total_Desc	No_Hab	Cod_Ejec	Porc_C	Com_Pag	Cta_Venta
							1.01	1.01	    0.00	.	      .	    008	    0.00	  0.00	        0.00	.	       .	       0	   0	   .	
							
							CodigoU	   CodBodega	Tonelaje	Costo	Comision	Mes	    X	Producto_Aux	Puntos	Autorizacion	
							1714997663	  .	          0.00	     0.00	  0.00	   Julio	.	     .	          0	    1007201901179250932700120010030000067681234567812	
							
							Serie	CodMarca	Gramaje	Orden_No	Mes_No	C	CodigoB	   Precio2	Total_Desc2	SubTotal_NC	Total_IVA_NC	Fecha_IN	
							001003	   .	     0.00	   0	       7	0	1750667642	1.15	   0.00	       0.00	        0.00	    2019-07-10 00:00:00.000	
							
							Fecha_OUT	              Cant_Hab	Tipo_Hab	Codigo_Barra	Serie_NC	Autorizacion_NC	            Fecha_NC	
							2019-07-10 00:00:00.000	      0	        .	         .	          .	              .	                    2019-07-10 00:00:00.000		
							
							Secuencial_NC	Fecha_V	                 Cant_Bonif	Lote_No	 Fecha_Fab	                Fecha_Exp	                    ID	
							      0         2019-07-10 00:00:00.000	      0	         .	 2019-07-10 00:00:00.000	2019-07-10 00:00:00.000	      302220	 	
								  
						    Modelo	Procedencia	Serie_No	Porc_IVA	Cantidad_NC	 Total_Desc_NC
							  .         .	         .	        0	     0.00	        0.00  
							
							INSERT INTO Detalle_Factura
									   (Periodo,T,TC,CodigoC,Factura,Fecha,Codigo,CodigoL,Producto,Cantidad,Precio,Total,Total_IVA,Ruta,Ticket,Item,Corte,Reposicion,Total_Desc,No_Hab
									   ,Cod_Ejec,Porc_C,Com_Pag,Cta_Venta,CodigoU,CodBodega,Tonelaje,Costo,Comision,Mes,X,Producto_Aux,Puntos,Autorizacion,Serie,CodMarca,Gramaje,Orden_No
									   ,Mes_No,C,CodigoB,Precio2,Total_Desc2,SubTotal_NC,Total_IVA_NC,Fecha_IN,Fecha_OUT,Cant_Hab,Tipo_Hab,Codigo_Barra,Serie_NC,Autorizacion_NC,Fecha_NC
									   ,Secuencial_NC,Fecha_V,Cant_Bonif,Lote_No,Fecha_Fab,Fecha_Exp,Modelo ,Procedencia,Serie_No,Porc_IVA ,Cantidad_NC ,Total_Desc_NC)
								 VALUES
									   ('.','C','FA',CodigoC,'$row[6]',Fecha,'$arr1[$i]',CodigoL,'$arr5[$i]','$arr3[$i]' ,'$arr7[$i]','$arr10[$i]' ,'0' ,'.',
									   '.','$_SESSION['INGRESO']['item']',0 ,0 ,'$arr8[$i]' ,'.','.',0 ,0 ,'.',CodigoU,'.',0 ,0 ,0 ,'Julio','.','.',0 ,
									   '$row[1]','$row[5]','.','.' ,0 ,7 ,0 ,CodigoB,Precio2,Total_Desc2 ,0 ,0 ,Fecha_IN ,Fecha_OUT ,0 ,'.','.','.','.',
									   Fecha_NC ,0 ,Fecha_V ,Cant_Bonif ,'.',Fecha_Fab ,Fecha_Exp ,'.','.','.',0 ,0 ,0)
							*/
						}
						//889
						echo "Total: ".$ii;
						sqlsrv_close( $cid );
					}
				?>
			  </div>
		</div>
	</div>
</div>
