<?php

/**
 * ECSHOP 会员中心
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: user.php 17217 2011-01-19 06:29:08Z liubo $
*/

define('IN_ECS', true);

require(dirname(__FILE__) . '/includes/init.php');
include_once(ROOT_PATH . '/includes/cls_image.php');
$image = new cls_image($_CFG['bgcolor']);
/* 载入语言文件 */
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/user.php');

$user_id = $_SESSION['user_id'];
$action  = isset($_REQUEST['act']) ? trim($_REQUEST['act']) : 'default';

$affiliate = unserialize($GLOBALS['_CFG']['affiliate']);
$smarty->assign('affiliate', $affiliate);
$back_act='';

$colors=array('#FFBCEF','#FFD8DB','#AAA8FF','#FFFEAB','#FFAF97','#DDEAFF','#78CCCB','#A7DEFF','#D1C893','#C4EBB9');

// 不需要登录的操作或自己验证是否登录（如ajax处理）的act
$not_login_arr =
array('login','act_login','register','act_register','act_edit_password','get_password','send_pwd_email','password', 'signin', 'add_tag', 'collect', 'return_to_cart', 'logout', 'email_list', 'validate_email', 'send_hash_mail', 'order_query', 'is_registered', 'check_email','clear_history','qpassword_name', 'get_passwd_question', 'check_answer');

/* 显示页面的action列表 */
$ui_arr = array('default', 'gettaocan','addshop','delshop','consignee','edit_mygood_num');

/**
 * 获得所有的友情链接
 *
 * @access  private
 * @return  array
 */
function index_get_links()
{
    $sql = 'SELECT link_logo, link_name, link_url FROM ' . $GLOBALS['ecs']->table('friend_link') . ' ORDER BY show_order';
    $res = $GLOBALS['db']->getAll($sql);

    $links['img'] = $links['txt'] = array();

    foreach ($res AS $row)
    {
        if (!empty($row['link_logo']))
        {
            $links['img'][] = array('name' => $row['link_name'],
                                    'url'  => $row['link_url'],
                                    'logo' => $row['link_logo']);
        }
        else
        {
            $links['txt'][] = array('name' => $row['link_name'],
                                    'url'  => $row['link_url']);
        }
    }

    return $links;
}



/* links */
$links = index_get_links();
$smarty->assign(‘img_links’, $links['img']);
$smarty->assign(‘txt_links’, $links['txt']); 


/* 未登录处理 */
if (empty($_SESSION['user_id']))
{
    if (!in_array($action, $not_login_arr))
    {
        if (in_array($action, $ui_arr))
        {
            /* 如果需要登录,并是显示页面的操作，记录当前操作，用于登录后跳转到相应操作
            if ($action == 'login')
            {
                if (isset($_REQUEST['back_act']))
                {
                    $back_act = trim($_REQUEST['back_act']);
                }
            }
            else
            {}*/
            if (!empty($_SERVER['QUERY_STRING']))
            {
                $back_act = 'user.php?' . strip_tags($_SERVER['QUERY_STRING']);
            }
            $action = 'login';
        }
        else
        {
            //未登录提交数据。非正常途径提交数据！
            die($_LANG['require_login']);
        }
    }
}

/* 如果是显示页面，对页面进行相应赋值 */
if (in_array($action, $ui_arr))
{
    assign_template();
    $position = assign_ur_here(0, $_LANG['user_center']);
    $smarty->assign('page_title', $position['title']); // 页面标题
    $smarty->assign('ur_here',    $position['ur_here']);
    $sql = "SELECT value FROM " . $ecs->table('shop_config') . " WHERE id = 419";
    $row = $db->getRow($sql);
    $car_off = $row['value'];
    $smarty->assign('car_off',       $car_off);
    /* 是否显示积分兑换 */
    if (!empty($_CFG['points_rule']) && unserialize($_CFG['points_rule']))
    {
        $smarty->assign('show_transform_points',     1);
    }
    $smarty->assign('helps',      get_shop_help());        // 网店帮助
    $smarty->assign('data_dir',   DATA_DIR);   // 数据目录
    $smarty->assign('action',     $action);
	$smarty->assign('tid',     $_GET['tid']);
    $smarty->assign('lang',       $_LANG);
	$smarty->assign('colors', $colors);
}

