<?php

    if(!isset($_SESSION)) 
	 		session_start();
		$_SESSION['INGRESO']['ti']='Cambio Periodo';
	//datos para consultar
	//CI_NIC
	//echo $_SESSION['INGRESO']['Opc'].' '.$_SESSION['INGRESO']['Sucursal'].' '.$_SESSION['INGRESO']['item'].' '.$_SESSION['INGRESO']['periodo'].' ';

?>

<!--<h2>Balance de Comprobacion/Situación/General</h2>-->
<br/>
<br/>
<br/>
<br/>
<div class="panel box box-primary">
	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
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
					//echo $url.' '.posix_getcwd(); 
					
					//sp_Mayorizar_Cuentas($_SESSION['INGRESO']['Opc'],$_SESSION['INGRESO']['Sucursal'],$_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['periodo']);
					//$url= url();
					//die();
					//consultamos errores
							//$texto = sp_errores($_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['modulo'],$_SESSION['INGRESO']['Id']);
							$texto[0]=1;
							//list option
							//select_option('Tabla_Dias_Meses','No_D_M','Dia_Mes',"Tipo='M' AND No_D_M<>0 ");
							
							if(count($texto)>0)
							{
								//contamos registros
								$texto=contar_option('Catalogo_Cuentas','Periodo','cperiodo',"Periodo <> '.' AND Item = '002' GROUP BY Periodo ORDER BY Periodo "); 
								//para el overflow
								if(count($texto)<=8)
								{
									$ove=220;
								}
								if(count($texto)>8 and count($texto)<=14)
								{
									$ove=300;
								}
								if(count($texto)>14 and count($texto)<=21)
								{
									$ove=350;
								}
								if(count($texto)>21)
								{
									$ove=400;
								}
					?>	
								<!-- Modal -->
								<div class="modal fade" id="myModal" role="dialog">
									<div class="modal-dialog" >
									
									
									  <div class="modal-content" >
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal">&times;</button>
										  <h4 class="modal-title">Cambio de periodo</h4>
										</div>
										<div class="modal-body" style=" height:<?php echo $ove; ?>px;overflow-y: scroll;">
											<div class="form-group">
												<div class="row">
												  <div class="col-4">
													<div class="list-group" id="myList" role="tablist">
														<?php 
															//caso contabilidad
															//
															//caso contabilidad
															//list_option('Tabla_Dias_Meses','No_D_M','Dia_Mes',"Tipo='M' AND No_D_M<>0 "); 
															//echo " entro ";
															?>
																<div class="list-group" id="myList1" role="tablist">
																	<table>
																			<?php 
															if($_SESSION['INGRESO']['modulo']=='11')
															{
																//periodos
																list_option('Catalogo_Periodo_Lectivo','Periodo','Periodo',"Periodo <> '.' AND Item = '".$_SESSION['INGRESO']['item']."' GROUP BY Periodo ORDER BY Periodo "); 
																//periodo actual
															}
															else
															{
																if($_SESSION['INGRESO']['modulo']=='02')
																{
																	list_option('Facturas','Periodo','Periodo',"Periodo <> '.' AND Item = '".$_SESSION['INGRESO']['item']."' GROUP BY Periodo ORDER BY Periodo "); 
																}
																else
																{
																	list_option('Catalogo_Cuentas','Periodo','Periodo',"Periodo <> '.' AND Item = '".$_SESSION['INGRESO']['item']."' GROUP BY Periodo ORDER BY Periodo "); 
																}
															}
															?>
																				<tr>
																					<td WIDTH="50%">
																						<a class="list-group-item list-group-item-action " id="list-actual" 
																						  data-toggle="list" href="#list-actual" role="tab" aria-controls="<?php echo 'actual'; ?>">
																							<?php echo 'Periodo Actual'; ?>
																						</a>
																						<script>
																							$('#list-actual').on('click', function (e) {
																								  var select = document.getElementById('opcion'); //El <select>
																								  //alert($("#list-home-list").text());
																								  select.value = '.';
																							});
																						</script>
																					</td>
																				</tr>
																		</table>
																	<?php
																?>
																	</div>
																	
																<?php
														?>
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
											
											<button id="btnCopiar" class="btn btn-primary" onclick='cambiarPeriodo();'>Cambiar</button>
										    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										</div>
									  </div>
									  
									</div>
								</div>
								
								<script>
								$('#myList a').on('click', function (e) {
								  e.preventDefault()
								  $(this).tab('show');
									 $('#list-home-list').on('click', function (e) {
										  $('#list-profile-list').removeClass('active'); 
										  $('#list-messages-list').removeClass('active'); 
										  $('#list-settings-list').removeClass('active'); 
										  $('#list-home-list').addClass('active');
										  var select = document.getElementById('fecha'); //El <select>
										  //alert($("#list-home-list").text());
										  select.value = $("#list-home-list").text();
									});
									$('#list-profile-list').on('click', function (e) {
										  $('#list-home-list').removeClass('active'); 
										  $('#list-messages-list').removeClass('active'); 
										  $('#list-settings-list').removeClass('active'); 
										  $('#list-profile-list').addClass('active');
										  var select = document.getElementById('fecha'); 
										  select.value = $("#list-profile-list").text();
									});
									$('#list-messages-list').on('click', function (e) {
										  $('#list-profile-list').removeClass('active'); 
										  $('#list-home-list').removeClass('active'); 
										  $('#list-settings-list').removeClass('active'); 
										  $('#list-messages-list').addClass('active');
										  var select = document.getElementById('fecha'); 
										  select.value = $("#list-messages-list").text();
									});
									$('#list-settings-list').on('click', function (e) {
										  $('#list-profile-list').removeClass('active'); 
										  $('#list-messages-list').removeClass('active'); 
										  $('#list-home-list').removeClass('active'); 
										  $('#list-settings-list').addClass('active');
										  var select = document.getElementById('fecha'); 
										  select.value = $("#list-settings-list").text();
									});
								});
									$(".loader1").hide();
									$(function() { 
										$("#myModal").modal();
										//$("#dialog").dialog(); 
									});
									function cambiarPeriodo()
									{
										var periodo = document.getElementById('opcion');
										$.post('ajax/vista_ajax.php'
										, {ajax_page: 'cambiarPeriodo', campo: periodo.value }, function(data){
											//$('div.'+idMensaje).html(data); 
											//alert('entrooo ');
											Swal.fire({
												  title: 'Cambio periodo!',
												  text: 'periodo cambiado con existo.',
												  
												  animation: false
												}).then((result) => {
														  if (
															result.value
														  ) {
															console.log('I was closed by the timer');
															location.href ="contabilidad.php?mod=contabilidad";
														  }
														});
										});
									}
									function copiar()
									{
										var codigoACopiar = document.getElementById('texto');
										var seleccion = document.createRange();
										seleccion.selectNodeContents(codigoACopiar);
										window.getSelection().removeAllRanges();
										window.getSelection().addRange(seleccion);
										try {
											var res = document.execCommand('copy'); //Intento el copiado
											if (res)
												exito();
											else
												fracaso();

											mostrarAlerta();
										}
										catch(ex) {
											excepcion();
										}
										window.getSelection().removeRange(seleccion);
									}
									function interceptarPegado(ev) {
										alert('Has pegado el texto:' + ev.clipboardData.getData('text/plain'));
									}

								///////
								// Auxiliares para mostrar y ocultar mensajes
								///////
									var divAlerta = document.getElementById('alerta');
									
									function exito() {
										divAlerta.innerText = '¡¡Copiado con exito al portapapeles!!';
										divAlerta.classList.add('alert-success');
									}

									function fracaso() {
										divAlerta.innerText = '¡¡Ha fallado el copiado al portapapeles!!';
										divAlerta.classList.add('alert-warning');
									}

									function excepcion() {
										divAlerta.innerText = 'Se ha producido un error al copiar al portapaples';
										divAlerta.classList.add('alert-danger');
									}

									function mostrarAlerta() {
										divAlerta.classList.remove('invisible');
										divAlerta.classList.add('visible');
										setTimeout(ocultarAlerta, 1500);
									}

									function ocultarAlerta() {
										divAlerta.innerText = '';
										divAlerta.classList.remove('alert-success', 'alert-warning', 'alert-danger', 'visible');
										divAlerta.classList.add('invisible');
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
								  text: 'Error con formulario.',
								  
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
<script>
	


	//Date picker
    $('#desde').datepicker({
		dateFormat: 'dd/mm/yyyy',
      autoclose: true
    });
	$('#hasta').datepicker({
		dateFormat: 'dd/mm/yyyy',
      autoclose: true
    });
	//modificar url
	function modificar(texto){
		var l1=$('#l1').attr("href");  
		var l1=l1+'&OpcDG='+texto;
		//asignamos
		$("#l1").attr("href",l1);
		
		var l2=$('#l2').attr("href");  
		var l2=l2+'&OpcDG='+texto;
		//asignamos
		$("#l2").attr("href",l2);
		
		var l4=$('#l4').attr("href");  
		var l4=l4+'&OpcDG='+texto;
		//asignamos
		$("#l4").attr("href",l4);
		
		var l5=$('#l5').attr("href");  
		var l5=l5+'&OpcDG='+texto;
		//asignamos
		$("#l5").attr("href",l5);
		
		var l6=$('#l6').attr("href");  
		var l6=l6+'&OpcDG='+texto;
		//asignamos
		$("#l6").attr("href",l6);
		//var ti=getParameterByName('ti');
		//alert(ti);
	  //document.getElementById("mienlace").innerHTML = texto;
	  //document.getElementById("mienlace").href = url;
	  //document.getElementById("mienlace").target = destino;
	} 
	//balance nomenclatura nacional o internacional
		//modificar url
	function modificarb(id){
		texto='0';
		if (document.getElementById(id).checked)
		{
			//alert('Seleccionado');
			texto='1';
		}
		
		var l1=$('#l1').attr("href");  
		var l1=l1+'&OpcCE='+texto;
		//asignamos
		$("#l1").attr("href",l1);
		
		var l2=$('#l2').attr("href");  
		var l2=l2+'&OpcCE='+texto;
		//asignamos
		$("#l2").attr("href",l2);
		
		var l4=$('#l4').attr("href");  
		var l4=l4+'&OpcCE='+texto;
		//asignamos
		$("#l4").attr("href",l4);
		
		var l5=$('#l5').attr("href");  
		var l5=l5+'&OpcCE='+texto;
		//asignamos
		$("#l5").attr("href",l5);
		
		var l6=$('#l6').attr("href");  
		var l6=l6+'&OpcCE='+texto;
		//asignamos
		$("#l6").attr("href",l6);
		//var ti=getParameterByName('ti');
		//alert(ti);
	  //document.getElementById("mienlace").innerHTML = texto;
	  //document.getElementById("mienlace").href = url;
	  //document.getElementById("mienlace").target = destino;
	} 
	function modificar1()
	{
		var ti=getParameterByName('ti');
		//alert(ti);
		if( ti=='BALANCE DE COMPROBACIÓN')
		{
			var l1=$('#l1').attr("href"); 
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);		
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='BALANCE MENSUAL')
		{
			var l1=$('#l2').attr("href"); 
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);		
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='ESTADO SITUACIÓN')
		{
			var l1=$('#l5').attr("href"); 
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);		
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		if( ti=='ESTADO RESULTADO')
		{
			var l1=$('#l6').attr("href"); 
			patron = "contabilidad.php";
			nuevoValor    = "descarga.php";
			l1 = l1.replace(patron, nuevoValor);		
			//asignamos
			$("#l7").attr("href",l1+'&ex=1');
		}
		
	}
</script>
