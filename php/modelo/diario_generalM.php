<?php
include(dirname(__DIR__).'/funciones/funciones.php');//
include(dirname(__DIR__).'/db/variables_globales.php');//
@session_start(); 

/**
 * 
 */
class diario_generalM
{
	private $conn;
	
	function __construct()
	{
		$this->conn = cone_ajax();
	}

  function llenar_agencia()
  {
  	$cid = $this->conn;
  	$sql= "SELECT (Sucursal +'  ' + Empresa) As NomEmpresa,Sucursal as 'Item'
FROM Acceso_Sucursales
INNER JOIN Empresas ON Acceso_Sucursales.Sucursal = Empresas.Item
WHERE Acceso_Sucursales.Item ='".$_SESSION['INGRESO']['item']."'
ORDER BY Acceso_Sucursales.Item,Empresa";

        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$result[] = $row;
	   }

  //cerrarSQLSERVERFUN($cid);
	   return $result;

  }
  function llenar_usuario()
  {
  	$cid = $this->conn;
  	
  	$sql = "SELECT (Nombre_Completo +'  '+ Codigo) As CodUsuario,Codigo
       FROM Accesos 
       WHERE Codigo <> '*' 
       ORDER BY Nombre_Completo ";
       $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$result[] = $row;
	   }

  cerrarSQLSERVERFUN($cid);
	   return $result;
  	
  }

  function cargar_consulta_libro_tabla($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
	{
		$cid = $this->conn;


		$sql = "SELECT T.Fecha,T.TP,T.Numero,CL.Cliente As Beneficiario,Co.Concepto,T.Cta,C.Cuenta,
       T.Parcial_ME,T.Debe,T.Haber,T.Detalle,Ac.Nombre_Completo,Co.CodigoU,Co.Autorizado,T.Item,T.ID 
       FROM Transacciones As T,Catalogo_Cuentas As C,Comprobantes As Co,Clientes As CL,Accesos As Ac 
       WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
		/*
		 
  sSQL = "SELECT T.Fecha,T.TP,T.Numero,CL.Cliente As Beneficiario,Co.Concepto,T.Cta,C.Cuenta," _
       & "T.Parcial_ME,T.Debe,T.Haber,T.Detalle,Ac.Nombre_Completo,Co.CodigoU,Co.Autorizado,T.Item,T.ID " _
       & "FROM Transacciones As T,Catalogo_Cuentas As C,Comprobantes As Co,Clientes As CL,Accesos As Ac " _
       & "WHERE T.Fecha BETWEEN #" & FechaIni & "# and #" & FechaFin & "# " _
       & "AND T.Periodo = '" & Periodo_Contable & "' "
  If OpcCI.value Then  sSQL = sSQL & "AND T.TP = '" & CompIngreso & "' "*/
  if($OpcCI=='true')
  {
     $sql.="AND T.TP = '".G_COMPINGRESO."'";
  }
 // ElseIf OpcCE.value Then
   //  sSQL = sSQL & "AND T.TP = '" & CompEgreso & "' "
  if($OpcCE=='true')
  {
  	$sql.=  "AND T.TP = '".G_COMPEGRESO."' ";
  }
 /*  ElseIf OpcCD.value Then
     sSQL = sSQL & "AND T.TP = '" & CompDiario & "' "*/
  if($OpcCD=='true')
  {
  	 $sql.="AND T.TP = '".G_COMPDIARIO."' "; 	
  }
 /* ElseIf OpcND.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaDebito & "' "*/
  if($OpcND=='true')
  {
  	 $sql.="AND T.TP = '".G_COMPNOTADEBITO."' ";
  }
   /*ElseIf OpcNC.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaCredito & "' "
  End If*/
  if($OpcNC=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTACREDITO."' ";  	
  }
  /*If OpcA.value Then
     sSQL = sSQL & "AND T.T = '" & Anulado & "' "
  Else
     sSQL = sSQL & "AND T.T = '" & Normal & "' "
  End If*/
  if($OpcA=='true')
  {
  	$sql.="AND T.T = '".G_ANULADO."' ";  	
  }else
  {
  	 $sql.="AND T.T = '".G_NORMAL."' ";
  }

 /* 
  If CheckAgencia.value = 1 Then
     sSQL = sSQL & "AND Co.Item = '" & SinEspaciosIzq(DCAgencia.Text) & "' "
  Else
     If Not ConSucursal Then sSQL = sSQL & "AND Co.Item = '" & NumEmpresa & "' "
  End If
  */
  if($CheckAgencia=='true')
  {
  	if($DCAgencia=='')
  	{  		
  		$sql.="AND Co.Item = '".$_SESSION['INGRESO']['item']."' ";
  	}else
  	{
  		$sql.= "AND Co.Item = '".$DCAgencia."' ";
  	}

  }else
  {
    $sql.="AND Co.Item = '".$_SESSION['INGRESO']['item']."' ";
  }
  /*
  If CheckUsuario.value = 1 Then sSQL = sSQL & "AND Co.CodigoU = '" & SinEspaciosDer(DCUsuario.Text) & "' "

  */
  if($CheckUsuario=='true')
  {
  	$sql.= "AND Co.CodigoU = '".$DCUsuario."' ";
  }
  /*
  If CheckNum.value = 1 Then sSQL = sSQL & "AND Co.Numero BETWEEN " & CLng(TextNumNo.Text) & " and " & CLng(TextNumNo1.Text) & " "
  */
  if($CheckNum=='true')
  {  	
  	$sql.= "AND Co.Numero BETWEEN ".$TextNumNo." and ".$TextNumNo1." ";
  }
  $sql.= "AND T.Item = Co.Item 
       AND T.Item = C.Item
       AND C.Item = Co.Item
       AND T.Periodo = C.Periodo
       AND T.Periodo = Co.Periodo 
       AND C.Periodo = Co.Periodo 
       AND T.Cta = C.Codigo 
       AND T.TP = Co.TP 
       AND T.Numero = Co.Numero 
       AND T.Fecha = Co.Fecha 
       AND Co.Codigo_B = CL.Codigo 
       AND Co.CodigoU = Ac.Codigo 
       ORDER BY T.Fecha,T.TP,T.Numero,T.ID ";

       // print_r($sql);
       // die();

      $stmt = sqlsrv_query($cid, $sql);
	   if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return '';
		 die( print_r( sqlsrv_errors(), true));  
	   }

	  
        $tabla = grilla_generica($stmt,null,NULL,'1',null,null,null,true);
     //   $tabla1 = utf8_encode($tabla);
   /*   if($tabla1 == "")
        {
        	 return "<table><tr><td>Sin registros</td></tr></table>";
        }else{
*/
 // cerrarSQLSERVERFUN($cid);
        	return $tabla;
       // }


       /*
 'MsgBox sSQL
  SelectDataGrid DGDiario, AdoDiario, sSQL
 
  Debe = 0: Haber = 0
  Debe_ME = 0: Haber_ME = 0
  DGDiario.Visible = False
  With AdoDiario.Recordset
   If .RecordCount > 0 Then
      'MsgBox .RecordCount
       RatonReloj
       Progreso_Barra.Valor_Maximo = Progreso_Barra.Valor_Maximo + .RecordCount
       Do While Not .EOF
          Debe = Debe + .Fields("Debe")
          Haber = Haber + .Fields("Haber")
          If .Fields("Parcial_ME") > 0 Then
              Debe_ME = Debe_ME + .Fields("Parcial_ME")
          Else
              Haber_ME = Haber_ME + (-.Fields("Parcial_ME"))
          End If
          Progreso_Barra.Mensaje_Box = "Consultando Diario General " & .Fields("Fecha")
          Progreso_Esperar
         .MoveNext
       Loop
       RatonNormal
      .MoveFirst
   End If
  End With*/
	}


    function cargar_consulta_libro($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
  {
    $cid = $this->conn;


    $sql = "SELECT T.Fecha,T.TP,T.Numero,CL.Cliente As Beneficiario,Co.Concepto,T.Cta,C.Cuenta,
       T.Parcial_ME,T.Debe,T.Haber,T.Detalle,Ac.Nombre_Completo,Co.CodigoU,Co.Autorizado,T.Item,T.ID 
       FROM Transacciones As T,Catalogo_Cuentas As C,Comprobantes As Co,Clientes As CL,Accesos As Ac 
       WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
    /*
     
  sSQL = "SELECT T.Fecha,T.TP,T.Numero,CL.Cliente As Beneficiario,Co.Concepto,T.Cta,C.Cuenta," _
       & "T.Parcial_ME,T.Debe,T.Haber,T.Detalle,Ac.Nombre_Completo,Co.CodigoU,Co.Autorizado,T.Item,T.ID " _
       & "FROM Transacciones As T,Catalogo_Cuentas As C,Comprobantes As Co,Clientes As CL,Accesos As Ac " _
       & "WHERE T.Fecha BETWEEN #" & FechaIni & "# and #" & FechaFin & "# " _
       & "AND T.Periodo = '" & Periodo_Contable & "' "
  If OpcCI.value Then  sSQL = sSQL & "AND T.TP = '" & CompIngreso & "' "*/
  if($OpcCI=='true')
  {
     $sql.="AND T.TP = '".G_COMPINGRESO."'";
  }
 // ElseIf OpcCE.value Then
   //  sSQL = sSQL & "AND T.TP = '" & CompEgreso & "' "
  if($OpcCE=='true')
  {
    $sql.=  "AND T.TP = '".G_COMPEGRESO."' ";
  }
 /*  ElseIf OpcCD.value Then
     sSQL = sSQL & "AND T.TP = '" & CompDiario & "' "*/
  if($OpcCD=='true')
  {
     $sql.="AND T.TP = '".G_COMPDIARIO."' ";  
  }
 /* ElseIf OpcND.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaDebito & "' "*/
  if($OpcND=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTADEBITO."' ";
  }
   /*ElseIf OpcNC.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaCredito & "' "
  End If*/
  if($OpcNC=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTACREDITO."' ";   
  }
  /*If OpcA.value Then
     sSQL = sSQL & "AND T.T = '" & Anulado & "' "
  Else
     sSQL = sSQL & "AND T.T = '" & Normal & "' "
  End If*/
  if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }

 /* 
  If CheckAgencia.value = 1 Then
     sSQL = sSQL & "AND Co.Item = '" & SinEspaciosIzq(DCAgencia.Text) & "' "
  Else
     If Not ConSucursal Then sSQL = sSQL & "AND Co.Item = '" & NumEmpresa & "' "
  End If
  */
  if($CheckAgencia=='true')
  {
    if($DCAgencia=='')
    {     
      $sql.="AND Co.Item = '".$_SESSION['INGRESO']['item']."' ";
    }else
    {
      $sql.= "AND Co.Item = '".$DCAgencia."' ";
    }

  }else
  {
    $sql.="AND Co.Item = '".$_SESSION['INGRESO']['item']."' ";
  }
  /*
  If CheckUsuario.value = 1 Then sSQL = sSQL & "AND Co.CodigoU = '" & SinEspaciosDer(DCUsuario.Text) & "' "

  */
  if($CheckUsuario=='true')
  {
    $sql.= "AND Co.CodigoU = '".$DCUsuario."' ";
  }
  /*
  If CheckNum.value = 1 Then sSQL = sSQL & "AND Co.Numero BETWEEN " & CLng(TextNumNo.Text) & " and " & CLng(TextNumNo1.Text) & " "
  */
  if($CheckNum=='true')
  {   
    $sql.= "AND Co.Numero BETWEEN ".$TextNumNo." and ".$TextNumNo1." ";
  }
  $sql.= "AND T.Item = Co.Item 
       AND T.Item = C.Item
       AND C.Item = Co.Item
       AND T.Periodo = C.Periodo
       AND T.Periodo = Co.Periodo 
       AND C.Periodo = Co.Periodo 
       AND T.Cta = C.Codigo 
       AND T.TP = Co.TP 
       AND T.Numero = Co.Numero 
       AND T.Fecha = Co.Fecha 
       AND Co.Codigo_B = CL.Codigo 
       AND Co.CodigoU = Ac.Codigo 
       ORDER BY T.Fecha,T.TP,T.Numero,T.ID ";

       //print_r($sql);

      $stmt = sqlsrv_query($cid, $sql);
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }

    $result = array();
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
       $result[] = $row;
     }

  //cerrarSQLSERVERFUN($cid);
     return $result;
  }

  function cargar_consulta_submodulo($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
  {
    
    $cid = $this->conn;
    $sql = "SELECT T.Fecha,T.TP,T.Numero,C.Cliente,T.Cta,T.TC,T.Factura,T.Debitos,T.Creditos,T.Prima
        FROM Trans_SubCtas As T,Clientes As C 
        WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

 /* If CheckAgencia.value = 1 Then
     sSQL = sSQL & "AND T.Item = '" & SinEspaciosIzq(DCAgencia.Text) & "' "
  Else
     sSQL = sSQL & "AND T.Item = '" & NumEmpresa & "' "
  End If*/

   if($CheckAgencia=='true')
  {
    if($DCAgencia=='')
    {     
      $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
    }else
    {
      $sql.= "AND T.Item = '".$DCAgencia."' ";
    }

  }else
  {
    $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
  }


 /* If OpcCI.value Then
     sSQL = sSQL & "AND T.TP = '" & CompIngreso & "' "
  ElseIf OpcCE.value Then
     sSQL = sSQL & "AND T.TP = '" & CompEgreso & "' "
  ElseIf OpcCD.value Then
     sSQL = sSQL & "AND T.TP = '" & CompDiario & "' "
  ElseIf OpcND.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaDebito & "' "
  ElseIf OpcNC.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaCredito & "' "
  End If*/
   if($OpcCI=='true')
  {
     $sql.="AND T.TP = '".G_COMPINGRESO."'";
  }
  if($OpcCE=='true')
  {
    $sql.=  "AND T.TP = '".G_COMPEGRESO."' ";
  }
  if($OpcCD=='true')
  {
     $sql.="AND T.TP = '".G_COMPDIARIO."' ";  
  }
 if($OpcND=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTADEBITO."' ";
  }
   if($OpcNC=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTACREDITO."' ";   
  }


/*
  If OpcA.value Then
     sSQL = sSQL & "AND T.T = '" & Anulado & "' "
  Else
     sSQL = sSQL & "AND T.T = '" & Normal & "' "
  End If
  */

   if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }

/*  If CheckUsuario.value = 1 Then sSQL = sSQL & "AND T.CodigoU = '" & SinEspaciosIzq(DCUsuario.Text) & "' "
  If CheckNum.value = 1 Then sSQL = sSQL & "AND T.Numero BETWEEN " & CLng(TextNumNo.Text) & " and " & CLng(TextNumNo1.Text) & " "*/

   if($CheckUsuario=='true')
  {
    $sql.= "AND T.CodigoU = '".$DCUsuario."' ";
  }
   if($CheckNum=='true')
  {   
    $sql.= "AND T.Numero BETWEEN ".$TextNumNo." and ".$TextNumNo1." ";
  }
  $sql .="AND T.Codigo = C.Codigo
       UNION
       SELECT T.Fecha,T.TP,T.Numero,Detalle As Cliente,T.Cta,T.TC,T.Factura,T.Debitos,T.Creditos,T.Prima
       FROM Trans_SubCtas As T,Catalogo_SubCtas As C 
        WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

  /*If CheckAgencia.value = 1 Then
     sSQL = sSQL & "AND T.Item = '" & SinEspaciosIzq(DCAgencia.Text) & "' "
  Else
     sSQL = sSQL & "AND T.Item = '" & NumEmpresa & "' "
  End If*/
    if($CheckAgencia=='true')
  {
    if($DCAgencia=='')
    {     
      $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
    }else
    {
      $sql.= "AND T.Item = '".$DCAgencia."' ";
    }

  }else
  {
    $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
  }


/*
  If OpcCI.value Then
     sSQL = sSQL & "AND T.TP = '" & CompIngreso & "' "
  ElseIf OpcCE.value Then
     sSQL = sSQL & "AND T.TP = '" & CompEgreso & "' "
  ElseIf OpcCD.value Then
     sSQL = sSQL & "AND T.TP = '" & CompDiario & "' "
  ElseIf OpcND.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaDebito & "' "
  ElseIf OpcNC.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaCredito & "' "
  End If*/
    if($OpcCI=='true')
  {
     $sql.="AND T.TP = '".G_COMPINGRESO."'";
  }
  if($OpcCE=='true')
  {
    $sql.=  "AND T.TP = '".G_COMPEGRESO."' ";
  }
  if($OpcCD=='true')
  {
     $sql.="AND T.TP = '".G_COMPDIARIO."' ";  
  }
 if($OpcND=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTADEBITO."' ";
  }
   if($OpcNC=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTACREDITO."' ";   
  }
  /*
  If OpcA.value Then
     sSQL = sSQL & "AND T.T = '" & Anulado & "' "
  Else
     sSQL = sSQL & "AND T.T = '" & Normal & "' "
  End If
  */
  if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }
