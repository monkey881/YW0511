<?php
    define('IN_ECS', true);
    require(dirname(__FILE__) . '/includes/init.php');
     include_once('includes/cls_json.php');
    $json = new JSON;
    $user_id = (int)($_SESSION['user_id']);
    $action = $_POST['action'];
   if($action =='taocan_info'){//点击某个套餐查看该套餐的具体信息
        include_once('includes/cls_json.php');
        $json = new JSON;
        $taocan_id = (int)$_POST['taocan_id'];
        $sql = "SELECT `type`,`paytime` FROM `ecs_taocan` WHERE `taocan_id`=$taocan_id AND `user_id`=$user_id";
        $arr = $GLOBALS['db']->getAll($sql);
        $name = getNameByType($arr[0]['type']);//配送的套餐类型
         $num = ($arr[0]['type']%10)*8;//配送的总次数
        $starttime = nextPeiTime($arr[0]['paytime']);//配送开始时间
        $endtime = 0;//配送结束的时间
        $time_ship =array();//用来保存每次配送的时间
        $start_xinqi = date('w',$starttime);//获取开始配送该天的星期
        switch($start_xinqi){
            case 3://星期三
                $endtime = $starttime+(($num/2-1)*7+3)*24*60*60;
                $time_ship[0] = $starttime;
                for($i=1;$i<=$num-1;$i++){
                    if($i%2 ==0){ 
                        $time_ship[$i]=$time_ship[$i-1]+4*24*60*60;
                    }else{
                        $time_ship[$i]=$time_ship[$i-1]+3*24*60*60;
                    }
                }
                break;
            case 6://星期六
                $endtime = $starttime+(($num/2-1)*7+4)*24*60*60;
                $time_ship[0] = $starttime;
                for($i=1;$i<=$num-1;$i++){
                    if($i%2 ==0){ 
                        $time_ship[$i]=$time_ship[$i-1]+3*24*60*60;
                    }else{
                        $time_ship[$i]=$time_ship[$i-1]+4*24*60*60;
                    }
                }
                break;
        }
       
        
        $result = array();
        $result["time_ship"] = $time_ship;
        $result["name"] = $name;
        $result["num"] = $num;
        $result["starttime"] = $starttime;
        $result["endtime"] = $endtime;
       die($json->encode($result));
        
    }elseif($action == 'display_info'){//显示配送信息
        include_once('includes/cls_json.php');
        $json = new JSON;
        $peiTime = (int)$_POST['peiTime'];//获取点击的是那一次配送
        $type = $_POST['type'];//获取套餐类型
        $taocan_id = $_POST['taocan_id'];//获取套餐ＩＤ
        $time_ship = $_POST['time_ship'];
        $month_ship = date('n',$time_ship);//计算本次配送的月份
        $sql = "SELECT a.`goods_id`, a.`goods_name`,a.goods_thumb FROM `ecs_goods` AS a ,`ecs_taocan_fixed` AS b WHERE 
a.`goods_id` = b.goods_id AND b.`month`=$month_ship AND b.type=$type";
        $arr_fixed = $GLOBALS['db']->getAll($sql);
        $sql = "SELECT a.`goods_id`, a.`goods_name`,a.goods_thumb FROM `ecs_goods` AS a ,`ecs_taocan_choiceable` AS b WHERE 
a.`goods_id` = b.goods_id AND b.`month`=$month_ship AND b.type=$type order by a.goods_id asc";
        $arr_choiceable = $GLOBALS['db']->getAll($sql);
        //获取已经选择的套餐情况
        $sql= "select location,goods_id,num from ecs_taocanchoice where taocan_id = $taocan_id and time_num=$peiTime group by goods_id order by goods_id asc";
        $arr_selected = $GLOBALS['db']->getAll($sql);
        $result = array();
        $result["arr_selected"] =array();
        $result["arr_selected_num"] = array();
        foreach($arr_selected as $value){
            $result["arr_selected"][]=$value["goods_id"];
            $result["arr_selected_num"][]=$value["num"];
        }
        $result["location"] = $arr_selected[0]["location"];
        $result["choiceable_num"] = ((int)($type/10))*2;//获取可选商品数目
        $result["arr_fixed"] = $arr_fixed;
        $result["arr_choiceable"] = $arr_choiceable;
        die($json->encode($result));//AJAX返回信息
        
    }elseif($action == 'update_info'){//更新配送信息
        include_once('includes/cls_json.php');
        $json = new JSON;
        $goods_id = $_POST['goods_id'];//获取用户自己选择的商品ID
        $taocan_id = $_POST['taocan_id'];//获取套餐ＩＤ
        $time_num = $_POST['time_num'];//获取是第几次配送
        $time_ship = $_POST['time_ship'];//获取配送的时间
        $location = $_POST['location'];//获取本次配送的地址
        $sql = "DELETE FROM `ecs_taocanchoice` WHERE `taocan_id`=$taocan_id AND `time_num`=$time_num AND `time_ship`=$time_ship";//先删除后更新
        if(!$GLOBALS['db']->query($sql)){
            $result = '异常错误！';
             die($json->encode($result));
        }else{//插入新的数据
            foreach($goods_id as $value){
				$temp = explode("#",$value);
                $sql = "INSERT INTO `ecs_taocanchoice`(`taocan_id`,`goods_id`,`time_num`,`time_ship`,`location`,`num`)VALUES($taocan_id,$temp[0],$time_num,$time_ship,'$location','$temp[1]')";
                if(!$GLOBALS['db']->query($sql)){
                    $result = '异常错误！';
                    die($json->encode($result));
                }else{
                    $result = '更新成功！';
                }
            }
            die($json->encode($result));

        }
    }





//以下是套餐二次开发

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
    
    
    //按照某个状态获取某用户的套餐信息
    //参数$user_id 用户ＩＤ，状态编号-1表示没有付款 1表示已经付款没有开始配送 2表示正在配送中 3表示已经配送结束
    function get_taocan($user_id,$zhuangtai){
        $sql = "SELECT `taocan_id`,`order_sn`,`type`,`zhuangtai`,`paytime` FROM `ecs_taocan` WHERE `user_id`=$user_id AND `zhuangtai`=$zhuangtai";
        $arr = $GLOBALS['db']->getAll($sql);
        return $arr;
    }
?>