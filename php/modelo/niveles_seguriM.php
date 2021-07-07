<?php 
include(dirname(__DIR__).'/db/variables_globales.php');//
include(dirname(__DIR__).'/funciones/funciones.php');
require_once(dirname(__DIR__)."/db/db.php");
/**
 * 
 */
class niveles_seguriM
{
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	   $this->dbs=Conectar::conexionSQL();
	}

	function modulos_todo()
	{
		$cid = Conectar::conexion('MYSQL');
		$sql="SELECT * 
		    FROM modulos 
		    WHERE modulo <> '".G_NINGUNO."' and modulo <> 'VS'
		    ORDER BY aplicacion "; $datos=[];
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

	function entidades($valor)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql ="SELECT Nombre_Entidad,ID_Empresa,RUC_CI_NIC FROM entidad  WHERE RUC_CI_NIC <> '.' AND Nombre_Entidad LIKE '%".$valor."%' 
		    ORDER BY Nombre_Entidad";
		$datos=[];
		if($cid)
		{
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				// $datos[]=['id'=>$filas['ID_Empresa'],'text'=>utf8_encode($filas['Nombre_Entidad'])];	
				$datos[]=['id'=>utf8_encode($filas['ID_Empresa']),'text'=>utf8_encode($filas['Nombre_Entidad']),'RUC'=>utf8_encode($filas['RUC_CI_NIC'])];				
			}
		}
	    return $datos;
	}

	function entidades_usuario($ci_nic)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql ="SELECT AU.Nombre_Usuario,AU.Usuario,AU.Clave, AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC As Codigo_Entidad
				FROM acceso_empresas AS AE,acceso_usuarios AS AU, entidad AS E
				WHERE AU.CI_NIC ='".$ci_nic."'
				AND AE.ID_Empresa = E.ID_Empresa 
				AND AE.CI_NIC = AU.CI_NIC
				GROUP BY AU.Nombre_Usuario,AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC,AU.Usuario,AU.Clave
				ORDER BY E.Nombre_Entidad ";
		$datos=[];
		if($cid)
		{
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				// $datos[]=['id'=>$filas['ID_Empresa'],'text'=>utf8_encode($filas['Nombre_Entidad'])];	
				$datos[]=['id'=>utf8_encode($filas['Codigo_Entidad']),'text'=>utf8_encode($filas['Nombre_Entidad']),'RUC'=>utf8_encode($filas['Codigo_Entidad']),'Usuario'=>utf8_encode($filas['Usuario']),'Clave'=>utf8_encode($filas['Clave']),'Email'=>utf8_encode($filas['Email'])];				
			}
		}
	    return $datos;
	}

	function entidades_usuarios($ci_nic)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql ="SELECT AU.Nombre_Usuario,AU.Usuario,AU.Clave, AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC As Codigo_Entidad
				FROM acceso_empresas AS AE,acceso_usuarios AS AU, entidad AS E
				WHERE AE.ID_Empresa ='".$ci_nic."'
				AND AE.ID_Empresa = E.ID_Empresa 
				AND AE.CI_NIC = AU.CI_NIC
				GROUP BY AU.Nombre_Usuario,AU.Email, E.Nombre_Entidad, E.RUC_CI_NIC,AU.Usuario,AU.Clave
				ORDER BY E.Nombre_Entidad ";
		$datos=[];
		if($cid)
		{
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				// $datos[]=['id'=>$filas['ID_Empresa'],'text'=>utf8_encode($filas['Nombre_Entidad'])];	
				$datos[]=['id'=>utf8_encode($filas['Codigo_Entidad']),'text'=>utf8_encode($filas['Nombre_Entidad']),'RUC'=>utf8_encode($filas['Codigo_Entidad']),'Usuario'=>utf8_encode($filas['Usuario']),'Clave'=>utf8_encode($filas['Clave']),'Email'=>utf8_encode($filas['Email'])];				
			}
		}
	    return $datos;
	}

	function empresas($entidad)
	{
		$cid = Conectar::conexion('MYSQL');
		
		$sql="SELECT  ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_empresa = ".$entidad." AND Item <> '".G_NINGUNO."' ORDER BY Empresa";
		// print_r($sql);die();
		  $datos=[];
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[]=['id'=>utf8_encode($filas['Item']),'text'=>utf8_encode($filas['Empresa'])];				
			}
		 }

	      return $datos;
	}
	function empresas_datos($entidad,$Item)
	{
		$cid = Conectar::conexion('MYSQL');		
		$sql="SELECT  ID,Empresa,Item,IP_VPN_RUTA,Base_Datos,Usuario_DB,Contrasena_DB,Tipo_Base,Puerto   FROM lista_empresas WHERE ID_Empresa=".$entidad." AND Item = '".$Item."' AND Item <> '".G_NINGUNO."' ORDER BY Empresa";
		// print_r($sql);die();
		  $datos=[];
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				// $datos[]=['id'=>$filas['ID'],'text'=>utf8_encode($filas['Empresa']),'host'=>$filas['IP_VPN_RUTA'],'usu'=>$filas['Usuario_DB'],'base'=>$filas['Base_Datos'],'Puerto'=>$filas['Puerto'],'Item'=>$filas['Item']];	
				$datos[]=['id'=>$filas['ID'],'text'=>$filas['Empresa'],'host'=>$filas['IP_VPN_RUTA'],'usu'=>$filas['Usuario_DB'],'base'=>$filas['Base_Datos'],'Puerto'=>$filas['Puerto'],'Item'=>$filas['Item']];				
			}
		 }

	      return $datos;
	}
	function usuarios($entidad,$query)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "SELECT  ID,CI_NIC,Nombre_Usuario,Usuario,Clave,Email FROM acceso_usuarios WHERE SUBSTRING(CI_NIC,1,6)  <> 'ACCESO' AND  Nombre_Usuario LIKE '%".$query."%' ";
		if($entidad)
		{
			$sql.="AND ID_Empresa='".$entidad."'";
		}
		 $datos[]=array('id'=>'0','text'=>'TODOS','CI'=>'0','usuario'=>'TODOS','clave'=>'0');
		 // print_r($sql);die();
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				// $datos[]=['id'=>$filas['CI_NIC'],'text'=>utf8_encode($filas['Nombre_Usuario']),'CI'=>$filas['CI_NIC'],'usuario'=>$filas['Usuario'],'clave'=>$filas['Clave']];
				$datos[]=['id'=>utf8_encode($filas['CI_NIC']),'text'=>utf8_encode($filas['Nombre_Usuario']),'CI'=>utf8_encode($filas['CI_NIC']),'usuario'=>utf8_encode($filas['Usuario']),'clave'=>utf8_encode($filas['Clave']),utf8_encode($filas['Email'])];					
			}
		 }

	      return $datos;


	}

	function acceso_empresas($entidad,$empresas,$usuario)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "SELECT * FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND Item='".$empresas."' AND CI_NIC = '".$usuario."'";
		 $datos=[];
		 // print_r($sql);die();
		 if($cid)
		 {
		 	// print_r($sql);
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[]=array('id'=>$filas['ID'],'Modulo'=>$filas['Modulo'],'item'=>$filas['Item']);				
			}
		 }
	      return $datos;

	}
	function acceso_empresas_($entidad,$empresas,$usuario)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "SELECT * FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND Item='".$empresas."' AND CI_NIC = '".$usuario."'";
		 $datos=[];
		 // print_r($sql);
		 if($cid)
		 {
		 	// print_r($sql);
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[]=array('id'=>$filas['ID'],'Modulo'=>$filas['Modulo'],'item'=>$filas['Item']);				
			}
		 }
	      return $datos;

	}
	function datos_usuario($entidad,$usuario)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "SELECT CI_NIC,Usuario,Clave,Nivel_1 as 'n1',Nivel_2 as 'n2',Nivel_3 as 'n3',Nivel_4 as 'n4',Nivel_5 as 'n5',Nivel_6 as 'n6',Nivel_7 as 'n7',Supervisor,Cod_Ejec,Email FROM acceso_usuarios WHERE  CI_NIC = '".$usuario."'";

		// print_r($sql);die();
		 $datos=array();
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos =$filas;			
			}
		 }
		 // print_r($datos);die();
	      return $datos;

	}

	function actualizar_correo($correo,$ci_nic){
		$cid = Conectar::conexion('MYSQL');
		$sql = "UPDATE acceso_usuarios set Email = '".$correo ."' WHERE CI_NIC = '".$ci_nic."'";
		$cid->query($sql) or die($cid->error);
	}

	function guardar_acceso_empresa($modulos,$entidad,$empresas,$usuario)
	{	
	    $cid = Conectar::conexion('MYSQL');
	    // $delet = $this->delete_modulos($entidad,$empresas,$usuario);
	    // if($delet==1)
	    // {
	    $regis = $this->acceso_empresas_($entidad,$empresas,$usuario);
	    $modulo = explode(',',$modulos);
	    $valor = '';
	    $existe = 0;
	    if(count($regis)>0)
	    {
	       foreach ($modulo as $key => $value) {
	    	   foreach ($regis as $key1 => $value1) {
	    	    if($value == $value1['Modulo'])
	    		   {
	    		   	$existe = 1;
	    		   	break;
	    		   }	    		  
	    	   }
	    	    if($existe == 0)
	    	       {
	    	   	    $valor.= '('.$entidad.',"'.$usuario.'","'.$value.'","'.$empresas.'"),';
	    	       }
	    	   $existe =0;	    	   
	       }   	
	    }else
	    {
	    	foreach ($modulo as $key => $value) {
	    	  $valor.= '('.$entidad.',"'.$usuario.'","'.$value.'","'.$empresas.'"),';
	       }
	    }

	  if($cid)
	  {
	  	if($valor != "")
	  	{
	  		$valor = substr($valor, 0,-1);
	  	    $sql = "INSERT INTO acceso_empresas (ID_Empresa,CI_NIC,Modulo,item) VALUES ".$valor;
	  	   $resultado = mysqli_query($cid, $sql);
	  	   if(!$resultado)
	  		{
	  			echo "Error: " . $sql . "<br>" . mysqli_error($cid);
	  			return -1;
	  		}
	  	   return 1;
	  	   mysqli_close($cid);
	    }
	    return 1;
	  }
	// }


	}
	function update_acceso_usuario($niveles,$usuario,$clave,$entidad,$CI_NIC,$email)
	{
	   $cid = Conectar::conexion('MYSQL');
	   $sql = "UPDATE acceso_usuarios SET TODOS = 1, Nivel_1 =".$niveles['1'].", Nivel_2 =".$niveles['2'].", Nivel_3 =".$niveles['3'].", Nivel_4 =".$niveles['4'].",Nivel_5 =".$niveles['5'].", Nivel_6=".$niveles['6'].", Nivel_7=".$niveles['7'].", Supervisor = ".$niveles['super'].", Usuario = '".$usuario."',Clave = '".$clave."',Email='".$email."' WHERE CI_NIC = '".$CI_NIC."';";
	   if($cid)
	   {
	   	 $resultado = mysqli_query($cid, $sql);
	  	   if(!$resultado)
	  		{
	  			echo "Error: " . $sql . "<br>" . mysqli_error($cid);
	  			return -1;
	  		}	  		
	  	   mysqli_close($cid);
	  	   return 1;
	   }

	}
	function delete_modulos($entidad,$empresas=false,$usuario,$modulo=false)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "DELETE FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." ";
		if($empresas)
		{
			$sql.=" AND Item='".$empresas."'";
		}
			$sql.=" AND CI_NIC = '".$usuario."'";
		if($modulo)
		{
			 $sql.=" AND Modulo='".$modulo."'";
		}
		// print_r($sql);die();
		 if($cid)
		 {
		 	 $resultado = mysqli_query($cid, $sql);
	  	   if(!$resultado)
	  		{
	  			echo "Error: " . $sql . "<br>" . mysqli_error($cid);
	  			return -1;
	  		}	  		
	  	   mysqli_close($cid);
	  	   return 1;
		 }

	}

	function bloquear_usuario($entidad,$CI_NIC)
	{
		  $cid = Conectar::conexion('MYSQL');
	   $sql = "UPDATE acceso_usuarios SET TODOS=0 WHERE ID_Empresa = '".$entidad."' AND CI_NIC = '".$CI_NIC."';";
	   if($cid)
	   {
	   	 $resultado = mysqli_query($cid, $sql);
	  	   if(!$resultado)
	  		{
	  			echo "Error: " . $sql . "<br>" . mysqli_error($cid);
	  			return -1;
	  		}	  		
	  	   mysqli_close($cid);
	  	   return 1;
	   }
	}

	function nuevo_usuario($parametros)
	{
		  $cid = Conectar::conexion('MYSQL');
	   $sql = "INSERT INTO acceso_usuarios (TODOS,Clave,Usuario,CI_NIC,ID_Empresa,Nombre_Usuario) VALUES (1,'".$parametros['cla']."','".$parametros['usu']."','".$parametros['ced']."','".$parametros['ent']."','".$parametros['nom']."')";
	   if($cid)
	   {
	   	 $resultado = mysqli_query($cid, $sql);
	  	   if(!$resultado)
	  		{
	  			echo "Error: " . $sql . "<br>" . mysqli_error($cid);
	  			return -1;
	  		}

	  	    mysqli_close($cid);
	  		if($this->crear_como_cliente_SQLSERVER($parametros)==1)
	  		{
	  			return 1;
	  		}else
	  		{
	  			return -3;
	  		}
	   }
	}

	function crear_como_cliente_SQLSERVER($parametros)
	{
		$registrado = true;
		$cid = Conectar::conexion('MYSQL');
		$sql= "SELECT DISTINCT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_Empresa = '".$parametros['ent']."' AND Base_Datos <>'.'";
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[] =$filas;			
			}
		 }
		 $insertado = false;
		// print_r($datos);die();
		 foreach ($datos as $key => $value) {
		 	if($value['Usuario_DB']=='sa')
		 	{

		 	// print_r($value);die();
		 	     $cid2 = Conectar:: modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);

		 	     // print_r($value['IP_VPN_RUTA'].'-'.$value['Usuario_DB'].'-'.$value['Contrasena_DB'].'-'.$value['Base_Datos'].'-'.$value['Puerto']);die();


		 	     $sql = "INSERT INTO Clientes(T,FA,Codigo,Fecha,Cliente,TD,CI_RUC,FactM,Descuento,RISE,Especial)VALUES('N',0,'".$parametros['ced']."','".date('Y-m-d')."','".$parametros['nom']."','C','".$parametros['ced']."',0,0,0,0);";
		 	     $sql.="INSERT INTO Accesos (TODOS,Clave,Usuario,Codigo,Nombre_Completo,Nivel_1,Nivel_2,Nivel_3,Nivel_4,Nivel_5,Nivel_6,Nivel_7,Supervisor,EmailUsuario) VALUES (1,'".$parametros['cla']."','".$parametros['usu']."','".$parametros['ced']."','".$parametros['nom']."','".$parametros['n1']."','".$parametros['n2']."','".$parametros['n3']."','".$parametros['n4']."','".$parametros['n5']."','".$parametros['n6']."','".$parametros['n7']."','".$parametros['super']."','".$parametros['email']."')";
		 	     // print_r($sql);die();
		 	    $stmt = sqlsrv_query($cid2, $sql);
	            if($stmt === false)  
	        	    {  
	        	    	// print_r('fallo');die();
	        		    // echo "Error en consulta PA.\n";
	        		    // print_r($sql);die();
	        		    return -1;
		               die( print_r( sqlsrv_errors(), true));  
	                }else
	                {

	        	    	// print_r('si');die();
	            	    cerrarSQLSERVERFUN($cid2);
	            	    $insertado = true;
	                }     
	        }     
		 }
		 if($insertado == true)
		 {
		 	return 1;
		 }else
		 {
		 	return -1;
		 }

	}


	function existe_en_SQLSERVER($parametros)
	{
        $registrado = true;
		$cid = Conectar::conexion('MYSQL');
		$sql= "SELECT DISTINCT Base_Datos,Usuario_DB,Contrasena_DB,IP_VPN_RUTA,Tipo_Base,Puerto  FROM lista_empresas WHERE ID_Empresa = '".$parametros['entidad']."' AND Base_Datos <>'.'";
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[] =$filas;			
			}
		 }
		 $insertado = false;
		// print_r($datos);die();
		 foreach ($datos as $key => $value) {
		 	if($value['Usuario_DB']=='sa')
		 	{

		 	// print_r($value);die();
		 	     $cid2 = Conectar:: modulos_sql_server($value['IP_VPN_RUTA'],$value['Usuario_DB'],$value['Contrasena_DB'],$value['Base_Datos'],$value['Puerto']);
		 	     // print_r($cid2);die();

		 	     $sql = "SELECT * FROM Accesos WHERE Codigo = '".$parametros['CI_usuario']."'";
		 	     // print_r($sql);die();
		 	     $stmt = sqlsrv_query($this->dbs, $sql);
		 	     $result = array();	
		 	     if($stmt===false)
		 	     {
		 	     	// print_r('fallo');die();
		 	     	return -2;
		 	     }else{

		 	     	// print_r('consulto');die();
		 	        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC)) 
		 	     	   {
		 	     		   $result[] = $row;
		 	     	   }

		 	     	// print_r($result);die();
		 	     	 if(count($result)>0)
		 	     	 {

		 	     	// print_r('existe');die();
		 	     	 	$sql = "UPDATE Accesos SET Nivel_1 =".$parametros['n1'].", Nivel_2 =".$parametros['n2'].", Nivel_3 =".$parametros['n3'].", Nivel_4 =".$parametros['n4'].",Nivel_5 =".$parametros['n5'].", Nivel_6=".$parametros['n6'].", Nivel_7=".$parametros['n7'].", Supervisor = ".$parametros['super'].", Usuario = '".$parametros['usuario']."',Clave = '".$parametros['pass']."',EmailUsuario='".$parametros['email']."' WHERE Codigo = '".$parametros['CI_usuario']."';";

		 	     	 	  $sql = str_replace('false',0, $sql);
		 	     	 	  $sql = str_replace('true',1, $sql);
		 	     	 	  // print_r($sql);die();
		 	     	 	 $stmt2 = sqlsrv_query($this->dbs, $sql);
		 	     	 	  if( $stmt2 === false)                         
	                          {  
		                        echo "Error en consulta PA.\n";  
		                        return '';
		                        die( print_r( sqlsrv_errors(), true));  
	                          }else
	                          {
	                          	$insertado = true;
	                          }	 	    

		 	     	 }else
		 	     	 {

		 	     	// print_r('no existe');die();
		 	     	 	$parametros_ing = array();
		 	            $parametros_ing['ent']	  = $parametros['entidad'];     	 	 
		 	            $parametros_ing['cla'] = $parametros['pass'];
		 	            $parametros_ing['usu'] = $parametros['usuario'];
		 	            $parametros_ing['ced'] = $parametros['CI_usuario'];
		 	            $parametros_ing['nom'] = $parametros['nombre'];
		 	            $parametros_ing['n1'] = $parametros['n1'];
		 	            $parametros_ing['n2'] = $parametros['n2'];
		 	            $parametros_ing['n3'] = $parametros['n3'];
		 	            $parametros_ing['n4'] = $parametros['n4'];
		 	            $parametros_ing['n5'] = $parametros['n5'];
		 	            $parametros_ing['n6'] = $parametros['n6'];
		 	            $parametros_ing['n7'] = $parametros['n7'];
		 	            $parametros_ing['super'] = $parametros['super'];
		 	            $parametros_ing['email'] = $parametros['email'];
		 	            // print_r($parametros_ing);die();
		 	     	 	 if($this->crear_como_cliente_SQLSERVER($parametros_ing)==1)
		 	     	 	 {
		 	     	 	 	$insertado = true;
		 	     	 	 }
		 	     	 }
		 	     }
	        }     
		 }
		 if($insertado == true)
		 {
		 	return 1;
		 }else
		 {
		 	return -1;
		 }


	}

	function usuario_existente($usuario,$clave,$entidad)
	{
	   $cid = Conectar::conexion('MYSQL');
	   $sql = "SELECT * FROM acceso_usuarios WHERE Usuario = '".$usuario."' AND Clave = '".$clave."' AND ID_Empresa = '".$entidad."'";
	   $datos=array();
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[] =$filas;			
			}
		 }
	  
		 if(count($datos)>0)
		 {
		 	return 1;
		 }else
		 {
		 	return -1;
		 }
	}


	function buscar_ruc($ruc)
	{
	   $cid = Conectar::conexion('MYSQL');
	   $sql = "SELECT Item,Empresa as 'emp',L.RUC_CI_NIC as 'ruc',Estado,L.ID_Empresa,Nombre_Entidad as 'Entidad',E.RUC_CI_NIC as 'Ruc_en' FROM lista_empresas L
	          LEFT JOIN entidad E ON  L.ID_Empresa = E.ID_Empresa
	          WHERE L.RUC_CI_NIC = '".$ruc."'";
       // $sql2 = "SELECT Item,Empresa as 'emp',RUC_CI_NIC as 'ruc',Estado FROM lista_empresas WHERE RUC_CI_NIC = '".$ruc."'";
	   $empresa = array();

		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$entidad[] =$filas;			
			}
			
		 }
		 return $entidad;

	}

	function accesos_modulos($entidad,$usuario)
	{

		$cid = Conectar::conexion('MYSQL');	  
		$sql="SELECT Item,Modulo FROM acceso_empresas WHERE ID_Empresa = '".$entidad."' AND CI_NIC = '".$usuario."'";
		 $datos = array();


		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[] =$filas;			
			}
		 }
		 // print_r($datos);die();
	      return $datos;
	}
	
}
?>