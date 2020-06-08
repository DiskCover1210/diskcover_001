<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<link rel="stylesheet" href="style.css" />
<script src="animateprogress.js"></script>
<script type="text/javascript" src="jquery-3.3.1.min.js"></script>

<?php
	$valor="13T08:16:51.055-05:00 2 1 
	CONGREGACION DE HERMANAS DOMINICAS DE LA INMACULADA CONCEPCION UNIDAD EDUCATIVA PARTICULAR BILINGUE SANTO DOMINGO DE 
	GUZMAN 0990054088001 1304201501099005408800120010050000016260904201612 01 001 005 000001626 CALLE QUINTA Y AV. DE LA MONJAS - URDESA CENTRAL 
	13/04/2015 CALLE QUINTA Y AV. DE LA MONJAS - URDESA CENTRAL SI 05 DARWIN WEIR ALVEAR 0907601256 1969.40 88.65 2 0 1969.40 0.00 0 1880.75 DOLAR 02.02 
	PENSION: 1.00 196.94 0.00 196.94 2 0 0 196.94 0 02.02 PENSION: 1.00 196.94 9.85 187.09 2 0 0 187.09 0 02.02 PENSION: 1.00 196.94 9.85 187.09 2 0 0 
	187.09 0 02.02 PENSION: 1.00 196.94 9.85 187.09 2 0 0 187.09 0 02.02 PENSION: 1.00 196.94 9.85 187.09 2 0 0 187.09 0 02.02 PENSION: 1.00 196.94 9.85 
	187.09 2 0<�";
	$valor = utf8_encode($valor);
									$texto1=1;
									$longitud_cad = strlen($valor); 
									//$valor = substr_replace($valor,"",$longitud_cad-2,1); 
									$valor = str_replace("�", "", utf8_encode($valor));
									//echo ' ccccccc '.$valor;
									//die();
	//para sql server
	$dsn = "sistema"; 
	//debe ser de sistema no de usuario
	$usuario = "sa";
	$clave="Dlcjvl1210";
	//$base_mi="diskcover_system";
	$base_mi="DiskCover_prueba";
	//local
	$server="SISTEMAS";
	$user="sa";
	$password="Dlcjvl1210";
	//$database="diskcover_system";
	$database="DiskCover_prueba";
	
	//$server="mysql.diskcoversystem.com";
	//$user="sa";
	//$password="disk2017Cover";
	//$database="DiskCover_Prismanet";
	//$base_mi="DiskCover_Prismanet";
	//192.168.2.109 
	$cid = odbc_connect("Driver={SQL Server}; Server=".$server."; Database=".$database.";", $user, $password);

	//realizamos la conexion mediante odbc
	//$cid=odbc_connect($dsn, $usuario, $clave);
	//conexion de mysql local
	$host_my="localhost";
	$usuario_my="root";
	$clave_my="";
	$base_my="diskcover_system";
	
	//conexion de mysql server
	//$host_my="mysql.diskcoversystem.com";
	//$usuario_my="rootDiskcover";
	//$clave_my="Dlcjvl1210@";
	//$base_my="DiskCover_Prismanet";
	$conexion=new mysqli($host_my, $usuario_my, $clave_my, $base_my);
    $conexion->query("SET NAMES 'utf8'");
	if (!$cid){
		exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
	}
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
	<div class="center">
	 
		
		<h1 align="center"><p>Migracion</p></h1>
		<div class="progress">
			<p>Servidor</p>
			<br>
			<p>Base de datos</p>
			<br>
			<p>Usuario</p>
			<br>
			<p>Clave</p>
			<br>
			<p>Creando Tablas</p>
			<progress id="php" max="<?php echo $tablas; ?>" value="0"></progress>
			<span></span>
		
		
