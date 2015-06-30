
var miModulo = angular.module("tools", []);
 
miModulo.factory("$toolsfactory", function($http)
{
	return {
		saludo: function()
		{
			return "Hola desde otro modulo";
		},

		hola : function()
		{
			alert("hola putos");
		},

		Regresar : function(ub)
		{
			ubicacion = '/'+ub;
    		$location.path(ubicacion).search({});
		},
		DatosJson : function(accion,id)
    	{
    			Arr 		 		= new Object();
				Arr['Accion'] 		= accion;
				Arr['id'] 			= id;
				params 				= JSON.stringify(Arr);
				return params;
    	},
    	DefineMarker: function(marker)
    	{
    		img = "";
    		switch(marker)
    		{
    			case 0 :
    				img = "img/brown_MarkerM.png";
    			break;
    		}
    		return img;
    	},

		CambiarColorAlerta : function(tipo_cambio,texto)
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
	}
});