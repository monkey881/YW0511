<div class="container">
<div class="taocan_left">
        <div class="taocan_now">
            <p>当前套餐</p>
                
                <p style="display:none;" class="taocan_type"><?php echo $_REQUEST['type']?></p> <p  class="taocan_id" style="display:none;"><?php echo $_REQUEST['id']?></p>
               
        </div>
        <div class="taocan_display">
        <div class="taocan_info">
            <p class="p_first"><span>当前套餐类型</span><span>开始配送时间</span><span>结束配送时间</span></p>
            <p class="p_second"><span></span><span></span><span></span></p>
            <p class="p_third">共分<span style="font-size:17px;color:red;">8</span>次/月*<span style="font-size:17px;color:green;"></span>月=<span style="font-size:18px;color:purple;"></span>次配送</p>
        </div>
        <div class="taocan_nums">
           
        </div>
            <div class="taocan_location">
            <p>请选择蔬菜的领取点</p>
                <span>江南世家1楼门卫</span><span>香江花城响当当超市</span><span>万科物管中心</span>
                <span>火车站山林苑</span><span>老房管局体彩店</span>
            </div>
        </div>
    </div>
    <div class="taocan_right">
        
        <div class="fixed">
           
        </div>
        <div class="nofixed">
            
           
        </div>
        <div><p class="taocan_update">更新套餐配置</p></div>
    </div>
    <div style="clear:both"></div>
    </div>
    </div>
    <style>
        .container{
            border:0;
    font-weight:inherit;
    font-style:inherit;
    font-family:inherit;
    vertical-align:baseline;
    list-style:none;
    font-size:12px; font-family:"微软雅黑","宋体",Verdana, Arial;
        }
        .taocan_left{
            width:425px;
            float:left;
            display:inline;
        }
        .taocan_right{
            width:320px;
            float:left;
            display:inline;
        }
        .taocan_left .taocan_now span, .taocan_past span, .taocan_invalid span{
            font-size:16px;
            width:67px;
            height:27px;
            margin-left:2px;
            padding:2px;
            display:inline-block;
            cursor:pointer;
        }
        .taocan_now span{
             background:#FFBF85;
             color:white;
        }
        .taocan_past span{
            background:#819BAD;
             color:white;
        }
        .taocan_invalid span{
            background:#BC831F;
             color:white;
        }
        .taocan_now p ,.taocan_past p,.taocan_invalid p{
            font-size:15px;    
        }
        .taocan_now p{
            color:#FD7F02;
        }
        .taocan_past p{
            color:#819BAD;
        }
        .taocan_invalid p{
            color:#BC831F;
        }
        .taocan_info{
            margin-top:15px;
        }
        .taocan_info .p_first span,.taocan_info .p_second span,.taocan_location span,.taocan_update{
            display:inline-block;
            margin-left:2px;
            margin-bottom:3px;
            width:115px;
            height30px;
            background:#F2B5A0;
            text-align:center;
            cursor:pointer;
        }
        .p_third{
            vertical-align:middle;
        }
        .span_past, .span_ing, .span_feature{
            margin-left:5px;
            margin-bottom:5px;
            display:inline-block;
            width:60px;
            height:60px;
            text-align:center;
            cursor:pointer;
            border:2px solid white;
        }
        .taocan_nums span p{
            height:1px;
        }
        .p_num{
            text-align: left;
            text-indent: 3px;
            font-size: 10px;
        }
        .p_date_hidden{
            display:none;
        }
        .span_past{
             background:gray;
        }
        .span_ing{
             background:green;
        }
        .span_feature{
             background:yellow;
        }
        .taocan_right .span_fixed ,.span_choiceable{
            margin-left:2px;
            margin-bottom:40px;
            display:inline-block;
            width:100px;
            height:100px;
            text-align:center;
            cursor:pointer;
            background:#A5D8E3;
            border:2px solid white;
            font-size:14px;
        }
        .itemselected{
            border:2px solid red;
        }
        .taocan_display,.goods_hidden_id{
            display:none;
        }
        .taocan_right{
            display:none;
        }
