<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

//ecshop二次开发，后台幻灯片上传   by chenpeng
$tempimgname = strtolower($_FILES["file"][name]);
//mb_convert_encoding转换传输字集encod
$tempimgname = mb_convert_encoding(   $tempimgname,   "gb2312",   "utf-8");
//echo $tempimgname;
$tmpfiletype = substr(strrchr($tempimgname,"."),1);
if($tmpfiletype=="jpg")
{
        if(copy($_FILES["file"]["tmp_name"],strtolower("huandengimage\\".$_POST["rename"].".".$tmpfiletype)))
        {
            //输出原来的文件名，大小，类型
            echo "图片已经上传成功！<br>";
            echo "编号：".$_POST["rename"]."<br>";
        }
        else
        {
            echo "图片上传失败"."<br>";
        }
}
else
{
    echo "未上传图片或上传文件类型错误，请勿上传除jpg以外的其他图片。<br>";
}
if($_POST["uname"])
{
 echo "链接地址：".$_POST["uname"];
$conn=mysql_connect('localhost','root','chen095040079win');
mysql_query("SET NAMES 'utf8'"); 
mysql_select_db('ecshop',$conn);

$sql= "insert   into   ecs_huandengpian  (`uid`,`address`)   values   ('".$_POST["rename"]."','".$_POST["uname"]."')";     
$result=mysql_query($sql);   
if($result) 
 echo "<br>数据库记录成功";
else 
{
$sqla= "replace  into   ecs_huandengpian  (`uid`,`address`)   values   ('".$_POST["rename"]."','".$_POST["uname"]."')";     
$res=mysql_query($sqla);   
if($res)
 echo "<br>数据库更新成功";
 else
 echo "<br>数据库更新失败";
}

}
?>
