<?php

/**
 * ECSHOP 商品管理程序
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: goods.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
require_once(ROOT_PATH . '/' . ADMIN_PATH . '/includes/lib_goods.php');
include_once(ROOT_PATH . '/includes/cls_image.php');
$_REQUEST['act']=empty($_REQUEST['act'])?'default':$_REQUEST['act'];
/*------------------------------------------------------ */
//-- 商品列表，商品回收站
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list' )
{
    admin_priv('goods_manage');

	$sql="SELECT u.user_name,p.ps_time,p.status FROM ". $GLOBALS['ecs']->table('peisong')." AS p LEFT JOIN ". $GLOBALS['ecs']->table('user')." AS u ON p.user_id=u.user_id  LEFT JION  ". $GLOBALS['ecs']->table('user_address')." AS a ON p.address_id=a.address_id";
	$peisong=$row=$GLOBALS['db']->getRow($sql);
  
	
    $smarty->assign('peisong',   $peisong);
    $smarty->assign('filter',       $taocan_order_list['filter']);
    $smarty->assign('record_count', $taocan_order_list['record_count']);
    $smarty->assign('page_count',   $taocan_order_list['page_count']);
    $smarty->assign('full_page',    1);

    /* 排序标记 */
   // $sort_flag  = sort_flag($goods_list['filter']);
    
    //print_r($taocan_order_list);


    $smarty->display("taocan_order_list.htm");
}
else if ($_REQUEST['act'] == 'edit' )
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    admin_priv('goods_manage');
	$tid=$_GET['tid'];
	$sql="SELECT * FROM ". $GLOBALS['ecs']->table('order_taocan')." WHERE tid=$tid";
	$row=$GLOBALS['db']->getRow($sql);
	
	if($row){
		$row['add_time']=local_date($GLOBALS['_CFG']['time_format'], $row['add_time']);
		$row['start_time']=local_date($GLOBALS['_CFG']['time_format'], $row['start_time']);
		$row['end_time']=local_date($GLOBALS['_CFG']['time_format'], $row['end_time']);
		$row['user_name']=$GLOBALS['db']->getone("SELECT user_name FROM ". $GLOBALS['ecs']->table('users')." WHERE user_id=$row[user_id]");
		}
	$smarty->assign('order_taocan', $row);


    $smarty->display("taocan_order_edit.htm");
}
else if ($_REQUEST['act'] == 'edit_save' )
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    admin_priv('goods_manage');
	$arr=array(
		'add_time'=>local_strtotime($_POST['add_time']),
		'start_time'=>local_strtotime($_POST['start_time']),
		'end_time'=>local_strtotime($_POST['end_time']),
		'taocan_weight'=>$_POST['taocan_weight'],
		'song_weight'=>$_POST['song_weight'],
		'status'=>$_POST['status'],
	);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_taocan'),
        $arr, 'UPDATE', "tid = '$_POST[tid]'");
	$links[] = array('href' => 'taocan_order.php?act=list', 'text' =>  "套餐订单列表");
    sys_msg("信息更改成功", 0, $links);

}
else if($_REQUEST['act'] == 'default' )
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
   admin_priv('goods_manage');

	
	$peisong=get_peisong();
	$peisong_list=array();
	$peisong_shop_list=array();
	foreach($peisong['peisong'] as $ps_id => $ps){
		$ps['user_name']=get_username($ps['user_id']);
		if($ps['shipping_id']==5){
			$addressinfo=get_addressmore($ps['address_id']);
			$ps['address']=$addressinfo['address'].",".$addressinfo['conginee'].",".$addressinfo['tel'];
		}
		else{
			$ps['address']=get_address($ps['address_id']);
		}
		
		$peisong_shop_list[$ps_id]=get_peisong_shop($ps['ps_id']);
		$peisong_list[]=$ps;
	}
    $smarty->assign('peisong',   $peisong_list);
	$smarty->assign('peisong_shop_list',   $peisong_shop_list);
    $smarty->assign('filter',       $peisong['filter']);
    $smarty->assign('record_count', $peisong['record_count']);
    $smarty->assign('page_count',   $peisong['page_count']);
    $smarty->assign('full_page',    1);

    /* 排序标记 */
   // $sort_flag  = sort_flag($goods_list['filter']);
    
    //print_r($taocan_order_list);

    $smarty->display("peisong_list.htm");
}
else if($_REQUEST['act'] == 'query')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
   	admin_priv('goods_manage');

	
	$peisong=get_peisong();
	$peisong_list=array();
	$peisong_shop_list=array();
	
	foreach($peisong['peisong'] as $ps_id => $ps){
		$ps['user_name']=get_username($ps['user_id']);
		if($ps['shipping_id']==5){
			$addressinfo=get_addressmore($ps['address_id']);
			$ps['address']=$addressinfo['address'].",".$addressinfo['conginee'].",".$addressinfo['tel'];
		}
		else{
			$ps['address']=get_address($ps['address_id']);
		}
		$peisong_shop_list[$ps_id]=get_peisong_shop($ps['ps_id']);
		$peisong_list[]=$ps;
	}
	
    $smarty->assign('peisong',   $peisong_list);
    $smarty->assign('filter',       $peisong['filter']);
	$smarty->assign('peisong_shop_list',   $peisong_shop_list);
    $smarty->assign('record_count', $peisong['record_count']);
    $smarty->assign('page_count',   $peisong['page_count']);

	$tpl = 'peisong_list.htm';
    make_json_result($smarty->fetch($tpl), '',array('filter' => $peisong['filter'], 'page_count' => $peisong['page_count']));
}
else if($_REQUEST['act'] == 'setStatus')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
   	admin_priv('goods_manage');
	$status=$_POST['status'];
	
	
	if($status=='ph' || $status=='gqph'){
		$temp_peisong=get_peisong_by_status(1);
		foreach($temp_peisong as $ps){
		$data=array('status'=>'2');
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('peisong'), $data, 'UPDATE', "ps_id = $ps[ps_id]");
		}
	}
	else if($status=='fh' ||$status=='gqfh'){
		$temp_peisong=get_peisong_by_status(2);
		foreach($temp_peisong as $ps){
		$data=array('status'=>'3');
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('peisong'), $data, 'UPDATE', "ps_id = $ps[ps_id]");
		
		$data2=array('song_weight'=>'song_weight+'.$ps['weight']);
		$GLOBALS['db']->query("UPDATE ".$GLOBALS['ecs']->table('order_taocan')." SET song_weight=song_weight+$ps[weight] WHERE tid = $ps[order_taocan_id]");
		}
	}
	$peisong=get_peisong();
	
    $smarty->assign('peisong',   $peisong_list);
    $smarty->assign('filter',       $peisong['filter']);
	$smarty->assign('peisong_shop_list',   $peisong_shop_list);
    $smarty->assign('record_count', $peisong['record_count']);
    $smarty->assign('page_count',   $peisong['page_count']);

	$tpl = 'peisong_list.htm';
    make_json_result($smarty->fetch($tpl), '',array('filter' => $peisong['filter'], 'page_count' => $peisong['page_count']));
}

