<?php 
	include("libs/libs.php");
	session_start();
	if(!isset($_SESSION['usuario']))
	{
		echo '<meta http-equiv="Refresh" content="1 ;url=login.php">';
	}

	
?>
<!DOCTYPE html>
<html lang="en" ng-app="app">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Gestión Cobranza</title>

		<meta name="description" content="overview &amp; stats" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="assets/css/bootstrap.css" />
		<link rel="stylesheet" href="assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="assets/js/html5shiv.js"></script>
		<script src="assets/js/respond.js"></script>
		<![endif]-->
	</head>
<style type="text/css">
.main-content {
  margin-left: 190px;
}
</style>
	<body class="no-skin">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
		<script src="js/angular.js"></script>
		<script src="js/angular-route.min.js"></script>
		<script src="js/angular-locale_es-mx.js"></script>
		<script src="js/ui-bootstrap-tpls-0.13.0.min.js"></script>
		<script src="js/pagination.js"></script>
		<script src="js/bootstrap.js"></script>
		<script src="js/tools.js"></script>
		<script src="js/methods.js"></script>
		<script type="text/javascript" src="http://ecn.dev.virtualearth.net/mapcontrol/mapcontrol.ashx?v=7.0"></script>
		<script src="scripts/RouteServiceHelper.js"></script>
		<!-- Controlers -->
		<script src="js/arbol.js"></script>
		<script src="js/tracking.js"></script>
		<!-- final de controlers -->
		<script src="js/bootstrap-datepicker.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
		<script src="js/dirPagination.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/datepicker.css">
		<?php  include("includes/cabecera.php"); ?>
	
			<!-- Contenido -->
			<div class="main-container" id="main-container">
				<script type="text/javascript">
					try{ace.settings.check('main-container' , 'fixed')}catch(e){}
				</script>
				<?php include("includes/menu.php"); ?>
				<div ng-view class="main-content-inner">
				</div>
			</div><!-- main-container -->
		<!-- Footer -->
		

	</body>
</html>

<script type="text/javascript">
	angular.module('app',['ngRoute','ui.bootstrap','tools','appArbol','AppTracking','Methods'])
	.config(function($routeProvider)
	{
	    $routeProvider
	    .when('/',{
	      templateUrl:'modulos/inicio.html'
	    })
	    .when('/puntosr',{
	      templateUrl:'modulos/cobranza/arbolito.html'
	    })
	    .when('/Tracking',{
	      templateUrl:'modulos/cobranza/Tracking.html'
	    })
	    .when('/Mapa',{
	      	templateUrl:'modulos/cobranza/Mapa.html'
	    })
	    .otherwise({
	      redirectTo:'/'
	    });/*otherwhise*/

  	})  /*angular.module*/
.directive('a', function() {
    return {
        restrict: 'E',
        link: function(scope, elem, attrs) {
            if(attrs.toggle){
                elem.on('click', function(e){
                    e.preventDefault();
                });
            }
        }
   };
})
    	.controller('Principal',function($scope,$http){
    		$(".Navegacion li").removeClass("active");
    		//Abriendo el logout.
    		$("#logout").click(function(){
    			$('.dropdown-toggle').dropdown()
    		});

    		
 	 })/*Usuarios*/
		


		
</script>		