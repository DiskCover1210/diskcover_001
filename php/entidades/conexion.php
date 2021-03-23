<?php
/*include_once("../LibPhp/GenCifras.php");
include_once("General.inc.php");
include_once("GenUti.inc.php");
include_once("adodb.inc.php");
include_once("CMysqlConnection.php");
include_once("Reporte.class.php");
include_once("funciones.php");*/

function conectar($DBSRVR, $DBUSER, $DBPASS, $DBNAME, $tunner=null)
{
	$conexion=null;
	if($tunner=='0' OR $tunner==null)
	{
		$conexion = @mysqli_connect($DBSRVR, $DBUSER, $DBPASS, $DBNAME);
		// $hijo_con=@mysqli_connect('localhost', 'root', 'xx', '15_luderson');
		if (!$conexion) {
			die("imposible conectarse: " . mysqli_error($conexion));
		}
		if (@mysqli_connect_errno()) {
			die("Connect failed: " . mysqli_connect_errno() . " : " . mysqli_connect_error());
		}
	}
	else
	{
		$connection = ssh2_connect($DBSRVR, 22); 
		if (ssh2_auth_password($connection, 'root', '20Arb12')) 
		{
		 	echo "Authentication Successful!\n";
		} 
		else 
		{
		 	die('Authentication Failed...');
		}
		$tunnel = ssh2_tunnel($connection, $DBSRVR, 22);
		//echo " entroooo ";
		/*$stream=ssh2_exec($connection,'echo "select * from '.$DBNAME.'.eDoc_empresa ;"  | mysql');
		stream_set_blocking($stream, true);
		while($line = fgets($stream)) {
			   flush();
			   echo $line."\n";
		}*/
		//shell_exec("ssh -f -L 127.0.0.1:3306:".$DBSRVR.":3306 root sleep 60 >> logfile"); 
		$conexion = @mysqli_connect($DBSRVR, $DBUSER, $DBPASS, $DBNAME,3307, $tunnel);
		// $hijo_con=@mysqli_connect('localhost', 'root', 'xx', '15_luderson');
		if (!$conexion) {
			die("imposible conectarse: " . mysqli_error($conexion));
		}
		if (@mysqli_connect_errno()) {
			die("Connect failed: " . mysqli_connect_errno() . " : " . mysqli_connect_error());
		}
	}
	return $conexion;
}

function cerrar($link)
{
	if ($link) 
	{
		// Database is reachable
		mysqli_close($link);
	}
}
function seleccionMysql($link,$tabla,$filtro=null)
{
	if($filtro!=null)
	{
		$sql= "SELECT * FROM ".$tabla." WHERE ".$filtro." ; ";
	}
	else
	{
		$sql= "SELECT * FROM ".$tabla." ; ";
	}
	$query = mysqli_query($link,$sql);
	$row2 = mysqli_fetch_array($query, MYSQLI_BOTH);
	while($row = $result->fetch_array())
	{
		$rows[] = $row;
	}
	return $rows;
}
function conexionSQL($DBSRVR, $DBUSER, $DBPASS, $DBNAME,$Puerto)
{
	$cid=null;
	$server=$DBSRVR.', '.$Puerto;
	$connectionInfo = array("Database"=>$DBNAME, "UID" => $DBUSER,"PWD" => $DBPASS);
	$cid = sqlsrv_connect($server, $connectionInfo);
	if( $cid === false )
	{
		echo "imposible conectarse:<br>";
		echo "Fallo conexion sql server ".$server." - ".$connectionInfo[0]." - ".$connectionInfo[1]." - ".$connectionInfo[2]."";
	}
	return $cid;
}
function cantidadCamposSqlServer($link,$tabla,$filtro=null)
{
	$cant=0;
	if($filtro!=null)
	{
		$sql= "SELECT  * 
		FROM   ".$tabla." WHERE ".$filtro." ; ";
		
	}
	else
	{
		$sql= "SELECT  * 
		FROM   ".$tabla." ; ";
	}
	$stmt = sqlsrv_query( $link, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
		foreach( $fieldMetadata as $name => $value) {
			if(!is_numeric($value))
			{
				if($value!='')
				{
					$cant++;
				}
			}
		}
	}
	return $cant;
}
function seleccionSqlServer($link,$campo=null,$tabla,$filtro=null)
{
	if($campo==null)
	{
		$campo='*';
	}
	if($filtro!=null)
	{
		$sql= "SELECT  ".$campo." 
		FROM   ".$tabla." WHERE ".$filtro." ; ";
		
	}
	else
	{
		$sql= "SELECT  ".$campo." 
		FROM   ".$tabla." ; ";
	}
	//echo $sql;
	// print_r($sql);die();
	$stmt = sqlsrv_query( $link, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	return $stmt;
}
function cerrarSQLSERVERFUN($cid)
{
	sqlsrv_close( $cid );
}
?>
