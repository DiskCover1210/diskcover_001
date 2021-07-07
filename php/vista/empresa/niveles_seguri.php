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
        placeholder: 'Seleccione un Usuario',
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

  // function cargar_modulos(empresas)
  //   {
  //   	var parametros ={
  //   		'empresa':empresas,
  //   		'usu':$('#ddl_usuarios').val(),
  //   		'entidad':$('#ddl_entidad').val(),
  //   	}
  //     console.log(parametros);
  //   	$.ajax({
  //   		data:  {parametros:parametros},
  //   		url:   '../controlador/niveles_seguriC.php?modulos=true',
  //   		type:  'post',
  //   		dataType: 'json',
  //   		beforeSend: function () { 
  //   		  $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
  //   		},
  //   		success:  function (response) { 
  //   			if(response)
  //   			 {
  //   			 	console.log(response);
  //           // $('#modu').remove();
  //           // $('#tabs_titulo').remove();

  //   			 	$('#modu').html(response.body);
  //           $('#tabs_titulo').html(response.header);
  //   			 	cargar_modulos_otros(empresas);
  //   					// $('#myModal_espera').modal('hide'); 
  //   			 }
  //   		}
  //   	});
  // }

  // function cargar_modulos_otros(emp)
  //   {
  //   	var parametros ={
  //   		'entidad':$('#ddl_entidad').val(),
  //   		'empresa':emp,
  //   		'usuario':$('#ddl_usuarios').val(),
  //   	}
  //   	$.ajax({
  //   		 data:  {parametros:parametros},
  //   		url:   '../controlador/niveles_seguriC.php?mod_activos=true',
  //   		type:  'post',
  //   		dataType: 'json',
  //   		// beforeSend: function () { 
  //   		//   $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
  //   		// },
  //   		success:  function (response) { 
  //   			if(response)
  //   			 {
  //   			 	$.each(response, function(i, item){
    			 		
  //   			 		$('#modulos_'+item.item+'_'+item.Modulo).prop('checked',true);
  //             //  $('.nav-tabs a[href="#modulos_' + item.item+'_'+item.Modulo + '"]').tab('show'); 

  //             //$('#panel_mo').load(' #panel_mo');
  //   			 		// console.log(item.Modulo);
  //   			 		// console.log(i);
  //   			 	}); 

  //   			 }
  //   		}
  //   	});
  // }
  function buscar_permisos()
  {
    
      var id = $('#ddl_usuarios').val();
      var texto = $('select[name="ddl_usuarios"] option:selected').text();
      cargar_empresas();
      $('#ddl_usuarios').append($('<option>',{value: id, text:texto,selected: true }));
  	if($('#ddl_usuarios').val()!='')
  	{
  		usuario();
  	}    
  }

   function usuario_empresa()
  {
      var parametros ={
        'entidad':$('#ddl_entidad').val(),
        'usuario':$('#ddl_usuarios').val(),
      }
      $.ajax({
         data:  {parametros:parametros},
        url:   '../controlador/niveles_seguriC.php?usuario_empresa=true',
        type:  'post',
        dataType: 'json',
        // beforeSend: function () { 
        //   $('#modu').html('<img src="../../img/gif/loader4.1.gif" width="50%">');
        // },
        success:  function (response) { 
          if(response)
           {
            $.each(response,function(i,item){
              // console.log(item);
               var ind = item.split('_');
               $('#'+item).prop('checked',true);
               // console.log('#indice_'+ind[2]);
               $('#indice_'+ind[2]).css('display','initial');  
            })
             // console.log(response);
           }
        }
      });

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
          console.log(response);
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
    			 	$('#txt_pass').val(response.Clave);
            $('#txt_email').val(response.Email);
            $('#txt_ci_nic').val(response.CI_NIC);
    			 	console.log(response);
    			 }

      usuario_empresa();
    		}
    	});

  }

  // function empresa_select(id)
  // {

  // 	if($('#emp_'+id).prop('checked'))
  // 	{
  // 		var ant = $('#txt_empresas').val();
  // 		if(ant == '')
  // 		{
  // 			$('#txt_empresas').val(id+',');
  // 		}else
  // 		{
  // 			$('#txt_empresas').val(ant+id+',');
  // 		}
  // 		var ant = $('#txt_empresas').val();
  //     activo(id);
  // 		empresa_select1(ant);
  		
  // 	}else
  // 	{
  //     if($('#rbl_all').prop('checked')){
  //       $('#rbl_all').prop('checked',false);
  //       var lis = $('#txt_list_emp').val();
  //       lis = lis.split(',');
  //       ite = '';
  //       $.each(lis,function(i,item){
  //         if($('#emp_'+item).prop('checked'))
  //         {
  //           ite+=item+',';
  //         }
  //       })
  //       if($('#txt_coincidencia').val()!='')
  //       {
  //         empresa_select($('#txt_modal_conten').val());
  //       }else
  //       {
  //         empresa_select($('#txt_modal_conten').val());
  //       }

  //       var ant = $('#txt_empresas').val(ite);
  //     }

  //       var ant = $('#txt_empresas').val();
  // 			var res = ant.split(id+',').join('');
  // 			$('#txt_empresas').val(res);
  // 			$('#'+id).remove();
  // 			$("#tab_"+id).remove();
  // 		if($('#txt_empresas').val() == '')
  // 		{
  // 			// alert('vacio');
  // 			$('#modu').html('<div>No a seleccionado ninguna empresa</div>');
  // 		}
  		
  		
  // 	}

  // }

  // function empresa_select1(selected)
  // {
  // 	// alert(selected);
  //       var num_em = selected.slice(0,-1).split(',');
  //       var nom  = $('#lbl_'+num_em[num_em.length-1]).text();
  //       if (selected !='') 
  //       {
  //       	if(num_em.length == 1)
  //       	{
  //       		cargar_modulos(selected);
  //           // alert('sss');
  //       	}else
  //       	{
  //       		var tab = $('#tabs_titulo').html();
  //       		var num_tab = '<li id="tab_'+num_em[num_em.length-1]+'" onclick="activo(\''+num_em[num_em.length-1]+'\')"><a data-toggle="tab" href="#'+num_em[num_em.length-1]+'">'+nom+'</a></li>';
  //       		$('#tabs_titulo').html(tab+num_tab);

  //       		var cont = $('#tab-content').html();
  //       		console.log(cont);

  //       		var res = cont.split(num_em[0]).join(num_em[num_em.length-1]);
  //       		var res = res.split('checked').join('');
  //           var res = res.split('in active').join('');
  //       		$('#tab-content').html(cont+res); 
  //       		cargar_modulos_otros(num_em[num_em.length-1]);
  //       		// console.log(res);
  //       	}
  //       }else
  //       {
  //       	$('#modu').html('<div>No a seleccionado ninguna empresa</div>')
  //       } 
       
  //       		// buscar_permisos();
  // }

  function cargar_empresas()
  {

  	$('#ddl_usuarios').val('');   
    $('#tbl_modulos').html('');
  	$('#modu').html('<div>No a seleccionado ninguna empresa</div>');
  	$('#txt_empresas').val('');
  	autocmpletar_usuario();
  	let entidad = $('#ddl_entidad').val();
    // alert(entidad);
    $('#myModal_espera').modal('show');
  	$.ajax({
    		 data:  {entidad:entidad},
    		url:   '../controlador/niveles_seguriC.php?empresas=true',
    		type:  'post',
    		dataType: 'json',
    		// beforeSend: function () { 
    		//   $('#myModal_espera').modal('show'); 
    		// },
    		success:  function (response) { 
    			 	$('#myModal_espera').modal('hide');				
    				$('#tbl_modulos').html(response);  
    		}
    	});

  }
