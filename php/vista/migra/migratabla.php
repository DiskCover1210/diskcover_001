<?php

// Test connection
//$server="mysql.diskcoversystem.com";
//$user="sa";
//$password="disk2017Cover";
//$database="DiskCover_Prismanet";
//$base_mi="DiskCover_Prismanet";
//192.168.2.109 */
/*$database="DiskCover_Prismanet";
$server="mysql.diskcoversystem.com";
$user="sa";
$password="disk2017Cover";*/
$database="DiskCover_La_Curia_Tulcan";
$server="192.168.31.2";
$user="sa";
$password="Darwin92";

$ID_Empresa=6;
$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

$cid = sqlsrv_connect($server, $connectionInfo); //returns false
if( $cid === false )
{
	echo "fallo conecion";
}
$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcoverMigra", "diskcover2019Migra@", "DiskCover_Empresas");
$conexion->query("SET NAMES 'utf8'");
/*
 select * from Entidad where Nombre_Entidad like '%CONSEJO GUBERNATIVO DE BIENES DIOCESANOS DE DIÓCESIS - TULCÁN%'

select * from Lista_Empresas where ID_Empresa=6

update Lista_Empresas set IP_VPN_RUTA='192.168.31.2',Tipo_Base='SQL SERVER',Usuario_DB='sa',
`Contraseña_DB`='Darwin92',Puerto='1433' where ID_Empresa=6
*/
//buscamos tabla a migrar
$sql = "SELECT  *
		FROM  Empresas";
$stmt = sqlsrv_query( $cid, $sql);
if( $stmt === false)  
{  
	 echo "Error en consulta.\n";  
	 die( print_r( sqlsrv_errors(), true));  
}  
$i=0;
while( $obj = sqlsrv_fetch_object( $stmt)) 
{
	echo $obj->Item."<br />";
	$tabla[$i]['tabla']=$obj->Item;
	$i++;
	$sql="update Lista_Empresas set IP_VPN_RUTA='".$server."',Tipo_Base='SQL SERVER',Usuario_DB='".$user."',
`Contrasena_DB`='".$password."',Puerto='1433',Base_Datos='".$database."' where ID_Empresa=".$ID_Empresa." AND Item='".$obj->Item."'";
    //echo $sql;
	if ($conexion->query($sql) === TRUE) {
		//echo " ha sido creado".'<br>';
	} else {
		echo "Hubo un error al crear la tabla : ".$tabla[$i]['tabla']." ".$consu1." " . $conexion->error.'<br>';
		die();
	}
}

/*

PARA MAS INFORMACION:

https://docs.microsoft.com/en-us/sql/connect/php/programming-guide-for-php-sql-driver?view=sql-server-2017

*/

?>

