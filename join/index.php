<?php 
session_start();
require('../dbconnect.php');

if(!empty($_POST)){
	if($_POST['name'] === ''){
		$error['name'] = 'blank';
	}
	if($_POST['email'] === ''){
		$error['email'] = 'blank';
	}
	if(strlen($_POST['password']) < 4){
		$error['password'] = 'length';
	}
	if($_POST['password'] === ''){
		$error['password'] = 'blank';
	}

	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if($record['cnt'] > 0){
			$error['email'] = 'duplicate';
		}
	}

	if(empty($error)){
		
		$_SESSION['join'] = $_POST;
		header('Location: check.php');
		exit();
	}
}

if($_REQUEST['action'] == 'rewrite' && isset($_SESSION['join'])){
	$_POST = $_SESSION['join']; 
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>予約システム会員登録</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="../style.css" />
</head>

<body>
<nav class="navbar navbar-name">
  <div class="container-fluid title">
    <a class="navbar-brand" href="../index.php">
      ROOMY<span>会議室予約</span>
    </a>
    <div class="d-flex">
      <a class="btn btn-gr" href="../login.php" role="button">ログイン</a>
    </div>
  </div>
</nav>
<div class="container pb-5">
	<form action="" method="post" enctype="multipart/form-data" class="form-signin mt-5">
		<h1 class="h3 mb-3 font-weight-normal">サインイン</h1>
		<dl>
			<dd>
				<input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" class="form-control" placeholder="Name" required autofocus/>
				<?php if($error['name'] === 'blank'): ?>
				<p class="error">＊名前を入力してください。</p>
				<?php endif; ?>
			</dd>
			<dd>
				<input type="email" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" class="form-control" placeholder="Email" required autofocus/>
				<?php if($error['email'] === 'blank'): ?>
				<p class="error">＊メールアドレスを入力してください。</p>
				<?php endif; ?>
				<?php if($error['email'] === 'duplicate'): ?>
				<p class="error">＊指定されたメールアドレスは使われています。</p>
				<?php endif; ?>
			</dd>
			<dd>
				<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" class="form-control" placeholder="Password" required autofocus/>
				<?php if($error['password'] === 'length'): ?>
				<p class="error">＊パスワード４文字以上で入力してください。</p>
				<?php endif; ?>
				<?php if($error['password'] === 'blank'): ?>
				<p class="error">＊パスワードを入力してください。</p>
				<?php endif; ?>
			</dd>
		</dl>
		<div><input class="btn btn-lg btn-primary btn-block"  type="submit" value="入力内容確認" /></div>
	</form>
</div>
　<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
