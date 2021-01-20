<?php  require_once("panel.php");?>
<script type="text/javascript">
  $( document ).ready(function() {
     provincia();
     cargar_clientes();
  });

  function provincia()
  {
     $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?provincias=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response)
        {
          var op = '<option value="">Seleccione provincia</option>';
          $.each(response,function(i,item){
             op+= '<option value="'+item.Codigo+'">'+item.Descripcion_Rubro+'</option>';
          });
          $('#ddl_provincia').html(op);
        }
      }
    });
  }

  function cargar_clientes()
  {
    var query = $('#txt_query').val();
    var rbl = $('input:radio[name=rbl_buscar]:checked').val();
    var pag =$('#txt_pag').val();
    var parametros = 
    {
      'query':query,
      'tipo':rbl,
      'codigo':'',
      'pag':pag,  // numero de registeros que se van a visualizar
      'fun':'cargar_clientes' // funcion que se va a a ejecutar en el paginando para recargar
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?pacientes=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log();
        if(response)
        {
          $('#tbl_pacientes').html(response.tr);
          $('#tbl_pag').html(response.pag);
        }
      }
    });
  }

  function limpiar()
  {

    $('#txt_codigo').val('');
    $('#txt_nombre').val('');
    $('#txt_ruc').val('');
    $('#ddl_provincia').val('');
    $('#txt_localidad').val('');
    $('#txt_telefono').val('');
    $('#txt_tip').val('N');    
    $('#txt_id').val('');
    $('#btn_nu').html('<i class="fa fa-plus"></i> Nuevo cliente');
  }


  function nuevo_paciente()
  {

    var parametros = 
    {
       // 'cod':$('#txt_codigo').val(),
       'id':$('#txt_id').val(),
       'nom':$('#txt_nombre').val(),
       'ruc':$('#txt_ruc').val(),
       'pro':$('#ddl_provincia').val(),
       'loc':$('#txt_localidad').val(),
       'tel':$('#txt_telefono').val(),
       'tip':$('#txt_tip').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          if(parametros.tip=='E')
          {
           Swal.fire('','Cliente Editado.','success');
           cargar_clientes();
           }else
           {
            Swal.fire('','Nuevo Cliente Registrado.','success');
            cargar_clientes();
           }          
        }else
        {
          Swal.fire('','Existio algun tipo de problema intente mas tarde.','error');
        }
      }
    });

  }

  function buscar_cod(tipo,campo)
  {

      $('#myModal_espera').modal('show');    
    $('#btn_nu').html('<i class="fa fa-pencil"></i> Editar cliente');
    $('#txt_tip').val('E');
    var query = $('#'+campo).val();
    var parametros;
    if(tipo=='N' || tipo=='N1')
    {
     parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }else if(tipo=='R' || tipo=='R1' )
    {
       parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }else if(tipo=='E')
    {
      parametros = 
      { 
        'query':'',
        'tipo':'',
        'codigo':campo,
      }

    }else
    {
      parametros = 
      { 
        'query':query,
        'tipo':tipo,
        'codigo':'',
      }
    }
    
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response !=-1)
        {
           $('#txt_codigo').val(response[0].Matricula);
           $('#txt_nombre').val(response[0].Cliente);
           $('#txt_ruc').val(response[0].CI_RUC);
           $('#ddl_provincia').val(response[0].Prov);
           $('#txt_localidad').val(response[0].Ciudad);
           $('#txt_telefono').val(response[0].Telefono);
           $('#txt_id').val(response[0].ID);
           $(window).scrollTop(0);
          
        }else
        {
          var query = $('#'+campo).val();
          limpiar();
          $('#'+campo).val(query);
          Swal.fire('','No se a es encontrado registros.','info');
        }

       $('#myModal_espera').modal('hide');    
      }
    });
  }

  function eliminar(cli,ruc)
  {
    Swal.fire({
      title: 'Quiere eliminar este registro?',
      text: "Esta seguro de eliminar este registro!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
              $.ajax({
                data:  {cli:cli,ruc:ruc},
                url:   '../controlador/farmacia/pacienteC.php?eliminar=true',
                type:  'post',
                dataType: 'json',
                success:  function (response) 
                      {
                        if(response ==1)
                          {
                            Swal.fire('','Agregado en entregas.','success');
                          }else
                          {
                            Swal.fire('','Este usuario tiene Datos ligados.','error');
                          }
                      }
                });
        }
      });

  }

</script>
<div class="container-lg">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button title="Guardar"  class="btn btn-default" onclick="">
            <img src="../../img/png/grabar.png" >
          </button>
        </div>     
 </div>
</div>
<div class="container">
  <div class="row"><br>
     <div class="panel panel-primary">
      <div class="panel-heading text-center"><b>BUSCAR CLIENTES</b></div>
      <div class="panel-body">
      	<div class="row">
          <div class="col-sm-6">
            <b>Codigo de cliente :</b>
            <input type="hidden" class="form-control" id="txt_tip" value="N">
            <input type="hidden" class="form-control" id="txt_id">   
            <div class="input-group">
                <input type="text" class="form-control input-sm" id="txt_codigo">                
                <span class="input-group-addon" title="Buscar" onclick="buscar_cod('C1','txt_codigo')"><i class="fa fa-search"></i></span>
                <!-- <span class="input-group-addon" title="Buscar"><i class="fa fa-search"></i></span> -->
            </div>
            <b>RUC / CI:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-sm">
                
            <b>Provincia:</b>
            <select class="form-control input-sm" id="ddl_provincia">
              <option>Seleccione una provincia</option>
            </select>    
                   
          </div>
          <div class="col-sm-6">
             <b>Nombre</b>
                <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-sm">
                 <b>Teléfono:</b>
            <input type="text" name="txt_telefono" id="txt_telefono" class="form-control input-sm">
            <b>Localidad:</b>
            <input type="text" name="txt_localidad" id="txt_localidad" class="form-control input-sm">    
            
          </div>          
        </div>        
      </div>
    </div>
  </div>
  <div class="row">
     <div class="col-sm-6">
        <input type="text" name="" placeholder="Buscar" class="form-control" onkeyup="cargar_clientes()" id="txt_query">
        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"><b> Nombre</b></label>
        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_codigo" value="C"><b> Codigo</b></label>
        <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_ruc" value="R"><b> RUC / CI</b></label>
       
            
     </div> 
     <div class="col-sm-6">
        <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick=" limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
        <button type="button" class="btn btn-success" id="btn_nu" onclick="nuevo_paciente()"><i class="fa fa-plus"></i> Nuevo cliente</button>
        <button type="button" class="btn btn-default"><i class="fa fa-print"></i> Imprimirr</button>
      </div>
     </div>             
  </div>
  <div class="row">
  	<div class="col-sm-10">
        <!-- N de articulos encontrados: 000 -->
      </div>
      <input type="hidden" name="" id="txt_pag" value="0">
      <div class="col-sm-2" id="tbl_pag">
        <!-- mostrados: 1-50 -->
      </div>  	
  </div>
  
  <div class="row" >
  	<div class="table-responsive">      
  		<table class="table table-hover">
  			<thead>
  				<th>ITEM</th>
  				<th>CODIGO</th>
  				<th>NOMBRE</th>
  				<th>RUC</th>
  				<th>TELEFONO</th>
  				<th></th>
  			</thead>
  			<tbody id="tbl_pacientes">
  				<tr>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-pencil"></span></button>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-search"></span></button>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>
  			</tbody>
  		</table>
  	</div>
  </div>
</div>
