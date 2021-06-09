<?php
  include "../controlador/cybernet/facturaC.php";
  $facturar = new facturaC();
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
    autocomplete_producto();
    $("#nombreCliente").hide();
    //enviar datos del cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      console.log(data);
      $('#email').val(data.email);
      $('#direccion').val(data.direccion);
      $('#telefono').val(data.telefono);
      $('#ci_ruc').val(data.ci_ruc);
      $("#subtotal0").val(parseFloat(0.00).toFixed(2));
      $("#subtotal12").val(parseFloat(0.00).toFixed(2));
      $("#iva").val(parseFloat(0.00).toFixed(2));
      $("#total").val(parseFloat(0.00).toFixed(2));
    });
    $('#producto0').on('select2:select', function (e) {
      var data = e.params.data.data;
      $("#pvp0").val(parseFloat(data.pvp).toFixed(2));
      console.log(data);
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

  function autocomplete_producto(){
    $('#producto0').select2({
      placeholder: 'Seleccione un producto',
      ajax: {
        url:   '../controlador/cybernet/facturaC.php?catalagoProducto=true',
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
    $("#nombreCliente").show();
    $("#cliente").select2().next().hide();
  }

  function searchCliente(){
    $("#nombreCliente").hide();
    $("#cliente").select2().next().show();
    autocomplete_cliente();
  }

</script>
<div class="container" id="container1">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-5 col-xs-12">
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
            <input type="text" class="form-control input-sm" placeholder="Ingrese nombre del nuevo cliente" name="nombreCliente" id="nombreCliente">
          </div>
          <div class="col-sm-3 col-xs-4">
            <label>RUC/CI</label>
            <input type="input" class="form-control input-sm" name="ci_ruc" id="ci_ruc">
          </div>
          <div class="col-sm-2 col-xs-4">
            <label>Serie</label>
            <input type="input" class="form-control input-sm" name="serie" id="serie" value="<?php echo $serie ?>" readonly>
          </div>
          <div class="col-sm-2 col-xs-4">
            <label>Factura No</label>
            <input type="input" class="form-control input-sm" name="factura" id="factura" value="<?php echo $codigo ?>" readonly>
          </div>
        </div>
      
        <div class="row">
          <div class="col-sm-3 col-xs-6">
            <label>Correo eléctronico</label>
            <input type="input" class="form-control input-sm" name="email" id="email">
          </div>
          <div class="col-sm-5 col-xs-6">
            <label>Dirección</label>
            <input type="input" class="form-control input-sm" name="direccion" id="direccion">
          </div>
          <div class=" col-sm-2 col-xs-6">
            <label>Telefono convencional</label>
            <input type="input" class="form-control input-sm" name="telefono1" id="telefono">
          </div>
          <div class=" col-sm-2 col-xs-6">
            <label>Telefono celular</label>
            <input type="input" class="form-control input-sm" name="telefono2" id="telefono1">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <h4>Detalle del pedido</h4>
          </div>
          <div class="col-sm-12" style="
              height: 150px;
              overflow: auto;
              display: block;
          ">
            <table class="table table-responsive table-borfed thead-dark" id="customers">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>PVP</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody id="cuerpo">
                <tr>
                  <td>
                    <select class="form-control input-sm" id="producto0" name="producto0">
                      <option value="">Seleccione un cliente</option>
                    </select>
                  </td>
                  <td>
                    <input type="text" class="form-control input-sm text-right" name="cantidad0" value="0">
                  </td>
                  <td>
                    <input type="text" class="form-control input-sm text-right" name="pvp0" id="pvp0" value="0">
                  </td>
                  <td>
                    <input type="text" class="form-control input-sm text-right" name="total0" value="0">
                  </td>
                </tr>
              </tbody>
            </table>          
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-2 col-xs-offset-4 col-sm-offset-8 col-xs-4 text-right">
            <b>Subtotal 0%</b>
          </div>
          <div class="col-sm-2 col-xs-4">
            <input type="text" name="subtotal0" id="subtotal0" class="form-control input-sm red text-right" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-xs-offset-4 col-xs-4 col-sm-offset-8 text-right">
            <b>Subtotal 12%</b>
          </div>
          <div class="col-sm-2 col-xs-4">
            <input type="text" name="subtotal12" id="subtotal12" class="form-control input-sm red text-right" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-xs-offset-4 col-xs-4 col-sm-offset-8 text-right">
            <b>IVA 12%</b>
          </div>
          <div class="col-sm-2 col-xs-4">
            <input type="text" name="iva" id="iva" class="form-control input-sm red text-right" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-xs-offset-4 col-sm-offset-8 col-xs-4 text-right">
            <b>TOTAL</b>
          </div>
          <div class="col-sm-2 col-xs-4">
            <input type="text" name="total" id="total" class="form-control input-sm red text-right" readonly>
          </div>
        </div>
        <div class="row">
          <div class=" col-sm-4 col-xs-6 col-sm-offset-8 col-xs-offset-5">
            <div class="col-sm-2 col-xs-4 col-sm-offset-4 col-xs-offset-4">
              <a title="Guardar" class="btn btn-default" tabindex="22">
                <img src="../../img/png/save.png" width="25" height="30" onclick="guardarFactura();">
              </a>
            </div>
            <div class="col-xs-4 col-md-2 col-sm-2 col-lg-1">
              <a  href="./panel.php" title="Salir de modulo" class="btn btn-default" tabindex="23">
                <img src="../../img/png/salire.png" width="25" height="30">
              </a>
            </div>
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

<!-- Modal cliente nuevo-->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
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