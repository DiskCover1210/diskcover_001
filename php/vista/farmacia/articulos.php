<?php  require_once("panel.php"); $_SESSION['INGRESO']['modulo_']='99';?>

<!-- <style type="text/css">
  .form-control[disabled], .form-control[readonly], fieldset[disabled]
  {
    background-color: #c7c1c1;
    opacity: 1;
  }

</style>
 -->
<script type="text/javascript">
   $( document ).ready(function() {
    cargar_productos();
    autocoplet_fami_modal();
    autocoplet_fami();
    autocoplet_cta();
    autocoplet_pro();
    autocoplet_prov();
    autocoplet_prov_modal();

    autocoplet_cta_inv();
    autocoplet_cta_CV();
    autocoplet_cta_ventas();
    autocoplet_cta_vnt_0();
    autocoplet_cta_vnt_ant();
    num_comprobante();



           $("#subir_imagen").on('click', function() {
          if($('#file_img').val()=="")
          {
            Swal.fire(
                  '',
                  'Asegurese primero de colocar una imagen.',
                  'info')
            return false;
          }
        var formData = new FormData(document.getElementById("form_img"));
        var files = $('#file_img')[0].files[0];
        formData.append('file',files);
       // formData.append('curso',curso);
        $.ajax({
            url:   '../controlador/farmacia/articulosC.php?Articulos_imagen=true',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType:'json',
         // beforeSend: function () {
         //        $("#foto_alumno").attr('src',"../../img/gif/proce.gif");
         //     },
            success: function(response) {
               if(response==-1)
               {
                 Swal.fire(
                  '',
                  'Algo extraño a pasado intente mas tarde.',
                  'error')

               }else if(response ==-2)
               {
                  Swal.fire(
                  '',
                  'Asegurese que el archivo subido sea una imagen.',
                  'error')
               }  else
               {
                 Swal.fire(
                  '',
                  'Imagen Guardada.',
                  'success')
               } 
            }
        });
    });


  });
   function nombres(nombre)
  {
    $('#txt_nombre_prove').val(nombre.ucwords());
  }


   function autocoplet_fami(){
      $('#ddl_familia').select2({
        placeholder: 'Seleccione una familia',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?familias=true',
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

  function autocoplet_cta_inv(){
      $('#ddl_cta_inv').select2({
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='1'",
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

    function autocoplet_cta_CV(){
      $('#ddl_cta_CV').select2({
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='5','9'",
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

    function autocoplet_cta_ventas(){
      $('#ddl_cta_venta').select2({
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

    function autocoplet_cta_vnt_0(){
      $('#ddl_cta_ventas_0').select2({
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

    function autocoplet_cta_vnt_ant(){
      $('#ddl_cta_vnt_anti').select2({
        placeholder: 'Seleccione cuenta Inventario',
        ajax: {
          url:   "../controlador/farmacia/articulosC.php?cuenta_asignar='4'",
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

  
  function autocoplet_fami_modal(){
      $('#ddl_familia_modal').select2({
        placeholder: 'Seleccione una familia',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?familias=true',
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
  function autocoplet_cta(){
      $('#ddl_cta').select2({
        placeholder: 'Seleccione una familia',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?cuenta=true',
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
      function autocoplet_prov(){
      $('#ddl_proveedor').select2({
        placeholder: 'Seleccione una proveedor',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?proveedores=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            if(data != -1)
            {
              return {
                results: data
              };
            }else
            {
              Swal.fire('','Defina una cuenta "Cta_Proveedores" en Cta_Procesos.','info');
            }
          },
          cache: true
        }
      });
   
  }


   function autocoplet_prov_modal(){
      $('#ddl_proveedor_modal').select2({
        placeholder: 'Seleccione una proveedor',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?proveedores=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            if(data != -1)
            {
              return {
                results: data
              };
            }else
            {
              Swal.fire('','Defina una cuenta "Cta_Proveedores" en Cta_Procesos.','info');
            }
          },
          cache: true
        }
      });
  }



   function cargar_productos()
   {
    var query = $('#txt_query').val();
    var pag =$('#txt_pag').val();
    var parametros = 
    {
      'query':query,
      'pag':pag,  // numero de registeros que se van a visualizar
      'fun':'cargar_productos' // funcion que se va a a ejecutar en el paginando para recargar
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/farmacia/articulosC.php?productos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        if(response)
        {
          $('#tbl_ingresados').html(response.tabla);
          $('#tbl_pag').html(response.pag);
           $('#A_No').val(response.item);
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


   function guardar_producto()
   {
     var datos =  $("#form_nuevo_producto").serialize();
     if($('#ddl_cta_inv').val()==0 || $('#ddl_cta_CV').val()==0 || $('#ddl_cta_CV').val()==null || $('#ddl_cta_inv').val()==null)
     {
       Swal.fire('','Asegurese que la cuenta de inventario y la cuenta de costo de venta esten seleccionados.','info');   
      return false;
     }

     if($('#ddl_familia_modal').val()=='' || $('#txt_ref').val()=='' || $('#txt_nombre').val()=='' || $('#txt_max').val()=='' || $('#txt_min').val()=='' || $('#txt_reg_sanitario').val()=='')
     {
       Swal.fire('','Llene todo lso campos.','info');   
      return false;
     }

     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?producto_nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        // console.log(response);
        
          $('#Nuevo_producto').modal('hide');
        if(response==1)
        {
          Swal.fire('','Nuevo producto rwegistrado.','success'); 
          limpiar_nuevo_producto();         
        }
      }
    });
     // console.log(datos);
   }


   function generar_factura(numero,prove)
   {
    $('#myModal_espera').modal('show');  
     var fac = $('#txt_num_fac').val();
     var iva = $('#iva_'+numero).val();
     var parametros = 
     {
      'num_fact':numero,
      'prove':prove,
      'iva_exist':iva,
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
          Swal.fire('','Comprobante '+response.com+' generado.','success'); 
          cargar_productos();
        }else if(response.resp==-2)
        {
          Swal.fire('Asegurese de tener una cuenta Cta_Iva_Inventario.','','info'); 
        }else if(response.resp==-3)
        {
          Swal.fire('','Esta factura Tiene dos  o mas fechas','info'); 
          cargar_productos();
        }
        else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
      }
    });
     // console.log(datos);
   }

  function guardar_proveedor()
   {
     var datos =  $("#form_nuevo_proveedor").serialize();
     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?proveedor_nuevo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {

          $('#Nuevo_proveedor').modal('hide');
          Swal.fire('','Proveedores Guardo.','success');            
        }
      }
    });

     // console.log(datos);
   }


   function cargar_detalles()
   {
     var id = $('#ddl_producto').val();
     console.log(id);
     var datos = id.split('_');
      $('#ddl_familia').append($('<option>',{value: datos[1], text:datos[0],selected: true }));
      $('#txt_referencia').val(datos[2]);
      $('#txt_existencias').val(datos[9]);
      $('#txt_ubicacion').val(datos[7]);
      $('#txt_precio_ref').val(datos[3]);
      if(datos[8]==0)
      {
        $('#rbl_no').prop('checked',true);
      }else
      {        
        $('#rbl_si').prop('checked',true);
      }
      $('#txt_reg_sani').val(datos[10]);
      $('#txt_max_in').val(datos[11]);
      $('#txt_min_in').val(datos[12]);
          
     // console.log(datos);
   }

   function limpiar()
   {
      $("#ddl_familia").empty();
      $("#ddl_descripcion").empty();
      $("#ddl_pro").empty();
   }

   function familia_modal()
   {
     var cta = $('#ddl_familia_modal').val();
     var parte = cta.split('-');
     buscar_ultimo(parte[0]);
     // $('#txt_ref').val(cta);
     console.log(cta);
   }

   function buscar_ultimo(cta)
   {
     $.ajax({
      data:  {cta:cta},
      url:   '../controlador/farmacia/articulosC.php?buscar_ultimo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        $('#txt_ref').val(response);
      }
    });

   }

   function agregar()
   {
    if($('#ddl_producto').val()=='' || $('#ddl_proveedor').val()=='' || $('#ddl_familia').val()=='' || $('#txt_precio').val()=='' || $('#txt_canti').val()=='' || $('#txt_serie').val()=='' || $('#txt_num_fac').val()=='' || $('#txt_fecha_ela').val()=='' || $('#txt_fecha_exp').val()=='' || $('#txt_reg_sani').val()=='' || $('#txt_procedencia').val()=='' || $('#txt_lote').val()==''|| $('#txt_descto').val()=='' )
    {
      Swal.fire('','Llene todo los campos.','info');   
      return false;
    }
     var datos =  $("#form_add_producto").serialize();
     $.ajax({
      data:  datos,
      url:   '../controlador/farmacia/articulosC.php?add_producto=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response==1)
        {
          // $('#Nuevo_proveedor').modal('hide');
          Swal.fire('','Producto agregado.','success');   
          $('#txt_descto').val(0)
          cargar_productos();         
        }
      }
    });
   }

   function eliminar_lin(linea,orden,pro)
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
            var parametros=
            {
              'lin':linea,
              'ord':orden,
              'pro':pro,
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/articulosC.php?lin_eli=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_productos();
                }
              }
            });
        }
      });

   }

   function calculos()
   {
     let cant = parseFloat($('#txt_canti').val());
     let pre = parseFloat($('#txt_precio').val());
     let des = parseFloat($('#txt_descto').val());
     if($('#rbl_si').prop('checked'))
     {
       let subtotal = pre*cant;
       let dscto = (subtotal*des)/100;
       let total = (subtotal-dscto)*1.12;

       let iva = parseFloat($('#txt_iva').val()); 
       $('#txt_subtotal').val(subtotal-dscto);
       $('#txt_total').val(total);
       $('#txt_iva').val(total-(subtotal-dscto));

     }else
     {
      $('#txt_iva').val(0);
       let iva = parseFloat($('#txt_iva').val());       
       let sub = (pre*cant);
       let dscto = (sub*des)/100;

       let total = (sub-dscto);
       $('#txt_subtotal').val(sub-dscto);
       $('#txt_total').val(total);
     }
   }

   function limpiar_cta(cta)
   {
     $('#'+cta).empty();
   }

   function limpiar_nuevo_producto()
   {
     $('#ddl_cta_inv').empty();
     $('#ddl_cta_CV').empty();
     $('#ddl_cta_venta').empty();
     $('#ddl_cta_ventas_0').empty();
     $('#ddl_cta_vnt_anti').empty();
     $('#ddl_familia_modal').empty();
     $('#txt_ref').val('');
     $('#txt_nombre').val('');
     $('#txt_max').val('');
     $('#txt_min').val('');
     $('#txt_reg_sanitario').val('');
     $('#txt_cod_barras').val('');
   }
   function eliminar_todo(fac,prov)
   {
      Swal.fire({
      title: 'Quiere eliminar  toda la factura ingresada?',
      text: "Esta seguro de eliminar esta factura!",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
        if (result.value) {
            var parametros=
            {
              'orden':fac,
              'pro':prov
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/articulosC.php?eliminar_ingreso=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  cargar_productos();
                }
              }
            });
        }
      });
   }

   function subir($orden,$prov)
   {
     $('#txt_nom_img').val($orden+'_'+$prov);
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
     <div class="panel panel-primary">
      <div class="panel-heading">
        <div class="row">
         <div class="col-sm-6 text-right"><b>INGRESAR ARTICULOS</b></div>         
         <div class="col-sm-6 text-right"> No. COMPROBANTE  <u id="num"></u></div>        
        </div>
      </div>
      <div class="panel-body">
        <form id="form_add_producto">
        <div class="row">
           <div class="col-md-2">
              <b>Referencia:</b>
              <input type="text" name="txt_referencia" id="txt_referencia" class="form-control input-sm" readonly="">
           </div>
           <div class="col-sm-5">
              <b>Producto:</b>
              <select class="form-control input-sm" id="ddl_producto" name="ddl_producto" onchange="cargar_detalles()">
                <option value="">Seleccione una producto</option>
              </select>
           </div>
           <div class="col-sm-2">
              <b>Fecha:</b>
              <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" onblur="num_comprobante()">
           </div>
           <div class=" col-sm-3">
              <b>Familia:</b>
                <select class="form-control input-sm" id="ddl_familia" name="ddl_familia" disabled="">
                  <option>Seleccione una familia</option>
                </select>     
           </div>
        </div>
        <div class="row">
            <div class="col-sm-5">
              <b>Proveedor:</b>
              <div class="input-group"> 
                  <select class="form-control input-sm" id="ddl_proveedor" name="ddl_proveedor">
                     <option value="">Seleccione un proveedor</option>
                  </select>             
                   <span class="input-group-addon bg-green" title="Buscar" data-toggle="modal" data-target="#Nuevo_proveedor"><i class="fa fa-plus"></i></span>
              </div>
            </div>
            <div class="col-sm-3">
               <b>Ubicacion</b>
               <input type="text" name="txt_ubicacion" id="txt_ubicacion" class="form-control input-sm" readonly="">
            </div>
            <div class="col-sm-1">
              <b>Serie</b>
              <input type="text" name="txt_serie" id="txt_serie" class="form-control input-sm" onkeyup="num_caracteres('txt_serie',6)">            
            </div>
            <div class="col-sm-2">
              <b>Numero de factura</b>
              <input type="text" name="txt_num_fac" id="txt_num_fac" class="form-control input-sm">            
            </div>
            <div class="col-sm-1">
              <b>Lleva iva</b><br>
              <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_no" checked="" onchange="calculos()"> No</label>
              <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_si" onchange="calculos()"> Si</label>            
            </div>                   
        </div>
        <div class="row">
            <div class="col-sm-2">
               <b>Existencias</b>
                  <input type="text" name="txt_existencias" id="txt_existencias" class="form-control input-sm" readonly="">
            </div>
            <div class="col-sm-2">
               <b>Fecha Elab</b>
                  <input type="date" name="txt_fecha_ela" id="txt_fecha_ela" class="form-control input-sm" >
            </div>
            <div class="col-sm-2">
               <b>Fecha Exp</b>
                  <input type="date" name="txt_fecha_exp" id="txt_fecha_exp" class="form-control input-sm" >
            </div>
            <div class="col-sm-2">
               <b>Reg. Sanitario</b>
                  <input type="text" name="txt_reg_sani" id="txt_reg_sani" class="form-control input-sm" readonly="" value=".">
            </div>
            <div class="col-sm-2">
               <b>Procedencia</b>
                  <input type="text" name="txt_procedencia" id="txt_procedencia" class="form-control input-sm">
            </div>
            <div class="col-sm-2">
               <b>Lote</b>
                  <input type="text" name="txt_lote" id="txt_lote" class="form-control input-sm">
            </div>              
        </div>
        <div class="row">
          <div class="col-sm-1">
               <b>Max</b>
                  <input type="text" name="txt_max_in" id="txt_max_in" class="form-control input-sm" readonly="">
            </div>
            <div class="col-sm-1">
               <b>Min</b>
                  <input type="text" name="txt_min_in" id="txt_min_in" class="form-control input-sm" readonly="">
            </div>
          <div class="col-sm-1">
               <b>Cantidad</b>
                  <input type="text" name="txt_canti" id="txt_canti" class="form-control input-sm"  value="1" onblur="calculos()">
            </div>
            <div class="col-sm-1">
               <b>Precio</b>
                  <input type="text" name="txt_precio" id="txt_precio" class="form-control input-sm"  value="0" onblur="calculos()">
            </div>
            <div class="col-sm-1">
               <b>Precio Ref</b>
                  <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control input-sm"  value="0" readonly="">
            </div>
            <div class="col-sm-1">
               <b>% descto</b>
                  <input type="text" name="txt_descto" id="txt_descto" class="form-control input-sm"  value="0" onblur="calculos()">
            </div>             
            <div class="col-sm-1">
               <b>Subtotal</b>
                  <input type="text" name="txt_subtotal" id="txt_subtotal" class="form-control input-sm" readonly="" value="0">
            </div>
            <div class="col-sm-1">
               <b>Iva</b>
                  <input type="text" name="txt_iva" id="txt_iva" class="form-control input-sm" readonly="" value="0">
            </div>  
            <div class="col-sm-1">
               <b>Total</b>
                  <input type="text" name="txt_total" id="txt_total" class="form-control input-sm" readonly="" value="0">
            </div>          
        </div>
        <div class="row">
          <div class="col-sm-8">
            
          </div>
          <div class="col-sm-4 text-right" style="padding-left: 0px"><br>
               <button type="button" class="btn btn-info" onclick="agregar()"><i class="fa fa-plus"></i> Agregar a ingreso</button>                
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#Nuevo_producto"><i class="fa fa-plus"></i> Nuevo articulo</button>
                <button type="button" class="btn btn-primary" onclick="limpiar()"><i class="fa fa-paint-brush"></i> Limpiar</button>
            </div>
        </div>
        <input type="hidden" id="A_No" name ="A_No" value="0">
        </form>       
      </div>
      </div>
  </div>
  <div class="row">
    <input type="hidden" name="" id="txt_pag" value="0">
    <div id="tbl_pag"></div>    
  </div>
  <div class="row" id="tbl_ingresados">

  </div>
</div>

<!-- Modal -->
<div id="Nuevo_producto" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content modal-md">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo producto</h4>
      </div>
      <div class="modal-body">
        <form id="form_nuevo_producto">
        <div class="row">
          <div class="col-sm-5">
            <b>Familiar</b>
              <select id="ddl_familia_modal" name="ddl_familia_modal" class="form-control input-sm" onchange="familia_modal()">
                 <option value="">seleccione familia</option>
              </select>
          </div>
          <div class="col-sm-6" style="padding-right: 0px; ">
              <b>Cuenta Inv</b> 
              <select class="form-control" name="ddl_cta_inv" id="ddl_cta_inv">
                <option value="0">Seleccione Cuenta</option>
              </select>                
            </div>
            <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px: ">
              <br>
               <span class="input-group-addon bg-default" title="limpiar" onclick="limpiar_cta('ddl_cta_inv')"><i class="fa fa-close"></i></span>              
            </div>      
        </div>
        <div class="row">
          <div class="col-sm-8">
             <b>Nombre de producto</b>
            <input type="text" id="txt_nombre" name="txt_nombre" class="form-control input-sm">              
          </div>
          <div class="col-sm-4">
            <b>Referencia</b>
            <input type="text" name="txt_ref" id="txt_ref" class="form-control input-sm" readonly="">              
          </div>
        </div>
        <div class="row">        
          <div class="col-sm-2">
             <b>Max</b>
            <input type="text" id="txt_max" name="txt_max" class="form-control input-sm">              
          </div>
          <div class="col-sm-2">
             <b>Min</b>
            <input type="text" id="txt_min" name="txt_min" class="form-control input-sm">              
          </div> 
          <div class="col-sm-2">
            <b>Unid Med.</b>
            <input type="text" id="txt_uni" name="txt_uni" class="form-control input-sm">              
          </div>
          <div class="col-sm-3">
             <b>Cod Barras</b>
            <input type="text" name="txt_cod_barras" class="form-control input-sm">              
          </div>
          <div class="col-sm-3">
             <b>Reg. Sanitario</b>
            <input type="text" name="txt_reg_sanitario" id="txt_reg_sanitario" class="form-control input-sm">              
          </div>              
        </div>
        <div class="row">
            <div class="col-sm-5" style="padding-right: 0px;" >
              <b>Cuenta Costo venta</b>
              <select class="form-control input-sm" name="ddl_cta_CV" id="ddl_cta_CV">
                 <option value="0">Seleccione Cuenta</option>
               </select>             
            </div>
            <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px: ">
              <br>
               <span class="input-group-addon bg-default" title="limpiar" onclick="limpiar_cta('ddl_cta_CV')"><i class="fa fa-close"></i></span>              
            </div>  
            <div class="col-sm-5" style="padding-right: 0px;" >           
               <b>Cuenta Ventas</b>
                <select class="form-control input-sm" name="ddl_cta_venta" id="ddl_cta_venta">
                  <option value="0">Seleccione Cuenta</option>
                </select> 
            </div>
            <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px: ">
                <br>
                 <span class="input-group-addon bg-default" title="limpiar" onclick="limpiar_cta('ddl_cta_venta')"><i class="fa fa-close"></i></span>              
            </div>         
        </div>
        <div class="row">
          <div class="col-sm-5" style="padding-right: 0px;" >
            <b>Cuenta Ventas 0</b>
            <select class="form-control input-sm" name="ddl_cta_ventas_0" id="ddl_cta_ventas_0">
              <option value="0">Seleccione Cuenta</option>
            </select>  
          </div>
          <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px: ">
              <br>
               <span class="input-group-addon bg-default" title="limpiar" onclick="limpiar_cta('ddl_cta_ventas_0')"><i class="fa fa-close"></i></span>              
          </div>  
          <div class="col-sm-5" style="padding-right: 0px;" >
            <b>Cuenta Ventas Anti</b>    
            <select class="form-control input-sm" name="ddl_cta_vnt_anti" id="ddl_cta_vnt_anti">
              <option value="0">Seleccione Cuenta</option>
            </select> 
          </div>
          <div class="col-sm-1" style="padding-left: 0px;padding-right: 0px: ">
              <br>
               <span class="input-group-addon bg-default" title="limpiar" onclick="limpiar_cta('ddl_cta_vnt_anti')"><i class="fa fa-close"></i></span>              
            </div>
        </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="guardar_producto()">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<div id="Nuevo_proveedor" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo proveedor</h4>
      </div>
      <div class="modal-body">
        <form id="form_nuevo_proveedor">
        <div class="row">
          <div class="col-sm-8">
            <b>Nombre de proveedor</b>
            <input type="text" id="txt_nombre_prove" name="txt_nombre_prove" class="form-control input-sm" onblur="nombres(this.value)">  
          </div> 
          <div class="col-sm-4">
            <b>CI / RUC</b>
            <input type="txt_ruc" name="txt_ruc" class="form-control input-sm">              
          </div>           
        </div>
        <div class="row">
          <div class="col-sm-12">
            <b>Direccion</b>
            <input type="txt_direccion" name="txt_direccion" class="form-control input-sm">  
          </div>        
        </div>
        <div class="row">
          <div class="col-sm-8">
            <b>Email</b>
            <input type="txt_email" name="txt_email" class="form-control input-sm">  
          </div> 
          <div class="col-sm-4">
            <b>Telefono</b>
            <input type="txt_telefono" name="txt_telefono" class="form-control input-sm">              
          </div> 
        </div>
      </div>
      </form>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="guardar_proveedor()">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div id="modal_de_foto" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cargar imagen de factura</h4>
      </div>
      <div class="modal-body">
        <div class="col-sm-12">
          <p>asegurese de que su archivo sea .jpg .png .pdf</p>
             <!-- <img src="../img/de_sistema/sin_imagen.jpg" style="width: 80%;" id="img" name="img"> -->
          <form enctype="multipart/form-data" id="form_img" method="post">
              <div class="custom-file">
                  <input type="file" class="btn" id="file_img" name="file_img">
                  <input type="hidden" name="txt_nom_img" id="txt_nom_img" value="">
              </div>
            <button type="button" class="btn btn-primary btn-sm" style="width: 100%" id="subir_imagen"> Subir Imagen</button>
          </form>  
          <br> 
        </div>
      <div class="modal-footer">
        <!-- <button type="button" class="btn btn-primary" onclick="guardar_proveedor()">Guardar</button> -->
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
