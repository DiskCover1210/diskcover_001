<?php
  include "../controlador/facturacion/divisasC.php";
  $divisas = new divisasC();
  $serie = $_SESSION['INGRESO']['Serie_FA'];
  $codigo = ReadSetDataNum("FA_SERIE_".$serie , True, False);
?>
<style type="text/css">
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
    catalogoLineas();
    autocomplete_cliente();
    provincias();
    $("#nombreCliente").hide();
    //enviar datos del cliente
    $('#cliente').on('select2:select', function (e) {
      var data = e.params.data.data;
      $('#email').val(data.email);
      $('#direccion').val(data.direccion);
      $('#telefono').val(data.telefono);
      $('#ci_ruc').val(data.ci_ruc);
      $('#codigoCliente').val(data.codigo);
      $('#celular').val(data.celular);
      console.log(data);
    });
  });

  function autocomplete_cliente(){
    $('#cliente').select2({
      placeholder: 'Seleccione un cliente',
      ajax: {
        url:   '../controlador/facturacion/divisasC.php?cliente=true',
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
    var src ="../vista/modales.php?FCliente=true";
     $('#FCliente').attr('src',src).show();
  }

  function setPVP(){
    producto0 = $("#producto").val();
    producto = producto0.split("/");
    $("#preciounitario").val(producto[2]);
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
        $("#factura").val(datos.codigo);
      }
    });
  }

  function productos(){
    DCLinea = $("#DCLinea").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/divisasC.php?productos=true',
      data: {
        'DCLinea' : DCLinea,
      }, 
      success: function(data)
      {
        if (data) {
          datos = JSON.parse(data);
          llenarComboList(datos,'producto')
          setPVP();
        }else{
          console.log("No tiene datos");
        }  
      }
    });
  }

  function catalogoLineas(){
    fecha = $("#fecha").val();
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/divisasC.php?catalogoLineas=true',
      data: {
        'fecha' : fecha,
      }, 
      success: function(data)
      {
        if (data) {
          datos = JSON.parse(data);
          llenarComboList(datos,'DCLinea')
          numeroFactura();
          productos();
        }else{
          console.log("No tiene datos");
        }  
      }
    });
  }

  function calcular(){
    TextVUnit = Number.parseFloat($("#preciounitario").val());
    TextCant = Number.parseFloat($("#cantidad").val());
    TextVTotal = Number.parseFloat($("#total").val());
    producto = $("#producto").val();
    producto = producto.split("/");
    Div = producto[3];
    if (TextVUnit <= 0) {
      $("#preciounitario").val(1);
    }
    if (TextVTotal > 0 && TextCant == 0) {
      if (Div == "1") {
        TextCant = (TextVTotal / TextVUnit);
      }else{
        TextCant = (TextVTotal * TextVUnit);
      }
      $("#cantidad").val(parseFloat(TextCant).toFixed(2));
    }else if(TextCant > 0 && TextVTotal == 0){
      if (Div == "1") {
        TextVTotal = (TextCant / TextVUnit);
      }else{
        TextVTotal = (TextCant * TextVUnit);
      }
      $("#total").val(parseFloat(TextVTotal).toFixed(2));
    }
  }

  function aceptar(){
    producto = $("#producto").val();
    pvp = $("#preciounitario").val();
    total = $("#total").val();
    cantidad = $("#cantidad").val();
    producto = producto.split("/");
    var table = document.getElementById('customers');
    var rowLength = table.rows.length;
    var tr = `<tr>
      <td><input type="text" id="codigo`+rowLength+`" value="`+producto[0]+`" disabled class="sinborde"></td>
      <td><input type="text" id="cantidad`+rowLength+`" value="`+cantidad+`" disabled class="sinborde"></td>
      <td><input type="text" id=boni"`+rowLength+`" value="`+cantidad+`" disabled class="sinborde"></td>
      <td><input type="text" id="producto`+rowLength+`" value="`+producto[1]+`" disabled class="sinborde"></td>
      <td><input type="text" id="pvp`+rowLength+`" value="`+pvp+`" disabled class="sinborde"></td>
      <td><input type="text" id="total`+rowLength+`" value="`+total+`" disabled class="sinborde"></td>
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
    total = 0;
    for(var i=1; i<rowLength; i++){
      total += parseFloat($("#total"+i).val());
    }
    $("#total0").val(parseFloat(total).toFixed(2));
    $("#totalFac").val(parseFloat(total).toFixed(2));
    $("#efectivo").val(parseFloat(total).toFixed(2));
  }

  function guardarFactura(){
    validarDatos = $("#total").val();
    totalFac = $("#totalFac").val();
    codigoCliente = $("#codigoCliente").val();
    console.log(codigoCliente);
    if (codigoCliente == '' ) {
      Swal.fire({
        type: 'info',
        title: 'Ingrese el cliente para la factura',
        text: ''
      });
    }else if (totalFac <= 0 ) {
      Swal.fire({
        type: 'info',
        title: 'Ingrese una o más lineas para generar la factura',
        text: ''
      });
    }else{
      
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
                      cambio = $("#cambio").val();
                      efectivo = $("#efectivo").val();
                      var url = '../vista/appr/controlador/formatoTicket.php?ticket=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0]+'&efectivo='+efectivo+'&saldo='+cambio;
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

                  }else if(response.respuesta == '4')
                  {
                    Swal.fire({
                      type: 'success',
                      title: 'Factura guardada correctamente',
                      text: ''
                    }).then(() => {
                      serie = DCLinea.split(" ");
                      cambio = $("#cambio").val();
                      efectivo = $("#efectivo").val();
                      var url = '../vista/appr/controlador/formatoTicket.php?ticket=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0]+'&efectivo='+efectivo+'&saldo='+cambio;
                      window.open(url,'_blank');
                      location.reload();
                      //imprimir_ticket_fac(0,TextCI,TextFacturaNo,serie[1]);
                    });
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
    }
    
  }

  function calcularSaldo(){
    efectivo = $("#efectivo").val();
    total = $("#totalFac").val();
    saldo = efectivo - total;
    $("#cambio").val(parseFloat(saldo).toFixed(2));
  }

  function provincias()
  {
   var option ="<option value=''>Seleccione provincia</option>"; 
     $.ajax({
      url: '../controlador/detalle_estudianteC.php?provincias=true',
      type:'post',
      dataType:'json',
     // data:{usu:usu,pass:pass},
      beforeSend: function () {
                   $("#select_ciudad").html("<option value=''>Seleccione provincia</option>");
             },
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#select_provincias').html(option);
    }
    });

  }

  function ciudad(idpro)
  {
    var option ="<option value=''>Seleccione ciudad</option>"; 
    //var idpro = $('#select_provincias').val();
    if(idpro !='')
    {
     $.ajax({
      url: '../controlador/detalle_estudianteC.php?ciudad=true',
      type:'post',
      dataType:'json',
      data:{idpro:idpro},
      success: function(response){
      response.forEach(function(data,index){
        option+="<option value='"+data.Codigo+"'>"+data.Descripcion_Rubro+"</option>";
      });
       $('#select_ciudad').html(option);
    }
    });
   } 

  }


</script>
<div class="container" id="container1">
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>Fecha</label>
            <input type="date" class="form-control input-sm" name="fecha" id="fecha" value="<?php echo date('Y-m-d'); ?>" onchange="numeroFactura();catalogoLineas();">
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
            <input type="hidden" name="direccion" id="direccion">
            <input type="hidden" name="ci" id="ci_ruc">
            <input type="hidden" name="email" id="email">
            <input type="hidden" name="fechaEmision" id="fechaEmision" value="<?php echo date('Y-m-d'); ?>">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label class="text-right">TIPO DE PROCESO</label>
          </div>
          <div class="col-sm-4">
            <select class="form-control input-sm" name="DCLinea" id="DCLinea" onchange="numeroFactura();productos();">
            </select>
          </div>
          <div class="col-sm-2">
            <label id="numeroSerie" class="red">() No.</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="factura" id="factura" value="1" class="form-control input-sm text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-sm-offset-1">
            <label>PRODUCTO</label>
            <select class="form-control input-sm" id="producto" onchange="setPVP();">
            </select>
          </div>
          <div class="col-sm-2">
            <label>Precio Unitario</label>
            <input type="text" name="preciounitario" id="preciounitario" value="101.7900" class="form-control input-sm text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 col-sm-offset-1">
            <label>TOTAL EN S/.</label>
            <input type="text" name="total" id="total" value="0.00" class="form-control input-sm text-right">
          </div>
          <div class="col-sm-4">
            <label>Cantidad Cambio</label>
            <input type="text" name="cantidad" id="cantidad" value="0.00" class="form-control input-sm text-right">
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
          <div class="col-sm-12">
            <div class="tab-content" style="background-color:#E7F5FF">
              <div id="home" class="tab-pane fade in active">
                <div class="table-responsive" id="tabla_" style="overflow-y: scroll; height:250px; width: auto;">
                  <div class="sombra" style>
                    <table border class="table table-striped table-hover" id="customers" tabindex="14" >
                      <thead>
                        <tr>
                          <th style="border: #b2b2b2 1px solid;">Código</th>
                          <th style="border: #b2b2b2 1px solid;">Cantidad</th>
                          <th style="border: #b2b2b2 1px solid;">Cant Bonf</th>
                          <th style="border: #b2b2b2 1px solid;">Producto</th>
                          <th style="border: #b2b2b2 1px solid;">Precio</th>
                          <th style="border: #b2b2b2 1px solid;">Total</th>
                        </tr>
                      </thead>
                      <tbody id="cuerpo">
                      </tbody>
                    </table>          
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>Total Tarifa 0%</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="total0" id="total0" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <label>Total Factura</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="totalFac" id="totalFac" class="form-control input-sm red text-right" value="0.00" readonly>
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
            <input type="text" name="total12" id="total12" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <label>Total Fact. (ME)</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="totalFacMe" id="totalFacMe" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <a title="Guardar" class="btn btn-default" tabindex="22" title="Salir del panel" href="facturacion.php?mod=facturacion">
              <img src="../../img/png/salire.png" width="25" height="30" >
            </a>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-1">
            <label>I.V.A. 12%</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="iva12" id="iva12" class="form-control input-sm red text-right" value="0.00" readonly>
          </div>
          <div class="col-sm-2">
            <label>EFECTIVO</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="efectivo" id="efectivo" class="form-control input-sm red text-right" value="0.00" onkeyup="calcularSaldo();">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 col-sm-offset-5">
            <label>Cambio</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="cambio" id="cambio" class="form-control input-sm red text-right" value="0.00">
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
          <iframe  id="FCliente" width="100%" height="400px" marginheight="0" frameborder="0"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>
 <!-- Fin Modal cliente nuevo-->