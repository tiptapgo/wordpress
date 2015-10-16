<?php
/**
 * Template Name: signupnew
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

$name = trim(stripslashes($_POST['signup_name']));
$email = trim(stripslashes($_POST['signup_email']));
if($name=='' || $email==''){
	echo "INVALID PARAMETER";
} else {
	$mobile = trim(stripslashes($_POST['signup_mobile']));
	$dob = '';
	//$dob = trim(stripslashes($_POST['signup_dob']));
	//$gender = trim(stripslashes($_POST['signup_gender']));
	$gender = '';
	$location = trim(stripslashes($_POST['signup_location']));
	//$category = trim(stripslashes($_POST['signup_category']));
	$category = '';
	$password = trim(stripslashes($_POST['signup_password']));

	$code = rand(1000, 9999);

	$url = "https://rest.nexmo.com/sms/json?api_key=8fa2959e&api_secret=b6268d96&from=TipTapGo&to=91".$mobile."&text=TipTapGo%20Sign-Up%20Code:%20".$code;
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$nexmo = curl_exec($ch);
	$info = curl_getinfo($ch);
	curl_close($ch);
	if($info['http_code'] != 200){
		echo "NEMXO ERROR: ".$info['http_code'];
	} else {
		$nexmoParse = json_decode($nexmo->messages, true);
		if($nexmoParse['status'] != 0){
			echo "NEMXO ERROR: ".$nexmoParse['error-text'];
		} else { 

			preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#', $name, $namesplit);

			if($namesplit[2] == '') {
				$namesplit[2] = $name;
				$namesplit[3] = '';
			}

			$username = '';


			if(!username_exists( $namesplit[2] ) && !email_exists($email)){ 
				$userdata = array(
					'user_login'  =>  $namesplit[2],
					'user_pass'   =>  $password,
					'user_email'  =>  $email,
					'first_name'  =>  $namesplit[2],
					'last_name'	  =>  $namesplit[3],
					'role'        =>  'employer',
					'display_name'=>  $name,
					);	
				$user_id = wp_insert_user( $userdata );
				$username = $namesplit[2];
			}

			else if(!username_exists( $namesplit[2].$namesplit[3] ) && !email_exists($email)){ 
				$userdata = array(
					'user_login'  =>  $namesplit[2].$namesplit[3],
					'user_pass'   =>  $password,
					'user_email'  =>  $email,		
					'first_name'  =>  $namesplit[2],
					'last_name'	  =>  $namesplit[3],
					'role'        =>  'employer',
					'display_name'=>  $name,
					);	
				$user_id = wp_insert_user( $userdata );
				$username = $namesplit[2].$namesplit[3];
			}


			else{
				do {
					$random_str = wp_generate_password( $length=2, $include_standard_special_chars=false ); 
					$userdata = array(
						'user_login'  =>  $namesplit[2].'.'.$random_str,
						'user_pass'   =>  $password,
						'user_email'  =>  $email,			
						'first_name'  =>  $namesplit[2],
						'last_name'	  =>  $namesplit[3],
						'role'        =>  'employer',
						'display_name'=>  $name,
						);	
					$user_id = wp_insert_user( $userdata );
					$username = ' ';
					$username = $namesplit[2].'.'.$random_str;
				} while ( is_wp_error( $user_id ));
			}

			if(is_wp_error( $user_id )){
				echo "WP USER CREATE ERROR";

			} else{ 

/*				$to = $email;
				$subject = "Please confirm your email address";				
				$txt = '<html lang="en-US"><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title></title> <style type="text/css">#outlook a,h1,h2,h3,h4,h5,h6,p{padding:0}h1,h2,h3,h4,h5,h6{margin:0}h1,h2,h3,h4{font-weight:700;font-style:normal;display:block;color:#3396d1}#templateBody,#templateFooter,#templateHeader,#templatePreheader{border-top:0;border-bottom:0}.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p,.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p,.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p,h1,h2,h3,h4{font-family:Helvetica;text-align:left}#bodyCell,#bodyTable,body{height:100%;margin:0;padding:0;width:100%}table{border-collapse:collapse}a img,img{border:0;outline:0;text-decoration:none}p{margin:1em 0}a{word-wrap:break-word}.ExternalClass,.ReadMsgBody{width:100%}.ExternalClass,.ExternalClass div,.ExternalClass font,.ExternalClass p,.ExternalClass span,.ExternalClass td{line-height:100%}h1,h2,h3,h4{line-height:125%}table,td{mso-table-lspace:0;mso-table-rspace:0}img{-ms-interpolation-mode:bicubic}a,blockquote,body,li,p,table,td{-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%}#bodyCell{padding:20px;border-top:0}.mcnImage{vertical-align:bottom}.mcnTextContent img{height:auto}#bodyTable,body{background-color:#3396d1}#templateContainer{border:0}h1{font-size:40px;letter-spacing:-1px}h2{font-size:26px;letter-spacing:-.75px}h3{font-size:18px;letter-spacing:-.5px}h4{font-size:16px;letter-spacing:normal}#templatePreheader{background-color:#FFF}.preheaderContainer .mcnTextContent,.preheaderContainer .mcnTextContent p{color:#606060;font-family:Helvetica;font-size:11px;line-height:125%;text-align:left}.preheaderContainer .mcnTextContent a{color:#606060;font-weight:400;text-decoration:underline}#templateHeader{background-color:#FFF}.headerContainer .mcnTextContent,.headerContainer .mcnTextContent p{color:#606060;font-size:15px;line-height:150%}.headerContainer .mcnTextContent a{color:#6DC6DD;font-weight:400;text-decoration:underline}#templateBody{background-color:#fff}.bodyContainer .mcnTextContent,.bodyContainer .mcnTextContent p{color:#3396d1;font-size:15px;line-height:150%}.bodyContainer .mcnTextContent a{color:#3396d1;font-weight:400;text-decoration:underline}#templateFooter{background-color:#FFF}.footerContainer .mcnTextContent,.footerContainer .mcnTextContent p{color:#606060;font-size:11px;line-height:125%}.footerContainer .mcnTextContent a{color:#606060;font-weight:400;text-decoration:underline}@media only screen and (max-width:480px){a,blockquote,body,li,p,table,td{-webkit-text-size-adjust:none}body{width:100%;min-width:100%}td[id=bodyCell]{padding:10px}img[class=mcnImage],table[class=mcnBoxedTextContentContainer],table[class=mcnCaptionBottomContent],table[class=mcnCaptionLeftImageContentContainer],table[class=mcnCaptionLeftTextContentContainer],table[class=mcnCaptionRightImageContentContainer],table[class=mcnCaptionRightTextContentContainer],table[class=mcnCaptionTopContent],table[class=mcnImageCardLeftTextContentContainer],table[class=mcnImageCardRightTextContentContainer],table[class=mcnImageGroupContentContainer],table[class=mcnTextContentContainer]{width:100%}table[class=mcpreview-image-uploader]{width:100%;display:none}td[class=mcnImageGroupContent]{padding:9px}td[class=mcnImageGroupBlockInner]{padding-bottom:0;padding-top:0}tbody[class=mcnImageGroupBlockOuter]{padding-bottom:9px;padding-top:9px}td[class=mcnImageCardLeftImageContent],td[class=mcnImageCardRightImageContent]{padding-right:18px;padding-left:18px;padding-bottom:0}td[class=mcnImageCardBottomImageContent]{padding-bottom:9px}td[class=mcnCaptionBlockInner] table[class=mcnCaptionTopContent]:last-child td[class=mcnTextContent],td[class=mcnImageCardTopImageContent]{padding-top:18px}table[class=mcnCaptionLeftContentOuter] td[class=mcnTextContent],table[class=mcnCaptionRightContentOuter] td[class=mcnTextContent]{padding-top:9px}td[class=mcnBoxedTextContentColumn]{padding-left:18px;padding-right:18px}td[class=mcnTextContent]{padding-right:18px;padding-left:18px}table[id=templateBody],table[id=templateContainer],table[id=templateFooter],table[id=templateHeader],table[id=templatePreheader]{max-width:600px;width:100%}h1{font-size:24px;line-height:125%}h2{font-size:20px;line-height:125%}h3,table[class=mcnBoxedTextContentContainer] td[class=mcnTextContent],td[class=bodyContainer] td[class=mcnTextContent],td[class=bodyContainer] td[class=mcnTextContent] p,td[class=headerContainer] td[class=mcnTextContent],td[class=headerContainer] td[class=mcnTextContent] p,td[class=mcnBoxedTextContentContainer] td[class=mcnTextContent] p{font-size:18px;line-height:125%}h4{font-size:16px;line-height:125%}table[id=templatePreheader],td[class=footerContainer] a[class=utilityLink]{display:block}td[class=footerContainer] td[class=mcnTextContent],td[class=footerContainer] td[class=mcnTextContent] p,td[class=preheaderContainer] td[class=mcnTextContent],td[class=preheaderContainer] td[class=mcnTextContent] p{font-size:14px;line-height:115%}}</style> <center> <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable"> <tbody><tr> <td align="center" valign="top" id="bodyCell"> <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer"> <tbody><tr> <td align="center" valign="top"> <table border="0" cellpadding="0" cellspacing="0" width="600" id="templatePreheader"> <tbody><tr> <td valign="top" class="preheaderContainer" style="padding-top:9px;"></td></tr></tbody></table> </td></tr><tr> <td align="center" valign="top"> <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader"> <tbody><tr> <td valign="top" class="headerContainer"></td></tr></tbody></table> </td></tr><tr> <td align="center" valign="top"> <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody"> <tbody><tr> <td valign="top" class="bodyContainer"><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnTextBlock"> <tbody class="mcnTextBlockOuter"> <tr> <td valign="top" class="mcnTextBlockInner"> <table align="left" border="0" cellpadding="0" cellspacing="0" width="600" class="mcnTextContentContainer"> <tbody><tr> <td valign="top" class="mcnTextContent" style="padding-top:9px; padding-right: 18px; padding-bottom: 9px; padding-left: 18px;"> <h1>Hello, '.$name.'!</h1><h3>Welcome to TipTapGo</h3><p>To access your account and start new classes, please&nbsp;confirm&nbsp;your email by clicking the button below:</p></td></tr></tbody></table> </td></tr></tbody></table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnButtonBlock"> <tbody class="mcnButtonBlockOuter"> <tr> <td style="padding-top:0; padding-right:18px; padding-bottom:18px; padding-left:18px;" valign="top" align="center" class="mcnButtonBlockInner"> <table border="0" cellpadding="0" cellspacing="0" class="mcnButtonContentContainer" style="border-collapse: separate ;border: 2px solid #388E3C;border-radius: 5px;background-color: #4CAF50;"> <tbody> <tr> <td align="center" valign="middle" class="mcnButtonContent" style="font-family: Arial; font-size: 16px; padding: 16px;"> <a class="mcnButton " title="Click Here To Confirm Email" href="http://tiptapgo.co/my-account/?id='.$user_id.'&amp;key=true" target="_blank" style="font-weight: bold;letter-spacing: normal;line-height: 100%;text-align: center;text-decoration: none;color: #FFFFFF;">Click Here To Confirm Email</a> </td></tr></tbody> </table> </td></tr></tbody></table><table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowBlock"> <tbody class="mcnFollowBlockOuter"> <tr> <td align="center" valign="top" style="padding:9px" class="mcnFollowBlockInner"> <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContentContainer"> <tbody><tr> <td align="center" style="padding-left:9px;padding-right:9px;"> <table border="0" cellpadding="0" cellspacing="0" width="100%" class="mcnFollowContent" style="border: 1px solid #EEEEEE;background-color: #FAFAFA;"> <tbody><tr> <td align="center" valign="top" style="padding-top:9px; padding-right:9px; padding-left:9px;"> <table border="0" cellpadding="0" cellspacing="0"> <tbody><tr> <td valign="top"> <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnFollowStacked"> <tbody><tr> <td align="center" valign="top" class="mcnFollowIconContent" style="padding-right:10px; padding-bottom:5px;"> <a href="https://www.facebook.com/tiptapgoco" target="_blank"><img src="http://cdn-images.mailchimp.com/icons/social-block-v2/color-facebook-96.png" alt="Facebook" class="mcnFollowBlockIcon" width="48" style="width:48px; max-width:48px; display:block;"></a> </td></tr><tr> <td align="center" valign="top" class="mcnFollowTextContent" style="padding-right:10px; padding-bottom:9px;"> <a href="https://www.facebook.com/tiptapgoco" target="_blank" style="color: #606060;font-family: Arial;font-size: 11px;font-weight: normal;line-height: 100%;text-align: center;text-decoration: none;">Facebook</a> </td></tr></tbody></table><!--[if gte mso 6]> </td><td align="left" valign="top"><![endif]--> <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnFollowStacked"> <tbody><tr> <td align="center" valign="top" class="mcnFollowIconContent" style="padding-right:10px; padding-bottom:5px;"> <a href="http://blog.tiptapgo.co/" target="_blank"><img src="http://cdn-images.mailchimp.com/icons/social-block-v2/color-tumblr-96.png" alt="Tumblr" class="mcnFollowBlockIcon" width="48" style="width:48px; max-width:48px; display:block;"></a> </td></tr><tr> <td align="center" valign="top" class="mcnFollowTextContent" style="padding-right:10px; padding-bottom:9px;"> <a href="http://blog.tiptapgo.co/" target="_blank" style="color: #606060;font-family: Arial;font-size: 11px;font-weight: normal;line-height: 100%;text-align: center;text-decoration: none;">Tumblr</a> </td></tr></tbody></table><!--[if gte mso 6]> </td><td align="left" valign="top"><![endif]--> <table align="left" border="0" cellpadding="0" cellspacing="0" class="mcnFollowStacked"> <tbody><tr> <td align="center" valign="top" class="mcnFollowIconContent" style="padding-right:0; padding-bottom:5px;"> <a href="http://tiptapgo.co/" target="_blank"><img src="http://cdn-images.mailchimp.com/icons/social-block-v2/color-link-96.png" alt="Website" class="mcnFollowBlockIcon" width="48" style="width:48px; max-width:48px; display:block;"></a> </td></tr><tr> <td align="center" valign="top" class="mcnFollowTextContent" style="padding-right:0; padding-bottom:9px;"> <a href="http://tiptapgo.co/" target="_blank" style="color: #606060;font-family: Arial;font-size: 11px;font-weight: normal;line-height: 100%;text-align: center;text-decoration: none;">Website</a> </td></tr></tbody></table><!--[if gte mso 6]> </td><td align="left" valign="top"><![endif]--> </td></tr></tbody></table> </td></tr></tbody></table> </td></tr></tbody></table> </td></tr></tbody></table></td></tr></tbody></table> </td></tr><tr> <td align="center" valign="top"> <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateFooter"> <tbody><tr> <td valign="top" class="footerContainer" style="padding-bottom:9px;"></td></tr></tbody></table> </td></tr></tbody></table> </td></tr></tbody></table> </center> </html>';
				$headers = "From: Priya from TipTapGo <priya@tiptapgo.co>\r\n";
				$headers .= "Reply-To: Priya from TipTapGo <priya@tiptapgo.co> priya@tiptapgo.co\r\n";
				$headers .= "Cc: help@tiptapgo.co\r\n";				
				$headers .= "Bcc: help@tiptapgo.co\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
				mail($to,$subject,$txt,$headers);		*/		

				$initdata = $gender.'@'.$mobile.'@'.$dob.'@'.$location.'@'.$category;
				update_user_meta($user_id,'initdata',$initdata);

				if(is_wp_error($user_id)){
					$result = $user_id->get_error_message();
				} else if(!is_object($user_id)){
					if( class_exists('W3_Plugin_TotalCacheAdmin') )	{
							$plugin_totalcacheadmin = & w3_instance('W3_Plugin_TotalCacheAdmin');
						    $plugin_totalcacheadmin->flush_all();
					}
					do {
						if (isset($_SERVER['HTTP_COOKIE'])) {
						    $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
						    foreach($cookies as $cookie) {
						        $parts = explode('=', $cookie);
						        $name = trim($parts[0]);
						        setcookie($name, '', time()-1000);
						        setcookie($name, '', time()-1000, '/');
						    }
						}						
						wp_set_current_user ( $user_id, $username );
						wp_set_auth_cookie  ( $user_id );
						do_action( 'wp_login', $username );
					} while(!is_user_logged_in());					
					$result = $user_id.'@'.$username.'@'.$code.'@'.$nexmoParse['message-id'];		
				}

				echo $result;
			}
		}
	}
}
?>