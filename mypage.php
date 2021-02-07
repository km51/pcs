<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'off');

// if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()){
//   $_SESSION['time'] = time();

//   $members = $db->prepare('SELECT * FROM members WHERE id=?');
//   $members->execute(array($_SESSION['id']));
//   $member = $members->fetch();
// }else{
//   header('Location: login.php');
//   exit();
// }

// if (!empty($_POST)){
//   if ($_POST['message'] !== ''){
//     $message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_message_id=?, created=now()');
//     $message->execute(array(
//       $member['id'],
//       $_POST['message'],
//       NULL
//     ));
// // 更新ボタン押下されても、メッセージが追加されないようにするため、メッセージの登録後に、自分自身をもう一度呼び出す
//     header('Location: index.php');
//     exit();
//   }
// }

// $page = $_REQUEST['page'];
// if($page == ''){
//   $page = 1;
// }
// $page = max($page, 1);

// $counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
// $cnt = $counts->fetch();
// $maxPage = ceil($cnt['cnt'] / 5);
// $page = min($page, $maxPage);
// if($page == 0){
//   $page = 1;
// }
// $page = max($page, 1);


// $start = ($page - 1) *5;

// $posts = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');
// $posts->bindParam(1, $start, PDO::PARAM_INT);
// $posts->execute();

// if (isset($_REQUEST['res'])){
//   // 返信の処理
//   // 指定されたIDが存在するかどうかの確認→DBに問い合わせ
//   $response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
//   $response->execute(array($_REQUEST['res']));

//   $table = $response->fetch();
//   $message = '@' . $table['name'] . ' ' . $table['message'];
// } 
$members = $db->prepare('SELECT * FROM members WHERE member_id=?');
$members->execute(array($_SESSION['member_id']));
$member = $members->fetch();



?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>MyPage | PCS</title>
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
  <div id="head">
    <h1><br><br>マイページ</h1>
  </div>
  <div id="content">
    <form action="" method="post">
      <dl>
        <dt>
				<img src="member_picture/<?php print(htmlspecialchars($member['member_picture'], ENT_QUOTES)); ?>" width="128" height="170" alt="">
				</dt>
        <dt><?php print($member['member_name']); ?>さん</dt>
        <dt>mail : <a href=""><?php print($member['member_address']); ?></a></dt>
      

      </dl>
      <div>
        <p>
          <a href="update.php">編集する</a>
        </p>
      </div>
    </form>
    <a class="memo" href="index.php">TOPページに戻る</a>
  </div>
</div>
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>

</body>
</html>
