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
$action  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'default';
 $smarty->assign('action',     $action);
/*------------------------------------------------------ */
//-- 商品列表，商品回收站
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list' )
{
    admin_priv('goods_manage');


	
    $taocan_order_list = get_taocan_order();
	
    $smarty->assign('order_taocan',   $taocan_order_list['orders_taocan']);
    $smarty->assign('filter',       $taocan_order_list['filter']);
    $smarty->assign('record_count', $taocan_order_list['record_count']);
    $smarty->assign('page_count',   $taocan_order_list['page_count']);
    $smarty->assign('full_page',    1);

    /* 排序标记 */
   // $sort_flag  = sort_flag($goods_list['filter']);
    
    //print_r($taocan_order_list);


    $smarty->display("taocan_order_list.htm");
}
elseif ($_REQUEST['act'] == 'sztaocan' )
{
    admin_priv('goods_manage');


	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/calendar.class.php');
	$tid = isset($_REQUEST['tid']) ? intval($_REQUEST['tid']) : 0;
	
    $taocan = get_order_taocan($tid);
	$songed=get_peisong_date_list($taocan['goods_id'],1);
	$shezhied=get_peisong_date_list($taocan['goods_id'],0);
//	print_r($shezhied);
	$cal = new Calendar($songed,$shezhied);
	$cal = $cal->display();
	$smarty->assign('cal',  $cal);
	$smarty->assign('user_id',  $taocan['user_id']);
    $smarty->assign('merge',  $merge);

    $smarty->assign('taocan', $taocan);
	$smarty->assign('full_page',    1);
   


    $smarty->display("taocan_order_list.htm");
}
elseif ($_REQUEST["act"] == "query")
{
    $taocan_order_list = get_taocan_order();
    $smarty->assign('order_taocan',   $taocan_order_list['orders_taocan']);
    $smarty->assign('filter',       $taocan_order_list['filter']);
    $smarty->assign('record_count', $taocan_order_list['record_count']);
    $smarty->assign('page_count',   $taocan_order_list['page_count']);

   
    $tpl = 'taocan_order_list.htm';
    make_json_result($smarty->fetch($tpl), '',array('filter' => $taocan_order_list['filter'], 'page_count' => $taocan_order_list['page_count']));
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
		'zengsong'=>$_POST['zengsong'],
		'status'=>$_POST['status'],
	);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_taocan'),
        $arr, 'UPDATE', "tid = '$_POST[tid]'");
	$links[] = array('href' => 'taocan_order.php?act=list', 'text' =>  "套餐订单列表");
    sys_msg("信息更改成功", 0, $links);

}
else if ($_REQUEST['act'] == 'del' )
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    admin_priv('goods_manage');
	
	 $sql = "UPDATE " . $ecs->table('order_taocan') .
                " SET status=-1 WHERE tid=$_GET[tid]";
     $GLOBALS['db']->query($sql);
	$links[] = array('href' => 'taocan_order.php?act=list', 'text' =>  "套餐订单列表");
    sys_msg("信息更改成功", 0, $links);

}

function get_taocan_order()
{
    $result = get_filter();
    if ($result === false)
    {
        /* 过滤信息 */
        
        if (!empty($_GET['is_ajax']) && $_GET['is_ajax'] == 1)
        {
            $_REQUEST['consignee'] = json_str_iconv($_REQUEST['consignee']);
            //$_REQUEST['address'] = json_str_iconv($_REQUEST['address']);
        }
       
        $filter['order_status'] = isset($_REQUEST['order_status']) ? intval($_REQUEST['order_status']) : -1;
        $filter['user_id'] = empty($_REQUEST['user_id']) ? 0 : intval($_REQUEST['user_id']);
        $filter['user_name'] = empty($_REQUEST['user_name']) ? '' : trim($_REQUEST['user_name']);
       
        $filter['sort_by'] = empty($_REQUEST['sort_by']) ? 'add_time' : trim($_REQUEST['sort_by']);
        $filter['sort_order'] = empty($_REQUEST['sort_order']) ? 'DESC' : trim($_REQUEST['sort_order']);

        $filter['start_time'] = empty($_REQUEST['start_time']) ? '' : (strpos($_REQUEST['start_time'], '-') > 0 ?  local_strtotime($_REQUEST['start_time']) : $_REQUEST['start_time']);
        $filter['end_time'] = empty($_REQUEST['end_time']) ? '' : (strpos($_REQUEST['end_time'], '-') > 0 ?  local_strtotime($_REQUEST['end_time']) : $_REQUEST['end_time']);

        $where = 'WHERE o.status <> -1 ';
        if ($filter['order_id'])
        {
            $where .= " AND o.order_id LIKE '%" . mysql_like_quote($filter['order_id']) . "%'";
        }
        
        if ($filter['order_status'] != -1)
        {
            $where .= " AND o.status  = '$filter[order_status]'";
        }
       
        if ($filter['user_id'])
        {
            $where .= " AND o.user_id = '$filter[user_id]'";
        }
        if ($filter['user_name'])
        {
            $where .= " AND u.user_name LIKE '%" . mysql_like_quote($filter['user_name']) . "%'";
        }
        if ($filter['start_time'])
        {
            $where .= " AND o.add_time >= '$filter[start_time]'";
        }
        if ($filter['end_time'])
        {
            $where .= " AND o.add_time <= '$filter[end_time]'";
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
            $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('order_taocan') . " AS o ,".
                   $GLOBALS['ecs']->table('users') . " AS u " . $where;
        }
        else
        {
            $sql = "SELECT COUNT(*) FROM " . $GLOBALS['ecs']->table('order_taocan') . " AS o ". $where;
        }

        $filter['record_count']   = $GLOBALS['db']->getOne($sql);
        $filter['page_count']     = $filter['record_count'] > 0 ? ceil($filter['record_count'] / $filter['page_size']) : 1;

        /* 查询 */
        $sql = "SELECT o.*,
                    IFNULL(u.user_name, '" .$GLOBALS['_LANG']['anonymous']. "') AS buyer ".
                " FROM " . $GLOBALS['ecs']->table('order_taocan') . " AS o " .
                " LEFT JOIN " .$GLOBALS['ecs']->table('users'). " AS u ON u.user_id=o.user_id ". $where .
                " ORDER BY $filter[sort_by] $filter[sort_order] ".
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
        $row[$key]['start_time']=local_date('m-d H:i', $value['start_time']);
		$row[$key]['end_time']=local_date('m-d H:i', $value['end_time']);
		$row[$key]['status']=getStatus2($value['status']);
		$row[$key]['taocan_weight']=($value['taocan_weight']*2).'斤';
		$row[$key]['song_weight']=($value['song_weight']*2).'斤';
		$hy_weight=$value['taocan_weight']+$value['zengsong']-$value['song_weight'];
		if($hy_weight==0){
			$GLOBALS['db']->query("UPDATE ". $GLOBALS['ecs']->table('order_taocan') ." SET status=4,end_time=".time()." WHERE tid=".$value['tid']);
		}
		$row[$key]['hy_weight']=$hy_weight<=3?'<font color="#FF0000">'.($hy_weight*2).'斤</font>':($hy_weight*2).'斤';
    }
    $arr = array('orders_taocan' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);

    return $arr;
}
function getStatus2($status=0)
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