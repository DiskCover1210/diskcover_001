<?php 
include (dirname(__DIR__,2).'/modelo/farmacia/farmacia_internaM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new farmacia_internaC();
if(isset($_GET['tabla_ingresos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_ingresos($parametros));
}
if(isset($_GET['tabla_catalogo']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_catalogo($parametros));
}
if(isset($_GET['cargar_pedidos']))
{	
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_pedidos($parametros));
}
class farmacia_internaC 
{
	private $modelo;
	private $ing_descargos;
	private $pdf;
	function __construct()
	{
		$this->modelo = new farmacia_internaM();
		$this->pdf = new cabecera_pdf();
	}

	function tabla_ingresos($parametros)
	{
		$pro[2] = '';
		if($parametros['proveedor']!='')
		{
		$pro = explode('-',$parametros['proveedor']);
	    }
		// print_r($parametros);die();
		$datos = $this->modelo->tabla_ingresos($pro[2],$parametros['comprobante'],$parametros['factura']);
		return $datos['tbl'];
	}

	function tabla_catalogo($parametros)
	{
		$query ='';
		if($parametros['descripcion']!='')
		{
			$q = explode('_',$parametros['descripcion']);
			$query = $q[0];
		}
		if($parametros['referencia']!='')
		{
			$q = explode('_',$parametros['descripcion']);
			$query = $q[0];
		}
		// print_r($parametros);die();
		$datos = $this->modelo->tabla_catalogo($query,$parametros['tipo']);
		return $datos['tbl'];
	}

	function cargar_pedidos($parametros)
	{
		
		// print_r($parametros);die();
		$datos = $this->modelo->pedido_paciente($parametros['nom'],$parametros['ci'],$parametros['historia'],$parametros['depar'],$parametros['proce'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);
		$tr='';
		// print_r($nega);die();		
		foreach ($datos as $key => $value) {			
			$bur = '';
			// print_r($nega)die();
			
			$item = $key+1;
			$d =  dimenciones_tabl(strlen($value['Fecha_Fab']->format('Y-m-d')));
			$d2 = dimenciones_tabl(strlen($value['nombre']));
			$d3 = dimenciones_tabl(strlen($value['Codigo_B']));
			$d4 = dimenciones_tabl(strlen($value['Matricula']));
			$d5 = dimenciones_tabl(strlen($value['subcta'])); 
			$d6 = dimenciones_tabl(strlen($value['importe']));
			$d7 =  dimenciones_tabl(strlen($value['Detalle']));
			$tr.='<tr>
  					<td width="'.$d.'">'.$value['Fecha_Fab']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['nombre'].'</td>
  					<td width="'.$d3.'">'.$value['Codigo_B'].'</td>
  					<td width="'.$d4.'">'.$value['Matricula'].'</td>
  					<td width="'.$d5.'">'.$value['subcta'].'</td>
  					<td width="'.$d6.'">'.$value['importe'].'</td>
  					<td width="'.$d7.'">'.$value['Detalle'].'</td>
  					<td width="90px">
  						<a href="../vista/farmacia.php?mod=Farmacia&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&num_ped='.$value['ORDEN'].'&area='.$value['area'].'-'.$value['Detalle'].'&cod='.$value['his'].'#" class="btn btn-sm btn-primary" title="Editar pedido"><span class="glyphicon glyphicon-pencil"></span></a>
  						<button class="btn btn-sm btn-danger" onclick="eliminar_pedido(\''.$value['ORDEN'].'\',\''.$value['area'].'\')"><span class="glyphicon glyphicon-trash"></span></button>
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