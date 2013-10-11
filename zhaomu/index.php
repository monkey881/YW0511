<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>原味生活招募表单</title>
<link rel="stylesheet" type="text/css" href="view.css" media="all">
<script type="text/javascript" src="view.js"></script>
<script>
    function checknull(){
        for(var i =1;i<=10;i++){
            var element = document.getElementById('element_'+i);
            if(i ==7 || i==9)
                break;
            if(element.value == ''){
                alert('请填写相关内容！');
                return false;
            }
        }
        return true;
    }
</script>
<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '../../includes/init.php');
if($_POST['name']){
    $addtime = mktime();
 $sql = "INSERT INTO `ecs_zhaomu`(`name`,`age`,`phone`,`wunongage`,`chanpin`,`companyaddress`,`company`,`money`,`tedian`,`addtime`) VALUES('".$_POST['name']."',".$_POST['age'].",'".$_POST['phone']."',".$_POST['wunongage'].",'".$_POST['chanpin']."','".$_POST['companyaddress']."','".$_POST['company']."','".$_POST['money']."','".$_POST['tedian']."',".$addtime.")";

if($db->query($sql)){
    echo "<script>alert('感谢您提交信息！我们会尽快与您联系！')</script>";
}else{
    echo "<script>alert('未知错误，提交失败！')</script>";
}
}

?>
<style type="text/css">
body {
    background-color: #B8B8B8;
}
</style>
</head>
<body id="main_body" >
    
    <img src="top.png" alt="" height="63" id="top">
    <div id="form_container">
    
  <h1><a>Untitled Form12121212121</a></h1>
        <form id="form_509801" class="appnitro"  method="post" action="" onsubmit="return checknull();">
                    <div class="form_description">
                      <p style="font-family: '楷体'; font-weight: bold; font-size: 24px; text-align: center;">镇江原味生活&quot;良心农&quot;在线申请加盟</p>
                      <p style="font-weight: bold" >原味生活简介： </p>
                      <p >镇江市原味生活农业科技有限公司，简称&quot;原味生活&quot;(www.yw0511.com)是一个以短半径、可追溯、受监督、安全、好吃、高质为理念，提供&#8220;连婴儿和妈妈都可以吃得放心&#8221;的B2C安全食品幸福超市。 </p>
                      <p >原味生活目前主要以线上销售签约农户的0农药有机肥蔬菜套餐为特色，未来将辅以家庭日常所需的五谷杂粮、新鲜禽肉蛋类等食品，通过镇江市这种短半径内的供给、实名制的可追溯体制、受消费者和原味生活的共同监督、定期不定期检测的安全体制、再现原味的好吃和高质量，为消费者提供可信赖的安全高质的食品。 </p>
                      <p style="font-weight: bold" >原味生活愿景： </p>
                      <p >尝试解决一部分对食品安全有着强烈诉求的特殊人群（母婴、化学物质过敏性体质人群、疾病患者、健康生活提倡者等）的饮食问题，诚实、负责的为原味生活每个会员的家庭服务好每一天。</p>
                      <div style=" color: red; font-weight: normal;">
                        <p style="font-weight: bold; padding-top:10px; padding-left:10px;">填写内容注意事项:</p>
                        <p style="padding-left:10px; padding-bottom:5px;"> 对于有愿成为镇江原味生活&quot;良心农&quot;的农户，按以下的要求如实填写后提交，等待原味生活的工作人员与您联系，谢谢您的支持！</p>
                      </div>
                      <p >&nbsp;</p>
          </div>						
            <ul >
            
                    <li id="li_1" >
        <label class="description" for="element_1">姓名 </label>
        <div>
            <input id="element_1" name="name" class="element text medium" type="text" maxlength="255" value=""/> 
        </div> 
        </li>		<li id="li_2" >
        <label class="description" for="element_2">年龄 </label>
        <div>
            <input id="element_2" name="age" class="element text medium" type="text" maxlength="255" value=""/> 
        </div> 
        </li>		<li id="li_3" >
        <label class="description" for="element_3">手机 </label>
        <div>
            <input id="element_3" name="phone" class="element text medium" type="text" maxlength="255" value=""/> 
        </div> 
        </li>		<li id="li_5" >
        <label class="description" for="element_5">务农年数 </label>
        <div>
            <input id="element_5" name="wunongage" class="element text medium" type="text" maxlength="255" value=""/> 
        </div> 
        </li>		<li id="li_4" >
        <label class="description" for="element_4">农场地址 </label>
        <div>
            <input id="element_4" name="companyaddress" class="element text large" type="text" maxlength="255" value=""/> 
        </div> 
        </li>		<li id="li_6" >
        <label class="description" for="element_6">特色产品名称 </label>
        <div>
            <textarea id="element_6" name="chanpin" class="element textarea "></textarea> 
        </div> 
        </li>		<li id="li_8" >
        <label class="description" for="element_8">特色农产品特点描述及是否安全
 </label>
        <div>
            <textarea id="element_8" name="tedian" class="element textarea "></textarea> 
        </div> 
        </li>		<li id="li_9" >
        <label class="description" for="element_9">公司/合作社名称（无则不填） </label>
        <div>
            <textarea id="element_9" name="company" class="element textarea "></textarea> 
        </div> 
        </li>		<li id="li_7" >
        <label class="description" for="element_7">注册资本（无则不填）</label>
        <div>
            <textarea id="element_7" name="money" class="element textarea "></textarea> 
        </div> 
        </li>
        <li id="li_10">
        <div>
            <img src="../captcha.php?7529" alt="captcha" onClick="this.src='../captcha.php?'+Math.random()">
            <input id="element_10" name="captcha" class="element  "></input> 
        </div> 
        </li>
            
                    <li class="buttons">
                
                <input id="saveForm" class="button_text" type="submit" name="submit" value="提交申请" />
        </li>
            </ul>
        </form>	
        
    </div>
    <img id="bottom" src="bottom.png" alt="">
    </body>
</html>