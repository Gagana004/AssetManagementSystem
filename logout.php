<?php
//logout.php
session_start();

session_destroy(); //kill all the sessions

header("location:login.php"); //redirect to login page

?>