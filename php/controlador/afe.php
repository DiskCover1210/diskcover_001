<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
//Llamada al modelo
require_once("../modelo/afe_model.php");
require_once("../funciones/funciones.php");
//proceso para recibir los datos y llamar a funcion generica digito verificador
if(isset($_POST['RUC']) AND isset($_POST['submitweb'])) 
{
	//obtenemos los datos
	$pag='afe';
	$ruc=$_POST['RUC'];
	$idMen='mens';
	$item=$_POST['item'];
	//echo $item.' vvv ';
	$valor=digito_verificadorf($ruc,$pag,$idMen,$item);
	//redireccionamos
	//echo "entro ".$valor;
	if($valor!='')
	{
		redireccion($pag);
	}
}
//ruc y pagina que llama
function digito_verificador($ruc,$pag=null,$idMen=null)
{
	//$ruc=$_POST['RUC'];
	//echo $ruc.' '.strlen($ruc);
	if(strlen($ruc)==10 and is_numeric($ruc))
	{
		//echo "cedula";
		$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
		$arr1 = str_split($ruc);
		$resu = array();
		$resu1=0;
		$coe1=0;
		$pro='';
		$ter='';
		for($jj=0;$jj<(strlen($ruc));$jj++)
		{
			//echo $arr1[$jj].' -- '.$jj.' cc ';
			//validar los dos primeros registros
			if($jj==0 or $jj==1)
			{
				$pro=$pro.$arr1[$jj];
			}
			if($jj==2)
			{
				$ter=$arr1[$jj];
			}
			//operacion suma
			if($jj<=(strlen($ruc)-2))
			{
				$resu[$jj]=$coe[$jj]*$arr1[$jj];
				if($resu[$jj]>=10)
				{
					$resu[$jj]=$resu[$jj]-9;
				}
				//suma
				$resu1=$resu[$jj]+$resu1;
			}
			//ultimo digito
			if($jj==(strlen($ruc)-1))
			{
				//echo " entro ";
				$coe1=$arr1[$jj];
			}
			
		}
		//verificamos los dos primeros registros
		if($pro>=24)
		{
			echo "incorrecto los dos primeros digitos";
		}
		//verificamos el tercer registros
		if($ter>6)
		{
			echo "incorrecto el tercer digito";
		}
		//partimos string
		$arr2 = str_split($resu1);
		for($jj=0;$jj<(strlen($resu1));$jj++)
		{
			if($jj==0)
			{
				$arr2[$jj]=$arr2[$jj]+1;
			}
		}
		//aumentamos a la siguiente decena
		$resu2=$arr2[0].'0';
		//resultado del ultimo coeficioente
		$resu3 = $resu2- $resu1;
		//echo $resu2.' - '.$resu1.' ';
		//echo $resu3.' ddd '.$coe1;
		if($resu3==$coe1)
		{
			echo "correcto";
		}
		//caso de 10
		if($resu3=='10')
		{
			echo "correcto";
		}
	}
	if(strlen($ruc)==13 and is_numeric($ruc))
	{
		echo "ruc";
	}
	if(strlen($ruc)!=13 and is_numeric($ruc) and strlen($ruc)!=10)
	{
		echo "pasaporte";
	}
	die();
	//login('', '', '');
}
//devuelve empresas asociadas al usuario
/*function getEntidades($id_entidad=null)
{
	$per=new entidad_model();
	$entidades=$per->getEntidades($id_entidad);
	return $entidades;
}*/
 
?>
