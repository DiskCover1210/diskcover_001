<?php  require_once("panel.php");date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION);die();//print_r($_SESSION['INGRESO']);die();?>
<script type="text/javascript">
	 let Modificar = false;
   let Bandera = true;
	var PorCodigo=false;
  $(document).ready(function () {
  	var tipo = "<?php echo $_GET['tipo']; ?>";
  	$('#TipoFactura').val(tipo);
  	Eliminar_linea('','');
  	 lineas_factura();
  	 numero_factura();
  	 DCTipoPago();
  	 DCMod();
  	 DCMedico();
  	 DCGrupo_No();
  	 DCLineas(); 
  	 FPorCodigo();  	
  	 CDesc1();
  	 DCEjecutivo();
  	 DCBodega();
  	 DCMarca();
  	 autocomplete_cliente();
  	 autocomplete_producto();
  	  LstOrden();
  	 // Lineas_De_CxC();

  });

  function DCTipoPago()
  {
  	
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCTipoPago=true',
			//data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				llenarComboList(data,'DCTipoPago'); 
			}
		});

  }
  function DCMod()
  {
  	
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCMod=true',
			//data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				if(data.length>0)
				{
				 llenarComboList(data,'DCMod'); 
				}else
				{
					$('#DCMod').css('display','block');
				}
			}
		});

  }


  function DCMedico()
  {
  	
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCMedico=true',
			//data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				if(data.length>0)
				{
					llenarComboList(data,'DCMedico'); 
				}else
				{
					$('#DCMedico').css('display','none');

				}
			}
		});

  }


 function DCGrupo_No()
  {
  	$('#DCGrupo_No').select2({
      placeholder: 'Grupo',
      ajax: {
        url: '../controlador/facturacion/facturarC.php?DCGrupo_No=true',
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }


  function DCLineas()
  {
  	var parametros = 
  	{
  		'Fecha':$('#MBoxFecha').val(),
  		'TC':$('#TipoFactura').val(),
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturarC.php?DCLineas=true',
		data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			llenarComboList(data,'DCLineas'); 
			Lineas_De_CxC();
		}
	});
  }

  function FPorCodigo()
  {  	
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?PorCodigo=true',
			// data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				if(data!=0)
				{
					PorCodigo = true;
				}			
			}
		});
  }

  function Lineas_De_CxC()
  { 
  	var parametros = 
  	{	'TC' :$('#TipoFactura').val(),
			'Fecha': $('#MBoxFecha').val(),
			'Cod_CxC': $('#DCLineas option:selected').text(),
			'Vencimiento':$('#MBoxFechaV').val(),
  	}

  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?Lineas_De_CxC=true',
			data:  {parametros:parametros},
			dataType:'json',
			success: function(data)
			{
				if(typeof  data === 'object')
				{
					console.log(data);
					Tipo_De_Facturacion(data);
					$('#Cant_Item_FA').val(data.Cant_Item_FA );
				}else
				{
					swal.fire(data,'','info');
				}
				
			}
		});

  }

  function Tipo_De_Facturacion(data)
  {  	 				
  	// console.log(data.Autorizacion);
  	// console.log(data.Serie);
  	// console.log(data.Porc_IVA);
				var TC = $('#TipoFactura').val();
			  if(TC == "NV"){
			     // Facturas.Caption = "INGRESAR NOTA DE VENTA"
			      $('#label2').text(data.Autorizacion+" NOTA DE VENTA No. "+data.Serie+"-");
			     $('#label3').text("I.V.A. 0.00%");
			  }else if(TC == "OP"){
			     // Facturas.Caption = "INGRESAR ORDEN DE PEDIDO"
			     $('#label2').text(data.Autorizacion +" ORDEN No. "+data.Serie+"-");
			     $('#label3').text("I.V.A. 0.00%");
			  }else{
			     // Facturas.Caption = "INGRESAR FACTURA"
			     $('#label2').text(data.Autorizacion+" FACTURA No. "+data.Serie+"-");
			     $('#label3').text("I.V.A. "+(parseFloat(data.Porc_IVA) * 100).toFixed(2)+"%")
			  }
			  // 'Facturas.Caption = Facturas.Caption & " (" & FA.TC & ")"
			  $('#label36').text("Serv. "+(data.Porc_Serv * 100).toFixed(2)+"%")
  }

  function DCEjecutivo()
  {  	
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCEjecutivo=true',
			//data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				if(data.length>0)
				{
					llenarComboList(data,'DCEjecutivo'); 
				}else
				{
					$('#DCEjecutivo').append($('<option>',{value:'.', text:'.',selected: true }));
					$('#DCMedico').css('display','none');

				}
			}
		});

  }




  function lineas_factura()
  {
  	var parametros = 
  	{
  		'codigoCliente':'',
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturarC.php?lineas_factura=true',
		// data: {parametros: parametros},
		dataType:'json',
		beforeSend: function () {	$('#tbl').html('<img src="../../img/gif/loader4.1.gif" width="40%"> ');}, 		
		success: function(data)
		{
			console.log(data);
			$('#tbl').html(data.tbl);
			$('#Mod_PVP').val(data.Mod_PVP);
			if(data.DCEjecutivo==0)
			{
				$('#DCEjecutivoFrom').css('display','none');
			}
			if(data.TextFacturaNo==0)
			{
				$('#TextFacturaNo').attr('readonly',true);
			}				 
		}
	});

  }

  

  function DCBodega()
  {
  	
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturarC.php?DCBodega=true',
		//data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			llenarComboList(data,'DCBodega'); 
		}
	});

  }


  function DCMarca()
  {
  	
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?DCMarca=true',
			//data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				llenarComboList(data,'DCMarca'); 
			}
		});

  }


  function CDesc1()
  {
  	
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?CDesc1=true',
			//data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				llenarComboList(data,'CDesc1'); 
			}
		});

  }



  function autocomplete_cliente(){
  	var grupo = $('#DCGrupo_No').val();
  	console.log(grupo);
    $('#DCCliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url: '../controlador/facturacion/facturarC.php?DCCliente=true&Grupo='+grupo,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function autocomplete_producto()
  {
  	var marca = $('#DCMarca').val();
  	var cod_marca = $('#DCMarca').val();
  	// console.log(grupo);
    $('#DCArticulos').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url: '../controlador/facturacion/facturarC.php?DCArticulos=true&marca='+marca+'&codMarca='+cod_marca,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results: data
          };
        },
        cache: true
      }
    });
  }

  function LstOrden()
  {
  	$.ajax({
			type: "POST",
			url: '../controlador/facturacion/facturarC.php?LstOrden=true',
			// data: {parametros: parametros},
			dataType:'json',
			success: function(data)
			{
				console.log(data);
			}
		});

  }

  function numero_factura()
  {  	
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturarC.php?numero_factura=true',
		// data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{
			$('#CheqSPFrom').css('display','initial');
					
			console.log(data);
		}
	});


  }

  function DCArticulo_LostFocus()
  {
  	  var parametros = {
  	  	'codigo':$('#DCArticulos').val(),
  	  	'fecha':$('#MBoxFecha').val(),
  	  	'bodega':$('#DCBodega').val(),
  	  	'marca':$('#DCMarca').val(),
  	  	'tipoFactura':$('#TipoFactura').val(),
  	  }
			$.ajax({
			  type: "POST",
			  url: '../controlador/facturacion/facturarC.php?DCArticulo_LostFocus=true',
			  data: {parametros:parametros }, 
			  dataType:'json',
			  success: function(data)
			  {
			  	$('#TextVUnit').val(data.TextVUnit);
			  	$('#LabelStock').val(data.labelstock);
			  	$('#LabelStockArt').html(data.LabelStockArt);
			  	$('#TextComEjec').val(data.TextComEjec);
			  	$('#TxtDetalle').val(data.TxtDetalle);
			  	$('#BanIVA').val(data.baniva);
			  	// $('#DCArticulos').focus();
			  	// $('#cambiar_nombre').modal('show');
			  
			  $('#cambiar_nombre').on('shown.bs.modal', function () {
					    $('#TxtDetalle').focus();
					})

			   $('#cambiar_nombre').modal('show', function () {
    					$('#TxtDetalle').focus();
					})

			  }
			})
   
  }

  function cerrar_modal_cambio_nombre()
  {
     $('#cambiar_nombre').modal('hide');
     var nuevo = $('#TxtDetalle').val();
     var dcart = $('#DCArticulos').val();
     $('#DCArticulos').append($('<option>',{value:dcart, text:nuevo,selected: true }));
     $('#TextComEjec').focus();
  }

  function TextCant_Change()
  {
  	var Real1 = 0;
  	if ($('#TextCant').val() == ""){ $('#TextCant').val(0);}
    if ($('#TextVUnit').val() == ""){ $('#TextVUnit').val(0)}
  
  	if($('#TextCant').val() != 0 && $('#TextVUnit').val() != 0) { var Real1 = $('#TextCant').val() *$('#TextVUnit').val() }
  		$('#LabelVTotal').val(Real1.toFixed(2));
  }

