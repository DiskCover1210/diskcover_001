<?php
require_once("../db/db.php");
class entidad_model{
    private $db;
    private $contacto;
	private $ID_Entidad     ="";
  private $Entidad        ="";
 


  public $Mensaje        ="";
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
	
	//devuelve empresas asociadas a la entidad del usuario
	function getEntidades($id_entidad=null){
		if($id_entidad!=null)
		{
			$consulta=$this->db->query("SELECT * FROM `entidad` `ID_Empresa`='".$id_entidad."' ;");
		}
		else
		{
			$consulta=$this->db->query("SELECT * FROM `entidad` ;");
		}
        while($filas=$consulta->fetch_assoc()){
            $entidades[]=$filas;
        }
		//pendiente colocar los campos
        return $entidades;
	}
}
?>