<?php  require_once("panel.php"); $cod='';$area='';$sub='';if(isset($_GET['comprobante'])){$cod =$_GET['comprobante'];}
if(isset($_GET['subcta'])){$sub =$_GET['subcta'];}
if(isset($_GET['area'])){$area =$_GET['area'];}
 $_SESSION['INGRESO']['modulo_']='99'; date_default_timezone_set('America/Guayaquil'); 
      unset($_SESSION['NEGATIVOS']['CODIGO_INV']);?>
<script type="text/javascript">
   $( document ).ready(function() {
   	autocoplet_pro();
   	 autocoplet_area();
    // autocoplet_paci();
    // autocoplet_ref();
    // autocoplet_desc();
    // autocoplet_cc();
    // autocoplet_area();
    num_comprobante();
    //  // buscar_cod();
    // if(c!='')
    // {
    //   buscar_codi();
    // }
    // if(area !='')
    // {
    //   buscar_Subcuenta();
    // }
    var num_li=0;
    // cargar_pedido();
    lista_devolucion();

  });



 
  // function buscar_cod()
  // {
  //     var  parametros = 
  //     { 
  //       'query':$('#ddl_paciente').val(),
  //       'tipo':'R1',
  //       'codigo':'',
  //     }    
  //     // console.log(parametros);
  //    $.ajax({
  //     data:  {parametros:parametros},
  //     url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
  //     type:  'post',
  //     dataType: 'json',
  //     success:  function (response) { 
  //       // console.log(response);
  //       if(response != -1){       
  //          $('#txt_codigo').val(response[0].Matricula);
  //          $('#txt_nombre').val(response[0].Cliente);
  //          $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
  //          $('#txt_ruc').val(response[0].CI_RUC);
  //        }
  //     }
  //   });
  // }

  //  function buscar_codi()
  // {
  //     var  parametros = 
  //     { 
  //       'query':'<?php echo $cod; ?>',
  //       'tipo':'C1',
  //       'codigo':'',
  //     }    
  //     // console.log(parametros);
  //    $.ajax({
  //     data:  {parametros:parametros},
  //     url:   '../controlador/farmacia/pacienteC.php?buscar_edi=true',
  //     type:  'post',
  //     dataType: 'json',
  //     success:  function (response) { 
  //       // console.log(response);
       
  //          $('#txt_codigo').val(response.matricula);
  //          $('#txt_nombre').val(response.nombre);
  //          $('#ddl_paciente').append($('<option>',{value: response.ci, text:response.nombre,selected: true }));
  //          $('#txt_ruc').val(response.ci);
  //     }
  //   });
  // }






  // function cargar_pedido()
  // {   
  //   $('#modal_espera').modal('show');
  //   var comprobante = '<?php echo $cod; ?>';  
  //   var query = $('#txt_query').val();    
  //    // console.log(parametros);
  //    $.ajax({
  //     data:  {comprobante:comprobante,query:query},
  //     url:   '../controlador/farmacia/devoluciones_insumosC.php?datos_comprobante=true',
  //     type:  'post',
  //     dataType: 'json',
  //     success:  function (response) { 

  //   $('#modal_espera').modal('hide');
  //     	console.log(response);
  //       if(response)
  //       {
  //         // $('#paciente').val(response.cliente[0].Cliente);
  //         $('#cod').val(response.cliente[0].CodigoL);
  //         $('#detalle').text(response.cliente[0].Concepto);
  //         $('#fecha').text(response.cliente[0].Fecha.date);
  //         $('#comp').val(response.cliente[0].Numero);
  //         num_li = response.lineas;
  //       }
  //     }
  //   });
  // }

