	<?php
/**
 * Autor: Diskcover System.
 * Mail:  diskcover@msn.com
 * web:   www.diskcoversystem.com
 * distribuidor: PrismaNet Profesional S.A.
 */
		if(isset($_GET['mod'])) 
		{
			//sesion para saber en que modulo esta 
			$_SESSION['INGRESO']['modulo']=$_GET['mod'];
			//verificamos accion 
			if(isset($_GET['acc'])) 
			{
				$_SESSION['INGRESO']['accion']=$_GET['acc'];
			}
			else
			{
				unset( $_SESSION['INGRESO']['accion']);
			}
			//verificacion titulo accion
			if(isset($_GET['acc1'])) 
			{
				$_SESSION['INGRESO']['accion1']=$_GET['acc1'];
			}
			else
			{
				unset( $_SESSION['INGRESO']['accion1']);
			}
			?>
				<div class="navbar-header">
						 <p style='color:#fff;position:absolute;margin: 0 5px 10px;'>
								<?php echo $_SESSION['INGRESO']['item']; ?>-
								<?php echo $_SESSION['INGRESO']['noempr']; ?> <i class="fa fa-fw fa-chevron-circle-right"></i>
								<?php echo $_SESSION['INGRESO']['modulo']; ?> 
								<?php if(isset($_SESSION['INGRESO']['accion1'])) 
								{ ?>
									<i class="fa fa-fw fa-chevron-circle-right"></i>
									<?php echo $_SESSION['INGRESO']['accion1']; ?>
							<?php 
								} 
							?>
								
							</p>
							<br/>
							<a href="panel.php?sa=s" class="logo" >
							  <!-- mini logo for sidebar mini 50x50 pixels -->
							  <?php 
								//si es jpg
								$src = __DIR__ . '/../../diskcover_img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.jpg'; 
								$exis=0;
								if (@getimagesize($src)) 
								{ 
									$exis=1;
									?>
									 <span class="logo-mini"><img src="../../diskcover_img/logotipos/<?php 
											if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
											{
												echo $_SESSION['INGRESO']['Logo_Tipo'];
											}
											else
											{
												echo "DEFAULT";
											}
											?>.jpg" class="user-image" alt="User Image"
									 width='100%' height='100%'></span>
									 <!-- logo for regular state and mobile devices -->
									  <span class="logo-lg"><img src="../../diskcover_img/logotipos/<?php 
										if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
										{
											echo $_SESSION['INGRESO']['Logo_Tipo'];
										}
										else
										{
											echo "DEFAULT";
										}?>.jpg" class="user-image" alt="User Image"
								 width='20%' height='50%'></span>
									<?php
								}
								//si es gif
								$src = __DIR__ . '/../../diskcover_img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.gif'; 
								if (@getimagesize($src)) 
								{ 
									$exis=1;
									?>
									 <span class="logo-mini"><img src="../../diskcover_img/logotipos/<?php 
											if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
											{
												echo $_SESSION['INGRESO']['Logo_Tipo'];
											}
											else
											{
												echo "DEFAULT";
											}
											?>.gif" class="user-image" alt="User Image"
									 width='100%' height='100%'></span>
									 <!-- logo for regular state and mobile devices -->
									  <span class="logo-lg"><img src="../../diskcover_img/logotipos/<?php 
										if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
										{
											echo $_SESSION['INGRESO']['Logo_Tipo'];
										}
										else
										{
											echo "DEFAULT";
										}?>.gif" class="user-image" alt="User Image"
								 width='20%' height='50%'></span>
									<?php
								}
								//si es png
								$src = __DIR__ . '/../../diskcover_img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.png'; 
								if (@getimagesize($src)) 
								{ 
									$exis=1;
									?>
									 <span class="logo-mini"><img src="../../diskcover_img/logotipos/<?php 
											if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
											{
												echo $_SESSION['INGRESO']['Logo_Tipo'];
											}
											else
											{
												echo "DEFAULT";
											}
											?>.png" class="user-image" alt="User Image"
									 width='100%' height='100%'></span>
									 <!-- logo for regular state and mobile devices -->
									  <span class="logo-lg"><img src="../../diskcover_img/logotipos/<?php 
										if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
										{
											echo $_SESSION['INGRESO']['Logo_Tipo'];
										}
										else
										{
											echo "DEFAULT";
										}?>.png" class="user-image" alt="User Image"
								 width='20%' height='50%'></span>
									<?php
								}
								//si es png
								$src = __DIR__ . '/../../diskcover_img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.png'; 
								if ($exis==0) 
								{ 
									?>
									 <span class="logo-mini"><img src="../../diskcover_img/logotipos/<?php 
											if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
											{
												echo $_SESSION['INGRESO']['Logo_Tipo'];
											}
											else
											{
												echo "DEFAULT";
											}
											?>.png" class="user-image" alt="User Image"
									 width='100%' height='100%'></span>
									 <!-- logo for regular state and mobile devices -->
									  <span class="logo-lg"><img src="../../diskcover_img/logotipos/<?php 
										if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
										{
											echo $_SESSION['INGRESO']['Logo_Tipo'];
										}
										else
										{
											echo "DEFAULT";
										}?>.png" class="user-image" alt="User Image"
								 width='20%' height='50%'></span>
									<?php
								}
							  ?>
							 
							</a>
							
						  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
							<i class="fa fa-bars"></i>
						  </button>
					</div>
				  <br/>
			<?php
			if($_GET['mod']=='empresa') 
			{
			?>
				 <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
						<ul class="nav navbar-nav mr-auto">
							<!--<li class="nav-item <!-- active "> -->
								<!--<a class="nav-link" href="#">Archivo <span class="sr-only">(current)</span></a>
							</li>-->
							<!-- archivo -->
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="archivo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Archivo</a>
								<ul class="dropdown-menu" aria-labelledby="archivo">
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="del_sistema" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Del sistema
										<i class="fa fa-fw fa-chevron-circle-right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="del_sistema">
											<li class="dropdown-item"><a href="empresa.php?mod=empresa&acc=cambioe&acc1=Modificar empresa&b=1">Modificar empresa</a></li>
											<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=campe&acc1=Cambio de periodo&b=1">Cambio de periodo</a></li>
										</ul>
									</li>
									<li class="dropdown-item"><a href="panel.php">Volver</a></li>
								</ul>
							</li>
							<li class="nav-item">  <!-- active -->
							<?php
								//verificamos el periodo
								if($_SESSION['INGRESO']['periodo']=='.')
								{
									$sty="color:#D8F781;font-size:90%;";
								}
								else
								{
									$sty="color:#F9A790;font-size:90%;";
								}
							?>
								<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Fecha Actual">
									<p style="<?php echo $sty; ?>">
										<?php
											//$miFecha= date("d-m-Y");
											$miFecha= gmmktime(12,0,0,date("m"),date("d"),date("Y"));
											//echo 'Antes de setlocale strftime devuelve: '.strftime("%A, %d de %B de %Y", $miFecha).'<br/>';

											//echo 'Antes de setlocale date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';

											setlocale(LC_TIME,"es_ES");

											//echo 'Después de setlocale es_ES date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';

											echo ' '.utf8_encode(strftime("%A, %d de %B de %Y", $miFecha)).'';
										?>
									</p>
								</a>
							</li>
							<li class="nav-item">  <!-- active -->
								<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Periodo del proceso contable">
									<p style="<?php echo $sty; ?>">
										<?php
											if($_SESSION['INGRESO']['periodo']=='.')
											{
												echo 'PERIODO ACTUAL';
											}
											else
											{
												echo $_SESSION['INGRESO']['periodo'];
											}
										?>
									</p>
								</a>
							</li>
							<li class="nav-item">  <!-- active -->
								<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Motor de base de datos">
									<p style="<?php echo $sty; ?>">
										<?php
											echo $_SESSION['INGRESO']['Tipo_Base'];
										?>
									</p>
								</a>
							</li>
							
						</ul>
					
				</div>
			<?php
			}
			?>
			<?php
			if($_GET['mod']=='contabilidad') 
			{
	?>
				 <!--
				 <a href="panel.php?sa=s" class="navbar"><br/>
						  <span class="logo-mini"><img src="../../diskcover_img/logotipos/DEFAULT.png" class="user-image" alt="User Image"
						 width='15%' height='30%'></span></a>
				 <a href="panel.php" class="logo">
				  <!-- mini logo for sidebar mini 50x50 pixels -->
				 <!-- <span class="logo-mini"><b>L</b>G</span>
				  <!-- logo for regular state and mobile devices -->
				  <!--<span class="logo-lg"><b>Disk</b>Cover</span>
				</a>-->
				 <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
						<ul class="nav navbar-nav mr-auto">
							<!--<li class="nav-item <!-- active "> -->
								<!--<a class="nav-link" href="#">Archivo <span class="sr-only">(current)</span></a>
							</li>-->
							<!-- archivo -->
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="archivo" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Archivo</a>
								<ul class="dropdown-menu" aria-labelledby="archivo">
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="del_sistema" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Del sistema
										<i class="fa fa-fw fa-chevron-circle-right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="del_sistema">
											<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=cambioc&acc1=Cambio de clave">Cambio de clave</a></li>
											<li class="dropdown-item"><a href="#">Ingresar nuevo usuario</a></li>
											<li class="dropdown-item"><a href="#">Modulo de auditoria</a></li>
											<li class="dropdown-item"><a href="#">Autorizacion del SRI</a></li>
											<li class="dropdown-item"><a href="#">Cierre del mes</a></li>
											<li class="dropdown-item"><a href="#">Cierre del ejercicio</a></li>
											<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=campe&acc1=Cambio de periodo&b=1">Cambio de periodo</a></li>
										</ul>
									</li>
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="de_operacion" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">De operación
										<i class="fa fa-fw fa-chevron-circle-right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="de_operacion">
											<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=incc&acc1=Ingreso catalogo de Cuentas (Crtl+f6)">
											Ingreso catalogo de Cuentas (Crtl+f6)</a></li>
											<li class="dropdown-item dropdown">
												<a class="dropdown-toggle" id="catalogosub" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ingreso catalogo de Subcuentas
												<i class="fa fa-fw fa-chevron-circle-right"></i></a>
												<ul class="dropdown-menu" aria-labelledby="catalogosub">
													<li class="dropdown-item"><a href="#">Ctas. Por Cobrar/Ctas por Pagar</a></li>
													<li class="dropdown-item"><a  href="#">Ctas. Ingreso/Egresos/Primas</a></li>
												</ul>
											</li>
											<li class="dropdown-item"><a href="#">Ingresar Clientes/Proveedores (Crtl+f8)</a></li>
											<li class="dropdown-item"><a href="#">Ingresar Comprobantes (Crtl+f5)</a></li>
											<li class="dropdown-item"><a href="#">Ingresos/Egreso de caja chica (Crtl+f7)</a></li>
											<li class="dropdown-item"><a href="#">Modificación de primas</a></li>
											<li class="dropdown-item"><a href="#">Conciliación bancaria de debitos/creditos</a></li>
										</ul>
									</li>
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="archivo_e" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Archivos de excel
										<i class="fa fa-fw fa-chevron-circle-right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="archivo_e">
											<li class="dropdown-item"><a href="#">Importar Compras de excel (Crtl+i)</a></li>
											<li class="dropdown-item"><a href="#">Importar Ventas de excel (Crtl+j)</a></li>
											<li class="dropdown-item"><a href="#">Importar Clientes/Proveedores</a></li>
											<li class="dropdown-item"><a href="#">Importar Submodulos</a></li>
											<li class="dropdown-item"><a href="#">Importar Contabilidad externa</a></li>
											
										</ul>
									</li>
									<li class="dropdown-item"><a href="#">Pagos programados bancos</a></li>
									<li class="dropdown-item"><a href="panel.php">Volver</a></li>
								</ul>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="reporte" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reportes</a>
								<ul class="dropdown-menu" aria-labelledby="reporte">
									<li class="dropdown-item"><a href="#" >Catalogo de cuentas</a></li>
									<li class="dropdown-item"><a href="#" >Catalogo de SubCtas bloque</a></li>
									<li class="dropdown-item"><a href="#" >Cuotas Pendientes de prestamos</a></li>
									<li class="dropdown-item"><a href="#" >Catalogo de rol de pagos</a></li>
									<li class="dropdown-item"><a href="#" >Catalogo de retención</a></li>
									<li class="dropdown-item"><a href="#" >Diario general (Crtl+d)</a></li>
									<li class="dropdown-item"><a href="#" >Libro banco (Crtl+f)</a></li>
									<li class="dropdown-item"><a href="#" >Mayores auxiliares (Crtl+m)</a></li>
									<li class="dropdown-item"><a href="#" >Mayores auxiliares por concepto</a></li>
									<li class="dropdown-item"><a href="#" >Mayores de SubCtas (Crtl+s)</a></li>
									<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=compro&acc1=Comprobantes Procesados&b=1" >
									Comprobantes procesados (Crtl+l)</a></li>
									<li class="dropdown-item"><a href="#" >Comprobantes de retención (Crtl+r)</a></li>
									<li class="dropdown-item"><a href="#" >Cheques procesados</a></li>
									<li class="dropdown-item"><a href="#" >Conciliación bancaria</a></li>
									<li class="dropdown-item"><a href="#" >Imprimir lista de comprobantes</a></li>
									<li class="dropdown-item"><a href="#" >Saldo de Caja/Bancos/Especiales</a></li>
									<li class="dropdown-item"><a href="#" >Saldo de facturas en SubModulos</a></li>
									<li class="dropdown-item"><a href="#" >Flujo de caja chica</a></li>
									<li class="dropdown-item"><a href="#" >Reportes de SubCtas de costos</a></li>
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="rsubcostos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reportes de SubCtas de costos
										<i class="fa fa-fw fa-chevron-circle-right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="rsubcostos">
											<li class="dropdown-item"><a  href="#">Ingresar facturas de costos</a></li>
											<li class="dropdown-item"><a  href="#">reporte de costos</a></li>
										</ul>
									</li>
									<li class="dropdown-item"><a href="#" >Informa a UAF (Cooperativas)</a></li>
									<li class="dropdown-item"><a href="#" >Buscar datos</a></li>
									
								</ul>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="anexos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Anexos transaccionales</a>
								<ul class="dropdown-menu" aria-labelledby="anexos">
									<li class="dropdown-item"><a href="#" >Generar anexos transaccionales (f12)</a></li>
									<li class="dropdown-item"><a href="#" >Codigo de retención (AIR) f11</a></li>
									<li class="dropdown-item"><a href="#" >Resumen de retensiones (f9)</a></li>
									<li class="dropdown-item"><a href="#" >Correción de formularios</a></li>
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="coform" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Correción de formularios
										<i class="fa fa-fw fa-chevron-circle-right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="coform">
											<li class="dropdown-item"><a  href="#">Compras (MAYUS+f1)</a></li>
											<li class="dropdown-item"><a  href="#">Ventas (MAYUS+f2)</a></li>
											<li class="dropdown-item"><a  href="#">Exportaciones (MAYUS+f3)</a></li>
											<li class="dropdown-item"><a  href="#">Importaciones (MAYUS+f4)</a></li>
										</ul>
									</li>
									<li class="dropdown-item"><a href="#" >Relación por dependiencia (f7)</a></li>
									<li class="dropdown-item"><a href="#" >Anulados (f6)</a></li>
								</ul>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="efinan" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Estados financieros</a>
								<ul class="dropdown-menu" aria-labelledby="efinan">
									<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=macom&acc1=Mayorización&b=1" >Mayorizar Comprobantes procesados (Crtl+t)</a></li>
									<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&b=1" >
									Balance de Comprobacion/Situación/General (Crtl+g)</a></li>
									<li class="dropdown-item"><a href="#" >Resumen analitico de Utilidad/Perdida (Crtl+u)</a></li>
									<li class="dropdown-item"><a href="#" >Balance de SubModulos</a></li>
									
									
								</ul>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="herra" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Herramientas</a>
								<ul class="dropdown-menu" aria-labelledby="herra">
									<li class="dropdown-item"><a href="#" >Calculadora (Crtl+f1)</a></li>
									<li class="dropdown-item"><a href="#" >Programador f11</a></li>
									<li class="dropdown-item"><a href="#" >Memorando (Crtl+e)</a></li>
									<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=hco&acc1=Conexión Oracle&b=1" >Conexión Oracle</a></li>
									
									<li class="dropdown-item"><a target="_blank" href="http://192.168.27.2/" >diskcoversystem</a></li>
									<li class="dropdown-item"><a href="#" >Enviar correo electrónico</a></li>
								</ul>
							</li>
							<li class="nav-item">  <!-- active -->
							<?php
								//verificamos el periodo
								if($_SESSION['INGRESO']['periodo']=='.')
								{
									$sty="color:#D8F781;font-size:90%;";
								}
								else
								{
									$sty="color:#F9A790;font-size:90%;";
								}
							?>
								<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Fecha Actual">
									<p style="<?php echo $sty; ?>">
										<?php
											//$miFecha= date("d-m-Y");
											$miFecha= gmmktime(12,0,0,date("m"),date("d"),date("Y"));
											//echo 'Antes de setlocale strftime devuelve: '.strftime("%A, %d de %B de %Y", $miFecha).'<br/>';

											//echo 'Antes de setlocale date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';

											setlocale(LC_TIME,"es_ES");

											//echo 'Después de setlocale es_ES date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';

											echo ' '.utf8_encode(strftime("%A, %d de %B de %Y", $miFecha)).'';
										?>
									</p>
								</a>
							</li>
							<li class="nav-item">  <!-- active -->
								<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Periodo del proceso contable">
									<p style="<?php echo $sty; ?>">
										<?php
											if($_SESSION['INGRESO']['periodo']=='.')
											{
												echo 'PERIODO ACTUAL';
											}
											else
											{
												echo $_SESSION['INGRESO']['periodo'];
											}
										?>
									</p>
								</a>
							</li>
							<li class="nav-item">  <!-- active -->
								<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Motor de base de datos">
									<p style="<?php echo $sty; ?>">
										<?php
											echo $_SESSION['INGRESO']['Tipo_Base'];
										?>
									</p>
								</a>
							</li>
							
						</ul>
					
				</div>
	<script >
	//$(document).bind('keydown', 'ctrl+a', function(){
	  //alert("Has pulsado ctrl+a");
	 // var win = window.open("https://www.google.com", '_blank');
	 // win.focus();
	//});
	//para acceso por teclado
	$(document).bind('keydown', 'ctrl+f6', function(e){
	    //alert("Has pulsado ctrl+f6");
	    var code = e.keyCode || e.which;
	    //var code1=new Array(2);
	    if(code!=null)
	    {
			if(readCookie('codi1')==null)
			{
				document.cookie = "codi1="+code+"; ";
				document.cookie = "codi2=;";
			}
			else
			{
				document.cookie = "codi2="+code+"; ";
			}
	    }
		if(readCookie('codi1')=='97' && readCookie('codi2')=='117')
		{
			alert(readCookie('codi1')+' '+readCookie('codi2'));
		}
		//ctr+g
		//alert(readCookie('codi1')+' '+readCookie('codi2'));
		if(readCookie('codi1')=='97' && readCookie('codi2')=='71')
		{
			alert(readCookie('codi1')+' '+readCookie('codi2'));
			window.location="<?php echo $_SESSION['INGRESO']['url']; ?>/diskcover_php/vista/contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General";
			//window.open("<?php echo $_SESSION['INGRESO']['url']; ?>/diskcover_php/vista/contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General");
		}
		//local
		if(readCookie('codi1')=='17' && readCookie('codi2')=='71')
		{
			//alert(readCookie('codi1')+' '+readCookie('codi2'));
			window.location="<?php echo $_SESSION['INGRESO']['url']; ?>/diskcover_php/vista/contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General";
			
		}
	    
	});
	</script >
<?php
			}
		}
	?>