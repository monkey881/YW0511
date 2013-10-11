<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>上传图片</title>
</head>

<body>
<form id="ratioimg" name="ratioimg" enctype="multipart/form-data" method="post" action="huandengpian_a.php">
<label>
请选择幻灯片图片编号：
<input type="radio" name="rename" value="1">1

<input type="radio" name="rename" value="2">2

<input type="radio" name="rename" value="3">3

<input type="radio" name="rename" value="4">4


&nbsp;&nbsp;&nbsp;

<input type="radio" name="rename" value="1_small">1_small

<input type="radio" name="rename" value="2_small">2_small

<input type="radio" name="rename" value="3_small">3_small

<input type="radio" name="rename" value="4_small">4_small

  </label>
  <label>
  <br>
  请输入上述图片对应网址：
  <input type="text" name="uname" />
  （如果无需更改可以不填）
  </label>
<br>
请选择图片：
  <label>
  <input type="file" name="file" />
  </label>
  <p>
    <label>
    <input type="submit" name="Submit" value="确认提交" />
    </label>
  </p>
</form>
<br>
<font> 【提交后请点击清除缓存！】</font>
</body>
</html>
