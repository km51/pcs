<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'off');

$members = $db->prepare('SELECT * FROM members WHERE member_id=?');
$members->execute(array($_SESSION['member_id']));
$member = $members->fetch();

// $machines = $db->prepare('SELECT * FROM machines WHERE machine_code=?');
// $machines->execute(array($_SESSION['join']['machine_code']));
// $machine = $machines->fetch();

// $parts = $db->prepare('SELECT * FROM parts WHERE parts_code=?');
// $parts->execute(array($_SESSION['join']['parts_code']));
// $part = $parts->fetch();

if(!empty($_POST)){
  if ($_POST['machine_code'] === ''){
    $error['machine_code'] = 'blank';
  }
  if ($_POST['parts_code'] === ''){
    $error['parts_code'] = 'blank';
  }

  if ($_POST['production_num'] === ''){
    $error['production_num'] = 'blank';
  }

  if(empty($error)){
    $_SESSION['join'] = $_POST;
    header('Location: checkReport.php');
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
	<title>Memo | PCS</title>
	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./style.css" />
  <link rel="stylesheet" href="./css/fixed.css">
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
    <h1><br><br>実績登録</h1>
  <div id="content">
    <h4>実績入力（全項目必須）</h4>
     <form action="" method="post">
        <table>
          <tr>
          <td>社員コード：<?php print(htmlspecialchars($member['member_code'], ENT_QUOTES)); ?>
          </td>
          </tr>
          <tr>
          <td>設備コード：<input type="text" name="machine_code" value="<?php print(htmlspecialchars($_POST['machine_code'], ENT_QUOTES)); ?>">
		  <select><option>111</option></select>
          <?php if ($error['machine_code'] === 'blank'): ?>
					<p class="error">* 設備コードを正しく入力してください</p>
					<?php endif; ?>
          </td>
          </tr>
          <tr>
          <td>品番コード：<input type="text" name="parts_code" value="<?php print(htmlspecialchars($_POST['parts_code'], ENT_QUOTES)); ?>">
          <?php if ($error['parts_code'] === 'blank'): ?>
					<p class="error">* 品番コードを正しく入力してください</p>
					<?php endif; ?>
          </td>
          </tr>
          <tr>
          <td>生産数　　：<input type="text" name="production_num" value="<?php print(htmlspecialchars($_POST['production_num'], ENT_QUOTES)); ?>">
          <?php if ($error['production_num'] === 'blank'): ?>
					<p class="error">* 生産数を入力してください</p>
					<?php endif; ?>
          </td>
          </tr>
          <tr>
          <td>生産日　　：<input type="text" name="production_date" value="<?php print(htmlspecialchars($_POST['production_date'], ENT_QUOTES)); ?>"> ※「20210126」のように入力</td>
          </tr>
          <tr>
          <td>開始時刻　：<input type="text" name="start_hour" size="3" value="<?php print(htmlspecialchars($_POST['start_hour'], ENT_QUOTES)); ?>">：<input type="text" name="start_min" size="3" value="<?php print(htmlspecialchars($_POST['start_min'], ENT_QUOTES)); ?>"></td>
          </tr>
          <tr>
          <td>終了時刻　：<input type="text" name="finish_hour" size="3" value="<?php print(htmlspecialchars($_POST['finish_hour'], ENT_QUOTES)); ?>">：<input type="text" name="finish_min" size="3" value="<?php print(htmlspecialchars($_POST['finish_min'], ENT_QUOTES)); ?>"></td>
          </tr>
          <tr><td>休憩時間　：<input type="text" name="lunch_hour" size="3" value="<?php print(htmlspecialchars($_POST['lunch_hour'], ENT_QUOTES)); ?>">：<input type="text" name="lunch_min" size="3" value="<?php print(htmlspecialchars($_POST['lunch_min'], ENT_QUOTES)); ?>"></td>
          </tr>
          <tr>
          <td>停止時間　：<input type="text" name="breakdown_hour" size="3" value="<?php print(htmlspecialchars($_POST['breakdown_hour'], ENT_QUOTES)); ?>">：<input type="text" name="breakdown_min" size="3" value="<?php print(htmlspecialchars($_POST['breakdown_min'], ENT_QUOTES)); ?>"></td>
          <!-- <td><button type="button">行削除</button></td> -->
          </tr>
          <tr>
          <td>停止理由　：<input type="text" name="breakdown_reason" size="3" value="<?php print(htmlspecialchars($_POST['breakdown_reason'], ENT_QUOTES)); ?>">
          </td>
          </tr>
          <tr>
          <td>コメント　：
        <textarea name="comment" cols="40" rows="3" value=""><?php print(htmlspecialchars($_POST['comment'], ENT_QUOTES)); ?></textarea>
        </td>
        </tr>      
        </table>
        <p><input type="submit" value="確認画面へ" /></p>
    </div>
      </form>
        <a class="memo" href="list.php">実績確認ページへ</a>

  
</div>
</body>
</html>
