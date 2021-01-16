<?php

namespace Service;

use \PDO;

class Db extends PDO {

    private $_HOST;
    private $_DBNAME;
    private $_USER = null;
    private $_PASS = null;
    private $_CHARSET;

    private $_options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::ATTR_PERSISTENT => true
    ];

    public $result = NULL;

    static protected $_instance;

    public $statement;
    
    public function __construct()
    {
        $dsn = 'sqlite::memory:';

        return parent::__construct($dsn, $this->_USER, $this->_PASS, $this->_options);
    }

    static public function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /* prepare sql statement for executing
     * 
    */
    public function prepareStatement( $sql, $options = [] ) {
        $this->statement = $this->prepare( $sql, $options );
    }

    /* execute sql statement for executing
     * 
    */
    public function executeStatement( $params = [] ) {
        return $this->statement->execute( $params );
    }

    /* get all rows in array
     * 
    */
    public function getAll() {
        return $this->statement->fetchAll();
    }

    /* get one row in array
     * 
    */
    public function getRow() {
        return $this->statement->fetch();
    }

    /* run sql & return count of affected rows
     * 
    */
    public function execSql( $sql ) {
        return $this->exec( $sql );
    }

    /* run sql & return result
     * 
    */
    public function querySql( $sql ) {
        $this->statement = $this->query( $sql );
        return $this->statement;
        //return $this->query( $sql );
    }

    /* get sql error info
     * 
    */
    public function errInfo() {
        return $this->statement->errorInfo();
    }

    /* get sql error info
     * 
    */
    public function getCount() {
        return $this->statement->rowCount();
    }

    /* get last ID
     *
    */
    public function getLastId() {
        return $this->lastInsertId();
    }
}