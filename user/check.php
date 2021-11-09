<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['reserve'])){
	header('Location: index.php');
	exit();
}

// 日付、時間の合算
$date1 = $_SESSION['reserve']['date'];
$starttime1 = $_SESSION['reserve']['starttime1'];
$duration =$_SESSION['reserve']['duration'];

$sday = new DateTimeImmutable($date1.$starttime1);
$starttime2= $sday->format('Y-m-d H:i');

$sday2 = new DateTimeImmutable($date1.$starttime1);
$endtime2 = $sday2->modify('+'.$duration.'minutes')->format('Y-m-d H:i');
$endtime = $sday2->modify('+'.$duration.'minutes')->format('H:i');

// 日時修正セッション保存
$_SESSION['reserve']['starttime'] = $starttime2;
$_SESSION['reserve']['endtime'] = $endtime2;


// データベース保存
if(!empty($_POST)){
	$reservation = $db->prepare('INSERT INTO reservations SET member_id=?, date=?, item=?, starttime=?, duration=?, endtime=?, created=NOW()');
	$reservation->execute(array(
		$_SESSION['id'],
		$_SESSION['reserve']['date'],
		$_SESSION['reserve']['item'],
		$_SESSION['reserve']['starttime'],
		$_SESSION['reserve']['duration'],
		$_SESSION['reserve']['endtime']
	));
	unset($_SESSION['reserve']);

	header('Location: index.php');
	exit();
}

$existance = $db->prepare('SELECT * FROM reservations WHERE starttime<? AND endtime>? AND item=?');
$existance -> execute(array(
	$_SESSION['reserve']['endtime'],
	$_SESSION['reserve']['starttime'],
	$_SESSION['reserve']['item'])
	);
if($existance->fetch() === false) {
	$class="";
	$notice='予約内容を確認して、正しければ「予約する」ボタンをクリックしてください';
}else{
	$class="hide";
	$notice='入力した日時は既に予約が入っております。予約状況を確認していただき別の日時を入力してください';
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会議室予約</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="../style.css">
</head>
<body>
	<nav class="navbar navbar-name">
	<div class="container-fluid title">
		<a class="navbar-brand" href="index.php">
		ROOMY<span>会議室予約</span>
		</a>
		<div class="d-flex">
		<a class="btn btn-gr" href="../logout.php" role="button">ログアウト</a>
		</div>
	</div>
	</nav>
<div class="container pb-5">
	<div class="row form-signin3 mt-5 " >
		<h3>会議室予約確認</h3>
	</div>
	<div class="row py-3 form-signin3 resev_st"> 	
		<form action="" method="post" class="p-2">
			<input type="hidden" name="action" value="submit" />
			<dl>
				<dt class="font-weight-normal">予約日</dt>
				<dd class="font-weight-bold mb-3">
				<?php print(htmlspecialchars($_SESSION['reserve']['date'], ENT_QUOTES)); ?>
				<dt class="font-weight-normal">部屋タイプ</dt>
				<dd class="font-weight-bold mb-3">
				<?php print(htmlspecialchars($_SESSION['reserve']['item'], ENT_QUOTES)); ?>
				</dd>
				<dt class="font-weight-normal">利用開始時刻</dt>
				<dd class="font-weight-bold mb-3">
				<?php print(htmlspecialchars($_SESSION['reserve']['starttime1'], ENT_QUOTES)); ?>
				</dd>
				<dt class="font-weight-normal">利用時間</dt>
				<dd class="font-weight-bold mb-3">
				<?php print(htmlspecialchars($_SESSION['reserve']['duration'], ENT_QUOTES) . '分間'); ?>
				</dd>
				<dt class="font-weight-normal">終了時刻</dt>
				<dd class="font-weight-bold mb-3">
				<?php print(htmlspecialchars($endtime, ENT_QUOTES)); ?>
				</dd>
			</dl>

			<div>
				<p><?php echo $notice;?></p>
				<input class="<?php echo $class ?> btn btn-primary btn-block mb-3" type="submit" value="予約する" >
				<a href="index.php?action=rewrite" class="btn btn-secondary btn-block ">予約修正</a> 
			</div>
		</form>
	</div>
</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
