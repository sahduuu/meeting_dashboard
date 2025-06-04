<?php
class Database {
    private $host = "localhost";
    private $port = "5432";
    private $dbname = "meeting_db";
    private $user = "postgres";
    private $password = "sahda123";
    protected $conn;


    public function connect() {
        $this->conn = pg_connect("host=$this->host port=$this->port dbname=$this->dbname user=$this->user password=$this->password");
        if (!$this->conn) {
            die("Connection failed: " . pg_last_error());
        }
        return $this->conn;
    }
}
?>
