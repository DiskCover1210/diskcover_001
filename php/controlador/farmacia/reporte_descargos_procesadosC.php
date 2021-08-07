<?php 
include 'pacienteC.php'; 
include (dirname(__DIR__,2).'/modelo/farmacia/reportes_descargos_procesadosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new reportes_descargos_procesadosC();
if(isset($_GET['cargar_pedidos']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->cargar_pedidos($parametros));
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

if(isset($_GET['imprimir_pdf1']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}

if(isset($_GET['imprimir_excel']))
{   
	$parametros= $_GET;
	$controlador->imprimir_excel($parametros);	
}

class reportes_descargos_procesadosC 
{
	private $modelo;
	private $paciente;
	function __construct()
	{
		$this->modelo = new reportes_descargos_procesadosM();
		$this->paciente = new pacienteC();
		$this->pdf = new cabecera_pdf();
	}

	

	function cargar_pedidos($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->cargar_comprobantes($parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);
		// print_r($datos);die();
		$tr='';
		// print_r($datos);die();
		foreach ($datos as $key => $value) {
			$item = $key+1;
			
			$d =  dimenciones_tabl(strlen($item));
			$d2 =  dimenciones_tabl(strlen($value['Numero']));
			$d3 =  dimenciones_tabl(strlen($value['Fecha']->format('Y-m-d')));
			$d4 =  dimenciones_tabl(strlen($value['Concepto']));
			$d5 =  dimenciones_tabl(strlen($value['Cliente']));
			$d6 =  dimenciones_tabl(strlen($value['Monto_Total']));
			$tr.='<tr>
  					<td width="'.$d.'">'.$item.'</td>
  					<td width="'.$d2.'">'.$value['Numero'].'</td>
  					<td width="'.$d3.'">'.$value['Fecha']->format('Y-m-d').'</td>
  					<td width="'.$d4.'">'.$value['Concepto'].'</td>
  					<td width="'.$d5.'">'.$value['Cliente'].'</td>
  					<td width="'.$d6.'">'.$value['Monto_Total'].'</td>
  					<td width="90px">
  						<!-- <a href="#" class="btn btn-sm btn-primary" title="Editar pedido"><span class="glyphicon glyphicon-pencil"></span></a>
  						<button class="btn btn-sm btn-default" title="Revisar contenido" onclick="eliminar_pedido()"><span class="glyphicon glyphicon-list"></span></button>-->
  					</td>
  				</tr>';
  		 
		}
		if(count($datos)>0)
		{
			$tabla = array('num_lin'=>0,'tabla'=>$tr);
			return $tabla;
		}else
		{
			$tabla = array('num_lin'=>0,'tabla'=>'<tr><td colspan="7" class="text-center"><b><i>Sin registros...<i></b></td></tr>');
			return $tabla;		
		}

	}

	function imprimir_pdf($parametros)
    {
    	// print_r($parametros['txt_desde']);die();
  	    $desde = str_replace('-','',$parametros['txt_desde']);
		$hasta = str_replace('-','',$parametros['txt_hasta']);

		$datos = $this->modelo->cargar_comprobantes($parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);


		$titulo = 'D E S C A R G O S  R E A L I Z A D O S';
		$sizetable =7;
		$mostrar = TRUE;
		$Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
		$Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();
		
		$medidas = array(41,110,22,17);
		$alineado = array('L','L','L','L');
		$pos = 0;
		$borde = 1;
		// print_r($datos);die();
		$gran_total = 0;
		foreach ($datos as $key => $value){
			$tablaHTML[$pos]['medidas']=$medidas;
		    $tablaHTML[$pos]['alineado']=$alineado;
		    $tablaHTML[$pos]['datos']=array('');
		    // $tablaHTML[$pos]['borde'] =$borde;
		    $pos+=1;
			$tablaHTML[$pos]['medidas']=$medidas;
		    $tablaHTML[$pos]['alineado']=$alineado;
		    // print_r($value);die();
		    $tablaHTML[$pos]['datos']=array('<b>Nombre:
		    	'.$value['Cliente'],'<b>PROCEDIMIENTO:
		    	'.$value['Concepto'],'<b>Fecha :
		    	'.$value['Fecha']->format('Y-m-d'),'<b>No.Comp:
		    	'.$value['Numero']);
		    $tablaHTML[$pos]['borde'] =$borde;

		    if($parametros['txt_tipo_filtro']=='f')
		    {
		    	     $lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 $pos+=1;
		    		 $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		             $tablaHTML[$pos]['borde'] =$borde;

		             $total = 0;
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		    		 	$pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array($value2['Codigo_Inv'],$pro[0]['Producto'],$value2['Salida'],$value2['Valor_Unitario'],$value2['Valor_Total']);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $total+=$value2['Valor_Total'];
		    		 }
		    		 $pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $gran_total+=$total;
		    	     $pos+=1;		    		
		    	
		    }else
		    {
		    	 $lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 $pos+=1;
		    		 $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		             $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		             $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		             $tablaHTML[$pos]['borde'] =$borde;

		             $total = 0;
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		    		 	$pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array($value2['Codigo_Inv'],$pro[0]['Producto'],$value2['Salida'],$value2['Valor_Unitario'],$value2['Valor_Total']);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $total+=$value2['Valor_Total'];
		    		 }
		    		 $pos+=1;
		    		    $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		                $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		                $tablaHTML[$pos]['datos']=array('','','','TOTAL',$total);
		                $tablaHTML[$pos]['borde'] =$borde;
		                $gran_total+=$total;
		    	     $pos+=1;		
		    }

			// foreach ($lineas as $key => $value) {
			// 	$lin+=1;
			// }
			$pos+=1;
			
		}
		 $tablaHTML[$pos]['medidas']=array(39,100,13,21,18);
		 $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		 $tablaHTML[$pos]['datos']=array('','','','<b>GRAN TOTAL ',$gran_total);
		 $tablaHTML[$pos]['borde'] =array('T');


		if($parametros['txt_tipo_filtro']=='f')
		{
			$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,$sizetable,$mostrar,25,'P');
		}else
		{
			$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,false,false,$sizetable,$mostrar,25,'P');
		
		}

  }

  function tabla_detalles($parametros)
  {
  	// print_r($parametros);die();
  	    $desde = str_replace('-','',$parametros['desde']);
		$hasta = str_replace('-','',$parametros['hasta']);
		
		$datos = $this->modelo->cargar_comprobantes($parametros['query'],$parametros['desde'],$parametros['hasta'],$parametros['busfe']);

         $html = '';
		foreach ($datos as $key => $value){

			$html.='<tr style="background: skyblue;">
                <td colspan="2"><b>NOMBRE: </b> '.$value['Cliente'].'</td>
                <td><b>DETALLE: </b> '.$value['Concepto'].'</td>
                <td><b>FECHA: </b> '.$value['Fecha']->format('Y-m-d').'</td>
                <td><b>No. Compro.</b>'.$value['Numero'].'</td>                
              </tr>';
		    if($parametros['busfe']=='f')
		    {
		    		$html.='<tr  style="background: skyblue;">
                              
                           </tr>
                           <tr>
                             <td><b>CODIGO</b></td>
                             <td><b>PRODUCTO</b></td>
                             <td><b>CANTIDAD</b></td>
                             <td><b>VALOR UNI</b></td>
                             <td><b>VALOR TOTAL</b></td>
                           </tr>';
		             $total = 0;

		    		$lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		    		 $html.='<tr>
                             <td>'.$value2['Codigo_Inv'].'</td>
                             <td>'.$pro[0]['Producto'].'</td>
                             <td>'.$value2['Salida'].'</td>
                             <td>'.$value2['Valor_Unitario'].'</td>
                             <td>'.$value2['Valor_Total'].'</td>
                           </tr>';
		                $total+=$value2['Valor_Total'];
		    		 }
		    		$html.='<tr>
		    		          <td colspan="3"></td>
                              <td><b>TOTAL</b></td>
                              <td><b>'.$total.'</b></td>
                           </tr>';
		    	
		    }else
		    {
		    	    $total = 0;

		    		$lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 foreach ($lineas as $key => $value2) {
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);
		    		 $html.='<tr>
                             <td>'.$value2['Codigo_Inv'].'</td>
                             <td>'.$pro[0]['Producto'].'</td>
                             <td>'.$value2['Salida'].'</td>
                             <td>'.$value2['Valor_Unitario'].'</td>
                             <td>'.$value2['Valor_Total'].'</td>
                           </tr>';
		                $total+=$value2['Valor_Total'];
		    		 }
		    		$html.='<tr>
		    		          <td colspan="3"></td>
                              <td><b>TOTAL</b></td>
                              <td><b>'.$total.'</b></td>
                           </tr>';
		    	
		    }
		}

		// print_r($html);die();

		return $html;

  }

  function imprimir_excel($parametros)
	{
	 $_SESSION['INGRESO']['ti']='DESCARGOS REALIZADOS';
	 $Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
     $Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		
	 $datos = $datos = $this->modelo->cargar_comprobantes($parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);

	 $registros = array();
	 $reg_lineas = array();
	 foreach ($datos as $key => $value){
	 	    // print_r($value);die();
			$registros[] = array('Nombre'=>$value['Cliente'],'fecha'=>$value['Fecha']->format('Y-m-d'),'Concepto'=>$value['Concepto'],'comprobante'=>$value['Numero'],'registros'=>array());
		             $lineas = $this->modelo->trans_kardex($value['Numero']);
		    		 // $pos+=1;
		    		 // $tablaHTML[$pos]['medidas']=array(39,100,15,18,18);
		       //       $tablaHTML[$pos]['alineado']=array('L','L','R','R','R');
		       //       $tablaHTML[$pos]['datos']=array('<b>CODIGO','<b>PRODUCTO','<b>CANTIDAD','<b>VALOR UNI','<b>VALOR TOTAL');
		       //       $tablaHTML[$pos]['borde'] =$borde;

		             // $total = 0;
		    		 foreach ($lineas as $key2 => $value2) {
		    		 	$pro = $this->modelo->producto($value2['Codigo_Inv']);

		    		 	$reg_lineas[] = array('codigo'=>$value2['Codigo_Inv'],'cantidad'=>$value2['Salida'],'producto'=>$pro[0]['Producto'],'pre_uni'=>$value2['Valor_Unitario'],'total'=>$value2['Valor_Total']);
		    		 	// $total+=$value2['Valor_Total'];
		    		 }
		    		  array_push($registros[$key]['registros'],$reg_lineas);
		    	      $reg_lineas=array();
		}

		// print_r($registros);die();


	 $this->modelo->imprimir_excel($registros);
	}


}