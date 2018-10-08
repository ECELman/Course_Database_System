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
        $serial = $_POST["serial"];
        $over_day = $_POST["over"];
        $date = $_POST["date"];
        $bbq_cnt = $_POST["bbq_cnt"];
        $camp_cnt = $_POST["camp_cnt"];
        $show_cnt = $_POST["show_cnt"];
        $show_time = $_POST["show_time"];
        $price = $_POST["price"];
        header("Content-type: text/html; charset=utf-8");

        //建立資料連接
        $link = create_connection();
        //開始寫入資料庫
        //UPDATE receipt SET accept = 0 WHERE receipt_serial = 1; //recover
        $sql = "UPDATE receipt SET accept = 2 WHERE receipt_serial = ".$serial;
        $result = execute_sql($link, "bbq_database", $sql);
        if($over_day == 1)
        {
            $sql = "UPDATE receipt SET accept = 2 WHERE receipt_serial = ".($serial+1);
            $result = execute_sql($link, "bbq_database", $sql);
        }
?>
        <br>
        <table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
            <tr class='primary'>
                <td colspan='2' align='center'>
                    <label><font size='4' color='0000FF'><?php echo "訂單編號".$serial;?> 繳費明細</font></label>
                </td>
            </tr>
<?php
            echo '<tr bgcolor="#FFFFBB"><td colspan="2" align="center"><font size="3">';
            # get user name
            $sql = "SELECT DISTINCT U.name, R.id FROM user U, receipt R Where U.id = R.id AND receipt_serial = ".$serial;
            $result = execute_sql($link, "bbq_database", $sql);
            $user = mysqli_fetch_array($result);
            echo '<label>租借人: '.$user["name"].'<br><br>';
            echo '使用日期: '.$date.'<br>';
            if($bbq_cnt)
            {
                $sql = "SELECT DISTINCT time_interval FROM receipt R Where R.receipt_serial = ".$serial." AND receipt_no>=1 AND receipt_no<=12";
                $result = execute_sql($link, "bbq_database", $sql);
                $time = mysqli_fetch_array($result);
                echo '烤肉台 '.$time["time_interval"].' 共 '.$bbq_cnt.' 台<br>';
            }
            if($camp_cnt)
            {
                echo '露營區 12:30~翌日11:30 共 '.$camp_cnt.' 區<br>';
            }
            if($show_cnt)
            {
                echo '露天表演場 '.$show_time.'<br>';
            }
            echo "總金額: $".$price.'<br>';
            echo '</font></td></tr>';
?>
            <td colspan='2' align="center">
                <div class="form-group"> <div class="col-sm-offset-0 col-sm-0">
                    <br>
                    <button type="button" class="btn btn-primary" onClick='location.href="DB_payment.php"'><label><font size='2'>回繳費頁面</label></font></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-primary" onClick='location.href="main.php"'><label><font size='2'>回主頁</label></font></button>
                </div></div>
            </td>
         </table>
  </body>
<?php
        header('refresh:5;url="main.php"');
?>


<?php
    //釋放資源及關閉資料連接
    mysqli_free_result($result);
    mysqli_close($link);
    }
?>