<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');

$sql = 'SELECT * FROM `ecs_zhaomu` ORDER BY `addtime` DESC';
$results = $db->getAll($sql);
foreach($results as $result){
?>
<table width="90%" border="0" cellpadding="8" bgcolor="#D0FFDC">
  <tr>
    <td colspan="2">ID:<?php echo $result['id']?></td>
  </tr>
  <tr>
    <td>姓名:<?php echo $result['name']?></td>
    <td>年龄:<?php echo $result['age']?></td>
  </tr>
  <tr>
    <td>电话:<?php echo $result['phone']?></td>
    <td>务农年龄:<?php echo $result['wunongage']?></td>
  </tr>
  <tr>
    <td colspan="2">公司地址:<?php echo $result['companyaddress']?></td>
  </tr>
  <tr>
    <td colspan="2">主营产品:<?php echo $result['chanpin']?></td>
  </tr>
  <tr>
    <td>属性:<?php echo $result['shuxin']?></td>
    <td>特点:<?php echo $result['tedian']?></td>
  </tr>
  <tr>
    <td>公司名称:<?php echo $result['company']?></td>
    <td>地区:<?php echo $result['area']?></td>
  </tr>
  <tr>
    <td>注册资本:<?php echo $result['money']?></td>
    <td>农场地址:<?php echo $result['farmaddress']?></td>
  </tr>
  <tr>
    <td>申请时间:<?php echo date('Y-m-d H:i:s',$result['addtime'])?></td>
    <td>&nbsp;</td>
  </tr>
</table>
<br /></br>
<?php
}
?>
<style>
.list_zhaomu{
    float:left;
}
.list_zhaomu li{

}

</style>