else if($_REQUEST['act'] == 'wsz')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
   	admin_priv('goods_manage');
	$psdate=getPeisongDate(1);
	$pssql="SELECT order_taocan_id FROM ".$GLOBALS['ecs']->table('peisong')." WHERE status=1 AND ps_time=".$psdate[0];
	$sql = "SELECT * FROM ".$GLOBALS['ecs']->table('order_taocan')." AS ot LEFT JOIN "
	.$GLOBALS['ecs']->table('users')." AS u ON ot.user_id=u.user_id where ot.status=2  AND ot.tid NOT IN($pssql)";
	$taocan_urer=$GLOBALS['db']->getAll($sql);
	foreach($taocan_urer as $taocan){
		$taocan['status']=getTaocanStatus($taocan['status']);
		$hy_weight=$taocan['taocan_weight']+$taocan['zengsong']-$taocan['song_weight'];
		$taocan['hy_weight']=$hy_weight<=3?'<font color="#FF0000">'.($hy_weight*2).'斤</font>':($hy_weight*2).'斤';
		$taocan2[]=$taocan;
	}
	
    $smarty->assign('taocan_urer',   $taocan2);
	$smarty->assign('act',   'wsz');
    $smarty->assign('filter',       $peisong['filter']);
    $smarty->assign('record_count', $peisong['record_count']);
    $smarty->assign('page_count',   $peisong['page_count']);

	$tpl = 'peisong_list.htm';
    make_json_result($smarty->fetch($tpl));
}
else if($_REQUEST['act'] == 'excel')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
   	admin_priv('goods_manage');
	$psdate=getPeisongDate(1);
	$sql="SELECT * FROM ".$GLOBALS['ecs']->table('user_basket')." AS ub LEFT JOIN ".$GLOBALS['ecs']->table('peisong')." AS ps ON ub.ps_id=ps.ps_id WHERE ps_time=".$psdate[0];
	$peisong=$GLOBALS['db']->getAll($sql);
	$peisong_list=array();
	$peisong_shop_list=array();
	
	foreach($peisong as $ps_id => $ps){
		$ps['user_name']=get_username($ps['user_id']);
		if($ps['shipping_id']==5){
			$addressinfo=get_addressmore($ps['address_id']);
			$ps['address']=$addressinfo['address'].",".$addressinfo['conginee'].",".$addressinfo['tel'];
		}
		else{
			$ps['address']=get_address($ps['address_id']);
		}
		$ps['weight']=get_goods_weight($ps['goods_id'])*$ps['goods_number']*2;
		$ps['taocan_name']=get_taocan_name($ps['order_taocan_id']);
		$ps['goods_name']=get_goods_name($ps['goods_id']);
		$ps['order_sn']=get_order_sn($ps['order_taocan_id']);
		$peisong_list[]=$ps;
	}
	
	header("Content-type:application/vnd.ms-excel;");
    header("Content-Type:application/force-dowanload");
	header("Content-Disposition:attachment;filename=peisong_".date("y-m-d").".xls");
	
	
	echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'>";
    echo "<table border='1'>";
    echo "        <thead>";
    echo "            <tr>";
    echo "                <td>客户</td>";
    echo "                <td>订单号</td>";
    echo "                <td>时间</td>";
    echo "                <td>套餐种类</td>";
    echo "                <td>商品</td>";
    echo "                <td>数量</td>";
    echo "                <td>地点</td>";
    echo "            </tr>";
    echo "        </thead>";
    echo "        <tbody>";
    foreach($peisong_list as $item){       
      echo "           <tr>";
      echo "               <td>".$item['user_name']."</td>";
      echo "               <td>".$item['order_sn']."</td>";
      echo "               <td>".date('y-m-d',$psdate[0])."</td>";
      echo "               <td>".$item['taocan_name']."</td>";
      echo "               <td>".$item['goods_name']."</td>";
      echo "               <td>".$item['weight']."</td>";
      echo "               <td> ".$item['address']."</td>";
      echo "           </tr>";
               }
      echo "      </tbody>";
      echo "  </table>";
    
}


