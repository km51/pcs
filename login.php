<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'off');

if ($_COOKIE['member_code'] !== ''){
  $code = $_COOKIE['member_code'];
}

if (!empty($_POST)){
  $code = $_POST['member_code'];
   if ($_POST['member_code']!==''&& $_POST['member_password'] !== ''){
    $login = $db->prepare('SELECT * FROM members WHERE member_code=? AND member_password=?');
    $login->execute(array(
      $_POST['member_code'],
      sha1($_POST['member_password'])
    ));
    $member= $login->fetch();

    if($member){
      $_SESSION['member_id'] = $member['member_id'];
      $_SESSION['time'] = time();
      if ($_POST['save'] === 'on'){
        setcookie('member_code', $_POST['member_code'], time()+60*60*24*14);
      }

      header('Location: index.php');
      exit();
    }else{
      // ログインに失敗
      $error['login'] = 'failed';
    }
  }else{
    $error['login'] = 'blank';
  }
}
?>




<!DOCTYPE html>
<html land="ja">
<head>
<meta charset="UTF-8"/>
	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="css/fixed.css">
<title>Login | PCS</title>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="login.php">Production Control System</a>
	
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarResponsive">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
        <a class="nav-link" href="login.php">ログイン</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="register.php">登録</a>
      </li>
      <li class="nav-item">
				<a class="nav-link" href="guestlogin.php">簡単ログイン</a>
			</li>
		</ul>
	</div>
</nav>
<div id="wrap">
  <div id="head">
    <h1><br><br>ログインする</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>社員コードとパスワードを記入してログインしてください。</p>
      <p>社員登録がまだの方はこちらからどうぞ。</p>
      <p>&raquo;<a href="register.php">社員登録をする</a></p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>社員コード</dt>
        <dd>
          <input type="text" name="member_code" size="35" maxlength="255" value="<?php print(htmlspecialchars($code, ENT_QUOTES)); ?>" />
          <?php if($error['login'] === 'blank'): ?>
          <p class='error'>* 社員コードとパスワードをご記入ください</p>
          <?php endif; ?>
          <?php if($error['login'] === 'failed'): ?>
          <p class='error'>* ログインに失敗しました。正しくご記入ください</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="member_password" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['member_password'], ENT_QUOTES)); ?>" />
        </dd>
        <dt>ログイン情報の記録</dt>
        <dd>
          <input id="save" type="checkbox" name="save" value="on">
          <label for="save">次回からは自動的にログインする</label>
          <!-- sessionでは、ブラウザを閉じるとデータが消えるため、cookieでデータを保存しておく仕組みを使う。 -->
        </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" />
      </div>
      <br>
      <h4>※ パスワードを忘れた方はシステム課へ確認！</h4>
      >><a href="forget.php"> 今すぐパスワードを確認するには</a>
    </form>
  </div>
  <div id="foot">
    <!-- <p><img src="images/txt_copyright.png" width="136" height="15" alt="" /></p> -->
  </div>
</div>
<script src="./js/jquery-3.3.1.min.js"></script>
</body>
</html>
