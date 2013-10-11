<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>doodle管理</title>
<?php
if(isset($_FILES["doodle"])){
	echo "hello";
if ($_FILES["doodle"]["error"] > 0)
  {
  echo "错误: " . $_FILES["doodle"]["error"] . "<br />";
  }
else
  {
	   move_uploaded_file($_FILES["doodle"]["tmp_name"],
      "../../displaypic/doodle.jpg");
  echo "Upload: " . $_FILES["doodle"]["name"] . "<br />";
  echo "Type: " . $_FILES["doodle"]["type"] . "<br />";
  echo "Size: " . ($_FILES["doodle"]["size"] / 1024) . " Kb<br />";
  echo "Stored in: " . $_FILES["doodle"]["tmp_name"];
  echo "<br>";
  }
}

?>
</head>

<body>

<div class="doodle_now"><!-- 当前doodle-->
<h2 style="color:#06C">当前doodle</h2>
<img src="../../displaypic/doodle.jpg"  alt="展示" width="480" height="300" hspace="200" vspace="13" align="middle" />
</div>
<div class="doodle_new">
<h2 style="color:#06c">修改doodle</h2>
<form action="" method="post" enctype="multipart/form-data" name="modify_doodle" id="modify_doodle">
<input type="file" name="doodle"  /><span style="color:green">请选择940*600分辨率图片</span>
<input type="submit" name="submit" title="提交" value="提交" />
</form>
</div>
</body>
</html>