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

  function autocomplete_producto(){
    $('#producto1').select2({
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
    $('#email').val('');
    $('#direccion').val('');
    $('#telefono').val('');
    $('#ci_ruc').val('');
    $('#codigoCliente').val('');
    $('#celular').val('');
  }

  function searchCliente(){
    $("#nombreCliente").hide();
    $("#cliente").select2().next().show();
    autocomplete_cliente();
  }

  function setPVP(id){
    producto = $("#producto"+id).val();
    cantidad = $("#cantidad"+id).val();
    pvp = producto.split("/");
    $("#pvp"+id).val(pvp[1]);
    $("#iva"+id).val(pvp[2]);
    total = cantidad * pvp[1];
    $("#total"+id).val(total);
    calcularTotal();
  }

  function calcularSubtotal(id){
    cantidad = $("#cantidad"+id).val();
    pvp = $("#pvp"+id).val();
    total = cantidad * pvp;
    $("#total"+id).val(total);
    calcularTotal();
  }

  function calcularTotal(){
    var nFilas = $("#customers tr").length;
    total0 = 0;
    total12 = 0;
    iva12 = 0;
    total = 0;
    for (var i = 1; i <= nFilas - 1; i++) {
      totalp = $("#total"+i).val();
      ivap = $("#iva"+i).val();
      if (ivap == 0) {
        total0 += parseFloat(totalp);
      }else{
        total12 += parseFloat(totalp);
      }
    }
    iva12 = parseFloat(total12) * 0.12;
    console.log(total12);
    total = parseFloat(total0) + parseFloat(total12) + parseFloat(iva12);
    $("#subtotal0").val(total0);
    $("#subtotal12").val(total12);
    $("#iva").val(iva12);
    $("#total").val(total);
  }

  function guardarFactura(){
    var update = false;
    var nombreCliente = $("#nombreCliente").val();
    var cliente = $("#cliente").val();
    var factura = $("#factura").val();
    var textMsg = "¿Desea actualizar los datos del cliente?";
    if (nombreCliente != '') {
      textMsg = "¿Desea crear nuevo cliente?";
    }
    Swal.fire({
      title: 'Esta seguro?',
      text: textMsg,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si!'
    }).then((result) => {
      if (result.value==true) {
        update = true;
      }
      email = $('#email').val();
      direccion = $('#direccion').val();
      telefono = $('#telefono').val();
      ci_ruc = $('#ci_ruc').val();
      celular = $('#celular').val();
      codigoCliente = $('#codigoCliente').val();
      //datos de la tabla
      var datos = [];
      var nFilas = $("#customers tr").length;
      for (var i = 1; i <= nFilas - 1; i++) {
        producto0 = $("#producto"+i).val();
        producto = producto0.split("/");
        datos[i-1] =  {
                        'total' : $("#total"+i).val() , 
                        'producto' : producto[0], 
                        'cantidad' : $("#cantidad"+i).val(),
                        'pvp' : producto[1],
                        'iva' : producto[2],
                        'codigo' : producto[3],
                      };
      }
      iva12 = parseFloat(total12) * 0.12;
      total = parseFloat(total0) + parseFloat(total12) + parseFloat(iva12);
      $("#subtotal0").val(total0);
      $("#subtotal12").val(total12);
      $("#iva").val(iva12);
      $("#total").val(total);
      Swal.fire({
        title: 'Esta seguro?',
        text: '¿Desea guardar la factura No. '+ factura+"?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
      }).then((result) => {
        if (result.value == true) {
          $('#myModal_espera').modal('show');
          $.ajax({
            type: "POST",
            url: '../controlador/cybernet/facturaC.php?guardarFactura=true',
            data: {
              'update' : update,
              'cliente' : cliente,
              'nombreCliente' : nombreCliente,
              'direccion' : direccion,
              'telefono' : telefono,
              'factura' : factura,
              'ci_ruc' : ci_ruc,
              'email' : email,
              'celular' : celular,
              'codigoCliente' : codigoCliente,
              'datos' : datos,
              'total' : total,
              'iva' : iva12,
            }, 
            success: function(response)
            {
              $('#myModal_espera').modal('hide');
              if (response) {

                response = JSON.parse(response);
                if(response.respuesta == '3')
                {
                  Swal.fire({
                       type: 'error',
                       title: 'Este documento electronico ya esta autorizado',
                       text: ''
                     });

                  }else if(response.respuesta == '1')
                  {
                    Swal.fire({
                      type: 'success',
                      title: 'Este documento electronico fue autorizado',
                      text: ''
                    }).then(() => {
                      serie = DCLinea.split(" ");
                      url = '../vista/appr/controlador/imprimir_ticket.php?mesa=0&tipo=FA&CI='+TextCI+'&fac='+TextFacturaNo+'&serie='+serie[1];
                      window.open(url, '_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });
                  }else if(response.respuesta == '2')
                  {
                    Swal.fire({
                       type: 'info',
                       title: 'XML devuelto',
                       text: ''
                     });
                    //descargar_archivos(response.url,response.ar);

                  }
                  else
                  {
                    Swal.fire({
                       type: 'info',
                       title: 'Error por: '+response,
                       text: ''
                     });

                  }
              }else{
                Swal.fire({
                  type: 'info',
                  title: 'La factura ya se autorizo',
                  text: ''
                });
              }
            }
          });
        }
      })
    })
  }

  function agregarLinea(){
    var nFilas = $("#customers tr").length;
    clave = nFilas;
    var tr = `<tr>
        <td>
          <select class="form-control input-sm" id="producto`+clave+`" onchange="setPVP('`+clave+`');">
            <option value="">Seleccione un cliente</option>
          </select>      
        </td>
        <td><input type ="text" class="form-control input-sm text-right" onkeyup="calcularSubtotal('`+clave+`');" id="cantidad`+clave+`" value ="1"/></td>
        <td>
          <input type ="text" class="form-control input-sm text-right" id="pvp`+clave+`" value ="0" disabled/>
          <input type ="hidden" class="form-control input-sm text-right" id="iva`+clave+`" />
        </td>
        <td><input type ="text" class="form-control input-sm text-right" id="total`+clave+`" value ="0" disabled/></td>
      </tr>`;
    $("#cuerpo").append(tr);
    $('#producto'+clave).select2({
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
            <input type="text" class="form-control input-sm" placeholder="Ingrese nombre del nuevo cliente" name="nombreCliente" id="nombreCliente" autocomplete="off">
          </div>
          <div class="col-sm-3 col-xs-4">
            <label>RUC/CI</label>
            <input type="input" class="form-control input-sm" name="ci_ruc" id="ci_ruc" autocomplete="off">
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
            <input type="input" class="form-control input-sm" name="email" id="email" autocomplete="off">
          </div>
          <div class="col-sm-5 col-xs-6">
            <label>Dirección</label>
            <input type="input" class="form-control input-sm" name="direccion" id="direccion" autocomplete="off">
          </div>
          <div class=" col-sm-2 col-xs-6">
            <label>Telefono convencional</label>
            <input type="input" class="form-control input-sm" name="telefono" id="telefono" autocomplete="off">
          </div>
          <div class=" col-sm-2 col-xs-6">
            <label>Telefono celular</label>
            <input type="input" class="form-control input-sm" name="celular" id="celular" autocomplete="off">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12 col-xs-12">
            <h4>Detalle del pedido</h4>
          </div>
          <div class="col-sm-12" style="
              height: 150px;
              overflow: auto;
              display: block;
              width: 100%;
          ">
            <table class="table table-responsive table-borfed thead-dark" id="customers">
              <thead>
                <tr>
                  <th width="500px">Producto</th>
                  <th width="200px">Cantidad</th>
                  <th width="200px">PVP</th>
                  <th width="200px">Total</th>
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
        <div class="row" style="padding-bottom: 10px">
          <div class="col-sm-2 col-sm-offset-10 col-xs-offset-7 col-xs-4">
            <button class="btn btn-default" onclick="agregarLinea();">Agregar línea</button>
          </div>
        </div>
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
        <div class="row" style="padding-top: 10px;">
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

<!-- Modal cliente nuevo 
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
 Fin Modal cliente nuevo-->