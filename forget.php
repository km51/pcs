<
<?php
session_start();
require('dbconnect.php');

ini_set('display_errors', 'off');
// dbconnectとの接続


if(!empty($_POST)){
	// check if get some information or not when submit button was pushed 
	if ($_POST['name'] === ''){
		$error['name'] = 'blank';
	}
	if ($_POST['email'] === ''){
		$error['email'] = 'blank';
	}
	
	if (strlen($_POST['password']) < 4){
		$error['password'] = 'length';
	}
	if ($_POST['password'] === ''){
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if(!empty($fileName)){
		$ext = substr($fileName, -3);

		if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
			$error['image'] = 'type';
			}	
	}	

// アカウントの重複をチェック
// blank等の確認がまず先↑
	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0){
			$error['email'] = 'duplicate';
		}
	}

	if(empty($error)){
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;

		header('Location: remind.php');
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
        	<input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" />
					<?php if ($error['name'] === 'blank'): ?>
					<p class="error">* 社員コードを入力してください</p>
					<?php endif; ?>
		</dd>
		<dt>メールアドレス<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" /> <!-- 他の項目に入力ミスがあったとき、すでに入力済みの項目はそのまま表示する -->
					<?php if ($error['email'] === 'blank'): ?>
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
