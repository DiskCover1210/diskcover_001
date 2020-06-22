<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("../../db/db.php");
require_once("../../funciones/funciones_ajax.php");
require_once("../../../lib/excel/plantilla.php");
require_once("../../../lib/fpdf/reporte_comp.php");
require_once("../../funciones/numeros_en_letras.php");

//caso comprobantes aprobados codigo para buscar los comprobantes
if(isset($_POST['ajax_page']) ) 
{
	//buscar comprobante
	if($_REQUEST['ajax_page']=='comproba')
	{
		buscar_com();
	}
	//mandar pdf comprobante seleccionado
	if($_REQUEST['ajax_page']=='comp')
	{
		reporte_com();
	}
	//verificar ciudad
	if($_REQUEST['ajax_page']=='ciudad')
	{
		validarCiu();
	}
	//verificar entidad
	if($_REQUEST['ajax_page']=='Entidad')
	{
		validarEnt();
	}
	//verificar usuario en login
	if($_REQUEST['ajax_page']=='USER')
	{
		validarUser();
	}
	//verificar entidad-usuario
	if($_REQUEST['ajax_page']=='entidad_u')
	{
		buscarEntidad_usu();
	}
	//verificar usuario
	if($_REQUEST['ajax_page']=='usuario')
	{
		//buscarUsuario();
	}
	if($_REQUEST['ajax_page']=='buscarusu')
	{
		buscarUsuario();
	}
	
	//cambiar periodo
	if($_REQUEST['ajax_page']=='cambiarPeriodo')
	{
		cambiarPeriodo();
	}
	//datos entidad-empresas
	if($_REQUEST['ajax_page']=='entidad')
	{
		buscarEntidad();
	}
	//datos empresa
	if($_REQUEST['ajax_page']=='empresa')
	{
		buscarEmpresa();
	}
	//modificar empresa
	if($_REQUEST['ajax_page']=='cambiarEmpresa')
	{
		modificarEmpresa();
	}
	//modificar entidad
	if($_REQUEST['ajax_page']=='cambiarEmpresaMa')
	{
		modificarEmpresaMa();
	}
	//mensaje individual
	if($_REQUEST['ajax_page']=='mindividual')
	{
		mindividual();
	}
	//mensaje masivo
	if($_REQUEST['ajax_page']=='mmasivo')
	{
		mmasivo();
	}
	//eliminar registro asiento_b
	if($_REQUEST['ajax_page']=='eli1')
	{
		eliminar();
	}
	//autocompletar registro asiento_b
	if($_REQUEST['ajax_page']=='aut')
	{
		autocompletar();
	}
	//autocompletar registro otra forma
	if($_REQUEST['ajax_page']=='aut1')
	{
		autocompletar1();
	}
	//buscar registro generico
	if($_REQUEST['ajax_page']=='bus')
	{
		buscar();
	}
	//ingresar registro
	if($_REQUEST['ajax_page']=='ing1')
	{
		ingresar();
	}
}
//Buscar empresa
function buscarUsuario(){
	 
		$cid = Conectar::conexion('MYSQL');
		//$_POST['TP']='CD';
		//$_POST['MesNo']=0;
		//$cade = explode("-", $_POST['com']);
		$sql = "select IP_Acceso,CodigoU,
			(select Nombre_usuario from acceso_usuarios 
			where acceso_pcs.CodigoU=acceso_usuarios.CI_NIC 
			limit 1 ) as usuario,Item,RUC,Fecha,Hora,Aplicacion,Tarea,Proceso,Credito_No,ES,Periodo 
			from acceso_pcs where 1=1 ";
		/*$sql = "select IP_Acceso,CodigoU,Item,RUC,Fecha,Hora,Aplicacion,Tarea,Proceso,Credito_No,ES,Periodo 
			from acceso_pcs where 1=1 ";*/
		//echo $sql;
		//die();
		/*
		value1: value1, value3: value3, value5: value5, value6: value6,
					value7: value7, ch1: ch1, ch2: ch2, ch3: ch3
		*/
		$ci_nic='';
		$filtro='';
		$item='';
		if($_POST['ch2']==1)
		{
			$sql1 = "SELECT *
				  FROM acceso_usuarios
				  WHERE ID = '".$_POST['value3']."' ";
				  $consulta=$cid->query($sql1) or die($cid->error);
			while($filas=$consulta->fetch_array())
			{
				$ci_nic=$filas['CI_NIC'];
			}
			$filtro=$filtro." AND CodigoU = '".$ci_nic."' ";
		}
		if($_POST['ch3']==1)
		{
			$sql1 = "SELECT *
				  FROM lista_empresas
				  WHERE Item = '".$_POST['value7']."' AND ID_Empresa = '".$_POST['value1']."'";
				  $consulta=$cid->query($sql1) or die($cid->error);
			while($filas=$consulta->fetch_array())
			{
				$ruc=$filas['RUC_CI_NIC'];
			}
			$filtro=$filtro." AND Item = '".$_POST['value7']."' AND RUC='".$ruc."'";
		}
		else
		{
			if($_POST['ch1']==1 and $_POST['ch3']==0)
			{
				$sql1 = "SELECT *
				FROM lista_empresas
				WHERE ID_Empresa = '".$_POST['value1']."' ORDER BY Empresa;";
				  $consulta=$cid->query($sql1) or die($cid->error);
				  $item='';
				  $i=0;
				  $filtro=$filtro.' AND ( ';
				while($filas=$consulta->fetch_array())
				{
					$item=$item." (Item in 
					(select Item from lista_empresas 
					where lista_empresas.Item='".$filas['Item']."' 
					and lista_empresas.RUC_CI_NIC='".$filas['RUC_CI_NIC']."' AND ID_Empresa='".$_POST['value1']."')
					AND 
					RUC in 
					(select RUC_CI_NIC from lista_empresas 
					where lista_empresas.Item='".$filas['Item']."' 
					and lista_empresas.RUC_CI_NIC='".$filas['RUC_CI_NIC']."' AND ID_Empresa='".$_POST['value1']."')
					) OR";
					//$item=$item."'".$filas['Item']."',";
				}
				
				//echo $item.'<br>';
				$longitud_cad = strlen($item); 
				$item = substr_replace($item,"",$longitud_cad-1,1);
				$longitud_cad = strlen($item); 
				$item = substr_replace($item,"",$longitud_cad-1,2); 				
				$filtro=$filtro.$item;
				$filtro=$filtro.' )';
				
				//echo $item.'<br>';
			}
		}
		/*$date =$_POST['value5'];
		$now = new DateTime($date);
		$hoy=$now->format('Ymd'); 
		
		$date1 =$_POST['value5'];
		$now1 = new DateTime($date1);
		$hoy1=$now1->format('Ymd');*/
		$fi=date("Ymd", strtotime($_POST['value5']));
		$ff=date("Ymd", strtotime($_POST['value6']));
		//echo $fi.' '.$ff;
		//$filtro=$filtro." AND (Fecha >= '".date("Y-m-d")."' AND Fecha<='".date("Y-m-d")."') ";
		//$filtro=$filtro." AND (Fecha >= '".date("Ymd", strtotime($_POST['value5']))."' AND fecha<='".date("Ymd", strtotime($_POST['value6']))."') ";
		//$filtro=$filtro." AND (Fecha >= '".$hoy."' AND fecha<='".$hoy1."') ";
		$filtro=$filtro." AND Fecha BETWEEN '$fi' AND '$ff' ";
		//$filtro=$filtro." AND (Fecha >= STR_TO_DATE(\"2019-10-23\", \"%Y-%m-%d\") AND Fecha<=STR_TO_DATE(\"2019-10-23\", \"%Y-%m-%d\")) ";
		
		$sql=$sql.$filtro.'  ORDER BY CodigoU; ';
		//echo $sql.'<br>';
		/*echo $_POST['value1'].' '.$_POST['value3'].' '.$_POST['value5'].' '.$_POST['value6']
		.' '.$_POST['value7'].' '.$_POST['ch1'].' '.$_POST['ch2'].' '.$_POST['ch3'];*/
		
		paginador('acceso_pcs',$filtro,'empresa.php?mod=empresa&acc=cambiou&acc1=Administrar%20Usuario&cod='.$ci_nic.'&item='.$item.'');
		//echo $sql;	
		//die();
		$consulta=$cid->query($sql) or die($cid->error);
		//$row_cnt = $consulta->num_rows;
		//echo $row_cnt.' cccc ';
		$camne=array();
		/*while($filas=$consulta->fetch_array())
		{
			echo $filas['CodigoU'].'<br>';
		}
		while ($row = $consulta->fetch_row())
		{
			echo $row[0].'<br>';
		}*/		
		//grilla_generica_mysql($consulta,null,NULL,'1',null,null);
		grilla_generica($consulta,null,NULL,'1',null,null,'MYSQL');
		die();
		/*
		var value1 = document.getElementById('entidad_u').value;
		var ch1 = '0';
		var isChecked = document.getElementById('entidadch').checked;
		if(isChecked)
		{
			ch1 = '1';
		}
		var value3 = document.getElementById('usuario').value;
		var ch2 = '0';
		var isChecked = document.getElementById('usuarioch').checked;
		if(isChecked)
		{
			ch2 = '1';
		}
		var ch3 = '0';
		var isChecked = document.getElementById('empresach').checked;
		if(isChecked)
		{
			ch3 = '1';
		}
		var value7 = document.getElementById('empresa').value;
		var value5 = document.getElementById('FechaI').value;
		var value6 = document.getElementById('FechaF').value;
		*/
		$consulta=$cid->query($sql) or die($cid->error);
		//Realizamos un bucle para ir obteniendo los resultados
		$i=0;
		/*$info_campo = $consulta->fetch_fields();

				foreach ($info_campo as $valor) {
					printf("Nombre:           %s\n",   $valor->name);
					printf("Tabla:            %s\n",   $valor->table);
					printf("Longitud máx.:    %d\n",   $valor->max_length);
					printf("Longitud:         %d\n",   $valor->length);
					printf("Nº conj. caract.: %d\n",   $valor->charsetnr);
					printf("Banderas:         %d\n",   $valor->flags);
					printf("Tipo:             %d\n\n", $valor->type);
				}
		$consulta=$cid->query($sql) or die($cid->error);*/
		//while($filas=$consulta->fetch_assoc()){
			while($filas=$consulta->fetch_array()){
			?>
				
				<div class="col-md-2">
					<div class="form-group">
					  <label for="FechaR">Fecha Inicio(dia-mes-año)</label>
					   
					  <input type="date" class="form-control" id="FechaI" placeholder="Fecha Inicio" 
					  value='<?php echo date('Y-m-d'); ?>' 
					  onKeyPress="return soloNumeros(event)"  maxlength="10" >
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
					  <input type="hidden" id='usuario' name='usuario'  value='<?php echo $filas['CI_NIC']; ?>' />
					  <label for="Fecha">Fecha Fin(dia-mes-año)</label>
					   
					  <input type="date" class="form-control" id="FechaF" placeholder="Fecha Fin" 
					  value='<?php echo date('Y-m-d'); ?>' onKeyPress="return soloNumeros(event)" 
					  maxlength="10" >
					</div>
				</div>
				<div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
					<div class="form-group">
						<button type="button"  class="btn btn-primary" id='buscarusu' onclick="return buscar('buscarusu');">Buscar</button>
					</div>
				</div>
				<?php
				$sql = "select IP_Acceso,CodigoU,
				(select Nombre_usuario from acceso_usuarios 
				where acceso_pcs.CodigoU=acceso_usuarios.CI_NIC 
				limit 1 ) as usuario,Item,RUC,Fecha,Hora,Aplicacion,Tarea,Proceso,Credito_No,ES,Periodo 
				from acceso_pcs where CodigoU = '".$filas['CI_NIC']."' and item='".$_SESSION['INGRESO']['item']."' ";
				paginador('acceso_pcs'," CodigoU = '".$filas['CI_NIC']."' and item='".$_SESSION['INGRESO']['item']."' ",
				'empresa.php?mod=empresa&acc=cambiou&acc1=Administrar%20Usuario&cod='.$filas['CI_NIC'].'&item='.$_SESSION['INGRESO']['item'].'');
				//$camne=array();
				//grilla_generica_mysql($stmt,null,NULL,'1','0,1,clave','asi');
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';
			$i++;
		}
		?>
		<?php
			
			//echo $sql;
			//die(); $_SESSION['INGRESO']['item']
			
			$consulta=$cid->query($sql) or die($cid->error);
			$camne=array();
			//grilla_generica_mysql($consulta,null,NULL,'1',null,null);
			grilla_generica($consulta,null,NULL,'1',null,null,'MYSQL');
			
			?>
			<?php
		if($i==0)
		{
			//echo '<div id="alerta" class="alert alert-warning visible">Usuario no encontrado</div>';
			echo "<script> Swal.fire({
				  type: 'error',
				  title: 'No se pudo realizar sesion',
				  text: 'Usuario no encontrado.'
				});
				</script>";
		}
	 $cid->close();
}
//contar registros caso paginador por ejemplo (sql server y MYSQL) 
function cantidaREGSQL_AJAX($tabla,$filtro=null,$base=null)
{
	//echo $filtro.' gg ';
	if($base==null or $base=='SQL SEVER')
	{
		$cid = Conectar::conexion('SQL SERVER');
		if($filtro!=null AND $filtro!='')
		{
			$sql = "SELECT count(*) as regis FROM ".$tabla." WHERE 1=1 ".$filtro." ";
		}
		else
		{
			$sql = "SELECT count(*) as regis FROM ".$tabla;
		}
		//echo $sql;
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$row_count=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$row_count = $row[0];
			//echo $row[0];
		}
		cerrarSQLSERVERFUN($cid);
	}
	else
	{
		if($base=='MYSQL')
		{
			$cid = Conectar::conexion('MYSQL');
			
			if($filtro!=null AND $filtro!='')
			{
				$sql = "SELECT count(*) as regis FROM ".$tabla." WHERE 1=1 ".$filtro." ";
			}
			else
			{
				$sql = "SELECT count(*) as regis FROM ".$tabla;
			}
			//echo $sql;
			$consulta=$cid->query($sql) or die($cid->error);
			$row_count=0;
			while($row=$consulta->fetch_assoc())
			{
				$row_count = $row['regis'];
				//echo $row[0];
			}
			$cid->close();
		}
	}
	//numero de columnas
	//$row_count = sqlsrv_num_rows( $stmt );
	return $row_count;
}

function paginador($tabla,$filtro=null,$link=null)
{
	//saber si hay paginador
	$pag=1;
	$start_from=null; 
	$record_per_page=null;
	if($pag==1) 
	{
		//obtenemos los valores
		$record_per_page = 10;
		$pagina = '';
		if(isset($_GET["pagina"]))
		{
		 $pagina = $_GET["pagina"];
		}
		else
		{
		 $pagina = 1;
		}
		$start_from = ($pagina-1)*$record_per_page;
		//buscamos cantidad de registros
		$filtros=" Item = '".$_SESSION['INGRESO']['item']."'  
		AND ( Periodo='".$_SESSION['INGRESO']['periodo']."' ) ";
		//hacemos los filtros
		if(isset($_POST['tipo']))
		{
			if($_POST['tipo']!='seleccione')
			{
				$filtros=$filtros." AND TD='".$_POST['tipo']."' ";
				$_SESSION['FILTRO']['cam1']=$_POST['tipo'];
			}
			else
			{
				unset($_SESSION['FILTRO']['cam1']);
			}
		}
		else
		{
			//si ya existe un filtro caso paginador
			if(isset($_SESSION['FILTRO']['cam1']))
			{
				$filtros=$filtros." AND TD='".$_SESSION['FILTRO']['cam1']."' ";
			}
		}
		if(isset($_POST['fechai']) and isset($_POST['fechaf']))
		{
			//echo $_POST['fechai'];
			if($_POST['fechai']!='' AND $_POST['fechaf']!='')
			{
				$fei = explode("/", $_POST['fechai']);
				$fef = explode("/", $_POST['fechaf']);
				if(strlen($fei[2])==2 AND strlen($fef[2])==2)
				{
					$filtros=$filtros." AND convert(datetime,(SUBSTRING(Clave_Acceso, 5, 4)+'/'
					+SUBSTRING(Clave_Acceso, 3, 2)+'/'+SUBSTRING(Clave_Acceso, 1, 2)+' 00:00:00.000 AM'))
					BETWEEN '".$fei[0].$fei[1].$fei[2]."' AND '".$fef[0].$fef[1].$fef[2]."' ";
					$_SESSION['FILTRO']['cam2']=$fei[0].'/'.$fei[1].'/'.$fei[2];
					$_SESSION['FILTRO']['cam3']=$fef[0].'/'.$fef[1].'/'.$fef[2];
				}
				else
				{
					$filtros=$filtros." AND convert(datetime,(SUBSTRING(Clave_Acceso, 5, 4)+'/'
					+SUBSTRING(Clave_Acceso, 3, 2)+'/'+SUBSTRING(Clave_Acceso, 1, 2)+' 00:00:00.000 AM'))
					BETWEEN '".$fei[2].$fei[0].$fei[1]."' AND '".$fef[2].$fef[0].$fef[1]."' ";
					$_SESSION['FILTRO']['cam2']=$fei[2].'/'.$fei[0].'/'.$fei[1];
					$_SESSION['FILTRO']['cam3']=$fef[2].'/'.$fef[0].'/'.$fef[1];
				}
				//echo $fei[0].' '.$fei[1].' '.$fei[2].' ';
				
				
			}
		}
		else
		{
			//si ya existe un filtro caso paginador
			if(isset($_SESSION['FILTRO']['cam2']) AND isset($_SESSION['FILTRO']['cam3']))
			{
				$fei = explode("/", $_SESSION['FILTRO']['cam2']);
				$fef = explode("/", $_SESSION['FILTRO']['cam3']);
				
				$filtros=$filtros." AND convert(datetime,(SUBSTRING(Clave_Acceso, 5, 4)+'/'
				+SUBSTRING(Clave_Acceso, 3, 2)+'/'+SUBSTRING(Clave_Acceso, 1, 2)+' 00:00:00.000 AM'))
				BETWEEN '".$fei[0].$fei[1].$fei[2]."' AND '".$fef[0].$fef[1].$fef[2]."' ";
			}
		}
		
			//$_POST['fechai']; 
		$total_records=cantidaREGSQL_AJAX($tabla,$filtro,'MYSQL');
		//echo ' ddd '.$total_records;
		//die();
		if($total_records>0)
		{
			$total_pages = ceil($total_records/$record_per_page);
		}
		else
		{
			$total_pages = 0;
		}
		//echo '  '.$total_pages;
		$start_loop = $pagina;
		$diferencia = $total_pages - $pagina;
		if(isset($_SESSION['INGRESO']['Tipo_Base']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
		{
			$record_per_page = $start_from+10;
		}
		if($total_pages>0)
		{
			$start_loop1=$start_loop;
			if($diferencia <= 5)
			{
				$start_loop = $total_pages - 5;
				$start_loop1=$start_loop;
				if($start_loop < 0)
				{
					//$total_pages=$total_pages+$start_loop;
					$start_loop1=$start_loop;
					$start_loop=1;
				}
				if($start_loop == 0)
				{
					$start_loop=1;
				}
			}
			$end_loop = $start_loop1 + 4;
		}
		else
		{
			$start_loop=0;
			$end_loop=0;
		}
	}
	if($link==null)
	{
	?>
	<div class="box-footer clearfix">
		<ul class="pagination pagination-sm no-margin pull-right">
				<?php
			if($pag==1) 
			{
				if($pagina == 1)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=1'>Primera</a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina - 1)."'><<</a>";
					?>
					
				 <?php
				}
				if($pagina > 1)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=1'>Primera</a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina - 1)."'><<</a>";
					?>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=1">1</a></li>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=<?php echo ($pagina-1); ?>">&laquo;</a></li>
				 <?php
				}
				//echo $start_loop.' '.$end_loop;
				for($i=$start_loop; $i<=$end_loop; $i++)
				{     
					//echo "<a class='pagina' href='pagina.php?pagina=".$i."'>".$i."</a>";
						?>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
					 <?php
				}
				if($pagina <= $end_loop)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina + 1)."'>>></a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".$total_pages."'>Última</a>";
					?>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=<?php echo $pagina+1; ?>">&raquo;</a></li>
					<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=
				&Opcb=6&Opcen=0&b=0&pagina=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a></li>
					
				 <?php
				}
			
			}
		?>		
		</ul>
	</div>
	<?php
	}
	else
	{
		?>
	<div class="box-footer clearfix">
		<ul class="pagination pagination-sm no-margin pull-right">
				<?php
			if($pag==1) 
			{
				if($pagina == 1)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=1'>Primera</a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina - 1)."'><<</a>";
					?>
					
				 <?php
				}
				if($pagina > 1)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=1'>Primera</a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina - 1)."'><<</a>";
					?>
					<li><a href="<?php echo $link; ?>&pagina=1">1</a></li>
					<li><a href="<?php echo $link; ?>&pagina=<?php echo ($pagina-1); ?>">&laquo;</a></li>
				 <?php
				}
				//echo $start_loop.' '.$end_loop;
				for($i=$start_loop; $i<=$end_loop; $i++)
				{     
					//echo "<a class='pagina' href='pagina.php?pagina=".$i."'>".$i."</a>";
						?>
					<li><a href="rde.php?<?php echo $link; ?>&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
					 <?php
				}
				if($pagina <= $end_loop)
				{
					//echo "<a class='pagina' href='pagina.php?pagina=".($pagina + 1)."'>>></a>";
					//echo "<a class='pagina' href='pagina.php?pagina=".$total_pages."'>Última</a>";
					?>
					<li><a href="<?php echo $link; ?>&pagina=<?php echo $pagina+1; ?>">&raquo;</a></li>
					<li><a href="<?php echo $link; ?>&pagina=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a></li>
					
				 <?php
				}
			
			}
		?>		
		</ul>
	</div>
	<?php
	}
}

