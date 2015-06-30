
angular.module('appArbol',['ngRoute','ui.bootstrap','tools','angularUtils.directives.dirPagination'])

	.controller('Arbolito',function($scope,$http,$toolsfactory){
    		
    		//Abriendo el logout.
    		$("#logout").click(function(){
    			$('.dropdown-toggle').dropdown()
    		});

        $(".Navegacion li").removeClass("active"); // removiendo active a cualquier otro item
        $("#puntos").addClass("active"); // Añadiendo active a la sección deseada

			// Variables 
        $scope.opciones= {};
        $scope.mostrarbuscando = false; //Muestra la animación de cargar.s  
        $scope.currentPage = 1; // Página actual, para paginación
        $scope.pageSize = 5;   // Tamaño de la página, para paginación.

         
   
   		//Obteniendo la información de la pantalla principal+
   		Arr 		 		= new Object();
		Arr['Accion'] 		= "InfoBusqueda";
		params 				= JSON.stringify(Arr);
	      var url = 'modulos/cobranza/funciones.php';
	       $http({
	      method: "post",	
		      url: url,
		      data: $.param({params:params}), 
		      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
	     	})
	       .success(function(data, status, headers, config) 
	       { 
	       		console.log($scope.opciones=data.opciones);
	      	})	
	       .error(function(data, status, headers, config) 
	       	{
	       		$toolsfactory.CambiarColorAlerta(2,status);
	        	
	      	});

      $scope.pageChangeHandler = function(num) {
          console.log('meals page changed to ' + num);
        }
    	$scope.fecha = function(fecha)	
    	 {
    	 	id = "#"+fecha;
    	 	console.log(id);
    	 	$(id).datepicker('show');
    	 }

    	 $scope.BuscarInformacion = function()
    	 {
            //Activando la variale para mostrar la animación de cargando.
            $scope.mostrarbuscando = true;
            $scope.mostrarPuntos   = false;
            $scope.marker          = null;
            //Ingresando la fechas al objeto registro.
            $scope.registro.fechainicio = $("#fh_desde").val();
            $scope.registro.fechafinal  = $("#fh_hasta").val();
    	 	
            //Mandando por ajax el objeto.
            //Creando el archivo params, para enviarlo por AJAX.
                Arr                = new Object();
                Arr['Accion']      = "BuscarMigasPan";
                Arr['cantidad']    = $scope.registro.cantidad;
                Arr['opcion']      = $scope.registro.opcion;
                Arr['fechafinal']  = $scope.registro.fechafinal;
                Arr['fechainicio'] = $scope.registro.fechainicio;
                params             = JSON.stringify(Arr);

                //Se mandan los datos por AJAX a la Base de datos.
                var url = 'modulos/cobranza/funciones.php';
               $http({
              method: "post",
                  url: url,
                  data: $.param({params:params}), 
                  headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
               .success(function(data, status, headers, config) 
               {
                    // Obteniendo los datos, ocultando el loading y mostrando
                    // el contenido.
                    $scope.empleadostotal  = data.empleadosTotales;
                    $scope.puntos          = data.puntos;
                    $scope.migas           = data.migas;
                    $scope.mostrarbuscando = false;
                    $scope.mostrarPuntos   = true;
                    
                })
               .error(function(data, status, headers, config) 
                {
                    Metodos.Alerta(" Usuario no agregado, Un error ocurrió",2);
                });

    	 }//BuscarInformacion

    	 $scope.OnlyNumer = function(ev)
    	 {
    	 	 charCode = ev.keyCode;
    	 	  if (charCode > 31 && (charCode < 48 || charCode > 57))
    	 	  {
	     		$scope.registro.cantidad = "";
    		  }
    		 
    	 }

         $scope.MostrarMapa = function(latitud,longitud,nombre,apaterno,amaterno)
         {
            var xmarker = null;
            var xmap    = null;
            var mapOptions = {
            zoom: 4,center: new google.maps.LatLng(24.8463705, -107.3985812),mapTypeId: google.maps.MapTypeId.TERRAIN   
            };
            xmap = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);



            $scope.nombrem    = nombre;
            $scope.apaternom  = apaterno;
            $scope.amaternonm = amaterno;
            $("#modalmapa").modal("show");
            $('#modalmapa').on('shown.bs.modal', function() {
                  var currentCenter = xmap.getCenter();  // Get current center before resizing
                  google.maps.event.trigger(xmap, "resize");
                   // Re-set previous center
                  //pegando el marker

                    var myLatlng = new google.maps.LatLng(latitud,longitud);
                    xmap.setCenter(myLatlng);
                    xmarker = new google.maps.Marker({position: myLatlng, title:"Hello World!",map: xmap, });
                    xmarker.setMap(xmap);
                    xmap.setZoom(17);

                });
         }//MostrarMapa

         $scope.Detalles = function(latitud,longitud,opcion,empleado,nombre,apellidop,apellidom)
         {
                // datos para pintar en la vista.
                $scope.nombred     = nombre;
                $scope.apellidopd  = apellidop;
                $scope.apellidomd  = apellidom;
                $scope.empleadod   = empleado;
                $scope.longitudd   = longitud;
                $scope.latitudd    = latitud;

                Arr['Accion']      = "DetallesCoordenadasEmpleado";
                Arr['latitud']     = latitud ;
                Arr['longitud']    = longitud;
                Arr['opcion']      = opcion;
                Arr['empleado']    = empleado;
                Arr['fec_inicio']  = $scope.registro.fechainicio;
                Arr['fec_fin']     = $scope.registro.fechafinal;
                params             = JSON.stringify(Arr);

                //Se mandan los datos por AJAX a la Base de datos.
                var url = 'modulos/cobranza/funciones.php';
               $http({
              method: "post",
                  url: url,
                  data: $.param({params:params}), 
                  headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
                })
               .success(function(data, status, headers, config) 
               {
                    console.log(data.detalles);
                    $scope.detallecoordenadas = data.detalles;
                    $("#modaldetalle").modal("show");
                })
               .error(function(data, status, headers, config) 
                {
                    Metodos.Alerta(" Usuario no agregado, Un error ocurrió",2);
                });
         }//Detalles

        
 	 })/*Usuarios*/

    .controller('OtherController', function($scope){
    // Variables


   $scope.pageChangeHandler = function(num) {
    console.log('going to page ' + num);
  };
})