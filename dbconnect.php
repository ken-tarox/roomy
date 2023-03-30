<?php
try{
    $db = new PDO('mysql:dbname=heroku_023486de338ccf1;host=us-cdbr-east-06.cleardb.net;charset=utf8', 'b4d5703c222109', '019b4f7a');
}catch(PDOException $e){
    print('DB接続エラー：' . $e->getMessage());
}