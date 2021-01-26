<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'on');


if (isset($_SESSION['member_id'])){
  // idのsessionが記録されているか、自分のメッセージがあるかどうか
  // 削除する候補を読み込む
  $id = $_REQUEST['memo_id'];

  $memos = $db->prepare('SELECT * FROM memos WHERE memo_id=?');
  $memos->execute(array($id));
  $memo = $memos->fetch();

  if ($memo['member_id'] == $_SESSION['member_id']){
    $del = $db->prepare('DELETE FROM memos WHERE memo_id=?');
    $del->execute(array($id));
  }
}
header('Location: memo.php');
exit();
?>


