<?php 
require_once(dirname(__DIR__)."/modelo/modelomesa.php");
require_once(dirname(__DIR__,3)."/funciones/funciones.php");
/**
 * 
 */
if(isset($_REQUEST['pedido']))
	{
		$mesa= new MesaCon();
		$id = $_GET['id'];
		$nom = $_GET['nom'];
		$item = $_POST['item'];
		$periodo = $_POST['pediodo'];
		$query = $_POST['buscar'];
		//echo json_encode($mesa->lista_productos());
		echo json_encode($mesa->modal_pedido($id,$nom,$query));
	}

if(isset($_REQUEST['agregar']))
{
	$mesa= new MesaCon();
   echo json_encode($mesa->agregar_producto());
}

if(isset($_REQUEST['eliminar']))
{
	$mesa = new MesaCon();
	echo json_encode($mesa->eliminar_producto());

}
if(isset($_REQUEST['eliminarabo']))
{
	$mesa = new MesaCon();
	echo $mesa->eliminar_abono();

}
if(isset($_REQUEST['factura']))
{
	$mesa = new MesaCon();
	$me= $_POST['me'];
	$nom= $_POST['nom'];
	echo json_encode($mesa->modal_facturas($me,$nom));
}
if(isset($_REQUEST['abono']))
{
	$mesa = new MesaCon();
	$mesa->agregar_abono();
	$me= $_POST['me'];
	$nom= $_POST['nom'];
	echo json_encode($mesa->modal_facturas($me,$nom));
}
if(isset($_REQUEST['facturar']))
{
	$mesa = new MesaCon();
	$mesa->agregar_factura();
	$me= $_POST['me'];
	$nom= $_POST['nom'];
	echo json_encode($mesa->modal_facturas($me,$nom));
}
if(isset($_REQUEST['buscarcli']))
{   
	$mesa = new MesaCon();
	$query = $_GET['buscarcli'];
	echo json_encode($mesa->buscar_cliente($query));
}
if(isset($_REQUEST['liberar']))
{
	$mesa = new MesaCon();
	$me = $_GET['me'];
	echo json_encode($mesa->liberar_mesa($me));
}

if(isset($_REQUEST['pdf']))
{
	$mesa = new MesaCon();
	$me= $_GET['me'];
	$param=array();
	$param[0]['nombrec']=$_POST['nombrec'];
	//echo $param[0]['nombrec'].' -- ';
	$param[0]['ruc']=$_POST['ruc'];
	$param[0]['mesa']=$_POST['me'];
	$param[0]['PFA']='PF';
	$me = $_GET['me'];
	$mesa->pdf_imprimir($me,$param);
}

class MesaCon 
{
	private $modelo;
	PRIVATE $pdf;

	function __construct() 
	{
		$this->modelo = new MesaModel();
		$this->pdf = new PDF();
	}

	function cargar_todas_mesas()
     {
	$todos_mesa = $this->modelo->cargar_mesas();
	foreach ($todos_mesa as $key => $value) 
	{
		if($value['Estado']==0 OR $value['Estado']==null)
			{
				echo $html ="
				<div class='col-lg-3 col-xs-6'>
					<!-- small box -->
					<div class='small-box bg-green'>
						<div class='inner' onclick='agregar_n(\"".$value['Codigo_Inv']."\",\"".$value['Producto']."\");'>
							<h3 style='font-size: 20px'>Disponible<sup style='font-size: 17px'></sup></h3>
							<p>". $value['Producto']."</p>
						</div>
						<div class='icon'>
							<i class='ion ion-stats-bars'></i>
						</div>
						<a onclick='agregar_n(\"".$value['Codigo_Inv']."\",\"".$value['Producto']."\");' class='small-box-footer'>ver <i class='fa fa-arrow-circle-right'></i></a>
					</div>
				</div>
				";
			}
			else
			{
				echo $html = "<div class='col-lg-3 col-xs-6'>
					<!-- small box -->
					<div class='small-box bg-yellow'>
						<div class='inner' onclick='agregar_n(\"".$value['Codigo_Inv']."\",\"".$value['Producto']."\");'>
							<!--<h3 style='font-size: 20px'>Disponible<sup style='font-size: 17px'></sup></h3>-->
							<h3 style='font-size: 20px'>Ocupada <sup style='font-size: 17px'>".$this->modelo->productos_entregar($value['Codigo_Inv'])."</sup></h3>
							<p>".$value['Producto']."</p>
							
						</div>
						<div class='icon'>
							<i class='ion ion-stats-bars'></i>
						</div>
						<a onclick='agregar_n(\"".$value['Codigo_Inv']."\",\"".$value['Producto']."\");' class='small-box-footer'>ver <i class='fa fa-arrow-circle-right'></i></a>
					</div>
				</div>";
			}
	}
     }

