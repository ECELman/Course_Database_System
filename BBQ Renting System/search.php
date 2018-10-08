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

  $startdate = $_POST{"startdate"};
  $enddate = $_POST{"enddate"};
  $bbq_amount = $_POST{"bbq_amount"};
  $time_interval = $_POST{"time_interval"};
  $camp_amount = $_POST{"camp_amount"};
  $show_start_hour = $_POST{"show_start_hour"};
  $show_start_minute = $_POST{"show_start_minute"};
  $show_end_hour = $_POST{"show_end_hour"};
  $show_end_minute = $_POST{"show_end_minute"};
  
  $bbq_check = false;
  $camp_check = false;
  $show_check = true;
  $devide_2_receipt = false;
  $order_belongs_to = false;
  
  header("Content-type: text/html; charset=utf-8"); 

  //建立資料連接
  $link = create_connection();
  
  //確認租借人
  if(@$_POST{"order_belongs_to"} == NULL)
  {
	$id = $_COOKIE{"id"};
	$order_belongs_to = true;
  }
  else{
	$id = $_POST{"order_belongs_to"};
	$order_belongs_to = true;
	$sql = "SELECT COUNT(*) AS RESULT FROM rent_person WHERE id = '$id'";
    $result = execute_sql($link, "bbq_database", $sql);
    $row = mysqli_fetch_assoc($result);
    if($row{"RESULT"} != 1)
	{
		$order_belongs_to = false;
	}
  }
  
  
  
  
  //檢查烤肉區
  if($bbq_amount != "請選擇" && $order_belongs_to == true)
  {
	  if($time_interval != NULL)
	  {
		  $sql = "SELECT COUNT(*) AS COUNT_RESULT FROM receipt WHERE receipt_no <= 12 AND use_date = '$startdate' AND time_interval = '$time_interval' ";
		  $result = execute_sql($link, "bbq_database", $sql);
		  $row = mysqli_fetch_assoc($result);
		  if(12 - $row{"COUNT_RESULT"} >= $bbq_amount)
			$bbq_check = true;
	  }
  }else{$bbq_check = true;}
  
  //檢查露營區
  if($camp_amount != "請選擇" && $order_belongs_to == true)
  {
	  $sql = "SELECT COUNT(*) AS COUNT_RESULT FROM receipt WHERE receipt_no > 12 AND receipt_no <= 24 AND use_date = '$startdate' AND time_interval = '12:30~11:30' ";
	  $result = execute_sql($link, "bbq_database", $sql);
	  $row = mysqli_fetch_assoc($result);
	  if(12 - $row{"COUNT_RESULT"} >= $camp_amount)
		  $camp_check = true;
  }else{$camp_check = true;}
  
  //檢查露天表演場
  if($show_start_hour != "請選擇" && $show_start_minute != "請選擇" && $show_end_hour != "請選擇" && $show_end_minute != "請選擇" && $order_belongs_to == true)
  {
	  $sql = "SELECT time_interval AS RESULT FROM receipt WHERE receipt_no = 25 AND use_date = '$startdate'";
	  $result = execute_sql($link, "bbq_database", $sql);
	  if($show_start_hour > $show_end_hour || ($show_start_hour == $show_end_hour && $show_start_minute > $show_end_minute))
	  {
		  $devide_2_receipt = true;
		  while($row = mysqli_fetch_assoc($result))
		  {
			  $time_str = explode("~", $row{"RESULT"});
			  $start_time = explode(":", $time_str[0]);
			  $end_time = explode(":", $time_str[1]);
			  $end_time[0] = '23';
			  $end_time[1] = '59';
		  
			  if((($show_start_hour < $start_time[0])
			  || ($show_start_hour == $start_time[0] && $show_start_minute < $start_time[1])
			  || ($show_start_hour > $end_time[0])
			  || ($show_start_hour == $end_time[0] && $show_start_minute >= $end_time[1]))
			  && (
				 ($show_end_hour < $start_time[0])
			  || ($show_end_hour == $start_time[0] && $show_end_minute <= $start_time[1])
			  || ($show_end_hour > $end_time[0])
			  || ($show_end_hour == $end_time[0] && $show_end_minute > $end_time[1])))
			  {
				  $show_check = true;
			  }else{
				  $show_check = false;
				  break;
				  }
		  }
		  if($show_check != false)
		  {
			  $sql = "SELECT time_interval AS RESULT FROM receipt WHERE receipt_no = 25 AND use_date = '$enddate'";
			  $result = execute_sql($link, "bbq_database", $sql);
			  while($row = mysqli_fetch_assoc($result))
			  {
				  $time_str = explode("~", $row{"RESULT"});
				  $start_time = explode(":", $time_str[0]);
				  $end_time = explode(":", $time_str[1]);
				  $start_time[0] = '00';
				  $start_time[1] = '00';
		  
				  if((($show_start_hour < $start_time[0])
				  || ($show_start_hour == $start_time[0] && $show_start_minute < $start_time[1])
				  || ($show_start_hour > $end_time[0])
				  || ($show_start_hour == $end_time[0] && $show_start_minute >= $end_time[1]))
				  && (
					 ($show_end_hour < $start_time[0])
				  || ($show_end_hour == $start_time[0] && $show_end_minute <= $start_time[1])
				  || ($show_end_hour > $end_time[0])
				  || ($show_end_hour == $end_time[0] && $show_end_minute > $end_time[1])))
				  {
					  $show_check = true;
				  }else{
					  $show_check = false;
					  break;
					  }
			  }
		  }
	  }else{
		  while($row = mysqli_fetch_assoc($result))
		  {
			  $time_str = explode("~", $row{"RESULT"});
			  $start_time = explode(":", $time_str[0]);
			  $end_time = explode(":", $time_str[1]);
		  
			  if((($show_start_hour < $start_time[0])
			  || ($show_start_hour == $start_time[0] && $show_start_minute < $start_time[1])
			  || ($show_start_hour > $end_time[0])
			  || ($show_start_hour == $end_time[0] && $show_start_minute >= $end_time[1]))
			  && (
				 ($show_end_hour < $start_time[0])
			  || ($show_end_hour == $start_time[0] && $show_end_minute <= $start_time[1])
			  || ($show_end_hour > $end_time[0])
			  || ($show_end_hour == $end_time[0] && $show_end_minute > $end_time[1])))
			  {
				  $show_check = true;
			  }else{
				  $show_check = false;
				  break;
				  }
		  }
	  }
	  
  }else{$show_check = true;}
  
  
  if($bbq_check == true && $camp_check == true && $show_check == true && $order_belongs_to == true)
  {
	  if($bbq_amount != "請選擇")
	  {
		  $sql = "SELECT MAX(receipt_no) AS RESULT FROM receipt WHERE use_date = '$startdate' AND time_interval = '$time_interval' AND receipt_no <= 12";
		  $result = execute_sql($link, "bbq_database", $sql);
		  $row = mysqli_fetch_assoc($result);
		  
		  if($row{"RESULT"} == NULL)
			  $receipt_no = 1;
		  else{$receipt_no = $row{"RESULT"} + 1;}
		  for($i = 0;$i < $bbq_amount;$receipt_no += 1, $i += 1)
		  {
			  $sql = "INSERT INTO `receipt` (`id`, `receipt_no`, `receipt_serial`, `use_date`, `time_interval`, `accept`) VALUES ('$id', '$receipt_no', '$receipt_serial', '$startdate', '$time_interval', '0')";
			  $result = execute_sql($link, "bbq_database", $sql);
		  }  
	  }
	  
	  if($camp_amount != "請選擇")
	  {
		  $sql = "SELECT MAX(receipt_no) AS RESULT FROM receipt WHERE use_date = '$startdate' AND time_interval = '12:30~11:30' AND receipt_no > 12 AND receipt_no <= 24";
		  $result = execute_sql($link, "bbq_database", $sql);
		  $row = mysqli_fetch_assoc($result);
		  
		  if($row{"RESULT"} == NULL)
			  $receipt_no = 13;
		  else{$receipt_no = $row{"RESULT"} + 1;}
		  for($i = 0;$i < $camp_amount;$receipt_no += 1, $i += 1)
		  {
			  $sql = "INSERT INTO `receipt` (`id`, `receipt_no`, `receipt_serial`, `use_date`, `time_interval`, `accept`) VALUES ('$id', '$receipt_no', '$receipt_serial', '$startdate', '12:30~11:30', '0')";
			  $result = execute_sql($link, "bbq_database", $sql);
		  }  
	  }
	  
	  if($show_start_hour != "請選擇")
	  {
		  if($devide_2_receipt == true)
		  {
			  $time_interval = $show_start_hour;
			  $time_interval .= ':';
			  $time_interval .= $show_start_minute;
			  $time_interval .= '~';
			  $time_interval .= '23:59';
			  
			  $sql = "INSERT INTO `receipt` (`id`, `receipt_no`, `receipt_serial`, `use_date`, `time_interval`, `accept`) VALUES ('$id', '25', '$receipt_serial', '$startdate', '$time_interval', '0')";
			  $result = execute_sql($link, "bbq_database", $sql);
			  
			  $time_interval = '00:00~';
			  $time_interval .= $show_end_hour;
			  $time_interval .= ':';
			  $time_interval .= $show_end_minute;
			  $receipt_serial += 1;
			  
			  $sql = "INSERT INTO `receipt` (`id`, `receipt_no`, `receipt_serial`, `use_date`, `time_interval`, `accept`) VALUES ('$id', '25', '$receipt_serial', '$enddate', '$time_interval', '0')";
			  $result = execute_sql($link, "bbq_database", $sql);
		  }else{
			  $time_interval = $show_start_hour;
			  $time_interval .= ':';
			  $time_interval .= $show_start_minute;
			  $time_interval .= '~';
			  $time_interval .= $show_end_hour;
			  $time_interval .= ':';
			  $time_interval .= $show_end_minute;
			  $sql = "INSERT INTO `receipt` (`id`, `receipt_no`, `receipt_serial`, `use_date`, `time_interval`, `accept`) VALUES ('$id', '25', '$receipt_serial', '$startdate', '$time_interval', '0')";
			  $result = execute_sql($link, "bbq_database", $sql);
		  }  
	  }
	?>
	<br>	
	<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
		<tr class='primary'> 
			<td colspan='2' align='center'> 
				<label><font size='4'>查詢結果</font></label>
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
  }else if($order_belongs_to == true)
  {
	?>
	<br>	
	<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
		<tr class='primary'> 
			<td colspan='2' align='center'> 
				<label><font size='4'>您查詢的時段已租借，請更改查詢條件</font></label>
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
  }else
  {
	?>
	<br>	
	<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
		<tr class='primary'> 
			<td colspan='2' align='center'> 
				<label><font size='4'>查無此租借人ID或此租借人無權租借</font></label>
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