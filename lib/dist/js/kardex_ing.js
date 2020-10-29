$(document).ready(function()
  {
    ddl_DCRetIBienes();
    ddl_DCRetISer();
    ddl_DCSustento();
    ddl_DCDctoModif();
    ddl_DCPorcenIva();
    ddl_DCPorcenIce();
    ddl_DCTipoPago();
    ddl_DCRetFuente();
    ddl_DCConceptoRet();
    ddl_DCPais();
    
  })

 function ddl_DCRetIBienes() {
 	var opcion = '<option>Seleccione tipo de retencion</option>';
	$.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCRetIBienes=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Codigo+'">'+item.Cuentas+'</option>';
        	})
        	$('#DCRetIBienes').html(opcion);
                    // console.log(response);
      }
    }); 
}

function ddl_DCRetISer() {
 	var opcion = '<option>Seleccione tipo de retencion</option>';
	$.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCRetISer=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	//console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Codigo+'">'+item.Cuentas+'</option>';
        	})
        	$('#DCRetISer').html(opcion);
                    // console.log(response);
      }
    }); 
}
function ddl_DCSustento() {
 	var opcion = '<option>Seleccione sustento</option>';
 	var parametros = 
 	{
 		'fecha':$('#MBFechaI').val(),
 	}
	$.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCSustento=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	//console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Credito_Tributario+'">'+item.Sustento+'</option>';
        	})
        	$('#DCSustento').html(opcion);
                    // console.log(response);
      }
    }); 
}


function ddl_DCDctoModif() {
 	var opcion = '<option>Seleccione tipo de Comprobante</option>';
	$.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCDctoModif=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	//console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Codigo+'">'+item.Descripcion+'</option>';
        	})
        	$('#DCDctoModif').html(opcion);
                    // console.log(response);
      }
    }); 
}

function ddl_DCPorcenIva() {
 	var opcion = '<option>Iva</option>';
 	var parametros = 
 	{
 		'fecha':$('#MBFechaI').val(),
 	}
	$.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCPorcenIva=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        //	console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Porc+'">'+item.Porc+'</option>';
        	})
        	$('#DCPorcenIva').html(opcion);
                    // console.log(response);
      }
    }); 
}

function ddl_DCPorcenIce() {
 	var opcion = '<option>ICE</option>';
 	var parametros = 
 	{
 		'fecha':$('#MBFechaI').val(),
 	}
	$.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCPorcenIce=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	//console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Porc+'">'+item.Porc+'</option>';
        	})
        	$('#DCPorcenIce').html(opcion);
                    // console.log(response);
      }
    }); 
}

function ddl_DCTipoPago() {
 	var opcion = '<option>Seleccione tipo de pago</option>';
	$.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCTipoPago=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	//console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Codigo+'">'+item.CTipoPago+'</option>';
        	})
        	$('#DCTipoPago').html(opcion);
                    // console.log(response);
      }
    }); 
}


function ddl_DCRetFuente() {
 	var opcion = '<option>Seleccione tipo de Retencion</option>';
	$.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCRetFuente=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	//console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Codigo+'">'+item.Cuentas+'</option>';
        	})
        	$('#DCRetFuente').html(opcion);
                    // console.log(response);
      }
    }); 
}


function ddl_DCConceptoRet() {
 	var opcion = '<option>Seleccione Codigo de retencion</option>';
 	var parametros = 
 	{
 		'fecha':$('#MBFechaI').val(),
 	}
	$.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCConceptoRet=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        //	console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Porc+'">'+item.Detalle_Conceptos+'</option>';
        	})
        	$('#DCConceptoRet').html(opcion);
                    // console.log(response);
      }
    }); 
}


function ddl_DCPais() {
 	var opcion = '<option>Seleccione pais</option>';
	$.ajax({
      //data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCPais=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        //	console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.CPais+'">'+item.Descripcion_Rubro+'</option>';
        	})
        	$('#DCPais').html(opcion);
                    // console.log(response);
      }
    }); 
}

function ddl_DCTipoComprobante() {
 	var opcion = '<option>Seleccione tipo de comprobante</option>';
 	var parametros = 
 	{
 		'DCSustento':$('#DCSustento').val(),
 		'fecha':$('#MBFechaI').val(),
 		'TipoBenef':$('#TipoBenef').val(),
 	}
	$.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCTipoComprobante=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
        	//console.log(response);
        	$.each(response,function(i,item){
        		opcion+='<option value="'+item.Tipo+'">'+item.Descripcion+'</option>';
        	})
        	$('#DCTipoComprobante').html(opcion);
                    // console.log(response);
      }
    }); 
}

function DCBenef_LostFocus()
{
	 var parametros =
    {
        'DCBenef':$('select[name="DCBenef"] option:selected').text(),
        'cta' :$('#SubCta').val(),
        'contra' :$('#DCCtaObra').val(),
    }
    $.ajax({
      data:  {parametros:parametros},
      url:   '../controlador/inventario/kardex_ingC.php?DCBenef_Data=true',
      type:  'post',
      dataType: 'json',
        success:  function (response) {
            if (response.length !=0) 
            {
           // 	console.log(response)
             $("#grupo_no").val(response.grupo_no); 
             $("#Tipodoc").val(response.tipodoc);
             $("#TipoBenef").val(response.TipoBenef);
             $("#cod_benef").val(response.cod_benef); 
             $("#InvImp").val(response.InvImp);  
             $("#TipoBenef").val(response.TipoBenef); 
             $("#ci").val(response.CICLIENTE); 

             $('#TextConcepto').val(response.text);
             $('#LblNumIdent').val(response.CICLIENTE);
             $('#LblTD').val(response.TipoBenef);
             $('#DCProveedor').html('<option value="'+response.id+'">'+response.text+'</option>');

             $("#si_no").val(response.si_no); 
             // ddl_DCTipoComprobante();
            }
         
      }
    });  
}

function mostrar_panel()
{
	if($('#DCTipoComprobante').val()== 4 || $('#DCTipoComprobante').val()== 5)
	{
    	$('#panel_notas').show();
	}else{
		$('#panel_notas').hide();
	}
}

function mostrar_panel_ext()
{
	if($('#CFormaPago').val()== 2)
	{
    	$('#panel_exterior').show();
	}else{
		$('#panel_exterior').hide();
	}
}

function mostra_select()
{

	if($('#ChRetF').prop('checked'))
	{
    	$('#DCRetFuente').show();
	}else{
		$('#DCRetFuente').hide();
	}
}



// function ddl_DCRetIBienes1() {
// 	 $('#ddl_familia').select2({
//         placeholder: 'Seleccione una Familia',
//         ajax: {
//            url:   '../controlador/inventario/kardex_ingC.php?familias=true',
//           dataType: 'json',
//           delay: 250,
//           processResults: function (data) {
//             console.log(data);
//             return {
//               results: data
//             };
//           },
//           cache: true
//         }
//       });
// }


//  function leercuenta()
//   {    
//     $.ajax({
//       data:  {parametros:parametros},
//       url:   '../controlador/inventario/kardex_ingC.php?leercuenta=true',
//       type:  'post',
//       dataType: 'json',
//         success:  function (response) {
                    
//       }
//     });  

//   }