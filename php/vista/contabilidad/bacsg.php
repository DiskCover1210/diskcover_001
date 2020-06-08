<?php

    if(!isset($_SESSION)) 
	 		session_start();
	
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
		$_SESSION['INGRESO']['ti']='BALANCE DE COMPROBACIÓN';
	}
?>
 <div class="row" id='submenu'>
		 <div class="col-xs-12">
			 <div class="box" style='margin-bottom: 5px;'>
			  <div class="box-header">
					<h4 class="box-title">
						<a class="btn btn-default"  data-toggle="tooltip" title="Salir del modulo" href="panel.php?sa=s">
							<i ><img src="../../img/png/salir.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l1' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance de Comprobación"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&
						ti=BALANCE DE COMPROBACIÓN&Opcb=1&Opcen=0&b=1&bm=0&fechai=<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechai'])) ?>
						&fechaf=<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechaf'])) ?>">
							<i ><img src="../../img/png/pbc.png" class="user-image" alt="User Image"
							style='font-size:20px; display:block; height:100%; width:100%;'></i> 
						</a>
						<a id='l2' class="btn btn-default"  data-toggle="tooltip" title="Procesar balance mensual"
						href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Procesar balance mensual&
						ti=BALANCE MENSUAL&Opcb=2&Opcen=1&b=1&bm=1&fechai=<?php echo $_SESSION['INGRESO']['Fechai']; ?>
						&fechaf=<?php echo $_SESSION['INGRESO']['Fechaf']; ?>">
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
						
						
					  <!--<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
						Collapsible Group Item #1
					  </a> -->
					</h4>
			  </div>
			 </div>
		 </div>
	  </div>

