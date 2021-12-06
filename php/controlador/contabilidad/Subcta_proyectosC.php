<?php
include(dirname(__DIR__,2).'/modelo/contabilidad/Subcta_proyectosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new Subcta_proyectosC();

if(isset($_GET['DGCostos']))
{
	echo json_encode($controlador->DGCostos());
}

if(isset($_GET['DCProyecto']))
{
	echo json_encode($controlador->DCProyecto());
}

if(isset($_GET['DCSubModulos']))
{
	echo json_encode($controlador->DCSubModulos());
}

class Subcta_proyectosC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  Subcta_proyectosM();	   
	   $this->pdf = new cabecera_pdf();
	}


	function DGCostos()
	{
		$resp =  $this->modelo->DGCostos();
		return $resp['tbl'];
		// print_r($resp);die();
	}

	function DCProyecto()
	{
		$resp =  $this->modelo->DCProyecto();
		$datos =array();
		foreach ($resp as $key => $value) {
			$datos[] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Cuenta']);
		}
		// print_r($resp);die();
		return $datos;
	}

	function DCSubModulos()
    {
    	$resp =  $this->modelo->DCSubModulos();
    	$datos =array();
    	foreach ($resp as $key => $value) {
    		$datos[] = array('codigo'=>$value['Codigo'],'nombre'=>$value['Detalle']);
    	}
    	// print_r($resp);die();
    	return $datos;
    }
 



	
}
?>