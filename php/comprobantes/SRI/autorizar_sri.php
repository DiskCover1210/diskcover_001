<?php 
require_once(dirname(__DIR__,2)."/db/db.php");
date_default_timezone_set('America/Guayaquil');

@session_start(); 

$controlador = new autorizacion_sri();
if(isset($_GET['autorizar']))
{
	$parametros = $_POST['parametros'];
     echo json_encode($controlador->Autorizar($parametros));
}

/**
 * 
 */
class autorizacion_sri
{
	private $clave;
	//Metodo de encriptación
	private $method;
	private $iv;
	private $conn;
	// Puedes generar una diferente usando la funcion $getIV()
	
	function __construct()
	{
		$this->clave = 'Una cadena, muy, muy larga para mejorar la encriptacion';
		$this->method = 'aes-256-cbc';
		$this->iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");
		$this->conn = new Conectar();
	}
	function encriptar($dato)
	{
		return openssl_encrypt ($dato, $this->method, $this->clave, false, $this->iv);
	}
	function desencriptar($dato)
	{
		 return openssl_decrypt($dato, $this->method, $this->clave, false, $this->iv);
	}

	function Autorizar($parametros)
	{
		$cabecera['ambiente']=$_SESSION['INGRESO']['Ambiente'];
	    $cabecera['ruta_ce']=$_SESSION['INGRESO']['Ruta_Certificado'];
	    $cabecera['clave_ce']=$_SESSION['INGRESO']['Clave_Certificado'];
	    $cabecera['nom_comercial_principal']=$this->quitar_carac($_SESSION['INGRESO']['Nombre_Comercial']);
	    $cabecera['razon_social_principal']=$this->quitar_carac($_SESSION['INGRESO']['Razon_Social']);
	    $cabecera['ruc_principal']=$_SESSION['INGRESO']['RUC'];
	    $cabecera['direccion_principal']= $this->quitar_carac($_SESSION['INGRESO']['Direccion']);
	    $cabecera['serie']=$parametros['serie'];
	    $cabecera['factura']=$parametros['num_fac'];
	    $cabecera['esta']=substr($parametros['serie'],0,3); 
	    $cabecera['pto_e']=substr($parametros['serie'],3,5); 
	    $cabecera['cod_doc']=$parametros['cod_doc'];
	    $cabecera['item']=$_SESSION['INGRESO']['item'];
	    $cabecera['tc']=$parametros['tc'];
	    $cabecera['periodo']=$_SESSION['INGRESO']['periodo'];


	    if($parametros['cod_doc']=='01')
	    {
	    	//datos de factura
	    	$datos_fac = $this->datos_factura($parametros['serie'],$parametros['num_fac']);
	    	// print_r($datos_fac);die();
	    	    $cabecera['RUC_CI']=$datos_fac[0]['RUC_CI'];
				$cabecera['Fecha']=$datos_fac[0]['Fecha']->format('Y-m-d');
				$cabecera['Razon_Social']=$this->quitar_carac($datos_fac[0]['Razon_Social']);
				$cabecera['Direccion_RS']=$this->quitar_carac($datos_fac[0]['Direccion_RS']);
				$cabecera['Sin_IVA']= number_format($datos_fac[0]['Sin_IVA'],2);
				$cabecera['Descuento']=number_format($datos_fac[0]['Descuento']+$datos_fac[0]['Descuento2'],2);
				$cabecera['baseImponible']=number_format($datos_fac[0]['Sin_IVA']+$cabecera['Descuento'],2);
				$cabecera['Porc_IVA']=number_format($datos_fac[0]['Porc_IVA'],2);
				$cabecera['Con_IVA']=number_format($datos_fac[0]['Con_IVA'],2);
				$cabecera['Total_MN']=number_format($datos_fac[0]['Total_MN']);
				if($datos_fac[0]['Forma_Pago'] == '.')
				{
					$cabecera['formaPago']='01';
				}else
				{
					$cabecera['formaPago']=$datos_fac[0]['Forma_Pago'];
				}
				$cabecera['Propina']=$datos_fac[0]['Propina'];
				$cabecera['Autorizacion']=$datos_fac[0]['Autorizacion'];
				$cabecera['Imp_Mes']=$datos_fac[0]['Imp_Mes'];
				$cabecera['SP']=$datos_fac[0]['SP'];
				$cabecera['CodigoC']=$datos_fac[0]['CodigoC'];
				$cabecera['TelefonoC']=$datos_fac[0]['Telefono_RS'];
				$cabecera['Orden_Compra']=$datos_fac[0]['Orden_Compra'];
				$cabecera['baseImponibleSinIva']=$cabecera['Sin_IVA']-$datos_fac[0]['Desc_0'];
				$cabecera['baseImponibleConIva']=$cabecera['Con_IVA']-$datos_fac[0]['Desc_X'];
				$cabecera['totalSinImpuestos']=$cabecera['Sin_IVA']+$cabecera['Con_IVA'] - $cabecera['Descuento'];
				$cabecera['IVA']=number_format($datos_fac[0]['IVA'],2);
				$cabecera['Total_MN']=number_format($datos_fac[0]['Total_MN'],2);
				$cabecera['descuentoAdicional']=0;
				$cabecera['moneda']="DOLAR";
				$cabecera['tipoIden']='';

			//datos de cliente
	    	$datos_cliente = $this->datos_cliente($datos_fac[0]['CodigoC']);
	    	// print_r($datos_cliente);die();
	    	    $cabecera['Cliente']=$this->quitar_carac($datos_cliente[0]['Cliente']);
				$cabecera['DireccionC']=$this->quitar_carac($datos_cliente[0]['Direccion']);
				$cabecera['TelefonoC']=$datos_cliente[0]['Telefono'];
				$cabecera['EmailR']=$this->quitar_carac($datos_cliente[0]['Email2']);
				$cabecera['EmailC']=$this->quitar_carac($datos_cliente[0]['Email']);
				$cabecera['Contacto']=$datos_cliente[0]['Contacto'];
				$cabecera['Grupo']=$datos_cliente[0]['Grupo'];

			//codigo verificador 
				if($cabecera['RUC_CI']=='9999999999999')
				  {
				  	$cabecera['tipoIden']='07';
			      }else
			      {
			      	$cod_veri = $this->digito_verificadorf($datos_fac[0]['RUC_CI'],1);
			      	switch ($cod_veri) {
			      		case 'R':
			      			$cabecera['tipoIden']='04';
			      			break;
			      		case 'C':
			      			$cabecera['tipoIden']='05';
			      			break;
			      		case 'O':
			      			$cabecera['tipoIden']='06';
			      			break;
			      	}
			      }
			    $cabecera['codigoPorcentaje']=0;
			    if(($cabecera['Porc_IVA']*100)>12)
			    {
			       $cabecera['codigoPorcentaje']=3;
			    }else
			    {
			      $cabecera['codigoPorcentaje']=2;
			    }
			   //detalle de factura
			    $detalle = array();
			    $cuerpo_fac = $this->detalle_factura($cabecera['serie'],$cabecera['factura'],$cabecera['Autorizacion'],$cabecera['tc']);
			    foreach ($cuerpo_fac as $key => $value) 
			    {
			    	$producto = $this->datos_producto($value['Codigo']);
			    	$detalle[$key]['Codigo'] =  $value['Codigo'];
			    	$detalle[$key]['Cod_Aux'] =  $producto[0]['Desc_Item'];
				    $detalle[$key]['Cod_Bar'] =  $producto[0]['Codigo_Barra'];
				    $detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']);
				    $detalle[$key]['Cantidad'] = $value['Cantidad'];
				    $detalle[$key]['Precio'] = $value['Precio'];
				    $detalle[$key]['descuento'] = $value['Total_Desc']+$value['Total_Desc2'];
				  if ($cabecera['Imp_Mes']==true)
				  {
				   	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', '.$value['Ticket'].': '.$value['Mes'].' ';
				  }
				  if($cabecera['SP']==true)
				  {
				  	$detalle[$key]['Producto'] = $this->quitar_carac($value['Producto']).', Lote No. '.$value['Lote_No'].
					', ELAB. '.$value['Fecha_Fab'].
					', VENC. '.$value['Fecha_Exp'].
					', Reg. Sanit. '.$value['Reg_Sanitario'].
					', Modelo: '.$value['Modelo'].
					', Procedencia: '.$value['Procedencia'];
				  }
				   $detalle[$key]['SubTotal'] = ($value['Cantidad']*$value['Precio'])-($value['Total_Desc']+$value['Total_Desc2']);
				   $detalle[$key]['Serie_No'] = $value['Serie_No'];
				   $detalle[$key]['Total_IVA'] = number_format($value['Total_IVA'],2);
				   $detalle[$key]['Porc_IVA']= $value['Porc_IVA'];
			    }
			    $cabecera['fechaem']=  date("d/m/Y", strtotime($cabecera['Fecha']));
			    // print_r($cabecera);print_r($detalle);die();
	            
	           $respuesta = $this->generar_xml($cabecera,$detalle);
	           $num_res = count($respuesta);
	           if($num_res>=2)
	           {
	           	// print_r($respuesta);die();
	           	if($num_res!=2)
	           	{
	           	 $estado = explode(' ', $respuesta[2]);
	           	 if($estado[1].' '.$estado[2]=='FACTURA AUTORIZADO')
	           	 {
	           	 	$respuesta = $this->actualizar_datos_CE(trim($estado[0]),$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['item'],$cabecera['Autorizacion']);
	           	 	if($respuesta==1)
	           	 	{
	           	 	  return array('respuesta'=>1);
	           	 	}
	           	 }else
	           	 {

	           	   $compro = explode('FACTURA', $respuesta[2]);
	           	   $entidad= '001';
	           	   $url_No_autorizados ='../../comprobantes/entidades/entidad_'.$entidad."/CE_".$entidad.'/No_autorizados/';
	           	   $resp = array('respuesta'=>2,'ar'=>trim($compro[0]).'.xml','url'=>$url_No_autorizados);
	           	 	return $resp;
	           	 }
	           	}else
	           	{
	           	 $estado = explode(' ', $respuesta[1]);
	           	 if($estado[1].' '.$estado[2]=='FACTURA AUTORIZADO')
	           	 {
	           	 	$respuesta = $this->actualizar_datos_CE(trim($estado[0]),$cabecera['tc'],$cabecera['serie'],$cabecera['factura'],$cabecera['item'],$cabecera['Autorizacion']);
	           	 	if($respuesta==1)
	           	 	{
	           	 	  return array('respuesta'=>1);
	           	 	}
	           	 }

	           	}

	           }else
	           {
	           	if($respuesta[1]=='Autorizado')
	           	{
	           		return array('respuesta'=>3);

	           	}else{
	           		$resp = utf8_encode($respuesta[1]);
	           		return $resp;
	           	}
	           }

	    }
	}

