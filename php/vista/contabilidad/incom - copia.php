<?php

    if(!isset($_SESSION)) 
	 		session_start();
		$_SESSION['INGRESO']['ti']='Ingresar Comprobantes (Crtl+f5)';
		//echo $_SESSION['INGRESO']['Id']; 
	//datos para consultar
	//CI_NIC
	//echo $_SESSION['INGRESO']['Opc'].' '.$_SESSION['INGRESO']['Sucursal'].' '.$_SESSION['INGRESO']['item'].' '.$_SESSION['INGRESO']['periodo'].' ';

?>

<!--<h2>Balance de Comprobacion/Situación/General</h2>-->
<!--
<style>
	.nav{
		background-color: #336683;
		padding: 25px;
	}

	.nav-tabs {
		border-bottom: none !important;
	}

	#tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
		color: #336683 !important;
		background-color: #a4d6ef !important;
		border-color: transparent transparent #f3f3f3;
	}
	#tabs .nav-tabs .nav-link {
		border: 1px solid transparent;
		color: #eee;
		font-size: 20px;
		background-color: #336683;
		border: 1px solid #a4d6ef;
		color: #a4d6ef;
	}

	.nav-item {
		border-radius: 0 !important;
	}

	.nav-item:last-child {
		border-top-right-radius: .5rem !important;
		border-bottom-right-radius: .5rem !important;
	}

	.nav-item:first-child {
		border-top-left-radius: .5rem !important;
		border-bottom-left-radius: .5rem !important;
	}

	.nav-tabs .nav-link.active{
	   background-color: #a4d6ef !important; 
	}
