<?php
/**
 * The Template for displaying all single posts.
 *
 * @since 1.0
 */
get_header(); ?>

	<div id="primary" <?php custom_primary_attr(); ?>>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'content', get_post_format() ); ?>

			<div id="posts-pagination">

				<div class="container">

					<h3 class="screen-reader-text"><?php _e( 'Post navigation', 'custom' ); ?></h3>

					<?php if ( 'attachment' == get_post_type( get_the_ID() ) ) { ?>

						<div class="previous pull-left"><?php previous_image_link( false, __( '&larr; Previous Image', 'custom' ) ); ?></div>

						<div class="next pull-right"><?php next_image_link( false, __( 'Next Image &rarr;', 'custom' ) ); ?></div>

					<?php } else { ?>

						<div class="previous pull-left"><?php previous_post_link( '%link', __( '&larr; %title', 'custom' ) ); ?></div>
						
						<div class="next pull-right"><?php next_post_link( '%link', __( '%title &rarr;', 'custom' ) ); ?></div>
					<?php } ?>
				</div>
			</div><!-- #posts-pagination -->


			<?php comments_template( '', true ); ?>

		<?php endwhile; // end of the loop. ?>

	</div>

<?php get_footer(); ?>