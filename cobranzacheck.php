<?php 
	include("modulos/personal/personal3.php");
		$num_centro = 500305;
		if(is_numeric($num_centro))
		{
			$empleados 	  = getDatosEmpleadosXCentro($num_centro);
			$emp_total = array();

				foreach ($empleados as $empleado) {
					if($empleado['emp_cancelado']==0)
					{
						array_push($emp_total, $empleado);
					}
				}//foreach
		
			$numerico = 1;
			$contenido = array("Empleados"=>$emp_total,"Numerico"=>$numerico);
		}//if
		else
		{
			$numerico=0;
			$contenido = array("Numerico"=>$numerico);
		}
 ?>

 <pre><?php print_r($emp_total);?></pre>
 <!-- 91733057-> cobranza 3 -->
 <!-- 90243196 -> cobranza 1 -->
 <!-- 90394569 ->cobranza 4 -->


