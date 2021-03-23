<?php 
include (dirname(__DIR__,2).'/modelo/farmacia/pacienteM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/descargosM.php');
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
    $area = $_POST['area'];
    $nombre = $_POST['nombre'];
    $fecha = $_POST['fecha'];

	echo json_encode($controlador->generar_factura($orden,$ruc,$area,$nombre,$fecha));
}

if(isset($_GET['areas']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->areas($parametros['cod']));
}
if(isset($_GET['edi_proce']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->editar_procedimiento($parametros));
}


if(isset($_GET['num_com']))
{
	$fecha = $_POST['fecha'];
	echo json_encode(numero_comprobante1('Diario',true,false,$fecha));
}


class ingreso_descargosC 
{
	private $modelo;
	private $paciente;
	private $decargos;
	function __construct()
	{
		$this->modelo = new ingreso_descargosM();
		$this->paciente = new pacienteM();
		$this->descargos = new descargosM();
	}
	function buscar_paciente($query)
	{
		// print_r($query);die();
		$parametros = array('tipo'=>'N','query'=>$query,'codigo'=>'');
		$datos = $this->paciente->cargar_paciente($parametros);
		$paciente = array();
		foreach ($datos as $key => $value) {
			// $paciente[]= array('id'=>$value['CI_RUC'],'text'=>$value['Cliente']);
			$paciente[]= array('id'=>$value['CI_RUC'],'text'=>$value['Cliente']);
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
				$costo = 0;
				$exist = 0;
			}else
			{
				      $costo = $costo_venta[0]['Costo'];
				      $exist = $costo_venta[0]['Existencia'];
			}
			if($tipo=='ref')
			{
				$producto[] = array('id'=>$value['Codigo_Inv'].'_'.$costo.'_'.$value['Producto'].'_'.$value['Cta_Inventario'].'_'.$value['TC'].'_'.$value['Cta_Costo_Venta'].'_'.$value['IVA'].'_'.$value['Unidad'].'_'.$exist.'_'.$value['Maximo'].'_'.$value['Minimo'],'text'=>$value['Codigo_Inv']);
			}else
			{
				$producto[] = array('id'=>$value['Codigo_Inv'].'_'.$costo.'_'.$value['Producto'].'_'.$value['Cta_Inventario'].'_'.$value['TC'].'_'.$value['Cta_Costo_Venta'].'_'.$value['IVA'].'_'.$value['Unidad'].'_'.$exist.'_'.$value['Maximo'].'_'.$value['Minimo'],'text'=>$value['Producto']);
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
    	$ordenes = $this->modelo->cargar_pedidos_fecha($parametros['num_ped'],$parametros['area']);
    	$datos1 = $this->modelo->cargar_pedidos($parametros['num_ped'],$parametros['area']);
    	 $neg = false;
    	 $num = 0;
    	 $procedimiento = '';
         $tab='<ul class="nav nav-tabs">';
         $content = '<div class="tab-content">';
    	foreach ($ordenes as $key => $value) {
    		if($value['SUBCTA']!='.')
    		{

    		if($key==0)
    		{

    		    $content.='<input type="hidden" id="txt_f"  value="'.$value['Fecha_Fab']->format('Y-m-d').'"><div id="'.$value['SUBCTA'].'-'.$value['Fecha_Fab']->format('Y-m-d').'" class="tab-pane fade in active">';
    			$tab.='<li class="active"><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['Fecha_Fab']->format('Y-m-d').'">'.'Fecha: '.$value['Fecha_Fab']->format('Y-m-d').'</a></li>';
    		}else
    		{

    		    $content.='<div id="'.$value['SUBCTA'].'-'.$value['Fecha_Fab']->format('Y-m-d').'" class="tab-pane fade">';
    			$tab.='<li><a data-toggle="tab" href="#'.$value['SUBCTA'].'-'.$value['Fecha_Fab']->format('Y-m-d').'">'.'Fecha: '.$value['Fecha_Fab']->format('Y-m-d').'</a></li>';
    		}
    		  $datos =  $this->cargar_pedidos_tab($value['ORDEN'],$value['SUBCTA'],$value['Fecha_Fab']->format('Y-m-d'));

    		  $num = $datos['num_lin'];
    		  if($datos['neg']==true)
    		  {
    		  	$neg = $datos['neg'];
    		  }
    		  $procedimiento = $datos['detalle'];
    		  $ruc = $datos['ruc'];
    		  // print_r($datos['tabla']);die();

    		    $content.= $datos['tabla'];
    		$content.='</div>';
    	  }
    	}
    	$tab.='</ul>';
    	$content.='</div>';
    	$tabs_tabla = $tab.$content;
    	if(!isset($datos1[0]['A_No']))
    	{
    		$datos1[0]['A_No'] = 0;
    	}
    	if(count($ordenes)==0)
    	{
    		$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'neg'=>$neg,'detalle'=>$procedimiento);
			return $tabla;		
    	}

    	$tabla = array('num_lin'=>$datos1[0]['A_No'],'tabla'=> $tabs_tabla,'ruc'=>$ruc,'item'=>$datos1[0]['A_No'],'neg'=>$neg,'detalle'=>$procedimiento);
			// print_r($tabla);die();
		return $tabla;		
    }



