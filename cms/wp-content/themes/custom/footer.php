<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main, .grid and #page div elements.
 *
 * @since 1.0
 */
$custom_theme_options = custom_theme_options();
		/* Do not display sidebars if full width option selected on single
		   post/page templates */
		if ( is_custom_full_width() ) {
			if ( 5 != $custom_theme_options['layout'] )
				get_sidebar();
			get_sidebar( 'second' );
		}
		?>
		</div> <!-- .row -->
	</div> <!-- #main -->
</div> <!-- #page -->

<footer id="footer" role="contentinfo">
	<div id="footer-content" class="container">
		<div class="row">
			<?php dynamic_sidebar( 'extended-footer' ); ?>
		</div><!-- .row -->

		<div class="row">
			<div class="col-lg-12">
				<?php $class = ( is_active_sidebar( 'extended-footer' ) ) ? ' active' : ''; ?>
				<span class="line<?php echo $class; ?>"></span>
				<span class="pull-left">Copyright &copy; <?php echo date( 'Y' ); ?> <a href="<?php echo esc_url( home_url() ); ?>"><?php bloginfo( 'name' ); ?></a>. All Rights Reserved.</span>
				<!--<span class="credit-link pull-right"><?php printf( __( '%s created by %s.', 'custom' ), CYBER_DC_custom, '<a href="http://cyberdesigncraft.com/custom/">Cyberdesign Craft</a>' ); ?></span>-->
			</div><!-- .col-lg-12 -->
		</div><!-- .row -->
	</div><!-- #footer-content.container -->
</footer><!-- #footer -->

<?php wp_footer(); ?>
</body>
</html>