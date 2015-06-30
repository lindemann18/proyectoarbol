<!DOCTYPE HTML>
<html ng-app="app" ng-controller="LoginController">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sitio RaspBerry</title>
</head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"
<!-- incluyedno scripts-->
<link rel="stylesheet" href="css/bootstrap.css">
<link rel="stylesheet" href="css/jasny-bootstrap.css">
<!-- text fonts -->
<link rel="stylesheet" href="assets/css/ace-fonts.css" />

<!-- ace styles -->
<link rel="stylesheet" href="assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
<link rel="stylesheet" href="assets/css/font-awesome.css" />
<script src="js/jquery-1.11.2.js"></script>
<script src="js/bootstrap.js"></script>
<script src="js/jasny-bootstrap.js"></script>
<script src="js/angular.min.js"></script>
<script src="js/tools.js"></script>
<style>
.jumbotron p {
  margin-bottom: 13px;
  font-size: 16px;
  font-weight: 200;
  color:red;
}
.Pad {padding: 3px !important}
	@media (min-width: @screen-md-min) and (max-width: @screen-md-max) {
		.navbar-brand {float: left;height: 50px; padding: 3px; font-size: 18px;line-height: 20px; }
		.Pad {padding: 3px !important}
	}	

	@media (min-width: @screen-lg-min) { 
		.navbar-brand {float: left;height: 50px; padding: 3px; font-size: 18px;line-height: 20px; }
		.Pad {padding: 3px !important}
	}
	
	.ContainerLogin {margin-top: 1%; margin-right: 13%; }
	.Pregunta{margin-bottom: 2%;}
	.ContainerPreguntas {}
	.ContainerButtons{margin-top: 3%;  margin-left: 4%;}
	.FondoCoppel{background-color: #005CAF;}
	.fondo {background-color: #FFF;}
	.ImagenCoppelMain {background: url(css/img/logo_coppel.jpg) no-repeat 	;
	background-size: 100%; height: 100px; margin-top: 5%; background-size: 68%;background-position: 47.5% 0%, center center;}
	.bgimage-inside {padding-top: 37.36%; /* this is actually (426/1140)*100 */}
	.backgroundlogin {background: url(css/img/coppel_icono.jpg) no-repeat;background-size: 100%; height: 50px; background-size: 17%;}
	
		/* Portrait tablets and small desktops */
		@media (min-width: 768px) and (max-width: 991px) {
			.ContainerLogin {margin-top: 1%;margin-right: 0%;}
			.ImagenCoppelMain { background-size: 61% auto; background-position: 55% 0%, center center;}
			.backgroundlogin{background-size: 7%;}	
		}
	/* Landscape phones and portrait tablets */
@media (max-width: 767px) {
		.ContainerLogin {margin-top: 1%; margin-right: 0%; .backgroundlogin{background-position: 1% 36%;}}
		.ImagenCoppelMain {
		    height: 100px;
		    margin-top: 5%;
		    background-size: 57% auto;
		    background-position: 40% 0%, center center;
		}
		.backgroundlogin{background-size: 10%;}	
		
}
	
	/* Landscape phones and smaller */
	@media (max-width: 480px) {
		.ContainerLogin {margin-top: 1%; margin-right: 0%; .backgroundlogin{background-position: 1% 36%;}}
		.backgroundlogin{background-position: 0;background-size: 20%;}

	}

</style>
<!-- bootstrap & fontawesome -->
<header>
	<?php include("includes/cabecera.php");?>
</header>

<body>


	<div class="container">
		<div class="row">
			<div class="jumbotron ContainerLogin col-md-9  col-xs-12 .col-sm-12 pull-right fondo">
			<div class="row">
				
				<div class="col-md-12">
					<h2 class="text-center">Cobranza</h2>
				</div>
				<div class="alert alert-danger alert-dismissible col-md-12" role="alert" id="alerta" style="display:none;">
				  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				  <strong>Atención!</strong> <p id="ErrorTexto"></p>
				</div>
				<div class="col-md-2"></div>
				<div class="Logo col-md-8 .bgimage-inside ImagenCoppelMain">
				</div><!-- Logo-->
				<div class="col-md-2"> </div>
				<div class="col-md-12  col-xs-12 ContainerForm">
					<form name="form" role="form">
						<h5 class="text-center">Favor De Llenar Los Campos</h5>
						<div class="col-md-3"> </div>
						<div class="col-md-6  col-xs-12 ContainerPreguntas">
							<div class="col-md-12 Pregunta">
								<input type="numeric" placeholder="Usuario" class="form-control" id="num_empleado" name="num_empleado" ng-model="usuario.num_empleado" required>
							</div><!-- Pregunta -->

							<div class="col-md-12 Pregunta" ng-show="form.num_empleado.$dirty">
								<p class="help-block text-danger" ng-show="form.num_empleado.$error.required">Campo Obligatorio</p>
                   				
							</div><!-- Pregunta -->

							<div class="col-md-1"></div>
							<div class="col-md-11 Pregunta ContainerButtons">
								<div class="row">
									<input type="button" class="btn btn-primary col-xs-12 pull-right" 
										value="Ingresar" id="logbtn" ng-click="LoginUsuario()" ng-disabled="form.$invalid" >
								</div><!-- row-->
							</div><!-- ContainerButtons -->
							
						</div><!-- md-6 ContainerPreguntas -->
					</form>
				</div><!-- containerForm-->
			</div><!-- row -->
		</div><!--jumbotron -->
		</div><!-- row-->
	</div><!-- container-->
</body>

<footer>
	

</footer>
</html>

<script>
	
	$(document).ready(function(){
		$(".ace-nav").css("display","none");
		$("#cabecera").removeAttr("href");
	});

	 /* Angular */
	 var app = angular.module("app",['tools']);
	 app.controller("LoginController",['$scope','$log','$http','$toolsfactory', function($scope,$log,$http,$toolsfactory){
       
     $scope.usuario  = 
     {
     	name:"",
     	password:"",
     	activo:true
     };
     $scope.onKeyPressResult = "";

     var getKeyboardEventResult = function (keyEvent, keyEventDesc)
    {
      return keyEventDesc + (window.event ? keyEvent.keyCode : keyEvent.which);
    };

      $scope.onKeyPress = function ($event) 
      {
        var key = $event.keyCode;
       
        if(key==13)
        {
        	$scope.LoginUsuario();
        }//if
      }//onKeyPress


      $scope.LoginUsuario = function()
      {
      	 usuario  = $scope.usuario.num_empleado;

      	 // Mandando por ajax, verificando la existencia del usuario. 
      	 //Obteniendo todos los datos y pintando todo
		Arr 		 	= new Object();
		Arr['usuario']  = usuario;
		Arr['Accion'] 	= "LoginUsuario";
		params 			= JSON.stringify(Arr);

     	var url = 'modulos/cobranza/funciones.php';
        $http({
	      method: "post",
		      url: url,
		      data: $.param({params:params}), 
		      headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
	     	})
	       .success(function(data, status, headers, config) 
	       {
	    		correcto = data.correcto;
	    		console.log(data);
	    		if(correcto==1)
	    		{
	    			window.location = "index.php";
	    		}//if
	    		else
	    		{
	    			respuesta = data.respuesta;
	    			alerta    = respuesta.alerta;
	    			error     = respuesta.error;
	    			$toolsfactory.CambiarColorAlerta(alerta,error);
	    		}//else
	      	})
	       .error(function(data, status, headers, config) 
	       	{
	        	alert("Ha fallado la petición. Estado HTTP:"+status);
	      	});

	    }//LoginUsuario
  	}]);

	function CambiarColorAlerta(tipo_cambio,texto)
	{
		// Los tipos de cambio serán 1 y 2
		// 1) cuando la alerta es verde, mostrando algo positivo.
		// 2) cuando la alerta es roja, mostrando algo negativo, como errores.
		switch(tipo_cambio)
		{
			case 1:
			//Aquí la alerta se vuelve positiva.
			if($("#alerta").hasClass("alert-danger"))
			{
				$("#alerta").removeClass("alert-danger");
				$("#alerta").addClass("alert-success");
			}//if
			break;

			case 2:
			//Aquí la alerta se vuelve negativa.
			if($("#alerta").hasClass("alert-success"))
			{
				$("#alerta").removeClass("alert-success");
				$("#alerta").addClass("alert-danger");
			}//if
			break;
		} // switch

		$("#ErrorTexto").text(texto);
		$("#alerta").css("display","inherit");
		$("#alerta").fadeOut(5000);
		//
	}//CambiarColorAlerta

	function keyPress(e)
	{
	alert("hola");
	}
</script>