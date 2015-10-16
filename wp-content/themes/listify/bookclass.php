<?php
/**
 * Template Name: bookclass
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

require_once dirname( __FILE__ ) . '/payu.php';

$classid = trim(stripslashes($_POST['bookclass']));
if($classid == ''){
	header("Location: ".$_SERVER['HTTP_REFERER']);
	die();
}
$name = trim(stripslashes($_POST['bookname']));
$email = trim(stripslashes($_POST['bookemail']));
$mobile = trim(stripslashes($_POST['bookmobile']));
$tutorid = trim(stripslashes($_POST['booktutor']));
$fees = trim(stripslashes($_POST['bookfees']));
if($name=='' || $email=='' || $mobile=='' || $tutorid=='' || $fees==''){
	header("Location: ".get_post_permalink($classid));
	die();
}
$fees += 100;

$location = trim(stripslashes($_POST['booklocation']));
$gender = trim(stripslashes($_POST['bookgender']));
$phone =  get_cimyFieldValue($tutorid, 'MOBILE');
$tutor = get_userdata( $tutorid );
$tutormail = $tutor->user_email; 
$booking_exists = false;
$args=array(
	'meta_query' => array(
		array(
			'key' => 'bookemail',
			'value' => $email,
			'compare' => 'LIKE'
			)
		),
	'post_type' => 'bookings',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'caller_get_posts'=> 1
	);

$my_query = null;
$my_query = new WP_Query($args);
if( $my_query->have_posts() ) {
	while ($my_query->have_posts()) {
		$my_query->the_post();
		$matchid = get_the_ID();
		$matchmob = get_post_meta($matchid, 'bookmobile', true);
		$matchemail = get_post_meta($matchid, 'bookemail', true);
		$matchbookid = get_post_meta($matchid, 'bookclassid', true);
		if($matchmob == $mobile && $matchemail == $email && $matchbookid == $classid){
			$matchstatus = get_post_meta($matchid, 'bookstatus', true);
			if($matchstatus == "true"){
				header("Location: ".get_post_permalink( $classid )."/?already_payu_complete=true");
			} else if($matchstatus == "false"){
				$matchpaystatus = get_post_meta($matchid, 'bookpaystatus', true);
				if($matchpaystatus == "true"){
					header("Location: ".get_post_permalink( $classid )."/?wait_for_tutor=true");
				} else if($matchpaystatus == "false"){
					$booking_exists = true;
					$postid = $matchid;
				}
			}
		}	
	}
}

preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#', $name, $namesplit);
if($namesplit[2] == '') {
	$namesplit[2] = $name;
	$namesplit[3] = '';
}

if($booking_exists == false){
	$password = wp_generate_password( $length=5, $include_standard_special_chars=false ); 
	if(email_exists($email)){
		$user = get_user_by( 'email', $email );
		$user_id = $user->ID;
	} 

	if(!email_exists($email)){

		$username = '';

		if(!username_exists( $namesplit[2] )){ 
			$userdata = array(
				'user_login'  =>  $namesplit[2],
				'user_pass'   =>  $password,
				'user_email'  =>  $email,
				'first_name'  =>  $namesplit[2],
				'last_name'	  =>  $namesplit[3],
				'role'        =>  'student',
				'display_name'=>  $name,
				);	
			$user_id = wp_insert_user( $userdata );
			$username = $namesplit[2];
		}

		else if(!username_exists( $namesplit[2].$namesplit[3] ) ){ 
			$userdata = array(
				'user_login'  =>  $namesplit[2].$namesplit[3],
				'user_pass'   =>  $password,
				'user_email'  =>  $email,		
				'first_name'  =>  $namesplit[2],
				'last_name'	  =>  $namesplit[3],
				'role'        =>  'student',
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
					'role'        =>  'student',
					'display_name'=>  $name,
					);	
				$user_id = wp_insert_user( $userdata );
				$username = ' ';
				$username = $namesplit[2].'.'.$random_str;
			} while ( is_wp_error( $user_id ));
		}

		if(is_wp_error( $user_id )){
			header("Location: ".get_post_permalink($classid));
			die();
		} 
		$initdata = ' '.'@'.$mobile.'@'.' '.'@'.$location.'@'.' ';
		update_user_meta($user_id,'initdata',$initdata);
	}

	if((!is_wp_error( $user_id ) && !is_object($user_id )) || email_exists($email)){ 

		$admin = get_user_by( 'email', get_option(get_current_blog_id(), 'admin_email') );	

		$my_post = array(
			'post_title'    => 'Booking by '.$name.' for '.$tutor->display_name,
			'post_content'  => 'Booking by '.$name.' for '.$tutor->display_name,
			'post_status'   => 'publish',
			'post_author'	=> $admin->ID,	
			'post_type' => 'bookings',
			);
		$postid = wp_insert_post( $my_post, false);

		if($postid == 0){
			header("Location: ".get_post_permalink($classid));
			die();
		}
		update_post_meta($postid, 'bookname', $name);
		update_post_meta($postid, 'bookmobile', $mobile);
		update_post_meta($postid, 'bookemail', $email);
		update_post_meta($postid, 'booklocation', $location);
		update_post_meta($postid, 'bookgender', $gender);
		update_post_meta($postid, 'booktutorid', $tutorid);
		update_post_meta($postid, 'bookclassid', $classid);
		update_post_meta($postid, 'bookfees', $fees);
		update_post_meta($postid, 'bookrejected', "false");
		update_post_meta($postid, 'bookstatus', "false");
		update_post_meta($postid, 'bookpaystatus', "false");
	}
	
}

function skm_payment_success() {
	global $classid, $postid;
	header('Location: '.get_site_url().'/wp-content/themes/listify/afterbook.php?classid='.$classid.'&postid='.$postid.'&payu_success=true');
} 
function skm_payment_failure() {
	global $classid, $postid; 
	header('Location: '.get_site_url().'/wp-content/themes/listify/afterbook.php?classid='.$classid.'&postid='.$postid.'&payu_success=false');
}

//skm_payment_success();

$posted = array();
$posted['key'] = '1o242B';
$posted['txnid'] = substr(hash('sha256', mt_rand() . microtime()), 0, 20);
$posted['amount'] = $fees;
$posted['firstname'] = $namesplit[2];
$posted['email'] = $email;
$posted['phone'] = $mobile;
$posted['productinfo'] = 'TipTapGo Class Booking';
$posted['surl'] = get_site_url().'/wp-content/themes/listify/afterbook.php?classid='.$classid.'&postid='.$postid.'&payu_success=true';
$posted['furl'] = get_site_url().'/wp-content/themes/listify/afterbook.php?classid='.$classid.'&postid='.$postid.'&payu_success=false';
$posted['service_provider'] = 'TipTapGo';
$posted['SALT'] = 'nSzfx5WS';
$SALT = "nSzfx5WS";

$hash = '';
$hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|||||";
$hashVarsSeq = explode('|', $hashSequence);
$hash_string = '';	
foreach($hashVarsSeq as $hash_var) {
	$hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
	$hash_string .= '|';
}

$hash_string .= $SALT;
$hash = strtolower(hash('sha512', $hash_string));
$posted['hash'] = $hash;
$PAYU_BASE_URL = "https://secure.payu.in";
$action = $PAYU_BASE_URL . '/_payment';
?>

<html>
  <head>
  <script>
    var hash = '<?php echo $hash ?>';
    function submitPayuForm() {
      if(hash == '') {
        return;
      }
      var payuForm = document.forms.payuForm;
      payuForm.submit();
    }
  </script>
  </head>
  <body onload="submitPayuForm()">
    <form action="<?php echo $action; ?>" method="post" name="payuForm">
      <input type="hidden" name="key" value="<?php echo $posted['key'] ?>">
      <input type="hidden" name="hash" value="<?php echo $hash ?>">
      <input type="hidden" name="txnid" value="<?php echo $posted['txnid'] ?>">
      <input type="hidden" name="amount" value="<?php echo (empty($posted['amount'])) ? '' : $posted['amount'] ?>">
      <input type="hidden" name="firstname" id="firstname" value="<?php echo (empty($posted['firstname'])) ? '' : $posted['firstname']; ?>" />
      <input type="hidden" name="email" id="email" value="<?php echo (empty($posted['email'])) ? '' : $posted['email']; ?>">
      <input type="hidden" name="phone" value="<?php echo (empty($posted['phone'])) ? '' : $posted['phone']; ?>">
      <input type="hidden" name="productinfo" value="<?php echo (empty($posted['productinfo'])) ? '' : $posted['productinfo'] ?>">
      <input type="hidden" name="surl" value="<?php echo (empty($posted['surl'])) ? '' : $posted['surl'] ?>">
      <input type="hidden" name="furl" value="<?php echo (empty($posted['furl'])) ? '' : $posted['furl'] ?>">
      <input type="hidden" name="service_provider" value="payu_paisa">
      <input style="display: none;" type="submit" value="Submit">
    </form>
  </body>
</html>