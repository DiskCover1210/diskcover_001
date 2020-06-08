<?php

if($_POST){
	//echo "recibo algo POST";
	
	//recibo los datos y los decodifico con PHP
	$misDatosJSON = json_decode($_POST["datos"]);
	
	//con esto podría mostrar todos los dtos del JSON recibido
	//print_r($misDatosJSON);
	foreach ($misDatosJSON as $clave => $valor) {
		// $array[3] se actualizará con cada valor de $array...
		echo "{$clave} => {$valor} ";
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
	echo "No recibí datos por POST";
}

if( isset($_GET['club_id']) ) {
    get_persons($_GET['club_id']);
} else {
    die("Solicitud no válida.");
}
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