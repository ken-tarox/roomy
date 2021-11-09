<?php
session_start();
require('../dbconnect.php');

if($_COOKIE['email'] !==''){
  $email = $_COOKIE['email'];
}

if(!empty($_POST)){
  $email = $_POST['email'];
  if($_POST['email'] !=='' && $_POST['password'] !==''){
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=? AND admin=true');
    $login->execute(array(
      $_POST['email'],
      sha1($_POST['password']
      )
    ));
    $member = $login->fetch();

    if($member){
      $_SESSION['id'] = $member['id'];
      $_SESSION['time'] = time();
      

      if($_POST['save'] === 'on'){
        setcookie('email', $_POST['email'], time()+60*60*24*14);
      }
      header('Location: index.php');
      exit();
    }else{
      $error['login'] = 'failed';
    }
  }else{
    $error['login'] = 'blank';
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>管理者ログイン</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="../style.css">
</head>

<body>
<nav class="navbar navbar-name">
  <div class="container-fluid title">
    <a class="navbar-brand" href="../index.php">
      ROOMY<span>会議室予約</span>
    </a>
  </div>
</nav>
  <div class="container pb-5">
      <form action="" method="post" class="form-signin mt-5">
        <h1 class="h3 mb-3 font-weight-normal">管理者ログイン</h1>
          <dl>
            <dd>
              <input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($email, ENT_QUOTES)); ?>" class="form-control" placeholder="Email" required autofocus/>
              <?php if($error['login'] === 'blank'): ?>
                <p class="error">メールアドレスとパスワードを入力してください</p>
              <?php endif; ?>
              <?php if($error['login'] === 'failed'): ?>
                <p class="error">ログインに失敗しました。正しくご記入ください</p>
              <?php endif; ?>
            </dd>
          
            <dd>
              <input type="password" name="password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" class="form-control" placeholder="Password" required autofocus/>
            </dd>
          </dl>
          <div>
            <input class="btn btn-lg btn-primary btn-block" type="submit" value="ログイン" />
          </div>
      </form>
   </div>
</div>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>