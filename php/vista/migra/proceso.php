<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
ini_set('max_execution_time', 3600);
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<?php
function proceso()
{
	$resultado = $_POST['tabla'] .' '. $_POST['cantidadreg'].' '. $_POST['cantidadcam'].' '. $_POST['camID'].' '. $_POST['consu1'].' '. $_POST['cam1']
	.' '. $_POST['i']; 

	$tabla = $_POST['tabla'];
	$cantidadreg = '';
	$cantidadreg = $_POST['cantidadreg'];
	$cantidadcam = $_POST['cantidadcam'];
	$camID = $_POST['camID'];
	$consu1 = $_POST['consu1'];
	$i = $_POST['i'];
	//$cam1 =  utf8_decode($_POST['cam1']);
	if($_POST['tabla']=='Saldo_Diarios')
	{
		$cam1 =  ($_POST['cam1']);
		$cadena_buscada   = "Ã©";
									
		$posicion_coincidencia = strpos($cam1, $cadena_buscada);
		//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
		if ($posicion_coincidencia === false) {
			//echo "NO se ha encontrado la palabra deseada!!!!";
			//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
		} else {
			//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
			//sustituir cadena
			//$cam1 = str_replace("Ã©", "é", $cam1);
			//$cam1 = str_replace("Ã¡", "á", $cam1);
			//$cam1 = str_replace("Ã©", "e", $cam1);
			//$cam1 = str_replace("Ã¡", "a", $cam1);
			
		}
		$cam1 =  utf8_decode($_POST['cam1']);
			//echo $cam1;
		//echo "entrooo";
		$consu1 = str_replace("é", "e", $consu1);
		$consu1 = str_replace("á", "a", $consu1);
	}
	else
	{
		$cam1 =  ($_POST['cam1']);
	}
	
	$cadena_buscada   = "Ã±";
								
	$posicion_coincidencia = strpos($cam1, $cadena_buscada);
	//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
	if ($posicion_coincidencia === false) {
		//echo "NO se ha encontrado la palabra deseada!!!!";
		//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
	} else {
		//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
		//sustituir cadena
		$cam1 = str_replace("Ã±", "ñ", $cam1);
		//$cam1 = str_replace("Ã¡", "á", $cam1);
		//$cam1 = str_replace("Ã©", "e", $cam1);
		//$cam1 = str_replace("Ã¡", "a", $cam1);
		
	}
	
	//$cam1 =  ($_POST['cam1']);
	//para que funcione en local
	//$cam1 =  utf8_decode($_POST['cam1']);
	$base_mi=trim($_POST['base_mi']);
	$server=trim($_POST['server']);
	$user=trim($_POST['user']);
	$password=trim($_POST['password']);
	$database=trim($_POST['base_mi']);
	$host_my=trim($_POST['host_my']);
	$usuario_my=trim($_POST['usuario_my']);
	$clave_my=trim($_POST['clave_my']);
	$base_my=trim($_POST['base_my']);
	$port_my=trim($_POST['port_my']);
	//cantidad de registros guardados
	 $regis=trim($_POST['regis']);
	//echo $tabla;
	//echo $tabla.' ---- '.$cantidadreg.' ----- '.$consu1.' ----- '.$regis;
	//die();
	//para sql server
		/*$dsn = "sistema"; 
		//debe ser de sistema no de usuario
		$usuario = "sa";
		$clave="Dlcjvl1210";
		$base_mi="diskcover_system";
		//$base_mi="DiskCover_prueba";
		//local
		$server="SISTEMAS";
		$user="sa";
		$password="Dlcjvl1210";
		$database="diskcover_system";
		//$database="DiskCover_prueba";
		
		//$server="mysql.diskcoversystem.com";
		//$user="sa";
		//$password="disk2017Cover";
		//$database="DiskCover_Prismanet";
		//$base_mi="DiskCover_Prismanet";
		//192.168.2.109 */
		$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			echo "fallo conecion";
		}
		//$cid = odbc_connect("Driver={SQL Server}; Server=".$server."; Database=".$database.";", $user, $password);

		//realizamos la conexion mediante odbc
		//$cid=odbc_connect($dsn, $usuario, $clave);
		//conexion de mysql local
		/*$host_my="localhost";
		$usuario_my="root";
		$clave_my="";
		$base_my="diskcover_system";
		$sql="SELECT COLUMN_NAME,DATA_TYPE,IS_NULLABLE,CHARACTER_MAXIMUM_LENGTH
				FROM Information_Schema.Columns
				WHERE TABLE_NAME = '".$tabla[$i]['tabla']."'";
				
				$stmt = sqlsrv_query( $cid, $sql);
				while( $obj = sqlsrv_fetch_object( $stmt)) 
				{
					$campos[$ii]['tabla']=$tabla[$i]['tabla'];
					$campos[$ii]['campo']=$obj->COLUMN_NAME;
					//echo $tabla[$i]['tabla'].' '.$campos[$ii]['campo']."<br>";
					$ii++;
				}
		*/
		
		//conexion de mysql server
		//$host_my="mysql.diskcoversystem.com";
		//$usuario_my="rootDiskcover";
		//$clave_my="Dlcjvl1210@";
		//$base_my="DiskCover_Prismanet";
		$conexion=new mysqli($host_my.$port_my, $usuario_my, $clave_my, $base_my);
		$conexion->query("SET NAMES 'utf8'");
		if (!$cid){
			exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
		}
		 ?>
		 <div id="car<?php echo $tabla; ?>">
				<!--<progress id="car" max="<?php echo $tabla[$i]['canreg']; ?>" value="0"></progress>
					<span></span> -->
				
			</div>
		<script type="text/javascript"> 
							
			animateprogress1("#car<?php echo $tabla; ?>",<?php echo $_POST['cantidadreg']; ?>,<?php echo $_POST['i']; ?>);
			
			//document.querySelector ('#boton').addEventListener ('click', function() { 
			//	animateprogress("#php",72);
			//}
			//);
		</script>
		<?php
				$jj1=0;
				$cantidad1=0;
				if($tabla=='Trans_Documentos')
				{
					$ini1=10;
					$fin=10;
					//echo " entro die";
					//die();
				}
				else
				{
					if($tabla=='Trans_Notas_Auxiliares' OR $tabla=='Trans_Notas')
					{
						$ini1=1000;
						$fin=1000;
					}
					else
					{
						$ini1=1000;
						$fin=1000;
					}
				}
				$cantidad=$cantidadreg/$ini1;
				if($regis=='')
				{
					$inicio=0;
				}
				else
				{
					$inicio=$regis-$ini1;
					if($inicio<=0)
					{
						$inicio=0;
					}
					$fin=$fin+$regis;
				}
					//echo $fin.' cdcdcd ';
					//ejecutamos de 500 en 500
					if($camID==0)
					{
						$cantidad=1;
					}
					$sql="SELECT COLUMN_NAME,DATA_TYPE,IS_NULLABLE,CHARACTER_MAXIMUM_LENGTH
					FROM Information_Schema.Columns
					WHERE TABLE_NAME = '".$tabla."'";
					$pages = array();
					$campos=array();
					$i1=0;
					//para saber si hay id
					$id1=0;
					$id2='';
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
						{  
							 echo "Error en consulta 213.\n";  
							 die( print_r( sqlsrv_errors(), true));  
						}  
					while( $obj = sqlsrv_fetch_object( $stmt)) 
					{
						$campos[$i1]['tabla']=$tabla;
						$campos[$i1]['campo']=$obj->COLUMN_NAME;
						$campos[$i1]['tipo']=$obj->DATA_TYPE;
						if($i1==0)
						{
							$id2=$campos[$i1]['campo'];
						}
						if($campos[$i1]['campo']=='ID')
						{
							$id1=1;
						}
						$i1++;
					}
					//validar que los id sean igual que cantida de registros en caso contrario ejecuto hasta el numero de id
					if($id1==1)		
					{
						$sql="SELECT max(id) AS idm FROM ".$tabla." ";
					}
					else
					{
						$sql="SELECT max(".$id2.") AS idm FROM ".$tabla." ";
					}
					//echo $sql;
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
						{  
							 echo "Error en consulta 245.\n";  
							 die( print_r( sqlsrv_errors(), true));  
						}  
					while( $obj = sqlsrv_fetch_object( $stmt)) 
					{
						$valor = $obj->idm;
					}
					$rec=0;
					if ($valor>$_POST['cantidadreg'] and $_POST['cantidadreg']<=1000)
					{
						$rec=1;
					}
					if ($valor>$_POST['cantidadreg'] and $_POST['cantidadreg']>=1000)
					{
						$sql="SELECT ".$cam1." FROM ".$tabla." WHERE ID>".$inicio." AND ID<=".$valor." ";
						//$cantidad=$cantidad+2;
						$cantidad=$valor/$ini1;
					}
					for($k=0;$k<$cantidad;$k++)
					{
						$jj=0;
						//buscamos los registros
						if($camID==1)
						{
							
							if ($rec==1)
							{
								$sql="SELECT ".$cam1." FROM ".$tabla." WHERE ID>".$inicio." AND ID<=".$valor." ";
							}
							else
							{
								$sql="SELECT ".$cam1." FROM ".$tabla." WHERE ID>".$inicio." AND ID<=".$fin." ";
							}
							
						}
						else
						{
							$sql="SELECT ".$cam1." FROM ".$tabla." ";
						}
						//$sql="SELECT ".$cam1." FROM ".$tabla." WHERE ID>".$inicio." AND ID<=".$fin." ";
						//echo $sql.'<br>';
						//die();
						//echo $sql;
						$stmt = sqlsrv_query( $cid, $sql);
						if( $stmt === false)  
						{  
							 echo "Error en consulta 268.\n";  
							 die( print_r( sqlsrv_errors(), true));  
						}  
						$ban1=0;
						$cam2=" VALUES ";
						$cam3 = explode(",", $cam1);
						while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
						{
							
							$cam2=$cam2."( ";
							$ban1=1;
							for($j=0;$j<count($cam3);$j++)
							{
								$cam4=trim($cam3[$j]);
								//echo $cam4.' vv ';
								//echo $row[''.$cam4.''].", <br />";
								$txt=$row[''.$cam4.''];
								$valor = utf8_decode($txt);
								//echo $valor.' -- ';
								//validamos que si son decimales y esta nulos coloque 0
								if($campos[$j]['tipo']=='real')
								{
									if($valor=='')
									{
										$valor='0.00';
									}
								}
								if($campos[$j]['tipo']=='money')
								{
									if($valor=='')
									{
										$valor='0.00';
									}
								}
								if($campos[$j]['tipo']=='float')
								{
									if($valor=='')
									{
										$valor='0.00';
									}
								}
								//caso entreos
								if($campos[$j]['tipo']=='smallint')
								{
									if($valor=='')
									{
										$valor='0';
									}
								}
								if($campos[$j]['tipo']=='int')
								{
									if($valor=='')
									{
										$valor='0';
									}
								}
								if($campos[$j]['tipo']=='bit')
								{
									if($valor=='')
									{
										$valor='0';
									}
								}
								if($campos[$j]['tipo']=='tinyint')
								{
									if($valor=='')
									{
										$valor='0';
									}
								}
								// fecha
								if($campos[$j]['tipo']=='smalldatetime' OR $campos[$j]['tipo']=='datetime')
								{
									//echo "entroooo111111 -- ".$valor;
									$valor = utf8_decode(date_format($txt,'Y-m-d H:i:s'));
									if($valor=='')
									{
										$valor='NULL';
										//echo "entroooo";
									}
								}
								//caracteres especiales
								//�
								$texto1=0;
								$cadena_buscada   = "�";
								$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
								//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
								if ($posicion_coincidencia === false) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								} else {
									//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
									//sustituir cadena
									$valor = str_replace("�", "Ñ", utf8_encode($valor));
									$texto1=1;
								}
								//Ã
								$texto1=0;
								$cadena_buscada   = "Ã";
								$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
								//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
								if ($posicion_coincidencia === false) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								} else {
									//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
									//sustituir cadena
									$valor = str_replace("Ã", "Ñ", utf8_encode($valor));
									$texto1=1;
								}
								//\'
								$texto1=0;
								$cadena_buscada   = "\\";
								$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
								//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
								if ($posicion_coincidencia === false) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								} else {
									//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
									//sustituir cadena
									$valor = str_replace("\\", "/", utf8_encode($valor));
									$texto1=1;
								}
								// '
								//buscar '
								$texto1=0;
								$cadena_buscada   = "'";
								$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
								//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
								if ($posicion_coincidencia === false) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								} else {
									//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
									//sustituir cadena
									$valor = str_replace("'", "\'", utf8_encode($valor));
									$texto1=1;
								}
								//carater 
								$texto1=0;
								$cadena_buscada   = "";
								$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
								//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
								if ($posicion_coincidencia === false) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								} else {
									//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
									//sustituir cadena
									$valor = str_replace("", "-", utf8_encode($valor));
									$texto1=1;
								}
								// caracter \xEF\xBF\xBD � \n\n
								$texto1=0;
								$cadena_buscada   = "1304201501099005408800120010050000016260904201612";
								$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
								//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
								if ($posicion_coincidencia === false) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								} else {
									//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
									//sustituir cadena
									
										//echo ' ccccccc '.$valor.' cccc ';
										//$valor = str_replace("1304201501099005408800120010050000016260904201612", "1304201501099005408800120010050000016260904201612", utf8_encode($valor));
										$texto1=1;
										//$valor = utf8_encode($valor);
										if($j==4){
											$longitud_cad = strlen($valor); 
											//$valor = substr_replace($valor,"",$longitud_cad-2,1); 
											//$valor = str_replace("U+FFF", "", utf8_encode($valor));
											//echo ' ccccccc '.$valor;
											 $nombre_archivo = "logs.txt"; 
		 
											if(file_exists($nombre_archivo))
											{
												$mensaje = "El Archivo $nombre_archivo se ha modificado";
											}
										 
											else
											{
												$mensaje = "El Archivo $nombre_archivo se ha creado";
											}
										 
											if($archivo = fopen($nombre_archivo, "a"))
											{
												if(fwrite($archivo, date("d m Y H:m:s"). " ". $mensaje. "\n"))
												{
													echo "Se ha ejecutado correctamente";
													fwrite($archivo,$valor);
												}
												else
												{
													echo "Ha habido un problema al crear el archivo";
												}
										 
												fclose($archivo);
											}
											//die();
										}
										//die();
									
								}
								if($valor=='NULL')
								{
									if($j==($cantidadcam-1) and $jj<($ini1-1))
									{
										$cam2=$cam2."".utf8_encode($valor)." ), ";
									}
									else
									{
										if($jj<($ini1-1))
										{
											$cam2=$cam2."".utf8_encode($valor).", ";
										}
										else
										{
											if($j==($cantidadcam-1))
											{
												//echo " entro 1 ".$j.' ddd ';
												//$cam2=$cam2."".utf8_encode($valor)." ); ";
												$cam2=$cam2."".utf8_encode($valor)." ), ";
											}
											else
											{
												$cam2=$cam2."".utf8_encode($valor).", ";
											}
										}
									}
								}
								else
								{
									if($camID==1)
									{
										if($j==($cantidadcam-1) and $jj<($ini1-1))
										{
											$cam2=$cam2."'".utf8_encode($valor)."' ), ";
										}
										else
										{
											if($jj<($ini1-1))
											{
												$cam2=$cam2."'".utf8_encode($valor)."', ";
											}
											else
											{
												if($j==($cantidadcam-1))
												{
													//echo " entro 2 ".$j.' ddd '.$cantidadcam.' sss '.$ini1.' hghg ';
													$cam2=$cam2."'".utf8_encode($valor)."' ), ";
												}
												else
												{
													$cam2=$cam2."'".utf8_encode($valor)."', ";
												}
											}
										}
									}
									else
									{
										if($j==($cantidadcam-1) and $jj<($cantidadreg-1))
										{
											$cam2=$cam2."'".utf8_encode($valor)."' ), ";
										}
										else
										{
											if($jj<($cantidadreg-1))
											{
												$cam2=$cam2."'".utf8_encode($valor)."', ";
											}
											else
											{
												if($j==($cantidadcam-1))
												{
													//echo " entro 3 ".$j.' ddd ';
													//$cam2=$cam2."'".utf8_encode($valor)."' ); ";
													$cam2=$cam2."'".utf8_encode($valor)."' ), ";
												}
												else
												{
													$cam2=$cam2."'".utf8_encode($valor)."', ";
												}
											}
										}
									}
								}
								//echo $cam2.'<br>';
								//echo "Result is ".$tabla." ".$valor;
							}
							$jj++;
							$jj1++;
							 ?>
			
							<script type="text/javascript"> 
								
									animateprogress("#car<?php echo $i; ?>",'<?php echo $_POST['cantidadreg']; ?>','<?php echo $jj1; ?>','<?php echo $tabla; ?>');
								
								//document.querySelector ('#boton').addEventListener ('click', function() { 
								//	animateprogress("#php",72);
								//}
								//);
							</script>
								<?php
								//echo $cam2;
								//die();
						}
							//echo ", <br />";
					
						//die();
						
						
						
							$longitud_cad = strlen($cam2); 
							$cam2 = substr_replace($cam2,";",$longitud_cad-2,1); 
						
						$inicio=$inicio+$ini1;
						$fin=$fin+$ini1;
						$consu2=$consu1."".$cam2;
						//echo '<br>'.$consu1.' ';
						//echo '<br>'.$consu2.' ';
						//die();
						if($ban1==1)
						{
							if ($conexion->query($consu2) === TRUE) {
								//echo " ha sido creado".'<br>';
								//echo '<br>'.$consu2.' ';
								//die();
							} else {
								//echo "Hubo un error al insertar datos de la tabla : ".$tabla." ".$consu2." " . $conexion->error.'<br>';
								//echo "Hubo un error al insertar datos de la tabla : ".$tabla."  " . $conexion->error.'<br>';
								echo "Hubo un error al insertar datos de la tabla : ".$tabla." " .'<br>';
								//die();
							}
						}
						//die();
					}
					sqlsrv_close( $cid );
					$conexion->close();
					ob_flush();
					sleep(1);
					exit(0);
	}
	$tabla = $_POST['tabla'];
	$cantidadreg = '';
	$cantidadreg = $_POST['cantidadreg'];
	$cantidadcam = $_POST['cantidadcam'];
	$camID = $_POST['camID'];
	$consu1 = $_POST['consu1'];
	$i = $_POST['i'];
	$cam1 =  utf8_decode($_POST['cam1']);
	$base_mi=trim($_POST['base_mi']);
	$server=trim($_POST['server']);
	$user=trim($_POST['user']);
	$password=trim($_POST['password']);
	$database=trim($_POST['base_mi']);
	$host_my=trim($_POST['host_my']);
	$usuario_my=trim($_POST['usuario_my']);
	$clave_my=trim($_POST['clave_my']);
	$base_my=trim($_POST['base_my']);
	$port_my=trim($_POST['port_my']);
	proceso();
	$regis1=0;
	$sql="SELECT count(*) AS cont FROM ".$tabla[$i]['tabla']." ";
	$consulta=$conexion->query($sql);
	$filas=$consulta->fetch_assoc();
	$regis = $filas['cont'];
	//echo $filas['cont'].' nnnn ';
	if($tabla[$i]['canreg']> $regis)
	{
		$regis1=1;
	}
	/*while($filas=$consulta->fetch_assoc())
	{
		
	}*/
	//echo $tabla[$i]['canreg'].' nnnn '. $regis;
	//volver a ingresar
	if($regis1==1)
	{
		//truncar
		$sql="TRUNCATE TABLE ".$tabla[$i]['tabla']." ";
		$consulta=$conexion->query($sql);
		echo "incompleto";
		proceso();
		die();
	}
	else
	{
		echo "completo";
	}
	$conexion->close();
	die();
	