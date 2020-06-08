
			</div>
		</div>
		</section>
	</div>
	<!--<div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Folders</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li class="active"><a href="#"><i class="fa fa-inbox"></i> Inbox
                  <span class="label label-primary pull-right">12</span></a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> Sent</a></li>
                <li><a href="#"><i class="fa fa-file-text-o"></i> Drafts</a></li>
                <li><a href="#"><i class="fa fa-filter"></i> Junk <span class="label label-warning pull-right">65</span></a>
                </li>
                <li><a href="#"><i class="fa fa-trash-o"></i> Trash</a></li>
              </ul>
            </div>
             /.box-body 
     </div>-->
	<script>
		var esVisible = $(".loader1").is(":visible");
		$(".cargando").removeClass('cargando');
	</script>
	  <footer class="main-footer" style='margin-left: 0px !important;background: #ccc;word-wrap: break-word;bottom: 0px; 
	  position: fixed; display: block; position: fixed; display: block;width:100%;'>
		<div id="copyright" style='font-size: 10px;background: #ccc;'>
			<?php
				//verificamos el periodo
				if($_SESSION['INGRESO']['periodo']=='.')
				{
					//$sty="color:#D8F781;font-size:90%;";
					$sty="color:#1E03FF;font-size:90%;font-weight: bold;";
				}
				else
				{
					//$sty="color:#F9A790;font-size:90%;font-weight: bold;";
					$sty="color:#FA2929;font-size:90%;font-weight: bold;";
				}
			?>
			<div style=' float: left;margin: 0 5px 0;background: #ccc;'>
				<div style=' float: left;margin: 0 5px 0;background: #ccc;'>
					<a  href="#" data-toggle="tooltip"  title="Fecha Actual" style="<?php echo $sty; ?>">
						<img src="../../img/png/calendario.png" alt="Poland" style="margin-top:-4px"
						width='15%' height='15%'>
							<?php
								//$miFecha= date("d-m-Y");
								$miFecha= gmmktime(12,0,0,date("m"),date("d"),date("Y"));
								//echo 'Antes de setlocale strftime devuelve: '.strftime("%A, %d de %B de %Y", $miFecha).'<br/>';

								//echo 'Antes de setlocale date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';

								setlocale(LC_TIME,"es_ES");

								//echo 'Después de setlocale es_ES date devuelve: '.date("l, d-m-Y (H:i:s)", $miFecha).'<br/>';

								echo ' '.utf8_encode(strftime("%A, %d de %B de %Y", $miFecha)).'';
							?>
					</a>
				</div>
				<div style=' float: left;margin: 0 5px 0;background: #ccc;'>
					<a  href="#" data-toggle="tooltip"  title="Periodo del proceso contable" style="<?php echo $sty; ?>">
					<img src="../../img/png/periodo.png" alt="Poland" style="margin-top:-4px"
						width='15%' height='15%'>
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
					</a>
				</div>
				<div style=' float: left;margin: 0 5px 0;background: #ccc;'>
					<a  href="#" data-toggle="tooltip"  title="Motor de base de datos" style="<?php echo $sty; ?>">
					<img src="../../img/png/based.png" alt="Poland" style="margin-top:-4px"
						width='15%' height='15%'>
					<?php
						echo $_SESSION['INGRESO']['Tipo_Base'];
					?>
					</a>
				</div>
				<div style=' float: right;margin: 0 5px 0;background: #ccc;'>
					contactos: <b>diskcover@msn.com</b> / <b>diskcoversystem@msn.com</b> / <b>diskcover_contabilidad@outlook.com</b>
					
					telefonos: <b>0989105300</b> / <b>0999654196</b>
					Todos los derechos reservados por DiskCover System &copy;   <img src="../../img/png/poland.png" alt="Poland" style="margin-top:-4px">
				</div>
				<br>
			</div>
		</div>
	  </footer>
	</div>	
        
<!-- ./wrapper -->


</body>
</html>
