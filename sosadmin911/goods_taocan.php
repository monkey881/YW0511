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
require_once(ROOT_PATH . 'languages/' .$_CFG['lang']. '/admin/goods.php');
$image = new cls_image($_CFG['bgcolor']);
$exc = new exchange($ecs->table('goods'), $db, 'goods_id', 'goods_name');

/*------------------------------------------------------ */
//-- 商品列表，商品回收站
/*------------------------------------------------------ */

if ($_REQUEST['act'] == 'list' )
{
    admin_priv('goods_manage');
	$tid=empty($_REQUEST['tid']) ? 0 : intval($_REQUEST['tid']);

   
  	
    /* 模板赋值 */
    $goods_ur = array('' => $_LANG['01_goods_list'], 'virtual_card'=>$_LANG['50_virtual_card_list']);
    $ur_here = ($_REQUEST['act'] == 'list') ? $goods_ur[$code] : $_LANG['11_goods_trash'];
    $smarty->assign('ur_here', $ur_here);

   
   $smarty->assign('tid',     $tid);
    $smarty->assign('cat_list',     cat_list(0, $cat_id));
    $smarty->assign('brand_list',   get_brand_list());
    $smarty->assign('intro_list',   get_intro_list());
    $smarty->assign('lang',         $_LANG);
    $smarty->assign('list_type',    $_REQUEST['act'] == 'list' ? 'goods' : 'trash');
    $smarty->assign('use_storage',  empty($_CFG['use_storage']) ? 0 : 1);

    
    $goods_list = goods_list($_REQUEST['act'] == 'list' ? 0 : 1, ($_REQUEST['act'] == 'list') ? (($code == '') ? 1 : 0) : -1);
	
	
    $smarty->assign('goods_list',   $goods_list['goods']);
    $smarty->assign('filter',       $goods_list['filter']);
    $smarty->assign('record_count', $goods_list['record_count']);
    $smarty->assign('page_count',   $goods_list['page_count']);
    $smarty->assign('full_page',    1);

    /* 排序标记 */
    $sort_flag  = sort_flag($goods_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    /* 获取商品类型存在规格的类型 */
    $specifications = get_goods_type_specifications();
    $smarty->assign('specifications', $specifications);

    /* 显示商品列表页面 */
    assign_query_info();
    $htm_file = ($_REQUEST['act'] == 'list') ?
        'goods_list_taocan.htm' : (($_REQUEST['act'] == 'trash') ? 'goods_trash.htm' : 'goods_list_taocan.htm');
    $smarty->display($htm_file);
}

else if ($_REQUEST['act'] == 'shoplist'||$_REQUEST['act'] == 'addshop')
{
    admin_priv('goods_manage');
	$id=empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);

   
  	if($id ){
		if($_REQUEST['act'] == 'addshop')
		{
			$sql = "SELECT goods_id, goods_name,goods_thumb,shop_price,goods_number 
		 FROM " . $GLOBALS['ecs']->table('goods') . "  where goods_id NOT IN (select goods_id from ". $GLOBALS['ecs']->table('taocan_shop') ." where goods_taocan_id=$id)  ORDER BY add_time DESC";
		}
		else
		{
			
			$sql = "SELECT g.goods_id, g.goods_name,g.goods_thumb,g.shop_price,g.goods_number,t.num
		 FROM " . $GLOBALS['ecs']->table('goods') . " g  LEFT JOIN ". $GLOBALS['ecs']->table('taocan_shop') ." t ON g.goods_id=t.goods_id where t.goods_taocan_id=$id  ORDER BY t.num DESC";
		}
        
					
	$row = $GLOBALS['db']->getAll($sql);	
		
	}
    /* 模板赋值 */
    $goods_ur = array('' => $_LANG['01_goods_list'], 'virtual_card'=>$_LANG['50_virtual_card_list']);
    $ur_here = ($_REQUEST['act'] == 'shoplist') ? '套餐商品管理' : '添加套餐商品';
    $smarty->assign('ur_here', $ur_here);
	
    $action_link =($_REQUEST['act'] == 'shoplist') ?array('href' => 'goods_taocan.php?act=addshop&id='.$id, 'text' => '添加套餐商品'):'';
    $smarty->assign('action_link',  $action_link);
   $smarty->assign('id',     $id);
    $smarty->assign('cat_list',     cat_list(0, $cat_id));
    $smarty->assign('brand_list',   get_brand_list());
    $smarty->assign('intro_list',   get_intro_list());
    $smarty->assign('lang',         $_LANG);
    $smarty->assign('list_type',    $_REQUEST['act'] == 'list' ? 'goods' : 'trash');
    $smarty->assign('use_storage',  empty($_CFG['use_storage']) ? 0 : 1);

    
	$smarty->assign('act',   $_REQUEST['act'] );
    $smarty->assign('goods_list',   $row);
    $smarty->assign('filter',       $goods_list['filter']);
    $smarty->assign('record_count', $goods_list['record_count']);
    $smarty->assign('page_count',   $goods_list['page_count']);
    $smarty->assign('full_page',    1);

    /* 排序标记 */
    $sort_flag  = sort_flag($goods_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    /* 获取商品类型存在规格的类型 */
    $specifications = get_goods_type_specifications();
    $smarty->assign('specifications', $specifications);

    /* 显示商品列表页面 */
    assign_query_info();
    $htm_file =     'taocan_shoplist.htm' ;
    $smarty->display($htm_file);
}
else if ($_REQUEST['act'] == 'saveshop' || $_REQUEST['act'] == 'updata')
{
	admin_priv('goods_manage');
	$id=empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	$goods_ids=empty($_POST['checkboxes']) ? 0 : $_POST['checkboxes'];
	if($goods_ids){
		
		foreach($goods_ids as $goods_id){
			$num =empty($_REQUEST['num'.$goods_id]) ? 0 : intval($_REQUEST['num'.$goods_id]);
			$arr['goods_id']=$goods_id;
			$arr['goods_taocan_id']=$id;
			$arr['num']=$num;
			if($_REQUEST['act'] == 'saveshop'){
				$mode='INSERT';
				$GLOBALS['db']->autoExecute($ecs->table('taocan_shop'),$arr) ;
			}
			else{
				$where="goods_id=$goods_id AND goods_taocan_id=$id";
				if($_REQUEST['chkDel']){
					$GLOBALS['db']->query("DELETE FROM ". $GLOBALS['ecs']->table('taocan_shop')." WHERE $where");
				}
				else{
				$mode='UPDATE';
				$GLOBALS['db']->autoExecute($ecs->table('taocan_shop'), $arr,$mode,$where) ;
				}
			}
			
			
    
	}
	
        
       
        /*添加链接*/
        $link[0]['text'] = '反回套餐';
        $link[0]['href'] = 'goods_taocan.php?act=shoplist&id='.$id;

     
        sys_msg('套餐商品添加成功', 0, $link);
    
	}
	else{
		  /*添加链接*/
        $link[0]['text'] = '反回套餐';
        $link[0]['href'] = 'goods_taocan.php?act=shoplist&id='.$id;

     
        sys_msg('请选择操作', 0, $link);
	}
}

else if ($_REQUEST['act'] == 'query')
{
    admin_priv('goods_manage');
	$id=empty($_REQUEST['id']) ? 0 : intval($_REQUEST['id']);
	
	$is_delete = empty($_REQUEST['is_delete']) ? 0 : intval($_REQUEST['is_delete']);
    $code = empty($_REQUEST['extension_code']) ? '' : trim($_REQUEST['extension_code']);
    $goods_list = goods_list($is_delete, ($code=='') ? 1 : 0);
    /* 模板赋值 */
    $goods_ur = array('' => $_LANG['01_goods_list'], 'virtual_card'=>$_LANG['50_virtual_card_list']);
    $ur_here = ($_REQUEST['act'] == 'shoplist') ? '套餐商品管理' : '添加套餐商品';
    $smarty->assign('ur_here', $ur_here);
	
    $action_link =($_REQUEST['act'] == 'shoplist') ?array('href' => 'goods_taocan.php?act=addshop&id='.$id, 'text' => '添加套餐商品'):'';
    $smarty->assign('action_link',  $action_link);
    $smarty->assign('id',     $id);
    $smarty->assign('cat_list',     cat_list(0, $cat_id));
    $smarty->assign('brand_list',   get_brand_list());
    $smarty->assign('intro_list',   get_intro_list());
    $smarty->assign('lang',         $_LANG);
    $smarty->assign('list_type',    $_REQUEST['act'] == 'list' ? 'goods' : 'trash');
    $smarty->assign('use_storage',  empty($_CFG['use_storage']) ? 0 : 1);

    
	$smarty->assign('act',   $_REQUEST['act'] );
    $smarty->assign('goods_list',   $goods_list['goods']);
    $smarty->assign('filter',       $goods_list['filter']);
    $smarty->assign('record_count', $goods_list['record_count']);
    $smarty->assign('page_count',   $goods_list['page_count']);
  

    /* 排序标记 */
    $sort_flag  = sort_flag($goods_list['filter']);
    $smarty->assign($sort_flag['tag'], $sort_flag['img']);

    /* 获取商品类型存在规格的类型 */
    $specifications = get_goods_type_specifications();
    $smarty->assign('specifications', $specifications);

    /* 显示商品列表页面 */
    assign_query_info();
    $htm_file =     'taocan_shoplist.htm' ;
     make_json_result($smarty->fetch($htm_file), '',
        array('filter' => $goods_list['filter'], 'page_count' => $goods_list['page_count']));
}
/**
 * 列表链接
 * @param   bool    $is_add         是否添加（插入）
 * @param   string  $extension_code 虚拟商品扩展代码，实体商品为空
 * @return  array('href' => $href, 'text' => $text)
 */
function list_link($is_add = true, $extension_code = '')
{
    $href = 'goods.php?act=list';
    if (!empty($extension_code))
    {
        $href .= '&extension_code=' . $extension_code;
    }
    if (!$is_add)
    {
        $href .= '&' . list_link_postfix();
    }

    if ($extension_code == 'virtual_card')
    {
        $text = $GLOBALS['_LANG']['50_virtual_card_list'];
    }
    else
    {
        $text = $GLOBALS['_LANG']['01_goods_list'];
    }

    return array('href' => $href, 'text' => $text);
}

/**
 * 添加链接
 * @param   string  $extension_code 虚拟商品扩展代码，实体商品为空
 * @return  array('href' => $href, 'text' => $text)
 */
function add_link($extension_code = '')
{
    $href = 'goods.php?act=add';
    if (!empty($extension_code))
    {
        $href .= '&extension_code=' . $extension_code;
    }

    if ($extension_code == 'virtual_card')
    {
        $text = $GLOBALS['_LANG']['51_virtual_card_add'];
    }
    else
    {
        $text = $GLOBALS['_LANG']['02_goods_add'];
    }

    return array('href' => $href, 'text' => $text);
}

/**
 * 检查图片网址是否合法
 *
 * @param string $url 网址
 *
 * @return boolean
 */
function goods_parse_url($url)
{
    $parse_url = @parse_url($url);
    return (!empty($parse_url['scheme']) && !empty($parse_url['host']));
}

/**
 * 保存某商品的优惠价格
 * @param   int     $goods_id    商品编号
 * @param   array   $number_list 优惠数量列表
 * @param   array   $price_list  价格列表
 * @return  void
 */
function handle_volume_price($goods_id, $number_list, $price_list)
{
    $sql = "DELETE FROM " . $GLOBALS['ecs']->table('volume_price') .
           " WHERE price_type = '1' AND goods_id = '$goods_id'";
    $GLOBALS['db']->query($sql);


    /* 循环处理每个优惠价格 */
    foreach ($price_list AS $key => $price)
    {
        /* 价格对应的数量上下限 */
        $volume_number = $number_list[$key];

        if (!empty($price))
        {
            $sql = "INSERT INTO " . $GLOBALS['ecs']->table('volume_price') .
                   " (price_type, goods_id, volume_number, volume_price) " .
                   "VALUES ('1', '$goods_id', '$volume_number', '$price')";
            $GLOBALS['db']->query($sql);
        }
    }
}

/**
 * 修改商品库存
 * @param   string  $goods_id   商品编号，可以为多个，用 ',' 隔开
 * @param   string  $value      字段值
 * @return  bool
 */
function update_goods_stock($goods_id, $value)
{
    if ($goods_id)
    {
        /* $res = $goods_number - $old_product_number + $product_number; */
        $sql = "UPDATE " . $GLOBALS['ecs']->table('goods') . "
                SET goods_number = goods_number + $value,
                    last_update = '". gmtime() ."'
                WHERE goods_id = '$goods_id'";
        $result = $GLOBALS['db']->query($sql);

        /* 清除缓存 */
        clear_cache_files();

        return $result;
    }
    else
    {
        return false;
    }
}
?>