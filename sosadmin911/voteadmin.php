<?php

define('IN_ECS', true);
require_once(dirname(__FILE__) . '/includes/init.php');
	$sql="select * from votetitle";
	$results=mysql_query($sql);
	$rows=mysql_fetch_assoc($results);
    $inall=$rows['sum'];

    $a=$_GET["a"];
    for($x=1;$x<=$inall;$x++)
    {
    if($_POST["Submit_$x"])
	{
	$a=$x;
	}
	}
    if($a=="") $a=1;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<link href="../style.css" rel="stylesheet" type="text/css" />
<title>原味生活投票系统</title>
<style type="text/css">
/*全局样式*/
body { font-family: "宋体"; font-size: 12pt; color: #333333; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px;background-image: url(images/Zlppy_Bg.jpg);} 
table { font-family: "宋体"; font-size: 9pt; line-height: 20px; color: #333333}
/*全局样式结束*/
</style>
<script language="javascript">
	function selectAll()
	{
		node=window.document.frm.itm;
		for(i=0;i<node.length;i++)
		{
			node[i].checked=true;
		}
	}
	function cancelAll()
	{
		node=frm.itm;
		for(i=0;i<node.length;i++)
		{
			node[i].checked=false;
		}
	}
	function del()
	{
		node=frm.itm;
		id="";
		for(i=0;i<node.length;i++)
		{
			if(node[i].checked)
			{
				if(id=="")
				{
					id=node[i].value
				}
				else
				{
					id=id+","+node[i].value
				}
			}
		}
		if(id=="")
		{
			alert("您没有选择删除项");
		}
		else
		{
			location.href="?type=del&id="+id
		}
	}
</script>
</head>
<body>
<?php


	if($_POST["Submit"])
	{
		$title=$_POST["title"];
		$sql="update votetitle set votetitle='$title' where `titleid`='".$_POST["testid"]."'";
		mysql_query($sql);
		?>
		<script language="javascript">
		    location.href="?a=<?php echo $_POST["testid"]?>";
		</script>
		<?php
	}
	if($_POST["Submit2"])
	{
		$newitem=$_POST["newitem"];
		$sql="insert into vote (titleid,item,count) values ('".$_POST["testid"]."','$newitem',1)";
		mysql_query($sql);
		?>
        <script language="javascript">
		    location.href="?a=<?php echo $_POST["testid"]?>";
		</script>
	<?php 
	}
    
    if($_POST["Submit_all"])   //设置数据库投票标题总数
	{
		$all=$_POST["testall"];
		$sql="update votetitle set sum='$all'";
		mysql_query($sql);
                
        for($i=1;$i<=$all;$i++){
        
		$sql="select * from votetitle where titleid=$i ";  
        $result=mysql_query($sql);  
  
        $row = mysql_fetch_array($result, MYSQL_ASSOC);  
  
        if (!mysql_num_rows($result))  
        {  
        $sql2="insert into votetitle (titleid,votetitle,sum) values ('$i','暂无','$all')";
		mysql_query($sql2);
        }  
        ?>
        <script language="javascript">
			alert("修改成功");
		    location.href="?a=<?php echo $_POST["testid"]?>";
		</script>
	    <?php }
		
	}
?>

<center>
<form id="all" name="all" method="post" action="" style="margin-bottom:3px;">
<?php for($x=1;$x<=$inall;$x++) { ?>                                    <!--投票记录条数-->
<input type="submit" name="Submit_<?php echo $x ?>" value="<?php echo $x ?>"<?php if($x==$a) {?>style=" background-color:#FD5B5B; cursor: pointer;"<?php } else{ ?> style=" background-color:#80BDCB; cursor: pointer;" <?php }?> />
<?php } ?>&nbsp;&nbsp;
<input type="text" name="testall" value="<?php echo $inall ?>" size="1" style=" background-color:#FFBF85;"/>
<input type="submit" name="Submit_all" value="设置投票项目显示总数" style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/>
</form>
</center>
<br><center><b>当前题号：<font color=red>（<?php echo $a ?>）</b></font> </center><br>
<form id="frm" name="frm" method="post" action="" style="margin-bottom:3px;">
  <table width="665" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#C2C2C2">
    <tr>
      <td colspan="4" bgcolor="#FFFFFF"><label>
	  <?php
	  	$sql="select * from votetitle  where `titleid`='$a'";
		$rs=mysql_query($sql);
		$rows=mysql_fetch_assoc($rs);
	  ?>
	  <textarea cols="100" rows="3" name="title"  id="title" value="<?php echo $rows["votetitle"]?>"/><?php echo $rows["votetitle"]?></textarea>
      </label></td>
      <td width="68" align="center" bgcolor="#FFFFFF"><label>
      <input type="text" name="testid" value="<?php echo $a ?>" size="1" style="display:none;"/>
        <input type="submit" name="Submit" value="修改标题" style="padding: 10px; background-color:#80BDCB; font-size: 14px; cursor: pointer; background-position: initial initial; background-repeat: initial initial;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/>
      </label>
</td>
    </tr>
    <tr>
      <th width="30" bgcolor="#BBDDE5">编号</th>
      <th width="355" bgcolor="#BBDDE5">项目</th>
      <th width="52" bgcolor="#BBDDE5">票数</th>
      <th width="50" align="center" bgcolor="#BBDDE5">修改</th>
      <th align="center" bgcolor="#BBDDE5">删除</th>
    </tr>
    <?php
		$sql="select * from vote  where `titleid`='$a' order by count desc";
		$rs=mysql_query($sql);
		while($rows=mysql_fetch_assoc($rs))
		{
		?>
	<tr>
      <td align="center" bgcolor="#FFFFFF"><input type="checkbox" name="itm" value="<?php echo $rows["id"]?>" /><?php echo $rows["id"]?></td>
      <td align="center" bgcolor="#FFFFFF"><?php echo $rows["item"]?></td>
      <td align="center" bgcolor="#FFFFFF"><?php echo $rows["count"]?></td>
      <td align="center" bgcolor="#FFFFFF"><input type="button" value="修改" onclick="location.href='?type=modify&id=<?php echo $rows["id"]?>&a=<?php echo $a ?>'" style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/></td>
      <td align="center" bgcolor="#FFFFFF"><input type="button" value="删除" onclick="location.href='?type=del&id=<?php echo $rows["id"]?>&a=<?php echo $a ?>'"  style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/></td>
    </tr>
		<?php
		}
	?>
    <tr>
      <td colspan="5" align="center" bgcolor="#FFFFFF">
	  	<input type="button" value="选择全部" onclick="selectAll()" style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/>
		<input type="button" value="取消全部" onclick="cancelAll()" style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/>
	    <input type="button" value="删除所选" onclick="del()"style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'" />
        <input type="button" value="查看投票结果" onClick="location.href='voteadmin.php?id=ck&a=<?php echo $a ?>'" style=" background-color:#FFBF85; cursor: pointer;" onmouseover="this.style.background='#FF8C24'" onmouseout="this.style.background='#FFBF85'"/>
      </td>
    </tr>
    <tr>
      <td colspan="3" bgcolor="#FFFFFF"><label>
        <input name="newitem" type="text" id="newitem" size="100"/>
      </label></td>
      <td colspan="2" bgcolor="#FFFFFF"><label>
        <input type="submit" name="Submit2" value="添加新项" style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/>
      </label>
      </td>
    </tr>
  </table>
</form>

<?php

    if($_GET["type"]=="modify"){
	
	$id=$_GET["id"];
	if($_POST["Submit3"])
	{
		$item=$_POST["itm"];
		$count=$_POST["count"];
		$sql="update vote set item='$item',count=$count where id=$id";
		mysql_query($sql);
	echo "<script language=javascript>window.location='voteadmin.php?a=$a'</script>";
	}
	$sql="select * from vote where id=$id";
	$rs=mysql_query($sql);
	$rows=mysql_fetch_assoc($rs);
?>
<form id="form1" name="form1" method="post" action="" style="margin-top:2px;">
  <table width="600" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#C2C2C2">
    <tr>
      <th colspan="2" bgcolor="#BBDDE5">修改投票项目</th>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFFFFF">名称:</td>
      <td bgcolor="#FFFFFF"><label>
        <input name="itm" type="text" id="itm" value="<?php echo $rows["item"]?>"  size="80"/>
      </label></td>
    </tr>
    <tr>
      <td align="center" bgcolor="#FFFFFF">票数：</td>
      <td bgcolor="#FFFFFF"><label>
        <input name="count" type="text" id="count" value="<?php echo $rows["count"]?>" />
      </label></td>
    </tr>
    <tr>
      <td colspan="2" align="center" bgcolor="#FFFFFF"><label>
        <input type="submit" name="Submit3" value="修改" style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/>
        <input type="reset" name="Submit" value="重置" style=" background-color:#80BDCB; cursor: pointer;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#80BDCB'"/>
      </label></td>
    </tr>
  </table>
</form>

<?php 
	}
?>
<?php
	if($_GET["type"]=="del"){
	$id=$_GET["id"];
	$sql="delete from vote where id in ($id)";
	mysql_query($sql);
	echo "<script language=javascript>alert('删除成功！');location.href='?a=$a';</script>";
	}
?>

<?php if($_GET["id"]=="ck")   /***************************显示投票结果******************************/
{
	$sql="select sum(count) as 'total' from vote where titleid=$a";
	$rs=mysql_query($sql);
	$rows=mysql_fetch_assoc($rs);
	$sum=$rows["total"];	         //得出总票数
	$sql="select * from vote where titleid=$a";
	$rs=mysql_query($sql);
?>

<table width="565" border="0" align="center" cellpadding="5" cellspacing="1" bgcolor="#C2C2C2">
<tr>
	<th bgcolor="#BBDDE5" width="70%">项目</th>
	<th bgcolor="#BBDDE5">票数</th>
	<th bgcolor="#BBDDE5">百分比</th>
</tr>
<?php
	while($rows=mysql_fetch_assoc($rs))
	{
	?>
	<tr>
		<td bgcolor="#FFFFFF"><?php echo $rows["item"]?></td>
		<td bgcolor="#FFFFFF"><?php echo $rows["count"]?></td>
		<td bgcolor="#FFFFFF">
			<?php
				$per=$rows["count"]/$sum;
				$per=number_format($per,4);
			?>
			<img src="100.jpg" height="4" width="<?php echo $per*100?>" />
			<?php echo $per*100?>%		</td>
	</tr>
	<?php
	}
?>
</table>
<div align="center">
<a href="voteadmin.php?a=<?php echo $a ?>" style="cursor: pointer; text-decoration:none;" >
隐藏结果
</a>
</div>
<br>
<?php } ?>


</body>
</html>

