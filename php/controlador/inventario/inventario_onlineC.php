<?php 
require(dirname(__DIR__,2).'/modelo/inventario/inventario_onlineM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new inventario_onlineC();
if (isset($_GET['producto'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo json_encode($controlador->lista_producto($_GET['q']));
}
if (isset($_GET['rubro'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo json_encode($controlador->lista_rubro($_GET['q']));
}

if (isset($_GET['cc'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo json_encode($controlador->lista_cc($_GET['q']));
}
if (isset($_GET['entrega'])) {
	
	echo json_encode($controlador->lista_entrega());
}
if (isset($_GET['guardar'])) {
	
	echo json_encode($controlador->guardar_entrega($_POST['parametros']));
}
if (isset($_GET['eliminar_linea'])) {
	echo json_encode($controlador->eliminar_linea_entrega($_POST['parametros']));
}
if (isset($_GET['reporte_pdf'])) {
	echo json_encode($controlador->reporte_pdf());
}
if (isset($_GET['reporte_excel'])) {
	echo json_encode($controlador->reporte_EXcel());
}


class inventario_onlineC
{
	private $modelo;
	private $pdf;
	
	function __construct()
	{
		$this->modelo = new inventario_onlineM();		
		$this->pdf = new cabecera_pdf();	
		// $this->pdftable = new PDF_MC_Table();			
	}

	function lista_producto($query)
	{
		$resp = $this->modelo->listar_articulos($query);
		return $resp;
	}
	function lista_rubro($query)
	{
		// print_r($query);die();
		$resp = $this->modelo->listar_rubro($query);
		return $resp;
	}
	function lista_cc($query)
	{
		$resp = $this->modelo->listar_cc($query);
		return $resp;
	}

	function lista_entrega()
	{
		$resp = $this->modelo->lista_entrega();
		return $resp;
	}
	function guardar_entrega($parametro)
	{
		// print_r($parametro);die();
		$id =count($this->lista_entrega())+1;
		if($parametro['id']=='')
		{
		   $datos[0]['campo']='CODIGO_INV';
		   $datos[0]['dato']=$parametro['codigo'];
		   $datos[1]['campo']='PRODUCTO';
		   $datos[1]['dato']=$parametro['producto'];
		   $datos[2]['campo']='UNIDAD';
		   $datos[2]['dato']=$parametro['uni'];
		   $datos[3]['campo']='CANT_ES';
		   $datos[3]['dato']=$parametro['cant'];
		   $datos[4]['campo']='CTA_INVENTARIO';
		   $datos[4]['dato']=$parametro['cc'];
		   $datos[5]['campo']='CONTRA_CTA';
		   $datos[5]['dato']=$parametro['rubro'];		   
		   $datos[6]['campo']='CodigoU';
		   $datos[6]['dato']=$_SESSION['INGRESO']['Id'];   
		   $datos[7]['campo']='Item';
		   $datos[7]['dato']=$_SESSION['INGRESO']['item'];
		   $datos[8]['campo']='Consumos';
		   $datos[8]['dato']=$parametro['bajas'];
		   $datos[9]['campo']='Procedencia';
		   $datos[9]['dato']=$parametro['observacion'];
		   $datos[10]['campo']='A_No';
		   $datos[10]['dato']=$id;
		   $datos[11]['campo']='Fecha_Fab';
		   $datos[11]['dato']=date('Y-m-d',strtotime($parametro['fecha']));
		   // print_r($datos);die();
		   $resp = $this->modelo->ingresar_asiento_K($datos);
		   return $resp;
	    }else
	    {
	    	// print_r($parametro);die();
	       $datos[0]['campo']='CODIGO_INV';
		   $datos[0]['dato']=$parametro['codigo'];
		   $datos[1]['campo']='PRODUCTO';
		   $datos[1]['dato']=$parametro['producto'];
		   $datos[2]['campo']='UNIDAD';
		   $datos[2]['dato']=$parametro['uni'];
		   $datos[3]['campo']='CANT_ES';
		   $datos[3]['dato']=$parametro['cant'];
		   $datos[4]['campo']='CTA_INVENTARIO';
		   $datos[4]['dato']=$parametro['cc'];
		   $datos[5]['campo']='CONTRA_CTA';
		   $datos[5]['dato']=$parametro['rubro'];
		   $datos[6]['campo']='Consumos';
		   $datos[6]['dato']=$parametro['bajas'];
		   $datos[7]['campo']='Procedencia';
		   $datos[7]['dato']=$parametro['observacion'];
		   $datos[8]['campo']='Fecha_Fab';
		   $datos[8]['dato']=date('Y-m-d');

		   $where[0]['campo']='CODIGO_INV';
		   $where[0]['valor']=$parametro['ante'];
		   $where[1]['campo']='Item';
		   $where[1]['valor']=$_SESSION['INGRESO']['item'];
		   // print_r($datos);die();
		   $resp = $this->modelo->ingresar_asiento_K($datos,$where);
return $resp;
	    }
	    // print_r($resp);die();
		
	}
	function eliminar_linea_entrega($parametro)
	{
		$codigo = $parametro['id'];
		$item = $_SESSION['INGRESO']['item'];
		$pos = $parametro['id_'];
		$resp = $this->modelo->eliminar($codigo,$item,$pos);
		return $resp;
	}

	function reporte_pdf()
	{
		$titulo = 'E N T R E G A  DE  M A T E R I A L E S';
		$sizetable = 8;
		$mostrar = true;
		$resp = $this->modelo->lista_entrega();
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(18,23,35,10,35,23,18,28);
		$tablaHTML[0]['alineado']=array('L','L','L','R','L','L','C','L');
		$tablaHTML[0]['datos']=array('FECHA','CODIGO','PRODUCTO','CANT','CENTRO DE COSTOS','RUBRO','BAJAS O DESPER','OBSERVACIONES');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$pos = 1;
		foreach ($resp as $key => $value) {

			// print_r($value);die();
			$rubro = $this->modelo->listar_rubro($value['CONTRA_CTA']);
			$cc =  $this->modelo->listar_cc($value['CTA_INVENTARIO']);
			$tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=array('L','L','L','C','L','L','R','L');
		    $tablaHTML[$pos]['datos']=array($value['Fecha_Fab']->format('Y-m-d'),$value['CODIGO_INV'],$value['PRODUCTO'],$value['CANT_ES'],$cc[0]['text'],$rubro[0]['text'],$value['Consumos'],$value['Procedencia']);

		 $tablaHTML[$pos]['borde'] ='T';

		    $pos+=1;
		}
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,'','',$sizetable,$mostrar,25);
	}

	function reporte_EXcel()
	{
		$this->modelo->cargar_datos_cuenta_datos(true);
	}
	

}
?>