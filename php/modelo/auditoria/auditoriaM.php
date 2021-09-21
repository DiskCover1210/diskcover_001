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
		$this->conn = new db();
		
	}

	function modulos_todo($query =false)
	{
		$sql="SELECT * 
		    FROM modulos 
		    WHERE modulo <> '".G_NINGUNO."' and modulo <> 'VS'";
		    if($query)
		    {
		    	$sql.=" AND aplicacion like '%".$query."%'";

		    }
		    $sql.="ORDER BY aplicacion "; 

		    $datos = $this->conn->datos($sql,'MY SQL');
	      return $datos;
	}
	function entidades($valor=false,$ruc=false)
	{
		$sql ="SELECT Nombre_Entidad,ID_Empresa,RUC_CI_NIC as 'ruc' FROM entidad  WHERE RUC_CI_NIC <> '.' ";
		   if($valor){
			 $sql.="AND Nombre_Entidad LIKE '%".$valor."%'";
		   }
		   if($ruc)
		   {
		   	$sql.=" AND RUC_CI_NIC =  '".$ruc."' ";
		   } 
		    $sql.="ORDER BY Nombre_Entidad";
		  $datos = $this->conn->datos($sql,'MY SQL');
		  $lista = array();
		  foreach ($datos as $key => $value) {
		  	// code...
		  	$lista[] = array('id'=>$value['ruc'].'_'.$value['ID_Empresa'],'text'=>$value['Nombre_Entidad']);
		  }
	      return $lista;



		 //  $datos=[];
		 // if($cid)
		 // {
		 // 	$consulta=$cid->query($sql) or die($cid->error);
		 // 	while($filas=$consulta->fetch_assoc())
			// {
			// 	// $datos[]=['id'=>$filas['ID_Empresa'],'text'=>utf8_encode($filas['Nombre_Entidad'])];	
			// 	$datos[]=['id'=>$filas['ruc'].'_'.$filas['ID_Empresa'],'text'=>$filas['Nombre_Entidad']];				
			// }
		 // }

	  //     return $datos;

	}

	function empresas($entidad,$query=false,$item=false)
	{
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

		 $datos = $this->conn->datos($sql,'MY SQL');
		  $lista[0] = array('id'=>'0','text'=>'TODOS');
		  foreach ($datos as $key => $value) {
		  	// code...
		  	$lista[] = array('id'=>$value['Item'],'text'=>$value['Empresa']);
		  }
	      return $lista;


		 //  $datos[]=array('id'=>'0','text'=>'TODOS');
		 // if($cid)
		 // {
		 // 	$consulta=$cid->query($sql) or die($cid->error);
		 // 	while($filas=$consulta->fetch_assoc())
			// {
			// 	// $datos[]=['id'=>$filas['Item'],'text'=>utf8_encode($filas['Empresa'])];		
			// 	$datos[]=['id'=>$filas['Item'],'text'=>$filas['Empresa']];				
			// }
		 // }

	  //     return $datos;
	}

	function tabla_registros($entidad = false,$empresa=false,$CodigoU=false,$aplicacion=false,$desde=false,$hasta=false,$numReg = 50)
	{
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
			$sql.=" '".$hasta."'";
		}

		if($numReg!='T')
		{
		  $sql.=' LIMIT 0, '.$numReg.' ';
		}

		  $datos = $this->conn->datos($sql,'MY SQL');
	      return $datos;
	}

	function imprimir_excel($stmt1)
	{		
	     exportar_excel_auditoria($stmt1,'Auditoria',null,1);
	}

	function delete_registros($parametros)
	{
		$sql = "DELETE FROM acceso_pcs WHERE  Fecha BETWEEN '".$parametros['txt_desde']."' and '".$parametros['txt_hasta']."'";

		if($parametros['ddl_modulos']!='')
		{
			$sql.=" AND Aplicacion = '".$parametros['ddl_modulos']."'";
		}
		if($parametros['ddl_entidad']!='')
		{
			$entidad = explode('_', $parametros['ddl_entidad']);
			$sql.=" AND RUC = '".$entidad[0]."' ";
		}
		if($parametros['ddl_usuario']!= '')
		{
			$sql.=" AND CodigoU = '".$parametros['ddl_usuario']."'";
		}
		if($parametros['ddl_empresa']!='')
		{
			$sql.=" AND Item='".$parametros['ddl_empresa']."' ";
		}
		// print_r($sql);die();

		$datos = $this->conn->String_Sql($sql,'MY SQL');
	    return $datos;
	}



}
?>