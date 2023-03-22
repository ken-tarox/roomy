<?php
session_start();

require('../dbconnect.php');

if(!isset($_SESSION['join'])){
	header('Location: index.php');
	exit();
}

if(!empty($_POST)){
	
	$check=$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, created=NOW()');
	$statement->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		sha1($_SESSION['join']['password'])
	));
	unset($_SESSION['join']);
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
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>
<body>
<div id="wrap">
	<div id="head">
		<h1>会員登録</h1>
	</div>
	<div id="content">
		<div class="content_bg_form">
		<p>予約内容を確認して、「登録する」ボタンをクリックしてください</p>
		<form action="" method="post">
			<input type="hidden" name="action" value="submit" />
			<div class="reserve_info">
				<dl>
					<dt>ニックネーム</dt>
					<dd>
					<?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
					<dt>メールアドレス</dt>
					<dd>
					<?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
					</dd>
					<dt>パスワード</dt>
					<dd>
					***********
					</dd>
				</dl>
			</div>
			<div class="login-button"><input type="submit" value="登録する" /></div>
			<div class="rewrite-button"><a href="index.php?action=rewrite">登録内容修正</a></div>
		</form>
		</div>
	</div>
</div>
</body>
</html>
