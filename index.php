<?php
date_default_timezone_set('Asia/Tokyo');
require('dbconnect.php');

session_start();

// if(empty($error)){
		
// 		$_SESSION['join'] = $_POST;
// 		header('Location: check.php');
// 		exit();
// 	}

// if(!empty($_POST)){
// 	if($_POST['date'] === ''){
// 		$error['date'] = 'blank';
// 	}
// // }

// if(($_POST)){
//   header('Location: index.php');
// 		exit();
// 	}
// }

$format = 'Y-m-d';
$now = new \DateTime();

$datey = $now->format('Y-m-d');

if(empty($_POST)){
	$date = $datey;
}else{
  $date = $_POST['date'];
}

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

$statementdate = $db->prepare("SELECT * FROM reservations r WHERE r.date ='$date'");
$statementdate->execute();

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

	<link rel="stylesheet" href="style.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css">
 
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  

  <!-- <script type="text/javascript"> 
    $(function() { 
      document.myform.submit()
    }
    return false;
    ); 
</script>  -->

  <script type="text/javascript">
  
    window.onload = function(){
      document.myform.submit();
      }
    return false;
  </script>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ROOMY<span>会議室予約</span></h1>
    <div class="head-login"><a href="login.php">ログイン</a></div>
  </div>
  <div id="content">
    <div id="topic-ttl">
      <h3>日付選択</h3>
    </div>
    <form action="" method="post" enctype="multipart/form-data" name="myform">
      <input type="text" name="date" id="datepicker" value="<?php print($date); ?>">
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
      <h3>予約状況</h3>
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
        
        for($roomnum = 1; $roomnum <= 3; $roomnum++){
        echo '<tr>';
        echo '<th>ROOM'.$roomnum.'</th>';
        echo '<div class=""> ';
        //1セルずつ処理
        for ($i = 1; $i <=48 ; $i++)
        {
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
          $bg = 'bgcolor=#C0C0C0';
          echo "<td $bg class=$i></td>";
        } else {
          $bg_emp ='bgcolor=#FFFFFF';
          echo "<td $bg_emp class=$i></td>";
        }
      }}
        ?>
      </table>
    </div>  

    <div class="new-resist">
      <p>アカウント登録してない方は新規登録をしてください</p>
      <a href="join/index.php">新規会員登録</a>
    </div>

    <?php foreach($posts as $post): ?>
    <div class="msg">
      <img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>" />
      <p>
        <?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?><span class="name">（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>）</span>[<a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">Re</a>]
      </p>
      <p class="day">
        <a href="view.php?id=<?php print(htmlspecialchars($post['id'])); ?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>

        <?php if($post['reply_message_id'] > 0): ?>
        <a href="view.php?id=<?php print(htmlspecialchars($post['reply_message_id'], ENT_QUOTES));?>">
        返信元のメッセージ</a>
        <?php endif; ?>

        <?php if($_SESSION['id'] == $post['member_id']): ?>
        [<a href="delete.php?id=<?php print(htmlspecialchars($post['id'])); ?>"
        style="color: #F33;">削除</a>]
        <?php endif; ?>
      </p>
    </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
