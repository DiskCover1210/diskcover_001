<?php
include(dirname(__DIR__).'/modelo/catalogoCtaM.php');
require(dirname(__DIR__,2).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */

if(isset($_GET['consultar']))
{
	$controlador = new catalogoCtaC();
	$parametros = $_POST['parametros'];
	//print_r( $parametros);
	echo json_encode($controlador->cargar_datos($parametros));
}
if(isset($_GET['imprimir_pdf']))
{
	$controlador = new catalogoCtaC();
	$parametros = array('OpcT'=>$_GET["OpcT"],'OpcG'=>$_GET["OpcG"],'OpcD'=>$_GET["OpcD"],'txt_CtaI'=>$_GET['txt_CtaI'],'txt_CtaF'=>$_GET['txt_CtaF']);
	$controlador->imprimir_pdf($parametros);

}
if(isset($_GET['imprimir_excel']))
{   
	$controlador = new catalogoCtaC();
	$parametros = $_POST['parametros'];
	$controlador->imprimir_excel($parametros);	
}
class catalogoCtaC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   $this->modelo = new  catalogoCtaM();	   
	   $this->pdf = new cabecera_pdf();
	}


	function cargar_datos($parametros)
	{     
      $datos = $this->modelo->cargar_datos_cuenta_tabla($parametros['OpcT'],$parametros['OpcG'],$parametros['OpcD'],$parametros['txt_CtaI'],$parametros['txt_CtaF']);
      //print_r($datos);
      return $datos;
		
	}

	function imprimir_excel($parametros)
	{

	 $_SESSION['INGRESO']['ti']='PLAN DE CUENTAS';
	 $this->modelo->cargar_datos_cuenta_datos($parametros['OpcT'],$parametros['OpcG'],$parametros['OpcD'],$parametros['txt_CtaI'],$parametros['txt_CtaF'],true);
	}

	function imprimir_pdf($parametros)
	{
	    $datos = $this->modelo->cargar_datos_cuenta_datos($parametros['OpcT'],$parametros['OpcG'],$parametros['OpcD'],$parametros['txt_CtaI'],$parametros['txt_CtaF']);
	    $interlineado=20;
	
		$tablaHtml='<table><tr>
		<td width="60"><b>Clave</b></td>
		<td width="30"><b>TC</b></td>
		<td width="30"><b>ME</b></td>
		<td width="30"><b>DG</b></td>
		<td width="120"><b>Codigo</b></td>
		<td width="300"><b>Cuenta</b></td>
		<td width="95"><b>Presupuesto</b></td>
		<td width="85"><b>Codigo_ext</b></td>		
		</tr></table><table border="RIGHT">';
		foreach ($datos as $key => $value) {
			$tablaHtml.='<tr>
		<td width="60" HEIGHT="'.$interlineado.'">'.$value['Clave'].'</td>
		<td width="30" HEIGHT="'.$interlineado.'">'.$value['TC'].'</td>
		<td width="30" HEIGHT="'.$interlineado.'">'.$value['ME'].'</td>
		<td width="30" HEIGHT="'.$interlineado.'">'.$value['DG'].'</td>
		<td width="120" HEIGHT="'.$interlineado.'">'.$value['Codigo'].'</td>
		<td width="300" HEIGHT="'.$interlineado.'">'.$value['Cuenta'].'</td>
		<td width="95" HEIGHT="'.$interlineado.'" align="RIGHT">'.$value['Presupuesto'].'</td>
		<td width="85" HEIGHT="'.$interlineado.'">'.$value['Codigo_Ext'].'</td>		
		</tr>';
			
		}
		$tablaHtml.='</table>';
		$this->pdf->cabecera_reporte('PLAN DE CUENTAS',$tablaHtml,$contenido=false,$image=false,'','',9,$mostrar=false);

	}
}
?>