//用户中心欢迎页
if ($action == 'default')
{
    include_once(ROOT_PATH .'includes/lib_clips.php');
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    $tid = isset($_GET['tid']) ? trim($_GET['tid']) : '';
	$date=isset($_GET['date']) ? trim($_GET['date']) : '';
	$taocan = get_order_taocan($tid);

	$goods_taocan_list = get_goods_taocan_list($taocan['goods_id'],18, $pager['start'],$brand_id);
	$shipping_area=get_shipping_area();
	
	$brand_sql="SELECT * FROM ". $GLOBALS['ecs']->table('brand') ." WHERE brand_id IN (SELECT brand_id FROM ". $GLOBALS['ecs']->table('taocan_shop') ." AS t LEFT JOIN ". $GLOBALS['ecs']->table('goods') ." AS g ON t.goods_id = g.goods_id WHERE t.goods_taocan_id=$taocan[goods_id])";
	$sqlps="select ps_id,address_id,order_taocan_id,ps_time from ". $GLOBALS['ecs']->table('peisong') ." where order_taocan_id=$tid and ps_time=$date";
	$peisong=$db->getRow($sqlps);
	if(!$peisong){
		
		$db->query("INSERT INTO ".$ecs->table('peisong')." (ps_time,user_id,order_taocan_id,address_id,shipping_id) VALUES ('$date','$user_id','$tid','$taocan[peisong_address_id]','$taocan[shipping_id]')");		
		$peisong['ps_id']=$db->insert_id();
	}
	$brands=$db->getAll($brand_sql);
	if($taocan['shipping_id']==5){
		$address=$db->getRow("SELECT * FROM ".$ecs->table('user_address')." WHERE address_id=".$taocan[peisong_address_id]);
		$smarty->assign('address', $address);
	}
	
	
	if(($date-43200)<time() ){
		$yips=1;
	}
	
	
	
	$basket_list = get_basket_list($peisong['ps_id']);
	$smarty->assign('basket_list', $basket_list[1]);
	$smarty->assign('z_weight', $basket_list[0]);
	$smarty->assign('brands', $brands);
	$smarty->assign('shipping_area', $shipping_area);
	$smarty->assign('peisong', $peisong);
	$smarty->assign('psdate', date("m-d",$peisong['ps_time']));
	$smarty->assign('tid', $tid);
	$smarty->assign('yips', $yips);
	$smarty->assign('date', $date);
	$smarty->assign('selectAddress',$peisong['address_id']);
	$smarty->assign('order_taocan',$taocan);
	$smarty->assign('full_page', 1);
	$smarty->assign('order_taocan_id', $peisong['order_taocan_id']);
	$smarty->assign('ps_id', $peisong['ps_id']);
	$smarty->assign('goods_taocan_list', $goods_taocan_list);
	$smarty->assign('bgcolor', $colors[$_POST['key']]);
    $smarty->display('user_gettaocan.dwt');
}

