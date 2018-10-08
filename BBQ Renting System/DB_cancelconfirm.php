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
    <title>取消訂單</title>
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
    <p align="center" ><b><font size="8">取消訂單</font></b></p>
    <form name="dataForm" method="post" action="DB_cancel_order.php" >
      <table border="3" align="center" class = "table table-striped" bordercolor="rhba(255,255,255,0)">

        <?php
            // check user is rent_person or not
            $sql = "SELECT COUNT(*) AS COUNT_RESULT FROM rent_person Where id = '".$id."'";
            $result = execute_sql($link, "bbq_database", $sql);
            $rent = mysqli_fetch_array($result);
            // rent_person
            if($rent["COUNT_RESULT"] == 1)
            {
                echo '
                <tr>
                  <td colspan="2" bgcolor="#6666FF" align="center"><label>
                    <font size="3" color="#FFFFFF">';
                        //執行 SELECT 陳述式取得使用者資料
                        $sql = "SELECT * FROM user Where id = '$id'";
                        $result = execute_sql($link, "bbq_database", $sql);
                        $row = mysqli_fetch_assoc($result);
                        echo $row{"name"};
                echo '的訂單
                    </font>
                  </td>
                </tr>';
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
                                echo '" type="submit" class="btn btn-danger">取消</button></td></tr>';
                            else
                                echo '" type="button" class="btn btn-danger">不可取消</button></td></tr>';
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
                                echo '" type="submit" class="btn btn-danger">取消</button></td></tr>';
                            else
                                echo '" type="button" class="btn btn-danger">不可取消</button></td></tr>';
                    }
                    if($time_str[1] == '23:59')
                        $time_interval_true_false = true;
                    else
                        $time_interval_true_false = false;
                    $receipt_serial_temp = $row{"receipt_serial"};
                }
                echo $row{"name"};
            }
            else
            {
                # get receipt serial
                $sql = "SELECT receipt_serial FROM receipt GROUP BY receipt_serial ORDER BY receipt_serial ASC";
                $result = execute_sql($link, "bbq_database", $sql);
                $serial = array();
                while($tmp = mysqli_fetch_array($result))
                    array_push($serial, $tmp);
                if(COUNT($serial) == 0) {
                    echo "<tr bgcolor='#99FF99'>
                          <td align='center'><label><font size='5' color='FF0000'>尚無租借訂單</font></td>
                          </tr>";
                }else{
                    $OVER = array();    #flag for if it's over day(0/1)
                    foreach ($serial as $key) {
                        // 為了防止 跨日訂單一同輸出, 因此判斷完跨日搜索後再輸出
                        $print = "";
                        $print .= '<tr>
                              <td colspan="4" bgcolor="#6666FF" align="center"><label>
                              <font size="3" color="#FFFFFF">';
                        // get receipt serial
                        $print .= '訂單編號 '.$key{"receipt_serial"}.'<br>';
                        $print .= '</font></td></tr>';

                        $print .= "<tr bgcolor='#99FF99'>
                              <td align='center'><font size='3'>租借人</font></td>
                              <td align='center'><font size='3'>租借明細</font></td>
                              <td align='center'><font size='3'>租借價格</font></td>
                              <td align='center'><font size='3'>我想取消</font></td>
                              </tr>";

                        //------

                        $print .= '<tr>
                              <td colspan="1" align="center">
                              <label>
                              <font size="3" color="#000000">';

                        # get user name
                        $sql = "SELECT DISTINCT U.name, R.id FROM user U, receipt R Where U.id = R.id AND receipt_serial = ".$key{"receipt_serial"};
                        $result = execute_sql($link, "bbq_database", $sql);
                        $user = mysqli_fetch_array($result);
                        $print .= $user{"name"}.'<br>';

                        # check identification
                        $sql = "SELECT DISTINCT identification FROM rent_person RP, receipt R Where RP.id = R.id AND receipt_serial = ".$key{"receipt_serial"};
                        $result = execute_sql($link, "bbq_database", $sql);
                        $idf = mysqli_fetch_array($result);
                        if(COUNT($idf) == 0)
                            $print .= "( system )";
                        else
                            $print .= '( '.($idf{"identification"}==0 ? "校內人士" : "校外人士").' )';
                        $print .= '</font>
                              </td>
                              <td width="40%" colspan="1" align="center"><label>
                              <font size="3" color="#000000">';

                        #get user order
                        $sql = "SELECT * FROM receipt R Where R.receipt_serial = ".$key{"receipt_serial"};
                        $result = execute_sql($link, "bbq_database", $sql);
                        $order = array();
                        while($tmp = mysqli_fetch_array($result))
                            array_push($order, $tmp);
                        $bbq_cnt=0; $camp_cnt=0; $show_cnt=0;
                        foreach ($order as $i) {
                            if($i{"receipt_no"} >=1 && $i{"receipt_no"} <= 12)
                                $bbq_cnt++;
                            else if($i{"receipt_no"} >= 13 && $i{"receipt_no"} <= 24)
                                $camp_cnt++;
                            else
                                $show_cnt++;
                            #echo $i{"receipt_no"};
                        }

                        # get use date
                        $sql = "SELECT DISTINCT use_date FROM receipt R Where R.receipt_serial = ".$key{"receipt_serial"};
                        $result = execute_sql($link, "bbq_database", $sql);
                        $date = mysqli_fetch_array($result);
                        $print .= $date{"use_date"}.'<br>';

                        #get order time and count
                        if($bbq_cnt)
                        {
                            $sql = "SELECT DISTINCT time_interval FROM receipt R Where R.receipt_serial = ".$key{"receipt_serial"}." AND receipt_no>=1 AND receipt_no<=12";
                            $result = execute_sql($link, "bbq_database", $sql);
                            $time = mysqli_fetch_array($result);
                            $print .= '烤肉台 '.$time{"time_interval"}.' 共 '.$bbq_cnt.' 台<br>';
                        }
                        if($camp_cnt)
                        {
                            $print .= '露營區 12:30~翌日11:30 共 '.$camp_cnt.' 區<br>';
                        }

                        $totalHR = 0; #count how many hours for show order
                        $diffMIN = 0; #count how many minute for show order
                        $OVER[$key{"receipt_serial"}] = 0;
                        if($show_cnt)
                        {
                            $sql = "SELECT DISTINCT time_interval FROM receipt R Where R.receipt_serial = ".$key{"receipt_serial"}." AND receipt_no=25";
                            $result = execute_sql($link, "bbq_database", $sql);
                            $time = mysqli_fetch_array($result);
                            //$print .= 'debug '.$time{"time_interval"}.'<br>';
                            $print .= "露天表演場 ";

                            # checking if order is over next day
                            $today = explode("~", $time{"time_interval"});
                            $begin_time = explode(":", $today[0]);
                            $end_time = explode(":", $today[1]);

                            // calc today minate for money
                            $begin_min = (int)$begin_time[0] * 60 + (int)$begin_time[1];
                            $end_min = (int)$end_time[0] * 60 + (int)$end_time[1];
                            $diffMIN = $end_min - $begin_min;
                            //$print .= $diffMIN.'<br>';
                            if($end_time[0] == '23' && $end_time[1] == '59')
                            {
                                $key{"receipt_serial"} += 1;
                                $sql = "SELECT COUNT(*) AS RESULT FROM receipt Where receipt_no = 25 AND receipt_serial = ".$key{"receipt_serial"}." AND id = '".$user{"id"}."'";
                                $result = execute_sql($link, "bbq_database", $sql);
                                $over_day = mysqli_fetch_array($result);
                                if($over_day{"RESULT"} == 1)
                                {
                                    $sql = "SELECT * FROM receipt Where receipt_no = 25 AND receipt_serial = ".$key{"receipt_serial"};
                                    $result = execute_sql($link, "bbq_database", $sql);
                                    $time = mysqli_fetch_array($result);
                                    $next_day = explode("~", $time{"time_interval"});
                                    $next_day_begin_time = explode(":", $next_day[0]);
                                    $next_day_end_time = explode(":", $next_day[1]);

                                    if($next_day_begin_time[0] == '00' && $next_day_begin_time[1] == '00')
                                    {
                                        $OVER[$key{"receipt_serial"}-1] = 1;

                                        // calc next day minate for money
                                        $begin_min = (int)$next_day_begin_time[0] * 60 + (int)$next_day_begin_time[1];
                                        $end_min = (int)$next_day_end_time[0] * 60 + (int)$next_day_end_time[1];
                                        $diffMIN += ($end_min - $begin_min);

                                        $print .= $today[0].'~翌日'.$next_day[1];
                                    }
                                    else
                                        $print .= $today[0].'~'.$today[1];
                                }
                                else
                                {
                                    $print .= $time{"time_interval"};
                                }
                                $key{"receipt_serial"} -= 1;
                            }
                            # if it's over day and last day already print it, go pass!
                            else if($begin_time[0] == '00' && $begin_time[1] == '00')
                            {
                                $key{"receipt_serial"} -= 1;
                                $sql = "SELECT COUNT(*) AS RESULT FROM receipt Where receipt_no = 25 AND receipt_serial = ".$key{"receipt_serial"}." AND id = '".$user{"id"}."'";
                                $result = execute_sql($link, "bbq_database", $sql);
                                $over_day = mysqli_fetch_assoc($result);
                                if($over_day{"RESULT"} == 1)
                                {
                                    $sql = "SELECT * FROM receipt Where receipt_no = 25 AND receipt_serial = ".$key{"receipt_serial"};
                                    $result = execute_sql($link, "bbq_database", $sql);
                                    $time1 = mysqli_fetch_array($result);
                                    $last_day = explode("~", $time1{"time_interval"});
                                    $last_day_end_time = explode(":", $last_day[1]);
                                    if($last_day_end_time[0] == '23' && $last_day_end_time[1] == '59'
                                        && !$bbq_cnt && !$camp_cnt)
                                        continue;
                                    else
                                    {
                                        $print .= $time["time_interval"];
                                        $post_show_time = $time["time_interval"];
                                    }
                                }
                                else
                                {
                                    $print .= $time{"time_interval"};
                                }
                                $key{"receipt_serial"} += 1;
                            }
                            else
                            {
                                $print .= $time{"time_interval"};
                            }
                            $print .= ' ('.ceil($diffMIN/60.0).'hr)<br>';
                        }
                        echo $print;
                        //echo '<br>#debug'.$bbq_cnt.'  '.$camp_cnt.'   '.$show_cnt.'<br>';
                        echo '</font></td>
                              <td width="15%" colspan="1" align="center"><label>
                              <font size="3" color="#000000">';

                        #get order price
                        if(COUNT($idf) == 0)
                            echo " 無料 <br>";
                        else
                        {
                            $total = 0;
                            if($idf{"identification"} == 0)
                                $total = ($bbq_cnt+$camp_cnt)*300 + ceil($diffMIN/60.0)*150;
                            else
                                $total = ($bbq_cnt+$camp_cnt)*500 + ceil($diffMIN/60.0)*200;
                            echo '$'.$total.'<br>';
                        }
                        echo '</font></td>
                              <td width="15%" colspan="1" align="center"><label>
                              <font size="3" color="#000000">';
                        // check if the order is already pay it
                        $sql = "SELECT DISTINCT accept FROM receipt Where receipt_serial = ".$key["receipt_serial"];
                        $result = execute_sql($link, "bbq_database", $sql);
                        $accept = mysqli_fetch_array($result);

                        echo '<button name="post_value" value="'.$key{"receipt_serial"};
                        if($accept["accept"] == 0)
                            echo '" type="submit" class="btn btn-danger">取消</button>';
                        else
                            echo '" type="button" class="btn btn-danger">不可取消</button>';
                        echo '</font></td>';
                    }
                }
            }



        ?>


        <tr bgcolor="#99FF99">
        <?php
            // if rent_person
            if($rent["COUNT_RESULT"] == 1)
                echo '<td bgcolor="#99FF99" colspan="2" align="CENTER">';
            else
                echo '<td bgcolor="#99FF99" colspan="4" align="CENTER">';
        ?>
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-20">
                    <?php
                        $id = $_COOKIE{"id"};
                        $sql = "SELECT COUNT(*) AS RESULT FROM admin Where id = '$id'";
                        $result = execute_sql($link, "bbq_database", $sql);
                        $row = mysqli_fetch_assoc($result);
                        if($row{"RESULT"} == 1)
                            echo '<button type="button" class="btn btn-default" onClick=\'location.href="main.php"\'>回上頁</button>';
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