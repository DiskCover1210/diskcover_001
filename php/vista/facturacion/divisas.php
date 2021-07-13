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

  function searchCliente(){
    $("#nombreCliente").hide();
    $("#cliente").select2().next().show();
    autocomplete_cliente();
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
        document.querySelector('#numeroSerie').innerText = datos.serie;
        console.log(DCLinea);
        $("#factura").val(datos.codigo);
      }
    });
  }

</script>
<div class="container" id="container1">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>Fecha</label>
            <input type="date" class="form-control input-sm" name="fecha" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-sm-6 col-xs-12">
            <label class="text-right">Cliente</label>
            <a title="Agregar nuevo cliente" style="padding-left: 20px" onclick="addCliente();">
              <img src="../../img/png/mostrar.png" width="20" height="20">
            </a>
            <a title="Buscar cliente" style="padding-left: 20px" onclick="searchCliente();">
              <img src="../../img/png/consultar.png" width="20" height="20">
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
            <input type="text" name="total" value="0.00" class="form-control input-sm">
          </div>
          <div class="col-sm-4">
            <label>Cantidad Cambio</label>
            <input type="text" name="total" value="0.00" class="form-control input-sm">
          </div>
          <div class=" col-sm-2">
              <a title="Calcular" class="btn btn-default" tabindex="22">
                <img src="../../img/png/calculadora.png" width="25" height="30" onclick="guardarFactura();">
              </a>
              <a  href="./panel.php" title="Aprobar" class="btn btn-default" tabindex="23">
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
                  <th width="500px">Codigo</th>
                  <th width="200px">Cantidad</th>
                  <th width="200px">Cant Bonf</th>
                  <th width="200px">Producto</th>
                </tr>
              </thead>
              <tbody id="cuerpo">
                <tr>
                  <td>
                    <select class="form-control input-sm" id="producto1" name="producto1" onchange="setPVP('1');">
                      <option value="">Seleccione un cliente</option>
                    </select>
                  </td>
                  <td>
                    <input type="text" size="50px" class="form-control input-sm text-right input-width" onkeyup="calcularSubtotal('1')" name="cantidad1" id="cantidad1" value="1">
                  </td>
                  <td>
                    <input type="text" size="50px" class="form-control input-sm text-right input-width" name="pvp1" id="pvp1" value="0" readonly>
                    <input type="hidden" class="form-control input-sm text-right" name="iva1" id="iva1" value="0" readonly>
                  </td>
                  <td>
                    <input type="text" size="50px" class="form-control input-sm text-right input-width" name="total1" id="total1" value="0" readonly>
                  </td>
                </tr>
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
            <input type="text" name="" class="form-control input-sm red" value="0.00">
          </div>
          <div class="col-sm-2">
            <label>Total Factura</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="" class="form-control input-sm red" value="0.00">
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
            <input type="text" name="" class="form-control input-sm red" value="0.00">
          </div>
          <div class="col-sm-2">
            <label>Total Fact. (ME)</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="" class="form-control input-sm red" value="0.00">
          </div>
          <div class="col-sm-2">
            <a title="Guardar" class="btn btn-default" tabindex="22">
              <img src="../../img/png/salire.png" width="25" height="30" onclick="guardarFactura();">
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

<!-- Modal cliente nuevo -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cliente Nuevo</h4>
      </div>
      <div class="modal-body">
        <label>Cliente</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el nombre del cliente">
        <label>RUC/CI</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el RUC/CI del cliente">
        <label>Dirección</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese la dirección del cliente">
        <label>Correo</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el correo del cliente">
        <label>Telefono convencional</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el telefono convencional del cliente">
        <label>Telefono celular</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el telefono celular del cliente">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" onclick="">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
 <!-- Fin Modal cliente nuevo-->