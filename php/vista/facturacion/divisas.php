<?php
  include "../controlador/facturacion/divisasC.php";
  $divisas = new divisasC();
  $serie = $_SESSION['INGRESO']['Serie_FA'];
  $codigo = ReadSetDataNum("FA_SERIE_".$serie , True, False);
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
    text-align: left;
    background-color: #ddd;
    color: black;
  }
  th, td {
    padding: 10px;
  }

  @media (max-width:600px) {
    .input-width{
      width: 80px;
    }
  }
</style>

<script type="text/javascript">
  $('body').on("keydown", function(e) { 
    if ( e.which === 27) {
      document.getElementById("").focus();
      e.preventDefault();
    }
  });
  $(document).ready(function () {
    autocomplete_cliente();
    numeroFactura();
    $("#nombreCliente").hide();
    //enviar datos del cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      console.log(data);
      $('#email').val(data.email);
      $('#direccion').val(data.direccion);
      $('#telefono').val(data.telefono);
      $('#ci_ruc').val(data.ci_ruc);
      $('#codigoCliente').val(data.codigo);
      $('#celular').val(data.celular);
    });
  });

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/cybernet/facturaC.php?cliente=true',
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

  function addCliente(){
    $("#myModal").modal("show");
  }

  function setPVP(){
    producto0 = $("#producto").val();
    producto = producto0.split("/");
    $("#preciounitario").val(producto[2]);
    console.log(producto);
  }

  function numeroFactura(){
    DCLinea = $("#DCLinea").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?numFactura=true',
      data: {
        'DCLinea' : DCLinea,
      }, 
      success: function(data)
      {
        datos = JSON.parse(data);
        labelFac = "("+datos.autorizacion+") No. "+datos.serie;
        document.querySelector('#numeroSerie').innerText = labelFac;
        console.log(DCLinea);
        $("#factura").val(datos.codigo);
      }
    });
  }

  function calcular(){
    TextVUnit = Number.parseFloat($("#preciounitario").val());
    TextCant = Number.parseFloat($("#cantidad").val());
    TextVTotal = Number.parseFloat($("#total").val());
    producto = $("#producto").val();
    producto = producto.split("/");
    console.log(TextVUnit);
    console.log(TextCant);
    console.log(TextVTotal);
    //if (TextVUnit && TextCant && TextVTotal) {
      if (TextVUnit <= 0) {
        $("#preciounitario").val(1);
      }
      console.log("entra");
      if (TextVTotal > 0 && TextCant == 0) {
        if (producto[3]) {
          TextCant = (TextVTotal / TextVUnit);
        }else{
          TextCant = (TextVTotal * TextVUnit);
        }
        $("#cantidad").val(TextCant);
      }else if(TextCant > 0 && TextVTotal == 0){
        if (producto[3]) {
          TextVTotal = (TextCant / TextVUnit);
        }else{
          TextVTotal = (TextCant * TextVUnit);
        }
        $("#total").val(TextVTotal);
      }
    //}
  }

  function aceptar(){
    producto = $("#producto").val();
    pvp = $("#preciounitario").val();
    total = $("#total").val();
    cantidad = $("#cantidad").val();
    producto = producto.split("/");
    console.log(producto);
    var table = document.getElementById('customers');
    var rowLength = table.rows.length;
    console.log(rowLength);
    console.log(producto);
    var tr = `<tr>
      <td><input type="text" id="codigo`+rowLength+`" value="`+producto[0]+`" disabled class="form-control input-sm"></td>
      <td><input type="text" id="cantidad`+rowLength+`" value="`+cantidad+`" disabled class="form-control input-sm"></td>
      <td><input type="text" id=boni"`+rowLength+`" value="`+cantidad+`" disabled class="form-control input-sm"></td>
      <td><input type="text" id="producto`+rowLength+`" value="`+producto[1]+`" disabled class="form-control input-sm"></td>
      <td><input type="text" id="pvp`+rowLength+`" value="`+pvp+`" disabled class="form-control input-sm"></td>
      <td><input type="text" id="total`+rowLength+`" value="`+total+`" disabled class="form-control input-sm"></td>
    </tr>`;
    $("#cuerpo").append(tr);
    calcularTotal();
  }

  function calcularTotal(){
    var table = document.getElementById('customers');
    var rowLength = table.rows.length;
    total = 0;
    for(var i=1; i<rowLength; i+=1){
      total += $("#total"+i).val();
    }
    $("#total0").val(parseFloat(total).toFixed(2));
    $("#totalFac").val(parseFloat(total).toFixed(2));
    $("#efectivo").val(parseFloat(total).toFixed(2));
  }

