<?php

	class ConexionBean
	{
		function _Con()
		{
			//$con = new PDO('pgsql:dbname=RaspBerry;host=localhost;user=postgres;password=12345');
			//return $con;
			require("rb.php");
			$con = R::setup('pgsql:dbname=cobranzas;host=10.44.15.130;user=sysgps;password=DS2yhqpPp7XOIAyE0i15');
			return $con;
		}

		}//Conexion
	
?>