<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
if(!isset($_SESSION)) 
	{ 		
			@session_start();
	}
//require_once("../../lib/excel/plantilla.php");
require_once(dirname(__DIR__,2)."/lib/excel/plantilla.php");
require_once(dirname(__DIR__,1)."/db/db.php");
require_once(dirname(__DIR__,1)."/db/variables_globales.php");

//Lutgarda6018
//require_once("../../diskcover_lib/fpdf/reporte_de.php");
//C:\wamp64\www\diskcover_lib\excel
//llamar funcion generica digito verificadorf
if(isset($_POST['RUC']) AND !isset($_POST['submitweb'])) 
{
	$pag=$_POST['vista'];
	$ruc=$_POST['RUC'];
	$idMen=$_POST['idMen'];
	$item=$_POST['item'];
	digito_verificadorf($ruc,$pag,$idMen,$item);
}

function ip()
{
  // print_r($_SESSION);die();
   $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
  echo $ipaddress;

}

//Configuración del algoritmo de encriptación

//Debes cambiar esta cadena, debe ser larga y unica
//nadie mas debe conocerla
$clave  = 'Una cadena, muy, muy larga para mejorar la encriptacion';

//Metodo de encriptación
$method = 'aes-256-cbc';

// Puedes generar una diferente usando la funcion $getIV()
$iv = base64_decode("C9fBxl1EWtYTL1/M8jfstw==");

 /*
 Encripta el contenido de la variable, enviada como parametro.
  */
 $encriptar = function ($valor) use ($method, $clave, $iv) {
     return openssl_encrypt ($valor, $method, $clave, false, $iv);
 };

 /*
 Desencripta el texto recibido
 */
 $desencriptar = function ($valor) use ($method, $clave, $iv) {
     $encrypted_data = base64_decode($valor);
     return openssl_decrypt($valor, $method, $clave, false, $iv);
 };

 /*
 Genera un valor para IV
 */
 $getIV = function () use ($method) {
     return base64_encode(openssl_random_pseudo_bytes(openssl_cipher_iv_length($method)));
 };
function control_procesos($TipoTrans,$Tarea,$opcional_proceso)
{  
  $TMail_Credito_No = G_NINGUNO;
  if($NumEmpresa=="")
  {
    $NumEmpresa = G_NINGUNO;
  }
  if($TMail == "")
  {
    $TMail = G_NINGUNO;
  }
  if($Modulo <> G_NINGUNO AND $TipoTrans<>G_NINGUNO AND $NumEmpresa<>G_NINGUNO)
  {
    if($Tarea == G_NINGUNO)
    {
      $Tarea = "Inicio de Sección";
    }else
    {
      $Tarea = substr($Tarea,0,60);
    }
    $proceso = substr($opcional_proceso,0,60);
    $NombreUsuario1 = substr($NombreUsuario, 0, 60);
    $TipoTrans = $TipoTrans;
    $Mifecha1 = date("Y-m-d");
    $MiHora1 = date("H:i:s");
    $$CodigoUsuario='';
    if($Tarea == "")
    {
      $Tarea = G_NINGUNO;
    }
    if($opcional_proceso=="")
    {
      $opcional_proceso = G_NINGUNO;
    }
    $sql = "INSERT INTO acceso_pcs (IP_Acceso,CodigoU,Item,Aplicacion,RUC,Fecha,Hora,
             ES,Tarea,Proceso,Credito_No,Periodo)VALUES('172.168.2.20','".$CodigoUsuario."','".$NumEmpresa."',
             '".$Modulo."','".$_SESSION['INGRESO']['Id']."','".$Mifecha1."','".$MiHora1."','".$TipoTrans."','".$Tarea."','".$Proceso."','".$TMail_Credito_No."','".$_SESSION['INGRESO']['periodo']."');";

  }
}

function Actualizar_Datos_ATS_SP($Items,$MBFechaI,$MBFechaF,$Numero)
{
  $respuesta = 1;
  $conn = new Conectar();
  $cid=$conn->conexion();
  $FechaIni = $MBFechaI;
  $FechaFin = $MBFechaF;

$parametros = array(
array(&$Items, SQLSRV_PARAM_IN),
array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN),
array(&$FechaIni, SQLSRV_PARAM_IN),
array(&$FechaFin, SQLSRV_PARAM_IN),
array(&$Numero, SQLSRV_PARAM_IN)
);
$sql = "EXEC sp_Actualizar_Datos_ATS @Item= ?,@Periodo=?,@FechaDesde=?,@FechaHasta=?,@Numero=?";
$stmt = sqlsrv_prepare($cid, $sql, $parametros);
    if (!sqlsrv_execute($stmt)) {
   
      echo "Error en consulta PA.\n";  
      $respuesta = -1;
      die( print_r( sqlsrv_errors(), true));  
    die;
}

    return $respuesta;
}


function Fecha_Del_AT($ATMes, $ATAno)
{
$fechas_ats = array();
$FechaInicial='';$FechaMitad='';$FechaFinal='';
if($ATMes == 'Todos')
{
  $FechaInicial='01/01/'.$ATAno;
  $FechaMitad = '15/01/'.$ATAno;
  $FechaFinal =date('dd/mm/yyy');
  // $fechas_ats = array('FechaIni'=>$FechaInicial,'FechaMit'=>$FechaMitad,'FechaFin'=>$FechaFin);
}else
{
  
     $FechaInicial='01/'.$ATMes.'/'.$ATAno;
     $FechaMitad = '15/'.$ATMes.'/'.$ATAno;
     $FechaFinal = date("d",(mktime(0,0,0,$ATMes+1,1,$ATAno)-1)).'/'.$ATMes.'/'.$ATAno;     
     // $fechas_ats = array('FechaIni'=>$FechaInicial,'FechaMit'=>$FechaMitad,'FechaFin'=>$FechaFinal);
 }
 if($_SESSION['INGRESO']['Tipo_Base'] == 'SQL SERVER')
  {
    $mitad = DateTime::createFromFormat('d/m/Y', $FechaMitad);
    $final = DateTime::createFromFormat('d/m/Y', $FechaFinal);
    $inicial = DateTime::createFromFormat('d/m/Y',$FechaInicial);

       $FechaInicial=$inicial->format('Ymd');
       $FechaMitad=$mitad->format('Ymd');
       $FechaFinal=$final->format('Ymd');


  }else
  {
    $FechaInicial=date('Y-m-d',strtotime($FechaInicial));
    $FechaMitad = date('Y-m-d',strtotime($FechaMitad));
    $FechaFinal = date('Y-m-d',strtotime($FechaFinal));

  }
  $fechas_ats = array('FechaIni'=>$FechaInicial,'FechaMit'=>$FechaMitad,'FechaFin'=>$FechaFinal);
  return $fechas_ats;
}

function copiar_tabla_empresa($NombreTabla,$OldItemEmpresa,$PeriodoCopy,$si_periodo,$AdoStrCnnCopy=false,$NoBorrarTabla=false)
{

 $conn = new Conectar();
 $cid=$conn->conexion();

 $NombreTabla = trim($NombreTabla);
 $campos_db = dimenciones_tabla($NombreTabla);

 // 'Borramos datos si existen en la empresa nueva'
 if($NoBorrarTabla)
 {
   $sqld = "DELETE  FROM ".$NombreTabla." WHERE Item = '".$_SESSION['INGRESO']['item']."' ";
   if($si_periodo)
   {
    if($PeriodoCopy !='.')
    {
       $sqld .= " AND Periodo = '".$PeriodoCopy."' ";
    }else
    {
      $sqld .=" AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
    }
   }
 }

 $stmt = sqlsrv_query($cid, $sqld);
  if( $stmt === false)  
   {  
      echo "Error en consulta PA.\n";  
      return '';
      die( print_r( sqlsrv_errors(), true));  
    }

  if($PeriodoCopy == '.')
  {
    $PeriodoCopy = date('Y');
  }

 
$tabla_sistema = '';
foreach ($campos_db as $key => $value) {
  if($value->COLUMN_NAME !='ID' && $value->COLUMN_NAME !='Periodo' &&$value->COLUMN_NAME !='Item')
  {
    $tabla_sistema.=$value->COLUMN_NAME.',';
  }
}
$tabla_sistema = substr($tabla_sistema,0,-1);


   $sql1 = "select  '".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['periodo']."',".$tabla_sistema." FROM ".$NombreTabla."  WHERE Item = '".$OldItemEmpresa."'";
   $sql = "INSERT INTO ".$NombreTabla." (Item,Periodo,".$tabla_sistema.") ";
         if($si_periodo)
          {
            if(checkdate('12',$PeriodoCopy, '31'))
              {
                $sql1 .= " AND Periodo = '".$PeriodoCopy."' ";
              }
            else
              {
                $sql1 .=" AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
              }
          }else
          {
             $sql1 = "select '".$_SESSION['INGRESO']['item']."',".$tabla_sistema." FROM ".$NombreTabla."  WHERE Item = '".$OldItemEmpresa."'";
            $sql = "INSERT INTO ".$NombreTabla." (Item,".$tabla_sistema.") ";
          }


 $sql = $sql.$sql1;
  $stmt = sqlsrv_query($cid, $sql);
   if( $stmt === false)  
        {  
         // echo "Error en consulta PA.\n";  
         return -1;
         die( print_r( sqlsrv_errors(), true));  
        } else
        {
          return 1;
        } 




}

function Rubro_Rol_Pago($Detalle_Rol)
{


	$Rubro_Rol_Pago = '';
	$cod = array();
	$Det_Rol = str_replace( ".", "",$Detalle_Rol);
    $Det_Rol = str_replace( "/", "",$Det_Rol);
    $Det_Rol = str_replace( "Á", "A",$Det_Rol);
    $Det_Rol = str_replace( "É", "E",$Det_Rol);
    $Det_Rol = str_replace( "Í", "I",$Det_Rol);
    $Det_Rol = str_replace( "Ó", "O",$Det_Rol);
    $Det_Rol = str_replace( "Ú", "U",$Det_Rol);
    $Det_Rol = str_replace( "Ñ", "N",$Det_Rol);
    $Det_Rol = str_replace( "á", "a",$Det_Rol);
    $Det_Rol = str_replace( "é", "e",$Det_Rol);
    $Det_Rol = str_replace( "í", "i",$Det_Rol);
    $Det_Rol = str_replace( "ó", "o",$Det_Rol);
    $Det_Rol = str_replace( "ú", "u",$Det_Rol);
    $Det_Rol = str_replace( "ñ", "n",$Det_Rol);

    $cod = explode(' ', $Det_Rol);

    // $cod[0] = trim($Det_Rol);
    // $Det_Rol =substr($Det_Rol,strlen($cod[0])+1,strlen($Det_Rol));
    // $cod[1] = trim($Det_Rol);
    // $Det_Rol =substr($Det_Rol,strlen($cod[1])+1,strlen($Det_Rol));
    // $cod[2] = trim($Det_Rol);
    // $Det_Rol =substr($Det_Rol,strlen($cod[2])+1,strlen($Det_Rol));
    // $cod[3] = trim($Det_Rol);


    $Det_Rol = '';
    // if(strlen(trim($cod[0]))>=2)
    // {
    // 	$Det_Rol = $Det_Rol.''.trim(substr($cod[0],0,3)).'_';
    // }      
    foreach ($cod as $key => $value) {
    	if(strlen(trim($value))>=2)
    	{
    		if($key == 0)
    		{
    			$Det_Rol .=trim(substr($value, 0, 3))."_";
    		}else
    		{
    			$Det_Rol .=trim(substr($value, 0, 2))."_";
    		}   		
    		 
    	}

    }     $Det_Rol = trim(substr($Det_Rol, 0,-1));
    // $Rubro_Rol_Pago = $Det_Rol;
   $Rubro_Rol_Pago = $Det_Rol;
    return $Rubro_Rol_Pago;

}



function ReadSetDataNum($sqls,$ParaEmpresa =false,$Incrementar = false)
{
  $result = '';
  $NumCodigo = 0;
  $NuevoNumero = False;
  $FechaComp = '';
  if(strlen($FechaComp) < 10 || $FechaComp == '00/00/0000')
  {
  	$FechaComp =date('d/m/Y');
  }

  if($ParaEmpresa)
  {
  	$NumEmpA = $_SESSION['INGRESO']['item'];
  }else
  {
  	$NumEmpresa = '000';
  }

  $Num_Meses_CI=false;
  $Num_Meses_CD=false;  
  $Num_Meses_CE=false;
  $Num_Meses_ND=false;
  $Num_Meses_NC=false;
    

  if($sqls != '')
  {
    $MesComp = '';
    if(strlen($FechaComp) >= 10)
    {
    	$MesComp = date('m');;
    }
    if($MesComp == '')
    {
    	$MesComp = '01';
    }
    if($Num_Meses_CD and $sqls == 'Diario')
    {
    	$SQLs = $MesComp.''.$sqls;
      $Si_MesComp = True;
    }
    if($Num_Meses_CI and $sqls == 'Ingresos')
    {
    	$SQLs = $MesComp.''.$sqls;
      $Si_MesComp = True;
    }
    if($Num_Meses_CE and $sqls == 'Egresos')
    {
    	$SQLs = $MesComp.''.$sqls;
      $Si_MesComp = True;
    }
    if($Num_Meses_ND and $sqls == 'NotaDebito')
    {
    	$SQLs = $MesComp.''.$sqls;
      $Si_MesComp = True;
    }
    if($Num_Meses_NC and $sqls == 'NotaCredito')
    {
    	$SQLs = $MesComp.''.$sqls;
      $Si_MesComp = True;
    }
  }

  if($sqls !='')
  {
    $MesComp = "";
    if(strlen($FechaComp) >= 10)
    {
    	$MesComp = date('m');
    }
    if($MesComp == '')
    {
    	$MesComp = '01';
    }
    $conn = new Conectar();
	  $cid=$conn->conexion();
    $sql = "SELECT Numero, ID FROM Codigos
            WHERE Concepto = '".$sqls. "' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']. "'
            AND Item = '".$_SESSION['INGRESO']['item']."'" ;
		$stmt = sqlsrv_query($cid, $sql);
	  if( $stmt === false)  
	  {  
		  echo "Error en consulta PA.\n";  
		  return '';
		  die( print_r( sqlsrv_errors(), true));  
	  }   
    //echo $sql;
	  $result = array();	
	  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	  {
	    $result[] =$row;
		  //echo $row[0];
	  }

	  if(count($result)>0)
	  {
	    $NumCodigo = $result[0]["Numero"];

	  }else
	  {
	    $NuevoNumero = True;
      $NumCodigo = 1;
      if($Num_Meses_CD && $Si_MesComp){$NumCodigo = intval($MesComp.''.'000001');}
      if($Num_Meses_CI && $Si_MesComp){$NumCodigo = intval($MesComp.''.'000001');}
      if($Num_Meses_CE && $Si_MesComp){$NumCodigo = intval($MesComp.''.'000001');}
      if($Num_Meses_ND && $Si_MesComp){$NumCodigo = intval($MesComp.''.'000001');}
      if($Num_Meses_NC && $Si_MesComp){$NumCodigo = intval($MesComp.''.'000001');}
	  }
	  
    if($NumCodigo > 0)
	  {
	    if($NuevoNumero)
	    {
	    	$Strgs = "INSERT INTO Codigos (Periodo,Item,Concepto,Numero)
                VALUES ('".$_SESSION['INGRESO']['periodo']."','".$_SESSION['INGRESO']['item']."','".$sqls."',".$NumCodigo.") ";
                //faltra ejecutar
	    }
	    if($Incrementar)
	    {
	    	$Strgs = "UPDATE Codigos 
                SET Numero = Numero + 1 
                WHERE Concepto = '".$sqls."'
                AND Periodo = '" .$_SESSION['INGRESO']['periodo']."' 
                AND Item = '".$_SESSION['INGRESO']['item']. "' ";
	    }
	  }
  }
  return $NumCodigo;
}


function paginancion($tabla,$function,$pag=false,$where=false)
{
  $num_index = 6;

  $ini=0;
  $fin = 6;

  $sql = 'SELECT count(*) as total FROM '.$tabla.' WHERE 1=1 ';
  $conn = new Conectar();
  $cid=$conn->conexion();
  // print_r($sql);die();
  $stmt = sqlsrv_query($cid, $sql);
    if($stmt === false)  
      {  
        echo "Error en consulta PA.\n";  
        return '';
        die( print_r( sqlsrv_errors(), true));  
      }   
      $result = 0;  
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
        {
        $result =$row;
        //echo $row[0];
        }
       $total = $result['total'];
       // print_r($total);die();
       $partes = $total/25;
  $html='<div class="row text-right" id="paginacion"><ul class="pagination">
  <li class="paginate_button" onclick="$(\'#txt_pag\').val(this.value);'.$function.'();" value="0"><a href="#">Inicio</a></li>';

  $index_actual = ($pag/25)+1;
  if($index_actual % $num_index == 0)
  {
    $ini = $index_actual-1;
    $fin = $index_actual+6;
  }else
  {
    $secc = intval(($pag/25)/6);
    // print_r($secc);die();
    $i=0;
    while ($secc>$i) {
      // print_r('expression');
      $ini = $fin-1;
      $fin = $fin+6;
      $i++;
    }
  }

  

  for($i=$ini;$i<$fin;$i++)
  {
    $valor =$i*25;
    $index = $i+1;

    if($valor==$pag)
    {
      $html.='<li class="paginate_button active" onclick="$(\'#txt_pag\').val(this.value);'.$function.'();" value="'.$valor.'"><a href="#">'.$index.'</a></li>';
    }else
    {
      $html.='<li class="paginate_button " onclick="$(\'#txt_pag\').val(this.value);'.$function.'();" value="'.$valor.'"><a href="#">'.$index.'</a></li>';
    }
  }
  $final =  intval(($total-25));
  $html.=' <li class="paginate_button" onclick="$(\'#txt_pag\').val(this.value);'.$function.'();" value="'.$final.'"><a href="#">Fin</a></li></ul></div>';

  // print_r($html);die();
       return $html;       

}

// texto valido
function TextoValido($texto,$numero=false,$Mayusculas=false,$NumeroDecimales=false)
{
	$result = '';
	if($Mayusculas)
	{
		$result = strtoupper($texto);
	}
	if($numero)
	{
		if($texto == '')
		{
			$texto = 0;
		}
		if(IsNumeric($texto))
		{
			$result = round($texto, 2, PHP_ROUND_HALF_DOWN);
			switch ($NumeroDecimales) {
				case 0:
				$result = round($texto, 2, PHP_ROUND_HALF_DOWN);
				break;
				
				case $NumeroDecimales > 2:
				$result = round($texto, $NumeroDecimales, PHP_ROUND_HALF_DOWN);
				break;
			}
		}
	}else
	{
		if($texto == '')
		{
			$result = 'Ninguno';
		}else
		{
			$result = $texto;
		}

	}
  //print_r($result);
	return $result;
}

//string de tipo de cuenta
function TiposCtaStrg($cuenta) {
	$Resultado='NINGUNA';
   switch ($cuenta){
   	case 'value':
   		# code...
   		break;
   	case "1":  
   	$Resultado = "ACTIVO";
   	break;
    case "2":  
    $Resultado = "PASIVO";
    break;
    case "3":  
    $Resultado = "CAPITAL";
    break;
    case "4":  
    $Resultado = "INGRESO";
    break;
    case "5":  
    $Resultado = "EGRESO";
    break;
   }
   return $Resultado;
}

//enviar emails
  function enviar_email($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$EMAIL_CONEXION,$EMAIL_CONTRASEÑA)
  {

  	$respuesta=true;
  	//$correo='ejfc19omoshiroi@gmail.com,ejfc_omoshiroi@hotmail.com';
  	//$to =explode(',', $correo);
  	$to =explode(',', $to_correo);
  
   foreach ($to as $key => $value) {
 //  	print_r($value);
  		 $mail = new PHPMailer();
       $mail->isSMTP();
	     $mail->SMTPDebug = 0;
	     $mail->Host = "smtp.gmail.com";
	     $mail->Port =  465;
	     //$mail->SMTPSecure = "none";
	     $mail->SMTPAuth = true;
	     $mail->SMTPSecure = 'ssl';
	     $mail->Username = $EMAIL_CONEXION;  //EMAIL_CONEXION DE TABLA EMPRESA
	     $mail->Password = $EMAIL_CONTRASEÑA; //EMAIL_CONTRASEÑA DE LA TABLA EMPRESA
	     $mail->setFrom($correo_apooyo,$nombre);

         $mail->addAddress($value);
         $mail->Subject = $titulo_correo;
         $mail->Body = $cuerpo_correo; // Mensaje a enviar


         if($archivos)
         {
          foreach ($archivos as $key => $value) {
           if(file_exists('../vista/TEMP/'.$value))
            {
          //		print_r('../vista/TEMP/'.$value);
          
         	  $mail->AddAttachment('../vista/TEMP/'.$value);
             }          
          }         
        }
          if (!$mail->send()) 
          {
          	$respuesta = false;
     	  }
    }
    print_r($mail->ErrorInfo);die();
    return $respuesta;
  }

  function mes_X_nombre($num)
  {
  	//print_r($num);
  	$monthNameSpanish='';
  	switch($num)
  	 {   
       case 1:
       $monthNameSpanish = "Enero";
       break;

       case 2:
       $monthNameSpanish = "Febrero";
       break;

       case 3:
       $monthNameSpanish = "Marzo";
       break;

       case 4:
       $monthNameSpanish = "Abril";
       break;

       case 5:
       $monthNameSpanish = "Mayo";
       break;

       case 6:
       $monthNameSpanish = "Junio";
       break;

       case 7:
       $monthNameSpanish = "Julio";
       break;

       case 8:
       $monthNameSpanish = "Agosto";
       break;

       case 9:
       $monthNameSpanish = "Septiembre";
       break;

        case 10:
       $monthNameSpanish = "Octubre";
       break;

       case 11:
       $monthNameSpanish = "Noviembre";
       break;

       case 12:
       $monthNameSpanish = "Diciembre";
       break;
    }

return $monthNameSpanish;

  }
   function nombre_X_mes($num)
  {
    //print_r($num);
    $monthNameSpanish='';
    switch($num)
     {   
       case ($num =='Enero') || ($num == 'enero'):
       $monthNameSpanish = "01";
       break;

       case ($num =='Febrero') || ($num =='febrero'):
       $monthNameSpanish = "02";
       break;

       case ($num =='Marzo') || ($num =='marzo'):
       $monthNameSpanish = "03";
       break;

       case ($num =='Abril') || ($num =='abril'):
       $monthNameSpanish = "04";
       break;

       case ($num =='Mayo') || ($num =='mayo'):
       $monthNameSpanish = "05";
       break;

       case ($num =='Junio') || ($num =='junio'):
       $monthNameSpanish = "06";
       break;

       case ($num =='Julio') || ($num =='julio'):
       $monthNameSpanish = "07";
       break;

       case ($num =='Agosto') || ($num =='agosto'):
       $monthNameSpanish = "08";
       break;

       case ($num =='Septiembre') || ($num =='septiembre'):
       $monthNameSpanish = "09";
       break;

        case ($num =='Octubre') || ($num =='octubre'):
       $monthNameSpanish = "10";
       break;

       case ($num =='Noviembre') || ($num =='noviembre'):
       $monthNameSpanish = "11";
       break;

       case ($num =='Diciembre') || ($num =='diciembre'):
       $monthNameSpanish = "12";
       break;
    }

return $monthNameSpanish;

  }

 // funcion para enviar todos los meses del año

  function meses_del_anio()
  {
  	$mese = array(
  		array('mes'=>'Enero','num'=>'01','acro'=>'ENE'),
  		array('mes'=>'Febrero','num'=>'02','acro'=>'FEB'),
  		array('mes'=>'Marzo','num'=>'03','acro'=>'MAR'),
  		array('mes'=>'Abril','num'=>'04','acro'=>'ABR'),
  		array('mes'=>'Mayo','num'=>'05','acro'=>'MAY'),
  		array('mes'=>'Junio','num'=>'06','acro'=>'JUN'),
  		array('mes'=>'Julio','num'=>'07','acro'=>'JUL'),
  		array('mes'=>'Agosto','num'=>'08','acro'=>'AGO'),
  		array('mes'=>'Septiembre','num'=>'09','acro'=>'SEP'),
  		array('mes'=>'Octubre','num'=>'10','acro'=>'OCT'),
  		array('mes'=>'Noviembre','num'=>'11','acro'=>'NOV'),
  		array('mes'=>'Diciembre','num'=>'12','acro'=>'DIC'),
  	);

  	return $mese;
  }

//verificar si tiene sucursales

  function existe_sucursales()
  {
  	$conn = new Conectar();
	$cid=$conn->conexion();
    $sql = "SELECT * FROM Acceso_Sucursales where Item='".$_SESSION['INGRESO']['item']."'";
		$stmt = sqlsrv_query($cid, $sql);
	    if($stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }   
         //echo $sql;
	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	      {
	    	$result[] =$row;
		    //echo $row[0];
	      }
	      if(count($result) == 0)
	      {
	      	return -1;
	      }else
	      {
	      	return 1;
	      }
  }

//año bisiesto
function provincia_todas()
{
	$conn = new Conectar();
	$cid=$conn->conexion();
    $sql = "SELECT * FROM Tabla_Naciones WHERE CPais = '593' AND TR ='P' ORDER BY CProvincia";
		$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }   
         //echo $sql;
	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	      {
	    	$result[] =array('Codigo'=>$row['CProvincia'],'Descripcion_Rubro'=>utf8_encode($row['Descripcion_Rubro']));
		    //echo $row[0];
	      }

	      return $result;
	     //print_r($result);
}

function todas_ciudad($idpro)
{
	$conn = new Conectar();
	$cid=$conn->conexion();
    $sql = "SELECT * FROM Tabla_Naciones WHERE CPais = '593' AND TR ='C' AND CProvincia='".$idpro."' ORDER BY CCiudad";
		$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return '';
		     die( print_r( sqlsrv_errors(), true));  
	      }   
        // echo $sql;
	    $result = array();	
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	      {
	    	$result[] =array('Codigo'=>$row['Codigo'],'Descripcion_Rubro'=>utf8_encode($row['Descripcion_Rubro']));
		    //echo $row[0];
	      }

	      return $result;
	     //print_r($result);

}
function esBisiesto($year=NULL) 
{
    $year = ($year==NULL)? date('Y'):$year;
    return ( ($year%4 == 0 && $year%100 != 0) || $year%400 == 0 );
}
//para devolver la url basica
function url($pag=null,$idMen=null)
{
	//directorio adicional en caso de tener uno
	$direc=$pag;
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
			$uri = 'https://';
		}else{
			$uri = 'http://';
		}
		$uri .= $_SERVER['HTTP_HOST'].$direc;
	return $uri;
}
function redireccion($pag=null,$idMen=null)
{
	if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
		$uri = 'https://';
	}else{
		$uri = 'http://';
	}
	$uri .= $_SERVER['HTTP_HOST'];
	//Aqui modificar si el pag de aministracion esta 
	//en un subdirectorio
	// "<script type=\"text/javascript\">
	// window.location=\"".$uri."/wp-admin/admin.php\";
	// </script>";
	echo "<script type='text/javascript'>window.location='".$uri."/php/vista/".$pag.".php'</script>";
}
//agregar ceros a cadena a la izquierda $tam= tamaño de la cadena
function generaCeros($numero,$tam=null){
	 //obtengop el largo del numero
	 $largo_numero = strlen($numero);
	 //especifico el largo maximo de la cadena
	 if($tam==null)
	 {
	 	$largo_maximo = 7;
	 }
	 else
	 {
	 	 $largo_maximo =$tam;
	 }
	
	 //tomo la cantidad de ceros a agregar
	 $agregar = $largo_maximo - $largo_numero;
	 //agrego los ceros
	 for($i =0; $i<$agregar; $i++){
	 $numero = "0".$numero;
	 }
	 //retorno el valor con ceros
	 return $numero;
 }
