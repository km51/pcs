<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'off');

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
	<title>List | PCS</title>
	<link rel="stylesheet" href="./bootstrap-4.1.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="./style.css" />
  <link rel="stylesheet" href="./css/fixed.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/css/theme.ice.min.css">


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
    <h1><br><br>実績確認</h1>
  </div>
    <h3>表示条件：</h3>
    <form action="" method="post">
      <table>
        <tr>
          <td>社員コード：<input type="text" name="member_code"></td>
          <td>設備コード：<input type="text" name="machine_code"></td>
        </tr>
        <tr>
          <td>品番コード：<input type="text" name="parts_code"></td>
          <td>停止理由　：<input type="text" name="breakdown_reason"></td>
        </tr>
        </table>
        <input type="submit" value="検索">
        <br>
      </form>
      <br>
      <?php
      $sql = 'SELECT report_id, member_code, machine_code, parts_code, production_num, start_time, finish_time, lunch_time, breakdown_time, breakdown_reason, comment FROM reports 
      -- WHERE member_code=?, machine_code=?, parts_code=?, breakdown_reason=?'
// $sql->execute(array(
  // $_POST['member_code'],
  // $_POST['machine_code'],
  // $_POST['parts_code'],
  // $_POST['breakdown_reason']
// ))
;
$stmt = $db->query( $sql );
?>

    
  <table class="tablesorter" id="myTable" border="1">
    <thead><tr><th>No.</th><th>名前</th><th>設備名</th><th>品番</th><th>生産数</th><th>開始時刻</th><th>終了時刻</th><th>休憩時間</th><th>停止時間</th><th>停止理由</th><th>コメント</th><th>削除</th></tr></thead>
    <tbody>
    <?php
    while( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ){
    echo "<tr>";
    echo "<td>{$result['report_id']}</td>";
    echo "<td>{$result['member_code']}</td>";
    echo "<td>{$result['machine_code']}</td>";
    echo "<td>{$result['parts_code']}</td>";
    echo "<td>{$result['production_num']}</td>";
    echo "<td>{$result['start_time']}</td>";
    echo "<td>{$result['finish_time']}</td>";
    echo "<td>{$result['lunch_time']}</td>";
    echo "<td>{$result['breakdown_time']}</td>";
    echo "<td>{$result['breakdown_reason']}</td>";
    echo "<td>{$result['comment']}</td>";
    echo '<td><a href="deleteReport.php?report_id=?">削除</td>';
    echo "</tr>";
    } ?>
    </tbody>
  </table>

<br>
<form action="./csv.php" method="post">
      <!-- 社員コード：<input type ="text" name="member_code" /> -->
      <input type="submit" name="dlbtn" value="CSV出力" />
    </form>
<!-- <ul class="paging">
<?php if($page > 1): ?>
<li><a href="index.php?page=<?php print($page - 1); ?>">前のページへ</a></li>
<?php else: ?>
<li>前のページへ</li>
<?php endif; ?>
<?php if($page < $maxPage): ?>
<li><a href="index.php?page=<?php print($page + 1); ?>">次のページへ</a></li>
<?php else: ?>
<li>次のページへ</li>
<?php endif; ?>
</ul> -->
  </div>
</div>
<br>
<a class="memo" href="report.php">実績登録ページへ</a>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/js/jquery.tablesorter.min.js">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.1/js/extras/jquery.metadata.min.js">
<script type="text/javascript" src="./js/tablesorter.js"></script>
</body>
</html>
