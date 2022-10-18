<?php
  define('DB_SERVER', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', '');
  define('DB_DATABASE', 'xms');
  $mysqli  = new mysqli(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    
  // Check connection
    if($mysqli  === false){
        die("ERROR: Could not connect.. " . $mysqli->connect_error);
    }
?>