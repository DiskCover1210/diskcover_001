<?php 
include (dirname(__DIR__,2).'/modelo/farmacia/pacienteM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
// $_SESSION['INGRESO']['modulo_']='99';

/**
 * 
 */
$controlador = new ingreso_descargosC();
if(isset($_GET['paciente']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_paciente($query));
}

if(isset($_GET['producto']))
{
	$tipo = $_GET['tipo'];
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_producto($query,$tipo));
}

if(isset($_GET['cc']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_cc($query));
}

if(isset($_GET['subcuenta']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->buscar_subcuenta($query));
}

if(isset($_GET['guardar']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->guardar_entrega($parametros));
}

if(isset($_GET['pedido']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_pedidos($parametros));
}
if(isset($_GET['lin_edi']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_edi($parametros));
}
if(isset($_GET['lin_eli']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->lineas_eli($parametros));
}
if(isset($_GET['facturar']))
{
	$orden = $_POST['orden'];
    $ruc = $_POST['ruc'];
	echo json_encode($controlador->generar_factura($orden,$ruc));
}


class ingreso_descargosC 
{
	private $modelo;
	private $paciente;
	function __construct()
	{
		$this->modelo = new ingreso_descargosM();
		$this->paciente = new pacienteM();
	}
	function buscar_paciente($query)
	{
		// print_r($query);die();
		$parametros = array('tipo'=>'N','query'=>$query,'codigo'=>'');
		$datos = $this->paciente->cargar_paciente($parametros);
		$paciente = array();
		foreach ($datos as $key => $value) {
			// $paciente[]= array('id'=>$value['CI_RUC'],'text'=>$value['Cliente']);
			$paciente[]= array('id'=>$value['CI_RUC'],'text'=>utf8_encode($value['Cliente']));
		}
		// print_r($paciente);die();
		return $paciente;
	}

	function buscar_producto($query,$tipo)
	{
		// print_r($tipo);die();

		$producto = array();
		if($query!='')
		{
			$datos = $this->modelo->buscar_producto($query,$tipo);
		
		foreach ($datos as $key => $value) {

			$costo_venta = $this->modelo->costo_venta($value['Codigo_Inv']);
			// print_r($costo_venta);die();
			if(empty($costo_venta))
			{
				$costo_venta = 0;
			}else
			{
				$costo_venta = $costo_venta[0]['Costo'];
			}
			if($tipo=='ref')
			{
				$producto[] = array('id'=>$value['Codigo_Inv'].'-'.$costo_venta.'-'.utf8_encode($value['Producto']).'-'.$value['Cta_Inventario'].'-'.$value['TC'].'-'.$value['Cta_Costo_Venta'].'-'.$value['IVA'],'text'=>$value['Codigo_Inv']);
			}else
			{
				$producto[] = array('id'=>$value['Codigo_Inv'].'-'.$costo_venta.'-'.$value['Cta_Inventario'].'-'.$value['TC'].'-'.$value['Cta_Costo_Venta'].'-'.$value['IVA'],'text'=>utf8_encode($value['Producto']));
			}
		}
	  }
	  // print_r($producto);die();
		return $producto;
	}

	function buscar_cc($query)
	{
		$cc = $this->modelo->buscar_cc($query);
		$datos =  array();
		foreach ($cc as $key => $value) {
			$datos[] = array('id'=>$value['Codigo'].'-'.$value['TC'],'text'=>$value['Cuenta']);
		}
		// print_r($datos);die();
		return $datos;
	}


