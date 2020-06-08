<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once("../../../db/db.php");
require_once("../../../funciones/funciones_ajax.php");
require_once("../../../../lib/excel/plantilla.php");
//require_once("../../../../lib/fpdf/reporte_comp.php");
require_once("../../../../lib/fpdf/reporte_de.php");
require_once("../../../funciones/numeros_en_letras.php");
//require_once("../../../funciones/funciones.php");

//caso comprobantes aprobados codigo para buscar los comprobantes
if(isset($_POST['ajax_page']) ) 
{
	//buscar productos en mesa
	if($_REQUEST['ajax_page']=='pop1')
	{
		pop1();
	}
	//mostrar mesas
	if($_REQUEST['ajax_page']=='pop2')
	{
		pop2();
	}
	//elimiar item
	if($_REQUEST['ajax_page']=='pop3')
	{
		pop3();
	}
	//mostrar pedidos cocina
	if($_REQUEST['ajax_page']=='ped2')
	{
		ped2();
	}
	//enviar pedidos
	if($_REQUEST['ajax_page']=='env')
	{
		env();
	}
	//pre factura y factura
	if($_REQUEST['ajax_page']=='fac1')
	{
		fac();
	}
	//autocompletar registro otra forma
	if($_REQUEST['ajax_page']=='aut1')
	{
		autocompletar1();
	}
	//pre factura y factura
	if($_REQUEST['ajax_page']=='cli1')
	{
		cli();
	}
	//digito verificador
	if($_REQUEST['ajax_page']=='dig')
	{
		digito_verificadorf($_POST['ruc'],$_POST['vista'],$_POST['idMen'],$_POST['item']);
	}
	//mandar pdf comprobante seleccionado
	if($_REQUEST['ajax_page']=='rfac')
	{
		reporte_pfac1();
	}
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

function paginador_ajax($tabla,$filtro=null,$link=null)
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
//conseguir un valor en una etiqueta xml
function etiqueta_xml($xml,$eti)
{
	//validar que etiqueta sea unica
	$cont=substr_count($xml,$eti);
	if( $cont <= 1 and $cont<>0 )
	{
		$resul1 = explode($eti, $xml);
		$cont1=substr_count($eti,">");
		$eti1 = str_replace("<", "</", $eti);
		//sin atributos
		if($cont1==1)
		{
			$resul2 = explode($eti1, $resul1[1]);
		}
		else
		{
			//con atributos
			$resul3 = explode(">", $resul1[1]);
			$resul2 = explode($eti1, $resul3[1]);
		}
		if($eti=='<baseImponible')
		{
			//echo $resul2[0].' ssssssssssssssssssss<br>';
		}
		//$resul2 = explode($eti1, $resul1[1]);
		//echo $resul2[0].' <br>';
		return $resul2[0]; 
		
	}
	else
	{
		if( $cont > 1  )
		{
			//echo " vvv ".$cont;
			$resul1 = explode($eti, $xml);
			//$eti1 = str_replace("<", "</", $eti);
			//$resul2 = explode($eti1, $resul1[1]);
			$j=0;
			$resul4=array();
			
			for($i=0;$i<count($resul1);$i++)
			{
				if($i>=1)
				{
					$resul3 = explode(">", $resul1[$i]);
					$eti1 = str_replace("<", "</", $eti);
					$resul2 = explode($eti1, $resul3[1]);
					$resul4[$j]=$resul2[0];
					//echo $resul2[0].' <br>';
					//echo " segunda opc".' <br>';
					//echo $j.' <br>';
					if($eti=='<baseImponible')
					{
						//echo $resul1[$i].' ssssssssssssssssssss<br>';
					}
					$j++;
				}
			}
			return $resul4;
		}
		else
		{
			return '';
		}
	}
}
//tomar solo porcion de etiqueta xml
function porcion_xml($xml,$eti,$etf)
{
	$resul1 = explode($eti, $xml);
	$resul4=array();
	$j=0;
	for($i=0;$i<count($resul1);$i++)
	{
		if($i>=1)
		{
			$resul2 = explode($etf, $resul1[$i]);
			$resul4[$j]=$resul2[0];
			//echo $resul2[0];
			$j++;
		}
	}
	return $resul4;
}
//$imp variable para descargar o no archivo
function ImprimirDoc($stmt,$id=null,$formato=null,$va=null,$imp=null,$ruta=null,$ti1=null)
{
	if($ruta==null)
	{
		//require_once("../../lib/fpdf/reporte_de.php");
	}
	$nombre_archivo = "TEMP/".$id.".".$formato; 
	if($formato=='xml')
	{
		if($imp==0)
		{
			$nombre_archivo = "TEMP/".$id.".xml"; 
		}
		if(file_exists($nombre_archivo))
		{
			//$mensaje = "El Archivo $nombre_archivo se ha modificado";
		}
	 
		else
		{
			//$mensaje = "El Archivo $nombre_archivo se ha creado";
		}
		
		//if($archivo = fopen($nombre_archivo, "a"))
		if($archivo = fopen($nombre_archivo, "w+b"))
		{
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				$row[0] = str_replace("ï»¿", "", $row[0]);
				if(fwrite($archivo, $row[0]))
				{
					echo "Se ha ejecutado correctamente";
				}
				else
				{
					echo "Ha habido un problema al crear el archivo";
				}
			}
		   
	 
			fclose($archivo);
		}
		if($imp==null or $imp==1)
		{
			if (file_exists($nombre_archivo)) {
				$downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($nombre_archivo);
				
				header('Content-Description: File Transfer');
				header('Content-Type: application/octet-stream');
				header('Content-Disposition: attachment; filename=' . $downloadfilename);
				header('Content-Transfer-Encoding: binary');
				header('Expires: 0');
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Content-Length: ' . filesize($nombre_archivo));
				
				ob_clean();
				flush();
				readfile($nombre_archivo);
				
				exit;
			}
		}
	}
	if($formato=='pdf')
	{
		$nombre_archivo = "TEMP/".$id.".xml"; 
		//desde archivo
		if($va==1)
		{
			//echo "asas";
			if(file_exists($nombre_archivo))
			{
				//$mensaje = "El Archivo $nombre_archivo se ha modificado";
			}
		 
			else
			{
				//$mensaje = "El Archivo $nombre_archivo se ha creado";
			}
		 
			//if($archivo = fopen($nombre_archivo, "a"))
			if($archivo = fopen($nombre_archivo, "w+b"))
			{
				
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$row[0] = str_replace("ï»¿", "", $row[0]);
					$stmt1=$row[0];
					$ti=$row[1];
					if(fwrite($archivo, $row[0]))
					{
						//echo "Se ha ejecutado correctamente";
					}
					else
					{
						echo "Ha habido un problema al crear el archivo";
					}
				}
			   
		 
				fclose($archivo);
			}
		}
		else
		{
			//echo "dddd";
			//desde variable
			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
			{
				//echo $row[0];
				$row[0] = str_replace("ï»¿", "", $row[0]);
				$stmt1=$row[0];
				$ti=$row[1];
			}
		}
		$ti='PFA';
		//die();
		//echo $ti;
		//die();
		if($ti1=='PFA')
		{
			$param=array();
			$param[0]['nombrec']=$_POST['nombrec'];
			//echo $param[0]['nombrec'].' -- ';
			$param[0]['ruc']=$_POST['ruc'];
			$param[0]['mesa']=$_POST['me'];
			$param[0]['PFA']=$ti1;
			//imprimirDocElP($stmt1,$id,$formato,$nombre_archivo,$va,$imp,$param);
			imprimirDocElPF($stmt1,$id,$formato,$nombre_archivo,$va,$imp,$param);
		}
		if($ti1=='FA')
		{
			imprimirDocEl($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		/*if($ti=='NC')
		{
			imprimirDocElNC($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='RE')
		{
			imprimirDocElRE($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='GR' OR $ti=='XX')
		{
			imprimirDocElGR($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='NV')
		{
			imprimirDocElNV($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}
		if($ti=='ND')
		{
			imprimirDocElND($stmt1,$id,$formato,$nombre_archivo,$va,$imp);
		}*/
		
	}
}
//pdf facturas y prefacturas
function reporte_pfac1($com=null,$cl=null)
{
	//($_GET['cl'],'pdf','Trans_Documentos','Documento_Autorizado,TD','Clave_Acceso')
	//($id,$formato=null,$tabla=null,$campo=null,$campob=null,$imp=null)
	$cid=cone_ajaxSQL();
	if($com!=null)
	{
		$_POST['com']=$com;
	}
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	if($cl=='pre1')
	{
		/*
			  FA.TC = TipoFactura
			  FA.Fecha = MBoxFecha
			  sSQL = "SELECT * " _
				   & "FROM Catalogo_Lineas " _
				   & "WHERE TL <> " & Val(adFalse) & " " _
				   & "AND Item = '" & NumEmpresa & "' " _
				   & "AND Periodo = '" & Periodo_Contable & "' " _
				   & "AND Fact = '" & FA.TC & "' " _
				   & "AND Fecha <= #" & BuscarFecha(FA.Fecha) & "# " _
				   & "AND Vencimiento >= #" & BuscarFecha(FA.Fecha) & "# " _
				   & "ORDER BY Codigo "
			  SelectDBCombo DCLinea, AdoLinea, sSQL, "Concepto"
			  
				SELECT        TOP (200) Periodo, TL, Codigo, Concepto, Fact, CxC, Cta_Venta, Logo_Factura, Largo, Ancho, Item, Individual, Espacios, Pos_Factura, Fact_Pag, Pos_Y_Fact, Serie, Autorizacion, Vencimiento, Fecha, Secuencial, ItemsxFA, 
                    Grupo_I, Grupo_F, CxC_Anterior, Imp_Mes, Nombre_Establecimiento, Direccion_Establecimiento, Telefono_Estab, Logo_Tipo_Estab, Tipo_Impresion, ID, X
				FROM            Catalogo_Lineas
				WHERE        (LEN(Autorizacion) >= 13) AND (Periodo = '.') AND (Item = '001') AND (Fact = 'FA') AND (Fecha <= '2020-03-11') AND (Vencimiento >= '2020-03-11')
		*/
		//0203202001179185161700120010050000147851234567811
		//FA	001005	14785
		$sql="SELECT Documento_Autorizado,TD
			FROM  Trans_Documentos WHERE
			Item = '".$_SESSION['INGRESO']['item']."'  
			AND Clave_Acceso='0203202001179185161700120010050000147851234567811' ";
		$sql=$sql."  ";
			//echo $sql;
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
		//echo ' mm '.$imp;
		
		ImprimirDoc($stmt,$_POST['me'],'pdf',null,null,null,'PFA');
		//hacemos update para imprimir automatico
		$sql="UPDATE Catalogo_Productos SET RP=1 
		WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv = '".$_POST['me']."'";
		$sql=$sql."  ";
			//echo $sql;
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
	}
	cerrarSQLSERVERFUN($cid);
}
//pdf facturas y prefacturas
function reporte_pfac($com=null,$cl=null)
{
	$cid=cone_ajaxSQL();
	if($com!=null)
	{
		$_POST['com']=$com;
	}
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	if($cl=='pre1')
	{
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
//funcion para facturar y prefacturar
function cli($cl=null)
{
	$cid=cone_ajaxSQL();
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	if($cl=='cli1')
	{
		$me=$_POST['me'];
		$nom=$_POST['nom'];
		?>
			<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Datos Cliente</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
              <div class="box-body">
				<div class="form-group">
					<label for="ruc" class="col-sm-1 control-label" id="resultado">RUC/CI*</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="ruc" name="ruc" placeholder="ruc"  
						onkeyup="vcliente(event,'<?php echo $me; ?>','ruc')" tabindex="0">
						<div id='e_ruc' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar RUC/CI</span>
						</div>
					</div>
					<label for="telefono" class="col-sm-1 control-label">Telefono*</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Telefono" 
						onkeyup="vcliente(event,'<?php echo $me; ?>','ruc')" onclick="vcliente(event,'<?php echo $me; ?>','ruc')" tabindex="0">
						<div id='e_telefono' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Telefono</span>
						</div>
					</div>
					<label for="codigoc" class="col-sm-1 control-label">Codigo*</label>
					<div class="col-sm-3">
						<input type="hidden" id='buscar' name='buscar'  value='' />
						<input type="hidden" id='TC' name='TC'  value='' />
						<input type="text" class="form-control" id="codigoc" name="codigoc" placeholder="Codigo" disabled
						tabindex="0">
						<div id='e_codigoc' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Codigo</span>
						</div>
					</div>
                </div>
                <div class="form-group">
					<label for="nombrec" class="col-sm-1 control-label">Razon social*</label>

					<div class="col-sm-11">
						<input type="text" class="form-control" id="nombrec" name="nombrec" placeholder="Razon social" 
						tabindex="0">
						<div id='e_nombrec' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Razon social</span>
						</div>
					</div>
                </div>
				<div class="form-group">
					<label for="email" class="col-sm-1 control-label">Email</label>

					<div class="col-sm-11">
						<input type="email" class="form-control" id="email" name="email" placeholder="Email" tabindex="0">
					</div>
                </div>
				<div class="form-group">
					<label for="direccion" class="col-sm-1 control-label">Direccion*</label>

					<div class="col-sm-11">
						<input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion" tabindex="0">
						<div id='e_direccion' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Direccion</span>
						</div>
					</div>
                </div>
				<div class="form-group">
				    <label for="nv" class="col-sm-1 control-label">Numero vivienda</label>

				    <div class="col-sm-3">
				    	<input type="text" class="form-control" id="nv" name="nv" placeholder="Numero vivienda"  tabindex="0">
				    </div>
				    <label for="grupo" class="col-sm-1 control-label">Grupo</label>
				    <div class="col-sm-3">
						<input type="text" class="form-control" id="grupo" name="grupo" placeholder="Grupo" 
						tabindex="0">
					</div>
					<label for="naciona" class="col-sm-1 control-label">Nacionalidad</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="naciona" name="naciona" placeholder="Nacionalidad" 
						tabindex="0">
					</div>
                </div>
				<div class="form-group">
				    <label for="prov" class="col-sm-1 control-label">Provincia</label>

				    <div class="col-sm-5">
				    	<input type="text" class="form-control" id="prov" name="prov" placeholder="Provincia"  tabindex="0">
				    </div>
				    <label for="ciu" class="col-sm-1 control-label">Ciudad</label>
				    <div class="col-sm-5">
						<input type="text" class="form-control" id="ciu" name="ciu" placeholder="Ciudad" 
						tabindex="0">
					</div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<a class="btn btn-info pull-right" id='proce' onclick="guardac1(event,'<?php echo $me; ?>','ruc','<?php echo $me; ?>')" 
				onkeyup="guardac1(event,'<?php echo $me; ?>','ruc','<?php echo $me; ?>')" tabindex="0">
					Procesar
				</a>
				<a class="btn btn-default" tabindex="0">
					Cancelar
				</a>                
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
		<?php
		?>
			<script>
				$( "#pie_p" ).html('<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>'+
				'<a class="btn btn-info pull-right" id=\'proce\' onclick="guardac1(event,\'<?php echo $me; ?>\',\'ruc\',\'<?php echo $me; ?>\')"'+ 
				'onkeyup="guardac1(event,\'<?php echo $me; ?>\',\'ruc\',\'<?php echo $me; ?>\')" tabindex="0">Procesar</a>');
			</script>
		<?php
		$sql="SELECT * ". 
		   "FROM Catalogo_Productos
			WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv like 'MS.%'
			ORDER BY Codigo_Inv";
		
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
	}
	//guardar cliente
	if($cl=='cli2')
	{
		$me=$_POST['me'];
		$cli=$_POST['cli'];
		$telefono=$_POST['telefono'];
		$codigoc=$_POST['codigoc'];
		$nombrec=$_POST['nombrec'];
		$email=$_POST['email'];
		$direccion=$_POST['direccion'];
		$nv=$_POST['nv'];
		$naciona=$_POST['naciona'];
		$prov=$_POST['prov'];
		$ciu=$_POST['ciu'];
		$TC=$_POST['TC'];
		$grupo=$_POST['grupo'];
		
		/*
		 SetFields AdoListCtas, "T", T
		  SetFields AdoListCtas, "Codigo", Codigo
		  SetFields AdoListCtas, "Cliente", TxtApellidosS
		  SetFields AdoListCtas, "CI_RUC", TxtCI_RUC
		  SetFields AdoListCtas, "Direccion", TxtDirS
		  SetFields AdoListCtas, "Telefono", Format$(TxtTelefonoS, "000000000")
		  SetFields AdoListCtas, "DirNumero", TxtNumero
		  SetFields AdoListCtas, "Ciudad", CCiudadS
		  SetFields AdoListCtas, "Email", TxtEmail
		  SetFields AdoListCtas, "TD", TipoBenef
		  SetFields AdoListCtas, "Prov", "00"
		  SetFields AdoListCtas, "Pais", "593"
		  SetFields AdoListCtas, "Grupo", TxtGrupo
		  Select Case Modulo
			Case "FACTURACION", "FARMACIA"
				 SetFields AdoListCtas, "FA", adTrue
		  End Select
		*/
		$dato[0]['campo']='T';
		$dato[0]['dato']='N';
		$dato[1]['campo']='Codigo';
		$dato[1]['dato']=$codigoc;
		$dato[2]['campo']='Cliente';
		$dato[2]['dato']=$nombrec;
		$dato[3]['campo']='CI_RUC';
		$dato[3]['dato']=$cli;
		$dato[4]['campo']='Direccion';
		$dato[4]['dato']=$direccion;
		$dato[5]['campo']='Telefono';
		$dato[5]['dato']=$telefono;
		$dato[6]['campo']='DirNumero';
		$dato[6]['dato']=$nv;
		$dato[7]['campo']='Email';
		$dato[7]['dato']=$email;
		$dato[8]['campo']='TD';
		$dato[8]['dato']=$TC;
		$dato[9]['campo']='CodigoU';
		$dato[9]['dato']=$_SESSION['INGRESO']['CodigoU'];
		$dato[10]['campo']='Prov';
		$dato[10]['dato']='00';
		$dato[11]['campo']='Pais';
		$dato[11]['dato']='593';
		$dato[12]['campo']='Grupo';
		$dato[12]['dato']=$grupo;
		//facturacion
		if($_SESSION['INGRESO']['modulo_']=='02')
		{
			$dato[13]['campo']='FA';
			$dato[13]['dato']=1;
		}
		
		insert_generico("Clientes",$dato);
		
		//echo $me.' '.$cl.' '.$telefono.' '.$codigoc.' '.$nombrec.' '.$email.' '.$direccion.' '.$nv.' '.$naciona.' '.$prov.' '.$ciu.' '.$TC.' '.$_SESSION['INGRESO']['modulo_'];
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion para facturar y prefacturar
function fac($cl=null)
{
	$cid=cone_ajaxSQL();
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	if($cl=='pre1')
	{
		$me=$_POST['me'];
		$nom=$_POST['nom'];
		?>
			<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Datos Cliente</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
              <div class="box-body">
                <div class="form-group">
					<label for="nombrec" class="col-sm-2 control-label">Clientes</label>

					<div class="col-sm-10">
						<input type="text" class="form-control" id="nombrec" name="nombrec" placeholder="Razon social" 
						onkeyup="buscarCli(this,'<?php echo $me; ?>','<?php echo $nom; ?>')" tabindex="0">
					
					</div>
                </div>
				<div class="form-group" id="cbeneficiario1" style='display:none;' >
					<label for="nombrec" id="lbeneficiario1" style='display:none;' class="col-sm-2 control-label"></label>
					<div class="col-sm-10">
						<select id="beneficiario1" name='beneficiario1' class="form-control" style='display:none;' onchange="seleOn('beneficiario1','<?php echo $me; ?>')" ></select>
						<div id="bener">
						</div>
						<input type="hidden" name="beneficiario2" id="beneficiario2" value='' />
					</div>
                </div>
                <div class="form-group">
                  <label for="ruc" class="col-sm-2 control-label">RUC</label>

                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="ruc" name="ruc" placeholder="ruc"  value='.' onfocus="seleFo('ruc','<?php echo $me; ?>')" tabindex="0">
                  </div>
                </div>
				<div class="form-group">
                  <label for="email" class="col-sm-2 control-label">Email</label>

                  <div class="col-sm-10">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" tabindex="0">
                  </div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<a class="btn btn-info pull-right" id='proce' onclick="prefact1(event,'<?php echo $me; ?>','ruc','<?php echo $nom; ?>')" 
				onkeyup="prefact1(event,'<?php echo $me; ?>','ruc','<?php echo $nom; ?>')" tabindex="0">
					Procesar
				</a>
				<a class="btn btn-default" tabindex="0">
					Cancelar
				</a>                
              </div>
              <!-- /.box-footer -->
            </form>
			
          </div>
		<?php
		?>
			<script>
				$( "#pie_p" ).html('<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>'+
				'<a class="btn btn-info pull-right" id=\'proce\' onclick="prefact1(event,\'<?php echo $me; ?>\',\'ruc\')"'+
				'onkeyup="prefact1(event,\'<?php echo $me; ?>\',\'ruc\')" tabindex="0"> Procesar </a>');
			</script>
		<?php
		$sql="SELECT * ". 
		   "FROM Catalogo_Productos
			WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv like 'MS.%'
			ORDER BY Codigo_Inv";
		
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion para mostrar pedidos
function ped2($cl=null)
{
	$cid=cone_ajaxSQL();
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	if($cl=='ped2')
	{
		//echo $cuen.' ------------------------- ';
		
			?>
			<ul class="todo-list">
				<li>
					<table>
						<tr>
							<td>
								<span class="handle">
								
								</span>
							</td>
							<td width="10%">
								<span class="text">
									<?php echo 'Mesa'; ?>
								</span>
							</td>
							<td width="30%">
								<span class="text">
									<?php echo 'Producto'; ?>
								</span>
							</td>
							<td width="25%">
								<span class="text">
									<?php echo 'Cantidad '; ?>
								</span>
							</td>
							<td width="25%">
								<span class="text">
									<?php echo 'Observacion '; ?>
								</span>
							</td>
							<td width="10%">
								<span class="text">
										<?php echo 'Accion'; ?>
								</span>
							</td>
						</tr>
					</table>
				</li>
			
			<?php
				$sql="select * ". 
				   "FROM Asiento_F
					WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
					AND (Estado='R')
					ORDER BY CODIGO";
				
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
				$total=0;
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$Result[$i]['CODIGO']=$row[0];
					$Result[$i]['PRODUCTO']=$row[3];
					$Result[$i]['CANT']=$row[1];
					$Result[$i]['PRECIO']=$row[4];
					$Result[$i]['A_No']=$row[34];
					$Result[$i]['HABIT']=$row[23];
					$Result[$i]['RUTA']=$row[24];
					$total=$total+($Result[$i]['PRECIO']*$Result[$i]['CANT']);
					
					?>
					<li>
						<table>
							<tr>
								<td>
									<span class="handle">
									</span>
								</td>
								<td width="10%">
									<span class="text">
										<?php echo $Result[$i]['HABIT']; ?>
									</span>
								</td>
								<td width="30%">
									<span class="text">
										<?php echo $Result[$i]['PRODUCTO']; ?>
										 <small class="label label-danger"><i class="fa fa-clock-o"></i> Pendiente</small>
									</span>
								</td>
								<td width="25%">
									<span class="text">
										<?php echo $Result[$i]['CANT']; ?>
									</span>
								</td>
								<td width="25%">
									<span class="text">
										<?php echo $Result[$i]['RUTA']; ?>
									</span>
								</td>
								<td width="10%">
									<span class="text">
											<a onclick="enviar('<?php echo $Result[$i]['A_No']; ?>','<?php echo $Result[$i]['HABIT']; ?>',
											'<?php echo $Result[$i]['PRODUCTO']; ?>','<?php echo $Result[$i]['CANT']; ?>');"><i class="fa fa-edit"></i></a>
									</span>
								</td>
							</tr>
						</table>
					</li>
					
					<?php
				}
				/*for($i=0;$i<($cuen);$i++)
				{
					if($_SESSION['APPR']['PED'][$i]['MESA']!='-')
					{
					?>
						<li>
							<table>
								<tr>
									<td>
										<span class="handle">
										</span>
									</td>
									<td width="10%">
										<span class="text">
											<?php echo $_SESSION['APPR']['PED'][$i]['MESA']; ?>
										</span>
									</td>
									<td width="30%">
										<span class="text">
											<?php echo $_SESSION['APPR']['PED'][$i]['PROD']; ?>
											 <small class="label label-danger"><i class="fa fa-clock-o"></i> Pendiente</small>
										</span>
									</td>
									<td width="25%">
										<span class="text">
											<?php echo $_SESSION['APPR']['PED'][$i]['CANT']; ?>
										</span>
									</td>
									<td width="25%">
										<span class="text">
											<?php echo 'Observacion '; ?>
										</span>
									</td>
									<td width="10%">
										<span class="text">
												<i class="fa fa-edit"></i>
										</span>
									</td>
								</tr>
							</table>
						</li>
				<?php
					}
				}*/
		/*if(isset($_POST['cod_p']))
		{
			$prod =  $_POST['cod_p'];
			$cant = $_POST['cant'];
			$cod = $_POST['cod'];
			$me=$_POST['me'];
			//echo count($prod).' ------------------------- ';
			for($i=0;$i<($cuen);$i++)
			{
				if($_SESSION['APPR']['PED'][$i]['MESA']==$me and 
				$_SESSION['APPR']['PED'][$i]['PROD']==$prod and $_SESSION['APPR']['PED'][$i]['CANT']==$cant 
				and $_SESSION['APPR']['PED'][$i]['COD']==$cod)
				{
					//echo "entrooooooooooooooo";
					$_SESSION['APPR']['PED'][$i]['MESA']='-';
					$_SESSION['APPR']['PED'][$i]['PROD']='-';
					$_SESSION['APPR']['PED'][$i]['CANT']='-';
					//$_SESSION['APPR']['PED'][$i]['COD']='-';
				}
			}
		}*/
		?>
		</ul>
		<?php
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion para elimiar item cocina y entregar pedido mesa
function env($cl=null)
{
	$cid=cone_ajaxSQL();
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	if($cl=='env1')
	{
		if(isset($_POST['me']))
		{
			$prod =  $_POST['prod'];
			$cant = $_POST['cant'];
			$me = $_POST['me'];
			$cod_i = $_POST['cod_i'];
			$sql="UPDATE ". 
			   " Asiento_F SET Estado='A'
				WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
				AND A_No = '".$cod_i."' AND HABIT='".$me."' 
				";
		
			//echo $sql;
			//die();
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//echo count($prod).' ------------------------- ';
			/*for($i=0;$i<($cuen);$i++)
			{
				if($_SESSION['APPR']['PED'][$i]['MESA']==$me and 
				$_SESSION['APPR']['PED'][$i]['PROD']==$prod and $_SESSION['APPR']['PED'][$i]['CANT']==$cant 
				and $_SESSION['APPR']['PED'][$i]['COD']==$cod)
				{
					//echo "entrooooooooooooooo";
					$_SESSION['APPR']['PED'][$i]['MESA']='-';
					$_SESSION['APPR']['PED'][$i]['PROD']='-';
					$_SESSION['APPR']['PED'][$i]['CANT']='-';
					//$_SESSION['APPR']['PED'][$i]['COD']='-';
				}
			}*/
		}
	}
	if($cl=='env2')
	{
		if(isset($_POST['me']))
		{
			$prod =  $_POST['prod'];
			$cant = $_POST['cant'];
			$me = $_POST['me'];
			$cod_i = $_POST['cod_i'];
			$sql="UPDATE ". 
			   " Asiento_F SET Estado='V'
				WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
				AND A_No = '".$cod_i."' AND HABIT='".$me."' 
				";
		
			//echo $sql;
			//die();
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}			
		}
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion para elimiar item
function pop3($cl=null)
{
	$cid=cone_ajaxSQL();
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	//eliminar item
	if($cl=='eli1')
	{
		if(isset($_POST['cod_p']))
		{
			$prod =  $_POST['cod_p'];
			$cant = $_POST['cant'];
			$cod = $_POST['cod'];
			$me=$_POST['me'];
			
			$sql="DELETE ". 
			   "FROM Asiento_F
				WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
				AND A_No = '".$cod."' AND HABIT='".$me."' 
				";
		
			//echo $sql;
			//die();
			$stmt = sqlsrv_query( $cid, $sql);
			if( $stmt === false)  
			{  
				 echo "Error en consulta PA.\n";  
				 die( print_r( sqlsrv_errors(), true));  
			}
			//echo count($prod).' ------------------------- ';
			/*for($i=0;$i<($cuen);$i++)
			{
				if($_SESSION['APPR']['PED'][$i]['MESA']==$me and 
				$_SESSION['APPR']['PED'][$i]['PROD']==$prod and $_SESSION['APPR']['PED'][$i]['CANT']==$cant 
				and $_SESSION['APPR']['PED'][$i]['COD']==$cod)
				{
					//echo "entrooooooooooooooo";
					$_SESSION['APPR']['PED'][$i]['MESA']='-';
					$_SESSION['APPR']['PED'][$i]['PROD']='-';
					$_SESSION['APPR']['PED'][$i]['CANT']='-';
					//$_SESSION['APPR']['PED'][$i]['COD']='-';
				}
			}*/
		}
	}
	//liberar mesa
	if($cl=='lib1')
	{
		$me=$_POST['me'];
		$sql="UPDATE ". 
		   " Catalogo_Productos SET Estado=0
			WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv = '".$me."'
			";
		
		//echo $sql;
		//die();
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta PA.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		}
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion para mostrar las mesas
function pop2($cl=null)
{
	$cid=cone_ajaxSQL();
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	if($cl=='mes2')
	{
		$sql="SELECT * ". 
		   "FROM Catalogo_Productos
			WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
			AND (Item = '".$_SESSION['INGRESO']['item']."')
			AND Codigo_Inv like 'MS.%'
			ORDER BY Codigo_Inv";
		
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
			$Result[$i]['Codigo_Inv']=$row[2];
			$Result[$i]['Producto']=$row[3];
			$Result[$i]['Detalle']=$row[4];
			$Result[$i]['Estado']=$row[64];
			if($Result[$i]['Estado']==0 OR $Result[$i]['Estado']==null)
			{
			?>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-green">
						<div class="inner" onclick="agregar('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $Result[$i]['Producto'];?>');">
							<h3 style="font-size: 20px">Disponible<sup style="font-size: 17px"></sup></h3>
							<p><?php echo $Result[$i]['Producto']; ?></p>
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a onclick="agregar('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $Result[$i]['Producto'];?>');" class="small-box-footer">ver <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			<?php
			}
			else
			{
				//buscamos productos para entregar
				$sql="select * ". 
				   "FROM Asiento_F
					WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
					AND  HABIT='".$Result[$i]['Codigo_Inv']."' AND Estado='A'
					ORDER BY CODIGO";
				
				//echo $sql;
				//die();
				$stmt1 = sqlsrv_query( $cid, $sql);
				if( $stmt1 === false)  
				{  
					 echo "Error en consulta PA.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}
				
				$ii=0;
				
				while( $row1 = sqlsrv_fetch_array( $stmt1, SQLSRV_FETCH_NUMERIC) ) 
				{
					$ii++;
				}
				if($ii==0)
				{
					$pedi="";
				}
				else
				{
					$pedi="<small class='label label-danger'><i class='fa fa-clock-o'></i>Servir</small>";
				}
			?>
				<div class="col-lg-3 col-xs-6">
					<!-- small box -->
					<div class="small-box bg-yellow">
						<div class="inner" onclick="agregar('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $Result[$i]['Producto'];?>');" >
							<!--<h3 style="font-size: 20px">Disponible<sup style="font-size: 17px"></sup></h3>-->
							<h3 style="font-size: 20px">Ocupada <sup style="font-size: 17px"><?php echo $pedi; ?></sup></h3>
							<p><?php echo $Result[$i]['Producto']; ?></p>
							
						</div>
						<div class="icon">
							<i class="ion ion-stats-bars"></i>
						</div>
						<a onclick="agregar('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $Result[$i]['Producto'];?>');" class="small-box-footer">ver <i class="fa fa-arrow-circle-right"></i></a>
					</div>
				</div>
			<?php
			}
			$i++;
		}
	}
	cerrarSQLSERVERFUN($cid);
}
//funcion pop1 para mostrar productos y pedidos de una mesa
function pop1($cl=null)
{
	$cid=cone_ajaxSQL();
	//$_POST['TP']='CD';
	//$_POST['MesNo']=0;
	if($cl==null)
	{
		$cl=$_POST['cl'];
	}
	$cid=cone_ajaxSQL();
	if($cl=='mes1' and isset( $_POST['me']) and isset($_POST['nom']))
	{
		?>
		<!-- TO DO List -->
		<h3 class="box-title">
			<?php echo $_POST['nom']; ?>
		</h3>
		<div class="box-body">
			<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
			<ul class="todo-list">
			<?php
				$total=0;
				?>
					<li>
						<table>
							<tr>
								<td>
									<span class="handle">
									
									</span>
								</td>
								<td width="30%">
									<span class="text">
										<?php echo 'Producto'; ?>
									</span>
								</td>
								<td width="20%">
									<span class="text">
										<?php echo 'Cantidad '; ?>
									</span>
								</td>
								<td width="20%">
									<span class="text">
										<?php echo 'Precio '; ?>
									</span>
								</td>
								<td width="20%">
									<span class="text">
										<?php echo 'Status '; ?>
									</span>
								</td>
								<td width="10%">
									<span class="text">
											<?php echo 'Total'; ?>
									</span>
								</td>
							</tr>
					  </table>
					</li>
				<?php
				$sql="select * ". 
				   "FROM Asiento_F
					WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
					AND  HABIT='".$_POST['me']."' 
					ORDER BY CODIGO";
				
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
				$total=0;
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					$Result[$i]['CODIGO']=$row[0];
					$Result[$i]['PRODUCTO']=$row[3];
					$Result[$i]['CANT']=$row[1];
					$Result[$i]['PRECIO']=$row[4];
					$Result[$i]['A_No']=$row[34];
					$Result[$i]['Estado']=$row[52];
					$total=$total+($Result[$i]['PRECIO']*$Result[$i]['CANT']);
					
					?>
					<li>
						<table>
							<tr>
								<td>
									<span class="handle">
								
									</span>
								</td>
								<td width="30%">
									<span class="text">
										<?php echo $Result[$i]['PRODUCTO']; ?>
									</span>
								</td>
								<td width="20%">
									<span class="text">
										<?php echo $Result[$i]['CANT']; ?>
									</span>
								</td>
								<td width="20%">
									<span class="text">
										<?php echo $Result[$i]['PRECIO']; ?>
									</span>
								</td>
								<td width="20%">
									<span class="text">
										<?php 
											if($Result[$i]['Estado']=='R')
											{
												?>
												<small class="label label-danger"><i class="fa fa-clock-o"></i>Pendiente</small>
												<?php
											}
											if($Result[$i]['Estado']=='A')
											{
												?>
												<a onclick="entregar('<?php echo $Result[$i]['A_No']; ?>',
												'<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
												'<?php echo $Result[$i]['PRODUCTO']; ?>','<?php echo $Result[$i]['CANT']; ?>');">
													<small class="label label-warning"><i class="fa fa-clock-o"></i>Por entregar</small>
												</a>
												<?php
											}
											if($Result[$i]['Estado']=='V')
											{
												?>
												<small class="label label-success"><i class="fa fa-clock-o"></i>Despachado</small>
												<?php
											}
										?>
									</span>
								</td>
								<td width="10%">
									<span class="text">
											<?php echo ($Result[$i]['PRECIO']*$Result[$i]['CANT']); ?>
									</span>
									<div class="tools">
										<?php
											if($Result[$i]['Estado']!='V')
											{
										?>
												<a onclick="eliminarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
												'<?php echo $Result[$i]['CODIGO'];?>',
												'<?php echo $Result[$i]['CANT']; ?>',
												'<?php echo $Result[$i]['A_No'];?>');">
												<i class="fa fa-trash-o"></i>
										<?php
											}
										?>
									</div>
								</td>
							</tr>
						</table>
					</li>
					<?php
				}
			?>
				<li>
					  <span class="handle">
						<i class="fa fa-ellipsis-v"></i>
						<i class="fa fa-ellipsis-v"></i>
					  </span>
					  <!--<span class="text">
						<?php echo $nombre.' '.$_SESSION['APPR']['PED'][$i]['CANT']; ?> 
						$ <?php echo ($precio*$_SESSION['APPR']['PED'][$i]['CANT']); ?></span>-->
					<div class="tools" id='total_' style='display:block;'>
						<?php echo 'TOTAL $ '.$total; ?>
					</div>
				   
				</li>
			</ul>
		</div>
		<form action="#" class="credit-card-div" id='formu1'>
			<div class="box box-primary">
				<div class="box-header">
					<input type="hidden" id='mesa' name='mesa'  value='<?php echo $_POST['me']; ?>' />
					<!--<h3 class="box-title">Seleccionar productos</h3>-->
					<h3 class="box-title">
						<table>
							<tr>
								<td width='80%'>
									<div class="input-group input-group-sm col-sm-5">
										<input type="text" class="form-control" placeholder="Seleccionar productos" id='buscar' name='buscar'>
											<span class="input-group-btn">
												<button type="button" class="btn btn-info btn-flat" 
												onclick="buscarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>');"><i class="ion ion-clipboard"></i></button>
												<i class="fa fa-fw fa-arrow-circle-right"></i><button type="button" class="btn btn-info btn-flat" 
												onclick="verpedido('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>');">Ver Pedido</button>
											</span>
									</div>
								</td>
								<td width='20%' align='left'>
									<div class="box-footer clearfix no-border">
										<button type="button" class="btn btn-default pull-right" onclick="agregar_('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>')"><i class="fa fa-plus"></i>Agregar Producto</button>
									</div>
								</td>
							</tr>
						</table>
					</h3>
					
					<!--<div class="box-tools pull-right">
						<ul class="pagination pagination-sm inline">
							<li><a href="#">&laquo;</a></li>
							<li><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
							<li><a href="#">&raquo;</a></li>
						</ul>
					</div>-->
				</div>
				<!-- /.box-header -->
				<!-- /.box-body -->
				
				<div class="box-body">
					<!-- See dist/js/pages/dashboard.js to activate the todoList plugin 
					select * FROM            Catalogo_Productos
					WHERE        (Periodo = '.') AND (Item = '001')
					and tc='P' and len(Cta_Inventario)>2 and len(Cta_Costo_Venta)>2
					ORDER BY Codigo_Inv
					-->
					<ul class="todo-list">
					<?php
						$sql="SELECT * ". 
						   "FROM Catalogo_Productos
							WHERE (LEN(Cta_Inventario) > 2) AND (LEN(Cta_Costo_Venta) > 2) AND (TC = 'P') AND 
							(Periodo = '".$_SESSION['INGRESO']['periodo']."') 
							AND (Item = '".$_SESSION['INGRESO']['item']."')
							ORDER BY Codigo_Inv";
						
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
							$Result[$i]['Codigo_Inv']=$row[2];
							$Result[$i]['Producto']=$row[3];
							$Result[$i]['PVP']=$row[9];
							?>
								<li>
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
								
									<input type="checkbox" name='selec_p[]' 
									value="<?php echo $Result[$i]['Codigo_Inv']; ?>" onclick="selec_p('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $i; ?>')">
									<span class="text">
										<label id="lcanti_<?php echo $Result[$i]['Codigo_Inv']; ?>" style='display:none;'>Cantidad</label>
										<input type="text" class="xs" id="canti_<?php echo $Result[$i]['Codigo_Inv']; ?>" 
										name='canti_<?php echo $Result[$i]['Codigo_Inv']; ?>' 
										placeholder="cantidad" value='1' maxlength='30' size='5' style='display:none;'
										onkeyup="c_total('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $Result[$i]['PVP']; ?>','<?php echo $i; ?>')">
									</span>
									
									<span class="text">
										<label id="lobs_<?php echo $Result[$i]['Codigo_Inv']; ?>" style='display:none;'>Observacion</label>
										<input type="text" class="xs" id="obs_<?php echo $Result[$i]['Codigo_Inv']; ?>" 
										name='obs_<?php echo $Result[$i]['Codigo_Inv']; ?>' 
										placeholder="Observacion" value='.' maxlength='50' size='5' style='display:none;'
										>
									</span>
									
									<span class="text">
										<?php echo $Result[$i]['Producto']; ?> - 
										<span style='color: #dd4b39;'> $ <?php echo $Result[$i]['PVP']; ?></span>
									</span>
								
									<span class="text">
										<small class="label label-success"><i class="fa fa-clock-o"></i> Disponible</small>
									</span>
								
									<div class="tools"  id="total_<?php echo $i; ?>" >
										<?php echo 'TOTAL $ '.$Result[$i]['PVP']; ?>
									</div> 
											
									<!--<table>
										<tr>
											<td width="5%">
												<span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												</span>
											</td>
											<td width="10%">
												<input type="checkbox" name='selec_p[]' 
												value="<?php echo $Result[$i]['Codigo_Inv']; ?>" onclick="selec_p('<?php echo $Result[$i]['Codigo_Inv']; ?>')">
												<span class="text">
													<input type="text" class="xs" id="canti_<?php echo $Result[$i]['Codigo_Inv']; ?>" 
													name='canti_<?php echo $Result[$i]['Codigo_Inv']; ?>' 
													placeholder="cantidad" value='1' maxlength='30' size='5' style='display:none;'
													onkeyup="c_total('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $Result[$i]['PVP']; ?>','<?php echo $i; ?>')">
												</span>
											</td>
											<td width="30%">
												<span class="text">
													<?php echo $Result[$i]['Producto']; ?> - 
													<span style='color: #dd4b39;'> $ <?php echo $Result[$i]['PVP']; ?></span>
												</span>
											</td>
											<td width="10%">
												<span class="text">
													<small class="label label-success"><i class="fa fa-clock-o"></i> Disponible</small>
												</span>
											</td>
											<td width="15%">
												<div class="tools"  id="total_<?php echo $i; ?>" >
													<?php echo 'TOTAL $ '.$Result[$i]['PVP']; ?>
												</div> 
											</td>
										</tr>
									</table>-->
								</li>
								<?php
							$i++;
						}
						/*for($i=0;$i<count($_SESSION['APPR']['PROD']);$i++)
						{
							if($_SESSION['APPR']['PROD'][$i]['disp']==1)
							{
								?>
								<li>
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<input type="checkbox" name='selec_p[]' 
									value="<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>" onclick="selec_p('<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>')">
								  
									<span class="text">
										<input type="text" class="xs" id="canti_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>" 
										name='canti_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>' 
										placeholder="cantidad" value='1' maxlength='30' size='5' style='display:none;'
										onkeyup="c_total('<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>','<?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?>')">
									</span>
									<span class="text">
										<?php echo $_SESSION['APPR']['PROD'][$i]['nombre']; ?> - 
										<span style='color: #dd4b39;'> $ <?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?></span>
									</span>
									<span class="text">
										<small class="label label-success"><i class="fa fa-clock-o"></i> Disponible</small>
									</span>
								    <div class="tools"  id="total_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>" >
										<?php echo 'TOTAL $ '.$_SESSION['APPR']['PROD'][$i]['precio']; ?>
									</div> 
								</li>
								<?php
							}
							if($_SESSION['APPR']['PROD'][$i]['disp']==0)
							{
								?>
								<li>
								  <!-- drag handle -->
								  <span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									  </span>
								  <!-- checkbox -->
								  <input type="checkbox" value="" disabled>
								  <!-- todo text -->
								  <span class="text">  <span class="text"><?php echo $_SESSION['APPR']['PROD'][$i]['nombre']; ?><br>$<?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?></span></span>
								  <!-- Emphasis label -->
								  <small class="label label-danger"><i class="fa fa-clock-o"></i> Agotado</small>
								  <!-- General tools such as edit or delete-->
								 <!-- <div class="tools">
									<i class="fa fa-edit"></i>
									<i class="fa fa-trash-o"></i>
								  </div>-->
								</li>
								<?php
							}
						}*/
					?>
					
					</ul>
				</div>
				
			</div>
		</form>
		<?php
		?>
			<script>
				$( "#pie_p" ).html('<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>'+
				'<button type="button" class="btn btn-default pull-right" onclick="agregar_(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\')">'+
				'<i class="fa fa-plus"></i>Agregar Producto</button>'+
				'<button type="button" class="btn btn-info btn-flat" onclick="verpedido(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\');">Ver Pedido</button>');
			</script>
		<?php
	}
	else
	{
		if($cl=='mes2' and isset( $_POST['me']) and isset($_POST['nom']))
		{
			?>
			<!-- TO DO List -->
			<h3 class="box-title">
				<?php echo $_POST['nom']; ?>
			</h3>
			<div class="box-body">
				<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
				<ul class="todo-list">
				<?php
					$total=0;
					?>
						<li>
							<table>
								<tr>
									<td>
										<span class="handle">
										
										</span>
									</td>
									<td width="30%">
										<span class="text">
											<?php echo 'Producto'; ?>
										</span>
									</td>
									<td width="20%">
										<span class="text">
											<?php echo 'Cantidad '; ?>
										</span>
									</td>
									<td width="20%">
										<span class="text">
											<?php echo 'Precio '; ?>
										</span>
									</td>
									<td width="20%">
										<span class="text">
											<?php echo 'Status '; ?>
										</span>
									</td>
									<td width="10%">
										<span class="text">
												<?php echo 'Total'; ?>
										</span>
									</td>
								</tr>
						  </table>
						</li>
					<?php
					$sql="select * ". 
					   "FROM Asiento_F
						WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
						AND  HABIT='".$_POST['me']."' 
						ORDER BY CODIGO";
					
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
					$total=0;
					while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
					{
						$Result[$i]['CODIGO']=$row[0];
						$Result[$i]['PRODUCTO']=$row[3];
						$Result[$i]['CANT']=$row[1];
						$Result[$i]['PRECIO']=$row[4];
						$Result[$i]['A_No']=$row[34];
						$Result[$i]['Estado']=$row[52];
						$total=$total+($Result[$i]['PRECIO']*$Result[$i]['CANT']);
						
						?>
						<li>
							<table>
								<tr>
									<td>
										<span class="handle">
									
										</span>
									</td>
									<td width="30%">
										<span class="text">
											<?php echo $Result[$i]['PRODUCTO']; ?>
										</span>
									</td>
									<td width="20%">
										<span class="text">
											<?php echo $Result[$i]['CANT']; ?>
										</span>
									</td>
									<td width="20%">
										<span class="text">
											<?php echo $Result[$i]['PRECIO']; ?>
										</span>
									</td>
									<td width="20%">
										<span class="text">
											<?php 
												if($Result[$i]['Estado']=='R')
												{
													?>
													<small class="label label-danger"><i class="fa fa-clock-o"></i>Pendiente</small>
													<?php
												}
												if($Result[$i]['Estado']=='A')
												{
													?>
													<a onclick="entregar('<?php echo $Result[$i]['A_No']; ?>',
													'<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
													'<?php echo $Result[$i]['PRODUCTO']; ?>','<?php echo $Result[$i]['CANT']; ?>');">
														<small class="label label-warning"><i class="fa fa-clock-o"></i>Por entregar</small>
													</a>
													<?php
												}
												if($Result[$i]['Estado']=='V')
												{
													?>
													<small class="label label-success"><i class="fa fa-clock-o"></i>Despachado</small>
													<?php
												}
											?>
										</span>
									</td>
									<td width="10%">
										<span class="text">
												<?php echo ($Result[$i]['PRECIO']*$Result[$i]['CANT']); ?>
										</span>
										<div class="tools">
											<?php
												if($Result[$i]['Estado']!='V')
												{
											?>
													<a onclick="eliminarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
													'<?php echo $Result[$i]['CODIGO'];?>',
													'<?php echo $Result[$i]['CANT']; ?>',
													'<?php echo $Result[$i]['A_No'];?>');">
													<i class="fa fa-trash-o"></i>
											<?php
												}
											?>
										</div>
									</td>
								</tr>
							</table>
						</li>
						<?php
					}
				?>
					<li>
						  <span class="handle">
							<i class="fa fa-ellipsis-v"></i>
							<i class="fa fa-ellipsis-v"></i>
						  </span>
						  <!--<span class="text">
							<?php echo $nombre.' '.$_SESSION['APPR']['PED'][$i]['CANT']; ?> 
							$ <?php echo ($precio*$_SESSION['APPR']['PED'][$i]['CANT']); ?></span>-->
						<div class="tools" id='total_' style='display:block;'>
							<?php echo 'TOTAL $ '.$total; ?>
						</div>
					   
					</li>
				</ul>
			</div>
			<form action="#" class="credit-card-div" id='formu1'>
				<div class="box box-primary">
					<div class="box-header">
						<input type="hidden" id='mesa' name='mesa'  value='<?php echo $_POST['me']; ?>' />
						<!--<h3 class="box-title">Seleccionar productos</h3>-->
						<h3 class="box-title">
							<table>
								<tr>
									<td width='80%'>
										<div class="input-group input-group-sm col-sm-5">
											<input type="text" class="form-control" placeholder="Seleccionar productos" id='buscar' name='buscar'>
												<span class="input-group-btn">
													<button type="button" class="btn btn-info btn-flat" 
													onclick="buscarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>');"><i class="ion ion-clipboard"></i></button>
													<i class="fa fa-fw fa-arrow-circle-right"></i><button type="button" class="btn btn-info btn-flat" 
													onclick="verpedido('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>');">Ver Pedido</button>
												</span>
										</div>
									</td>
									<td width='20%' align='left'>
										<div class="box-footer clearfix no-border">
											<button type="button" class="btn btn-default pull-right" onclick="agregar_('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>')"><i class="fa fa-plus"></i>Agregar Producto</button>
										</div>
									</td>
								</tr>
							</table>
						</h3>
						<!--<div class="box-tools pull-right">
							<ul class="pagination pagination-sm inline">
								<li><a href="#">&laquo;</a></li>
								<li><a href="#">1</a></li>
								<li><a href="#">2</a></li>
								<li><a href="#">3</a></li>
								<li><a href="#">&raquo;</a></li>
							</ul>
						</div>-->
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
						<ul class="todo-list">
						<?php
							$sql="SELECT * ". 
							   "FROM Catalogo_Productos
								WHERE (LEN(Cta_Inventario) > 2) AND (LEN(Cta_Costo_Venta) > 2) AND (TC = 'P') AND 
								(Periodo = '".$_SESSION['INGRESO']['periodo']."') 
								AND (Item = '".$_SESSION['INGRESO']['item']."')
								AND  Producto LIKE '%".$_POST['bus']."%'
								ORDER BY Codigo_Inv";
							
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
								$Result[$i]['Codigo_Inv']=$row[2];
								$Result[$i]['Producto']=$row[3];
								$Result[$i]['PVP']=$row[9];
								?>
								<li>
									<span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									</span>
									<input type="checkbox" name='selec_p[]' 
									value="<?php echo $Result[$i]['Codigo_Inv']; ?>" onclick="selec_p('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $i; ?>')">
								  
									<span class="text">
										<label id="lcanti_<?php echo $Result[$i]['Codigo_Inv']; ?>" style='display:none;'>Cantidad</label>
										<input type="text" class="xs" id="canti_<?php echo $Result[$i]['Codigo_Inv']; ?>" 
										name='canti_<?php echo $Result[$i]['Codigo_Inv']; ?>' 
										placeholder="cantidad" value='1' maxlength='30' size='5' style='display:none;'
										onkeyup="c_total('<?php echo $Result[$i]['Codigo_Inv']; ?>','<?php echo $Result[$i]['PVP']; ?>','<?php echo $i; ?>')">
									</span>
									
									<span class="text">
										<label id="lobs_<?php echo $Result[$i]['Codigo_Inv']; ?>" style='display:none;'>Observacion</label>
										<input type="text" class="xs" id="obs_<?php echo $Result[$i]['Codigo_Inv']; ?>" 
										name='obs_<?php echo $Result[$i]['Codigo_Inv']; ?>' 
										placeholder="Observacion" value='.' maxlength='50' size='5' style='display:none;'
										>
									</span>
									<span class="text">
										<?php echo $Result[$i]['Producto']; ?> - 
										<span style='color: #dd4b39;'> $ <?php echo $Result[$i]['PVP']; ?></span>
									</span>
									<span class="text">
										<small class="label label-success"><i class="fa fa-clock-o"></i> Disponible</small>
									</span>
								    <div class="tools"  id="total_<?php echo $i; ?>" >
										<?php echo 'TOTAL $ '.$Result[$i]['PVP']; ?>
									</div> 
								</li>
								<?php
								$i++;
							}
							
							/*for($i=0;$i<count($_SESSION['APPR']['PROD']);$i++)
							{
								if($_POST['bus']==null or $_POST['bus']=='')
								{
									if($_SESSION['APPR']['PROD'][$i]['disp']==1)
									{
										?>
										<li>
											<span class="handle">
												<i class="fa fa-ellipsis-v"></i>
												<i class="fa fa-ellipsis-v"></i>
											</span>
											<input type="checkbox" name='selec_p[]' 
											value="<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>" onclick="selec_p('<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>')">
											<span class="text">
												<input type="text" class="xs" id="canti_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>" 
												name='canti_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>' 
												placeholder="cantidad" value='1' maxlength='30' size='5' style='display:none;'
												onkeyup="c_total('<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>','<?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?>')">
											</span>
											<span class="text">
												<?php echo $_SESSION['APPR']['PROD'][$i]['nombre']; ?> -
												<span style='color: #dd4b39;'> $ <?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?></span>
											</span>
											<small class="label label-success"><i class="fa fa-clock-o"></i> Disponible</small>
											<div class="tools" id='total_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>'>
												<?php echo 'TOTAL $ '.$_SESSION['APPR']['PROD'][$i]['precio']; ?>
											</div>
										</li>
										<?php
									}
									if($_SESSION['APPR']['PROD'][$i]['disp']==0)
									{
										?>
										<li>
										  <!-- drag handle -->
											<span class="handle">
												<i class="fa fa-ellipsis-v"></i>
												<i class="fa fa-ellipsis-v"></i>
											</span>
										  <!-- checkbox -->
										  <input type="checkbox" value="" disabled>
										  <!-- todo text -->
										  <span class="text">  <span class="text"><?php echo $_SESSION['APPR']['PROD'][$i]['nombre']; ?><br>$<?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?></span></span>
										  <!-- Emphasis label -->
										  <small class="label label-danger"><i class="fa fa-clock-o"></i> Agotado</small>
										  <!-- General tools such as edit or delete-->
										 <!-- <div class="tools">
											<i class="fa fa-edit"></i>
											<i class="fa fa-trash-o"></i>
										  </div>-->
										</li>
										<?php
									}
								}
								if($_SESSION['APPR']['PROD'][$i]['disp']==1 and ($_SESSION['APPR']['PROD'][$i]['nombre']==$_POST['bus']))
								{
									?>
									<li>
										<span class="handle">
											<i class="fa fa-ellipsis-v"></i>
											<i class="fa fa-ellipsis-v"></i>
										</span>
										<input type="checkbox" name='selec_p[]'
										value="<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>" onclick="selec_p('<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>')">
										<span class="text">
											<input type="text" class="xs" id="canti_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>" 
											name='canti_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>' 
											placeholder="cantidad" value='1' maxlength='30' size='5' style='display:none;'
												onkeyup="c_total('<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>','<?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?>')">
										</span>
										<span class="text">
											<?php echo $_SESSION['APPR']['PROD'][$i]['nombre']; ?> -
											<span style='color: #dd4b39;'> $ <?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?></span>
										</span>
										<small class="label label-success"><i class="fa fa-clock-o"></i> Disponible</small>
										<div class="tools" id='total_<?php echo $_SESSION['APPR']['PROD'][$i]['codigo']; ?>'>
											<?php echo 'TOTAL $ '.$_SESSION['APPR']['PROD'][$i]['precio']; ?>
										</div>
									</li>
									<?php
								}
								if($_SESSION['APPR']['PROD'][$i]['disp']==0 and ($_SESSION['APPR']['PROD'][$i]['nombre']==$_POST['bus']))
								{
									?>
									<li>
									  <!-- drag handle -->
										<span class="handle">
											<i class="fa fa-ellipsis-v"></i>
											<i class="fa fa-ellipsis-v"></i>
										</span>
									  <!-- checkbox -->
									  <input type="checkbox" value="" disabled>
									  <!-- todo text -->
									  <span class="text">  <span class="text"><?php echo $_SESSION['APPR']['PROD'][$i]['nombre']; ?><br>$<?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?></span></span>
									  <!-- Emphasis label -->
									  <small class="label label-danger"><i class="fa fa-clock-o"></i> Agotado</small>
									  <!-- General tools such as edit or delete-->
									 <!-- <div class="tools">
										<i class="fa fa-edit"></i>
										<i class="fa fa-trash-o"></i>
									  </div>-->
									</li>
									<?php
								}
							}*/
						?>
						
						</ul>
					</div>
					
				</div>
			</form>
			
			<?php
			?>
				<script>
					$( "#pie_p" ).html('<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>'+
					'<button type="button" class="btn btn-default pull-right" onclick="agregar_(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\')">'+
					'<i class="fa fa-plus"></i>Agregar Producto</button>'+
					'<button type="button" class="btn btn-info btn-flat" onclick="verpedido(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\');">Ver Pedido</button>');
				</script>
			<?php
		}
		else
		{
			if($cl=='pep1' and isset( $_POST['me']) and isset($_POST['nom']))
			{
				?>
				<form action="#" class="credit-card-div" id='formu1'>
					<!-- pedidos por mesa 3.1.03.01-->
					<div class="box box-primary">
						<div class="box-header">
							<input type="hidden" id='mesa' name='mesa'  value='<?php echo $_POST['me']; ?>' />
							<!--<h3 class="box-title">Seleccionar productos</h3>-->
							<h3 class="box-title">
								<?php echo 'Consumo '.$_POST['nom']; ?>
								<span class="input-group-btn">
										<input type="hidden" class="form-control" placeholder="Seleccionar productos" id='buscar' name='buscar' value='<?php echo $_POST['bus'];?>'>
										<button type="button" class="btn btn-info btn-flat" 
										onclick="buscarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>');">Ir a Productos</button>
									</span>
							</h3>
							<?php
							if(isset( $_POST['nombrec']) and isset( $_POST['ruc']))
							{
								$sql="SELECT Item, Concepto, Numero, Periodo, ID
									FROM Codigos
									WHERE (Item = '".$_SESSION['INGRESO']['item']."') AND 
									(Periodo = '".$_SESSION['INGRESO']['periodo']."') AND 
									(Concepto = 'FA_SERIE_001005')";
								
								
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
								$total=0;
								while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
								{
									$numero=$row[2];	
								}
							?>
							<input type="hidden" id='nombrec' name='nombrec' value='<?php echo $_POST['nombrec'];?>'>
							<input type="hidden" id='ruc' name='ruc' value='<?php echo $_POST['ruc'];?>'>
							<input type="hidden" id='email' name='email' value='<?php echo $_POST['email'];?>'>
							<input type="hidden" id='ser' name='ser' value='<?php echo 'FA_SERIE_001005';?>'>
							<input type="hidden" id='n_fac' name='n_fac' value='<?php echo $numero;?>'>
							<ul class="todo-list">
								<li>
								<table>
									<tr>
										<td >
											<b>Datos del cliente</b>
										</td>
									<tr>
									<tr>
										<td>
											<b>Razon social: </b><?php echo $_POST['nombrec']; ?>
										</td>
									</tr>
									<tr>
										<td>
											<b>Ruc: </b><?php echo $_POST['ruc']; ?>
										</td>
									</tr>
									<tr>
										<td>
											<b>Email: </b><?php echo $_POST['email']; ?>
										</td>
									</tr>
									<tr>
										<td>
											<b>Serie: </b><?php echo "FA_SERIE_001005"; ?> <b> Numero: </b><?php echo $numero; ?>
										</td>
									</tr>
									<?php
										//consultar los abonos
										
									?>
									<tr>
										<td>
											<b>Tipo Pago: </b>
											<select class="xs" name="abo" id='abo' onChange="mos_ocu('texto_a','abo')">
												<option value='0'>Seleccionar</option>
												<?php select_option_a('Catalogo_Cuentas','Codigo,TC',"Cuenta",
												" TC IN ('BA','CJ','CP','C','P','TJ','CF','CI','CB') 
												AND DG = 'D' AND Item = '".$_SESSION['INGRESO']['item']."' 
												AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  "); ?>
											</select>
										</td>
									</tr>
									<tr id='texto_a' style='display:none;'>
										<td>
											<b>Comprobante:</b>
											<input type="text" class="xs" id="compro_a" name='compro_a' 
											placeholder="Numero compro o cheque" value='.' maxlength='45' size='5' >
										</td>
										
									</tr>
									<tr>
										<td>
											<b>Monto:</b>
											<input type="text" class="xs" id="monto_a" name='monto_a' 
											placeholder="Monto" value='1' maxlength='30' size='5' >
										</td>
										<td>
											<button type="button" class="btn btn-info btn-flat btn-sm" 
										onclick="agregarabo('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>','<?php echo $_POST['bus'];?>');">Agregar</button>
										</td>
									</tr>
								</table>
								</li>
							</ul>
							<?php
							}
							?>
							<!--<div class="box-tools pull-right">
								<ul class="pagination pagination-sm inline">
									<li><a href="#">&laquo;</a></li>
									<li><a href="#">1</a></li>
									<li><a href="#">2</a></li>
									<li><a href="#">3</a></li>
									<li><a href="#">&raquo;</a></li>
								</ul>
							</div>-->
						</div>
						<!-- /.box-header -->
						<div class="box-body">
							<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
							<ul class="todo-list">
							<?php
								$total=0;
								?>
									<li>
										<table>
											<tr>
												<td>
													<span class="handle">
													
													</span>
												</td>
												<td width="30%">
													<span class="text">
														<?php echo 'Producto'; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php echo 'Cantidad '; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php echo 'Precio '; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php echo 'Status '; ?>
													</span>
												</td>
												<td width="10%">
													<span class="text">
															<?php echo 'Total'; ?>
													</span>
												</td>
											</tr>
									  </table>
									</li>
								<?php
								$sql="select * ". 
								   "FROM Asiento_F
									WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
									AND  HABIT='".$_POST['me']."' 
									ORDER BY CODIGO";
								
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
								$ii=0;
								$Result = array();
								$total=0;
								while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
								{
									$Result[$i]['CODIGO']=$row[0];
									$Result[$i]['PRODUCTO']=$row[3];
									$Result[$i]['CANT']=$row[1];
									$Result[$i]['PRECIO']=$row[4];
									$Result[$i]['A_No']=$row[34];
									$Result[$i]['Estado']=$row[52];
									$total=$total+($Result[$i]['PRECIO']*$Result[$i]['CANT']);
									
									?>
									<li>
										<table>
											<tr>
												<td>
													<span class="handle">
												
													</span>
												</td>
												<td width="30%">
													<span class="text">
														<?php echo $Result[$i]['PRODUCTO']; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php echo $Result[$i]['CANT']; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php echo $Result[$i]['PRECIO']; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php 
															if($Result[$i]['Estado']=='R')
															{
																?>
																<small class="label label-danger"><i class="fa fa-clock-o"></i>Pendiente</small>
																<?php
															}
															if($Result[$i]['Estado']=='A')
															{
																?>
																<a onclick="entregar('<?php echo $Result[$i]['A_No']; ?>',
																'<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
																'<?php echo $Result[$i]['PRODUCTO']; ?>','<?php echo $Result[$i]['CANT']; ?>');">
																	<small class="label label-warning"><i class="fa fa-clock-o"></i>Por entregar</small>
																</a>
																<?php
															}
															if($Result[$i]['Estado']=='V')
															{
																?>
																<small class="label label-success"><i class="fa fa-clock-o"></i>Despachado</small>
																<?php
															}
														?>
													</span>
												</td>
												<td width="10%">
													<span class="text">
															<?php echo ($Result[$i]['PRECIO']*$Result[$i]['CANT']); ?>
													</span>
													<div class="tools">
														<?php
															if($Result[$i]['Estado']!='V')
															{
														?>
																<a onclick="eliminarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
																'<?php echo $Result[$i]['CODIGO'];?>',
																'<?php echo $Result[$i]['CANT']; ?>',
																'<?php echo $Result[$i]['A_No'];?>');">
																<i class="fa fa-trash-o"></i>
														<?php
															}
														?>
														
													</div>
												</td>
											</tr>
										</table>
									</li>
									<?php
									$ii++;
								}
								/*for($i=0;$i<count($_SESSION['APPR']['PED']);$i++)
								{
									if($_SESSION['APPR']['PED'][$i]['MESA']==$_POST['me'])
									{
										//buscamos productos
										for($j=0;$j<count($_SESSION['APPR']['PROD']);$j++)
										{
											if($_SESSION['APPR']['PED'][$i]['PROD']==$_SESSION['APPR']['PROD'][$j]['codigo'])
											{
												$nombre=$_SESSION['APPR']['PROD'][$j]['nombre'];
												$precio=$_SESSION['APPR']['PROD'][$j]['precio'];
												
											}
										}
										$total=$total+($precio*$_SESSION['APPR']['PED'][$i]['CANT']);
									?>
										<li>
											<table>
												<tr>
													<td>
														<span class="handle">
													
														</span>
													</td>
													<td width="40%">
														<span class="text">
															<?php echo $nombre; ?>
														</span>
													</td>
													<td width="25%">
														<span class="text">
															<?php echo $_SESSION['APPR']['PED'][$i]['CANT']; ?>
														</span>
													</td>
													<td width="25%">
														<span class="text">
															<?php echo $precio; ?>
														</span>
													</td>
													<td width="10%">
														<span class="text">
																<?php echo ($precio*$_SESSION['APPR']['PED'][$i]['CANT']); ?>
														</span>
														<div class="tools">
															<!--<i class="fa fa-edit"></i>-->
															<a onclick="eliminarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
															'<?php echo $_SESSION['APPR']['PED'][$i]['PROD'];?>',
															'<?php echo $_SESSION['APPR']['PED'][$i]['CANT']; ?>',
															'<?php echo $_SESSION['APPR']['PED'][$i]['COD']; ?>');">
																<i class="fa fa-trash-o"></i>
															</a>
														</div>
													</td>
												</tr>
											</table>
										</li>
									<?php
									}
								}*/
							?>
								<li>
									  <span class="handle">
										<i class="fa fa-ellipsis-v"></i>
										<i class="fa fa-ellipsis-v"></i>
									  </span>
									  <!--<span class="text">
										<?php echo $nombre.' '.$_SESSION['APPR']['PED'][$i]['CANT']; ?> 
										$ <?php echo ($precio*$_SESSION['APPR']['PED'][$i]['CANT']); ?></span>-->
									<div class="tools" id='total_' style='display:block;'>
										<?php echo 'TOTAL $ '.$total; ?>
									</div>
								   
								</li>
							</ul>
						</div>
						<!-- /.box-body -->
						<div class="box-footer clearfix no-border">
							
							<?php
							if(isset( $_POST['nombrec']) and isset( $_POST['ruc']))
							{
							?>
								<button type="button" class="btn btn-default pull-right" onclick="fact('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>')"><i class="fa fa-plus"></i>Generar factura</button>
								<button type="button" class="btn btn-default pull-right" onclick="prefact2('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>')"><i class="fa fa-plus"></i>Pre-factura</button>
							<?php
							}
							else
							{
							?>
								<button type="button" class="btn btn-default pull-right" onclick="prefact2('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>')"><i class="fa fa-plus"></i>Pre-factura</button>
								<button type="button" class="btn btn-default pull-right" onclick="prefact('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>')"><i class="fa fa-plus"></i>Generar factura</button>
								<button type="button" class="btn btn-default pull-right" onclick="liberar('<?php echo $_POST['me']; ?>')"><i class="fa fa-plus"></i>Liberar</button>
							<?php
							}
							?>
							
						</div>
					</div>
				</form>
				<?php
				if($ii>5)
				{
				?>
					<?php
						if(isset( $_POST['nombrec']) and isset( $_POST['ruc']))
						{
						?>
							<script>
								$( "#pie_p" ).html('<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>'+
								'<button type="button" class="btn btn-info btn-flat" onclick="buscarpro(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\');">Ir a Productos</button>'+
								'<button type="button" class="btn btn-default pull-right" onclick="fact(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\')"><i class="fa fa-plus"></i>Generar factura</button>'+
								'<button type="button" class="btn btn-default pull-right" onclick="prefact2(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\')"><i class="fa fa-plus"></i>Pre-factura</button>'+
								'<button type="button" class="btn btn-default pull-right" onclick="liberar(\'<?php echo $_POST['me']; ?>\')"><i class="fa fa-plus"></i>Liberar</button>');
							</script>
						<?php
						}
						else
						{
						?>
							<script>
								$( "#pie_p" ).html('<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>'+
								'<button type="button" class="btn btn-info btn-flat" onclick="buscarpro(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\');">Ir a Productos</button>'+
								'<button type="button" class="btn btn-default pull-right" onclick="prefact(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\')"><i class="fa fa-plus"></i>Generar factura</button>'+
								'<button type="button" class="btn btn-default pull-right" onclick="prefact2(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\')"><i class="fa fa-plus"></i>Pre-factura</button>'+
								'<button type="button" class="btn btn-default pull-right" onclick="liberar(\'<?php echo $_POST['me']; ?>\')"><i class="fa fa-plus"></i>Liberar</button>');
							</script>
						<?php
						}
					?>
				<?php
				}
				else
				{
					?>
					<script>
						$( "#pie_p" ).html('<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>');
					</script>
					<?php
				}
			}
			else
			{
				if($cl=='ing_p1' and isset( $_POST['me']) and isset($_POST['nom']))
				{
					//ingresamos
					$cuen=count($_SESSION['APPR']['PED']);
					//echo $cuen.' ------------------------- ';
					if(isset($_POST['prod']))
					{
						$fecha_actual = date("Y-m-d"); 
						$prod = explode(",", $_POST['prod']);
						$cant = explode(",", $_POST['cant']);
						$obs = explode(",", $_POST['obs']);
						$me=$_POST['me'];
						//echo $_POST['prod'].'<br>';
						//echo count($prod).' ------------------------- ';
						for($i=0;$i<(count($prod)-1);$i++)
						{
							//echo $prod[$i].'<br>';
							$sql="SELECT * ". 
							   "FROM Catalogo_Productos
								WHERE (LEN(Cta_Inventario) > 2) AND (LEN(Cta_Costo_Venta) > 2) AND (TC = 'P') AND 
								(Periodo = '".$_SESSION['INGRESO']['periodo']."') 
								AND (Item = '".$_SESSION['INGRESO']['item']."')
								AND  Codigo_Inv = '".trim($prod[$i])."'
								ORDER BY Codigo_Inv";
							
							//echo $sql;
							//die();
							$stmt = sqlsrv_query( $cid, $sql);
							if( $stmt === false)  
							{  
								 echo "Error en consulta PA.\n";  
								 die( print_r( sqlsrv_errors(), true));  
							}
							$row_count=0;
							$ii=0;
							$Result = array();
							while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
							{
								$Result[$ii]['Codigo_Inv']=$row[2];
								$Result[$ii]['Producto']=$row[3];
								//echo $Result[$ii]['Producto']."<br>";
								$Result[$ii]['PVP']=$row[9];
								$Result[$ii]['IVA']=$row[10];
								if($Result[$ii]['IVA']==true)
								{
									//buscar variable del iva
									$Result[$ii]['IVA']=$Result[$ii]['PVP']*$_SESSION['INGRESO']['porc'];
								}
								else
								{
									$Result[$ii]['IVA']=0;
								}
								$Result[$ii]['Cta_Inventario']=$row[11];
								$Result[$ii]['Cta_Costo_Venta']=$row[12];
								//$ii++;
							}
							//verificamos valor
							$A_No=0;
							$sql=" SELECT MAX(A_No) AS Expr1 FROM  Asiento_F
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
							while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
							{
								$A_No=$row[0];
							}
							if($A_No==null)
							{
								$A_No=1;
							}
							else
							{
								$A_No++;
							}
							//echo $Result[0]['Producto']."<br>";
							$dato[0]['campo']='CODIGO';
							$dato[0]['dato']=trim($prod[$i]);
							$dato[1]['campo']='CANT';
							$dato[1]['dato']=$cant[$i];
							$dato[2]['campo']='PRODUCTO';
							$dato[2]['dato']=$Result[0]['Producto'];
							$dato[3]['campo']='PRECIO';
							$dato[3]['dato']=$Result[0]['PVP'];
							$dato[4]['campo']='Total_IVA';
							$dato[4]['dato']=($Result[0]['IVA']*$cant[$i]);
							$dato[5]['campo']='TOTAL';
							$dato[5]['dato']=($Result[0]['PVP']*$cant[$i]);
							$dato[6]['campo']='VALOR_TOTAL';
							$dato[6]['dato']=(($Result[0]['PVP']*$cant[$i])+($Result[0]['IVA']*$cant[$i]));
							$dato[7]['campo']='HABIT';
							$dato[7]['dato']=$me;
							$dato[8]['campo']='Item';
							$dato[8]['dato']=$_SESSION['INGRESO']['item'];
							$dato[9]['campo']='CodigoU';
							$dato[9]['dato']=$_SESSION['INGRESO']['CodigoU'];
							$dato[10]['campo']='Cta_Inv';
							$dato[10]['dato']=$Result[0]['Cta_Inventario'];
							$dato[11]['campo']='Cta_Costo';
							$dato[11]['dato']=$Result[0]['Cta_Costo_Venta'];
							$dato[12]['campo']='Fecha_IN';
							$dato[12]['dato']='20200209';
							$dato[13]['campo']='Meses';
							$dato[13]['dato']='0';
							$dato[14]['campo']='A_No';
							$dato[14]['dato']=$A_No;
							$dato[15]['campo']='RUTA';
							$dato[15]['dato']=$obs[$i];
							$dato[16]['campo']='Estado';
							$dato[16]['dato']='R';
							insert_generico("Asiento_F",$dato);
							
							//hacemos udate
							$sql="UPDATE ". 
							   " Catalogo_Productos SET Estado=1
								WHERE (Periodo = '".$_SESSION['INGRESO']['periodo']."') 
								AND (Item = '".$_SESSION['INGRESO']['item']."')
								AND Codigo_Inv = '".$me."'
								";
							
							//echo $sql;
							//die();
							$stmt = sqlsrv_query( $cid, $sql);
							if( $stmt === false)  
							{  
								 echo "Error en consulta PA.\n";  
								 die( print_r( sqlsrv_errors(), true));  
							}
							
							/*$sql="INSERT INTO Asiento_F
								   (CODIGO,CANT ,CANT_BONIF,PRODUCTO ,PRECIO,Total_Desc,Total_Desc2,Total_IVA ,SERVICIO
								   ,TOTAL ,VALOR_TOTAL,COSTO ,Fecha_IN ,Fecha_OUT,Cant_Hab ,Tipo_Hab,Orden_No,Mes
								   ,Cod_Ejec,Porc_C,REP,FECHA ,CODIGO_L,HABIT ,RUTA,TICKET,Cta,Cta_SubMod,Item,CodigoU
								   ,CodBod,CodMar,TONELAJE,CORTE,A_No,Codigo_Cliente,Numero,Serie ,Autorizacion
								   ,Codigo_B,PRECIO2,COD_BAR,Fecha_V,Lote_No,Fecha_Fab,Fecha_Exp,Reg_Sanitario
								   ,Modelo,Procedencia,Serie_No,Cta_Inv,Cta_Costo)
							 VALUES
								   ('".trim($prod[$i])."',".$cant[$i].",0,'".$Result[0]['Producto']."',".$Result[0]['PVP'].",0,0
								   ,".($Result[0]['IVA']*$cant[$i]).",0,".(($Result[0]['PVP']*$cant[$i])+($Result[0]['IVA']*$cant[$i]))."
								   ,".(($Result[0]['PVP']*$cant[$i])+($Result[0]['IVA']*$cant[$i])).",0,'".$fecha_actual."','".$fecha_actual."'
								   ,0,'.',0,'.','.',0,0,'".$fecha_actual."','.','".$me."','.','.','".$Result[$ii]['Cta_Inventario']."','.'
								   ,'".$_SESSION['INGRESO']['item']."','".$_SESSION['INGRESO']['CodigoU']."','.','.',0,0,".$A_No.",'.',0
								   ,'.','.','.',0,'.','".$fecha_actual."','.','".$fecha_actual."','".$fecha_actual."','.','.','.','.'
								   ,'".$Result[0]['Cta_Inventario']."','".$Result[0]['Cta_Costo_Venta']."');";
							//echo $sql;
							$stmt = sqlsrv_query( $cid, $sql);
							if( $stmt === false)  
							{  
								 echo "Error en consulta PA.\n";  
								 die( print_r( sqlsrv_errors(), true));  
							}
							*/
							/*$_SESSION['APPR']['PED'][$cuen]['MESA']=$me;
							$_SESSION['APPR']['PED'][$cuen]['PROD']=$prod[$i];
							$_SESSION['APPR']['PED'][$cuen]['CANT']=$cant[$i];
							$_SESSION['APPR']['PED'][$cuen]['COD']=$cuen;
							$cuen++;*/
						}
					}
					?>
					<form action="#" class="credit-card-div" id='formu1'>
						<!-- pedidos por mesa 3.1.03.01-->
						<div class="box box-primary">
							<div class="box-header">
								<input type="hidden" id='mesa' name='mesa'  value='<?php echo $_POST['me']; ?>' />
								<!--<h3 class="box-title">Seleccionar productos</h3>-->
								<h3 class="box-title">
									<?php echo 'Consumo '.$_POST['nom']; ?>
									<span class="input-group-btn">
											<input type="hidden" class="form-control" placeholder="Seleccionar productos" id='buscar' name='buscar' value='<?php echo $_POST['bus'];?>'>
											<button type="button" class="btn btn-info btn-flat" 
											onclick="buscarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>');">Ir a Productos</button>
										</span>
								</h3>
								<div class="box-tools pull-right">
									<ul class="pagination pagination-sm inline">
										<li><a href="#">&laquo;</a></li>
										<li><a href="#">1</a></li>
										<li><a href="#">2</a></li>
										<li><a href="#">3</a></li>
										<li><a href="#">&raquo;</a></li>
									</ul>
								</div>
							</div>
							<!-- /.box-header -->
							<div class="box-body">
								<!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
								<ul class="todo-list">
								<?php
									$total=0;
									?>
									<li>
										<table>
											<tr>
												<td>
													<span class="handle">
													
													</span>
												</td>
												<td width="30%">
													<span class="text">
														<?php echo 'Producto'; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php echo 'Cantidad '; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php echo 'Precio '; ?>
													</span>
												</td>
												<td width="20%">
													<span class="text">
														<?php echo 'Estado '; ?>
													</span>
												</td>
												<td width="10%">
													<span class="text">
															<?php echo 'Total'; ?>
													</span>
												</td>
											</tr>
									  </table>
									</li>
									<?php
										$sql="select * ". 
										   "FROM Asiento_F
											WHERE  (Item = '".$_SESSION['INGRESO']['item']."')
											AND  HABIT='".$me."' 
											ORDER BY CODIGO";
										
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
										$total=0;
										while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
										{
											$Result[$i]['CODIGO']=$row[0];
											$Result[$i]['PRODUCTO']=$row[3];
											$Result[$i]['CANT']=$row[1];
											$Result[$i]['PRECIO']=$row[4];
											$Result[$i]['A_No']=$row[34];
											$Result[$i]['Estado']=$row[52];
											$total=$total+($Result[$i]['PRECIO']*$Result[$i]['CANT']);
											
											?>
											<li>
												<table>
													<tr>
														<td>
															<span class="handle">
														
															</span>
														</td>
														<td width="30%">
															<span class="text">
																<?php echo $Result[$i]['PRODUCTO']; ?>
															</span>
														</td>
														<td width="20%">
															<span class="text">
																<?php echo $Result[$i]['CANT']; ?>
															</span>
														</td>
														<td width="20%">
															<span class="text">
																<?php echo $Result[$i]['PRECIO']; ?>
															</span>
														</td>
														<td width="20%">
															<span class="text">
																<?php 
																	if($Result[$i]['Estado']=='R')
																	{
																		?>
																		<small class="label label-danger"><i class="fa fa-clock-o"></i>Pendiente</small>
																		<?php
																	}
																	if($Result[$i]['Estado']=='A')
																	{
																		?>
																		<a onclick="entregar('<?php echo $Result[$i]['A_No']; ?>',
																		'<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
																		'<?php echo $Result[$i]['PRODUCTO']; ?>','<?php echo $Result[$i]['CANT']; ?>');">
																			<small class="label label-warning"><i class="fa fa-clock-o"></i>Por entregar</small>
																		</a>
																		<?php
																	}
																	if($Result[$i]['Estado']=='V')
																	{
																		?>
																		<small class="label label-success"><i class="fa fa-clock-o"></i>Despachado</small>
																		<?php
																	}
																?>
															</span>
														</td>
														<td width="10%">
															<span class="text">
																	<?php echo ($Result[$i]['PRECIO']*$Result[$i]['CANT']); ?>
															</span>
															<div class="tools">
																<!--<i class="fa fa-edit"></i>-->
																<?php
																	if($Result[$i]['Estado']!='V')
																	{
																?>
																		<a onclick="eliminarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
																		'<?php echo $Result[$i]['CODIGO'];?>',
																		'<?php echo $Result[$i]['CANT']; ?>',
																		'<?php echo $Result[$i]['A_No'];?>');">
																		<i class="fa fa-trash-o"></i>
																<?php
																	}
																?>
																</a>
															</div>
														</td>
													</tr>
												</table>
											</li>
											<?php
										}
									/*for($i=0;$i<count($_SESSION['APPR']['PED']);$i++)
									{
										if($_SESSION['APPR']['PED'][$i]['MESA']==$me)
										{
											//buscamos productos
											for($j=0;$j<count($_SESSION['APPR']['PROD']);$j++)
											{
												if($_SESSION['APPR']['PED'][$i]['PROD']==$_SESSION['APPR']['PROD'][$j]['codigo'])
												{
													$nombre=$_SESSION['APPR']['PROD'][$j]['nombre'];
													$precio=$_SESSION['APPR']['PROD'][$j]['precio'];
													
												}
											}
											$total=$total+($precio*$_SESSION['APPR']['PED'][$i]['CANT']);
										?>
											<li>
												<table>
													<tr>
														<td>
															<span class="handle">
														
															</span>
														</td>
														<td width="40%">
															<span class="text">
																<?php echo $nombre; ?>
															</span>
														</td>
														<td width="25%">
															<span class="text">
																<?php echo $_SESSION['APPR']['PED'][$i]['CANT']; ?>
															</span>
														</td>
														<td width="25%">
															<span class="text">
																<?php echo $precio; ?>
															</span>
														</td>
														<td width="10%">
															<span class="text">
																	<?php echo ($precio*$_SESSION['APPR']['PED'][$i]['CANT']); ?>
															</span>
															<div class="tools">
																<!--<i class="fa fa-edit"></i>-->
																<a onclick="eliminarpro('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>',
																'<?php echo $_SESSION['APPR']['PED'][$i]['PROD'];?>',
																'<?php echo $_SESSION['APPR']['PED'][$i]['CANT']; ?>',
																'<?php echo $_SESSION['APPR']['PED'][$i]['COD'];?>');">
																	<i class="fa fa-trash-o"></i>
																</a>
															</div>
														</td>
													</tr>
												</table>
												  <!--<span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												  </span>
												  <span class="text">
													<?php echo $nombre.' '.$_SESSION['APPR']['PED'][$i]['CANT']; ?> 
													$ <?php echo ($precio*$_SESSION['APPR']['PED'][$i]['CANT']); ?></span>
											  
											   <div class="tools">
												<i class="fa fa-edit"></i>
												<i class="fa fa-trash-o"></i>
											  </div>-->
											</li>
										<?php
										}
									}*/
									?>
										<li>
											  <span class="handle">
												
											  </span>
											  <!--<span class="text">
												<?php echo $nombre.' '.$_SESSION['APPR']['PED'][$i]['CANT']; ?> 
												$ <?php echo ($precio*$_SESSION['APPR']['PED'][$i]['CANT']); ?></span>-->
											<div class="tools" id='total_' style='display:block;'>
												<?php echo 'TOTAL $ '.$total; ?>
											</div>
										   
										</li>
									<?php
									/*for($i=0;$i<count($_SESSION['APPR']['PROD']);$i++)
									{
										if($_SESSION['APPR']['PROD'][$i]['disp']==1)
										{
											?>
											<li>
												  <span class="handle">
													<i class="fa fa-ellipsis-v"></i>
													<i class="fa fa-ellipsis-v"></i>
												  </span>
											  <span class="text"><?php echo $_SESSION['APPR']['PROD'][$i]['nombre']; ?><br>$<?php echo $_SESSION['APPR']['PROD'][$i]['precio']; ?></span>
											  
											   <div class="tools">
												<i class="fa fa-edit"></i>
												<i class="fa fa-trash-o"></i>
											  </div>
											</li>
											<?php
										}
									}*/
								?>
								
								</ul>
							</div>
							<!-- /.box-body -->
							<div class="box-footer clearfix no-border">
								<button type="button" class="btn btn-default pull-right"><i class="fa fa-plus"></i>Procesar</button>
							</div>
						</div>
					</form>
					<script>
						$( "#pie_p" ).html('<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>'+
						'<button type="button" class="btn btn-info btn-flat" onclick="buscarpro(\'<?php echo $_POST['me']; ?>\',\'<?php echo $_POST['nom'];?>\');">Ir a Productos</button>');
					</script>
					<?php
				}
				else
				{
					?>
					<script>
					 Swal.fire({
					  type: 'error',
					  title: 'Oops...',
					  text: 'debe seleccionar un registro!'
					});
					</script>
					<?php
				}
			}
		}
	}
	
	cerrarSQLSERVERFUN($cid);
}
//funcion para mostrar las mesas
function insert_generico($tabla=null,$datos=null)
{
	$cid=cone_ajaxSQL();
	$sql = "SELECT * from Information_Schema.Tables where TABLE_TYPE = 'BASE TABLE' AND TABLE_NAME='".$tabla."' ORDER BY TABLE_NAME";
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	$tabla_="";
	while( $obj = sqlsrv_fetch_object( $stmt)) 
	{
		//echo $obj->TABLE_NAME."<br />";
		$tabla_=$obj->TABLE_NAME;
	}
	if($tabla_!='')
	{
		//buscamos los campos
		$sql="SELECT        TOP (1) sys.sysindexes.rows
		FROM   sys.sysindexes INNER JOIN
		sys.sysobjects ON sys.sysindexes.id = sys.sysobjects.id
		WHERE   (sys.sysobjects.xtype = 'U') AND (sys.sysobjects.name = '".$tabla_."')
		ORDER BY sys.sysindexes.indid";
		
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			echo "Error en consulta.\n";  
			die( print_r( sqlsrv_errors(), true));  
		} 
		$tabla_cc=0;
		while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			//cantidad de campos
			$tabla_cc=$obj->rows;
		}
		$sql="SELECT COLUMN_NAME,DATA_TYPE,IS_NULLABLE,CHARACTER_MAXIMUM_LENGTH
		FROM Information_Schema.Columns
		WHERE TABLE_NAME = '".$tabla_."'";
		
		$stmt = sqlsrv_query( $cid, $sql);
		if( $stmt === false)  
		{  
			 echo "Error en consulta.\n";  
			 die( print_r( sqlsrv_errors(), true));  
		} 
		//consulta sql
		$sql_="INSERT INTO ".$tabla_."
			  (";
		$sql_v=" VALUES 
		(";
		$fecha_actual = date("Y-m-d"); 
		while( $obj = sqlsrv_fetch_object( $stmt)) 
		{
			if($obj->COLUMN_NAME!='ID')
			{
				$sql_=$sql_.$obj->COLUMN_NAME." ,";
			}
			
			//recorremos los datos
			$ban=0;
			for($i=0;$i<count($datos);$i++)
			{
				if($obj->COLUMN_NAME==$datos[$i]['campo'])
				{
					if($obj->DATA_TYPE=='int identity')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='nvarchar')
					{
						$sql_v=$sql_v."'".$datos[$i]['dato']."',";
					}
					if($obj->DATA_TYPE=='ntext')
					{
						$sql_v=$sql_v."'".$datos[$i]['dato']."',";
					}
					if($obj->DATA_TYPE=='tinyint')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='real')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='bit')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='smalldatetime' OR $obj->DATA_TYPE=='datetime')
					{
						$sql_v=$sql_v."'".$datos[$i]['dato']."',";
					}
					if($obj->DATA_TYPE=='money')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='int')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='float')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='smallint')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					if($obj->DATA_TYPE=='uniqueidentifier')
					{
						$sql_v=$sql_v."".$datos[$i]['dato'].",";
					}
					$ban=1;
				}
			}
			//por defaul
			if($ban==0)
			{
				if($obj->DATA_TYPE=='int identity')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='nvarchar')
				{
					$sql_v=$sql_v."'.',";
				}
				if($obj->DATA_TYPE=='ntext')
				{
					$sql_v=$sql_v."'.',";
				}
				if($obj->DATA_TYPE=='tinyint')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='real')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='bit')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='smalldatetime' OR $obj->DATA_TYPE=='datetime')
				{
					$sql_v=$sql_v."'".$fecha_actual."',";
				}
				if($obj->DATA_TYPE=='money')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='int')
				{
					if($obj->COLUMN_NAME=='ID')
					{
						$sql_v=$sql_v." ";
					}
					else
					{
						$sql_v=$sql_v."0,";
					}
				}
				if($obj->DATA_TYPE=='float')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='smallint')
				{
					$sql_v=$sql_v."0,";
				}
				if($obj->DATA_TYPE=='uniqueidentifier')
				{
					$sql_v=$sql_v."0,";
				}
			}
		}
		$longitud_cad = strlen($sql_); 
		$cam2 = substr_replace($sql_,")",$longitud_cad-1,1); 
		$longitud_cad = strlen($sql_v); 
		$v2 = substr_replace($sql_v,")",$longitud_cad-1,1); 
		//echo $cam2.$v2;
		$stmt = sqlsrv_query( $cid, $cam2.$v2);
		if( $stmt === false)  
		{  
			echo "Error en consulta PA.\n";  
			die( print_r( sqlsrv_errors(), true));  
		}
		/*
		
		*/
	}
	cerrarSQLSERVERFUN($cid);
}
//agregar ceros a cadena a la izquierda
function generaCeros($numero){
	 //obtengop el largo del numero
	 $largo_numero = strlen($numero);
	 //especifico el largo maximo de la cadena
	 $largo_maximo = 7;
	 //tomo la cantidad de ceros a agregar
	 $agregar = $largo_maximo - $largo_numero;
	 //agrego los ceros
	 for($i =0; $i<$agregar; $i++){
	 $numero = "0".$numero;
	 }
	 //retorno el valor con ceros
	 return $numero;
 }
