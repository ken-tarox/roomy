<?php
date_default_timezone_set('Asia/Tokyo');
require('../dbconnect.php');

session_start();
header("Expires:-1");
header("Cache-Control:");
header("Pragma:");

// 新規予約項目バリデーション
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

// ログイン
if(isset($_SESSION['id']) && $_SESSION['time'] +3600 > time()){
  $_SESSION['time'] = time();
  $members = $db->prepare("SELECT * FROM members WHERE id=?");
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
}else{
  header('Location: ../login.php');
  exit();
}
$format = 'Y-m-d';
$now = new DateTime();
$datey = $now->format('Y-m-d');

if(empty($_POST)){
	$date = $datey;
}else{
  $date = $_POST['date'];
}

$m_id = $_SESSION['id'];

$item = ['item1'=>'ROOM1',
        'item2'=>'ROOM2',
        'item3'=>'ROOM3'
        ];

foreach($item as $item_key => $item_val){
  $item .="<option value='". $item_key;
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
// 予約一覧
$reservations = $db->prepare("SELECT r.* FROM members m, reservations r WHERE m.id=r.member_id ORDER BY r.starttime ASC");
$reservations->execute();

// $statement = $db->prepare("SELECT r.* FROM members m, reservations r WHERE m.id=r.member_id");
// $statement->execute();

// ログインユーザーの予約状況
$statementdate = $db->prepare("SELECT r.* FROM reservations r WHERE r.member_id = $m_id and r.date ='$date'");
$statementdate->execute();

// カラム数
$record = $db->prepare('SELECT count(*) FROM reservations');
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
 
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  
  <link rel="stylesheet" href="https://cdn.rawgit.com/jonthornton/jquery-timepicker/3e0b283a/jquery.timepicker.min.css">
  <script src="https://cdn.rawgit.com/jonthornton/jquery-timepicker/3e0b283a/jquery.timepicker.min.js"></script>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ROOMY</h1>
    <div><a href="../logout.php" class="login_button">ログアウト</a></div>
  </div>
  <div id="content">
    <div id="topic-ttl">
      <h3>日付選択</h3>
    </div>
    <form action="" method="post" enctype="multipart/form-data" name="myform">
      <div class="inout_txt">
        <input type="text" name="date" id="datepicker" value="<?php print($date); ?>">
      </div>
       <script>
       $("#datepicker").datepicker({
        dateFormat: 'yy-mm-dd',
        datepicker:false,
        firstDay: 1, 
        monthNames: [ "1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月" ],
        minDate: new Date(),
        maxDate: "+3m",
        onSelect : function(dateText, inst) {
        document.myform.submit();
      }
    });
    </script>
    </form>
    <div id="topic-ttl">
      <h3><?php print(htmlspecialchars($member['name'], ENT_QUOTES)); ?>さんの予約状況</h3>
    </div>
    <div class="slider">
      <table>
        <thead>
          <tr>
            <th ><?php print($date); ?></th>
            <?php
              for ($num = 9; $num <= 20; $num++){
              echo('<th colspan="4">'.$num.'</th>') ;
              }
            ?>
          </tr>
        </thead>
        <?php
        //配列変数にデータを格納
        foreach($statementdate as $index => $state){
          $room[$index] = substr($state['item'],4);
          $strday[$index] = $state['date']."09:00:00";
          $starttime[$index] = $state['starttime'];
          $endtime[$index] = $state['endtime'];

          $strbusi[$index] = new DateTime($strday[$index]);
          $strres[$index] = new DateTime($starttime[$index]);
          $endres[$index] = new DateTime($endtime[$index]);

          $strpt[$index] = $strres[$index]->diff($strbusi[$index]);
          $tbstr[$index] = ($strpt[$index]->h*60+$strpt[$index]->i)/15;
          $endpt[$index] = $endres[$index]->diff($strres[$index]);
          $tbend[$index] = ($endpt[$index]->h*60+$endpt[$index]->i)/15+$tbstr[$index];
          }

          //1行ずつ処理
          for($roomnum = 1; $roomnum <= 3; $roomnum++){
          echo '<tr>';
          echo '<th>ROOM'.$roomnum.'</th>';
          echo '<div class=""> ';
          //1セルずつ処理
          for ($i = 1; $i <=48 ; $i++){
          $flag=false;
          //全レコードをチェック
          for ($ind = 0; $ind <= $count-1 ; $ind++)
          {
          //ルーム番号が同じ時だけ処理
          if($room[$ind]==$roomnum){
            //いずれからのレコードの時間幅の間におさまるときだけフラグをtrueに変える
            if($i >= $tbstr[$ind]+1 && $i <= $tbend[$ind]){
              $flag=true;
            }}
          }
          //フラグがtrueのときのみ色のついたセルを描画
          if($flag){
            $bg = 'bgcolor=#fec775';
            echo "<td $bg class=$i></td>";
          } else {
            $bg_emp ='bgcolor=#FFFFFF';
            echo "<td $bg_emp class=$i></td>";
          }
        }}
        ?>
      </table>
    </div>  

    <div id="topic-ttl">
      <h3>新規会議室予約</h3>
    </div>
    <div class="content_bg">
      <form action="" method="post" enctype="multipart/form-data" name="mydata" class="form_bg">
        <div class="list">
          <label class="label_bg" for="item">予約日</label>
          <input type="hidden" value="<?php print(htmlspecialchars($date, ENT_QUOTES)); ?>" name="date"/>
          <?php print(htmlspecialchars($date, ENT_QUOTES)); ?>
        </div>
        <div class="list">
          <label class="label_bg" for="item">部屋タイプ</label>
          <div class="select_box">
            <select class="" name="item">
              <?php echo $item; ?>
            </select>
          </div>
        </div>
        <div class="list">
          <label class="label_bg" for="starttime1">開始時刻</label>
          <div class="inout_txt2">
            <input type="text"  id="onselectExample"  name="starttime1"/>
          </div>
          <script>
          $('#onselectExample').timepicker({
            minTime: '9:00',
            maxTime: '20:45',
            step: 15,
            timeFormat: 'H:i',
          });
          </script>
        </div>
        <div class="list">
          <label class="label_bg" for="duration">利用時間</label>
          <div class="select_box">
            <select name="duration">
              <?php
              echo $duration; ?>
            </select>
          </div>
        </div>
        <div class="list-next">
          <input class="button_bg" type="submit" name="submit1" value="次へ進む" />
        </div>
      </form>
    </div>

    <div id="topic-ttl">
      <h3>予約一覧</h3>
    </div>
    <div class="content_bg reserve_bg">
        <table class="reserve_table">
          <thead>
            <tr class="first_tr">
              <th>部屋</th>
              <th>予約日</th>
              <th>開始時刻</th>
              <th>終了時刻</th>
              <th></th>
            </tr>
          </thead>
          <thead>
            <?php foreach($reservations as $reservation): ?>
            <?php if($_SESSION['id'] == $reservation['member_id']): ?>
              <?php $r_starttime = $reservation['starttime']?>
              <?php $r_endtime = $reservation['endtime']?>
              <?php $rc_starttime = new DateTime($r_starttime)?>
              <?php $rc_endtime = new DateTime($r_endtime)?>
              <?php $r_item = $reservation['item']?>
              
              <?php if($r_item == 'item1'): ?>
              <?php $rc_item = 'ROOM1'?>
              <?php elseif($r_item == 'item2'): ?>
              <?php $rc_item = 'ROOM2'?>
              <?php elseif($r_item == 'item3'): ?>
              <?php $rc_item = 'ROOM3'?>
              <?php endif; ?>
              <tr>
                <td><?php print(htmlspecialchars($rc_item, ENT_QUOTES)); ?></td>
                <td><?php print(htmlspecialchars($reservation['date'], ENT_QUOTES)); ?></td>
                <td><?php print(htmlspecialchars($rc_starttime->format('H:i'), ENT_QUOTES));?></td>
                <td><?php print(htmlspecialchars($rc_endtime->format('H:i'), ENT_QUOTES)); ?></td>
                <td><a class="del_button" href="delete.php?id=<?php print(htmlspecialchars($reservation['id'])); ?>">削除</a></td>
              </tr>
            <?php endif; ?>
            <?php endforeach; ?>
          </thead>
        </table>
      </div>
  </div>
</div>
</body>
</html>
