<?php  require_once("panel.php");?>
<script type="text/javascript">

  $(document).ready(function () {
  	DGCostos();
  	DCProyecto();
  	DCSubModulos();

  });
	
  function DGCostos(todas=false)
  {
  	var parametros = 
    {
      'query':false,
      'TodasCtas':todas,
      'CodSubCta': $('#ddl_proyecto').val(),
      'SubCta':$('#ddl_cuenta_pro').val(),
    }
  	$.ajax({
		type: "POST",
		url: '../controlador/contabilidad/Subcta_proyectosC.php?DGCostos=true',
		data: {parametros: parametros},
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


  function DCCtasProyecto()
  {
    var parametros = {
      'codigo':$('#ddl_proyecto').val(),
    }    
    $.ajax({
    type: "POST",
    url: '../controlador/contabilidad/Subcta_proyectosC.php?DCCtasProyecto=true',
    data: {parametros: parametros},
    dataType:'json',
    success: function(data)
    {     
      console.log(data);
      llenarComboList(data,'ddl_cuenta_pro');
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
			llenarComboList(data,'ddl_sub_pro');
			// console.log(data);
			// $('#tbl_tabla').html(data);
		}
	});
  }

  function eliminar_item(id)
  {
    var parametros = 
    {
      'id':id,
    }
    $.ajax({
    type: "POST",
    url: '../controlador/contabilidad/Subcta_proyectosC.php?eliminar=true',
    data: {parametros: parametros},
    dataType:'json',
    success: function(data)
    { 
      if(data==1)
      {   
        DGCostos();
      }else
      {
        Swal.fire('Algo inesperado a pasado','','error')
      }
    }
  });
  }

  function todas()
  {
    var proyecto = $('#ddl_proyecto').val();
    if(proyecto!='')
    {
      $('#ddl_cuenta_pro').val('');
      DGCostos(true);
    }else
    {
      Swal.fire('Seleccione un proyecto','','info');
    }
  }

  function imprimir_excel()
  {
    var proyecto = $('#ddl_proyecto').val();
    if(proyecto=='')
    {
      Swal.fire('Seleccione un proyecto','','info');
    }else{

    var para = $('#form_filtros').serialize();   
    var url = '../controlador/contabilidad/Subcta_proyectosC.php?imprimir_excel=true&'+para;
      window.open(url, '_blank');
    }
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
        <!-- <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
          <button type="button" class="btn btn-default" data-toggle="dropdown" title="Descargar PDF">
            <img src="../../img/png/impresora.png">
          </button>           
        </div> -->
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="#" onclick="imprimir_excel()"  class="btn btn-default" title="Descargar excel" id='imprimir_excel'>
                <img src="../../img/png/table_excel.png">
          </a>      
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <button class="btn btn-default" title="Autorizar" onclick="todas();">
            <img src="../../img/png/autorizar1.png">
          </button>
        </div>
        <!-- <div class="col-xs-2 col-md-2 col-sm-2">
          <button title="Consultar Catalogo de cuentas"  class="btn btn-default" onclick="libro_general();">
            <img src="../../img/png/consultar.png" >
          </button>
        </div> -->
      </div>
    </div>
     <div class="row"> 
     <form  id="form_filtros"> 
        <div class="col-sm-4">
        	<b>Proyecto</b>
        	<select class="form-control input-sm" id="ddl_proyecto" name="ddl_proyecto" onchange="DCCtasProyecto()">
        		<option value="">Seleccione</option>
        	</select>                 
     	 </div>
     	 <div class="col-sm-4">
        	<b>cuentas de Proyecto</b>
        	<select class="form-control input-sm" id="ddl_cuenta_pro" name="ddl_cuenta_pro">
        		<option value="">Seleccione</option>
        	</select>                 
     	 </div>
     	 <div class="col-sm-4">
        	<b>SubModulos de proyecto</b>
        	<select class="form-control input-sm" id="ddl_sub_pro" name="ddl_sub_pro" onblur="insertar()">
        		<option value="">Seleccione</option>
        	</select>                 
     	 </div>
      </form>
    </div>
    <div class="row">
    	<div class="col-sm-12" id="tbl_tabla">
    		
    	</div>    	
    </div>

    <script type="text/javascript">
      function insertar()
      {
        var pro = $('#ddl_proyecto').val();
        var cta = $('#ddl_cuenta_pro').val();
        var sub = $('#ddl_sub_pro').val();


        var cta_n = $('#ddl_cuenta_pro option:selected').text();
        var sub_n = $('#ddl_sub_pro option:selected').text();

        if(pro!='' && cta!='' && sub!='')
        {
           Swal.fire({
             title: 'Esta seguro de Inserta en la cuenta! '+cta_n+" El centro de costo: "+sub_n,
             text: '',
             type: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             confirmButtonText: 'Si!'
             }).then((result) => {
               if (result.value==true) {
                agregar();

               }
             })
        }

      }

      function eliminar(id)
      {
       
           Swal.fire({
             title: 'Esta seguro de eliminar!',
             text: 'Este registro sera eliminado',
             type: 'warning',
             showCancelButton: true,
             confirmButtonColor: '#3085d6',
             cancelButtonColor: '#d33',
             confirmButtonText: 'Si!'
             }).then((result) => {
               if (result.value==true) {
                eliminar_item(id);

               }
             })
      }


      function agregar()
      {
         var pro = $('#ddl_proyecto').val();
         var cta = $('#ddl_cuenta_pro').val();
         var sub = $('#ddl_sub_pro').val();
         var cta_n = $('#ddl_cuenta_pro option:selected').text();
         var sub_n = $('#ddl_sub_pro option:selected').text();
         var parametros = 
         {
          'cta':cta,
          'codigo':sub,
         }
        $.ajax({
          type: "POST",
          url: '../controlador/contabilidad/Subcta_proyectosC.php?agregar=true',
          data: {parametros: parametros},
          dataType:'json',
          success: function(data)
          {
            console.log(data);
            if(data==null)
            {
              Swal.fire('Item agregado','','success');
              DGCostos();
            }else if(data==2)
            {
               DGCostos();
              Swal.fire('Item existente','','info');
            }
           
          }
        });

      }

    </script>
    <!--seccion de panel-->
    