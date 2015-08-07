<?php
/**
 * The template part for displaying a message that posts cannot be found.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Listify
 */
?>

<style type="text/css">
#post-0{
	height:600px;
	margin:0;
	position: relative;
}
.nfimg{
	position: absolute;
	top: 50%;
	left: 50%;
	-ms-transform: translate(-50%, -50%);
	-moz-transform: translate(-50%, -50%);
	-webkit-transform: translate(-50%, -50%);
			transform: translate(-50%, -50%);
	height: 200px;
	width: 200px;
}
.top-text{
	text-align:center;
	font-style: italic;
	font-size:16px;
}
.content-box-inner{
	padding:0;
}
.btndiv{
	width: 100%;
	text-align: center;
	position: absolute;
	bottom: 50px;
	margin-left: -36px;
}
</style>
<div class="col-md-3 col-sm-2 col-xs-12 col-lg-3"></div>
<div class="col-md-6 col-sm-8 col-xs-12 col-lg-6">
	<article id="post-0" class="hentry content-box content-box-wrapper no-results not-found">
		<div class="content-box-inner">
			<div class="top-text">
				All that is gold does not glitter,<br>
				Not all those who wander are lost.<br><br>
				- J.R.R Tolkein
			</div>
			<div class="nfimg"><img src="<?php echo get_template_directory_uri();?>/images/404.png" alt="Elephant not found" height="200" width="200"></div>
			<div class="btndiv"><a class="button" href="http://tiptapgo.co/">Return to homepage</a></div>
		</div>
	</article>
</div>
<div class="col-md-3 col-sm-2 col-xs-12 col-lg-3"></div>