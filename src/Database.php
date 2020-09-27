<?php

/**
 * Database configuration
 * 
 * @author  Khoerul Umam <id.khoerulumam@gmail.com>
 * @version $Revision: 1 $
 * @access  public
 * 
 */

class Database
{
    /**
     * DB Host configuration
     */
    protected $dbhost = '127.0.0.1';

    /**
     * DB Name configuration
     */
    protected $dbname = 'phpchats';

    /**
     * DB user configuration
     */
    protected $dbuser = 'root';

    /**
     * DB password configuration
     */
    protected $dbpass = '';

    /**
     * Database
     */
    protected $database;

    /**
     * Saved data
     */
    protected $data = [];

    /**
     * Init apps
     */
    public function __construct()
    {
        $dbhost = $this->dbhost;
        $dbname = $this->dbname;
        $this->database = new PDO("mysql:host=$dbhost;dbname=$dbname", $this->dbuser, $this->dbpass);
        $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_setDatabase();
    }

    /**
     * Set database
     * 
     * @return boolean
     */
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

    /**
     * Get database object
     * 
     * @return object
     */
    public function getDatabase()
    {
        return $this->database;
    }
}