function guardar()
  {

    var selected = '';
    // if($('#txt_coincidencia').val()!='')
    // {
      var emp = $('#txt_empresas').val();
    //   item =$('#txt_modal_conten').val();
    //    $('#form_'+item+' input[type=checkbox]').each(function(){
    //         if (this.checked) {
    //             selected += $(this).val()+',';
    //         }
    //     // });       
    //     }); 
    // }else
    // {
    //   var emp = $('#txt_empresas').val();
    //   item = $('#txt_modal_conten').val();
    //    $('#form_'+item+' input[type=checkbox]').each(function(){
    //         if (this.checked) {
    //             selected += $(this).val()+',';
    //         }
    //     // });       
    //     }); 
    // }

       enviar_para_guardar(selected,emp); 
  }

 function enviar_para_guardar(modulos,empresas)
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
    'nombre': $('select[name="ddl_usuarios"] option:selected').text(),
 		'pass':$('#txt_pass').val(),
    'email':$('#txt_email').val(),
 		'modulos':modulos,
    'empresas':empresas,
 		'entidad':$('#ddl_entidad').val(),
 		'CI_usuario':$('#ddl_usuarios').val(),
 	}

  console.log(parametros);
  // return false;
 	$.ajax({
    		 data:  {parametros:parametros},
    		url:   '../controlador/niveles_seguriC.php?guardar_datos=true',
    		type:  'post',
    		dataType: 'json',
    		beforeSend: function () { 
    		 $('#myModal_espera').modal('show'); 
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
              $('#rbl_all').prop('checked',false); 
    					//buscar_permisos();
    				}else if(response == -2)
            {
              $('#myModal_espera').modal('hide'); 
              Swal.fire('No se pudo crear el usuario para SQLServer','Pongace en contacto con el administrador del sistema, su base no esta actualizada o no tiene las credenciales correctas','error');
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
 function guardarN()
 {
  var usu=$('#txt_usu').val();
  var cla=$('#txt_cla').val();
  var nom=$('#txt_nom').val();
  var ced=$('#txt_ced').val();
  var ent=$('#ddl_entidad').val();
  var parametros = 
  {
    'usu':usu,
    'cla':cla,
    'nom':nom,
    'ced':ced,
    'ent':ent,
  }
  if(ent != "")
  {
   if(usu=='' || cla == '' || nom== '' || ced == '' )
   {
    Swal.fire({
      type: 'info',
      title: 'Llene todo los campos!',
      showConfirmButton: true});
   }else
   {
      $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/niveles_seguriC.php?nuevo_usuario=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          // $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          if (response == 1)
           {

          $('#myModal_espera').modal('hide'); 
             Swal.fire({
                 type: 'success',
                 title: 'Usuario Creado!',
                 showConfirmButton: true});
          $('#myModal').modal('hide'); 
           }else if(response == -2)
           {

          $('#myModal_espera').modal('hide'); 
            Swal.fire({
              type: 'info',
              title: 'Usuario y Clave existente!',
              showConfirmButton: true});

           }
           else if(response == -3)
           {

          $('#myModal_espera').modal('hide'); 
            Swal.fire({
              type: 'info',
              title: 'Nuevo Usuario no registrar en base de datos de la entidad!',
              showConfirmButton: true});

           }else
           {

          $('#myModal_espera').modal('hide'); 
             Swal.fire({
              type: 'error',
              title: 'Surgio un problema intente mas tarde!',
              showConfirmButton: true});

           }
          
        }
      });


   }
 }else
 {
  Swal.fire({
              type: 'error',
              title: 'Selecione una entidad!',
              showConfirmButton: true});
 }

 }

 function buscar_empresa_ruc()
 {
  var ruc = $('#ruc_empresa').val();
      $.ajax({
        data:  {ruc:ruc},
        url:   '../controlador/niveles_seguriC.php?buscar_ruc=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
          $('#list_empre').html('<tr class="text-center"><td colspan="6"> No encontrado... </td></tr>');
          $('#txt_enti').val('');
        },
        success:  function (response) { 
          if(response == -1)
           {
            Swal.fire({
                //position: 'top-end',
                type: 'info',
                title: 'RUC no encontrado!',
                showConfirmButton: true
                //timer: 2500
                });
              $('#myModal_espera').modal('hide'); 
            
           }else
           {

            // $('#txt_enti').val(response.entidad[0]['Nombre_Entidad']);
            var empresa = '';
            console.log(response);
            $.each(response, function(i,item){
              if(i==0)
              {
             empresa+="<tr><td><input type='radio' name='radio_usar' value='"+item.ID_Empresa+"-"+item.Entidad+"-"+item.Item+"' checked></td><td>"+item.emp+"</td><td>"+item.Item+"</td><td>"+item.ruc+"</td><td>"+item.Estado+"</td><td><i><b><u>"+item.Entidad+"</u></b></i></td><td><i><b><u>"+item.Ruc_en+"</u></b></i></td></tr>";
              }else
              {
                 empresa+="<tr><td><input type='radio' name='radio_usar' value='"+item.ID_Empresa+"-"+item.Entidad+"-"+item.Item+"'></td><td>"+item.emp+"</td><td>"+item.Item+"</td><td>"+item.ruc+"</td><td>"+item.Estado+"</td><td><i><b><u>"+item.Entidad+"</u></b></i></td><td><i><b><u>"+item.Ruc_en+"</u></b></i></td></tr>";

              }
            });

           $('#list_empre').html(empresa);
             $('#myModal_espera').modal('hide'); 
           }
        }
      });

}

