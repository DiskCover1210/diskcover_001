<?php

    if(!isset($_SESSION)) 
	 		session_start();
	//datos para consultar
	//CI_NIC
	//echo $_SESSION['INGRESO']['Opc'].' '.$_SESSION['INGRESO']['Sucursal'].' '.$_SESSION['INGRESO']['item'].' '.$_SESSION['INGRESO']['periodo'].' ';

?>
<?php
 //verificacion titulo accion
	$_SESSION['INGRESO']['ti']='';
	if(isset($_GET['ti'])) 
	{
		$_SESSION['INGRESO']['ti']=$_GET['ti'];
	}
	else
	{
		unset( $_SESSION['INGRESO']['ti']);
		$_SESSION['INGRESO']['ti']='MAYORIZACIÓN';
	}
?>
 <div class="row">
		 <div class="col-xs-12">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-header">
					<h4 class="box-title">
						<a id='l1' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
							<i ><img src="../../img/png/pbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l2' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance mensual"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Procesar balance mensual&ti=BALANCE MENSUAL&Opcb=2&Opcen=1&b=1">
							<i ><img src="../../img/png/pbm.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l3' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance consolidado de varias sucursales">
							<i ><img src="../../img/png/pbcs.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l4' class="btn btn-default"  data-toggle="tooltip" title="Presenta balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
							<i ><img src="../../img/png/vbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de situación (general: activo, pasivo y patrimonio)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0"
						>
							<i ><img src="../../img/png/bc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l6' class="btn btn-default"  data-toggle="tooltip" title="Presenta estado de resultado (ingreso y egresos)"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de resultado&ti=ESTADO RESULTADO&Opcb=6&Opcen=0&b=0">
							<i ><img src="../../img/png/up.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a class="btn btn-default"  data-toggle="tooltip" title="Presenta balance mensual por semana">
							<i ><img src="../../img/png/pbms.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a class="btn btn-default"  data-toggle="tooltip" title="SBS B11">
							<i ><img src="../../img/png/books.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a class="btn btn-default"  data-toggle="tooltip" title="Imprimir resultados">
							<i ><img src="../../img/png/impresora.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						
						<a id='l7' class="btn btn-default"  data-toggle="tooltip" title="Exportar Excel"
						href="descarga.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&ex=1" onclick='modificar1();' target="_blank">
							<i ><img src="../../img/png/table_excel.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
							<a class="btn btn-default"  data-toggle="tooltip" title="Salir del modulo" href="contabilidad.php?mod=contabilidad">
							<i ><img src="../../img/png/salir.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
			  </div>
			 </div>
		 </div>
	  </div>

<div class="panel box box-primary">
	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		<div class="box table-responsive">
            <div class="box-header">
              <!--<h3 class="box-title">Striped Full Width Table</h3>
			  <table>
				<tr>
					<td>
						<div class="loader1"></div>
					</td>
				</tr>
			  </table>-->
			<?php
					
					?>
					
					<?php
					//echo $url.' '.posix_getcwd(); 
					//llamamos funcion SPK marotizar
					if(function_exists('curl_init')) // Comprobamos si hay soporte para cURL
					{
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL,
							"contabilidad.php?mod=contabilidad&acc=macom&acc1=Mayorización&b=1&asi=1");
						curl_setopt($ch, CURLOPT_TIMEOUT, 30);
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						$resultado = curl_exec ($ch);
						
						print_r($resultado);
					}
					else
					{
						echo "No hay soporte para cURL";
					}
					//sp_Mayorizar_Cuentas($_SESSION['INGRESO']['Opc'],$_SESSION['INGRESO']['Sucursal'],$_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['periodo'],'1');
					sp_Mayorizar_Cuentas($_SESSION['INGRESO']['Opc'],$_SESSION['INGRESO']['Sucursal'],$_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['periodo']);
					//$url= url();
					//die();
					//consultamos errores
							$texto = sp_errores($_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['modulo_'],$_SESSION['INGRESO']['Id']);
							if(count($texto)>0)
							{
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
								<?php //echo $ove; ?>
								<!-- Modal -->
								<div class="modal fade" id="myModal" role="dialog">
									<div class="modal-dialog" >
									
									
									  <div class="modal-content">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal">&times;</button>
										  <h4 class="modal-title"><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg"> Listado de errores</h4>
										</div>
										<div class="modal-body" style="height:<?php echo $ove; ?>px;overflow-y: scroll;">
											<div class="form-group">
												<p align='left' id='texto'>
												<?php
													for($i=0;$i<count($texto);$i++)
													{
														echo ''.$texto[$i].'<br>';
														//echo $texto[$i];
														//echo "<br>";
													}
												?>	
												</p>
											</div>
										</div>
										<div class="modal-footer">
											<div id="alerta" class="alert invisible"></div>
											<p  align='left'><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg">
											En caso de dudas, comuniquese al centro de atención al cliente, a los telefonos:<br> 
											+593-2-321-0051 / +593-9-8035-5483</p>
											<a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Exportar PDF"
											href="descarga.php?mod=contabilidad&acc=macom&acc1=Errores de mayorización&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
											&Opcb=6&Opcen=0&b=0&ex=pdf&cl=0" target="_blank" >
												<i ><img src="../../img/png/pdf.png" class="user-image" alt="User Image"
												style='font-size:20px; display:block; height:100%; width:100%;'></i> 
											</a>
											<button id="btnCopiar" class="btn btn-primary" onclick='copiar();'>Copiar</button>
										    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										</div>
									  </div>
									  
									</div>
								</div>
								
								<script>
									//$(".loader1").hide();
									$('#myModal_espera').modal('show');
									$(function() { 
										$("#myModal").modal();
										//$("#dialog").dialog(); 
									});
									$('#myModal_espera').modal('hide');
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
									function interceptarPegado(ev) 
									{
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
								 // $(".loader1").hide();
								  <?php
										//
										//die();
									?>
								
								 // $(".loader2").show();
								 Swal.fire({
								  title: 'Terminado!',
								  text: 'Proceso de mayorización listo.',
								  
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
