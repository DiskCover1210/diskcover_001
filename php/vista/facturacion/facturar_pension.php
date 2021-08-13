<?php
  include "../controlador/facturacion/facturar_pensionC.php";
  $facturar = new facturar_pensionC();
?>

<script type="text/javascript">
  $('body').on("keydown", function(e) { 
    if ( e.which === 27) {
      document.getElementById("DCLinea").focus();
      e.preventDefault();
    }
  });
  var total = 0;
  var total0 = 0;
  var total12 = 0;
  var iva12 = 0;
  var descuento = 0;
  $(document).ready(function () {
    autocomplete_cliente();
    catalogoLineas();
    totalRegistros();
    verificarTJ();
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
            cursos.append('<option value="' + datos[indice].id +" "+datos[indice].text+ ' ">' + datos[indice].text + '</option>');
          }
        }else{
          console.log("No tiene datos");
        }
        numeroFactura();            
      }
    });
    $('#myModal_espera').modal('hide');
  }

  function imprimir_ticket_fac(mesa,ci,fac,serie)
  {
    var html='<iframe style="width:100%; height:50vw;" src="../appr/controlador/imprimir_ticket.php?mesa='+mesa+'&tipo=FA&CI='+ci+'&fac='+fac+'&serie='+serie+'" frameborder="0" allowfullscreen></iframe>';
    $('#contenido').html(html); 
    $("#myModal").modal();
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
              <td><input class="sinborde" type="checkbox" id="checkbox`+clave+`" onclick="totalFactura('checkbox`+clave+`','`+subtotal+`','`+datos[indice].iva+`','`+datos[indice].descuento+`','`+datos.length+`')" name="`+datos[indice].mes+`"></td>
              <td><input class="sinborde" type ="text" id="Mes`+clave+`" value ="`+datos[indice].mes+`" disabled/></td>
              <td><input class="sinborde" type ="text" id="Codigo`+clave+`" value ="`+datos[indice].codigo+`" disabled/></td>
              <td><input class="sinborde" type ="text" id="Periodo`+clave+`" value ="`+datos[indice].periodo+`" disabled/></td>
              <td><input class="sinborde" type ="text" id="Producto`+clave+`" value ="`+datos[indice].producto+`" disabled/></td>
              <td><input class="sinborde text-right" size="10px" type ="text" id="valor`+clave+`" value ="`+parseFloat(datos[indice].valor).toFixed(2)+`" disabled/></td>
              <td><input class="sinborde text-right" size="10px" type ="text" id="descuento`+clave+`" value ="`+parseFloat(datos[indice].descuento).toFixed(2)+`" disabled/></td>
              <td><input class="sinborde text-right" size="10px" type ="text" id="descuento2`+clave+`" value ="`+parseFloat(datos[indice].descuento2).toFixed(2)+`" disabled/></td>
              <td><input class="sinborde text-right" size="10px" type ="text" id="subtotal`+clave+`" value ="`+parseFloat(subtotal).toFixed(2)+`" disabled/></td>
              <input size="10px" type ="hidden" id="CodigoL`+clave+`" value ="`+datos[indice].CodigoL+`"/>
              <input size="10px" type ="hidden" id="Iva`+clave+`" value ="`+datos[indice].iva+`"/>
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

  function historiaCliente(){
    codigoCliente = $('#codigoCliente').val();
    $('#myModal_espera').modal('show');
    
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?historiaCliente=true',
      data: {'codigoCliente' : codigoCliente }, 
      success: function(data)
      {
        $('#myModal_espera').modal('hide');
        $('#myModalHistoria').modal('show');
        if (data) {
          datos = JSON.parse(data);
          clave = 0;
          $("#cuerpoHistoria").empty();
          for (var indice in datos) {
            var tr = `<tr>
              <td><input class="sinborde" size="1" type ="text" id="TD`+clave+`" value ="`+datos[indice].TD+`" disabled/></td>
              <td><input class="sinborde" size="7" type ="text" id="Fecha`+clave+`" value ="`+datos[indice].Fecha+`" disabled/></td>
              <td><input class="sinborde" size="6" type ="text" id="Serie`+clave+`" value ="`+datos[indice].Serie+`" disabled/></td>
              <td><input class="sinborde" size="6" type ="text" id="Factura`+clave+`" value ="`+datos[indice].Factura+`" disabled/></td>
              <td><input class="sinborde" size="70" type ="text" id="Detalle`+clave+`" value ="`+datos[indice].Detalle+`" disabled/></td>
              <td><input class="sinborde" size="2" class="text-right" type ="text" id="Anio`+clave+`" value ="`+datos[indice].Anio+`" disabled/></td>
              <td><input class="sinborde" size="10" type ="text" id="Mes`+clave+`" value ="`+datos[indice].Mes+`" disabled/></td>
              <td><input class="sinborde" size="6" class="text-right" size="10px" type ="text" id="Total`+clave+`" value ="`+parseFloat(datos[indice].Total).toFixed(2)+`" disabled/></td>
              <td><input class="sinborde" size="6" class="text-right" type ="text" id="Abonos`+clave+`" value ="`+parseFloat(datos[indice].Abonos).toFixed(2)+`" disabled/></td>
              <td><input size="2" class="text-right sinborde" type ="text" id="Mes_No`+clave+`" value ="`+datos[indice].Mes_No+`" disabled/></td>
              <td><input size="2" class="text-right sinborde" type ="text" id="No`+clave+`" value ="`+datos[indice].No+`" disabled/></td>
            </tr>`;
            $("#cuerpoHistoria").append(tr);
            clave++;
          }
        }else{
          console.log("No tiene datos");
        }            
      }
    });
  }

  function historiaClienteExcel(){
    codigoCliente = $('#codigoCliente').val();
    url = '../controlador/facturacion/facturar_pensionC.php?historiaClienteExcel=true&codigoCliente='+codigoCliente;
    window.open(url, '_blank');
  }

  function historiaClientePDF(){
    codigoCliente = $('#codigoCliente').val();
    url = '../controlador/facturacion/facturar_pensionC.php?historiaClientePDF=true&codigoCliente='+codigoCliente;
    window.open(url, '_blank');
  }

  function enviarHistoriaCliente(){
    codigoCliente = $('#codigoCliente').val();
    email = $('#email').val();
    //url = '../controlador/facturacion/facturar_pensionC.php?enviarCorreo=true&codigoCliente='+codigoCliente+'&email='+email;
    //window.open(url, '_blank');
    $('#myModal_espera').modal('show');
    $.ajax({
      type: "POST",                 
      url: '../controlador/facturacion/facturar_pensionC.php?enviarCorreo=true&codigoCliente='+codigoCliente,
      data: {'email' : email }, 
      success: function(data)
      {
        $('#myModal_espera').modal('hide');
        Swal.fire({
          type: 'success',
          title: 'Correo enviado correctamente',
          text: ''
        });
      }
    });
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

  function totalFactura(id,valor,iva,descuento1,datos){
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
    } else {
      if (iva == 0) {
        total0 -= valor;  
      }else{
        total12 -= valor;
      }
      descuento -= descuento1;
      total -= valor;
    }
    datosLineas = [];
    key = 0;
    for (var i = 1; i <= datos; i++) {
      datosId = 'checkbox'+i;
      datosCheckBox = document.getElementById(datosId);
      if (datosCheckBox.checked == true) {
        datosLineas[key] = {
          'Codigo' : $("#Codigo"+i).val(),
          'CodigoL' : $("#CodigoL"+i).val(),
          'Producto' : $("#Producto"+i).val(),
          'Precio' : $("#valor"+i).val(),
          'Total_Desc' : $("#descuento"+i).val(),
          'Total_Desc2' : $("#descuento2"+i).val(),
          'Iva' : $("#Iva"+i).val(),
          'Total' : $("#subtotal"+i).val(),
          'MiMes' : $("#Mes"+i).val(),
          'Periodo' : $("#Periodo"+i).val(),
        };
        key++;
      }
    }
    codigoCliente = $("#codigoCliente").val();
    $("#total12").val(parseFloat(total12).toFixed(2));
    $("#descuento").val(parseFloat(descuento).toFixed(2));
    $("#iva12").val(parseFloat(iva12).toFixed(2));
    $("#total").val(parseFloat(total).toFixed(2));
    $("#total0").val(parseFloat(total0).toFixed(2));
    $("#valorBanco").val(parseFloat(total).toFixed(2));
    $("#saldoTotal").val(parseFloat(0).toFixed(2));

    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?guardarLineas=true',
      data: {
        'codigoCliente' : codigoCliente,
        'datos' : datosLineas,
      }, 
      success: function(data)
      {
        
      }
    });    
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
    banco = $("#valorBanco").val();
    saldo = total - banco - efectivo - abono;
    $("#saldoTotal").val(saldo.toFixed(2));
  }

  function numeroFactura(){
    DCLinea = $("#DCLinea").val();
    console.log(DCLinea);
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
        $("#factura").val(datos.codigo);
      }
    });
  }

  function totalRegistros(){
    $.ajax({
      type: "POST",
      url: '../controlador/facturacion/facturar_pensionC.php?cliente=true&total=true',
      data: {
        'q' : '',
      }, 
      success: function(data)
      {
        datos = JSON.parse(data);
        $("#registros").val(datos.registros);
      }
    });
  }

  function verificarTJ(){
    TC = $("#cuentaBanco").val();
    TC = TC.split("/");
    //console.log("entra");
    if (TC[1] == "TJ") {
      $("#divInteres").show();
    }else{
      $("#divInteres").hide();
    }
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
        DCBanco = DCBanco.split("/");
        DCBanco = DCBanco[0];
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
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a  href="./panel.php" title="Salir de modulo" class="btn btn-default">
          <img src="../../img/png/team.png" width="25" height="30">
        </a>
      </div>
      <!--
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
      -->  
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a title="Historia del cliente"  class="btn btn-default" onclick="historiaCliente();">
          <img src="../../img/png/document.png" width="25" height="30">
        </a>
      </div>
      <!--
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
      --> 
    </div>
  </div>
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-2">
            <input type="hidden" id="Autorizacion">
            <input type="hidden" id="Cta_CxP">
            <select class="form-control input-sm" name="DCLinea" id="DCLinea" tabindex="1" onchange="numeroFactura();">
              
            </select>
          </div>
          <div class="col-sm-2 text-right">
            <label>Fecha emisión</label>
          </div>
          <div class="col-sm-2">
            <input tabindex="2" type="date" name="fechaEmision" id="fechaEmision" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" onchange="catalogoLineas();">
          </div>
          <div class="col-sm-2 text-right">
            <label>Fecha vencimiento</label>
          </div>
          <div class="col-sm-2">
            <input type="date" tabindex="3" name="fechaVencimiento" id="fechaVencimiento" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>" onchange="catalogoLineas();">
          </div>
          <div class=" col-sm-2">
            <label class="red">Factura No.</label>
            <label id="numeroSerie" class="red"></label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label class="text-right">Cliente/Alumno(C)</label>
          </div>
          <div class="col-sm-6">
            <select class="form-control input-sm" id="cliente" name="cliente" tabindex="5">
              <option value="">Seleccione un cliente</option>
            </select>
            <input type="hidden" name="codigoCliente" id="codigoCliente">
          </div>
          <div class="col-sm-2">
            <input type="input" class="form-control input-sm" id="grupo" name="grupo" tabindex="6">
          </div>
          <div class=" col-sm-2">
            <input tabindex="7" type="input" class="form-control input-sm text-right" name="factura" id="factura">
          </div>
          
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Dirección</label>
          </div>
          <div class="col-sm-6">
            <input tabindex="8" type="input" class="form-control input-sm" name="direccion" id="direccion">
          </div>
          <div class="col-sm-2 text-right">
            <label class="form-control input-sm" id="saldo">Saldo pendiente</label>
          </div>
          <div class="col-sm-2">
            <input type="input" id="saldoPendiente" class="form-control input-sm text-right blue saldo_input" name="saldoPendiente">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Razón social</label>
          </div>
          <div class="col-sm-4">
            <input tabindex="9" type="input" class="form-control input-sm" name="persona" id="persona">
          </div>
          <div class="col-sm-1 text-right">
            <label>CI/R.U.C</label>
          </div>
          <div class="col-sm-1 text-right">
            <input tabindex="10" type="input" class="form-control input-sm" name="tdCliente" id="tdCliente" readonly>
          </div>
          <div class=" col-sm-2">
            <input tabindex="10" type="input" class="form-control input-sm" name="ci" id="ci_ruc">   
          </div>
          <div class="col-sm-1">
            <label class="online-radio"><input tabindex="4" type="checkbox" name="rbl_radio" id="rbl_no" checked="" style="margin-right: 2px;">Con mes</label>  
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Dirección</label>
          </div>
          <div class="col-sm-5">
            <input tabindex="11" type="input" class="form-control input-sm" name="direccion" id="direccion1">
          </div>
          <div class="col-sm-1 text-right">
            <label>Telefono</label>
          </div>
          <div class=" col-sm-2">
            <input tabindex="12" type="input" class="form-control input-sm" name="telefono" id="telefono">
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
            <input tabindex="13" type="input" class="form-control input-sm" name="email" id="email">
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm" name="codigo" id="codigo" tabindex="26">
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
                          <th style="border: #b2b2b2 1px solid;"></th>
                          <th style="border: #b2b2b2 1px solid;">Mes</th>
                          <th style="border: #b2b2b2 1px solid;">Código</th>
                          <th style="border: #b2b2b2 1px solid;">Año</th>
                          <th style="border: #b2b2b2 1px solid;" width="300px">Producto</th>
                          <th style="border: #b2b2b2 1px solid;" width="100px">Valor</th>
                          <th style="border: #b2b2b2 1px solid;" width="100px">Descuento</th>
                          <th style="border: #b2b2b2 1px solid;" width="100px">Desc. P. P.</th>
                          <th style="border: #b2b2b2 1px solid;" width="100px">Total</th>
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
          <div class="col-sm-2 text-left">
            <b>Total Tarifa 0%</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>Total Tarifa 12%</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>Descuentos</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>Desc x P P</b>
            <button tabindex="25" type="button" class="btn" data-toggle="modal" data-target="#myModal">%</button>
          </div>
          <div class="col-sm-2 text-left">
            <b>I.V.A. 12%</b>
          </div>
          <div class="col-sm-2 text-left">
            <b>Total Facturado</b>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <input type="text" name="total0" id="total0" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2">
            <input type="text" name="total12" id="total12" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2">
            <input type="text" name="descuento" id="descuento" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2">
            <input type="text" name="descuentop" id="descuentop" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2">
            <input type="text" name="iva12" id="iva12" class="form-control input-sm red text-right" readonly>
          </div>
          <div class="col-sm-2">
            <input type="text" name="total" id="total" class="form-control input-sm red text-right" readonly>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Detalle del pago</label>
          </div>
          <div class="col-sm-6">
            <input type="text" name="cheque" class="form-control input-sm" value="." tabindex="17">
          </div>
          <div class="col-sm-2 text-right">
            <b>Cheque No.</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="chequeNo" id="chequeNo" class="form-control input-sm text-right" tabindex="18">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Bancos/Tarjetas</label>
          </div>
          <div class="col-sm-7">
            <select class="form-control input-sm" name="cuentaBanco" id="cuentaBanco" tabindex="15" onchange="verificarTJ();">
              <?php
                $cuentas = $facturar->getCatalogoCuentas();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-1 text-right">
            <label>USD</label>
          </div>
          <div class="col-sm-2">
            <input tabindex="19" type="text" name="valorBanco" id="valorBanco" onkeyup="calcularSaldo();" class="form-control input-sm red text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Anticipos</label>
          </div>
          <div class="col-sm-7">
            <select class="form-control input-sm" name="cuentaBanco" id="cuentaBanco" tabindex="15">
              <?php
                $cuentas = $facturar->getAnticipos();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-1 text-right">
            <label>USD</label>
          </div>
          <div class="col-sm-2">
            <input type="input" id="saldoFavor" class="form-control input-sm red text-right" name="saldoFavor" tabindex="24" onkeyup="calcularSaldo();">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Notas de crédito</label>
          </div>
          <div class="col-sm-7">
            <select class="form-control input-sm" name="cuentaNC" id="cuentaNC" tabindex="16">
              <?php
                $cuentas = $facturar->getNotasCredito();
                foreach ($cuentas as $cuenta) {
                  echo "<option value='".$cuenta['codigo']."'>".$cuenta['nombre']."</option>";
                }
              ?>
            </select>
          </div>
          <div class="col-sm-1 text-right">
            <label>USD</label>
          </div>
          <div class="col-sm-2">
            <input tabindex="21" type="text" name="abono" id="abono" onkeyup="calcularSaldo();" class="form-control input-sm red text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-4 text-center">
            <input type="text" name="codigoB" class="red1 form-control input-sm" id="codigoB" style="color: white" value="Código del banco: " />
          </div>
          <div class="col-sm-2 col-sm-offset-4 text-right">
            <b>Efectivo USD</b>
          </div>
          <div class="col-sm-2">
            <input tabindex="20" type="text" name="efectivo" id="efectivo" onkeyup="calcularSaldo();" class="form-control input-sm red text-right">
          </div>
        </div>
        <div class="row" id="divInteres">
          <div class="col-sm-2 col-sm-offset-8 text-right">
            <b>Interés Tarjeta USD</b>
          </div>
          <div class="col-sm-2">
            <input tabindex="20" type="text" name="interesTarjeta" id="interesTarjeta" class="form-control input-sm red text-right">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-1 text-center justify-content-center align-items-center">
            <input style="width: 50px" type="text" id="registros" class="form-control input-sm text-center justify-content-center align-items-center" readonly>
          </div>
          <div class=" col-sm-4 col-sm-offset-3">
            <div class="col-sm-2 col-sm-offset-4">
              <a title="Guardar" class="btn btn-default" tabindex="22">
                <img src="../../img/png/save.png" width="25" height="30" onclick="guardarPension();">
              </a>
            </div>
            <div class="col-sm-2">
              <a title="Guardar" class="btn btn-default" tabindex="22" title="Salir del panel" href="facturacion.php?mod=facturacion">
                <img src="../../img/png/salire.png" width="25" height="30" >
              </a>
            </div>
          </div>
          <div class="col-sm-2 text-right">
            <b>Saldo USD</b>
          </div>
          <div class="col-sm-2">
            <input type="text" name="saldoTotal" id="saldoTotal" class="form-control input-sm red text-right">
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

<!-- Modal porcentaje-->
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

<!-- Modal historia del cliente-->
<div id="myModalHistoria" class="modal fade modal-xl" role="dialog">
  <div class="modal-dialog modal-xl" style="width:1250px;height: 400px">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Historia del cliente</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-sm-12">
            <div class="tab-content" style="background-color:#E7F5FF">
              <div id="home" class="tab-pane fade in active">
                <div class="table-responsive" id="tabla_" style="overflow-y: scroll; height:450px; width: auto;">
                  <div class="sombra" style>
                    <table border class="table table-striped table-hover" id="customers" tabindex="14" >
                      <thead>
                        <tr>
                          <th>TD</th>
                          <th>Fecha</th>
                          <th>Serie</th>
                          <th>Factura</th>
                          <th>Detalle</th>
                          <th>Año</th>
                          <th>Mes</th>
                          <th>Total</th>
                          <th>Abonos</th>
                          <th>Mes No</th>
                          <th>No</th>
                        </tr>
                      </thead>
                      <tbody id="cuerpoHistoria">
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>  
        </div>
      </div>
      <div class="modal-footer">
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a type="button" href="#" target="_blank" class="btn btn-default" onclick="historiaClientePDF();">
            <img title="Generar PDF" src="../../img/png/impresora.png">
          </a>                           
        </div>      
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a type="button" href="#" target="_blank" class="btn btn-default" onclick="historiaClienteExcel();">
            <img title="Generar EXCEL" src="../../img/png/table_excel.png">
          </a>                          
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a type="button" class="btn btn-default" onclick="enviarHistoriaCliente();">
            <img title="Enviar a correo" src="../../img/png/email.png">
          </a>                          
        </div>
        
        
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>

  </div>
</div>