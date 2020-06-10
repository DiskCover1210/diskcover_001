<?php
/*    Using "mysqli" instead of "mysql" that is obsolete.
* Change the value of parameter 3 if you have set a password on the root userid
* Add port number 3307 in parameter number 5 to use MariaDB instead of MySQL
*
*     Utilisation de "mysqli" à la place de "mysql" qui est obsolète.
* Changer la valeur du 3e paramètre si vous avez mis un mot de passe à root
* Ajouter le port 3307 en paramètre 5 si vous voulez utiliser MariaDB
*/
//prueba
$mysqli = new mysqli('127.0.0.1', 'root', '', '');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}
echo '<p>Connection OK '. $mysqli->host_info.'</p>';
echo '<p>Server '.$mysqli->server_info.'</p>';
$mysqli->close();

$server='tcp:mysql.diskcoversystem.com, 11433';
$connectionInfo = array("Database"=>'DiskCover_Prismanet', "UID" => 'sa',
"PWD" => 'disk2017Cover');

$cid = sqlsrv_connect($server, $connectionInfo); //returns false
if( $cid === false )
{
	echo "fallo conecion sql server";
	
}
echo " prueba registro<br>";
$sql="SELECT TOP 1 A_No FROM Asiento
WHERE (Item = '002')
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
		echo ' vcvcvcvc '.$row[0];
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

/*class Foo
{  
	public function makeFoo(callable $bar) :string { return "Hello ".$bar(); } 	
}
class Bar
{ 
	public function makeFooBar() :string { return (new Foo)->makeFoo([$this, 'makeBar']); 
} 

private function makeBar() :string { return "World"; }}
echo (new Bar)->makeFooBar(); */
echo "prueba";
?>
