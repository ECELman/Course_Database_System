<?php
  //include_once('DB_site_check.php');
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
    <title>場地狀態查詢</title>
    <meta charset="utf-8">
  </head>

  <body>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <!--引入 CSS 引入 jQuery 引入 jQuery UI-->
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />
  <script type="text/javascript" src="https://code.jquery.com/jquery.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
  <script type="text/javascript">
  $(function(){

  $('#chooseenddate').datepicker({
    minDate: 3,
  });
  $('#choosestartdate').datepicker({
    minDate: 3,
    onSelect: function (min, inst) {
      $('#chooseenddate').datepicker('option', 'minDate', min);
      var max = $('#choosestartdate').datepicker('getDate');
      max.setDate(max.getDate()+1);
      $('#chooseenddate').datepicker('option', 'maxDate', max);
    }
  });
});


$.datepicker.setDefaults({ dateFormat: 'yy-mm-dd' }); //全局設置日期格式

    function check_data()
    {
        if (document.dataForm.startdate.value.length == 0)
        {
          alert("「選擇日期」一定要填寫哦...");
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
        margin-right: 200px;
        margin-left: 200px;
    }
    </style>
    <p align="center" ><b><font size="8">場地狀態查詢</font></b></p>
    <form class="form-inline" name="dataForm" method="post" action="DB_site_search.php" >
        <table border="3"  align="center" class = "table table-striped" bordercolor="rhba(255,255,255,0)">
        <?php
            $sql = "SELECT * FROM user Where id = '$id'";
            $result = execute_sql($link, "bbq_database", $sql);
            $row = mysqli_fetch_assoc($result);
            echo '<tr class="info"><td colspan="4" align="center"><label><font size="3"> 親愛的';
            echo $row{'id'};
            echo '您好，請填寫欲查詢的日期</font></label></td></tr>';
            $init_date = "";
            if (isset($_POST["startdate"]))
                $init_date = $_POST["startdate"];
            echo "<tr class='default'><td colspan='4' align='center'><label><font size='3'>選擇日期 : <input id='choosestartdate' name='startdate' type='text' placeholder='$init_date' readonly='readonly' /><br /></td></tr>";

            // query
            if (isset($_POST["startdate"]))
            {
                $date = $_POST["startdate"];
                $sql = "SELECT receipt_no, time_interval FROM receipt WHERE use_date='$date' ORDER BY receipt_no ASC";
                $result = execute_sql($link, "bbq_database", $sql);
                $place = array();
                while($tmp = mysqli_fetch_array($result))
                    array_push($place, $tmp);

                $bbq = array();
                $camp_cnt=12;
                $show = array();
                if(COUNT($place) != 0)
                {
                    foreach ($place as $key) {
                        if($key["receipt_no"] >=1 && $key["receipt_no"] <= 12)
                            array_push($bbq, $key);
                        else if($key["receipt_no"] >= 13 && $key["receipt_no"] <= 24)
                            $camp_cnt--;
                        else
                            array_push($show, $key);
                    }
                }

                // check bbq every remaining interval
                $bbq_interval_1=12; $bbq_interval_2=12; $bbq_interval_3=12;
                if(COUNT($bbq) != 0) {
                    foreach ($bbq as $key) {
                        if($key["time_interval"] == "08:00~11:00")
                            $bbq_interval_1--;
                        else if($key["time_interval"] == "11:00~14:00")
                            $bbq_interval_2--;
                        else
                            $bbq_interval_3--;
                    }
                }
                echo '<tr class="default"><td colspan="1" rowspan="3" align="center"><label><font size="3"><br><br>烤肉台 :</td>
                    <td colspan="3" align="center"><label><font size="3">08:00~11:00 &nbsp;&nbsp;尚餘 '.$bbq_interval_1.' 台</td></tr>
                    <tr><td colspan="3" align="center"><label><font size="3">11:00~14:00 &nbsp;&nbsp;尚餘 '.$bbq_interval_2.' 台</td></tr>
                    <tr><td colspan="3" align="center"><label><font size="3">18:00~21:00 &nbsp;&nbsp;尚餘 '.$bbq_interval_3.' 台</td></tr>';
                echo '<tr class="default"><td colspan="1" align="center"><label><font size="3">營位 :</td>
                    <td colspan="3" align="center"><label><font size="3">12:30~翌日11:30 &nbsp;&nbsp;尚餘 '.$camp_cnt.' 區</td></tr>';


                # search show already loan interval
                $loan_interval = array();
                #initial to 1(if it can loan)
                for($i=0; $i<1440; $i++)    //00:00~23:59
                    $loan_interval[$i] = 1;
                foreach ($show as $key) {
                    //echo $key["time_interval"].'<br>';
                    $interval = explode("~", $key["time_interval"]);
                    $time = explode(":", $interval[0]);
                    $begin_MIN = (int)$time[0] * 60 + (int)$time[1];
                    $time = explode(":", $interval[1]);
                    $end_MIN = (int)$time[0] * 60 + (int)$time[1];
                    //echo $begin_MIN.' '.$end_MIN.'<br>';
                    for($i=$begin_MIN; $i<=$end_MIN; $i++)
                        $loan_interval[$i] = 0;
                }

                // search remaining interval
                $begin=0; $end=0;
                $show_time = array();
                for($i=0; $i<1440; $i++)
                    if($loan_interval[$i] == 1)
                    {
                        $begin = $i;
                        for(; $i<1440; $i++)
                            if($loan_interval[$i] == 0)
                                break;
                        $end = $i-1;

                        # $begin(MIN) ~ $end(MIN) can loan!!!
                        # if interval is over 1hr, then it can loan to others
                        if($end-$begin > 60)
                        {
                            //echo $begin.' '.$end.'<br>';
                            $time_str = "";
                            $HR = (int)($begin / 60);
                            $MIN = $begin - $HR*60;
                            $time_str .= ($HR<10 ? '0'.$HR : $HR).':'.($MIN<10 ? '0'.$MIN : $MIN).'~';
                            $HR = (int)($end / 60);
                            $MIN = $end - $HR*60;
                            $time_str .= ($HR<10 ? '0'.$HR : $HR).':'.($MIN<10 ? '0'.$MIN : $MIN);
                            array_push($show_time, $time_str);
                            //echo $time_str.'<br>';
                        }
                    }

                echo '<tr class="default"><td colspan="1" align="center"><label><font size="3">露天表演場 :</td>
                    <td colspan="3" align="center"><label><font size="3">可租借時段<br>';
                foreach ($show_time as $key) {
                    echo $key.'<br>';
                }
                echo '</td></tr>';
            }
        ?>
      <td align="center" colspan="4">
        <div class="form-group">
            <div id="submit" class="col-sm-offset-0 col-sm-20">
                <button type="button" class="btn btn-primary" onClick="check_data()">送出查詢</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
