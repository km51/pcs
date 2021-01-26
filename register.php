
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
	if ($_POST['code'] === ''){
		$error['code'] = 'blank';
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
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE member_code=?');
		$member->execute(array($_POST['code']));
		$record = $member->fetch();
		if ($record['cnt'] > 0){
			$error['code'] = 'duplicate';
		}
	}

	if(empty($error)){
		if($_POST['image'] !== ''){
			$image = date('YmdHis') . $_FILES['image']['name'];
			move_uploaded_file($_FILES['image']['tmp_name'], 'member_picture/' . $image);
			$_SESSION['join'] = $_POST;
			$_SESSION['join']['image'] = $image;
		}else{
			$_SESSION['join']['image'] = 'None.jpg';

		}
		header('Location: checkResister.php');
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
<p>次のフォームに必要事項をご記入ください。</p>
<form action="" method="post" enctype="multipart/form-data">
	<dl>
		<dt>名前<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="name" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['name'], ENT_QUOTES)); ?>" />
					<?php if ($error['name'] === 'blank'): ?>
					<p class="error">* 名前を入力してください</p>
					<?php endif; ?>
		</dd>
		<dt>社員コード<span class="required">必須</span></dt>
		<dd>
        	<input type="text" name="code" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['code'], ENT_QUOTES)); ?>" /> <!-- 他の項目に入力ミスがあったとき、すでに入力済みの項目はそのまま表示する -->
					<?php if ($error['code'] === 'blank'): ?>
					<p class="error">* 社員コードを入力してください</p>
					<?php endif; ?>
					<?php if ($error['code'] === 'duplicate'): ?>
					<p class="error">* 指定された社員コードはすでに登録されています</p>
					<?php endif; ?>
		<dt>パスワード<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
					<?php if ($error['password'] === 'length'): ?>
					<p class="error">* パスワードは4文字以上で入力してください</p>
					<?php endif; ?>
					<?php if ($error['password'] === 'blank'): ?>
					<p class="error">* パスワードを入力してください</p>
					<?php endif; ?>
        </dd>
    <!-- <dt>パスワード再確認用<span class="required">必須</span></dt>
		<dd>
        	<input type="password" name="password" size="10" maxlength="20" value="<?php print(htmlspecialchars($_POST['password'], ENT_QUOTES)); ?>" />
					<?php if ($error['password'] === 'length'): ?>
					<p class="error">* パスワードは4文字以上で入力してください</p>
					<?php endif; ?>
					<?php if ($error['password'] === 'blank'): ?>
					<p class="error">* パスワードを入力してください</p>
					<?php endif; ?>
        </dd> -->
    <dt>メールアドレス<span class="required"></span></dt>
		<dd>
        	<input type="text" name="email" size="35" maxlength="255" value="<?php print(htmlspecialchars($_POST['email'], ENT_QUOTES)); ?>" /> <!-- 他の項目に入力ミスがあったとき、すでに入力済みの項目はそのまま表示する -->

					<?php if ($error['email'] === 'duplicate'): ?>
					<p class="error">* 指定されたメールアドレスはすでに登録されています</p>
					<?php endif; ?>
		<dt>写真・画像</dt>
		<dd>
        	<input type="file" name="image" size="35" value="test"  />
					<?php if ($error['image'] === 'type'): ?>
					<p class="error">* 写真などは「.gif」「.jpg」「.png」の画像を指定してください</p>
					<?php endif; ?>
					<?php if (!empty($error)): ?>
					<p class="error">* 恐れ入りますが画像を改めて指定してください</p>
					<?php endif; ?>

        </dd>
	</dl>
	<div><input type="submit" value="入力内容を確認する" /></div>
	<br>
	<div>>>すでに登録されている方は<a href="login.php">ログイン画面</a>へ</div>
</form>
</div>
</body>
</html>
