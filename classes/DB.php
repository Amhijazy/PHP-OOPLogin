<?php

// This is a database wrapper
// It aims to provide easier access to the database
// we will use PDO

class DB {
    // In this class we will use a singlton pattern
    // This means we do not connect to the database every time we need to query it
    // We save local instance of the database here.

    //the _ before every var is a notation to remind us that
    //these are private variables

    private static $_instance = null;

    private $_pdo, //store the instantiated pdo
            $_query, // last executed query
            $_error = false, //if we get an error
            $_results, // the result set
            $_count = 0; //how much results we got
    
    private function __construct(){ //constructor is private. This means we cant instantiate this class outside of it
        try{
            $this->_pdo = new PDO('mysql:host='. Config::get('mysql/host') . ';dbname='.Config::get('mysql/db'),Config::get('mysql/username'),Config::get('mysql/password'));
            // $this->_pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e){
            die($e->getMessage()); //
        }
    }

    public static function getInstance() {
        if(!isset(self::$_instance)){       // we used self:: here because $_instance is a static property.
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($sql,$params = array()){
        $this->_error = false;  //reset the error flag in case we are doing multiple queries in a row
        if($this->_query = $this->_pdo->prepare($sql)){     //if the query prepared successfully
            if(count($params)){
                $x = 1;
                foreach($params as $param){       // check if we have bindings and bind them
                    $this->_query->bindValue($x,$param);
                    $x++;
                }
            }
            if($this->_query->execute()){                   // execute the query and tell me if it succeeded
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ); // gets all the result objects
                $this->_count = $this->_query->rowCount();                  // gives me the reults count
            }else {
                $this->_error = true;       //tell us if there is an error
            }
        }
        return $this;
    }

    public function action($action,$table,$where = array()){
        if(count($where == 3)){
            $operators = ['=','<','>','<=','>='];
            $field      = $where[0];
            $operator   = $where[1];
            $value      = $where[2];
            if(in_array($operator,$operators)){
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql,array($value))->error()){
                    return $this;
                }
            }

        }
        return false;
    }

    public function get($table,$where){
        return $this ->action('SELECT *',$table,$where);
    }

    public function delete($table,$where ){
        return $this->action('DELETE',$table,$where);
    }

    public function insert($table,$fields=array()){
        if(count($fields)){
            $keys = array_keys($fields);
            $values = null;
            $x=1;
            foreach($fields as $field){
                $values .= '?';
                if($x<count($fields)){
                    $values .= ',';
                }
                $x++;
            }
            $sql = "INSERT INTO `$table` (`" . implode('`,`',$keys) . "`) VALUES ($values)";
            if(!$this->query($sql,$fields)->error()){
                return true;
            }
        }
        return false;
    }
    public function update($table,$id,$fields=array()){
        $set = '';
        $x = 1;
        foreach($fields as $key => $field){
            $set .= $key .' = ?';
            if($x<count($fields)){
                $set .= ',';
            }
            $x++;
        }
        $sql = "UPDATE $table SET $set WHERE id = $id" ;
        if(!$this->query($sql,$fields)->error()){
            return true;
        }
        return false;
    }

    public function results(){
        return $this->_results;
    }

    public function first(){
        return $this->results()[0];
    }

    public function error(){
        return $this->_error;
    }

    public function count(){
        return $this->_count;
    }
}