//convertir digitos a letras  ABCDEFGHIJ 0123456789
function convertirnumle($digito=null)
{
	$letra='';
	if($digito!=null)
	{
		if($digito==0)
		{
			$letra='A';
		}
		if($digito==1)
		{
			$letra='B';
		}
		if($digito==2)
		{
			$letra='C';
		}
		if($digito==3)
		{
			$letra='D';
		}
		if($digito==4)
		{
			$letra='E';
		}
		if($digito==5)
		{
			$letra='F';
		}
		if($digito==6)
		{
			$letra='G';
		}
		if($digito==7)
		{
			$letra='H';
		}
		if($digito==8)
		{
			$letra='I';
		}
		if($digito==9)
		{
			$letra='J';
		}
	}
	return $letra;
}
//digito_verificadorf('1710034065'); $solovar = si es uno devuelve solo el codigo
function digito_verificadorf($ruc,$solovar=null,$pag=null,$idMen=null,$item=null,$estudiante=null)
{
	//$ruc=$_POST['RUC'];
	//echo $ruc.' '.strlen($ruc);
	//codigo walter
	$DigStr = "";
	$VecDig = "";
	$Dig3 = "";
	$sSQLRUC = "";
	$CodigoEmp = "";
	$Producto = "";
	$SumaDig = "";
	$NumDig = "";
	$ValDig = "";
	$TipoModulo = "";
	$CodigoRUC = "";
	$Residuo = "";
	//echo $ruc.' ';
	$Dig3 = substr($ruc, 2, 1);
	//echo $Dig3;
	//$Codigo_RUC_CI = substr($ruc, 0, 10);
	//echo $Dig3.' '.$Codigo_RUC_CI ;
	$Tipo_Beneficiario = "P";
	//$NumEmpresa='001';
	$NumEmpresa=$item;
	//echo $item.' dddvc '.$NumEmpresa;
	$Codigo_RUC_CI = $NumEmpresa . "0000001";
	$Digito_Verificador = "-";
	$RUC_CI = $ruc;
	$RUC_Natural = False;
	//echo $Codigo_RUC_CI;
	//die();
	if($ruc == "9999999999999" )
	{
		$Tipo_Beneficiario = "R";
		$Codigo_RUC_CI = substr($ruc, 0, 10);
		$Digito_Verificador = 9;
		$DigStr = "9";
		//echo ' ccc '.$Codigo_RUC_CI;
		//die();
	}
	else
	{
		$DigStr = $ruc;
		$TipoBenef = "P";
		$VecDig = "000000000";
		$TipoModulo = 1;
		If (is_numeric($ruc) And $ruc <= 0)
		{
			$Codigo_RUC_CI = $NumEmpresa & "0000001";
		}
		Else
		{
			//es cedula
			if(strlen($ruc)==10 and is_numeric($ruc))
			{
				$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
				$arr1 = str_split($ruc);
				$resu = array();
				$resu1=0;
				$coe1=0;
				$pro='';
				$ter='';
				$TipoModulo=10;
				//validador
				$ban=0;
				for($jj=0;$jj<(strlen($ruc));$jj++)
				{
					//echo $arr1[$jj].' -- '.$jj.' cc ';
					//validar los dos primeros registros
					if($jj==0 or $jj==1)
					{
						$pro=$pro.$arr1[$jj];
					}
					if($jj==2)
					{
						$ter=$arr1[$jj];
					}
					//operacion suma
					if($jj<=(strlen($ruc)-2))
					{
						$resu[$jj]=$coe[$jj]*$arr1[$jj];
						if($resu[$jj]>=10)
						{
							$resu[$jj]=$resu[$jj]-9;
						}
						//suma
						$resu1=$resu[$jj]+$resu1;
					}
					//ultimo digito
					if($jj==(strlen($ruc)-1))
					{
						//echo " entro ";
						$coe1=$arr1[$jj];
					}
					
				}
				//verificamos los dos primeros registros
				if($pro>=24)
				{
					//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
					$ban=1;
				}
				//verificamos el tercer registros
				if($ter>6)
				{
					//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
					$ban=1;
				}
				//partimos string
				$arr2 = str_split($resu1);
				for($jj=0;$jj<(strlen($resu1));$jj++)
				{
					if($jj==0)
					{
						$arr2[$jj]=$arr2[$jj]+1;
					}
				}
				//aumentamos a la siguiente decena
				$resu2=$arr2[0].'0';
				//resultado del ultimo coeficioente
				$resu3 = $resu2- $resu1;
				$Residuo = $resu1 % $TipoModulo;
				//echo ' dsdsd '.$Residuo;
				//die();
				If ($Residuo == 0)
				{
				  $Digito_Verificador = "0";
				}
				Else
				{
				   $Residuo = $TipoModulo - $Residuo;
				   $Digito_Verificador = $Residuo;
				}
				//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
				if($ban==0)
				{
					If ($Digito_Verificador == substr($ruc, 9, 1))
					{
						$Tipo_Beneficiario = "C";
					}	
				}					
			}
			else
			{
				//caso ruc
				if(strlen($ruc)==13 and is_numeric($ruc))
				{
					//caso ruc ecuatorianos de extrangeros
					$Tipo_Beneficiario='O';
					if ($Dig3 == 6 )
					{
						$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
						$arr1 = str_split($ruc);
						$resu = array();
						$resu1=0;
						$coe1=0;
						$pro='';
						$ter='';
						$TipoModulo=10;
						//validador
						$ban=0;
						for($jj=0;$jj<(count($coe));$jj++)
						{
							//echo $arr1[$jj].' -- '.$jj.' cc ';
							//validar los dos primeros registros
							if($jj==0 or $jj==1)
							{
								$pro=$pro.$arr1[$jj];
							}
							if($jj==2)
							{
								$ter=$arr1[$jj];
							}
							//operacion suma
							if($jj<=(count($coe)-2))
							{
								$resu[$jj]=$coe[$jj]*$arr1[$jj];
								if($resu[$jj]>=10)
								{
									$resu[$jj]=$resu[$jj]-9;
								}
								//suma
								$resu1=$resu[$jj]+$resu1;
							}
							//ultimo digito
							if($jj==(count($coe)-1))
							{
								//echo " entro ";
								$coe1=$arr1[$jj];
							}
							
						}
						//verificamos los dos primeros registros
						if($pro>=24)
						{
							//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
							$ban=1;
						}
						//verificamos el tercer registros
						if($ter>6)
						{
							//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
							$ban=1;
						}
						//partimos string
						$arr2 = str_split($resu1);
						for($jj=0;$jj<(strlen($resu1));$jj++)
						{
							if($jj==0)
							{
								$arr2[$jj]=$arr2[$jj]+1;
							}
						}
						//aumentamos a la siguiente decena
						$resu2=$arr2[0].'0';
						//resultado del ultimo coeficioente
						$resu3 = $resu2- $resu1;
						$Residuo = $resu1 % $TipoModulo;
						//echo ' dsdsd '.$Residuo;
						//die();
						If ($Residuo == 0)
						{
						  $Digito_Verificador = "0";
						}
						Else
						{
						   $Residuo = $TipoModulo - $Residuo;
						   $Digito_Verificador = $Residuo;
						}
						//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
						if($ban==0)
						{
							If ($Digito_Verificador == substr($ruc, 9, 1))
							{
								$Tipo_Beneficiario = "R";
								$RUC_Natural = True;
							}	
						}	
					}
					if($Tipo_Beneficiario=='O')
					{
						$TipoModulo = 11;
						//echo $Dig3.' qmm ';
						if (($Dig3 <= 5) and ($Dig3 >= 0))
						{
							$TipoModulo = 10;
							$TipoModulo1=9;
							$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
							$VecDig = "212121212";
							//echo " aquiii 1 ";
						}
						else
						{
							if ($Dig3 == 6)
							{
								$coe = array("3", "2", "7", "6","5", "4", "3", "2");
								$TipoModulo1=8;
								$VecDig = "32765432";
								//echo " aquiii 2 ";
							}
							else
							{
								if($Dig3 == 9)
								{
									$coe = array("4", "3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=9;
									$VecDig = "432765432";
									//echo " aquiii 3 ";/
								}
								else
								{
									$VecDig = "222222222";
									$TipoModulo1=9;
									//echo " aquiii 4 ";
									$coe = array("2", "2", "2", "2", "2","2", "2", "2", "2");
								}
							}
						}
						
						$arr1 = str_split($ruc);
						$resu = array();
						$resu1=0;
						$coe1=0;
						$pro='';
						$ter='';
						//$TipoModulo=10;
						//validador
						$ban=0;
						for($jj=0;$jj<($TipoModulo1);$jj++)
						{
							//echo $arr1[$jj].' -- '.$jj.' cc ';
							//validar los dos primeros registros
							if($jj==0 or $jj==1)
							{
								$pro=$pro.$arr1[$jj];
							}
							if($jj==2)
							{
								$ter=$arr1[$jj];
							}
							//operacion suma
							if($jj<=(strlen($ruc)-2))
							{
								$resu[$jj]=$coe[$jj]*$arr1[$jj];
								/*if($resu[$jj]>=10)
								{
									$resu[$jj]=$resu[$jj]-9;
								}*/
								If (0 <= $Dig3 And $Dig3 <= 5 And $resu[$jj] > 9)
								{
									$resu[$jj]=$resu[$jj]-9;
								}									
								//suma
								$resu1=$resu[$jj]+$resu1;
								//echo $coe[$jj].' * '.$arr1[$jj].' = '.$resu[$jj].' sum '.$resu1.' -- ';
								
							}
							//ultimo digito
							if($jj==(strlen($ruc)-1))
							{
								//echo " entro ";
								$coe1=$arr1[$jj];
							}
							
						}
						//partimos string
						$arr2 = str_split($resu1);
						for($jj=0;$jj<(strlen($resu1));$jj++)
						{
							if($jj==0)
							{
								$arr2[$jj]=$arr2[$jj]+1;
							}
						}
						//aumentamos a la siguiente decena
						$resu2=$arr2[0].'0';
						//resultado del ultimo coeficioente
						$resu3 = $resu2- $resu1;
						$Residuo = $resu1 % $TipoModulo;
						If ($Residuo == 0)
						{
						  $Digito_Verificador = "0";
						}
						Else
						{
						   $Residuo = $TipoModulo - $Residuo;
						   $Digito_Verificador = $Residuo;
						}
						//echo $Digito_Verificador.' '.$Dig3.' ';
						If ($Dig3 == 6) 
						{
							If ($Digito_Verificador = substr($ruc, 8, 1)) 
							{
								$Tipo_Beneficiario = "R";
							}
						} 
						Else
						{
							//echo $Digito_Verificador.' veri '.substr($ruc, 9, 1);
							If ($Digito_Verificador == substr($ruc, 9, 1))
							{
								$Tipo_Beneficiario = "R";
							}							
						}
						If ($Dig3 < 6 )
						{
							$RUC_Natural = True;
						}
					}
				}
				//echo $Tipo_Beneficiario;
			}
		}
		//$_SESSION['INGRESO']['item']
		//Si no es RUC/CI, procesamos el numero de codigo que le corresponde
		//echo ' www '.substr($ruc, 12, 1).' -- '.$ruc;
		if(substr($ruc, 12, 1)!='1' and strlen($ruc)<>10)
		{
			$Tipo_Beneficiario = 'O';
		}
		if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
		{
			$database=$_SESSION['INGRESO']['Base_Datos'];
			//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
			$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
			$user=$_SESSION['INGRESO']['Usuario_DB'];
			$password=$_SESSION['INGRESO']['Contraseña_DB'];
		}
		else
		{
			$database="DiskCover_Prismanet";
			$server="tcp:mysql.diskcoversystem.com, 11433";
			$user="sa";
			$password="disk2017Cover";
		}
		/*$database="DiskCover_Prismanet";
		$server="mysql.diskcoversystem.com";
		$user="sa";
		$password="disk2017Cover";*/
		if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
		{
			$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

			$cid = sqlsrv_connect($server, $connectionInfo); //returns false
			if( $cid === false )
			{
				echo "fallo conecion sql server";
			}
			
		}
		switch ($Tipo_Beneficiario) 
		{
			case ($Tipo_Beneficiario =='C'):
			{
				$Codigo_RUC_CI = substr($ruc, 0, 10);
				//verificamos que no exista cliente
				$sql="SELECT Codigo from Clientes WHERE CI_RUC = '".$ruc."' AND TD ='C' ";
				//echo $sql;
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				} 
				$ii=0;				
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					//echo " cc ".$row[0];
					$ii++;
				}
				//echo $ii;
				if($ii>0)
				{
					if($solovar!=1)
					{
						echo "<script> alert('ya existe este RUC/CI: ".$ruc." '); </script>";
					}
					
					$Codigo_RUC_CI = 'ya existe';
				}
				sqlsrv_close( $cid );
				break;
			}
			case ($Tipo_Beneficiario =='R' ):
			{
				if($RUC_Natural == True)
				{
					//echo " natural ";
					//transformamos los primeros dos digitos ABCDEF 012345
					$let1=convertirnumle(substr($ruc, 0, 1));
					$let2=convertirnumle(substr($ruc, 1, 1));
					//$Codigo_RUC_CI = substr($ruc, 1, 1).''.substr($ruc, 2, 1).''. substr($ruc, 3, 8);
					$Codigo_RUC_CI = $let1.''.$let2.''. substr($ruc, 2, 8);
				}
				else
				{
					$Codigo_RUC_CI = substr($ruc, 0, 10);
				}
				//verificamos que no exista cliente
				$sql="SELECT Codigo As Cod_RUC from Clientes WHERE CI_RUC = '".$ruc."' AND TD ='R' ";
				//echo $sql;
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				} 
				$ii=0;				
				while( $obj = sqlsrv_fetch_object( $stmt)) 
				{
					$ii++;
				}
				if($ii>0)
				{
					if($solovar!=1)
					{
						echo "<script> alert('ya existe este RUC/CI: ".$ruc." '); </script>";
					}
					
					$Codigo_RUC_CI = 'ya existe';
				}
				sqlsrv_close( $cid );
				break;
			}
			default:    
			{    
				$Codigo_RUC_CI = $NumEmpresa."0000001";
				$CodigoEmp = $NumEmpresa."8888888";
				//echo $_SESSION['INGRESO']['IP_VPN_RUTA'];
				$sql="SELECT MAX(Codigo) As Cod_RUC from Clientes WHERE Codigo <  '".$CodigoEmp."'
				AND SUBSTRING(Codigo,1,3) = '".$NumEmpresa."' AND LEN(Codigo) = 10 
				AND TD NOT IN ('C','R') AND ISNUMERIC(Codigo) <> 0 ";
				//echo $sql;
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}  
				$i=0;
				while( $obj = sqlsrv_fetch_object( $stmt)) 
				{
					//echo $obj->TABLE_NAME."<br />";
					$CodigoRUC=$obj->Cod_RUC;
					//echo $obj->Cod_RUC.' vvv ';
					$CodigoRUC = substr($obj->Cod_RUC, 4, strlen($obj->Cod_RUC))+1;
					//buscar funcion para agregar ceros
					$CodigoRUC=generaCeros($CodigoRUC);
					$Codigo_RUC_CI = $NumEmpresa . $CodigoRUC;
					$i++;
				}
				
				//verificamos que no exista cliente
				$sql="SELECT Codigo As Cod_RUC from Clientes WHERE Codigo = '".$Codigo_RUC_CI."' ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				} 
				$ii=0;				
				while( $obj = sqlsrv_fetch_object( $stmt)) 
				{
					$ii++;
				}
				if($i==0)
				{
					$CodigoRUC = 1;
				}
				if($ii>0)
				{
					if($solovar!=1)
					{
						///
            echo "<script> alert('ya existe este RUC/CI: ".$ruc." '); </script>";
					}
					
					$Codigo_RUC_CI++;
					//$Codigo_RUC_CI = 'ya existe';
				}
				sqlsrv_close( $cid );
			} 
		}
		//echo $Codigo_RUC_CI;
		$TipoBenef = $Tipo_Beneficiario;
		$DigStr = $Digito_Verificador;
		//echo $Tipo_Beneficiario.' vvv '.strlen($ruc).' ccc ';
		switch ($Tipo_Beneficiario) 
		{
			case ($Tipo_Beneficiario =='R'):
			{
				if(strlen($ruc)<> 13 )
				{
					//echo " entro 1 ";
					$Tipo_Beneficiario = "P";
				}
				break;
			}
			//echo $Tipo_Beneficiario.' aden ';
			case ($Tipo_Beneficiario =='C'):
			{
				if(strlen($ruc)<> 10 )
				{
					//echo " entro 2 ";
					$Tipo_Beneficiario = "P";
				}
				break;
			}
			default:    
			{
				break;
			}
		}
		if($solovar!=1)
		{
			if($estudiante == null)
			{
			echo "<script> document.getElementById('".$_POST['idMen']."').value='".$Codigo_RUC_CI."'; </script>";
			//echo "<script> document.getElementById('".$_POST['TC']."').value='".$Tipo_Beneficiario."'; </script>";
			echo 'RUC/CI ('.$Tipo_Beneficiario.') ';
			return 'RUC/CI ('.$Tipo_Beneficiario.') ';
		   }else
		   {
		   	 return array('tipo'=>$Tipo_Beneficiario,'codigo'=>$Codigo_RUC_CI);
		   }
		}
		else
		{
			return  strval($Codigo_RUC_CI);
		}
	}
}
//C 12345678
//grilla generica
/*public function Data_Grid($Encabezado){
      $num = mysqli_num_fields($this->stmt);
      $campos="";
      for ($x=0;$x<$num;$x++){
          $campo[$x] = $this->stmt->fetch_field_direct($x);
          $campos=$campos."<th>".$campo[$x]->name."</th>";   
      }                     
      $malla="<div id='encabezado'>
                  <a>".$Encabezado."</a>
              </div>
              <div class='datagrid'>
               <table>
                  <thead>
                     <tr>".$campos."</tr>
                  </thead>";
      echo $malla;
      $malla=" <tfoot>
                  <tr>
                     <td colspan='4'>
                        <div id='paging'>
                           <ul>
                              <li>
                                 <a href='#><span>Previous</span></a>
                              </li>
                              <li>
                                 <a href='# class='active'><span>1</span></a>
                              </li>
                              <li>
                                 <a href='#'><span>2</span></a>
                              </li>
                              <li>
                                 <a href='#'><span>3</span></a>
                              </li>
                              <li>
                                 <a href='#'><span>4</span></a>
                              </li>
                              <li>
                                 <a href='#'><span>5</span></a>
                              </li>
                              <li>
                                 <a href='#'><span>Next</span></a>
                              </li>
                           </ul>
                        </div>
                  </tr>
               </tfoot>";
      echo $malla;
      $fila=0;
      $campos="";
      while ($fila<$this->num_regs()){
         if (($fila % 2) == 0){
            $campos=$campos."<tr>";
         }else{
            $campos=$campos."<tr class='alt'>";
         }   
         //$registro=mysqli_fetch_array($this->stmt);
         $registro=$this->obtener_fila($this->stmt,$fila);
         for ($x=0;$x<$num;$x++){
             $campos=$campos."<td>".$registro[$x]."</td>";
         }             
         $campos=$campos."</tr>";
         $fila++;
      }
      $malla="<tbody>
               ".$campos."
              </tbody>
            </table>
         </div>";
   echo $malla;
   }*/
//devuelve empresas asociadas al usuario
/*function getEntidades($id_entidad=null)
{
	$per=new entidad_model();
	$entidades=$per->getEntidades($id_entidad);
	return $entidades;
}*/
/*
	funcion grilla generica 
	$stmt: codigo sql ya ejecutado en el modelo, 
	$ti: titulo, 
	$camne: para poner alguna fila en negrita en base a condiones,
	$b: para border de tabla
	$ch lleva check-box posicion,nombre del check
	$tabla caso donde sean necesaria varias grillas
*/