.span_choiceable{
            position:relative;
        }
        .goods_num{
            display:none;
            color: #EEE;
            position: absolute;
            top: 0px;
            left:0px;
            width: 100px;
            height: 100px;
        }
        .num_display{
            color: #EEE;
            position: absolute;
            top: -14px;
            left:0px;
            width: 100px;
            height: 100px;
            filter:progid:DXImageTransform.Microsoft.Alpha(opacity=50);
            opacity:0.5;
            background-color:black;
        }
        .num_display span{
            margin-left:70px;
            display:inline-block;
            color:red;
            font-size:20px;
        }
        .goods_num input{
            display:block;
            width:60px;
            margin-left:17px;
        }
        .p_date{
            font-size:17px;
            margin-bottom:5px;
            color:green;
        }
    </style>
    <script src="../js/jquery.js" type="text/javascript"></script>
    <script type="text/javascript">var $$=jQuery.noConflict();</script>
    <script>
         $$(document).ready(function(){
            var taocan_id = $$('.taocan_id').text();
                    $$.post("peisong_info.php",{action:'taocan_info',taocan_id:taocan_id},result_manager,"text");
             
         });
         function result_manager(data){
            data = eval("("+data+")");
             $$('.p_second span:eq(0)').text(data.name);
             var starttime = data.starttime*1000;
             var d = new Date(starttime);
             var s='';
             s += (d.getFullYear()) + "-";            
             s += d.getMonth() +1 + "-";                   
             s += d.getDate();               
             $$('.p_second span:eq(1)').text(s);
              var endtime = data.endtime*1000;
             var d = new Date(endtime);
             var s='';
             s += (d.getFullYear()) + "-";           
             s += d.getMonth() +1 + "-";  
             s += d.getDate();                  
             $$('.p_second span:eq(2)').text(s);
             $$('.p_third span:eq(1)').text(data.num/8);
             $$('.p_third span:eq(2)').text(data.num);
             var html="<div>";
             a= data.time_ship;
             for(var i=0;i<a.length;i++){
                var t=a[i]*1000;
                var d= new Date(t);
                var s = (d.getMonth()+1)+"-"+(d.getDate());
                //获取今天类似这样的日期2012/2/12
                var date = new Date();
                 date = date.getFullYear()+"/"+(date.getMonth()+1)+"/"+(date.getDate());
                //获取本次类似这样的日期2012/2/12
                var thisdate = d.getFullYear()+"/"+(d.getMonth()+1)+"/"+(d.getDate());
                date = new Date(Date.parse(date));
                thisdate = new Date(Date.parse(thisdate));
                if(thisdate< date){//是过去的套餐
                    html+="<span class=\"span_past\"><p class=\"p_num\">"+(i+1)+"</p>  <p class=\"p_date\">"+s+"</p><p class=\"p_date_hidden\">"+a[i]+"</p><p class=\"p_day\">"+getXinqi(t)+"</p></span>";
                }else if(thisdate> date){
                    html+="<span class=\"span_feature\"><p class=\"p_num\">"+(i+1)+"</p>  <p class=\"p_date\">"+s+"</p><p class=\"p_date_hidden\">"+a[i]+"</p><p class=\"p_day\">"+getXinqi(t)+"</p></span>";
                }else{
                    html+="<span class=\"span_ing\"><p class=\"p_num\">"+(i+1)+"</p>  <p class=\"p_date\">"+s+"</p><p class=\"p_date_hidden\">"+a[i]+"</p><p class=\"p_day\">"+getXinqi(t)+"</p></span>";
                }
                
             }
             html+="</div>";
             $$('.taocan_nums').html(html);
             $$('.taocan_display').show();
             //处理点击配送时间表
            $$('.taocan_nums div span').each(function(){
            $$(this).live("click",function(){
                $$(this).toggleClass("itemselected");
                $$(this).siblings().removeClass("itemselected");
                var type = $$('.taocan_type').text();
                var taocan_id = $$('.taocan_id').text();
                var peiTime = $$(this).find('.p_num').text();
                var time_ship = $$(this).find('.p_date_hidden').text();
                var time_now = new Date();
                var diff_time = time_ship*1000 - time_now;
                if($$(this).hasClass("itemselected"))
                    $$.post("peisong_info.php",{action:'display_info',taocan_id:taocan_id,type:type,peiTime:peiTime,time_ship:time_ship},return_result,"text");
                if($$(this).hasClass("span_feature") || diff_time<10*60*60*1000 && diff_time >0){//是过去的套餐或是前一天5点以后不显示更新按钮
                    $$('.taocan_update').show();
                }else{
                    $$('.taocan_update').hide();
                }
            });
         });
         }
         //以下是执行更新的事件处理
            //获取点击的送货地址
            
            $$('.taocan_location span').live("click",function(){
                $$(this).toggleClass('itemselected');
                 $$(this).siblings().removeClass("itemselected");
            });
         function return_result(data){
          data = eval("("+data+")");
          var html = "<p>根据您购买的套餐当前固定的商品数是1，可选的商品数是<span class=\"choiceable_num\">"+data.choiceable_num+"</span></p>"
            //组装套餐固定
             html +="<div> <p style=\"font-size:15px;color:brown;\">固定的商品</p>";
            var a = data.arr_fixed;
            for(var i=0;i<a.length;i++){
                html+="<span class=\"span_fixed\"><img src=\"../../"+a[i].goods_thumb+"\" alt=\""+a[i].goods_name+"\"></img><p>"+a[i].goods_name+"</p><p style=\"display:none\">"+a[i].goods_id+"</p></span>";
            }
            html+="</div>";
            $$('.fixed').html(html);
            //如果取回的数据固定的和可选的有重复的，则需要处理
            for(var i =0;i<data.arr_selected.length;i++){
                if(data.arr_fixed[0].goods_id == data.arr_selected[i]){
                    data.arr_selected_num[i]-=1;
                    break;
                }
            }
            a = data.arr_choiceable;
            html ="<div><p style=\"font-size:15px;color:green;\">可选的商品</p>"
             var b =0;
            for(var i=0;i<a.length;i++){
               //判断是否是之前选择的商品
                if(is_in_array(a[i].goods_id,data.arr_selected)){
                    if(data.arr_selected_num[b++]){
                    html+="<span class=\"span_choiceable itemselected\"><img src=\"../../"+a[i].goods_thumb+"\" alt=\""+a[i].goods_name+"\"></img><p class=\"num_display\"><span>"+data.arr_selected_num[b-1] +"</span></p><p class=\"goods_num\">数量:<input type=\"text\" value=\""+data.arr_selected_num[b-1] +"\"  /></p><p>"+a[i].goods_name+"</p><p class=\"goods_hidden_id\">"+a[i].goods_id+"</p></span>";
                    }else{
                html+="<span class=\"span_choiceable\"><img src=\"../../"+a[i].goods_thumb+"\" alt=\""+a[i].goods_name+"\"></img><p class=\"goods_num\">数量:<input type=\"text\"  /></p><p>"+a[i].goods_name+"</p><p class=\"goods_hidden_id\">"+a[i].goods_id+"</p></span>";
                }
                }else{
                html+="<span class=\"span_choiceable\"><img src=\"../../"+a[i].goods_thumb+"\" alt=\""+a[i].goods_name+"\"></img><p class=\"goods_num\">数量:<input type=\"text\"  /></p><p>"+a[i].goods_name+"</p><p class=\"goods_hidden_id\">"+a[i].goods_id+"</p></span>";
                }
            }
            html+="</div>";
            $$('.nofixed').html(html);
            $$('.taocan_right').show();
            $$('.taocan_location span').removeClass("itemselected");
            $$('.taocan_location span').each(function(){
                if($$(this).text()==data.location){
                    $$(this).addClass("itemselected");
                }
            });
            //设置可选套餐点击效果
            $$('.nofixed .span_choiceable').each(function(){
                $$(this).live("click",function(){
                    $$(this).toggleClass("itemselected");
                     if($$(this).hasClass("itemselected")){//如果是去掉选择则设置背景效果无
                        var goods_num_obj = $$(this).find(".num_display span");
                        var goods_num = goods_num_obj.text();
                        goods_num = goods_num==""?1:goods_num;
                        $$(this).find(".goods_num").show().children().focus().val(goods_num);
                        //如果修改数目则相应的右上角数据也改变
                        $$(this).find(".goods_num input").change(function(){
                            goods_num_obj.text($$(this).val());
                        });
                    }else{
                        $$(this).find(".goods_num").hide();
                        $$(this).find(".num_display").hide();
                    }
                });
            });
            
         }
         //点击更新配置按钮
            $$('.taocan_update').live("click",function(){
                //获取套餐ID
                var taocan_id = $$('.taocan_id').text();
                //第几次配送
                var peiTime = $$('.taocan_nums .itemselected').find('.p_num').text();
                //配送的时间
                var time_ship = $$('.taocan_nums .itemselected').find('.p_date_hidden').text();
                //配送的地址
                var location = $$('.taocan_location .itemselected').text();
                //获取商品的ID
                var goods_id = new Array();
                
                //再压入可选套餐的ID
                $$('.nofixed .span_choiceable').each(function(){
                    if($$(this).hasClass("itemselected")){//压入被选中的ID
                       //判断数量
                        var num = $$(this).find(".goods_num input").val();
                        if(num>1){
                            for( var i=0;i<num;i++){
                                goods_id.push($$(this).children('.goods_hidden_id').text());
                            }
                        }else{
                            goods_id.push($$(this).children('.goods_hidden_id').text());
                        }
                    }
                });
                if(goods_id.length-1 != $$('.choiceable_num').text()){
                    alert("请选择"+$$('.choiceable_num').text()+"种商品！");
                    return;
                }
                
                if(taocan_id ===undefined ||taocan_id ==''){
                    alert('请选择您要更新的套餐！');
                    return;
                }
                if(peiTime === undefined ||peiTime == ''||time_ship ===undefined ||time_ship== ''){
                    alert('请选择您要更新的次数！');
                    return;
                }
                if(location === undefined || '' ==location){
                    alert('请选择本次您取货的地址！');
                    return;
                }
                //更新
                $$.post("peisong_info.php",{action:'update_info',goods_id:goods_id,taocan_id:taocan_id,time_ship:time_ship,time_num:peiTime,location:location},manager_result,"text");
            });
         function is_in_array(value,array){
            if(array===undefined || array.length ==0){
                return false;
            }
            for(var i=0;i<array.length;i++){
                if(array[i]==value){
                    return true;
                }
            }
            return false;
         }
         function manager_result(data){
            data = eval("("+data+")");
            alert(data);
         }
         function getXinqi(i){
            var x = new Array("星期日", "星期一", "星期二","星期三","星期四", "星期五","星期六");
            d = new Date(i);
            day = d.getDay();
            return(x[day]);
        }
    </script>