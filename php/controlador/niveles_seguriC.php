<?php 
require(dirname(__DIR__).'/modelo/niveles_seguriM.php');
require(dirname(__DIR__,2).'/lib/phpmailer/enviar_emails.php');
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
if(isset($_GET['buscar_ruc']))
{
	$parametros=$_POST['ruc'];
	echo json_encode($controlador->buscar_ruc($parametros));
}
if(isset($_GET['usuario_empresa']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->modulos_usuario($parametros['entidad'],$parametros['usuario']));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}

if(isset($_GET['acceso_todos']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->accesos_todos($parametros));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}
if(isset($_GET['enviar_email']))
{
	$parametros=$_POST['parametros'];
	echo json_encode($controlador->enviar_email($parametros));
	// echo json_encode($controlador->usuario_empresa($parametros['entidad'],$parametros['usuario']));
}

class niveles_seguriC
{
	private $modelo;
	private $email;
	
	function __construct()
	{
		$this->modelo = new niveles_seguriM();	
		$this->email = new enviar_emails();	
	}
	function entidades($valor)
	{
		$entidades = $this->modelo->entidades($valor);
		echo json_encode($entidades);
		exit();
		return $entidades;

	}
	// function empresas($entidad)
	// {
	// 	 $this->acceso_modulos($entidad);
	// 	// $empresas = $this->modelo->empresas($entidad);
	// 	// $items = '';
	// 	// $linea = '';
	// 	// foreach ($empresas as $key => $value) {
	// 	// 	$linea.= $value['id'].',';
	// 	// 	$items .= '<label class="checkbox-inline" id="lbl_'.$value['id'].'"><input type="checkbox" name="empresas[]" id="emp_'.$value['id'].'" value="'.$value['id'].'" onclick="empresa_select(\''.$value['id'].'\')"><i class="fa fa-circle-o text-red" style="display:none" id="indice_'.$value['id'].'"></i><b>'.utf8_decode($value['text']).'</b></label><br>';
			
	// 	// }
	// 	// $linea = substr($linea,0,-1);
	// 	// return  array('items' => $items,'linea'=>$linea);

