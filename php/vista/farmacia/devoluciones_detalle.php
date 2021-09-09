<?php  require_once("panel.php"); $cod='';if(isset($_GET['comprobante'])){$cod =$_GET['comprobante'];} $_SESSION['INGRESO']['modulo_']='99'; date_default_timezone_set('America/Guayaquil'); 
      unset($_SESSION['NEGATIVOS']['CODIGO_INV']);?>
<script type="text/javascript">
   $( document ).ready(function() {
    // autocoplet_paci();
    // autocoplet_ref();
    // autocoplet_desc();
    // autocoplet_cc();
    // autocoplet_area();
    // num_comprobante();
    //  // buscar_cod();
    // if(c!='')
    // {
    //   buscar_codi();
    // }
    // if(area !='')
    // {
    //   buscar_Subcuenta();
    // }
    cargar_pedido();

  });



 
  function buscar_cod()
  {
      var  parametros = 
      { 
        'query':$('#ddl_paciente').val(),
        'tipo':'R1',
        'codigo':'',
      }    
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response != -1){       
           $('#txt_codigo').val(response[0].Matricula);
           $('#txt_nombre').val(response[0].Cliente);
           $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
           $('#txt_ruc').val(response[0].CI_RUC);
         }
      }
    });
  }

   function buscar_codi()
  {
      var  parametros = 
      { 
        'query':'<?php echo $cod; ?>',
        'tipo':'C1',
        'codigo':'',
      }    
      // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
       
           $('#txt_codigo').val(response.matricula);
           $('#txt_nombre').val(response.nombre);
           $('#ddl_paciente').append($('<option>',{value: response.ci, text:response.nombre,selected: true }));
           $('#txt_ruc').val(response.ci);
      }
    });
  }

  function producto_seleccionado(tipo)
  {
    if(tipo=='R')
    {
      var val = $('#ddl_referencia').val();
      var partes = val.split('_');
        $('#ddl_descripcion').append($('<option>',{value: partes[0]+'_'+partes[1]+'_'+partes[2]+'_'+partes[3]+'_'+partes[4]+'_'+partes[5]+'_'+partes[6], text:partes[2],selected: true }));
        $('#txt_precio').val(partes[1]); 
        $('#txt_iva').val(partes[6]); 
        $('#txt_unidad').val(partes[7]); 
        $('#txt_Stock').val(partes[8]);

        $('#txt_max').val(partes[9]);
        $('#txt_min').val(partes[10]); 
        console.log($('#ddl_referencia').val());
    }else
    {
      var val = $('#ddl_descripcion').val();
      var partes = val.split('_');
        $('#ddl_referencia').append($('<option>',{value: partes[0]+'_'+partes[1]+'_'+partes[2]+'_'+partes[3]+'_'+partes[4]+'_'+partes[5]+'_'+partes[6], text:partes[0],selected: true }));

        // console.log($('#ddl_descripcion').val());
        $('#txt_precio').val(partes[1]); 
        $('#txt_iva').val(partes[6]);  
        $('#txt_unidad').val(partes[7]);
        $('#txt_Stock').val(partes[8]);
        $('#txt_max').val(partes[9]);
        $('#txt_min').val(partes[10]); 
        console.log($('#ddl_descripcion').val());
    }

  }





   function cargar_pedido()
  {
    
    var comprobante = '<?php echo $cod; ?>';    
     // console.log(parametros);
     $.ajax({
      data:  {comprobante:comprobante},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?datos_comprobante=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        if(response)
        {
          $('#tbl_body').html(response.tabla);
          $('#paciente').text(response.cliente[0].Cliente);
          $('#detalle').text(response.cliente[0].Concepto);
          $('#fecha').text(response.cliente[0].Fecha.date);
          $('#comp').text(response.cliente[0].Numero);
          $('#txt_total2').text(response.total);
          num_li = response.lineas;
        }
      }
    });
  }


   function num_comprobante()
   {
    var fecha = $('#txt_fecha').val();
     $.ajax({
       data:  {fecha:fecha},
      url:   '../controlador/farmacia/articulosC.php?num_com=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#num').text(response);
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
          <button href="#" title="Generar reporte pdf"  class="btn btn-default" onclick="generar_informe()">
            <img src="../../img/png/impresora.png" >
          </button>
        </div> 
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
          </a>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
          </a>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
            <img src="../../img/png/articulos.png" >
          </a>
        </div> 
            
 </div>
</div>
<div class="container">
  <div class="row"><br>
     <div class="panel panel-info">
      <div class="panel-heading">
        <div class="row">
         <div class="col-sm-6 text-right"><b>Devoluciones de insumos</b></div>         
         <div class="col-sm-6 text-right"> No. COMPROBANTE  <u id="num"></u></div>        
        </div>
      </div>
      <div class="panel-body" style="border: 1px solid #337ab7;">
        <div class="row">
          <div class="col-sm-3"> 
            <b>Num Historia clinica:</b>
            <input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-sm" readonly="">      
          </div>
          <div class="col-sm-6">
            <b>Nombre:</b>
            <!-- <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-sm"> -->
            <select class="form-control input-sm" id="ddl_paciente" onchange="buscar_cod()">
              <option value="">Seleccione paciente</option>
            </select>
          </div>
          <div class="col-sm-3">
            <b>RUC:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-sm">             
          </div>          
        </div>
      </div>
       <div class="panel-body">
        <div class="row">
          <div class="col-sm-4"> 
            <b>Centro de costos:</b>
            <select class="form-control input-sm" id="ddl_cc" onchange="')">
              <option value="">Seleccione Centro de costos</option>
            </select>           
          </div>
          <div class="col-sm-2">    
          <b>Numero de pedido</b>
          <input type="text" name="" id="txt_pedido" readonly="" class="form-control input-sm" value="">     
          </div>
          <div class="col-sm-3">
             <b>Fecha:</b>
            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm" value="<?php echo date('Y-m-d')  ?>" onblur="num_comprobante()">                 
          </div>
          <div class="col-sm-3">
            <b>Area de descargo</b>
            <select class="form-control input-sm" id="ddl_areas">
              <option value="">Seleccione motivo de ingreso</option>
            </select>            
          </div>          
        </div>
        <div class="row">
          <div class="col-sm-4"> 
            <b>Cod Producto:</b>
            <select class="form-control input-sm" id="ddl_referencia" onchange="producto_seleccionado('R')">
              <option value="">Escriba referencia</option>
            </select>           
          </div>
          <div class="col-sm-5"> 
                <b>Descripcion:</b>
                <select class="form-control input-sm" id="ddl_descripcion" onchange="producto_seleccionado('D')">
                  <option value="">Escriba descripcion</option>
                </select>          
              </div> 
          <div class="col-sm-3"> 
            <b>Procedimiento:</b>
            <div class="input-group input-group-sm">
                <textarea class="form-control input-sm" style="resize: none;" name="txt_procedimiento" id="txt_procedimiento" readonly=""></textarea>          
                    <span class="input-group-btn">
                      <button type="button" class="btn btn-info btn-flat" onclick="cambiar_procedimiento()"><i class="fa fa-pencil"></i></button>
                    </span>
              </div>
           
          </div>           
        </div>
        <div class="row">
               <div class="col-sm-4"> 
                  <div class="col-sm-3"> 
                    <b>MIN:</b>
                    <input type="text" name="txt_min" id="txt_min" class="form-control input-sm"readonly="">
                  </div>
                  <div class="col-sm-3"> 
                    <b>MAX:</b>
                    <input type="text" name="txt_max" id="txt_max" class="form-control input-sm"readonly="">
                  </div>   
                  
              </div>               
              <div class="col-sm-2"> 
                <b>Costo:</b>
                <input type="text" name="txt_precio" id="txt_precio" class="form-control input-sm" value="0" onblur="calcular_totales();" readonly="">            
              </div>   
              <div class="col-sm-1"> 
                <b>Cantidad:</b>
                <input type="text" name="txt_cant" id="txt_cant" class="form-control input-sm" value="1" onblur="calcular_totales();">            
              </div>   
              <div class="col-sm-1"> 
                <b>UNI:</b>
                <input type="text" name="txt_unidad" id="txt_unidad" class="form-control input-sm" readonly="">            
              </div>
              <div class="col-sm-1"> 
                <b>Stock:</b>
                <input type="text" name="txt_Stock" id="txt_Stock" class="form-control input-sm" readonly="">            
              </div>    
              <div class="col-sm-1"> 
                <b>Importe:</b>
                <input type="text" name="txt_importe" id="txt_importe" class="form-control input-sm" readonly="">
                <input type="hidden" name="txt_iva" id="txt_iva" class="form-control input-sm">            
              </div> 
              <div class="col-sm-1"><br>
                <button class="btn btn-primary" onclick="calcular_totales();Guardar()"><i class="fa fa-arrow-down"></i> Agregar</button>
              </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="table-responsive">
      <input type="hidden" name="" id="txt_num_lin" value="0">
      <input type="hidden" name="" id="txt_num_item" value="0">
      <input type="hidden" name="txt_neg" id="txt_neg" value="false">
      <div class="col-sm-12"> 
        	<div class="table-responsive">
        		<table id="datos_t">
        			<thead>
        				<th>Codigo</th>
        				<th>Producto</th>
        				<th>Cantidad</th>
        				<th>Precio Uni</th>
        				<th>Precio Total</th>
        				<th>cant devolver</th>
        				<th>Valor</th>
        				<th>Total devolucion</th>
                <th></th>
        			</thead>
        			<tbody id="tbl_body">
        				
        			</tbody>
        		</table>
        	</div>
  		</div>
         
    </div>
    
  </div>
</div>

<div class="modal fade" id="modal_procedimiento" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Cambiar procedimiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <div class="row">
          <div class="col-sm-12">
            Nombre de procedimiento
            <input type="text" class="form-control input-sm" name="txt_new_proce" id="txt_new_proce">
          </div>        
         </div> 
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="guardar_new_pro();">Guardar</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>
<style type="text/css">
          #datos_t tbody tr:nth-child(even) { background:#fffff;}
          #datos_t tbody tr:nth-child(odd) { background: #e2fbff;}
          #datos_t tbody tr:nth-child(even):hover {  background: #DDB;}
          #datos_t thead { background: #afd6e2; }
          #datos_t tbody tr:nth-child(odd):hover {  background: #DDA;}
          #datos_t table {border-collapse: collapse;}
          #datos_t table, th, td {  border: solid 1px #aba0a0;  padding: 2px;  }
          #datos_t tbody { box-shadow: 10px 10px 6px rgba(0, 0, 0, 0.6);  }
          #datos_t thead { background: #afd6e2;  box-shadow: 10px 0px 6px rgba(0, 0, 0, 0.6);} 

          /*#datos_t tbody { display:block; height:300px;  overflow-y:auto; width:fit-content;}*/
          /*#datos_t thead,tbody tr {    display:table;  width:100%;  table-layout:fixed; } */
          #datos_t thead { width: calc( 100% - 1.2em ) /*scrollbar is average 1em/16px width, remove it from thead width*/ }


       </style>      