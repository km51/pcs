<?php
session_start();
require('dbconnect.php');

ini_set('display_errors', 'on');

if (!isset($_SESSION['join'])){
	header('Location: register.php');
	exit();

}
if(!empty($_POST)){
	// if there is something in box
	// DBへの登録について
	$statement = $db -> prepare('INSERT INTO members (member_name, member_code, member_password, member_picture, member_address, created) VALUES (?, ?, ?, ?, ?,NOW())');
	// NOW()は登録された日時
	$statement->execute(array(
			$_SESSION['join']['name'],
			$_SESSION['join']['code'],
			sha1($_SESSION['join']['password']),
			$_SESSION['join']['image'],
			$_SESSION['join']['email']
	));
	unset($_SESSION['join']);
	header('Location: thanks.php');
	exit();
}

//  check.phpは2回呼び出される。一つは、フォームに入力された時（このときには登録せず）、もう一つは、内容の確認場面で「登録する」ボタンを押した時



?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Register | PCS</title>
	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="css/fixed.css">
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
				<a class="nav-link" href="index.php">簡単ログイン</a>
			</li>
		</ul>
	</div>
</nav>
<div id="wrap">
<div id="head">
<h1><br><br>社員登録</h1>
</div>

<div id="content">
<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<form action="" method="post">
	<input type="hidden" name="action" value="submit" />
	<dl>
		<dt>名前</dt>
		<dd>
		<?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
        </dd>
		<dt>社員コード</dt>
		<dd>
		<?php print(htmlspecialchars($_SESSION['join']['code'], ENT_QUOTES)); ?>
        </dd>
		<dt>パスワード</dt>
		<dd>
		【表示されません】
		</dd>
		<dt>メールアドレス</dt>
		<dd>
		<?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
        </dd>
		<dt>写真など</dt>
		<dd>
		<?php if ($_SESSION['join']['image'] !== ''): ?>
			<img src="member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>">
		<?php endif; ?>
		</dd>
	</dl>
	<div><a href="register.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
</form>
</div>

</div>
<script src="js/jquery-3.3.1.min.js"></script>
script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>

</body>
</html>
