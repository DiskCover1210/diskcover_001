<?php 
// include(dirname(__DIR__,2).'/db/db1.php');//
if(!class_exists('variables_g'))
{
	include(dirname(__DIR__,2).'/db/variables_globales.php');//
    include(dirname(__DIR__,2).'/funciones/funciones.php');
}
@session_start(); 

/**
 * 
 */
class farmacia_internaM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}

	function tabla_ingresos($query=false,$comprobante=false,$factura=false)
	{
		$sql="SELECT Fecha_DUI as 'Fecha',Cliente,Numero as 'Comprobante',Factura 
		FROM Trans_Kardex T
		RIGHT JOIN Clientes C ON T.Codigo_P=C.Codigo
		WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		AND Periodo  ='".$_SESSION['INGRESO']['periodo']."' 
		AND Entrada <> 0";
		if($query)
		{
			$sql.=" AND Codigo_P='".$query."'";
		}
		if($comprobante)
		{
			$sql.=" AND Numero like '%".$comprobante."%'";
		}
		if($factura)
		{
			$sql.=" AND Factura like '%".$factura."%'";
		}
		$sql.="GROUP BY Numero,Codigo_P,Factura,Fecha_DUI,Cliente
		ORDER BY Fecha_DUI DESC";
		// $sql.=' OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;';

		$reg = array(0,100);
		// print_r($sql);die();
		$tbl = grilla_generica_new($sql,'Trans_Kardex T ',$id_tabla='tbl_ingresos',null,$botones=false,false,false,1,1,1,500,2,false);
		// print_r($tbl);die();
		$datos = $this->conn->datos($sql);
		return array('tbl'=>$tbl,'datos'=>$datos);
	}


	function tabla_catalogo($query,$tipo)
	{
		// print_r($query);
		// print_r($tipo);die();
		$sql = "SELECT Codigo_Inv as 'Codigo',Producto,Valor_Total,Stock_Actual as 'Cantidad' 
		FROM Catalogo_Productos 
		WHERE INV = 1 
		AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND Item = '".$_SESSION['INGRESO']['item']."' 
		AND LEN(Cta_Inventario)>3 AND LEN(Cta_Costo_Venta)>3 AND ";
		if($tipo =='desc')
		{
		 $sql.="Producto LIKE '%".$query."%'";
		}else
		{
			$sql.=" Codigo_Inv LIKE '%".$query."%'";
		}
		$sql.=' ORDER BY Producto';
		// OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;';

		// print_r($sql);die();

        $tbl = grilla_generica_new($sql,'Catalogo_Productos',$id_tabla='tbl_pro',false,$botones=false,false,false,1,1,1,500,null,null);
		// print_r($tbl);die();
		$datos = $this->conn->datos($sql);
		return array('tbl'=>$tbl,'datos'=>$datos);
	}

	function pedido_paciente($nombre=false,$ci=false,$historia=false,$departamento=false,$procedimiento=false,$desde=false,$hasta =false,$busfe=false)
	{
		$sql = "SELECT Fecha_Fab as 'Fecha',C.Cliente as 'Paciente',CI_RUC AS 'Cedula',C.Matricula as 'Historia',CS.Detalle as 'Departamento',SUM(VALOR_TOTAL) as 'importe',A.Detalle as 'Procedimiento'
			FROM Asiento_K A
			LEFT JOIN Clientes C ON C.CI_RUC = A.Codigo_B  
			LEFT JOIN Catalogo_SubCtas CS ON CS.Codigo = A.SUBCTA
			WHERE DH='2' ";
		if($historia)
		{
			$sql.=" AND Matricula like '".$historia."%' ";
		}
		if($ci!='')
		{
			$sql.=" AND Codigo_B LIKE '".$ci."%' ";
		}
		if ($nombre!='') 
		{
			$sql.=" AND Cliente LIKE '%".$nombre."%'";
		}

		if($departamento)
		{		
		  $sql.=" AND CS.Detalle like '".$departamento."%'";
		}
		if($procedimiento)
		{		
		  $sql.=" AND A.Detalle like '%".$procedimiento."%'";
		}
		
		if($busfe=='true')
		{		
			  $sql.=" AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."'";
		}

		$sql.=" GROUP BY ORDEN ,CI_RUC,Fecha_Fab,C.Cliente,A.SUBCTA,CS.Detalle,C.Matricula,A.Detalle,Matricula ORDER BY Fecha_Fab DESC";
		// $sql.=" OFFSET 0 ROWS FETCH NEXT 50 ROWS ONLY;";
		
		// print_r($sql);die();
		$botones[0] = array();
		$tbl = grilla_generica_new($sql,' Asiento_K A','tbl_pedi',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,500);
		$datos = $this->conn->datos($sql);
       return array('tbl'=>$tbl,'datos'=>$datos) ;
	}




	function descargos_medicamentos($query=false,$paciente=false,$ci=false,$departamento=false,$desde=false,$hasta=false,$tipo=false)
	{
		// print_r($tipo);die();
		$sql = "SELECT T.Fecha,CP.Producto,Cliente,CI_RUC as 'Cedula',C.Matricula,Centro_Costo as 'Departamento',Salida as 'Cantidad'
		FROM Trans_Kardex T
		INNER JOIN Catalogo_Productos CP ON T.Codigo_Inv = CP.Codigo_Inv
		INNER JOIN Clientes C ON T.Codigo_P = C.Codigo  
		WHERE T.Item = ".$_SESSION['INGRESO']['item']." AND T.Periodo  ='".$_SESSION['INGRESO']['periodo']."' AND Entrada = 0 AND Matricula <>0 AND Centro_Costo <> '.'";
		if($query)
		{
			$sql.=" AND CP.Producto like '%".$query."%'";
		}
		if($paciente)
		{
			$sql.=" AND Cliente like '%".$paciente."%'";
		}

		if($ci)
		{
			$sql.=" AND CI_RUC like '".$ci."%'";
		}
		if($departamento)
		{
			$sql.=" AND Centro_Costo like '%".$departamento."%'";
		}
		if($tipo=='true')
		{
			$sql.=" AND T.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}

		$sql.="GROUP BY T.Fecha,CP.Producto,Cliente,CI_RUC,C.Matricula,Centro_Costo,Numero,Salida
		ORDER BY T.Fecha DESC ";
		 $sql.=" OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;";
		 // print_r($sql);die();
		$tbl = grilla_generica_new($sql,' Trans_Kardex T','tbl_medi',false,$botones=false,$check=false,$imagen=false,$border=1,$sombreado=1,$head_fijo=1,500);
		$datos = $this->conn->datos($sql);
		 // print_r($datos);die();
       return array('tbl'=>$tbl,'datos'=>$datos) ;
	}

	function costo_producto($Codigo_Inv)
	{

		$cid = $this->conn;
		$sql = "SELECT TOP 1 Codigo_Inv,Costo,Valor_Unitario,Existencia,Total,T 
               FROM Trans_Kardex 
               WHERE Item = '".$_SESSION['INGRESO']['item']. "' 
               AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
               AND Fecha <= '".date('Y-m-d')."' 
               AND Codigo_Inv = '".$Codigo_Inv."' 
               AND T <> 'A' 
               ORDER BY Fecha DESC,TP DESC, Numero DESC,ID DESC ";
               // print_r($sql);die();
       		$datos = $this->conn->datos($sql);
       return $datos;

	}

}

?>