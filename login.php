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
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログイン</title>
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>ログイン</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>登録されたメールアドレスとパスワードを入力してログインボタンを押してログインしてください。</p>
      <p>予約システム入会登録がまだの方はこちらからどうぞ。</p>
      <p><a href="join/">予約システム入会登録</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>メールアドレス</dt>
        <dd>
          <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>" />
          <?php if($error['login'] === 'blank'): ?>
            <p class="error">メールアドレスとパスワードを入力してください</p>
          <?php endif; ?>
          <?php if($error['login'] === 'failed'): ?>
            <p class="error">ログインに失敗しました。正しくご記入ください</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログイン" />
      </div>
      <div>
        <p><a href="/roomy">トップページ</a></p>
      </div>
    </form>
  </div>
  <div id="foot">
    <p></p>
  </div>
</div>
</body>
</html>