	function cargar_pedidos($parametros)
	{
		$datos = $this->modelo->cargar_pedidos($parametros['num_ped']);
		$num = count($datos);

		$tr = '';
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();
			$costo =  $this->modelo->costo_venta($value['CODIGO_INV']);
			if(empty($costo))
			{
				$costo[0]['Costo'] = 0;
			}

			// print_r($costo);die();
			$d =  dimenciones_tabl(strlen($value['A_No']));
			$d2 =  dimenciones_tabl(strlen($value['CODIGO_INV']));
			$d3 =  dimenciones_tabl(strlen($value['PRODUCTO']));
			$d4 =  dimenciones_tabl(strlen($value['CANTIDAD']));
			$d5 =  dimenciones_tabl(strlen($value['VALOR_UNIT']));
			$d6 =  dimenciones_tabl(strlen($value['P_DESC']));
			$d7 =  dimenciones_tabl(strlen($value['VALOR_TOTAL']));
			$tr.='<tr>
  					<td width="'.$d.'">'.$value['A_No'].'</td>
  					<td width="'.$d2.'">'.$value['CODIGO_INV'].'</td>
  					<td width="'.$d3.'">'.$value['PRODUCTO'].'</td>
  					<td width="'.$d4.'">
  					     <input type="text" class="form-control input-sm" id="txt_can_lin_'.$value['A_No'].'" value="'.$value['CANTIDAD'].'" onblur="calcular_totales(\''.$value['A_No'].'\');descuentos();"/>
  					</td>
  					<td width="'.$d5.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['A_No'].'\');descuentos();" class="form-control input-sm" id="txt_pre_lin_'.$value['A_No'].'" value="'.number_format($costo[0]['Costo'],2).'" readonly=""/>
  					</td>
  					<td width="'.$d6.'">
  					     <input type="text" onblur="validar_pvp_costo(\''.$value['A_No'].'\');calcular_totales(\''.$value['A_No'].'\');descuentos();" class="form-control input-sm" id="txt_uti_lin_'.$value['A_No'].'" value="'.number_format($value['VALOR_UNIT'],2).'" />
  					</td>
  					<td width="'.$d6.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['A_No'].'\');descuentos();" class="form-control input-sm" id="txt_des_lin_'.$value['A_No'].'" value="'.number_format($value['P_DESC'],2).'" readonly=""/>
  					</td>
  					<td width="'.$d6.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['A_No'].'\');descuentos();" class="form-control input-sm" id="txt_iva_lin_'.$value['A_No'].'" value="'.number_format($value['IVA'],4).'" readonly=""/>
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="form-control input-sm" id="txt_tot_lin_'.$value['A_No'].'" value="'.number_format($value['VALOR_TOTAL'],4).'" readonly="" />
  					</td>
  					<td width="90px">
  						<button class="btn btn-sm btn-primary" onclick="editar_lin(\''.$value['A_No'].'\')" title="Editar paciente"><span class="glyphicon glyphicon-floppy-disk"></span></button> 
  						<button class="btn btn-sm btn-danger" title="Eliminar paciente"  onclick="eliminar_lin(\''.$value['A_No'].'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		if($num!=0)
		{
			// print_r($num);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$tr,'ruc'=>$datos[0]['Codigo_B'],'item'=>$datos[0]['A_No']);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0);
			return $tabla;		
		}
		

	}

	function guardar_entrega($parametro)
	{
		// print_r($parametro);die();
		$num_ped =$parametro['num_ped'];
		if($num_ped=='')
		{
			$num_ped = $this->modelo->asignar_num_pedido_clinica();
			$revisar = $this->modelo->COD_PEDIDO_CLINICA_EXISTENTE($num_ped);
			while ($revisar == -1) {
				$num_ped+=1;
				$revisar = $this->modelo->COD_PEDIDO_CLINICA_EXISTENTE($num_ped);			
			}
		}

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
		   $datos[8]['campo']='ORDEN';
		   $datos[8]['dato']=$num_ped;
		   $datos[9]['campo']='A_No';
		   $datos[9]['dato']=$parametro['id']+1;
		   $datos[10]['campo']='Fecha_Fab';
		   $datos[10]['dato']=date('Y-m-d',strtotime($parametro['fecha']));
		   $datos[11]['campo']='TC';
		   $datos[11]['dato']=$parametro['TC'];
		   $datos[12]['campo']='VALOR_TOTAL';
		   $datos[12]['dato']=round($parametro['total'],2);
		   $datos[13]['campo']='CANTIDAD';
		   $datos[13]['dato']=$parametro['cant'];
		   $datos[14]['campo']='VALOR_UNIT';
		   $datos[14]['dato']=round($parametro['valor'],2);
		   $datos[15]['campo']='DH';
		   $datos[15]['dato']=2;
		   $datos[16]['campo']='CONTRA_CTA';
		   $datos[16]['dato']=$parametro['cc'];
		   $datos[17]['campo']='P_DESC';
		   $datos[17]['dato']=$parametro['descuento'];
		   $datos[18]['campo']='Codigo_B';
		   $datos[18]['dato']=$parametro['ci'];
		   if($parametro['iva']!=0)
		   {
		   	   $datos[19]['campo']='IVA';
		       $datos[19]['dato']=(round($parametro['total'],2)*1.12)-round($parametro['total'],2);
		   }
		   $resp = $this->modelo->ingresar_asiento_K($datos);
		   $num = $num_ped;
		   return  $respuesta = array('ped'=>$num,'resp'=>$resp);
	
	    // print_r($resp);die();
		
	}
	function lista_entrega()
	{
		$resp = $this->modelo->lista_entrega();
		$resp= array_map(array($this, 'encode'), $resp);
		// print_r($resp);die();
		return $resp;
	}

	function lineas_eli($parametros)
	{
		$resp = $this->modelo->lineas_eli($parametros);
		return $resp;

	}

