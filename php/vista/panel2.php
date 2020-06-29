 </div> 
  </div> 
</section>

	
	<!--<h3 class="box-title">Striped Full Width Table</h3>-->

		<?php
			//llamamos a los parciales para menus
			if (isset($_SESSION['INGRESO']['accion'])) 
			{ 
				//Mayorización
				if ($_SESSION['INGRESO']['accion']=='macom') 
				{
					require_once("contabilidad/macom_m.php");
				}
				//Balance de Comprobacion/Situación/General
				if ($_SESSION['INGRESO']['accion']=='bacsg') 
				{
					//require_once("contabilidad/bacsg_m.php");
				}
				//reporte documentos electronicos
				if ($_SESSION['INGRESO']['accion']=='rde') 
				{
					require_once("rde_m.php");
				}
				//reporte facturacion
				if ($_SESSION['INGRESO']['accion']=='fact') 
				{
					require_once("fact_m.php");
				}
				if ($_SESSION['INGRESO']['accion']=='compro') 
				{
					require_once("contabilidad/compro_m.php");
				}	
				if ($_SESSION['INGRESO']['accion']=='cambioe') 
				{
					require_once("empresa/cambioe_m.php");
				}	
			}	
				?>	
		<!--</div>-->
		<?php
		//echo $_SESSION['INGRESO']['empresa'].' sdsddssd ';
			if($_SESSION['INGRESO']['RUCEnt']=='0590031984001'  ) 
			{
				
				//echo "entro";
				//die();
				if(!isset($_SESSION['INGRESO']['IP_VPN_RUTA']) ) 
				{
					//para redireccionar
					//$_SESSION['INGRESO']['solouna']=0;
					//hacemos sesion automatica
					$_SESSION['INGRESO']['noempr']='CALZACUERO CA';
					$_SESSION['INGRESO']['item']='003';
					
					$empresa=getEmpresasId('842');
					foreach ($empresa as &$valor) 
					
					{
						$_SESSION['INGRESO']['IP_VPN_RUTA']=$valor['IP_VPN_RUTA'];
						$_SESSION['INGRESO']['Base_Datos']=$valor['Base_Datos'];
						$_SESSION['INGRESO']['Usuario_DB']=$valor['Usuario_DB'];
						$_SESSION['INGRESO']['Contraseña_DB']=$valor['Contraseña_DB'];
						$_SESSION['INGRESO']['Tipo_Base']=$valor['Tipo_Base'];
						$_SESSION['INGRESO']['Puerto']=$valor['Puerto'];
						$_SESSION['INGRESO']['Fecha']=$valor['Fecha'];
						$_SESSION['INGRESO']['Logo_Tipo']=$valor['Logo_Tipo'];
						$_SESSION['INGRESO']['periodo']='.';
						$_SESSION['INGRESO']['Razon_Social']=$valor['Razon_Social'];
						//echo $_SESSION['INGRESO']['IP_VPN_RUTA'];
						//obtenemos el resto de inf. de la empresa tales como correo direccion
						$empresa_d=getEmpresasDE($_SESSION['INGRESO']['item'],$_SESSION['INGRESO']['noempr']);
						$_SESSION['INGRESO']['Direccion']=$empresa_d[0]['Direccion'];
						$_SESSION['INGRESO']['Telefono1']=$empresa_d[0]['Telefono1'];
						$_SESSION['INGRESO']['FAX']=$empresa_d[0]['FAX'];
						$_SESSION['INGRESO']['Nombre_Comercial']=$empresa_d[0]['Nombre_Comercial'];
						$_SESSION['INGRESO']['Razon_Social']=$empresa_d[0]['Razon_Social'];
						$_SESSION['INGRESO']['Sucursal']=$empresa_d[0]['Sucursal'];
						$_SESSION['INGRESO']['Opc']=$empresa_d[0]['Opc'];
						$_SESSION['INGRESO']['noempr']=$empresa_d[0]['Empresa'];
						$_SESSION['INGRESO']['S_M']=$empresa_d[0]['S_M'];						
						$_SESSION['INGRESO']['Formato_Cuentas']=$empresa_d[0]['Formato_Cuentas'];
						$_SESSION['INGRESO']['porc']=$empresa_d[0]['porc'];
						$_SESSION['INGRESO']['Ambiente']=$empresa_d[0]['Ambiente'];
				        $_SESSION['INGRESO']['Obligado_Conta']=$empresa_d[0]['Obligado_Conta'];
				        $_SESSION['INGRESO']['LeyendaFA']=$empresa_d[0]['LeyendaFA'];
						//verificamos si es sql server o mysql para consultar periodos
						if(isset($_SESSION['INGRESO']['IP_VPN_RUTA']) ) 
						{
							if($_SESSION['INGRESO']['Tipo_Base']=='SQL SERVER')
							{
								$periodo=getPeriodoActualSQL();
								//echo $periodo[0]['Fecha_Inicial'];
								
								$usuario=getUsuario();
								$_SESSION['INGRESO']['CodigoU']=$usuario[0]['CodigoU'];
								$_SESSION['INGRESO']['Nombre_Completo']=$usuario[0]['Nombre_Completo'];
								//verificamos en acceso si puede ingresar a esa empresa
								$_SESSION['INGRESO']['accesoe']='0';
								$_SESSION['INGRESO']['modulo'][0]='0';
								$permiso=getAccesoEmpresas();
							}
							else
							{
								//mysql que se valide en controlador
								//echo ' ada '.$_SESSION['INGRESO']['Tipo_Base'];
								$periodo=getPeriodoActualSQL();
								//echo $periodo[0]['Fecha_Inicial'];
								//$_SESSION['INGRESO']['Fechai']=$periodo[0]['Fecha_Inicial'];
								//$_SESSION['INGRESO']['Fechaf']=$periodo[0]['Fecha_Final'];
								$usuario=getUsuario();
								$_SESSION['INGRESO']['CodigoU']=$usuario[0]['CodigoU'];
								$_SESSION['INGRESO']['Nombre_Completo']=$usuario[0]['Nombre_Completo'];
								//verificamos en acceso si puede ingresar a esa empresa
								$_SESSION['INGRESO']['accesoe']='0';
								$_SESSION['INGRESO']['modulo'][0]='0';
								$permiso=getAccesoEmpresas();
							}
						}
					}
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
				}
				//echo $_SESSION['INGRESO']['Cambio'].' ddddddddddddddddd ';
				if ($_SESSION['INGRESO']['Cambio']==0)
				{
					//$_SESSION['INGRESO']['Cambio']=1;
					//echo $_SESSION['INGRESO']['Cambio'].' ddddddddddddddddd ';
				?>
					<script>
						/*swal({
							title: "Se requiere Clave Administrador",
							text: 'Introduzca su código:',
							type: 'input',
							inputType: "password",
							showCancelButton: true,
							closeOnConfirm: false,
							animation: "slide-from-top",
							inputPlaceholder: "Código",
						},
						function(inputValue){
							if (inputValue === false) return false;

							if (inputValue === "") {
								swal.showInputError("Debe escribir su código!");
								return false;
							}
						});*/
						async function getFormValues () {
							const {value: formValues} = await Swal.fire({
							  title: 'Nueva Contraseña',
							  html:
								'<input id="swal-input1" class="swal2-input" placeholder="Contraseña" type="password">' +
								'<input id="swal-input2" class="swal2-input" placeholder="Re-escribir contraseña" type="password">',
							  focusConfirm: false,
							  preConfirm: () => {
								if (document.getElementById('swal-input1').value === false) return false;

								if (document.getElementById('swal-input1').value === "") {
									//swal.showInputError("Debe escribir su nueva clave!");
									return 1;
								}
								else
								{
									if (document.getElementById('swal-input2').value === "") {
										//swal.showInputError("Debe re-escribir su nueva clave!");
										return 2;
									}
									else
									{
										if (document.getElementById('swal-input1').value != document.getElementById('swal-input2').value) {
											//swal.showInputError("No coincide su nueva clave!");
											return 3;
										}
										else
										{
											return 4;
										}
									}
								}
							  }
							})

							if (formValues) {
								//swal.showInputError("Debe escribir su nueva clave!");
								if(formValues==1)
								{
									Swal.fire({
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
									})
									//location.href="logout.php";
								}
								if(formValues==2)
								{
									Swal.fire({
									  type: 'error',
									  title: 'Oops...',
									  text: 'Debe re-escribir su nueva contraseña!'
									}).then((result) => {
									  if (result.value) {
										location.href="logout.php";
									  } 
									})
								}
								if(formValues==3)
								{
									Swal.fire({
									  type: 'error',
									  title: 'Oops...',
									  text: 'No coincide las contraeñas!'
									}).then((result) => {
									  if (result.value) {
										location.href="logout.php";
									  } 
									})
								}
								//llamamos ajax
								if(formValues==4)
								{
									$.post('ajax/cambiarcon.php', { clave: document.getElementById('swal-input1').value,
									id: <?php echo $_SESSION['INGRESO']['ID']; ?> }, 
										function(returnedData){
									 console.log(returnedData);
									 if(returnedData.success){
										 Swal.fire({
										  //position: 'top-end',
										  type: 'success',
										  title: 'Su contraseña fue cambiada con exito!',
										  showConfirmButton: true
										  //timer: 2500
										}).then((result) => {
										  if (result.value) {
											location.href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti="+
										"<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0";
										  } 
										});
										//swal("Correcto!", 'Autorizado por: ' + returnedData.name, "success");
										
										//location.href="ver_nominas.php?borrar=" + name;
									 }else{
										 Swal.fire({
										  type: 'error',
										  title: 'Oops...',
										  text: 'No se pudo cambiar contraeñas!'
										});
									 }
										}, 'json');
									
									//location.href="logout.php";
									/*return [
											  document.getElementById('swal-input1').value,
											  document.getElementById('swal-input2').value
											]*/
								}
								
							  //Swal.fire(JSON.stringify(formValues))
							}
						}
						getFormValues();
						//swal("Error!", 'Debe seleccionar una opcion', "warning");
					</script>
					<?php
					$_SESSION['INGRESO']['empresa']='842-003';
				}
				else
				{
					$_SESSION['INGRESO']['empresa']='842-003';
					//$_SESSION['INGRESO']['solouna']=0;
				    //echo $_SESSION['INGRESO']['solouna'].' fffff ';
					//verificamos que la url sea siempre rde.php$host= $_SERVER["HTTP_HOST"];
					$host= $_SERVER["HTTP_HOST"];
					$url= $_SERVER["REQUEST_URI"];
					$url = $host . $url;
					//echo $url;
					/*$cadena_de_texto = 'Esta es la frase donde haremos la búsqueda';
					
					$bus   = 'fact.php';
					$posicion_coincidencia = strpos($url, $bus);
					 $ban=0;
					if($_SESSION['INGRESO']['Tipo_Usuario']!='user')
					{
						//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
						if ($posicion_coincidencia === false) 
						{
							//se redirecciona
							?>	
								<script>
									location.href="fact.php?mod=contabilidad&acc=fact&acc1=Reporte Facturación";
								</script>
							<?php
						} 
						else 
						{
							
						}
					}
					$bus   = 'rde.php';
					$posicion_coincidencia = strpos($url, $bus);
					 $ban=0;
					if($_SESSION['INGRESO']['Tipo_Usuario']!='user')
					{
						//se puede hacer la comparacion con 'false' o 'true' y los comparadores '===' o '!=='
						if ($posicion_coincidencia === false) 
						{
							?>	
							<script>
								location.href="rde.php?mod=contabilidad&acc=rde&acc1=Reporte Doc. Electronico&ti="+
												"<?php echo $_SESSION['INGRESO']['ti']; ?>&Opcb=6&Opcen=0&b=0";
							</script>
											<?php
						} 
						else 
						{
							
						}
					}*/
				}
			}
		?>	
 <!--mensajes popup-->
