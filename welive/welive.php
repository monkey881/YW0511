<?php

// +---------------------------------------------+
// |     Copyright  2010 - 2028 WeLive           |
// |     http://www.weentech.com                 |
// |     This file may not be redistributed.     |
// +---------------------------------------------+

include('includes/welive.Core.php');

header_nocache();

$panel_status = ForceIncomingCookie('PANEL'.COOKIE_KEY, 1);
$iframe_height = ForceInt(ForceIncomingCookie('IFRHIGHT'.COOKIE_KEY));

if($_CFG['cActived']){

	echo 'var welive_lastScrollY = -108;
	var welive_Iframeheight = '.$iframe_height.';
	var welive_thisPageUrl = window.location.href;

	function welive_ajustHeight(self){
		if(welive_Iframeheight == 0) {
			welive_Iframeheight = self.contentWindow.document.body.scrollHeight;
		}
			
		self.height = welive_Iframeheight;
	}


	window.setInterval("welive_move()",1);

	var welive_panel_top = "<style type=\"text\/css\">#welive-righDiv,#welive-closeDiv{padding:0px;position:absolute;}</style>" +


	"<div style=\"width:144px;height:100%;padding:0 12px;\"><div style=\"position:relative;width:142px;height:100%;background:#fff;padding:0;margin:0;\"><iframe id=\"welive_main_frame\" src=\"'.BASEURL.'online.php\" frameBorder=\"0\" style=\"margin:0;padding:0;width:100%;overflow:hidden;border:none;background:#FFF;\" scrolling=\"no\" onload=\"welive_ajustHeight(this);\"></iframe></div></div>";

	var welive_panel_foot = "<div style=\"height:12px;overflow:hidden;filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\''.TURL.'images/panel_foot.png\', sizingMethod=\'scale\');background:url(\''.TURL.'images/panel_foot.png\') !important;background:;\"></div>";
	
	document.write(welive_panel_top);
	document.write(welive_panel_main);
	document.write(welive_panel_foot);';

}

?>