    function modal_pedido($id,$nom,$buscar)
    {
      $articulos = $this->lista_productos($buscar);
	  //determinar si hay pedidos
	  $linea = $this->modelo->pedido_realizado($id);

	$i =0;
	foreach ($linea as $key => $value) {
		$i++;
	}
	if($i>0)
	{
		$bot=' <div class="col-sm-6 text-right">
      		        <button type="button" class="btn btn-default btn-sm" onclick="prefact(\''.$id.'\',\''.$nom.'\')"><span class="glyphicon glyphicon-floppy-disk"></span> Factura</button>
      		         <button type="button" class="btn btn-default btn-sm" onclick="prefact22(\''.$id.'\',\''.$nom.'\')"><i class="fa fa-file"></i> Pre-factura</button>
      		        </div>';
	}
	else
	{
		$bot='';
	}
      //$this->pedido_mesa($id,$nom);
	  echo $html = '
	<form action="#" class="credit-card-div" id="formu1">
	<div class="row">
      	   <div class="col-sm-6">   
      		  <div class="panel panel-info">

      		  	<input type="hidden" id="mesa" name="mesa"  value='.$id.' />
      		     <div class="panel-heading">
      		      <div class="row">
      		        <div class="col-sm-6">
      		         <button type="button" class="btn btn-default btn-sm" onclick="liberar(\''.$id.'\')"><i class="fa fa-paint-brush"></i> Liberar</button>
      		        </div>
      		        '.$bot.'
      		      </div>    		    
      		     
      		     
      		    
      		     </div>
      		        <table class="table table-responsive">
      		           <thead>
      		           	 <th>Cant</th>
      		           	 <th>Producto</th>
      		           	 <th>Precio</th>
      			         <th>Status</th>
      			         <th>Total</th>
      		           </thead>
      		           <tbody>
      			         '.$this->pedido_mesa($id,$nom).'
      		           </tbody>
      		        </table>
      		        
      		   </div>
      		</div>
      		<div class="col-sm-6">
      		   <div class="panel panel-info">
      		     <div class="panel-heading">Articulos</div>
      			    <div class="panel-body">
      			      <div class="row">
       		             <div class="col-sm-6">
       			             <div class="input-group">
				                <input type="text" class="form-control" placeholder="Buscar Producto" id="buscar" name="buscar">
				                <span class="input-group-btn">
				                  <button type="button" class="btn btn-info btn-flat"onclick="agregar_n(\''.$id.'\',\''.$nom.'\')">
					                <i class="ion ion-search"></i>
					              </button>
					             </span>		
				             </div>       			
       		             </div>
       		             <div class="col-sm-3">
       		             	<button type="button" class="btn btn-default btn-sm" onclick="agregar_(\''.$id.'\',\''.$nom.'\')">
					           <i class="fa fa-plus"></i> Add Producto
					        </button>					
       		             </div>       		                   		
       	              </div>
       	              <div class="row">

       	              <div class="col-sm-12">
       	                 <table class="table table-responsive">
       	                   <thead style="display:block;">
       	                     <th width="30px"></th>
       	                     <th width="200px">Producto</th>
       	                     <th width="30px">Precio</th>
       	                     <th width="30px">Estatus</th>
       	                     </thead>
       	                     <tbody  style="display:block; height:400px; overflow: scroll;">
       	                     '.$articulos.'
       	                     </tbody>
	                     </table>
       	                </div>
       	              </div>
      			    </div>      			       	
      		   </div>
      		</div>
      	</div>
      	</form>';		
}

function lista_productos($buscar)
{
	$productos = $this->modelo->list_product($buscar);
	$html = '';
	foreach ($productos as $key => $value) {

		$html.= '
		<tr>
				<td>
				  <input type="checkbox" name="selec_p[]" value=\''.$value['Codigo_Inv'].'\' onclick="selec_p(\''.$value['Codigo_Inv'].'\',\''.$key.'\')">
				</td>
				<td width="300px">
				  '.$value["Producto"].'
				  <br>
				  <div class="row">
				  <div class="col-sm-6">
				  <label id="lcanti_'.$value['Codigo_Inv'].'" style="display:none;">Cantidad</label>
					<input type="text" class="xs" id="canti_'.$value['Codigo_Inv'].'" name="canti_'.$value['Codigo_Inv'].'"	placeholder="Cant" value="1" maxlength="30"  size="5" style="display:none;" onkeyup="c_total(\''.$value["Codigo_Inv"].'\',\''.$value["PVP"].'\',\''.$key.'\')">
				  </div>
				  <div class="col-sm-6">
				  <label id="lobs_'.$value['Codigo_Inv'].'" style="display:none;">Observacion</label>
					<input type="text" class="xs" id="obs_'.$value['Codigo_Inv'].'"	name="obs_'.$value['Codigo_Inv'].'"	placeholder="Observacion" value="." maxlength="50" size="15" style="display:none;">
				  </div>
				  </div>
				</td>
				<td width="50px">
				 $ '.$value['PVP'].'
				 </td>
				<td width="30px">
				 <small class="label label-success"><i class="fa fa-clock-o"></i> Disponible</small>
				</td>
				
		</tr>';
	}

  return $html;
}

function pedido_mesa($me,$nom)
{
	$linea = $this->modelo->pedido_realizado($me);
	$html = '';
	$total =0;
	$iva=0;
	foreach ($linea as $key => $value) {
		$total = $total+ ($value['PRECIO']*$value['CANT']);
		$iva=$iva+$value['Total_IVA'];
		$html.='
		<tr>
		 <td>'.intval($value['CANT']).'</td>
		 <td>'.$value['PRODUCTO'].'</td>
		 <td>'.$value['PRECIO'].'</td>
		 <td width="30px">';
		 if($value['Estado']=='R')
		 	{
		 		$html.='<small class="label label-danger"><i class="fa fa-clock-o"></i>Pendiente</small>';
		 	}
		 	else if($value['Estado']=='A')
		 	{
		 		$html.='<a onclick="entregar(\''.$value["A_No"].'\',\''.$me.'\',\''.$nom.'\',\''.$value['PRODUCTO'].'\',\''.$value['CANT'].'\');">
		 				<small class="label label-warning"><i class="fa fa-clock-o"></i> Por entregar</small>
				    	</a>';
			}
			else if($value['Estado']=='V')
			{
				$html.='<small class="label label-success"><i class="fa fa-clock-o"></i> Despachado</small>';

			}
		 $html.='
		 </td>
		 <td>'.$value['PRECIO']* $value['CANT'].'</td>
		 <td>';
		 if($value['Estado']!='V')
		 	{
		 		$html.='<a onclick="eliminarpro(\''.$me.'\',\''.$nom.'\',\''.$value['CODIGO'].'\',\''.$value['CANT'].'\',\''.$value['A_No'].'\');" Tittle="Eliminar">
		 				<i class="fa fa-trash-o"></i></a>';
		 	}

		 $html.='</td>
		</tr>';
	}
	$html.='<tr><td colspan="6" class="text-right" style="color:red">SUB TOTAL: $'.$total.'</td></tr>'.
		'<tr><td colspan="6" class="text-right" style="color:red">IVA: $'.$iva.'</td></tr>'.
		'<tr><td colspan="6" class="text-right" style="color:red">TOTAL: $'.($total+$iva).'</td></tr>'.
		'<script>
			var total_abono=document.getElementById(\'total_abono\').value;
			var devo=total_abono-'.($total+$iva).';
			$( "#total_abono1" ).html(\'<b>TOTAL ABONO: \'+total_abono+\'</b>\');
			$( "#operacion" ).html(\'<input type="hidden" id="total_total_" name="total_total_" value="'.($total+$iva).'"><b>TOTAL: $'.($total+$iva).'</b>\');
			$( "#devolucion" ).html(\'<b>DEVALUCION: $\'+devo+\'</b>\');
		</script>';
	return $html;

}

function agregar_producto()
{
	$datos = $_POST['parametros'];
	$resp = $this->modelo->agregar_a_pedido($datos);
	if($resp==1)
	{
		return 1;
	}
}
function eliminar_abono()
{
	$datos = $_POST['parametros'];
	$resp = $this->modelo->eliminar_abono($datos);
    if($resp == 1)
    {
		echo "<script type='text/javascript'>
				Swal.fire({
					//position: 'top-end',
					type: 'success',
					title: 'abono eliminado con exito!',
					showConfirmButton: true
					//timer: 2500
				});
			</script>";
    	return 1;
    }else
    {
		echo "<script type='text/javascript'>
				Swal.fire({
					type: 'error',
					title: 'Oops...',
					text: 'No se pudo eliminar abono!'
				});
			</script>";
    	return 0;
    }
}
function eliminar_producto()
{
	$datos = $_POST['parametros'];
	$resp = $this->modelo->eliminar_de_pedido($datos);
    if($resp == 1)
    {
    	return 1;
    }else
    {
    	return -1;
    }
}
function agregar_factura()
{
	$resp = $this->modelo->agregar_factura($_POST);
	if($resp==1)
	{
		echo "<script type='text/javascript'>
				Swal.fire({
					//position: 'top-end',
					type: 'success',
					title: 'abono agregado con exito!',
					showConfirmButton: true
					//timer: 2500
				});
			</script>";
	}
}
function agregar_abono()
{
	//$datos = $_POST['parametros'];
	//$datos= $_POST['me'];
	//$datos= $_POST['nom'];
	$resp = $this->modelo->agregar_abono($_POST);
	if($resp==1)
	{
		echo "<script type='text/javascript'>
				Swal.fire({
					//position: 'top-end',
					type: 'success',
					title: 'abono agregado con exito!',
					showConfirmButton: true
					//timer: 2500
				});
			</script>";
	}
}
function mostrar_abono($me)
{
	$resp = $this->modelo->mostrar_abono($me);
	return $resp;
}
function  modal_facturas($me,$nom)
{
	
	if(isset($_POST['nombrec']) and isset( $_POST['ruc']))
	{
		$ser = $this->modelo->factura_serie();
		$numero = $this->modelo->factura_numero($ser);
		$abono=0;
	?>
		<input type="hidden" id='nombrec' name='nombrec' value='<?php echo $_POST['nombrec'];?>'>
		<input type="hidden" id='ruc' name='ruc' value='<?php echo $_POST['ruc'];?>'>
		<input type="hidden" id='email' name='email' value='<?php echo $_POST['email'];?>'>
		<input type="hidden" id='ser' name='ser' value='<?php echo 'FA_SERIE_'.$ser.'';?>'>
		<input type="hidden" id='n_fac' name='n_fac' value='<?php echo $numero;?>'>
		
		<ul class="todo-list">
			<li>
			<table>
				<tr>
					<td >
						<b>Datos del cliente</b>
					</td>
				</tr>
				<tr>
					<td>
						<b>Razon social: </b><?php echo $_POST['nombrec']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<b>Ruc: </b><?php echo $_POST['ruc']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<b>Email: </b><?php echo $_POST['email']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<b>Serie: </b><?php echo "FA_SERIE_001005"; ?> <b> Numero: </b><?php echo $numero; ?>
					</td>
				</tr>
				<tr>
					<td>
						<table class="table table-responsive">
							<tr>
								<td colspan='2'>
									<div class=""><h4>ABONOS</h4></div>
								</td>
							</tr>
				<?php
					//consultar los abonos
					$resul=$this->mostrar_abono($me);
					for($i=0;$i<count($resul);$i++)
					{
						?>
							<tr>
								<td>
									<input type="hidden" id='abono' name='abono' value='<?php echo $resul[$i]['Abono'];?>'>
									<input type="hidden" id='comp' name='comp' value='<?php echo $resul[$i]['Comprobante'];?>'>
									<input type="hidden" id='cta' name='cta' value='<?php echo $resul[$i]['Cta'];?>'>
									<b>Monto: </b><?php echo number_format($resul[$i]['Abono'],2, '.', ','); ?> 
								</td>
								<td>
									<b> Adicional: </b><?php echo $resul[$i]['Comprobante']; ?>
									<a onclick="eliminarabo('<?php echo $resul[$i]['Abono'];?>','<?php echo $resul[$i]['Comprobante'];?>','<?php echo $resul[$i]['Cta'];?>');" tittle="Eliminar">
										<i class="fa fa-trash-o"></i>
									</a>
								</td>
							</tr>
						<?php
						$abono=$abono+$resul[$i]['Abono'];
					}
				?>
						</table>
					</td>
				</tr>
				<tr>
					<td>
						<b>Tipo Pago (*): </b>
						<select class="xs" name="abo" id='abo' onChange="mos_ocu('texto_a','abo')">
							<option value='0'>Seleccionar</option>
							<?php select_option_aj('Catalogo_Cuentas','Codigo,TC',"Cuenta",
							" TC IN ('BA','CJ','CP','C','P','TJ','CF','CI','CB') 
							AND DG = 'D' AND Item = '".$_SESSION['INGRESO']['item']."' 
							AND Periodo = '".$_SESSION['INGRESO']['periodo']."'  "); ?>
						</select>
						<div id='e_abo' class="form-group has-error" style='display:none'>
							<span class="help-block">debe Seleccionar un tipo de pago</span>
						</div>
					</td>
				</tr>
				<tr id='texto_a' style='display:none;'>
					<td>
						<b>Comprobante:</b>
						<input type="text" class="xs" id="compro_a" name='compro_a' 
						placeholder="Numero compro o cheque" value='.' maxlength='45' size='5' >
						
					</td>
					
				</tr>
				<tr>
					<td>
						<b>Monto (*):</b>
						<input type="text" class="xs" id="monto_a" name='monto_a' 
						placeholder="Monto" value='1' maxlength='30' size='5' >
						<div id='e_monto_a' class="form-group has-error" style='display:none'>
							<span class="help-block">debe agregar monto</span>
						</div>
					</td>
					
				</tr>
				<tr>
					<td colspan='2' >
						<input type="hidden" id='total_abono' name='total_abono' value='<?php echo $abono;?>'>
						<p id='total_abono1'>Abono Total: $<?php echo $abono; ?></p> <p id="operacion"></p> <p id="devolucion"></p>
					</td>
				</tr>
				<tr>
					<td colspan='2'>
						<button type="button" class="btn btn-info btn-flat btn-sm" 
						onclick="agregar_abono('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>','<?php //echo $_POST['bus'];?>');">Agregar Abono</button>
						<?php
							if($i>0)
							{
								?>
								<button type="button" class="btn btn-info btn-flat btn-sm" 
								onclick="facturar('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>','<?php //echo $_POST['bus'];?>');">
									<span class="glyphicon glyphicon-floppy-disk"></span>
									Facturar
								</button>
								<?php
							}
						?>
					</td>
				</tr>
			</table>
			</li>
		</ul>
	<?php
	}
	echo $html = '
	<form>
       <div class="row">
         <div class="col-sm-6">
     	    <div class="panel panel-info">
				<div class="panel-heading"><h3>Datos de cliente</h3></div>
     	       <div class="panel-body">
     	         <div class="form-group">
                    <label for="nombrec" class="col-sm-2 control-label">Clientes</label>
					<input type="hidden" name="me" id="me" value="'.$me.'" />
					<input type="hidden" name="nom" id="nom" value="'.$nom.'" />
                	<input type="text" class="form-control" id="nombrec" name="nombrec" placeholder="Razon social" onkeyup="buscarCli(this,\''.$me.'\',\''.$nom.'\')" tabindex="0" required autocomplete="off">                				
                </div>
                <div class="form-group" id="cbeneficiario1" style="display:none;">
                	<label for="nombrec" id="lbeneficiario1" style="display:none;" class="col-sm-2 control-label"></label>
                		<select id="beneficiario1" name="beneficiario1" class="form-control" style="display:none;"  onchange="seleccionado('."beneficiario1".',\''.$me.'\');">
                			<option value="">Seleccione Empresa</option>
                		</select>
                	<input type="hidden" name="beneficiario2" id="beneficiario2" value="" />
              	</div>
                <div class="form-group">
                   	<label for="ruc" class="col-sm-2 control-label">RUC</label>
                   	<input type="text" class="form-control" id="ruc" name="ruc" placeholder="ruc"  value="." onfocus="seleFo("ruc",\''.$me.'\')" tabindex="0" required>
                </div>
                <div class="form-group">
                    <label for="email" class="col-sm-2 control-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" tabindex="0" required="">
                </div>
				<div class="form-group">
					<button type="button" class="btn btn-info btn-flat btn-sm" 
					onclick="agregar_cli(\''.$me.'\',\''.$nom.'\');">Agregar</button>
				</div>
           </div>
     	</div>
     </div>
     <div class="col-sm-6">
     	<div class="panel panel panel-info">
			<div class="panel-heading">
			  <div class="text-right">
			      <button type="button" class="btn btn-default btn-sm" onclick="prefact(\''.$me.'\',\''.$nom.'\')"><span class="glyphicon glyphicon-floppy-disk"></span> Factura</button>
				 <button type="button" class="btn btn-default btn-sm" onclick="prefact22(\''.$me.'\',\''.$nom.'\')"><i class="fa fa-file"></i> Pre-factura</button>
			  </div>
			</div>
     	   <div class="panel-body">
     	    <div class="row">
              <table class="table table-responsive">
               <thead>
                  <th>Cant</th>
      		      <th>Producto</th>
      		      <th>Precio</th>
      			  <th>Status</th>
      			  <th>Total</th>
      		    </thead>
      		    <tbody>
      			  '.$this->pedido_mesa($me,$nom).'
      		    </tbody>
      		   </table>
              <div>	
     	   </div>
     	</div>
     </div>
</div>
		
	</form>
        <script>
				$( "#pie_p" ).html(\'<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>\');
			</script>  
		
              ';
			  return 1;
			/*<script>
				$( "#pie_p" ).html("<button type="button" class="btn btn-default" data-dismiss="modal" tabindex="-1">Salir</button>'+
				'<a class="btn btn-info pull-right" id=\'proce\' onclick="prefact1(event,\'<?php echo $me; ?>\',\'ruc\')"'+
				'onkeyup="prefact1(event,\'<?php echo $me; ?>\',\'ruc\')" tabindex="0"> Procesar </a>');
			</script>';*/
		
}


function buscar_cliente($dato)
{
	$resultado = $this->modelo->bucarcliente($dato);	
	return $resultado;
}
function liberar_mesa($me)
{
	$respuesta = $this->modelo->liberar($me);
	return $respuesta;

}
function pdf_imprimir($me,$param)
{
	$this->modelo->pdf_imprimir($me,$param );
	//imprimirDocElPF(null,$me,null,null,null,0,null,'PF');
}




}

?>
