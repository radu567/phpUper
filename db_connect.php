<?php
class DB_Connect {
    public $conn;
    //modificat


 
    // Conectare la baza de date
    public function connect() {
    	    $HOST = "localhost";
    		$CLIENT = "stark567_uper";
    		$PAROLA = "stark567_uper";
    		$DATABASE = "stark567_uper";
         
        // Conectare la baza de date
        $this->conn = new mysqli("localhost", "stark567_uper", "stark567_uper", "stark567_uper");
         
        // Returnare baza de date preluata
        return $this->conn;
        
    }
}
?>