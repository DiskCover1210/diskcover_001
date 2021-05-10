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
  .saldo_input{
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
  
  var total = 0;
  var total0 = 0;
  var total12 = 0;
  var iva12 = 0;
  var descuento = 0;
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
      $('#chequeNo').val(data.grupo);
      $('#codigoCliente').val(data.codigo);
      $('#tdCliente').val(data.tdCliente);
      $('#codigoB').val("Código del banco: "+data.ci_ruc);
      $("#total12").val(parseFloat(0.00).toFixed(2));
      $("#descuento").val(parseFloat(0.00).toFixed(2));
      $("#descuentop").val(parseFloat(0.00).toFixed(2));
      $("#efectivo").val(parseFloat(0.00).toFixed(2));
      $("#abono").val(parseFloat(0.00).toFixed(2));
      $("#iva12").val(parseFloat(0.00).toFixed(2));
      $("#total").val(parseFloat(0.00).toFixed(2));
      $("#total0").val(parseFloat(0.00).toFixed(2));
      $("#valorBanco").val(parseFloat(0.00).toFixed(2));
      $("#saldoTotal").val(parseFloat(0.00).toFixed(2));
      //$("input[type=checkbox]").prop("checked", false);
      total = 0;
      total0 = 0;
      total12 = 0;
      iva12 = 0;
      descuento = 0;
      catalogoProductos(data.codigo);
      saldoFavor(data.codigo);
      saldoPendiente(data.codigo);
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
    $('#myModal_espera').modal('show');
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
    $('#myModal_espera').modal('hide');
  }

  function catalogoProductos(codigoCliente){
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?catalogoProducto=true',
      data: {'codigoCliente' : codigoCliente }, 
      success: function(data)
      {
        if (data) {
          datos = JSON.parse(data);
          clave = 1;
          $("#cuerpo").empty();
          for (var indice in datos) {
            subtotal = (parseFloat(datos[indice].valor) + (parseFloat(datos[indice].valor) * parseFloat(datos[indice].iva) / 100)) - parseFloat(datos[indice].descuento) - parseFloat(datos[indice].descuento2);
            var tr = `<tr>
              <td><input type="checkbox" id="checkbox`+clave+`" onclick="totalFactura('checkbox`+clave+`','`+subtotal+`','`+datos[indice].iva+`','`+datos[indice].descuento+`')" name="`+datos[indice].mes+`"></td>
              <td>`+datos[indice].mes+`</td>
              <td>`+datos[indice].codigo+`</td>
              <td>`+datos[indice].periodo+`</td>
              <td>`+datos[indice].producto+`</td>
              <td><input size="10px" type ="text" id="valor`+clave+`" value ="`+parseFloat(datos[indice].valor).toFixed(2)+`" disabled/></td>
              <td><input size="10px" type ="text" id="descuento`+clave+`" value ="`+parseFloat(datos[indice].descuento).toFixed(2)+`" disabled/></td>
              <td><input size="10px" type ="text" id="descuento2`+clave+`" value ="`+parseFloat(datos[indice].descuento2).toFixed(2)+`" disabled/></td>
              <td><input size="10px" type ="text" id="subtotal`+clave+`" value ="`+parseFloat(subtotal).toFixed(2)+`" disabled/></td>
            </tr>`;
            $("#cuerpo").append(tr);
            clave++;
          }
          $("#efectivo").val(parseFloat(0.00).toFixed(2));
          $("#abono").val(parseFloat(0.00).toFixed(2));
          $("#descuentop").val(parseFloat(0.00).toFixed(2));
        }else{
          console.log("No tiene datos");
        }            
      }
    });
    $('#myModal_espera').modal('hide');
  }

  function saldoFavor(codigoCliente){
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?saldoFavor=true',
      data: {'codigoCliente' : codigoCliente }, 
      success: function(data)
      {
        datos = JSON.parse(data);
        valor = 0;
        if (datos !== null) {
          valor = datos.Saldo_Pendiente;
        }
        $("#saldoFavor").val(parseFloat(valor).toFixed(2));
      }
    });
  }

  function saldoPendiente(codigoCliente){
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?saldoPendiente=true',
      data: {'codigoCliente' : codigoCliente }, 
      success: function(data)
      {
        datos = JSON.parse(data);
        valor = 0;
        if (datos !== null) {
          valor = datos.Saldo_Pend;
        }
        $("#saldoPendiente").val(parseFloat(valor).toFixed(2));
      }
    });
  }

  function totalFactura(id,valor,iva,descuento1){
    console.log(valor);
    valor = parseFloat(valor);
    descuento1 = parseFloat(descuento1);
    var checkBox = document.getElementById(id);
    if (checkBox.checked == true){
      if (iva == 0) {
        total0 += valor;
      }else{
        iva12 += valor*(iva/100);
        total12 += valor;
      }
      descuento += descuento1;
      total += valor;
      console.log(total);
    } else {
      if (iva == 0) {
        total0 -= valor;  
      }else{
        total12 -= valor;
      }
      descuento -= descuento1;
      total -= valor;
      console.log(total);
    }

    $("#total12").val(parseFloat(total12).toFixed(2));
    $("#descuento").val(parseFloat(descuento).toFixed(2));
    $("#iva12").val(parseFloat(iva12).toFixed(2));
    $("#total").val(parseFloat(total).toFixed(2));
    $("#total0").val(parseFloat(total0).toFixed(2));
    $("#valorBanco").val(parseFloat(total).toFixed(2));
    $("#saldoTotal").val(parseFloat(total).toFixed(2));
  }

  function calcularDescuento(){
    $('#myModal').modal('hide');
    porcentaje = $('#porcentaje').val();
    var table = document.getElementById('customers');
    var rowLength = table.rows.length;

    for(var i=1; i<rowLength; i+=1){
      var row = table.rows[i];
      var cellLength = row.cells.length;
      checkbox = "checkbox"+i;
      var checkBox = document.getElementById(checkbox);
      if (checkBox.checked == true){
        valor = $("#valor"+i).val();
        descuento1 = valor * (porcentaje/100);
        $("#descuento2"+i).val(descuento1.toFixed(2));
        subtotal = valor - descuento1;
        $("#subtotal"+i).val(subtotal.toFixed(2));
        console.log(row.cells[0]);
        console.log(valor);
      }
      total0 = $("#total0").val();
      descuento = total0 * (porcentaje/100);
      total = total0 - descuento;
      $("#descuentop").val(parseFloat(descuento).toFixed(2));
      $("#total").val(parseFloat(total).toFixed(2));
      $("#valorBanco").val(parseFloat(total).toFixed(2));
      $("#saldoTotal").val(total.toFixed(2));
    }
  }

  function calcularSaldo(){
    total = $("#total").val();
    efectivo = $("#efectivo").val();
    abono = $("#abono").val();
    saldo = total - efectivo - abono;
    console.log(saldo);
    $("#saldoTotal").val(saldo.toFixed(2));
  }

  function guardarPension(){
    validarDatos = $("#total").val();
    if (validarDatos <= 0 ) {
      alert('Ingrese los datos necesarios para guardar la factura');
    }else{
      var update = confirm("¿Desea actualizar los datos del cliente?");
      TextRepresentante = $("#persona").val();
      TxtDireccion = $("#direccion").val();
      TxtTelefono = $("#telefono").val();
      TextFacturaNo = $("#factura").val();
      TxtGrupo = $("#grupo").val();
      TextCI = $("#ci_ruc").val();
      TD_Rep = $("#tdCliente").val();
      TxtEmail = $("#email").val();
      TxtDirS = $("#direccion1").val();
      TextCheque = $("#valorBanco").val();
      DCBanco = $("#cuentaBanco").val();
      TxtEfectivo = $("#efectivo").val();
      TxtNC = $("#cuentaNC").val();
      DCNC = $("#abono").val();
      codigoCliente = $("#codigoCliente").val();
      var confirmar = confirm("Esta seguro que desea guardar \n La factura No."+TextFacturaNo);
      if (confirmar == true) {
        $.ajax({
          type: "POST",
          url: '../controlador/facturacion/facturar_pensionC.php?guardarPension=true',
          data: {
            'update' : update,
            'TextRepresentante' : TextRepresentante,
            'TxtDireccion' : TxtDireccion,
            'TxtTelefono' : TxtTelefono,
            'TextFacturaNo' : TextFacturaNo,
            'TxtGrupo' : TxtGrupo,
            'TextCI' : TextCI,
            'TD_Rep' : TD_Rep,
            'TxtEmail' : TxtEmail,
            'TxtDirS' : TxtDirS,
            'codigoCliente' : codigoCliente,
            'TextCheque' : TextCheque,
            'DCBanco' : DCBanco,
            'TxtEfectivo' : TxtEfectivo,
            'TxtNC' : TxtNC,
            'DCNC' : DCNC, 
          }, 
          success: function(data)
          {
            
          }
        });
      }
    }
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
            <input type="hidden" name="codigoCliente" id="codigoCliente">
          </div>
          <div class="col-sm-2">
            <input type="input" class="form-control input-sm" id="grupo" name="grupo">   
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm text-right" name="factura" id="factura" value="<?php echo $codigo; ?>">
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
          <div class="col-sm-1 text-right">
            <input type="input" class="form-control input-sm" name="tdCliente" id="tdCliente">
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
          <div class="col-sm-12" style="
              height: 150px;
              overflow: auto;
              display: block;
          ">
            <table class="table table-responsive table-bordered thead-dark" id="customers">
              <thead>
                <tr>
                  <th></th>
                  <th>Mes</th>
                  <th>Código</th>
                  <th>Año</th>
                  <th>Producto</th>
                  <th width="100px">Valor</th>
                  <th width="100px">Descuento</th>
                  <th width="100px">Desc. P. P.</th>
                  <th width="100px">Total</th>
                </tr>
              </thead>
              <tbody id="cuerpo">
              </tbody>
            </table>          
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Bancos/Tarjetas</label>
          </div>
          <div class="col-sm-6">
            <select class="form-control input-sm" name="cuentaBanco" id="cuentaBanco">
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
            <input type="input" id="saldoFavor" class="form-control input-sm text-right black saldo_input" name="saldoFavor">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Notas de crédito</label>
          </div>
          <div class="col-sm-6">
            <select class="form-control input-sm" name="cuentaNC" id="cuentaNC">
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
            <input type="input" id="saldoPendiente" class="form-control input-sm text-right blue saldo_input" name="saldoPendiente">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3 text-right">
            <label>Cheque / Deposito del banco</label>
          </div>
          <div class="col-sm-5">
            <input type="text" name="cheque" class="form-control input-sm" value=".">
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
            <input type="text" name="total0" id="total0" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2 text-right">
            <b>Cheque No.</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="chequeNo" id="chequeNo" class="form-control input-sm text-right">
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
            <input type="text" name="total12" id="total12" class="form-control input-sm red text-right" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>Descuentos</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="descuento" id="descuento" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2 text-right">
            <b>Valor Banco</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="valorBanco" id="valorBanco" class="form-control input-sm red text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>Desc x P P</b>
            <button type="button" class="btn" data-toggle="modal" data-target="#myModal">%</button>
          </div>
          <div class="col-sm-2">
            <input type="text" name="descuentop" id="descuentop" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2 text-right">
            <b>Efectivo</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="efectivo" id="efectivo" onkeyup="calcularSaldo();" class="form-control input-sm red text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>I. V. A. 12%</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="iva12" id="iva12" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2 text-right">
            <b>Abono N/C</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="abono" id="abono" onkeyup="calcularSaldo();" class="form-control input-sm red text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <b>Total Facturado</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="total" id="total" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2 text-right">
            <b>Saldo</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="saldoTotal" id="saldoTotal" class="form-control input-sm red text-right">
          </div>
          <div class=" col-sm-4 col-sm-offset-8">
            <div class="col-sm-2 col-sm-offset-4">
              <a title="Guardar" class="btn btn-default">
                <img src="../../img/png/save.png" width="25" height="30" onclick="guardarPension();">
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
</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Porcentaje de descuento</h4>
      </div>
      <div class="modal-body">
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el porcentaje de descuento %">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="calcularDescuento();">Aceptar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>