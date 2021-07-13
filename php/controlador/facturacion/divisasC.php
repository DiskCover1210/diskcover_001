<?php
require_once(dirname(__DIR__,2)."/modelo/facturacion/divisasM.php");
require_once(dirname(__DIR__,2)."/comprobantes/SRI/autorizar_sri.php");
require_once(dirname(__DIR__,3)."/lib/excel/plantilla.php");
require_once(dirname(__DIR__,3).'/lib/phpmailer/enviar_emails.php');
if(!class_exists('cabecera_pdf'))
{
  require(dirname(__DIR__,3).'/lib/fpdf/cabecera_pdf.php');
}

$controlador = new divisasC();

class divisasC
{
	private $facturacion;
  private $pdf;

	public function __construct(){
    $this->modelo = new divisasM();
    $this->autorizar_sri = new autorizacion_sri();
    $this->pdf = new cabecera_pdf();
    $this->email = new enviar_emails(); 
  }

  public function getCatalogoLineas(){
    $datos = $this->modelo->getCatalogoLineas();
    $catalogo = [];
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      //$catalogo[] = array('id'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC'] ,'text'=>utf8_encode($value['Concepto']));
      $catalogo[] = array('id'=>$value['Fact']." ".$value['Serie']." ".$value['Autorizacion']." ".$value['CxC'] ,'text'=>$value['Concepto']);
    }
    return $catalogo;
  }

  public function getProductos(){
    $datos = $this->modelo->getProductos();
    $productos = [];
    while ($value = sqlsrv_fetch_array( $datos, SQLSRV_FETCH_ASSOC)) {
      //$productos[] = array('id'=>utf8_decode($value['Codigo_Inv'])." ".utf8_decode($value['Producto']) ,'text'=>utf8_encode($value['Producto']));
      $productos[] = array('id'=>$value['Codigo_Inv']."/".$value['Producto']."/".$value['PVP'] ,'text'=>$value['Producto']);
    }
    return $productos;
  }
        
}
?>