function marcar_all(item)
{
  
  var parametros = 
  {
    'item':item,
    'modulo':'',
    'entidad':$('#ddl_entidad').val(),
    'usuario':$('#ddl_usuarios').val(),
    'check':$('#rbl_'+item+'_T').prop('checked'),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/niveles_seguriC.php?acceso_todos=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          if(response == 1)
           {  
             usuario_empresa();
            $('#myModal_espera').modal('hide');             
           }
        }
      });

    if($('#rbl_'+item+'_T').prop('checked')==false)
    {
      var id = $('#ddl_usuarios').val();
      var texto = $('select[name="ddl_usuarios"] option:selected').text();
      cargar_empresas();
      $('#ddl_usuarios').append($('<option>',{value: id, text:texto,selected: true }));
      usuario_empresa();
    }
  }
 

function marcar_acceso(item,modulo)
{
  var parametros = 
  {
    'item':item,
    'modulo':modulo,
    'entidad':$('#ddl_entidad').val(),
    'usuario':$('#ddl_usuarios').val(),
    'check':$('#rbl_'+modulo+'_'+item).prop('checked'),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/niveles_seguriC.php?acceso_todos=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          // $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          if(response == 1)
           {
              usuario_empresa();
              // $('#myModal_espera').modal('hide');      
            
           }
        }
      }); 
}

