<?php
  //檢查 cookie 中的 passed 變數是否等於 TRUE
  $passed = $_COOKIE["passed"];

  /*  如果 cookie 中的 passed 變數不等於 TRUE
      表示尚未登入網站，將使用者導向首頁 index.htm   */
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
    <title>租借訂單管理</title>
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
        margin-right: 200px;
        margin-left: 200px;
    }
    </style>
    <p align="center" ><b><font size="8">租借訂單管理</font></b></p>
    <table border="3" align="center" class = "table table-striped" bordercolor="rhba(255,255,255,0)">
    <?php
        // check user is rent_person or not
        $sql = "SELECT COUNT(*) AS COUNT_RESULT FROM rent_person Where id = '".$id."'";
        $result = execute_sql($link, "bbq_database", $sql);
        $rent = mysqli_fetch_array($result);

        // rent_person
        if($rent["COUNT_RESULT"] == 1)
        {
            #get user order
            $sql = "SELECT * FROM receipt Where id = '".$id."' ORDER BY receipt_serial";
            $result = execute_sql($link, "bbq_database", $sql);
            $order = array();
            while($tmp = mysqli_fetch_array($result))
                array_push($order, $tmp);

            // get receipt serial
            $sql = "SELECT DISTINCT receipt_serial FROM receipt Where id = '".$id."' ORDER BY receipt_serial";
            $result = execute_sql($link, "bbq_database", $sql);
            $serial = array();
            while($tmp = mysqli_fetch_array($result))
                array_push($serial, $tmp);
            if(COUNT($serial) == 0) {
                echo "<tr bgcolor='#99FF99'>
                      <td colspan='3' align='center'><label><font size='5' color='FF0000'>尚無租借訂單</font></td>
                      </tr>";
            }else{
                $OVER = array();    #flag for if it's over day(0/1)
                # print out with receipt_serial
                foreach ($serial as $key) {
                    $print = "";
                    $print .= '<tr>
                          <td colspan="3" bgcolor="#6666FF" align="center"><label>
                          <font size="3" color="#FFFFFF">';

                    // get receipt serial
                    $print .= '訂單編號 '.$key["receipt_serial"].'<br>';
                    $print .= '</font></td></tr>';

                    $print .= '<tr bgcolor="#99FF99">
                          <td align="center"><font size="3">租借明細</font></td>
                          <td align="center"><font size="3">租借價格</font></td>
                          <td align="center"><font size="3">目前狀態</font></td>
                          </tr>';

                    //------

                    $print .= '<tr>
                          <td colspan="1" align="center">
                          <label>
                          <font size="3" color="#000000">';

                    // count order
                    $bbq_cnt=0; $camp_cnt=0; $show_cnt=0;
                    foreach ($order as $i) {
                        if($i["receipt_serial"] == $key["receipt_serial"])
                        {
                            if($i{"receipt_no"} >=1 && $i{"receipt_no"} <= 12)
                                $bbq_cnt++;
                            else if($i{"receipt_no"} >= 13 && $i{"receipt_no"} <= 24)
                                $camp_cnt++;
                            else
                                $show_cnt++;
                        }
                    }

                    # get use date
                    $sql = "SELECT DISTINCT use_date FROM receipt Where receipt_serial = ".$key["receipt_serial"];
                    $result = execute_sql($link, "bbq_database", $sql);
                    $date = mysqli_fetch_array($result);
                    $print .= $date{"use_date"}.'<br>';
                    # get order time and count
                    if($bbq_cnt)
                    {
                        $sql = "SELECT DISTINCT time_interval FROM receipt R Where R.receipt_serial = ".$key["receipt_serial"]." AND receipt_no>=1 AND receipt_no<=12";
                        $result = execute_sql($link, "bbq_database", $sql);
                        $time = mysqli_fetch_array($result);
                        $print .= '烤肉台 '.$time["time_interval"].' 共 '.$bbq_cnt.' 台<br>';
                    }
                    if($camp_cnt)
                    {
                        $print .= '露營區 12:30~翌日11:30 共 '.$camp_cnt.' 區<br>';
                    }

                    $totalHR = 0; #count how many hours for show order
                    $diffMIN = 0;
                    $OVER[$key{"receipt_serial"}] = 0;
                    $post_show_time = "";
                    if($show_cnt)
                    {
                        $sql = "SELECT DISTINCT time_interval FROM receipt R Where R.receipt_serial = ".$key["receipt_serial"]." AND receipt_no=25";
                        $result = execute_sql($link, "bbq_database", $sql);
                        $time = mysqli_fetch_array($result);
                        //$print .= 'debug '.$time{"time_interval"}.'<br>';
                        $print .= "露天表演場 ";

                        # checking if order is over next day
                        $today = explode("~", $time["time_interval"]);
                        $begin_time = explode(":", $today[0]);
                        $end_time = explode(":", $today[1]);

                        # calc today minate for money
                        $begin_min = (int)$begin_time[0] * 60 + (int)$begin_time[1];
                        $end_min = (int)$end_time[0] * 60 + (int)$end_time[1];
                        $diffMIN = $end_min - $begin_min;
                        //$print .= $diffMIN.'<br>';
                        if($end_time[0] == '23' && $end_time[1] == '59')
                        {
                            $key["receipt_serial"] += 1;
                            $sql = "SELECT COUNT(*) AS RESULT FROM receipt Where receipt_no = 25 AND receipt_serial = ".$key["receipt_serial"]." AND id = '".$id."'";
                            $result = execute_sql($link, "bbq_database", $sql);
                            $over_day = mysqli_fetch_array($result);
                            if($over_day["RESULT"] == 1)
                            {
                                $sql = "SELECT * FROM receipt Where receipt_no = 25 AND receipt_serial = ".$key["receipt_serial"];
                                $result = execute_sql($link, "bbq_database", $sql);
                                $time = mysqli_fetch_array($result);
                                $next_day = explode("~", $time["time_interval"]);
                                $next_day_begin_time = explode(":", $next_day[0]);
                                $next_day_end_time = explode(":", $next_day[1]);

                                if($next_day_begin_time[0] == '00' && $next_day_begin_time[1] == '00')
                                {

                                    $OVER[$key["receipt_serial"]-1] = 1;

                                    // calc next day minate for money
                                    $begin_min = (int)$next_day_begin_time[0] * 60 + (int)$next_day_begin_time[1];
                                    $end_min = (int)$next_day_end_time[0] * 60 + (int)$next_day_end_time[1];
                                    $diffMIN += ($end_min - $begin_min);

                                    $print .= $today[0].'~翌日'.$next_day[1];
                                    $post_show_time = $today[0].'~翌日'.$next_day[1];
                                }
                                else
                                {
                                    $print .= $today[0].'~'.$today[1];
                                    $post_show_time = $today[0].'~'.$today[1];
                                }
                            }
                            else
                            {
                                $print .= $time["time_interval"];
                                $post_show_time = $time["time_interval"];
                            }
                            $key["receipt_serial"] -= 1;
                        }
                        # if it's over day and last day already print it, go pass!
                        else if($begin_time[0] == '00' && $begin_time[1] == '00')
                        {
                            $key["receipt_serial"] -= 1;
                            $sql = "SELECT COUNT(*) AS RESULT FROM receipt Where receipt_no = 25 AND receipt_serial = ".$key["receipt_serial"]." AND id = '".$id."'";
                            $result = execute_sql($link, "bbq_database", $sql);
                            $over_day = mysqli_fetch_assoc($result);
                            if($over_day{"RESULT"} == 1)
                            {
                                $sql = "SELECT * FROM receipt Where receipt_no = 25 AND receipt_serial = ".$key["receipt_serial"];
                                $result = execute_sql($link, "bbq_database", $sql);
                                $time1 = mysqli_fetch_array($result);
                                $last_day = explode("~", $time1["time_interval"]);
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
                                $print .= $time["time_interval"];
                                $post_show_time = $time["time_interval"];
                            }
                            $key["receipt_serial"] += 1;
                        }
                        else
                        {
                            $print .= $time["time_interval"];
                            $post_show_time = $time["time_interval"];
                        }
                        $print .= ' ('.ceil($diffMIN/60.0).'hr)<br>';
                    }
                    echo $print;
                    //echo '<br>#debug'.$bbq_cnt.'  '.$camp_cnt.'   '.$show_cnt.'<br>';
                    echo '</font>
                          </td>
                          <td width="15%" colspan="1" align="middle" valign="center"><label>
                          <font size="3" color="#000000">';

                    # check identification
                    $sql = "SELECT DISTINCT identification FROM rent_person RP, receipt R Where RP.id = R.id AND receipt_serial = ".$key["receipt_serial"];
                    $result = execute_sql($link, "bbq_database", $sql);
                    $idf = mysqli_fetch_array($result);

                    #get order price
                    $total = 0;
                    if($idf["identification"] == 0)
                        $total = ($bbq_cnt+$camp_cnt)*300 + ceil($diffMIN/60.0)*150;
                    else
                        $total = ($bbq_cnt+$camp_cnt)*500 + ceil($diffMIN/60.0)*200;
                    echo '$'.$total.'<br>';

                    echo '</font></td>
                          <td width="40%" colspan="1" align="center" valign="center"><label>
                          <font size="3" color="#000000">';

                    $sql = "SELECT DISTINCT accept FROM receipt Where receipt_serial = ".$key["receipt_serial"];
                    $result = execute_sql($link, "bbq_database", $sql);
                    $accept = mysqli_fetch_array($result);
                    if($accept["accept"] == 0)
                    {
                        echo "<font color='FF0000'>審核中</font>";
                    }
                    else if($accept["accept"] == 1)
                    {
                        echo '<form name="dataForm" method="post" action="DB_payment_action.php" >
                          <input type="hidden" name="serial" value="'.$key["receipt_serial"].'" />
                          <input type="hidden" name="over" value="'.$OVER[$key["receipt_serial"]].'" />
                          <input type="hidden" name="date" value="'.$date{"use_date"}.'" />
                          <input type="hidden" name="bbq_cnt" value="'.$bbq_cnt.'" />
                          <input type="hidden" name="camp_cnt" value="'.$camp_cnt.'" />
                          <input type="hidden" name="show_cnt" value="'.$show_cnt.'" />
                          <input type="hidden" name="show_time" value="'.$post_show_time.'" />
                          <input type="hidden" name="price" value="'.$total.'" />
                          <input type="submit" class="btn btn-danger" value="線上繳費"/>
                          <input type="submit" class="btn btn-danger" value="列印單據"/>
                          </form></font></td>';
                    }
                    else //$accept["accept"] == 2
                    {
                        echo "<font color='0000FF'>已繳費</font>";
                    }
                    echo '</font></td>';
                }
            }
        }
        else    // admin or deal_person
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
                          <td align='center'><font size='3'>目前狀態</font></td>
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
                    if($accept["accept"] == 0)
                    {
                        echo "<font color='FF0000'>待審核</font>";
                    }
                    else if($accept["accept"] == 1)
                    {
                        echo "<font color='#008800'>未繳費</font>";
                    }
                    else //$accept["accept"] == 2
                    {
                        echo "<font color='0000FF'>已繳費</font>";
                    }
                    echo '</font></td>';
                }

            }
        }
    ?>

    <tr bgcolor="#99FF99">
<?php
    if($rent["COUNT_RESULT"] == 1)  //rent_person
        echo '<td colspan="3" align="CENTER">';
    else //admin & deal_person
        echo '<td colspan="4" align="CENTER">';
?>
        <div class="form-group">
          <div class="col-sm-offset-0 col-sm-20">
          <button type="button" class="btn btn-default" onClick='location.href="main.php"'>回上頁</button>
          </div>
        </div>
      </td>
    </tr>
  </table>
  </body>
</html>
<?php
    //釋放資源及關閉資料連接
    mysqli_free_result($result);
    mysqli_close($link);
?>