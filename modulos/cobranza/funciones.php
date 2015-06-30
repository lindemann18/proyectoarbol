<?php 
	include("../../libs/libs.php");
	include("../personal/personal3.php");
	$params 	= $_POST['params'];
	$Parametros = json_decode($params);
	$Parametros = json_decode(json_encode($Parametros),true);			
	$accion     = $Parametros['Accion'];
	$conexion   = new ConexionBean(); //Variable de conexión
	$con        = $conexion->_con(); //Variable de conexión

	switch ($accion) 
	{
		case 'LoginUsuario':
			//Dirigiendo al método correspondiente
			$datos = LoginUsuario($Parametros);
			echo json_encode($datos);
		break;
		
		case 'InfoBusqueda':
			$datos = InfoBusqueda($Parametros);
			echo json_encode($datos);
		break;

		case 'BuscarMigasPan':
			$datos = BuscarMigasPan($Parametros);
			echo json_encode($datos);
		break;

		case 'Detallescoordenadas':
			$datos = Detallescoordenadas($Parametros);
			echo json_encode($datos);
		break;

		case 'DetallesEmpleado':
			$datos = DetallesEmpleado($Parametros);
			echo json_encode($datos);
		break;

		case 'DatosSitios':
			$datos = DatosSitios($Parametros);
			echo json_encode($datos);
		break;

		case 'InfoEmpleadosTracking':
			$datos = InfoEmpleadosTracking($Parametros);
			echo json_encode($datos);
		break;

		case 'PuntosPorEmpleado':
			$datos = PuntosPorEmpleado($Parametros);
			echo json_encode($datos);
		break;

		case 'DetallesCoordenadasEmpleado':
			$datos = DetallesCoordenadasEmpleado($Parametros);
			echo json_encode($datos);
		break;
		
		case 'BuscarInformacionEmpleado':
			$datos = BuscarInformacionEmpleado($Parametros);
			echo json_encode($datos);
		break;
			
	}//switch


	function InfoBusqueda($Parametros)
	{
		//Buscando la información de las opciones.
		$consultar = new Consultar();
		$resultado = $consultar->_ConsultarOpciones();
		$todos     = array("descripcion"=>"Todos","num_tipo"=>"99");
		array_push($resultado,$todos);
		$datos = array("opciones"=>$resultado);
		return $datos;
	}//EliminarUsuario

	function BuscarMigasPan($Parametros)
	{
		// Recibiendo los parámetros. y enviando al método de consulta
		$consultar = new Consultar();

		//obteniendo los empleados del centro.
		session_start();
		$num_centro   = $_SESSION['numerocentro']; 
		$empleados 	  = getDatosEmpleadosXCentro($num_centro);
		$emp_total = array();
		foreach ($empleados as $empleado) 
		{
			if($empleado['emp_cancelado']==0 && $empleado['numeropuesto']==71)
			{
				array_push($emp_total, $empleado['num_empleado']);
			}
		}//foreach
		
		$empleadoslista = join(',',$emp_total);
		$resultado = $consultar->_ConsultarMigasPan($Parametros,$empleadoslista);
		
		//Consultando detalles de las migas de pan
		$migas = $consultar->_ConsultarDetallesMigas($Parametros,$empleadoslista);
		//print_r($migas);

		//Consultando los nombres de los empleados contenidos en el array.
		$empleadosTotales = array();

		foreach ($migas as $info) 
		{
			//Tomando los datos del empleado con el servicio del oscar.
			$num_empleado = $info['num_empleado'];
			$emp      = is_numeric($num_empleado)?getDatosEmpleado($num_empleado):0;
			$nombre   = $emp['nom_empleado'];
			$apaterno = $emp['nom_apellidopaterno'];
			$amaterno = $emp['nom_apellidomaterno'];
			
			$empleadofiltrado = array("nombre"=>$nombre,"apaterno"=>$apaterno,
									  "amaterno"=>$amaterno,"cantidad"=>$info['cantidad'],
									  "num_latitud"=>$info['num_latitud'],"num_longitud"=>$info['num_longitud'],
									  "num_empleado"=>$num_empleado);
			array_push($empleadosTotales,$empleadofiltrado);
		}//foreach

		
		$datos = array("puntos"=>$resultado,"migas"=>$migas,"empleadosTotales"=>$empleadosTotales);
		return $datos;
	}//ObtenerUsuarios

	function Detallescoordenadas($Parametros)
	{
		$consultar = new Consultar();
		$latitud   = $Parametros['latitud'];
		$longitud  = $Parametros['longitud'];
		$opcion    = $Parametros['opcion'];
		//Consultando los detalles de las coordenadas.
		$detalles  = $consultar->_ConsultarDetallesCoordenadas($latitud,$longitud,$opcion);
		$datos 	   = array("detalles"=>$detalles);
		return $datos;
	}//DatosUsuarioPorId

	function EditarUsuario($Parametros)
	{
		//Tomando los parámetros para editar.
		$id           = $Parametros['id'];
		$nb_nombre    = $Parametros['nb_nombre'];
		$nb_apellidop = $Parametros['nb_apellidop'];
		$nb_apellidom = $Parametros['nb_apellidom'];
		$num_edad     = $Parametros['num_edad'];
		$mail         = $Parametros['mail'];
		$Usuario 	  = array("id"=>$id,"nb_nombre"=>$nb_nombre,"nb_apellidop"=>$nb_apellidop,
						 	  "nb_apellidom"=>$nb_apellidom,"num_edad"=>$num_edad,"mail"=>$mail);
		$actualizar   = new Actualizar();
		$result       = $actualizar->_ActualizarUsuario($Usuario);
	}//EditarUsuario

	function DetallesEmpleado($Parametros)
	{

		$empleado = $Parametros['empleado'];
		if(is_numeric($empleado))
		{
			//Buscando la información del empleado.
			$emp  	  = getDatosEmpleado($empleado);
			$empleado = array("nombre"=>$emp['nom_empleado'],
							  "apaterno"=>$emp['nom_apellidopaterno'],
							  "amaterno"=>$emp['nom_apellidomaterno'],
							  "num_gerente"=>$emp['num_gerente'],
							  "num_centro"=>$emp['numerocentro']);
			$datos        = array("empleado"=>$empleado);
		}
		return $datos;
	}//AgregarUsuario

	function DatosSitios()
	{
		$consultar = new Consultar();
		$sitios    = $consultar->_ConsultarSitiosRaspberry();
		$datos 	   = array("sitios"=>$sitios);
		return $datos;
	}//DatosSitios

	function DetallesCoordenadasEmpleado($Parametros)
	{
		//Tomar los detalles de las coordenadas por empleado
		$consultar    = new Consultar();
		$latitud      = $Parametros['latitud'];
		$longitud     = $Parametros['longitud'];
		$fec_inicio   = $Parametros['fec_inicio'];
		$fec_fin      = $Parametros['fec_fin'];
		$num_empleado = $Parametros['empleado'];
		$opcion       = $Parametros['opcion'];
		$detalles     = $consultar->_ConsultarDetallesCoordenadaFecha($latitud,$longitud,$fec_inicio,$fec_fin,$num_empleado,$opcion);
		$datos        = array("detalles"=>$detalles);
		return $datos;
	}//DetallesCoordenadasEmpleado


	function InfoEmpleadosTracking($Parametros)
	{
		//obteniendo los empleados del centro.
		session_start();
		$num_centro   = $_SESSION['numerocentro']; 
		$empleados 	  = getDatosEmpleadosXCentro($num_centro);
		$emp_total = array();

		foreach ($empleados as $empleado) 
		{
			if($empleado['emp_cancelado']==0 )
			{
				array_push($emp_total, $empleado);
			}
		}//foreach

		//ordenando los arrays
		$puestos = array();
		foreach ($emp_total as $key => $row)
		{
		    $puestos[$key] = $row['nombrepuesto'];
		}
		array_multisort($puestos, SORT_DESC, $emp_total);

		//Convirtiéndolos en string para hacer la consulta.
		$datos = array("empleados"=>$emp_total);
		return $datos;
	}//InfoEmpleadosTracking

	function PuntosPorEmpleado($Parametros)
	{
		$num_empleado = $Parametros['id'];
		$consultar    = new Consultar();
		//Consultando 
		$puntos = $consultar->_ConsultarPuntosEmpleados($num_empleado);
	}//PuntosPorEmpleado

	function BuscarInformacionEmpleado($Parametros)
	{
		//Buscando la información del empleado
		$consultar   = new Consultar();
		$empleado    = $Parametros['empleado'];
		$fecha       = $Parametros['fechainicio'];
		$opcion      = $Parametros['opcion'];
		$puntos      = $consultar->_ConsultarPuntosEmpleados($empleado,$fecha,$opcion);
		$promedioLat = 0;
		$promedioLon = 0;

		//Obteniendo el promedio de latitud y longitud
		$cantidad = count($puntos);
		if($cantidad>0)
		{
			// Aquí se verifica que existan puntos y no se haga una división
			// Entre 0.
			for($i=0; $i<$cantidad; $i++)
			{
				$promedioLat+=$puntos[$i]['num_latitud'];
				$promedioLon+=$puntos[$i]['num_longitud'];
			}//for
			$promedioLon = $promedioLon/$cantidad;
			$promedioLat = $promedioLat/$cantidad;
		}//if

		$datos     = array("puntos"=>$puntos,"LatitudP"=>$promedioLat,"LongitudP"=>$promedioLon);
		return $datos;
	}//BuscarInformacionEmpleado

	function LoginUsuario($Parametros)
	{
		$user		  = $Parametros['usuario'];
		$larg         = strlen($user);
		$num_correcto = 0;
		$num_gerente  = 0;
		$correcto     = 0;
		if(is_numeric($user) && $larg==8)
		{
			$num_correcto = 1;
			//obteniendo los datos del empleado
			$Empleado  	  = getDatosEmpleado($user);
			$numpuesto 	  = $Empleado['numeropuesto'];
			$numerocentro = $Empleado['numerocentro'];

			//Iniciando la sesión y gaurdando los datos
			if($numpuesto==35)
			{
				$num_gerente 			  = 1;
				session_start();
				$_SESSION['usuario'] 	  = $user;
				$_SESSION['numerocentro'] = $numerocentro;
			}//if
		}//if is numeric
		else
		{
			$num_correcto = 0;
		}

		switch(true)
		{
			case $num_correcto==0:
				$error    = "Número Incorrecto";
				$alerta   = 2;
				$correcto = 0;
			break;

			case $num_gerente==0:
				$error    = "No es gerente de cobranza";
				$alerta   = 2;
				$correcto = 0;
			break;

			default:
				$error    = "Todo correcto";
				$alerta   = 1;
				$correcto = 1;
			break;
		}//switch
		$respuesta = array("error"=>$error, "alerta"=>$alerta);
		$datos = array("num_correcto"=>$num_correcto,"num_gerente"=>$num_gerente,"respuesta"=>$respuesta,"correcto"=>$correcto);
		return $datos;
	}//LoginUsuario
 ?>