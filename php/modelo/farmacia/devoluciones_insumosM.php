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
class devoluciones_insumosM
{
	
	private $conn ;
	function __construct()
	{
	   $this->conn = new db();
	}

	function cargar_comprobantes($query=false,$desde,$hasta,$tipo='',$paginacion=false)
	{
		$sql="SELECT Numero,CP.Fecha,Concepto,Monto_Total,Cliente FROM Comprobantes CP 
		LEFT JOIN Clientes C ON CP.Codigo_B = C.Codigo
		WHERE 1=1 AND TP='CD' AND CP.T='N' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo_B <> '.' AND Numero IN ( SELECT  DISTINCT Numero FROM Trans_Kardex WHERE 1=1 AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  AND Entrada = 0 )";
		if($tipo =='f')
		{
			$sql.= " AND CP.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}
		if($query)
		{
			$sql.=" AND C.Cliente like '%".$query."%'";
		}
		$num_reg = array('0','100','cargar_pedidos()');
	    $botones[0] = array('boton'=>'Ver detalle','icono'=>'<i class="fa fa-reorder"></i>', 'tipo'=>'primary', 'id'=>'Numero');
	    $datos = grilla_generica_new($sql,'Comprobantes CP',$id_tabla=false,false,$botones,false,$imagen=false,1,1,1,300,2,$num_reg,false);
	 

     
        return $datos;

	}

	function cargar_comprobantes_datos($query=false,$desde='',$hasta='',$tipo='',$numero=false)
	{
		$sql="SELECT Numero,CP.Fecha,Concepto,Monto_Total,Cliente FROM Comprobantes CP 
		LEFT JOIN Clientes C ON CP.Codigo_B = C.Codigo
		WHERE 1=1 AND TP='CD' AND CP.T='N' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo_B <> '.' AND Numero IN ( SELECT  DISTINCT Numero FROM Trans_Kardex WHERE 1=1 AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  AND Entrada = 0 )";
		if($tipo =='f')
		{
			$sql.= " AND CP.Fecha BETWEEN '".$desde."' AND '".$hasta."'";
		}
		if($query)
		{
			$sql.=" AND C.Cliente like '%".$query."%'";
		}
		if($numero)
		{
			$sql.="AND Numero ='".$numero."'";
		}
		// " AND CP.CodigoU = '".$_SESSION['INGRESO']['CodigoU']."';";

		 return $this->conn->datos($sql);

	}

	function trans_kardex($numero)
	{

		$sql="SELECT  Codigo_Inv,Salida,Valor_Unitario,Valor_Total  FROM Trans_Kardex WHERE 1=1 AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Entrada = 0 AND Numero = '".$numero."' ";
		$datos = $this->conn->datos($sql);
        return $datos;

	}


	function producto($Codigo_Inv)
	{

        $sql="SELECT Producto FROM Catalogo_Productos WHERE Codigo_Inv = '".$Codigo_Inv."' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'";
	    $datos = $this->conn->datos($sql);
        return $datos;


	}
	function imprimir_excel($stmt1)
	{		
	     exportar_excel_comp($stmt1,null,null,1);
	}

	function lineas_trans_kardex($numero)
	{

		$sql="SELECT  T.Codigo_Inv,Salida,Valor_Unitario,T.Valor_Total,C.Producto,T.ID,T.Utilidad,C.Utilidad as 'utilidad_C'  
		FROM Trans_Kardex T
       INNER JOIN Catalogo_Productos C ON T.Codigo_Inv = C.Codigo_Inv 
		WHERE 1=1 AND C.Item = '".$_SESSION['INGRESO']['item']."' 
		AND C.Periodo ='".$_SESSION['INGRESO']['periodo']."' 
		AND Entrada = 0 
		AND Numero = '".$numero."' ";
		// print_r($sql);die();
		$datos = $this->conn->datos($sql);
        return $datos;
	}

	function familias($numero)
	{
		$sql ="SELECT DISTINCT SUBSTRING(Codigo_Inv,0,6) as 'familia'
		      FROM Trans_Kardex 
		      WHERE 1=1 
		      AND Item = '".$_SESSION['INGRESO']['item']."' 
		      AND Periodo ='".$_SESSION['INGRESO']['periodo']."' 
		      AND Entrada = 0 
		      AND Numero = '".$numero."' ";
		      $datos = $this->conn->datos($sql);
        return $datos;
	}
}

?>