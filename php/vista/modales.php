<head>
  <meta charset="utf-8">
<meta charset="ISO-8859-1">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta http-equiv="Pragma" content="no-cache"> <meta http-equiv="Expires" content="-1">
   
<!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script> -->
 
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../lib/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../lib/bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../lib/bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../lib/dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="../../lib/bower_components/jquery-ui/themes/base/jquery-ui.css">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../lib/dist/css/skins/_all-skins.min.css">
  
  
  <!-- daterange picker 
  <link rel="stylesheet" href="../../lib/bower_components/bootstrap-daterangepicker/daterangepicker.css">
  <!-- bootstrap datepicker 
  <link rel="stylesheet" href="../../lib/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css">
  <!-- iCheck for checkboxes and radio inputs 
  <link rel="stylesheet" href="../../lib/plugins/iCheck/all.css">
  <!-- Bootstrap Color Picker 
  <link rel="stylesheet" href="../../bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">
  <!-- Bootstrap time Picker 
  <link rel="stylesheet" href="../../plugins/timepicker/bootstrap-timepicker.min.css">
  <!-- Select2 
  <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">
-->
  
  
  
  <script src="../../lib/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
  <!-- jQuery 3 -->
<script src="../../lib/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="../../lib/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Select2 -->
<script src="../../lib/bower_components/select2/dist/js/select2.full.min.js"></script>
<!-- InputMask -->
<script src="../../lib/plugins/input-mask/jquery.inputmask.js"></script>
<script src="../../lib/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="../../lib/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<!-- date-range-picker -->
<script src="../../lib/bower_components/moment/min/moment.min.js"></script>
<script src="../../lib/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<script src="../../lib/bower_components/jquery-ui/jquery-ui.js"></script>

<!-- bootstrap datepicker -->
<script src="../../lib/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- bootstrap color picker -->
<script src="../../lib/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<!-- bootstrap time picker -->
<script src="../../lib/plugins/timepicker/bootstrap-timepicker.min.js"></script>
<!-- SlimScroll -->
<script src="../../lib/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- iCheck 1.0.1 -->
<script src="../../lib/plugins/iCheck/icheck.min.js"></script>
<!-- FastClick -->
<script src="../../lib/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="../../lib/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../lib/dist/js/demo.js"></script>