</script>
<div class="container" id="container1">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>Fecha</label>
            <input type="date" class="form-control input-sm" name="fecha" value="<?php echo date('Y-m-d'); ?>" onkeyup="numeroFactura();">
          </div>
          <div class="col-sm-6 col-xs-12">
            <label class="text-right">Cliente</label>
            <a title="Agregar nuevo cliente" style="padding-left: 20px" onclick="addCliente();">
              <img src="../../img/png/mostrar.png" width="20" height="20">
            </a>
            <select class="form-control input-sm" id="cliente" name="cliente">
              <option value="">Seleccione un cliente</option>
            </select>
            <input type="hidden" name="codigoCliente" id="codigoCliente">
            <input type="text" class="form-control input-sm" placeholder="Ingrese nombre del nuevo cliente" name="nombreCliente" id="nombreCliente" autocomplete="off">
          </div>
          <div class="col-sm-2">
            <textarea class="form-control input-sm"></textarea>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label class="text-right">TIPO DE PROCESO</label>
          </div>
          <div class="col-sm-4">
            <select class="form-control input-sm" name="DCLinea" id="DCLinea" onchange="numeroFactura();">
              <?php
                $cuentas = $divisas->getCatalogoLineas();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['id']."'>".$cuenta['text']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-2">
            <label id="numeroSerie" class="red">() No.</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="factura" id="factura" value="1" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-sm-offset-1">
            <label>PRODUCTO</label>
            <select class="form-control input-sm" id="producto" onchange="setPVP();">
              <?php
                $productos = $divisas->getProductos();
                foreach ($productos as $producto) {
                  echo "<option value='".$producto['id']."'>".$producto['text']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-2">
            <label>Precio Unitario</label>
            <input type="text" name="preciounitario" id="preciounitario" value="101.7900" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 col-sm-offset-1">
            <label>TOTAL EN S/.</label>
            <input type="text" name="total" id="total" value="0.00" class="form-control input-sm">
          </div>
          <div class="col-sm-4">
            <label>Cantidad Cambio</label>
            <input type="text" name="cantidad" id="cantidad" value="0.00" class="form-control input-sm">
          </div>
          <div class=" col-sm-2">
              <a title="Calcular" class="btn btn-default" tabindex="22">
                <img src="../../img/png/calculadora.png" width="25" height="30" onclick="calcular();">
              </a>
              <a title="Aprobar" class="btn btn-default" tabindex="23" onclick="aceptar();">
                <img src="../../img/png/aprobar.png" width="25" height="30">
              </a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1" style="
              height: 150px;
              overflow: auto;
              display: block;
          ">
            <table class="table table-responsive table-borfed thead-dark" id="customers">
              <thead>
                <tr>
                  <th width="200px">Codigo</th>
                  <th width="200px">Cantidad</th>
                  <th width="200px">Cant Bonf</th>
                  <th width="500px">Producto</th>
                  <th width="200px">Precio</th>
                  <th width="200px">Total</th>
                  <!--
                  <th width="200px">Total Desc2</th>
                  <th width="200px">Total IVA</th>
                  <th width="200px">SERVICIO</th>
                  <th width="200px">TOTAL</th>
                  <th width="200px">VALOR TOTAL</th>
                  <th width="200px">COSTO</th>
                  <th width="200px">Fecha IN</th>
                  <th width="200px">Fecha OUT</th>
                  <th width="200px">Cant Hab</th>
                  <th width="200px">Tipo Hab</th>
                  <th width="200px">Orden No</th>
                  <th width="200px">Mes</th>
                  <th width="200px">Cod Ejec</th>
                  <th width="200px">Porc C</th>
                  <th width="200px">REP</th>
                  <th width="200px">Fecha</th>
                  <th width="200px">CODIGO L</th>
                  <th width="200px">HABIT</th>
                  <th width="200px">TICKET</th>
                  <th width="200px">Cta</th>
                  <th width="200px">Cta SubMod</th>
                  <th width="200px">Item</th>
                  <th width="200px">Codigo U</th>
                  <th width="200px">CodBod</th>
                  <th width="200px">CodBar</th>
                  <th width="200px">TONELAJE</th>
                  <th width="200px">CORTE</th>
                  <th width="200px">A_No</th>
                  <th width="200px">Codigo_Cliente</th>
                  <th width="200px">Numero</th>
                  <th width="200px">Serie</th>
                  <th width="200px">Autorizacion</th>
                  <th width="200px">Codigo B</th>
                  <th width="200px">PRECIO2</th>
                  <th width="200px">COD_BAR</th>
                  <th width="200px">Fecha_V</th>
                  <th width="200px">Lote No</th>
                  <th width="200px">Fecha Fab</th>
                  <th width="200px">Fecha Exp</th>
                  <th width="200px">Reg Sanitario</th>
                  <th width="200px">Modelo</th>
                  <th width="200px">Procedencia</th>
                  <th width="200px">Serie No</th>
                  <th width="200px">Cta Inv</th>
                  <th width="200px">Cta Costo</th>
                  <th width="200px">Estado</th>
                  -->
                </tr>
              </thead>
              <tbody id="cuerpo">
                
              </tbody>
            </table>          
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>Total Tarifa 0%</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="total0" id="total0" class="form-control input-sm red" value="0.00">
          </div>
          <div class="col-sm-2">
            <label>Total Factura</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="totalFac" id="totalFac" class="form-control input-sm red" value="0.00">
          </div>
          <div class="col-sm-2">
            <a title="Guardar" class="btn btn-default" tabindex="22">
              <img src="../../img/png/save.png" width="25" height="30" onclick="guardarFactura();">
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>Total Tarifa 12%</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="total12" id="total12" class="form-control input-sm red" value="0.00">
          </div>
          <div class="col-sm-2">
            <label>Total Fact. (ME)</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="totalFacMe" id="totalFacMe" class="form-control input-sm red" value="0.00">
          </div>
          <div class="col-sm-2">
            <a title="Guardar" class="btn btn-default" tabindex="22">
              <img src="../../img/png/salire.png" width="25" height="30" onclick="guardarFactura();">
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>I.V.A. 12%</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="iva12" id="iva12" class="form-control input-sm red" value="0.00">
          </div>
          <div class="col-sm-2">
            <label>EFECTIVO</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="efectivo" id="efectivo" class="form-control input-sm red" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-3">
            <label>Cambio</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="cambio" id="cambio" class="form-control input-sm red" value="0.00">
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

<!-- Modal cliente nuevo -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cliente Nuevo</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-4">
            <label>RUC/CI</label>
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el RUC/CI del cliente">
          </div>
          <div class="col-sm-4">
            <label>Telefono</label>
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el RUC/CI del cliente">
          </div>
          <div class="col-sm-4">
            <label>Codigo Beneficiario</label>
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el RUC/CI del cliente">
          </div>
        </div>
        <label>Apellidos y Nombres</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el nombre del cliente">
        <label>Dirección</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el RUC/CI del cliente">
        <label>Email Principal</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese la dirección del cliente">
        <div class="row">
          <div class="col-sm-4">
            <label>Número de vivienda</label>
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el RUC/CI del cliente">
          </div>
          <div class="col-sm-2">
            <label>Grupo</label>
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el RUC/CI del cliente">
          </div>
          <div class="col-sm-6">
            <label>Nacionalidad</label>
            <select class="form-control input-sm">
              <option>593 Ecuador</option>
            </select>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
            <label>Provincia</label>
            <select class="form-control input-sm">
              <option>01 Azuay</option>
            </select>
          </div>
          <div class="col-sm-6">
            <label>Ciudad</label>
            <select class="form-control input-sm">
              <option>Cuenca</option>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
 <!-- Fin Modal cliente nuevo-->