<?php
	if(isset($_SESSION['INGRESO']['accesoe']) )
	{
		if($_SESSION['INGRESO']['CodigoU']=='')
		{
			?>
				<script>
				Swal.fire({
				  type: 'error',
				  title: 'Oops...',
				  text: 'No existe su usuario a esta empresa!'
				}).then((result) => {
				  if (result.value) {
					location.href="logout.php";
				  } 
				});
			</script>
			<?php
		}
	}
 
	if(isset($_SESSION['INGRESO']['accesoe']) )
	{
		if($_SESSION['INGRESO']['accesoe']=='0')
		{
?>
			<script>
				Swal.fire({
				  type: 'error',
				  title: 'Oops...',
				  text: 'No tiene acceso a esta empresa!'
				}).then((result) => {
				  if (result.value) {
					location.href="panel.php?mos2=e";
				  } 
				});
			</script>
<?php		
		}

	}
?>


  <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <!--<section class="content-header">
      <h1>
        Bienvenido
        <small>a DiskCover</small>
      </h1>
	  <?php
	  if (!isset($_SESSION['INGRESO']['empresa'])) 
	  {
	  ?>
		  <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Seleccione empresa</a></li>
		  </ol>
	  <?php
	  }
	  else
		  {
			  ?>
			  <ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $_SESSION['INGRESO']['noempr']; ?></a></li>
				<li><a href="panel.php?mos2=e">Seleccione empresa</a></li>
			  </ol>
			  
		<?php
		  }
		  ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> prueba</a></li>
        <li><a href="#">prueba</a></li>
        <li class="active">prueba</li>
      </ol>
    </section>-->

    <!-- Main content -->

      <!-- Default box -->
        <!--<div class="box-header with-border">
		 <?php
		  if (!isset($_SESSION['INGRESO']['empresa'])) 
		  {
		  ?>
			  <h3 class="box-title">Selecione su empresa</h3>
		<?php
		  }
		  else
		  {
			  ?>
			  <h3 class="box-title"><?php echo $_SESSION['INGRESO']['noempr']; ?></h3>
		<?php
		  }
		  ?>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>-->
			
			
	
	<script>
		//$(document).on('change', '#sempresa', function(event) {
			 //$('#servicioSelecionado').val($("#sempresa option:selected").text());
			// alert($("#sempresa option:selected").text());
		//});
		$(document).ready(function () {

			$('.navbar .dropdown-item.dropdown').on('click', function (e) {
				var $el = $(this).children('.dropdown-toggle');
				if ($el.length > 0 && $(e.target).hasClass('dropdown-toggle')) {
					var $parent = $el.offsetParent(".dropdown-menu");
					$(this).parent("li").toggleClass('open');
			
					if (!$parent.parent().hasClass('navbar-nav')) {
						if ($parent.hasClass('show')) {
							$parent.removeClass('show');
							$el.next().removeClass('show');
							$el.next().css({"top": -999, "left": -999});
						} else {
							$parent.parent().find('.show').removeClass('show');
							$parent.addClass('show');
							$el.next().addClass('show');
							$el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
						}
						e.preventDefault();
						e.stopPropagation();
					}
					return;
				}
			});

			$('.navbar .dropdown').on('hidden.bs.dropdown', function () {
				$(this).find('li.dropdown').removeClass('show open');
				$(this).find('ul.dropdown-menu').removeClass('show open');
			});

		});
		function cambiar(id){
			var select = document.getElementById(id), //El <select>
			value = select.value; //El valor seleccionado
			//partimos cadenas
			separador = "-"; // un espacio en blanco
			limite    = 2;
			arregloDeSubCadenas = value.split(separador, limite);
			text = select.options[select.selectedIndex].innerText; //El texto de la opción seleccionada
			//alert(value);
			//redireccionamos
			window.location="panel.php?mos="+value+"&mos1="+text+"&mos3="+arregloDeSubCadenas[1]+"";
		}
	
		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}
		
		function soloNumeros(e)
		{
			var key = window.Event ? e.which : e.keyCode
			return (key >= 48 && key <= 57)
		}
		function soloNumeros12(e)
		{
			$("#codigo1").hide();
			var key = window.Event ? e.which : e.keyCode
			//alert(key);
			if(key >= 49 && key <= 50)
			{
				 $(this).next().focus();
				 return (key >= 49 && key <= 50);
			}
			else
			{
				
			}
			
		}
		function soloNumerosDecimales(e)
		{
			var key = window.Event ? e.which : e.keyCode
			return (key <= 13 || (key >= 48 && key <= 57) || key==46)
		}
		function  cerrar(id)
		{
			if(id=='codigo1')
			{
				$( "#moneda" ).focus(function() {
					var bene = document.getElementById('cuenta').value;
					var cod = document.getElementById('codigo').value;
					if('Seleccionar'==bene || bene=='' || bene=='no existe registro' || bene=='undefined' || cod=='0' || cod=='')
					{
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: 'debe agregar cuenta!'
						});
						$("#cuenta").focus();
					}
					//
				});
				$("#"+id).hide();
			}
			else
			{
				$("#"+id).hide();
			}
		}
</script>	
<?php
//si es un sola empresa que redireccione directo
include('footer.php');
?>	
   