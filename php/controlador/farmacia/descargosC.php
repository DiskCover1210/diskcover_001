<?php 
include 'pacienteC.php'; 
include (dirname(__DIR__,2).'/modelo/farmacia/descargosM.php');
/**
 * 
 */
$controlador = new ingreso_descargosC();
if(isset($_GET['pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->pedidos_paciente($parametros));
}

if(isset($_GET['cargar_pedidos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_pedidos($parametros));
}

class ingreso_descargosC 
{
	private $modelo;
	private $paciente;
	function __construct()
	{
		$this->modelo = new descargosM();
		$this->paciente = new pacienteC();
	}

	function pedidos_paciente($parametros)
	{
		// print_r($parametros);die();

		$num_his = $parametros['cod'];
		if($num_his==0)
		{
		   $parametros=array('query'=>$parametros['ci'],'tipo'=>'R1','codigo'=>'');
		   $datos = $this->paciente->buscar_ficha($parametros);
		   return $datos;
		}else
		{

			 // print_r('ssss');die();
		   $parametros=array('query'=>$parametros['ci'],'tipo'=>'R1','codigo'=>'');
		   $datos = $this->paciente->buscar_ficha($parametros);
		   // print_r($datos);die();
		   return $datos;

		}

	}

	function cargar_pedidos($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->pedido_paciente($parametros['codigo'],$parametros['tipo'],$parametros['query'],$parametros['desde'],$parametros['hasta']);
		$tr='';
		foreach ($datos as $key => $value) {
			$item = $key+1;
			$d =  dimenciones_tabl(strlen($item));
			$d2 =  dimenciones_tabl(strlen($value['ORDEN']));
			$d3 =  dimenciones_tabl(strlen($value['nombre']));
			$d4 =  dimenciones_tabl(strlen($value['importe']));
			$d5 =  dimenciones_tabl(strlen($value['Fecha_Fab']->format('Y-m-d')));
			$d6 =  dimenciones_tabl(strlen('E'));
			$tr.='<tr>
  					<td width="'.$d.'">'.$item.'</td>
  					<td width="'.$d2.'">'.$value['ORDEN'].'</td>
  					<td width="'.$d3.'">'.$value['nombre'].'</td>
  					<td width="'.$d4.'">'.$value['importe'].'</td>
  					<td width="'.$d5.'">'.$value['Fecha_Fab']->format('Y-m-d').'</td>
  					<td width="'.$d6.'">E</td>
  					<td width="90px">
  						<a href="../vista/farmacia.php?mod=Farmacia&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&num_ped='.$value['ORDEN'].'#" class="btn btn-sm btn-primary" title="Editar pedido"><span class="glyphicon glyphicon-pencil"></span></a>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-search"></span></button>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
		}
		if(count($datos)>0)
		{
			$tabla = array('num_lin'=>0,'tabla'=>$tr);
			return $tabla;
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>');
			return $tabla;		
		}

	}


}