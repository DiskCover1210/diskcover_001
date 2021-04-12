<?php
// require(dirname(__DIR__,2).'/modelo/niveles_seguriM.php');
require(dirname(__DIR__,2).'/modelo/seteos/auditoriaM.php');
require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
/**
 * 
 */

$controlador = new auditoriaC();
if(isset($_GET['modulos']))
{
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo json_encode($controlador->modulos($_GET['q']));
}
if (isset($_GET['empresa'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}

	// print_r($_GET);
	echo json_encode($controlador->empresas($_GET['entidad'],$_GET['q']));
}
if (isset($_GET['tabla'])) 
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->reporte_auditoria($parametros));
}
if (isset($_GET['entidades'])) {
	if(!isset($_GET['q']))
	{
		$_GET['q']='';
	}
	echo json_encode($controlador->entidades($_GET['q']));
}
if(isset($_GET['imprimir_pdf']))
{
	$parametros= $_GET;
     $controlador->imprimir_pdf($parametros);
}
if(isset($_GET['imprimir_excel']))
{   
	$parametros= $_GET;
	$controlador->imprimir_excel($parametros);	
}

class auditoriaC
{
	
	private $modelo;
	private $pdf;
	
	function __construct()
	{
	   // $this->niveles = new  niveles_seguriM();	 
	   $this->modelo = new  auditoriaM();	   
	   $this->pdf = new cabecera_pdf();
	}

	function entidades($valor)
	{
		$entidades = $this->modelo->entidades($valor);
		return $entidades;

	}

	function modulos($query)
	{
		$modulos = $this->modelo->modulos_todo($query);
		// print_r($modulos);die();
		$mod = array();
		foreach ($modulos as $key => $value) {
			$mod[]=array('id'=>$value['aplicacion'],'text'=>$value['aplicacion']);
		}
		return $mod;
	}
	function empresas($entidad,$query)
	{
		$empresas = $this->modelo->empresas($entidad,$query);
		
		return $empresas;

	}
	function reporte_auditoria($parametros)
	{
		// print_r($parametros);die();
		$datos = $this->modelo->tabla_registros($parametros['ent'],$parametros['emp'],$parametros['usu'],$parametros['mod'],$parametros['des'],$parametros['has'],$parametros['numReg']);
		$tr='';
		if(count($datos)>0)
		{
		foreach ($datos as $key => $value) {
			 $ent = $this->modelo->entidades(false,$value['RUC']);
			 $ent = explode('_', $ent[0]['id']);
			$empresas = $this->modelo->empresas($ent[1],false,$value['Item']);
			$tam_en = '100px';
			$tam_nom = '100px';
			$tam_ta = '100px';
			// print_r($empresas);die();

			$tr.='<tr>
						<td  width="95px">'.$value['Fecha'].'</td>
						<td  width="50px">'.$value['Hora'].'</td>
						<td  width="150px">'.$value['enti'].'</td>
						<td  width="150px">'.$value['IP_Acceso'].'</td>
						<td  width="95px">'.$value['Aplicacion'].'</td>
						<td width = "150px">'.$value['Tarea'].'</td>
						<td width="150px">'.$empresas[0]['text'].'</td>
						<td  width="150px">'.$value['nom'].'</td>
					</tr>';
		}
	   }else
	   {
	   	 $tr.='<tr><td colspan="7">NO SE ENCONTRARON REGISTROS...</td></tr>';
	   }

		return $tr;

	}

	function imprimir_pdf($parametros)
    {
    	// print_r($parametros);die();
  	    $desde = str_replace('-','',$parametros['txt_desde']);
		$hasta = str_replace('-','',$parametros['txt_hasta']);
		$empresa = explode('_', $parametros['ddl_entidad']);
		$parametros['ddl_entidad'] = $empresa[0];

		// print_r($parametros);die();

		// $datos = $this->modelo->pedido_paciente_distintos(false,$parametros['rbl_buscar'],$parametros['txt_query'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['txt_tipo_filtro']);


		$titulo = 'I N F O R M E  D E  A U D I T O R I A';
		$sizetable =7;
		$mostrar = TRUE;
		$Fechaini = $parametros['txt_desde'] ;//str_replace('-','',$parametros['Fechaini']);
		$Fechafin = $parametros['txt_hasta']; //str_replace('-','',$parametros['Fechafin']);
		$tablaHTML= array();
		
		$medidas = array(56,55,62,17);
		$alineado = array('L','L','L','L');
		$pos = 0;
		$borde = 1;
		// print_r($datos);die();
		$pos=1;
		    		$tablaHTML[0]['medidas']=array(15,13,30,20,23,35,30,27);
		            $tablaHTML[0]['alineado']=array('L','L','L','L','L','L','L','L');
		            $tablaHTML[0]['datos']=array('FECHA','HORA','ENTIDAD','IP ACCESO','MODULO','TAREA REALIZADA','EMPRESA','USAURIO');
		            $tablaHTML[0]['borde'] =$borde;

		$datos = $this->modelo->tabla_registros($parametros['ddl_entidad'],$parametros['ddl_empresa'],$parametros['ddl_usuario'],$parametros['ddl_modulos'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['ddl_num_reg']);
		
		foreach ($datos as $key => $value) {
			 $ent = $this->modelo->entidades(false,$value['RUC']);
			 $ent = explode('_', $ent[0]['id']);
			$empresas = $this->modelo->empresas($ent[1],false,$value['Item']);

		    $tablaHTML[$pos]['medidas']=$tablaHTML[0]['medidas'];
		    $tablaHTML[$pos]['alineado']=$tablaHTML[0]['alineado'];
		    $tablaHTML[$pos]['datos']=array($value['Fecha'],$value['Hora'],utf8_decode($value['enti']),$value['IP_Acceso'],$value['Aplicacion'],utf8_decode($value['Tarea']),utf8_decode($empresas[0]['text']),utf8_decode($value['nom']));
		    $tablaHTML[$pos]['borde'] =$borde;

			$pos+=1;
		}
	   
		$this->pdf->cabecera_reporte_MC($titulo,$tablaHTML,$contenido=false,$image=false,$Fechaini,$Fechafin,$sizetable,$mostrar,25,'P');
  }

  function imprimir_excel($parametros)
  {
		$empresa = explode('_', $parametros['ddl_empresa']);
		$parametros['ddl_empresa'] = $empresa[0];
  	$datos = $this->modelo->tabla_registros($parametros['ddl_entidad'],$parametros['ddl_empresa'],$parametros['ddl_usuario'],$parametros['ddl_modulos'],$parametros['txt_desde'],$parametros['txt_hasta'],$parametros['ddl_num_reg']);
  	$reg = array();
  	foreach ($datos as $key => $value) {
			 $ent = $this->modelo->entidades(false,$value['RUC']);
			 $ent = explode('_', $ent[0]['id']);
			 $empresas = $this->modelo->empresas($ent[1],false,$value['Item']);
  		$reg[] = array('Fecha'=>$value['Fecha'],'Hora'=>$value['Hora'],'Entidad'=>$value['enti'],'IP_Acceso'=>$value['IP_Acceso'],'Aplicacion'=>$value['Aplicacion'],'Tarea'=>$value['Tarea'],'Empresa'=>$empresas[0]['text'],'Usuario'=>$value['nom']); 
  	}
	 $this->modelo->imprimir_excel($reg);
  }

}
?>