<script src="../../lib/dist/js/select2.min.js"></script>
  <link rel="stylesheet" href="../../lib/dist/css/select2.min.css">
  
 <link rel="stylesheet" href="../../lib/dist/css/sweetalert.css">
  <script src="../../lib/dist/js/sweetalert-dev.js"></script>
  
  <script src="../../lib/dist/js/sweetalert2.min.js"></script>
  <script type="text/javascript" src="../../lib/dist/js/typeahead.js"></script>
  <link rel="stylesheet" href="../../lib/dist/css/sweetalert2.min.css">
  <link rel="shortcut icon" href="../../img/jpg/logo.jpg">

  
  <style type="text/css">
    .fond{position:absolute;padding-top:85px;top:0;left:0; right:0;bottom:0;
 background-color:#00506b;}

.style_prevu_kit
{
    display:inline-block;
    border:0;
    /*width:110px;*/
    /*height:80px;*/
    position: relative;
    -webkit-transition: all 200ms ease-in;
    -webkit-transform: scale(1); 
    -ms-transition: all 200ms ease-in;
    -ms-transform: scale(1); 
    -moz-transition: all 200ms ease-in;
    -moz-transform: scale(1);
    transition: all 200ms ease-in;
    transform: scale(1);   

}
.style_prevu_kit:hover
{
    /*box-shadow: 0px 0px 150px #000000;*/
    z-index: 2;
    -webkit-transition: all 200ms ease-in;
    -webkit-transform: scale(1.2);
    -ms-transition: all 200ms ease-in;
    -ms-transform: scale(1.2);   
    -moz-transition: all 200ms ease-in;
    -moz-transform: scale(1);
    transition: all 200ms ease-in;
    transform: scale(1.2);
}
</style>
  <style>
.courier {
	font-family: "courier new";
	font-size: 0.8em;
	position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top: 100%;
    left: 0;
    right: 0;
}
/*the container must be positioned relative:*/
.autocomplete {
  position: relative;
  display: inline-block;
}


.autocomplete-items {
  position: absolute;
  border: 1px solid #d4d4d4;
  border-bottom: none;
  border-top: none;
  z-index: 99;
  /*position the autocomplete items to be the same width as the container:*/
  top: 100%;
  left: 0;
  right: 0;
}

.autocomplete-items div {
  padding: 10px;
  h pointer;
  background-color: #fff; 
  border-bottom: 1px solid #d4d4d4; 
}

/*when hovering an item:*/
.autocomplete-items div:hover {
  background-color: #e9e9e9; 
}

/*when navigating through the items using the arrow keys:*/
.autocomplete-active {
  background-color: DodgerBlue !important; 
  color: #ffffff; 
}
</style>
<!--    <script src="../../lib/dist/js/sweetalert2.js"></script>
  <link rel="stylesheet" href="../../lib/dist/css/sweetalert2.css"> -->
	<script>
		function sleep(ms) {
		  return new Promise(resolve => setTimeout(resolve, ms));
		}
		async function empezar() {
			$('#cargar').css('cursor', 'wait');
			await sleep(2000);
			//$('#cargar').css('cursor', 'default');
		}
		async function parar() {
			//$('#cargar').css('cursor', 'wait');
			//await sleep(2000);
			$('#cargar').css('cursor', 'default');
		}
			function cambiar_1(){
			$('#myModal_espera').modal('show');
			//var select = document.getElementById(id), //El <select>
			var value =  $('#sempresa').val(); //El valor seleccionado
			//partimos cadenas
			separador = "-"; // un espacio en blanco
			limite    = 2;
			arregloDeSubCadenas = value.split(separador, limite);
			text =$('#sempresa option:selected').html(); //El texto de la opción seleccionada
			//console.log(text);
			// alert(value);
			// alert(text);
			//redireccionamos
			window.location="panel.php?mos="+value+"&mos1="+text+"&mos3="+arregloDeSubCadenas[1]+"";
		}
			function addCommas(nStr) {
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}
		
		function buscar(idMensaje)
		{
			//caso comprobantes procesados
			if(idMensaje=='comproba')
			{
				var select = document.getElementById('mes'); //El <select>
				value = select.value;
				var select = document.getElementById('tipoc'); //El <select>
				value1 = select.value;
				//alert(value);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, MesNo: value, TP: value1 }, function(data){
						$('div.'+idMensaje).html(data); 
						//alert('entrooo '+idMensaje);
					});
			}
			//caso buscar
			if(idMensaje=='comp')
			{
				var select = document.getElementById(idMensaje); //El <select>
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1 }, function(data){
						//$('div.pdfcom').load(data);
						$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						//alert('entrooo '+idMensaje+" ajax/TEMP/'+value1+'.pdf");
					});
			}
			//caso entidad-ciudad
			if(idMensaje=='ciudad')
			{
				//alert(idMensaje+' entro ');
				//solo para el caso de gestion empresa
				var select = document.getElementById('entidad'); //El <select>
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			//caso entidad-empresa-ciudad
			if(idMensaje=='entidad')
			{
				var select = document.getElementById('ciudad'); //El <select>
				value2 = select.value;
				var select = document.getElementById(idMensaje); //El <select>
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1, ciu: value2 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			//caso entidad-empresa
			if(idMensaje=='entidad_u')
			{
				var select = document.getElementById(idMensaje); //El <select>
				var ch = '0';
				var isChecked = document.getElementById('entidadch').checked;
				if(isChecked){
					ch = '1';
				}
				value1 = select.value;
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1, ch: ch }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			//caso entidad-empresa
			if(idMensaje=='usuario')
			{
				var select = document.getElementById(idMensaje); //El <select>
				value1 = select.value;
				//var value2 = document.getElementById('item1').value; //
				//alert(value2);
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			if(idMensaje=='buscarusu')
			{
				var value1 = document.getElementById('entidad_u').value;
				var ch1 = '0';
				var isChecked = document.getElementById('entidadch').checked;
				if(isChecked)
				{
					ch1 = '1';
				}
				var value3 = document.getElementById('usuario').value;
				var ch2 = '0';
				var isChecked = document.getElementById('usuarioch').checked;
				if(isChecked)
				{
					ch2 = '1';
				}
				var ch3 = '0';
				var isChecked = document.getElementById('empresach').checked;
				if(isChecked)
				{
					ch3 = '1';
				}
				var value7 = document.getElementById('empresa').value;
				var value5 = document.getElementById('FechaI').value;
				var value6 = document.getElementById('FechaF').value;
				//alert(value1+' '+value3+' '+value5+' '+value6+' '+value7+' '+ch1+' '+ch2+' '+ch3);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, value1: value1, value3: value3, value5: value5, value6: value6,
					value7: value7, ch1: ch1, ch2: ch2, ch3: ch3 }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			//caso empresa
			if(idMensaje=='empresa')
			{
				var select = document.getElementById(idMensaje); //El <select>
				var sms = $('#Mensaje').val();
				value1 = select.value;
				//alert(value1);
				$.post('ajax/vista_ajax.php'
					, {ajax_page: idMensaje, com: value1,sms:sms }, function(data){
						//alert('#'+idMensaje+'1');
						//$('div.pdfcom').load(data);
						//$('#pdfcom').html('<iframe style="width:100%; height:50vw;" src="ajax/TEMP/'+value1+'.pdf" frameborder="0" allowfullscreen></iframe>'); 
						$('#'+idMensaje+'1').html(data); 
					});
			}
			
		}
	</script>
 
		<script>

		$(document).ready(function () {
			console.log(navigator);

			var es_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
		if(es_chrome)
		{
			if(document.URL.indexOf("#")==-1)
            {
               url = document.URL+"#"; 
               location = "#"; 
               location.reload(true); 
           }			    
        }
			$('.navbar .dropdown-item.dropdown').on('click', function (e) {
				var $el = $(this).children('.dropdown-toggle');
				if ($el.length > 0 && $(e.target).hasClass('dropdown-toggle')) {
					var $parent = $el.offsetParent(".dropdown-menu");
					$(this).parent("li").toggleClass('open');
			
					if (!$parent.parent().hasClass('navbar-nav')) {
						if ($parent.hasClass('show')) {
							$parent.removeClass('show');
							$el.next().removeClass('show');
							$el.next().css({"top": -999, "left": -999});
						} else {
							$parent.parent().find('.show').removeClass('show');
							$parent.addClass('show');
							$el.next().addClass('show');
							$el.next().css({"top": $el[0].offsetTop, "left": $parent.outerWidth() - 4});
						}
						e.preventDefault();
						e.stopPropagation();
					}
					return;
				}
			});

			$('.navbar .dropdown').on('hidden.bs.dropdown', function () {
				$(this).find('li.dropdown').removeClass('show open');
				$(this).find('ul.dropdown-menu').removeClass('show open');
			});

		});
		function cambiar(id){
			var select = document.getElementById(id), //El <select>
			value = select.value; //El valor seleccionado
			//partimos cadenas
			separador = "-"; // un espacio en blanco
			limite    = 2;
			arregloDeSubCadenas = value.split(separador, limite);
			text = select.options[select.selectedIndex].innerText; //El texto de la opción seleccionada
			//alert(value);
			//redireccionamos
			window.location="panel.php?mos="+value+"&mos1="+text+"&mos3="+arregloDeSubCadenas[1]+"";
		}
		String.prototype.ucwords = function() {
			str = this.toLowerCase();
			return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
				function($1){
					return $1.toUpperCase();
					});
		}

		function descargar_archivos(url,archivo)
		{
            var link = document.createElement("a");
            link.download = archivo;
            link.href = url;
            link.click();
        }

		function validar_year_mayor(nombre)
		{

			var fecha = $('#'+nombre+'').val();
			var partes = fecha.split('-');
			console.log(partes);
			if(partes[0].length > 4 || partes[0] > 2050)
			{
				$('#'+nombre+'').val('2050-'+partes[1]+'-'+partes[2]);
			}
			

		}
		function validar_year_menor(nombre)
		{

			var fecha = $('#'+nombre+'').val();
			var partes = fecha.split('-');
			console.log(partes);
			if(partes[0] < 2000)
			{
				alert('Año seleccionado menor a 1999');
				$('#'+nombre+'').val('1999-'+partes[1]+'-'+partes[2]);
			}
			

		}
	
	
		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
		}

		function num_caracteres(campo,num)
{
	var val = $('#'+campo).val();
	var cant = val.length;
	console.log(cant+'-'+num);

	if(cant>num)
	{
		$('#'+campo).val(val.substr(0,num));
		return false;
	}

}

		
		function soloNumeros(e)
		{
			var key = window.Event ? e.which : e.keyCode
			return (key >= 48 && key <= 57)
		}
		function soloNumeros12(e)
		{
			$("#codigo1").hide();
			var key = window.Event ? e.which : e.keyCode
			//alert(key);
			if(key >= 49 && key <= 50)
			{
				 $(this).next().focus();
				 return (key >= 49 && key <= 50);
			}
			
		}
		function soloNumerosDecimales(e)
		{
			var key = window.Event ? e.which : e.keyCode
			return (key <= 13 || (key >= 48 && key <= 57) || key==46)
		}
		function  cerrar(id)
		{
			if(id=='codigo1')
			{
				$( "#moneda" ).focus(function() {
					var bene = document.getElementById('cuenta').value;
					var cod = document.getElementById('codigo').value;
					if('Seleccionar'==bene || bene=='' || bene=='no existe registro' || bene=='undefined' || cod=='0' || cod=='')
					{
						Swal.fire({
						  type: 'error',
						  title: 'Oops...',
						  text: 'debe agregar cuenta!'
						});
						$("#cuenta").focus();
					}
					//
				});
				$("#"+id).hide();
			}
			else
			{
				$("#"+id).hide();
			}
		}
