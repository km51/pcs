<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'o');

if (isset($_SESSION['member_id']) && $_SESSION['time'] + 3600 > time()){
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE member_id=?');
  $members->execute(array($_SESSION['member_id']));
  $member = $members->fetch();
}else{
  header('Location: index.php');
  exit();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>HOME | PCS</title>
	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
	<link rel="stylesheet" href="./style.css">
	<link rel="stylesheet" href="./css/fixed.css">
</head>

<body data-spy="scroll" data-target="#navbarResponsive">

<!-- Start Home Section -->
<div id="home">


<!-- Navigation -->
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="#">Production Control System</a>

	<div>こんにちは、<?php print($member['member_name']); ?>さん<br>＜社員コード：<?php print($member['member_code']); ?>＞</div>
	
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

</div>
<div class="landing">
  <div class="home-wrap">
		<div class="home-inner">

		</div>

	</div>

</div>

<div class="caption ml-5">
	<h1>生産実績管理システム</h1>
	<h1>～ Production Control System ～</h1>
	<h3>以下の管理・共有ができます。</h3>
	<div class="list">
		<p>・生産実績登録　　　<a class="btn btn-outline-light btn-lg" href="report.php">実績登録</a></p>
		<p>・過去実績の確認　　<a class="btn btn-outline-light btn-lg" href="list.php">実績確認</a></p>
		<p>・作業時のメモ　　　<a class="btn btn-outline-light btn-lg" href="memo.php">メモ登録</a></p>
	</div>
	</>

	<h4><br>生産性向上に向けて、情報を共有しましょう！</h4>
</div>


</body>
</html>
