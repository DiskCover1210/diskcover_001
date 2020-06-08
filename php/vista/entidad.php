<?php
/**
 * Autor: Rodrigo Chambi Q.
 * Mail:  filvovmax@gmail.com
 * web:   www.gitmedio.com
 */
//require_once 'determ.php';
require_once("panel.php");
require_once("chequear_seguridad_e.php");
require_once("../controlador/entidad.php");
	//echo ' jjj '.$_SESSION['autentificado'];
	//die();
	//cuerpo
?>
	<div class="panel panel-info">
		<div class="panel-heading">
			<p class="box-title">Entidad</p>
		</div>
	</div>
	<!-- <h3 class="box-title">Entidad </h3>-->
		<div class="row">
			<div class="col-xs-12">
			  <div class="box">
				<div class="box-header">
					
				 <!-- <h3 class="box-title">Responsive Hover Table</h3>

				  <div class="box-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
					  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

					  <div class="input-group-btn">
						<button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
					  </div>
					</div>
				  </div>-->
				</div>
				<!-- /.box-header -->
				<div class="box-body table-responsive no-padding">
				  <table class="table table-hover" style='border-color: #bce8f1;color:#31708f;'>
					<tr style='background-color: #006699; color: #FFFFFF'>
					  <th>ID</th>
					  <th>Nombre</th>
					  <th>RUC</th>
					  <th>Representante</th>
					  <th>Status</th>
					</tr>
					<?php
						//saber si es super admin o no
						$entidades=getEntidades();
						$con=0;
						for($con=0;$con<count($entidades);$con++)
						{
							?>
						<tr>
							  <td><?php echo $entidades[$con]['ID']; ?></td>
							  <td><?php echo $entidades[$con]['Nombre_Entidad']; ?></td>
							  <td><?php echo $entidades[$con]['RUC_CI_NIC']; ?></td>
							  <td><?php echo $entidades[$con]['Representante']; ?></td>
							  <td><span class="label label-success">Approved</span></td>
						</tr>
						<?php
							//echo $entidades[$con]['ID']."<br>";
						}
					?>
				  </table>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->
			</div>
		</div>
<?php

require_once("footer.php");
	
?>			
	
