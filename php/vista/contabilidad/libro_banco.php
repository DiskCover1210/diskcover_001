<script type="text/javascript">
	var Individual = false;
	$(document).ready(function()
	{
		llenar_combobox();
		llenar_combobox_cuentas();    
		   $('#imprimir_pdf').click(function(){
            var url = '../controlador/libro_bancoC.php?imprimir_pdf=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val();
                 
      	   window.open(url, '_blank');
       });

	
	  $('#imprimir_excel').click(function(){
            var url = '../controlador/libro_bancoC.php?imprimir_excel=true&CheckUsu='+$("#CheckUsu").is(':checked')+'&CheckAgencia='+$("#CheckAgencia").is(':checked')+'&txt_CtaI='+$('#txt_CtaI').val()+'&txt_CtaF='+$('#txt_CtaF').val()+'&desde='+$('#desde').val()+'&hasta='+$('#hasta').val()+'&DCAgencia='+$('#DCAgencia').val()+'&DCUsuario='+$('#DCUsuario').val()+'&DCCtas='+$('#DCCtas').val()+'&OpcUno='+$('#OpcU').val()+'&PorConceptos='+Individual+'&submodulo=false';
                 
      	   window.open(url, '_blank');
       });

    });



    function fecha_fin()
    {
    	$fecha = $('#desde').val();
    	var date = new Date($fecha);
    	var primerDia = new Date(date.getFullYear(), date.getMonth(), 1);
    	var ultimoDia = new Date(date.getFullYear(), date.getMonth() + 1, 0);
    	var mes= date.getMonth()+1;

    	if(mes <10)
    	{
    		mes = '0'+mes;
    	}
    	$('#hasta').val(date.getFullYear()+"-"+mes+"-"+ultimoDia.getDate());
    	consultar_datos();
 

    }
		
	function consultar_datos()
	{
		var parametros =
		{
			'CheckUsu':$("#CheckUsu").is(':checked'),
			'CheckAgencia':$("#CheckAgencia").is(':checked'),
			'desde':$('#desde').val(),
			'hasta':$('#hasta').val(),	
			'DCAgencia':$('#DCAgencia').val(),
			'DCUsuario':$('#DCUsuario').val(),	
			'DCCtas':$('#DCCtas').val(),			
		}
		$titulo = 'Mayor de '+$('#DCCtas option:selected').html(),
		$.ajax({
			data:  {parametros:parametros},
			url:   '../controlador/libro_bancoC.php?consultar=true',
			type:  'post',
			//dataType: 'json',
			beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				 $('#myModal_espera').modal('show');
			},
				success:  function (response) {
				consultar_totales();
				
				 $('#tabla_').html(response);
				 var nFilas = $("#tabla_ tr").length;
				 $('#num_r').html(nFilas-1);	
				 $('#myModal_espera').modal('hide');	
				 $('#tit').text($titulo);			    
				
			}
		});

	}

	function consultar_totales()
	{
		var parametros =
		{
			'CheckUsu':$("#CheckUsu").is(':checked'),
			'CheckAgencia':$("#CheckAgencia").is(':checked'),
			'desde':$('#desde').val(),
			'hasta':$('#hasta').val(),	
			'DCAgencia':$('#DCAgencia').val(),
			'DCUsuario':$('#DCUsuario').val(),	
			'DCCtas':$('#DCCtas').val(),			
		}
		$titulo = 'Mayor de '+$('#DCCtas option:selected').html(),
		$.ajax({
			data:  {parametros:parametros},
			url:   '../controlador/libro_bancoC.php?consultar_tot=true',
			type:  'post',
			dataType: 'json',
			beforeSend: function () {		
			  //    var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 // $('#tabla_').html(spiner);
				// $('#myModal_espera').modal('show');
			},
				success:  function (response) {
					$('#debe').text(response.Debe);
					$('#haber').text(response.Haber);					
					$('#saldo_ant').text(response.SalAnt);
					$('#saldo').text(response.Saldo);

					$('#debe_').text(response.Debe_ME);
					$('#haber_').text(response.Haber_ME);
					$('#saldo_ant_').text(response.SalAnt_);
					$('#saldo_').text(response.Saldo_ME);

				console.log(response);
			}
		});

	}

		
	function llenar_combobox()
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

	function llenar_combobox_cuentas()
	{	

		var agencia='<option value="">Seleccione Cuenta</option>';
		$.ajax({
			//data:  {ini:ini,fin:fin},
			url:   '../controlador/libro_bancoC.php?cuentas=true',
			type:  'post',
			dataType: 'json',
			/*beforeSend: function () {		
			     var spiner = '<div class="text-center"><img src="../../img/gif/proce.gif" width="100" height="100"></div>'			
				 $('#tabla_').html(spiner);
			},*/
				success:  function (response) {	
				var count=0;			
				$.each(response, function(i, item){
					if(count == 0)
					{
					  agencia+='<option value="'+response[i].Codigo+'" selected>'+response[i].Nombre_Cta+'</option>';
				    }else
				    {
				      agencia+='<option value="'+response[i].Codigo+'">'+response[i].Nombre_Cta+'</option>';	
				    } 

					count = count+1;
				});				

				$('#DCCtas').html(agencia);					    
		        consultar_datos(true,Individual);				
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
              <a href="#" id="imprimir_pdf" class="btn btn-default">
                 <img src="../../img/png/impresora.png">
              </a>                          	
            </div>
            	
            <div class="col-xs-2 col-md-2 col-sm-2">
            		<a href="#" id="imprimir_excel"  class="btn btn-default">
            	      <img src="../../img/png/table_excel.png">
            	     </a>                          	
                </div>

            <div class="col-xs-2 col-md-2 col-sm-2">
            	<button title="Consultar Mayores auxiliares"  class="btn btn-default" onclick="consultar_datos(true,Individual);">
            		<img src="../../img/png/consultar.png" >
            	</button>
            	</div>		
   		</div>
   		
   	</div>
	<div class="row">          
              
	  	
	  	<div class="col-sm-3"><br>
	  		<b>Desde:</b>
            <input type="date" name="desde" id="desde" class="input-sm"  value="<?php echo date("Y-m-d");?>" onblur="fecha_fin()">
			<br>
            <b>Hasta:&nbsp;</b>
            <input type="date" name="hasta" id="hasta"  class="input-sm"  value="<?php echo date("Y-m-d");?>" onblur="consultar_datos(true,Individual);">  	              	
	  	</div>

	  	<div class="col-sm-3">
                <input type="checkbox" name="CheckUsu" id="CheckUsu">  <b>Por usuario</b>
                <select class="form-control input-sm" id="DCUsuario"  onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione usuario</option>
                </select>
          	    <input type="checkbox" name="CheckAgencia" id="CheckAgencia">  <b>Agencia</b>
          	     <select class="form-control input-sm" id="DCAgencia" onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione agencia</option>
                </select>             
        </div>
        <div class="col-sm-3">
        	<b>Por cuenta</b>
                <select class="form-control input-sm" id="DCCtas" onchange="consultar_datos(true,Individual);">
                	<option value="">Seleccione cuenta</option>
                </select>
          	   
        </div>		
	</div>
	  <!--seccion de panel-->
	  <div class="row">
	  	<input type="input" name="OpcU" id="OpcU" value="true" hidden="">
	  	<div class="col-sm-12">
	  		<ul class="nav nav-tabs">
	  		   <li class="active">
	  		   	<a data-toggle="tab" href="#home" id="titulo_tab" onclick="activar(this)"><b id="tit">Mayores auxiliares</b></a></li>
	  		</ul>
	  	    <div class="tab-content" style="background-color:#E7F5FF">
	  	    	<div id="home" class="tab-pane fade in active">
	  	    			<div class="text-right">
	  	    				Registros:<b id="num_r">0</b>
	  	    			</div>
	  	    			<br>
	  	    	   <div class="table-responsive" id="tabla_" style="overflow-y: scroll; height:450px; width: auto;">
	  	    	   		  	    	   	
	  	    	   </div>
	  	    	 </div>		  	    	  	    	
	  	    </div>
	  	    <br>
	  	    <div class="table-responsive">
	  	    	<table>
	  	    	 <tr><td width="100px"><b>Saldo Ant MN:</b></td><td id="saldo_ant" width="70px">0.00</td><td width="70px"><b>Debe MN:</b></td><td id="debe" width="70px">0.00</td><td width="70px"><b>Haber MN:</b></td><td id="haber" width="70px">0.00</td><td width="70px"><b>Saldo MN:</b></td><td id="saldo" width="70px">0.00</td></tr>

	  	    	 <tr><td width="100px"><b>Saldo Ant ME:</b></td><td id="saldo_ant_" width="70px">0.00</td><td width="70px"><b>Debe ME:</b></td><td id="debe_" width="70px">0.00</td><td width="70px"><b>Haber ME:</b></td><td id="haber_" width="70px">0.00</td><td width="70px"><b>Saldo ME:</b></td><td id="saldo_" width="70px">0.00</td></tr>
	  	    	</table>	  	    	 	
	  	    </div> 
	  	</div>
	  </div>
	</div>