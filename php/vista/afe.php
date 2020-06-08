<?php
/**
 * Autor: Orlando Quintero.
 * Mail:  filvovmax@gmail.com
 * web:   www.diskcoversystem.com
 */
//require_once 'determ.php';
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once("panel.php");
require_once("chequear_seguridad_e.php");
require_once("../controlador/afe.php");
	//echo ' jjj '.$_SESSION['autentificado'];
	//die();
	//cuerpo
?>
	<div class="panel panel-info">
		<div class="panel-heading">
			<p class="box-title">Autorizacion Facturas electronicas</p>
		</div>
	</div>
	<!-- <h3 class="box-title">Entidad </h3>-->
		<div class="row">
			<div class="col-xs-12">
			  <div class="box">
				<div class="box-header">
					
				 <!-- <h3 class="box-title">Responsive Hover Table</h3>

				  <div class="box-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
					  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

					  <div class="input-group-btn">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					  </div>
					</div>
				  </div>-->
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
					<form role="form" enctype="multipart/form-data" action="../controlador/afe.php" method="POST">
						<h3 class="box-title">Datos factura</h3>
						<div class="box-body">
							<div class="col-md-3">
							  <div class="form-group">
								<label id="resultado" for="exampleInputEmail1">RUC/CI </label>
								
								<input type="hidden" class="form-control" id="item" 
								name="item" value='<?php echo $_SESSION['INGRESO']['item'];?>'>
								<input type="text" class="form-control" id="RUC" placeholder="1111111111" 
								name="RUC" onkeyup=''>
							  </div>
							 </div>
							 <div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Factura</label>
								<input type="text" class="form-control" id="nfactura" placeholder="111"
								name="nfactura">
							  </div>
						    </div>
							 <div class="col-md-3">
							   <div class="form-group">
								<label>Fecha:</label>
								<div class="input-group date">
								  <div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </div>
								  <input type="text" class="form-control pull-right" id="datepicker" placeholder="01/01/2019"
								  name="fecha">
								</div>
							   </div>
							  <!-- /.form-group -->
							</div>
							
							
						</div>
						<div class="box-body">
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Subtotal</label>
								<input type="text" class="form-control" id="subtotal" placeholder="Subtotal" 
								name="subtotal">
							  </div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="exampleInputEmail1">IVA</label>
									<input type="text" class="form-control" id="iva" placeholder="IVA" 
									name="iva">
							    </div>
							  <!-- /.form-group -->
							</div>
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Descuento</label>
								<input type="text" class="form-control" id="descuento" placeholder="Descuento"
								name="descuento">
							  </div>
						    </div>
						</div>
						<div class="box-body">
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Servicio</label>
								<input type="text" class="form-control" id="servicio" placeholder="Servicio" 
								name="servicio">
							  </div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="exampleInputEmail1">Propinas</label>
									<input type="text" class="form-control" id="propinas" placeholder="Propinas" 
									name="propinas">
							    </div>
							  <!-- /.form-group -->
							</div>
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Total</label>
								<input type="text" class="form-control" id="total" placeholder="Total"
								name="total">
							  </div>
						    </div>
						</div>
						<div class="box-body">
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Forma pago</label>
								<input type="text" class="form-control" id="forma_pago" placeholder="Forma pago" 
								name="forma_pago">
							  </div>
							</div>
							<div class="col-md-3">
								<div class="form-group">
									<label for="exampleInputEmail1">Serie</label>
									<input type="text" class="form-control" id="serie" placeholder="Serie" 
									name="serie">
							    </div>
							  <!-- /.form-group -->
							</div>
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Anulada</label>
								<input type="text" class="form-control" id="anulada" placeholder="Anulada"
								name="anulada">
							  </div>
						    </div>
						</div>
						<div class="box-body">
							<div class="col-md-7">
							  <div class="form-group">
								<label for="exampleInputEmail1">Cliente</label>
								<input type="text" class="form-control" id="cliente" placeholder="Cliente"
								name="cliente">
							  </div>
							</div>
						</div>
						<h3 class="box-title">Detalles factura</h3>
						<div class="box-body">
							<div class="col-md-7">
							  <div class="form-group">
								<label for="exampleInputEmail1">Detalle</label>
								<input type="text" class="form-control" id="detalle" placeholder="Detalle"
								name="detalle">
							  </div>
							</div>
						</div>
						<div class="box-body">
							<div class="col-md-3">
								<div class="form-group">
									 <label>Fecha vencimiento:</label>
									<div class="input-group date">
									  <div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									  </div>
									  <input type="text" class="form-control pull-right" id="datepickerv" placeholder="01/01/2019"
									  name="fechav">
									</div>
								</div>
							</div>
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Codigo Inv</label>
								<input type="text" class="form-control" id="codigo_inv" placeholder="Codigo Inv"
								name="codigo_inv">
							  </div>
							  <!-- /.form-group -->
							</div>
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Mes abono</label>
								<input type="text" class="form-control" id="mes_abono" placeholder="Mes abono"
								name="mes_abono">
							  </div>
							  <!-- /.form-group -->
							</div>
						</div>
						<div class="box-body">
							<div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Cta abono</label>
								<input type="text" class="form-control" id="cta_abono" placeholder="Cta abono"
								name="cta_abono">
							  </div>
							 </div>
							 <div class="col-md-3">
							  <div class="form-group">
								<label for="exampleInputEmail1">Detalle abono</label>
								<input type="text" class="form-control" id="detalle abono" placeholder="Detalle abono"
								name="detalle_abono">
							  </div>
							  <!-- /.form-group -->
							</div>
						</div>
						<div class="box-footer">
							<input type="submit" name="submitweb" class="btn btn-primary" value="Enviar datos" />
						</div>
					</form>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->
			</div>
		</div>
	<script>
	//Date picker
    $('#datepicker').datepicker({
      autoclose: true
    });
	$('#datepickerv').datepicker({
      autoclose: true
    });
	//para llamar validaciones mediante ajax
	 $("#RUC").keydown(function(e) {
		 var RUC = $("#RUC").val();
		 var item = $("#item").val();
		 //tecla tab
		 var code = e.keyCode || e.which;
		 //eliminamos cookies
		 document.cookie = "nombre=; max-age=0";
		 //alert(code);
		if (code == '9') {
			//alert(RUC);
			var parametros = 
			{
				"RUC" : RUC,
				"vista" : 'afe',
				"idMen" : 'idMen',
				"item" : item
			};
			$.ajax({
				data:  parametros,
				url:   '../funciones/funciones.php',
				type:  'post',
				beforeSend: function () {
						$("#resultado").html("");
				},
				success:  function (response) {
						$("#resultado").html("");
						$("#resultado").html(response);
						 var valor = $("#resultado").html();
						 document.cookie = "nombre=1; ";
						 if(valor=='RUC/CI (P)')
						 {
							if(confirm("¿EL CODIGO INGRESADO, NO ES NI CEDULA NI RUC(NIC); ESTE CODIGO ES DE UN PASAPORTE?"))
							{
								
							}
							else
							{
								$("#RUC").val('');
								$("#resultado").html('RUC/CI  <p style="color:#FF0000;">ingrese un RUC/CI correcto</p>');
								$("#RUC").focus();
								$("#RUC").select();
							}
						}
				}
			});
		}
		/*if (window.event.keyCode == 9)
		{
			alert(RUC);
		}*/
    });
	//caso click factura
	$("#nfactura").click(function(e) {
		var RUC = $("#RUC").val();
		 var item = $("#item").val();
		 //tecla tab
		 var code = e.keyCode || e.which;
		 //alert(code);
		var entro = readCookie('nombre');
			//alert(RUC);
			if(entro!='1')
			{
				var parametros = 
				{
					"RUC" : RUC,
					"vista" : 'afe',
					"idMen" : 'idMen',
					"item" : item
				};
				$.ajax({
					data:  parametros,
					url:   '../funciones/funciones.php',
					type:  'post',
					beforeSend: function () {
							$("#resultado").html("");
					},
					success:  function (response) {
							$("#resultado").html("");
							$("#resultado").html(response);
							 var valor = $("#resultado").html();
							 document.cookie = "nombre=1; ";
							 if(valor=='RUC/CI (P)')
							 {
								if(confirm("¿EL CODIGO INGRESADO, NO ES NI CEDULA NI RUC(NIC); ESTE CODIGO ES DE UN PASAPORTE?"))
								{
									
								}
								else
								{
									$("#RUC").val('');
									$("#resultado").html('RUC/CI  <p style="color:#FF0000;">ingrese un RUC/CI correcto</p>');
									$("#RUC").focus();
									$("#RUC").select();
								}
							}
					}
				});
			}
		
		/*if (window.event.keyCode == 9)
		{
			alert(RUC);
		}*/
    });
	</script>
<?php

	require_once("footer.php");
	
?>			
	
