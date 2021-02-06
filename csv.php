<?php
ini_set('display_errors', 'off');
// $db = new PDO('mysql:dbname=pcs;host=127.0.0.1;charset=utf8', 'root', '_*n7PACtL7qKM#4');



$dsn = 'mysql:host=127.0.0.1;dbname=pcs';
$id = 'root';
$pw = '_*n7PACtL7qKM#4';
//画面からパラメータ取得
// $val = filter_input(INPUT_POST, "member_code");

if (isset($_POST["dlbtn"])) {
  try {
    //DB検索処理
    $pdo = new PDO($dsn, $id, $pw,
    array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    $sql = "SELECT * FROM reports 
    -- WHERE member_code = :member_code "
    ;
    $stmt = $pdo->prepare($sql);
    // $stmt->bindParam(':member_code', $val, PDO::PARAM_STR);
    $stmt->execute();
    
    // $stmt->fetch(PDO::FETCH_ASSOC));
    // $csvstr .= 'member_code' . ",";
    // $csvstr .= 'machine_code' . ",";
    // $csvstr .= 'parts_code' . ",";
    // $csvstr .= 'start_time' . ",";
    // $csvstr .= 'finish_time' . ",";
    // $csvstr .= 'lunch_time' . ",";
    // $csvstr .= 'breakdown_time' . ",";
    // $csvstr .= 'breakdown_reason' . ",";
    // $csvstr .= 'comment' . ",";
    // $csvstr .= 'created' . "\r\n";
    
    
    //CSV文字列生成
    $csvstr = "";
    $csvstr .= 'report_id' . ",";
    $csvstr .= 'member_code' . ",";
    $csvstr .= 'member_name' . ",";
    $csvstr .= 'machine_code' . ",";
    $csvstr .= 'machine_name'. ",";
    $csvstr .= 'parts_code' . ",";
    $csvstr .= 'parts_name' . ",";
    $csvstr .= 'production_num' . ",";
    $csvstr .= 'production_date' . ",";
    $csvstr .= 'start_time' . ",";
    $csvstr .= 'finish_time' . ",";
    $csvstr .= 'lunch_time' . ",";
    $csvstr .= 'breakdown_time1' . ",";
    $csvstr .= 'breakdown_reason1' . ",";
    $csvstr .= 'comment1' . ",";
    $csvstr .= 'breakdown_time2' . ",";
    $csvstr .= 'breakdown_reason2' . ",";
    $csvstr .= 'comment2' . ",";
    $csvstr .= 'breakdown_time3' . ",";
    $csvstr .= 'breakdown_reason3' . ",";
    $csvstr .= 'comment3' . ",";
    $csvstr .= 'created' . "\r\n";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $csvstr .= $row['report_id'] . ",";
      $csvstr .= $row['member_code'] . ",";
      $csvstr .= $row['member_name'] . ",";
      $csvstr .= $row['machine_code'] . ",";
      $csvstr .= $row['machine_name'] . ",";
      $csvstr .= $row['parts_code'] . ",";
      $csvstr .= $row['parts_name'] . ",";
      $csvstr .= $row['production_num'] . ",";
      $csvstr .= $row['production_date'] . ",";
      $csvstr .= $row['start_time'] . ",";
      $csvstr .= $row['finish_time'] . ",";
      $csvstr .= $row['lunch_time'] . ",";
      $csvstr .= $row['breakdown_time1'] . ",";
      $csvstr .= $row['breakdown_reason1'] . ",";
      $csvstr .= $row['comment1'] . ",";
      $csvstr .= $row['breakdown_time2'] . ",";
      $csvstr .= $row['breakdown_reason2'] . ",";
      $csvstr .= $row['comment2'] . ",";
      $csvstr .= $row['breakdown_time3'] . ",";
      $csvstr .= $row['breakdown_reason3'] . ",";
      $csvstr .= $row['comment3'] . ",";
      $csvstr .= $row['created'] . "\r\n";
    }
    
    //CSV出力
    $fileNm = "report.csv";
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename='.$fileNm);
    echo mb_convert_encoding($csvstr, 'SJIS', 'UTF-8'); //Shift-JISに変換したい場合のみ
    exit();
    
  }catch(ErrorException $ex){
    print('ErrorException:' . $ex->getMessage());
  }catch(PDOException $ex){
    print('PDOException:' . $ex->getMessage());
  }
}

?>