function TextVUnit_LostFocus()
{
	  var parametros = {
  	  	'codigo':$('#DCArticulos').val(),
  	  	'fecha':$('#MBoxFecha').val(),
  	  	'fechaV':$('#MBoxFechaV').val(),
  	  	'fechaVGR':$('#MBoxFechaV').val(), //ojo poner el verdadero
  	  	'TxtDetalle':$('#TxtDetalle').val(),
  	  	'bodega':$('#DCBodega').val(),
  	  	'marca':$('#DCMarca').val(),
  	  	//$('#DCArticulos option:selected').text(),
  	  	'Cant_Item_FA':$('#Cant_Item_FA').val(),
  	  	'tipoFactura':$('#TipoFactura').val(),
  	  	'Mod_PVP':$('#Mod_PVP').val(),
  	  	'DatInv_Serie_No':$('#DatInv_Serie_No').val(),
  	  	'TextVUnit':$('#TextVUnit').val(),
  	  	'TextCant':$('#TextCant').val(),
  	  	'TextFacturaNo':$('#TextFacturaNo').val(),
  	  	'TextComision':$('#TextComision').val(),
  	  	'CDesc1':$('#CDesc1').val(),
  	  	'BanIVA':$('#BanIVA').val(),
  	  	'TextComEjec':$('#TextComEjec').val(),
  	  	'SubCta':'.',
  	  	'Cod_Ejec':$('#DCEjecutivo').val(),
  	  	'CodigoL': $('#DCLineas').val(),
  	  	'MBFechaIn':$('#MBoxFechaV').val(), //ojo poner el verdadero
  	  	'MBFechaOut':$('#MBoxFechaV').val(), //ojo poner el verdadero
  	  	'TxtCantRooms':'.',//$('#MBoxFechaV').val(), //ojo poner el verdadero  	  	
  	  	'TxtTipoRooms':'.',//$('#MBoxFechaV').val(), //ojo poner el verdadero
  	  	'LstOrden':'.',//$('#MBoxFechaV').val(), //ojo poner el verdadero
  	  	'Sec_Public':$('#CheqSP').prop('checked'),
  	  }
			$.ajax({
			  type: "POST",
			  url: '../controlador/facturacion/facturarC.php?TextVUnit_LostFocus=true',
			  data: {parametros:parametros }, 
			  dataType:'json',
			  beforeSend: function () {	$('#tbl').html('<img src="../../img/gif/loader4.1.gif" width="40%"> ');}, 		
			  success: function(data)
			  {
			  	if(data==1)
			  	{
			  		lineas_factura();
			  	}else
			  	{
			  		swal.fire(data,'','info');
			  	}
			  	
			  }
			})
}