function grilla_generica_old($stmt,$ti=null,$camne=null,$b=null,$ch=null,$tabla=null)
{
	//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
			foreach( $fieldMetadata as $name => $value) {
				if(!is_numeric($value))
				{
					if($value!='')
					{
						$cant++;
					}
				}
			}
		}
		if($ch!=null)
		{
			$ch1 = explode(",", $ch);
			$cant++;
		}
		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//colocar cero a tabla en caso de no existir definida ninguna
		if($tabla==null OR $tabla=='0' OR $tabla=='')
		{
			$tabla=0;
		}
		?>
		<div class="box-body no-padding">
            <table class="table table-striped w-auto <?php echo $bor; ?>" >
			<?php
				if($ti!='' or $ti!=null)
				{
			?>
				<tr>
					<th colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
				</tr>
			<?php
				}
			?>
                <tr>
					<?php
					//cantidad campos
					$cant=0;
					//guardamos los campos
					$campo='';
					//tipo de campos
					$tipo_campo=array();
					//guardamos posicion de un campo ejemplo fecha
					$cam_fech=array();
					//contador para fechas
					$cont_fecha=0;
					//obtenemos los campos 
					//en caso de tener check
					if($ch!=null)
					{
						echo "<th style='text-align: left;' id='tit_sel'>SEL</th>";
					}
					foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
						//$camp='';
						$i=0;
						//tipo de campo
						$ban=0;
						//texto
						if($fieldMetadata['Type']==-9)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//numero
						if($fieldMetadata['Type']==3)
						{
							//number_format($item_i['nombre'],2, ',', '.')
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//echo $fieldMetadata['Type'].' ccc <br>';
						//echo $fieldMetadata['Name'].' ccc <br>';
						//caso fecha
						if($fieldMetadata['Type']==93)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
							$cam_fech[$cont_fecha]=$cant;
							//contador para fechas
							$cont_fecha++;
						}
						//caso bit
						if($fieldMetadata['Type']==-7)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//caso int
						if($fieldMetadata['Type']==4)
						{
							$tipo_campo[($cant)]=" style='text-align: right;'";
							$ban=1;
						}
						//caso tinyint
						if($fieldMetadata['Type']==-6)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso smallint
						if($fieldMetadata['Type']==5)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso real
						if($fieldMetadata['Type']==7)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso float
						if($fieldMetadata['Type']==6)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//uniqueidentifier
						if($fieldMetadata['Type']==-11)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//ntext
						if($fieldMetadata['Type']==-10)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//rownum
						if($fieldMetadata['Type']==-5)
						{
							//echo " dddd ";
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//ntext
						if($fieldMetadata['Type']==12)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						if($ban==0)
						{
							echo ' no existe tipo '.$value.' '.$fieldMetadata['Name'].' '.$fieldMetadata['Type'];
						}
						
						foreach( $fieldMetadata as $name => $value) {
							
							if(!is_numeric($value))
							{
								if($value!='')
								{
									echo "<th ".$tipo_campo[$cant].">".$value."</th>";
									$camp=$value;
									$campo[$cant]=$camp;
									//echo ' dd '.$campo[$cant];
									$cant++;
									//echo $value.' cc '.$cant.' ';
								}
							}
						   //echo "$name: $value<br />";
						}
						
						  //echo "<br />";
					}
					/*for($i=0;$i<$cant;$i++)
					{
						echo $i.' gfggf '.$tipo_campo[$i];
					}*/
					?>
				</tr>
				
                 
					<?php
					//echo $cant.' fffff ';
					//obtener la configuracion para celdas personalizadas
					//campos a evaluar
					$campoe=array();
					//valor a verificar
					$campov=array();
					//campo a afectar 
					$campoaf=array();
					//adicional
					$adicional=array();
					//signos para comparar
					$signo=array();
					//titulo de proceso
					$tit=array();
					//indice de registros a comparar con datos
					$ind=0;
					//obtener valor en caso de mas de una condicion
					$con_in=0;
					if($camne!=null)
					{
						for($i=0;$i<count($camne['TITULO']);$i++)
						{
							if($camne['TITULO'][$i]=='color_fila')
							{	
								$tit[$ind]=$camne['TITULO'][$i];
								//temporar para indice
								//$temi=$i;
								//buscamos campos a evaluar
								$camneva = explode(",", $camne['CAMPOE'][$i]);
								//si solo es un campo
								if(count($camneva)==1)
								{
									$camneva1 = explode("=", $camneva[0]);
									$campoe[$ind]=$camneva1[0];
									$campov[$ind]=$camneva1[1];
									//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
								}
								else
								{
									//hacer bucle
								}
								//para los campos a afectar
								if(count($camne['CAMPOA'])==1 AND $i==0)
								{
									if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
									{
										$campoaf[$ind]='TODOS';
									}
									else
									{
										//otras opciones
									}
								}
								else
								{
									//bucle
									if(!empty($camne['CAMPOA'][$i]))
									{
										if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
										{
											$campoaf[$ind]='TODOS';
										}
										else
										{
											//otras opciones
										}
									}
								}
								//valor adicional en este caso color
								if(count($camne['ADICIONAL'])==1 AND $i==0)
								{
									$adicional[$ind]=$camne['ADICIONAL'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['ADICIONAL'][$i]))
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
								}
								//signo de comparacion
								if(count($camne['SIGNO'])==1 AND $i==0)
								{
									$signo[$ind]=$camne['SIGNO'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['SIGNO'][$i]))
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
								}
								$ind++;
								//echo ' pp '.count($camneva);
							}
							//caso de indentar columna
							/*if($camne['TITULO'][$i]=='indentar')
							{
								$tit[$ind]=$camne['TITULO'][$i];
									//buscamos campos a evaluar
								$camneva = explode(",", $camne['CAMPOE'][$i]);
								//si solo es un campo
								if(count($camneva)==1)
								{
									$camneva1 = explode("=", $camneva[0]);
									$campoe[$ind]=$camneva1[0];
									$campov[$ind]=$camneva1[1];
									//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
								}
								else
								{
									//hacer bucle
								}
								//para los campos a afectar
								if(count($camne['CAMPOA'])==1 AND $i==0)
								{
									$campoaf[$ind]=$camne['CAMPOA'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['CAMPOA'][$i]))
									{
										//otras opciones
										$campoaf[$ind]=$camne['CAMPOA'][$i];
									}
								}
								//valor adicional en este caso color
								if(count($camne['ADICIONAL'])==1 AND $i==0)
								{
									$adicional[$ind]=$camne['ADICIONAL'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['ADICIONAL'][$i]))
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
								}
								//signo de comparacion
								if(count($camne['SIGNO'])==1 AND $i==0)
								{
									$signo[$ind]=$camne['SIGNO'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['SIGNO'][$i]))
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
								}
								$ind++;
							}*/
							//caso italica, subrayar, indentar
							if($camne['TITULO'][$i]=='italica' OR $camne['TITULO'][$i]=='subrayar' OR $camne['TITULO'][$i]=='indentar')
							{
								$tit[$ind]=$camne['TITULO'][$i];
									//buscamos campos a evaluar
								if(!is_array($camne['CAMPOE'][$i]))
								{
									$camneva = explode(",", $camne['CAMPOE'][$i]);
									//si solo es un campo
									if(count($camneva)==1)
									{
										$camneva1 = explode("=", $camneva[0]);
										$campoe[$ind]=$camneva1[0];
										$campov[$ind]=$camneva1[1];
										//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
									}
									else
									{
										//hacer bucle
									}
								}
								else
								{
									//es mas de un campo
									$con_in = count($camne['CAMPOE'][$i]);
									//recorremos registros
									for($j=0;$j<$con_in;$j++)
									{
										//echo $camne['CAMPOE'][$i][$j].' ';
										$camneva = explode(",", $camne['CAMPOE'][$i][$j]);
										//si solo es un campo
										if(count($camneva)==1)
										{
											$camneva1 = explode("=", $camneva[0]);
											$campoe[$ind][$j]=$camneva1[0];
											$campov[$ind][$j]=$camneva1[1];
											//echo ' pp '.$campoe[$ind][$j].' '.$campov[$ind][$j];
										}
									}
								}
								//para los campos a afectar
								if(!is_array($camne['CAMPOA'][$i]))
								{
									if(count($camne['CAMPOA'])==1 AND $i==0)
									{
										$campoaf[$ind]=$camne['CAMPOA'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['CAMPOA'][$i]))
										{
											//otras opciones
											$campoaf[$ind]=$camne['CAMPOA'][$i];
										}
									}
								}
								else
								{
									//recorremos el ciclo
									//es mas de un campo
									$con_in = count($camne['CAMPOA'][$i]);
									//recorremos registros
									for($j=0;$j<$con_in;$j++)
									{
										$campoaf[$ind][$j]=$camne['CAMPOA'][$i][$j];
										//echo ' pp '.$campoaf[$ind][$j];
									}
								}
								//valor adicional en este caso color
								
									if(count($camne['ADICIONAL'])==1 AND $i==0)
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['ADICIONAL'][$i]))
										{
											//es mas de un campo
											$con_in = count($camne['ADICIONAL'][$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$adicional[$ind][$j]=$camne['ADICIONAL'][$i][$j];
												//echo ' pp '.$adicional[$ind][$j];
											}
										}
									}
								
								
								//signo de comparacion
								if(!is_array($camne['SIGNO'][$i]))
								{
									if(count($camne['SIGNO'])==1 AND $i==0)
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['SIGNO'][$i]))
										{
											$signo[$ind]=$camne['SIGNO'][$i];
										}
									}
								}
								else
								{
									//es mas de un campo
									$con_in = count($camne['SIGNO'][$i]);
									for($j=0;$j<$con_in;$j++)
									{
										$signo[$ind][$j]=$camne['SIGNO'][$i][$j];
										//echo ' pp '.$signo[$ind][$j];
									}
								}
								$ind++;
							}
						}
					}
					$i=0;
					while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
							//para colocar identificador unicode_decode
							if($ch!=null)
							{
								if(count($ch1)==2)
								{
									$cch=$ch1[0];
									?>
										<tr <?php echo "id=ta_".$row[$cch]."";?> >
									<?php
								}
								else
								{
									//casos con mas id
									$cch='';
									$camch='';
									//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
									for($ca=0;$ca<count($ch1);$ca++)
									{
										if($ca<(count($ch1)-1))
										{
											$cch=$ch1[$ca];
											$camch=$camch.$row[$cch].'--';
										}
									}
									$ca=$ca-1;
									?>
										<tr <?php echo "id=ta_".$camch."";?> >
									<?php
								}
							}
							else
							{
								?>
								<tr >
								<?php
							}
							if($ch!=null)
							{
								if(count($ch1)==2)
								{
									$cch=$ch1[0];
									echo "<td style='text-align: left;'><input type='checkbox' id='id_".$row[$cch]."' name='".$ch1[1]."' value='".$row[$cch]."'
									onclick='validarc(\"id_".$row[$cch]."\",\"".$tabla."\")'></td>";
								}
								else
								{
									//casos con mas id
									$cch='';
									$camch='';
									//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
									for($ca=0;$ca<count($ch1);$ca++)
									{
										if($ca<(count($ch1)-1))
										{
											$cch=$ch1[$ca];
											$camch=$camch.$row[$cch].'--';
										}
									}
									$ca=$ca-1;
									echo "<td style='text-align: left;'><input type='checkbox' id='id_".$camch."' name='".$ch1[$ca]."' value='".$camch."'
									onclick='validarc(\"id_".$camch."\",\"".$tabla."\")'></td>";
									//die();
								}
							}
							//comparamos con los valores de los array para personalizar las celdas
							//para titulo color fila
							$cfila1='';
							$cfila2='';
							//indentar
							$inden='';
							$indencam=array();
							$indencam1=array();
							//contador para caso indentar
							$conin=0;
							//contador caso para saber si cumple varias condiciones ejemplo italica TC=P OR TC=C
							$ca_it=0;
							//variable para colocar italica
							$ita1='';
							$ita2='';
							//contador para caso italicas
							$conita=0;
							//valores de campo a afectar
							$itacam1=array();
							//variables para subrayar
							//valores de campo a afectar en caso subrayar
							$subcam1=array();
							//contador caso subrayar
							$consub=0;
							//contador caso para saber si cumple varias condiciones ejemplo subrayar TC=P OR TC=C
							$ca_sub=0;
							//variable para colocar subrayar
							$sub1='';
							$sub2='';
							for($i=0;$i<$ind;$i++)
							{
								if($tit[$i]=='color_fila')
								{
									if(!is_array($campoe[$i]))
									{
										//campo a comparar
										$tin=$campoe[$i];
										//comparamos valor
										if($signo[$i]=='=')
										{
											if($row[$tin]==$campov[$i])
											{
												if($adicional[$i]=='black')
												{
													//activa condicion
													$cfila1='<B>';
													$cfila2='</B>';
												}
											}
										}
									}
								}
								if($tit[$i]=='indentar')
								{	
									if(!is_array($campoe[$i]))
									{
										//campo a comparar
										$tin=$campoe[$i];
										//comparamos valor
										if($signo[$i]=='=')
										{
											if($campov[$i]=='contar')
											{
												$inden1 = explode(".", $row[$tin]);
												//echo ' '.count($inden1);
												//hacemos los espacios
												//$inden=str_repeat("&nbsp;&nbsp;", count($inden1));
												if(count($inden1)>1)
												{
													$indencam1[$conin]=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", (count($inden1)-1));
												}
												else
												{
													$indencam1[$conin]="";
												}
												/*if(count($inden1)==1)
												{
													$inden='';
												}
												if(count($inden1)==2)
												{
													$inden='&nbsp;';
												}
												if(count($inden1)==3)
												{
													$inden='&nbsp;&nbsp;';
												}
												if(count($inden1)==3)
												{
													$inden='&nbsp;&nbsp;&nbsp;';
												}*/
											}
											$indencam[$conin]=$campoaf[$i];
											//echo $indencam[$conin].' dd ';
											$conin++;
										}
									}
								}
								if($tit[$i]=='italica')
								{	
									if(!is_array($campoe[$i]))
									{
										
									}
									else
									{
										//es mas de un campo
										$con_in = count($campoe[$i]);
										$ca_it=0;
										for($j=0;$j<$con_in;$j++)
										{
											$tin=$campoe[$i][$j];
											//echo ' pp '.$tin[$i][$j];
											//comparamos valor
											if($signo[$i][$j]=='=')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]==$campov[$i][$j])
												{
													$ca_it++;
												}
											}
											//si es diferente
											if($signo[$i][$j]=='<>')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]<>$campov[$i][$j])
												{
													$ca_it++;
												}
											}
											
										}
										$con_in = count($campoaf[$i]);
										for($j=0;$j<$con_in;$j++)
										{
											$itacam1[$conita]=$campoaf[$i][$j];
											//echo $itacam1[$conita].' ';
											$conita++;
										}
										//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
										if($ca_it==count($campoe[$i]))
										{
											$ita1='<em>';
											$ita2='</em>';
										}
										else
										{
											$ita1='';
											$ita2='';
										}
									}
									
								}
								if($tit[$i]=='subrayar')
								{	
									if(!is_array($campoe[$i]))
									{
										
									}
									else
									{
										//es mas de un campo
										$con_in = count($campoe[$i]);
										$ca_sub=0;
										$ca_sub1=0;
										for($j=0;$j<$con_in;$j++)
										{
											$tin=$campoe[$i][$j];
											//echo ' pp '.$tin[$i][$j];
											//comparamos valor
											if($signo[$i][$j]=='=')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]==$campov[$i][$j])
												{
													$ca_sub++;
													$ca_sub1++;
												}
											}
											//si es diferente
											if($signo[$i][$j]=='<>')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]<>$campov[$i][$j])
												{
													$ca_sub++;
												}
											}
											
										}
										$con_in = count($campoaf[$i]);
										for($j=0;$j<$con_in;$j++)
										{
											$subcam1[$consub]=$campoaf[$i][$j];
											//echo $subcam1[$consub].' ';
											$consub++;
										}
										//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
										$sub1='';
										$sub2='';
										//condicion para verificar si signo es "=" o no
										if($ca_sub1==0)
										{
											//condicion en caso de distintos
											if($ca_sub==count($campoe[$i]))
											{
												$sub1='<u>';
												$sub2='</u>';
											}
											else
											{
												$sub1='';
												$sub2='';
											}
										}
										else
										{
											$sub1='<u>';
											$sub2='</u>';
										}
									}
								}
							}
							//para check box
						
						for($i=0;$i<$cant;$i++)
						{
							//caso indentar
							for($j=0;$j<count($indencam);$j++)
							{
								if($indencam[$j]==$i)
								{
									$inden=$indencam1[$j];
								}
								else
								{
									$inden='';
								}
							}
							//caso italica
							$ita3="";
							$ita4="";
							for($j=0;$j<count($itacam1);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($itacam1[$j]==$i)
								{
									$ita3=$ita1;
									$ita4=$ita2;
								}
								
							}
							//caso subrayado
							$sub3="";
							$sub4="";
							for($j=0;$j<count($subcam1);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($subcam1[$j]==$i)
								{
									$sub3=$sub1;
									$sub4=$sub2;
								}
								
							}
							//caso de campos fechas
							for($j=0;$j<count($cam_fech);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($cam_fech[$j]==$i)
								{
									//$row[$i]=$row[$i]->format('Y-m-d H:i:s');
									$row[$i]=$row[$i]->format('Y-m-d');
								}
								
							}
							//echo "<br/>";
							//formateamos texto si es decimal
							if($tipo_campo[$i]=="style='text-align: right;'")
							{
								//si es cero colocar -
								if(number_format($row[$i],2, ',', '.')==0 OR number_format($row[$i],2, ',', '.')=='0.00')
								{
									echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
								}
								else
								{
									//si es negativo colocar rojo
									if($row[$i]<0)
									{
										//reemplazo una parte de la cadena por otra
										$longitud_cad = strlen($tipo_campo[$i]); 
										$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
										echo "<td ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
									}
									else
									{
										echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
									}
								}
								
							}
							else
							{
								if(strlen($row[$i])<=50)
								{
									echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
								}
								else
								{
									$resultado = substr($row[$i], 0, 50);
									//echo $resultado; // imprime "ue"
									echo "<td ".$tipo_campo[$i]." data-toggle='tooltip' data-placement='left' title='".$row[$i]."'>".$cfila1.$ita3.$inden.$sub3."".$resultado."...".$sub4.$ita4.$cfila2."</td>";
								}
							}
						}
						/*$cam=$campo[$i];
						echo "<td>".$row['DG']."</td>";
						echo "<td>".$row['Codigo']."</td>";
						echo "<td>".$row['Cuenta']."</td>";
						echo "<td>".$row['Saldo_Anterior']."</td>";
						echo "<td>".$row['Debitos']."</td>";
						echo "<td>".$row['Creditos']."</td>";
						echo "<td>".$row['Saldo_Total']."</td>";
						echo "<td>".$row['TC']."</td>";*/
						 ?>
						  </tr>
						  <?php
						
						//$campo
						  //echo $row[$i].", <br />";
						  $i++;
						  if($cant==($i))
						  {
							  
							  //echo $cant.' ddddd '.$i;
							  $i=0;
							 
						  }
					}
		 ?>
			</table>
		</div>
		  <?php
}	
//excel
function exportar_excel_generico($stmt,$ti=null,$camne=null,$b=null,$base=null)
{
  excel_file($stmt,$ti,$camne,$b,$base); 
}
function exportar_excel_descargos($stmt,$ti=null,$camne=null,$b=null,$base=null)
{
	excel_file_descargos($stmt,$ti,$camne,$b,$base); 
}
function exportar_excel_auditoria($stmt,$ti=null,$camne=null,$b=null,$base=null)
{
  excel_file_auditoria($stmt,$ti,$camne,$b,$base); 
}
function exportar_excel_comp($stmt,$ti=null,$camne=null,$b=null,$base=null)
{
  excel_file_comp($stmt,$ti,$camne,$b,$base); 
}
function exportar_excel_diario_g($re,$ti=null,$camne=null,$b=null,$base=null)
{
	excel_file_diario($re,$ti,$camne,$b,$base); 
}
function exportar_excel_libro_g($re,$stmt,$ti=null,$camne=null,$b=null,$base=null)
{
	excel_file_libro($re,$stmt,$ti,$camne,$b,$base); 
}
function exportar_excel_mayor_auxi($re,$sub,$ti=null,$camne=null,$b=null,$base=null)
{
	excel_file_mayor_auxi($re,$sub,$ti,$camne,$b,$base); 
}
function exportar_excel_libro_banco($re,$ti=null,$camne=null,$b=null,$base=null)
{
	excel_file_libro_banco($re,$ti,$camne,$b,$base); 
}
//impimir xml txt etc $va en caso de ser pdf se evalua si lee 0 desde variable si lee 1 desde archivo xml
//$imp variable para descargar o no archivo
function ImprimirDoc($stmt,$id=null,$formato=null,$va=null,$imp=null,$ruta=null)
{
	if($ruta==null)
	{
		require_once("../../lib/fpdf/reporte_de.php");
	}
	$nombre_archivo = "TEMP/".$id.".".$formato; 
	if($formato=='xml')
	{
		if($imp==0)
		{
			$nombre_archivo = "TEMP/".$id.".xml"; 
		}
		if(file_exists($nombre_archivo))
		{
			//$mensaje = "El Archivo $nombre_archivo se ha modificado";
		}
	 
		else
		{
			//$mensaje = "El Archivo $nombre_archivo se ha creado";
		}
		
		//if($archivo = fopen($nombre_archivo, "a"))
		if($archivo = fopen($nombre_archivo, "w+b"))
		{
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$row[0] = str_replace("ï»¿", "", $row[0]);
				if(fwrite($archivo, $row[0]))
				{
					echo "Se ha ejecutado correctamente";
				}
				else
				{
					echo "Ha habido un problema al crear el archivo";
				}
			}
		   
	 
			fclose($archivo);
		}
		if($imp==null or $imp==1)
		{
			if (file_exists($nombre_archivo)) {
				$downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($nombre_archivo);
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $downloadfilename);
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($nombre_archivo));
				
				ob_clean();
				flush();
				readfile($nombre_archivo);
				
				exit;
			}
		}
	}
	if($formato=='pdf')
	{
		$nombre_archivo = "TEMP/".$id.".xml"; 
		//desde archivo
		if($va==1)
		{
			//echo "asas";
			if(file_exists($nombre_archivo))
			{
				//$mensaje = "El Archivo $nombre_archivo se ha modificado";
			}
		 
			else
			{
				//$mensaje = "El Archivo $nombre_archivo se ha creado";
			}
		 
			//if($archivo = fopen($nombre_archivo, "a"))
			if($archivo = fopen($nombre_archivo, "w+b"))
			{
				
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$row[0] = str_replace("ï»¿", "", $row[0]);
					$stmt1=$row[0];
					$ti=$row[1];
					if(fwrite($archivo, $row[0]))
					{
						//echo "Se ha ejecutado correctamente";
					}
					else
					{
						echo "Ha habido un problema al crear el archivo";
					}
				}
			   
		 
				fclose($archivo);
			}
		}
		else
		{
			//echo "dddd";
			//desde variable
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				//echo $row[0];
				$row[0] = str_replace("ï»¿", "", $row[0]);
				$stmt1=$row[0];
				$ti=$row[1];
			}
		}
		//die();
		//echo $ti;
		//die();
		if($ti=='FA')
		{
			imprimirDocEl($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='NC')
		{
			imprimirDocElNC($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='RE')
		{
			imprimirDocElRE($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='GR' OR $ti=='XX')
		{
			imprimirDocElGR($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='NV')
		{
			imprimirDocElNV($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='ND')
		{
			imprimirDocElND($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		
	}
}
function ImprimirDocError($stmt,$id=null,$formato=null,$va=null,$imp=null)
{
	//para errores de mayorizacion
	if($id=='macom')
	{
		require_once("../../lib/fpdf/reporte_comp.php");
		//echo " entrooo ";
		//die();
		$nombre_archivo = "TEMP/".$id.".xml"; 
		imprimirDocERRORPDF($stmt,$id,$formato,$nombre_archivo,$va,$imp);
	}
}
//conseguir un valor en una etiqueta xml
function etiqueta_xml($xml,$eti)
{
	//validar que etiqueta sea unica
	$cont=substr_count($xml,$eti);
	if( $cont <= 1 and $cont<>0 )
	{
		$resul1 = explode($eti, $xml);
		$cont1=substr_count($eti,">");
		$eti1 = str_replace("<", "</", $eti);
		//sin atributos
		if($cont1==1)
		{
			$resul2 = explode($eti1, $resul1[1]);
		}
		else
		{
			//con atributos
			$resul3 = explode(">", $resul1[1]);
			$resul2 = explode($eti1, $resul3[1]);
		}
		if($eti=='<baseImponible')
		{
			//echo $resul2[0].' ssssssssssssssssssss<br>';
		}
		//$resul2 = explode($eti1, $resul1[1]);
		//echo $resul2[0].' <br>';
		return $resul2[0]; 
		
	}
	else
	{
		if( $cont > 1  )
		{
			//echo " vvv ".$cont;
			$resul1 = explode($eti, $xml);
			//$eti1 = str_replace("<", "</", $eti);
			//$resul2 = explode($eti1, $resul1[1]);
			$j=0;
			$resul4=array();
			
			for($i=0;$i<count($resul1);$i++)
			{
				if($i>=1)
				{
					$resul3 = explode(">", $resul1[$i]);
					$eti1 = str_replace("<", "</", $eti);
					$resul2 = explode($eti1, $resul3[1]);
					$resul4[$j]=$resul2[0];
					//echo $resul2[0].' <br>';
					//echo " segunda opc".' <br>';
					//echo $j.' <br>';
					if($eti=='<baseImponible')
					{
						//echo $resul1[$i].' ssssssssssssssssssss<br>';
					}
					$j++;
				}
			}
			return $resul4;
		}
		else
		{
			return '';
		}
	}
}
//tomar solo porcion de etiqueta xml
function porcion_xml($xml,$eti,$etf)
{
	$resul1 = explode($eti, $xml);
	$resul4=array();
	$j=0;
	for($i=0;$i<count($resul1);$i++)
	{
		if($i>=1)
		{
			$resul2 = explode($etf, $resul1[$i]);
			$resul4[$j]=$resul2[0];
			//echo $resul2[0];
			$j++;
		}
	}
	return $resul4;
}
//crear select option
function select_option_aj($tabla,$value,$mostrar,$filtro=null,$sel=null)
{
	//realizamos conexion
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
	{
		$database=$_SESSION['INGRESO']['Base_Datos'];
		//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
		$user=$_SESSION['INGRESO']['Usuario_DB'];
		$password=$_SESSION['INGRESO']['Contraseña_DB'];
	}
	else
	{
		$database="DiskCover_Prismanet";
		$server="tcp:mysql.diskcoversystem.com, 11433";
		$user="sa";
		$password="disk2017Cover";
	}
	/*$database="DiskCover_Prismanet";
	$server="mysql.diskcoversystem.com";
	$user="sa";
	$password="disk2017Cover";*/
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			echo "fallo conecion sql server";
		}
		$sql = "SELECT ".$value.",".$mostrar." FROM ".$tabla;
		if($filtro!=null and $filtro!='')
		{
			$sql =  $sql." WHERE ".$filtro." ";
		}
	}
	$value1 = explode(",", $value);
	if(count($value1)==1)
	{
		$val1=0;
	}
	else
	{
		$val1=1;
	}
	$mostrar1 = explode(",", $mostrar);
	if(count($mostrar1)==1)
	{
		$cam1=0;
	}
	else
	{
		$cam1=1;
	}
	//echo $sql;
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	$selc='';
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$selc='';
		if($sel==$row[0])
		{
			$selc='selected';
		}
		if($val1==0)
		{
		?>	
			<option value='<?php echo $row[0]; ?>' <?php echo $selc; ?> >
		<?php
		}
		if($val1==1)
		{
		?>	
			<option value='<?php echo $row[0].'-'.$row[1]; ?>' <?php echo $selc; ?> >
		<?php
		}
				if($cam1==0)
				{
					if($val1==0)
					{
						echo $row[1];
					}
					if($val1==1)
					{
						echo $row[2];
					}
				}
				else
				{
					if($val1==0)
					{
						echo $row[1].'  '.$row[2];
					}
					if($val1==1)
					{
						echo $row[3].'  '.$row[4];
					}
				}
			?></option>
		<?php
	}
	sqlsrv_close( $cid );
}
//crear select option
function select_option($tabla,$value,$mostrar,$filtro=null,$click=null,$id_html=null)
{
	//realizamos conexion
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
	{
		$database=$_SESSION['INGRESO']['Base_Datos'];
		//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
		$user=$_SESSION['INGRESO']['Usuario_DB'];
		$password=$_SESSION['INGRESO']['Contraseña_DB'];
	}
	else
	{
		$database="DiskCover_Prismanet";
		$server="tcp:mysql.diskcoversystem.com, 11433";
		$user="sa";
		$password="disk2017Cover";
	}
	/*$database="DiskCover_Prismanet";
	$server="mysql.diskcoversystem.com";
	$user="sa";
	$password="disk2017Cover";*/
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			echo "fallo conecion sql server";
		}
		$sql = "SELECT ".$value.",".$mostrar." FROM ".$tabla;
		if($filtro!=null and $filtro!='')
		{
			$sql =  $sql." WHERE ".$filtro." ";
		}
	}
	$mostrar1 = explode(",", $mostrar);
	if(count($mostrar1)==1)
	{
		$cam1=0;
	}
	else
	{
		$cam1=1;
	}
	// echo $sql;
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	$click1='';
	if($click!=null)
	{
		if($id_html!=null)
		{
			$click1=$click;
			$click1=$click1."('".$id_html."')";
			//onclick=" echo $click1; "
		}
	}
  $op='';
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{	
		$op = "<option value='".$row[0]."'>";
			
				if($cam1==0)
				{
					$op.=$row[1]; 
				}
				else
				{
					 $op.=$row[1].'-'.$row[2]; 
				}
		$op.="</option>";

	}
	sqlsrv_close( $cid );
  return $op;
}
//crear select option para mysql
function select_option_mysql($tabla,$value,$mostrar,$filtro=null)
{
	require_once("../db/db.php");
	$cid = Conectar::conexion('MYSQL');;
	
	$sql = "SELECT ".$value.",".$mostrar." FROM ".$tabla;
	
	if($filtro!=null and $filtro!='')
	{
		$sql =  $sql." WHERE ".$filtro." ";
	}
	//echo $sql;
	$consulta=$cid->query($sql) or die($cid->error);
	//$stmt = sqlsrv_query( $cid, $sql);
	//saber si hay mas campos amostrar
	$mostrar1 = explode(",", $mostrar);
	if(count($mostrar1)==1)
	{
		$cam1=0;
	}
	else
	{
		$cam1=1;
	}
	
	if( $consulta === false)  
	{  
		 echo "Error en consulta.\n";  
		 $return = array('success' => false);
		 //die( print_r( sqlsrv_errors(), true));  
	}
	else
	{	
		while($filas=$consulta->fetch_assoc())
		{
			?>	
			<option value='<?php echo $filas[$value]; ?>'>
				<?php 
					if($cam1==0)
					{
						echo $filas[$mostrar];
					}
					else
					{
						$mos1=$mostrar1[0];
						$mos2=$mostrar1[1];
						echo $filas[$mos1].'-'.$filas[$mos2];
					}
				?>
			</option>
			<?php
			
		}
		
	}
	$cid->close();
}

//consulta menus del sistema
function select_menu_mysql()
{
  require_once("../db/db.php");
  $cid = Conectar::conexion('MYSQL');;
  $sql = "SELECT * FROM menu_modulos";
  $consulta=$cid->query($sql) or die($cid->error);

  //verificar codigo del menu que se necesita
  while ($menu_item = $consulta->fetch_assoc()) {
    if (strtolower($_GET['mod']) == strtolower($menu_item['descripcionMenu'])) {
      $codMenu = $menu_item['codMenu'];
    }
  }

  //seleccionar todos los items del menu
  $sql = "SELECT * FROM menu_modulos WHERE codMenu LIKE '".$codMenu."%' ORDER BY codMenu ASC";
  $submenu=$cid->query($sql) or die($cid->error);
  $array_menu = array();
  $i = 0;
  while ($menu_item = $submenu->fetch_assoc()) {
    //echo $menu_item['codMenu']." ".$menu_item['descripcionMenu']."<br>";
    $array_menu[$i]['codMenu'] = $menu_item['codMenu'];
    $array_menu[$i]['descripcionMenu'] = $menu_item['descripcionMenu'];
    $array_menu[$i]['accesoRapido'] = $menu_item['accesoRapido'];
    $array_menu[$i]['rutaProceso'] = $menu_item['rutaProceso'];
    $i++;
  }
  return $array_menu;
  $cid->close();
  exit();
}

//consulta niveles del menu
function select_nivel_menu_mysql($padre)
{
  require_once("../db/db.php");
  $cid = Conectar::conexion('MYSQL');

  //seleccionar los niveles del menu
  $sql = "SELECT * FROM menu_modulos WHERE codMenu LIKE '".$padre.".%' ORDER BY codMenu ASC";
  $submenu=$cid->query($sql) or die($cid->error);
  $array_menu = array();
  $i = 0;
  while ($menu_item = $submenu->fetch_assoc()) {
    //echo $menu_item['codMenu']." ".$menu_item['descripcionMenu']."<br>";
    $array_menu[$i]['codMenu'] = $menu_item['codMenu'];
    $array_menu[$i]['descripcionMenu'] = $menu_item['descripcionMenu'];
    $array_menu[$i]['accesoRapido'] = $menu_item['accesoRapido'];
    $array_menu[$i]['rutaProceso'] = $menu_item['rutaProceso'];
    $i++;
  }
  return $array_menu;
  $cid->close();
  exit();
}

