<?php  require_once("panel.php");@session_start();  date_default_timezone_set('America/Guayaquil'); $nom = $_SESSION['INGRESO']['Entidad']; $entidad = $_SESSION['INGRESO']['RUCEnt']; $id = $_SESSION['INGRESO']['IDEntidad']; //print_r($_SESSION['INGRESO']);die();?>
<style type="text/css">
	.reset {
		min-height: auto;
	}
</style>
<script type="text/javascript">

  $(document).ready(function()
  {
  	var intro = document.getElementById('seccion');
    intro.style.minHeight = '800px';
   // cargar_modulos();
   entidad_default();
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
        // $('#'+id).empty();
        $('#'+id).val(null).trigger('change');
        cargar_registros();

	}

	function autocmpletar(){
      $('#ddl_entidad').select2({
        placeholder: 'Seleccione una Entidad',
        width:'resolve',
	    // minimumResultsForSearch: Infinity,
        ajax: {
          url: '../controlador/auditoria/auditoriaC.php?entidades=true',
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

  function entidad_default()
  {
  	var ent = '<?php echo $entidad;?>';
  	var id =  '<?php echo $id;?>';
  	var nom =  '<?php echo $nom;?>';

  	// console.log(ent);
  	// console.log(id);
  	if(ent != '1792164710001')
  	{
  		$('#ddl_entidad').append($('<option>',{value: ent+'_'+id, text: nom ,selected: true }));
      $('#ddl_entidad').attr("disabled", true); 

  	}else
  	{
  	}
  }

  function change_entidad()
  {
  	autocmpletar_empresa();
  	autocmpletar_usuario();
  }
   function autocmpletar_empresa(){
       let entidad = $('#ddl_entidad').val().split('_');	
       // console.log(entidad);
      $('#ddl_empresa').select2({
        placeholder: 'Seleccione una Usuario',
        width:'90%',
        ajax: {
          url: '../controlador/auditoria/auditoriaC.php?empresa=true&entidad='+entidad[1],
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
          url: '../controlador/auditoria/auditoriaC.php?modulos=true',
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
      'numReg':$('#ddl_num_reg').val(),
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/auditoria/auditoriaC.php?tabla=true',
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
  $('#ddl_entidad').attr("disabled", false); 
   var url = '../controlador/auditoria/auditoriaC.php?imprimir_pdf=true&';
   var datos =  $("#filtros").serialize();
   $('#ddl_entidad').attr("disabled", true); 
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
function reporte_excel()
{

   $('#ddl_entidad').attr("disabled", false); 
   var url = '../controlador/auditoria/auditoriaC.php?imprimir_excel=true&';
   var datos =  $("#filtros").serialize();
   $('#ddl_entidad').attr("disabled", true); 
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

function eliminar_reg()
{
  var desde  = $('#txt_desde').val();
  var hasta  = $('#txt_hasta').val();
     Swal.fire({
       title: 'Esta seguro que quiere Eliminar registros del '+desde+' al '+hasta,
       text: "Esta usted seguro de que quiere modificar!",
       type: 'warning',
       showCancelButton: true,
       confirmButtonColor: '#3085d6',
       cancelButtonColor: '#d33',
       confirmButtonText: 'Si!'
     }).then((result) => {
       if (result.value==true) {
        $('#clave_supervisor').modal('show');
        $('#TipoSuper_MYSQL').val('Supervisor');
       }
     })
}

function resp_clave_ingreso(response)
{
  if(response.respuesta == 1)
  {
    // alert('Eliminando');
    Delete_registros();
  }
}

function Delete_registros()
{
   var datos =  $("#filtros").serialize();
     $.ajax({
         data:  datos,
         url: '../controlador/auditoria/auditoriaC.php?Delete_registros=true',
         type:  'post',
         dataType: 'json',
         success:  function (response) {
           if(response ==1)
           {
            Swal.fire('Registros eliminados','','success');
            $('#clave_supervisor').modal('hide');
           } 
          
          } 
       });


}


</script>

<div class="container-lg">
  <div class="row"><br>
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Eliminar Registro del periodo" onclick="eliminar_reg()"><img src="../../img/png/delete_file.png"></button>
        </div>
 </div>
</div>
<div class="container">
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
			<div class="input-group input-group-sm"  style="display: flex;"> 
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
			<div class="input-group input-group-sm" style="display: flex;"> 
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
		<div class="col-sm-6 text-right">
			<b>Num Registros</b><br>
			<select id="ddl_num_reg" name="ddl_num_reg">
				<option value="50">1 a 50</option>
				<option value="100">1 a 100</option>
				<option value="200">1 a 200</option>
				<option value="T">Todos</option>
			</select>
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
