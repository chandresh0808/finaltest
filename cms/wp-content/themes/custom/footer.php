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

<footer class="pad-t40 pad-b20" role="contentinfo">
	<div id="footer-content" class="container pad-t10 pad-b10 font-small">
		<div class="row">
			<?php dynamic_sidebar( 'extended-footer' ); ?>
		</div><!-- .row -->

		<div class="row">
		
		
			 <div class="col-lg-2 col-sm-4 col-lg-push-6 pad-b20 font-size-small">
				<?php 
				 $args = array('theme_location' => 'footer_left', 
				 'container_class' => 'footer-nav', 
				 'menu_class' => 'footer-nav', 
				 'fallback_cb' => '', 
				  'menu_id' => 'footer-menu',
				  'walker' => new Cyber_DC_Walker_Nav_Menu()); 
				  wp_nav_menu($args);
				  ?>
				  </div>
				  
			    <!-- display footer2 menu -->
			    <div class="col-lg-2 col-sm-4 col-lg-push-6 pad-b20 font-size-small">
				<?php 
				 $args = array('theme_location' => 'footer_right', 
				 'container_class' => 'footer-nav', 
				 'menu_class' => 'footer-nav', 
				 'fallback_cb' => '', 
				  'menu_id' => 'footer-menu-right',
				  'walker' => new Cyber_DC_Walker_Nav_Menu()); 
				  wp_nav_menu($args);
				  ?>
				</div>
				
				<!-- display footer3 menu -->
			    <div class="col-lg-2 col-sm-4 col-lg-push-6 pad-b20 font-size-small">
				<?php 
				 $args = array('theme_location' => 'footer_last', 
				 'container_class' => 'footer-nav', 
				 'menu_class' => 'footer-nav', 
				 'fallback_cb' => '', 
				  'menu_id' => 'footer-menu-last',
				  'walker' => new Cyber_DC_Walker_Nav_Menu()); 
				  wp_nav_menu($args);
				  ?>
				</div>
		
				<?php $class = ( is_active_sidebar( 'extended-footer' ) ) ? ' active' : ''; ?>
				<span class="line<?php echo $class; ?>"></span>
				<div class="col-lg-3 col-sm-6 pad-b20 col-lg-pull-6"><a id="site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><img src="<?php echo site_url();?>/wp-content/themes/custom/images/Audit-Footer_Logo.png"></a>
				<p class="footercopyright pad-t20">&copy; <?php echo date( 'Y' ); ?>, Audit Companion.<br> All rights reserved.</p>
				</div>
			    
			    <div class="col-lg-3 col-sm-6 col-lg-pull-6 pad-b20"><?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('footer_contact') ) : endif; ?></div>
				<!-- display footer1 menu -->
				
			
				
				
		 
				
			
		</div><!-- .row -->
	</div><!-- #footer-content.container -->
</footer><!-- #footer -->



<?php wp_footer(); ?>
</body>
</html>