//contar registros se usa para determinar tamaños de ventanas
function contar_option($tabla,$value,$mostrar,$filtro=null)
{
	//realizamos conexion
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
	{
		$database=$_SESSION['INGRESO']['Base_Datos'];
		//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
		$user=$_SESSION['INGRESO']['Usuario_DB'];
		$password=$_SESSION['INGRESO']['Contraseña_DB'];
	}
	else
	{
		$database="DiskCover_Prismanet";
		$server="tcp:mysql.diskcoversystem.com, 11433";
		$user="sa";
		$password="disk2017Cover";
	}
	/*$database="DiskCover_Prismanet";
	$server="mysql.diskcoversystem.com";
	$user="sa";
	$password="disk2017Cover";*/
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			echo "fallo conecion sql server";
		}
		$sql = "SELECT ".$value." FROM ".$tabla;
		if($filtro!=null and $filtro!='')
		{
			$sql =  $sql." WHERE ".$filtro." ";
		}
	}
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	//div inicial	
	$cont=array();
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$cont[$i]=$row[0];		
		$i++;
	}
	sqlsrv_close( $cid );
	return $cont;
}
//crear list option
function list_option($tabla,$value,$mostrar,$filtro=null)
{
	//realizamos conexion
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
	{
		$database=$_SESSION['INGRESO']['Base_Datos'];
		//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
		$user=$_SESSION['INGRESO']['Usuario_DB'];
		$password=$_SESSION['INGRESO']['Contraseña_DB'];
	}
	else
	{
		$database="DiskCover_Prismanet";
		$server="tcp:mysql.diskcoversystem.com, 11433";
		$user="sa";
		$password="disk2017Cover";
	}
	/*$database="DiskCover_Prismanet";
	$server="mysql.diskcoversystem.com";
	$user="sa";
	$password="disk2017Cover";*/
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			echo "fallo conecion sql server";
		}
		$sql = "SELECT ".$value." FROM ".$tabla;
		if($filtro!=null and $filtro!='')
		{
			$sql =  $sql." WHERE ".$filtro." ";
		}
	}
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	//div inicial	
	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		?>
			<a class="list-group-item list-group-item-action " id="list-<?php echo $i; ?>" 
			  data-toggle="list" href="#list-<?php echo $i; ?>" role="tab" aria-controls="<?php echo $i; ?>">
				<?php echo $row[0]; ?>
			</a>
			<script>
				$('#list-<?php echo $i; ?>').on('click', function (e) {
					  var select = document.getElementById('opcion'); //El <select>
					  //alert($("#list-home-list").text());
					  select.value = $.trim($("#list-<?php echo $i; ?>").text());
				});
			</script>
		<?php
		$i++;
	}
	?>
		<input id="opcion" name="opcion" type="hidden" value="">
		
	<?php
	sqlsrv_close( $cid );
}
function cone_ajaxMYSQL1()
{
	//verificamos si es sql server o mysql para consultar periodos
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$Base_Datos=$_SESSION['INGRESO']['Base_Datos'];
		$Usuario_DB=$_SESSION['INGRESO']['Usuario_DB'];
		$Contrase=$_SESSION['INGRESO']['Contraseña_DB'];
		$Puerto=$_SESSION['INGRESO']['Puerto'];
		$conexion=new mysqli($server.":".$Puerto, $Usuario_DB, $Contrase, $Base_Datos);
	}
	else
	{
		//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcoverMigra", "diskcover2019Migra@", "DiskCover_Empresas");
		$conexion=new mysqli("localhost:13306", "diskcoverMigra", "diskcover2019Migra@", "diskcover_empresas");
		//$conexion=new mysqli("mysql.diskcoversystem.com:13306", "diskcover", "disk2017Cover", "diskcover_empresas");
		//$conexion=new mysqli("localhost", "root", "", "diskcover_empresas");
		/*$connection = ssh2_connect('mysql.diskcoversystem.com', 22); 

		ssh2_auth_password($connection, 'diskcover', 'Dlcjvl1210');

		$tunnel = ssh2_tunnel($connection, 'DESTINATION IP', 3307);

		$db = new mysqli_connect('localhost', 'diskcover', 'disk2017Cover', 
								 'diskcover_empresas', 13306, $tunnel)
			or die ('Fail: ' . mysql_error()); */
	}
	$conexion->query("SET NAMES 'utf8'");
	return $conexion;
}
//crear select option
function cone_ajax()
{
	//realizamos conexion
  $cid ='';
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
	{
		$database=$_SESSION['INGRESO']['Base_Datos'];
		//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
		$user=$_SESSION['INGRESO']['Usuario_DB'];
		$password=$_SESSION['INGRESO']['Contraseña_DB'];
	}
	else
	{
		$database="DiskCover_Prismanet";
		$server="tcp:mysql.diskcoversystem.com, 11433";
		$user="sa";
		$password="disk2017Cover";
	}
	/*$database="DiskCover_Prismanet";
	$server="mysql.diskcoversystem.com";
	$user="sa";
	$password="disk2017Cover";*/
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			echo "fallo conecion sql server";
		}
	}
	return $cid;
}
//cerrar sesion caso de usar funciones para hacer consultas rapidas fuera del MVC
function cerrarSQLSERVERFUN($cid)
{
	sqlsrv_close( $cid );
}
//para devolver columna de impuesto en reporte pdf
function impuesto_re($codigo)
{
	$resul4='';
	if($codigo==1)
	{
		$resul4='RENTA';
	}
	if($codigo==2)
	{
		$resul4='IVA';
	}
	if($codigo==6)
	{
		$resul4='ISD';
	}
	return $resul4;
}
function concepto_re($codigo)
{
	$resul4='';
	//conectamos
	$cid=cone_ajax();
	$sql="select Concepto from Tipo_Concepto_Retencion where '".date('Y-m-d')."'  BETWEEN Fecha_Inicio AND Fecha_Final;";
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$resul4 = $row[0];
		//echo $row[0];
	}
	/*if($codigo=='332')
	{
		$resul4='Otras compras de bienes y servicios no sujetas a retención';
	}
	if($codigo==1)
	{
		$resul4='IVA';
	}
	if($codigo==1)
	{
		$resul4='ISD';
	}*/
	//cerramos
	cerrarSQLSERVERFUN($cid);
	return $resul4;
}
//caso guia de remision buscar el cliente
function buscar_cli($serie,$factura)
{
	$resul4=array();
	//conectamos
	$cid=cone_ajax();
	/*select * from Facturas where
	TC='FA' and serie='001005' and factura='674' and periodo='.'*/
	$sql="select Razon_Social,RUC_CI from Facturas where
	TC='FA' and serie='".$serie."' and factura=".$factura." and periodo='".$_SESSION['INGRESO']['periodo']."';";
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$resul4[0] = $row[0];
		$resul4[1] = $row[1];
		//echo $row[0];
	}
	//cerramos
	cerrarSQLSERVERFUN($cid);
	return $resul4;
}
//generica para contar registros $stmt= consulta generada
function contar_registros($stmt)
{
	$i=0;
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$i++;
	}
	return $i;
}
//contar registros caso paginador por ejemplo (sql server y MYSQL) 
function cantidaREGSQL_AJAX($tabla,$filtro=null,$base=null)
{
	//echo $filtro.' gg ';
	if($base==null or $base=='SQL SEVER')
	{
		$cid = Conectar::conexion('SQL SERVER');
		if($filtro!=null AND $filtro!='')
		{
			$sql = "SELECT count(*) as regis FROM ".$tabla." WHERE ".$filtro." ";
		}
		else
		{
			$sql = "SELECT count(*) as regis FROM ".$tabla;
		}
		//echo $sql;
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$row_count=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$row_count = $row[0];
			//echo $row[0];
		}
		cerrarSQLSERVERFUN($cid);
	}
	else
	{
		if($base=='MYSQL')
		{
			$cid = Conectar::conexion('MYSQL');
			
			if($filtro!=null AND $filtro!='')
			{
				$sql = "SELECT count(*) as regis FROM ".$tabla." WHERE ".$filtro." ";
			}
			else
			{
				$sql = "SELECT count(*) as regis FROM ".$tabla;
			}
			//echo $sql;
			$consulta=$cid->query($sql) or die($cid->error);
			$row_count=0;
			while($row=$consulta->fetch_assoc())
			{
				$row_count = $row['regis'];
				//echo $row[0];
			}
			$cid->close();
		}
	}
	//numero de columnas
	//$row_count = sqlsrv_num_rows( $stmt );
	return $row_count;
}
function paginador($tabla,$filtro=null,$link=null)
{
	//saber si hay paginador
	$pag=1;
	$start_from=null; 
	$record_per_page=null;
	if($pag==1) 
	{
		//obtenemos los valores
		$record_per_page = 10;
		$pagina = '';
		if(isset($_GET["pagina"]))
		{
		 $pagina = $_GET["pagina"];
		}
		else
		{
		 $pagina = 1;
		}
		$start_from = ($pagina-1)*$record_per_page;
		
		//buscamos cantidad de registros
		$filtros=" Item = '".$_SESSION['INGRESO']['item']."'  
		AND ( Periodo='".$_SESSION['INGRESO']['periodo']."' ) ";
		//hacemos los filtros
		if(isset($_POST['tipo']))
		{
			if($_POST['tipo']!='seleccione')
			{
				$filtros=$filtros." AND TD='".$_POST['tipo']."' ";
				$_SESSION['FILTRO']['cam1']=$_POST['tipo'];
			}
			else
			{
				unset($_SESSION['FILTRO']['cam1']);
			}
		}
		else
		{
			//si ya existe un filtro caso paginador
			if(isset($_SESSION['FILTRO']['cam1']))
			{
				$filtros=$filtros." AND TD='".$_SESSION['FILTRO']['cam1']."' ";
			}
		}
		if(isset($_POST['fechai']) and isset($_POST['fechaf']))
		{
			//echo $_POST['fechai'];
			if($_POST['fechai']!='' AND $_POST['fechaf']!='')
			{
				$fei = explode("/", $_POST['fechai']);
				$fef = explode("/", $_POST['fechaf']);
				if(strlen($fei[2])==2 AND strlen($fef[2])==2)
				{
					$filtros=$filtros." AND convert(datetime,(SUBSTRING(Clave_Acceso, 5, 4)+'/'
					+SUBSTRING(Clave_Acceso, 3, 2)+'/'+SUBSTRING(Clave_Acceso, 1, 2)+' 00:00:00.000 AM'))
					BETWEEN '".$fei[0].$fei[1].$fei[2]."' AND '".$fef[0].$fef[1].$fef[2]."' ";
					$_SESSION['FILTRO']['cam2']=$fei[0].'/'.$fei[1].'/'.$fei[2];
					$_SESSION['FILTRO']['cam3']=$fef[0].'/'.$fef[1].'/'.$fef[2];
				}
				else
				{
					$filtros=$filtros." AND convert(datetime,(SUBSTRING(Clave_Acceso, 5, 4)+'/'
					+SUBSTRING(Clave_Acceso, 3, 2)+'/'+SUBSTRING(Clave_Acceso, 1, 2)+' 00:00:00.000 AM'))
					BETWEEN '".$fei[2].$fei[0].$fei[1]."' AND '".$fef[2].$fef[0].$fef[1]."' ";
					$_SESSION['FILTRO']['cam2']=$fei[2].'/'.$fei[0].'/'.$fei[1];
					$_SESSION['FILTRO']['cam3']=$fef[2].'/'.$fef[0].'/'.$fef[1];
				}
				//echo $fei[0].' '.$fei[1].' '.$fei[2].' ';
				
				
			}
		}
		else
		{
			//si ya existe un filtro caso paginador
			if(isset($_SESSION['FILTRO']['cam2']) AND isset($_SESSION['FILTRO']['cam3']))
			{
				$fei = explode("/", $_SESSION['FILTRO']['cam2']);
				$fef = explode("/", $_SESSION['FILTRO']['cam3']);
				
				$filtros=$filtros." AND convert(datetime,(SUBSTRING(Clave_Acceso, 5, 4)+'/'
				+SUBSTRING(Clave_Acceso, 3, 2)+'/'+SUBSTRING(Clave_Acceso, 1, 2)+' 00:00:00.000 AM'))
				BETWEEN '".$fei[0].$fei[1].$fei[2]."' AND '".$fef[0].$fef[1].$fef[2]."' ";
			}
		}
			//$_POST['fechai']; 
		$total_records=cantidaREGSQL_AJAX($tabla,$filtro,'MYSQL');
		//echo ' ddd '.$total_records;
		//die();
		if($total_records>0)
		{
			$total_pages = ceil($total_records/$record_per_page);
		}
		else
		{
			$total_pages = 0;
		}
		//echo '  '.$total_pages;
		$start_loop = $pagina;
		$diferencia = $total_pages - $pagina;
		if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
		{
			$record_per_page = $start_from+10;
		}
		if($total_pages>0)
		{
			$start_loop1=$start_loop;
			if($diferencia <= 5)
			{
				$start_loop = $total_pages - 5;
				$start_loop1=$start_loop;
				if($start_loop < 0)
				{
					//$total_pages=$total_pages+$start_loop;
					$start_loop1=$start_loop;
					$start_loop=1;
				}
				if($start_loop == 0)
				{
					$start_loop=1;
				}
			}
			$end_loop = $start_loop1 + 4;
		}
		else
		{
			$start_loop=0;
			$end_loop=0;
		}
	}
	if($link==null)
	{
	?>
	<div class="box-footer clearfix">
		<ul class="pagination pagination-sm no-margin pull-right">
				<?php
			if($pag==1) 
			{
				if($pagina == 1)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=1'>Primera</a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina - 1)."'><<</a>";
					?>
					
				 <?php
				}
				if($pagina > 1)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=1'>Primera</a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina - 1)."'><<</a>";
					?>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=1">1</a></li>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=<?php echo ($pagina-1); ?>">&laquo;</a></li>
				 <?php
				}
				//echo $start_loop.' '.$end_loop;
				for($i=$start_loop; $i<=$end_loop; $i++)
				{     
					//echo "<a class='pagina' href='pagina.php?pagina=".$i."'>".$i."</a>";
						?>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
					 <?php
				}
				if($pagina <= $end_loop)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina + 1)."'>>></a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".$total_pages."'>Última</a>";
					?>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=<?php echo $pagina+1; ?>">&raquo;</a></li>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a></li>
					
				 <?php
				}
			
			}
		?>		
		</ul>
	</div>
	<?php
	}
	else
	{
		?>
	<div class="box-footer clearfix">
		<ul class="pagination pagination-sm no-margin pull-right">
				<?php
			if($pag==1) 
			{
				if($pagina == 1)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=1'>Primera</a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina - 1)."'><<</a>";
					?>
					
				 <?php
				}
				if($pagina > 1)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=1'>Primera</a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina - 1)."'><<</a>";
					?>
					<li><a href="<?php echo $link; ?>&pagina=1">1</a></li>
					<li><a href="<?php echo $link; ?>&pagina=<?php echo ($pagina-1); ?>">&laquo;</a></li>
				 <?php
				}
				//echo $start_loop.' '.$end_loop;
				for($i=$start_loop; $i<=$end_loop; $i++)
				{     
					//echo "<a class='pagina' href='pagina.php?pagina=".$i."'>".$i."</a>";
						?>
					<li><a href="rde.php?<?php echo $link; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
					 <?php
				}
				if($pagina <= $end_loop)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina + 1)."'>>></a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".$total_pages."'>Última</a>";
					?>
					<li><a href="<?php echo $link; ?>&pagina=<?php echo $pagina+1; ?>">&raquo;</a></li>
					<li><a href="<?php echo $link; ?>&pagina=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a></li>
					
				 <?php
				}
			
			}
		?>		
		</ul>
	</div>
	<?php
	}
}
//grilla generica para mostrar en caso de usar ajax
//$tabla caso donde sean necesaria varias grillas
function grilla_generica($stmt,$ti=null,$camne=null,$b=null,$ch=null,$tabla=null,$base=null,$estilo=false,$button=false)
{
	if($base==null or $base=='SQL SERVER')
	{
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
			foreach( $fieldMetadata as $name => $value) {
				if(!is_numeric($value))
				{
					if($value!='')
					{
						$cant++;
					}
				}
			}
		}
		if($ch!=null)
		{
			$ch1 = explode(",", $ch);
			$cant++;
		}
    if($button)
    {
      $cant++;
    }
		//si lleva o no border
		$bor='';
    $bor1='';
    $bor2='';
		if($b!=null and $b!='0')
		{
			$bor='style="border: #b2b2b2 1px solid;"';
      $bor1='border: #b2b2b2 1px solid;';
      $bor2 = 'border';
			//style="border-top: 1px solid #bce8f1;"
		}
		//colocar cero a tabla en caso de no existir definida ninguna
		if($tabla==null OR $tabla=='0' OR $tabla=='')
		{
			$tabla=0;
		}
		?>
 <?php if($estilo)
 { echo  ' <style type="text/css">
      #datos_t table {
  border-collapse: collapse;
 
}

#datos_t table, th, td {
  /*border: solid 1px black;*/
  padding: 2px;
}

#datos_t tbody tr:nth-child(even) {
  background:#fffff;
}

#datos_t tbody tr:nth-child(odd) {
  background: #e2fbff;;
}

#datos_t tbody tr:nth-child(even):hover {
  background: #DDB;
}

#datos_t tbody tr:nth-child(odd):hover {
  background: #DDA;
}


.sombra {
  width: 99%;
  box-shadow: 10px 10px 6px rgba(0, 0, 0, 0.6);
}
 </style>';
 }
 ?>

		<div class="sombra" style="">
            <table <?php echo $bor2; ?> class="table table-striped table-hover" id='datos_t'>
				<?php
				if($ti!='' or $ti!=null)
				{
			?>
					<tr>
						<th  <?php echo $bor; ?> colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
					</tr>
			<?php
				}
			?>
                <!-- <tr> -->
                  <thead>
					<?php
					//cantidad campos
					$cant=0;
					//guardamos los campos
					$campo='';
					//tipo de campos
					$tipo_campo=array();
					//guardamos posicion de un campo ejemplo fecha
					$cam_fech=array();
					//contador para fechas
					$cont_fecha=0;
					//obtenemos los campos 
					//en caso de tener check
					if($ch!=null)
					{
						echo "<th style='text-align: left;' id='tit_sel'>SEL</th>";
					}
          if($button)
          {
            echo "<th style='text-align: left;' id='tit_sel'></th>";
          }
          /*
          datetime = 93;

        */
					foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
						//$camp='';
						$i=0;
						//tipo de campo
						$ban=0;
						//texto
						if($fieldMetadata['Type']==-9)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//numero
						if($fieldMetadata['Type']==3)
						{
							//number_format($item_i['nombre'],2, ',', '.')
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						// echo $fieldMetadata['Type'].' ccc <br>';
						// echo $fieldMetadata['Name'].' ccc <br>';
						//caso fecha
						if($fieldMetadata['Type']==93)
						{
							$tipo_campo[($cant)]="style='text-align: left; width:80px;'";
							$ban=1;
							$cam_fech[$cont_fecha]=$cant;
							//contador para fechas
							$cont_fecha++;
						}
						//caso bit
						if($fieldMetadata['Type']==-7)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//caso int
						if($fieldMetadata['Type']==4)
						{
							$tipo_campo[($cant)]=" style='text-align: right;'";
							$ban=1;
						}
						//caso tinyint
						if($fieldMetadata['Type']==-6)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso smallint
						if($fieldMetadata['Type']==5)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso real
						if($fieldMetadata['Type']==7)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso float
						if($fieldMetadata['Type']==6)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//uniqueidentifier
						if($fieldMetadata['Type']==-11)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//ntext
						if($fieldMetadata['Type']==-10)
						{
							$tipo_campo[($cant)]="style='text-align: left; width:40px;'";
							$ban=1;
						}
						//rownum
						if($fieldMetadata['Type']==-5)
						{
							//echo " dddd ";
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//ntext
						if($fieldMetadata['Type']==12)
						{
							$tipo_campo[($cant)]="style='text-align: left;  width:40px;'";
							$ban=1;
						}
						if($ban==0)
						{
							echo ' no existe tipo '.$value.' '.$fieldMetadata['Name'].' '.$fieldMetadata['Type'];
						}
						foreach( $fieldMetadata as $name => $value) {
							
							if(!is_numeric($value))
							{
								if($value!='')
								{
									echo "<th  ".$bor." id='id_$cant' onclick='orde($cant)' ".$tipo_campo[$cant].">".$value."</th>";
									$camp=$value;
									$campo[$cant]=$camp;
									//echo ' dd '.$campo[$cant];
									$cant++;
									//echo $value.' cc '.$cant.' ';
								}
							}
						   //echo "$name: $value<br />";
						}
						
						  //echo "<br />";
					}
					/*for($i=0;$i<$cant;$i++)
					{
						echo $i.' gfggf '.$tipo_campo[$i];
					}*/
					?>
				<!-- </tr> -->
				</thead>
                 
					<?php
					//echo $cant.' fffff ';
					//obtener la configuracion para celdas personalizadas
					//campos a evaluar
					$campoe=array();
					//valor a verificar
					$campov=array();
					//campo a afectar 
					$campoaf=array();
					//adicional
					$adicional=array();
					//signos para comparar
					$signo=array();
					//titulo de proceso
					$tit=array();
					//indice de registros a comparar con datos
					$ind=0;
					//obtener valor en caso de mas de una condicion
					$con_in=0;
					if($camne!=null)
					{
						for($i=0;$i<count($camne['TITULO']);$i++)
						{
							if($camne['TITULO'][$i]=='color_fila')
							{	
								$tit[$ind]=$camne['TITULO'][$i];
								//temporar para indice
								//$temi=$i;
								//buscamos campos a evaluar
								$camneva = explode(",", $camne['CAMPOE'][$i]);
								//si solo es un campo
								if(count($camneva)==1)
								{
									$camneva1 = explode("=", $camneva[0]);
									$campoe[$ind]=$camneva1[0];
									$campov[$ind]=$camneva1[1];
									//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
								}
								else
								{
									//hacer bucle
								}
								//para los campos a afectar
								if(count($camne['CAMPOA'])==1 AND $i==0)
								{
									if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
									{
										$campoaf[$ind]='TODOS';
									}
									else
									{
										//otras opciones
									}
								}
								else
								{
									//bucle
									if(!empty($camne['CAMPOA'][$i]))
									{
										if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
										{
											$campoaf[$ind]='TODOS';
										}
										else
										{
											//otras opciones
										}
									}
								}
								//valor adicional en este caso color
								if(count($camne['ADICIONAL'])==1 AND $i==0)
								{
									$adicional[$ind]=$camne['ADICIONAL'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['ADICIONAL'][$i]))
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
								}
								//signo de comparacion
								if(count($camne['SIGNO'])==1 AND $i==0)
								{
									$signo[$ind]=$camne['SIGNO'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['SIGNO'][$i]))
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
								}
								$ind++;
								//echo ' pp '.count($camneva);
							}
							//caso de indentar columna
						
							//caso italica, subrayar, indentar
							if($camne['TITULO'][$i]=='italica' OR $camne['TITULO'][$i]=='subrayar' OR $camne['TITULO'][$i]=='indentar')
							{
								$tit[$ind]=$camne['TITULO'][$i];
									//buscamos campos a evaluar
								if(!is_array($camne['CAMPOE'][$i]))
								{
									$camneva = explode(",", $camne['CAMPOE'][$i]);
									//si solo es un campo
									if(count($camneva)==1)
									{
										$camneva1 = explode("=", $camneva[0]);
										$campoe[$ind]=$camneva1[0];
										$campov[$ind]=$camneva1[1];
										//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
									}
									else
									{
										//hacer bucle
									}
								}
								else
								{
									//es mas de un campo
									$con_in = count($camne['CAMPOE'][$i]);
									//recorremos registros
									for($j=0;$j<$con_in;$j++)
									{
										//echo $camne['CAMPOE'][$i][$j].' ';
										$camneva = explode(",", $camne['CAMPOE'][$i][$j]);
										//si solo es un campo
										if(count($camneva)==1)
										{
											$camneva1 = explode("=", $camneva[0]);
											$campoe[$ind][$j]=$camneva1[0];
											$campov[$ind][$j]=$camneva1[1];
											//echo ' pp '.$campoe[$ind][$j].' '.$campov[$ind][$j];
										}
									}
								}
								//para los campos a afectar
								if(!is_array($camne['CAMPOA'][$i]))
								{
									if(count($camne['CAMPOA'])==1 AND $i==0)
									{
										$campoaf[$ind]=$camne['CAMPOA'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['CAMPOA'][$i]))
										{
											//otras opciones
											$campoaf[$ind]=$camne['CAMPOA'][$i];
										}
									}
								}
								else
								{
									//recorremos el ciclo
									//es mas de un campo
									$con_in = count($camne['CAMPOA'][$i]);
									//recorremos registros
									for($j=0;$j<$con_in;$j++)
									{
										$campoaf[$ind][$j]=$camne['CAMPOA'][$i][$j];
										//echo ' pp '.$campoaf[$ind][$j];
									}
								}
								//valor adicional en este caso color
								
									if(count($camne['ADICIONAL'])==1 AND $i==0)
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['ADICIONAL'][$i]))
										{
											//es mas de un campo
											$con_in = count($camne['ADICIONAL'][$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$adicional[$ind][$j]=$camne['ADICIONAL'][$i][$j];
												//echo ' pp '.$adicional[$ind][$j];
											}
										}
									}
								
								
								//signo de comparacion
								if(!is_array($camne['SIGNO'][$i]))
								{
									if(count($camne['SIGNO'])==1 AND $i==0)
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['SIGNO'][$i]))
										{
											$signo[$ind]=$camne['SIGNO'][$i];
										}
									}
								}
								else
								{
									//es mas de un campo
									$con_in = count($camne['SIGNO'][$i]);
									for($j=0;$j<$con_in;$j++)
									{
										$signo[$ind][$j]=$camne['SIGNO'][$i][$j];
										//echo ' pp '.$signo[$ind][$j];
									}
								}
								$ind++;
							}
						}
					}
					$i=0;
					while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
							//para colocar identificador unicode_decode
							if($ch!=null)
							{
								if(count($ch1)==2)
								{
									$cch=$ch1[0];
									?>
										<tr <?php echo "id=ta_".$row[$cch]."";?> >
									<?php
								}
								else
								{
									//casos con mas id
									$cch='';
									$camch='';
									//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
									for($ca=0;$ca<count($ch1);$ca++)
									{
										if($ca<(count($ch1)-1))
										{
											$cch=$ch1[$ca];
                      if(is_object($row[$cch]))
                      {
                        $camch=$camch.$row[$cch]->format('Y-m-d').'--';
                      }else
                      {
                         $camch=$camch.$row[$cch].'--';
                      }
										}
									}
									$ca=$ca-1;
									?>
										<tr <?php echo "id=ta_".$camch."";?> >
									<?php
								}
							}
							else
							{
								?>
								<tr >
								<?php
							}
							if($ch!=null)
							{
								if(count($ch1)==2)
								{
									$cch=$ch1[0];
									echo "<td style='text-align: left; ".$bor1."'><input type='checkbox' id='id_".$row[$cch]."[]' name='".$ch1[1]."' value='".$row[$cch]."'
									onclick=\"validarc('id_".$row[$cch]."','".$tabla."')\"></td>";
								}
								else
								{
									//casos con mas id
									$cch='';
									$camch='';
									//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
									for($ca=0;$ca<count($ch1);$ca++)
									{
										if($ca<(count($ch1)-1))
										{
											$cch=$ch1[$ca];
                      if(is_object($row[$cch]))
                      {
                        $camch=$camch.$row[$cch]->format('Y-m-d').'--';
                      }else
                      {
                         $camch=$camch.$row[$cch].'--';
                      }
										}
									}
									$ca=$ca-1;
									echo "<td style='text-align: left; ".$bor."'><input type='checkbox' id='id_".$camch."' name='".$ch1[$ca]."[]' value='".$camch."'
									onclick=\"validarc('id_".$camch."','".$tabla."')\"></td>";
									//die();
								}
							}
              if($button)
              {
                foreach ($button as $key => $value) {
                $nombre = str_replace(' ','_',$value['nombre']);
                $icono = $value['icon'];
                $tipo = $value['tipo'];
                $id = '';
                $datos = explode(',',$value['dato'][0]);
                foreach ($datos as $key2 => $value2) {
                  if(is_numeric($value2))
                    {
                      $id.= '\''.$row[$value2].'\',';
                    }else
                    {
                      $id.= '\''.$value2.'\',';
                    }
                }
                $id=substr($id,0,-1);
               
                 echo '<td><button class="btn btn-'.$tipo.' btn-sm"  type="button" onclick="'.$nombre.'('.$id.')"><i class ="'.$icono.'"></i></button></td>';             
                }
                }

              // print_r($button);die();
							//comparamos con los valores de los array para personalizar las celdas
							//para titulo color fila
							$cfila1='';
							$cfila2='';
							//indentar
							$inden='';
							$indencam=array();
							$indencam1=array();
							//contador para caso indentar
							$conin=0;
							//contador caso para saber si cumple varias condiciones ejemplo italica TC=P OR TC=C
							$ca_it=0;
							//variable para colocar italica
							$ita1='';
							$ita2='';
							//contador para caso italicas
							$conita=0;
							//valores de campo a afectar
							$itacam1=array();
							//variables para subrayar
							//valores de campo a afectar en caso subrayar
							$subcam1=array();
							//contador caso subrayar
							$consub=0;
							//contador caso para saber si cumple varias condiciones ejemplo subrayar TC=P OR TC=C
							$ca_sub=0;
							//variable para colocar subrayar
							$sub1='';
							$sub2='';
							for($i=0;$i<$ind;$i++)
							{
								if($tit[$i]=='color_fila')
								{
									if(!is_array($campoe[$i]))
									{
										//campo a comparar
										$tin=$campoe[$i];
										//comparamos valor
										if($signo[$i]=='=')
										{
											if($row[$tin]==$campov[$i])
											{
												if($adicional[$i]=='black')
												{
													//activa condicion
													$cfila1='<B>';
													$cfila2='</B>';
												}
											}
										}
									}
								}
								if($tit[$i]=='indentar')
								{	
									if(!is_array($campoe[$i]))
									{
										//campo a comparar
										$tin=$campoe[$i];
										//comparamos valor
										if($signo[$i]=='=')
										{
											if($campov[$i]=='contar')
											{
												$inden1 = explode(".", $row[$tin]);
												//echo ' '.count($inden1);
												//hacemos los espacios
												//$inden=str_repeat("&nbsp;&nbsp;", count($inden1));
												if(count($inden1)>1)
												{
													$indencam1[$conin]=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", (count($inden1)-1));
												}
												else
												{
													$indencam1[$conin]="";
												}
												/*if(count($inden1)==1)
												{
													$inden='';
												}
												if(count($inden1)==2)
												{
													$inden='&nbsp;';
												}
												if(count($inden1)==3)
												{
													$inden='&nbsp;&nbsp;';
												}
												if(count($inden1)==3)
												{
													$inden='&nbsp;&nbsp;&nbsp;';
												}*/
											}
											$indencam[$conin]=$campoaf[$i];
											//echo $indencam[$conin].' dd ';
											$conin++;
										}
									}
								}
								if($tit[$i]=='italica')
								{	
									if(!is_array($campoe[$i]))
									{
										
									}
									else
									{
										//es mas de un campo
										$con_in = count($campoe[$i]);
										$ca_it=0;
										for($j=0;$j<$con_in;$j++)
										{
											$tin=$campoe[$i][$j];
											//echo ' pp '.$tin[$i][$j];
											//comparamos valor
											if($signo[$i][$j]=='=')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]==$campov[$i][$j])
												{
													$ca_it++;
												}
											}
											//si es diferente
											if($signo[$i][$j]=='<>')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]<>$campov[$i][$j])
												{
													$ca_it++;
												}
											}
											
										}
										$con_in = count($campoaf[$i]);
										for($j=0;$j<$con_in;$j++)
										{
											$itacam1[$conita]=$campoaf[$i][$j];
											//echo $itacam1[$conita].' ';
											$conita++;
										}
										//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
										if($ca_it==count($campoe[$i]))
										{
											$ita1='<em>';
											$ita2='</em>';
										}
										else
										{
											$ita1='';
											$ita2='';
										}
									}
									
								}
								if($tit[$i]=='subrayar')
								{	
									if(!is_array($campoe[$i]))
									{
										
									}
									else
									{
										//es mas de un campo
										$con_in = count($campoe[$i]);
										$ca_sub=0;
										$ca_sub1=0;
										for($j=0;$j<$con_in;$j++)
										{
											$tin=$campoe[$i][$j];
											//echo ' pp '.$tin[$i][$j];
											//comparamos valor
											if($signo[$i][$j]=='=')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]==$campov[$i][$j])
												{
													$ca_sub++;
													$ca_sub1++;
												}
											}
											//si es diferente
											if($signo[$i][$j]=='<>')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]<>$campov[$i][$j])
												{
													$ca_sub++;
												}
											}
											
										}
										$con_in = count($campoaf[$i]);
										for($j=0;$j<$con_in;$j++)
										{
											$subcam1[$consub]=$campoaf[$i][$j];
											//echo $subcam1[$consub].' ';
											$consub++;
										}
										//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
										$sub1='';
										$sub2='';
										//condicion para verificar si signo es "=" o no
										if($ca_sub1==0)
										{
											//condicion en caso de distintos
											if($ca_sub==count($campoe[$i]))
											{
												$sub1='<u>';
												$sub2='</u>';
											}
											else
											{
												$sub1='';
												$sub2='';
											}
										}
										else
										{
											$sub1='<u>';
											$sub2='</u>';
										}
									}
								}
							}
							//para check box
						
						for($i=0;$i<$cant;$i++)
						{
							//caso indentar
							for($j=0;$j<count($indencam);$j++)
							{
								if($indencam[$j]==$i)
								{
									$inden=$indencam1[$j];
								}
								else
								{
									$inden='';
								}
							}
							//caso italica
							$ita3="";
							$ita4="";
							for($j=0;$j<count($itacam1);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($itacam1[$j]==$i)
								{
									$ita3=$ita1;
									$ita4=$ita2;
								}
								
							}
							//caso subrayado
							$sub3="";
							$sub4="";
							for($j=0;$j<count($subcam1);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($subcam1[$j]==$i)
								{
									$sub3=$sub1;
									$sub4=$sub2;
								}
								
							}
							//caso de campos fechas
							for($j=0;$j<count($cam_fech);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($cam_fech[$j]==$i)
								{
									//$row[$i]=$row[$i]->format('Y-m-d H:i:s');
                  if(is_object($row[$i]))
                      {
                        $row[$i]=$row[$i]->format('Y-m-d');
                      }else
                      {
                         $row[$i]=$row[$i];
                      }
									// $row[$i]=$row[$i]->format('Y-m-d');
								}
								
							}
							//echo "<br/>";
							//formateamos texto si es decimal
							if($tipo_campo[$i]=="style='text-align: right;'")
							{
								//si es cero colocar -
								//1.1.02.03.01.001 2017
								if(number_format($row[$i],2, ',', '.')==0.00 OR number_format($row[$i],2, ',', '.')=='0,00')
								{
									if($row[$i]>0)
									{
										echo "<td ".$tipo_campo[$i]." ".$bor.">".$cfila1.$ita3.$sub3.$inden.number_format($row[$i],2, ',', '.').$sub4.$ita4.$cfila2."</td>";
									}
									else
									{
										echo "<td ".$tipo_campo[$i]." ".$bor.">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
									}
									//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
								}
								else
								{
									//si es negativo colocar rojo
									if($row[$i]<0)
									{
										//reemplazo una parte de la cadena por otra
										$longitud_cad = strlen($tipo_campo[$i]); 
										$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
										echo "<td ".$cam2." ".$bor."> ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, '.', ',')."".$sub4.$ita4.$cfila2."</td>";
									}
									else
									{
										echo "<td ".$tipo_campo[$i]." ".$bor.">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, '.', ',')."".$sub4.$ita4.$cfila2."</td>";
									}
								}
								
							}
							else
							{
								if(strlen($row[$i])<=50)
								{
									echo "<td ".$tipo_campo[$i]." ".$bor.">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
								}
								else
								{
									$resultado = substr($row[$i], 0, 50);
									//echo $resultado; // imprime "ue"
									echo "<td ".$bor." ".$tipo_campo[$i]." data-toggle='tooltip' data-placement='left' title='".$row[$i]."'>".$cfila1.$ita3.$inden.$sub3."".$resultado."...".$sub4.$ita4.$cfila2."</td>";
								}
							}
						}
						/*$cam=$campo[$i];
						echo "<td>".$row['DG']."</td>";
						echo "<td>".$row['Codigo']."</td>";
						echo "<td>".$row['Cuenta']."</td>";
						echo "<td>".$row['Saldo_Anterior']."</td>";
						echo "<td>".$row['Debitos']."</td>";
						echo "<td>".$row['Creditos']."</td>";
						echo "<td>".$row['Saldo_Total']."</td>";
						echo "<td>".$row['TC']."</td>";*/
						 ?>
						  </tr>
						  <?php
						
						//$campo
						  //echo $row[$i].", <br />";
						  $i++;
						  if($cant==($i))
						  {
							  
							  //echo $cant.' ddddd '.$i;
							  $i=0;
							 
						  }
					}
		 ?>
			</table>
		</div>
		  <?php
	}
	else
	{
		if($base=='MYSQL')
		{
			$info_campo = $stmt->fetch_fields();
			$cant=0;
			//guardamos los campos
			$campo='';
			foreach ($info_campo as $valor) 
			{
				$cant++;
			}
			if($ch!=null)
			{
				$ch1 = explode(",", $ch);
				$cant++;
			}
			//si lleva o no border
			$bor='';
			if($b!=null and $b!='0')
			{
				$bor='table-bordered1';
				//style="border-top: 1px solid #bce8f1;"
			}
			//colocar cero a tabla en caso de no existir definida ninguna
			if($tabla==null OR $tabla=='0' OR $tabla=='')
			{
				$tabla=0;
			}
					//si lleva o no border
		$bor='';
		$bor1='';
		$bor2='';
		if($b!=null and $b!='0')
		{
			$bor='style="border: #b2b2b2 1px solid;"';
			$bor1='border: #b2b2b2 1px solid;';
			$bor2 = 'border';
			//style="border-top: 1px solid #bce8f1;"
		}
		//colocar cero a tabla en caso de no existir definida ninguna
		if($tabla==null OR $tabla=='0' OR $tabla=='')
		{
			$tabla=0;
		}
		?>
	<?php if($estilo)
	 { 
		echo  ' <style type="text/css">
				#datos_t table {
				border-collapse: collapse;
			}

			#datos_t table, th, td {
			  /*border: solid 1px black;*/
			  padding: 2px;
			}

			#datos_t tbody tr:nth-child(even) {
			  background:#fffff;
			}

			#datos_t tbody tr:nth-child(odd) {
			  background: #e2fbff;;
			}

			#datos_t tbody tr:nth-child(even):hover {
			  background: #DDB;
			}

			#datos_t tbody tr:nth-child(odd):hover {
			  background: #DDA;
			}

			.sombra {
			  width: 99%;
			  box-shadow: 10px 10px 6px rgba(0, 0, 0, 0.6);
			}
		</style>';
	 }
			?>

				<div class="sombra" style="">
				<!--<div class="box-body no-padding">-->
					<table <?php echo $bor2; ?> class="table table-striped table-hover" id='datos_t' >
						<?php
						if($ti!='' or $ti!=null)
						{
					?>
							<tr>
								<th  colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
							</tr>
					<?php
						}
					?>
						<tr>
							<th colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
						</tr>
						<thead>
							<?php
							//cantidad campos
							$cant=0;
							//guardamos los campos
							$campo='';
							//tipo de campos
							$tipo_campo=array();
							//guardamos posicion de un campo ejemplo fecha
							$cam_fech=array();
							//contador para fechas
							$cont_fecha=0;
							//obtenemos los campos 
							//en caso de tener check
							if($ch!=null)
							{
								echo "<th style='text-align: left;' id='tit_sel'>SEL</th>";
							}
							foreach ($info_campo as $valor) 
							{
								//$camp='';
								$i=0;
								//tipo de campo
								/*
								tinyint_    1   boolean_    1   smallint_    2 int_        3
								float_      4   double_     5   real_        5 timestamp_    7
								bigint_     8   serial      8   mediumint_    9 date_        10
								time_       11  datetime_   12  year_        13 bit_        16
								decimal_    246 text_       252 tinytext_    252 mediumtext_    252
								longtext_   252 tinyblob_   252 mediumblob_    252 blob_        252
								longblob_   252 varchar_    253 varbinary_    253 char_        254
								binary_     254
								*/
								$ban=0;
								//texto
								if( $valor->type==7 OR $valor->type==8 OR $valor->type==10
								 OR $valor->type==11 OR $valor->type==12 OR $valor->type==13 OR $valor->type==16 
								 OR $valor->type==252 OR $valor->type==253 OR $valor->type==254 )
								{
									$tipo_campo[($cant)]="style='text-align: left; width:40px;'";
									$ban=1;
								}
								if( $valor->type==10 OR $valor->type==11 OR $valor->type==12  )
								{
									$tipo_campo[($cant)]="style='text-align: left; width:80px;'";
									$ban=1;
								}
								//numero
								if($valor->type==3 OR $valor->type==2 OR $valor->type==4 OR $valor->type==5
								 OR isset($valor->type['Type'])==8  OR ($valor->type)==8  OR $valor->type==9 OR $valor->type==246)
								{
									//number_format($item_i['nombre'],2, ',', '.')
									$tipo_campo[($cant)]="style='text-align: right;'";
									$ban=1;
								}
								if($ban==0)
								{
									echo ' no existe tipo '.$valor->type.' '.$valor->name.' '.$valor->table;
								}
								echo "<th ".$tipo_campo[$cant].">".$valor->name."</th>";
											$camp=$valor->name;
											$campo[$cant]=$camp;
											//echo ' dd '.$campo[$cant];
											$cant++;
							}
							?>
						</thead>
						<?php
						//echo $cant.' fffff ';
							//obtener la configuracion para celdas personalizadas
							//campos a evaluar
							$campoe=array();
							//valor a verificar
							$campov=array();
							//campo a afectar 
							$campoaf=array();
							//adicional
							$adicional=array();
							//signos para comparar
							$signo=array();
							//titulo de proceso
							$tit=array();
							//indice de registros a comparar con datos
							$ind=0;
							//obtener valor en caso de mas de una condicion
							$con_in=0;
							if($camne!=null)
							{
								for($i=0;$i<count($camne['TITULO']);$i++)
								{
									if($camne['TITULO'][$i]=='color_fila')
									{	
										$tit[$ind]=$camne['TITULO'][$i];
										//temporar para indice
										//$temi=$i;
										//buscamos campos a evaluar
										$camneva = explode(",", $camne['CAMPOE'][$i]);
										//si solo es un campo
										if(count($camneva)==1)
										{
											$camneva1 = explode("=", $camneva[0]);
											$campoe[$ind]=$camneva1[0];
											$campov[$ind]=$camneva1[1];
											//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
										}
										else
										{
											//hacer bucle
										}
										//para los campos a afectar
										if(count($camne['CAMPOA'])==1 AND $i==0)
										{
											if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
											{
												$campoaf[$ind]='TODOS';
											}
											else
											{
												//otras opciones
											}
										}
										else
										{
											//bucle
											if(!empty($camne['CAMPOA'][$i]))
											{
												if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
												{
													$campoaf[$ind]='TODOS';
												}
												else
												{
													//otras opciones
												}
											}
										}
										//valor adicional en este caso color
										if(count($camne['ADICIONAL'])==1 AND $i==0)
										{
											$adicional[$ind]=$camne['ADICIONAL'][$i];
										}
										else
										{
											//bucle
											if(!empty($camne['ADICIONAL'][$i]))
											{
												$adicional[$ind]=$camne['ADICIONAL'][$i];
											}
										}
										//signo de comparacion
										if(count($camne['SIGNO'])==1 AND $i==0)
										{
											$signo[$ind]=$camne['SIGNO'][$i];
										}
										else
										{
											//bucle
											if(!empty($camne['SIGNO'][$i]))
											{
												$signo[$ind]=$camne['SIGNO'][$i];
											}
										}
										$ind++;
										//echo ' pp '.count($camneva);
									}
									//caso italica, subrayar, indentar
									if($camne['TITULO'][$i]=='italica' OR $camne['TITULO'][$i]=='subrayar' OR $camne['TITULO'][$i]=='indentar')
									{
										$tit[$ind]=$camne['TITULO'][$i];
											//buscamos campos a evaluar
										if(!is_array($camne['CAMPOE'][$i]))
										{
											$camneva = explode(",", $camne['CAMPOE'][$i]);
											//si solo es un campo
											if(count($camneva)==1)
											{
												$camneva1 = explode("=", $camneva[0]);
												$campoe[$ind]=$camneva1[0];
												$campov[$ind]=$camneva1[1];
												//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
											}
											else
											{
												//hacer bucle
											}
										}
										else
										{
											//es mas de un campo
											$con_in = count($camne['CAMPOE'][$i]);
											//recorremos registros
											for($j=0;$j<$con_in;$j++)
											{
												//echo $camne['CAMPOE'][$i][$j].' ';
												$camneva = explode(",", $camne['CAMPOE'][$i][$j]);
												//si solo es un campo
												if(count($camneva)==1)
												{
													$camneva1 = explode("=", $camneva[0]);
													$campoe[$ind][$j]=$camneva1[0];
													$campov[$ind][$j]=$camneva1[1];
													//echo ' pp '.$campoe[$ind][$j].' '.$campov[$ind][$j];
												}
											}
										}
										//para los campos a afectar
										if(!is_array($camne['CAMPOA'][$i]))
										{
											if(count($camne['CAMPOA'])==1 AND $i==0)
											{
												$campoaf[$ind]=$camne['CAMPOA'][$i];
											}
											else
											{
												//bucle
												if(!empty($camne['CAMPOA'][$i]))
												{
													//otras opciones
													$campoaf[$ind]=$camne['CAMPOA'][$i];
												}
											}
										}
										else
										{
											//recorremos el ciclo
											//es mas de un campo
											$con_in = count($camne['CAMPOA'][$i]);
											//recorremos registros
											for($j=0;$j<$con_in;$j++)
											{
												$campoaf[$ind][$j]=$camne['CAMPOA'][$i][$j];
												//echo ' pp '.$campoaf[$ind][$j];
											}
										}
										//valor adicional en este caso color
										
											if(count($camne['ADICIONAL'])==1 AND $i==0)
											{
												$adicional[$ind]=$camne['ADICIONAL'][$i];
											}
											else
											{
												//bucle
												if(!empty($camne['ADICIONAL'][$i]))
												{
													//es mas de un campo
													$con_in = count($camne['ADICIONAL'][$i]);
													for($j=0;$j<$con_in;$j++)
													{
														$adicional[$ind][$j]=$camne['ADICIONAL'][$i][$j];
														//echo ' pp '.$adicional[$ind][$j];
													}
												}
											}
										
										
										//signo de comparacion
										if(!is_array($camne['SIGNO'][$i]))
										{
											if(count($camne['SIGNO'])==1 AND $i==0)
											{
												$signo[$ind]=$camne['SIGNO'][$i];
											}
											else
											{
												//bucle
												if(!empty($camne['SIGNO'][$i]))
												{
													$signo[$ind]=$camne['SIGNO'][$i];
												}
											}
										}
										else
										{
											//es mas de un campo
											$con_in = count($camne['SIGNO'][$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$signo[$ind][$j]=$camne['SIGNO'][$i][$j];
												//echo ' pp '.$signo[$ind][$j];
											}
										}
										$ind++;
									}
								}
							}
							$i=0;
							while ($row = $stmt->fetch_row()) 
							//while($row=$stmt->fetch_array())
							//while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
							{
								//para colocar identificador unicode_decode
								if($ch!=null)
								{
									if(count($ch1)==2)
									{
										$cch=$ch1[0];
										?>
											<tr <?php echo "id=ta_".$row[$cch]."";?> >
										<?php
									}
									else
									{
										//casos con mas id
										$cch='';
										$camch='';
										//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
										for($ca=0;$ca<count($ch1);$ca++)
										{
											if($ca<(count($ch1)-1))
											{
												$cch=$ch1[$ca];
												$camch=$camch.$row[$cch].'--';
											}
										}
										$ca=$ca-1;
										?>
											<tr <?php echo "id=ta_".$camch."";?> >
										<?php
									}
								}
								else
								{
									?>
									<tr >
									<?php
								}
								if($ch!=null)
								{
									if(count($ch1)==2)
									{
										$cch=$ch1[0];
										echo "<td style='text-align: left;".$bor1."'><input type='checkbox' id='id_".$row[$cch]."' name='".$ch1[1]."[]' value='".$row[$cch]."'
										onclick=\"validarc('id_".$row[$cch]."','".$tabla."')\"></td>";
									}
									else
									{
										//casos con mas id
										$cch='';
										$camch='';
										//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
										for($ca=0;$ca<count($ch1);$ca++)
										{
											if($ca<(count($ch1)-1))
											{
												$cch=$ch1[$ca];
												$camch=$camch.$row[$cch].'--';
											}
										}
										$ca=$ca-1;
										echo "<td style='text-align: left;' ".$bor."><input type='checkbox' id='id_".$camch."' name='".$ch1[$ca]."[]' value='".$camch."'
										onclick=\"validarc('id_".$camch."','".$tabla."')\"></td>";
										//die();
									}
								}
								//comparamos con los valores de los array para personalizar las celdas
								//para titulo color fila
								$cfila1='';
								$cfila2='';
								//indentar
								$inden='';
								$indencam=array();
								$indencam1=array();
								//contador para caso indentar
								$conin=0;
								//contador caso para saber si cumple varias condiciones ejemplo italica TC=P OR TC=C
								$ca_it=0;
								//variable para colocar italica
								$ita1='';
								$ita2='';
								//contador para caso italicas
								$conita=0;
								//valores de campo a afectar
								$itacam1=array();
								//variables para subrayar
								//valores de campo a afectar en caso subrayar
								$subcam1=array();
								//contador caso subrayar
								$consub=0;
								//contador caso para saber si cumple varias condiciones ejemplo subrayar TC=P OR TC=C
								$ca_sub=0;
								//variable para colocar subrayar
								$sub1='';
								$sub2='';
								for($i=0;$i<$ind;$i++)
								{
									if($tit[$i]=='color_fila')
									{
										if(!is_array($campoe[$i]))
										{
											//campo a comparar
											$tin=$campoe[$i];
											//comparamos valor
											if($signo[$i]=='=')
											{
												if($row[$tin]==$campov[$i])
												{
													if($adicional[$i]=='black')
													{
														//activa condicion
														$cfila1='<B>';
														$cfila2='</B>';
													}
												}
											}
										}
									}
									if($tit[$i]=='indentar')
									{	
										if(!is_array($campoe[$i]))
										{
											//campo a comparar
											$tin=$campoe[$i];
											//comparamos valor
											if($signo[$i]=='=')
											{
												if($campov[$i]=='contar')
												{
													$inden1 = explode(".", $row[$tin]);
													//echo ' '.count($inden1);
													//hacemos los espacios
													//$inden=str_repeat("&nbsp;&nbsp;", count($inden1));
													if(count($inden1)>1)
													{
														$indencam1[$conin]=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", (count($inden1)-1));
													}
													else
													{
														$indencam1[$conin]="";
													}
												}
												$indencam[$conin]=$campoaf[$i];
												//echo $indencam[$conin].' dd ';
												$conin++;
											}
										}
									}
									if($tit[$i]=='italica')
									{	
										if(!is_array($campoe[$i]))
										{
											
										}
										else
										{
											//es mas de un campo
											$con_in = count($campoe[$i]);
											$ca_it=0;
											for($j=0;$j<$con_in;$j++)
											{
												$tin=$campoe[$i][$j];
												//echo ' pp '.$tin[$i][$j];
												//comparamos valor
												if($signo[$i][$j]=='=')
												{
													//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
													if($row[$tin]==$campov[$i][$j])
													{
														$ca_it++;
													}
												}
												//si es diferente
												if($signo[$i][$j]=='<>')
												{
													//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
													if($row[$tin]<>$campov[$i][$j])
													{
														$ca_it++;
													}
												}
												
											}
											$con_in = count($campoaf[$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$itacam1[$conita]=$campoaf[$i][$j];
												//echo $itacam1[$conita].' ';
												$conita++;
											}
											//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
											if($ca_it==count($campoe[$i]))
											{
												$ita1='<em>';
												$ita2='</em>';
											}
											else
											{
												$ita1='';
												$ita2='';
											}
										}
										
									}
									if($tit[$i]=='subrayar')
									{	
										if(!is_array($campoe[$i]))
										{
											
										}
										else
										{
											//es mas de un campo
											$con_in = count($campoe[$i]);
											$ca_sub=0;
											$ca_sub1=0;
											for($j=0;$j<$con_in;$j++)
											{
												$tin=$campoe[$i][$j];
												//echo ' pp '.$tin[$i][$j];
												//comparamos valor
												if($signo[$i][$j]=='=')
												{
													//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
													if($row[$tin]==$campov[$i][$j])
													{
														$ca_sub++;
														$ca_sub1++;
													}
												}
												//si es diferente
												if($signo[$i][$j]=='<>')
												{
													//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
													if($row[$tin]<>$campov[$i][$j])
													{
														$ca_sub++;
													}
												}
												
											}
											$con_in = count($campoaf[$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$subcam1[$consub]=$campoaf[$i][$j];
												//echo $subcam1[$consub].' ';
												$consub++;
											}
											//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
											$sub1='';
											$sub2='';
											//condicion para verificar si signo es "=" o no
											if($ca_sub1==0)
											{
												//condicion en caso de distintos
												if($ca_sub==count($campoe[$i]))
												{
													$sub1='<u>';
													$sub2='</u>';
												}
												else
												{
													$sub1='';
													$sub2='';
												}
											}
											else
											{
												$sub1='<u>';
												$sub2='</u>';
											}
										}
									}
								}
								//para check box
							
								for($i=0;$i<$cant;$i++)
								{
									//caso indentar
									for($j=0;$j<count($indencam);$j++)
									{
										if($indencam[$j]==$i)
										{
											$inden=$indencam1[$j];
										}
										else
										{
											$inden='';
										}
									}
									//caso italica
									$ita3="";
									$ita4="";
									for($j=0;$j<count($itacam1);$j++)
									{
										//echo $itacam1[$j].' ssscc '.$i;
										if($itacam1[$j]==$i)
										{
											$ita3=$ita1;
											$ita4=$ita2;
										}
										
									}
									//caso subrayado
									$sub3="";
									$sub4="";
									for($j=0;$j<count($subcam1);$j++)
									{
										//echo $itacam1[$j].' ssscc '.$i;
										if($subcam1[$j]==$i)
										{
											$sub3=$sub1;
											$sub4=$sub2;
										}
										
									}
									//caso de campos fechas
									for($j=0;$j<count($cam_fech);$j++)
									{
										//echo $itacam1[$j].' ssscc '.$i;
										if($cam_fech[$j]==$i)
										{
											//$row[$i]=$row[$i]->format('Y-m-d H:i:s');
											$row[$i]=$row[$i]->format('Y-m-d');
										}
										
									}
									//echo "<br/>";
									//formateamos texto si es decimal
									if($tipo_campo[$i]=="style='text-align: right;'")
									{
										//si es cero colocar -
										if(number_format($row[$i],2, '.', ',')==0 OR number_format($row[$i],2, '.', ',')=='0,00')
										{
											echo "<td ".$bor." ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
										}
										else
										{
											//si es negativo colocar rojo
											if($row[$i]<0)
											{
												//reemplazo una parte de la cadena por otra
												$longitud_cad = strlen($tipo_campo[$i]); 
												$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
												echo "<td ".$bor." ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, '.', ',')."".$sub4.$ita4.$cfila2."</td>";
											}
											else
											{
												echo "<td ".$bor." ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, '.', ',')."".$sub4.$ita4.$cfila2."</td>";
											}
										}
										
									}
									else
									{
										if(strlen($row[$i])<=50)
										{
											echo "<td ".$bor." ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
										}
										else
										{
											$resultado = substr($row[$i], 0, 50);
											//echo $resultado; // imprime "ue"
											echo "<td ".$bor." ".$tipo_campo[$i]." data-toggle='tooltip' data-placement='left' title='".$row[$i]."'>".$cfila1.$ita3.$inden.$sub3."".$resultado."...".$sub4.$ita4.$cfila2."</td>";
										}
									}
								}
								/*$cam=$campo[$i];
								echo "<td>".$row['DG']."</td>";
								echo "<td>".$row['Codigo']."</td>";
								echo "<td>".$row['Cuenta']."</td>";
								echo "<td>".$row['Saldo_Anterior']."</td>";
								echo "<td>".$row['Debitos']."</td>";
								echo "<td>".$row['Creditos']."</td>";
								echo "<td>".$row['Saldo_Total']."</td>";
								echo "<td>".$row['TC']."</td>";*/
								 ?>
								  </tr>
								  <?php
								
								//$campo
								  //echo $row[$i].", <br />";
								  $i++;
								  if($cant==($i))
								  {
									  
									  //echo $cant.' ddddd '.$i;
									  $i=0;
									 
								  }
							}
						?>
					</table>
				</div>
				<?php
		}
	}
}	
//para mandar correo con archivo adjunto
function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) 
{
    $file = $path.$filename;
    $file_size = filesize($file);
    $handle = fopen($file, "r");
    $content = fread($handle, $file_size);
    fclose($handle);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $name = basename($file);
    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= $message."\r\n\r\n";
    $header .= "--".$uid."\r\n";
    $header .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n"; // use different content types here
    $header .= "Content-Transfer-Encoding: base64\r\n";
    $header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
    $header .= $content."\r\n\r\n";
    $header .= "--".$uid."--";
	//echo $mailto." ".$subject." "."envio"." ".$header;
    if (mail($mailto, $subject, "envio", $header)) {
        echo "Correo Enviado ... OK ".$filename; // or use booleans here
    } else {
        echo "No se Envio Correo ... ERROR! ".$filename;
    }
}

