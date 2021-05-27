<?php 
include(dirname(__DIR__,2).'/modelo/contabilidad/incomM.php');
include(dirname(__DIR__,2).'/comprobantes/SRI/autorizar_sri.php');
/**
 * 
 */
$controlador = new incomC();
if(isset($_GET['beneficiario']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cargar_beneficiario($query));
}
if(isset($_GET['cuentas_efectivo']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cuentas_efectivo($query));
}
if(isset($_GET['ListarAsientoB']))
{
	echo json_encode($controlador->ListarAsientoB());
}
if(isset($_GET['cuentas_banco']))
{
	$query = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
	}
	echo json_encode($controlador->cuentas_banco($query));
}
if(isset($_GET['cuentasTodos']))
{
	$query = '';
    $ti='';
    $tipo = '';
	if(isset($_GET['q']))
	{
		$query = $_GET['q'];
         if(count($query)>1)
        {
            $query = $query['term'];
            if($query =='*')
            {
                $query = '';
            }
        }else{$query ='';}

        $ti = $_GET['tip'];
	}
    if(isset($_GET['q1']))
    {
        $query = $_GET['q1'];
        $tipo = '1';
    }
	echo json_encode($controlador->cuentas_Todos($query,$tipo,$ti));
}

if(isset($_GET['asientoB']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->InsertarAsientoBanco($parametros));
}
if(isset($_GET['EliAsientoB']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->delete_asientoB($parametros));
}
if(isset($_GET['EliAsientoBTodos']))
{
	echo json_encode($controlador->delete_asientoBTodos());
}
if(isset($_GET['tabs_contabilidad']))
{
	echo json_encode($controlador->cargar_tablas());
}
if(isset($_GET['tabs_sc']))
{
	echo json_encode($controlador->cargar_tablas_sc());
}
if(isset($_GET['tabs_sc_modal']))
{
	$parametros = $_POST['parametros'];
	echo json_decode($controlador->cargar_tablas_sc_modal($parametros));
}

if(isset($_GET['tabs_retencion']))
{
	echo json_encode($controlador->cargar_tablas_retencion());
}
if(isset($_GET['tabs_tab4']))
{
	echo json_encode($controlador->cargar_tablas_tab4());
}
if(isset($_GET['subcuentas']))
{
	$parametros = $_POST['parametros'];
	echo json_decode($controlador->listar_subcuentas($parametros));
}
if(isset($_GET['TipoCuenta']))
{
	$codigo = $_POST['codigo'];
	echo json_encode($controlador->LeerCta($codigo));
}

if(isset($_GET['modal_generar_sc']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_generar_asiento_SC($parametros));
}
if(isset($_GET['modal_ingresar_asiento']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_ingresar_asiento($parametros));
}
if(isset($_GET['modal_limpiar_asiento']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->modal_subcta_limpiar($parametros));
}
if(isset($_GET['eliminar_retenciones']))
{
    echo json_encode($controlador->eliminar_retenciones());
}
if(isset($_GET['modal_detalle_aux']))
{
	if(!isset($_GET['q'])){$_GET['q'] = '';}
	$parametros = array(
    		'tc'=>$_GET['tc'],
    		'OpcDH'=>$_GET['OpcDH'],
    		'OpcTM'=>$_GET['OpcTM'],
    		'cta'=>$_GET['cta'],
    		'query'=>$_GET['q']);
	echo json_encode($controlador->detalle_aux_submodulo($parametros));
}

if(isset($_GET['modal_subcta_catalogo']))
{

	if(!isset($_GET['q']))
	{
		$_GET['q'] = '';
	}

	$parametros = array(
    		'tc'=>$_GET['tc'],
    		'OpcDH'=>$_GET['OpcDH'],
    		'OpcTM'=>$_GET['OpcTM'],
    		'cta'=>$_GET['cta'],
    		'query'=>$_GET['q']);
	echo json_encode($controlador->catalogo_subcta($parametros));
	
}
if(isset($_GET['totales_asientos']))
{
	// $parametros = $_POST['parametros'];
	echo json_encode($controlador->datos_de_asientos());
}
if(isset($_GET['generar_comprobante']))
{
	$parametros = $_POST['parametros'];
	echo json_encode($controlador->generar_comprobante($parametros));
}
if(isset($_GET['eliminarregistro']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->eliminar_registro($parametros));
}

