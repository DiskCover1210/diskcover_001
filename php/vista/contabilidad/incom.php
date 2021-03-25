<?php
    if(!isset($_SESSION)) 
	 		session_start();
		$_SESSION['INGRESO']['ti']='Ingresar Comprobantes (Crtl+f5)';
		$T_No=1;
		$SC_No=0;
		//echo $_SESSION['INGRESO']['Id']; 
	//datos para consultar
	//CI_NIC
	//echo $_SESSION['INGRESO']['Opc'].' '.$_SESSION['INGRESO']['Sucursal'].' '.$_SESSION['INGRESO']['item'].' '.$_SESSION['INGRESO']['periodo'].' ';

?>
<style>
	.typeahead { border: 2px solid #FFF;border-radius: 4px;padding: 5px 12px;max-width: 300px;min-width: 290px;background: #f5f5f5;color: #000;}
	.tt-menu { width:300px; }
	ul.typeahead{margin:0px;padding:2px 0px;}
	ul.typeahead.dropdown-menu li a {padding: 2px !important;	border-bottom:#CCC 1px solid;color:#000;}
	ul.typeahead.dropdown-menu li:last-child a { border-bottom:0px !important; }
	.bgcolor {max-width: 550px;min-width: 290px;max-height:340px;background:url("world-contries.jpg") no-repeat center center;padding: 100px 10px 130px;border-radius:4px;text-align:center;margin:10px;}
	.demo-label {font-size:1.5em;color: #686868;font-weight: 500;color:#FFF;}
	.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover {
		text-decoration: none;
		/*background-color: #1f3f41;*/
		background-color: #0086c7;
		color: #FFFFFF;
		outline: 0;
	}
	.btn_f
	{
		background-color: #CFE9EF;
		color: #444;
		border-color: #ddd;
	}
</style>
<style>
	* {
		  box-sizing: border-box;
		}

		.form1 {
		  padding: 1em;
		  background: #f9f9f9;
		  border: 1px solid #c1c1c1;
		  margin-top: 2rem;
		  max-width: 600px;
		  margin-left: auto;
		  margin-right: auto;
		  padding: 1em;
		}
		.form1 input {
		  margin-bottom: 1rem;
		  background: #fff;
		  border: 1px solid #9c9c9c;
		}
		.form1 button {
		  background: lightgrey;
		  padding: 0.7em;
		  border: 0;
		}
		.form1 button:hover {
		  background: gold;
		}

		.label1 {
		  text-align: Left;
		  display: block;
		  padding: 0.5em 1.5em 0.5em 0;
		  background-color: #CFE9EF;
		  font-size: 10px;
		}

		.input1 {
		  width: 100%;
		  padding: 0.7em;
		  margin-bottom: 0.5rem;
		}
		.input1:focus {
		  outline: 3px solid blue;
		}

		@media (min-width: 400px) {
		  form {
			overflow: hidden;
		  }

		  .label1 {
			float: left;
			width: 100%;
		  }

		  .input1 {
			float: left;
			width: calc(50%);
		  }

		  .button1 {
			float: right;
			width: calc(100% - 200px);
		  }
		}
		.marco 
		{
			border: 1px solid #ddd;;
			padding: 0.5em 1em 1em 1em;
			height: 113px;
			position:absolute;
			top: 19%;
			left: 1%;
			font-size: 10px;
			width: 130px;
			/*overflow: scroll;*/
		}
		.input-group .input-group-addon 
		{
			background-color: #CFE9EF;
			color: #444;
			border-color: #ddd;
			border-bottom-left-radius: 5px;
			border-top-left-radius:  5px;
		}
</style>
<style>
	.xs {
	  border: 1px dotted #CFE9EF;
	  border-radius: 0;

	  -webkit-appearance: none;
	}
	.xs1 {
	  border: 1px dotted #999;
	  border-radius: 0;

	  -webkit-appearance: none;
	}
</style>


<script type="text/javascript">
	
	$(document).ready(function () {
		$('#tit_sel').html('<i class="fa  fa-trash"></i>');
		numero_comprobante();
    cargar_totales_aseintos();
		autocoplet_bene();
		cargar_cuenta_efectivo();
		cargar_cuenta_banco();
		cargar_cuenta();
		cargar_tablas_contabilidad();
		cargar_tablas_tab4();
    cargar_tablas_retenciones();
    cargar_tablas_sc();
        	});

	 function autocoplet_bene(){
      $('#beneficiario1').select2({
        placeholder: 'Seleccione una beneficiario',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?beneficiario=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }

    function benefeciario_selec()
    {
    	var valor = $('#beneficiario1').val();
    	parte = valor.split('-');
    	$('#ruc').val(parte[0]);
    	$('#email').val(parte[1]);
    }

    function mostrar_efectivo()
    {
    	if($('#efec').prop('checked'))
    	{
    		$('#rbl_efec').css("background-color",'#286090');
    		$('#rbl_efec').css("color",'#FFFFFF');
    		$('#rbl_efec').css("border-radius",'5px');
    		$('#rbl_efec').css("padding",'3px');
    		$('#ineg1').css('display','block');
    	}else
    	{
    		$('#rbl_efec').css("background-color",'');
    		$('#rbl_efec').css("color",'black');
    		$('#rbl_efec').css("border-radius",'');
    		$('#rbl_efec').css("padding",'');
    		$('#ineg1').css('display','none');
    	}
    }

    function mostrar_banco()
    {
    	if($('#ban').prop('checked'))
    	{
    		$('#rbl_banco').css("background-color",'#286090');
    		$('#rbl_banco').css("color",'#FFFFFF');
    		$('#rbl_banco').css("border-radius",'5px');
    		$('#rbl_banco').css("padding",'3px');
    		$('#ineg2').css('display','block');
    		$('#ineg3').css('display','block');
    	}else
    	{
    		$('#rbl_banco').css("background-color",'');
    		$('#rbl_banco').css("color",'black');
    		$('#rbl_banco').css("border-radius",'');
    		$('#rbl_banco').css("padding",'');
    		$('#ineg2').css('display','none');
    		$('#ineg3').css('display','none');
    	}
    }

    function cargar_cuenta_efectivo()
    {
      $('#conceptoe').select2({
        placeholder: 'Seleccione cuenta efectivo',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?cuentas_efectivo=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }				

     function cargar_cuenta_banco()
    {
      $('#conceptob').select2({
        placeholder: 'Seleccione cuenta banco',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?cuentas_banco=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }

     function cargar_cuenta()
    {
      $('#cuentar').select2({
        placeholder: 'Seleccione cuenta',
        ajax: {
          url:   '../controlador/contabilidad/incomC.php?cuentasTodos=true',
          dataType: 'json',
          delay: 250,
          processResults: function (data) {
            console.log(data);
            return {
              results: data
            };
          },
          cache: true
        }
      });
    }


    function reset_1(concepto,tipo)
    {
    	var sel = $('#tipoc').val();
    	$('#'+sel).removeClass("active");
    	if (tipo=='CD') 
    	{
    		$('#ineg').css('display','none');
    		$('#tipoc').val(tipo);
    		numero_comprobante();

    	}else if(tipo=='CI')
    	{ 
    		$('#tipoc').val(tipo);
    		$('#CI').addClass("active");
    		$('#tipoc').val(tipo);
    		$('#ineg').css('display','block');
    		$('#no_cheque').css('display','none');
    		$('#ingreso_val_banco').css('display','block');
    		$('#deposito_no').css('display','block');
    		numero_comprobante();

    	}else if(tipo=='CE')
    	{

			$('#myModal_espera').modal('show');
    		$('#tipoc').val(tipo);
    		$('#CE').addClass("active");
    		$('#tipoc').val(tipo);
    		$('#ineg').css('display','block');
    		$('#no_cheque').css('display','block');
    		$('#ingreso_val_banco').css('display','none');
    		$('#deposito_no').css('display','none');
    		numero_comprobante();
    		eliminar_todo_asisntoB();

    	}else if(tipo=='ND')
    	{
    		$('#tipoc').val(tipo);
    		$('#ND').addClass("active");
    		$('#tipoc').val(tipo);
    		$('#ineg').css('display','none');
    		numero_comprobante();

    	}else if(tipo=='NC')
    	{
    		$('#tipoc').val(tipo);
    		$('#NC').addClass("active");
    		$('#tipoc').val(tipo);
    		$('#ineg').css('display','none');
    		numero_comprobante();

    	}
    }

		        $('#tit_sel').html('<i class="fa  fa-trash"></i>');

    function eliminar_todo_asisntoB()
    {
    	 $.ajax({
          //data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?EliAsientoBTodos=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            if(response == 1)
            {
		        $("#div_tabla").load(" #div_tabla");
		        $('#tit_sel').html('<i class="fa  fa-trash"></i>');
		        $('#myModal_espera').modal('hide');
            } 
          }
        });

    }

  function numero_comprobante()
    {
      var tip = $('#tipoc').val();
      var fecha = $('#fecha1').val();
      if(tip=='CD'){ tip = 'Diario';}
      else if(tip=='CI'){tip = 'Ingresos'}
      else if(tip=='CE'){tip = 'Egresos';}
      else if(tip=='ND'){tip = 'NotaDebito';}
      else if(tip=='NC'){tip= 'NotaCredito';}
      var parametros = 
       {      
         'tip': tip,
         'fecha': fecha,                    
       };
    $.ajax({
      data:  {parametros:parametros},
       url:   '../controlador/contabilidad/incomC.php?num_comprobante=true',
      type:  'post',
      // beforeSend: function () {
      //    $("#num_com").html("");
      // },
      success:  function (response) {
          $("#num_com").html("");
          $("#num_com").html('Comprobante de '+tip+' No. <?php echo date('Y');?>-'+response);
          // var valor = $("#subcuenta1").html(); 
      }
    });
    }

    function agregar_depo()
    {
    	var banco = $('#conceptob').val();
    	let nom_banco = $('#conceptob option:selected').text();
    	nombre = nom_banco.replace(banco,'');
    	console.log(nombre);
    	var parametros = 
    	{
    		'banco':banco,
    		'bancoC':nombre,
    		'cheque':$('#no_cheq').val(),
    		'valor':$('#vab').val(),
    		'fecha':$('#fecha1').val(),
    		'T_no':$('#tipoc').val(),
    	}
    	if(banco =='')
    	{
    		Swal.fire({
				type: 'info',
				title: 'Oops...',
				text: 'Seleccione cuenta de banco!'
		        });
    		return false;
    	}
    	 $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?asientoB=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            if(response == 1)
            {
            	Swal.fire({
				type: 'success',
				title: 'Agregado',
				text: 'ingresado!'
		        });
		        $("#div_tabla").load(" #div_tabla");
		        $('#tit_sel').html('<i class="fa  fa-trash"></i>');
            }  else
            {
            	Swal.fire({
				type: 'error',
				title: 'Oops...',
				text: 'debe agregar beneficiario!'
		        });
            }       
          }
        });
    }

    function validarc(id,ta)
    {   	
    	if(document.getElementById(id).checked)
		{
			var val = document.getElementById(id).value;
			var partes = val.split('--');
			var parametros = 
			{
				'cta':partes[0],
				'cheque':partes[1],
			}
			Swal.fire({
                 title: 'Esta seguro?',
                 text: "Esta usted seguro de que quiere borrar este registro!",
                 type: 'warning',
                 showCancelButton: true,
                 confirmButtonColor: '#3085d6',
                 cancelButtonColor: '#d33',
                 confirmButtonText: 'Si!'
               }).then((result) => {
                 if (result.value==true) {
                 	eliminar(parametros);
                 }else
                 {
                 	document.getElementById(id).checked = false;
                 }
               })
    	}
    }

    function eliminar(parametros)
    {
    	 $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?EliAsientoB=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            if(response == 1)
            {
            	Swal.fire({
				type: 'success',
				title: 'Eliminado',
				text: 'Registro eliminado!'
		        });
		        $("#div_tabla").load(" #div_tabla");
		        $('#tit_sel').html('<i class="fa  fa-trash"></i>');
            }  else
            {
            	Swal.fire({
				type: 'error',
				title: 'Oops...',
				text: 'No se pudo ejecutar la solicitud!'
		        });
            }       
          }
        });
    }

    function abrir_modal_cuenta()
    {
    	var codigo = $('#cuentar').val();
    	tipo_cuenta(codigo);
    	$('#codigo').val($('#cuentar').val());
    	$('#modal_cuenta').modal('show');
    }

    function tipo_cuenta(codigo)
    {
    	$.ajax({
          data:  {codigo:codigo},
          url:   '../controlador/contabilidad/incomC.php?TipoCuenta=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
            $("#txt_cuenta").val(response.cuenta);
            $("#txt_codigo").val(response.codigo);
            $("#txt_tipocta").val(response.tipocta);
            $("#txt_subcta").val(response.subcta);
            $("#txt_tipopago").val(response.tipopago);
            $("#txt_moneda_cta").val(response.moneda);
            // $("#txt_moneda").val(response.moneda);
            if(response.subcta =='BA')
            {
            	$('#panel_banco').css('display','block');
            }else{
            	$('#panel_banco').css('display','none');
            }
			}
        });

    }
    function restingir(campo)
    {
    	var valor = $('#'+campo).val();
    	var cant = valor.length;
    	if(cant>1)
    	{
    		var num = valor.substr(0,1);
    		if(num<3 && num>0)
    		{
    			 $('#'+campo).val(num);
    		}else
    		{
    			$('#'+campo).val('');
    		}
    	}else
    	{
    		if(valor<3 && valor>0)
    		{
    			$('#'+campo).val(valor);
    		}else
    		{
    			$('#'+campo).val('');
    		}
    	}
    }
    function cambia_foco()
    {
    	$('#modal_cuenta').modal('hide');
    	$('#va').focus();
    }

    function cargar_tablas_contabilidad()
    {
    	
    	$.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_contabilidad=true',
          type:  'post',
          // dataType: 'json',
            success:  function (response) {    
            $('#contabilidad').html(response);      
          }
        });

    }
    function cargar_tablas_sc()
    {
    	
    	$.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_sc=true',
          type:  'post',
          // dataType: 'json',
            success:  function (response) {    
            $('#subcuentas').html(response);      
          }
        });

    }

    function cargar_tablas_retenciones()
    {
    	
    	$.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_retencion=true',
          type:  'post',
          // dataType: 'json',
            success:  function (response) {    
            $('#retenciones').html(response);      
          }
        });

    }

    function cargar_tablas_tab4()
    {
    	
    	$.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?tabs_tab4=true',
          type:  'post',
          // dataType: 'json',
            success:  function (response) {    
            $('#ac_av_ai_ae').html(response);      
          }
        });

    }

    function cargar_totales_aseintos()
    {
      
      $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?totales_asientos=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 

            console.log(response);     
            $('#txt_diferencia').val(response.diferencia);  
            $('#txt_debe').val(response.debe);  
            $('#txt_haber').val(response.haber);  
          }
        });

    }

    function ingresar_asiento()
    {
    var partes= $('#cuentar option:selected').text();
    var partes = partes.split('--');
    var dconcepto1 = partes[3].trim();
		var codigo = $("#codigo").val();
		var efectivo_as = $("#txt_efectiv").val();
		var chq_as = $("#txt_cheq_dep").val();
		var moneda = $("#txt_moneda").val();
		var cotizacion = $("#cotizacion").val();
		var con = $("#con").val();
		var tipo_cue = $("#txt_tipo").val();
		var valor = $('#va').val();
		if(moneda==2)
		{
			Swal.fire({type: 'error', title: 'Oops...', text: 'No se puede agregar cotizacion vacia o cero!'});
		}

		var parametros = 
			{
				"va" : valor,
				"dconcepto1" : '.',
				"codigo" : codigo,
				"cuenta" : dconcepto1,
				"efectivo_as" : efectivo_as,
				"chq_as" : chq_as,
				"moneda" : moneda,
				"tipo_cue" : tipo_cue,
				"cotizacion" : cotizacion,
				"con" : con,
				"t_no" : '1',
				"ajax_page": 'ing1',
				"cl": 'as_i'
												
			};
		$.ajax({
			data:  parametros,
			url:   'ajax/vista_ajax.php',
			type:  'post',
			// beforeSend: function () {
			// 		$("#tab1default").html("");
			// },
			success:  function (response) {
				cargar_tablas_contabilidad();
        cargar_totales_aseintos();														
			}
		});
    }

    function subcuenta_frame()
    {
      var deha = $('#txt_tipo').val();
      var moneda = $('#txt_moneda').val();
      if(deha=='' || moneda=='')
      {
        return false;
      }

      var tipo = $('#txt_subcta').val();
      var cta = $('#txt_codigo').val();
      var tipoc = $('#tipoc').val();
      $('#modal_cuenta').modal('hide');
      if(tipo == 'C' || tipo =='P' || tipo == 'G' || tipo=='I' || tipo=='PM' || tipo=='CP')
      {
        titulos(tipo);
        var src ="../vista/modales.php?FSubCtas=true&mod=&tipo_subcta="+tipo+"&OpcDH="+deha+"&OpcTM="+moneda+"&cta="+cta+"&tipoc="+tipoc+"#";
        $('#modal_subcuentas').modal('show');
        $('#titulo_frame').text('Ingreso de sub cuenta por cobras');
        $('#frame').attr('src',src).show();
      }else if(tipo=="CC")
      {
         
      }else
      {
        cambia_foco();
      }
    }
     function titulos(tc)
    {
      switch(tc) {
        case 'C':
           $('#titulo_frame').text("Ingreso se Subcuenta por Cobrar");
          break;
        case 'P':
           $('#titulo_frame').text("Ingreso se Subcuenta por Pagar");
          break;
          case 'G':
           $('#titulo_frame').text("Ingreso se Subcuenta de Gastos");
          break;
          case 'I':
           $('#titulo_frame').text("Ingreso se Subcuenta de Ingreso");
          break;
          case 'CP':
           $('#titulo_frame').text("Ingreso se Subcuenta por Cobrar");
          break;
          case 'PM':
           $('#titulo_frame').text("Ingreso se Subcuenta de Ingreso");
          break;
    }
  }

    function recarar()
    {

    cargar_tablas_contabilidad();
    cargar_tablas_tab4();
    cargar_tablas_retenciones();
    cargar_tablas_sc();

    }
    function cargar_modal()
    {
      var cod = $('#codigo').val();
      switch(cod) {
        case 'AC':
        case 'ac':
           if($('#beneficiario1').val()=='')
           {
            Swal.fire('Seleccione un beneficiario','','info');
            return false;
           }
            eliminar_ac();
           $('#titulo_frame').text("COMPRAS");
           var prv = $('#ruc').val();
           var ben = $('#beneficiario1 option:selected').text();
           var fec = $('#fecha1').val();
           var src ="../vista/modales.php?FCompras=true&mod=&prv="+prv+"&ben="+ben+"&fec="+fec+"#";
           $('#frame').attr('src',src).show();

           $('#frame').css('height','605px').show();
           $('#modal_subcuentas').modal('show');
          break;
        case 'AV':
        case 'av':
           $('#titulo_frame').text("VENTAS");
           $('#modal_subcuentas').modal('show');
          break;
          case 'AI':
          case 'ai':
           $('#titulo_frame').text("IMPORTACIONES");
           $('#modal_subcuentas').modal('show');
          break;
          case 'AE':
          case 'ae':
           $('#titulo_frame').text("EXPORTACIONES");
           $('#modal_subcuentas').modal('show');
          break;
    }
  }

  function validar_comprobante()
  {
    var debe =$('#txt_debe').val();
    var haber = $('#txt_haber').val(); 
    var ben = $('#beneficiario1').val();
    var fecha = $('#fecha1').val();
    var tip = $('#tipoc').val();
    var ruc = $('#ruc').val();
    var concepto = $('#concepto').val();
    var haber = $('#txt_haber').val();
    var com = $('#num_com').text();
    // var comprobante = com.split('.');
    if((debe != haber) || (debe==0 && haber==0) )
    {
      Swal.fire( 'Las transacciones no cuadran correctamente  corrija los resultados de las cuentas','','info');
      return false;
    }
    if(ben =='')
    {      
      Swal.fire( 'Seleccione un beneficiario','','info');
      return false;
    }
    var parametros = 
    {
      'ruc': ruc, //codigo del cliente que sale co el ruc del beneficiario codigo
      'tip':tip,//tipo de cuenta contable cd, etc
      "fecha": fecha,// fecha actual 2020-09-21
      'concepto':concepto, //detalle de la transaccion realida
      'totalh': haber, //total del haber
      'num_com':com,
    }
    Swal.fire({
      title: "Esta seguro de Grabar el "+$('#num_com').text(),
      text: "",
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si!'
    }).then((result) => {
      if (result.value==true) {

         grabar_comprobante(parametros);
      }else
      {
        alert('cancelado');
      }
    })
  }

  function grabar_comprobante(parametros)
  {      
      $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?generar_comprobante=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                eliminar_ac();
                Swal.fire({
                   title: 'Comprobante Generado',
                   text: "",
                   type: 'success',
                   showCancelButton: false,
                   confirmButtonColor: '#3085d6',
                   cancelButtonColor: '#d33',
                   confirmButtonText: 'OK!'
                 }).then((result) => {
                   if (result.value==true) {
                    location.reload();
                   }
                 });
              }else
              {
                Swal.fire( 'No se pudo generar','','error');
              }

          }
        });
  }

  function eliminar_ac()
  {
    $.ajax({
          // data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?eliminar_retenciones=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                
              }else
              {
                Swal.fire( 'No se pudo Eliminar','','error');
              }

          }
        });

  }

  function eliminar(codigo,tabla)
  {
     var parametros = 
    {
      'tabla':tabla,
      'Codigo':codigo,
    }

    Swal.fire({
      title: 'Esta seguro de eliminar este registro',
      text: "",
      type: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'OK!'
    }).then((result) => {
      if (result.value==true) {
        $.ajax({
          data:  {parametros:parametros},
          url:   '../controlador/contabilidad/incomC.php?eliminarregistro=true',
          type:  'post',
          dataType: 'json',
            success:  function (response) { 
              if(response==1)
              {
                 cargar_tablas_contabilidad();
                 cargar_tablas_tab4();
                 cargar_tablas_retenciones();
                 cargar_tablas_sc();
                 cargar_totales_aseintos();
              }

          }
        });                    
      }
    });
  }