//FUNCION PARA ADCTUALIZAR GENERICA
function update_generico($datos,$tabla,$campoWhere)
{
	$campos_db = dimenciones_tabla($tabla);
	$conn = new Conectar();
	$cid=$conn->conexion();	
	$wherelist ='';
   	$sql = 'UPDATE '.$tabla.' SET '; 
   	 $set='';
   	foreach ($datos as $key => $value) {
   		foreach ($campos_db as $key => $value1) 
   		{
   			if($value1->COLUMN_NAME==$value['campo'])
   				{
   					if($value1->CHARACTER_MAXIMUM_LENGTH != '' && $value1->CHARACTER_MAXIMUM_LENGTH != null)
   						{
   							$set .=$value['campo']."='".substr($value['dato'],0,$value1->CHARACTER_MAXIMUM_LENGTH)."',";
   				        }else
   				        {
   				        	$set .=$value['campo']."='".$value['dato']."',";
   				        }
   	            }

   		}
   		//print_r($value['campo']);
   	}
   	$set = substr($set,0,-1);
   	foreach ($campoWhere as $key => $value) {
   		//print_r($value['valor']);
   		if(is_numeric($value['valor']))
   		{
        if(isset($value['tipo']) && $value['tipo'] =='string')
        {

          $wherelist.= $value['campo']."='".$value['valor']."' AND ";
        }else
        {
          $wherelist.= $value['campo'].'='.$value['valor'].' AND ';
        }
   		}else{
   		  $wherelist.= $value['campo']."='".$value['valor']."' AND ";
   	    }
   	}
   	$wherelist = substr($wherelist,0,-5);
   	$where = "WHERE ".$wherelist;   
   	$sql = $sql.$set.$where;
   	// print_r($sql);	die();
   	$stmt = sqlsrv_query($cid, $sql);
	    if( $stmt === false)  
	      {  
		     echo "Error en consulta PA.\n";  
		     return -1;
		     die( print_r( sqlsrv_errors(), true));  
	      }
	      else{
	      	return 1;
	      }  
}


//FUNCION DE IONSERTAR GENERICO
function insert_generico($tabla=null,$datos=null)
{
	$conn = new Conectar();
	$cid=$conn->conexion();
	$sql = "SELECT * from Information_Schema.Tables where TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME='".$tabla."' ORDER BY TABLE_NAME";
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	$tabla_="";
	while( $obj = sqlsrv_fetch_object( $stmt)) 
	{
		//echo $obj->TABLE_NAME."<br />";
		$tabla_=$obj->TABLE_NAME;
	}
	if($tabla_!='')
	{
		//buscamos los campos
		$sql="SELECT        TOP (1) sys.sysindexes.rows
		FROM   sys.sysindexes INNER JOIN
		sys.sysobjects ON sys.sysindexes.id = sys.sysobjects.id
		WHERE   (sys.sysobjects.xtype = 'U') AND (sys.sysobjects.name = '".$tabla_."')
		ORDER BY sys.sysindexes.indid";
		
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta.\n";  
			die( print_r( sqlsrv_errors(), true));  
		} 
		$tabla_cc=0;
		while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			//cantidad de campos
			$tabla_cc=$obj->rows;
		}
		$sql="SELECT COLUMN_NAME,DATA_TYPE,IS_NULLABLE,CHARACTER_MAXIMUM_LENGTH
		FROM Information_Schema.Columns
		WHERE TABLE_NAME = '".$tabla_."'";
		
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		} 
		//consulta sql
		$sql_="INSERT INTO ".$tabla_."
			  (";
		$sql_v=" VALUES 
		(";
		$fecha_actual = date("Y-m-d"); 
		while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			if($obj->COLUMN_NAME!='ID')
			{
				$sql_=$sql_.$obj->COLUMN_NAME.",";
			}
			
			//recorremos los datos
			$ban=0;
			for($i=0;$i<count($datos);$i++)
			{
				if($obj->COLUMN_NAME==$datos[$i]['campo'])
				{
					if($obj->CHARACTER_MAXIMUM_LENGTH != '' && $obj->CHARACTER_MAXIMUM_LENGTH != null)
					{
						$datos[$i]['dato'] =substr($datos[$i]['dato'],0,$obj->CHARACTER_MAXIMUM_LENGTH);
			        }
			       
					if($obj->DATA_TYPE=='int identity')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='nvarchar')
					{
						$sql_v=$sql_v."'".$datos[$i]['dato']."',";
					}
					if($obj->DATA_TYPE=='ntext')
					{
						$sql_v=$sql_v."'".$datos[$i]['dato']."',";
					}
					if($obj->DATA_TYPE=='tinyint')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='real')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='bit')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='smalldatetime' OR $obj->DATA_TYPE=='datetime')
					{
            if(!is_array($datos[$i]['dato'])) {              
            $sql_v=$sql_v."'".$datos[$i]['dato']."',";
            }else{
             $sql_v=$sql_v."'".$datos[$i]['dato']->format('Y-m-d')."',";
            }
					}
					if($obj->DATA_TYPE=='money')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='int')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='float')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='smallint')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='uniqueidentifier')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					$ban=1;
				}
			}
			//por defaul
			if($ban==0)
			{
				if($obj->DATA_TYPE=='int identity')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='nvarchar')
				{
					$sql_v=$sql_v."'.',";
				}
				if($obj->DATA_TYPE=='ntext')
				{
					$sql_v=$sql_v."'.',";
				}
				if($obj->DATA_TYPE=='tinyint')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='real')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='bit')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='smalldatetime' OR $obj->DATA_TYPE=='datetime')
				{
					$sql_v=$sql_v."'".$fecha_actual."',";
				}
				if($obj->DATA_TYPE=='money')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='int')
				{
					if($obj->COLUMN_NAME=='ID')
					{
						$sql_v=$sql_v."";
					}
					else
					{
						$sql_v=$sql_v."0,";
					}
				}
				if($obj->DATA_TYPE=='float')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='smallint')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='uniqueidentifier')
				{
					$sql_v=$sql_v."0,";
				}
			}
		}
		$longitud_cad = strlen($sql_); 
		$cam2 = substr_replace($sql_,")",$longitud_cad-1,1); 
		$longitud_cad = strlen($sql_v); 
		$v2 = substr_replace($sql_v,")",$longitud_cad-1,1);
		//echo $ll =  $cam2.$v2;
		// print_r($cam2.$v2);
		// die();
		$stmt = sqlsrv_query( $cid, $cam2.$v2);
		// echo  $cam2.$v2 ;die();
		if( $stmt === false)  
		{  
      // return -1;
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		
		  // cerrarSQLSERVERFUN($cid);
		
	}
}


function dimenciones_tabla($tabla)
{
	$conn = new Conectar();
	$cid=$conn->conexion();
	$sql = "SELECT * from Information_Schema.Tables where TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME='".$tabla."' ORDER BY TABLE_NAME";
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	$tabla_="";
	while($obj = sqlsrv_fetch_object( $stmt)) 
	{
		//echo $obj->TABLE_NAME."<br />";
		$tabla_=$obj->TABLE_NAME;
	}
	if($tabla_ != '')
	{
		$sql="SELECT COLUMN_NAME,DATA_TYPE,IS_NULLABLE,CHARACTER_MAXIMUM_LENGTH
		FROM Information_Schema.Columns
		WHERE TABLE_NAME = '".$tabla_."'";
		
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		} 
		$campos = array();
		while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			$campos[]=$obj;
		}
    // print_r($campos);die();
		return $campos;
	}
}

