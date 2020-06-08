<?php
require('../../lib/fpdf/fpdf.php');
$tipo = $_GET['tipo'];

// datos que se deben llenar desde la base de datos 

	$datos = array('numfactura' => '1234567890','numautorizacio' =>'0987654321098765432112345678904567890','fechafac'=>'05/Mar/2020',
	'horafac' => '08:28:00','razon'=>'congregacion de hermana dominicanas de la inmaculada','ci'=>'1234567890',
	'telefono' =>'09999999999','email' =>'example@example.com','subtotal'=>'450.00','dto'=>'0.00','iva'=>'54.00','totalfac'=>'504.00' );

	$lineas = array(
		'0'=>array('cant' => '2','detalle'=>' servicio de mantenimineto','pvp'=>'450.00','total'=>'450.00' ),
		'1'=>array('cant' => '3','detalle'=>' servicio de mantenimineto de servidores','pvp'=>'450.00','total'=>'450.00' ),
        '2'=>array('cant' => '3','detalle'=>' servicio de mantenimineto de servidores','pvp'=>'450.00','total'=>'450.00' ),  );

// fin de datos de la base de datos




    $pdf = new FPDF('P','mm',array(90, 300));
    $pdf->AddPage();
    $salto = 5;
    // Logo
    $pdf->Image('../../img/logotipos/PRISMANE.GIF',3,3,35,20);
    // Arial bold 15
    $pdf->SetFont('Arial','B',12);
    // Título
    $pdf->Cell(30);
    $pdf->Cell(0,5,'RUC',0,0,'C');
    $pdf->Ln($salto);
    $pdf->Cell(30);
    $pdf->Cell(0,5,'1792164710001',0,0);
    $pdf->Ln($salto);
    $pdf->Cell(30);
    $pdf->Cell(0,5,'Telefono:025116377',0,0);
    $pdf->Ln($salto);
    $pdf->Cell(0,5,'PRISMANET PROFESIONAL S.A',0,0,'C');
    $pdf->Ln($salto);
    $pdf->Cell(0,5,'PRISMANET',0,0,'C');


    $pdf->Ln($salto);
    $pdf->SetFont('Arial','B',8);
    $pdf->Ln($salto);
    $pdf->MultiCell(70,3,'Direccion Mat.:PABLO PALACIO N23-154 Y AV.LA GASCA Una cuadramas arriba del segundo semaforo');
    $pdf->Ln(2);
    $pdf->Cell(0,3,'OBLIGADO A LLEVAR CONTABILIDAD: SI',0,0);
    $pdf->Ln($salto);
    $pdf->Cell(0,0,'',1,0);



    $pdf->SetFont('');
    $pdf->Ln(1);
    if($tipo == 'F')
    {    	
     $pdf->Cell(0,5,'FACTURA No:'.$datos['numfactura'],0,0);
    }
    else
    {
     $pdf->Cell(0,5,'PREFACTURA No:',0,0);    	
    }
    $pdf->Ln($salto);
    $pdf->MultiCell(70,3,'NUMERO DE AUTORIZACION '.$datos['numautorizacio'],0,'L');
    $pdf->Ln(1);
    $pdf->Cell(35,5,'FECHA:'.$datos['fechafac'],0,0);
    $pdf->Cell(35,5,'HORA:'.$datos['horafac'],0,0);
    $pdf->Ln($salto);
    $pdf->Cell(40,5,'AMBIENTE:Produccion',0,0);
    $pdf->Cell(30,5,'EMISION:Normal',0,0);
    $pdf->Ln($salto);   
    $pdf->MultiCell(70,3,'CALVE DE ACCESO '.$datos['numautorizacio'],0,'L');
    $pdf->Ln(3);   
    $pdf->Cell(0,0,'',1,0);
    
    $pdf->Ln(3);   
    $pdf->MultiCell(70,3,'Razon social/Nombres y Apellidos: '.$datos['razon'],0,'L');
    $pdf->Cell(35,5,'Identificacion:'.$datos['ci'],0,0);
    $pdf->Cell(35,5,'Telefono:'.$datos['telefono'],0,0);
    $pdf->Ln($salto);
    $pdf->Cell(0,0,'Email.'.$datos['email'],0,0);
    $pdf->Ln(3);
    $pdf->Cell(0,0,'',1,0); 

    $pdf->Ln(1);
    $pdf->SetFont('Arial','B',8);
    $pdf->Cell(10,2,'Cant',0,0);
    $pdf->Cell(28,2,'PRODUCTO',0,0,'C');
    $pdf->Cell(15,2,'P.V.P',0,0,'R');
    $pdf->Cell(17,2,'TOTAL',0,0,'R');
    $pdf->Ln($salto);
   

//    se cargan las lineas de la factura
    foreach ($lineas as $value) {
   
    $pdf->SetFont('Arial','',8);
    $pdf->Cell(10,2,$value['cant'],0,0);
     $y2 = $pdf->GetY();
    $pdf->MultiCell(28,2,$value['detalle'],0,'L');
    $y = $pdf->GetY();
    $pdf->SetXY(48,$y2);
    $pdf->Cell(15,2,$value['pvp'],0,0,'R');
    $pdf->Cell(17,2,$value['total'],0,0,'R');
    $pdf->SetY($y);
    $pdf->Ln(2);
    $pdf->Cell(0,0,'',1,0); 
    $pdf->Ln(2);

    }
// fin de carga de lineas de factura

    $pdf->SetFont('Arial','',8);
    $pdf->Cell(35,2,'Cajero',0,0,'L');
    $pdf->Cell(17,2,'SUBTOTAL :',0,0,'L');
    $pdf->Cell(17,2,$datos['subtotal'],0,0,'R');
    $pdf->Ln(3);

    $pdf->Cell(35,2,'',0,0,'L');
    $pdf->Cell(17,2,'DESCUENTOS :',0,0,'L');
    $pdf->Cell(17,2,$datos['dto'],0,0,'R');
    $pdf->Ln(3);


    $pdf->Cell(35,2,'',0,0,'L');
    $pdf->Cell(17,2,'I.V.A 12% :',0,0,'L');
    $pdf->Cell(17,2,$datos['iva'],0,0,'R');
    $pdf->Ln(3);


    $y2 = $pdf->GetY();
    $pdf->MultiCell(35,2,'Su factura sera enviada al correo electronico registrado',0,'L');
    $y = $pdf->GetY();
    $pdf->SetXY(45,$y2);
    $pdf->Cell(17,2,'TOTAL :',0,0,'L');
    $pdf->Cell(17,2,$datos['totalfac'],0,0,'R');
    $pdf->SetY($y);
    $pdf->Ln(3);
    $pdf->Cell(0,0,'',1,0); 
    $pdf->Ln($salto);
	
	//agregamos las lineas para los datos
	if($tipo == 'PF')
    {   
		$pdf->SetFont('Arial','B',8);
		$pdf->Cell(0,5,'Datos del pago',0,0,'C');
		$pdf->Ln($salto);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(0,5,'RUC       NOMBRE         MONTO    ',0,0,'C');
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
		$pdf->Ln($salto);
		$pdf->Cell(0,0,'',1,0); 
		$pdf->Ln($salto);
    }
    

    $pdf->SetFont('Arial','B',10);
    $pdf->Cell(0,5,'GRACIAS POR SU COLABORACION',0,0,'C');
    $pdf->Ln($salto);
    $pdf->SetFont('Arial','',7);
    $pdf->Cell(0,5,'www.diskcoversystem.com',0,0,'C');
    $pdf->Ln($salto);
	
	
    $pdf->Output();


?>


