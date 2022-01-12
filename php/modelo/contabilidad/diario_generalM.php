<?php
include(dirname(__DIR__,2).'/funciones/funciones.php');//
if(!class_exists('variables_g'))
{
include(dirname(__DIR__,2).'/db/variables_globales.php');//
}
@session_start(); 

/**
 * 
 */
class diario_generalM
{
	private $conn;
	
	function __construct()
	{     
        $this->db = new db();
	}

  function llenar_agencia()
  {
  	$sql= "SELECT (Sucursal +'  ' + Empresa) As NomEmpresa,Sucursal as 'Item'
FROM Acceso_Sucursales
INNER JOIN Empresas ON Acceso_Sucursales.Sucursal = Empresas.Item
WHERE Acceso_Sucursales.Item ='".$_SESSION['INGRESO']['item']."'
ORDER BY Acceso_Sucursales.Item,Empresa";

       $result = $this->db->datos($sql);
	   return $result;

  }
  function llenar_usuario()
  {
  	$sql = "SELECT (Nombre_Completo +'  '+ Codigo) As CodUsuario,Codigo
            FROM Comprobantes, Accesos
            where Item ='001'
            and Periodo='.'
            AND Comprobantes.CodigoU = Accesos.Codigo
            group by (Nombre_Completo +'  '+ Codigo) ,Codigo
            union 
            SELECT (Nombre_Completo +'  '+ Codigo) As CodUsuario,Codigo
            FROM Facturas,Accesos
            where Item ='001'
            and Periodo='.'
            AND Facturas.CodigoU = Accesos.Codigo
            group by (Nombre_Completo +'  '+ Codigo),Codigo
            order by Codigo";
  	/*$sql = "SELECT (Nombre_Completo +'  '+ Codigo) As CodUsuario,Codigo
       FROM Accesos 
       WHERE Codigo <> '*' 
       ORDER BY Nombre_Completo ";*/
    $result = $this->db->datos($sql);
       return $result;
  }

  function cargar_consulta_libro_tabla($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
	{
		$sql = "SELECT T.Fecha,T.TP,T.Numero,CL.Cliente As Beneficiario,Co.Concepto,T.Cta,C.Cuenta,
       T.Parcial_ME,T.Debe,T.Haber,T.Detalle,Ac.Nombre_Completo,Co.CodigoU,Co.Autorizado,T.Item,T.ID 
       FROM Transacciones As T,Catalogo_Cuentas As C,Comprobantes As Co,Clientes As CL,Accesos As Ac 
       WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
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
 
  if($OpcA=='true')
  {
  	$sql.="AND T.T = '".G_ANULADO."' ";  	
  }else
  {
  	 $sql.="AND T.T = '".G_NORMAL."' ";
  }

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
  
  if($CheckUsuario=='true')
  {
  	$sql.= "AND Co.CodigoU = '".$DCUsuario."' ";
  }
 
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
    
       $tbl = grilla_generica_new($sql,' Transacciones As T,Catalogo_Cuentas As C,Comprobantes As Co,Clientes As CL,Accesos As Ac ','tbl_di',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,500);
	  
        // $tabla = grilla_generica($stmt,null,NULL,'1',null,null,null,true);
        	return $tbl;
	}


    function cargar_consulta_libro($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
  {
    $sql = "SELECT T.Fecha,T.TP,T.Numero,CL.Cliente As Beneficiario,Co.Concepto,T.Cta,C.Cuenta,
       T.Parcial_ME,T.Debe,T.Haber,T.Detalle,Ac.Nombre_Completo,Co.CodigoU,Co.Autorizado,T.Item,T.ID 
       FROM Transacciones As T,Catalogo_Cuentas As C,Comprobantes As Co,Clientes As CL,Accesos As Ac 
       WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
  
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

  if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }


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
 
  if($CheckUsuario=='true')
  {
    $sql.= "AND Co.CodigoU = '".$DCUsuario."' ";
  }

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
        $result = $this->db->datos($sql);
       return $result;
  }

  function cargar_consulta_submodulo($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
  {
    
    
    $sql = "SELECT T.Fecha,T.TP,T.Numero,C.Cliente,T.Cta,T.TC,T.Factura,T.Debitos,T.Creditos,T.Prima
        FROM Trans_SubCtas As T,Clientes As C 
        WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";


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



   if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }


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

  if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }

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
$tbl = grilla_generica_new($sql,' Trans_SubCtas As T,Clientes As C ','tbl_mo',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,500);
return $tbl;
  }

  function cargar_consulta_submodulo_datos($FechaIni,$FechaFin,$DCAgencia,$DCUsuario,$TextNumNo,$TextNumNo1,$OpcCI,$OpcCE,$OpcCD,$OpcND,$OpcNC,$OpcA,$CheckAgencia,$CheckUsuario,$CheckNum)
  {
    
    
    $sql = "SELECT T.Fecha,T.TP,T.Numero,C.Cliente,T.Cta,T.TC,T.Factura,T.Debitos,T.Creditos,T.Prima
        FROM Trans_SubCtas As T,Clientes As C 
        WHERE T.Fecha BETWEEN '".$FechaIni."' and '".$FechaFin."' 
       AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";

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



   if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }

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
  
  if($OpcA=='true')
  {
    $sql.="AND T.T = '".G_ANULADO."' ";   
  }else
  {
     $sql.="AND T.T = '".G_NORMAL."' ";
  }

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

        $result = $this->db->datos($sql);
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