	function cargar_pedidos_tab($orden,$SUBCTA,$fecha)
	{
		$datos = $this->modelo->cargar_pedidos($orden,$SUBCTA,$fecha);
		$num = count($datos);

		$tr = '';
		$iva = 0;$subtotal=0;$total=0;
		$negativos = false;
		$procedimiento = '';
		$cabecera = '<table class="table table-hover">
        <thead>
          <th>ITEM</th>
          <th>FECHA</th>
          <th>REFERENCIA</th>
          <th>DESCRIPCION</th>
          <th class="text-right">CANTIDAD</th>
          <th class="text-right">COSTO</th>
          <!-- <th>PVP</th> -->
          <!-- <th>DCTO %</th> -->
          <th class="text-right">IVA</th>
          <th class="text-right">IMPORTE</th>
          <th>Stock(-)</th>
        </thead>
        <tbody id="tbl_body">';
        $pie = ' 
        </tbody>
      </table>';
		foreach ($datos as $key => $value) 
		{
			// print_r($value);die();
			$iva+=number_format($value['IVA'],2);
			// print_r($value['VALOR_UNIT']);
			$sub = $value['VALOR_UNIT']*$value['CANTIDAD'];
			// print_r($sub);die();
			// if(is_float($sub))
			// {
			// 	$subtotal+=number_format($sub,2);
			// }else
			// {
			  $subtotal+=$sub;
			// }

			  $procedimiento=$value['Detalle'];

			$total+=$value['VALOR_TOTAL'];

			$costo =  $this->modelo->costo_venta($value['CODIGO_INV']);
			$nega = 0;
			if(empty($costo))
			{
				$costo[0]['Costo'] = 0;
				$costo[0]['Existencia'] = 0;
			}else
			{
				$exis = number_format($costo[0]['Existencia']-$value['CANTIDAD'],2);
				if($exis<0)
				{
					$nega = $exis;
					$negativos = true;
				}
			}


			// print_r($costo);die();
			$d =  dimenciones_tabl(strlen($value['A_No']));
			$d1 =  dimenciones_tabl(strlen($value['Fecha_Fab']->format('Y-m-d')));
			$d2 =  dimenciones_tabl(strlen($value['CODIGO_INV']));
			$d3 =  dimenciones_tabl(strlen($value['PRODUCTO']));
			$d4 =  dimenciones_tabl(strlen($value['CANTIDAD']));
			$d5 =  dimenciones_tabl(strlen($value['VALOR_UNIT']));
			$d6 =  dimenciones_tabl(strlen($value['P_DESC']));
			$d7 =  dimenciones_tabl(strlen($value['VALOR_TOTAL']));
			$tr.='<tr>
  					<td width="'.$d.'">'.$value['A_No'].'</td>
  					<td width="'.$d1.'">'.$value['Fecha_Fab']->format('Y-m-d').'</td>
  					<td width="'.$d2.'">'.$value['CODIGO_INV'].'</td>
  					<td width="'.$d3.'">'.$value['PRODUCTO'].'</td>
  					<td width="'.$d4.'" class="text-right">
  					     <input type="text" class=" text-right form-control input-sm" id="txt_can_lin_'.$value['A_No'].'" value="'.$value['CANTIDAD'].'" onblur="calcular_totales(\''.$value['A_No'].'\');"/>
  					</td>
  					<td width="'.$d5.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['A_No'].'\');" class="text-right form-control input-sm" id="txt_pre_lin_'.$value['A_No'].'" value="'.number_format($costo[0]['Costo'],2).'" readonly=""/>
  					</td>
  					<!--<td width="'.$d6.'">
  					     <input type="text" onblur="validar_pvp_costo(\''.$value['A_No'].'\');calcular_totales(\''.$value['A_No'].'\');" class="text-right form-control input-sm" id="txt_uti_lin_'.$value['A_No'].'" value="'.number_format($value['VALOR_UNIT'],2).'" />
  					</td>
  					 <td width="'.$d6.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['A_No'].'\');" class="text-right form-control input-sm" id="txt_des_lin_'.$value['A_No'].'" value="'.number_format($value['P_DESC'],2).'" readonly=""/>
  					</td>-->
  					<td width="'.$d6.'">
  					     <input type="text" onblur="calcular_totales(\''.$value['A_No'].'\');" class="text-right form-control input-sm" id="txt_iva_lin_'.$value['A_No'].'" value="'.number_format($value['IVA'],4).'" readonly=""/>
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="text-right form-control input-sm" id="txt_tot_lin_'.$value['A_No'].'" value="'.number_format($value['VALOR_TOTAL'],4).'" readonly="" />
  					</td>
  					<td width="'.$d7.'">
  					     <input type="text" class="form-control input-sm" id="txt_negarivo_'.$value['A_No'].'" value="'.$nega.'" readonly="" />
  					</td>
  					<td width="90px">
  						<button class="btn btn-sm btn-primary" onclick="editar_lin(\''.$value['A_No'].'\')" title="Editar paciente"><span class="glyphicon glyphicon-floppy-disk"></span></button> 
  						<button class="btn btn-sm btn-danger" title="Eliminar paciente"  onclick="eliminar_lin(\''.$value['A_No'].'\')" ><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>';
			
		}
		$tr.='<tr><td colspan="2"><button type="button" class="btn btn-primary" onclick="generar_factura(\''.$fecha.'\')" id="btn_comprobante"><i class="fa fa-file-text-o"></i> Gernerar comprobante</button></td><td colspan="4"></td><td class="text-right">Total:</td><td><input type="text" class="form-control input-sm" value="'.$subtotal.'"></td><td colspan="2"></td></tr>';
		// print_r($tr);die();
		if($num!=0)
		{
			// print_r($tr);die();
			$tabla = array('num_lin'=>$num,'tabla'=>$cabecera.$tr.$pie,'ruc'=>$datos[0]['Codigo_B'],'item'=>$datos[0]['A_No'],'subtotal'=>$subtotal,'iva'=>$iva,'total'=>$total+$iva,'neg'=>$negativos,'detalle'=>$procedimiento);	
			return $tabla;		
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>','item'=>0,'subtotal'=>$subtotal,'iva'=>$iva,'total'=>$total+$iva,'neg'=>$negativos,'detalle'=>$procedimiento);
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

		   $datos[19]['campo']='Detalle';
		   $datos[19]['dato']=$parametro['pro'];
		   if($parametro['iva']!=0)
		   {
		   	   // $datos[19]['campo']='IVA';
		       // $datos[19]['dato']=(round($parametro['total'],2)*1.12)-round($parametro['total'],2);
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
	function generar_factura($orden,$ruc,$area,$nombre,$fecha)
	{
		// if($this->modelo->misma_fecha($orden,$ruc)==-1)
		// {
		// 	return array('resp'=>-3,'com'=>'');
		// }
		$asientos_SC = $this->modelo->datos_asiento_SC($orden,$fecha);

		// print_r($asientos_SC);die();

		$parametros_debe = array();
		$parametros_haber = array();
		// $fecha = date('Y-m-d');

		// print_r($asientos_SC);die();
		foreach ($asientos_SC as $key => $value) {
			 $cuenta = $this->modelo->catalogo_cuentas($value['CONTRA_CTA']);
			 $sub = $this->modelo->catalogo_subcuentas($value['SUBCTA']);
			$parametros = array(
                    'be'=>$cuenta[0]['Cuenta'],
                    'ru'=> '',
                    'co'=> $value['CONTRA_CTA'],// codigo de cuenta cc
                    'tip'=>$cuenta[0]['TC'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
                    'tic'=> 1, //debito o credito (1 o 2);
                    'sub'=> $value['SUBCTA'], //Codigo se trae catalogo subcuenta
                    'sub2'=>$cuenta[0]['Cuenta'],//nombre del beneficiario
                    'fecha_sc'=> $value['Fecha_Fab']->format('Y-m-d'), //fecha 
                    'fac2'=>0,
                    'mes'=> 0,
                    'valorn'=> round($value['total'],2),//valor de sub cuenta 
                    'moneda'=> 1, /// moneda 1
                    'Trans'=>$sub[0]['Detalle'],//detalle que se trae del asiento
                    'T_N'=> '99',
                    't'=> $cuenta[0]['TC'],                        
                  );
                  $this->modelo->generar_asientos_SC($parametros);
		}

		//asientos para el debe
		$asiento_debe = $this->modelo->datos_asiento_debe($orden,$fecha);
		$fecha = $asiento_debe[0]['fecha']->format('Y-m-d');
		
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

        // asiento para el haber
		$asiento_haber  = $this->modelo->datos_asiento_haber($orden,$fecha);
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

		// print_r($fecha.'-'.$nombre.'-'.$ruc);die();
		// $parametros = array('tip'=> 'CD','fecha'=>$fecha);
	 //    $num_comprobante = $this->modelo->numero_comprobante($parametros);

		// print_r($fecha);die();
	    $num_comprobante = numero_comprobante1('Diario',true,true,$fecha);
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
        	        "fecha1"=> $fecha,// fecha actual 2020-09-21
        	        'concepto'=>'Salida de inventario para '.$nombre.' con CI: '.$ruc.' el dia '.$fecha, //detalle de la transaccion realida
        	        'totalh'=> round($haber,2), //total del haber
        	        'num_com'=> '.'.date('Y', strtotime($fecha)).'-'.$num_comprobante, // codigo de comprobante de esta forma 2019-9000002
        	        );
				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();
                $resp = $this->modelo->generar_comprobantes($parametro_comprobante);
                // $cod = explode('-',$num_comprobante);
                if($resp==$num_comprobante)
                {
                	if($this->ingresar_trans_kardex_salidas($orden,$num_comprobante,$fecha,$area,$ruc,$nombre)==1)
                	{

                		$resp = $this->modelo->eliminar_aiseto_K($orden,$fecha);
                		if($resp==1)
                		{
                			return array('resp'=>1,'com'=>$num_comprobante);
                		}else
                		{
                			return array('resp'=>-1,'com'=>'No se pudo eliminar asiento_K');
                		}
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

	function ingresar_trans_kardex_salidas($orden,$comprobante,$fechaC,$area,$ruc,$nombre)
    {
		$datos_K = $this->modelo->cargar_pedidos($orden,$area,$fechaC);
		// $comprobante = explode('.',$comprobante);
		// $comprobante = explode('-',trim($comprobante[1]));
		$comprobante = $comprobante;
		$resp = 1;
		foreach ($datos_K as $key => $value) {
		   $datos_inv = $this->modelo->lista_hijos_id($value['CODIGO_INV']);
		   // print_r($datos_inv.'-'.$datos_inv[0]['id']);die();
		    $cant[2] = 0;
		   if(count($datos_inv)>0)
		   {
		   	 $cant = explode(',',$datos_inv[0]['id']);
		   }
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
		    $datos[5]['dato'] =$ruc; 
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
		    $datos[14]['dato'] =round(($cant[2]),2)-round(($value['CANTIDAD']),2);
		    $datos[15]['campo'] ='CodigoU';
		    $datos[15]['dato'] =$_SESSION['INGRESO']['CodigoU'];
		    $datos[16]['campo'] ='Item';
		    $datos[16]['dato'] =$_SESSION['INGRESO']['item'];
		    $datos[17]['campo'] ='CodBodega';
		    $datos[17]['dato'] ='01';
		    $datos[18]['campo'] ='CodigoL';
		    $datos[18]['dato'] =$value['SUBCTA'];

		    $datos[19]['campo'] ='Detalle';
		    $datos[19]['dato'] ='Salida de inventario para '.$nombre.' con CI: '.$ruc.' el dia '.$fechaC;
		    $datos[20]['campo'] ='Orden_No';
		    $datos[20]['dato'] =$orden;
		     if($this->modelo->insertar_trans_kardex($datos)!="")
		     {
		     	$resp = 0;
		     } 
	}
	return $resp;

}

function areas($codigo)
{
	$resp = $this->descargos->area_descargo(false,$codigo);
	return $resp;
}
function editar_procedimiento($parametros)
{
	    $campoWhere[0]['campo']='ORDEN';
		$campoWhere[0]['valor']=$parametros['ped'];

		$datos[0]['campo']='Detalle';
		$datos[0]['dato']=$parametros['text'];

		  return update_generico($datos,'Asiento_K',$campoWhere);
}

}
?>