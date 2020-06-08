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
						
							<?php 
								$src = __DIR__ . '/../../img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.jpg'; 
								$exis=0;
								if (@getimagesize($src)) 
								{ 
									$exis=1;
									//echo $src.' '.@getimagesize($src);
									if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
									{
										$imag=$_SESSION['INGRESO']['Logo_Tipo'].'.jpg';
									}
									else
									{
										$imag='DEFAULT.jpg';
									}
								}
								//si es gif
								$src = __DIR__ . '/../../img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.gif'; 
								if (@getimagesize($src)) 
								{ 
									//echo $src.' '.@getimagesize($src);
									$exis=1;
									if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
									{
										$imag=$_SESSION['INGRESO']['Logo_Tipo'].'.gif';
									}
									else
									{
										$imag='DEFAULT.gif';
									}
								}
								//si es png
								$src = __DIR__ . '/../../img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.png'; 
								if (@getimagesize($src)) 
								{ 
									//echo $src.' '.@getimagesize($src);
									$exis=1;
									if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
									{
										$imag=$_SESSION['INGRESO']['Logo_Tipo'].'.png';
									}
									else
									{
										$imag='DEFAULT.png';
									}
								}
								//si es png
								$src = __DIR__ . '/../../img/logotipos/'.$_SESSION['INGRESO']['Logo_Tipo'].'.png'; 
								if ($exis==0) 
								{ 
									//echo $src.' '.@getimagesize($src);
									if(isset($_SESSION['INGRESO']['Logo_Tipo'])) 
									{
										//$imag=$_SESSION['INGRESO']['Logo_Tipo'].'.png';
										$imag='DEFAULT.jpg';
									}
									else
									{
										$imag='DEFAULT.jpg';
									}
								}
								//echo $imag;
							?>
								<div style=' float: right;color:#fff;margin: 0 5px 0;'>
									<!--<p style='color:#fff;position:absolute;margin: 0 5px 10px;'>-->
										<a href="panel.php?sa=s"  data-toggle="tooltip"  title="Inicio" data-placement="bottom" >
											<span class="logo-mini"><img src="../../img/logotipos/<?php echo $imag; ?>" 
											 class="img-circle" alt="User Image" width='3%' height='3%'></span>
										</a>
										<?php echo $_SESSION['INGRESO']['item']; ?>-
										<?php 
											echo $_SESSION['INGRESO']['noempr']; 
										?> <i class="fa fa-fw fa-chevron-circle-right"></i>
										<?php echo $_SESSION['INGRESO']['modulo']; ?> 
										<?php if(isset($_SESSION['INGRESO']['accion1'])) 
										{ ?>
											<i class="fa fa-fw fa-chevron-circle-right"></i>
											<?php echo $_SESSION['INGRESO']['accion1'];
												//if(strlen($_SESSION['INGRESO']['noempr'])<20)
												//{
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
													echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
											//	}	
											?>
									<?php 
										}
										else
										{
											//if(strlen($_SESSION['INGRESO']['noempr'])<20)
											//{
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
												echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'; 
											//}
										}										
									?>
									
										<div style=' float: right;width:15%'>
										<!--class="hidden-xs"-->
											  <span  style=' float: left'>
												
											  <?php	
													//var_dump($_SESSION['INGRESO']);
													echo $_SESSION['INGRESO']['Nombre'];
												?></span>
											<a href="logout.php"  data-toggle="tooltip"  data-placement="bottom" title="Cerrar sesión" style=' float: right'>
												<img src="../../img/png/salirs.png"  width='70%' height='50%'>
											</a>
											<a href="panel.php?mos2=e"  data-toggle="tooltip"  data-placement="bottom" title="Salir de empresa" style=' float: right'>
												<img src="../../img/png/salire.png"  width='70%' height='50%'>
											</a>
											
										</div>
									<!--</p>-->
								</div>
										
							
							<br/>
							<!--<a href="panel.php?sa=s" class="logo" >
							   mini logo for sidebar mini 50x50 pixels 
									 <span class="logo-mini"><img src="../../img/logotipos/<?php echo $imag; ?>" 
									 class="user-image" alt="User Image" width='100%' height='100%'></span>
									 <!-- logo for regular state and mobile devices 
									  <span class="logo-lg"><img src="../../img/logotipos/<?php echo $imag; ?>" 
									  class="user-image" alt="User Image" width='20%' height='50%'></span>
							 
							</a>-->
							
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
								<a class="nav-link dropdown-toggle" id="archivo" data-toggle="dropdown" aria-haspopup="true" 
								aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Archivo</a>
								<ul class="dropdown-menu" aria-labelledby="archivo">
									<li class="dropdown-item dropdown" style='width:100%;height=100%'>
										<a class="dropdown-toggle" id="del_sistema" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style='width:100%;'>
										Del sistema 
										<div style=' float: right;'><i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></div></a>
										<ul class="dropdown-menu" aria-labelledby="del_sistema">
											<li class="dropdown-item">
												<a href="#">
													<div style=' float: left;'>Account Settings</div> 
													<div style=' float: right;'><span class="glyphicon glyphicon-cog pull-right"></span></div>
												</a></li>
											<li class="dropdown-item"><a href="empresa.php?mod=empresa&acc=cambioe&acc1=Modificar empresa&b=1">Modificar empresa</a></li>
											<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=campe&acc1=Cambio de periodo&b=1">Cambio de periodo</a></li>
										</ul>
									</li>
									<li class="dropdown-item"><a href="panel.php?sa=s">
										Volver
									</a></li>
								</ul>
							</li>
							
							<!--<li class="nav-item">  
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
							<li class="nav-item">  
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
							<li class="nav-item">  
								<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Motor de base de datos">
									<p style="<?php echo $sty; ?>">
										<?php
											echo $_SESSION['INGRESO']['Tipo_Base'];
										?>
									</p>
								</a>
							</li>-->
							
						</ul>
					
				</div>
			<?php
			}
			?>
			<?php
			if($_GET['mod']=='contabilidad' and isset($_GET['acc'])!='fact') 
			{
	?>
				 <!--
				 <a href="panel.php?sa=s" class="navbar"><br/>
						  <span class="logo-mini"><img src="../../img/logotipos/DEFAULT.png" class="user-image" alt="User Image"
						 width='15%' height='30%'></span></a>
				 <a href="panel.php" class="logo">
				  <!-- mini logo for sidebar mini 50x50 pixels -->
				 <!-- <span class="logo-mini"><b>L</b>G</span>
				  <!-- logo for regular state and mobile devices -->
				  <!--<span class="logo-lg"><b>Disk</b>Cover</span>
				</a>-->
				 <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
						<ul class="nav navbar-nav mr-auto" style='padding-inline-start: 20px;'>
							<!--<li class="nav-item <!-- active "> -->
								<!--<a class="nav-link" href="#">Archivo <span class="sr-only">(current)</span></a>
							</li>-->
							<!-- archivo -->
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="archivo" data-toggle="dropdown" aria-haspopup="true" 
								aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Archivo</a>
								<ul class="dropdown-menu" aria-labelledby="archivo">
									<!--<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="del_sistema" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Del sistema
										<i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="del_sistema">
											<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=cambioc&acc1=Cambio de clave">Cambio de clave</a></li>
											<li class="dropdown-item"><a href="#">Ingresar nuevo usuario</a></li>
											<li class="dropdown-item"><a href="#">Modulo de auditoria</a></li>
											<li class="dropdown-item"><a href="#">Autorizacion del SRI</a></li>
											<li class="dropdown-item"><a href="#">Cierre del mes</a></li>
											<li class="dropdown-item"><a href="#">Cierre del ejercicio</a></li>
											<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=campe&acc1=Cambio de periodo&b=1">Cambio de periodo</a></li>
										</ul>
									</li>-->
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="de_operacion" data-toggle="dropdown" aria-haspopup="true" 
										aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>De operación
										<i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="de_operacion">
											<!--<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=incc&acc1=Ingreso catalogo de Cuentas ">
											Ingreso catalogo de Cuentas <i  style=' float: right;' align="right">(CRTL+F6)</i></a></li>
											<li class="dropdown-item dropdown">
												<a class="dropdown-toggle" id="catalogosub" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ingreso catalogo de Subcuentas
												<i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
												<ul class="dropdown-menu" aria-labelledby="catalogosub">
													<li class="dropdown-item"><a href="#">Ctas. Por Cobrar/Ctas por Pagar</a></li>
													<li class="dropdown-item"><a  href="#">Ctas. Ingreso/Egresos/Primas</a></li>
												</ul>
											</li>-->
											<!--<li class="dropdown-item"><a href="#">Ingresar Clientes/Proveedores 
											<i  style=' float: right;' align="right">(CRTL+F8)</i></a></li>-->
											<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=incom&acc1=Ingresar Comprobantes&b=1&po=subcu">
											Ingresar Comprobantes <i  style=' float: right;' align="right">(CRTL+F5)</i></a></li>
											<!--<li class="dropdown-item"><a href="#">Ingresos/Egreso de caja chica 
											<i  style=' float: right;' align="right">(CRTL+F7)</i></a></li>
											<li class="dropdown-item"><a href="#">Modificación de primas</a></li>
											<li class="dropdown-item"><a href="#">Conciliación bancaria de debitos/creditos</a></li>-->
										</ul>
									</li>
									<!--<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="archivo_e" data-toggle="dropdown" aria-haspopup="true" 
										aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Archivos de excel
										<i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="archivo_e">
											<li class="dropdown-item"><a href="#">Importar Compras de excel (CRTL+I)</a></li>
											<li class="dropdown-item"><a href="#">Importar Ventas de excel (CRTL+J)</a></li>
											<li class="dropdown-item"><a href="#">Importar Clientes/Proveedores</a></li>
											<li class="dropdown-item"><a href="#">Importar Submodulos</a></li>
											<li class="dropdown-item"><a href="#">Importar Contabilidad externa</a></li>
											
										</ul>
									</li>-->
									<!--<li class="dropdown-item"><a href="#">Pagos programados bancos
										<i style=' float: right;' align="right">&nbsp;</i> </a></li>-->
									<li class="dropdown-item"><a href="panel.php">Volver</a></li>
								</ul>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="reporte" data-toggle="dropdown" aria-haspopup="true" 
								aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Reportes</a>
								<ul class="dropdown-menu" aria-labelledby="reporte">
									<!--<li class="dropdown-item"><a href="#" >Catalogo de cuentas</a></li>
									<li class="dropdown-item"><a href="#" >Catalogo de SubCtas bloque</a></li>
									<li class="dropdown-item"><a href="#" >Cuotas Pendientes de prestamos</a></li>
									<li class="dropdown-item"><a href="#" >Catalogo de rol de pagos</a></li>
									<li class="dropdown-item"><a href="#" >Catalogo de retención</a></li>
									<li class="dropdown-item"><a href="#" >Diario general 
									<i  style=' float: right;' align="right">(CRTL+D)</i></a></li>
									<li class="dropdown-item"><a href="#" >Libro banco 
									<i  style=' float: right;' align="right">(CRTL+F)</i></a></li>
									<li class="dropdown-item"><a href="#" >Mayores auxiliares 
									<i  style=' float: right;' align="right">(CRTL+M)</i></a></li>
									<li class="dropdown-item"><a href="#" >Mayores auxiliares por concepto</a></li>
									<li class="dropdown-item"><a href="#" >Mayores de SubCtas 
									<i  style=' float: right;' align="right">(CRTL+S)</i></a></li>-->
									<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=compro&acc1=Comprobantes Procesados&b=1" >
									Comprobantes procesados <i  style=' float: right;' align="right">(CRTL+L)</i></a></li>
									<!--<li class="dropdown-item"><a href="#" >Comprobantes de retención (CRTL+R)</a></li>
									<li class="dropdown-item"><a href="#" >Cheques procesados</a></li>
									<li class="dropdown-item"><a href="#" >Conciliación bancaria</a></li>
									<li class="dropdown-item"><a href="#" >Imprimir lista de comprobantes</a></li>
									<li class="dropdown-item"><a href="#" >Saldo de Caja/Bancos/Especiales</a></li>
									<li class="dropdown-item"><a href="#" >Saldo de facturas en SubModulos</a></li>
									<li class="dropdown-item"><a href="#" >Flujo de caja chica</a></li>
									<li class="dropdown-item"><a href="#" >Reportes de SubCtas de costos</a></li>
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="rsubcostos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Reportes de SubCtas de costos
										<i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="rsubcostos">
											<li class="dropdown-item"><a  href="#">Ingresar facturas de costos</a></li>
											<li class="dropdown-item"><a  href="#">reporte de costos</a></li>
										</ul>
									</li>
									<li class="dropdown-item"><a href="#" >Informa a UAF (Cooperativas)</a></li>
									<li class="dropdown-item"><a href="#" >Buscar datos</a></li>-->
									
								</ul>
							</li>
							<!--<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="anexos" data-toggle="dropdown" aria-haspopup="true" 
								aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Anexos transaccionales</a>
								<ul class="dropdown-menu" aria-labelledby="anexos">
									<li class="dropdown-item"><a href="#" >Generar anexos transaccionales (F12)</a></li>
									<li class="dropdown-item"><a href="#" >Codigo de retención (AIR) 
									<i  style=' float: right;' align="right">(F11)</i></a></li>
									<li class="dropdown-item"><a href="#" >Resumen de retensiones 
									<i  style=' float: right;' align="right">(F9)</i></a></li>
									<li class="dropdown-item"><a href="#" >Correción de formularios</a></li>
									<li class="dropdown-item dropdown">
										<a class="dropdown-toggle" id="coform" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Correción de formularios
										<i class="fa fa-fw fa-chevron-circle-right" style=' float: right;' align="right"></i></a>
										<ul class="dropdown-menu" aria-labelledby="coform">
											<li class="dropdown-item"><a  href="#">Compras 
											<i  style=' float: right;' align="right">(MAYUS+F1)</i></a></li>
											<li class="dropdown-item"><a  href="#">Ventas 
											<i  style=' float: right;' align="right">(MAYUS+F2)</i></a></li>
											<li class="dropdown-item"><a  href="#">Exportaciones (MAYUS+F3)</a></li>
											<li class="dropdown-item"><a  href="#">Importaciones (MAYUS+F4)</a></li>
										</ul>
									</li>
									<li class="dropdown-item"><a href="#" >Relación por dependiencia 
									<i  style=' float: right;' align="right">(F7)</i></a></li>
									<li class="dropdown-item"><a href="#" >Anulados 
									<i  style=' float: right;' align="right">(F6)</i></a></li>
								</ul>
							</li>-->
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="efinan" data-toggle="dropdown" aria-haspopup="true" 
								aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Estados financieros</a>
								<ul class="dropdown-menu" aria-labelledby="efinan">
									<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=macom&acc1=Mayorización&b=1" >Mayorizar Comprobantes procesados 
									<i  style=' float: right;' align="right">(CRTL+T)</i></a></li>
									<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General&b=1" >
									Balance de Comprobacion/Situación/General (CRTL+G)</a></li>
									<!--<li class="dropdown-item"><a href="#" >Resumen analitico de Utilidad/Perdida 
									<i  style=' float: right;' align="right">(CRTL+U)</i></a></li>
									<li class="dropdown-item"><a href="#" >Balance de SubModulos</a></li>-->
									
									
								</ul>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" id="herra" data-toggle="dropdown" aria-haspopup="true" 
								aria-expanded="false" style='padding-top: 5px;padding-bottom: 5px;'>Herramientas</a>
								<ul class="dropdown-menu" aria-labelledby="herra">
									<!--<li class="dropdown-item"><a href="#" >Calculadora 
									<i  style=' float: right;' align="right">(CRTL+F1)</i></a></li>
									<li class="dropdown-item"><a href="#" >Programador 
									<i  style=' float: right;' align="right">(F11)</i></a></li>
									<li class="dropdown-item"><a href="#" >Memorando 
									<i  style=' float: right;' align="right">(CRTL+E)</i></a></li>
									<li class="dropdown-item"><a href="contabilidad.php?mod=contabilidad&acc=hco&acc1=Conexión Oracle&b=1" >Conexión Oracle</a></li>
									-->
									<li class="dropdown-item"><a target="_blank" href="https://www.diskcoversystem.com/" >diskcoversystem</a></li>
									<!--<li class="dropdown-item"><a href="#" >Enviar correo electrónico</a></li>-->
								</ul>
							</li>
							
							<!--<li class="nav-item" > 
								<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" title="Motor de base de datos">
									<p >
										  <span class="hidden-xs"><?php	
										//var_dump($_SESSION['INGRESO']);
										//echo $_SESSION['INGRESO']['Nombre'];
									?></span>
									</p>
								</a>
									
							</li>-->
							
						</ul>
				</div>
				<div style=' float: right;color:#fff;margin: 0 5px 0;'>
					<a class="nav-link" href="#" data-toggle="tooltip" data-placement="bottom" 
						title="Mostrar u ocultar sub-menu" style='padding-top: 5px;padding-bottom: 5px;color: #fff;'>
						<div class="mos1" style='display:none;' id='mostrar'>
							<!--Mostrar-->
							<img src="../../img/png/mostrar.png" 
							 class="user-image" alt="User Image" width='90%' height='90%'>
						</div>
						<div class="mos2"  id='ocultar'>
							<!--Ocultar-->
							<img src="../../img/png/contraer.png" 
							 class="user-image" alt="User Image" width='90%' height='90%'>
						</div>
					</a>
				</div>
				<script>
					$(document).ready(function(){
						$("#mostrar").click(function(){
							$('#submenu').show(500,function() {
							});
							$('#submenu1').show();
							$('.mos2').show();
							$('.mos1').hide();
						});
						$("#ocultar").click(function(){
							$('#submenu').hide(500,function() {
							});
							$('#submenu1').hide();
							$('.mos1').show();
							$('.mos2').hide();
						});
					});
				</script>
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
			//window.location="<?php echo $_SESSION['INGRESO']['url']; ?>/php/vista/contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General";
			//window.open("<?php echo $_SESSION['INGRESO']['url']; ?>/php/vista/contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General");
		}
		//local
		if(readCookie('codi1')=='17' && readCookie('codi2')=='71')
		{
			//alert(readCookie('codi1')+' '+readCookie('codi2'));
			//window.location="<?php echo $_SESSION['INGRESO']['url']; ?>/php/vista/contabilidad.php?mod=contabilidad&acc=bacsg&acc1=Balance de Comprobacion/Situación/General";
			
		}
	    
	});
	</script >
<?php
			}
		}
	?>