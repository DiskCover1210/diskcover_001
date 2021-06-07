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
  $('body').on("keydown", function(e) { 
    if ( e.which === 27) {
      document.getElementById("").focus();
      e.preventDefault();
    }
  });
  $(document).ready(function () {
    autocomplete_cliente();
    //enviar datos del cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#email').val(data.email);
      $('#direccion').val(data.direccion);
      $('#telefono').val(data.telefono);
      $("#subtotal0").val(parseFloat(0.00).toFixed(2));
      $("#subtotal12").val(parseFloat(0.00).toFixed(2));
      $("#iva").val(parseFloat(0.00).toFixed(2));
      $("#total").val(parseFloat(0.00).toFixed(2));
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

  function guardarPension(){
    validarDatos = $("#total").val();
    saldoTotal = $("#saldoTotal").val();
    if (saldoTotal > 0 ) {
      Swal.fire({
        type: 'info',
        title: 'Debe pagar la totalidad de la factura',
        text: ''
      });
    }else if (validarDatos <= 0 ) {
      Swal.fire({
        type: 'info',
        title: 'Ingrese los datos necesarios para guardar la factura',
        text: ''
      });
    }else{
      var update = false;
      //var update = confirm("¿Desea actualizar los datos del cliente?");
      Swal.fire({
        title: 'Esta seguro?',
        text: "¿Desea actualizar los datos del cliente?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si!'
      }).then((result) => {
        if (result.value==true) {
          update = true;
        }else{
          update = false;
        }
        TextRepresentante = $("#persona").val();
        DCLinea = $("#DCLinea").val();
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
        chequeNo = $("#chequeNo").val();
        TxtEfectivo = $("#efectivo").val();
        TxtNC = $("#cuentaNC").val();
        DCNC = $("#abono").val();
        Fecha = $("#fechaEmision").val();
        Total = $("#total").val();
        codigoCliente = $("#codigoCliente").val();
        //var confirmar = confirm("Esta seguro que desea guardar \n La factura No."+TextFacturaNo);
        Swal.fire({
          title: 'Esta seguro?',
          text: "Esta seguro que desea guardar \n La factura No."+TextFacturaNo,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si!'
        }).then((result) => {
          if (result.value==true) {
            $('#myModal_espera').modal('show');
            $.ajax({
            type: "POST",
            url: '../controlador/facturacion/facturar_pensionC.php?guardarPension=true',
            data: {
              'update' : update,
              'DCLinea' : DCLinea,
              'Total' : Total,
              'TextRepresentante' : TextRepresentante,
              'TxtDireccion' : TxtDireccion,
              'TxtTelefono' : TxtTelefono,
              'TextFacturaNo' : TextFacturaNo,
              'TxtGrupo' : TxtGrupo,
              'chequeNo' : chequeNo,
              'TextCI' : TextCI,
              'TD_Rep' : TD_Rep,
              'TxtEmail' : TxtEmail,
              'TxtDirS' : TxtDirS,
              'codigoCliente' : codigoCliente,
              'TextCheque' : TextCheque,
              'DCBanco' : DCBanco,
              'TxtEfectivo' : TxtEfectivo,
              'TxtNC' : TxtNC,
              'Fecha' : Fecha,
              'DCNC' : DCNC, 
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
    
  }

</script>
<div class="container" id="container1">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-3 col-xs-1 text-center">
            <label>Serie</label>
          </div>
          <div class="col-sm-3 col-xs-4">
            <input type="input" class="form-control input-sm" name="serie" id="serie">
          </div>
          <div class="col-sm-3 col-xs-3 text-center">
            <label>Factura No</label>
          </div>
          <div class="col-sm-3 col-xs-4">
            <input type="input" class="form-control input-sm" name="factura" id="factura">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3 col-xs-2 text-right">
            <label class="text-right">Cliente</label>
          </div>
          <div class="col-sm-6 col-xs-10">
            <select class="form-control input-sm" id="cliente" name="cliente" tabindex="5">
              <option value="">Seleccione un cliente</option>
            </select>
            <input type="hidden" name="codigoCliente" id="codigoCliente">
          </div>
          <div class="col-sm-3 col-xs-12">
            <button type="button" class="btn" data-toggle="modal" data-target="#myModal">Cliente Nuevo</button>
          </div>
        </div>
      
        <div class="row">
          <div class="col-sm-2 col-xs-2 text-right">
            <label>Dirección</label>
          </div>
          <div class="col-sm-5 col-xs-10">
            <input type="input" class="form-control input-sm" name="direccion" id="direccion">
          </div>
          <div class="col-sm-1 col-xs-2 text-right">
            <label>Telefono</label>
          </div>
          <div class=" col-sm-2 col-xs-10">
            <input type="input" class="form-control input-sm" name="telefono" id="telefono">
          </div>
          <div class="col-sm-1 col-xs-2 text-right">
            <label>Email</label>
          </div>
          <div class="col-sm-2 col-xs-10">
            <input type="input" class="form-control input-sm" name="email" id="email">
          </div>
        </div>

        <br>
        <div class="row">
          <div class="col-sm-12">
            <h2>Detalle del pedido</h2>
          </div>
          <div class="col-sm-12" style="
              height: 150px;
              overflow: auto;
              display: block;
          ">
            <table class="table table-responsive table-borfed thead-dark" id="customers" tabindex="14">
              <thead>
                <tr>
                  <th>Producto</th>
                  <th>Cantidad</th>
                  <th>PVP</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody id="cuerpo">
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
                <img src="../../img/png/save.png" width="25" height="30" onclick="guardarPension();">
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