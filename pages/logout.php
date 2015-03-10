<?
//access existing session
session_start();

//remove session variables
session_unset();

//kill the session
session_destroy();

//redirect to the login page
header('location: ../pages/login.php');