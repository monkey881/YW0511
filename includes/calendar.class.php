<?php
/*
*author 578672331@qq.com
*
*date 2011-10-22
*/
class Calendar{
	private $month;//当前的年份
	private $year;//当前的月份
	private $days;//当前月的天数
	private $start_weekday;//当前月份的第一天对应的时周几
	
	//初始化
	function __construct($songed,$shezhi){
		$this->songed=$songed;
		$this->shezhi=$shezhi;
		$this->ps=array(3,6);
		$this->month = isset($_GET['month']) ? $_GET['month'] : date("m");
		$this->year = isset($_GET['year']) ? $_GET['year'] : date("Y");
		$this->tid = isset($_GET['tid']) ? $_GET['tid'] : 0;
		$this->days = date("t",mktime(0,0,0,$this->month,1,$this->year));
		$this->start_weekday = date("w",mktime(0,0,0,$this->month,1,$this->year)); 
		}
	
		
	function display(){
		 $temp.= "<table align='center' class='rili'>";
		 $temp.=$this->changeDate("user.php?act=rili&tid=".$this->tid);
		 $temp.=$this->weekList();
		 $temp.=$this->daysList();
		 $temp.= "</table>";
		 return $temp;
		 }
	 
	 //选择年月	 
	 private function changeDate($url=""){
		$a_month[1]="一月";
		$a_month[2]="二月";
		$a_month[3]="三月";
		$a_month[4]="四月";
		$a_month[5]="五月";
		$a_month[6]="六月";
		$a_month[7]="七月";
		$a_month[8]="八月";
		$a_month[9]="九月";
		$a_month[10]="十月";
		$a_month[11]="十一月";
		$a_month[12]="十二月";//一年中的月份
		$temp.= "<tr class='top'>";
		$temp.= "<td class='c'></td>";//上一年
		$temp.= "<td class='c'></td>";//上一月
		$temp.= "<td colspan='3'>";
		$temp.= "<form>";
		$temp.= "<select class='year' name='year' onChange='window.location=\"".$url."?year=\"+this.options[selectedIndex].value+\"&month=".$this->month."\"'>";//选择年
		    for($sy=1970;$sy<=2038;$sy++){
				$selected = ($sy == $this->year) ? "selected" : "";
				$temp.= '<option '.$selected.' value="'.$sy.'">'.$sy.'</option>';
			}
		$temp.= "</select>";
		$temp.= "<select class='month' name='month' onChange='window.location=\"".$url."&year=".$this->year."&month=\"+this.options[selectedIndex].value'>";//选择月
		    for($sm=1;$sm<=12;$sm++){
				$selected = ($sm == $this->month) ? "selected" : "";
				$temp.= '<option '.$selected.' value="'.$sm.'">'.$a_month[$sm].'</option>';
			}
		$temp.= "</select>";
		$temp.= "</form>";
		$temp.= "</td>";
		$temp.= "<td class='c'></td>";//下一年
		$temp.= "<td class='c'></td>";//下一月
		$temp.= "</tr>";
		return $temp;
		}
	 
	 //显示周几
	 private function weekList(){
			$week=array('日','一','二','三','四','五','六');
			$temp.= "<tr>";
			for($i=0; $i<count($week);$i++){
				$temp.= "<th>".$week[$i]."</th>";
				}
			$temp.= "</tr>";
			return $temp;
		}
	 
	 //显示天数	
	 private function daysList(){
		     $temp.= "<tr>";
			 //输出空格
			 for($b=0; $b<$this->start_weekday; $b++){
				 $temp.= "<td>&nbsp;</td>";
			 }
				 
			 //输出天数
			 $today='';
			 for($d=1; $d<=$this->days; $d++){
				 $b++;
				 if(mktime(0,0,0,$this->month,$d,$this->year) == mktime(0,0,0,date('m'),date('d'),date('y'))){
					 $today= " f";
				}
				else
				{
					 $today= " ";
					}
				  if(in_array(date("w",mktime(0,0,0,$this->month,$d,$this->year)),$this->ps))
				 {
					 if(in_array(mktime(0,0,0,$this->month,$d,$this->year),$this->songed))
					 {
						 $temp.= "<td class='songed $today'><a onclick='getTaoCan(".mktime(0,0,0,$this->month,$d,$this->year).")' href='#'>".$d."</a></td>";
					 }
					 else if(in_array(mktime(0,0,0,$this->month,$d,$this->year),$this->shezhi))
					 {
						  $temp.= "<td class='shezhi $today'><a onclick='getTaoCan(".mktime(0,0,0,$this->month,$d,$this->year).")' href='#'>".$d."</a></td>";
					 }
					 else
					 {
					 $temp.= "<td class='psr $today'><a onclick='getTaoCan(".mktime(0,0,0,$this->month,$d,$this->year).")' href='#'>".$d."</a></td>";
					 }
					 
				 }
				 
				 else{
					 $temp.= "<td class=' $today'>".$d."</td>";	 
				 }	 
				 
				 //天数换行
				 if($b%7==0){
				 $temp.= "</tr><tr>";	 
				 }
			 }
			 
			 $temp.= "</tr>";
			 return $temp;
	  }
	  

		
	 
	 //上一年
	 private function preYear($year,$month){
		 $year = ($year <= 1970) ? 1970 : $year-1;
		 return  "year={$year}&month={$month}";
		 }
		 
	 //下一年
	 private function nextYear($year,$month){
		 $year = ($year >= 2038) ? 2038 : $year+1;
		 return  "year={$year}&month={$month}";
		 }
		 
	 //上一月
	 private function preMonth($year,$month){
		 if($month == 1){
			 $month = 12;
			 $year = ($year <= 1970) ? 1970 : $year-1;
		 }
		 else{
		     $month--;	 
		 }
		 return  "year={$year}&month={$month}";
		 }
		 
	 // 下一月
	 private function nextMonth($year,$month){
		 if($month == 12){
			 $month = 1;
			 $year = ($year >= 2038) ? 2038 : $year+1;
		 }else{
		     $month++;	 
		 }
		 return  "year={$year}&month={$month}";
		 }
	function getPeisongDate($num){
		$today=mktime(0,0,0,date('m'),date('d'),date('y'));
		$psdate=array();
		$i=0;
		$m=0;
		while($i<$num){
			if(in_array(date("w",($today+86400*$m)),$this->ps)&&($today+86400*$m-time())>43200){
				
				$psdate[]=$today+86400*$m;
				$i++;
				}
			$m++;
			}
		return $psdate;
		}
	}