</script>

<div class="panel box box-primary">	  
  <div id="collapseOne" class="panel-collapse collapse in">
	<div class="box-body">
		
		<div width='100%'>
			<div style=" float: left;width:30%" align='left' width='30%'>
				<button type="button" class="btn btn-default btn-xs active" onclick="reset_1('comproba','CD');" 
				id='CD' style="width: 15%;" title='Comprobante diario'>Diario</button>
				<button type="button" class="btn btn-default btn-xs" onclick="reset_1('comproba','CI');" 
				id='CI' style="width: 15%;" title='Comprobante de ingreso'>Ingreso</button>
				<button type="button" class="btn btn-default btn-xs" onclick="reset_1('comproba','CE');" 
				id='CE' style="width: 15%;" title='Comprobante de egreso'>Egreso</button>
				<button type="button" class="btn btn-default btn-xs" onclick="reset_1('comproba','ND');" 
				id='ND' style="width: 15%;" title='Comprobante nota de debito'>N/D</button>
				<button type="button" class="btn btn-default btn-xs" onclick="reset_1('comproba','NC');" 
				id='NC' style="width: 15%;" title='Comprobante nota de credito'>N/C</button>
				<input id="tipoc" name="tipoc" type="hidden" value="CD">
			</div>											
			<div align='' width='40%'  style="float: left;width:40%; ">
				<div align='top' style="float: top;">
					<h3 align='center' style="float: top;padding: 5px 10px 5px 10px;vertical-align:top; margin-top: 1px; margin-bottom: 1px;" id='num_com'>
						Comprobante de Diario No. 0000-00000000
					</h3>
				</div>
			</div>
		
			<div class="checkbox" align='right' width='30%' style=" float: right;width:30%">
				<label>
					<input type="checkbox"> Imprimir copia
				</label>
			</div>
		</div>
		<div class="box table-responsive">
		
            <div class="box-header">
              <!--<h3 class="box-title">Striped Full Width Table</h3>-->
			  <table>
				<tr>
					<td>
						<!-- <div class="loader1"></div> -->
					</td>
				</tr>
			  </table>					
					<?php
					$texto[0]=1;
					if(count($texto)>0)
							{
					?>	
								<form action="#" class="credit-card-div" id='formu1'>
									<div class="panel panel-default" >
										
										<div class="panel-heading">
											<div class="row " style="padding-bottom: 5px;">
												
												<div class="col-md-2 col-sm-2 col-xs-2">
													<!-- <div class="form-group"> -->
													     <div class="input-group">
														     <div class="input-group-addon input-sm">
															     <b>FECHA:</b>
														     </div>
														     <input type="date" class="form-control input-sm" id="fecha1" placeholder="01/01/2019" value='<?php echo date('Y-m-d') ?>' maxlength='10' size='15' onKeyUp="reset_('comproba','');">
													     </div>
													<!-- </div> -->
												</div>
												<div class="col-md-7 col-sm-7 col-xs-7">
													<!-- <div class="form-group"> -->
													     <div class="input-group">
														     <div class="input-group-addon input-sm">
															     <b>BENEFICIARIO:</b>
														     </div>														
															<select id="beneficiario1" name='beneficiario1' class='input-sm' onchange="benefeciario_selec()">
                                <option value="">Seleccione beneficiario</option>                                
                              </select>
															<input type="hidden" name="beneficiario2" id="beneficiario2" value='' />
													     </div>
													<!-- </div> -->
												</div>
												
												<div class="col-md-3 col-sm-3 col-xs-3">
													<!-- <div class="form-group"> -->
													     <div class="input-group">
														     <div class="input-group-addon input-sm">
															     <b>R.U.C / C.I:</b>
														     </div>
														     <input type="text" class=" form-control input-sm" id="ruc" name='ruc' placeholder="R.U.C / C.I" value='000000000' maxlength='30' size='25'>
													     </div>
													<!-- </div> -->
												</div>
												
											</div>
											<div class="row ">
												<div class="col-md-6 col-sm-6 col-xs-6">
													<div class="input-group">
														<div class="btn_f input-sm col-sm-12 text-center">
															<b>EMAIL:</b>
														</div>
														    <input type="email" class="form-control input-sm" id="email" name="email" placeholder="prueba@prueba.com" 
														maxlength='255' size='100'/>
													</div>
												</div>
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group">
														<div class="btn_f input-sm col-sm-12 text-center">
															<b>COTIZACIÓN:</b>
														</div>
														    <input type="text" class="form-control input-sm" id="cotizacion" name='cotizacion' placeholder="0.00" onKeyPress='return soloNumerosDecimales(event)' style="text-align:right;" maxlength='20' size='25' />
													</div>
												</div>
												<div class="" 
												style="float: left;position:relative;left:1%;width: 10%;margin-bottom: 1px;">
													  <label class="labeltext" style="margin-bottom: 1px;">Tipo de conversión</label><br>
														<div class="">
															<label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(/)</span>
															  <input type="radio" checked="checked" name="con" id='con' value='/'>
															  <span class="checkmark"></span>
															</label>        
															<label class="customradio" style="margin-bottom: 1px;"><span class="radiotextsty">(X)</span>
															  <input type="radio" name="con" id='con' value='X'>
															  <span class="checkmark"></span>
															</label>
														</div>
												</div>
												<div class="col-md-2 col-sm-2 col-xs-2">
													<div class="input-group">
														<div class="btn_f input-sm col-sm-12 text-center">
															<b>VALOR TOTAL:</b>
														</div>
														    <input type="text" class="form-control input-sm" id="VT" name='VT' placeholder="0.00" style="text-align:right;" onKeyPress='return soloNumerosDecimales(event)' maxlength='20' size='33'>
													</div>
												</div>
											</div>
											<div id='ineg' class="row" style="display: none;"> <br>
												<div class="row">
													<div class="col-md-1">
														<label class="label-inline" id="rbl_efec"><input type="checkbox" id='efec' name='efec'onclick="mostrar_efectivo()" /> Efectivo</label>
														
													</div>
													<div class="col-md-11" id="ineg1" style="display: none;">
														<div class="col-md-10">
															<div class="input-group">
														        <div class="input-group-addon input-sm">
															        <b>CUENTA:</b>
														        </div>
														        <select class="form-control input-sm" name="conceptoe" id='conceptoe'>
														     	   <option value="">Seleccione cuenta de efectivo</option>
														        </select>
													        </div>														
													    </div>
													    <div class="col-md-2">
														    <div class="input-group">
														         <div class="input-group-addon input-sm">
															         <b><?php echo $_SESSION['INGRESO']['S_M']; ?>:</b>
														         </div>
														         <input type="text" class="xs" id="vae" name='vae' placeholder="0.00" style="text-align:right;" onKeyPress='return soloNumerosDecimales(event)' maxlength='20' size='13'>
													         </div>														
													    </div>															
													</div>												
												</div>
												<div class="row">
													<div class="col-md-1">
														<label class="label-inline" id="rbl_banco" style="background:rgb(40, 96, 144) ;color: #FFFFFF;padding:5px;border-radius: 5px;"><input type="checkbox" id='ban' name='ban'onclick="mostrar_banco()" checked="" /> Banco</label>
													</div>
													<div class="col-md-11" id='ineg2'>
														<div class="col-md-10">
															<div class="input-group">
														         <div class="input-group-addon input-sm">
														             <b>CUENTA:</b>
														         </div>
														         <select class="form-control input-sm" name="conceptob" id='conceptob'>
														   	         <option value="">Seleccione cuenta de banco</option>
														        </select>
													        </div>														
														</div>
														<div class="col-md-2"  id="ingreso_val_banco">
															<div class="input-group">
														         <div class="input-group-addon input-sm">
														             <b><?php echo $_SESSION['INGRESO']['S_M']; ?>:</b>
														         </div>
														         <input type="text" class="xs" id="vab" name='vab' placeholder="0.00" 
																style="text-align:right;"  onKeyPress='return soloNumerosDecimales(event)' 
																maxlength='20' size='13' value='0.00'>
													        </div>  
														</div>
														<div class="col-md-2" id="no_cheque" style="display: none;">
															<div class="input-group">
														         <div class="input-group-addon input-sm">
														             <b>No. Cheq:</b>
														         </div>
														         <input type="text" class="xs" id="no_cheq" name='no_cheq' placeholder="00000001" 
																style="text-align:right;"  onKeyPress='return soloNumerosDecimales(event)' 
																maxlength='20' size='13' value='00000001' onblur="agregar_depo()">
													        </div>	
														</div>
													</div>													
												</div>
												<div class="row" id='ineg3' >
													<div class="col-md-8">
														<div id="div_tabla">
															<?php  
																$balance=ListarAsientoTem(null,'1','1','0,2,clave');
															?>															
														</div>
														<input type="hidden" id='reg1' name='reg1'  value='' />
													</div>
													<div class="col-md-2">
														<div class="input-group">
														    <div class="btn_f input-sm col-sm-12 text-center">
															    <b>Efectivizar:</b>
														    </div>
														    <input type="date" class="form-control input-sm" id="efecti" name='efecti' placeholder="01/01/2019" value='<?php echo date('Y-m-d') ?>'>
													    </div>														
													</div>
													<div class="col-md-2">
														<div class="input-group" id="deposito_no">
														    <div class="btn_f input-sm col-sm-12 text-center">
															    <b>Deposito No:</b>
														    </div>
														    <input type="text" class="form-control input-sm" id="depos" name='depos' placeholder="12345" onblur="agregar_depo()">
													    </div>
													</div>													
												</div>											
											</div>

											<div class="row " style="padding-bottom: 5px;"><br>	
												<div class="col-md-12 col-sm-12 col-xs-12">
													     <div class="input-group">
														     <div class="input-group-addon input-sm">
															     <b>CONCEPTO:</b>
														     </div>
														    <input type="text" class="form-control input-sm" id="concepto" name="concepto" placeholder="concepto" maxlength='150'/>
													     </div>
												</div>												
											</div>

											<div class="row">
												<div class="col-md-2 col-sm-1 col-xs-1">
													<div class="input-group">
														<div class="input-sm col-md-12 btn_f text-center">
															<b>CODIGO:</b>
														</div>
														 <input type="text" class="form-control input-sm" id="codigo" name='codigo' placeholder="codigo" maxlength='30' size='12' onblur="cargar_modal()" />
													</div>
												</div>
												<div class="col-md-8 col-sm-9 col-xs-9">
													     <div class="input-group" style="display: block;">
														     <div class="btn_f input-sm col-md-12 text-center">
															    <b>DIGITE LA CLAVE O SELECCIONE LA CUENTA:</b>
														     </div>
														     <select id="cuentar" class="input-sm" onchange="abrir_modal_cuenta()">
														     	<option value="">Seleccione una cuenta</option>  	
														     </select>
														       <!--  <input type="text" class="xs" id="cuenta" name='cuenta' placeholder="cuenta" maxlength='70' size='153'/>
														        <input type="hidden" id='codigo_cu' name='codigo_cu' value='' />-->
														        <input type="hidden" id='TC' name='TC'  value='' />
													     </div>
												</div>
												<div class="col-md-2 col-sm-2 col-xs-2">
													     <div class="input-group">
														     <div class="btn_f input-sm col-md-12 text-center">
															    <b>VALOR:</b>
														     </div>
														       <input type="text" class="form-control input-sm" id="va" name='va' 
															placeholder="0.00" style="text-align:right;" onKeyPress='return soloNumerosDecimales(event)' onblur="ingresar_asiento()">
													     </div>
												</div>
											</div>
											<div class="row">
												<input type="hidden" name="txt_cuenta" id="txt_cuenta">
												<input type="hidden" name="txt_codigo" id="txt_codigo">
												<input type="hidden" name="txt_tipocta" id="txt_tipocta">
												<input type="hidden" name="txt_subcta" id="txt_subcta">
												<input type="hidden" name="txt_tipopago" id="txt_tipopago">
												<input type="hidden" name="txt_moneda_cta" id="txt_moneda_cta">											
											</div>
											<div class="row">
													<div class="col-xs-12 ">
														<div class="panel-heading">
															<ul class="nav nav-tabs">
																<li class="active"><a href="#contabilidad" data-toggle="tab">4. Contabilización</a></li>
																<li><a href="#subcuentas" data-toggle="tab">5. Subcuentas</a></li>
																<li><a href="#retenciones" data-toggle="tab">6. Retenciones</a></li>
																<li><a href="#ac_av_ai_ae" data-toggle="tab">7. AC-AV-AI-AE</a></li>
															</ul>
														</div>
														<div class="panel-body">
															<div class="tab-content">
																<div class="tab-pane fade in active" id="contabilidad">
																	<?php 
																		// $balance=ListarAsientoTem(null,null,'1','0,1,clave');
																		// ListarTotalesTem(null,null,'1','0,1,clave');
																	?>
																	
																</div>
																<div class="tab-pane fade" id="subcuentas">Default 2</div>
																<div class="tab-pane fade" id="retenciones">Default 3</div>
																<div class="tab-pane fade" id="ac_av_ai_ae">Default 4</div>
															</div>
														</div>
													</div>
												</div>
												<div class="row ">
                          <div class="col-sm-6">
                             <button type="button"  class="btn btn-primary" id='grabar1' onclick="validar_comprobante()">Guardar</button>
                             <button type="button"  class="btn btn-danger" id='cancelar'>Cancelar</button>                            
                          </div>
                          <div class="col-sm-6">
                            <div class="col-sm-4">
                              <b>Diferencia</b>
                                <input type="text" name="txt_diferencia" id="txt_diferencia" class="form-control input-sm" readonly="" value="0">
                            </div>
                            <div class="col-sm-4">
                              <b>Totales</b>
                               <input type="text" name="txt_debe" id="txt_debe" class="form-control input-sm" readonly="" value="0">
                            </div>
                            <div class="col-sm-4"><br>
                                <input type="text" name="txt_haber" id="txt_haber" class="form-control input-sm" readonly="" value="0">
                            </div>
                          </div>
												</div>												
										</div>	
																					
											<div class="row ">												
												
												<div class="row " id='compro'>
												</div>
																								 
											</div>
										</div>
									</form>		
										
											
										
								<!-- Modal -->
								<?php
									$ove=400;
								?>
								<div class="modal fade" id="myModal" role="dialog" >
									<div class="modal-dialog" style="width:90%;">
									  <div class="modal-content" style="width:90%;">
										<div class="modal-header">
										  <button type="button" class="close" data-dismiss="modal">&times;</button>
										  <h4 class="modal-title"><img  width='5%'  height='5%' src="../../img/jpg/logo.jpg"> Comprobante</h4>
										</div>
										<div class="modal-body" style="height:<?php echo $ove; ?>px;overflow-y: scroll;">
											<div class="form-group">
												<div id='pdfcom'>
				
												</div>
											</div>
										</div>
										<div class="modal-footer">
											
										    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
										</div>
									  </div>
									  
									</div>
								</div>
								<div id='entidad1'>
									
								</div>
								<div id='empresa1'>
								</div>
								
								<?php
									// require_once("popup.php");
								?>
								
								</div>
								
								<script>
								

								// $("#cancelar").click(function(e) {
								// 	//seteamos valores
								// 	document.getElementById('beneficiario1').value='.';
								// 	document.getElementById('va').value='';
								// 	document.getElementById('concepto').value='';
								// 	document.getElementById('ruc').value='000000000';
								// 	document.getElementById('email').value='';
								// 	document.getElementById('codigo').value='';
								// 	document.getElementById('cuentar').value='';
								// 	//listar
								// 	var parametros = 
								// 	{
								// 		"ajax_page": 'bus',
								// 		"cl": 'lis_com'
								// 	};
								// 	$.ajax({
								// 		data:  parametros,
								// 		url:   'ajax/vista_ajax.php',
								// 		type:  'post',
								// 		beforeSend: function () {
								// 				$("#tab1default").html("");
								// 		},
								// 		success:  function (response) {
								// 				$("#tab1default").html("");
								// 				$("#tab1default").html(response);
								// 				// var valor = $("#tab1default").html();
												
												
								// 		}
								// 	});
								// });

								$("#grabar").click(function(e) {
									//beneficiario
									if (document.getElementById("beneficiario1").value =='' ) {
										if($('#beneficiario1').css('display') == 'none'){
										   // Acción si el elemento no es visible
										   if(document.getElementById("beneficiario").value!='.')
										   {
											   document.getElementById('beneficiario').focus();	
										   }
										   
										}else{
										   // Acción si el elemento es visible
										   document.getElementById('beneficiario1').focus();	
										}
										
									}
									var bene = document.getElementById('beneficiario').value;
									if(bene=='no existe registro' || 'Seleccionar'==bene || bene=='')
									{
										 Swal.fire({
										  type: 'error',
										  title: 'debe agregar beneficiario!',
										  text: ''
										});
									}
									else
									{
										var dife = document.getElementById('diferencia').value;
										var totalh = document.getElementById('totalh').value;
										var totald = document.getElementById('totald').value;
										//alert(dife);
										if(parseFloat(dife)!=0 )
										{
											 Swal.fire({
											  type: 'error',
											  title: 'Las transacciones no cuadran correntamente corrija los resultados de las cuentas!',
											  text: ''
											});
										}
										else
										{
											if(parseFloat(totalh)==0 || parseFloat(totald)==0)
											{
												 Swal.fire({
												  type: 'error',
												  title: 'Las transacciones no cuadran correntamente corrija los resultados de las cuentas!',
												  text: ''
												});
											}
											else
											{	
												var T_N=<?php echo $T_No; ?>;
												var tt=document.getElementById('TC').value;
												var ben=document.getElementById('beneficiario').value;
												var ru=document.getElementById('ruc').value;
												var co=document.getElementById('codigo').value;
												var tip=document.getElementById('tipoc').value;
												//var tic=document.getElementById('tipo_cue').value;
												//var sub=document.getElementById('subcuenta').value;
												//var sub1 = document.getElementById("subcuenta");
												//var sub2 = sub1.options[sub1.selectedIndex].text;
												var fecha_sc=document.getElementById('fecha_sc').value;
												var fac2=document.getElementById('fac2').value;
												var mes=document.getElementById('mes').value;
												var valorn=document.getElementById('valorn').value;
												//var moneda=document.getElementById('moneda').value;
												var Trans=document.getElementById('Trans_Sub').value;
												var fecha1 =document.getElementById('fecha1').value;
												var concepto= document.getElementById('concepto').value;
												var cotizacion= document.getElementById('cotizacion').value;
												var totalh= document.getElementById('totalh').value;
												var num_com=document.getElementById('num_com').innerHTML;
												//alert(num_com);
												var parametros = 
												{
													"ajax_page": 'ing1',
													cl: 'ing_com',
													be: ben,
													ru: ru,
													co: co,
													tip: tip,
													//tic: tic,
													//sub: sub,
													//sub2: sub2,
													fecha_sc: fecha_sc,
													fac2: fac2,
													mes: mes,
													valorn: valorn,
													//moneda: moneda,
													Trans: Trans,
													T_N: T_N,
													t: tt,
													fecha1: fecha1,
													concepto: concepto,
													totalh: totalh,
													num_com: num_com
												};
												$.ajax({
													data:  parametros,
													url:   'ajax/vista_ajax.php',
													type:  'post',
													beforeSend: function () {
															$("#compro").html("");
													},
													success:  function (response) {
														$("#compro").html("");
														$("#compro").html(response);
														 Swal.fire({
															  //position: 'top-end',
															  type: 'success',
															  title: 'Comprobante ingresado con exito!',
															  showConfirmButton: true
															  //timer: 2500
															});
														
														// var valor = $("#subcuenta1").html();	
														var tip=document.getElementById('tipoc').value;
														if(tip==null)
														{
															tip='CD';
														}
														//
														var parametros = 
														{
															"ajax_page": 'bus',
															cl: 'num_com',
															tip: tip										
														};
														$.ajax({
															data:  parametros,
															url:   'ajax/vista_ajax.php',
															type:  'post',
															beforeSend: function () {
																	$("#num_com").html("");
															},
															success:  function (response) {
																	$("#num_com").html("");
																	$("#num_com").html(response);
																	// var valor = $("#subcuenta1").html();	
															}
														});
														var ca = document.getElementById('num_com1').value;
														$.post('ajax/vista_ajax.php'
														, {ajax_page: 'comp', com: ca }, function(data){
															//$('div.pdfcom').load(data);
															ventana = window.open("ajax/TEMP/"+ca+".pdf", "nuevo", "width=400,height=400");
															ventana.close();
															$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+ca+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
															$("#myModal").modal();
															//window.location.reload(true);
															//alert('entrooo '+idMensaje+" ajax/TEMP/'+value1+'.pdf");
														});
														//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+ca+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
														//$("#myModal").modal();
														
														//seteamos valores
														document.getElementById('beneficiario').value='';
														document.getElementById('va').value='';
														document.getElementById('concepto').value='';
														document.getElementById('ruc').value='';
														document.getElementById('email').value='';
														document.getElementById('codigo').value='';
														document.getElementById('cuenta').value='';
														//listar
														var parametros = 
														{
															"ajax_page": 'bus',
															"cl": 'lis_com'
														};
														$.ajax({
															data:  parametros,
															url:   'ajax/vista_ajax.php',
															type:  'post',
															beforeSend: function () {
																	$("#tab1default").html("");
															},
															success:  function (response) {
																	$("#tab1default").html("");
																	$("#tab1default").html(response);
																	// var valor = $("#tab1default").html();
																	
																	
															}
														});
														
													}
												});
											}
										}
									}
								});
								
								$("#vae").keydown(function(e) 
								{
									var code = e.keyCode || e.which;
									//eliminamos cookies
									document.cookie = "nombre=; max-age=0";
									var select = document.getElementById('tipoc');
									var vae = document.getElementById("vae").value;
									var conceptoe = document.getElementById("conceptoe").value;
									//VT
									if (code == '9')
									{
										//alert(code);
										if( select.value=='CI')
										{
											var parametros = 
											{
												"vae" : vae,
												"conceptoe" : conceptoe,
												"ajax_page": 'ing1',
												"tipoc": select.value,
												cl: 'as_i_h_e'
												
											};
											$.ajax({
												data:  parametros,
												url:   'ajax/vista_ajax.php',
												type:  'post',
												beforeSend: function () {
														$("#tab1default").html("");
												},
												success:  function (response) {
														$("#tab1default").html("");
														$("#tab1default").html(response);
														// var valor = $("#tab1default").html();
														document.getElementById("VT").value=vae;														
												}
											});
										}
									}
								});
																
								 $("#deposs").keydown(function(e) {
									var depos = $("#depos").val();
									//tecla tab
									var code = '';
									if (typeof e !== 'undefined')
									{
										var code = e.keyCode || e.which;
										
									}
									 //eliminamos cookies
									 document.cookie = "nombre=; max-age=0";
									 //alert(code);
									if (code == '9') {
										//alert(depos);
										 document.cookie = "nombre=2; ";
										 var banco = document.getElementById("conceptob").value;
										 var vab = document.getElementById("vab").value;
										 var efecti = document.getElementById("efecti").value;
										 //alert (banco+' '+vab+' '+efecti+' '+depos);
										// alert(depos);
										var parametros = 
										{
											"banco" : banco,
											//"vab" : vab,
											"vab" : '0',
											"efecti" : efecti,
											"depos" : depos,
											"ajax_page": 'ing1',
											"cl": 'as_b_i'
											
										};
										$.ajax({
											data:  parametros,
											url:   'ajax/vista_ajax.php',
											type:  'post',
											beforeSend: function () {
													$("#tabla_b").html("");
											},
											success:  function (response) {
													$("#tabla_b").html("");
													$("#tabla_b").html(response);
													 var valor = $("#tabla_b").html();
													
													
											}
										});
									}
								
								});
								
								$(document).ready(function () {
									
									
								});
					
								
								
								function  select1()
								{
									if(readCookie('cod')=='ca_cu_a')
									{
										if (document.getElementById("codigo1").value =='') {
											document.getElementById('codigo1').focus();	
										}
										else
										{
											document.getElementById("valoro").style.display = "none";
											if(document.getElementById('TC').value=='BA')
											{
												$("#tcom").html("<div class='marco'>"+
																"<form class='form1' action=''>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 1%;left: 1%;'>"+
																			"<b>Efectivizar</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 1%;left: 39%;'>"+
																		"<input id='efectivo_as' name='efectivo_as' class='' onfocus='cerrar(\"codigo1\");' "+
																		" type='date' maxlength='10' size='10' value='<?php echo date('Y-m-d') ?>'>"+
																	"</div>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 20%;left: 1%;'>"+
																			"<b>CHq/Dep</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 20%;left: 39%;'>"+
																		"<input id='chq_as' name='chq_as' class='' type='text' maxlength='30' size='5'>"+
																	"</div>"+
																	"<label for='firstName' class=''>"+
																		/*"<div style='position:absolute;top: 33%;left: 40%;'>"+
																			"<b>Valores:</b>"+
																		"</div>"+*/
																		"<div style='position:absolute;top: 40%;left: 1%;'>"+
																			"<b>1 M/N</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 49%;left: 1%;'>"+
																			"<b>2 M/E</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 40%;left: 39%;'>"+
																		"<input id='moneda' name='moneda' class='' type='text' "+
																		" onKeyPress='return soloNumeros12(event)' maxlength='1' size='5'>"+
																	"</div>"+
																	"<label for='lastName' class=''>"+
																		"<div style='position:absolute;top: 57%;left: 1%;'>"+
																			"<b>Debe 1:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 65%;left: 1%;'>"+
																			"<b>Haber 2</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 57%;left: 39%;'>"+
																		"<input id='tipo_cue' name='tipo_cue' class='' "+
																		" onkeydown='proceso1(event)' type='text' "+
																		" onKeyPress='return soloNumeros12(event)' maxlength='1' size='5'>"+	
																	"</div>	"+																
																"</form>"+
															"</div>"+
															"<input type='hidden' id='tipo_f' name='tipo_f'  value='1' />"+
															"<input type='hidden' id='dconcepto1' name='dconcepto1'  value='' />");
											}
											else
											{
												$("#tcom").html("<div class='marco'>"+
																"<form class='form1' action=''>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 3%;left: 40%;'>"+
																			"<b>Valores:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 13%;left: 1%;'>"+
																			"<b>1 M/N</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 21%;left: 1%;'>"+
																			"<b>2 M/E</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 13%;left: 32%;'>"+
																		"<input id='moneda' name='moneda' class='' type='text' "+
																		" onfocus='cerrar(\"codigo1\");' onKeyPress='return soloNumeros12(event)' maxlength='1' size='5'>"+
																	"</div>"+
																	"<label for='lastName' class=''>"+
																		"<div style='position:absolute;top: 32%;left: 1%;'>"+
																			"<b>Debe 1:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 40%;left: 1%;'>"+
																			"<b>Haber 2</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 32%;left: 32%;'>"+
																		"<input id='tipo_cue' name='tipo_cue' class='' type='text' "+
																		" onkeydown='proceso1(event)' maxlength='1' "+
																		" onKeyPress='return soloNumeros12(event)' size='5'>"+	
																	"</div>	"+																
																"</form>"+
															"</div>"+
															"<input type='hidden' id='tipo_f' name='tipo_f'  value='2' />"+
															"<input type='hidden' id='dconcepto1' name='dconcepto1'  value='' />");
											}
											document.getElementById("tcom").style.display = "block";
										}
									}
								}
																								
								function  selec(e,i)
								{
									//$(e).text('there');
									//ca = $(e).html().split('-');
									//alert($(e).html());
									//document.getElementById('beneficiario1').value=ca[0];
									//
									let cad = e;
									//limpia espacios vacios al inix¡cio y final de cadena de texto
									cad = cad.trim();
									//cad = cad.replace(" ", " ");
									//alert(e+' '+i+' '+cad);
									if(readCookie('cod')=='ca_cu_a')
									{
										$.post('ajax/vista_ajax.php'
										, {ajax_page: 'bus', com: cad,cl: 'ca_cu_b',ot: i }, function(data){
											var obj = JSON.parse(data);
											alert(obj[0].TC);
											//document.getElementById('codigo_cu').value=parseInt(obj[0].Cla);
											document.getElementById('codigo').value=obj[0].Cod;	
											document.getElementById('cuenta').value=obj[0].Cu;	
											document.getElementById('TC').value=obj[0].TC;	
											//llamamos siguiente popup
											//alert(depos);
											document.getElementById("valoro").style.display = "none";
											if(document.getElementById('TC').value=='BA')
											{
												$("#tcom").html("<div class='marco'>"+
																"<form class='form1' action=''>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 1%;left: 1%;'>"+
																			"<b>Efectivizar</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 1%;left: 39%;'>"+
																		"<input id='efectivo_as' name='efectivo_as' class='' "+
																		" type='date' maxlength='10' size='10' value='<?php echo date('Y-m-d') ?>'>"+
																	"</div>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 20%;left: 1%;'>"+
																			"<b>CHq/Dep</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 20%;left: 39%;'>"+
																		"<input id='chq_as' name='chq_as' class='' type='text' maxlength='30' size='5'>"+
																	"</div>"+
																	"<label for='firstName' class=''>"+
																		/*"<div style='position:absolute;top: 33%;left: 40%;'>"+
																			"<b>Valores:</b>"+
																		"</div>"+*/
																		"<div style='position:absolute;top: 40%;left: 1%;'>"+
																			"<b>1 M/N</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 49%;left: 1%;'>"+
																			"<b>2 M/E</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 40%;left: 39%;'>"+
																		"<input id='moneda' name='moneda' class='' type='text' "+
																		" onKeyPress='return soloNumeros12(event)' maxlength='1' size='5'>"+
																	"</div>"+
																	"<label for='lastName' class=''>"+
																		"<div style='position:absolute;top: 57%;left: 1%;'>"+
																			"<b>Debe 1:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 65%;left: 1%;'>"+
																			"<b>Haber 2</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 57%;left: 39%;'>"+
																		"<input id='tipo_cue' name='tipo_cue' class='' "+
																		" onkeydown='proceso1(event)' type='text' "+
																		" onKeyPress='return soloNumeros12(event)' maxlength='1' size='5'>"+	
																	"</div>	"+																
																"</form>"+
															"</div>"+
															"<input type='hidden' id='tipo_f' name='tipo_f'  value='1' />"+
															"<input type='hidden' id='dconcepto1' name='dconcepto1'  value='' />");
											}
											else
											{
												$("#tcom").html("<div class='marco'>"+
																"<form class='form1' action=''>"+
																	"<label for='firstName' class=''>"+
																		"<div style='position:absolute;top: 3%;left: 40%;'>"+
																			"<b>Valores:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 13%;left: 1%;'>"+
																			"<b>1 M/N</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 21%;left: 1%;'>"+
																			"<b>2 M/E</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 13%;left: 32%;'>"+
																		"<input id='moneda' name='moneda' class='' type='text' "+
																		" onKeyPress='return soloNumeros12(event)' maxlength='1' size='5'>"+
																	"</div>"+
																	"<label for='lastName' class=''>"+
																		"<div style='position:absolute;top: 32%;left: 1%;'>"+
																			"<b>Debe 1:</b>"+
																		"</div>"+
																		"<div style='position:absolute;top: 40%;left: 1%;'>"+
																			"<b>Haber 2</b>"+
																		"</div>"+
																	"</label>"+
																	"<div style='position:absolute;top: 32%;left: 32%;'>"+
																		"<input id='tipo_cue' name='tipo_cue' class='' type='text' "+
																		" onkeydown='proceso1(event)' maxlength='1' "+
																		" onKeyPress='return soloNumeros12(event)' size='5'>"+	
																	"</div>	"+																
																"</form>"+
															"</div>"+
															"<input type='hidden' id='tipo_f' name='tipo_f'  value='2' />"+
															"<input type='hidden' id='dconcepto1' name='dconcepto1'  value='' />");
											}
											document.getElementById("tcom").style.display = "block";
										});
									}
									if(readCookie('cod')=='cl_a')
									{
										$.post('ajax/vista_ajax.php'
										, {ajax_page: 'bus', com: cad,cl: 'cl_b' }, function(data){
											var obj = JSON.parse(data);
											//alert(obj[0].Cla);
											//document.getElementById('codigo_cu').value=parseInt(obj[0].Cla);
											document.getElementById('ruc').value=obj[0].CI;	
											document.getElementById('email').value=obj[0].Em;	
											document.getElementById('beneficiario2').value=obj[0].Cod;
										});
									}
									//document.getElementById('beneficiario').value=ca[1];
									//$(e).text(ca[1]);
								}
								function proceso1(e) 
								{
									var vae = $("#tipo_cue").val();
									//tecla tab
									var code = e.keyCode || e.which;
									//eliminamos cookies
									document.cookie = "nombre=; max-age=0";
									//alert(code);
									if (code == '9')
									{
										//alert(vae);
										// document.cookie = "nombre=1; ";
										document.getElementById("tcom").style.display = "none";
										document.getElementById("valoro").style.display = "block";
										var tip=document.getElementById('tipoc').value;
										var tic=document.getElementById('tipo_cue').value;
										$("#dconcepto").focus();
										
										if(document.getElementById('TC').value=='C' || document.getElementById('TC').value=='P'
										 || document.getElementById('TC').value=='G' || document.getElementById('TC').value=='I' 
										 || document.getElementById('TC').value=='CC')
										{
											//alert(document.getElementById('TC').value);
											this.href=window.location.href+"&cod="+document.getElementById('codigo').value+"&cue="+document.getElementById('cuenta').value;
											//document.getElementById('titulosub1').value=document.getElementById('codigo').value+' '+document.getElementById('cuenta').value;
											//alert(document.getElementById('codigo').value+' '+document.getElementById('cuenta').value);
											$("#titulosub1").html(document.getElementById('codigo').value+' '+document.getElementById('cuenta').value);
											if(document.getElementById('TC').value=='C')
											{
												$("#titulosub2").html(' Subcuentas Por Cobrar ');
											}
											if(document.getElementById('TC').value=='G')
											{
												$("#titulosub2").html(' Subcuentas Gasto ');
											}
											if(document.getElementById('TC').value=='P')
											{
												$("#titulosub2").html(' Subcuentas Por Pagar ');
											}
											if(document.getElementById('TC').value=='I')
											{
												$("#titulosub2").html(' Subcuentas De Ingreso ');
											}
											if(document.getElementById('TC').value=='I')
											{
												$("#titulosub2").html(' Subcuentas De Costo ');
											}
											//llamamos consulta
											if(document.getElementById('TC').value=='CC' || document.getElementById('TC').value=='G'
											 || document.getElementById('TC').value=='I' || document.getElementById('TC').value=='C'
											  || document.getElementById('TC').value=='P')
											{
												var tt=document.getElementById('TC').value;
												var ben=document.getElementById('beneficiario').value;
												var ru=document.getElementById('ruc').value;
												var co=document.getElementById('codigo').value;
												
												var parametros = 
												{
													"ajax_page": 'bus',
													cl: 'cat_sub',
													be: ben,
													ru: ru,
													co: co,
													tip: tip,
													tic: tic,
													t: tt													
												};
												$.ajax({
													data:  parametros,
													url:   'ajax/vista_ajax.php',
													type:  'post',
													beforeSend: function () {
															$("#subcuenta1").html("");
													},
													success:  function (response) {
															$("#subcuenta1").html("");
															$("#subcuenta1").html(response);
															// var valor = $("#subcuenta1").html();	
													}
												});
											}
											 $("#mostrarmodal").modal("show");
										}
										else
										{
											//document.getElementById("va").focus();
											<?php 
											if ($_SESSION['INGRESO']['Det_Comp']==true)
											{
											?>
												Swal.fire({
												  title: 'Detalle Auxiliar',
												  html:
													'<input type="text" class="form-control" id="dconcepto" name="dconcepto" maxlength="60" placeholder="" autofocus ><br><script> $("#dconcepto").focus(); <\/script>',
												}).then((result) => {
												  if (result.value) {
													//alert(document.getElementById("dconcepto").value);
													document.getElementById("dconcepto1").value=document.getElementById("dconcepto").value;
													$("#va").focus();
													//location.href="panel.php?mos2=e";
												  } 
												});
											<?php
											}
											else
											{
												?>
												Swal.fire({
														  //position: 'top-end',
														  type: 'success',
														  title: 'Cuenta seleccionada!',
														  showConfirmButton: true
														  //timer: 2500
														}).then((result) => {
													  if (result.value) {
														$("#va").focus();
													  } 
													});
												
												//$('#va').selectRange(4);
												<?php
											}
											?>
										}
									}
								}
								
								function buscarsub() 
								{
									var tt=document.getElementById('TC').value;
									var ben=document.getElementById('beneficiario').value;
									var ru=document.getElementById('ruc').value;
									var co=document.getElementById('codigo').value;
									var tip=document.getElementById('tipoc').value;
									var tic=document.getElementById('tipo_cue').value;
									var sub=document.getElementById('subcuenta').value;
									var parametros = 
									{
										"ajax_page": 'bus',
										cl: 'trans_sub1',
										be: ben,
										ru: ru,
										co: co,
										tip: tip,
										tic: tic,
										sub: sub,
										t: tt													
									};
									$.ajax({
										data:  parametros,
										url:   'ajax/vista_ajax.php',
										type:  'post',
										beforeSend: function () {
												$("#facturas1").html("");
										},
										success:  function (response) {
												$("#facturas1").html("");
												$("#facturas1").html(response);
												// var valor = $("#subcuenta1").html();	
										}
									});
								}

								//para el check
								$(function () {
									$('.button-checkbox').each(function () {

										// Settings
										var $widget = $(this),
											$button = $widget.find('button'),
											$checkbox = $widget.find('input:checkbox'),
											color = $button.data('color'),
											settings = {
												on: {
													icon: 'glyphicon glyphicon-check'
												},
												off: {
													icon: 'glyphicon glyphicon-unchecked'
												}
											};

										// Event Handlers
										$button.on('click', function () {
											$checkbox.prop('checked', !$checkbox.is(':checked'));
											$checkbox.triggerHandler('change');
											updateDisplay();
										});
										$checkbox.on('change', function () {
											updateDisplay();
										});
									});
									$('.button-checkbox1').each(function () {

										// Settings
										var $widget = $(this),
											$button = $widget.find('button'),
											$checkbox = $widget.find('input:checkbox'),
											color = $button.data('color'),
											settings = {
												on: {
													icon: 'glyphicon glyphicon-check'
												},
												off: {
													icon: 'glyphicon glyphicon-unchecked'
												}
											};

										// Event Handlers
										$button.on('click', function () {
											$checkbox.prop('checked', !$checkbox.is(':checked'));
											$checkbox.triggerHandler('change');
											updateDisplay();
										});
										$checkbox.on('change', function () {
											updateDisplay();
										});

									
									});
								});
								
								

								</script>
								<!-- /.modal -->
					<?php
							}
							else
							{
			?>
								<script>
									
								  
								
								 // $(".loader2").show();
								 Swal.fire({
								  title: 'Terminado!',
								  text: 'Error al cargar formulario.',
								  
								  animation: false
								}).then((result) => {
										  if (
											result.value
										  ) {
											console.log('I was closed by the timer');
											location.href ="contabilidad.php?mod=contabilidad&er=1";
										  }
										});
							</script>
			<?php
							}
			?>
            </div>
			
				
            </div>
				
            <!-- /.box-body -->
          </div>
	</div>
