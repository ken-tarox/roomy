<?php
session_start();

$_SESSION = array();
if(ini_set("session.use_cookies", 1)){
    $params = session_get_cookies_params();
    setcookie($session_name() . '', time() -42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

setcookie('email', '', time()-3600);

header('Location: index.php');
exit();
?>