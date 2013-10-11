
    <h1>套餐管理配置界面</h1>
    <div class="taocan_main">
        <div class="taocan_p1">
            <div> 请选择固定套餐还是可选套餐</div>
            <hr>
            <label><input type="radio" name="biao" value="0">固定</input></label>
            <label><input type="radio" name="biao" value="1">可选</input></label>
        </div>
        &nbsp;
         &nbsp;
        <div class="taocan_p2">
            <div>请选择套餐种类</div>
            <hr>
            <label><input type="radio" name="type" value="21">二人单月</input></label>
            <label><input type="radio" name="type" value="23">二人单季</input></label>
            <label><input type="radio" name="type" value="26">二人半年</input></label>
            <label><input type="radio" name="type" value="31">三人单月</input></label>
            <label><input type="radio" name="type" value="33">三人单季</input></label>
           <label> <input type="radio" name="type" value="36">三人半年</input></label>
            <label><input type="radio" name="type" value="41">四人单月</input></label>
            <label><input type="radio" name="type" value="43">四人单季</input></label>
           <label> <input type="radio" name="type" value="46">四人半年</input></label>
        </div>
         &nbsp;
          &nbsp;
        <div class="taocan_p3">
            <div>请选择月份</div>
            <hr>
            <label><input type="radio" name="month" value="1">一月</input></label>
            <label><input type="radio" name="month" value="2">二月</input></label>
           <label> <input type="radio" name="month" value="3">三月</input></label>
            <label><input type="radio" name="month" value="4">四月</input></label>
            <label><input type="radio" name="month" value="5">五月</input></label>
            <label><input type="radio" name="month" value="6">六月</input></label>
            <label><input type="radio" name="month" value="7">七月</input></label>
           <label> <input type="radio" name="month" value="8">八月</input></label>
            <label><input type="radio" name="month" value="9">九月</input></label>
            <label><input type="radio" name="month" value="10">十月</input></label>
            <label><input type="radio" name="month" value="11">十一月</input></label>
            <label><input type="radio" name="month" value="12">十二月</input></label>
        </div>
         &nbsp; &nbsp;
        <div>点击按钮获取已选配置</div>
        <input type="button" name="getdata" value="获取信息" class="get_data"></input>
         &nbsp; &nbsp;
        <div class="result" style="background:#DBA0E3"></div>
         &nbsp; &nbsp;
        <div class="taocan_data">
           
        </div>
    </div>
     &nbsp; &nbsp;
        <label>全选<input type="checkbox" value="全选" class="select_all"></input></label>
        <input type="button" value="更新" class="update_date"></input>
    <script src="../js/jquery.js" type="text/javascript"></script>
    <script type="text/javascript">var $$=jQuery.noConflict();</script>
    <script>
        $$(document).ready(function(){
            $$(".get_data").click(function(){
                var biao = $$('input[name="biao"]:checked').val();
                var type = $$('input[name="type"]:checked').val();
                var month = $$('input[name="month"]:checked').val();
                if(biao ===undefined||type===undefined||month===undefined ){
                    alert('请选择所需的属性！');
                }else{
                    $$.post("taocan.php",{biao:biao,type:type,month:month,op:'2'},managerdata,"json");
                  
                }
            });
            //当点击月份后查询该月商品
            $$('input[name="month"]').click(function(){
                 var month = $$('input[name="month"]:checked').val();
                $$.post("taocan.php",{month:month,op:'1'},getmonth,"json");
            });
            //点击全选按钮
            $$('.select_all').click(function(){
                if($$(this).attr("checked") != "checked"){
                    $$('input[name="goods_id"]').removeAttr("checked");
                }else{
                    $$('input[name="goods_id"]').attr("checked",'true');
                } 
            });
            //点击更新按钮
            $$('.update_date').live("click",function(){
                var biao = $$('input[name="biao"]:checked').val();
                var type = $$('input[name="type"]:checked').val();
                var month = $$('input[name="month"]:checked').val();
                var goods_id = new Array();
                $$("input[name='goods_id']:checkbox:checked").each(function(){ 
                    goods_id.push($$(this).val());
                });
                if(biao ===undefined || type ===undefined ||month === undefined ||goods_id.length == 0){
                    alert('请选择上述选项！');
                }else{
                    $$.post("taocan.php",{type:type,biao:biao,month:month,goods_id:goods_id,op:'3'},getresult,"text");
                }
                
            });
});
    function getresult(data){
        alert(data);
    }
    function getmonth(data){
        var result ="当前月份拥有以下商品：&nbsp";
        var html ="<div class=\"month_result\">";
        for(key in data){
            html+="<label><input type=\"checkbox\" name=\"goods_id\" value=\""+data[key].goods_id+"\">"+data[key].goods_name+"</input></label>";
        }    
        html+="</div>";
        $$(".taocan_data").html(result+html);
    }
    function managerdata(data){
    //data = eval( "(" + data + ")" );
        var result = '当前选择的商品有：';
        for(key in data){
            result += data[key].goods_name+'  ';
        }
        $$('.result').html(result);
    }
    </script>