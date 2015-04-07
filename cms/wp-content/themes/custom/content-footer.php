<?php
/**
 * The template for displaying article footers
 *
 */
$custom_theme_options = custom_theme_options();
$display_categories = $custom_theme_options['display_categories'];

 	if ( ! empty( $display_categories ) && 'page' != get_post_type() ) { ?>
		<h3 class="post-category"><?php the_category( ', ' ) ?></h3>
		<?php } ?>
	<footer class="entry">
	    <?php
	    if ( is_single() ) wp_link_pages( array( 'before' => '<p id="pages">' . __( 'Pages:', 'custom' ) ) );
	    edit_post_link( __( '(edit)', 'custom' ), '<p class="edit-link">', '</p>' );
		if ( is_single() ) the_tags( '<p class="tags"><span>' . __( 'Tags:', 'custom' ) . '</span>', ' ', '</p>' );
	    ?>
	</footer><!-- .entry -->