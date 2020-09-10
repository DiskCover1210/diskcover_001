<?php
//Configuración del algoritmo de encriptación

//Debes cambiar esta cadena, debe ser larga y unica
//nadie mas debe conocerla
$clave  = 'Una cadena, muy, muy larga para mejorar la encriptacion';

//Metodo de encriptación
$method = 'aes-256-cbc';

// Puedes generar una diferente usando la funcion $getIV()
$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

 /*
 Encripta el contenido de la variable, enviada como parametro.
  */
 $encriptar = function ($valor) use ($method, $clave, $iv) {
     return openssl_encrypt ($valor, $method, $clave, false, $iv);
 };

 /*
 Desencripta el texto recibido
 */
 $desencriptar = function ($valor) use ($method, $clave, $iv) {
     $encrypted_data = base64_decode($valor);
     return openssl_decrypt($valor, $method, $clave, false, $iv);
 };
 
//Autorizar
if(isset($_POST['ajax_page']) ) 
{
	if($_REQUEST['ajax_page']=='autorizar')
	{
		autorizar();
	}
}
//para autorizar facturas
function autorizar()
{
	echo " Entro ";
	generar_xml();
	die();
}
//agregar ceros a cadena a la izquierda
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
	return$verificador;
}
/*function digito_verificador($cadena)
{
	$cadena=trim($cadena);
	$baseMultiplicador=7;
	$aux = new SplFixedArray(srtlen($cadena));
	$aux = $aux->toArray();
	$multiplicador=0;
	$verificador=0;
	$total=0;
	for($i=count($aux)-1;$i>=0;--$i)
	{
		$aux[$i]=substr($cadena,$i,1);
		$aux[$i]*=$multiplicador;
		++$multiplicador;
		if($multiplicador>$baseMultiplicador)
		{
			$multiplicador=2;
		}
		$total+=$aux[$i];
	}
}*/
//digito_verificadorf('1710034065');
function digito_verificadorf($ruc,$pag=null,$idMen=null,$item=null)
{
	//$ruc=$_POST['RUC'];
	//echo $ruc.' '.strlen($ruc);
	//codigo walter
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
							/*
							error caso 0802351031001
							echo $ruc.' ';
							echo $Dig3.' ';
							switch ($Dig3) 
							{
								case (($Dig3 <= 5) OR ($Dig3 >= 0)):
								{
									$TipoModulo = 10;
									$TipoModulo1=9;
									$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
									$VecDig = "212121212";
									echo " aquiii 1 ";
									break;
								}
								case ($Dig3 == 6 ):
								{
									$coe = array("3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=8;
									$VecDig = "32765432";
									echo " aquiii 2 ";
									break;
								}
								case ($Dig3 == 9):
								{
									$coe = array("4", "3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=9;
									$VecDig = "432765432";
									echo " aquiii 3 ";
									//echo " entro a nueve";
									break;
								}
								default:    
								{    
									$VecDig = "222222222";
									$TipoModulo1=9;
									echo " aquiii 4 ";
									$coe = array("2", "2", "2", "2", "2","2", "2", "2", "2");
								} 
							}*/
							//echo $VecDig.' ccc '.$TipoModulo.' nn ';
							//die();
							//realizamos productos
							$arr1 = str_split($ruc);
							$resu = array();
							$resu1=0;
							$coe1=0;
							$pro='';
							$ter='';
							//$TipoModulo=10;
							//validador
							$ban=0;
							for($jj=0;$jj<($TipoModulo1);$jj++)
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
									/*if($resu[$jj]>=10)
									{
										$resu[$jj]=$resu[$jj]-9;
									}*/
									If (0 <= $Dig3 And $Dig3 <= 5 And $resu[$jj] > 9)
									{
										$resu[$jj]=$resu[$jj]-9;
									}									
									//suma
									$resu1=$resu[$jj]+$resu1;
									//echo $coe[$jj].' * '.$arr1[$jj].' = '.$resu[$jj].' sum '.$resu1.' -- ';
									
								}
								//ultimo digito
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
								//echo $Digito_Verificador.' veri '.substr($ruc, 9, 1);
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
					//echo $Tipo_Beneficiario;
				}
			}
			//$_SESSION['INGRESO']['item']
			//Si no es RUC/CI, procesamos el numero de codigo que le corresponde
			//echo ' www '.substr($ruc, 12, 1);
			if(substr($ruc, 12, 1)!='1')
			{
				$Tipo_Beneficiario = 'O';
			}
			
		}
		
	return $Digito_Verificador;
	//login('', '', '');
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
9 Dígito Verificador (módulo 11 )       Numérico       1
*/
function generar_xml()
{
	$entidad='001';
	$empresa='001';
	$fecha = date('dm').date('Y');
	//$fecha = '11082020';
	$ruc='1792164710001';
	$tc='01';
	$serie='001003';
	$numero='502';
	$numero=generaCeros($numero, '9');
	$emi='1';
	$nume='23456781';
	$nume='12345678';
	//produccion
	//0308202001179216471000120010050000009361234567819
	//prueba
	//0308202001179216471000110010030000005011234567819
	//1108202001179216471000110010030000005011234567813
	$dig=digito_verificadorf($ruc,$pag=null,$idMen=null,'001');

	//echo $dig;
	//die();
	$compro=$fecha.$tc.$ruc.'1'.$serie.$numero.$nume.$emi;
	$dig=digito_verificador($compro);
	//echo ' nn '.$dig;
	//die();
	$compro=$fecha.$tc.$ruc.'1'.$serie.$numero.$nume.$emi.$dig;
	/*exec("java -jar ".dirname(__DIR__)."/entidades/QuijoteLuiClient-1.2.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/no_autorizados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/autorizados ".$compro."", $o);
	print_r($o);*/
	//echo dirname(__DIR__).' -- ';
	//echo dirname(__DIR__).'/entidades/';
	//die();
	//echo ' '.$compro;
	//die();
	/*exec("java -jar C:\\wamp64\\www\\php\\entidades\\QuijoteLuiFirmador-master\\QuijoteLuiFirmador-master\\dist\\QuijoteLuiFirmador-1.4.jar ".$compro.".xml C:/wamp64/www/php/entidades/entidad01/empresa001/generados/ C:/wamp64/www/php/entidades/entidad01/empresa001/firmados C:/wamp64/www/php/entidades/entidad01/empresa001/ walter_jalil_vaca_prieto.p12 Dlcjvl1210", $o);
	print_r($o);
	die();
	$last_line = shell_exec("C:\\Program Files\\Java\\jdk1.8.0_111\\bin\\java.exe -jar C:\\wamp64\\www\\php\\entidades\\QuijoteLuiFirmador-master\\QuijoteLuiFirmador-master\\dist\\QuijoteLuiFirmador-1.4.jar");*/

	/*$handle = popen("C:\Program Files\Java\jdk1.8.0_111\bin\java.exe -jar C:\wamp64\www\php\entidades\QuijoteLuiFirmador-master\QuijoteLuiFirmador-master\dist\QuijoteLuiFirmador-1.4.jar", "r");
	//You can read with $read = fread($handle, 2096)
	pclose($handle);*/

	//echo $fecha.$tc.$ruc.$dig.$serie.$numero.$emi.$nume.'9';
	//die();

	header( "content-type: application/xml; charset=UTF-8" );

	// "Create" the document.
	$xml = new DOMDocument( "1.0", "UTF-8" );
	$xml->preserveWhiteSpace = false; 

	// Create some elements.
	$xml_factura = $xml->createElement( "factura" );
	//$xml_factura=$xml_factura.'\n';
	$xml_factura->setAttribute( "id", "comprobante" );
	$xml_factura->setAttribute( "version", "1.1.0" );
	$xml_infoTributaria = $xml->createElement( "infoTributaria" );
	$xml_ambiente = $xml->createElement( "ambiente",'1' );
	$xml_tipoEmision = $xml->createElement( "tipoEmision",'1' );
	$xml_razonSocial = $xml->createElement( "razonSocial",'PRISMANET PROFESIONAL S.A.' );
	$xml_nombreComercial = $xml->createElement( "nombreComercial",'PRISMANET' );
	$xml_ruc = $xml->createElement( "ruc",'1792164710001' );
	$xml_claveAcceso = $xml->createElement( "claveAcceso",$compro);
	$xml_codDoc = $xml->createElement( "codDoc",'01' );
	$xml_estab = $xml->createElement( "estab",'001' );
	$xml_ptoEmi = $xml->createElement( "ptoEmi",'003' );
	$xml_secuencial = $xml->createElement( "secuencial",$numero );
	$xml_dirMatriz = $xml->createElement( "dirMatriz",'Pablo Palacios N23-154 Y Av. La Gasca' );

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

	$xml_fechaEmision = $xml->createElement( "fechaEmision",''.date('d').'/'.date('m').'/'.date('Y').'' );
	$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",'Pablo Palacios N23-154 Y Av. La Gasca' );
	$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'SI' );
	$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionComprador",'04' );
	$xml_razonSocialComprador = $xml->createElement( "razonSocialComprador",'GUEVARA Y HERRERA ASOCIADOS' );
	$xml_identificacionComprador = $xml->createElement( "identificacionComprador",'1792624258001' );
	$xml_totalSinImpuestos = $xml->createElement( "totalSinImpuestos",'75.00' );
	$xml_totalDescuento = $xml->createElement( "totalDescuento",'0.00' );

	$xml_infoFactura->appendChild( $xml_fechaEmision );
	$xml_infoFactura->appendChild( $xml_dirEstablecimiento );
	$xml_infoFactura->appendChild( $xml_obligadoContabilidad );
	$xml_infoFactura->appendChild( $xml_tipoIdentificacionComprador );
	$xml_infoFactura->appendChild( $xml_razonSocialComprador );
	$xml_infoFactura->appendChild( $xml_identificacionComprador );
	$xml_infoFactura->appendChild( $xml_totalSinImpuestos );
	$xml_infoFactura->appendChild( $xml_totalDescuento );

	$xml_totalConImpuestos = $xml->createElement( "totalConImpuestos" );
	//bucle
	for($i=0;$i<2;$i++)
	{
		$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
		$xml_codigo = $xml->createElement( "codigo",'2' );
		$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0' );
		$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",'0' );
		$xml_baseImponible = $xml->createElement( "baseImponible",'0.00' );
		$xml_valor = $xml->createElement( "valor",'0.00' );
		
		$xml_totalImpuesto->appendChild( $xml_codigo );
		$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
		$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
		$xml_totalImpuesto->appendChild( $xml_baseImponible );
		$xml_totalImpuesto->appendChild( $xml_valor );
		
		$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
		$xml_infoFactura->appendChild( $xml_totalConImpuestos );
	}

	$xml_propina = $xml->createElement( "propina",'0' );
	$xml_importeTotal = $xml->createElement( "importeTotal",84.00 );
	$xml_moneda = $xml->createElement( "moneda",'DOLAR' );

	$xml_infoFactura->appendChild( $xml_propina );
	$xml_infoFactura->appendChild( $xml_importeTotal );
	$xml_infoFactura->appendChild( $xml_moneda );

	$xml_detalles = $xml->createElement( "detalles");

	for($i=0;$i<1;$i++)
	{
		$xml_detalle = $xml->createElement( "detalle" );
		$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",'01.02' );
		$xml_descripcion = $xml->createElement( "descripcion",'Alquiler Sistema Diskcover System Mes de Agosto 2018' );
		$xml_unidadMedida = $xml->createElement( "unidadMedida",'DOLAR' );
		$xml_cantidad = $xml->createElement( "cantidad",'1.000000' );
		$xml_precioUnitario = $xml->createElement( "precioUnitario",'75.000000' );
		$xml_descuento = $xml->createElement( "descuento",'1.000000' );
		$xml_precioTotalSinImpuesto = $xml->createElement( "precioTotalSinImpuesto",'75.00' );
		
		
		
		$xml_detalle->appendChild( $xml_codigoPrincipal );
		$xml_detalle->appendChild( $xml_descripcion );
		$xml_detalle->appendChild( $xml_unidadMedida );
		$xml_detalle->appendChild( $xml_cantidad );
		$xml_detalle->appendChild( $xml_precioUnitario );
		$xml_detalle->appendChild( $xml_descuento );
		$xml_detalle->appendChild( $xml_precioTotalSinImpuesto );
		$xml_impuestos = $xml->createElement( "impuestos" );
		
		for($j=0;$j<1;$j++)
		{
			$xml_impuesto = $xml->createElement( "impuesto" );
			$xml_codigo = $xml->createElement( "codigo",'2' );
			$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
			$xml_tarifa = $xml->createElement( "tarifa",'12' );
			$xml_baseImponible = $xml->createElement( "baseImponible",'75.00' );
			$xml_valor = $xml->createElement( "valor",'9.00' );
			
			$xml_impuesto->appendChild( $xml_codigo );
			$xml_impuesto->appendChild( $xml_codigoPorcentaje );
			$xml_impuesto->appendChild( $xml_tarifa );
			$xml_impuesto->appendChild( $xml_baseImponible );
			$xml_impuesto->appendChild( $xml_valor );
		
			$xml_impuestos->appendChild( $xml_impuesto );
			$xml_detalle->appendChild( $xml_impuestos );
			
		}
		
		$xml_detalles->appendChild( $xml_detalle );
	}

	$xml_infoAdicional = $xml->createElement( "infoAdicional");

	$xml_campoAdicional = $xml->createElement( "campoAdicional",'LA PRADERA' );
	$xml_campoAdicional->setAttribute( "nombre", "Direccion" );
	$xml_infoAdicional->appendChild( $xml_campoAdicional );

	$xml_campoAdicional = $xml->createElement( "campoAdicional",'002020408' );
	$xml_campoAdicional->setAttribute( "nombre", "Telefono" );
	$xml_infoAdicional->appendChild( $xml_campoAdicional );

	$xml_campoAdicional = $xml->createElement( "campoAdicional",'guevarabolivar@hotmail.com' );
	$xml_campoAdicional->setAttribute( "nombre", "Email" );
	$xml_infoAdicional->appendChild( $xml_campoAdicional );

	/*$xml_track = $xml->createElement( "Track", "The ninth symphony" );

	// Set the attributes.
	$xml_track->setAttribute( "length", "0:01:15" );
	$xml_track->setAttribute( "bitrate", "64kb/s" );
	$xml_track->setAttribute( "channels", "2" );

	// Create another element, just to show you can add any (realistic to computer) number of sublevels.
	$xml_note = $xml->createElement( "Note", "The last symphony composed by Ludwig van Beethoven." );

	// Append the whole bunch.
	$xml_track->appendChild( $xml_note );
	$xml_track = $xml->createElement( "Track", "Highway Blues" );

	$xml_track->setAttribute( "length", "0:01:33" );
	$xml_track->setAttribute( "bitrate", "64kb/s" );
	$xml_track->setAttribute( "channels", "2" );*/

	$xml_factura->appendChild( $xml_infoTributaria );
	$xml_factura->appendChild( $xml_infoFactura );
	$xml_factura->appendChild( $xml_detalles );
	$xml_factura->appendChild( $xml_infoAdicional );


	$xml->appendChild( $xml_factura );

	// Parse the XML.
	//print $xml->saveXML();
	$nombre_archivo = "entidad".$entidad."/CE".$empresa."/generados/".$compro.".xml"; 
	if( file_exists(dirname(__DIR__)."/entidades/entidad".$entidad) == true )
	{
		//echo "<p>El archivo existe</p>";
		if( file_exists("CE".$empresa) == true )
		{
			//echo "<p>El archivo existe</p>";
		}
		else
		{
			mkdir("entidad".$entidad."/certificados", 0777);
			mkdir("entidad".$entidad."/CE".$empresa, 0777);
			mkdir("entidad".$entidad."/CE".$empresa."/generados", 0777);
			mkdir("entidad".$entidad."/CE".$empresa."/no_autorizados", 0777);
			mkdir("entidad".$entidad."/CE".$empresa."/firmados", 0777);
			mkdir("entidad".$entidad."/CE".$empresa."/autorizados", 0777);
		}				
	}
	else
	{
		mkdir("entidad".$entidad, 0777);
		mkdir("entidad".$entidad."/certificados", 0777);
		mkdir("entidad".$entidad."/CE".$empresa, 0777);
		mkdir("entidad".$entidad."/CE".$empresa."/generados", 0777);
		mkdir("entidad".$entidad."/CE".$empresa."/no_autorizados", 0777);
		mkdir("entidad".$entidad."/CE".$empresa."/firmados", 0777);
		mkdir("entidad".$entidad."/CE".$empresa."/autorizados", 0777);
	}
	if($archivo = fopen($nombre_archivo, "w+b"))
	{
		
		
		if(fwrite($archivo, $xml->saveXML()))
		{
			//echo "Se ha ejecutado correctamente";
			//para ejecutar java
			/*exec("/app/jdk1.8.0_181/bin/java -Dfile.encoding=UTF-8 -jar /data/git/QuijoteLuiFirmador/dist/QuijoteLuiFirmador-1.3.jar 0401201901100645687700120011020000000151234567810.xml", $o); print_r($o);*/
			/*parametros 
			1 nombre documento 
			2 ruta del archivo 
			3 ruta de firmados 
			4 ruta de p12 
			5 archivo p12 
			6 clave*/
			exec("java -jar ".dirname(__DIR__)."/entidades/QuijoteLuiFirmador-1.4.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/generados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/certificados/ walter_jalil_vaca_prieto.p12 Dlcjvl1210", $o);
			
			exec("java -jar ".dirname(__DIR__)."/entidades/QuijoteLuiClient-1.2.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/no_autorizados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/autorizados ".$compro."", $o);
		}
		else
		{
			echo "Ha habido un problema al crear el archivo";
		}
		fclose($archivo);
	}
}
//http://www.formacionwebonline.com/generar-xml-en-php-con-xmlwriter/
?>

