
angular.module('AppTracking',['ngRoute','ui.bootstrap','tools','angularUtils.directives.dirPagination','Methods'])

	.controller('Tracking',function($scope,$http,$toolsfactory,$location){
    		
            $(".Navegacion li").removeClass("active");
            $("#track").addClass("active"); // Añadiendo active a la sección deseada

    		//Abriendo el logout.
    		$("#logout").click(function(){
    			$('.dropdown-toggle').dropdown()
    		});
		  
        //Variables 
        $scope.mostrarbuscando = true;
        $scope.tablamostrar    = false;
        $scope.currentPage     = 1; // Página actual, para paginación
        $scope.pageSize        = 5;   // Tamaño de la página, para paginación.

        //Buscando a los empleados de ese centro.
        //Obteniendo la información de la pantalla principal+
        Arr           = new Object();
        Arr['Accion'] = "InfoEmpleadosTracking";
        params        = JSON.stringify(Arr);
        var url = 'modulos/cobranza/funciones.php';
         $http({method: "post",url: url,data: $.param({params:params}), 
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
         .success(function(data, status, headers, config) 
         { 
            $scope.mostrarbuscando = false;
            $scope.empleados       = data.empleados;
            $scope.tablamostrar    = true;
            console.log($scope.empleados);
          })  
         .error(function(data, status, headers, config){$toolsfactory.CambiarColorAlerta(2,status);});

        /* se va a mostrar num empleado, nombre, puesto, cantidad de puntos totales sin 
        distinción de tipo y la acción*/
        $scope.Mapa = function(empleado)
        {
          $location.path('/Mapa').search({empleado: empleado});
        }//Mapa

 	 })/*Usuarios*/

.controller('Mapa',function($scope,$http,$toolsfactory,$location,$routeParams,$methodsService){
    //Variables
    $scope.empleado         = $routeParams.empleado;
    $scope.markers          = [];
    $scope.mostrarbuscando  = false;
    $scope.mostrarmapa      = true;
    $scope.contadorpuntos   = 0;
    $scope.promediolatitud  = 0;
    $scope.promediolongitud = 0;
    $scope.cantidadmarkers  = 0;
    $scope.mostrarbtnruta   = false;
    $scope.contenedorlatlng = []; // Contenedor de objetos latlng de BingMaps

    // Funciones.
    $scope.fecha = function(fecha)  
       {
        id = "#"+fecha;
        console.log(id);
        $(id).datepicker('show');
       }

     $scope.BuscarInformacion = function()
     {
        //verificando si hay ppuntos ene l mapa para limpiar.
        /*if($scope.markers.length>0)
        {
            //Eliminando los markers
            $scope.cleanmap();
        }*/

        $scope.mostrarbuscando = true;
        $scope.mostrarmapa     = false;
        $scope.registro.fechainicio = $("#fh_desde").val();
        Arr                = new Object();
        Arr['Accion']      = "BuscarInformacionEmpleado";
        Arr['opcion']      = $scope.registro.opcion;
        Arr['fechainicio'] = $scope.registro.fechainicio;
        Arr['empleado']    = $scope.empleado;
        params             = JSON.stringify(Arr);
        
        // Buscando la información del empleado 
        var url = 'modulos/cobranza/funciones.php';
       $http({method: "post",url: url,data: $.param({params:params}), 
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
        })
       .success(function(data, status, headers, config) 
       { 
            console.log(data);
           $scope.puntos          = data.puntos;
           cantidad               = $scope.puntos.length;
           LatitudP               = data.LatitudP;
           LongitudP              = data.LongitudP;
           testLocations          = [];
           for(i=0; i<cantidad;i++)
           {
              //Tomando los datos de latitud y longitud.
              var latpnt = $scope.puntos[i].num_latitud; 
              var lonpnt = $scope.puntos[i].num_longitud; 
              var msfpnt = new Microsoft.Maps.Location(latpnt,lonpnt)
              testLocations.push(msfpnt);
           }//forr

           //Pintando las líneas en el mapa
           map.entities.clear();

              //Create route options
              var options = {
                  avoid: null,
                  distanceBeforeFirstTurn: 0,
                  heading: null,
                  optimize: 'time',
                  routePathOutput: 'Points',
                  travelMode: 'Driving',
                  distanceUnit: 'km',
                  culture: null,
                  batchSize: 15
              };
            map.getCredentials(function (key) {
              console.log("algo");
              console.log(key);
                RouteServiceHelper.GetRoute(testLocations, key, options, RouteCallback);
            });
            console.log($scope.contenedorlatlng);
            $scope.mostrarbuscando = false;
            $scope.mostrarmapa     = true;
            $scope.mostrarbtnruta  = true;
        })  
       .error(function(data, status, headers, config) 
        {
            $toolsfactory.CambiarColorAlerta(2,status);
            
            });
     }//BuscarInformacion       

    //Inicializando componentes
    $(".Navegacion li").removeClass("active"); // removiendo active a cualquier otro item
    $("#track").addClass("active"); // Añadiendo active a la sección deseada

    //Mapa de bing
    var map,
    bingMapsKey = "AqscVZuLPoHLGpSlbsWsSBsrMKQG4oqda5w2yUs1QNJjntDzqTi775O14-uX_D5s";
    // Initialize the map
    map = new Microsoft.Maps.Map(document.getElementById("myMap"), { credentials: bingMapsKey });

    //Register and load the RouteServiceHelper Module
    Microsoft.Maps.registerModule("RouteServiceHelper", "scripts/RouteServiceHelper.js");
    Microsoft.Maps.loadModule("RouteServiceHelper");

              

    //opciones de selección
    //Obteniendo la información de la pantalla principal+
        Arr                 = new Object();
        Arr['Accion']       = "InfoBusqueda";
        params              = JSON.stringify(Arr);
          var url = 'modulos/cobranza/funciones.php';
           $http({method: "post",url: url,data: $.param({params:params}), 
              headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
            })
           .success(function(data, status, headers, config) 
           { 
                $scope.opciones = data.opciones;
                //console.log($scope.opciones);
            })  
           .error(function(data, status, headers, config) { $toolsfactory.CambiarColorAlerta(2,status);});
    function RouteCallback(result) {
              if (result && result.statusCode == 200 &&
                    result.resourceSets &&
                    result.resourceSets.length > 0 &&
                    result.resourceSets[0].resources &&
                    result.resourceSets[0].resources.length > 0) {

                  

                  var res = result.resourceSets[0].resources[0];

                  //Set map view based on bounding box
                  map.setView({ bounds: Microsoft.Maps.LocationRect.fromEdges(res.bbox[2], res.bbox[1], res.bbox[0], res.bbox[3]), padding : 20 });

                  //Draw route path
                  if (res.routePath && res.routePath && res.routePath.line
                        && res.routePath.line.coordinates
                        && res.routePath.line.coordinates.length >= 2) {
                      var p = [],
                      c = res.routePath.line.coordinates;

                      for (var i = 0; i < c.length; i++) {
                          p.push(new Microsoft.Maps.Location(c[i][0], c[i][1]));
                      }

                      map.entities.push(new Microsoft.Maps.Polyline(p, { strokeColor: new Microsoft.Maps.Color(156, 0, 0, 255) }));
                  }

                  //Draw Waypoints
                  for (var i = 0; i < res.routeLegs.length; i++) 
                  {
                      map.entities.push(new Microsoft.Maps.Pushpin(
                          new Microsoft.Maps.Location(res.routeLegs[i].actualStart.coordinates[0], res.routeLegs[i].actualStart.coordinates[1]), {
                            text : i + 1 + ''
                          }));
                    }//for

                    map.entities.push(new Microsoft.Maps.Pushpin(
                          new Microsoft.Maps.Location(res.routeLegs[res.routeLegs.length - 1].actualEnd.coordinates[0], res.routeLegs[res.routeLegs.length - 1].actualEnd.coordinates[1]), {
                              text: res.routeLegs.length + 1 + ''
                          }));
              } else if (result && result.statusCode != 200) {
                    
              }
          }//RouteCallback       
})