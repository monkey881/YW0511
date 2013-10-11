<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

//ecshop二次开发，有奖竞猜  by chenpeng
@$tempimgname = strtolower($_FILES["file"][name]);
//mb_convert_encoding转换传输字集encod
$tempimgname = mb_convert_encoding(   $tempimgname,   "gb2312",   "utf-8");
//echo $tempimgname;
$tmpfiletype = substr(strrchr($tempimgname,"."),1);
if($tmpfiletype=="jpg")
{
        if(copy($_FILES["file"]["tmp_name"],strtolower("question_image\\1.".$tmpfiletype)))
        {
            //输出原来的文件名，大小，类型
            echo "问题图片已经上传成功！<br>";
            echo "正确答案：".$_POST["rename"]."<br>";
        }
        else
        {
            echo "图片上传失败"."<br>";
        }
}
else
{
    echo "未上传问题图片或上传文件类型错误，请勿上传除jpg以外的其他图片。<br>";
}
if($_POST["question"])
{
 echo "问题：".$_POST["question"];

$sql= "insert   into   ecs_award  (`id`,`question`,`answer01`,`answer02`,`answer03`,`answer04`,`right_id`)   values   ('1','".$_POST["question"]."','".$_POST["answer01"]."','".$_POST["answer02"]."','".$_POST["answer03"]."','".$_POST["answer04"]."','".$_POST["rename"]."')";     
$result=mysql_query($sql);   
if($result) 
 echo "<br>数据库记录成功";
else 
{
$sqla= "replace  into   ecs_award  (`id`,`question`,`answer01`,`answer02`,`answer03`,`answer04`,`right_id`)   values   ('1','".$_POST["question"]."','".$_POST["answer01"]."','".$_POST["answer02"]."','".$_POST["answer03"]."','".$_POST["answer04"]."','".$_POST["rename"]."')";     
$res=mysql_query($sqla);   
if($res)
 echo "<br>数据库更新成功";
 else
 echo "<br>数据库更新失败";
}

}
?>
