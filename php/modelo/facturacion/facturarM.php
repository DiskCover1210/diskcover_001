<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class facturarM
{
	private $db;

	public function __construct(){
    $this->db = new db();
  }


  function lineas_factura()
  {
  	$sql = "SELECT * 
            FROM Asiento_F 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
    $datos = $this->db->datos($sql);
    $botones[0] = array('boton'=>'Eliminar linea', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'A_No,CODIGO' );
	$tbl = grilla_generica_new($sql,'Asiento_F','',$titulo=false,$botones,$check=false,$imagen=false,1,1,1,100);
    return array('datos'=>$datos,'tbl'=>$tbl);  
  }

  function DCMod()
  {
      $sql = "SELECT Detalle, Codigo, TC 
       FROM Catalogo_SubCtas 
       WHERE Item = '".$_SESSION['INGRESO']['item']."' 
       AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
       AND TC IN ('I','CC') 
       ORDER BY Detalle ";
       return $this->db->datos($sql);
  }
  function DCLinea($TC,$Fecha){
     $sql = "SELECT Codigo, Concepto, CxC, Serie, Autorizacion 
      FROM Catalogo_Lineas 
      WHERE TL <> 0 
      AND Item = '".$_SESSION['INGRESO']['item']."' 
      AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      AND Fact = '".$TC. "' 
      AND Fecha <= '".$Fecha."' 
      AND Vencimiento >='".$Fecha."'
      ORDER BY Codigo ";
      return $this->db->datos($sql);
  }

  function DCTipoPago()
  {
     $sql = "SELECT (Codigo+' '+ Descripcion) As CTipoPago,Codigo 
       FROM Tabla_Referenciales_SRI 
       WHERE Tipo_Referencia = 'FORMA DE PAGO' 
       AND Codigo IN ('01','16','17','18','19','20','21') 
       ORDER BY Codigo ";       
      return $this->db->datos($sql);
  }

  function DCGrupo_No($query=false)
  {
     $sql = "SELECT Grupo 
       FROM Clientes 
       WHERE T = 'N' 
       AND FA <> 0";
       if($query)
        {
          $sql.=" AND Grupo LIKE '%".$query."%' ";

        }
        $sql.="
       GROUP BY Grupo 
       ORDER BY Grupo";       
       //print_r($sql);die();
       return $this->db->datos($sql);
  }

  function Listar_Tipo_Beneficiarios($query=false,$grupo)
  {
    $sql = "SELECT Cliente,CI_RUC,Codigo,Cta_CxP,Grupo,Cod_Ejec
         FROM Clientes
         WHERE FA <> 0
         AND T = 'N' ";
      if($query)
      {
        $sql.=" AND Cliente = '".$grupo."' ";
      }
    if($grupo <> G_NINGUNO )
      {
        $sql.=" AND Grupo = '".$grupo."' ";
      }
    $sql .=" ORDER BY Cliente ";
    //print_r($sql);die();
    return $this->db->datos($sql);
  }

  function bodega()
  {
    $sql = "SELECT * 
      FROM Catalogo_Bodegas 
      WHERE Item = '".$_SESSION['INGRESO']['item']."' 
      AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      ORDER BY Bodega ";
    return $this->db->datos($sql);

  }

  function DCMarca()
  {
     $sql = "SELECT * 
      FROM Catalogo_Marcas 
      WHERE Item = '".$_SESSION['INGRESO']['item']."' 
      AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
      ORDER BY Marca ";
      return $this->db->datos($sql);
  }
  function CDesc1()
  {
      $sql = "SELECT *
          FROM Catalogo_Interes 
          WHERE TP = 'D' 
          ORDER BY Interes ";
      return $this->db->datos($sql);
  }

  function DCMedico()
  {

     $sql = "SELECT Cliente, CI_RUC, TD, Codigo 
       FROM Clientes 
       WHERE Asignar_Dr <> 0 
       ORDER BY Cliente";
      return $this->db->datos($sql);
  }

  function DCEjecutivo()
  {

     $sql ="SELECT CR.Codigo,C.Cliente,C.CI_RUC,CR.Porc_Com 
        FROM Catalogo_Rol_Pagos As CR, Clientes As C 
        WHERE CR.Item = '".$_SESSION['INGRESO']['item']."' 
        AND CR.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND CR.Codigo = C.Codigo 
        ORDER BY C.Cliente ";
      return $this->db->datos($sql);
  }


  function Listar_Productos($Cod_Marca,$OpcServicio=false,$PatronDeBusqueda=false,$NombreMarca=false,$SQL_Server=false)
  {
    if($NombreMarca=='.')
    {
      $NombreMarca ='';
    }
     if($Cod_Marca <> G_NINGUNO)
     {
        if($SQL_Server)
        {
           $sql = "UPDATE Catalogo_Productos 
                SET Marca = '".$NombreMarca."' 
                FROM Catalogo_Productos As CP,Trans_Kardex As TK ";
        }else{
           $sql = "UPDATE Catalogo_Productos As CP,Trans_Kardex As TK 
                SET CP.Marca = '".$NombreMarca."' ";
        }
        $sql.="
             WHERE CP.Item = '".$_SESSION['INGRESO']['item']."'
             AND CP.Periodo = '".$_SESSION['INGRESO']['periodo']."'
             AND TK.CodMarca = '".$Cod_Marca."'
             AND CP.Codigo_Inv = TK.Codigo_Inv
             AND CP.Item = TK.Item
             AND CP.Periodo = TK.Periodo ";
             $this->db->String_Sql($sql);
     }
   
     $sql = "SELECT Producto,Codigo_Inv,Codigo_Barra 
          FROM Catalogo_Productos 
          WHERE TC = 'P' 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND INV <> 0 ";
     if($OpcServicio){ $sql.=" AND LEN(Cta_Inventario) <= 1 ";}
     if($PatronDeBusqueda <> ""){ $sql.= " AND Producto LIKE '%".$PatronDeBusqueda."%' ";}
     if($NombreMarca <> ""){ $sql.= " AND Marca LIKE '%".$NombreMarca. "%' ";}
     $sql.=" ORDER BY Producto,Codigo_Inv,Codigo_Barra ";

     // print_r($sql);die();
     $respuest  = $this->db->datos($sql);
     return $respuest;
     
  }
 function LstOrden()
 {
   $sql = "SELECT Lote_No 
        FROM Trans_Kardex 
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
        AND T <> 'A' 
        AND LEN(Lote_No) > 1 
        GROUP BY Lote_No 
        ORDER BY Lote_No ";
    $respuest  = $this->db->datos($sql);
     return $respuest;
 }

  function Listar_Productos_all($PatronDeBusqueda=false,$codigo=false)
  {   
     $sql = "SELECT *
          FROM Catalogo_Productos 
          WHERE TC = 'P' 
          AND Item = '".$_SESSION['INGRESO']['item']."' 
          AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
          AND INV <> 0 ";
     if($PatronDeBusqueda <> ""){ $sql.= " AND Producto LIKE '%".$PatronDeBusqueda."%' ";}
      if($codigo <> ""){ $sql.= " AND Codigo_Inv = '".$codigo."' ";}

     // print_r($sql);die();
     $respuest  = $this->db->datos($sql);
     return $respuest;
     
  }

  function delete_asientoF($ln_No=false)
  {
    $sql = "DELETE 
        FROM Asiento_F 
        WHERE Item = '".$_SESSION['INGRESO']['item']."' 
        AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
        if($ln_No)
        {
          $sql.=' AND A_No='.$ln_No;
        }
     $respuest  = $this->db->String_Sql($sql);
     return $respuest;

  }
  function delete_asientoTK()
  {
    $sql = "DELETE
         FROM Asiento_TK
         WHERE Item = '".$_SESSION['INGRESO']['item']."'
         AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ";
    $respuest  = $this->db->String_Sql($sql);
     return $respuest;   
  }
   

}

?>