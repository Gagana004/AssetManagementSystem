<?php
//database_connection.php
//db = asset_management_db
//username = root
//password = ''

$connect = new PDO('mysql:host=localhost;dbname=asset_management_db', 'root', '');
session_start();

?>