function lista_devolucion()
  {   
    var comprobante = '<?php echo $cod; ?>';  
      $('#txt_orden').val(comprobante);
      var sub= '<?php echo $sub; ?>';
      var no = '<?php echo $area; ?>';
      // console.log(sub);console.log(no);
      $('#ddl_areas').append($('<option>',{value: sub, text: no,selected: true }));
    if(sub!='')
    {
       // $('#ddl_areas').attr('readonly',true);
       // $('select').prop('disabled', true);
       $('#ddl_areas').prop('disabled', true);
    }
    if(comprobante=='')
    {
       $('#txt_orden').attr('readonly',false);
    }
     // console.log(parametros);
     $.ajax({
      data:  {comprobante:comprobante},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?lista_devolucion_dep=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {
        $('#tbl_devoluciones').html('<img src="../../img/gif/loader4.1.gif" width="30%">');        
      },
      success:  function (response) { 
        $('#tbl_devoluciones').html(response.tr);
        $('#lineas').val(response.lineas)
        // if(response.lineas==0)
        // {
        //    location.href='../vista/farmacia.php?mod=Farmacia&acc=devoluciones_departamento&acc1=Devolucion%20por%20departamentos&b=1&po=sub'
        // }
      }
    });
  }




  function costo(codigo,id)
  {    
     $.ajax({
      data:  {codigo:codigo},
      url:   '../controlador/farmacia/devoluciones_insumosC.php?costo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        $('#txt_valor_'+id).val(response[0].Costo.toFixed(2));
        var Costo = response[0].Costo;
        var devolucion = $('#txt_cant_dev_'+id).val();
        var tot = Costo*devolucion;
        $('#txt_gran_t_'+id).val(tot.toFixed(2));
         var total =0; 
         for (var i =1 ; i < num_li+1; i++){
            total+=parseFloat($('#txt_gran_t_'+i).val());       
         }
         $('#txt_tt').text(total.toFixed(2));
      }
    });
  }


  function calcular()
  {
     var cant = $('#txt_cant').val();
     var prec = $('#txt_precio').val();
     if(cant==0 || cant=='')
     {
      cant =1;
     }
     if(prec=='')
     {
       prec=0;
     }

     var t = parseFloat(cant*prec);
     $('#txt_total').val(t.toFixed(2));


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


   function guardar_devolucion()
   {
   	var com = $('#txt_orden').val();
    var are = $('#ddl_areas').val();
    var nom_a = $('#ddl_areas option:selected').text();
    var parametros = 
    {
      'codigo':$('#txt_codigo').val(),
      'producto':$('#ddl_producto option:selected').text(),
      'cantidad':$('#txt_cant').val(),
      'precio':$('#txt_precio').val(),
      'total':$('#txt_total').val(), 
      'area':are,
      'comprobante': com,
      'linea': $('#lineas').val(),
    }
    if( $('#txt_cant').val() == 0 || $('#ddl_producto').val()=='' || $('#ddl_areas').val() =='' || com=='')
    {
      Swal.fire('Asegurese de llenar todos os campos','','info');
      return false;
    }

    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/farmacia/devoluciones_insumosC.php?guardar_devolucion_departamentos=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
          Swal.fire('Agregado a lista de devoluciones','','success');
          if($('#lineas').val()==0)
          {
          location.href='../vista/farmacia.php?mod=Farmacia&acc=devoluciones_departamento&acc1=Devolucion%20por%20departamentos&b=1&po=sub&comprobante='+com+'&subcta='+are+'&area='+nom_a; 
          }else{
          lista_devolucion();
          }
          // cargar_pedido();
         }
        }
      });

   }

    function Eliminar(comp,codigo,No)
  {
       Swal.fire({
      title: 'Esta seguro de eliminar este registro?',
      text:  "No se eliminara el registro seleccionado",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
         Eliminar_linea(comp,codigo,No)
        }
      })
  }

   function Eliminar_linea(comp,codigo,No)
   {
    var parametros = 
    {
      'codigo':codigo,
      'comprobante': comp,
      'No':No,  
    }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/farmacia/devoluciones_insumosC.php?eliminar_linea_dev_dep=true',
        type:  'post',
        dataType: 'json',
        success:  function (response) { 
         if(response==1)
         {
          Swal.fire('Devolucion eliminada','','success');
          lista_devolucion();
          // cargar_pedido();
         }
        }
      });

   }

  function generar_factura(numero)
   {
    var prove = $('#ddl_areas').val();
    $('#myModal_espera').modal('show');  
     var parametros = 
     {
      'num_fact':numero,
      'prove':prove,
      'iva_exist':0,
     }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/articulosC.php?generar_factura=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);

       $('#myModal_espera').modal('hide');  
       if(response.resp==1)
        {

          lista_devolucion();
          Swal.fire({
            type: 'success',
            title: 'Comprobante '+response.com+' generado.',
            confirmButtonText: 'OK!',
            allowOutsideClick: false,
          }).then((result) => {
            if (result.value) {
               location.href='../vista/farmacia.php?mod=Farmacia&acc=devoluciones_departamento&acc1=Devolucion%20por%20departamentos&b=1&po=sub'
            }
          })

          // Swal.fire('Comprobante '+response.com+' generado.','','success'); 
          // lista_devolucion();
          // cargar_pedido();
        }else if(response.resp==-2)
        {
          Swal.fire('Asegurese de tener una cuenta Cta_Iva_Inventario.','','info'); 
        }else if(response.resp==-3)
        {
          Swal.fire('','Esta factura Tiene dos  o mas fechas','info'); 
          lista_devolucion();
          // cargar_pedido();
        }
        else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
      }
    });
     // console.log(datos);
   }


    function autocoplet_pro(){
      $('#ddl_producto').select2({
        placeholder: 'Seleccione una producto',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?autocom_pro=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
              return {
                results: data
              };
          },
          cache: true
        }
      });
  }
  function cargar_detalles()
   {
     var id = $('#ddl_producto').val();
     console.log(id);
     var datos = id.split('_');
      $('#txt_codigo').val(datos[2]);
      $('#txt_stock').val(datos[9]);
      $('#txt_precio').val(datos[3]);
      $('#txt_cant').focus();

   }


  function autocoplet_area(){
      $('#ddl_areas').select2({
        placeholder: 'Seleccione una Area de descargo',
        ajax: {
          url:   '../controlador/farmacia/descargosC.php?areas=true',
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
         <div class="col-sm-6 text-right"><b>Devoluciones de insumos por departamento</b></div>         
         <div class="col-sm-6 text-right"> No. COMPROBANTE  <u id="num"></u></div>        
        </div>
      </div>
      <div class="panel-body" style="border: 1px solid #337ab7;">
        <div class="row">
          <div class="col-sm-7">
            <button id="" class="btn btn-primary" onclick="generar_factura('<?php echo $cod;?>')"><i class="icon fa fa-cogs"></i> Procesar devolucion</button>                       
          </div>
          <div class="col-sm-2">
            <b>No Orden</b>
             <input type="text" name="txt_orden" id="txt_orden" class="form-control input-sm" readonly>
          </div>             
           <div class="col-sm-3">
            <b>Area de devolucion:</b>
            <select class="form-control input-sm" id="ddl_areas" name="ddl_areas">
              <option value="">Seleccionar producto</option>
            </select>      
          </div>

          
        </div>
        <div class="row">
        	<div class="col-sm-2">
         	<b>Codigo</b>
           <input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-sm" readonly>
          </div>         
          <div class="col-sm-4"> 
          	<b>Productos</b>
            <select class="form-control input-sm" id="ddl_producto" name="ddl_producto" onchange="cargar_detalles()">
            	<option value="">Seleccionar producto</option>
            </select>      
          </div>
          <div class="col-sm-1">
         	<b>Cantidad</b>
           <input type="text" name="txt_cant" id="txt_cant" value="0" class="form-control input-sm" onblur="calcular()">
          </div>
          <div class="col-sm-1">
         	<b>Stock</b>
           <input type="text" name="txt_stok" id="txt_stok" value="0" class="form-control input-sm" readonly>
          </div>
          <div class="col-sm-1">
         	<b>Precio</b>
           <input type="text" name="txt_precio" id="txt_precio" value="0" class="form-control input-sm" readonly>
          </div>
          <div class="col-sm-1">
         	<b>Total</b>
           <input type="text" name="txt_total" id="txt_total" value="0" class="form-control input-sm" readonly>
          </div>
          <div class="col-sm-2">
          <b>Fecha</b>
           <input type="date" name="txt_fecha" id="txt_fecha" value="<?php echo date('Y-m-d'); ?>" class="form-control input-sm" readonly>
          </div>          
         
          <!-- <div class="col-sm-6">
            <b>Detalle:</b>
            <textarea class="form-control" id="detalle" readonly="" rows="2"></textarea>            
          </div>           -->
        </div>
        <div class="modal-footer">
        	 <button id="" class="btn btn-primary" onclick="guardar_devolucion()"><i class="icon fa  fa-arrow-down"></i> Agregar devolucion</button> 
        </div>
      </div>
  </div>
  <div class="row">
  	<input type="hidden" name="lineas" id="lineas">
  	<div class="col-sm-12 text-center" id="tbl_devoluciones">
          
    </div>  


  </div><br>  
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
          <button type="button" class="btn btn-primary" onclick="guardar_new_pro();">Guardar Todo</button>
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button>
        </div>
    </div>
  </div>
</div>