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
	<title>新規会員登録</title>
	<link rel="stylesheet" href="../style.css" />
	<link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div id="wrap">
	<div id="head">
		<h1><a class="home_link" href="../index.php">ROOMY</a></h1>
		<div class="head-login"><a class="login_button" href="../login.php">ログイン</a></div>
  	</div>
	<div id="content">
		<div class="content_bg_form">
			<p>次のフォームに必要事項をご記入ください</p>
			<form action="" method="post" enctype="multipart/form-data">
				<div class="cp_iptxt">
					<label class="ef">
						<input type="text" placeholder="Name" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" />
						<?php if($error['name'] === 'blank'): ?>
						<p class="error">＊名前を入力してください</p>
						<?php endif; ?>
					</label>
				</div>
				<div class="cp_iptxt">
					<label class="ef">
						<input type="text" placeholder="Email" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" />
						<?php if($error['email'] === 'blank'): ?>
						<p class="error">＊メールアドレスを入力してください</p>
						<?php endif; ?>
						<?php if($error['email'] === 'duplicate'): ?>
						<p class="error">＊指定されたメールアドレスは使われています</p>
						<?php endif; ?>
					</label>
				</div>
				<div class="cp_iptxt">
					<label class="ef">
						<input type="password" placeholder="Password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
						<?php if($error['password'] === 'length'): ?>
						<p class="error">＊パスワード４文字以上で入力してください。</p>
						<?php endif; ?>
						<?php if($error['password'] === 'blank'): ?>
						<p class="error">＊パスワードを入力してください。</p>
						<?php endif; ?>
					</label>
				</div>
				<div class="login-button">
					<input type="submit" value="入力内容を確認する" />
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>
