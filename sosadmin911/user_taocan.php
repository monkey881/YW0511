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
/* 查看订单列表 */
elseif ($action == 'rili')
{
 	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/calendar2.class.php');
	$tid = isset($_REQUEST['tid']) ? intval($_REQUEST['tid']) : 0;
	
    $taocan = get_order_taocan($tid);
	$songed=get_peisong_date_list($taocan['tid'],3);
	$shezhied=get_peisong_date_list($taocan['tid'],1);
	
	if($taocan['status']=='进行中')
	   {
		   $taocan['handler'] = "<a href=\"user_taocan.php?act=stop_taocan&tid=" .$taocan['tid']. "\" onclick=\"if (!confirm('你确认要暂停配送吗？想要开始的时候记得来套餐管理里点击\"开始配送\"！')) return false;\">暂停配送</a>";
	   }
	    else if($taocan['status']=='已暂停')
	   {
		   $taocan['handler'] = " <a href=\"user_taocan.php?act=start_taocan&tid=" .$taocan['tid']. "\" onclick=\"if (!confirm('你确认要开始配送吗')) return false;\">开始配送</a>";
	   }
	$cal = new Calendar($songed,$shezhied);
	$cal = $cal->display();
	$smarty->assign('cal',  $cal);
	$smarty->assign('user_id',  $taocan['user_id']);
    $smarty->assign('merge',  $merge);
    $smarty->assign('taocan', $taocan);
    $smarty->display('user_taocan_rili.htm');
}
elseif ($action == 'stop_taocan')
{
 	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	$tid = isset($_REQUEST['tid']) ? intval($_REQUEST['tid']) : 1;
	$data=array(
	'status'=>'3'
	);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_taocan'), $data, 'UPDATE', "tid = $tid");
	
	$sql="select ps_id from " .$ecs->table('peisong'). " where order_taocan_id=$tid and status=1 order by ps_time asc";
	$peisong=$db->getAll($sql);
	foreach($peisong as  $item){
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('peisong'),array('status'=>'4'), 'UPDATE', "ps_id = $item[ps_id] AND status=1");
		}
		
		
     ecs_header("Location: taocan_order.php?act=list\n");
       exit;
	
}
elseif ($action == 'start_taocan')
{
	include_once(ROOT_PATH . 'includes/calendar.class.php');
 	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	$tid = isset($_REQUEST['tid']) ? intval($_REQUEST['tid']) : 0;
	$data=array(
	'status'=>'2'
	);
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('order_taocan'), $data, 'UPDATE', "tid = $tid");
	$sql="select ps_id from " .$ecs->table('peisong'). " where order_taocan_id=$tid and status=4 order by ps_time asc";
	$peisong=$db->getAll($sql);
	$cal = new Calendar();
	$psdate=$cal->getPeisongDate($peisong_count);
	$i=0;
	foreach($peisong as  $item){
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('peisong'),array('ps_time'=>$psdate[$i],'status'=>'1'), 'UPDATE', "ps_id = $item[ps_id] AND status='4'");
		$i++;
		}
		
    ecs_header("Location: taocan_order.php?act=list\n");
       exit;
	
}

?>