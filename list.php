<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'off');

$members = $db->prepare('SELECT * FROM members WHERE member_id=?');
$members->execute(array($_SESSION['member_id']));
$member = $members->fetch();

$operators = $db->prepare('SELECT * FROM members WHERE member_code=?');
$operators->execute(array($_SESSION['member_code']));
$operator = $operators->fetch();

$machines = $db->prepare('SELECT * FROM machines WHERE machine_code=?');
$machines->execute(array($_SESSION['join']['machine_code']));
$machine = $machines->fetch();

$parts = $db->prepare('SELECT * FROM parts WHERE parts_code=?');
$parts->execute(array($_SESSION['join']['parts_code']));
$part = $parts->fetch();



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


  <script type="text/javascript">
    function disp(){

      // 「OK」時の処理開始 ＋ 確認ダイアログの表示
      if(window.confirm('本当にいいんですね？')){

        location.href = "./reportDelete.php?report_id="+; // example_confirm.html へジャンプ

      }
      // 「OK」時の処理終了

      // 「キャンセル」時の処理開始
      else{

        window.alert('キャンセルされました'); // 警告ダイアログを表示

      }
      // 「キャンセル」時の処理終了

    }

    //
  </script>

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
    <!-- <h3>表示条件：</h3>
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
      </form> -->
      <br>
      <?php
      $sql = 'SELECT report_id, member_id, member_code, member_name, machine_code, machine_name, parts_code, parts_name, production_num, production_date, start_time, finish_time, lunch_time, breakdown_time1, breakdown_reason1, comment1, breakdown_time2, breakdown_reason2, comment2, breakdown_time3, breakdown_reason3, comment3 FROM reports';
      $stmt = $db->query( $sql );
  
      ?>


  <table class="tablesorter" id="myTable" border="1">
    <thead><tr><th>No.</th><th>編集</th><th>社員名</th><th>設備名</th><th>品番</th><th>生産数</th><th>生産日</th><th>開始時刻</th><th>終了時刻</th><th>休憩時間</th><th>停止時間1</th><th>停止理由1</th><th>コメント1</th><th>停止時間2</th><th>停止理由2</th><th>コメント2</th><th>停止時間3</th><th>停止理由3</th><th>コメント3</th><th>削除</th></tr></thead>
    <tbody>
      <?php
        while( $result = $stmt->fetch( PDO::FETCH_ASSOC )){
          $result['start_time']= new DateTime($result['start_time']);
          $result['finish_time']= new DateTime($result['finish_time']);
          $result['lunch_time']= new DateTime($result['lunch_time']);
          $result['breakdown_time1']= new DateTime($result['breakdown_time1']);
          $result['breakdown_time2']= new DateTime($result['breakdown_time2']);
          $result['breakdown_time3']= new DateTime($result['breakdown_time3']);
        echo "<tr>";
        echo "<td>{$result['report_id']}</td>";
        if ($_SESSION['member_id'] == $result['member_id']){
          echo '<td>';
          echo "<a href='reportEdit.php?report_id=".$result['report_id']."'>編集";
          echo '</td>';
        }else{
          echo '<td></td>';
        }
        // echo "<td>{$result['member_id']}</td>";
        // echo "<td>{$result['member_code']}</td>";
        echo "<td>{$result['member_name']}</td>";
        // echo "<td>{$result['machine_code']}</td>";
        echo "<td>{$result['machine_name']}</td>";
        // echo "<td>{$result['parts_code']}</td>";
        echo "<td>{$result['parts_name']}</td>";
        echo "<td>{$result['production_num']}</td>";
        echo "<td>{$result['production_date']}</td>";
        echo "<td>{$result['start_time']->format('H:i')}</td>";
        echo "<td>{$result['finish_time']->format('H:i')}</td>";
        echo "<td>{$result['lunch_time']->format('H:i')}</td>";
        echo "<td>{$result['breakdown_time1']->format('H:i')}</td>";
        echo "<td>{$result['breakdown_reason1']}</td>";
        echo "<td>{$result['comment1']}</td>";
        echo "<td>{$result['breakdown_time2']->format('H:i')}</td>";
        echo "<td>{$result['breakdown_reason2']}</td>";
        echo "<td>{$result['comment2']}</td>";
        echo "<td>{$result['breakdown_time3']->format('H:i')}</td>";
        echo "<td>{$result['breakdown_reason3']}</td>";
        echo "<td>{$result['comment3']}</td>";
        if ($_SESSION['member_id'] == $result['member_id']){
          echo '<td>';
          // echo '<input type="button" value="削除" onClick="disp()">';
          echo "<a href='reportDelete.php?report_id=".$result['report_id']."'>削除";
          echo '</td>';
        }else{
          echo '<td></td>';
        }
        echo "</tr>";
        } 
      ?>
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
