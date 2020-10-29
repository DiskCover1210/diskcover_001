 </div> 
  </div> 
</section>

	
	<!--<h3 class="box-title">Striped Full Width Table</h3>-->

		<?php
			//llamamos a los parciales para menus
			if (isset($_SESSION['INGRESO']['accion'])) 
			{ 
				//Mayorización
				if ($_SESSION['INGRESO']['accion']=='macom') 
				{
					require_once("contabilidad/macom_m.php");
				}
				//Balance de Comprobacion/Situación/General
				if ($_SESSION['INGRESO']['accion']=='bacsg') 
				{
					//require_once("contabilidad/bacsg_m.php");
				}
				//reporte documentos electronicos
				if ($_SESSION['INGRESO']['accion']=='rde') 
				{
					require_once("rde_m.php");
				}
				//reporte facturacion
				if ($_SESSION['INGRESO']['accion']=='fact') 
				{
					require_once("fact_m.php");
				}
				if ($_SESSION['INGRESO']['accion']=='compro') 
				{
					require_once("contabilidad/compro_m.php");
				}	
				if ($_SESSION['INGRESO']['accion']=='cambioe') 
				{
					require_once("empresa/cambioe_m.php");
				}	
			}	
				?>	
		<!--</div>-->
		
 <!--mensajes popup-->
<?php
	if(isset($_SESSION['INGRESO']['accesoe']) )
	{
		if($_SESSION['INGRESO']['CodigoU']=='')
		{
			?>
				<script>
				Swal.fire({
				  type: 'error',
				  title: 'Oops...',
				  text: 'No existe su usuario a esta empresa!'
				}).then((result) => {
				  if (result.value) {
					location.href="logout.php";
				  } 
				});
			</script>
			<?php
		}
	}
 
	if(isset($_SESSION['INGRESO']['accesoe']) )
	{
		if($_SESSION['INGRESO']['accesoe']=='0')
		{
?>
			<script>
				Swal.fire({
				  type: 'error',
				  title: 'Oops...',
				  text: 'No tiene acceso a esta empresa!'
				}).then((result) => {
				  if (result.value) {
					location.href="panel.php?mos2=e";
				  } 
				});
			</script>
<?php		
		}

	}
?>


  <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <!--<section class="content-header">
      <h1>
        Bienvenido
        <small>a DiskCover</small>
      </h1>
	  <?php
	  if (!isset($_SESSION['INGRESO']['empresa'])) 
	  {
	  ?>
		  <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Seleccione empresa</a></li>
		  </ol>
	  <?php
	  }
	  else
		  {
			  ?>
			  <ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> <?php echo $_SESSION['INGRESO']['noempr']; ?></a></li>
				<li><a href="panel.php?mos2=e">Seleccione empresa</a></li>
			  </ol>
			  
		<?php
		  }
		  ?>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> prueba</a></li>
        <li><a href="#">prueba</a></li>
        <li class="active">prueba</li>
      </ol>
    </section>-->

    <!-- Main content -->

      <!-- Default box -->
        <!--<div class="box-header with-border">
		 <?php
		  if (!isset($_SESSION['INGRESO']['empresa'])) 
		  {
		  ?>
			  <h3 class="box-title">Selecione su empresa</h3>
		<?php
		  }
		  else
		  {
			  ?>
			  <h3 class="box-title"><?php echo $_SESSION['INGRESO']['noempr']; ?></h3>
		<?php
		  }
		  ?>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip"
                    title="Collapse">
              <i class="fa fa-minus"></i></button>
            <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="Remove">
              <i class="fa fa-times"></i></button>
          </div>
        </div>-->
			
			
	
	<script>
		//$(document).on('change', '#sempresa', function(event) {
			 //$('#servicioSelecionado').val($("#sempresa option:selected").text());
			// alert($("#sempresa option:selected").text());
		//});
		$(document).ready(function () {

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
	
		function getParameterByName(name) {
			name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
			var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
			results = regex.exec(location.search);
			return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
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
			else
			{
				
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
<?php
//si es un sola empresa que redireccione directo
include('footer.php');
?>	
   