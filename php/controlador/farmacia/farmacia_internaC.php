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

if(isset($_GET['descargos_medicamentos']))
{	
	$parametros = $_POST['parametros'];
	$paginacion = $_POST['paginacion'];
	echo json_encode($controlador->descargos_medicamentos($parametros,$paginacion));
}

if(isset($_GET['imprimir_pdf']))
{	
	$parametros = $_GET;
	echo json_encode($controlador->reporte_pdf($parametros));
}

if(isset($_GET['imprimir_excel']))
{	
	$parametros = $_GET;
	echo json_encode($controlador->reporte_excel($parametros));
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
			$q = explode('_',$parametros['referencia']);
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
		return $datos;

	}

    function descargos_medicamentos($parametros,$paginacion)
    {
    	// print_r($parametros);die();
    	$resp = $this->modelo->descargos_medicamentos($parametros['medicamento'],$parametros['nom'],$parametros['ci'],$parametros['depar'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);
    	return $resp;
    }
    function reporte_pdf($parametros)
    {
    	// print_r($parametros);die();
    	switch ($parametros['opcion']) {
    		case 1:
	    		$pro[2] = '';
				if($parametros['ddl_proveedor']!='')
				{
				$pro = explode('-',$parametros['ddl_proveedor']);
			    }
			    $titulo = 'R E P O R T E   D E   I N G R E S O S';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->tabla_ingresos($pro[2],$parametros['txt_comprobante'],$parametros['txt_factura']);
	    			 $tablaHTML[0]['medidas']=array(25,100,30,25);
		             $tablaHTML[0]['alineado']=array('L','L','L','L');
		             $tablaHTML[0]['datos']=array('<b>Fecha','<b>Cliente','<b>Comprobante','<b>Factura');
		             $tablaHTML[0]['borde'] =1;
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Cliente'],$value['Comprobante'],$value['Factura']);
		             $tablaHTML[$pos]['borde'] =1;
		             $pos+=1;
	    		}
	    		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'P');
    			break;
    		case 2:
    			$query ='';
				if(isset($parametros['ddl_descripcion']) && $parametros['ddl_descripcion']!='')
				{
					$q = explode('_',$parametros['ddl_descripcion']);
					$query = $q[0];
				}
				if(isset($parametros['ddl_referencia']) && $parametros['ddl_referencia']!='')
				{
					$q = explode('_',$parametros['ddl_referencia']);
					$query = $q[0];
				}
			    $titulo = 'L I S T A D O   D E L   C A T A L O G O';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->tabla_catalogo($query,'ref');
	    			 $tablaHTML[0]['medidas']=array(25,100,30,25);
		             $tablaHTML[0]['alineado']=array('L','L','L','L');
		             $tablaHTML[0]['datos']=array('<b>Codigo','<b>Producto','<b>Valor Total','<b>Cantidad');
		             $tablaHTML[0]['borde'] =1;
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		             $tablaHTML[$pos]['datos']=array($value['Codigo'],$value['Producto'],$value['Valor_Total'],$value['Cantidad']);
		             $tablaHTML[$pos]['borde'] =1;
		             $pos+=1;
	    		}
	    		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'P');
    			break;
    		case 4:
    			$f = '';
    			if(isset($parametros['rbl_fecha'])){$f=1;}

    			$datos = $this->modelo->pedido_paciente($parametros['txt_paciente'],$parametros['txt_ci'],$parametros['txt_historia'],$parametros['txt_departamento'],$parametros['txt_procedimiento'],$parametros['txt_desde'],$parametros['txt_hasta'],$f);
    			// print_r($datos);die();
    		 	$titulo = 'DESCARGOS PARA VISUALIZAR POR PACIENTE';
    		 	$Fechaini =''; $Fechafin='';
    		 	if($parametros['txt_desde'] != $parametros['txt_hasta'])
    		 	{
			    $Fechaini =$parametros['txt_desde'] ; $Fechafin=$parametros['txt_hasta'];
			    }
			    $tablaHTML = array();
	    			 $tablaHTML[0]['medidas']=array(25,85,25,20,50,20,48);
		             $tablaHTML[0]['alineado']=array('L','L','L','L','L','R','L');
		             $tablaHTML[0]['datos']=array('<b>Fecha','<b>Paciente','<b>Cedula','<b>Historia','<b>Departamento','<b>Importe','<b>	Procedimiento');
		             $tablaHTML[0]['borde'] =1;
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Paciente'],$value['Cedula'],$value['Historia'],$value['Departamento'],$value['importe'],$value['Procedimiento']);
		             $tablaHTML[$pos]['borde'] =1;
		             $pos+=1;
	    		}
	    		// print_r($tablaHTML);die();
	    		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'L');
    			break;
    		case 5:
    		    $f = '';
    			if(isset($parametros['rbl_fecha5'])){$f=1;}
    			$Fechaini =''; $Fechafin='';
    		 	if($parametros['txt_desde5'] != $parametros['txt_hasta5'])
    		 	{
			    $Fechaini =$parametros['txt_desde5'] ; $Fechafin=$parametros['txt_hasta5'];
			    }
    			$titulo = 'VISUALIZACION DE DESCARGOS DE FARMACIA INTERNA';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->descargos_medicamentos($parametros['txt_medicamento'],$parametros['txt_paciente5'],$parametros['txt_ci_ruc'],$parametros['txt_departamento5'],$parametros['txt_desde5'],$parametros['txt_hasta5'],$f);
	    			 $tablaHTML[0]['medidas']=array(25,80,85,25,23,45);
		             $tablaHTML[0]['alineado']=array('L','L','L','L','L','L');
		             $tablaHTML[0]['datos']=array('<b>Fecha','<b>Producto','<b>Cliente','<b>Cedula','<b>Matricula','<b>Departamento');
		             $tablaHTML[0]['borde'] =1;
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Producto'],$value['Cliente'],$value['Cedula'],$value['Matricula'],$value['Departamento']);
		             $tablaHTML[$pos]['borde'] =1;
		             $pos+=1;
	    		}
	    		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'L');
    			break;    			
    		
    		default:
    			// code...
    			break;
    	}

    }

    function reporte_excel($parametros)
    {
    		switch ($parametros['opcion']) {
    		case 1:
	    		$pro[2] = '';
				if($parametros['ddl_proveedor']!='')
				{
				$pro = explode('-',$parametros['ddl_proveedor']);
			    }
			    $titulo = 'R E P O R T E   D E   I N G R E S O S';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->tabla_ingresos($pro[2],$parametros['txt_comprobante'],$parametros['txt_factura']);
	    			 $tablaHTML[0]['medidas']=array(13,50,18,18);
		             $tablaHTML[0]['datos']=array('Fecha','Cliente','Comprobante','Factura');
		             $tablaHTML[0]['tipo'] ='C';
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Cliente'],$value['Comprobante'],$value['Factura']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
	    		}
	    		 excel_generico($titulo,$tablaHTML);
	    		// $this->pdf->cabecera_reporte_MC($titulo,$tablaHTML);
    			break;
    		case 2:
    			$query ='';
				if(isset($parametros['ddl_descripcion']) && $parametros['ddl_descripcion']!='')
				{
					$q = explode('_',$parametros['ddl_descripcion']);
					$query = $q[0];
				}
				if(isset($parametros['ddl_referencia']) && $parametros['ddl_referencia']!='')
				{
					$q = explode('_',$parametros['ddl_referencia']);
					$query = $q[0];
				}
			    $titulo = 'L I S T A D O   D E L   C A T A L O G O';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->tabla_catalogo($query,'ref');
	    			 $tablaHTML[0]['medidas']=array(15,50,30,25);
		             $tablaHTML[0]['datos']=array('Codigo','Producto','Valor Total','Cantidad');
		             $tablaHTML[0]['tipo'] ='C';
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Codigo'],$value['Producto'],$value['Valor_Total'],$value['Cantidad']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
	    		}

	    		 excel_generico($titulo,$tablaHTML);
	    		//$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,false,true,25,'P');
    			break;
    		case 4:
    			$f = '';
    			if(isset($parametros['rbl_fecha'])){$f=1;}

    			$datos = $this->modelo->pedido_paciente($parametros['txt_paciente'],$parametros['txt_ci'],$parametros['txt_historia'],$parametros['txt_departamento'],$parametros['txt_procedimiento'],$parametros['txt_desde'],$parametros['txt_hasta'],$f);
    			// print_r($datos);die();
    		 	$titulo = 'DESCARGOS PARA VISUALIZAR POR PACIENTE';
    		 	$Fechaini =''; $Fechafin='';
    		 	if($parametros['txt_desde'] != $parametros['txt_hasta'])
    		 	{
			    $Fechaini =$parametros['txt_desde'] ; $Fechafin=$parametros['txt_hasta'];
			    }
			    $tablaHTML = array();
	    			 $tablaHTML[0]['medidas']=array(13,50,25,20,50,20,48);
		             $tablaHTML[0]['datos']=array('Fecha','Paciente','Cedula','Historia','Departamento','Importe','Procedimiento');
		             $tablaHTML[0]['tipo'] ='C';
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Paciente'],$value['Cedula'],$value['Historia'],$value['Departamento'],$value['importe'],$value['Procedimiento']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
	    		}
	    		// print_r($tablaHTML);die();	    		
	    		 excel_generico($titulo,$tablaHTML);
    			break;
    		case 5:
    		    $f = '';
    			if(isset($parametros['rbl_fecha5'])){$f=1;}
    			$Fechaini =''; $Fechafin='';
    		 	if($parametros['txt_desde5'] != $parametros['txt_hasta5'])
    		 	{
			    $Fechaini =$parametros['txt_desde5'] ; $Fechafin=$parametros['txt_hasta5'];
			    }
    			$titulo = 'VISUALIZACION DE DESCARGOS DE FARMACIA INTERNA';
			    $Fechaini = ''; $Fechafin='';
	    		$datos = $this->modelo->descargos_medicamentos($parametros['txt_medicamento'],$parametros['txt_paciente5'],$parametros['txt_ci_ruc'],$parametros['txt_departamento5'],$parametros['txt_desde5'],$parametros['txt_hasta5'],$f);
	    			 $tablaHTML[0]['medidas']=array(13,50,50,25,23,40);
		             $tablaHTML[0]['datos']=array('Fecha','Producto','Cliente','Cedula','Matricula','Departamento');
		             $tablaHTML[0]['tipo'] ='C';
		             $pos = 1;
	    		foreach ($datos['datos'] as $key => $value) {
	    			 $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		             $tablaHTML[$pos]['datos']=array($value['Fecha']->format('Y-m-d'),$value['Producto'],$value['Cliente'],$value['Cedula'],$value['Matricula'],$value['Departamento']);
		             $tablaHTML[$pos]['tipo'] ='N';
		             $pos+=1;
	    		}	    				
	    		 excel_generico($titulo,$tablaHTML);
    			break;    			
    		
    		default:
    			// code...
    			break;
    	}
    }

}