	// }
	function usuarios($parametros)
	{
		$parametros['entidad'] = '';
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
					$tabs.='<li class="active" id="tab_'.$value.'" onclick="activo(\''.$value.'\')"><a data-toggle="tab" href="#'.$datos_empresas[0]['Item'].'">'.$datos_empresas[0]['text'].'</a></li>';

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
		$r = $this->modelo->existe_en_SQLSERVER($parametros);
		// print_r($r);die();
		if($r==1)
		{		

		$niveles = array('1'=>$parametros['n1'],'2'=>$parametros['n2'],'3'=>$parametros['n3'],'4'=>$parametros['n4'],'5'=>$parametros['n5'],'6'=>$parametros['n6'],'7'=>$parametros['n7'],'super'=>$parametros['super']);

		// $insert = $this->modelo->guardar_acceso_empresa($modulos,$parametros['entidad'],$empresa,$parametros['CI_usuario']);


		$update = $this->modelo->update_acceso_usuario($niveles,$parametros['usuario'],$parametros['pass'],$parametros['entidad'],$parametros['CI_usuario'],$parametros['email']);
		if($update == 1)
		{
			return 1;
		}else
		{
			return -1 ;
		}
	}else
	{
		return -2;
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
		$parametros['n1'] = 0;
		$parametros['n2'] = 0;
		$parametros['n3'] = 0;
		$parametros['n4'] = 0;
		$parametros['n5'] = 0;
		$parametros['n6'] = 0;
		$parametros['n7'] = 0;
		$parametros['super'] = 0;
		$parametros['email'] = '.';
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

	function buscar_ruc($parametros)
	{
		// print_r($parametros);die();
		$existe = $this->modelo->buscar_ruc($parametros);
		if(count($existe)>0)
		{
			return $existe;
		}else
		{
			return -1;
		}

	}

	// function usuario_empresa($entidad,$usuario)
	// {
	// 	$emp = $this->modelo->usuario_empresas($entidad,$usuario);
	// 	$linea = '';
	// 	foreach ($emp as $key => $value) {
	// 		$linea.=$value['Item'].',';
	// 	}

	// 	// print_r($linea);die();
	// 	return $linea;
	// }


	function empresas($entidad)
	{
		$tbl = '<style>table ,tr td{border:1px solid red}tbody { display:block;height:300px; overflow:auto;}thead, tbody tr {
    display:table;width:100%;table-layout:fixed;}thead { width: calc( 100% - 1em )}table {    width:400px;}</style>';
		$tbl.='<table class="table table-hover table-bordered">
		       <thead><tr style="height:70px" class="bg-info"><th style="width:250px"></th><th style="width: 50px;">Todos</th>';
		$modulos = $this->modelo->modulos_todo();
		foreach ($modulos as $key => $value) {
			$tbl.='<th style="width: 50px;
		        	text-align: center;
		        	transform: rotate(-45deg);
		        	/* display: inline-block;*/
		        	">'.$value['aplicacion'].'</th>';
		}
		$tbl.='</tr></thead><tbody>';		
		$empresas = $this->modelo->empresas($entidad);
		// print_r($empresas);die();
		foreach ($empresas as $key1 => $value1) {
			$tbl.='<tr><td style="width: 250px;"><i class="fa fa-circle-o text-red" style="display:none" id="indice_'.$value1['id'].'"></i><b>'.utf8_decode($value1['text']).'</b></td>
			<td class="text-center" style="border: solid 1px;"><input type="checkbox" name="rbl_'.$value1['id'].'_T" id="rbl_'.$value1['id'].'_T" onclick="marcar_all(\''.$value1['id'].'\')" ></td>';
			foreach ($modulos as $key2 => $value2) {
				$tbl.='<td class="text-center" style="border: solid 1px;"><input type="checkbox" name="rbl_'.$value2['modulo'].'_'.$value1['id'].'" id="rbl_'.$value2['modulo'].'_'.$value1['id'].'" title="'.$value2['aplicacion'].'" onclick="marcar_acceso(\''.$value1['id'].'\',\''.$value2['modulo'].'\')" ></td>';
			}
		}
		return $tbl;
	}

	function modulos_usuario($entidad,$usuario)
	{
		$datos = $this->modelo->accesos_modulos($entidad,$usuario);
		$rbl = array();
		foreach ($datos as $key => $value) {
			// print_r($value);die();
			$rbl[] = 'rbl_'.$value['Modulo'].'_'.$value['Item'];
		}
		return $rbl;
	}
	function accesos_todos($parametros)
	{
		// print_r($parametros);die();
		if($parametros['item']!='' && $parametros['modulo']=='')
		{
			if( $parametros['check']=='true')
			{
			     $this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario']);
			     $modulos = $this->modelo->modulos_todo();
			     $m = '';
			     foreach ($modulos as $key => $value) {
				     $m.=$value['modulo'].',';
			     }

			     $m = substr($m,0,-1);
			     $res = $this->modelo->guardar_acceso_empresa($m,$parametros['entidad'],$parametros['item'],$parametros['usuario']);
			     return $res;
		    }else
		    {
			   $resp =   $this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario']);
			    return $resp;
		    }
		}else
		{
			if($parametros['check']=='true')
			{
				 $res = $this->modelo->guardar_acceso_empresa($parametros['modulo'],$parametros['entidad'],$parametros['item'],$parametros['usuario']);
				 return $res;
			}else
			{
			   $resp = 	$this->modelo->delete_modulos($parametros['entidad'],$parametros['item'],$parametros['usuario'],$parametros['modulo']);
			   return $resp;

			}

		}
	}

  function enviar_email($parametros)
  {
  	// print_r($parametros);die();
    // $empresaGeneral = array_map(array($this, 'encode1'), $this->empresaGeneral);

  	// $nueva_Clave = generate_clave(8);
  	// $datos[0]['campo']='Clave';
  	// $datos[0]['dato']=$nueva_Clave;

  	// $where[0]['campo'] = 'Codigo';
  	// $where[0]['valor'] = $parametros['ci'];
  	// $where[0]['tipo'] = 'string';

//hay que cambiar esas variables de conexion y pass
 
    
  	$this->modelo->actualizar_correo($parametros['email'],$parametros['CI_usuario']);
    $datos = $this->modelo->entidades_usuario($parametros['CI_usuario']);

  	$email_conexion = 'info@diskcoversystem.com'; //$empresaGeneral[0]['Email_Conexion'];
    $email_pass =  'info2021DiskCover'; //$empresaGeneral[0]['Email_Contraseña'];
    // print_r($empresaGeneral[0]);die();
  	$correo_apooyo="credenciales@diskcoversystem.com"; //correo que saldra ala do del emisor
  	$cuerpo_correo = '
  	Estimado (a) '.$parametros['usuario'].' sus credenciales de acceso:
  	 <br>
  	<h3>Usuario:</h3>'.$datos[0]['Usuario'].'<br>
  	<h3>Clave:</h3>'.$datos[0]['Clave'].' <br>
  	<h3>Email:</h3>'.$datos[0]['Email'].' <br>
  	Usted esta asignado a las siguientes entidades: <br>
  	<table>
  	<tr><th>Codigo</th><th>Entidad</th></tr>
  	';

  	foreach ($datos as $value) {
  		$cuerpo_correo .= '<tr><td>'.utf8_decode($value['id']).'</td><td>'.utf8_decode($value['text']).'</td></tr>';
  	}
    $cuerpo_correo .= ' </table><br>'.utf8_decode('
    <pre>
-----------------------------------
SERVIRLES ES NUESTRO COMPROMISO, DISFRUTARLO ES EL SUYO.


Este correo electrónico fue generado automáticamente del Sistema Financiero Contable DiskCover System a usted porque figura como correo electrónico alternativo de Oblatas de San Francisco de Sales.
Nosotros respetamos su privacidad y solamente se utiliza este correo electrónico para mantenerlo informado sobre nuestras ofertas, promociones y comunicados. No compartimos, publicamos o vendemos su información personal fuera de nuestra empresa. Para obtener más información, comunicate a nuestro Centro de Atención al Cliente Teléfono: 052310304. Este mensaje fue recibido por: DiskCover Sytem.

Por la atención que se de al presente quedo de usted.


Esta dirección de correo electrónico no admite respuestas. En caso de requerir atención personalizada por parte de un asesor de servicio al cliente de DiskCover System, Usted podrá solicitar ayuda mediante los canales de atención al cliente oficiales que detallamos a continuación: Telefonos: (+593) 02-321-0051/098-652-4396/099-965-4196/098-910-5300.
Emails: prisma_net@hotmail.es/diskcover@msn.com.

www.diskcoversystem.com
QUITO - ECUADOR</pre>');

  	$titulo_correo = 'Credenciales de acceso al sistema DiskCover System';
  	$archivos = false;
  	$correo = $parametros['email'];
  	// print_r($correo);die();
  	// $resp = $this->modelo->ingresar_update($datos,'Clientes',$where);  	
  	
  	// if($resp==1)
  	// {
  		if($this->email->enviar_credenciales($archivos,$correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,'Credenciales de acceso al sistema DiskCover System',$email_conexion,$email_pass,$html=1)==1){
  			echo json_encode(1);
  			exit();
  		}else
  		{
  			echo json_encode(-1);
  			return -1;
  		}
  	// }else
  	// {
  		// return -1;
  	// }
  }


 // function encode1($arr) {
 //    $new = array(); 
 //    foreach($arr as $key => $value) {
 //      if(!is_object($value))
 //      {
 //      	if($key=='Archivo_Foto')
 //      		{
 //      			if (!file_exists('../../img/img_estudiantes/'.$value)) 
 //      				{
 //      					$value='';
 //      					//$new[utf8_encode($key)] = utf8_encode($value);
 //      					$new[$key] = $value;
 //      				}
 //      		} 
 //         if($value == '.')
 //         {
 //         	$new[$key] = '';
 //         }else{
 //         	//$new[utf8_encode($key)] = utf8_encode($value);
 //         	$new[$key] = $value;
 //         }
 //      }else
 //        {
 //          //print_r($value);
 //          $new[$key] = $value->format('Y-m-d');          
 //        }
 //     }
 //     return $new;
 //    }

}
?>