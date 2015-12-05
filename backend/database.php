<?php
class Database {
    private static $hostName = "localhost";
    private static $username = "root";//"ignatymc_admin";
    private static $password = null;//"1Gn4tym";
    private static $name = "ignatymc_main";
    private $con;
    public function __construct() {
        $this->con = $this->getConnection();
    }
    
    public function getDatabaseName(){
        return self::$name;
    }
    
    public function escapeString($str){
        return mysqli_real_escape_string($this->con,$str);
    }
    
    public function _query($sql){
        $result = mysqli_query($this->con, $sql);
        return $result;
    }
    
    public function querySingle($sql){
        $result = mysqli_query($this->con, $sql);
        if(is_bool($result)){
            return false;
        }
        $numRows = mysqli_num_rows($result);
        if($numRows > 1){
            throw new tooManyRowsException();
        }
        $row = $result->fetch_assoc();
        mysqli_free_result($result);
        return $row;
    }
    
    public function queryMulti($sql){
        $result = mysqli_query($this->con, $sql);
        $arr =  $result->fetch_all(MYSQLI_ASSOC);
        mysqli_free_result($result);
        return $arr;
    }
    
    public function lastQueryNumRows(){
        return mysqli_affected_rows($this->con);
    }
    
    /**
     *returns the ID of the last affted row by a query
     */
    public function lastQueryID(){
        return mysqli_insert_id($this->con);
    }
    
    private function getConnection(){
        //produces a warning if db name is given and db does not exist
        $con = mysqli_connect(self::$hostName,self::$username,self::$password,self::$name);
        //check connection
        if (mysqli_connect_errno()){
            throw new couldNotConnectException();
        }
        return $con;
    }
}

class dbException extends Exception{}
class tooManyRowsException extends dbException{}
class couldNotConnectException extends dbException{}
class resultIsBoolException extends dbException{}

?>
