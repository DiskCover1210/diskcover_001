<script type="text/javascript">
	function buscar_cliente()
	{

	}
	function guardar_cliente()
	{
		var parametros = 
		{
			'ruc';'',
			'tel':'',
			'cod':'',
			'raz':'',
			'ema':'',
			'dir':,
			'num':,
			'gru':,
			'nac':,
			'pro':,
			'ciu':,

		}

	}
</script>			

			<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Datos Cliente</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal">
              <div class="box-body">
				<div class="form-group">
					<label for="ruc" class="col-sm-1 control-label" id="resultado">RUC/CI*</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="ruc" name="ruc" placeholder="ruc"  
						onkeyup="vcliente(event,'<?php echo $me; ?>','ruc')" tabindex="0">
						<div id='e_ruc' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar RUC/CI</span>
						</div>
					</div>
					<label for="telefono" class="col-sm-1 control-label">Telefono*</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Telefono" 
						onkeyup="vcliente(event,'<?php echo $me; ?>','ruc')" onclick="vcliente(event,'<?php echo $me; ?>','ruc')" tabindex="0">
						<div id='e_telefono' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Telefono</span>
						</div>
					</div>
					<label for="codigoc" class="col-sm-1 control-label">Codigo*</label>
					<div class="col-sm-3">
						<input type="hidden" id='buscar' name='buscar'  value='' />
						<input type="hidden" id='TC' name='TC'  value='' />
						<input type="text" class="form-control" id="codigoc" name="codigoc" placeholder="Codigo" disabled
						tabindex="0">
						<div id='e_codigoc' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Codigo</span>
						</div>
					</div>
                </div>
                <div class="form-group">
					<label for="nombrec" class="col-sm-1 control-label">Razon social*</label>

					<div class="col-sm-11">
						<input type="text" class="form-control" id="nombrec" name="nombrec" placeholder="Razon social" 
						tabindex="0">
						<div id='e_nombrec' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Razon social</span>
						</div>
					</div>
                </div>
				<div class="form-group">
					<label for="email" class="col-sm-1 control-label">Email</label>

					<div class="col-sm-11">
						<input type="email" class="form-control" id="email" name="email" placeholder="Email" tabindex="0">
					</div>
                </div>
				<div class="form-group">
					<label for="direccion" class="col-sm-1 control-label">Direccion*</label>

					<div class="col-sm-11">
						<input type="text" class="form-control" id="direccion" name="direccion" placeholder="Direccion" tabindex="0">
						<div id='e_direccion' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Direccion</span>
						</div>
					</div>
                </div>
				<div class="form-group">
				    <label for="nv" class="col-sm-1 control-label">Numero vivienda</label>

				    <div class="col-sm-3">
				    	<input type="text" class="form-control" id="nv" name="nv" placeholder="Numero vivienda"  tabindex="0">
				    </div>
				    <label for="grupo" class="col-sm-1 control-label">Grupo</label>
				    <div class="col-sm-3">
						<input type="text" class="form-control" id="grupo" name="grupo" placeholder="Grupo" 
						tabindex="0">
					</div>
					<label for="naciona" class="col-sm-1 control-label">Nacionalidad</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="naciona" name="naciona" placeholder="Nacionalidad" 
						tabindex="0">
					</div>
                </div>
				<div class="form-group">
				    <label for="prov" class="col-sm-1 control-label">Provincia</label>

				    <div class="col-sm-5">
				    	<input type="text" class="form-control" id="prov" name="prov" placeholder="Provincia"  tabindex="0">
				    </div>
				    <label for="ciu" class="col-sm-1 control-label">Ciudad</label>
				    <div class="col-sm-5">
						<input type="text" class="form-control" id="ciu" name="ciu" placeholder="Ciudad" 
						tabindex="0">
					</div>
                </div>
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<a class="btn btn-info pull-right" id='proce' onclick="guardac1(event,'<?php echo $me; ?>','ruc','<?php echo $me; ?>')" 
				onkeyup="guardac1(event,'<?php echo $me; ?>','ruc','<?php echo $me; ?>')" tabindex="0">
					Procesar
				</a>
				<a class="btn btn-default" tabindex="0">
					Cancelar
				</a>                
              </div>
              <!-- /.box-footer -->
            </form>
          </div>