function dimenciones_tabl($len)
{
  $px = 8;
  if($len > 60)
  {
    $val = 60*8;
    return $val.'px';
  }elseif ($len==1) {
     $val = ($len+3)*8;
    return $val.'px';
  }elseif ($len >= 10 And $len<=13){
     $val = ($len+3)*8;
    return $val.'px';
  }elseif ($len==10){
     $val = ($len+3)*8;
    return $val.'px';
  }elseif ($len>3 And $len <6) {
     $val = ($len+3)*8;
    return $val.'px';
  }elseif ($len==3){
     $val = ($len+3)*8;
    return $val.'px';
  }elseif ($len>13 And $len<60) {
     $val = ($len+3)*8;
    return $val.'px';
  }else
  {
     $val = ($len+3)*8;
    return $val.'px';
  }
}

  function numero_comprobante1($query,$empresa,$incrementa,$FechaComp)
  {
    $NumCodigo = 0;
    $NuevoNumero = False;
    if(strlen($FechaComp)<10)
    {
      $FechaComp = date('Y-m-d');
    }
    if($FechaComp == '00/00/0000')
    {
      $FechaComp = date('Y-m-d');
    }
    $Si_MesComp = false;
    if($empresa)
    {
      $NumEmpA = $_SESSION['INGRESO']['item'];
    }else
    {
      $NumEmpA = '000';
    }

    if ($query<>'') {
      $MesComp = '';
      if(strlen($FechaComp)>=10)
      {
        $MesComp = date('m', strtotime($FechaComp)); 
      }
      if($MesComp=='')
      {
        $MesComp = '01';
      }

      if($_SESSION['INGRESO']['Num_CD'] and $query=='Diario')
      {
        $query = $MesComp.''.$query;
      }
       if($_SESSION['INGRESO']['Num_CI'] and $query=='Ingresos')
      {
        $query = $MesComp.''.$query;
      }
       if($_SESSION['INGRESO']['Num_CE'] and $query=='Egresos')
      {
        $query = $MesComp.''.$query;
      }
       if($_SESSION['INGRESO']['Num_ND'] and $query=='NotaDebito')
      {
        $query = $MesComp.''.$query;
      }
       if($_SESSION['INGRESO']['Num_NC'] and $query=='NotaCredito')
      {
        $query = $MesComp.''.$query;
      }       

    }

    $conn = new Conectar();
    $cid=$conn->conexion();
    
    $sql = "SELECT Numero, ID 
           FROM Codigos 
           WHERE Concepto = '".$query."' 
           AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
           AND Item = '".$_SESSION['INGRESO']['item']."' ";

           // print_r($sql);die();
            $stmt = sqlsrv_query( $cid, $sql);
    if( $stmt === false)  
    {  
        echo "Error en consulta PA.\n";  
        die( print_r( sqlsrv_errors(), true));  
    }
    $Result = array();
    while( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
        $Result[] = $row;
      }

      // print_r($Result);die();

      if(count($Result)>0)
      {
        $NumCodigo = $Result[0]['Numero'];
      }else
      {
        $NuevoNumero = true;
        $NumCodigo = 1;
        if($Num_Meses_CD && $Si_MesComp){$NumCodigo= $MesComp.'000001';}
        if($Num_Meses_CI && $Si_MesComp){$NumCodigo= $MesComp.'000001';}
        if($Num_Meses_CE && $Si_MesComp){$NumCodigo= $MesComp.'000001';}
        if($Num_Meses_ND && $Si_MesComp){$NumCodigo= $MesComp.'000001';}
        if($Num_Meses_NC && $Si_MesComp){$NumCodigo= $MesComp.'000001';}
      }

      if($NumCodigo > 0)
      {
        if($NuevoNumero)
        {
          $sql = "INSERT INTO Codigos (Periodo,Item,Concepto,Numero) 
                VALUES ('".$_SESSION['INGRESO']['periodo']."','".$_SESSION['INGRESO']['item']."','".$query."',".$NumCodigo.") ";
                 $stmt = sqlsrv_query( $cid, $sql);
          if( $stmt === false)  
          {  
              echo "Error en consulta PA.\n";  
              die( print_r( sqlsrv_errors(), true));  
          }
        }
        if($incrementa)
        {
           $sql = "UPDATE Codigos
                SET Numero = Numero + 1
                WHERE Concepto = '".$query."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
                AND Item = '".$_SESSION['INGRESO']['item']."' ";
                 $stmt = sqlsrv_query( $cid, $sql);
           if( $stmt === false)  
             {  
               echo "Error en consulta PA.\n";  
               die( print_r( sqlsrv_errors(), true));  
              }

        }
      }
      return $NumCodigo;
  
  }

  function numero_comprobante($parametros)
  {
    $conn = new Conectar();
    $cid=$conn->conexion();
    if(isset($parametros['fecha']))
    {
      if($parametros['fecha']=='')
      {
        $fecha_actual = date("Y-m-d"); 
      }
      else
      {
        $fecha_actual = $parametros['fecha']; 
      }
    }
    else
    {
      $fecha_actual = date("Y-m-d"); 
    }
    $ot = explode("-",$fecha_actual);
    if($parametros['tip']=='CD')
    {
      if($_SESSION['INGRESO']['Num_CD']==1)
      {
        $sql ="SELECT        Periodo, Item, Concepto, Numero, ID
        FROM            Codigos
        WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
        AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
        AND (Concepto = '".$ot[1]."Diario')";
        $stmt = sqlsrv_query( $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        $row_count=0;
        $i=0;
        $Result = array();
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          
          $Result[$i]['Numero'] = $row[3];
          
          //echo $Result[$i]['nombre'];
          $i++;
        }
        $codigo=$Result[0]['Numero']++;

        if($i==0)
        {
          return -1;
        }else
        {
          return "Comprobante de Ingreso No. ".$ot[0].'-'.$codigo;
        }
      }
    }
    if($parametros['tip']=='CI')
    {
      if($_SESSION['INGRESO']['Num_CI']==1)
      {
        $sql ="SELECT        Periodo, Item, Concepto, Numero, ID
        FROM            Codigos
        WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
        AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
        AND (Concepto = '".$ot[1]."Ingresos')";
        
        $stmt = sqlsrv_query( $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        $row_count=0;
        $i=0;
        $Result = array();
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          
          $Result[$i]['Numero'] = $row[3];
          
          //echo $Result[$i]['nombre'];
          $i++;
        }
        $codigo=$Result[0]['Numero']++;
        echo "Comprobante de Ingreso No. ".$ot[0].'-'.$codigo;
        if($i==0)
        {
          echo 'no existe registro';
          //echo json_encode($Result);
        }
      }
    }
    if($parametros['tip']=='CE')
    {
      if($_SESSION['INGRESO']['Num_CE']==1)
      {
        $sql ="SELECT        Periodo, Item, Concepto, Numero, ID
        FROM            Codigos
        WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
        AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
        AND (Concepto = '".$ot[1]."Egresos')";
        
        $stmt = sqlsrv_query( $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        $row_count=0;
        $i=0;
        $Result = array();
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          
          $Result[$i]['Numero'] = $row[3];
          
          //echo $Result[$i]['nombre'];
          $i++;
        }
        $codigo=$Result[0]['Numero']++;
        echo "Comprobante de Egreso No. ".$ot[0].'-'.$codigo;
        if($i==0)
        {
          echo 'no existe registro';
          //echo json_encode($Result);
        }
      }
    }
    if($parametros['tip']=='NC')
    {
      if($_SESSION['INGRESO']['Num_NC']==1)
      {
        $sql ="SELECT        Periodo, Item, Concepto, Numero, ID
        FROM            Codigos
        WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
        AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
        AND (Concepto = '".$ot[1]."NotaCredito')";
        
        $stmt = sqlsrv_query( $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        $row_count=0;
        $i=0;
        $Result = array();
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          
          $Result[$i]['Numero'] = $row[3];
          
          //echo $Result[$i]['nombre'];
          $i++;
        }
        $codigo=$Result[0]['Numero']++;
        echo "Comprobante de Nota de Credito No. ".$ot[0].'-'.$codigo;
        if($i==0)
        {
          echo 'no existe registro';
          //echo json_encode($Result);
        }
      }
    }
    if($parametros['tip']=='ND')
    {
      if($_SESSION['INGRESO']['Num_ND']==1)
      {
        $sql ="SELECT        Periodo, Item, Concepto, Numero, ID
        FROM            Codigos
        WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
        AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
        AND (Concepto = '".$ot[1]."NotaDebito')";
        
        $stmt = sqlsrv_query( $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        $row_count=0;
        $i=0;
        $Result = array();
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          
          $Result[$i]['Numero'] = $row[3];
          
          //echo $Result[$i]['nombre'];
          $i++;
        }
        $codigo=$Result[0]['Numero']++;
        echo "Comprobante de Nota de Debito No. ".$ot[0].'-'.$codigo;
        if($i==0)
        {
          echo 'no existe registro';
          //echo json_encode($Result);
        }
      }
    } 
  }

function ingresar_asientos_SC($parametros)
{
    $conn = new Conectar();
    $cid=$conn->conexion(); 
    $cod=$parametros['sub'];    
    if($parametros['t']=='P' OR $parametros['t']=='C')
    {
      $sql=" SELECT codigo FROM clientes WHERE CI_RUC='".$parametros['sub']."' ";
      $stmt = sqlsrv_query( $cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      //echo $sql;
      $row_count=0;
      $i=0;
      $Result = array();
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
      {
        $cod=$row[0];
      }
    }
    else
    {
      //echo ' nnnn ';
      $cod=$parametros['sub'];
    }
    //verificamos valor
    $SC_No=0;
    $sql=" SELECT MAX(SC_No) AS Expr1 FROM  Asiento_SC 
    where CodigoU ='".$_SESSION['INGRESO']['CodigoU']."' 
    AND item='".$_SESSION['INGRESO']['item']."'";
    $stmt = sqlsrv_query( $cid, $sql);
    if( $stmt === false)  
    {  
       echo "Error en consulta PA.\n";  
       die( print_r( sqlsrv_errors(), true));  
    }
    //echo $sql;
    $row_count=0;
    $i=0;
    $Result = array();
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
    {
      $SC_No=$row[0];
    }
    if($SC_No==null)
    {
      $SC_No=1;
    }
    else
    {
      $SC_No++;
    }
    $fecha_actual=$parametros['fecha_sc'];
    if($parametros['fac2']==0)
    {
      if($_SESSION['INGRESO']['modulo_']!='1')
      {
      $ot = explode("-",$fecha_actual);
      $fact2=$ot[0].$ot[1].$ot[2];
      }else
      {
        $fact2=$parametros['fac2'];
      }
      
    }
    else
    {
      $fact2=$parametros['fac2'];
      
    }
    if($parametros['mes']==0)
    {
      $sql="INSERT INTO Asiento_SC(Codigo ,Beneficiario,Factura ,Prima,DH,Valor,Valor_ME
           ,Detalle_SubCta,FECHA_V,TC,Cta,TM,T_No,SC_No
           ,Fecha_D ,Fecha_H,Bloquear,Item,CodigoU)
      VALUES
           ('".$cod."'
           ,'".$parametros['sub2']."'
           ,'".$fact2."'
           ,0
           ,'".$parametros['tic']."'
           ,".$parametros['valorn']."
           ,0
           ,'".$parametros['Trans']."'
           ,'".$fecha_actual."'
           ,'".$parametros['t']."'
           ,'".$parametros['co']."'
           ,".$parametros['moneda']."
           ,".$parametros['T_N']."
           ,".$SC_No."
           ,null
           ,null
           ,0
           ,'".$_SESSION['INGRESO']['item']."'
           ,'".$_SESSION['INGRESO']['CodigoU']."')";
       $stmt = sqlsrv_query( $cid, $sql);
       //echo $sql;
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
    }
    else
    {
      $sql="INSERT INTO Asiento_SC(Codigo ,Beneficiario,Factura ,Prima,DH,Valor,Valor_ME
      ,Detalle_SubCta,FECHA_V,TC,Cta,TM,T_No,SC_No
      ,Fecha_D ,Fecha_H,Bloquear,Item,CodigoU)
      VALUES
      ";
      $dia=0;
      for ($i=0;$i<$parametros['mes'];$i++)
      {
        $sql=$sql."('".$cod."'
         ,'".$parametros['sub2']."'
         ,'".$fact2."'
         ,0
         ,'".$parametros['tic']."'
         ,".$parametros['valorn']."
         ,0
         ,'".$parametros['Trans']."'
         ,'".$fecha_actual."'
         ,'".$parametros['t']."'
         ,'".$parametros['co']."'
         ,".$parametros['moneda']."
         ,".$parametros['T_N']."
         ,".$SC_No."
         ,null
         ,null
         ,0
         ,'".$_SESSION['INGRESO']['item']."'
         ,'".$_SESSION['INGRESO']['CodigoU']."'),";
         $SC_No++;
         $ot = explode("-",$fecha_actual);
         if($ot[1]=='01')
         {
            if($ot[2]>=28)
            {
             $dia=$ot[2];
              $year=esBisiesto_ajax($ot[0]);
            if($year==1)
            {
              $fecha_actual = date("Y-m-d",strtotime($ot[0].'-02-29')); 
              if($parametros['fac2']==0)
              {
                $fact2 = date("Ymd",strtotime($ot[0].'0229')); 
              }
              //$fact2 = $ot[0].'0229'; 
            }
            else
            {
              $fecha_actual = date("Y-m-d",strtotime($ot[0].'-02-28')); 
               if($parametros['fac2']==0)
              {
                $fact2 = date("Ymd",strtotime($ot[0].'0228')); 
              }
            }
            }
          else
          {
            $fecha_actual = date("Y-m-d",strtotime($fecha_actual."+ 1 month")); 
             if($parametros['fac2']==0)
            {
              $fact2 = date("Ymd",strtotime($fact2."+ 1 month")); 
            }
          }
           
         }
         else
         {
          //$ot = explode("-",$fecha_actual);
          //if($ot[1]=='03')
          //{
          /*if( $dia>=28)
          {
            $ot = explode("-",$fecha_actual);
            $fecha_actual = date("Y-m-d",strtotime($ot[0].'-03-31')); 
            if($_POST['fac2']==0)
            {
              $fact2 = date("Ymd",strtotime($ot[0].'0331')); 
            }
            $dia=0;
          }*/
          //else
          //{
            
            if( $dia>=28)
            {
              $ot = explode("-",$fecha_actual);
              if($ot[1]=='02')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-03-31')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'0331')); 
                }
              }
              if($ot[1]=='03')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-04-30')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'0430')); 
                }
              }
              if($ot[1]=='04')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-05-31')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'0531')); 
                }
              }
              if($ot[1]=='05')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-06-30')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'0630')); 
                }
              }
              if($ot[1]=='06')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-07-31')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'0731')); 
                }
              }
              if($ot[1]=='07')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-08-31')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'0831')); 
                }
              }
              if($ot[1]=='08')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-09-30')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'0930')); 
                }
              }
              if($ot[1]=='09')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-10-31')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'1031')); 
                }
              }
              if($ot[1]=='10')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-11-30')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'1130')); 
                }
              }
              if($ot[1]=='11')
              {
                $fecha_actual = date("Y-m-d",strtotime($ot[0].'-12-31')); 
                if($parametros['fac2']==0)
                {
                  $fact2 = date("Ymd",strtotime($ot[0].'1231')); 
                }
              }
            }
            else
            {
              $fecha_actual = date("Y-m-d",strtotime($fecha_actual."+ 1 month")); 
              if($parametros['fac2']==0)
              {
                $fact2 = date("Ymd",strtotime($fact2."+ 1 month")); 
              }
            }
          //}
          //}
          }
        // echo $fecha_actual.' <br>';
      }
      //reemplazo una parte de la cadena por otra
      $longitud_cad = strlen($sql); 
      $cam2 = substr_replace($sql,"",$longitud_cad-1,1); 
      $stmt = sqlsrv_query( $cid, $cam2);
        //echo $sql;
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      //echo $cam2;
    }
      $sql="SELECT Codigo, Beneficiario, Factura, Prima, DH, Valor, Valor_ME, Detalle_SubCta,T_No, SC_No,Item, CodigoU
      FROM Asiento_SC
      WHERE 
        Item = '".$_SESSION['INGRESO']['item']."' 
        AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
      $stmt = sqlsrv_query( $cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      else
      {
        $camne=array();
      }

}


function ingresar_asientos($parametros)
{

    $conn = new Conectar();
    $cid=$conn->conexion();
    $va = $parametros['va'];
    $dconcepto1 = $parametros['dconcepto1'];
    $codigo = $parametros['codigo'];
    $cuenta = $parametros['cuenta'];
    $tc ='';
    if(isset($parametros['tc']))
    {
      $tc = $parametros['tc'];
    }
    if(isset($parametros['t_no']))
    {
      $t_no = $parametros['t_no'];
    }
    else
    {
      $t_no = 1;
    }
    if(isset($parametros['efectivo_as']))
    {
      $efectivo_as = $parametros['efectivo_as'];
    }
    else
    {
      $efectivo_as = '';
    }
    if(isset($parametros['chq_as']))
    {
      $chq_as = $parametros['chq_as'];
    }
    else
    {
      $chq_as = '';
    }
    
    $moneda = $parametros['moneda'];
    $tipo_cue = $parametros['tipo_cue'];
    
    if($efectivo_as=='' or $efectivo_as==null)
    {
      $efectivo_as=$fecha;
    }
    if($chq_as=='' or $chq_as==null)
    {
      $chq_as='.';
    }
    $parcial = 0;
    if($moneda==2)
    {
      $cotizacion = $parametros['cotizacion'];
      $con = $parametros['con'];
      if($tipo_cue==1)
      {
        if($con=='/')
        {
          $debe=$va/$cotizacion;
        }
        else
        {
          $debe=$va*$cotizacion;
        }
        $parcial = $va;
        $haber=0;
      }
      if($tipo_cue==2)
      {
        if($con=='/')
        {
          $haber=$va/$cotizacion;
        }
        else
        {
          $haber=$va*$cotizacion;
        }
        $parcial = $va;
        $debe=0;
      }
    }
    else
    {
      if($tipo_cue==1)
      {
        $debe=$va;
        $haber=0;
      }
      if($tipo_cue==2)
      {
        $debe=0;
        $haber=$va;
      }
    }
    //verificar si ya existe en ese modulo ese registro
    $sql="SELECT CODIGO, CUENTA
    FROM Asiento
    WHERE (CODIGO = '".$codigo."') AND (Item = '".$_SESSION['INGRESO']['item']."') 
    AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND (DEBE = '".$va."') 
    AND T_No=".$_SESSION['INGRESO']['modulo_']." 
    ORDER BY A_No ASC ";
    //print_r($sql);die();
    $stmt = sqlsrv_query( $cid, $sql);
    if( $stmt === false)  
    {  
       echo "Error en consulta PA.\n";  
       die( print_r( sqlsrv_errors(), true));  
    }
    //para contar registro
    $i=0;
    $i=contar_registros($stmt);
    if($t_no == '60')
    {
      $i=0;
    }
    //echo $i.' -- '.$sql;
    //seleccionamos el valor siguiente
    $sql="SELECT TOP 1 A_No FROM Asiento
    WHERE (Item = '".$_SESSION['INGRESO']['item']."')
    ORDER BY A_No DESC";
    $A_No=0;
    $stmt = sqlsrv_query( $cid, $sql);
    if( $stmt === false)  
    {  
       echo "Error en consulta PA.\n";  
       die( print_r( sqlsrv_errors(), true));  
    }
    else
    {
      $ii=0;
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
      {
        $A_No = $row[0];
        $ii++;
      }
      
      if($ii==0)
      {
        $A_No++;
      }
      else
      {
        $A_No++;
      }
    }
    //si no existe guardamos
    if($i==0)
    {
      
        $sql="INSERT INTO Asiento
        (CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
        ,ME,T_No,Item,CodigoU,A_No,TC)
        VALUES
        ('".$codigo."','".$cuenta."',".$parcial.",".$debe.",".$haber.",'".$chq_as."','".$dconcepto1."',
        '".$efectivo_as."','.','.',0,".$t_no.",'".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['CodigoU']."',".$A_No.",'".$tc."')";
       $stmt = sqlsrv_query( $cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      else
      {
        $sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
        FROM Asiento
        WHERE 
          T_No=".$_SESSION['INGRESO']['modulo_']." AND
          Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
          ORDER BY A_No ASC ";
        $stmt = sqlsrv_query( $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        else
        {
          $camne=array();
          return 1;
        }
      }
    }
    else
    {
      //echo " ENTROOO ";
      echo "<script>
            Swal.fire({
              type: 'error',
              title: 'No se pudo guardar registro',
              text: 'Ya existe un registro con estos datos',
              footer: ''
          })
      </script>";
      $sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
        FROM Asiento
        WHERE 
          T_No=".$_SESSION['INGRESO']['modulo_']." AND
          Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
          ORDER BY A_No ASC ";
        $stmt = sqlsrv_query( $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        else
        {
          return 1;
        }
    }    
}

function generar_comprobantes($parametros)
  {
    $conn = new Conectar();
    $cid=$conn->conexion();
    if(isset($parametros['cotizacion']))
    {
      if($parametros['cotizacion']=='' or $parametros['cotizacion']==null)
      {
        $parametros['cotizacion']=0;
      }
    }
    else
    {
      $parametros['cotizacion']=0;
    }
    $codigo_b='';
    //echo $_POST['ru'].'<br>';
    if($parametros['ru']=='000000000')
    {
      $codigo_b='.';
    }
    else
    {
      //buscamos codigo
      $sql="  SELECT Codigo
          FROM Clientes
          WHERE((CI_RUC = '".$parametros['ru']."')) ";
          // print_r($sql);die();
      $stmt = sqlsrv_query( $cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      else
      {
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          $codigo_b=$row[0];
        }
      }
      //caso en donde se necesite guardar el codigo de usuario como codigo beneficiario de comprobante
      if($codigo_b =='' or $codigo_b==null)
      {
        $codigo_b =$parametros['ru'];
      }
      //$codigo_b=$_POST['ru'];
    }
    //buscamos total
    if($parametros['tip']=='CE' or $parametros['tip']=='CI')
    {
      $sql="SELECT        SUM( DEBE) AS db, SUM(HABER) AS ha
      FROM            Asiento
      where T_No=".$_SESSION['INGRESO']['modulo_']." AND
          Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '".$_SESSION['INGRESO']['Id']."'  AND CUENTA 
      in (select Cuenta FROM  Catalogo_Cuentas 
      where Catalogo_Cuentas.Cuenta=Asiento.CUENTA AND (Catalogo_Cuentas.TC='CJ' OR Catalogo_Cuentas.TC='BA'))";
      
      $stmt = sqlsrv_query( $cid, $sql);
      $totald=0;
      $totalh=0;
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      else
      {
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          $totald=$row[0];
          $totalh=$row[1];
        }
      }
      if($parametros['tip']=='CE')
      {
        $parametros['totalh']=$totalh;
      }
      if($parametros['tip']=='CI')
      {
        $parametros['totalh']=$totald;
      }
    }
    if($parametros['concepto']=='')
    {
      $parametros['concepto']='.';
    }
    $num_com = explode("-", $parametros['num_com']);
    //verificamos que no se coloque fecha erronea
    $ot = explode("-",$parametros['fecha1']);
    $num_com1 = explode(".", $num_com[0]);
    $parametros['fecha1']=trim($num_com1[1]).'-'.$ot[1].'-'.$ot[2];
    
    //echo $_POST['fecha1'];
    //die();
    
    $sql="INSERT INTO Comprobantes
           (Periodo ,Item,T ,TP,Numero ,Fecha ,Codigo_B,Presupuesto,Concepto,Cotizacion,Efectivo,Monto_Total
           ,CodigoU ,Autorizado,Si_Existe ,Hora,CEj,X)
       VALUES
           ('".$_SESSION['INGRESO']['periodo']."'
           ,'".$_SESSION['INGRESO']['item']."'
           ,'N'
           ,'".$parametros['tip']."'
           ,".$num_com[1]."
           ,'".$parametros['fecha1']."'
           ,'".$codigo_b."'
           ,0
           ,'".$parametros['concepto']."'
           ,'".$parametros['cotizacion']."'
           ,0
           ,'".$parametros['totalh']."'
           ,'".$_SESSION['INGRESO']['CodigoU']."'
           ,'.'
           ,0
           ,'".date('h:i:s')."'
           ,'.'
           ,'.')";
        // echo $sql.'<br>';
           // print_r($sql);die();
        $stmt = sqlsrv_query( $cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }

       //consultamos transacciones
       $sql="SELECT CODIGO,CUENTA,PARCIAL_ME  ,DEBE ,HABER ,CHEQ_DEP ,DETALLE ,EFECTIVIZAR,CODIGO_C,CODIGO_CC
        ,ME,T_No,Item,CodigoU ,A_No,TC
        FROM Asiento
        WHERE 
          T_No='".$_SESSION['INGRESO']['modulo_']."' AND
          Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
      
      $sql=$sql." ORDER BY A_No ";
      $stmt = sqlsrv_query( $cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      else
      {
        $i=0;
        $ii=0;
        $Result = array();
        $fecha_actual = date("Y-m-d"); 
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          $Result[$i]['CODIGO']=$row[0];
          $Result[$i]['CHEQ_DEP']=$row[5];
          $Result[$i]['DEBE']=$row[3];
          $Result[$i]['HABER']=$row[4];
          $Result[$i]['PARCIAL_ME']=$row[2];
          $Result[$i]['EFECTIVIZAR']=$row[7]->format('Y-m-d');
          $Result[$i]['CODIGO_C']=$row[8];
          
          $sql=" INSERT INTO Transacciones
            (Periodo ,T,C ,Cta,Fecha,TP ,Numero,Cheq_Dep,Debe ,Haber,Saldo ,Parcial_ME ,Saldo_ME ,Fecha_Efec ,Item ,X ,Detalle
            ,Codigo_C,Procesado,Pagar,C_Costo)
           VALUES
            ('".$_SESSION['INGRESO']['periodo']."'
            ,'N'
            ,0
            ,'".$Result[$i]['CODIGO']."'
            ,'".$parametros['fecha1']."'
            ,'".$parametros['tip']."'
            ,".$num_com[1]."
            ,'".$Result[$i]['CHEQ_DEP']."'
            ,".$Result[$i]['DEBE']."
            ,".$Result[$i]['HABER']."
            ,0
            ,".$Result[$i]['PARCIAL_ME']."
            ,0
            ,'".$Result[$i]['EFECTIVIZAR']."'
            ,'".$_SESSION['INGRESO']['item']."'
            ,'.'
            ,'.'
            ,'".$Result[$i]['CODIGO_C']."'
            ,0
            ,0
            ,'.');";
           // echo $sql.'<br>';

           // print_r($sql);
          $stmt1 = sqlsrv_query( $cid, $sql);
          if( $stmt1 === false)  
          {  
             echo "Error en consulta PA.\n";  
             die( print_r( sqlsrv_errors(), true));  
          }
          $i++;
        }
        $sql="SELECT  Codigo,Beneficiario,Factura,Prima,DH,Valor ,Valor_ME,Detalle_SubCta,FECHA_V ,TC,Cta,TM
        ,T_No,SC_No,Fecha_D,Fecha_H,Bloquear,Item,CodigoU
        FROM Asiento_SC
        WHERE 
          Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";

        //echo $sql;
        $stmt = sqlsrv_query(   $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        else
        {
          $i=0;
          $Result = array();
          $fecha_actual = date("Y-m-d"); 
          while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
          {
            $Result[$i]['TC']=$row[9];
            $Result[$i]['Cta']=$row[10];
            $Result[$i]['FECHA_V']=$row[8]->format('Y-m-d');
            $Result[$i]['Codigo']=$row[0];
            $Result[$i]['Factura']=$row[2];
            $Result[$i]['Prima']=$row[3];
            $Result[$i]['DH']=$row[4];
            if($Result[$i]['DH']==1)
            {
              $Result[$i]['DEBITO']=$row[5];
              $Result[$i]['HABER']=0;
            }
            if($Result[$i]['DH']==2)
            {
              $Result[$i]['DEBITO']=0;
              $Result[$i]['HABER']=$row[5];
            }
            $sql="INSERT INTO Trans_SubCtas
                 (Periodo ,T,TC,Cta,Fecha,Fecha_V,Codigo ,TP,Numero ,Factura ,Prima ,Debitos ,Creditos ,Saldo_MN,Parcial_ME
                 ,Saldo_ME,Item,Saldo ,CodigoU,X,Comp_No,Autorizacion,Serie,Detalle_SubCta,Procesado)
             VALUES
                 ('".$_SESSION['INGRESO']['periodo']."'
                 ,'N'
                 ,'".$Result[$i]['TC']."'
                 ,'".$Result[$i]['Cta']."'
                 ,'".$parametros['fecha1']."'
                 ,'".$Result[$i]['FECHA_V']."'
                 ,'".$Result[$i]['Codigo']."'
                 ,'".$parametros['tip']."'
                 ,".$num_com[1]."
                 ,".$Result[$i]['Factura']."
                 ,".$Result[$i]['Prima']."
                 ,".$Result[$i]['DEBITO']."
                 ,".$Result[$i]['HABER']."
                 ,0
                 ,0
                 ,0
                 ,'".$_SESSION['INGRESO']['item']."'
                 ,0
                 ,'".$_SESSION['INGRESO']['CodigoU']."'
                 ,'.'
                 ,0
                 ,'.'
                 ,'.'
                 ,'.'
                 ,0)";
            //echo $sql.'<br>';

           // print_r($sql);die();
            $stmt1 = sqlsrv_query( $cid, $sql);
            if( $stmt1 === false)  
            {  
               echo "Error en consulta PA.\n";  
               die( print_r( sqlsrv_errors(), true));  
            }
          }
        }
        //incrementamos el secuencial
        if($_SESSION['INGRESO']['Num_CD']==1)
        {
          //para variable en html
          $num1=$num_com[1];
          $num_com[1]=$num_com[1]+1;
          //echo $num_com[1].'<br>'.$_POST['tip'].'<br>';
          if(isset($parametros['fecha1']))
          {
            //echo $_POST['fecha'];
            $fecha_actual = $parametros['fecha1']; 
          }
          else
          {
            $fecha_actual = date("Y-m-d"); 
          }
          $ot = explode("-",$fecha_actual);
          if($parametros['tip']=='CD')
          {
            $sql ="UPDATE Codigos set Numero=".$num_com[1]."
            WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
            AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
            AND (Concepto = '".$ot[1]."Diario')";
          }
          if($parametros['tip']=='CI')
          {
            $sql ="UPDATE Codigos set Numero=".$num_com[1]."
            WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
            AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
            AND (Concepto = '".$ot[1]."Ingresos')";
          }
          if($parametros['tip']=='CE')
          {
            $sql ="UPDATE Codigos set Numero=".$num_com[1]."
            WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
            AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
            AND (Concepto = '".$ot[1]."Egresos')";
          }
          if($parametros['tip']=='ND')
          {
            $sql ="UPDATE Codigos set Numero=".$num_com[1]."
            WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
            AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
            AND (Concepto = '".$ot[1]."NotaDebito')";
          }
          if($parametros['tip']=='NC')
          {
            $sql ="UPDATE Codigos set Numero=".$num_com[1]."
            WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
            AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
            AND (Concepto = '".$ot[1]."NotaCredito')";
          }
          $stmt = sqlsrv_query( $cid, $sql);
          if( $stmt === false)  
          {  
             echo "Error en consulta PA.\n";  
             die( print_r( sqlsrv_errors(), true));  
          }
          //borramos temporales asientos
          $sql="DELETE FROM Asiento
          WHERE 
          T_No=".$_SESSION['INGRESO']['modulo_']." AND
          Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
          // echo  $sql;
          $stmt = sqlsrv_query( $cid, $sql);
          if( $stmt === false)  
          {  
             echo "Error en consulta PA.\n";  
             die( print_r( sqlsrv_errors(), true));  
          }
          //borramos temporales asientos bancos
          
          $sql="DELETE FROM Asiento_B
          WHERE 
          Item = '".$_SESSION['INGRESO']['item']."' 
          AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
          $stmt = sqlsrv_query( $cid, $sql);
          if( $stmt === false)  
          {  
             echo "Error en consulta PA.\n";  
             die( print_r( sqlsrv_errors(), true));  
          }
          //echo $sql;
          $stmt = sqlsrv_query( $cid, $sql);
          if( $stmt === false)  
          {  
             echo "Error en consulta PA.\n";  
             die( print_r( sqlsrv_errors(), true));  
          }
          //borramos asiento subcuenta
          $sql="DELETE FROM Asiento_SC
          WHERE 
            Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
          $stmt = sqlsrv_query(   $cid, $sql);
          if( $stmt === false)  
          {  
             echo "Error en consulta PA.\n";  
             die( print_r( sqlsrv_errors(), true));  
          }
          //generamos comprobante
          //reporte_com($num1);
          return $num1;
        }
      }
  }

  function mayorizar_inventario_sp()
  {
    // set_time_limit(1024);
    // ini_set("memory_limit", "-1");
    // $desde = '2019/10/28';
    // $hasta = '2019/11/29';
    $_SESSION['INGRESO']['modulo_']='01';
    $conn = new Conectar();
      $cid=$conn->conexion();
      $parametros = array(
      array(&$_SESSION['INGRESO']['item'], SQLSRV_PARAM_IN),
      array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN),
      array(&$_SESSION['INGRESO']['CodigoU'], SQLSRV_PARAM_IN),
      array(&$_SESSION['INGRESO']['modulo_'], SQLSRV_PARAM_IN),
      array(&$_SESSION['INGRESO']['Dec_PVP'], SQLSRV_PARAM_IN),
      array(&$_SESSION['INGRESO']['Dec_Costo'], SQLSRV_PARAM_IN)
      );     
     $sql="EXEC sp_Mayorizar_Inventario @Item=?, @Periodo=?, @Usuario=?, @NumModulo=?, @DecPVP=?, @DecCosto=?";
     // print_r($_SESSION['INGRESO']);die();

      $stmt = sqlsrv_prepare($cid, $sql,$parametros);
      if(!$stmt)
      {
        die( print_r( sqlsrv_errors(), true));
      }
      if (!sqlsrv_execute($stmt)) {
   
         echo "Error en consulta PA.\n";         
         $respuesta = -1;
         die( print_r( sqlsrv_errors(), true));
         return $respuesta;  
       die;
      }
     $respuesta =  1;
       return $respuesta;   
  }

  function sp_Reindexar_Periodo()
  {
    // set_time_limit(1024);
    // ini_set("memory_limit", "-1");
    // $desde = '2019/10/28';
    // $hasta = '2019/11/29';
    $_SESSION['INGRESO']['modulo_']='01';
    $conn = new Conectar();
      $cid=$conn->conexion();
      $parametros = array(
      array(&$_SESSION['INGRESO']['item'], SQLSRV_PARAM_IN),
      array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN)
      );     
     $sql="EXEC sp_Reindexar_Periodo @Item=?, @Periodo=?";
     // print_r($_SESSION['INGRESO']);die();

      $stmt = sqlsrv_prepare($cid, $sql,$parametros);
      if(!$stmt)
      {
        die( print_r( sqlsrv_errors(), true));
      }
      if (!sqlsrv_execute($stmt)) {
   
         echo "Error en consulta PA.\n";         
         $respuesta = -1;
         die( print_r( sqlsrv_errors(), true));
         return $respuesta;  
       die;
      }
     $respuesta =  1;
       return $respuesta;   
  }

  function Leer_Campo_Empresa($query)
  {
     $conn = new Conectar();
     $cid=$conn->conexion();
    $sql = "SELECT ".$query." 
            FROM Empresas 
            WHERE Item = '".$_SESSION['INGRESO']['item']."'";
    $stmt = sqlsrv_query( $cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
       $result = 0;  
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
        {
           $result =$row;
        }
        return $result[$query];

  }

function buscar_cta_iva_inventario()
  {
    $conn = new Conectar();
    $cid=$conn->conexion();
    $sql = "SELECT * FROM Ctas_Proceso WHERE Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item='".$_SESSION['INGRESO']['item']."' AND Detalle = 'Cta_Iva_Inventario'";
    // print_r($sql); die();
    $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
            $datos[]=$row;
     }
     if(count($datos)>0)
     {
       return $datos[0]['Codigo'];
     }else
     {
       return -1;
     }

  }

function LeerCta($CodigoCta )
{
  $conn = new Conectar();
  $cid=$conn->conexion();
  $Cuenta = G_NINGUNO;
  $Codigo = G_NINGUNO;
  $TipoCta = "G";
  $SubCta = "N";
  $TipoPago = "01";
  $Moneda_US = False;
  if(strlen(substr($CodigoCta, 1, 1)) >= 1){

     $sql = "SELECT Codigo, Cuenta, TC, ME, DG, Tipo_Pago
             FROM Catalogo_Cuentas
             WHERE Codigo = '".$CodigoCta."'
             AND Item = '".$_SESSION['INGRESO']['item']."'
             AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
    $stmt = sqlsrv_query($cid, $sql);
    $datos =  array();
    $datoscta = array();
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
            $datos[]=$row;
     }

     // print_r($datos);die();
     // print_r('expression');
     if (count($datos)>0) {
       foreach ($datos as $key => $value) {
         
         if (intval($value['Tipo_Pago']) <= 0){ $tipo= "01";}
         $datoscta[] = array( 'Codigo' =>$value["Codigo"],'Cuenta'=>$value["Cuenta"],'SubCta'=>$value["TC"],'Moneda_US'=>$value["ME"],'TipoCta'=>$value["DG"],'TipoPago'=> $tipo);
       }
     }
     return $datoscta;
     // DataReg.open sSQL, AdoStrCnn, , , adCmdText
     // With DataReg
     //  If .RecordCount > 0 Then
     //      $Codigo = .Fields("Codigo")
     //      $Cuenta = .Fields("Cuenta")
     //      $SubCta = .Fields("TC")
     //      $Moneda_US = .Fields("ME")
     //      $TipoCta = .Fields("DG")
     //      $TipoPago = .Fields("Tipo_Pago")
     //      If Val(TipoPago) <= 0 Then TipoPago = "01"
     //  End If
     // End With
     // DataReg.Close
  }
}

