<?php
if (isset($_GET['po']))
{
?>
	<?php
		if ($_GET['po']=='subcu')
		{
	?>
	<script>
		<!-- color: #FAFE0D; background-color: #3c8dbc;-->
		//alert(document.getElementById('codigo').value);
	</script>
			<!--<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
				<div class="modal-dialog" style='width: 80%;'>
					<div class="modal-content">
					<table width='100%' height='100%' cellspacing='100%' cellpadding='100%'>
						<tr>
						    <td>
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h3>Ingreso en Subcuentas Por Pagar</h3>
								</div>
							</td>
							 <td>
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
									<h3>Ingreso en Subcuentas Por Pagar</h3>
								</div>
							</td>
						</tr>
					</table>
						<div class="modal-body">
							<div class="panel panel-default" >
								<div class="panel-heading">
									<table width='100%' height='100%' cellspacing='100%' cellpadding='100%'>
										<tr>
											<td>
												<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h4>Ingreso de Subcuentas Por Pagar</h4>
											</td>
											 
										</tr>
										<tr>
											<td>
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
													<h4>Ingreso en Subcuentas Por Pagar</h4>
												</div>
											</td>
											 <td>
												<div align="top" style="float: top;">
													<h4 align="center" id='titulosub1' style="float: top;padding: 5px 10px 5px 10px;vertical-align:top; margin-top: 1px; margin-bottom: 1px;">
														
													</h4>
												</div>
											</td>
										</tr>
										<tr>
											<td>
												<div class="col-md-8 col-sm-8 col-xs-8" id='tabla_b' style="height: 70px; overflow-y: scroll;" >
													<?php  
														$balance=ListarAsientoTem(null,'1','1','0,2,clave');
													?>
												</div>
											</td>
											 <td>
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<button type="button" class="btn btn-default btn-xs btn-block btn_f" tabindex="-1" >
																<b>Efectivizar:</b>
															</button>
														</div>
														<input type="date" class="xs" id="efecti" name='efecti' 
															placeholder="01/01/2019" value='<?php echo date('Y-m-d') ?>'>
													</div>
												</div>	
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<button type="button" class="btn btn-default btn-xs btn-block btn_f" id='ldepo' tabindex="-1" >
																<b>Deposito No:</b>
															</button>
														</div>
														<input type="text" class="xs" id="depos" name='depos' 
															placeholder="12345" >
													</div>
												</div>
											</td>
										</tr>
									</table>
									
									<div class="row " style='width: 80%;'>
											<div class="col-md-8 col-sm-8 col-xs-8" id='tabla_b' style="height: 70px; overflow-y: scroll;" >
												<?php  
													$balance=ListarAsientoTem(null,'1','1','0,2,clave');
												?>
											</div>
											<div class="col-md-2 col-sm-2 col-xs-2">
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<button type="button" class="btn btn-default btn-xs btn-block btn_f" tabindex="-1" >
															<b>Efectivizar:</b>
														</button>
													</div>
													<input type="date" class="xs" id="efecti" name='efecti' 
														placeholder="01/01/2019" value='<?php echo date('Y-m-d') ?>'>
												</div>
											</div>	
											<div class="col-md-2 col-sm-2 col-xs-2">
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<button type="button" class="btn btn-default btn-xs btn-block btn_f" id='ldepo' tabindex="-1" >
															<b>Deposito No:</b>
														</button>
													</div>
													<input type="text" class="xs" id="depos" name='depos' 
														placeholder="12345" >
												</div>
											</div>
											<div class="col-md-8 col-sm-8 col-xs-8" id='tabla_b' style="height: 70px; overflow-y: scroll;" >
												<?php  
													$balance=ListarAsientoTem(null,'1','1','0,2,clave');
												?>
											</div>
									</div>
									<div class="row " style='width: 80%;'>
										<?php  
											$balance=ListarAsientoTem(null,'1','1','0,2,clave');
										?>
									</div>
									<div class="row " style='width: 100%;'>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<div class="input-group">
											
												<div class="input-group-btn">
													<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>Diferencia:</b></button>
												
												</div>
												
												<input type="text" class="xs" id="diferencia" name="diferencia" placeholder="0.00" value="0,00" style="width:100%;text-align:right; ">
												
											</div>
										</div>
									</div>
									<div class="row " style='width: 100%;'>
										<div class="col-md-2 col-sm-2 col-xs-2">
											<div class="input-group">
											
												<div class="input-group-btn">
													<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>Diferencia:</b></button>
												
												</div>
												
												<input type="text" class="xs" id="diferencia" name="diferencia" placeholder="0.00" value="0,00" style="width:100%;text-align:right; ">
												
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<a href="#" data-dismiss="modal" class="btn btn-danger">Cerrar</a>
						</div>
					</div>
					
				</div>
			</div>-->
			<!--<div class="modal-dialog" role="document" id="mostrarmodal">
				<div class="modal-content">
				  <div class="modal-header">
					<h5 class="modal-title" id="gridModalLabel">Grids in modals</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				  </div>
				  <div class="modal-body">
					<div class="container-fluid bd-example-row">
					  <div class="row">
						<div class="col-md-4">.col-md-4</div>
						<div class="col-md-4 ml-auto">.col-md-4 .ml-auto</div>
					  </div>
					  <div class="row">
						<div class="col-md-3 ml-auto">.col-md-3 .ml-auto</div>
						<div class="col-md-2 ml-auto">.col-md-2 .ml-auto</div>
					  </div>
					  <div class="row">
						<div class="col-md-6 ml-auto">.col-md-6 .ml-auto</div>
					  </div>
					  <div class="row">
						<div class="col-sm-9">
						  Level 1: .col-sm-9
						  <div class="row">
							<div class="col-8 col-sm-6">
							  Level 2: .col-8 .col-sm-6
							</div>
							<div class="col-4 col-sm-6">
							  Level 2: .col-4 .col-sm-6
							</div>
						  </div>
						</div>
					  </div>
					</div>
				  </div>
				  <div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				  </div>
				</div>
			</div>-->
			<div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true" >
				<div class="modal-dialog" role="document" style='width: 70%;'>
					<div class="modal-content">
					  <div class="modal-header" style='color: #FAFE0D; background-color: #3c8dbc;height: 50%'>
						<h5 class="modal-title" id="gridModalLabel">Ingreso en Subcuentas Por Pagar</h5>
						<!--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>-->
					  </div>
					  <div class="modal-body">
						<div class="container-fluid bd-example-row">
						  <div class="row">
								<div class="row">
									<div class="col-sm-12">
										<div class="row">
											<div class="col-8 col-sm-6">
												<h5 align="center"  id='titulosub2'
												style="background-color: rgba(86,61,124,.15); border: 1px solid rgba(86,61,124,.2);float: top;padding: 5px 10px 5px 10px;vertical-align:top; margin-top: 1px; margin-bottom: 1px;">
													
												</h5>
											</div>
											<div class="col-8 col-sm-6">
												<h5 align="center" id='titulosub1' 
														style="background-color: rgba(86,61,124,.15); border: 1px solid rgba(86,61,124,.2);float: top;padding: 5px 10px 5px 10px;vertical-align:top; margin-top: 1px; margin-bottom: 1px;">
												</h5>
											</div>
											
										</div>
										
											
										<div class="row">
										  <div class="col-8 col-sm-6" style="height: 70px; ">
											<div id='subcuenta1'>
												
											</div>
										  </div>
										  <div class="col-md-2 col-sm-2 col-xs-2">
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<button type="button" class="btn btn-default btn-xs btn-block btn_f" tabindex="-1" 
														style='width:90%; ' align='right'>
															<b>fecha ven:</b>
														</button>
													</div>
													<input type="date" class="xs" id="fecha_sc" placeholder="01/01/2019" name='fecha_sc' 
														 value='<?php echo date('Y-m-d') ?>' maxlength='10' size='10' style='width:90%; '>
													
												</div>
											</div>
											<div class="col-md-1 col-sm-1 col-xs-1">
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<button type="button" class="btn btn-default btn-xs btn-block btn_f" tabindex="-1" 
														style='width:100%; ' align='right'>
															<b>Factura No:</b>
														</button>
													</div>
												<div id='facturas1'>
													<input type="text" class="xs" id="fac2" name='fac2' 
														placeholder="Factura" maxlength='30' size='12' value='0'>
												</div>
												</div>
											</div>
											<div class="col-md-1 col-sm-1 col-xs-1">
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<button type="button" class="btn btn-default btn-xs btn-block btn_f" tabindex="-1" 
														style='width:100%; ' align='right'>
															<b>Meses:</b>
														</button>
													</div>
													<input type="text" class="xs" id="mes" name='mes' 
														placeholder="Meses" maxlength='30' size='8' value='0'>
												</div>
											</div>
											<div class="col-md-1 col-sm-1 col-xs-1">
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<button type="button" class="btn btn-default btn-xs btn-block btn_f" tabindex="-1" 
														style='width:100%; ' align='right'>
															<b>Valor M/N:</b>
														</button>
													</div>
													<input type="text" class="xs" id="valorn" name='valorn' 
														placeholder="Valor" maxlength='30' size='12'>
												</div>
											</div>
										  <!--<div class="col-8 col-sm-6" style="height: 70px; ">
												
										  </div>-->
										</div>
										<div class="row">
											<div class="col-md-6 col-sm-6 col-xs-6">
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														
													</div>
													
												</div>
											</div>
											<div class="col-md-6 col-sm-6 col-xs-6">
												<div class="input-group mb-2">
													<div class="input-group-prepend">
														<button type="button" class="btn btn-default btn-xs btn-block btn_f" tabindex="-1" 
														style='width:100%; ' align='right'>
															<b>Detalle auxiliar de submodulo:</b>
														</button>
													</div>
													<select class="form-control" name="Trans_Sub" id='Trans_Sub' onBlur="agregarsc();">
														<option value='0'>Seleccione Entidad</option>
														<?php select_option('Trans_SubCtas','Detalle_SubCta','Detalle_SubCta',
														"  (Periodo = '".$_SESSION['INGRESO']['periodo']."') AND (Item = '".$_SESSION['INGRESO']['item']."') GROUP BY Detalle_SubCta "); ?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
											
							<script>
								//document.getElementById("subcuenta").focus();
								//$('#subcuenta').focus();
							</script>							
						  </div>
						  <div id='facturas2'>
							  <div class="row">
								<div class="col-md-16 col-sm-16 col-xs-16" id='tabla_b' style="height: 70px; overflow-y: scroll;">
									<?php  
										$balance=ListarAsientoSc(null,NULL,'1','8,9,10,11,clave1');
									?>
									<input type="hidden" id='reg1' name='reg1'  value='' />
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
						  </div >
						  
						 <!-- <div class="row">
							<div class="col-md-6 ml-auto">.col-md-6 .ml-auto</div>
						  </div>
						  <div class="row">
							<div class="col-sm-9">
							  Level 1: .col-sm-9
							  <div class="row">
								<div class="col-8 col-sm-6">
								  Level 2: .col-8 .col-sm-6
								</div>
								<div class="col-4 col-sm-6">
								  Level 2: .col-4 .col-sm-6
								</div>
							  </div>
							</div>
						  </div>-->
						</div>
					  </div>
					  <div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="button" class="btn btn-primary">Continuar</button>
					  </div>
					</div>
				</div>
			</div>
	<?php
		}
	?>
<?php
}
?>
	
