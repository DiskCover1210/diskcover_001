<?php  
require_once("panel.php");
?>
<link rel="stylesheet" href="../../lib/dist/css/style_acordeon.css">
<script type="text/javascript">

  $(document).ready(function()
  {
   cargar_cuentas();
   tipo_pago();

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
        Swal.fire(
         grabar()
          )
      }
    })
  }
  function grabar()
  {
   $.ajax({
    data:  {
      'OpcG':$('#OpcG').is(':checked'),
      'OpcD':$('#OpcD').is(':checked'),
      'CheqTipoPago':$('#CheqTipoPago').is(':checked'),
      'DCTipoPago':$('#DCTipoPago').val(),
      'TextPresupuesto':$('#').val(),
      'Numero': 0,
      'LstSubMod':$('#LstSubMod').val(),
      'TextConcepto':$('#TextConcepto').val(),
      'MBoxCta':$('#MBoxCta').val(),
      'LabelCtaSup':$('#LabelCtaSup').val(),


    },
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
       <button type="button" class="btn btn-default" title="Copiar Catalogo" data-toggle="dropdown">
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
  <div class="col-sm-3" id="tabla"></div>
  <div class="col-sm-9">      
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#home">DATOS PRINCIPALES</a></li>
      <li><a data-toggle="tab" href="#menu1">PRESUPUESTOS DE SUBMODULOS</a></li>
    </ul>
    <div class="tab-content-wrapper"><br>
      <div id="home" class="tab-pane fade in active">
        <div class="row">
          <div class="col-sm-2">
           <b>Codigo de cuenta</b><br>
           <input type="" name="MBoxCta" class="form-control input-sm" id="MBoxCta" placeholder="<?php 
           echo $_SESSION['INGRESO']['Formato_Cuentas']; ?>" onblur="tip_cuenta(this.value)" ><br>
           <b>Cuenta superior</b><br>
           <input type="" name="LabelCtaSup" class="form-control input-sm" id="LabelCtaSup" readonly=""><br>
           <b>Tipo de cuenta</b><br>
           <label class="checkbox-inline"><input type="radio" name="OpcD" id="OpcD" checked=""> <b>Detalle</b> </label><br>
           <label class="checkbox-inline"><input type="radio" name="OpcG" id="OpcG"> <b>Grupo</b> </label>
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
               <td><input type="" name="LabelNumero" class="form-control input-sm" id="LabelNumero"></td>
             </tr>
             <tr>
               <td><b>Codigo Externo</b></td>
               <td><input type="" name="TxtCodExt" class="form-control input-sm" id="TxtCodExt" readonly=""></td>
             </tr>
           </table>         
         </div>
         <div class="col-sm-3">
           <b>Tipo de cuenta</b><br>
           <select class="form-control input-sm" id="LstSubMod">
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
           <label class="checkbox-inline"><input type="checkbox" name=""> <b>Para gastos de caja chica</b></label> <br> 
           <label class="checkbox-inline"><input type="checkbox" name=""> <b>Cuenta M/E</b></label>  <br>
           <label class="checkbox-inline"><input type="checkbox" name=""> <b>Flujo efectivo</b></label>  <br>                
         </div>
         <div class="col-sm-3">
          <div class="panel panel-default">
           <div class="panel-heading"><b>Rol de Pagos para Emoleados</b></div>
           <label class="checkbox-inline"><input type="radio" name="rbl_rol"> <b>No Aplica</b> </label><br>
           <label class="checkbox-inline"><input type="radio" name="rbl_rol"> <b>Ingreso</b> </label><br>
           <label class="checkbox-inline"><input type="radio" name="rbl_rol"> <b>Descuentos</b> </label><br>
           <label class="checkbox-inline"><input type="checkbox" name="rbl_rol" id="CheqConIESS"> <b>Ingreso extra con Aplicacion al IESS</b></label>
         </div>          
       </div>
     </div>
     <div class="row">
      <br>
      <div class="col-sm-2">
       <label class="checkbox-inline"><input type="checkbox" name="CheqTipoPago" id="CheqTipoPago"> TIPO DE PAGO</label>
     </div>
     <div class="col-sm-10">
       <select class="form-control input-sm" id="DCTipoPago">
         <option>seleccione tipo de pago</option>
       </select>          
     </div>      
   </div>
   <div class="row">
    <input type="" name="TextPresupuesto" id="TextPresupuesto">
     
   </div>   
 </div>
 <div id="menu1" class="tab-pane fade">
  <h3>Menu 1</h3>
  <p>Some content in menu 1.</p>
</div>
</div>  
</div>

</div>
</div>

<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

