<?php 
require_once(dirname(__DIR__)."/modelo/modelomesa.php");
require_once(dirname(__DIR__,3)."/funciones/funciones.php");
date_default_timezone_set('America/Guayaquil');
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
		if(isset($_POST['fil']) )
		{
			$fil = $_POST['fil'];
		}
		else
		{
			$fil='0';
		}
		$query = $_POST['buscar'];
		//echo json_encode($mesa->lista_productos());
		echo json_decode($mesa->modal_pedido($id,$nom,$query,$fil));
		
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
	$me= $_POST['me'];
	$nom= $_POST['nom'];
	if($mesa->cambiar_detalle_fac($me)==1)
	{
	  $mesa->agregar_factura();
	  echo json_encode($mesa->modal_facturas($me,$nom));
	}else
	{
		print_r('expression');die();
	}
}
// if(isset($_REQUEST['buscarcli']))
// {   
// 	$mesa = new MesaCon();
// 	$query = $_GET['buscarcli'];
// 	echo json_encode($mesa->buscar_cliente($query));
// }
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

if (isset($_GET['buscarcli'])) {
	$mesa = new MesaCon();
	if(!isset($_GET['q']))
	{
		$_GET['q'] =''; 
	}
	echo json_encode($mesa->lista_clientes($_GET['q']));
}

