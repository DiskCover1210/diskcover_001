<?php  require_once("panel.php"); 
$cod = ''; $ci =''; if(isset($_GET['cod'])){$cod = $_GET['cod'];} if(isset($_GET['ci'])){$ci = $_GET['ci'];}?>
<script type="text/javascript">
   $( document ).ready(function() {
    cargar_ficha();
    cargar_pedidos();
    autocoplet_paci();
    // cargar_ficha();
  });

  function cargar_pedidos()
  {
    var ruc = '<?php echo $ci; ?>';
    var nom = $('#txt_query').val();
    var ci = ruc.substring(0,10);
    var desde=$('#txt_desde').val();
      var  parametros = 
      { 
        'codigo':ci,
        'nom':$('#txt_nombre').val(),
        'query':nom,
        'tipo':$('input:radio[name=rbl_buscar]:checked').val(),
        'desde':desde,
        'hasta':$('#txt_hasta').val(),
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/descargosC.php?cargar_pedidos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response)
        {
          $('#tbl_body').html(response.tabla);
        }
      }
    });
  }


  function cargar_ficha()
  {
    var cod ='<?php echo $cod; ?>';
    var ci = '<?php echo $ci; ?>';
    var parametros=
    {
      'cod':cod,
      'ci':ci,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/descargosC.php?pedido=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        
          console.log(response);
        if(response)
        {
          if(cod!='0')
          {
            $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
            // $('#txt_nombre').val(response[0].Cliente);
            $('#txt_codigo').val(response[0].Matricula);
            cargar_pedidos();
          }else
          {
            var url = "../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu&cod="+response[0].ORDEN+"&ci="+ci;
            $(location).attr('href',url);
          }
        }
      }
    });
  }

  function nuevo_pedido()
  {
    var cod_cli = $('#txt_codigo').val();
    if(cod_cli!='')
    {
      var href="../vista/farmacia.php?mod=Farmacia&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&cod="+cod_cli+"#";
      $(location).attr('href',href);
    }else
    {
      Swal.fire('','Escoja un paciente.','info');
    }
  }


   function autocoplet_paci(){
      $('#ddl_paciente').select2({
        placeholder: 'Seleccione una paciente',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?paciente=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }

  function buscar_cod()
  {
      var  parametros = 
      { 
        'query':$('#ddl_paciente').val(),
        'tipo':'R1',
        'codigo':'',
      }    
      //console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
       // console.log(response);
       
           $('#txt_codigo').val(response[0].Matricula);
           // $('#txt_nombre').val(response[0].Cliente);
           $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
           // $('#txt_ruc').val(response[0].CI_RUC);
      }
    });
  }

  function limpiar()
  {
    
    var href="../vista/farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#";
    $(location).attr('href',href);
    $('#txt_query').val('');
    $("#ddl_paciente").empty();
    $("#txt_codigo").val('');
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
      <div class="panel-heading text-center"><b>BUSCAR PEDIDOS</b></div>
      <div class="panel-body">
      	<div class="row">
          <div class="col-sm-6">
            <b>Codigo de cliente :</b>
            <input type="text" class="form-control input-sm" id="txt_codigo"value="<?php echo $cod; ?>">
            <b>Nombre</b>
            <div class="input-group">
                <select class="form-control input-sm" id="ddl_paciente" onchange="buscar_cod()">
                  <option value="">Seleccione paciente</option>
                </select>
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
            </div>
           
           <!--  <b>FECHA INICIO:</b>
            <input type="date" name="txt_localidad" id="txt_desde1" class="form-control input-sm">
            <b>FECHA FIN:</b>
            <input type="date" name="txt_telefono" id="txt_hasta1" class="form-control input-sm">       -->      
          </div>
          <div class="col-sm-6">
            <b>NUM FACTURA:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-sm">
          </div>          
        </div>        
      </div>
    </div>
  </div>
  <div class="row">
      <div class="col-sm-8"> 
      <div class="col-sm-6">
         <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_nombre" checked="" value="N"> Nombre</label>
         <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_ruc" value="C"> CI / RUC</label>
         <label class="radio-inline"><input type="radio" name="rbl_buscar" id="rbl_pedido" value="P"> Pedido</label>
         <br>
        <b>NOMBRE DE PACIENTE</b>
        <input type="text" name="" id="txt_query" class="form-control" placeholder="Nombre paciente" onkeypress="cargar_pedidos()" onblur="cargar_pedidos()">
      </div>
      <div class="col-sm-3">
        <br>
        <b>FECHA INICIO</b>
        <input type="date" name="" id="txt_desde" class="form-control" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos()">
      </div>
      <div class="col-sm-3">
        <br>
        <b>FECHA FIN</b>
        <input type="date" name="" id="txt_hasta" class="form-control" value="<?php echo date('Y-m-d')?>" onblur="cargar_pedidos()">
      </div>
      
    </div>
    <div class="col-sm-4">
       <div class="modal-footer">
        <!-- <button type="button" class="btn btn-info"><i class="fa fa-search"></i> Buscar</button> -->
        <button type="button" class="btn btn-primary" onclick="limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
        <button type="button" class="btn btn-success" onclick="nuevo_pedido()"><i class="fa fa-plus"></i> Nuevo Pedido</button>
      </div>
    </div>
    
  </div>
  <div class="row">
  	<div class="col-sm-10">
        N de articulos encontrados: 000
      </div>
      <div class="col-sm-2">
        mostrados: 1-50
      </div>  	
  </div>
  <div class="row"  style="height: 400px">
  	<div class="table-responsive">      
  		<table class="table table-hover">
  			<thead>
  				<th>ITEM</th>
  				<th>NUM PEDIDO</th>
  				<th>PACIENTE</th>
  				<th>IMPORTE</th>
  				<th>FECHA</th>
  				<th>ESTADO</th>
  				<th></th>
  			</thead>
  			<tbody id="tbl_body">
  				
  			</tbody>
  		</table>      
  	</div>
  </div>
</div>
