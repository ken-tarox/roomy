<?php
session_start();


$_SESSION = array();
if(isset($_COOKIE[session_name()])==true){
    setcookie(session_name() . '', time() -42000,'/');
}

session_destroy();

setcookie('email', '', time()-3600);

header('Location: login.php');
exit();
?>

