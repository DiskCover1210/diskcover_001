<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class auditoriaM
{	
		
    private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function modulos_todo($query =false)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql="SELECT * 
		    FROM modulos 
		    WHERE modulo <> '".G_NINGUNO."' and modulo <> 'VS'";
		    if($query)
		    {
		    	$sql.=" AND aplicacion like '%".$query."%'";

		    }
		    $sql.="ORDER BY aplicacion "; 
		    $datos=[];
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[]=['modulo'=>$filas['modulo'],'aplicacion'=>utf8_encode($filas['aplicacion'])];				
			}
		 }

	      return $datos;
	}
	function entidades($valor=false,$ruc=false)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql ="SELECT Nombre_Entidad,ID_Empresa,RUC_CI_NIC as 'ruc' FROM entidad  WHERE RUC_CI_NIC <> '.' ";
		   if($valor){
			 $sql.="AND Nombre_Entidad LIKE '%".$valor."%'";
		   }
		   if($ruc)
		   {
		   	$sql.=" AND RUC_CI_NIC =  '".$ruc."' ";
		   } 
		    $sql.="ORDER BY Nombre_Entidad";
		  $datos=[];
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				// $datos[]=['id'=>$filas['ID_Empresa'],'text'=>utf8_encode($filas['Nombre_Entidad'])];	
				$datos[]=['id'=>$filas['ruc'].'_'.$filas['ID_Empresa'],'text'=>$filas['Nombre_Entidad']];				
			}
		 }

	      return $datos;

	}

	function empresas($entidad,$query=false,$item=false)
	{
		$cid = Conectar::conexion('MYSQL');
		
		$sql="SELECT  ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_empresa = ".$entidad." AND Item <> '".G_NINGUNO."'";
		if($query)
		{
			$sql.=" AND Empresa like '%".$query."%' ";
		}
		if($item)
		{
			$sql.=" AND Item  = '".$item."' ";
		}

		$sql.="ORDER BY Empresa";
		// print_r($sql);die();
		  $datos=[];
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[]=['id'=>$filas['Item'],'text'=>utf8_encode($filas['Empresa'])];				
			}
		 }

	      return $datos;
	}

	function tabla_registros($entidad = false,$empresa=false,$CodigoU=false,$aplicacion=false,$desde=false,$hasta=false)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "SELECT IP_Acceso,Nombre_Usuario as 'nom',RUC,A.Fecha,Hora,Aplicacion,Tarea,Item,Nombre_Entidad as 'enti' 
		FROM acceso_pcs A
		INNER JOIN entidad E ON A.RUC = E.RUC_CI_NIC 
		INNER JOIN acceso_usuarios AC ON A.CodigoU = AC.CI_NIC ";
		if($entidad)
		{
		  $sql.=" and RUC='".$entidad."' ";
		}
		if($empresa)
		{
		  $sql.=" and A.Item ='".$empresa."' ";
		}
		if($CodigoU)
		{
		  $sql.=" and A.CodigoU ='".$CodigoU."' ";
		}
		if($aplicacion)
		{
		  $sql.=" and Aplicacion ='".$aplicacion."' ";
		}

		$sql.=" AND A.Fecha BETWEEN ";
		if($desde)
		{
			$sql.=" '".$desde."' ";
		}
		$sql.=" AND";
		if($hasta)
		{
			$sql.=" '".$desde."'";
		}

		$sql.=' LIMIT 0, 50 ';

		 // print_r($sql);die();
		 $datos=array();
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[] =$filas;			
			}
		 }
	    return $datos;
	}



}
?>