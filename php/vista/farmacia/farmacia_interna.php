<?php  require_once("panel.php"); ?>
<style type="text/css">
          #datos_t tbody tr:nth-child(even) { background:#fffff;}
          #datos_t tbody tr:nth-child(odd) { background: #e2fbff;}
          #datos_t tbody tr:nth-child(even):hover {  background: #DDB;}
          #datos_t thead { background: #afd6e2; }
          #datos_t tbody tr:nth-child(odd):hover {  background: #DDA;}
          #datos_t table {border-collapse: collapse;}
          #datos_t table, th, td {  border: solid 1px #aba0a0;  padding: 2px;  }
          #datos_t tbody { box-shadow: 10px 10px 6px rgba(0, 0, 0, 0.6);  }
          #datos_t thead { background: #afd6e2;  box-shadow: 10px 0px 6px rgba(0, 0, 0, 0.6);} 

          /*#datos_t tbody { display:block; height:300px;  overflow-y:auto; width:fit-content;}*/
          /*#datos_t thead,tbody tr {    display:table;  width:100%;  table-layout:fixed; } */
          #datos_t thead { width: calc( 100% - 1.2em ) /*scrollbar is average 1em/16px width, remove it from thead width*/ }


       </style>      
<script type="text/javascript">
   $( document ).ready(function() {
   	 tabla_ingresos();
   	 autocoplet_prov()
// /------------------------
   	 autocoplet_desc();
   	 autocoplet_ref();
   	 tabla_catalogo('ref');
//---------------------------

//--------------------------
    cargar_pedidos();

   
  });
   function autocoplet_prov(){
      $('#ddl_proveedor').select2({
        placeholder: 'Seleccione una proveedor',
        ajax: {
          url:   '../controlador/farmacia/articulosC.php?proveedores=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            if(data != -1)
            {
              return {
                results: data
              };
            }else
            {
              Swal.fire('','Defina una cuenta "Cta_Proveedores" en Cta_Procesos.','info');
            }
          },
          cache: true
        }
      });
   
  }

   function tabla_ingresos()
   {
   	 var parametros=
    {
      'proveedor':$('#ddl_proveedor').val(),
      'factura':$('#txt_factura').val(),
      'comprobante':$('#txt_comprobante').val(),
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?tabla_ingresos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#tbl_opcion1').html(response);
      }
    });

   }

 //-----------------------opcion2-----------------------------
  function autocoplet_desc(){
      $('#ddl_descripcion').select2({
        placeholder: 'Escriba Descripcion',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=desc',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  } 

   function autocoplet_ref(){
      $('#ddl_referencia').select2({
        placeholder: 'Escriba Referencia',
        ajax: {
          url:   '../controlador/farmacia/ingreso_descargosC.php?producto=true&tipo=ref',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            // console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
   
  }



   function tabla_catalogo(tipo)
   {
   	 var parametros=
    {
      'descripcion':$('#ddl_descripcion').val(),
      'referencia':$('#ddl_referencia').val(),
      'tipo':tipo,
    }
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?tabla_catalogo=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
      	console.log(response);
        $('#tbl_opcion2').html(response);
      }
    });

   }




function Ver_Comprobante(comprobante)
{
    url='../controlador/farmacia/reporte_descargos_procesadosC.php?Ver_comprobante=true&comprobante='+comprobante;
    window.open(url, '_blank');
}
function Ver_detalle(comprobante)
{
    url='../vista/farmacia.php?mod=Farmacia&acc=utilidad_insumos&acc1=Utilidad insumos&b=1&po=subcu&comprobante='+comprobante;
    window.open(url, '_blank');
}
 function cargar_pedidos(f='')
  {
   
      var  parametros = 
      { 
        'nom':$('#txt_paciente').val(),
        'ci':$('#txt_ci').val(),
        'historia':$('#txt_historia').val(),
        'depar':$('#txt_departamento').val(),
        'proce':$('#txt_procedimiento').val(),
        'desde':$('#txt_desde').val(),
        'hasta':$('#txt_hasta').val(),
        'busfe':f,
      }    
     // console.log(parametros);
     $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/farmacia/farmacia_internaC.php?cargar_pedidos=true',
      type:  'post',
      dataType: 'json',
      success:  function (response) { 
        if(response)
        {
          $('#tbl_descargos').html(response.tabla);
        }
      }
    });
  }


</script>

<div class="container-lg">
  <div class="row"><br>
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="./farmacia.php?mod=Farmacia#" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>        
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=pacientes&acc1=Visualizar%20paciente&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_pdf" title="Pacientes">
            <img src="../../img/png/pacientes.png">
          </a>           
        </div>
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=vis_descargos&acc1=Visualizar%20descargos&b=1&po=subcu#" type="button" class="btn btn-default" id="imprimir_excel" title="Descargos">
            <img src="../../img/png/descargos.png">
          </a>         
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
          <a href="./farmacia.php?mod=Farmacia&acc=articulos&acc1=Visualizar%20articulos&b=1&po=subcu#" title="Ingresar Articulosr"  class="btn btn-default" onclick="">
            <img src="../../img/png/articulos.png" >
          </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 	</div>
 </div>
<div class="container"><br>
	<div class="row">
		<div class="col-sm-6">
			<select class="form-control input-sm">
				<option value="">Seleccione opcion</option>
				<option value="1">INGRESOS</option>
				<option value="2">LISTADO DEL CATALOGO</option>
				<option value="3">EGRESOS O DESCARGOS DE PACIENTES</option>
				<option value="4">DESCARGOS PARA VISUALIZAR POR PACIENTE</option>
				<option value="5">VISUALIZACION DE DESCARGOS DE FARMACIA INTERNA</option>
			</select>			
		</div>		
	</div>
	<div id="opcion1" style="display:none;">
		<div class="row">
			<div class="col-sm-4">
				<b>Proveedor</b>
				<div class="input-group"> 
                  <select class="form-control input-sm" id="ddl_proveedor" name="ddl_proveedor" onchange="tabla_ingresos()">
                     <option value="">Seleccione un proveedor</option>
                  </select>             
                   <span class="input-group-addon" onclick="$('#ddl_proveedor').empty();tabla_ingresos()" title="Borrar seleccion"><i class="fa fa-close"></i></span>
              </div>
			</div>	
			<div class="col-sm-4">
				Facturas
				<input type="text" class="form-control input-sm" name="txt_factura" id="txt_factura" placeholder="Numero de factura" onkeyup="tabla_ingresos()">
			</div>	
			<div class="col-sm-4">
				No. Comprobante
				<input type="text" class="form-control input-sm" name="txt_comprobante" id="txt_comprobante" onkeyup="tabla_ingresos()" placeholder="Numero de Comprobante">
			</div>			
		</div>
		<div class="row"><br>
			<div id="tbl_opcion1">
				
			</div>
			
		</div>		
	</div>
	<div id="opcion2" style="display:none">
		<div class="row">
			<div class="col-sm-4">
				<b>Codigo</b>
				<div class="input-group"> 
                  <select class="form-control input-sm" id="ddl_referencia" name="ddl_referencia" onchange="tabla_catalogo('ref')">
                     <option value="">Seleccione un proveedor</option>
                  </select>             
                   <span class="input-group-addon" onclick="$('#ddl_referencia').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="fa fa-close"></i></span>
              </div>
			</div>	
			<div class="col-sm-4">
				Descripcion
				<div class="input-group"> 
                  <select class="form-control input-sm" id="ddl_descripcion" name="ddl_descripcion" onchange="tabla_catalogo('ref')">
                     <option value="">Seleccione un proveedor</option>
                  </select>             
                   <span class="input-group-addon" onclick="$('#ddl_descripcion').empty();tabla_catalogo('ref')" title="Borrar seleccion"><i class="fa fa-close"></i></span>
              </div>
			</div>	
					
		</div>
		<div id="tbl_opcion2">
			
		</div>	
	</div>

  <div id="opcion3">
    <div class="row">
      <div class="col-sm-12">
        
      </div>      
    </div>
    <div class="row">
      <div class="col-sm-12">
        
      </div>      
    </div>
    
  </div>

  <div id="opcion4">
    <div class="row">
      <div class="col-sm-3">
        <b>Paciente</b>
        <input type="text"  class="form-control input-sm" name="txt_paciente" id="txt_paciente" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-2">
        Numero de Cedula       
        <input type="text"  class="form-control input-sm" name="txt_ci" id="txt_ci" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-2">
        <b>Historia Clinica</b>        
        <input type="text"  class="form-control input-sm" name="txt_historia" id="txt_historia" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-3">
        Departamento       
        <input type="text"  class="form-control input-sm" name="txt_departamento" id="txt_departamento" onkeyup="cargar_pedidos();">
      </div>  
      <div class="col-sm-2">
        <b>Procedimiento</b>       
        <input type="text"  class="form-control input-sm" name="txt_procedimiento" id="txt_procedimiento">
      </div>  
      <div class="col-sm-2">
        Desde        
        <input type="date"  class="form-control input-sm" name="txt_desde" id="txt_desde">
      </div>  
      <div class="col-sm-2">
        <b>Hasta</b>        
        <input type="date"  class="form-control input-sm" name="txt_hasta" id="txt_hasta">
      </div>  
    </div>
    <div class="row">
      <div class="col-sm-12">
        <div class="table-responsive">
          <table id="datos_t">
            <thead>
              <th>Fecha</th>
              <th>Paciente</th>
              <th>Numero de cedula</th>
              <th>Historia clinica</th>
              <th>Departamento</th>
              <th>Costo total</th>
              <th>Porcedimiento</th>
            </thead>
            <tbody id="tbl_descargos">
              
            </tbody>
          </table>
          
        </div>
        
      </div>      
    </div>
    
  </div>

</div>

