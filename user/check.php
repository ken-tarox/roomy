<?php
session_start();

require('../dbconnect.php');

// 未ログイン時強制ページ戻し
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

	$items = $_SESSION['reserve']['item'];
	$starttimes = $_SESSION['reserve']['starttime'];
	$endtimes = $_SESSION['reserve']['endtime'];

	$sql = "SELECT COUNT(*) FROM reservations WHERE item = '$items' AND 
    '$starttimes' BETWEEN starttime AND endtime";
	var_dump($sql);
	$stmt1 = $db->prepare($sql);
    $stmt1->execute();
	$count = $stmt1->fetchColumn();
	// 存在チェック後の処理 ※1
	if ($count != 0) {
		echo "レコードあり";
	} else {
		echo "レコードなし";
	};
	if ($count != 0) {
			$error['reserve'] = 'blank';
		}
	
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
	header('Location: thanks.php');
	exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会議室予約</title>
	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
	<div id="head">
		<h1>会議室予約確認</h1>
	</div>		

	<div id="content">
		<?php if($error['reserve'] === 'blank'): ?>
            <p class="error">指定の時間帯はすでに予約が入っております。予約修正をおこなってください。</p>
		<?php else: ?>
		<p>入力した内容を確認して、正しければ「予約する」ボタンをクリックしてください</p>
		<?php endif; ?>
		<form action="" method="post">
			<input type="hidden" name="action" value="submit" />
			<dl>
				<dt>予約日</dt>
				<dd>
					<?php print(htmlspecialchars($_SESSION['reserve']['date'], ENT_QUOTES)); ?>
				</dd>
				<dt>部屋タイプ</dt>
				<dd>
					<?php print(htmlspecialchars($_SESSION['reserve']['item'], ENT_QUOTES)); ?>
				</dd>
				<dt>利用開始時刻</dt>
				<dd>
					<?php print(htmlspecialchars($_SESSION['reserve']['starttime1'], ENT_QUOTES)); ?>
				</dd>
				<dt>利用時間</dt>
				<dd>
					<?php print(htmlspecialchars($_SESSION['reserve']['duration'], ENT_QUOTES) . '分間'); ?>
				</dd>
				<dt>終了時刻</dt>
				<dd>
					<?php print(htmlspecialchars($endtime, ENT_QUOTES)); ?>
				</dd>
			</dl>
			<?php if($error['reserve'] === 'blank'): ?>
				<div><a href="index.php?action=rewrite">&laquo;&nbsp;予約修正</a>
			<?php else: ?>
				<div><a href="index.php?action=rewrite">&laquo;&nbsp;予約修正</a> | <input type="submit" value="予約する" /></div>
			<?php endif; ?>	
		</form>
	</div>
</div>
</body>
</html>
