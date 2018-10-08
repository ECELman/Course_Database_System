<?php
    require_once("dbtools.inc.php");
	
    //取得 modify.php 網頁的表單資料
	$account = $_POST["account"];
	$password = $_POST["password"]; 
	$name = $_POST["name"];
	$identification = $_POST["identification"];
	$cellphone = $_POST["cellphone"]; 	
	$address = $_POST["address"];
	$email = $_POST["email"];
	$vat_number = $_POST["vat_number"];
		
    //建立資料連接
    $link = create_connection();
				
    //執行 UPDATE 陳述式來更新使用者資料
	if($password == "No")
	{
		$sql = "UPDATE user SET name = '$name', mail = '$email', phone = '$cellphone' WHERE id = '$account'";
		$result = execute_sql($link, "bbq_database", $sql);
	}
	else
	{		
		$sql = "UPDATE user SET name = '$name', mail = '$email', phone = '$cellphone',
        		password = '$password' WHERE id = '$account'";
		$result = execute_sql($link, "bbq_database", $sql);
	}
    	
	$sql = "UPDATE rent_person SET vat_number = '$vat_number', address = '$address'
	        , identification = '$identification' WHERE id = '$account'";
    $result = execute_sql($link, "bbq_database", $sql);
		
    //關閉資料連接
    mysqli_close($link);
?>
<!doctype html>
<html>
  <head>
    <title>修改會員資料成功</title>
    <meta charset="utf-8">
  </head>
  <body>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <style type="text/css"> 
	body
	{
		background-color:rgba(255,255,255,0.8);
		background-image: url("bbq.jpg");
		background-blend-mode: lighten;
		margin-bottom: 100px;
		margin-top: 50px;
		margin-right: 200px;
		margin-left: 200px;
	}
	</style>
    <center>
      <img src="revise.png"><br><br>
      <font size="5"><label>會員 <?php echo $account ?> 的個人資料已經修改成功。</label></font>
	  <div class="form-group">
				<div class="col-sm-offset-0 col-sm-20">
					<br>
					<button type="button" class="btn btn-primary" onClick='location.href="DB_select_person.php"'>回修改管理頁面</button>
				</div>
		</div>
    </center>        
  </body>
</html>

<?php
	setcookie("id", "0");
    setcookie("passed", "TRUE");
?>