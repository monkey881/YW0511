<?php
define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
// 按三种方式查询套餐一是订单号二是用户名三是套餐类型
$username = empty($_REQUEST['username']) ? '': trim($_REQUEST['username']);
$order_sn = empty($_REQUEST['order_sn'])? '' :trim($_REQUEST['order_sn']);
$type = empty($_REQUEST['type']) ? '' : trim($_REQUEST['type']);
$action = empty($_REQUEST['action']) ? '' : trim($_REQUEST['action']);
$id = empty($_REQUEST['id']) ? '' : trim($_REQUEST['id']);
$arr=array();
if($action == 'stop')
{
	$sql = "UPDATE taocan SET zhuangtai=10 WHERE taocan_id=$id";
	$GLOBALS['db'] ->query($sql);
	
	}

if ($username != '')
{
    $sql = "SELECT `taocan_id`,`order_sn`,`user_name`,`type`,`paytime` FROM `ecs_taocan` AS a,`ecs_users` AS b WHERE
 a.`user_id` =b.`user_id` AND a.`zhuangtai` IN (1,2) AND b.`user_name`= '$username' ";
    $arr = $GLOBALS['db'] -> getAll($sql);
    foreach($arr as $key => $value)
    {
        $arr[$key]['paytime'] = date('Y-m-d H:i:s',$value['paytime']);
        $arr[$key]['starttime'] = date('Y-m-d',nextPeiTime($value['paytime']));
        $arr[$key]['endtime'] = date('Y-m-d',get_end_time(nextPeiTime($value['paytime']), $value['type']));
        $arr[$key]['type_name'] = getNameByType($value['type']);
    } 
} elseif ($order_sn != '')
{
    $sql = "SELECT `taocan_id`, `order_sn`,`user_name`,`type`,`paytime` FROM `ecs_taocan` AS a,`ecs_users` AS b WHERE
 a.`user_id` =b.`user_id` AND a.`zhuangtai` IN (1,2) AND a.`order_sn`= '$order_sn' ";
    $arr = $GLOBALS['db'] -> getAll($sql);
    foreach($arr as $key => $value)
    {
        $arr[$key]['paytime'] = date('Y-m-d H:i:s',$value['paytime']);
        $arr[$key]['starttime'] = date('Y-m-d',nextPeiTime($value['paytime']));
        $arr[$key]['endtime'] = date('Y-m-d',get_end_time(nextPeiTime($value['paytime']), $value['type']));
        $arr[$key]['type_name'] = getNameByType($value['type']);
    } 
} elseif ($type != 0)
{
    $sql = "SELECT `taocan_id`, `order_sn`,`user_name`,`type`,`paytime` FROM `ecs_taocan` AS a,`ecs_users` AS b WHERE
 a.`user_id` =b.`user_id` AND a.`zhuangtai` IN (1,2) AND a.`type`= '$type' ";
    $arr = $GLOBALS['db'] -> getAll($sql);
    foreach($arr as $key => $value)
    {
        $arr[$key]['paytime'] = date('Y-m-d H:i:s',$value['paytime']);
        $arr[$key]['starttime'] = date('Y-m-d',nextPeiTime($value['paytime']));
        $arr[$key]['endtime'] = date('Y-m-d',get_end_time(nextPeiTime($value['paytime']), $value['type']));
        $arr[$key]['type_name'] = getNameByType($value['type']);
    } 
} 
else // 显示默认的数据
{
    $sql = "SELECT `taocan_id`, `order_sn`,`user_name`,`type`,`paytime`,`zhuangtai` FROM `ecs_taocan` AS a,`ecs_users` AS b WHERE
 a.`user_id` =b.`user_id` AND a.`zhuangtai` IN (1,2) ";
    $arr = $GLOBALS['db'] -> getAll($sql);
    foreach($arr as $key => $value)
    {
        $arr[$key]['paytime'] = date('Y-m-d H:i:s',$value['paytime']);
        $arr[$key]['starttime'] = date('Y-m-d',nextPeiTime($value['paytime']));
        $arr[$key]['endtime'] = date('Y-m-d',get_end_time(nextPeiTime($value['paytime']), $value['type']));
        $arr[$key]['type_name'] = getNameByType($value['type']);
		$arr[$key]['zhuangtai'] = $value['zhuangtai'];
    } 
} 