	function lineas_edi($parametros)
	{

			$datos[0]['campo']='CANTIDAD';
		    $datos[0]['dato']=$parametros['can'];
			$datos[1]['campo']='VALOR_UNIT';
		    $datos[1]['dato']=$parametros['pre'];
			$datos[2]['campo']='P_DESC';
		    $datos[2]['dato']=$parametros['des'];
			$datos[3]['campo']='VALOR_TOTAL';
		    $datos[3]['dato']=$parametros['tot'];

		    $campoWhere[0]['campo']='A_No';
		    $campoWhere[0]['valor']=$parametros['lin'];
		    $campoWhere[1]['campo']='ORDEN';
		    $campoWhere[1]['valor']=$parametros['ped'];

		$resp = $this->modelo->lineas_edi($datos,$campoWhere);
		return $resp;
		
	}

	function cargar_ficha($parametros)
	{
		$datos = $this->paciente->cargar_paciente($parametros);
		print_r($datos);
	}
	function generar_factura($orden,$ruc)
	{
		$asiento_haber  = $this->modelo->datos_asiento_haber($orden);
		$asiento_debe = $this->modelo->datos_asiento_debe($orden);
		$fecha = $asiento_debe[0]['fecha']->format('Y-m-d');
		$parametros_debe = array();
		$parametros_haber = array();

		//asientos para el debe
		foreach ($asiento_debe as $key => $value) 
		{
			// print_r($value);die();
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);		
				$parametros_debe = array(
				 "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 1,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '99',
			);
				 $this->modelo->ingresar_asientos($parametros_debe);
		}

		foreach ($asiento_haber as $key => $value) {
			$cuenta = $this->modelo->catalogo_cuentas($value['cuenta']);			
				$parametros_haber = array(
                  "va" =>round($value['total'],2),//valor que se trae del otal sumado
                  "dconcepto1" =>$cuenta[0]['Cuenta'],
                  "codigo" => $value['cuenta'], // cuenta de codigo de 
                  "cuenta" => $cuenta[0]['Cuenta'], // detalle de cuenta;
                  "efectivo_as" =>$value['fecha']->format('Y-m-d'), // observacion si TC de catalogo de cuenta
                  "chq_as" => 0,
                  "moneda" => 1,
                  "tipo_cue" => 2,
                  "cotizacion" => 0,
                  "con" => 0,// depende de moneda
                  "t_no" => '99',
                );
                $this->modelo->ingresar_asientos($parametros_haber);
		}
		$parametros = array('tip'=> 'CD','fecha'=>$fecha);
	    $num_comprobante = $this->modelo->numero_comprobante($parametros);
	    $dat_comprobantes = $this->modelo->datos_comprobante();
	    $debe = 0;
		$haber = 0;
		foreach ($dat_comprobantes as $key => $value) {
			$debe+=$value['DEBE'];
			$haber+=$value['HABER'];
		}
		if(strval($debe)==strval($haber))
		{
			if($debe !=0 && $haber!=0)
			{
				 $parametro_comprobante = array(
        	        'ru'=> $ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
        	        'tip'=>'CD',//tipo de cuenta contable cd, etc
        	        "fecha1"=> date('Y-m-d'),// fecha actual 2020-09-21
        	        'concepto'=>'Salida de inventario por centro de costos '.date('Y-m-d'), //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=> $num_comprobante, // codigo de comprobante de esta forma 2019-9000002
        	        );

                $resp = $this->modelo->generar_comprobantes($parametro_comprobante);
                $cod = explode('-',$num_comprobante);
                if($resp==$cod[1])
                {
                	if($this->ingresar_trans_kardex_salidas($orden,$num_comprobante,$fecha)==1)
                	{
                		return array('resp'=>1,'com'=>$resp);
                	}else
                	{
                		return array('resp'=>-1,'com'=>'Uno o todos No se pudo registrar en Trans_Kardex');
                	}
                }else
                {
        	        return array('resp'=>-1,'com'=>$resp);
                }

			}else
			{
				// print_r($debe."-".$haber); 
				 return array('resp'=>-1,'com'=>'Los resultados son 0');

			}
		}else
		{
			return array('resp'=>-1,'com'=>'No coinciden');

		}

	}

	function ingresar_trans_kardex_salidas($orden,$comprobante,$fechaC)
    {
		$datos_K = $this->modelo->cargar_pedidos($orden);
		$comprobante = explode('.',$comprobante);
		$comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante[1];
		$resp = 1;
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
		    $datos[18]['campo'] ='CodigoL';
		    $datos[18]['dato'] =$value['SUBCTA'];
		     if($this->modelo->insertar_trans_kardex($datos)!="")
		     {
		     	$resp = 0;
		     } 
	}
	return $resp;

}



}
?>