<?php
	//echo $_SESSION['INGRESO']['Opc'].' ------- '.$_SESSION['INGRESO']['Sucursal'];
	//llamamos spk
	if(isset($_GET['bm'])) 
	{
		if(isset($_GET['OpcBs']) and $_GET['OpcBs']!='') 
		{
			sp_Procesar_Balance($_SESSION['INGRESO']['Opc'],$_SESSION['INGRESO']['Sucursal'],$_SESSION['INGRESO']['item'],
			$_SESSION['INGRESO']['periodo'],$_GET['fechai'],$_GET['fechaf'],$_GET['bm'],$_GET['OpcBs']);
		}
		else
		{
			sp_Procesar_Balance($_SESSION['INGRESO']['Opc'],$_SESSION['INGRESO']['Sucursal'],$_SESSION['INGRESO']['item'],
			$_SESSION['INGRESO']['periodo'],$_GET['fechai'],$_GET['fechaf'],$_GET['bm'],'.');
		}
		//0,0,'003','.', '20190101','20191231', 0 
	
		
		//verificamos periodo	
		if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) ) 
		{
			if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
			{
				$periodo=getPeriodoActualSQL();
				//echo $periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
			}
			else
			{
				//mysql que se valide en controlador
				//echo ' ada '.$_SESSION['INGRESO']['Tipo_Base'];
				$periodo=getPeriodoActualSQL();
				//echo $periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
				$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
			}
		}
	}
			?>
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
				<div class="form-group">
					<label for="fechai" class="col-sm-1 control-label">Desde: </label>
					<div class="col-md-2">
						<div class="input-group date">
							
							  <div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							  </div>
							  <!--<input type="text" class="form-control pull-right" id="desde" placeholder="01/01/2019"
							  value='<?php echo $_SESSION['INGRESO']['Fechai']; ?>' name="fechai" onchange='modificar("NI");'>-->
							  <input type="date" class="form-control pull-right input-sm" id="desde" placeholder="01/01/2019"
							 name="fechai" onKeyPress="return soloNumeros(event)"  maxlength="10" 
							 onchange='modificar("NI");' pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
							  value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechai'])) ?>'>
						</div>
					</div>
				
					<label for="fechaf" class="col-sm-1 control-label">hasta: </label>
					<div class="col-md-2">
						<div class="input-group date">
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i>
							</div>
							<!-- <input type="text" class="form-control pull-right" id="hasta" placeholder="01/01/2019"
							value='<?php echo $_SESSION['INGRESO']['Fechaf']; ?>' name="fechaf" onchange='modificar("NI");'>-->
							<input type="date" class="form-control pull-right input-sm" id="hasta" placeholder="01/01/2019"
							 name="fechaf" 
							onKeyPress="return soloNumeros(event)"  maxlength="10" onchange='modificar("NI");'
							pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" 
							value='<?php echo date('Y-m-d',strtotime($_SESSION['INGRESO']['Fechaf'])) ?>'>
						</div>
					</div>
					
					 <div class="col-md-2">
						<label for="tipo" class="control-label">Tipo balance:  </label>
						<label>
						<?php
						$check='';
						/*if(isset($_GET['OpcCE'])) 
						{
							if($_GET['OpcCE']=='1') 
							{
								$check='checked';
							}
						}
						*/
						?>
						  <input type="checkbox" class="minimal" name="optionsCheck" id="optionsCheck1"
						  <?php echo $check; ?> onclick='modificarb("optionsCheck1");'>
						  
						</label>
					</div>
					
					<div class="col-md-2">
						<label for="tipo" class="control-label">Tipo Presentación cuentas: </label>
					</div>
						<label>
						  <input type="radio" name="optionsRadios" id="optionsRadios1" value="" checked>
						  Todos 
						</label>
						<label>
						  <input type="radio" name="optionsRadios" id="optionsRadios2" value="G" onclick='modificar("G");'>
						  Grupo 
						</label>
						<label>
						  <input type="radio" name="optionsRadios" id="optionsRadios3" value='D' onclick='modificar("D");' >
						  Detalle 
						</label>
					<div class="col-md-2">
						<label for="tipo" class="control-label">Balance:  </label>
						<label>
						<?php
						$check='';
						/*if(isset($_GET['OpcCE'])) 
						{
							if($_GET['OpcCE']=='1') 
							{
								$check='checked';
							}
						}
						*/
						?>
						  <input type="checkbox" class="minimal" name="tbalan" id="tbalan"
						  <?php echo $check; ?> onclick='modificarb1("tbalan");'>
						 
						  
						</label>
						<div id='ineg' style='display:none;float:right;' >
							<select class="form-control pull-right input-sm" name="tipob" id='tipob' onChange="return agregarSu('tipob');">
								<option value='seleccione'>seleccione</option>
								<!-- 
									SELECT       Codigo, Detalle
									FROM            Catalogo_SubCtas
									WHERE        (TC = 'CC')
								-->
								<?php select_option('Catalogo_SubCtas','Codigo','Detalle',
								" (TC = 'CC') AND item='".$_SESSION['INGRESO']['item']."' AND periodo='".$_SESSION['INGRESO']['periodo']."' order by Detalle"); ?>
							</select>
						</div>
					</div>
				</div>
            </div>
             <!--  /.box-header -->
			<?php
			//verificamos sesion sql
			if(isset($_SESSION['INGRESO']['IP_VPN_RUTA'])) 
			{
				$database=$_SESSION['INGRESO']['Base_Datos'];
				$server=$_SESSION['INGRESO']['IP_VPN_RUTA'];
				$user=$_SESSION['INGRESO']['Usuario_DB'];
				$password=$_SESSION['INGRESO']['Contraseña_DB'];
			}
			$OpcDG=null;
			//border
			$b=null;
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
			//para opcion de mostrar el balance con nomenclatura nacional o internacional
			$OpcCE=null;
			if(isset($_GET['OpcCE'])) 
			{
				$OpcCE=$_GET['OpcCE'];
			}
			//llamamos a la funcion para mostrar la grilla o exportar a excel , pdf
			if(isset($_GET['ex'])) 
			{
				if($_GET['ex']==1) 
				{
					if(isset($_GET['Opcb'])) 
					{
						exportarExcel($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,$OpcCE);
					}
					else
					{
						exportarExcel('BALANCE DE COMPROBACIÓN',null,null,$OpcDG,$b,$OpcCE);
					}
				}					
			}
			else
			{
				if(isset($_GET['Opcb'])) 
				{
					
					$balance=ListarTipoDeBalanceSQL($_GET['ti'],$_GET['Opcb'],$_GET['Opcen'],$OpcDG,$b,$OpcCE);
					
				}
				else
				{
					$balance=ListarTipoDeBalanceSQL('BALANCE DE COMPROBACIÓN',null,null,$OpcDG,$b,$OpcCE);
				}
			}
			?>
			 <!--<div class="box-footer clearfix">
              <ul class="pagination pagination-sm no-margin pull-right">
                <li><a href="#">&laquo;</a></li>
                <li><a href="#">1</a></li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#">&raquo;</a></li>
              </ul>
            </div>-->
            <!-- /.box-body -->
          </div>
	</div>
  </div>
