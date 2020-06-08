<?php
//Llamada al modelo
require_once("../modelo/usuario_model.php");

if(isset($_POST['submitlog'])) 
{
	login('', '', '');
}
//devuelve empresas asociadas al usuario
function getEmpresas($id_entidad)
{
	$per=new usuario_model();
	$empresa=$per->getEmpresas($id_entidad);
	return $empresa;
}
//devuelve empresas seleccionada por el usuario
function getEmpresasId($id_empresa)
{
	//echo ' dd '.$id_empresa;
	$per=new usuario_model();
	$empresa=$per->getEmpresasId($id_empresa);
	return $empresa;
}
//devuelve inf del detalle de la empresa seleccionada por el usuario
function getEmpresasDE($item,$nombre)
{
	//echo ' dd '.$id_empresa;
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getEmpresasDESQL($item,$nombre);
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getEmpresasDEMYSQL($item,$nombre);
	}
	
	return $empresa;
}
//perido actual funcion sql server
function getPeriodoActualSQL()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getPeriodoActualSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getPeriodoActualMYSQL();
	}
	
	return $empresa;
}

//obtener datos de usuario  
function getUsuario()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getUsuarioSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getUsuarioMYSQL();
	}
	
	return $empresa;
}
//verificar acceso usuario
function getAccesoEmpresas()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getAccesoEmpresasSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getAccesoEmpresasMYSQL();
	}
	
	return $empresa;
} 
//consultar modulo
function getModulo()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getModuloSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getModuloMYSQL();
	}
	
	return $empresa;
} 
?>
