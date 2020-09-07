<?php  require_once("panel.php");?>
<script type="text/javascript">
  $(document).ready(function()
  {
  $('#imprimir_pdf').click(function(){
  var url = '../controlador/inventario/inventario_onlineC.php?reporte_pdf';                
  window.open(url, '_blank');
}); 
  $('#imprimir_excel').click(function(){
  var url = '../controlador/inventario/inventario_onlineC.php?reporte_excel';                
  window.open(url, '_blank');
}); 


   autocmpletar();
    autocmpletar_rubro();
    autocmpletar_cc();
    cargar_entrega();

      $('body').on('DOMNodeInserted', '.select2', function () {
               $('#'+this.id).select2({
                placeholder: 'Seleccione una producto',
                ajax: {
                url: '../controlador/inventario/inventario_onlineC.php?producto=true',
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
     });

     $('body').on('DOMNodeInserted', '.selectr', function () {
        
               $('#'+this.id).select2({
                placeholder: 'Seleccione rubro',
                ajax: {
                url: '../controlador/inventario/inventario_onlineC.php?rubro=true',
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
  }); 
       $('body').on('DOMNodeInserted', '.select_cc', function () {       
          
               $('#'+this.id).select2({
                 placeholder: 'Centro costo',
                 ajax: {
                 url: '../controlador/inventario/inventario_onlineC.php?cc=true',
                 dataType: 'json',
                 delay: 250,
                 processResults: function (data) {
                   return {
                     results: data
                     };
                 },
                 cache: true  
                 }       //      
            });
  }); 



  });


   function autocmpletar(id=''){
      $('#ddl_productos_'+id).select2({
        placeholder: 'Seleccione una producto',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?producto=true',
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
  function autocmpletar_rubro(id=''){
   
      $('#ddl_rubro_'+id).select2({
        placeholder: 'Seleccione rubro',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?rubro=true',
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
   function autocmpletar_cc(id=''){
   
       $('#ddl_cc_'+id).select2({
        placeholder: 'Centro costo',
        ajax: {
          url: '../controlador/inventario/inventario_onlineC.php?cc=true',
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

  function cargar_datos(ID='')
  {
    if(ID=='')
    {
     var selec = $('#ddl_productos_').val();
     console.log(selec);
     var cod = selec.split('/'); 
     $('#txt_codigo_').val(cod[0]);
     $('#txt_uni_').val(cod[1]);
    }else
    {
     var selec = $('#ddl_productos_'+ID).val();
     var cod = selec.split('/'); 
     $('#txt_codigo_'+ID).val(cod[0]);
     $('#txt_uni_'+ID).val(cod[1]);

    }
  }
  function cargar_entrega()
    {
      var lineas = '';
      
      $.ajax({
        // data:  {parametros:parametros},
        url:   '../controlador/inventario/inventario_onlineC.php?entrega=true',
        type:  'post',
        dataType: 'json',
        // beforeSend: function () { 
        //   $('#contenido_entrega').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
        // },
        success:  function (response) { 
          if(response)
           {
            $.each(response,function(i,item){
              console.log(item.Fecha_Fab.date);
                lineas +='<div class="row"><div class="col-sm-12"><div class="col-sm-1" style=" padding-left: 0px;  padding-right: 0px;"><b>Codigo</b><input type="hidden" id="txt_id_pro_'+i+'" value="'+item.CODIGO_INV+'"><input type="hidden" id="txt_pos_'+i+'" value="'+item.A_No+'"><input type="text" name="" id="txt_codigo_'+i+'" class="form-control input-sm" value="'+item.CODIGO_INV+'"></div><div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;"><b>Descripcion</b><select class="form-control select2" id="ddl_productos_'+i+'" name="ddl_productos_'+i+'" onchange="cargar_datos(\''+i+'\')" onfocus="cargar(\''+i+'\')"><option>Seleccione producto</option></select></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>UNI</b><input type="" value="'+item.UNIDAD+'" name="txt_uni_" id="txt_uni_'+i+'" class="form-control input-sm"></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>Cantidad</b><input value="'+item.CANT_ES+'" type="text" name="txt_cant_" id="txt_cant_'+i+'" placeholder="Cantidad" class="form-control input-sm"></div><div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;"><b>Centro de costos</b><br><select class="form-control select_cc" id="ddl_cc_'+i+'"  id="ddl_cc_'+i+'"><option>Centro de costos</option></select></div><div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;"><b>Rubro</b><br><select class="form-control selectr" id="ddl_rubro_'+i+'" name="ddl_rubro_'+i+'"><option>Rubro</option></select></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"> <b style="font-size: 13px;">Bajas o desperdicios</b><input type="" name="" placeholder="Bajas o desperdicios" id ="txt_bajas_'+i+'" class="form-control input-sm" value="'+item.Consumos+'"></div><div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;"><b>Observaciones</b><textarea placeholder="observacion" class="form-control" id="txt_obs_'+i+'">'+item.Procedencia+'</textarea></div><div class="col-sm-1"><br><button onclick="Guardar(\''+i+'\')" class="btn btn-primary" title="Guardar"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button><button onclick="eliminar(\''+i+'\')" class="btn btn-danger" title="Eliminar"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button><input type="text" readonly value="'+item.Fecha_Fab.date.substr(0,10)+'" class="form-control input-sm"/></div></div></div>'; 
            });            
            $('#contenido_entrega').html(lineas);

            $.each(response,function(i,item){
               $('#ddl_productos_'+i).append($('<option>',{value: item.CODIGO_INV, text: item.PRODUCTO,selected: true }));
               rubro_cod(item.CONTRA_CTA,i);
               cc_cod(item.CTA_INVENTARIO,i);
            });
                
           }
        }
      });
  }

  function cargar(id)
  {
    alert('ddd');

  }

  function Guardar(id='')
  {
    var parametros = 
    {
        'codigo':$('#txt_codigo_'+id).val(),
        'producto':$('select[name="ddl_productos_'+id+'"] option:selected').text(),
        'uni':$('#txt_uni_'+id).val(),
        'cant':$('#txt_cant_'+id).val(),
        'cc':$('#ddl_cc_'+id).val(),
        'rubro':$('#ddl_rubro_'+id).val(),
        'bajas':$('#txt_bajas_'+id).val(),
        'observacion':$('#txt_obs_'+id).val(),
        'id':id,
        'ante':$('#txt_id_pro_'+id).val(),
        'fecha':$('#txt_fecha').val(),
    };
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?guardar=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response ==null)
        {
          Swal.fire(
            '',
            'Operaciopn realizada con exito.',
            'success'
          )
         cargar_entrega();
        }else
        {
          Swal.fire(
            '',
            'Algo extraño a pasado.',
            'error'
          )

        }           
      }
    });

  }
  function eliminar(id)
  {
    Swal.fire({
  title: 'Quiere eliminar linea?',
  text: "Esta seguro d eliminar linea!",
  icon: 'warning',
  showCancelButton: true,
  confirmButtonColor: '#3085d6',
  cancelButtonColor: '#d33',
  confirmButtonText: 'Si'
}).then((result) => {
  if (result.value) {

    var parametros = 
    {
        'id':$('#txt_codigo_'+id).val(),        
        'id_':$('#txt_pos_'+id).val(),
    };
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?eliminar_linea=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
        if(response ==1)
        {
          Swal.fire(
            '',
            'Operaciopn realizada con exito.',
            'success'
          )         
         cargar_entrega(); 
        }else
        {
          Swal.fire(
            '',
            'Algo extraño a pasado.',
            'error'
          )

        }           
      }
    });
  }
})

  }

   function rubro_cod(id,pos)
  {
    $.ajax({
     // data:  {parametros:parametros},
      url:    '../controlador/inventario/inventario_onlineC.php?rubro=true&q='+id,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response)

             $('#ddl_rubro_'+pos).append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));
         
      }
    });
  }
function cc_cod(id,pos)
  {
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/inventario_onlineC.php?cc=true&q='+id,
      type:  'post',
      dataType: 'json',
        success:  function (response) { 
          console.log(response)
             $('#ddl_cc_'+pos).append($('<option>',{value: response[0].id, text:response[0].text,selected: true }));         
      }
    });
  }

</script>

   <div style="padding-left: 20px;padding-right: 20px">
    <div class="row">
      <div class="col-lg-3 col-sm-4 col-md-8 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="panel.php?sa=s" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
          <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>           
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <button title="Consultar Catalogo de cuentas"  class="btn btn-default">
            <img src="../../img/png/grabar.png" >
          </button>
        </div>
      </div>      
    </div>
    <div class="row">
      <div class="col-sm-12 text-center">
        <h1><b>ENTREGA DE MATERIAL</b></h1>
      </div>
    </div>
    
   
   
   <div class="row">
    <div class="panel panel-info">
       <div class="panel-heading">
         <div class="row">  
        <div class="col-sm-12">
               <div class="col-sm-1" style=" padding-left: 0px;  padding-right: 0px;">
                  <b>Codigo</b>
                  <input type="text" name="txt_codigo_" id="txt_codigo_" disabled="" class="form-control input-sm">
                </div>
                <div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;">
                   <b>Descripcion</b><br>
                  <select class="form-control" id="ddl_productos_" name="ddl_productos_" onchange="cargar_datos()">
                    <option>Seleccione producto</option>
                  </select>
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>UNI</b>
                  <input type="" name="txt_uni_" id="txt_uni_" disabled="" class="form-control input-sm">
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>Cantidad</b>
                  <input type="text" name="txt_cant_" id="txt_cant_" placeholder="Cantidad" class="form-control input-sm">
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>Centro de costos</b><br>
                 <select class="form-control" id="ddl_cc_">
                   <option>Centro de costos</option>
                 </select>
                </div>
                <div class="col-sm-2"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>Rubro</b><br>
                    <select class="form-control" id="ddl_rubro_" name="ddl_rubro_">
                      <option>Rubro</option>
                    </select>
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>Fecha</b><br>
                   <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm">
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b style="font-size: 13px;">Bajas o desperdicios</b>
                  <input type="" name="" id="txt_bajas_" placeholder="Bajas o desperdicios" class="form-control input-sm">
                </div>
                <div class="col-sm-1"  style=" padding-left: 2px;  padding-right: 0px;">
                  <b>Observaciones</b>
                  <textarea placeholder="observacion" class="form-control" id="txt_obs_"></textarea>
                </div>
                <div class="col-sm-1" id="campos">
                  <br>
                  <button class="btn btn-primary btn-sm" title="Agregar" onclick="Guardar()"><span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span></button>
                </div>  
           </div>
    </div>

       </div>
       <div class="panel-body">
          <div class="text-center" id="contenido_entrega"></div>

       </div>
     </div>
   </div>