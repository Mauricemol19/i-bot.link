<?php
/**
 * Class Database
 *
 * Usage:
 *
 * //Setting a query
 * $db->query("SELECT * FROM");
 *
 * //Adding vars to query (binding)
 * $db->query("SELECT * FROM something WHERE id = :id");
 * $db->bind("id", $id);
 *
 * //Executing sql without return var (update, insert, drop etc.)
 * $db->execute();
 *
 * //Execute select statement and return the first collumn in a single array
 * $row = $db->single();
 *
 * //Execute select statement and return all collumns in a multidimensional array
 * $rows = $db->resultset();
 *
 * //Return the number of rows the query contains
 * $count = $db->rowCount();
 *
 * Use transactions to execute the same sql statement multiple times but changing the vars
 *
*/

class Database {
    private $host      = DB_HOST;
    private $user      = DB_USER;
    private $pass      = DB_PASS;
    private $dbname    = DB_NAME;

    private $dbh;
    private $error;
    private $stmt;

    public function __construct() {
        //Set host
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
        // Set options
        $options = array(
            //PDO::ATTR_PERSISTENT    => true,
            PDO::ATTR_ERRMODE       => PDO::ERRMODE_EXCEPTION
        );

        // Create a new PDO instance
        try
        {
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }
        catch(PDOException $e)
        {
            echo $e;
            $this->error = $e->getMessage();
        }
    }

    /**
     * query()
     *
     * Execute query
     *
     * @param string $query
     */
    public function query($query) {
        $this->stmt = $this->dbh->prepare($query);
    }

    public function bind($param, $value, $type = null) {
        //Bind parameter
        if (is_null($type)) {
            switch (true) {
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
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * execute()
     *
     * Execute query
     *
     * @return boolean
     */
    public function execute() {
        //Execute query
        return $this->stmt->execute();
    }

    /**
     * resultset()
     *
     * Return associative array of multiple results
     *
     * @return mixed
     */
    public function resultset() {
        $this->execute();
        return $this->stmt->fetchALL(PDO::FETCH_ASSOC);
    }

    /**
     * single()
     *
     * Execute one string with results
     *
     * @return mixed
     */
    public function single() {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * rowCount()
     *
     * Return the number of rows the query contains
     *
     * @return mixed
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }

    /**
     * lastInsertId()
     *
     * Return the last inserted id
     *
     * @return string
     */
    public function lastInsertId() {
        return $this->dbh->lastInsertId();
    }

    /**
     * beginTransaction()
     *
     * Begin transaction
     *
     * @return boolean
     */
    public function beginTransaction() {
        return $this->dbh->beginTransaction();
    }

    /**
     * endTransaction()
     *
     * End transaction
     *
     * @return boolean
     */
    public function endTransaction() {
        return $this->dbh->commit();
    }

    /**
     * cancelTransaction()
     *
     * Cancel out of a transaction
     *
     * @return boolean
     */
    public function cancelTransaction() {
        return $this->dbh->rollBack();
    }

    /**
     * debugDumpParams()
     *
     * Display debug results
     *
     * @return mixed
     *
     */
    public function debugDumpParams() {
        return $this->stmt->debugDumpParams();
    }
}
?>
