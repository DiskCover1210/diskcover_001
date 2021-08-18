<?php
  include "../controlador/facturacion/listar_facturasC.php";
  $serie = $_SESSION['INGRESO']['Serie_FA'];
  $codigo = ReadSetDataNum("FA_SERIE_".$serie , True, False);
  $facturar = new listar_facturasC();
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
    //autocomplete_cliente();
    series();
    secuencial();
    envioDatos();
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
    
    //habilitar o desahibilitar botones
    NombreUsuario = $('#nombre_completo').val();
    if (NombreUsuario == 'Administrador de Red') {
      $("#Cambio_Emision_Facturas").attr('disabled', false); 
      $("#Cambio_Vencimiento_Facturas").attr('disabled',false);
      $("#Cambia_Autorizacion_Facturas").attr('disabled',false);
      $("#Cambia_Numero_de_Facturas").attr('disabled',false);
      $("#Reprocesar_Saldos_Facturas").attr('disabled',false);
      $("#Eliminar_Facturas").attr('disabled',false);
      $("#Revertir_Facturas").attr('disabled',false);
      $("#Actualizar_Representantes").attr('disabled',false);
      $("#Liberar_FA_SRI").attr('disabled',false);
      $("#Ejecutivo").attr('disabled',false);
      $("#Kardex").attr('disabled',false);
    }else{
      $("#Cambio_Emision_Facturas").attr('disabled',true);
      $("#Cambio_Vencimiento_Facturas").attr('disabled',true);
      $("#Cambia_Autorizacion_Facturas").attr('disabled',true);
      $("#Cambia_Numero_de_Facturas").attr('disabled',true);
      $("#Reprocesar_Saldos_Facturas").attr('disabled',true);
      $("#Eliminar_Facturas").attr('disabled',true);
      $("#Revertir_Facturas").attr('disabled',true);
      $("#Actualizar_Representantes").attr('disabled',true);
      $("#Liberar_FA_SRI").attr('disabled',true);
      $("#Ejecutivo").attr('disabled',true);
      $("#Kardex").attr('disabled',true);
    }
    
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

  function series(){
    TC = $("#TC").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/listar_facturasC.php?serie=true',
      data: {
        'TC' : TC,
      }, 
      success: function(data)
      {
        const $select = $("#serie");
        $select.empty();
        datos = JSON.parse(data);
        llenarComboList(datos,'serie');
      }
    });
  }

  function secuencial(){
    $('#myModal_espera').modal('show');
    TC = $("#TC").val();
    serie = $("#serie").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/listar_facturasC.php?secuencial=true',
      data: {
        'TC' : TC,
        'serie' : serie,
      }, 
      success: function(data)
      {
        const $select = $("#secuencial");
        $select.empty();
        datos = JSON.parse(data);
        llenarComboList(datos,'secuencial');
        $('#myModal_espera').modal('hide');
      }
    });
  }

  function envioDatos(){
    secuencial0 = $("#secuencial").val();
    datos = secuencial0.split('/');
    autorizacion = datos[0];
    clave_acceso = datos[1];
    codigo = datos[2];
    cliente = datos[3];
    console.log(datos);
    $("#autorizacion").val(autorizacion);
    $("#clave_acceso").val(clave_acceso);
    $("#codigo_cliente").val(codigo);
    $("#cliente").val(cliente);
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
        $("#cantidad").val(parseFloat(TextCant).toFixed(4));
      }else if(TextCant > 0 && TextVTotal == 0){
        if (producto[3]) {
          TextVTotal = (TextCant / TextVUnit);
        }else{
          TextVTotal = (TextCant * TextVUnit);
        }
        $("#total").val(parseFloat(TextVTotal).toFixed(4));
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

    datosLineas = [];
    var year = new Date().getFullYear();
    var rowLength = table.rows.length;
    total = 0;
    key = 0;
    for(var i=1; i<rowLength; i+=1){
      datosLineas[key] = {
        'Codigo' : $("#codigo"+i).val(),
        'CodigoL' : $("#codigo"+i).val(),
        'Producto' : $("#producto"+i).val(),
        'Precio' : $("#pvp"+i).val(),
        'Total_Desc' : 0,
        'Total_Desc2' : 0,
        'Iva' : 0,
        'Total' : $("#total"+i).val(),
        'MiMes' : '',
        'Periodo' : year,
      };
      key++;
    }
    codigoCliente = $("#codigoCliente").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/divisasC.php?guardarLineas=true',
      data: {
        'codigoCliente' : codigoCliente,
        'datos' : datosLineas,
      }, 
      success: function(data)
      {
        
      }
    });
  }

  function calcularTotal(){
    var table = document.getElementById('customers');
    var rowLength = table.rows.length;
    console.log()
    total = 0;
    for(var i=1; i<rowLength; i++){
      total += parseFloat($("#total"+i).val());
    }
    console.log(total);
    $("#total0").val(parseFloat(total).toFixed(4));
    $("#totalFac").val(parseFloat(total).toFixed(4));
    $("#efectivo").val(parseFloat(total).toFixed(4));
  }

  function guardarFactura(){
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
        TextRepresentante = null;
        DCLinea = $("#DCLinea").val();
        TxtDireccion = $("#direccion").val();
        TxtTelefono = null;
        TextFacturaNo = $("#factura").val();
        TxtGrupo = null;
        TextCI = $("#ci_ruc").val();
        TD_Rep = $("#ci_ruc").val();
        TxtEmail = $("#email").val();
        TxtDirS = $("#direccion").val();
        TextCheque = null;
        DCBanco = null;
        chequeNo = $("#chequeNo").val();
        TxtEfectivo = $("#efectivo").val();
        TxtNC = null;
        DCNC = null;
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
            url: '../controlador/facturacion/divisasC.php?guardarFactura=true',
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
                      //url = '../vista/appr/controlador/imprimir_ticket.php?mesa=0&tipo=FA&CI='+TextCI+'&fac='+TextFacturaNo+'&serie='+serie[1];
                      //window.open(url, '_blank');
                      var url = '../controlador/detalle_estudianteC.php?ver_fac=true&codigo='+TextFacturaNo+'&ser='+serie[1]+'&ci='+TextCI;
                      window.open(url,'_blank');
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
          <div class="col-sm-12">
            <a id="salir" title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/salire.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/impresora.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/fa.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/generapdf.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/mes.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/calendario.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/modificar.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/data.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/document.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/archivero.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/copiare.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/team.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/bloqueo.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/borrar_archivo.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/modificar1.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/sriama.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/sri.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/hom.png" width="25" height="30">
            </a>
            <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
              <img src="../../img/png/modificar1.png" width="25" height="30">
            </a>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-1">
            <input type="hidden" id="nombre_completo" value="<?php echo $_SESSION['INGRESO']['Nombre_Completo']; ?>" class="form-control input-sm">
            <label>Tipo Documento</label>
          </div>
          <div class="col-sm-1">
            <select class="form-control input-sm" id="TC" onchange="series();secuencial();" style="width: 80px;">
              <?php
                $cuentas = $facturar->factura_formatos();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['TC']."'>".$cuenta['TC']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-1">
            <label>Serie</label>
          </div>
          <div class="col-sm-1">
            <select class="form-control input-sm" id="serie" onchange="secuencial();" style="width: 80px;">
              <option>001001</option>
            </select>
          </div>
          <div class="col-sm-1">
            <label>Secuencial No.</label>
          </div>
          <div class="col-sm-1">
            <select class="form-control input-sm" id="secuencial" onchange="envioDatos();" style="width: 80px;">
              <option>0000000</option>
            </select>
          </div>
          <div class="col-sm-2">
            <input type="date" class="form-control input-sm" name="">
          </div>
          <div class="col-sm-1">
            <input type="text" name="" value="9999" class="form-control input-sm" disabled>
          </div>
          <div class="col-sm-2">
            <input type="text" name="" value="99999999999" class="form-control input-sm" id="codigo_cliente">
          </div>
          <div class="col-sm-1">
            <label>Pendiente</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-1">
            <label>Clave</label>
          </div>
          <div class="col-sm-5">
            <input type="text" name="" class="form-control input-sm" id="clave_acceso">
          </div>
          <div class="col-sm-1">
            <label>Autorización</label>
          </div>
          <div class="col-sm-5">
            <input type="text" name="" class="form-control input-sm" id="autorizacion">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <label>En Bloque</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-1">
            <label>Desde:</label>
          </div>
          <div class="col-sm-1">
            <input type="text" name="" value="000000000" class="form-control input-sm">
          </div>
          <div class="col-sm-1">
            <label>Hasta:</label>
          </div>
          <div class="col-sm-1">
            <input type="text" name="" value="000000000" class="form-control input-sm">
          </div>
          <div class="col-sm-1">
            <label>A</label>
            <label>A</label>
          </div>
          <div class="col-sm-1">
            <input type="date" name="" value="<?php echo date('Y-m-d'); ?>" class="form-control input-sm">
          </div>
          <div class="col-sm-1">
            <input type="checkbox" name="">
            <label>Sin Deuda Pendiente</label>
          </div>
          <div class="col-sm-1">
            <input type="checkbox" name="">
            <label>Imprimir Solo Copia</label>
          </div>
          <div class="col-sm-2">
            <input type="checkbox" name="">
            <label>Imprimir sin codigo de alumna</label>
          </div>
          <div class="col-sm-2">
            <input type="submit" name="" value="Actualizar Alumnos" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <label>Cliente:</label>
          </div>
          <div class="col-sm-10">
            <input type="text" name="" class="form-control input-sm" id="cliente">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <label>No. de Bultos:</label>
          </div>
          <div class="col-sm-10">
            <input type="text" name="" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <label>Entregado en:</label>
          </div>
          <div class="col-sm-10">
            <input type="text" name="" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <label>Observación:</label>
          </div>
          <div class="col-sm-10">
            <input type="text" name="" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <label>Nota:</label>
          </div>
          <div class="col-sm-10">
            <input type="text" name="" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <label>Detalle de Factura</label>
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
                  <th width="200px">aaaa</th>
                  <th width="200px">aaa</th>
                  <th width="200px">aaa</th>
                  <th width="500px">aaa</th>
                  <th width="200px">aaa</th>
                  <th width="200px">aaa</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td width="200px"></td>
                  <td width="200px"></td>
                  <td width="200px"></td>
                  <td width="500px"></td>
                  <td width="200px"></td>
                  <td width="200px"></td>
                </tr>
              </tbody>
            </table>          
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-2">
            <label>Subtotal sin IVA</label>
          </div>
          <div class="col-sm-2">
            <label>Subtotal con IVA</label>
          </div>
          <div class="col-sm-1">
            <label>Descuento</label>
          </div>
          <div class="col-sm-1">
            <label>Subtotal</label>
          </div>
          <div class="col-sm-1">
            <label>I.V.A.</label>
          </div>
          <div class="col-sm-2">
            <label>Subtotal Servicio</label>
          </div>
          <div class="col-sm-2">
            <label>Total Facturado</label>
          </div>
          <div class="col-sm-1">
            <label>Saldo Actual</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <input type="text" name="" class="form-control input-sm">
          </div>
          <div class="col-sm-2">
            <input type="text" name="" class="form-control input-sm">
          </div>
          <div class="col-sm-1">
            <input type="text" name="" class="form-control input-sm">
          </div>
          <div class="col-sm-1">
            <input type="text" name="" class="form-control input-sm">
          </div>
          <div class="col-sm-1">
            <input type="text" name="" class="form-control input-sm">
          </div>
          <div class="col-sm-2">
            <input type="text" name="" class="form-control input-sm">
          </div>
          <div class="col-sm-2">
            <input type="text" name="" class="form-control input-sm">
          </div>
          <div class="col-sm-1">
            <input type="text" name="" class="form-control input-sm">
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