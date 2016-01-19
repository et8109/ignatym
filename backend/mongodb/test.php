<?php
// Config
$dbhost = 'localhost';
$dbname = 'mongoTest';
 
// Connect to test database
$m = new Mongo("mongodb://$dbhost");
$db = $m->$dbname;
 
// select the collection
$scenes = $db->scenes;

$scene = array(
        'title'     => 'What is MongoDB',
        'content'   => 'MongoDB is a document database that provides high performance...',
        'saved_at'  => new MongoDate() 
    );
$scenes->insert($scene);
 
// pull a cursor query
$cursor = $scenes->find();

foreach($cursor as $s){
  var_dump($s);
}
?>
