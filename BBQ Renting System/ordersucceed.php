<!doctype html>
<html>
  <head>
    <title>訂位成功</title>
    <meta charset="utf-8">
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
  else
  {
  require_once("dbtools.inc.php");
  $id = $_COOKIE{"id"};
  header("Content-type: text/html; charset=utf-8"); 

  //建立資料連接
  $link = create_connection();
  
  //建立訂單資料
  $sql = "SELECT MAX(ordercode) FROM orderlist Where 1";
  $result = execute_sql($link, "restaurant", $sql);
  @$max = preg_split("/^CSIE/", implode(" ",mysqli_fetch_assoc($result)));
  @$max[1] += 1;
  $csie = "CSIE";
  $csie .= str_pad($max[1], 4, "0", STR_PAD_LEFT);
  @$temp = $_POST["food"];
  if($temp != NULL)
  {
	 $orderlist = "";
	$num = count($temp);
	$i = 0;
	forEach($temp as $value)
	{
		$orderlist .= $value;
		if(++$i != $num)
		$orderlist .= ",";
	}

	//插入訂單至資料庫
	$sql = "INSERT INTO orderlist (id, ordercode, orderlist) VALUES ('$id', '$csie', '$orderlist')";
	execute_sql($link, "restaurant", $sql);
	?>
	<br>	
	<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
		<tr class='primary'> 
			<td colspan='2' align='center'> 
				<label><font size='4'>訂位成功</font></label>
			</td>
		</tr>
		<td colspan='2' align="center"> 
			<div class="form-group">
				<div class="col-sm-offset-0 col-sm-0">
					<br>
					<button type="button" class="btn btn-primary" onClick='location.href="main.php"'><label><font size='2'>回主頁</label></font></button>
				</div>
			</div>
          </td>
     </table>
  </body>
	<?php
	header('refresh:5;url="main.php"'); 
  }else
  {
	?>
	<br>	
	<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
		<tr class='primary'> 
			<td colspan='2' align='center'> 
				<label><font size='4'>您尚未勾選任何菜色</font></label>
			</td>
		</tr>
		<td colspan='2' align="center"> 
			<div class="form-group">
				<div class="col-sm-offset-0 col-sm-0">
					<br>
					<button type="button" class="btn btn-primary" onClick='location.href="order.php"'><label><font size='2'>回訂餐頁面</label></font></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-primary" onClick='location.href="main.php"'><label><font size='2'>回主頁</label></font></button>
				</div>
			</div>
          </td>
     </table>
  </body>
	<?php
	 header('refresh:5;url="order.php"'); 
  }
  
  }
 ?>

 <?php
  //釋放 $result 佔用的記憶體
  //mysqli_free_result($result);
		
  //關閉資料連接	
  mysqli_close($link);
?>