function get_username($userid){
	return $GLOBALS['db']->getone('SELECT user_name FROM '.$GLOBALS['ecs']->table('users').' WHERE user_id='.$userid);
}
function get_address($address_id){
	$sql="SELECT shipping_area_name FROM  ".$GLOBALS['ecs']->table('shipping_area')."  WHERE shipping_area_id=".$address_id;
	return $GLOBALS['db']->getone($sql);

}

function get_addressmore($address_id){
	$sql="SELECT consignee,address,tel FROM  ".$GLOBALS['ecs']->table('user_address')."  WHERE address_id=".$address_id;
	return $GLOBALS['db']->getrow($sql);

}

function get_taocan_name($order_taocan_id){
	$sql="SELECT taocan_name FROM ".$GLOBALS['ecs']->table('order_taocan')."  WHERE tid=".$order_taocan_id;
	return $GLOBALS['db']->getone($sql);

}
function get_shippingid($tid){
	$sql="SELECT oi.shipping_id FROM ".$GLOBALS['ecs']->table('order_info')." AS oi LEFT JOIN ".$GLOBALS['ecs']->table('order_taocan')." AS ot ON oi.order_id=ot.order_id  WHERE ot.tid=".$tid;
	return $GLOBALS['db']->getone($sql);

}
function get_order_sn($order_id){
	$sql="SELECT order_sn FROM ".$GLOBALS['ecs']->table('order_taocan')." AS ot LEFT JOIN ".$GLOBALS['ecs']->table('order_info')." AS oi ON ot.order_id=oi.order_id  WHERE ot.tid=$order_id";
	
	return $GLOBALS['db']->getone($sql);

}
function get_goods_name($goods_id){
	$sql="SELECT goods_name FROM ".$GLOBALS['ecs']->table('goods')."  WHERE goods_id=".$goods_id;
	return $GLOBALS['db']->getone($sql);

}
function get_goods_weight($goods_id){
	$sql="SELECT goods_weight FROM ".$GLOBALS['ecs']->table('goods')."  WHERE goods_id=".$goods_id;
	return $GLOBALS['db']->getone($sql);

}
function get_peisong_shop($ps_id){
	$sql="SELECT g.goods_name,(ub.goods_number*g.goods_weight*2) as weight ,g.goods_id FROM ".$GLOBALS['ecs']->table('user_basket')." AS ub LEFT JOIN ".$GLOBALS['ecs']->table('goods')." AS g ON ub.goods_id=g.goods_id WHERE ub.ps_id=".$ps_id;
	return $GLOBALS['db']->getAll($sql);
}
function get_peisong()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤信息 */
        
      
       
      

        $where = 'WHERE 1 ';
		$psdate=getPeisongDate(1);
       	
		$psact=empty($_REQUEST['psDate'])?"jj":$_REQUEST['psDate'];
		$filter['psDate'] = $psact;
        if($psact=='jj'){
			$where.=' AND status=1 AND ps_time='.$psdate[0];
		}
		else if($psact=='yph'){
			$where.=' AND status=2';
		}
		else if($psact=='yjs'){
			$where.=' AND status=3';
		}
		else if($psact=='gq'){
			$now=time();
			$where.=' AND status=1 AND ps_time<'.$now;
		}

        /* 分页大小 */
        $filter['page'] = empty($_REQUEST['page']) || (intval($_REQUEST['page']) <= 0) ? 1 : intval($_REQUEST['page']);

        if (isset($_REQUEST['page_size']) && intval($_REQUEST['page_size']) > 0)
        {
            $filter['page_size'] = intval($_REQUEST['page_size']);
        }
        elseif (isset($_COOKIE['ECSCP']['page_size']) && intval($_COOKIE['ECSCP']['page_size']) > 0)
        {
            $filter['page_size'] = intval($_COOKIE['ECSCP']['page_size']);
        }
        else
        {
            $filter['page_size'] = 15;
        }

        /* 记录总数 */
        if ($filter['user_name'])
        {
            $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('peisong') . " AS p ,".
                   $GLOBALS['ecs']->table('users') . " AS u " . $where;
        }
        else
        {
            $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('peisong') . " AS p ". $where;
        }

        $filter['record_count']   = $GLOBALS['db']->getOne($sql);
        $filter['page_count']     = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

        /* 查询 */
       $sql="SELECT * FROM ". $GLOBALS['ecs']->table('peisong')." $where  ORDER BY ps_time DESC ".
                " LIMIT " . ($filter['page'] - 1) * $filter['page_size'] . ",$filter[page_size]";

       // foreach (array('order_sn', 'consignee', 'email', 'address', 'zipcode', 'tel', 'user_name') AS $val)
