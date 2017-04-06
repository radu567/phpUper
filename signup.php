<?php
 
require_once 'db_functions.php';
$db = new DB_Functions();
 
// json raspuns array
$response = array("error" => FALSE);
//modificat
//$db->storeclient("name", "prenume", "telefon", "oras", "email", "parola");
 
if (isset($_POST['nume']) && isset($_POST['prenume']) && isset($_POST['telefon']) && isset($_POST['oras']) && isset($_POST['email']) && isset($_POST['parola'])) {
 
    // primire parametri post
    $nume = $_POST['nume'];
    $prenume = $_POST['prenume'];
    $telefon = $_POST['telefon'];
    $oras = $_POST['oras'];
    $email = $_POST['email'];
    $parola = $_POST['parola'];
 
    // verificare clienti dupa email
    if ($db->isclientExisted($email)) {
        // client existent
        $response["error"] = TRUE;
        $response["error_msg"] = "acest client exista cu :  " . $email;
        echo json_encode($response);
    } else {
        // creare client nou
        $client = $db->storeclient($nume, $prenume, $telefon, $oras, $email, $parola);
        if ($client) {
            // client inregistrat cu succes
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
            // eroare de inregistrare
            $response["error"] = TRUE;
            $response["error_msg"] = "Eroare necunoscuta la inregistrare!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Parametri obligatorii (nume, prenume, telefon, oras, email, parola) neindicati!";
    echo json_encode($response);
}
?>