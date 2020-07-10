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
        $this->_setDatabase();
    }

    private function _setDatabase()
    {
        try {
            $file = "CREATE TABLE IF NOT EXISTS filename(
                id INT(11) AUTO_INCREMENT PRIMARY KEY,
                filename VARCHAR(100) NOT NULL)";
            $data = "CREATE TABLE IF NOT EXISTS datachat(
                id BIGINT(20) AUTO_INCREMENT PRIMARY KEY,
                filename_id INT(11) NOT NULL,
                date date NOT NULL,
                time VARCHAR(10) NOT NULL, 
                contact VARCHAR(50) NOT NULL,
                message TEXT NOT NULL,
                emoji TEXT NOT NULL,
                url INT(11) NOT NULL,
                letter_count INT(11) NOT NULL,
                word_count INT(11) NOT NULL)";
            $this->database->exec($file);
            $this->database->exec($data);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getDatabase()
    {
        return $this->database;
    }
}
