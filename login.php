<?php
require_once 'db_functions.php';
$db = new DB_Functions();
 
// json raspuns in array
$response = array("error" => FALSE);

//modificare
//$client = $db->getclientByEmailAndPassword("R", "R");
 
if (isset($_POST['email']) && isset($_POST['parola'])) {
 
    // primire parametri post
    $email = $_POST['email'];
    $parola = $_POST['parola'];
 
    // obtinere client dupa email si parola
    $client = $db->getclientByEmailAndPassword($email, $parola);
 
    if ($client != false) {
        // daca este gasit
        $response["error"] = FALSE;
        $response["client"]["id_client"] = $client["id_client"];
        $response["client"]["nume"] = $client["nume"];
        $response["client"]["prenume"] = $client["prenume"];
        $response["client"]["telefon"] = $client["telefon"];
        $response["client"]["oras"] = $client["oras"];
        $response["client"]["email"] = $client["email"];
        $response["client"]["data_creare"] = $client["data_creare"];
        echo json_encode($response);
        
    } else {
        // client nu este gasit
        $response["error"] = TRUE;
        $response["error_msg"] = "Date de conectare incorecte!";
        echo json_encode($response);
    }
} else {
    // parametri necesari lipsa
    $response["error"] = TRUE;
    $response["error_msg"] = "Parametri necesari (email sau parola) neindicati!";
    echo json_encode($response);
}
?>