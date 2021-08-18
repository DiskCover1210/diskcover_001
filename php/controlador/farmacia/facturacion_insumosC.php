<?php 
include 'pacienteC.php'; 
include (dirname(__DIR__,2).'/modelo/farmacia/reportes_descargos_procesadosM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */
$controlador = new facturacion_insumosC();
if(isset($_GET['datos_comprobante']))
{   
	$parametros= $_POST['comprobante'];
	echo json_encode($controlador->datos_comprobante($parametros));	
}
if(isset($_GET['guardar_utilidad']))
{   
	$parametros= $_POST['parametros'];
	echo json_encode($controlador->guardar_utilidad($parametros));	
}



class facturacion_insumosC 
{
	private $descargos_procesados;
	private $paciente;
	function __construct()
	{
		$this->descargos_procesados = new reportes_descargos_procesadosM();
		$this->paciente = new pacienteC();
		$this->pdf = new cabecera_pdf();
	}

	function datos_comprobante($comprobante)
	{
		$datos = $this->descargos_procesados->cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$comprobante);
		$lineas = $this->descargos_procesados->lineas_trans_kardex($comprobante);
		$tr='';
		$tot=0;
		$num_lineas = count($lineas);
		foreach ($lineas as $key => $value) {
			$key+=1;
			$uti = number_format($value['Utilidad']*100,2);
			if($value['Utilidad']=='' || $value['Utilidad']==0)
			{
				$uti = number_format($value['utilidad_C']*100,2);
				$parametros = array('utilidad'=>$uti,'linea'=>$value['ID']);
				$this->guardar_utilidad($parametros);
			}
			$valor =number_format( ($uti/100)*$value['Valor_Total'],2);
			$gran =number_format( $value['Valor_Total']+$valor,2);
			$tr.='<tr>
			  			<td width="'.dimenciones_tabl(strlen('Codigo_Inv')).'">'.$value['Codigo_Inv'].'</td>
			  			<td width="'.dimenciones_tabl(strlen($value['Producto'])).'">'.$value['Producto'].'</td>
			  			<td width="'.dimenciones_tabl(strlen($value['Salida'])).'">'.$value['Salida'].'</td>
			  			<td width="'.dimenciones_tabl(strlen('Valor_Unitario')).'" class="text-right">'.number_format($value['Valor_Unitario'],2).'</td>
			  			<td width="'. dimenciones_tabl(strlen('Precio Total')).'"><input class="form-control input-sm text-right" id="txt_to_'.$key.'" value="'.number_format($value['Valor_Total'],2).'"  readonly></td>
        			<td  width="'.dimenciones_tabl(strlen('% Utilidad')).'"><input class="form-control input-sm text-right" id="txt_porcentaje_'.$key.'"  value="'.$uti.'" onblur="calcular(\''.$key.'\')"></td>
        			<td width="'.dimenciones_tabl(strlen('Valor utilidad')).'"><input class="form-control input-sm text-right" id="txt_valor_'.$key.'" value="'.$valor.'"   readonly></td>
        			<td width="'.dimenciones_tabl(strlen('Gran total')).'"><input class="form-control input-sm text-right" id="txt_gran_t_'.$key.'" value="'.$gran.'" onblur="calcular_uti(\''.$key.'\')"></td>
        			<td><button onclick="guardar_uti(\''.$value['ID'].'\',\''.$key.'\')" id="btn_linea_'.$key.'" class="btn btn-primary"><i class="fa-icon fa fa-save"></i></button></td>
        		</tr>';
        	$tot+=$gran;
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


}