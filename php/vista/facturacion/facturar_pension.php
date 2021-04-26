<?php
  include "../controlador/facturacion/facturar_pensionC.php";
  $facturar = new facturar_pensionC();
  $codigo = ReadSetDataNum("FA_SERIE_001001", True, False);
?>
<style type="text/css">
  #container1{
    margin-top: 20px;
  }
  #label1{
    font-size: 11px;
  }
  #saldo{
    background-color: #C1C000;
    border: 0;
  }
  #saldo_input{
    background-color: #FFFFBE;
    border: 0;
  }
  .red{
    color: #E80A0A;
    font-size: 14px;
    font-weight: bold;
  }
  .red1{
    background-color: #810001;
  }
  .black{
    color: #010101;
    font-size: 14px;
    font-weight: bold;
  }
  .green{
    color: #20AC1D;
    font-size: 14px;
    font-weight: bold;
  }
  .blue{
    color: #04012C;
    font-size: 14px;
    font-weight: bold;
  }
  #customers th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #ddd;
    color: black;
  }
</style>

<script type="text/javascript">
  
  $(document).ready(function () {
    autocomplete_cliente();
    catalogoLineas();
    //enviar datos del cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#email').val(data.email);
      $('#direccion').val(data.direccion);
      $('#direccion1').val(data.direccion);
      $('#telefono').val(data.telefono);
      $('#codigo').val(data.codigo);
      $('#ci_ruc').val(data.ci_ruc);
      $('#persona').val(data.cliente);
      $('#grupo').val(data.grupo);
      $('#codigoB').val("Código del banco: "+data.ci_ruc);
      catalogoProductos(data.codigo);
    });
  });

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/facturar_pensionC.php?cliente=true',
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

  function catalogoLineas(){
    var cursos = $("#DCLinea");
    fechaEmision = $('#fechaEmision').val();
    fechaVencimiento = $('#fechaVencimiento').val();
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogo=true',
      data: {'fechaVencimiento' : fechaVencimiento , 'fechaEmision' : fechaEmision}, 
      success: function(data)             
      {
        if (data) {
          datos = JSON.parse(data);
          // Limpiamos el select
          cursos.find('option').remove();
          for (var indice in datos) {
            cursos.append('<option value="' + datos[indice].id + '">' + datos[indice].text + '</option>');
          }
        }else{
          console.log("No tiene datos");
        }            
      }
    });
  }

  function catalogoProductos(codigoCliente){
    console.log(codigoCliente);
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogoProducto=true',
      data: {'codigoCliente' : codigoCliente }, 
      success: function(data)
      {
        if (data) {
          datos = JSON.parse(data);
          for (var indice in datos) {
            var tr = `<tr>
              <td><input type="checkbox" name="`+datos[indice].mes+`"></td>
              <td>`+datos[indice].mes+`</td>
              <td>`+datos[indice].codigo+`</td>
              <td>`+datos[indice].periodo+`</td>
              <td>`+datos[indice].producto+`</td>
              <td>`+datos[indice].valor+`</td>
              <td>`+datos[indice].descuento+`</td>
              <td>`+datos[indice].descuento2+`</td>
              <td>`+datos[indice].valor+`</td>
            </tr>`;
            $("#cuerpo").append(tr);
          }
        }else{
          console.log("No tiene datos");
        }            
      }
    });
  }

