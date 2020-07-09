<?php  
require_once("panel.php");
?>
<link rel="stylesheet" href="../../lib/dist/css/style_acordeon.css">
<script type="text/javascript">

  $(document).ready(function()
  {
    meses();
   cargar_cuentas();
   tipo_pago();
   copy_empresa();

   $('#MBoxCta').keyup(function(e){ 
    if(e.keyCode != 46 && e.keyCode !=8)
    {
      validar_cuenta(this);
    }
  })

   $('#MBoxCtaAcreditar').keyup(function(e){ 
    if(e.keyCode != 46 && e.keyCode !=8)
    {
      validar_cuenta(this);
    }
  })

 });

  function cargar_cuentas()
  {
    $.ajax({
   // data:  {parametros:parametros},
   url:   '../controlador/ctaOperacionesC.php?cuentas=true',
   type:  'post',
   dataType: 'json',
   beforeSend: function () {   
     $('#myModal_espera').modal('show');   
   },
   success:  function (response) { 
    if(response)
    {
      $('#tabla').html(response);
      $('#myModal_espera').modal('hide');   
    }

  }
});
  }

  function copiar_op($op)
  {
    
    if($('#DLEmpresa').val() !='')
    {
      Swal.fire({
          title: 'Seguro de Copiar el catalogo de:?',
          text: "("+$('#DLEmpresa').val()+") "+$('#DLEmpresa option:selected').text(),
          footer: "Este proceso remplazara el catalogo actual",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
              if($op == 'true')
              {
                $('#modal_periodo').modal('show');
              }else
              {
                copiar();
              }                        
          }
        })
    }else
    {
       Swal.fire('Seleccione una empresa');
       $('#modal_copiar').modal('show');   
    }
   
  }

  function copiar()
  {
    var parametros = 
    {
      "CheqCatalogo":$('#CheqCatalogo').is(':checked'),
      "CheqFact":$('#CheqFact').is(':checked'),
      "CheqSubCta":$('#CheqSubCta').is(':checked'),
      "CheqSubCP":$('#CheqSubCP').is(':checked'),
      "CheqSetImp":$('#CheqSetImp').is(':checked'),
      'empresa':$('#DLEmpresa').val(),
      'periodo':$('#txt_perido_c').val(),
      'si_no':'false',

    }
     $.ajax({
              data:  {parametros:parametros},
              url:   '../controlador/ctaOperacionesC.php?copiar=true',
              type:  'post',
              dataType: 'json',
              beforeSend: function () {   
                $('#myModal_espera').modal('show');   
              },
              success:  function (response) { 
               if(response==1)
               {
                 // $('#tabla').html(response);
                 cargar_cuentas();
                $('#myModal_espera').modal('hide');
                Swal.fire(
                  'Proceso terminado?',
                  'Se a copia con exito el catalo de cuentas',
                  'success'
)   
               }else
               {
                  alert('el proceso se finalizo opero con errores');
                   cargar_cuentas();
                $('#myModal_espera').modal('hide');   
               }
              }
            });
  }


   function copy_empresa()
  {
    var empresas = '<option value="">Elija empresa a copiar el catalogo</option>';
    $.ajax({
   // data:  {parametros:parametros},
   url:   '../controlador/ctaOperacionesC.php?copy_empresa=true',
   type:  'post',
   dataType: 'json',
   beforeSend: function () {   
     $('#myModal_espera').modal('show');   
   },
   success:  function (response) { 
    if(response)
    {
       $.each(response, function(i, item){
          // console.log(item);
          empresas+='<option value="'+item.Item+'">'+item.Empresa+'</option>';
        }); 
      $('#DLEmpresa').html(empresas);
    }

  }
});
  }

  function ingresar_presu()
  {
    if($('#DCMes').val() != '' && $('#txt_val_pre').val() != ''){
    var parametros=
    {
      'mes':$('#DCMes').val(),
      'mes1':$('#DCMes option:selected').text(),
      'valor':$('#txt_val_pre').val(),
      'Cta':$('#MBoxCta').val(),
    }
   
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/ctaOperacionesC.php?ingresar_presu=true',
      type:  'post',
      dataType: 'json',
      // beforeSend: function () {   
      //   $('#myModal_espera').modal('show');   
      // },
      success:  function (response) { 
        if(response == 1)
          {
            // console.log($('#MBoxCta').val().slice(0,-1));
             cargar_presupuesto($('#MBoxCta').val().slice(0,-1));
           $('#exampleModalCenter').modal('hide');

          }else
          {
            alert('no sss');
          }
      }
    });
  }else
  {
    Swal.fire({
      type: 'error',
      title: 'Algo salio mal',
      text: 'Debe llenar todo los campos!'
    });
  }
  }

  function meses()
  {
    var meses='<option value="">Seleccione mes</option>'
    $.ajax({
      // data:  {parametros:parametros},
      url:   '../controlador/ctaOperacionesC.php?meses=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
         $.each(response, function(i, item){
          // console.log(item);
          meses+='<option value="'+item.acro+'">'+item.mes+'</option>';
        }); 
    $('#DCMes').html(meses);   
        }
    });
  }

  function tipo_pago()
  {
    var pago = '<option value="">Selecciopne tipo de pago</option>';
    $.ajax({
   // data:  {parametros:parametros},
   url:   '../controlador/ctaOperacionesC.php?tipo_pago=true',
   type:  'post',
   dataType: 'json',
   success:  function (response) { 
    $.each(response, function(i, item){
          // console.log(item);
          pago+='<option value="'+item.Codigo+'">'+item.CTipoPago+'</option>';
        }); 
    $('#DCTipoPago').html(pago);              
  }
});
  }

  function grabar_cuenta()
  {
    var num = $('#MBoxCta').val();
    var nom = $('#TextConcepto').val();
    if(nom =='')
    {
      nom = 'Sin nombre';
      $('#TextConcepto').val(nom);
    }
    Swal.fire({
      title: 'Esta seguro de guardar?',
      text: "la cuenta N°"+num+' '+nom+' ',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si'
    }).then((result) => {
      if (result.value) {        
       grabar();
     }
   })
  }

  function grabar()
  {
    var acre = $('#MBoxCtaAcreditar').val();
    if(acre == ''){acre = 0;}

    var parametros=  {
      'OpcG':$('#OpcG').is(':checked'),
      'OpcD':$('#OpcD').is(':checked'),
      'CheqTipoPago':$('#CheqTipoPago').is(':checked'),
      'DCTipoPago':$('#DCTipoPago').val(),
      'TextPresupuesto':$('#TextPresupuesto').val(),
      'Numero': 0,
      'LstSubMod':$('#LstSubMod').val(),
      'TextConcepto':$('#TextConcepto').val(),
      'MBoxCta':$('#MBoxCta').val(),
      'LabelCtaSup':$('#LabelCtaSup').val(),
      'MBoxCtaAcreditar':acre,
      'OpcNoAplica':$('#OpcNoAplica').is(':checked'),
      'OpcIEmp':$('#OpcIEmp').is(':checked'),
      'OpcEEmp':$('#OpcEEmp').is(':checked'),
      'CheqConIESS':$('#CheqConIESS').is(':checked'),
      'CheqUS':$('#CheqUS').is(':checked'),
      'CheqFE':$('#CheqFE').is(':checked'),
      'CheqModGastos':$('#CheqModGastos').is(':checked'),
      'TxtCodExt':$('#TxtCodExt').val(),

    }
    $.ajax({   
     url:   '../controlador/ctaOperacionesC.php?grabar=true',
     type:  'post',
     dataType: 'json',
     data:{parametros:parametros} ,     
     success:  function (response) { 
      if(response == 1)
      {
       cargar_cuentas();
     }else
     {
      alert('oops!');
    }

  }
});
  }
  function forma_pago()
  {
    if($('#CheqTipoPago').is(':checked'))
    {
     $('#DCTipoPago').show();
    // alert('sss');
  }else
  {
    // alert('ffff');
    $('#DCTipoPago').hide();
  }
}

