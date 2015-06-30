<?php 
	class Agregar
	{
		function _AgregarUsuario($Parametros)
		{
			//Tomando los datos.
			$nb_nombre     = $Parametros['nb_nombre'];
			$nb_apellidop  = $Parametros['nb_apellidop'];
			$nb_apellidom  = $Parametros['nb_apellidom'];
			$num_edad  	   = $Parametros['num_edad'];
			$mail  	       = $Parametros['mail'];

			$query    = '
				INSERT INTO usuarios (nb_nombre,nb_apellidop,nb_apellidom,num_edad,mail,sn_activo) 
				VALUES(
				"'.$nb_nombre.'",
				"'.$nb_apellidop.'",
				"'.$nb_apellidom.'",
				"'.$num_edad.'",
				"'.$mail.'",
				"1"
					)
			';
			$conectar = new Conectar();
			$con 	  = Conectar::_con();
			$result   = $con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;

		}//_AgregarUsuario

		function _AgregarRaspberry($Parametros)
		{
			$raspberry = R::dispense('equiposrasp');
			$raspberry->num_id = $Parametros['num_id'];
			$raspberry->nom_descripcion = $Parametros['nom_descripcion'];
			$raspberry->id_sitio =$Parametros['id_sitio'];
			$raspberry->sn_activo =$Parametros['sn_activo'];
			//$id        = R::store($raspberry);
			R::begin();
			    try{
			       $id = R::store($raspberry);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $id =  R::rollback();
			    }

			R::close();
			return $id;
		}//_AgregarRaspberry

	}//Agregar

?>