</div>
<script>
	$(".loader1").hide();
	//Date picker
    /*$('#desde').datepicker({
		dateFormat: 'dd/mm/yyyy',
      autoclose: true
    });
	$('#hasta').datepicker({
		dateFormat: 'dd/mm/yyyy',
      autoclose: true
    });*/
	//modificar url
	function modificar(texto){
		if(texto=='NI')
		{
			texto='';
		}
		var fechai = document.getElementById('desde');
		var fechaf = document.getElementById('hasta');
		//alert(fechai.value+' '+fechaf.value);
	 /* 
		//cambiamos formato
		var ca1 = fechai.split('/');
		alert(ca1.length);
		var ca2 = fechaf.split('/');
		alert(ca2.length);
		for(var i=0;i < ca.length;i++) {

		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) {
		  return decodeURIComponent( c.substring(nameEQ.length,c.length) );
		}

	  }*/
		var l1=$('#l1').attr("href");  
		var l1=l1+'&OpcDG='+texto+'&fechai='+fechai.value+'&fechaf='+fechaf.value;
		//asignamos
		$("#l1").attr("href",l1);
		
		var l2=$('#l2').attr("href");  
		var l2=l2+'&OpcDG='+texto+'&fechai='+fechai.value+'&fechaf='+fechaf.value;
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
	function modificarb1(id)
	{
		texto='';
		if (document.getElementById(id).checked)
		{
			//alert('Seleccionado');
			document.getElementById("ineg").style.display = "block";
		}
		else
		{
			document.getElementById("ineg").style.display = "none";
			//alert('ppp');
			var l1=$('#l1').attr("href");  
			var l1=l1+'&OpcBs='+texto;
			//asignamos
			$("#l1").attr("href",l1);
			
			var l2=$('#l2').attr("href");  
			var l2=l2+'&OpcBs='+texto;
			//asignamos
			$("#l2").attr("href",l2);
			
			var l4=$('#l4').attr("href");  
			var l4=l4+'&OpcBs='+texto;
			//asignamos
			$("#l4").attr("href",l4);
			
			var l5=$('#l5').attr("href");  
			var l5=l5+'&OpcBs='+texto;
			//asignamos
			$("#l5").attr("href",l5);
			
			var l6=$('#l6').attr("href");  
			var l6=l6+'&OpcBs='+texto;
			//asignamos
			$("#l6").attr("href",l6);
		}
	}
	function agregarSu(id)
	{
		var select = document.getElementById(id); //El <select>
		texto = select.value;
		
		var l1=$('#l1').attr("href");  
		var l1=l1+'&OpcBs='+texto;
		//asignamos
		$("#l1").attr("href",l1);
		
		var l2=$('#l2').attr("href");  
		var l2=l2+'&OpcBs='+texto;
		//asignamos
		$("#l2").attr("href",l2);
		
		var l4=$('#l4').attr("href");  
		var l4=l4+'&OpcBs='+texto;
		//asignamos
		$("#l4").attr("href",l4);
		
		var l5=$('#l5').attr("href");  
		var l5=l5+'&OpcBs='+texto;
		//asignamos
		$("#l5").attr("href",l5);
		
		var l6=$('#l6').attr("href");  
		var l6=l6+'&OpcBs='+texto;
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
