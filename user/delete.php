<?php
session_start();
require('../dbconnect.php');

if(isset($_SESSION['id'])){
    $id = $_REQUEST['id'];

    $reservations = $db->prepare('SELECT * FROM reservations WHERE id=?');
    $reservations->execute(array($id));
    $reservation = $reservations->fetch();

    if($reservation['member_id'] == $_SESSION['id']){
        $del = $db->prepare('DELETE FROM reservations WHERE id=?');
        $del->execute(array($id));
    }
}

header('Location: index.php');
exit();
?>