
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="style.css" />
<script src="animateprogress.js"></script>
<script type="text/javascript" src="jquery-3.3.1.min.js"></script>

<?php
	?>
	<div class="center">
		 
			
			<h1 align="center"><p>Migracion</p></h1>
			<div class="progress">
	<form enctype="multipart/form-data" action="" method="POST">
		<!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
		<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
		<!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
		Enviar este fichero: <input name="fichero_usuario" type="file" />
		<input type="submit" name="submitweb" value="Enviar fichero" />
	</form>
	
	<script>
		  function realizaProceso(tabla, cantidadreg, cantidadcam, camID, consu1,cam1,i,base_mi,server,user,password,database,host_my,
		  usuario_my,clave_my,base_my,port_my)
		  {
				var parametros = {
						"tabla" : tabla,
						"cantidadreg" : cantidadreg,
						"cantidadcam" : cantidadcam,
						"camID" : camID,
						"consu1" : consu1,
						"cam1" : cam1,
						"i" : i,
						"base_mi" : base_mi,
						"server" : server,
						"user" : user,
						"password" : password,
						"database" : database,
						"host_my" : host_my,
						"usuario_my" : usuario_my,
						"clave_my" : clave_my,
						"base_my" : base_my,
						"port_my" : port_my
				};
				$.ajax({
						data:  parametros,
						url:   'proceso.php',
						type:  'post',
						beforeSend: function () {
								$("#resultado"+tabla).html("Procesando, espere por favor... "+tabla);
						},
						success:  function (response) {
								$("#resultado"+tabla).html(response);
						}
				});
		}
	</script>
					
	<?php
	
	if(isset($_POST['submitweb'])) 
	{
		$dir_subida = dirname(__FILE__); 
		//$dir_subida = '/var/www/uploads/';
		$fichero_subido = $dir_subida."\\" . basename($_FILES['fichero_usuario']['name']);
		//echo $fichero_subido;
		echo '<pre>';
		if (move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $fichero_subido)) {
			echo "El fichero es válido y se subió con éxito.\n";
		} else {
			echo "¡Posible ataque de subida de ficheros!\n";
		}

		//echo 'Más información de depuración:';
		//print_r($_FILES);

		print "</pre>";
		//inicializamos variables
		$usuario = "";
		$clave="";
		//$base_mi="diskcover_system";
		$base_mi="";
		//local
		$server="";
		$user="";
		$password="";
		//$database="diskcover_system";
		$database="";
		$host_my="";
		$usuario_my="";
		$clave_my="";
		$base_my="";
		$port_my="";
		$fp = fopen($_FILES['fichero_usuario']['name'], "r");
		//contador 
		$i=0;
		while(!feof($fp)) {

			$linea = fgets($fp);

			//echo $i.' '.$linea . "<br />";
			//url sql server
			if($i==8)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='Data Source')
				{
					$longitud_cad = strlen($linea1[1]); 
					$server = substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo url sql server";
				}
			}
			//base de datos sql server
			if($i==9)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='Initial Catalog')
				{
					$longitud_cad = strlen($linea1[1]); 
					$base_mi = substr_replace($linea1[1],"",$longitud_cad-3,1); 
					$database = substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo base de datos sql server";
				}
			}
			//usuario sql server
			if($i==11)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='UID')
				{
					$longitud_cad = strlen($linea1[1]); 
					$user = substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo usuario sql server";
				}
			}
			//clave sql server
			if($i==12)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='PWD')
				{
					$longitud_cad = strlen($linea1[1]); 
					$password = substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo clave sql server";
				}
			}
			//url mysql
			if($i==16)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='SERVER')
				{
					$longitud_cad = strlen($linea1[1]); 
					$host_my = substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo url mysql";
				}
			}
			//base de datos mysql
			if($i==17)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='DATABASE')
				{
					$longitud_cad = strlen($linea1[1]); 
					$base_my = substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo base de datos  mysql";
				}
			}
			//usuario mysql
			if($i==19)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='UID')
				{
					$longitud_cad = strlen($linea1[1]); 
					$usuario_my = substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo usuario mysql";
				}
			}
			//clave mysql
			if($i==20)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='PWD')
				{
					$longitud_cad = strlen($linea1[1]); 
					$clave_my = substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo clave mysql";
				}
			}
			//clave mysql
			if($i==21)
			{
				$linea1 = explode("=", $linea);
				if($linea1[0]=='PORT')
				{
					$longitud_cad = strlen($linea1[1]); 
					$port_my = ':'.substr_replace($linea1[1],"",$longitud_cad-3,1); 
				}
				else
				{
					echo "ERROR leyendo puerto mysql";
				}
			}
			$i++;
		}
		fclose($fp);
		//echo $server.' '.$base_mi.' '.$user.' '.$password.' '.$database.' '.$host_my.' '.$usuario_my.' '.$clave_my.' '.$base_my.' '.$port_my;
		 ?>
				<table>
					&nbsp;
					<tr>
						<td>
							<p>Servidor sql server: <b><?php echo $server; ?></b> </p><br>
						</td>
						<td>
							<p>Servidor Mysql: <b><?php echo $host_my; ?></b></p><br>
						</td>
					</tr>
					
					<tr>
						<td>
							<p>Base de datos sql server: <b><?php echo $base_mi; ?></b></p><br>
						</td>
						<td>
							<p>Base de datos Mysql: <b><?php echo $base_my; ?></b></p><br>
						</td>
					</tr>
					<tr>
						<td>
							<p>Usuario sql server: <b><?php echo $user; ?></b></p><br>
						</td>
						<td>
							<p>Usuario Mysql: <b><?php echo $usuario_my; ?></b></p><br>
						</td>
					</tr>
					<tr>
						<td>
							<p>Clave sql server: <b><?php echo $password; ?></b></p><br>
						</td>
						<td>
							<p>Clave Mysql: <b><?php echo $clave_my; ?></b></p><br>
						</td>
						<td>
							<p>Puerto: <b><?php echo $port_my; ?></b></p><br>
						</td>
					</tr>
				</table>
				<form enctype="multipart/form-data" action="" method="POST">
					<input type="hidden" name="server" value="<?php echo $server; ?>" />
					<input type="hidden" name="host_my" value="<?php echo $host_my; ?>" />
					<input type="hidden" name="base_my" value="<?php echo $base_my; ?>" />
					<input type="hidden" name="base_mi" value="<?php echo $base_mi; ?>" />
					<input type="hidden" name="user" value="<?php echo $user; ?>" />
					<input type="hidden" name="usuario_my" value="<?php echo $usuario_my; ?>" />
					<input type="hidden" name="clave_my" value="<?php echo $clave_my; ?>" />
					<input type="hidden" name="password" value="<?php echo $password; ?>" />
					<input type="hidden" name="port_my" value="<?php echo $port_my; ?>" />
					<input type="submit" name="submitweb1" value="Procesar" />
					<input type="submit" name="submitweb2" value="Verificar" />
					<input type="submit" name="submitweb3" value="Verificar Detalle" />
				</form>
			
		<?php
	}
	//procesar migracion
	if(isset($_POST['submitweb1'])) 
	{
		
		$base_mi=trim($_POST['base_mi']);
		$server=trim($_POST['server']);
		$user=trim($_POST['user']);
		$password=trim($_POST['password']);
		$database=trim($_POST['base_mi']);
		$host_my=trim($_POST['host_my']);
		$usuario_my=trim($_POST['usuario_my']);
		$clave_my=trim($_POST['clave_my']);
		$base_my=trim($_POST['base_my']);
		$port_my=trim($_POST['port_my']);
		//echo $server.' '.$base_mi.' '.$user.' '.$password.' '.$database.' '.$host_my.' '.$usuario_my.' '.$clave_my.' '.$base_my;
		/*//para sql server
		$dsn = "sistema"; 
		//debe ser de sistema no de usuario
		$usuario = "sa";
		$clave="Dlcjvl1210";
		$base_mi="diskcover_system";
		//$base_mi="DiskCover_prueba";
		//local
		$server="SISTEMAS";
		$user="sa";
		$password="Dlcjvl1210";
		$database="diskcover_system";
		//$database="DiskCover_prueba";

		//$server="mysql.diskcoversystem.com";
		//$user="sa";
		//$password="disk2017Cover";
		//$database="DiskCover_Prismanet";
		//$base_mi="DiskCover_Prismanet";
		//192.168.2.109 */
		$cid = odbc_connect("Driver={SQL Server}; Server=".$server."; Database=".$database.";", $user, $password);

		//realizamos la conexion mediante odbc
		//$cid=odbc_connect($dsn, $usuario, $clave);
		//conexion de mysql local
		/*$host_my="localhost";
		$usuario_my="root";
		$clave_my="";
		$base_my="diskcover_system";*/

		//conexion de mysql server
		//$host_my="mysql.diskcoversystem.com";
		//$usuario_my="rootDiskcover";
		//$clave_my="Dlcjvl1210@";
		//$base_my="DiskCover_Prismanet";
		//$conexion=new mysqli($host_my, $usuario_my, $clave_my, $base_my);
		$conexion=new mysqli($host_my.$port_my, $usuario_my, $clave_my);
		$conexion->query("SET NAMES 'utf8'");
		if (!$cid){
			exit("<strong>Ya ocurrio un error tratando de conectarse con el origen de datos.</strong>");
		}
		//creando base de datos
		$sql = 'CREATE DATABASE IF NOT EXISTS '.$base_my.'';
		if ($conexion->query($sql)) {
			echo "La base de datos mi_bd se creó correctamente\n";
		} else {
			echo 'Error al crear la base de datos: ' . $conexion->error . "\n";
		}
		$conexion->close();
		//nueva comexion a base de datos creada
		$conexion=new mysqli($host_my.$port_my, $usuario_my, $clave_my, $base_my);
		$conexion->query("SET NAMES 'utf8'");
		// consulta SQL a nuestra tabla "usuarios" que se encuentra en la base de datos "db.mdb"
		//$sql="Select * from Empresas";

		// generamos la tabla mediante odbc_result_all(); utilizando borde 1
		//$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
		//print odbc_result_all($result,"border=1");
		//$tablelist=odbc_tables($cid);
		//$tablelist=odbc_result_all($tablelist);

		$result = odbc_tables($cid);

		$tables = array();
		$i=0;
		$ii=0;
		$tabla=array();
		while (odbc_fetch_row($result))
		{
			if(odbc_result($result,"TABLE_TYPE")=="TABLE")
			{
				$tabla1=odbc_result($result,"TABLE_NAME");
				$tabla[$i]['tabla']=$tabla1;
				$i++;
				//echo"<br>".$i.'-'.odbc_result($result,"TABLE_NAME");
			}
			if(odbc_result($result,"TABLE_TYPE")=="VIEW")
			{
				$ii++;
				//echo"<br>".$ii.'-'.odbc_result($result,"TABLE_NAME");
			}
		}
		//cantidad de campos
		$tablas=$i;
		 ?>
		 <p>Creando Tablas</p>
		<progress id="php" max="<?php echo $tablas; ?>" value="0"></progress>
				<span></span>
		<?php
		//echo " dddd ";
		//die();
		$ii=0;
		for($i=0; $i < count($tabla); $i++)
		{
			$entro=0;
			//cantidad de registros 
			$sql="SELECT        TOP (1) sys.sysindexes.rows
				FROM   sys.sysindexes INNER JOIN
				sys.sysobjects ON sys.sysindexes.id = sys.sysobjects.id
				WHERE   (sys.sysobjects.xtype = 'U') AND (sys.sysobjects.name = '".$tabla[$i]['tabla']."')
				ORDER BY sys.sysindexes.indid";
				$result=odbc_exec($cid,$sql);
				while(odbc_fetch_row($result)){
					for($j=1;$j<=odbc_num_fields($result);$j++){
						$canreg=odbc_result($result,$j);
						$tabla[$i]['canreg']=$canreg;
						//echo $tabla[$i]['canreg'].'<br>';
						//echo ' bbb '.$i.'<br>';
						$entro=1;
					}
				}
				if($entro==0)
				{
					//echo $tabla[$i]['canreg']=0;
				}
				//echo ' ccc '.$i.'<br>';
			if($tabla[$i]['tabla']!='sysdiagramsname' and $tabla[$i]['tabla']!='sysdiagramsdefinition' and $tabla[$i]['tabla']!='Tipo_AccessTIddeReplica' 
			and $tabla[$i]['tabla']!='sysdiagrams' and $tabla[$i]['tabla']!='trace_xe_action_map' 
			AND $tabla[$i]['tabla']!='trace_xe_event_map')
			{
				$ii=0;
				//cantidad de columnas
				$outval = odbc_columns($cid, $base_mi, "%", $tabla[$i]['tabla'], "%");
				$pages = array();
				while (odbc_fetch_into($outval, $pages)) 
				{
					$ii++;
				}
				$tabla[$i]['canti']=$ii;
				
			}
		}
		//die();
		$consu='';
		$campos=array();;
		$i1=0;
		for($i=0; $i < count($tabla); $i++)
		{
			if($tabla[$i]['tabla']!='sysdiagramsname' and $tabla[$i]['tabla']!='sysdiagramsdefinition' and $tabla[$i]['tabla']!='Tipo_AccessTIddeReplica' 
			and $tabla[$i]['tabla']!='sysdiagrams' and $tabla[$i]['tabla']!='trace_xe_action_map' AND $tabla[$i]['tabla']!='trace_xe_event_map')
			{
				//echo "<br>".$tabla[$i]['tabla']." ".$i." dededed ";
				//echo "<br>".$tabla[$i]['tabla']." <br> ";
				//temporal
				
				$tabla1='';
				$ban1=0;
				$outval = odbc_columns($cid, $base_mi, "%", $tabla[$i]['tabla'], "%");
				$pages = array();
				$consu1='';
				$consu1=$consu1.' DROP TABLE IF EXISTS `'.$tabla[$i]['tabla'].'` ';
				if ($conexion->query($consu1) === TRUE) {
					//echo " ha sido creado".'<br>';
				} else {
					echo "Hubo un error al eliminar la tabla : ".$tabla[$i]['tabla']." ".$consu1." " . $conexion->error.'<br>';
					die();
				}
				$consu1='';
				$consu=$consu.' CREATE TABLE IF NOT EXISTS `'.$tabla[$i]['tabla'].'` ( ';
				$consu1=$consu1.' CREATE TABLE IF NOT EXISTS `'.$tabla[$i]['tabla'].'` ( ';
				$ii=0;
				while (odbc_fetch_into($outval, $pages)) {
						//var_dump($pages);
						//if($pages[5]!='int identity' and $pages[5]!='nvarchar' and $pages[5]!='ntext' and $pages[5]!='tinyint'
						//and $pages[5]!='real' and $pages[5]!='bit' and $pages[5]!='smalldatetime' and $pages[5]!='money'
						//  and $pages[5]!='int' and $pages[5]!='float' and $pages[5]!='smallint')
						//{
							//echo "<br>".$tabla[$i]['tabla']."  ";
							//echo $pages[3]. ' '.$pages[5]. ' '.$pages[6]. ' '.$pages[10]. ' '."<br />\n";
						//}
						//echo $ii.' ddd '.$tabla[$i]['canti'].' ';
						if($tabla1=='')
						{
							$tabla1=$tabla[$i]['tabla'];
						}
						if($tabla1==$tabla[$i]['tabla'])
						{
							if ($pages[3]=='ID')
							{
								$ban1=1;
							}
						}
						$pages[3]=utf8_encode($pages[3]);
						if($pages[3]=='Div')
						{
							$pages[3]='`Div`';
						}
						if($pages[3]=='TEntero Largo')
						{
							$pages[3]='TEntero_Largo';
						}
						$campos[$i1]['tabla']=$tabla[$i]['tabla'];
						$campos[$i1]['campo']=$pages[3];
						$i1++;
						if ($pages[3]=='ID')
						{
							//echo $pages[3]. ' '.$pages[5]. ' '.$pages[6]. ' '.$pages[10]. ' '."<br />\n";	
							//echo "int(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY";
							//echo $ii.' eee '.$tabla[$i]['canti'].' ';
							if($ii==($tabla[$i]['canti']-1))
							{
								$consu=$consu.$pages[3].' int(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY ';
								$consu1=$consu1.$pages[3].' int(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY ';
							}
							else
							{
								$consu=$consu.$pages[3].' int(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY, ';
								$consu1=$consu1.$pages[3].' int(11) NOT NULL AUTO_INCREMENT  PRIMARY KEY, ';
							}
						}
						if($pages[5]=='int identity')
						{
							//echo "<br>".$tabla[$i]['tabla']."  ".$pages[3].' --- ';
							//echo "int NOT NULL AUTO_INCREMENT";
						}
						if($pages[5]=='nvarchar')
						{
							//echo "VARCHAR";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' VARCHAR('.$pages[6].') ';
									$consu1=$consu1.$pages[3].' VARCHAR('.$pages[6].') ';
								}
								else
								{
									$consu=$consu.$pages[3].' VARCHAR('.$pages[6].'), ';
									$consu1=$consu1.$pages[3].' VARCHAR('.$pages[6].'), ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' VARCHAR('.$pages[6].') NOT NULL ';
									$consu1=$consu1.$pages[3].' VARCHAR('.$pages[6].') NOT NULL ';
								}
								else
								{
									$consu=$consu.$pages[3].' VARCHAR('.$pages[6].') NOT NULL, ';
									$consu1=$consu1.$pages[3].' VARCHAR('.$pages[6].') NOT NULL, ';
								}
							}
						}
						if($pages[5]=='ntext')
						{
							//echo "MEDIUMTEXT";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' MEDIUMTEXT ';
									$consu1=$consu1.$pages[3].' MEDIUMTEXT ';
								}
								else
								{
									$consu=$consu.$pages[3].' MEDIUMTEXT, ';
									$consu1=$consu1.$pages[3].' MEDIUMTEXT, ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' MEDIUMTEXT NOT NULL ';
									$consu1=$consu1.$pages[3].' MEDIUMTEXT NOT NULL ';
								}
								else
								{
									$consu=$consu.$pages[3].' MEDIUMTEXT NOT NULL, ';
									$consu1=$consu1.$pages[3].' MEDIUMTEXT NOT NULL, ';
								}
							}
						}
						if($pages[5]=='tinyint')
						{
							//echo "INT";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' INT ';
									$consu1=$consu1.$pages[3].' INT ';
								}
								else
								{
									$consu=$consu.$pages[3].' INT, ';
									$consu1=$consu1.$pages[3].' INT, ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' INT NOT NULL ';
									$consu1=$consu1.$pages[3].' INT NOT NULL ';
								}
								else
								{
									$consu=$consu.$pages[3].' INT NOT NULL, ';
									$consu1=$consu1.$pages[3].' INT NOT NULL, ';
								}
							}
						}
						if($pages[5]=='real')
						{
							//echo "DECIMAL(14,2)";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) ';
								}
								else
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2), ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2), ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00" ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00" ';
								}
								else
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00", ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00", ';
								}
							}
						}
						if($pages[5]=='bit')
						{
							//echo "TINYINT";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' TINYINT ';
									$consu1=$consu1.$pages[3].' TINYINT ';
								}
								else
								{
									$consu=$consu.$pages[3].' TINYINT, ';
									$consu1=$consu1.$pages[3].' TINYINT, ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' TINYINT NOT NULL ';
									$consu1=$consu1.$pages[3].' TINYINT NOT NULL ';
								}
								else
								{
									$consu=$consu.$pages[3].' TINYINT NOT NULL, ';
									$consu1=$consu1.$pages[3].' TINYINT NOT NULL, ';
								}
							}
						}
						if($pages[5]=='smalldatetime')
						{
							//echo 'DATETIME()';
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' DATETIME ';
									$consu1=$consu1.$pages[3].' DATETIME ';
								}
								else
								{
									$consu=$consu.$pages[3].' DATETIME, ';
									$consu1=$consu1.$pages[3].' DATETIME, ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' DATETIME NOT NULL ';
									$consu1=$consu1.$pages[3].' DATETIME NOT NULL ';
								}
								else
								{
									$consu=$consu.$pages[3].' DATETIME NOT NULL, ';
									$consu1=$consu1.$pages[3].' DATETIME NOT NULL, ';
								}
							}
						}
						if($pages[5]=='money')
						{
							//echo "DECIMAL(14,2)";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) ';
								}
								else
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2), ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2), ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00" ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00" ';
								}
								else
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00", ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00", ';
								}
							}
						}
						if($pages[5]=='int')
						{
							//echo "INT";
							if ($pages[3]!='ID')
							{
								if($pages[10]=='1')
								{
									if($ii==($tabla[$i]['canti']-1))
									{
										$consu=$consu.$pages[3].' INT ';
										$consu1=$consu1.$pages[3].' INT ';
									}
									else
									{
										$consu=$consu.$pages[3].' INT, ';
										$consu1=$consu1.$pages[3].' INT, ';
									}
								}
								else
								{
									if($ii==($tabla[$i]['canti']-1))
									{
										$consu=$consu.$pages[3].' INT NOT NULL ';
										$consu1=$consu1.$pages[3].' INT NOT NULL ';
									}
									else
									{
										$consu=$consu.$pages[3].' INT NOT NULL, ';
										$consu1=$consu1.$pages[3].' INT NOT NULL, ';
									}
								}
							}
						}
						if($pages[5]=='float')
						{
							//echo "DECIMAL(14,2)";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) ';
								}
								else
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2), ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2), ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00"';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00"';
								}
								else
								{
									$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00", ';
									$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL default "0.00", ';
								}
							}
						}
						if($pages[5]=='smallint')
						{
							//echo "SMALLINT";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' SMALLINT ';
									$consu1=$consu1.$pages[3].' SMALLINT ';
								}
								else
								{
									$consu=$consu.$pages[3].' SMALLINT, ';
									$consu1=$consu1.$pages[3].' SMALLINT, ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' SMALLINT NOT NULL ';
									$consu1=$consu1.$pages[3].' SMALLINT NOT NULL ';
								}
								else
								{
									$consu=$consu.$pages[3].' SMALLINT NOT NULL, ';
									$consu1=$consu1.$pages[3].' SMALLINT NOT NULL, ';
								}
							}
						}
						if($pages[5]=='uniqueidentifier')
						{
							//echo "LONGTEXT";
							if($pages[10]=='1')
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' LONGTEXT ';
									$consu1=$consu1.$pages[3].' LONGTEXT ';
								}
								else
								{
									$consu=$consu.$pages[3].' LONGTEXT, ';
									$consu1=$consu1.$pages[3].' LONGTEXT, ';
								}
							}
							else
							{
								if($ii==($tabla[$i]['canti']-1))
								{
									$consu=$consu.$pages[3].' LONGTEXT NOT NULL ';
									$consu1=$consu1.$pages[3].' LONGTEXT NOT NULL ';
								}
								else
								{
									$consu=$consu.$pages[3].' LONGTEXT NOT NULL, ';
									$consu1=$consu1.$pages[3].' LONGTEXT NOT NULL, ';
								}
							}
						}
						$ii++;
						 
				}
				// presents all fields of the array $pages in a new line until the array pointer reaches the end of array data
						 ?>

						<script type="text/javascript"> 
							
								animateprogress("#php",<?php echo $tablas; ?>,<?php echo $i; ?>,'<?php echo $tabla[$i]['tabla']; ?>');
							
							//document.querySelector ('#boton').addEventListener ('click', function() { 
							//	animateprogress("#php",72);
							//}
							//);
						</script>
							<?php
				//sin ID
				if($ban1==0)
				{
					//echo $tabla[$i]['tabla'].' SIN ID <br>';
					//echo "int NOT NULL AUTO_INCREMENT";
				}
				$consu=$consu.' ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1; ';
				$consu1=$consu1.' ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1; ';
				if ($conexion->query($consu1) === TRUE) {
					//echo " ha sido creado".'<br>';
				} else {
					echo "Hubo un error al crear la tabla : ".$tabla[$i]['tabla']." ".$consu1." " . $conexion->error.'<br>';
					die();
				}
				
				//echo $consu1;
			//die();
			}
			//echo $consu;
			//die();
		}
		//if (!$conexion->query($consu)) 
		//{
		//	echo "Errormessage: ". $conexion->error;
		//}

		//$conexion->query($consu);
			echo "<br>
				<p>tablas creadas en: ".$base_my." </p>
				<br>
				<p>Insertando registros en: </p>";
		//echo $consu;
		//para columnas de tablas
		//$consulta = "select * from Empresas";
		// $resposta = odbc_exec($cid, $consulta);
		// print odbc_result_all($resposta,"border=1");
		//for($i=1; $i <= odbc_num_fields($resposta); $i++)
		//{
			//echo "<br>".odbc_field_name($resposta, $i)." - ".odbc_field_name($resposta, $i)."";
		//}
		
		
		for($i=0; $i < count($tabla); $i++)
		{
			$consu1='';
			if($tabla[$i]['tabla']!='sysdiagramsname' and $tabla[$i]['tabla']!='sysdiagramsdefinition' and $tabla[$i]['tabla']!='Tipo_AccessTIddeReplica' 
			and $tabla[$i]['tabla']!='sysdiagrams' and $tabla[$i]['tabla']!='trace_xe_action_map' 
			AND $tabla[$i]['tabla']!='trace_xe_event_map')
			{
				$cantidad1=0;
				if($tabla[$i]['tabla']=='Trans_Documentos')
				{
					$ini1=100;
					$fin=100;
					//echo " entro die";
					//die();
				}
				else
				{
					if($tabla[$i]['tabla']=='Trans_Notas_Auxiliares' OR $tabla[$i]['tabla']=='Trans_Notas')
					{
						$ini1=1000;
						$fin=1000;
					}
					else
					{
						$ini1=1000;
						$fin=1000;
					}
				}
				$cantidad=$tabla[$i]['canreg']/$ini1;
				$inicio=0;
				
				
				//INSERT INTO `acceso_empresa` (`Modulo`, `Item`, `Codigo`, `ID`) VALUES ('1', '1', '1', '1');
				$consu1=$consu1."INSERT INTO ".$tabla[$i]['tabla']." (";
				$cam1="";
				//$cam2=" VALUES ";
				$jj=0;
				$camID=0;
					for($j=0;$j<count($campos);$j++)
					{
						
						//echo $tabla[$i]['tabla'].' -- '.$campos[$j]['tabla'].'<br>';
						
						if($tabla[$i]['tabla']==$campos[$j]['tabla'])
						{
							if($campos[$j]['campo']=='ID')
							{
								$camID=1;
								//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
							}
							if($campos[$j]['campo']=='`Div`')
							{
								$camID=1;
								//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
							}
							if($jj==($tabla[$i]['canti']-1))
							{
								//buscar ñ
								$cadena_buscada   = 'Ã±';
								$texto1=0;
								$posicion_coincidencia = strpos(utf8_encode($campos[$j]['campo']), $cadena_buscada);
								//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
								if ($posicion_coincidencia === false) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
								} else {
									//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
									//sustituir cadena
									$texto = str_replace("Ã±", "ñ", utf8_encode($campos[$j]['campo']));
									$consu1=$consu1." ".$texto.")";
									$texto1=1;
								}
								if ($texto1 == 0) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
								}
								/*if('AÃ±oRet'==utf8_encode($campos[$j]['campo']))
								{
									$consu1=$consu1." ".'AñoRet'.")";
								}
								else
								{
									$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
								}*/
								//caso palabra reservada
								if($campos[$j]['campo']=='`Div`')
								{
									$cam1=$cam1." ".utf8_decode('Div');
									//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
								}
								else
								{
									if($campos[$j]['campo']=='TEntero_Largo')
									{
										$cam1=$cam1." ".utf8_decode('TEntero Largo');
									}
									else
									{
										$cam1=$cam1." ".utf8_decode($campos[$j]['campo']);
									}
								}
								//echo '<br>'.$j.' 222 '.$jj.' 222 '.$tabla[$i]['canti'].' 222 '.$consu1.' 222 '.' 222 '.$cam1.'<br>';
							}
							else
							{
								//buscar ñ
								$texto1=0;
								$cadena_buscada   = 'Ã±';
								$posicion_coincidencia = strpos(utf8_encode($campos[$j]['campo']), $cadena_buscada);
								//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
								if ($posicion_coincidencia === false) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								} else {
									//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
									//sustituir cadena
									$texto = str_replace("Ã±", "ñ", utf8_encode($campos[$j]['campo']));
									$consu1=$consu1." ".$texto.",";
									$texto1=1;
								}
								if ($texto1 == 0) {
									//echo "NO se ha encontrado la palabra deseada!!!!";
									$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								}
								/*if('AÃ±oRet'==utf8_encode($campos[$j]['campo']))
								{
									$consu1=$consu1." ".'AñoRet'.",";
								}
								else
								{
									$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
								}*/
								
								if($campos[$j]['campo']=='`Div`')
								{
									$cam1=$cam1." ".utf8_decode('Div').",";
									//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
								}
								else
								{
									if($campos[$j]['campo']=='TEntero_Largo')
									{
										$cam1=$cam1." ".utf8_decode('TEntero Largo').",";
									}
									else
									{
										$cam1=$cam1." ".utf8_decode($campos[$j]['campo']).",";
									}
								}
								//echo '<br>'.$j.' 111 '.$jj.' 111 '.$tabla[$i]['canti'].' 111 '.$consu1.' 111 '.' 111 '.$cam1.'<br>';
							}
							$jj++;
						}
						//echo "campos ".$campos[$j]['tabla'].'<br>';
						//echo "campos ".$campos[$j]['campo'].'<br>';	
					}
					//si no existe ID ejecutar toda la consulta
					if($camID==0)
					{
						$cantidad=1;
					}
					$jj1=0;
					//echo utf8_encode($cam1).' ---ppp ';
					?>
					<div id="resultado<?php echo $tabla[$i]['tabla']; ?>">
			
					</div>
					<script type="text/javascript">
						realizaProceso('<?php echo $tabla[$i]['tabla']; ?>', '<?php echo $tabla[$i]['canreg']; ?>', '<?php echo $tabla[$i]['canti']; ?>',
						'<?php echo $camID; ?>', '<?php echo $consu1; ?>','<?php echo  utf8_encode($cam1); ?>','<?php echo $i; ?>','<?php echo $base_mi; ?>'
						,'<?php echo $server; ?>','<?php echo $user; ?>','<?php echo $password; ?>','<?php echo $database; ?>','<?php echo $host_my; ?>',
						'<?php echo $usuario_my; ?>','<?php echo $clave_my; ?>','<?php echo $base_my; ?>','<?php echo $port_my; ?>');
					</script>
					<?php
					//comparamos si estan todos los registros
					/*$regis1=0;
					$sql="SELECT count(*) AS cont FROM ".$tabla[$i]['tabla']." ";
					$consulta=$conexion->query($sql);
					$filas=$consulta->fetch_assoc();
					$regis = $filas['cont'];
					echo $filas['cont'].' nnnn ';
					if($tabla[$i]['canreg']> $regis)
					{
						$regis1=1;
					}
					/*while($filas=$consulta->fetch_assoc())
					{
						
					}*/
					/*echo $tabla[$i]['canreg'].' nnnn '. $regis;
					//volver a ingresar
					if($regis1==1)
					{
						echo "incompleto";
						die();
					}*/
			}
		}
		?>
		<!--<div id="resultado">
			
		</div>-->
		<?php
	    //die();
		$conexion->close();
		
	}
	//comparamos las tablas
	
		//procesar migracion
	if(isset($_POST['submitweb2'])) 
	{
		$usuario = "";
		$clave="";
		//$base_mi="diskcover_system";
		$base_mi="";
		//local
		$server="";
		$user="";
		$password="";
		//$database="diskcover_system";
		$database="";
		$host_my="";
		$usuario_my="";
		$clave_my="";
		$base_my="";
		$base_mi=trim($_POST['base_mi']);
		$server=trim($_POST['server']);
		$user=trim($_POST['user']);
		$password=trim($_POST['password']);
		$database=trim($_POST['base_mi']);
		$host_my=trim($_POST['host_my']);
		$usuario_my=trim($_POST['usuario_my']);
		$clave_my=trim($_POST['clave_my']);
		$base_my=trim($_POST['base_my']);
		$port_my=trim($_POST['port_my']);
		$cid = odbc_connect("Driver={SQL Server}; Server=".$server."; Database=".$database.";", $user, $password);

		$conexion=new mysqli($host_my.$port_my, $usuario_my, $clave_my, $base_my);
		$conexion->query("SET NAMES 'utf8'");
		//contamos tablas
		$result = odbc_tables($cid);

		$tables = array();
		$i=0;
		$ii=0;
		$tabla=array();
		while (odbc_fetch_row($result))
		{
			if(odbc_result($result,"TABLE_TYPE")=="TABLE")
			{
				$tabla1=odbc_result($result,"TABLE_NAME");
				$tabla[$i]['tabla']=$tabla1;
				$i++;
				//echo"<br>".$i.'-'.odbc_result($result,"TABLE_NAME");
			}
			if(odbc_result($result,"TABLE_TYPE")=="VIEW")
			{
				$ii++;
				//echo"<br>".$ii.'-'.odbc_result($result,"TABLE_NAME");
			}
		}
		//cantidad de campos
		$tablas=$i;
		//cantidad de campor y registros de la tabla
		$ii=0;
		$campos=array();;
		$i1=0;
		for($i=0; $i < count($tabla); $i++)
		{
			$entro=0;
			//cantidad de registros 
			$sql="SELECT        TOP (1) sys.sysindexes.rows
				FROM   sys.sysindexes INNER JOIN
				sys.sysobjects ON sys.sysindexes.id = sys.sysobjects.id
				WHERE   (sys.sysobjects.xtype = 'U') AND (sys.sysobjects.name = '".$tabla[$i]['tabla']."')
				ORDER BY sys.sysindexes.indid";
				$result=odbc_exec($cid,$sql);
				while(odbc_fetch_row($result)){
					for($j=1;$j<=odbc_num_fields($result);$j++){
						$canreg=odbc_result($result,$j);
						$tabla[$i]['canreg']=$canreg;
						//echo $tabla[$i]['canreg'].'<br>';
						//echo ' bbb '.$i.'<br>';
						$entro=1;
					}
				}
				if($entro==0)
				{
					//echo $tabla[$i]['canreg']=0;
				}
				//echo ' ccc '.$i.'<br>';
			if($tabla[$i]['tabla']!='sysdiagramsname' and $tabla[$i]['tabla']!='sysdiagramsdefinition' and $tabla[$i]['tabla']!='Tipo_AccessTIddeReplica' 
			and $tabla[$i]['tabla']!='sysdiagrams' and $tabla[$i]['tabla']!='trace_xe_action_map' 
			AND $tabla[$i]['tabla']!='trace_xe_event_map')
			{
				$ii=0;
				//cantidad de columnas
				$outval = odbc_columns($cid, $base_mi, "%", $tabla[$i]['tabla'], "%");
				$pages = array();
				while (odbc_fetch_into($outval, $pages)) 
				{
					if($tabla1=='')
					{
						$tabla1=$tabla[$i]['tabla'];
					}
					if($tabla1==$tabla[$i]['tabla'])
					{
						if ($pages[3]=='ID')
						{
							$ban1=1;
						}
					}
					$pages[3]=utf8_encode($pages[3]);
					if($pages[3]=='Div')
					{
						$pages[3]='`Div`';
					}
					if($pages[3]=='TEntero Largo')
					{
						$pages[3]='TEntero_Largo';
					}
					$campos[$i1]['tabla']=$tabla[$i]['tabla'];
					$campos[$i1]['campo']=$pages[3];
					$i1++;
					$ii++;
				}
				$tabla[$i]['canti']=$ii;
				
			}
		}
		//recorremos la tabla
		$i1=0;
		for($i=0; $i < count($tabla); $i++)
		{
			if($tabla[$i]['tabla']!='sysdiagramsname' and $tabla[$i]['tabla']!='sysdiagramsdefinition' and $tabla[$i]['tabla']!='Tipo_AccessTIddeReplica' 
			and $tabla[$i]['tabla']!='sysdiagrams' and $tabla[$i]['tabla']!='trace_xe_action_map' 
			AND $tabla[$i]['tabla']!='trace_xe_event_map')
			{
				$consu1='';
				$regis1=0;
				$sql="SELECT count(*) AS cont FROM ".$tabla[$i]['tabla']." ";
				$consulta=$conexion->query($sql);
				$filas=$consulta->fetch_assoc();
				$regis = $filas['cont'];
				//echo $filas['cont'].' nnnn ';
				if($tabla[$i]['canreg']> $regis)
				{
					$regis1=1;
				}
				/*while($filas=$consulta->fetch_assoc())
				{
					
				}*/
				//echo $tabla[$i]['canreg'].' nnnn '. $regis;
				//volver a ingresar
				if($regis1==1)
				{
					$sql="TRUNCATE TABLE ".$tabla[$i]['tabla']." ";
					$consulta=$conexion->query($sql);
					$i1++;
					//echo "<p style='color:#FF0000;'>".$tabla[$i]['canreg'].' nnnn '. $regis." incompleto ".$i1." ".$tabla[$i]['tabla'].' Procesando...<br></p>';
					echo "<p style='color:#FF0000;'>Registros (".$tabla[$i]['canreg'].") incompleto ".$i1." ".$tabla[$i]['tabla'].' Procesando...<br></p>';
					$cantidad1=0;
					if($tabla[$i]['tabla']=='Trans_Documentos')
					{
						$ini1=100;
						$fin=100;
						//echo " entro die";
						//die();
					}
					else
					{
						if($tabla[$i]['tabla']=='Trans_Notas_Auxiliares' OR $tabla[$i]['tabla']=='Trans_Notas')
						{
							$ini1=1000;
							$fin=1000;
						}
						else
						{
							$ini1=1000;
							$fin=1000;
						}
					}
					$cantidad=$tabla[$i]['canreg']/$ini1;
					$inicio=0;
					
					
					//INSERT INTO `acceso_empresa` (`Modulo`, `Item`, `Codigo`, `ID`) VALUES ('1', '1', '1', '1');
					$consu1=$consu1."INSERT INTO ".$tabla[$i]['tabla']." (";
					$cam1="";
					//$cam2=" VALUES ";
					$jj=0;
					$camID=0;
						for($j=0;$j<count($campos);$j++)
						{
							
							//echo $tabla[$i]['tabla'].' -- '.$campos[$j]['tabla'].'<br>';
							
							if($tabla[$i]['tabla']==$campos[$j]['tabla'])
							{
								if($campos[$j]['campo']=='ID')
								{
									$camID=1;
									//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
								}
								if($campos[$j]['campo']=='`Div`')
								{
									$camID=1;
									//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
								}
								if($jj==($tabla[$i]['canti']-1))
								{
									//buscar ñ
									$cadena_buscada   = 'Ã±';
									$texto1=0;
									$posicion_coincidencia = strpos(utf8_encode($campos[$j]['campo']), $cadena_buscada);
									//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
									if ($posicion_coincidencia === false) {
										//echo "NO se ha encontrado la palabra deseada!!!!";
										//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
									} else {
										//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
										//sustituir cadena
										$texto = str_replace("Ã±", "ñ", utf8_encode($campos[$j]['campo']));
										$consu1=$consu1." ".$texto.")";
										$texto1=1;
									}
									if ($texto1 == 0) {
										//echo "NO se ha encontrado la palabra deseada!!!!";
										$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
									}
									/*if('AÃ±oRet'==utf8_encode($campos[$j]['campo']))
									{
										$consu1=$consu1." ".'AñoRet'.")";
									}
									else
									{
										$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
									}*/
									//caso palabra reservada
									if($campos[$j]['campo']=='`Div`')
									{
										$cam1=$cam1." ".utf8_decode('Div');
										//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
									}
									else
									{
										if($campos[$j]['campo']=='TEntero_Largo')
										{
											$cam1=$cam1." ".utf8_decode('TEntero Largo');
										}
										else
										{
											$cam1=$cam1." ".utf8_decode($campos[$j]['campo']);
										}
									}
									//echo '<br>'.$j.' 222 '.$jj.' 222 '.$tabla[$i]['canti'].' 222 '.$consu1.' 222 '.' 222 '.$cam1.'<br>';
								}
								else
								{
									//buscar ñ
									$texto1=0;
									$cadena_buscada   = 'Ã±';
									$posicion_coincidencia = strpos(utf8_encode($campos[$j]['campo']), $cadena_buscada);
									//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
									if ($posicion_coincidencia === false) {
										//echo "NO se ha encontrado la palabra deseada!!!!";
										//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
									} else {
										//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
										//sustituir cadena
										$texto = str_replace("Ã±", "ñ", utf8_encode($campos[$j]['campo']));
										$consu1=$consu1." ".$texto.",";
										$texto1=1;
									}
									if ($texto1 == 0) {
										//echo "NO se ha encontrado la palabra deseada!!!!";
										$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
									}
									/*if('AÃ±oRet'==utf8_encode($campos[$j]['campo']))
									{
										$consu1=$consu1." ".'AñoRet'.",";
									}
									else
									{
										$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
									}*/
									
									if($campos[$j]['campo']=='`Div`')
									{
										$cam1=$cam1." ".utf8_decode('Div').",";
										//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
									}
									else
									{
										if($campos[$j]['campo']=='TEntero_Largo')
										{
											$cam1=$cam1." ".utf8_decode('TEntero Largo').",";
										}
										else
										{
											$cam1=$cam1." ".utf8_decode($campos[$j]['campo']).",";
										}
									}
									//echo '<br>'.$j.' 111 '.$jj.' 111 '.$tabla[$i]['canti'].' 111 '.$consu1.' 111 '.' 111 '.$cam1.'<br>';
								}
								$jj++;
							}
							//echo "campos ".$campos[$j]['tabla'].'<br>';
							//echo "campos ".$campos[$j]['campo'].'<br>';	
						}
						//si no existe ID ejecutar toda la consulta
						if($camID==0)
						{
							$cantidad=1;
						}
						$jj1=0;
						//echo utf8_encode($cam1).' ---ppp ';
						//if($tabla[$i]['tabla']=='Facturas_Formatos')
						//{
						?>
							<div id="resultado<?php echo $tabla[$i]['tabla']; ?>">
					
							</div>
							<script type="text/javascript">
								realizaProceso('<?php echo $tabla[$i]['tabla']; ?>', '<?php echo $tabla[$i]['canreg']; ?>', '<?php echo $tabla[$i]['canti']; ?>',
								'<?php echo $camID; ?>', '<?php echo $consu1; ?>','<?php echo  utf8_encode($cam1); ?>','<?php echo $i; ?>','<?php echo $base_mi; ?>'
								,'<?php echo $server; ?>','<?php echo $user; ?>','<?php echo $password; ?>','<?php echo $database; ?>','<?php echo $host_my; ?>',
								'<?php echo $usuario_my; ?>','<?php echo $clave_my; ?>','<?php echo $base_my; ?>','<?php echo $port_my; ?>');
							</script>
						<?php
						//}
					//die();
				}
				else
				{
					//echo "completo ".$tabla[$i]['tabla'].'<br>';
				}
			}
		}
	}
		//procesar migracion
	if(isset($_POST['submitweb3'])) 
	{
		$usuario = "";
		$clave="";
		//$base_mi="diskcover_system";
		$base_mi="";
		//local
		$server="";
		$user="";
		$password="";
		//$database="diskcover_system";
		$database="";
		$host_my="";
		$usuario_my="";
		$clave_my="";
		$base_my="";
		$base_mi=trim($_POST['base_mi']);
		$server=trim($_POST['server']);
		$user=trim($_POST['user']);
		$password=trim($_POST['password']);
		$database=trim($_POST['base_mi']);
		$host_my=trim($_POST['host_my']);
		$usuario_my=trim($_POST['usuario_my']);
		$clave_my=trim($_POST['clave_my']);
		$base_my=trim($_POST['base_my']);
		$port_my=trim($_POST['port_my']);
		$cid = odbc_connect("Driver={SQL Server}; Server=".$server."; Database=".$database.";", $user, $password);

		$conexion=new mysqli($host_my.$port_my, $usuario_my, $clave_my, $base_my);
		$conexion->query("SET NAMES 'utf8'");
		//contamos tablas
		$result = odbc_tables($cid);

		$tables = array();
		$i=0;
		$ii=0;
		$tabla=array();
		while (odbc_fetch_row($result))
		{
			if(odbc_result($result,"TABLE_TYPE")=="TABLE")
			{
				$tabla1=odbc_result($result,"TABLE_NAME");
				$tabla[$i]['tabla']=$tabla1;
				$i++;
				//echo"<br>".$i.'-'.odbc_result($result,"TABLE_NAME");
			}
			if(odbc_result($result,"TABLE_TYPE")=="VIEW")
			{
				$ii++;
				//echo"<br>".$ii.'-'.odbc_result($result,"TABLE_NAME");
			}
		}
		//cantidad de campos
		$tablas=$i;
		//cantidad de campor y registros de la tabla
		$ii=0;
		$campos=array();;
		$i1=0;
		for($i=0; $i < count($tabla); $i++)
		{
			$entro=0;
			//cantidad de registros 
			$sql="SELECT        TOP (1) sys.sysindexes.rows
				FROM   sys.sysindexes INNER JOIN
				sys.sysobjects ON sys.sysindexes.id = sys.sysobjects.id
				WHERE   (sys.sysobjects.xtype = 'U') AND (sys.sysobjects.name = '".$tabla[$i]['tabla']."')
				ORDER BY sys.sysindexes.indid";
				$result=odbc_exec($cid,$sql);
				while(odbc_fetch_row($result)){
					for($j=1;$j<=odbc_num_fields($result);$j++){
						$canreg=odbc_result($result,$j);
						$tabla[$i]['canreg']=$canreg;
						//echo $tabla[$i]['tabla'].' '.$tabla[$i]['canreg'].'<br>';
						//echo ' bbb '.$i.'<br>';
						$entro=1;
					}
				}
				if($entro==0)
				{
					//echo $tabla[$i]['canreg']=0;
				}
				//echo ' ccc '.$i.'<br>';
			if($tabla[$i]['tabla']!='sysdiagramsname' and $tabla[$i]['tabla']!='sysdiagramsdefinition' and $tabla[$i]['tabla']!='Tipo_AccessTIddeReplica' 
			and $tabla[$i]['tabla']!='sysdiagrams' and $tabla[$i]['tabla']!='trace_xe_action_map' 
			AND $tabla[$i]['tabla']!='trace_xe_event_map')
			{
				$ii=0;
				//cantidad de columnas
				$outval = odbc_columns($cid, $base_mi, "%", $tabla[$i]['tabla'], "%");
				$pages = array();
				while (odbc_fetch_into($outval, $pages)) 
				{
					if($tabla1=='')
					{
						$tabla1=$tabla[$i]['tabla'];
					}
					if($tabla1==$tabla[$i]['tabla'])
					{
						if ($pages[3]=='ID')
						{
							$ban1=1;
						}
					}
					$pages[3]=utf8_encode($pages[3]);
					if($pages[3]=='Div')
					{
						$pages[3]='`Div`';
					}
					if($pages[3]=='TEntero Largo')
					{
						$pages[3]='TEntero_Largo';
					}
					$campos[$i1]['tabla']=$tabla[$i]['tabla'];
					$campos[$i1]['campo']=$pages[3];
					$i1++;
					$ii++;
				}
				$tabla[$i]['canti']=$ii;
				
			}
		}
		//recorremos la tabla
		$i1=0;
		for($i=0; $i < count($tabla); $i++)
		{
			if($tabla[$i]['tabla']!='sysdiagramsname' and $tabla[$i]['tabla']!='sysdiagramsdefinition' and $tabla[$i]['tabla']!='Tipo_AccessTIddeReplica' 
			and $tabla[$i]['tabla']!='sysdiagrams' and $tabla[$i]['tabla']!='trace_xe_action_map' 
			AND $tabla[$i]['tabla']!='trace_xe_event_map')
			{
				$consu1='';
				$regis1=0;
				$sql="SELECT count(*) AS cont FROM ".$tabla[$i]['tabla']." ";
				$consulta=$conexion->query($sql);
				$filas=$consulta->fetch_assoc();
				$regis = $filas['cont'];
				//echo $filas['cont'].' nnnn ';
				if($tabla[$i]['canreg']> $regis)
				{
					$regis1=1;
				}
				/*while($filas=$consulta->fetch_assoc())
				{
					
				}*/
				//echo $tabla[$i]['canreg'].' nnnn '. $regis;
				//volver a ingresar
				if($regis1==1)
				{
					$sql="TRUNCATE TABLE ".$tabla[$i]['tabla']." ";
					$consulta=$conexion->query($sql);
					$i1++;
					//echo "<p style='color:#FF0000;'>".$tabla[$i]['canreg'].' nnnn '. $regis." incompleto ".$i1." ".$tabla[$i]['tabla'].' Procesando...<br></p>';
					echo "<p style='color:#FF0000;'>Registros (".$tabla[$i]['canreg'].") incompleto ".$i1." ".$tabla[$i]['tabla'].' Procesando...<br></p>';
					$cantidad1=0;
					if($tabla[$i]['tabla']=='Trans_Documentos')
					{
						$ini1=100;
						$fin=100;
						//echo " entro die";
						//die();
					}
					else
					{
						if($tabla[$i]['tabla']=='Trans_Notas_Auxiliares' OR $tabla[$i]['tabla']=='Trans_Notas')
						{
							$ini1=1000;
							$fin=1000;
						}
						else
						{
							$ini1=1000;
							$fin=1000;
						}
					}
					$cantidad=$tabla[$i]['canreg']/$ini1;
					$inicio=0;
					
					
					//INSERT INTO `acceso_empresa` (`Modulo`, `Item`, `Codigo`, `ID`) VALUES ('1', '1', '1', '1');
					$consu1=$consu1."INSERT INTO ".$tabla[$i]['tabla']." (";
					$cam1="";
					//$cam2=" VALUES ";
					$jj=0;
					$camID=0;
						for($j=0;$j<count($campos);$j++)
						{
							
							//echo $tabla[$i]['tabla'].' -- '.$campos[$j]['tabla'].'<br>';
							
							if($tabla[$i]['tabla']==$campos[$j]['tabla'])
							{
								if($campos[$j]['campo']=='ID')
								{
									$camID=1;
									//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
								}
								if($campos[$j]['campo']=='`Div`')
								{
									$camID=1;
									//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
								}
								if($jj==($tabla[$i]['canti']-1))
								{
									//buscar ñ
									$cadena_buscada   = 'Ã±';
									$texto1=0;
									$posicion_coincidencia = strpos(utf8_encode($campos[$j]['campo']), $cadena_buscada);
									//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
									if ($posicion_coincidencia === false) {
										//echo "NO se ha encontrado la palabra deseada!!!!";
										//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
									} else {
										//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
										//sustituir cadena
										$texto = str_replace("Ã±", "ñ", utf8_encode($campos[$j]['campo']));
										$consu1=$consu1." ".$texto.")";
										$texto1=1;
									}
									if ($texto1 == 0) {
										//echo "NO se ha encontrado la palabra deseada!!!!";
										$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
									}
									/*if('AÃ±oRet'==utf8_encode($campos[$j]['campo']))
									{
										$consu1=$consu1." ".'AñoRet'.")";
									}
									else
									{
										$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).")";
									}*/
									//caso palabra reservada
									if($campos[$j]['campo']=='`Div`')
									{
										$cam1=$cam1." ".utf8_decode('Div');
										//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
									}
									else
									{
										if($campos[$j]['campo']=='TEntero_Largo')
										{
											$cam1=$cam1." ".utf8_decode('TEntero Largo');
										}
										else
										{
											$cam1=$cam1." ".utf8_decode($campos[$j]['campo']);
										}
									}
									//echo '<br>'.$j.' 222 '.$jj.' 222 '.$tabla[$i]['canti'].' 222 '.$consu1.' 222 '.' 222 '.$cam1.'<br>';
								}
								else
								{
									//buscar ñ
									$texto1=0;
									$cadena_buscada   = 'Ã±';
									$posicion_coincidencia = strpos(utf8_encode($campos[$j]['campo']), $cadena_buscada);
									//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
									if ($posicion_coincidencia === false) {
										//echo "NO se ha encontrado la palabra deseada!!!!";
										//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
									} else {
										//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
										//sustituir cadena
										$texto = str_replace("Ã±", "ñ", utf8_encode($campos[$j]['campo']));
										$consu1=$consu1." ".$texto.",";
										$texto1=1;
									}
									if ($texto1 == 0) {
										//echo "NO se ha encontrado la palabra deseada!!!!";
										$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
									}
									/*if('AÃ±oRet'==utf8_encode($campos[$j]['campo']))
									{
										$consu1=$consu1." ".'AñoRet'.",";
									}
									else
									{
										$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
									}*/
									
									if($campos[$j]['campo']=='`Div`')
									{
										$cam1=$cam1." ".utf8_decode('Div').",";
										//echo $tabla[$i]['tabla'].' '.$campos[$j]['campo'].' -- ID '.'<br>';
									}
									else
									{
										if($campos[$j]['campo']=='TEntero_Largo')
										{
											$cam1=$cam1." ".utf8_decode('TEntero Largo').",";
										}
										else
										{
											$cam1=$cam1." ".utf8_decode($campos[$j]['campo']).",";
										}
									}
									//echo '<br>'.$j.' 111 '.$jj.' 111 '.$tabla[$i]['canti'].' 111 '.$consu1.' 111 '.' 111 '.$cam1.'<br>';
								}
								$jj++;
							}
							//echo "campos ".$campos[$j]['tabla'].'<br>';
							//echo "campos ".$campos[$j]['campo'].'<br>';	
						}
						//si no existe ID ejecutar toda la consulta
						if($camID==0)
						{
							$cantidad=1;
						}
						$jj1=0;
						//echo utf8_encode($cam1).' ---ppp ';
						//if($tabla[$i]['tabla']=='Facturas_Formatos')
						//{
						?>
							<div id="resultado<?php echo $tabla[$i]['tabla']; ?>">
					
							</div>
							<button onclick="realizaProceso('<?php echo $tabla[$i]['tabla']; ?>', '<?php echo $tabla[$i]['canreg']; ?>', '<?php echo $tabla[$i]['canti']; ?>',
								'<?php echo $camID; ?>', '<?php echo $consu1; ?>','<?php echo  utf8_encode($cam1); ?>','<?php echo $i; ?>','<?php echo $base_mi; ?>'
								,'<?php echo $server; ?>','<?php echo $user; ?>','<?php echo $password; ?>','<?php echo $database; ?>','<?php echo $host_my; ?>',
								'<?php echo $usuario_my; ?>','<?php echo $clave_my; ?>','<?php echo $base_my; ?>','<?php echo $port_my; ?>');">
									procesar <?php echo $tabla[$i]['tabla']; ?>
							</button>
							
						<?php
						//}
					//die();
				}
				else
				{
					//echo "completo ".$tabla[$i]['tabla'].'<br>';
				}
			}
		}
	}
		//$resposta = odbc_exec($cid, $consulta);
		//resource $connection_id , string $pk_qualifier , string $pk_owner , string $pk_table , string $fk_qualifier , string $fk_owner , string $fk_table
		//$resposta = odbc_foreignkeys (  $cid ,null, 'dbo', 'Empresas',null, 'dbo', null ); 
		//$result = odbc_foreignkeys($cid,"tutorialspoint", "dbo", "Categories", "Northwind", "dbo", "Products");
		// while ($rr = odbc_fetch_array($resposta)) {
		//    var_dump($rr);
		//}
		//print odbc_result_all($resposta,"border=1");
		//$procelist=odbc_procedures($cid);
		//$procelist=odbc_result_all($procelist);
		//$result = odbc_procedures($cid); 
		//DESKTOP-9DECCP5
		?>
			</div>
		</div>