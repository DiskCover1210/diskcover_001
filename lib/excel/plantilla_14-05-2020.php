<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;

use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

function download_file($archivo, $downloadfilename = null) 
{

    if (file_exists($archivo)) {
        $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
		
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $downloadfilename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($archivo));
		
        ob_clean();
        flush();
        readfile($archivo);
		
        exit;
    }

}

function excel_file($stmt,$ti=null,$camne=null,$b=null,$base=null) 
{
	//require_once __DIR__ . '/vendor/phpoffice/phpspreadsheet/src/Bootstrap.php';
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	//$sheet->setCellValue('A1', 'Hello World !');

	// Set document properties
	$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		->setLastModifiedBy('Maarten Balliauw')
		->setTitle('Office 2007 XLSX Test Document')
		->setSubject('Office 2007 XLSX Test Document')
		->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		->setKeywords('office 2007 openxml php')
		->setCategory('Test result file');

	// Set style for header row using alternative method
	
	/*$spreadsheet->getActiveSheet()->getStyle('E3')->applyFromArray(
		[
				'borders' => [
					'right' => [
						'borderStyle' => Border::BORDER_THIN,
					],
				],
			]
	);*/
	if($base==null or $base=='SQL SERVER')
	{
		//echo $base.' entrooo ';
		//para las celdas de excel
		$ie = 1;
		$je = 'B';
		//para sabar hasta donde unir celdas
		$je1=$je;
		//verificamos los campos
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
						//redimencionar
						$spreadsheet->getActiveSheet()->getColumnDimension($je1)
					->setAutoSize(true);
						$je1++;
					}
				}
			}
		}
		
		//cabecera
		//diseño de nombres de campos
		//'horizontal' => Alignment::HORIZONTAL_RIGHT,
		//nos posicionamos 3 posiciones anteriores campo
		$je2='B';
		for($i=0;$i<($cant-3);$i++)
		{
			$je2++;
		}
		$je1=$je2;
		$je3=$je2;
		$je3++;
		$spreadsheet->getActiveSheet()->getStyle('A3:'.$je3.'3')->applyFromArray(
			[
					'font' => [
						'bold' => true,
					],
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					'borders' => [
						'top' => [
							'borderStyle' => Border::BORDER_THIN,
						],
					],
					'fill' => [
						'fillType' => Fill::FILL_GRADIENT_LINEAR,
						'rotation' => 90,
						'startColor' => [
							/*'argb' => 'FFA0A0A0',*/
							'argb' => '0086c7',
						],
						'endColor' => [
							'argb' => 'FFFFFFFF',
						],
					],
				]
		);
		//redimencionar
		//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
			[
					'alignment' => [
						'horizontal' => Alignment::HORIZONTAL_CENTER,
					],
					
			]
		);
		//logo empresa
		//echo __DIR__ ;
		//die();
		$drawing = new Drawing();
		$drawing->setName('Logo');
		$drawing->setDescription('Logo');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		//$drawing->setPath(__DIR__ . '/logosMod.gif');
		if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			//si es jpg
			$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
			if (@getimagesize($src)) 
			{ 
				$logo=$logo.'.jpg';
			}
			else
			{
				//si es gif
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.gif';
				}
				else
				{
					//si es png
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.png';
					}
					else
					{
						$logo="DEFAULT.jpg";
					}
				}
			}
		}
		else
		{
			$logo="DEFAULT.jpg";
		}
		/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
		{
			$logo=$_SESSION['INGRESO']['Logo_Tipo'];
		}
		else
		{
			$logo="DEFAULT";
		}*/
		$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates('A1');
		$drawing->setOffsetX(10);
		$drawing->setOffsetY(20);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//otro logo
		$drawing = new Drawing();
		$drawing->setName('Logo1');
		$drawing->setDescription('Logo1');
		//windows
		//$drawing->setPath(__DIR__ . '\logosMod.gif');
		//linux
		$drawing->setPath(__DIR__ . '/logosMod.gif');
		//$drawing->setPath('logosMod.gif');
		$drawing->setHeight(36);
		$drawing->setCoordinates($je3.'1');
		$drawing->setOffsetX(100);
		$drawing->setOffsetY(10);
		$drawing->setWorksheet($spreadsheet->getActiveSheet());
		//unir celdas
		$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
		if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
		{
			$richText2 = new RichText();
			$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

			$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
			//ampliar columna
			//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
		}
		else
		{
			$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
		}
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
		$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
		$je1++;
		$richText1 = new RichText();
		$richText1->createText(''."\n");
		
		$redf=$richText1->createTextRun("Hora: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("H:i")."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Fecha: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		
		$redf=$richText1->createTextRun("Usuario: ");
		$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$redf->getFont()->setSize(8);
		$redf->getFont()->setBold(true);
		
		$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
		$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
		$red->getFont()->setSize(8);
		
		$spreadsheet->getActiveSheet()
			->getCell($je1.''.$ie)
			->setValue($richText1);

			//ampliar columna
		$spreadsheet->getActiveSheet()
			->getStyle($je1.''.$ie)
			->getAlignment()->setWrapText(true);
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
		$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);
		//$je1++;
		//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
		//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
		
		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
		//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

		$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');
		
		//si lleva o no border
		$bor='';
		if($b!=null and $b!='0')
		{
			$bor='table-bordered1';
			//style="border-top: 1px solid #bce8f1;"
		}
		//nombre de columnas
		//para las celdas de excel
		$ie = 3;
		$je = 'A';
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
		foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) {
			$ie = 3;
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
				$tipo_campo[($cant)]="style='text-align: right;'";
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
			//ntext
			if($fieldMetadata['Type']==12)
			{
				$tipo_campo[($cant)]="style='text-align: left;'";
				$ban=1;
			}
			if($ban==0)
			{
				echo ' no existe tipo '.$value;
				die();
			}
			foreach( $fieldMetadata as $name => $value) {
				
				if(!is_numeric($value))
				{
					if($value!='')
					{
						//echo "<th ".$tipo_campo[$cant].">".$value."</th>";
						$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $value);
						$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
						$camp=$value;
						$campo[$cant]=$camp;
						//echo ' dd '.$campo[$cant];
						$cant++;
						$ie++;
						//echo $value.' cc '.$cant.' ';
					}
				}
			   //echo "$name: $value<br />";
			}
			//incrementamos la celda
			$je++;
		}
		$ie = 4;
		$je = 'A';
		//imprimir valores
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
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) 
		{
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
									$indencam1[$conin]=str_repeat("    ", (count($inden1)-1));
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
			$je='A';
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
				//letra
				$ita5="Calibri";
				for($j=0;$j<count($itacam1);$j++)
				{
					//echo $itacam1[$j].' ssscc '.$i;
					if($itacam1[$j]==$i)
					{
						$ita3=$ita1;
						$ita4=$ita2;
						if($ita3=='<em>')
						{
							$ita5="Arial Narrow";
						}
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
					
					if(number_format($row[$i],2, '.', ',')==0 OR number_format($row[$i],2, '.', ',')=='0,00')
					{
						if($sub3=='<u>')
						{
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'font' => [
											'underline' => Font::UNDERLINE_SINGLE,
										],
									]
							);
						}
						if($cfila1=='<B>')
						{
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'font' => [
											'bold' => true,
										],
									]
							);

						}
						//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
						$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden."-");
						$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
					}
					else
					{
						//si es negativo colocar rojo
						if($row[$i]<0)
						{
							if($sub3=='<u>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'underline' => Font::UNDERLINE_SINGLE,
											],
										]
								);
							}
							if($cfila1=='<B>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'bold' => true,
											],
										]
								);

							}
							//reemplazo una parte de la cadena por otra
							$longitud_cad = strlen($tipo_campo[$i]); 
							$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
							//echo "<td ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
							$richText2 = new RichText();
							$red = $richText2->createTextRun(number_format($row[$i],2, '.', ','));
							$red->getFont()->setColor(new Color(Color::COLOR_RED));
							$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.$richText2);
							$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
							
						}
						else
						{
							if($sub3=='<u>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'underline' => Font::UNDERLINE_SINGLE,
											],
										]
								);
							}
							if($cfila1=='<B>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'bold' => true,
											],
										]
								);

							}
							//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
							$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.number_format($row[$i],2, '.', ','));
							$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
						}
					}
					
				}
				else
				{
					//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
							[
									'alignment' => [
										'horizontal' => Alignment::HORIZONTAL_LEFT,
									],
									'font' => [
										'name' => $ita5,
										//'underline' => Font::UNDERLINE_SINGLE,
									],
								]
						);
					if($sub3=='<u>')
					{
						$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
							[
									'font' => [
										'underline' => Font::UNDERLINE_SINGLE,
									],
								]
						);
					}
					if($cfila1=='<B>')
					{
						$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
							[
									'font' => [
										'bold' => true,
										'name' => $ita5,
									],
								]
						);

					}
					$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.$row[$i]);
					$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
				}
				$je++;
			}
			$ie++;
		}	
	}
	else
	{
		if($base=='MYSQL')
		{
			$info_campo = $stmt->fetch_fields();
			//para las celdas de excel
			$ie = 1;
			$je = 'B';
			//para sabar hasta donde unir celdas
			$je1=$je;
			//verificamos los campos
			//cantidad de campos
			$cant=0;
			//guardamos los campos
			$campo='';
			foreach ($info_campo as $valor) 
			{
				$cant++;
			}
			//cabecera
			//diseño de nombres de campos
			//'horizontal' => Alignment::HORIZONTAL_RIGHT,
			//nos posicionamos 3 posiciones anteriores campo
			$je2='B';
			for($i=0;$i<($cant-3);$i++)
			{
				$je2++;
			}
			$je1=$je2;
			$je3=$je2;
			$je3++;
			$spreadsheet->getActiveSheet()->getStyle('A3:'.$je3.'3')->applyFromArray(
				[
						'font' => [
							'bold' => true,
						],
						'alignment' => [
							'horizontal' => Alignment::HORIZONTAL_CENTER,
						],
						'borders' => [
							'top' => [
								'borderStyle' => Border::BORDER_THIN,
							],
						],
						'fill' => [
							'fillType' => Fill::FILL_GRADIENT_LINEAR,
							'rotation' => 90,
							'startColor' => [
								/*'argb' => 'FFA0A0A0',*/
								'argb' => '0086c7',
							],
							'endColor' => [
								'argb' => 'FFFFFFFF',
							],
						],
					]
			);
			//redimencionar
			//$spreadsheet->getActiveSheet()->getColumnDimension($je.''.$ie.':'.$je1.''.$ie)->setAutoSize(true);
			$spreadsheet->getActiveSheet()->getStyle('B1')->applyFromArray(
				[
						'alignment' => [
							'horizontal' => Alignment::HORIZONTAL_CENTER,
						],
						
				]
			);
			//logo empresa
			//echo __DIR__ ;
			//die();
			$drawing = new Drawing();
			$drawing->setName('Logo');
			$drawing->setDescription('Logo');
			//windows
			//$drawing->setPath(__DIR__ . '\logosMod.gif');
			//linux
			//$drawing->setPath(__DIR__ . '/logosMod.gif');
			if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
			{
				$logo=$_SESSION['INGRESO']['Logo_Tipo'];
				//si es jpg
				$src = __DIR__ . '/../../img/logotipos/'.$logo.'.jpg'; 
				if (@getimagesize($src)) 
				{ 
					$logo=$logo.'.jpg';
				}
				else
				{
					//si es gif
					$src = __DIR__ . '/../../img/logotipos/'.$logo.'.gif'; 
					if (@getimagesize($src)) 
					{ 
						$logo=$logo.'.gif';
					}
					else
					{
						//si es png
						$src = __DIR__ . '/../../img/logotipos/'.$logo.'.png'; 
						if (@getimagesize($src)) 
						{ 
							$logo=$logo.'.png';
						}
						else
						{
							$logo="DEFAULT.jpg";
						}
					}
				}
			}
			else
			{
				$logo="DEFAULT.jpg";
			}
			/*if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
			{
				$logo=$_SESSION['INGRESO']['Logo_Tipo'];
			}
			else
			{
				$logo="DEFAULT";
			}*/
			$drawing->setPath(__DIR__ . '/../../img/logotipos/'.$logo);
			//$drawing->setPath('logosMod.gif');
			$drawing->setHeight(36);
			$drawing->setCoordinates('A1');
			$drawing->setOffsetX(10);
			$drawing->setOffsetY(20);
			$drawing->setWorksheet($spreadsheet->getActiveSheet());
			//otro logo
			$drawing = new Drawing();
			$drawing->setName('Logo1');
			$drawing->setDescription('Logo1');
			//windows
			//$drawing->setPath(__DIR__ . '\logosMod.gif');
			//linux
			$drawing->setPath(__DIR__ . '/logosMod.gif');
			//$drawing->setPath('logosMod.gif');
			$drawing->setHeight(36);
			$drawing->setCoordinates($je3.'1');
			$drawing->setOffsetX(100);
			$drawing->setOffsetY(10);
			$drawing->setWorksheet($spreadsheet->getActiveSheet());
			//unir celdas
			$spreadsheet->getActiveSheet()->mergeCells($je.''.$ie.':'.$je2.''.$ie);
			if($_SESSION['INGRESO']['Razon_Social']!=$_SESSION['INGRESO']['noempr'])
			{
				$richText2 = new RichText();
				$richText2->createText($_SESSION['INGRESO']['noempr'].' ');

				$red = $richText2->createTextRun($_SESSION['INGRESO']['Razon_Social']);
				$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
				$spreadsheet->getActiveSheet()->setCellValue('B1', $richText2);
				//ampliar columna
				//$spreadsheet->getActiveSheet()->getStyle('B1')->getAlignment()->setWrapText(true);
			}
			else
			{
				$spreadsheet->getActiveSheet()->setCellValue('B1', $_SESSION['INGRESO']['noempr'].'');
			}
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setName('Candara');
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setSize(20);
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setUnderline(Font::UNDERLINE_SINGLE);
			$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
			
			$spreadsheet->getActiveSheet()->getStyle('B1')->getProtection()->setLocked(Protection::PROTECTION_UNPROTECTED);
			$spreadsheet->getActiveSheet()->getColumnDimension('B1')->setAutoSize(true);
			$je1++;
			$richText1 = new RichText();
			$richText1->createText(''."\n");
			
			$redf=$richText1->createTextRun("Hora: ");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			$redf->getFont()->setBold(true);
			
			$redf=$richText1->createTextRun(date("H:i")."\n");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			
			$redf=$richText1->createTextRun("Fecha: ");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			$redf->getFont()->setBold(true);
			
			$redf=$richText1->createTextRun(date("d-m-Y") ."\n");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			
			$redf=$richText1->createTextRun("Usuario: ");
			$redf->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$redf->getFont()->setSize(8);
			$redf->getFont()->setBold(true);
			
			$red = $richText1->createTextRun($_SESSION['INGRESO']['Nombre']);
			$red->getFont()->setColor(new Color(Color::COLOR_WHITE));
			$red->getFont()->setSize(8);
			
			$spreadsheet->getActiveSheet()
				->getCell($je1.''.$ie)
				->setValue($richText1);

				//ampliar columna
			$spreadsheet->getActiveSheet()
				->getStyle($je1.''.$ie)
				->getAlignment()->setWrapText(true);
			//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, Date::PHPToExcel(gmmktime(0, 0, 0, date('m'), date('d'), date('Y'))));
			//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_XLSX15);
			$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
			$spreadsheet->getActiveSheet()->getColumnDimension($je1)->setAutoSize(true);
			//$je1++;
			//$spreadsheet->getActiveSheet()->setCellValue($je1.''.$ie, '');
			//$spreadsheet->getActiveSheet()->getStyle($je1.''.$ie)->getFont()->getColor()->setARGB(Color::COLOR_WHITE);
			
			$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->setFillType(Fill::FILL_SOLID);
			//$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('FF808080');

			$spreadsheet->getActiveSheet()->getStyle('A'.$ie.':'.$je1.''.$ie)->getFill()->getStartColor()->setARGB('0086c7');
			
			//si lleva o no border
			$bor='';
			if($b!=null and $b!='0')
			{
				$bor='table-bordered1';
				//style="border-top: 1px solid #bce8f1;"
			}
			//nombre de columnas
			//para las celdas de excel
			$ie = 3;
			$je = 'A';
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
				/*echo "<th ".$tipo_campo[$cant].">".$valor->name."</th>";
							$camp=$valor->name;
							$campo[$cant]=$camp;*/
				//echo "<th ".$tipo_campo[$cant].">".$value."</th>";
							$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $valor->name);
							$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
							$camp=$valor->name;
							$campo[$cant]=$camp;
							//echo ' dd '.$campo[$cant];
							$cant++;
							//$ie++;
							$je++;
			}
			$ie = 4;
			$je = 'A';
			//imprimir valores
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
			{
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
										$indencam1[$conin]=str_repeat("    ", (count($inden1)-1));
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
				$je='A';
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
					//letra
					$ita5="Calibri";
					for($j=0;$j<count($itacam1);$j++)
					{
						//echo $itacam1[$j].' ssscc '.$i;
						if($itacam1[$j]==$i)
						{
							$ita3=$ita1;
							$ita4=$ita2;
							if($ita3=='<em>')
							{
								$ita5="Arial Narrow";
							}
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
						
						if(number_format($row[$i],2, '.', ',')==0 OR number_format($row[$i],2, '.', ',')=='0,00')
						{
							if($sub3=='<u>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'underline' => Font::UNDERLINE_SINGLE,
											],
										]
								);
							}
							if($cfila1=='<B>')
							{
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
									[
											'font' => [
												'bold' => true,
											],
										]
								);

							}
							//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$sub3.$inden."-".$sub4.$ita4.$cfila2."</td>";
							$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden."-");
							$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
						}
						else
						{
							//si es negativo colocar rojo
							if($row[$i]<0)
							{
								if($sub3=='<u>')
								{
									$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
										[
												'font' => [
													'underline' => Font::UNDERLINE_SINGLE,
												],
											]
									);
								}
								if($cfila1=='<B>')
								{
									$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
										[
												'font' => [
													'bold' => true,
												],
											]
									);

								}
								//reemplazo una parte de la cadena por otra
								$longitud_cad = strlen($tipo_campo[$i]); 
								$cam2 = substr_replace($tipo_campo[$i],"color: red;'",$longitud_cad-1,1); 
								//echo "<td ".$cam2." > ".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
								$richText2 = new RichText();
								$red = $richText2->createTextRun(number_format($row[$i],2, '.', ','));
								$red->getFont()->setColor(new Color(Color::COLOR_RED));
								$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.$richText2);
								$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
								
							}
							else
							{
								if($sub3=='<u>')
								{
									$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
										[
												'font' => [
													'underline' => Font::UNDERLINE_SINGLE,
												],
											]
									);
								}
								if($cfila1=='<B>')
								{
									$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
										[
												'font' => [
													'bold' => true,
												],
											]
									);

								}
								//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".number_format($row[$i],2, ',', '.')."".$sub4.$ita4.$cfila2."</td>";
								$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.number_format($row[$i],2, '.', ','));
								$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
							}
						}
						
					}
					else
					{
						//echo "<td ".$tipo_campo[$i].">".$cfila1.$ita3.$inden.$sub3."".$row[$i]."".$sub4.$ita4.$cfila2."</td>";
								$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'alignment' => [
											'horizontal' => Alignment::HORIZONTAL_LEFT,
										],
										'font' => [
											'name' => $ita5,
											//'underline' => Font::UNDERLINE_SINGLE,
										],
									]
							);
						if($sub3=='<u>')
						{
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'font' => [
											'underline' => Font::UNDERLINE_SINGLE,
										],
									]
							);
						}
						if($cfila1=='<B>')
						{
							$spreadsheet->getActiveSheet()->getStyle($je.''.$ie)->applyFromArray(
								[
										'font' => [
											'bold' => true,
											'name' => $ita5,
										],
									]
							);

						}
						$spreadsheet->getActiveSheet()->setCellValue($je.''.$ie, $inden.$row[$i]);
						$spreadsheet->getActiveSheet()->getColumnDimension($je)->setAutoSize(true);
					}
					$je++;
				}
				$ie++;
			}
			/*//obtenemos los campos 
			foreach( sqlsrv_field_metadata( $stmt ) as $fieldMetadata ) 
			{
				foreach( $fieldMetadata as $name => $value) {
					if(!is_numeric($value))
					{
						if($value!='')
						{
							$cant++;
							//redimencionar
							$spreadsheet->getActiveSheet()->getColumnDimension($je1)
						->setAutoSize(true);
							$je1++;
						}
					}
				}
			}*/
		}
	}
	$spreadsheet->setActiveSheetIndex(0);

	$writer = new Xlsx($spreadsheet);
	$writer->save(trim($_SESSION['INGRESO']['ti']).'.xlsx');
	//$writer->save('php://output');

	download_file(trim($_SESSION['INGRESO']['ti']).".xlsx", trim($_SESSION['INGRESO']['ti']).".xlsx");
}
?>