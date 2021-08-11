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
    catalogoLineas();
    autocomplete_cliente();
    numeroFactura();
    productos();
    provincias();
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
        }else{
          console.log("No tiene datos");
        }  
      }
    });
  }

  function llenarComboList(datos,nombre){
    var nombreCombo = $("#"+nombre);
    nombreCombo.find('option').remove();
    for (var indice in datos) {
      nombreCombo.append('<option value="' + datos[indice].codigo + '">' + datos[indice].nombre + '</option>');
    }
  }

  function calcular(){
    TextVUnit = Number.parseFloat($("#preciounitario").val());
    TextCant = Number.parseFloat($("#cantidad").val());
    TextVTotal = Number.parseFloat($("#total").val());
    producto = $("#producto").val();
    producto = producto.split("/");
    Div = producto[3];
    console.log(producto);
    //if (TextVUnit && TextCant && TextVTotal) {
      if (TextVUnit <= 0) {
        $("#preciounitario").val(1);
      }
      if (TextVTotal > 0 && TextCant == 0) {
        if (Div == "1") {
          TextCant = (TextVTotal / TextVUnit);
        }else{
          TextCant = (TextVTotal * TextVUnit);
        }
        $("#cantidad").val(parseFloat(TextCant).toFixed(4));
      }else if(TextCant > 0 && TextVTotal == 0){
        if (Div == "1") {
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
                      var url = '../vista/appr/controlador/formatoTicket.php?ticket=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0];
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
                      //url = '../vista/appr/controlador/imprimir_ticket.php?mesa=0&tipo=FA&CI='+TextCI+'&fac='+TextFacturaNo+'&serie='+serie[1];
                      //window.open(url, '_blank');
                      var url = '../vista/appr/controlador/formatoTicket.php?ticket=true&fac='+TextFacturaNo+'&serie='+serie[1]+'&CI='+TextCI+'&TC='+serie[0];
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
      })
    }
    
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
      console.log(response);
    }
    });

  }

  function ciudad(idpro)
  {
    console.log(idpro);
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
      console.log(response);
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
            <input type="text" name="factura" id="factura" value="1" class="form-control input-sm">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6 col-sm-offset-1">
            <label>PRODUCTO</label>
            <select class="form-control input-sm" id="producto" onchange="setPVP();">
              <?php
                $productos = $divisas->getProductos('FA');
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
              <img src="../../img/png/salire.png" width="25" height="30" >
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
          <div class="col-sm-2 col-sm-offset-5">
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
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="022222222">
          </div>
          <div class="col-sm-4">
            <label>Codigo Beneficiario</label>
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Codigo">
          </div>
        </div>
        <label>Apellidos y Nombres</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Ingrese el nombre del cliente">
        <label>Dirección</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Direccion">
        <label>Email Principal</label>
        <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="example@example.com">
        <div class="row">
          <div class="col-sm-4">
            <label>Número de vivienda</label>
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Numero vivienda">
          </div>
          <div class="col-sm-2">
            <label>Grupo</label>
            <input type="text" name="porcentaje" id="porcentaje" class="form-control" placeholder="Grupo">
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
            <select class="form-control input-sm" id="select_provincias" onchange="ciudad(this.value)">
              <option>01 Azuay</option>
            </select>
          </div>
          <div class="col-sm-6">
            <label>Ciudad</label>
            <select class="form-control input-sm" id="select_ciudad">
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