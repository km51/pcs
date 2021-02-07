<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Login | PCS</title>
	<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="css/fixed.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
	<a class="navbar-brand" href="login.php">Production Control System</a>
	
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive">
		<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarResponsive">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a class="nav-link" href="login.php">ログイン</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="register.php">登録</a>
      </li>
      <li class="nav-item">
				<a class="nav-link" href="index.php">簡単ログイン</a>
			</li>
		</ul>
	</div>
</nav>
<div id="wrap">
<div id="head">
<h1><br><br></h1>
</div>

<div id="content">
<p>メールを送信しました。※</p>
<p>5分以内に届かない場合は再度送信するか、システム課に確認してください。</p>
<p><a href="login.php">ログインする</a></p>

※テスト中のため、実際にはメールは送信していません。
<span id="view_today"></span>

<script type="text/javascript">
document.getElementById("view_today").innerHTML = getToday();

function getToday() {
	var now = new Date();
	var year = now.getFullYear();
	var mon = now.getMonth()+1; //１を足すこと
	var day = now.getDate();
	var you = now.getDay(); //曜日(0～6=日～土)

	//曜日の選択肢
	// var youbi = new Array("日","月","火","水","木","金","土");
	//出力用
	var s = "（" + year + "年" + mon + "月" + day + "日 現在）";
	return s;
}
</script>
</div>

</div>
	<script src="js/jquery-3.3.1.min.js"></script>
	<script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>

</body>
</html>
