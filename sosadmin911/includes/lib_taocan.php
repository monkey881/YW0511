<?php

/**
 * ECSHOP 管理中心商品相关函数
 * ============================================================================
 * * 版权所有 2005-2012 上海商派网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.ecshop.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: liubo $
 * $Id: lib_goods.php 17217 2011-01-19 06:29:08Z liubo $
 */

if (!defined('IN_ECS'))
{
    die('Hacking attempt');
}


/**
 * 获得商品列表
 *
 * @access  public
 * @params  integer $isdelete
 * @params  integer $real_goods
 * @params  integer $conditions
 * @return  array
 */
function taocan_list()
{

        /* 记录总数 */
        $sql = "SELECT COUNT(*) FROM " .$GLOBALS['ecs']->table('taocan');
        $filter['record_count'] = $GLOBALS['db']->getOne($sql);

        /* 分页大小 */
        $filter = page_and_size($filter);

        $sql = "SELECT *  FROM " . $GLOBALS['ecs']->table('taocan'). 
                    " ORDER BY tid ".
                    " LIMIT " . $filter['start'] . ",$filter[page_size]";

        $filter['keyword'] = stripslashes($filter['keyword']);
        set_filter($filter, $sql, $param_str);

    $row = $GLOBALS['db']->getAll($sql);

    return array('taocan' => $row, 'filter' => $filter, 'page_count' => $filter['page_count'], 'record_count' => $filter['record_count']);
}



?>