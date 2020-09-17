<?php 
require(dirname(__DIR__,2).'/modelo/inventario/inventario_onlineM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new inventario_onlineC();
if(isset($_GET['existe_cuenta']))
{
	echo json_encode($controlador->cuenta_existente());
}
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
if (isset($_GET['rubro_bajas'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo json_encode($controlador->lista_rubro_bajas($_GET['q']));
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
if (isset($_GET['producto_id'])) {
	echo json_encode($controlador->producto_id($_GET['q']));
}
if (isset($_GET['generar_asiento'])) {
	echo json_encode($controlador->datos_asientos());
}
if (isset($_GET['datos_asiento'])) {
	echo json_encode($controlador->asientos());
}

if (isset($_GET['datos_asiento_SC'])) {
	echo json_encode($controlador->datos_asiento_SC());
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

	function cuenta_existente()
	{
		$resp = $this->modelo->cuenta_existente();
		return $resp;
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
	function lista_rubro_bajas($query)
	{
		// print_r($query);die();
		$resp = $this->modelo->listar_rubro_bajas($query);
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
		$resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}
	function producto_id($query)
	{
		$resp = $this->modelo->lista_hijos_id($query);
		// $resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}
	function datos_asientos()
	{
		$resp = $this->modelo->datos_asiento();
		// $resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}
	function datos_asiento_SC()
	{
		$resp = $this->modelo->datos_asiento_SC();
		$datos = array(); 
		foreach ($resp as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			// print_r($cuenta);die();
			$sub = $this->modelo->catalogo_subcuentas($value['SUBCTA']);
			$SC = array('benericiario'=>$cuenta[0]['Cuenta'],'ruc'=>'','Codigo'=>$value['CONTRA_CTA'],'tipo'=>$cuenta[0]['TC'],'tic'=>1,'sub'=>$value['SUBCTA'],'fecha'=>$value['Fecha_Fab']->format('Y-m-d'),'fac2'=>0,'valorn'=>$value['total'],'moneda'=>1,'Trans'=>$sub[0]['Detalle'],'T_N'=>60,'t'=>$value['SUBCTA']);
			array_push($datos, $SC);
		}   
		return $datos;
	}
    function encode($arr) 
    {
     $new = array(); 
    foreach($arr as $key => $value) {
      //echo is_array($value);
      if(!is_object($value))
      {
         if($value == '.')
         {

         $new[$key] = '';
         }else{

          $new[utf8_encode($key)] = utf8_encode($value);
          // $new[$key] = $value;
         }
        }else
        {
          //print_r($value);
          $new[$key] = $value;          
        }

     }
     return $new;
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
		   $datos[4]['dato']=$parametro['cta_pro'];
		   $datos[5]['campo']='SUBCTA';
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
		   $datos[12]['campo']='Codigo_Dr';
		   $datos[12]['dato']=$parametro['bajas_por'];
		   $datos[13]['campo']='TC';
		   $datos[13]['dato']=$parametro['TC'];
		   $datos[14]['campo']='VALOR_TOTAL';
		   $datos[14]['dato']=$parametro['total'];
		   $datos[15]['campo']='CANTIDAD';
		   $datos[15]['dato']=$parametro['cant'];
		   $datos[16]['campo']='VALOR_UNIT';
		   $datos[16]['dato']=$parametro['valor'];
		   $datos[17]['campo']='DH';
		   $datos[17]['dato']=2;
		   $datos[18]['campo']='CONTRA_CTA';
		   $datos[18]['dato']=$parametro['cc'];
		   // print_r($datos);die();
		   $resp = $this->modelo->ingresar_asiento_K($datos);
		   return $resp;
	    }else
	    {
	    	// print_r($parametro);die();
	       $datos[0]['campo']='CODIGO_INV';
		   $datos[0]['dato']=strval($parametro['codigo']);
		   $datos[1]['campo']='PRODUCTO';
		   $datos[1]['dato']=$parametro['producto'];
		   $datos[2]['campo']='UNIDAD';
		   $datos[2]['dato']=$parametro['uni'];
		   $datos[3]['campo']='CANT_ES';
		   $datos[3]['dato']=$parametro['cant'];
		   $datos[4]['campo']='CTA_INVENTARIO';
		   $datos[4]['dato']=$parametro['cta_pro'];
		   $datos[5]['campo']='SUBCTA';
		   $datos[5]['dato']=$parametro['rubro'];
		   $datos[6]['campo']='Consumos';
		   $datos[6]['dato']=$parametro['bajas'];
		   $datos[7]['campo']='Procedencia';
		   $datos[7]['dato']=$parametro['observacion'];
		   $datos[8]['campo']='Fecha_Fab';
		   $datos[8]['dato']=date('Y-m-d');
		   $datos[9]['campo']='Codigo_Dr';
		   $datos[9]['dato']=$parametro['bajas_por'];
		   $datos[10]['campo']='TC';
		   $datos[10]['dato']=$parametro['TC'];
		   $datos[11]['campo']='VALOR_TOTAL';
		   $datos[11]['dato']=$parametro['total'];
		   $datos[12]['campo']='CANTIDAD';
		   $datos[12]['dato']=$parametro['cant'];
		   $datos[13]['campo']='VALOR_UNIT';
		   $datos[13]['dato']=$parametro['valor'];
		   $datos[14]['campo']='CONTRA_CTA';
		   $datos[14]['dato']=$parametro['cc'];

		   $where[0]['campo']='CODIGO_INV';
		   $where[0]['valor']=strval($parametro['ante']);
		   $where[1]['campo']='Item';
		   $where[1]['valor']=$_SESSION['INGRESO']['item'];
		   $where[2]['campo']='A_No';
		   $where[2]['valor']=$id-1;
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