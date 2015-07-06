<?php 
/**
 * Rating form template for star rating
 */
?>
<div class="col-md-6 col-sm-6 col-lg-6 col-xs-6">
	<?php
	$include_minus = apply_filters( 'mr_rating_form_include_minus', true);
	?>

	<span class="mr-star-rating mr-star-rating-select"><?php
		
	// add star icons
		$index = 0;
		for ( $index; $index <= $max_option_value; $index++ ) {
			
			if ( $index == 0 && $include_minus ) {
				$class = $icon_classes['minus'] . ' index-' . $index . '-' . $element_id;
				?>
				<i id="index-<?php echo $index; ?>-<?php echo $element_id; ?>" class="<?php echo $class; ?>"></i>
				<?php
				continue;
			}
			
			$class = $icon_classes['star_full'];

		// if default value is less than the current index, it must be empty
			if ( $default_option_value < $index ) {
				$class = $icon_classes['star_empty'];
			}
			
			$id = 'index-' . $index . '-' . $element_id;
			$class .= ' index-' . $index . '-' . $element_id;
			
			?>
			<i title="<?php echo esc_attr( $index ); ?>" id="<?php echo $id; ?>" class="<?php echo $class; ?>"></i>
			<?php
		}
		?>
	</span>	
</div>

<!-- hidden field for storing selected star rating value -->
<input type="hidden" name="<?php echo $element_id; ?>" id="<?php echo $element_id; ?>" value="<?php echo $default_option_value; ?>">