function costo_venta($codigo_inv)
  {
  $conn = new Conectar();
  $cid=$conn->conexion();
    $sql = "SELECT  SUM(Entrada-Salida) as 'Existencia' 
    FROM Trans_Kardex
    WHERE Fecha <= '".date('Y-m-d')."'
    AND Codigo_Inv = '".$codigo_inv."'
    AND Item = '".$_SESSION['INGRESO']['item']."'
    AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
    AND T <> 'A'";
    // print_r($sql);die();
    $stmt = sqlsrv_query($cid, $sql);
        $datos =  array();
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
    $datos[]=$row;  
     }
       return $datos;

  }
  

function crear_variables_session($empresa)
{

  // print_r($empresa);die();
        $_SESSION['INGRESO']['IP_VPN_RUTA']='mysql.diskcoversystem.com';
        $_SESSION['INGRESO']['Base_Datos']='diskcover_empresas';
        $_SESSION['INGRESO']['Usuario_DB']='diskcover';
        $_SESSION['INGRESO']['Contraseña_DB']='disk2017Cover';
        $_SESSION['INGRESO']['Tipo_Base']='MySQL';
        $_SESSION['INGRESO']['Puerto']='13306';
        $_SESSION['INGRESO']['Fecha']='';
        $_SESSION['INGRESO']['Logo_Tipo']=$empresa[0]['Logo_Tipo'];
        $_SESSION['INGRESO']['periodo']='.';
        $_SESSION['INGRESO']['Razon_Social']=$empresa[0]['Razon_Social'];
        $_SESSION['INGRESO']['Fecha_ce']='';
        //echo $_SESSION['INGRESO']['IP_VPN_RUTA'];
        //obtenemos el resto de inf. de la empresa tales como correo direccion
        // print_r($empresa_d);die();
        $_SESSION['INGRESO']['Direccion']='';
        $_SESSION['INGRESO']['Telefono1']='';
        $_SESSION['INGRESO']['FAX']='';
        $_SESSION['INGRESO']['Nombre_Comercial']='';
        $_SESSION['INGRESO']['Razon_Social']=$empresa[0]['Razon_Social'];
        $_SESSION['INGRESO']['Sucursal']='';
        $_SESSION['INGRESO']['Opc']='';
        $_SESSION['INGRESO']['noempr']=$empresa[0]['Empresa'];
        $_SESSION['INGRESO']['S_M']='';
        $_SESSION['INGRESO']['Num_CD']='';
        $_SESSION['INGRESO']['Num_CE']='';
        $_SESSION['INGRESO']['Num_CI']='';
        $_SESSION['INGRESO']['Num_ND']='';
        $_SESSION['INGRESO']['Num_NC']='';
        $_SESSION['INGRESO']['Email_Conexion_CE']='';
        $_SESSION['INGRESO']['Formato_Cuentas']='';
        $_SESSION['INGRESO']['Formato_Inventario']='';
        $_SESSION['INGRESO']['porc']='';
        $_SESSION['INGRESO']['Ambiente']='';
        $_SESSION['INGRESO']['Obligado_Conta']='';
        $_SESSION['INGRESO']['LeyendaFA']='';
        $_SESSION['INGRESO']['Email']='';
        $_SESSION['INGRESO']['RUC']=$empresa[0]['RUC_CI_NIC'];
        $_SESSION['INGRESO']['Gerente']=$empresa[0]['Gerente'];;
        $_SESSION['INGRESO']['Det_Comp']='';
        $_SESSION['INGRESO']['Signo_Dec']='';
        $_SESSION['INGRESO']['Signo_Mil']='';
        $_SESSION['INGRESO']['Sucursal']='';
        $_SESSION['INGRESO']['RUC_Contador'] = '';
        $_SESSION['INGRESO']['CI_Representante'] = '';
        $_SESSION['INGRESO']['Ruta_Certificado'] = '';
        $_SESSION['INGRESO']['Clave_Certificado'] = '';
        $_SESSION['INGRESO']['Ambiente'] = '';
        $_SESSION['INGRESO']['Dec_PVP'] = '';
        $_SESSION['INGRESO']['Dec_Costo'] = '';
        $_SESSION['INGRESO']['Cotizacion'] = '';
        // print_r($empresa_d);die();
        $_SESSION['INGRESO']['Ciudad'] = $empresa[0]['Ciudad'];;       
        $_SESSION['INGRESO']['accesoe']='0';
        $_SESSION['INGRESO']['CodigoU']='';
         $_SESSION['INGRESO']['Nombre_Completo']='';
}

  function Leer_Seteos_Ctas($Det_Cta = ""){
    //conexion
    $conn = new Conectar();
    $cid=$conn->conexion();
    $RatonReloj;
    $Cta_Ret_Aux = "0";
    $SSQLSeteos = "SELECT * 
               FROM Ctas_Proceso 
               WHERE Item = '".$_SESSION['INGRESO']['item']."'
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
               AND Detalle = '".$Det_Cta."' ";
    $stmt = sqlsrv_query($cid, $SSQLSeteos);
    if( $stmt === false)  
    {  
      die( print_r( sqlsrv_errors(), true));
    } else
    {
      $datos = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC);
      return $datos['Codigo'];
    }
  }

  // function sp_mayorizar_cuentas()
  // {
    // set_time_limit(1024);
    // ini_set("memory_limit", "-1");
    // $desde = '2019/10/28';
    // $hasta = '2019/11/29';
    // $Escoop = false;
    // $ConSucursal = false;
    //  $conn = new Conectar();
    //   $cid=$conn->conexion();
    //   $parametros = array(
    //   array(&$_SESSION['INGRESO']['item'], SQLSRV_PARAM_IN),
    //   array(&$_SESSION['INGRESO']['periodo'], SQLSRV_PARAM_IN),
    //   array(&$Escoop, SQLSRV_PARAM_IN),
    //   array(&$ConSucursal, SQLSRV_PARAM_IN),
    //   );     
    //  $sql="EXEC sp_Reindexar_Periodo @Item=?, @Periodo=?";
    //  // print_r($_SESSION['INGRESO']);die();

    //   $stmt = sqlsrv_prepare($cid, $sql,$parametros);
    //   if(!$stmt)
    //   {
    //     die( print_r( sqlsrv_errors(), true));
    //   }
    //   if (!sqlsrv_execute($stmt)) {
   
    //      echo "Error en consulta PA.\n";         
    //      $respuesta = -1;
    //      die( print_r( sqlsrv_errors(), true));
    //      return $respuesta;  
    //    die;
    //   }
    //  $respuesta =  1;
    //    return $respuesta;   
  // }

  function TextoValido1($TextB,$Numero = false, $Mayusculas = false, $NumeroDecimales = 2){
    $TextosB = "";
    $TextosB = $TextB;
    if (is_null($TextosB)) $TextosB = "";
    
    if (empty($TextosB)) $TextosB = "";
    $TextosB = trim($TextosB);
    if ($Mayusculas) $TextosB = strtoupper($TextosB);
    if ($Numero) {
      if ($TextosB == "") $TextosB = "0";
      if (is_numeric($TextosB)) {
        if($NumeroDecimales) {
          if($NumeroDecimales == 0){
            $TextosB = number_format($TextosB, $NumeroDecimales, '.', '');
          }elseif ($NumeroDecimales > 2) {
            $TextosB = Format($TextosB, $NumeroDecimales, '.', '');
          }else{
            $TextosB = number_format($TextosB, $NumeroDecimales, '.', '');
          }
        }
        $TextB = trim($TextosB);
      } else {
        $TextosB = "0";
        $TextB = $TextosB;
      }
    }else{
      if ($TextosB == "") $TextosB = G_NINGUNO;
      $TextB = $TextosB;
    }
    return $TextB;
  }

  function Leer_Datos_Clientes($Codigo_CIRUC_Cliente = ""){
    //Dim AdoCliDB As ADODB.Recordset
    $TBenef = $Codigo_CIRUC_Cliente;
    $Por_Codigo = false;
    $Por_CIRUC = false;
    $Por_Cliente = false;
    $CadAux = "";

    if ($TBenef != "") {
      $FA = false;
      $Asignar_Dr = false;
      $Codigo = G_NINGUNO;
      $Cliente = G_NINGUNO;
      $Tipo_Cta = G_NINGUNO;
      $Cta_Numero = G_NINGUNO;
      $Descuento = false;
      $T = "";
      $TP = "";
      $CI_RUC = "";
      $TD = "";
      $Fecha = "";
      $Fecha_A = "";
      $Fecha_N = "";
      $Sexo = "";
      $Email1 = "";
      $Email2 = "";
      $Direccion = "";
      $DirNumero = "";
      $Telefono1 = "";
      $TelefonoT = "";
      $Celular = "";
      $Ciudad = "";
      $Prov = "";
      $Pais = "";
      $Profesion = ""; 
      $Representante = G_NINGUNO;
      $RUC_CI_Rep = "";
      $TD_Rep = "";
      $Direccion_Rep = "SD";
      $Grupo_No = "";
      $Contacto = "";
      $Calificacion = "";
      $Plan_Afiliado = "";
      $Cte_Ahr_Otro = "";
      $Cta_Transf = "";
      $Cod_Banco = 0;
      $Salario = 0;
    }
    
    //if (strlen($Codigo_CIRUC_Cliente) <= 0) $Codigo_CIRUC_Cliente = G_NINGUNO;
    /*
   'Por Codigo
    sSQL = "SELECT Codigo " _
         & "FROM Clientes " _
         & "WHERE Codigo = '" & Codigo_CIRUC_Cliente & "' "
    Select_AdoDB AdoCliDB, sSQL
    If AdoCliDB.RecordCount > 0 Then
       TBenef.Codigo = AdoCliDB.Fields("Codigo")
       Por_Codigo = True
    End If
    AdoCliDB.Close
    
   'Por CI o RUC
    If Not Por_Codigo Then
       sSQL = "SELECT Codigo " _
            & "FROM Clientes " _
            & "WHERE CI_RUC = '" & Codigo_CIRUC_Cliente & "' "
       Select_AdoDB AdoCliDB, sSQL
       If AdoCliDB.RecordCount > 0 Then
          TBenef.Codigo = AdoCliDB.Fields("Codigo")
          Por_CIRUC = True
       End If
       AdoCliDB.Close
    End If
        
   'Por Cliente
    If Not Por_CIRUC Then
       sSQL = "SELECT Codigo " _
            & "FROM Clientes " _
            & "WHERE Cliente = '" & Codigo_CIRUC_Cliente & "' "
       Select_AdoDB AdoCliDB, sSQL
       If AdoCliDB.RecordCount > 0 Then
          TBenef.Codigo = AdoCliDB.Fields("Codigo")
          Por_Cliente = True
       End If
       AdoCliDB.Close
    End If
    
   'Verificamos la informacion del Clienete
    If Por_Codigo Or Por_CIRUC Or Por_Cliente Then
       With TBenef
            sSQL = "SELECT * " _
                 & "FROM Clientes " _
                 & "WHERE Codigo = '" & .Codigo & "' "
            Select_AdoDB AdoCliDB, sSQL
            If AdoCliDB.RecordCount > 0 Then
              .FA = AdoCliDB.Fields("FA")
              .Asignar_Dr = AdoCliDB.Fields("Asignar_Dr")
              .Cliente = AdoCliDB.Fields("Cliente")
              .Descuento = AdoCliDB.Fields("Descuento")
              .T = AdoCliDB.Fields("T")
              .CI_RUC = AdoCliDB.Fields("CI_RUC")
              .TD = AdoCliDB.Fields("TD")
              .Fecha = AdoCliDB.Fields("Fecha")
              .Fecha_N = AdoCliDB.Fields("Fecha_N")
              .Sexo = AdoCliDB.Fields("Sexo")
              .Email1 = AdoCliDB.Fields("Email")
              .Email2 = AdoCliDB.Fields("Email2")
              .EmailR = .Email1
              .Direccion = AdoCliDB.Fields("Direccion")
              .DirNumero = AdoCliDB.Fields("DirNumero")
              .Telefono1 = AdoCliDB.Fields("Telefono")
              .TelefonoT = AdoCliDB.Fields("Telefono")
              .Ciudad = AdoCliDB.Fields("Ciudad")
              .Prov = AdoCliDB.Fields("Prov")
              .Pais = AdoCliDB.Fields("Pais")
              .Profesion = AdoCliDB.Fields("Profesion")
              .Grupo_No = AdoCliDB.Fields("Grupo")
              .Contacto = AdoCliDB.Fields("Contacto")
              .Calificacion = AdoCliDB.Fields("Calificacion")
              .Plan_Afiliado = AdoCliDB.Fields("Plan_Afiliado")
              .Actividad = AdoCliDB.Fields("Actividad")
              .Credito = AdoCliDB.Fields("Credito")
              .Direccion_Rep = .Direccion
              'Averiguamos si no funciona con unidades educativas
               Select Case .TD
                 Case "C", "R", "P"
                     .Representante = .Cliente
                     .RUC_CI_Rep = .CI_RUC
                     .TD_Rep = .TD
                 Case Else
                     .Representante = "CONSUMIDOR FINAL"
                     .RUC_CI_Rep = "9999999999999"
                     .TD_Rep = "R"
               End Select
             '.Salario = 0
            End If
            AdoCliDB.Close
       
           'Averiguamos si tiene Representante
            sSQL = "SELECT Representante, Cedula_R, Lugar_Trabajo_R, Telefono_RS, TD, Email_R, Tipo_Cta, Cod_Banco, Cta_Numero, Caducidad " _
                 & "FROM Clientes_Matriculas " _
                 & "WHERE Item = '" & NumEmpresa & "' " _
                 & "AND Periodo = '" & Periodo_Contable & "' " _
                 & "AND Codigo = '" & .Codigo & "' "
            Select_AdoDB AdoCliDB, sSQL
            If AdoCliDB.RecordCount > 0 Then
               Select Case AdoCliDB.Fields("TD")
                 Case "C", "R", "P"
                      If Len(AdoCliDB.Fields("Representante")) > 1 And Len(AdoCliDB.Fields("Cedula_R")) > 1 Then
                        .Representante = Replace(AdoCliDB.Fields("Representante"), "  ", " ")
                        .RUC_CI_Rep = AdoCliDB.Fields("Cedula_R")
                        .TD_Rep = AdoCliDB.Fields("TD")
                        .Telefono1 = AdoCliDB.Fields("Telefono_RS")
                        .TelefonoT = AdoCliDB.Fields("Telefono_RS")
                        .Tipo_Cta = AdoCliDB.Fields("Tipo_Cta")
                        .Cod_Banco = AdoCliDB.Fields("Cod_Banco")
                        .Cta_Numero = AdoCliDB.Fields("Cta_Numero")
                        .Direccion_Rep = AdoCliDB.Fields("Lugar_Trabajo_R")
                        .Fecha_Cad = AdoCliDB.Fields("Caducidad")
                        .EmailR = AdoCliDB.Fields("Email_R")
                      End If
                 Case Else
                     .Representante = "CONSUMIDOR FINAL"
                     .RUC_CI_Rep = "9999999999999"
                     .TD_Rep = "R"
               End Select
            End If
            AdoCliDB.Close
            CadAux = .Email1 & .Email2 & .EmailR
            If Len(CadAux) <= 3 Then
              .Email1 = EmailProcesos
              .Email2 = EmailProcesos
              .EmailR = EmailProcesos
            End If
       End With
    End If
    Leer_Datos_Clientes = TBenef
    End Function*/
  }

  function SinEspaciosDer($texto = ""){
    $resultado = substr(strrchr($texto, " "), 1);
    return $resultado;
  }

  function SinEspaciosIzq($texto = ""){
    $resultado = explode(" ", $texto);
    return $resultado[0];
  }

function grilla_generica_new($sql,$tabla,$titulo=false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,$tamaño_tabla=300)
{

  $conn = new Conectar();
  $cid=$conn->conexion();
  $cid1=$conn->conexion();
  $stmt = sqlsrv_query($cid, $sql);
  $columnas = sqlsrv_query($cid1, $sql);
  $columnas = sqlsrv_field_metadata($columnas);
  $columnas_uti = array();
  foreach ($columnas as $key => $value) {
      $d =  datos_tabla($tabla,$value['Name']);
      if(empty($d))
      {
        array_push($columnas_uti,$value['Name']);
      }else
      {
        array_push($columnas_uti, $d[0]);
      }
      // print_r($d);
  }
  // print_r($columnas_uti);die();
  $medida_body =array();
  $alinea_body =array();
   $datos =  array();
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }
     while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
            $datos[]=$row;
     }
 $tbl =' <style type="text/css">
  #datos_t tbody tr:nth-child(even) { background:#fffff;}
  #datos_t tbody tr:nth-child(odd) { background: #e2fbff;}
  #datos_t tbody tr:nth-child(even):hover {  background: #DDB;}
  #datos_t thead { background: #afd6e2; }
  #datos_t tbody tr:nth-child(odd):hover {  background: #DDA;}
 ';

 if($border)
 {
  $tbl.=' #datos_t table {border-collapse: collapse;}
  #datos_t table, th, td {  border: solid 1px #aba0a0;  padding: 2px;  }'; 
 }

 if($sombreado)
 {
  $tbl.='#datos_t tbody { box-shadow: 10px 10px 6px rgba(0, 0, 0, 0.6);  }
   #datos_t thead { background: #afd6e2;  box-shadow: 10px 0px 6px rgba(0, 0, 0, 0.6);} ';
 }

 if($head_fijo)
 {
 $tbl.='tbody { display:block; height:'.$tamaño_tabla.'px;  overflow-y:auto;  width:fit-content;}
  thead,tbody tr {    display:table;  width:100%;  table-layout:fixed;  } 
  thead { width: calc( 100% - 1.2em )/* scrollbar is average 1em/16px width, remove it from thead width */}
  /*thead tr {    display:table;  width:98.5%;  table-layout:fixed;  }*/ ';
 } 

 $tbl.="</style>";

if($titulo)
 {
  // $num = count($columnas_uti);
   $tbl.="<div class='text-center'><b>".$titulo."</b></div>";
 }
 $tbl.= '<div class="table-responsive" style="overflow-x: scroll;"><div style="width:fit-content;padding-right:20px"><table class="table" style="table-layout: fixed;" id="datos_t"><thead>';
  //cabecera de la consulta sql//
 if($botones)
  {
    $med_b = count($botones)*42;
    $tbl.='<th width="'.$med_b.'"></th>';
  }
  if($check)
  {
     $label = false;
     if(isset($check[0]['text_visible']))
      {
        $label = $check[0]['text_visible'];
      }
      if($label==true)
      {
        $tbl.='<th width="'.dimenciones_tabl(strlen($check[0]['boton'])).'" class="text-center">'.$check[0]['boton'].'</th>';
      }else
      {
        $tbl.='<th width="30px" class="text-center"></th>';
      }
  }
  foreach ($columnas_uti as $key => $value) {
    //calcula dimenciones de cada columna 
    if(is_array($value))
    {
    if($value['CHARACTER_MAXIMUM_LENGTH']!='')
    {
    if($value['CHARACTER_MAXIMUM_LENGTH']>=60)
    {
      $medida = '300px';
    }else{
      if(($value['CHARACTER_MAXIMUM_LENGTH']==1 && strlen($value['COLUMN_NAME'])>2) || ($value['CHARACTER_MAXIMUM_LENGTH']==2 && strlen($value['COLUMN_NAME'])>2) || ($value['CHARACTER_MAXIMUM_LENGTH']==3 && strlen($value['COLUMN_NAME'])>2))
      {
        $medida = dimenciones_tabl(strlen($value['COLUMN_NAME']));
      }else
      {
        $medida = dimenciones_tabl($value['CHARACTER_MAXIMUM_LENGTH']);
      }
    }
   }else
   {
     $medida = dimenciones_tabl(strlen($value['COLUMN_NAME']));
   }
   //fin de dimenciones
   //alinea dependiendo el tipo de dato que sea
     switch ($value['DATA_TYPE']) 
     {
        case 'nvarchar':
            $alineado = 'text-left'; 
          break;                
        case 'int':            
        case 'money':            
        case 'real':                             
            $alineado = 'text-right';  
          break;
        case 'bit':       
            $alineado = 'text-left'; 
          break;
        case 'datetime':       
          $alineado = 'text-left'; 
          $medida ='100px';
        break;
      } 
  //fin de alineacion        

    $tbl.='<th class="'.$alineado.'" style="width:'.$medida.'">'.$value['COLUMN_NAME'].'</th>'; 
    array_push($medida_body, $medida);
    array_push($alinea_body, $alineado);
  }else
  {
    // print_r($columnas);die();
    foreach ($columnas as $key6 => $value6) {
      if($value == $value6['Name'])
      {
         $medida = '300px';
         $alineado = 'text-left';
         if($value6['Size']<60)
          {
            if($value6['Size']!='')
            {
              $medida = dimenciones_tabl($value6['Size']);
            }else
            {
              $medida = dimenciones_tabl(strlen($value6['Name']));
            }
          }
           switch ($value6['Type']) 
            {
               case '-9':// campo nvarchar 
                   $alineado = 'text-left'; 
                 break;                
               case '4':  //campo int          
               case '7':  //campo real          
               case '3':  //campo money                         
                   $alineado = 'text-right';  
                 break;
               case '-7':      //campo bit 
                   $alineado = 'text-left'; 
                 break;
               case '93':       // campo date
                 $alineado = 'text-left'; 
                 $medida ='100px';
               break;
             }
           // $medida1 = explode('p',$medida);
           // $medida1  = ($medida1[0]-6).'px';
           // // $medida1 = $medida1.'px'; 
           $tbl.='<th class="'.$alineado.'" style="width:'.$medida.'">'.$value6['Name'].'</th>'; 
           array_push($medida_body, $medida);
           array_push($alinea_body, $alineado);
           break;
      }
    }
   
    // $tbl.='<th class="'.$alineado.'" style="width:'.$medida.'">'.$value['COLUMN_NAME'].'</th>'; 
    // array_push($medida_body, $medida);
  }
  }
  //fin de cabecera
  $tbl.='</thead><tbody>';

//cuerpo de la consulta
  $colum = 0;
  foreach ($datos as $key => $value) {
     $tbl.='<tr>';
     //crea botones
       if($botones)
        {
          $med_b = count($botones)*42;
          $tbl.='<td width="'.$med_b.'px">';
          foreach ($botones as $key3 => $value3) {
            $valor = '';
            $tipo = 'default';
            $icono = '<i class="far fa-circle nav-icon"></i>';
            if(isset($value3['tipo']))
            {
              $tipo = $value3['tipo'];
            }
            if(isset($value3['icono']))
            {
              $icono = $value3['icono'];
            }
            $k = explode(',', $value3['id']);
            foreach ($k as $key4 => $value4) {
              // print_r($value);die();
              $valor.="'".$value[$value4]."',";
            }
            if($valor!='')
            {
              $valor = substr($valor,0,-1);
            }
            $funcion = str_replace(' ','_', $value3['boton']);
            $tbl.='<button class="btn btn-sm btn-'.$tipo.'" onclick="'.$funcion.'('.$valor.')" title="'.$value3['boton'].'">'.$icono.'</button>';
          }
          $tbl.='</td>';
        }
        //fin de crea botones
        //crea los check
        if($check)
        {
           $label = false;
           $med_ch ='30px';
           if(isset($check[0]['text_visible']))
            {
              $label = $check[0]['text_visible'];
            }
            if($label)
            {
              $med_ch = dimenciones_tabl(strlen($check[0]['boton']));
            }
          $tbl.='<td width="'.$med_ch.'" class="text-center">';
          foreach ($check as $key3 => $value3) {
            $valor = '';
            $k = explode(',', $value3['id']);
            foreach ($k as $key4 => $value4) {
              // print_r($value);die();
              $valor.="'".$value[$value4]."',";
            }
            if($valor!='')
            {
              $valor = substr($valor,0,-1);
            }
            $funcion = str_replace(' ','_', $value3['boton']);
            
            $tbl.='<label><input type="checkbox" onclick="'.$funcion.'('.$valor.')" title="'.$value3['boton'].'"></label>';
            
          }
          $tbl.='</td>';
        }
        //fin de creacion de checks

     foreach ($value as $key1 => $value1) { 
             $medida = $medida_body[$colum]; 
             $alineado = $alinea_body[$colum]; 
             if(is_object($value1))
             {
               $tbl.='<td style="width:'.$medida.'">'.$value1->format('Y-m-d').'</td>';              
             }
             else
             {              
                  $tbl.='<td style="width:'.$medida.'" class="'.$alineado.'">'.utf8_encode($value1).'</td>';         
             }
            $colum+=1;    
         }

         $colum=0;  
      }          
          
  $tbl.='</tbody>
      </table>
      </div>
      
    </div>';
    // print_r($tbl);die();


    return $tbl;

}