	function datos_factura($serie,$fact)
	{
		$con = $this->conn->conexion();
		$sql = "SELECT * From Facturas WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND TC = 'FA' AND Serie = '".$serie."' AND Factura = ".$fact." AND LEN(Autorizacion) = 13 AND T <> 'A' ";
		// print_r($sql);die();
		$stmt = sqlsrv_query($con, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	   }
	   $datos = array();
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
			{
				$datos[] = $row;
	        }
	        // print_r($datos);die();
	        return $datos;
	}

	function datos_cliente($codigo)
	{

		$con = $this->conn->conexion();
		$sql = "SELECT * From Clientes WHERE Codigo = '".$codigo."'";
		// print_r($sql);die();
		$stmt = sqlsrv_query($con, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	   }
	   $datos = array();
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
			{
				$datos[] = $row;
	        }
	        // print_r($datos);die();
	        return $datos;

	}

	function detalle_factura($serie,$factura,$autorizacion,$tc)
	{
		$con = $this->conn->conexion();
		$sql="SELECT DF.*,CP.Reg_Sanitario,CP.Marca FROM Detalle_Factura As DF, Catalogo_Productos As CP WHERE DF.Item = '".$_SESSION['INGRESO']['item']."'
		    AND DF.Periodo = '".$_SESSION['INGRESO']['periodo']."'
		    AND DF.TC = '".$tc."'
		    AND DF.Serie = '".$serie."' 
			AND DF.Autorizacion = '".$autorizacion."' 
			AND DF.Factura = '".$factura."' 
			AND LEN(DF.Autorizacion) >= 13 
			AND DF.T <> 'A' 
			AND DF.Item = CP.Item 
			AND DF.Periodo = CP.Periodo 
			AND DF.Codigo = CP.Codigo_Inv 
			ORDER BY DF.ID,DF.Codigo;";
			// print_r($sql);die();
			$stmt = sqlsrv_query($con, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	   }
	   $datos = array();
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
			{
				$datos[] = $row;
	        }
	        // print_r($datos);die();
	        return $datos;
	}

