<!doctype html>
<html>
  <head>
    <meta charset="utf-8">
    <title>國立高雄大學露營烤肉區租借系統</title>
    <script type="text/javascript">
	
		
		
	//https://css-tricks.com/snippets/javascript/javascript-md5/
	//----------------------------------------------------------------------------
	function check_data()
	{
		if (document.dataForm.account.value.length == 0)
			alert("帳號欄位不可以空白哦！");
		else dataForm.submit();
	}
    </script>
  </head>
  <body>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<style type="text/css"> 
	body
	{
		background-color:rgba(255,255,255,0.8);
		background-image: url("bbq.jpg");
		background-blend-mode: lighten;
	}
	</style>
    <h1 align="center"><b>修改會員資料</b></h1>
    <form action="DB_check_person.php" method="post" name="dataForm">
      <table width="40%" align="center">
        <tr>
          <td> 
		    <div class="form-group">
              <h2>請輸入欲修改的會員帳號：</h2> 
              <input name="account" type="text" size="15" class="form-control" placeholder="account">
			</div>
          </td>
        </tr>
        <tr>
          <td align="center"> 
			<div class="form-group">
				<div class="col-sm-offset-0 col-sm-20">
				<button type="button" onClick="check_data()" class="btn btn-primary"><label>提交</label></button>　 
				<button type="reset" class="btn btn-default"><label>重填</label></button>
				<br><br><br><br>
				<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
					<tr class='info'> 
						<td colspan='2' align='center'> 
							<label><font size='5'>會員名單</font></label>
						</td>
					</tr> 
					<tr> 
						<td align='center'> 
							<label><font size='5'>帳號</font></label>
						</td>
						<td align='center'>
							<label><font size='5'>姓名</font></label>
						</td>
					</tr>
					<?php
						require_once("dbtools.inc.php");
					
						//建立資料庫連接
						$link = create_connection();
						
						mysqli_select_db($link, "bbq_database"); //選擇資料庫bbq_database
						
						$sql = "SELECT rent_person.id, name 
						        FROM rent_person, user 
								WHERE rent_person.id = user.id";
						
						$result = mysqli_query($link,$sql); //執行SQL查詢
						
						$total_records = mysqli_num_rows($result);  // 取得資料表裡頭總共有幾筆資料
						
						for($i = 1; $i <= $total_records; $i++)
						{	
							$row = mysqli_fetch_assoc($result); //將陣列以欄位名索引
					
							echo "<tr><td align='center'><label><font size='5'>";
							echo $row{"id"};
							echo "</font></label></td><td align='center'><label><font size='5'>";
							echo $row{"name"};
							echo "</font></label></td></tr>";
						}
						
						setcookie("id", "0");
					?>
				</table>
				<br><br><br><br>
				<button type="button" class="btn btn-warning" OnClick='location.href="main.php"'><font size="5"><label>回管理頁面</label></font></button>
				</div>
			</div>
          </td>
        </tr>
	  </table>
    </form>
  </body>
</html>
