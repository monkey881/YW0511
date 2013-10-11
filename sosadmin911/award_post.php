<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>有奖竞猜后台问题管理</title>
</head>

<body>
<form id="ratioimg" name="ratioimg" enctype="multipart/form-data" method="post" action="award.php">
请选择问题图片：
  <label>
  <input type="file" name="file" />
  </label>
  <br>
  <hr>
  请输入图片对应的问题：

  <input type="text" name="question"  size="120"/><br>
  请输入各项答案：<br>
  <center>
  1.<input type="text" name="answer01" size="80"/><br>
  
  2.<input type="text" name="answer02" size="80"/><br>
  
  3.<input type="text" name="answer03" size="80"/><br>
  
  4.<input type="text" name="answer04" size="80"/><br>
  
  请选择正确的答案：
  <label>
<input type="radio" name="rename" value="1">1

<input type="radio" name="rename" value="2">2

<input type="radio" name="rename" value="3">3

<input type="radio" name="rename" value="4">4

  </label>
<br><br>

    <label>
    <input type="submit" name="Submit" value="确认提交" style=" border-right: #6a6a6a 1px solid;    padding-right: 10px;    border-top: #fff 1px solid; padding-left: 10px; font-size: 14px;    background: #d32c47;    padding-bottom: 3px;    border-left: #fff 1px solid;    cursor: pointer;    color: #fff;    padding-top: 3px;   border-bottom: #6a6a6a 1px solid;   height: 25px"/>
    </label>
  </p>
  
 
</form>
<br><br><br>
 </center>
</body>
</html>
