<?php 
	class Actualizar
	{
		function _EliminarUsuario($id)
		{
			$query    = 'UPDATE usuarios  set sn_activo =0 WHERE id="'.$id.'"';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ConsultarUsuarios

		function _ActualizarUsuario($Parametros)
		{
			$id 	      = $Parametros['id'];
			$nb_nombre    = $Parametros['nb_nombre'];
			$nb_apellidop = $Parametros['nb_apellidop'];
			$nb_apellidom = $Parametros['nb_apellidom'];
			$num_edad     = $Parametros['num_edad'];
			$mail     	  = $Parametros['mail'];
			$query    = '
				UPDATE usuarios set 
				nb_nombre ="'.$nb_nombre.'",
				nb_apellidop ="'.$nb_apellidop.'",
				nb_apellidom ="'.$nb_apellidom.'",
				num_edad ="'.$num_edad.'",
				mail ="'.$mail.'"
				WHERE id="'.$id.'"';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ActualizarUsuario

		function _ActualizarRaspberry($Parametros)
		{
			//Tomando los datos
			$keyx 			 = $Parametros['keyx']; 
			$num_id 		 = $Parametros['num_id'];
			$nom_descripcion = $Parametros['nom_descripcion']; 
			$id_sitio 		 = $Parametros['id_sitio'];
			$sn_activo 		 = $Parametros['sn_activo'];
			
			//Buscando la raspberry por id.
			$raspberry 					= R::load('equiposrasp', $keyx);
			$raspberry->num_id 			= $num_id;
			$raspberry->nom_descripcion = $nom_descripcion;
			$raspberry->id_sitio 		= $id_sitio;
			$raspberry->sn_activo 		= $sn_activo;
			//Editando los campos de la raspberry.
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

		}//_ActualizarRaspberry

		function _EliminarRaspberry($id)
		{
			$rasp = R::getRow("SELECT id FROM equiposrasp where num_id=?",[$id]);
			$id_rasp = $rasp['id'];
			$raspberry 			  = R::load('equiposrasp', $id_rasp);
			$raspberry->sn_activo = 0;
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

		}//_EliminarRaspberry

	}//Consultar

?>