function presupuesto_act(tip)
{
  if(tip == 'CC' || tip == 'G' || tip == 'I')
  {
     $('#btn_ingresar_pre').prop('disabled', false);
     $('#btn_ingresar_pre').prop('disabled', false);
  }else
  {
    $('#btn_ingresar_pre').prop('disabled', true);
    $('#btn_ingresar_pre').prop('disabled', true);
  }
}

function cargar_presupuesto(cod)
{
  var pago = '<tr><td>No se a encontrado ningun presupuesto</td><tr>';
  var suma = 0.00;
    $.ajax({
    data:  {cod:cod},
   url:   '../controlador/ctaOperacionesC.php?presupuesto=true',
   type:  'post',
   dataType: 'json',
   success:  function (response) { 
    if(response != 0)
    {
      console.log(response);
      pago='';
    $.each(response, function(i, item){
          suma +=item.Presupuesto;
          pago+='<tr><td>'+item.Mes+'</td><td>'+item.Presupuesto+'</td>';
        }); 
   }
   $('#TextPresupuesto').val(suma)
    $('#table_pre').html(pago);              
  }
});

}

function cargar_datos_cuenta(cod)
{
    $.ajax({
    data:  {cod:cod},
   url:   '../controlador/ctaOperacionesC.php?datos_cuenta=true',
   type:  'post',
   dataType: 'json',
   success:  function (response) { 
    if(response != 0)
    {
      console.log(response);  
      // $('#LstSubMod').val('"'+response.TC+'"');    //
      $('#LabelNumero').val(response[0].Clave);
      if(response[0].DG=='G')
      {
        $('#OpcG').prop('checked',true);
      }else
      {
        $('#OpcD').prop('checked',true);
      }
      if(response[0].Con_IESS != 0)
      {        
        $('#CheqConIESS').prop('checked',true);
      }
      if(response[0].Con_IESS != 0)
      {        
        $('#CheqConIESS').prop('checked',true);
      }else
      {
        $('#CheqConIESS').prop('checked',false);
      }
      if(response[0].I_E_Emp == 'I')
      {        
        $('#OpcIEmp').prop('checked',true);
      }
      if(response[0].I_E_Emp == 'E')
      {        
        $('#OpcEEmp').prop('checked',true);
      }
      if(response[0].I_E_Emp == '.')
      {        
        $('#OpcNoAplica').prop('checked',true);
      }
      if(response[0].Mod_Gastos != 0)
      {        
        $('#CheqModGastos').prop('checked',true);
      }else
      {
         $('#CheqModGastos').prop('checked',false);
      }
      if(response[0].ME != 0)
      {        
        $('#CheqUS').prop('checked',true);
      }else
      {
        $('#CheqUS').prop('checked',false);
      }
       if(response[0].Listar != 0)
      {        
        $('#CheqFE').prop('checked',true);
      }else
      {
        $('#CheqFE').prop('checked',false);
      }
      if(response[0].Tipo_Pago != 0)
      {        
        $('#CheqTipoPago').prop('checked',true);
        forma_pago();
        $("#DCTipoPago option[value='"+response[0].Tipo_Pago+"']").attr("selected", true); 
      }else
      {
        $('#CheqTipoPago').prop('checked',false);
        forma_pago();
        $("#DCTipoPago option[value='']").attr("selected", true); 
      }

      $("#LstSubMod option[value='"+response[0].TC+"']").attr("selected", true);   
      presupuesto_act($('#LstSubMod').val());  
  }
 }
});

}
</script>
<div class="container-lg">
  <div class="row">
    <div class="col-lg-3 col-sm-4 col-md-8 col-xs-12">
      <div class="col-xs-2 col-md-2 col-sm-2">
       <a  href="./contabilidad.php?mod=contabilidad#" title="Salir de modulo" class="btn btn-default">
         <img src="../../img/png/salire.png">
       </a>
     </div>
     <div class="col-xs-2 col-md-2 col-sm-2">
       <button type="button" class="btn btn-default" title="Copiar Catalogo" data-toggle="modal" data-target="#modal_copiar">
        <img src="../../img/png/copiar_1.png">
      </button>
    </div>
    <div class="col-xs-2 col-md-2 col-sm-2">                 
     <button type="button" class="btn btn-default" title="Cambiar Cuentas" data-toggle="dropdown">
       <img src="../../img/png/pbcs.png">
     </button>
   </div>
   <div class="col-xs-2 col-md-2 col-sm-2">
     <button title="Guardar"  class="btn btn-default" onclick="grabar_cuenta()">
       <img src="../../img/png/grabar.png" >
     </button>
   </div>
 </div>
