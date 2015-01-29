<?php
require_once("interface.php");

/**
 *creates a request splitter based on what you want to do
 */
class Req(){
    private function __construct() {}//static only
    
    public fromPlayerID($pid){
        $pid = mysqli_real_escape_string($pid);
        return new _SelectPlayerRequest("ID=$pid");
    }
    
    public fromPlayerName($pname){
        
    }
    
    public fromSceneID($sid){
        
    }
    
    public fromNpcID($nid){
        $nid = mysqli_real_escape_string($nid);
        return new _SelectNpcRequest("ID=$nid");
    }
    
    public fromItemID($nid){
        $pid = mysqli_real_escape_string($pid);
        return new _SelectItemRequest("ID=$pid");
    }
    
    public fromKeywordID($kid){
        $kid = mysqli_real_escape_string($kid);
        return new _SelectKeywordRequest("ID=$kid");
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
        
        /**
         *returns an object representation of the resulting array from the db
         */
        public function run(){
            if(count(this->$columns) < 1){
                throw new Exception("no cols in request");
            }
            $cols = array_map (self::prepVar, $columns);
            $table = self::prepVar($table);
            $array = self::$db->queryMulti("select ".implode(",",$cols)." from ".this->$table." ".$where);
            //convert to obj
            return (object)$array;
        }
        
        protected function addCol($col){
            if(!in_array($col, this->$columns){
                this->$columns[] = self::prepVar($col);
            }
        }
}

class _SelectNpcRequest extends _SelectRequest{

        public function __construct($where) {
            parent::__construct("npcs", $where);
        }
        
        /**
         *The npcs's name
         */
        public function name(){
            this->addCol("Name");
            return this;
        }
        
        /**
         *The npcs's decription
         */
        public function desc(){
            this->addCol("Desc");
            return this;
        }
        
        /**
         *The npcs's level
         */
        public function level(){
            this->addCol("Level");
            return this;
        }
}

class _SelectKeywordRequest extends _SelectRequest{

        public function __construct($where) {
            parent::__construct("keywords", $where);
        }
        
        /**
         *The keyword's description
         */
        public function desc(){
            this->addCol("Desc");
            return this;
        }
}

class _SelectItemRequest extends _SelectRequest{

        public function __construct($where) {
            parent::__construct("items", $where);
        }
        
        /**
         *The item's owner's id
         */
        public function ownerID(){
            this->addCol("playerID");
            return this;
        }
        
        /**
         *The item's name
         */
        public function name(){
            this->addCol("Name");
            return this;
        }
        
        /**
         *The item's description
         */
        public function desc(){
            this->addCol("Desc");
            return this;
        }
        
        /**
         *The item's remaining room (space for storage)
         */
        public function room(){
            this->addCol("room");
            return this;
        }
        
        /**
         *The item's id which holds this item
         */
        public function container(){
            this->addCol("insideOf");
            return this;
        }
}

class _SelectPlayerRequest extends _SelectRequest{

        public function __construct($where) {
            parent::__construct("playerinfo", $where);
        }
        
        /**
         *The player's description
         */
        public function desc(){
            this->addCol("Desc");
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
        public function __construct($pid) {
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
         *When the player gets a new item
         */
        public function newItem(){
            this->addAlert(1);
            return this;
        }
        
        /**
         *When the player gets a new spell
         */
        public function newSpell(13){
            this->addAlert();
            return this;
        }
        
        /**
         *When the player gets a new job
         */
        public function newJob(){
            this->addAlert(4);
            return this;
        }
        
        /**
         *When the player is fired from their job
         */
        public function fired(){
            this->addAlert(5);
            return this;
        }
        
        /**
         *When the player has a new employee
         */
        public function newEmployee(){
            this->addAlert(9);
            return this;
        }
        
        /**
         *When the player has a new manager
         */
        public function newManager(){
            this->addAlert(7);
            return this;
        }
        
        /**
         *When the player has a new lord
         */
        public function newLord(){
            this->addAlert(8);
            return this;
        }
        
        /**
         *When the player's employee quits
         */
        public function employeeQuit(){
            this->addAlert(6);
            return this;
        }
        
        /**
         *When the player's manager quits
         */
        public function managerQuit(){
            this->addAlert(10);
            return this;
        }
        
        /**
         *When the player's employee is fired
         */
        public function employeeFired(){
            this->addAlert(11);
            return this;
        }
        
        /**
         *When the player's manager is fired
         */
        public function managerFired(){
            this->addAlert(12);//??
            return this;
        }
        
        /**
         *When the player's item is removed
         */
        public function removedItem(){
            this->addAlert(3);
            return this;
        }
        
        /**
         *When the player's item is hidden
         */
        public function hiddenItem(){
            this->addAlert(2);
            return this;
        }
}
?>