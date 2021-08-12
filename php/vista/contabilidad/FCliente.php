
<script type="text/javascript">
	$( document ).ready(function() {
		 provincias();

        $( "#ruc" ).autocomplete({              
            source: function( request, response ) {
            	 $('#txt_id').val('');         
                $.ajax({
                	  url:   '../controlador/modalesC.php?buscar_cliente=true',          
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                      // console.log(data);
                        response( data );
                    }
                });
            },
            select: function (event, ui) {
            	 limpiar();
              // console.log(ui.item);
                $('#txt_id').val(ui.item.value); // display the selected text
                $('#ruc').val(ui.item.label); // display the selected text
                $('#nombrec').val(ui.item.nombre); // save selected id to input
                $('#direccion').val(ui.item.direccion); // save selected id to input
                $('#telefono').val(ui.item.telefono); // save selected id to input
                $('#codigoc').val(ui.item.codigo); // save selected id to input
                $('#email').val(ui.item.email); // save selected id to input
                $('#nv').val(ui.item.vivienda); // save selected id to input
                $('#grupo').val(ui.item.grupo); // save selected id to input
                $('#naciona').val(ui.item.nacionalidad); // save selected id to input
                $('#prov').val(ui.item.provincia); // save selected id to input
                $('#ciu').val(ui.item.ciudad); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                $('#txt_id').val(ui.item.value); // display the selected text
                $('#ruc').val(ui.item.label); // display the selected text
                
                return false;
            },
        });
	});

	function provincias()
  {
   var option ="<option value=''>Seleccione provincia</option>"; 
     $.ajax({
      url: '../controlador/detalle_estudianteC.php?provincias=true',
      type:'post',
      dataType:'json',
     // data:{usu:usu,pass:pass},
      beforeSend: function () {
                   $("#select_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#prov').html(option);
      console.log(response);
    }
    });

  }

  function limpiar()
  {
  	$('#txt_id').val(''); // display the selected text
    $('#ruc').val(''); // display the selected text
    $('#nombrec').val(''); // save selected id to input
    $('#direccion').val(''); // save selected id to input
    $('#telefono').val(''); // save selected id to input
    $('#codigoc').val(''); // save selected id to input
    $('#email').val(''); // save selected id to input
    $('#nv').val(''); // save selected id to input
    $('#grupo').val(''); // save selected id to input
    $('#naciona').val(''); // save selected id to input
    $('#prov').val(''); // save selected id to input
    $('#ciu').val(''); // save selected id to input
  }

  function codigo()
  {
  	 var ci = $('#ruc').val();
  	 if(ci!='')
  	 {
     $.ajax({
      url:   '../controlador/modalesC.php?codigo=true',      
      type:'post',
      dataType:'json',
      data:{ci:ci},
      success: function(response){     	
      	console.log(response);
      	$('#codigoc').val(response.Codigo);
      	$('#TC').val(response.Tipo);
        
      }
    });
   }else
   {
   	 limpiar();
   }

  }


	// function buscar_cliente_ci()
	// {
	// 	var ci = $('#ruc').val();
	// 	var parametros = 
	// 	{
	// 		'ruc':ci,
	// 	}
 //     $.ajax({
 //       data:  {parametros:parametros},
 //      url:   '../controlador/modalesC.php?buscar_cliente=true',
 //      type:  'post',
 //      dataType: 'json',
 //      success:  function (response) { 
 //        // console.log(response);
 //        if(response)
 //        {

 //        }
 //      }
 //    });
	// }

	function buscar_cliente_nom()
	{
		var ci = $('#nombrec').val();
		var parametros = 
		{
			'nombre':ci,
		}
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/modalesC.php?buscar_cliente_nom=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response)
        {

        }
      }
    });
	}

	function guardar_cliente()
	{
		 var datos = $('#form_cliente').serialize();
		  $.ajax({
       data:  datos,
      url:   '../controlador/modalesC.php?guardar_cliente=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response ==1)
        {
        	if($('#txt_id').val()!='')
        	{
        		swal.fire('Registro guardado','','success')
        	}else
        	{
        		swal.fire('Registro guardado','','success')
        	}

        }
      }
    });


	}
</script>			

			<div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Datos Cliente</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" id="form_cliente">
              <div class="box-body">
				<div class="form-group">
					<label for="ruc" class="col-sm-1 control-label" id="resultado">RUC/CI*</label>
					<div class="col-sm-3">
						<input type="hidden" class="form-control" id="txt_id" name="txt_id" placeholder="ruc" autocomplete="off">
						<input type="text" class="form-control" id="ruc" name="ruc" placeholder="ruc" autocomplete="off" onblur="codigo()">
						<div id='e_ruc' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar RUC/CI</span>
						</div>
					</div>
					<label for="telefono" class="col-sm-1 control-label">Telefono*</label>
					<div class="col-sm-3">
						<input type="text" class="form-control" id="telefono" name="telefono" placeholder="Telefono" autocomplete="off">
						<div id='e_telefono' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Telefono</span>
						</div>
					</div>
					<label for="codigoc" class="col-sm-1 control-label">Codigo*</label>
					<div class="col-sm-3">
						<input type="hidden" id='buscar' name='buscar'  value='' />
						<input type="hidden" id='TC' name='TC'  value='' />
						<input type="text" class="form-control" id="codigoc" name="codigoc" placeholder="Codigo" readonly="">
						<div id='e_codigoc' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar Codigo</span>
						</div>
					</div>
                </div>
                <div class="form-group">
					<label for="nombrec" class="col-sm-1 control-label">Razon social*</label>

					<div class="col-sm-11">
						<input type="text" class="form-control" id="nombrec" name="nombrec" placeholder="Razon social" onkeyup="buscar_cliente_nom()">
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
				    	<!-- <input type="text" class="form-control" id="prov" name="prov" placeholder="Provincia"  tabindex="0"> -->
				    	<select class="form-control" id="prov" name="prov">
				    		<option>Seleccione una provincia</option>
				    	</select>
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
              	<button type="button" onclick="guardar_cliente()" class="btn btn-primary">Procesar</button>
				      </div>
              <!-- /.box-footer -->
            </form>
          </div>