//        {
//            $filter[$val] = stripslashes($filter[$val]);
//        }
        set_filter($filter, $sql);
    }
    else
    {
        $sql    = $result['sql'];
        $filter = $result['filter'];
    }

    $row = $GLOBALS['db']->getAll($sql);

    /* 格式话数据 */
    foreach ($row AS $key => $value)
    {
        $row[$key]['ps_time']=local_date('m-d', $value['ps_time']);
		$row[$key]['status']=getpsStatus($value['status']);
		
    }
    $arr = array('peisong' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}
function getpsStatus($status=0)
{
	switch($status)
	{
		case 0:
		  return '未设置';
		  break;
		case 1:
			return '已设置';
		  	break;
		case 2:
			return '已配货';
		  	break;
		case 3:
			return '已结束';
		  	break;


	}
	
}
function get_peisong_by_status($status){
		$psdate=getPeisongDate(1);
		$now=time();
	$pssql="SELECT ps_id,weight,order_taocan_id FROM ".$GLOBALS['ecs']->table('peisong')." WHERE status=$status AND ps_time<$now";
	return $GLOBALS['db']->getAll($pssql);
}
function getPeisongDate($num){
	
		$today=mktime(0,0,0,date('m'),date('d'),date('y'));
		$psarray=array('3','6');
		$psdate=array();
		$i=0;
		$m=0;
		while($i<$num){
			if(in_array(date("w",($today+86400*$m)),$psarray)){
				
				$psdate[]=$today+86400*$m;
				$i++;
				}
			$m++;
			}
		return $psdate;
		}
function getTaocanStatus($status=0)
{
	switch($status)
	{
		case 0:
		  return '未付款';
		  break;
		case 1:
			return '未确认';
		  	break;
		case 2:
			return '进行中';
		  	break;
		case 3:
			return '已暂停';
		  	break;
		case 4:
			return '已结束';
		  	break;
		case 5:
			return '已失效';
		  	break;

	}
	
}
?>