<?php 

class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $password = DB_PASSWORD;
    private $dbname = DB_NAME;

    private $dbhandler;
    private $error;
    private $sqlQuery;

    public function __construct() {
        // set DSN
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // set options
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        // instantiate PDO object
        try {
            $this->dbhandler = new PDO($dsn, $this->user, $this->password, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    public function queryDB($dbQuery) {
        $this->sqlQuery = $this->dbhandler->prepare($dbQuery);
    }

    public function bind($parameter, $value, $type = null) {
        if (is_null($type)) {
            switch(true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->sqlQuery->bindValue($parameter, $value, $type);
    }

    public function execute() {
        return $this->sqlQuery->execute();
    }
    
    public function resultAll() {
        $this->execute();
        return $this->sqlQuery->fetchAll(PDO::FETCH_OBJ);
    }

    public function resultSingle() {
        $this->execute();
        return $this->sqlQuery->fetch(PDO::FETCH_OBJ);
    }
}

?>