/*
  If CheckUsuario.value = 1 Then sSQL = sSQL & "AND T.CodigoU = '" & SinEspaciosIzq(DCUsuario.Text) & "' "
  If CheckNum.value = 1 Then sSQL = sSQL & "AND T.Numero BETWEEN " & CLng(TextNumNo.Text) & " and " & CLng(TextNumNo1.Text) & " "
  */
    if($CheckUsuario=='true')
  {
    $sql.= "AND T.CodigoU = '".$DCUsuario."' ";
  }
   if($CheckNum=='true')
  {   
    $sql.= "AND T.Numero BETWEEN ".$TextNumNo." and ".$TextNumNo1." ";
  }

  $sql.= "AND T.Item = C.Item 
       AND T.Periodo = C.Periodo 
       AND T.Codigo = C.Codigo 
       ORDER BY T.Fecha,T.TP,T.Numero,T.Cta,T.Factura ";

  $stmt = sqlsrv_query($cid, $sql);
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }

    
        $tabla = grilla_generica($stmt,null,NULL,'1');

  //cerrarSQLSERVERFUN($cid);
        return $tabla;

  }

  function cargar_consulta_submodulo_datos($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
  {
    
    $cid = $this->conn;
    $sql = "SELECT T.Fecha,T.TP,T.Numero,C.Cliente,T.Cta,T.TC,T.Factura,T.Debitos,T.Creditos,T.Prima
        FROM Trans_SubCtas As T,Clientes As C 
        WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

 /* If CheckAgencia.value = 1 Then
     sSQL = sSQL & "AND T.Item = '" & SinEspaciosIzq(DCAgencia.Text) & "' "
  Else
     sSQL = sSQL & "AND T.Item = '" & NumEmpresa & "' "
  End If*/

   if($CheckAgencia=='true')
  {
    if($DCAgencia=='')
    {     
      $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
    }else
    {
      $sql.= "AND T.Item = '".$DCAgencia."' ";
    }

  }else
  {
    $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
  }


 /* If OpcCI.value Then
     sSQL = sSQL & "AND T.TP = '" & CompIngreso & "' "
  ElseIf OpcCE.value Then
     sSQL = sSQL & "AND T.TP = '" & CompEgreso & "' "
  ElseIf OpcCD.value Then
     sSQL = sSQL & "AND T.TP = '" & CompDiario & "' "
  ElseIf OpcND.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaDebito & "' "
  ElseIf OpcNC.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaCredito & "' "
  End If*/
   if($OpcCI=='true')
  {
     $sql.="AND T.TP = '".G_COMPINGRESO."'";
  }
  if($OpcCE=='true')
  {
    $sql.=  "AND T.TP = '".G_COMPEGRESO."' ";
  }
  if($OpcCD=='true')
  {
     $sql.="AND T.TP = '".G_COMPDIARIO."' ";  
  }
 if($OpcND=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTADEBITO."' ";
  }
   if($OpcNC=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTACREDITO."' ";   
  }


/*
  If OpcA.value Then
     sSQL = sSQL & "AND T.T = '" & Anulado & "' "
  Else
     sSQL = sSQL & "AND T.T = '" & Normal & "' "
  End If
  */

   if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }

/*  If CheckUsuario.value = 1 Then sSQL = sSQL & "AND T.CodigoU = '" & SinEspaciosIzq(DCUsuario.Text) & "' "
  If CheckNum.value = 1 Then sSQL = sSQL & "AND T.Numero BETWEEN " & CLng(TextNumNo.Text) & " and " & CLng(TextNumNo1.Text) & " "*/

   if($CheckUsuario=='true')
  {
    $sql.= "AND T.CodigoU = '".$DCUsuario."' ";
  }
   if($CheckNum=='true')
  {   
    $sql.= "AND T.Numero BETWEEN ".$TextNumNo." and ".$TextNumNo1." ";
  }
  $sql .="AND T.Codigo = C.Codigo
       UNION
       SELECT T.Fecha,T.TP,T.Numero,Detalle As Cliente,T.Cta,T.TC,T.Factura,T.Debitos,T.Creditos,T.Prima
       FROM Trans_SubCtas As T,Catalogo_SubCtas As C 
        WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

  /*If CheckAgencia.value = 1 Then
     sSQL = sSQL & "AND T.Item = '" & SinEspaciosIzq(DCAgencia.Text) & "' "
  Else
     sSQL = sSQL & "AND T.Item = '" & NumEmpresa & "' "
  End If*/
    if($CheckAgencia=='true')
  {
    if($DCAgencia=='')
    {     
      $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
    }else
    {
      $sql.= "AND T.Item = '".$DCAgencia."' ";
    }

  }else
  {
    $sql.="AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
  }


/*
  If OpcCI.value Then
     sSQL = sSQL & "AND T.TP = '" & CompIngreso & "' "
  ElseIf OpcCE.value Then
     sSQL = sSQL & "AND T.TP = '" & CompEgreso & "' "
  ElseIf OpcCD.value Then
     sSQL = sSQL & "AND T.TP = '" & CompDiario & "' "
  ElseIf OpcND.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaDebito & "' "
  ElseIf OpcNC.value Then
     sSQL = sSQL & "AND T.TP = '" & CompNotaCredito & "' "
  End If*/
    if($OpcCI=='true')
  {
     $sql.="AND T.TP = '".G_COMPINGRESO."'";
  }
  if($OpcCE=='true')
  {
    $sql.=  "AND T.TP = '".G_COMPEGRESO."' ";
  }
  if($OpcCD=='true')
  {
     $sql.="AND T.TP = '".G_COMPDIARIO."' ";  
  }
 if($OpcND=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTADEBITO."' ";
  }
   if($OpcNC=='true')
  {
     $sql.="AND T.TP = '".G_COMPNOTACREDITO."' ";   
  }
  /*
  If OpcA.value Then
     sSQL = sSQL & "AND T.T = '" & Anulado & "' "
  Else
     sSQL = sSQL & "AND T.T = '" & Normal & "' "
  End If
  */
  if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }
/*
  If CheckUsuario.value = 1 Then sSQL = sSQL & "AND T.CodigoU = '" & SinEspaciosIzq(DCUsuario.Text) & "' "
  If CheckNum.value = 1 Then sSQL = sSQL & "AND T.Numero BETWEEN " & CLng(TextNumNo.Text) & " and " & CLng(TextNumNo1.Text) & " "
  */
    if($CheckUsuario=='true')
  {
    $sql.= "AND T.CodigoU = '".$DCUsuario."' ";
  }
   if($CheckNum=='true')
  {   
    $sql.= "AND T.Numero BETWEEN ".$TextNumNo." and ".$TextNumNo1." ";
  }

  $sql.= "AND T.Item = C.Item 
       AND T.Periodo = C.Periodo 
       AND T.Codigo = C.Codigo 
       ORDER BY T.Fecha,T.TP,T.Numero,T.Cta,T.Factura ";

  $stmt = sqlsrv_query($cid, $sql);
     if( $stmt === false)  
     {  
     echo "Error en consulta PA.\n";  
     return '';
     die( print_r( sqlsrv_errors(), true));  
     }
    $result = array();
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
     {
       $result[] = $row;
     }

  //cerrarSQLSERVERFUN($cid);
     return $result;

  }

   function exportar_excel_diario($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
  {

    
      $result = $this->cargar_consulta_libro($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum);

     $b = 1;
    
     exportar_excel_diario_g($result,'Diario general',null,null,null);    
  }

  function exportar_excel_libro($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
  {    
     $datosub_m = $this->cargar_consulta_submodulo_datos($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum);
      $result = $this->cargar_consulta_libro($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum);

     $b = 1;
    
     exportar_excel_libro_g($result,$datosub_m,'Libro General',null,null,null);    
  }

}
?>