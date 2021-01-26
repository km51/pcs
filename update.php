<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'off');

$members = $db->prepare('SELECT * FROM members WHERE member_id=?');
$members->execute(array($_SESSION['member_id']));
$member = $members->fetch();

if(!empty($_POST)){
	$error=['name'=>'','image'=>'', 'email'=>''];
	// check if get some information or not when submit button was pushed 
	if ($_POST['name'] === ''){
		$error['name'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if(!empty($fileName)){
		$ext = substr($fileName, -3);

		if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
			$error['image'] = 'type';
			}	
	}	
	if(empty($error)){
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE member_address=? AND member_id !=?');
		$member->execute(array(
			$_POST['email'],
			$_SESSION['member_id']
		));
		$record = $member->fetch();
		if ($record['cnt'] > 0){
			$error['email'] = 'duplicate';
		}
	}
	if(empty($error)){
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], 'member_picture/' . $image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;

		header('Location: checkUpdate.php');
		exit();
	}


}



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
    <h1><br><br>マイページ編集画面</h1>
  </div>
  <div id="content">
    <form action="" method="post">
      <dl>
				<dt>写真：
				<img src="member_picture/<?php print(htmlspecialchars($member['member_picture'], ENT_QUOTES)); ?>" width="128" height="170" alt="">
				</dt>
				<input type="file" name="image">
				<?php if ($error['image'] === 'type'): ?>
					<p class="error">* 写真などは「.gif」「.jpg」「.png」の画像を指定してください</p>
					<?php endif; ?>
				<br>

				<dt>名前（必須）：<input type="text" name="name" cols="15" rows="1" value="<?php 
        print(htmlspecialchars($member['member_name'], ENT_QUOTES)); 
        ?>"> さん</dt>
					<?php if ($error['name'] === 'blank'): ?>
					<p class="error">* 名前を入力してください</p>
					<?php endif; ?>
				<br>
        <dt>メールアドレス：<input type="text" name="email" cols="30" rows="1" value="<?php 
        print(htmlspecialchars($member['member_address'], ENT_QUOTES)); 
        ?>"></dt>
					<?php if ($error['email'] === 'duplicate'): ?>
					<p class="error">* 指定されたメールアドレスはすでに登録されています</p>
					<?php endif; ?>


      
      </dl>
      <div>
        <p>
          <input type="submit" value="更新" />
        </p>
      </div>
    </form>
    <a class="memo" href="index.php">TOPページに戻る</a>

  </div>
</div>
</body>
</html>
