<?php 
include(dirname(__DIR__).'/funciones/funciones.php');//
@session_start(); 
/**
 * 
 */
class ctaOperacionesM 
{
	
	 private $conn ;
	function __construct()
	{
	   $this->conn = cone_ajax();
	}
}
?>