<!doctype html>

<html>

	<head>
	
		<title>歡迎光臨</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets2/css/Welcome.css" />
		
	</head>
	
	<body class="loading">
	
		<div id="wrapper">
			<div id="bg"></div>
			<div id="overlay"></div>
			<div id="main">

				<header id="header">
					<h1>Welcome</h1>
					<p>To Enjoy &nbsp;&bull;&nbsp; And &nbsp;&bull;&nbsp; To Free</p>
					<nav>
						<ul>
							<li><a href="#" class="icon fa-github" onclick="location.href='Home.html'"><span class="label">GO</span></a></li>
						</ul>
					</nav>
				</header>

			</div>
		</div>

		<script>
			window.onload = function() { document.body.className = ''; }
			window.ontouchmove = function() { return false; }
			window.onorientationchange = function() { document.body.scrollTop = 0; }
		</script>
		
	</body>
	
</html>