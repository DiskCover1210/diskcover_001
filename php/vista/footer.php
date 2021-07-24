
<footer class="main-footer">
    <div class="container-fluid">
      <div id="copyright" style='font-size: 11px;'>
			<?php
				//verificamos el periodo
			if(isset($_SESSION['INGRESO']['periodo']))
			{
				$_SESSION['INGRESO']['periodo'] = $_SESSION['INGRESO']['periodo'];
			}
			else
			{
				$_SESSION['INGRESO']['periodo'] = '.';
			}
				if($_SESSION['INGRESO']['periodo']=='.')
				{
					//$sty="color:#D8F781;font-size:90%;";
					$sty="color:#1E03FF;font-size:70%;font-weight: bold;";
				}
				else
				{
					//$sty="color:#F9A790;font-size:90%;font-weight: bold;";
					$sty="color:#FA2929;font-size:70%;font-weight: bold;";
				}
			?>
			<div style=' float: left;margin: 0 5px 0;'>
				<div style=' float: left;margin: 0 5px 0;'>
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
				<div style=' float: left;margin: 0 5px 0;'>
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
				<div style=' float: left;margin: 0 5px 0;'>
					<a  href="#" data-toggle="tooltip"  title="Motor de base de datos" style="<?php echo $sty; ?>">
					<img src="../../img/png/based.png" alt="Poland" style="margin-top:-4px"
						width='15%' height='15%'>
					<?php
					 if(isset($_SESSION['INGRESO']['Tipo_Base'])){
						echo $_SESSION['INGRESO']['Tipo_Base'];}else{ echo 'No Asignado';}
					?>
					</a>
				</div>
				<div style=' float: right;margin: 0 5px 0;'>
					contactos: <b>diskcover@msn.com</b> / <b>diskcoversystem@msn.com</b> / <b>diskcover_contabilidad@outlook.com</b>
					
					telefonos: <b>0989105300</b> / <b>0999654196</b>
					Todos los derechos reservados por DiskCover System &copy; 
				</div>
				<br>
			</div>
		</div>
    </div>
    <!-- /.container -->
  </footer>
</div>
   <div class="modal fade" id="myModal_info" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-body">
        	<table class="table">
        		<tr>
        			<td style="vertical-align:middle;"> <img src="../../<?php echo LOGO_DISKCOVER;?>" style="height:70px ;width:150px; margin: 10px"></td>
        			<td>
        				<h4 class="text-center"><b><?php echo NOMBRE_SISTEMA;?></b></h4>
        				<b>DIRECCION:</b> <?php echo DIRECCION_SISTEMA;?> <br>							 
        				<b>EMAIL:</b>
        				<?php
							$email1 = explode(";", EMAIL_SISTEMA);
							for($i=0;$i<count($email1);$i++)
							{
								echo $email1[$i].'<br>';
							}
							
						?>
        				<b>TELEFONO:</b>
        				<?php echo TELEFONO_SISTEMA; ?>
        			</td>
        		</tr>        		
        	</table> 	
        </div>
      </div>
    </div>
  </div>
   <div class="modal fade" id="myModal_espera" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body text-center">
        	<img src="../../img/gif/loader4.1.gif" width="80%"> 	
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="clave_contador" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
      <div class="modal-content">
      	<div class="modal-header">
        <h5 class="modal-title" id="titulo_clave">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
        <div class="modal-body text-center">
        	<div class="row">
        		<div class="col-sm-7">
        			<input type="hidden" name="TipoSuper" id="TipoSuper"><br>
        			<input type="hidden" name="intentos" id="intentos" value="1">
        			<input type="password" name="txt_IngClave" id="txt_IngClave" class="form-control input-sm" placeholder="Clave" autocomplete="off" onblur="IngresoClave();" autofocus="false">
        		</div>
        		<div class="col-sm-3">
        			<div class="btn-group">
        				<!-- <button class="btn btn-default btn-sm">Aceptar</button> --> 
        				<button class="btn btn-default"  data-dismiss="modal" onclick="limpiar_IngresoClave();"> <img src="../../img/png/bloqueo.png" ><br> Cancelar</button>			
        			</div>        			
        		</div>        		
        	</div>
        </div>
      </div>
    </div>
  </div>
   <script type="text/javascript">
  	function IngresoClave()
  	{
  		var parametros = 
  		{
  			'tipo':$('#TipoSuper').val(),
  			'intentos':$('#intentos').val(),
  			'pass':$('#txt_IngClave').val(),
  		}
  		var opcion = '';
      $.ajax({
      data:  {parametros:parametros},
       url:   '../controlador/panel.php?IngClaves=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
         console.log(response);
         if(response.respuesta==-1)
         {
           $('#intentos').val(response.intentos);
           Swal.fire(response.msj,'','info');           
         	 resp_clave_ingreso(response);
         }else
         {
         	//esta funcion debe estar definida en la paginandonde se este llamando
         	 resp_clave_ingreso(response);
         	 $('#clave_contador').modal('hide');
         }
      }
    }); 

  	}

  	function limpiar_IngresoClave()
  	{
  		$('#intentos').val('1');
  		$('#txt_IngClave').val('');
  	}
  </script>
</body>
</html>
