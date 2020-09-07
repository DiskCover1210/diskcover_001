<?php 
include(dirname(__DIR__).'/db/variables_globales.php');//
include(dirname(__DIR__).'/funciones/funciones.php');
/**
 * 
 */
class niveles_seguriM
{
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
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
		$sql ="SELECT Nombre_Entidad,ID_Empresa FROM entidad  WHERE RUC_CI_NIC <> '.' AND Nombre_Entidad LIKE '%".$valor."%' 
		    ORDER BY Nombre_Entidad";
		  $datos=[];
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[]=['id'=>$filas['ID_Empresa'],'text'=>utf8_encode($filas['Nombre_Entidad'])];	
				// $datos[]=['id'=>$filas['ID_Empresa'],'text'=>$filas['Nombre_Entidad']];				
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
				$datos[]=['id'=>$filas['Item'],'text'=>utf8_encode($filas['Empresa'])];				
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
				$datos[]=['id'=>$filas['ID'],'text'=>utf8_encode($filas['Empresa']),'host'=>$filas['IP_VPN_RUTA'],'usu'=>$filas['Usuario_DB'],'base'=>$filas['Base_Datos'],'Puerto'=>$filas['Puerto'],'Item'=>$filas['Item']];				
			}
		 }

	      return $datos;
	}
	function usuarios($entidad,$query)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "SELECT  ID,CI_NIC,Nombre_Usuario,Usuario,Clave FROM acceso_usuarios WHERE SUBSTRING(CI_NIC,1,6)  <> 'ACCESO' AND  Nombre_Usuario LIKE '%".$query."%' AND ID_Empresa='".$entidad."'";
		 $datos=[];
		 // print_r($sql);die();
		 if($cid)
		 {
		 	$consulta=$cid->query($sql) or die($cid->error);
		 	while($filas=$consulta->fetch_assoc())
			{
				$datos[]=['id'=>$filas['CI_NIC'],'text'=>utf8_encode($filas['Nombre_Usuario']),'CI'=>$filas['CI_NIC'],'usuario'=>$filas['Usuario'],'clave'=>$filas['Clave']];				
			}
		 }

	      return $datos;


	}

	function acceso_empresas($entidad,$empresas,$usuario)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "SELECT * FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND CI_NIC = '".$usuario."'";
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
		$sql = "SELECT Usuario,Clave,Nivel_1 as 'n1',Nivel_2 as 'n2',Nivel_3 as 'n3',Nivel_4 as 'n4',Nivel_5 as 'n5',Nivel_6 as 'n6',Nivel_7 as 'n7',Supervisor,Cod_Ejec FROM acceso_usuarios WHERE  ID_Empresa = ".$entidad." AND CI_NIC = '".$usuario."'";
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
	function guardar_acceso_empresa($modulos,$entidad,$empresas,$usuario)
	{	
	    $cid = Conectar::conexion('MYSQL');
	    $delet = $this->delete_modulos($entidad,$empresas,$usuario);
	    if($delet==1)
	    {
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
	}


	}
	function update_acceso_usuario($niveles,$usuario,$clave,$entidad,$CI_NIC)
	{
	   $cid = Conectar::conexion('MYSQL');
	   $sql = "UPDATE acceso_usuarios SET Nivel_1 =".$niveles['1'].", Nivel_2 =".$niveles['2'].", Nivel_3 =".$niveles['3'].", Nivel_4 =".$niveles['4'].",Nivel_5 =".$niveles['5'].", Nivel_6=".$niveles['6'].", Nivel_7=".$niveles['7'].", Supervisor = ".$niveles['super'].", Usuario = '".$usuario."',Clave = '".$clave."' WHERE ID_Empresa = '".$entidad."' AND CI_NIC = '".$CI_NIC."';";
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
	function delete_modulos($entidad,$empresas,$usuario)
	{
		$cid = Conectar::conexion('MYSQL');
		$sql = "DELETE FROM acceso_empresas WHERE  ID_Empresa = ".$entidad." AND Item='".$empresas."' AND CI_NIC = '".$usuario."'";
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
	
}
?>