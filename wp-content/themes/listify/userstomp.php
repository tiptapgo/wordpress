<?php
/**
 * Template Name: yoastupdate
 *
 * @package Listify
 */

require_once("../../../wp-load.php");

get_header();
?>
<aside class="widget">
	<?php

	$blogusers = get_users( 'blog_id='.get_current_blog_id().'&orderby=nicename&role=employer' );
	//echo '<div>$name,$first_name, $last_name, $username , $email, $password, $phone, $profile_type, $user_type, $gender, $id, $email_verified, $phone_verified, $profile<br>';	
	foreach ( $blogusers as $user ) {
		$id = $user->ID;

		$args1 = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
			'post_type'           => 'job_listing',
			'post_status'         => array( 'publish', 'expired', 'pending' ),
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => 10,
			'orderby'             => 'date',
			'order'               => 'desc',
			'author'              => $id
			) );
		$listid = " ";
		$jobs = new WP_Query;
		$jobs->query( $args1 );
		while ( $jobs->have_posts() ) {
			$jobs->the_post();
			if(get_post_meta((int)get_the_ID(),'_mobile_num',true)!=''){
				$listid = get_the_ID();
				if(get_post_status( $listid ) == 'publish'){
					update_post_meta($listid,'_claimed',true);
				}
			}
		}

		/*$password1 = " ";
		$password1 = substr($user->user_login, 0, 5);
		$password1 = 'ttg'.$password1;
		wp_set_password( $password1, $id );		
		$args1 = apply_filters( 'job_manager_get_dashboard_jobs_args', array(
			'post_type'           => 'job_listing',
			'post_status'         => array( 'publish', 'expired', 'pending' ),
			'ignore_sticky_posts' => 1,
			'posts_per_page'      => 10,
			'orderby'             => 'date',
			'order'               => 'desc',
			'author'              => $id
			) );
		$listid = " ";
		$jobs = new WP_Query;
		$jobs->query( $args1 );
		while ( $jobs->have_posts() ) {
			$jobs->the_post();
			if(get_post_meta((int)get_the_ID(),'_mobile_num',true)!=''){
				$listid = get_the_ID();
			}
		}

		$name=" ";
		$name = get_post_meta((int)$listid,'_tutor_name', true);
		if($name ==''){
			$name = $user->display_name;
		}
		preg_match('#^(\w+\.)?\s*([\'\’\w]+)\s+([\'\’\w]+)\s*(\w+\.?)?$#', $name, $namesplit);

		if($namesplit[2] == '') {
			$namesplit[2] = $name;
			$namesplit[3] = '';
		}
		$phone=" ";
		$phone = get_cimyFieldValue($id,'MOBILE');
		if($phone == ''){
			$phone = get_post_meta((int)$listid,'_mobile_num',true);
		}
		$gender=" ";
		$gender = get_post_meta((int)$listid,'_listing_gender', true);
		if($gender == ''){
			$temp = get_cimyFieldValue($id,'MALE');
			if ($temp == "YES"){
				$gender = "Male";
			}
			$temp = get_cimyFieldValue($id,'FEMALE');
			if ($temp == "YES"){
				$gender = "Female";
			}
		}
		echo $name.','.$namesplit[2].','.$namesplit[3].','.$user->user_login.','.$user->user_email.','.$password1.','.$phone.',Wordpress,Tutor,'.strtolower($gender).','.$id.', true, true, http://tiptapgo.co/tutor/'.$user->user_login.'<br>';*/
	}
	//echo '</div>';
	?>
</aside>
<?php
get_footer();
?>