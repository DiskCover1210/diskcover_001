<?php  require_once("panel.php"); date_default_timezone_set('America/Guayaquil'); ?>
<script type="text/javascript">

  $(document).ready(function()
  {
   // cargar_modulos();
   autocmpletar();
   autocmpletar_usuario();
   autocmpletar_modulos(); 
   autocmpletar_empresa();
   cargar_registros();
  });

	function ocultar_ddl(id)
	{
        var res = id.replace("rbl", "ddl");
        if($('#'+id).prop('checked'))
        {
        	$('#'+res).css('display','block');
        // alert(res);

        }else
        {
        	$('#'+res).css('display','none');
        }

	}
	function limpiar_ddl(id)
	{
        $('#'+id).empty();
        cargar_registros();

	}

	function autocmpletar(){
      $('#ddl_entidad').select2({
        placeholder: 'Seleccione una Entidad',
        width:'resolve',
	    // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/seteos/auditoriaC.php?entidades=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }

  function change_entidad()
  {
  	autocmpletar_empresa();
  	autocmpletar_usuario();
  }
   function autocmpletar_empresa(){
       let entidad = $('#ddl_entidad').val().split('_');	
       console.log(entidad);
      $('#ddl_empresa').select2({
        placeholder: 'Seleccione una Usuario',
        width:'90%',
        ajax: {
          url: '../controlador/seteos/auditoriaC.php?empresa=true&entidad='+entidad[1],
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }

  function autocmpletar_usuario(){
       let entidad = $('#ddl_entidad').val().split('_');	
      $('#ddl_usuario').select2({
        placeholder: 'Seleccione una Usuario',
        width:'90%',
        ajax: {
          url: '../controlador/niveles_seguriC.php?usuarios=true&entidad='+entidad[1],
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }


  function autocmpletar_modulos(){
      $('#ddl_modulos').select2({
        placeholder: 'Seleccione una Usuario',
        width:'90%',
        ajax: {
          url: '../controlador/seteos/auditoriaC.php?modulos=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }



   function cargar_registros()
   {
   	var enti = $('#ddl_entidad').val().split('_');
    var parametros = 
    {
      'des':$('#txt_desde').val(),
      'has':$('#txt_hasta').val(),
      'ent':enti[0], 
      'emp':$('#ddl_empresa').val(),  // numero de registeros que se van a visualizar
      'mod':$('#ddl_modulos').val(),
      'usu':$('#ddl_usuario').val(),
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/seteos/auditoriaC.php?tabla=true',
      type:  'post',
      dataType: 'json',
       beforeSend: function () {
                $("#tbl_tabla").html('<tr><td colspan="7" class="text-center"><img src="../../img/gif/loader4.1.gif" width="250px"></td></tr>');
             },
      success:  function (response) { 
        // console.log(response);
       $('#tbl_tabla').html(response);
      }
    });

   }
function reporte_pdf()
{
   var url = '../controlador/seteos/auditoriaC.php?imprimir_pdf=true&';
   var datos =  $("#filtros").serialize();
    window.open(url+datos, '_blank');
     $.ajax({
         data:  {datos:datos},
         url:   url,
         type:  'post',
         dataType: 'json',
         success:  function (response) {  
          
          } 
       });

}

</script>

<div class="container-lg">
  <div class="row"><br>
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 </div>
</div>
<div class="container"><br>
	<form id="filtros">
	<div class="row">
		<div class="col-sm-2">
			<b>Desde</b>
			<input type="date" name="txt_desde" id="txt_desde" class="form-control input-sm" value="<?php echo date('Y-m-d');?>"  onKeyPress="return soloNumeros(event)" maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="cargar_registros()">
		</div>
		<div class="col-sm-2">
			<b>Hasta</b>
			<input type="date" name="txt_hasta" id="txt_hasta" class="form-control input-sm" value="<?php echo date('Y-m-d');?>" onKeyPress="return soloNumeros(event)" maxlength="10" onkeyup="validar_year_mayor(this.id)" onblur="cargar_registros()">
		</div>
		<div class="col-sm-4">
			<b>ENTIDAD</b>
			<div class="input-group input-group-sm"> 
			    <select class="form-control input-sm" id="ddl_entidad" name="ddl_entidad" style="display: none;"  onchange="change_entidad();cargar_registros();">
				    <option value="">Seleccione entidad</option>
			    </select>
			    <span class="input-group-btn"><br>
                    <button type="button" class="btn btn-danger" onclick="limpiar_ddl('ddl_entidad')"><i class="fa fa-trash"></i></button>
                </span>
			</div>			
		</div>
		<div class="col-sm-4">
			<b>EMPRESA</b>
			<div class="input-group input-group-sm"> 
			    <select class="form-control input-sm" id="ddl_empresa" name="ddl_empresa" onchange="cargar_registros()">
				    <option value="">Seleccione Empresa</option>
			    </select>
			    <span class="input-group-btn"><br>
                    <button type="button" class="btn btn-danger" onclick="limpiar_ddl('ddl_empresa')"><i class="fa fa-trash"></i></button>
                </span>
			</div>			
		</div>				
	</div>
	<div class="row">
		<div class="col-sm-3">
			<b> MODULOS</b>
			<div class="input-group input-group-sm">  
			    <select class="form-control input-sm" id="ddl_modulos" name="ddl_modulos" style="display: none;" onchange="cargar_registros()">
				    <option value="">Seleccione modulos</option>
			    </select>
			    <span class="input-group-btn"><br>
                    <button type="button" class="btn btn-danger" onclick="limpiar_ddl('ddl_modulos')"><i class="fa fa-trash"></i></button>
                </span>
			</div>			
		</div>
		<div class="col-sm-3">
		    <b> USUARIO</b>
			<div class="input-group input-group-sm">  
				     <select class="form-control input-sm" id="ddl_usuario" name="ddl_usuario" onchange="cargar_registros()">
				     	<option value="">Seleccione usuario</option>
				     </select>	
                    <span class="input-group-btn"><br>
                      <button type="button" class="btn btn-danger" onclick="limpiar_ddl('ddl_usuario')"><i class="fa fa-trash"></i></button>
                    </span>
              </div>
		</div>		
	</div>
</form>
	<div class="row"><br>
		<div class="table responsive">
			<table class="table table-hover">
				<thead>
					<th >FECHA</th>
					<th>HORA</th>
					<th>ENTIDAD</th>
					<th>IP ACCESO</th>
					<th>MODULO</th>
					<th>TAREA REALIZADA</th>
					<th>EMPRESA</th>
					<th>USAURIO</th>
				</thead>
				<tbody id="tbl_tabla">
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>
					
				</tbody>
			</table>
			
		</div>
		
		
	</div>
  
</div>
