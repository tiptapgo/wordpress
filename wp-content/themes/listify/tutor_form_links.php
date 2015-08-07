<?php
/**
 * Template Name: Tutor Form links
 *
 * @package Listify
 */
?>
<?php get_header(); ?>

	<div id="primary" class="container">
		<div class="row content-area">
			<div class="content-box-inner">		
				<div class="entry-content">
					<form action="http://tiptapgo.co/wp-content/themes/listify/csv.php" method="post" enctype="multipart/form-data">
						<input type="file" name="fileToUpload" id="fileToUpload">
						<input class="btn btn-primary" type="submit" name="submit" id="submit">
					</form>
					<?php 
						session_start();
						$linkArr = $_SESSION['tutor_form'];
						$message = $_SESSION['message'];
						$result = $_SESSION['success'];
						$name = $_SESSION['nameArr'];
						echo '<h2>'.$message.'</h2>';
						if($result == true){
							$counter = 0;
							foreach ($linkArr as $key) {
								echo "</br><div class='tutor-links'><a class='button' href='".$key."' target='_blank'>Form link ".$name[$counter++]."</a></div></br>";
							}
						}
						session_unset(); 
						session_destroy(); 
					?>	
				</div>
			</div>				
		</div>
	</div>

<?php get_footer(); ?>