function activo(id)
{
  var emp = $('#txt_empresas').val();
  emp = emp.slice(0,-1).split(',');
  if(emp.length ==1)
  {
   $('#txt_modal_conten').val(id);
  }
}

function enviar_email()
{
  var email  = $('#txt_email').val();
  if(email == '.' || email =='')
  {
      Swal.fire('Campo de email vacio','','info');
    return false;
  }
  var parametros = 
  {
    'nick':$('#txt_usuario').val(),
    'clave':$('#txt_pass').val(),
    'email':email,
    'entidad':$('select[name="ddl_entidad"] option:selected').text(),
    'ruc':$('#ddl_entidad').val(),
    'usuario':$('select[name="ddl_usuarios"] option:selected').text(), 
    'CI_usuario':$('#ddl_usuarios').val(),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/niveles_seguriC.php?enviar_email=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          console.log(response);
          if(response == 1)
           {
             Swal.fire('Email enviado,Se guardara el correo','','success');
             guardar();
            
           }else
           {
             Swal.fire('No se pudo enviar','asegurese que su correo sea el correcto','error');
           }
        }
      }); 
}

function enviar_email_masivo()
{
  var email  = $('#txt_email').val();
  var parametros = 
  {
    'nick':$('#txt_usuario').val(),
    'clave':$('#txt_pass').val(),
    'email':email,
    'entidad':$('select[name="ddl_entidad"] option:selected').text(),
    'ruc':$('#ddl_entidad').val(),
    'usuario':$('select[name="ddl_usuarios"] option:selected').text(), 
    'CI_usuario':$('#ddl_usuarios').val(),
  }
    $.ajax({
        data:  {parametros:parametros},
        url:   '../controlador/niveles_seguriC.php?enviar_email_masivo=true',
        type:  'post',
        dataType: 'json',
        beforeSend: function () { 
          $('#myModal_espera').modal('show'); 
        },
        success:  function (response) { 
          console.log(response);
          if(response==1)
          {
            Swal.fire('Email enviado,Se guardara el correo','','success');
          }else if(response==2)
          {
            Swal.fire('Puede ser que algunos usuarios no hayan recibido sus credenciales','info');
          }
          $('#myModal_espera').modal('hide');
        }
    }); 
}
</script>

<style>
  table ,tr, td{ 
    /*border:1px solid */
  } 
  tbody { 
    display:block;
    height:300px; 
    overflow:auto;
  } 
  thead, tbody, tr {
    display:table;
    width:100%;table-layout:fixed;
  } 
  thead { 
    width: calc( 100% - 1em )
  } 
  table { 
    width:400px;
  }
</style>

