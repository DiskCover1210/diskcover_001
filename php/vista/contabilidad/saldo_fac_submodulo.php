<?php  
require_once("panel.php");

?>
	<script type="text/javascript">
		
		function cuenta()
		{
			if($("#CheqCta").is(':checked'))
			{
				$('#select_cuenta').css('display','block')

			}else
			{
				$('#select_cuenta').css('display','none')
			}
		}

		function detalle()
		{
			if($("#CheqDet").is(':checked'))
			{
				$('#select_detalle').css('display','block')

			}else
			{
				$('#select_detalle').css('display','none')
			}
		}
		function beneficiario()
		{

			if($("#CheqIndiv").is(':checked'))
			{
				$('#select_beneficiario').css('display','block')

			}else
			{
				$('#select_beneficiario').css('display','none')
			}

		}

		function cargar_cbx()
		{
		var select = $('#tipo_cuenta').val();
		if(select =='G' || select =='I'|| select == 'CC')
		{
			$('#lbl_bene').text('SubModulo');
		}
		else
		{
			$('#lbl_bene').text('Beneficiario');
		}
		var cta='<option value="">Seleccione cuenta</option>';
		var det='<option value="">Seleccione Detalle</option>';
		var bene='<option value="">Seleccione Beneficiario</option>';
		$.ajax({
			data:  {select:select},
			url:   '../controlador/saldo_fac_submoduloC.php?cargar=true',
			type:  'post',
			dataType: 'json',
			beforeSend: function () {					
				$('#titulo_tab').text('');
				$('#titulo2_tab').text('');
			},
			success:  function (response) {
				consultar_datos();

				$('#titulo_tab').text(response.titulo);
				$('#titulo2_tab').text(response.titulo+' TEMPORIZADO');
				//llena select de cta				
				$('#select_cuenta').html(cta);
				$.each(response.cta, function(i, item){
					cta+='<option value="'+response.cta[i].Nombre_Cta+'">'+response.cta[i].Nombre_Cta+'</option>';
				});
				$('#select_cuenta').html(cta);

				//llena select de detalle				
				$('#select_detalle').html(cta);
				$.each(response.det, function(i, item){
					det+='<option>'+response.det[i].Detalle_SubCta+'</option>';
				});
				$('#select_detalle').html(det);
				//llena select de beneficiario				
				$('#select_beneficiario').html(bene);
				$.each(response.beneficiario, function(i, item){
					bene+='<option value="'+response.beneficiario[i].Codigo+'">'+response.beneficiario[i].Cliente+'</option>';
				});
				$('#select_beneficiario').html(bene);
				console.log(response);
				//console.log(response.titulo);
			}
		});
	}

	function consultar_datos()
	{
		var parametros =
		{
			'tipocuenta':$('#tipo_cuenta').val(),
			'ChecksubCta':$("#ChecksubCta").is(':checked'),
			'OpcP':$("#OpcP").is(':checked'),
			'CheqCta':$("#CheqCta").is(':checked'),
			'CheqDet':$("#CheqDet").is(':checked'),
			'CheqIndiv':$("#CheqIndiv").is(':checked'),
			'fechaini':$('#txt_desde').val(),
			'fechafin':$('#txt_hasta').val(),
			'Cta':$('#select_cuenta').val(),
			'CodigoCli':$('#select_beneficiario').val(),
			'DCDet':$('#select_detalle').val(),
		}
		$.ajax({
			data:  {parametros:parametros},
			url:   '../controlador/saldo_fac_submoduloC.php?consultar=true',
			type:  'post',
			//dataType: 'json',
			beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);

         $('#myModal_espera').modal('show');  
			},
				success:  function (response) {
				
				 $('#tabla_').html(response);				 	
				 consultar_datos_tempo();
			    
         $('#myModal_espera').modal('hide');  
				
				
			console.log(response);
				//console.log(response.titulo);
			}
		});

	}

	function consultar_datos_tempo()
	{
		var parametros =
		{
			'tipocuenta':$('#tipo_cuenta').val(),
			'ChecksubCta':$("#ChecksubCta").is(':checked'),
			'OpcP':$("#OpcP").is(':checked'),
			'CheqCta':$("#CheqCta").is(':checked'),
			'CheqDet':$("#CheqDet").is(':checked'),
			'CheqIndiv':$("#CheqIndiv").is(':checked'),
			'fechaini':$('#txt_desde').val(),
			'fechafin':$('#txt_hasta').val(),
			'Cta':$('#select_cuenta').val(),
			'CodigoCli':$('#select_beneficiario').val(),
			'DCDet':$('#select_detalle').val(),
		}
		$.ajax({
			data:  {parametros:parametros},
			url:   '../controlador/saldo_fac_submoduloC.php?consultar_tempo=true',
			type:  'post',
			//dataType: 'json'
			beforeSend: function () {		
			     var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 $('#tabla_temp').html(spiner);
			},			
			success:  function (response) {
				
				 $('#tabla_temp').html(response);
			    
				//$('#tabla_temp').html(response.tabla_2);
				
			//console.log(response);
				//console.log(response.titulo);
			}
		});

	}

	function activar($this)
	{
		var tab = $this.id;
		if(tab=='titulo2_tab')
		{
			$('#activo').val('2');

		}else
		{
			$('#activo').val('1');
		}

	}

	$(document).ready(function()
	{
    
       $("#descargar_pdf").click(function(){

       	if($('#activo').val()=='1'){   

       	var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?Mostrar_pdf=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&OpcP='+$("#OpcP").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&CheqDet='+$("#CheqDet").is(':checked')+'&CheqIndiv='+$("#CheqIndiv").is(':checked')+'&CodigoCli='+$('#select_beneficiario').val()+'&Cta='+$('#select_cuenta').val()+'&DCDet='+$('#select_detalle').val()+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&tipo=mostrar&tabla=normal'; 
       }else{

      	var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?Mostrar_pdf=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&OpcP='+$("#OpcP").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&CheqDet='+$("#CheqDet").is(':checked')+'&CheqIndiv='+$("#CheqIndiv").is(':checked')+'&CodigoCli='+$('#select_beneficiario').val()+'&Cta='+$('#select_cuenta').val()+'&DCDet='+$('#select_detalle').val()+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&tipo=imprimir&tabla=temp';  
      	} 	    
      	   
      	    window.open(url, '_blank');
       });



       $('#descargar_excel').click(function(){

       		if($('#activo').val()=='1'){   

       	var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?Mostrar_excel=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&OpcP='+$("#OpcP").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&CheqDet='+$("#CheqDet").is(':checked')+'&CheqIndiv='+$("#CheqIndiv").is(':checked')+'&CodigoCli='+$('#select_beneficiario').val()+'&Cta='+$('#select_cuenta').val()+'&DCDet='+$('#select_detalle').val()+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&tipo=mostrar&tabla=normal'; 
       }else{

      	var url ='../../lib/fpdf/reportes_Saldo_fac_subMod.php?Mostrar_excel=true&tipocuenta='+$('#tipo_cuenta').val()+'&ChecksubCta='+$("#ChecksubCta").is(':checked')+'&OpcP='+$("#OpcP").is(':checked')+'&CheqCta='+$("#CheqCta").is(':checked')+'&CheqDet='+$("#CheqDet").is(':checked')+'&CheqIndiv='+$("#CheqIndiv").is(':checked')+'&CodigoCli='+$('#select_beneficiario').val()+'&Cta='+$('#select_cuenta').val()+'&DCDet='+$('#select_detalle').val()+'&fechaini='+$('#txt_desde').val()+'&fechafin='+$('#txt_hasta').val()+'&tipo=imprimir&tabla=temp';  
      	} 	    
      	   
      	    window.open(url, '_blank');
       });

       
    });



	</script>

   <div class="container">
   <div class="col-ms-12">
	  <div class="row">          
	  	     <div class="col-sm-4">
            	<a  href="./contabilidad.php?mod=contabilidad#" title="Salir de modulo" class="btn btn-default">
            		<img src="../../img/png/salire.png">
            	</a>
            	 <a href="#" class="btn btn-default" id='descargar_pdf'>
            		<img src="../../img/png/impresora.png">
            	</a>
            	<button title="Consultar SubModulo"  class="btn btn-default" onclick="consultar_datos();">
            		<img src="../../img/png/archivero1.png" >
            	</button>
            	<a href="" title="Presenta Resumen de costos"  class="btn btn-default">
            		<img src="../../img/png/resumen.png">
            	</a>
            	<a href="#"  class="btn btn-default" title="Descargar excel" id='descargar_excel'>
            		<img src="../../img/png/table_excel.png">
            	</a>
	  	     </div>
	  	<div class="col-sm-5">
	  		<div class="row">
	  			<div class="col-xs-4">
             		<b>Desde:</b>
             		<br>
             	   <input type="date" style="width:125px" name="txt_desde" id="txt_desde" value="<?php echo date("Y-m-d");?>" onblur="consultar_datos();">
             	</div>
                <div class="col-xs-4">
             	   <b>Hasta:</b>
             	<br>
             	   <input type="date" style="width:125px" name="txt_hasta" id="txt_hasta" value="<?php echo date("Y-m-d");?>" onblur="consultar_datos();"> 
             	</div>             	
             	<div class="col-xs-3">
             	<br>
             		<select id="tipo_cuenta" name="tipo_cuenta" onchange="cargar_cbx()">
             	   	<option value="C">CxC</option>
             	   	<option value="P">CxP</option>
             	   	<option value="I">Ingresos</option>
             	   	<option value="G">Egresos</option>
             	   	<option value="CC">Centro de costos</option>             	   	
             	   </select>             		
                 </div>	  		
	  			
	  		</div>             	
	  	</div>
	  	<div class="col-sm-3">
             	<div class="row">
             		<div class="col-sm-12">
             	    <label class="radio-inline"><input type="radio" name="OpcP" value="1" id="OpcP" checked=""><b>Pendientes</b></label>
          		    <label class="radio-inline"><input type="radio" name="OpcP" value="" id="OpcC"><b>Canceladas</b></label>              			
             		</div>
             		<div class="col-sm-12">
             		<label class="form-check-label" style="font-size: 12px"><input type="checkbox" name="chekSubCta" id="chekSubCta"> Procesar con Detalle de SubModulo</label>
             		</div>             		
                </div>
         </div>	

	  </div>
	  <div class="row">	  		
	  <div class="col-sm-4">
	  	   <label class="form-check-label"><input type="checkbox" name="CheqCta" id="CheqCta" onchange="cuenta()" value="true"> Por Cta.</label>
	  		<select class="form-control" id="select_cuenta" style="display: none;" onchange="consultar_datos();">
	  			<option value="">Seleccione cuenta</option>
	  		</select>
	  		
	  			
	  			
	  								
	  						
	  	
	  </div>
	  <div class="col-sm-4">
	  	<label class="form-check-label"><input type="checkbox" name="CheqDet" id="CheqDet" onchange="detalle()"> Por det</label>	  	 
	  		  <select class="form-control"   id="select_detalle" style="display: none;" onchange="consultar_datos();">
	  			 <option value="">Seleccione detalle</option>
	  		  </select>
	  	
	  </div>
	  <div class="col-sm-4">
	  	<label class="form-check-label"><input type="checkbox" name="CheqIndiv" id="CheqIndiv" onchange="beneficiario()"><span id="lbl_bene"> Beneficiario</span></label> 
	  			   <select  class="form-control" id="select_beneficiario" style="display: none;" onchange="consultar_datos();">
	  		     	<option value="">Seleccione Beneficiario</option>
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
	  		   	<a data-toggle="tab" href="#home" onclick="activar(this)" id="titulo_tab">SALDO DE CUENTAS POR COBRAR</a></li>
	  		   <li>
	  		   	<a data-toggle="tab" href="#menu1"  onclick="activar(this)" id="titulo2_tab">SALDO DE CUENTAS POR COBRAR TEMPORIZADO</a></li>
	  		</ul>
	  	    <div class="tab-content" style="background-color:#E7F5FF">
	  	    	<div id="home" class="tab-pane fade in active">
	  	    		<br>
	  	    	   <div class="table-responsive content-wrapper" id="tabla_">
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>
	  	    	 <div id="menu1" class="tab-pane fade">
	  	    	 	<br>
	  	    	   <div class="table-responsive content-wrapper" id="tabla_temp">
	  	    	   	  	    	   	
	  	    	   </div>
	  	    	 </div>
	  	    </div>
	  	</div>
	  </div>
	</div>