function Eliminar_linea(ln_No,Cod)
{
	  var parametros = {
  	  	'codigo':Cod,  	  	
  	  	'ln_No':ln_No,
  	  }
			$.ajax({
			  type: "POST",
			  url: '../controlador/facturacion/facturarC.php?Eliminar_linea=true',
			  data: {parametros:parametros }, 
			  dataType:'json',
			  success: function(data)
			  {
			  	if(data==1)
			  	{
			  	  lineas_factura();			  	
			    }
			  }
			})
   

}


// function DCLinea_LostFocus()
// {
// 	DCLinea = $("#DCLinea").val();

// }
// function numeroFactura(){
// DCLinea = $("#DCLinea").val();
// $.ajax({
//   type: "POST",
//   url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
//   data: {
//     'DCLinea' : DCLinea,
//   }, 
//   success: function(data)
//   {
//     datos = JSON.parse(data);
//     labelFac = "("+datos.autorizacion+") No. "+datos.serie;
//     document.querySelector('#numeroSerie').innerText = labelFac;
//     $("#factura").val(datos.codigo);
//   }
// });
// }
</script>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="generar_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 </div>
</div>
<div class="container">	
	<!-- //valiable  -->
	<input type="hidden" name="Mod_PVP" id="Mod_PVP" value="0">
	<input type="hidden" name="DatInv_Serie_No" id="DatInv_Serie_No" value="">
	<input type="hidden" name="Cant_Item_FA" id="Cant_Item_FA">
	<input type="hidden" name="BanIVA" id="BanIVA">
	<input type="hidden" name="" id="">
	<input type="hidden" name="" id="">
	<input type="hidden" name="" id="">
	<input type="hidden" name="" id="">

	<!-- //fin de variables -->
	<input type="hidden" name="TipoFactura" id="TipoFactura">
	<div class="row">
		<div class="col-sm-2">
			<label><input type="checkbox" name=""> Factura en MN</label>
		</div>
		<div class="col-sm-2" id="CheqSPFrom" style="display: none;">
			<label><input type="checkbox" name="CheqSP" id="CheqSP"> Sector publico</label>
		</div>
		<div class="col-sm-4">
			<b class="col-sm-4 control-label" style="padding: 0px">Orden Compra No</b>
			<div class="col-sm-8">
				<input type="" name="" id="" class="form-control input-sm">
			</div>
		</div>
		<div class="col-sm-4">
			<select class="form-control input-sm" id="DCMod" name="DCMod">
					<option value="">Seleccione</option>
				</select>
		</div>		
	</div>
	<div class="row">		
		<div class="col-sm-4">
			<b class="col-sm-4 control-label" style="padding: 0px">Cuenta x Cobrar</b>
			<div class="col-sm-8" style="padding: 0px">
				<select class="form-control input-sm" id="DCLineas" name="DCLineas" onchange="DCLinea_LostFocus()">
					<option value="">Seleccione</option>
				</select>

				<input type="hidden" name="DCLineasV" id="DCLineasV">
			</div>			
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-9" style="padding-right: 0px;">
					<b style="color:red" id="label2">0000000000000 NOTA DE VENTA No. 001001-</b>					
				</div>
				<div class="col-sm-3" style="padding-left: 0px;">
					<input type="text" name="TextFacturaNo" id="TextFacturaNo" class="form-control input-sm" value="0">	
				</div>				
			</div>
		</div>
		<div class="col-sm-4">
			<b class="col-sm-4 control-label" style="padding: 0px">Saldo pendiente</b>
			<div class="col-sm-6">
				<input type="text" name="" id="" class="form-control input-sm">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<b class="col-sm-4 control-label" style="padding: 0px">Fecha Emision</b>
			<div class="col-sm-8" style="padding: 0px">
				<input type="date" name="MBoxFecha" id="MBoxFecha" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>">
			</div>
		</div>
		<div class="col-sm-3">
			<b class="col-sm-6 control-label" style="padding: 0px">Fecha Vencimiento</b>
			<div class="col-sm-6" style="padding: 0px">
				<input type="date" name="MBoxFechaV" id="MBoxFechaV" class="form-control input-sm">
			</div>
		</div>
		<div class="col-sm-5">
			<b class="col-sm-3 control-label" style="padding: 0px">Tipo de pago</b>
			<div class="col-sm-8">
				<select class="form-control input-sm" id="DCTipoPago" name="DCTipoPago">
					<option value="">Seleccione</option>
				</select>				
			</div>			
		</div>
	</div>	
	<div class="row">
		<div class="col-sm-2">
			<b>Grupo</b>
			<select class="form-control input-sm" id="DCGrupo_No" name="DCGrupo_No" onchange="autocomplete_cliente()">
				<option value="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-3">
			<b>Cliente</b>
			<select class="form-control input-sm" id="DCCliente" name="DCCliente">
				<option value="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-2">
			<b>C.I / R.U.C</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-2">
			<b>Telefono</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-3">
			<b>ACTUALICE SU CORREO ELECTRONICO</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
	</div>
	<div class="row">
		<div class="col-sm-4">
			<b>Direccion</b>
			<input type="text" name="Label24" id="Label24" class="form-control input-sm" value="" readonly="">			
		</div>
		<div class="col-sm-4">
			<b>No</b>
			<input type="text" name="Label21" id="Label21" class="form-control input-sm" value="" readonly="">			
		</div>
		<div class="col-sm-4">
			<select class="form-control input-sm" id="DCMedico" name="DCMedico">
					<option value="">Seleccione</option>
				</select>		
		</div>		
	</div>
	<div class="row">
		<div class="col-sm-6" >
			<div id="DCEjecutivoFrom">
			<b class="col-sm-3 control-label" style="padding: 0px"><input type="checkbox" name=""> Ejecutivo de venta</b>
			<div class="col-sm-9">
				<select class="form-control input-sm" name="DCEjecutivo" id="DCEjecutivo">
					<option value="">Seleccione</option>
				</select>
			</div>
			</div>
		</div>
		<div class="col-sm-2">
			<div id="TextComisionForm" style="display:none;">
			<b class="col-sm-4 control-label" style="padding: 0px">comision%</b>
			<div class="col-sm-8">
				<input type="text" name="TextComision" id="TextComision" value="0" class="form-control input-sm">
			</div>
			</div>			
		</div>
		<div class="col-sm-4">
			<b class="col-sm-2 control-label" style="padding: 0px">Bodega</b>
			<div class="col-sm-9">
				<select class="form-control input-sm" name="DCBodega" id="DCBodega">
					<option value="">Seleccione</option>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<b>Observacion</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-6">
			<b>Nota</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
	</div>	

	<div class="row">
		<div class="col-sm-2">
			<b>Marca</b>
			<select class="form-control input-sm" id="DCMarca" name="DCMarca">
				<option value="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-4">
			<b id="LabelStockArt">Producto</b>
			<select class="form-control input-sm" name="DCArticulos" id="DCArticulos" onchange="DCArticulo_LostFocus()">
				<option value="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-1">
			<b>Stock</b>
			<input type="text" name="LabelStock" id="LabelStock" class="form-control input-sm" readonly="">
		</div>
		<div class="col-sm-1">
			<b>Ord./lote</b>
			<input type="text" name="TextComEjec" id="TextComEjec" class="form-control input-sm">
		</div>
		<div class="col-sm-1">
			<b>Desc%</b>
			<select class="form-control input-sm" id="CDesc1" name="CDesc1">
				<option value="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-1">
			<b>Cantidad</b>
			<input type="text" name="TextCant" id="TextCant" class="form-control input-sm" onblur="TextCant_Change()" value="0">
		</div>
		<div class="col-sm-1">
			<b>P.V.P</b>
			<input type="text" name="TextVUnit" id="TextVUnit" class="form-control input-sm" onblur="TextVUnit_LostFocus()" value="0">
		</div> 
		<div class="col-sm-1">
			<b>TOTAL</b>
			<input type="text" name="LabelVTotal" id="LabelVTotal" class="form-control input-sm" readonly="" value="0"> 
		</div>
		
	</div>
	<div class="row"><br>
		<div class="col-sm-12 text-center">
			<div id="tbl">				
        	
			</div>
			
		</div>
		
	</div>
	<div class="row">
		<div class="col-sm-2">
			<b>Total sin Iva</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-2">
			<b>Total con IVA</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-1">
			<b>Total Desc</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-2">
			<b id="label36">Serv. 0.00%</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-2">
			<b id="label3">I.V.A 12.00%</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-2">
			<b>Total Facturado</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-1">
			<b>P.V.P</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		
	</div>
  
</div>

 <div class="modal fade" id="cambiar_nombre" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog modal-dialog-centered modal-sm" style="margin-left: 300px; margin-top: 345px;">
      <div class="modal-content">
        <div class="modal-body text-center">
        	<textarea class="form-control" style="resize: none;" rows="4" id="TxtDetalle" name="TxtDetalle" onblur="cerrar_modal_cambio_nombre()"></textarea> 		
        </div>
      </div>
    </div>
  </div>
