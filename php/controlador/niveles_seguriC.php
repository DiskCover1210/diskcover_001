<?php 
require(dirname(__DIR__).'/modelo/niveles_seguriM.php');
/**
 * 
 */
$controlador = new niveles_seguriC();
if (isset($_GET['modulos'])) {
	echo json_encode($controlador->modulos($_POST['parametros']));
}
if (isset($_GET['empresas'])) {
	echo json_encode($controlador->empresas($_POST['entidad']));
}
if (isset($_GET['usuarios'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	$parametros = array('entidad'=>$_GET['entidad'],'query'=>$_GET['q']);
	echo json_encode($controlador->usuarios($parametros));
}
if (isset($_GET['entidades'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q']='';
	}
	echo json_encode($controlador->entidades($_GET['q']));
}
if(isset($_GET['mod_activos']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->mod_activos($parametros['entidad'],$parametros['empresa'],$parametros['usuario']));
}
if(isset($_GET['usuario_data']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->data_usuario($parametros['entidad'],$parametros['usuario']));
}
if(isset($_GET['guardar_datos']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->guardar_datos_modulo($parametros));
}
if(isset($_GET['bloqueado']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->bloqueado_usurio($parametros));
}
if(isset($_GET['nuevo_usuario']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->nuevo_usurio($parametros));
}
class niveles_seguriC
{
	private $modelo;
	
	function __construct()
	{
		$this->modelo = new niveles_seguriM();		
	}
	function entidades($valor)
	{
		$entidades = $this->modelo->entidades($valor);
		return $entidades;

	}
	function empresas($entidad)
	{
		$empresas = $this->modelo->empresas($entidad);
		$items = '';
		foreach ($empresas as $key => $value) {
			$items .= '<label class="checkbox-inline" id="lbl_'.$value['id'].'"><input type="checkbox" name="empresas[]" id="emp_'.$value['id'].'" value="'.$value['id'].'" onclick="empresa_select(\''.$value['id'].'\')"><b>'.$value['text'].'</b></label><br>';
			
		}
		return $items;

	}
	function usuarios($parametros)
	{
		$usuarios = $this->modelo->usuarios($parametros['entidad'],$parametros['query']);
		// print_r($usuarios);die();
		return $usuarios;
	}
	function modulos($parametros)
	{
		// print_r($parametros);die();
		$conjunto_empresa = substr($parametros['empresa'],0,-1);
		$empresa_selec =explode(',', $conjunto_empresa);
		$items = '';
		$tabs = '';
		foreach ($empresa_selec as $key => $value) {
			$datos_empresas = $this->modelo->empresas_datos($parametros['entidad'],$value);
			if(count($datos_empresas)>0)
			{
				if($key==0)
				{
					$tabs.='<li class="active" id="tab_'.$value.'"><a data-toggle="tab" href="#'.$datos_empresas[0]['Item'].'">'.$datos_empresas[0]['text'].'</a></li>';

				}else
				{
					$tabs.='<li><a data-toggle="tab" href="#'.$datos_empresas[0]['id'].'">'.$datos_empresas[0]['text'].'</a></li>';
				}
				 $modulos = $this->modelo->modulos_todo();
				 if(count($datos_empresas)>0)
				 {				 	
				 	if($key==0)
				 	{
				 		$items.='<div id="'.$datos_empresas[0]['Item'].'" class="tab-pane fade in active">';
				 	}else
				 	{
				 		$items.='<div id="'.$datos_empresas[0]['Item'].'" class="tab-pane fade">';
				 	}
				 	$items.='<form id="form_'.$value.'">';
				 	$mod = $this->modelo->acceso_empresas($parametros['entidad'],$value,$parametros['usu']);
				 	$existente = 0;
				 		
				 	foreach ($modulos as $key1 => $value1) {
				 		if(count($mod)>0)
				 		{
				 			foreach ($mod as $key2 => $value2) {
				 				if ($value2['Modulo'] == $value1['modulo']) {
				 					$existente = 1;
				 					break;
				 				}
				 			}
				 			if($existente == 1)
				 			 {
				 			 	$items .= '<label class="checkbox-inline"><input type="checkbox" name="modulos_'.$value.'_'.$value1['modulo'].'" id="modulos_'.$value.'_'.$value1['modulo'].'" value="'.$value1['modulo'].'" checked><b>'.$value1['aplicacion'].'</b></label><br>';				 				
				 			 }else
				 			 {
				 			 	$items .= '<label class="checkbox-inline"><input type="checkbox" name="modulos_'.$value.'_'.$value1['modulo'].'" id="modulos_'.$value.'_'.$value1['modulo'].'" value="'.$value1['modulo'].'"><b>'.$value1['aplicacion'].'</b></label><br>';
				 			 }
				 			 $existente = 0;
				 		}else
				 		{
				 			$items .= '<label class="checkbox-inline"><input type="checkbox" name="modulos_'.$value.'_'.$value1['modulo'].'" id="modulos_'.$value.'_'.$value1['modulo'].'" value="'.$value1['modulo'].'"><b>'.$value1['aplicacion'].'</b></label><br>';
				 		}
				 		// if(count($mod)>0)
				 		// {
				 		// 	$items .= '<label class="checkbox-inline"><input type="checkbox" name="modulos_'.$value.'[]" value="'.$value1['modulo'].'" checked><b>'.$value1['aplicacion'].'</b></label><br>';
				 		// }
				 		// else
				 		// {
				 		// 	$items .= '<label class="checkbox-inline"><input type="checkbox" name="modulos_'.$value.'[]" value="'.$value1['modulo'].'"><b>'.$value1['aplicacion'].'</b></label><br>';
				 		// }
				 	}
				 	$items.='</form></div>';
				 }

			}			
		}
		$contenido = array('header'=>$tabs,'body'=>'<div class="tab-content" id="tab-content">'.$items.'</div>');
		// print_r($contenido);die();		
		return $contenido;

	}
	function mod_activos($entidad,$empresa,$usuario)
	{
		$mod = $this->modelo->acceso_empresas($entidad,$empresa,$usuario);
		return $mod;

	}
	function data_usuario($entidad,$usuario)
	{
		$data = $this->modelo->datos_usuario($entidad,$usuario);
		return $data;
	}
	function guardar_datos_modulo($parametros)
	{
		$modulos = explode(',', $parametros['modulos']);
		$ultimo = count($modulos)-1;
		$empresa = $modulos[$ultimo];
		$modulo = '';
		foreach ($modulos as $key => $value) {
			if ($key != $ultimo) {
				$modulo.=$value.',';
			}			
		}
		$modulos = substr($modulo,0,-1);
		$niveles = array('1'=>$parametros['n1'],'2'=>$parametros['n2'],'3'=>$parametros['n3'],'4'=>$parametros['n4'],'5'=>$parametros['n5'],'6'=>$parametros['n6'],'7'=>$parametros['n7'],'super'=>$parametros['super']);
		$insert = $this->modelo->guardar_acceso_empresa($modulos,$parametros['entidad'],$empresa,$parametros['CI_usuario']);
		$update = $this->modelo->update_acceso_usuario($niveles,$parametros['usuario'],$parametros['pass'],$parametros['entidad'],$parametros['CI_usuario']);
		if($insert == 1 and $update == 1)
		{
			return 1;
		}else
		{
			return -1 ;
		}

	}
	function bloqueado_usurio($parametros)
	{
		$rest = $this->modelo->bloquear_usuario($parametros['entidad'],$parametros['usuario']);
		return $rest;

	}
	function nuevo_usurio($parametros)
	{
		// print_r($parametros);die();
		$existe = $this->modelo->usuario_existente($parametros['usu'],$parametros['cla'],$parametros['ent']);
		if($existe == 1)
		{
			return -2;
		}else
		{
			$op = $this->modelo->nuevo_usuario($parametros);
			if($op==1)
			{
				return 1;
			}else if($op == -3)
			{
				return -3;
			}
			else
			{
				return -1;
			}			
		}
		// $rest = $this->modelo->nuevo_usuario();
		// return $rest;

	}

}
?>