<?php 
include 'pacienteC.php'; 
include (dirname(__DIR__,2).'/modelo/farmacia/devoluciones_insumosM.php');
include (dirname(__DIR__,2).'/modelo/farmacia/ingreso_descargosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new devoluciones_insumosC();
if(isset($_GET['cargar_pedidos']))
{
	$parametros = $_POST['parametros'];
	$paginacion = $_POST['paginacion'];
	echo json_encode($controlador->cargar_pedidos($parametros,$paginacion));
}
if(isset($_GET['tabla_detalles']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->tabla_detalles($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}

if(isset($_GET['formatoEgreso']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}

if(isset($_GET['imprimir_excel']))
{   
	$parametros= $_GET;
	$controlador->imprimir_excel($parametros);	
}
if(isset($_GET['Ver_comprobante']))
{   
	$parametros= $_GET['comprobante'];
	$controlador->ver_comprobante($parametros);	
}

if(isset($_GET['datos_comprobante']))
{   
	$comprobante= $_POST['comprobante'];
	$query= $_POST['query'];
	echo json_encode($controlador->datos_comprobante($comprobante,$query));	
}
if(isset($_GET['costo']))
{   
	$parametros= $_POST['codigo'];
	echo json_encode($controlador->costo($parametros));	
}

if(isset($_GET['guardar_devolucion']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->guardar_devoluciones($parametros));	
}
if(isset($_GET['lista_devolucion']))
{   
	$comprobante= $_POST['comprobante'];
	echo json_encode($controlador->lista_devoluciones($comprobante));	
}
if(isset($_GET['eliminar_linea_dev']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->eliminar_linea_devo($parametros));	
}



class devoluciones_insumosC 
{
	private $modelo;
	private $paciente;
	private $descargos;
	function __construct()
	{
		$this->modelo = new devoluciones_insumosM();
		$this->paciente = new pacienteC();
		$this->pdf = new cabecera_pdf();
		$this->descargos = new ingreso_descargosM();
	}

	

	function cargar_pedidos($parametros,$paginacion)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_comprobantes($parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe'],$paginacion);
		return $tabla = array('num_lin'=>0,'tabla'=>$datos);

	}

	// function imprimir_pdf($parametros)
 //    {
 //    	// print_r($parametros['txt_desde']);die();
 //  	    $desde = str_replace('-','',$parametros['txt_desde']);
	// 	$hasta = str_replace('-','',$parametros['txt_hasta']);

	// 	$datos = $this->modelo->cargar_comprobantes_datos($parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);


	// 	$titulo = 'CARGO DE INSUMOS Y MEDICAMENTOS DE PACIENTE PRIVADO';
	// 	$sizetable =7;
	// 	$mostrar = TRUE;
	// 	$Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
	// 	$Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
	// 	$tablaHTML= array();
		
	// 	$medidas =array(17,52,17,70,17,17);
	// 	$alineado = array('L','L','L','L','L','L');
	// 	$pos = 0;
	// 	$borde = 1;
	// 	// print_r($datos);die();
	// 	$gran_total = 0;
	// 	foreach ($datos as $key => $value){
	// 		$tablaHTML[$pos]['medidas']=$medidas;
	// 	    $tablaHTML[$pos]['alineado']=$alineado;
	// 	    $tablaHTML[$pos]['datos']=array('');
	// 	    // $tablaHTML[$pos]['borde'] =$borde;
	// 	    $pos+=1;
	// 		$tablaHTML[$pos]['medidas']=$medidas;
	// 	    $tablaHTML[$pos]['alineado']=$alineado;
	// 	    // print_r($value);die();
	// 	    $tablaHTML[$pos]['datos']=array('<b>Paciente:',$value['Cliente'],'<b>Detalle:',$value['Concepto'],'<b>No.Comp:',$value['Numero']);
	// 	    $tablaHTML[$pos]['borde'] =$borde;

	// 	    if($parametros['txt_tipo_filtro']=='f')
	// 	    {
	// 	    	     $lineas = $this->modelo->trans_kardex($value['Numero']);
	// 	    		 $pos+=1;
	// 	    		 $tablaHTML[$pos]['medidas']=array(39,85,16,25,25);
	// 	             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	// 	             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
	// 	             $tablaHTML[$pos]['borde'] =$borde;

	// 	             $total = 0;
	// 	    		 foreach ($lineas as $key => $value2) {
	// 	    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
	// 	    		 	$pos+=1;
	// 	    		    $tablaHTML[$pos]['medidas']=array(39,85,16,25,25);
	// 	                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	// 	                $tablaHTML[$pos]['datos']=array($value2['Codigo_Inv'],$pro[0]['Producto'],$value2['Salida'],$value2['Valor_Unitario'],$value2['Valor_Total']);
	// 	                $tablaHTML[$pos]['borde'] =$borde;
	// 	                $total+=$value2['Valor_Total'];
	// 	    		 }
	// 	    		 $pos+=1;
	// 	    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
	// 	                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	// 	                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
	// 	                $tablaHTML[$pos]['borde'] =$borde;
	// 	                $gran_total+=$total;
	// 	    	     $pos+=1;		    		
		    	
	// 	    }else
	// 	    {
	// 	    	 $lineas = $this->modelo->trans_kardex($value['Numero']);
	// 	    		 $pos+=1;
	// 	    		 $tablaHTML[$pos]['medidas']=array(39,85,16,25,25);
	// 	             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	// 	             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
	// 	             $tablaHTML[$pos]['borde'] =$borde;

	// 	             $total = 0;
	// 	    		 foreach ($lineas as $key => $value2) {
	// 	    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
	// 	    		 	$pos+=1;
	// 	    		    $tablaHTML[$pos]['medidas']=array(39,85,16,25,25);
	// 	                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	// 	                $tablaHTML[$pos]['datos']=array($value2['Codigo_Inv'],$pro[0]['Producto'],$value2['Salida'],$value2['Valor_Unitario'],$value2['Valor_Total']);
	// 	                $tablaHTML[$pos]['borde'] =$borde;
	// 	                $total+=$value2['Valor_Total'];
	// 	    		 }
	// 	    		 $pos+=1;
	// 	    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
	// 	                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	// 	                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
	// 	                $tablaHTML[$pos]['borde'] =$borde;
	// 	                $gran_total+=$total;
	// 	    	     $pos+=1;		
	// 	    }

	// 		// foreach ($lineas as $key => $value) {
	// 		// 	$lin+=1;
	// 		// }
	// 		$pos+=1;
			
	// 	}
	// 	 $tablaHTML[$pos]['medidas']=array(39,100,13,21,18);
	// 	 $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	// 	 $tablaHTML[$pos]['datos']=array('','','','<b>GRAN TOTAL ',$gran_total);
	// 	 $tablaHTML[$pos]['borde'] =array('T');


	// 	if($parametros['txt_tipo_filtro']=='f')
	// 	{
	// 		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,$sizetable,$mostrar,25,'P');
	// 	}else
	// 	{
	// 		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,false,false,$sizetable,$mostrar,25,'P');
		
	// 	}

 //  }

  // function tabla_detalles($parametros)
  // {
  // 	// print_r($parametros);die();
  // 	    $desde = str_replace('-','',$parametros['desde']);
		// $hasta = str_replace('-','',$parametros['hasta']);
		
		// $datos = $this->modelo->cargar_comprobantes($parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);

  //        $html = '';
		// foreach ($datos as $key => $value){

		// 	$html.='<tr style="background: skyblue;">
  //               <td colspan="2"><b>NOMBRE: </b> '.$value['Cliente'].'</td>
  //               <td><b>DETALLE: </b> '.$value['Concepto'].'</td>
  //               <td><b>FECHA: </b> '.$value['Fecha']->format('Y-m-d').'</td>
  //               <td><b>No. Compro.</b>'.$value['Numero'].'</td>                
  //             </tr>';
		//     if($parametros['busfe']=='f')
		//     {
		//     		$html.='<tr  style="background: skyblue;">
                              
  //                          </tr>
  //                          <tr>
  //                            <td><b>CODIGO</b></td>
  //                            <td><b>PRODUCTO</b></td>
  //                            <td><b>CANTIDAD</b></td>
  //                            <td><b>VALOR UNI</b></td>
  //                            <td><b>VALOR TOTAL</b></td>
  //                          </tr>';
		//              $total = 0;

		//     		$lineas = $this->modelo->trans_kardex($value['Numero']);
		//     		 foreach ($lineas as $key => $value2) {
		//     		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		//     		 $html.='<tr>
  //                            <td>'.$value2['Codigo_Inv'].'</td>
  //                            <td>'.$pro[0]['Producto'].'</td>
  //                            <td>'.$value2['Salida'].'</td>
  //                            <td>'.$value2['Valor_Unitario'].'</td>
  //                            <td>'.$value2['Valor_Total'].'</td>
  //                          </tr>';
		//                 $total+=$value2['Valor_Total'];
		//     		 }
		//     		$html.='<tr>
		//     		          <td colspan="3"></td>
  //                             <td><b>TOTAL</b></td>
  //                             <td><b>'.$total.'</b></td>
  //                          </tr>';
		    	
		//     }else
		//     {
		//     	    $total = 0;

		//     		$lineas = $this->modelo->trans_kardex($value['Numero']);
		//     		 foreach ($lineas as $key => $value2) {
		//     		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		//     		 $html.='<tr>
  //                            <td>'.$value2['Codigo_Inv'].'</td>
  //                            <td>'.$pro[0]['Producto'].'</td>
  //                            <td>'.$value2['Salida'].'</td>
  //                            <td>'.$value2['Valor_Unitario'].'</td>
  //                            <td>'.$value2['Valor_Total'].'</td>
  //                          </tr>';
		//                 $total+=$value2['Valor_Total'];
		//     		 }
		//     		$html.='<tr>
		//     		          <td colspan="3"></td>
  //                             <td><b>TOTAL</b></td>
  //                             <td><b>'.$total.'</b></td>
  //                          </tr>';
		    	
		//     }
		// }

		// // print_r($html);die();

		// return $html;

  // }

 //  function imprimir_excel($parametros)
	// {
	//  $_SESSION['INGRESO']['ti']='DESCARGOS REALIZADOS';
	//  $Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
 //     $Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		
	//  $datos = $datos = $this->modelo->cargar_comprobantes_datos($parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);

	//  $registros = array();
	//  $reg_lineas = array();
	//  foreach ($datos as $key => $value){
	//  	    // print_r($value);die();
	// 		$registros[] = array('Nombre'=>$value['Cliente'],'fecha'=>$value['Fecha']->format('Y-m-d'),'Concepto'=>$value['Concepto'],'comprobante'=>$value['Numero'],'registros'=>array());
	// 	             $lineas = $this->modelo->trans_kardex($value['Numero']);
	// 	    		 // $pos+=1;
	// 	    		 // $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
	// 	       //       $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	// 	       //       $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
	// 	       //       $tablaHTML[$pos]['borde'] =$borde;

	// 	             // $total = 0;
	// 	    		 foreach ($lineas as $key2 => $value2) {
	// 	    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);

	// 	    		 	$reg_lineas[] = array('codigo'=>$value2['Codigo_Inv'],'cantidad'=>$value2['Salida'],'producto'=>$pro[0]['Producto'],'pre_uni'=>$value2['Valor_Unitario'],'total'=>$value2['Valor_Total']);
	// 	    		 	// $total+=$value2['Valor_Total'];
	// 	    		 }
	// 	    		  array_push($registros[$key]['registros'],$reg_lineas);
	// 	    	      $reg_lineas=array();
	// 	}

	// 	// print_r($registros);die();


	//  $this->modelo->imprimir_excel($registros);
	// }

	// function  ver_comprobante($comprobante)
	// {
	// 	$datos = $this->modelo->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
	// 	$lineas = $this->modelo->lineas_trans_kardex($comprobante);
	// 	// print_r($datos);print_r($lineas); die();
	// 	$titulo = 'CARGO DE INSUMOS Y MEDICAMENTOS DE PACIENTE PRIVADO';
	// 	$mostrar = '1';
	// 	$sizetable = 8;
	// 	$tablaHTML = array();

	// 	$tablaHTML[0]['medidas']= array(17,52,17,70,17,17);
 //    $tablaHTML[0]['alineado']=array('L','L','L','L','L');
 //    $tablaHTML[0]['datos']=array('<b>Paciente:',$datos[0]['Cliente'],'<b>Detalle:',$datos[0]['Concepto'],'<b>No.Comp:',$datos[0]['Numero']);
 //    $tablaHTML[0]['borde'] ='1';

 //    $tablaHTML[1]['medidas']=array(39,83,18,25,25);
 //    $tablaHTML[1]['alineado']=array('L','L','R','R','R');
 //    $tablaHTML[1]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>PRECIO UNI','<b>PRECIO TOTAL');
 //    $tablaHTML[1]['borde'] =1;

 //    $pos=2;
 //    $total =0;
 //    $familias = $this->modelo->familias($comprobante);
 //    $reg = count($familias);

 //    foreach ($familias as $key1 => $value1) {
	//     foreach ($lineas as $key => $value) {
	//     	if($value1['familia']==substr($value['Codigo_Inv'],0,5))
	//     	{

	//     		// print_r($value1['familia']);print_r(substr($value['Codigo_Inv'],0,5));die();
	//     	  $uti = $value['Utilidad'];
	//     	  if($value['Utilidad']=='' || $value['Utilidad']==0)
	//     	  {
	//     	  	$uti = number_format($value['utilidad_C']*100,2);
	// 				  $parametros = array('utilidad'=>$uti,'linea'=>$value['ID']);
	// 				  $this->guardar_utilidad($parametros);
	// 				  $uti = number_format($value['utilidad_C']);
	//     	  }
	//     	  $gra_t = ($value['Valor_Total']*$uti)+$value['Valor_Total'];
	//     	  $uni = ($gra_t/$value['Salida']);
	//     	 	$tablaHTML[$pos]['medidas']=$tablaHTML[1]['medidas'];
	// 		    $tablaHTML[$pos]['alineado']= $tablaHTML[1]['alineado'];
	// 		    $tablaHTML[$pos]['datos']=array($value['Codigo_Inv'],$value['Producto'],$value['Salida'],number_format($uni,2),number_format($gra_t,2));
	// 		    $tablaHTML[$pos]['borde'] =1;

	// 		    $pos+=2;
	// 		    $total+=number_format($gra_t,2);
	// 		  }
	//     }
	//      $pos+=1;
	//      $tablaHTML[$pos]['medidas']=array(140,25,25);
 //       $tablaHTML[$pos]['alineado']=array('L','L','R');
 //       $tablaHTML[$pos]['datos']=array('','<b>Total','<b>'.$total);
 //       $tablaHTML[$pos]['borde'] =1;
 //       $pos+=1;
 //       if(($key1+1)!=$reg)
 //       {
 //       	 $tablaHTML[$pos]['medidas']=array(190);
	//        $tablaHTML[$pos]['alineado']=array('L');
	//        $tablaHTML[$pos]['datos']=array('');

	//         $pos+=1;
	// 	     $tablaHTML[$pos]['medidas']=array(39,83,18,25,25);
	//        $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
	//        $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>PRECIO UNI','<b>PRECIO TOTAL');
	//        $tablaHTML[$pos]['borde'] =1;
	//        $pos+=1;
 //       }

 //    }


    


 //    // $tablaHTML[$pos]['medidas']=array(140,25,25);
 //    // $tablaHTML[$pos]['alineado']=array('L','L','R');
 //    // $tablaHTML[$pos]['datos']=array('','<b>Total','<b>'.$total);
 //    // $tablaHTML[$pos]['borde'] =1;

 //  	$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,false,false,$sizetable,$mostrar,25,'P');
	// }

	function datos_comprobante($comprobante,$query1)
	{
		$datos = $this->modelo->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->modelo->lineas_trans_kardex($comprobante,$query1);
		$tr='';
		$tot=0;
		$num_lineas = count($lineas);
		foreach ($lineas as $key => $value) {
			$readonly = '';
			$key+=1;
			$registrado = $this->modelo->lista_devoluciones($comprobante);
			foreach ($registrado['datos'] as $key2 => $value2) {
				if($value['Codigo_Inv']==$value2['CODIGO PRODUCTO'])
				{
					$readonly = 'readonly=""';
					break;
				}
			}
			$tr.='<tr>
			  			<td width="'.dimenciones_tabl(strlen('Codigo_Inv')).'" id="codigo_'.$key.'">'.$value['Codigo_Inv'].'</td>
			  			<td width="'.dimenciones_tabl(strlen($value['Producto'])).'" id="producto_'.$key.'">'.$value['Producto'].'</td>
			  			<td width="'.dimenciones_tabl(strlen('cant'.$value['Salida'])).'"><input class="form-control input-sm" value="'.$value['Salida'].'" readonly="" name="txt_salida_'.$key.'" id="txt_salida_'.$key.'"></td>
			  			<td width="'.dimenciones_tabl(strlen('Valor_Unitario')).'" class="text-right">'.number_format($value['Valor_Unitario'],2).'</td>
			  			<td width="'. dimenciones_tabl(strlen('Precio Total')).'" class="text-right">'.number_format($value['Valor_Total'],2).'</td>
        			<td  width="'.dimenciones_tabl(strlen('cant_dev')).'"><input class="form-control input-sm text-right" id="txt_cant_dev_'.$key.'"  value="0" onblur="calcular_dev(\''.$key.'\')" '.$readonly.'></td>
        			<td width="'.dimenciones_tabl(strlen('Valor utilidad')).'"><input class="form-control input-sm text-right" id="txt_valor_'.$key.'" value="0"   readonly></td>
        			<td width="'.dimenciones_tabl(strlen('total_dev')).'"><input class="form-control input-sm text-right" id="txt_gran_t_'.$key.'" value="0"  readonly=""></td>
        			<td><button onclick="calcular_dev(\''.$key.'\');guardar_devolucion(\''.$value['ID'].'\',\''.$key.'\')" id="btn_linea_'.$key.'" class="btn btn-primary"><i class="fa-icon fa fa-save"></i></button></td>
        		</tr>';
        	// $tot+=$gran;
		}
		$tr.='<tr><td colspan="6"></td><td class="text-right"><b>TOTAL</b></td><td class="text-right" id="txt_tt">'.$tot.'</td></tr>';

		return array('cliente'=>$datos,'tabla'=>$tr,'lineas'=>$num_lineas,'total'=>number_format($tot,2));

	}

	function guardar_utilidad($parametros)
	{
		$tabla = 'Trans_Kardex';
		$datos[0]['campo']='Utilidad';
		$datos[0]['dato']=$parametros['utilidad']/100;

		$campoWhere[0]['campo']='ID';
		$campoWhere[0]['valor']=$parametros['linea'];
		return update_generico($datos,$tabla,$campoWhere);

	}
	function costo($codigo)
	{
		$datos = $this->descargos->costo_producto($codigo);
		return $datos;
	}

	function guardar_devoluciones($parametro)
	{
		// print_r($parametro);die();

		   $linea = $this->modelo->trans_kardex_linea_all($parametro['linea']);
		   // print_r($linea);die();
		   $datos[0]['campo']='CODIGO_INV';
		   $datos[0]['dato']=$parametro['codigo'];
		   $datos[1]['campo']='PRODUCTO';
		   $datos[1]['dato']=$parametro['producto'];
		   $datos[2]['campo']='UNIDAD';
		   $datos[2]['dato']='';
		   $datos[3]['campo']='CANT_ES';
		   $datos[3]['dato']=$parametro['cantidad'];
		   $datos[4]['campo']='CTA_INVENTARIO';
		   $datos[4]['dato']=$linea[0]['Cta_Inv'];
		   $datos[5]['campo']='SUBCTA';
		   $datos[5]['dato']='.';		   //proveedor cod
		   $datos[6]['campo']='CodigoU';
		   $datos[6]['dato']=$_SESSION['INGRESO']['Id'];   
		   $datos[7]['campo']='Item';
		   $datos[7]['dato']=$_SESSION['INGRESO']['item'];
		   $datos[8]['campo']='A_No';
		   $datos[8]['dato']=1;

		   $datos[9]['campo']='Fecha_DUI';
		   $datos[9]['dato']=date('Y-m-d');

		   $datos[10]['campo']='TC';
		   $datos[10]['dato']='P';
		   $datos[11]['campo']='VALOR_TOTAL';
		   $datos[11]['dato']=number_format($parametro['total'],'2');
		   $datos[12]['campo']='CANTIDAD';
		   $datos[12]['dato']=$parametro['cantidad'];
		   $datos[13]['campo']='VALOR_UNIT';
		   $datos[13]['dato']= number_format($parametro['precio'],2);
		   //round($parametro['txt_precio'],2,PHP_ROUND_HALF_DOWN);
		   $datos[14]['campo']='DH';
		   $datos[14]['dato']=1;
		   $datos[15]['campo']='CONTRA_CTA';
		   $datos[15]['dato']='.';
		   $datos[16]['campo']='ORDEN';
		   $datos[16]['dato']=$parametro['comprobante'];
		   // $datos[17]['campo']='IVA';
		   // $datos[17]['dato']=bcdiv($parametro['txt_iva'],'1',4);

		   // $datos[18]['campo']='Fecha_Fab';
		   // $datos[18]['dato']=$parametro['txt_fecha_ela'];

		   
		   // $datos[19]['campo']='Fecha_Exp';
		   // $datos[19]['dato']=$parametro['txt_fecha_exp'];

		   
		   // $datos[20]['campo']='Reg_Sanitario';
		   // $datos[20]['dato']=$parametro['txt_reg_sani'];

		   
		   // $datos[21]['campo']='Lote_No';
		   // $datos[21]['dato']=$parametro['txt_lote'];

		   
		   $datos[17]['campo']='Procedencia';
		   $datos[17]['dato']='Devolucion';

		   
		   // $datos[23]['campo']='Serie_No';
		   // $datos[23]['dato']=$parametro['txt_serie'];

		   // $datos[24]['campo']='P_DESC';
		   // $datos[24]['dato']=$val_descto; 
		   // print_r($parametro);

// print_r($datos);die();
		   $resp = $this->descargos->ingresar_asiento_K($datos);
		   // print_r($resp);die();
		   if($resp ==null)
		   {
		   	return 1;
		   }else
		   {
		   	return -1;
		   }
	
	    // print_r($resp);die();
	}

	function lista_devoluciones($comprobante){
		$datos = $this->modelo->lista_devoluciones($comprobante);
		return $datos['tabla'];
	}

	function eliminar_linea_devo($parametros)
	{
		return $this->modelo->eliminar_linea_dev($parametros['codigo'],$parametros['comprobante']);
	}


}