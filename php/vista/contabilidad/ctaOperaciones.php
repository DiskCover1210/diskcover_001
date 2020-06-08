<?php  
require_once("panel.php");
?>
<link rel="stylesheet" href="../../lib/dist/css/style_acordeon.css">
<script type="text/javascript">
  
  $(document).ready(function()
  {
   cargar_cuentas();

  });

  function cargar_cuentas()
  {
      $.ajax({
     // data:  {parametros:parametros},
      url:   '../controlador/ctaOperacionesC.php?cuentas=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {   
         $('#myModal_espera').modal('show');   
      },
        success:  function (response) { 
          if(response)
          {
            $('#tabla').html(response);
            $('#myModal_espera').modal('hide');   
          }
                
      }
    });
  }
</script>
   <div class="container">
    <div class="row">
      <div class="col-lg-4 col-sm-4 col-md-8 col-xs-12">
          <div class="col-xs-2 col-md-2 col-sm-2">
                 <a  href="panel.php?sa=s" title="Salir de modulo" class="btn btn-default">
                 <img src="../../img/png/salire.png">
                 </a>
                </div>
              <div class="col-xs-2 col-md-2 col-sm-2">
                 <button type="button" class="btn btn-default" data-toggle="dropdown">
                    <img src="../../img/png/impresora.png">
                   </button>
                  <ul class="dropdown-menu">
                   <li><a href="#" id="imprimir_pdf">Diario General</a></li>
                    <li><a href="#" id="imprimir_pdf_2">Libro Diario</a></li>
                  </ul>
              </div>
                <div class="col-xs-2 col-md-2 col-sm-2">                 
                 <button type="button" class="btn btn-default" data-toggle="dropdown">
                     <img src="../../img/png/table_excel.png">
                   </button>
                  <ul class="dropdown-menu">
                   <li><a href="#" id="imprimir_excel">Diario General</a></li>
                    <li><a href="#" id="imprimir_excel_2">Libro Diario</a></li>
                  </ul>
                </div>
                <!-- <div class="col-xs-2 col-md-2 col-sm-2">
                 <a href="../controlador/catalogoCtaC.php?imprimir_pdf=true" class="btn btn-default" title="Autorizar"  target="_blank" id='imprimir_pdf'>
                 <img src="../../img/png/autorizar1.png">
                 </a>
                </div> -->
                <div class="col-xs-2 col-md-2 col-sm-2">
                 <button title="Consultar Catalogo de cuentas"  class="btn btn-default" onclick="libro_general();">
                 <img src="../../img/png/consultar.png" >
                 </button>
                </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6" id="tabla">

      </div>

    </div>
  </div>

<!-- partial:index.partial.html -->

<!-- partial -->
<!-- //<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script> -->

