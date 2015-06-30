<?php
require "personal3.php";

$Empleado=getDatosEmpleado(90293738);

//90293738 de la rosa gerente cobranza

$Centro=getDatosEmpleadosXCentro(230397);

echo '<pre>'.print_r($Empleado).'</pre>';


?>