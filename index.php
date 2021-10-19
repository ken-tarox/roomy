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

	// $_POST['date'] === date('m月d日');

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会議室予約システム</title>

	<link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>

  <script type="text/javascript"> 
    $(function() { 
      document.f.submit();
    }
    return false;
    ); 
</script> 

  <!-- <script type="text/javascript">
  
    window.onload = function(){
      document.f.submit();
      }
    return false;
  </script> -->
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>会議室予約システム</h1>
  </div>
  <div id="content">

<form name="f" action="" method="post" enctype="multipart/form-data">
  <input type="text" name="date" id="datepicker" value="<?php print(date('m月d日')); ?>">
  <script>
    $("#datepicker").datepicker({
    dateFormat: 'mm月dd日',
    firstDay: 1, 
    monthNames: [ "1月", "2月", "3月", "4月", "5月", "6月", "7月", "8月", "9月", "10月", "11月", "12月" ],
    minDate: new Date(),
    maxDate: "+3m"
  });
  </script>
  <div><input   type="submit" value="予約予定日を確認する" /></div>
</form>
  
  



  <div id="">
    <h3>予約状況</h3>
  </div>
    <div class="slider">
  <table >
  <thead>
  <tr>
    <th ><?php print(htmlspecialchars($_POST['date'], ENT_QUOTES)); ?></th>
    <?php
    for ($num = 9; $num <= 20; $num++){
      echo('<th colspan="4">'.$num.'</th>') ;
    }
    ?>
  </tr>
</thead>
  <tr>
    <th>room1</th> 
    <?php $i = 1;
    while ($i <= 48) {
    echo '<td></td>';
    $i++;
    }
    ?>
  </tr>
  <tr>
    <th>room2</th> 
    <?php $i = 1;
    while ($i <= 48) {
    echo '<td></td>';
    $i++;
    }
    ?>
  </tr>
  <tr>
    <th>room3</th> 
    <?php $i = 1;
    while ($i <= 48) {
    echo '<td></td>';
    $i++;
    }
    ?>
  </tr>
  
</table>
</div>
    <div><a href="login.php">ログイン</a></div>
    <div><a href="join/index.php">予約システム会員登録</a></div>

<?php foreach($posts as $post): ?>
    <div class="msg">
    <img src="member_picture/<?php print(htmlspecialchars($post['picture'], ENT_QUOTES)); ?>" width="48" height="48" alt="<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>" />
    <p><?php print(htmlspecialchars($post['message'], ENT_QUOTES)); ?><span class="name">（<?php print(htmlspecialchars($post['name'], ENT_QUOTES)); ?>）</span>[<a href="index.php?res=<?php print(htmlspecialchars($post['id'], ENT_QUOTES)); ?>">Re</a>]</p>
    <p class="day"><a href="view.php?id=<?php print(htmlspecialchars($post['id'])); ?>"><?php print(htmlspecialchars($post['created'], ENT_QUOTES)); ?></a>

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
