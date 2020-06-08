<?php 
include('../modelo/ctaOperacionesM.php');
/**
 * 
 */
$controlador =  new ctaOperacionesC();
if(isset($_GET['cuentas']))
{
	echo json_encode($controlador->cuentas());
}
class ctaOperacionesC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new ctaOperacionesM();
	}

	function cuentas()
	{
		// print_r();

		$p = explode('.',$_SESSION['INGRESO']['Formato_Cuentas']);
		
		// C.C.CC.CC.CC.CCC
		 $datos = $this->modelo->cargar_cuentas(strlen($p[0]));
		 $tabla = '<div class="menujq"><ul>';
		 foreach ($datos as $key => $value) {
		 	 $datos2 = $this->modelo->cargar_cuentas(strlen($p[0])+ strlen($p[1])+1,$value['Codigo']);
		 	if(is_numeric($value['Codigo']))
		 	{
		 		if(empty($datos2))
		 		{
		 		   $tabla.='<li><a href="#">'.$value['Codigo'].'- '.$value['Cuenta'].'</a>';        
		 		}else
		 		{
		 			$tabla.='<li><a href="javascript:void();">'.$value['Codigo'].'- '.$value['Cuenta'].'</a><ul>'; 
		 			foreach ($datos2 as $key2 => $value2) {
		 				 $datos3 = $this->modelo->cargar_cuentas(strlen($p[0])+ strlen($p[1])+ strlen($p[2])+2,$value2['Codigo']);
		 				
		 				if(empty($datos3))
		 				{
		 					 $tabla.='<li><a href="#">'.$value2['Codigo'].'- '.$value2['Cuenta'].'</a>';
		 				}else
		 				{
		 				  $tabla.='<li><a href="javascript:void();">'.$value2['Codigo'].'- '.$value2['Cuenta'].'</a><ul>';
		 					foreach ($datos3 as $key3 => $value3) {
		 						 	 $datos4 = $this->modelo->cargar_cuentas(strlen($p[0])+ strlen($p[1])+ strlen($p[2])+ strlen($p[3])+3,$value3['Codigo']);
		 						if(empty($datos4))
		 						{
		 							$tabla.='<li><a href="#">'.$value3['Codigo'].'- '.$value3['Cuenta'].'</a>';
		 						}else
		 						{
		 						  $tabla.='<li><a href="javascript:void();">'.$value3['Codigo'].'- '.$value3['Cuenta'].'</a><ul>';
		 							foreach ($datos4 as $key4 => $value4) {
		 								     $datos5 = $this->modelo->cargar_cuentas(strlen($p[0])+ strlen($p[1])+ strlen($p[2])+ strlen($p[3])+ strlen($p[4])+4,$value4['Codigo']);
		 								if(empty($datos5))
		 								{
		 									$tabla.='<li><a href="#">'.$value4['Codigo'].'- '.$value4['Cuenta'].'</a>';
		 								}else
		 								{
		 								  $tabla.='<li><a href="javascript:void();">'.$value4['Codigo'].'- '.$value4['Cuenta'].'</a><ul>';
		 									foreach ($datos5 as $key5 => $value5) {
		 										      $datos6 = $this->modelo->cargar_cuentas(strlen($p[0])+ strlen($p[1])+ strlen($p[2])+ strlen($p[3])+ strlen($p[4])+ strlen($p[5])+5,$value5['Codigo']);
		 										if(empty($datos6))
		 										{
		 											$tabla.='<li><a href="#">'.$value5['Codigo'].'- '.$value5['Cuenta'].'</a>';
		 										}
		 									}
		 									$tabla.='</ul>';
		 								}		 								
		 							}
		 							$tabla.='</ul>';

		 						}
		 					}
		 				   $tabla.='</ul>';	
		 				}
		 					 				
		 			}
		 			$tabla.='</ul>';

		 		}		 		 
		    }
		}

   $tabla.='</ul>
          </li>';	
		 $tabla.='</ul></div><script  src="../../lib/dist/js/script_acordeon.js"></script>';
// 		 $tabla.='<div class="menujq">
// <ul>
//  <li><a href="javascript:void();">Opción1 desplegable</a>
//   <ul>
//    <li><a href="URL del enlace">Opc. 1.1</a></li>
//    <li><a href="URL del enlace">Opc. 1.2</a></li>
//    <li><a href="javascript:void();">Opc. 1.3 desplegable</a>
//     <ul>
//     <li><a href="URL del enlace">Opc. 1.3.1</a></li>
//     <li><a href="URL del enlace">Opc. 1.3.2</a></li>
//     <li><a href="URL del enlace">Opc. 1.3.3</a></li>
//     <li><a href="URL del enlace">Opc. 1.3.4</a></li>
//     <li><a href="URL del enlace">Opc. 1.3.5</a></li>
//     </ul>
//    </li>
//    <li><a href="URL del enlace">Opc. 1.4</a></li>
//    <li><a href="URL del enlace">Opc. 1.5</a></li>
//   </ul>
//  </li>
//  <li><a href="javascript:void();">Opción2 desplegable</a>
//   <ul>
//    <li><a href="URL del enlace">Opc. 2.1</a></li>
//    <li><a href="URL del enlace">Opc. 2.2</a></li>
//    <li><a href="URL del enlace">Opc. 2.3</a></li>
//    <li><a href="URL del enlace">Opc. 2.4</a></li>
//    <li><a href="URL del enlace">Opc. 2.5</a></li>
//    <li><a href="URL del enlace">Opc. 2.6</a></li>
//    <li><a href="URL del enlace">Opc. 2.7</a></li>
//    <li><a href="URL del enlace">Opc. 2.8</a></li>
//    <li><a href="URL del enlace">Opc. 2.9</a></li>
//   </ul>
//  </li>
//  <li><a href="javascript:void();">Opción3 desplegable</a>
//   <ul>
//    <li><a href="URL del enlace">Opc. 3.1</a></li>
//    <li><a href="URL del enlace">Opc. 3.2</a></li>
//    <li><a href="URL del enlace">Opc. 3.3</a></li>
//    <li><a href="URL del enlace">Opc. 3.4</a></li>
//    <li><a href="URL del enlace">Opc. 3.5</a></li>
//    <li><a href="URL del enlace">Opc. 3.6</a></li>
//    <li><a href="URL del enlace">Opc. 3.7</a></li>
//   </ul>
//  </li>
//  <li><a href="URL del enlace">Opción4 Directa</a></li>
//  <li><a href="javascript:void();">Opción5 desplegable</a>
//   <ul>
//    <li><a href="URL del enlace">Opc. 5.1</a></li>
//    <li><a href="URL del enlace">Opc. 5.2</a></li>
//    <li><a href="URL del enlace">Opc. 5.3</a></li>
//    <li><a href="URL del enlace">Opc. 5.4</a></li>
//   </ul>
//  </li>
//  <li><a href="URL del enlace">Opción6 Directa</a></li>
// </ul>
// </div>
// <script  src="../../lib/dist/js/script_acordeon.js"></script>
// ';
return $tabla;
	}
}
?>