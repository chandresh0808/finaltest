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
				<div><a id="site-title" class="navbar-brand" style="color:#<?php header_textcolor(); ?>;" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"  class="navbar-brand"><img src="<?php echo site_url();?>/wp-content/themes/custom/images/Audit_Logo.png"></a></div>
			    <div>&copy; <?php echo date( 'Y' ); ?>,DataMatrixSystems. All rights reserved.</div>
			    <div><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_contact') ) : endif; ?></div>
				<!-- display footer1 menu -->
				<?php 
				 $args = array('theme_location' => 'footer_left', 
				 'container_class' => 'navbar-collapse collapse navbar-right', 
				 'menu_class' => 'nav navbar-nav footer-left	-menu', 
				 'fallback_cb' => '', 
				  'menu_id' => 'footer-menu',
				  'walker' => new Cyber_DC_Walker_Nav_Menu()); 
				  wp_nav_menu($args);
				  ?>
				  
			    <!-- display footer2 menu -->
				<?php 
				 $args = array('theme_location' => 'footer_right', 
				 'container_class' => 'navbar-collapse collapse navbar-right', 
				 'menu_class' => 'nav navbar-nav footer-right-menu', 
				 'fallback_cb' => '', 
				  'menu_id' => 'footer-menu-right',
				  'walker' => new Cyber_DC_Walker_Nav_Menu()); 
				  wp_nav_menu($args);
				  ?>
				
				
				
				
			</div><!-- .col-lg-12 -->
		</div><!-- .row -->
	</div><!-- #footer-content.container -->
</footer><!-- #footer -->

<?php wp_footer(); ?>
</body>
</html>