<?php
require_once("../controlador/panel.php");


				$empresa=getEmpresas($_SESSION['INGRESO']['IDEntidad']);
				$i=0;

				$num = count($empresa);
				if($num !=1)
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
				}else
				{
					$html='<script>window.location="panel.php?mos='.$empresa[0]['ID'].'-'.$empresa[0]['Item'].'&mos1='.$empresa[0]['Empresa'].'&mos3='.$empresa[0]['Item'].'"</script>';
					echo $html;
				}
				
?>