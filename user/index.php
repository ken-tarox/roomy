<?php
date_default_timezone_set('Asia/Tokyo');
require('../dbconnect.php');

session_start();

if(!empty($_POST)){
	if(!$_POST['date'] === ''){
		$error['date'] = 'blank';
	}
	if($_POST['item'] === ''){
		$error['item'] = 'blank';
	}
  if($_POST['starttime1'] === ''){
		$error['starttime1'] = 'blank';
	}
  if($_POST['duration'] === ''){
		$error['duration'] = 'blank';
	}
  if(empty($error) && isset($_POST['submit1'])){
    $_SESSION['reserve'] = $_POST;
    unset($_SESSION['reserve']['submit1']);
    header('Location: check.php');
    exit();
  }
}

if(isset($_SESSION['id']) && $_SESSION['time'] +3600 > time()){
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
}else{
  header('Location: ../login.php');
  exit();
}

$format = 'Y-m-d';
$now = new DateTime('+1 day');
$datey = $now->format('Y-m-d');

if(empty($_POST)){
	$date = $datey;
}else{
  $date = $_POST['date'];
}
$timestamp = strtotime($date); 
$newDate = date("m月d日", $timestamp );

$item = ['item1'=>'ROOM1',
        'item2'=>'ROOM2',
        'item3'=>'ROOM3'
        ];

foreach($item as $item_val){
  $item .="<option value='". $item_val;
  $item .="'>". $item_val. "</option>";
}

$duration = [
        '15'=>'15分',
        '30'=>'30分',
        '45'=>'45分',
        '60'=>'1時間',
        '75'=>'1時間15分',
        '90'=>'1時間30分',
        '105'=>'1時間45分',
        '120'=>'2時間'
        ];

foreach($duration as $duration_key => $duration_val){
  $duration .="<option value='". $duration_key;
  $duration .="'>". $duration_val. "</option>";
}

$reservations = $db->query('SELECT r.* FROM members m, reservations r WHERE m.id=r.member_id ORDER BY r.starttime ASC');
$reservations->execute();


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
	<title>会議室予約システム</title>
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
			<a class="btn btn-gr mr-2" href="../admin/index.php" role="button">管理者ページ</a>
			<?php endif; ?>
      <a class="btn btn-gr" href="../logout.php" role="button">ログアウト</a>
    </div>
</div>
</nav>
<div class="container pb-5">
  <div class="my-3 row form-signin2">
    <p><span class="font-weight-bold"><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さん</span>ログイン中</p>
  </div>
  <div class="row form-signin2">
    <h3>日付選択</h3>
  </div>
  <div class="mb-5 row form-signin2">
    <form action="" method="post" enctype="multipart/form-data" name="myform">
      <input type="text" name="date" id="datepicker" value="<?php print($date); ?>" readonly="readonly" />
      <script>
        $("#datepicker").datepicker({
          dateFormat: 'yy-mm-dd',
          datepicker:false,
          firstDay: 1, 
          monthNames: [ "1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月" ],
          minDate: '+1d',
          maxDate: "+3m",

          onSelect : function(dateText, inst) {
            document.myform.submit();
        }
      });
      </script>
    </form>
  </div>

  <div class="row form-signin2">
    <h3>予約状況</h3>
  </div>

  <div class="mb-5 row form-signin2">
    <div id="slider" class="table-responsive">
      <table class="table table-bordered" >
        <thead>
          <tr>
            <th scope="col"><?php print($newDate); ?></th>
            <?php
            for ($num = 9; $num <= 20; $num++){
              echo('<th scope="col" colspan="4">'.$num.'</th>') ;
            }
            ?>
          </tr>
        </thead>
        <tbody>
          <?php require('../reservation_table.php'); ?> 
        </tbody>
      </table>
    </div>
  </div>

  <div class="row form-signin2 mt-5 " >
    <h3>新規会議室予約</h3>
  </div>

  <div class="row p-3 form-signin2 resev_st"> 
    <form action="" method="post" enctype="multipart/form-data" name="mydata" class="form-signin2" >
      <div class="d-flex d-flex flex-wrap justify-content-center" style="white-space: nowrap">
        <div class="mr-4 flex-fill">
          <dt class="mb-2">予約日</dt>
          <dd>
            <input type="hidden" value="<?php print(htmlspecialchars($date, ENT_QUOTES)); ?>" name="date"/>
            <?php print(htmlspecialchars($date, ENT_QUOTES)); ?>
          </dd>
        </div>
        <div class="mr-4 flex-fill">
          <dt class="mb-2">部屋タイプ</dt>
          <dd>
            <select name="item" class="custom-select custom-select-md">
              <?php
              echo $item; ?>
            </select>
          </dd>
        </div>
        <div class="mr-4 flex-fill">
          <dt class="mb-2">開始時刻</dt>
          <dd>
            <input type="text"  id="onselectExample"  name="starttime1" class="custom-select custom-select-md " readonly="readonly" />
            <script>
              $('#onselectExample').timepicker({
                minTime: '9:00',
                maxTime: '20:45',
                step: 15,
                timeFormat: 'H:i',
              });
            </script>
          </dd>
        </div>

        <div class="flex-fill">
          <dt class="mb-2">利用時間：</dt>
          <dd>
            <select name="duration" class="custom-select custom-select-md">
              <?php echo $duration; ?>
            </select>
          </dd>
        </div>
      </div>

      <div class="mt-3 text-right">
        <input type="submit" name="submit1" value="次へ進む" class="btn btn-primary ">
      </div>
    </form>
  </div>

  <div class="row form-signin2 mt-5 ">
    <h3>予約一覧</h3>
  </div>

  <div class="row table-responsive form-signin2 resev_st">
  <table class="table table-striped" style="white-space: nowrap">
    <thead>
      <tr>
        <th>部屋名</th>
        <th>日付</th>
        <th>開始時刻</th>
        <th>終了時刻</th>
        <th></th>
      </tr>
    </thead>

    <tbody>
      <?php foreach($reservations as $reservation): ?>
      <?php if($_SESSION['id'] == $reservation['member_id'] &&$reservation['date'] >= $datey): ?>
        <tr>
        <th>
          <?php print(htmlspecialchars($reservation['item'], ENT_QUOTES)); ?>
        </th>
        <th>
          <?php
          $timestamp4 = strtotime($reservation['date']); 
          $w = date("w", $timestamp4);
          $week_name = array("日", "月", "火", "水", "木", "金", "土");
          $datTim = date("Y年m月d日", $timestamp4). "($week_name[$w])\n";
          print(htmlspecialchars($datTim, ENT_QUOTES));
          ?>
        </th>
        <th>
          <?php 
          $timestamp2 = strtotime($reservation['starttime']); 
          $stTim = date("
          H時i分", $timestamp2);
          print(htmlspecialchars($stTim, ENT_QUOTES)); 
          ?>
        </th>
        <th>
          <?php 
          $timestamp3 = strtotime($reservation['endtime']); 
          $enTim = date("
          H時i分", $timestamp3);
          print(htmlspecialchars($enTim, ENT_QUOTES)); 
          ?>
        </th>
        <th> 
          <a href="delete.php?id=<?php print(htmlspecialchars($reservation['id'])); ?>" class="btn btn-info btn-sm">削除</a>
        </th>
      <?php endif; ?>
      <?php endforeach; ?>
      </tr>
    </tbody>
  </table>
  </div>
</div>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
