s<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'off');

$members = $db->prepare('SELECT * FROM members WHERE member_id=?');
$members->execute(array($_SESSION['member_id']));
$member = $members->fetch();

$reports = $db->prepare('SELECT * FROM reports WHERE report_id=?');
$reports->execute(array($_REQUEST['report_id']));
$report = $reports->fetch();

try {
  $sql = "SELECT * FROM breakdown";
  $stmt = $db->query($sql);
  $breakdown = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $exception) {
  echo $exception->getMessage();
  exit();
}
// $parts = $db->prepare('SELECT * FROM parts WHERE parts_code=?');
// $parts->execute(array($_SESSION['join']['parts_code']));
// $part = $parts->fetch();

// $breakdown = $db->prepare('SELECT * FROM breakdown WHERE parts_code=?');
// $parts->execute(array($_SESSION['join']['parts_code']));
// $part = $parts->fetch();

if(!empty($_POST)){
  if ($_POST['machine_code'] === '' || $_POST['machine_judge'] == '1'){
    $error['machine_code'] = 'blank';
  }


  if ($_POST['parts_code'] === ''  || $_POST['parts_judge'] == '1'){
    $error['parts_code'] = 'blank';
  }

  if ($_POST['production_num'] === ''){
    $error['production_num'] = 'blank';
  }
  if ($_POST['production_date'] === ''){
    $error['production_date'] = 'blank';
  }
  if ($_POST['start_time'] === ''){
    $error['start_time'] = 'blank';
  }
  if ($_POST['finish_time'] === ''){
    $error['finish_time'] = 'blank';
  }

  if(empty($error)){
    $_SESSION['join'] = $_POST;
    $_SESSION['report_id'] = $_REQUEST['report_id'];
    header('Location: checkEditReport.php');
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

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
    <h1><br><br>実績編集</h1>
  <div id="content">
    <h4>実績入力（<span class="must">※</span>入力必須項目）</h4>
     <form action="" method="post">
        <table>
          <tr>
          <td>レポートNo.　　：<?php print(htmlspecialchars($_REQUEST['report_id'], ENT_QUOTES)); ?>
          </td>
          </tr>
          <tr>
          <td>社員コード　　　：<?php print(htmlspecialchars($member['member_code'], ENT_QUOTES)); ?>　　　名前　：<?php print(htmlspecialchars($member['member_name'],ENT_QUOTES)); ?>さん
          </td>
          </tr>
          <tr>
          <td>設備コード<span class="must">※</span>　　：<input type="text" id="search-text-machine" placeholder="設備コード入力" name="machine_code" 
          value="<?php if ($error['machine_code'] === 'blank'){echo '';}else{print(htmlspecialchars($report['machine_code'], ENT_QUOTES));} ?>">
          <input type="button" value="設備リスト表示切替" onclick="clickBtn1()" />
                     
          <div class="search-result-machine">
            <div class="search-result-machine__hit-num"></div>
            <div id="search-result-machine__list"></div>
            <input type="hidden" id="search-result-machine__judge" name="machine_judge">
          </div>
          <ul class="target-area-machine">
            <?php 
            $sql = 'SELECT machine_code, machine_name FROM machines';
            $stmt = $db->query( $sql );
            ?>   
              <table class="tablesorter target-area-machine" id="p1" rules="rows">
                <thead><tr><th>設備コード/設備名</th></tr></thead>
                <tbody>
                  <?php
                while( $result = $stmt->fetch( PDO::FETCH_ASSOC ) ){
                echo "<tr><td>{$result['machine_code']} / {$result['machine_name']}</td></tr>";
                } ?>
                </tbody>
              </table>
            </ul>
          <script>
            //初期表示は非表示
                document.getElementById("p1").style.display ="none";

                function clickBtn1(){
                  const p1 = document.getElementById("p1");

                  if(p1.style.display=="block"){
                    // noneで非表示
                    p1.style.display ="none";
                  }else{
                    // blockで表示
                    p1.style.display ="block";
                  }
                }
              </script>
              </td><td>
                <?php if ($error['machine_code'] === 'blank'): ?>
					<p class="error">* 設備コードを正しく入力してください</p>
					<?php endif; ?>
        </td>
      </tr>
          <tr>
            <td>品番コード<span class="must">※</span>　　：<input type="text" id="search-text-parts" placeholder="品番コード入力" name="parts_code" value="<?php if ($error['parts_code'] === 'blank'){echo '';}else{print(htmlspecialchars($report['parts_code'], ENT_QUOTES));} ?>">
          <input type="button" value="品番リスト表示切替" onclick="clickBtn2()" />
          
          <div class="search-result-parts">
            <div class="search-result-parts__hit-num"></div>
            <div id="search-result-parts__list"></div>
            <input type="hidden" id="search-result-parts__judge" name="parts_judge">
          </div>
          <ul class="target-area-parts">
            <?php $sql2 = 'SELECT parts_code, parts_name FROM parts';
            $stmt2 = $db->query( $sql2 );
            ?>   
              <table class="tablesorter target-area-parts" id="p2" rules="rows">
                <thead><tr><th>品番コード/品番</th></tr></thead>
                <tbody>
                <?php
                while( $result2 = $stmt2->fetch( PDO::FETCH_ASSOC ) ){
                echo "<tr><td>{$result2['parts_code']} / {$result2['parts_name']}</td></tr>";
                } ?>
                </tbody>
              </table>
          </ul>
          <script>
                //初期表示は非表示
                document.getElementById("p2").style.display ="none";

                function clickBtn2(){
                  const p2 = document.getElementById("p2");

                  if(p2.style.display=="block"){
                    // noneで非表示
                    p2.style.display ="none";
                  }else{
                    // blockで表示
                    p2.style.display ="block";
                  }
                }
              </script>
              </td><td>
          <?php if ($error['parts_code'] === 'blank'): ?>
					<p class="error">* 品番コードを正しく入力してください</p>
					<?php endif; ?>
          </td>
          </tr>
          <tr>
          <td>生産数<span class="must">※</span>　　　　：<input type="text" name="production_num" value="<?php print(htmlspecialchars($report['production_num'], ENT_QUOTES)); ?>"></td><td>
          <?php if ($error['production_num'] === 'blank'): ?>
					<p class="error">* 生産数を入力してください</p>
					<?php endif; ?>
          </td>
          </tr>
          <tr>
          <td>生産日<span class="must">※</span>　　　　：<input type="date" name="production_date" value="<?php print(htmlspecialchars($report['production_date'], ENT_QUOTES)); ?>"></td><td>          
          <?php if ($error['production_date'] === 'blank'): ?>
					<p class="error">* 生産日を入力してください</p>
					<?php endif; ?> </td>
          </tr>
          <tr>
          <td>開始時刻<span class="must">※</span>　　　：<input type="time" name = "start_time" value="<?php print(htmlspecialchars($report['start_time'], ENT_QUOTES)); ?>"></td><td>
          <?php if ($error['start_time'] === 'blank'): ?>
					<p class="error">* 開始時刻を入力してください</p>
					<?php endif; ?>
          </td>
          </tr>
          <tr>
          <td>終了時刻<span class="must">※</span>　　　：<input type="time" name = "finish_time" value="<?php print(htmlspecialchars($report['finish_time'], ENT_QUOTES)); ?>"></td><td>
          <?php if ($error['finish_time'] === 'blank'): ?>
					<p class="error">* 終了時刻を入力してください</p>
					<?php endif; ?>

          </td>
          </tr>
          <tr><td>休憩時間　　　　：
             <select name='lunch_time' value="<?php print(htmlspecialchars($report['lunch_time'], ENT_QUOTES)); ?>">
             <?php 
             $timeop = "";
              for ($i = 0; $i <=16; $i++) {//この場合は32回目の17:00も必要なので$i<32ではなく$i<=32
                $time = date("H:i", strtotime("+". $i * 15 ." minute",-3600*9));
                $timeop .= "<option value =\"{$time}\">".$time."</option>\n";
              }
            echo $timeop;
            ?>
            
          </select></td>
          </tr>
          <tr>
          <td>停止時間１　　　：<?php echo "<select name='breakdown_time1'>";
          $timeop = "";
          for ($i = 0; $i < 96; $i++) {//この場合は32回目の17:00も必要なので$i<32ではなく$i<=32
          $time = date("H:i", strtotime("+". $i * 15 ." minute",-3600*9));
          $timeop .= "<option value =\"{$time}\">".$time."</option>\n";
         }
          echo $timeop;
          echo "</select>"; ?></td>
          </tr>
          <tr>
          <td>停止理由１　　　：
          <select name="breakdown_reason1">
                    <?php foreach ($breakdown as $value) { ?>
                        <option value="<?php echo htmlspecialchars($value["breakdown_code"]." / ".$value["breakdown_reason"], ENT_QUOTES); ?>">
                            <?php echo htmlspecialchars($value["breakdown_code"]." / ".$value["breakdown_reason"], ENT_QUOTES); ?>
                        </option>
                    <?php } ?>
          </select>
          </td>
          </tr>
          <tr>
          <td class="comment">停止コメント１　：</td>
          <td class="comment"><textarea name="comment1" cols="40" rows="2" value="" placeholder="停止理由を記載"><?php print(htmlspecialchars($_POST['comment1'], ENT_QUOTES)); ?></textarea></td>
          <tr>
          <td>停止時間２　　　：<?php echo "<select name='breakdown_time2'>";
          $timeop = "";
          for ($i = 0; $i < 96; $i++) {//この場合は32回目の17:00も必要なので$i<32ではなく$i<=32
            $time = date("H:i", strtotime("+". $i * 15 ." minute",-3600*9));
            $timeop .= "<option value =\"{$time}\">".$time."</option>\n";
          }
          echo $timeop;
          echo "</select>"; ?></td>
          <!-- <td><button type="button">行削除</button></td> -->
          </tr>
          <tr>
          <td>停止理由２　　　：
          <!-- <input type="text" name="breakdown_reason" size="3" value="<?php print(htmlspecialchars($_POST['breakdown_reason2'], ENT_QUOTES)); ?>"> -->
          <select name="breakdown_reason2">
            <?php foreach ($breakdown as $value) { ?>
                <option value="<?php echo htmlspecialchars($value["breakdown_code"]." / ".$value["breakdown_reason"], ENT_QUOTES); ?>">
                    <?php echo htmlspecialchars($value["breakdown_code"]." / ".$value["breakdown_reason"], ENT_QUOTES); ?>
                </option>
            <?php } ?>
          </select>
          </td>
          </tr>
          <tr>
          <td class="comment" >停止コメント２　：</td>
          <td class="comment" >
        <textarea name="comment2" cols="40" rows="2" value="" placeholder="停止理由を記載"><?php print(htmlspecialchars($_POST['comment2'], ENT_QUOTES)); ?></textarea></td>
          <tr>
          <td>停止時間３　　　：<?php echo "<select name='breakdown_time3'>";
          $timeop = "";
          for ($i = 0; $i < 96; $i++) {//この場合は32回目の17:00も必要なので$i<32ではなく$i<=32
            $time = date("H:i", strtotime("+". $i * 15 ." minute",-3600*9));
            $timeop .= "<option value =\"{$time}\">".$time."</option>\n";
          }
          echo $timeop;
          echo "</select>"; ?></td>
          <!-- <td><button type="button">行削除</button></td> -->
          </tr>
          <tr>
          <td>停止理由３　　　：
          <!-- <input type="text" name="breakdown_reason" size="3" value="<?php print(htmlspecialchars($_POST['breakdown_reason'], ENT_QUOTES)); ?>"> -->
          <select name="breakdown_reason3">
            <?php foreach ($breakdown as $value) { ?>
                <option value="<?php echo htmlspecialchars($value["breakdown_code"]." / ".$value["breakdown_reason"], ENT_QUOTES); ?>">
                    <?php echo htmlspecialchars($value["breakdown_code"]." / ".$value["breakdown_reason"], ENT_QUOTES); ?>
                </option>
            <?php } ?>
          </select>
          </td>
          </tr>
          <tr>
          <td class="comment" >停止コメント３　：</td>
          <td class="comment" >
        <textarea name="comment3" cols="40" rows="2" value="" placeholder="停止理由を記載"><?php print(htmlspecialchars($_POST['comment3'], ENT_QUOTES)); ?></textarea>
        </td>
        </tr>      
        </table>
        <p><input type="submit" id = "submit_button"  value="確認画面へ" /></p>
    </div>
      </form>
        <!-- <a class="memo" href="list.php">実績確認ページへ</a> -->

  
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
<script type="text/javascript" src="js/instant.js"></script>
	<script src="js/jquery-3.3.1.min.js"></script>
<script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
</body>
</html>