<div class="container">
  <div class="row">
    <div class="col-lg-7 col-sm-10 col-md-8 col-xs-12"> 
    <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
      <a  href="./empresa.php?mod=empresa#" title="Salir de modulo" class="btn btn-default">
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
   <div class="col-xs-2 col-md-1 col-sm-1 col-lg-1">
     <button title="Enviar credenciales masivos"  class="btn btn-default" onclick="" data-toggle="dropdown" aria-expanded="false">
       <img src="../../img/png/email.png" >
       <span class="fa fa-caret-down"></span>
     </button>
     <ul class="dropdown-menu">
       <li><a href="#" onclick="enviar_email_masivo();">Enviar credenciales masivos</a></li>
       <!-- <li><a href="#" data-toggle="modal" data-target="#myModal_ruc" >Redactar email</a></li> -->
        <!-- <li><a href="#">Something else here</a></li> -->
       <!-- <li class="divider"></li> -->
       <!-- <li><a href="#">Recupera</a></li> -->
     </ul>
   </div>
 </div>
</div>
 <div class="row">
	<div class="col-sm-4">
   	<b>Entidad</b> <br>
      <div class="input-group" style="display: flex; width: 90%;">
         <select class="form-control" id="ddl_entidad" name="ddl_entidad" onchange="cargar_empresas();" style="display: none;"><option value="">Seleccione entidad</option></select>
       <div class="input-group-btn">
          <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#myModal_ruc"><span class="fa fa-search"></span> RUC</button>
       </div>

      </div>
	</div>
	<div class="col-sm-4">    
   <b>Usuario</b> <br>
    <div class="input-group">
        <select class="form-control input" id="ddl_usuarios"  name="ddl_usuarios" onchange="buscar_permisos();"  style="width:50%"></select>
        <div class="input-group-btn">
          <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal"><span class="fa fa-plus"></span> Nuevo</button>
        </div>
      </div>
	</div>
	<div class="col-sm-4">
		<div class="col-sm-6">
			<b>Usuario</b> <br>
			<input type="input" name="txt_usuario" class="form-control input-sm" id="txt_usuario">
		</div>
		<div class="col-sm-6">
			<b>Clave</b> <br>
			<input type="input" name="txt_pass" class="form-control input-sm" id="txt_pass">	
		</div>
    <div class="col-sm-12">
      <b>Email</b><br>
      <div class="input-group">
        <input type="input" name="txt_email" class="form-control input-sm" id="txt_email"> 
        <div class="input-group-btn">
          <button type="button" class="btn btn-primary btn-xs" onclick="enviar_email()"><span class="fa fa-send-o"></span> Enviar correo</button>
        </div>
      </div> 
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
  <div class="col-sm-12">
    <div class="panel panel-default">      
       <div class="panel-heading" style="padding: 5px"><b>Lista de empresas</b></div>
       <div class="panel-body">
        <div class="row table-responsive" id="tbl_modulos" style="overflow-x: auto;">
          
        </div>      
       </div>
      </div> 
      <ul class="nav nav-tabs" id="tabs_titulo">
    </ul>     
  </div>   
 </div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Nuevo usuario</h4>
      </div>
      <div class="modal-body">
        <b>Usuario</b><br>
        <input type="text" name="" id="txt_usu" class="form-control">
        <b>Clave</b><br>
        <input type="text" name="" id="txt_cla" class="form-control">
        <b>Nombre completo</b><br>
        <input type="text" name="" id="txt_nom" class="form-control">
        <b>Cedula</b><br>
        <input type="text" name="" id="txt_ced" class="form-control">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" onclick="guardarN()">Guardar</button>
      </div>
    </div>

  </div>
</div>

<div id="myModal_ruc" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Buscar empresa por ruc</h4>
      </div>
      <div class="modal-body">
        <div class="input-group">
          <input type="text" name="" id="ruc_empresa" class="form-control" placeholder="Ingrese RUC de empresa">
          <div class="input-group-btn">
            <button type="button" class="btn btn-primary" onclick="buscar_empresa_ruc()">Buscar</button>
          </div>          
        </div>
        <table class="table table-hover">
          <thead>
            <th></th>
            <th>Empresa</th>
            <th>Item</th>
            <th>Ruc asociado</th>
            <th>Estado</th>
            <th>Entidad</th>
            <th>Ruc Entidad</th>
          </thead>
          <tbody id="list_empre">
            <tr class="text-center">
              <td colspan="6"> No encontrado... </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" onclick="usar_busqueda()">Usar</button>
      </div>
    </div>

  </div>
</div>