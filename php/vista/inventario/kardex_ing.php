<?php  require_once("panel.php");?>
<style type="text/css">
	td,
th {
  padding: 8px;
}
</style>
<script type="text/javascript">

  $(document).ready(function()
  {
    familias();
    contracuenta();
    Trans_Kardex();
    bodega();
    marca();
    
  })

  function familias()
  {
      $('#ddl_familia').select2({
        placeholder: 'Seleccione una Familia',
        ajax: {
           url:   '../controlador/inventario/kardex_ingC.php?familias=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });

  }
   function producto_famili(familia)
  { 
    var fami = $('#ddl_familia').val();
    $('#ddl_producto').select2({
        placeholder: 'Seleccione producto',
        ajax: {
           url:   '../controlador/inventario/kardex_ingC.php?producto=true&fami='+fami,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   

  }
function contracuenta()
  { 
    $('#DCCtaObra').select2({
        placeholder: 'Seleccione Contracuenta',
        ajax: {
           url:   '../controlador/inventario/kardex_ingC.php?contracuenta=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   

  }

  function leercuenta()
  { 
    var parametros =
    {
        'cuenta':$('#DCCtaObra').val(),
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?leercuenta=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response.length !=0) 
            {
                $('#Codigo').val(response.Codigo);
                $('#Cuenta').val(response.Cuenta);
                $('#SubCta').val(response.SubCta);
                $('#Moneda_US').val(response.Moneda_US);
                $('#TipoCta').val(response.TipoCta);
                $('#TipoPago').val(response.TipoPago);
                ListarProveedorUsuario();

            }
         
      }
    });  

  }

   function Trans_Kardex()
  { 
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?Trans_Kardex=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if (response.length !=0) 
        {
            console.log(response);
        }         
      }
    });  

  }

     function bodega()
  { 
    var option = '<option value="">Seleccione bodega</option>';
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?bodega=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if (response.length !=0) 
        {
        $.each(response,function(i,item){
            console.log(item);
             option+='<option value="'+item.CodMar+'">'+item.Marca+'</option';
           });
           $('#DCBodega').html(option); 
        }         
      }
    });  

  }


   function marca()
  { 
    var option = '<option value="">Seleccione marca</option>';
    $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?marca=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        if (response.length !=0) 
        {
           $.each(response,function(i,item){
            console.log(item);
             option+='<option value="'+item.CodMar+'">'+item.Marca+'</option';
           });
           $('#DCMarca').html(option); 
        }         
      }
    });  

  }



   function ListarProveedorUsuario()
  { 
    var cta = $('#SubCta').val();
    var contra = $('#DCCtaObra').val();
    $('#DCBenef').select2({
        placeholder: 'Seleccione Cliente',
        ajax: {
           url:   '../controlador/inventario/kardex_ingC.php?ListarProveedorUsuario=true&cta='+cta+'&contra='+contra,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   

  }

  function guardar()
  {
  	var tipo = $('input:radio[name=rbl_]:checked').val();
  }
  function modal()
  {
  	alert($('#rbl_retencion').is('checked'));
  	if($('#rbl_retencion').is(':checked')=='true')
  	{
  		$('#myModal').modal('Show');
  	}
  }
</script>

<div class="container-lg">
  <div class="row">
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
    	 <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./inventario.php?mod=Inventario#" title="Salir de modulo" class="btn btn-default">
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
    <input type="" name="" id="Codigo">
    <input type="" name="" id="Cuenta">
    <input type="" name="" id="SubCta">
    <input type="" name="" id="Moneda_US">
    <input type="" name="" id="TipoCta">
    <input type="" name="" id="TipoPago">

	<div class="row"><br>
		<div class="col-sm-2">
			<select class="form-control">
				<option value="">Seleccione TP</option>
                <option value="CD">CD</option>
                <option value="NC">NC</option>
                <option value="ND">ND</option>
                <option value="CD">CD</option>
			</select>
		</div>
		<div class="col-sm-2">
			<label class="radio-inline" ><b><input type="radio" name="rbl_tipo" checked=""> Ingreso</b></label>
			<label class="radio-inline" ><b><input type="radio" name="rbl_tipo"> Egreso</b></label>
		</div>
		<div class="col-sm-2">			
			<label class="radio-inline"><b><input type="checkbox" name="cbx_contra_cta" checked=""> CONTRA CUENTA</b></label>
		</div>
		<div class="col-sm-3">
			<select class="form-control" id="DCCtaObra" onchange="leercuenta();">
				<option>seleccione contra cuenta</option>
			</select>
		</div>
		<div class="col-sm-3">
			<select class="form-control" id="DCBenef">
				<option>seleccione clinete </option>
			</select>
		</div>
    </div>
    <div class="row">
    	<div class="col-sm-2">
    		<b>Fecha:</b><input type="date" name="" value="<?php echo date('Y-m-d')?>">
    	</div>
    	<div class="col-sm-2">
    		<b>Vencimiento:</b><input type="date" name="">
    	</div>
    	<div class="col-sm-5">
    		<b>POR CONCEPTO DE:</b><input type="text" name="" class="form-control input-sm"> 
    	</div>
    	<div class="col-sm-3 text-right">
    		<br>
    		<b>N° Factura:</b><input type="text" name="" class="input-sm"> 
    	</div>
    </div>
    <div class="row">
    	<div class="col-sm-4">
    		<label class="radio-inline"><b><input type="checkbox" name="rbl_retencion" onclick="modal();" > Retencion en la funente:</b> <input type="text" name="" class="input-sm"></label>
    	</div>
    	<div class="col-sm-4">
    		<b>Retencion del I.V.A:</b> <input type="text" name="" class="input-sm">
    	</div>
    </div> 
    <div class="row"><br>
    	<div class="col-md-4"><br>
            <select class="form-control input-sm" id="ddl_familia" onchange="producto_famili($('#ddl_familia').val())">
                <option value="">Seleccione un Familiar</option>
            </select>
    		<select class="form-control input-sm" id="ddl_producto">
    			<option>Seleccione un articulo</option>
    		</select>
    	</div>
    	<div class="col-sm-2">
    		<label class="radio-inline" ><b><input type="radio" name="rbl_" checked=""> Con Iva</b></label>
			<label class="radio-inline" ><b><input type="radio" name="rbl_"> Sin Iva</b></label>   
			<select class="form-control input-sm" id="DCBodega">
				<option>Seleccione</option>
			</select> 		
    	</div>
    	<div class="col-sm-3">
    		<b>MARCA</b><br>
    		<select class="form-control input-sm" id="DCMarca">
    			<option>Seleccione Marca</option>
    		</select>
    	</div>
    	<div class="col-sm-3">
    		<b>CODIGO</b><br>
    		<input type="text" class="form-control input-sm">    			
    	</div>
    	
    </div>
    <div class="row">
    	<div class="col-sm-1">
    	  <b>UNIDAD</b>
    	  <input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-1">
    		<b>GUIA N°</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-1">
    		<b>CANTIDAD</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-1">
    		 <b>VALOR UNI</b>
    		 <input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-2">
    		<b>CODIGO DE BARRAS</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div>
    	<div class="col-sm-2">
    		<b>LOTE N°</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-2">
    		<b>FECHA FAB</b>
    		<input type="date" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-2">
    		<b>FECHA EXP</b>
    		<input type="date" name="" class="form-control input-sm">
    	</div>     	
    </div> 
    <div class="row">
    	<div class="col-sm-3">
    		<b>REG. SANITARIO</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-3">
    		<b>MODELO</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-3">
    		<b>PROCEDENCIA</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-3">
    		<b>SERIE N°</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-5">
    		<b>DESC.1</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-5">
    		<b>DESC.2</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div> 
    	<div class="col-sm-2">
    		<b>VALOR TOTAL</b>
    		<input type="text" name="" class="form-control input-sm">
    	</div>     	
    </div>
    <div class="row">
    	<div class="table-responsive" style="height: 400px">
    		<table>
    			<thead>
    				<th width="25px">TP</th>
    				<th>CODIGO_INV</th>
    				<th>DH</th>
    				<th>PRODUCTO</th>
    				<th>CANT_ES</th>
    				<th>VALOR_UNI</th>
    				<th>VALOR_TOTAL</th>
    				<th>CANTIDAD</th>
    				<th>SALDO</th>
    				<th>P_DESC</th>
    				<th>P_DESC1</th>
    				<th>IVA</th>
    				<th>CTA_INVENTARIO</th>
    				<th>CONTRA_CTA</th>
    				<th>UNIDAD</th>
    				<th>CodBod</th>
    				<th>CodMar</th>
    				<th>COD_BAR</th>
    				<th>T_No</th>
    				<th>Item</th>
    				<th>CodigoU</th>
    				<th>SUBCTA</th>
    				<th>Cod_Tarifa</th>
    				<th>Fecha_DUI</th>
    				<th>No_Refrendo</th>
    				<th>DUI</th>
    				<th>A_No</th>
    				<th>ValorEM</th>
    				<th>Especifico</th>
    				<th>Consumo</th>
    				<th>Antidumping</th>
    				<th>Modernizacion</th>
    				<th>Control</th>
    				<th>Almacenaje</th>
    				<th>FODIN</th>
    				<th>Salvaguardas</th>
    				<th>Interes</th>
    				<th>CODIGO_INV1</th>
    				<th>CodBod1</th>
    				<th>Codigo_B</th>
    				<th>Codigo_Dr</th>
    				<th>ORDEN</th>
    				<th>VALOR_FOB</th>
    				<th>COMIS</th>
    				<th>TRANS_UNI</th>
    				<th>TRANS_TOTAL</th>
    				<th>PRECION_CIF</th>
    				<th>UTIL</th>
    				<th>PVP</th>
    				<th>CTA_COSTO</th>
    				<th>CTA_VENTA</th>
    				<th>TOTAL_PVP</th>
    				<th>Codigo_Tra</th>
    				<th>Lote_N°</th>
    				<th>Fecha_Fab</th>
    				<th>Fecha_Exp</th>
    				<th>Reg_Sanitario</th>
    				<th>Modelo</th>
    				<th>Procedencia</th>
    				<th>Serie_N°</th>
    			</thead>
    			<tbody>
    				
    			</tbody>
    		</table>
    	</div>
    	
    </div>	
    <div class="row"><br><br>
    	<div class="col-sm-2">
    		<button class="btn btn-default" data-toggle="modal" data-target="#myModal_comprobante">Seleccionar <br> comprobante</button>
    	</div>
    	<div class="col-sm-2">
    		<b>DIF x DECIMALES</b>
    		<input type="text" class="input-sm form-control" name="">
    	</div>
    	<div class="col-sm-2">
    		<b>SUBTOTAL</b>
    		<input type="text" class="input-sm form-control" name="">
    	</div>
    	<div class="col-sm-2">
    		<b>I.V.A</b>
    		<input type="text" class="input-sm form-control" name="">
    	</div>
    	<div class="col-sm-2">
    		<b>TOTAL</b>
    		<input type="text" class="input-sm form-control" name="">
    	</div>
    </div>
</div>
</div>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal -->
<div id="myModal_comprobante" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
      </div>
      <div class="modal-body">
        <p>Some text in the modal.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>



<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

