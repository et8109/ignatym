<?php
    class ErrorHandler{
        private function __construct() {}//static only
        
        public static function handle($e){
            $message = $e->getMessage();
            echo "<img id='errorPoint' src='../images/errorPoint.png'><span id='error'>$message</span>";
        }
    }
?>