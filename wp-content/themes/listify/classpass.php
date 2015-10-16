<?php
/**
* Template Name: classpass
*
* @package Listify
*/

require_once("../../../wp-load.php");

$id = trim(stripslashes($_GET['id']));
if($id==''){
$id = trim(stripslashes($_POST['id']));
}
if($id==''){
echo "ERR: REQUIRED PARAMETER MISSING";
die();
}

$tutorid = get_post_meta($id,'booktutorid',true);
$classid = get_post_meta($id,'bookclassid',true);
$posttitle = get_the_title( $classid );
$address = get_post_meta($classid,'_job_location', true).' - '.get_post_meta($classid,'_company_website', true);
$tutormob = get_post_meta($tutorid,'_mobile_num',true);
$classtype = get_post_meta($classid,'_job_classtype', true);
if($classtype == '' || $classtype == 'regular'){
$daydatetime = "Regular Class";
} else {
$month_names = array("January","February","March","April","May","June","July","August","September","October","November","December");
$date = get_post_meta($classid,'_job_date',true);
$date = explode("/", $date);
$timestamp = strtotime($date[2].'-'.$date[1].'-'.$date[0]);
$day = date('l', $timestamp);
$starttime = get_post_meta($classid,'_job_start_time',true);
if($starttime = ''){
$starttime = explode(',', get_post_meta($classid,'_job_time_dump',true))[0];
}
$daydatetime = $day.', '.$date[0].'-'.$month_names[$date[1]-1].', '.$date[2].'<br>Starts at '.$starttime;
}
if($tutormob == ''){
$tutormob =  get_cimyFieldValue($tutorid, 'MOBILE');
}
$tutor = get_user_by('id', $tutorid);
$tutorname = $tutor->display_name;
$tutoremail = $tutor->user_email;
$fees = get_post_meta($id,'bookfees',true);

