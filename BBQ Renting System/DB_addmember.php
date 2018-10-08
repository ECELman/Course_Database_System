<?php

  require_once("dbtools.inc.php");
  
  //取得表單資料
  $account = $_POST["account"];
  $password = $_POST["password"]; 
  $name = $_POST["name"];
  $identification = $_POST["identification"];
  $cellphone = $_POST["cellphone"]; 	
  $address = $_POST["address"];
  $email = $_POST["email"];
  $vat_number = $_POST["vat_number"];
  
  //分析密碼
  $password_orign = substr($password, 0, strpos($password,"/"));
  $password_MD5 = substr($password, strpos($password,"/") + 1);
  
  //建立資料連接
  $link = create_connection();
			
  //檢查帳號是否有人申請
  $sql = "SELECT * FROM user Where id = '$account'";
  $result = execute_sql($link, "bbq_database", $sql);

  //如果帳號已經有人使用
  if (mysqli_num_rows($result) != 0)
  {
    //釋放 $result 佔用的記憶體
    mysqli_free_result($result);
		
    //顯示訊息要求使用者更換帳號名稱
    echo "<script type='text/javascript'>";
    echo "alert('您所指定的帳號已經有人使用，請使用其它帳號');";
    echo "history.back();";
    echo "</script>";
  }
	
  //如果帳號沒人使用
  else
  {
    //釋放 $result 佔用的記憶體	
    mysqli_free_result($result);
		
    //執行 SQL 命令，新增此帳號
    $sql = "INSERT INTO user (id, name, mail, phone, password) 
	        VALUES ('$account', '$name', '$email', '$cellphone', '$password_MD5')";

    $result = execute_sql($link, "bbq_database", $sql);
	
	$sql = "INSERT INTO rent_person (id, vat_number, address, identification) 
	        VALUES ('$account', '$vat_number', '$address', '$identification')";
		
    $result = execute_sql($link, "bbq_database", $sql);
  }
	
  //關閉資料連接	
  mysqli_close($link);
?>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>新增帳號成功</title>
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
		margin-top: 30px;
		margin-right: 200px;
		margin-left: 200px;
	}
	</style>
    <p align="center"><img src="success.png"> 
	<br>	
	<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
		<tr class='info'> 
			<td colspan='2' align='center'> 
				<label><font size='5'>恭喜您已經註冊成功了，您的資料如下：</font></label>
			</td>
		</tr> 
		<tr> 
			<td align='center'> 
				<label><font size='5'>帳號：</font></label>
			</td>
			<td align='center'>
				 <label><font color="#FF0000" size='5'><?php echo $account ?></font></label>
			</td>
		</tr>
		<tr> 
			<td align='center'> 
				<label><font size='5'>密碼：</font></label>
			</td>
			<td align='center'>
				 <label><font color="#FF0000" size='5'><?php echo $password_orign ?></font></label>
			</td>
		</tr>
		<tr> 
			<td align='center'> 
				<label><font size='5'>姓名：</font></label>
			</td>
			<td align='center'>
				 <label><font color="#FF0000" size='5'><?php echo $name ?></font></label>
			</td>
		</tr>
		<tr> 
			<td align='center'> 
				<label><font size='5'>身份：</font></label>
			</td>
			<td align='center'>
				 <label><font color="#FF0000" size='5'><?php echo ($identification == 0)?"校內人士":"校外人士" ?></font></label>
			</td>
		</tr>
		<tr> 
			<td align='center'> 
				<label><font size='5'>行動電話：</font></label>
			</td>
			<td align='center'>
				 <label><font color="#FF0000" size='5'><?php echo $cellphone ?></font></label>
			</td>
		</tr>
		<tr> 
			<td align='center'> 
				<label><font size='5'>地址：</font></label>
			</td>
			<td align='center'>
				 <label><font color="#FF0000" size='5'><?php echo $address ?></font></label>
			</td>
		</tr>
		<tr> 
			<td align='center'> 
				<label><font size='5'>E-mail：</font></label>
			</td>
			<td align='center'>
				 <label><font color="#FF0000" size='5'><?php echo $email ?></font></label>
			</td>
		</tr>
		<tr> 
			<td align='center'> 
				<label><font size='5'>統一編號：</font></label>
			</td>
			<td align='center'>
				 <label><font color="#FF0000" size='5'><?php echo $vat_number ?></font></label>
			</td>
		</tr>
		<tr class='danger'> 
			<td colspan='2' align='center'> 
				<label><font size='5'>請牢記帳號及密碼。</font></label>
			</td>
		</tr>
		<tr>
          <td colspan='2' align="center"> 
			<div class="form-group">
				<div class="col-sm-offset-0 col-sm-0">
					<br>
					<button type="button" class="btn btn-success" onClick='location.href="main.php"'><label><font size='4'>返回管理頁面</label></font></button>
				</div>
			</div>
          </td>
		</tr>
	</table>
    </p>
  </body>
</html>