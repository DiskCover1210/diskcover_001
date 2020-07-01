<?php 
include('../modelo/ctaOperacionesM.php');
include(dirname(__DIR__).'/db/variables_globales.php');//
/**
 * 
 */
$controlador =  new ctaOperacionesC();
if(isset($_GET['meses']))
{
	echo json_encode($controlador->meses_presu());
}
if(isset($_GET['cuentas']))
{
	echo json_encode($controlador->cuentas());
}
if(isset($_GET['tipo_pago']))
{
	echo json_encode($controlador->tipo_pago());
}
if(isset($_GET['tip_cuenta']))
{
	echo json_encode($controlador->tip_cuenta($_POST['cuenta']));
}
if(isset($_GET['grabar']))
{
	echo json_encode($controlador->grabar_cuenta($_POST['parametros']));
}
if(isset($_GET['presupuesto']))
{
	echo json_encode($controlador->presupuesto($_POST['cod']));
	
}
if(isset($_GET['datos_cuenta']))
{
	echo json_encode($controlador->datos_cuenta($_POST['cod']));
	
}
class ctaOperacionesC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new ctaOperacionesM();
	}


function datos_cuenta($cod)
{
	$dato = $this->modelo->datos_cuenta($cod);
	return $dato;
}

function presupuesto($cod)
{
	$dato = $this->modelo->presupuesto($cod);
	return $dato;
}
function meses_presu()
{
	return meses_del_anio();
}
   
	function cuentas()
	{
		// print_r();

		$p = explode('.',$_SESSION['INGRESO']['Formato_Cuentas']);
		$niveles = count($p);
		//carga las cuentas po seccion c.c.cc.cc.cc.ccc
		$datos = $this->modelo->cargar_cuentas(strlen($p[0]));
		$nivel = array();
		$len =0;
		foreach ($datos as $key => $value) {
		     //carga todos los componentes dentro de una			 
			  $niv = $this->modelo->cargar_niveles($value['Codigo']);
			for ($i=0; $i < $niveles; $i++) {
				$len+=strlen($p[$i]);
				 foreach ($niv as $key => $value2) {
				 	if(strlen($value2['Codigo']) == $len)
				 	{
				 		//if($value2['Codigo'] )
				 		$nivel[$i][$value2['Codigo']] = array('Codigo'=>$value2['Codigo'],'Cuentas'=>$value2['Cuenta'],'ico'=>$value2['TC']);
				 	}
				 }
				$len+=1;
			}
			$len=0;
		}
		// c.c.cc.cc.cc.cccc
  $le=0;
  $nom_nivel='';
  $nombretemp='';
  $tabla = '';
  $tablatemp = '';

  // print_r($nivel);
  
// $temporar = array();
// C.C.CC.CC.CC.CCC
for ($i=$niveles; $i >0; $i--){
	if(isset($nivel[$i]))
	{		
		$le=strlen($p[$i]);
	   foreach ($nivel[$i] as $key => $value) {		
	   	  	$ni = substr($value['Codigo'], 0, (-1*$le)-1);
	   	  	if($nom_nivel == '')
	   	  	{
	   	  		$nombretemp = $ni;
	   	  		$nom_nivel = $ni;	
	   	  		$n = $ni;   	  		
	   	  		$tabla.='<li><a href="javascript:void();">'.$nom_nivel.'-</a><ul>';
	   	  		$tabla.='<li><a href="#">'.$value['Codigo'].'- '.$value['Cuentas'].'</a></li>';
	   	  	}else
	   	  	{
	   	  		if($ni==$nombretemp)
	   	  		{
	   	  			$tabla.='<li><a href="#">'.$value['Codigo'].'- '.$value['Cuentas'].'</a></li>';
	   	  		}else
	   	  		{
	   	  			$tabla='';
	   	  			$nombretemp = $ni;
	   	  		    $nom_nivel = $ni;
	   	  		    $tabla.='<li><a href="javascript:void();">'.$nom_nivel.'-</a><ul>';
	   	  		    $tabla.='<li><a href="#">'.$value['Codigo'].'- '.$value['Cuentas'].'</a></li>';		   	  		   
	   	  		}

	   	  	}
	   	  	$temporar[$nombretemp]= array($tabla.'</ul></li fin>');
	   }  
	   $tabla='';	   	 
   }

}

$corte ='';
$valor='';
$co = 0;
$corte_ante='';
$titulo='';

// print_r($nivel);
// die();

foreach ($temporar as $key => $value) {
	$tablatemp.=$value[0]; 
}

foreach ($temporar as $key => $value) {
	
	if(strlen($key) != 1)
	{
	$posicion_coincidencia = strpos($tablatemp, '</li fin>');
	if($posicion_coincidencia !== false)
	{	 
		$remplazo = substr($tablatemp,0,$posicion_coincidencia).'</li fin>';
		$tablatemp = str_replace($remplazo,'',$tablatemp);
	}
	$valor = $key.'- ';
	// print_r($tablatemp);
	$remplazar_en = strpos($tablatemp, $valor);
	if($remplazar_en !== false)
	{
		$corte = substr($tablatemp,$remplazar_en);
		$numc = strlen($corte);
			$hast_titulo = strpos($corte,'</a></li>');
			 if($hast_titulo !== false)
			 {
			 	$titulo = substr($corte,0,$hast_titulo);
			 	
			 }
			  $continua = strpos($corte,'</li>');
			 if($continua !== false)
			 {
			 	
			 	$corte = substr($corte,$continua+5);
			 	 
			 }
			 $corte_ante = substr($tablatemp,0,$remplazar_en);
			 $corte_ante = substr($corte_ante,0,-16);

			 $seccion = str_replace($key.'-',$titulo,$remplazo);
			
			 
			 $seccion = str_replace('</li fin>','</li>',$seccion);
			 $tablatemp = $corte_ante.''.$seccion.''.$corte;
			
	}	

  }

}

foreach ($datos as $key => $value) {
	if(is_numeric($value['Codigo']))
	{
		$posicion_coincidencia = strpos($tablatemp,'>'.$value['Codigo'].'-<');
		if($posicion_coincidencia !== false)
		{	 		
			$tablatemp = str_replace('>'.$value['Codigo'].'-<','>'.$value['Codigo'].'- '.$value['Cuenta'].'<',$tablatemp);	
		}else
		{

			// print_r($key);
			$parte_tabla = explode('</li fin>', $tablatemp);
			$tablatemp = '';
			foreach ($parte_tabla as $key1 => $value1) {
				if($key == $key1)
				{
					$tablatemp.='<li class="fa"><a href="#">'.$value['Codigo'].'- '.$value['Cuenta'].'</a></li fin>'.$parte_tabla[$key];
				}else
				{
					$tablatemp.=$parte_tabla[$key1];
				}
			}

			// print_r($tablatemp);
			// die();
			//$parte_tabla= array_map('trim', explode('</li fin>', $tablatemp));
			//$tablatemp.='<li><a href="#">'.$value['Codigo'].'- '.$value['Cuenta'].'</a></li>';
		}

		// $tablatemp = str_replace($value['Codigo'].'- '.$value['Cuenta'],$value['Codigo'].'- ',$tablatemp);	
	}
}

 $tabla1 ='<div class="menujq"><ul>';
 $tabla1.=$tablatemp;
 $tabla1.='</ul></div><script  src="../../lib/dist/js/script_acordeon.js"></script>';
 $tabla = $tabla1;
return $tabla;
	}

