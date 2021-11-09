<?php
date_default_timezone_set('Asia/Tokyo');
require('../dbconnect.php');

session_start();

if(isset($_SESSION['id']) && $_SESSION['time'] +3600 > time()){
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();

}else{
  header('Location:login.php');
  exit();
}
if($member['admin'] == 0){
  header('Location:login.php');
}

$format = 'Y-m-d';
$now = new DateTime('');
$datey = $now->format('Y-m-d');

if(empty($_POST)){
	$date = $datey;
}else{
  $date = $_POST['date'];
}
$timestamp = strtotime($date); 
$newDate = date("mm月dd日", $timestamp );



$reservations = $db->query('SELECT r.* FROM members m, reservations r WHERE m.id=r.member_id ORDER BY r.starttime ASC');
$reservations->execute();

$delmembers = $db->query('SELECT m.* FROM members m ORDER BY m.created ASC');
$delmembers->execute();


$statement = $db->query('SELECT r.* FROM members m, reservations r WHERE m.id=r.member_id');
$statement->execute();


$record = $db->query('SELECT count(*) FROM reservations');
  $record->execute();
  $count = $record->fetchColumn();

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>管理者ページ</title>
	<link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.rawgit.com/jonthornton/jquery-timepicker/3e0b283a/jquery.timepicker.min.css">
  <script src="https://cdn.rawgit.com/jonthornton/jquery-timepicker/3e0b283a/jquery.timepicker.min.js"></script>
  <link rel="stylesheet" href="../style.css">
</head>

<body>
  <nav class="navbar navbar-name">
    <div class="container-fluid title">
      <a class="navbar-brand" href="index.php">
        ROOMY<span>会議室予約</span>
      </a>
      <div class="d-flex">
        <?php if($member['admin'] == 1): ?>
        <a class="btn btn-gr mr-2" href="../user/index.php" role="button">ユーザーページ</a>
        <?php endif; ?>
        <a class="btn btn-gr" href="logout.php" role="button">ログアウト</a>
      </div>
    </div>
  </nav>
  <div class="container pb-5">
    <div class="my-3 row form-signin2">
      <p><span class="font-weight-bold"><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さん</span>ログイン中</p>
    </div>
    <p>
      <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="true" aria-controls="multiCollapseExample2">予約一覧</button>
      <a class="btn btn-primary" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="true"  aria-controls="multiCollapseExample1">会員一覧</a>
    </p>
    <div class="row">
      <div class="col">
        <div class="show multi-collapse" id="multiCollapseExample2">
          <?php require('reservation.php'); ?> 
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="collapse multi-collapse" id="multiCollapseExample1">
          <?php require('user.php'); ?> 
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
