<?php 
include('../modelo/ctaOperacionesM.php');
/**
 * 
 */
class ctaOperacionesC
{
	private $modelo;
	function __construct()
	{
		$this->modelo = new ctaOperacionesM();
	}
}
?>