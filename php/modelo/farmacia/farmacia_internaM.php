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
		// print_r($sql);die();
		$tbl = grilla_generica_new($sql,'Trans_Kardex T ',$id_tabla='tbl_ingresos',null,$botones=false,null,false,null,null,null,null,null,null);
		// print_r($tbl);die();
		$datos = $this->conn->datos($sql);
		return array('tbl'=>$tbl,'datos'=>$datos);
	}


	function tabla_catalogo($query,$tipo)
	{
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
		$sql.=' ORDER BY ID OFFSET 0 ROWS FETCH NEXT 100 ROWS ONLY;';
        $tbl = grilla_generica_new($sql,'Catalogo_Productos',$id_tabla='tbl_ingresos',null,$botones=false,null,false,null,null,null,null,null,null);
		// print_r($tbl);die();
		$datos = $this->conn->datos($sql);
		return array('tbl'=>$tbl,'datos'=>$datos);
	}

	function pedido_paciente($nombre=false,$ci=false,$historia=false,$departamento=false,$procedimiento=false,$desde=false,$hasta =false,$busfe=false)
	{
		$sql = "SELECT SUM(VALOR_TOTAL) as 'importe',ORDEN,Codigo_B,Fecha_Fab,C.Cliente as 'nombre',A.SUBCTA as 'area',CS.Detalle as 'subcta',C.Matricula as 'his',A.Detalle as 'Detalle',Matricula
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
		if($busfe)
		{		
			  $sql.=" AND Fecha_Fab BETWEEN '".$desde."' and '".$hasta."'";
		}

		$sql.=" GROUP BY ORDEN ,Codigo_B,Fecha_Fab,C.Cliente,A.SUBCTA,CS.Detalle,C.Matricula,A.Detalle,Matricula ORDER BY Fecha_Fab DESC";
		$sql.=" OFFSET 0 ROWS FETCH NEXT 50 ROWS ONLY;";
		
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
       return $datos;
	}

}

?>