
<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("panel.php");
require_once("chequear_seguridad_e.php");
require_once("../controlador/entidad.php");
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="migra/style.css" />
<script src="migra/animateprogress.js"></script>
<script type="text/javascript" src="migra/jquery-3.3.1.min.js"></script>


<?php
	?>
	<div class="row">
		<div class="col-xs-12">
			<div class="box-header">
				<table width="100%">
					<tr>
						<td>
							<h4 class="box-title">
								<a class="btn btn-default"  data-toggle="tooltip" title="Salir del modulo" href="panel.php?sa=s">
									<i ><img src="../../img/png/salir.png" class="user-image" alt="User Image"
									style='font-size:20px; display:block; height:100%; width:100%;'></i> 
								</a>
							</h4>
						</td>
					</tr>
				</table>
			</div>
			<div class="box">
						<h1 align="center"><p>Re-indexar <?php if (isset($_POST['base_mi'])) { echo $_POST['base_mi'];} ?></p></h1>
						
							<form role="form" enctype="multipart/form-data" action="reindexar.php" method="POST">
								<div class="box-body">
									 <div class="col-md-5">
									  <div class="form-group">
										<label for="exampleInputEmail1">Servidor Mysql </label>
										<input type="text" class="form-control" id="host_my" placeholder="Servidor Mysql"
										name="host_my">
									  </div>
									  <!-- /.form-group -->
									</div>
								</div>
								<div class="box-body">
									 <div class="col-md-5">
									  <div class="form-group">
										<label for="exampleInputEmail1">Base de datos Mysql</label>
										<input type="text" class="form-control" id="base_my" placeholder="Base de datos Mysql"
										name="base_my">
									  </div>
									  <!-- /.form-group -->
									</div>
								</div>
								<div class="box-body">
									<div class="col-md-5">
									  <div class="form-group">
										<label for="exampleInputEmail1">Usuario Mysql</label>
										<input type="text" class="form-control" id="usuario_my" placeholder="Usuario Mysql"
										name="usuario_my">
									  </div>
									  <!-- /.form-group -->
									</div>
								</div>
								<div class="box-body">
									
									<div class="col-md-5">
									  <div class="form-group">
										<label for="exampleInputEmail1">Clave Mysql</label>
										<input type="text" class="form-control" id="clave_my" placeholder="Clave Mysql"
										name="clave_my">
									  </div>
									  <!-- /.form-group -->
									</div>
								</div>
								<div class="box-body">
									
									 <div class="col-md-5">
									  <div class="form-group">
										<label for="exampleInputEmail1">Puerto Mysql</label>
										<input type="text" class="form-control" id="port_my" placeholder="Puerto Mysql"
										name="port_my">
									  </div>
									  <!-- /.form-group -->
									</div>
								</div>
								<div class="box-body">
									<div class="col-md-5">
										<div class="form-group">
										  <label for="fichero_usuario">Enviar este fichero(OPC):</label>
										  <input type="file" id="fichero_usuario" name="fichero_usuario">
										</div>
									</div>
								</div>
								<div class="box-footer">
									<input type="submit" name="submitweb" class="btn btn-primary" value="Enviar datos" />
								</div>
							</form>
						
				<!-- <form enctype="multipart/form-data" action="" method="POST">
					MAX_FILE_SIZE debe preceder al campo de entrada del fichero 
					<input type="hidden" name="MAX_FILE_SIZE" value="30000" />
					<!-- El nombre del elemento de entrada determina el nombre en el array $_FILES 
					Enviar este fichero: <input name="fichero_usuario" type="file" />
					<input type="submit" name="submitweb" value="Enviar fichero" />
				</form>-->
				
				<script>
					  function realizaProceso(tabla, cantidadreg, cantidadcam, camID, consu1,cam1,i,host_my,
					  usuario_my,clave_my,base_my,port_my,regis)
					  {
							var parametros = {
									"tabla" : tabla,
									"cantidadreg" : cantidadreg,
									"cantidadcam" : cantidadcam,
									"camID" : camID,
									"consu1" : consu1,
									"cam1" : cam1,
									"i" : i,
									
									"host_my" : host_my,
									"usuario_my" : usuario_my,
									"clave_my" : clave_my,
									"base_my" : base_my,
									"port_my" : port_my,
									"regis" : regis
							};
							$.ajax({
									data:  parametros,
									url:   'migra/proceso.php',
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
					$dir_subida = dirname(__FILE__).'/migra'; 
					if(isset($_POST['base_my']))
					{
						$host_my=trim($_POST['host_my']);
						$usuario_my=trim($_POST['usuario_my']);
						$clave_my=trim($_POST['clave_my']);
						$base_my=trim($_POST['base_my']);
						$port_my=':'.trim($_POST['port_my']);
					}
					//caso archivos
					if(isset($_FILES['fichero_usuario']['name']) and !isset($_POST['base_my']))
					{
						//$dir_subida = '/var/www/uploads/';
						//$fichero_subido = $dir_subida."\\" . basename($_FILES['fichero_usuario']['name']);
						$fichero_subido = $dir_subida."/" . basename($_FILES['fichero_usuario']['name']);
						//echo $fichero_subido;
						//die();
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
						$fp = fopen('migra/'.$_FILES['fichero_usuario']['name'], "r");
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
					}
					//echo $server.' '.$base_mi.' '.$user.' '.$password.' '.$database.' '.$host_my.' '.$usuario_my.' '.$clave_my.' '.$base_my.' '.$port_my;
					 ?>
							<table>
								&nbsp;
								<tr>
									<td>
										<p>Servidor Mysql: <b><?php echo $host_my; ?></b></p><br>
									</td>
								</tr>
								
								<tr>
									<td>
										<p>Base de datos Mysql: <b><?php echo $base_my; ?></b></p><br>
									</td>
								</tr>
								<tr>
									<td>
										<p>Usuario Mysql: <b><?php echo $usuario_my; ?></b></p><br>
									</td>
								</tr>
								<tr>
									<td>
										<p>Clave Mysql: <b><?php echo $clave_my; ?></b></p><br>
									</td>
									<td>
										<p>Puerto: <b><?php echo $port_my; ?></b></p><br>
									</td>
								</tr>
							</table>
							<form enctype="multipart/form-data" action="" method="POST">
								<input type="hidden" name="host_my" value="<?php echo $host_my; ?>" />
								<input type="hidden" name="base_my" value="<?php echo $base_my; ?>" />
								<input type="hidden" name="usuario_my" value="<?php echo $usuario_my; ?>" />
								<input type="hidden" name="clave_my" value="<?php echo $clave_my; ?>" />
								<input type="hidden" name="port_my" value="<?php echo $port_my; ?>" />
								<input type="submit" name="submitweb1" value="Procesar" />
								<!--<input type="submit" name="submitweb2" value="Verificar" />
								<input type="submit" name="submitweb3" value="Verificar Detalle" />-->
							</form>
						
					<?php
				}
				//procesar migracion
				if(isset($_POST['submitweb1'])) 
				{
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
					
					//$cid = odbc_connect("Driver={SQL Server}; Server=".$server."; Database=".$database.";", $user, $password);

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
					//echo $host_my.$port_my.' '.$usuario_my.' '.$clave_my;
					$conexion=new mysqli($host_my.$port_my, $usuario_my, $clave_my);
					$conexion->query("SET NAMES 'utf8'");
					
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
					//SELECT * from Information_Schema.Tables where TABLE_TYPE = 'BASE TABLE'
					$sql = "SELECT * FROM INFORMATION_SCHEMA.tables where TABLE_SCHEMA='".$base_my."' ";
					//echo $sql;
					$consulta = $conexion->query($sql);
					$i=0;
					if ($consulta == TRUE) {
						while($filas=$consulta->fetch_array())
						{
							$tabla[$i]['tabla']=$filas['TABLE_NAME'];
							//echo $filas['TABLE_NAME'].' ';
							$i++;
						}
						//echo " ha sido creado".'<br>';
					} else {
						echo "Hubo un error en la consulta : " . $consulta->error.'<br>';
						die();
					}
					
					$tablas=$i;
					
					//cantidad de campos
					
					 ?>
					 <p>Indexando Tablas</p>
					<progress id="php" max="<?php echo $tablas; ?>" value="0"></progress>
							<span></span>
					<?php
					//echo " dddd ";
					//die();
					$ii=0;
					$i1=0;
					$campos=array();
					for($i=0; $i < count($tabla); $i++)
					{
						$entro=0;
						//cantidad de registros 
						$sql = "SELECT * 
						FROM INFORMATION_SCHEMA.COLUMNS
						WHERE table_name = '".$tabla[$i]['tabla']."'
						AND table_schema = '".$base_my."'";
						//echo $sql;
						$consulta = $conexion->query($sql);
						//echo '<br>'.' TABLA: '.$tabla[$i]['tabla'].'<br>';
						if ($consulta == TRUE) 
						{
							while($filas=$consulta->fetch_array())
							{
								if($filas['COLUMN_NAME']=='Codigo' OR $filas['COLUMN_NAME']=='RUC_CI' OR $filas['COLUMN_NAME']=='CI_RUC' 
								OR $filas['COLUMN_NAME']=='CodigoC' or $filas['COLUMN_NAME']=='Codigo_Inv' or $filas['COLUMN_NAME']=='Cliente' 
								OR $filas['COLUMN_NAME']=='Factura' OR $filas['COLUMN_NAME']=='Numero' OR $filas['COLUMN_NAME']=='Cta')
								{
									//echo '<br>'.' TABLA: '.$tabla[$i]['tabla'].'<br>';
									//echo $filas['COLUMN_NAME'].' ';
									$sql="ALTER TABLE ".$tabla[$i]['tabla']." DROP INDEX `".$filas['COLUMN_NAME']."`;";
									//echo $sql.'<br>';
									$consulta1 = $conexion->query($sql);
									if ($consulta === TRUE) 
									{
										$sql="ALTER TABLE ".$tabla[$i]['tabla']." ADD INDEX (".$filas['COLUMN_NAME'].");";
										$consulta1 = $conexion->query($sql);
										//echo $sql.'<br>';
									}
									else 
									{
										$sql="ALTER TABLE ".$tabla[$i]['tabla']." ADD INDEX (".$filas['COLUMN_NAME'].");";
										//echo $sql.'<br>';
										$consulta1 = $conexion->query($sql);
										//echo "Hubo un error en la consulta : " . $consulta1->error.'<br>';
									}
								}
								if($filas['COLUMN_NAME']=='ID')
								{
									//echo '<br>'.' TABLA: '.$tabla[$i]['tabla'].'<br>';
									//echo $filas['COLUMN_NAME'].' '.$filas['COLUMN_KEY'];
									$sql="ALTER TABLE ".$tabla[$i]['tabla']." DROP PRIMARY KEY";
									$consulta1 = $conexion->query($sql);
									if ($consulta === TRUE) 
									{
										$sql="ALTER TABLE ".$tabla[$i]['tabla']." ADD PRIMARY KEY (".$filas['COLUMN_NAME'].");";
										$consulta1 = $conexion->query($sql);
									}
									else 
									{
										$sql="ALTER TABLE ".$tabla[$i]['tabla']." ADD PRIMARY KEY (".$filas['COLUMN_NAME'].");";
										$consulta1 = $conexion->query($sql);
										//echo "Hubo un error en la consulta : " . $consulta->error.'<br>';
									}
								}
							}
							//echo " ha sido creado".'<br>';
						} 
						else 
						{
							echo "Hubo un error en la consulta : " . $consulta->error.'<br>';
							die();
						}
						
						 ?>
						<script type="text/javascript"> 
							animateprogress("#php",<?php echo $tablas; ?>,<?php echo $i; ?>,'<?php echo $tabla[$i]['tabla']; ?>');
						</script>
						<?php
					}
						echo "<br>
							<p>tablas indexadas en: ".$base_my." </p>
							<br>";
					//die();
					$conexion->close();
					
				}
					?>
			</div>
		</div>
	</div>