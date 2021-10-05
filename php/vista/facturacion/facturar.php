<?php  require_once("panel.php");date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();?>
<script type="text/javascript">
  $(document).ready(function () {
  	var tipo = "<?php echo $_GET['tipo']; ?>";
  	$('#tipo').val(tipo);
  	 lineas_factura();

  });
  function lineas_factura()
  {
  	var parametros = 
  	{
  		'codigoCliente':'',
  	}
  	$.ajax({
		type: "POST",
		url: '../controlador/facturacion/facturarC.php?lineas_factura=true',
		data: {parametros: parametros},
		dataType:'json',
		beforeSend: function () {	$('#tbl').html('<img src="../../img/gif/loader4.1.gif" width="40%"> ');}, 		
		success: function(data)
		{
			$('#tbl').html(data.tbl);		 
		}
	});

  }
  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/divisasC.php?cliente=true',
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


function numeroFactura(){
DCLinea = $("#DCLinea").val();
$.ajax({
  type: "POST",
  url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
  data: {
    'DCLinea' : DCLinea,
  }, 
  success: function(data)
  {
    datos = JSON.parse(data);
    labelFac = "("+datos.autorizacion+") No. "+datos.serie;
    document.querySelector('#numeroSerie').innerText = labelFac;
    $("#factura").val(datos.codigo);
  }
});
}
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
	<input type="hidden" name="tipo" id="tipo">
	<div class="row">		
		<div class="col-sm-4">
			<b class="col-sm-4 control-label" style="padding: 0px">Cuenta x Cobrar</b>
			<div class="col-sm-8" style="padding: 0px">
				<select class="form-control input-sm">
					<option class="">Seleccione</option>
				</select>
			</div>			
		</div>
		<div class="col-sm-4">
			<div class="row">
				<div class="col-sm-9" style="padding-right: 0px;">
					<b style="color:red">0000000000000 NOTA DE VENTA No. 001001-</b>					
				</div>
				<div class="col-sm-3" style="padding-left: 0px;">
					<input type="text" name="" id="" class="form-control input-sm">	
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
				<input type="date" name="" id="" class="form-control input-sm">
			</div>
		</div>
		<div class="col-sm-3">
			<b class="col-sm-6 control-label" style="padding: 0px">Fecha Vencimiento</b>
			<div class="col-sm-6" style="padding: 0px">
				<input type="date" name="" id="" class="form-control input-sm">
			</div>
		</div>
		<div class="col-sm-5">
			<b class="col-sm-3 control-label" style="padding: 0px">Tipo de pago</b>
			<div class="col-sm-8">
				<select class="form-control input-sm">
					<option class="">Seleccione</option>
				</select>				
			</div>			
		</div>
	</div>
	<div class="row">
		<div class="col-sm-2">
			<b>Grupo</b>
			<select class="form-control input-sm">
				<option class="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-3">
			<b>Cliente</b>
			<select class="form-control input-sm">
				<option class="">Seleccione</option>
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
		<div class="col-sm-5">
			<b>Direccion</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-2">
			<b>No</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
	</div>	
	<div class="row">
		<div class="col-sm-8">
			
		</div>
		<div class="col-sm-4">
			<b class="col-sm-2 control-label" style="padding: 0px">Bodega</b>
			<div class="col-sm-9">
				<select class="form-control input-sm">
					<option class="">Seleccione</option>
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
			<select class="form-control input-sm">
				<option class="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-4">
			<b>Producto</b>
			<select class="form-control input-sm">
				<option class="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-1">
			<b>Stock</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-1">
			<b>Ord./lote</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-1">
			<b>Desc%</b>
			<select class="form-control input-sm">
				<option class="">Seleccione</option>
			</select>
		</div>
		<div class="col-sm-1">
			<b>Cantidad</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-1">
			<b>P.V.P</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-1">
			<b>TOTAL</b>
			<input type="text" name="" id="" class="form-control input-sm">
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
			<b>Serv. 0.00%</b>
			<input type="text" name="" id="" class="form-control input-sm">
		</div>
		<div class="col-sm-2">
			<b>I.V.A 12.00%</b>
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
