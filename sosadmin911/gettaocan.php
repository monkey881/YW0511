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

 if ($action == 'gettaocan')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	$brand_id = isset($_POST['brand_id']) ? trim($_POST['brand_id']) : '';
    $tid = isset($_POST['tid']) ? trim($_POST['tid']) : '';
	$taocan = get_order_taocan($tid);
   	$goods_taocan_list = get_goods_taocan_list($taocan['goods_id'],18, $pager['start'],$brand_id);
	$smarty->assign('goods_taocan_list', $goods_taocan_list);
	$smarty->assign('bgcolor', $colors[$_POST['key']]);
	 $tpl = 'user_gettaocan.html';

    make_json_result($smarty->fetch($tpl));
}

else if ($action == 'addshop')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/lib_order.php');
	$ps_id = isset($_POST['ps_id']) ? trim($_POST['ps_id']) : '';
	$goods_id = isset($_POST['goods_id']) ? trim($_POST['goods_id']) : '';
	$num = isset($_POST['num']) ? trim($_POST['num']) : '';
	
	$shipping_area=get_shipping_area();
	
	$basket_list = get_basket_list($ps_id);
	
	if($_POST['tid']){
	$order_taocan=get_order_taocan_one($_POST['tid']);
	}
		
	$kucun=$db->getOne("select goods_number from ".$ecs->table('goods')." where goods_id=$goods_id");
	
	if($kucun>=$num && ($order_taocan['song_weight']+$basket_list[0]/2)<($order_taocan['taocan_weight']+$order_taocan['zengsong'])){
		if($goods_num=$db->getOne("select goods_number from ".$ecs->table('user_basket')." where ps_id=$ps_id and goods_id=$goods_id"))
		{
			
			$db->query("UPDATE ".$ecs->table('user_basket')." SET goods_number=goods_number+$num where ps_id=$ps_id and goods_id=$goods_id");
			}
		else
		{
		$sql = "INSERT INTO ".$ecs->table('user_basket')." (ps_id,goods_id,goods_number) VALUES ('$ps_id','$goods_id','$num')";
		$db->query($sql);
		}
		change_goods_storage($goods_id,'',-$num);
		
		$basket_list = get_basket_list($ps_id);
		
		
			$weight_mess="";
		if($basket_list[0]/2>=$order_taocan['ps_weight'])
		{
			$weight_mess="您已经挑选".($basket_list[0])."斤菜了";
			$psdata['status']=1;
		}
		$psdata['weight']=$basket_list[0]/2;
		$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('peisong'), $psdata, 'UPDATE', "ps_id = $ps_id");
	
	}
	else
	{
		
		if(($order_taocan['song_weight']+$basket_list[0]/2)>=($order_taocan['taocan_weight']+$order_taocan['zengsong'])){
			$weight_mess="你的套餐已经用完";
			
		}
		else{
		
		$weight_mess="库存已不足，请选择其它菜品";
		}
	}
	
	$smarty->assign('basket_list', $basket_list[1]);
	$smarty->assign('z_weight', $basket_list[0]);
	
	$smarty->assign('bgcolor', $colors[$_POST['key']]);
	$smarty->assign('shipping_area', $shipping_area);
	 $tpl = 'user_gettaocan.html';
	
    make_json_result($smarty->fetch($tpl),$weight_mess);
}

elseif ($action == 'changeAddress')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    $ps_id = isset($_POST['ps_id']) ? intval($_POST['ps_id']) : 0;
	$addres_id = isset($_POST['addres_id']) ? intval($_POST['addres_id']) : 0;
	$tid = isset($_GET['tid']) ? trim($_GET['tid']) : '';
    if ($ps_id > 0)
    {
		
        $db->query('UPDATE  ' .$ecs->table('peisong'). "SET address_id=$addres_id WHERE ps_id='$ps_id'" );
		$db->query('UPDATE  ' .$ecs->table('order_taocan'). "SET peisong_area_id=$addres_id WHERE tid='$tid'" );
    }

   
}

