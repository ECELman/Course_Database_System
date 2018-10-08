<?php
  //檢查 cookie 中的 passed 變數是否等於 TRUE
  $passed = $_COOKIE["passed"];
	
  /*  如果 cookie 中的 passed 變數不等於 TRUE，
      表示尚未登入網站，將使用者導向首頁 index.htm */
  if ($passed != "TRUE")
  {
    header("location:index.htm");
    exit();
  }
	
  /*  如果 cookie 中的 passed 變數等於 TRUE，
      表示已經登入網站，將使用者的帳號刪除 */	
	else
	{
		require_once("dbtools.inc.php");
		$id = $_COOKIE["id"];
		$password = $_POST["password"];
		
		//建立資料連接
		$link = create_connection();
	
		$sql = "SELECT * FROM users WHERE id = $id";
		$result = execute_sql($link, "restaurant", $sql);
	
		$temp = mysqli_fetch_assoc($result);
		if($temp{"password"} == $password)
		{
			//刪除帳號及現有訂單
			$sql = "DELETE FROM orderlist Where id = $id";
			$result = execute_sql($link, "restaurant", $sql);
			$sql = "DELETE FROM users Where id = $id";
			$result = execute_sql($link, "restaurant", $sql);
		}
		else
		{
			echo "密碼輸入錯誤，請確認密碼後再輸入";
			setcookie("passed", "", time()-3600);
			header('refresh:5;url="index.htm"');
			exit();
		}
		
    //關閉資料連接
    mysqli_close($link);
  }
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>刪除會員資料成功</title>
  </head>
  <body>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style type="text/css"> 
	body
	{
		background-color:rgba(255,255,255,0.8);
		background-image: url("restaurant.jpg");
		background-blend-mode: lighten;
		margin-bottom: 100px;
		margin-top: 50px;
		margin-right: 350px;
		margin-left: 350px;
	}
	</style>
    <p align="center"><img src="erase.png"></p>
	<br>	
	<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
		<tr class='danger'> 
			<td colspan='2' align='center'> 
				<label><font size='4'>您的資料已從本站中刪除，若要再次使用本站台服務，請重新申請，謝謝。</font></label>
			</td>
		</tr>
		<td colspan='2' align="center"> 
			<div class="form-group">
				<div class="col-sm-offset-0 col-sm-0">
					<br>
					<button type="button" class="btn btn-primary" onClick='location.href="index.htm"'><label><font size='2'>回首頁</label></font></button>
				</div>
			</div>
          </td>
     </table>
  </body>
</html>