if(isset($_REQUEST['update_cli']))
{
	$mesa = new MesaCon();
	$parametros = $_POST['parametros'];
	echo json_encode($mesa->update_cliente($parametros));
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
		 $ser = $this->modelo->factura_serie();
		if($ser=='.')
		{
			?>
			<script>
				Swal.fire({
					type: 'error',
					title: 'debe configurar la serie de facturaracion',
					text: ''
				}).then((result) => {
					if (result.value) {
						window.location='../panel.php?sa=s';
						//location.href="panel.php?mos2=e";
					} 
				});
				
			</script>
			<?php
		}
		$todos_mesa = $this->modelo->cargar_mesas();
		// print_r($todos_mesa);
		foreach ($todos_mesa as $key => $value) 
		{
			if($value['Estado']==0 OR $value['Estado']==null)
			{
				echo $html ="
				<div class='col-lg-3 col-xs-6'>
					<!-- small box -->
					<div class='small-box bg-green'>
						<div class='inner' onclick='agregar_n(\"".$value['Codigo_Inv']."\",\"".$value['Producto']."\",\"\");'>
							<h3 style='font-size: 20px'>Disponible<sup style='font-size: 17px'></sup></h3>
							<p>". $value['Producto']."</p>
						</div>
						<div class='icon'>
							<i class='ion ion-stats-bars'></i>
						</div>
						<a onclick='agregar_n(\"".$value['Codigo_Inv']."\",\"".$value['Producto']."\",\"\");' class='small-box-footer'>ver <i class='fa fa-arrow-circle-right'></i></a>
					</div>
				</div>
				";
			}
			else
			{
				echo $html = "<div class='col-lg-3 col-xs-6'>
					<!-- small box -->
					<div class='small-box bg-yellow'>
						<div class='inner' onclick='agregar_n(\"".$value['Codigo_Inv']."\",\"".$value['Producto']."\",\"\");'>
							<!--<h3 style='font-size: 20px'>Disponible<sup style='font-size: 17px'></sup></h3>-->
							<h3 style='font-size: 20px'>Ocupada <sup style='font-size: 17px'>".$this->modelo->productos_entregar($value['Codigo_Inv'])."</sup></h3>
							<p>".$value['Producto']."</p>
							
						</div>
						<div class='icon'>
							<i class='ion ion-stats-bars'></i>
						</div>
						<a onclick='agregar_n(\"".$value['Codigo_Inv']."\",\"".$value['Producto']."\",\"\");' class='small-box-footer'>ver <i class='fa fa-arrow-circle-right'></i></a>

					</div>
				</div>";
			}
		}
    }

    function modal_pedido($id,$nom,$buscar,$fil)
    {
		$categoria = $this->lista_categorias($id,$nom);
		$articulos = $this->lista_productos($buscar,$fil);
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
						 
						 <button type="button" class="btn btn-default btn-sm" onclick="imprimir_ticket(\''.$id.'\')"><i class="fa fa-file"></i> Pre-factura</button>
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
					<div class="panel panel-info">';
						if($buscar!='' or ($fil!='' and $fil!='0'))
						{
							echo $html ='
							<div class="panel-heading">Articulos 
								<select class="xs" name="f_pro" id="f_pro" onChange="filtro_prod(\''.$id.'\',\''.$nom.'\',\'f_pro\')">
									<option value="0">Seleccionar</option>
									<option value="">Todos</option>';
									select_option_aj('Catalogo_Productos','Codigo_Inv',"Producto",
										"  (TC = 'I')
										AND Item = '".$_SESSION['INGRESO']['item']."' 
										AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
										AND LEN(Codigo_Inv)>=4
										AND SUBSTRING(Codigo_Inv,1,2)='01' 
										 order by Producto "); 
								echo $html ='</select>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Buscar Producto" id="buscar" name="buscar">
											<span class="input-group-btn">
												<button type="button" class="btn btn-info btn-flat"onclick="agregar_n(\''.$id.'\',\''.$nom.'\',\'f_pro\')">
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
							</div> ';
						}
						else
						{
							echo $html ='
							<div class="panel-heading">Categorias 
								<select class="xs" name="f_pro" id="f_pro" onChange="filtro_prod(\''.$id.'\',\''.$nom.'\',\'f_pro\')">
									<option value="0">Seleccionar</option>
									<option value="">Todos</option>';
									select_option_aj('Catalogo_Productos','Codigo_Inv',"Producto",
										"  (TC = 'I')
										AND Item = '".$_SESSION['INGRESO']['item']."' 
										AND Periodo = '".$_SESSION['INGRESO']['periodo']."'
										AND LEN(Codigo_Inv)>=4
										AND SUBSTRING(Codigo_Inv,1,2)='01'  order by Producto "); 
								echo $html ='</select>
							</div>
							<div class="panel-body">
								<div class="row">
									<div class="col-sm-6">
										<div class="input-group">
											<input type="text" class="form-control" placeholder="Buscar Producto" id="buscar" name="buscar">
											<span class="input-group-btn">
												<button type="button" class="btn btn-info btn-flat"onclick="agregar_n(\''.$id.'\',\''.$nom.'\',\'f_pro\')">
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
								<br>
									'.$categoria.'
							</div>';
						}
					echo $html ='
					</div>
				</div>
			</div>
		</form>';	
	}
	function lista_categorias($id,$nom)
	{
		$categorias = $this->modelo->list_categorias();
		$html = '';
		$i=0;
		foreach ($categorias as $key => $value) {
			if($i==0)
			{
				$html.= '<div class="row">
							<div class="btn-group btn-group-justified">';
			}
			if(($i % 2)==0)
			{
				$html.= '</div>
							</div><br>';
				$html.= '<div class="row">
							<div class="btn-group btn-group-justified">';
				//MARQUES DE CASA CONCHA ET (24)
			}
			if(strlen($value["Producto"])<21)
			{
				$html.= '
					<a class="btn btn-app" href="#" style="width:80%;"  title="'.$value["Producto"].'" 
				onClick="filtro_prod(\''.$id.'\',\''.$nom.'\',\''.$value['Codigo_Inv'].'\')">
					<i class="fa fa-fw fa-spoon"></i> '.$value["Producto"].'
				</a>';
			}
			else
			{
				$html.= '
					<a class="btn btn-app" href="#" style="width:80%;"  title="'.$value["Producto"].'" 
					onClick="filtro_prod(\''.$id.'\',\''.$nom.'\',\''.$value['Codigo_Inv'].'\')">
						<i class="fa fa-fw fa-spoon"></i> '.substr($value["Producto"], 0, 21).'
					</a>';
			}
			$i++;
				/*<a class="btn btn-app" href="#" style="width:60px;" data-toggle="tooltip" title="'.$value["Producto"].'" 
				onClick="filtro_prod(\''.$id.'\',\''.$nom.'\',\''.$value['Codigo_Inv'].'\')">
					<i class="fa fa-fw fa-file-excel-o"></i> '.$value["Producto"].'
				</a>*/
		}
		/*
		$html.= '
			<a class="btn btn-block btn-default btn-sm" href="#" style="width:50%;"  title="'.$value["Producto"].'" 
			onClick="filtro_prod(\''.$id.'\',\''.$nom.'\',\''.$value['Codigo_Inv'].'\')">
				<i class="fa fa-fw fa-file-excel-o"></i> '.$value["Producto"].'
			</a>&nbsp;';
		$html.= '
			<a class="btn btn-block btn-default btn-sm" href="#" style="width:50%;"  title="'.$value["Producto"].'" 
			onClick="filtro_prod(\''.$id.'\',\''.$nom.'\',\''.$value['Codigo_Inv'].'\')">
				<i class="fa fa-fw fa-file-excel-o"></i> '.substr($value["Producto"], 0, 21).'...
			</a>&nbsp;';
		*/

	  return $html;
	}
	function lista_productos($buscar,$fil)
	{
		$productos = $this->modelo->list_product($buscar,$fil);
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
						<input type="text" class="xs" id="canti_'.$value['Codigo_Inv'].'" name="canti_'.$value['Codigo_Inv'].'"	
						placeholder="Cant" value="1" maxlength="30"  size="5" style="display:none;" 
						onkeyup="c_total(\''.$value["Codigo_Inv"].'\',\''.$value["PVP"].'\',\''.$value["PVP_2"].'\',\''.$key.'\')">
					  </div>
					  <div class="col-sm-6">
					  <label id="lobs_'.$value['Codigo_Inv'].'" style="display:none;">Observacion</label>
						<input type="text" class="xs" id="obs_'.$value['Codigo_Inv'].'"	name="obs_'.$value['Codigo_Inv'].'"	placeholder="Observacion" value="." maxlength="50" size="15" style="display:none;">
					  </div>';
					  if($value['PVP_2']<>0)
					  {
							$html.=' <div class="col-sm-6">
								<label id="lcop_'.$value['Codigo_Inv'].'" style="display:none;">Medida</label>
								<select class="xs" id="cop_'.$value['Codigo_Inv'].'"	name="cop_'.$value['Codigo_Inv'].'" style="display:none;" 
								onChange="c_total(\''.$value["Codigo_Inv"].'\',\''.$value["PVP"].'\',\''.$value["PVP_2"].'\',\''.$key.'\')">
									<option value="0">Producto Completo</option>
									<option value="1">Por Fraccion (Copa)</option>
								</select>
							</div>';
					  }
					  else
					  {
							$html.=' <div class="col-sm-6">
								<label id="lcop_'.$value['Codigo_Inv'].'" style="display:none;"></label>
								<select class="xs" id="cop_'.$value['Codigo_Inv'].'"	name="cop_'.$value['Codigo_Inv'].'" style="display:none;">
									<option value="0">Producto Completo</option>
								</select>
							</div>';
					  }
					 $html.=' </div>
					</td>
					';
					if($value['PVP_2']==0)
					{
						$html.='<td width="50px"> $ '.number_format($value['PVP'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).'';
					}
					else
					{
						$html.='<td width="90px"> $ '.number_format($value['PVP'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).' Copa:';
						$html.=' $ '.number_format($value['PVP_2'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).'';
					}
					$html.=' </td>
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
			 <td>'.number_format($value['PRECIO'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).'</td>
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
			 <td>'.number_format(($value['PRECIO']* $value['CANT']),2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).'</td>
			 <td>';
			 if($value['Estado']!='V')
				{
					$html.='<a onclick="eliminarpro(\''.$me.'\',\''.$nom.'\',\''.$value['CODIGO'].'\',\''.$value['CANT'].'\',\''.$value['A_No'].'\');" Tittle="Eliminar">
							<i class="fa fa-trash-o"></i></a>';
				}

			 $html.='</td>
			</tr>';
		}
		$html.='<tr><td colspan="5" class="text-right" style="color:red">SUB TOTAL:</td><td class="text-right" style="color:red">$'.number_format($total,2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).'</td></tr>'.
			'<tr><td colspan="5" class="text-right" style="color:red">IVA:</td><td class="text-right" style="color:red">$'.number_format($iva,2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).'</td></tr>'.
			'<tr><td colspan="5" class="text-right" style="color:red">TOTAL:</td><td class="text-right" style="color:red">$'.number_format(($total+$iva),2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).'</td></tr>'.
			'<script>
				var total_abono=document.getElementById(\'total_abono\').value;
				var devo=(total_abono-'.($total+$iva).').toFixed(2);
				$( "#total_abono1" ).html(\'<b>TOTAL ABONO: \'+total_abono+\'</b>\');
				$( "#operacion" ).html(\'<input type="hidden" id="total_total_" name="total_total_" value="'.number_format(($total+$iva),2).'"><b>TOTAL: $'.number_format(($total+$iva),2,$_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']).'</b>\');
				$( "#devolucion" ).html(\'<b>DIFERENCIA: $\'+devo+\'</b>\');
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
		if($resp==2)
		{
			echo "<script type='text/javascript'>
					Swal.fire({
						//position: 'top-end',
						type: 'success',
						title: 'Factura Generada con exito!',
						showConfirmButton: true
						//timer: 2500
					});
				</script>";
		}
		if($resp==0)
		{
			echo "<script type='text/javascript'>
					Swal.fire({
						type: 'error',
						title: 'error al ingresar factura recargue y intente nuevamente',
						text: ''
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
			if($ser=='.')
			{
				?>
				<script>
					Swal.fire({
						type: 'error',
						title: 'debe configurar serie a cual facturar',
						text: ''
					}).then((result) => {
						if (result.value) {
							window.location='../panel.php?sa=s';
							//location.href="panel.php?mos2=e";
						} 
					});
				</script>
				<?php
			}
			//echo ' vvv '.$ser;
			$numero = $this->modelo->factura_numero($ser);
			$abono=0;
		?>
			<input type="hidden" id='nombrec2' name='nombrec2' value='<?php echo $_POST['nombrec'];?>'>
			<input type="hidden" id='ruc' name='ruc' value='<?php echo $_POST['ruc'];?>'>
			<input type="hidden" id='email' name='email' value='<?php echo $_POST['email'];?>'>
			<input type="hidden" id='ser' name='ser' value='<?php echo 'FA_SERIE_'.$ser.'';?>'>
			<input type="hidden" id='n_fac' name='n_fac' value='<?php echo $numero;?>'>
			<input type="hidden" id='dir' name='dir' value='<?php echo $_POST["dir"];?>'>
			<input type="hidden" id='tel' name='tel' value='<?php echo $_POST['tel'];?>'>
			

		<div class="box box-info">
            <div class="box-header with-border" style="padding-bottom: 0px;">
              <h3 class="box-title"><b>Datos de cliente</b></h3>
            </div>
            <div class="box-body" style="padding-top: 0px;padding-bottom: 0px;">
            	<div class="row">
            		<div class="col-sm-9 col-md-10">
            			<div class="row">
            				<div class="col-md-9 col-sm-12">
              	               <b>Razon Social: </b><?php echo $_POST['nombrec']; ?>
                             </div>
                             <div class="col-sm-9 col-md-3">
              	               <b>Ruc: </b><?php echo $_POST['ruc']; ?>
                             </div>            				
            			</div>
            			<div class="row">
            				 <div class="col-sm-6 col-md-4">
              	               <b>Email: </b><?php echo $_POST['email']; ?>
                             </div>
                             <div class="col-sm-6 col-md-3">
              	                <b>Telefono: </b><?php echo $_POST['tel']; ?>
                             </div>
                              <div class="col-sm-12 col-md-5">
              	               <b>Direccion: </b><?php echo $_POST['dir']; ?>
                             </div>            				
            			</div>
            		</div>
            		<div class="col-sm-3 col-md-2" style="padding: 0px;">
            			<h3 style="margin-top: 0px;"><b> Fact. No: </b><?php echo $numero; ?></h3> 
            			<b>Serie: </b><?php echo "FA_SERIE_".$ser.""; ?>      			
            		</div>     		
            	</div>
            	   
            </div>
            <!-- /.box-body -->
          </div>

		<div class="box box-info">
            <div class="box-body">
            	<div class="row">
            		<div class="col-md-6">
            			<div class="box-header with-border">
            				<h3 class="box-title"><b>ABONOS</b></h3>
            			</div>
            			<table class="table table-hover">
            				<thead>
            					<th>Forma de pago</th>            					
            					<th>Monto</th>         					
            					<th>Adicional</th>
            					<th></th>
            				</thead>
            				<tbody>
            			<?php
						//consultar los abonos
						$resul=$this->mostrar_abono($me);
						for($i=0;$i<count($resul);$i++)
						{
							?>
								<tr>
									<td>
										<?php echo $resul[$i]['Forma']; ?> 
									</td>
									<td>
										<input type="hidden" id='abono' name='abono' value='<?php echo $resul[$i]['Abono'];?>'>
										<input type="hidden" id='comp' name='comp' value='<?php echo $resul[$i]['Comprobante'];?>'>
										<input type="hidden" id='cta' name='cta' value='<?php echo $resul[$i]['Cta'];?>'>
										<?php echo number_format($resul[$i]['Abono'],2, $_SESSION['INGRESO']['Signo_Dec'], $_SESSION['INGRESO']['Signo_Mil']); ?> 
									</td>
									<td>
										<?php echo $resul[$i]['Comprobante']; ?>
										
									</td>
									<td>
										<a onclick="eliminarabo('<?php echo $resul[$i]['Abono'];?>','<?php echo $resul[$i]['Comprobante'];?>','<?php echo $resul[$i]['Cta'];?>');" tittle="Eliminar">
											<i class="fa fa-trash-o"></i>
										</a>										
									</td>
								</tr>
							<?php
							$abono=$abono+$resul[$i]['Abono'];
						}
					?> 
					</tbody>
				    </table>
            		</div>
            		<div class="col-md-6">            			
            			<div><b>Tipo Pago (*):</b>
							<select class="xs form-control" name="abo" id='abo' onChange="mos_ocu('texto_a','abo')">
									<option value='0'>Seleccionar</option>
									<?php										
										select_option_aj('Catalogo_Cuentas','Codigo,TC',"Cuenta",
										" TC IN ('TJ','EF') 
										AND DG = 'D' AND Item = '".$_SESSION['INGRESO']['item']."' 
										AND Periodo = '".$_SESSION['INGRESO']['periodo']."' order by TC ,Codigo ",'1.1.01.01.09-CJ'); ?>
							</select>
							<div id='e_abo' class="form-group has-error" style='display:none'>
								<span class="help-block">debe Seleccionar un tipo de pago</span>
							</div>
						</div>
						<div>
							<b>Monto (*):</b>
							<input type="text" class="xs form-control" id="monto_a" name='monto_a' 
								placeholder="Monto" value='1' maxlength='30' size='5'>
							<div id='e_monto_a' class="form-group has-error" style='display:none'>
								<span class="help-block">debe agregar monto</span>
							</div>
						</div>
						<div>
							<b>Propina:</b>
							<input type="text" class="xs form-control" id="propina_a" name='propina_a' 
								placeholder="Propina" value='0' maxlength='30' size='5' >
						</div> 
						<div id='texto_a' style='display:none;' ><b>Comprobante:</b>
							<input type="text" class="xs form-control" id="compro_a" name='compro_a' 
								placeholder="Numero compro o cheque" value='.' maxlength='45' size='5' >
						</div>									
            		</div>

            	</div>
            	<div class="row">
            		<div class="col-md-6">
            			<table class="table table-hober">
            				<tr>
            					<td>
            						 <p id="operacion"></p>
            					</td>
            					<td>
            						<p id='total_abono1'>Abono Total: $<?php echo $abono; ?></p>
            					</td>
            					<td>
            						 <p id="devolucion"></p>
            					</td>
            				</tr>
            				
            			</table>
            			<input type="hidden" id='total_abono' name='total_abono' value='<?php echo number_format($abono,2);?>'>
            		</div>
            		<div class="col-md-6">            			
							<button type="button" class="btn btn-info btn-flat btn-sm" 
							onclick="agregar_abono('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>','<?php //echo $_POST['bus'];?>');">Agregar Abono</button>
							<?php
								if($i>0)
								{
									?>									
									<button type="button" class="btn btn-info btn-flat btn-sm" 
									onclick="facturar1('<?php echo $_POST['me']; ?>','<?php echo $_POST['nom'];?>','<?php //echo $_POST['bus'];?>');">
										<span class="glyphicon glyphicon-floppy-disk"></span>
										Facturar
									</button>									
									<?php
								}
							?>            			
            		</div>
            	</div>            	   
            </div>
        </div>


		
		<?php
		}
		$html = '
		
		<form>
		   <div class="row">
			 <div class="col-sm-6">
				<div class="panel panel-info">
					<div class="panel-heading"><h4>Datos de cliente</h4></div>
				   <div class="panel-body">
				        <div class="row">
				            <div class="col-sm-12 text-right">
						    <button type="button" class="btn btn-info btn-flat btn-sm" 						    onclick="nuevo_cliente();">Nuevo Cliente</button>				    
				            </div>
				        </div>
				        <br>
					    <div class="row">
					        <div class="col-sm-3">
						        <label for="nombrec" class="col-sm-2 control-label">Clientes</label>
						        <input type="hidden" name="me" id="me" value="'.$me.'" />
						        <input type="hidden" name="nom" id="nom" value="'.$nom.'" />
					        </div>
					        <div class="col-sm-9">
					            <select class="form-control" id="nombrec" name="nombrec" onchange="seleccionado(\''.$me.'\');">
                                     <option value="">Buscar cliente</option>
                                </select>  
					        </div>
					    </div>
					     <div class="row">
					        <div class="col-sm-3">
						        <label for="ruc" class="col-sm-2 control-label">RUC</label>
					        </div>
					        <div class="col-sm-9">
					            <input type="text" class="form-control" id="ruc" name="ruc" placeholder="ruc"  value="." tabindex="0" required>
					        </div>
					    </div>					    
					    <div class="row">
					        <div class="col-sm-3">
						        <label for="ruc" class="col-sm-2 control-label">Telefono</label>
					        </div>
					        <div class="col-sm-9">
					            <input type="text" class="form-control" id="Telefono" name="Telefono" placeholder="Telefono"  value="." tabindex="0" required>
					        </div>
					    </div>
					    <div class="row">
					        <div class="col-sm-3">
						        <label for="email" class="col-sm-2 control-label">Email</label>
					        </div>
					        <div class="col-sm-9">
					           <input type="email" class="form-control" id="email" name="email" placeholder="Email" tabindex="0" required="" onblur="validarEmail()">
					        </div>
					    </div>
					    <div class="row">
					        <div class="col-sm-3">
						        <label for="ruc" class="col-sm-2 control-label">Direccion</label>
					        </div>
					        <div class="col-sm-9">
					            <textarea class="form-control" id="Direccion" required onblur="validarDir()"></textarea>
					        </div>
					    </div>		
					   						
					    <div class="form-group">
						    <button type="button" class="btn btn-info btn-flat btn-sm" 
						    onclick="agregar_cli(\''.$me.'\',\''.$nom.'\');" id="add_datos">Agregar</button>
						    <button type="button" class="btn btn-info btn-flat btn-sm" 
						    onclick="update_cliente()" id="update_cli" style="display:none">Editar Cliente</button>
					    </div>
					    
			     </div>
			</div>
		 </div>
		 <div class="col-sm-6">
			<div class="panel panel panel-info">
				<div class="panel-heading">
				  <div class="text-center">';
					if(isset($_POST['nombrec']) and isset( $_POST['ruc']))
					{
						$html=$html.'<h4> Datos Pedido </h4>'; 
					}
					else
					{
						$html=$html.'<h4> Datos Pedido </h4>'; 
						/*$html=$html.'<button type="button" class="btn btn-default btn-sm" onclick="prefact(\''.$me.'\',\''.$nom.'\')"><span class="glyphicon glyphicon-floppy-disk"></span> Factura</button>
						<button type="button" class="btn btn-default btn-sm" onclick="prefact22(\''.$me.'\',\''.$nom.'\')"><i class="fa fa-file"></i> Pre-factura</button>';*/
					}
				  $html=$html.'</div>
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
					var ruc = $(\'#ruc\').val();
					var query = $(\'#nombrec\').val();	
					$("#ruc").focus(function()
					{
						if(ruc==\'.\' )
						{
							//alert(\'vvv\');
							buscarCli(\'\',\'13\');
						}
						
					});
				</script> 
				<script type="text/javascript">

			$("#nombrec").select2({
             placeholder: "Digite Cliente",
             ajax: {
               url: "controlador/controladormesa.php?buscarcli=true",
               dataType: "json",
               delay: 250,
               processResults: function (data) {
                 return {
                   results: data
                 };
               },
               cache: true
             }
           });
	    </script>
 
			
				  ';
				  echo $html;
				  //<!-- se quita esta funcion del ruc = onfocus="seleFo("ruc",\''.$me.'\')"--> 
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

	function datos_fac_pre($parametros)
	{
		date_default_timezone_set('America/Guayaquil');
		$datos_pre  ="";
		if($parametros['tipo'] =='PF')
		{
		 $datos_pre =  $this->modelo->datos_pre_factura($parametros['mesa']);
		 $cabe ='<div style="font-size: 12px;"><b>PREFACTURA No:</b> <br>
        	<b>NUMERO DE AUTORIZACION: </b> 0 <br>
        	<b>FECHA: </b>'.date('Y-m-d').' <b>HORA: </b>'.date('H:m:s').' <br>
        	<b>AMBIENTE: </b>Produccion  <b>EMISION: </b>Normal <br>
        	<b>CLAVE DE ACCESO</b><br></div><hr>';
        $cliente = "<b>Razon social/Nombres y Apellidos: </b>. <br>
                    <b>Identificacion: </b>0 <b>Telefono: </b>022920133<br>
                    <b>Email: </b>diskcover.system@gmail.com<br>";

        $lineas = "<hr><pre>";
           foreach ($datos_pre['lineas'] as $key => $value) {
    $lineas.='
 <tr>Cant: <td>'.$value['CANT'].'</td></br>';
                   if($value['Total_IVA']==0)
                   {
                   	 $lineas.= '<td>PROD:'.$value['PRODUCTO'].' (E) </td><br>';
                   }else
                   {
                   	$lineas.= '<td>PROD:'.$value['PRODUCTO'].'</td><br>';
                   }
                   $lineas.='<td style="text-align: right;">PRE:'.number_format($value['PRECIO'],2).'</td><br><td style="text-align: right;">TOT'.number_format($value['TOTAL'],2).'</td>
               </tr>';
           }              
           $lineas.="</pre>";


		}else{
		 $datos_pre =  $this->modelo->datos_factura($parametros);
		 // print_r($datos_pre['lineas']);die();
			$cabe ='<b>FACTURA No:</b>'.$datos_pre['lineas'][0]['Factura'].' <br>
        	<b>NUMERO DE AUTORIZACION: </b><br>  
        	<table style="font-size:12px;"><tr><td>'.$datos_pre['lineas'][0]['Autorizacion'].'</td></tr></table>
        	<b>FECHA: </b>'.date('Y-m-d').' <b>HORA: </b>'.date('H:m:s').' <br>
        	<b>AMBIENTE: </b>Produccion  <b>EMISION: </b>Normal <br>
        	<b>CLAVE DE ACCESO</b><br><hr>';
          $cliente = "<b>Razon social/Nombres y Apellidos: </b>. <br>".$datos_pre['cliente']['Cliente']."<br>
                    <b>Identificacion: </b>".$datos_pre['cliente']['CI_RUC']." <b> 
                    Telefono: </b>".$datos_pre['cliente']['Telefono']."<br>
                    <b>Email: </b>".$datos_pre['cliente']['Email']."<br>";




        $lineas = "<hr><pre>";
           foreach ($datos_pre['lineas'] as $key => $value) {
    $lineas.='
 <tr>Cant: <td>'.$value['Cantidad'].'</td></br>';
                   if($value['Total_IVA']==0)
                   {
                   	 $lineas.= '<td>PROD:'.$value['Producto'].' (E) </td><br>';
                   }else
                   {
                   	$lineas.= '<td>PROD:'.$value['Producto'].'</td><br>';
                   }
                   $lineas.='<td style="text-align: right;">PRE:'.number_format($value['Precio'],2).'</td><br><td style="text-align: right;">TOT'.number_format($value['Total'],2).'</td>
               </tr>';
           }              
           $lineas.="</pre>";

     }

         $totales = "<hr>
         <table style='font-size: 10px;'>
           <tr>
             <td style='width: 250px;'><b>Cajero:</b><br></td>
             <td><b>SUBTOTAL:</b></td>
             <td style='text-align: right;'>".number_format($datos_pre['preciot'],2) ."</td>
           </tr>
           <tr>
             <td>".$_SESSION['INGRESO']['Nombre']."</td>
             <td><b>DESCUENTO:</b></td>
             <td style='text-align: right;'>0.00</td>
           </tr>
           <tr>
             <td rowspan='3'>Su factura sera enviada al correo electronico registrado</td>
             <td><b>I.V.A 12%:</b> </td>
             <td style='text-align: right;'>".number_format($datos_pre['iva'],2) ."</td>
           </tr>
           <tr>
             <td><b>TOTAL:</b></td>
             <td style='text-align: right;'>".number_format($datos_pre['tota'],2)."</td>
           </tr>
           <tr>
             <td><b>Propina:</b></td>
             <td style='text-align: right;'>0.00</td>
           </tr>

         </table>
         ";

         $datos_extra = "<hr><pre>
         <table style='width:100%'>
           <tr style='text-align:center'>
            <td><b>Datos de factura</b></td>
           </tr>
            <tr>
            <td><b>Nombre</b> <br><hr> </td>
           </tr>
            <tr>
            <td><b>CI/RUC</b> <br><hr> </td>
           </tr>
            <tr>
            <td><b>Correo</b> <br><hr> </td>
           </tr>
            <tr>
            <td><b>Telefono</b> <br><hr> </td>
           </tr>
            <tr>
            <td><b>Direccion</b> <br><hr> </td>
           </tr>
            <tr>
            <td style='text-align:center'>Fue un placer atenderle <br>www.cofradiadelvino.com <br>
                </td>
           </tr>
         </table>











</pre>
         ";

        return $cabe.$cliente.$lineas.$totales.$datos_extra;
		

	}

	function datos_fac_ticket($parametros,$TC)
	{
		date_default_timezone_set('America/Guayaquil');
		$datos_pre  ="";
		$datos_pre =  $this->modelo->datos_factura($parametros);
		$cabe ='<font face="Courier New" size=2>Transaccion ('.$TC.'): No. '.$datos_pre['lineas'][0]['Factura'].' <br>
				Fecha: '.date('Y-m-d').' - Hora: </b>'.date('H:m:s').' <br>
				Cliente: <br>'.$datos_pre["cliente"]["Cliente"].'<br>
				R.U.C/C.I.: '.$datos_pre['cliente']['CI_RUC'].'<br> 
				Cajero: '.$_SESSION['INGRESO']['Nombre'].' <br>
				Telefono: '.$datos_pre['cliente']['Telefono'].'<br>
				Dirección: '.$datos_pre['cliente']['Direccion'].'<br>';
        $cabe .= "<hr>PRODUCTO/Cant x PVP/TOTAL";
        $lineas = "<hr>";
        foreach ($datos_pre['lineas'] as $key => $value) {
            if($value['Total_IVA']==0)
            {
                $lineas.= '<div class="row"><div class="col-sm-12">'.$value['Producto'].' </div></div>';
            }else
            {
                $lineas.= '<div class="row"><div class="col-sm-12">'.$value['Producto'].'</div></div>';
            }
    		$lineas.='<div class="row"><div class="col-sm-6">'.$value['Cantidad'].' X '.number_format($value['Precio'],2).'</div><div class="col-sm-6" style="text-align: right;">'.number_format($value['Total'],2).'</div></div>';
        }
        $totales = "<hr>
         <table>
           <tr>
           	 <td style='width: 200px;' colspan='3'></td>
             <td style='text-align: right;'>SUBTOTAL:</td>
             <td style='text-align: right;'>".number_format($datos_pre['tota'],2) ."</td>
           </tr>
           <tr>
           	 <td colspan='3'></td>
             <td style='text-align: right;'>I.V.A 12%:</td>
             <td style='text-align: right;'>".number_format($datos_pre['iva'],2) ."</td>
           </tr>
           <tr>
             <td colspan='3'></td>
             <td style='text-align: right;'>TOTAL FACTURA:</td>
             <td style='text-align: right;'>".number_format($datos_pre['tota'],2)."</td>
           </tr>
           <tr>
             <td colspan='3'></td>
             <td style='text-align: right;'>EFECTIVO:</td>
             <td style='text-align: right;'>".number_format($datos_pre['tota'],2)."</td>
           </tr>
           <tr>
             <td colspan='3'></td>
             <td style='text-align: right;'>CAMBIO:</td>
             <td style='text-align: right;'>0.00</td>
           </tr>
         </table>
     	";

         $datos_extra = "<hr>
         <table style='width:100%'>
            <tr>
            <td style='text-align:center'>Fue un placer atenderle <br>Gracias por su compra<br>www.cofradiadelvino.com <br>
                </td>
           </tr>
         </table></font>";
        return $cabe.$lineas.$totales.$datos_extra;
	}


	function lista_clientes($query)
	{
		$resp = $this->modelo->listar_clientes($query);
		return $resp;
	}

	function update_cliente($parametros)
	{
		$resp = $this->modelo->update_cliente($parametros['ema'],$parametros['dir'],$parametros['tel'],$parametros['id']);
		// print_r($resp);die();
		return $resp;
	}
	function cambiar_detalle_fac($mesa)
	{
		   $pedido = $this->modelo->pedido_realizado($mesa);
		   $datos[0]['campo']='CODIGO_INV';		   
		   $datos[1]['campo']='PRODUCTO';
		   $datos[2]['campo']='CANT_ES';
		   $datos[3]['campo']='CTA_INVENTARIO';
		   $datos[4]['campo']='CodigoU';
		   $datos[4]['dato']=$_SESSION['INGRESO']['Id'];   
		   $datos[5]['campo']='Item';
		   $datos[5]['dato']=$_SESSION['INGRESO']['item'];
		   $datos[6]['campo']='A_No';
		   $datos[7]['campo']='Fecha_Fab';
		   $datos[8]['campo']='TC';
		   $datos[9]['campo']='VALOR_TOTAL';
		   $datos[10]['campo']='CANTIDAD';
		   $datos[11]['campo']='VALOR_UNIT';
		   $datos[12]['campo']='CONTRA_CTA';
		   $datos[13]['campo']='IVA';
		   $datos[14]['campo']='ORDEN';
		   $valor_total = 0;
		   $iva = 0;
		   $total = 0;

		foreach ($pedido as $key => $value) {

		   $datos[0]['dato']=$value['CODIGO'];
		   $datos[1]['dato']=$value['PRODUCTO'];
		   $datos[2]['dato']=$value['CANT'];
		   $datos[3]['dato']=$value['Cta_Inv'];
		   $datos[6]['dato']=$value['A_No'];
		   $datos[7]['dato']=$value['FECHA']->format('Y-m-d');
		   $datos[8]['dato']='FA';
		   $datos[9]['dato']=round($value['TOTAL'],2);
		   $datos[10]['dato']=$value['CANT'];
		   $datos[11]['dato']=round($value['PRECIO'],2);
		   $datos[12]['dato']=$value['Cta_Costo'];
		   $datos[13]['dato']=$value['Total_IVA'];
		   $datos[14]['dato']=$value['HABIT'];
		   $resp = $this->modelo->ingresar_asiento_K($datos);
		   $valor_total +=$datos[9]['dato'];
		   $total+=$datos[9]['dato'];
		   $iva+=$datos[13]['dato'];
		   $par = array('cod_p'=>'','cant'=>$value['CANT'],'cod'=>$value['A_No'],'me'=>$value['HABIT']);
		   $this->modelo->eliminar_de_pedido($par);		
		}
		 $producto = array("me"=>$mesa,"iva"=>$iva,'prod'=>'99.99','total'=>$total,'obs'=>'','valor_total'=>$total,'Precio'=>$total);
		 // print_r($producto);
		 // die();
		   if($this->modelo->agregar_a_pedido_EXPER($producto)==1)
		   {
		   	 return 1;
		   }else
		   {
		   	return -1;
		   }
	}
}

?>
