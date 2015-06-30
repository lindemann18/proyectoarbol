<?php 
include("../../libs/libs.php");
	
	$params 	= $_POST['params'];
	$Parametros = json_decode($params,true);
	$accion		= $Parametros['Accion'];
	session_start();

	switch ($accion) 
	{
		case 'VerificaExistenciaCentro':
			/*Verificando que el gerente ya registró el número de centro*/
			$consultar = new Consultar();
			$sesion    = $_SESSION['Sesion'];
			$usuario   = $sesion['usuario'];
			$num_centr = $sesion['numerocentro'];
			$result    = $consultar->_ConsultarDatosCobranzaNumCentro($num_centr);
			$fila	   = $result->fetchAll(PDO::FETCH_ASSOC);
			$num_rows  = $result->rowCount();
			$respuesta = array("existente"=>$num_rows);
			echo json_encode($respuesta);

		break;
		
		case 'RegistrarNumCobranza':
			/*Aquí se registra el num de la cobranza*/

			/*Tomando los datos*/	
			$num_cobranza = $Parametros['num_cobranza'];	
			$num_ciudad	  = $Parametros['num_ciudad'];
			$numerocentro = $Parametros['numerocentro'];
			$datos        = RegistrarNumCobranza($num_cobranza,$num_ciudad,$numerocentro);
			echo json_encode($datos);
		break;

		case 'ObtenerDatosCobranza':

				$datosCobranza= ObtenerDatosCobranza();
				echo json_encode($datosCobranza);
		break;

		case 'ObtenerDatosPersonal':
			$json_return = ObtenerDatosPersonal();
			echo json_encode($json_return);
		break;

		case 'VerificarCantidadAsignados':
			$json_return = VerificarCantidadAsignados();
			echo json_encode($json_return);
		break;

		case 'ObtenerDatosMoviles':
			$json_return = ObtenerDatosMoviles();
			echo json_encode($json_return);
		break;

		case 'AsignarEmpleadoResponsable':
			$json_return = AsignarEmpleadoResponsable($Parametros);
			echo json_encode($json_return);
		break;

		case 'LiberarTelefono':
			$json_return = LiberarTelefono($Parametros);
			echo json_encode($json_return);
		break;

		case 'ObtenerDatosPassword':
			$json_return = ObtenerDatosPassword($Parametros);
			echo json_encode($json_return);
		break;

		case 'DarAltaEmpleado':
			$json_return = DarAltaEmpleado($Parametros);
			echo json_encode($json_return);
		break;

		case 'AgregarPasswordEmpleado':
			$json_return = AgregarPasswordEmpleado($Parametros);
			echo json_encode($json_return);
		break;

		case 'EliminarTelefono':
			$json_return = EliminarTelefono($Parametros);
			echo json_encode($json_return);
		break;

		case 'EliminarEmpleadoPassword':
			$json_return = EliminarEmpleadoPassword($Parametros);
			echo json_encode($json_return);
		break;

		case 'PasswordEmpleado':
			$json_return = PasswordEmpleado($Parametros);
			echo json_encode($json_return);
		break;

		case 'RegistroEmpleadoLogin':
			$json_return = RegistroEmpleadoLogin($Parametros);
			echo json_encode($json_return);
		break;

	}/*switch*/

	function RegistrarNumCobranza($num_cobranza,$num_ciudad,$numerocentro)
	{
		$agregar   = new Agregar();
		$consultar = new Consultar();
		$funciones = new Funciones();
		$result    = $funciones->_RegistraOEditaNumCobranzaCiudad($num_cobranza,$num_ciudad,$numerocentro);
		$Resultado = $result->fetchall(PDO::FETCH_ASSOC);
		$result    = array("estado"=>$Resultado[0]['estado'],"msj"=>$Resultado[0]['token']); 
		$datos     = array("Resultado"=>$result);
		return $datos;
	}//RegistrarNumCobranza

	function ObtenerDatosCobranza()
	{
			$sesion 	  = $_SESSION['Sesion'];
			$num_ciudad   = $sesion['num_ciudad'];
			$numerocentro = $sesion['numerocentro'];
			$num_gerente  = $sesion['num_gerente'];
			$numpuesto    = $sesion['numpuesto'];
			//Verificar si se tiene el número de la cobranza ya puesto.
			$consultar 	  = new Consultar();
			$result       = $consultar->_ConsultarDatosCobranzaNumCentro($numerocentro);
			$fila	   	  = $result->fetchAll(PDO::FETCH_ASSOC);
			$num_rows	  = $result->rowCount();
			if($num_rows>0)
			{
				$num_cobranza = $fila[0]['num_cobranza'];
				$num_ciudad   = $fila[0]['num_ciudad'];
			}//if
			else
			{$num_cobranza = "";}	

			$datosCobranza = array("num_ciudad"=>$num_ciudad,"numerocentro"=>$numerocentro,"num_cobranza"=>$num_cobranza);
			return $datosCobranza;
	}//ObtenerDatosCobranza


	function ObtenerDatosPersonal()
	{
		include("personal3.php");
		$sesion 	  = $_SESSION['Sesion'];
		//print_r($sesion);
		$num_ciudad   = $sesion['num_ciudad'];
		$num_empleado = $sesion['usuario'];
		$num_gerente  = $sesion['num_gerente'];
		$numpuesto    = $sesion['numpuesto'];
		$num_centro    = $sesion['numerocentro'];
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
		return $contenido;
		
	}/*ObtenerDatosPersonal*/

	function VerificarCantidadAsignados()
	{
		$consultar = new Consultar();
		$resultado = $consultar->_ConsultarCantidadResponsables();
		$fila	   = $resultado->fetchAll(PDO::FETCH_ASSOC);
		$count 	   = $fila[0]['count'];
		$contenido = array("count"=>$count);
		return $contenido;
	}//VerificarCantidadAsignados

	function ObtenerDatosMoviles()
	{
		//Tomando las variables de sesión y obteniendo el número de centro
		$sesion  	   = $_SESSION['Sesion'];
		$numero_centro = $sesion['numerocentro'];
		$consultar 	   = new Consultar();

		//Haciendo el query de la clase consultar para obtener los móviles
		$result 	   = $consultar->_ConsultarMovilesPorCobranza($numero_centro);
		$fila	   	   = $result->fetchAll(PDO::FETCH_ASSOC);
		$num_rows	   = $result->rowCount();
		$moviles 	   = array();

		// Una vez habiendo obtenido los móviles se procederá a tomarlos y.
		// hacer un array con ellos para devolverlos.
		if($num_rows>0)
		{
			for($i=0; $i<$num_rows; $i++)
			{
				// Here you get all the data from the array so we make an
				// an array to return back all the phones.
				$datos_movil = $fila[$i];
				$imei 		 = $datos_movil['imei'];
				$descripcion = $datos_movil['descripcion'];
				$centro_cob  = $datos_movil['centro_cobranza'];
				$responsable = ($datos_movil['num_empleado_responsable']!="")?$datos_movil['num_empleado_responsable']:"";
				$movil 		 = array("imei"=>$imei,"descripcion"=>$descripcion,"centro_cob"=>$centro_cob,"responsable"=>$responsable);
				array_push($moviles,$movil);
			}//for
			$contenido = 1;
		} // if$num_rows>0
		else
		{
			$contenido = 0;
		}/*else*/
		
		$json_return = array("contenido"=>$contenido,"moviles"=>$moviles);
		return $json_return;
	}//ObtenerDatosMoviles

	function AsignarEmpleadoResponsable($Parametros)
	{
		$empleado = $Parametros['Empleado'];
		//asignando la fecha horaria de mazatlán y guardando la fecha
		date_default_timezone_set('America/Mazatlan');
		$fecha 	  =  date('Y-m-d h:i:s');

		// Se debe tomar el imei y despejar el contenido, dado que llega.
		// con notación científica, se convierte a número, se le quitan los 
		// 0, se quita la separación por , y se obtiene limpio el imei.
		$imei     = FormatoImei($Parametros['imei']);
		

		//Agregando los datos a la base de datos
		$agregar 	 = new Agregar();
		$consultar   = new Consultar();
		$funciones    = new Funciones();
		$sesion 	 = $_SESSION['Sesion'];
		$num_cen 	 = $sesion['numerocentro'];
		$result      = $funciones->AsignarResponsable($empleado,$imei,$fecha,$num_cen);
		$Resultado   = $result->fetchall(PDO::FETCH_ASSOC);
		$respuesta   = array("estado"=>$Resultado[0]['estado'],
							 "existente"=>$Resultado[0]['existente'],
							 "msj"=>$Resultado[0]['token']);
		$datos       = array("respuesta"=>$respuesta);  
		return $datos;
	}//AsignarEmpleadoResponsable

	function LiberarTelefono($Parametros)
	{
		$imei        = FormatoImei($Parametros['imei']);
		$funciones   = new Funciones();
		$result      = $funciones->_LiberarTelefono($imei);
		$Resultado   = $result->fetchall(PDO::FETCH_ASSOC);
		$respuesta   = array("estado"=>$Resultado[0]['estado'],
							 "existente"=>$Resultado[0]['existente'],
							 "msj"=>$Resultado[0]['token']);
		$datos       = array("respuesta"=>$respuesta);  
		return $datos;

	}//LiberarTelefono

	function ObtenerDatosPassword($Parametros)
	{
		//Tomando las variables de sesión y obteniendo el número de centro
		$sesion  	   = $_SESSION['Sesion'];
		$numero_centro = $sesion['numerocentro'];
		$consultar 	   = new Consultar();

		//Haciendo el query de la clase consultar para obtener los móviles
		$result 	   = $consultar->_ConsultarEmpleadosconPassword($numero_centro);
		$fila	   	   = $result->fetchAll(PDO::FETCH_ASSOC);
		$num_rows	   = $result->rowCount();
		$empleados 	   = array();
		// Una vez habiendo obtenido los móviles se procederá a tomarlos y.
		// hacer un array con ellos para devolverlos.
		if($num_rows>0)
		{
			$contenido = 1;
			//Tomando a los empleados que ya están registrados.
			for($i=0; $i<$num_rows; $i++)
			{
				$empleado = $fila[$i]; // tomando al empleado.
				//tomando a los datos del empleado.
				$num_empleado = $empleado['num_empleado'];
				$nb_nombre 	  = $empleado['nombre'];
				$nb_apellidop = $empleado['apellidop'];
				$nb_apellidom = $empleado['apellidom'];
				$pw_password  = ($empleado['password']);
				$pw_password  = ($pw_password==null)?"":$empleado['password'];
				$num_centro   = $empleado['num_centro'];
				$apellidos    = $nb_apellidop." ".$nb_apellidom;
				$emp_datos    = array("num_empleado"=>$num_empleado,"nb_nombre"=>$nb_nombre,
									   "apellidos"=>$apellidos,"pw_password"=>$pw_password,
									   "num_centro"=>$num_centro);
				array_push($empleados, $emp_datos);
			}//for
		} // if$num_rows>0
		else
		{
			//si se entra aquí es por que no hay empleados dados de alta.
			$contenido = 0;
		}/*else*/
		
		$json_return = array("contenido"=>$contenido,"empleados"=>$empleados);
		return $json_return;
	}//ObtenerDatosPassword

	function DarAltaEmpleado($Parametros)
	{
		include("personal3.php");
		$num_empleado = $Parametros['num_empleado'];
		$agregar   	  = new Agregar();
		$consultar    = new Consultar();
		$existente    = 0; //Variable para indicar si ya existe el empleado.
		$empleados 	  = array(); // Contenedor de los empleados.
		//Obteniendo los datos del empleado con el servicio.
		if(is_numeric($num_empleado))
		{
			// Verificando si el empleado ya existe en la BD.
			// de ser así se regresa una alerta indicando que ya existe.
			$resBusqueda  = $consultar->_ConsultarUsuarioPasswordNumEmpleado($num_empleado);
			$num_existe	  = $resBusqueda->rowCount();
			if($num_existe>0)
			{
				// si entra aquí es por que el empleado ya existe,
 				// Entonces se debe notificar al gerente que ya está
 				// dado de alta.
 				

 				// Se debe verificar, si el empleado ya existe se está re activando.
 				// por ende se le reactivará, enc aso que el sn_activo = 0, si no
 				// se está intentando dar de alta nuevamente al mismo empleado.
 				$consultar = new Consultar();
 				$resultado = $consultar->_ConsultarUsuarioPasswordNumEmpleado($num_empleado);
 				$fila	   = $resultado->fetchAll(PDO::FETCH_ASSOC);

 				// Tomando el dato sn_activo, si está en 0 se reactiva
 				// si está en 1 se manda la alerta qeu se intenta
 				// re agregar uno ya existente
 				$sn_activo = $fila[0]['estado'];

 				if($sn_activo==1)
 				{
 					$existente = 1;
 					$agregado  = 0;
 				}//if
 				else
 				{
 					
 					$actualizar = new Actualizar();
 					$resAct     = $actualizar->_ActivarEmpleadoPassword($num_empleado);
 					$existente    = 0;
					$agregado  	  = 1;
					if(is_numeric($num_empleado))
					{
						$datos 		  = getDatosEmpleado($num_empleado);
						$num_centro   = $datos['numerocentro'];

						//Tomando los empleados de ese centro.
						$empleados = ArrayUsuariosPasswordPorCentro($num_centro);
					}//if numeric
 				}//else
			}//if
			else
			{
				$existente    = 0;
				$agregado  	  = 1;
				$datos 		  = getDatosEmpleado($num_empleado);
				//Sacando los datos del arreglo.
				$nb_nombre 	  = $datos['nom_empleado'];
				$nb_apellidop = $datos['nom_apellidopaterno'];
				$nb_apellidom = $datos['nom_apellidomaterno'];
				$num_centro   = $datos['numerocentro'];
				$fecha_alta   = FechaActual();
				$empleado 	  = array("nb_nombre"=>$nb_nombre,"nb_apellidop"=>$nb_apellidop,
									  "nb_apellidom"=>$nb_apellidom,"num_centro"=>$num_centro,
									  "fecha_alta"=>$fecha_alta,"num_empleado"=>$num_empleado);

				//Agregando el empleado a base de datos
				
				$result    = $agregar->_AgregarUsuarioALista($empleado);

				// Una vez agregado el empleado se procede a devolver el 
				// arreglo actual con los empleados para actualizarlo.
				$resultem 	   = $consultar->_ConsultarEmpleadosconPassword($num_centro); 
				$fila	   	   = $resultem->fetchAll(PDO::FETCH_ASSOC);
				$num_rows	   = $resultem->rowCount();
				
				for($i=0; $i<$num_rows; $i++)
				{
					$empleado 	  = $fila[$i];
					$nb_nombre 	  = $empleado['nombre'];
					$nb_apellidop = $empleado['apellidop'];
					$nb_apellidom = $empleado['apellidom'];
					$num_centro   = $empleado['num_centro'];
					$num_empleado = $empleado['num_empleado'];
					$pw_password  = $empleado['password'];
					$pw_password  = ($pw_password==null)?"":$empleado['password'];
					$apellidos    = $nb_apellidop." ".$nb_apellidom;
					$emp_datos    = array("nb_nombre"=>$nb_nombre,"apellidos"=>$apellidos,
										  "pw_password"=>$pw_password,"num_centro"=>$num_centro,
										  "num_empleado"=>$num_empleado);
					array_push($empleados,$emp_datos);
				}//for
			}//else
			
		}//if
		$json_return = array("empleados"=>$empleados,"agregado"=>$agregado,"existente"=>$existente);
		return $json_return;
	}//DarAltaEmpleado

	function AgregarPasswordEmpleado($Parametros)
	{
		//Tomando los valores
		$pw_password  = $Parametros['pw_password'];
    	$num_empleado = $Parametros['num_empleado'];
    	$funciones    = new Funciones();
    	$result   	  = $funciones->_ActualizarPasswordEmpleado($num_empleado,$pw_password);
    	$Resultado    = $result->fetchall(PDO::FETCH_ASSOC);
		$respuesta    = array("estado"=>$Resultado[0]['estado'],
							 "msj"=>$Resultado[0]['token']);
		
		//Tomando los empleados nuevamente
		//Haciendo el query de la clase consultar para obtener los móviles
		$sesion 	   = $_SESSION['Sesion'];
		$numero_centro = $sesion['numerocentro'];
		$consultar     = new Consultar();
		$result 	   = $consultar->_ConsultarEmpleadosconPassword($numero_centro);
		$fila	   	   = $result->fetchAll(PDO::FETCH_ASSOC);
		$num_rows	   = $result->rowCount();
		$empleados 	   = array();
		// Una vez habiendo obtenido los móviles se procederá a tomarlos y.
		// hacer un array con ellos para devolverlos.
		if($num_rows>0)
		{
			$contenido = 1;
			//Tomando a los empleados que ya están registrados.
			for($i=0; $i<$num_rows; $i++)
			{
				$empleado = $fila[$i]; // tomando al empleado.
				//tomando a los datos del empleado.
				$num_empleado = $empleado['num_empleado'];
				$nb_nombre 	  = $empleado['nombre'];
				$nb_apellidop = $empleado['apellidop'];
				$nb_apellidom = $empleado['apellidom'];
				$pw_password  = ($empleado['password']);
				$pw_password  = ($pw_password==null)?"":$empleado['password'];
				$num_centro   = $empleado['num_centro'];
				$apellidos    = $nb_apellidop." ".$nb_apellidom;
				$emp_datos    = array("num_empleado"=>$num_empleado,"nb_nombre"=>$nb_nombre,
									   "apellidos"=>$apellidos,"pw_password"=>$pw_password,
									   "num_centro"=>$num_centro);
				array_push($empleados, $emp_datos);
			}//for
		} // if$num_rows>0
		else
		{
			//si se entra aquí es por que no hay empleados dados de alta.
			$contenido = 0;
		}/*else*/

    	//Actualizando la contraseña para el empleado.
    	$datos = array("respuesta"=>$respuesta,"contenido"=>$contenido,"empleados"=>$empleados);
		return $datos;
	}//AgregarPasswordEmpleado

	function EliminarTelefono($Parametros)
	{
		//Tomando los datos necesarios
		$imei 		= $Parametros['imei'];
		$imei 		= FormatoImei($imei);
		$fh_baja 	= FechaActual();
		$actualizar = new Actualizar();
		$consultar  = new Consultar();
		// Actualizando el estado 
		$resultado 	= $actualizar->_DesactivarEquipoPorImei($imei,$fh_baja);

		// Buscando los equipos restantes.
		$sesion 	= $_SESSION['Sesion'];
		$num_centro = $sesion['numerocentro'];
		$funciones  = new Funciones();
		$result     = $funciones->_EliminarTelefono($imei,$fh_baja,$num_centro);
		$Resultado  = $result->fetchall(PDO::FETCH_ASSOC);
		$cantidad   = count($Resultado);
		$equipos    = array();
		for($i=0; $i<$cantidad; $i++)
		{
			// tomando los equipos.
			$equip_info  = $Resultado[$i];
			$imeit 		 = $equip_info['imei'];
			$descripcion = $equip_info['descripcion'];
			$num_centro  = $equip_info['centro_cobranza'];
			$responsable = $equip_info['num_empleado_responsable'];
			$responsable = ($responsable!=null)?$responsable:"";
			$equipo      = array("imei"=>$imeit,"descripcion"=>$descripcion,
								 "num_centro"=>$num_centro,"responsable"=>$responsable);
			array_push($equipos,$equipo);
		}//for
		$json_return = array("Eliminado"=>1,"equipos"=>$equipos);
		return $json_return;
	}//EliminarTelefono

	function EliminarEmpleadoPassword($Parametros)
	{
		$num_empleado = $Parametros['num_empleado'];
		//Dando de baja al empleado.
		$actualizar = new Actualizar();
		$resultado  = $actualizar->_DesactivarEmpleadoPassword($num_empleado);

		//Consultando el número de centro del empleado.
		include("personal3.php");
		$datos 		  = getDatosEmpleado($num_empleado);
		$num_centro   = $datos['numerocentro'];

		//Tomando los empleados restantes.
		$consultar = new Consultar();
		$resEmp    = $consultar->_ConsultarEmpleadosconPassword($num_centro);
		$fila	   = $resEmp->fetchAll(PDO::FETCH_ASSOC);
		$num_rows  = $resEmp->rowCount();
		$empleados = array();
		// Una vez habiendo obtenido los móviles se procederá a tomarlos y.
		// hacer un array con ellos para devolverlos.
		if($num_rows>0)
		{
			$contenido = 1;
			//Tomando a los empleados que ya están registrados.
			for($i=0; $i<$num_rows; $i++)
			{
				$empleado     = $fila[$i]; // tomando al empleado.
				//tomando a los datos del empleado.
				$num_empleado = $empleado['num_empleado'];
				$nb_nombre 	  = $empleado['nombre'];
				$nb_apellidop = $empleado['apellidop'];
				$nb_apellidom = $empleado['apellidom'];
				$pw_password  = ($empleado['password']);
				$pw_password  = ($pw_password==null)?"":$empleado['password'];
				$num_centro   = $empleado['num_centro'];
				$apellidos    = $nb_apellidop." ".$nb_apellidom;
				$emp_datos    = array("num_empleado"=>$num_empleado,"nb_nombre"=>$nb_nombre,
									   "apellidos"=>$apellidos,"pw_password"=>$pw_password,
									   "num_centro"=>$num_centro);
				array_push($empleados, $emp_datos);
			}//for
		} // if$num_rows>0
		else
		{
			//si se entra aquí es por que no hay empleados dados de alta.
			$contenido = 0;
		}/*else*/
		
		$json_return = array("contenido"=>$contenido,"empleados"=>$empleados);
		return $json_return;
	}//EliminarEmpleadoPassword

	function PasswordEmpleado($Parametros)
	{
		$consultar    = new Consultar();
		$num_empleado = $Parametros['num_empleado'];
		$result 	  = $consultar->_ConsultarUsuarioPasswordNumEmpleado($num_empleado);
		$fila	   	  = $result->fetchAll(PDO::FETCH_ASSOC);
		$json_return  = array("Password"=>$fila[0]['password']);
		return $json_return;
	}//PasswordEmpleado

	function RegistroEmpleadoLogin($Parametros)
	{
		include("personal3.php");
		//Tomando los datos
		$num_empleado = $Parametros['num_empleado'];
		$pw_password  = $Parametros['pw_password'];

		// Obteniendo los datos pertinentes del empleado para el registro.

		if(is_numeric($num_empleado))
		{
			$Empleado  	= getDatosEmpleado($num_empleado);
			
			//Datos para registrar al empleado
			$fecha 		= FechaActual();
			$num_centro = $Empleado['numerocentro'];

			//Agregando a la BD
			$agregar    = new Agregar();
			$result     = $agregar->_AgregarEmpleadoCobranza($num_empleado,$pw_password,$num_centro);
			$fila	    = $result->fetchAll(PDO::FETCH_ASSOC);
			$notif      = $fila[0]['grabagerentecobranza'];

		}//if
		$json_return = array("Notificacion"=>$notif);
		return $json_return;
	}//RegistroEmpleadoLogin

	function FormatoImei($imei_nonformat)
	{
		// Se debe tomar el imei y despejar el contenido, dado que llega.
		// con notación científica, se convierte a número, se le quitan los 
		// 0, se quita la separación por , y se obtiene limpio el imei.
		$imei     = number_format($imei_nonformat,15);
		$contn    = explode(".",$imei);
		$imei     = $contn[0];
		$contn    = explode(",",$imei);
		$contador = count($contn);
		$imei     = "";
		for($i=0; $i<$contador; $i++)
		{
			$cadena = $contn[$i];
			$imei .=$cadena;
		}//for
		return $imei;
	}//FormatoImei

	function FechaActual()
	{
		//asignando la fecha horaria de mazatlán y guardando la fecha
		date_default_timezone_set('America/Mazatlan');
		$fecha 	  =  date('Y-m-d h:i:s');
		return $fecha;
	}//FechaActual

	function ArrayUsuariosPasswordPorCentro($num_centro)
	{
		$consultar 	      = new Consultar();
		$resultem 	      = $consultar->_ConsultarEmpleadosconPassword($num_centro); 
		$fila	   	      = $resultem->fetchAll(PDO::FETCH_ASSOC);
		$num_rows	      = $resultem->rowCount();
		$empleados        = array();
		for($i=0; $i<$num_rows; $i++)
		{
			$empleado 	  = $fila[$i];
			$nb_nombre 	  = $empleado['nombre'];
			$nb_apellidop = $empleado['apellidop'];
			$nb_apellidom = $empleado['apellidom'];
			$num_centro   = $empleado['num_centro'];
			$num_empleado = $empleado['num_empleado'];
			$pw_password  = $empleado['password'];
			$pw_password  = ($pw_password==null)?"":$empleado['password'];
			$apellidos    = $nb_apellidop." ".$nb_apellidom;
			$emp_datos    = array("nb_nombre"=>$nb_nombre,"apellidos"=>$apellidos,
								  "pw_password"=>$pw_password,"num_centro"=>$num_centro,
								  "num_empleado"=>$num_empleado);
			array_push($empleados,$emp_datos);
		}//for
		return $empleados;
	}//ArrayUsuariosPasswordPorCentro
 ?>