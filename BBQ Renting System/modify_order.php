<?php
  //檢查 cookie 中的 passed 變數是否等於 TRUE
  $passed = $_COOKIE{"passed"};
	
  //如果 cookie 中的 passed 變數不等於 TRUE
  //表示尚未登入網站，將使用者導向首頁 index.htm
  if ($passed != "TRUE")
  {
    header("location:index.htm");
    exit();
  }
	
  //如果 cookie 中的 passed 變數等於 TRUE
  //表示已經登入網站，取得使用者資料	
  else
  {
    require_once("dbtools.inc.php");
		
    $id = $_COOKIE{"id"};
		
    //建立資料連接
    $link = create_connection();
?>
<!doctype html>
<html>
  <head>
    <title>會員修改訂位</title>
    <meta charset="utf-8">
	
	
  </head>
  <body>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!--引入 CSS 引入 jQuery 引入 jQuery UI-->
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
  <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
  <script type="text/javascript">
	function check_data()
	{
		if (!((document.dataForm.bbq_increase_or_decrease.value == "請選擇" && document.dataForm.bbq_change_amount.value == "請選擇") || (document.dataForm.bbq_increase_or_decrease.value != "請選擇" && document.dataForm.bbq_change_amount.value != "請選擇")))
        {
          alert("烤肉台尚未完成變更哦...");
          return false;
        }
		if (!((document.dataForm.camp_increase_or_decrease.value == "請選擇" && document.dataForm.camp_change_amount.value == "請選擇") || (document.dataForm.camp_increase_or_decrease.value != "請選擇" && document.dataForm.camp_change_amount.value != "請選擇")))
        {
          alert("營位尚未完成變更哦...");
          return false;
        }
		dataForm.submit();
	}
  </script>
  
  <style type="text/css"> 
	body
	{
		background-color:rgba(255,255,255,0.8);
		background-image: url("bbq.jpg");
		background-blend-mode: lighten;
		margin-bottom: 100px;
		margin-right: 100px;
		margin-left: 100px;
	}
	</style>
    <p align="center" ><b><font size="8">會員修改訂位</font></b></p>
    <form class="form-inline" name="dataForm" method="post" action="update_checkorder.php" >
      <table border="3"  align="center" class = "table table-striped" bordercolor="rhba(255,255,255,0)">
	  <?php
		$receipt_serial = $_POST{"post_value"};
		$sql = "SELECT name FROM receipt, user Where receipt.id = user.id AND receipt_serial = '$receipt_serial'";
		$result = execute_sql($link, "bbq_database", $sql);
		$row = mysqli_fetch_assoc($result);
		?>
		<tr class='info'><td colspan='5' align='center'><label><font size='3'> 修改
		<?php
		echo $row{'name'};
		?>
		的訂單</font></label></td></tr>
		<tr class="default"><td colspan="5" align="center"><label><font size="3">選擇日期 : 
		<?php
			$sql = "SELECT * FROM receipt Where receipt_serial = '$receipt_serial'";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			echo $row{"use_date"};
		?>
		<br /></td></tr>
		<?php
			$sql = "SELECT COUNT(*) AS RESULT FROM receipt Where receipt_no <= 12 AND receipt_serial = '$receipt_serial'";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			if($row{"RESULT"} != 0)
			{
		?>
		<tr class="default"><td colspan="2" align="center"><label><font size="3">烤肉台 : &nbsp;&nbsp;
		<select name="bbq_increase_or_decrease">
		  <option>請選擇</option>
          <option>增加</option>
          <option>減少</option>
          </select>&nbsp;&nbsp;
		  <select name="bbq_change_amount">
		  <option>請選擇</option>
          <option>1</option>
          <option>2</option>
          <option>3</option>
		  <option>4</option>
          <option>5</option>
          <option>6</option>
		  <option>7</option>
          <option>8</option>
          <option>9</option>
		  <option>10</option>
          <option>11</option>
          <option>12</option>
          </select>&nbsp;&nbsp;台<br/></td>
		  <td colspan="3" align="center"><label><font size="3">
		  <?php
			$sql = "SELECT * FROM receipt Where receipt_no <= 12 AND receipt_serial = '$receipt_serial'";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			echo $row{"time_interval"};
			}else{
			?>
			<input type="hidden" name="bbq_increase_or_decrease" value=請選擇/> <input type="hidden" name="bbq_change_amount" value=請選擇/>
			<?php
			}
			?>
		  </td>
		</tr>
		<?php
			$sql = "SELECT COUNT(*) AS RESULT FROM receipt Where receipt_no > 12 AND receipt_no <= 24 AND receipt_serial = '$receipt_serial'";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			if($row{"RESULT"} != 0)
			{
		?>
		<tr class="default"><td colspan="2" align="center"><label><font size="3">營位 :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<select name="camp_increase_or_decrease">
		  <option>請選擇</option>
          <option>增加</option>
          <option>減少</option>
          </select>&nbsp;&nbsp;
		  <select name="camp_change_amount">
		  <option>請選擇</option>
          <option>1</option>
          <option>2</option>
          <option>3</option>
		  <option>4</option>
          <option>5</option>
          <option>6</option>
		  <option>7</option>
          <option>8</option>
          <option>9</option>
		  <option>10</option>
          <option>11</option>
          <option>12</option>
          </select>&nbsp;&nbsp;台<br/></td>
		  <td colspan="3" align="center"><label><font size="3">12:30~翌日11:30
		  </td>
		  <?php
			}else{
		  ?>
			<input type="hidden" name="camp_increase_or_decrease" value=請選擇/> <input type="hidden" name="camp_change_amount" value=請選擇/>
		  <?php
			}
		  ?>
		</tr>
		
		<?php
			$sql = "SELECT COUNT(*) AS RESULT FROM receipt Where receipt_no = 25 AND receipt_serial = '$receipt_serial'";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			if($row{"RESULT"} != 0)
			{
		?>
		<tr class="default"><td colspan="2" align="center"><label><font size="3">露天表演場
		  <td colspan="3" align="center"><label><font size="3">
		  <?php
			$sql = "SELECT * FROM receipt Where receipt_no = 25 AND receipt_serial = '$receipt_serial'";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			
			$time_interval = $row{"time_interval"};
			$time_str = explode("~", $row{"time_interval"});
			$end_time = explode(":", $time_str[1]);
			$if_receipt_continue_print_time = substr($time_interval, 0, 6);
			if($end_time[0] == '23' && $end_time[1] == '59')
			{
				$receipt_serial += 1;
				$sql = "SELECT COUNT(*) AS RESULT FROM receipt Where receipt_no = 25 AND receipt_serial = '$receipt_serial'";
				$result = execute_sql($link, "bbq_database", $sql);
				$row = mysqli_fetch_assoc($result);
				if($row{"RESULT"} == 1)
				{
					$sql = "SELECT * FROM receipt Where receipt_no = 25 AND receipt_serial = '$receipt_serial'";
					$result = execute_sql($link, "bbq_database", $sql);
					$row = mysqli_fetch_assoc($result);
					$time_str = explode("~", $row{"time_interval"});
					$start_time = explode(":", $time_str[0]);
					if($start_time[0] == '00' && $start_time[1] == '00')
					{
						$if_receipt_continue_print_time .= '翌日';
						$if_receipt_continue_print_time .= $time_str[1];
						echo $if_receipt_continue_print_time;
					}
				}else{echo $time_interval;}
				$receipt_serial -= 1;
			}else{echo $time_interval;}
		  ?>
		  </td>
		  </td>
		  <?php
			}
		  ?>
		</tr>
	  <td align="center" colspan="5">
	  <input type="hidden" name="receipt_serial" <?php echo "value=\"$receipt_serial\""?>>
		<div class="form-group">
				<div class="col-sm-offset-0 col-sm-20">
					<button type="button" class="btn btn-primary" onClick="check_data()">修改訂單</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="reset" class="btn btn-default">重填</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<button type="button" class="btn btn-default" onClick='location.href="main.php"'>回上頁</button>
				</div>
			</div>
      </td>
    </form>
  </body>
</html>
<?php
    //釋放資源及關閉資料連接
    mysqli_free_result($result);
    mysqli_close($link);
  }
?>
