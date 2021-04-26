<?php
//Llamada al modelo
require_once("../modelo/usuario_model.php");

if(isset($_POST['submitlog'])) 
{
	login('', '', '');
}
//devuelve empresas asociadas al usuario
function getEmpresas($id_entidad)
{
	$per=new usuario_model();
	$empresa=$per->getEmpresas($id_entidad);
	// print_r($empresa);die();
	return $empresa;
}
//devuelve empresas seleccionada por el usuario
function getEmpresasId($id_empresa)
{
	//echo ' dd '.$id_empresa;
	$per=new usuario_model();
	$empresa=$per->getEmpresasId($id_empresa);
	// print_r($empresa);die();
	// print_r($_SESSION); die();
	return $empresa;
}
//devuelve empresas seleccionada por el usuario de mysql sin credenciales sqlserver
function empresa_sin_creenciales_sqlserver($id_empresa)
{
	//echo ' dd '.$id_empresa;
	$per=new usuario_model();
	$empresa=$per->getEmpresasId_sin_sqlserver($id_empresa);
	// print_r($empresa);die();
	// print_r($_SESSION); die();
	return $empresa;
}
//devuelve inf del detalle de la empresa seleccionada por el usuario
function getEmpresasDE($item,$nombre)
{
	//echo ' dd '.$id_empresa;
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getEmpresasDESQL($item,$nombre);
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getEmpresasDEMYSQL($item,$nombre);
	}
	
	return $empresa;
}
//perido actual funcion sql server
function getPeriodoActualSQL()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getPeriodoActualSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getPeriodoActualMYSQL();
	}
	
	return $empresa;
}

//obtener datos de usuario  
function getUsuario()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getUsuarioSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getUsuarioMYSQL();
	}
	
	return $empresa;
}
//verificar acceso usuario
function getAccesoEmpresas()
{
	// echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{

	// echo ' dd '.$id_empresa;
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getAccesoEmpresasSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		// echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();		
		$empresa=$per->getAccesoEmpresasMYSQL();
	}
	
	return $empresa;
} 
//consultar modulo
function getModulo()
{
	//echo ' dd '.$id_empresa;
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$per=new usuario_model();
		//hacemos conexion en sql
		$per->conexionSQL();
		$empresa=$per->getModuloSQL();
	}
	//mysql
	if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='MySQL') 
	{
		//echo ' sss '.$_SESSION['INGRESO']['Tipo_Base'];
		$per=new usuario_model();
		
		$empresa=$per->getModuloMYSQL();
	}
	
	return $empresa;
} 

function modulos_habiliatados()
{
	$per=new usuario_model();
	$modulos=$per->modulos_registrados();
	return $modulos;
	
}

function contruir_modulos($modulos)
{
	$mod="";
	$color = array('1'=>'bg-green','2'=>'bg-yellow','3'=>'bg-red','4'=>'bg-aqua');

	$pos = 1;
	foreach ($modulos  as $key => $value) {
		// print_r($value);die();
		$mod .= '<div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box '.$color[$pos].'"  style="border-radius: 10px;">
            <div class="inner"><a href="'.$value['link'].'" style="color: #ffffff;">';
            if(strlen($value['apli'])<9)
            {
            	$mod.= '<h4><b>'.$value['apli'].'</b></h4>';
            }else
            {
            	$mod.= '<h4><b>'.$value['apli'].'</b></h4>';

            }
              $mod.='<p>Modulo</p>
              </a>
            </div>
            <div class="icon">';
            if($value['icono']!='.'){
              $mod.='<i class="ion ion" style="padding-right: 15px;"><img  class="style_prevu_kit" src="'.$value['icono'].'" class="icon" style="display:block;width:100%;margin-top: 35%;"></i>';
            }else
            {
            	$mod.='<i class="ion ion" style="padding-right: 15px;width: 80px;"></i>';
            }

            $mod.='</div>
            <a href="'.$value['link'].'" class="small-box-footer">Click para ingresar <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>';        
        $pos+=1;
        if($pos==5)
        {
        	$pos = 1;
        }

	}
	return $mod;

}
function contruir_todos_modulos()
{

	$per=new usuario_model();
	$modulos=$per->modulos_todos();
	$mod="";
	$color = array('1'=>'bg-green','2'=>'bg-yellow','3'=>'bg-red','4'=>'bg-aqua');
	$pos =1;
	foreach ($modulos  as $key => $value) {
		$mod .= '<div class="col-lg-3 col-xs-6">
		<a href="'.$value['link'].'">
          <!-- small box -->
          <div class="small-box '.$color[$pos].'" style="border-radius: 10px;">
            <div class="inner"><a href="'.$value['link'].'" style="color: #ffffff;">';
            if(strlen($value['Aplicacion'])<9)
            {
            	$mod.= '<h3>'.$value['Aplicacion'].'</h3>';
            }else{
               $mod.= '<h4 style="font-size: 30px;"><b>'.$value['Aplicacion'].'</b></h4>';
            }

           $mod.='<p>Modulo</p>
           <a>
            </div>
            <div class="icon">';
            if($value['icono']!='.'){
              $mod.='<i class="ion ion-plus" style="padding-right: 15px;"><img src="'.$value['icono'].'" class="icon" style="display:block;width:85%;margin-top: 35%;"></i>';
            }else
            {
            	$mod.='<i class="ion ion-plus"></i>';
            }

            $mod.='</div>
          </a>
        </div>';        
        $pos= $pos+1;
        if($pos==5)
        {
        	$pos = 1;
        }
        
	}
	return $mod;

	//style="display:block; height:80%; width:100%;"

}
?>
