<?php
//Llamada al modelo
require_once("../modelo/entidad_model.php");

if(isset($_POST['submitlog'])) 
{
	login('', '', '');
}
//devuelve empresas asociadas al usuario
function getEntidades($id_entidad=null)
{
	$per=new entidad_model();
	$entidades=$per->getEntidades($id_entidad);
	return $entidades;
}
 
?>
