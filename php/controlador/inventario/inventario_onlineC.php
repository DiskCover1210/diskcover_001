<?php 
require(dirname(__DIR__,2).'/modelo/inventario/inventario_onlineM.php');
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


class inventario_onlineC
{
	private $modelo;
	
	function __construct()
	{
		$this->modelo = new inventario_onlineM();		
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
		$resp = '';
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
		   $resp = $this->modelo->ingresar_asiento_K($datos);
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

		   $where[0]['campo']='CODIGO_INV';
		   $where[0]['valor']=$parametro['ante'];
		   $where[1]['campo']='Item';
		   $where[1]['valor']=$_SESSION['INGRESO']['item'];
		   $resp = $this->modelo->ingresar_asiento_K($datos,$where);

	    }
	    // print_r($resp);die();
		return $resp;
	}
	function eliminar_linea_entrega($parametro)
	{
		$codigo = $parametro['id'];
		$item = $_SESSION['INGRESO']['item'];
		$resp = $this->modelo->eliminar($codigo,$item);
		return $resp;
	}

	

}
?>