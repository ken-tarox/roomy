<?php
try{
    $db = new PDO('mysql:dbname=heroku_2163b7933f34649;host=us-cdbr-east-04.cleardb.com;charset=utf8', 'ba134650246312', '76422bc0');
}catch(PDOException $e){
    print('DB接続エラー：' . $e->getMessage());
}