function digito_verificadorf($ruc,$pag=null,$idMen=null,$item=null)
{
	//$ruc=$_POST['RUC'];
	//echo $ruc.' '.strlen($ruc);
	//codigo walter
	$DigStr = "";
	$VecDig = "";
	$Dig3 = "";
	$sSQLRUC = "";
	$CodigoEmp = "";
	$Producto = "";
	$SumaDig = "";
	$NumDig = "";
	$ValDig = "";
	$TipoModulo = "";
	$CodigoRUC = "";
	$Residuo = "";
	//echo $ruc.' ';
	$Dig3 = substr($ruc, 2, 1);
	//echo $Dig3;
	//$Codigo_RUC_CI = substr($ruc, 0, 10);
	//echo $Dig3.' '.$Codigo_RUC_CI ;
	$Tipo_Beneficiario = "P";
	//$NumEmpresa='001';
	$NumEmpresa=$item;
	//echo $item.' dddvc '.$NumEmpresa;
	$Codigo_RUC_CI = $NumEmpresa . "0000001";
	$Digito_Verificador = "-";
	$RUC_CI = $ruc;
	$RUC_Natural = False;
	//echo $Codigo_RUC_CI;
	//die();
	if($ruc == "9999999999999" )
	{
		$Tipo_Beneficiario = "R";
		$Codigo_RUC_CI = substr($ruc, 0, 10);
		$Digito_Verificador = 9;
		$DigStr = "9";
		//echo ' ccc '.$Codigo_RUC_CI;
		//die();
	}
	else
	{
		$DigStr = $ruc;
		$TipoBenef = "P";
		$VecDig = "000000000";
		$TipoModulo = 1;
		If (is_numeric($ruc) And $ruc <= 0)
		{
			$Codigo_RUC_CI = $NumEmpresa & "0000001";
		}
		Else
		{
			//es cedula
			if(strlen($ruc)==10 and is_numeric($ruc))
			{
				$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
				$arr1 = str_split($ruc);
				$resu = array();
				$resu1=0;
				$coe1=0;
				$pro='';
				$ter='';
				$TipoModulo=10;
				//validador
				$ban=0;
				for($jj=0;$jj<(strlen($ruc));$jj++)
				{
					//echo $arr1[$jj].' -- '.$jj.' cc ';
					//validar los dos primeros registros
					if($jj==0 or $jj==1)
					{
						$pro=$pro.$arr1[$jj];
					}
					if($jj==2)
					{
						$ter=$arr1[$jj];
					}
					//operacion suma
					if($jj<=(strlen($ruc)-2))
					{
						$resu[$jj]=$coe[$jj]*$arr1[$jj];
						if($resu[$jj]>=10)
						{
							$resu[$jj]=$resu[$jj]-9;
						}
						//suma
						$resu1=$resu[$jj]+$resu1;
					}
					//ultimo digito
					if($jj==(strlen($ruc)-1))
					{
						//echo " entro ";
						$coe1=$arr1[$jj];
					}
					
				}
				//verificamos los dos primeros registros
				if($pro>=24)
				{
					//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
					$ban=1;
				}
				//verificamos el tercer registros
				if($ter>6)
				{
					//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
					$ban=1;
				}
				//partimos string
				$arr2 = str_split($resu1);
				for($jj=0;$jj<(strlen($resu1));$jj++)
				{
					if($jj==0)
					{
						$arr2[$jj]=$arr2[$jj]+1;
					}
				}
				//aumentamos a la siguiente decena
				$resu2=$arr2[0].'0';
				//resultado del ultimo coeficioente
				$resu3 = $resu2- $resu1;
				$Residuo = $resu1 % $TipoModulo;
				//echo ' dsdsd '.$Residuo;
				//die();
				If ($Residuo == 0)
				{
				  $Digito_Verificador = "0";
				}
				Else
				{
				   $Residuo = $TipoModulo - $Residuo;
				   $Digito_Verificador = $Residuo;
				}
				//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
				if($ban==0)
				{
					If ($Digito_Verificador == substr($ruc, 9, 1))
					{
						$Tipo_Beneficiario = "C";
					}	
				}					
			}
			else
			{
				//caso ruc
				if(strlen($ruc)==13 and is_numeric($ruc))
				{
					//caso ruc ecuatorianos de extrangeros
					$Tipo_Beneficiario='O';
					if ($Dig3 == 6 )
					{
						$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
						$arr1 = str_split($ruc);
						$resu = array();
						$resu1=0;
						$coe1=0;
						$pro='';
						$ter='';
						$TipoModulo=10;
						//validador
						$ban=0;
						for($jj=0;$jj<(count($coe));$jj++)
						{
							//echo $arr1[$jj].' -- '.$jj.' cc ';
							//validar los dos primeros registros
							if($jj==0 or $jj==1)
							{
								$pro=$pro.$arr1[$jj];
							}
							if($jj==2)
							{
								$ter=$arr1[$jj];
							}
							//operacion suma
							if($jj<=(count($coe)-2))
							{
								$resu[$jj]=$coe[$jj]*$arr1[$jj];
								if($resu[$jj]>=10)
								{
									$resu[$jj]=$resu[$jj]-9;
								}
								//suma
								$resu1=$resu[$jj]+$resu1;
							}
							//ultimo digito
							if($jj==(count($coe)-1))
							{
								//echo " entro ";
								$coe1=$arr1[$jj];
							}
							
						}
						//verificamos los dos primeros registros
						if($pro>=24)
						{
							//echo "RUC/CI <p style='color:#FF0000;'>incorrecto los dos primeros digitos</p>";
							$ban=1;
						}
						//verificamos el tercer registros
						if($ter>6)
						{
							//echo "RUC/CI <p style='color:#FF0000;'>incorrecto el tercer digito</p>";
							$ban=1;
						}
						//partimos string
						$arr2 = str_split($resu1);
						for($jj=0;$jj<(strlen($resu1));$jj++)
						{
							if($jj==0)
							{
								$arr2[$jj]=$arr2[$jj]+1;
							}
						}
						//aumentamos a la siguiente decena
						$resu2=$arr2[0].'0';
						//resultado del ultimo coeficioente
						$resu3 = $resu2- $resu1;
						$Residuo = $resu1 % $TipoModulo;
						//echo ' dsdsd '.$Residuo;
						//die();
						If ($Residuo == 0)
						{
						  $Digito_Verificador = "0";
						}
						Else
						{
						   $Residuo = $TipoModulo - $Residuo;
						   $Digito_Verificador = $Residuo;
						}
						//echo $Digito_Verificador .' correcto '. substr($ruc, 9, 1);
						if($ban==0)
						{
							If ($Digito_Verificador == substr($ruc, 9, 1))
							{
								$Tipo_Beneficiario = "R";
								$RUC_Natural = True;
							}	
						}	
					}
					if($Tipo_Beneficiario=='O')
					{
						$TipoModulo = 11;
						//echo $Dig3.' qmm ';
						if (($Dig3 <= 5) and ($Dig3 >= 0))
						{
							$TipoModulo = 10;
							$TipoModulo1=9;
							$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
							$VecDig = "212121212";
							//echo " aquiii 1 ";
						}
						else
						{
							if ($Dig3 == 6)
							{
								$coe = array("3", "2", "7", "6","5", "4", "3", "2");
								$TipoModulo1=8;
								$VecDig = "32765432";
								//echo " aquiii 2 ";
							}
							else
							{
								if($Dig3 == 9)
								{
									$coe = array("4", "3", "2", "7", "6","5", "4", "3", "2");
									$TipoModulo1=9;
									$VecDig = "432765432";
									//echo " aquiii 3 ";/
								}
								else
								{
									$VecDig = "222222222";
									$TipoModulo1=9;
									//echo " aquiii 4 ";
									$coe = array("2", "2", "2", "2", "2","2", "2", "2", "2");
								}
							}
						}
						/*
						error caso 0802351031001
						echo $ruc.' ';
						echo $Dig3.' ';
						switch ($Dig3) 
						{
							case (($Dig3 <= 5) OR ($Dig3 >= 0)):
							{
								$TipoModulo = 10;
								$TipoModulo1=9;
								$coe = array("2", "1", "2", "1","2", "1", "2", "1","2");
								$VecDig = "212121212";
								echo " aquiii 1 ";
								break;
							}
							case ($Dig3 == 6 ):
							{
								$coe = array("3", "2", "7", "6","5", "4", "3", "2");
								$TipoModulo1=8;
								$VecDig = "32765432";
								echo " aquiii 2 ";
								break;
							}
							case ($Dig3 == 9):
							{
								$coe = array("4", "3", "2", "7", "6","5", "4", "3", "2");
								$TipoModulo1=9;
								$VecDig = "432765432";
								echo " aquiii 3 ";
								//echo " entro a nueve";
								break;
							}
							default:    
							{    
								$VecDig = "222222222";
								$TipoModulo1=9;
								echo " aquiii 4 ";
								$coe = array("2", "2", "2", "2", "2","2", "2", "2", "2");
							} 
						}*/
						//echo $VecDig.' ccc '.$TipoModulo.' nn ';
						//die();
						//realizamos productos
						$arr1 = str_split($ruc);
						$resu = array();
						$resu1=0;
						$coe1=0;
						$pro='';
						$ter='';
						//$TipoModulo=10;
						//validador
						$ban=0;
						for($jj=0;$jj<($TipoModulo1);$jj++)
						{
							//echo $arr1[$jj].' -- '.$jj.' cc ';
							//validar los dos primeros registros
							if($jj==0 or $jj==1)
							{
								$pro=$pro.$arr1[$jj];
							}
							if($jj==2)
							{
								$ter=$arr1[$jj];
							}
							//operacion suma
							if($jj<=(strlen($ruc)-2))
							{
								$resu[$jj]=$coe[$jj]*$arr1[$jj];
								/*if($resu[$jj]>=10)
								{
									$resu[$jj]=$resu[$jj]-9;
								}*/
								If (0 <= $Dig3 And $Dig3 <= 5 And $resu[$jj] > 9)
								{
									$resu[$jj]=$resu[$jj]-9;
								}									
								//suma
								$resu1=$resu[$jj]+$resu1;
								//echo $coe[$jj].' * '.$arr1[$jj].' = '.$resu[$jj].' sum '.$resu1.' -- ';
								
							}
							//ultimo digito
							if($jj==(strlen($ruc)-1))
							{
								//echo " entro ";
								$coe1=$arr1[$jj];
							}
							
						}
						//partimos string
						$arr2 = str_split($resu1);
						for($jj=0;$jj<(strlen($resu1));$jj++)
						{
							if($jj==0)
							{
								$arr2[$jj]=$arr2[$jj]+1;
							}
						}
						//aumentamos a la siguiente decena
						$resu2=$arr2[0].'0';
						//resultado del ultimo coeficioente
						$resu3 = $resu2- $resu1;
						$Residuo = $resu1 % $TipoModulo;
						If ($Residuo == 0)
						{
						  $Digito_Verificador = "0";
						}
						Else
						{
						   $Residuo = $TipoModulo - $Residuo;
						   $Digito_Verificador = $Residuo;
						}
						//echo $Digito_Verificador.' '.$Dig3.' ';
						If ($Dig3 == 6) 
						{
							If ($Digito_Verificador = substr($ruc, 8, 1)) 
							{
								$Tipo_Beneficiario = "R";
							}
						} 
						Else
						{
							//echo $Digito_Verificador.' veri '.substr($ruc, 9, 1);
							If ($Digito_Verificador == substr($ruc, 9, 1))
							{
								$Tipo_Beneficiario = "R";
							}							
						}
						If ($Dig3 < 6 )
						{
							$RUC_Natural = True;
						}
					}
				}
				//echo $Tipo_Beneficiario;
			}
		}
		//$_SESSION['INGRESO']['item']
		//Si no es RUC/CI, procesamos el numero de codigo que le corresponde
		//echo ' www '.substr($ruc, 12, 1).' -- '.$ruc;
		if(substr($ruc, 12, 1)!='1' and strlen($ruc)<>10)
		{
			$Tipo_Beneficiario = 'O';
		}
		if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
		{
			$database=$_SESSION['INGRESO']['Base_Datos'];
			//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
			$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
			$user=$_SESSION['INGRESO']['Usuario_DB'];
			$password=$_SESSION['INGRESO']['Contraseña_DB'];
		}
		else
		{
			$database="DiskCover_Prismanet";
			$server="tcp:mysql.diskcoversystem.com, 11433";
			$user="sa";
			$password="disk2017Cover";
		}
		/*$database="DiskCover_Prismanet";
		$server="mysql.diskcoversystem.com";
		$user="sa";
		$password="disk2017Cover";*/
		if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
		{
			$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

			$cid = sqlsrv_connect($server, $connectionInfo); //returns false
			if( $cid === false )
			{
				echo "fallo conecion sql server";
			}
			
		}
		switch ($Tipo_Beneficiario) 
		{
			case ($Tipo_Beneficiario =='C'):
			{
				$Codigo_RUC_CI = substr($ruc, 0, 10);
				//verificamos que no exista cliente
				$sql="SELECT Codigo from Clientes WHERE CI_RUC = '".$ruc."' AND TD ='C' ";
				//echo $sql;
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				} 
				$ii=0;				
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
				{
					//echo " cc ".$row[0];
					$ii++;
				}
				//echo $ii;
				if($ii>0)
				{
					echo "<script> alert('ya existe este RUC/CI: ".$ruc." '); </script>";
					$Codigo_RUC_CI = 'ya existe';
				}
				sqlsrv_close( $cid );
				break;
			}
			case ($Tipo_Beneficiario =='R' ):
			{
				if($RUC_Natural == True)
				{
					//echo " natural ";
					//transformamos los primeros dos digitos ABCDEF 012345
					$let1=convertirnumle(substr($ruc, 0, 1));
					$let2=convertirnumle(substr($ruc, 1, 1));
					//$Codigo_RUC_CI = substr($ruc, 1, 1).''.substr($ruc, 2, 1).''. substr($ruc, 3, 8);
					$Codigo_RUC_CI = $let1.''.$let2.''. substr($ruc, 2, 8);
				}
				else
				{
					$Codigo_RUC_CI = substr($ruc, 0, 10);
				}
				//verificamos que no exista cliente
				$sql="SELECT Codigo As Cod_RUC from Clientes WHERE CI_RUC = '".$ruc."' AND TD ='R' ";
				//echo $sql;
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				} 
				$ii=0;				
				while( $obj = sqlsrv_fetch_object( $stmt)) 
				{
					$ii++;
				}
				if($ii>0)
				{
					echo "<script> alert('ya existe este RUC/CI: ".$ruc." '); </script>";
					$Codigo_RUC_CI = 'ya existe';
				}
				sqlsrv_close( $cid );
				break;
			}
			default:    
			{    
				$Codigo_RUC_CI = $NumEmpresa."0000001";
				$CodigoEmp = $NumEmpresa."8888888";
				//echo $_SESSION['INGRESO']['IP_VPN_RUTA'];
				$sql="SELECT MAX(Codigo) As Cod_RUC from Clientes WHERE Codigo <  '".$CodigoEmp."'
				AND SUBSTRING(Codigo,1,3) = '".$NumEmpresa."' AND LEN(Codigo) = 10 
				AND TD NOT IN ('C','R') AND ISNUMERIC(Codigo) <> 0 ";
				//echo $sql;
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				}  
				$i=0;
				while( $obj = sqlsrv_fetch_object( $stmt)) 
				{
					//echo $obj->TABLE_NAME."<br />";
					$CodigoRUC=$obj->Cod_RUC;
					//echo $obj->Cod_RUC.' vvv ';
					$CodigoRUC = substr($obj->Cod_RUC, 4, strlen($obj->Cod_RUC))+1;
					//buscar funcion para agregar ceros
					$CodigoRUC=generaCeros($CodigoRUC);
					$Codigo_RUC_CI = $NumEmpresa . $CodigoRUC;
					$i++;
				}
				
				//verificamos que no exista cliente
				$sql="SELECT Codigo As Cod_RUC from Clientes WHERE Codigo = '".$Codigo_RUC_CI."' ";
				$stmt = sqlsrv_query( $cid, $sql);
				if( $stmt === false)  
				{  
					 echo "Error en consulta.\n";  
					 die( print_r( sqlsrv_errors(), true));  
				} 
				$ii=0;				
				while( $obj = sqlsrv_fetch_object( $stmt)) 
				{
					$ii++;
				}
				if($i==0)
				{
					$CodigoRUC = 1;
				}
				if($ii>0)
				{
					echo "<script> alert('ya existe este RUC/CI: ".$ruc." '); </script>";
					$Codigo_RUC_CI++;
					//$Codigo_RUC_CI = 'ya existe';
				}
				sqlsrv_close( $cid );
			} 
		}
		//echo $Codigo_RUC_CI;
		$TipoBenef = $Tipo_Beneficiario;
		$DigStr = $Digito_Verificador;
		//echo $Tipo_Beneficiario.' vvv '.strlen($ruc).' ccc ';
		switch ($Tipo_Beneficiario) 
		{
			case ($Tipo_Beneficiario =='R'):
			{
				if(strlen($ruc)<> 13 )
				{
					//echo " entro 1 ";
					$Tipo_Beneficiario = "P";
				}
				break;
			}
			//echo $Tipo_Beneficiario.' aden ';
			case ($Tipo_Beneficiario =='C'):
			{
				if(strlen($ruc)<> 10 )
				{
					//echo " entro 2 ";
					$Tipo_Beneficiario = "P";
				}
				break;
			}
			default:    
			{
				break;
			}
		}
		echo "<script> document.getElementById('".$_POST['idMen']."').value='".$Codigo_RUC_CI."'; </script>";
		echo "<script> document.getElementById('".$_POST['TC']."').value='".$Tipo_Beneficiario."'; </script>";
		echo 'RUC/CI ('.$Tipo_Beneficiario.') ';
		return 'RUC/CI ('.$Tipo_Beneficiario.') ';
	}
	$Digito_Verificador = $DigStr;
	//login('', '', '');
}
//crear select option
/*function select_option_a($tabla,$value,$mostrar,$filtro=null,$click=null,$id_html=null)
{
	//realizamos conexion
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
	{
		$database=$_SESSION['INGRESO']['Base_Datos'];
		//$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
		$server=''.$_SESSION['INGRESO']['IP_VPN_RUTA'].', '.$_SESSION['INGRESO']['Puerto'];
		$user=$_SESSION['INGRESO']['Usuario_DB'];
		$password=$_SESSION['INGRESO']['Contraseña_DB'];
	}
	else
	{
		$database="DiskCover_Prismanet";
		$server="tcp:mysql.diskcoversystem.com, 11433";
		$user="sa";
		$password="disk2017Cover";
	}
	/*$database="DiskCover_Prismanet";
	$server="mysql.diskcoversystem.com";
	$user="sa";
	$password="disk2017Cover";*/
	/*
	if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) and $_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER') 
	{
		$connectionInfo = array("Database"=>$database, "UID" => $user, "PWD" => $password);

		$cid = sqlsrv_connect($server, $connectionInfo); //returns false
		if( $cid === false )
		{
			echo "fallo conecion sql server";
		}
		$sql = "SELECT ".$value.",".$mostrar." FROM ".$tabla;
		if($filtro!=null and $filtro!='')
		{
			$sql =  $sql." WHERE ".$filtro." ";
		}
	}
	$mostrar1 = explode(",", $mostrar);
	if(count($mostrar1)==1)
	{
		$cam1=0;
	}
	else
	{
		$cam1=1;
	}
	//echo $sql;
	$stmt = sqlsrv_query( $cid, $sql);
	if( $stmt === false)  
	{  
		 echo "Error en consulta.\n";  
		 die( print_r( sqlsrv_errors(), true));  
	}  
	$i=0;
	$click1='';
	if($click!=null)
	{
		if($id_html!=null)
		{
			$click1=$click;
			$click1=$click1."('".$id_html."')";
			//onclick=" echo $click1; "
		}
	}
	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
	{
		?>	
		<option value='<?php echo $row[0]; ?>' >
			<?php
				if($cam1==0)
				{
					echo $row[1]; 
				}
				else
				{
					echo $row[1].'  '.$row[2]; 
				}
			?></option>
		<?php
	}
	sqlsrv_close( $cid );
}*/
?>