<?php
//Llamada al modelo
require_once("../modelo/contacto_model.php");

if(isset($_POST['submitweb'])) 
{
	set_contacto('contacto', '', '');
}
 function set_contacto($sTabla, $vValores, $sCampos=NULL)
{
	//echo "entroooo";
	//vemos los campos a enviar
	if (isset($_POST['campo']) )
	{
		$cam=$_POST['campo'];
	}
	$sCampos='';
	$vValores='';
	for($i=0;$i<count($cam);$i++)
	{
		$sCampos=$sCampos.$cam[$i].', ';
		$va=$cam[$i];
		$vValores=$vValores.'\''.addslashes($_POST[$va]).'\',';
	}
	//campos auditoria
	if(isset($_POST['usuario'])) 
	{
		$usuario=$_POST['usuario'];
	}
	else
	{
		$usuario="web";
	}
	
	$fecha=date("d-m-Y");
	$sCampos=$sCampos." usuario, usuario_mod, fecha, fecha_mod ";
	$vValores=$vValores.'\''.$usuario.'\',\''.$usuario.'\',\''.$fecha.'\',\''.$fecha.'\'';
	//llamamos al modelo
	$per=new contacto_model();
	$per->set_contacto($sTabla, $vValores,$sCampos);
	$datos=$per->get_contacto();
	//Llamada a la vista
	//require_once("../vista/contacto_view.php");
}	
//$per=new contacto_model();
//$datos=$per->get_contacto();
//Llamada a la vista
//require_once("../vista/contacto_view.php");
?>