function datos_tabla($tabla,$campo=false)
{
    $conn = new Conectar();
    $cid=$conn->conexion();
    $sql="SELECT COLUMN_NAME,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH
    FROM Information_Schema.Columns
    WHERE TABLE_NAME = '".$tabla."' ";
    if($campo){
      $sql.=" AND COLUMN_NAME = '".$campo."'";
    }
    // print_r($sql);die();
    $stmt = sqlsrv_query($cid, $sql);
     $datos =  array();
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }
     while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
            $datos[]=$row;
     }

     return $datos;
}


  function Leer_Cta_Catalogo($CodigoCta = ""){
    
    //conexion
    $conn = new Conectar();
    $cid=$conn->conexion();

    //RatonReloj
    $NoEncontroCta = true;
    $Cuenta = G_NINGUNO;
    $Codigo_Catalogo = G_NINGUNO;
    $TipoCta = "G";
    $SubCta = "N";
    $TipoPago = "01";
    $Moneda_US = false;
    $cuenta = [];
    $auxCodigoCta = intval(substr($CodigoCta, 0,1));
    if ($auxCodigoCta >= 1) {
      echo "entra";
      $sql = "SELECT Codigo, Cuenta, TC, ME, DG, Tipo_Pago
            FROM Catalogo_Cuentas
            WHERE Codigo = '".$CodigoCta."' 
            AND Item = '".$_SESSION['INGRESO']['item']."'
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
      $datos = sqlsrv_query( $cid, $sql);
      $cuenta['TipoPago'] = 0;
      while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
        $cuenta['Codigo_Catalogo'] = $value['Codigo'];
        $cuenta['Cuenta'] = $value['Cuenta'];
        $cuenta['SubCta'] = $value['TC'];
        $cuenta['Moneda_US'] = $value['ME'];
        $cuenta['TipoCta'] = $value['DG'];
        $cuenta['TipoPago'] = $value['Tipo_Pago'];
      }
      if (intval($cuenta['TipoPago']) <= 0) {
        $cuenta['TipoPago'] = "01";
        $NoEncontroCta = false;
      }
      if ($NoEncontroCta) {
        $sql = "SELECT Codigo, Cuenta, TC, ME, DG, Tipo_Pago
                FROM Catalogo_Cuentas
                WHERE Codigo_Ext LIKE '%".$CodigoCta."'_
                AND Item = '".$_SESSION['INGRESO']['item']."'
                AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
        $datos = sqlsrv_query( $cid, $sql);
        while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
          $cuenta['Codigo_Catalogo'] = $value['Codigo'];
          $cuenta['Cuenta'] = $value['Cuenta'];
          $cuenta['SubCta'] = $value['TC'];
          $cuenta['Moneda_US'] = $value['ME'];
          $cuenta['TipoCta'] = $value['DG'];
          $cuenta['TipoPago'] = $value['Tipo_Pago'];
        }
        if (intval($cuenta['TipoPago']) <= 0) {
          $cuenta['TipoPago'] = "01";
          $NoEncontroCta = false;
        }
      }
    }
    return $cuenta;
  }

  function Calculos_Totales_Factura($codigoCliente){
    //conexion
    $conn = new Conectar();
    $cid=$conn->conexion();

    $TFA['SubTotal'] = 0;
    $TFA['Con_IVA'] = 0;
    $TFA['Sin_IVA'] = 0;
    $TFA['Descuento'] = 0;
    $TFA['Total_IVA'] = 0;
    $TFA['Total_MN'] = 0;
    $TFA['Total_ME'] = 0;
    $TFA['Descuento2'] = 0;
    $TFA['Descuento_0'] = 0;
    $TFA['Descuento_X'] = 0;

    //Miramos de cuanto es la factura para los calculos de los totales
    $Total_Desc_ME = 0;
    $sql = "SELECT *
          FROM Asiento_F 
          WHERE Item = '".$_SESSION['INGRESO']['item']."'
          AND CodigoU = '".$codigoCliente."' ";
    $datos = sqlsrv_query( $cid, $sql);
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      $cuenta['Codigo_Catalogo'] = $value['Codigo'];
      $cuenta['Cuenta'] = $value['Cuenta'];
      $cuenta['SubCta'] = $value['TC'];
      $cuenta['Moneda_US'] = $value['ME'];
      $cuenta['TipoCta'] = $value['DG'];
      $cuenta['TipoPago'] = $value['Tipo_Pago'];
      $TFA['Descuento'] += $value['Total_Desc'];
      $TFA['Descuento2'] += $value['Total_Desc2'];
      $TFA['Total_IVA'] += $value['Total_IVA'];
      if ($value['Total_IVA']) {
        $TFA['Con_IVA'] += $value['TOTAL'];
        $TFA['Descuento_X'] = $TFA['Descuento_X'] + $TFA['Descuento'] + $TFA['Descuento2'];
      }else{
        $TFA['Sin_IVA'] += $value['TOTAL'];
        $TFA['Descuento_0'] = $TFA['Descuento_0'] + $TFA['Descuento'] + $TFA['Descuento2'];
      }
    }

    $TFA['Total_IVA'] = round($TFA['Total_IVA'],2);
    $TFA['Con_IVA'] = round($TFA['Con_IVA'],2);
    $TFA['Sin_IVA'] = round($TFA['Sin_IVA'],2);
    $TFA['Servicio'] = round(($TFA['Sin_IVA'] + $TFA['Con_IVA'] - $TFA['Descuento'] - $TFA['Descuento2']) 
    * $Porc_Serv,2);
    $TFA['SubTotal'] = $TFA['Sin_IVA'] + $TFA['Con_IVA'] - $TFA['Descuento'] - $TFA['Descuento2'];
    $TFA['Total_MN'] = $TFA['Sin_IVA'] + $TFA['Con_IVA'] - $TFA['Descuento'] - $TFA['Descuento2'] + $TFA['Total_IVA'] + $TFA['Servicio'];
  }

  function Existe_Factura($TFA){
    //conexion
    $conn = new Conectar();
    $cid=$conn->conexion();

    $Respuesta = false;
    //Consultamos si exista la factura
    $sql = "SELECT TC, Serie, Factura 
            FROM Facturas 
            WHERE Factura = ".$TFA['Factura']."
            AND TC = '".$TFA['TC']."' 
            AND Serie = '".$TFA['Serie']."' 
            AND Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
    $datos = sqlsrv_query( $cid, $sql);
    $rows_affected = sqlsrv_rows_affected( $stmt);
    if ($rows_affected > 0) {
      $Respuesta = true;
    }
    return $Respuesta;
  }

  function agregar_factura($datos1)
  {
    $cid=$this->conn->conexion();
    $nombrec= $datos1['nombrec'];
    $ruc= $datos1['ruc'];
    $email= $datos1['email'];
    $ser= $datos1['ser'];
    $ser1=explode("_", $ser);
    $n_fac= $datos1['n_fac'];
    $me= $datos1['me'];
    $total_total_= $datos1['total_total_'];
    $total_abono= $datos1['total_abono']; 
    $propina_a= $datos1['propina_a'];
    $fecha_actual = date("Y-m-d"); 
    $hora = date("H:i:s");
    $fechaEntera = strtotime($fecha_actual);
    $anio = date("Y", $fechaEntera);
    $mes = date("m", $fechaEntera);
    $total_iva=0;
    $imp=0;
    if(isset($datos1['imprimir']))
    {
      $imp=$datos1['imprimir'];
    }
    if($imp==0)
    {
      //$mes=$mes+1;
      //consultamos clientes
      $sql="SELECT * FROM Clientes WHERE  (CI_RUC= '".$ruc."') AND Cliente='".$nombrec."' ";
      $stmt = sqlsrv_query($cid, $sql);
      if( $stmt === false)  
      {  
        echo "Error en consulta PA.\n";  
        die( print_r( sqlsrv_errors(), true));  
      }
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
      {
        $codigo=$row[2];
      }
      //consultamos catalogo linea
      $sql="SELECT   Codigo, CxC
      FROM   Catalogo_Lineas
      WHERE   (Periodo = '".$_SESSION['INGRESO']['periodo']."') AND 
      (Item = '".$_SESSION['INGRESO']['item']."') AND (Serie = '".$ser1[2]."') AND (Fact = 'FA')";
      //echo $sql;
      $stmt = sqlsrv_query($cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
      {
        $cxc=$row[1];
        $cod_linea=$row[0];
      }
      //verificamos que no exista la factura
      $sql="SELECT        TOP (200) Periodo, T, TC, CodigoC, Factura, Fecha, Codigo, CodigoL, Producto, Cantidad, Precio, Total, Total_IVA, Ruta, Ticket, Item, Corte, Reposicion, Total_Desc, No_Hab, Cod_Ejec, Porc_C, Com_Pag, Cta_Venta, CodigoU, 
                 CodBodega, Tonelaje, Costo, Comision, Mes, X, Producto_Aux, Puntos, Autorizacion, Serie, CodMarca, Gramaje, Orden_No, Mes_No, C, CodigoB, Precio2, Total_Desc2, SubTotal_NC, Total_IVA_NC, Fecha_IN, Fecha_OUT, 
                 Cant_Hab, Tipo_Hab, Codigo_Barra, Serie_NC, Autorizacion_NC, Fecha_NC, Secuencial_NC, Fecha_V, Cant_Bonif, Lote_No, Fecha_Fab, Fecha_Exp, Modelo, Procedencia, Serie_No, Porc_IVA, Cantidad_NC, Total_Desc_NC, 
                 ID
          FROM            Detalle_Factura
          WHERE        (Factura = '".$n_fac."') AND (Serie = '".$ser1[2]."') AND (Item = '".$_SESSION['INGRESO']['item']."') AND (Periodo = '".$_SESSION['INGRESO']['periodo']."')
         ";
              
      //echo $sql;
      //die();
      $stmt =sqlsrv_query( $cid, $sql);
      if( $stmt === false)  
      {  
         echo "Error en consulta PA.\n";  
         die( print_r( sqlsrv_errors(), true));  
      }
      $ii=0;
      while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
      {
        $ii++;
      }
      if($ii==0)
      {
        //agregamos detalle factura
        $sql="select * ". 
            "FROM Asiento_F
             WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
             AND  HABIT='".$me."' 
             ORDER BY CODIGO";
                
        //echo $sql;
        //die();
        $total_coniva=0;
        $total_siniva=0;
        $stmt =sqlsrv_query( $cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        /*
        SELECT        TOP (200) Periodo, Item, C, T, ME, Cod_CxC, TC, Serie, Autorizacion, Factura, CodigoC, Fecha, Fecha_C, Fecha_V, SubTotal, Con_IVA, Sin_IVA, IVA, Descuento, Porc_C, Comision, Servicio, Total_MN, Total_ME, Saldo_MN, 
                   Saldo_ME, Saldo_Actual, Forma_Pago, Cotizacion, Cta_CxP, Cta_Venta, Cod_Ejec, Com_Pag, Nota, Observacion, CodigoU, SubCta, Hora, Vencimiento, P, Fecha_Aut, Dias_Vencidos, Desc_0, Desc_X, RUC_CI, TB, Razon_Social, 
                   Direccion_RS, Telefono_RS, CodigoB, Descuento2, Total_Efectivo, Total_Banco, Total_Ret_Fuente, Total_Ret_IVA_B, Total_Ret_IVA_S, Otros_Abonos, Total_Abonos, Abonos_MN, Clave_Acceso, Hora_Aut, Estado_SRI, Efectivo, 
                   CodigoDr, Serie_R, Secuencial_R, Autorizacion_R, Fecha_R, Autorizacion_NC, Clave_Acceso_NC, Hora_Aut_NC, Estado_SRI_NC, Tipo_Pago, Error_FA_SRI, Porc_IVA, Imp_Mes, SP, Orden_Compra, X, Chq_Posf, Venc_0_60, 
                   Venc_61_90, Venc_91_120, Venc_121_360, Venc_mas_360, ID
        FROM            Facturas
        WHERE        (Factura = '1569') AND (Item = '001') AND (Periodo = '.')
        SELECT        TOP (200) Periodo, T, TC, CodigoC, Factura, Fecha, Codigo, CodigoL, Producto, Cantidad, Precio, Total, Total_IVA, Ruta, Ticket, Item, Corte, Reposicion, Total_Desc, No_Hab, Cod_Ejec, Porc_C, Com_Pag, Cta_Venta, CodigoU, 
                   CodBodega, Tonelaje, Costo, Comision, Mes, X, Producto_Aux, Puntos, Autorizacion, Serie, CodMarca, Gramaje, Orden_No, Mes_No, C, CodigoB, Precio2, Total_Desc2, SubTotal_NC, Total_IVA_NC, Fecha_IN, Fecha_OUT, 
                   Cant_Hab, Tipo_Hab, Codigo_Barra, Serie_NC, Autorizacion_NC, Fecha_NC, Secuencial_NC, Fecha_V, Cant_Bonif, Lote_No, Fecha_Fab, Fecha_Exp, Modelo, Procedencia, Serie_No, Porc_IVA, Cantidad_NC, Total_Desc_NC, 
                   ID
        FROM            Detalle_Factura
        WHERE        (Factura = '1569') AND (Item = '001') AND (Periodo = '.')
        */
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          $dato[0]['campo']='T';
          $dato[0]['dato']='C';
          $dato[1]['campo']='TC';
          $dato[1]['dato']='FA';
          $dato[2]['campo']='CodigoC';
          $dato[2]['dato']=$codigo;
          $dato[3]['campo']='Factura';
          $dato[3]['dato']=$n_fac;
          $dato[4]['campo']='Fecha';
          $dato[4]['dato']=$fecha_actual; 
          $dato[5]['campo']='Codigo';
          $dato[5]['dato']=$row[0];
          $dato[6]['campo']='CodigoL';
          $dato[6]['dato']=$cod_linea;  
          $dato[7]['campo']='Producto';
          $dato[7]['dato']=$row[3]; 
          $dato[8]['campo']='Cantidad';
          $dato[8]['dato']=$row[1];
          $dato[9]['campo']='Precio';
          $dato[9]['dato']=$row[4]; 
          $dato[10]['campo']='Total';
          $dato[10]['dato']=$row[9];//descontar descuentos  
          $dato[11]['campo']='Total_IVA';
          $dato[11]['dato']=$row[7];
          //$dato[12]['campo']='Cta_Venta';
          //$dato[12]['dato']='.';  
          $dato[12]['campo']='Item';
          $dato[12]['dato']=$_SESSION['INGRESO']['item']; 
          $dato[13]['campo']='CodigoU';
          $dato[13]['dato']=$_SESSION['INGRESO']['CodigoU'];  
          $dato[14]['campo']='Periodo';
          $dato[14]['dato']=$_SESSION['INGRESO']['periodo'];  
          $dato[15]['campo']='Serie';
          $dato[15]['dato']=$ser1[2]; 
          $dato[16]['campo']='Mes_No';
          $dato[16]['dato']=$mes; 
          //$dato[17]['campo']='C';
          //$dato[17]['dato']=0;  
          /*$dato[18]['campo']='Fecha_IN';
          $dato[18]['dato']=$fecha_actual;  
          $dato[19]['campo']='Fecha_OUT';
          $dato[19]['dato']=$fecha_actual;  
          $dato[20]['campo']='Fecha_NC';
          $dato[20]['dato']=$fecha_actual;
          $dato[21]['campo']='Fecha_V';
          $dato[21]['dato']=$fecha_actual;  
          $dato[22]['campo']='Fecha_Fab';
          $dato[22]['dato']=$fecha_actual;  
          $dato[23]['campo']='Fecha_Exp';
          $dato[23]['dato']=$fecha_actual;*/  
          $dato[17]['campo']='Porc_IVA';
          $dato[17]['dato']=$_SESSION['INGRESO']['porc']; 
          $dato[18]['campo']='Autorizacion';
          $dato[18]['dato']=$_SESSION['INGRESO']['RUC'];
          $total_iva=$total_iva+$row[7];
          $this->insert_generico("Detalle_Factura",$dato);
          if($row[7]==0)
          {
            $total_siniva=$row[9]+$total_siniva;
          }
          else
          {
            $total_coniva=$row[9]+$total_coniva;
          }
        }
        //agregamos abono
        $sql="SELECT * FROM Asiento_Abonos WHERE  (HABIT= '".$me."') AND 
        (Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Item = '".$_SESSION['INGRESO']['item']."')";
        $stmt = sqlsrv_query($cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }       
        
        /*
        INSERT INTO Trans_Abonos
           (Periodo,C,ME,T,TP,Cta(preguntar),Cta_CxP(preguntar),Fecha,Recibo_No,Comprobante(preguntar),Factura,Total,Abono(preguntar),
           CodigoC,Banco,Cheque,CodigoU
           ,Item,Serie,Tipo_Cta ,Fecha_Aut_NC,Fecha_Aut,Ejecutivo)
        */
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
        {
          //datos de la cuenta
          $sql="SELECT TC,Codigo,Cuenta,Tipo_Pago FROM Catalogo_Cuentas 
            WHERE TC IN ('BA','CJ','CP','C','P','TJ','CF','CI','CB') 
            AND DG = 'D' AND Item = '".$_SESSION['INGRESO']['item']."' 
            AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo='".$row[11]."' ";
            //echo $sql.'<br>';
          $stmt1 =sqlsrv_query( $cid, $sql);
          if( $stmt1 === false)  
          {  
             echo "Error en consulta PA.\n";  
             die( print_r( sqlsrv_errors(), true));  
          }
          $cod_cue='.';
          $TC='.';
          $cuenta='.';
          $tipo_pago='.';
          while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
          {
            $cod_cue=$row1[1];
            $TC=$row1[0];
            $cuenta=$row1[2];
            if($row1[3]!='.')
            {
              if($tipo_pago=='.')
              {
                $tipo_pago=$row1[3];
              }
              else
              {
                if($tipo_pago<$row1[3])
                {
                  $tipo_pago=$row1[3];
                }
              }
            }
          }
          $dato[0]['campo']='T';
          $dato[0]['dato']='C';
          $dato[1]['campo']='TP';
          $dato[1]['dato']='FA';
          $dato[2]['campo']='CodigoC';
          $dato[2]['dato']=$codigo;
          $dato[3]['campo']='Factura';
          $dato[3]['dato']=$n_fac;
          $dato[4]['campo']='Fecha';
          $dato[4]['dato']=$fecha_actual; 
          $dato[5]['campo']='Cta';
          $dato[5]['dato']=$cod_cue;
          $dato[6]['campo']='Cta_CxP';
          $dato[6]['dato']=$cod_linea;  
          $dato[7]['campo']='Recibo_No';
          $dato[7]['dato']='0000000000';  
          $dato[8]['campo']='Comprobante';
          $dato[8]['dato']='.';
          $dato[9]['campo']='Abono';
          //$dato[9]['dato']=$row[9]; 
          $dato[9]['dato']=$total_total_;
          $dato[10]['campo']='Total';
          //$dato[10]['dato']=$row[9];  
          $dato[10]['dato']=$total_total_;
          $dato[11]['campo']='Cheque';
          $dato[11]['dato']=$row[12];
          $dato[12]['campo']='Fecha_Aut_NC';
          $dato[12]['dato']=$fecha_actual;  
          $dato[13]['campo']='Item';
          $dato[13]['dato']=$_SESSION['INGRESO']['item']; 
          $dato[14]['campo']='CodigoU';
          $dato[14]['dato']=$_SESSION['INGRESO']['CodigoU'];  
          $dato[14]['campo']='Periodo';
          $dato[14]['dato']=$_SESSION['INGRESO']['periodo'];  
          $dato[15]['campo']='Serie';
          $dato[15]['dato']=$ser1[2]; 
          $dato[16]['campo']='Fecha_Aut';
          $dato[16]['dato']=$fecha_actual;
          $dato[17]['campo']='C';
          $dato[17]['dato']=0;
          $dato[18]['campo']='Tipo_Cta';
          $dato[18]['dato']=$TC;
          $dato[19]['campo']='Banco';
          $dato[19]['dato']=$cuenta;
          $dato[20]['campo']='Autorizacion';
          $dato[20]['dato']=$_SESSION['INGRESO']['RUC'];
          $this->insert_generico("Trans_Abonos",$dato);
        }
        /*
        INSERT INTO Detalle_Factura
             (T,TC,CodigoC ,Factura,Fecha,Codigo,CodigoL ,Producto,Cantidad,Reposicion ,Precio,Total ,Total_Desc,Total_IVA,Ruta,Ticket,No_Hab
             ,Cod_Ejec,Porc_C,Cta_Venta,Item,CodigoU,Periodo,Com_Pag,CodBodega,Tonelaje,Corte,X,Costo,Comision,Mes,Producto_Aux,Puntos
             ,Autorizacion,Serie,CodMarca,Gramaje,Orden_No,Mes_No,C,CodigoB,Precio2,Total_Desc2,SubTotal_NC,Total_IVA_NC,Fecha_IN
             ,Fecha_OUT,Cant_Hab,Tipo_Hab,Codigo_Barra,Serie_NC,Autorizacion_NC,Fecha_NC,Secuencial_NC,Fecha_V ,Cant_Bonif,Lote_No
             ,Fecha_Fab,Fecha_Exp,Modelo,Procedencia,Serie_No,Porc_IVA ,Cantidad_NC,Total_Desc_NC)
        
        */
        /*
        INSERT INTO Facturas
             (C,T ,TC,ME,Factura,CodigoC ,Fecha,Fecha_C ,Fecha_V,SubTotal,Con_IVA ,Sin_IVA,IVA,Total_MN
             ,Cta_CxP,Cta_Venta,Item ,CodigoU,Periodo,Cod_CxC,Com_Pag
             ,Hora ,X,Serie,Vencimiento,P,Fecha_Aut,RUC_CI,TB,Razon_Social,Total_Efectivo,Total_Banco,Otros_Abonos,Total_Abonos,
             Abonos_MN,Tipo_Pago,Porc_IVA)
         VALUES
        */
        $query="INSERT INTO Facturas
             (C,T ,TC,ME,Factura,CodigoC ,Fecha,Fecha_C ,Fecha_V,SubTotal,Con_IVA ,Sin_IVA,IVA,Total_MN
             ,Cta_CxP,Cta_Venta,Item ,CodigoU,Periodo,Cod_CxC,Com_Pag
             ,Hora ,X,Serie,Vencimiento,P,Fecha_Aut,RUC_CI,TB,Razon_Social,Total_Efectivo,Total_Banco,Otros_Abonos,Total_Abonos,
             Abonos_MN,Tipo_Pago,Porc_IVA)
         VALUES
           (1
           ,'C'
           ,'FA'
           ,0
           ,".$n_fac."
           ,'".$codigo."'
           ,'".$fecha_actual."'
           ,'".$fecha_actual."'
           ,'".$fecha_actual."'
           ,".$total_total_."
           ,0
           ,".$total_total_."
           ,".$total_iva."
           ,".$total_total_."
           ,'".$cxc."'
           ,'0'
           ,'".$_SESSION['INGRESO']['item']."'
           ,'".$_SESSION['INGRESO']['CodigoU']."'
           ,'".$_SESSION['INGRESO']['periodo']."'
           ,'".$cod_linea."'
           ,0
           ,'".$hora."'
           ,'X'
           ,'".$ser1[2]."'
           ,'".$fecha_actual."'
           ,0
           ,'".$fecha_actual."'
           ,'".$ruc."'
           ,'R'
           ,'".$nombrec."'
           ,".$total_abono."
           ,0
           ,0
           ,".$total_abono."
           ,".$total_abono."
           ,'20'
           ,".$_SESSION['INGRESO']['porc']."
          )";
        //echo $query;
        
        $dato[0]['campo']='C';
        $dato[0]['dato']=1;
        $dato[1]['campo']='T';
        $dato[1]['dato']='C';
        $dato[2]['campo']='TC';
        $dato[2]['dato']='FA';
        $dato[3]['campo']='ME';
        $dato[3]['dato']=0;
        $dato[4]['campo']='Factura';
        $dato[4]['dato']=$n_fac;
        $dato[5]['campo']='CodigoC';
        $dato[5]['dato']=$codigo;
        $dato[6]['campo']='Fecha';
        $dato[6]['dato']=$fecha_actual;
        $dato[7]['campo']='Fecha_C';
        $dato[7]['dato']=$fecha_actual;
        $dato[8]['campo']='Fecha_V';
        $dato[8]['dato']=$fecha_actual;
        $dato[9]['campo']='SubTotal';
        $dato[9]['dato']=($total_total_-$total_iva);
        $dato[10]['campo']='Con_IVA';
        $dato[10]['dato']=($total_coniva-$total_iva);
        $dato[11]['campo']='Sin_IVA';
        $dato[11]['dato']=$total_siniva;
        $dato[12]['campo']='IVA';
        $dato[12]['dato']=$total_iva;
        $dato[13]['campo']='Total_MN';
        $dato[13]['dato']=$total_total_;
        $dato[14]['campo']='Cta_CxP';
        $dato[14]['dato']=$cxc;
        $dato[15]['campo']='Cta_Venta';
        $dato[15]['dato']='0';
        $dato[16]['campo']='Item';
        $dato[16]['dato']=$_SESSION['INGRESO']['item'];
        $dato[17]['campo']='CodigoU';
        $dato[17]['dato']=$_SESSION['INGRESO']['CodigoU'];
        $dato[18]['campo']='Periodo';
        $dato[18]['dato']=$_SESSION['INGRESO']['periodo'];
        $dato[19]['campo']='Cod_CxC';
        $dato[19]['dato']=$cod_linea;
        $dato[20]['campo']='Com_Pag';
        $dato[20]['dato']=0;
        $dato[21]['campo']='Hora';
        $dato[21]['dato']=$hora;
        $dato[22]['campo']='X';
        $dato[22]['dato']='X';
        $dato[23]['campo']='Serie';
        $dato[23]['dato']=$ser1[2];
        $dato[24]['campo']='Vencimiento';
        $dato[24]['dato']=$fecha_actual;
        $dato[25]['campo']='P';
        $dato[25]['dato']=0;
        $dato[26]['campo']='Fecha_Aut';
        $dato[26]['dato']=$fecha_actual;
        $dato[27]['campo']='RUC_CI';
        $dato[27]['dato']=$ruc;
        $dato[28]['campo']='TB';
        $dato[28]['dato']='R';
        $dato[29]['campo']='Razon_Social';
        $dato[29]['dato']=$nombrec;
        $dato[30]['campo']='Total_Efectivo';
        $dato[30]['dato']=$total_total_;
        $dato[31]['campo']='Total_Banco';
        $dato[31]['dato']=0;
        $dato[32]['campo']='Otros_Abonos';
        $dato[32]['dato']=0;
        $dato[33]['campo']='Total_Abonos';
        $dato[33]['dato']=$total_total_;
        $dato[34]['campo']='Abonos_MN';
        $dato[34]['dato']=$total_total_;
        $dato[35]['campo']='Tipo_Pago';
        $dato[35]['dato']=$tipo_pago;
        $dato[36]['campo']='Porc_IVA';
        $dato[36]['dato']=$_SESSION['INGRESO']['porc'];
        $dato[37]['campo']='Propina';
        $dato[37]['dato']=$propina_a; 
        $dato[38]['campo']='Autorizacion';
        $dato[38]['dato']=$_SESSION['INGRESO']['RUC'];
        $this->insert_generico("Facturas",$dato);
        $n_fac++;
        //incrementar contador de facturas
        $sql="UPDATE Codigos set Numero='".$n_fac."'
        WHERE  (Concepto = 'FA_SERIE_".$ser1[2]."') AND (Item = '".$_SESSION['INGRESO']['item']."') 
        AND (Periodo = '".$_SESSION['INGRESO']['periodo']."')";
        //echo $sql;
        $stmt1 =sqlsrv_query( $cid, $sql);
        if( $stmt1 === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        //eliminar campos temporales asiento_f
        $sql="DELETE ". 
          "FROM Asiento_F
          WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
          AND  HABIT='".$me."' ";
        //echo $sql;
        $stmt = sqlsrv_query($cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        //eliminar abono
        $sql="DELETE FROM Asiento_Abonos WHERE  (HABIT= '".$me."') AND 
        (Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Item = '".$_SESSION['INGRESO']['item']."')";
        //echo $sql;
        $stmt = sqlsrv_query($cid, $sql);
        if( $stmt === false)  
        {  
           echo "Error en consulta PA.\n";  
           die( print_r( sqlsrv_errors(), true));  
        }
        cerrarSQLSERVERFUN($cid);
          //campo que informar imprimir pdf automatico
          return 2;
      }
      else
      {
        return 0;
      }
    }
    else
    {
      //liberar mesa 
      $this->liberar($me);
      //datos para el pdf
      $param=array();
      $param[0]['nombrec']=$nombrec;
      //echo $param[0]['nombrec'].' -- ';
      $param[0]['ruc']=$ruc;
      $param[0]['mesa']=$me;
      $param[0]['PFA']='F';
      $param[0]['serie']=$ser1[2];
      $param[0]['factura']=($n_fac-1);
      imprimirDocElPF(null,$me,null,null,null,0,$param,'F',$cid);
      //imprimir factura despues de autorizar 
      return 2;
    }
  }
?>
