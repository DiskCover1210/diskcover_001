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

	function tabla_registros($entidad = false,$empresa=false,$CodigoU=false,$aplicacion=false,$desde=false,$hasta=false,$paginacion)
	{
		$query = false;
		$cid = $this->conn;
		$sql="SELECT *
		   FROM Clientes 
		   WHERE T <> '.' ";
		   if($query != '')
		   {
		   	$sql.=" AND Cliente LIKE '%".$query."%'";
		   }
		   // $sql.=" ORDER BY Cliente OFFSET 0 ROWS FETCH NEXT 12 ROWS ONLY;";
		  // print_r($sql);die();
  //       $stmt = sqlsrv_query($cid, $sql);
	 //    $result = array();	
	 //   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	 //   {
		// $result[] = $row;
	 //   }

	   // return $result;

		 // print_r($sql);die();


		  $botones[0] = array(
		  	'boton'=>'Ver factura',  //nombre del title y de la funcion onclick -- los espacion se remplazan por (_)
		  	'icono'=>'<i class="fa fa-trash"></i>', //icono del boton
		  	'tipo'=>'primary', // default,primary,danger,success, info -- color del boton boopstrap
		  	'id'=>'ID' // campos de la consulta sql para agregart en funcion onclick -- funcion('','','')
		  );	  


		  $check[0] = array(
		  	'boton'=>'Ver factura', //nombre del title y de la funcion onclick -- los espacion se remplazan por (_)
		  	'id'=>'ID,CI_RUC,Sexo',  // campos de la consulta sql para agregart en funcion onclick -- funcion('','','')
		  	'text_visible'=>false //titulo de header visible o no (true /false)
		  );
		  
		  $image=array();
		  $tabla = 'Clientes';
		  $titulo ='balance de comprobacion';
		  $paginado = $paginacion;
		  //array('0','25','cargar_registros'); //paginado primero desde que registro va a comenzar segundo cantidad de rregistros, tercero funcio que se ejecuta para mostrar los registros
	return  grilla_generica_new($sql,$tabla,$id_tabla=false,$titulo,$botones,$check,$imagen=false,null,null,null,null,null,$paginado);
	// ($sql,$tabla,$id_tabla=false,$titulo,$botones,$check,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$tamaño_tabla=300,$num_decimales=2,$num_reg=false)
		
	}

	function imprimir_excel($stmt1)
	{		
	     exportar_excel_auditoria($stmt1,'Auditoria',null,1);
	}



}
?>