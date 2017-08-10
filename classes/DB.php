<?php
// Database Wrapper

class DB {
    // Singleton Pattern - main static method get instance
    // Do not have to keep connecting to our database again. We can just get an instance of our db if it's been instantiated. 
    // Using the Singleton instead of a constructor
    
    private static $_instance = null; // Will store the instance of the db if available
    // _ is simply a notation that lets us know that the variables are private 
    private $_pdo, // store PDO object
        $_query, // last query that's executed
        $_error = false, // did the query fail
        $_results, // store the result set
        $_count = 0; // number of results returned
        
    private function __construct() {
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password')); //set private pdo 
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    public static function getInstance() {
        // if there's not an instance of the DB, create one
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    
    public function query($sql, $params = array()) {
        $this->_error = false; 
        // check if the query has been prepared properly
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $x = 1; // counter for position in bindValue
            // have items been added to the array? 
            if (count($params)) {
                foreach($params as $param) {
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            
            if ($this->_query->execute()) {
                // store the result set
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount(); // rowCount - built in method of pdo
            } else {
                $this->_error = true;
            }
        }
        
        return $this; // returns current obj. allows us to chain on error function  
    }
    
    public function action($action, $table, $where = array()) {
        // is $where == 3 - need field, operator, and value
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
        
        
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }
    
    public function get($table, $where) {
        return $this->action('SELECT *', $table, $where);
    }
    
    public function delete($table, $where) {
        return $this->action('DELETE', $table, $where);
    }
    
    public function insert($table, $fields = array()) {
        //if (count($fields)) {
            $keys = array_keys($fields);
            $values = ""; 
            $x = 1;
            
            foreach($fields as $field) {
                $values .= '?';
                if ($x < count($fields)) {
                    $values .= ', ';
                }
                $x++;
            }
            
            $sql = "INSERT INTO users (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
            
            // if there aren't any errors
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
            
            echo $sql;
       // }
        return false; 
    }
    
    public function update($table, $id, $fields = array()) {
        $set = '';
        $x = 1;
        
        foreach($fields as $name => $value) {
            $set .= "{$name} = ?";
            if ($x < count($fields)) {
                $set .= ', ';
            }
            $x++;
        }
        
        
        $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
        
        if (!$this->query($sql, $fields)->error()) {
            return true;
        }
    }
    
    public function results() {
        return $this->_results; 
    }
    
    public function first() {
        return $this->results()[0];
    }
    
    public function error() {
        return $this->_error;
    }
    
    public function count() {
        return $this->_count;
    }
    
}