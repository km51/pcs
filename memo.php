 <?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'off');

if (isset($_SESSION['member_id']) && $_SESSION['time'] + 3600 > time()){
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE member_id=?');
  $members->execute(array($_SESSION['member_id']));
  $member = $members->fetch();
}else{
  header('Location: login.php');
  exit();
}

if (!empty($_POST)){
  $fileName = $_FILES['photo']['name'];
  if(!empty($fileName)){
    // $ext = substr($fileName, -3);

    // if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png'){
    //   $error['photo'] = 'type';
    //   }	
    
    // if(empty($error)){
      $image = date('YmdHis') . $fileName;
      move_uploaded_file($_FILES['photo']['tmp_name'], './memo_photo/' . $image);
      $_SESSION['join'] = $_POST;
      $_SESSION['join']['photo'] = $image;
    // }
  }else{
    $error['phpto'] = '';
  }	
    
  if ($_POST['memo_content'] !== ''){
    $message = $db->prepare('INSERT INTO memos SET member_id=?, memo_content=?, memo_photo=?, reply_message_id=?, created=now()');
    $message->execute(array(
      $member['member_id'],
      $_POST['memo_content'],
      $_SESSION['join']['photo'],
      NULL
    ));
    // 更新ボタン押下されても、メッセージが追加されないようにするため、メッセージの登録後に、自分自身をもう一度呼び出す
    header('Location: memo.php');
    exit();
  }
  
}

$page = $_REQUEST['page'];
if($page == ''){
  $page = 1;
}
$page = max($page, 1);

$counts = $db->query('SELECT COUNT(*) AS cnt FROM memos');
$cnt = $counts->fetch();
$maxPage = ceil($cnt['cnt'] / 5);
$page = min($page, $maxPage);
if($page == 0){
  $page = 1;
}
$page = max($page, 1);


$start = ($page - 1) *5;

$memos = $db->prepare('SELECT m1.member_name, m1.member_picture, m2.* FROM members m1, memos m2 WHERE m1.member_id=m2.member_id ORDER BY m2.created DESC LIMIT ?,5');
$memos->bindParam(1, $start, PDO::PARAM_INT);
$memos->execute();

if (isset($_REQUEST['res'])){
  // 返信の処理
  // 指定されたIDが存在するかどうかの確認→DBに問い合わせ
  $response = $db->prepare('SELECT m1.member_name, m1.member_picture, m2.* FROM members m1, memos m2 WHERE m1.member_id=m2.member_id AND m2.memo_id=?');
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();

  $reply = '@' . $table['member_name'] . 'さん【 ' . mb_substr($table['memo_content'], 0 ,15) . '...】：';
} else{
  $_REQUEST['res']='';
  $reply = '';
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
  <div id="head">
    <h1><br><br>メモ</h1>
  </div>
  <div id="content">
    <form action="" method="post" enctype="multipart/form-data">
      <dl>
        <dt>なにかありましたか？</dt>
        <dd>
          <textarea name="memo_content" cols="50" rows="5"><?php 
            print(htmlspecialchars($reply, ENT_QUOTES)); ?></textarea>
          <input type="hidden" name="reply_post_id" value="<?php 
          print(htmlspecialchars($_REQUEST['res'], ENT_QUOTES));?>" />
        </dd>

      </dl>
      <div>
           <input type="file" name="photo" enctype="multipart/form-data">
          <?php if ($error['photo'] === 'type'): ?>
					<p class="error">* 写真などは「.gif」「.jpg」「.png」の画像を指定してください</p>
					<?php endif; ?>
          <br> 
          <input type="submit" value="共有する" />
      </div>
    </form>
<?php foreach ($memos as $memo): ?>
    <div class="msg">
      <img class="member_picture" src="member_picture/<?php print(htmlspecialchars($memo['member_picture'], ENT_QUOTES)); ?>" width="48" height="48" />
      <span class="member_name"><?php print(htmlspecialchars($memo['member_name'], ENT_QUOTES)); ?>さん </span><p><?php print(htmlspecialchars($memo['memo_content'], ENT_QUOTES)); ?>
      
      <?php if($memo['memo_photo'] !== NULL):?>
        <img class="memo_photo" src="memo_photo/<?php print(htmlspecialchars($memo['memo_photo'], ENT_QUOTES)); ?>" width="100" height="100" />
      
      <?php endif; ?>

      [<a href="memo.php?res=<?php print(htmlspecialchars($memo['memo_id'], ENT_QUOTES)); ?>">Re</a>]</p>
      <p class="day"><a href="view.php?id="><?php print(htmlspecialchars($memo['created'], ENT_QUOTES)); ?></a>


      <?php if ($memo['reply_message_id'] > 0): ?>
        <a href="view.php?id=<?php print(htmlspecialchars($memo['reply_message_id'], ENT_QUOTES)); ?>">
      返信元のメッセージ</a>
      <?php endif; ?>

      <?php if ($_SESSION['member_id'] == $memo['member_id']): ?>
      [<a href="delete.php?memo_id=<?php print(htmlspecialchars($memo['memo_id'])); ?>"
      style="color: #F33;">削除</a>]
      <?php endif; ?>

      </p>
    </div>
<?php endforeach; ?>

<ul class="paging">
<?php if($page > 1): ?>
<li><a href="memo.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
<?php else: ?>
<li>前のページへ</li>
<?php endif; ?>
<?php if($page < $maxPage): ?>
<li><a href="memo.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
<?php else: ?>
<li>次のページへ</li>
<?php endif; ?>
</ul>
  </div>
</div>
</body>
</html>
