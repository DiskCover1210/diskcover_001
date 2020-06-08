<?php
  if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
//header('Access-Control-Allow-Origin: *'); 
header('content-type: application/json; charset=utf-8');
//ini_set("allow_url_fopen", true);
//print_r($_POST);
//var_dump($_POST) returns: array(0) {}
//$_POST = array_merge($_POST, (array) json_decode(file_get_contents('php://input')));

if($_POST){
	//echo "recibo algo POST";
	
	//recibo los datos y los decodifico con PHP
	$misDatosJSON = json_decode($_POST["datos"]);
	
	//con esto podría mostrar todos los dtos del JSON recibido
	//print_r($misDatosJSON);
	foreach ($misDatosJSON as $clave => $valor) {
		// $array[3] se actualizará con cada valor de $array...
		echo "{$clave} => {$valor} ";
		if(is_array($valor))
		{
			foreach ($valor as $clave => $valor1) 
			{
				echo "{$clave} => {$valor1} ".'<br>';
			}
		}
		//print_r($array);
	}
	//ahora muestro algún dato de este array bidimiesional
	/*$salida = "";
	$salida .= "Capital de Francia: " . $misDatosJSON[1][1];
	$salida .= "<br>Nombre del país 1 (índice 0 del array): " . $misDatosJSON[0][0];
	$salida .= "<br>Nombre del país 3: " . $misDatosJSON[2][0];
	//echo utf8_encode($salida);
	echo $salida;*/
}else{
	$jsondata["success"] = false;
	$jsondata["data"] = array(
				'message' => 'No recibí datos por POST.'
				);
	header('Content-type: application/json; charset=utf-8');
	echo json_encode($jsondata, JSON_FORCE_OBJECT);
	//echo "No recibí datos por POST";
}

if( isset($_GET['club_id']) ) {
	$jsondata["success"] = true;
    $jsondata["data"]["message"] = sprintf("Se recibido informacion");
	header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
    //get_persons($_GET['club_id']);
} else {
	$jsondata["success"] = false;
		$jsondata["data"] = array(
				'message' => 'Solicitud no válida.'
				);
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($jsondata, JSON_FORCE_OBJECT);
		//echo 'Solicitud no válida.';
}
 die();
/*INSERT INTO `club` (`club_id`, `email`, `password`, `name`, `display_name`, `phone`, `status`, `address1`,
 `address2`, `city`, `state`, `country`, `zip`, `last_login`, `language`) VALUES
(7, 'clubprueba@hotmail.com', 'bd9ae8389724804a6d4e3145031e69a349a9e3bb', 'clubprueba', 'clubprueba', 
'453453453', 'approved', 'clubprueba', 'clubprueba', 'clubprueba', 'clubprueba', 'clubprueba', 0, NULL, '0');*/

function get_persons( $id ) {
    
    //Cambia por los detalles de tu base datos
    $dbserver = "localhost";
    $dbuser = "root";
    $password = "";
    $dbname = "msistore";
    
    $database = new mysqli($dbserver, $dbuser, $password, $dbname);
    
    if($database->connect_errno) {
        die("No se pudo conectar a la base de datos");
    }
    
    $jsondata = array();
    
    //Sanitize ipnut y preparar query
    if( is_array($id) ) {
        $id = array_map('intval', $id);
        $querywhere = "WHERE `club_id` IN (" . implode( ',', $id ) . ")";
    } else {
        $id = intval($id);
        $querywhere = "WHERE `club_id` = " . $id;
    }
    
    if ( $result = $database->query( "SELECT * FROM `club` " . $querywhere ) ) {
        
        if( $result->num_rows > 0 ) {
            
            $jsondata["success"] = true;
            $jsondata["data"]["message"] = sprintf("Se han encontrado %d usuarios", $result->num_rows);
            $jsondata["data"]["users"] = array();
            while( $row = $result->fetch_object() ) {
                //$jsondata["data"]["users"][] es un array no asociativo. Tendremos que utilizar JSON_FORCE_OBJECT en json_enconde
                //si no queremos recibir un array en lugar de un objeto JSON en la respuesta
                //ver http://www.php.net/manual/es/function.json-encode.php para más info
                $jsondata["data"]["users"][] = $row;
            }
            
        } else {
            
            $jsondata["success"] = false;
            $jsondata["data"] = array(
            'message' => 'No se encontró ningún resultado.'
            );
            
        }
        
        $result->close();
        
    } else {
        
        $jsondata["success"] = false;
        $jsondata["data"] = array(
        'message' => $database->error
        );
        
    }
    
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($jsondata, JSON_FORCE_OBJECT);
    
    $database->close();
    
}

exit();                            