</style>
-->
<style>
	.typeahead { border: 2px solid #FFF;border-radius: 4px;padding: 5px 12px;max-width: 300px;min-width: 290px;background: #f5f5f5;color: #000;}
	.tt-menu { width:300px; }
	ul.typeahead{margin:0px;padding:2px 0px;}
	ul.typeahead.dropdown-menu li a {padding: 2px !important;	border-bottom:#CCC 1px solid;color:#000;}
	ul.typeahead.dropdown-menu li:last-child a { border-bottom:0px !important; }
	.bgcolor {max-width: 550px;min-width: 290px;max-height:340px;background:url("world-contries.jpg") no-repeat center center;padding: 100px 10px 130px;border-radius:4px;text-align:center;margin:10px;}
	.demo-label {font-size:1.5em;color: #686868;font-weight: 500;color:#FFF;}
	.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
		text-decoration: none;
		/*background-color: #1f3f41;*/
		background-color: #0086c7;
		color: #FFFFFF;
		outline: 0;
	}
	.btn_f
	{
		background-color: #CFE9EF;
		color: #444;
		border-color: #ddd;
	}
</style>
<style>
	* {
		  box-sizing: border-box;
		}

		.form1 {
		  padding: 1em;
		  background: #f9f9f9;
		  border: 1px solid #c1c1c1;
		  margin-top: 2rem;
		  max-width: 600px;
		  margin-left: auto;
		  margin-right: auto;
		  padding: 1em;
		}
		.form1 input {
		  margin-bottom: 1rem;
		  background: #fff;
		  border: 1px solid #9c9c9c;
		}
		.form1 button {
		  background: lightgrey;
		  padding: 0.7em;
		  border: 0;
		}
		.form1 button:hover {
		  background: gold;
		}

		.label1 {
		  text-align: Left;
		  display: block;
		  padding: 0.5em 1.5em 0.5em 0;
		  background-color: #CFE9EF;
		  font-size: 10px;
		}

		.input1 {
		  width: 100%;
		  padding: 0.7em;
		  margin-bottom: 0.5rem;
		}
		.input1:focus {
		  outline: 3px solid blue;
		}

		@media (min-width: 400px) {
		  form {
			overflow: hidden;
		  }

		  .label1 {
			float: left;
			width: 100%;
		  }

		  .input1 {
			float: left;
			width: calc(50%);
		  }

		  .button1 {
			float: right;
			width: calc(100% - 200px);
		  }
		}
		.marco 
		{
			border: 1px solid #ddd;;
			padding: 0.5em 1em 1em 1em;
			height: 113px;
			position:absolute;
			top: 19%;
			left: 1%;
			font-size: 10px;
			width: 130px;
			/*overflow: scroll;*/
		}
</style>
<style>
	.xs {
	  border: 1px dotted #CFE9EF;
	  border-radius: 0;

	  -webkit-appearance: none;
	}
	.xs1 {
	  border: 1px dotted #999;
	  border-radius: 0;

	  -webkit-appearance: none;
	}
</style>
<div class="panel box box-primary">
	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		
		<div width='100%'>
			<div style=" float: left;width:30%" align='left' width='30%'>
				<button type="button" class="btn btn-default btn-xs active" onclick="reset_('comproba','CD');" 
				id='CD' style="width: 15%;">Diario</button>
				<button type="button" class="btn btn-default btn-xs" onclick="reset_('comproba','CI');" 
				id='CI' style="width: 15%;">Ingreso</button>
				<button type="button" class="btn btn-default btn-xs" onclick="reset_('comproba','CE');" 
				id='CE' style="width: 15%;">Egreso</button>
				<button type="button" class="btn btn-default btn-xs" onclick="reset_('comproba','ND');" 
				id='ND' style="width: 15%;">N/D</button>
				<button type="button" class="btn btn-default btn-xs" onclick="reset_('comproba','NC');" 
				id='NC' style="width: 15%;">N/C</button>
				<input id="tipoc" name="tipoc" type="hidden" value="CD">
			</div>											
			<div align='' width='40%'  style="float: left;width:40%; ">
				<div align='top' style="float: top;">
					<h3 align='center' style="float: top;padding: 5px 10px 5px 10px;vertical-align:top; margin-top: 1px; margin-bottom: 1px;">
						Comprobante de Diario No. 2019-07000059
					</h3>
				</div>
			</div>
		
			<div class="checkbox" align='right' width='30%' style=" float: right;width:30%">
				<label>
					<input type="checkbox"> Imprimir copia
				</label>
			</div>
		</div>
		<div class="box table-responsive">
		
            <div class="box-header">
              <!--<h3 class="box-title">Striped Full Width Table</h3>-->
			  <table>
				<tr>
					<td>
						<div class="loader1"></div>
					</td>
				</tr>
			  </table>
			<?php
					
					?>
					
					<?php
					$texto[0]=1;
					if(count($texto)>0)
							{
					?>	
								<form action="#" class="credit-card-div">
									<div class="panel panel-default" >
										
										<div class="panel-heading">
											<table width='100%' height='100%' cellspacing='100%' cellpadding='100%'>
												<tr>
													<td >
														
													</td>
												</tr>
												<!--<tr>
													<td>
														<div class="form-group">
															<!--style='max-width: 6%;'-->
												<!--			<div style=" float: left;width:30%" align='left' width='30%'>
																<label for="ejemplo_email_3" class="col-lg-3 control-label" >FECHA:</label>
																<div class="col-lg-5">
																  <input type="date" class="form-control input-sm" id="ejemplo_email_3"
																		 placeholder="01/01/2019" >
																</div>
															</div>
															<div style=" float: left;width:40%" align='left' width='40%'>											
																<label for="ejemplo_email_3" class="col-xs-3 control-label" >CLIENTE:</label>
																<div class="col-lg-6">
																  <input type="text" class="form-control input-sm" id="ejemplo_email_3"
																		 placeholder="cliente" >
																</div>
															</div>
															<div style=" float: left;width:30%" align='left' width='30%'>
																<label for="ejemplo_email_3" class="col-lg-3 control-label" >R.U.C / C.I:</label>
																<div class="col-lg-5">
																  <input type="text" class="form-control input-sm" id="ejemplo_email_3"
																		 placeholder="0000000000000">
																</div>
															</div>
														</div>
													</td>
												</tr>
												-->
											</table>
											<div class="row ">
												
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group">
														
														<div class="input-group-btn">
															<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1" >
																<b>FECHA:</b>
															</button>
														
														</div>
														
														<input type="date" class="xs" id="fecha1" placeholder="01/01/2019" 
														 value='<?php echo date('Y-m-d') ?>' maxlength='10' size='15'>
													</div>
												</div>
												<div class="col-md-7 col-sm-7 col-xs-7">
													<div class="input-group">
													
														<div class="input-group-btn">
															<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>BENEFICIARIO:</b></button>
														
														</div>
														
														<input type="text" class="xs" id="beneficiario" name='beneficiario' 
														placeholder="beneficiario" value='.' maxlength='120' size='103'>
														<div id="bener">
															<input type="hidden" name="beneficiario1" id="beneficiario1" value='' />
														</div>
														<input type="hidden" name="beneficiario2" id="beneficiario2" value='' />
													</div>
												</div>
												<div class="col-md-3 col-sm-3 col-xs-3">
													<div class="input-group">
													
														<!--<div class="input-group-btn">
															<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1"><b>R.U.C / C.I:</b></button>
														
														</div>
														
														<input type="text" class="form-control input-sm" id="ruc" name='ruc' placeholder="R.U.C / C.I" 
														value='000000000'>-->
														<div class="input-group-btn">
															<button type="button" class="btn btn-default btn-xs btn_f" tabindex="-1"><b>R.U.C / C.I:</b></button>
														
														</div>
														
														<input type="text" class="xs" id="ruc" name='ruc' placeholder="R.U.C / C.I" 
														value='000000000'>
													</div>
												</div>
												<!--<div class="col-md-12">
													<input type="text" class="form-control input-sm" placeholder="Enter Card Number" />
													 <input type="date" class="form-control input-sm" id="fecha1" placeholder="01/01/2019" >
												</div>-->
											</div>
											<div class="row ">
												
												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="input-group">
														
														<div class="input-group-btn">
															<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1"><b>EMAIL:</b></button>
														
														</div>
														
														<input type="email" class="form-control input-sm" id="email" name="email" placeholder="prueba@prueba.com" >
													</div>
												</div>
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group">
													
														<div class="input-group-btn">
															<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1"><b>COTIZACIÓN:</b></button>
														
														</div>
														
														<input type="text" class="form-control input-sm" id="cotizacion" name='cotizacion' 
														placeholder="cotizacion" style="text-align:right;">
													</div>
												</div>
												<!--<div class="col-md-2 col-sm-2 col-xs-2">
													<label>
														conversion
													</label>
													<div class="input-group">
														<span class="input-group-addon">
															<input type="radio" class="" id="con1" name='con' >
														</span>
														(/)
														<span class="input-group-addon">
															<input type="radio" class="" id="con2" name='con' >
														</span>
														(X)
													</div>
												</div>-->
												 <div class="col-md-2 col-sm-2 col-xs-8 form-group">
													  <label class="labeltext">Tipo de conversión</label><br>
														<div class="form-check-inline">
															<label class="customradio"><span class="radiotextsty">(/)</span>
															  <input type="radio" checked="checked" name="con">
															  <span class="checkmark"></span>
															</label>        
															<label class="customradio"><span class="radiotextsty">(X)</span>
															  <input type="radio" name="con">
															  <span class="checkmark"></span>
															</label>
														</div>
												</div>
												
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group">
													
														<div class="input-group-btn">
															<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1"><b>VALOR TOTAL:</b></button>
														
														</div>
														
														<input type="text" class="form-control input-sm" id="VT" name='VT' placeholder="0.00" 
														style="text-align:right;" >
													</div>
												</div>
												
												<!--<div class="col-md-12">
													<input type="text" class="form-control input-sm" placeholder="Enter Card Number" />
													 <input type="date" class="form-control input-sm" id="fecha1" placeholder="01/01/2019" >
												</div>-->
											</div>
											<div id='ineg' style='display:none;'>
												<div class="row ">
													<div class="col-md-1 col-sm-1 col-xs-1">
														<span class="button-checkbox">
															<button type="button" class="btn btn-xs btn-primary active" data-color="primary">
																<i class="state-icon glyphicon glyphicon-check"></i>&nbsp;Efectivo&nbsp;&nbsp;
															</button>
															<input type="checkbox" id='efec' name='efec' class="hidden" />
														</span>
													</div>
													<div id='ineg1' style='display:none;'>
														<div class="col-md-9 col-sm-9 col-xs-9">
															<div class="input-group">
																
																<div class="input-group-btn">
																	<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1">
																		<b>CUENTA:</b>
																	</button>
																
																</div>
																<select class="form-control form-control-sm" name="conceptoe" id='conceptoe' >
																	<?php select_option('Catalogo_Cuentas','Codigo','Codigo,Cuenta',
																	'  (TC = \'CJ\') AND (DG = \'D\') AND (Periodo = \''.$_SESSION['INGRESO']['periodo'].'\') 
																	AND (Item = \''.$_SESSION['INGRESO']['item'].'\')  ORDER BY Cuenta '); ?>
																</select>
																<!--<input type="text" class="form-control input-sm" id="conceptoe" name="conceptoe" 
																placeholder="concepto" >-->
															</div>
														</div>
														<div class="col-md-2 col-sm-2 col-xs-2">
															<div class="input-group">
															
																<div class="input-group-btn">
																	<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1">
																		<b><?php echo $_SESSION['INGRESO']['S_M']; ?>:</b>
																	</button>
																
																</div>
																
																<input type="text" class="form-control input-sm" id="vae" name='vae' placeholder="0.00" 
																style="text-align:right;" >
															</div>
														</div>
													</div>
												</div>
												<div class="row ">
													<div class="col-md-1 col-sm-1 col-xs-1">
														<span class="button-checkbox1">
															<button type="button" class="btn btn-xs btn-primary active" data-color="primary">
																<i class="state-icon glyphicon glyphicon-check"></i>&nbsp;Banco&nbsp;&nbsp;&nbsp;
																&nbsp;&nbsp;
															</button>
															<input type="checkbox" id='ban' name='ban' class="hidden" checked/>
														</span>
													</div>
													<div id='ineg2' style='display:none;'>
														<div class="col-md-9 col-sm-9 col-xs-9">
															<div class="input-group">
																
																<div class="input-group-btn">
																	<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1"><b>CUENTA:</b></button>
																
																</div>
																
																<select class="form-control form-control-sm" name="conceptob" id='conceptob' >
																	<?php select_option('Catalogo_Cuentas','Codigo','Codigo,Cuenta',
																	'  (TC = \'BA\') AND (DG = \'D\') AND (Periodo = \''.$_SESSION['INGRESO']['periodo'].'\') 
																	AND (Item = \''.$_SESSION['INGRESO']['item'].'\')  ORDER BY Cuenta '); ?>
																</select>
																<!--<input type="text" class="form-control input-sm" id="conceptob" 
																name="conceptob" placeholder="concepto" >-->
															</div>
														</div>
														<div class="col-md-2 col-sm-2 col-xs-2">
															<div class="input-group">
															
																<div class="input-group-btn">
																	<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1">
																		<b><?php echo $_SESSION['INGRESO']['S_M']; ?>:</b>
																	</button>
																
																</div>
																
																<input type="text" class="form-control input-sm" id="vab" name='vab' placeholder="0.00" 
																style="text-align:right;" >
															</div>
														</div>
													</div>
												</div>
												<div class="row ">
													<div id='ineg3' style='display:none;'>
														<div class="col-md-8 col-sm-8 col-xs-8" id='tabla_b' style="height: 70px; overflow-y: scroll;" >
															<?php  
																$balance=ListarAsientoTem(null,'1','1','0,2,clave');
															?>
															<input type="hidden" id='reg1' name='reg1'  value='' />
														</div>
														<div class="col-md-2 col-sm-2 col-xs-2">
															<div class="input-group mb-2">
																<div class="input-group-prepend">
																	<button type="button" class="btn btn-default btn-sm btn-block btn_f" tabindex="-1" >
																		<b>Efectivizar:</b>
																	</button>
																</div>
																<input type="date" class="form-control input-sm" id="efecti" name='efecti' 
																	placeholder="01/01/2019" value='<?php echo  date('Y-m-d') ?>'>
															</div>
														</div>	
														<div class="col-md-2 col-sm-2 col-xs-2">
															<div class="input-group mb-2">
																<div class="input-group-prepend">
																	<button type="button" class="btn btn-default btn-sm btn-block btn_f" tabindex="-1" >
																		<b>Deposito No:</b>
																	</button>
																</div>
																<input type="text" class="form-control input-sm" id="depos" name='depos' 
																	placeholder="12345" >
															</div>
														</div>	
													</div>
												</div>
											</div>
												
											<div class="row ">
												
												<div class="col-md-12 col-sm-12 col-xs-12">
													<div class="input-group">
														
														<div class="input-group-btn">
															<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1"><b>CONCEPTO:</b></button>
														
														</div>
														
														<input type="text" class="form-control input-sm" id="concepto" name="concepto" placeholder="concepto" >
													</div>
												</div>
												
											</div>
											
											<div class="row ">
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group mb-2">
														<div class="input-group-prepend">
															<button type="button" class="btn btn-default btn-sm btn-block btn_f" tabindex="-1" ><b>CODIGO:</b></button>
														</div>
														<input type="text" class="form-control input-sm" id="codigo" name='codigo' 
															placeholder="codigo">
													</div>
												</div>
												<div class="col-md-8 col-sm-8 col-xs-8">
													<div class="input-group mb-8 btn-block">
														<div class="input-group-prepend">
															<button type="button" class="btn btn-default btn-sm btn-block btn_f" tabindex="-1" >
																<b>DIGITE LA CLAVE O SELECCIONE LA CUENTA:</b>
															</button>
														</div>
														<input type="text" class="form-control input-sm" id="cuenta" name='cuenta' 
															placeholder="cuenta">
														<input type="hidden" id='codigo_cu' name='codigo_cu'  value='' />
														<input type="hidden" id='TC' name='TC'  value='' />
														<div id="cuentar">
															
														</div>
														<!--<div id="cuentar1">
															<input type="hidden" id='codigo_cu' name='codigo_cu'  value='' />
														</div>-->
													</div>
												</div>
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group mb-2">
														<div id='valoro'>
														<div class="input-group-prepend">
															<button type="button" class="btn btn-default btn-sm btn-block btn_f" tabindex="-1" >
																<b>VALOR:</b>
															</button>
														</div>
														<input type="text" class="form-control input-sm" id="va" name='va' 
															placeholder="0.00" style="text-align:right;">
														</div>
														
														<div id='tcom' style='display:none;'>
															<div class="marco">
																	<!--<form class="form1" action="">
																		<label for="firstName" class="">
																			<div style='position:absolute;top: 3%;left: 40%;'>
																				<b>Valores:</b>
																			</div>
																			<div style='position:absolute;top: 13%;left: 1%;'>
																				<b>1 M/N</b>
																			</div>
																			<div style='position:absolute;top: 21%;left: 1%;'>
																				<b>2 M/E</b>
																			</div>
																		</label>
																		<div style='position:absolute;top: 13%;left: 32%;'>
																			<input id="moneda" name='moneda' class="" type="text" maxlength="1" size="5">
																		</div>
																		<label for="lastName" class="">
																			<div style='position:absolute;top: 32%;left: 1%;'>
																				<b>Debe 1:</b>
																			</div>
																			<div style='position:absolute;top: 40%;left: 1%;'>
																				<b>Haber 2</b>
																			</div>
																		</label>
																		<div style='position:absolute;top: 32%;left: 32%;'>
																			<input id="tipo_cue" name='tipo_cue' class="" type="text" maxlength="1" size="5">	
																		</div>																	
																	</form>
																</div>
																
																<div class="input-group">
																	<div class="input-group-btn">
																		<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1">
																			<b>Valores: <br/>1 M/N <br/>2 M/E</b>
																		</button>
																	</div>
																	<input type="text" class="xs" id="moneda" name='moneda' placeholder="#" maxlength="1" size="1">
																</div>
																<div class="input-group">
																	<div class="input-group-btn">
																		<button type="button" class="btn btn-default btn-sm btn_f" tabindex="-1">
																			<b>Debe 1:<br/>Haber 2</b>
																		</button>
																	</div>
																	<input type="text" class="xs" id="tipo_cue" name='tipo_cue' placeholder="#" maxlength="1" size="1">
																</div>-->
																<input type="hidden" id='dconcepto1' name='dconcepto1'  value='' />
															</div>
														</div>
													</div>	
												</div>
												<div class="row">
													<div class="col-xs-12 ">
														<!--<nav>
															<div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
																<a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Home</a>
																<a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#nav-profile" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</a>
																<a class="nav-item nav-link" id="nav-about-tab" data-toggle="tab" href="#nav-about" role="tab" aria-controls="nav-about" aria-selected="false">About</a>
															</div>
														</nav>
														<div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
															<div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
																Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.
															</div>
															<div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
																Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.
															</div>
															<div class="tab-pane fade" id="nav-about" role="tabpanel" aria-labelledby="nav-about-tab">
																Et et consectetur ipsum labore excepteur est proident excepteur ad velit occaecat qui minim occaecat veniam. Fugiat veniam incididunt anim aliqua enim pariatur veniam sunt est aute sit dolor anim. Velit non irure adipisicing aliqua ullamco irure incididunt irure non esse consectetur nostrud minim non minim occaecat. Amet duis do nisi duis veniam non est eiusmod tempor incididunt tempor dolor ipsum in qui sit. Exercitation mollit sit culpa nisi culpa non adipisicing reprehenderit do dolore. Duis reprehenderit occaecat anim ullamco ad duis occaecat ex.
															</div>
														</div>-->
													
														<div class="panel-heading">
															<ul class="nav nav-tabs">
																<li class="active"><a href="#tab1default" data-toggle="tab">4. Contabilización</a></li>
																<li><a href="#tab2default" data-toggle="tab">5. Subcuentas</a></li>
																<li><a href="#tab3default" data-toggle="tab">6. Retenciones</a></li>
																<li><a href="#tab4default" data-toggle="tab">7. AC-AV-AI-AE</a></li>
															</ul>
														</div>
														<div class="panel-body">
															<div class="tab-content">
																<div class="tab-pane fade in active" id="tab1default">
																	<?php 
																		$balance=ListarAsientoTem(null,null,'1','0,1,clave');
																	?>
																</div>
																<div class="tab-pane fade" id="tab2default">Default 2</div>
																<div class="tab-pane fade" id="tab3default">Default 3</div>
																<div class="tab-pane fade" id="tab4default">Default 4</div>
															</div>
														</div>
													</div>
												</div>
												<!--<div class="row ">
													<div class="col-md-3 col-sm-3 col-xs-3">
														<span class="help-block text-muted small-font" > Expiry Month</span>
														<input type="text" class="form-control input-sm" placeholder="MM" />
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3">
														<span class="help-block text-muted small-font" >  Expiry Year</span>
														<input type="text" class="form-control input-sm" placeholder="YY" />
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3">
														<span class="help-block text-muted small-font" >  CCV</span>
														<input type="text" class="form-control input-sm" placeholder="CCV" />
													</div>
													<div class="col-md-3 col-sm-3 col-xs-3">
														<img src="assets/img/1.png" class="img-rounded" />
													</div>
												</div>
												 <div class="row ">
													  <div class="col-md-12 pad-adjust">

														  <input type="text" class="form-control" placeholder="Name On The Card" />
													  </div>
												 </div>
												 <div class="row">
													<div class="col-md-12 pad-adjust">
														<div class="checkbox">
														<label>
														  <input type="checkbox" checked class="text-muted"> Save details for fast payments <a href="#"> learn how ?</a>
														</label>
													  </div>
													</div>
												</div>
												<div class="row ">
													<div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
														 <input type="submit"  class="btn btn-danger" value="CANCEL" />
													  </div>
													  <div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
														  <input type="submit"  class="btn btn-warning btn-block" value="PAY NOW" />
													  </div>
												</div>-->
												 
											</div>
										</div>
									</form>		
										
											
										
								<!-- Modal -->
								
								<div id='entidad1'>
									
								</div>
								<div id='empresa1'>
								</div>
								
								<!--<div class="form-group">
									<div class="col-md-12">
										<div id="alerta" class="alert invisible"></div>
										<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">
											En caso de dudas, comuniquese al centro de atención al cliente, a los telefonos:
											+593-2-321-0051 / +593-9-8035-5483</p>
									</div>	
									<div class="col-md-9">
										<button id="btnCopiar" class="btn btn-primary" onclick='cambiarEmpresa();'>Cambiar</button>
										<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
									</div>
								</div>
								<div class="modal fade" id="myModal" role="dialog" >
									<div class="modal-dialog" >
									
									
									  <div class="modal-content" >
										<div class="modal-header" style="background-color: #367fa9;color: #fff;">
										  <button type="button" class="close" data-dismiss="modal" 
										  style="color: #fff;">&times;</button>
										  <h4 class="modal-title">Modificar empresa</h4>
										</div>
										<div class="modal-body" style="height:250px;overflow-y: scroll;">
											<div class="box-body">
												<div class="form-group">
												    <label for="Entidad">Entidad</label>
												    <select class="form-control" name="entidad" id='entidad' onChange="return buscar('entidad');">
														<option value='0'>Seleccione Entidad</option>
														<?php select_option_mysql('entidad','ID_Empresa','Nombre_Entidad',''); ?>
													</select>
												</div>
												
												<div id='entidad1'>
													
												</div>
												<div id='empresa1'>
												</div>
												
											</div>
											
											<div class="form-group">
												<div class="row">
													
												  <div class="col-4">
													
													<div class="list-group" id="myList" role="tablist">
														
													</div>
												  </div>
												 
												  </div>
												</div>
												
											</div>
										</div>
										<div class="modal-footer" style="background-color: #fff;">
											<div id="alerta" class="alert invisible"></div>
											<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">
											En caso de dudas, comuniquese al centro de atención al cliente, a los telefonos:<br> 
											+593-2-321-0051 / +593-9-8035-5483</p>
											
											<button id="btnCopiar" class="btn btn-primary" onclick='cambiarEmpresa();'>Cambiar</button>
										    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										</div>
									  </div>
									  
									</div>-->
								</div>
								
								<script>
								
								$("#va").keydown(function(e) 
								{
									//colocar cookies
									var code = e.keyCode || e.which;
									 //eliminamos cookies
									 document.cookie = "nombre=; max-age=0";
									 //alert(code);
									if (code == '9')
									{
										var dconcepto1 = document.getElementById("dconcepto1").value;
										var codigo = document.getElementById("codigo").value;
										var cuenta = document.getElementById("cuenta").value;
										var efectivo_as = document.getElementById("efectivo_as").value;
										var chq_as = document.getElementById("chq_as").value;
										var moneda = document.getElementById("moneda").value;
										var tipo_cue = document.getElementById("tipo_cue").value;
										/*alert(va.value);
										alert(dconcepto1);
										alert(codigo);
										alert(cuenta);
										alert(efectivo_as);
										alert(chq_as);
										alert(moneda);
										alert(tipo_cue);*/
										//llamamos ajax
										var parametros = 
										{
											"va" : va.value,
											"dconcepto1" : dconcepto1,
											"codigo" : codigo,
											"cuenta" : cuenta,
											"efectivo_as" : efectivo_as,
											"chq_as" : chq_as,
											"moneda" : moneda,
											"tipo_cue" : tipo_cue,
											"ajax_page": 'ing1',
											"cl": 'as_i'
											
										};
										$.ajax({
											data:  parametros,
											url:   'ajax/vista_ajax.php',
											type:  'post',
											beforeSend: function () {
													$("#tab1default").html("");
											},
											success:  function (response) {
													$("#tab1default").html("");
													$("#tab1default").html(response);
													// var valor = $("#tab1default").html();
													
													
											}
										});
										//$("#tab1default").html(" datos aqui");
										//$("#tab1default").html(response);
									}
								});
								$("#vae").keyup(function(e) {
									 var vae = $("#vae").val();
									 //tecla tab
									 var code = e.keyCode || e.which;
									 //eliminamos cookies
									 document.cookie = "nombre=; max-age=0";
									 //alert(code);
									if (code == '9') {
										alert(vae);
										 document.cookie = "nombre=1; ";
										/*var parametros = 
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
										});*/
									}
									/*if (window.event.keyCode == 9)
									{
										alert(RUC);
									}*/
								});
								 $("#depos").keydown(function(e) {
									 var depos = $("#depos").val();
									 //tecla tab
									 var code = e.keyCode || e.which;
									 //eliminamos cookies
									 document.cookie = "nombre=; max-age=0";
									 //alert(code);
									if (code == '9') {
										//alert(depos);
										 document.cookie = "nombre=2; ";
										 var banco = document.getElementById("conceptob").value;
										 var vab = document.getElementById("vab").value;
										 var efecti = document.getElementById("efecti").value;
										 //alert (banco+' '+vab+' '+efecti+' '+depos);
										// alert(depos);
										var parametros = 
										{
											"banco" : banco,
											"vab" : vab,
											"efecti" : efecti,
											"depos" : depos,
											"ajax_page": 'ing1',
											"cl": 'as_b_i'
											
										};
										$.ajax({
											data:  parametros,
											url:   'ajax/vista_ajax.php',
											type:  'post',
											beforeSend: function () {
													$("#tabla_b").html("");
											},
											success:  function (response) {
													$("#tabla_b").html("");
													$("#tabla_b").html(response);
													 var valor = $("#tabla_b").html();
													
													
											}
										});
									}
									/*if (window.event.keyCode == 9)
									{
										alert(RUC);
									}*/
								});
								//caso click factura
								/*$("#concepto").click(function(e) {
									var depos = $("#depos").val();
									 
									var entro = readCookie('nombre');
										//alert(RUC);
										if(entro!='1' && entro!='2')
										{
											alert(depos);
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
								/*});*/
								
								
								$(document).ready(function () {
									
									/*$('#beneficiario').typeahead({
										//document.cookie = "cod=; ";
										//document.cookie = "cod=cla; ";
										source: function (query, result) {
											$.ajax({
												url: 'ajax/vista_ajax.php',
												data: {query: query, ajax_page: 'aut', cl: 'cla'},             
												dataType: "json",
												type: "POST",
												success: function (data) {
													result($.map(data, function (item) {
														//alert(item.nombre);
														//document.getElementById('txtCountry').value=item.id1;
														return  item.nombre;
													}));
												}
											});
										}
									});
									$('#cuenta').typeahead({
										//document.cookie = "cod=; ";
										//document.cookie = "cod=cua; ";
										source: function (query, result) {
											$.ajax({
												url: 'ajax/vista_ajax.php',
												data: {query: query, ajax_page: 'aut', cl: 'cla'},             
												dataType: "json",
												type: "POST",
												success: function (data) {
													result($.map(data, function (item) {
														//alert(item.nombre);
														//document.getElementById('txtCountry').value=item.id1;
														return  item.nombre;
													}));
												}
											});
										}
									});*/
								});
								$("#cuenta").keyup(function(e) {
									 var depos = $("#cuenta").val();
									 //tecla tab
									 var code = e.keyCode || e.which;
									 //eliminamos cookies
									 document.cookie = "nombre=; max-age=0";
									// alert(depos);
									 var parametros = 
										{
											"query" : depos,
											"ajax_page": 'aut1',
											cl: 'ca_cu_a'
											
										};
										$.ajax({
											data:  parametros,
											url:   'ajax/vista_ajax.php',
											type:  'post',
											beforeSend: function () {
													$("#resultado").html("");
											},
											success:  function (response) {
													$("#cuentar").html("");
													$("#cuentar").html(response);
													 var valor = $("#cuentar").html();
													 autocomplete(document.getElementById("cuenta"), countries);
													 document.cookie = "cod=; ";
													 document.cookie = "cod=ca_cu_a; ";	
											}
										});
								});
								$("#codigo").keyup(function(e) {
									 var depos = $("#codigo").val();
									 //tecla tab
									 var code = e.keyCode || e.which;
									 //eliminamos cookies
									 document.cookie = "nombre=; max-age=0";
									// alert(depos);
									 var parametros = 
										{
											"query" : depos,
											"ajax_page": 'aut1',
											cl: 'ca_cu_a1'
											
										};
										$.ajax({
											data:  parametros,
											url:   'ajax/vista_ajax.php',
											type:  'post',
											beforeSend: function () {
													$("#resultado").html("");
											},
											success:  function (response) {
													$("#cuentar").html("");
													$("#cuentar").html(response);
													var valor = $("#cuentar").html();
													autocomplete(document.getElementById("codigo"), countries,'cuenta');
													document.cookie = "cod=; ";
													document.cookie = "cod=ca_cu_a; ";	
											}
										});
								});
								$("#beneficiario").keyup(function(e) {
									 var depos = $("#beneficiario").val();
									 //tecla tab
									 var code = e.keyCode || e.which;
									 //eliminamos cookies
									 document.cookie = "nombre=; max-age=0";
									// alert(depos);
									 var parametros = 
										{
											"query" : depos,
											"ajax_page": 'aut1',
											cl: 'cl_a'
											
										};
										$.ajax({
											data:  parametros,
											url:   'ajax/vista_ajax.php',
											type:  'post',
											beforeSend: function () {
													$("#resultado").html("");
											},
											success:  function (response) {
													$("#bener").html("");
													$("#bener").html(response);
													 var valor = $("#bener").html();
													 autocomplete(document.getElementById("beneficiario"), countries);
													 document.cookie = "cod=; ";
													 document.cookie = "cod=cl_a; ";
											}
										});
								});
								
								function  selec(e)
								{
									//$(e).text('there');
									//ca = $(e).html().split('-');
									//alert($(e).html());
									//document.getElementById('beneficiario1').value=ca[0];
									//
									let cad = e;
									//limpia espacios vacios al inix¡cio y final de cadena de texto
									cad = cad.trim();
									//cad = cad.replace(" ", " ");
									if(readCookie('cod')=='ca_cu_a')
									{
										$.post('ajax/vista_ajax.php'
										, {ajax_page: 'bus', com: cad,cl: 'ca_cu_b' }, function(data){
											var obj = JSON.parse(data);
											//alert(obj[0].TC);
											//document.getElementById('codigo_cu').value=parseInt(obj[0].Cla);
											document.getElementById('codigo').value=obj[0].Cod;	
											document.getElementById('cuenta').value=obj[0].Cu;	
											document.getElementById('TC').value=obj[0].TC;	
											//llamamos siguiente popup
											//alert(depos);
											document.getElementById("valoro").style.display = "none";
											if(document.getElementById('TC').value=='BA')
											{
												$("#tcom").html("<div class='marco'>"+
																"<form class='form1' action=''>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 1%;left: 1%;'>"+
																			"<b>Efectivizar</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 1%;left: 39%;'>"+
																		"<input id='efectivo_as' name='efectivo_as' class='' "+
																		" type='date' maxlength='10' size='10'>"+
																	"</div>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 20%;left: 1%;'>"+
																			"<b>CHq/Dep</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 20%;left: 39%;'>"+
																		"<input id='chq_as' name='chq_as' class='' type='text' maxlength='30' size='5'>"+
																	"</div>"+
																	"<label for='firstName' class=''>"+
																		/*"<div style='position:absolute;top: 33%;left: 40%;'>"+
																			"<b>Valores:</b>"+
																		"</div>"+*/
																		"<div style='position:absolute;top: 40%;left: 1%;'>"+
																			"<b>1 M/N</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 49%;left: 1%;'>"+
																			"<b>2 M/E</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 40%;left: 39%;'>"+
																		"<input id='moneda' name='moneda' class='' type='text' maxlength='1' size='5'>"+
																	"</div>"+
																	"<label for='lastName' class=''>"+
																		"<div style='position:absolute;top: 57%;left: 1%;'>"+
																			"<b>Debe 1:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 65%;left: 1%;'>"+
																			"<b>Haber 2</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 57%;left: 39%;'>"+
																		"<input id='tipo_cue' name='tipo_cue' class='' "+
																		" onkeydown='proceso1(event)' type='text' maxlength='1' size='5'>"+	
																	"</div>	"+																
																"</form>"+
															"</div>"+
															"<input type='hidden' id='dconcepto1' name='dconcepto1'  value='' />");
											}
											else
											{
												$("#tcom").html("<div class='marco'>"+
																"<form class='form1' action=''>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 3%;left: 40%;'>"+
																			"<b>Valores:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 13%;left: 1%;'>"+
																			"<b>1 M/N</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 21%;left: 1%;'>"+
																			"<b>2 M/E</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 13%;left: 32%;'>"+
																		"<input id='moneda' name='moneda' class='' type='text' maxlength='1' size='5'>"+
																	"</div>"+
																	"<label for='lastName' class=''>"+
																		"<div style='position:absolute;top: 32%;left: 1%;'>"+
																			"<b>Debe 1:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 40%;left: 1%;'>"+
																			"<b>Haber 2</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 32%;left: 32%;'>"+
																		"<input id='tipo_cue' name='tipo_cue' class='' type='text' "+
																		" onkeydown='proceso1(event)' maxlength='1' size='5'>"+	
																	"</div>	"+																
																"</form>"+
															"</div>"+
															"<input type='hidden' id='dconcepto1' name='dconcepto1'  value='' />");
											}
											document.getElementById("tcom").style.display = "block";
										});
									}
									if(readCookie('cod')=='cl_a')
									{
										$.post('ajax/vista_ajax.php'
										, {ajax_page: 'bus', com: cad,cl: 'cl_b' }, function(data){
											var obj = JSON.parse(data);
											//alert(obj[0].Cla);
											//document.getElementById('codigo_cu').value=parseInt(obj[0].Cla);
											document.getElementById('ruc').value=obj[0].CI;	
											document.getElementById('email').value=obj[0].Em;	
											document.getElementById('beneficiario2').value=obj[0].Cod;
										});
									}
									//document.getElementById('beneficiario').value=ca[1];
									//$(e).text(ca[1]);
								}
								function proceso1(e) 
								{
									var vae = $("#tipo_cue").val();
									//tecla tab
									var code = e.keyCode || e.which;
									//eliminamos cookies
									document.cookie = "nombre=; max-age=0";
									//alert(code);
									if (code == '9')
									{
										//alert(vae);
										// document.cookie = "nombre=1; ";
										document.getElementById("tcom").style.display = "none";
										document.getElementById("valoro").style.display = "block";
										$("#va").focus();
										document.getElementById("va").focus();
										Swal.fire({
										  title: 'Detalle Auxiliar',
										  html:
											'<input type="text" class="form-control" id="dconcepto" name="dconcepto" maxlength="60" placeholder="" ><br>',
										}).then((result) => {
										  if (result.value) {
											//alert(document.getElementById("dconcepto").value);
											document.getElementById("dconcepto1").value=document.getElementById("dconcepto").value;
											$("#va").focus();
											//location.href="panel.php?mos2=e";
										  } 
										});
									}
								}
								/*$("#tipo_cue").keyup(function(e) 
								{
									var vae = $("#tipo_cue").val();
									//tecla tab
									var code = e.keyCode || e.which;
									//eliminamos cookies
									document.cookie = "nombre=; max-age=0";
									alert(code);
									if (code == '9')
									{
										//alert(vae);
										// document.cookie = "nombre=1; ";
										document.getElementById("tcom").style.display = "none";
										document.getElementById("valoro").style.display = "block";
										$("#va").focus();
										document.getElementById("va").focus();
										Swal.fire({
										  title: 'Detalle Auxiliar',
										  html:
											'<input type="text" class="form-control" id="dconcepto" name="dconcepto" maxlength="60" placeholder="" ><br>',
										}).then((result) => {
										  if (result.value) {
											alert(document.getElementById("dconcepto").value);
											document.getElementById("dconcepto1").value=document.getElementById("dconcepto").value;
											$("#va").focus();
											//location.href="panel.php?mos2=e";
										  } 
										});
									}
								});*/

								//para el check
								$(function () {
									$('.button-checkbox').each(function () {

										// Settings
										var $widget = $(this),
											$button = $widget.find('button'),
											$checkbox = $widget.find('input:checkbox'),
											color = $button.data('color'),
											settings = {
												on: {
													icon: 'glyphicon glyphicon-check'
												},
												off: {
													icon: 'glyphicon glyphicon-unchecked'
												}
											};

										// Event Handlers
										$button.on('click', function () {
											$checkbox.prop('checked', !$checkbox.is(':checked'));
											$checkbox.triggerHandler('change');
											updateDisplay();
										});
										$checkbox.on('change', function () {
											updateDisplay();
										});

										// Actions
										function updateDisplay() {
											var isChecked = $checkbox.is(':checked');

											// Set the button's state
											$button.data('state', (isChecked) ? "on" : "off");

											// Set the button's icon
											$button.find('.state-icon')
												.removeClass()
												.addClass('state-icon ' + settings[$button.data('state')].icon);

											// Update the button's color
											if (isChecked) {
												$button
													.removeClass('btn-default')
													.addClass('btn-' + color + ' active');
												document.getElementById("ineg1").style.display = "block";
											}
											else {
												$button
													.removeClass('btn-' + color + ' active')
													.addClass('btn-default');
												document.getElementById("ineg1").style.display = "none";
											}
										}

										// Initialization
										function init() {

											updateDisplay();

											// Inject the icon if applicable
											if ($button.find('.state-icon').length == 0) {
												$button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
											}
										}
										init();
									});
									$('.button-checkbox1').each(function () {

										// Settings
										var $widget = $(this),
											$button = $widget.find('button'),
											$checkbox = $widget.find('input:checkbox'),
											color = $button.data('color'),
											settings = {
												on: {
													icon: 'glyphicon glyphicon-check'
												},
												off: {
													icon: 'glyphicon glyphicon-unchecked'
												}
											};

										// Event Handlers
										$button.on('click', function () {
											$checkbox.prop('checked', !$checkbox.is(':checked'));
											$checkbox.triggerHandler('change');
											updateDisplay();
										});
										$checkbox.on('change', function () {
											updateDisplay();
										});

										// Actions
										function updateDisplay() {
											var isChecked = $checkbox.is(':checked');

											// Set the button's state
											$button.data('state', (isChecked) ? "on" : "off");

											// Set the button's icon
											$button.find('.state-icon')
												.removeClass()
												.addClass('state-icon ' + settings[$button.data('state')].icon);

											// Update the button's color
											if (isChecked) {
												$button
													.removeClass('btn-default')
													.addClass('btn-' + color + ' active');
												document.getElementById("ineg2").style.display = "block";
												document.getElementById("ineg3").style.display = "block";
											}
											else {
												$button
													.removeClass('btn-' + color + ' active')
													.addClass('btn-default');
												document.getElementById("ineg2").style.display = "none";
												document.getElementById("ineg3").style.display = "none";
											}
										}

										// Initialization
										function init() {

											updateDisplay();

											// Inject the icon if applicable
											if ($button.find('.state-icon').length == 0) {
												$button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
											}
										}
										init();
									});
								});
								$('#myList a').on('click', function (e) {
								  e.preventDefault()
								  $(this).tab('show');
									});
									$(".loader1").hide();
									$(function() { 
										$("#myModal").modal();
										//$("#dialog").dialog(); 
									});
									function reset_(idMensaje,tipoc)
									{
										if(tipoc=='CI' || tipoc=='CE')
										{
											document.getElementById("ineg").style.display = "block";
										}
										else
										{
											document.getElementById("ineg").style.display = "none";
										}
										if(tipoc!='')
										{
											//creamos cookie
											//document.cookie = "tipoco=;";
											//if(readCookie('tipoco')==null)
											//{
											document.cookie = "tipoco=; ";
											document.cookie = "tipoco="+tipoc+"; ";
											//}
											//alert(' 1 '+readCookie('tipoco'));
											if(tipoc!='CD')
											{
												var element = document.getElementById("CD");
												element.classList.remove("active");
											}
											if(tipoc!='CI')
											{
												var element = document.getElementById("CI");
												element.classList.remove("active");
											}
											if(tipoc!='CE')
											{
												var element = document.getElementById("CE");
												element.classList.remove("active");
											}
											if(tipoc!='ND')
											{
												var element = document.getElementById("ND");
												element.classList.remove("active");
											}
											if(tipoc!='NC')
											{
												var element = document.getElementById("NC");
												element.classList.remove("active");
											}
											
											var select = document.getElementById('tipoc'); //El <select>
											select.value = tipoc;
										}
										//si ya esta la cookies verificamos para que este presionado
										//alert(' 2 '+readCookie('tipoco'));
										if(readCookie('tipoco')!=null)
										{
											var element = document.getElementById(readCookie('tipoco'));
											//element.classList.remove("active");
											element.classList.add('active');
											//myElemento.classList.add('nombreclase1','nombreclase2');
											if(readCookie('tipoco')!='CD')
											{
												var element = document.getElementById("CD");
												element.classList.remove("active");
											}
											if(readCookie('tipoco')!='CI')
											{
												var element = document.getElementById("CI");
												element.classList.remove("active");
											}
											if(readCookie('tipoco')!='CE')
											{
												var element = document.getElementById("CE");
												element.classList.remove("active");
											}
											if(readCookie('tipoco')!='ND')
											{
												var element = document.getElementById("ND");
												element.classList.remove("active");
											}
											if(readCookie('tipoco')!='NC')
											{
												var element = document.getElementById("NC");
												element.classList.remove("active");
											}
										}
										/*$('div.'+idMensaje).html('<select class="form-control" name="tipo" onclick="buscar(\'comproba\');">'+
																	'<option value="seleccione">seleccione</option>'+
																	'</select>'); */
									}
									//se implementa funcion check en vista
									 function validarc(id,ta) 
									 {
										//verificamos que este seleccionado
										 
										if (document.getElementById(id).checked)
										{
											var select = document.getElementById(id); //El <select>
										    value = select.value; 
											//alert(value);
											var reg1 = document.getElementById('reg1');
											 //value1 = reg1.value; 
											// value1 =  value1+value+',';
											value1 =  value;
											// alert(value1);
											reg1.value = value1;
											Swal.fire({
											  title: 'eliminar registro',
											  text: "Desea usted eliminar registro!",
											  type: 'warning',
											  showCancelButton: true,
											  confirmButtonColor: '#3085d6',
											  cancelButtonColor: '#d33',
											  cancelButtonText: 'Cancelar',
											  confirmButtonText: 'Si eliminar!'
											}).then((result) => {
											  if (result.value) {
												$.post('ajax/vista_ajax.php', { ajax_page: 'eli1', clave: reg1.value,
													id: <?php echo $_SESSION['INGRESO']['Id']; ?>,it: <?php echo $_SESSION['INGRESO']['item']; ?>,
													cl: ta }, 
														function(returnedData){
													 console.log(returnedData);
													 if(returnedData.success){
														 Swal.fire({
														  //position: 'top-end',
														  type: 'success',
														  title: 'Registro eliminado con exito!',
														  showConfirmButton: true
														  //timer: 2500
														}).then((result) => {
														  if (result.value) {
															 var ca = id.split('_');
															//  alert(ca[0]+' '+ca[1]);
															  document.getElementById("ta_" + ca[1]).style.display = "none";
															//location.href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti="+
														//"<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0";
														  } 
														});
														//swal("Correcto!", 'Autorizado por: ' + returnedData.name, "success");
														
														//location.href="ver_nominas.php?borrar=" + name;
													 }else{
														 // var ca = id.split('_');
														 // alert(ca[0]+' '+ca[1]);
														 // document.getElementById("ta_" + ca[1]).style.display = "none";
														 Swal.fire({
														  type: 'error',
														  title: 'Oops...',
														  text: 'No se pudo eliminar registro!'
														});
													 }
														}, 'json');
												
											  }
											});
										}
										else
										{
											/*
												$('#myDiv').change(function() {
												  var values = 0.00;
												  {
													$('#myDiv :checked').each(function() {
													  //if(values.indexOf($(this).val()) === -1){
													  values=values+parseFloat(($(this).val()));
													  // }
													});
													console.log( parseFloat(values));
												  }
												});
												<div id="myDiv">
												  <input type="checkbox" name="type" value="4.00" />
												  <input type="checkbox" name="type" value="3.75" />
												  <input type="checkbox" name="type" value="1.25" />
												  <input type="checkbox" name="type" value="5.50" />
												</div>
											*/
										}
									 }
								</script>
								<!-- /.modal -->
					<?php
							}
							else
							{
			?>
								<script>
									/*
										let timerInterval
										Swal.fire({
										  title: 'Mayorizando!',
										  html: 'quedan <strong></strong> segundos.',
										  timer: 4000,
										  onBeforeOpen: () => {
											Swal.showLoading()
											timerInterval = setInterval(() => {
											  Swal.getContent().querySelector('strong')
												.textContent = Swal.getTimerLeft()
											}, 100)
										  },
										  onClose: () => {
											clearInterval(timerInterval)
										  }
										}).then((result) => {
										  if (
											// Read more about handling dismissals
											result.dismiss === Swal.DismissReason.timer
										  ) {
											console.log('I was closed by the timer');
											 //location.href ="contabilidad.php?mod=contabilidad";
										  }
										});*/
								  $(".loader1").hide();
								  <?php
										//
										//die();
									?>
								
								 // $(".loader2").show();
								 Swal.fire({
								  title: 'Terminado!',
								  text: 'Error al cargar formulario.',
								  
								  animation: false
								}).then((result) => {
										  if (
											result.value
										  ) {
											console.log('I was closed by the timer');
											location.href ="contabilidad.php?mod=contabilidad&er=1";
										  }
										});
							</script>
			<?php
							}
			?>
            </div>
			
				
            </div>
				
            <!-- /.box-body -->
          </div>
	</div>
</div>

