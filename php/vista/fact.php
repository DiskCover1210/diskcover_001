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


$IP_VPN_RUTA = $_SESSION['INGRESO']['IP_VPN_RUTA'];
$Base_Datos = $_SESSION['INGRESO']['Base_Datos'];
$Usuario_DB = $_SESSION['INGRESO']['Usuario_DB'];
$Contraseña_DB = $_SESSION['INGRESO']['Contraseña_DB'];
$Tipo_Base = $_SESSION['INGRESO']['Tipo_Base'];
$Puerto = $_SESSION['INGRESO']['Puerto'];
$ruta_cer=$_SESSION['INGRESO']['Ruta_Certificado'];
$clave_cer=$_SESSION['INGRESO']['Clave_Certificado'];
$ambiente=$_SESSION['INGRESO']['Ambiente'];
$nom_com=$_SESSION['INGRESO']['Nombre_Comercial'];
$raz_soc=$_SESSION['INGRESO']['Razon_Social'];
$ruc = $_SESSION['INGRESO']['RUC'];
$dir = $_SESSION['INGRESO']['Direccion'];

//Encripta información:
$IP_VPN_RUTA = $encriptar($IP_VPN_RUTA);
$Base_Datos = $encriptar($Base_Datos);
$Usuario_DB = $encriptar($Usuario_DB);
$Contraseña_DB = $encriptar($Contraseña_DB);
$Tipo_Base = $encriptar($Tipo_Base);
$Puerto = $encriptar($Puerto);
$ruta_cer = $encriptar($ruta_cer);
$clave_cer = $encriptar($clave_cer);
$ambiente = $encriptar($ambiente);
$nom_com = $encriptar($nom_com);
$raz_soc = $encriptar($raz_soc);
$ruc = $encriptar($ruc);
$dir = $encriptar($dir);
$item = $encriptar($_SESSION['INGRESO']['item']);
$periodo = $encriptar($_SESSION['INGRESO']['periodo']);
//tipo documkento
$cod_doc = $encriptar('01');
$tc=$encriptar('FA');
	//echo ' jjj '.$_SESSION['autentificado'];
	//die();
	//cuerpo
?>
	  <?php

    if(!isset($_SESSION)) 
	 		session_start();
	//para validar que solo este en esta pagina
	//$_SESSION['INGRESO']['solouna']=0;
	//echo $_SESSION['INGRESO']['solouna'].' fffff ';
?>
<!--<h2>Balance de Comprobacion/Situación/General</h2>-->

