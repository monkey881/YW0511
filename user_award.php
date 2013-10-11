<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

if(!isset($_COOKIE["user"])){

$sql= "select `question`,`answer01`,`answer02`,`answer03`,`answer04`,`right_id` from ecs_award where id=1";     
$result = mysql_query($sql) or die("Query failed : " . mysql_error());

echo "<div style='height:310px; margin-top:230px; margin-left:360px;'>
<img src='sosadmin911/question_image/1.jpg' width=300 height=300 style='border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;float:left;'>
<div style='float:left; padding-left:20px;'>";

 while ( $rs = mysql_fetch_array( $result) ) {
   echo "问题:<font color=red><b>".$rs['question'].'</b></font><br><br><br>';
   echo "&nbsp;&nbsp;&nbsp;&nbsp;<1>&nbsp;".$rs['answer01'].'<br>';
   echo "&nbsp;&nbsp;&nbsp;&nbsp;<2>&nbsp;".$rs['answer02'].'<br>';
   echo "&nbsp;&nbsp;&nbsp;&nbsp;<3>&nbsp;".$rs['answer03'].'<br>';
   echo "&nbsp;&nbsp;&nbsp;&nbsp;<4>&nbsp;".$rs['answer04'].'<br>';
   
 }

?>
<style>
body{ background-image:url(images/award.jpg);background-repeat:no-repeat;}
</style>




<form id="ratioimg" name="ratioimg" enctype="multipart/form-data" method="post" action="award_after.php">

<label>
<br><br>
请选择正确的答案（每人只有一次机会哦~）
<br><br><br>
<input type="radio" name="rename" value="1"> 1

<input type="radio" name="rename" value="2"> 2

<input type="radio" name="rename" value="3"> 3

<input type="radio" name="rename" value="4"> 4
</label>
<br><br>
<label>
    <input type="submit" name="Submit" value="确认提交" style="padding: 10px; background-color: rgb(153, 204, 102); font-size: 14px; cursor: pointer; background-position: initial initial; background-repeat: initial initial;" onmouseover="this.style.background='#6CE3D5'" onmouseout="this.style.background='#9C6'"/>
</label>
</form>

</div>
</div>

<!--<DIV STYLE="position:absolute;left=10;top=20"><script src="http://127.0.0.7/ori-life/goods_script.php?cat_id=71&need_image=true&goods_num=10&arrange=h&rows_num=10&charset=UTF8&sitename="></script></DIV>
<DIV STYLE="position:absolute;left=15;top=25"> 你的内容123</DIV>-->

<?php
}else 
{ 
echo "您已参与过本次有奖竞猜活动！请关注下一次有奖竞猜活动";
}
?>

