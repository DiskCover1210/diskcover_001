<?php
//require_once("MysqlStreamDriver.php");
if(!isset($_SESSION)) 
	{ 		
			session_start();
	}
class Conectar{
    public static function conexion($tipo_base=null){
        //$conexion=new mysqli("localhost", "root", "", "diskcover_empresas");
		//$conexion=new mysqli("192.168.27.2", "diskcover", "disk2017Cover", "DiskCover_Empresas");
		//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcoverMigra", "diskcover2019Migra@", "DiskCover_Empresas");
		if($tipo_base==null)
		{
			//echo "entro 2";
			//verificamos si es sql server o mysql para consultar periodos
			if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MYSQL') 
			{
				$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
				$Base_Datos=$_SESSION['INGRESO']['Base_Datos'];
				$Usuario_DB=$_SESSION['INGRESO']['Usuario_DB'];
				$Contrase=$_SESSION['INGRESO']['Contraseña_DB'];
				$Puerto=$_SESSION['INGRESO']['Puerto'];
				$conexion=new mysqli($server.":".$Puerto, $Usuario_DB, $Contrase, $Base_Datos);
				$conexion->query("SET NAMES 'utf8'");
				return $conexion;
			}
			else
			{
				if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
				{
					/*$fp = fsockopen($_SESSION['INGRESO']['IP_VPN_RUTA'], $_SESSION['INGRESO']['Puerto'], $errno, $errstr, 30);
					if (!$fp) {
						echo "<script>
									alert('Fallo conexion sql server');
							</script>";
							die();
					}
					fclose($fp);*/
					/*$ip = $_SESSION['INGRESO']['IP_VPN_RUTA'];
					$output = shell_exec("ping $ip");
					 
					if (strpos($output, "recibidos = 0")) {
						echo "<script>
									alert('Fallo conexion sql server verificar IP');
							</script>";
						die();
					}*/ 
					$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
					$connectionInfo = array("Database"=>$_SESSION['INGRESO']['Base_Datos'], "UID" => $_SESSION['INGRESO']['Usuario_DB'],
					"PWD" => $_SESSION['INGRESO']['Contraseña_DB']);

					$cid = sqlsrv_connect($server, $connectionInfo); //returns false
					if( $cid === false )
					{
						//echo "fallo conecion sql server";
						echo "<script>
									/*Swal.fire({
										type: 'error',
										title: 'Fallo',
										text: 'fallo conexion sql server',
										footer: 'Fallo conexion sql server'
									})*/
									alert('Fallo conexion sql server');
							</script>";
					}
					return $cid;
				}
				else
				{
					//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcoverMigra", "diskcover2019Migra@", "DiskCover_Empresas");
					//$conexion=new mysqli("localhost:13306", "diskcoverMigra", "diskcover2019Migra@", "diskcover_empresas");
					//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcover", "disk2017Cover", "diskcover_empresas");
					//$conexion=new mysqli("localhost", "diskcover", "disk2017Cover", "diskcover_empresas",13306);
					mysqli_report(MYSQLI_REPORT_STRICT);//Considera el warning como un error, y así tratar la excepción.
					try {
						$conexion=new mysqli("localhost", "diskcover", "disk2017Cover", "diskcover_empresas",13306);
						$conexion->query("SET NAMES 'utf8'");
						return $conexion;
					} catch (Exception $e) {
						//echo 'ERROR:'.$e->getMessage();
						try {
							$connection = ssh2_connect('mysql.diskcoversystem.com', 22); 
							
							if (ssh2_auth_password($connection, 'diskcover', 'Dlcjvl1210')) {
								   //echo "Authentication Successful!\n";
							} else {
								   die('Authentication Failed...');
							}
							$tunnel = ssh2_tunnel($connection, 'mysql.diskcoversystem.com', 22);
							

							//echo " entroooo ";
						//	shell_exec("ssh -f -L 127.0.0.1:3306:mysql.diskcoversystem.com:13306 diskcover sleep 60 >> logfile");  
							$conexion = new mysqli('mysql.diskcoversystem.com', 'diskcover', 'disk2017Cover', 'diskcover_empresas', 13306);
							$conexion->query("SET NAMES 'utf8'");
							return $conexion;
							/*$conexion=new mysqli("localhost", "root", "", "diskcover_empresas");
							$conexion->query("SET NAMES 'utf8'");
							return $conexion;*/
						} catch (Exception $e) {
							echo 'ERROR :'.$e->getMessage();
							return null;
						}
					}
					/*$connection = ssh2_connect('mysql.diskcoversystem.com', 22); 

					ssh2_auth_password($connection, 'diskcover', 'Dlcjvl1210');

					$tunnel = ssh2_tunnel($connection, 'DESTINATION IP', 3307);
					echo " entroooo ";
					$db = new mysqli_connect('localhost', 'diskcover', 'disk2017Cover', 
										 'diskcover_empresas', 13306, $tunnel)
					or die ('Fail: ' . mysql_error()); */
					
				}
			}
			
		}
		else
		{
			if(isset($_SESSION['INGRESO']['Tipo_Base']) and $tipo_base=='MYSQL' and $_SESSION['INGRESO']['Tipo_Base']!='SQL SERVER') 
			{
				//echo "entro 3";
				$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
				$Base_Datos=$_SESSION['INGRESO']['Base_Datos'];
				$Usuario_DB=$_SESSION['INGRESO']['Usuario_DB'];
				$Contrase=$_SESSION['INGRESO']['Contraseña_DB'];
				$Puerto=$_SESSION['INGRESO']['Puerto'];
				$conexion=new mysqli($server.":".$Puerto, $Usuario_DB, $Contrase, $Base_Datos);
				$conexion->query("SET NAMES 'utf8'");
				return $conexion;
			}
			else
			{
				if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $tipo_base=='SQL SERVER') 
				{
					//echo "entro 4";
					/*$fp = fsockopen($_SESSION['INGRESO']['IP_VPN_RUTA'], $_SESSION['INGRESO']['Puerto'], $errno, $errstr, 30);
					if (!$fp) {
						echo "<script>
									alert('Fallo conexion sql server');
							</script>";
							die();
					}
					fclose($fp);*/
					/*$ip = $_SESSION['INGRESO']['IP_VPN_RUTA'];
					$output = shell_exec("ping $ip");
					 
					if (strpos($output, "recibidos = 0")) {
						echo "<script>
									alert('Fallo conexion sql server verificar IP');
							</script>";
						die();
					}*/ 
					//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
					$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
					$connectionInfo = array("Database"=>$_SESSION['INGRESO']['Base_Datos'], "UID" => $_SESSION['INGRESO']['Usuario_DB'],
					"PWD" => $_SESSION['INGRESO']['Contraseña_DB']);

					$cid = sqlsrv_connect($server, $connectionInfo); //returns false
					if( $cid === false )
					{
						//echo "fallo conecion sql server";
						echo "<script>
									/*Swal.fire({
										type: 'error',
										title: 'Fallo',
										text: 'fallo conexion sql server',
										footer: 'Fallo conexion sql server'
									})*/
									alert('Fallo conexion sql server');
							</script>";
					}
					return $cid;
				}
				else
				{
					//echo "entro 5";
					//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcoverMigra", "diskcover2019Migra@", "DiskCover_Empresas");
					//$conexion=new mysqli("localhost:13306", "diskcoverMigra", "diskcover2019Migra@", "diskcover_empresas");
					//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcover", "disk2017Cover", "diskcover_empresas");
					mysqli_report(MYSQLI_REPORT_STRICT);//Considera el warning como un error, y así tratar la excepción.
					try {
						$conexion=new mysqli("localhost", "diskcover", "disk2017Cover", "diskcover_empresas",13306);
						$conexion->query("SET NAMES 'utf8'");
						return $conexion;
					} catch (Exception $e) {
						//echo 'ERROR:'.$e->getMessage();
						try {
							$connection = ssh2_connect('mysql.diskcoversystem.com', 22); 
							
							if (ssh2_auth_password($connection, 'diskcover', 'Dlcjvl1210')) {
								   //echo "Authentication Successful!\n";
							} else {
								   die('Authentication Failed...');
							}
							$tunnel = ssh2_tunnel($connection, 'mysql.diskcoversystem.com', 22);
							
							/*$stream=ssh2_exec($connection,'echo "select * from diskcover_empresas.lista_empresas where like \"%santa%\";" | mysql');
							stream_set_blocking($stream, true);
							while($line = fgets($stream)) { 
								flush();
								echo "1111";
								echo $line."\n";
							}
							echo " fdfdfd ";
							die();*/
							//$conexion='';
							//echo " entroooo ";
						//	shell_exec("ssh -f -L 127.0.0.1:3306:mysql.diskcoversystem.com:13306 diskcover sleep 60 >> logfile");  
							//shell_exec('ssh -f -L 3307:mysql.diskcoversystem.com:3306 diskcover sleep 10 > /dev/null');
							$conexion = new mysqli('mysql.diskcoversystem.com', 'diskcover', 'disk2017Cover', 'diskcover_empresas', 13306);
							$conexion->query("SET NAMES 'utf8'");
							return $conexion;
							
							/*$conexion=new mysqli("localhost", "root", "", "diskcover_empresas");
							$conexion->query("SET NAMES 'utf8'");
							return $conexion;*/
						} catch (Exception $e) {
							echo 'ERROR :'.$e->getMessage();
							return null;
						}
					}
					
					/*$connection = ssh2_connect('mysql.diskcoversystem.com', 22); 

					ssh2_auth_password($connection, 'diskcover', 'Dlcjvl1210');

					$tunnel = ssh2_tunnel($connection, 'DESTINATION IP', 3307);
					echo " entroooo ";
					$db = new mysqli_connect('localhost', 'diskcover', 'disk2017Cover', 
										 'diskcover_empresas', 13306, $tunnel)
					or die ('Fail: ' . mysql_error()); */
					//return null;
				}
			}
			
		}
       
    }
	public static function encryption($string){
			$output=FALSE;
			$key=hash('sha256', SECRET_KEY);
			$iv=substr(hash('sha256', SECRET_IV), 0, 16);
			$output=openssl_encrypt($string, METHOD, $key, 0, $iv);
			$output=base64_encode($output);
			return $output;
	}
	public static function decryption($string){
		$key=hash('sha256', SECRET_KEY);
		$iv=substr(hash('sha256', SECRET_IV), 0, 16);
		$output=openssl_decrypt(base64_decode($string), METHOD, $key, 0, $iv);
		return $output;
	}
	//para sql server
	public static function conexionSQL(){
		$cid='';
        if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
		{
			/*$fp = fsockopen($_SESSION['INGRESO']['IP_VPN_RUTA'], $_SESSION['INGRESO']['Puerto'], $errno, $errstr, 30);
			if (!$fp) {
				echo "<script>
							alert('Fallo conexion sql server');
					</script>";
					die();
			}
			fclose($fp);*/
			/*$ip = $_SESSION['INGRESO']['IP_VPN_RUTA'];
			$output = shell_exec("ping $ip");
			 
			if (strpos($output, "recibidos = 0")) {
				echo "<script>
							alert('Fallo conexion sql server verificar IP');
					</script>";
				die();
			}*/ 
			//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
			$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
			$connectionInfo = array("Database"=>$_SESSION['INGRESO']['Base_Datos'], "UID" => $_SESSION['INGRESO']['Usuario_DB'],
			"PWD" => $_SESSION['INGRESO']['Contraseña_DB']);

			$cid = sqlsrv_connect($server, $connectionInfo); //returns false
			if( $cid === false )
			{
				//echo "fallo conecion sql server";
				echo "<script>
							/*Swal.fire({
								type: 'error',
								title: 'Fallo',
								text: 'fallo conexion sql server',
								footer: 'Fallo conexion sql server'
							})*/
							alert('Fallo conexion sql server');
					</script>";
			}
		}
        return $cid;
    }
}
?>