if(isset($_GET['num_comprobante']))
{    
    $parametros = $_POST['parametros'];

    // print_r($parametros);die();
    echo json_encode(numero_comprobante1($parametros['tip'],true,false,$parametros['fecha']));
}
if(isset($_GET['generar_xml']))
{
    $parametros = $_POST['parametros'];
    echo json_encode($controlador->SRI_Crear_Clave_Acceso_Retenciones($parametros));
}

class incomC
{
	private $modelo;
    private $sri;	
	function __construct()
	{
		$this->modelo = new incomM();
        $this->sri = new autorizacion_sri();
	}

	function cargar_beneficiario($query)
	{
		$datos = $this->modelo->beneficiarios($query);
		$bene = array();
		foreach ($datos as $key => $value) {
			$bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>utf8_encode($value['nombre']));
			// $bene[] = array('id'=>$value['id'].'-'.$value['email'],'text'=>$value['nombre']);//para produccion
		}
		return $bene;
	}

	function cuentas_efectivo($query)
	{
		$datos = $this->modelo->cuentas_efectivo($query);
		$cuenta = array();
		foreach ($datos as $key => $value) {
			$cuenta[] = array('id'=>$value['Codigo'],'text'=>utf8_encode($value['cuenta']));
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);//para produccion
		}
		return $cuenta;

	}

	function cuentas_banco($query)
	{
		$datos = $this->modelo->cuentas_banco($query);
		$cuenta = array();
		foreach ($datos as $key => $value) {
			$cuenta[] = array('id'=>$value['Codigo'],'text'=>utf8_encode($value['cuenta']));
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['cuenta']);//para produccion
		}
		return $cuenta;

	}

	function cuentas_Todos($query,$tipo,$tipoCta)
	{
		$datos = $this->modelo->cuentas_todos($query,$tipo,$tipoCta);
		$cuenta = array();
		foreach ($datos as $key => $value) {
            if($tipo=='')
            {
                $cuenta[] = array('id'=>$value['Codigo'],'text'=>utf8_encode($value['Nombre_Cuenta']));
			// $cuenta[] = array('id'=>$value['Codigo'],'text'=>$value['Nombre_Cuenta']);//para produccion
            }else
            {                
                $cuenta[] = array('value'=>$value['Codigo'],'label'=>utf8_encode($value['Nombre_Cuenta']));
            }
		}
		return $cuenta;

	}

	function InsertarAsientoBanco($parametros)
	{
		// print_r($parametros);die();
		// $datos = $this->modelo->cargar_asientosB();
        $datos[0]['campo']= "ME";
        $datos[1]['campo']= "CTA_BANCO"; 
        $datos[2]['campo']= "BANCO";
        $datos[3]['campo']= "CHEQ_DEP";
        $datos[4]['campo']= "EFECTIVIZAR";
        $datos[5]['campo']= "VALOR";
        $datos[6]['campo']= "T_No"; 
        $datos[7]['campo']= "Item";
        $datos[8]['campo']= "CodigoU";
		
		$datos[0]['dato']= 0;
		$datos[1]['dato']= $parametros['banco']; 
		$datos[2]['dato']= $parametros['bancoC']; 
		$datos[3]['dato']= $parametros['cheque']; 
		$datos[4]['dato']= $parametros['fecha']; 
		$datos[5]['dato']= $parametros['valor']; 
		$datos[6]['dato']= 1;  
		$datos[7]['dato']= $_SESSION['INGRESO']['item'];
		$datos[8]['dato']= $_SESSION['INGRESO']['CodigoU'];

		$resp = $this->modelo->insertar_ingresos($datos);
        if($resp == '')
        {
    	    return 1;
        }else
        {
    	    return -1;
        }
	}

	function delete_asientoB($parametros)
	{
		$cta = $parametros['cta'];
		$cheq = $parametros['cheque'];
		$resp = $this->modelo->delete_asientoB($cta,$cheq);
		if($resp == 1)
		{
			return 1; 
		}else
		{
			return -1;
		}
	}
	function delete_asientoBTodos()
	{
		$resp = $this->modelo->delete_asientoBTodos();
		if($resp == 1)
		{
			return 1; 
		}else
		{
			return -1;
		}
	}

	function cargar_tablas()
	{
		$asiento= $this->modelo->DG_asientos();
		return $asiento;   
	}

	function cargar_tablas_sc()
	{
		$sc= $this->modelo->DG_asientos_SC();
		return $sc;   
	}

	function cargar_tablas_retencion()
	{
		$b= $this->modelo->DG_AC();
		$r= $this->modelo->DG_asientoR();		

		return array('b'=>$b,'r'=>$r);
	}
	function cargar_tablas_tab4()
	{
		// $AC= $this->modelo->DG_AC();
		$AV= $this->modelo->DG_AV();
		$AE= $this->modelo->DG_AE();
		$AI= $this->modelo->DG_AI();
		return $AV.$AE.$AI;   
	}

	function LeerCta($CodigoCta)
	{
		$Cuenta = '.';
        $Codigo = '.';
        $TipoCta = "G";
        $SubCta = "N";
        $TipoPago = "01";
        $Moneda_US = False;
		$datos= $this->modelo->LeerCta($CodigoCta);
		if(count($datos)>0)
		{
			foreach ($datos as $key => $value) {
				$Codigo = $value["Codigo"];
				$Cuenta = $value["Cuenta"];
				$SubCta = $value["TC"];
				$Moneda_US = $value["ME"];
				$TipoCta = $value["DG"];
				$TipoPago = $value["Tipo_Pago"];
				if (strlen($TipoPago) <= 0){$TipoPago = "01";}
			}
		}
		return array('cuenta'=>$Cuenta,'codigo'=>$Codigo,'tipocta'=>$TipoCta,'subcta'=>$SubCta,'tipopago'=>$TipoPago,'moneda'=>$Moneda_US);
     }


     function catalogo_subcta($parametros)
     {
     	// print_r($parametros);die();
     	if($parametros['tc']=='C' ||  $parametros['tc']== "P" || $parametros['tc']=="CP" )
     	{
     		$datos = $this->modelo->Catalogo_CxCxP($parametros['tc'],$parametros['cta'],$parametros['query']);
     		$ddl =array();
     		foreach ($datos as $key => $value) {
     			$ddl[]=array('id'=>$value['Codigo'],'text'=>$value['NomCuenta']);
     		}
     		return $ddl;
     	}else
     	{
     		$datos_tabla = $this->modelo->catalogo_subcta_grid($parametros['tc'],$parametros['cta'],$parametros['OpcDH'],$parametros['OpcTM']);
     	    $datos = $this->modelo->catalogo_subcta($parametros['tc']);
     	

     	}
     }

     function detalle_aux_submodulo($parametros)
     {
     	$result = $this->modelo->detalle_aux_submodulo($parametros['query']);
     	return $result;
     }

     function modal_generar_asiento_SC($parametros)
     {
     	// print_r($parametros);die();
     	$parametros_sc = array(
            'be'=>$parametros['ben'],
            'ru'=> '',
            'co'=> $parametros['cta'],// codigo de cuenta cc
            'tip'=>$parametros['tipoc'],//tipo de cuenta(CE,CD,..--) biene de catalogo subcuentas TC
            'tic'=> $parametros['dh'], //debito o credito (1 o 2);
            'sub'=> $parametros['codigo'], //Codigo se trae catalogo subcuenta o ruc del proveedor en caso de que se este ingresando
            'sub2'=>$parametros['ben'],//nombre del beneficiario
            'fecha_sc'=> $parametros['fec'], //fecha 
            'fac2'=>$parametros['fac'],
            'mes'=> $parametros['mes'],
            'valorn'=> round($parametros['val'],2),//valor de sub cuenta 
            'moneda'=> $parametros['tm'], /// moneda 1
            'Trans'=>$parametros['aux'],//detalle que se trae del asiento
            'T_N'=> $_SESSION['INGRESO']['modulo_'],
            't'=> $parametros['tc'],                        
        );

        $resp = ingresar_asientos_SC($parametros_sc);
        if($resp==null)
        {
        	return array('resp'=>1,'total'=>$parametros['val']);
        }else
        {
        	return array('resp'=>-1,'total'=>$parametros['val']);

        }
     }

     function modal_ingresar_asiento($parametros)
     {
     	$valor = $this->modelo->DG_asientos_SC_total();
     	$cuenta = $this->modelo->cuentas_todos($parametros['cta'],'',''); 
        $parametros_asiento = array(
				"va" => round($valor[0]['total'],2),
				"dconcepto1" => '.',
				"codigo" => $parametros['cta'],
				"cuenta" => $cuenta[0]['Cuenta'],
				"efectivo_as" => $parametros['fec'],
				"chq_as" =>0,
				"moneda" => $parametros['tm'],
				"tipo_cue" => $parametros['dh'],
				"cotizacion" => 0,
				"con" => 0,
				"t_no" => '1',
				"tc"=>$cuenta[0]['TC'],									
			);
         $resp = ingresar_asientos($parametros_asiento);
         if($resp==1)
         {
         	return 1;
         }else
         {
         	return -1;
         }
     }
     function cargar_tablas_sc_modal($parametros)
     {
     	$datos = $this->modelo->catalogo_subcta_grid($parametros['tc'],$parametros['cta'],$parametros['dh'],$parametros['tm']);
     	// print_r($datos);die();
     	return $datos;
     }
     function modal_subcta_limpiar($parametros)
     {
     	$this->modelo->limpiar_asiento_SC($parametros['tc'],$parametros['cta'],$parametros['dh'],$parametros['tm']);
     }
     function asientos_grabados()
     {
     	$asiento = $this->modelo->asiento();
     	$debe = 0;
     	$haber = 0;
     	foreach ($asiento as $key => $value) {
     		$debe+=$value['DEBE'];
     		$haber+=$value['HABER'];
     	}
     	if(($debe-$haber)<>0)
     	{
     		return 2;//Las transacciones no cuadran correctamente  "corrija los resultados de las cuentas"
     	}
     	$asiento_sc = $this->modelo->asiento_sc();
     }

     function datos_de_asientos()
     {
     	$asiento = $this->modelo->asientos();
     	$debe = 0;
     	$haber = 0;
     	foreach ($asiento as $key => $value) {
     		$debe+=$value['DEBE'];
     		$haber+=$value['HABER'];
     	}
     	return array('debe'=>$debe,'haber'=>$haber,'diferencia'=>$debe-$haber);

     }
     function generar_comprobante($parametros)
     {
         if($parametros['tip']=='CD'){$tip = 'Diario';}
         else if($parametros['tip']=='CI'){$tip = 'Ingresos';}
         else if($parametros['tip']=='CE'){$tip = 'Egresos';}
         else if($parametros['tip']=='ND'){$tip = 'NotaDebito';}
         else if($parametros['tip']=='NC'){$tip= 'NotaCredito';}

         $num_com = numero_comprobante1($tip,true,true,$parametros['fecha']);
         // $num_com = '123654789';

     	$parametro_comprobante = array(
            'ru'=> $parametros['ruc'], //codigo del cliente que sale co el ruc del beneficiario codigo
            'tip'=>$parametros['tip'],//tipo de cuenta contable cd, etc
            "fecha1"=> $parametros['fecha'],// fecha actual 2020-09-21
            'concepto'=>$parametros['concepto'], //detalle de la transaccion realida
            'totalh'=> $parametros['totalh'], //total del haber
            'num_com'=> '.'.date('Y', strtotime($parametros['fecha'])).'-'.$num_com, // codigo de comprobante de esta forma 2019-9000002
            );

				 // print_r($nombre);print_r($ruc);print_r($fecha);
				 // print_r($parametro_comprobante);die();

            // $cod = explode('-',$parametros['num_com']);
            // print_r($cod);die();
              if($this->ingresar_trans_Air($num_com,$parametros['tip'])==1){
                $resp = generar_comprobantes($parametro_comprobante);
            // print_r($resp);die();
                if($resp==$num_com)
                {
                    return 1;
                }else
                {
                    return -1;
                }
              }else
              {
                echo " no se genero";
              }
           

     }
     function ingresar_trans_Air($numero,$tipo)
     {
        $air = $this->modelo->DG_asientoR_datos();
        $fallo = false;
        if(count($air)>0)
        {
            foreach ($air as $key => $value) {
                $datos[0]['campo'] = 'CodRet';
                $datos[1]['campo'] = 'Detalle';
                $datos[2]['campo'] = 'BaseImp';
                $datos[3]['campo'] = 'Porcentaje';
                $datos[4]['campo'] = 'ValRet';
                $datos[5]['campo'] = 'EstabRetencion';
                $datos[6]['campo'] = 'PtoEmiRetencion';
                $datos[7]['campo'] = 'SecRetencion';
                $datos[8]['campo'] = 'AutRetencion';
                $datos[9]['campo'] = 'FechaEmiRet';
                $datos[10]['campo'] = 'Cta_Retencion';
                $datos[11]['campo'] = 'EstabFactura';
                $datos[12]['campo'] = 'PuntoEmiFactura';
                $datos[13]['campo'] = 'Factura_No';
                $datos[14]['campo'] = 'IdProv';
                $datos[15]['campo'] = 'Item';
                $datos[16]['campo'] = 'CodigoU';
                $datos[17]['campo'] = 'A_No';
                $datos[18]['campo'] = 'T_No';
                $datos[19]['campo'] = 'Tipo_Trans';
                $datos[20]['campo'] = 'T';
                $datos[21]['campo'] = 'Numero';
                $datos[22]['campo'] = 'TP';

                $datos[0]['dato'] =$value['CodRet'];
                $datos[1]['dato'] =$value['Detalle'];
                $datos[2]['dato'] =$value['BaseImp'];
                $datos[3]['dato'] =$value['Porcentaje'];
                $datos[4]['dato'] =$value['ValRet'];
                $datos[5]['dato'] =$value['EstabRetencion'];
                $datos[6]['dato'] =$value['PtoEmiRetencion'];
                $datos[7]['dato'] =$value['SecRetencion'];
                $datos[8]['dato'] =$value['AutRetencion'];
                $datos[9]['dato'] =$value['FechaEmiRet']->format('Y-m-d');
                $datos[10]['dato'] =$value['Cta_Retencion'];
                $datos[11]['dato'] =$value['EstabFactura'];
                $datos[12]['dato'] =$value['PuntoEmiFactura'];
                $datos[13]['dato'] =$value['Factura_No'];
                $datos[14]['dato'] =$value['IdProv'];
                $datos[15]['dato'] =$value['Item'];
                $datos[16]['dato'] =$value['CodigoU'];
                $datos[17]['dato'] =$value['A_No'];
                $datos[18]['dato'] =$value['T_No'];
                $datos[19]['dato'] =$value['Tipo_Trans'];
                $datos[20]['dato'] ='N';
                $datos[21]['dato'] =$numero;
                $datos[22]['dato'] =$tipo;
            }
           $resp = $this->modelo->insertar_ingresos_tabla('Trans_Air',$datos);
           if($resp !='')
           {
             $fallo = true;
           }
        }
        // print_r($air);die();
        if($fallo==false)
        {
            return 1;
        }
        else{
            return -1;
        }
     }
     function eliminar_retenciones()
     {
        return $this->modelo->eliminacion_retencion();
     }
     function eliminar_registro($parametros)
     {
        $Codigo = '';
        $tabla = '';
       switch ($parametros['tabla']) {
           case 'asiento':
             $Codigo = "CODIGO = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento';
               break;
            case 'asientoSC':
             $Codigo = "Codigo = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_SC';
               # code...
               break;
            case 'asientoB':
             $Codigo = "CTA_BANCO = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_B';
               # code...
               break;
            case 'inpor':
             $Codigo = "Cod_Sustento = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_Importaciones';
               # code...
               break;
            case 'expo':
             $Codigo = "Codigo = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Exportaciones';
               # code...
               break;
            case 'ventas':
             $Codigo = "IdProv = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Ventas';
               # code...
               break;
            case 'compras':
             $Codigo = "IdProv = '".$parametros['Codigo']."' ";
            $tabla = 'Asiento_Compras';
               # code...
               break;
            case 'air':             
             $Codigo = "CodRet = '".$parametros['Codigo']."' ";
             $tabla = 'Asiento_Air';
               # code...
               break;
       }
       return $this->modelo->eliminar_registros($tabla,$Codigo);
     }

     function SRI_Crear_Clave_Acceso_Retenciones($parametros)
     {
        $datos = $this->modelo->retencion_compras($parametros['numero'],$parametros['comp']);
        if(count($datos)>0)
        {
          $TFA[0]["Serie_R"] = $datos[0]["Serie_Retencion"];
          $TFA[0]["Retencion"] = $datos[0]["SecRetencion"];
          $TFA[0]["Autorizacion_R"] = $datos[0]["AutRetencion"];
          $TFA[0]["Autorizacion"] = $datos[0]["Autorizacion"];
          $TFA[0]["Fecha"] = $datos[0]["FechaRegistro"];
          $TFA[0]["Vencimiento"] = $datos[0]["FechaRegistro"];
          $TFA[0]["Serie"] = $datos[0]["Establecimiento"].$datos[0]["PuntoEmision"];
          $TFA[0]["Factura"] = $datos[0]["Secuencial"];
          $TFA[0]["Hora"] = date('H:m:s');
          $TFA[0]["Cliente"] = $datos[0]["Cliente"];
          $TFA[0]["CI_RUC"] = $datos[0]["CI_RUC"];
          $TFA[0]["TD"] = $datos[0]["TD"];
          $TFA[0]["DireccionC"] = $datos[0]["Direccion"];
          $TFA[0]["TelefonoC"] = $datos[0]["Telefono"];
          $TFA[0]["EmailC"] = $datos[0]["Email"];
          $CodSustento = $datos[0]["CodSustento"];

          $TFA[0]["Ruc"] = $datos[0]["CI_RUC"];
          $TFA[0]["TP"] = $parametros['comp'];
          $TFA[0]["Numero"] = $parametros['numero'];
          $TFA[0]["TipoComprobante"] = '0'.$datos[0]["TipoComprobante"];

          // Validar_Porc_IVA $TFA[0]["Fecha"];
          
         // 'Algoritmo Modulo 11 para la clave de la retencion
         // '& Format$(TFA.Vencimiento, "ddmmyyyy")
          $len= strlen($TFA[0]["Retencion"]);
          // $rete = '';
          if($len<9)
          {
            $num_ce = 9-$len;
            $retencion = str_repeat('0',$num_ce);
            $rete = $TFA[0]["Retencion"].$retencion;
          }
          // print_r($rete);die();
          $TFA[0]["ClaveAcceso"] = date("ddmmyyyy", strtotime($TFA[0]['Fecha']->format('Y-m-d')))."07".$parametros['ruc'].$_SESSION['INGRESO']['Ambiente'].$TFA[0]["Serie_R"].$rete."123456781";
          $TFA[0]["ClaveAcceso"] = str_replace('.','1', $TFA[0]['ClaveAcceso']);
          $this->sri->generar_xml_retencion($TFA,$datos);

        }

     }
     function ListarAsientoB()
     {
     	$Opcb = 1;
     	$tbl = $this->modelo->ListarAsientoTemSQL('',$Opcb,false,false);
     	return $tbl;
     }

}
?>