$html = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"><title>Your Class Pass</title><style type="text/css">img{display:block;}#outlook a{padding:0;}body{width:100% !important;}body{margin:0;padding:0;}img{border:none;outline:none;text-decoration:none;}body{background-image:url("http://gallery.mailchimp.com/0d61bb2ec9002f0e9872b8c36/images/bg_texture.1.png");background-position:left top;background-repeat:repeat;background-color:#FDF3CE;}#container{background-image:url("http://gallery.mailchimp.com/0d61bb2ec9002f0e9872b8c36/images/bg_texture.1.png");background-position:left top;background-repeat:repeat;background-color:#FDF3CE;height:100%;margin:0;padding:0;width:100%;}#templateWrapper{-moz-border-radius:5px;-moz-box-shadow:0 2px 10px #FCDEB2;-webkit-border-radius:5px;-webkit-box-shadow:0 2px 10px #FCDEB2;background-color:#FFFFFF;border:1px solid #C7DEE4;border-radius:5px;box-shadow:0 2px 10px #FCDEB2;}.title{color:#404040;font-family:Arial;font-size:18px;font-weight:bold;line-height:150%;}.subTitle{color:#606060;font-family:Arial;font-size:16px;font-weight:bold;line-height:150%;}#templateHeader{-moz-border-radius:5px 5px 0 0;-webkit-border-radius:5px 5px 0 0;background-color:#D9F5FD;border-radius:5px 5px 0 0;color:#404040;font-family:Arial;font-size:18px;font-weight:normal;line-height:100%;text-align:center;}#templateHeader a:link,#templateHeader a:visited{color:#606060;font-weight:normal;text-decoration:underline;}#templateHeader img{display:inline;height:auto;max-width:550px;}#eventImage{display:inline;height:auto;max-width:350px;}#templateBody{border-bottom:1px solid #C7DEE4;border-top:1px solid #C7DEE4;}#templateBodyCopy{color:#404040;font-family:Arial;font-size:14px;line-height:150%;}#templateBodyCopy a:link,#templateBodyCopy a:visited{color:#006699;font-weight:bold;text-decoration:none;}#templateSidebar{-moz-border-radius:5px;-webkit-border-radius:5px;background-color:#FFFFFF;border:1px solid #EDEDED;border-radius:5px;}#date,#location{color:#404040;font-family:Arial;font-size:12px;line-height:150%;}#date a:link,#date a:visited,#location a:link,#location a:visited{color:#006699;font-weight:bold;text-decoration:none;}#button{-moz-border-radius:20px;-webkit-border-radius:20px;background-color:#CC0000;border-radius:20px;padding-top:10px;padding-bottom:10px;text-align:center;text-shadow:0 -1px 0px #680000;width:92%;}#button,#button a:link,#button a:visited{color:#FFFFFF;font-family:Arial;font-size:20px;font-weight:normal;line-height:100%;text-decoration:none;}#social{color:#707070;font-family:Arial;font-size:11px;line-height:180%;}#social a:link,#social a:visited{color:#006699;font-weight:bold;text-decoration:none;}#utility{color:#404040;font-family:Arial;font-size:11px;line-height:180%;}#utility a:link,#utility a:visited{color:#006699;font-weight:bold;text-decoration:none;}#templateFooter{-moz-border-radius:0 0 5px 5px;-webkit-border-radius:0 0 5px 5px;background-color:#D9F5FD;border-radius:0 0 5px 5px;color:#707070;font-family:Arial;font-size:10px;line-height:150%;}#templateFooter a:link,#templateFooter a:visited{color:#404040;font-weight:bold;text-decoration:underline;}</style></head><body><center><table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="container"><tr><td align="center" valign="top"><br><table border="0" cellpadding="0" cellspacing="0" width="600" id="templateWrapper"><tr><td align="center" valign="bottom"><table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader"><tr><td width="20"><br></td><td valign="middle" id="headerImage"><br><img src="http://i.imgur.com/LpJ2WuJ.png" style="max-width:550px;" mc:edit="header_image" mc:allowtext><br><br></td><td width="20"><br></td></tr></table></td></tr><tr><td align="center" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody"><tr><td align="center" valign="top" width="350"><table border="0" cellpadding="0" cellspacing="0" width="350" mc:repeatable><tr><td width="20"><br></td><td align="left" valign="top" width="310"><br><div id="templateBodyCopy" mc:edit="main"><strong>'.$posttitle.'</strong><br><br><strong>Tutor Details</strong><hr>'.$tutorname.'<br>'.$tutormob.'<br>'.$tutoremail.'<br><br><strong>Order Summary</strong><hr>Ticket Amount: Rs. '.$fees.'<br>Internet handling fee (incl. Service Tax): Rs. 100<br>Total: Rs. '.($fees + 100).'</div><br></td><td width="20"></td><br><br></tr><br><br></table><br><br></td><td rowspan="2" width="20" style="background-color:#FAFAFA; border-left:1px solid #C7DEE4;"><br></td><td align="center" rowspan="2" valign="top" width="210" style="background-color:#FAFAFA;"><br><table border="0" cellpadding="0" cellspacing="0" width="210" id="templateSidebar"><tr><td width="10"><br></td><td width="190"><table border="0" cellpadding="0" cellspacing="0" width="190"><tr><td align="left" valign="top"><br><div id="date" mc:edit="date"><strong>'.$daydatetime.'</strong></div></td></tr><tr><td align="left" valign="top"><div id="location" mc:edit="sidecolumn">'.$address.'</div></td></tr><tr><td align="left" valign="top" style="border-top:1px solid #EDEDED;"><br><table border="0" cellpadding="0" cellspacing="0" width="190" id="social"><tr><td align="center" valign="middle" width="16"><a href="https://www.facebook.com/tiptapgoco" target="_blank"><img src="http://gallery.mailchimp.com/0d61bb2ec9002f0e9872b8c36/images/icon_facebook.4.png"></a></td><td width="5"><br></td><td align="left" valign="middle"><div mc:edit="social_link_two"><a href="https://www.facebook.com/tiptapgoco" target="_blank">Friend us on Facebook</a></div></td></tr></table><br></td></tr><tr><td align="left" valign="top" style="border-top:1px solid #EDEDED;"><br><div id="utility" mc:edit="utility">Need help?<br><a href="mailto:help@tiptapgo.co?subject=Need%20help%20with%20my%20class%20pass" target="_blank">help@tiptapgo.co</a></div><br><br></td></tr></table></td><td width="10"><br></td></tr></table><br></td><td rowspan="2" width="20" style="background-color:#FAFAFA;"><br></td></tr></table></td></tr><tr><td align="center" valign="top"><table border="0" cellpadding="0" cellspacing="0" width="600" id="templateFooter"><tr><td width="20"><br></td><td valign="top" width="560"><br><div mc:edit="footer"></div><br></td><td width="20"><br></td></tr></table></td></tr></table><br></td></tr></table></center></body></html>';

include("./pdf/mpdf.php");
$mpdf=new mPDF('utf-8', array(210,160));
$mpdf->WriteHTML($html);
$mpdf->Output('TipTapGo-ClassPass.pdf','D');
exit;

?>