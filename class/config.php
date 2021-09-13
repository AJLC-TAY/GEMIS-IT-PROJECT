<?php
class dbConfig {
    protected $serverName;
    protected $userName;
    protected $password;
    protected $dbName;
    protected $port;
    public function __construct() {
        $this -> serverName = '127.0.0.1';
        $this -> userName = 'root';
        $this -> password = 'root';
        $this -> dbName = 'gemis';
        $this -> port = 8889;
    }

    public function connect() {
        return new mysqli($this->serverName, $this->userName, $this->password, $this->dbName, $this->port);
    }
}
?>