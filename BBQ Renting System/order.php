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
    <title>會員訂位</title>
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
		if (!((document.dataForm.show_start_hour.value == "請選擇" && document.dataForm.show_start_minute.value == "請選擇" && document.dataForm.show_end_hour.value == "請選擇" && document.dataForm.show_end_minute.value == "請選擇") || (document.dataForm.show_start_hour.value != "請選擇" && document.dataForm.show_start_minute.value != "請選擇" && document.dataForm.show_end_hour.value != "請選擇" && document.dataForm.show_end_minute.value != "請選擇" && document.dataForm.enddate.value.length != 0)))
        {
          alert("「露天表演場」選擇時間還沒填完哦...");
          return false;
        }
		if ((document.dataForm.show_start_hour.value == document.dataForm.show_end_hour.value) && (document.dataForm.show_start_minute.value == document.dataForm.show_end_minute.value && document.dataForm.startdate.value == document.dataForm.enddate.value) && document.dataForm.show_start_hour.value != "請選擇")
        {
          alert("露天表演場選擇時間填寫不正確哦...");
          return false;
        }
		if ((document.dataForm.show_start_hour.value > document.dataForm.show_end_hour.value || (document.dataForm.show_start_hour.value == document.dataForm.show_end_hour.value && document.dataForm.show_start_minute.value > document.dataForm.show_end_minute.value)) && document.dataForm.startdate.value == document.dataForm.enddate.value)
        {
          alert("如果需要隔夜借場地請正確填寫結束日期哦...");
          return false;
        }
		if (((document.dataForm.show_start_hour.value < document.dataForm.show_end_hour.value) || (document.dataForm.show_start_hour.value == document.dataForm.show_end_hour.value && document.dataForm.show_start_minute.value < document.dataForm.show_end_minute.value)) && document.dataForm.startdate.value != document.dataForm.enddate.value)
        {
          alert("露天表演場不可借超過24小時...");
          return false;
        }
		if (document.dataForm.show_end_hour.value == 0 && document.dataForm.show_end_minute.value == 0)
        {
          alert("結束時間不可為午夜12點整(00:00)...");
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
    <p align="center" ><b><font size="8">會員訂位</font></b></p>
    <form class="form-inline" name="dataForm" method="post" action="checkorder.php" >
      <table border="3"  align="center" class = "table table-striped" bordercolor="rhba(255,255,255,0)">
	  <?php
		$sql = "SELECT * FROM user Where id = '$id'";
		$result = execute_sql($link, "bbq_database", $sql);
		$row = mysqli_fetch_assoc($result);
		?>
		<tr class='info'><td colspan='5' align='center'><label><font size='3'> 親愛的
		<?php
		echo $row{'id'};
		?>
		您好，請填寫您的訂單</font></label></td></tr>
		<tr class="default"><td colspan="5" align="center"><label><font size="3">選擇日期 : <input id="choosestartdate" name="startdate" type="text" readonly="readonly" /><br /></td></tr>
		<tr class="default"><td colspan="1" rowspan="3" align="center"><label><font size="3"><br><br>烤肉台 :
		  <select name="bbq_amount">
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
          </select>台<br/></td>
		  
		  <td colspan="4" align="center"><label><font size="3">
		  <input type="radio" name="time_interval" value="08:00~11:00" checked="checked">08:00~11:00</td></tr>
		  <tr><td colspan="4" align="center"><label><font size="3">
		  <input type="radio" name="time_interval" value="11:00~14:00">11:00~14:00</td></tr>
		  <tr><td colspan="4" align="center"><label><font size="3">
		  <input type="radio" name="time_interval" value="18:00~21:00">18:00~21:00
		  </td>
		</tr>
		
		<tr class="default"><td colspan="1" align="center"><label><font size="3">營位 :
		  <select name="camp_amount">
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
          </select>區<br/></td>
		  <td colspan="4" align="center"><label><font size="3">12:30~翌日11:30</td>
		</tr>
		
		<tr class="default"><td colspan="1" align="center"><label><font size="3">露天表演場
		  <td colspan="4" align="center"><label><font size="3">
		  <select name="show_start_hour">
		  <option>請選擇</option>
		  <option>00</option>
          <option>01</option>
          <option>02</option>
          <option>03</option>
		  <option>04</option>
          <option>05</option>
          <option>06</option>
		  <option>07</option>
          <option>08</option>
          <option>09</option>
		  <option>10</option>
          <option>11</option>
          <option>12</option>
		  <option>13</option>
		  <option>14</option>
		  <option>15</option>
		  <option>16</option>
		  <option>17</option>
		  <option>18</option>
		  <option>19</option>
		  <option>20</option>
		  <option>21</option>
		  <option>22</option>
		  <option>23</option>
          </select>:
		  <select name="show_start_minute">
		  <option>請選擇</option>
		  <option>00</option>
          <option>01</option>
          <option>02</option>
          <option>03</option>
		  <option>04</option>
          <option>05</option>
          <option>06</option>
		  <option>07</option>
          <option>08</option>
          <option>09</option>
		  <option>10</option>
          <option>11</option>
          <option>12</option>
		  <option>13</option>
		  <option>14</option>
		  <option>15</option>
		  <option>16</option>
		  <option>17</option>
		  <option>18</option>
		  <option>19</option>
		  <option>20</option>
		  <option>21</option>
		  <option>22</option>
		  <option>23</option>
		  <option>24</option>
		  <option>25</option>
		  <option>26</option>
		  <option>27</option>
		  <option>28</option>
		  <option>29</option>
		  <option>30</option>
		  <option>31</option>
		  <option>32</option>
		  <option>33</option>
		  <option>34</option>
		  <option>35</option>
		  <option>36</option>
		  <option>37</option>
		  <option>38</option>
		  <option>39</option>
		  <option>40</option>
		  <option>41</option>
		  <option>42</option>
		  <option>43</option>
		  <option>44</option>
		  <option>45</option>
		  <option>46</option>
		  <option>47</option>
		  <option>48</option>
		  <option>49</option>
		  <option>50</option>
		  <option>51</option>
		  <option>52</option>
		  <option>53</option>
		  <option>54</option>
		  <option>55</option>
		  <option>56</option>
		  <option>57</option>
		  <option>58</option>
		  <option>59</option>
          </select>~
		  <select name="show_end_hour">
		  <option>請選擇</option>
		  <option>00</option>
          <option>01</option>
          <option>02</option>
          <option>03</option>
		  <option>04</option>
          <option>05</option>
          <option>06</option>
		  <option>07</option>
          <option>08</option>
          <option>09</option>
		  <option>10</option>
          <option>11</option>
          <option>12</option>
		  <option>13</option>
		  <option>14</option>
		  <option>15</option>
		  <option>16</option>
		  <option>17</option>
		  <option>18</option>
		  <option>19</option>
		  <option>20</option>
		  <option>21</option>
		  <option>22</option>
		  <option>23</option>
          </select>:
		  <select name="show_end_minute">
		  <option>請選擇</option>
		  <option>00</option>
          <option>01</option>
          <option>02</option>
          <option>03</option>
		  <option>04</option>
          <option>05</option>
          <option>06</option>
		  <option>07</option>
          <option>08</option>
          <option>09</option>
		  <option>10</option>
          <option>11</option>
          <option>12</option>
		  <option>13</option>
		  <option>14</option>
		  <option>15</option>
		  <option>16</option>
		  <option>17</option>
		  <option>18</option>
		  <option>19</option>
		  <option>20</option>
		  <option>21</option>
		  <option>22</option>
		  <option>23</option>
		  <option>24</option>
		  <option>25</option>
		  <option>26</option>
		  <option>27</option>
		  <option>28</option>
		  <option>29</option>
		  <option>30</option>
		  <option>31</option>
		  <option>32</option>
		  <option>33</option>
		  <option>34</option>
		  <option>35</option>
		  <option>36</option>
		  <option>37</option>
		  <option>38</option>
		  <option>39</option>
		  <option>40</option>
		  <option>41</option>
		  <option>42</option>
		  <option>43</option>
		  <option>44</option>
		  <option>45</option>
		  <option>46</option>
		  <option>47</option>
		  <option>48</option>
		  <option>49</option>
		  <option>50</option>
		  <option>51</option>
		  <option>52</option>
		  <option>53</option>
		  <option>54</option>
		  <option>55</option>
		  <option>56</option>
		  <option>57</option>
		  <option>58</option>
		  <option>59</option>
          </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		  <font size="3">結束日期 : <input id="chooseenddate" name="enddate" type="text" readonly="readonly" />
		  </td>
		  </td>
		  <?php
			$sql = "SELECT COUNT(*) AS COUNT_RESULT FROM admin Where id = '$id'";
			$result = execute_sql($link, "bbq_database", $sql);
			$row = mysqli_fetch_assoc($result);
			if($row{"COUNT_RESULT"} == 1)
				echo '<tr class="default"><td colspan="5" align="center"><label><font size="3">訂單ID : <input name="order_belongs_to" type="text"><br /></td></tr>'
		  ?>
		</tr>
		
	  <td align="center" colspan="5">
		<div class="form-group">
				<div class="col-sm-offset-0 col-sm-20">
					<button type="button" class="btn btn-primary" onClick="check_data()">送出訂單</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
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
