<?php
//Autorizar
if(isset($_POST['ajax_page']) ) 
{
	if($_REQUEST['ajax_page']=='autorizar')
	{
		autorizar();
	}
}
//para autorizar facturas
function autorizar()
{
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
	//echo " Entro ";
	//echo " ip: ".$_POST['IP'].' ';
	 // print_r($_POST['IP']);die();

	$IP = $desencriptar($_POST['IP']);
	$Ba = $desencriptar($_POST['Ba']);
	$Us = $desencriptar($_POST['Us']);
	$Co = $desencriptar($_POST['Co']);
	$Ti = $desencriptar($_POST['Ti']);
	$Pu = $desencriptar($_POST['Pu']);
	$datos = $_POST['datos'];
	//datos certificado
	$ru_ce = $desencriptar($_POST['ru_ce']);
	$cl_ce = $desencriptar($_POST['cl_ce']);
	$amb = $desencriptar($_POST['amb']);
	$nom_com = $desencriptar($_POST['nom_com']);
	$raz_soc = $desencriptar($_POST['raz_soc']);
	$ruc = $desencriptar($_POST['ruc']);
	$dir = $desencriptar($_POST['dir']);
	$datos1 = explode("--", $datos);
	$esta = substr($datos1[0], 0, 3);
	$pto_e = substr($datos1[0], 3, 5);
	$cod_doc = $desencriptar($_POST['cod_doc']);
	$tc=$desencriptar($_POST['tc']);
	$item=$desencriptar($_POST['item']);
	$periodo=$desencriptar($_POST['peri']);
	//$serie='001003';
	//$numero='502';
	/*echo $IP.' '.$Ba.' '.$Us.' '.$Co.' '.$Ti.' '.$Pu.' '.$datos.' '.$ru_ce.' '.$cl_ce.' '.$amb.' nombre comercial '.$nom_com.' razon social '.$raz_soc.
	' ruc '.$ruc.' direccion '.$dir.' '.
	$datos1[0].' '.$datos1[1].' '.$esta.' '.$pto_e.' '.$cod_doc.'<br>';*/
	$cabecera=array();
	$cabecera[0]['ambiente']=$amb;
	$cabecera[0]['ruta_ce']=$ru_ce;
	$cabecera[0]['clave_ce']=$cl_ce;
	$cabecera[0]['nom_comercial_principal']=$nom_com;
	$cabecera[0]['razon_social_principal']=$raz_soc;
	$cabecera[0]['ruc_principal']=$ruc;
	$cabecera[0]['direccion_principal']=$dir;
	$cabecera[0]['serie']=$datos1[0];
	$cabecera[0]['factura']=$datos1[1];
	$cabecera[0]['esta']=$esta;
	$cabecera[0]['pto_e']=$pto_e;
	$cabecera[0]['cod_doc']=$cod_doc;
	$cabecera[0]['item']=$item;
	$cabecera[0]['tc']=$tc;
	$cabecera[0]['periodo']=$periodo;

	// print_r($cabecera[0]);die();
	include_once("conexion.php");
	if($cod_doc=='01')
	{
		if($Ti=='MYSQL')
		{
			$conexion=conectar($IP, $Us, $Co, $Ba, $Tu);
			$rows=seleccionMysql($conexion,'*','eDoc_empresa');
			foreach($rows as $row)
			{
				/*echo $row['ruc'].' '.$row['razonSocial'];*/
			}
			cerrar($conexion);
		}
		if($Ti=='SQL SERVER')
		{
			/*echo "entro sql server";*/
			$conexion=conexionSQL($IP, $Us, $Co, $Ba, $Pu);
			//factura
			//$cant=cantidadCamposSqlServer($conexion,'Facturas', " Serie='".$cabecera[0]['serie']."' AND Factura='".$cabecera[0]['factura']."' ");
			$stmt=seleccionSqlServer($conexion,'*','Facturas', " Item = '".$cabecera[0]['item']."' 
            AND Periodo = '".$cabecera[0]['periodo']."' 
			AND TC = '".$cabecera[0]['tc']."' 
			AND Serie = '".$cabecera[0]['serie']."' 
			AND Factura = ".$cabecera[0]['factura']." 
			AND LEN(Autorizacion) = 13 
			AND T <> 'A' ");
			
			//$stmt=seleccionSqlServer($conexion,'*','Facturas', " Serie='".$cabecera[0]['serie']."' AND Factura='".$cabecera[0]['factura']."' ");
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
			{
				/*$RUC_CI=$row['RUC_CI'];
				$Fecha=$row['Fecha']->format('Y-m-d');
				$Razon_Social=$row['Razon_Social'];
				$Direccion_RS=$row['Direccion_RS'];
				$Sin_IVA=(float)$row['Sin_IVA'];
				$Descuento=(float)$row['Descuento']+$row['Descuento2'];
				//duda
				$baseImponible=(float)($row['Sin_IVA']+$Descuento);
				$Porc_IVA=round((float)$row['Porc_IVA'],2);
				$Con_IVA=(float)$row['Con_IVA'];
				$Total_MN=(float)$row['Total_MN'];
				$formaPago = $row['Forma_Pago'];
				$Propina = $row['Propina'];
				$Autorizacion = $row['Autorizacion'];
				$Imp_Mes = $row['Imp_Mes'];
				$SP = $row['SP'];*/
				//llenamos matriz para el xml
				$cabecera[0]['RUC_CI']=$row['RUC_CI'];
				$cabecera[0]['Fecha']=$row['Fecha']->format('Y-m-d');
				$cabecera[0]['Razon_Social']=$row['Razon_Social'];
				$cabecera[0]['Direccion_RS']=$row['Direccion_RS'];
				$cabecera[0]['Sin_IVA']=(float)$row['Sin_IVA'];
				$cabecera[0]['Descuento']=(float)$row['Descuento']+$row['Descuento2'];
				$cabecera[0]['baseImponible']=(float)($row['Sin_IVA']+$cabecera[0]['Descuento']);
				$cabecera[0]['Porc_IVA']=round((float)$row['Porc_IVA'],2);
				$cabecera[0]['Con_IVA']=(float)$row['Con_IVA'];
				$cabecera[0]['Total_MN']=(float)$row['Total_MN'];
				$cabecera[0]['formaPago']=$row['Forma_Pago'];
				$cabecera[0]['Propina']=$row['Propina'];
				$cabecera[0]['Autorizacion']=$row['Autorizacion'];
				$cabecera[0]['Imp_Mes']=$row['Imp_Mes'];
				$cabecera[0]['SP']=$row['SP'];
				$cabecera[0]['CodigoC']=$row['CodigoC'];
				$cabecera[0]['TelefonoC']=$row['Telefono_RS'];
				$cabecera[0]['Orden_Compra']=$row['Orden_Compra'];
				$cabecera[0]['baseImponibleSinIva']=$cabecera[0]['Sin_IVA']-$row['Desc_0'];
				$cabecera[0]['baseImponibleConIva']=$cabecera[0]['Con_IVA']-$row['Desc_X'];
				$cabecera[0]['totalSinImpuestos']=$cabecera[0]['Sin_IVA'] + $cabecera[0]['Con_IVA'] - $cabecera[0]['Descuento'];
				$cabecera[0]['IVA']=(float)$row['IVA'];
				$cabecera[0]['Total_MN']=(float)$row['Total_MN'];
			}
			if(!isset($cabecera[0]['CodigoC']))
			{
				echo "<script languaje='javascript' type='text/javascript'>alert('Ya se encuentra autorizado este documento ');</script>";
				echo "<script languaje='javascript' type='text/javascript'>window.close();</script>";
				die();
			}
			//buscamos datos adicionales
			$stmt=seleccionSqlServer($conexion,'*','Clientes', " Codigo = '".$cabecera[0]['CodigoC']."' ");
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
			{
				$cabecera[0]['Cliente']=$row['Cliente'];
				//preguntar
				//$cabecera[0]['Curso']=$row['Curso'];
				$cabecera[0]['DireccionC']=$row['Direccion'];
				$cabecera[0]['TelefonoC']=$row['Telefono'];
				$cabecera[0]['EmailR']=$row['Email2'];
				$cabecera[0]['EmailC']=$row['Email'];
				$cabecera[0]['Contacto']=$row['Contacto'];
				$cabecera[0]['Grupo']=$row['Grupo'];
			}
			if($cabecera[0]['formaPago']=='.')
			{
				//$formaPago='01';
				$cabecera[0]['formaPago']='01';
			}
			//$descuentoAdicional=0;
			$cabecera[0]['descuentoAdicional']=0;
			//$totalSinImpuestos=$Sin_IVA+$Con_IVA-$Descuento;
			//$cabecera[0]['totalSinImpuestos']=$cabecera[0]['Sin_IVA']+$cabecera[0]['Descuento'];
			//$moneda = "DOLAR";
			$cabecera[0]['moneda']="DOLAR";
			//$tipoIden='';
			$cabecera[0]['tipoIden']='';
			//
			$tipo_veri=digito_verificadorf($cabecera[0]['RUC_CI'],1);
			if($tipo_veri=='R')
			{
				//$tipoIden='04';
				$cabecera[0]['tipoIden']='04';
			}
			if($tipo_veri=='C')
			{
				//$tipoIden='05';
				$cabecera[0]['tipoIden']='05';
			}
			if($tipo_veri=='O')
			{
				//$tipoIden='06';
				$cabecera[0]['tipoIden']='06';
			}
			if($cabecera[0]['RUC_CI']=='9999999999999')
			{
				//$tipoIden='07';
				$cabecera[0]['tipoIden']='07';
			}
			//$codigoPorcentaje=0;
			$cabecera[0]['codigoPorcentaje']=0;
			if(($cabecera[0]['Porc_IVA']*100)>12)
			{
				//$codigoPorcentaje=3;
				$cabecera[0]['codigoPorcentaje']=3;
			}
			else
			{
				//$codigoPorcentaje=2;
				$cabecera[0]['codigoPorcentaje']=2;
			}
			//detalle de factura
			$stmt=seleccionSqlServer($conexion,'DF.*,CP.Reg_Sanitario,CP.Marca','Detalle_Factura As DF, Catalogo_Productos As CP ', " DF.Item = '".$cabecera[0]['item']."' 
			 AND DF.Periodo = '".$cabecera[0]['periodo']."' 
			 AND DF.TC = '".$cabecera[0]['tc']."' 
			 AND DF.Serie = '".$cabecera[0]['serie']."' 
			AND DF.Autorizacion = '".$cabecera[0]['Autorizacion']."' 
			AND DF.Factura = ".$cabecera[0]['factura']." 
			AND LEN(DF.Autorizacion) >= 13 
			AND DF.T <> 'A' 
			AND DF.Item = CP.Item 
			AND DF.Periodo = CP.Periodo 
			AND DF.Codigo = CP.Codigo_Inv 
			ORDER BY DF.ID,DF.Codigo ");
			$detalle=array();
			$i=0;
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
			{
				$detalle[$i]['Codigo']=$row['Codigo'];
				$Cod_Aux = '';
				$Cod_Bar = '';
				//buscamos si existe codigo
				$stmt1=seleccionSqlServer($conexion,'*','Catalogo_Productos ', " Item = '".$item."' 
					 AND Periodo = '".$periodo."' 
					 AND Codigo_Inv = '".$detalle[$i]['Codigo']."' ");
				while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_ASSOC) ) 
				{
					$Cod_Aux = $row1['Desc_Item'];
					$Cod_Bar = $row1['Codigo_Barra'];
				}
				$detalle[$i]['Cod_Aux'] = $Cod_Aux;
				$detalle[$i]['Cod_Bar'] = $Cod_Bar;
				$detalle[$i]['Producto'] = $row['Producto'];
				$detalle[$i]['Cantidad'] = $row['Cantidad'];
				$detalle[$i]['Precio'] = $row['Precio'];
				$detalle[$i]['descuento'] = $row['Total_Desc']+$row['Total_Desc2'];
				if ($cabecera[0]['Imp_Mes']==true)
				{
					$detalle[$i]['Producto'] = $row['Producto'].', '.$row['Ticket'].': '.$row['Mes'].' ';
				}
				if($cabecera[0]['SP']==true)
				{
					$detalle[$i]['Producto'] = $detalle[$i]['Producto'].', Lote No. '.$row['Lote_No'].
					', ELAB. '.$row['Fecha_Fab'].
					', VENC. '.$row['Fecha_Exp'].
					', Reg. Sanit. '.$row['Reg_Sanitario'].
					', Modelo: '.$row['Modelo'].
					', Procedencia: '.$row['Procedencia'];
				}
				$detalle[$i]['SubTotal'] = ($row['Cantidad']*$row['Precio'])-($row['Total_Desc']+$row['Total_Desc2']);
				$detalle[$i]['Serie_No'] = $row['Serie_No'];
				$detalle[$i]['Total_IVA'] = (float)$row['Total_IVA'];
				$detalle[$i]['Porc_IVA']= $row['Porc_IVA'];
				$i++;
			}
			$Fecha1 = explode("-", $cabecera[0]['Fecha']);
			$fechaem=$Fecha1[2].'/'.$Fecha1[1].'/'.$Fecha1[0];
			$cabecera[0]['fechaem']=$fechaem;
			/*echo ' tipo verificacion '.$cabecera[0]['tipoIden'];
			
			echo ' fecha emision '.$cabecera[0]['fechaem'].' cliente '.$cabecera[0]['Razon_Social'].' direc '.$cabecera[0]['Direccion_RS'].' sin iva '
			.$cabecera[0]['Sin_IVA'].' desc '.$cabecera[0]['Descuento'].' base impo '.$cabecera[0]['baseImponible'].' totalSinImpuestos '
			.$cabecera[0]['totalSinImpuestos'].' Con_IVA '.$cabecera[0]['Con_IVA'].' codigoPorcentaje '.$cabecera[0]['codigoPorcentaje']
			.' Porc_IVA '.$cabecera[0]['Porc_IVA'].' descuentoAdicional '.$cabecera[0]['descuentoAdicional'].' propina '.$cabecera[0]['Propina']
			.' Total_MN '.$cabecera[0]['Total_MN'].' moneda '.$cabecera[0]['moneda'].' formaPago '.$cabecera[0]['formaPago'];
			echo '<br>'.' Codigo producto '.$detalle[0]['Codigo'].' Cod_Aux '.$detalle[0]['Cod_Aux'].' Cod_Bar '.$detalle[0]['Cod_Bar'].
			' Producto '.$detalle[0]['Producto'].' Cantidad '.$detalle[0]['Cantidad'].' Precio '.$detalle[0]['Precio'].' descuento '.$detalle[0]['descuento'].
			' SubTotal '.$detalle[0]['SubTotal'].' Serie_No '.$detalle[0]['Serie_No'].' Total_IVA '.$detalle[0]['Total_IVA'];*/
			
			/*while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				for($i=0;$i<$cant;$i++)
				{
					if(is_object($row[$i])) {
						$row[$i]=$row[$i]->format('Y-m-d');
					}
					echo $row[$i].' -- ';
				}
			}*/
			//detalle
			//$cant=cantidadCamposSqlServer($conexion,'Detalle_Factura', " Serie='".$esta.$pto_e."' AND Factura='".$datos1[1]."' ");
			//$stmt=seleccionSqlServer($conexion,'*','Detalle_Factura', " Serie='".$esta.$pto_e."' AND Factura='".$datos1[1]."' ");
			/*
			4 VENTA A CONSUMIDOR FINAL* 07 Obligatorio
			5 IDENTIFICACION DELEXTERIOR* 08 Obligatorio
			
			<codigo>3</codigo > 
			Impuesto Código
			IVA 2
			ICE 3
			IRBPNR 5
			
			<codigoPorcentaje>2</ codigoPorcentaje>
			Porcentaje de IVA Código
			0% 0
			12% 2
			14% 3
			No Objeto de
			Impuesto 6
			Exento de IVA 7


			*/
			/*while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				for($i=0;$i<$cant;$i++)
				{
					if(is_object($row[$i])) {
						$row[$i]=$row[$i]->format('Y-m-d');
					}
					echo $row[$i].' -- ';
				}
			}*/
			/*
			
			$xml_totalDescuento = $xml->createElement( "totalDescuento",'0.00' );
			*/
			//$rows=seleccion($conexion,'eDoc_empresa');
			
			cerrarSQLSERVERFUN($conexion);
		}
	}
	$ban=generar_xml($cabecera,$detalle);
	//buscamos y modificamos
	$ban1 = explode("_", $ban);
	//0702164179001
	header('content-type text/html charset=utf-8');
	if( $ban1[1] == '1' )
	{
		if($Ti=='SQL SERVER')
		{
			$conexion=conexionSQL($IP, $Us, $Co, $Ba, $Pu);
			$sql ="UPDATE Facturas SET Autorizacion='".$ban1[0]."',Clave_Acceso='".$ban1[0]."' WHERE Item = '".$cabecera[0]['item']."' 
            AND Periodo = '".$cabecera[0]['periodo']."' 
			AND TC = '".$cabecera[0]['tc']."' 
			AND Serie = '".$cabecera[0]['serie']."' 
			AND Factura = ".$cabecera[0]['factura']." 
			AND LEN(Autorizacion) = 13 
			AND T <> 'A'; ";
			//echo $sql;
			$stmt = sqlsrv_query( $conexion, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$sql="UPDATE Detalle_Factura SET Autorizacion='".$ban1[0]."' WHERE Item = '".$cabecera[0]['item']."' 
			 AND Periodo = '".$cabecera[0]['periodo']."' 
			 AND TC = '".$cabecera[0]['tc']."' 
			 AND Serie = '".$cabecera[0]['serie']."' 
			AND Autorizacion = '".$cabecera[0]['Autorizacion']."' 
			AND Factura = ".$cabecera[0]['factura']." 
			AND LEN(Autorizacion) >= 13 
			AND T <> 'A'; ";
			//echo $sql;
			$stmt = sqlsrv_query( $conexion, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//modificamos trans abonos
			$sql="UPDATE Trans_Abonos SET Autorizacion='".$ban1[0]."',Clave_Acceso='".$ban1[0]."' WHERE Item = '".$cabecera[0]['item']."' 
			 AND Periodo = '".$cabecera[0]['periodo']."' 
			 AND TP = '".$cabecera[0]['tc']."' 
			 AND Serie = '".$cabecera[0]['serie']."' 
			AND Autorizacion = '".$cabecera[0]['Autorizacion']."' 
			AND Factura = ".$cabecera[0]['factura']." 
			AND LEN(Autorizacion) >= 13 
			AND T <> 'A'; ";
			$stmt = sqlsrv_query( $conexion, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//creamos trans_documentos
			//echo $ban1[2];
			$archivo = fopen($ban1[2],"rb");
			if( $archivo == false ) 
			{
				echo "Error al abrir el archivo";
			}
			else
			{
				rewind($archivo);   // Volvemos a situar el puntero al principio del archivo
				$cadena2 = fread($archivo, filesize($ban1[2]));  // Leemos hasta el final del archivo
				if( $cadena2 == false )
					echo "Error al leer el archivo";
				else
				{
					//echo "<p>\$contenido1 es: [".$cadena1."]</p>";
					//echo "<p>\$contenido2 es: [".$cadena2."]</p>";
				}
			}
			// Cerrar el archivo:
			fclose($archivo);
			$sql="INSERT INTO Trans_Documentos
		    (Item,Periodo,Clave_Acceso,Documento_Autorizado,TD,Serie,Documento,X)
			 VALUES
		    ('".$cabecera[0]['item']."' 
		    ,'".$cabecera[0]['periodo']."' 
		    ,'".$ban1[0]."'
		    ,'".$cadena2."'
		    ,'".$cabecera[0]['tc']."' 
		    ,'".$cabecera[0]['serie']."' 
		    ,".$cabecera[0]['factura']." 
			,'.');";
			//echo $sql;
			$stmt = sqlsrv_query( $conexion, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			/*
			"INSERT INTO Trans_Documentos
           (Item,Periodo,Clave_Acceso,Documento_Autorizado,TD,Serie,Documento,X)
			VALUES
           '".$cabecera[0]['item']."' 
           ,<Periodo, nvarchar(10),>
           ,<Clave_Acceso, nvarchar(49),>
           ,<Documento_Autorizado, ntext,>
           ,<TD, nvarchar(2),>
           ,<Serie, nvarchar(6),>
           ,<Documento, int,>
           ,<X, nvarchar(1),>);"
			*/
			cerrarSQLSERVERFUN($conexion);
		}
		echo '<link rel="stylesheet" href="lib/css/sweetalert.css">
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.10/dist/sweetalert2.all.min.js"></script>
			<link rel="stylesheet" href="lib/css/sweetalert2.min.css">';
		echo "<script>
				alert('se ha autorizado con exito el documento electronico');
				window.close();
			</script>";
		echo " existe ";
		//header('Location: '.'http://localhost/php/entidades/cerrar.php');
		header('Location: '.'cerrar.php?ban=1');
	}
	else
	{
		echo '
			<link rel="stylesheet" href="lib/css/sweetalert.css">
			<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.26.10/dist/sweetalert2.all.min.js"></script>
			<link rel="stylesheet" href="lib/css/sweetalert2.min.css">';
		echo "<script>
				alert('No se pudo autorizar el documento electronico');
				window.close();
			</script>";
		echo "no existe";
		header('Location: '.'cerrar.php?ban=0');
	}
	die();
}
//agregar ceros a cadena a la izquierda
function generaCeros($numero, $tamaño=null)
{
	 //obtengop el largo del numero
	 $largo_numero = strlen($numero);
	 //especifico el largo maximo de la cadena
	 if($tamaño==null)
	 {
		$largo_maximo = 7;
	 }
	 else
	 {
		 $largo_maximo = $tamaño;
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
function digito_verificador($cadena)
{
	$cadena=trim($cadena);
	$baseMultiplicador=7;
	$aux=new SplFixedArray(strlen($cadena));
	$aux=$aux->toArray();
	$multiplicador=2;
	$total=0;
	$verificador=0;
	for($i=count($aux)-1;$i>=0;--$i)
	{
		$aux[$i]= substr($cadena,$i,1);
		$aux[$i]*=$multiplicador;
		++$multiplicador;
		if($multiplicador>$baseMultiplicador)
		{
			$multiplicador=2;
		}
		$total+=$aux[$i];
	}
	if(($total==0)||($total==1)) $verificador=0;
	else
	{
		$verificador=(11-($total%11)==11)?0:11-($total%11);
	}
	if($verificador==10)
	{
		$verificador=1;
	}
	return$verificador;
}
/*function digito_verificador($cadena)
{
	$cadena=trim($cadena);
	$baseMultiplicador=7;
	$aux = new SplFixedArray(srtlen($cadena));
	$aux = $aux->toArray();
	$multiplicador=0;
	$verificador=0;
	$total=0;
	for($i=count($aux)-1;$i>=0;--$i)
	{
		$aux[$i]=substr($cadena,$i,1);
		$aux[$i]*=$multiplicador;
		++$multiplicador;
		if($multiplicador>$baseMultiplicador)
		{
			$multiplicador=2;
		}
		$total+=$aux[$i];
	}
}*/
//digito_verificadorf('1710034065');
function digito_verificadorf($ruc,$tipo=null,$pag=null,$idMen=null,$item=null)
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
							/*
							error caso 0802351031001
							echo $ruc.' ';
							echo $Dig3.' ';
							switch ($Dig3) 
							{
								case (($Dig3 <= 5) OR ($Dig3 >= 0)):
								{
									$TipoModulo = 10;
									$TipoModulo1=9;
									$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
									$VecDig = "212121212";
									echo " aquiii 1 ";
									break;
								}
								case ($Dig3 == 6 ):
								{
									$coe = array("3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=8;
									$VecDig = "32765432";
									echo " aquiii 2 ";
									break;
								}
								case ($Dig3 == 9):
								{
									$coe = array("4", "3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=9;
									$VecDig = "432765432";
									echo " aquiii 3 ";
									//echo " entro a nueve";
									break;
								}
								default:    
								{    
									$VecDig = "222222222";
									$TipoModulo1=9;
									echo " aquiii 4 ";
									$coe = array("2", "2", "2", "2", "2","2", "2", "2", "2");
								} 
							}*/
							//echo $VecDig.' ccc '.$TipoModulo.' nn ';
							//die();
							//realizamos productos
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
					else
					{
						if(strlen($ruc)==48 and is_numeric($ruc))
						{
							
						}
					}
					//echo $Tipo_Beneficiario;
				}
			}
			//$_SESSION['INGRESO']['item']
			//Si no es RUC/CI, procesamos el numero de codigo que le corresponde
			//echo ' www '.substr($ruc, 12, 1);
			if(substr($ruc, 12, 1)!='1')
			{
				$Tipo_Beneficiario = 'O';
			}
			
		}
	if($tipo==null OR $tipo==0)
	{	
		return $Digito_Verificador;
	}
	else
	{
		return $Tipo_Beneficiario;
	}
	//login('', '', '');
} 

//parametros clave de acceso
/*
1 Fecha de Emisión Numérico             ddmmaaaa       8 Obligatorio <claveAcceso> 
2 Tipo de Comprobante                   Tabla 3        2 
3 Número de RUC                         1234567890001  13 
4 Tipo de Ambiente                      Tabla 4        1 
5 Serie                                 001001         6 
6 Número del Comprobante (secuencial)   000000001      9 
7 Código Numérico                       Numérico       8 
8 Tipo de Emisión                       Tabla 2        1 
9 Dígito Verificador (módulo 11 )       Numérico       1
*/
function generar_xml($cabecera,$detalle)
{
	/*
	$cabecera[0]['ambiente']=$amb;
	$cabecera[0]['ruta_ce']=$ru_ce;
	$cabecera[0]['clave_ce']=$cl_ce;
	$cabecera[0]['nom_comercial_principal']=$nom_com;
	$cabecera[0]['razon_social_principal']=$raz_soc;
	$cabecera[0]['ruc_principal']=$ruc;
	$cabecera[0]['direccion_principal']=$dir;
	$cabecera[0]['serie']=$datos1[0];
	$cabecera[0]['factura']=$datos1[1];
	$cabecera[0]['esta']=$esta;
	$cabecera[0]['pto_e']=$pto_e;
	$cabecera[0]['cod_doc']=$cod_doc;
	$cabecera[0]['item']=$item;
	$cabecera[0]['tc']=$tc;
	$cabecera[0]['periodo']=$periodo;
	$cabecera[0]['RUC_CI']=$row['RUC_CI'];
	$cabecera[0]['Fecha']=$row['Fecha']->format('Y-m-d');
	$cabecera[0]['fechaem']
	$cabecera[0]['IVA']
	$cabecera[0]['Razon_Social']=$row['Razon_Social'];
	$cabecera[0]['Direccion_RS']=$row['Direccion_RS'];
	$cabecera[0]['Sin_IVA']=(float)$row['Sin_IVA'];
	$cabecera[0]['Descuento']=(float)$row['Descuento']+$row['Descuento2'];
	$cabecera[0]['baseImponible']=(float)($row['Sin_IVA']+$cabecera[0]['Descuento']);
	$cabecera[0]['Porc_IVA']=round((float)$row['Porc_IVA'],2);
	$cabecera[0]['Con_IVA']=(float)$row['Con_IVA'];
	$cabecera[0]['Total_MN']=(float)$row['Total_MN'];
	$cabecera[0]['formaPago']=$row['Forma_Pago'];
	$cabecera[0]['Propina']=$row['Propina'];
	$cabecera[0]['Autorizacion']=$row['Autorizacion'];
	$cabecera[0]['Imp_Mes']=$row['Imp_Mes'];
	$cabecera[0]['SP']=$row['SP'];
	$cabecera[0]['Total_MN']
	$cabecera[0]['CodigoC']=$row['CodigoC'];
	$cabecera[0]['TelefonoC']=$row['Telefono_RS'];
	$cabecera[0]['Orden_Compra']=$row['Orden_Compra'];
	$cabecera[0]['Cliente']=$row['Cliente'];
	//preguntar
	//$cabecera[0]['Curso']=$row['Curso'];
	$cabecera[0]['DireccionC']=$row['Direccion'];
	$cabecera[0]['TelefonoC']=$row['Telefono'];
	$cabecera[0]['EmailR']=$row['Email2'];
	$cabecera[0]['Contacto']=$row['Contacto'];
	$cabecera[0]['formaPago']='01';
	$cabecera[0]['descuentoAdicional']=0;
	$cabecera[0]['baseImponibleSinIva'];
	$cabecera[0]['baseImponibleConIva'];
	$cabecera[0]['totalSinImpuestos']
	$cabecera[0]['moneda']="DOLAR";
	$cabecera[0]['tipoIden'];
	$cabecera[0]['codigoPorcentaje'];
	$cabecera[0]['Grupo']
	$cabecera[0]['EmailC']
	
	$detalle[$i]['Cod_Aux'] = $Cod_Aux;
	$detalle[$i]['Cod_Bar'] = $Cod_Bar;
	$detalle[$i]['Producto'] = $row['Producto'];
	$detalle[$i]['Cantidad'] = $row['Cantidad'];
	$detalle[$i]['Precio'] = $row['Precio'];
	$detalle[$i]['descuento'] = $row['Total_Desc']+$row['Total_Desc2'];
	$detalle[$i]['SubTotal'] = ($row['Cantidad']*$row['Precio'])-($row['Total_Desc']+$row['Total_Desc2']);
	$detalle[$i]['Serie_No'] = $row['Serie_No'];
	$detalle[$i]['Total_IVA'] = $row['Total_IVA'];	
	$detalle[$i]['Codigo']
	$detalle[$i]['Porc_IVA']
	*/
	$entidad='001';
	$empresa=$cabecera[0]['item'];
	$Fecha1 = explode("/", $cabecera[0]['fechaem']);
	$fecha = $Fecha1[0].$Fecha1[1].$Fecha1[2];
	$ruc=$cabecera[0]['ruc_principal'];
	//$tc=$cabecera[0]['tc'];
	$tc=$cabecera[0]['cod_doc'];
	$serie=$cabecera[0]['serie'];
	$numero=$cabecera[0]['factura'];
	$numero=generaCeros($numero, '9');
	$emi='1';
	$nume='12345678';
	$ambiente=$cabecera[0]['ambiente'];
	$codDoc=$cabecera[0]['cod_doc'];
	/*$fecha = date('dm').date('Y');
	//$fecha = '11082020';
	$ruc='1792164710001';
	$tc='01';
	$serie='001003';
	$numero='502';
	$numero=generaCeros($numero, '9');
	$emi='1';
	$nume='23456781';
	$nume='12345678';
	$ambiente=$cabecera[0]['ambiente'];
	$codDoc='01';*/
	//produccion
	//0308202001179216471000120010050000009361234567819
	//prueba
	//0308202001179216471000110010030000005011234567819
	//1108202001179216471000110010030000005011234567813
	$dig=digito_verificadorf($ruc);

	//echo $dig;
	//die();
	$compro=$fecha.$tc.$ruc.$ambiente.$serie.$numero.$nume.$emi;
	$dig=digito_verificador($compro);
	//echo ' nn '.$dig;
	//die();
	$compro=$fecha.$tc.$ruc.'1'.$serie.$numero.$nume.$emi.$dig;
	$nombre_archivo = "entidad".$entidad."/CE".$empresa."/autorizados/".$compro.".xml";
	header('content-type text/html charset=utf-8');		
	if( file_exists(dirname(__DIR__)."/entidades/".$nombre_archivo) == true )
	{
		//echo " dfdffdsfdsfsd ";
		//return $compro.'_1_'.$nombre_archivo;
		header('Location: '.$_SERVER['HTTP_HOST'].'/php/entidades/cerrar.php?ban=3');
		die();
	}
	else
	{
		//return $compro.'_0_'.$nombre_archivo;
	}
	//echo $compro;
	/*echo "java -jar ".dirname(__DIR__)."/entidades/firmador.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/generados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/certificados/ ".$cabecera[0]['ruta_ce']." ".$cabecera[0]['clave_ce']."";
	exec("java -jar ".dirname(__DIR__)."/entidades/firmador.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/generados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/certificados/ ".$cabecera[0]['ruta_ce']." ".$cabecera[0]['clave_ce']."", $o);
	print_r($o);	
	die();*/
	/*		exec("java -jar ".dirname(__DIR__)."/entidades/QuijoteLuiClient-1.2.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/no_autorizados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/autorizados ".$compro."", $o);
	print_r($o);*/
	//die();
	/*exec("java -jar ".dirname(__DIR__)."/entidades/QuijoteLuiClient-1.2.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/no_autorizados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/autorizados ".$compro."", $o);
	print_r($o);*/
	//echo dirname(__DIR__).' -- ';
	//echo dirname(__DIR__).'/entidades/';
	//die();
	//echo ' '.$compro;
	//die();
	/*exec("java -jar C:\\wamp64\\www\\php\\entidades\\QuijoteLuiFirmador-master\\QuijoteLuiFirmador-master\\dist\\QuijoteLuiFirmador-1.4.jar ".$compro.".xml C:/wamp64/www/php/entidades/entidad01/empresa001/generados/ C:/wamp64/www/php/entidades/entidad01/empresa001/firmados C:/wamp64/www/php/entidades/entidad01/empresa001/ walter_jalil_vaca_prieto.p12 Dlcjvl1210", $o);
	print_r($o);
	java -jar C:\wamp64\www\php/entidades/QuijoteLuiFirmador-1.4.jar 2109202001070216417900110010030000005061234567814.xml C:\wamp64\www\php/entidades/entidad001/CE001/generados/ C:\wamp64\www\php/entidades/entidad001/CE001/firmados C:\wamp64\www\php/entidades/entidad001/certificados/ walter_jalil_vaca_prieto_natural_2020_09_10.p12 Dlcjvl1210
	java -jar C:\wamp64\www\php/entidades/firmador.jar 2109202001070216417900110010030000005061234567814.xml C:\wamp64\www\php/entidades/entidad001/CE001/generados/ C:\wamp64\www\php/entidades/entidad001/CE001/firmados C:\wamp64\www\php/entidades/entidad001/certificados/ walter_jalil_vaca_prieto_natural_2020_09_10.p12 Dlcjvl1210
	SELECT        Item, Empresa, RUC, Razon_Social, Nombre_Comercial, Ruta_Certificado, Clave_Certificado, CI_Representante, TD, RUC_Contador, 
	Ambiente, Codigo_Contribuyente_Especial, Email_Conexion_CE, Gerente, Obligado_Conta
	FROM            Empresas
	die();
	$last_line = shell_exec("C:\\Program Files\\Java\\jdk1.8.0_111\\bin\\java.exe -jar C:\\wamp64\\www\\php\\entidades\\QuijoteLuiFirmador-master\\QuijoteLuiFirmador-master\\dist\\QuijoteLuiFirmador-1.4.jar");*/

	/*$handle = popen("C:\Program Files\Java\jdk1.8.0_111\bin\java.exe -jar C:\wamp64\www\php\entidades\QuijoteLuiFirmador-master\QuijoteLuiFirmador-master\dist\QuijoteLuiFirmador-1.4.jar", "r");
	//You can read with $read = fread($handle, 2096)
	pclose($handle);*/

	//echo $fecha.$tc.$ruc.$dig.$serie.$numero.$emi.$nume.'9';
	//die();
	/*for($i=0;$i<count($detalle);$i++)
	{
		echo $detalle[$i]['Cod_Bar'];
		
		if($cabecera[0]['SP']==true)
		{
			if(strlen($detalle[$i]['Cod_Bar'])>1)
			{
				echo $detalle[$i]['Cod_Bar'];
			}
			if(strlen($detalle[$i]['Cod_Aux'])>1)
			{
				echo $detalle[$i]['Cod_Aux'];
			}
			else
			{
				echo $detalle[$i]['Codigo'];
			}
		}
		else
		{
			if(strlen($detalle[$i]['Cod_Aux'])>1)
			{
				echo $detalle[$i]['Cod_Aux'];
			}
			else
			{
				echo $detalle[$i]['Codigo'];
			}
			if(strlen($detalle[$i]['Cod_Bar'])>1)
			{
				echo $detalle[$i]['Cod_Bar'];
			}
		}	
		echo $detalle[$i]['Producto'];
		echo $cabecera[0]['moneda'];
		echo $detalle[$i]['Cantidad'];
		echo $detalle[$i]['Precio'];
		echo $detalle[$i]['descuento'];
		echo $detalle[$i]['SubTotal'];
		if(strlen($detalle[$i]['Serie_No'])>1)
		{
			echo $detalle[$i]['Serie_No'];
		}
		
		if($detalle[$i]['Total_IVA'] == 0)
		{
			
		}
		else
		{
			if(($detalle[$i]['Porc_IVA']*100) > 12)
			{
				
			}
			else
			{
				
			}
			echo ($detalle[$i]['Porc_IVA']*100);
			echo$detalle[$i]['SubTotal'];
			echo $detalle[$i]['Total_IVA'];
		}
	}*/
	//header( "content-type: application/xml; charset=UTF-8" );

	// "Create" the document.
	$xml = new DOMDocument( "1.0", "UTF-8" );
	$xml->preserveWhiteSpace = false; 

	// Create some elements.
	if($codDoc=='01')
	{
		$xml_factura = $xml->createElement( "factura" );
	}
	if($codDoc=='07')
	{
		$xml_factura = $xml->createElement( "comprobanteRetencion" );
	}
	if($codDoc=='03')
	{
		$xml_factura = $xml->createElement( "factura" );
	}
	if($codDoc=='04')
	{
		$xml_factura = $xml->createElement( "notaCredito" );
	}
	if($codDoc=='05')
	{
		$xml_factura = $xml->createElement( "notaDebito" );
	}
	if($codDoc=='06')
	{
		$xml_factura = $xml->createElement( "guiaRemision" );
	}
	//$xml_factura=$xml_factura.'\n';
	$xml_factura->setAttribute( "id", "comprobante" );
	$xml_factura->setAttribute( "version", "1.1.0" );
	$xml_infoTributaria = $xml->createElement( "infoTributaria" );
	$xml_ambiente = $xml->createElement( "ambiente",$ambiente );
	$xml_tipoEmision = $xml->createElement( "tipoEmision",'1' );
	$xml_razonSocial = $xml->createElement( "razonSocial",$cabecera[0]['razon_social_principal']);
	$xml_nombreComercial = $xml->createElement( "nombreComercial",$cabecera[0]['nom_comercial_principal'] );
	$xml_ruc = $xml->createElement( "ruc",$cabecera[0]['ruc_principal'] );
	$xml_claveAcceso = $xml->createElement( "claveAcceso",$compro);
	$xml_codDoc = $xml->createElement( "codDoc",$codDoc );
	$xml_estab = $xml->createElement( "estab",$cabecera[0]['esta'] );
	$xml_ptoEmi = $xml->createElement( "ptoEmi",$cabecera[0]['pto_e'] );
	$xml_secuencial = $xml->createElement( "secuencial",$numero );
	$xml_dirMatriz = $xml->createElement( "dirMatriz",$cabecera[0]['direccion_principal'] );

	$xml_infoTributaria->appendChild( $xml_ambiente );
	$xml_infoTributaria->appendChild( $xml_tipoEmision );
	$xml_infoTributaria->appendChild( $xml_razonSocial );
	$xml_infoTributaria->appendChild( $xml_nombreComercial );
	$xml_infoTributaria->appendChild( $xml_ruc );
	$xml_infoTributaria->appendChild( $xml_claveAcceso );
	$xml_infoTributaria->appendChild( $xml_codDoc );
	$xml_infoTributaria->appendChild( $xml_estab );
	$xml_infoTributaria->appendChild( $xml_ptoEmi );
	$xml_infoTributaria->appendChild( $xml_secuencial );
	$xml_infoTributaria->appendChild( $xml_dirMatriz );

	$xml_infoFactura = $xml->createElement( "infoFactura" );

	$xml_fechaEmision = $xml->createElement( "fechaEmision",$cabecera[0]['fechaem'] );
	if($cabecera[0]['Direccion_RS']=='.')
	{
		$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera[0]['direccion_principal']);
	}
	else
	{
		$xml_dirEstablecimiento = $xml->createElement( "dirEstablecimiento",$cabecera[0]['Direccion_RS'] );
	}
	$xml_obligadoContabilidad = $xml->createElement( "obligadoContabilidad",'SI' );
	$xml_tipoIdentificacionComprador = $xml->createElement( "tipoIdentificacionComprador",$cabecera[0]['tipoIden'] );
	$xml_razonSocialComprador = $xml->createElement( "razonSocialComprador",$cabecera[0]['Razon_Social'] );
	$xml_identificacionComprador = $xml->createElement( "identificacionComprador",$cabecera[0]['RUC_CI'] );
	$xml_totalSinImpuestos = $xml->createElement( "totalSinImpuestos",round($cabecera[0]['totalSinImpuestos'],2) );
	$xml_totalDescuento = $xml->createElement( "totalDescuento",round($cabecera[0]['Descuento'],2) );

	$xml_infoFactura->appendChild( $xml_fechaEmision );
	$xml_infoFactura->appendChild( $xml_dirEstablecimiento );
	$xml_infoFactura->appendChild( $xml_obligadoContabilidad );
	$xml_infoFactura->appendChild( $xml_tipoIdentificacionComprador );
	$xml_infoFactura->appendChild( $xml_razonSocialComprador );
	$xml_infoFactura->appendChild( $xml_identificacionComprador );
	$xml_infoFactura->appendChild( $xml_totalSinImpuestos );
	$xml_infoFactura->appendChild( $xml_totalDescuento );

	$xml_totalConImpuestos = $xml->createElement( "totalConImpuestos" );
	//sin iva
	$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
	$xml_codigo = $xml->createElement( "codigo",'2' );
	$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0'  );
	$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",round($cabecera[0]['descuentoAdicional'],2) );
	$xml_baseImponible = $xml->createElement( "baseImponible",round($cabecera[0]['baseImponibleSinIva'],2) );
	//$xml_tarifa = $xml->createElement( "tarifa",'0.00' );
	$xml_valor = $xml->createElement( "valor",'0.00' );
	
	$xml_totalImpuesto->appendChild( $xml_codigo );
	$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
	$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
	$xml_totalImpuesto->appendChild( $xml_baseImponible );
	//$xml_totalImpuesto->appendChild( $xml_tarifa );
	$xml_totalImpuesto->appendChild( $xml_valor );
	$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
	if(($cabecera[0]['Con_IVA']) > 0)
	{
		$xml_totalImpuesto = $xml->createElement( "totalImpuesto" );
		$xml_codigo = $xml->createElement( "codigo",'2' );
		$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",$cabecera[0]['codigoPorcentaje'] );
		$xml_descuentoAdicional = $xml->createElement( "descuentoAdicional",round($cabecera[0]['descuentoAdicional'],2) );
		$xml_baseImponible = $xml->createElement( "baseImponible",round($cabecera[0]['baseImponibleConIva'],2) );
		$xml_tarifa = $xml->createElement( "tarifa",round(($cabecera[0]['Porc_IVA']*100),2) );
		$xml_valor = $xml->createElement( "valor",round($cabecera[0]['IVA'],2) );
		
		$xml_totalImpuesto->appendChild( $xml_codigo );
		$xml_totalImpuesto->appendChild( $xml_codigoPorcentaje );
		$xml_totalImpuesto->appendChild( $xml_descuentoAdicional );
		$xml_totalImpuesto->appendChild( $xml_baseImponible );
		$xml_totalImpuesto->appendChild( $xml_tarifa );
		$xml_totalImpuesto->appendChild( $xml_valor );
		
		$xml_totalConImpuestos->appendChild( $xml_totalImpuesto );
	}
	$xml_infoFactura->appendChild( $xml_totalConImpuestos );

	$xml_propina = $xml->createElement( "propina",round($cabecera[0]['Propina'],2) );
	$xml_importeTotal = $xml->createElement( "importeTotal",round($cabecera[0]['Total_MN'],2) );
	$xml_moneda = $xml->createElement( "moneda",$cabecera[0]['moneda'] );

	$xml_infoFactura->appendChild( $xml_propina );
	$xml_infoFactura->appendChild( $xml_importeTotal );
	$xml_infoFactura->appendChild( $xml_moneda );

	$xml_detalles = $xml->createElement( "detalles");
	for($i=0;$i<count($detalle);$i++)
	{
		//echo $detalle[$i]['Cod_Bar'];
		if($detalle[$i]['Cod_Bar']!='' OR $detalle[$i]['Codigo']!='')
		{
			$xml_detalle = $xml->createElement( "detalle" );
			if($cabecera[0]['SP']==true)
			{
				if(strlen($detalle[$i]['Cod_Bar'])>1)
				{
					$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$detalle[$i]['Cod_Bar'] );
				}
				$xml_detalle->appendChild( $xml_codigoPrincipal );
				if(strlen($detalle[$i]['Cod_Aux'])>1)
				{
					$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$detalle[$i]['Cod_Aux'] );
				}
				else
				{
					$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$detalle[$i]['Codigo'] );
				}
				$xml_detalle->appendChild( $xml_codigoAuxiliar );
			}
			else
			{
				if(strlen($detalle[$i]['Cod_Aux'])>1)
				{
					$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$detalle[$i]['Cod_Aux'] );
				}
				else
				{
					$xml_codigoPrincipal = $xml->createElement( "codigoPrincipal",$detalle[$i]['Codigo'] );
				}
				$xml_detalle->appendChild( $xml_codigoPrincipal );
				if(strlen($detalle[$i]['Cod_Bar'])>1)
				{
					$xml_codigoAuxiliar = $xml->createElement( "codigoAuxiliar",$detalle[$i]['Cod_Bar'] );
					$xml_detalle->appendChild( $xml_codigoAuxiliar );
				}
			}
					
			$xml_descripcion = $xml->createElement( "descripcion",$detalle[$i]['Producto'] );
			$xml_unidadMedida = $xml->createElement( "unidadMedida",$cabecera[0]['moneda'] );
			$xml_cantidad = $xml->createElement( "cantidad",$detalle[$i]['Cantidad'] );
			$xml_precioUnitario = $xml->createElement( "precioUnitario",round($detalle[$i]['Precio'],2) );
			$xml_descuento = $xml->createElement( "descuento",round($detalle[$i]['descuento'],2) );
			$xml_precioTotalSinImpuesto = $xml->createElement( "precioTotalSinImpuesto",round($detalle[$i]['SubTotal'],2) );
			
			$xml_detalle->appendChild( $xml_codigoPrincipal );
			
			$xml_detalle->appendChild( $xml_descripcion );
			$xml_detalle->appendChild( $xml_unidadMedida );
			$xml_detalle->appendChild( $xml_cantidad );
			$xml_detalle->appendChild( $xml_precioUnitario );
			$xml_detalle->appendChild( $xml_descuento );
			$xml_detalle->appendChild( $xml_precioTotalSinImpuesto );
			if(strlen($detalle[$i]['Serie_No'])>1)
			{
				$detallesAdicionales = $xml->createElement( "detallesAdicionales" );
				$detAdicional = $xml->createElement( "detAdicional" );
				$detAdicional->setAttribute( "nombre", "Serie_No" );
				$detAdicional->setAttribute( "valor", $detalle[$i]['Serie_No'] );
				$detallesAdicionales->appendChild( $detAdicional );
				$xml_detalle->appendChild( $detallesAdicionales );
			}
			$xml_impuestos = $xml->createElement( "impuestos" );
			$xml_impuesto = $xml->createElement( "impuesto" );
			$xml_codigo = $xml->createElement( "codigo",'2' );
			if($detalle[$i]['Total_IVA'] == 0)
			{
				$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'0' );
				$xml_tarifa = $xml->createElement( "tarifa",'0' );
			}
			else
			{
				if(($detalle[$i]['Porc_IVA']*100) > 12)
				{
					$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'3' );
				}
				else
				{
					$xml_codigoPorcentaje = $xml->createElement( "codigoPorcentaje",'2' );
				}
				$xml_tarifa = $xml->createElement( "tarifa",round(($detalle[$i]['Porc_IVA']*100),2) );
				
			}
			$xml_baseImponible = $xml->createElement( "baseImponible",round($detalle[$i]['SubTotal'],2) );
			$xml_valor = $xml->createElement( "valor",round($detalle[$i]['Total_IVA'],2)  );
			$xml_impuesto->appendChild( $xml_codigo );
			$xml_impuesto->appendChild( $xml_codigoPorcentaje );
			$xml_impuesto->appendChild( $xml_tarifa );
			$xml_impuesto->appendChild( $xml_baseImponible );
			$xml_impuesto->appendChild( $xml_valor );
		
			$xml_impuestos->appendChild( $xml_impuesto );
			$xml_detalle->appendChild( $xml_impuestos );
			$xml_detalles->appendChild( $xml_detalle );
		}
	}
	$xml_infoAdicional = $xml->createElement( "infoAdicional");
	//agregar informacion por default
		$xml_campoAdicional = $xml->createElement( "campoAdicional",'.' );
		$xml_campoAdicional->setAttribute( "nombre", "adi" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	if($cabecera[0]['Cliente']<>'.' AND ($cabecera[0]['Cliente']!=$cabecera[0]['Razon_Social']))
	{
		if(strlen($cabecera[0]['Cliente'])>1)
		{
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera[0]['Cliente'] );
			$xml_campoAdicional->setAttribute( "nombre", "Beneficiario" );
			$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera[0]['Grupo'] );
			$xml_campoAdicional->setAttribute( "nombre", "Ubicacion" );
			$xml_infoAdicional->appendChild( $xml_campoAdicional );
		}
	}
	if(strlen($cabecera[0]['DireccionC'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera[0]['DireccionC'] );
		$xml_campoAdicional->setAttribute( "nombre", "Direccion" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera[0]['TelefonoC'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera[0]['TelefonoC'] );
		$xml_campoAdicional->setAttribute( "nombre", "Telefono" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera[0]['EmailC'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera[0]['EmailC'] );
		$xml_campoAdicional->setAttribute( "nombre", "Email" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera[0]['EmailR'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera[0]['EmailR'] );
		$xml_campoAdicional->setAttribute( "nombre", "Email2" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera[0]['Contacto'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera[0]['Contacto'] );
		$xml_campoAdicional->setAttribute( "nombre", "Referencia" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	if(strlen($cabecera[0]['Orden_Compra'])>1)
	{
		$xml_campoAdicional = $xml->createElement( "campoAdicional",$cabecera[0]['Orden_Compra'] );
		$xml_campoAdicional->setAttribute( "nombre", "ordenCompra" );
		$xml_infoAdicional->appendChild( $xml_campoAdicional );
	}
	/*$xml_campoAdicional = $xml->createElement( "campoAdicional",'LA PRADERA' );
	$xml_campoAdicional->setAttribute( "nombre", "Direccion" );
	$xml_infoAdicional->appendChild( $xml_campoAdicional );

	$xml_campoAdicional = $xml->createElement( "campoAdicional",'002020408' );
	$xml_campoAdicional->setAttribute( "nombre", "Telefono" );
	$xml_infoAdicional->appendChild( $xml_campoAdicional );

	$xml_campoAdicional = $xml->createElement( "campoAdicional",'guevarabolivar@hotmail.com' );
	$xml_campoAdicional->setAttribute( "nombre", "Email" );
	$xml_infoAdicional->appendChild( $xml_campoAdicional );*/

	/*$xml_track = $xml->createElement( "Track", "The ninth symphony" );

	// Set the attributes.
	$xml_track->setAttribute( "length", "0:01:15" );
	$xml_track->setAttribute( "bitrate", "64kb/s" );
	$xml_track->setAttribute( "channels", "2" );

	// Create another element, just to show you can add any (realistic to computer) number of sublevels.
	$xml_note = $xml->createElement( "Note", "The last symphony composed by Ludwig van Beethoven." );

	// Append the whole bunch.
	$xml_track->appendChild( $xml_note );
	$xml_track = $xml->createElement( "Track", "Highway Blues" );

	$xml_track->setAttribute( "length", "0:01:33" );
	$xml_track->setAttribute( "bitrate", "64kb/s" );
	$xml_track->setAttribute( "channels", "2" );*/

	$xml_factura->appendChild( $xml_infoTributaria );
	$xml_factura->appendChild( $xml_infoFactura );
	$xml_factura->appendChild( $xml_detalles );
	$xml_factura->appendChild( $xml_infoAdicional );


	$xml->appendChild( $xml_factura );

	// Parse the XML.
	//print $xml->saveXML();
	$nombre_archivo = "entidad".$entidad."/CE".$empresa."/generados/".$compro.".xml"; 
	if( file_exists(dirname(__DIR__)."/entidades/entidad".$entidad) == true )
	{
		//echo "<p>El archivo existe</p>";
		if( file_exists("CE".$empresa) == true )
		{
			//echo "<p>El archivo existe</p>";
		}
		else
		{
			mkdir("entidad".$entidad."/certificados", 0777);
			mkdir("entidad".$entidad."/CE".$empresa, 0777);
			mkdir("entidad".$entidad."/CE".$empresa."/generados", 0777);
			mkdir("entidad".$entidad."/CE".$empresa."/no_autorizados", 0777);
			mkdir("entidad".$entidad."/CE".$empresa."/firmados", 0777);
			mkdir("entidad".$entidad."/CE".$empresa."/autorizados", 0777);
		}				
	}
	else
	{
		mkdir("entidad".$entidad, 0777);
		mkdir("entidad".$entidad."/certificados", 0777);
		mkdir("entidad".$entidad."/CE".$empresa, 0777);
		mkdir("entidad".$entidad."/CE".$empresa."/generados", 0777);
		mkdir("entidad".$entidad."/CE".$empresa."/no_autorizados", 0777);
		mkdir("entidad".$entidad."/CE".$empresa."/firmados", 0777);
		mkdir("entidad".$entidad."/CE".$empresa."/autorizados", 0777);
	}
	
	if($archivo = fopen($nombre_archivo, "w+b"))
	{
		
		if(fwrite($archivo, $xml->saveXML()))
		{
			//echo "Se ha ejecutado correctamente";
			//para ejecutar java
			/*exec("/app/jdk1.8.0_181/bin/java -Dfile.encoding=UTF-8 -jar /data/git/QuijoteLuiFirmador/dist/QuijoteLuiFirmador-1.3.jar 0401201901100645687700120011020000000151234567810.xml", $o); print_r($o);*/
			/*parametros 
			1 nombre documento 
			2 ruta del archivo 
			3 ruta de firmados 
			4 ruta de p12 
			5 archivo p12 
			6 clave*/
			exec("java -jar ".dirname(__DIR__)."/entidades/firmador.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/generados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/certificados/ ".$cabecera[0]['ruta_ce']." ".$cabecera[0]['clave_ce']."", $o);
			
			exec("java -jar ".dirname(__DIR__)."/entidades/QuijoteLuiClient-1.2.jar ".$compro.".xml".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/firmados/ ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/no_autorizados".
			" ".dirname(__DIR__)."/entidades/entidad".$entidad."/CE".$empresa."/autorizados ".$compro."", $o);
			
			fclose($archivo);
			//verificamos si esta autorizado
		}
		else
		{
			fclose($archivo);
			echo "Ha habido un problema al crear el archivo";
		}
	}
	$nombre_archivo = "entidad".$entidad."/CE".$empresa."/autorizados/".$compro.".xml";
	header('content-type text/html charset=utf-8');		
	if( file_exists(dirname(__DIR__)."/entidades/".$nombre_archivo) == true )
	{
		return $compro.'_1_'.$nombre_archivo;
	}
	else
	{
		return $compro.'_0_'.$nombre_archivo;
	}
}
//http://www.formacionwebonline.com/generar-xml-en-php-con-xmlwriter/
?>

