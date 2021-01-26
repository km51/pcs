<?php
session_start();
require('dbconnect.php');

ini_set('display_errors', 'on');

$members = $db->prepare('SELECT * FROM members WHERE member_id=?');
$members->execute(array($_SESSION['member_id']));
$member = $members->fetch();

$machines = $db->prepare('SELECT * FROM machines WHERE machine_code=?');
$machines->execute(array($_SESSION['join']['machine_code']));
$machine = $machines->fetch();

$parts = $db->prepare('SELECT * FROM parts WHERE parts_code=?');
$parts->execute(array($_SESSION['join']['parts_code']));
$part = $parts->fetch();
// if (!isset($_SESSION['join'])){
// 	header('Location: login.php');
// 	exit();

// }
if(!empty($_POST)){
	// if there is something in box
	// DBへの登録について
	$statement = $db -> prepare('INSERT INTO reports (member_code, machine_code, parts_code, production_num, production_date, start_time, finish_time, lunch_time, breakdown_time,  breakdown_reason, comment, created) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
	// NOW()は登録された日時
	$statement->execute(array(
			$member['member_code'],
			$_SESSION['join']['machine_id'],
			$_SESSION['join']['parts_id'],
      $_SESSION['join']['production_num'],
      $_SESSION['join']['production_date'],
			$_SESSION['join']['start_time'],
      $_SESSION['join']['finish_time'],
			$_SESSION['join']['lunch_time'],
			$_SESSION['join']['breakdown_time'],
      $_SESSION['join']['breakdown_reason'],
      $_SESSION['join']['comment']
	));
	unset($_SESSION['join']);
	header('Location: complete.php');
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
    <h1><br><br>実績登録</h1>
  </div>
  <div id="content">
    <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
    <h4>実績</h4>
    <form action="" method="post">
    	<input type="hidden" name="action" value="submit" />  
        <table>
          <tr>
          <td>社員コード：<?php print(htmlspecialchars($member['member_code'],ENT_QUOTES)); ?>　名前：<?php print(htmlspecialchars($member['member_name'],ENT_QUOTES)); ?></td>
          </tr>
          <tr>
          <td>設備コード：<?php print(htmlspecialchars($_SESSION['join']['machine_code'],ENT_QUOTES)); ?> 　設備名：<?php print(htmlspecialchars($machine['machine_name'],ENT_QUOTES)); ?></td>
          </tr>
          <tr>
          <td>品番コード：<?php print(htmlspecialchars($_SESSION['join']['parts_code'],ENT_QUOTES)); ?>　品番名：<?php print(htmlspecialchars($part['parts_name'],ENT_QUOTES)); ?>
          </td>
          </tr>
          <tr>
          <td>生産数　　：<?php print(htmlspecialchars($_SESSION['join']['production_num'],ENT_QUOTES)); ?>
          </td>
          </tr>
          <tr>
          <td>開始時刻　：<?php print(htmlspecialchars($_SESSION['join']['start_time'],ENT_QUOTES)); ?></td>
          </tr>
          <tr>
          <td>終了時刻　：<?php print(htmlspecialchars($_SESSION['join']['finish_time'],ENT_QUOTES)); ?></td>
          </tr>
          <tr><td>休憩時間　：<?php print(htmlspecialchars($_SESSION['join']['lunch_time'],ENT_QUOTES)); ?></td>
          </tr>
          <tr>
          <td>停止時間　：<?php print(htmlspecialchars($_SESSION['join']['breakdown_time'],ENT_QUOTES)); ?></td>
          <!-- <td><button type="button">行削除</button></td> -->
          </tr>
          <tr>
          <td>停止理由　：<?php print(htmlspecialchars($_SESSION['join']['breakdown_reason'],ENT_QUOTES)); ?></td>
          </tr>
          <tr>
          <td>コメント　：<?php print(htmlspecialchars($_SESSION['join']['comment'], ENT_QUOTES)); ?></td>
          </tr>
        </table>
  </div>
	<div><a href="report.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" /></div>
</div>
</form>

</body>
</html>
