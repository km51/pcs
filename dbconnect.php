<?php
try{
// 例外処理

    $db = new PDO('mysql:dbname=heroku_1572927894d2baf;host=us-cdbr-east-03.cleardb.com;charset=utf8', 'b20f2cba5b89cc', 'b601a40c');
    // DBに接続できるようになった。

}catch(PDOException $e){
  // DBに接続できなかった場合
  print('DB接続エラー：' . $e->getMessage());
}
?>
