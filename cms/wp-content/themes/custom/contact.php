<?php
/*
Template Name: contact
*/
$custom_theme_options = custom_theme_options();
get_header();
?>
	<div id="primary" <?php custom_primary_attr(); ?>>

		<?php while ( have_posts() ) : the_post(); ?>
			<div class="container">
			
			<div class="col-lg-8 col-sm-12 col-md-8">
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<h1 class="entry-title"><?php the_title(); ?></h1>

					    <div class="entry-content">
						    <?php the_content( __( 'Read more &#133;', 'custom' ) ); ?>
					    </div><!-- .entry-content -->

					    <?php get_template_part( 'content', 'footer' ); ?>
				</article><!-- #post-<?php the_ID(); ?> -->
				<?php
				//comments_template( '', true );
				?>
			</div>
			<?php
		endwhile; // end of the loop.
		?>

	<div class="col-lg-4 col-sm-3 col-md-4">
	 <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('contact_support') ) : endif; ?>
	 <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('contact_address') ) : endif; ?>
	</div>
	</div>

<?php get_footer(); ?>