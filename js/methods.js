
var miModulo = angular.module("Methods", []);

 	miModulo.service('$methodsService', function(){
    	this.Marcador = function(marker)
		{
			img = "";
			switch(marker)
			{
				case "0":
					img = "img/brown_MarkerM.png";
				break;

				case "1":
					img = "img/blue_MarkerE.png";
				break;

				case "2":
					img = "img/blue_MarkerL.png";
				break;

				case "3":
					img = "img/blue_MarkerL.png";
				break;

				case "4":
					img = "img/brown_MarkerE.png";
				break;

				case "5":
					img = "img/darkgreen_MarkerE.png";
				break;
			}//switch
			return img;
		};
    })	
