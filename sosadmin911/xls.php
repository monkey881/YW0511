<?php
    header("Content-type:application/vnd.ms-excel;");
    header("Content-Type:application/force-dowanload");
    define('IN_ECS', true);
    require(dirname(__FILE__) . '/includes/init.php');
    
    $nextpeitime = 0;
    //此处进行判断若是当天就是配送的时间，则下次配送时间就是今天
        //获取今天是星期几
        $today = mktime();
        $xinqi = date('w',$today);//获取该天的星期
        if($xinqi == 3 ||$xinqi == 6){//是配送的当天
            $nextpeitime = time0($today);
        }else{
            $nextpeitime = nextPeiTime($today);
        }
        $name = date('Y-m-d',$nextpeitime).'日订单';
     header("Content-Disposition:attachment;filename=$name.xls");
    $sql = "SELECT b.user_name,c.order_sn,d.goods_name,c.type,a.num,time_num,time_ship,location 
from ecs_taocanchoice as a,ecs_users as b,ecs_taocan as c,ecs_goods as d
WHERE a.time_ship=$nextpeitime and a.taocan_id =c.taocan_id and c.user_id = b.user_id and a.goods_id=d.goods_id and c.zhuangtai in(1,2)
GROUP BY a.goods_id,a.taocan_id
ORDER BY c.order_sn";
        $result_dingdan = $GLOBALS['db']->getAll($sql);
        
        
        
         function getendtime($starttime,$num){
        $endtime = 0;//配送结束的时间
        $start_xinqi = date('w',$starttime);//获取开始配送该天的星期
        switch($start_xinqi){
            case 3://星期三
                $endtime = $starttime+(($num/2-1)*7+3)*24*60*60;
                break;
            case 6://星期六
                $endtime = $starttime+(($num/2-1)*7+4)*24*60*60;
                break;
        }
        return $endtime;
    }

function nextPeiTime($time){//付款之后的套餐下一次开始配送的日期
        $xinqi = date('w',$time);//获取该天的星期
        switch($xinqi){
            case 0://星期天，星期三配送需要3天后
                $time +=3*24*60*60;
                break;
            case 1://星期一，星期三配送需要2天后
                $time +=2*24*60*60;
                break;
            case 2://星期二，星期三配送需要1天后
                $time +=1*24*60*60;
                break;
            case 3://星期三，星期六配送需要3天后
                $time +=3*24*60*60;
                break;
            case 4://星期四，星期六配送需要2天后
                $time +=2*24*60*60;
                break;
            case 5://星期五，星期六配送需要1天后
                $time +=1*24*60*60;
                break;
            case 6://星期六，星期三配送需要4天后
                $time +=4*24*60*60;
                break;
            
        }
        return time0($time);
    }
    function time0($time){//获取零点UNIX时间戳
        return $time - $time % 86400 - date('O') * 36;
    }
     function getNameByType($type){//根据类型获得套餐名称
        $name = '';
        switch($type){
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
?>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <table border="1">
            <thead>
                <tr>
                    <td>客户</td>
                    <td>订单号</td>
                    <td>时间</td>
                    <td>套餐种类</td>
                    <td>商品</td>
                    <td>数量</td>
                    <td>地点</td>
                    <td>配送次数</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($result_dingdan as $value){?>
                <tr>
                    <td><?php echo $value['user_name']?></td>
                    <td><?php echo $value['order_sn']?></td>
                    <td><?php echo date('Y-m-d',$value['time_ship'])?></td>
                    <td><?php echo getNameByType($value['type'])?></td>
                    <td><?php echo $value['goods_name']?></td>
                    <td><?php echo $value['num']?></td>
                    <td> <?php echo $value['location']?></td>
                    <td> <?php echo $value['time_num']?></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    