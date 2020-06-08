<?php
require_once("../db/db.php");
class contacto_model{
    private $db;
    private $contacto;
	var $vQuery;
 
    public function __construct(){
        $this->db=Conectar::conexion();
        $this->contacto=array();
    }
    public function get_contacto(){
        $consulta=$this->db->query("select * from contacto;");
        while($filas=$consulta->fetch_assoc()){
            $this->contacto[]=$filas;
        }
        return $this->contacto;
    }

	public function set_contacto($sTabla, $vValores, $sCampos=NULL){
		$sInsert="";
		if ($sCampos==NULL):
			$sInsert = "INSERT INTO {$sTabla} VALUES({$vValores});";			
		else:
			$sInsert = "INSERT INTO {$sTabla} ({$sCampos}) VALUES ({$vValores});";
		endif;
		//echo $sInsert;
		
		$this->vQuery = $this->db->query($sInsert);
		return $this->vQuery;
	}
}
?>