<
<?php
session_start();
require('dbconnect.php');

ini_set('display_errors', 'off');
// dbconnectとの接続


if(!empty($_POST)){
	// check if get some information or not when submit button was pushed 
	if ($_POST['member_code'] === ''){
		$error['member_code'] = 'blank';
	}
	if ($_POST['member_address'] === ''){
		$error['member_address'] = 'blank';
	}
	

// アカウントの重複をチェック
// blank等の確認がまず先↑

	if(empty($error)){


		header('Location: remind.php');
		exit();
	}
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Login | PCS</title>
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
				<a class="nav-link" href="managerlogin.php">管理者ログイン</a>
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
<h1><br><br>パスワード忘れた方</h1>
</div>

<div id="content">
<p>社員コードと登録したメールアドレスをご記入ください。</p>
<p>※ メールアドレスを登録していない場合は確認できません。システム課に確認願います。</p>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>社員コード<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="member_code" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['member_code'], ENT_QUOTES)); ?>" />
					<?php if ($error['member_code'] === 'blank'): ?>
					<p class="error">* 社員コードを入力してください</p>
					<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="member_address" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['member_address'], ENT_QUOTES)); ?>" /> <!-- 他の項目に入力ミスがあったとき、すでに入力済みの項目はそのまま表示する -->
					<?php if ($error['member_address'] === 'blank'): ?>
					<p class="error">* メールアドレスを入力してください</p>
					<?php endif; ?>
		<dt>パスワード<span class="required">必須</span></dt>
	</dl>
	<div><input type="submit" value="メールを送信する" /></div>
	<br>
	<div>>>思い出した方は<a href="login.php">ログイン画面</a>へ</div>
</form>
</div>
</body>
</html>
