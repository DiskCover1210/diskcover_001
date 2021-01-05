<?php 
require(dirname(__DIR__,2).'/modelo/farmacia/pacienteM.php');
/**
 * 
 */
$controlador = new pacienteC();
if(isset($_GET['provincias']))
{
	$respuesta = $controlador->provincias();
	echo json_encode($respuesta);
}
if(isset($_GET['pacientes']))
{
	$parametros = $_POST['parametros'];
	$respuesta = $controlador->cargar_paciente($parametros);
	echo json_encode($respuesta);
}
if(isset($_GET['buscar_edi']))
{
	$respuesta = $controlador->buscar_ficha($_POST['parametros']);
	echo json_encode($respuesta);
}
if(isset($_GET['nuevo']))
{
	$respuesta = $controlador->insertar_paciente($_POST['parametros']);
	echo json_encode($respuesta);
}
class pacienteC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new pacienteM();
	}

	function cargar_paciente($parametros)
	{
		$datos = $this->modelo->cargar_paciente($parametros);
		$tr = '';
		foreach ($datos as $key => $value) 
		{
			$d =  dimenciones_tabl(strlen($value['ID']));
			$d2 =  dimenciones_tabl(strlen($value['Codigo']));
			$d3 =  dimenciones_tabl(strlen($value['Cliente']));
			$d4 =  dimenciones_tabl(strlen($value['CI_RUC']));
			$d5 =  dimenciones_tabl(strlen($value['Telefono']));
			$tr.='<tr>
  					<td width="'.$d.'">'.$value['ID'].'</td>
  					<td width="'.$d2.'">'.$value['Codigo'].'</td>
  					<td width="'.$d3.'">'.$value['Cliente'].'</td>
  					<td width="'.$d4.'">'.$value['CI_RUC'].'</td>
  					<td width="'.$d5.'">'.$value['Telefono'].'</td>
  					<td width="90px">
  					    <button class="btn btn-sm btn-default" title="Ver Historial"><span class="glyphicon glyphicon-th-large"></span></button>
  						<button class="btn btn-sm btn-primary" onclick="buscar_cod(\'E\',\''.$value['Codigo'].'\')" title="Editar paciente"><span class="glyphicon glyphicon-pencil"></span></button>  						
  						<a href="http://localhost/diskcover_001/php/vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" class="btn btn-sm btn-danger" title="Editar paciente"><span class="glyphicon glyphicon-trash"></span></a>
  					</td>
  				</tr>';
			
		}
		return $tr;
		
	}
	function buscar_ficha($parametros)
	{

		$datos = $this->modelo->cargar_paciente($parametros);
		if(count($datos)>0){
		if($datos[0]['Matricula']=='')
		{			
			$num_his = $this->modelo->ASIGNAR_COD_HISTORIA_CLINICA();
			$revisar = $this->modelo->COD_HISTORIA_CLINICA_EXISTENTE($num_his);
			while ($revisar == -1) {
				$num_his+=1;
				$revisar = $this->modelo->COD_HISTORIA_CLINICA_EXISTENTE($num_his);			
			}
			$datos[0]['campo']='Matricula';
		    $datos[0]['dato']=$num_his;


			$datos2[0]['campo']='Numero';
		    $datos2[0]['dato']=$num_his;

		    $campoWhere[0]['campo']='ID';
		    $campoWhere[0]['valor']=$datos[0]['ID'];
		    if($this->modelo->insertar_paciente($datos,$campoWhere)==1 and $this->modelo->ACTUALIZAR_COD_HISTORIA_CLINICA($datos2)==1)
		    {
		    	$datos = $this->modelo->cargar_paciente($parametros);
		    	return $datos;
		    }

		}else
		{
			return $datos;
		}	
	  }else
	  {
	  	return -1;
	  }
	}
	function insertar_paciente($parametros)
	{
		$datos[0]['campo']='Cliente';
		$datos[0]['dato']=$parametros['nom'];
		$datos[1]['campo']='CI_RUC';
		$datos[1]['dato']=$parametros['ruc'];
		$datos[2]['campo']='Prov';
		$datos[2]['dato']=$parametros['pro'];
		$datos[3]['campo']='Ciudad';
		$datos[3]['dato']=$parametros['loc'];
		$datos[4]['campo']='Telefono';
		$datos[4]['dato']=$parametros['tel'];

		if($parametros['tip']=='E')
		{
			$campoWhere[0]['campo']='ID';
			$campoWhere[0]['valor']=$parametros['id'];
			
			return  $this->modelo->insertar_paciente($datos,$campoWhere,$parametros['tip']);
		}else
		{

		$datos[5]['campo'] = 'Codigo';
		$datos[5]['dato']=digito_verificadorf($parametros['ruc'],1);
		$datos[6]['campo'] = 'T';
		$datos[6]['dato']='N';
			return  $this->modelo->insertar_paciente($datos,false,$parametros['tip']);
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
		$prov = $this->modelo->provincias();
		return $prov;
	}
}

?>