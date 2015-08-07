<?php
global $wp_post_types;

$content = '
<style>
article{
	padding:60px 0 !important;
	background-color:#fff !important;
}
.elgo{
    border-radius: 50%;
    border: 2px solid #F0F8FF;
    background-color: #FFC107;
}
.ortext{
	margin: 10px 0;
	font-size:14px;
}

</style>
<div class="col-xs-12 col-lg-12 col-sm-12 col-md-12" style="text-align:center;font-size:20px">
	<div class="col-xs-12 col-lg-4 col-sm-4 col-md-4">
		<img class="img-responsive elgo" src="http://tiptapgo.co/wp-content/plugins/wp-job-manager/assets/images/elgo.png">	
	</div>
	<div class="col-xs-12 col-lg-8 col-sm-8 col-md-8">
	<strong>Hold tight! Your application is under review.</br>
	 We will get back to you about it\'s status shortly.</br>

		<p>In the meantime:</p>

<div style="padding: 15px 0; width:100%;"><a class="button btn1" href="https://www.facebook.com/tiptapgoco" style="background-color:#3b5998 !important;">Let your friends know about us on Facebook</a></div>

<div class="ortext">-- OR --</div>

<div style="padding: 15px 0; width:100%" ><a class="button btn2" href="http://tiptapgoco.tumblr.com/">Read interesting articles on our blog</a></div>

	</div>	
</div>
<script type="text/javascript">
$(function(){
    $(".btn2").each(function() {
        var $this = $(this);
	        $this.css({
            \'width\': $(".btn1").outerWidth() + \'px\'
                    });
	        /*Recalculate on window resize*/
        $(window).resize(function() {
	        $this.css({
            \'width\': $(".btn1").outerWidth() + \'px\'
        });
	        });
    });

});
</script>
';




switch ( $job->post_status ) :
	case 'publish' :
		printf( __( '%s listed successfully. To view your listing <a href="%s">click here</a>.', 'wp-job-manager' ), $wp_post_types['job_listing']->labels->singular_name, get_permalink( $job->ID ) );
	break;
	case 'pending' :
		echo $content;
	break;
	default :
		do_action( 'job_manager_job_submitted_content_' . str_replace( '-', '_', sanitize_title( $job->post_status ) ), $job );
	break;
endswitch;

do_action( 'job_manager_job_submitted_content_after', sanitize_title( $job->post_status ), $job );