<?php
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
				echo $tabla[$i]['canreg']=0;
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
								$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL ';
								$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL ';
							}
							else
							{
								$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL, ';
								$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL, ';
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
								$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL ';
								$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL ';
							}
							else
							{
								$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL, ';
								$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL, ';
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
								$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL ';
								$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL ';
							}
							else
							{
								$consu=$consu.$pages[3].' DECIMAL(14,2) NOT NULL, ';
								$consu1=$consu1.$pages[3].' DECIMAL(14,2) NOT NULL, ';
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
	?>
	<div id="car-1">
			<!--<progress id="car" max="<?php echo $tabla[$i]['canreg']; ?>" value="0"></progress>
				<span></span> -->
			
		</div>
	<?php
	for($i=0; $i < count($tabla); $i++)
	{
	?>
	<script type="text/javascript"> 
						
				animateprogress1("#car-1",<?php echo $tabla[$i]['canreg']; ?>,<?php echo $i; ?>);
				
				//document.querySelector ('#boton').addEventListener ('click', function() { 
				//	animateprogress("#php",72);
				//}
				//);
			</script>
		
	<?php
		$consu1='';
		if($tabla[$i]['tabla']!='sysdiagramsname' and $tabla[$i]['tabla']!='sysdiagramsdefinition' and $tabla[$i]['tabla']!='Tipo_AccessTIddeReplica' 
		and $tabla[$i]['tabla']!='sysdiagrams' and $tabla[$i]['tabla']!='trace_xe_action_map' 
		AND $tabla[$i]['tabla']!='trace_xe_event_map')
		{
			//echo $tabla[$i]['canreg'].'<br>';
			//para ejecutar el query por parte
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
				//ejecutamos de 500 en 500
				for($k=0;$k<$cantidad;$k++)
				{
					$jj=0;
					//buscamos los registros
					if($camID==1)
					{
						$sql="SELECT ".$cam1." FROM ".$tabla[$i]['tabla']." WHERE ID>".$inicio." AND ID<=".$fin." ";
					}
					else
					{
						$sql="SELECT ".$cam1." FROM ".$tabla[$i]['tabla']." ";
					}
					//$sql="SELECT ".$cam1." FROM ".$tabla[$i]['tabla']." WHERE ID>".$inicio." AND ID<=".$fin." ";
					//echo $sql.'<br>';
					$result=odbc_exec($cid,$sql)or die(exit("Error en consultar datos de tabla ".$tabla[$i]['tabla']));
					
					$ban1=0;
					$cam2=" VALUES ";
					while(odbc_fetch_row($result)){
						//VALUES ('1', '111'), ('2', '222');
						//echo  $tabla[$i]['tabla'].' -- -- '.$jj.'<br>';
						$cam2=$cam2."( ";
						$ban1=1;
						for($j=1;$j<=odbc_num_fields($result);$j++){
							//echo  $tabla[$i]['tabla'].' -- '.$j.' -- '.$jj.'<br>';
							$valor = odbc_result($result,$j);
							//caracteres especiales
							//\'
							$texto1=0;
							$cadena_buscada   = "\\";
							$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
							//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
							if ($posicion_coincidencia === false) {
								//echo "NO se ha encontrado la palabra deseada!!!!";
								//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
							} else {
								//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
								//sustituir cadena
								$valor = str_replace("\\", "/", utf8_encode($valor));
								$texto1=1;
							}
							// '
							//buscar '
							$texto1=0;
							$cadena_buscada   = "'";
							$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
							//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
							if ($posicion_coincidencia === false) {
								//echo "NO se ha encontrado la palabra deseada!!!!";
								//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
							} else {
								//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
								//sustituir cadena
								$valor = str_replace("'", "\'", utf8_encode($valor));
								$texto1=1;
							}
							//carater 
							$texto1=0;
							$cadena_buscada   = "";
							$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
							//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
							if ($posicion_coincidencia === false) {
								//echo "NO se ha encontrado la palabra deseada!!!!";
								//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
							} else {
								//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
								//sustituir cadena
								$valor = str_replace("", "-", utf8_encode($valor));
								$texto1=1;
							}
							// caracter \xEF\xBF\xBD � \n\n
							$texto1=0;
							$cadena_buscada   = "1304201501099005408800120010050000016260904201612";
							$posicion_coincidencia = strpos(utf8_encode($valor), $cadena_buscada);
							//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
							if ($posicion_coincidencia === false) {
								//echo "NO se ha encontrado la palabra deseada!!!!";
								//$consu1=$consu1." ".utf8_decode($campos[$j]['campo']).",";
							} else {
								//echo "Éxito!!! Se ha encontrado la palabra buscada en la posición: ".$posicion_coincidencia;
								//sustituir cadena
								
									//echo ' ccccccc '.$valor.' cccc ';
									//$valor = str_replace("1304201501099005408800120010050000016260904201612", "1304201501099005408800120010050000016260904201612", utf8_encode($valor));
									$texto1=1;
									//$valor = utf8_encode($valor);
									if($j==4){
										$longitud_cad = strlen($valor); 
										//$valor = substr_replace($valor,"",$longitud_cad-2,1); 
										//$valor = str_replace("U+FFF", "", utf8_encode($valor));
										//echo ' ccccccc '.$valor;
										 $nombre_archivo = "logs.txt"; 
	 
										if(file_exists($nombre_archivo))
										{
											$mensaje = "El Archivo $nombre_archivo se ha modificado";
										}
									 
										else
										{
											$mensaje = "El Archivo $nombre_archivo se ha creado";
										}
									 
										if($archivo = fopen($nombre_archivo, "a"))
										{
											if(fwrite($archivo, date("d m Y H:m:s"). " ". $mensaje. "\n"))
											{
												echo "Se ha ejecutado correctamente";
												fwrite($archivo,$valor);
											}
											else
											{
												echo "Ha habido un problema al crear el archivo";
											}
									 
											fclose($archivo);
										}
										//die();
									}
									//die();
								
							}
							if($camID==1)
							{
								if($j==($tabla[$i]['canti']) and $jj<($ini1-1))
								{
									$cam2=$cam2."'".utf8_encode($valor)."' ), ";
								}
								else
								{
									if($jj<($ini1-1))
									{
										$cam2=$cam2."'".utf8_encode($valor)."', ";
									}
									else
									{
										if($j==($tabla[$i]['canti']))
										{
											$cam2=$cam2."'".utf8_encode($valor)."' ); ";
										}
										else
										{
											$cam2=$cam2."'".utf8_encode($valor)."', ";
										}
									}
								}
							}
							else
							{
								if($j==($tabla[$i]['canti']) and $jj<($tabla[$i]['canreg']-1))
								{
									$cam2=$cam2."'".utf8_encode($valor)."' ), ";
								}
								else
								{
									if($jj<($tabla[$i]['canreg']-1))
									{
										$cam2=$cam2."'".utf8_encode($valor)."', ";
									}
									else
									{
										if($j==($tabla[$i]['canti']))
										{
											$cam2=$cam2."'".utf8_encode($valor)."' ); ";
										}
										else
										{
											$cam2=$cam2."'".utf8_encode($valor)."', ";
										}
									}
								}
							}
							//echo $cam2.'<br>';
							//echo "Result is ".$tabla[$i]['tabla']." ".$valor;
						}
						$jj++;
						$jj1++;
						 ?>
		
						<script type="text/javascript"> 
							
								animateprogress("#car<?php echo $i; ?>",<?php echo $tabla[$i]['canreg']; ?>,<?php echo $jj1; ?>,'<?php echo $tabla[$i]['tabla']; ?>');
							
							//document.querySelector ('#boton').addEventListener ('click', function() { 
							//	animateprogress("#php",72);
							//}
							//);
						</script>
							<?php
					}
					
						$longitud_cad = strlen($cam2); 
						$cam2 = substr_replace($cam2,";",$longitud_cad-2,1); 
					
					$inicio=$inicio+$ini1;
					$fin=$fin+$ini1;
					$consu2=$consu1."".$cam2;
					//echo '<br>'.$consu1.' ';
					if($ban1==1)
					{
						if ($conexion->query($consu2) === TRUE) {
							//echo " ha sido creado".'<br>';
						} else {
							//echo "Hubo un error al insertar datos de la tabla : ".$tabla[$i]['tabla']." ".$consu2." " . $conexion->error.'<br>';
							echo "Hubo un error al insertar datos de la tabla : ".$tabla[$i]['tabla']." " .'<br>';
							//die();
						}
					}
				}
				$jj=0;
				//buscamos los registros
				$sql="SELECT ".$cam1." FROM ".$tabla[$i]['tabla']." ";
				//echo $sql.'<br>';
				/*
				echo $sql.'<br>';
					$result=odbc_exec($cid,$sql)or die(exit("Error en consultar datos de tabla ".$tabla[$i]['tabla']));
					
					$ban1=0;
					while(odbc_fetch_row($result)){
						
						//VALUES ('1', '111'), ('2', '222');
						//echo  $tabla[$i]['tabla'].' -- -- '.$jj.'<br>';
						$cam2=$cam2."( ";
						$ban1=1;
						for($j=1;$j<=odbc_num_fields($result);$j++){
							//echo  $tabla[$i]['tabla'].' -- '.$j.' -- '.$jj.'<br>';
							$valor = odbc_result($result,$j);
							if($j==($tabla[$i]['canti']) and $jj<($tabla[$i]['canreg']-1))
							{
								$cam2=$cam2."'".utf8_encode($valor)."' ), ";
							}
							else
							{
								if($jj<($tabla[$i]['canreg']-1))
								{
									$cam2=$cam2."'".utf8_encode($valor)."', ";
								}
								else
								{
									if($j==($tabla[$i]['canti']))
									{
										$cam2=$cam2."'".utf8_encode($valor)."' ); ";
									}
									else
									{
										$cam2=$cam2."'".utf8_encode($valor)."', ";
									}
								}
							}
							//echo $cam2.'<br>';
							//echo "Result is ".$tabla[$i]['tabla']." ".$valor;
						}
						$jj++;
						 ?>
		
						<script type="text/javascript"> 
							
								animateprogress("#car",<?php echo $tabla[$i]['canreg']; ?>,<?php echo $i; ?>,'<?php echo $tabla[$i]['tabla']; ?>');
							
							//document.querySelector ('#boton').addEventListener ('click', function() { 
							//	animateprogress("#php",72);
							//}
							//);
						</script>
							<?php
					}
					$inicio=$inicio+$ini1;
					$fin=$fin+$ini1;
					$consu2=$consu1."".$cam2;
					//echo '<br>'.$consu1.' ';
					if($ban1==1)
					{
						if ($conexion->query($consu2) === TRUE) {
							//echo " ha sido creado".'<br>';
						} else {
							echo "Hubo un error al insertar datos de la tabla : ".$tabla[$i]['tabla']." ".$consu2." " . $conexion->error.'<br>';
							die();
						}
					}
				*/

			// generamos la tabla mediante odbc_result_all(); utilizando borde 1
			//$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
		}
		//die();
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