/* 删除收藏的商品 */
elseif ($action == 'delshop')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/lib_order.php');
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$ps_id= isset($_GET['ps_id']) ? intval($_GET['ps_id']) : 0;
	
	
    if ($id > 0)
    {
		$temp=$db->getRow("select goods_id,goods_number,ps_id from ".$ecs->table('user_basket'). "where basket_id='$id'");
        $db->query('DELETE FROM ' .$ecs->table('user_basket'). " WHERE basket_id='$id'" );
		change_goods_storage($temp['goods_id'],'',$temp['goods_number']);
		
    }

   	$basket_list = get_basket_list($ps_id);
	$tid=$db->getone("select order_taocan_id from ". $GLOBALS['ecs']->table('peisong') ." where ps_id=$ps_id");
	$order_taocan=get_order_taocan_one($tid);
	if($basket_list[0]<$order_taocan['ps_weight'])
	{
		
		$psdata['status']=0;
	}
	$psdata['weight']=$basket_list[0]/2;
	$GLOBALS['db']->autoExecute($GLOBALS['ecs']->table('peisong'), $psdata, 'UPDATE', "ps_id = $ps_id");
	$smarty->assign('shipping_area', $shipping_area);
	$smarty->assign('basket_list', $basket_list[1]);
	$smarty->assign('z_weight', $basket_list[0]);
	$tpl = 'user_gettaocan.html';

    make_json_result($smarty->fetch($tpl));
}


elseif ($action == 'edit_mygood_num')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	include_once(ROOT_PATH . 'includes/lib_order.php');
    $basket_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
	$weight = isset($_POST['val']) ? intval($_POST['val']) : 0;

    if ($basket_id > 0)
    {
		$temp=$db->getOne("select goods_id,goods_number from ".$ecs->table('user_basket'). "where basket_id='$basket_id'");
		
		//计算当前数量
		$good_weight=$db->getOne("select goods_weight from ".$ecs->table('goods')." where goods_id=$temp[goods_id]");
		$goods_num=$weight/$good_weight[0]*2;
		
		//更新菜蓝子
        $db->query('UPDATE  ' .$ecs->table('user_basket'). "SET goods_number=$goods_num WHERE basket_id='$basket_id'" );
		change_goods_storage($temp['goods_id'],'',$goods_num-$temp['goods_number']);
    }

   make_json_result($weight,"");
}
else 
{
   include_once(ROOT_PATH .'includes/lib_clips.php');
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    $tid = isset($_GET['tid']) ? trim($_GET['tid']) : '';
	$date=isset($_GET['date']) ? trim($_GET['date']) : '';
	$taocan = get_order_taocan($tid);

	$goods_taocan_list = get_goods_taocan_list($taocan['goods_id'],18, $pager['start'],$brand_id);
	$shipping_area=get_shipping_area();
	
	$brand_sql="SELECT * FROM ". $GLOBALS['ecs']->table('brand') ." WHERE brand_id IN (SELECT brand_id FROM ". $GLOBALS['ecs']->table('taocan_shop') ." AS t LEFT JOIN ". $GLOBALS['ecs']->table('goods') ." AS g ON t.goods_id = g.goods_id WHERE t.goods_taocan_id=$taocan[goods_id])";
	$brands=$db->getAll($brand_sql);
	
	$sqlps="select ps_id,address_id,order_taocan_id,ps_time from ". $GLOBALS['ecs']->table('peisong') ." where order_taocan_id=$tid and ps_time=$date";
	$peisong=$db->getRow($sqlps);
	
	if(!$peisong){
		
		$db->query("INSERT INTO ".$ecs->table('peisong')." (ps_time,user_id,order_taocan_id,address_id) VALUES ('$date','$taocan[user_id]','$tid','$taocan[peisong_area_id]')");		
		$peisong['ps_id']=$db->insert_id();
	}
	
	
	if(($date-43200)<time()){
		$yips=1;
	}
	
	//取得配送地点
	if($peisong['address_id']){
		$selectAddress=$peisong['address_id'];
		}
		else{
			$selectAddress=$taocan['peisong_area_id'];
		}
	$basket_list = get_basket_list($peisong['ps_id']);
	$smarty->assign('basket_list', $basket_list[1]);
	$smarty->assign('z_weight', $basket_list[0]);
	$smarty->assign('brands', $brands);
	$smarty->assign('shipping_area', $shipping_area);
	$smarty->assign('peisong', $peisong);
	$smarty->assign('psdate', date("m-d",$date));
	$smarty->assign('tid', $tid);
	$smarty->assign('yips', $yips);
	$smarty->assign('date', $date);
	$smarty->assign('action', 'default');
	$smarty->assign('selectAddress',$selectAddress);
	$smarty->assign('order_taocan',$taocan);
	$smarty->assign('full_page', 1);
	$smarty->assign('order_taocan_id', $peisong['order_taocan_id']);
	$smarty->assign('ps_id', $peisong['ps_id']);
	$smarty->assign('goods_taocan_list', $goods_taocan_list);
	$smarty->assign('bgcolor', $colors[$_POST['key']]);
    $smarty->display('user_gettaocan.html');
}

?>