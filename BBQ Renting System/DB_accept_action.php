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
        header("Content-type: text/html; charset=utf-8");

        //建立資料連接
        $link = create_connection();
        //開始寫入資料庫
        //UPDATE receipt SET accept = 0 WHERE receipt_serial = 1; //recover
        $sql = "UPDATE receipt SET accept = 1 WHERE receipt_serial = ".$serial;
        $result = execute_sql($link, "bbq_database", $sql);
        if($over_day == 1)
        {
            $sql = "UPDATE receipt SET accept = 1 WHERE receipt_serial = ".($serial+1);
            $result = execute_sql($link, "bbq_database", $sql);
        }
?>
        <br>
        <table border='3'  align='center' class = 'table table-striped' bordercolor='rhba(255,255,255,0)'>
            <tr class='primary'>
                <td colspan='2' align='center'>
                    <label><font size='4'><?php echo "訂單編號".$serial;?> 核准完成</font></label>
                </td>
            </tr>
            <td colspan='2' align="center">
                <div class="form-group"> <div class="col-sm-offset-0 col-sm-0">
                    <br>
                    <button type="button" class="btn btn-primary" onClick='location.href="DB_accept.php"'><label><font size='2'>回核准頁面</label></font></button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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