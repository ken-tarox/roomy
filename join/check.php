<?php
session_start();
require('../dbconnect.php');

if(!isset($_SESSION['join'])){
	header('Location: index.php');
	exit();
}

if(!empty($_POST)){
	$statement = $db->prepare('INSERT INTO members SET name=?, email=?, password=?, created=NOW()');
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
	<title>新規登録</title>
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
		<h3>新規登録</h3>
	</div>
	<div class="row py-3 form-signin3 resev_st"> 	
		<form action="" method="post" class="p-2">
			<input type="hidden" name="action" value="submit" />
			<dl>
				<dt class="font-weight-normal">ニックネーム</dt>
				<dd class="font-weight-bold mb-3">
				<?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
				<dt class="font-weight-normal">メールアドレス</dt>
				<dd class="font-weight-bold mb-3">
				<?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
				</dd>
				<dt class="font-weight-normal">パスワード</dt>
				<dd class="font-weight-bold mb-3">
				***********
				</dd>
			</dl>
			<p>入力した内容が正しければ「登録する」ボタン、内容を修正する場合は「修正する」ボタンをクリックしてください</p>
			<div>
			<input type="submit" value="登録する" class="btn btn-primary btn-block mb-3" >
			<a href="index.php?action=rewrite" class="btn btn-secondary btn-block ">修正する</a>
			</div>
		</form>
	</div>
</div>
</body>
</html>