</div>
<div class="row"><br>
  <div class="col-sm-5" id="tabla"></div>
  <div class="col-sm-7">      
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home">DATOS PRINCIPALES</a></li>
      <li><a data-toggle="tab" href="#menu1">PRESUPUESTOS DE SUBMODULOS</a></li>
    </ul>
    <div class="tab-content"><br>
      <div id="home" class="tab-pane fade in active">
        <div class="row">
          <div class="col-sm-4">
           <b>Codigo de cuenta</b><br>
           <input type="" name="MBoxCta" class="form-control input-sm" id="MBoxCta" placeholder="<?php 
           echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" onblur="tip_cuenta(this.value)" ><br>
           <b>Cuenta superior</b><br>
           <input type="" name="LabelCtaSup" class="form-control input-sm" id="LabelCtaSup" readonly=""><br>
           <b>Tipo de cuenta</b><br>
           <label class="checkbox-inline"><input type="radio" name="rbl_t" id="OpcD" > <b>Detalle</b> </label><br>
           <label class="checkbox-inline"><input type="radio" name="rbl_t" id="OpcG" checked=""> <b>Grupo</b> </label>
         </div>
         <div class="col-sm-4">
           <b>Nombre de cuenta</b> <br>
           <input type="" name="TextConcepto" class="form-control input-sm" id="TextConcepto"> <br>
           <table class="table">
             <tr>
               <td><b>Tipo de cuenta</b></td>
               <td><input type="" name="LabelTipoCta" class="form-control input-sm" id="LabelTipoCta"></td>
             </tr>
             <tr>
               <td><b>Numero</b></td>
               <td><input type="" name="LabelNumero" class="form-control input-sm" id="LabelNumero" value="0"></td>
             </tr>
             <tr>
               <td><b>Codigo Externo</b></td>
               <td><input type="" name="TxtCodExt" class="form-control input-sm" id="TxtCodExt" readonly="" value="0"></td>
             </tr>
           </table>         
         </div>
         <div class="col-sm-4">
           <b>Tipo de cuenta</b><br>
           <select class="form-control input-sm" id="LstSubMod" onchange="presupuesto_act($('#LstSubMod').val())">
             <option value="N">Seleccione tipo de cuenta</option>
             <option value='N'>General/Normal</option>
             <option value='CtaCaja'>Cuenta de Caja</option>
             <option value='CtaBancos'>Cuenta de Bancos</option>
             <option value='C'>Modulo de CxC</option>
             <option value='P'>Modulo de CxP</option>
             <option value='I'>Modulo de Ingresos</option>
             <option value='G'>Modulo de Gastos</option>
             <option value='CS'>CxC Sin Submódulo</option>
             <option value='PS'>CxP Sin Submódulo</option>
             <option value='RF'>Retención en la Fuente</option>
             <option value='RI'>Retención del I.V.A Servicios</option>
             <option value='RB'>Retencion del I.V.A Bienes</option>
             <option value='CF'>Crédito Retencion en la Funete</option>
             <option value='CI'>Crédito Retencion del I.V.A. Servicio</option>
             <option value='CB'>Crédito Retencion del I.V.A. Bienes</option>
             <option value='CP'>Caja Cheques Posfechados</option>
             <option value='PM'>Modulo de Primas</option>
             <option value='RP'>Modulo de Inventario</option>
             <option value='TJ'>Opcion Tarjeta de Credito</option>
             <option value='CC'>Modulo Centro de Costos</option>
           </select><br>
           <b>Codigo acreditar</b><br>
           <input type="" name="MBoxCtaAcreditar" class="form-control input-sm" id="MBoxCtaAcreditar" placeholder="<?php 
           echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>">
           <label class="checkbox-inline"><input type="checkbox" name="CheqModGastos" id="CheqModGastos"> <b>Para gastos de caja chica</b></label> <br> 
           <label class="checkbox-inline"><input type="checkbox" name="CheqUS" id="CheqUS"> <b>Cuenta M/E</b></label>  <br>
           <label class="checkbox-inline"><input type="checkbox" name="CheqFE" id="CheqFE"> <b>Flujo efectivo</b></label>  <br>                
         </div>         
       </div>
       <div class="row">
        <div class="col-sm-5">
          <div class="panel panel-default">
           <div class="panel-heading"><b>Rol de Pagos para Empleados</b></div>
           <label class="checkbox-inline"><input type="radio" name="rbl_rol" id="OpcNoAplica" checked=""> <b>No Aplica</b> </label><br>
           <label class="checkbox-inline"><input type="radio" name="rbl_rol" id="OpcIEmp"> <b>Ingreso</b> </label><br>
           <label class="checkbox-inline"><input type="radio" name="rbl_rol" id="OpcEEmp"> <b>Descuentos</b> </label><br>
           <label class="checkbox-inline"><input type="checkbox" name="rbl_rol" id="CheqConIESS"> <b>Ingreso extra con Aplicacion al IESS</b></label>
         </div>  
       </div>
       <div class="col-sm-7">
        <div class="col-sm-12">
          <label class="checkbox-inline"><input type="checkbox" name="CheqTipoPago" id="CheqTipoPago" onclick="forma_pago()"> TIPO DE PAGO</label>
            <br>
            <select class="form-control input-sm" id="DCTipoPago" style="display: none;">
              <option>seleccione tipo de pago</option>
            </select>           
        </div>
        <div class="col-sm-10">
          <b>PRESUPUESTOS</b>
          <table class="table table-responsive col-md-4">
            <th>Mes</th>
            <th>Presupuesto</th>
            <tbody id="table_pre">
              <td>-</td>
              <td>-</td>
            </tbody>
          </table>          
        </div>
        <div class="col-sm-2"><br>
          <input type="button" name="" id="btn_ingresar_pre" disabled="" class="btn btn-primary btn-xs" value="Ingresar" data-toggle="modal" data-target="#exampleModalCenter">
        </div>
        <div class="col-sm-12"><br>
          <input type="" name="TextPresupuesto" id="TextPresupuesto" value="0.0">  
        </div>
      </div>     
    </div>
    <div class="row">
      seccion de presupuesto
      
  
    </div>   
  </div>
  <div id="menu1" class="tab-pane fade">
    <div class="row">
      <div class="col-sm-12">
         <h3>Menu 1</h3>
    <p>Some content in menu 1.</p>
    <input type="" name="">
      </div>
    </div>
  </div>
