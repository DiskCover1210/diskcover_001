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
    $("#myModal").modal("show");
    //$("#nombreCliente").show();
    //$("#cliente").select2().next().hide();
    //$('#email').val('');
    //$('#direccion').val('');
    //$('#telefono').val('');
    //$('#ci_ruc').val('');
    //$('#codigoCliente').val('');
    //$('#celular').val('');
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
            <select class="form-control input-sm" name="tipoproceso">
              <option>CXC Clientes</option>
            </select>
          </div>
          <div class="col-sm-2">
            <label>() No.</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="no" value="1" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-sm-offset-1">
            <label>PRODUCTO</label>
            <select class="form-control input-sm">
              <option>AALTO-Ribera del Duero-españa</option>
            </select>
          </div>
          <div class="col-sm-2">
            <label>Precio Unitario</label>
            <input type="text" name="preciounitario" value="101.7900" class="form-control input-sm">
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