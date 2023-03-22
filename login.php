<?php
session_start();
require('dbconnect.php');

if($_COOKIE['email'] !==''){
  $email = $_COOKIE['email'];
}

if(!empty($_POST)){
  $email = $_POST['email'];
  if($_POST['email'] !=='' && $_POST['password'] !==''){
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password'])
    ));
    $member = $login->fetch();
    
    if($member){
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();
      
      if($_POST['save'] === 'on'){
        setcookie('email', $_POST['email'], time()+60*60*24*14);
      }
      header('Location: user/index.php');
      exit();
    }else{
      $error['login'] = 'failed';
    }
  }else{
    $error['login'] = 'blank';
  }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
<title>ログイン</title>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1><a class="home_link" href="index.php">ROOMY</a></h1>
  </div>
  <div id="content">
    <div class="content_bg_form">
      <form action="" method="post">
        <div class="cp_iptxt">
          <label class="ef">
            <input type="text" placeholder="Email" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>" />
            <?php if($error['login'] === 'blank'): ?>
              <p class="error">メールアドレスとパスワードを入力してください</p>
            <?php endif; ?>
            <?php if($error['login'] === 'failed'): ?>
              <p class="error">ログインに失敗しました。正しくご記入ください</p>
            <?php endif; ?>
            </label>
        </div>
        <div class="cp_iptxt">
          <label class="ef">
            <input type="password" placeholder="Password" name="password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
          </label>
        </div>
        <div class="login-info">
          <div class="login-mention">
            <div class="login-title">ログイン情報の記録</div>
            <input id="save" type="checkbox" name="save" value="on">
            <label for="save">次回からは自動的にログインする</label>
            <div class="login-button">
              <input type="submit" value="ログイン" />
            </div>
          </div>
          <div class="lead">
            <p>入会登録がまだの方はこちらからどうぞ</p>
            <div class="join-button"><a href="join/">新規入会登録</a></div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>