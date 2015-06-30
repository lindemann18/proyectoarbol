
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
    $scope.empleado = $routeParams.empleado;
    $scope.markers  = [];
    $scope.mostrarbuscando  = false;
    $scope.mostrarmapa      = true;
    $scope.contadorpuntos   = 0;
    $scope.promediolatitud  = 0;
    $scope.promediolongitud = 0;
    $scope.cantidadmarkers  = 0;
    $scope.mostrarbtnruta   = false;
    $scope.contenedorlatlng = []; // Contenedor de objetos latlng de google maps
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
                $scope.opciones=data.opciones;
                console.log($scope.opciones);
            })  
           .error(function(data, status, headers, config) 
            {
                $toolsfactory.CambiarColorAlerta(2,status);
                
            });

    // Datos de mapas
            var mapOptions = { zoom: 4, center: new google.maps.LatLng(24.8463705, -107.3985812) };
            map        = new google.maps.Map(document.getElementById('map_canvas'),mapOptions);

            google.maps.event.addListener(map, 'click', function(event) {
                //Pegando el marcador
                mapZoom       = map.getZoom(17);
               
               //ReverseGeocodingLatLng(latitud,Longitud);
            });

    $scope.mostrarbuscando = false; //Muestra la animación de cargar.s  

    $scope.fecha = function(fecha)  
     {
        id = "#"+fecha;
        console.log(id);
        $(id).datepicker('show');
     }

     $scope.BuscarInformacion = function()
     {

        //verificando si hay ppuntos ene l mapa para limpiar.
        if($scope.markers.length>0)
        {
            //Eliminando los markers
            $scope.cleanmap();
        }

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
           $scope.puntos = data.puntos;
           cantidad = $scope.puntos.length;
           $scope.cantidadmarkers = cantidad;
           LatitudP  = data.LatitudP;
           LongitudP = data.LongitudP;
           for(i=0; i<cantidad;i++)
           {
              latitud  = $scope.puntos[i].num_latitud;
              longitud = $scope.puntos[i].num_longitud;
              cantidadm = $scope.puntos[i].cantidad;
              $scope.promediolatitud  = $scope.promediolatitud+latitud;
              $scope.promediolongitud = $scope.promediolongitud+longitud;
              $scope.marker(latitud,longitud,map,cantidad,LongitudP,LatitudP,cantidadm);
           }//forr

           
            $scope.mostrarbuscando = false;
            $scope.mostrarmapa     = true;
            $scope.mostrarbtnruta  = true;
        })  
       .error(function(data, status, headers, config) 
        {
            $toolsfactory.CambiarColorAlerta(2,status);
            
            });
     }//BuscarInformacion

     $scope.marker = function(latitud,longitud,map,cantidad,LongitudP,LatitudP,cantidadm)
     {
        if(latitud !="" && longitud!="")
        {
            //Definiendo la imagen de marker.
            markerimg = $methodsService.Marcador($scope.registro.opcion);
             $scope.contadorpuntos++;
            //Definiendo el contenido de los markers.
            var contentString = '<div class="col-md-12"><h3 class="text-center">Puntos:'+cantidadm+'</h3></div>';

            var infowindow = new google.maps.InfoWindow({
                content: contentString
            });

           
            var myLatlng = new google.maps.LatLng(latitud,longitud); //Definiendo el objeto latlng // guardando el objeto latlng en el arreglo.
            $scope.contenedorlatlng.push(myLatlng); // guardando el objeto latlng en el arreglo.
            latlngp      = new google.maps.LatLng(LatitudP,LongitudP);
            map.setCenter(latlngp);
            var marker = new google.maps.Marker({position: myLatlng, title:"Hello World!",map: map,icon:markerimg,animation: google.maps.Animation.DROP, });
            //Definiendo comportamiento del marker
            $scope.markers.push(marker) // Metiendo el marker al arreglar
            marker.setMap(map);         // Poniendo el marker en el mapa
            //ASignando el comportamiento al marker.
            google.maps.event.addListener(marker, 'click', function() {infowindow.open(map,marker);});
            map.setZoom(17);
        }

        if(cantidad==$scope.contadorpuntos)
        {
          //Aquí se crea el recorrido de la linea
         /* var flightPlanCoordinates = $scope.contenedorlatlng;
          //definiendo el objeto de la linea y pinmtando
            var flightPath = new google.maps.Polyline({
              path: flightPlanCoordinates,
              geodesic: true,
              strokeColor: '#FF0000',
              strokeOpacity: 1.0,
              strokeWeight: 2
            });

            flightPath.setMap(map);*/
            

        }//if
        
     }//marker

    //Funciones

    $scope.RutaTrazada = function ()
    {
        //Definiendo el mapa de bing.
        bingMapsKey = "AqscVZuLPoHLGpSlbsWsSBsrMKQG4oqda5w2yUs1QNJjntDzqTi775O14-uX_D5s";
        mapbing = new Microsoft.Maps.Map(document.getElementById("myMap"), { credentials: bingMapsKey });
        //Register and load the RouteServiceHelper Module
        Microsoft.Maps.registerModule("RouteServiceHelper", "scripts/RouteServiceHelper.js");
        Microsoft.Maps.loadModule("RouteServiceHelper");
        mapbing.entities.clear();
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
        var testLocations = [
            new Microsoft.Maps.Location(24.758981,-107.421023),
            new Microsoft.Maps.Location(24.758506,-107.420991),
            new Microsoft.Maps.Location(24.758506,-107.420925),
            new Microsoft.Maps.Location(24.758486,-107.421536),
            new Microsoft.Maps.Location(24.758598,-107.421626),
            new Microsoft.Maps.Location(24.758663,-107.421543),
            new Microsoft.Maps.Location(24.758608,-107.42177),
            new Microsoft.Maps.Location(24.758495,-107.42235),
            new Microsoft.Maps.Location(24.758545,-107.42236),
            new Microsoft.Maps.Location(24.758521,-107.422475),
            new Microsoft.Maps.Location(24.758586,-107.4226),
            new Microsoft.Maps.Location(24.758558,-107.422776),
            new Microsoft.Maps.Location(24.75848,-107.422735),
            new Microsoft.Maps.Location(24.758523,-107.423306),
            new Microsoft.Maps.Location(24.758425,-107.423511),
            new Microsoft.Maps.Location(24.758406,-107.423896),
            new Microsoft.Maps.Location(24.758343,-107.424671),
            new Microsoft.Maps.Location(24.759006,-107.422813),
            new Microsoft.Maps.Location(24.75895,-107.422856),
            new Microsoft.Maps.Location(24.75893,-107.422748) 
                    ];
         mapbing.getCredentials(function (key) {
                RouteServiceHelper.GetRoute(testLocations, key, options, RouteCallback);
            });       
        $("#modaldetalle").modal("show");
    }// RutaTrazada

    $scope.cleanmap = function()
    {
        for(var i = 0; i < $scope.markers.length; i++) 
        {
          $scope.markers[i].setMap(null);
        }

        $scope.contenedorlatlng = [];
         
    }//cleanmap

    $scope.CentroMapa = function(latitud,longitud,map)
    {
        var myLatlng = new google.maps.LatLng(latitud,longitud);
        map.setCenter(myLatlng);
    }//CentroMapa

    function RouteCallback (result)
    {
      if (result && result.statusCode == 200 &&
                    result.resourceSets &&
                    result.resourceSets.length > 0 &&
                    result.resourceSets[0].resources &&
                    result.resourceSets[0].resources.length > 0) {

                  var res = result.resourceSets[0].resources[0];

                  //Set map view based on bounding box
                  mapbing.setView({ bounds: Microsoft.Maps.LocationRect.fromEdges(res.bbox[2], res.bbox[1], res.bbox[0], res.bbox[3]), padding : 20 });

                  //Draw route path
                  if (res.routePath && res.routePath && res.routePath.line
                        && res.routePath.line.coordinates
                        && res.routePath.line.coordinates.length >= 2) {
                      var p = [],
                      c = res.routePath.line.coordinates;

                      for (var i = 0; i < c.length; i++) {
                          p.push(new Microsoft.Maps.Location(c[i][0], c[i][1]));
                      }

                      mapbing.entities.push(new Microsoft.Maps.Polyline(p, { strokeColor: new Microsoft.Maps.Color(156, 0, 0, 255) }));
                  }

                  //Draw Waypoints
                  for (var i = 0; i < res.routeLegs.length; i++) 
                  {
                      mapbing.entities.push(new Microsoft.Maps.Pushpin(
                          new Microsoft.Maps.Location(res.routeLegs[i].actualStart.coordinates[0], res.routeLegs[i].actualStart.coordinates[1]), {
                            text : i + 1 + ''
                          }));
                    }//for

                    mapbing.entities.push(new Microsoft.Maps.Pushpin(
                          new Microsoft.Maps.Location(res.routeLegs[res.routeLegs.length - 1].actualEnd.coordinates[0], res.routeLegs[res.routeLegs.length - 1].actualEnd.coordinates[1]), {
                              text: res.routeLegs.length + 1 + ''
                          }));
              } else if (result && result.statusCode != 200) {
                    //document.getElementById('statusDiv').innerHTML = "Error: " + result.errorDetails;
                    console.log(result.errorDetails);
              }
    }//RouteCallback
})