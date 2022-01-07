<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
if(!isset($_SESSION)) 
	{ 		
			session_start();
	}

//C:\wamp64\www\lib\excel
//llamar funcion generica digito verificadorf
//conexion mysql
//crear select option
function cone_ajaxMYSQL()
{
	//verificamos si es sql server o mysql para consultar periodos
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$Base_Datos=$_SESSION['INGRESO']['Base_Datos'];
		$Usuario_DB=$_SESSION['INGRESO']['Usuario_DB'];
		$Contrase=$_SESSION['INGRESO']['Contraseña_DB'];
		$Puerto=$_SESSION['INGRESO']['Puerto'];
		$conexion=new mysqli($server.":".$Puerto, $Usuario_DB, $Contrase, $Base_Datos);
		return $conexion;
	}
	else
	{
		//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcoverMigra", "diskcover2019Migra@", "DiskCover_Empresas");
		
		mysqli_report(MYSQLI_REPORT_STRICT);//Considera el warning como un error, y así tratar la excepción.
		try {
			$conexion=new mysqli("localhost:13306", "diskcoverMigra", "diskcover2019Migra@", "diskcover_empresas");
			$conexion->query("SET NAMES 'utf8'");
			return $conexion;
		} catch (Exception $e) {
			//echo 'ERROR:'.$e->getMessage();
			try {
				$conexion=new mysqli("localhost", "root", "", "diskcover_empresas");
				$conexion->query("SET NAMES 'utf8'");
				return $conexion;
			} catch (Exception $e) {
				echo 'ERROR :'.$e->getMessage();
				return null;
			}
		}
		//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcover", "disk2017Cover", "diskcover_empresas");
		//$conexion=new mysqli("localhost", "root", "", "diskcover_empresas");
		/*$connection = ssh2_connect('mysql.diskcoversystem.com', 22); 

		ssh2_auth_password($connection, 'diskcover', 'Dlcjvl1210');

		$tunnel = ssh2_tunnel($connection, 'DESTINATION IP', 3307);

		$db = new mysqli_connect('localhost', 'diskcover', 'disk2017Cover', 
								 'diskcover_empresas', 13306, $tunnel)
			or die ('Fail: ' . mysql_error()); */
	}
}
//crear select option
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
//crear select option para mysql
// function select_option_mysql_a($tabla,$valor,$mostrar,$filtro=null)
// {

// 	$cid = new db();	
// 	$sql = "SELECT ".$valor.",".$mostrar." FROM ".$tabla;

// 	$datos = $cid->datos($sql,'MYSQL');
	
// 	if($filtro!=null and $filtro!='')
// 	{
// 		$sql =  $sql." WHERE ".$filtro." ";
// 	}
// 	//echo $sql;
// 	// $consulta=$cid->query($sql) or die($cid->error);
// 	//saber si hay mas campos amostrar
// 	$mostrar1 = explode(",", $mostrar);
// 	if(count($mostrar1)==1)
// 	{
// 		$cam1=0;
// 	}
// 	else
// 	{
// 		$cam1=1;
// 	}

// 	if(count($datos)>0)
// 	{
// 		foreach ($datos as $key => $value) {
// 		 	$op='<option value="'.$value[$valor].'">';
// 					if($cam1==0)
// 					{
// 					   $op.=$value[$mostrar];
// 					}
// 					else
// 					{
// 						$mos1=$mostrar1[0];
// 						$mos2=$mostrar1[1];
// 						$op.= $value[$mos1].'-'.$value[$mos2];
// 					}
// 			$op.="</option>";
// 		 } 
// 	}
	
// 	return $op;
// }
//agregar ceros a cadena a la izquierda 
//$numero=numero o texto a convertir
//$largo_maximo= maximo caracateres a colocar
//$pos= posicion ejem izquierda derecha
//$cadena= caracter a colocar
function generaEspacios($numero,$largo_maximo=null,$pos=null,$cadena=null){
	if($cadena == null)
	{
		$cadena=' ';
	}
	if($pos==null)
	{
		$pos='izq';
	}
	
	 //obtengop el largo del numero
	 $largo_numero = strlen($numero);
	 //especifico el largo maximo de la cadena
	 if($largo_maximo==null)
	 {
		$largo_maximo = 7;
	 }
	 //tomo la cantidad de ceros a agregar
	 $agregar = $largo_maximo - $largo_numero;
	 //agrego los ceros
	 for($i =0; $i<$agregar; $i++){
		if($pos=='izq')
		{
			$numero = $cadena.$numero;
		}
		else
		{
			$numero = $numero.$cadena;
		}
		
	 }
	 //retorno el valor con ceros
	 return $numero;
}
//generica para contar registros $stmt= consulta generada
function contar_registros($stmt)
{
	$i=0;
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$i++;
	}
	return $i;
}
//crear select option
function select_option_a($tabla,$value,$mostrar,$filtro=null,$sel=null)
{
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
		$sql = "SELECT ".$value.",".$mostrar." FROM ".$tabla;
		if($filtro!=null and $filtro!='')
		{
			$sql =  $sql." WHERE ".$filtro." ";
		}
	}
	$mostrar1 = explode(",", $mostrar);
	if(count($mostrar1)==1)
	{
		$cam1=0;
	}
	else
	{
		$cam1=1;
	}
	echo $sql;
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	$selc='';
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$selc='';
		if($sel==$row[0])
		{
			$selc='selected';
		}
		?>	
		<option value='<?php echo $row[0]; ?>' <?php echo $selc; ?> >
			<?php
				if($cam1==0)
				{
					echo $row[1]; 
				}
				else
				{
					echo $row[1].'-'.$row[2]; 
				}
			?></option>
		<?php
	}
	sqlsrv_close( $cid );
}
function esBisiesto_ajax($year=NULL) 
{
    $year = ($year==NULL)? date('Y'):$year;
    return ( ($year%4 == 0 && $year%100 != 0) || $year%400 == 0 );
}
//cerrar sesion caso de usar funciones para hacer consultas rapidas fuera del MVC
function cerrarSQLSERVERFUN($cid)
{
	sqlsrv_close( $cid );
}

?>
