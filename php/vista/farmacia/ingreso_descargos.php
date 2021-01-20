<?php  require_once("panel.php"); $num_ped = '';$cod='';if(isset($_GET['num_ped'])){$num_ped =$_GET['num_ped'];}
if(isset($_GET['cod'])){$cod =$_GET['cod'];} $_SESSION['INGRESO']['modulo_']='99';?>
<script type="text/javascript">
   $( document ).ready(function() {
    autocoplet_paci();
    autocoplet_ref();
    autocoplet_desc();
    autocoplet_cc();
     // buscar_cod();
    var c = '<?php echo $cod; ?>';
    if(c!='')
    {
      buscar_codi();
    }
    cargar_pedido();

  });



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

  function autocoplet_cc(){
      $('#ddl_cc').select2({
        placeholder: 'Seleccione centro de costos',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?cc=true',
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
    function autocoplet_ref(){
      $('#ddl_referencia').select2({
        placeholder: 'Escriba Referencia',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=ref',
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
   function autocoplet_desc(){
      $('#ddl_descripcion').select2({
        placeholder: 'Escriba Descripcion',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=desc',
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
        console.log(response);
       
           $('#txt_codigo').val(response[0].Matricula);
           $('#txt_nombre').val(response[0].Cliente);
           $('#ddl_paciente').append($('<option>',{value: response[0].CI_RUC, text:response[0].Cliente,selected: true }));
           $('#txt_ruc').val(response[0].CI_RUC);
      }
    });
  }

  function producto_seleccionado(tipo)
  {
    if(tipo=='R')
    {
      var val = $('#ddl_referencia').val();
      var partes = val.split('-');
        $('#ddl_descripcion').append($('<option>',{value: partes[0]+'-'+partes[1]+'-'+partes[2]+'-'+partes[3]+'-'+partes[4]+'-'+partes[5]+'-'+partes[6], text:partes[2],selected: true }));
        // console.log(partes[0]+'-'+partes[1]+'-'+partes[2]+'-'+partes[3]+'-'+partes[4]+'-'+partes[5]+'-'+partes[6]);
        $('#txt_precio').val(partes[1]); 
        $('#txt_iva').val(partes[6]);  
    }else
    {
      var val = $('#ddl_descripcion').val();
      var partes = val.split('-');
        $('#ddl_referencia').append($('<option>',{value: partes[0]+'-'+partes[1]+'-'+partes[2]+'-'+partes[3]+'-'+partes[4]+'-'+partes[5], text:partes[0],selected: true }));

        // console.log(partes[0]+'-'+partes[1]+'-'+partes[2]+'-'+partes[3]+'-'+partes[4]+'-'+partes[5]);
        $('#txt_precio').val(partes[1]); 
        $('#txt_iva').val(partes[5]);  
    }

  }



  function Guardar()
  {
   var producto = $('#ddl_descripcion').val();
   var cc = $('#ddl_cc').val();
   var cc1 = cc.split('-');
   var ruc = $('#txt_ruc').val();
   var cc = $('#ddl_cc').val();
    if(producto !='' && ruc!='' && cc!='')
    {
      if($('#txt_cant').val()<=0)
      {
        Swal.fire('','La cantidad Debe ser mayor que 0.','info');
        $('#txt_cant').val('1');
        return false;
      }
      var prod = producto.split('-');
    // console.log(producto);
       var parametros = 
       {
           'codigo':prod[0],
           'producto':prod[2],
           'cta_pro':prod[3],
           'uni':'',
           'cant':$('#txt_cant').val(),
           'cc':cc1[0],
           'rubro':'',
           'bajas':'',
           'observacion':'',
           'id':$('#txt_num_item').val(),
           'ante':'',
           'fecha':$('#txt_fecha').val(),
           'bajas_por':'',
           'TC':prod[4],
           'valor':$('#txt_precio').val(),
           'total':$('#txt_importe').val(),
           'num_ped':$('#txt_pedido').val(),
           'ci':$('#txt_ruc').val(),
           'descuento':$('#txt_descuento').val(),
           'iva':prod[6]
       };
       $.ajax({
         data:  {parametros:parametros},
         url:   '../controlador/farmacia/ingreso_descargosC.php?guardar=true',
         type:  'post',
         dataType: 'json',
           success:  function (response) { 
           if(response.resp==null)
           {
            $('#txt_pedido').val(response.ped);
            Swal.fire('','Agregado a pedido.','success');
            limpiar();
            cargar_pedido();
            // location.reload();
           }else
           {
            Swal.fire('','Algo extraño a pasado.','error');
           }           
         }
       });
    }else
    {
       Swal.fire('','Producto,Centro de costos ó Cliente no seleccionado.','error');
    }
  }



  function cargar_pedido()
  {
    var p = $('#txt_pedido').val();
    var parametros=
    {
      'num_ped':p,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?pedido=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        console.log(response);
        if(response.num_lin !=0 && p!='')
        { 
           var c = '<?php echo $num_ped; ?>';
          if(c!='')
          {        
           $('#txt_num_lin').val(response.num_lin);
           $('#txt_num_item').val(response.item);
           $('#txt_ruc').val(response.ruc+'001');
           $('#ddl_paciente').append($('<option>',{value: response.ruc+'001', text:'',selected: true }));
          $('#tbl_body').html(response.tabla);
          }else
          {
            var num_p = $('#txt_pedido').val();
            var cod = $('#txt_codigo').val();
              var url="../vista/farmacia.php?mod=Farmacia&acc=ingresar_descargos&acc1=Ingresar%20Descargos&b=1&po=subcu&num_ped="+num_p+"&cod="+cod+"#";
            $(location).attr('href',url);
          }
        }
          $('#tbl_body').html(response.tabla);
          var c = '<?php echo $num_ped; ?>';
          if(c!='')
            {
              buscar_cod();
            }
         // buscar_cod();
         descuentos();
      }
    });
  }

  function editar_lin(num)
  {
    var parametros=
    {
      'can':$('#txt_can_lin_'+num).val(),
      'pre':$('#txt_uti_lin_'+num).val(),
      'des':$('#txt_des_lin_'+num).val(),
      'tot':$('#txt_tot_lin_'+num).val(),
      'lin':num,
      'ped':$('#txt_pedido').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/ingreso_descargosC.php?lin_edi=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response==1)
        {
          Swal.fire('','Linea de pedido Editado.','success');         
          cargar_pedido();
        }
      }
    });
  }
  function eliminar_lin(num)
  {
    var ruc = $('#txt_ruc').val();
    var cli = $('#ddl_paciente').text();
    console.log(cli);
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
              'lin':num,
              'ped':$('#txt_pedido').val(),
            }
             $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/farmacia/ingreso_descargosC.php?lin_eli=true',
              type:  'post',
              dataType: 'json',
              success:  function (response) { 
                if(response==1)
                {
                  $('#txt_ruc').val(ruc);
                  $('#ddl_paciente').append($('<option>',{value: ruc, text:'',selected: true }));
                  cargar_pedido();
                }
              }
            });
        }
      });
  }

  function calcular_totales(num=false)
  {
    if(num)
    {
      var cant = $('#txt_can_lin_'+num).val();
      var pre = $('#txt_pre_lin_'+num).val();
      var uti = parseFloat($('#txt_uti_lin_'+num).val());


      var sin_des = (cant*pre);
      var des = $('#txt_des_lin_'+num).val();
      var val_des = (sin_des*des)/100;
      var impo = parseFloat(sin_des-val_des);
      var iva = parseFloat($('#txt_iva_lin_'+num).val());
      var tot = $('#txt_tot_lin_'+num).val(impo.toFixed(4));

      // console.log(tot);
       if(iva!=0 && uti!=0)
       {
         var sin_des = (cant*uti);
         var des = $('#txt_des_lin_'+num).val();
         var val_des = (sin_des*des)/100;
         var impo = parseFloat(sin_des-val_des);
         var tot_iva = ((impo*1.12)-impo);
         // console.log(tot_iva);
          $('#txt_iva_lin_'+num).val(parseFloat(tot_iva));
          $('#txt_tot_lin_'+num).val(impo.toFixed(4));
       }else if(uti!=0 && iva==0)
       {
         var sin_des = (cant*uti);
         var des = $('#txt_des_lin_'+num).val();
         var val_des = (sin_des*des)/100;
         var impo = parseFloat(sin_des-val_des);
         var tot_iva = ((impo*1.12)-impo);
         // console.log(tot_iva);
          // $('#txt_iva_lin_'+num).val(parseFloat(tot_iva));
          $('#txt_tot_lin_'+num).val(impo.toFixed(4));

       }

    }else
    {
      // console.log('entr');
      var cant = $('#txt_cant').val();
      var pre = $('#txt_precio').val();
      var sin_des = (cant*pre);
      var des = $('#txt_descuento').val();
      var val_des = (sin_des*des)/100;
      var tot = $('#txt_importe').val((sin_des-val_des).toFixed(2));

    }

      // descuentos();
  }

  function descuentos()
  {
    var num = $('#txt_num_lin').val();
    var item = $('#txt_num_item').val();
    var op = $('input:radio[name=rbl_des]:checked').val();

      // console.log(op);
    if(op=='L')
    {
       $('#txt_tot_des').val(0);
       var tot = 0;
       var sub = 0;
       var iva = 0;
      for (var i = 0; i <=item ; i++) {
            $('#txt_des_lin_'+i).attr("readonly", false);
            calcular_totales(i);
            if($('#txt_tot_lin_'+i).length)
            {
              var des = parseFloat($('#txt_des_lin_'+i).val());          
              pre = parseFloat($('#txt_pre_lin_'+i).val());
              can = parseFloat($('#txt_can_lin_'+i).val());
              uti = parseFloat($('#txt_uti_lin_'+i).val());
              sub+= parseFloat($('#txt_tot_lin_'+i).val());
              iva+=parseFloat($('#txt_iva_lin_'+i).val());
              tot+=((((pre*can)+uti)*des)/100);
            }
             $('#txt_tot_des').val(tot.toFixed(2))
             $('#txt_sub_tot').val(sub.toFixed(2));
             $('#txt_tot_iva').val(iva.toFixed(2));
      }

    }else if(op=='TL')
    {
      var des =$('#txt_des').val();
      var tot = 0;
      var sub = 0;
      var iva = 0;
      for (var i = 0; i <=item ; i++) {
            // console.log(i);
           
            if($('#txt_tot_lin_'+i).length)
            {
              $('#txt_des_lin_'+i).val(des);            
              $('#txt_des_lin_'+i).attr("readonly", true);
              calcular_totales(i);
              pre = parseFloat($('#txt_pre_lin_'+i).val());
              can = parseFloat($('#txt_can_lin_'+i).val());
              uti = parseFloat($('#txt_uti_lin_'+i).val());
              sub+= parseFloat($('#txt_tot_lin_'+i).val());
              iva+= parseFloat($('#txt_iva_lin_'+i).val());
              
              if(uti!=0)
              {
                tot+=(((can*uti)*des)/100);
              }else
              {
                tot+=(((can*pre)*des)/100);
              }
            }
      }
      $('#txt_tot_des').val(tot.toFixed(2))
      $('#txt_sub_tot').val(sub.toFixed(2));
      $('#txt_tot_iva').val(iva.toFixed(2));
    }else
    {
      var tot = 0;
      var des = parseFloat($('#txt_des').val());
      var iva = 0;
      for (var i = 0; i <=item ; i++) {
            // console.log(i);
            $('#txt_des_lin_'+i).attr("readonly", true);
            calcular_totales(i);
            if($('#txt_tot_lin_'+i).length)
            {
              
            // $('#txt_des_lin_'+i).val(0);
              tot = parseFloat($('#txt_tot_lin_'+i).val())+tot;
              iva+=parseFloat($('#txt_iva_lin_'+i).val());
            }
      }

      // console.log(iva);
      $('#txt_sub_tot').val(tot.toFixed(2));
      var des_t = ((tot*des)/100);
      $('#txt_tot_des').val(des_t.toFixed(2));
      $('#txt_tot_iva').val(iva.toFixed(2));


    }
    var sub = parseFloat($('#txt_sub_tot').val());
    var des = parseFloat($('#txt_tot_des').val());
    var iva = parseFloat($('#txt_tot_iva').val());
    $('#txt_pre_tot').val(((sub-des)+iva).toFixed(2));
  }

  function validar_pvp_costo(i)
  {
    var costo = $('#txt_pre_lin_'+i).val();
    var pvp = $('#txt_uti_lin_'+i).val();
    console.log(costo);
    console.log(pvp);
    if(parseFloat(pvp)< parseFloat(costo))
    {
      Swal.fire('','Precio de PVP debe ser mayor al costo.','error'); 
      $('#txt_uti_lin_'+i).focus();
      $('#txt_uti_lin_'+i).val(parseFloat(costo)+0.01);           
    }

  }

  function limpiar()
  {
    $('#txt_precio').val(0);
    $('#txt_cant').val(0);
    $('#txt_descuento').val(0);
    $('#txt_importe').val(0);
    $('#txt_precio').val(0);
    // $('#txt_precio').val(0);
    $("#ddl_referencia").empty();
    $("#ddl_descripcion").empty();
    $("#txt_iva").val(0);
  }

  function generar_factura()
  {

    $('#myModal_espera').modal('show');    
    var orden = $('#txt_pedido').val();
    var ruc= $('#txt_ruc').val();
     $.ajax({
      data:  {orden:orden,ruc:ruc},
      url:   '../controlador/farmacia/ingreso_descargosC.php?facturar=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) {    
      $('#myModal_espera').modal('hide');    
        if(response.resp==1)
        {
          Swal.fire('','Comprobante '+response.com+' generado.','success'); 
        }else
        {
          Swal.fire('','No se pudo generado.','info'); 
        }
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
      <div class="panel-heading text-center"><b>Nuevo Pedido</b></div>
      <div class="panel-body" style="border: 1px solid #337ab7;">
        <div class="row">
          <div class="col-sm-6"> 
            <b>Codigo Cliente:</b>
            <input type="text" name="txt_codigo" id="txt_codigo" class="form-control input-sm" readonly="">            
            <b>Nombre:</b>
            <!-- <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-sm"> -->
            <select class="form-control input-sm" id="ddl_paciente" onchange="buscar_cod()">
              <option value="">Seleccione paciente</option>
            </select>      
          </div>
          <div class="col-sm-6">
            <b>RUC:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-sm">  
             <b>Fecha:</b>
            <input type="date" name="txt_fecha" id="txt_fecha" class="form-control input-sm" value="<?php echo date('Y-m-d')  ?>">                
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
          <div class="col-sm-6">    
          <b>Numero de pedido</b>
          <input type="text" name="" id="txt_pedido" readonly="" class="form-control input-sm" value="<?php echo $num_ped;?>">     
          </div>          
        </div>
        <div class="row">
          <div class="col-sm-4"> 
            <b>Cod Producto:</b>
            <select class="form-control input-sm" id="ddl_referencia" onchange="producto_seleccionado('R')">
              <option value="">Escriba referencia</option>
            </select>           
          </div>
          <div class="col-sm-8"> 
                <b>Descripcion:</b>
                <select class="form-control input-sm" id="ddl_descripcion" onchange="producto_seleccionado('D')">
                  <option value="">Escriba descripcion</option>
                </select>          
              </div>           
        </div>
        <div class="row">
               <div class="col-sm-4"> 
                  
              </div>
              <div class="col-sm-2"> 
                <b>Costo:</b>
                <input type="text" name="txt_precio" id="txt_precio" class="form-control input-sm" value="0" onblur="calcular_totales();" readonly="">            
              </div>   
              <div class="col-sm-1"> 
                <b>Cantidad:</b>
                <input type="text" name="txt_cant" id="txt_cant" class="form-control input-sm" value="0" onblur="calcular_totales();">            
              </div>   
              <div class="col-sm-2"> 
                <b>Dscto:</b>
                <input type="text" name="txt_descuento" id="txt_descuento" class="form-control input-sm" value="0" onblur="calcular_totales();">            
              </div>   
              <div class="col-sm-1"> 
                <b>Importe:</b>
                <input type="text" name="txt_importe" id="txt_importe" class="form-control input-sm" readonly="">
                <input type="hidden" name="txt_iva" id="txt_iva" class="form-control input-sm" readonly="">            
              </div> 
              <div class="col-sm-1"><br>
                <button class="btn btn-primary" onclick="Guardar()"><i class="fa fa-arrow-down"></i> Agregar</button>
              </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="table-responsive" style="height:400px">
      <input type="hidden" name="" id="txt_num_lin" value="0">
      <input type="hidden" name="" id="txt_num_item" value="0">
      <table class="table table-hover">
        <thead>
          <th>ITEM</th>
          <th>REFERENCIA</th>
          <th class="text-center">DESCRIPCION</th>
          <th>CANTIDAD</th>
          <th>COSTO</th>
          <th>PVP</th>
          <th>DCTO %</th>
          <th>IVA</th>
          <th>IMPORTE</th>
        </thead>
        <tbody style="height:400px" id="tbl_body">
          
        </tbody>
        <tbody>
          <tr>
            <td colspan="6" class="text-right">
              <b>% Descuentos</b>
            </td>
            <td>
                <input type="text" class="form-control input-sm" name="" id="txt_des" value="0" onblur="descuentos()">
            </td>
            <td colspan="9">
               <label class="radio-inline"><input type="radio" name="rbl_des" id="rbl_pedido" value="T" checked="" onclick="descuentos()"> Al Total</label>
                <label class="radio-inline"><input type="radio" name="rbl_des" id="rbl_pedido" value="TL" onclick="descuentos()"> En las lineas</label>
                <label class="radio-inline"><input type="radio" name="rbl_des" id="rbl_pedido" value="L" onclick="descuentos()"> Por lineas</label>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    
  </div>
  <div class="row">
    <div class="col-sm-9">
      <button type="button" class="btn btn-primary" onclick="generar_factura()"><i class="fa fa-file-text-o"></i> Facturar</button>
      <!-- <button type="button" class="btn btn-primary"><i class="fa fa-check"></i> Aceptar</button> -->
      <!-- <button type="button" class="btn btn-danger"><i class="fa fa-close"></i> Cancelar</button>       -->
    </div>
    <div class="col-sm-3">
      <div class="row">
          <div class="col-sm-6">
            Sub Total:
          </div>
          <div class="col-sm-6">
            <input type="text" name="" class="form-control input-sm" id="txt_sub_tot">
          </div>        
      </div> 
      <div class="row">
          <div class="col-sm-6">
            Total descuento:
          </div>
          <div class="col-sm-6">
            <input type="text" name="" id="txt_tot_des" class="form-control input-sm">
          </div>        
      </div> 
      <div class="row">
          <div class="col-sm-6">
            IVA:
          </div>
          <div class="col-sm-6">
            <input type="text" name="" class="form-control input-sm" id="txt_tot_iva" value="0">
          </div>        
      </div> 
      <div class="row">
          <div class="col-sm-6">
            Precio Total:
          </div>
          <div class="col-sm-6">
            <input type="text" name="" class="form-control input-sm" id="txt_pre_tot">
          </div>        
      </div>      
    </div>   
  </div>   
</div>
