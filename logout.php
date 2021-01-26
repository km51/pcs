<?php
session_start();

// // ログアウト時はセッションの情報を削除する必要がある

$_SESSION = array();
// セッション情報を、空の配列で上書き
if (ini_set('session.use_cookies',1)){
  $params = session_get_cookie_params();
  setcookie(session_name() . '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}
session_destroy();

setcookie('email', '', time() - 3600);

header('Location: login.php');
exit();
?>