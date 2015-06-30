<?php
/*
	Este archivo .php se utiliza para obtener el nombre de los empleados.
	Recibe como parametro el numero del empleado y retorna un arreglo con el nombre
	separado por espacio en blanco (" ").
	
*/

//Obtiene la direccion del web service que consulta la base de datos.
require 'cnf.db.php';

function getDatosEmpleado($num_empleado)
{
	try
	{
		$client = new SoapClient(URL_WS_CONSULTA_NODO);
			if($client) {			
				$req 					= new stdClass();
				$req->num_empleado 		= $num_empleado;
				
				$res = @$client->consultaPorEmpleado($req);
				
				if($res)
				{
					$empleado['nom_empleado']		= $res->nombre;
					$empelado['nom_apellidopaterno']= $res->apellidopaterno;
					$empleado['nom_apellidomaterno']= $res->apellidomaterno;
					$empleado['fechanacimiento']	= $res->fechanacimiento;
					$empleado['rfcempleado']		= $res->rfcempleado;
					$empleado['numerocentro']		= $res->numerocentro;
					$empleado['nombrecentro']		= $res->nombrecentro;
					$empleado['num_gerente']		= $res->num_gerente;
					$empleado['num_seccion']		= $res->num_seccion;
					$empleado['numeropuesto']		= $res->numeropuesto;
					$empleado['nombrepuesto']		= $res->nombrepuesto;
					$empleado['empresa']			= $res->empresa;
					$empleado['num_ciudad']			= $res->num_ciudad;
					$empleado['nom_ciudad']			= $res->nom_ciudad;
					$empleado['nom_ciudadinicial']	= $res->nom_ciudadinicial;
					$empleado['num_region']			= $res->num_region;
					$empleado['nom_region']			= $res->nom_region;
					$empleado['gerente']			= $res->gerente;
					$empleado['fechaalta']			= $res->fechaalta;
					$empleado['sexo']				= $res->sexo;
					
					return $res;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
	}
	catch (Exception $e) {
		return false;
	}
}

//Funcion que recibe como parametro el numero del empleado y retorna el nombre del mismo separado en palabras.
function getDatosEmpleadosXCentro($num_centro) {
	try {
		$client = new SoapClient(URL_WS_CONSULTA_NODO);
		if($client) {
			
			$req 					= new stdClass();
			$req->num_centro 		= $num_centro;
		

			$res = @$client->consultaPorCentro($req);
			if($res) {				
				$empleados=array();
				foreach($res->empleado as $preEmpleado)
				{					
					if($preEmpleado) {
						$empleado['num_empleado']		= $preEmpleado->numero;
						$empleado['nom_empleado']		= $preEmpleado->nombre;
						$empelado['nom_apellidopaterno']= $preEmpleado->apellidopaterno;
						$empleado['nom_apellidomaterno']= $preEmpleado->apellidomaterno;
						$empleado['fechanacimiento']	= $preEmpleado->fechanacimiento;
						$empleado['rfcempleado']		= $preEmpleado->rfcempleado;
						$empleado['numerocentro']		= $preEmpleado->numerocentro;
						$empleado['nombrecentro']		= $preEmpleado->nombrecentro;
						$empleado['num_gerente']		= $preEmpleado->num_gerente;
						$empleado['num_seccion']		= $preEmpleado->num_seccion;
						$empleado['numeropuesto']		= $preEmpleado->numeropuesto;
						$empleado['nombrepuesto']		= $preEmpleado->nombrepuesto;
						$empleado['emp_cancelado']		= $preEmpleado->emp_cancelado;
						$empleado['empresa']			= $preEmpleado->empresa;
						$empleado['num_ciudad']			= $preEmpleado->num_ciudad;
						$empleado['nom_ciudad']			= $preEmpleado->nom_ciudad;
						$empleado['nom_ciudadinicial']	= $preEmpleado->nom_ciudadinicial;
						$empleado['num_region']			= $preEmpleado->num_region;
						$empleado['nom_region']			= $preEmpleado->nom_region;
						$empleado['gerente']			= $preEmpleado->gerente;
						$empleado['fechaalta']			= $preEmpleado->fechaalta;
						$empleado['sexo']				= $preEmpleado->sexo;
						
						
						$empleados[]=$empleado;
					}
				}
				return $empleados;				
			}
			else
				return false;
		}
		else
			return false;
	}
	catch (Exception $e) {
		return false;
	}
}
?>