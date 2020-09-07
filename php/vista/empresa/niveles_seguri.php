<?php  
require_once("panel.php");
?>
 <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script> -->
<script type="text/javascript">

  $(document).ready(function()
  {
   // cargar_modulos();
   autocmpletar();
   autocmpletar_usuario();
  });

  function autocmpletar(){
      $('#ddl_entidad').select2({
        placeholder: 'Seleccione una Entidad',
        ajax: {
          url: '../controlador/niveles_seguriC.php?entidades=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }

  function autocmpletar_usuario(){
       let entidad = $('#ddl_entidad').val();	
      $('#ddl_usuarios').select2({
        placeholder: 'Seleccione una Usuario',
        ajax: {
          url: '../controlador/niveles_seguriC.php?usuarios=true&entidad='+entidad,
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            return {
              results: data
            };
          },
          cache: true
        }
      });
  }

  function cargar_modulos(empresas)
    {
    	var parametros ={
    		'empresa':empresas,
    		'usu':$('#ddl_usuarios').val(),
    		'entidad':$('#ddl_entidad').val(),
    	}
    	$.ajax({
    		data:  {parametros:parametros},
    		url:   '../controlador/niveles_seguriC.php?modulos=true',
    		type:  'post',
    		dataType: 'json',
    		beforeSend: function () { 
    		  $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
    		},
    		success:  function (response) { 
    			if(response)
    			 {
    			 	// console.log(response);
    			 	$('#modu').html(response);
    			 	cargar_modulos_otros(empresas);
    					// $('#myModal_espera').modal('hide'); 
    			 }
    		}
    	});
  }

  function cargar_modulos_otros(emp)
    {
    	var parametros ={
    		'entidad':$('#ddl_entidad').val(),
    		'empresa':emp,
    		'usuario':$('#ddl_usuarios').val(),
    	}
    	$.ajax({
    		 data:  {parametros:parametros},
    		url:   '../controlador/niveles_seguriC.php?mod_activos=true',
    		type:  'post',
    		dataType: 'json',
    		// beforeSend: function () { 
    		//   $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
    		// },
    		success:  function (response) { 
    			if(response)
    			 {
    			 	$.each(response, function(i, item){
    			 		
    			 		$('#modulos_'+item.item+'_'+item.Modulo).prop('checked',true);
    			 		// console.log(item.Modulo);
    			 		// console.log(i);
    			 	}); 

    			 	
    			 }
    		}
    	});
  }
  function buscar_permisos()
  {
  	var empres = $('#txt_empresas').val();
  	if($('#ddl_usuarios').val()!='')
  	{
  		usuario();
  	}
  	if(empres != '')
  	{
  		empre = empres.slice(0,-1).split(',');
  	   $.each(empre, function(i, item){
        cargar_modulos_otros(item);
        // console.log(item.Modulo);
        // console.log(i);
       }); 
    }
     
    
  }

  function usuario()
  {
    	var parametros ={
    		'entidad':$('#ddl_entidad').val(),
    		'usuario':$('#ddl_usuarios').val(),
    	}
    	$.ajax({
    		 data:  {parametros:parametros},
    		url:   '../controlador/niveles_seguriC.php?usuario_data=true',
    		type:  'post',
    		dataType: 'json',
    		// beforeSend: function () { 
    		//   $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
    		// },
    		success:  function (response) { 
    			if(response)
    			 {
    			 	if(response.n1==1)
    			 	{
    			 		$('#rbl_n1').prop('checked',true);
    			 	}else { $('#rbl_n1').prop('checked',false); }
    			 	if(response.n2==1)
    			 	{
    			 		$('#rbl_n2').prop('checked',true);
    			 	}else { $('#rbl_n2').prop('checked',false); }
    			 	if(response.n3==1)
    			 	{
    			 		$('#rbl_n3').prop('checked',true);
    			 	}else { $('#rbl_n3').prop('checked',false); }
    			 	if(response.n4==1)
    			 	{
    			 		$('#rbl_n4').prop('checked',true);
    			 	}else { $('#rbl_n4').prop('checked',false); }
    			 	if(response.n5==1)
    			 	{
    			 		$('#rbl_n5').prop('checked',true);
    			 	}else { $('#rbl_n5').prop('checked',false); }
    			 	if(response.n6==1)
    			 	{
    			 		$('#rbl_n6').prop('checked',true);
    			 	}else { $('#rbl_n6').prop('checked',false); }
    			 	if(response.n7==1)
    			 	{
    			 		$('#rbl_n7').prop('checked',true);
    			 	}else { $('#rbl_n7').prop('checked',false); }
    			 	if(response.Supervisor==1)
    			 	{
    			 		$('#rbl_super').prop('checked',true);
    			 	}else { $('#rbl_super').prop('checked',false); }
    			 	$('#txt_usuario').val(response.Usuario);
    			 	$('#txt_pass').val(response.Clave)
    			 	
    			 }
    		}
    	});

  }

  function empresa_select(id)
  {

  	if($('#emp_'+id).prop('checked'))
  	{
  		var ant = $('#txt_empresas').val();
  		if(ant == '')
  		{
  			$('#txt_empresas').val(id+',');
  		}else
  		{
  			$('#txt_empresas').val(ant+id+',');
  		}
  		var ant = $('#txt_empresas').val();
  		empresa_select1(ant);
  		
  	}else
  	{
  		    var ant = $('#txt_empresas').val();
  			var res = ant.split(id+',').join('');
  			$('#txt_empresas').val(res);
  			$('#'+id).remove();
  			$("#tab_"+id).remove();
  		if($('#txt_empresas').val() == '')
  		{
  			// alert('vacio');
  			$('#modu').html('<div>No a seleccionado ninguna empresa</div>');
  		}
  		
  		
  	}

  }

  function empresa_select1(selected)
  {
  	// alert(selected);
        var num_em = selected.slice(0,-1).split(',');
        var nom  = $('#lbl_'+num_em[num_em.length-1]).text();
        if (selected !='') 
        {
        	if(num_em.length == 1)
        	{
        		cargar_modulos(selected);
        	}else
        	{
        		var tab = $('#tabs_titulo').html();
        		var num_tab = '<li id="tab_'+num_em[num_em.length-1]+'"><a data-toggle="tab" href="#'+num_em[num_em.length-1]+'">'+nom+'</a></li>';
        		$('#tabs_titulo').html(tab+num_tab);

        		var cont = $('#tab-content').html();
        		console.log(cont);

        		var res = cont.split(num_em[0]).join(num_em[num_em.length-1]);
        		var res = res.split('checked').join('');
        		$('#tab-content').html(cont+res); 
        		cargar_modulos_otros(num_em[num_em.length-1]);
        		// console.log(res);
        	}
        }else
        {
        	$('#modu').html('<div>No a seleccionado ninguna empresa</div>')
        } 
       
        		// buscar_permisos();
  }

  function cargar_empresas()
  {

  	$('#ddl_usuarios').val('');
  	$('#modu').html('<div>No a seleccionado ninguna empresa</div>');
  	$('#txt_empresas').val('');
  	autocmpletar_usuario();
  	let entidad = $('#ddl_entidad').val();
  	$.ajax({
    		 data:  {entidad:entidad},
    		url:   '../controlador/niveles_seguriC.php?empresas=true',
    		type:  'post',
    		dataType: 'json',
    		// beforeSend: function () { 
    		//   $('#myModal_espera').modal('show'); 
    		// },
    		success:  function (response) { 
    			if(response)
    				{    					
    				  $('#empresas').html(response);
    					// $('#myModal_espera').modal('hide'); 
    				}
    		}
    	});

  }
function guardar()
  {
  	var empre = $('#txt_empresas').val().slice(0,-1).split(',');
  	$.each(empre, function(i, item){
  		var selected = '';
  		 $('#form_'+item+' input[type=checkbox]').each(function(){
            if (this.checked) {
                selected += $(this).val()+',';
            }
        });
       enviar_para_guardar(selected+item);        
    }); 

  }

 function enviar_para_guardar(modulos)
 {
 	var parametros = {
 		'n1':$('#rbl_n1').prop('checked'),
 		'n2':$('#rbl_n2').prop('checked'),
 		'n3':$('#rbl_n3').prop('checked'),
 		'n4':$('#rbl_n4').prop('checked'),
 		'n5':$('#rbl_n5').prop('checked'),
 		'n6':$('#rbl_n6').prop('checked'),
 		'n7':$('#rbl_n7').prop('checked'),
 		'super':$('#rbl_super').prop('checked'),
 		'usuario':$('#txt_usuario').val(),
 		'pass':$('#txt_pass').val(),
 		'modulos':modulos,
 		'entidad':$('#ddl_entidad').val(),
 		'CI_usuario':$('#ddl_usuarios').val(),
 	}
 	$.ajax({
    		 data:  {parametros:parametros},
    		url:   '../controlador/niveles_seguriC.php?guardar_datos=true',
    		type:  'post',
    		dataType: 'json',
    		beforeSend: function () { 
    		 // $('#myModal_espera').modal('show'); 
    		},
    		success:  function (response) { 
    			if(response==1)
    				{    					
    					// $('#modulo').html(response);
    					Swal.fire({
    						//position: 'top-end',
    						type: 'success',
    						title: 'Guardado Correctamente!',
    						showConfirmButton: true
    						//timer: 2500
    						});
    					$('#myModal_espera').modal('hide'); 
    					buscar_permisos();
    				}
    		}
    	});

 }
 function bloquear()
 {
 	var parametros = 
 	{
 		'entidad':$('#ddl_entidad').val(),
 		'usuario':$('#ddl_usuarios').val(),
 	}
 	$.ajax({
    		data:  {parametros:parametros},
    		url:   '../controlador/niveles_seguriC.php?bloqueado=true',
    		type:  'post',
    		dataType: 'json',
    		beforeSend: function () { 
    		  $('#myModal_espera').modal('show'); 
    		},
    		success:  function (response) { 
    			if(response == 1)
    			 {
    			 	Swal.fire({
    						//position: 'top-end',
    						type: 'success',
    						title: 'Usuario bloqueado Correctamente!',
    						showConfirmButton: true
    						//timer: 2500
    						});
    					$('#myModal_espera').modal('hide'); 
    			 	
    			 }
    		}
    	});

 }
</script>

<div class="container">
  <div class="row">
    <div class="col-lg-7 col-sm-10 col-md-8 col-xs-12"> 
    <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
      <a  href="./contabilidad.php?mod=contabilidad#" title="Salir de modulo" class="btn btn-default">
        <img src="../../img/png/salire.png">
     </a>
    </div>    
    <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
       <button type="button" class="btn btn-default" title="Cambiar Numero">
        <img src="../../img/png/change_number.png">        
       </button>      
    </div>
    <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">                 
     <button type="button" class="btn btn-default"  title="Cambiar item y periodo">
       <img src="../../img/png/change_period.png">       
     </button>     
   </div>
   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
     <button class="btn btn-default" title="Eliminar periodo">
       <img src="../../img/png/delet_period.png" >
     </button>
   </div>
   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
     <button title="Cerrar Educativo"  class="btn btn-default" >
       <img src="../../img/png/close_house.png" >
     </button>
   </div>
   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
     <button title="Cerrar Facturacion"  class="btn btn-default">
       <img src="../../img/png/close_billing.png" >
     </button>
   </div>
   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
     <button title="Limpiar Base de datos"  class="btn btn-default">
       <img src="../../img/png/limpiar.png" >
     </button>
   </div>   
   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
     <button title="Copiar catalogos de periodo"  class="btn btn-default">
       <img src="../../img/png/copiar_1.png" >
     </button>
   </div>
   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
     <button title="Guardar"  class="btn btn-default" onclick="guardar()">
       <img src="../../img/png/grabar.png" >
     </button>
   </div>
   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
     <button title="Bloquear"  class="btn btn-default" onclick="bloquear()">
       <img src="../../img/png/lock.png" >
     </button>
   </div>
   
 </div>
</div>
 <div class="row">
	<div class="col-sm-4">
		<b>Entidad</b> <br>
		<select class="form-control" id="ddl_entidad" onchange="cargar_empresas();"></select>
	</div>
	<div class="col-sm-4">
		<b>Usuario</b> <br>
		<select class="form-control" id="ddl_usuarios" onchange="buscar_permisos();"></select>
	</div>
	<div class="col-sm-4">
		<div class="col-sm-6">
			<b>Usuario</b> <br>
			<input type="input" name="txt_usuario" class="form-control" id="txt_usuario">
		</div>
		<div class="col-sm-6">
			<b>Clave</b> <br>
			<input type="input" name="txt_pass" class="form-control" id="txt_pass">	
		</div>	
	</div>
	<div class="col-sm-12"><br>
		<div class="panel panel-default">
			 <div class="panel-heading" style="padding: 5px"><b>Niveles de seguridad</b></div>
			 <div class="panel-body">
			 	<div class="col-sm-1 col-xs-3">
			 		<label class="checkbox-inline"><input type="checkbox" name="rbl_n1" id="rbl_n1"><b>No. 1</b></label>
			 	</div>
			 	<div class="col-sm-1 col-xs-3">
			 		<label class="checkbox-inline"><input type="checkbox" name="rbl_n2" id="rbl_n2"><b>No. 2</b></label>
			 	</div>
			 	<div class="col-sm-1 col-xs-3">
			 	  	<label class="checkbox-inline"><input type="checkbox" name="rbl_n3" id="rbl_n3"><b>No. 3</b></label>
			 	</div>
			 	<div class="col-sm-1 col-xs-3">
			 	 	<label class="checkbox-inline"><input type="checkbox" name="rbl_n4" id="rbl_n4"><b>No. 4</b></label>
			 	</div>
			 	<div class="col-sm-1 col-xs-3">
			 		<label class="checkbox-inline"><input type="checkbox" name="rbl_n5" id="rbl_n5"><b>No. 5</b></label>
			 	</div>
			 	<div class="col-sm-1 col-xs-3">
			 		<label class="checkbox-inline"><input type="checkbox" name="rbl_n6" id="rbl_n6"><b>No. 6</b></label>
			 	</div>
			 	<div class="col-sm-1 col-xs-3">
			 		<label class="checkbox-inline"><input type="checkbox" name="rbl_n7" id="rbl_n7"><b>No. 7</b></label>
			 	</div>
			 	<div class="col-sm-1 col-xs-3">
			 		<label class="checkbox-inline"><input type="checkbox" name="rbl_super" id="rbl_super"><b>Supervisor</b></label>
			 	</div>
			 </div>
		</div>
	</div>      
 </div>
 <div class="row">
 	<div class="col-sm-6">
 		<div class="panel panel-default">
 			<input type="text" name="" id="txt_empresas" hidden="">
			 <div class="panel-heading" style="padding: 5px"><b>Lista de empresas</b></div>
			 <div class="panel-body">
			 	<form id="empresas" style="height: 300px;overflow-y: scroll;"><!-- 
 			       <label class="checkbox-inline"><input type="checkbox" name="modulos[]" value="01"><b>Activo</b></label><br>
 			      <label class="checkbox-inline"><input type="checkbox" name="modulos[]" value="03"><b>Activo</b></label> -->
 				</form>
			 </div>
	    </div> 		
 	</div>
 	<div class="col-sm-6">
 		<div class="panel panel-default">
			 <div class="panel-heading" style="padding: 5px"><b>Modulos</b></div>
			 <div class="panel-body" id="modu">
			 	<div>No a seleccionado ninguna empresa</div>
			 	<ul class="nav nav-tabs" id="tabs_titulo">
			 	</ul>
 		    </div>
 		</div>
 	</div>
 </div>  
</div><br>