function nextPeiTime($time) // 付款之后的套餐下一次开始配送的日期
{
    $xinqi = date('w', $time); //获取该天的星期
    switch ($xinqi)
    {
        case 0:// 星期天，星期三配送需要3天后
            $time += 3 * 24 * 60 * 60;
            break;
        case 1:// 星期一，星期三配送需要2天后
            $time += 2 * 24 * 60 * 60;
            break;
        case 2:// 星期二，星期三配送需要1天后
            $time += 1 * 24 * 60 * 60;
            break;
        case 3:// 星期三，星期六配送需要3天后
            $time += 3 * 24 * 60 * 60;
            break;
        case 4:// 星期四，星期六配送需要2天后
            $time += 2 * 24 * 60 * 60;
            break;
        case 5:// 星期五，星期六配送需要1天后
            $time += 1 * 24 * 60 * 60;
            break;
        case 6:// 星期六，星期三配送需要4天后
            $time += 4 * 24 * 60 * 60;
            break;
    } 
    return $time;
} 

function getNameByType($type) // 根据类型获得套餐名称
{
    $name = '';
    switch ($type)
    {
        case 21:
            $name = '二人单月';
            break;
        case 23:
            $name = '二人单季';
            break;
        case 26:
            $name = '二人半年';
            break;
        case 31:
            $name = '三人单月';
            break;
        case 33:
            $name = '三人单季';
            break;
        case 36:
            $name = '三人半年';
            break;
        case 41:
            $name = '四人单月';
            break;
        case 43:
            $name = '四人单季';
            break;
        case 46:
            $name = '四人半年';
            break;
    } 
    return $name;
} 
function get_end_time($starttime, $type)
{
    $endtime = 0; //配送结束的时间
    $start_xinqi = date('w', $starttime); //获取开始配送该天的星期
    $num = ($type % 10) * 8; //配送的总次数
    switch ($start_xinqi)
    {
        case 3:// 星期三
            $endtime = $starttime + (($num / 2-1) * 7 + 3) * 24 * 60 * 60;

            break;
        case 6:// 星期六
            $endtime = $starttime + (($num / 2-1) * 7 + 4) * 24 * 60 * 60;

            break;
    } 
    return $endtime;
} 

?>
<div>
<form action="" method="post" name= "myform">
    <div>
        <p>查找：</p>
        用户名：<input type="text" name="username" />
        订单号：<input type="text" name="order_sn"/>
        <select name="type">
            <option value="0">请选择类型</option>
            <option value="21">二人单月</option>
            <option value="23">二人单季</option>
            <option value="26">二人半年</option>
            <option value="31">三人单月</option>
            <option value="33">三月单季</option>
            <option value="36">三人半年</option>
            <option value="41">四人单月</option>
            <option value="43">四人单季</option>
            <option value="46">四人半年</option>
        </select>
        <input type="submit" name="submit" value="查询" />
    <div>
</form>
</div>
<link href="styles/main.css" rel="stylesheet" type="text/css">
<div class="list-div">
    <table cellpadding="3" cellspacing="1">
        <tbody>
        <tr>
            <th>套餐ID</th>
            <th>订单号</th>
            <th>用户名</th>
            <th>套餐类型</th>
            <th>付款时间</th>
            <th>开始配送时间</th>
            <th>结束配送时间</th>
            <th>查看配送信息</th>
            
        </tr>
        <?php foreach ($arr as $value){?>
        <tr>
            <td><?php echo $value['taocan_id']?></td>
            <td><?php echo $value['order_sn']?></td>
            <td><?php echo $value['user_name']?></td>
            <td><?php echo $value['type_name']?></td>
             <td><?php echo $value['paytime']?></td>
            <td><?php echo $value['starttime']?></td>
            <td><?php echo $value['endtime']?></td>
            <td><a href="peisong_disp.php?id=<?php echo $value['taocan_id']?>&type=<?php echo $value['type']?>" title="查看配送信息"><img src="images/icon_view.gif" border="0" height="16" width="16"></a>&nbsp;&nbsp;  
           
            </td>
        
        </tr>
        <?php } ?>
        </tbody>
    </table>
    
    
</div>
