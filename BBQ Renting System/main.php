<?php
  //檢查 cookie 中的 passed 變數是否等於 TRUE
  $passed = $_COOKIE["passed"];

  /*  如果 cookie 中的 passed 變數不等於 TRUE
      表示尚未登入網站，將使用者導向首頁 index.htm	*/
  if ($passed != "TRUE")
  {
    header("location:index.htm");
    exit();
  }

  $id = $_COOKIE["id"];
  require_once("dbtools.inc.php");
  //建立資料連接
  $link = create_connection();
?>
<!doctype html>
<html>
  <head>
    <title>我要訂位</title>
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
	}
	</style>
	<p align="center" ><b><font size="8">會員專區</font></b></p>
    <p align="center">
	<?php
	    $ifadmin = false;
		$ifdeal_person = false;
		$sql = "SELECT COUNT(*) AS COUNT_RESULT FROM admin Where id = '$id'";
		$result = execute_sql($link, "bbq_database", $sql);
		$row = mysqli_fetch_assoc($result);
		if($row{"COUNT_RESULT"} == 1)
		{
			echo '<a href="DB_join.php"><button type="button" class="btn btn-primary"><font size="5">新增帳號</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	              <a href="DB_select_person.php"><button type="button" class="btn btn-primary"><font size="5">資料修改</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	              <a href="DB_delete.php"><button type="button" class="btn btn-primary"><font size="5">刪除帳號</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="DB_site_search.php"><button type="button" class="btn btn-primary"><font size="5">場地狀態查詢</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	              <a href="order.php"><button type="button" class="btn btn-primary"><font size="5">開始租借</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	              <a href="update_whom.php"><button type="button" class="btn btn-primary"><font size="5">修改租借訂單</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	              <a href="DB_cancelconfirm.php"><button type="button" class="btn btn-primary"><font size="5">取消租借訂單</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="DB_payment.php"><button type="button" class="btn btn-primary"><font size="5">管理租借訂單</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		    $ifadmin = true;
		}
		$sql = "SELECT COUNT(*) AS COUNT_RESULT FROM deal_person Where id = '$id'";
		$result = execute_sql($link, "bbq_database", $sql);
		$row = mysqli_fetch_assoc($result);
		if($row{"COUNT_RESULT"} == 1)
		{
			echo '<a href="DB_accept.php"><button type="button" class="btn btn-primary"><font size="5">核准租借訂單</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="DB_payment.php"><button type="button" class="btn btn-primary"><font size="5">管理租借訂單</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		    $ifdeal_person = true;
		}
		$sql = "SELECT COUNT(*) AS COUNT_RESULT FROM rent_person Where id = '$id'";
		$result = execute_sql($link, "bbq_database", $sql);
		$row = mysqli_fetch_assoc($result);
		if($row{"COUNT_RESULT"} == 1 && $ifadmin != true)
		{
			if($ifdeal_person != true)
	           echo '<a href="modify.php"><button type="button" class="btn btn-primary"><font size="5">資料修改</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="DB_site_search.php"><button type="button" class="btn btn-primary"><font size="5">場地狀態查詢</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="order.php"><button type="button" class="btn btn-primary"><font size="5">開始租借</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="update_order.php"><button type="button" class="btn btn-primary"><font size="5">修改租借訂單</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="DB_cancelconfirm.php"><button type="button" class="btn btn-primary"><font size="5">取消租借訂單</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="DB_payment.php"><button type="button" class="btn btn-primary"><font size="5">管理租借訂單</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}
	?>
	  <a href="logout.php"><button type="button" class="btn btn-danger"><font size="5">登出網站</font></button></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    </p>
  </body>
</html>