<?php  require_once("panel.php");@session_start();  date_default_timezone_set('America/Guayaquil');  //print_r($_SESSION['INGRESO']);die();?>
<script type="text/javascript">

  $(document).ready(function()
  {
  	cargar_registros();
  	
  });

   function cargar_registros()
   {
   
    var parametros = 
    {
      'grupo':$('#txt_grupo').val(),
      'ci':$('#txt_ci').val(),
      'c':$('#txt_c').val(), 
    }
     $.ajax({
       data:  {parametros:parametros},
      url:   '../controlador/facturacion/lista_facturasC.php?tabla=true',
      type:  'post',
      dataType: 'json',
       // beforeSend: function () {
       //          $("#tbl_tabla").html('<tr><td colspan="7" class="text-center"><img src="../../img/gif/loader4.1.gif" width="250px"></td></tr>');
       //       },
      success:  function (response) { 
        // console.log(response);
       $('#tbl_tabla').html(response);
      }
    });

   }

   	function Ver_factura(id,serie,ci)
	{		 
		var url = '../controlador/facturacion/lista_facturasC.php?ver_fac=true&codigo='+id+'&ser='+serie+'&ci='+ci;		
		window.open(url,'_blank');
	}

    function reporte_pdf()
    {
       var url = '../controlador/facturacion/lista_facturasC.php?imprimir_pdf=true&';
       var datos =  $("#filtros").serialize();
        window.open(url+datos, '_blank');
         $.ajax({
             data:  {datos:datos},
             url:   url,
             type:  'post',
             dataType: 'json',
             success:  function (response) {  
          
          } 
           });

    }
    function generar_excel()
	{		
	  var cod = $('#txt_ci').val();
	   var url = '../controlador/detalle_estudianteC.php?imprimir_excel=true&codigo='+cod;
	   window.open(url);

	}

</script>

<div class="container-lg">
  <div class="row"><br>
    <div class="col-lg-6 col-sm-10 col-md-6 col-xs-12">
       <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <a  href="<?php $ruta = explode('&' ,$_SERVER['REQUEST_URI']); print_r($ruta[0].'#');?>" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="reporte_pdf()"><img src="../../img/png/pdf.png"></button>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-1">
            <button type="button" class="btn btn-default" title="Generar pdf" onclick="generar_excel()"><img src="../../img/png/table_excel.png"></button>
        </div>
 </div>
</div>
<div class="container">
	<form id="filtros">
		<div class="row">
			<div class="col-sm-2">
				<b>GRUPO</b>
				<input type="text" name="txt_grupo" id="txt_grupo" class="form-control input-sm">
			</div>
			<div class="col-sm-2">
				<b>CI / RUC</b>
				<input type="text" name="txt_ci" id="txt_ci" class="form-control input-sm" value="0701520546" onblur="cargar_registros();">
			</div>
			<div class="col-sm-2">
				<b>CLAVE</b>
				<input type="text" name="txt_c" id="txt_c" class="form-control input-sm">
			</div>			
		</div>
	</form>
	<div class="row">
		<h2>Listado de facturas</h2>
		<div id="tbl_tabla">
			
		</div>
		
	</div>
  
</div>
