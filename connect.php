<?php

class Connect {
    private $servername = "localhost";
    private $username = "root";
    private $password = "";
    private $dbname = "chatfluency";
    public $conn;

    public function __construct() {
        $this->conn = mysqli_connect($this->servername, $this->username, $this->password, $this->dbname);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        mysqli_select_db($this->conn, $this->dbname);
    }

    public function unique_id() {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $rand = array();
        $length = strlen($str) - 1;
        for ($i = 0; $i < 20; $i++) {
            $n = mt_rand(0, $length);
            $rand[] = $str[$n];
        }
        return implode($rand);
    }
}
?>