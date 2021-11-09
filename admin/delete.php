<?php
session_start();
require('../dbconnect.php');

if(isset($_SESSION['id'])){
    $id = $_REQUEST['id'];

        $del = $db->prepare('DELETE FROM reservations WHERE id=?');
        $del->execute(array($id));

}

header('Location: index.php');
exit();
?>