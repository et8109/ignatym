<?php
    class ErrorHandler{
        private function __construct() {}//static only
        
        public static function handle($e){
            $message = $e->getMessage();
            echo "<span id='error'>$message</span>";
        }
    }
?>
