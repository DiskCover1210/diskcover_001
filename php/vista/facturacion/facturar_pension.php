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
  #saldo_input{
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
<div class="container" id="container1">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
          <img src="../../img/png/team.png" width="25" height="30">
        </a>
      </div>
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
      <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
        <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
          <img src="../../img/png/document.png" width="25" height="30">
        </a>
      </div>
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
    </div>
  </div>
  <div class="row">
    <div class="panel panel-primary">
      <div class="panel-body">
        <div class="row">
          <div class="col-md-2">
            <select class="form-control input-sm">
              <option>Gerencia</option>
            </select>
          </div>
          <div class="col-sm-2 text-right">
            <label>Fecha emisión</label>
          </div>
          <div class="col-sm-2">
            <input type="date" name="fechaEmision" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class="col-sm-2 text-right">
            <label>Fecha vencimiento</label>
          </div>
          <div class="col-sm-2">
            <input type="date" name="fechaVencimiento" class="form-control input-sm" value="<?php echo date('Y-m-d'); ?>">
          </div>
          <div class=" col-sm-2">
            <label class="red">Factura No.</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label class="text-right">Cliente/Alumno(C)</label>
          </div>
          <div class="col-sm-6">
            <select class="form-control input-sm" id="cliente" name="cliente" >
              <option value="">Seleccione un cliente</option>
            </select>
          </div>
          <div class="col-sm-2">
            <input type="input" class="form-control input-sm" value="SPG">   
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm text-right" name="factura" value="13981">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Dirección</label>
          </div>
          <div class="col-sm-6">
            <input type="input" class="form-control input-sm" name="factura">
          </div>
          <div class="col-sm-2 text-right">
            <label class="form-control input-sm" id="saldo">Saldo pendiente</label>
          </div>
          <div class="col-sm-2">
            <input type="input" id="saldo_input" class="form-control input-sm text-right blue" name="saldo" value="0.00">   
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Persona Natural</label>
          </div>
          <div class="col-sm-5">
            <input type="input" class="form-control input-sm" name="factura">
          </div>
          <div class="col-sm-1 text-right">
            <label>CI/R.U.C</label>
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm" name="factura">   
          </div>
          <div class="col-sm-1">
            <label class="online-radio"><input type="radio" name="rbl_radio" id="rbl_no" checked="" style="margin-right: 2px;">Con mes</label>  
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Dirección</label>
          </div>
          <div class="col-sm-5">
            <input type="input" class="form-control input-sm" name="factura">
          </div>
          <div class="col-sm-1 text-right">
            <label>Telefono</label>
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm" name="factura">   
          </div>
          <div class="col-sm-2">
            <label>Código intero</label>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Email</label>
          </div>
          <div class="col-sm-8">
            <input type="input" class="form-control input-sm" name="factura">
          </div>
          <div class=" col-sm-2">
            <input type="input" class="form-control input-sm" name="factura">   
          </div>
        </div>
        <br>
        <div class="row">
          <div class="col-sm-12">
            <table class="table table-responsive table-bordered thead-dark" id="customers" style="height: 100px">
              <thead>
                <tr>
                  <th></th>
                  <th>Mes</th>
                  <th>Código</th>
                  <th>Año</th>
                  <th>Producto</th>
                  <th>Valor</th>
                  <th>Descuento</th>
                  <th>Desc. P. P.</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td><input type="checkbox" name="abril"></td>
                  <td>Abril</td>
                  <td>98.90</td>
                  <td>2020</td>
                  <td>Servicio de entrega</td>
                  <td>2.20</td>
                  <td>0</td>
                  <td>0</td>
                  <td>2.20</td>
                </tr>
              </tbody>
            </table>          
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2">
            <b>Total Tarifa 0%</b>
            <input type="text" name="txt_max_in" id="txt_max_in" class="form-control input-sm red text-right" value="35.70">
          </div>
          <div class="col-sm-2">
             <b>Total Tarifa 12%</b>
              <input type="text" name="txt_min_in" id="txt_min_in" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class="col-sm-2">
            <b>Descuentos</b>
            <input type="text" name="txt_canti" id="txt_canti" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class="col-sm-2">
            <b>Desc x P P</b>
            <input type="text" name="txt_precio" id="txt_precio" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class="col-sm-2">
            <b>I. V. A. 12%</b>
            <input type="text" name="txt_precio_ref" id="txt_precio_ref" class="form-control input-sm red text-right" value="0.00">
          </div>
          <div class="col-sm-2">
            <b>Total Facturado</b>
            <input type="text" name="txt_descto" id="txt_descto" class="form-control input-sm red text-right" value="35.70">
          </div>         
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Detalle del pago</label>
          </div>
          <div class="col-sm-4">
            <input type="text" name="detalle" class="form-control input-sm">
          </div>
          <div class="col-sm-2 text-right">
            <label>Cheq./Dep. No.</label>
          </div>
          <div class="col-sm-4">
            <input type="text" name="cheque" class="form-control input-sm" value="SGP">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Bancos/Tarjetas</label>
          </div>
          <div class="col-sm-7">
            <select class="form-control input-sm">
              <option>Seleccione Banco/Tarjeta</option>
            </select>
          </div>
          <div class="col-sm-1 text-right">
            <label>USD</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="cheque" class="form-control input-sm black text-right" value="35.70">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Acticipos</label>
          </div>
          <div class="col-sm-7">
            <select class="form-control input-sm">
              <option>Seleccione Anticipo</option>
            </select>
          </div>
          <div class="col-sm-1 text-right">
            <label>USD</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="cheque" class="form-control input-sm green text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2 text-right">
            <label>Notas de crédito</label>
          </div>
          <div class="col-sm-7">
            <select class="form-control input-sm">
              <option>Seleccione nota de crédito</option>
            </select>
          </div>
          <div class="col-sm-1 text-right">
            <label>USD</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="cheque" class="form-control input-sm blue text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3 text-center">
            <label class="red1 form-control input-sm" style="color: white">Código del banco: 0701520546</label>
          </div>
          <div class="col-sm-2 col-sm-offset-5 text-right">
            <label>Efectivo USD</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="cheque" class="form-control input-sm blue text-right" value="0.00">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-3 text-center justify-content-center align-items-center">
            <input style="width: 50px" type="text" name="codigoBanco" class="form-control input-sm text-center justify-content-center align-items-center" value="538">
          </div>
          <div class=" col-sm-4 col-sm-offset-2">
            <div class="col-sm-2 col-sm-offset-4">
              <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/save.png" width="25" height="30">
              </a>
            </div>
            <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
              <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
                <img src="../../img/png/salire.png" width="25" height="30">
              </a>
            </div>
          </div>
          <div class="col-sm-1  text-right">
            <label>Saldo USD</label>
          </div>
          <div class="col-sm-2">
            <input type="text" name="cheque" class="form-control input-sm red text-right" value="0.00">
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <input type="hidden" name="" id="txt_pag" value="0">
    <div id="tbl_pag"></div>    
  </div>
  <div class="row" id="tbl_ingresados">

  </div>
</div>