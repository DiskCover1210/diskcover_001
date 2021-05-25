<?php 
include(dirname(__DIR__,2).'/funciones/funciones.php');
// include(dirname(__DIR__).'/db/variables_globales.php');
@session_start(); 

/**
 * 
 */
class incomM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}

	function beneficiarios($query)
	{
		$cid = $this->conn;
		$sql="SELECT Cliente AS nombre, CI_RUC as id, email
		   FROM Clientes 
		   WHERE T <> '.' ";
		   if($query != '')
		   {
		   	$sql.=" AND Cliente LIKE '%".$query."%'";
		   }
		  $sql.=" ORDER BY Cliente OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY;";
		  // print_r($sql);die();
        $stmt = sqlsrv_query($cid, $sql);
	    $result = array();	
	   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
	   {
		$result[] = $row;
	   }

	   return $result;
	}
	function cuentas_efectivo($query)
	{
		$cid = $this->conn;
		$sql="SELECT Codigo,Codigo+' '+Cuenta  as 'cuenta' 
		FROM Catalogo_Cuentas
		WHERE TC = 'CJ' AND DG = 'D' ";
		if($query)
		{
			$sql.= " AND Codigo+' '+Cuenta LIKE '%".$query."%' ";
		}
		$sql.="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."'  ORDER BY Cuenta";
		// print_r($sql);die();
		 $stmt = sqlsrv_query($cid, $sql);
		 $result = array();
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	{
		 		$result[] = $row;
		 	}
		 	return $result;
	}

	function cuentas_banco($query)
	{
		$cid = $this->conn;
		$sql="SELECT Codigo,Codigo+' '+Cuenta  as 'cuenta' 
		FROM Catalogo_Cuentas
		WHERE TC ='BA' AND DG ='D' ";
		if($query)
		{
			$sql.= " AND Codigo+' '+Cuenta LIKE '%".$query."%' ";
		}
		$sql.="AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."'  ORDER BY Cuenta";
		// print_r($sql);die();
		 $stmt = sqlsrv_query($cid, $sql);
		 $result = array();
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	{
		 		$result[] = $row;
		 	}
		return $result;
	}

	function cuentas_todos($query,$tipo,$tipocta)
	{
		$cid = $this->conn;
		$sql="SELECT Codigo+Space(19-LEN(Codigo))+' -- '+TC+Space(3-LEN(TC))+' -- '+cast( Clave as varchar(5))+' '
					+Space(5-LEN(cast( Clave as varchar(5))))+' -- '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
			   FROM Catalogo_Cuentas 
			   WHERE DG = 'D' 
			   AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
			   AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  ";
			   if($query && $tipo =='')
			   	{
			   		$sql.="AND Codigo+Space(19-LEN(Codigo))+' '+TC+Space(3-LEN(TC))+' '+cast( Clave as varchar(5))+' '
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+Cuenta LIKE '%".$query."%'";
			   	}else
			   	{
			   		$sql.="AND Codigo+Space(19-LEN(Codigo))+' '+TC+Space(3-LEN(TC))+' '+cast( Clave as varchar(5))+' '
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+Cuenta LIKE '".$query."%'";

			   	}
			   	if($tipocta)
			   	{
			   		$sql.=" AND TC = '".$tipocta."'";
			   	}
			   	$sql.="ORDER BY Codigo";
		// print_r($sql);die();
		 $stmt = sqlsrv_query($cid, $sql);
		 $result = array();
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	{
		 		$result[] = $row;
		 	}
		return $result;
	}

	function cargar_asientosB()
	{
		$cid = $this->conn;
		$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
			FROM Asiento_B
			WHERE Item = '".$_SESSION['INGRESO']['item']."' AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
		// print_r($sql);die();
		 $stmt = sqlsrv_query($cid, $sql);
		 $result = array();
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	{
		 		$result[] = $row;
		 	}
		return $result;
	}

	function insertar_ingresos($datos)
	{
		$resp = insert_generico('Asiento_B',$datos);
		return $resp;
	}
	function insertar_ingresos_tabla($tabla,$datos)
	{
		$resp = insert_generico($tabla,$datos);
		return $resp;
	}

	function delete_asientoB($cta,$cheq)
	{
		$cid = $this->conn;
		$sql="Delete from Asiento_B ".
		   "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
		   "AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ".
		   "AND CTA_BANCO='".$cta."' ".
		   "AND CHEQ_DEP='".$cheq."' ";
		// print_r($sql);die();
		 $stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return -1;
		 die( print_r( sqlsrv_errors(), true));  
	   }
		return 1;

	}

	function delete_asientoBTodos()
	{
		$cid = $this->conn;
		$sql="Delete from Asiento_B";
		// print_r($sql);die();
		 $stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
	   {  
		 echo "Error en consulta PA.\n";  
		 return -1;
		 die( print_r( sqlsrv_errors(), true));  
	   }
		return 1;

	}

	function ListarAsientoTemSQL($ti,$Opcb,$b,$ch)
	{
		//opciones para generar consultas (asientos bancos)
		$cid = $this->conn;
		if($Opcb=='1')
		{
			$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
			FROM Asiento_B
			WHERE 
			Item = '".$_SESSION['INGRESO']['item']."' 
			AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$ta='asi_b';
		}
		else
		{
			$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE ,HABER ,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			
			$sql=$sql." ORDER BY A_No ";
			$ta='asi';
		}
		//echo $sql;
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$camne=array();
		$tabla =  grilla_generica($stmt,$ti,NULL,$b,$ch,$ta);
		return $tabla;
	}

	function ListarAsientoScSQL($ti,$Opcb,$b,$ch)
	{
		$cid = $this->conn;
		//opciones para generar consultas (asientos bancos)
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
				return grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
			}
	}

	function DG_asientos()
	{
		$cid = $this->conn;
	  $sql = "SELECT *
       FROM Asiento
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
       $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'CODIGO,asiento' );
	   $tbl = grilla_generica_new($sql,'Asiento','',$titulo=false,$botones,$check=false,$imagen=false,1,1,1,100);
			 // print_r($tbl);die();
		return $tbl;
    }

    function DG_asientos_SC()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_SC
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";

        $botones[0] = array('boton'=>'eliminar','icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'Codigo,asientoSC' );
        $tbl = grilla_generica_new($sql,'Asiento_SC',false,$titulo=false,$botones,$check=false,$imagen=false,1,1,1,100);
			 // print_r($tbl);die();
		return $tbl;
    }

    function DG_asientoB()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_B
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
       $stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$camne=array();
				// return grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
				 $button[0] = array('nombre'=>'eliminar','tipo'=>'danger','icon'=>'fa fa-trash','dato'=>array('0,asientoB'));
				 grilla_generica($stmt,null,null,1,null,null,null,false,$button);
			}
    }

    function DG_asientoR()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_Air
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
 
       $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'CodRet,air');
	   $tbl = grilla_generica_new($sql,'Asiento_Air',false,$titulo=false,$botones,$check=false,$imagen=false,1,1,1,100);
			 // print_r($tbl);die();
		return $tbl;
    }
     function DG_asientoR_datos()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_Air
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
       $stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
		$datos = array();
		 while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	{
		 		$datos[] = $row;
		 	}
		return $datos;
    }

    function DG_AC()
    {
    	$cid = $this->conn;

       $sql = "SELECT *
       FROM Asiento_Compras
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
  
	   $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'IdProv,compras' );
	   $tbl = grilla_generica_new($sql,'Asiento_Compras',$id_tabla = 'tbl_ac',$titulo=false,$botones,$check=false,$imagen=false,1,1,1,100);
	   return $tbl;
	
    }

    function DG_AV()
    {
    	$cid = $this->conn;
       $sql = "SELECT *
       FROM Asiento_Ventas
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
   
	   $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'IdProv,ventas' );
	   $tbl = grilla_generica_new($sql,'Asiento_Ventas','tbl_av',$titulo=false,$botones,$check=false,$imagen=false,1,1,1,30);
	   return $tbl;
    }

    function DG_AE()
    {
    	$cid = $this->conn;
        $sql = "SELECT *
       FROM Asiento_Exportaciones
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
   //     $stmt = sqlsrv_query( $cid, $sql);
			// if( $stmt === false)  
			// {  
			// 	 echo "Error en consulta PA.\n";  
			// 	 die( print_r( sqlsrv_errors(), true));  
			// }
			// else
			// {
			// 	$camne=array();
			// 	// return grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
			// 	 $button[0] = array('nombre'=>'eliminar','tipo'=>'danger','icon'=>'fa fa-trash','dato'=>array('0,expo'));
			// 	 grilla_generica($stmt,null,null,1,null,null,null,false,$button);
			// }
        $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'Codigo,expo' );
	   $tbl = grilla_generica_new($sql,'Asiento_Exportaciones',$id_tabla ='tbl_ae',$titulo=false,$botones,$check=false,$imagen=false,1,1,1,30);
	   return $tbl;
    }

    function DG_AI()
    {
    	$cid = $this->conn;

      $sql = "SELECT *
       FROM Asiento_Importaciones
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
   //     $stmt = sqlsrv_query( $cid, $sql);
			// if( $stmt === false)  
			// {  
			// 	 echo "Error en consulta PA.\n";  
			// 	 die( print_r( sqlsrv_errors(), true));  
			// }
			// else
			// {
			// 	$camne=array();
			// 	// return grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
			// 	 $button[0] = array('nombre'=>'eliminar','tipo'=>'danger','icon'=>'fa fa-trash','dato'=>array('0,inpor'));
			// 	 grilla_generica($stmt,null,null,1,null,null,null,false,$button);
			// }
         $botones[0] = array('boton'=>'eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'CodSustento,inpor' );
	   $tbl = grilla_generica_new($sql,'Asiento_Importaciones',$id_tabla ='tbl_ai',$titulo=false,$botones,$check=false,$imagen=false,1,1,1,30);
	   return $tbl;

    }

    function LeerCta($CodigoCta)
    {
    	
    	$cid = $this->conn;
    	if(strlen(substr($CodigoCta,0,1))>=1)
    	{
    	  $sql="SELECT Codigo, Cuenta, TC, ME, DG, Tipo_Pago 
          FROM Catalogo_Cuentas 
          WHERE Codigo = '".$CodigoCta."'
          AND Item = '".$_SESSION['INGRESO']['item']."'
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
           $stmt = sqlsrv_query($cid, $sql);
		   $result = array();
		   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	  {
		 		  $result[] = $row;
		 	  }
		  return $result;
    	}
    }

    function catalogo_subcta_grid($tc,$SubCtaGen,$OpcDH,$OpcTM)
    {
    	$cid = $this->conn;
    	

        $sql = "SELECT * 
         FROM Asiento_SC
         WHERE TC = '".$tc."'
         AND Cta = '".$SubCtaGen."'
         AND DH = '".$OpcDH."'
         AND TM = '".$OpcTM."'
         AND T_No = '".$_SESSION['INGRESO']['modulo_']."'
         AND Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";
// print_r($sql);
        $stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$camne=array();
				return grilla_generica($stmt,null,null,1);
			}
    }

    function catalogo_subcta($SubCta)
    { 
    	$cid = $this->conn;
    	
    	$sql = "SELECT Detalle,Codigo, Nivel
        FROM Catalogo_SubCtas
        WHERE TC = '".$SubCta."'
        AND Item = '".$_SESSION['INGRESO']['item']."'
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
        AND Agrupacion <> 0
        AND Codigo <> '.' 
        ORDER BY Nivel,Detalle ";
// print_r($sql);
          $stmt = sqlsrv_query($cid, $sql);
		   $result = array();
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	  {
		 		  $result[] = $row;
		 	  }
		  return $result;
    }
    function Catalogo_CxCxP($SubCta,$SubCtaGen,$query=false)
    {

    	$cid = $this->conn;
    	 $sql= "SELECT Cl.Cliente As NomCuenta, CP.Codigo, Cl.Credito 
         FROM Catalogo_CxCxP As CP,Clientes As Cl 
         WHERE CP.TC = '".$SubCta."' 
         AND CP.Cta = '".$SubCtaGen."' 
         AND CP.Item = '".$_SESSION['INGRESO']['item']."'
         AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
         AND Cl.Codigo <> '.' 
         AND CP.Codigo = Cl.Codigo "; 
         if($query)
         {
         	$sql.= "AND Cl.Cliente LIKE '%".$query."%' ";

         }
         $sql.=" ORDER BY Cl.Cliente ";
        
         // print_r($sql);die();
          $stmt = sqlsrv_query($cid, $sql);
		   $result = array();
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	  {
		 		  $result[] = $row;
		 	  }
		  return $result;
    }


     function detalle_aux_submodulo($query = false)
     {

    	$cid = $this->conn;
     	 $sql = "SELECT Detalle_SubCta
         FROM Trans_SubCtas
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
         if($query)
         {
         	$sql.=" AND Detalle_SubCta LIKE '%".$query."%' ";
         }
         $sql.="GROUP BY Detalle_SubCta
         ORDER BY Detalle_SubCta ";
           $stmt = sqlsrv_query($cid, $sql);
		   $result = array();
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 	  {
		 		  $result[] = $row;
		 	  }
		  return $result;
     }

     function DG_asientos_SC_total()
    {
    	$cid = $this->conn;
       $sql = "SELECT SUM(Valor) as 'total'
       FROM Asiento_SC
       WHERE Item = '".$_SESSION['INGRESO']['item']. "'
       AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
       AND T_No = ".$_SESSION['INGRESO']['modulo_']." ";
       $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		  return $result;
    }
    function limpiar_asiento_SC($SubCta,$SubCtaGen,$OpcDH,$OpcTM)
    {

    	$cid = $this->conn;
    	 $sql = "DELETE 
         FROM Asiento_SC 
         WHERE TC = '".$SubCta."'
         AND Cta = '".$SubCtaGen."'
         AND DH = '".$OpcDH."'
         AND TM = '".$OpcTM."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_']."
         AND Item = '".$_SESSION['INGRESO']['item']. "'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'";

         // print_r($sql);die();
         $stmt = sqlsrv_query( $cid, $sql);
         if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		   
		return 1;
    }
    function asientos()
    { 

    	$cid = $this->conn;
    	$sql = "SELECT *
         FROM Asiento
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_']."
         ORDER BY A_No ";
         $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		  return $result;
 

    }
    function asientos_SC()
    {
    	
    	$cid = $this->conn;
    	$sql = "SELECT *
         FROM Asiento_SC
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_']."";
         $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		  return $result;

    }
    function eliminacion_retencion()
    {
    	
    	$cid = $this->conn;
    	$sql = "DELETE FROM Asiento_Air
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_'].";";

         $sql.= "DELETE FROM Asiento_Compras
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_'].";";

         $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			return -1;
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	   
		  return 1;

    }

    function eliminar_registros($tabla,$Codigo)
    {
    	$cid = $this->conn;
    	$sql = "DELETE FROM ".$tabla."
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND ".$Codigo."
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."'
         AND T_No = ".$_SESSION['INGRESO']['modulo_'].";";

         // print_r($sql);die();

         $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			return -1;
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	   
		  return 1;

    }
    function retencion_compras($numero,$tipoCom)
    {
    	$cid = $this->conn;
        $sql = "SELECT C.Cliente,C.CI_RUC,C.TD,C.Direccion,C.Telefono,C.Email,TC.* 
        FROM Trans_Compras As TC, Clientes As C 
        WHERE TC.Item = '".$_SESSION['INGRESO']['item']."' 
        AND TC.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND TC.Numero = ".$numero." 
        AND TC.TP = '".$tipoCom."' 
        AND LEN(TC.AutRetencion) = 13 
        AND TC.IdProv = C.Codigo 
        ORDER BY Serie_Retencion,SecRetencion ";
        // print_r($sql);die();
        $result = array();
         $stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		  return $result;
    }

    function validar_porc_iva($FechaIVA)
    {
   // 'Carga la Tabla de Porcentaje IVA'
    $cid = $this->conn;
    if ($FechaIVA == "00/00/0000"){ $FechaIVA = date('Y-m-d');}
     $sql = "SELECT * 
           FROM Tabla_Por_ICE_IVA 
           WHERE IVA <> 0 
           AND Fecha_Inicio <= '".$FechaIVA."' 
           AND Fecha_Final >= '".$FechaIVA."'
           ORDER BY Porc ";
         $stmt = sqlsrv_query($cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
	    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		 {
		 	$result[] = $row;
		 }
		 if(count($result)>0)
		 {
		 	$Porc_IVA = round($result[0]["Porc"] / 100, 2);
		 	return $Porc_IVA;
		 }
	}
    

}
?>