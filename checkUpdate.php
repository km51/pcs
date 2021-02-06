<?php
session_start();
require('dbconnect.php');

ini_set('display_errors', 'off');

$members = $db->prepare('SELECT * FROM members WHERE member_id=?');
$members->execute(array($_SESSION['member_id']));
$member = $members->fetch();

if(!empty($_POST)){
	// if there is something in box
	// DBへの登録について
	$statement = $db -> prepare('UPDATE members SET member_name=?, member_picture=?, member_address=?, modified=NOW() WHERE member_id = ?');
	// NOW()は登録された日時
	$statement->execute(array(
			$_SESSION['join']['name'],
			$_SESSION['join']['image'],
      $_SESSION['join']['email'],
      $_SESSION['member_id']
	));
	unset($_SESSION['join']);
	header('Location: mypage.php');
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
	<a class="navbar-brand" href="index.php">Production Control System</a>

	<div class="text-align">こんにちは、<?php print($member['member_name']); ?>さん<br>＜社員コード：<?php print($member['member_code']); ?>＞</div>
	
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarResponsive">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link" href="report.php">登録</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="list.php">確認</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="memo.php">メモ</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="mypage.php">マイページ</a>
      </li>
      <li class="nav-item">
				<a class="nav-link" href="login.php">ログアウト</a>
			</li>
		</ul>
	</div>
</nav>
<div id="wrap">
<div id="head">
<h1><br><br>登録内容更新</h1>
</div>

<div id="content">
<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
<form action="" method="post">
	<input type="hidden" name="action" value="submit" />
	<dl>
		<dt>写真</dt>
		<dd>
		<!-- <?php if ($_SESSION['join']['image'] === ''){
			// echo "<img src='./member_picture/".$member['member_picture']."'>";
		// }else{
			// echo "<img src='./member_picture/".$_SESSION['join']['image']."'>";
		} ?> -->
		<!-- <?php if($_SESSION['join']['image'] !== ''): ?> -->
			<img src='./member_picture/<?php print(htmlspecialchars($_SESSION['join']['image'], ENT_QUOTES)); ?>'>
		<!-- <?php endif; ?>	 -->
		</dd>
		<dt>名前</dt>
		<dd>
		<?php print(htmlspecialchars($_SESSION['join']['name'], ENT_QUOTES)); ?>
        </dd>

 		<dt>メールアドレス</dt>
		<dd>
		<?php print(htmlspecialchars($_SESSION['join']['email'], ENT_QUOTES)); ?>
    </dd>
	</dl>
	<div><a href="update.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="更新する" /></div>
</form>
</div>

</div>
</body>
</html>
