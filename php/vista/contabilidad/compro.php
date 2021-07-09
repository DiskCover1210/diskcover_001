<?php

    if(!isset($_SESSION)) 
	 		session_start();
	
	
?>
<!--<h2>Balance de Comprobacion/Situación/General</h2>-->
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
		$_SESSION['INGRESO']['ti']='BALANCE DE COMPROBACIÓN';
	}
	require_once("../controlador/contabilidad_controller.php");
?>
 <div class="row">
		 <div class="col-xs-12">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-header">
					<table width="100%">
						<tr>
							<td width="35%">
								<h4 class="box-title">
									<a class="btn btn-default" title="Salir del modulo" href="./contabilidad.php?mod=contabilidad#">
										<i ><img src="../../img/png/salire.png" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:100%;'></i> 
									</a>
									<a id='l7' class="btn btn-default"  data-toggle="tooltip" title="Exportar Excel"
									href="descarga.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&ti=<?php echo $_SESSION['INGRESO']['ti']; ?>
									&Opcb=6&Opcen=0&b=0&ex=1" onclick='modificar1();' target="_blank">
										<i ><img src="../../img/png/table_excel.png" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:100%;'></i> 
									</a>
									<a id='l1' class="btn btn-default"  data-toggle="tooltip" title="Modificar el comprobante"
									href="contabilidad.php?mod=contabilidad&acc=compro&acc1=Comprobantes Procesados&b=1">
										<i ><img src="../../img/png/modificar.png" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:100%;'></i> 
									</a>
									<a id='l2' class="btn btn-default"  data-toggle="tooltip" title="Anular comprobante"
									href="contabilidad.php?mod=contabilidad&acc=compro&acc1=Comprobantes Procesados&b=1">
										<i ><img src="../../img/png/anular.png" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:100%;'></i> 
									</a>
									<a id='l3' class="btn btn-default"  data-toggle="tooltip" title="Autorizar comprobante autorizado">
										<i ><img src="../../img/png/autorizar.png" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:100%;'></i> 
									</a>
									<a id='l4' class="btn btn-default"  data-toggle="tooltip" title="Realizar una copia al comprobante"
									href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta balance de Comprobación&ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1">
										<i ><img src="../../img/png/copiar.png" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:100%;'></i> 
									</a>
									<a id='l5' class="btn btn-default"  data-toggle="tooltip" title="Copiar a otra empresa el comprobante"
									href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Presenta estado de situación (general)&ti=ESTADO SITUACIÓN&Opcb=5&Opcen=1&b=0"
									>
										<i ><img src="../../img/png/copiare.png" class="user-image" alt="User Image"
										style='font-size:20px; display:block; height:100%; width:100%;'></i> 
									</a>
									
								</h4>
							</td>
							<td>
								<?php echo $_SESSION['INGRESO']['item']; ?> 
							</td>
							<td>
								<button type="submit" class="btn btn-default active" onclick="reset_('comproba','CD');" id='CD'>Diario</button>
								<button type="submit" class="btn btn-default" onclick="reset_('comproba','CI');" id='CI'>Ingreso</button>
								<button type="submit" class="btn btn-default" onclick="reset_('comproba','CE');" id='CE'>Egreso</button>
								<button type="submit" class="btn btn-default" onclick="reset_('comproba','ND');" id='ND'>N/D</button>
								<button type="submit" class="btn btn-default" onclick="reset_('comproba','NC');" id='NC'>N/C</button>
								<input id="tipoc" name="tipoc" type="hidden" value="CD">
							</td>
							<td>
								No.
							</td>
							<td>
								<select class="form-control" name="tipo" id='mes' onchange="reset_('comproba','');">
									<option value='0'>Todos</option>
									
									<?php echo  Tabla_Dias_Meses();
									// select_option('Tabla_Dias_Meses','No_D_M','Dia_Mes',"Tipo='M' AND No_D_M<>0 "); 
									?>
								</select>
							</td>
							<td>
								&nbsp;
							</td>
							<td>
							    <div class='comproba'>
									<select class="form-control" name="tipo" onchange="buscar('comproba');">
										<option value='seleccione'>Seleccionar</option>
										<?php
										if(isset($_SESSION['FILTRO']['cam1']))
										{
										?>
											<option value='<?php echo $_SESSION['FILTRO']['cam1'];?>' selected><?php echo $_SESSION['FILTRO']['cam1'];?></option>
										<?php
										}
										?>
										<?php //select_option('Trans_Documentos','TD','TD','1=1 group by TD'); ?>
									</select>
								</div>
							</td>
						</tr>
					</table>
								  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
									Collapsible Group Item #1
								  </a> -->
			  </div>
			 </div>
		 </div>
	  </div>
	<script>
	function reset_(idMensaje,tipoc)
	{
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
		$('div.'+idMensaje).html('<select class="form-control" name="tipo" onchange="buscar(\'comproba\');">'+
									'<option value="seleccione">Seleccionar</option>'+
									'</select>'); 
		
		buscar('comproba');
	}
	
	</script>

<div class="panel box box-primary">
	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		<div class="box table-responsive" width="100%" height="100%">
			<div id='pdfcom'>
				
			</div>
		  </div>
		   
	</div>
  </div>
</div>
<script>
	$( document ).ready(function() {
		buscar('comproba');
	});

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