</script>
<div class="container" id="container1">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
          <img src="../../img/png/team.png" width="25" height="30">
        </a>
      </div>
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a href="./farmacia.php?mod=Farmacia&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_pdf" title="Pacientes">
          <img src="../../img/png/pacientes.png" width="25" height="30">
        </a>           
      </div>
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a href="./farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_excel" title="Descargos">
          <img src="../../img/png/group.png" width="25" height="30">
        </a>         
      </div>
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
          <img src="../../img/png/bus.png" width="25" height="30">
        </a>
      </div>  
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
          <img src="../../img/png/document.png" width="25" height="30">
        </a>
      </div>
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
          <img src="../../img/png/project.png" width="25" height="30">
        </a>
      </div>
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
          <img src="../../img/png/data.png" width="25" height="30">
        </a>
      </div> 
    </div>
  </div>
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-2">
            <select class="form-control input-sm" name="DCLinea" id="DCLinea">
              <option>Gerencia</option>
              <?php 
                $catalogo = $facturar->getCa
              ?>
            </select>
          </div>
          <div class="col-sm-2 text-right">
            <label>Fecha emisión</label>
          </div>
          <div class="col-sm-2">
            <input type="date" name="fechaEmision" id="fechaEmision" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" onchange="catalogoLineas();">
          </div>
          <div class="col-sm-2 text-right">
            <label>Fecha vencimiento</label>
          </div>
          <div class="col-sm-2">
            <input type="date" name="fechaVencimiento" id="fechaVencimiento" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" onchange="catalogoLineas();">
          </div>
          <div class=" col-sm-2">
            <label class="red">Factura No.</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label class="text-right">Cliente/Alumno(C)</label>
          </div>
          <div class="col-sm-6">
            <select class="form-control input-sm" id="cliente" name="cliente" >
              <option value="">Seleccione un cliente</option>
              
            </select>
          </div>
          <div class="col-sm-2">
            <input type="input" class="form-control input-sm" id="grupo" name="grupo">   
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm text-right" name="factura" value="<?php echo $codigo; ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Dirección</label>
          </div>
          <div class="col-sm-6">
            <input type="input" class="form-control input-sm" name="direccion" id="direccion">
          </div>
          <div class="col-sm-2 text-center justify-content-center align-items-center">
            <input style="width: 50px" type="text" name="codigoBanco" class="form-control input-sm text-center justify-content-center align-items-center" value="538">
          </div>
          <div class="col-sm-1">
            <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_no" checked="" style="margin-right: 2px;">Con mes</label>  
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Persona Natural</label>
          </div>
          <div class="col-sm-5">
            <input type="input" class="form-control input-sm" name="persona" id="persona">
          </div>
          <div class="col-sm-1 text-right">
            <label>CI/R.U.C</label>
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm" name="ci" id="ci_ruc">   
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Dirección</label>
          </div>
          <div class="col-sm-5">
            <input type="input" class="form-control input-sm" name="direccion" id="direccion1">
          </div>
          <div class="col-sm-1 text-right">
            <label>Telefono</label>
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm" name="telefono" id="telefono">
          </div>
          <div class="col-sm-2">
            <label>Código interno</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Email</label>
          </div>
          <div class="col-sm-8">
            <input type="input" class="form-control input-sm" name="email" id="email">
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm" name="codigo" id="codigo">
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-responsive table-bordered thead-dark" id="customers" style="height: 100px">
              <thead>
                <tr>
                  <th></th>
                  <th>Mes</th>
                  <th>Código</th>
                  <th>Año</th>
                  <th>Producto</th>
                  <th>Valor</th>
                  <th>Descuento</th>
                  <th>Desc. P. P.</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody id="cuerpo">
              </tbody>
            </table>          
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Bancos/Tarjetas</label>
          </div>
          <div class="col-sm-6">
            <select class="form-control input-sm">
              <option>Seleccione Banco/Tarjeta</option>
              <?php
                $cuentas = $facturar->getCatalogoCuentas();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-2 text-right">
            <label class="form-control input-sm" id="saldo">Saldo a favor</label>
          </div>
          <div class="col-sm-2">
            <input type="input" id="saldo_input" class="form-control input-sm text-right blue" name="saldo" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Notas de crédito</label>
          </div>
          <div class="col-sm-6">
            <select class="form-control input-sm">
              <option>Seleccione nota de crédito</option>
              <?php
                $cuentas = $facturar->getNotasCredito();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-2 text-right">
            <label class="form-control input-sm" id="saldo">Saldo pendiente</label>
          </div>
          <div class="col-sm-2">
            <input type="input" id="saldo_input" class="form-control input-sm text-right blue" name="saldo" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3 text-right">
            <label>Cheque / Deposito del banco</label>
          </div>
          <div class="col-sm-5">
            <input type="text" name="cheque" class="form-control input-sm" value="SGP">
          </div>
          <div class="col-sm-4 text-center">
            <input type="text" name="codigoB" class="red1 form-control input-sm" id="codigoB" style="color: white" value="Código del banco: " />
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>Total Tarifa 0%</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_max_in" id="txt_max_in" class="form-control input-sm red text-right" value="35.70">
          </div>
          <div class="col-sm-2 text-right">
            <b>Cheque No.</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_max_in" id="txt_max_in" class="form-control input-sm text-right">
          </div>
          <div class="col-sm-4 text-center">
            <input type="text" class="form-control input-sm" id="codigoB"/>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>Total Tarifa 12%</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_min_in" id="txt_min_in" class="form-control input-sm red text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>Descuentos</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_canti" id="txt_canti" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class="col-sm-2 text-right">
            <b>Valor Banco</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_canti" id="txt_canti" class="form-control input-sm red text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>Desc x P P</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_precio" id="txt_precio" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class="col-sm-2 text-right">
            <b>Efectivo</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_canti" id="txt_canti" class="form-control input-sm red text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>I. V. A. 12%</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class="col-sm-2 text-right">
            <b>Abono N/C</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control input-sm red text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>Total Facturado</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class="col-sm-2 text-right">
            <b>Saldo</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class=" col-sm-4 col-sm-offset-8">
            <div class="col-sm-2 col-sm-offset-4">
              <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/save.png" width="25" height="30">
              </a>
            </div>
            <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
              <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png" width="25" height="30">
              </a>
            </div>
          </div>
        </div>
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