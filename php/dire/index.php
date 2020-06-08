<?php
	error_reporting(E_ALL);
    ini_set('display_errors', '1');
    //Llamamos a la función, y ella hace todo :)
    write_visita ();

    //función que escribe la IP del cliente en un archivo de texto    
    function write_visita (){

        //Indicar ruta de archivo válida
        $archivo="visitas.txt";

        //Si que quiere ignorar la propia IP escribirla aquí, esto se podría automatizar
        $ip="186.4.206.95";
        $new_ip=get_client_ip();

        if ($new_ip!==$ip){
            $now = new DateTime();

       //Distinguir el tipo de petición, 
       // tiene importancia en mi contexto pero no es obligatorio

        if (!$_GET) {
            $datos="*POST: ".$_POST;

        } 
        else
        {
            //Saber a qué URL se accede
            $peticion = explode('/', $_GET['PATH_INFO']);
            $datos=str_pad($peticion[0],10).' '.$peticion[1];   
        }
        $txt =  str_pad($new_ip,25). " ".
                str_pad($now->format('Y-m-d H:i:s'),25)." ".
                str_pad(ip_info($new_ip, "Country"),25)." ".json_encode($datos);

        $myfile = file_put_contents($archivo, $txt.PHP_EOL , FILE_APPEND);
        }
    }


    //Obtiene la IP del cliente
    function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
		//echo $ipaddress;
		echo base64_encode($ipaddress); 
        return $ipaddress;
    }


    //Obtiene la info de la IP del cliente desde geoplugin

    function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE) {
        $output = NULL;
        if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
            $ip = $_SERVER["REMOTE_ADDR"];
            if ($deep_detect) {
                if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose    = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support    = array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents = array(
            "AF" => "Africa",
            "AN" => "Antarctica",
            "AS" => "Asia",
            "EU" => "Europe",
            "OC" => "Australia (Oceania)",
            "NA" => "North America",
            "SA" => "South America"
        );
        if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
            $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
            if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
                switch ($purpose) {
                    case "location":
                        $output = array(
                            "city"           => @$ipdat->geoplugin_city,
                            "state"          => @$ipdat->geoplugin_regionName,
                            "country"        => @$ipdat->geoplugin_countryName,
                            "country_code"   => @$ipdat->geoplugin_countryCode,
                            "continent"      => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code" => @$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address = array($ipdat->geoplugin_countryName);
                        if (@strlen($ipdat->geoplugin_regionName) >= 1)
                            $address[] = $ipdat->geoplugin_regionName;
                        if (@strlen($ipdat->geoplugin_city) >= 1)
                            $address[] = $ipdat->geoplugin_city;
                        $output = implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output = @$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output = @$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output = @$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output = @$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }
	

?>
<!--
<script>
$('#CD').click(function () {
    latitudReal = posicion.coords.latitude;
    longitudReal = posicion.coords.longitude;
    var markerPosicionReal = new google.maps.Marker({
        position: {
          lat: latitudReal,
          lng: longitudReal
        },
        title: "Mi actual ubicación"
    });
    markerPosicionReal.setMap(map);
    // Si quieres centrar el mapa en el nuevo marker:
    map.setCenter(markerPosicionReal.getPosition());
});
</script>-->
<!DOCTYPE html>
<html>
<head>
	
</head>
<body>
	
	
	<input type="text" class="form-control input-sm " id="beneficiario" name='beneficiario'  value='.'>
	<button type="button"  id='CD' style="width: 15%;">Diario</button>
<div id="map" style="width:100%;height:600px;"></div>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script>
$('#CD').click(function () {
	navigator.geolocation.getCurrentPosition(function(location) {
	  console.log(location.coords.latitude);
	  console.log(location.coords.longitude);
	  // Define the string
		var string = location.coords.latitude;
		var string1 = location.coords.longitude;
		// Encode the String
		var encodedString = btoa(string);
		var encodedString1 = btoa(string1);
		//document.getElementById("beneficiario").value=encodedString;
		 document.write("<h1>"+encodedString+" <br> "+encodedString1+"</h1>");
		//alert(encodedString); // Outputs: "SGVsbG8gV29ybGQh"

		// Decode the String
		var decodedString = atob(encodedString);
		//alert(decodedString);
		/*var map;
		var center = {lat: location.coords.latitude, lng: location.coords.longitude};
		function initMap() {
			map = new google.maps.Map(document.getElementById('map'), {
			center: center,
			zoom: 6
			});
			var marker = new google.maps.Marker({
			position: {lat: location.coords.latitude, lng: location.coords.longitude},
			map:map,
			title: 'Ubication'
			});
		}
		initMap();*/
	});
});
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuBEidKGDuQo7Bzf1uRg47MPaRRlEesw0">
</script>

</body>
</html>
