<?php
session_start();
require('../dbconnect.php');

if(isset($_SESSION['id'])){
    $id2 = $_REQUEST['id'];

        $de2 = $db->prepare('DELETE FROM members WHERE id=?');
        $de2->execute(array($id2));

        $de3 = $db->prepare('DELETE FROM reservations WHERE member_id=?');
        $de3->execute(array($id2));

}

header('Location: index.php');
exit();
?>