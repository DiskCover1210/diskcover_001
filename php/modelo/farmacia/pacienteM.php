<?php 
include(dirname(__DIR__,2).'/db/variables_globales.php');//
include(dirname(__DIR__,2).'/funciones/funciones.php');
@session_start(); 
/**
 * 
 */
class pacienteM
{
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function cargar_paciente($parametros)
	{
		$cid = $this->conn;
		$sql="SELECT * FROM Clientes WHERE 1=1 ";

		if($parametros['codigo']!='')
		{
			$sql.=" AND Codigo='".$parametros['codigo']."'";
		}
		if($parametros['query']!='')
		{
		   switch ($parametros['tipo']) {
			    case 'N':
				    $sql.=" AND Cliente like '%".$parametros['query']."%'";
				    break;
				case 'N1':
				    $sql.=" AND Cliente = '".$parametros['query']."'";
				    break;
			    case 'C':
				    $sql.=" AND Matricula like '%".$parametros['query']."%'";
				    break;
				case 'C1':
				    $sql.=" AND Matricula='".$parametros['query']."'";
				    break;
			    case 'R':
				    $sql.=" AND CI_RUC like '".$parametros['query']."%'";
				    break;
				case 'R1':
				    $sql.=" AND CI_RUC = '".$parametros['query']."'";
				    break;		
		   }
	    }
		$sql.=" ORDER BY ID OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		// print_r($datos);die();
		return $datos;
	}
	function insertar_paciente($datos,$campoWhere=false,$tipo=false)
	{
		if($tipo && $tipo == 'E')
		{
			// print_r($datos);print_r('expression');die();
			return update_generico($datos,'Clientes',$campoWhere);
		}else if($tipo && $tipo == 'N')
		{
			$resp = insert_generico('Clientes',$datos);
			if($resp=='')
			{
				return 1;
			}
		}else
		{
			// print_r('expression');die();
		   return update_generico($datos,'Clientes',$campoWhere);
			
		}

	}
	function eliminar_paciente()
	{

	}
	function imprimir_paciente()
	{
		
	}
	function provincias()
	{
		$prov = provincia_todas();
		return $prov;
	}
	
	function ASIGNAR_COD_HISTORIA_CLINICA()
	{

		$cid = $this->conn;
		$sql="SELECT * FROM Codigos WHERE 
		Item='".$_SESSION['INGRESO']['item']."' and 
		Periodo='".$_SESSION['INGRESO']['periodo']."' and Concepto='HISTORIA_CLINICA'";
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		if(count($datos)==0)
		{
			$res = $this->CREAR_COD_HISTORIA_CLINICA();
			return $res;
		}else
		{

		   return $datos[0]['Numero'];
		}

	}

	function ACTUALIZAR_COD_HISTORIA_CLINICA($datos)
	{
		$campoWhere[0]['campo']='Concepto';
		$campoWhere[0]['valor']='HISTORIA_CLINICA';

		$campoWhere[1]['campo']='Item';
		$campoWhere[1]['valor']=$_SESSION['INGRESO']['item'];

		$campoWhere[2]['campo']='Periodo';
		$campoWhere[2]['valor']=$_SESSION['INGRESO']['periodo'];

		  return update_generico($datos,'Codigos',$campoWhere);
	}

    function COD_HISTORIA_CLINICA_EXISTENTE($num_his)
	{

		$cid = $this->conn;
		$sql="SELECT * FROM Clientes WHERE Matricula='".$num_his."'";
		// print_r($sql);die();
		$stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		$datos = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		{
			$datos[]=$row;
		}
		if(count($datos)==0)
		{
			return $num_his;
		}else
		{
		   return -1;
		}

	}

	function CREAR_COD_HISTORIA_CLINICA()
	{
		$datos[0]['campo']='Periodo';
		$datos[0]['dato']='.';

		$datos[1]['campo']='Item';
		$datos[1]['dato']=$_SESSION['INGRESO']['item'];

		$datos[2]['campo']='Concepto';
		$datos[2]['dato']='HISTORIA_CLINICA';

		$datos[3]['campo']='Numero';
		$datos[3]['dato']=1;

		$resp = insert_generico('Codigo',$datos);
		if($resp=='')
		{
			return 1;
		}
	}

}

?>