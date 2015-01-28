<?php
require_once("interface.php");

/**
 *creates a request splitter based on what you want to do
 */
class Req(){
    private function __construct() {}//static only
    
    public static select(){
        return new _SelectRequestSplitter();
    }
    public static insert(){
        return new _InsertRequestSplitter();
    }
    public static update(){
        return new _UpdateRequestSplitter();
    }
    public static delete(){
        return new _DeleteRequestSplitter();
    }
    
}

class _SelectRequestSplitter{
    
    public fromPlayerID($pid){
        $pid = mysqli_real_escape_string($pid);
        return new _SelectPlayerRequest("ID=$pid");
    }
    public fromPlayerName($pname){
        
    }
    public fromSceneID($sid){
        
    }
    public fromNpcID($nid){
        
    }
}

class _InsertRequestSplitter{
    
    public alert($aidFromEnum){
        $aidFromEnum = self::prepVar($aidFromEnum);
        return new _AddAlertRequest($aidFromEnum);
    }

}

class _SelectRequest extends Interface_class{
        private $columns;
        private $table;
        private $where;
        public function __construct($table, $where) {
            this->$where = $where;
            this->$table = $table;
            this->$columns = [];
        }
        
        public function run(){
            if(count(this->$columns) < 1){
                throw new Exception("no cols in request");
            }
            $cols = array_map (self::prepVar, $columns);
            $table = self::prepVar($table);
            return self::$db->queryMulti("select ".implode(",",$cols)." from ".this->$table." ".$where);
        }
}

class _InsertRequest extends Interface_class{
        private $columns;
        private $values;
        private $table;
        public function __construct($table) {
            this->$table = $table;
        }
        
        public function run(){
            if(count(this->$columns) < 1){
                throw new Exception("no cols in request");
            }
            if(count(this->$columns) != count(this->$values)){
                throw new Exception("val and col count don't match");
            }
            $columns = array_map (self::prepVar, $columns);
            $values = array_map (self::prepVar, $values);
            $table = self::prepVar($table);
            self::$db->queryMulti("insert into ".$table." (".implode(",",$columns).") values (".implode(",",$values));
        }
}

class _SelectPlayerRequest extends _SelectRequest{

        public function __construct($table, $where) {
            parent::__construct("playerinfo", $where);
        }
        
        private function addCol($col){
            if(!in_array($col, this->$columns){
                this->$columns[] = self::prepVar($col);
            }
        }
        /**
         *The player's description
         */
        public function desc(){
            this->addCol("Description");
            return this;
        }
        /**
         *The player's name
         */
        public function name(){
            this->addCol("Name");
            return this;
        }
        /**
         *The player's id
         */
        public function id(){
            this->addCol("ID");
            return this;
        }
        /**
         *The player's current scene
         */
        public function sceneid(){
            this->addCol("Scene");
            return this;
        }
        /**
         *The player's crafting skill
         */
        public function crafting(){
            this->addCol("CraftSkill");
            return this;
        }
        /**
         *The player's current health
         */
        public function health(){
            this->addCol("Health");
            return this;
        }
        /**
         *The player's option for front loading scenes
         */
        public function frontLoadScenes(){
            this->addCol("FrontLoadScenes");
            return this;
        }
        /**
         *The player's option for front loading keywords
         */
        public function frontLoadKeywords(){
            this->addCol("FrontLoadKeywords");
            return this;
        }
        /**
         *The player's email
         */
        public function email(){
            this->addCol("Email");
            return this;
        }
        /**
         *If the player is logged in
         */
        public function loggedIn(){
            this->addCol("LoggedIn");
            return this;
        }
        /**
         *The player's last login time
         */
        public function lastLoginTime(){
            this->addCol("LastLoginTime");
            return this;
        }
}

class _AddAlertRequest extends _InsertRequest{

        //need to keep track of multiple insert rows
        public function __construct($aid, $pid) {
            parent::__construct("playeralerts");
            this->$columns[] = "playerID";
            this->$values[] = $pid;
            this->$columns[] = "alertID";
        }
        
        private function addAlert($aid){
            if(!in_array($aid, this->$values){
                this->$values[] = $aid;
            }
        }
        /**
         *Whent he player gets a new item
         */
        public function newItem(){
            this->addAlert();//??
            return this;
        }
}
?>