<?php

class DB_Functions {
 
    private $conn;

 
    // constructor
    function __construct() {
        require_once 'db_connect.php';
        // conectare la baza de date
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    // destructor
    //function __destruct() {}
 
    /**
     * Stocare client nou
     * Returnare detalii client
     */
///////////////////////////////////////////////////

        public function hashSSHA($parola) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($parola . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 

    public function storeclient($nume, $prenume, $telefon, $oras, $email, $parola) {
        $id_client = uniqid('', true);
        $hash = $this->hashSSHA($parola);
        $parola = $hash["encrypted"]; // parola criptata
        $salt = $hash["salt"]; // salt

        ob_start();  
        var_dump($id_client, $nume, $prenume, $telefon, $oras, $email, $parola, $salt);  
        $out = ob_get_clean();  
        echo $out;


        $stmt = $this->conn->prepare("INSERT INTO client(id_client, nume, prenume, telefon, oras, email, parola, salt) VALUES(?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssssss", $id_client, $nume, $prenume, $telefon, $oras, $email, $parola, $salt);
        /////////////

        ////////////
        $result = $stmt->execute();
        $stmt->close();
 
        // pentru stocare cu succes
        if ($result) {
            $stmt = $this->conn->prepare("SELECT id_client, nume, prenume, telefon, oras, email, data_creare FROM client WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id_client, $nume, $prenume, $telefon, $oras, $email, $data_creare);
            mysqli_stmt_fetch($stmt);
            /* setarea valorilor*/
            $client['id_client'] = $id_client;
            $client['nume'] = $nume;
            $client['prenume'] = $prenume;
            $client['telefon'] = $telefon;
            $client['oras'] = $oras;
            $client['email'] = $email;
            $client['data_creare'] = $data_creare;
            $stmt->close();
 
            return $client;
        } else {
            return false;
        }
    }
 
    /**
     * Obtinere client dupa email si parola
     */
    public function getclientByEmailAndPassword($email, $parola) {
 
        $stmt = $this->conn->prepare("SELECT id_client, nume, prenume, telefon, oras, email, parola, salt, data_creare FROM client WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            //modificare
            $stmt->bind_result($id_client, $nume, $prenume, $telefon, $oras, $email, $parola_db, $salt, $data_creare);
            mysqli_stmt_fetch($stmt);
            /* setarea valorilor*/
            $client['id_client'] = $id_client;
            $client['nume'] = $nume;
            $client['prenume'] = $prenume;
            $client['telefon'] = $telefon;
            $client['oras'] = $oras;
            $client['email'] = $email;
            $client['data_creare'] = $data_creare;
            //sfirsit modificare    ..........................................

            $stmt->close();
 
            // verificare client parola
            //$salt = $client['salt'];
            // modificat      $parola = $client['parola'];
            $hash = $this->checkhashSSHA($salt, $parola);
            // verificare parola
            if ($parola_db == $hash) {
                // datele clientului de autentificare sunt corecte
                return $client;
            }
        } else {
            return NULL;
        }
    }
 
    /**
     * Verificare existenta client
     */
    public function isclientExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from client WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // client existent
            $stmt->close();
            return true;
        } else {
            // client inexistent
            $stmt->close();
            return false;
        }
    }
 
    /**
     * Criptarea parolei, returnarea saltului si a parolei criptate (parametru de intrare: parola)
     */
////////////////////sdg sdg sdg dg dfh d
    /**
     * Decriptarea parolei, returnarea hash string (parametri de intrare salt si parola)
     */
    public function checkhashSSHA($salt, $parola) {
 
        $hash = base64_encode(sha1($parola . $salt, true) . $salt);
 
        return $hash;
    }
}
?>