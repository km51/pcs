<?php
session_start();
require('dbconnect.php');
ini_set('display_errors', 'on');


if (isset($_SESSION['member_id'])){
  $id = $_REQUEST['report_id'];

  $reports = $db->prepare('SELECT * FROM reports WHERE report_id=?');
  $reports->execute(array($id));
  $report = $reports->fetch();

  if ($report['member_id'] == $_SESSION['member_id']){
    $del = $db->prepare('DELETE FROM reports WHERE report_id=?');
    $del->execute(array($id));
  }
}
header('Location: list.php');
exit();
?>


