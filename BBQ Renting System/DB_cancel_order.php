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
        $serial = $_POST["post_value"];

        header("Content-type: text/html; charset=utf-8");

        //建立資料連接
        $link = create_connection();
        //開始寫入資料庫

        $time_end = array();
        $time_end[0]="0";
		$time_end[1]="0";
        $sql = "SELECT DISTINCT time_interval FROM `receipt` WHERE `receipt_serial`=".$serial." AND `receipt_no`=25";
        $result = execute_sql($link, "bbq_database", $sql);
        $time = mysqli_fetch_array($result);
        $time_end = explode("~", $time["time_interval"]);

        // delete now order
        $sql = "DELETE FROM `receipt` WHERE `receipt_serial`=".$serial;
        $result = execute_sql($link, "bbq_database", $sql);

        // check next order is over day order
        if(@$time_end[1] == "23:59")
        {
            $serial += 1;
            $sql = "SELECT * FROM `receipt` WHERE `receipt_serial`=".$serial;
            $result = execute_sql($link, "bbq_database", $sql);
            $next_order = array();
            while($tmp = mysqli_fetch_array($result))
                array_push($next_order, $tmp);

            // if next order just one && receipt_no=25 && time_begin=00:00, delete it!
            if(COUNT($next_order)==1)
            {
                $time = $next_order[0]["time_interval"];
                $time_begin = explode("~", $time);
                if($next_order[0]["receipt_no"]==25 && $time_begin[0]=="00:00")
                {
                    $sql = "DELETE FROM `receipt` WHERE `receipt_serial`=".$serial;
                    $result = execute_sql($link, "bbq_database", $sql);
                }
            }
            $serial -= 1;
        }
?>
        <br>
        <table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
            <tr class='primary'>
                <td colspan='2' align='center'>
                    <label><font size='4'><?php echo "訂單編號".$serial;?> 取消完成</font></label>
                </td>
            </tr>
            <td colspan='2' align="center">
                <div class="form-group"> <div class="col-sm-offset-0 col-sm-0">
                    <br>
                    <button type="button" class="btn btn-primary" onClick='location.href="DB_cancelconfirm.php"'><label><font size='2'>回取消頁面</label></font></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <button type="button" class="btn btn-primary" onClick='location.href="main.php"'><label><font size='2'>回主頁</label></font></button>
                </div></div>
              </td>
         </table>
  </body>
<?php
        header('refresh:5;url="main.php"');
?>


<?php
    //釋放 $result 佔用的記憶體
    @mysqli_free_result($result);

    //關閉資料連接
    mysqli_close($link);
    }
?>