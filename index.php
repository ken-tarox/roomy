<?php
  date_default_timezone_set('Asia/Tokyo');
  require('dbconnect.php');

  session_start();
  header('Expires:-1');
  header('Cache-Control:');
  header('Pragma:');

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
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会議室予約システム</title>

	
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1/i18n/jquery.ui.datepicker-ja.min.js"></script>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">

  <script type="text/javascript"> 
    $(function() {
      document.f.submit();
    }
    return false;
    ); 
  </script> 
</head>

<body>
<nav class="navbar navbar-name">
  <div class="container-fluid title">
    <a class="navbar-brand" href="index.php">
      ROOMY<span>会議室予約</span>
    </a>
    <div class="d-flex">
      <a class="btn btn-gr" href="login.php" role="button">ログイン</a>
    </div>
  </div>
</nav>

<div class="container pb-5">
  z

  <div class="row form-signin2">
    <h3>予約状況</h3>
  </div>

  <div class="row form-signin2">
    <div id="slider" class="table-responsive mb-5">
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
          <?php require('reservation_table.php'); ?> 
        </tbody>
      </table>
    </div>
  </div>

  <div class="row ">
    <div class="col-md-7  mx-auto rounded signin text-center">
      <p class="mt-5">アカウント登録していない方はサインインしてください</p>
      <a class="btn btn-lg btn-block btn-gr mt-3 mb-5"  href="join/index.php" role="button">サインイン</a>
    </div>
  </div>
</div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
