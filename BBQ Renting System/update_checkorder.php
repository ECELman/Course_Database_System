<!doctype html>
<html>
  <head>
    <title>國立高雄大學露營烤肉區租借系統</title>
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

  $receipt_serial = $_POST{"receipt_serial"};
  $bbq_increase_or_decrease = $_POST{"bbq_increase_or_decrease"};
  $bbq_change_amount = $_POST{"bbq_change_amount"};
  $camp_increase_or_decrease = $_POST{"camp_increase_or_decrease"};
  $camp_change_amount = $_POST{"camp_change_amount"};
  $bbq_check = true;
  
  header("Content-type: text/html; charset=utf-8"); 

  //建立資料連接
  $link = create_connection(); 
  
  
  //檢查烤肉區
  if($bbq_increase_or_decrease != "請選擇" || $bbq_increase_or_decrease != NULL)
  {
	  if($bbq_increase_or_decrease == "增加")
	  {
		  $sql = "SELECT COUNT(*) AS COUNT_RESULT FROM receipt WHERE receipt_no <= 12 AND use_date = (SELECT use_date FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no <= 12 LIMIT 0,1 ) AND time_interval = (SELECT time_interval FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no <= 12 LIMIT 0,1 ) ";
		  $result = execute_sql($link, "bbq_database", $sql);
		  $row = mysqli_fetch_assoc($result);
		  if(12 - $row{"COUNT_RESULT"} >= $bbq_change_amount)
			$bbq_check = true;
		  else
			$bbq_check = false;
	  }else{
		  $sql = "SELECT COUNT(*) AS COUNT_RESULT FROM receipt WHERE receipt_no <= 12 AND receipt_serial = '$receipt_serial' ";
		  $result = execute_sql($link, "bbq_database", $sql);
		  $row = mysqli_fetch_assoc($result);
		  if($row{"COUNT_RESULT"} - $bbq_change_amount >= 0)
			$bbq_check = true;
		  else
			$bbq_check = false;
	  }
  }else{$bbq_check = true;}
  
  //檢查露營區
  if($camp_increase_or_decrease != "請選擇" || $camp_increase_or_decrease != NULL)
  {
	  if($camp_increase_or_decrease == "增加")
	  {
		  $sql = "SELECT COUNT(*) AS COUNT_RESULT FROM receipt WHERE receipt_no > 12 AND receipt_no <=24 AND use_date = (SELECT use_date FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no > 12 AND receipt_no <=24 LIMIT 0,1) AND time_interval = (SELECT time_interval FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no > 12 AND receipt_no <=24 LIMIT 0,1) ";
		  $result = execute_sql($link, "bbq_database", $sql);
		  $row = mysqli_fetch_assoc($result);
		  if(12 - $row{"COUNT_RESULT"} >= $camp_change_amount)
			$camp_check = true;
		  else
			$camp_check = false;
	  }else{
		  $sql = "SELECT COUNT(*) AS COUNT_RESULT FROM receipt WHERE receipt_no > 12 AND receipt_no <=24 AND receipt_serial = '$receipt_serial' ";
		  $result = execute_sql($link, "bbq_database", $sql);
		  $row = mysqli_fetch_assoc($result);
		  if($row{"COUNT_RESULT"} - $camp_change_amount >= 0)
			$camp_check = true;
		  else
			$camp_check = false;
	  }
  }else{$camp_check = true;}
  
  //開始寫入資料庫
  if($bbq_check == true && $camp_check == true) 
  {
	  if($bbq_change_amount != "請選擇")
	  {
		  if($bbq_increase_or_decrease == "增加")
		  {
			$sql = "SELECT MAX(receipt_no) AS RESULT FROM receipt WHERE use_date = (SELECT use_date FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no <= 12 LIMIT 0,1) AND time_interval = (SELECT time_interval FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no <= 12 LIMIT 0,1) AND receipt_no <= 12";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			if($row{"RESULT"} == NULL)
				$receipt_no = 1;
			else{$receipt_no = $row{"RESULT"} + 1;}
			$sql = "SELECT * FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no <= 12";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			$id = $row{"id"};
			$startdate = $row{"use_date"};
			$time_interval = $row{"time_interval"};
			for($i = 0;$i < $bbq_change_amount;$receipt_no += 1, $i += 1)
			{
				$sql = "INSERT INTO `receipt` (`id`, `receipt_no`, `receipt_serial`, `use_date`, `time_interval`, `accept`) VALUES ('$id', '$receipt_no', '$receipt_serial', '$startdate', '$time_interval', '0')";
				$result = execute_sql($link, "bbq_database", $sql);
			} 
		  }else{
				$sql = "SELECT * FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no <= 12";
				$result = execute_sql($link, "bbq_database", $sql);
				$row = mysqli_fetch_assoc($result);
				$id = $row{"id"};
				$sql = "SELECT MAX(receipt_no) AS RESULT FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no <= 12";
				$result = execute_sql($link, "bbq_database", $sql);
				$row = mysqli_fetch_assoc($result);
				$receipt_no = $row{"RESULT"};
				for($i = 0;$i < $bbq_change_amount;$receipt_no -= 1, $i += 1)
				{
					$sql = "DELETE FROM receipt WHERE id = '$id' AND receipt_no = $receipt_no AND receipt_serial = '$receipt_serial'";
					$result = execute_sql($link, "bbq_database", $sql);
				} 
		  }
	  }
	  
	  if($camp_change_amount != "請選擇")
	  {
		  if($camp_increase_or_decrease == "增加")
		  {
			$sql = "SELECT MAX(receipt_no) AS RESULT FROM receipt WHERE use_date = (SELECT use_date FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no > 12 AND receipt_no <= 24 LIMIT 0,1) AND time_interval = (SELECT time_interval FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no > 12 AND receipt_no <= 24 LIMIT 0,1) AND receipt_no > 12 AND receipt_no <= 24";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			if($row{"RESULT"} == NULL)
				$receipt_no = 13;
			else{$receipt_no = $row{"RESULT"} + 1;}
			$sql = "SELECT * FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no > 12 AND receipt_no <= 24";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			$id = $row{"id"};
			$startdate = $row{"use_date"};
			$time_interval = $row{"time_interval"};
			for($i = 0;$i < $camp_change_amount;$receipt_no += 1, $i += 1)
			{
				$sql = "INSERT INTO `receipt` (`id`, `receipt_no`, `receipt_serial`, `use_date`, `time_interval`, `accept`) VALUES ('$id', '$receipt_no', '$receipt_serial', '$startdate', '$time_interval', '0')";
				$result = execute_sql($link, "bbq_database", $sql);
			} 
		  }else{
				$sql = "SELECT * FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no > 12 AND receipt_no <= 24";
				$result = execute_sql($link, "bbq_database", $sql);
				$row = mysqli_fetch_assoc($result);
				$id = $row{"id"};
				$sql = "SELECT MAX(receipt_no) AS RESULT FROM receipt WHERE receipt_serial = '$receipt_serial' AND receipt_no > 12 AND receipt_no <= 24";
				$result = execute_sql($link, "bbq_database", $sql);
				$row = mysqli_fetch_assoc($result);
				$receipt_no = $row{"RESULT"};
				for($i = 0;$i < $camp_change_amount;$receipt_no -= 1, $i += 1)
				{
					$sql = "DELETE FROM receipt WHERE id = '$id' AND receipt_no = $receipt_no AND receipt_serial = '$receipt_serial'";
					$result = execute_sql($link, "bbq_database", $sql);
				} 
		  }
	  }
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
				<label><font size='4'>您預定的時段空位不夠，請查詢後再預約</font></label>
			</td>
		</tr>
		<td colspan='2' align="center"> 
			<div class="form-group">
				<div class="col-sm-offset-0 col-sm-0">
					<br>
					<button type="button" class="btn btn-primary" onClick='history.back()'><label><font size='2'>回變更頁面</label></font></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-primary" onClick='location.href="main.php"'><label><font size='2'>回主頁</label></font></button>
				</div>
			</div>
          </td>
     </table>
  </body>
	<?php
	 header('refresh:5;url="order.php"'); 
  }
 ?>

 <?php
  //釋放 $result 佔用的記憶體
  //mysqli_free_result($result);
		
  //關閉資料連接	
  mysqli_close($link);
  }
?>