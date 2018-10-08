<?php	
    require_once("dbtools.inc.php");
    //建立資料連接
    $link = create_connection();
	
	$name = $_POST["name"];
	$email = $_POST["email"];
	$message = $_POST["message"];
	if($name != NULL && $email != NULL && $message != NULL)
	{
	//執行 INSERT INTO 陳述式取得使用者資料
	$sql = "INSERT INTO messageboard (name, email, message) VALUES ('$name', '$email', '$message')";
	execute_sql($link, "restaurant", $sql);
	}
?>
<!DOCTYPE HTML>

<html>

	<head>
	
		<title>感謝填寫</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets4/css/Thank.css" />
		<noscript><link rel="stylesheet" href="assets4/css/noscript.css" /></noscript>
		
	</head>
	
	<body>

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Header -->
					<header id="header">
						<div class="logo">
							<span class="icon fa-diamond"></span>
						</div>
						<div class="content">
							<div class="inner">
								<h1>感謝填寫</h1>
								<p>我們將會改善您所提供的意見</p><p>期待您下一次的到來</p>
							</div>
						</div>
						<nav>
							<ul>
								<li><a href="Home.html">回到首頁</a></li>
								<li><a href="Introduction.html">餐點介紹</a></li>
								<li><a href="About.html">關於我們</a></li>
								<li><a href="index.htm">我要訂位</a></li>
								<li><a href="Response.html">意見回饋</a></li>
							</ul>
						</nav>
					</header>

				<!-- Footer -->
					<footer id="footer">
						<p class="copyright">&copy; T-TEAM. Design: HELLO WORLD</a>.</p>
					</footer>

			</div>

		<!-- BG -->
			<div id="bg"></div>

		<!-- Scripts -->
			<script src="assets4/js/jquery.min.js"></script>
			<script src="assets4/js/skel.min.js"></script>
			<script src="assets4/js/util.js"></script>
			<script src="assets4/js/main.js"></script>

	</body>
	
</html>
