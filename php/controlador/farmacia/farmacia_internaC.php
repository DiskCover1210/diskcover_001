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

}