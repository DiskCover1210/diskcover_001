<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 */
require_once("../db/db.php");
class usuario_model{
    public $db;
	private $dbs;
    private $contacto;
	private $ID_Entidad     ="";
  private $Entidad        ="";
  private $Nombre_Entidad ="";

	private $ID_Usuario     ="";
  private $Nombre_Usuario ="";
  private $Mail           ="";
  private $Contrasena     ="";
  private $IP_Usuario     ="";
  
  private $Hora           ="";
  private $Fecha          ="";
  public $Mensaje        ="";
	var $vQuery;
 
    public function __construct(){
		//parent::__construct();
        $this->db=Conectar::conexion();
        $this->contacto=array();
    }
	//para conexion sql server
	public function conexionSQL(){
        $this->dbs=Conectar::conexionSQL();
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
	// Metdo devuelve true o false para ingresar a la sesccion de pagina de administracion
	public function Ingresar($Entidad,$Mail,$Pasword){
		$this->Entidad=$Entidad;
		$this->Mail=$Mail;
		$this->Contrasena=$Pasword;
		//determinamos cada uno de los metodos devueltos
	  if($this->ValidarEntidad()==false){
		  $this->Mensaje=$this->Mensaje;
		  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
						$uri = 'https://';
					}else{
						$uri = 'http://';
					}
					$uri .= $_SERVER['HTTP_HOST'];
					//echo $uri;
			echo "<script type='text/javascript'>window.location='".$uri.$_SERVER["REQUEST_URI"]."/../../../php/vista/login.php?men=".$this->Mensaje."'</script>";
			//echo "<script type='text/javascript'>window.location='".$uri."/diskcover/nuevo/index.php?men=".$this->Mensaje."'</script>";
		//echo $this->Mensaje;		  
		}else{
			//echo $this->Mensaje;	
			//die();
			if($this->ValidarUser()==false){
				$this->Mensaje=$this->Mensaje;
				  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
								$uri = 'https://';
							}else{
								$uri = 'http://';
							}
							$uri .= $_SERVER['HTTP_HOST'];
							//echo $uri;
					echo "<script type='text/javascript'>window.location='".$uri.$_SERVER["REQUEST_URI"]."/../../../php/vista/login.php?men=".$this->Mensaje."'</script>";
					//echo "<script type='text/javascript'>window.location='".$uri."/diskcover/nuevo/index.php?men=".$this->Mensaje."'</script>";
			}else{
				//echo $this->Mensaje;	
				if($this->Pasword_usr()==false){
					if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
								$uri = 'https://';
							}else{
								$uri = 'http://';
							}
							$uri .= $_SERVER['HTTP_HOST'];
							//echo $uri;
					echo "<script type='text/javascript'>window.location='".$uri.$_SERVER["REQUEST_URI"]."/../../../php/vista/login.php?men=".$this->Mensaje."'</script>";
					//echo "<script type='text/javascript'>window.location='".$uri."/diskcover/nuevo/index.php?men=".$this->Mensaje."'</script>";
				}else{
					
					//por lo es correcto el logeo realizamos la redireccion
					if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
						$uri = 'https://';
					}else{
						$uri = 'http://';
					}
					$uri .= $_SERVER['HTTP_HOST'];
					//die();
					//Aqui modificar si el pag de aministracion esta 
					//en un subdirectorio
					// "<script type=\"text/javascript\">
					// window.location=\"".$uri."/wp-admin/admin.php\";
					// </script>";
					echo "<script type='text/javascript'>window.location='".$uri.$_SERVER["REQUEST_URI"]."/../../../php/vista/panel.php'</script>";
					//echo "<script type='text/javascript'>window.location='".$uri.$_SERVER["REQUEST_URI"]."/diskcover_php/vista/panel.php'</script>";
				} 
			}        
		}
	}
	private function ValidarEntidad(){
		 $retornar=false;
		 $entidadfilter =preg_match("/^[0-9]{13}$/", $this->Entidad);
		 $entidadfilter1 =preg_match("/^[0-9]{10}$/", $this->Entidad);
		 if ($entidadfilter or $entidadfilter1){
			//Creamos una query sencilla
			$query = "SELECT *
					  FROM entidad
					  WHERE RUC_CI_NIC = '".$this->Entidad."';";
			//echo $query;
					
			$consulta=$this->db->query($query) or die($this->db->error);
			
			//Realizamos un bucle para ir obteniendo los resultados
			while($filas=$consulta->fetch_assoc()){
				$this->ID_Entidad=$filas['ID_Empresa'];
				//echo $filas['ID_Empresa'];
				$this->Nombre_Entidad=$filas['Nombre_Entidad'];
				$this->Mensaje=$this->EscribirMsg("Ok!","Entidad encontrada.");
				
				$retornar=true;
			}
			//die();	
			if($this->ID_Entidad=='')
			{
				$this->Mensaje=$this->EscribirMsg("Error Entidad!","Servidor de datos no econtrado, vuelva a intentar mas tarde.");
			}
			
			// cerrar la conexión
			$consulta->close();
		 }else{
			 //echo " tgtgtg ";
		   //Se muesta al usuario el mensaje de error sobre el formato de la entidad
			 $this->Mensaje=$this->EscribirMsg("Error Entidad!","La entidad que ingresaste no tiene el formato correcto.");
		 }
	return $retornar;
	}
	/*
	 * Validamos la entrada de correo
	 * electronico
	 * @param [String mail]
	 */
	private function ValidarUser(){
		 $retornar=false;
		 $mailfilter =filter_var($this->Mail);//filtramos el correo
		 // Validamos el formato  de correo electronico utilizando expresiones 
	   // regulares:"/[a-zAZ0-9\_\-]+\@[a-zA-Z0-9]+\.[a-zA-Z0-9]/"
	   if ($mailfilter){  
			// Creamos una query sencilla
			$query = "SELECT * 
					  FROM acceso_usuarios 
					  WHERE Usuario = '".$this->Mail."' 
					  AND ID_Empresa = ".$this->ID_Entidad.";";
			// Ejecutamos la query
			$consulta=$this->db->query($query) or die($this->db->error);
			// Realizamos un bucle para ir obteniendo los resultados
			$this->Mail='';
			while($filas=$consulta->fetch_assoc()){
				$this->Mail=$mailfilter;
				$this->Mensaje=$this->EscribirMsg("Ok!","Email/Usuario encontrada.");
				$retornar=true;
			}
			if($this->Mail=='')
			{
				$this->Mensaje=$this->EscribirMsg("Error Usuario!","Servidor de datos no econtrado, vuelva a intentar mas tarde.");
			}
		
			// cerrar la conexión
			$consulta->close();
		 }else{
			   // Se muesta al usuario el mensaje de error sobre el formato de correo
			$this->Mensaje=$this->EscribirMsg("Error Usuario!","El correo/usuario que ingresaste no tiene formato correcto.");    
		 }
	return $retornar;
	}
	/*
	 * Metodo para determinar
	 * la existencia de la contraseña y verificacion 
	 * @param [type] $pasword [ingresar contraseña]
	 */
	private function Pasword_usr(){
		$retornar = false;
		//saneamos la entrada de los caracteres
		$contra = filter_var($this->Contrasena, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_ENCODE_AMP);
		if ($contra==""){
		//si que no existen ningun contraseña mostramos el mensaje de error
		 $this->Mensaje=$this->EscribirMsg("Error!","Escriba su contraseña.");        
		}else{
			//Realizamos la consulta sql a la bd verificamos la contraseña
			
			// Creamos una query sencilla
			$query = "SELECT * 
					  FROM acceso_usuarios 
					  WHERE Usuario = '".$this->Mail."' 
					  AND Clave = '".$this->Contrasena."'
					  AND ID_Empresa = ".$this->ID_Entidad.";";
			// Ejecutamos la query
			//echo $query;
			//die();
			$consulta=$this->db->query($query) or die($this->db->error);
			// Realizamos un bucle para ir obteniendo los resultados
			while($filas=$consulta->fetch_assoc()){
				
				$this->ID_Usuario = ''.$filas['CI_NIC'];
				$this->Nombre_Usuario = $filas['Nombre_Usuario'];
				$this->IP_Usuario = $this->IPuser();
				//Recuperando la hora en el que ingreso
				$this->Hora = date('H:i:s', time());
				$this->Fecha = date("m.d.y");
				//$Clave=Conectar::encryption($this->Contrasena);
				//echo $filas['CI_NIC'].'  '.$this->IP_Usuario;
				//die();
				//echo $this->ID_Usuario.' wwwwwwwwwwwww ';
				//hacemos sesion
					if(!isset($_SESSION)) 
					{ 
							session_start(); 
					}
					else
					{
							session_destroy();
							session_start(); 
					} 
					$_SESSION['Nombre'] = $this->Nombre_Usuario;
					$_SESSION['autentificado'] = "VERDADERO";
					$_SESSION['INGRESO'] = array("Id"      =>''.$this->ID_Usuario,
												 "IP"      =>$this->IP_Usuario,
												 "RUCEnt"  =>$this->Entidad,
												 "Entidad" =>$this->Nombre_Entidad,
												 "Nombre"  =>$this->Nombre_Usuario,
												 "Hora"    =>$this->Hora,
												 "Fecha"   =>$this->Fecha,
												 "Mail"    =>$this->Mail,
												 "Clave"   =>$this->Contrasena,
												 "IDEntidad" =>$this->ID_Entidad,
												 "Cambio" =>$filas['Cambio'],
												 "ID" =>$filas['ID'],
												 "ERROR" =>'1',
												 "Tipo_Usuario" =>$filas['Tipo_Usuario']
												 //"ClaveEncriptada" =>$Clave
												 ); 
				//Asignamos el valor verdadero para retornarlo
				
				$this->Mensaje=$this->EscribirMsg("Ok!","El Usuario se ha registrado correctamente.");
				//echo $_SESSION['Nombre'];
				//die();
				$retornar=true;// se retorna un valor verdadero
			}
			if($this->ID_Usuario =='')
			{
				$this->Mensaje=$this->EscribirMsg("Error!","Contraseña incorrecta escriba nuevamente.");
			}
			
			$consulta->close();
		}
	 return $retornar; //Retornaos el valor true o false
	}
		
	/*
	 * Returna el IP de usuario
	 * @return [string] [devuel la io del usuario]
	 */
	private function IPuser() {
		$returnar ="";
	if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
		 $returnar=$_SERVER['HTTP_X_FORWARDED_FOR'];}
	if (!empty($_SERVER['HTTP_CLIENT_IP'])){
		 $returnar=$_SERVER['HTTP_CLIENT_IP'];}
	if(!empty($_SERVER['REMOTE_ADDR'])){
		 $returnar=$_SERVER['REMOTE_ADDR'];}
	return $returnar;
	}
	public function MostrarMsg(){
		return $this->Mensaje;
	}
		
	private function EscribirMsg($Alerta,$Mensajes){
		/*$MsgStrong = " Swal.fire({
				  type: 'error',
				  title: 'No se pudo realizar sesion',
				  text: '".$Mensajes."'
				});
				";*/
				$MsgStrong = $Mensajes;
	    /*$MsgStrong= "<div class=\'alert alert-danger alert-dismissible fade in\' role=\'alert\'>".
	    "<button type=\'button\' class=\'close\' data-dismiss=\'alert\' aria-label=\'Close\'>".
				   "<span aria-hidden=\'true\'>x</span>".
				   "</button>".
				   "<strong>".$Alerta." </strong>".$Mensajes."".
				   "</div>";*/
	  return $MsgStrong;
	}
		
	function MsgBox($Mensajes){
		echo "<script>alert('".$Mensajes."');</script>";
	}
	//devuelve empresas asociadas a la entidad del usuario

	function getEmpresas($id_entidad){
		$empresa= array();
		 $consulta=$this->db->query("SELECT * 
									 FROM `lista_empresas` 
									 WHERE IP_VPN_RUTA<>'.' 
									 AND Base_Datos<>'.' 
									 AND Usuario_DB<>'.' 
									 AND Contraseña_DB<>'.' 
									 AND Tipo_Base<>'.' 
									 AND Puerto<>'0'									 
									 AND `ID_Empresa`='".$id_entidad."';");
									
        while($filas=$consulta->fetch_assoc()){
            $empresa[]=$filas;
        }
        return $empresa;
	}
	//devuelve empresa seleccionada por id 
	function getEmpresasId($id_empresa){
		/*echo "SELECT * 
					FROM `lista_empresas` 
					WHERE IP_VPN_RUTA<>'.' 
					 AND Base_Datos<>'.' 
					 AND Usuario_DB<>'.' 
					 AND Contraseña_DB<>'.' 
					 AND Tipo_Base<>'.' 
					 AND Puerto<>'0' 
					 AND`ID`=".$id_empresa.";";*/
			$consulta=$this->db->query("SELECT * 
									FROM `lista_empresas` 
									WHERE IP_VPN_RUTA<>'.' 
									 AND Base_Datos<>'.' 
									 AND Usuario_DB<>'.' 
									 AND Contraseña_DB<>'.' 
									 AND Tipo_Base<>'.' 
									 AND Puerto<>'0' 
									 AND`ID`=".$id_empresa.";");
		
		//echo "SELECT * FROM `Lista_Empresas` 
		//							WHERE `ID`=".$id_empresa.";";
		//$filas=$consulta->fetch_assoc();
		//echo $filas['IP_VPN_RUTA'];
		 if ($consulta) {

			/* Obtener la información del campo para todas las columnas */
			$info_campo = $consulta->fetch_fields();
			$i=0;
			foreach ($info_campo as $valor) {
				if($i==15)
				{
					$contra=$valor->name;
				}
				$i++;
			}
		}
        while($filas=$consulta->fetch_assoc()){
            $empresa[]=$filas;
			$_SESSION['INGRESO']['Contraseña_DB']=$filas[$contra];
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $empresa;
	}
	//consultar periodo mysql
	function getPeriodoActualMYSQL($Opcem=null){
		$empresa=array();
		if($Opcem==null)
		{
			$sql="SELECT * 
			FROM Fechas_Balance 
			where detalle='Balance' 
			and Item='".$_SESSION['INGRESO']['item']."' 
			AND periodo='.'";
		}
		else
		{
			//verificamos si es mes o año
			$sql="SELECT * 
			FROM Fechas_Balance 
			where detalle='Balance' 
			and Item='".$_SESSION['INGRESO']['item']."' 
			AND periodo='.'";
			 If ($Opcem=='1')
				  {
					 $sql= $sql."AND Detalle = 'Balance Mes' ";
				  }
				  Else
				  {
					  $sql= $sql."AND Detalle = 'Balance' ";
				  }
		
		}
		//echo $sql;
		$consulta=$this->db->query($sql);
		while($filas=$consulta->fetch_assoc()){
            $empresa[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $empresa;
	}
	//consulta periodo sql server
	function getPeriodoActualSQL($Opcem=null){
		$empresa=array();
		if($Opcem==null)
		{
			$sql="SELECT * 
			FROM Fechas_Balance 
			where detalle='Balance' 
			and Item='".$_SESSION['INGRESO']['item']."' 
			AND periodo='.' ";
		}
		else
		{
			//verificamos si es mes o año
			$sql="SELECT * 
			FROM Fechas_Balance 
			where detalle='Balance' 
			and Item='".$_SESSION['INGRESO']['item']."'  
			AND periodo='.'";
			 If ($Opcem=='1')
				  {
					 $sql= $sql." AND Detalle = 'Balance Mes' ";
				  }
				  Else
				  {
					  $sql= $sql." AND Detalle = 'Balance' ";
				  }
		
		}
		//echo $sql;
		$stmt = false;
		if($this->dbs!='')
		{
			$stmt = sqlsrv_query( $this->dbs, $sql);
		}
		if( $stmt === false)  
		{  
			//echo "Error en consulta PA.\n";  
			 echo "<script>
							/*Swal.fire({
								type: 'error',
								title: 'Fallo',
								text: 'Error en consulta PA.',
								footer: ''
							})*/
							alert('Error en consulta');
					</script>";
			 if($_SESSION['INGRESO']['ERROR']==1)
			 {
				die( print_r( sqlsrv_errors(), true)); 
			 }
			 die();
		} 
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				$cam=date_format($obj->Fecha_Inicial,'Y-m-d H:i:s');
				$empresa[$i]['Fecha_Inicial']=$cam;
				$cam=date_format($obj->Fecha_Final,'Y-m-d H:i:s');
				$empresa[$i]['Fecha_Final']=$cam;
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			if($i==0)
			{
				$_SESSION['INGRESO']['Fechai']='';
				$_SESSION['INGRESO']['Fechaf']='';
			}
			else
			{
				$_SESSION['INGRESO']['Fechai']=$empresa[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechaf']=$empresa[0]['Fecha_Final'];
			}
			sqlsrv_close( $this->dbs );
		}
		
        return $empresa;
	}
	//detalle de la empresa sql server
	function getEmpresasDESQL($item,$nombre){
		$empresa=array();
		if($item!=null and $nombre!=null)
		{
			/*$sql="SELECT * 
			FROM Empresas 
			where Item='".$_SESSION['INGRESO']['item']."' 
			and Empresa='".$_SESSION['INGRESO']['noempr']."'
			 ";*/
			$sql="SELECT * 
			FROM Empresas 
			where Item='".$_SESSION['INGRESO']['item']."' ";
		}
		//echo $sql;
		$stmt = false;
		if($this->dbs!='')
		{
			$stmt = sqlsrv_query( $this->dbs, $sql);
		}
		if( $stmt === false)  
		{  
			 //echo "Error en consulta PA.\n";  
			 echo "<script>
							/*Swal.fire({
								type: 'error',
								title: 'Fallo',
								text: 'Error en consulta PA.',
								footer: ''
							})*/
							alert('Error en consulta');
					</script>";
			 if($_SESSION['INGRESO']['ERROR']==1)
			 {
				die( print_r( sqlsrv_errors(), true)); 
			 }	
			die();			 
		}  
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				$cam=date_format($obj->Fecha,'Y-m-d H:i:s');
				$empresa[$i]['Fecha']=$cam;
				$empresa[$i]['Gerente']=$obj->Gerente;
				$empresa[$i]['Telefono1']=$obj->Telefono1;
				$empresa[$i]['Telefono2']=$obj->Telefono2;
				$empresa[$i]['FAX']= $obj->FAX;
				$empresa[$i]['Direccion']=$obj->Direccion;
				$empresa[$i]['Email']=$obj->Email;
				$empresa[$i]['Contador']=$obj->Contador;
				$empresa[$i]['CI_Representante']=$obj->CI_Representante;
				$empresa[$i]['RUC_Contador']=$obj->RUC_Contador;
				$empresa[$i]['Email_Contabilidad']=$obj->Email_Contabilidad;
				$empresa[$i]['Nombre_Comercial']=$obj->Nombre_Comercial;
				$empresa[$i]['Razon_Social']=$obj->Razon_Social;
				$empresa[$i]['Det_Comp']=$obj->Det_Comp;
				//$empresa[$i]['Sucursal']=$obj->Sucursal;
				//consultar sucursal
				$empresa[$i]['Sucursal']=false;
				$sql="select * from Acceso_Sucursales where Sucursal<>'.'  ";
				$stmt1 = false;
				$ii=0;
				if($this->dbs!='')
				{
					$stmt1 = sqlsrv_query( $this->dbs, $sql);
				}
				while( $obj1 = sqlsrv_fetch_object( $stmt1)) 
				{
					$ii++;
				}
				if($ii>0)
				{
					$empresa[$i]['Sucursal'] = true;
				}
				$empresa[$i]['Opc']=$obj->Opc;
				$empresa[$i]['Empresa']=$obj->Empresa;		
				$empresa[$i]['S_M']=$obj->S_M;	
				$empresa[$i]['Num_CD']=$obj->Num_CD;
				$empresa[$i]['Num_CE']=$obj->Num_CE;
				$empresa[$i]['Num_CI']=$obj->Num_CI;
				$empresa[$i]['Num_ND']=$obj->Num_ND;		
				$empresa[$i]['Num_NC']=$obj->Num_NC;
				$empresa[$i]['Email_Conexion_CE']=$obj->Email_Conexion_CE;
				$empresa[$i]['Formato_Cuentas']=$obj->Formato_Cuentas;
				$empresa[$i]['Obligado_Conta']=$obj->Obligado_Conta;
				$empresa[$i]['Ambiente']=$obj->Ambiente;
				$empresa[$i]['LeyendaFA']=$obj->LeyendaFA;	
				$empresa[$i]['RUC']=$obj->RUC;
				$empresa[$i]['Gerente']=$obj->Gerente;					
							
				//IVA ACTUAL
				$sql="SELECT ROUND((Porc/100), 2) AS porc FROM Tabla_Por_ICE_IVA WHERE IVA <> '0' 
				 AND Fecha_Inicio <= '20200408' AND Fecha_Final >= '20200408'
				 ORDER BY Porc ";
				 $stmt1 = false;
				if($this->dbs!='')
				{
					$stmt1 = sqlsrv_query( $this->dbs, $sql);
				}
				if( $stmt1 === false)  
				{  
					 //echo "Error en consulta PA.\n";  
					 echo "<script>
									/*Swal.fire({
										type: 'error',
										title: 'Fallo',
										text: 'Error en consulta PA.',
										footer: ''
									})*/
									alert('Error en consulta');
							</script>";
					 if($_SESSION['INGRESO']['ERROR']==1)
					 {
						die( print_r( sqlsrv_errors(), true)); 
					 }	
					die();			 
				} 
				else
				{
					while( $row = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
					{
						$empresa[$i]['porc']=$row[0];
					}
				}
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
		
			sqlsrv_close( $this->dbs );
		}
        return $empresa;
	}
	//consultar periodo mysql
	function getEmpresasDEMYSQL($item,$nombre){
		$empresa=array();
		if($item!=null and $nombre!=null)
		{
			$sql="SELECT * 
			FROM Empresas 
			where Item='".$_SESSION['INGRESO']['item']."' 
			and Empresa='".$_SESSION['INGRESO']['noempr']."'
			 ";
		}
		//echo $sql;
		$consulta=$this->db->query($sql);
		while($filas=$consulta->fetch_assoc()){
            $empresa[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $empresa;
	}
	//detalle ddl usuario en sql server
	function getUsuarioSQL()
	{
		$usuario=array();
		$sql="SELECT * FROM Accesos
				WHERE        (Usuario = '".$_SESSION['INGRESO']['Mail']."') 
				AND (Clave = '".$_SESSION['INGRESO']['Clave']."') ";
		
		//echo $sql;
		$stmt = false;
		if($this->dbs!='')
		{
			$stmt = sqlsrv_query( $this->dbs, $sql);
		}
		if( $stmt === false)  
		{  
			 //echo "Error en consulta PA.\n";  
			 echo "<script>
							/*Swal.fire({
								type: 'error',
								title: 'Fallo',
								text: 'Error en consulta PA.',
								footer: ''
							})*/
							alert('Error en consulta');
					</script>";
			 if($_SESSION['INGRESO']['ERROR']==1)
			 {
				die( print_r( sqlsrv_errors(), true)); 
			 }
			 die();
		} 
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				
				$usuario[$i]['CodigoU']=$obj->Codigo;		
				$usuario[$i]['Nombre_Completo']=$obj->Nombre_Completo;	
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			if($i==0)
			{
				$usuario[$i]['CodigoU']='';		
				$usuario[$i]['Nombre_Completo']='';	
			}
			sqlsrv_close( $this->dbs );
		}
        return $usuario;
	}
	//consultar datos usuarios mysql
	function getUsuarioMYSQL($item,$nombre)
	{
		$usuario=array();
		$sql="SELECT    * FROM Accesos
				WHERE        (Usuario = 'WALTER') AND (Clave = '070216')
		 ";
		//echo $sql;
		$consulta=$this->db->query($sql);
		while($filas=$consulta->fetch_assoc()){
            $usuario[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $usuario;
	}
	//verificar acceso usuario
	function getAccesoEmpresasSQL()
	{
		$permiso=array();
		$_SESSION['INGRESO']['modulo']=array();
		$sql="SELECT    * FROM Acceso_Empresa 
				WHERE  Codigo='".$_SESSION['INGRESO']['CodigoU']."' ";
		
		//echo $sql;
		$stmt = false;
		if($this->dbs!='')
		{
			$stmt = sqlsrv_query( $this->dbs, $sql);
		}
		if( $stmt === false)  
		{  
			 //echo "Error en consulta PA.\n";  
			 echo "<script>
							/*Swal.fire({
								type: 'error',
								title: 'Fallo',
								text: 'Error en consulta PA.',
								footer: ''
							})*/
							alert('Error en consulta');
					</script>";
			 if($_SESSION['INGRESO']['ERROR']==1)
			 {
				die( print_r( sqlsrv_errors(), true)); 
			 }
			 die();
		} 
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				//echo "entro 1";
				$permiso[$i]['Modulo']=$obj->Modulo;	
				//echo " mmm ".$permiso[$i]['Modulo'].' ind= '.$i.'<br>';
				$permiso[$i]['Item']=$obj->Item;					
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			//no existe
			if($i==0)
			{
				//echo "entro 2";
				$permiso[$i]['Modulo']='TODOS';
				$permiso[$i]['Item']='TODOS';
				$_SESSION['INGRESO']['accesoe']='TODOS';
				$_SESSION['INGRESO']['modulo'][$i]='TODOS';
			}
			else
			{
				//hacemos ciclo para buscar si puede acceder a la empresa y que modulos
				$j=0;
				for($i=0;$i<count($permiso);$i++)
				{
					if($permiso[$i]['Item']==$_SESSION['INGRESO']['item'])
					{
						//echo $permiso[$i]['Item']." ".$_SESSION['INGRESO']['item']."<br>";
						$_SESSION['INGRESO']['accesoe']='1';
						$_SESSION['INGRESO']['modulo'][$j]=$permiso[$i]['Modulo'];
						//echo ' per '.$permiso[$i]['Modulo'].' '.$_SESSION['INGRESO']['modulo'][$j].' ind= '.$i.'<br>';
						$j++;
					}
				}
			}
			sqlsrv_close( $this->dbs );
		}
		//die();
        return $permiso;
	}
	//consultar datos usuarios mysql
	function getAccesoEmpresasMYSQL()
	{
		$usuario=array();
		$sql="SELECT    * FROM Acceso_Empresa 
				WHERE  Codigo='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."'
		 ";
		//echo $sql;
		$consulta=$this->db->query($sql);
		while($filas=$consulta->fetch_assoc()){
            $usuario[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $usuario;
	}
	//consultar modulo
	function getModuloSQL()
	{
		$permiso=array();
		$sql="SELECT * FROM Modulos
		ORDER BY Aplicacion ";
		
		//echo $sql;
		$stmt = false;
		if($this->dbs!='')
		{
			$stmt = sqlsrv_query( $this->dbs, $sql);
		}
		if( $stmt === false)  
		{  
			 //echo "Error en consulta PA.\n";  
			 echo "<script>
							/*Swal.fire({
								type: 'error',
								title: 'Fallo',
								text: 'Error en consulta PA.',
								footer: ''
							})*/
							alert('Error en consulta');
					</script>";
			 if($_SESSION['INGRESO']['ERROR']==1)
			 {
				die( print_r( sqlsrv_errors(), true)); 
			 }
			 die();
		} 
		else
		{
			$i=0;
			while( $obj = sqlsrv_fetch_object( $stmt)) 
			{
				//echo "entro 1";
				$permiso[$i]['Modulo']=$obj->Modulo;	
				$permiso[$i]['Aplicacion']=$obj->Aplicacion;					
				//echo $empresa[$i]['Opc'].' '.$empresa[$i]['Sucursal'];
				//die(); 			
				//echo $empresa[$i]['Fecha_Inicial'];
				$i++;
			}
			sqlsrv_close( $this->dbs );
		}
		//die();
        return $permiso;
	}
	//consultar datos modulos mysql
	function getModuloMYSQL()
	{
		$usuario=array();
		$sql="SELECT    * FROM Acceso_Empresa 
				WHERE  Codigo='".$_SESSION['INGRESO']['CodigoU']."' AND Item='".$_SESSION['INGRESO']['item']."'
		 ";
		//echo $sql;
		$consulta=$this->db->query($sql);
		while($filas=$consulta->fetch_assoc()){
            $usuario[]=$filas;
			//echo ' vvv '.$filas['IP_VPN_RUTA'];
        }
        return $usuario;
	}
	function cerrarSQLSERVER(){
		sqlsrv_close( $this->dbs );
	}
}
?>