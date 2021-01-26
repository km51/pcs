<?php
try{
// 例外処理

    $db = new PDO('mysql:dbname=pcs;host=127.0.0.1;charset=utf8', 'root', '_*n7PACtL7qKM#4');
    // DBに接続できるようになった。

}catch(PDOException $e){
  // DBに接続できなかった場合
  print('DB接続エラー：' . $e->getMessage());
}
?>