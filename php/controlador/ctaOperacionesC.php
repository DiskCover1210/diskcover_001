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
if(isset($_GET['tipo_pago']))
{
	echo json_encode($controlador->tipo_pago());
}
if(isset($_GET['tip_cuenta']))
{
	echo json_encode($controlador->tip_cuenta($_POST['cuenta']));
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
	if($parametros['OpcG'])
	{
		$TipoDoc = 'G';
	}else
	{
		$TipoDoc = 'D';
	}
	if($parametros['CheqTipoPago'])
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
	}
	$Numero = 0;
	$TipoCta = "N";
	$TipoCta = $parametros['LstSubMod'];
	if($TipoDoc == 'G')
	{
		$TextConcepto = TextoValido($parametros['TextPresupuesto'],,True);
	}else
	{		
		$TextConcepto = TextoValido($parametros['TextPresupuesto']);
	}
    
   $Codigo1 = $parametros['MBoxCta'];
   $Codigo = "C".$Codigo1;
   $Cta_Sup = "C" & CodigoCuentaSup(Codigo1);

  Cuenta = Codigo1 & " - " & TextConcepto.Text
  Mensajes = "Esta seguro de Grabar la cuenta" & vbCrLf _
           & "No. [" & Codigo1 & "] - " & TextConcepto.Text
  Titulo = "Pregunta de grabación"
  If BoxMensaje = vbYes Then
     With AdoCta.Recordset
      If .RecordCount > 0 Then
         .MoveFirst
         .Find ("Codigo like '" & Codigo1 & "' ")
          If Not .EOF Then
             Numero = .Fields("Clave")
             If OpcD.value And Numero = 0 Then
                Numero = ReadSetDataNum("Numero Cuenta", True, True)
             End If
          Else
            .AddNew
            .Fields("Codigo") = Codigo1
             If OpcD.value Then
                Numero = ReadSetDataNum("Numero Cuenta", True, True)
             End If
             AddNewCta TipoCta
             NuevaCta = True
          End If
      Else
         .AddNew
         .Fields("Codigo") = Codigo1
          If OpcD.value Then
             Numero = ReadSetDataNum("Numero Cuenta", True, True)
          End If
          If OpcG.value Then AddNewCta "DG" Else AddNewCta TipoCta
      End If
     ' MsgBox TipoCta
     .Fields("Clave") = Numero
     .Fields("DG") = TipoDoc
     .Fields("TC") = TipoCta
     .Fields("ME") = CheqUS.value
     .Fields("Listar") = CheqFE.value
     .Fields("Mod_Gastos") = CheqModGastos.value
     .Fields("Cuenta") = TextConcepto.Text
     .Fields("Presupuesto") = CCur(TextPresupuesto.Text)
     .Fields("Procesado") = vbTrue
     .Fields("Periodo") = Periodo_Contable
     .Fields("Item") = NumEmpresa
     .Fields("Codigo_Ext") = TxtCodExt
     .Fields("Cta_Acreditar") = CambioCodigoCta(MBoxCtaAcreditar)
     .Fields("Tipo_Pago") = FA.Tipo_Pago
      If OpcNoAplica.value Then
        .Fields("I_E_Emp") = Ninguno
        .Fields("Con_IESS") = False
        .Fields("Cod_Rol_Pago") = Ninguno
      Else
        .Fields("Cod_Rol_Pago") = Rubro_Rol_Pago(TextConcepto)
         If OpcIEmp.value Then
           .Fields("I_E_Emp") = "I"
            If CheqConIESS.value <> 0 Then .Fields("Con_IESS") = True Else .Fields("Con_IESS") = False
         Else
           .Fields("I_E_Emp") = "E"
         End If
      End If
     .Update
      UpdateCta TipoCta
     End With
  End If
  If OpcCxCP Then
     Mensajes = "Ingrese la Cuenta de Interes:"
     Titulo = "Cuenta de Interés para el Prestamo"
     TextoCheque = InputBox(Mensajes, Titulo, "")
     
     If TextoCheque = "" Then TextoCheque = "1"
     MsgBox TextoCheque
     SQL1 = "SELECT * " _
          & "FROM Ctas_Proceso " _
          & "WHERE Item = '" & NumEmpresa & "' " _
          & "AND Periodo = '" & Periodo_Contable & "' " _
          & "ORDER BY T_No "
     SelectDataGrid DGGastos, AdoGastos, SQL1
     If AdoGastos.Recordset.RecordCount > 0 Then
        AdoGastos.Recordset.MoveLast
        Contador = AdoGastos.Recordset.Fields("T_No") + 1
        Si_No = True
        Do While Not AdoGastos.Recordset.EOF And Si_No
           If AdoGastos.Recordset.Fields("Detalle") = Codigo1 Then Si_No = False
           AdoGastos.Recordset.MoveNext
        Loop
        If Si_No Then
           AdoGastos.Recordset.AddNew
           AdoGastos.Recordset.Fields("DC") = "C"
           AdoGastos.Recordset.Fields("T_No") = Contador
           AdoGastos.Recordset.Fields("Detalle") = Codigo1
           AdoGastos.Recordset.Fields("Item") = NumEmpresa
        End If
        AdoGastos.Recordset.Fields("Codigo") = TextoCheque
        AdoGastos.Recordset.Fields("Lst") = False
        AdoGastos.Recordset.Update
     End If
  End If
  If OpcGas Or OpcI Then
     If AdoGastos.Recordset.RecordCount > 0 Then
        AdoGastos.Recordset.MoveFirst
        Codigo1 = CambioCodigoCta(MBoxCta)
        SQL1 = "DELETE * " _
             & "FROM Trans_Presupuestos " _
             & "WHERE Cta = '" & Codigo1 & "' " _
             & "AND Item = '" & NumEmpresa & "' " _
             & "AND Periodo = '" & Periodo_Contable & "' "
        Conectar_Ado_Execute SQL1
        Do While Not AdoGastos.Recordset.EOF
           Valor = AdoGastos.Recordset.Fields("Presupuesto")
           Codigo = AdoGastos.Recordset.Fields("Codigo")
           If Valor >= 0 Then
              AdoPresupuestos.Recordset.AddNew
              AdoPresupuestos.Recordset.Fields("Cta") = Codigo1
              AdoPresupuestos.Recordset.Fields("Codigo") = Codigo
              AdoPresupuestos.Recordset.Fields("Presupuesto") = Valor
              AdoPresupuestos.Recordset.Fields("Item") = NumEmpresa
              AdoPresupuestos.Recordset.Fields("Periodo") = Periodo_Contable
              AdoPresupuestos.Recordset.Update
           End If
           AdoGastos.Recordset.MoveNext
        Loop
     End If
  End If
  If NuevaCta Then
     Control_Procesos Normal, "Nuva Cuenta: " & Codigo1 & " - " & TextConcepto.Text
  Else
     Control_Procesos Normal, "Modificacion de Cuenta: " & Codigo1 & " - " & TextConcepto.Text
  End If
  sSQL = "SELECT * " _
       & "FROM Catalogo_Cuentas " _
       & "WHERE Item = '" & NumEmpresa & "' " _
       & "AND Periodo = '" & Periodo_Contable & "' " _
       & "AND MidStrg(Codigo,1,1) <> 'x' " _
       & "ORDER BY Codigo "
  SelectAdodc AdoCta, sSQL
  IE = TVCatalogo.SelectedItem.Index
  If NuevaCta = False Then TVCatalogo.Nodes(IE).Text = Codigo1 & " - " & TextConcepto.Text
  TVCatalogo.Refresh
  Label6.Visible = True
  Nuevo = False




}



}
?>