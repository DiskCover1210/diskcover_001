<?php
require_once(dirname(__DIR__,2)."/db/db1.php");
require_once(dirname(__DIR__,2)."/funciones/funciones.php");

class facturarM
{
	private $db;

	public function __construct(){
    $this->db = new db();
  }


  function lineas_factura($CodigoUsuario)
  {
  	$sql = "SELECT * 
            FROM Asiento_F 
            WHERE Item = '".$_SESSION['INGRESO']['item']."' 
            AND CodigoU = '".$CodigoUsuario."' ";
    $datos = $this->db->datos($sql);
    $botones[0] = array('boton'=>'Eliminar', 'icono'=>'<i class="fa fa-trash"></i>', 'tipo'=>'danger', 'id'=>'' );
	$tbl = grilla_generica_new($sql,'Asiento_F','',$titulo=false,$botones,$check=false,$imagen=false,1,1,1,100);
    return array('datos'=>$datos,'tbl'=>$tbl);  
  }

}

?>