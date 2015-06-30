<?php 
	class Consultar
	{
		function _ConsultarUsuarios()
		{
			$query    = 'SELECT * FROM usuarios  where sn_activo =1 ORDER BY id ASC';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ConsultarUsuarios

		function _ConsultarUsuarioPorId($id)
		{
			$query    = 'SELECT * FROM usuarios  where sn_activo =1 and id="'.$id.'"';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ConsultarUsuarioPorId

		function _ConsultarRaspberries()
		{
			$conexion = new conexion();
		 	$con = $conexion->_con();
			
			$query = '
				SELECT 
				rasp.num_id,nom_descripcion,
				rasp.fec_alta::date as fec_alta,
				rasp.id_sitio,
				rasp.sn_activo,
				rasp.keyx,
				rasp.id,
				sit.file_system
				FROM equiposrasp rasp
				INNER JOIN sitios sit 
				ON sit.num_id = rasp.id_sitio
				WHERE rasp.sn_activo = 1 ORDER BY id ASC
			';
			$resultado = $con->query($query);
			return $resultado;
		}//_ConsultarRaspberries

		function _ConsultarRaspberryPorId($id)
		{
			$raspberry = R::getRow("select * from equiposrasp where num_id='$id'");
			R::close();
			return $raspberry;
		}//_ConsultarRaspberryPorId

		function _ConsultarOpciones()
		{
			
			$sitios = R::getAll('SELECT num_tipo, descripcion FROM cat_tipocaptura;');
			R::close();
			return $sitios;
		}//_ConsultarSitiosRaspberry

		function _ConsultarRaspnumId($id)
		{
			$raspberry = R::getRow("select * from equiposrasp where num_id='$id'");
			R::close();

			return $raspberry;
		}//_ConsultarRaspnumId

		function _ConsultaLogin($Parametros)
		{
			$user = $Parametros['usuario'];
			$pass = $Parametros['password'];
			$raspberry = R::getRow("select * from usuariosrasp where nb_nombre='$user' and pw_password='$pass'");
			R::close();
			return $raspberry;
		}//_ConsultaLogin

		function _ConsultarMigasPan($Parametros,$empleadoslista)
		{
			//Tomando los valores.
			$cantidad	 = $Parametros['cantidad'];
			$opcion   	 = $Parametros['opcion'];
			$fechainicio = $Parametros['fechainicio'];
			$fechafinal  = $Parametros['fechafinal'];

			//Definiendo el query
			$query =	'SELECT cantidad AS punteos, COUNT(1) AS cantidad FROM (
							SELECT COUNT(1) AS cantidad 
							FROM logs_gps 
							WHERE 
								fec_alta BETWEEN ? AND ?
								AND gps_fix = 1
								AND num_tipo = ?
								and num_empleado in ('.$empleadoslista.')
							GROUP BY num_latitud, num_longitud, num_empleado
							HAVING COUNT(1) > ?
							ORDER BY cantidad DESC) AS punteos GROUP BY cantidad ORDER BY cantidad DESC;';
			$puntos = R::getAll($query,[$fechainicio,$fechafinal,$opcion,$cantidad]);
			R::close();
			return $puntos;
		}//_consultarMigasPan

		function _ConsultarDetallesMigas($Parametros,$empleadoslista)
		{
			//Tomando los valores.
			$cantidad	 = $Parametros['cantidad'];
			$opcion   	 = $Parametros['opcion'];
			$fechainicio = $Parametros['fechainicio'];
			$fechafinal  = $Parametros['fechafinal'];
			$tipo        = ($opcion!="99")?"AND num_tipo = ?":" ";
			//Definiendo el query
			$query = 'SELECT COUNT(1) AS cantidad, num_latitud, num_longitud, num_empleado 
					  FROM logs_gps 
					  WHERE 
						  fec_alta BETWEEN ? AND ?
						  AND gps_fix = 1 
						  '.$tipo.'
						  AND num_empleado !=0
						  and num_empleado in ('.$empleadoslista.')
						  GROUP BY num_latitud, num_longitud, num_empleado 
						  HAVING COUNT(1) > ?
					  	ORDER BY cantidad DESC;';	

			if($opcion!="99")
			{$puntosd = R::getAll($query,[$fechainicio,$fechafinal,$opcion,$cantidad]);}		  	
			else{$puntosd = R::getAll($query,[$fechainicio,$fechafinal,$cantidad]);}
			R::close();
			return $puntosd;
		}//_consultarMigasPan

		function _ConsultarPuntosEmpleados($num_empleado,$fecha,$tipo)
		{
			$query = '
			SELECT num_latitud, num_longitud,COUNT(1) AS cantidad
			FROM logs_gps 
			WHERE 
			fec_alta::date = ?
			and fec_punteo::date = ?
			AND gps_fix = 1 
			AND num_tipo =  ?
			AND num_empleado = ?
			GROUP BY fec_punteo,num_latitud,num_longitud 
			ORDER BY fec_punteo,num_latitud 
			';
			$puntosd = R::getAll($query,[$fecha,$fecha,$tipo,$num_empleado]);
			R::close();
			return $puntosd;
		}

		function _ConsultarPuntosEmpleados2	($num_empleado,$fecha,$tipo)
		{
			$query = '
			SELECT COUNT(1) AS cantidad, num_latitud, num_longitud, num_empleado ,fec_alta::date,num_tipo,fec_punteo::date
			FROM logs_gps 
			WHERE 
			fec_alta::date = ?
			and fec_punteo::date = ? 
			AND gps_fix = 1 
			AND num_tipo = ?
			AND num_empleado = ?
			GROUP BY fec_alta::date,num_latitud, num_longitud, num_empleado,num_tipo,fec_punteo::date 
			ORDER BY cantidad DESC, fec_punteo::date DESC;
			';
			$puntosd = R::getAll($query,[$fecha,$fecha,$tipo,$num_empleado]);
			R::close();
			return $puntosd;
		}
		
		function _ConsultarDetallesCoordenadas($latitud,$longitud,$opcion)
		{
			$cliente = "";
			if($opcion!=0)
			{
				$cliente = "num_cliente != 0 AND ";
			}else {}
			//Definiendo el query
			$query = 'select num_latitud, num_longitud, num_empleado, fec_alta, num_cliente, fec_punteo, num_tipo 
						FROM logs_gps 
						WHERE '.$cliente.'
						num_latitud = ? 
						AND num_longitud = ?
						ORDER BY fec_punteo;';

			$detalles = R::getAll($query,[$latitud,$longitud]);
			R::close();
			return $detalles;
		}//_ConsultarDetallesCoordenadas

		function _ConsultarDetallesCoordenadaFecha($latitud,$Longitud,$fec_inicio,$fec_fin,$num_empleado,$opcion)
		{
			$tipo        = ($opcion!="99")?"AND gps.num_tipo = ?":"";
			$query = "  select gps.num_latitud, gps.num_longitud, gps.num_empleado, gps.fec_alta::date, gps.num_cliente, substring(gps.fec_punteo::bpchar,1,10) as fec_punteo, gps.num_tipo,
						substring(gps.fec_punteo::bpchar,12,8) as hora_punteo,
						cat.num_tipo, cat.descripcion 
						FROM logs_gps gps
						inner join cat_tipocaptura cat on cat.num_tipo = gps.num_tipo
						WHERE 
						num_latitud = ?
						AND num_longitud = ?
						and num_empleado = ?
						and fec_alta BETWEEN ? AND ?
						".$tipo."
						ORDER BY fec_punteo,hora_punteo,gps.num_cliente;
						";
			if($opcion!=99)
			{$detalles = R::getAll($query,[$latitud,$Longitud,$num_empleado,$fec_inicio,$fec_fin,$opcion]);}
			else{$detalles = R::getAll($query,[$latitud,$Longitud,$num_empleado,$fec_inicio,$fec_fin]);}
			R::close();
			return $detalles;
		}//_ConsultarDetallesCoordenadaFecha

	}//Consultar

?>