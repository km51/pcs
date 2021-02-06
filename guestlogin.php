<?php
session_start();
require('dbconnect.php');

$_SESSION['member_id']='11';
$_SESSION['time'] = time();

header('Location: index.php');
exit();

?>