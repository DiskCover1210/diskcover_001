<?php 
require(dirname(__DIR__,2).'/modelo/inventario/inventario_onlineM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
$_SESSION['INGRESO']['modulo_']='60';
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
	echo json_encode($controlador->datos_asientos($_POST['fechaA']));
}

if (isset($_GET['datos_asiento_SC'])) {
	echo json_encode($controlador->datos_asiento_SC($_POST['fecha']));
}

if (isset($_GET['datos_comprobante'])) {
	echo json_encode($controlador->datos_comprobante($_POST['codigo']));
}
if (isset($_GET['Trans_kardex'])) {
	echo json_encode($controlador->ingresar_trans_kardex_salidas($_POST['comprobante'],$_POST['f']));
}
if (isset($_GET['eliminar_asientos_k'])) {
	echo json_encode($controlador->eliminar_asientos_k());
}
if (isset($_GET['stock_kardex'])) {
	echo json_encode($controlador->stock_kardex($_POST['id']));
}
if (isset($_GET['costo_venta'])) {
	echo json_encode($controlador->costo_venta($_POST['id']));
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
	function datos_asientos($fecha)
	{
		$debe = $this->modelo->datos_asiento_debe($fecha);		
		$haber = $this->modelo->datos_asiento_haber($fecha);
		// print_r($haber);die();
		$desperdicios_debe = $this->modelo->desperdicios_debe($fecha);
		$desperdicios_haber = $this->modelo->desperdicios_haber($fecha);
		$datos1 = array();
		$datos2= array();
		foreach ($debe as $key => $value) {
			// print_r($value);die();
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);
			// $d = array('valor'=>$value['total'],'dconcepto1'=>utf8_encode($cuenta[0]['Cuenta']),'codigo'=>$value['cuenta'],'cuenta'=>utf8_encode($cuenta[0]['Cuenta']),'tipo_cue'=>1,'fecha'=>$value['fecha']->format('Y-m-d'));

			$d = array('valor'=>round($value['total'],2),'dconcepto1'=>$cuenta[0]['Cuenta'],'codigo'=>$value['cuenta'],'cuenta'=>$cuenta[0]['Cuenta'],'tipo_cue'=>1,'fecha'=>$value['fecha']->format('Y-m-d'));
			array_push($datos1, $d);

		}

		if(count($desperdicios_debe)>0)
		{
		$cuenta1 = $this->modelo->catalogo_cuentas($_SESSION['INGRESO']['CTA_DESPERDICIO']);
		$tot = 0;
		foreach ($desperdicios_debe as $key => $value) {
			$tot =$tot+ $value['TOTAL'];
			// $d = array('valor'=>$value['total'],'dconcepto1'=>utf8_encode($cuenta[0]['Cuenta']),'codigo'=>$value['cuenta'],'cuenta'=>utf8_encode($cuenta[0]['Cuenta']),'tipo_cue'=>1,'fecha'=>$value['fecha']->format('Y-m-d'))
		}
		$d1 = array('valor'=>round($tot,2),'dconcepto1'=>$cuenta1[0]['Cuenta'],'codigo'=>$_SESSION['INGRESO']['CTA_DESPERDICIO'],'cuenta'=>$cuenta1[0]['Cuenta'],'tipo_cue'=>1,'fecha'=>$value['fecha']->format('Y-m-d'));
			array_push($datos1, $d1);
		}

		foreach ($haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);
			// $h = array('valor'=>$value['total'],'dconcepto1'=>utf8_encode($cuenta[0]['Cuenta']),'codigo'=>$value['cuenta'],'cuenta'=>utf8_encode($cuenta[0]['Cuenta']),'tipo_cue'=>2,'fecha'=>$value['fecha']->format('Y-m-d'));
			// array_push($datos2,$h);

			$h = array('valor'=>round($value['total'],2),'dconcepto1'=>$cuenta[0]['Cuenta'],'codigo'=>$value['cuenta'],'cuenta'=>$cuenta[0]['Cuenta'],'tipo_cue'=>2,'fecha'=>$value['fecha']->format('Y-m-d'));
			array_push($datos2,$h);

		}
		foreach ($desperdicios_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['CTA_INVENTARIO']);
			// $h = array('valor'=>$value['total'],'dconcepto1'=>utf8_encode($cuenta[0]['Cuenta']),'codigo'=>$value['cuenta'],'cuenta'=>utf8_encode($cuenta[0]['Cuenta']),'tipo_cue'=>2,'fecha'=>$value['fecha']->format('Y-m-d'));
			// array_push($datos2,$h);

			$h = array('valor'=>round($value['TOTAL'],2),'dconcepto1'=>$cuenta[0]['Cuenta'],'codigo'=>$value['CTA_INVENTARIO'],'cuenta'=>$cuenta[0]['Cuenta'],'tipo_cue'=>2,'fecha'=>$value['fecha']->format('Y-m-d'));
			array_push($datos2,$h);
		}
		$resp = array('debe'=>$datos1,'haber'=>$datos2);
		// print_r($resp);die();
		return $resp;
	}
	function datos_asiento_SC($fecha)
	{
		$resp = $this->modelo->datos_asiento_SC($fecha);
		$desperdicio = $this->modelo-> desperdicios_debe($fecha);
		$datos = array(); 
		foreach ($resp as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			//print_r($cuenta);die();
			$sub = $this->modelo->catalogo_subcuentas($value['SUBCTA']);
			// print_r($sub);die();
			// $SC = array('benericiario'=>$cuenta[0]['Cuenta'],'ruc'=>'','Codigo'=>$value['CONTRA_CTA'],'tipo'=>$cuenta[0]['TC'],'tic'=>1,'sub'=>$value['SUBCTA'],'fecha'=>$value['Fecha_Fab']->format('Y-m-d'),'fac2'=>0,'valorn'=>$value['total'],'moneda'=>1,'Trans'=>utf8_encode($sub[0]['Detalle']),'T_N'=>60,'t'=>$value['SUBCTA']);
			// array_push($datos, $SC);
				$SC = array('benericiario'=>$cuenta[0]['Cuenta'],'ruc'=>'','Codigo'=>$value['CONTRA_CTA'],'tipo'=>$cuenta[0]['TC'],'tic'=>1,'sub'=>$value['SUBCTA'],'fecha'=>$value['Fecha_Fab']->format('Y-m-d'),'fac2'=>0,'valorn'=>round($value['total'],2),'moneda'=>1,'Trans'=>$sub[0]['Detalle'],'T_N'=>60,'t'=>$value['SUBCTA']);
			array_push($datos, $SC);

		} 
		foreach ($desperdicio as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($_SESSION['INGRESO']['CTA_DESPERDICIO']);
			$sub = $this->modelo->catalogo_subcuentas($value['Codigo_Dr']);
			$SC = array('benericiario'=>$cuenta[0]['Cuenta'],'ruc'=>'','Codigo'=>$_SESSION['INGRESO']['CTA_DESPERDICIO'],'tipo'=>$cuenta[0]['TC'],'tic'=>1,'sub'=>$value['Codigo_Dr'],'fecha'=>$value['fecha']->format('Y-m-d'),'fac2'=>0,'valorn'=>round($value['TOTAL'],2),'moneda'=>1,'Trans'=>$sub[0]['Detalle'],'T_N'=>60,'t'=>$_SESSION['INGRESO']['CTA_DESPERDICIO']);
			array_push($datos, $SC);
		}
		 // print_r($datos);die();
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
		   $datos[8]['dato']=round($parametro['bajas'],2);
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
		   $datos[14]['dato']=round($parametro['total'],2);
		   $datos[15]['campo']='CANTIDAD';
		   $datos[15]['dato']=$parametro['cant'];
		   $datos[16]['campo']='VALOR_UNIT';
		   $datos[16]['dato']=round($parametro['valor'],2);
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
		   $datos[6]['dato']=round($parametro['bajas'],2);
		   $datos[7]['campo']='Procedencia';
		   $datos[7]['dato']=$parametro['observacion'];
		   $datos[8]['campo']='Fecha_Fab';
		   $datos[8]['dato']=date('Y-m-d');
		   $datos[9]['campo']='Codigo_Dr';
		   $datos[9]['dato']=$parametro['bajas_por'];
		   $datos[10]['campo']='TC';
		   $datos[10]['dato']=$parametro['TC'];
		   $datos[11]['campo']='VALOR_TOTAL';
		   $datos[11]['dato']=round($parametro['total'],2);
		   $datos[12]['campo']='CANTIDAD';
		   $datos[12]['dato']=$parametro['cant'];
		   $datos[13]['campo']='VALOR_UNIT';
		   $datos[13]['dato']=round($parametro['valor'],2);
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
		$pos = $parametro['id_'];
		$resp = $this->modelo->eliminar($codigo,$pos);
		return $resp;
	}

	function reporte_pdf()
	{
		$titulo = 'E N T R E G A  DE  M A T E R I A L E S';
		$sizetable = 8;
		$mostrar = true;
		$resp = $this->modelo->lista_entrega();
		// print_r($resp);die();
		$tablaHTML = array();
		$tablaHTML[0]['medidas']=array(18,23,35,10,35,23,18,28);
		$tablaHTML[0]['alineado']=array('L','L','L','R','L','L','C','L');
		$tablaHTML[0]['datos']=array('FECHA','CODIGO','PRODUCTO','CANT','CENTRO DE COSTOS','RUBRO','BAJAS O DESPER','OBSERVACIONES');
		$tablaHTML[0]['estilo']='BI';
		$tablaHTML[0]['borde'] = '1';
		$pos = 1;
		foreach ($resp as $key => $value) {

			// print_r($value);die();
			$rubro = $this->modelo->listar_rubro($value['SUBCTA']);
			$cc =  $this->modelo->listar_cc_info($value['CTA_INVENTARIO']);
			// print_r($cc);print_r($rubro);die();
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

	function datos_comprobante($codigo)
	{
		$datos_Asi = $this->modelo->datos_comprobante($_POST['fechaC']);
		$debe = 0;
		$haber = 0;
		foreach ($datos_Asi as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
 // print_r($debe."-".$haber); die();
		if($debe==$haber)
		{
			if($debe != 0 && $haber != 0)
			{
			  $datosCom = array('ru'=>$_SESSION['INGRESO']['CodigoU'],'tip'=>'CD','fecha1'=>date('Y-m-d'),'concepto'=>'Salida de inventario por centro de costos '.date('Y-m-d'),'totalh'=>round($haber,2),'num_com'=>$codigo);
		    }else
		    {
		    	$this->modelo->delete_SC_ASientos();
		    	return -2;
		    }
		}else
		{
			$this->modelo->delete_SC_ASientos();
			return -1;
		}

		// print_r($datosCom);die();
		return $datosCom;
	}

function ingresar_trans_kardex_salidas($comprobante,$fechaC)
 {
		$datos_K = $this->modelo->lista_entrega($fechaC);
		$comprobante = explode('.',$comprobante);
		$comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante[1];
		foreach ($datos_K as $key => $value) {
		   $datos_inv = $this->modelo->lista_hijos_id($value['CODIGO_INV']);
		   // print_r($datos_inv.'-'.$datos_inv[0]['id']);die();
		   $cant = explode(',',$datos_inv[0]['id']);			
		    $datos[0]['campo'] ='Codigo_Inv';
		    $datos[0]['dato'] =$value['CODIGO_INV']; 
		    $datos[1]['campo'] ='Fecha';
		    $datos[1]['dato'] =$fechaC; 
		    $datos[2]['campo'] ='Numero';
		    $datos[2]['dato'] =$comprobante;  
		    $datos[3]['campo'] ='T';
		    $datos[3]['dato'] ='N'; 
		    $datos[4]['campo'] ='TP';
		    $datos[4]['dato'] ='CD'; 
		    $datos[5]['campo'] ='Codigo_P';
		    $datos[5]['dato'] =$_SESSION['INGRESO']['CodigoU']; 
		    $datos[6]['campo'] ='Cta_Inv';
		    $datos[6]['dato'] =$value['CTA_INVENTARIO']; 
		    $datos[7]['campo'] ='Contra_Cta';
		    $datos[7]['dato'] =$value['CONTRA_CTA']; 
		    $datos[8]['campo'] ='Periodo';
		    $datos[8]['dato'] =$_SESSION['INGRESO']['periodo']; 
		    $datos[9]['campo'] ='Salida';
		    $datos[9]['dato'] =$value['CANTIDAD']; 
		    $datos[10]['campo'] ='Valor_Unitario';
		    $datos[10]['dato'] =round($value['VALOR_UNIT'],2); 
		    $datos[11]['campo'] ='Valor_Total';
		    $datos[11]['dato'] =round($value['VALOR_TOTAL'],2); 
		    $datos[12]['campo'] ='Costo';
		    $datos[12]['dato'] =round($value['VALOR_UNIT'],2); 
		    $datos[13]['campo'] ='Total';
		    $datos[13]['dato'] =round($value['VALOR_TOTAL'],2);
		    $datos[14]['campo'] ='Existencia';
		    $datos[14]['dato'] =intval($cant[2])-intval($value['CANTIDAD']);
		    $datos[15]['campo'] ='CodigoU';
		    $datos[15]['dato'] =$_SESSION['INGRESO']['CodigoU'];
		    $datos[16]['campo'] ='Item';
		    $datos[16]['dato'] =$_SESSION['INGRESO']['item'];
		    $datos[17]['campo'] ='CodBodega';
		    $datos[17]['dato'] ='01';
		    $this->modelo->insertar_trans_kardex($datos);
		    if($value['Consumos']<>0)
			{
		        $datos1[0]['campo'] ='Codigo_Inv';
		        $datos1[0]['dato'] =$value['CODIGO_INV']; 
		        $datos1[1]['campo'] ='Fecha';
		        $datos1[1]['dato'] =$fechaC; 
		        $datos1[2]['campo'] ='Numero';
		        $datos1[2]['dato'] =$comprobante;  
		        $datos1[3]['campo'] ='T';
		        $datos1[3]['dato'] ='N'; 
		        $datos1[4]['campo'] ='TP';
		        $datos1[4]['dato'] ='CD'; 
		        $datos1[5]['campo'] ='Codigo_P';
		        $datos1[5]['dato'] =$_SESSION['INGRESO']['CodigoU']; 
		        $datos1[6]['campo'] ='Cta_Inv';
		        $datos1[6]['dato'] =$value['CTA_INVENTARIO']; 
		        $datos1[7]['campo'] ='Contra_Cta';
		        $datos1[7]['dato'] =$value['CONTRA_CTA']; 
		        $datos1[8]['campo'] ='Periodo';
		        $datos1[8]['dato'] =$_SESSION['INGRESO']['periodo']; 
		        $datos1[9]['campo'] ='Salida';
		        $datos1[9]['dato'] =$value['Consumos']; 
		        $datos1[10]['campo'] ='Valor_Unitario';
		        $datos1[10]['dato'] =round($value['VALOR_UNIT'],2); 
		        $datos1[11]['campo'] ='Valor_Total';
		        $datos1[11]['dato'] =round($value['Consumos']*$value['VALOR_UNIT'],2); 
		        $datos1[12]['campo'] ='Costo';
		        $datos1[12]['dato'] =round($value['VALOR_UNIT'],2); 
		        $datos1[13]['campo'] ='Total';
		        $datos1[13]['dato'] =round($value['Consumos']*$value['VALOR_UNIT'],2);
		        $datos1[14]['campo'] ='Existencia';
		        $datos1[14]['dato'] =intval($cant[2])-intval($value['Consumos'])-intval($value['CANTIDAD']);  
		        $datos1[15]['campo'] ='CodigoU';
		        $datos1[15]['dato'] =$_SESSION['INGRESO']['CodigoU'];
		        $datos1[16]['campo'] ='Item';
		        $datos1[16]['dato'] =$_SESSION['INGRESO']['item'];
		        $datos1[17]['campo'] ='CodBodega';
		        $datos1[17]['dato'] ='01';
				 $this->modelo->insertar_trans_kardex($datos1);
			}  
		}

	}
function eliminar_asientos_k()
 {
 	$resp = $this->modelo->eliminar_aiseto_K();
 	return $resp;
 }

 function stock_kardex($id)
 {
 	$resp = $this->modelo->stock_kardex($id);
 	// print_r($resp);die();
 	return $resp;
 }
  function costo_venta($id)
 {
 	$resp = $this->modelo->costo_venta($id);
 	// print_r($resp);die();
 	return $resp;
 }
}
?>