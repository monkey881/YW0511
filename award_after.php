<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$sql= "select `question`,`answer01`,`answer02`,`answer03`,`answer04`,`right_id` from ecs_award where id=1";     
$result = mysql_query($sql) or die("Query failed : " . mysql_error());

 while ( $rs = mysql_fetch_array( $result) ) {

 if( !empty($_POST["rename"]) && $_POST["rename"]==$rs['right_id']) {
  echo "问题回答正确，页面转跳中..."; 
  setcookie("user", "1", time()+3600*24*7);
  $user_cookie=$_COOKIE['user'];
 //header("Set-Cookie: name=$user_cookie;");
  header("refresh:0;url=category.php?id=71");           //跳转页面
 }
 else 
 {
 echo "<script language=javascript>alert('问题回答错误！');window.location='user_award.php';</script>";
 setcookie("user", "0", time()+3600*24*7);
 }

}

?>