//Buscar entidad usuario
function buscarEntidad_usu()
{
	$cid = Conectar::conexion('MYSQL');
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	//echo $sql;
	//die();
	$i=0;
	if($_POST['ch']=='1')
	{
		$sql = "SELECT *
			  FROM lista_empresas
			  WHERE ID_Empresa = '".$_POST['com']."' ORDER BY Empresa;";
		$consulta=$cid->query($sql) or die($cid->error);
		//Realizamos un bucle para ir obteniendo los resultados
		
	?>
		<div class="col-md-6">
			<div class="form-group">
				<input type="checkbox" name='empresach' id='empresach'>
				<label for="Empresa">Empresa</label>
				
				<select class="form-control" name="empresa" id='empresa' >
					<option value='0'>Seleccione Empresa</option>
		<?php
		while($filas=$consulta->fetch_assoc()){
			?>
				<option value='<?php echo $filas['Item']; ?>'><?php echo $filas['Empresa']; ?></option>
			<?php
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';
			$i++;
		}
		?>
				</select>
			</div>
		</div>
	<?php
		if($i==0)
		{
			echo '<div id="alerta" class="alert alert-warning visible">Empresa no encontrado</div>';
		}
		$sql = "SELECT *
			  FROM acceso_usuarios
			  WHERE ID_Empresa = '".$_POST['com']."' ORDER BY Nombre_Usuario;";
		$consulta=$cid->query($sql) or die($cid->error);
		//Realizamos un bucle para ir obteniendo los resultados
		
	?>
		<div class="col-md-6">
			<div class="form-group">
				<input type="checkbox" name='usuarioch' id='usuarioch'>
				<label for="Empresa">Usuario</label>
				
				<select class="form-control" name="usuario" id='usuario' onChange="return buscar('usuario');">
					<option value='0'>Seleccione Usuario</option>
		<?php
		while($filas=$consulta->fetch_assoc()){
			?>
				<option value='<?php echo $filas['ID']; ?>'><?php echo $filas['Nombre_Usuario']; ?></option>
			<?php
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';
			$i++;
		}
		?>
				</select>
			</div>
		</div>
	<?php
		if($i==0)
		{
			echo '<div id="alerta" class="alert alert-warning visible">Usuario no encontrado</div>';
		}
	}
	else
	{
		$sql = "SELECT *
			  FROM lista_empresas ORDER BY Empresa;";
		$consulta=$cid->query($sql) or die($cid->error);
		//Realizamos un bucle para ir obteniendo los resultados
		
	?>
		<div class="col-md-6">
			<div class="form-group">
				<input type="checkbox" name='empresach' id='empresach'>
				<label for="Empresa">Empresa</label>
				
				<select class="form-control" name="empresa" id='empresa' >
					<option value='0'>Seleccione Empresa</option>
		<?php
		while($filas=$consulta->fetch_assoc()){
			?>
				<option value='<?php echo $filas['Item']; ?>'><?php echo $filas['Empresa']; ?></option>
			<?php
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';
			$i++;
		}
		?>
				</select>
			</div>
		</div>
	<?php
		if($i==0)
		{
			echo '<div id="alerta" class="alert alert-warning visible">Empresa no encontrado</div>';
		}
		$sql = "SELECT *
			  FROM acceso_usuarios
			  group by Nombre_Usuario ORDER BY Nombre_Usuario;";
		$consulta=$cid->query($sql) or die($cid->error);
		?>
		<div class="col-md-6">
			<div class="form-group">
				<input type="checkbox" name='usuarioch' id='usuarioch'>
				<label for="Empresa">Usuario</label>
				
				<select class="form-control" name="usuario" id='usuario' onChange="return buscar('usuario');">
					<option value='0'>Seleccione Usuario</option>
		<?php
		while($filas=$consulta->fetch_assoc()){
			?>
				<option value='<?php echo $filas['ID']; ?>'><?php echo $filas['Nombre_Usuario']; ?></option>
			<?php
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';
			$i++;
		}
		?>
				</select>
			</div>
		</div>
	<?php
		if($i==0)
		{
			echo '<div id="alerta" class="alert alert-warning visible">Usuario no encontrado</div>';
		}
	}
		
	$cid->close();
}
//ingresar registro
function ingresar()
{
	$cid=cone_ajaxSQL();
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	$fecha = date("Y-m-d H:i:s");
	//ingresar haber desde registro en efectivo
	//echo $_POST['cl'];
	if($_POST['cl']=='as_i_h_e')
	{
		//echo " entro ";
		$vae = $_POST['vae'];
		$conceptoe = $_POST['conceptoe'];
		$tipoc = $_POST['tipoc'];
		//tipo movimiento debe-haber
		$cam_ti='';
		if($tipoc=='CE')
		{
			$cam_ti='HABER';
		}
		else
		{
			$cam_ti='DEBE';
		}
		$sql="SELECT CODIGO, CUENTA
		FROM Asiento
		WHERE (Item = '".$_SESSION['INGRESO']['item']."') 
		AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND ('".$cam_ti."' = '".$vae."') 
		AND (CODIGO='".$conceptoe."') 
		ORDER BY A_No ASC ";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//echo $sql;
		//die();
		//para contar registro
		$i=0;
		$i=contar_registros($stmt);
		//si no existe le creamos al haber el banco
		//echo $vae;
		//die();
		if($i==0)
		{
			if($vae!='' and $vae!=0)
			{	
				//buscamos nombre de la cuenta
				$sql="SELECT Codigo,Cuenta
				FROM  Catalogo_Cuentas
				WHERE  (TC = 'CJ') AND (DG = 'D') 
				AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
				AND Codigo='".$conceptoe."' ";
				//echo $sql;
				//die();
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$ncuenta = $row[1];
				}
				//seleccionamos el valor siguiente
				$sql="SELECT TOP 1 A_No FROM Asiento
				WHERE (Item = '".$_SESSION['INGRESO']['item']."')
				ORDER BY A_No DESC";
				$A_No=0;
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$ii=0;
					while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
					{
						$A_No = $row[0];
						$ii++;
					}
					
					if($ii==0)
					{
						$A_No++;
					}
					else
					{
						$A_No++;
					}
				}
				if($cam_ti=='HABER')
				{
					$sql="INSERT INTO Asiento
					(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
					,ME,T_No,Item,CodigoU,A_No)
					VALUES
					('".$conceptoe."','".$ncuenta."',0,0,".$vae.",'.','.',
					'".$fecha."','.','.',0,1,'".$_SESSION['INGRESO']['item']."',
					'".$_SESSION['INGRESO']['CodigoU']."',".$A_No.")";
				}
				if($cam_ti=='DEBE')
				{
					$sql="INSERT INTO Asiento
					(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
					,ME,T_No,Item,CodigoU,A_No)
					VALUES
					('".$conceptoe."','".$ncuenta."',0,".$vae.",0,'.','.',
					'".$fecha."','.','.',0,1,'".$_SESSION['INGRESO']['item']."',
					'".$_SESSION['INGRESO']['CodigoU']."',".$A_No.")";
				}
			   $stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
					FROM Asiento
					WHERE 
						T_No=".$_SESSION['INGRESO']['modulo_']." AND
						Item = '".$_SESSION['INGRESO']['item']."' 
						AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
						ORDER BY A_No ASC ";
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					else
					{
						$camne=array();
						grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
						ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
					}
				}
			}
		}
		/*else
		{
			echo "<script>
					Swal.fire({
						type: 'error',
						title: 'No se pudo guardar registro',
						text: 'No existe asiento al debe',
						footer: ''
					})
			</script>";
			$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
			FROM Asiento
			WHERE 
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$camne=array();
				grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
			}
		}*/
	}
	//ingresar haber desde registro en banco
	if($_POST['cl']=='as_i_h')
	{
		//sie existe valor en efectivo
		$vae=$_POST['vae'];
		if($vae=='')
		{
			$vae=0;
		}
		//buscamos si hay cheques
		//verificar si ya existe
		$sql="SELECT CTA_BANCO ,BANCO,VALOR,CHEQ_DEP,EFECTIVIZAR  ". 
		   "FROM Asiento_B ".
		   "WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ".
		   "ORDER BY CTA_BANCO ";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$ii=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$CTA_BANCO = $row[0];
			$BANCO = $row[1];
			$VALOR = $row[2];
			$CHEQ_DEP = $row[3];
			$EFECTIVIZAR = $row[4];
			$ii++;
		}
		if($ii==0)
		{
			echo "<script>
						Swal.fire({
							type: 'error',
							title: 'No se pudo guardar registro',
							text: 'No existe un registro bancario',
							footer: ''
					})
			</script>";
			$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
					ORDER BY A_No ASC ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$camne=array();
					grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
					ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
				}
		}
		else
		{
			//verificar si ya existe asiento
			/*$sql="SELECT CODIGO, CUENTA
			FROM Asiento
			WHERE  (Item = '".$_SESSION['INGRESO']['item']."') 
			AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND (DEBE = '".$VALOR."') ";*/
			$sql="SELECT CODIGO, CUENTA,DEBE
			FROM Asiento
			WHERE  T_No=".$_SESSION['INGRESO']['modulo_']." AND 
			(Item = '".$_SESSION['INGRESO']['item']."') 
			AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') 
			ORDER BY A_No ASC ";
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//para contar registro
			//echo $sql;
			$i=0;
			$i=contar_registros($stmt);
			//echo $i.' -- '.$sql;
			
			//si existe guardamos verificamos que el asiento de banco este
			if($i>0)
			{
				//buscamos valor del debe
				$stmt = sqlsrv_query( $cid, $sql);
				$VALOR=0;
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$VALOR = $VALOR+$row[2];
				}
				$VALOR = $VALOR-$vae;
				$sql="SELECT CODIGO, CUENTA
				FROM Asiento
				WHERE (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND (HABER = '".$VALOR."') 
				AND (CODIGO='".$CTA_BANCO."') AND T_No=".$_SESSION['INGRESO']['modulo_']." ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				//echo $sql;
				//para contar registro
				$i=0;
				$i=contar_registros($stmt);
				//si no existe le creamos al haber el banco
				if($i==0)
				{	
					//seleccionamos el valor siguiente
					$sql="SELECT TOP 1 A_No FROM Asiento
					WHERE (Item = '".$_SESSION['INGRESO']['item']."')
					ORDER BY A_No DESC";
					$A_No=0;
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					else
					{
						$ii=0;
						while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
						{
							$A_No = $row[0];
							$ii++;
						}
						
						if($ii==0)
						{
							$A_No++;
						}
						else
						{
							$A_No++;
						}
					}
					$sql="INSERT INTO Asiento
					(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
					,ME,T_No,Item,CodigoU,A_No)
					VALUES
					('".$CTA_BANCO."','".$BANCO."',0,0,".$VALOR.",'".$CHEQ_DEP."','.',
					'".$EFECTIVIZAR->format('Y-m-d H:i:s')."','.','.',0,1,'".$_SESSION['INGRESO']['item']."',
					'".$_SESSION['INGRESO']['CodigoU']."',".$A_No.")";
					
				   $stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					else
					{
						$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
						FROM Asiento
						WHERE 
							T_No=".$_SESSION['INGRESO']['modulo_']." AND
							Item = '".$_SESSION['INGRESO']['item']."' 
							AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
							ORDER BY A_No ASC ";
						$stmt = sqlsrv_query( $cid, $sql);
						if( $stmt === false)  
						{  
							 echo "Error en consulta PA.\n";  
							 die( print_r( sqlsrv_errors(), true));  
						}
						else
						{
							$camne=array();
							grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
							ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
						}
					}
				}
				else
				{
					echo "<script>
						Swal.fire({
							type: 'error',
							title: 'No se pudo guardar registro',
							text: 'ya existe registro de banco',
							footer: ''
							})
					</script>";
					$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
					FROM Asiento
					WHERE 
						T_No=".$_SESSION['INGRESO']['modulo_']." AND
						Item = '".$_SESSION['INGRESO']['item']."' 
						AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
						ORDER BY A_No ASC ";
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					else
					{
						$camne=array();
						grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
						ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
					}
				}
			}
			else
			{
				echo "<script>
						Swal.fire({
							type: 'error',
							title: 'No se pudo guardar registro',
							text: 'No existe asiento al debe',
							footer: ''
						})
				</script>";
				$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
					ORDER BY A_No ASC ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$camne=array();
					grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
					ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
				}
			}
		}
		
	}
	//ingresar debe desde registro en banco
	if($_POST['cl']=='as_i_d')
	{
		//buscamos si hay cheques
		//verificar si ya existe
		$sql="SELECT CTA_BANCO ,BANCO,VALOR,CHEQ_DEP,EFECTIVIZAR  ". 
		   "FROM Asiento_B ".
		   "WHERE Item = '".$_SESSION['INGRESO']['item']."' 
		    AND CodigoU = '".$_SESSION['INGRESO']['CodigoU']."' ".
		   "ORDER BY CTA_BANCO ";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA. 1\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$ii=0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$CTA_BANCO = $row[0];
			$BANCO = $row[1];
			$VALOR = $row[2];
			$CHEQ_DEP = $row[3];
			$EFECTIVIZAR = $row[4];
			$ii++;
		}
		if($ii==0)
		{
			echo "<script>
						Swal.fire({
							type: 'error',
							title: 'No se pudo guardar registro',
							text: 'No existe un registro bancario',
							footer: ''
					})
			</script>";
			$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
					ORDER BY A_No ASC ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA. 2\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$camne=array();
					grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
					ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
				}
		}
		else
		{
			//verificar si ya existe asiento
			/*$sql="SELECT CODIGO, CUENTA
			FROM Asiento
			WHERE  (Item = '".$_SESSION['INGRESO']['item']."') 
			AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND (DEBE = '".$VALOR."') ";*/
			$sql="SELECT CODIGO, CUENTA,HABER
			FROM Asiento
			WHERE  T_No=".$_SESSION['INGRESO']['modulo_']." AND (Item = '".$_SESSION['INGRESO']['item']."') 
			AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') ";
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA. 3\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//para contar registro
			$i=0;
			$i=contar_registros($stmt);
			//echo $i.' -- '.$sql;
			
			//si existe guardamos verificamos que el asiento de banco este
			if($i>0)
			{
				//buscamos valor del debe
				$stmt = sqlsrv_query( $cid, $sql);
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$VALOR = $row[2];
				}
				$sql="SELECT CODIGO, CUENTA
				FROM Asiento
				WHERE (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND (HABER = '".$VALOR."') 
				AND (CODIGO='".$CTA_BANCO."') AND T_No=".$_SESSION['INGRESO']['modulo_']." ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA. 4\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				//echo $sql;
				//para contar registro
				$i=0;
				$i=contar_registros($stmt);
				//si no existe le creamos al haber el banco
				if($i==0)
				{	
					//seleccionamos el valor siguiente
					$sql="SELECT TOP 1 A_No FROM Asiento
					WHERE (Item = '".$_SESSION['INGRESO']['item']."')
					ORDER BY A_No DESC ";
					$A_No=0;
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA. 5\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					else
					{
						$ii=0;
						while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
						{
							$A_No = $row[0];
							$ii++;
						}
						
						if($ii==0)
						{
							$A_No++;
						}
						else
						{
							$A_No++;
						}
					}
					$sql="INSERT INTO Asiento
					(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
					,ME,T_No,Item,CodigoU,A_No)
					VALUES
					('".$CTA_BANCO."','".$BANCO."',0,".$VALOR.",0,'".$CHEQ_DEP."','.',
					'".$EFECTIVIZAR->format('Y-m-d H:i:s')."','.','.',0,1,'".$_SESSION['INGRESO']['item']."',
					'".$_SESSION['INGRESO']['CodigoU']."',".$A_No.")";
					
				   $stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA. 6\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					else
					{
						$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
						FROM Asiento
						WHERE 
							T_No=".$_SESSION['INGRESO']['modulo_']." AND
							Item = '".$_SESSION['INGRESO']['item']."' 
							AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
							ORDER BY A_No ASC ";
						$stmt = sqlsrv_query( $cid, $sql);
						if( $stmt === false)  
						{  
							 echo "Error en consulta PA. 7\n";  
							 die( print_r( sqlsrv_errors(), true));  
						}
						else
						{
							$camne=array();
							grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
							ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
						}
					}
				}
				else
				{
					echo "<script>
						Swal.fire({
							type: 'error',
							title: 'No se pudo guardar registro',
							text: 'ya existe registro de banco',
							footer: ''
							})
					</script>";
					$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
					FROM Asiento
					WHERE 
						T_No=".$_SESSION['INGRESO']['modulo_']." AND
						Item = '".$_SESSION['INGRESO']['item']."' 
						AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
						ORDER BY A_No ASC ";
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.8 \n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					else
					{
						$camne=array();
						grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
						ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
					}
				}
			}
			else
			{
				echo "<script>
						Swal.fire({
							type: 'error',
							title: 'No se pudo guardar registro',
							text: 'No existe asiento al haber',
							footer: ''
						})
				</script>";
				$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
					ORDER BY A_No ASC ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA. 9\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$camne=array();
					grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
					ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
				}
			}
		}
		
	}
	//ingresar asiento 
	if($_POST['cl']=='as_i')
	{
		$va = $_POST['va'];
		$dconcepto1 = $_POST['dconcepto1'];
		$codigo = $_POST['codigo'];
		$cuenta = $_POST['cuenta'];
		if(isset($_POST['efectivo_as']))
		{
			$efectivo_as = $_POST['efectivo_as'];
		}
		else
		{
			$efectivo_as = '';
		}
		if(isset($_POST['chq_as']))
		{
			$chq_as = $_POST['chq_as'];
		}
		else
		{
			$chq_as = '';
		}
		
		$moneda = $_POST['moneda'];
		$tipo_cue = $_POST['tipo_cue'];
		
		if($efectivo_as=='' or $efectivo_as==null)
		{
			$efectivo_as=$fecha;
		}
		if($chq_as=='' or $chq_as==null)
		{
			$chq_as='.';
		}
		$parcial = 0;
		if($moneda==2)
		{
			$cotizacion = $_POST['cotizacion'];
			$con = $_POST['con'];
			if($tipo_cue==1)
			{
				if($con=='/')
				{
					$debe=$va/$cotizacion;
				}
				else
				{
					$debe=$va*$cotizacion;
				}
				$parcial = $va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				if($con=='/')
				{
					$haber=$va/$cotizacion;
				}
				else
				{
					$haber=$va*$cotizacion;
				}
				$parcial = $va;
				$debe=0;
			}
		}
		else
		{
			if($tipo_cue==1)
			{
				$debe=$va;
				$haber=0;
			}
			if($tipo_cue==2)
			{
				$debe=0;
				$haber=$va;
			}
		}
		//verificar si ya existe en ese modulo ese registro
		$sql="SELECT CODIGO, CUENTA
		FROM Asiento
		WHERE (CODIGO = '".$codigo."') AND (Item = '".$_SESSION['INGRESO']['item']."') 
		AND (CodigoU = '".$_SESSION['INGRESO']['CodigoU']."') AND (DEBE = '".$va."') 
		AND T_No=".$_SESSION['INGRESO']['modulo_']." 
		ORDER BY A_No ASC ";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//para contar registro
		$i=0;
		$i=contar_registros($stmt);
		//echo $i.' -- '.$sql;
		//seleccionamos el valor siguiente
		$sql="SELECT TOP 1 A_No FROM Asiento
		WHERE (Item = '".$_SESSION['INGRESO']['item']."')
		ORDER BY A_No DESC";
		$A_No=0;
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		else
		{
			$ii=0;
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$A_No = $row[0];
				$ii++;
			}
			
			if($ii==0)
			{
				$A_No++;
			}
			else
			{
				$A_No++;
			}
		}
		//si no existe guardamos
		if($i==0)
		{
			//para saber si es debe o haber
			if($tipo_cue==1)
			{
				$sql="INSERT INTO Asiento
				(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
				,ME,T_No,Item,CodigoU,A_No)
				VALUES
				('".$codigo."','".$cuenta."',".$parcial.",".$debe.",".$haber.",'".$chq_as."','".$dconcepto1."',
				'".$efectivo_as."','.','.',0,1,'".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['CodigoU']."',".$A_No.")";
			}
			if($tipo_cue==2)
			{
				$sql="INSERT INTO Asiento
				(CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE,EFECTIVIZAR,CODIGO_C,CODIGO_CC
				,ME,T_No,Item,CodigoU,A_No)
				VALUES
				('".$codigo."','".$cuenta."',".$parcial.",".$debe.",".$haber.",'".$chq_as."','".$dconcepto1."',
				'".$efectivo_as."','.','.',0,1,'".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['CodigoU']."',".$A_No.")";
			}
			
		   $stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
					ORDER BY A_No ASC ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$camne=array();
					grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
					ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
				}
			}
		}
		else
		{
			//echo " ENTROOO ";
			echo "<script>
						Swal.fire({
							type: 'error',
							title: 'No se pudo guardar registro',
							text: 'Ya existe un registro con estos datos',
							footer: ''
					})
			</script>";
			$sql="SELECT A_No,CODIGO,CUENTA,PARCIAL_ME,DEBE,HABER,CHEQ_DEP,DETALLE
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' 
					ORDER BY A_No ASC ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$camne=array();
					grilla_generica($stmt,null,NULL,'1','0,1,clave','asi');
					ListarTotalesTemSQL_AJAX(null,null,'1','0,1,clave');
				}
		}
		
	}
	//ingresar asiento banco
	if($_POST['cl']=='as_b_i')
	{
		$banco = $_POST['banco'];
		$nbanco = '';
		$vab = $_POST['vab'];
		$efecti = $_POST['efecti'];
		$depos = $_POST['depos'];
		//encontrar nombre de cuenta
		$sql="SELECT Codigo,Cuenta
			FROM  Catalogo_Cuentas
			WHERE  (TC = 'BA') AND (DG = 'D') 
			AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND Codigo='".$banco."' ";
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$nbanco = $row[1];
		}
		//verificar si ya existe
		$sql="SELECT CTA_BANCO ,BANCO  ". 
		   "FROM Asiento_B ".
		   "WHERE CTA_BANCO = '".$banco."' AND Item = '".$_SESSION['INGRESO']['item']."' 
		   AND CHEQ_DEP='".$depos."' AND VALOR='".$vab."' ".
		   "ORDER BY CTA_BANCO ";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//para contar registro
		$i=0;
		$i=contar_registros($stmt);
		//echo $i.' -- '.$sql;
		//si no existe guardamos
		if($i==0)
		{
			$sql="INSERT INTO Asiento_B
           (CTA_BANCO ,BANCO ,CHEQ_DEP,EFECTIVIZAR,VALOR,ME,T_No,Item,CodigoU)
			VALUES
           ('".$banco."' ,'".$nbanco."','".$depos."','".$fecha."','".$vab."',0,1 ,
		   '".$_SESSION['INGRESO']['item']."' ,'".$_SESSION['INGRESO']['CodigoU']."')";
		   $stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
				FROM Asiento_B
				WHERE 
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$camne=array();
					grilla_generica($stmt,null,NULL,'1','0,2,clave','asi_b');
				}
			}
		}
		else
		{
			$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
			FROM Asiento_B
			WHERE 
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$camne=array();
				grilla_generica($stmt,null,NULL,'1','0,2,clave');
			}
			//echo " ENTROOO ";
			echo "<script>
						Swal.fire({
							type: 'error',
							title: 'No se pudo guardar registro',
							text: 'Ya existe un registro con estos datos',
							footer: ''
					})
			</script>";
		}
		
	}
	//categoria cuenta
	if($_POST['cl']=='ca_cu_b')
	{
		$keyword = str_replace(" ", " ", $keyword);
		$sql="SELECT Codigo,TC,Clave,Cuenta
			FROM Catalogo_Cuentas WHERE DG = 'D' AND Cuenta <> '.' 
			AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND ( Clave = '".$keyword."' ) ORDER BY Catalogo_Cuentas.Codigo";
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$row_count=0;
		$i=0;
		$Result = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$Result[$i]['Cod'] = $row[0];
			$Result[$i]['TC'] = $row[1];
			$Result[$i]['Cla'] = (int)$row[2];
			$Result[$i]['Cu'] = $row[3];
			//echo $Result[$i]['nombre'];
			$i++;
		}
		
		if($i==0)
		{
			$Result[0] = 'no existe registro';
			echo json_encode($Result);
		}
		else
		{
			echo json_encode($Result);
		}
	}
	//ingresar asiento sc
	if($_POST['cl']=='ing_sub1')
	{
		//echo " entrooo ";
		
		if($_POST['t']=='P' OR $_POST['t']=='C')
		{
			$sql=" SELECT codigo FROM clientes WHERE CI_RUC='".$_POST['sub']."' ";
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//echo $sql;
			$row_count=0;
			$i=0;
			$Result = array();
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$cod=$row[0];
			}
		}
		else
		{
			//echo ' nnnn ';
			$cod=$_POST['sub'];
		}
		//verificamos valor
		$SC_No=0;
		$sql=" SELECT MAX(SC_No) AS Expr1 FROM  Asiento_SC 
		where CodigoU ='".$_SESSION['INGRESO']['CodigoU']."' 
		AND item='".$_SESSION['INGRESO']['item']."'";
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//echo $sql;
		$row_count=0;
		$i=0;
		$Result = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$SC_No=$row[0];
		}
		if($SC_No==null)
		{
			$SC_No=1;
		}
		else
		{
			$SC_No++;
		}
		$fecha_actual=$_POST['fecha_sc'];
		if($_POST['fac2']==0)
		{
			$ot = explode("-",$fecha_actual);
			$fact2=$ot[0].$ot[1].$ot[2];
			
		}
		else
		{
			$fact2=$_POST['fac2'];
			
		}
		if($_POST['mes']==0)
		{
			$sql="INSERT INTO Asiento_SC(Codigo ,Beneficiario,Factura ,Prima,DH,Valor,Valor_ME
           ,Detalle_SubCta,FECHA_V,TC,Cta,TM,T_No,SC_No
           ,Fecha_D ,Fecha_H,Bloquear,Item,CodigoU)
			VALUES
           ('".$cod."'
           ,'".$_POST['sub2']."'
           ,'".$fact2."'
           ,0
           ,'".$_POST['tic']."'
           ,".$_POST['valorn']."
           ,0
           ,'".$_POST['Trans']."'
           ,'".$fecha_actual."'
           ,'".$_POST['t']."'
           ,'".$_POST['co']."'
           ,".$_POST['moneda']."
           ,".$_POST['T_N']."
           ,".$SC_No."
           ,null
           ,null
           ,0
           ,'".$_SESSION['INGRESO']['item']."'
           ,'".$_SESSION['INGRESO']['CodigoU']."')";
		   $stmt = sqlsrv_query( $cid, $sql);
		   //echo $sql;
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
		}
		else
		{
			$sql="INSERT INTO Asiento_SC(Codigo ,Beneficiario,Factura ,Prima,DH,Valor,Valor_ME
			,Detalle_SubCta,FECHA_V,TC,Cta,TM,T_No,SC_No
			,Fecha_D ,Fecha_H,Bloquear,Item,CodigoU)
			VALUES
			";
			$dia=0;
			for ($i=0;$i<$_POST['mes'];$i++)
			{
				$sql=$sql."('".$cod."'
			   ,'".$_POST['sub2']."'
			   ,'".$fact2."'
			   ,0
			   ,'".$_POST['tic']."'
			   ,".$_POST['valorn']."
			   ,0
			   ,'".$_POST['Trans']."'
			   ,'".$fecha_actual."'
			   ,'".$_POST['t']."'
			   ,'".$_POST['co']."'
			   ,".$_POST['moneda']."
			   ,".$_POST['T_N']."
			   ,".$SC_No."
			   ,null
			   ,null
			   ,0
			   ,'".$_SESSION['INGRESO']['item']."'
			   ,'".$_SESSION['INGRESO']['CodigoU']."'),";
			   $SC_No++;
			   $ot = explode("-",$fecha_actual);
			   if($ot[1]=='01')
			   {
				    if($ot[2]>=28)
				    {
					   $dia=$ot[2];
					    $year=esBisiesto_ajax($ot[0]);
						if($year==1)
						{
							$fecha_actual = date("Y-m-d",strtotime($ot[0].'-02-29')); 
							if($_POST['fac2']==0)
							{
								$fact2 = date("Ymd",strtotime($ot[0].'0229')); 
							}
							//$fact2 = $ot[0].'0229'; 
						}
						else
						{
							$fecha_actual = date("Y-m-d",strtotime($ot[0].'-02-28')); 
							 if($_POST['fac2']==0)
							{
								$fact2 = date("Ymd",strtotime($ot[0].'0228')); 
							}
						}
				    }
					else
					{
						$fecha_actual = date("Y-m-d",strtotime($fecha_actual."+ 1 month")); 
					   if($_POST['fac2']==0)
						{
							$fact2 = date("Ymd",strtotime($fact2."+ 1 month")); 
						}
					}
				   
			   }
			   else
			   {
					//$ot = explode("-",$fecha_actual);
					//if($ot[1]=='03')
					//{
					/*if( $dia>=28)
					{
						$ot = explode("-",$fecha_actual);
						$fecha_actual = date("Y-m-d",strtotime($ot[0].'-03-31')); 
						if($_POST['fac2']==0)
						{
							$fact2 = date("Ymd",strtotime($ot[0].'0331')); 
						}
						$dia=0;
					}*/
					//else
					//{
						
						if( $dia>=28)
						{
							$ot = explode("-",$fecha_actual);
							if($ot[1]=='02')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-03-31')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0331')); 
								}
							}
							if($ot[1]=='03')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-04-30')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0430')); 
								}
							}
							if($ot[1]=='04')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-05-31')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0531')); 
								}
							}
							if($ot[1]=='05')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-06-30')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0630')); 
								}
							}
							if($ot[1]=='06')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-07-31')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0731')); 
								}
							}
							if($ot[1]=='07')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-08-31')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0831')); 
								}
							}
							if($ot[1]=='08')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-09-30')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'0930')); 
								}
							}
							if($ot[1]=='09')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-10-31')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'1031')); 
								}
							}
							if($ot[1]=='10')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-11-30')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'1130')); 
								}
							}
							if($ot[1]=='11')
							{
								$fecha_actual = date("Y-m-d",strtotime($ot[0].'-12-31')); 
								if($_POST['fac2']==0)
								{
									$fact2 = date("Ymd",strtotime($ot[0].'1231')); 
								}
							}
						}
						else
						{
							$fecha_actual = date("Y-m-d",strtotime($fecha_actual."+ 1 month")); 
							if($_POST['fac2']==0)
							{
								$fact2 = date("Ymd",strtotime($fact2."+ 1 month")); 
							}
						}
					//}
					//}
			    }
			  // echo $fecha_actual.' <br>';
			}
			//reemplazo una parte de la cadena por otra
			$longitud_cad = strlen($sql); 
			$cam2 = substr_replace($sql,"",$longitud_cad-1,1); 
			$stmt = sqlsrv_query( $cid, $cam2);
		    //echo $sql;
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//echo $cam2;
		}
			$sql="SELECT Codigo, Beneficiario, Factura, Prima, DH, Valor, Valor_ME, Detalle_SubCta,T_No, SC_No,Item, CodigoU
			FROM Asiento_SC
			WHERE 
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$camne=array();
				?>
				<div class="row">
					<div class="col-md-16 col-sm-16 col-xs-16" id='tabla_b' style="height: 70px; overflow-y: scroll;">
						<input type="hidden" id='reg1' name='reg1'  value='' />	
				<?php
				grilla_generica($stmt,null,NULL,'1','8,9,10,11,clave1','asi_sc');
				?>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>TOTAL M/N</b></button>
							
							</div>
							<input type="text" class="xs" id="totald" name="totald" placeholder="0.00" value="0,00" maxlength="20" size="21" style="text-align:right;">
							
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-2 col-sm-2 col-xs-2">
						<div class="input-group">
							<div class="input-group-btn">
								<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>TOTAL M/E</b></button>
							
							</div>
							<input type="text" class="xs" id="totald" name="totald" placeholder="0.00" value="0,00" maxlength="20" size="21" style="text-align:right;">
							
						</div>
					</div>
				</div>
				<?php
			}
		  // echo $sql;
	}
	//ingresar comprobante
	if($_POST['cl']=='ing_com')
	{
		if(isset($_POST['cotizacion']))
		{
			if($_POST['cotizacion']=='' or $_POST['cotizacion']==null)
			{
				$_POST['cotizacion']=0;
			}
		}
		else
		{
			$_POST['cotizacion']=0;
		}
		//echo $_POST['ru'].'<br>';
		if($_POST['ru']=='000000000')
		{
			$codigo_b='.';
		}
		else
		{
			//buscamos codigo
			$sql=" 	SELECT Codigo
					FROM Clientes
					WHERE (CI_RUC = '".$_POST['ru']."') ";
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$codigo_b=$row[0];
				}
			}
			//$codigo_b=$_POST['ru'];
		}
		//buscamos total
		if($_POST['tip']=='CE' or $_POST['tip']=='CI')
		{
			$sql="SELECT        SUM( DEBE) AS db, SUM(HABER) AS ha
			FROM            Asiento
			where T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."'  AND CUENTA 
			in (select Cuenta FROM  Catalogo_Cuentas 
			where Catalogo_Cuentas.Cuenta=Asiento.CUENTA AND (Catalogo_Cuentas.TC='CJ' OR Catalogo_Cuentas.TC='BA'))";
			
			$stmt = sqlsrv_query( $cid, $sql);
			$totald=0;
			$totalh=0;
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$totald=$row[0];
					$totalh=$row[1];
				}
			}
			if($_POST['tip']=='CE')
			{
				$_POST['totalh']=$totalh;
			}
			if($_POST['tip']=='CI')
			{
				$_POST['totalh']=$totald;
			}
		}
		
		if($_POST['concepto']=='')
		{
			$_POST['concepto']='.';
		}
		$num_com = explode("-", $_POST['num_com']);
		$sql="INSERT INTO Comprobantes
           (Periodo ,Item,T ,TP,Numero ,Fecha ,Codigo_B,Presupuesto,Concepto,Cotizacion,Efectivo,Monto_Total
           ,CodigoU ,Autorizado,Si_Existe ,Hora,CEj,X)
		   VALUES
           ('".$_SESSION['INGRESO']['periodo']."'
           ,'".$_SESSION['INGRESO']['item']."'
           ,'N'
           ,'".$_POST['tip']."'
           ,".$num_com[1]."
           ,'".$_POST['fecha1']."'
           ,'".$codigo_b."'
           ,0
           ,'".$_POST['concepto']."'
           ,'".$_POST['cotizacion']."'
           ,0
           ,'".$_POST['totalh']."'
           ,'".$_SESSION['INGRESO']['CodigoU']."'
           ,'.'
           ,0
           ,'".date('h:i:s')."'
           ,'.'
           ,'.')";
		    //echo $sql.'<br>';
		    $stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
		   //consultamos transacciones
		   $sql="SELECT CODIGO,CUENTA,PARCIAL_ME  ,DEBE ,HABER ,CHEQ_DEP ,DETALLE ,EFECTIVIZAR,CODIGO_C,CODIGO_CC
				,ME,T_No,Item,CodigoU ,A_No,TC
				FROM Asiento
				WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
			
			$sql=$sql." ORDER BY A_No ";
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			else
			{
				$i=0;
				$ii=0;
				$Result = array();
				$fecha_actual = date("Y-m-d"); 
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$Result[$i]['CODIGO']=$row[0];
					$Result[$i]['CHEQ_DEP']=$row[5];
					$Result[$i]['DEBE']=$row[3];
					$Result[$i]['HABER']=$row[4];
					$Result[$i]['PARCIAL_ME']=$row[2];
					$Result[$i]['EFECTIVIZAR']=$row[7]->format('Y-m-d');
					$Result[$i]['CODIGO_C']=$row[8];
					
					$sql=" INSERT INTO Transacciones
				    (Periodo ,T,C ,Cta,Fecha,TP ,Numero,Cheq_Dep,Debe ,Haber,Saldo ,Parcial_ME ,Saldo_ME ,Fecha_Efec ,Item ,X ,Detalle
				    ,Codigo_C,Procesado,Pagar,C_Costo)
					 VALUES
				    ('".$_SESSION['INGRESO']['periodo']."'
				    ,'N'
				    ,0
				    ,'".$Result[$i]['CODIGO']."'
				    ,'".$_POST['fecha1']."'
				    ,'".$_POST['tip']."'
				    ,".$num_com[1]."
				    ,'".$Result[$i]['CHEQ_DEP']."'
				    ,".$Result[$i]['DEBE']."
				    ,".$Result[$i]['HABER']."
				    ,0
				    ,".$Result[$i]['PARCIAL_ME']."
				    ,0
				    ,'".$Result[$i]['EFECTIVIZAR']."'
				    ,'".$_SESSION['INGRESO']['item']."'
				    ,'.'
				    ,'.'
				    ,'".$Result[$i]['CODIGO_C']."'
				    ,0
				    ,0
				    ,'.');";
				   // echo $sql.'<br>';
					$stmt1 = sqlsrv_query( $cid, $sql);
					if( $stmt1 === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					$i++;
				}
				$sql="SELECT  Codigo,Beneficiario,Factura,Prima,DH,Valor ,Valor_ME,Detalle_SubCta,FECHA_V ,TC,Cta,TM
				,T_No,SC_No,Fecha_D,Fecha_H,Bloquear,Item,CodigoU
				FROM Asiento_SC
				WHERE 
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
				$stmt = sqlsrv_query(   $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				else
				{
					$i=0;
					$Result = array();
					$fecha_actual = date("Y-m-d"); 
					while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
					{
						$Result[$i]['TC']=$row[9];
						$Result[$i]['Cta']=$row[10];
						$Result[$i]['FECHA_V']=$row[8]->format('Y-m-d');
						$Result[$i]['Codigo']=$row[0];
						$Result[$i]['Factura']=$row[2];
						$Result[$i]['Prima']=$row[3];
						$Result[$i]['DH']=$row[4];
						if($Result[$i]['DH']==1)
						{
							$Result[$i]['DEBITO']=$row[5];
							$Result[$i]['HABER']=0;
						}
						if($Result[$i]['DH']==2)
						{
							$Result[$i]['DEBITO']=0;
							$Result[$i]['HABER']=$row[5];
						}
						$sql="INSERT INTO Trans_SubCtas
							   (Periodo ,T,TC,Cta,Fecha,Fecha_V,Codigo ,TP,Numero ,Factura ,Prima ,Debitos ,Creditos ,Saldo_MN,Parcial_ME
							   ,Saldo_ME,Item,Saldo ,CodigoU,X,Comp_No,Autorizacion,Serie,Detalle_SubCta,Procesado)
						 VALUES
							   ('".$_SESSION['INGRESO']['periodo']."'
							   ,'N'
							   ,'".$Result[$i]['TC']."'
							   ,'".$Result[$i]['Cta']."'
							   ,'".$_POST['fecha1']."'
							   ,'".$Result[$i]['FECHA_V']."'
							   ,'".$Result[$i]['Codigo']."'
							   ,'".$_POST['tip']."'
							   ,".$num_com[1]."
							   ,".$Result[$i]['Factura']."
							   ,".$Result[$i]['Prima']."
							   ,".$Result[$i]['DEBITO']."
							   ,".$Result[$i]['HABER']."
							   ,0
							   ,0
							   ,0
							   ,'".$_SESSION['INGRESO']['item']."'
							   ,0
							   ,'".$_SESSION['INGRESO']['CodigoU']."'
							   ,'.'
							   ,0
							   ,'.'
							   ,'.'
							   ,'.'
							   ,0)";
						//echo $sql.'<br>';
						$stmt = sqlsrv_query( $cid, $sql);
						if( $stmt === false)  
						{  
							 echo "Error en consulta PA.\n";  
							 die( print_r( sqlsrv_errors(), true));  
						}
					}
				}
				//incrementamos el secuencial
				if($_SESSION['INGRESO']['Num_CD']==1)
				{
					//para variable en html
					$num1=$num_com[1];
					$num_com[1]=$num_com[1]+1;
					//echo $num_com[1].'<br>'.$_POST['tip'].'<br>';
					if(isset($_POST['fecha1']))
					{
						//echo $_POST['fecha'];
						$fecha_actual = $_POST['fecha1']; 
					}
					else
					{
						$fecha_actual = date("Y-m-d"); 
					}
					$ot = explode("-",$fecha_actual);
					if($_POST['tip']=='CD')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Diario')";
					}
					if($_POST['tip']=='CI')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Ingresos')";
					}
					if($_POST['tip']=='CE')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."Egresos')";
					}
					if($_POST['tip']=='ND')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."NotaDebito')";
					}
					if($_POST['tip']=='NC')
					{
						$sql ="UPDATE Codigos set Numero=".$num_com[1]."
						WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
						AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
						AND (Concepto = '".$ot[1]."NotaCredito')";
					}
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//borramos temporales asientos
					$sql="DELETE FROM Asiento
					WHERE 
					T_No=".$_SESSION['INGRESO']['modulo_']." AND
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//borramos temporales asientos bancos
					
					$sql="DELETE FROM Asiento_B
					WHERE 
					Item = '".$_SESSION['INGRESO']['item']."' 
					AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//echo $sql;
					$stmt = sqlsrv_query( $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//borramos asiento subcuenta
					$sql="DELETE FROM Asiento_SC
					WHERE 
						Item = '".$_SESSION['INGRESO']['item']."' 
						AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
					$stmt = sqlsrv_query(   $cid, $sql);
					if( $stmt === false)  
					{  
						 echo "Error en consulta PA.\n";  
						 die( print_r( sqlsrv_errors(), true));  
					}
					//generamos comprobante
					//reporte_com($num1);
					echo "<input type='hidden' id='num_com1' name='num_com1'  value='".$num1."' />";
				}
			}
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion buscar generica
function buscar($cl=null)
{
	$cid=cone_ajaxSQL();
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	
	if($_POST['cl']=='cl_b')
	{
		$keyword = strval($_POST['com']);
		$sql="SELECT Cliente,Codigo,CI_RUC,Email ". 
		   "FROM Clientes ".
		   "WHERE T <> '.' AND (CI_RUC = '".$keyword."' ) ".
		   "ORDER BY Cliente ";
		
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$row_count=0;
		$i=0;
		$Result = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$Result[$i]['nom'] = $row[0];
			$Result[$i]['Cod'] = $row[1];
			$Result[$i]['CI'] = $row[2];
			$Result[$i]['Em'] = $row[3];
			//echo $Result[$i]['nombre'];
			$i++;
		}
		
		if($i==0)
		{
			$Result[0] = 'no existe registro';
			echo json_encode($Result);
		}
		else
		{
			echo json_encode($Result);
		}
	}
	//categoria cuenta
	if($_POST['cl']=='ca_cu_b')
	{
		$keyword = strval($_POST['com']);
		$ot = $_POST['ot'];
		$ot = explode(" ", $ot);
		$keyword = str_replace(" ", " ", $keyword);
		$sql="SELECT Codigo,TC,Clave,Cuenta
			FROM Catalogo_Cuentas WHERE DG = 'D' AND Cuenta <> '.' 
			AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
			AND ( Clave = '".$keyword."' ) AND ( Codigo = '".$ot[0]."') ORDER BY Catalogo_Cuentas.Codigo";
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$row_count=0;
		$i=0;
		$Result = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$Result[$i]['Cod'] = $row[0];
			$Result[$i]['TC'] = $row[1];
			$Result[$i]['Cla'] = (int)$row[2];
			$Result[$i]['Cu'] = $row[3];
			//echo $Result[$i]['nombre'];
			$i++;
		}
		
		if($i==0)
		{
			$Result[0] = 'no existe registro';
			echo json_encode($Result);
		}
		else
		{
			echo json_encode($Result);
		}
	}
	//catalogosubcuenta
	//categoria cuenta
	if($_POST['cl']=='cat_sub')
	{
		if($_POST['t']=='G' or $_POST['t']=='I' or $_POST['t']=='CC')
		{
			?>
			<select class="form-control" name="subcuenta" id='subcuenta' 
			size="5" >
				<option value='0'>Seleccione Opcion</option>
				<?php select_option_a('Catalogo_SubCtas','ID','Detalle',
				" TC='".$_POST['t']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ORDER BY Detalle  "); ?>
			</select>
			<?php
			
		}
		if($_POST['t']=='C' or $_POST['t']=='P' )
		{
			?>
			<select class="form-control" name="subcuenta" id='subcuenta' onChange='buscarsub()'
			size="5" >
			
			
				<option value='0'>Seleccione Opcion</option>
				<?php select_option_a('Clientes','CI_RUC','Cliente',"  (Codigo IN  (SELECT Codigo FROM Catalogo_CxCxP WHERE 
				(Codigo = Clientes.Codigo) AND Codigo <>'.' AND Cta='".$_POST['co']."' AND (TC = '".$_POST['t']."') AND Item = '".$_SESSION['INGRESO']['item']."' 
				AND Periodo = '".$_SESSION['INGRESO']['periodo']."')) order by Cliente ",$_POST['ru']); ?>
			</select>
			<?php
			
		}
	}
	//trans subcuenta
	if($_POST['cl']=='trans_sub1')
	{
		//echo $_POST['be'].' '.$_POST['ru'].' '.$_POST['co'].' '.$_POST['tip'].' '.$_POST['tic'].' '.$_POST['t'].' ';
		//buscamos codigo de beneficiario
		if($_POST['t']=='C' OR $_POST['t']=='P')
		{
			$sql="SELECT *	FROM Clientes WHERE  ( CI_RUC = '".$_POST['sub']."' ) ORDER BY Cliente";
			//echo $sql;
			//die();
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$row_count=0;
			$i=0;
			$Result = array();
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$Result[$i]['Cod'] = $row[2];
				
				//echo $Result[$i]['nombre'];
				$i++;
			}
		}
		if($_POST['t']=='G' or $_POST['t']=='I' or $_POST['t']=='CC')
		{
			?>
			<select class="form-control" name="fac2" id='fac2' style='width:100%; ' class="xs"
			 >
				<option value='0'>Facturas</option>
				<?php select_option_a('Catalogo_SubCtas','ID','Detalle'," TC='".$_POST['t']."' AND Item = '".$_SESSION['INGRESO']['item']."' AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ORDER BY Detalle  "); ?>
			</select>
			<?php
			
		}
		if($_POST['t']=='C' and $_POST['tic']=='2' )
		{
			?>
			<select class="form-control" name="fac2" id='fac2' style='width:100%; ' class="xs"
			 >
				<option value='0'>Facturas</option>
				<?php 
				//falta Fecha
				select_option_a('Trans_SubCtas','Factura','Factura',"  (Cta = '".$_POST['co']."') 
				AND (Codigo = '".$Result[0]['Cod']."') AND (TC = '".$_POST['t']."') AND T\<\>'A' AND  
				Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' 
				GROUP BY Factura 
				HAVING (SUM(Debitos)-SUM(Creditos))>0"); ?>
			</select>
			<?php
			
		}
		if($_POST['t']=='C' and $_POST['tic']=='1' )
		{
			?>
			<input type="text" class="xs" id="fac2" name='fac2' placeholder="" maxlength='30' size='8' value='0'>
			<?php
		}
		if($_POST['t']=='P' and $_POST['tic']=='1' )
		{
			?>
			<select class="form-control" name="fac2" id='fac2' style='width:100%; ' class="xs"
			 >
				<?php select_option_a('Trans_SubCtas','Factura','Factura',"  (Cta = '".$_POST['co']."') 
				AND (Codigo = '".$Result[0]['Cod']."') AND (TC = '".$_POST['t']."') AND T<>'A' AND  
				Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Item = '".$_SESSION['INGRESO']['item']."' 
				GROUP BY Factura 
				HAVING (SUM(Creditos)-SUM(Debitos))>0 "); ?>
			</select>
			<?php
			
		}
		if($_POST['t']=='P' and $_POST['tic']=='2' )
		{
			?>
			<input type="text" class="xs" id="fac2" name='fac2' placeholder="" maxlength='30' size='8' value='0'>
			<?php
		}
	}
	if($_POST['cl']=='num_com' or $cl=='num_com')
	{
		
		/*$sql="SELECT  Num_CD, Num_CE, Num_CI, Num_ND, Num_NC
        FROM Empresas where Item='".$_SESSION['INGRESO']['item']."' ";
		
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$row_count=0;
		$i=0;
		$Result = array();
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$Result[$i]['Num_CD'] = $row[0];
			$Result[$i]['Num_CE'] = $row[1];
			$Result[$i]['Num_CI'] = $row[2];
			$Result[$i]['Num_ND'] = $row[3];
			$Result[$i]['Num_NC'] = $row[4];
			//echo $Result[$i]['nombre'];
			$i++;
		}
		
		if($i==0)
		{
			echo 'no existe registro';
			//echo json_encode($Result);
		}*/
		//echo $_POST['tip'].' '.$_SESSION['INGRESO']['Num_CD'].' '.$_SESSION['INGRESO']['Num_CE'].' '.
		//$_SESSION['INGRESO']['Num_CI'].' '.$_SESSION['INGRESO']['Num_ND'].' '.$_SESSION['INGRESO']['Num_NC'].' ';
		if(isset($_POST['fecha']))
		{
			//echo $_POST['fecha'];
			$fecha_actual = $_POST['fecha']; 
		}
		else
		{
			$fecha_actual = date("Y-m-d"); 
		}
		$ot = explode("-",$fecha_actual);
		if($_POST['tip']=='CD')
		{
			if($_SESSION['INGRESO']['Num_CD']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."Diario')";
				
				//echo $sql;
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Diario No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}
		if($_POST['tip']=='CI')
		{
			if($_SESSION['INGRESO']['Num_CI']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."Ingresos')";
				
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Ingreso No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}
		if($_POST['tip']=='CE')
		{
			if($_SESSION['INGRESO']['Num_CE']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."Egresos')";
				
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Egreso No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}
		if($_POST['tip']=='NC')
		{
			if($_SESSION['INGRESO']['Num_NC']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."NotaCredito')";
				
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Nota de Credito No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}
		if($_POST['tip']=='ND')
		{
			if($_SESSION['INGRESO']['Num_ND']==1)
			{
				$sql ="SELECT        Periodo, Item, Concepto, Numero, ID
				FROM            Codigos
				WHERE        (Item = '".$_SESSION['INGRESO']['item']."') 
				AND (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
				AND (Concepto = '".$ot[1]."NotaDebito')";
				
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				$row_count=0;
				$i=0;
				$Result = array();
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					
					$Result[$i]['Numero'] = $row[3];
					
					//echo $Result[$i]['nombre'];
					$i++;
				}
				$codigo=$Result[0]['Numero']++;
				echo "Comprobante de Nota de Debito No. ".$ot[0].'-'.$codigo;
				if($i==0)
				{
					echo 'no existe registro';
					//echo json_encode($Result);
				}
			}
		}
		
		/*
		SELECT        Periodo, Item, Concepto, Numero, ID
		FROM            Codigos
		WHERE        (Item = '002') AND (Periodo = '.') AND (Concepto = '12Diario')
		*/
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion autocompletar generica
function autocompletar1()
{
	$cid=cone_ajaxSQL();
	$sql='';
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	$keyword = strval($_REQUEST['query']);
	if($_REQUEST['cl']=='ca_cu_a')
	{
		$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
			   FROM Catalogo_Cuentas 
			   WHERE DG = 'D' 
			   AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
			   AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND ( Cuenta LIKE '%".$keyword."%' OR Codigo LIKE '%".$keyword."%')
			   ORDER BY Codigo";
		
	}
	if($_REQUEST['cl']=='ca_cu_a1')
	{
		//$keyword = strval($_REQUEST['keyword']);
		//echo " entro 1 ";
		//die();
		if(strlen($keyword)==1)
		{
			//echo " entro 2 ";
			if(is_numeric($keyword))
			//if (ctype_alnum($keyword)) 
			{
				//echo " entro 3 ";
					$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
				    FROM Catalogo_Cuentas 
				    WHERE DG = 'D' 
				    AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
				    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo = '".$keyword."' ORDER BY Codigo";
				
			}
			else
			{
				if($keyword=='N' OR $keyword=='n')
				{
					//echo " entro 4 ";
					$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
				    FROM Catalogo_Cuentas 
				    WHERE DG = 'D' 
				    AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
				    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ORDER BY Codigo";
				}
				else
				{
					//echo " entro 9 ";
					$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
				    FROM Catalogo_Cuentas 
				    WHERE DG = 'D' 
				    AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
				    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND TC = '".$keyword."' ORDER BY Codigo";
				}
			}
		}
		else
		{
			//echo " entro 6 ";
			if(is_numeric($keyword))
			//if (ctype_alnum($keyword)) 
			{
				//echo " entro 7 ";
				$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
				   FROM Catalogo_Cuentas 
				   WHERE DG = 'D' 
				   AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
				   AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND ( Codigo LIKE '%".$keyword."%' OR  Clave LIKE '%".$keyword."%')
				   ORDER BY Codigo";
			}
			else
			{
				//echo " entro 8 ";
				$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC  
				   FROM Catalogo_Cuentas 
				   WHERE DG = 'D' 
				   AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
				   AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND ( Cuenta LIKE '%".$keyword."%' OR 
				   Codigo LIKE '%".$keyword."%' OR TC LIKE '%".$keyword."%' OR Clave LIKE '%".$keyword."%')
				   ORDER BY Codigo";
			}
		}
		//echo $sql;
	}
	if($_REQUEST['cl']=='ca_cu_a2')
	{
		//echo " entro 1 ";
		//echo strlen($keyword);
		if(!is_numeric($keyword))
		{
			if(strlen($keyword)<=2)
			{
				//echo " entro 2 ";
					$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+
					Cuenta As Nombre_Cuenta,Codigo,Cuenta,TC 
				    FROM Catalogo_Cuentas 
				    WHERE DG = 'D' 
				    AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
				    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND TC = '".$keyword."' ORDER BY Codigo";
				
			}
			else
			{
				//echo " entro 3 ";
				$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
				+Space(5-LEN(cast( Clave as varchar(5))))+' '+
				Cuenta As Nombre_Cuenta ,Codigo,Cuenta,TC
					FROM Catalogo_Cuentas 
					WHERE DG = 'D' 
					AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo like '".$keyword."%' ORDER BY Codigo";
			}
		}
		else
		{
			//echo " entro 4 ";
			if(strlen($keyword)<=2)
			{
				//echo " entro 5 ";
					$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
					+Space(5-LEN(cast( Clave as varchar(5))))+' '+
					Cuenta As Nombre_Cuenta ,Codigo,Cuenta,TC
				    FROM Catalogo_Cuentas 
				    WHERE DG = 'D' 
				    AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
				    AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Clave = '".$keyword."' ORDER BY Codigo";
				
			}
			else
			{
				//echo " entro 6 ";
				$sql="SELECT Codigo+Space(19-LEN(Codigo))+''+TC+Space(3-LEN(TC))+''+cast( Clave as varchar(5))+''
				+Space(5-LEN(cast( Clave as varchar(5))))+' '+
				Cuenta As Nombre_Cuenta ,Codigo,Cuenta,TC
					FROM Catalogo_Cuentas 
					WHERE DG = 'D' 
					AND Cuenta <> '".$_SESSION['INGRESO']['ninguno']."' AND Item = '".$_SESSION['INGRESO']['item']."'
					AND Periodo = '".$_SESSION['INGRESO']['periodo']."' AND Codigo like '".$keyword."%' ORDER BY Codigo";
			}
			
		}
	}
	if($_REQUEST['cl']=='cl_a')
	{
		$sql="SELECT Cliente AS nombre, CI_RUC as id, email ". 
		   "FROM Clientes ".
		   "WHERE T <> '.' AND (Cliente LIKE '%".$keyword."%' ) OR  (CI_RUC LIKE '%".$keyword."%') ".
		   "ORDER BY Cliente ";
		
	}
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$row_count=0;
	$i=0;
	$Result = array();
	//echo "<script> ";
	//echo " var countries = [];";
	//echo " var countries = [ ";
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		
		$row[0] = str_replace("'", " ", $row[0]);
		$row[0] = str_replace(" ", "&nbsp;", $row[0]);
		//$row[0] = str_replace("-", ".", $row[0]);
		$Result[$i]['nombre'] = utf8_encode($row[0]);
		if($_REQUEST['cl']=='ca_cu_a' or $_REQUEST['cl']=='ca_cu_a1' or 
		$_REQUEST['cl']=='ca_cu_a2' )
		{
			$Result[$i]['id'] = utf8_encode($row[1]);
			//$row[0] = str_replace("-", "..", $row[2]);
			$Result[$i]['cuenta'] = utf8_encode($row[2]);
			$Result[$i]['tc'] = utf8_encode($row[3]);
		}
		if($_REQUEST['cl']=='cl_a')
		{
			$Result[$i]['id'] = utf8_encode($row[1]);
			$Result[$i]['email'] = utf8_encode($row[2]);
		}
		//echo" '".$Result[$i]['nombre']."',";
		//$Result[$i]['id1'] = $row[1];
		//echo $Result[$i]['nombre'];
		$i++;
	}
	//echo " '' ];";
	//echo "</script> ";
	if($i==0)
	{
		$Result[$i]['nombre']  = 'no existe registro';
		echo json_encode($Result);
	}
	else
	{
		echo json_encode($Result);
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion autocompletar generica
function autocompletar()
{
	$cid=cone_ajaxSQL();
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	$keyword = strval($_POST['query']);
	if($_POST['cl']=='cla')
	{
		$sql="SELECT Cliente,Codigo,CI_RUC ". 
		   "FROM Clientes ".
		   "WHERE T <> '.' AND ( CLIENTE LIKE '%".$keyword."%' OR CI_RUC LIKE '%".$keyword."%') ".
		   "ORDER BY Cliente ";
	}
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$row_count=0;
	$i=0;
	$Result = array();
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$Result[$i]['nombre'] = $row[0];
		//$Result[$i]['id1'] = $row[1];
		//echo $Result[$i]['nombre'];
		$i++;
	}
	
	if($i==0)
	{
		$Result[0] = 'no existe registro';
		echo json_encode($Result);
	}
	else
	{
		echo json_encode($Result);
	}
	cerrarSQLSERVERFUN($cid);
}
//eliminar generica
function eliminar()
{
	$cid=cone_ajaxSQL();
	$clave=$_POST['clave'];
	$cl=$_POST['cl'];
	//caso eliminar tabla Asiento_B
	if($cl=='asi_b')
	{
		$clave1 = explode("--", $clave);
		/*if(count($clave1))
		{
			for($i=0;$i<(count($clave1)-1);$i++)
			{
				
			}
		}*/
		$sql="Delete from Asiento_B ".
		   "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
		   "AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ".
		   "AND CTA_BANCO='".$clave1[0]."' ".
		   "AND CHEQ_DEP='".$clave1[1]."' ";
	}
	//caso eliminar tabla Asiento
	if($cl=='asi')
	{
		$clave1 = explode("--", $clave);
		/*if(count($clave1))
		{
			for($i=0;$i<(count($clave1)-1);$i++)
			{
				
			}
		}*/
		$sql="Delete from Asiento ".
		   "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
		   "AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ".
		   "AND A_No='".$clave1[0]."' ".
		   "AND CODIGO='".$clave1[1]."' ";
		   
	}
	//caso eliminar tabla Asiento sc
	if($cl=='asi_sc')
	{
		$clave1 = explode("--", $clave);
		/*if(count($clave1))
		{
			for($i=0;$i<(count($clave1)-1);$i++)
			{
				
			}
		}*/
		$sql="Delete from Asiento_sc ".
		   "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
		   "AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ".
		   "AND T_No ='".$clave1[0]."' ".
		   "AND SC_No ='".$clave1[1]."' ";
	}
	//echo $sql.' '.$clave.' '.count($clave1);
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	//$stmt = false;
	if( $stmt === false)  
	{  
		  $jsondata = array('success' => false);
		// die( print_r( sqlsrv_errors(), true));  
	}
	else
	{
		//$_SESSION['INGRESO']['Cambio']=1;
		//recalcular totales
		$sql="SELECT (SUM(DEBE)-SUM(HABER)) AS DIFERENCIA, SUM(DEBE) AS DEBE ,SUM(HABER) AS HABER 
			FROM Asiento
			WHERE 
				T_No=".$_SESSION['INGRESO']['modulo_']." AND
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
		//echo $sql;
		$stmt = sqlsrv_query( $cid, $sql);
		//$stmt = false;
		$dif=0.00;
		$totd=0.00;
		$toth=0.00;
		if( $stmt == true)  
		{  
			
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$dif=number_format($row[0],2, '.', ',');
				$totd=number_format($row[1],2, '.', ',');
				$toth=number_format($row[2],2, '.', ',');
			}
			// die( print_r( sqlsrv_errors(), true));  
		}
		$jsondata = array('success' => true, 'name'=>$clave1[0],'dif'=>$dif,'totd'=>$totd,'toth'=>$toth);
	}
	cerrarSQLSERVERFUN($cid);
	 //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata);
    exit();
}
function mindividual()
{
	$cid = Conectar::conexion('MYSQL');
	$sql = "UPDATE lista_empresas set Mensaje='".$_POST['campo3']."' WHERE ID_Empresa='".$_POST['campo1']."' ";
	//echo $sql;
	//die();
	$consulta=$cid->query($sql);// or die($cid->error);
	//$stmt = sqlsrv_query( $cid, $sql);

	if( $consulta === false)  
	{  
		// echo "Error en consulta.\n";  
		 $jsondata = array('success' => false);
		 //die( print_r( sqlsrv_errors(), true));  
	}
	else
	{	
		$_SESSION['INGRESO']['Cambio']=1;
		$jsondata = array('success' => true, 'name'=>$_POST['campo1']);
	}
	$cid->close();
	 //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata);
    exit();

	
}
function mmasivo()
{
	$cid = Conectar::conexion('MYSQL');
	$sql = "UPDATE lista_empresas set Mensaje='".$_POST['campo3']."' ";
	//echo $sql;
	//die();
	$consulta=$cid->query($sql);// or die($cid->error);
	//$stmt = sqlsrv_query( $cid, $sql);

	if( $consulta === false)  
	{  
		// echo "Error en consulta.\n";  
		 $jsondata = array('success' => false);
		 //die( print_r( sqlsrv_errors(), true));  
	}
	else
	{	
		$_SESSION['INGRESO']['Cambio']=1;
		$jsondata = array('success' => true, 'name'=>$_POST['campo1']);
	}
	$cid->close();
	 //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata);
    exit();

	
}
function modificarEmpresaMa()
{
	$cid = Conectar::conexion('MYSQL');
	$sql = "UPDATE lista_empresas set Fecha='".$_POST['campo11']."' , Fecha_VPN='".$_POST['campo12']."' , Fecha_CE='".$_POST['campo4']."'  
	WHERE ID_Empresa='".$_POST['campo13']."'";
	//echo $sql;
	//die();
	$consulta=$cid->query($sql);// or die($cid->error);
	//$stmt = sqlsrv_query( $cid, $sql);

	if( $consulta === false)  
	{  
		// echo "Error en consulta.\n";  
		 $jsondata = array('success' => false);
		 //die( print_r( sqlsrv_errors(), true));  
	}
	else
	{	
		$_SESSION['INGRESO']['Cambio']=1;
		$jsondata = array('success' => true, 'name'=>$_POST['campo1']);
	}
	$cid->close();
	 //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata);
    exit();

	
}
function modificarEmpresa()
{
	$cid = Conectar::conexion('MYSQL');
	$sql = "SELECT *
			  FROM lista_empresas
			  WHERE ID = '".$_POST['campo1']."';";
	//echo $sql;
	//die();
	 if ($resultado = $cid->query($sql)) {

		/* Obtener la información del campo para todas las columnas */
		$info_campo = $resultado->fetch_fields();
		$i=0;
		foreach ($info_campo as $valor) {
			if($i==15)
			{
				$contra=$valor->name;
			}
			$i++;
		}
		$resultado->free();
	}
	$sql = "UPDATE lista_empresas set Estado='".$_POST['campo2']."',Mensaje='".$_POST['campo3']."',
	Fecha_CE='".$_POST['campo4']."' ,IP_VPN_RUTA='".$_POST['campo5']."',
	Base_Datos='".$_POST['campo6']."' ,Usuario_DB='".$_POST['campo7']."',
	`".$contra."`='".$_POST['campo8']."' ,Tipo_Base='".$_POST['campo9']."',
    Puerto='".$_POST['campo10']."',Fecha='".$_POST['campo11']."',Fecha_VPN='".$_POST['campo12']."' WHERE ID='".$_POST['campo1']."' ";
	//echo $sql;
	//die();
	$consulta=$cid->query($sql) or die($cid->error);
	//$stmt = sqlsrv_query( $cid, $sql);

	if( $consulta === false)  
	{  
		// echo "Error en consulta.\n";  
		 $jsondata = array('success' => false);
		 //die( print_r( sqlsrv_errors(), true));  
	}
	else
	{	
		$_SESSION['INGRESO']['Cambio']=1;
		$jsondata = array('success' => true, 'name'=>$_POST['campo1']);
	}
	$cid->close();
	 //Aunque el content-type no sea un problema en la mayoría de casos, es recomendable especificarlo
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata);
    exit();

	
}
//Buscar empresa
function buscarEntidad(){
	 
		$cid = Conectar::conexion('MYSQL');
		//$_POST['TP']='CD';
		//$_POST['MesNo']=0;
		$sql = "SELECT *
				  FROM lista_empresas
				  WHERE ID_Empresa = '".$_POST['com']."' AND Ciudad='".$_POST['ciu']."' ORDER BY Empresa;";
		//echo $sql;
		//die();
		$consulta=$cid->query($sql) or die($cid->error);
		//Realizamos un bucle para ir obteniendo los resultados
		$i=0;
		?>
			<div class="col-md-6">
				<div class="form-group">
					<label for="Empresa">Empresa</label>
					<select class="form-control" name="empresa" id='empresa' onChange="return buscar('empresa');">
						<option value='0'>Seleccione Empresa</option>
			<?php
			while($filas=$consulta->fetch_assoc()){
				?>
					<option value='<?php echo $filas['ID']; ?>'><?php echo $filas['Empresa']; ?></option>
				<?php
				//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';
				$i++;
			}
			?>
					</select>
				</div>
			</div>
		<?php
		if($i==0)
		{
			echo '<div id="alerta" class="alert alert-warning visible">Empresa no encontrada</div>';
		}
		
	 $cid->close();
}
//Buscar empresa
function buscarEmpresa(){
	 
		$cid = Conectar::conexion('MYSQL');
		//$_POST['TP']='CD';
		//$_POST['MesNo']=0;
		$sql = "SELECT *
				  FROM lista_empresas
				  WHERE ID = '".$_POST['com']."';";
		//echo $sql;
		//die();
		 if ($resultado = $cid->query($sql)) {

			/* Obtener la información del campo para todas las columnas */
			$info_campo = $resultado->fetch_fields();
			$i=0;
			foreach ($info_campo as $valor) {
				if($i==15)
				{
					$contra=$valor->name;
				}
				$i++;
			}
			$resultado->free();
		}
		$sql = "SELECT *
				  FROM lista_empresas
				  WHERE ID = '".$_POST['com']."';";
		$consulta=$cid->query($sql) or die($cid->error);
		//Realizamos un bucle para ir obteniendo los resultados
		$i=0;
		/*$info_campo = $consulta->fetch_fields();

				foreach ($info_campo as $valor) {
					printf("Nombre:           %s\n",   $valor->name);
					printf("Tabla:            %s\n",   $valor->table);
					printf("Longitud máx.:    %d\n",   $valor->max_length);
					printf("Longitud:         %d\n",   $valor->length);
					printf("Nº conj. caract.: %d\n",   $valor->charsetnr);
					printf("Banderas:         %d\n",   $valor->flags);
					printf("Tipo:             %d\n\n", $valor->type);
				}
		$consulta=$cid->query($sql) or die($cid->error);*/
		while($filas=$consulta->fetch_assoc()){
			//while($filas=$consulta->fetch_array()){
			?>
				<div class="col-md-4">
					<div class="form-group">
					    <label for="Estado">Estado</label>
					    <select class="form-control" name="Estado" id='Estado' >
							<option value='<?php echo $filas['Estado']; ?>'><?php echo $filas['Estado']; ?></option>
						    <option value='0'>Seleccione Estado</option>
							<?php select_option_mysql_a('lista_estados','Estado','Estado,Descripcion',''); ?>
					    </select>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="FechaR">Fecha Renovación(dia-mes-año)</label>
					   
					  <input type="date" class="form-control" id="FechaR" placeholder="FechaR" 
					  value='<?php echo date('Y-m-d',strtotime($filas['Fecha'])) ?>' 
					  onKeyPress="return soloNumeros(event)"  maxlength="10" >
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="Fecha">Fecha Comp. Electronico(dia-mes-año)</label>
					   
					  <input type="date" class="form-control" id="Fecha" placeholder="Fecha" 
					  value='<?php echo date('Y-m-d',strtotime($filas['Fecha_CE'])) ?>' onKeyPress="return soloNumeros(event)" 
					  maxlength="10" >
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="Fecha">Fecha VPN(dia-mes-año)</label>
					   
					  <input type="date" class="form-control" id="FechaV" placeholder="FechaV" 
					  value='<?php echo date('Y-m-d',strtotime($filas['Fecha_VPN'])) ?>'   onKeyPress="return soloNumeros(event)" maxlength="10">
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="Servidor">Servidor</label>
					  <input type="text" class="form-control" id="Servidor" placeholder="Servidor" value='<?php echo $filas['IP_VPN_RUTA']; ?>'>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="Base">Base</label>
					  <input type="text" class="form-control" id="Base" placeholder="Base" value='<?php echo $filas['Base_Datos']; ?>'>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="Usuario">Usuario</label>
					   
					  <input type="text" class="form-control" id="Usuario" placeholder="Usuario" value='<?php echo $filas['Usuario_DB']; ?>'>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="Clave">Clave</label>
					  <input type="text" class="form-control" id="Clave" placeholder="Clave" value='<?php echo $filas[$contra]; ?>'>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="Motor">Motor BD</label>
					  <input type="text" class="form-control" id="Motor" placeholder="Motor" value='<?php echo $filas['Tipo_Base']; ?>'>
					</div>
				</div>
				<div class="col-md-4">
					<div class="form-group">
					  <label for="Puerto">Puerto</label>
					   
					  <input type="text" class="form-control" id="Puerto" placeholder="Puerto" value='<?php echo $filas['Puerto']; ?>'>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
					  <label for="Mensaje">Mensaje</label>
					  <input type="text" class="form-control" id="Mensaje" placeholder="Mensaje" value='<?php echo $filas['Mensaje']; ?>'>
					</div>
				</div>
			<?php
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';
			$i++;
		}
		if($i==0)
		{
			echo '<div id="alerta" class="alert alert-warning visible">Empresa no encontrada</div>';
		}
		
	 $cid->close();
}

//funcion para buscar los comprobantes
function buscar_com()
{
	$cid=cone_ajaxSQL();
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	$sql="SELECT Numero ". 
       "FROM Comprobantes ".
       "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
       "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ".
       "AND TP = '".$_POST['TP']."' ";
	   if($_POST['MesNo']>0)
	   {
		   $sql=$sql." AND MONTH(Fecha) = ".$_POST['MesNo']." ";
	   }
    $sql=$sql." ORDER BY Numero ";
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$row_count=0;
	//hacemos select
	?>
	<select class="form-control" name="comp" id='comp' onChange="return buscar('comp');">
		<?php
		if(isset($_SESSION['FILTRO']['cam1']))
		{
		?>
			<option value='<?php echo $_SESSION['FILTRO']['cam1'];?>' selected><?php echo $_SESSION['FILTRO']['cam1'];?></option>
		<?php
		}
		?>
		<?php
		$i=0;
		?>
			<option value='' selected><?php echo "Seleccionar";?></option>
		<?php
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			$i++;
			?>
				<option value='<?php echo $row[0];?>' ><?php echo $row[0];?></option>
			<?php
		}
		if($i==0)
		{
			?>
				<option value='' selected><?php echo "No existen datos";?></option>
			<?php
		}
		
		?>
	</select>
	<?php

	cerrarSQLSERVERFUN($cid);
}
//funcion para buscar los comprobantes
function reporte_com($com=null)
{
	$cid=cone_ajaxSQL();
	if($com!=null)
	{
		$_POST['com']=$com;
	}
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	//Listar el Comprobante
	$sql="SELECT  Periodo, T, TP, Numero, Fecha, Codigo_B, Presupuesto, Concepto, Cotizacion, Efectivo, Monto_Total,".
    " CodigoU, Autorizado, Item, Si_Existe, Hora, CEj, X, ID ". 
       "FROM Comprobantes ".
       "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
       "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ".
       "AND Numero = '".$_POST['com']."' ";
    $sql=$sql." ORDER BY Numero ";
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta Listar.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$i=0;
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		$i++;
		//Listar el Comprobante
		$sql="SELECT  Periodo, T, TP, Numero, Fecha, Codigo_B, Presupuesto, Concepto, Cotizacion, Efectivo, Monto_Total,".
		" CodigoU, Autorizado, Item, Si_Existe, Hora, CEj, X, ID ". 
		   "FROM Comprobantes ".
		   "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
		   "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ".
		   "AND Numero = '".$_POST['com']."' ";
		$sql=$sql." ORDER BY Numero ";
		//echo $sql;
		//die();
		$stmt7 = sqlsrv_query( $cid, $sql);
		if( $stmt7 === false)  
		{  
			 echo "Error en consulta Listar.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$sql="";
		//para ver cheuqes en tipo comprobante CE Y CI
		$sql=" select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Haber) as monto
			 from Transacciones as t, Catalogo_Cuentas as cc
			 where t.Item='".$_SESSION['INGRESO']['item']."' and t.Periodo='".$_SESSION['INGRESO']['periodo']."' 
			 and t.TP='CE' and t.Numero='".$_POST['com']."'
			 and cc.TC IN ('BA','CJ')
			 and SUBSTRING(t.Cta,1,1)='1'
			 and t.Haber>0
			 and t.Item=cc.Item
			 and t.Periodo=cc.Periodo
			 and t.Cta=cc.Codigo
			 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
			 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep";
		$stmt8 = sqlsrv_query( $cid, $sql);
		if( $stmt8 === false)  
		{  
			 echo "Error en consulta Listar.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$stmt8_count = contar_reg($stmt8);
			//vovlemos a generar consulta
		$stmt8 = sqlsrv_query( $cid, $sql);
		
		$sql=" select cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep,sum(t.Debe) as monto
			 from Transacciones as t, Catalogo_Cuentas as cc
			 where t.Item='".$_SESSION['INGRESO']['item']."' and t.Periodo='".$_SESSION['INGRESO']['periodo']."' 
			 and t.TP='CI' and t.Numero='".$_POST['com']."'
			 and cc.TC IN ('BA','CJ')
			 and SUBSTRING(t.Cta,1,1)='1'
			 and t.Debe>0
			 and t.Item=cc.Item
			 and t.Periodo=cc.Periodo
			 and t.Cta=cc.Codigo
			 group by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep
			 order by cc.TC,t.Cta,cc.Cuenta,t.Cheq_Dep";
		$stmt9 = sqlsrv_query( $cid, $sql);
		if( $stmt9 === false)  
		{  
			 echo "Error en consulta Listar.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$stmt9_count = contar_reg($stmt9);
			//vovlemos a generar consulta
		$stmt9 = sqlsrv_query( $cid, $sql);
		//manda a realizar el comprobante
		//cabecera
		$TipoComp = $row[2];
		$Numero =$row[3];
		// Periodo, Item, T, TP, Numero, Fecha, Codigo_B, Presupuesto, Concepto, Cotizacion, Efectivo, Monto_Total, CodigoU, Autorizado, Si_Existe, Hora, CEj, X, ID
		/*$sql="SELECT C.Periodo, C.Item, C.T, C.TP, A.Nombre_Completo ,Cl.CI_RUC,Cl.Direccion,Cl.Email,Cl.Telefono,Cl.Celular,Cl.Cliente,Cl.Ciudad ";
        $sql=$sql."FROM Comprobantes C, Accesos A, Clientes Cl WHERE C.Numero ='".$row[3]."' AND C.TP = '".$row[2]."' ";
        $sql=$sql."AND C.Item = '".$_SESSION['INGRESO']['item']."' AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
        $sql=$sql."AND C.CodigoU = A.Codigo AND C.Codigo_B = Cl.Codigo ";*/
		$sql="select  a.Periodo, a.Item, a.T, a.TP, a.Numero, a.Fecha, a.Codigo_B, a.Presupuesto, a.Concepto, 
		a.Cotizacion, a.Efectivo, a.Monto_Total, a.CodigoU, a.Autorizado, a.Si_Existe, a.Hora, a.CEj, a.X, a.ID,
		a.Efectivo, a.Nombre_Completo ,a.CI_RUC,a.Direccion,a.Email,a.Telefono,a.Celular,
		a.Cliente,a.Ciudad from (
		SELECT C.Periodo, C.Item, C.T, C.TP, C.Numero, C.Fecha, C.Codigo_B, C.Presupuesto, C.Concepto, 
		C.Cotizacion, C.Efectivo, C.Monto_Total, C.CodigoU, C.Autorizado, C.Si_Existe, C.Hora, C.CEj, C.X, C.ID,
		A.Nombre_Completo ,Cl.CI_RUC,Cl.Direccion,Cl.Email,Cl.Telefono,Cl.Celular,
		Cl.Cliente,Cl.Ciudad FROM Comprobantes C, Accesos A, Clientes Cl 
		WHERE C.Numero ='".$row[3]."' AND C.TP = '".$row[2]."' 
		AND C.Item = '".$_SESSION['INGRESO']['item']."' AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
		AND C.CodigoU = A.Codigo AND C.Codigo_B = Cl.Codigo  ) a ";
		
		/*$sql="SELECT Comprobantes.T,Comprobantes.Fecha,Comprobantes.Codigo_B,Comprobantes.Concepto,Comprobantes.Efectivo,Accesos.Nombre_Completo,Clientes.CI_RUC,Clientes.Direccion,Clientes.Email,Clientes.Telefono,
			Clientes.Celular,Clientes.Cliente,Clientes.Ciudad FROM 
			Comprobantes 
			INNER JOIN Accesos  ON (Comprobantes.CodigoU = Accesos.Codigo)
			INNER JOIN Clientes ON (Comprobantes.Codigo_B = Clientes.Codigo) WHERE Comprobantes.Numero ='".$row[3]."' AND Comprobantes.TP = '".$row[2]."' ";
        $sql=$sql."AND Comprobantes.Item = '".$_SESSION['INGRESO']['item']."' AND Comprobantes.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";*/
        $sql=$sql." ";
		//echo $sql;
		$stmt1 = sqlsrv_query( $cid, $sql);
		if( $stmt1 === false)  
		{  
			 echo "Error en consulta cabecera.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		$i=0;
		$concepto = 'Ninguno';
		foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
			foreach( $fieldMetadata as $name => $value) {
				// echo "$name: $value<br />";
			}
		}
		/*
		Name: Periodo<br />Type: -9<br />Size: 10<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: T<br />Type: -9<br />Size: 1<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: TP<br />Type: -9<br />Size: 3<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: Numero<br />Type: 4<br />Size: <br />Precision: 10<br />Scale: <br />Nullable: 1<br />
		Name: Fecha<br />Type: 93<br />Size: <br />Precision: 23<br />Scale: 3<br />Nullable: 1<br />
		Name: Codigo_B<br />Type: -9<br />Size: 10<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: Presupuesto<br />Type: 3<br />Size: <br />Precision: 19<br />Scale: 4<br />Nullable: 1<br />
		Name: Concepto<br />Type: -9<br />Size: 160<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: Cotizacion<br />Type: 7<br />Size: <br />Precision: 24<br />Scale: <br />Nullable: 1<br />
		Name: Efectivo<br />Type: 3<br />Size: <br />Precision: 19<br />Scale: 4<br />Nullable: 1<br />
		Name: Monto_Total<br />Type: 3<br />Size: <br />Precision: 19<br />Scale: 4<br />Nullable: 1<br />
		Name: CodigoU<br />Type: -9<br />Size: 10<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: Autorizado<br />Type: -9<br />Size: 10<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: Item<br />Type: -9<br />Size: 3<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: Si_Existe<br />Type: -7<br />Size: <br />Precision: 1<br />Scale: <br />Nullable: 0<br />
		Name: Hora<br />Type: -9<br />Size: 8<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: CEj<br />Type: -9<br />Size: 1<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: X<br />Type: -9<br />Size: 1<br />Precision: <br />Scale: <br />Nullable: 1<br />
		Name: ID<br />Type: 4<br />Size: <br />Precision: 10<br />Scale: <br />Nullable: 0<br /><br />

		*/
		/*while( $row1 = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) 
		{
			$i++;
			$t = $row1['T'];
			$Fecha = $row1['Fecha']->format('Y-m-d');
			$codigoB = $row1['Codigo_B'];
			$beneficiario = $row1['Cliente'];
			$concepto = $row1['Concepto'];
			$efectivo = number_format($row1['Efectivo'],2, ',', '.');
			//$num = NumerosEnLetras::convertir(1988208.99);
			//echo $num;
			//die();
			$est="Normal";
			if($t == 'A')
			{
				$est="Anulado";
			}
			$usuario= $row1['Nombre_Completo'];
			//echo $t.' '.$Fecha.' '.$codigoB.' '.$beneficiario.' '.$concepto.' '.$efectivo.' '.$est.' '.$usuario;
		}*/
		while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
		{
			$i++;
			$t = $row1[2];
			$Fecha = $row1[5]->format('Y-m-d');
			$codigoB = $row1[6];
			$beneficiario = $row1[25];
			$concepto = $row1[8];
			$efectivo = number_format($row1[10],2, ',', '.');
			//$num = NumerosEnLetras::convertir(1988208.99);
			//echo $num;
			//die();
			$est="Normal";
			if($t == 'A')
			{
				$est="Anulado";
			}
			$usuario= $row1[19];
			//echo $t.' '.$Fecha.' '.$codigoB.' '.$beneficiario.' '.$concepto.' '.$efectivo.' '.$est.' '.$usuario;
		}
		if($i!=0)
		{
			//manda a realizar el comprobante
			//cabecera
			/*$sql="SELECT C.*,A.Nombre_Completo,Cl.CI_RUC,Cl.Direccion,Cl.Email,Cl.Telefono,Cl.Celular,Cl.Cliente,Cl.Ciudad ";
			$sql=$sql."FROM Comprobantes As C, Accesos As A, Clientes As Cl WHERE C.Numero ='".$row[3]."' AND C.TP = '".$row[2]."' ";
			$sql=$sql."AND C.Item = '".$_SESSION['INGRESO']['item']."' AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND C.CodigoU = A.Codigo AND C.Codigo_B = Cl.Codigo ";*/
			$sql="select  a.Periodo, a.Item, a.T, a.TP, a.Numero, a.Fecha, a.Codigo_B, a.Presupuesto, a.Concepto, 
			a.Cotizacion, a.Efectivo, a.Monto_Total, a.CodigoU, a.Autorizado, a.Si_Existe, a.Hora, a.CEj, a.X, a.ID,
			a.Efectivo, a.Nombre_Completo ,a.CI_RUC,a.Direccion,a.Email,a.Telefono,a.Celular,
			a.Cliente,a.Ciudad from (
			SELECT C.Periodo, C.Item, C.T, C.TP, C.Numero, C.Fecha, C.Codigo_B, C.Presupuesto, C.Concepto, 
			C.Cotizacion, C.Efectivo, C.Monto_Total, C.CodigoU, C.Autorizado, C.Si_Existe, C.Hora, C.CEj, C.X, C.ID,
			A.Nombre_Completo ,Cl.CI_RUC,Cl.Direccion,Cl.Email,Cl.Telefono,Cl.Celular,
			Cl.Cliente,Cl.Ciudad FROM Comprobantes C, Accesos A, Clientes Cl 
			WHERE C.Numero ='".$row[3]."' AND C.TP = '".$row[2]."' 
			AND C.Item = '".$_SESSION['INGRESO']['item']."' AND C.Periodo = '".$_SESSION['INGRESO']['periodo']."' 
			AND C.CodigoU = A.Codigo AND C.Codigo_B = Cl.Codigo  ) a ";
			//echo $sql;
			//die();
			$stmt1 = sqlsrv_query( $cid, $sql);
			if( $stmt1 === false)  
			{  
				 echo "Error en consulta cabecera.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//existe comprobante
			//Listar las Transacciones
			$sql="SELECT T.Cta,Ca.Cuenta,T.Parcial_ME,T.Debe,T.Haber,T.Detalle,T.Cheq_Dep,T.Fecha_Efec,T.Codigo_C,Ca.Item,T.TP,T.Numero,T.Fecha,T.T ";
            $sql=$sql."FROM Transacciones As T, Catalogo_Cuentas As Ca ";
			$sql=$sql."WHERE T.TP = '".$row[2]."' ";
			$sql=$sql."AND T.Numero = ".$row[3]." ";
			$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND T.Item = Ca.Item ";
			$sql=$sql."AND T.Periodo = Ca.Periodo ";
			$sql=$sql."AND T.Cta = Ca.Codigo ";
			$sql=$sql."ORDER BY T.ID,Debe DESC,T.Cta ";
			//echo $sql;
			$stmt2 = sqlsrv_query( $cid, $sql);
			if( $stmt2 === false)  
			{  
				 echo "Error en consulta Transacciones.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$stmt2_count = contar_reg($stmt2,5);
			//vovlemos a generar consulta
			$stmt2 = sqlsrv_query( $cid, $sql);
			//echo ' fdfdfd '.$stmt2_count;
			//Llenar Bancos
			$sql="SELECT T.Cta,C.TC,C.Cuenta,Co.Fecha,Cl.Cliente,T.Cheq_Dep,T.Debe,T.Haber,T.Fecha_Efec ";
			$sql=$sql."FROM Transacciones As T,Comprobantes As Co,Catalogo_Cuentas As C,Clientes As Cl ";
			$sql=$sql."WHERE T.TP = '".$row[2]."' ";
			$sql=$sql."AND T.Numero = ".$row[3]." ";
			$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND T.Numero = Co.Numero ";
			$sql=$sql."AND T.TP = Co.TP ";
			$sql=$sql."AND T.Cta = C.Codigo ";
			$sql=$sql."AND T.Item = C.Item ";
			$sql=$sql."AND T.Item = Co.Item ";
			$sql=$sql."AND T.Periodo = C.Periodo ";
			$sql=$sql."AND T.Periodo = Co.Periodo ";
			$sql=$sql."AND C.TC = 'BA' ";
			$sql=$sql."AND Co.Codigo_B = Cl.Codigo ";
			//echo $sql.'<br>';
			$stmt3 = sqlsrv_query( $cid, $sql);
			if( $stmt3 === false)  
			{  
				 echo "Error en consulta Bancos.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$stmt3_count = contar_reg($stmt3);
			//echo " ffff ".$stmt3_count;
			//vovlemos a generar consulta
			$stmt3 = sqlsrv_query( $cid, $sql);
			//Listar las Retenciones del IVA
			$sql="SELECT * ";
			$sql=$sql."FROM Trans_Compras ";
			$sql=$sql."WHERE Numero = ".$row[3]." ";
			$sql=$sql."AND TP = '".$row[2]."' ";
			$sql=$sql."AND Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."ORDER BY Cta_Servicio,Cta_Bienes ";
			//echo $sql.'<br>';
			$stmt4 = sqlsrv_query( $cid, $sql);
			if( $stmt4 === false)  
			{  
				 echo "Error en consulta Retenciones.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$stmt4_count = contar_reg($stmt4);
			//echo " ffff ".$stmt4_count;
			//vovlemos a generar consulta
			$stmt4 = sqlsrv_query( $cid, $sql);
			//Listar las Retenciones de la Fuente
			$sql="SELECT R.*,TIV.Concepto ";
			$sql=$sql."FROM Trans_Air As R,Tipo_Concepto_Retencion As TIV ";
			$sql=$sql."WHERE R.Numero = ".$row[3]." ";
			$sql=$sql."AND R.TP = '".$row[2]."' ";
			$sql=$sql."AND R.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND TIV.Fecha_Inicio <= '".$Fecha."' ";
			$sql=$sql."AND TIV.Fecha_Final >= '".$Fecha."' ";
			$sql=$sql."AND R.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND R.Tipo_Trans IN ('C','I') ";
			$sql=$sql."AND R.CodRet = TIV.Codigo ";
			$sql=$sql."ORDER BY R.Cta_Retencion ";
			//echo $sql.'<br>';
			$stmt5 = sqlsrv_query( $cid, $sql);
			if( $stmt5 === false)  
			{  
				 echo "Error en consulta Retenciones 1.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$stmt5_count = contar_reg($stmt5);
			//echo " ffff ".$stmt5_count;
			//vovlemos a generar consulta
			$stmt5 = sqlsrv_query( $cid, $sql);
			//Llenar SubCtas
			$sql="SELECT T.Cta,T.TC,T.Factura,C.Cliente,T.Detalle_SubCta,T.Debitos,T.Creditos,T.Fecha_V,T.Codigo,T.Prima ";
			$sql=$sql."FROM Trans_SubCtas As T,Clientes As C ";
			$sql=$sql."WHERE T.TP = '".$row[2]."' ";
			$sql=$sql."AND T.Numero = ".$row[3]." ";
			$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";	
			$sql=$sql."AND T.TC IN ('C','P') ";
			$sql=$sql."AND T.Codigo = C.Codigo ";
			$sql=$sql."UNION ";
			$sql=$sql."SELECT T.Cta,T.TC,T.Factura,C.Detalle As Cliente,T.Detalle_SubCta,T.Debitos,T.Creditos,T.Fecha_V,T.Codigo,T.Prima ";
			$sql=$sql."FROM Trans_SubCtas As T,Catalogo_SubCtas As C ";
			$sql=$sql."WHERE T.TP = '".$row[2]."' ";
			$sql=$sql."AND T.Numero = ".$row[3]." ";
			$sql=$sql."AND T.Item = '".$_SESSION['INGRESO']['item']."' ";
			$sql=$sql."AND T.Periodo = '".$_SESSION['INGRESO']['periodo']."' ";
			$sql=$sql."AND T.TC = C.TC ";
			$sql=$sql."AND T.Item = C.Item ";
			$sql=$sql."AND T.Periodo = C.Periodo ";
			$sql=$sql."AND T.Codigo = C.Codigo ";
			$sql=$sql."ORDER BY T.Cta,T.Codigo,T.Fecha_V,T.Factura ";
			//echo $sql.'<br>';
			//die();
			$stmt6 = sqlsrv_query( $cid, $sql);
			if( $stmt6 === false)  
			{  
				 echo "Error en consulta SubCtas.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			$stmt6_count = contar_reg($stmt6);
			//echo " ffff ".$stmt6_count;
			//vovlemos a generar consulta
			$stmt6 = sqlsrv_query( $cid, $sql);
			//llamamos a los pdf
			if($TipoComp=='CD')
			{
				/*header('Content-type: application/pdf');
				header('Content-Disposition: attachment; filename="myPDF.pdf');

				// Send Headers: Prevent Caching of File
				header('Cache-Control: private');
				header('Pragma: private');
				header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');*/
				imprimirCD($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
			}
			if($TipoComp=='CI')
			{
				imprimirCI($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $stmt9, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,
				$stmt5_count,$stmt6_count,$stmt9_count);	
			}
			if($TipoComp=='CE')
			{
				imprimirCE($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $stmt8, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,
				$stmt5_count,$stmt6_count,$stmt8_count);
			}
			if($TipoComp=='ND')
			{
				imprimirND($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
			}
			if($TipoComp=='NC')
			{
				imprimirNC($stmt7, $stmt2, $stmt4, $stmt5, $stmt6, $stmt1, $Numero,null,null,null,0,$stmt2_count,$stmt4_count,$stmt5_count,$stmt6_count);
			}
			/*Select Case Co.TP
			  Case CompIngreso: ImprimirCompIngreso AdoComp, AdoBanc, AdoTrans, AdoSubC
			  Case CompEgreso: ImprimirCompEgreso AdoComp, AdoBanc, AdoTrans, AdoFact, AdoRet, AdoSubC, ImpSoloReten
			  Case CompDiario: ImprimirCompDiario AdoComp, AdoTrans, AdoFact, AdoRet, AdoSubC, ImpSoloReten
			  Case CompNotaDebito: ImprimirCompNota_D_C AdoComp, AdoTrans, AdoSubC, "ND"
			  Case CompNotaCredito: ImprimirCompNota_D_C AdoComp, AdoTrans, AdoSubC, "NC"
			End Select*/
		}
		else
		{
			echo "El Comprobante no exite.";
		}
		?>
			<!--<iframe style="width:100%; height:50vw;" src="ajax/TEMP/<?php echo $Numero;?>.pdf" frameborder="0" allowfullscreen>
			</iframe>-->
		<?php
	}
	if($i==0)
	{
		echo "No existen datos";
	}
	cerrarSQLSERVERFUN($cid);
}
//contar registros
function contar_reg($stmt,$detalle=null)
{
	$i=0;
	$concepto = 'Ninguno';
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		if($detalle!=null)
		{
			//varificamos que campo sea distinto de .
			if($row[$detalle]!='.')
			{
				$i++;
			}
		}
		$i++;
	}
	return $i;
}
//validar usuario en login
function validarUser(){
	 
	//$cid=cone_ajaxMYSQL();
	$cid = Conectar::conexion('MYSQL');
	if($cid!=null)
	{	
		/* if ($cid>connect_errno) 
		 {
			 echo '<div id="alerta" class="alert alert-warning visible">Entidad no encontrada</div>';
			 die();
		 }*/
		//$_POST['TP']='CD';
		$i=0;
		if(isset($_SESSION['INGRESO']['ID_Empresa']))
		{
			//$_POST['MesNo']=0;
			$sql="SELECT * FROM acceso_usuarios 
			WHERE Usuario='".$_POST['user']."' AND ID_Empresa='".$_SESSION['INGRESO']['ID_Empresa']."'
			";
			//echo $sql;
			//die();
			$consulta=$cid->query($sql) or die($cid->error);
			//Realizamos un bucle para ir obteniendo los resultados
			
			while($filas=$consulta->fetch_assoc())
			{
				echo '<div id="alerta" class="alert alert-success visible" align="center">ENTIDAD: '.$_SESSION['INGRESO']['Nombre_Entidad'].'</div>';
				$i++;
			}
		}
		if($i==0)
		{
			//echo '<div id="alerta" class="alert alert-warning visible" align="center">Entidad no encontrada</div>';
			if(isset($_SESSION['INGRESO']['Nombre_Entidad']))
			{
				echo "<script> 
					document.getElementById('Correo').focus();
					Swal.fire({
					  type: 'error',
					  title: 'este usuario no pertenece a: ".$_SESSION['INGRESO']['Nombre_Entidad']."',
					  text: 'No se pudo realizar sesion(4)'
					});
					
					</script>";
			}
			else
			{
				echo "<script> 
					document.getElementById('Correo').focus();
					Swal.fire({
					  type: 'error',
					  title: 'este usuario no pertenece a esta entidad',
					  text: 'No se pudo realizar sesion(4)'
					});
					
					</script>";
			}
		}
		$cid->close();
	}
	else
	{
		 echo "<script> Swal.fire({
			  type: 'error',
			  title: 'No se pudo realizar sesion, verifique conexion (2)',
			  text: 'Error de conexion.'
			});
			</script>";
	}
	
}
//verificar ciudad
function validarCiu()
{
	$cid = Conectar::conexion('MYSQL');
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	$sql="SELECT Ciudad
			  FROM lista_empresas
			  WHERE ID_Empresa = '".$_POST['com']."' group by Ciudad";
	//echo $sql;
	//die();
	$consulta=$cid->query($sql) or die($cid->error);
	//Realizamos un bucle para ir obteniendo los resultados
	$i=0;
	?>
		<div class="col-md-6">
			<div class="form-group">
				<label for="Ciudad">Ciudad</label>
				<select class="form-control" name="ciudad" id='ciudad' onChange="return buscar('entidad');">
					<option value='0'>Seleccione Ciudad</option>
		<?php
		while($filas=$consulta->fetch_assoc()){
			?>
				<option value='<?php echo $filas['Ciudad']; ?>'><?php echo $filas['Ciudad']; ?></option>
			<?php
			//echo '<div id="alerta" class="alert alert-success visible">'.$filas['Empresa'].'</div>';
			$i++;
		}
		?>
				</select>
			</div>
		</div>
	<?php
	if($i==0)
	{
		echo '<div id="alerta" class="alert alert-warning visible">Ciudad no encontrada</div>';
	}
		
	 $cid->close();
}
//verificar entidad
function validarEnt(){
	 $retornar=false;
	 $entidadfilter =preg_match("/^[0-9]{13}$/", $_POST['com']);
	 $entidadfilter1 =preg_match("/^[0-9]{10}$/", $_POST['com']);
	if ($entidadfilter or $entidadfilter1)
	{
		 //$cid=cone_ajaxMYSQL();
		 $cid = Conectar::conexion('MYSQL');
		if($cid!=null)
		{	
			/* if ($cid>connect_errno) 
			 {
				 echo '<div id="alerta" class="alert alert-warning visible">Entidad no encontrada</div>';
				 die();
			 }*/
			//$_POST['TP']='CD';
			//$_POST['MesNo']=0;
			$sql = "SELECT *
					  FROM entidad
					  WHERE RUC_CI_NIC = '".$_POST['com']."';";
			//echo $sql;
			//die();
			$consulta=$cid->query($sql) or die($cid->error);
			//Realizamos un bucle para ir obteniendo los resultados
			$i=0;
			while($filas=$consulta->fetch_assoc())
			{
				$_SESSION['INGRESO']['Nombre_Entidad']=$filas['Nombre_Entidad'];
				$_SESSION['INGRESO']['ID_Empresa']=$filas['ID_Empresa'];
				if(isset($_SESSION['TIPOCON']))
				{
					if($_SESSION['TIPOCON']==3)
					{
						echo '<div id="alerta" class="alert alert-success visible" align="center">"'.$_SESSION['INGRESO']['Nombre_Entidad'].'"</div>';
					}
					if($_SESSION['TIPOCON']==0)
					{
						echo '<div id="alerta" class="alert alert-success visible" align="center">'.$_SESSION['INGRESO']['Nombre_Entidad'].'</div>';
					}
				}
				
				$i++;
			}
			if($i==0)
			{
				//echo '<div id="alerta" class="alert alert-warning visible" align="center">Entidad no encontrada</div>';
				echo "<script> Swal.fire({
					  type: 'error',
					  title: 'No se pudo realizar sesion(1)',
					  text: 'Entidad no encontrada'
					});
					</script>";
			}
			$cid->close();
		}
		else
		{
			 echo "<script> Swal.fire({
				  type: 'error',
				  title: 'No se pudo realizar sesion, verifique conexion (2)',
				  text: 'Error de conexion.'
				});
				</script>";
		}
	 }
	 else
	 {
		 
	  // echo '<div id="alerta" class="alert alert-danger visible" align="center">La entidad que ingresaste no tiene el formato correcto.</div>';
	    echo "<script> Swal.fire({
				  type: 'error',
				  title: 'No se pudo realizar sesion',
				  text: 'La entidad que ingresaste no tiene el formato correcto.(3)'
				});
				</script>";
	 }
}

//cambiar periodo
function cambiarPeriodo()
{
	$_SESSION['INGRESO']['periodo']=$_POST['campo'];
	$cid=cone_ajaxSQL();
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	//Listar el Comprobante
	/*$sql="SELECT  Periodo, T, TP, Numero, Fecha, Codigo_B, Presupuesto, Concepto, Cotizacion, Efectivo, Monto_Total,".
    " CodigoU, Autorizado, Item, Si_Existe, Hora, CEj, X, ID ". 
       "FROM Comprobantes ".
       "WHERE Item = '".$_SESSION['INGRESO']['item']."' ".
       "AND Periodo = '".$_SESSION['INGRESO']['periodo']."' ".
       "AND Numero = '".$_POST['com']."' ";
    $sql=$sql." ORDER BY Numero ";
	//echo $sql;
	//die();
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta Listar.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	$i=0;
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		
	}*/
	$cid->close();
}
//listar totales temporales sql
//$Opcb = tipo de asiento si es asiento o asiento_b por ejemplo
function ListarTotalesTemSQL_AJAX($ti,$Opcb,$b,$ch)
{
	//opciones para generar consultas (asientos bancos)
	$cid=cone_ajaxSQL();
	if($Opcb=='1')
	{
		/*$sql="SELECT CTA_BANCO, BANCO, CHEQ_DEP, EFECTIVIZAR, VALOR, ME, T_No, Item, CodigoU
		FROM Asiento_B
		WHERE 
		Item = '".$_SESSION['INGRESO']['item']."' 
		AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
		$ta='asi_b';*/
	}
	else
	{
		$sql="SELECT (SUM(DEBE)-SUM(HABER)) AS DIFERENCIA, SUM(DEBE) AS DEBE ,SUM(HABER) AS HABER 
			FROM Asiento
			WHERE 
				T_No=".$_SESSION['INGRESO']['modulo_']." AND
				Item = '".$_SESSION['INGRESO']['item']."' 
				AND CodigoU = '".$_SESSION['INGRESO']['Id']."' ";
		$ta='asi';
		//echo $sql;
	}
	$stmt = sqlsrv_query($cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta PA.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}
	else
	{
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
			?>
			<div class="row ">			
				<div class="col-md-4 col-sm-4 col-xs-4">
					
				</div>
				<div class="col-md-2 col-sm-2 col-xs-2">
					<div class="input-group">
					
						<div class="input-group-btn">
							<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>Diferencia:</b></button>
						
						</div>
						
						<input type="text" class="xs" id="diferencia" name='diferencia' 
						placeholder="0.00" value='<?php echo number_format($row[0],2, ',', '.'); ?>' style='width:100%;text-align:right; '>
						
					</div>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-2">
					<div class="input-group">
						<div class="input-group-btn">
							<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>Totales</b></button>
						
						</div>
						<input type="text" class="xs" id="totald" name='totald' 
						placeholder="0.00" value='<?php echo number_format($row[1],2, ',', '.'); ?>' maxlength='20' size='21' style='text-align:right;'>
						
					</div>
				</div>
				<div class="col-md-2 col-sm-2 col-xs-2">
					<div class="input-group">
						<input type="text" class="xs" id="totalh" name='totalh' placeholder="0.00" 
						value='<?php echo number_format($row[2],2, ',', '.'); ?>' maxlength='20' size='21' style='text-align:right;'>
					</div>
				</div>
				
			</div>
			<?php
		}
	}
}
//grilla generica para mostrar en caso de usar ajax con mysql
//$tabla caso donde sean necesaria varias grillas
function grilla_generica_mysql($stmt,$ti=null,$camne=null,$b=null,$ch=null,$tabla=null)
{
	$info_campo = $stmt->fetch_fields();
	$cant=0;
	//guardamos los campos
	$campo='';
	foreach ($info_campo as $valor) 
	{
		$cant++;
	}
	if($ch!=null)
	{
		$ch1 = explode(",", $ch);
		$cant++;
	}
	//si lleva o no border
	$bor='';
	if($b!=null and $b!='0')
	{
		$bor='table-bordered1';
		//style="border-top: 1px solid #bce8f1;"
	}
	//colocar cero a tabla en caso de no existir definida ninguna
	if($tabla==null OR $tabla=='0' OR $tabla=='')
	{
		$tabla=0;
	}
	?>
		<div class="box-body no-padding">
            <table class="table table-striped w-auto <?php echo $bor; ?>" >
				<?php
				if($ti!='' or $ti!=null)
				{
			?>
					<tr>
						<th colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
					</tr>
			<?php
				}
			?>
				<tr>
					<th colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
				</tr>
                <tr>
					<?php
					//cantidad campos
					$cant=0;
					//guardamos los campos
					$campo='';
					//tipo de campos
					$tipo_campo=array();
					//guardamos posicion de un campo ejemplo fecha
					$cam_fech=array();
					//contador para fechas
					$cont_fecha=0;
					//obtenemos los campos 
					//en caso de tener check
					if($ch!=null)
					{
						echo "<th style='text-align: left;'>SEL</th>";
					}
					foreach ($info_campo as $valor) 
					{
						//$camp='';
						$i=0;
						//tipo de campo
						/*
						tinyint_    1   boolean_    1   smallint_    2 int_        3
						float_      4   double_     5   real_        5 timestamp_    7
						bigint_     8   serial      8   mediumint_    9 date_        10
						time_       11  datetime_   12  year_        13 bit_        16
						decimal_    246 text_       252 tinytext_    252 mediumtext_    252
						longtext_   252 tinyblob_   252 mediumblob_    252 blob_        252
						longblob_   252 varchar_    253 varbinary_    253 char_        254
						binary_     254
						*/
						$ban=0;
						//texto
						if( $valor->type==7 OR $valor->type==8 OR $valor->type==10
						 OR $valor->type==11 OR $valor->type==12 OR $valor->type==13 OR $valor->type==16 
						 OR $valor->type==252 OR $valor->type==253 OR $valor->type==254 )
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//numero
						if($valor->type==3 OR $valor->type==2 OR $valor->type==4 OR $valor->type==5
						 OR $valor->type['Type']==8 OR $valor->type==9 OR $valor->type==246)
						{
							//number_format($item_i['nombre'],2, ',', '.')
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						if($ban==0)
						{
							echo ' no existe tipo '.$valor->type.' '.$valor->name.' '.$valor->table;
						}
						echo "<th ".$tipo_campo[$cant].">".$valor->name."</th>";
									$camp=$valor->name;
									$campo[$cant]=$camp;
									//echo ' dd '.$campo[$cant];
									$cant++;
					}
					?>
				</tr>
				<?php
				//echo $cant.' fffff ';
					//obtener la configuracion para celdas personalizadas
					//campos a evaluar
					$campoe=array();
					//valor a verificar
					$campov=array();
					//campo a afectar 
					$campoaf=array();
					//adicional
					$adicional=array();
					//signos para comparar
					$signo=array();
					//titulo de proceso
					$tit=array();
					//indice de registros a comparar con datos
					$ind=0;
					//obtener valor en caso de mas de una condicion
					$con_in=0;
					if($camne!=null)
					{
						for($i=0;$i<count($camne['TITULO']);$i++)
						{
							if($camne['TITULO'][$i]=='color_fila')
							{	
								$tit[$ind]=$camne['TITULO'][$i];
								//temporar para indice
								//$temi=$i;
								//buscamos campos a evaluar
								$camneva = explode(",", $camne['CAMPOE'][$i]);
								//si solo es un campo
								if(count($camneva)==1)
								{
									$camneva1 = explode("=", $camneva[0]);
									$campoe[$ind]=$camneva1[0];
									$campov[$ind]=$camneva1[1];
									//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
								}
								else
								{
									//hacer bucle
								}
								//para los campos a afectar
								if(count($camne['CAMPOA'])==1 AND $i==0)
								{
									if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
									{
										$campoaf[$ind]='TODOS';
									}
									else
									{
										//otras opciones
									}
								}
								else
								{
									//bucle
									if(!empty($camne['CAMPOA'][$i]))
									{
										if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
										{
											$campoaf[$ind]='TODOS';
										}
										else
										{
											//otras opciones
										}
									}
								}
								//valor adicional en este caso color
								if(count($camne['ADICIONAL'])==1 AND $i==0)
								{
									$adicional[$ind]=$camne['ADICIONAL'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['ADICIONAL'][$i]))
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
								}
								//signo de comparacion
								if(count($camne['SIGNO'])==1 AND $i==0)
								{
									$signo[$ind]=$camne['SIGNO'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['SIGNO'][$i]))
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
								}
								$ind++;
								//echo ' pp '.count($camneva);
							}
							//caso italica, subrayar, indentar
							if($camne['TITULO'][$i]=='italica' OR $camne['TITULO'][$i]=='subrayar' OR $camne['TITULO'][$i]=='indentar')
							{
								$tit[$ind]=$camne['TITULO'][$i];
									//buscamos campos a evaluar
								if(!is_array($camne['CAMPOE'][$i]))
								{
									$camneva = explode(",", $camne['CAMPOE'][$i]);
									//si solo es un campo
									if(count($camneva)==1)
									{
										$camneva1 = explode("=", $camneva[0]);
										$campoe[$ind]=$camneva1[0];
										$campov[$ind]=$camneva1[1];
										//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
									}
									else
									{
										//hacer bucle
									}
								}
								else
								{
									//es mas de un campo
									$con_in = count($camne['CAMPOE'][$i]);
									//recorremos registros
									for($j=0;$j<$con_in;$j++)
									{
										//echo $camne['CAMPOE'][$i][$j].' ';
										$camneva = explode(",", $camne['CAMPOE'][$i][$j]);
										//si solo es un campo
										if(count($camneva)==1)
										{
											$camneva1 = explode("=", $camneva[0]);
											$campoe[$ind][$j]=$camneva1[0];
											$campov[$ind][$j]=$camneva1[1];
											//echo ' pp '.$campoe[$ind][$j].' '.$campov[$ind][$j];
										}
									}
								}
								//para los campos a afectar
								if(!is_array($camne['CAMPOA'][$i]))
								{
									if(count($camne['CAMPOA'])==1 AND $i==0)
									{
										$campoaf[$ind]=$camne['CAMPOA'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['CAMPOA'][$i]))
										{
											//otras opciones
											$campoaf[$ind]=$camne['CAMPOA'][$i];
										}
									}
								}
								else
								{
									//recorremos el ciclo
									//es mas de un campo
									$con_in = count($camne['CAMPOA'][$i]);
									//recorremos registros
									for($j=0;$j<$con_in;$j++)
									{
										$campoaf[$ind][$j]=$camne['CAMPOA'][$i][$j];
										//echo ' pp '.$campoaf[$ind][$j];
									}
								}
								//valor adicional en este caso color
								
									if(count($camne['ADICIONAL'])==1 AND $i==0)
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['ADICIONAL'][$i]))
										{
											//es mas de un campo
											$con_in = count($camne['ADICIONAL'][$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$adicional[$ind][$j]=$camne['ADICIONAL'][$i][$j];
												//echo ' pp '.$adicional[$ind][$j];
											}
										}
									}
								
								
								//signo de comparacion
								if(!is_array($camne['SIGNO'][$i]))
								{
									if(count($camne['SIGNO'])==1 AND $i==0)
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['SIGNO'][$i]))
										{
											$signo[$ind]=$camne['SIGNO'][$i];
										}
									}
								}
								else
								{
									//es mas de un campo
									$con_in = count($camne['SIGNO'][$i]);
									for($j=0;$j<$con_in;$j++)
									{
										$signo[$ind][$j]=$camne['SIGNO'][$i][$j];
										//echo ' pp '.$signo[$ind][$j];
									}
								}
								$ind++;
							}
						}
					}
					$i=0;
					while ($row = $stmt->fetch_row()) 
					//while($row=$stmt->fetch_array())
					//while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
					{
						//para colocar identificador unicode_decode
						if($ch!=null)
						{
							if(count($ch1)==2)
							{
								$cch=$ch1[0];
								?>
									<tr <?php echo "id=ta_".$row[$cch]."";?> >
								<?php
							}
							else
							{
								//casos con mas id
								$cch='';
								$camch='';
								//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
								for($ca=0;$ca<count($ch1);$ca++)
								{
									if($ca<(count($ch1)-1))
									{
										$cch=$ch1[$ca];
										$camch=$camch.$row[$cch].'--';
									}
								}
								$ca=$ca-1;
								?>
									<tr <?php echo "id=ta_".$camch."";?> >
								<?php
							}
						}
						else
						{
							?>
							<tr >
							<?php
						}
						if($ch!=null)
						{
							if(count($ch1)==2)
							{
								$cch=$ch1[0];
								echo "<td style='text-align: left;'><input type='checkbox' id='id_".$row[$cch]."' name='".$ch1[1]."' value='".$row[$cch]."'
								onclick='validarc(\"id_".$row[$cch]."\",\"".$tabla."\")'></td>";
							}
							else
							{
								//casos con mas id
								$cch='';
								$camch='';
								//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
								for($ca=0;$ca<count($ch1);$ca++)
								{
									if($ca<(count($ch1)-1))
									{
										$cch=$ch1[$ca];
										$camch=$camch.$row[$cch].'--';
									}
								}
								$ca=$ca-1;
								echo "<td style='text-align: left;'><input type='checkbox' id='id_".$camch."' name='".$ch1[$ca]."' value='".$camch."'
								onclick='validarc(\"id_".$camch."\",\"".$tabla."\")'></td>";
								//die();
							}
						}
						//comparamos con los valores de los array para personalizar las celdas
						//para titulo color fila
						$cfila1='';
						$cfila2='';
						//indentar
						$inden='';
						$indencam=array();
						$indencam1=array();
						//contador para caso indentar
						$conin=0;
						//contador caso para saber si cumple varias condiciones ejemplo italica TC=P OR TC=C
						$ca_it=0;
						//variable para colocar italica
						$ita1='';
						$ita2='';
						//contador para caso italicas
						$conita=0;
						//valores de campo a afectar
						$itacam1=array();
						//variables para subrayar
						//valores de campo a afectar en caso subrayar
						$subcam1=array();
						//contador caso subrayar
						$consub=0;
						//contador caso para saber si cumple varias condiciones ejemplo subrayar TC=P OR TC=C
						$ca_sub=0;
						//variable para colocar subrayar
						$sub1='';
						$sub2='';
						for($i=0;$i<$ind;$i++)
						{
							if($tit[$i]=='color_fila')
							{
								if(!is_array($campoe[$i]))
								{
									//campo a comparar
									$tin=$campoe[$i];
									//comparamos valor
									if($signo[$i]=='=')
									{
										if($row[$tin]==$campov[$i])
										{
											if($adicional[$i]=='black')
											{
												//activa condicion
												$cfila1='<B>';
												$cfila2='</B>';
											}
										}
									}
								}
							}
							if($tit[$i]=='indentar')
							{	
								if(!is_array($campoe[$i]))
								{
									//campo a comparar
									$tin=$campoe[$i];
									//comparamos valor
									if($signo[$i]=='=')
									{
										if($campov[$i]=='contar')
										{
											$inden1 = explode(".", $row[$tin]);
											//echo ' '.count($inden1);
											//hacemos los espacios
											//$inden=str_repeat("&nbsp;&nbsp;", count($inden1));
											if(count($inden1)>1)
											{
												$indencam1[$conin]=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", (count($inden1)-1));
											}
											else
											{
												$indencam1[$conin]="";
											}
										}
										$indencam[$conin]=$campoaf[$i];
										//echo $indencam[$conin].' dd ';
										$conin++;
									}
								}
							}
							if($tit[$i]=='italica')
							{	
								if(!is_array($campoe[$i]))
								{
									
								}
								else
								{
									//es mas de un campo
									$con_in = count($campoe[$i]);
									$ca_it=0;
									for($j=0;$j<$con_in;$j++)
									{
										$tin=$campoe[$i][$j];
										//echo ' pp '.$tin[$i][$j];
										//comparamos valor
										if($signo[$i][$j]=='=')
										{
											//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
											if($row[$tin]==$campov[$i][$j])
											{
												$ca_it++;
											}
										}
										//si es diferente
										if($signo[$i][$j]=='<>')
										{
											//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
											if($row[$tin]<>$campov[$i][$j])
											{
												$ca_it++;
											}
										}
										
									}
									$con_in = count($campoaf[$i]);
									for($j=0;$j<$con_in;$j++)
									{
										$itacam1[$conita]=$campoaf[$i][$j];
										//echo $itacam1[$conita].' ';
										$conita++;
									}
									//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
									if($ca_it==count($campoe[$i]))
									{
										$ita1='<em>';
										$ita2='</em>';
									}
									else
									{
										$ita1='';
										$ita2='';
									}
								}
								
							}
							if($tit[$i]=='subrayar')
							{	
								if(!is_array($campoe[$i]))
								{
									
								}
								else
								{
									//es mas de un campo
									$con_in = count($campoe[$i]);
									$ca_sub=0;
									$ca_sub1=0;
									for($j=0;$j<$con_in;$j++)
									{
										$tin=$campoe[$i][$j];
										//echo ' pp '.$tin[$i][$j];
										//comparamos valor
										if($signo[$i][$j]=='=')
										{
											//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
											if($row[$tin]==$campov[$i][$j])
											{
												$ca_sub++;
												$ca_sub1++;
											}
										}
										//si es diferente
										if($signo[$i][$j]=='<>')
										{
											//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
											if($row[$tin]<>$campov[$i][$j])
											{
												$ca_sub++;
											}
										}
										
									}
									$con_in = count($campoaf[$i]);
									for($j=0;$j<$con_in;$j++)
									{
										$subcam1[$consub]=$campoaf[$i][$j];
										//echo $subcam1[$consub].' ';
										$consub++;
									}
									//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
									$sub1='';
									$sub2='';
									//condicion para verificar si signo es "=" o no
									if($ca_sub1==0)
									{
										//condicion en caso de distintos
										if($ca_sub==count($campoe[$i]))
										{
											$sub1='<u>';
											$sub2='</u>';
										}
										else
										{
											$sub1='';
											$sub2='';
										}
									}
									else
									{
										$sub1='<u>';
										$sub2='</u>';
									}
								}
							}
						}
						//para check box
					
						for($i=0;$i<$cant;$i++)
						{
							//caso indentar
							for($j=0;$j<count($indencam);$j++)
							{
								if($indencam[$j]==$i)
								{
									$inden=$indencam1[$j];
								}
								else
								{
									$inden='';
								}
							}
							//caso italica
							$ita3="";
							$ita4="";
							for($j=0;$j<count($itacam1);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($itacam1[$j]==$i)
								{
									$ita3=$ita1;
									$ita4=$ita2;
								}
								
							}
							//caso subrayado
							$sub3="";
							$sub4="";
							for($j=0;$j<count($subcam1);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($subcam1[$j]==$i)
								{
									$sub3=$sub1;
									$sub4=$sub2;
								}
								
							}
							//caso de campos fechas
							for($j=0;$j<count($cam_fech);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($cam_fech[$j]==$i)
								{
									//$row[$i]=$row[$i]->format('Y-m-d H:i:s');
									$row[$i]=$row[$i]->format('Y-m-d');
								}
								
							}
							//echo "<br/>";
							//formateamos texto si es decimal
							if($tipo_campo[$i]=="style='text-align: right;'")
							{
								//si es cero colocar -
								if(number_format($row[$i],2, ',', '.')==0 OR number_format($row[$i],2, ',', '.')=='0.00')
								{
									echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
								}
								else
								{
									//si es negativo colocar rojo
									if($row[$i]<0)
									{
										//reemplazo una parte de la cadena por otra
										$longitud_cad = strlen($tipo_campo[$i]); 
										$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
										echo "<td ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
									}
									else
									{
										echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
									}
								}
								
							}
							else
							{
								if(strlen($row[$i])<=50)
								{
									echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
								}
								else
								{
									$resultado = substr($row[$i], 0, 50);
									//echo $resultado; // imprime "ue"
									echo "<td ".$tipo_campo[$i]." data-toggle='tooltip' data-placement='left' title='".$row[$i]."'>".$cfila1.$ita3.$inden.$sub3."".$resultado."...".$sub4.$ita4.$cfila2."</td>";
								}
							}
						}
						/*$cam=$campo[$i];
						echo "<td>".$row['DG']."</td>";
						echo "<td>".$row['Codigo']."</td>";
						echo "<td>".$row['Cuenta']."</td>";
						echo "<td>".$row['Saldo_Anterior']."</td>";
						echo "<td>".$row['Debitos']."</td>";
						echo "<td>".$row['Creditos']."</td>";
						echo "<td>".$row['Saldo_Total']."</td>";
						echo "<td>".$row['TC']."</td>";*/
						 ?>
						  </tr>
						  <?php
						
						//$campo
						  //echo $row[$i].", <br />";
						  $i++;
						  if($cant==($i))
						  {
							  
							  //echo $cant.' ddddd '.$i;
							  $i=0;
							 
						  }
					}
				?>
			</table>
		</div>
		<?php
}	
//grilla generica para mostrar en caso de usar ajax
//$tabla caso donde sean necesaria varias grillas
function grilla_generica($stmt,$ti=null,$camne=null,$b=null,$ch=null,$tabla=null,$base=null)
{
	if($base==null or $base=='SQL SERVER')
	{
		//cantidad de campos
		$cant=0;
		//guardamos los campos
		$campo='';
		//obtenemos los campos 
		foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
			foreach( $fieldMetadata as $name => $value) {
				if(!is_numeric($value))
				{
					if($value!='')
					{
						$cant++;
					}
				}
			}
		}
		if($ch!=null)
		{
			$ch1 = explode(",", $ch);
			$cant++;
		}
		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//colocar cero a tabla en caso de no existir definida ninguna
		if($tabla==null OR $tabla=='0' OR $tabla=='')
		{
			$tabla=0;
		}
		?>
		<div class="box-body no-padding">
            <table class="table table-striped w-auto <?php echo $bor; ?>" >
				<?php
				if($ti!='' or $ti!=null)
				{
			?>
					<tr>
						<th colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
					</tr>
			<?php
				}
			?>
				<tr>
					<th colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
				</tr>
                <tr>
					<?php
					//cantidad campos
					$cant=0;
					//guardamos los campos
					$campo='';
					//tipo de campos
					$tipo_campo=array();
					//guardamos posicion de un campo ejemplo fecha
					$cam_fech=array();
					//contador para fechas
					$cont_fecha=0;
					//obtenemos los campos 
					//en caso de tener check
					if($ch!=null)
					{
						echo "<th style='text-align: left;'>SEL</th>";
					}
					foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
						//$camp='';
						$i=0;
						//tipo de campo
						$ban=0;
						//texto
						if($fieldMetadata['Type']==-9)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//numero
						if($fieldMetadata['Type']==3)
						{
							//number_format($item_i['nombre'],2, ',', '.')
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//echo $fieldMetadata['Type'].' ccc <br>';
						//echo $fieldMetadata['Name'].' ccc <br>';
						//caso fecha
						if($fieldMetadata['Type']==93)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
							$cam_fech[$cont_fecha]=$cant;
							//contador para fechas
							$cont_fecha++;
						}
						//caso bit
						if($fieldMetadata['Type']==-7)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//caso int
						if($fieldMetadata['Type']==4)
						{
							$tipo_campo[($cant)]=" style='text-align: right;'";
							$ban=1;
						}
						//caso tinyint
						if($fieldMetadata['Type']==-6)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso smallint
						if($fieldMetadata['Type']==5)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso real
						if($fieldMetadata['Type']==7)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//caso float
						if($fieldMetadata['Type']==6)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//uniqueidentifier
						if($fieldMetadata['Type']==-11)
						{
							$tipo_campo[($cant)]="style='text-align: right;'";
							$ban=1;
						}
						//ntext
						if($fieldMetadata['Type']==-10)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//rownum
						if($fieldMetadata['Type']==-5)
						{
							//echo " dddd ";
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						//ntext
						if($fieldMetadata['Type']==12)
						{
							$tipo_campo[($cant)]="style='text-align: left;'";
							$ban=1;
						}
						if($ban==0)
						{
							echo ' no existe tipo '.$value.' '.$fieldMetadata['Name'].' '.$fieldMetadata['Type'];
						}
						
						foreach( $fieldMetadata as $name => $value) {
							
							if(!is_numeric($value))
							{
								if($value!='')
								{
									echo "<th ".$tipo_campo[$cant].">".$value."</th>";
									$camp=$value;
									$campo[$cant]=$camp;
									//echo ' dd '.$campo[$cant];
									$cant++;
									//echo $value.' cc '.$cant.' ';
								}
							}
						   //echo "$name: $value<br />";
						}
						
						  //echo "<br />";
					}
					/*for($i=0;$i<$cant;$i++)
					{
						echo $i.' gfggf '.$tipo_campo[$i];
					}*/
					?>
				</tr>
				
                 
					<?php
					//echo $cant.' fffff ';
					//obtener la configuracion para celdas personalizadas
					//campos a evaluar
					$campoe=array();
					//valor a verificar
					$campov=array();
					//campo a afectar 
					$campoaf=array();
					//adicional
					$adicional=array();
					//signos para comparar
					$signo=array();
					//titulo de proceso
					$tit=array();
					//indice de registros a comparar con datos
					$ind=0;
					//obtener valor en caso de mas de una condicion
					$con_in=0;
					if($camne!=null)
					{
						for($i=0;$i<count($camne['TITULO']);$i++)
						{
							if($camne['TITULO'][$i]=='color_fila')
							{	
								$tit[$ind]=$camne['TITULO'][$i];
								//temporar para indice
								//$temi=$i;
								//buscamos campos a evaluar
								$camneva = explode(",", $camne['CAMPOE'][$i]);
								//si solo es un campo
								if(count($camneva)==1)
								{
									$camneva1 = explode("=", $camneva[0]);
									$campoe[$ind]=$camneva1[0];
									$campov[$ind]=$camneva1[1];
									//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
								}
								else
								{
									//hacer bucle
								}
								//para los campos a afectar
								if(count($camne['CAMPOA'])==1 AND $i==0)
								{
									if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
									{
										$campoaf[$ind]='TODOS';
									}
									else
									{
										//otras opciones
									}
								}
								else
								{
									//bucle
									if(!empty($camne['CAMPOA'][$i]))
									{
										if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
										{
											$campoaf[$ind]='TODOS';
										}
										else
										{
											//otras opciones
										}
									}
								}
								//valor adicional en este caso color
								if(count($camne['ADICIONAL'])==1 AND $i==0)
								{
									$adicional[$ind]=$camne['ADICIONAL'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['ADICIONAL'][$i]))
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
								}
								//signo de comparacion
								if(count($camne['SIGNO'])==1 AND $i==0)
								{
									$signo[$ind]=$camne['SIGNO'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['SIGNO'][$i]))
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
								}
								$ind++;
								//echo ' pp '.count($camneva);
							}
							//caso de indentar columna
							/*if($camne['TITULO'][$i]=='indentar')
							{
								$tit[$ind]=$camne['TITULO'][$i];
									//buscamos campos a evaluar
								$camneva = explode(",", $camne['CAMPOE'][$i]);
								//si solo es un campo
								if(count($camneva)==1)
								{
									$camneva1 = explode("=", $camneva[0]);
									$campoe[$ind]=$camneva1[0];
									$campov[$ind]=$camneva1[1];
									//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
								}
								else
								{
									//hacer bucle
								}
								//para los campos a afectar
								if(count($camne['CAMPOA'])==1 AND $i==0)
								{
									$campoaf[$ind]=$camne['CAMPOA'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['CAMPOA'][$i]))
									{
										//otras opciones
										$campoaf[$ind]=$camne['CAMPOA'][$i];
									}
								}
								//valor adicional en este caso color
								if(count($camne['ADICIONAL'])==1 AND $i==0)
								{
									$adicional[$ind]=$camne['ADICIONAL'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['ADICIONAL'][$i]))
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
								}
								//signo de comparacion
								if(count($camne['SIGNO'])==1 AND $i==0)
								{
									$signo[$ind]=$camne['SIGNO'][$i];
								}
								else
								{
									//bucle
									if(!empty($camne['SIGNO'][$i]))
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
								}
								$ind++;
							}*/
							//caso italica, subrayar, indentar
							if($camne['TITULO'][$i]=='italica' OR $camne['TITULO'][$i]=='subrayar' OR $camne['TITULO'][$i]=='indentar')
							{
								$tit[$ind]=$camne['TITULO'][$i];
									//buscamos campos a evaluar
								if(!is_array($camne['CAMPOE'][$i]))
								{
									$camneva = explode(",", $camne['CAMPOE'][$i]);
									//si solo es un campo
									if(count($camneva)==1)
									{
										$camneva1 = explode("=", $camneva[0]);
										$campoe[$ind]=$camneva1[0];
										$campov[$ind]=$camneva1[1];
										//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
									}
									else
									{
										//hacer bucle
									}
								}
								else
								{
									//es mas de un campo
									$con_in = count($camne['CAMPOE'][$i]);
									//recorremos registros
									for($j=0;$j<$con_in;$j++)
									{
										//echo $camne['CAMPOE'][$i][$j].' ';
										$camneva = explode(",", $camne['CAMPOE'][$i][$j]);
										//si solo es un campo
										if(count($camneva)==1)
										{
											$camneva1 = explode("=", $camneva[0]);
											$campoe[$ind][$j]=$camneva1[0];
											$campov[$ind][$j]=$camneva1[1];
											//echo ' pp '.$campoe[$ind][$j].' '.$campov[$ind][$j];
										}
									}
								}
								//para los campos a afectar
								if(!is_array($camne['CAMPOA'][$i]))
								{
									if(count($camne['CAMPOA'])==1 AND $i==0)
									{
										$campoaf[$ind]=$camne['CAMPOA'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['CAMPOA'][$i]))
										{
											//otras opciones
											$campoaf[$ind]=$camne['CAMPOA'][$i];
										}
									}
								}
								else
								{
									//recorremos el ciclo
									//es mas de un campo
									$con_in = count($camne['CAMPOA'][$i]);
									//recorremos registros
									for($j=0;$j<$con_in;$j++)
									{
										$campoaf[$ind][$j]=$camne['CAMPOA'][$i][$j];
										//echo ' pp '.$campoaf[$ind][$j];
									}
								}
								//valor adicional en este caso color
								
									if(count($camne['ADICIONAL'])==1 AND $i==0)
									{
										$adicional[$ind]=$camne['ADICIONAL'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['ADICIONAL'][$i]))
										{
											//es mas de un campo
											$con_in = count($camne['ADICIONAL'][$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$adicional[$ind][$j]=$camne['ADICIONAL'][$i][$j];
												//echo ' pp '.$adicional[$ind][$j];
											}
										}
									}
								
								
								//signo de comparacion
								if(!is_array($camne['SIGNO'][$i]))
								{
									if(count($camne['SIGNO'])==1 AND $i==0)
									{
										$signo[$ind]=$camne['SIGNO'][$i];
									}
									else
									{
										//bucle
										if(!empty($camne['SIGNO'][$i]))
										{
											$signo[$ind]=$camne['SIGNO'][$i];
										}
									}
								}
								else
								{
									//es mas de un campo
									$con_in = count($camne['SIGNO'][$i]);
									for($j=0;$j<$con_in;$j++)
									{
										$signo[$ind][$j]=$camne['SIGNO'][$i][$j];
										//echo ' pp '.$signo[$ind][$j];
									}
								}
								$ind++;
							}
						}
					}
					$i=0;
					while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
							//para colocar identificador unicode_decode
							if($ch!=null)
							{
								if(count($ch1)==2)
								{
									$cch=$ch1[0];
									?>
										<tr <?php echo "id=ta_".$row[$cch]."";?> >
									<?php
								}
								else
								{
									//casos con mas id
									$cch='';
									$camch='';
									//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
									for($ca=0;$ca<count($ch1);$ca++)
									{
										if($ca<(count($ch1)-1))
										{
											$cch=$ch1[$ca];
											$camch=$camch.$row[$cch].'--';
										}
									}
									$ca=$ca-1;
									?>
										<tr <?php echo "id=ta_".$camch."";?> >
									<?php
								}
							}
							else
							{
								?>
								<tr >
								<?php
							}
							if($ch!=null)
							{
								if(count($ch1)==2)
								{
									$cch=$ch1[0];
									echo "<td style='text-align: left;'><input type='checkbox' id='id_".$row[$cch]."' name='".$ch1[1]."' value='".$row[$cch]."'
									onclick='validarc(\"id_".$row[$cch]."\",\"".$tabla."\")'></td>";
								}
								else
								{
									//casos con mas id
									$cch='';
									$camch='';
									//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
									for($ca=0;$ca<count($ch1);$ca++)
									{
										if($ca<(count($ch1)-1))
										{
											$cch=$ch1[$ca];
											$camch=$camch.$row[$cch].'--';
										}
									}
									$ca=$ca-1;
									echo "<td style='text-align: left;'><input type='checkbox' id='id_".$camch."' name='".$ch1[$ca]."' value='".$camch."'
									onclick='validarc(\"id_".$camch."\",\"".$tabla."\")'></td>";
									//die();
								}
							}
							//comparamos con los valores de los array para personalizar las celdas
							//para titulo color fila
							$cfila1='';
							$cfila2='';
							//indentar
							$inden='';
							$indencam=array();
							$indencam1=array();
							//contador para caso indentar
							$conin=0;
							//contador caso para saber si cumple varias condiciones ejemplo italica TC=P OR TC=C
							$ca_it=0;
							//variable para colocar italica
							$ita1='';
							$ita2='';
							//contador para caso italicas
							$conita=0;
							//valores de campo a afectar
							$itacam1=array();
							//variables para subrayar
							//valores de campo a afectar en caso subrayar
							$subcam1=array();
							//contador caso subrayar
							$consub=0;
							//contador caso para saber si cumple varias condiciones ejemplo subrayar TC=P OR TC=C
							$ca_sub=0;
							//variable para colocar subrayar
							$sub1='';
							$sub2='';
							for($i=0;$i<$ind;$i++)
							{
								if($tit[$i]=='color_fila')
								{
									if(!is_array($campoe[$i]))
									{
										//campo a comparar
										$tin=$campoe[$i];
										//comparamos valor
										if($signo[$i]=='=')
										{
											if($row[$tin]==$campov[$i])
											{
												if($adicional[$i]=='black')
												{
													//activa condicion
													$cfila1='<B>';
													$cfila2='</B>';
												}
											}
										}
									}
								}
								if($tit[$i]=='indentar')
								{	
									if(!is_array($campoe[$i]))
									{
										//campo a comparar
										$tin=$campoe[$i];
										//comparamos valor
										if($signo[$i]=='=')
										{
											if($campov[$i]=='contar')
											{
												$inden1 = explode(".", $row[$tin]);
												//echo ' '.count($inden1);
												//hacemos los espacios
												//$inden=str_repeat("&nbsp;&nbsp;", count($inden1));
												if(count($inden1)>1)
												{
													$indencam1[$conin]=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", (count($inden1)-1));
												}
												else
												{
													$indencam1[$conin]="";
												}
												/*if(count($inden1)==1)
												{
													$inden='';
												}
												if(count($inden1)==2)
												{
													$inden='&nbsp;';
												}
												if(count($inden1)==3)
												{
													$inden='&nbsp;&nbsp;';
												}
												if(count($inden1)==3)
												{
													$inden='&nbsp;&nbsp;&nbsp;';
												}*/
											}
											$indencam[$conin]=$campoaf[$i];
											//echo $indencam[$conin].' dd ';
											$conin++;
										}
									}
								}
								if($tit[$i]=='italica')
								{	
									if(!is_array($campoe[$i]))
									{
										
									}
									else
									{
										//es mas de un campo
										$con_in = count($campoe[$i]);
										$ca_it=0;
										for($j=0;$j<$con_in;$j++)
										{
											$tin=$campoe[$i][$j];
											//echo ' pp '.$tin[$i][$j];
											//comparamos valor
											if($signo[$i][$j]=='=')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]==$campov[$i][$j])
												{
													$ca_it++;
												}
											}
											//si es diferente
											if($signo[$i][$j]=='<>')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]<>$campov[$i][$j])
												{
													$ca_it++;
												}
											}
											
										}
										$con_in = count($campoaf[$i]);
										for($j=0;$j<$con_in;$j++)
										{
											$itacam1[$conita]=$campoaf[$i][$j];
											//echo $itacam1[$conita].' ';
											$conita++;
										}
										//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
										if($ca_it==count($campoe[$i]))
										{
											$ita1='<em>';
											$ita2='</em>';
										}
										else
										{
											$ita1='';
											$ita2='';
										}
									}
									
								}
								if($tit[$i]=='subrayar')
								{	
									if(!is_array($campoe[$i]))
									{
										
									}
									else
									{
										//es mas de un campo
										$con_in = count($campoe[$i]);
										$ca_sub=0;
										$ca_sub1=0;
										for($j=0;$j<$con_in;$j++)
										{
											$tin=$campoe[$i][$j];
											//echo ' pp '.$tin[$i][$j];
											//comparamos valor
											if($signo[$i][$j]=='=')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]==$campov[$i][$j])
												{
													$ca_sub++;
													$ca_sub1++;
												}
											}
											//si es diferente
											if($signo[$i][$j]=='<>')
											{
												//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
												if($row[$tin]<>$campov[$i][$j])
												{
													$ca_sub++;
												}
											}
											
										}
										$con_in = count($campoaf[$i]);
										for($j=0;$j<$con_in;$j++)
										{
											$subcam1[$consub]=$campoaf[$i][$j];
											//echo $subcam1[$consub].' ';
											$consub++;
										}
										//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
										$sub1='';
										$sub2='';
										//condicion para verificar si signo es "=" o no
										if($ca_sub1==0)
										{
											//condicion en caso de distintos
											if($ca_sub==count($campoe[$i]))
											{
												$sub1='<u>';
												$sub2='</u>';
											}
											else
											{
												$sub1='';
												$sub2='';
											}
										}
										else
										{
											$sub1='<u>';
											$sub2='</u>';
										}
									}
								}
							}
							//para check box
						
						for($i=0;$i<$cant;$i++)
						{
							//caso indentar
							for($j=0;$j<count($indencam);$j++)
							{
								if($indencam[$j]==$i)
								{
									$inden=$indencam1[$j];
								}
								else
								{
									$inden='';
								}
							}
							//caso italica
							$ita3="";
							$ita4="";
							for($j=0;$j<count($itacam1);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($itacam1[$j]==$i)
								{
									$ita3=$ita1;
									$ita4=$ita2;
								}
								
							}
							//caso subrayado
							$sub3="";
							$sub4="";
							for($j=0;$j<count($subcam1);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($subcam1[$j]==$i)
								{
									$sub3=$sub1;
									$sub4=$sub2;
								}
								
							}
							//caso de campos fechas
							for($j=0;$j<count($cam_fech);$j++)
							{
								//echo $itacam1[$j].' ssscc '.$i;
								if($cam_fech[$j]==$i)
								{
									//$row[$i]=$row[$i]->format('Y-m-d H:i:s');
									$row[$i]=$row[$i]->format('Y-m-d');
								}
								
							}
							//echo "<br/>";
							//formateamos texto si es decimal
							if($tipo_campo[$i]=="style='text-align: right;'")
							{
								//si es cero colocar -
								if(number_format($row[$i],2, ',', '.')==0.00 OR number_format($row[$i],2, ',', '.')=='0,00')
								{
									if($row[$i]>0)
									{
										echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden.number_format($row[$i],2, ',', '.').$sub4.$ita4.$cfila2."</td>";
									}
									else
									{
										echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
									}
								}
								else
								{
									//si es negativo colocar rojo
									if($row[$i]<0)
									{
										//reemplazo una parte de la cadena por otra
										$longitud_cad = strlen($tipo_campo[$i]); 
										$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
										echo "<td ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
									}
									else
									{
										echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
									}
								}
								
							}
							else
							{
								if(strlen($row[$i])<=50)
								{
									echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
								}
								else
								{
									$resultado = substr($row[$i], 0, 50);
									//echo $resultado; // imprime "ue"
									echo "<td ".$tipo_campo[$i]." data-toggle='tooltip' data-placement='left' title='".$row[$i]."'>".$cfila1.$ita3.$inden.$sub3."".$resultado."...".$sub4.$ita4.$cfila2."</td>";
								}
							}
						}
						/*$cam=$campo[$i];
						echo "<td>".$row['DG']."</td>";
						echo "<td>".$row['Codigo']."</td>";
						echo "<td>".$row['Cuenta']."</td>";
						echo "<td>".$row['Saldo_Anterior']."</td>";
						echo "<td>".$row['Debitos']."</td>";
						echo "<td>".$row['Creditos']."</td>";
						echo "<td>".$row['Saldo_Total']."</td>";
						echo "<td>".$row['TC']."</td>";*/
						 ?>
						  </tr>
						  <?php
						
						//$campo
						  //echo $row[$i].", <br />";
						  $i++;
						  if($cant==($i))
						  {
							  
							  //echo $cant.' ddddd '.$i;
							  $i=0;
							 
						  }
					}
		 ?>
			</table>
		</div>
		  <?php
	}
	else
	{
		if($base=='MYSQL')
		{
			$info_campo = $stmt->fetch_fields();
			$cant=0;
			//guardamos los campos
			$campo='';
			foreach ($info_campo as $valor) 
			{
				$cant++;
			}
			if($ch!=null)
			{
				$ch1 = explode(",", $ch);
				$cant++;
			}
			//si lleva o no border
			$bor='';
			if($b!=null and $b!='0')
			{
				$bor='table-bordered1';
				//style="border-top: 1px solid #bce8f1;"
			}
			//colocar cero a tabla en caso de no existir definida ninguna
			if($tabla==null OR $tabla=='0' OR $tabla=='')
			{
				$tabla=0;
			}
			?>
				<div class="box-body no-padding">
					<table class="table table-striped w-auto <?php echo $bor; ?>" >
						<?php
						if($ti!='' or $ti!=null)
						{
					?>
							<tr>
								<th colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
							</tr>
					<?php
						}
					?>
						<tr>
							<th colspan='<?php echo $cant; ?>' style='text-align: center;background-color: #0086c7;color: #FFFFFF;' ><?php echo $ti; ?></th>
						</tr>
						<tr>
							<?php
							//cantidad campos
							$cant=0;
							//guardamos los campos
							$campo='';
							//tipo de campos
							$tipo_campo=array();
							//guardamos posicion de un campo ejemplo fecha
							$cam_fech=array();
							//contador para fechas
							$cont_fecha=0;
							//obtenemos los campos 
							//en caso de tener check
							if($ch!=null)
							{
								echo "<th style='text-align: left;'>SEL</th>";
							}
							foreach ($info_campo as $valor) 
							{
								//$camp='';
								$i=0;
								//tipo de campo
								/*
								tinyint_    1   boolean_    1   smallint_    2 int_        3
								float_      4   double_     5   real_        5 timestamp_    7
								bigint_     8   serial      8   mediumint_    9 date_        10
								time_       11  datetime_   12  year_        13 bit_        16
								decimal_    246 text_       252 tinytext_    252 mediumtext_    252
								longtext_   252 tinyblob_   252 mediumblob_    252 blob_        252
								longblob_   252 varchar_    253 varbinary_    253 char_        254
								binary_     254
								*/
								$ban=0;
								//texto
								if( $valor->type==7 OR $valor->type==8 OR $valor->type==10
								 OR $valor->type==11 OR $valor->type==12 OR $valor->type==13 OR $valor->type==16 
								 OR $valor->type==252 OR $valor->type==253 OR $valor->type==254 )
								{
									$tipo_campo[($cant)]="style='text-align: left;'";
									$ban=1;
								}
								//numero
								//echo $valor->type.' cccc '.$valor->type['Type'].'<br>';
								if(isset($valor->type) )
								{
									if($valor->type==3 OR $valor->type==2 OR $valor->type==4 OR $valor->type==5
									 OR $valor->type==9 OR $valor->type==246)
									{
										//number_format($item_i['nombre'],2, ',', '.')
										$tipo_campo[($cant)]="style='text-align: right;'";
										$ban=1;
									}
								}
								else
								{
									if( isset($valor->type['Type']))
									{
										if( $valor->type['Type']==8 )
										{
											//number_format($item_i['nombre'],2, ',', '.')
											$tipo_campo[($cant)]="style='text-align: right;'";
											$ban=1;
										}
									}
								}
								if($ban==0)
								{
									echo ' no existe tipo '.$valor->type.' '.$valor->name.' '.$valor->table;
								}
								echo "<th ".$tipo_campo[$cant].">".$valor->name."</th>";
											$camp=$valor->name;
											$campo[$cant]=$camp;
											//echo ' dd '.$campo[$cant];
											$cant++;
							}
							?>
						</tr>
						<?php
						//echo $cant.' fffff ';
							//obtener la configuracion para celdas personalizadas
							//campos a evaluar
							$campoe=array();
							//valor a verificar
							$campov=array();
							//campo a afectar 
							$campoaf=array();
							//adicional
							$adicional=array();
							//signos para comparar
							$signo=array();
							//titulo de proceso
							$tit=array();
							//indice de registros a comparar con datos
							$ind=0;
							//obtener valor en caso de mas de una condicion
							$con_in=0;
							if($camne!=null)
							{
								for($i=0;$i<count($camne['TITULO']);$i++)
								{
									if($camne['TITULO'][$i]=='color_fila')
									{	
										$tit[$ind]=$camne['TITULO'][$i];
										//temporar para indice
										//$temi=$i;
										//buscamos campos a evaluar
										$camneva = explode(",", $camne['CAMPOE'][$i]);
										//si solo es un campo
										if(count($camneva)==1)
										{
											$camneva1 = explode("=", $camneva[0]);
											$campoe[$ind]=$camneva1[0];
											$campov[$ind]=$camneva1[1];
											//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
										}
										else
										{
											//hacer bucle
										}
										//para los campos a afectar
										if(count($camne['CAMPOA'])==1 AND $i==0)
										{
											if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
											{
												$campoaf[$ind]='TODOS';
											}
											else
											{
												//otras opciones
											}
										}
										else
										{
											//bucle
											if(!empty($camne['CAMPOA'][$i]))
											{
												if($camne['CAMPOA'][$i]=='TODOS' OR $camne['CAMPOA'][$i]='')
												{
													$campoaf[$ind]='TODOS';
												}
												else
												{
													//otras opciones
												}
											}
										}
										//valor adicional en este caso color
										if(count($camne['ADICIONAL'])==1 AND $i==0)
										{
											$adicional[$ind]=$camne['ADICIONAL'][$i];
										}
										else
										{
											//bucle
											if(!empty($camne['ADICIONAL'][$i]))
											{
												$adicional[$ind]=$camne['ADICIONAL'][$i];
											}
										}
										//signo de comparacion
										if(count($camne['SIGNO'])==1 AND $i==0)
										{
											$signo[$ind]=$camne['SIGNO'][$i];
										}
										else
										{
											//bucle
											if(!empty($camne['SIGNO'][$i]))
											{
												$signo[$ind]=$camne['SIGNO'][$i];
											}
										}
										$ind++;
										//echo ' pp '.count($camneva);
									}
									//caso italica, subrayar, indentar
									if($camne['TITULO'][$i]=='italica' OR $camne['TITULO'][$i]=='subrayar' OR $camne['TITULO'][$i]=='indentar')
									{
										$tit[$ind]=$camne['TITULO'][$i];
											//buscamos campos a evaluar
										if(!is_array($camne['CAMPOE'][$i]))
										{
											$camneva = explode(",", $camne['CAMPOE'][$i]);
											//si solo es un campo
											if(count($camneva)==1)
											{
												$camneva1 = explode("=", $camneva[0]);
												$campoe[$ind]=$camneva1[0];
												$campov[$ind]=$camneva1[1];
												//echo ' pp '.$campoe[$ind].' '.$campov[$ind];
											}
											else
											{
												//hacer bucle
											}
										}
										else
										{
											//es mas de un campo
											$con_in = count($camne['CAMPOE'][$i]);
											//recorremos registros
											for($j=0;$j<$con_in;$j++)
											{
												//echo $camne['CAMPOE'][$i][$j].' ';
												$camneva = explode(",", $camne['CAMPOE'][$i][$j]);
												//si solo es un campo
												if(count($camneva)==1)
												{
													$camneva1 = explode("=", $camneva[0]);
													$campoe[$ind][$j]=$camneva1[0];
													$campov[$ind][$j]=$camneva1[1];
													//echo ' pp '.$campoe[$ind][$j].' '.$campov[$ind][$j];
												}
											}
										}
										//para los campos a afectar
										if(!is_array($camne['CAMPOA'][$i]))
										{
											if(count($camne['CAMPOA'])==1 AND $i==0)
											{
												$campoaf[$ind]=$camne['CAMPOA'][$i];
											}
											else
											{
												//bucle
												if(!empty($camne['CAMPOA'][$i]))
												{
													//otras opciones
													$campoaf[$ind]=$camne['CAMPOA'][$i];
												}
											}
										}
										else
										{
											//recorremos el ciclo
											//es mas de un campo
											$con_in = count($camne['CAMPOA'][$i]);
											//recorremos registros
											for($j=0;$j<$con_in;$j++)
											{
												$campoaf[$ind][$j]=$camne['CAMPOA'][$i][$j];
												//echo ' pp '.$campoaf[$ind][$j];
											}
										}
										//valor adicional en este caso color
										
											if(count($camne['ADICIONAL'])==1 AND $i==0)
											{
												$adicional[$ind]=$camne['ADICIONAL'][$i];
											}
											else
											{
												//bucle
												if(!empty($camne['ADICIONAL'][$i]))
												{
													//es mas de un campo
													$con_in = count($camne['ADICIONAL'][$i]);
													for($j=0;$j<$con_in;$j++)
													{
														$adicional[$ind][$j]=$camne['ADICIONAL'][$i][$j];
														//echo ' pp '.$adicional[$ind][$j];
													}
												}
											}
										
										
										//signo de comparacion
										if(!is_array($camne['SIGNO'][$i]))
										{
											if(count($camne['SIGNO'])==1 AND $i==0)
											{
												$signo[$ind]=$camne['SIGNO'][$i];
											}
											else
											{
												//bucle
												if(!empty($camne['SIGNO'][$i]))
												{
													$signo[$ind]=$camne['SIGNO'][$i];
												}
											}
										}
										else
										{
											//es mas de un campo
											$con_in = count($camne['SIGNO'][$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$signo[$ind][$j]=$camne['SIGNO'][$i][$j];
												//echo ' pp '.$signo[$ind][$j];
											}
										}
										$ind++;
									}
								}
							}
							$i=0;
							while ($row = $stmt->fetch_row()) 
							//while($row=$stmt->fetch_array())
							//while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
							{
								//para colocar identificador unicode_decode
								if($ch!=null)
								{
									if(count($ch1)==2)
									{
										$cch=$ch1[0];
										?>
											<tr <?php echo "id=ta_".$row[$cch]."";?> >
										<?php
									}
									else
									{
										//casos con mas id
										$cch='';
										$camch='';
										//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
										for($ca=0;$ca<count($ch1);$ca++)
										{
											if($ca<(count($ch1)-1))
											{
												$cch=$ch1[$ca];
												$camch=$camch.$row[$cch].'--';
											}
										}
										$ca=$ca-1;
										?>
											<tr <?php echo "id=ta_".$camch."";?> >
										<?php
									}
								}
								else
								{
									?>
									<tr >
									<?php
								}
								if($ch!=null)
								{
									if(count($ch1)==2)
									{
										$cch=$ch1[0];
										echo "<td style='text-align: left;'><input type='checkbox' id='id_".$row[$cch]."' name='".$ch1[1]."' value='".$row[$cch]."'
										onclick='validarc(\"id_".$row[$cch]."\",\"".$tabla."\")'></td>";
									}
									else
									{
										//casos con mas id
										$cch='';
										$camch='';
										//no manda fechas se debe colocar $row[$i]->format('Y-m-d');
										for($ca=0;$ca<count($ch1);$ca++)
										{
											if($ca<(count($ch1)-1))
											{
												$cch=$ch1[$ca];
												$camch=$camch.$row[$cch].'--';
											}
										}
										$ca=$ca-1;
										echo "<td style='text-align: left;'><input type='checkbox' id='id_".$camch."' name='".$ch1[$ca]."' value='".$camch."'
										onclick='validarc(\"id_".$camch."\",\"".$tabla."\")'></td>";
										//die();
									}
								}
								//comparamos con los valores de los array para personalizar las celdas
								//para titulo color fila
								$cfila1='';
								$cfila2='';
								//indentar
								$inden='';
								$indencam=array();
								$indencam1=array();
								//contador para caso indentar
								$conin=0;
								//contador caso para saber si cumple varias condiciones ejemplo italica TC=P OR TC=C
								$ca_it=0;
								//variable para colocar italica
								$ita1='';
								$ita2='';
								//contador para caso italicas
								$conita=0;
								//valores de campo a afectar
								$itacam1=array();
								//variables para subrayar
								//valores de campo a afectar en caso subrayar
								$subcam1=array();
								//contador caso subrayar
								$consub=0;
								//contador caso para saber si cumple varias condiciones ejemplo subrayar TC=P OR TC=C
								$ca_sub=0;
								//variable para colocar subrayar
								$sub1='';
								$sub2='';
								for($i=0;$i<$ind;$i++)
								{
									if($tit[$i]=='color_fila')
									{
										if(!is_array($campoe[$i]))
										{
											//campo a comparar
											$tin=$campoe[$i];
											//comparamos valor
											if($signo[$i]=='=')
											{
												if($row[$tin]==$campov[$i])
												{
													if($adicional[$i]=='black')
													{
														//activa condicion
														$cfila1='<B>';
														$cfila2='</B>';
													}
												}
											}
										}
									}
									if($tit[$i]=='indentar')
									{	
										if(!is_array($campoe[$i]))
										{
											//campo a comparar
											$tin=$campoe[$i];
											//comparamos valor
											if($signo[$i]=='=')
											{
												if($campov[$i]=='contar')
												{
													$inden1 = explode(".", $row[$tin]);
													//echo ' '.count($inden1);
													//hacemos los espacios
													//$inden=str_repeat("&nbsp;&nbsp;", count($inden1));
													if(count($inden1)>1)
													{
														$indencam1[$conin]=str_repeat("&nbsp;&nbsp;&nbsp;&nbsp;", (count($inden1)-1));
													}
													else
													{
														$indencam1[$conin]="";
													}
												}
												$indencam[$conin]=$campoaf[$i];
												//echo $indencam[$conin].' dd ';
												$conin++;
											}
										}
									}
									if($tit[$i]=='italica')
									{	
										if(!is_array($campoe[$i]))
										{
											
										}
										else
										{
											//es mas de un campo
											$con_in = count($campoe[$i]);
											$ca_it=0;
											for($j=0;$j<$con_in;$j++)
											{
												$tin=$campoe[$i][$j];
												//echo ' pp '.$tin[$i][$j];
												//comparamos valor
												if($signo[$i][$j]=='=')
												{
													//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
													if($row[$tin]==$campov[$i][$j])
													{
														$ca_it++;
													}
												}
												//si es diferente
												if($signo[$i][$j]=='<>')
												{
													//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
													if($row[$tin]<>$campov[$i][$j])
													{
														$ca_it++;
													}
												}
												
											}
											$con_in = count($campoaf[$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$itacam1[$conita]=$campoaf[$i][$j];
												//echo $itacam1[$conita].' ';
												$conita++;
											}
											//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
											if($ca_it==count($campoe[$i]))
											{
												$ita1='<em>';
												$ita2='</em>';
											}
											else
											{
												$ita1='';
												$ita2='';
											}
										}
										
									}
									if($tit[$i]=='subrayar')
									{	
										if(!is_array($campoe[$i]))
										{
											
										}
										else
										{
											//es mas de un campo
											$con_in = count($campoe[$i]);
											$ca_sub=0;
											$ca_sub1=0;
											for($j=0;$j<$con_in;$j++)
											{
												$tin=$campoe[$i][$j];
												//echo ' pp '.$tin[$i][$j];
												//comparamos valor
												if($signo[$i][$j]=='=')
												{
													//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
													if($row[$tin]==$campov[$i][$j])
													{
														$ca_sub++;
														$ca_sub1++;
													}
												}
												//si es diferente
												if($signo[$i][$j]=='<>')
												{
													//echo $row[$tin].' wwww '.$campov[$i][$j].'<br/>';
													if($row[$tin]<>$campov[$i][$j])
													{
														$ca_sub++;
													}
												}
												
											}
											$con_in = count($campoaf[$i]);
											for($j=0;$j<$con_in;$j++)
											{
												$subcam1[$consub]=$campoaf[$i][$j];
												//echo $subcam1[$consub].' ';
												$consub++;
											}
											//echo $ca_it.' cdcd '.count($campoe[$i]).'<br/>';
											$sub1='';
											$sub2='';
											//condicion para verificar si signo es "=" o no
											if($ca_sub1==0)
											{
												//condicion en caso de distintos
												if($ca_sub==count($campoe[$i]))
												{
													$sub1='<u>';
													$sub2='</u>';
												}
												else
												{
													$sub1='';
													$sub2='';
												}
											}
											else
											{
												$sub1='<u>';
												$sub2='</u>';
											}
										}
									}
								}
								//para check box
							
								for($i=0;$i<$cant;$i++)
								{
									//caso indentar
									for($j=0;$j<count($indencam);$j++)
									{
										if($indencam[$j]==$i)
										{
											$inden=$indencam1[$j];
										}
										else
										{
											$inden='';
										}
									}
									//caso italica
									$ita3="";
									$ita4="";
									for($j=0;$j<count($itacam1);$j++)
									{
										//echo $itacam1[$j].' ssscc '.$i;
										if($itacam1[$j]==$i)
										{
											$ita3=$ita1;
											$ita4=$ita2;
										}
										
									}
									//caso subrayado
									$sub3="";
									$sub4="";
									for($j=0;$j<count($subcam1);$j++)
									{
										//echo $itacam1[$j].' ssscc '.$i;
										if($subcam1[$j]==$i)
										{
											$sub3=$sub1;
											$sub4=$sub2;
										}
										
									}
									//caso de campos fechas
									for($j=0;$j<count($cam_fech);$j++)
									{
										//echo $itacam1[$j].' ssscc '.$i;
										if($cam_fech[$j]==$i)
										{
											//$row[$i]=$row[$i]->format('Y-m-d H:i:s');
											$row[$i]=$row[$i]->format('Y-m-d');
										}
										
									}
									//echo "<br/>";
									//formateamos texto si es decimal
									if($tipo_campo[$i]=="style='text-align: right;'")
									{
										//si es cero colocar -
										if(number_format($row[$i],2, ',', '.')==0 OR number_format($row[$i],2, ',', '.')=='0.00')
										{
											echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
										}
										else
										{
											//si es negativo colocar rojo
											if($row[$i]<0)
											{
												//reemplazo una parte de la cadena por otra
												$longitud_cad = strlen($tipo_campo[$i]); 
												$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
												echo "<td ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
											}
											else
											{
												echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
											}
										}
										
									}
									else
									{
										if(strlen($row[$i])<=50)
										{
											echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
										}
										else
										{
											$resultado = substr($row[$i], 0, 50);
											//echo $resultado; // imprime "ue"
											echo "<td ".$tipo_campo[$i]." data-toggle='tooltip' data-placement='left' title='".$row[$i]."'>".$cfila1.$ita3.$inden.$sub3."".$resultado."...".$sub4.$ita4.$cfila2."</td>";
										}
									}
								}
								/*$cam=$campo[$i];
								echo "<td>".$row['DG']."</td>";
								echo "<td>".$row['Codigo']."</td>";
								echo "<td>".$row['Cuenta']."</td>";
								echo "<td>".$row['Saldo_Anterior']."</td>";
								echo "<td>".$row['Debitos']."</td>";
								echo "<td>".$row['Creditos']."</td>";
								echo "<td>".$row['Saldo_Total']."</td>";
								echo "<td>".$row['TC']."</td>";*/
								 ?>
								  </tr>
								  <?php
								
								//$campo
								  //echo $row[$i].", <br />";
								  $i++;
								  if($cant==($i))
								  {
									  
									  //echo $cant.' ddddd '.$i;
									  $i=0;
									 
								  }
							}
						?>
					</table>
				</div>
				<?php
		}
	}
}	
?>
