<?php
class dbConfig {
    protected $serverName;
    protected $userName;
    protected $password;
    protected $dbName;
    protected $port;
    public function __construct() {
        $this -> serverName = 'localhost';
        $this -> userName = 'root';
        $this -> password = '';
    //  $this -> dbName = 'gemis';
         $this -> dbName = 'gemistest';
        $this -> port = 3306;
    }

    public function connect() {
        return new mysqli($this->serverName, $this->userName, $this->password, $this->dbName, $this->port);
    }
}
?>