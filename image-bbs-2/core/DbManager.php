<?php 

class DbManager{
    protected $con;

    public function __construct() {
        require_once dirname(__FILE__).'/../db_env.php';
        
        $dsn = 'mysql:dbname='.$db_name.';host='.$host;
        $dbh = new PDO($dsn, $user, $pass);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $this->con = $dbh;
        
    }

    public function execute($sql, array $params = []) {
        $stmt = $this->con->prepare($sql);
        $stmt->execute($params);

        return $stmt;
    }

    public function fetchAll($sql, array $params = []) {
        $stmt = $this->execute($sql, $params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $results;
    }

    public function __destruct() {
        $this->con = null;
    }
}

