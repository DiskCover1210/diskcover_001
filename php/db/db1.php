<?php 
@session_start();
/**
 * 
 */
 // $d = new db();
 // $d->conexion('MYSQL');
class db
{
	private $usuario;
	private $password;  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	private $servidor;
	private $database;
	private $puerto;
	
	function __construct()
	{
		$this->usuario = 'diskcover';
	    $this->password =  'disk2017Cover';  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    $this->servidor ='mysql.diskcoversystem.com';
	    $this->database = 'diskcover_empresas';
	    $this->puerto = 13306;	   
	}

	function conexion($tipo='')
	{
		if($tipo=='MYSQL' || $tipo=='My SQL')
		{
		  return $this->MySQL();
		  // $this->MySQL2();

		}else
		{
		  return  $this->SQLServer();
		}

		
	}

	function SQLServer()
	{
		// print_r($_SESSION['INGRESO']);die();
		$this->usuario = $_SESSION['INGRESO']['Usuario_DB'];
	    $this->password = $_SESSION['INGRESO']['Contraseña_DB'];  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    $this->servidor = $_SESSION['INGRESO']['IP_VPN_RUTA'];
	    $this->database = $_SESSION['INGRESO']['Base_Datos'];
	    $this->puerto = $_SESSION['INGRESO']['Puerto'];
		// print_r($_SESSION);die();

		$connectionInfo = array("Database"=>$this->database, "UID" => $this->usuario,"PWD" => $this->password,"CharacterSet" => "UTF-8");
		// print_r($connectionInfo);die();
		$cid = sqlsrv_connect($this->servidor.', '.$this->puerto, $connectionInfo); //returns false
		if( $cid === false )
		   {
				echo 'no se pudo conectar a la base de datos';
				die( print_r( sqlsrv_errors(), true));
		   }else
		   {
		   	// echo 'sql con';
		   }
		return $cid;
	}

	function MySQL()
	{
		$this->usuario = 'diskcover';
	    $this->password =  'disk2017Cover';  // en mi caso tengo contraseña pero en casa caso introducidla aquí.
	    $this->servidor ='mysql.diskcoversystem.com';
	    $this->database = 'diskcover_empresas';
	    $this->puerto = 13306;
		$conn =  new mysqli($this->servidor, $this->usuario, $this->password,$this->database,$this->puerto);
		$conn->set_charset("utf8");
		if (!$conn) 
		{
			echo  mysqli_connect_error();
			return false;
		}else
		{
			// echo 'conec 1';
		}
		return $conn;
	}



	function datos($sql,$tipo=false)
	{
		if($tipo=='MY SQL')
		{
			$conn = $this->MySQL();
			$resultado = mysqli_query($conn, $sql);
			if(!$resultado)
			{
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
				return false;
			}
			$datos = array();
			while ($row = mysqli_fetch_assoc($resultado)) {
				$datos[] = $row;
			}
			mysqli_close($conn);
			return $datos;

		}else
		{
			$conn = $this->SQLServer();	
			$stmt = sqlsrv_query($conn,$sql);
			// print_r($sql);die();
			$result = array();	
			if( $stmt === false) {
				die( print_r( sqlsrv_errors(), true) );
			}
			while( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC) ) 
	     	{
	     		$result[] = $row;
	     	}
	     	sqlsrv_close($conn);
	     	return $result;

		}
	
	}

	function existe_registro($sql,$tipo=false)
	{
		if($tipo=='MY SQL')
		{
			$conn = $this->MySQL();
			$resultado = mysqli_query($conn, $sql);
			if(!$resultado)
			{
				echo "Error: " . $sql . "<br>" . mysqli_error($conn);
				return false;
			}
			$datos = array();
			while ($row = mysqli_fetch_assoc($resultado)) {
				$datos[] = $row;
			}
			mysqli_close($conn);
			if(count($datos)>0)
	     	{
	     		return 1;
	     	}else
	     	{
	     		return 0;
	     	}

		}else
		{
			$conn = $this->SQLServer();	
			$stmt = sqlsrv_query($conn,$sql);
			// print_r($sql);die();
			$result = array();	
			if( $stmt === false) {
				die( print_r( sqlsrv_errors(), true) );
			}
			while( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC) ) 
	     	{
	     		$result[] = $row;
	     	}
	     	sqlsrv_close($conn);
	     	if(count($result)>0)
	     	{
	     		return 1;
	     	}else
	     	{
	     		return 0;
	     	}

		}
	
	}


	function String_Sql($sql,$tipo=false)
	{
		if($tipo=='MY SQL')
		{
			$conn = $this->MySQL();
			$resultado = mysqli_query($conn, $sql);
		    if(!$resultado)
		    {
			  echo "Error: " . $sql . "<br>" . mysqli_error($conn);
			  return -1;
		    }

		    mysqli_close($conn);
		    return 1;

		}else
		{
		   $conn = $this->SQLServer();
           $stmt = sqlsrv_query($conn, $sql);
		   if(!$stmt)
		   {
			   die( print_r( sqlsrv_errors(), true));
			   sqlsrv_close($conn);
			return -1;
		   }

		   sqlsrv_close($conn);
		   return 1;

		}

	}

	function ejecutar_procesos_almacenados($sql,$parametros,$tipo=false)
	{
		if($tipo=='MY SQL')
		{
			// colocar funcion para ejecutar procesos almacenados en mysql

		}else
		{
		   $conn = $this->SQLServer();
           $stmt = sqlsrv_prepare($conn, $sql, $parametros);
           if (!sqlsrv_execute($stmt)) 
           {
           	echo "Error en consulta PA.\n";  
           	$respuesta = -1;
           	die( print_r( sqlsrv_errors(), true));  
           }
		   sqlsrv_close($conn);
		   return 1;
		}

	}
	
}
?>