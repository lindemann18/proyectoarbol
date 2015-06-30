<?php
	class Utilidades 
	{
		function LimpiarDatos($Dato)
		{

			$conectar  = new Conectar();
			$con 	   = Conectar::_con();
			$datoclean = $con->real_escape_string($Dato);
			return $datoclean;
		}//LimpiarDatos

	}//utilidades
?>