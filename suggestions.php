<?php
   header('Content-type: text/html; charset=utf-8');
   error_reporting(0);
   $mysqli=new mysqli('localhost','orizxc','sunzxc-ori/236712','ecshop');
  $mysqli->query('SET NAMES utf8');
   $value=$_GET['input'];
   //对输入的数据进行过滤
   $value = addslashes($value);
   $value = str_replace("%","\%",$value);
   $value = str_replace("_","\_",$value);
   $type=$_GET['type'];
    if($type == '商品'){
    $sql='select `goods_name` from `ecs_goods` where `goods_name` LIKE \'%'.$value.'%\'';
   }else if($type == '农户'){
     $sql='select `brand_name` from `ecs_brand` where `brand_name` LIKE \'%'.$value.'%\'';
   }else if($type == '菜谱'){
     $sql='select `title` from `ecs_article` where `cat_id` = 13 and `title` LIKE \'%'.$value.'%\'';
   }
   $result=$mysqli->query($sql);
    while($row=$result->fetch_array()){
           $text.='<li>'.$row[0].'</li>';
    }
    
    echo $text;
?>