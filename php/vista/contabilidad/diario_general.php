<?php  
require_once("panel.php");

?>
  <script type="text/javascript">

  function activar($this)
  {
    var tab = $this.id;
    if(tab=='tabla_submodulo')
    {
      $('#activo').val('2');

    }else
    {
      $('#activo').val('1');
    }

  }
    
  function consultar_datos()
  { 

    var agencia='<option value="">Seleccione Agencia</option>';
    var usu='<option value="">Seleccione Usuario</option>';
    $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/diario_generalC.php?drop=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) {       
        $.each(response.agencia, function(i, item){
          agencia+='<option value="'+response.agencia[i].Item+'">'+response.agencia[i].NomEmpresa+'</option>';
        });       
        $('#DCAgencia').html(agencia);
        $.each(response.usuario, function(i, item){
          usu+='<option value="'+response.usuario[i].Codigo+'">'+response.usuario[i].CodUsuario+'</option>';
        });         
        $('#DCUsuario').html(usu);          
        
      }
    });

  }

  function sucursal_exis()
  { 

     $.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/diario_generalC.php?sucu_exi=true',
      type:  'post',
      dataType: 'json',
      /*beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },*/
        success:  function (response) { 
        if(response == 1)
        {
          $("#CheckAgencia").show();
          $('#DCAgencia').show();
          $('#lblAgencia').show();
        } else
        {
          $("#CheckAgencia").hide();
          $('#DCAgencia').hide();
          $('#lblAgencia').hide();
        }     
        
      }
    });

  }


  function libro_general()
  { 
          var parametros= {
            'OpcT':$("#OpcT").is(':checked'),
            'OpcCI':$("#OpcCI").is(':checked'),
            'OpcCE':$("#OpcCE").is(':checked'),
            'OpcCD':$("#OpcCD").is(':checked'),
            'OpcA':$("#OpcA").is(':checked'),
            'OpcND':$("#OpcND").is(':checked'),
            'OpcNC':$("#OpcNC").is(':checked'),
            'CheckNum':$("#CheckNum").is(':checked'),
            'TextNumNo':$('#TextNumNo').val(),
            'TextNumNo1':$('#TextNumNo1').val(),
            'CheckUsuario':$("#CheckUsuario").is(':checked'),
            'DCUsuario':$('#DCUsuario').val(),
            'CheckAgencia':$("#CheckAgencia").is(':checked'),
            'DCAgencia':$('#DCAgencia').val(),
            'DCAgencia':$('#DCAgencia').val(),
            'Fechaini':$('#txt_desde').val(),
            'Fechafin':$('#txt_hasta').val(),
          }

            $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/diario_generalC.php?consultar_libro=true',
      type:  'post',
      //dataType: 'json',
      beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_').html(spiner);
      },
        success:  function (response) { 
        libro_general_saldos(); 
        libro_submodulo();    
         $('#tabla_').html(response);
           var nFilas = $("#tabla_ tr").length;
         $('#num_r').html(nFilas-1);      
        
      }
    });

  }

  function libro_submodulo()
  { 
          var parametros= {
            'OpcT':$("#OpcT").is(':checked'),
            'OpcCI':$("#OpcCI").is(':checked'),
            'OpcCE':$("#OpcCE").is(':checked'),
            'OpcCD':$("#OpcCD").is(':checked'),
            'OpcA':$("#OpcA").is(':checked'),
            'OpcND':$("#OpcND").is(':checked'),
            'OpcNC':$("#OpcNC").is(':checked'),
            'CheckNum':$("#CheckNum").is(':checked'),
            'TextNumNo':$('#TextNumNo').val(),
            'TextNumNo1':$('#TextNumNo1').val(),
            'CheckUsuario':$("#CheckUsuario").is(':checked'),
            'DCUsuario':$('#DCUsuario').val(),
            'CheckAgencia':$("#CheckAgencia").is(':checked'),
            'DCAgencia':$('#DCAgencia').val(),
            'DCAgencia':$('#DCAgencia').val(),
            'Fechaini':$('#txt_desde').val(),
            'Fechafin':$('#txt_hasta').val(),
          }

            $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/diario_generalC.php?consultar_submodulo=true',
      type:  'post',
      //dataType: 'json',
      beforeSend: function () {   
           var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
         $('#tabla_submodulo').html(spiner);
      },
        success:  function (response) { 
          if(response)
          {
            $('#tabla_submodulo').html(response);
          }
                
      }
    });

  }

  function libro_general_saldos()
  { 
          var parametros= {
            'OpcT':$("#OpcT").is(':checked'),
            'OpcCI':$("#OpcCI").is(':checked'),
            'OpcCE':$("#OpcCE").is(':checked'),
            'OpcCD':$("#OpcCD").is(':checked'),
            'OpcA':$("#OpcA").is(':checked'),
            'OpcND':$("#OpcND").is(':checked'),
            'OpcNC':$("#OpcNC").is(':checked'),
            'CheckNum':$("#CheckNum").is(':checked'),
            'TextNumNo':$('#TextNumNo').val(),
            'TextNumNo1':$('#TextNumNo1').val(),
            'CheckUsuario':$("#CheckUsuario").is(':checked'),
            'DCUsuario':$('#DCUsuario').val(),
            'CheckAgencia':$("#CheckAgencia").is(':checked'),
            'DCAgencia':$('#DCAgencia').val(),
            'DCAgencia':$('#DCAgencia').val(),
            'Fechaini':$('#txt_desde').val(),
            'Fechafin':$('#txt_hasta').val(),
          }

            $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/diario_generalC.php?consultar_libro_1=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {   
           //var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        // $('#tabla_').html(spiner);
      },
        success:  function (response){
        if(response)
        {
          $('#debe').html(response.debe.toFixed(2));
          $('#haber').html(response.haber.toFixed(2));
          $('#debe_me').html(response.debe_me.toFixed(2));          
          $('#haber_me').html(response.haber_me.toFixed(2));

        }       
         
        
      }
    });

  }

  /*function reporte_libro_1()
  { 
          var parametros= {
            'OpcT':$("#OpcT").is(':checked'),
            'OpcCI':$("#OpcCI").is(':checked'),
            'OpcCE':$("#OpcCE").is(':checked'),
            'OpcCD':$("#OpcCD").is(':checked'),
            'OpcA':$("#OpcA").is(':checked'),
            'OpcND':$("#OpcND").is(':checked'),
            'OpcNC':$("#OpcNC").is(':checked'),
            'CheckNum':$("#CheckNum").is(':checked'),
            'TextNumNo':$('#TextNumNo').val(),
            'TextNumNo1':$('#TextNumNo1').val(),
            'CheckUsuario':$("#CheckUsuario").is(':checked'),
            'DCUsuario':$('#DCUsuario').val(),
            'CheckAgencia':$("#CheckAgencia").is(':checked'),
            'DCAgencia':$('#DCAgencia').val(),
            'DCAgencia':$('#DCAgencia').val(),
            'Fechaini':$('#txt_desde').val(),
            'Fechafin':$('#txt_hasta').val(),
          }

            $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/diario_generalC.php?reporte_libro_1=true',
      type:  'post',
      dataType: 'json',
      beforeSend: function () {   
           //var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'     
        // $('#tabla_').html(spiner);
      },
        success:  function (response){
        if(response)
        {
        //  $('#debe').html(response.debe.toFixed(2));
        //  $('#haber').html(response.haber.toFixed(2));
        //  $('#debe_me').html(response.debe_me.toFixed(2));          
        //  $('#haber_me').html(response.haber_me.toFixed(2));

        }       
         
        
      }
    });

  }
*/


  function mostrar_campos()
  {
    console.log($("#CheckNum").is(':checked'));

    if($("#CheckNum").is(':checked'))
    {
      $('#campos').css('display','block');
      //$('#TextNumNo1').css('display','block');
    }else
    {
      $('#campos').css('display','none');
    //  $('#TextNumNo1').css('display','none');
    }
  }



  $(document).ready(function()
  {
    consultar_datos();
    libro_general();
    sucursal_exis();

    $('#txt_CtaI').keyup(function(e){ 
      if(e.keyCode != 46 && e.keyCode !=8)
      {
        validar_cuenta(this);
      }
     })

    $('#txt_CtaF').keyup(function(e){ 
      if(e.keyCode != 46 && e.keyCode !=8)
      {
        validar_cuenta(this);
      }
     })


       $('#imprimir_excel').click(function(){         

        var url = '../controlador/diario_generalC.php?reporte_libro_1_excel=true&OpcT='+$("#OpcT").is(':checked')+'&OpcCI='+$("#OpcCI").is(':checked')+'&OpcCE='+$("#OpcCE").is(':checked')+'&OpcCD='+$("#OpcCD").is(':checked')+'&OpcA='+$("#OpcA").is(':checked')+'&OpcND='+$("#OpcND").is(':checked')+'&OpcNC='+$("#OpcNC").is(':checked')+'&CheckNum='+$("#CheckNum").is(':checked')+'&TextNumNo='+$('#TextNumNo').val()+'&TextNumNo1='+$('#TextNumNo1').val()+'&CheckUsuario='+$("#CheckUsuario").is(':checked')+'&DCUsuario='+$('#DCUsuario').val()+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&DCAgencia='+$('#DCAgencia').val()+'&DCAgencia='+$('#DCAgencia').val()+'&Fechaini='+$('#txt_desde').val()+'&Fechafin='+$('#txt_hasta').val();
            window.open(url, '_blank');
       });

       $('#imprimir_excel_2').click(function(){         

        var url = '../controlador/diario_generalC.php?reporte_libro_2_excel=true&OpcT='+$("#OpcT").is(':checked')+'&OpcCI='+$("#OpcCI").is(':checked')+'&OpcCE='+$("#OpcCE").is(':checked')+'&OpcCD='+$("#OpcCD").is(':checked')+'&OpcA='+$("#OpcA").is(':checked')+'&OpcND='+$("#OpcND").is(':checked')+'&OpcNC='+$("#OpcNC").is(':checked')+'&CheckNum='+$("#CheckNum").is(':checked')+'&TextNumNo='+$('#TextNumNo').val()+'&TextNumNo1='+$('#TextNumNo1').val()+'&CheckUsuario='+$("#CheckUsuario").is(':checked')+'&DCUsuario='+$('#DCUsuario').val()+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&DCAgencia='+$('#DCAgencia').val()+'&DCAgencia='+$('#DCAgencia').val()+'&Fechaini='+$('#txt_desde').val()+'&Fechafin='+$('#txt_hasta').val();
            window.open(url, '_blank');
       });

       $('#imprimir_pdf').click(function(){
       var url = '../controlador/diario_generalC.php?reporte_libro_1=true&OpcT='+$("#OpcT").is(':checked')+'&OpcCI='+$("#OpcCI").is(':checked')+'&OpcCE='+$("#OpcCE").is(':checked')+'&OpcCD='+$("#OpcCD").is(':checked')+'&OpcA='+$("#OpcA").is(':checked')+'&OpcND='+$("#OpcND").is(':checked')+'&OpcNC='+$("#OpcNC").is(':checked')+'&CheckNum='+$("#CheckNum").is(':checked')+'&TextNumNo='+$('#TextNumNo').val()+'&TextNumNo1='+$('#TextNumNo1').val()+'&CheckUsuario='+$("#CheckUsuario").is(':checked')+'&DCUsuario='+$('#DCUsuario').val()+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&DCAgencia='+$('#DCAgencia').val()+'&DCAgencia='+$('#DCAgencia').val()+'&Fechaini='+$('#txt_desde').val()+'&Fechafin='+$('#txt_hasta').val();
                 
           window.open(url, '_blank');
       });

       $('#imprimir_pdf_2').click(function(){
       var url = '../controlador/diario_generalC.php?reporte_libro_2=true&OpcT='+$("#OpcT").is(':checked')+'&OpcCI='+$("#OpcCI").is(':checked')+'&OpcCE='+$("#OpcCE").is(':checked')+'&OpcCD='+$("#OpcCD").is(':checked')+'&OpcA='+$("#OpcA").is(':checked')+'&OpcND='+$("#OpcND").is(':checked')+'&OpcNC='+$("#OpcNC").is(':checked')+'&CheckNum='+$("#CheckNum").is(':checked')+'&TextNumNo='+$('#TextNumNo').val()+'&TextNumNo1='+$('#TextNumNo1').val()+'&CheckUsuario='+$("#CheckUsuario").is(':checked')+'&DCUsuario='+$('#DCUsuario').val()+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&DCAgencia='+$('#DCAgencia').val()+'&DCAgencia='+$('#DCAgencia').val()+'&Fechaini='+$('#txt_desde').val()+'&Fechafin='+$('#txt_hasta').val();
                 
           window.open(url, '_blank');
       });

       
    });



  </script>

   <div style="padding-left: 20px;padding-right: 20px">
    <div class="row">
      <div class="col-lg-4 col-sm-4 col-md-8 col-xs-12">
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
            <a  href="panel.php?sa=s" title="Salir de modulo" class="btn btn-default">
              <img src="../../img/png/salire.png">
            </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2 col-lg-2">
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
        <div class="col-xs-2 col-md-2 col-sm-2">
          <a href="../controlador/catalogoCtaC.php?imprimir_pdf=true" class="btn btn-default" title="Autorizar"  target="_blank" id='imprimir_pdf'>
            <img src="../../img/png/autorizar1.png">
          </a>
        </div>
        <div class="col-xs-2 col-md-2 col-sm-2">
          <button title="Consultar Catalogo de cuentas"  class="btn btn-default" onclick="libro_general();">
            <img src="../../img/png/consultar.png" >
          </button>
        </div>
      </div>
      <div class="col-sm-8 col-lg-8 col-md-4 col-xs-12">
        <div class="col-xs-3 col-md-3 col-sm-3 col-lg-3">
          <b>Desde:</b><br>
            <input type="date" min="01-01-1900" max="01-01-2030"  name="txt_desde" id="txt_desde" value="<?php echo date("Y-m-d");?>" onblur="consultar_datos();">  
        </div>   
        <div class="col-xs-3 col-md-3 col-sm-3 col-lg-3">
          <b>Hasta:</b><br>
            <input type="date"  min="01-01-1900" max="01-01-2030" name="txt_hasta" id="txt_hasta" value="<?php echo date("Y-m-d");?>" onblur="consultar_datos();"> 
        </div>   
      </div>
     <div class="row">  
        <div class="col-sm-12"><br>
          <div class="panel panel-default">
             <div class="panel-heading" style="padding: 2px">COMPROBANTES DE</div>
            <div class="panel-body">
            <div class="col-sm-1">
              <label class="radio-inline"><input type="radio" name="OpcP" id="OpcT" onchange="consultar_datos();" checked=""><b>Todos</b></label> 
            </div>
            <div class="col-sm-1">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcCI" onchange="consultar_datos();"><b>Ingresos</b></label>
            </div>
            <div class="col-md-1">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcCE" onchange="consultar_datos();"><b>Egreso</b></label>          
            </div>
            <div class="col-md-1">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcCD" onchange="consultar_datos();"><b>Diario</b></label>
            </div>
                    <div class="col-md-2">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcND" onchange="consultar_datos();"><b>Notas de debito</b></label>
            </div>
            <div class="col-md-2">
              <label class="radio-inline"><input type="radio" name="OpcP" id="OpcNC" onchange="consultar_datos();"><b>Notas de credito</b></label>
            </div>
            <div class="col-md-1">
                <label class="radio-inline"><input type="radio" name="OpcP" id="OpcA" onchange="consultar_datos();"><b>Anulado</b></label>
            </div>
            <div class="col-md-2">
              <label class="radio-inline"><input type="checkbox" name="CheckNum" id="CheckNum" onchange="mostrar_campos();"><b> Desde el No: </b></label>                      
            </div>
            <div class="col-md-1" id="campos" style="display: none">
              <input type="text" style="width:30px;" name="TextNumNo" id="TextNumNo" value="0">
              <input type="text" style="width:30px;" name="TextNumNo1" id="TextNumNo1" value="0"> 
            </div>  
           </div>
        </div>                
      </div>
    </div>
    <div class="row">
      <div class="col-sm-8">
        <div class="col-sm-2">
          <label class="radio-inline input-sm"><input type="checkbox" name="CheckUsuario" id="CheckUsuario" onchange="consultar_datos();"> <b>Por Usuario</b></label>          
        </div>
        <div class="col-sm-4">
          <select class="form-control input-sm" id="DCUsuario" >
            <option value="">Seleccione usuario</option>
          </select>           
        </div>  
      <div class="col-sm-2">        
        <label class="radio-inline input-sm" id='lblAgencia'><input type="checkbox" name="CheckAgencia" id="CheckAgencia" onchange="consultar_datos();"> <b>Por Agencia</b></label>
      </div>
      <div class="col-sm-4">
        <select class="form-control input-sm" id="DCAgencia">
          <option value="">Seleccione Agencia</option>
        </select>
     </div> 
     </div>             
    </div>       
    <!--seccion de panel-->
    <br>
    <div class="row">
      <input type="input" name="activo" id="activo" value="1" hidden="">
      <div class="col-sm-12">
        <ul class="nav nav-tabs">
           <li class="active">
            <a data-toggle="tab" href="#home" id="titulo_tab" onclick="activar(this)">DIARIO GENERAL</a></li>
           <li>
            <a data-toggle="tab" href="#menu1" id="titulo2_tab" onclick="activar(this)">SUB MODULOS</a></li>
        </ul>       
          <div class="tab-content" >
            <div class="text-right">
              Registros: <b id="num_r"></b>
            </div>
            <div id="home" class="tab-pane fade in active">
              <br>
               <div class="table-responsive" id="tabla_">
                                
               </div>
             </div>
             <div id="menu1" class="tab-pane fade">
              <br>
               <div class="table-responsive" id="tabla_submodulo">
                              
               </div>
             </div>           
          </div>
      </div>
    </div>
     <div class="row"><br>
        <div class="col-sm-12">
          <div class="table-responsive">
            <table>
              <tr>
                <td><b>Total Debe</b></td>
                <td id="debe"></td>
                <td><b>Total Haber</b></td>
                <td id="haber"></td>
                <td><b>Debe - Haber</b></td>
                <td></td>
              </tr>
              <tr>
                <td><b>Total Debe ME</b></td>
                <td id="debe_me"></td>
                <td><b>Total Haber ME</b></td>
                <td id="haber_me"></td>
                <td><b>Debe - Haber ME</b></td>
                <td></td>
              </tr>
            </table>
          </div>          
        </div>        
      </div>  
   </div>