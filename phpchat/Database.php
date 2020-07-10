<?php

class Database
{
    protected $dbhost = '127.0.0.1';
    protected $dbname = 'phpchats';
    protected $dbuser = 'root';
    protected $dbpass = '';
    protected $database;

    protected $data = [];

    public function __construct()
    {
        $dbhost = $this->dbhost;
        $dbname = $this->dbname;
        $this->database = new PDO("mysql:host=$dbhost;dbname=$dbname", $this->dbuser, $this->dbpass);
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getDatabase()
    {
        return $this->database;
    }
}