<div class="panel box box-primary">
	<div id="collapseOne" class="panel-collapse collapse in">
		<div class="box-body">
			<div class="box table-responsive">  

	
			<div class="tab-content" style="background-color:#E7F5FF">
				<div id="home" class="tab-pane fade in active">									
				   <div class="table-responsive" id="tabla_" style="overflow-y: scroll; overflow-x: scroll; height:450px; width: auto;">
						 <!--  /.box-header -->
						<?php
						//verificamos sesion sql
						if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
						{
							$database=$_SESSION['INGRESO']['Base_Datos'];
							$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
							$user=$_SESSION['INGRESO']['Usuario_DB'];
							$password=$_SESSION['INGRESO']['Contraseña_DB'];
							$usuario=getUsuario();
							$_SESSION['INGRESO']['CodigoU']=$usuario[0]['CodigoU'];
							$_SESSION['INGRESO']['Nombre_Completo']=$usuario[0]['Nombre_Completo'];
							//verificamos en acceso si puede ingresar a esa empresa
							$_SESSION['INGRESO']['accesoe']='0';
							$_SESSION['INGRESO']['modulo'][0]='0';
							$permiso=getAccesoEmpresas();
						}
						$OpcDG=null;
						//border
						$b=null;
						//order
						$ord=null;
						if(isset($_GET['ord'])) 
						{
							$ord=$_GET['ord'];
						}
						//si escogio una opcion de radio buton
						if(isset($_GET['OpcDG'])) 
						{
							$OpcDG=$_GET['OpcDG'];
						}
						//border
						if(isset($_GET['b'])) 
						{
							$b=$_GET['b'];
						}
						//llamamos a la funcion para mostrar la grilla o exportar a excel , pdf
						
						if(isset($_GET['ex'])) 
						{
							if($_GET['ex']==1) 
							{
								if(isset($_GET['Opcb'])) 
								{
									ListarFacturacion($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,'2');
								}
								else
								{
									ListarFacturacion('REPORTE FACTURACION',null,null,$OpcDG,$b,'2');
								}
							}					
						}
						else
						{
							if(isset($_GET['Opcb'])) 
							{
								$balance=ListarFacturacion($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,null,'1,3,clave',
								$start_from, $record_per_page, $filtros,$ord,$cam4);
							}
							else
							{
								$balance=ListarFacturacion('REPORTE FACTURACION',null,null,$OpcDG,$b,null,'1,3,clave',
								$start_from, $record_per_page,$filtros,$ord,$cam4);
							}
							//echo $start_from.' fgfgfg '.$record_per_page;
						}
						?>
						<?php
							if(1==0)
							{
						?>
							  <div class="row ">
								  <div class="col-md-6 col-sm-6 col-xs-6 pad-adjust">
									 <a href="gde.php?q=q" target='_blank'><button type="submit" class="btn btn-default btn-xs active"  id='CD' style="width: 15%;">
									 Generar detalle</button></a>
								  </div>
							  </div>
						<?php
							}
						?>
						<script>
							function autorizar() 
							{
								var datos=document.getElementsByName("clave[]");
								var auto='1';
								var datos1='';
								var ii=0;
								for(var i=0; i<datos.length; i++) 
								{
									if(datos[i].checked==true)
									{
										datos1=datos1+''+datos[i].value+','+datos[i].checked+';';
										//alert(" Elemento: " + datos[i].value + "\n Seleccionado: " + datos[i].checked);
										ii++;
									}
								}
								if(ii==0)
								{
									Swal.fire({
										type: 'error',
										title: 'Debe seleccionar un registro',
										text: ''
									});
								}
								else
								{
									var mapForm = document.createElement("form");
									mapForm.target = "_blank";    
									mapForm.method = "POST";
									mapForm.action = "../entidades/autorizar.php";
									
									// Create an input
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "ajax_page";
									mapInput.value = "autorizar";

									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "datos";
									mapInput.value = datos1;

									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "IP";
									mapInput.value = "<?php echo $IP_VPN_RUTA; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "Ba";
									mapInput.value = "<?php echo $Base_Datos; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "Us";
									mapInput.value = "<?php echo $Usuario_DB; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "Co";
									mapInput.value = "<?php echo $Contraseña_DB; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "Ti";
									mapInput.value = "<?php echo $Tipo_Base; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "Pu";
									mapInput.value = "<?php echo $Puerto; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "ru_ce";
									mapInput.value = "<?php echo $ruta_cer; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "cl_ce";
									mapInput.value = "<?php echo $clave_cer; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "amb";
									mapInput.value = "<?php echo $ambiente; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "nom_com";
									mapInput.value = "<?php echo $nom_com; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "raz_soc";
									mapInput.value = "<?php echo $raz_soc; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "ruc";
									mapInput.value = "<?php echo $ruc; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "dir";
									mapInput.value = "<?php echo $dir; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "cod_doc";
									mapInput.value = "<?php echo $cod_doc; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "tc";
									mapInput.value = "<?php echo $tc; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "item";
									mapInput.value = "<?php echo $item; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									var mapInput = document.createElement("input");
									mapInput.type = "text";
									mapInput.name = "peri";
									mapInput.value = "<?php echo $periodo; ?>";
																	
									// Add the input to the form
									mapForm.appendChild(mapInput);
									
									// Add the form to dom
									document.body.appendChild(mapForm);
									
									// Just submit
									mapForm.submit();
									document.body.removeChild(mapForm);
								}
								/*$.ajax({
									data:  {
										'datos':datos1,
										'ajax_page':'autorizar'
									},
									url:   '../entidades/autorizar.php',
									type:  'post',
									beforeSend: function () {
											//$("#pdfcom1").html("");
											$('#myModal_espera').modal('show');
											window.open('../entidades/autorizar.php?ajax_page=autorizar', "_blank");
									},
									success:  function (response) {
											$('#myModal_espera').modal('hide');
											//$("#pdfcom1").html(response);
											
									}
								});*/
								
								/*for(var i=0; i<datos.length; i++) 
								{
									alert(" Elemento: " + datos[i].value + "\n Seleccionado: " + datos[i].checked);
								}*/
							}
							function orde(id) 
							{
								//alert(id);
								//document.getElementById('id_'+id).setAttribute=( "onclick","orde('"+id+"d');");
								var l4="fact.php?mod=contabilidad&acc=fact&acc1=Reporte%20Facturación&ti=Facturacion&Opcb=6&Opcen=0&b=0";
								//agregar fechas				
								//var l1=l1+'&OpcDG='+texto;
								if(readCookie('ord')==null || readCookie('ord')==0)
								{
									var l4=l4+'&ord='+id+'_A';
									document.cookie = "ord=1; ";
								}
								else
								{
									var l4=l4+'&ord='+id+'_D';
									document.cookie = "ord=0; ";
								}
								//alert(l4);
								location.href=l4;
							}
							 function validarc(id,ta) 
							 {
								//verificamos que este seleccionado
								if (document.getElementById(id).checked)
								{
									$('#l4').attr("disabled", false);
									$('#l5').attr("disabled", false);
									$('#l6').attr("disabled", false);
								}
								else
								{
									$('#l4').attr("disabled", true);
									$('#l5').attr("disabled", true);
									$('#l6').attr("disabled", true);
								}
								
								var select = document.getElementById(id), //El <select>
								value = select.value; 
								
								var l4=$('#l4').attr("href");
								//agregar fechas				
								//var l1=l1+'&OpcDG='+texto;
								var l4=l4+'&cl='+value+'';
								//asignamos
								$("#l4").attr("href",l4);
								
								var l5=$('#l5').attr("href");
								//agregar fechas				
								//var l1=l1+'&OpcDG='+texto;
								var l5=l5+'&cl='+value+'';
								//asignamos
								$("#l5").attr("href",l5);
								
								var l6=$('#l6').attr("href");
								//agregar fechas				
								//var l1=l1+'&OpcDG='+texto;
								var l6=l6+'&cl='+value+'';
								//asignamos
								$("#l6").attr("href",l6);
								//alert(value);
								
								//otro link
								var l7=$('#l7').attr("href");
								//agregar fechas				
								//var l1=l1+'&OpcDG='+texto;
								var l7=l7+'&cl='+value+'';
								//asignamos
								$("#l7").attr("href",l7);
							 }
						 </script>
						<!-- /.box-body -->
					</div>
				</div>		  	    	  	    	
			</div>
			</div>
		</div>		  	    	  	    	
	</div>
</div>
<?php

	require_once("footer.php");
	
?>			
	