function tipo_pago()
{
	$datos = $this->modelo->tipo_pago_();
	return $datos;
}

function tip_cuenta($cuenta)
{
	$datos = TiposCtaStrg($cuenta);
	return $datos;
}


function grabar_cuenta($parametros)
{
	// print_r($parametros);
	$editar = false;
	$ID='';
	if($parametros['OpcG']=='true')
	{
		$TipoDoc = 'G';
	}else
	{
		$TipoDoc = 'D';
	}
	if($parametros['CheqTipoPago'] == 'true')
	{
		$FA_Tipo_Pago = $parametros['DCTipoPago'];
	}else
	{
		$FA_Tipo_Pago = "00";
	}

	$NuevaCta = False;
	$TextPresupuesto = TextoValido($parametros['TextPresupuesto']);
	if($parametros['LabelCtaSup'] == '')
	{
		$LabelCtaSup = '0';
	}else
	{
		$parametros['LabelCtaSup'] = substr($parametros['LabelCtaSup'],0,-1);  
	}
	$Numero = 0;
	$TipoCta = "N";
	$TipoCta = $parametros['LstSubMod'];
	if($TipoDoc == 'G')
	{
		$TextConcepto = TextoValido($parametros['TextConcepto'],false,True);
	}else
	{		
		$TextConcepto = TextoValido($parametros['TextConcepto']);
	}
	$formato = $_SESSION['INGRESO']['Formato_Cuentas'];
	$forma = explode('.',$formato);
	$int_cuenta =explode('.',substr($parametros['MBoxCta'],0,-1));
	$cuent = '';
	foreach ($int_cuenta as $key => $value) {
		if(strlen($value) == strlen($forma[$key]))
		{
			$cuent.=$value.'.';
		}else
		{
			$n = str_repeat("0", strlen($forma[$key])-strlen($value));
			$cuent.=$n.''.$value.'.';
		}
	}
	$parametros['MBoxCta'] =$cuent;
   $Codigo1 = substr($parametros['MBoxCta'],0,-1);  
   $Codigo = "C".$Codigo1;
   $Cta_Sup = "C".$parametros['LabelCtaSup'];
   $Cuenta = $Codigo1." - ".$TextConcepto;
  // Mensajes = "Esta seguro de Grabar la cuenta" & vbCrLf _
  //          & "No. [" & Codigo1 & "] - " & TextConcepto.Text
  // Titulo = "Pregunta de grabación"


  $cuenta_exist = $this->modelo->cta_existente();
  if(count($cuenta_exist)!=0)
  {
  	$cuenta_exist_1 = $this->modelo->cta_existente($Codigo1);
  	if(count($cuenta_exist_1) !=0)
  	{  	
  		
  	 $Numero = $cuenta_exist_1[0]["Clave"];
  	 $ID =  $cuenta_exist_1[0]["ID"];
	 $editar = true;
             if($parametros['OpcD'] == 'true' And $Numero = 0)
             {
             	$Numero = ReadSetDataNum("Numero Cuenta", True, True);
             }
  	}else
  	{
  		$dato[17]['campo']='Codigo';
        $dato[17]['dato']=strval($Codigo1);
        if($parametros['OpcD'] == 'true')
           {
                $Numero = ReadSetDataNum("Numero Cuenta",True,True);
           }
        $NuevaCta = True;
    }
  }else
  {
  	$dato[17]['campo']='Codigo';
    $dato[17]['dato']=$Codigo1;

    if($parametros['OpcD'] == 'true')
    {
    	 $Numero = ReadSetDataNum("Numero Cuenta", True, True);
    }
    // if($parametros['OpcG'] == true)
    // {
    // 	//AddNewCta "DG";
    // }else
    // {
    // 	//AddNewCta $TipoCta;

    // }  
  	 
  }
 
     // ' MsgBox TipoCta'
      $dato[0]['campo']='Clave';
      $dato[0]['dato']=$Numero;
      $dato[1]['campo']='DG';
      $dato[1]['dato']=$TipoDoc;
      $dato[2]['campo']='TC';
      $dato[2]['dato']=$TipoCta;
      $dato[3]['campo']='ME';
      $dato[3]['dato']= (int)($parametros['CheqUS'] === 'true');
      $dato[4]['campo']='Listar';
      $dato[4]['dato']=(int)($parametros['CheqFE'] === 'true');
      $dato[5]['campo']='Mod_Gastos';
      $dato[5]['dato']=(int)($parametros['CheqModGastos']=== 'true');
      $dato[6]['campo']='Cuenta';
      $dato[6]['dato']=$TextConcepto;
      $dato[7]['campo']='Presupuesto';
      $dato[7]['dato']=$TextPresupuesto;
      $dato[8]['campo']='Procesado';
      $dato[8]['dato']=True;
      $dato[9]['campo']='Periodo_Contable';
      $dato[9]['dato']=$_SESSION['INGRESO']['periodo'];
      $dato[10]['campo']='Item';
      $dato[10]['dato']=$_SESSION['INGRESO']['item'];
      $dato[11]['campo']='Codigo_Ext';
      $dato[11]['dato']=$parametros['TxtCodExt'];
      $dato[12]['campo']='Cta_Acreditar';
      $dato[12]['dato']=$parametros['MBoxCtaAcreditar'];
      $dato[13]['campo']='Tipo_Pago';
      $dato[13]['dato']=$FA_Tipo_Pago;

     if($parametros['OpcNoAplica'] == 'true')
     {
        $dato[14]['campo']='I_E_Emp';
        $dato[14]['dato']= G_NINGUNO;
        $dato[15]['campo']='Con_IESS';
        $dato[15]['dato']= (int)('false' === 'true');;
        $dato[16]['campo']='Cod_Rol_Pago';
        $dato[16]['dato']= G_NINGUNO;

      }else
     {
         $dato[16]['campo']='Cod_Rol_Pago';
         $dato[16]['dato']= Rubro_Rol_Pago($TextConcepto);
         // print_R(Rubro_Rol_Pago($TextConcepto));
         // die();
     	   if($parametros['OpcIEmp']=='true')
     	   {
     		$dato[14]['campo']='I_E_Emp';
     		$dato[14]['dato']= 'I';
     		if($parametros['CheqConIESS'] != 'false')
     		{
     			$dato[15]['campo']='Con_IESS';
     			$dato[15]['dato']=(int)('true'=== 'true');;
     		}else
     		{
     			$dato[15]['campo']='Con_IESS';
     			$dato[15]['dato']= (int)('false'=== 'true');
     		}
     	   }else
     	   {
     	   	$dato[14]['campo']='I_E_Emp';
     	    $dato[14]['dato']='E';     		
     	   }
     } 

      $dato[18]['campo']='CC';
      $dato[18]['dato']=$parametros['TxtCodExt'];


      // print_r($dato);
      // print_r((int)('true' === 'true'));
      // print_r('-');
      // print_r((int)('false' === 'true'));
      // die();     
      
      if($editar == true)
      {
      	
      	$where[0]['campo']='ID';
      	$where[0]['valor']=strval($ID);
      	if(update_generico($dato,'Catalogo_Cuentas',$where) == 1)
      	{
      		return 1;
      	}else
      	{
      		return -1;
      	}


      }else
      {
      	if(insert_generico('Catalogo_Cuentas',$dato) == null)
      	{
      		return 1;
      	}else
      	{
      		return -1;
      	}

      }
//      .Update



//       UpdateCta TipoCta
//      End With
//   End If
// //fin de actualizacion

//   if($parametros['OpcCxCP'])
//   {
//   	 Mensajes = "Ingrese la Cuenta de Interes:"
//      Titulo = "Cuenta de Interés para el Prestamo"
//      TextoCheque = InputBox(Mensajes, Titulo, "")
//      if($parametros['TextoCheque'] == '')
//      {
//      	$TextoCheque = 1;
//      }
//      $DGGastos = $this->modelo->DGGastos();

//   }
//   If OpcCxCP Then
     
//      If TextoCheque = "" Then TextoCheque = "1"
//      MsgBox TextoCheque
//      SelectDataGrid DGGastos, AdoGastos, SQL1
//      If AdoGastos.Recordset.RecordCount > 0 Then
//         AdoGastos.Recordset.MoveLast
//         Contador = AdoGastos.Recordset.Fields("T_No") + 1
//         Si_No = True
//         Do While Not AdoGastos.Recordset.EOF And Si_No
//            If AdoGastos.Recordset.Fields("Detalle") = Codigo1 Then Si_No = False
//            AdoGastos.Recordset.MoveNext
//         Loop
//         If Si_No Then
//            AdoGastos.Recordset.AddNew
//            AdoGastos.Recordset.Fields("DC") = "C"
//            AdoGastos.Recordset.Fields("T_No") = Contador
//            AdoGastos.Recordset.Fields("Detalle") = Codigo1
//            AdoGastos.Recordset.Fields("Item") = NumEmpresa
//         End If
//         AdoGastos.Recordset.Fields("Codigo") = TextoCheque
//         AdoGastos.Recordset.Fields("Lst") = False
//         AdoGastos.Recordset.Update
//      End If
//   End If
//   If OpcGas Or OpcI Then
//      If AdoGastos.Recordset.RecordCount > 0 Then
//         AdoGastos.Recordset.MoveFirst
//         Codigo1 = CambioCodigoCta(MBoxCta)
//         SQL1 = "DELETE * " _
//              & "FROM Trans_Presupuestos " _
//              & "WHERE Cta = '" & Codigo1 & "' " _
//              & "AND Item = '" & NumEmpresa & "' " _
//              & "AND Periodo = '" & Periodo_Contable & "' "
//         Conectar_Ado_Execute SQL1
//         Do While Not AdoGastos.Recordset.EOF
//            Valor = AdoGastos.Recordset.Fields("Presupuesto")
//            Codigo = AdoGastos.Recordset.Fields("Codigo")
//            If Valor >= 0 Then
//               AdoPresupuestos.Recordset.AddNew
//               AdoPresupuestos.Recordset.Fields("Cta") = Codigo1
//               AdoPresupuestos.Recordset.Fields("Codigo") = Codigo
//               AdoPresupuestos.Recordset.Fields("Presupuesto") = Valor
//               AdoPresupuestos.Recordset.Fields("Item") = NumEmpresa
//               AdoPresupuestos.Recordset.Fields("Periodo") = Periodo_Contable
//               AdoPresupuestos.Recordset.Update
//            End If
//            AdoGastos.Recordset.MoveNext
//         Loop
//      End If
//   End If
//   If NuevaCta Then
//      Control_Procesos Normal, "Nuva Cuenta: " & Codigo1 & " - " & TextConcepto.Text
//   Else
//      Control_Procesos Normal, "Modificacion de Cuenta: " & Codigo1 & " - " & TextConcepto.Text
//   End If
//   sSQL = "SELECT * " _
//        & "FROM Catalogo_Cuentas " _
//        & "WHERE Item = '" & NumEmpresa & "' " _
//        & "AND Periodo = '" & Periodo_Contable & "' " _
//        & "AND MidStrg(Codigo,1,1) <> 'x' " _
//        & "ORDER BY Codigo "
//   SelectAdodc AdoCta, sSQL
//   IE = TVCatalogo.SelectedItem.Index
//   If NuevaCta = False Then TVCatalogo.Nodes(IE).Text = Codigo1 & " - " & TextConcepto.Text
//   TVCatalogo.Refresh
//   Label6.Visible = True
//   Nuevo = False

}



}
?>