/* 显示会员注册界面 */
else if ($action == 'gettaocan')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
	$brand_id = isset($_POST['brand_id']) ? trim($_POST['brand_id']) : '';
    $tid = isset($_POST['tid']) ? trim($_POST['tid']) : '';
	$taocan = get_order_taocan($tid);
	
	
   	$goods_taocan_list = get_goods_taocan_list($taocan['goods_id'],18, $pager['start'],$brand_id);
	$smarty->assign('goods_taocan_list', $goods_taocan_list);
	$smarty->assign('bgcolor', $colors[$_POST['key']]);
	 $tpl = 'user_gettaocan.dwt';

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
		if($user_id!=6){
		change_goods_storage($goods_id,'',-$num);
		}
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
	 $tpl = 'user_gettaocan.dwt';
	
	
    make_json_result($smarty->fetch($tpl),$weight_mess);
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
		if($user_id!=6){
		change_goods_storage($temp['goods_id'],'',$temp['goods_number']);
		}
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
	$tpl = 'user_gettaocan.dwt';

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
		$temp=$db->getRow("select goods_id,goods_number,ps_id from ".$ecs->table('user_basket'). "where basket_id='$basket_id'");
		
		$good_weight=$db->getOne("select goods_weight from ".$ecs->table('goods')." where goods_id=".$temp['goods_id']);
		
		$goods_num=$weight/($good_weight*2);
		
		
		
        $db->query('UPDATE  ' .$ecs->table('user_basket'). "SET goods_number=$goods_num WHERE basket_id='$basket_id'" );
		//if($user_id!=6){
		//change_goods_storage($temp['goods_id'],'',$goods_num-$temp['goods_number']);
		//}
    }
	
	$basket_list = get_basket_list($temp['ps_id']);

   $smarty->assign('shipping_area', $shipping_area);
	$smarty->assign('basket_list', $basket_list[1]);
	$smarty->assign('z_weight', $basket_list[0]);
	$tpl = 'user_gettaocan.dwt';

    make_json_result($smarty->fetch($tpl));
}
elseif ($action == 'changeAddress')
{
	include_once(ROOT_PATH . 'includes/lib_transaction.php');
    $ps_id = isset($_POST['ps_id']) ? intval($_POST['ps_id']) : 0;
	$addres_id = isset($_POST['addres_id']) ? intval($_POST['addres_id']) : 0;

    if ($ps_id > 0)
    {
		
        $db->query('UPDATE  ' .$ecs->table('peisong'). "SET address_id=$addres_id WHERE ps_id='$ps_id'" );
		
    }

   
}
else if ($action=='checkout'||$action=='consignee'){
	/*------------------------------------------------------ */
    //-- 收货人信息
    /*------------------------------------------------------ */
    include_once('includes/lib_transaction.php');

    if ($_SERVER['REQUEST_METHOD'] == 'GET')
    {
        
        /*
         * 收货人信息填写界面
         */

        if (isset($_REQUEST['direct_shopping']))
        {
            $_SESSION['direct_shopping'] = 1;
        }

        /* 取得国家列表、商店所在国家、商店所在国家的省列表 */
        $smarty->assign('country_list',       get_regions());
        $smarty->assign('shop_country',       $_CFG['shop_country']);
        $smarty->assign('shop_province_list', get_regions(1, $_CFG['shop_country']));

        /* 获得用户所有的收货人信息 */
        if ($_SESSION['user_id'] > 0)
        {
            $consignee_list = get_consignee_list($_SESSION['user_id']);

            if (count($consignee_list) < 5)
            {
                /* 如果用户收货人信息的总数小于 5 则增加一个新的收货人信息 */
                $consignee_list[] = array('country' => $_CFG['shop_country'], 'email' => isset($_SESSION['email']) ? $_SESSION['email'] : '');
            }
        }
        else
        {
            if (isset($_SESSION['flow_consignee'])){
                $consignee_list = array($_SESSION['flow_consignee']);
            }
            else
            {
                $consignee_list[] = array('country' => $_CFG['shop_country']);
            }
        }
        $smarty->assign('name_of_region',   array($_CFG['name_of_region_1'], $_CFG['name_of_region_2'], $_CFG['name_of_region_3'], $_CFG['name_of_region_4']));
        $smarty->assign('consignee_list', $consignee_list);

        /* 取得每个收货地址的省市区列表 */
        $province_list = array();
        $city_list = array();
        $district_list = array();
        foreach ($consignee_list as $region_id => $consignee)
        {
            $consignee['country']  = isset($consignee['country'])  ? intval($consignee['country'])  : 0;
            $consignee['province'] = isset($consignee['province']) ? intval($consignee['province']) : 0;
            $consignee['city']     = isset($consignee['city'])     ? intval($consignee['city'])     : 0;

            $province_list[$region_id] = get_regions(1, $consignee['country']);
            $city_list[$region_id]     = get_regions(2, $consignee['province']);
            $district_list[$region_id] = get_regions(3, $consignee['city']);
			if($consignee['district']){
		$area_list[$region_id] = get_area($consignee['district']);
		}
        }
		 $smarty->assign('area_list',          $area_list);
		 $smarty->assign('taocan',          1);
        $smarty->assign('province_list', $province_list);
        $smarty->assign('city_list',     $city_list);
        $smarty->assign('district_list', $district_list);
		$smarty->display('user_gettaocan.dwt');
       
    }
    else
    {
        /*
         * 保存收货人信息
         */
		 $tid=empty($_POST['tid']) ? 0  :   intval($_POST['tid']);
		 $date=empty($_POST['date']) ? 0  :   intval($_POST['date']);
        $consignee = array(
            'address_id'    => empty($_POST['address_id']) ? 0  :   intval($_POST['address_id']),
            'consignee'     => empty($_POST['consignee'])  ? '' :   compile_str(trim($_POST['consignee'])),
            'country'       => empty($_POST['country'])    ? '' :   intval($_POST['country']),
            'province'      => empty($_POST['province'])   ? '' :   intval($_POST['province']),
            'city'          => empty($_POST['city'])       ? '' :   intval($_POST['city']),
            'district'      => empty($_POST['district'])   ? '' :   intval($_POST['district']),
			'ps_shiping'      => empty($_POST['shipping_area'])   ? '' :   intval($_POST['shipping_area']),
            'email'         => empty($_POST['email'])      ? '' :   compile_str($_POST['email']),
            'address'       => empty($_POST['address'])    ? '' :   compile_str($_POST['address']),
            'zipcode'       => empty($_POST['zipcode'])    ? '' :   compile_str(make_semiangle(trim($_POST['zipcode']))),
            'tel'           => empty($_POST['tel'])        ? '' :   compile_str(make_semiangle(trim($_POST['tel']))),
            'mobile'        => empty($_POST['mobile'])     ? '' :   compile_str(make_semiangle(trim($_POST['mobile']))),
            'sign_building' => empty($_POST['sign_building']) ? '' :compile_str($_POST['sign_building']),
            'best_time'     => empty($_POST['best_time'])  ? '' :   compile_str($_POST['best_time']),
        );

        if ($_SESSION['user_id'] > 0)
        {
            include_once(ROOT_PATH . 'includes/lib_transaction.php');

            /* 如果用户已经登录，则保存收货人信息 */
            $consignee['user_id'] = $_SESSION['user_id'];

            save_consignee($consignee, true);
			$GLOBALS['db']->query("UPDATE ".$GLOBALS['ecs']->table('order_taocan')." SET peisong_address_id=".$_POST['address_id']." WHERE tid=$tid");
        }

        /* 保存到session */
        $_SESSION['flow_consignee'] = stripslashes_deep($consignee);

        ecs_header("Location: gettaocan.php?date=$date&tid=$tid\n");
        exit;
    }
}
?>