</div>  
</div>

</div>
</div>


<div class="modal fade bd-example-modal-sm" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Ingrese Presupuesto</h5>
      </div>
      <div class="modal-body">
        <select class="form-control input-sm" id="DCMes">
          <option>Seleccione mes</option>
        </select>
        <input type="" name="" id="txt_val_pre" class="form-control input-sm" placeholder="0.00">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="ingresar_presu()">Ingresar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modal_copiar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Copiar catalogo de otra empresa</h5>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-9">
              <select class="form-control input-sm" id="DLEmpresa">
                <option>Elija empresa a copiar el catalogo</option>
              </select><br>
              <label class="checkbox-inline"><input type="checkbox" name="CheqCatalogo" id="CheqCatalogo"> Catalogo de cuentas</label><br>
              <label class="checkbox-inline"><input type="checkbox" name="CheqSetImp" id="CheqSetImp"> Seteos de impresion</label><br>
              <label class="checkbox-inline"><input type="checkbox" name="CheqFact" id="CheqFact"> Seteos de facturacion</label><br>
              <label class="checkbox-inline"><input type="checkbox" name="CheqSubCta" id="CheqSubCta"> SubCuentas de Ingreso, Gastos y costos</label><br>
              <label class="checkbox-inline"><input type="checkbox" name="CheqSubCP" id="CheqSubCP"> SubCuentas de CxC y CxP</label>            
          </div>
          <div class="col-md-3 text-center">
            <div class="row">
              <div class="col-md-12 col-sm-6 col-xs-2">                
                 <button type="button" class="btn btn-default" title="Copiar Catalogo" data-toggle="modal" data-target="#modal_copiar" onclick="copiar_op('false')">
                  <img src="../../img/png/agregar.png"><br>
                  Aceptar
                </button>
              </div>
              <div class="col-md-12 col-sm-6 col-xs-2">
                <br>
                 <button type="button" class="btn btn-default" title="Cerrar" data-dismiss="modal">
                  <img src="../../img/png/salire.png"><br>&nbsp; &nbsp;Salir&nbsp;&nbsp;&nbsp;
                </button>
              </div>              
            </div>
            
          </div>
        </div>
      </div>
     <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="ingresar_presu()">Ingresar</button>
      </div> -->
    </div>
  </div>
</div>

<div class="modal fade bd-example-modal-sm" id="modal_periodo" tabindex="-1" role="dialog" aria-labelledby="modal_periodo" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle">Ingrese Periodo</h5>
      </div>
      <div class="modal-body">
        <input type="" name="" id="txt_perido_c" class="form-control input-sm" value=".">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="copiar()">Aceptar</button>
      </div>
    </div>
  </div>
</div>


<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