</script>	
	<script>
	function readCookie(name) {

	  var nameEQ = name + "="; 
	  var ca = document.cookie.split(';');

	  for(var i=0;i < ca.length;i++) {

		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) {
		  return decodeURIComponent( c.substring(nameEQ.length,c.length) );
		}

	  }

	  return null;

	}
	function autocomplete(inp, arr, ina='') {

	    /*the autocomplete function takes two arguments,
	    the text field element and an array of possible autocompleted values:*/
	    var currentFocus;
	    /*execute a function when someone writes in the text field:*/
	    if (ina == '') 
		{
			inp.addEventListener("input", function(e) {
				  var a, b, i, val = this.value;
				  /*close any already open lists of autocompleted values*/
				  closeAllLists();
				  if (!val) { return false;}
				  currentFocus = -1;
				  /*create a DIV element that will contain the items (values):*/
				  a = document.createElement("DIV");
				  a.setAttribute("id", this.id + "autocomplete-list");
				  a.setAttribute("class", "autocomplete-items");
				  /*append the DIV element as a child of the autocomplete container:*/
				  this.parentNode.appendChild(a);
				  /*for each item in the array...*/
				  //alert(arr.length);
				  separador = "-"; // un espacio en blanco
				  limite    = 2;
				for (i = 0; i < arr.length; i++) {
					/*check if the item starts with the same letters as the text field value:*/
					//if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
					  /*create a DIV element for each matching element:*/
					  b = document.createElement("DIV");
					  b.className = "form-control input-sm";
					  //class='form-control input-sm'
					  /*make the matching letters bold:*/
					  b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
					  b.innerHTML += arr[i].substr(val.length);
					  /*insert a input field that will hold the current array item's value:*/
					 //alert(arr[i]);
						arregloDeSubCadenas = arr[i].split(separador, limite);
						if(arregloDeSubCadenas.length>2)
						{
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[2] + "' id='V_1'>";
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[1] + "' id='V_0'>";
						}
						if(arregloDeSubCadenas.length==2)
						{
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[0] + "' id='V_1'>";
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[1] + "' id='V_0'>";
						}
						//  b.innerHTML += "<input type='hidden' value='" + arr[i] + "'  >";
						/*execute a function when someone clicks on the item value (DIV element):*/
						b.addEventListener("click", function(e) {
							
							//selec(this.getElementsByTagName("input")[1].value,document.getElementById('V_1').value);
							selec(this.getElementsByTagName("input")[1].value,this.getElementsByTagName("input")[0].value);
							/*insert the value for the autocomplete text field:*/
							inp.value = this.getElementsByTagName("input")[0].value;
							/*close the list of autocompleted values,
							(or any other open lists of autocompleted values:*/
							closeAllLists();
						});
					  a.appendChild(b);
					//}
				}
			});
		}
		else
		{
			//alert(" pppp ");
			ina1=document.getElementById(ina);
			inp.addEventListener("input", function(e) {
				  var a, b, i, val = this.value;
				  /*close any already open lists of autocompleted values*/
				  closeAllLists();
				  if (!val) { return false;}
				  currentFocus = -1;
				  /*create a DIV element that will contain the items (values):*/
				  a = document.createElement("DIV");
				  a.setAttribute("id", this.id + "autocomplete-list");
				  a.setAttribute("class", "autocomplete-items");
				  /*append the DIV element as a child of the autocomplete container:*/
				 ina1.parentNode.appendChild(a);
				  /*for each item in the array...*/
				  //alert(arr.length);
				  separador = "-"; // un espacio en blanco
				  limite    = 2;
				for (i = 0; i < arr.length; i++) {
					/*check if the item starts with the same letters as the text field value:*/
					//if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
					  /*create a DIV element for each matching element:*/
					  b = document.createElement("DIV");
					  b.className = "form-control input-sm";
					  //class='form-control input-sm'
					  /*make the matching letters bold:*/
					  b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
					  b.innerHTML += arr[i].substr(val.length);
					  /*insert a input field that will hold the current array item's value:*/
					 
						arregloDeSubCadenas = arr[i].split(separador, limite);
						if(arregloDeSubCadenas.length>2)
						{
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[2] + "' id='V_1'>";
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[1] + "' >";
						}
						if(arregloDeSubCadenas.length==2)
						{
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[0] + "' id='V_1'>";
							b.innerHTML += "<input type='hidden' value='" + arregloDeSubCadenas[1] + "' >";
						}
						//  b.innerHTML += "<input type='hidden' value='" + arr[i] + "'  >";
						/*execute a function when someone clicks on the item value (DIV element):*/
						b.addEventListener("click", function(e) {
							
							selec(this.getElementsByTagName("input")[1].value,document.getElementById('V_1').value);
							
							//selec(this.getElementsByTagName("input")[1].value);
							/*insert the value for the autocomplete text field:*/
							inp.value = this.getElementsByTagName("input")[0].value;
							/*close the list of autocompleted values,
							(or any other open lists of autocompleted values:*/
							closeAllLists();
						});
					  a.appendChild(b);
					//}
				}
			});
		}
		    
	  /*execute a function presses a key on the keyboard:*/
	  inp.addEventListener("keydown", function(e) {
		  var x = document.getElementById(this.id + "autocomplete-list");
		  if (x) x = x.getElementsByTagName("div");
		  if (e.keyCode == 40) {
			/*If the arrow DOWN key is pressed,
			increase the currentFocus variable:*/
			currentFocus++;
			/*and and make the current item more visible:*/
			addActive(x);
		  } else if (e.keyCode == 38) { //up
			/*If the arrow UP key is pressed,
			decrease the currentFocus variable:*/
			currentFocus--;
			/*and and make the current item more visible:*/
			addActive(x);
		  } else if (e.keyCode == 13) {
			/*If the ENTER key is pressed, prevent the form from being submitted,*/
			e.preventDefault();
			if (currentFocus > -1) {
			  /*and simulate a click on the "active" item:*/
			  if (x) x[currentFocus].click();
			}
		  }
	  });
	  function addActive(x) {
		/*a function to classify an item as "active":*/
		if (!x) return false;
		/*start by removing the "active" class on all items:*/
		removeActive(x);
		if (currentFocus >= x.length) currentFocus = 0;
		if (currentFocus < 0) currentFocus = (x.length - 1);
		/*add class "autocomplete-active":*/
		x[currentFocus].classList.add("autocomplete-active");
	  }
	  function removeActive(x) {
		/*a function to remove the "active" class from all autocomplete items:*/
		for (var i = 0; i < x.length; i++) {
		  x[i].classList.remove("autocomplete-active");
		}
	  }
	  function closeAllLists(elmnt) {
		/*close all autocomplete lists in the document,
		except the one passed as an argument:*/
		var x = document.getElementsByClassName("autocomplete-items");
		for (var i = 0; i < x.length; i++) {
		  if (elmnt != x[i] && elmnt != inp) {
			x[i].parentNode.removeChild(x[i]);
		  }
		}
	  }
	  /*execute a function when someone clicks in the document:*/
	  document.addEventListener("click", function (e) {
		  closeAllLists(e.target);
	  });
	}

	function validar_cuenta(campo)
	{
		var id = campo.id;
		let cap = $('#'+id).val();
		let cuentaini = cap.replace(/[.]/gi,'');
	//	var cuentafin = $('#txt_CtaF').val();
		var formato = "C.C.CC.CC.CC.CCC";
		let parte =formato.split('.');
		var nuevo =  new Array(); 
		let cadnew ='';
		for (var i = 0 ; i < parte.length; i++) {

			if(cuentaini.length != '')
			{
				var b = parte[i].length;
				var c = cuentaini.substr(0,b);
				if(c.length==b)
				{
					nuevo[i] = c;
					cuentaini = cuentaini.substr(b);
				}else
				{   
				  if(c != 0){  
					//for (var ii =0; ii<b; ii++) {
						var n = c;
						//if(n.length==b)
						//{
						   //if(n !='00')
						  // {
							nuevo[i] =n;
				            cuentaini = cuentaini.substr(b);
				         //  }
				         //break;
						  
						//}else
						//{
						//	c = n;
						//}
						
					//}
				  }else
				  {
				  	nuevo[i] =c;
				    cuentaini = cuentaini.substr(b);
				  }
				}
			}
		}
		var m ='';
		nuevo.forEach(function(item,index){
			m+=item+'.';
		})
		//console.log(m);
		$('#'+id).val(m);


	}
	
	</script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<script type="text/javascript">
	 $( document ).ready(function() {
	$('body').css('padding-top','0px');
});
</script>
<?php

if(isset($_GET['FSubCtas']))
{
	require_once('contabilidad/FSubCtas.php');
}
if(isset($_GET['FCompras']))
{
	require_once('contabilidad/FCompras.php');
}

?>

<div class="modal fade" id="myModal_espera" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body text-center">
        	<img src="../../img/gif/loader4.1.gif" width="80%"> 	
        </div>
      </div>
    </div>
  </div>				