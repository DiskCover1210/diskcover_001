<?php
require_once("../../db/db.php");
require_once("../../funciones/funciones_ajax.php");

$return = array('success' => false);
IF($_POST):
//$cid=cone_ajaxSQL();
//$con = new Conectar();
$cid = Conectar::conexion();
$sql = "UPDATE acceso_usuarios set Clave= '".$_POST['clave']."',Cambio=1 WHERE ID='".$_POST['id']."' ";
//echo $sql;
$consulta=$cid->query($sql) or die($cid->error);
//$stmt = sqlsrv_query( $cid, $sql);

if( $consulta === false)  
{  
	 echo "Error en consulta.\n";  
	 $return = array('success' => false);
	 //die( print_r( sqlsrv_errors(), true));  
}
else
{	
	$_SESSION['INGRESO']['Cambio']=1;
	$return = array('success' => true, 'name'=>$_POST['clave']);
}
$cid->close();
//echo $_SESSION['INGRESO']['Cambio'];
//die();
else:
$return = array('success' => false);
ENDIF;
echo json_encode($return);
/*$return = array('success' => false);
IF($_POST):
include("conexion.php");
$link=Conectarse();
$query3=mysql_query("select * from usuarios where id = ".$_POST['clave'],$link);
while ( $fila_buscar3=mysql_fetch_array($query3)) 
     {
		 if($fila_buscar3['tipo_usuario'] === 'administrador'):
		 $query4=mysql_query("insert into log_nomina (user_name,autorizado_por,nomina) values ('".$_POST['user_name']."',".$_POST['clave'].",".$_POST['nomina'].")",$link);
		 $return = array('success' => true, 'name'=>$fila_buscar3['nombre']);
		 else:
		 $return = array('success' =>false);
		 endif;
	 }
else:
$return = array('success' => false);
ENDIF;
echo json_encode($return);*/
?>