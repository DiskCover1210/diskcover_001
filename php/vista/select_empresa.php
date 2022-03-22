<?php
require_once("../controlador/panel.php");


	$empresa=getEmpresas($_SESSION['INGRESO']['IDEntidad']);
	$i=0;

	$num = count($empresa);
	if($num > 1)
	{
		$html = '
		<div class="box-body">
			<div class="col-lg-12 col-xs-9">						
				<label>Empresas asociadas a este usuario</label>
				<select class="form-control select2" name="sempresa" id="sempresa" onchange="cambiar_1()">							  
				  <option value="0-0">Seleccione empresa</option>';	
				  foreach ($empresa as $valor) 
		         {
			       $v_empre=$valor['ID'].'-'.$valor['Item'];
				   $html.='<option value='.$valor['ID'].'-'.$valor['Item'].'>'.$valor['Empresa'].'</option>';

		         }         
		         
			$html.='</select>
			</div>
	 </div>';
	 echo $html;
	}else if($num == 1)
	{
		$html='<script>$("#myModal_espera").modal("show"); window.location="panel.php?mos='.$empresa[0]['ID'].'-'.$empresa[0]['Item'].'&mos1='.$empresa[0]['Empresa'].'&mos3='.$empresa[0]['Item'].'"</script>';
		echo $html;
	}else
	{
		$html = '<div class="box-body text-center"><div class="col-lg-12 col-xs-9"><img src="../../img/NO_ASIGNADO.gif" style="width: 50%;"></div></div>';
		echo $html;
	}
	
?><!-- 
<script type="text/javascript">	$('#myModal_espera').modal('show');</script>
<script type="text/javascript">	$('#myModal_espera').modal('hide');</script> -->