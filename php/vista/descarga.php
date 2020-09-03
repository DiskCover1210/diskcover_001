<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 */
//require_once 'determ.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("chequear_seguridad_e.php");
require_once("../controlador/contabilidad_controller.php");

$OpcDG=null;
//border
$b=null;
//si escogio una opcion de radio buton
if(isset($_GET['OpcDG'])) 
{
	$OpcDG=$_GET['OpcDG'];
}
//border
if(isset($_GET['b'])) 
{
	$b=$_GET['b'];
}
if(isset($_GET['ex'])) 
{
	//documento excel
	if($_GET['ex']==1) 
	{
		$OpcCE=null;
		if(isset($_GET['OpcCE'])) 
		{
			$OpcCE=$_GET['OpcCE'];
		}
		if($_GET['acc']=='bacsg')
		{
			if(isset($_GET['Opcb'])) 
			{
				exportarExcel($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,$OpcCE);
			}
			else
			{
				exportarExcel($_SESSION['INGRESO']['ti'],null,null,$OpcDG,$b,$OpcCE);
			}
		}
		else
		{
			if($_GET['acc']=='cambiou')
			{
				//echo "dddd ".$_GET['ti'].' - '.$_GET['ch1'].' - '.$_GET['ch2'].' - '.$_GET['ch3'].' - '.$_GET['value1'].' - '.$_GET['value3'].' - '.
				//$_GET['value7'].' - '.$_GET['value5'].' - '.$_GET['value6'].' - ';
				$arr=array();
				$arr['ti']=$_GET['ti'];
				$arr['ch1']=$_GET['ch1'];
				$arr['ch2']=$_GET['ch2'];
				$arr['ch3']=$_GET['ch3'];
				$arr['value1']=$_GET['value1'];
				$arr['value3']=$_GET['value3'];
				$arr['value7']=$_GET['value7'];
				$arr['value5']=$_GET['value5'];
				$arr['value6']=$_GET['value6'];
				if(isset($_GET['Opcb'])) 
				{
					exportarExcel($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,$OpcCE,'MYSQL','1','USUARIO',$arr);
				}
				else
				{
					exportarExcel($_SESSION['INGRESO']['ti'],null,null,$OpcDG,$b,$OpcCE,'MYSQL','1','USUARIO',$arr);
				}
				//'&ch1='+ch1+'&value1='+value1+'&ch2'+ch2+'&value3='+value3+'&ch3='+ch3+'&value7='+value7+'&value5='+value5+'&value6='+value6+'';
				die();
			}
			else
			{
				if($_GET['acc']=='fact')
				{
					
					if(isset($_GET['Opcb'])) 
					{
						//ListarFacturacion($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,'2');
						ListarFacturacion($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,'2','1,clave',
						null, null, $_GET['filtros'],$_GET['ord']);
					}
					else
					{
						//ListarFacturacion('REPORTE FACTURACION',null,null,$OpcDG,$b,'2');
						ListarFacturacion('REPORTE FACTURACION',null,null,$OpcDG,$b,'2','1,clave',
						null, null, $_GET['filtros'],$_GET['ord']);
					}
				}
				else
				{
					if($_GET['acc']=='mosEm')
					{
						ListarEmpresasSQL('Analisis de vencimiento',null,null,null,null,2,null,$_GET['desde'],$_GET['hasta']);
						//die();
					}
					else
					{
						if(isset($_GET['Opcb'])) 
						{
							ListarDocEletronico($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,'2');
						}
						else
						{
							ListarDocEletronico($_SESSION['INGRESO']['ti'],null,null,$OpcDG,$b,'2');
						}
					}
				}
			}
		}
	}
	//documento xml
	if(!isset($_GET['cl']))
	{
		//return "vacio";
		echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
	}
	if($_GET['ex']=='xml') 
	{
		//vemos la accion
		if($_GET['acc']=='rde')
		{
			ImprimirDocEletronico($_GET['cl'],'xml','Trans_Documentos','Documento_Autorizado','Clave_Acceso');
		}
	}
	//documento pdf
	if($_GET['ex']=='pdf') 
	{
		//vemos la accion
		if($_GET['acc']=='rde')
		{
			ImprimirDocEletronico($_GET['cl'],'pdf','Trans_Documentos','Documento_Autorizado,TD','Clave_Acceso');
		}
		if($_GET['acc']=='macom')
		{
			ImprimirDocEletronico('macom','pdf','Trans_Documentos','Documento_Autorizado,TD','Clave_Acceso');
		}
	}
}