	function datos_producto($codigo)
	{
		$con = $this->conn->conexion();
		$sql="SELECT * from Catalogo_Productos WHERE Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo_Inv = '".$codigo."';";
		$stmt = sqlsrv_query($con, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	   }
	   $datos = array();
	   while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC) ) 
			{
				$datos[] = $row;
	        }
	        // print_r($datos);die();
	        return $datos;
	}

    function digito_verificadorf($ruc,$tipo=null,$pag=null,$idMen=null,$item=null)
    {
		$DigStr = "";
		$VecDig = "";
		$Dig3 = "";
		$sSQLRUC = "";
		$CodigoEmp = "";
		$Producto = "";
		$SumaDig = "";
		$NumDig = "";
		$ValDig = "";
		$TipoModulo = "";
		$CodigoRUC = "";
		$Residuo = "";
		//echo $ruc.' ';
		$Dig3 = substr($ruc, 2, 1);
		//echo $Dig3;
		//$Codigo_RUC_CI = substr($ruc, 0, 10);
		//echo $Dig3.' '.$Codigo_RUC_CI ;
		$Tipo_Beneficiario = "P";
		//$NumEmpresa='001';
		$NumEmpresa=$item;
		//echo $item.' dddvc '.$NumEmpresa;
		$Codigo_RUC_CI = $NumEmpresa . "0000001";
		$Digito_Verificador = "-";
		$RUC_CI = $ruc;
		$RUC_Natural = False;
		//echo $Codigo_RUC_CI;
		//die();
		if($ruc == "9999999999999" )
		{
			$Tipo_Beneficiario = "R";
			$Codigo_RUC_CI = substr($ruc, 0, 10);
			$Digito_Verificador = 9;
			$DigStr = "9";
			//echo ' ccc '.$Codigo_RUC_CI;
			//die();
		}
		else
		{
			$DigStr = $ruc;
			$TipoBenef = "P";
			$VecDig = "000000000";
			$TipoModulo = 1;
			If (is_numeric($ruc) And $ruc <= 0)
			{
				$Codigo_RUC_CI = $NumEmpresa & "0000001";
			}
			Else
			{
				//es cedula
				if(strlen($ruc)==10 and is_numeric($ruc))
				{
					$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
					$arr1 = str_split($ruc);
					$resu = array();
					$resu1=0;
					$coe1=0;
					$pro='';
					$ter='';
					$TipoModulo=10;
					//validador
					$ban=0;
					for($jj=0;$jj<(strlen($ruc));$jj++)
					{
						//echo $arr1[$jj].' -- '.$jj.' cc ';
						//validar los dos primeros registros
						if($jj==0 or $jj==1)
						{
							$pro=$pro.$arr1[$jj];
						}
						if($jj==2)
						{
							$ter=$arr1[$jj];
						}
						//operacion suma
						if($jj<=(strlen($ruc)-2))
						{
							$resu[$jj]=$coe[$jj]*$arr1[$jj];
							if($resu[$jj]>=10)
							{
								$resu[$jj]=$resu[$jj]-9;
							}
							//suma
							$resu1=$resu[$jj]+$resu1;
						}
						//ultimo digito
						if($jj==(strlen($ruc)-1))
						{
							//echo " entro ";
							$coe1=$arr1[$jj];
						}
						
					}
					//verificamos los dos primeros registros
					if($pro>=24)
					{
						//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
						$ban=1;
					}
					//verificamos el tercer registros
					if($ter>6)
					{
						//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
						$ban=1;
					}
					//partimos string
					$arr2 = str_split($resu1);
					for($jj=0;$jj<(strlen($resu1));$jj++)
					{
						if($jj==0)
						{
							$arr2[$jj]=$arr2[$jj]+1;
						}
					}
					//aumentamos a la siguiente decena
					$resu2=$arr2[0].'0';
					//resultado del ultimo coeficioente
					$resu3 = $resu2- $resu1;
					$Residuo = $resu1 % $TipoModulo;
					//echo ' dsdsd '.$Residuo;
					//die();
					If ($Residuo == 0)
					{
					  $Digito_Verificador = "0";
					}
					Else
					{
					   $Residuo = $TipoModulo - $Residuo;
					   $Digito_Verificador = $Residuo;
					}
					//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
					if($ban==0)
					{
						If ($Digito_Verificador == substr($ruc, 9, 1))
						{
							$Tipo_Beneficiario = "C";
						}	
					}					
				}
				else
				{
					//caso ruc
					if(strlen($ruc)==13 and is_numeric($ruc))
					{
						//caso ruc ecuatorianos de extrangeros
						$Tipo_Beneficiario='O';
						if ($Dig3 == 6 )
						{
							$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
							$arr1 = str_split($ruc);
							$resu = array();
							$resu1=0;
							$coe1=0;
							$pro='';
							$ter='';
							$TipoModulo=10;
							//validador
							$ban=0;
							for($jj=0;$jj<(count($coe));$jj++)
							{
								//echo $arr1[$jj].' -- '.$jj.' cc ';
								//validar los dos primeros registros
								if($jj==0 or $jj==1)
								{
									$pro=$pro.$arr1[$jj];
								}
								if($jj==2)
								{
									$ter=$arr1[$jj];
								}
								//operacion suma
								if($jj<=(count($coe)-2))
								{
									$resu[$jj]=$coe[$jj]*$arr1[$jj];
									if($resu[$jj]>=10)
									{
										$resu[$jj]=$resu[$jj]-9;
									}
									//suma
									$resu1=$resu[$jj]+$resu1;
								}
								//ultimo digito
								if($jj==(count($coe)-1))
								{
									//echo " entro ";
									$coe1=$arr1[$jj];
								}
								
							}
							//verificamos los dos primeros registros
							if($pro>=24)
							{
								//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
								$ban=1;
							}
							//verificamos el tercer registros
							if($ter>6)
							{
								//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
								$ban=1;
							}
							//partimos string
							$arr2 = str_split($resu1);
							for($jj=0;$jj<(strlen($resu1));$jj++)
							{
								if($jj==0)
								{
									$arr2[$jj]=$arr2[$jj]+1;
								}
							}
							//aumentamos a la siguiente decena
							$resu2=$arr2[0].'0';
							//resultado del ultimo coeficioente
							$resu3 = $resu2- $resu1;
							$Residuo = $resu1 % $TipoModulo;
							//echo ' dsdsd '.$Residuo;
							//die();
							If ($Residuo == 0)
							{
							  $Digito_Verificador = "0";
							}
							Else
							{
							   $Residuo = $TipoModulo - $Residuo;
							   $Digito_Verificador = $Residuo;
							}
							//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
							if($ban==0)
							{
								If ($Digito_Verificador == substr($ruc, 9, 1))
								{
									$Tipo_Beneficiario = "R";
									$RUC_Natural = True;
								}	
							}	
						}
						if($Tipo_Beneficiario=='O')
						{
							$TipoModulo = 11;
							//echo $Dig3.' qmm ';
							if (($Dig3 <= 5) and ($Dig3 >= 0))
							{
								$TipoModulo = 10;
								$TipoModulo1=9;
								$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
								$VecDig = "212121212";
								//echo " aquiii 1 ";
							}
							else
							{
								if ($Dig3 == 6)
								{
									$coe = array("3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=8;
									$VecDig = "32765432";
									//echo " aquiii 2 ";
								}
								else
								{
									if($Dig3 == 9)
									{
										$coe = array("4", "3", "2", "7", "6","5", "4", "3", "2");
										$TipoModulo1=9;
										$VecDig = "432765432";
										//echo " aquiii 3 ";/
									}
									else
									{
										$VecDig = "222222222";
										$TipoModulo1=9;
										//echo " aquiii 4 ";
										$coe = array("2", "2", "2", "2", "2","2", "2", "2", "2");
									}
								}
							}
							$arr1 = str_split($ruc);
							$resu = array();
							$resu1=0;
							$coe1=0;
							$pro='';
							$ter='';
							$ban=0;
							for($jj=0;$jj<($TipoModulo1);$jj++)
							{
								if($jj==0 or $jj==1)
								{
									$pro=$pro.$arr1[$jj];
								}
								if($jj==2)
								{
									$ter=$arr1[$jj];
								}
								if($jj<=(strlen($ruc)-2))
								{
									$resu[$jj]=$coe[$jj]*$arr1[$jj];
									If (0 <= $Dig3 And $Dig3 <= 5 And $resu[$jj] > 9)
									{
										$resu[$jj]=$resu[$jj]-9;
									}									
									//suma
									$resu1=$resu[$jj]+$resu1;
									
								}
								if($jj==(strlen($ruc)-1))
								{
									//echo " entro ";
									$coe1=$arr1[$jj];
								}
								
							}
							//partimos string
							$arr2 = str_split($resu1);
							for($jj=0;$jj<(strlen($resu1));$jj++)
							{
								if($jj==0)
								{
									$arr2[$jj]=$arr2[$jj]+1;
								}
							}
							//aumentamos a la siguiente decena
							$resu2=$arr2[0].'0';
							//resultado del ultimo coeficioente
							$resu3 = $resu2- $resu1;
							$Residuo = $resu1 % $TipoModulo;
							If ($Residuo == 0)
							{
							  $Digito_Verificador = "0";
							}
							Else
							{
							   $Residuo = $TipoModulo - $Residuo;
							   $Digito_Verificador = $Residuo;
							}
							//echo $Digito_Verificador.' '.$Dig3.' ';
							If ($Dig3 == 6) 
							{
								If ($Digito_Verificador = substr($ruc, 8, 1)) 
								{
									$Tipo_Beneficiario = "R";
								}
							} 
							Else
							{
								If ($Digito_Verificador == substr($ruc, 9, 1))
								{
									$Tipo_Beneficiario = "R";
								}							
							}
							If ($Dig3 < 6 )
							{
								$RUC_Natural = True;
							}
						}
					}
					else
					{
						if(strlen($ruc)==48 and is_numeric($ruc))
						{
							
						}
					}
				}
			}
			if(substr($ruc, 12, 1)!='1')
			{
				$Tipo_Beneficiario = 'O';
			}
			
		}
	    if($tipo==null OR $tipo==0)
	    {	
		   return $Digito_Verificador;
	    }
	    else
	    {
		   return $Tipo_Beneficiario;
	    }
    } 

    function generaCeros($numero, $tamaño=null)
    {
	   //obtengop el largo del numero
	   $largo_numero = strlen($numero);
	   //especifico el largo maximo de la cadena
	   if($tamaño==null)
	   {
		  $largo_maximo = 7;
	   }
	   else
	   {
		 $largo_maximo = $tamaño;
	   }
	   //tomo la cantidad de ceros a agregar
	   $agregar = $largo_maximo - $largo_numero;
	   //agrego los ceros
	   for($i =0; $i<$agregar; $i++){
	     $numero = "0".$numero;
	   }
	   //retorno el valor con ceros
	   return $numero;
    }

    function digito_verificador($cadena)
    {
	    $cadena=trim($cadena);
	    $baseMultiplicador=7;
	    $aux=new SplFixedArray(strlen($cadena));
	    $aux=$aux->toArray();
	    $multiplicador=2;
	    $total=0;
	    $verificador=0;
	    for($i=count($aux)-1;$i>=0;--$i)
	    {
		    $aux[$i]= substr($cadena,$i,1);
		    $aux[$i]*=$multiplicador;
		    ++$multiplicador;
		    if($multiplicador>$baseMultiplicador)
		    {
			    $multiplicador=2;
		    }
		$total+=$aux[$i];
	    }
	    if(($total==0)||($total==1)) $verificador=0;
	    else
	    {
		    $verificador=(11-($total%11)==11)?0:11-($total%11);
	    }
	    if($verificador==10)
	    {
		    $verificador=1;
	    }
	    return $verificador;
    }


    //parametros clave de acceso
    /*
    1 Fecha de Emisión Numérico             ddmmaaaa       8 Obligatorio <claveAcceso> 
    2 Tipo de Comprobante                   Tabla 3        2 
    3 Número de RUC                         1234567890001  13 
    4 Tipo de Ambiente                      Tabla 4        1 
    5 Serie                                 001001         6 
    6 Número del Comprobante (secuencial)   000000001      9 
    7 Código Numérico                       Numérico       8 
    8 Tipo de Emisión                       Tabla 2        1 
    9 Dígito Verificador (módulo 11 )       Numérico       1*/
   function generar_xml($cabecera,$detalle)
   {
   	    $entidad='001';
	    $empresa=$cabecera['item'];
	    $fecha = str_replace('/','',$cabecera['fechaem']);
	    $ruc=$cabecera['ruc_principal'];
	    $tc=$cabecera['cod_doc'];
	    $serie=$cabecera['serie'];
	    $numero=$this->generaCeros($cabecera['factura'], '9');
	    $emi='1';
	    $nume='12345678';
	    $ambiente=$cabecera['ambiente'];
	    $codDoc=$cabecera['cod_doc'];
	
	    $dig=$this->digito_verificadorf($ruc);

	    $compro=$fecha.$tc.$ruc.$ambiente.$serie.$numero.$nume.$emi;
	    $dig=$this->digito_verificador($compro);
	    $compro=$fecha.$tc.$ruc.'1'.$serie.$numero.$nume.$emi.$dig;

        //verificamos si existe una carpeta de la entidad si no existe las creamos
	    $carpeta_entidad = "../entidades/entidad_".$entidad;
	    $carpeta_autorizados = "";		  
        $carpeta_generados = "";
        $carpeta_firmados = "";
        $carpeta_no_autori = "";

	if(file_exists($carpeta_entidad))
	{
		$carpeta_comprobantes = $carpeta_entidad.'/CE_'.$empresa;
		if(file_exists($carpeta_comprobantes))
		{
		  $carpeta_autorizados = $carpeta_comprobantes."/Autorizados";		  
		  $carpeta_generados = $carpeta_comprobantes."/Generados";
		  $carpeta_firmados = $carpeta_comprobantes."/Firmados";
		  $carpeta_no_autori = $carpeta_comprobantes."/No_autorizados";

			if(!file_exists($carpeta_autorizados))
			{
				mkdir($carpeta_entidad."/CE_".$empresa."/Autorizados", 0777);
			}
			if(!file_exists($carpeta_generados))
			{
				 mkdir($carpeta_entidad.'/CE_'.$empresa.'/Generados', 0777);
			}
			if(!file_exists($carpeta_firmados))
			{
				 mkdir($carpeta_entidad.'/CE_'.$empresa.'/Firmados', 0777);
			}
			if(!file_exists($carpeta_no_autori))
			{
				 mkdir($carpeta_entidad.'/CE_'.$empresa.'/No_autorizados', 0777);
			}
		}else
		{
			mkdir($carpeta_entidad.'/CE_'.$empresa, 0777);
			mkdir($carpeta_entidad."/CE_".$empresa."/Autorizados", 0777);
		    mkdir($carpeta_entidad.'/CE_'.$empresa.'/Generados', 0777);
		    mkdir($carpeta_entidad.'/CE_'.$empresa.'/Firmados', 0777);
		    mkdir($carpeta_entidad.'/CE_'.$empresa.'/No_autorizados', 0777);
		}
	}else
	{
		   mkdir($carpeta_entidad, 0777);
		   mkdir($carpeta_entidad.'/CE_'.$empresa, 0777);
		   mkdir($carpeta_entidad."/CE_".$empresa."/Autorizados", 0777);
		   mkdir($carpeta_entidad.'/CE_'.$empresa.'/Generados', 0777);
		   mkdir($carpeta_entidad.'/CE_'.$empresa.'/Firmados', 0777);
		   mkdir($carpeta_entidad.'/CE_'.$empresa.'/No_autorizados', 0777);	    
	}
		

	if(file_exists($carpeta_autorizados.'/'.$compro.'.xml'))
	{
		$respuesta = array('1'=>'Autorizado');
		return $respuesta;
	}
	
	// "Create" the document.
	$xml = new DOMDocument( "1.0", "UTF-8" );
	$xml->formatOutput = true;
	$xml->preserveWhiteSpace = false; 

	// Create some elements.
	switch ($codDoc) {
		case '01':
			$xml_factura = $xml->createElement( "factura" );
			break;
		case '07':
			$xml_factura = $xml->createElement( "comprobanteRetencion" );
			break;
		case '03':
			$xml_factura = $xml->createElement( "factura" );
			break;
		case '04':
			$xml_factura = $xml->createElement( "notaCredito" );
			break;
		case '05':
			$xml_factura = $xml->createElement( "notaDebito" );
			break;
		case '06':
			$xml_factura = $xml->createElement( "guiaRemision" );
			break;
		
		
	}
	
	$xml_factura->setAttribute( "id", "comprobante" );
	$xml_factura->setAttribute( "version", "1.1.0" );
	$xml_infoTributaria = $xml->createElement( "infoTributaria" );
	$xml_ambiente = $xml->createElement( "ambiente",$ambiente );
	$xml_tipoEmision = $xml->createElement( "tipoEmision",'1' );
	$xml_razonSocial = $xml->createElement( "razonSocial",$cabecera['razon_social_principal']);
	$xml_nombreComercial = $xml->createElement( "nombreComercial",$cabecera['nom_comercial_principal'] );
	$xml_ruc = $xml->createElement( "ruc",$cabecera['ruc_principal'] );
	$xml_claveAcceso = $xml->createElement( "claveAcceso",$compro);
	$xml_codDoc = $xml->createElement( "codDoc",$codDoc );
	$xml_estab = $xml->createElement( "estab",$cabecera['esta'] );
	$xml_ptoEmi = $xml->createElement( "ptoEmi",$cabecera['pto_e'] );
	$xml_secuencial = $xml->createElement( "secuencial",$numero );
	$xml_dirMatriz = $xml->createElement( "dirMatriz",$cabecera['direccion_principal'] );

	$xml_infoTributaria->appendChild( $xml_ambiente );
	$xml_infoTributaria->appendChild( $xml_tipoEmision );
	$xml_infoTributaria->appendChild( $xml_razonSocial );
	$xml_infoTributaria->appendChild( $xml_nombreComercial );
	$xml_infoTributaria->appendChild( $xml_ruc );
	$xml_infoTributaria->appendChild( $xml_claveAcceso );
	$xml_infoTributaria->appendChild( $xml_codDoc );
	$xml_infoTributaria->appendChild( $xml_estab );
	$xml_infoTributaria->appendChild( $xml_ptoEmi );
	$xml_infoTributaria->appendChild( $xml_secuencial );
	$xml_infoTributaria->appendChild( $xml_dirMatriz );

	$xml_infoFactura = $xml->createElement( "infoFactura" );

	$xml_fechaEmision = $xml->createElement( "fechaEmision",$cabecera['fechaem'] );
	if($cabecera['Direccion_RS']=='.')
	{
		$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['direccion_principal']);
	}
	else
	{
		$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera['Direccion_RS'] );
	}
	$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'SI' );
	$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionComprador",$cabecera['tipoIden'] );
	$xml_razonSocialComprador = $xml->createElement( "razonSocialComprador",$cabecera['Razon_Social'] );
	$xml_identificacionComprador = $xml->createElement( "identificacionComprador",$cabecera['RUC_CI'] );
	$xml_totalSinImpuestos = $xml->createElement( "totalSinImpuestos",round($cabecera['totalSinImpuestos'],2) );
	$xml_totalDescuento = $xml->createElement( "totalDescuento",round($cabecera['Descuento'],2) );

	$xml_infoFactura->appendChild( $xml_fechaEmision );
	$xml_infoFactura->appendChild( $xml_dirEstablecimiento );
	$xml_infoFactura->appendChild( $xml_obligadoContabilidad );
	$xml_infoFactura->appendChild( $xml_tipoIdentificacionComprador );
	$xml_infoFactura->appendChild( $xml_razonSocialComprador );
	$xml_infoFactura->appendChild( $xml_identificacionComprador );
	$xml_infoFactura->appendChild( $xml_totalSinImpuestos );
	$xml_infoFactura->appendChild( $xml_totalDescuento );

	$xml_totalConImpuestos = $xml->createElement( "totalConImpuestos" );
	//sin iva
	$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
	$xml_codigo = $xml->createElement( "codigo",'2' );
	$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0'  );
	$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",round($cabecera['descuentoAdicional'],2) );
	$xml_baseImponible = $xml->createElement( "baseImponible",round($cabecera['baseImponibleSinIva'],2) );
	//$xml_tarifa = $xml->createElement( "tarifa",'0.00' );
	$xml_valor = $xml->createElement( "valor",'0.00' );
	
	$xml_totalImpuesto->appendChild( $xml_codigo );
	$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
	$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
	$xml_totalImpuesto->appendChild( $xml_baseImponible );
	//$xml_totalImpuesto->appendChild( $xml_tarifa );
	$xml_totalImpuesto->appendChild( $xml_valor );
	$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
	if(($cabecera['Con_IVA']) > 0)
	{
		$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
		$xml_codigo = $xml->createElement( "codigo",'2' );
		$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",$cabecera['codigoPorcentaje'] );
		$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",round($cabecera['descuentoAdicional'],2) );
		$xml_baseImponible = $xml->createElement( "baseImponible",round($cabecera['baseImponibleConIva'],2) );
		$xml_tarifa = $xml->createElement( "tarifa",round(($cabecera['Porc_IVA']*100),2) );
		$xml_valor = $xml->createElement( "valor",round($cabecera['IVA'],2) );
		
		$xml_totalImpuesto->appendChild( $xml_codigo );
		$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
		$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
		$xml_totalImpuesto->appendChild( $xml_baseImponible );
		$xml_totalImpuesto->appendChild( $xml_tarifa );
		$xml_totalImpuesto->appendChild( $xml_valor );
		
		$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
	}
	$xml_infoFactura->appendChild( $xml_totalConImpuestos );

	$xml_propina = $xml->createElement( "propina",round($cabecera['Propina'],2) );
	$xml_importeTotal = $xml->createElement( "importeTotal",round($cabecera['Total_MN'],2) );
	$xml_moneda = $xml->createElement( "moneda",$cabecera['moneda'] );

	$xml_infoFactura->appendChild( $xml_propina );
	$xml_infoFactura->appendChild( $xml_importeTotal );
	$xml_infoFactura->appendChild( $xml_moneda );

	$xml_detalles = $xml->createElement( "detalles");
	foreach ($detalle as $key => $value) {
		if($value['Cod_Bar'] !='' or $value['Codigo']!='')
		{
			$xml_detalle = $xml->createElement( "detalle" );
			if($cabecera['SP']==true)
			{
				if(strlen($value['Cod_Bar'])>1)
				{
					$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Cod_Bar'] );
				}
				$xml_detalle->appendChild( $xml_codigoPrincipal );
				if(strlen($detalle[$i]['Cod_Aux'])>1)
				{
					$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$value['Cod_Aux'] );
				}
				else
				{
					$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$value['Codigo'] );
				}
				$xml_detalle->appendChild( $xml_codigoAuxiliar );

			}else
			{

				$cod_au = str_replace('.','', $value['Codigo']);
				$cod =explode('.', $value['Codigo']);
					$num_partes = count($cod);
					$val_cod = '';
					for ($i=0; $i <$num_partes-1 ; $i++) { 
						$val_cod.= $cod[$i].'.';
						$val_cod = substr($val_cod,0,-1);
					}

				if(strlen($value['Cod_Aux'])>1)
				{
					$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Cod_Aux'] );
				}
				else
				{					
					$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$value['Codigo']);
				}
				$xml_detalle->appendChild( $xml_codigoPrincipal );
				// if(strlen($value['Cod_Bar'])>1)
				// {
					// $xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$val_cod);
					// $xml_detalle->appendChild( $xml_codigoAuxiliar );
				// }
			}

			$xml_descripcion = $xml->createElement( "descripcion",$value['Producto'] );
			$xml_unidadMedida = $xml->createElement( "unidadMedida",$cabecera['moneda'] );
			$xml_cantidad = $xml->createElement( "cantidad",$value['Cantidad'] );
			$xml_precioUnitario = $xml->createElement( "precioUnitario",round($value['Precio'],2) );
			$xml_descuento = $xml->createElement( "descuento",round($value['descuento'],2) );
			$xml_precioTotalSinImpuesto = $xml->createElement( "precioTotalSinImpuesto",round($value['SubTotal'],2) );
			
			$xml_detalle->appendChild( $xml_codigoPrincipal );
			
			$xml_detalle->appendChild( $xml_descripcion );
			$xml_detalle->appendChild( $xml_unidadMedida );
			$xml_detalle->appendChild( $xml_cantidad );
			$xml_detalle->appendChild( $xml_precioUnitario );
			$xml_detalle->appendChild( $xml_descuento );
			$xml_detalle->appendChild( $xml_precioTotalSinImpuesto );
			if(strlen($value['Serie_No'])>1)
			{
				$detallesAdicionales = $xml->createElement( "detallesAdicionales" );
				$detAdicional = $xml->createElement( "detAdicional" );
				$detAdicional->setAttribute( "nombre", "Serie_No" );
				$detAdicional->setAttribute( "valor", $value['Serie_No'] );
				$detallesAdicionales->appendChild( $detAdicional );
				$xml_detalle->appendChild( $detallesAdicionales );
			}
			$xml_impuestos = $xml->createElement( "impuestos" );
			$xml_impuesto = $xml->createElement( "impuesto" );
			$xml_codigo = $xml->createElement( "codigo",'2' );

			if($value['Total_IVA'] == 0)
			{
				$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0' );
				$xml_tarifa = $xml->createElement( "tarifa",'0' );
			}
			else
			{
				if(($value['Porc_IVA']*100) > 12)
				{
					$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'3' );
				}
				else
				{
					$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
				}
				$xml_tarifa = $xml->createElement( "tarifa",round(($value['Porc_IVA']*100),2) );
				
			}
			$xml_baseImponible = $xml->createElement( "baseImponible",round($value['SubTotal'],2) );
			$xml_valor = $xml->createElement( "valor",round($value['Total_IVA'],2)  );
			$xml_impuesto->appendChild( $xml_codigo );
			$xml_impuesto->appendChild( $xml_codigoPorcentaje );
			$xml_impuesto->appendChild( $xml_tarifa );
			$xml_impuesto->appendChild( $xml_baseImponible );
			$xml_impuesto->appendChild( $xml_valor );
		
			$xml_impuestos->appendChild( $xml_impuesto );
			$xml_detalle->appendChild( $xml_impuestos );
			$xml_detalles->appendChild( $xml_detalle );
		}
	}
	$xml_infoAdicional = $xml->createElement( "infoAdicional");
	//agregar informacion por default
		// $xml_campoAdicional = $xml->createElement( "campoAdicional",'.' );
		// $xml_campoAdicional->setAttribute( "nombre", "adi" );
		// $xml_infoAdicional->appendChild( $xml_campoAdicional );
	if($cabecera['Cliente']<>'.' AND ($cabecera['Cliente']!=$cabecera['Razon_Social']))
	{
		if(strlen($cabecera['Cliente'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Cliente'] );
			$xml_campoAdicional->setAttribute( "nombre", "Beneficiario" );
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Grupo'] );
			$xml_campoAdicional->setAttribute( "nombre", "Ubicacion" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
	}
	if(strlen($cabecera['DireccionC'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['DireccionC'] );
		$xml_campoAdicional->setAttribute( "nombre", "Direccion" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera['TelefonoC'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['TelefonoC'] );
		$xml_campoAdicional->setAttribute( "nombre", "Telefono" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera['EmailC'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['EmailC'] );
		$xml_campoAdicional->setAttribute( "nombre", "Email" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera['EmailR'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['EmailR'] );
		$xml_campoAdicional->setAttribute( "nombre", "Email2" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera['Contacto'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Contacto'] );
		$xml_campoAdicional->setAttribute( "nombre", "Referencia" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera['Orden_Compra'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera['Orden_Compra'] );
		$xml_campoAdicional->setAttribute( "nombre", "ordenCompra" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	
	$xml_factura->appendChild( $xml_infoTributaria );
	$xml_factura->appendChild( $xml_infoFactura );
	$xml_factura->appendChild( $xml_detalles );
	$xml_factura->appendChild( $xml_infoAdicional );


	$xml->appendChild($xml_factura);

	$ruta_G = dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE_".$entidad.'/Generados';
	if($archivo = fopen($ruta_G.'/'.$compro.'.xml',"w+b"))
	  {
	  	fwrite($archivo,$xml->saveXML());
	  	 $respuesta =  $this->firmar_documento($compro,$entidad,$cabecera['clave_ce'],$cabecera['ruta_ce']);
	     return $respuesta;
	  }else
	  {
	  	// print_r($ruta_G);die();
	  	// return 's';
	  }

	
}



  function firmar_documento($nom_doc,$entidad,$pass,$p12)
    {	

 	    $firmador = dirname(__DIR__).'/SRI/firmar/firmador.jar';
 	    $url_generados=dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE_".$entidad.'/Generados/';
 	    $url_firmados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE_".$entidad.'/Firmados/';
 	    $certificado_1 = dirname(__DIR__).'/certificados/';

       if(file_exists($certificado_1.$p12))
       {
 		exec("java -jar ".$firmador." ".$nom_doc.".xml ".$url_generados." ".$url_firmados." ".$certificado_1." ".$p12." ".$pass, $f);
 	   }else
 	   {
 	   	$respuesta = array('1'=>'No se han encontrado Certificados');
 				return $respuesta;
 	   }

 		$quijoteCliente =  dirname(__DIR__).'/SRI/firmar/QuijoteLuiClient-1.2.jar';
 	    $url_No_autorizados =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE_".$entidad.'/No_autorizados/';
 	    $url_autorizado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE_".$entidad.'/Autorizados';

 		if(count($f)<6)
 		{
 			exec("java -jar ".$quijoteCliente." ".$nom_doc.".xml".
			" ".$url_firmados." ".$url_No_autorizados.
			" ".$url_autorizado." ".$nom_doc."", $o);
			sleep(4);
			// $respuesta = $compro.'-'.$url_autorizado;
			// print_r($o);
			// die();
	       return $o;
 		}else
 		{
 			$respuesta = explode(':',$f[5]);
 			// $error = utf8_decode(trim($respuesta[3]));
 			$error = trim($respuesta[3]);
 			// print_r($error);die();
 			if($error == 'No se encuentra el nodo ra?z' or $error == 'No se encuentra el nodo raiz' or $error =='No se encuentra el nodo ra�z' or $error == 'No se encuentra el nodo raíz')
 			{
 				$respuesta = array('1'=>'El XML tiene caracteres especiales');
 				return $respuesta;
 			}else
 			{
 				$respuesta = array('1'=>$error);
 				// print_r($respuesta);die();
 				return $respuesta;
 			}             
        }
    }

    function actualizar_datos_CE($autorizacion,$tc,$serie,$factura,$entidad,$autorizacion_ant)
    {
			$con = $this->conn->conexion();
			$sql ="UPDATE Facturas SET Autorizacion='".$autorizacion."',Clave_Acceso='".$autorizacion."' WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND TC = '".$tc."' 
			AND Serie = '".$serie."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) = 13 
			AND T <> 'A'; ";
			// print_r($sql);die();
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$sql="UPDATE Detalle_Factura SET Autorizacion='".$autorizacion."' WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			 AND TC = '".$tc."' 
			 AND Serie = '".$serie."' 
			AND Autorizacion = '".$autorizacion_ant."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) >= 13 
			AND T <> 'A'; ";
			//echo $sql;
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//modificamos trans abonos
			$sql="UPDATE Trans_Abonos SET Autorizacion='".$autorizacion."',Clave_Acceso='".$autorizacion."' WHERE Item = '".$_SESSION['INGRESO']['item']."' 
			 AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			 AND TP = '".$tc."' 
			 AND Serie = '".$serie."' 
			AND Autorizacion = '".$autorizacion_ant."' 
			AND Factura = ".$factura." 
			AND LEN(Autorizacion) >= 13 
			AND T <> 'A'; ";
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//creamos trans_documentos
			//echo $ban1[2];
			$url_autorizado =dirname(__DIR__).'/entidades/entidad_'.$entidad."/CE_".$entidad.'/Autorizados/'.$autorizacion.'.xml';

			$archivo = fopen($url_autorizado,"rb");
			if( $archivo == false ) 
			{
				echo "Error al abrir el archivo";
			}
			else
			{
				rewind($archivo);   // Volvemos a situar el puntero al principio del archivo
				$cadena2 = fread($archivo, filesize($url_autorizado));  // Leemos hasta el final del archivo
				if( $cadena2 == false )
					echo "Error al leer el archivo";
				else
				{
					//echo "<p>\$contenido1 es: [".$cadena1."]</p>";
					//echo "<p>\$contenido2 es: [".$cadena2."]</p>";
				}
			}
			// Cerrar el archivo:
			fclose($archivo);
			$sql="INSERT INTO Trans_Documentos
		    (Item,Periodo,Clave_Acceso,Documento_Autorizado,TD,Serie,Documento,X)
			 VALUES
		    ('".$_SESSION['INGRESO']['item']."' 
		    ,'".$_SESSION['INGRESO']['periodo']."' 
		    ,'".$autorizacion."'
		    ,'".$cadena2."'
		    ,'".$tc."' 
		    ,'".$serie."' 
		    ,".$factura." 
			,'.');";
			//echo $sql;
			$stmt = sqlsrv_query($con, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			return 1;
    }
    function quitar_carac($query)
    {
    	$buscar = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','Ñ','ñ','/','?','�','-');
    	$remplaza = array('a','e','i','o','u','A','E','I','O','U','N','n','','','','');
    	$corregido = str_replace($buscar, $remplaza, $query);
    	 // print_r($corregido);
    	return $corregido;

    }

}

?>