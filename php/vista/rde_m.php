<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 */
 error_reporting(E_ALL);
ini_set('display_errors', '1');
 require_once("../controlador/contabilidad_controller.php");
 //verificacion titulo accion
	$_SESSION['INGRESO']['ti']='';
	if(isset($_GET['ti'])) 
	{
		$_SESSION['INGRESO']['ti']=$_GET['ti'];
	}
	else
	{
		unset( $_SESSION['INGRESO']['ti']);
		$_SESSION['INGRESO']['ti']='DOCUEMNTO ELECTRÓNICO';
	}
	
?>
<link rel="stylesheet" href="../../lib/bower_components/bootstrap-daterangepicker/daterangepicker.css">
<link rel="stylesheet" href="../../lib/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
<link rel="stylesheet" href="../../lib/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css"> 
 <div class="row">
		 <div class="col-xs-12">
			 <div class="box">
			  <div class="box-header">
			  <table width="100%">
						<tr>
						<td>
							<h4 class="box-title">
						
							<a class="btn btn-default"  data-toggle="tooltip" title="Salir del modulo" href="panel.php?sa=s">
								<i ><img src="../../img/png/salir.png" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:100%;'></i> 
							</a>
												
							<a id='l4' class="btn btn-default"  data-toggle="tooltip" title="Enviar correo"
							href='rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
							&Opcb=6&Opcen=0&b=0&ex=pdf&cor=1' disabled>
								<i ><img src="../../img/png/mail.png" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:100%;'></i> 
							</a>
							<a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Exportar PDF"
							href="descarga.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
							&Opcb=6&Opcen=0&b=0&ex=pdf" target="_blank" disabled>
								<i ><img src="../../img/png/pdf.png" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:100%;'></i> 
							</a>
							<a id='l6' class="btn btn-default"  data-toggle="tooltip" title="Exportar XML"
							href="descarga.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
							&Opcb=6&Opcen=0&b=0&ex=xml" target="_blank" disabled>
								<i ><img src="../../img/png/XML.png" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:100%;'></i> 
							</a>
							
							<a id='l7' class="btn btn-default"  data-toggle="tooltip" title="Exportar Excel"
							href="descarga.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
							&Opcb=6&Opcen=0&b=0&ex=1" onclick='modificar1();' target="_blank">
								<i ><img src="../../img/png/table_excel.png" class="user-image" alt="User Image"
								style='font-size:20px; display:block; height:100%; width:100%;'></i> 
							</a>
						  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
							Collapsible Group Item #1
						  </a> -->
						  
						</h4>
					</td>
					<?php
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
								/*"AND SUBSTRING(Clave_Acceso, 1, 2) >= '01' AND SUBSTRING(Clave_Acceso, 1, 2) <= '30'
								AND SUBSTRING(Clave_Acceso, 3, 2) = '06' AND SUBSTRING(Clave_Acceso, 3, 2) = '06'
								AND SUBSTRING(Clave_Acceso, 5, 4) = '2016' AND SUBSTRING(Clave_Acceso, 5, 4) = '2016'"*/
								
								/*$filtros=$filtros." AND SUBSTRING(Clave_Acceso, 1, 2) >= '".$fei[1]."' 
								AND SUBSTRING(Clave_Acceso, 1, 2) <= '".$fef[1]."' 
								AND SUBSTRING(Clave_Acceso, 3, 2) >= '".$fei[0]."' 
								AND SUBSTRING(Clave_Acceso, 3, 2) <= '".$fef[0]."'
								AND SUBSTRING(Clave_Acceso, 5, 4) >= '".$fei[2]."' 
								AND SUBSTRING(Clave_Acceso, 5, 4) <= '".$fef[2]."' ";*/
								
							}
						}
						else
						{
							//si ya existe un filtro caso paginador
							if(isset($_SESSION['FILTRO']['cam2']) AND isset($_SESSION['FILTRO']['cam3']))
							{
								$fei = explode("/", $_SESSION['FILTRO']['cam2']);
								$fef = explode("/", $_SESSION['FILTRO']['cam3']);
								/*"AND SUBSTRING(Clave_Acceso, 1, 2) >= '01' AND SUBSTRING(Clave_Acceso, 1, 2) <= '30'
								AND SUBSTRING(Clave_Acceso, 3, 2) = '06' AND SUBSTRING(Clave_Acceso, 3, 2) = '06'
								AND SUBSTRING(Clave_Acceso, 5, 4) = '2016' AND SUBSTRING(Clave_Acceso, 5, 4) = '2016'"*/
								
								/*$filtros=$filtros." AND SUBSTRING(Clave_Acceso, 1, 2) >= '".$fei[0]."' 
								AND SUBSTRING(Clave_Acceso, 1, 2) <= '".$fef[0]."' 
								AND SUBSTRING(Clave_Acceso, 3, 2) >= '".$fei[1]."' 
								AND SUBSTRING(Clave_Acceso, 3, 2) <= '".$fef[1]."'
								AND SUBSTRING(Clave_Acceso, 5, 4) >= '".$fei[2]."' 
								AND SUBSTRING(Clave_Acceso, 5, 4) <= '".$fef[2]."' ";*/
								$filtros=$filtros." AND convert(datetime,(SUBSTRING(Clave_Acceso, 5, 4)+'/'
								+SUBSTRING(Clave_Acceso, 3, 2)+'/'+SUBSTRING(Clave_Acceso, 1, 2)+' 00:00:00.000 AM'))
								BETWEEN '".$fei[0].$fei[1].$fei[2]."' AND '".$fef[0].$fef[1].$fef[2]."' ";
							}
						}
							//$_POST['fechai']; 
						$total_records=cantidaREG('Trans_Documentos',$filtros);
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
					//enviar correo
					if(isset($_GET['cor']))
					{
						if($_GET['cor']==1)
						{
							//xml
							//vemos la accion
							if(isset($_GET['cl']))
							{
								if($_GET['acc']=='rde')
								{
									ImprimirDocEletronico($_GET['cl'],'xml','Trans_Documentos','Documento_Autorizado,TD','Clave_Acceso','0');
								}
								//documento pdf
								//vemos la accion
								if($_GET['acc']=='rde')
								{
									ImprimirDocEletronico($_GET['cl'],'pdf','Trans_Documentos','Documento_Autorizado,TD','Clave_Acceso','0');
								}
								//buscar correo electronico
								$correo=buscarCorreoDoc($_GET['cl']);
								$emails  = explode(';', $correo);
								
								/*for($i = 0; $i < count($emails); $i++) {
									//$mail->AddAddress($emails[$i]);
									echo $emails[$i];
								}*/
								//die();
								$mail = new PHPMailer(true);
								$mail->CharSet = 'utf-8';
								ini_set('default_charset', 'UTF-8');
								//echo 'TEMP/'.$_GET['cl'].'.pdf';
								//	die();
								try {
									$to = 'orlandoquintero45@gmail.com';
									if (!PHPMailer::validateAddress($to)) {
										throw new phpmailerException("Email address " . $to . " is invalid -- aborting!");
									}
									$mail->isSMTP();
									$mail->SMTPDebug = 0;
									$mail->Host = "mail.diskcoversystem.com";
									$mail->Port = "465";
									//$mail->SMTPSecure = "none";
									$mail->SMTPAuth = true;
									$mail->SMTPSecure = "ssl";
									$mail->Username = "info@diskcoversystem.com";
									$mail->Password = "info2017INFO";
									$mail->setFrom("info@diskcoversystem.com", ":) @dministración");
									for($i = 0; $i < count($emails); $i++) {
										
										if($emails[$i]!='')
										{
											if (!PHPMailer::validateAddress($emails[$i])) {
												throw new phpmailerException("Email address " . $emails[$i] . " is invalid -- aborting!");
											}
											$mail->AddAddress($emails[$i]);
											//echo $emails[$i];
										}
										
									}
									//die();
									//$mail->addAddress($to, 'nombre');
									//$mail->addBCC('diskcover@msn.com ', 'walter');
									$mail->Subject = "Documento Electronico";
									$mail->isHTML(true);
									$body = file_get_contents('plantilla/email2.html');
									$search = array('##USER_NAME##', '##PAYMENT_URI##', '##COMPANY##','##DOCUEMNTO##');
									$replace = array('nombre', 'pago',$_SESSION['INGRESO']['noempr'],$_GET['cl']);
									$newbody = str_replace($search, $replace, $body);
									$mail->WordWrap = 78;
									$mail->msgHTML($newbody, dirname(__FILE__), true); //Create message bodies and embed images
									//$mail->AltBody = 'This is a plain-text message body';
									//$mail->isHTML(false);
									//Build a simple message body
									//$mail->Body = "ppp";
									//xml
									$mail->AddAttachment('TEMP/'.$_GET['cl'].'.xml', 
									'TEMP/'.$_GET['cl'].'.xml');
									//pdf
									//echo 'TEMP/'.$_GET['cl'].'.pdf';
									//die();
									$mail->AddAttachment('TEMP/'.$_GET['cl'].'.pdf', 
									'TEMP/'.$_GET['cl'].'.pdf');
									try {
										$mail->send();
										$results_messages = "Message has been sent using SMTP to " . $to;
										$enviado = true;
										//$objMp->preference_id = $preference['response']['id'];
										//$objMp->save();
										$data['success'] = true;
										$data['message'] = $results_messages;
									} catch (phpmailerException $e) {
										//throw new phpmailerException('Unable to send to: ' . $to . ': ' . $e->getMessage());
										$data['success'] = false;
										$data['message'] = 'Error al procesar su solicitud favor intente mas tarde';
									}
								} catch (phpmailerException $e) {
									$results_messages = $e->errorMessage();
								}
								//die();
								?>
							<script type="text/javascript">
								let timerInterval
								Swal.fire({
								  title: 'Enviando Correo!',
								  html: 'quedan <strong></strong> seconds.',
								  timer: 2000,
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
									 location.href ="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
								&Opcb=6&Opcen=0&b=0";
								  }
								});
								
							</script>
							<?php
							}
							else
							{
								//modal
								?>
								<script>
									//swal("Error!", 'Debe seleccionar una opcion', "warning");
									/*Swal.fire({
									  type: 'error',
									  title: 'Oops...',
									  text: 'Debe escribir su nueva contraseña!',
									  showCancelButton: false,
									  confirmButtonColor: '#3085d6',
									  cancelButtonColor: '#d33',
									  confirmButtonText: 'OK!'
									}).then((result) => {
									  if (result.value) {
										location.href="logout.php";
									  } 
									});*/
									Swal.fire({
									  type: 'error',
									  title: 'ERROR',
									  text: 'Debe seleccionar una opcion!'
									}).then((result) => {
									  if (result.value) {
										location.href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti="+
										"<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0";
									  } 
									});
								</script>
								
								<?php
							}
							//echo "The email message was sent.";
							
						}
						//Omar Ivan Mayorga Enriquez 1802249944
						//Galo Hernan Garcia Tamayo 1802858983
					}
						?>
					<form action="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
								&Opcb=6&Opcen=0&b=0" method="post" class="sidebar-form">
						<td>
							<label>Tipo Documento</label>
						</td>
						<td>
								<select class="form-control" name="tipo">
									<option value='seleccione'>seleccione</option>
									<?php
									if(isset($_SESSION['FILTRO']['cam1']))
									{
									?>
										<option value='<?php echo $_SESSION['FILTRO']['cam1'];?>' selected><?php echo $_SESSION['FILTRO']['cam1'];?></option>
									<?php
									}
									?>
									<?php select_option('Trans_Documentos','TD','TD','1=1 group by TD'); ?>
								</select>
						</td>
						<td>
							<label>Fecha del</label>
						</td>
						<td>
						<!--<input type="text" name="date" placeholder="yyyy-mm-dd" onkeyup="
						  var date = this.value;
						  if (date.match(/^\d{4}$/) !== null) {
							 this.value = date + '-';
						  } else if (date.match(/^\d{4}\-\d{2}$/) !== null) {
							 this.value = date + '-';
						  }" maxlength="10">
							<input type="text" class="form-control" data-inputmask="'alias': 'dd/mm/yyyy'" data-mask>-->
							<?php
							if(isset($_SESSION['FILTRO']['cam2']))
							{
							?>
								<input type="text" class="form-control pull-right" id="fechai" placeholder="dd/mm/yyyy" 
								name="fechai" value='<?php echo $_SESSION['FILTRO']['cam2'];?>'>

							<?php
							}
							else
							{
							?>
								<input type="text" class="form-control pull-right" id="fechai" placeholder="dd/mm/yyyy" 
								name="fechai">
							<?php
							}
							?>
						</td>
						<td>
							<label>&nbsp; Al &nbsp; </label>
						</td>
						<td>
							<?php
							if(isset($_SESSION['FILTRO']['cam3']))
							{
							?>
								<input type="text" class="form-control pull-right" id="fechaf" placeholder="dd/mm/yyyy" 
								name="fechaf" value='<?php echo $_SESSION['FILTRO']['cam3'];?>'>

							<?php
							}
							else
							{
							?>
								<input type="text" class="form-control pull-right" id="fechaf" placeholder="dd/mm/yyyy"
								name="fechaf">
							<?php
							}
							?>
						</td>
						
						<td>
							<span class="input-group-btn">
								<a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
								&Opcb=6&Opcen=0&b=0">
									<input type="submit" name="Buscar" id="search-btn" class="btn btn-flat" value="Buscar" />
									<i class="fa fa-search"></i>
								</a>
							</span>
						</td>
					 </form>
					
						<td>
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
							<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&pagina=1">1</a></li>
							<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&pagina=<?php echo ($pagina-1); ?>">&laquo;</a></li>
						 <?php
						}
						//echo $start_loop.' '.$end_loop;
						for($i=$start_loop; $i<=$end_loop; $i++)
						{     
							//echo "<a class='pagina' href='pagina.php?pagina=".$i."'>".$i."</a>";
								?>
							<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&pagina=<?php echo $i; ?>"><?php echo $i; ?></a></li>
							 <?php
						}
						if($pagina <= $end_loop)
						{
							//echo "<a class='pagina' href='pagina.php?pagina=".($pagina + 1)."'>>></a>";
							//echo "<a class='pagina' href='pagina.php?pagina=".$total_pages."'>Última</a>";
							?>
							<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&pagina=<?php echo $pagina+1; ?>">&raquo;</a></li>
							<li><a href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
						&Opcb=6&Opcen=0&b=0&pagina=<?php echo $total_pages; ?>"><?php echo $total_pages; ?></a></li>
							
						 <?php
						}
					?>
						
								
							  </ul>
							</div>
						</td>
					<?php
					}
					?>
					  </tr>
					  </table>	
			  </div>
			 </div>
		 </div>
	  </div>
	<script>
		//Date picker
		$('#fechai').datepicker({
		  autoclose: true
		});
		$('#fechaf').datepicker({
		  autoclose: true
		});
		//valo=$("#fechai").val();
		//var valo = $("#fechai").val().split('/');
		//$("#fechai").val()
		//alert(valo[0]+' '+valo[1]);
	</script>