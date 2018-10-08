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
		
	//建立資料連接
    $link = create_connection();
	$have_order = true;
	
	//確認租借人
	if(@$_POST{"order_belongs_to"} == NULL)
	{
		$id = $_COOKIE{"id"};
		$order_belongs_to = true;
		//確認是否有訂單
		$sql = "SELECT COUNT(*) AS RESULT FROM receipt WHERE id = '$id'";
		$result = execute_sql($link, "bbq_database", $sql);
		$row = mysqli_fetch_assoc($result);
		if($row{"RESULT"} < 1)
		{
			$have_order = false;
		}
	}
	else{
		$id = $_POST{"order_belongs_to"};
		$order_belongs_to = true;
		$sql = "SELECT COUNT(*) AS RESULT FROM receipt WHERE id = '$id'";
		$result = execute_sql($link, "bbq_database", $sql);
		$row = mysqli_fetch_assoc($result);
		if($row{"RESULT"} < 1)
		{
			$order_belongs_to = false;
		}
	}
	
	
?>
<!doctype html>
<html>
  <head>
    <title>變更訂單</title>
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
		margin-top: 50px;
		margin-bottom: 100px;
		margin-right: 200px;
		margin-left: 200px;
	}
	</style>
	<?php
	if($order_belongs_to == true && $have_order == true)
	{
	?>
    <p align="center" ><b><font size="8">變更訂單</font></b></p>
    <form name="dataForm" method="post" action="modify_order.php" >
      <table border="3" align="center" class = "table table-striped" bordercolor="rhba(255,255,255,0)">
        <tr> 
          <td colspan="2" bgcolor="#6666FF" align="center"><label>
            <font size="3" color="#FFFFFF">
			<?php
			    //執行 SELECT 陳述式取得使用者資料
				$sql = "SELECT * FROM user Where id = '$id'";
				$result = execute_sql($link, "bbq_database", $sql);
				$row = mysqli_fetch_assoc($result);
				echo $row{"name"};	
			?>
			的訂單
			</font>
          </td>
        </tr>
		<?php
			$sql = "SELECT * FROM receipt AS t WHERE id = '$id' GROUP BY id, receipt_no, receipt_serial, use_date, time_interval, accept HAVING receipt_no = (SELECT MAX(receipt_no) FROM receipt WHERE receipt_serial = t.receipt_serial) ORDER BY receipt_serial";
			$result = execute_sql($link, "bbq_database", $sql);
			
			$receipt_serial_temp = -1;
			$time_interval_true_false = false;
			while($row = mysqli_fetch_assoc($result))
			{
				$time_str = explode("~", $row{"time_interval"});
				if($row{"receipt_serial"} - $receipt_serial_temp == 1 && $time_interval_true_false == true && $time_str[0] == '00:00')
				{
					$receipt_serial_temp += 1;
					$link1 = create_connection();
					$sql = "SELECT COUNT(*) AS RESULT FROM receipt Where id = '$id' AND receipt_serial = '$receipt_serial_temp'";
					$result1 = execute_sql($link1, "bbq_database", $sql);
					$row1 = mysqli_fetch_assoc($result1);
					if($row1{"RESULT"} > 1)
					{
						echo '<tr><td colspan="1" align="center"><label><font size="3" color="#000000">';
						echo $row{"receipt_serial"};
						echo '號訂單</font></td><td colspan="1" align="center"><label><font size="3" color="#000000"><button name="post_value" value="';
						echo $row{"receipt_serial"};
						if($row{"accept"} == 0)
							echo '" type="submit" class="btn btn-danger">修改</button></td></tr>';
						else
							echo '" type="button" class="btn btn-danger">不可修改</button></td></tr>';
					}
					$receipt_serial_temp -= 1;
					mysqli_free_result($result1);
					mysqli_close($link1);
				}else{
					echo '<tr><td colspan="1" align="center"><label><font size="3" color="#000000">';
					echo $row{"receipt_serial"};
					echo '號訂單</font></td><td colspan="1" align="center"><label><font size="3" color="#000000"><button name="post_value" value="';
					echo $row{"receipt_serial"};
					if($row{"accept"} == 0)
							echo '" type="submit" class="btn btn-danger">修改</button></td></tr>';
						else
							echo '" type="button" class="btn btn-danger">不可修改</button></td></tr>';
				}
				if($time_str[1] == '23:59')
					$time_interval_true_false = true;
				else
					$time_interval_true_false = false;
				$receipt_serial_temp = $row{"receipt_serial"};
			}
			echo $row{"name"};	
		?>
		
		 
        <tr bgcolor="#99FF99"> 
          <td bgcolor="#99FF99" colspan="2" align="CENTER">
			<div class="form-group">
				<div class="col-sm-offset-0 col-sm-20">
					<?php
						$id = $_COOKIE{"id"};
						$sql = "SELECT COUNT(*) AS RESULT FROM admin Where id = '$id'";
						$result = execute_sql($link, "bbq_database", $sql);
						$row = mysqli_fetch_assoc($result);
						if($row{"RESULT"} == 1)
							echo '<button type="button" class="btn btn-default" onClick=\'location.href="update_whom.php"\'>回上頁</button>';
						else
							echo '<button type="button" class="btn btn-default" onClick=\'location.href="main.php"\'>回上頁</button>';
					?>
				</div>
			</div>
          </td>
        </tr>
      </table>
    </form>
	<?php
	}else{  
	?>
<table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
		<tr class='primary'> 
			<td colspan='2' align='center'> 
				<label><font size='4'>查無此租借人訂單或無此租借人</font></label>
			</td>
		</tr>
		<td colspan='2' align="center"> 
			<div class="form-group">
				<div class="col-sm-offset-0 col-sm-0">
					<br>
					<?php
						$id = $_COOKIE{"id"};
						$sql = "SELECT COUNT(*) AS RESULT FROM admin Where id = '$id'";
						$result = execute_sql($link, "bbq_database", $sql);
						$row = mysqli_fetch_assoc($result);
						if($row{"RESULT"} == 1)
							echo '<button type="button" class="btn btn-primary" onClick=\'location.href="update_whom.php"\'>回確認ID頁面</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
						echo '<button type="button" class="btn btn-default" onClick=\'location.href="main.php"\'>回主頁</button>';
					?>
				</div>
			</div>
          </td>
     </table>
  </body>
</html>
<?php
    //釋放資源及關閉資料連接
		mysqli_free_result($result);
		mysqli_close($link);
	}
  }
 ?>