</div>

<div class="modal fade" id="modal_cuenta" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLongTitle"></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      	<div id="panel_banco" style=" display: none">
        <div class="row">
          <div class="col-sm-6">
          	<b>Efectiv.</b>
          </div>
          <div class="col-sm-6">
          	<input type="date" name="txt_efectiv" id="txt_efectiv" class="form-control input-sm" value="<?php echo date('Y-m-d');?>">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6">
          	<b> Cheq / Dep</b>
          </div>
          <div class="col-sm-6">
          	<input type="text" name="txt_cheq_dep" id="txt_cheq_dep" class="form-control input-sm">
          </div>
        </div>
        </div>
        <div class="row">
          <div class="col-sm-6"><br>
          	<b>Valores</b>
          </div>
          <div class="col-sm-6">
          	<b>M/N = 1 | M/E=2</b>
          	<input type="text" name="txt_moneda" id="txt_moneda" class="form-control input-sm" onkeyup="restingir('txt_moneda')" value="1">
          </div>
        </div>
        <div class="row">
          <div class="col-sm-6"><br>
          	<b>Debe / Haber</b>
          </div>
          <div class="col-sm-6">
          	<b>Debe = 1 | Haber=2</b>
          	<input type="text" name="txt_tipo" id="txt_tipo" class="form-control input-sm" onkeyup="restingir('txt_tipo')" value="1">
          </div>
        </div>
      </div>
      <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="subcuenta_frame();">Guardar</button>
          <!-- <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">Cerrar</button> -->
        </div>
    </div>
  </div>
</div>}

<div class="modal fade" id="modal_subcuentas" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="titulo_frame">SUB CUENTAS</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- <div class="container-fluid"> -->
          <iframe  id="frame" width="100%" height="400px" marginheight="0" frameborder="0"></iframe>
          
        <!-- </div> -->
        <!-- <iframe src="../vista/contabilidad/FSubCtas.php"></iframe> -->
        
      </div>
      <div class="modal-footer">
          <!-- <button type="button" class="btn btn-primary" onclick="cambia_foco();">Guardar</button> -->
          <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close" onclick="recarar()">Cerrar</button>
        </div>
    </div>
  </div>
</div>



