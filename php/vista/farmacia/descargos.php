<?php  require_once("panel.php");?>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_pdf" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button type="button" class="btn btn-default" id="imprimir_excel" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <button title="Guardar"  class="btn btn-default" onclick="">
            <img src="../../img/png/grabar.png" >
          </button>
        </div>     
 </div>
</div>
<div class="container">
  <div class="row"><br>
     <div class="panel panel-primary">
      <div class="panel-heading text-center"><b>BUSCAR FACTURA</b></div>
      <div class="panel-body">
      	<div class="row">
          <div class="col-sm-6">
            <b>Codigo de cliente :</b>
            <div class="input-group">
                <input type="text" class="form-control">
                <span class="input-group-addon"><i class="fa fa-search"></i></span>
            </div>
            <b>Nombre</b>
            <input type="text" name="txt_nombre" id="txt_nombre" class="form-control input-sm">            
            <b>NUM FACTURA:</b>
            <input type="text" name="txt_ruc" id="txt_ruc" class="form-control input-sm">
            <b>ESTADO:</b>
            <select class="form-control input-sm" id="ddl_provincia">
              <option>Seleccione estado</option>
            </select>    
            <b>FECHA INICIO:</b>
            <input type="date" name="txt_localidad" id="txt_desde" class="form-control input-sm">
            <b>FECHA FIN:</b>
            <input type="date" name="txt_telefono" id="txt_hasta" class="form-control input-sm">
            
          </div>
          <div class="col-sm-6">
            
          </div>          
        </div>        
      </div>
    </div>
  </div>
  <div class="row">
     <div class="modal-footer">
        <button type="button" class="btn btn-info"><i class="fa fa-search"></i> Buscar</button>
        <button type="button" class="btn btn-primary"><i class="fa fa-paint-brush"></i> Limpiar</button>
        <button type="button" class="btn btn-success"><i class="fa fa-plus"></i> Nuevo factura</button>
      </div>
  </div>
  <div class="row">
  	<div class="col-sm-10">
        N de articulos encontrados: 000
      </div>
      <div class="col-sm-2">
        mostrados: 1-50
      </div>  	
  </div>
  <div class="row"  style="height: 400px">
  	<div class="table-responsive">      
  		<table class="table table-hover">
  			<thead>
  				<th>ITEM</th>
  				<th>NUM FACTURA</th>
  				<th>PACIENTE</th>
  				<th>IMPORTE</th>
  				<th>FECHA</th>
  				<th>ESTADO</th>
  				<th></th>
  			</thead>
  			<tbody>
  				<tr>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td></td>
  					<td>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-pencil"></span></button>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-search"></span></button>
  						<button class="btn btn-sm"><span class="glyphicon glyphicon-trash"></span></button>
  					</td>
  				</tr>
  			</tbody>
  		</table>
  	</div>
  </div>
</div>
