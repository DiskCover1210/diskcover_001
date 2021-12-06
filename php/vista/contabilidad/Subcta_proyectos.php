<?php  require_once("panel.php");?>
<script type="text/javascript">

  $(document).ready(function () {
  	DGCostos();
  	DCProyecto();
  	DCSubModulos();

  });
	
  function DGCostos()
  {
  	
  	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/Subcta_proyectosC.php?DGCostos=true',
		//data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{			
			console.log(data);
			$('#tbl_tabla').html(data);
		}
	});
  }

  function DCProyecto()
  {
  	
  	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/Subcta_proyectosC.php?DCProyecto=true',
		//data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{			
			llenarComboList(data,'ddl_proyecto');
			// console.log(data);
			// $('#tbl_tabla').html(data);
		}
	});
  }

 function DCSubModulos()
  {
  	
  	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/Subcta_proyectosC.php?DCSubModulos=true',
		//data: {parametros: parametros},
		dataType:'json',
		success: function(data)
		{			
			llenarComboList(data,'ddl_cuenta_pro');
			// console.log(data);
			// $('#tbl_tabla').html(data);
		}
	});
  }
</script>

   <div style="padding-left: 20px;padding-right: 20px">
    <div class="row">
      <div class="col-lg-3 col-sm-4 col-md-8 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="./contabilidad.php?mod=contabilidad#"  title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
          <button type="button" class="btn btn-default" data-toggle="dropdown" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>
            <ul class="dropdown-menu">
              <li><a href="#" id="imprimir_pdf">Diario General</a></li>
              <li><a href="#" id="imprimir_pdf_2">Libro Diario</a></li>
            </ul>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <button type="button" class="btn btn-default" data-toggle="dropdown" title="Descargar Excel">
            <img src="../../img/png/table_excel.png">
          </button>
          <ul class="dropdown-menu">
            <li><a href="#" id="imprimir_excel">Diario General</a></li>
            <li><a href="#" id="imprimir_excel_2">Libro Diario</a></li>
          </ul>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="../controlador/contabilidad/catalogoCtaC.php?imprimir_pdf=true" class="btn btn-default" title="Autorizar"  target="_blank" id='imprimir_pdf'>
            <img src="../../img/png/autorizar1.png">
          </a>
        </div>
        <!-- <div class="col-xs-2 col-md-2 col-sm-2">
          <button title="Consultar Catalogo de cuentas"  class="btn btn-default" onclick="libro_general();">
            <img src="../../img/png/consultar.png" >
          </button>
        </div> -->
      </div>
    </div>
     <div class="row">  
        <div class="col-sm-4">
        	<b>Proyecto</b>
        	<select class="form-control input-sm" id="ddl_proyecto">
        		<option value="">Seleccione</option>
        	</select>                 
     	 </div>
     	 <div class="col-sm-4">
        	<b>cuentas de Proyecto</b>
        	<select class="form-control input-sm" id="ddl_cuenta_pro">
        		<option value="">Seleccione</option>
        	</select>                 
     	 </div>
     	 <br>
     	  <div class="col-sm-4">
     	  	<!-- . -->
     	  </div>
     	 <div class="col-sm-4">
        	<b>SubModulos de proyecto</b>
        	<select class="form-control input-sm" id="ddl_sub_pro">
        		<option value="">Seleccione</option>
        	</select>                 
     	 </div>
    </div>
    <div class="row">
    	<div class="col-sm-12" id="tbl_tabla">
    		
    	</div>    	
    </div>
    <!--seccion de panel-->
    