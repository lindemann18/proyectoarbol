<?php

	class Conexion
	{
		function _Con()
		{
			$con = new PDO('pgsql:dbname=cobranzas;host=10.44.15.130;user=sysgps;password=DS2yhqpPp7XOIAyE0i15');
			//print_r ($db);
			//$query = 'select * from users';
			//$res = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
			//print_r($res);	
			return $con;
		}

		}//Conexion
	
?>